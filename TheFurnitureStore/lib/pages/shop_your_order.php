<?php
$CART = show_cart();
$order = get_current_order();
$cart_items = $CART["content"];
$subtotal = $CART['total_price'];
$shipping = (float)$order['shipping_fee'];
$tax_amount = (float)$order['tax'];
$TOTAL_AM = $order['amount'];

$voucher_amount = 0;
if ($_SESSION['checkout_voucher']) {
	$voucher_amount = nws_get_voucher_amount($CART['total_price'], $_SESSION['checkout_voucher']);
}

$total_amnt = ($subtotal - $voucher_amount) + $shipping + $tax_amount;

$is_layaway_allowed = false;
$is_layaway_process = false;
if (layaway_is_enabled() && $CART['total_item_num'] == 1) {
	$pdetails = explode("|", $cart_items[0]);
	$days = layaway_get_product_days($pdetails[8]);
	if ($days >= 8) {
		$is_layaway_allowed = true;
		if ($_SESSION['layaway_order'] > 0) {
			$oamounts = layaway_get_process_amounts($_SESSION['layaway_order']);
			$layaway_amount = $oamounts['balance'];
		} else {
			$layaway_amount = layaway_get_amount($CART['total_price']);
		}
		$layaway_def_amount = $layaway_amount;
		if ($_SESSION['layaway_process'] == 1 && $_SESSION['layaway_amount']) {
			$layaway_amount = $_SESSION['layaway_amount'];
		}
	}
	if ($_SESSION['layaway_process'] == 1) {
		$is_layaway_process = true;
	}
}
?>
	<div class="payment-order checkout-your-order cf">
		<div class="head">
			<h3>Your Order</h3>
		</div>
		<div class="order-content cf">
		<?php
		foreach($cart_items as $cart_item) {
			$cart_item_data = explode("|", $cart_item);
			$prod_id = $cart_item_data[8];
			$item_img = '';
			if (strlen($cart_item_data[6])) {
				$img_src 	= $cart_item_data[6];
				$img_size 	= $OPTION['wps_ProdRelated_img_size'];
				$des_src 	= $OPTION['upload_path'].'/cache';
				$img_file 	= mkthumb($img_src,$des_src,$img_size,'width');    
				$item_img 	= $siteurl.'/'.$des_src.'/'.$img_file;	
				$wlurl = 'index.php?wishlist=add&fpg=checkout&pid='.$cart_item_data[8];
			}
			?>
			<div class="p-item cf">
				<a href="<?php echo get_permalink($prod_id); ?>" class="image"><?php if (strlen($item_img)) { ?><img src="<?php echo $item_img; ?>" alt="<?php echo $cart_item_data[2]; ?>" /><?php } ?></a>
				<div class="holder">
					<h4><a href="<?php echo get_permalink($prod_id); ?>"><?php echo $cart_item_data[2]; ?></a></h4>
					<a href="<?php echo site_url($wlurl); ?>" class="text-bottom"<?php if (!is_user_logged_in()) { echo ' style="display:none;"'; } ?>>Move to Wishlist</a>
					<div class="i-price"><?php echo format_price($cart_item_data[3] * $_SESSION['currency-rate'], true); ?></div>
				</div>
			</div>
		<?php } ?>
		<?php if ($is_layaway_allowed) { ?>
			<div class="p-item cf">
				<form name="ch_purch_in_install_form" class="ch-purch-in-install-form" method="POST">
				<ul class="yo-installmets">
					<li>Purchase in installments:&nbsp;&nbsp;</li>
					<li><input type="radio" name="layaway_process" value="1" onclick="document.ch_purch_in_install_form.submit();"<?php if ($_SESSION['layaway_process'] == 1) { echo ' CHECKED'; } ?>></li>
					<li>&nbsp;Yes&nbsp;&nbsp;&nbsp;</li>
					<li><input type="radio" name="layaway_process" value="0" onclick="document.ch_purch_in_install_form.submit();"<?php if (!$_SESSION['layaway_process']) { echo ' CHECKED'; } ?>></li>
					<li>&nbsp;No&nbsp;&nbsp;&nbsp;</li>
					<li style="padding:0px;"><a href="#layaway-purchase" class="installments-popup-link"><img src="<?php echo TEMPLURL; ?>/images/question-icon.gif"></a></li>
				</ul>
				<input type="hidden" name="proceed2Checkout" value="true">
				<input type="hidden" name="layaway_amount" value="<?php echo format_price($layaway_amount * $_SESSION['currency-rate']); ?>">
				<input type="hidden" name="layaway_def_amount" value="<?php echo $layaway_def_amount; ?>">
				<input type="hidden" name="layaway_cart_total" value="<?php echo $subtotal; ?>">
				</form>
			</div>
		<?php } ?>
		<table class="table-order">
			<tr>
				<th>Subtotal:</th>
				<td><?php echo format_price($subtotal * $_SESSION['currency-rate'], true); ?></td>
			</tr>
			<?php if ($_SESSION['layaway_order'] > 0 && $CART['total_item_num'] == 1) {
				$oamounts = layaway_get_process_amounts($_SESSION['layaway_order']); ?>
				<tr>
					<th>Paid amount:</th>
					<td><?php echo format_price($oamounts['paid'] * $_SESSION['currency-rate'], true); ?></td>
				</tr>
				<tr>
					<th>Balance amount:</th>
					<td><?php echo format_price($oamounts['balance'] * $_SESSION['currency-rate'], true); ?></td>
				</tr>
				<?php if (!$is_layaway_process) { ?>
				<tr>
					<th><strong>Order Total:</strong></th>
					<td><strong><?php echo format_price($oamounts['balance'] * $_SESSION['currency-rate'], true); ?></strong></td>
				</tr>
				<?php } ?>
			<?php } else { ?>
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
						<td><?php echo format_price($tax_amount * $_SESSION['currency-rate'], true); ?></td>
					</tr>
				<?php } ?>
				<tr>
					<th><strong>Total:</strong></th>
					<td><strong><?php echo format_price($total_amnt * $_SESSION['currency-rate'], true); ?></strong></td>
				</tr>
			<?php } ?>
			<?php if ($is_layaway_process) {
				$layaway_amount = $_SESSION['layaway_amount']; ?>
				<tr>
					<th><strong>Installment Payment:</strong></th>
					<td><strong><?php echo format_price($layaway_amount * $_SESSION['currency-rate'], true); ?></strong></td>
				</tr>
				<tr>
					<th><strong>Order Total:</strong></th>
					<td><strong><?php echo format_price($layaway_amount * $_SESSION['currency-rate'], true); ?></strong></td>
				</tr>
			<?php } ?>
		</table>
		<?php if ($is_layaway_allowed) { ?>
			<div class="text-holder text-right">
				<a href="/buy-installments/" class="link-orange" target="_blank"><strong>Learn more about buying in Installments</strong></a>
			</div>
		<?php } ?>
		</div>
	</div>
	<?php if (is_user_logged_in()) { ?>
		<div class="payment-info-links cf">
			<a href="#lightbox-3-day" class="link-returns colorbox-popup">Days Returns</a>
			<a href="#lightbox-full-refunds" class="link-refunds right colorbox-popup">Full Refunds</a>
		</div>
		<div class="payment-aside-text">
			Return any item to us within 3 days of receipt in its original packaging, to receive a full refund of your payment
		</div>
		<script>
		jQuery(document).ready(function(){
			jQuery('.payment-info-links a').click(function(){
				var href = jQuery(this).attr('href');
				jQuery.colorbox({inline:true, href:href});
				return false;
			});
		});
		</script>
	<?php } else { ?>
		<div class="payment-f-logos">
			<p>As seen in:</p>
			<ul class="featured-logos mini">
				<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/logo-ahlan-mini.png" alt="ahlan"/></li>
				<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/logo-cosmopolitan-mini.png" alt="cosmopolitan"/></li>
				<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/logo-hello-mini.png" alt="hello"/></li>
				<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/logo-whatson-mini.png" alt="whatson"/></li>
				<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/logo-elle-mini.png" alt="elle"/></li>
				<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/logo-timeout-mini.png" alt="timeout"/></li>
				<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/logo-grazia-mini.png" alt="grazia"/></li>
			</ul>
		</div>
	<?php } ?>
