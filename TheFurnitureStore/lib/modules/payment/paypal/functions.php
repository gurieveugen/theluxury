<?php
$p = explode('wp-content',__FILE__);
include $p[0].'wp-load.php';

$paypal_url = 'www.paypal.com';
//$paypal_url = 'www.sandbox.paypal.com';

function pdt_response(){
	global $OPTION, $paypal_url, $wpdb;
	// read the post from PayPal system and add 'cmd'
	$req 		= 'cmd=_notify-synch';
	$tx_token 	= $_GET['tx'];
	$auth_token = trim(get_option('wps_paypal_pdttoken'));
	$req 		.= "&tx=$tx_token&at=$auth_token";

	// post back to PayPal system to validate
	$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$header .= "Host: ".$paypal_url."\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
	$fp 	= fsockopen ($paypal_url, 80, $errno, $errstr, 30);
	// If possible, securely post back to paypal using HTTPS
	// Your PHP server will need to be SSL enabled
	// $fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

	if (!$fp) {
		// HTTP ERROR
	} else {
		fputs ($fp, $header . $req);
		// read the body data 
		$res = '';
		$headerdone = false;
		while (!feof($fp)) {
			$line = fgets ($fp, 1024);
			if (strcmp($line, "\r\n") == 0) {
				// read the header
				$headerdone = true;
			}
			elseif ($headerdone)
			{
				// header has been read. now read the contents
				$res .= $line;
			}
		}

		// parse the data
		$lines 		= explode("\n", $res);
		$keyarray 	= array();
		if (strcmp ($lines[0], "SUCCESS") == 0) {
			for ($i=1; $i<count($lines);$i++){
				list($key,$val) = explode("=", $lines[$i]);
				$keyarray[urldecode($key)] = urldecode($val);
			}

			//echo 'keyarray :'; print_r($keyarray); echo '<br />';
			$PDT_DATA 	= array();
			$PDT_DATA['firstname'] 		= $keyarray['first_name'];
			$PDT_DATA['lastname'] 		= $keyarray['last_name'];
			$PDT_DATA['itemname'] 		= $keyarray['item_name'];
			$PDT_DATA['amount'] 		= $keyarray['mc_gross'];
			$PDT_DATA['currency'] 		= $keyarray['mc_currency'];	
			
			$PDT_DATA['payment_status']	= $keyarray['payment_status']; 
			$PDT_DATA['payment_type'] 	= $keyarray['payment_type'];
			$PDT_DATA['pending_reason'] = $keyarray['pending_reason'];
			$PDT_DATA['who'] 			= NWS_decode($keyarray['custom']);

			$ERROR 		= 0;
			$COMPLETED 	= 0;
			
			// check the payment_status is Completed	
			if($keyarray['payment_status'] == 'Completed')
			{
				$PDT_DATA['payment_status'] = 'Completed';
				$COMPLETED					= 1;
			}
						
			if($keyarray['payment_status'] == 'Pending')
			{
				$PDT_DATA['payment_status'] = 'Pending';		
				$PENDING					= 1;
			}

			// check that receiver_email is your Primary PayPal email
			$paypal_email = $OPTION['wps_paypal_email'];
			if(strcasecmp($paypal_email,$keyarray['business']) == 0){
			}
			else {
				$ERROR = 1;
				$PDT_DATA['error_code'] 	= 'PP02';
				$PDT_DATA['error_message']	= __('Error PP02: Receiver-email and Paypal-email are not identical','wpShop')."<br/> Receiver-email : $keyarray[business] and Paypal-email : $paypal_email ";
			}
						
			// check that payment_amount/payment_currency are correct
			$paypal_amount_ok = paypal_amount_correct($keyarray['mc_gross'],$PDT_DATA[who]);
			if($paypal_amount_ok){
			}
			else {
				$ERROR = 1;
				$PDT_DATA['error_code'] 	= 'PP03';
				$PDT_DATA['error_message']	= __('Error PP03: Paypal amount is incorrect.','wpShop')."<br/>";
			}
			
			$shop_currency = $OPTION['wps_currency_code'];
			if($shop_currency == $keyarray['mc_currency']){		
			}
			else {
				$ERROR = 1;
				$PDT_DATA['error_code'] 	= 'PP04';
				$PDT_DATA['error_message']	= __('Error PP04: Paypal currency and shop currency are not identical.','wpShop')."<br>";
			}
			$PDT_DATA['error'] = $ERROR;			
						
			// Process paypal transaction 
			// 1. No errors & status: completed 
			if(($ERROR == 0) && ($COMPLETED == 1)){
				$table = is_dbtable_there('orders');
				$order_data = $wpdb->get_row(sprintf("SELECT * FROM %s WHERE who = '%s'", $table, $PDT_DATA['who']));

				$PDT_DATA['d_option'] = $order->d_option;
				$parts = explode("-",$PDT_DATA['who']);
				$PDT_DATA['tracking_id'] = $parts[0];

				if(pdf_usable_language()){ 	
					$PDT_DATA['pdf_bill'] = NWS_encode($OPTION['wps_invoice_prefix'].'_' . $PDT_DATA['tracking_id'] . '.pdf');	
				}
				else{
					$PDT_DATA['pdf_bill'] = NWS_encode($OPTION['wps_invoice_prefix'].'_' . $PDT_DATA['tracking_id'] . '.html');	
				}

				// are there digital goods in cart? - if create download links for them 					
				if(digital_in_cart($PDT_DATA['who'])){
						
					$DIGITALGOODS 				= load_what_is_needed('digitalgoods');	//change.9.10								
					$PDT_DATA['digital_there']	= 1; 
					$PDT_DATA['dlinks']			= array();
				
					// Create the download links 
					$table 	= is_dbtable_there('shopping_cart');
					$qStr 	= "SELECT item_file FROM $table WHERE who = '$PDT_DATA[who]' AND item_file != 'none'";				
					$res 	= mysql_query($qStr);
					$j		= 0;
					
					while($row = mysql_fetch_assoc($res)){
						$PDT_DATA['dlinks'][$j] = $DIGITALGOODS->create_dlink($row['item_file'],$PDT_DATA['who']);	//change.9.10
						$j++;						
					}
					
					// send the user dlinks also by email
					send_user_dlinks($PDT_DATA['who'],'FE');
				}
			}
			
			// Status 2: Pending (is done taken over by IPN response)
			if(($ERROR == 0) && ($PENDING == 1)){}	
		}
		else if(strcmp ($lines[0], "FAIL") == 0) {
			// log for manual investigation
			mail($OPTION['wps_shop_email'],'PDT error',__('There was an PDT failure. This mail was sent from function pdt_response. Please check if you entered the PDT-Identity Token for PayPal correctly on your backend.','wpShop'));
		}

	}
	fclose ($fp);	
	
	// delete  old custom_id - in order to avoid mixed up orders 
	unset($_SESSION[cust_id]);
	
	return $PDT_DATA;
}



function ipn_response(){
	global $OPTION, $paypal_url;

	$INVOICE 	= load_what_is_needed('invoice');	//change.9.10
	$EMAIL 		= load_what_is_needed('email');		//change.9.10
	
	// read the post from PayPal system and add 'cmd'
	$req 		= 'cmd=_notify-validate';
	$keyarray	= array();

	foreach($_POST as $key => $value){
		$value 			= urlencode(stripslashes($value));
		$req 			.= "&$key=$value";
	}	
	
	// post back to PayPal system to validate	
	$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$header .= "Host: ".$paypal_url."\r\n";
	//$header .= "Host: www.sandbox.paypal.com\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
	$fp 	= fsockopen ('ssl://'.$paypal_url, 443, $errno, $errstr, 30);	
	if($fp === FALSE) { //in seldom cases fsockopen can't handle SSL b'c host hasn't activated it, then it will fall back to port 80
		$fp 	= fsockopen ($paypal_url,80,$errno,$errstr,30);	 
	}

	if(!$fp){
		// HTTP ERROR
		mail($OPTION['wps_shop_email'],'HTTP error',__('In connection with an IPN call a HTTP error has ocurred.','wpShop'));
	}else{
		fputs ($fp, $header . $req);
		while (!feof($fp))
		{
			$res = fgets ($fp, 1024);
			if(strcmp($res,"VERIFIED") == 0){

				// assign posted variables to local variables
				$IPN_DATA 	= array();
				$keyarray	= array();
				
				foreach($_POST as $key => $value){
					if($key == 'business'){			
						$value = urldecode($value);
					}			
					if($key == 'item_name'){			
						$value = urldecode($value);
					}			
					$keyarray[$key] = $value;
				}
				$who = NWS_decode($keyarray['custom']);
				$keyarray['who'] = $who;

				$IPN_DATA['firstname'] 		= $keyarray['first_name'];
				$IPN_DATA['lastname'] 		= $keyarray['last_name'];
				$IPN_DATA['itemname'] 		= $keyarray['item_name'];
				$IPN_DATA['amount'] 		= $keyarray['mc_gross'];
				$IPN_DATA['currency'] 		= $keyarray['mc_currency'];	
				$IPN_DATA['payment_type'] 	= $keyarray['payment_type'];
				$IPN_DATA['who'] 			= $who;
				$IPN_DATA['pay_m']			= 'paypal_ipn';
				
				$ERROR 		= 0;
				$COMPLETED 	= 0;	

				
				// check the payment_status is Completed	
				if($keyarray['payment_status'] == 'Completed')
				{
					$IPN_DATA['payment_status'] 	= 'Completed';
					$COMPLETED						= 1;
				}			
	
	
				if($keyarray['payment_status'] == 'Pending')
				{
					$IPN_DATA['payment_status'] 	= 'Pending';	
					$IPN_DATA['pending_reason'] 	= $keyarray['pending_reason'];	
					$PENDING						= 1;
				}	
				# see also https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_html_IPNandPDTVariables
		
		
				//start of error checking//
				// check that txn_id has not been previously processed
				$txn_id_there = check_txn_id($keyarray['txn_id']);
				if($txn_id_there == FALSE){
					#echo "<br><br> 1. txn_id not yet there <br>";
				}
				else {
					$ERROR = $ERROR + 1;
					#echo "<br><br> 1. txn_id used already <br>";
				}	
			
			
				// check that receiver_email is your Primary PayPal email
				$paypal_email = $OPTION['wps_paypal_email'];
				/*if(strcasecmp($paypal_email,$keyarray['business']) == 0){
					#echo "2. Receiver-email and Paypal-email identical<br>";
				}
				else {
					$ERROR = $ERROR + 10;
					#echo "2. Receiver-email and Paypal-email are not identical<br>";
				}*/

			
				// check that payment_amount/payment_currency are correct
				$paypal_amount_ok = paypal_amount_correct($keyarray['mc_gross'],$IPN_DATA['who']);
				if($paypal_amount_ok){
					#echo "3. Paypal amount is ok.<br>";
				}
				else {
					$ERROR = $ERROR + 100;
					#echo "3. Paypal amount is not ok.<br>";
				}
			
				$shop_currency = $OPTION['wps_currency_code'];
				if($shop_currency == $keyarray['mc_currency']){		
					#echo "4. Paypal currency and shop currency identical.<br>";
				}
				else {
					$ERROR = $ERROR + 1000;
					#echo "4. Paypal currency and shop currency are not identical.<br>";
				}			
	

				// we log complete PayPal data + error 
				$_POST['error_feedback'] = "error: ".$ERROR.' - '."completed: ".$COMPLETED;
				log_payment_data($_POST,$IPN_DATA['who']);

				// Process paypal transaction 
				if($ERROR == 0)  // If No errors
				{
					if($COMPLETED == 1 || $PENDING == 1)
					{
						//1.1 we change the level of order
						//$order = process_paypal_payment($keyarray['txn_id'],$IPN_DATA['payment_status'],$IPN_DATA['who']);
						$order = process_payment($keyarray,'paypal');
							
						//1.2 we create a pdf for bill + save name of pdf in IPN-DATA
						// in case of other languages than english,german,french,italian we produce a html instead
						if(pdf_usable_language()){
							$INVOICE->make_pdf($order);		//change.9.10
							$IPN_DATA[pdf_bill] = $OPTION['wps_invoice_prefix'].'_' . $order['tracking_id'] . '.pdf';
						}
						else{
							$INVOICE->make_html($order);	//change.9.10
							$IPN_DATA[pdf_bill] = $OPTION['wps_invoice_prefix'].'_' . $order['tracking_id'] . '.html';
						}
					}

					// 1. status: Completed 
					if($COMPLETED == 1)
					{				
						//1.3 Email to customer as confirmation in HTML format
						$EMAIL->email_confirmation($order,$IPN_DATA);	//change.9.10

						//1.4 Email to shop owner
						$search		= array("[##header##]","[##f_name##]","[##l_name##]","[##amount##]","[##currency##]","[##url##]");
						//change.9.10
						$replace 	= array($EMAIL->email_header(),$order['f_name'],$order['l_name'],$order['amount'],$OPTION['wps_currency_code'],url_be());
						$EMAIL->email_owner_order_notification($IPN_DATA,$search,$replace);		
						//\change.9.10
						

						// IF order payment status is currently pending (8) then update db + sent notification email to merchant
						$order_level = what_order_level(2,$keyarray['txn_id'],$IPN_DATA['who']);	

						if($order_level == '8'){

							// DB 
							$table 					= is_dbtable_there('orders');
							$column_value_array 	= array();
							$where_conditions 		= array();

							$column_value_array[level] 			= '4';														
							$where_conditions[0]				= "txn_id = '$keyarray[txn_id]'";				
							
							db_update($table, $column_value_array, $where_conditions);					
									

							// Email to shop owner
							$search		= array("[##header##]","[##$item_name##]","[##$payment_status##]","[##$payment_amount##]","[##$payment_currency##]",
											"[##$txn_id##]","[##$receiver_email##]","[##$payer_email##]","[##url##]");	
							//change.9.10
							$replace 	= array($EMAIL->email_header(),$keyarray['item_name'],$keyarray['payment_status'],$keyarray['mc_gross'],
											$keyarray['mc_currency'],$keyarray['txn_id'],$keyarray['business'],$keyarray['payer_email'],url_be());
							
							$EMAIL->email_owner_pending_payment_notification($search,$replace,'completed');				
							//\change.9.10
						}
					}

					// status: Pending 
					if($PENDING == 1)
					{
						// Email to shop owner 
						$search		= array("[##header##]","[##f_name##]","[##l_name##]","[##amount##]","[##shop_currency##]","[##url##]");	
						//change.9.10
						$replace 	= array($EMAIL->email_header(),$order['f_name'],$order['l_name'],$order['amount'],$shop_currency,url_be());
							
						$EMAIL->email_owner_pending_payment_notification($search,$replace,'new');
						//\change.9.10
					}
				}					
					
				// track IPN activity in db
				$table 			= is_dbtable_there('ipn');
				$column_array	= array();
				$value_array	= array();           
					
				$column_array[0]	= 'ipn_id';			$value_array[0]	= '';
				$column_array[1]	= 'txn_id';			$value_array[1]	= $keyarray['txn_id'];
				$column_array[2]	= 'who';			$value_array[2]	= $IPN_DATA['who'];
				$column_array[3]	= 'status';			$value_array[3]	= $keyarray['payment_status'];
				$column_array[4]	= 'tstamp';			$value_array[4]	= date("Y-m-d") .'#'. date("H:i:s")."#". time();				
				
				db_insert($table,$column_array,$value_array);					
								
			}
			else if (strcmp ($res, "INVALID") == 0) {
				// log for manual investigation
				mail($OPTION['wps_shop_email'],'IPN INVALID',__('A invalid IPN response has occured.','wpShop'));
			}
		}
		fclose ($fp);
	}
	
	return $IPN_DATA;
}



function check_txn_id($txn_id){

	$table = is_dbtable_there('orders');
	
	$qStr 	= "SELECT * FROM $table WHERE txn_id = '$txn_id'";
	$res	= mysql_query($qStr);
	$num 	= mysql_num_rows($res);
	
	if($num < 1){
		$feedback = FALSE;
	}
	else{
		$feedback = TRUE;
	}

	return $feedback;
}



function paypal_amount_correct($paypal_amount,$cust_id){
	
	$table = is_dbtable_there('orders');
	
	$qStr 	= "SELECT * FROM $table WHERE who = '$cust_id'";
	$res	= mysql_query($qStr);
	$row 	= mysql_fetch_assoc($res);
	$order_amount = format_price($row['amount']);
	$order_amount = str_replace(',', '', $order_amount);

	if((int)$order_amount == (int)$paypal_amount) {
		return true;
	}
	
	return false;
}



function process_paypal_payment($txn_id,$payment_status,$cust_id,$pending_r='na',$option='paypal'){
	// update orders table according to payment status
	$table 					= is_dbtable_there('orders');
	$column_value_array 	= array();
	$where_conditions 		= array();
	$parts 					= explode("-",$cust_id);	
			
	if($payment_status == 'Completed'){
							
		$column_value_array['txn_id'] 		= $txn_id;
		$column_value_array['tracking_id'] 	= $parts[0];
		$column_value_array['order_time'] 	= time();
								
		$cart_comp = cart_composition($cust_id);
		if($cart_comp == 'digi_only'){
			$column_value_array['level'] 	= '7';						
		}
		else{						
			$column_value_array['level']	= '4';
		}
	}
	elseif($payment_status == 'Pending'){
		$column_value_array['txn_id'] 		= $txn_id;
		$column_value_array['tracking_id'] 	= $parts[0];
		$column_value_array['order_time'] 	= time();
		$column_value_array['pending_r']	= $pending_r; 
		$column_value_array['level']		= '8';					
	}
	elseif($payment_status == 'free'){
		$column_value_array['txn_id'] 		= $txn_id;
		$column_value_array['tracking_id'] 	= $parts[0];
		$column_value_array['order_time'] 	= time();
		$column_value_array['level'] 		= '7';							
	}

	$where_conditions[0]				= "who = '$cust_id'";
																
	db_update($table, $column_value_array, $where_conditions);	
	
	$qStr 	= "SELECT * FROM $table WHERE who = '$cust_id' LIMIT 1";
	$res 	= mysql_query($qStr);
	$order 	= mysql_fetch_assoc($res);
	
						
	// voucher management 
	$qStr 	= "SELECT voucher FROM $table WHERE who = '$cust_id' LIMIT 0,1"; 
	$res 	= mysql_query($qStr);
	$row	= mysql_fetch_assoc($res);
	
	if($row['voucher'] != 'non'){  //..then update vouchers table 
	
		$table2	= is_dbtable_there('vouchers');	
		$qStr 	= "SELECT duration FROM $table2 WHERE vcode = '$row[voucher]' LIMIT 0,1"; 
		$res2 	= mysql_query($qStr);
		$row2	= mysql_fetch_assoc($res2);
		
		$column_value_array 	= array();
		$where_conditions 		= array();						
		
		if($row2[duration] == '1time'){
		$column_value_array['used'] 		= '1';
		}
		$column_value_array['time_used'] 	= date("F j, Y");
		$column_value_array['who'] 			= $_SESSION['cust_id'];
		$where_conditions[0]				= "vcode = '$row[voucher]'";
																			
		db_update($table2, $column_value_array, $where_conditions);	
	}				
	return $order;
}




function log_payment_data($data,$who){

	ksort($data);

	$log_data	= NULL; 
	foreach($data as $k => $v){
		$log_data .= $k.' : '.$v."<br />";
	}
	$log_who = $who;			


		// what cart items? 
		$cart_items = NULL;
		$table		= is_dbtable_there('shopping_cart');
		$qStr 		= "SELECT * FROM $table WHERE who = '$who'";
		$res 		= mysql_query($qStr);
		
		while($row = mysql_fetch_assoc($res)){
			$cart_items .= $row[item_amount].'x '.$row[item_id].' '.$row[item_name].' '.$row[item_price];
			$cart_items .= ' || ';
		}
	
		// save data in logging table 
		$table 			= is_dbtable_there('log_payment');
		$column_array	= array();
		$value_array	= array();     
		
		$column_array[0]	= 'log_id';			$value_array[0]	= '';
		$column_array[1]	= 'payment_mod';	$value_array[1]	= 'paypal';
		$column_array[2]	= 'log_data';		$value_array[2]	= $log_data;
		$column_array[3]	= 'cart_items';		$value_array[3]	= $cart_items;
		$column_array[4]	= 'who';			$value_array[4]	= $log_who;
		$column_array[5]	= 'tstamp';			$value_array[5]	= date("Y-m-d") .'#'. date("H:i:s")."#". time();	

		db_insert($table,$column_array,$value_array);				
}


?>