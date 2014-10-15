<?php
ini_set('soap.wsdl_cache_enabled', 0);
class ChannelAdvisor{
	var $client, $headers, $accountID, $DeveloperKey, $Password;

	function ChannelAdvisor() {
		global $OPTION;
		$this->accountID = $OPTION['wps_channel_advisor_profile_id'];
		$this->DeveloperKey = $OPTION['wps_channel_advisor_developer_key'];
		$this->Password = $OPTION['wps_channel_advisor_password'];
		if (!class_exists('soapclient')) {
			echo '<p style="color:#FF0000;">php_soap.dll extension isn\'t included in PHP.</p>';
			exit;
		}
	}

	function add_item($post_id) {
		global $wpdb;
		$item_data = $wpdb->get_row(sprintf("SELECT * FROM %sposts WHERE ID = %s", $wpdb->prefix, $post_id));

		$item_id = get_post_meta($post_id, 'ID_item', true);
		$item_price = get_post_meta($post_id, 'new_price', true);
		if (!$item_price) { $item_price = get_post_meta($post_id, 'price', true); }

		$post_brands = wp_get_post_terms($post_id, 'brand');
		if ($post_brands) { foreach($post_brands as $post_brand) { $item_brand = $post_brand->name; } }

		$post_selections = wp_get_post_terms($post_id, 'selection');
		if ($post_selections) { foreach($post_selections as $post_selection) { $item_condition = $post_selection->name; } }
		if (strpos($item_condition, 'Used')) { $item_condition = 'Used'; }

		$inventory = get_item_inventory($post_id, $item_id);

		$ImageList = array();
		$post_featured = get_post_thumbnail_id($post_id);
		if ($post_featured) {
			$ImageList['ImageInfoSubmit'][] = array('PlacementName' => $post_id, 'FilenameOrUrl' => get_post_thumb($post_featured));
		}
		$post_attachs = $wpdb->get_results(sprintf("SELECT * FROM %sposts WHERE post_parent = %s AND post_type = 'attachment' ORDER BY menu_order, ID", $wpdb->prefix, $post_id));
		if ($post_attachs) {
			foreach($post_attachs as $post_attach) {
				if ($post_featured != $post_attach->ID) {
					$ImageList['ImageInfoSubmit'][] = array('PlacementName' => $post_id, 'FilenameOrUrl' => get_post_thumb($post_attach->ID));
				}
			}
		}

		$data = array(
			'accountID' => $this->accountID,
			'item' => array(
				'Sku' => $item_id,
				'Title' => $item_data->post_title,
				'Description' => strip_tags($item_data->post_content),
				'Brand' => $item_brand,
				'Condition' => $item_condition,
				'DistributionCenterList' => array('DistributionCenterInfoSubmit' => array('DistributionCenterCode' => $this->get_dc_code(), 'Quantity' => $inventory)),
				'PriceInfo' => array('RetailPrice' => format_price($item_price)),
				'ImageList' => $ImageList
			)
		);
		$soapurl = 'https://api.channeladvisor.com/ChannelAdvisorAPI/v7/InventoryService.asmx?WSDL';
		return $this->request('additem', $data, $soapurl);
	}

	function update_inventory_and_price($post_id, $item_id = '') {
		global $wpdb;
		$inventory = get_item_inventory($post_id, $item_id);
		if (!$item_id) { $item_id = get_post_meta($post_id, 'ID_item', true); }
		$price = get_post_meta($post_id, 'new_price', true);
		if (!$price) { $price = get_post_meta($post_id, 'price', true); }
		$data = array(
			'accountID' => $this->accountID,
			'itemQuantityAndPrice' => array(
				'Sku' => $item_id,
				'DistributionCenterCode' => $this->get_dc_code(),
				'Quantity' => $inventory,
				'UpdateType' => 'Absolute',
				'PriceInfo' => array('RetailPrice' => round($price))
			)
		);
		$soapurl = 'https://api.channeladvisor.com/ChannelAdvisorAPI/v7/InventoryService.asmx?WSDL';
		return $this->request('inventory', $data, $soapurl, $item_id);
	}

	function submit_order($order_id) {
		global $OPTION, $wpdb;

		$order_info = $wpdb->get_row(sprintf("SELECT * FROM %swps_orders WHERE oid = %s", $wpdb->prefix, $order_id), ARRAY_A);
		$odata = $order_info;

		$shipp_info = array();
		if ($order_info['d_addr'] == 1) {
			$drow = $wpdb->get_row(sprintf("SELECT * FROM %swps_delivery_addr WHERE who = '%s'", $wpdb->prefix, $order_info['who']), ARRAY_A);
			foreach($drow as $rk => $rv) {
				$shipp_info['s_'.$rk] = $rv;
			}
		} else {
			$shipp_info['s_street'] = $order_info['street'];
			$shipp_info['s_town'] = $order_info['town'];
			$shipp_info['s_state'] = $order_info['state'];
			$shipp_info['s_zip'] = $order_info['zip'];
			$shipp_info['s_country'] = $order_info['country'];
			$shipp_info['s_f_name'] = $order_info['f_name'];
			$shipp_info['s_l_name'] = $order_info['l_name'];
			$shipp_info['s_telephone'] = $order_info['telephone'];
		}
		$odata = array_merge($odata, $shipp_info);

		$LineItemSKUList = array();
		$order_items = array('items' => array());
		$oitems = $wpdb->get_results(sprintf("SELECT * FROM %swps_shopping_cart WHERE order_id = %s ORDER BY cid", $wpdb->prefix, $order_id));
		if ($oitems) {
			foreach($oitems as $oitem) {
				if ($oitem->item_amount > 0) {
					$LineItemSKUList['OrderLineItemItem'][] = array(
						'LineItemType' => 'SKU',
						'LineItemID' => $oitem->postID,
						'SKU' => $oitem->item_id,
						'Title' => $oitem->item_name,
						'Quantity' => $oitem->item_amount,
						'UnitPrice' => round($oitem->item_price),
						'AllowNegativeQuantity' => false,
						'BuyerFeedbackRating' => '',
						'VATRate' => 0
					);
				}
			}
		}
		$LineItemInvoiceList = array();
		if ($odata['shipping_fee'] > 0) {
			$LineItemInvoiceList['OrderLineItemInvoice'][] = array('UnitPrice' => round($odata['shipping_fee']), 'LineItemType' => 'Shipping');
		}

		$data = array(
			'accountID' => $this->accountID,
			'order' => array(
				'OrderTimeGMT' => date("Y-m-d\TH:i:s", $odata['order_time'] + $OPTION['wps_time_addition']),
				'ClientOrderIdentifier' => $OPTION['wps_order_no_prefix'].$odata['oid'],
				'BuyerEmailAddress' => $odata['email'],
				'EmailOptIn' => false,
				'ResellerID' => $odata['user_id'],
				'BillingInfo' => array(
					'AddressLine1' => $odata['street'],
					'City' => $odata['town'],
					'Region' => $odata['state'],
					'PostalCode' => $odata['zip'],
					'CountryCode' => $this->get_country_code($odata['country']),
					'FirstName' => $odata['f_name'],
					'LastName' => $odata['l_name'],
					'PhoneNumberDay' => $odata['telephone']
				),
				'PaymentInfo' => array(
					'PaymentType' => $odata['p_option'],
					'PaymentTransactionID' => $odata['txn_id']
				),
				'ShippingInfo' => array(
					'AddressLine1' => $odata['s_street'],
					'City' => $odata['s_town'],
					'Region' => $odata['s_state'],
					'PostalCode' => $odata['s_zip'],
					'CountryCode' => $this->get_country_code($odata['s_country']),
					'FirstName' => $odata['s_f_name'],
					'LastName' => $odata['s_l_name'],
					'PhoneNumberDay' => $odata['s_telephone']
				),
				'ShoppingCart' => array(
					'CartID' => 0,
					'LineItemInvoiceList' => $LineItemInvoiceList,
					'LineItemSKUList' => $LineItemSKUList
				)
			)
		);
		$soapurl = 'https://api.channeladvisor.com/ChannelAdvisorAPI/v7/OrderService.asmx?WSDL';
		$request = $this->request('order', $data, $soapurl, $order_id);
		return $request;
	}

	function get_auth_list() {
		$soapurl = 'https://api.channeladvisor.com/ChannelAdvisorAPI/v7/AdminService.asmx?WSDL';
		return $this->request('authlist', $data, $soapurl);
	}

	function ping() {
		$soapurl = 'https://api.channeladvisor.com/ChannelAdvisorAPI/v7/AdminService.asmx?WSDL';
		return $this->request('ping', $data, $soapurl);
	}

	function request($type, $data, $soapurl, $keyparam = '') {
		//var_dump($data); exit;
		$this->client = new SoapClient($soapurl, array('trace' => 1, 'exception' => 0));
		$soap_header = new SoapHeader(
			'http://api.channeladvisor.com/webservices/',
			'APICredentials',
			array(
				'DeveloperKey' => $this->DeveloperKey,
				'Password' => $this->Password
			)
		);
		$this->client->__setSoapHeaders($soap_header);
		try {
			switch ($type) {
				case "additem":
					$result = $this->client->SynchInventoryItem($data);
					$status = $result->SynchInventoryItemResult->Status;
					$message_code = $result->SynchInventoryItemResult->MessageCode;
					$rdata = $result->SynchInventoryItemResult->ResultData;
				break;
				case "inventory":
					$result = $this->client->UpdateInventoryItemQuantityAndPrice($data);
					$status = $result->UpdateInventoryItemQuantityAndPriceResult->Status;
					$message_code = $result->UpdateInventoryItemQuantityAndPriceResult->MessageCode;
					$rdata = $result->UpdateInventoryItemQuantityAndPriceResult->ResultData;
				break;
				case "order":
					$result = $this->client->SubmitOrder($data);
					$status = $result->SubmitOrderResult->Status;
					$message_code = $result->SubmitOrderResult->MessageCode;
					$rdata = $result->SubmitOrderResult->ResultData;
				break;
				case "authlist":
					$result = $this->client->GetAuthorizationList();
					$status = $result->GetAuthorizationListResult->Status;
					$message_code = $result->GetAuthorizationListResult->MessageCode;
					$rdata = $result->GetAuthorizationListResult->ResultData;
				break;
				case "ping":
					$result = $this->client->Ping();
					$status = $result->PingResult->Status;
					$message_code = $result->PingResult->MessageCode;
					$rdata = $result->PingResult->ResultData;
				break;
			}
		} catch (SoapFault $e) {
			$status = 'SoapFault Error';
			$rdata = $e->getMessage();
		} catch (Exception $e) {
			$status = 'Exception Error';
			$rdata = $e->getMessage();
		}
		//var_dump($this->client->__getLastRequest());
		$this->create_log($keyparam, $status, $rdata, $data);
		return array('status' => $status, 'code' => $message_code, 'rdata' => $rdata);
	}

	function create_log($keyparam, $status, $rdata, $data) {
		global $wpdb;
		$insert = array();
		$insert['log_date'] = current_time('mysql');
		$insert['key_param'] = $keyparam;
		$insert['status'] = $status;
		$insert['result'] = serialize($rdata);
		$insert['data'] = serialize($data);
		$wpdb->insert($wpdb->prefix."wps_channel_advisor_logs", $insert);
	}

	function array2xml($arrData, $level) {
		$xml = '';
		foreach( $arrData as $key => $value ) {
			$spacer = '';
			for ($i = 0; $i < $level; $i++) { $spacer .= "\t"; }
			if (is_array($value)) {
				if (preg_match("/^(.+)___\d+$/", $key, $m)) {
					$xml .= $spacer . "<" . $m[1] . ">\n" . $this->array2xml($value, $level+1) . $spacer . "</" . $m[1] . ">\n";
				} else {
					$xml .= $spacer . "<" . $key . ">\n" . $this->array2xml($value, $level+1) . $spacer . "</" . $key . ">\n";
				}
			} else {
				$xml .= $spacer . "<" . $key . ">" . $value . "</" . $key . ">\n";
			}
		}
		return $xml;
	}

	function get_country_code($c) {
		global $wpdb;
		return $wpdb->get_var(sprintf("SELECT abbr FROM %swps_countries WHERE country = '%s'", $wpdb->prefix, $c));
	}

	function get_dc_code() {
		return 'Al Sufou 1';
	}
}

add_action('init', 'ca_hivista_init');
function ca_hivista_init() {
	global $wpdb;
	if ($_GET['hivista'] == 'chadv') {
		$chadv = new ChannelAdvisor();

		//$chadv->add_item(9662);

		$chadv->update_inventory_and_price(18896, 'LC-220113-04-02');
		$chadv->update_inventory_and_price(9662, 'LV-JAML-DNM-MI0075');

		$chadv->submit_order(2001);

		//$chadv->get_auth_list();

		//$chadv->ping();

		exit;
	}
}
?>