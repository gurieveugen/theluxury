<?php
include_once('functions.php');
$order 		= process_order(3);
global $OPTION;

$INVOICE 	= load_what_is_needed('invoice');	//change.9.10
$EMAIL 		= load_what_is_needed('email');		//change.9.10

$feedback 	= array();
$response = $_POST;
$string_to_hash = $OPTION['wps_2checkout_key'].$OPTION['wps_2checkout_sid'].$_POST["order_number"].$_POST["total"];
	$check_key = strtoupper(md5($string_to_hash));
	if($check_key != $_POST['key'] && (!isset($_POST['demo']) || $_POST['demo'] != 'Y'))
	{
		mail($OPTION['wps_shop_email'],'2CHECKOUT.com warning','Somebody tried to reach your 2checkout ipn page with a fraud payment.');
	}
//print_r($response); echo "<br />";
//print_r($order); echo "<br />";
if($response['credit_card_processed'] != 'Y')
{
	$feedback['status'] 				= "Failed";
	return $feedback;
}
else
$feedback['status'] 				= "Success";
$feedback['itemname']				= $response["li_0_name"];
$name = explode(' ',$response["card_holder_name"]);
//return $response;
$feedback['FNAME'] 					= (isset($order['f_name']))? $order['f_name']:$name[0];
$feedback['LNAME']					= (isset($order['l_name']))? $order['l_name']:$name[2];
$feedback['AMOUNT']					= $response["total"];
$feedback['CURRENCY']				= $response["currency"];
$feedback['temp_txn_id']			= $response["li_0_product_id"];
$feedback['trans_id']				= $response["invoice_id"];
$feedback['x_response_reason_text']	= "";		
$feedback['pay_m']					= '2checkout';//print_r($feedback); echo "<br />";

//lets log the raw data from Authnet 
log_authn_payment_data($response,$feedback['temp_txn_id']);


switch($feedback['status'])
{
	case "Success":

		// Update DB 
		$order = process_payment($feedback,'2checkout');
					
		// We add some additional values for confirmation screen needed for e.g. info about pick-up etc.
		#$PDT_DATA[d_option] 	= $order[d_option];
		#$PDT_DATA[tracking_id]	= $order[tracking_id];
						

		// Manage downloadable products
		// are there digital goods in cart? - if create download links for them 					
		if(digital_in_cart($order['who'])){
					
				$DIGITALGOODS 			= load_what_is_needed('digitalgoods');	//change.9.10
					
				$order['digital_there']	= 1; 
				$order['dlinks']			= array();
			
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
				send_user_dlinks($order['who'],'FE');
		}
		
		
		// Provide Invoice in PDF/Html format  
		$parts 			= explode("-",$order['who']);
		$invoice_file 	= $parts[0]; 
		if(pdf_usable_language()){
			$INVOICE->make_pdf($order);		//change.9.10
			$order['pdf_bill'] = NWS_encode($OPTION['wps_invoice_prefix'].'_' . $invoice_file . '.pdf');	
		}
		else{
			$INVOICE->make_html($order);	//change.9.10
			$order['pdf_bill'] = NWS_encode($OPTION['wps_invoice_prefix'].'_' . $invoice_file . '.html');
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
