<?php
global $current_user;
$chstep = 1;
$CART = show_cart();
$order = get_current_order();

// countries
$b_country = 'UNITED ARAB EMIRATES';
$d_country = 'UNITED ARAB EMIRATES';

$geoplugin = new geoPlugin();
$geoplugin->locate($_SERVER['REMOTE_ADDR']);
if (strlen($geoplugin->countryName)) {
	$ip_country = strtoupper(trim($geoplugin->countryName));
	if (strlen($ip_country)) {
		$b_country = $ip_country;
		$country_2 = $ip_country;
	}
}

$d_option = 'pickup';
if ($order) { $d_option = $order['d_option']; }
if ($_POST['order_step'] == 1) {
	$d_option = $_POST['d_option'];
	$f_name		= trim($_POST['f_name']);
	$l_name		= trim($_POST['l_name']);
	$b_country	= trim($_POST['country']);
	$email		= trim($_POST['email']);
	$telephone	= trim($_POST['telephone']);

	$f_name2	= trim($_POST['f_name|2']);
	$l_name2	= trim($_POST['l_name|2']);
	$d_country	= trim($_POST['country|2']);
	$feedback = check_address_form();
} else {
	if (isset($_SESSION['layaway_order_data'])) {
		$f_name		= $_SESSION['layaway_order_data']['fname'];
		$l_name		= $_SESSION['layaway_order_data']['lname'];
		$b_country	= $_SESSION['layaway_order_data']['country'];
		$email		= $_SESSION['layaway_order_data']['email'];
		$telephone	= $_SESSION['layaway_order_data']['telephone'];

		$f_name2	= $_SESSION['layaway_order_data']['shipp_fname'];
		$l_name2	= $_SESSION['layaway_order_data']['shipp_lname'];
		$d_country	= $_SESSION['layaway_order_data']['shipp_country'];
	} else if ($user_order_info = nws_get_user_order_info()) {
		$_SESSION['order_data'] = $user_order_info;
		$f_name		= $_SESSION['order_data']['fname'];
		$l_name		= $_SESSION['order_data']['lname'];
		$b_country	= $_SESSION['order_data']['country'];
		$email		= $_SESSION['order_data']['email'];
		$telephone	= $_SESSION['order_data']['telephone'];

		$f_name2	= $_SESSION['order_data']['shipp_fname'];
		$l_name2	= $_SESSION['order_data']['shipp_lname'];
		$d_country	= $_SESSION['order_data']['shipp_country'];
	}
}
if ($order && !strlen($b_country)) {
	$f_name		= $order['f_name'];
	$l_name		= $order['l_name'];
	$b_country	= $order['country'];
	$email		= $order['email'];
	$telephone	= $order['telephone'];
}

//get cart composition
$cart_comp = cart_composition($_SESSION['cust_id']);
if(isset($_SESSION['user_logged']) && $_SESSION['user_logged'] === TRUE){ get_member_billing_addr(2); }
if(isset($_GET['dpchange']) && $_GET['dpchange'] == 1){ retrieve_address_data(); }

$show_pickup = true; // show pickup delivery for UAE visitors
$show_transfer = true;
$geoplugin = new geoPlugin();
$geoplugin->locate($_SERVER['REMOTE_ADDR']);
if (strlen($geoplugin->countryName)) {
	$ip_country = strtoupper(trim($geoplugin->countryName));
	//$ip_country = 'UNITED ARAB EMIRATES';
	if (strlen($ip_country)) {
		if ($ip_country != 'UNITED ARAB EMIRATES') {
			$show_pickup = false;
			if ($d_option == 'pickup') { $d_option = 'post'; }
		}
	}
}

if ($show_pickup && $d_option == 'pickup') {
	$pickup_show_info = true;
	$pickup_email = $current_user->user_email;
	if ($order) {
		$pickup_f_name = $order['f_name'];
		$pickup_l_name = $order['l_name'];
		$pickup_email = $order['email'];
		$pickup_telephone = $order['telephone'];
	} else if ($user_order_info = nws_get_user_order_info()) {
		$pickup_f_name = $user_order_info['fname'];
		$pickup_l_name = $user_order_info['lname'];
		$pickup_email = $user_order_info['email'];
		$pickup_telephone = $user_order_info['telephone'];
		if (!strlen($pickup_email)) { $pickup_email = $current_user->user_email; }
		if (strlen($pickup_f_name) && strlen($pickup_l_name) && strlen($pickup_email) && strlen($pickup_telephone)) {
			$pickup_show_info = false;
		}
	}
	if ($_POST['order_step'] == 1) {
		$pickup_f_name		= trim($_POST['pickup_f_name']);
		$pickup_l_name		= trim($_POST['pickup_l_name']);
		$pickup_email		= trim($_POST['pickup_email']);
		$pickup_telephone	= trim($_POST['pickup_telephone']);
	}
}
?>
	<?php wishlist_success(); ?>
	<div class="payment-content">
		<div class="payment_steps">
			<?php if (!is_user_logged_in()) { ?>
			<div class="payment-step sign-in open">
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
								<input type="text" name="ch_register_email" class="mb-18" id="ch-register-email" />
								<label>Password</label>
								<input type="password" name="ch_register_pwd" id="ch-register-pwd" />
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
								<div class="btn-holder right" style="position:relative;">
									<div class="action-loading"><img src="<?php echo TEMPLURL; ?>/images/loading-ajax.gif"></div>
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
									<div class="btn-holder right" style="position:relative; margin-top:15px;">
										<div class="action-loading"><img src="<?php echo TEMPLURL; ?>/images/loading-ajax.gif"></div>
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
			<div class="payment-step<?php if (is_user_logged_in()) { echo ' open'; } ?>">
				<div class="payment-step-title cf">
					<h3>Delivery</h3>
				</div>
				<div class="payment-step-content"<?php if (!is_user_logged_in()) { echo ' style="display:none;"'; } ?>>
					<div class="payment-delivery">
						<form class="form-default cf" method="POST">
							<input type="hidden" name="utm_source" id="utm_source">
							<input type="hidden" name="utm_medium" id="utm_medium">
							<input type="hidden" name="utm_campaign" id="utm_campaign">
							<input type="hidden" name="utm_content" id="utm_content">
							<input type="hidden" name="utm_term" id="utm_term">
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
										<input type="text" name="pickup_f_name" value="<?php echo $pickup_f_name; ?>" maxlength="255" />
										<label>Last Name:</label>
										<input type="text" name="pickup_l_name" value="<?php echo $pickup_l_name; ?>" maxlength="255" />
										<label>Email:</label>
										<input type="text" name="pickup_email" value="<?php echo $pickup_email; ?>" maxlength="255" />
										<label>Telephone:</label>
										<input type="text" name="pickup_telephone" value="<?php echo $pickup_telephone; ?>" maxlength="255" />
									</div>
								</div>
							</div>
							<div class="payment-address delivery-content-post<?php if ($d_option == 'post') { echo ' open'; } ?>">
								<div class="columns cf">
									<div class="column">
										<h3>Billing Address:</h3>
										<label>First Name:</label>
										<input id="firstname" type="text" name="f_name" value="<?php echo $f_name; ?>" maxlength="255" />
										<label>Last Name:</label>
										<input id="lastname" type="text" name="l_name" value="<?php echo $l_name; ?>" maxlength="255" />
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
												<select name="country" size="1" id="billingCountry" onChange="getBaddressForm('<?php echo is_in_subfolder(); ?>', '<?php echo get_protocol(); ?>');">
													<option value="bc"><?php echo __('-- Select a Country --','wpShop'); ?></option>
													<option value="<?php echo $shop_country; ?>" <?php echo $selected; ?>><?php echo $shop_country; ?></option>
													<?php foreach($countries  as $country) {
														$selected = ($b_country == $country ? 'selected="selected"' : ''); ?>
														<option value="<?php echo $country; ?>" <?php echo $selected; ?>><?php echo $country; ?></option>
													<?php } ?>
												</select>
											</div>
										<?php } ?>
										<input type="hidden" id="editOption" name="editOption" value="billingAddressCT" />
										<span id="billingAddress"></span>
										<span id="billingAddressCheck">
											<?php redisplay_address_form(); ?>
										</span>
										<label>Email:</label>
										<input type="text" name="email" value="<?php echo $email; ?>" maxlength="255" />
										<label>Telephone:</label>
										<input type="text" name="telephone" value="<?php echo $telephone; ?>" maxlength="255" />
										<?php $checked = (isset($_POST['delivery_address_yes']) && $_POST['delivery_address_yes'] == '1' ? 'checked="checked"' : ''); ?>
										<div class="check-row">
											<input type="checkbox" id="display_switch" name="delivery_address_yes" value="1" <?php echo $checked; ?> onClick="display_delivery_address();" />
											<label for="another-address">I have a different delivery address.</label>
										</div>
									</div>
									<?php // Display Delivery Address Form
									$visibility = (isset($_POST['delivery_address_yes']) && $_POST['delivery_address_yes'] == '1'? 'visible' : 'hidden'); ?>
									<div id="delivery_address" class="column" style="visibility: <?php echo $visibility; ?>;">
										<h3>Delivery Address:</h3>
										<p>Fill In Only If Different From Billing Address</p>
										<label>First Name:</label>
										<input id="dfirstname" type="text" name="f_name|2" value="<?php echo $f_name2; ?>" maxlength="255" />
										<label>Last Name:</label>
										<input id="dlastname" type="text" name="l_name|2" value="<?php echo $l_name2; ?>" maxlength="255" />
										<?php if ($dc['num'] > 0) { ?>
										<label>Country:</label>
										<div class="custom-select">
											<select name="country|2" size="1" id="deliveryCountry" onChange="getDaddressForm('<?php echo is_in_subfolder(); ?>', '<?php echo get_protocol(); ?>');">
												<option value="dc"><?php echo __('-- Select a Country --','wpShop'); ?></option>
												<option value="<?php echo $shop_country; ?>" <?php echo $selected2; ?>><?php echo $shop_country; ?></option>
												<?php foreach($countries  as $country) {
													$selected = ($d_country == $country ? 'selected="selected"' : ''); ?>
													<option value="<?php echo $country; ?>" <?php echo $selected; ?>><?php echo $country; ?></option>
												<?php } ?>
											</select>
										</div>
										<?php } ?>
										<input type="hidden" id="editOption" name="editOption" value="billingAddressCT" />
										<span id="deliveryAddress"></span>
										<span id="deliveryAddressCheck">
											<?php redisplay_address_form('shipping'); ?>
										</span>
									</div>
								</div>
							</div>
							<div class="bottom-block">
								<div class="check-row">
									<input type="checkbox" name="terms_accepted" />
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
								<?php if (strlen($feedback['e_message'])) { ?>
									<p class="error" style="margin-top:20px;"><?php echo $feedback['e_message']; ?></p>
								<?php } ?>
								<div class="btn-holder cf">
									<button class="btn-orange right-arrow right" name="step1">proceed to checkout</button>
								</div>
							</div>
							<input type="hidden" name="order_step" value="1">
						</form>
					</div>
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
			<div class="payment-step">
				<div class="payment-step-title cf">
					<h3>Confirmation</h3>
				</div>
			</div>
		</div>
	</div>
	<div class="payment-aside">
		<?php include('shop_your_order.php'); ?>
		<?php if (is_user_logged_in()) { ?>
			<div class="payment-info-links cf">
				<a href="#lightbox-3-day" class="link-returns colorbox-popup">Days Returns</a>
				<a href="#lightbox-full-refunds" class="link-refunds right colorbox-popup">Full Refunds</a>
			</div>
			<div class="payment-aside-text">
				Return any item to us within 3 days of receipt in its original packaging, to receive a full refund of your payment
			</div>
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
	</div>
<script language="javascript">
var ldtype = '<?php echo $d_option; ?>';
if (!jQuery('.delivery-line-pickup').size()) { ldtype = 'post'; }
function checkout_change_delivery(dtype) {
	if (dtype != ldtype) {
		jQuery('.delivery-line-'+ldtype).removeClass('checked');
		jQuery('.delivery-content-'+ldtype).removeClass('open');
		jQuery('.delivery-line-'+dtype).addClass('checked');
		jQuery('.delivery-content-'+dtype).addClass('open');
	}
	ldtype = dtype;
}
</script>
