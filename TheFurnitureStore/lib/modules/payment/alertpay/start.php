<?php
$lc = (WPLANG != 'se_SV' ? substr(WPLANG,3,2) : strtoupper(substr(WPLANG,0,2))); 	
if($OPTION['wps_alertpay_test_request'] != true) $action = "https://www.alertpay.com/PayProcess.aspx";
else $action = "https://sandbox.alertpay.com/sandbox/checkout";
?>
<form class="order_now" method="post" action="<?php echo $action; ?>" target="_top">
	<input type="hidden" name="ap_merchant" value="<?php echo trim($OPTION['wps_alertpay_api_login']); ?>" />
	<input type="hidden" name="ap_purchasetype" value="item-goods"/>
	<input type="hidden" name="ap_itemname" value="<?php echo $Your_Order.' - '.$date_order; ?>" />
	<input type="hidden" name="ap_itemcode" value="<?php echo $order['who']; ?>" />
    <input type="hidden" name="ap_currency" value="<?php echo $OPTION['wps_currency_code']; ?>" />
	<input type="hidden" name="ap_quantity" value="1" />
	<input type="hidden" name="ap_amount" value="<?php echo $TOTAL_AM; ?>" />
	<input type="hidden" name="ap_fname" value="<?php echo $order['f_name']; ?>" />
	<input type="hidden" name="ap_lname" value="<?php echo $order['l_name']; ?>" />
	<input type="hidden" name="ap_contactemail" value="<?php echo $order['email']; ?>" />
	<input type="hidden" name="ap_contactphone" value="<?php echo $order['telephone']; ?>" />
	<input type="hidden" name="ap_addressline1" value="<?php echo $order['street']; ?>" />				
	<input type="hidden" name="ap_zippostalcode" value="<?php echo $order['zip']; ?>" />
	<input type="hidden" name="ap_city" value="<?php echo $order['town']; ?>" />
	<input type="hidden" name="ap_stateprovince" value="<?php echo $order['state']; ?>" />	
	<input type="hidden" name="ap_country" value="<?php echo $order['country']; ?>" />
	<input type="hidden" name="ap_returnurl" value="<?php echo trim($OPTION['wps_alertpay_confirm_url']); ?>" /> 
	<input type="hidden" name="ap_cancelurl" value="<?php echo trim($OPTION['wps_alertpay_api_cancelurl']); ?>" /> 
	<div class="button-right">
		<input type="submit" class="shop-button" name="add" value="Place Order" />
	</div>
</form>	
