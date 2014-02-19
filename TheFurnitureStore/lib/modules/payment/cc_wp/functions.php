<?php
function wpay_response(){
	global $OPTION;
	
	$INVOICE 	= load_what_is_needed('invoice');	//change.9.10
	$EMAIL 		= load_what_is_needed('email');		//change.9.10	
	
	$feedback 	= array();
		
	$feedback['status'] 		= $_POST[transStatus];  //(=Y or C)
	$feedback['temp_txn_id'] 	= $_POST[MC_invoice];
	$feedback['trans_id'] 		= md5(microtime());
	$feedback['itemname']		= $_POST[desc];
	$feedback['currency'] 		= $_POST[currency];
	$feedback['pay_m'] 			= 'cc_wp';

	
	// update order table 
	$order = process_payment($feedback,'wpay');
				
	// We add some additional values for confirmation screen needed for e.g. info about pick-up etc.
	#$PDT_DATA[d_option] 	= $order[d_option];
	#$PDT_DATA[tracking_id]	= $order[tracking_id];
					

	// Manage downloadable products
	// are there digital goods in cart? - if create download links for them 					
	if(digital_in_cart($order[who])){
							
		$DIGITALGOODS 	= load_what_is_needed('digitalgoods');	//change.9.10					
							
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
		send_user_dlinks($order['who'],'FE');
	}
	
	// Provide Invoice in PDF/Html format  
	if(pdf_usable_language()){
		$INVOICE->make_pdf($order);		//change.9.10
		$order[pdf_bill] = NWS_encode($OPTION['wps_invoice_prefix'].'_' . $order['tracking_id'] . '.pdf');
	}
	else{
		$INVOICE->make_html($order);	//change.9.10
		$order[pdf_bill] = NWS_encode($OPTION[wps_invoice_prefix].'_' . $order['tracking_id'] . '.html');
	}
				
	// Email to customer 
	$EMAIL->email_confirmation($order,$feedback);	//change.9.10
	
	// Email to shop owner
	$search		= array("[##header##]","[##f_name##]","[##l_name##]","[##amount##]","[##currency##]","[##url##]");
	$replace 	= array($EMAIL->email_header(),$order['f_name'],$order['l_name'],$order['amount'],$OPTION['wps_currency_code'],url_be()); 	//change.9.10
	$EMAIL->email_owner_order_notification($feedback,$search,$replace);	//change.9.10

	foreach($order as $k =>$v){
		$feedback[$k] = $v;
	}
	
	// delete  old custom_id - in order to avoid mixed up orders 
	unset($_SESSION['cust_id']);
	$_SESSION = array();

return $feedback;
}
?>