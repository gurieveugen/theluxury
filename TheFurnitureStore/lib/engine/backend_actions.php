<?php
##################################################################################################################################
//												BACKEND-FUNCTIONS																			
##################################################################################################################################

	function make_section_header($current){
		
		global $CONFIG_WPS,$OPTION;

		$links 				 = array();
		$links['settings']	 = '?page=functions.php';
		$links['orders']  	 = '?page=functions.php&section=orders';
		$links['inquiries']  = '?page=functions.php&section=inquiries';
		$links['inventory']  = '?page=functions.php&section=inventory';
		$links['lkeys']  	 = '?page=functions.php&section=lkeys';
		$links['statistics'] = '?page=functions.php&section=statistics';
		$links['vouchers']	 = '?page=functions.php&section=vouchers';
		$links['members']	 = '?page=functions.php&section=members';
		$links['pricing']	 = '?page=functions.php&section=pricing';
		$links['searches']	 = '?page=functions.php&section=searches';
		$links['logs']		 = '?page=functions.php&section=logs';

		$css_class = array();
		foreach($links as $k => $v) {
			$css_class[$k] = 'class="inactive"';
			if($k == $current) {
				$css_class[$k] = 'class="active"';
			}
		}

		$output2 = "<div class='wrap'>";
			$output2 .= "<ul class='tabs mainTabs'>";
				if(current_user_role() == 'editor') {
					if($OPTION['wps_shoppingCartEngine_yes']) {
						if($OPTION['wps_track_inventory']=='active'){
							$output2 .= "<li><a href='$links[inventory]' $css_class[inventory]>".__('Manage Inventory','wpShop')."</a></li>";
						}
					}
				} else {
					$output2 .= "<li><a href='$links[settings]' $css_class[settings]>".__('Theme Options','wpShop')."</a></li>";
					//using the shopping cart?
					if($OPTION['wps_shoppingCartEngine_yes']) {
						if($OPTION['wps_shop_mode'] == 'Normal shop mode'){ 
							$output2 .=  "<li><a href='$links[orders]' $css_class[orders]>".__('Manage Orders','wpShop')."</a></li>";
						}elseif($OPTION['wps_shop_mode'] == 'Inquiry email mode'){
							$output2 .=  "<li><a href='$links[inquiries]' $css_class[inquiries] >".__('Manage Enquiries','wpShop')."</a></li>";
						}elseif($OPTION['wps_shop_mode'] == 'payloadz_mode'){
							$output2 .=  "<li><a href='https://www.payloadz.com/' target=_blank>".__('PayLoadz','wpShop')."</a></li>";
						}else {}
						
						// tracking inventory?
						if($OPTION['wps_track_inventory']=='active'){
							$output2 .= "<li><a href='$links[inventory]' $css_class[inventory]>".__('Manage Inventory','wpShop')."</a></li>";
						}
						// using License keys?
						$l_mode = $OPTION['wps_l_mode'];
						if(($l_mode == 'GIVE_KEYS')&&($OPTION['wps_shop_mode'] != 'payloadz_mode')&&($OPTION['wps_shop_mode'] != 'Inquiry email mode')){ 
							$output2 .= "<li><a href='$links[lkeys]' $css_class[lkeys] >".__('Upload L-Keys','wpShop')."</a></li>";
						}
						// using vouchers?
						if ($OPTION['wps_voucherCodes_enable']) {
							$output2 .= "<li><a href='$links[vouchers]' $css_class[vouchers] >".__('Vouchers','wpShop')."</a></li>";
						}
					}
					// using a membership area?
					if($OPTION['wps_lrw_yes']) {
						$output2 .= "<li><a href='$links[members]' $css_class[members] >".__('Members','wpShop')."</a></li>";
					}
					//using the shopping cart?
					if($OPTION['wps_shoppingCartEngine_yes']) {
						$output2 .= "<li><a href='$links[statistics]' $css_class[statistics] >".__('Statistics','wpShop')."</a></li>";
					}
					$output2 .= "<li><a href='$links[pricing]' $css_class[pricing] >".__('Pricing','wpShop')."</a></li>";
					$output2 .= "<li><a href='$links[searches]' $css_class[searches] >".__('Searches','wpShop')."</a></li>";
					$output2 .= "<li><a href='$links[logs]' $css_class[logs] >".__('Logs','wpShop')."</a></li>";
				}
			$output2 .= "</ul>";
		
		return $output2;
	}

	function make_section_staff_header($current)
	{
		global $CONFIG_WPS,$OPTION;
		$currrole = current_user_role();

		$links 				 = array();
		$links['statistics'] = __('Statistics','wpShop');
		$links['orders']  	 = __('Manage Orders','wpShop');
		if($currrole == 'buyer') {
			$links['inventory']  = __('Manage Inventory','wpShop');
		}
		$links['pricing']	 = __('Pricing','wpShop');
		
		$output = '<div class="wrap"><ul class="tabs mainTabs">';
		foreach($links as $lk => $lv) {
			$ac = '';
			if ($lk == $current) { $ac = ' class="active"'; }
			$output .= '<li><a href="admin.php?page=functions.php&section='.$lk.'"'.$ac.'>'.$lv.'</a></li>';
		}
		$output .= '</ul>';
		return $output;
		

	}

	function make_section_footer(){
		$output 	= "</div></div></div></div>";
		$output2 	= "</div>";
	return $output2;
	}



//////////////////////////////////////////////// ORDERS ///////////////////////////////////////////////////////
	function classify_orders($res)
	{
		$odata	= array();

		while($order = mysql_fetch_assoc($res)) {
			switch($order[level]){
				// cancelled
				case '0':
					$order[items] = show_orders($order[who]);
					$odata[7][]	  = $order;
				break; 
				// layaway
				case '3':
					$order[items] = show_orders($order[who]);
					$odata[6][]	  = $order;
				break; 
				// new
				case '4':
					$order[items] = show_orders($order[who]);
					$odata[1][]	  = $order;
				break; 
				// shipped
				case '5':
					$order[items] = show_orders($order[who]);
					$odata[2][]	  = $order;
				break; 
				// received
				case '6':
					$order[items] = show_orders($order[who]);
					$odata[3][]	  = $order;
				break; 
				// completed
				case '7':
					$order[items] = show_orders($order[who]);
					$odata[4][]	  = $order;
				break; 
				// pending
				case '8':
					$order[items] = show_orders($order[who]);
					$odata[5][]	  = $order;
				break; 
			}
		}
		return $odata;
	}

	function returned_order() {
		global $OPTION, $wpdb, $current_user; 
		$table = is_dbtable_there('orders');
		$table2 = is_dbtable_there('shopping_cart');
		$table3 = is_dbtable_there('inventory');
		$order_id = $_REQUEST['pop_rt_order_id'];

		$order_data = $wpdb->get_row(sprintf("SELECT * FROM %s WHERE oid = %s", $table, $order_id));
		if($order_data) {
			$order_who = $order_data->who;
			$order_tax = $order_data->tax;
			$order_voucher = $order_data->voucher_amount;
			$order_delivery = $order_data->d_option;
			$order_country = get_order_shipping_country($order_id);
			$return_flag = false;

			// select order items
			$inventory_items = array();
			$orderitems = $wpdb->get_results(sprintf("SELECT * FROM %s WHERE who = '%s'", $table2, $order_who));
			if ($orderitems) {
				foreach($orderitems as $orderitem) {
					$cid = $orderitem->cid;
					$return_qty = (int)$_REQUEST['pop_item_rtqty_'.$cid];
					if ($return_qty > 0) {
						log_action('order_return', 'Order ID: '.$order_id.'; Item ID: '.$orderitem->item_id.'; Qty: '.$orderitem->item_amount.'; Returned Qty: '.$return_qty.'; User ID: '.$current_user->ID);

						if ($return_qty > $orderitem->item_amount) { $return_qty = $orderitem->item_amount; }

						$wpdb->query(sprintf("UPDATE %s SET item_amount = item_amount - %s WHERE cid = %s", $table2, $return_qty, $cid));

						// order amounts
						$ret_amount = $orderitem->item_price * $return_qty;
						$order_net = $order_net - $ret_amount;
						$order_amount = $order_amount - $ret_amount;

						// update inventory
						update_item_inventory($orderitem->item_id, $return_qty, false, 'Return order - oid = '.$order_id);

						$return_flag = true;
					}
				}
			}
			// update order amounts
			if ($return_flag) {
				$order_net = 0;
				$total_weight = 0;
				$total_items = 0;
				$delete_flag = true;
				$orderitems = $wpdb->get_results(sprintf("SELECT * FROM %s WHERE who = '%s' AND item_amount > 0", $table2, $order_who));
				if ($orderitems) {
					$delete_flag = false;
					foreach($orderitems as $orderitem) {
						$order_net += $orderitem->item_price * $orderitem->item_amount;
						$total_weight += $orderitem->item_weight;
						$total_items++;
					}
					$order_shipping = calculate_shipping($order_delivery, $order_net, $total_weight, $total_items, $order_country);
					$order_amount = ($order_net - $order_voucher) + $order_shipping + $order_tax;
					$wpdb->query(sprintf("UPDATE %s SET net = '%s', amount = '%s', shipping_fee = '%s' WHERE oid = %s", $table, $order_net, $order_amount, $order_shipping, $order_id));

					// regenerate pdf file
					$order = mysql_fetch_assoc(mysql_query("SELECT * FROM $table WHERE oid = '$order_id' LIMIT 1"));
					$INVOICE = load_what_is_needed('invoice');
					$INVOICE->make_pdf($order);
				}
				// cancel order if returned all items
				if ($delete_flag) {
					total_order_delete($order_id, false);
				}
			}
		} // if $order_data
	}

	function multi_change_order_level(){
		global $wpdb;
		$table = is_dbtable_there('orders');						
		$table2 = is_dbtable_there('shopping_cart');

		foreach($_POST as $order_id => $act){	
			if($act == 'move') {
				$status = trim($_POST['status']);
				
				if ($status == 'delete' || $status == 0) {
					total_order_delete($order_id);
				} else {
					$wpdb->query(sprintf("UPDATE %s SET level = '%s' WHERE oid = %s", $table, $status, $order_id));
					
					// if payment received then send order email if product owner is seller
					if($status == '6') {
						$order_products = $wpdb->get_results(sprintf("SELECT * FROM %s WHERE order_id = '%s'", $table2, $order_id));
						if ($order_products) {
							foreach($order_products as $order_product) {
								sellers_send_order_email($order_product->postID, $order_product->item_id);
							}
						}
					}
					$ola_vals = array('4' => 'new_level', '5' => 'shipped_level', '6' => 'received_level', '7' => 'completed_level');
					order_log_action($order_id, $ola_vals[$status]);
				}
			}
		}
		return true;
	}

	function total_order_delete($order_id, $inventory_return = true, $action = '') {
		global $wpdb, $current_user;
		$cancel_reason = $_POST['cancel_reason'];
		$table = is_dbtable_there('orders');
		$table2 = is_dbtable_there('shopping_cart');

		$order = $wpdb->get_row(sprintf("SELECT * FROM %s WHERE oid = %s", $table, $order_id));
		if ($order->level != 0) {
			if ($inventory_return) {
				$sc_items = $wpdb->get_results(sprintf("SELECT * FROM %s WHERE order_id = '%s'", $table2, $order_id));
				if (!$sc_items && $order->layaway_process == 1) {
					$moid = $wpdb->get_var(sprintf("SELECT oid FROM %s WHERE layaway_order = '%s' AND oid < %s", $table, $order_id, $order_id));
					$sc_items = $wpdb->get_results(sprintf("SELECT * FROM %s WHERE order_id = '%s'", $table2, $moid));
				}
				if ($sc_items) {
					foreach($sc_items as $sc_item) {
						$cid = $sc_item->cid;
						$item_id = $sc_item->item_id;
						$item_amount = $sc_item->item_amount;

						// update inventory
						if ($item_amount > 0) {
							update_item_inventory($item_id, $item_amount, true, 'Total order delete - oid = '.$order_id);
						}

						// update shopping cart qty
						$wpdb->query(sprintf("UPDATE %s SET item_amount = item_amount - %s WHERE cid = %s", $table2, $item_amount, $cid));

						log_action('order_delete', 'Order ID: '.$order_id.'; Item ID: '.$item_id.'; Qty: '.$item_amount.'; User ID: '.$current_user->ID);
					}
				}
			}
			$wpdb->query(sprintf("UPDATE %s SET level = '0', cancel_reason = '%s' WHERE oid = %s", $table, $cancel_reason, $order_id));
			order_log_action($order_id, 'cancelled_level');
		}
	}

	function order_return_popup_script()
	{
		$url = get_bloginfo('template_directory') . '/js/return_popup.js';
		echo '<script type="text/javascript" src="'. $url . '"></script>';
	}

	
	function display_order_entries($odata,$status,$date_format,$table_header,$table_footer,$empty_message){
		global $CONFIG_WPS,$OPTION,$wpdb,$current_user;
		$otable = is_dbtable_there('orders');
		$INVOICE 	= load_what_is_needed('invoice');	//change.9.10
		$base_url  	= '?page=functions.php&section=orders';
		if((count($odata[$status])) > 0)
		{	
			echo $table_header; 					
					if(pdf_usable_language()){ 			
						$inv_ending = '.pdf';
					} 
					else {
						$inv_ending = '.html';
					}
					$wps_time_addition = get_option('wps_time_addition');
			
					foreach($odata[$status] as $k => $order){
						
						$date 			= date($date_format,($order['order_time'] + $wps_time_addition)); 
						$style 			= ($status == 5 ? "style='background:coral;'" : NULL);
						$dl_sent_info 	= ($order[dlinks_sent] != 0 ? ' - last sent on: '.date("F j, Y, G:i",$order[dlinks_sent]) : NULL);			
						
						echo "
						<tr>
							<td $style><input type='checkbox' name='$order[oid]' value='move' /></td>
							<td $style id='torder_id'>".$OPTION['wps_order_no_prefix'].$order['oid']."</td> 
							<td $style>$date
							<input type='hidden' name='order_id' id='order_id' value='$order[oid]' />
							</td>
							<td $style>";
								echo address_format($order);
								if ($status == 4 && $current_user->ID == 1) {
									echo "<br/>".$order['email'];
								}
								echo "<br/><a href='mailto:$order[email]'>".__('Send email','wpShop')."</a>";
								if(strlen($order['telephone'])){
									echo "<br/>".__('Tel:','wpShop'). $order['telephone'];
								}
							echo "</td>";
							
							echo "<td $style>";
								if($order['d_addr'] == '1'){
									$delivery_addr = retrieve_delivery_addr('BE',$order);
									echo address_format($delivery_addr,'d-addr');
									if(strlen($delivery_addr['email'])){
										echo "<br/><a href='mailto:$delivery_addr[email]'>".__('Send email','wpShop')."</a>";
									}
									if(strlen($delivery_addr['telephone'])){
										echo "<br/>".__('Tel:','wpShop'). $delivery_addr['telephone'];
									}
								}
								echo "
							</td>
							<td $style>";
								if ($status == 6) {
									echo '$'.format_price($order['net']+$order['shipping_fee']+$order['tax']);
									echo '<br>Paid: ';
								}
								echo '$'.format_price($order['amount']);
							echo  "</td>
							<td $style id='items'>"; 
								//echo list_order_items($order[items]);
								order_items_table($order[who]);
								if($status == 1) $return_link = '&nbsp;&nbsp;|&nbsp;&nbsp;<a class="return" href="#">Returned this Order?</a>';
								else $return_link = '';
								echo __('Track-ID:','wpShop').' '.$order['tracking_id'].$return_link;
								if ($status == 7 && $order['layaway_process'] == 1) {
									echo '&nbsp;&nbsp;<font style="color:#FF0000">Installment order</font>';
								}

								if(strlen($order['voucher']) && $order['voucher'] != 'non'){
									echo '<br />Voucher: <strong>'.$order['voucher'].'</strong>; Amount: $'.format_price($order['voucher_amount']).' redeemed.';
								}
								if(digital_in_cart($order[who]) === TRUE){
									echo "<a href = '?page=functions.php&section=orders&subsection=dlinks&token=$order[who]'>".__('Send D-Links','wpShop')."</a>$dl_sent_info";
								}	
								
							
							$invoice_id = NWS_encode($OPTION['wps_invoice_prefix'].'_'.$order['tracking_id'].$inv_ending);
							//change.9.10
							echo "</td>";
							// if layaway order
							if ($order['layaway_process'] == 1) {
								echo '<td colspan="2" '.$style.'>';
								$suborders = $wpdb->get_results(sprintf("SELECT * FROM %s WHERE layaway_order = %s AND tracking_id != '' AND tracking_id IS NOT NULL ORDER BY oid", $otable, $order['oid']));
								if (!$suborders){
									$suborders = $wpdb->get_results(sprintf("SELECT * FROM %s WHERE oid = %s AND tracking_id != '' AND tracking_id IS NOT NULL", $otable, $order['oid']));
								}
								if ($suborders) {
									echo '<table cellpadding="0" cellspacing="0" class="laorder">';
									foreach($suborders as $suborder) {
										$so_invoice_id = NWS_encode($OPTION['wps_invoice_prefix'].'_'.$suborder->tracking_id.$inv_ending);
										echo '<tr>';
										echo '<td><a href="'.get_option('home').'/index.php?display_invoice=1&invoice='.$so_invoice_id.'" target="_blank">'.$INVOICE->retrieve_invoice_no($suborder->oid).'</a></td>';
										echo '<td style="padding-left:3px;"><a href="admin.php?page=functions.php&section=orders&otab='.$_GET['otab'].'&opg='.$_GET['opg'].'&invoice=regenerate&oid='.$suborder->oid.'" title="Regenerate Invoice"><img src="'.TEMPLURL.'/images/regeninv.png"></a></td>';
										echo '<td>&nbsp;-&nbsp;</td>';
										echo '<td>'.date("d.m.Y",($suborder->order_time + $wps_time_addition)).'</td>';
										echo '<td>&nbsp;-&nbsp;</td>';
										echo '<td nowrap>';
										print_order_p_option($suborder->p_option, $suborder->txn_id);
										echo '</td></tr>';
									}
									echo '<tr><td colspan="5" style="border:none;"></td><td style="border:none;">D: '.$order[d_option].'</td></tr>';
									echo '</table>';
								}
								echo '</td>';
							} else {
								echo "<td $style>";
									echo '<table cellpadding="0" cellspacing="0"><tr>';
									echo "<td style='border:none;padding:0px;'><a href='".get_option('home')."/index.php?display_invoice=1&invoice=$invoice_id' target='_blank'>".$INVOICE->retrieve_invoice_no($order['oid'])."</a></td><td style='border:none;padding:0 0 0 3px;'><a href='admin.php?page=functions.php&section=orders&otab=".$_GET['otab']."&opg=".$_GET['opg']."&invoice=regenerate&oid=".$order['oid']."' title='Regenerate Invoice'><img src='".TEMPLURL."/images/regeninv.png'></a></td></tr></table>";
								echo "</td>";
								echo "<td $style>";
									print_order_p_option($order[p_option], $order[txn_id]);
									echo "D: $order[d_option]";
									if ($status == 7) {
										echo "<br><br>Cancel Reason:<br><strong>$order[cancel_reason]</strong>";
									}
								echo "</td>";
							}
							
							if($OPTION['wps_customNote_enable']) {
								echo "<td $style>$order[custom_note]</td>";	
							}
							
						echo "</tr>";
					}
					
			echo $table_footer;
		}
		else {
			echo $empty_message;
		}	
	}

	function order_items_table($who) {
		global $wpdb, $OPTION;
		$summary_page_url = get_permalink($OPTION['wps_sellers_summary_page']).'?seller=';
		?>
		<table>
			<thead>
				<tr>
					<th>Qty</th>
					<th>ID</th>
					<th>Item</th>
					<th>Unit</th>
					<th>Total</th>
					<th>Seller</th>
					<th>Price</th>
				</tr>
			</thead>
		<?php
		$order_items = $wpdb->get_results(sprintf("SELECT sc.*, p.post_author, u.user_login FROM %swps_shopping_cart sc LEFT JOIN %sposts p ON p.ID = sc.postID LEFT JOIN %susers u ON u.ID = p.post_author WHERE sc.who = '%s' ORDER BY sc.cid", $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $who));
		if ($order_items) {
			foreach($order_items as $order_item) {
				$item_seller = get_post_meta($order_item->postID, 'item_seller', true);
				$item_your_quotation_price = '';
				if (strlen($item_seller)) { $item_your_quotation_price = get_post_meta($order_item->postID, 'item_your_quotation_price', true); }
				?>
				<tr>
					<td id='item_qty'><?php echo $order_item->item_amount; ?>x</td>
					<td id='item_id'><?php echo $order_item->item_id; ?></td>
					<td id='item_name'><a href="<?php echo get_permalink($order_item->postID); ?>" target="_blank"><b><?php echo $order_item->item_name; ?></b></a></td>
					<td>$<?php echo format_price($order_item->item_price); ?></td>
					<td>$<?php echo format_price($order_item->item_amount * $order_item->item_price); ?></td>
					<td><?php if (strlen($item_seller)) { ?><a href="<?php echo $summary_page_url.$order_item->post_author; ?>"><?php echo $order_item->user_login; ?></a><?php } else { echo '&nbsp;'; } ?></td>
					<td><?php if (strlen($item_seller)) { echo '$'.format_price($item_your_quotation_price); } ?></td>
					<input type='hidden' name='cid' id='cid' value='<?php echo $order_item->cid; ?>' />
				</tr>
			<?php
			}
		} ?>
		</table>
		<?php
	}

	function display_order_pagination($opages, $otab) {
		$ourl = 'themes.php?page=functions.php&section=orders&otab='.$otab.'&opg=';
		$opg = $_GET['opg'];
		if (!$opg) { $opg = 1; }
		$prev_pg = $opg - 1;
		$next_pg = $opg + 1;
		if ($opages > 1) { ?>
			<div class="nws-orders-pagination">
				<?php if ($prev_pg > 0) { ?><span class="prev"><a href="<?php echo $ourl.$prev_pg; ?>"><< Prev</a></span><?php } ?>
				<?php for($p=1; $p<=$opages; $p++) { ?>
				<span class="page<?php if ($p == $opg) { echo ' active'; } ?>"><a href="<?php echo $ourl.$p; ?>"><?php echo $p; ?></a></span>
				<?php } ?>
				<?php if ($next_pg <= $opages) { ?><span class="next"><a href="<?php echo $ourl.$next_pg; ?>">Next >></a></span><?php } ?>
			</div>
		<?php
		}
	}

	function print_order_p_option($p_option, $txn_id) {
		global $OPTION, $order_payment_methods;
		echo 'P: ';
		switch($p_option){
			case 'paypal':
				echo "<a href='https://www.paypal.com/vst/id={$txn_id}' target='_blank' title='Check PayPal txn_id {$txn_id}'>".$order_payment_methods[$p_option]."</a>";
			break;
			case 'transfer':
				echo $order_payment_methods[$p_option]." <a href='http://".$OPTION['wps_online_banking_url']."' target='_blank' title='".__('Url to your Online Banking','wpShop')."'>".__('Check Account','wpShop')."</a>";													
			break; 
			default:
				echo $order_payment_methods[$p_option];
			break;
		}
		echo "<br/>";
	}

	function send_shipping_notification($oid){
		global $OPTION;	
		$EMAIL 	= load_what_is_needed('email');		//change.9.10
		$table 	= is_dbtable_there('orders');
		$qStr 	= "SELECT * FROM $table WHERE oid = $oid LIMIT 1";
		$res 	= mysql_query($qStr);
		$order 	= mysql_fetch_assoc($res);
		// we send customer a notification email about the shipping	
		$order_no				= $OPTION['wps_order_no_prefix'].$order[oid];  
		$to 					= $order[email];
		$subject_str			= __('Your Order %ORDER_OID% has been shipped','wpShop');						
		$subject 				= str_replace("%ORDER_OID%",$order_no,$subject_str);						

		if(strlen(WPLANG)< 1){  // shop runs in English 
			$filename			= WP_CONTENT_DIR . '/themes/'.WPSHOP_THEME_NAME.'/email/email-shipping.html';
		}
		else {					// shops runs in another language 
			$filename			= WP_CONTENT_DIR . '/themes/'.WPSHOP_THEME_NAME.'/email/'.WPLANG.'-email-shipping.html';
		}
		
		$message				= file_get_contents($filename);
		$admin_email_address	= $OPTION['wps_shop_email'];
		$domain					= $OPTION['wps_shop_name'];										

		$em_logo_path 	= get_bloginfo('template_directory') .'/images/logo/' . $OPTION['wps_email_logo'];	
		// might be that these char combos show up b'c image tag 
		$message		= str_replace('%5B','[', "$message");
		$message		= str_replace('%5D',']', "$message");
		
		$message		= str_replace('[##Email-Logo##]', $em_logo_path, "$message");									
		$message		= str_replace('[##biz##]',utf2latin($OPTION['wps_shop_name']), "$message");
		$message		= str_replace('[##name##]', $order[f_name].' '.$order[l_name] , "$message");					
		$message		= str_replace('[##orderid##]', $order_no, "$message");	
		$to = 'root@localhost.com';
		$EMAIL->html_mail($to,$subject,$message,$admin_email_address,$domain);		//change.9.10
	}
	
	
	
	function NWS_total_orders_there($option='all'){
		$totalOrders 	= array();
		$table 			= is_dbtable_there('orders');
		
		//total All
			$qStr 				= "SELECT * FROM $table WHERE level IN ('4','5','6','7','8')";
			$res 				= mysql_query($qStr);
			$num 				= mysql_num_rows($res);
			$totalOrders['all'] = $num;
		
		//Completed		
			$qStr 						= "SELECT * FROM $table WHERE level ='7'";
			$res 						= mysql_query($qStr);
			$num 						= mysql_num_rows($res);				
			$totalOrders['completed'] 	= $num;
			
		//Shipped		
			$qStr 						= "SELECT * FROM $table WHERE level ='6'";
			$res 						= mysql_query($qStr);
			$num 						= mysql_num_rows($res);				
			$totalOrders['shipped'] 	= $num;
			
		//In process		
			$qStr 						= "SELECT * FROM $table WHERE level ='5'";
			$res 						= mysql_query($qStr);
			$num 						= mysql_num_rows($res);				
			$totalOrders['in_process'] 	= $num;
			
		//New		
			$qStr 						= "SELECT * FROM $table WHERE level ='4'";
			$res 						= mysql_query($qStr);
			$num 						= mysql_num_rows($res);				
			$totalOrders['new'] 		= $num;
			
		//Pending		
			$qStr 						= "SELECT * FROM $table WHERE level ='8'";
			$res 						= mysql_query($qStr);
			$num 						= mysql_num_rows($res);				
			$totalOrders['pending'] 	= $num;
		
		
		return $totalOrders;
	}
	
	
	function NWS_total_earnings_there($option='all'){
		$totalEarnings 	= array();
		$table 			= is_dbtable_there('orders');
		
		//total All
			$qStr 					= "SELECT SUM(amount) AS totalEarnings FROM $table WHERE level IN ('4','5','6','7')";
			$res 					= mysql_query($qStr);
			$row 					= mysql_fetch_assoc( $res );				
			$totalEarnings['all'] 	= $row['totalEarnings'];
		
					
		//Total Year		
			// 1. what year now?
			$currentYear = date('Y');
			
			// 2. when did it start - time stamp | when did it end?
			$begin = mktime(0,0,1,1,1,$currentYear);
			$end   = mktime(23,59,59,12,31,$currentYear);
			
			#echo date("F j, Y, H:i:s",$begin); 
			#echo date("F j, Y, H:i:s",$end);
			
			
			// 3. mySql query with a where between clause 
			$begin 					= (string) $begin;
			$end 					= (string) $end;
			$qStr 					= "SELECT SUM(amount) as totalEarnings FROM $table WHERE (order_time BETWEEN '$begin' AND '$end') AND (level IN ('4','5','6','7'))";
			$res 					= mysql_query($qStr);
			$row 					= mysql_fetch_assoc( $res );				
			$totalEarnings['year'] 	= $row['totalEarnings'];
				
		//Total Month			
			// 1. what month now?
			$currentMonth = date('n');
			
			
			// 2. when did it start - time stamp | when did it end?
			$begin 		= mktime(0,0,1,$currentMonth,1,$currentYear);
			$numDays 	= date("t",$begin);
			$end   		= mktime(23,59,59,$currentMonth,$numDays,$currentYear);
			
			#echo date("F j, Y, H:i:s",$begin); 
			#echo date("F j, Y, H:i:s",$end);
			
			
			// 3. mySql query with a where between clause 
			$begin 						= (string) $begin;
			$end 						= (string) $end;
			$qStr 						= "SELECT SUM(amount) as totalEarnings FROM $table WHERE (order_time BETWEEN '$begin' AND '$end') AND (level IN ('4','5','6','7'))";
			$res 						= mysql_query($qStr);
			$row 						= mysql_fetch_assoc( $res );				
			$totalEarnings['month'] 	= $row['totalEarnings'];
					
		//Total Week			
			// 1. what week now?
			$currentWeek 	= date('W');
			$currDayinWeek 	= date('N');
			$currDayinMonth = date('j');
			switch($currDayinWeek){
				case '1':
					$firstDay 	= $currDayinMonth - 0;
					$lastDay 	= $currDayinMonth + 6;
				break;
				
				case '2':
					$firstDay 	= $currDayinMonth - 1;
					$lastDay 	= $currDayinMonth + 5;
				break;
				
				case '3':
					$firstDay 	= $currDayinMonth - 2;
					$lastDay 	= $currDayinMonth + 4;
				break;
				
				case '4':
					$firstDay 	= $currDayinMonth - 3;
					$lastDay 	= $currDayinMonth + 3;
				break;
				
				case '5':
					$firstDay 	= $currDayinMonth - 4;
					$lastDay 	= $currDayinMonth + 2;
				break;
				
				case '6':
					$firstDay 	= $currDayinMonth - 5;
					$lastDay 	= $currDayinMonth + 1;
				break;
				
				case '7':
					$firstDay 	= $currDayinMonth - 6;
					$lastDay 	= $currDayinMonth + 0;
				break;
				
			}
			
			// 2. when did it start - time stamp | when did it end?
			$begin 			= mktime(0,0,1,$currentMonth,$firstDay,$currentYear);
			$end   			= mktime(23,59,59,$currentMonth,$lastDay,$currentYear);
			
			
			#echo date("F j, Y, H:i:s",$begin); 
			#echo date("F j, Y, H:i:s",$end);
			
			
			// 3. mySql query with a where between clause 
			$begin 						= (string) $begin;
			$end 						= (string) $end;
			$qStr 						= "SELECT SUM(amount) as totalEarnings FROM $table WHERE (order_time BETWEEN '$begin' AND '$end') AND (level IN ('4','5','6','7'))";
			$res 						= mysql_query($qStr);
			$row 						= mysql_fetch_assoc( $res );				
			$totalEarnings['week'] 		= $row['totalEarnings'];	
		
		//Total Today			
			// 1. what week now?
			$today = date('j');
			
			// 2. when did it start - time stamp | when did it end?
			$begin 		= mktime(0,0,1,$currentMonth,$today,$currentYear);
			$end   		= mktime(23,59,59,$currentMonth,$today,$currentYear);
			
			#echo date("F j, Y, H:i:s",$begin); 
			#echo date("F j, Y, H:i:s",$end);
			
			
			// 3. mySql query with a where between clause 
			$begin 						= (string) $begin;
			$end 						= (string) $end;
			$qStr 						= "SELECT SUM(amount) as totalEarnings FROM $table WHERE (order_time BETWEEN '$begin' AND '$end') AND (level IN ('4','5','6','7'))";
			$res 						= mysql_query($qStr);
			$row 						= mysql_fetch_assoc( $res );				
			$totalEarnings['today'] 	= $row['totalEarnings'];
			
	return $totalEarnings;
	}
	
//////////////////////////////////////////////// INQUIRIES ///////////////////////////////////////////////////////
	
	function classify_inquiries()
	{
			
		// get inquires from db
		$odata[1]	= array();
		$odata[2]	= array();
		$counter1	= 0;
		$counter2	= 0;
		$table 	= is_dbtable_there('inquiries');
		$qStr 	= "SELECT * FROM $table ORDER BY inquiry_time DESC";
		$res 	= mysql_query($qStr);

		while($order 	= mysql_fetch_assoc($res)){				
							
			switch($order[level]){
				// new
				case '4':					
					$details 				= show_orders($order[who]);							
					$order[items]			= $details;
					$odata[1][$counter1]	= $order;								
					$counter1++;
				break; 
				// replied to
				case '5':
					$details 				= show_orders($order[who]);							
					$order[items]			= $details;
					$odata[2][$counter2]	= $order;								
					$counter2++;
				break; 

			}
			
		}
		return $odata;
	}
	
	
	function NWS_total_enquiries_there($option='all'){
		$totalEnquiries = array();
		$table 			= is_dbtable_there('inquiries');
		
		//total All
			$qStr 						= "SELECT * FROM $table";
			$res 						= mysql_query($qStr);
			$num 						= mysql_num_rows($res);
			$totalEnquiries['all'] 		= $num;
		
		//Replied to		
			$qStr 						= "SELECT * FROM $table WHERE level ='5'";
			$res 						= mysql_query($qStr);
			$num 						= mysql_num_rows($res);				
			$totalEnquiries['replied'] 	= $num;
	
			
		//New		
			$qStr 						= "SELECT * FROM $table WHERE level ='4'";
			$res 						= mysql_query($qStr);
			$num 						= mysql_num_rows($res);				
			$totalEnquiries['new'] 		= $num;
			
		return $totalEnquiries;
	}
	
	
	
	
	
	function display_inquiry_entries($odata,$status,$date_format,$table_header,$table_footer,$empty_message){
		global $CONFIG_WPS,$OPTION;
	
		$base_url  = '?page=functions.php&section=inquiries';
	
		if((count($odata[$status])) > 0){
			
			echo $table_header; 
			
				foreach($odata[$status] as $k => $inq){
					
					$date 			= date($date_format,$inq[inquiry_time]);
					$style 			= ($status == 5 ? "style='background:coral;'" : NULL);
					$dl_sent_info 	= ($inq[dlinks_sent] != 0 ? ' - last sent on: '.date("F j, Y, G:i",$inq[dlinks_sent]) : NULL);			
					
					echo "
					<tr>
						<td $style><input type='checkbox' name='$inq[oid]' value='move' /></td>
						<td $style>$inq[oid]</td>
						<td $style>$date</td>
						<td $style>$inq[l_name] $inq[f_name]<br/><a href='mailto:$inq[email]'>".__('Send email','wpShop')."</a></td>
						<td $style>";				
						echo address_format($inq);
						echo "
						</td>
						
						<td $style>";					
							if(diversity_check($inq,0,'BE') === TRUE){
								echo address_format(retrieve_delivery_addr('BE',$inq),'d-addr');
							}
						echo "</td>";	
						
						echo "
						<td $style>";
								echo '$'.format_price($inq['amount']); 
						echo  "</td>
						<td $style>"; 					
							echo list_order_items($inq[items]);	
							echo __('Shipping Fee:','wpShop').' ';
								echo '$'.format_price($inq['shipping_fee']); 
						echo  "</td>
						<td $style>";
						
							switch($inq[p_option]){
								case 'paypal':
									echo 'P: '.__('PayPal','wpShop');
								break;

								case 'cc_authn':
									echo 'P: '.__('Authorize.net','wpShop');
								break;	
								
								case 'cc_wp':
									echo 'P: '.__('WorldPay','wpShop');
								break;				
								
								case 'transfer':
									echo 'P: '.__('Bank Transfer','wpShop');
								break; 
								
								case 'cash':
									echo 'P: '.__('Cash on Location','wpShop');							
								break;	

								case 'cod':
									echo 'P: '.__('Cash on Delivery','wpShop');
								break;	
							} 
							
							echo "<br/>";
							echo "D: $inq[d_option]";
						echo "</td>";
						echo "<td $style>$inq[custom_note]</td>";	
					echo "</tr>";						
				}
					
			echo $table_footer;
		}
		else {
			echo $empty_message;
		}	
	}
	
	
	
	function change_inquiry_level($oid,$new_level){

		// update orders table
		$table = is_dbtable_there('inquiries');
		$column_value_array 	= array();
		$where_conditions 		= array();
		
		$column_value_array[level] 		= $new_level;
		
		$where_conditions[0]			= "oid = $oid";
							
		db_update($table, $column_value_array, $where_conditions);
	
	return TRUE;
	}
	
	function multi_change_inquiry_level(){

		// update inquiries table
		$table = is_dbtable_there('inquiries');						
						
		foreach($_POST as $k=>$v){	
		
			if($v == 'move'){
			
				$status						= trim($_POST['status']);
				
				if($status != 'delete'){
									
					$column_value_array 	= array();
					$where_conditions 		= array();
					
					$column_value_array['level'] 	= $status;					
					$where_conditions[0]			= "oid = $k";
										
					db_update($table, $column_value_array, $where_conditions);
						
				}
				else {
					$sql = "DELETE FROM $table WHERE oid = $k";
					mysql_query($sql);
				}
			}
		}
		
	return TRUE;
	}

//////////////////////////////////////////////// LKEYS ///////////////////////////////////////////////////////

	function save_lkeys(){

		$filename      	= 'csvfile';
		$table_name1   	= is_dbtable_there('lkeys');
								
		$delimit       	= "\n";
		$prod_quantity	= 1000;
		$row 			= 1;
		$lkeys 			= 1;
		$error			= 0;
		$err_message 	= __('There was an error with the file upload!','wpShop');
		
		
		if(strlen($_FILES[$filename]['tmp_name']) < 1){						
			$err_message .= "<br/>".__('You forgot to enter a .txt file.','wpShop');
			$error++;
		}
		if (substr($_FILES[$filename]['name'],-4) != '.txt'){ 
			$err_message .= "<br/>".__('The file needs to have the ending .txt','wpShop');
			$error++;
		}
		/*
		if ($_FILES[$filename]['type'] != 'text/plain' ){ 
			$err_message .= "<br/>".__('The file needs to have the ending .txt','wpShop');
			$error++;
		}
		*/
		if ($_FILES[$filename]['size'] > 102400){ // size = not more than 100kB
			$err_message .= "<br/>".__('Your file exceeds the size of 100 KB.','wpShop');
			$error++;
		}						
		if ($_FILES[$filename]['error'] !== UPLOAD_ERR_OK){
			$err_message .= "<br/>".file_upload_error_message($_FILES[$filename]['error']);
			$error++;
		}
		
		if(!$_POST[name_file]){
			$err_message .= "<br/>".__('Please enter the exact name of the file the license keys are used for.','wpShop');
			$error++;
		}
		$result = $err_message;
		
		if($error == 0){

			$handle = fopen($_FILES[$filename]['tmp_name'], "r");

			while(($data = fgetcsv($handle, 1000, $delimit)) !== FALSE){

				$row++;
				$len 		= strlen($data[0]);
				$data[0]	= trim($data[0]);	
				
				if($len > 0){
					###### Insert into lkeys table #####################
					$column_array 	= array();
					$value_array   	= array();

					$column_array[0] 	= 'lid';      			$value_array[0]   	= '';
					$column_array[1] 	= 'filename';      		$value_array[1]   	= $_POST[name_file];
					$column_array[2] 	= 'lkey';      			$value_array[2]   	= $data[0];
					$column_array[3] 	= 'used';    			$value_array[3]   	= '0';	
					
					$result_db1 = db_insert($table_name1, $column_array, $value_array);

					if($result_db1 != 1){
						echo "<p>".__('There was a problem with','wpShop')." $table_name1 !</p>\n";
					}
					$lkeys++;
				}  
			}      
			fclose($handle);
			$lkeys--;
			$result = "
				<h1>".__('Upload sucessful!','wpShop')."</h1>".__('You uploaded ','wpShop').
				$lkeys.__(' new license keys.','wpShop')."
				<br/><br/>";

		}

	return $result;
	}



	function file_upload_error_message($error_code) {
		switch($error_code){ 
			case UPLOAD_ERR_INI_SIZE: 
				return __('The uploaded file exceeds the upload_max_filesize directive in php.ini','wpShop');
			break;
			case UPLOAD_ERR_FORM_SIZE: 
				return __('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.','wpShop');
			break;
			case UPLOAD_ERR_PARTIAL: 
				return __('The uploaded file was only partially uploaded.','wpShop');
			break;
			case UPLOAD_ERR_NO_FILE: 
				return  __('No file was uploaded.','wpShop');
			break;
			case UPLOAD_ERR_NO_TMP_DIR: 
				return __('Missing a temporary folder.','wpShop');
			break;
			case UPLOAD_ERR_CANT_WRITE: 
				return __('Failed to write file to disk.','wpShop');
			break;
			case UPLOAD_ERR_EXTENSION: 
				return __('File upload stopped by extension.','wpShop');
			break;
			default: 
				return  __('Unknown upload error.','wpShop');
			break;
		} 
	} 

//////////////////////////////////////////////// MEMBERS ///////////////////////////////////////////////////////
		
	function display_members($num_per_page){
	
		$table  		= is_dbtable_there('feusers');
		$memb_wanted 	= trim($_GET['memb_wanted']);
		
		$data	= "<table class='widefat'>
					<thead>
						<tr>
							<th>".__('Username','wpShop')."</th>
							<th>".__('Email','wpShop')."</th>
							<th>".__('Lastname','wpShop')."</th>
							<th>".__('Firstname','wpShop')."</th>
							<th>".__('Member since','wpShop')."</th>
							<th>".__('Remove','wpShop')."</th>
						</tr>
					</thead>";
					
		$LIMIT 	= pagination_limit_clause($num_per_page);
		
		if(strlen($memb_wanted) < 1){		
			$qStr 	= "SELECT * FROM $table WHERE level = '0' ORDER BY lname ASC $LIMIT";	
		} else {
			$qStr 	= "SELECT * FROM $table WHERE level = '0' AND lname LIKE '$memb_wanted%' ORDER BY lname ASC $LIMIT";		
		}
		$res 	= mysql_query($qStr);
		$num 	= mysql_num_rows($res);
	
		if($num > 0){
		
			?>
			
				<script>
					function display_extra_info(uid){
						
						var user_id	= "user_" + uid;
						var value 	= document.getElementById(user_id).style.display;	
						
						if(value == 'none'){
							document.getElementById(user_id).style.display = 'block';
						}
						else{
							document.getElementById(user_id).style.display = 'none';
						}
					}
				</script>
			
			<?php
		
			$table 	= is_dbtable_there('feusers_meta');	
			$uid  	= (int) $_SESSION['uid'];	
	
			while($row = mysql_fetch_assoc($res)){
			
				$qStr2 		= "SELECT meta_key,meta_value FROM $table WHERE uid = $row[uid]";
				$res2  		= mysql_query($qStr2);
				$num2 		= mysql_num_rows($res2);
				$extraData 	= NULL;
				
				if($num2 > 0){
				
					$extraData .= "<span style='text-decoration:underline;cursor: pointer;display:block;' onclick='display_extra_info($row[uid])'>".__('Additional Info:')."</span>";
					$extraData .= "<div id='user_{$row[uid]}' style='display: none;'>";
					while($row2 = mysql_fetch_assoc($res2)){
						$v_key = str_replace("_", " ",$row2['meta_key']); 
						$extraData .= $v_key.': '.$row2['meta_value']."<br/>";
					}
					$extraData .= "</div>";
				}	
					
				$data .= "<tbody><tr>";
				$data .= "<td>$row[uname]</td>";  
				$data .= "<td><a href='mailto:{$row[email]}'>$row[email]</a>{$extraData}</td>";  
				$data .= "<td>$row[lname]</td>";  
				$data .= "<td>$row[fname]</td>";  
				$data .= "<td>$row[since]</td>";  
				$data .= "<td><a href='?page=functions.php&section=members&action=del&vid={$row[uid]}'>X</a></td>";  
				$data .= "</tr></tbody>";
			}
		}
		else{
			$data .= "<tbody><tr><td colspan='7'>".__('You have no Registered Members yet.','wpShop')."</td></tr></tbody>";
		}
		$data .= "</table>";
		
	return $data;
	}
		
	function member_delete($uid){
	
		$vid	= (int) trim($uid);	
		$table 	= is_dbtable_there('feusers');
		$qStr 	= "DELETE FROM $table WHERE uid = $uid";
		$res	= mysql_query($qStr);

	return $res;		
	}
	
	function NWS_total_members_there($option='all'){
		$totalMembers 	= array();
		$table 			= is_dbtable_there('feusers');
		
		//total All
			$qStr 					= "SELECT * FROM $table";
			$res 					= mysql_query($qStr);
			$num 					= mysql_num_rows($res);
			$totalMembers['all'] 	= $num;
	
		return $totalMembers;
	}
	
	function NWS_activeWishlists_there($option='all'){
	
		$data	= array();
	
		$table 	= is_dbtable_there('wishlist');	
		$qStr 	= "SELECT uid FROM $table";
		$res 	= mysql_query($qStr);
	
		while($row = mysql_fetch_assoc($res)){
			$data[] = $row['uid'];	
		}	
		//make unique and count the results
		$activeWishlists = count(array_unique($data));
		
		return $activeWishlists;
	}




//////////////////////////////////////////////// STATISTICS ///////////////////////////////////////////////////////
	function provide_statistics($header,$header_color,$diagram_height,$diagram_color){
		global $OPTION;
		
		//change.9.10
		$STATISTICS = load_what_is_needed('statistics');
		//\change.9.10
			
		echo make_section_header('statistics');		
		$currency 	= $OPTION['wps_currency_code'];
		$header_str	= __('Sales in %CURRENCY% :: Development last 12 months','wpShop');					
		$header 	= str_replace("%CURRENCY%",$currency,$header_str);		
		
		echo "<h3>".__('Statistics','wpShop')."</h3>";	
		echo $STATISTICS->graph_monthly_sales($diagram_height,$diagram_color);		//change.9.10
		echo make_section_footer();
	}




//////////////////////////////////////////////// INVENTORY ///////////////////////////////////////////////////////
function adapt_inventory2attributes(){

	global $wpdb;

	// get any attribute keys saved in post_meta
	$table 	= $wpdb->prefix . 'postmeta';
	$qStr 	= "SELECT DISTINCT meta_key FROM $table WHERE meta_key LIKE 'item_attr_%'";
	$res 	= mysql_query($qStr);
	
	$attributes = array();
	
	while($row = mysql_fetch_assoc($res)){
		$p 				= explode("_",$row['meta_key']);		// explode them - keep [2]
		$attributes[] 	= $p[2];
	}

	// run check: can a column with that name be found in inventory table?					
	$table 	= is_dbtable_there('inventory');	
	$qStr 	= "SHOW columns FROM $table";
	$res 	= mysql_query($qStr);
	
	$cols 	= array();
	$i		= 0;
	
	while($row = mysql_fetch_assoc($res)){
	
		$cols[$i] = $row['Field']; 
	
			if($row['Field'] == 'ID_item'){
				$j = $i;
			}
	$i++;
	}		

	$j--;
	$After_Col = $cols["$j"];
	
	$sql_cols = array();
				
	foreach($attributes as $v){
		if(!in_array(ucfirst($v),$cols)){		
			$sql_cols[] = $v;
		}
	}
	$num = count($sql_cols);
	

	// no new attributes: nice
	// yes, there are new attributes - change the inventory table 
	
	if($num > 0){
				
		$qStr = "ALTER TABLE $table "; 
		
		foreach($sql_cols as $v){
			$v = ucfirst($v);					
			$qStr .= " ADD `$v` ".'VARCHAR(255) NOT NULL AFTER '.$After_Col.',';
		}
		
			$qStr = substr($qStr,0,-1); 		
			mysql_query($qStr); 
	}
}

function prepare_master_array($post,$b){	
	
	global $wpdb;
	
	$qStr 	= "SELECT meta_value,meta_key FROM $wpdb->postmeta WHERE post_id = $post AND meta_key LIKE 'item_attr_%'";									
	$res 	= mysql_query($qStr);
								
	$MASTER_V 	= array();
	$MASTER_K 	= array();
	$y			= 0;
	
		while($row = mysql_fetch_assoc($res)){
			
			$k 				= explode("_",$row[meta_key]);	// name of col		
			$MASTER_K[$y] 	= $k[2];
			
			// rows - values
			if($b[attr_op] == '2'){
			
				$p 			= array();
				$almost  	= explode("|",$row[meta_value]);
				foreach($almost as $val_price_token){
					$parts 	= explode("-",$val_price_token); 
					$p[] 	= $parts[0];
				}
			}
			else {
				$p 			= explode("|",$row[meta_value]);
			}
										
			$MASTER_V[] = $p;
							
		$y++;
		}	
		
	$result = array();
	$result[master_v] = $MASTER_V;
	$result[master_k] = $MASTER_K;
	
return $result;
}




function permutations($array)		
{
	switch (count($array)) {
		case 1:
			return $array[0];
			$error = 0;
		break;
		case 0:
			$error = 1;
		break;
	}
	
	if($error == 0){
	
		$a = array_shift($array);
		$b = permutations($array);

		$return = array();
		foreach ($a as $v) {
			foreach ($b as $v2) {
				$return[] = array_merge(array($v), (array) $v2);
			}
		}

		foreach($return as $val){

			$num = count($val);
			$res = NULL;
			
			for($i=0;$i<$num;$i++){
				$res .= $val[$i].'&&';	
			}
			
			$output[] = substr($res,0,-2);
		}		
		
		return $output;
	}
	else {
		echo "<p class='error'>Error: Requires at least one array. Check if there are product posts with custom field 'has_attributes' BUT missing the additional 'item_attr_'!</p>";
	}
}

function add_permutations_db($MASTER_K,$MASTER_V,$ID_ITEM){
								
	$table 	= is_dbtable_there('inventory');
								
	// get the cols 
	$needed_cols = NULL;													
	foreach($MASTER_K as $ckey=> $col){
		$needed_cols .=  $col . ',';											
	}
	$needed_cols .=  'ID_item,amount';
	
	// get the values + do the DB insert
	$arr = permutations($MASTER_V);			


	foreach($arr as $key => $combo){
	
		$combo 		= explode("&&",$combo);	
		$where_str 	= NULL; 
		$val_str 	= NULL;
	
		foreach($combo as $k => $att_vals){
			$c			= ucfirst($MASTER_K[$k]);
			$where_str	.= "$c = '$att_vals' AND ";
			$val_str 	.= "'$att_vals',";
		}		
		
		// ID_ITEM + start amount 0 as well
		$where_str	.= "ID_item = '$ID_ITEM'";
		$val_str 	.= "'$ID_ITEM','0'";
		
		$qStr1 	= "SELECT * FROM $table WHERE $where_str";
		$res1	= mysql_query($qStr1);
		$num 	= mysql_num_rows($res1);
		
		if($num == 0){
			$qStr2 = "INSERT INTO $table ($needed_cols) VALUES ($val_str)";
			mysql_query($qStr2);
		}
	}	
}

function inventory_order_clause(){

		$table 	= is_dbtable_there('inventory');	
		$qStr 	= "SHOW columns FROM $table";

		$res	= mysql_query($qStr);
		$cols 	= array();
		$i		= 0;

		while($row = mysql_fetch_assoc($res)){
					
			if(($row['Field'] != 'iid') && ($row['Field'] != 'ID_item') && ($row['Field'] != 'amount'))
			{
				$cols[$i] = $row['Field'];
			}
			$i++;
		}	

		$order_clause = 'ORDER BY ';
		
		foreach($cols as $v){
			$order_clause .= $v.',';
		}
		$order_clause = substr($order_clause,0,-1);			

return $order_clause;
}




function pagination_limit_clause($num_per_page){

		$limit 		= 'LIMIT ';
		$start		= 0;
		$end		= $num_per_page;
		
		if(isset($_GET['start'])){
			$start 	= (int)$_GET['start'];
		}
		if(isset($_GET['end'])){
			$end 	= (int)$_GET['end'];
		}				
		
		$limit 		= $limit.$start.','.$end;

return $limit;
}



function inventory_main_query($limit) {
	global $wpdb;
	
	$art_wanted = trim($_GET['art_wanted']);

	if(strlen($art_wanted)) {
		$where = " AND pm.meta_value LIKE '".$art_wanted."%' ";
	}

	$res = mysql_query(sprintf("SELECT * FROM %sposts p INNER JOIN %spostmeta pm ON pm.post_id = p.ID WHERE p.post_type = 'post' AND pm.meta_key = 'ID_item' %s ORDER BY pm.meta_value %s", $wpdb->prefix, $wpdb->prefix, $where, $limit));

	return $res;
}

function inventory_article_check(){

	global $wpdb;
	$table1	= $wpdb->prefix . 'postmeta';
	$table2	= $wpdb->prefix . 'posts';
	
	$qStr 	= "SELECT meta_value FROM $table1 
				INNER JOIN $table2 ON $table1.post_id = $table2.ID 
			WHERE 
				$table1.meta_key = 'ID_item' 
			AND 
				$table2.post_status IN ('publish','draft','inherit')
			ORDER BY $table1.meta_value 
			$limit";

	$res 	= mysql_query($qStr);		
	$a		= array();
	
	// an array of Item-Id's is build
	while($row = mysql_fetch_assoc($res)){
		$a[] = $row['meta_value'];
	}
	
	//based on the Item-Id's array we check the inventory table if really all permutations and cols are there
	$table 	= is_dbtable_there('inventory');
	
	
	foreach($a as $v){
		$qStr 	= "SELECT * FROM $table WHERE ID_item = '$v' LIMIT 0,1";
		$res 	= mysql_query($qStr);
		$num	= mysql_num_rows($res);
		$status = ($num == 0 ? __('Article','wpShop').' ' . $v . ' '.__('will be added to inventory','wpShop')."<br/>" : NULL);
		
		//an item is not yet in the inventory - so we add it
		if($num == 0)
		{					
			$post 	= find_post_id($v);
			$b 		= has_attributes($post,2);
			
			# attributes-yes? - which ones?
			if($b[status] == 'yes'){
			
				$MASTER 	= prepare_master_array($post,$b);
				$MASTER_V 	= $MASTER[master_v];	
				$MASTER_K 	= $MASTER[master_k];	
													
				add_permutations_db($MASTER_K,$MASTER_V,$v);	
			}
			else { // no attributes? fine - we just add it like that				
			
				$column_array 	= array(); 
				$value_array	= array();
				
				$column_array[0] = 'ID_item'; 		$value_array[0] = $v;
				$column_array[1] = 'amount'; 		$value_array[1] = 0;
				
				db_insert($table,$column_array,$value_array);								
			}
		}

		# 3. Article-Check / all permutations of all articles really there?			
		$post 	= find_post_id($v);
		$b 		= has_attributes($post,2);
			
			
		if($b['status'] == 'yes'){
		
			$MASTER 	= prepare_master_array($post,$b);
			$MASTER_V 	= $MASTER[master_v];	
			$MASTER_K 	= $MASTER[master_k];			

			$feedback 		= get_attr_permutation_nums($MASTER_V,$MASTER_K,$v);
			$num1 			= $feedback['num1'];
			$num2 			= $feedback['num2'];
			$new_attr_flag	= $feedback['new_attr_flag'];

			// it could be that an attribute option itself was changed
			$k_num = count($MASTER_K);
			for($i=0;$i<$k_num;$i++){
				$qStr 		= "SELECT * FROM $table WHERE ID_item='$v'";
				$res6 		= mysql_query($qStr);
				
				while($row6 = mysql_fetch_assoc($res6)){
					if(! in_array($row6[ucfirst($MASTER_K[$i])],$MASTER_V[$i])){
						$remove	= $row6[ucfirst($MASTER_K[$i])]; 								
						$qStr 	= "DELETE FROM $table WHERE ID_item='$v' AND ".ucfirst($MASTER_K[$i])." = '$remove'";											
						mysql_query($qStr);
					}
				}
			}
						
			// we check again		
			$MASTER 	= prepare_master_array($post,$b);
			$MASTER_V 	= $MASTER[master_v];	
			$MASTER_K 	= $MASTER[master_k];	

			$feedback 		= get_attr_permutation_nums($MASTER_V,$MASTER_K,$v);
			$num1 			= $feedback['num1'];
			$num2 			= $feedback['num2'];
			$new_attr_flag	= $feedback['new_attr_flag'];	
													
			if($num1 > $num2){
				
					//why the difference in number, is it a new attribute or a new option of an existing attribute?
				
					//DELETE - in case a new attr group was added 				
					if($new_attr_flag == 1){
						$qStr = "DELETE FROM $table WHERE ID_item='$v'";											
						mysql_query($qStr);
					}
					
					// Create all the combos
					add_permutations_db($MASTER_K,$MASTER_V,$v);	
				}		
				elseif($num1 < $num2){
				
					//why the difference in number, is it one less attribute group or one less option of an existing attribute?
					$less_attr_flag	= 0;
					
					//get all columns of inventory table 
					$result 	= mysql_query("SELECT * FROM $table LIMIT 0,1");
					$table_cols	= mysql_fetch_assoc($result);
					$avail_cols = array();
					
					foreach($table_cols as $kkey => $value){
						$avail_cols[$kkey] = $kkey;
					}
					unset($avail_cols['iid']);
					unset($avail_cols['ID_item']);
					unset($avail_cols['amount']);
				
					foreach($avail_cols as $val){
						$sql9 = "SELECT MAX(".ucfirst($val).") AS LargestVal FROM $table WHERE ID_item = '$v'";
						$res9 = mysql_query($sql9);
						$row9 = mysql_fetch_assoc($res9);
						
						if(strlen($row9['LargestVal'])>0){
							if((!in_array($val,$MASTER_K)) && (!in_array(strtolower($val),$MASTER_K))){
								$less_attr_flag	= 1;
							}
						}
					}

					if($less_attr_flag == 1){
						$qStr = "DELETE FROM $table WHERE ID_item='$v'";											
						mysql_query($qStr);
					}
					else {
						//which of the attr options doesn't exist anymmore?
						$k_num = count($MASTER_K);
						for($i=0;$i<$k_num;$i++){
							$qStr 		= "SELECT * FROM $table WHERE ID_item='$v'";
							$res6 		= mysql_query($qStr);
							
							while($row6 = mysql_fetch_assoc($res6)){
								if(! in_array($row6[ucfirst($MASTER_K[$i])],$MASTER_V[$i])){
									$remove	= $row6[ucfirst($MASTER_K[$i])]; 								
									$qStr 	= "DELETE FROM $table WHERE ID_item='$v' AND ".ucfirst($MASTER_K[$i])." = '$remove'";											
									mysql_query($qStr);
								}
							}
						}
					}
					
					// Create all the combos
					add_permutations_db($MASTER_K,$MASTER_V,$v);	
				}	
			}
			else {
			// no attributes? then there should be only 1 single record in inventory table 
					$table 	= is_dbtable_there('inventory');
					$qStr 	= "SELECT * FROM $table WHERE ID_item = '$v'";
					$res	= mysql_query($qStr);
					$num2	= mysql_num_rows($res);

					if($num2 > 1){
						$qStr = "DELETE FROM $table WHERE ID_item='$v'";											
						mysql_query($qStr);					
					
						$qStr = "INSERT INTO $table (ID_item,amount) VALUES ($v,0)";						
						mysql_query($qStr);
					}
			}
	}
}

function get_attr_permutation_nums($MASTER_V,$MASTER_K,$v){

	$feedback 			= array();
	$table 				= is_dbtable_there('inventory');
	
	// how many acc. to fields of the post?
	$permu_num 			= permutations($MASTER_V);
	$feedback['num1'] 	= count($permu_num);		
	
	// how many acc. inventory db table?
	//was a complete new attr group added?
	$feedback['new_attr_flag']	= 0;
	
	foreach($MASTER_K as $attr_col){
		$qStr 	= "SELECT MAX(".ucfirst($attr_col).") AS LargestVal FROM $table WHERE ID_item = '$v'";
		$res 	= mysql_query($qStr);
		$row 	= mysql_fetch_assoc($res);
		
		if(strlen($row['LargestVal']) == 0){
			$feedback['new_attr_flag'] = 1;
		}
	}

	$qStr 				= "SELECT * FROM $table WHERE ID_item = '$v'";
	$res				= mysql_query($qStr);
	$feedback['num2']	= mysql_num_rows($res);
			
return $feedback;
}

function inventory_amount_update(){
	global $wpdb, $current_user;
	$table 	= is_dbtable_there('inventory');
	unset($_POST[submit_this]);
	
	foreach($_POST as $k => $v){
		$item_id = $wpdb->get_var(sprintf("SELECT ID_item FROM %s WHERE iid = %s", $table, $k));
		update_item_inventory($item_id, $v, true, 'Update inventory amount - item_id = '.$item_id);
		log_action('inventory_update', 'Item ID: '.$item_id.'; Amount: '.$v.'; User ID: '.$current_user->ID);
	}
	$redirect = 'admin.php?page='.$_GET['page'];
	if (isset($_GET['section'])) {
		$redirect .= '&section='.$_GET['section'];
	}
	header("Location: ".$redirect);
	exit;
}




function inventory_product_title($post_id){
	global $wpdb;
	
	$table	= $wpdb->prefix . 'posts';
	$qStr 	= "SELECT post_title FROM $table WHERE ID = $post_id LIMIT 0,1";
	$res 	= mysql_query($qStr);					
	$row   	= mysql_fetch_assoc($res);		

	return $row['post_title'];
}




function inventory_product_image($post_id){
	global $wpdb;

	$table	= $wpdb->prefix . 'postmeta';
	$qStr 	= "SELECT * FROM $table WHERE meta_key = 'image_thumb' AND post_id = $post_id LIMIT 0,1";
	$res 	= mysql_query($qStr);					
	$row   	= mysql_fetch_assoc($res);
	
	return $row['meta_value'];	
}




function inventory_has_attributs($post_id){
	global $wpdb;
	
	$table		= $wpdb->prefix . 'postmeta';
	$qStr 		= "SELECT meta_id FROM $table WHERE post_id = $post_id AND meta_key LIKE 'item_attr%'";
	$res2 		= mysql_query($qStr);	
	$num		= mysql_num_rows($res2);

	return $num;
}



function header_for_attributes($meta_value){
	
	$table		= is_dbtable_there('inventory');						
	$qStr		= "SELECT * FROM $table WHERE ID_item = '$meta_value' LIMIT 0,1";
	$res 		= mysql_query($qStr);
	$row_head 	= mysql_fetch_assoc($res);						
	
	$data		= NULL;
	
	if($row_head != FALSE){
		foreach($row_head as $k => $v){		
			if($k != 'iid' && $k != 'ID_item' && $k != 'amount' && !empty($v)){
				$data .= "<th>".ucwords($k)."</th>";
			}
		}
	} else {echo "<p class='warning'>".__('Please press the "Refresh List" button at the top of the page.','wpShop')."</p>";}
	
	$data .= "<th>".__('Amount','wpShop')."</th></tr>";
		
	return $data;
}





function display_attributes_data($meta_value,$order_by_clause){

	$table		= is_dbtable_there('inventory');
	$qStr		= "SELECT * FROM $table WHERE ID_item = '$meta_value' $order_by_clause"; // ORDER BY
	$qStr		= "SELECT * FROM $table WHERE ID_item = '$meta_value'"; 					
	$res 		= mysql_query($qStr);							

	$data		= NULL;

		while($row = mysql_fetch_assoc($res)){
			
				$data .= "<tr>";
				
				foreach($row as $k => $v){
					if($k != 'iid' && $k != 'ID_item' && $k != 'amount' && !empty($v)){
						$data .= "<td>$v</td>";
					}			
				}
				
				$data .= "<td><input type='text' name='$row[iid]' value='$row[amount]' /></td></tr>";
		}

	return $data;
}



function display_amount($meta_value){

	$table		= is_dbtable_there('inventory');
	$qStr		= "SELECT * FROM $table WHERE ID_item = '$meta_value'"; // ORDER BY
	$res 		= mysql_query($qStr);							

	return $res;
}


function NWS_total_prods_there($option='all'){
	$totalProds 	= array();
	$table 			= is_dbtable_there('inventory');
	
	//total All
		$qStr 				= "SELECT * FROM $table";
		$res 				= mysql_query($qStr);
		$num 				= mysql_num_rows($res);
		$totalProds['all'] 	= $num;
		
	//Low in Stock		
		$qStr 						= "SELECT * FROM $table WHERE amount ='1'";
		$res 						= mysql_query($qStr);
		$num 						= mysql_num_rows($res);				
		$totalProds['low'] 			= $num;
	
	//Out of Stock		
		$qStr 						= "SELECT * FROM $table WHERE amount ='0'";
		$res 						= mysql_query($qStr);
		$num 						= mysql_num_rows($res);				
		$totalProds['out'] 			= $num;
	
	return $totalProds;
}




/////////////////////////////////////////////// PAGINATION //////////////////////////////////////////////////////////
function NWS_inventory_pagination($articles=10){

	global $wpdb;

	$table 		= $wpdb->prefix . 'postmeta';
	$art_wanted = trim($_GET['art_wanted']);

	// how many articles altogether?
	if(strlen($art_wanted)<1){
		$qStr 	= "SELECT * FROM $table WHERE meta_key = 'ID_item'";
	}
	else{
		$qStr 	= "SELECT * FROM $table WHERE meta_key = 'ID_item' AND meta_value LIKE '$art_wanted%'";
	}
	$res 		= mysql_query($qStr);
	$num_art	= mysql_num_rows($res);
	
	if($num_art == 0){
		echo "<b>".__('No articles found','wpShop')."</b>";
	}
	else {
		$num_pages 	= $num_art / $articles;				
		$base_url 	= '?page=functions.php&section=inventory&art_wanted='.$_GET['art_wanted'];
		
		echo "<a class='prev page-numbers' href='{$base_url}&start=0&end=".$articles."' style='text-decoration:none;'>&laquo;</a>";
		
		for($i=0,$j=1,$s=0,$e=$articles;$i<$num_pages;$i++,$j++,$s += $articles){
		
			if($_GET['start'] != $s){
				echo "<a class='page-numbers' href='{$base_url}&start={$s}&end={$e}'>$j</a>";
			}
			else {
				echo "<span class='page-numbers current'>$j</span>";
			}
		}	
		$l = $s - $articles;
		echo "<a class='next page-numbers' href='{$base_url}&start={$l}&end={$e}' style='text-decoration:none;'>&raquo;</a>";
	}
}

function NWS_members_pagination($members=10){

	global $wpdb;

	$table 			= is_dbtable_there('feusers');
	$memb_wanted 	= trim($_GET['memb_wanted']);

	// how many members altogether?
	if(strlen($memb_wanted) < 1){
		$qStr 	= "SELECT * FROM $table WHERE level = '0'";
	}
	else{
		$qStr 	= "SELECT * FROM $table WHERE level = '0' AND lname LIKE '$memb_wanted%'";
	}
	$res 		= mysql_query($qStr);
	$memb_num	= mysql_num_rows($res);
	
	if($memb_num == 0){
		echo "<b>".__('No Members found','wpShop')."</b>";
	}
	else {
		$num_pages 	= $memb_num / $members;				
		$base_url 	= '?page=functions.php&section=members&memb_wanted='.$_GET['memb_wanted'];
		
		echo "<a class='prev page-numbers' href='{$base_url}&start=0&end=".$members."' style='text-decoration:none;'>&laquo;</a>";
		
		for($i=0,$j=1,$s=0,$e=$members;$i<$num_pages;$i++,$j++,$s += $members){
		
			if($_GET['start'] != $s){
				echo "<a class='page-numbers' href='{$base_url}&start={$s}&end={$e}'>$j</a>";
			}
			else {
				echo "<span class='page-numbers current'>$j</span>";
			}
		}	
		$l = $s - $members;
		echo "<a class='next page-numbers' href='{$base_url}&start={$l}&end={$e}' style='text-decoration:none;'>&raquo;</a>";
	}
}

//////////////////////////////////////////////// THEME-OPTIONS ///////////////////////////////////////////////////////


	function summarize_multi_checkbox($name_option,$option_v){
								
		global $wpdb,$CONFIG_WPS;
		
		$the_option 	= NULL;
		$option_name 	= $CONFIG_WPS[shortname].$name_option;
		$table			= $wpdb->prefix.'options';
	
		foreach($option_v as $k => $v){				

			$in_val = "$CONFIG_WPS[shortname]{$name_option}|$v";	
	
			if(array_key_exists($in_val,$_POST)){
				$data 		= explode("|",$in_val);
				$the_option .= $data[1].'|';
			}								
		}
	
		$the_option = substr($the_option, 0, -1);
		
		if($the_option[0] == '|'){
			$the_option = substr($the_option,1);
		}
	
	
		// Delete old entry					
		$qStr 			= "DELETE FROM $table WHERE option_name = '$option_name'";		
		mysql_query($qStr);

		// Insert new option
		$qStr1 = "INSERT INTO $table (option_name,option_value) VALUES ('$option_name','$the_option')";
		mysql_query($qStr1);
				
	return 'DONE';				
	}


	function optimize_table($table='options'){
		global $wpdb;
		$table = $wpdb->prefix.$table;
		$sql 	= "OPTIMIZE TABLE `$table`"; 
		mysql_query($sql);
	}
	

//////////////////////////////////////////////// Voucher Tabs ///////////////////////////////////////////////////////	
	
	function make_voucher_tabs($current){

		global $CONFIG_WPS;

		
		$links 					= array();
		$links['upload']		= '?page=functions.php&section=vouchers&action=start';
		$links['display']  		= '?page=functions.php&section=vouchers&action=display';
		$links['bgImg'] 		= '?page=functions.php&section=vouchers&action=bgImg';
		$links['pdf'] 			= '../wp-content/themes/'.$CONFIG_WPS[themename].'/lib/fpdf16/pdf_vouchers.php';
		$links['reseller']  	= '?page=functions.php&section=vouchers&action=reseller';
		
		
		$css_class 					= array();
		$css_class['upload']		= 'class="inactive"';
		$css_class['display']  		= 'class="inactive"';
		$css_class['bgImg'] 		= 'class="inactive"';
		$css_class['pdf'] 			= 'class="inactive"';
		$css_class['reseller']  	= 'class="inactive"';
		
		foreach($links as $k => $v){
			if($k == $current){
				$v 				= '#';
				$css_class[$k]	= 'class="active"';
			}
		}
		
			
		$output2 = "
			<ul class='tabs secondaryTabs v_tabs'> 
				<li><a href='$links[upload]' $css_class[upload]>".__('Upload New Single-Use Vouchers','wpShop')."</a></li>";
				$output2 .=  "<li><a href='$links[display]' $css_class[display]>".__('Manage Single-Use Vouchers','wpShop')."</a></li>";
				$output2 .=  "<li><a href='$links[bgImg]' $css_class[bgImg] >".__('Upload Background-Image','wpShop')."</a></li>";
				$output2 .= "<li><a target='_blank' href='$links[pdf]' $css_class[pdf]>".__('Create PDF','wpShop')."</a></li>";
				$output2 .= "<li><a href='$links[reseller]' $css_class[reseller] >".__('Reseller (Multi-Use) Vouchers','wpShop')."</a></li>";
			$output2 .= "</ul>";
		
	return $output2;
	}
	
	function NWS_total_vouchers_there($option='all'){
		global $wpdb;
		$table = is_dbtable_there('vouchers');

		$totalVouchers 	= array(
			'all' => $wpdb->get_var(sprintf("SELECT COUNT(vid) FROM %s", $table)),
			'single_use' => $wpdb->get_var(sprintf("SELECT COUNT(vid) FROM %s WHERE type = 1", $table)),
			'multi_use' => $wpdb->get_var(sprintf("SELECT COUNT(vid) FROM %s WHERE type = 2", $table))
		);
		
		return $totalVouchers;
	}
	

//////////////////////////////////////////////////////////// CHECKS //////////////////////////////////////////////////////////

function attributesDataChecker($checkno = 1){
	
	global $wpdb;

	switch($checkno){
	
		case 1:
		$table 	= $wpdb->prefix . postmeta;
		$qStr 	= "SELECT * FROM $table WHERE meta_key LIKE '%item_attr_%'";
		$res 	= mysql_query($qStr);

		while($row = mysql_fetch_assoc($res))
		{
				$meta_value = trim($row['meta_value']);
				$len 		= strlen($meta_value)-1;
				$last 		= $meta_value[$len];
				
				if($last == "|"){
					$new_meta_value =  substr($meta_value,0,-1);
					#echo "ID $meta_id - Old value: $meta_value - New value: $new_meta_value"."<br/>";
					$qStr2 	=  "UPDATE $table SET meta_value ='$new_meta_value' WHERE meta_id = $row[meta_id]";
					mysql_query($qStr2);
				}
		}
		break;
		
		case 2:
		$table 	= $wpdb->prefix . postmeta;
		$qStr 	= "SELECT * FROM $table WHERE meta_key = 'ID_item'";
		$res 	= mysql_query($qStr);

		while($row = mysql_fetch_assoc($res)){
			echo "<pre>";
				print_r($row);
			echo "</pre>";

			$parts 	= explode(" ",$row['meta_value']);
			$num 	= count($parts);
			
			if($num > 1){
				$meta_value = implode("_", $parts);
				$qStr2 		=  "UPDATE $table SET meta_value ='$meta_value' WHERE meta_id = $row[meta_id]";
				
				echo $qStr2;
				mysql_query($qStr2);
			}
		}
		break;
		
		case 3:
		echo "<b>".__('Find posts with attribut conflicts:','wpShop')."</b><br/>";

		$counter 	= 0;
		$arr 		= array();
		$table 		= $wpdb->prefix . postmeta;
		$qStr 		= "SELECT * FROM $table WHERE meta_key = 'add_attributes'";
		$res 		= mysql_query($qStr);

		while($row = mysql_fetch_assoc($res)){
			$arr[] = (int) $row['post_id'];
		}
		foreach($arr as $v){

			$qStr 	= "SELECT * FROM $table WHERE post_id = $v AND meta_key LIKE 'item_attr_%' LIMIT 0,1";
			$res 	= mysql_query($qStr);
			$num 	= mysql_num_rows($res);

			if($num == 0){
				$counter++;
			
				$f  = __('Post ','wpShop').$v. __(' has no custom field item_attr_% - Link: ','wpShop');
				$f .= "<a href='".get_option('home')."/wp-admin/post.php?action=edit&post=$v' target='_blank'>";
				$f .= get_option('home')."/wp-admin/post.php?action=edit&post=$v</a><br/>";
				echo $f;
			}
		}
		if($counter == 0){
			echo __('No conflicts found.','wpShop')."<br/><br/>";
		}
		break;
	}
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
add_action('init', 'csv_export_actions');
function csv_export_actions() {
	global $wpdb, $OPTION, $order_payment_methods;
	if (strlen($_GET['csvexport'])) {
		$eol = "\n";
		if ($_GET['csvexport'] == 'orders') { // orders export
			$otable = is_dbtable_there('orders');
			$sctable = is_dbtable_there('shopping_cart');
			$INVOICE 	= load_what_is_needed('invoice');
			$otime_addition = get_option('wps_time_addition');
			$csv_name = "orders-export.csv";

			$csvdata  = tocsv('ContactName');
			$csvdata .= tocsv('EmailAddress');
			$csvdata .= tocsv('POAddressLine1');
			$csvdata .= tocsv('POAddressLine2');
			$csvdata .= tocsv('POAddressLine3');
			$csvdata .= tocsv('POAddressLine4');
			$csvdata .= tocsv('POCity');
			$csvdata .= tocsv('PORegion');
			$csvdata .= tocsv('POPostalCode');
			$csvdata .= tocsv('POCountry');
			$csvdata .= tocsv('OrderNumber');
			$csvdata .= tocsv('InvoiceNumber');
			$csvdata .= tocsv('Reference');
			$csvdata .= tocsv('InvoiceDate');
			$csvdata .= tocsv('DueDate');
			$csvdata .= tocsv('SubTotal');
			$csvdata .= tocsv('TotalTax');
			$csvdata .= tocsv('Total');
			$csvdata .= tocsv('Description');
			$csvdata .= tocsv('Quantity');
			$csvdata .= tocsv('UnitAmount');
			$csvdata .= tocsv('Discount');
			$csvdata .= tocsv('AccountCode');
			$csvdata .= tocsv('TaxType');
			$csvdata .= tocsv('TaxAmount');
			$csvdata .= tocsv('TrackingName1');
			$csvdata .= tocsv('TrackingOption1');
			$csvdata .= tocsv('TrackingName2');

			if ($_GET['otype'] == 'cancelled') {
				$inlevel = "'0'";
				$csv_name = "cancelled-orders-export.csv";
				$csvdata .= tocsv('TrackingOption2');
				$csvdata .= tocsv('InstallmentOrder');
				$csvdata .= tocsv('CancelReason', $eol);
			} else {
				$inlevel = "'3','4','5','6','7','8'";
				$csvdata .= tocsv('TrackingOption2', $eol);
			}

			if ($_GET['otype'] == 'cancelled') {
			}
			$csvorders = $wpdb->get_results(sprintf("SELECT * FROM %s WHERE level IN (%s) ORDER BY oid DESC", $otable, $inlevel));
			if ($csvorders) {
				foreach($csvorders as $csvorder) {
					$voucher = $csvorder->voucher;
					if ($voucher == 'non') { $voucher = ''; }
					$orderproducts = array();
					$csvoproducts = $wpdb->get_results(sprintf("SELECT * FROM %s WHERE who = '%s' ORDER BY cid", $sctable, $csvorder->who));
					foreach($csvoproducts as $csvoproduct) {
						$orderproducts[] = array('item_id' => $csvoproduct->item_id, 'item_name' => $csvoproduct->item_name, 'item_qty' => $csvoproduct->item_amount);
					}
					$odate = $csvorder->order_time;
					if ($csvorder->level == 3) {
						$odate = $csvorder->layaway_date;
					}

					$csvdata .= tocsv($csvorder->f_name.' '.$csvorder->l_name);
					$csvdata .= tocsv($csvorder->email);
					$csvdata .= tocsv($csvorder->street);
					$csvdata .= tocsv('');
					$csvdata .= tocsv('');
					$csvdata .= tocsv('');
					$csvdata .= tocsv($csvorder->town);
					$csvdata .= tocsv($csvorder->state);
					$csvdata .= tocsv($csvorder->zip);
					$csvdata .= tocsv($csvorder->country);
					$csvdata .= tocsv($OPTION['wps_order_no_prefix'].$csvorder->oid);
					$csvdata .= tocsv($INVOICE->retrieve_invoice_no($csvorder->oid));
					$csvdata .= tocsv($orderproducts[0]['item_id']);
					$csvdata .= tocsv(date("d.m.Y", ($odate + $otime_addition)));
					$csvdata .= tocsv('');
					$csvdata .= tocsv(format_price($csvorder->net + $csvorder->shipping_fee));
					$csvdata .= tocsv(format_price($csvorder->tax));
					$csvdata .= tocsv(format_price($csvorder->amount));
					$csvdata .= tocsv($orderproducts[0]['item_name']);
					$csvdata .= tocsv($orderproducts[0]['item_qty']);
					$csvdata .= tocsv('');
					$csvdata .= tocsv($voucher);
					$csvdata .= tocsv('');
					$csvdata .= tocsv('');
					$csvdata .= tocsv('');
					$csvdata .= tocsv('Payment Method');
					$csvdata .= tocsv($order_payment_methods[$csvorder->p_option]);
					$csvdata .= tocsv('Shipping Method');
					if ($_GET['otype'] == 'cancelled') {
						$csvdata .= tocsv($csvorder->d_option);
						$csvdata .= tocsv($csvorder->layaway_process);
						$csvdata .= tocsv($csvorder->cancel_reason, $eol);
					} else {
						$csvdata .= tocsv($csvorder->d_option, $eol);
					}

					if (count($orderproducts) > 1) {
						for($i=1; $i<count($orderproducts); $i++) {
							$csvdata .= tocsv('');
							$csvdata .= tocsv('');
							$csvdata .= tocsv('');
							$csvdata .= tocsv('');
							$csvdata .= tocsv('');
							$csvdata .= tocsv('');
							$csvdata .= tocsv('');
							$csvdata .= tocsv('');
							$csvdata .= tocsv('');
							$csvdata .= tocsv('');
							$csvdata .= tocsv('');
							$csvdata .= tocsv('');
							$csvdata .= tocsv($orderproducts[$i]['item_id']);
							$csvdata .= tocsv('');
							$csvdata .= tocsv('');
							$csvdata .= tocsv('');
							$csvdata .= tocsv('');
							$csvdata .= tocsv('');
							$csvdata .= tocsv($orderproducts[$i]['item_name']);
							$csvdata .= tocsv($orderproducts[$i]['item_qty']);
							$csvdata .= tocsv('');
							$csvdata .= tocsv('');
							$csvdata .= tocsv('');
							$csvdata .= tocsv('');
							$csvdata .= tocsv('');
							$csvdata .= tocsv('');
							$csvdata .= tocsv('');
							$csvdata .= tocsv('');
							if ($_GET['otype'] == 'cancelled') {
								$csvdata .= tocsv('');
								$csvdata .= tocsv('');
								$csvdata .= tocsv('', $eol);
							} else {
								$csvdata .= tocsv('', $eol);
							}
						}
					}
				}
			}
		} else if ($_GET['csvexport'] == 'inventory') { // inventory export
			$csv_name = "inventory-export.csv";

			$csvdata = tocsv('ItemCode');
			$csvdata .= tocsv('Description');
			$csvdata .= tocsv('PurchasesPrice');
			$csvdata .= tocsv('PurchasesAccount');
			$csvdata .= tocsv('Tax');
			$csvdata .= tocsv('SalesPrice');
			$csvdata .= tocsv('SalesAccount');
			$csvdata .= tocsv('Tax', $eol);

			$itable = is_dbtable_there('inventory');
			$csvinvitems = $wpdb->get_results(sprintf("SELECT p.post_title, pm.meta_value FROM %sposts p LEFT JOIN %spostmeta pm ON pm.post_id = p.ID LEFT JOIN %s i ON i.ID_item = pm.meta_value WHERE p.post_type = 'post' AND p.post_status = 'publish' AND pm.meta_key = 'ID_item' AND i.amount > 0 ORDER BY p.post_title", $wpdb->prefix, $wpdb->prefix, $itable));
			if ($csvinvitems) {
				foreach($csvinvitems as $csvinvitem) {
					$csvdata .= tocsv($csvinvitem->meta_value);
					$csvdata .= tocsv($csvinvitem->post_title);
					$csvdata .= tocsv('');
					$csvdata .= tocsv('');
					$csvdata .= tocsv('');
					$csvdata .= tocsv('');
					$csvdata .= tocsv('');
					$csvdata .= tocsv('', $eol);
				}
			}
		} else if ($_GET['csvexport'] == 'searches') { // searches export
			$csv_name = "searches-export.csv";

			$csvdata .= tocsv('Search term');
			$csvdata .= tocsv('Number of searches', $eol);

			$sdate = $_GET['sdate'];
			$edate = $_GET['edate'];
			$floc = $_GET['floc'];
			if (strlen($floc)) {
				$locwhere = " AND slocation = '".$floc."'";
			}

			$searches = $wpdb->get_results(sprintf("SELECT svalue, COUNT(*) as snumber FROM %swps_searches WHERE sdate >= '%s' AND sdate <= '%s' %s GROUP BY svalue ORDER BY snumber DESC", $wpdb->prefix, $sdate, $edate, $locwhere));
			if ($searches) {
				foreach($searches as $sdata) {
					$csvdata .= tocsv($sdata->svalue);
					$csvdata .= tocsv($sdata->snumber, $eol);
				}
			}
		}
		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=".$csv_name);
		header("Content-Length: ".strlen($csvdata));
		header("Pragma: no-cache");
		echo($csvdata);
		exit;
	}
}

function get_order_cancel_reasons() {
	return array(
		__('test order','wpShop'),
		__('error in order details','wpShop'),
		__('customer no response before dispatch','wpShop'),
		__('order cancelled before dispatch','wpShop'),
		__('non delivery','wpShop'),
		__('item returned by customer','wpShop'),
		__('item exchange','wpShop')
	);
}

add_action('init', 'pricing_actions_init');
function pricing_actions_init() {
	global $wpdb, $OPTION;

	if (strlen($_POST['PricingAction'])) {
		$pid = $_POST['pid'];
		$category = $_POST['p_category'];
		$brand = $_POST['p_brand'];
		$style_name = $_POST['p_style_name'];
		$selection = $_POST['p_selection'];
		$colour = $_POST['p_colour'];
		$original_price = $_POST['p_original_price'];
		$high_price = $_POST['p_high_price'];
		$low_price = $_POST['p_low_price'];
		$includes_box = $_POST['p_includes_box'];
		$includes_invoice = $_POST['p_includes_invoice'];
		$includes_dustbag = $_POST['p_includes_dustbag'];
		$includes_card = $_POST['p_includes_card'];
		$includes_booklet = $_POST['p_includes_booklet'];
		$includes_packaging = $_POST['p_includes_packaging'];
		$notes = $_POST['p_notes'];
		$metal = $_POST['p_metal'];
		$material = $_POST['p_material'];
		$movement = $_POST['p_movement'];
		if (is_array($metal)) { $metal = implode('|', $metal); }
		if (is_array($material)) { $material = implode('|', $material); }
		if (is_array($movement)) { $movement = implode('|', $movement); }

		require_once('includes/post.php');
		require_once('includes/image.php');
		require_once('includes/file.php');
		require_once('includes/media.php');
		$photo_file = wp_handle_upload($_FILES['p_photo'], array('test_form' => false), current_time('mysql'));
		$photo = $photo_file['url'];

		$data = array();
		$data['category'] = $category;
		$data['brand'] = $brand;
		$data['style_name'] = $style_name;
		$data['selection'] = $selection;
		$data['colour'] = $colour;
		$data['original_price'] = $original_price;
		$data['high_price'] = $high_price;
		$data['low_price'] = $low_price;
		$data['includes_box'] = $includes_box;
		$data['includes_invoice'] = $includes_invoice;
		$data['includes_dustbag'] = $includes_dustbag;
		$data['includes_card'] = $includes_card;
		$data['includes_booklet'] = $includes_booklet;
		$data['includes_packaging'] = $includes_packaging;
		$data['notes'] = $notes;
		$data['metal'] = $metal;
		$data['material'] = $material;
		$data['movement'] = $movement;
		if (strlen($photo)) {
			$data['photo'] = $photo;
		}

		switch ($_POST['PricingAction']) {
			case "add":
				$wpdb->insert($wpdb->prefix."wps_pricing", $data);
			break;
			case "edit":
				$wpdb->update($wpdb->prefix."wps_pricing", $data, array('pid' => $pid));
			break;
			case "delete":
				$wpdb->delete($wpdb->prefix."wps_pricing", array('pid' => $pid));
			break;
		}
		$redirurl = 'admin.php?page=functions.php&section=pricing';
		if (strlen($_GET['pg'])) { $redirurl .= '&pg='.$_GET['pg']; }
		wp_redirect($redirurl);
		wp_exit();
	}
}

// allowed IP
add_action('admin_menu', 'staff_allowed_ip_admin_menu');
function staff_allowed_ip_admin_menu() {
	global $current_user;
	if ($current_user->ID == 1 || $current_user->ID == 6871) {
		add_users_page(
			'Staff Allowed IP', // meta title
			'Staff Allowed IP', // admin menu title
			8,
			'staff-allowed-ip',
			'staff_allowed_ip_page'
		);
	}
}
function staff_allowed_ip_page() {
	if ($_POST['staff_allowed_ip_submit'] == 'true') {
		update_option('allowed_staff_ips', trim($_POST['allowed_staff_ips']));
	}
	$allowed_staff_ips = get_option('allowed_staff_ips');
?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php echo __('Staff Allowed IP'); ?></h2><br>
		<form method="post" method="POST">
		<input type="hidden" name="staff_allowed_ip_submit" value="true">
		<?php if ($_POST['staff_allowed_ip_submit'] == 'true') { ?><div id="message" class="updated fade" style="margin-bottom:0px;"><p>Saved.</p></div><?php } ?>
		<table style="width:auto;">
		  <tr>
			<td>IP Addresses:</td>
		  </tr>
		  <tr>
			<td><textarea name="allowed_staff_ips" style="width:150px; height:150px;"><?php echo $allowed_staff_ips; ?></textarea></td>
		  </tr>
		</table>
		<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save') ?>" /></p>
		</form>
	</div>
<?php
}

// post admin dates
add_action('post_submitbox_misc_actions', 'post_created_modified_date_metabox');
function post_created_modified_date_metabox() {
	global $post;
	if ($post->post_type == 'post') {
		$post_created = post_created_save_post($post->ID);
		$post_quoted = get_post_meta($post->ID, '_post_quoted', true);
		$post_received = get_post_meta($post->ID, '_post_received', true); ?>
		<div class="post-created-date-val">
			<span>Created on: <b><?php echo date("M j, Y @ H:i", strtotime($post_created)); ?></b></span>
		</div>
		<div class="post-modified-date-val">
			<span>Modified on: <b><?php echo date("M j, Y @ H:i", strtotime($post->post_modified)); ?></b></span>
		</div>
		<?php if (strlen($post_quoted)) { ?>
			<div class="post-modified-date-val">
				<span>Quoted on: <b><?php echo date("M j, Y @ H:i", strtotime($post_quoted)); ?></b></span>
			</div>
		<?php } ?>
		<?php if (strlen($post_received)) { ?>
			<div class="post-modified-date-val">
				<span>Received on: <b><?php echo date("M j, Y @ H:i", strtotime($post_received)); ?></b></span>
			</div>
		<?php } ?>
	<?php
    }
}

add_action('init', 'backend_actions_init');
function backend_actions_init() {
	global $wpdb, $current_user, $voucher_errors;
	$vuploaded = 0;
	// VOUCHER ACTIONS
	if (isset($_GET['voucher_action'])) {
		if ($_GET['voucher_action'] == 'create') {
			if (strlen(trim($_POST['voucher_code']))) {
				$check_voucher_code = $wpdb->get_var(sprintf("SELECT COUNT(vid) FROM %swps_vouchers WHERE code = '%s'", $wpdb->prefix, $_POST['voucher_code']));
				if (!$check_voucher_code) {
					$voucher_expired = '';
					if (strlen($_POST['voucher_expired_dd']) && strlen($_POST['voucher_expired_mm']) && strlen($_POST['voucher_expired_yy'])) {
						$yy = $_POST['voucher_expired_yy'];
						$mm = $_POST['voucher_expired_mm'];
						$dd = $_POST['voucher_expired_dd'];
						$hh = $_POST['voucher_expired_hh'];
						$ii = $_POST['voucher_expired_ii'];
						$ss = '00';
						if (!$hh) { $hh = '00'; }
						if (!$ii) { $ii = '00'; }
						$voucher_expired = $yy.'-'.$mm.'-'.$dd.' '.$hh.':'.$ii.':'.$ss;
					}
					$insert = array();
					$insert['code'] = trim($_POST['voucher_code']);
					$insert['type'] = $_POST['voucher_type'];
					$insert['option'] = $_POST['voucher_option'];
					$insert['amount'] = trim($_POST['voucher_amount']);
					$insert['expired'] = $voucher_expired;
					$insert['zone'] = $_POST['voucher_zone'];
					$insert['created'] = current_time('mysql');
					$insert['user_id'] = $current_user->ID;
					$wpdb->insert($wpdb->prefix."wps_vouchers", $insert);
				} else {
					$voucher_errors = 'Such Voucher Code already exists.';
				}
			} else {
				$voucher_errors = 'Please enter Voucher Code.';
			}
		} else if ($_GET['voucher_action'] == 'upload') {
			require_once('includes/file.php');
			$file = wp_handle_upload($_FILES['vouchers_csv'], array('test_form' => false), current_time('mysql'));
			if ($file) {
				$sep = ',';
				$csv_file = $file["file"];
				if (($handle = fopen($csv_file, "r")) !== false) {
					while (($data = fgetcsv($handle, 1000, $sep)) !== false) {
						$voucher_code = trim($data[0]);
						$voucher_type = trim($data[1]);
						$voucher_option = trim($data[2]);
						$amount = trim($data[3]);
						$expiry_date = trim($data[4]);
						$shipping_zone = trim($data[5]);

						$type = 1;
						if ($voucher_type == 'M') { $type = 2; }
						$expired = '';
						if ($edlen = strlen($expiry_date)) {
							$expired = str_replace('/', '-', $expiry_date);
							if ($edlen == 10) {
								$expired .= ' 00:00:00';
							}
						}
						$shipping_zone = trim(str_replace('Zone', '', $shipping_zone));

						if (strlen($voucher_code) && $voucher_code != 'voucher code' && $amount && strlen($voucher_option)) {
							$check_voucher_code = $wpdb->get_var(sprintf("SELECT COUNT(vid) FROM %swps_vouchers WHERE code = '%s'", $wpdb->prefix, $voucher_code));
							if (!$check_voucher_code) {
								$insert = array();
								$insert['code'] = $voucher_code;
								$insert['type'] = $type;
								$insert['option'] = $voucher_option;
								$insert['amount'] = $amount;
								$insert['expired'] = $expired;
								$insert['zone'] = $shipping_zone;
								$insert['created'] = current_time('mysql');
								$insert['user_id'] = $current_user->ID;
								$wpdb->insert($wpdb->prefix."wps_vouchers", $insert);
								$vuploaded++;
							}
						}
					}
					fclose($handle);
				}
			}
		} else if ($_GET['voucher_action'] == 'remove') {
			$vid = $_GET['vid'];
			if ($vid) {
				$wpdb->query(sprintf("DELETE FROM %swps_vouchers WHERE vid = %s", $wpdb->prefix, $vid));
			}
		}
		if (!strlen($voucher_errors)) {
			$redir = 'admin.php?page=functions.php&section=vouchers';
			if ($vuploaded > 0) { $redir .= '&vuploaded='.$vuploaded; }
			wp_redirect($redir);
			wp_exit();
		}
	}
}
?>