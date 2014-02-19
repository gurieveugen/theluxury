<?php
$order 		= process_order(3);
$CART 		= show_cart();
//print_r($CART);
$shipping = calculate_shipping($order['d_option'],$CART['total_price'],$CART['total_weight'],$CART['total_item_num'],$order['country']);
//get taxes		
$taxable_amount = (get_option('wps_salestax_onshipping') == 'Yes' ? $CART['total_price'] + $shipping : $CART['total_price']);		
$tax_data 		= provide_tax_data($order,$taxable_amount);		
if(($order['voucher'] != 'non')&&($VOUCHER->code_exist($order['voucher'])))
{
	$order_am	= (float) $CART['total_price'];
	$vdata 		= $VOUCHER->subtract_voucher($order_am,$order);
			
	$TOTAL_AM	= update_order($CART['total_weight'],$shipping,$CART['total_price'],sprintf("%01.2f",$vdata['subtr_am']),$tax_data['amount']);			
}
else { $TOTAL_AM	= update_order($CART['total_weight'],$shipping,$CART['total_price'],NULL,$tax_data['amount']);	}

//echo $TOTAL_AM.' -'.$CART['total_weight'].' -'.$shipping.' -'.$CART['total_price'].' -'.sprintf("%01.2f",$vdata['subtr_am']).' -'.$tax_data['amount'].'<br /> ';
//$TOTAL_AM	= update_order($CART['total_weight'],$shipping,$CART['total_price'],sprintf("%01.2f",$vdata['subtr_am']),$tax_data['amount']);
$mid 		= trim(get_option('wps_authn_mid_login')); // 
$appid      = trim(get_option('wps_authn_appid_key'));
$muid       = 123;
$signature = trim(get_option('wps_authn_secret_key')); 	//Transaction
$testRequest	= get_option('wps_authn_test_request');		
$Your_Order = trim($OPTION['wps_order_no_prefix'].$order['oid']); 
$msignature = md5($appid.$Your_Order.'usd'.$TOTAL_AM.$signature);
$table 						= is_dbtable_there('orders');
$column_value_array 		= array();
$column_value_array['txn_id'] = 'g2ttemp_'.$Your_Order;
$where_conditions 			= array();
$where_conditions[0]		= "who = '$_SESSION[cust_id]'";		
db_update($table, $column_value_array, $where_conditions);	
if($testRequest == 1 || $testRequest == 'true') $test = 'g2ptest';
else $test = 'g2p';
global $current_user;
if($current_user->ID == 0)
{
	$muids = explode('-',$_SESSION['cust_id']);
	$_SESSION['g2p_muid'] = $muid = $muids[0];
}
else $_SESSION['g2p_muid'] =  $muid = $current_user->ID;

//echo $mid.' '.$appid. ' -'.$muid.' -'.$signature.' '.$testRequest.' '.$Your_Order.' '.$msignature.' '.$TOTAL_AM;
//echo "https://g2p.gate2play.com:8843/$test/transact_iframeneworderbyinv?mid=$mid&appid=$appid&muid=$muid&orderid=$Your_Order&ordercurr=usd&orderamt=$TOTAL_AM&msignature=$msignature&c_confirm=29";
//exit;
?>


<iframe src="https://g2p.gate2play.com:8843/<?=$test?>/transact_iframeneworderbyinv?mid=<?=$mid?>&appid=<?=$appid?>&muid=<?=$muid?>&orderid=<?=$Your_Order?>&ordercurr=usd&orderamt=<?=$TOTAL_AM?>&msignature=<?=$msignature?>&c_confirm=29" width="1000" height="462" scrolling="auto" frameborder="0"></iframe>
<?php 
// the currency has to be USD - otherwise we stop and issue warning
/*if($OPTION['wps_currency_code'] == 'USD'){

	// the parameters for the payment 
	$mid 		= trim(get_option('wps_authn_mid_login')); // 
	$appid      = trim(get_option('wps_authn_appid_key'));
	$muid       = trim(get_option('wps_authn_muid_key'));
	$signature = trim(get_option('wps_authn_secret_key')); 	//Transaction
	$testRequest	= get_option('wps_authn_test_request');				
	$url			= get_option('wps_authn_url');	
	//$orderamt    =							
	$response_url	= get_option('home') . '/?confirm=2';
	
	//exit;
	//$msignature = md5($appid . $orderid . $app_secrect_key);
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
		<div class='shopform_btn pay_now'> 
			<input type='image'  name='add' src='"; echo get_bloginfo('stylesheet_directory'); echo"/images/pay_now.png' />
		</div>	
	";
	echo "</form>
	<br/>
	<br/>
	<span class='error order_remark'>". __('Remark','wpShop') . ": <u>$LANG[dont_close_browser_1]</u> 
	$LANG[dont_close_browser_2]</span> 
	";			
}
else{
	echo "
	<div style='width:100%;color:red;font-weight:800;
	text-align: right; margin-bottom:20px;'>".__('CHANGE CURRENCY OF SHOP TO USD - US DOLLAR!','wpShop')."</div>";
}
*/?>