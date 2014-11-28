<?php
$chstep = 2;
$CART = show_cart();
$order = get_current_order();

$d_option = $order['d_option'];

$dpch = array('d_option' => $order['d_option'], 'p_option' => $order['p_option']);

$show_transfer = true;
$geoplugin = new geoPlugin();
$geoplugin->locate($_SERVER['REMOTE_ADDR']);
if (strlen($geoplugin->countryName)) {
	$ip_country = strtoupper(trim($geoplugin->countryName));
	if (strlen($ip_country)) {
		if ($ip_country == 'UNITED STATES') {
			$show_transfer = false;
		}
	}
}

$cod_available = true;
if ($_POST['order_step'] == 2) {
	$cod_available = check_cod_available();
}

if ($_SESSION['layaway_order'] > 0) { $dstyle = ' style="display:none;"'; }
?>
	<?php wishlist_success(); ?>

	<div class="payment-content">
		<div class="payment_steps">
			<div class="payment-step">
				<div class="payment-step-title cf">
					<h3>Delivery</h3>
				</div>
			</div>
			<div class="payment-step open">
				<div class="payment-step-title cf">
					<h3>Payment</h3>
				</div>
				<div class="payment-step-content">
					<form class="form-default cf" method="POST">
						<div class="ch-payment-methods cf">
							<div class="check-row-lines v1">
								<?php
								$payment_options = get_payment_options($dpch);
								$checked = '';
								foreach($payment_options as $poval => $podata) {
									if ($poval == 'transfer' && !$show_transfer) { continue; }
									if ($poval == 'cash' && $d_option == 'post') { continue; }
									if (strlen($podata['checked'])) { $checked = $poval; }
								}
								if (strlen($order['p_option'])) { $checked = $order['p_option']; }
								if (!strlen($checked)) { $checked = 'cod'; }

								foreach($payment_options as $poval => $podata) {
									if ($poval == 'transfer' && !$show_transfer) { continue; }
									if ($poval == 'cash' && $d_option == 'post') { continue; } ?>
									<div class="check-row-line ch-pay-method-<?php echo $poval; ?><?php if ($poval == $checked) { echo ' checked'; } ?> cf">
										<input type="radio" name="p_option" value="<?php echo $poval; ?>" <?php if ($poval == $checked) { echo 'checked="checked"'; } ?> onclick="checkout_change_paymemt('<?php echo $poval; ?>');" />
										<label for="pick-up">
											<strong><?php echo $podata['label']; ?></strong>
										</label>
									</div>
								<?php } ?>
							</div>
							<?php foreach($payment_options as $poval => $podata) {
								if ($poval == 'transfer' && !$show_transfer) { continue; }
								if ($poval == 'cash' && $d_option == 'post') { continue; } ?>
								<div class="check-row-content v1 ch-pm-content-<?php echo $poval; ?><?php if ($poval == $checked) { echo ' open'; } ?>">
									<div class="check-row-box cf">
										<?php echo wpautop($OPTION['wps_checkout_'.$poval.'_text']); ?>
									</div>
								</div>
							<?php } ?>
						</div>
						<div class="buttons-bottom voucher-block cf" style="position:relative;">
							<?php if (!$cod_available) { ?>
								<p class="error" style="text-align:right;">Cash on delivery is not available outside UAE</p>
							<?php } ?>
							<?php if ($OPTION['wps_voucherCodes_enable']) { ?>
								<div class="btn-text-field">
									<input type="text" name="voucher_code" id="voucher-code" placeholder="Enter voucher code">
									<input type="button" class="btn-orange" value="Use" onclick="check_voucher_code();">
								</div>
								<div class="pay-status" style="display:none;"></div>
								<div class="action-loading"><img src="<?php echo TEMPLURL; ?>/images/loading-ajax.gif"></div>
							<?php } ?>
							<input type="submit" value="Next" class="btn-orange right" onclick="ga_send_event('checkout_process');">
						</div>
						<input type="hidden" name="order_step" value="2">
					</form>
				</div>
			</div>
			<div class="payment-step">
				<div class="payment-step-title cf">
					<h3>Order Review</h3>
				</div>
			</div>
			<div class="payment-step">
				<div class="payment-step-title cf">
					<h3>Confirmation</h3>
				</div>
			</div>
		</div>
	</div>
	<div class="payment-aside">
		<?php include('shop_your_order.php'); ?>
		<ul class="payment-logos">
			<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/logo-secure-p.png" height="33" width="65" alt="ahlan"/></li>
			<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/logo-visa-p.png" alt="cosmopolitan"/></li>
			<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/logo-mastercard-p.png" alt="hello"/></li>
		</ul>
	</div>
<script language="javascript">
var lpm = '';
if (!jQuery('.delivery-line-pickup').size()) { ldtype = 'post'; }
function checkout_change_paymemt(pm) {
	if (pm != lpm) {
		jQuery('.ch-payment-methods .check-row-line').removeClass('checked');
		jQuery('.ch-payment-methods .check-row-content').removeClass('open');
		jQuery('.ch-payment-methods .ch-pay-method-'+pm).addClass('checked');
		jQuery('.ch-payment-methods .ch-pm-content-'+pm).addClass('open');
	}
	lpm = pm;
}
function check_voucher_code() {
	var vcode = jQuery('#voucher-code').val();
	jQuery('.voucher-block .pay-status').hide().removeClass('false');
	if (vcode != '') {
		jQuery('.voucher-block .action-loading').show();
		jQuery.post(siteurl, 
			{
				FormAction: 'check_voucher',
				vcode: vcode
			},
			function(data){
				jQuery('.voucher-block .action-loading').hide();
				if (data == 'success') {
					jQuery('.voucher-block .pay-status').show();
				} else {
					jQuery('.voucher-block .pay-status').addClass('false').show();
				}
			}
		);
	}
}
</script>
