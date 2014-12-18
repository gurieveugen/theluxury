<?php
/*
Template Name: My Purchases
*/
global $wpdb, $current_user, $OPTION;
get_header();
if (is_user_logged_in()) {
get_currentuserinfo(); // grabs the user info and puts into vars
$user_ID = $current_user->ID;

if(isset($_REQUEST['resend'])) resend_invitation();
if(isset($_REQUEST['invdelete'])) delete_invitation();
if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'credit') $credit = true;
else $credit = false;

// this is a "fake cronjob" = whenever default index page is called - the age of dlinks is checked - and removed if necessary
$DIGITALGOODS = load_what_is_needed('digitalgoods');	//change.9.10
$DIGITALGOODS->delete_dlink();							//change.9.10
$otable = is_dbtable_there('orders');
$optable = is_dbtable_there('shopping_cart');
$remove_charge = get_user_meta($user_ID, 'remove_charge', true);
?>
<div class="my-purchases-wrap">
	<?php the_content(); ?>
	<?php
	$my_orders = $wpdb->get_results(sprintf("SELECT * FROM %s WHERE user_id = %s AND level IN ('3','4','5','6','7','8') ORDER BY oid DESC", $otable, $user_ID));
	if ($my_orders) { ?>
	<div class="purchases-table">
		<table width="100%">
			<tr>
				<td class="mp-head"><?php _e('Order ID','wpShop'); ?></td>
				<td class="mp-head"><?php _e('Date','wpShop'); ?></td>
				<td class="mp-head" width="120"><?php _e('Details','wpShop'); ?></td>
				<td class="mp-head"><?php _e('Subtotal','wpShop'); ?></td>
				<td class="mp-head"><?php _e('Shipping','wpShop'); ?></td>
				<td class="mp-head"><?php _e('Tax','wpShop'); ?></td>
				<td class="mp-head"><?php _e('Paid','wpShop'); ?></td>
			</tr>
			<?php foreach($my_orders as $my_order) {
				$order_id = $my_order->oid;
				$order_date = $my_order->order_time;
				$layaway_date = $my_order->layaway_date;
				$subtotal = $my_order->net;
				$shipping = $my_order->shipping_fee;
				$tax = $my_order->tax;
				$total = $subtotal + $shipping + $tax;
				$amount = $my_order->amount;
	
				$total_purchased_amount = $amount;
	
				$currency_code = $my_order->currency_code;
				$currency_rate = $my_order->currency_rate;
				if (!strlen($currency_code)) { $currency_code = 'USD'; }
				if (!$currency_rate) { $currency_code = 1; }
				$ppref = '';
				$psuf = '';
				if ($currency_code == 'USD') { $ppref = '$'; } else { $psuf = $currency_code; }
			?>
			<tr>
				<td><?php echo $OPTION[wps_order_no_prefix].$order_id; ?></td>
				<td><?php if ($order_date) { echo date("d.m.Y H:i:s", $order_date); } ?></td>
				<td>
					<a href="#order-details" class="odetails-link" rel="odetails-<?php echo $order_id; ?>"><?php _e('Show Details','wpShop'); ?></a>
					<div id="odetails-<?php echo $order_id; ?>" class="odetails-div">
						<img src="<?php bloginfo('template_url'); ?>/images/close-icon.png" class="odetails-close" rel="odetails-<?php echo $order_id; ?>">
						<?php
						$my_order_products = $wpdb->get_results(sprintf("SELECT * FROM %s WHERE who = '%s' ORDER BY cid DESC", $optable, $my_order->who));
						if ($my_order_products) {
						?>
						<table width="100%" class="mp-items">
							<tr>
								<td class="odhead"><?php _e('ID','wpShop'); ?></td>
								<td class="odhead"><?php _e('Item','wpShop'); ?></td>
								<td class="odhead"><?php _e('Qty','wpShop'); ?></td>
								<td class="odhead"><?php _e('Unit','wpShop'); ?></td>
								<td class="odhead"><?php _e('Total','wpShop'); ?></td>
								<td class="odhead"></td>
							</tr>
							<?php foreach($my_order_products as $my_order_product) {
								$item_price = $my_order_product->item_price;
								$item_amount = $item_price * $my_order_product->item_amount;
							?>
							<tr>
								<td><?php echo $my_order_product->item_id; ?></td>
								<td><?php echo $my_order_product->item_name; ?></td>
								<td><?php echo $my_order_product->item_amount; ?></td>
								<td><?php echo format_price($item_price * $currency_rate); ?></td>
								<td><?php echo format_price($item_amount * $currency_rate); ?></td>
								<td></td>
							</tr>
							<?php } ?>
						</table>
						<?php } ?>
					</div>
				</td>
				<td><?php echo $ppref.format_price($subtotal * $currency_rate).$psuf; ?></td>
				<td><?php echo $ppref.format_price($shipping * $currency_rate).$psuf; ?></td>
				<td><?php echo $ppref.format_price($tax * $currency_rate).$psuf; ?></td>
				<td><?php echo $ppref.format_price($amount * $currency_rate).$psuf; ?></td>
			</tr>
			<?php
			if ($my_order->layaway_process == 1) {
			  $oamounts = layaway_get_process_amounts($my_order->oid);
			  $total = $oamounts['total'];
			  $paid = $oamounts['paid'];
			  $odays = $oamounts['odays'];
			  $balance = $oamounts['balance'];
			  if ($paid < $total) {
				if ($odays > 30 && $remove_charge != 1) {
					$percent_note = ' (+5% fee after 30 days)';
				}
			?>
			<tr class="row-1">
				<td colspan="7" style="text-align:right"><?php _e('Balance','wpShop'); ?>: <?php echo $ppref.format_price($balance * $currency_rate).$psuf; ?><?php echo $percent_note; ?>&nbsp;&nbsp;<a href="#continue-payment" class="continue-payment"><?php _e('Continue Payment','wpShop'); ?></a>
				<form method="POST">
				<input type="hidden" name="layaway_payment" value="true" />
				<input type="hidden" name="loid" value="<?php echo $order_id; ?>" />
				<input type="hidden" name="cmd" value="add" />
				<input type="hidden" name="post_id" value="<?php echo $my_order_product->postID; ?>" />
				</form>
				</td>
			</tr>
			<?php } // if ($total_purchased_amount < $total) { ?>
			<?php } // if ($my_order->layaway_process == 1) { ?>
			<tr><td colspan="7" height="1" class="mp-sep"></td></tr>
			<?php
			}
			?>
		</table>
	</div>
	<?php
	} else {
		_e("Currently You don't have any purchases.","wpShop");
		echo '<br /><br />';
	}
	?>
	<a class="btn-orange" href="<?php bloginfo('home'); ?>"><?php _e('Continue Shopping','wpShop');?></a>
</div>
<?php
} else {
	echo '<p>You are not allowed to view this page.</p>';
}
get_footer();
?>
