<?php
$p = explode('wp-content/',__FILE__);
include $p[0].'wp-load.php';

function get_cc_amount(){
	global $OPTION;
	$table	= is_dbtable_there('orders');
	$sql 	= "SELECT * FROM $table WHERE who = '$_SESSION[cust_id]' LIMIT 0,1";
	$row 	= mysql_query($sql);
	$order	= mysql_fetch_assoc($row);
	$amount = $order[amount] .' '.$OPTION['wps_currency_code'];
	
return $amount;
}	
	
function cc_expire_ddown($option='year'){

	if($option == 'year'){
	
			$year 	= (int) date("Y");
			$output	= NULL;
		
			for($i=0,$y=$year; $i<10; $y++,$i++){
				$selected = ($_SESSION['cc_exp_year'] == $y ? "selected='selected'" : NULL);
				$output .= "<option value='$y' $selected >$y</option>" . "\n";
			}
	}
return $output;
}	
	
function createPProString(){
	global $OPTION;
	$table	= is_dbtable_there('orders');
	$sql 	= "SELECT * FROM $table WHERE who = '$_SESSION[cust_id]' LIMIT 0,1";
	$row 	= mysql_query($sql);
	$order	= mysql_fetch_assoc($row);


	$params = array('USER' => get_option('wps_paypal_api_user'),
					'PWD' => get_option('wps_paypal_api_pw'),
					'VERSION' => '3.2',
					'SIGNATURE' => get_option('wps_paypal_api_signature'),
					'METHOD' => 'DoDirectPayment',
					'PAYMENTACTION' => 'Sale',
					'IPADDRESS' => $_SERVER['REMOTE_ADDR'],
					'AMT' => $order[amount],
					'CREDITCARDTYPE' => $_POST['cc_type'],
					'ACCT' => $_POST['cc_number'],
					'EXPDATE' => $_POST['cc_exp_month'] . $_POST['cc_exp_year'],
					'CVV2' => $_POST['cc_cvc'],
					'FIRSTNAME' => substr($_POST['cc_owner'], 0, strpos($_POST['cc_owner'], ' ')),
					'LASTNAME' => substr($_POST['cc_owner'], strpos($_POST['cc_owner'], ' ')+1),
					
					'STREET' => $order['street'],
					'CITY' => $order['town'],
					'STATE' => $order['state'],
					'COUNTRYCODE' => get_countries(3,$order['country']),
					'ZIP' => $order['zip'],
					'EMAIL' => $order['email'],
					'PHONENUM' => '',
					'CURRENCYCODE' => $OPTION['wps_currency_code'],
					'CUSTOM' => $_SESSION['cust_id'],
					'NOTIFYURL' => get_option('wps_ipn_url'),
					'BUTTONSOURCE' => $OPTION['template']);

	$post_string = NULL;

	foreach ($params as $key => $value) {
	  $post_string .= $key . '=' . urlencode(trim($value)) . '&';
	}
				
	$post_string = substr($post_string, 0, -1);
			
	// for easier error management
	$_SESSION['cc_owner'] 		= $_POST['cc_owner'];
	$_SESSION['cc_type'] 		= $_POST['cc_type'];
	$_SESSION['cc_exp_month'] 	= $_POST['cc_exp_month'];
	$_SESSION['cc_exp_year'] 	= $_POST['cc_exp_year'];

return $post_string;
}	

function sendPayPalProTransaction($url, $parameters){
  $server = parse_url($url);

  if (!isset($server['port'])) {
	$server['port'] = ($server['scheme'] == 'https') ? 443 : 80;
  }

  if (!isset($server['path'])) {
	$server['path'] = '/';
  }

  if (isset($server['user']) && isset($server['pass'])) {
	$header[] = 'Authorization: Basic ' . base64_encode($server['user'] . ':' . $server['pass']);
  }

  if(function_exists('curl_init')){
  
	$curl = curl_init($server['scheme'] . '://' . $server['host'] . $server['path'] . (isset($server['query']) ? '?' . $server['query'] : ''));
	curl_setopt($curl, CURLOPT_PORT, $server['port']);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
	curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);

	$result = curl_exec($curl);

	curl_close($curl);
  } else {
	exec(escapeshellarg(MODULE_PAYMENT_PAYPAL_DIRECT_CURL) . ' -d ' . escapeshellarg($parameters) . ' "' . $server['scheme'] . '://' . $server['host'] . $server['path'] . (isset($server['query']) ? '?' . $server['query'] : '') . '" -P ' . $server['port'] . ' -k', $result);
	$result = implode("\n", $result);
  }

  return $result;
}	
	
function paypal_pro_redirect($response){

	$response_array = array();
	parse_str($response, $response_array);				
		
	if("SUCCESS" == strtoupper($response_array["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($response_array["ACK"])){	
	
		// save customer_id + transaction_id in db; for fraud protection of confirmation page
		$table 	= is_dbtable_there('pppro_fcheck');
		$sql 	= "INSERT INTO $table (who,transid) VALUES ('$_SESSION[cust_id]','$response_array[TRANSACTIONID]')";
		mysql_query($sql);
		
		if($OPTION['wps_enforce_ssl'] === 'force_ssl'){
			$url = 'https'.substr(get_option('home'),5).'/index.php?orderNow=81&t='.$response_array['TRANSACTIONID'];		
		}
		else {
			$url = 'http'.substr(get_option('home'),4).'/index.php?orderNow=81&t='.$response_array['TRANSACTIONID'];		
		}
		
		header('Location: ' . $url);
		exit(NULL);
	} else  {
	
		// we collect the error long messages and eridicate duplicates in the message 
		$error_info 	= NULL;
		$error_codes 	= NULL;
		
		for($i=0;$i<10;$i++){
		
			$key1 	= 'L_LONGMESSAGE'.$i;
			$v 		= $response_array[$key];
			
			$key2 	= 'L_ERRORCODE'.$i;
			
			if(array_key_exists($key1,$response_array) && strpos($error_info,$response_array[$key1]) === FALSE){
				$error_info .= $response_array[$key1] . "<br />";
			}
			if(array_key_exists($key2,$response_array)){
				$error_codes .= $response_array[$key2] . "-";
			}
		}
		
		// we log errors in a log file		
		add_to_log($response_array[TIMESTAMP],$error_codes);
		
		
		// different pages according to error number 
		if(in_array(10706,$response_array)){ //wrong postal code
			$error_target = 'https'.substr(get_option('home'),5).'/index.php?orderNow=2';
		}
		else {
			$error_target = 'https'.substr(get_option('home'),5).'/index.php?orderNow=8';
		}
		
		$_SESSION['error_target'] 	= $error_target;
		$_SESSION['error_info'] 	= $error_info;
		$_SESSION['paypal_pro_err']	= 1;

		$rawUrl 	= get_option('home');
		$urlParts 	= explode('https://',$rawUrl);		
		$url 		= 'https://'.$urlParts[1].'/index.php?orderNow=8';	
	
		header('Location: ' . $url);
		exit(NULL);
	}
	exit();					
}
	
function add_to_log($ts,$ecodes){
	
	$table 	= is_dbtable_there('pppro_errors');
	$sql 	= "INSERT INTO $table (timestamp,who,errors) VALUES ('$ts','$_SESSION[cust_id]','$ecodes')";
	mysql_query($sql);
}	
	
	
function paypal_pro_response(){

	$table 	= is_dbtable_there('pppro_fcheck');
	$sql	= "SELECT cid FROM $table WHERE who = '$_SESSION[cust_id]' AND '$_GET[t]'";
	$res 	= mysql_query($sql);
	$num 	= mysql_num_rows($res);

	$feedback 					= array();
	$feedback['status'] 		= ($num == 1 ? 'Completed' : 'Unhealthy');
	
	$INVOICE 					= load_what_is_needed('invoice');	//change.9.10
	$EMAIL 						= load_what_is_needed('email');		//change.9.10
	
	if($feedback['status'] == 'Completed'){
		// Update DB 
		$order = process_payment($feedback,'paypal_pro');
							
		// Manage downloadable products
		// are there digital goods in cart? - if create download links for them 					
		if(digital_in_cart($order[who])){
								
			$DIGITALGOODS 			= load_what_is_needed('digitalgoods');	//change.9.10							
			$order[digital_there]	= 1; 
			$order[dlinks]			= array();
		
			// Create the download links 
			$table 	= is_dbtable_there('shopping_cart');
			$qStr 	= "SELECT item_file FROM $table WHERE who = '$order[who]' AND item_file != 'none'";				
			$res 	= mysql_query($qStr);
			$j		= 0;
			
			while($row = mysql_fetch_assoc($res)){
				$order['dlinks'][$j] = $DIGITALGOODS->create_dlink($row['item_file'],$order['who']);	//change.9.10
				$j++;						
			}
			
			// send the user dlinks also by email
			send_user_dlinks($order[who],'FE');
		}
	
	
		// Provide Invoice in PDF/Html format  
		if(pdf_usable_language()){
			$INVOICE->make_pdf($order);		//change.9.10
			$order[pdf_bill] = NWS_encode($OPTION[wps_invoice_prefix].'_' . $order[tracking_id] . '.pdf');
		}
		else{
			$INVOICE->make_html($order);	//change.9.10
			$order[pdf_bill] = NWS_encode($OPTION[wps_invoice_prefix].'_' . $order[tracking_id] . '.html');
		}
				
	
	$order[status] 		= $feedback['status'];  
	$order[itemname]	= 'Order' . ' - ' . date("F j, Y, g:i a",$order['order_time']); 	
	$feedback[itemname]	= $order[itemname];
	
				
	// Email to customer 
	$EMAIL->email_confirmation($order,$feedback);		//change.9.10
	
	
	// Email to shop owner
	$search		= array("[##header##]","[##f_name##]","[##l_name##]","[##amount##]","[##currency##]","[##url##]");
	//change.9.10
	$replace 	= array($EMAIL->email_header(),$order['f_name'],$order['l_name'],$order['amount'],$OPTION['wps_currency_code'],url_be());
	$EMAIL->email_owner_order_notification($feedback,$search,$replace);
	//change.9.10
	}
	
	if($feedback['status'] == 'Unhealthy'){
		$url = str_replace('https://','http://',get_option('home'));
		echo "<div class='failure'><p>";
		echo __('A payment procedure error has occurred. Please contact the shop owner. Code: 101','wpShop');
		echo "<br/>";
		echo __('You will be redirected to the start page in about 20 seconds','wpShop');
		echo "</p></div>";
		echo "<meta http-equiv='refresh' content='20; URL=$url' />";
		exit(NULL);		
	}
return $order;
}
?>