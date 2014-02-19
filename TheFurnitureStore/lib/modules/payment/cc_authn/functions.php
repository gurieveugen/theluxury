<?php
$p = explode('wp-content/',__FILE__);
include $p[0].'wp-load.php';



function authn_response(){

	global $OPTION;

	$INVOICE 	= load_what_is_needed('invoice');	//change.9.10
	$EMAIL 		= load_what_is_needed('email');		//change.9.10
	
	$feedback 	= array();
	
	$feedback['status'] 				= (int) $_POST[x_response_code];
	$feedback['itemname']				= $_POST[x_description];
	$feedback['FNAME'] 					= $_POST[x_first_name];
	$feedback['LNAME']					= $_POST[x_last_name];
	$feedback['AMOUNT']					= $_POST[x_amount];
	$feedback['CURRENCY']				= 'USD';
	$feedback['temp_txn_id']			= $_POST[x_invoice_num];
	$feedback['trans_id']				= $_POST[x_trans_id];
	$feedback['x_response_reason_text']	= $_POST['x_response_reason_text'];		
	$feedback['pay_m']					= 'authn';


	//lets log the raw data from Authnet 
	log_authn_payment_data($_POST,$feedback['temp_txn_id']);
	
	//little bit of added security
	if(!isset($_POST['x_response_code'])){	
		mail($OPTION['wps_shop_email'],'Authorize.net warning','Somebody tried to reach your Authnet confirmation page without a CC payment.');
		exit("Payment failure - Please contact the webmaster of this site.");
	}
	
	
	###############################################################
	#	depending on status - different reaction follows
	#
	#	1 = Success
	#	2 = Declined
	#	3 = Error
	#	4 = Held for Review
	###############################################################
	
	
	switch($feedback['status'])
	{
		case 1:
	
			// Update DB 
			$order = process_payment($feedback,'authn');
						
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
		
		case 2:
				echo "<b>This transaction has been declined.</b>";	
				if($_POST['x_response_reason_text'] != 'This transaction has been declined.'){
					echo "<b>".$_POST['x_response_reason_text']."</b>";
				}	
		break;
		
		case 3:
				echo "<b>There has been an error processing this transaction.</b>";	
				if($_POST['x_response_reason_text'] != 'There has been an error processing this transaction.'){
					echo "<b>".$_POST['x_response_reason_text']."</b>";
				}
		break;
		
		case 4:
				echo "<b>This transaction is being held for review.</b>";	
				if($_POST['x_response_reason_text'] != 'This transaction is being held for review.'){
					echo "<b>".$_POST['x_response_reason_text']."</b>";
				}
		break;
	}		
	
	
	// delete  old custom_id - in order to avoid mixed up orders 
	unset($_SESSION[cust_id]);
	$_SESSION = array();
			
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
?>