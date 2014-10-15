<?php
// for the shopping cart
if (is_cart_page()) {
	$pageTitleClass 		= 'cart-title';
	
	if($OPTION['wps_shop_mode']=='Inquiry email mode'){
		$pageTitle 		= $OPTION['wps_pgNavi_inquireOption'];
	} else {
		$pageTitle 		= $OPTION['wps_pgNavi_cartOption'];
	}
	
//checkout process
} elseif(($_GET[orderNow] == '1') || ($_GET[orderNow] == '2') || ($_GET[orderNow] == '3') || ($_GET[orderNow] == '4') || ($_GET[orderNow] == '5') || ($_GET[orderNow] == '6') || ($_GET[orderNow] == '7') || ($_GET[orderNow] == '8') || ($_GET[orderNow] == '81')) {
	$pageTitleClass 	= 'checkout-title';
	$pageTitle 		= __('Checking out','wpShop');
	
	if (($_GET[orderNow] == '4') || ($_GET[orderNow] == '5') || ($_GET[orderNow] == '6') || ($_GET[orderNow] == '7') || ($_GET[orderNow] == '81')) {$pageTitle 		= __('Checked Out','wpShop');}
	
// confirmation pages	
} elseif(($_GET[confirm] == '1') || ($_GET[confirm] == '2') || ($_GET[confirm] == '3')) {
	$pageTitleClass 	= 'confirmation-title';
	$pageTitle 		= __('Confirmation Page','wpShop');

// Term & Conditions page	
} elseif($_GET[showTerms] == '1') {
	$pageTitleClass 	= 'terms-conditions-title';
	$pageTitle 		= __('Terms &amp; Conditions','wpShop');
	
// Map page	
} elseif($_GET[showMap] == 1) {
	$pageTitleClass 	= 'find-us-title';
	$pageTitle 		= __('How to find us','wpShop');

// Order Status page	
} elseif($_GET[checkOrderStatus] == 1) {
	$pageTitleClass 	= 'terms-conditions-title';
	
	if($OPTION['wps_shop_mode'] == 'Inquiry email mode'){
		$pageTitle 		= __('The status of your Enquiry','wpShop');
	} else{
		$pageTitle 		= __('The status of your Order','wpShop');
	}
	
} else {}
?>

<h1 class="whereAmI <?php echo $pageTitleClass; ?>"><?php echo $pageTitle; ?></h1>
			
		