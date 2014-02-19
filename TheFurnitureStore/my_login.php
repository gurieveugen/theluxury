<?php
/*
Template Name: MY Login
*/
global $OPTION;

if ( is_user_logged_in() && ( !isset($_GET['action']) || $_GET['action'] != 'logout') ) 
{
	wp_redirect(site_url());
	exit();
}
if ( force_ssl_admin() && !is_ssl() ) {
	if ( 0 === strpos($_SERVER['REQUEST_URI'], 'http') ) {
		wp_redirect(preg_replace('|^http://|', 'https://', $_SERVER['REQUEST_URI']));
		exit();
	} else {
		wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
		exit();
	}
}

function login_header($title = 'Log In', $message = '', $wp_error = '') 
{
	global $error, $is_iphone, $interim_login, $current_site;

	// Don't index any of these forms
	get_header();
	set_referral();
	
	// this is a "fake cronjob" = whenever default index page is called - the age of dlinks is checked - and removed if necessary
	$DIGITALGOODS = load_what_is_needed('digitalgoods');	//change.9.10
	$DIGITALGOODS->delete_dlink();							//change.9.10
	//content to feature?
	$featuredCont 		= $OPTION['wps_feature_option'];
	//type of effect?
	$featuredEffect 	= $OPTION['wps_featureEffect_option'];
	// sidebar location?
	$WPS_sidebar		= $OPTION['wps_sidebar_option'];
	switch($WPS_sidebar)
	{
		case 'alignRight':	$the_float_class 	= 'alignleft';	break;
		case 'alignLeft':
		default:	$the_float_class 	= 'alignright';	break;
	}
	if($OPTION['wps_front_sidebar_disable']) 
	{
		$the_div_class 	= 'featured_wrap featured_wrap_alt';
		$the_div_id 	= 'main_col_alt';
	} else 
	{
		$the_div_class 	= 'featured_wrap ' .$the_float_class;
		$the_div_id 	= 'main_col';
	}
	
	add_filter( 'pre_option_blog_public', '__return_zero' );
	if ( empty($wp_error) )
		$wp_error = new WP_Error();
		remove_action('login_head','dd_loginPageCss',10);
		do_action('login_head'); ?>
	<link rel="stylesheet" type="text/css" media="all" href="<?=get_option('siteurl')."/prelaunch/"?>css/styles.css" />	
	<div id="<?php echo $the_div_id;?>" class="<?php echo $the_div_class;?>">
	<?php if($title == 'Log In') { ?><a href="<?php echo rtrim(get_my_theme_register_link(),"?"); echo (!empty( $_REQUEST['redirect_to'] ))?  '?redirect_to='.$_REQUEST['redirect_to']:''; ?>"><div class="log_head_img"></div></a><?php }?>
	<div id="login">
		<div class="login">
			<h1><?php echo $title; ?></h1>
			<div class="arw_corn"></div>
	<?php
	$message = apply_filters('login_message', $message);
	if ( !empty( $message ) ) echo '<div class="clear"></div><p class="message" style="border:1px solid #C1C1C1; background:#FFFFCC; padding:5px 10px;">'.$message.'</p>' . "\n";
	// Incase a plugin uses $error rather than the $errors object
	if ( !empty( $error ) ) {
		$wp_error->add('error', $error);
		unset($error);
	}
	if (is_wp_error($wp_error)) {
		if ( $wp_error->get_error_code() ) 
		{
			$errors = '';
			$messages = '';
			foreach ( $wp_error->get_error_codes() as $code ) 
			{
				$severity = $wp_error->get_error_data($code);
				foreach ( $wp_error->get_error_messages($code) as $error )
				{
					if ( 'message' == $severity )
						$messages .= '	' . $error . "<br />\n";
					else
						$errors .= '	' . $error . "<br />\n";
				}
			}		
			if ( !empty($errors) ) {
				$errors = str_replace('wp-login.php?action=lostpassword', 'login?action=lostpassword', $errors);
				echo '<div class="box-red">' . $errors. "</div>\n";
			} else if ( !empty($messages) ) {
				echo '<p class="good_message">' . $messages . "</p>\n";
			}
		}
	}
} // End of login_header()

// Main
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'login';
$errors = new WP_Error();

// validate action so as to default to the login screen
if ( !in_array($action, array('logout', 'lostpassword', 'retrievepassword', 'resetpass', 'rp', 'login'), true) && false === has_filter('login_form_' . $action) )
	$action = 'login';

nocache_headers();

header('Content-Type: '.get_bloginfo('html_type').'; charset='.get_bloginfo('charset'));

if ( defined('RELOCATE') ) { // Move flag is set
	if ( isset( $_SERVER['PATH_INFO'] ) && ($_SERVER['PATH_INFO'] != $_SERVER['PHP_SELF']) )
		$_SERVER['PHP_SELF'] = str_replace( $_SERVER['PATH_INFO'], '', $_SERVER['PHP_SELF'] );

	$schema = is_ssl() ? 'https://' : 'http://';
	if ( dirname($schema . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']) != get_option('siteurl') )
		update_option('siteurl', dirname($schema . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']) );
}

//Set a cookie now to see if they are supported by the browser.
setcookie(TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN);
if ( SITECOOKIEPATH != COOKIEPATH )
	setcookie(TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN);

// allow plugins to override the default actions, and to add extra actions if they want
do_action('login_form_' . $action);

$http_post = ('POST' == $_SERVER['REQUEST_METHOD']);
switch ($action) {

case 'logout' :
	wp_logout();
	session_start();
	unset($_SESSION['uname']);
    unset($_SESSION['user_logged']);
    unset($_SESSION['timeout']);
    unset($_SESSION['browser']);
    unset($_SESSION['level']);
    unset($_SESSION['uid']);
    unset($_SESSION['fname']);
    unset($_SESSION['lname']);
	wp_safe_redirect(site_url());
	exit();

break;

case 'lostpassword' :
case 'retrievepassword' :
	if ( $http_post ) 
	{
		$errors = wps_retrieve_password();
		if ( !is_wp_error($errors) ) 
		{
			$redirect_to = !empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : get_my_theme_login_link().'checkemail=confirm';
			wp_safe_redirect( $redirect_to );
			exit();
		}
	}
	if(!is_wp_error($errors)) $login_errors = new WP_Error();
			
	if ( isset($_GET['error']) && 'invalidkey' == $_GET['error'] ) {
		$errors->add('invalidkey', __('Sorry, that key does not appear to be valid.'));
	}
	$redirect_to = apply_filters( 'lostpassword_redirect', !empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '' );
	do_action('lost_password');
	login_header(__('Lost Password'), '', $errors);
	$user_login = isset($_POST['user_login']) ? stripslashes($_POST['user_login']) : '';
?>

	<form name="lostpasswordform" id="lostpasswordform" action="" method="post">
		<div class="fiel_row">
			<div class="fiel_nam"><?php _e('Username or E-mail:') ?></div>
			<input type="text" name="user_login" id="user_login" class="fiel_in" value="<?php echo esc_attr($user_login); ?>" size="20" tabindex="10" />
		</div>
		<?php do_action('lostpassword_form'); ?>
		<div class="fiel_row">
		<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>" />
		<input type="hidden" name="action" value="lostpassword" />
		<p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="button" value="<?php esc_attr_e('Reset Password'); ?>" tabindex="100" />
		</p>
		</div>
	</form>
	<div class="fiel_row">
		<?php if (get_option('users_can_register')) : ?>
			<a href="<?php echo rtrim(get_my_theme_login_link(),"?"); echo (!empty( $_REQUEST['redirect_to'] ))?  '?redirect_to='.$_REQUEST['redirect_to']:''; ?>"><?php _e('Log in') ?></a>&nbsp;|&nbsp;<a href="<?php echo rtrim(get_my_theme_register_link(),"?"); echo (!empty( $_REQUEST['redirect_to'] ))?  '?redirect_to='.$_REQUEST['redirect_to']:''; ?>"><?php _e('Register') ?></a>
		<?php else : ?>
			<a href="<?= rtrim(get_my_theme_login_link(),"?").(!empty( $_REQUEST['redirect_to'] ))?  '?redirect_to='.$_REQUEST['redirect_to']:''; ?>"><?php _e('Log in') ?></a>
		<?php endif; ?>
	</div>
	</div></div></div>
	<?php if ($OPTION['wps_front_sidebar_disable'] != TRUE) 
	{

		switch($OPTION['wps_sidebar_option']){
			case 'alignRight':	$the_float_class 	= 'alignright';	break;
			case 'alignLeft':	$the_float_class 	= 'alignleft';	break;
		}

		$the_div_class 	= 'sidebar frontPage_sidebar noprint '. $the_float_class; ?>

		<div class="<?php echo $the_div_class;?> front-widgets">
			<div class="padding">
				<?php if ( is_sidebar_active('frontpage_widget_area') ) : dynamic_sidebar('frontpage_widget_area'); endif; ?>
			</div><!-- padding -->
		</div><!-- frontPage_sidebar -->
		
	<?php  }  ?>
	<script type="text/javascript">
	try{document.getElementById('user_login').focus();}catch(e){}
	if(typeof wpOnload=='function')wpOnload();
	</script>
	<?php get_footer(); 
break;

case 'resetpass' :
	$show_form = true;
	if ($_POST['newpass'] == 'true') {
		$key = $_POST['key'];
		$login = $_POST['login'];
		$errors = new_password();
		if (!is_wp_error($errors)) {
			$redirect_to = get_permalink($OPTION['wps_account_login_page']).'?newpass=true';
			wp_safe_redirect($redirect_to);
			exit();
		}
	} else {
		$key = $_GET['key'];
		$login = $_GET['login'];
		$errors = resetpass($key, $login);
		if (is_wp_error($errors)) {
			$show_form = false;
		}
	}
			
	login_header(__('Lost Password'), '', $errors);
?>

	<?php if ($show_form) { ?>
	<form name="lostpasswordform" id="lostpasswordform" action="" method="post">
		<div class="fiel_row">
			<div class="fiel_nam"><?php _e('New Password:') ?></div>
			<input type="password" name="new_pass" id="new_pass" class="fiel_in" value="<?php echo $_POST['new_pass']; ?>" size="20" tabindex="10" />
		</div>
		<div class="fiel_row">
			<div class="fiel_nam"><?php _e('Confirm Password:') ?></div>
			<input type="password" name="new_pass2" id="new_pass2" class="fiel_in" value="" size="20" tabindex="10" />
		</div>
		<div class="fiel_row">
			<input type="hidden" name="action" value="resetpass" />
			<input type="hidden" name="newpass" value="true" />
			<input type="hidden" name="key" value="<?php echo $key; ?>" />
			<input type="hidden" name="login" value="<?php echo $login; ?>" />
			<p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="button" value="<?php esc_attr_e('Submit'); ?>" tabindex="100" /></p>
		</div>
	</form>
	<?php } ?>
	<div class="fiel_row">
		<?php if (get_option('users_can_register')) : ?>
			<a href="<?php echo rtrim(get_my_theme_login_link(),"?"); echo (!empty( $_REQUEST['redirect_to'] ))?  '?redirect_to='.$_REQUEST['redirect_to']:''; ?>"><?php _e('Log in') ?></a>&nbsp;|&nbsp;<a href="<?php echo rtrim(get_my_theme_register_link(),"?"); echo (!empty( $_REQUEST['redirect_to'] ))?  '?redirect_to='.$_REQUEST['redirect_to']:''; ?>"><?php _e('Register') ?></a>
		<?php else : ?>
			<a href="<?= rtrim(get_my_theme_login_link(),"?").(!empty( $_REQUEST['redirect_to'] ))?  '?redirect_to='.$_REQUEST['redirect_to']:''; ?>"><?php _e('Log in') ?></a>
		<?php endif; ?>
	</div>
	</div></div></div>
	<?php if ($OPTION['wps_front_sidebar_disable'] != TRUE) 
	{

		switch($OPTION['wps_sidebar_option']){
			case 'alignRight':	$the_float_class 	= 'alignright';	break;
			case 'alignLeft':	$the_float_class 	= 'alignleft';	break;
		}

		$the_div_class 	= 'sidebar frontPage_sidebar noprint '. $the_float_class; ?>

		<div class="<?php echo $the_div_class;?>">
			<div class="padding">
				<?php if ( is_sidebar_active('frontpage_widget_area') ) : dynamic_sidebar('frontpage_widget_area'); endif; ?>
			</div><!-- padding -->
		</div><!-- frontPage_sidebar -->
		
	<?php  }  ?>
	<script type="text/javascript">
	try{document.getElementById('user_login').focus();}catch(e){}
	if(typeof wpOnload=='function')wpOnload();
	</script>
	<?php get_footer(); 
break;

case 'rp' :
	$errors = wps_reset_password($_GET['key'], $_GET['login']);

	if ( !is_wp_error($errors) ) {
		wp_redirect(get_permalink($OPTION['wps_account_login_page']).'?checkemail=newpass');
		exit();
	}

	wp_redirect(get_permalink($OPTION['wps_account_register_page']).'?action=lostpassword&error=invalidkey');
	exit();

break;

case 'login' :
default:
	$secure_cookie = '';
	$fl =0;
	$interim_login = isset($_REQUEST['interim-login']);
	$creds['user_login'] = $_POST['log'];
	$creds['user_password'] = $_POST['pwd'];
	$creds['remember'] = $_POST['rememberme'];
	
	// If the user wants ssl but the session is not ssl, force a secure cookie.
	if ( !empty($_POST['log']) && !force_ssl_admin() ) 
	{
		$user_name = sanitize_user($_POST['log']);
		if ( $user = get_userdatabylogin($user_name) ) 
		{
			if ( get_user_option('use_ssl', $user->ID) ) 
			{
				$secure_cookie = true;
				force_ssl_admin(true);
			}
		}
		else if(isset($_POST['wp-submit']) && $_POST['wp-submit'] == 'Log In' && empty($_POST['pwd'] )) 
		{
			if(!is_wp_error($login_errors)) $login_errors = new WP_Error();
			$login_errors->add('empty_password', __("Please enter your password"));
			$fl = 1;
		}
		else if(is_email(trim($_POST['log'])))
		{
		
			$user = get_user_by_email(trim($_POST['log']));
			$creds['user_login'] = $user->user_login;
			if(empty($user))
			{
				$login_errors = new WP_Error();
				$login_errors->add('invalid_username', __("Please check email you have entered"));
				$fl = 1;
			}
		}
		else
		{
			if(!is_wp_error($login_errors)) $login_errors = new WP_Error();
			$login_errors->add('invalid_username', __("Please check username you have entered"));
			$fl = 1;
		}
	}
	else if(isset($_POST['wp-submit']) && $_POST['wp-submit'] == 'Log In' ) 
	{
		if(!is_wp_error($login_errors)) $login_errors = new WP_Error();
		$login_errors->add('empty_username', __("Please enter your username or email"));
	}
	if(isset($_POST['wp-submit']) && $_POST['wp-submit'] == 'Log In' && empty($_POST['pwd']) && $fl == 0) 
	{
		if(!is_wp_error($login_errors)) $login_errors = new WP_Error();
		$login_errors->add('empty_password', __("Please enter your password"));
	}
	
	if ( isset( $_REQUEST['redirect_to'] ) ) 
	{
		$redirect_to = $_REQUEST['redirect_to'];
		// Redirect to https if user wants ssl
		if ( $secure_cookie && false !== strpos($redirect_to, 'wp-admin') )
			$redirect_to = preg_replace('|^http://|', 'https://', $redirect_to);
	} 
	else 
	{
		$redirect_to = home_url();
	}
	$reauth = empty($_REQUEST['reauth']) ? false : true;
	
	
	// the admin via http or https.
	if ( !$secure_cookie && is_ssl() && force_ssl_login() && !force_ssl_admin() && ( 0 !== strpos($redirect_to, 'https') ) && ( 0 === strpos($redirect_to, 'http') ) )
		$secure_cookie = false;

	if ( !is_wp_error($login_errors))
	{
		$user = wps_user_login($creds, $secure_cookie);
	}
	else if(isset($user) && !empty($user))
	{
		$user = wps_user_login($creds, $secure_cookie);
	}
	$redirect_to = apply_filters('login_redirect', $redirect_to, isset( $_REQUEST['redirect_to'] )? $_REQUEST['redirect_to'] : '', $user);	
	if (( !is_wp_error($user) && !is_wp_error($login_errors) ) || is_user_logged_in()) 
	{
		if ((in_array('staff', $user->roles) || in_array('buyer', $user->roles)) && !check_staff_user_by_ip($user)) {
			$redirect_to = home_url();
			wp_safe_redirect($redirect_to);
			exit();
		}
		
		if ( $interim_login ) 
		{
			$message = __('You have logged in successfully.');
			login_header( '', $message ); ?>
			<script type="text/javascript">setTimeout( function(){window.close()}, 8000);</script>
				<p class="alignright">
					<input type="button" class="button-primary" value="<?php esc_attr_e('Close'); ?>" onclick="window.close()" />
				</p>
			</div>
			<?php
			get_footer(); 
			exit;
		}
		// If the user can't edit posts, send them to their profile.
		if (!strlen($redirect_to)) { $redirect_to = home_url(); }
		wp_safe_redirect($redirect_to);
		exit();
	}
	//if($reauth) echo 1;
	if(is_wp_error($login_errors)) $errors = $login_errors;
	else 	$errors = $user;
	//exit();
	// Clear errors if loggedout is set.
	if ( (!empty($_GET['loggedout']) || $reauth ) && !isset($_POST['wp-submit']) )
		$errors = new WP_Error();

	// If cookies are disabled we can't log in even with a valid user+pass
	if ( isset($_POST['testcookie']) && empty($_COOKIE[TEST_COOKIE]) )
		$errors->add('test_cookie', __("<strong>ERROR</strong>: Cookies are blocked or not supported by your browser. You must <a href='http://www.google.com/cookies.html'>enable cookies</a> to use WordPress."));

	// Some parts of this script use the main login form to display a message
	if(isset($_GET['loggedout']) && TRUE == $_GET['loggedout'] && !isset($_POST['wp-submit'])  )
	{
		$errors->add('loggedout', __('You are now logged out.'), 'message');
		unset($_GET['loggedout']);
	}
	elseif	( isset($_GET['registration']) && 'disabled' == $_GET['registration'] )
		$errors->add('registerdisabled', __('User registration is currently not allowed.'));
	elseif	( $interim_login )
		$errors->add('expired', __('Your session has expired. Please log-in again.'), 'message');
	else if(stripos($_SERVER['REQUEST_URI'],"item_form",0))
		$errors->add('login', __('Please login or register with us to sell your bag'), 'message');
	// Clear any stale cookies.
	if ( $reauth ) { wp_clear_auth_cookie(); }

	if (isset($_GET['checkemail']) && $_GET['checkemail'] == 'confirm') {
		$message = __('Check your e-mail for the confirmation link.');
	} else if (isset($_GET['checkemail']) && $_GET['checkemail'] == 'newpass') {
		$message = __('Check your e-mail for your new password.');
	} else if ( isset($_GET['action']) && $_GET['action'] == 'registered' ) {
		$message = __('Registration complete. Please login now.');
	} else if ( isset($_GET['newpass']) && $_GET['newpass'] == 'true' ) {
		$message = __('Password changed. Please login now.');
	}

	login_header(__('Log In'), $message, $errors);
	if ( isset($_POST['log']) )
		$user_login = ( 'incorrect_password' == $errors->get_error_code() || 'empty_password' == $errors->get_error_code() ) ? esc_attr(stripslashes($_POST['log'])) : '';
	$rememberme = ! empty( $_POST['rememberme'] );
	if (!isset($_POST['log']) && $_GET['installments'] == 1) {
		echo '<p style="clear:both; padding:5px; border:1px solid #C1C1C1; background:#FFFFCC">' . __('Please login or register with us to purchase on installments.') . '</p>';
	}
?>
	<form name="loginform" id="loginform" action="" method="post">
		<div class="fiel_row">
			<div class="fiel_nam"><?php _e('Email') ?></div>
			<input type="text" name="log" id="user_login" class="fiel_in" value="<?php echo esc_attr($user_login); ?>" size="20" tabindex="10" />
		</div>
		<div class="fiel_row">
			<div class="fiel_nam"><?php _e('Password') ?></div>
			<input type="password" name="pwd" id="user_pass" class="fiel_in" value="" size="20" tabindex="20" /></label>
		</div>
		<?php
			global $wp_actions;
			//print_r($wp_actions['login_form'])
			
		?>
		<?php do_action('login_form'); ?>
		<div class="fiel_row">
			<div class="fiel_nam">
				<input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90"<?php checked( $rememberme ); ?> /> <?php esc_attr_e('Remember Me'); ?>
			</div>
		
			<input type="submit" name="wp-submit" id="wp-submit" class="button" value="<?php esc_attr_e('Log In'); ?>" tabindex="100" />
			<input type="hidden" name="redirect_to" id="redirect_to" value="<?php echo esc_attr($redirect_to); ?>" />
	<?php	if ( $interim_login ) { ?>
			<input type="hidden" name="interim-login" value="1" />
	<?php 	} ?>
			<input type="hidden" name="testcookie" value="1" />
		</div>
		<!--<div class="fiel_row">
			<div class="login-with-fb" id="login-with-fb">Login With My Facebook</div>
		</div>-->
	</form>
	<?php if ( !$interim_login ) {  ?>
		<div class="fiel_row">
			<?php if ( isset($_GET['checkemail']) && in_array( $_GET['checkemail'], array('confirm', 'newpass') ) ) : ?>
			<?php elseif ( get_option('users_can_register') ) : ?>
				<a href="<?php echo rtrim(get_my_theme_register_link(),"?"); echo (!empty( $_REQUEST['redirect_to'] ))?  '?redirect_to='.$_REQUEST['redirect_to']:''; ?>"><?php _e('Register') ?></a> |
				<a href="<?=  get_my_theme_login_link(); ?>action=lostpassword<?=(!empty( $_REQUEST['redirect_to'] ))?  '&redirect_to='.$_REQUEST['redirect_to']:''?>" title="<?php _e('Password Lost and Found') ?>"><?php _e('Lost your password?') ?></a>
			<?php else : ?>
				<a href="<?= get_my_theme_login_link(); ?>action=lostpassword<?=(!empty( $_REQUEST['redirect_to'] ))?  '&redirect_to='.$_REQUEST['redirect_to']:''?>" title="<?php _e('Password Lost and Found') ?>"><?php _e('Lost your password?') ?></a>
			<?php endif; ?>
		</div>
	</div>
	<?php } else { ?>
	</div>
	<?php } ?>
	</div>
	</div>
	<?php if ($OPTION['wps_front_sidebar_disable'] != TRUE) 
	{

		switch($OPTION['wps_sidebar_option']){
			case 'alignRight':	$the_float_class 	= 'alignright';	break;
			case 'alignLeft':	$the_float_class 	= 'alignleft';	break;
		}

		$the_div_class 	= 'sidebar front-widgets frontPage_sidebar noprint '. $the_float_class; ?>

		<div class="<?php echo $the_div_class;?>">
			<div class="padding">
				<?php if ( is_sidebar_active('frontpage_widget_area') ) : dynamic_sidebar('frontpage_widget_area'); endif; ?>
			</div><!-- padding -->
		</div><!-- frontPage_sidebar -->
		
	<?php  }  ?>
	<script type="text/javascript">
		function wp_attempt_focus(){
			setTimeout( function(){ try{
				<?php if ( $user_login || $interim_login ) { ?>
					d = document.getElementById('user_pass');
				<?php } else { ?>
					d = document.getElementById('user_login');
				<?php } ?>
				d.value = '';
				d.focus();
				} catch(e){}
			}, 200);
		}
		<?php if ( !$error ) 
		{ ?>
			wp_attempt_focus();
		<?php } ?>
		if(typeof wpOnload=='function')wpOnload();
	</script>
	<?php
	if(strripos(get_option('siteurl'),"localhost",0)) $apikey = "276284119066763"; 
	else if(strripos(get_option('siteurl'),"ancorps",0)) $apikey ="251447664896335";
	else $apikey ="250313664982898";	
?>
<script type="text/javascript">
var base = '<?=get_option('siteurl')?>';
window.fbAsyncInit = function() {
	FB.init({
	appId   : <?=$apikey?>,
	session : null, // don't refetch the session when PHP already has it
	status  : true, // check login status
	cookie  : true, // enable cookies to allow the server to access the session
	xfbml   : true // parse XFBML
	});
	FB.getLoginStatus(function(response) {
		if (response && (response.status !== "unknown")) 
		{	jQuery.cookie("fbs", response.status);} 
		else {	jQuery.cookie("fbs", null);}
	});				
	jQuery(document).trigger('fbInit');
};

FACEBOOK_PERMS = "publish_stream,email";
</script>
<script type="text/javascript" src="<?=get_option('siteurl')?>/prelaunch/js/jquery.cookies.js"></script>
<script src="<?=get_option('siteurl')?>/prelaunch/js/referrals.js" type="text/javascript"></script>
	<?php  get_footer(); 
	break;
} // end action switch
