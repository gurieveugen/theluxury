<?php
	$appendAmp = 0;
	$vpcURL = "";
	$newHash = "";
	$md5HashData = "";

	switch($OPTION['wps_price_format']){
		case '3':
			$formatted_TOTAL_AM = str_replace(".", "", $TOTAL_AM) * 100;
		break;		
		case '4':
			$formatted_TOTAL_AM = str_replace(",", "", $TOTAL_AM) * 100;
		break;		
		case '5':
			$formatted_TOTAL_AM = str_replace(" ", "", $TOTAL_AM) * 100;
		break;		
		case '6':
			$formatted_TOTAL_AM = str_replace("'", "", $TOTAL_AM) * 100;
		break;
		case '7':
			$formatted_TOTAL_AM = str_replace(" ", "", $TOTAL_AM) * 100;
		break;
		case '8':
			$formatted_TOTAL_AM = str_replace(",", "", $TOTAL_AM) * 100;
		break;		
	}

	$arrPaymentDetails = array(
		"secure_secret" => trim($OPTION['wps_audi_secret']),
		"accessCode" => trim($OPTION['wps_audi_code']),
		"amount" => $formatted_TOTAL_AM,
		"merchTxnRef" => time(),
		"merchant" => trim($OPTION['wps_audi_mid']),
		"orderInfo" => $OPTION['wps_order_no_prefix'].$order['oid'],
		"returnURL" => trim($OPTION['wps_audi_return_url'])
	);
	$thousand_separators = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U");

	foreach($arrPaymentDetails as $key => $value) 
	{
		// create the md5 input and URL. Secure_secret is required for md5 creating only.
		if ($key != 'secure_secret') {
			// this ensures the first paramter of the URL is preceded by the '?' char
			if ($appendAmp == 0) {
				$vpcURL .= urlencode($key) . '=' . urlencode($value);
				$appendAmp = 1;
			} else {
				$vpcURL .= '&' . urlencode($key) . "=" . urlencode($value);
			}
		}				
		$md5HashData .= $value;
	}	
	$newHash .= $vpcURL . "&vpc_SecureHash=" . strtoupper(md5($md5HashData));
?>	
<form action="https://gw1.audicards.com/TPGWeb/payment/prepayment.action?<?php echo $newHash; ?>" method="POST">
	<div class="button-right" style="float:right;">
		<input type="submit" class="btn-orange" value="Place Order" />
	</div>
</form>