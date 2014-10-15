<?php 
// do we want a customer area with a wishlist?	
if($OPTION['wps_lrw_yes']) {
	if($_SESSION['user_logged'] ||  is_user_logged_in()){
		global $current_user;
		global $OPTION;
		get_currentuserinfo(); // grabs the user info and puts into vars
		$user_ID = $current_user->ID;
		$userdata = get_userdata($user_ID);

		$_SESSION['user_logged'] = true;
		$_SESSION['timeout'] 	 = time() + (int) $OPTION['wps_login_duration'];
		$_SESSION['browser'] 	 = md5(strtolower($_SERVER['HTTP_USER_AGENT'])); // browser?
		$_SESSION['level']		 = $userdata->wp_user_level;
		$_SESSION['uid']		 = $user_ID;
		$_SESSION['uname']		 = $userdata->user_login;
		$_SESSION['fname']		 = $userdata->first_name;
		$_SESSION['lname']		 = $userdata->last_name; 	
	
		$logoutURLExtension = NULL;
		if($OPTION['wps_useGet4logout'] == 'yes'){
			$logoutURLExtension = '&redirect_to='.get_real_base_url();
		}
		$login_page = get_page_by_title( 'Login' );
		$login_url = get_option('siteurl').'/wp-login.php?action=logout';
		if($login_page) {
			$login_url = get_permalink($login_page->ID).'?action=logout';
		}
		$my_items_page = get_permalink($OPTION['wps_indvseller_my_items_page']);
		$sell_item_page = get_permalink($OPTION['wps_indvseller_add_item_page']);
		if (in_array('profseller', $current_user->roles)) {
			$my_items_page = get_permalink($OPTION['wps_profreseller_my_items_page']);
			$sell_item_page = get_permalink($OPTION['wps_profreseller_add_item_page']);
		}
	?>
		<li class="drp">Hi, <?=$current_user->display_name; ?>
			<ul class="head_drop">
				<li class="myprofile"><a href="<?php echo get_permalink($OPTION['wps_account_my_profile_page']); ?>" title="<?php _e('Edit your profile','wpShop'); ?>"><?php _e('My Profile','wpShop');?></a></li>
				<li class="myitems"><a href="<?php echo $my_items_page; ?>"><?php _e('My Items','wpShop'); ?></a></li>
				<!--<li class="myitems"><a href="<?php echo $sell_item_page; ?>"><?php _e('Sell an Item','wpShop'); ?></a></li>-->
				<li class="myhisory"><a href="<?php echo get_permalink($OPTION['wps_account_my_history_page']); ?>"><?php _e('My Invites','wpShop'); ?></a></li>
				<li class="mypurchases"><a href="<?php echo get_permalink($OPTION['wps_account_my_purchases_page']); ?>"><?php _e('My Purchases','wpShop'); ?></a></li>
				<li class="mywishlist"><?php include (TEMPLATEPATH . '/lib/pages/header_wishlist.php'); ?></li>
				<?php if ($OPTION['wps_alerts_enable']) { ?><li class="myalerts"><a href="<?php echo get_permalink($OPTION['wps_account_my_alerts_page']); ?>"><?php _e('My Notifications','wpShop'); ?></a></li><?php } ?>
				<li><a href="<?php echo $login_url.$logoutURLExtension; ?>" title="<?php _e('Exit your account','wpShop'); ?>"><?php _e('Logout','wpShop');?></a></li>
			</ul>
		</li>
	<?php } else {
		if (is_page($accountLog->post_title)) {$the_li_class='current_page_item';}else {$the_li_class='';} ?>
		<li class="<?php echo $the_li_class; ?>"><a href="<?php echo get_permalink($accountLog->ID); ?>" title="<?php _e('Login to your account','wpShop');?>"><?php echo $accountLog->post_title;?></a></li>
		<?php if (is_page($accountReg->post_title)) {$the_li_class='current_page_item';}else {$the_li_class='';} ?>
		<li class="<?php echo $the_li_class; ?>"><a href="<?php echo get_permalink($accountReg->ID); ?>" title="<?php _e('Create an account','wpShop');?>"><?php echo $accountReg->post_title;?></a></li>
	<?php }
}
// Sing Up link
?>
<?php $sul = false; if (!is_front_page() && $_COOKIE["MCEvilPopupClosed"] != 'yes' && $sul) { ?>
<li class="head-signup"><a href="#sign-up" title="<?php _e('Sign Up','wpShop'); ?>"><?php _e('Sign Up','wpShop'); ?></a></li>
<?php } ?>
<?php
// do we want a search link?
if($OPTION['wps_search_link_enable']){
	if (is_page($search->post_title)) {$the_li_class='current_page_item';}else {$the_li_class='';} ?>
	<li class="<?php echo $the_li_class; ?>"><a class="extLoadTrigger" href="<?php echo get_permalink($search->ID); ?>" rel="div.overlay:eq(1)" title="<?php _e('Find products','wpShop');?>"><?php echo $search->post_title;?></a></li>	

<?php } ?>
<li>
<div class="switch switcher-currency">
    <div id="currencySelect" class="switch-wrapper">
		<span onclick="popUpMenu(this);">
			<strong class="current currency-USD">
				<span class="flag"></span>
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

</li>
<?php
// do we want the eCommerce engine?  
if($OPTION['wps_shoppingCartEngine_yes']) { ?>
	<li class="bag" id="header-bag-info">
		<a href="<?php echo get_cart_url(); ?>?showCart=1" class="cti">0</a>
	</li>
<?php } ?>	

