<?php
global $current_user;
if (!check_cart_items()) {
	wp_redirect(get_cart_url());
	exit;
}

$CART = show_cart();
$cart_items = $CART["content"];

$payerror = trim($_GET['payerror']);

$show_pickup = true; // show pickup delivery only for UAE visitors
$is_usa = false; // show transfer and cod payment method not for USA

// default countries
$b_country = 'UNITED ARAB EMIRATES';
$d_country = 'UNITED ARAB EMIRATES';

$geoplugin = new geoPlugin();
$geoplugin->locate($_SERVER['REMOTE_ADDR']);
if (strlen($geoplugin->countryName)) {
	$ip_country = strtoupper(trim($geoplugin->countryName));
	//$ip_country = 'UNITED ARAB EMIRATES';
	//$ip_country = 'UNITED STATES';
	if (strlen($ip_country)) {
		$b_country = $ip_country;
		$d_country = $ip_country;
		if ($ip_country != 'UNITED ARAB EMIRATES') {
			$show_pickup = false;
		}
		if ($ip_country == 'UNITED STATES') {
			$is_usa = true;
		}
	}
}

$checkout_info = get_checkout_info($b_country, $d_country);
$d_option      = $checkout_info['d_option'];
$p_option      = $checkout_info['p_option'];
$f_name        = $checkout_info['f_name'];
$l_name        = $checkout_info['l_name'];
$b_country     = $checkout_info['country'];
$street        = $checkout_info['street'];
$state         = $checkout_info['state'];
$town          = $checkout_info['town'];
$zip           = $checkout_info['zip'];
$email         = $checkout_info['email'];
$telephone     = $checkout_info['telephone'];
$shipp_fname   = $checkout_info['shipp_fname'];
$shipp_lname   = $checkout_info['shipp_lname'];
$d_country     = $checkout_info['shipp_country'];
$shipp_street  = $checkout_info['shipp_street'];
$shipp_state   = $checkout_info['shipp_state'];
$shipp_town    = $checkout_info['shipp_town'];
$shipp_zip     = $checkout_info['shipp_zip'];
$shipp_addr    = $checkout_info['shipp_addr'];

if (!$show_pickup && $d_option == 'pickup') { $d_option = 'post'; }
?>
	<?php wishlist_success(); ?>
	<div class="payment-content">
		<div class="payment_steps">
			<?php if ($_SESSION['layaway_process'] == 1) { ?>
				<div class="payment-step step-installments open">
					<div class="payment-step-title cf">
						<h3>Buy in installments</h3>
						<a href="#edit" class="ch-installments-edit" style="visibility:hidden;">Edit</a>
					</div>
					<div class="payment-step-content">
						<div class="payment-installments">
							<form class="ch-installments-form" method="POST">
							<table class="payment-itable">
								<tr>
									<th class="iname">Item Name</th>
									<th class="iprice">Item Price</th>
									<th class="ihelp"></th>
								</tr>
								<?php foreach($cart_items as $cart_item) {
									$cart_item_data = explode("|", $cart_item); ?>
									<tr>
										<td class="iname"><?php echo $cart_item_data[2]; ?></td>
										<td class="iprice"><?php echo format_price($cart_item_data[3] * $_SESSION['currency-rate'], true); ?></td>
										<td></td>
									</tr>
								<?php } ?>
								<?php
								$cart_total_price = $CART['total_price'];
								$layaway_amount = $_SESSION['layaway_amount'];
								$balance_total = $cart_total_price - $layaway_amount;

								$layaway_def_amount = layaway_get_amount($cart_total_price);
								if ($_SESSION['layaway_order'] > 0) {
									$oamounts = layaway_get_process_amounts($_SESSION['layaway_order']);
									$layaway_def_amount = $oamounts['balance'];
								}
								$payment_amount = format_price($layaway_amount * $_SESSION['currency-rate']);
								$payment_amount = str_replace(',', '', $payment_amount);
								?>
								<tr>
									<td class="iname">Select installment amount, <?php echo $_SESSION['currency-code']; ?></td>
									<td class="iprice form-default"><input type="text" name="ch_layaway_amount" class="ch-layaway-amount" value="<?php echo $payment_amount; ?>"></td>
									<td class="ihelp">
										<a href="#help" class="ihelp layaway-payment-q"><img src="<?php echo TEMPLURL; ?>/images/question-icon.gif"></a>
									</td>
								</tr>
								<tr class="itotal">
									<td class="iname">Balance total:</td>
									<td class="iprice i-balance-total"><?php echo format_price($balance_total * $_SESSION['currency-rate'], true); ?></td>
									<td class="ihelp"></td>
								</tr>
							</table>
							<p class="error ch-installments-errors" style="text-align:right; display:none;"></p>
							<div class="icontinue btn-holder right"><input type="button" value="CONTINUE" class="btn-orange" onclick="checkout_submit_installments()"></div>
							<input type="hidden" name="ch_layaway_def_amount" class="ch-layaway-def-amount" value="<?php echo $layaway_def_amount; ?>">
							<input type="hidden" name="ch_layaway_cart_total" class="ch-layaway-cart-total" value="<?php echo $cart_total_price; ?>">
							</form>
						</div>
					</div>
				</div>
			<?php } ?>
			<?php if (!is_user_logged_in()) { ?>
			<div class="payment-step step-sign-in<?php if (!$_SESSION['layaway_process']) { echo ' open'; } ?>">
				<div class="payment-step-title cf">
					<h3>Sign In</h3>
				</div>
				<div class="payment-step-content">
					<div class="payment-sign-in cf">
						<div class="column">
							<div class="description">
								<h4>New to the Luxury Closet?</h4>
								<p>Check out using your e-mail address:</p>
							</div>
							<form class="form-default checkout-register-form" method="POST">
								<label>E-mail</label>
								<input type="text" name="ch_register_email" class="mb-18" id="ch-register-email" autocomplete="off" placeholder="Enter your e-mail" />
								<label>Password</label>
								<input type="password" name="ch_register_pwd" id="ch-register-pwd" autocomplete="off" placeholder="Enter your password" />
								<ul class="check-row" id="ch-register-gender">
									<li>
										<input type="radio" name="ch_register_gender" value="Male" checked="checked" />
										<label for="male">Male</label>
									</li>
									<li>
										<input type="radio" name="ch_register_gender" value="Female" />
										<label for="female">Female</label>
									</li>
								</ul>
								<div class="btn-holder right">
									<input type="submit" value="Continue" class="btn-orange"/>
								</div>
							</form>
						</div>
						<div class="column">
							<div class="ch-login-block">
								<div class="description">
									<h4>Login to your Account</h4>
									<p>If you have an account, please log in below:</p>
								</div>
								<form class="form-default checkout-login-form" method="POST">
									<label>E-mail</label>
									<input type="text" name="ch_login_email" placeholder="Enter your e-mail" class="mb-18" id="ch-login-email" />
									<label>Password</label>
									<input type="password" name="ch_login_pwd" placeholder="Enter your password" class="mb-18" id="ch-login-pwd" />
									<div class="btn-holder right" style="position:relative; margin-top:11px;">
										<div class="action-loading"><img src="<?php echo TEMPLURL; ?>/images/loading-ajax.gif"></div>
										<input type="submit" value="Continue" class="btn-orange" /><br />
										<a href="#forgot" class="ch-forgot-link">Forgot your password?</a>
									</div>
								</form>
							</div>
							<div class="ch-forgot-block" style="display:none;">
								<div class="description">
									<h4>Forgot your password?</h4>
									<p>Please enter your email below:</p>
								</div>
								<div class="success" style="display:none;">Please check your e-mail to set your new password.</div>
								<form class="form-default checkout-forgot-form" method="POST">
									<label>Your E-mail</label>
									<input type="text" name="ch_forgot_email" id="ch-forgot-email">
									<div class="btn-holder right" style="margin-top:15px;">
										<input type="submit" value="Change Password" class="btn-orange"><br />
										<a href="#login-back" class="ch-login-back">Back to Login</a>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>
			<form class="form-default checkout-form" method="POST">
			<div class="payment-step step-delivery<?php if (is_user_logged_in() && !$_SESSION['layaway_process'] && !strlen($payerror)) { echo ' open'; } ?>">
				<div class="payment-step-title cf">
					<h3>Delivery</h3>
					<a href="#edit" class="ch-delivery-edit" style="visibility:hidden;">Edit</a>
				</div>
				<div class="payment-step-content">
					<div class="payment-delivery">
						<?php if ($show_pickup) { ?>
							<div class="check-row-lines delivery-methods">
								<div class="check-row-line delivery-line-pickup<?php if ($d_option == 'pickup') { echo ' checked'; } ?> cf">
									<input type="radio" name="d_option" id="pick-up" value="pickup" onclick="checkout_change_delivery('pickup');"<?php if ($d_option == 'pickup') { echo ' checked="checked"'; } ?> />
									<label for="pick-up">
										<strong>Pick up</strong>
										<span><?php echo $OPTION['wps_pickUp_label']; ?></span>
									</label>
								</div>
								<div class="check-row-line delivery-line-post<?php if ($d_option == 'post') { echo ' checked'; } ?> cf">
									<input type="radio" name="d_option" id="delivery" value="post" onclick="checkout_change_delivery('post');"<?php if ($d_option == 'post') { echo ' checked="checked"'; } ?> />
									<label for="delivery">
										<strong>Delivery</strong>
										<?php if (is_flat_limit_shipping_free($CART['total_price'])) { ?>
											<span class="fl-free"><?php echo $OPTION['wps_delivery_free_label']; ?></span>
										<?php } else { ?>
											<span><?php echo $OPTION['wps_delivery_label']; ?></span>
										<?php } ?>
									</label>
								</div>
							</div>
							<div class="check-row-content delivery-content-pickup<?php if ($d_option == 'pickup') { echo ' open'; } ?>">
								<div class="check-row-box cf">
									<?php echo stripslashes($OPTION['wps_checkout_pickup_text']); ?>
								</div>
							</div>
							<div class="check-row-content delivery-content-post<?php if ($d_option == 'post') { echo ' open'; } ?>">
								<div class="check-row-box cf">
									<?php echo stripslashes($OPTION['wps_checkout_delivery_text']); ?>
								</div>
							</div>
						<?php } else { ?>
							<input type="hidden" name="d_option" value="post">
							<div style="width: 100%;">
								<p>Fill in your delivery address below:</p>
								<div class="check-row-box v1 open cf">
									<?php echo stripslashes($OPTION['wps_checkout_delivery_outside_uae_text']); ?>
								</div>
							</div>
						<?php } ?>
						<div class="payment-address delivery-content-pickup<?php if ($d_option == 'pickup') { echo ' open'; } ?>">
							<div class="columns cf">
								<div class="column">
									<label>First Name:</label>
									<input type="text" name="pickup_f_name" value="<?php echo $f_name; ?>" class="pickup-f-name" />
									<label>Last Name:</label>
									<input type="text" name="pickup_l_name" value="<?php echo $l_name; ?>" class="pickup-l-name" />
									<label>Email:</label>
									<input type="text" name="pickup_email" value="<?php echo $email; ?>" class="pickup-email" />
									<label>Telephone:</label>
									<input type="text" name="pickup_telephone" value="<?php echo $telephone; ?>" class="pickup-telephone" />
								</div>
							</div>
						</div>
						<div class="payment-address delivery-content-post<?php if ($d_option == 'post') { echo ' open'; } ?>">
							<div class="columns cf">
								<div class="column">
									<h3>Billing Address:</h3>
									<label>First Name:</label>
									<input id="firstname" type="text" name="f_name" value="<?php echo $f_name; ?>" class="ch-f-name" />
									<label>Last Name:</label>
									<input id="lastname" type="text" name="l_name" value="<?php echo $l_name; ?>" class="ch-l-name" />
									<?php
									$dc	= get_delivery_countries();
									$shop_country 	= get_countries(2, $OPTION['wps_shop_country']);
									$selected 		= ($b_country == $shop_country ? 'selected="selected"' : '');
									$selected2 		= ($d_country == $shop_country ? 'selected="selected"' : '');
									if ($dc['num'] > 0) {
										$countries = array();
										while($row = mysql_fetch_assoc($dc['res'])) {
											$countryName = $row['country'];
											if(WPLANG == 'de_DE') { $countryName = $row['de']; }
											elseif(WPLANG == 'fr_FR') { $countryName = $row['fr']; }
											$countries[] = $countryName;
										} ?>
										<label>Country:</label>
										<div class="custom-select">
											<select name="country" size="1" class="ch-country" onchange="checkout_check_state('billing');">
												<option value=""><?php echo __('-- Select a Country --','wpShop'); ?></option>
												<option value="<?php echo $shop_country; ?>" <?php echo $selected; ?>><?php echo $shop_country; ?></option>
												<?php foreach($countries  as $country) {
													$selected = ($b_country == $country ? 'selected="selected"' : ''); ?>
													<option value="<?php echo $country; ?>" <?php echo $selected; ?>><?php echo $country; ?></option>
												<?php } ?>
											</select>
										</div>
									<?php } ?>
									<label>Address:</label>
									<input type="text" name="street" value="<?php echo $street; ?>" class="ch-street" />
									<label>State/Province:</label>
									<input type="hidden" name="state" class="ch-state" value="<?php echo $state; ?>">
									<input type="text" name="state_txt" value="<?php echo $state; ?>" class="ch-state-txt" />
									<div class="custom-select ch-state-select" style="display:none;">
										<select name="state_list" size="1" title="State selection" class="ch-state-list"></select>
									</div>
									<label>City:</label>
									<input type="text" name="town" value="<?php echo $town; ?>" class="ch-town" />
									<label>Postcode:</label>
									<input type="text" name="zip" value="<?php echo $zip; ?>" class="ch-zip" />
									<label>Email:</label>
									<input type="text" name="email" value="<?php echo $email; ?>" class="ch-email" />
									<label>Telephone:</label>
									<input type="text" name="telephone" value="<?php echo $telephone; ?>" class="ch-telephone" />
									<?php $checked = ($delivery_address_yes == '1' ? 'checked="checked"' : ''); ?>
									<div class="check-row">
										<input type="checkbox" id="display_switch" name="delivery_address_yes" value="1" class="ch-delivery-address-yes" <?php echo $checked; ?> onclick="checkout_delivery_address();" />
										<label for="another-address">I have a different delivery address.</label>
									</div>
								</div>
								<?php // Display Delivery Address Form
								$visibility = ($delivery_address_yes == '1'? 'visible' : 'hidden'); ?>
								<div id="delivery_address" class="column" style="visibility: <?php echo $visibility; ?>;">
									<h3>Delivery Address:</h3>
									<p>Fill In Only If Different From Billing Address</p>
									<label>First Name:</label>
									<input id="dfirstname" type="text" name="shipp_f_name" value="<?php echo $shipp_fname; ?>" class="ch-shipp-f-name" />
									<label>Last Name:</label>
									<input id="dlastname" type="text" name="shipp_l_name" value="<?php echo $shipp_lname; ?>" class="ch-shipp-l-name" />
									<?php if ($dc['num'] > 0) { ?>
									<label>Country:</label>
									<div class="custom-select">
										<select name="shipp_country" size="1" class="ch-shipp-country" onchange="checkout_check_state('shipping');">
											<option value=""><?php echo __('-- Select a Country --','wpShop'); ?></option>
											<option value="<?php echo $shop_country; ?>" <?php echo $selected2; ?>><?php echo $shop_country; ?></option>
											<?php foreach($countries  as $country) {
												$selected = ($d_country == $country ? 'selected="selected"' : ''); ?>
												<option value="<?php echo $country; ?>" <?php echo $selected; ?>><?php echo $country; ?></option>
											<?php } ?>
										</select>
									</div>
									<?php } ?>
									<label>Address:</label>
									<input type="text" name="shipp_street" value="<?php echo $shipp_street; ?>" class="ch-shipp-street" />
									<label>State/Province:</label>
									<input type="hidden" name="shipp_state" class="ch-shipp-state" value="<?php echo $shipp_state; ?>">
									<input type="text" name="shipp_state_txt" value="<?php echo $shipp_state; ?>" class="ch-shipp-state-txt" />
									<div class="custom-select ch-shipp-state-select" style="display:none;">
										<select name="shipp_state_list" size="1" title="State selection" class="ch-shipp-state-list"></select>
									</div>
									<label>City:</label>
									<input type="text" name="shipp_town" value="<?php echo $shipp_town; ?>" class="ch-shipp-town" />
									<label>Postcode:</label>
									<input type="text" name="shipp_zip" value="<?php echo $shipp_zip; ?>" class="ch-shipp-zip" />
								</div>
							</div>
						</div>
						<div class="bottom-block">
							<div class="check-row">
								<input type="checkbox" name="terms_accepted" value="1" class="ch-terms-accepted" />
								<label for="accept">*Accept <a href="#terms" class="show-terms-link">terms &amp; conditions</a></label>
								<div style="display:none;">
									<div id="terms-and-conditions" class="cart-shipping-costs-desc" style="width:750px; padding:30px 5px 20px 20px;">
										<h2>Terms & Conditions</h2>
										<div style="height:550px;overflow:auto;">
											<?php echo wpautop($OPTION['wps_terms_conditions']); ?>
										</div>
									</div>
								</div>
							</div>
							<p class="error ch-delivery-errors" style="margin-top:20px; display:none;"></p>
							<div class="btn-holder cf">
								<input type="button" class="btn-orange right" value="Next" onclick="checkout_submit_delivery();">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="payment-step step-payment<?php if (strlen($payerror)) { echo ' open'; } ?>">
				<div class="payment-step-title cf">
					<h3>Payment</h3>
				</div>
				<div class="payment-step-content">
					<div class="ch-payment-methods cf">
						<div class="check-row-lines v1">
							<?php
							$payment_options = get_payment_options($dpch);
							$checked = '';
							$first_poval = '';
							foreach($payment_options as $poval => $podata) {
								if (($poval == 'transfer' || $poval == 'cod') && $is_usa) { continue; }
								if ($poval == 'cash' && $d_option == 'post') { continue; }
								if (strlen($podata['checked'])) { $checked = $poval; }
								if (!strlen($first_poval)) { $first_poval = $poval; }
							}
							if (strlen($p_option)) { $checked = $p_option; }
							if (!strlen($checked)) { $checked = $first_poval; }

							foreach($payment_options as $poval => $podata) {
								if (($poval == 'transfer' || $poval == 'cod') && $is_usa) { continue; }
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
						<?php if (strlen($payerror)) { ?>
							<p class="error" style="text-align:right;">There has been an error processing this transaction.<br />
							<font style="font-weight:bold;">ERROR: <?php echo $payerror; ?></font></p>
						<?php } ?>
						<p class="error ch-payment-errors" style="text-align:right; display:none;"><?php echo $payerror; ?></p>
						<?php if ($OPTION['wps_voucherCodes_enable'] && !$_SESSION['layaway_order']) { ?>
							<div class="btn-text-field">
								<input type="text" name="voucher_code" id="voucher-code" placeholder="Enter voucher code">
								<input type="button" class="btn-orange" value="Use" onclick="check_voucher_code();">
							</div>
							<div class="pay-status" style="display:none;"></div>
							<div class="action-loading"><img src="<?php echo TEMPLURL; ?>/images/loading-ajax.gif"></div>
						<?php } ?>
						<input type="button" value="Place Order" class="btn-orange right" onclick="checkout_submit_payment();">
					</div>
				</div>
			</div>
			<div class="payment-step step-confirmation">
				<div class="payment-step-title cf">
					<h3>Confirmation</h3>
				</div>
			</div>
			<input type="hidden" name="ch_cart_url" value="<?php echo get_cart_url(); ?>" class="ch-cart-url">
			<input type="hidden" name="order_process" value="true">
			</form>
			<div class="checkout-loading" style="display:none;"><img src="<?php echo TEMPLURL; ?>/images/ajax-loader.gif"></div>
			<div class="checkout-payment-form">
				<?php include WP_CONTENT_DIR.'/themes/'.WPSHOP_THEME_NAME.'/lib/pages/shop_checkout_payment_form.php'; ?>
			</div>
		</div>
	</div>
	<div class="payment-aside">
		<?php include('shop_your_order.php'); ?>
	</div>
