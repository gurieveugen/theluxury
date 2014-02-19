<?php
$p = explode('wp-content\\',__FILE__);
include_once $p[0].'wp-load.php';
function authn_response(){
	global $OPTION;
	$INVOICE 	= load_what_is_needed('invoice');	//change.9.10
	$EMAIL 		= load_what_is_needed('email');		//change.9.10
	$feedback 	= array();
	$feedback['status'] 				= (int) $_REQUEST['status'];
	$feedback['itemname']				= $_REQUEST['orderid'];
	$feedback['orderid']				= $_REQUEST['orderid'];
	$feedback['temp_txn_id']			= 'g2ttemp_'.$_REQUEST['orderid'];
	$feedback['AMOUNT']					= $_REQUEST['aamount'];
	$feedback['CURRENCY']				= 'USD';
	$feedback['trans_id']				= strtolower($_REQUEST['tranid']);
	$feedback['pay_m']					= 'g2p_authn';
	//lets log the raw data from Authnet 
	log_authn_payment_data($_REQUEST,$feedback['trans_id']);
	###############################################################
	#	depending on status - different reaction follows
	#
	#	1 = Success
	#	2 = Error
	###############################################################
	switch($feedback['status'])
	{
		case 1:
			// Update DB 
			$order = process_payment($feedback,'g2p_authn');
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
			$parts 			= explode("-",$order['who']);
			$invoice_file 	= $parts[0]; 
			if(pdf_usable_language()){
				$INVOICE->make_pdf($order);		//change.9.10
				$order[pdf_bill] = NWS_encode($OPTION[wps_invoice_prefix].'_' . $invoice_file . '.pdf');	
			}
			else{
				$INVOICE->make_html($order);	//change.9.10
				$order[pdf_bill] = NWS_encode($OPTION[wps_invoice_prefix].'_' . $invoice_file . '.html');
			}
			// Email to customer 
			$EMAIL->email_confirmation($order,$feedback);	//change.9.10
			// Email to shop owner
			$feedback['f_name']					= $order['f_name'];
			$feedback['l_name']					= $order['l_name'];
			$search		= array("[##header##]","[##f_name##]","[##l_name##]","[##amount##]","[##currency##]","[##url##]");
			$replace 	= array($EMAIL->email_header(),$order['f_name'],$order['l_name'],$order['amount'],get_option('wps_currency_code'),url_be()); //change.9.10
			$EMAIL->email_owner_order_notification($feedback,$search,$replace);		//change.9.10
			
			$order[itemname] = $feedback['itemname'];
			//unset($_SESSION['cust_id']);
		break;
		default:
				echo "<b>There has been an error processing this transaction.</b>";	
		break;
	}		
	
	
	// delete  old custom_id - in order to avoid mixed up orders 
	//unset($_SESSION[cust_id]);
	//$_SESSION = array();
			
	$order['status']					= $feedback['status']; // temp. 
	$order['itemname']					= $feedback['itemname'];
	$order['x_response_reason_text'] 	= $feedback['x_response_reason_text'];	
	
return $order;
}





function log_authn_payment_data($data,$who){

	ksort($data);

	$log_data	= NULL; 
	foreach($data as $k => $v){
		$log_data .= $k.' : '.$v."<br />";
	}
			
		//get the who value
		$table	= is_dbtable_there('orders');
		$qStr 	= "SELECT who FROM $table WHERE txn_id = '$who' LIMIT 0,1";
		$res 	= mysql_query($qStr);
		$row 	= mysql_fetch_assoc($res);
		$log_who= $row['who'];
	

		// what cart items? 
		$cart_items = NULL;
		$table		= is_dbtable_there('shopping_cart');
		$qStr 		= "SELECT * FROM $table WHERE who = '$log_who'";
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
		$column_array[1]	= 'payment_mod';	$value_array[1]	= 'authn';
		$column_array[2]	= 'log_data';		$value_array[2]	= $log_data;
		$column_array[3]	= 'cart_items';		$value_array[3]	= $cart_items;
		$column_array[4]	= 'who';			$value_array[4]	= $log_who;
		$column_array[5]	= 'tstamp';			$value_array[5]	= date("Y-m-d") .'#'. date("H:i:s")."#". time();	

		db_insert($table,$column_array,$value_array);				
}
function direct_response($result_data){
	//print_r($feedback);
	global $OPTION;
	$INVOICE 	= load_what_is_needed('invoice');	//change.9.10
	$EMAIL 		= load_what_is_needed('email');		//change.9.10
	$feedback 	= array();
	$feedback['status'] 				= $result_data['transtatus'];
	if(trim($feedback['status']) == "Transaction completed") { $feedback['status'] = 1; } // echo 'ok';
	else if($feedback['status'] != '1') { $feedback['status'] = 0; } //echo $feedback['status'].'not ok';
	$feedback['itemname']				= $result_data['paymenttypedesc'];
	$feedback['orderid']				= $result_data['merchantorderid'];
	$feedback['temp_txn_id']			= 'g2ttemp_'.$result_data['merchantorderid'];
	$feedback['AMOUNT']					= $result_data['netreceived'];
	$feedback['CURRENCY']				= 'USD';
	$feedback['trans_id']				= strtolower($result_data['tranid']);
	$feedback['pay_m']					= 'g2p_authn';
	//lets log the raw data from Authnet 
	log_authn_payment_data($feedback,$feedback['trans_id']);
	###############################################################
	#	depending on status - different reaction follows
	#
	#	1 = Success
	#	2 = Error
	###############################################################
	switch($feedback['status'])
	{
		case 1:
			// Update DB 
			$order = process_payment($feedback,'g2p_authn');
			//print_r($order);
			//exit;
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
			$parts 			= explode("-",$order['who']);
			$invoice_file 	= $parts[0]; 
			if(pdf_usable_language()){
				$INVOICE->make_pdf($order);		//change.9.10
				$order[pdf_bill] = NWS_encode($OPTION[wps_invoice_prefix].'_' . $invoice_file . '.pdf');	
			}
			else{
				$INVOICE->make_html($order);	//change.9.10
				$order[pdf_bill] = NWS_encode($OPTION[wps_invoice_prefix].'_' . $invoice_file . '.html');
			}
			// Email to customer 
			$EMAIL->email_confirmation($order,$feedback);	//change.9.10
			// Email to shop owner
			$feedback['f_name']					= $order['f_name'];
			$feedback['l_name']					= $order['l_name'];
			$search		= array("[##header##]","[##f_name##]","[##l_name##]","[##amount##]","[##currency##]","[##url##]");
			$replace 	= array($EMAIL->email_header(),$order['f_name'],$order['l_name'],$order['amount'],get_option('wps_currency_code'),url_be()); //change.9.10
			$EMAIL->email_owner_order_notification($feedback,$search,$replace);		//change.9.10
			
			$order[itemname] = $feedback['itemname'];
			unset($_SESSION['cust_id']);
		break;
		
		default:
				echo "<b>There has been an error processing this transaction.</b>";	
		break;
	}		
	
	
	// delete  old custom_id - in order to avoid mixed up orders 
	
	//$_SESSION = array();
			
	$order['status']					= $feedback['status']; // temp. 
	$order['itemname']					= $feedback['itemname'];
	$order['x_response_reason_text'] 	= $feedback['x_response_reason_text'];	
	
return $order;
}
function process_result()
{
	$CART 		= show_cart();
	$mid 		= trim(get_option('wps_authn_mid_login')); // 
	$appid      = trim(get_option('wps_authn_appid_key'));
	$muid       = 123;
	$signature = trim(get_option('wps_authn_secret_key')); 	//Transaction
	$testRequest	= get_option('wps_authn_test_request');		
	$Your_Order = trim($_REQUEST['orderid']); 
	//echo ' Fetching report ..';
	if($testRequest == 1 || $testRequest == 'true') $test = 'g2ptest';
	else $test = 'g2p';
	if(isset($_SESSION['g2p_muid']) && $_SESSION['g2p_muid'] != '')	$muid = $_SESSION['g2p_muid'];
	else 
	{
		global $current_user;
		if($current_user->ID == 0)
		{
			$muids = explode('-',$_SESSION['cust_id']);
			$muid = $muids[0];
		}
		else $muid = $current_user->ID;
	}
	$msignature = md5($appid.$muid.$Your_Order.$signature);
	//echo $mid.' '.$appid. ' -'.$muid.' -'.$signature.' '.$testRequest.' '.$Your_Order.' '.$msignature.' '.$TOTAL_AM;
	//exit;
	//assert_options(ASSERT_ACTIVE, 1);
	//assert_options(ASSERT_BAIL, 1);
	//assert_options(ASSERT_QUIET_EVAL, 1);
	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);
	//ini_set('allow_url_fopen', 1);
	//ini_set('allow_url_fopen ','ON');
	//echo ini_get('allow_url_fopen').' ok';
	//$ch1 = curl_init("https://g2p.gate2play.com:8843/$test/call_returntran?mid=$mid&appid=$appid&muid=$muid&orderid=$Your_Order&msignature=$msignature");    // initialize curl handle
    //curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, 1);
    //$data = curl_exec($ch1);
    //print($data);
	//curl_close($ch1); 
	$url = "https://g2p.gate2play.com:8843/$test/call_returntran?mid=$mid&appid=$appid&muid=$muid&orderid=$Your_Order&msignature=$msignature";
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_HEADER, FALSE); 
	curl_setopt($ch, CURLOPT_NOBODY, FALSE); 
	curl_setopt($ch, CURLOPT_TIMEOUT,60); // TIME OUT is 5 seconds
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
	$response = curl_exec($ch);
	if($response === false)
	{
    	echo 'Curl error: ' . curl_error($ch);
	} 
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
	//print_r($httpCode);
	curl_close($ch); 
	$result_xml = simplexml_load_string($response);		
	//echo "https://g2p.gate2play.com:8843/$test/call_returntran?mid=$mid&appid=$appid&muid=$muid&orderid=$Your_Order&msignature=$msignature";
	//$result_xml =simplexml_load_file("https://g2p.gate2play.com:8843/$test/call_returntran?mid=$mid&appid=$appid&muid=$muid&orderid=$Your_Order&msignature=$msignature");
	//print_r($result_xml);
	if ($result_xml == FALSE)
	{
	  echo "Failed loading XML\n";
	
	  foreach (libxml_get_errors() as $error) 
	  {
		echo "\t", $error->message;
	  }   
	} 

	if($result_xml->children()->getName() == 'tran')
	{
		$trns_xml = $result_xml->children();
		foreach ($trns_xml->children() as $child)
		{
			$res[$child->getName()] = $child;
		}
	}else $res = FALSE;
	return $res;
}
?>