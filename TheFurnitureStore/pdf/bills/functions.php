<?php
$p = explode('wp-content/',__FILE__);
include $p[0].'wp-load.php';

function authn_response(){

	global $OPTION;

	$INVOICE 	= load_what_is_needed('invoice');	//change.9.10
	$EMAIL 		= load_what_is_needed('email');		//change.9.10
	
	$feedback 	= array();
	
	$feedback['status'] 				= (int) $_GET[vpc_TxnResponseCode];
	$feedback['order_id']				=  null2unknown(addslashes($_GET["merchTxnRef"]));
	$feedback['AMOUNT']					= null2unknown(addslashes($_GET["amount"])/100);
	$feedback['CURRENCY']				= 'USD';
	$feedback['trans_id']				= null2unknown(addslashes($_GET["vpc_TransactionNo"]));
	$feedback['message']				= null2unknown(addslashes($_GET["vpc_Message"]));		
	$feedback['pay_m']					= 'audi';
	$feedback['itemname']				= "Order No." . $feedback['order_id'] . " - " . date($OPTION['date_format']);

	//lets log the raw data from Audi 
	log_authn_payment_data($_POST,$feedback['trans_id']);
	
	//little bit of added security
	//if(!isset($_POST['x_response_code'])){	
	//	mail($OPTION['wps_shop_email'],'Authorize.net warning','Somebody tried to reach your Authnet confirmation page without a CC payment.');
	//	exit("Payment failure - Please contact the webmaster of this site.");
	//}
	
	
	switch($feedback['status'])
	{
		case 0:
		
			//check if hash is correct, security measure to prevent users correct GET params
			if(isHashCorrect!="correct"){
				echo "<br><b>There has been an error processing this transaction.</b><br>";	
				echo ("ERROR: current order was processed already.<br/><br/>");
				$order['status'] = "9";
				return $order;
			}

			// Update DB 
			$order = process_payment($feedback,'audi');
			
			//if $order is empty, it's usually means that user pressed refressh or copied link to browser..
			if ($order==false){
				echo "<br><b>There has been an error processing this transaction.</b><br>";	
				echo ("ERROR: current order was processed already.<br/><br/>");
				$order['status'] = "9";
				return $order;
			}
			
			$order[itemname] = $feedback['itemname'];

			
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
			$search		= array("[##header##]","[##f_name##]","[##l_name##]","[##amount##]","[##currency##]","[##url##]");
			$replace 	= array($EMAIL->email_header(),$order['f_name'],$order['l_name'],$order['amount'],get_option('wps_currency_code'),url_be()); //change.9.10
			$EMAIL->email_owner_order_notification($feedback,$search,$replace);		//change.9.10
			

		break;
		
		default:
				echo "<br><b>There has been an error processing this transaction.</b><br>";	
				
				if (isset($_GET['vpc_TxnResponseCode'])){
					echo ("ERROR: " . getResponseDescription($feedback['status'])) . "<br/>";
					echo $feedback['message'];
				}
				$order['status'] = "9";
				return $order;
				
		break;
	}		
	
	
	// delete  old custom_id - in order to avoid mixed up orders 
	unset($_SESSION[cust_id]);
	$_SESSION = array();
			
	$order['status']					= $feedback['status']; 
	$order['itemname']					= $feedback['order_id'];
	$order['message'] 					= $feedback['message'];	
	$order['order_id'] 					= $feedback['order_id'];	
	
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


	//function to map each response code number to a text message	
	function getResponseDescription($responseCode) 
	{
	    switch ($responseCode) {
	        case "0" : $result = "Transaction Successful"; break;
	        case "?" : $result = "Transaction status is unknown"; break;
	        case "1" : $result = "Unknown Error"; break;
	        case "2" : $result = "Bank Declined Transaction"; break;
	        case "3" : $result = "No Reply from Bank"; break;
	        case "4" : $result = "Expired Card"; break;
	        case "5" : $result = "Insufficient funds"; break;
	        case "6" : $result = "Error Communicating with Bank"; break;
	        case "7" : $result = "Payment Server System Error"; break;
	        case "8" : $result = "Transaction Type Not Supported"; break;
	        case "9" : $result = "Bank declined transaction (Do not contact Bank)"; break;
	        case "A" : $result = "Transaction Aborted"; break;
	        case "C" : $result = "Transaction Cancelled"; break;
	        case "D" : $result = "Deferred transaction has been received and is awaiting processing"; break;
	        case "E" : $result = "Invalid Credit Card"; break;
	        case "F" : $result = "3D Secure Authentication failed"; break;
	        case "I" : $result = "Card Security Code verification failed"; break;
	        case "G" : $result = "Invalid Merchant"; break;
	        case "L" : $result = "Shopping Transaction Locked (Please try the transaction again later)"; break;
	        case "N" : $result = "Cardholder is not enrolled in Authentication scheme"; break;
	        case "P" : $result = "Transaction has been received by the Payment Adaptor and is being processed"; break;
	        case "R" : $result = "Transaction was not processed - Reached limit of retry attempts allowed"; break;
	        case "S" : $result = "Duplicate SessionID (OrderInfo)"; break;
	        case "T" : $result = "Address Verification Failed"; break;
	        case "U" : $result = "Card Security Code Failed"; break;
	        case "V" : $result = "Address Verification and Card Security Code Failed"; break;
	        case "X" : $result = "Credit Card Blocked"; break;
	        case "Y" : $result = "Invalid URL"; break;                
	        case "B" : $result = "Transaction was not completed"; break;                
	        case "M" : $result = "Please enter all required fields"; break;                
	        case "J" : $result = "Transaction already in use"; break;
	        case "BL" : $result = "Card Bin Limit Reached"; break;                
	        case "CL" : $result = "Card Limit Reached"; break;                
	        case "LM" : $result = "Merchant Amount Limit Reached"; break;                
	        case "Q" : $result = "IP Blocked"; break;                
	        case "R" : $result = "Transaction was not processed - Reached limit of retry attempts allowed"; break;                
	        case "Z" : $result = "Bin Blocked"; break;

	        default  : $result = "Unable to be determined"; 
	    }
	    return $result;
	}
	
	//function to display a No Value Returned message if value of field is empty
	function null2unknown($data) 
	{
	    if ($data == "") 
	        return "No Value Returned";
	     else 
	        return $data;
	} 	

	
	function isHashCorrect()}
		//get secure hash value of merchant	
		//get the secure hash sent from payment client
		$vpc_Txn_Secure_Hash = addslashes($_GET["vpc_SecureHash"]);
		unset($_GET["vpc_SecureHash"]); 
		ksort($_GET);
		//check if the value of response code is valid
		if (strlen($SECURE_SECRET) > 0 && addslashes($_GET["vpc_TxnResponseCode"]) != "7" && addslashes($_GET["vpc_TxnResponseCode"]) != "No Value Returned") 
		{
			//creat an md5 variable to be compared with the passed transaction secure hash to check if url has been tampered with or not
			$md5HashData = $SECURE_SECRET;

			//creat an md5 variable to be compared with the passed transaction secure hash to check if url has been tampered with or not
			$md5HashData_2 = $SECURE_SECRET;

			// sort all the incoming vpc response fields and leave out any with no value
			foreach($_GET as $key => $value) 
			{
				if ($key != "vpc_SecureHash" && strlen($value) > 0 && $key != 'action' ) 
				{
					$hash_value = str_replace(" ",'+',$value);
					$hash_value = str_replace("%20",'+',$hash_value);
					$md5HashData_2 .= $value;
					$md5HashData .= $hash_value;
					
				}
			}

			//if transaction secure hash is the same as the md5 variable created 
			if ((strtoupper($vpc_Txn_Secure_Hash) == strtoupper(md5($md5HashData)) || strtoupper($vpc_Txn_Secure_Hash) == strtoupper(md5($md5HashData_2))))
			{
				return "correct";
			} 
			else 
			{
				return "incorrect";
			}
		} 
		else 
		{
			return "incorrect";
		}	
	}

?>