<?php
global $OPTION;

if(isset($_GET['showCart']) && $_GET['showCart'] == '1') // show the shopping cart
{											
	include WP_CONTENT_DIR.'/themes/'.WPSHOP_THEME_NAME.'/lib/pages/shop_cart.php';
}
elseif(isset($_GET['orderNow']) && $_GET['orderNow'] == '1') // checkout payments and delivery
{
	include WP_CONTENT_DIR.'/themes/'.WPSHOP_THEME_NAME.'/lib/pages/shop_checkout_payment.php';
}
elseif(isset($_GET['orderNow']) && $_GET['orderNow'] == '2') // checkout billing & shipping
{
	include WP_CONTENT_DIR.'/themes/'.WPSHOP_THEME_NAME.'/lib/pages/shop_checkout_billing.php';
}
elseif(isset($_GET['orderNow']) && $_GET['orderNow'] == '3') // checkout order review
{
	include WP_CONTENT_DIR.'/themes/'.WPSHOP_THEME_NAME.'/lib/pages/shop_checkout_review.php';
}
elseif(strlen($_GET['orderNow']) && $_GET['orderNow'] == 'confirm') // Order confirmation page
{
	include WP_CONTENT_DIR.'/themes/'.WPSHOP_THEME_NAME.'/lib/pages/shop_checkout_confirmation.php';
}
elseif(isset($_GET['showTerms']) && $_GET['showTerms'] == '1') // Terms and conditions
{
	echo "<p>";
	echo nl2br($OPTION['wps_terms_conditions']);
	echo "</p>";
}
elseif(isset($_GET['showMap']) && $_GET['showMap'] == 1) // Show map
{
	$maplink = $OPTION['wps_google_maps_link'];
	echo "<a href='$maplink' target='_blank'>".__('Google-Map-Link','wpShop')."</a>";
}
elseif(isset($_GET['checkOrderStatus']) && $_GET['checkOrderStatus'] == 1) // Check order status
{
	if($OPTION['wps_shop_mode'] == 'Inquiry email mode'){
		$tid = trim($_POST['tid']);
		echo "<p>".check_inquiry_status($tid)."</p>";
	} else {
		$tid = trim($_POST['tid']);
		echo "<p>".check_order_status($tid)."</p>";
	}
}
?>
