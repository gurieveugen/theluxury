<?php
$instId 	= get_option('wps_wpay_instId');
$currency	= $OPTION['wps_currency_code'];
$testMode	= get_option('wps_wpay_testmode');

// an invoice is generated using the date and time + added into order table->txn_id
	$invoice					= date(YmdHis);
	
	$table 						= is_dbtable_there('orders');
	$column_value_array 		= array();
	$column_value_array[txn_id] = $invoice;
	$where_conditions 			= array();
	$where_conditions[0]		= "who = '$_SESSION[cust_id]'";		

	db_update($table, $column_value_array, $where_conditions);	

if($testMode == 'true'){				
	$url 			= 'https://select-test.worldpay.com/wcc/purchase';
	$testModeVal	= '100';
	$name			= 'AUTHORISED';
}
else {
	$url 			= 'https://select.worldpay.com/wcc/purchase';
	$testModeVal	= '0';
	$name			= $order[f_name].' '.$order[l_name];
}
$country	= get_countries(3,$order[country]);					
$lang 		= (strlen(WPLANG) > 0 ? substr(WPLANG,0,2) : 'en');


echo "
	<form class='order_now' action='$url' method=POST>
	<input type=hidden name='instId' value='$instId' />
	<input type=hidden name='currency' value='$currency' />
	<input type=hidden name='desc' value='$Your_Order - $date_order' />
	<input type=hidden name='cartId' value='101KT0098' />
	<input type=hidden name='amount' value='$TOTAL_AM' />					
	<input type=hidden name='testMode' value='$testModeVal' />					
	<input type=hidden name='name' value='$name' />					
	<input type=hidden name='address' value='$order[street] $order[town] $order[state]' />					
	<input type=hidden name='postcode' value='$order[zip]' />					
	<input type=hidden name='country' value='$country' />
	<input type=hidden name='tel' value='$order[telephone]' />
	<input type=hidden name='email' value='$order[email]' />
	<input type=hidden name='lang' value='$lang' />
	<input type='hidden' name='MC_invoice' value='$invoice' />
	<div class='button-right'>
		<input type='submit' class='shop-button' name='add' value='Place Order' />
	</div>
	</form>		
";
?>