<?php
function provide_tax_data($order,$taxable_amount){
return $tax_data;
}


function display_tax_data_backend(){
}


function round_tax_amount($amount){
	$result = $amount;
return $result;
}

function show_cart_tax_info(){

	global $LANG,$OPTION;

	echo "$LANG[incl]";
	echo $OPTION['wps_tax_percentage']; 
	echo "% "; 
	echo $OPTION['wps_tax_abbr']; 
}

function tax_invoice_pdf_addition($CART,$order){
	
	global $OPTION;
	
	$data 				= array();
	$data['display']	= 'no';
		
return $data;
}

function tax_email_confirm_addition($CART,$order){

	global $OPTION;

	$data 				= array();
	$data['display']	= 'no';

return $data;
}
?>