<?php global $OPTION;
$otable = is_dbtable_there('orders');
$sctable = is_dbtable_there('shopping_cart');
$oid = $_GET['oid'];
$order_payment_data = $_SESSION['order_payment_data'];
if (!$order_payment_data && $oid) {
	$order_payment_data = mysql_fetch_assoc(mysql_query("SELECT * FROM $otable WHERE oid = '$oid' LIMIT 1"));
}
if (!$order_payment_data) {
	wp_redirect(home_url('/'));
	wp_exit();
}
$order = mysql_fetch_assoc(mysql_query("SELECT * FROM $otable WHERE oid = '$oid' LIMIT 1"));
?>
	<?php wishlist_success(); ?>

	<div class="payment-content">
		<div class="payment_steps">
			<div class="payment-step">
				<div class="payment-step-title cf">
					<h3>Delivery</h3>
				</div>
			</div>
			<div class="payment-step">
				<div class="payment-step-title cf">
					<h3>Payment</h3>
				</div>
			</div>
			<div class="payment-step">
				<div class="payment-step-title cf">
					<h3>Order Review</h3>
				</div>
			</div>
			<div class="payment-step open">
				<div class="payment-step-title cf">
					<h3>Confirmation</h3>
				</div>
				<div class="payment-step-content">
					<div class="content-text">
						<?php if (strlen($order_payment_data['error'])) { ?>
							<p style="color:#FF0000;"><?php echo $order_payment_data['error']; ?></p>
						<?php } else { ?>
							<?php if ($order_payment_data['p_option'] == 'paypal' || $order_payment_data['p_option'] == 'audi') { ?>
								<h2>Thank you for your Order!</h2>
								<p>An e-mail confirmation has been sent your address. A tracking number will be sent via e-mail once your items have been shipped.</p>
							<?php } else if ($order_payment_data['p_option'] == 'cod') { ?>
								<h2>Thank you for shopping with us!</h2>
								<p>We will call you to confirm your order.</p>
							<?php } else if ($order_payment_data['p_option'] == 'cash') { ?>
								<h2>Thank you for shopping with us!</h2>
								<p>We will call you to confirm your order. Your items have been reserved for 3 days.</p>
							<?php } else if ($order_payment_data['p_option'] == 'transfer') { ?>
								<h2>Thank you for your Order!</h2>
								<p>Your items have been reserved for 3 days and an e-mail confirmation has been sent to your address.</p>
								<p>Please follow the instructions in the e-mail to complete your order.</p>
							<?php } ?>
						<?php } ?>
						<p>If you have any further questions or concerns, kindly send us an e-mail on <a href="mailto:<?php echo $OPTION['wps_shop_email']; ?>" class="mark"><?php echo $OPTION['wps_shop_email']; ?></a> or call us on <span class="mark"><?php echo $OPTION['wps_shop_questions_phone']; ?></span></p>
					</div>
					<input type="submit" value="Continue Shopping" class="btn-orange right" onclick="widnow.location.href='<?php echo home_url('/'); ?>';">
				</div>
			</div>
		</div>
	</div>
	<div class="payment-aside">
		<div class="payment-order cf">
			<div class="head">
				<h3>Your Order</h3>
			</div>
			<?php $cart_items = $wpdb->get_results(sprintf("SELECT * FROM %s WHERE order_id = %s", $sctable, $oid));
			if ($cart_items) { ?>
			<div class="order-content cf">
				<?php
				foreach($cart_items as $cart_item) {
					$item_img = '';
					if (strlen($cart_item->item_thumb)) {
						$img_src 	= $cart_item->item_thumb;
						$img_size 	= $OPTION['wps_ProdRelated_img_size'];
						$des_src 	= $OPTION['upload_path'].'/cache';
						$img_file 	= mkthumb($img_src,$des_src,$img_size,'width');    
						$item_img 	= $siteurl.'/'.$des_src.'/'.$img_file;	
					}
				?>
				<div class="p-item cf">
					<a href="<?php echo get_permalink($cart_item->postID); ?>" class="image"><?php if (strlen($item_img)) { ?><img src="<?php echo $item_img; ?>" alt=" " /><?php } ?></a>
					<div class="holder">
						<h4><a href="<?php echo get_permalink($cart_item->postID); ?>"><?php echo $cart_item->item_name; ?></a></h4>
						<div class="i-price"><?php echo format_price($cart_item->item_price * $_SESSION['currency-rate'], true); ?></div>
					</div>
				</div>
				<?php }
				$subtotal = $order['net'];
				$voucher_amount = $order['voucher_amount'];
				$shipping = $order['shipping_fee'];
				$tax_amount = $order['tax'];
				$TOTAL_AM = $order['amount'];
				$total_amnt = ($subtotal - $voucher_amount) + $shipping + $tax_amount;

				if ($order['layaway_process'] == 1) {
					$last_layaway_order = mysql_fetch_assoc(mysql_query("SELECT * FROM $otable WHERE layaway_order = '$oid' ORDER BY oid DESC LIMIT 1"));
					if ($last_layaway_order) {
						$TOTAL_AM = $last_layaway_order['amount'];
					}
				}
				?>
				<table class="table-order">
					<tr>
						<th>Subtotal:</th>
						<td><?php echo format_price($subtotal * $_SESSION['currency-rate'], true); ?></td>
					</tr>
					<?php // VOUCHER amount ?>
					<?php if ($voucher_amount) { ?>
						<tr>
							<th>- Voucher:</th>
							<td><?php echo format_price($voucher_amount * $_SESSION['currency-rate'], true); ?></td>
						</tr>
					<?php } ?>
					<?php // SHIPPING amount ?>
					<tr>
						<th>Shipping:</th>
						<?php if (is_flat_limit_shipping_free($CART['total_price'])) { ?>
							<td style="color:#FF0000">FREE</td>
						<?php } else { ?>
							<td><?php echo format_price($shipping * $_SESSION['currency-rate'], true); ?></td>
						<?php } ?>
					</tr>
					<?php // TAX amount ?>
					<?php if($OPTION['wps_tax_enable'] && $tax_amount > 0) { // TAX ?>
						<tr>
							<th>Custom Duties/Taxes:</th>
							<td colspan="2"><?php echo format_price($tax_amount * $_SESSION['currency-rate'], true); ?></td>
						</tr>
					<?php } ?>
					<tr>
						<th><strong>Total:</strong></th>
						<td><strong><?php echo format_price($total_amnt * $_SESSION['currency-rate'], true); ?></strong></td>
					</tr>
					<?php if (layaway_is_enabled() && $order['layaway_process'] == 1) { ?>
						<tr>
							<th>Installment Payment:</th>
							<td><?php echo format_price($TOTAL_AM * $_SESSION['currency-rate'], true); ?></td>
						</tr>
						<tr>
							<th><strong>Order Total:</strong></th>
							<td><strong><?php echo format_price($TOTAL_AM * $_SESSION['currency-rate'], true); ?></strong></td>
						</tr>
					<?php } ?>
				</table>
			</div>
			<?php } ?>
		</div>
	</div>
<?php
$custom_tracking = $OPTION['wps_custom_tracking'];
if($custom_tracking !=''){	echo $custom_tracking;}

ga_ecommerce_tracking_code($order_payment_data['who']);
?>
<script type="text/javascript">
document.write(unescape("%3Cscript id=%27pap_x2s6df8d%27 src=%27" + (("https:" == document.location.protocol) ? "https://" : "http://") + "perf.clickmena.com/scripts/trackjs.js%27 type=%27text/javascript%27%3E%3C/script%3E"));
</script>
<script data-cfasync="false" type="text/javascript">
PostAffTracker.setAccountId('66acecfb'); 
var sale = PostAffTracker.createSale();
price = '<?php echo $order_payment_data['amount']; ?>';
sale.setTotalCost(price);
if(price <= 2000){    sale.setCustomCommission('%8');}
else if(price > 2000 && price <= 3000) { sale.setCustomCommission('%6');}
else if(price > 3000 && price <= 10000) { sale.setCustomCommission('%4'); }
else{sale.setCustomCommission('%2');}
sale.setCurrency('<?php echo ($OPTION['wps_currency_code'] == '')? 'USD':$OPTION['wps_currency_code']; ?>');
sale.setOrderID('<?php echo $order_payment_data['tracking_id']; ?>');
sale.setProductID('<?php echo $order_payment_data['itemname']; ?>');
PostAffTracker.register();
</script>
<?php
//unset($_SESSION['order_payment_data']);
?>