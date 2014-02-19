<?php
if($OPTION['wps_enforce_ssl'] == 'force_ssl'){
	$parts 	= explode("://",get_option('home'));
	$url 	= 'https://'.$parts[1];
}
else {
	$url 	= get_option('home');
}
 
echo "
<form class='order_now' method='post' action='{$url}/index.php?orderNow=8' target='_top'>
	<input type='hidden' name='item_name' value='$Your_Order - $date_order' />
	<input type='hidden' name='amount' value='$TOTAL_AM' />
	<input type='hidden' name='currency_code' value='";echo $OPTION['wps_currency_code']; echo "' />
	<div class='button-right'>
		<input type='submit' class='shop-button' name='add' value='Place Order' />
	</div>
</form>	
";
?>