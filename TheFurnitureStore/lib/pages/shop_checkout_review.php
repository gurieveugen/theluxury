<?php
global $OPTION;
$LANG['delivery'] 				= __('Delivery Option:','wpShop');
$LANG['payment']				= __('Payment Option:','wpShop');
$LANG['change'] 				= __('edit','wpShop');
$LANG['address_data']			= __('Address:','wpShop');
$LANG['name_email']				= __('Your Name and Email:','wpShop');
$LANG['comments']				= __('Your Order Comments:','wpShop');
$LANG['your_order']				= __('Your Order:','wpShop');
$LANG['article'] 				= __('Item','wpShop');
$LANG['amount'] 				= __('Quantity','wpShop');
$LANG['unit_price'] 			= __('Item Price','wpShop');
$LANG['total'] 					= __('Item Total','wpShop');
$LANG['subtotal_cart']			= __('Subtotal:','wpShop');
$LANG['shipping_fee_1']			= __('Shipping:','wpShop');
$LANG['tax']					= __('Custom Duties/Taxes:','wpShop');
$LANG['total_cart']				= __('Order Total:','wpShop');
$LANG['total_single']			= __('Total:','wpShop');
$LANG['incl']					= __('incl.','wpShop');
$LANG['remark'] 				= __('Remark','wpShop');
$LANG['dont_close_browser_1'] 	= __('Please, do NOT close this Browser Window until you see the Confirmation Page','wpShop');
$LANG['dont_close_browser_2']	= __('- so that the Order Process can be Completed.','wpShop');		
$LANG['layaway_total']			= __('Installment Payment:','wpShop');
$LANG['balance_amount']			= __('Balance amount:','wpShop');

$order 		= process_order(3);
$CART 		= show_cart();
$cart_comp 	= cart_composition($_SESSION['cust_id']);	
//get shipping fees
$shipping = 0;
if($cart_comp != 'digi_only' && $_SESSION['layaway_order'] == 0){  // however the shipping option might be, no shipping for a digital product only
	$shipping = calculate_shipping($order['d_option'],$CART['total_price'],$CART['total_weight'],$CART['total_item_num'],$order['country']);
}
//get tax
$tax_amount = 0;
if ($_SESSION['layaway_order'] == 0) {
	$tax_amount = calculate_tax($order, $CART['total_price']);
}
//update the order
$voucher_amount = 0;
if ($_SESSION['checkout_voucher']) {
	$vdata = $_SESSION['checkout_voucher'];
	$voucher_amount = $vdata->amount;
	if ($vdata->option == 'P') {
		$voucher_amount = round(($CART['total_price'] / 100) * $vdata->amount, 2);
	}
}

$TOTAL_AM	= format_price(update_order($CART['total_weight'], $shipping, $CART['total_price'], $voucher_amount, $tax_amount));
$total_amnt = ($CART['total_price'] - $voucher_amount) + $shipping + $tax_amount;

$d_labels			= array();
$d_labels['pickup']		= __('Pick Up','wpShop');
$d_labels['post']		= __('Delivery','wpShop');
$d_labels['download']	= __('You will Receive your Download Links on this Website, after your Payment has been Successfully Completed.','wpShop');
$d_labels['email']		= __('Delivery by Email','wpShop');
	
	
$p_labels				= array();
$p_labels['paypal']		= __('PayPal','wpShop');
$p_labels['paypal_pro']	= __('Credit Card - PayPal Pro','wpShop');
$p_labels['transfer']	= __('Bank Transfer in Advance','wpShop');
$p_labels['cash']		= __('Payment at our Shop','wpShop');
$p_labels['cc_authn']	= __('Credit Card Authorize.net','wpShop');
$p_labels['g2p_authn']	= __('Gate2Play.com','wpShop');
$p_labels['alertpay']	= __('Credit Card Alertpay.com','wpShop');
$p_labels['2checkout']	= __('Credit Card 2checkout.com','wpShop');
$p_labels['audi']	= __('Credit Card Payment','wpShop');	
$p_labels['cc_wp']		= __('Credit Card WorldPay','wpShop');
$p_labels['cod']		= __('Cash on Delivery','wpShop') .' '. $OPTION['wps_cod_who_note'];

if ($_SESSION['layaway_order'] > 0) { $dstyle = ' style="visibility:hidden;"'; }

wps_shop_process_steps(4);
?>
<div class="payment-section">
	<h1 class="title">Delivery & Billing Address</h1>
	<div class="holder">
		<?php if(isset($_REQUEST['error']) && $_REQUEST['error'] == 'g2p_error') { ?>
			<p class="error">Your transaction is failed. There has been an error processing this transaction.</p>
		<?php } ?>
		<div class="review-order">
			<div class="box"<?php echo $dstyle; ?>>
				<h3 class="heading"><?php echo $LANG['delivery']; ?> <a href="?orderNow=1"><?php echo $LANG['change']; ?></a></h3>
				<div class="holder">
					<p><?php echo $d_labels[$order['d_option']]; ?></p>
				</div>
			</div>
			<div class="box">
				<h3 class="heading"><?php echo $LANG['payment']; ?> <a href="?orderNow=1"><?php echo $LANG['change']; ?></a></h3>
				<div class="holder">
					<p><?php echo $p_labels[$order['p_option']]; ?></p>
				</div>
			</div>
			<div class="box">
				<h3 class="heading"><?php echo $LANG['address_data']; ?> <a href="?orderNow=2&dpchange=1"><?php echo $LANG['change']; ?></a></h5>
				<div class="holder">
					<?php
					$ba_title = __('Billing and Shipping Address','wpShop');
					if($order['d_addr'] == '1') {
						$ba_title = __('Billing Address','wpShop');
					}
					?>
					<h5><?php echo $ba_title; ?></h5>
					<p>
						<?php echo address_format($order); ?>
						<?php echo '<br/>'.$order['email']; ?>
						<?php echo '<br/>'.$order['telephone']; ?>
					</p>
					<?php if($order['d_addr'] == '1') { ?>
						<h5><?php echo __('Delivery Address:','wpShop'); ?></h5>
						<p>
							<?php echo address_format(retrieve_delivery_addr(), 'd-addr'); ?>
							<?php echo '<br/>'.$order['email']; ?>
							<?php echo '<br/>'.$order['telephone']; ?>
						</p>
					<?php } ?>
					<?php if (strlen($order['custom_note'])) { ?>
						<h5><?php echo $LANG['comments']; ?></h5>
						<p><?php echo $order['custom_note']; ?></p>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php if($CART['status'] == 'filled') { ?>
		<form class="order_form" action="?orderNow=3" style="margin-top: 0px;" method="POST">
			<table class='order_table white'>
				<tr>
					<th colspan="2">Item</th>
					<th class="third">Item Price</th>
					<th class="last">Remove</th>
				</tr>
				<?php loop_products($CART);	?>
				<tr class="sums top">
					<td colspan="2"><?php echo $LANG['subtotal_cart']; ?></td>
					<td colspan="2"><?php echo format_price($CART['total_price'] * $_SESSION['currency-rate'], true); ?></td>
				</tr>
				<?php if ($_SESSION['layaway_order'] > 0) {
					$oamounts = layaway_get_process_amounts($_SESSION['layaway_order']);
					$paid = $oamounts['paid'];
					$balance = $oamounts['balance'];
					$TOTAL_AM = $balance; ?>
					<tr class="sums">
						<td colspan="2"><?php echo __('Paid Amount','wpShop'); ?>:</td>
						<td colspan="2"><?php echo format_price($paid * $_SESSION['currency-rate'], true); ?></td>
					</tr>
					<tr class="sums">
						<td colspan="2"><?php echo __('Balance Amount','wpShop'); ?>:</td>
						<td colspan="2"><?php echo format_price($balance * $_SESSION['currency-rate'], true); ?></td>
					</tr>
				<?php } else { ?>
					<?php // VOUCHER amount ?>
					<?php if ($voucher_amount) { ?>
						<tr class="sums">
							<td colspan="2"><?php echo __('- Voucher','wpShop'); ?>:</td>
							<td colspan="2"><?php echo format_price($voucher_amount * $_SESSION['currency-rate'], true); ?></td>
						</tr>
					<?php } ?>
					<?php // SHIPPING amount ?>
					<tr class="sums">
						<td colspan="2"><?php echo $LANG['shipping_fee_1']; ?></td>
						<td colspan="2"><?php echo format_price($shipping * $_SESSION['currency-rate'], true); ?></td>
					</tr>
					<?php // TAX amount ?>
					<?php if($OPTION['wps_tax_enable'] && $tax_amount > 0) { // TAX ?>
						<tr class="sums">
							<td colspan="2"><?php echo $LANG['tax']; ?></td>
							<td colspan="2"><?php echo format_price($tax_amount * $_SESSION['currency-rate'], true); ?></td>
						</tr>
					<?php } ?>
					<?php // TOTAL amount
					$totlabel = $LANG['total_cart'];
					if (layaway_is_enabled() && $CART['total_item_num'] == 1 && $_SESSION['layaway_process'] == 1) { $totlabel = $LANG['total_single']; }
					?>
					<tr class="sums bottom">
						<td colspan="2"><?php echo $totlabel; ?></td>
						<td colspan="2"><?php echo format_price($total_amnt * $_SESSION['currency-rate'], true); ?></td>
					</tr>
				<?php } ?>
				<?php // LAYAWAY amount ?>
				<?php if (layaway_is_enabled() && $CART['total_item_num'] == 1 && $_SESSION['layaway_process'] == 1) { ?>
					<?php
					$layaway_amount = $_SESSION['layaway_amount'];
					$TOTAL_AM = $layaway_amount; ?>
					<tr class="sums">
						<td colspan="2"><?php echo $LANG['layaway_total']; ?></td>
						<td colspan="2"><?php echo format_price($layaway_amount * $_SESSION['currency-rate'], true); ?></td>
					</tr>
					<tr class="sums bottom">
						<td colspan="2"><?php echo $LANG['total_cart']; ?></td>
						<td colspan="2"><?php echo format_price($TOTAL_AM * $_SESSION['currency-rate'], true); ?></td>
					</tr>
				<?php } ?>
			</table>
			<input type="hidden" name="order_review" value="update">
		</form>
		<?php } ?>
		<?php // call start of Payment method 	
		$Your_Order = __('Order No.','wpShop') . $OPTION['wps_order_no_prefix'] . $order['oid'];
		$date_order = NWS_translate_date();
		// paypal and audi payment needs custom redirect form
		if ($order['p_option'] == 'paypal' || $order['p_option'] == 'audi') {
			include WP_CONTENT_DIR.'/themes/'.WPSHOP_THEME_NAME.'/lib/modules/payment/'.$order['p_option'].'/start.php';
		} else {
		?>
			<form class="order_now" method="POST" action="?orderNow=complete" target="_top">
				<input type="hidden" name="order_id" value="<?php echo $order['oid']; ?>" />
				<input type="hidden" name="item_name" value="<?php echo $Your_Order.' - '.$date_order; ?>" />
				<input type="hidden" name="amount" value="<?php echo $TOTAL_AM; ?>" />
				<input type="hidden" name="p_option" value="<?php echo $order['p_option']; ?>" />
				<input type="hidden" name="order_step" value="3">
				<div class="button-right">
					<input type="submit" class="btn-orange" name="add" value="Place Order" />
				</div>
			</form>	
		<?php } ?>
		<div id="editOrderOverlay" class="overlay overlayAlt">
			<div class="editOrderWrap"></div>
		</div><!-- editOrderOverlay -->
	</div>
</div>
