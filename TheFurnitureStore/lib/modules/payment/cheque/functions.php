<?php
function cheque_response(){
	global $OPTION;			

	$INVOICE = load_what_is_needed('invoice');		//change.9.10
	$EMAIL 		= load_what_is_needed('email');		//change.9.10
	
	// Process cheque transaction 
	$feedback 				= array();
	$feedback['status'] 	= 'Completed';
	$feedback['itemname']	= $_POST['item_name'];

	// Update DB
	$order = payment_gateway_specific_logic($feedback);
	$order = process_payment($order);

	if($order == FALSE){expulsion();}  // if customer refreshes last confirmation page -> redirect				

	// We add some additional values for confirmation screen needed for e.g. info about pick-up etc.
	$PDT_DATA[d_option] 	= $order[d_option];
	$PDT_DATA[tracking_id]	= $order[tracking_id];
	$PDT_DATA[itemname]		= $feedback[itemname];
	
	// Provide Invoice in PDF/Html format  
	if(pdf_usable_language()){ 
		$INVOICE->make_pdf($order);		//change.9.10
		$PDT_DATA[pdf_bill] = NWS_encode($OPTION[wps_invoice_prefix].'_' . $order[tracking_id] . '.pdf');
	}
	else{
		$INVOICE->make_html($order);	//change.9.10
		$PDT_DATA[pdf_bill] = NWS_encode($OPTION[wps_invoice_prefix].'_' . $order[tracking_id] . '.html');
	}

	// Email to customer 
		$EMAIL->email_confirmation($order,$feedback);	//change.9.10
		
	// Email to shop owner
	$search		= array("[##header##]","[##f_name##]","[##l_name##]","[##amount##]","[##currency##]","[##url##]");
	$replace 	= array(email_header(),$order['f_name'],$order['l_name'],$order['amount'],$OPTION['wps_currency_code'],url_be());		//change.9.10
	$EMAIL->email_owner_order_notification($PDT_DATA,$search,$replace);		//change.9.10
			
	// delete  old custom_id - in order to avoid mixed up orders 
	unset($_SESSION[cust_id]);
	
return $PDT_DATA;
}


function payment_gateway_specific_logic($feedback){

	// update orders table according to payment status
	$table 					= is_dbtable_there('orders');
	$column_value_array 	= array();
	$where_conditions 		= array();

	$parts	= explode("-",$_SESSION['cust_id']);

	if($feedback[status] == 'Completed'){
		$column_value_array[txn_id] 		= md5(microtime());
		$column_value_array[tracking_id] 	= $parts[0];
		$column_value_array[order_time] 	= time();
		$column_value_array[level] 			= '4';
	}
	elseif($feedback[status] == 'Pending'){
		$column_value_array[txn_id] 		= $txn_id;
		$column_value_array[tracking_id] 	= $parts[0];
		$column_value_array[order_time] 	= time();
		$column_value_array[pending_r]		= $pending_r; 
		$column_value_array[level] 			= '8';					
	}
	elseif($feedback[status] == 'free'){
		$column_value_array[txn_id] 		= $txn_id;
		$column_value_array[tracking_id] 	= $parts[0];
		$column_value_array[order_time] 	= time();
		$column_value_array[level] 			= '7';							
	}
	else {}
	
	$where_conditions[0]				= "who = '$_SESSION[cust_id]'";
																
	db_update($table, $column_value_array, $where_conditions);	
	
	$qStr 	= "SELECT * FROM $table WHERE who = '$_SESSION[cust_id]' LIMIT 1";
	$res 	= mysql_query($qStr);
	$order 	= mysql_fetch_assoc($res);	

return $order;
}
?>