<?php
/*
Template Name: My Profile
*/
global $wpdb, $current_user, $OPTION;
get_header();
global $current_user;
if (is_user_logged_in()) {
get_currentuserinfo(); // grabs the user info and puts into vars
$user_ID = $current_user->ID;
if(!empty($_POST['action'])) {
	require_once(ABSPATH . 'wp-admin/includes/user.php');
	require_once(ABSPATH . WPINC . '/registration.php');
	check_admin_referer('update-profile_' . $user_ID);
	do_action('personal_options_update', $user_ID);
	$errors = edit_user($user_ID);
	$errmsg = '';
	if (is_wp_error($errors)) {
		foreach($errors->get_error_messages() as $message) {
			$errmsg .= $message."<br />";
		}
	}
	// if there are no errors, then process the ad updates
	if ($errmsg == '') {
		do_action('personal_options_update');
	} else {
		$errmsg = '<div class="box-red">' . $errmsg . '</div>';
	}
}
$userdata = get_userdata($user_ID);

// this is a "fake cronjob" = whenever default index page is called - the age of dlinks is checked - and removed if necessary
$DIGITALGOODS = load_what_is_needed('digitalgoods');	//change.9.10
$DIGITALGOODS->delete_dlink();							//change.9.10
?>
<div class="my-profile-wrap">
	<?php the_content(); ?>
	<?php if ($errmsg == '' && !empty($_POST['action'])) { ?>
		<p class="success-message"><?php _e('Your profile has been updated.')?></p>
	<?php } else {  } ?>
	<form name="profile" action="<?php the_permalink(); ?>" method="post" class="my-profile-form">
		<?php wp_nonce_field('update-profile_' . $user_ID) ?>
		<input type="hidden" name="from" value="profile" />
		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="checkuser_id" value="<?php echo $user_ID ?>" />
		<input type="hidden" name="dashboard_url" value="" />
		<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_ID; ?>" />
		<input type="hidden" name="user_login" value="<?php echo $userdata->user_login; ?>" />
		<div class="row">
			<label>First Name<em>*</em></label>
			<input name="first_name" type="text" id="first_name" value="<?php echo $userdata->first_name; ?>" maxlength="100" />
		</div>
		<div class="row">
			<label>Last Name<em>*</em></label>
			<input name="last_name" type="text" id="last_name" value="<?php echo $userdata->last_name; ?>" maxlength="100" />
		</div>
		<div class="row">
			 <label>Email<em>*</em></label>
			<input name="email" type="text" value="<?php echo $userdata->user_email ?>" maxlength="100" />
		</div>
		<div class="submit-row">
			<input name="save" type="submit" class="btn-orange" value="Update Profile" />
		</div>					
		<h2 class="change-title">Change Password</h2>
		<div class="row row-1">
			<label>New Password</label>
			<input name="pass1" id="pass1" autocomplete="off" type="password" maxlength="50" />
			<div class="desc">Leave this field blank unless you'd like to change your password.</div>
		</div>
		<div class="row">
			<label>Password Again</label>
			<input name="pass2" id="pass2" autocomplete="off" type="password" maxlength="50" />
			<div class="desc">Type your new password again.</div>
		</div>
		<div class="submit-row row-2">
			<input name="save" type="submit" class="btn-orange" value="Update Profile" />
		</div>
	</form>	
</div>
<?php
} else {
	echo '<p>You are not allowed to view this page.</p>';
}
get_footer();
?>
	