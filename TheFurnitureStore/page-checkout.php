<?php
/*
Template Name: Checkout Page
*/
?>
<?php get_header(); ?>

	<?php if ($_GET['confirmation'] == 'true') { ?>
		<?php include WP_CONTENT_DIR.'/themes/'.WPSHOP_THEME_NAME.'/lib/pages/shop_checkout_confirmation.php'; ?>
	<?php } else { ?>
		<?php include WP_CONTENT_DIR.'/themes/'.WPSHOP_THEME_NAME.'/lib/pages/shop_checkout.php'; ?>
	<?php } ?>

<?php get_footer(); ?>