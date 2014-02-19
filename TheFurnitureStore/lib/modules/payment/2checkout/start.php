<?php
$lc = (WPLANG != 'se_SV' ? substr(WPLANG,3,2) : strtoupper(substr(WPLANG,0,2))); 	
$oid = $OPTION['wps_order_no_prefix'].$order['oid'];
?>
<form class="order_now' method='get' action='https://www.2checkout.com/checkout/spurchase' target='_top'>
	<input type="hidden" name="sid" value="<?php echo trim($OPTION['wps_2checkout_sid']); ?>" />
	<input type="hidden" name="mode" value="2CO"/>
	<input type="hidden" name="li_0_type" value="product" />
	<input type="hidden" name="li_0_name" value="<?php echo $Your_Order.' - '.$date_order; ?>" />
    <input type="hidden" name="li_0_quantity" value="1" />  
	<input type="hidden" name="li_0_price" value="<?php echo $TOTAL_AM; ?>" />
	<input type="hidden" name="li_0_product_id" value="<?php echo $order['who']; ?>" />
	<input type="hidden" name="return_url" value="<?php echo trim($OPTION['wps_2checkout_return_url']); ?>" />
	<input type="hidden" name="merchant_order_id" value="<?php echo $oid; ?>" />
	<input type="hidden" name="currency" value="<?php echo $OPTION['wps_currency_code']; ?>" />
	<input type="hidden" name="first_name" value="<?php echo $order['f_name']; ?>" />
	<input type="hidden" name="last_name" value="<?php echo $order['l_name']; ?>" />
	<input type="hidden" name="street_address" value="<?php echo $order['street']; ?>" />
	<input type="hidden" name="city" value="<?php echo $order['town']; ?>" />
	<input type="hidden" name="state" value="<?php echo $order['state']; ?>" />	
	<input type="hidden" name="zip" value="<?php echo $order['zip']; ?>" />
	<input type="hidden" name="country" value="<?php echo $order['country']; ?>" />
	<input type="hidden" name="email" value="<?php echo $order['email']; ?>" />
	<input type="hidden" name="phone" value="<?php echo $order['telephone']; ?>" />
	<div class="button-right">
		<input type="submit" class="shop-button" name="add" value="Place Order" />
	</div>
</form>	
