<?php
// ---------------------------------------------------------
// Layaway Functions
// ---------------------------------------------------------
function layaway_set_session() {
	$layaway_amount = (float)$_POST['layaway_amount'];
	$minerror = false;

	if ($_POST['layaway_process'] == 1) {
		$layaway_def_amount = (float)$_POST['layaway_def_amount'];
		$layaway_cart_total = (float)$_POST['layaway_cart_total'];
		$layaway_amount = round($layaway_amount);

		if ($_SESSION['currency-rate']) {
			$layaway_amount = round($layaway_amount / $_SESSION['currency-rate']);
		}

		if ($layaway_amount < $layaway_def_amount) {
			$layaway_amount = $layaway_def_amount;
			$minerror = true;
		} else if ($layaway_amount > $layaway_cart_total) {
			$layaway_amount = $layaway_cart_total;
		}
	}
	$_SESSION['layaway_process'] = $_POST['layaway_process'];
	$_SESSION['layaway_amount'] = $layaway_amount;
	$_SESSION['layaway_currency_code'] = $_SESSION['currency-code'];
	if ($minerror) {
		$basket_url = get_option('home').'?showCart=1&minerror=1';
		wp_redirect($basket_url);
		exit();
	}
}

function layaway_clean_session() {
	unset($_SESSION['layaway_process']);
	unset($_SESSION['layaway_amount']);
	unset($_SESSION['layaway_order']);
	unset($_SESSION['layaway_order_data']);
}

function layaway_is_enabled() {
	$enabled = get_option('wps_layaway_enable');
	if ($enabled == 'true') {
		return true;
	}
	return false;
}

function layaway_get_percent_number() {
	$perc = get_option('wps_layaway_percent');
	$perc = str_replace('%', '', $perc);
	if (!$perc) { $perc = 25; }
	return $perc;
}

function layaway_get_product_days($pid, $d = '') {
	if (!strlen($d)) {
		$pdata = get_post($pid);
		$d = $pdata->post_date;
	}
	return ceil((time() - strtotime($d)) / 86400);
}

function layaway_get_amount($price, $aed = false) {
	$perc = layaway_get_percent_number();
	$pprice = round($price);
	if ($aed) {
		$pprice = $pprice * get_option('wps_exr_aed');
	}
	return round(($pprice / 100) * $perc);
}
function layaway_get_lorder_total($oid) {
	global $wpdb;
	$total = 0;
	$otable = is_dbtable_there('orders');
	$odata = $wpdb->get_row(sprintf("SELECT * FROM %s WHERE oid = %s", $otable, $oid));
	if ($odata) {
		$total = $odata->net + $odata->shipping_fee + $odata->tax;
	}
	return $total;
}

function layaway_get_process_amounts($order_id) {
	global $wpdb, $current_user;
	$remove_charge = get_user_meta($current_user->ID, 'remove_charge', true);
	$pamounts = array('total' => 0, 'paid' => 0);
	$otable = is_dbtable_there('orders');
	$layaway_order = $wpdb->get_row(sprintf("SELECT * FROM %s WHERE oid = %s", $otable, $order_id));
	if ($layaway_order) {
		$total = $layaway_order->net + $layaway_order->shipping_fee + $layaway_order->tax;
		$order_date = $layaway_order->layaway_date;
		if (!$order_date) { $order_date = $layaway_order->order_time; }
		$paid = $layaway_order->amount;

		$odays = ceil((time() - $order_date) / 86400);
		if ($odays > 30 && $layaway_order->level == '3' && $remove_charge != 1) {
			$am_5_percent = ($total / 100) * 5;
			$total = $total + $am_5_percent;
		}
		$balance = $total - $paid;
		if ($balance < 0) { $balance = 0; }
		$pamounts = array('total' => round($total), 'paid' => round($paid), 'balance' => round($balance), 'order_date' => $order_date, 'odays' => $odays);
	}
	return $pamounts;
}

function layaway_process_action($order) {
	global $wpdb;
	$otable = is_dbtable_there('orders');
	$sctable = is_dbtable_there('shopping_cart');
	if ($order['layaway_order'] > 0) {
		$order_id = $order['layaway_order'];
		$pamounts = layaway_get_process_amounts($order_id);
		$oamount = $pamounts['paid'] + $order['amount'];
		if ($oamount > $pamounts['total']) { $oamount = $pamounts['total']; }
		$wpdb->query(sprintf("UPDATE %s SET amount = '%s', order_time = '%s' WHERE oid = %s", $otable, $oamount, time(), $order_id));
	} else {
		// create root layaway order
		$who = time().'-'.substr(md5(time()),22);
		$track_parts = explode("-", $who);
		$new_order = $order;
		unset($new_order['oid']);
		$new_order["who"] = $who;
		$new_order["level"] = '3';
		$new_order["layaway_date"] = $order['order_time'];
		$new_order['tracking_id'] = $track_parts[0];
		$wpdb->insert($otable, $new_order);
		$order_id = $wpdb->insert_id;

		$scdata = $wpdb->get_row(sprintf("SELECT * FROM %s WHERE who = '%s'", $sctable, $order['who']));
		if ($scdata) {
			$sc_new = array();
			$sc_new['item_id'] = $scdata->item_id;
			$sc_new['postID'] = $scdata->postID;
			$sc_new['item_name'] = $scdata->item_name;
			$sc_new['item_amount'] = $scdata->item_amount;
			$sc_new['item_price'] = $scdata->item_price;
			$sc_new['item_weight'] = $scdata->item_weight;
			$sc_new['item_thumb'] = $scdata->item_thumb;
			$sc_new['item_file'] = $scdata->item_file;
			$sc_new['item_attributs'] = $scdata->item_attributs;
			$sc_new['item_personal'] = $scdata->item_personal;
			$sc_new['who'] = $who;
			$sc_new['level'] = $scdata->level;
			$wpdb->insert($sctable, $sc_new);
		}

		// update layaway order id
		$wpdb->query(sprintf("UPDATE %s SET layaway_order = '%s' WHERE oid = %s", $otable, $order_id, $order['oid']));
	}
	// check completed
	$pamounts = layaway_get_process_amounts($order_id);
	$total = $pamounts['total'];
	$paid = $pamounts['paid'];
	if ($paid >= $total) {
		$wpdb->query(sprintf("UPDATE %s SET level = '4', order_time = '%s' WHERE oid = %s", $otable, time(), $order_id));
	}
	return $order_id;
}

// cron job for reminder email for purchase
add_action('layaway_reminder_cron', 'layaway_reminder_send');
function layaway_cron_job() {
	if (!wp_next_scheduled('layaway_reminder_cron')) {
		wp_schedule_event(time(), 'daily', 'layaway_reminder_cron');
	}
}
add_action('wp', 'layaway_cron_job');

function layaway_reminder_send() {
	global $wpdb;
	$layaway_reminder_last = get_option("layaway_reminder_last");
	if (layaway_is_enabled() && $layaway_reminder_last != date('Y-m-d')) {
		$otable = is_dbtable_there('orders');
		$email_subject = get_option('wps_layaway_reminder_email_subject');
		$email_message = get_option('wps_layaway_reminder_email_message');
		$layaway_orders = $wpdb->get_results(sprintf("SELECT * FROM %s WHERE level = '3' AND layaway_process = 1 ORDER BY oid DESC", $otable));
		if ($layaway_orders) {
			foreach($layaway_orders as $layaway_order) {
				$order_date = $layaway_order->order_time;
				$odays = ceil((time() - $order_date) / 86400);
				if ($odays == 25) {
					$pamounts = layaway_get_process_amounts($layaway_order->oid);
					$total = $pamounts['total'];
					$paid = $pamounts['paid'];
					if ($paid < $total) {
						NWS_send_email($layaway_order->email, $email_subject, $email_message);
						log_action('layaway_reminder_email', 'OrderID: '.$layaway_order->oid.'; Client Email: '.$layaway_order->email);
					}
				}
			}
		}
		update_option("layaway_reminder_last", date('Y-m-d'));
	}
}
?>