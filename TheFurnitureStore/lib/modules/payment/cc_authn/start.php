<?php
// the currency has to be USD - otherwise we stop and issue warning
if($OPTION['wps_currency_code'] == 'USD'){

	// the parameters for the payment 
	$loginID 		= trim(get_option('wps_authn_api_login')); 			// API Login
	$transactionKey = trim(get_option('wps_authn_transaction_key')); 	//Transaction
	$testRequest	= get_option('wps_authn_test_request');				
	$url			= get_option('wps_authn_url');								
	$response_url	= get_option('home') . '/?confirm=2';


	// an invoice is generated using the date and time + added into order table->txn_id
	$invoice					= date(YmdHis);
	
	$table 						= is_dbtable_there('orders');
	$column_value_array 		= array();
	$column_value_array[txn_id] = $invoice;
	$where_conditions 			= array();
	$where_conditions[0]		= "who = '$_SESSION[cust_id]'";		

	db_update($table, $column_value_array, $where_conditions);	
	
	// a sequence number is randomly generated
	$sequence	= rand(1, 1000);
	// a timestamp is generated
	$timeStamp	= time();

	// The following lines generate the SIM fingerprint
	if( phpversion() >= '5.1.2' )
	{	$fingerprint = hash_hmac("md5", $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $TOTAL_AM . "^", $transactionKey); }
	else 
	{ $fingerprint = bin2hex(mhash(MHASH_MD5, $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $TOTAL_AM . "^", $transactionKey)); }


	// HTML form containing necessary SIM post values
	echo "<form class='order_now' method='post' action='$url'>";
	echo "<input type='hidden' name='x_login' value='$loginID' />";
	echo "<input type='hidden' name='x_version' value='3.1' />";
	echo "<input type='hidden' name='x_method' value='CC' />";
	echo "<input type='hidden' name='x_type' value='AUTH_CAPTURE' />";
	echo "<input type='hidden' name='x_amount' value='$TOTAL_AM' />";
	echo "<input type='hidden' name='x_description' value='$Your_Order - $date_order' />";
	echo "<input type='hidden' name='x_invoice_num' value='$invoice' />";
	echo "<input type='hidden' name='x_fp_sequence' value='$sequence' />";
	echo "<input type='hidden' name='x_fp_timestamp' value='$timeStamp' />";
	echo "<input type='hidden' name='x_fp_hash' value='$fingerprint' />";
	echo "<input type='hidden' name='x_test_request' value='$testRequest' />";
	echo "<input type='hidden' name='x_show_form' value='PAYMENT_FORM' />";
	echo "<input type='hidden' name='x_relay_response' value='TRUE' />";
	echo "<input type='hidden' name='x_relay_url' value='$response_url' />";
	
	echo "<input type='hidden' name='x_first_name' value='$order[f_name]' />";
	echo "<input type='hidden' name='x_last_name' value='$order[l_name]' />";								
	echo "<input type='hidden' name='x_address' value='$order[street]' />";
	echo "<input type='hidden' name='x_zip' value='$order[zip]' />";
	echo "<input type='hidden' name='x_city' value='$order[town]' />";
	echo "<input type='hidden' name='x_country' value='$order[country]' />";
	echo "<input type='hidden' name='x_email' value='$order[email]' />";
	if($order['telephone'] > 1){
		echo "<input type='hidden' name='x_phone' value='$order[telephone]' />";
	}	
	echo "
		<div class='button-right'>
			<input type='submit' class='shop-button' name='add' value='Place Order' />
		</div>
	";
	echo "</form>";			
}
else{
	echo "
	<div style='width:100%;color:red;font-weight:800;
	text-align: right; margin-bottom:20px;'>".__('CHANGE CURRENCY OF SHOP TO USD - US DOLLAR!','wpShop')."</div>";
}
?>