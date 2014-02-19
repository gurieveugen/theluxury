<?php
/*
Template Name: MY Register
*/
global $OPTION;

if ( is_user_logged_in()) 
{
	$q = (!empty( $_REQUEST['page_referrer'] ))?  '?page_referrer='.$_REQUEST['page_referrer']:'';
	wp_redirect(site_url().$q);
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

$errors = new WP_Error();

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
do_action('login_form_register');

$http_post = ('POST' == $_SERVER['REQUEST_METHOD']);
if ( is_multisite() ) 
{
	// Multisite uses wp-signup.php
	wp_redirect( apply_filters( 'wp_signup_location', get_bloginfo('wpurl') . '/wp-signup.php' ) );
	exit;
}

if ( !get_option('users_can_register') ) {
	wp_redirect(get_my_theme_login_link().'registration=disabled');
	exit();
}
$user_login = $user_email = $first_name = $last_name =  '';
if ( $http_post ) 
{
	$_POST['log'] = $_POST['email'];
	$user_email = trim($_POST['email']);
	$user_login = trim($_POST['email']);
	$gender = $_POST['gender'];

	$errors = wps_register_new_user();
	if ( !is_wp_error($errors) ) 
	{
		// registered. autologin
		$secure_cookie = '';
		$interim_login = isset($_REQUEST['interim-login']);
		$user_name = sanitize_user($_POST['email']);
		if ( $user = get_userdatabylogin($user_name) ) 
		{
			if ( get_user_option('use_ssl', $user->ID) ) 
			{
				$secure_cookie = true;
				force_ssl_admin(true);
			}
		}
		if ( isset( $_REQUEST['redirect_to'] ) ) $redirect_to = $_REQUEST['redirect_to'];
		else $redirect_to = home_url();
		$reg_red = '?page_referrer=registered';
		if (isset($_REQUEST['redirect_to'])) {
			$reg_red = $_REQUEST['redirect_to'];
			if (strpos($reg_red, 'page_referrer=registered') === false) {
				if (strpos($reg_red, '?') !== false) { $reg_red .= '&'; } else { $reg_red .= '?'; }
				$reg_red .= 'page_referrer=registered';
			}
		}
		$creds['user_login'] = $_POST['log'];
		$creds['user_password'] = $_POST['pwd'];
		$creds['remember'] = $_POST['rememberme'];
		$user = wps_user_login($creds, $secure_cookie);
		if ( !is_wp_error($user) || is_user_logged_in()) 
		{
			if ( $interim_login ) 
			{
				$message = '<p class="message">' . __('You have logged in successfully.') . '</p>';
				login_header( '', $message ); ?>
				<script type="text/javascript">setTimeout( function(){window.close()}, 8000);</script>
					<p class="alignright">
						<input type="button" class="button-primary" value="<?php esc_attr_e('Close'); ?>" onclick="window.close()" />
					</p>
				</div>
				<?php		
				get_my_footer(); 
				exit;
			}
			// If the user can't edit posts, send them to their profile.
			if ( isset( $_REQUEST['redirect_to'] ) ) $redirect_to = $_REQUEST['redirect_to'];
			else if($user->user_level == 10 && false == strpos($redirect_to, 'wp-admin')) $redirect_to = admin_url(); 
			else if($user->user_level == 0 && false !== strpos($redirect_to, 'wp-admin')) $redirect_to = home_url();
			else if ( !$user->has_cap('edit_posts')&& (empty( $redirect_to ) || $redirect_to == 'wp-admin/' || $redirect_to == admin_url()))
				$redirect_to = home_url();
			else $redirect_to = home_url();
			$redirect_to .= '?page_referrer=registered';
			wp_safe_redirect($redirect_to);
			exit();
		}
		$redirect_to = !empty( $_POST['redirect_to'] ) ? $_POST['redirect_to'] : get_my_theme_login_link().'action=registered';
		wp_safe_redirect( $redirect_to );
		exit();
	}
}
//print_r($errors);
$reg_red = '?page_referrer=registered';
if (!empty($_REQUEST['redirect_to'])) {
	$reg_red = $_REQUEST['redirect_to'];
	if (strpos($reg_red, '?') !== false) { $reg_red .= '&'; } else { $reg_red .= '?'; }
	$reg_red .= 'page_referrer=registered';
}
$redirect_to = apply_filters( 'registration_redirect', $reg_red );
global $error, $is_iphone, $interim_login, $current_site;
$title = 'Registration Form';
if(stripos($_SERVER['REQUEST_URI'],"item_form",0))
		$message = '<p class="hint">' . __('Please register or login with us to sell your bag') . '</p>';
	else $message = '<p class="hint">' . __('Register For This Site') . '</p>';
$wp_error = $errors;
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
	case 'alignLeft':	$the_float_class 	= 'alignright';	break;
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
//add_action( 'login_head', 'noindex' );
if ( empty($wp_error) )
	$wp_error = new WP_Error();
	remove_action('login_head','dd_loginPageCss',10);
	do_action('login_head'); ?>
<link rel="stylesheet" type="text/css" media="all" href="<?=get_option('siteurl')."/prelaunch/"?>css/styles.css" />	
<div id="<?php echo $the_div_id;?>" class="<?php echo $the_div_class;?>">
<div class="reg_head_img"></div>
<div id="user_register">
	<div class="user_register">
		<h1>Registration Form</h1>
		<div class="arw_corn"></div>
		<?php
		$message = apply_filters('login_message', $message);
		//if ( !empty( $message ) ) echo $message . "\n";
		// Incase a plugin uses $error rather than the $errors object
		if ( !empty( $error ) ) {
			$wp_error->add('error', $error);
			unset($error);
		}
		
		if ( $wp_error->get_error_code() ) 
		{
			$errors = $messages = '';
			foreach ( $wp_error->get_error_codes() as $code ) 
			{
				$severity = $wp_error->get_error_data($code);
				foreach ( $wp_error->get_error_messages($code) as $error )
				{
					if ( 'message' == $severity )	$messages .= '	' . $error . "<br />\n";
					else $errors .= '	' . $error . "<br />\n";
				}
			}
			if ( !empty($errors) )	echo '<div class="box-red">'.$errors."</div>\n";
			if ( !empty($messages) )	echo '<p class="good_message">'. $messages . "</p>\n";
		}
		else if ( !empty( $message ) ) echo $message . "\n";
		if (!is_array($gender)) { $gender = array(); }
	?>
	
	
		<form name="registerform" id="registerform" action="" method="post" class="register-form">
			<div class="fiel_row">
				<div class="fiel_nam"><?php _e('E-mail') ?></div>
				<input type="text" name="email" id="user_email" class="fiel_in" value="<?php echo esc_attr(stripslashes($user_email)); ?>" size="25" />
			</div>
			<div class="fiel_row">
				<div class="fiel_nam"><?php _e('Password') ?></div>
				<input type="password" name="pwd" id="pass1" autocomplete="off" class="fiel_in" value="" size="25" />
			</div>
			<div class="fiel_row">
				<div class="fiel_nam"><?php _e('Password Again') ?></div>
				<input type="password" name="pass2" id="pass2" autocomplete="off" class="fiel_in" value="" size="25" />
			</div>
			<div class="fiel_row gender-row">
				<div class="fiel_nam"><?php _e('Gender') ?></div>
				<ul>
					<li><input type="checkbox" name="gender[]" value="Male"<?php if (in_array('Male', $gender)) { echo ' CHECKED'; } ?> /></li>
					<li>Male</li>
					<li>&nbsp;</li>
					<li><input type="checkbox" name="gender[]" value="Female"<?php if (in_array('Female', $gender)) { echo ' CHECKED'; } ?> /></li>
					<li>Female</li>
				</ul>
			</div>
			<?php do_action('register_form'); ?>
			<input type="hidden" name="redirect_to" id="redirect_to" value="<?php if (strlen($_GET['redirect_to'])) { echo esc_attr($_GET['redirect_to']); } ?>" />
			<!--<div class="fiel_row">
				<div class="login-with-fb" id="login-with-fb">Register With My Facebook</div>
			</div>-->
			<div class="fiel_row">
				<div class="fiel_nam">
					<a href="<?php echo rtrim(get_my_theme_login_link(),'?'); echo (!empty( $_REQUEST['redirect_to'] ))?  '?redirect_to='.$_REQUEST['redirect_to']:''; ?>"><?php _e('Log in') ?></a> |
				<a href="<?php echo get_my_theme_login_link ().'action=lostpassword'; echo (!empty( $_REQUEST['redirect_to'] ))?  '&redirect_to='.$_REQUEST['redirect_to']:''; ?>" title="<?php _e('Password Lost and Found') ?>"><?php _e('Lost your password?') ?></a>
				</div>
				<input type="submit" name="wp-submit" id="wp-submit" class="button" value="<?php esc_attr_e('Register'); ?>" tabindex="100" />
			</div>
			<input type="hidden" name="utm_source" id="utm_source">
			<input type="hidden" name="utm_medium" id="utm_medium">
			<input type="hidden" name="utm_campaign" id="utm_campaign">
			<input type="hidden" name="utm_content" id="utm_content">
			<input type="hidden" name="utm_term" id="utm_term">
		</form>
	</div>
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
		<?php if ( is_sidebar_active('frontpage_widget_area') ) : dynamic_sidebar('frontpage_widget_area'); endif; ?>
	</div><!-- frontPage_sidebar -->
	
<?php  }  ?>
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
//FACEBOOK_PERMS = "read_stream,user_birthday,user_about_me,user_likes,user_education_history, user_hometown, user_interests, user_activities, user_location,publish_stream,email";
</script>
<script type="text/javascript" src="<?=get_option('siteurl')?>/prelaunch/js/jquery.cookies.js"></script>
<script src="<?=get_option('siteurl')?>/prelaunch/js/referrals.js" type="text/javascript"></script>
<?php get_footer(); 
?>
