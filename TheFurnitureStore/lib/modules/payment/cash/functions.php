<?php
function cas_response(){
	global $OPTION;
	
	$INVOICE 	= load_what_is_needed('invoice');	//change.9.10
	$EMAIL 		= load_what_is_needed('email');		//change.9.10
	
	// Process cash at store response 
	$feedback 			  = array();
	$feedback['status']   = 'Completed';
	$feedback['itemname'] = $_POST['item_name'];
	$feedback['who']	  = $_SESSION['cust_id'];

	// Update DB 
	$order = process_payment($feedback,'cash');
	
	if($order == FALSE) { expulsion(); } // if customer refreshes last confirmation page -> redirect
	
	// We add some additional values for confirmation screen needed for e.g. info about pick-up etc.
	$feedback['d_option'] 	   = $order['d_option'];
	$feedback['p_option'] 	   = $order['p_option'];
	$feedback['tracking_id']   = $order['tracking_id'];
	$feedback['amount']		   = $order['net'];
	$feedback['oid']		   = $order['oid'];
	$feedback['layaway_order'] = $order['layaway_order'];

	// Provide Invoice in PDF/Html format  	
	if(pdf_usable_language()) {
		$INVOICE->make_pdf($order);
		$feedback['pdf_bill'] = NWS_encode($OPTION['wps_invoice_prefix'].'_' . $order['tracking_id'] . '.pdf');
	} else {
		$INVOICE->make_html($order);
		$feedback['pdf_bill'] = NWS_encode($OPTION['wps_invoice_prefix'].'_' . $order['tracking_id'] . '.html');
	}

	// Email to customer 
	$EMAIL->email_confirmation($order,$feedback,'cash');
	
	// Email to shop owner
	$search		= array("[##header##]","[##f_name##]","[##l_name##]","[##amount##]","[##currency##]","[##url##]");
	$replace 	= array($EMAIL->email_header(),$order['f_name'],$order['l_name'],$order['amount'],$OPTION['wps_currency_code'],url_be());
	$EMAIL->email_owner_order_notification($feedback,$search,$replace);
	
	// delete  old custom_id - in order to avoid mixed up orders 
	unset($_SESSION['cust_id']);
	
	return $feedback;
}
?>