<?php
$cart_items = $CART["content"];
$subtotal = $CART['total_price'];
$voucher_amount = $order['voucher_amount'];
$shipping = (float)$order['shipping_fee'];
$tax_amount = (float)$order['tax'];
$TOTAL_AM = $order['amount'];
$total_amnt = ($subtotal - $voucher_amount) + $shipping + $tax_amount;
?>
	<div class="payment-order cf">
		<div class="head">
			<h3>Your Order</h3>
		</div>
		<div class="order-content cf">
			<?php
			foreach($cart_items as $cart_item) {
				$cart_item_data = explode("|", $cart_item);
				$item_img = '';
				if (strlen($cart_item_data[6])) {
					$img_src 	= $cart_item_data[6];
					$img_size 	= $OPTION['wps_ProdRelated_img_size'];
					$des_src 	= $OPTION['upload_path'].'/cache';
					$img_file 	= mkthumb($img_src,$des_src,$img_size,'width');    
					$item_img 	= $siteurl.'/'.$des_src.'/'.$img_file;	
					$wlurl = 'index.php?wishlist=add&fpg=checkout&ostep='.$chstep.'&pid='.$cart_item_data[8];
				}
			?>
			<div class="p-item cf">
				<a href="<?php echo get_permalink($cart_item_data[8]); ?>" class="image"><?php if (strlen($item_img)) { ?><img src="<?php echo $item_img; ?>" alt="<?php echo $cart_item_data[2]; ?>" /><?php } ?></a>
				<div class="holder">
					<h4><a href="<?php echo get_permalink($cart_item_data[8]); ?>"><?php echo $cart_item_data[2]; ?></a></h4>
					<?php if (is_user_logged_in()) { ?><a href="<?php echo site_url($wlurl); ?>" class="text-bottom">Move to Wishlist</a><?php } ?>
					<div class="i-price"><?php echo format_price($cart_item_data[3] * $_SESSION['currency-rate'], true); ?></div>
				</div>
			</div>
			<?php } ?>
			<table class="table-order">
				<tr>
					<th>Subtotal:</th>
					<td><?php echo format_price($subtotal * $_SESSION['currency-rate'], true); ?></td>
				</tr>
				<?php if ($chstep > 2) { ?>
					<?php if ($voucher_amount) { // VOUCHER amount ?>
						<tr>
							<th>- Voucher:</th>
							<td><?php echo format_price($voucher_amount * $_SESSION['currency-rate'], true); ?></td>
						</tr>
					<?php } ?>
					<tr><?php // SHIPPING amount ?>
						<th>Shipping:</th>
						<?php if (is_flat_limit_shipping_free($CART['total_price'])) { ?>
							<td style="color:#FF0000">FREE</td>
						<?php } else { ?>
							<td><?php echo format_price($shipping * $_SESSION['currency-rate'], true); ?></td>
						<?php } ?>
					</tr>
					<?php if($OPTION['wps_tax_enable'] && $tax_amount > 0) { // TAX ?>
						<tr>
							<th>Custom Duties/Taxes:</th>
							<td colspan="2"><?php echo format_price($tax_amount * $_SESSION['currency-rate'], true); ?></td>
						</tr>
					<?php } ?>
				<?php } ?>
				<tr>
					<th><strong>Total:</strong></th>
					<td><strong><?php echo format_price($total_amnt * $_SESSION['currency-rate'], true); ?></strong></td>
				</tr>
				<?php if (layaway_is_enabled() && $CART['total_item_num'] == 1 && $_SESSION['layaway_process'] == 1) { ?>
					<?php
					$layaway_amount = $_SESSION['layaway_amount'];
					$TOTAL_AM = $layaway_amount; ?>
					<tr>
						<th><strong>Installment Payment:</strong></th>
						<td><strong><?php echo format_price($layaway_amount * $_SESSION['currency-rate'], true); ?></strong></td>
					</tr>
					<tr>
						<th><strong>Order Total:</strong></th>
						<td><strong><?php echo format_price($TOTAL_AM * $_SESSION['currency-rate'], true); ?></strong></td>
					</tr>
				<?php } ?>
			</table>
			<?php if (layaway_is_enabled() && $CART['total_item_num'] == 1 && $_SESSION['layaway_process'] == 1) { ?>
				<div class="text-holder text-right">
					<a href="/buy-installments/" class="link-orange" target="_blank"><strong>Learn more about buying in Installments</strong></a>
				</div>
			<?php } ?>
		</div>
	</div>
