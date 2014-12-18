<?php
$lc = (WPLANG != 'se_SV' ? substr(WPLANG,3,2) : strtoupper(substr(WPLANG,0,2))); 	
$who = NWS_encode($_SESSION['cust_id']);	
$TOTAL_AM = str_replace(',', '', $TOTAL_AM);
$oid = $order['oid'];
if ($order['layaway_order'] > 0) { $oid = $order['layaway_order']; }
?>
<form class="submit-order-paypal" method="post" action="https://www.paypal.com/cgi-bin/webscr" target="_top">
	<input type="hidden" name="cmd" value="_xclick" />
	<input type="hidden" name="business" value="<?php echo trim($OPTION['wps_paypal_email']); ?>" />
	<input type="hidden" name="item_name" value="<?php echo $Your_Order.' - '.$date_order; ?>" />
	<input type="hidden" name="amount" value="<?php echo $TOTAL_AM; ?>" />
	<input type="hidden" name="currency_code" value="<?php echo $OPTION['wps_currency_code']; ?>" />
	<input type="hidden" name="quantity" value="1" />
	<input type="hidden" name="custom" value="<?php echo $who; ?>" />	
	<input type="hidden" name="first_name" value="<?php echo $order['f_name']; ?>" />
	<input type="hidden" name="last_name" value="<?php echo $order['l_name']; ?>" />
	<input type="hidden" name="address_street" value="<?php echo $order['street']; ?>" />
	<input type="hidden" name="address_zip" value="<?php echo $order['zip']; ?>" />
	<input type="hidden" name="address_city" value="<?php echo $order['town']; ?>" />
	<input type="hidden" name="address_state" value="<?php echo $order['state']; ?>" />	
	<input type="hidden" name="address_country" value="<?php echo $order['country']; ?>" />
	<input type="hidden" name="no_shipping" value="1" />
	<input type="hidden" name="lc" value="<?php echo $lc; ?>" /> 		
	<input type="hidden" name="bn" value="ButtonFactory.PayPal.001" />
	<input type='hidden' name="return" value="<?php echo $OPTION['wps_confirm_url']; ?>&oid=<?php echo $oid; ?>" />
	<input type="hidden" name="notify_url" value="<?php echo $OPTION['wps_ipn_url']; ?>" />
</form>	
