<?php
global $currency_options;
$location = 'International';
$geoplugin = new geoPlugin();
$geoplugin->locate($_SERVER['REMOTE_ADDR']);
$user_ccode = strtolower($geoplugin->currencyCode);
if (strlen($user_ccode)) {
	if ($user_ccode == 'usd') {
		$location = 'United States';
	} else if (in_array($user_ccode, $currency_options)) {
		$location = $geoplugin->countryName;
	}
}
$pageurl = $_SERVER['REQUEST_URI'];
if ($pageurl == '/') { $pageurl = site_url(); }
?>
	<div class="header-right">
		<div class="row">
			<strong class="label">Location: </strong> <span class="country"><?php echo $location; ?> |</span>
			<div class="switch switcher-currency">
				<div id="currencySelect" class="switch-wrapper">
					<span onclick="popUpMenu(this);">
						<strong class="current currency-USD">
							<span class="opacity-fader">USD</span>
						</strong>
						<span class="switcher opacity-fader"></span>
						<span class="opacity-fader">
							<ul class="currency-list faded" id="popId-currencySelect" style="display: none;">
								<li class="current currency-USD">
									<a href="#USD" class="currency-val" rel="USD" name="US Dollar"><span class="flag"></span>US Dollar</a>
								</li>
								<?php
								$sccodes = shop_get_currency_codes();
								foreach($sccodes as $cc => $cn) { $cc = strtoupper($cc); ?>
								<li class="currency-<?php echo $cc; ?>">
									<a href="#<?php echo $cc; ?>" class="currency-val" rel="<?php echo $cc; ?>" name="<?php echo $cn; ?>"><span class="flag"></span><?php echo $cn; ?></a>
								</li>
								<?php } ?>
							</ul>
						</span>
					</span>
				</div>
			</div>
		</div>
		<div class="row">&nbsp;
			<!--<strong class="label">Language:</strong> <div class="languages"><span class="active">English</span> <span>العربية</span></div>-->
		</div>
		<div class="register-block">
			<div class="holder">
				<?php
				$basket = '0';
				if(!isset($_GET['confirm'])) {
					$CART = show_cart();
					if(is_array($CART) && $CART['status'] == 'filled') {
						$basket = '<a href="'.get_option('home').'?showCart=1">'.$CART['total_item_num'].'</a>';
					}
				}
				?>
				<span class="bag"><?php echo $basket; ?></span>
				<?php if (is_user_logged_in()) { global $current_user;
					$my_items_page = get_permalink($OPTION['wps_indvseller_my_items_page']);
					if (in_array('profseller', $current_user->roles)) {
						$my_items_page = get_permalink($OPTION['wps_profreseller_my_items_page']);
					} // Hi, echo $current_user->display_name; ?>
					<ul class="logged-menu">
						<li><a href="#">My Account</a>
							<ul class="head_drop">
								<li><a href="<?php echo get_permalink($OPTION['wps_account_my_profile_page']); ?>" title="<?php _e('Edit your profile','wpShop'); ?>"><?php _e('My Profile','wpShop'); ?></a></li>
								<li><a href="<?php echo $my_items_page; ?>"><?php _e('My Items','wpShop'); ?></a></li>
								<li><a href="<?php echo get_permalink($OPTION['wps_account_my_purchases_page']); ?>"><?php _e('My Purchases','wpShop'); ?></a></li>
								<li><a href="<?php echo get_permalink($OPTION['wps_account_my_wishlist_page']); ?>"><?php _e('My Wishlist','wpShop'); ?></a></li>
								<?php if ($OPTION['wps_alerts_enable']) { ?><li><a href="<?php echo get_permalink($OPTION['wps_account_my_alerts_page']); ?>"><?php _e('My Notifications','wpShop'); ?></a></li><?php } ?>
								<li><a href="<?php echo get_permalink($OPTION['wps_account_login_page']); ?>?action=logout" title="<?php _e('Exit your account','wpShop'); ?>"><?php _e('Logout','wpShop'); ?></a></li>
							</ul>
						</li>
					</ul>
				<?php } else { ?>
					<div class="log-buttons">
						<a href="<?php echo $pageurl; ?>" class="login-lnk" title="Login to your account">Log In</a> | 
						<a href="<?php echo $pageurl; ?>" class="register-lnk" title="Create an account">Register</a>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
