<?php
include '../../../wp-load.php';
	
	$LANG[email]	= __('Email','wpShop');
/*
	$cart_comp = cart_composition($_SESSION[cust_id]);

// dont show this if digital product + short address form activated
if($OPTION['wps_short_addressform']=='not_active' && $cart_comp!='digi_only'){
	create_address_form();
}	
*/
	create_address_form();

if($_GET['option'] != 'billingAddressFE'){
	echo "<label for='email'>$LANG[email]:</label><input  id='email'type='text' name='email' value='$_POST[email]' maxlength='255' />";
}
//if($OPTION['wps_checkout_showtel'] == 'Yes'){
	echo "<label for='telephone'>".__('Telephone','wpShop').":</label><input id='telephone' type='text' name='telephone' value='$_POST[telephone]' maxlength='255' />";
//}
?>