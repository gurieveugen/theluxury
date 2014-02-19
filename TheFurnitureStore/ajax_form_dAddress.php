<?php
include '../../../wp-load.php';
/*
$cart_comp = cart_composition($_SESSION[cust_id]);

// dont show this if digital product + short address form activated
if($OPTION['wps_short_addressform']=='not_active' && $cart_comp!='digi_only'){		
	create_address_form('shipping');
}
*/
create_address_form('shipping');
?>