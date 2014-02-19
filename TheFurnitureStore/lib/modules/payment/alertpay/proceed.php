<?php
include_once('functions.php');
$order 		= process_order(3);
global $OPTION;

$INVOICE 	= load_what_is_needed('invoice');	//change.9.10
$EMAIL 		= load_what_is_needed('email');		//change.9.10

$feedback 	= array();
$EPD = new EPDDecryptor($OPTION['wps_alertpay_transaction_key']);
$response = $EPD->decrypt($_GET['AP']);
//print_r($response); echo "<br />";
//print_r($order); echo "<br />";
$feedback['status'] 				= $response['ap_status'];
$feedback['itemname']				= $response["ap_itemname"];
$feedback['FNAME'] 					= (isset($order['f_name']))? $order['f_name']:$response["ap_custfirstname"];
$feedback['LNAME']					= (isset($order['l_name']))? $order['l_name']:$response["ap_custlastname"];
$feedback['AMOUNT']					= $response["ap_amount"];
$feedback['CURRENCY']				= $response["ap_currency"];
$feedback['temp_txn_id']			= $response["ap_itemcode"];
$feedback['trans_id']				= $response["ap_referencenumber"];
$feedback['x_response_reason_text']	= "";		
$feedback['pay_m']					= 'alertpay';
//print_r($feedback); echo "<br />";

//lets log the raw data from Authnet 
log_authn_payment_data($response,$feedback['temp_txn_id']);


//little bit of added security
if(!isset($response['ap_status'])  ){	
	mail($OPTION['wps_shop_email'],'AlertPay.com warning','Somebody tried to reach your alertpay IPN page without a CC payment.');
	exit();
}


switch($feedback['status'])
{
	case "Success":

		// Update DB 
		$order = process_payment($feedback,'alertpay');
					
		// We add some additional values for confirmation screen needed for e.g. info about pick-up etc.
		#$PDT_DATA[d_option] 	= $order[d_option];
		#$PDT_DATA[tracking_id]	= $order[tracking_id];
						

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
		$search		= array("[##header##]","[##f_name##]","[##l_name##]","[##amount##]","[##currency##]","[##url##]");
		$replace 	= array($EMAIL->email_header(),$order['f_name'],$order['l_name'],$order['amount'],get_option('wps_currency_code'),url_be()); //change.9.10
		$EMAIL->email_owner_order_notification($feedback,$search,$replace);		//change.9.10
		
		$order[itemname] = $feedback['itemname'];
	break;
	
	default:
	break;
}		
