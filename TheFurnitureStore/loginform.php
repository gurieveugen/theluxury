<?php
if($_GET['failed'] == '1'){
	echo "<div class='login_err'>".__('Sorry - logging failed! Try again.','wpShop')."</div>";
}
$lostPass	= get_page_by_title($OPTION['wps_passLostPg']);
?>

<form id="signInForm" method="post" action="<?php echo get_bloginfo('template_url') . '/login.php'; ?>">
	<fieldset>
		<label for="signInUsername"><?php _e('Username','wpShop');?></label>
		<input type="text" name="signInUsername" id="signInUsername" size="35" maxlength="10" value="<?php echo $_SESSION['uname']; ?>" />
		<label for="signInPassword"><?php _e('Password','wpShop');?></label>
		<input id="signInPassword" type="password" size="35" maxlength="8" value="" name="signInPassword"/>
		<span class="passhelp"> <?php _e('I lost my password. Please','wpShop');?> <a href="<?php echo get_permalink($lostPass->ID); ?>"><?php _e('email it to me','wpShop');?></a></span>
		<input type='hidden' name='gotoURL' value='<?php echo get_real_base_url(); ?>' />
		<input class="formbutton" type="submit" alt="<?php _e('Sign in','wpShop');?>" value="<?php _e('Sign in','wpShop');?>" name="" title="<?php _e('Sign in','wpShop');?>" />
	</fieldset>
</form>