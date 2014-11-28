<?php
/*
Template Name: Reset Password Page
*/

$rp_errors = false;
$show_form = true;
if ($_POST['newpass'] == 'true') {
	$key = $_POST['key'];
	$login = $_POST['login'];
	$errors = new_password();
	if (!is_wp_error($errors)) {
		wp_safe_redirect(site_url().'/?slp=true&r='.site_url());
		exit();
	} else {
		$rp_errors = wps_get_wp_errors($errors);
	}
} else {
	$key = $_GET['key'];
	$login = $_GET['login'];
	$errors = resetpass($key, $login);
	if (is_wp_error($errors)) {
		$show_form = false;
		$rp_errors = wps_get_wp_errors($errors);
	}
}
?>
<?php get_header(); ?>
<div id="login">
	<div class="login" style="margin:0 auto;">
		<h2 class="form-title"><?php the_title(); ?></h2>
		<?php if ($rp_errors) { ?>
			<p class="error"><?php echo implode('<br />', $rp_errors); ?></p>
		<?php } ?>
		<?php if ($show_form) { ?>
		<form name="lostpasswordform" id="lostpasswordform" action="" method="post">
			<div class="row">
				<label><?php _e('New Password:') ?></label>
				<input type="password" name="new_pass" id="new_pass" class="fiel_in" value="<?php echo $_POST['new_pass']; ?>" size="20" tabindex="10" />
			</div>
			<div class="row">
				<label><?php _e('Confirm Password:') ?></label>
				<input type="password" name="new_pass2" id="new_pass2" class="fiel_in" value="" size="20" tabindex="10" />
			</div>
			<div class="cf">
				<input type="hidden" name="action" value="resetpass" />
				<input type="hidden" name="newpass" value="true" />
				<input type="hidden" name="key" value="<?php echo $key; ?>" />
				<input type="hidden" name="login" value="<?php echo $login; ?>" />
				<p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="btn-orange" value="<?php esc_attr_e('Submit'); ?>" tabindex="100" /></p>
			</div>
		</form>
		<?php } ?>
	</div>
</div>
<?php get_footer(); ?>