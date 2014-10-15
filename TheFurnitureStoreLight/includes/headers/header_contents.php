<?php
global $currency_options, $currency_locations;
$pageurl = $_SERVER['REQUEST_URI'];
if ($pageurl == '/') { $pageurl = site_url(); }
?>
	<div class="header-right">
		<div class="row currency-block">
			<strong class="label">Location: </strong> 
			<span class="country">
				<font class="curr-location curr-loc-usd">International</font>
				<?php foreach($currency_options as $cc) { ?>
					<font class="curr-location curr-loc-<?php echo $cc; ?>" style="display:none;"><?php echo $currency_locations[$cc]; ?></font>
				<?php } ?>
			 |</span>
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
						$basket = $CART['total_item_num'];
					}
				}
				?>
				<span class="bag" id="header-bag-info"><a href="<?php echo get_cart_url(); ?>"><?php echo $basket; ?></a></span>
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
								<li><a href="<?php echo site_url(); ?>/?logout=true" title="<?php _e('Exit your account','wpShop'); ?>"><?php _e('Logout','wpShop'); ?></a></li>
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
