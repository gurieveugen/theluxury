<?php
/*
Template Name: Test Page
*/
global $OPTION;
$amount = 12000;

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
echo '<hr><h1>Bank Transfer</h1><hr>';

echo"<h2>".__('Thank you for your order!','wpShop')."</h2>";

echo "<h4>";
	echo _e('Please have the Amount of ','wpShop'); 
	echo "<span class='pay_amount'>"; 
		if($OPTION['wps_currency_symbol'] !='') { echo $OPTION['wps_currency_symbol'];} echo format_price($amount); if($OPTION['wps_currency_code_enable']) { echo " " . $OPTION['wps_currency_code']; }  if($OPTION['wps_currency_symbol_alt'] !='') { echo " " . $OPTION['wps_currency_symbol_alt']; }
	echo "</span>"; _e(' to our Bank Account using the following information:','wpShop');
echo "</h4>";

echo "
	<table>
		<tr><td>". __('Name of Recipient:','wpShop') . "</td><td>"; echo $OPTION['wps_banktransfer_account_owner']; echo "</td></tr>
		<tr><td>". __('for:','wpShop')."</td><td>Test item name</td></tr>
		<tr><td>". __('Name of Bank:','wpShop')."</td><td>"; echo $OPTION['wps_banktransfer_bankname']; echo "</td></tr>
		<tr><td>". __('Routing Number:','wpShop')."</td><td>"; echo $OPTION['wps_banktransfer_bankno']; echo "</td></tr>
		<tr><td>". __('Account Number:','wpShop')."</td><td>"; echo $OPTION['wps_banktransfer_accountno']; echo "</td></tr>
		";
		$iban 	= $OPTION['wps_banktransfer_iban'];
		$bic 	= $OPTION['wps_banktransfer_bic'];
		if(!empty($iban)){
		echo "<tr><td>IBAN:</td><td>"; echo $OPTION['wps_banktransfer_iban']; echo "</td></tr>";
		}
		if(!empty($bic)){				
		echo "<tr><td>BIC/SWIFT:</td><td>"; echo $OPTION['wps_banktransfer_bic']; echo "</td></tr>";
		}
echo "</table><br/><br/>						
	<p>".__('When we have Received your Payment on our Account, we will begin to Process your Order.','wpShop')."</p>";

$shop_slug = $OPTION['wps_shop_slug'];
if(strlen($shop_slug) > 0){
	echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='category/"; echo $OPTION['wps_shop_slug']; echo "'>".__('Click here.','wpShop')."</a></h5>";					
}else{
	echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='".get_real_base_url('force_http')."'>".__('Click here.','wpShop')."</a></h5>";					
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
echo '<hr><h1>Cach on Delivery</h1><hr>';
echo "<h2>".__('Thank you for your Order!','wpShop')."</h2>";
echo "<h4>Please have the Amount of <span class='pay_amount'>".format_price($amount)." AED</span> ready upon Delivery. This amount can be paid in Dirhams.</h4>";

echo "<br/><br/>";

echo "<strong>What Happens Next?</strong><br/><br/>";
echo "Our customer service representative will give you a call to confirm the order.<br/><br/>";

echo "<strong>When will I get my items?</strong><br/><br/>";
echo "Your item will be delivered in 2-10 business days.<br/><br/>";
echo "Once your item has been dispatched, you will receive a notification e-mail which will contain your delivery reference number. You will be able to use this reference number to track your shipment.<br/><br/><br/><br/>";

if(pdf_usable_language()){ 	
	$bill_format_label = __('Your Bill in PDF Format','wpShop');
}else{
	$bill_format_label = __('Your Bill in HTML Format','wpShop');					
}

$shop_slug = (isset($OPTION['wps_shop_slug']))? $OPTION['wps_shop_slug']:'';
if(strlen($shop_slug) > 0){
	echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='category/"; echo $OPTION['wps_shop_slug']; echo "'>".
	__('Click here.','wpShop')."</a></h5>";					
}else{				
	echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='".get_real_base_url('force_http')."'>".
	__('Click here.','wpShop')."</a></h5>";					
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
echo '<hr><h1>Cach on Location</h1><hr>';
echo "<h2>".__('Thank you for your order!','wpShop')."</h2>";
echo "<h4>Please have the Amount of <span class='pay_amount'>".format_price($amount)." AED</span> ready upon Delivery. This amount can be paid in Dirhams.</h4>";

echo "<br/><br/>";

echo "<strong>What happens next?</strong><br/><br/>";

echo "A customer service associate will contact you to confirm your order. Your item is reserved and must be picked up within 2 days.<br/><br/>";

echo "Our Address:<br/>";
echo "803<br/>";
echo "Sidra Tower (Frasier Suites Hotel Building)<br/>";
echo "Sheikh Zayed Road<br/>";
echo "Al Sufou 1, Dubai, UAE<br/><br/>";

echo "Opening Hours:<br/>";
echo "9am-6pm Sunday-Thursday<br/><br/>";

echo "Location map:<br/>";
echo '<iframe width="565" height="445" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps/ms?msid=210891605401531059454.0004cde7608b3a7292fe7&amp;msa=0&amp;ie=UTF8&amp;ll=25.10814,55.179809&amp;spn=0.006791,0.013078&amp;t=m&amp;iwloc=0004cde76577d48ec19d3&amp;output=embed"></iframe><br/><br/><br/><br/>';

if(pdf_usable_language()){
	$bill_format_label = __('Your Bill in PDF Format','wpShop');
}else{
	$bill_format_label = __('Your Bill in HTML Format','wpShop');					
}

$shop_slug = $OPTION['wps_shop_slug'];
if (strlen($shop_slug) > 0) {
	echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='category/"; echo $shop_slug; echo "'>".__('Click here.','wpShop')."</a></h5>";
} else {
	echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='".get_real_base_url('force_http')."'>".__('Click here.','wpShop')."</a></h5>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
echo '<hr><h1>2CHECKOUT</h1><hr>';
	echo "<h2>".__('Thank you for your Purchase!','wpShop')."</h2>";
	echo "<dl><dt>".__('Payment Details','wpShop'). "</dt>\n";
	echo "<dd>".__('Name:','wpShop')." xxxxxx xxxxxxxxx</dd>\n";
	echo "<dd>".__('Item:','wpShop')." xxxxxxxxxx</dd>\n";
	echo "<dd>".__('Order Amount:','wpShop')." $amount USD</dd>\n";
	echo "<dd>".__('Transaction Id:','wpShop')." xxxxxxxxxx </dd></dl>\n\n";
				
	echo "	<h3 class='order_success'><span>".__('Your Transaction has been Completed, and a Receipt of your Purchase has been Emailed to you.','wpShop').	"</span></h3>";
	echo "
	<h4>".__('What Next?','wpShop')."</h4>	
	<p>".__('You may also Track your Order using the Tracking Number you are given in your Confirmation Email. Simply login to your Account and enter that number in the field under \'Track Your Order\' in the sidebar.','wpShop')."</p>";

	
	$shop_slug = $OPTION['wps_shop_slug'];
	if(strlen($shop_slug) > 0){
		echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='{$homeurl}/category/"; echo $OPTION['wps_shop_slug']; echo "'>".
		__('Click here.','wpShop')."</a></h5>";					
	}else{
	
		echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='"; echo get_real_base_url('force_http'); echo "'>".
		__('Click here.','wpShop')."</a></h5>";		
		
	}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
echo '<hr><h1>Alert Pay</h1><hr>';
	echo "<h2>".__('Thank you for your Purchase!','wpShop')."</h2>";
	echo "<dl><dt>".__('Payment Details','wpShop'). "</dt>\n";
	echo "<dd>".__('Name:','wpShop')." xxxxxxxx xxxxxxxxx</dd>\n";
	echo "<dd>".__('Item:','wpShop')." xxxxxxxxxxxx</dd>\n";
	echo "<dd>".__('Order Amount:','wpShop')." $amount USD</dd>\n";
	echo "<dd>".__('Transaction Id:','wpShop')." xxxxxxxxxxxxxxx </dd></dl>\n\n";
				
	echo "	<h3 class='order_success'><span>".__('Your Transaction has been Completed, and a Receipt of your Purchase has been Emailed to you.','wpShop').	"</span></h3>";
	echo "
	<h4>".__('What Next?','wpShop')."</h4>	
	<p>".__('You may also Track your Order using the Tracking Number you are given in your Confirmation Email. Simply login to your Account and enter that number in the field under \'Track Your Order\' in the sidebar.','wpShop')."</p>";

	
	$shop_slug = $OPTION['wps_shop_slug'];
	if(strlen($shop_slug) > 0){
		echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='{$homeurl}/category/"; echo $OPTION['wps_shop_slug']; echo "'>".
		__('Click here.','wpShop')."</a></h5>";					
	}else{
	
		echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='"; echo get_real_base_url('force_http'); echo "'>".
		__('Click here.','wpShop')."</a></h5>";		
		
	}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
echo '<hr><h1>PayPal</h1><hr>';
	echo "<h2>".__('Thank you for your Purchase!','wpShop')."</h2>";
	echo "<dl><dt>".__('Payment Details','wpShop'). "</dt>\n";
	echo "<dd>".__('Name:','wpShop')." xxxxxxxxxx xxxxxxxxxxxxx</dd>\n";
	echo "<dd>".__('Item:','wpShop')." xxxxxxxxxxxxxxxxxx</dd>\n";
	echo "<dd>".__('Order Amount:','wpShop').' '.format_price($amount)."USD</dd></dl>\n\n";
	
	echo "
	<h3 class='order_success'><span>"
	.__('Your Transaction has been Completed, and a receipt of your purchase has been emailed to you.','wpShop').
	"</span></h3>";
	echo "
	<h4>".__('What Next?','wpShop')."</h4>	
	<p>
		".__('You may Log into your Account at <a href="https://www.paypal.com">PayPal</a> to view Details of this Transaction.','wpShop')."<br/>".__('If you have an Account with us you may also Track your Order using the Tracking Number you are given in your Confirmation Email. Login to your Account and enter that Number in the field under "Track Your Order" in the Sidebar.','wpShop').						
	"</p>";
		$maplink = $OPTION['wps_google_maps_link'];
		echo "
		<h4>".__('You can Collect your Items at our Shop Location in:','wpShop')."</h4>";
		echo "<p>";
			echo $OPTION['wps_shop_name'];
			echo "<br/>";
			echo $OPTION['wps_shop_street'];
			echo "<br/>";
			echo $OPTION['wps_shop_province'];
			echo "<br/>";
			echo $OPTION['wps_shop_zip'];						
			echo $OPTION['wps_shop_town'];
			echo "<br/>";
			echo $OPTION['wps_shop_country'];
		echo "</p>";
		
		echo "
		<h4>".__('Your Tracking ID','wpShop')."</h4>";
		echo "<p>";
			_e('Please give the following Tracking-ID to the Shop Assistant when you come to Collect your Order:','wpShop');
			echo " $PDT_DATA[tracking_id]";
		echo "</p>";
		
		if(strlen($maplink) > 5){
		echo "<h4>".__('Our Location','wpShop')."</h4>";
		echo "<p>";
		echo "<a href='$maplink' target='_blank'>".__('Google Maps - How to Find us','wpShop')."</a>";
		}
		echo "</p>";						
	$shop_slug = $OPTION['wps_shop_slug'];
	if(strlen($shop_slug) > 0){
		echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='category/"; echo $OPTION['wps_shop_slug']; echo "'>".
		__('Click here.','wpShop')."</a></h5>";					
	}else{
	
		echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='"; echo get_real_base_url('force_http'); echo "'>".
		__('Click here.','wpShop')."</a></h5>";		
		
	}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
echo '<hr><h1>PayPal PRO</h1><hr>';
	echo "<h2>".__('Thank you for your Purchase!','wpShop')."</h2>";
	echo "<dl><dt>".__('Payment Details','wpShop'). "</dt>\n";
	echo "<dd>".__('Name:','wpShop')." $PDT_PRO_DATA[f_name] $PDT_PRO_DATA[l_name]</dd>\n";
	echo "<dd>".__('Item:','wpShop')." $PDT_PRO_DATA[itemname]</dd>\n";
	echo "<dd>".__('Order Amount:','wpShop').' '.format_price($PDT_PRO_DATA[amount]).' '.$OPTION['wps_currency_code']."</dd></dl>\n\n";	
				
	echo "
	<h3 class='order_success'><span>"
	.__('Your Transaction has been Completed, and a Receipt of your Purchase has been Emailed to you.','wpShop').
	"</span></h3>";


	if(pdf_usable_language()){ 
		$bill_format_label = __('Your Bill in PDF Format','wpShop');
	}else{
		$bill_format_label = __('Your Bill in HTML Format','wpShop');					
	}

	
	echo "
	<h4>".__('What Next?','wpShop')."</h4>	
	<p>".__('If you have an Account with us you may also Track your Order using the Tracking Number you are given in your Confirmation Email. Login to your Account and enter that Number in the field under "Track Your Order" in the Sidebar.','wpShop')."</p>";  
		$maplink = $OPTION['wps_google_maps_link'];
		echo "
		<h4>".__('You can Collect your Items at our Shop Location in:','wpShop')."</h4>";
		echo "<p>";
			echo $OPTION['wps_shop_name'];
			echo "<br/>";
			echo $OPTION['wps_shop_street'];
			echo "<br/>";
			echo $OPTION['wps_shop_province'];
			echo "<br/>";
			echo $OPTION['wps_shop_zip'];						
			echo $OPTION['wps_shop_town'];
			echo "<br/>";
			echo $OPTION['wps_shop_country'];
		echo "</p>";
		
		echo "
		<h4>".__('Your Tracking ID','wpShop')."</h4>";
		echo "<p>";
			_e('Please give the following Tracking-ID to the Shop Assistant when you come to Collect your Order:','wpShop');
			echo " $PDT_DATA[tracking_id]";
		echo "</p>";
		
		if(strlen($maplink) > 5){
			echo "<h4>".__('Our Location','wpShop')."</h4>";
			echo "<p>";
				echo "<a href='$maplink' target='_blank'>".__('Google Maps - How to Find us','wpShop')."</a>";
			echo "</p>";
		}							
	$shop_slug = $OPTION['wps_shop_slug'];

	if(strlen($shop_slug) > 0){
		$uri = get_real_base_url('force_http').'/category/'.$OPTION['wps_shop_slug'];
		echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='$uri"; 
		echo "?finished=1&url=$uri'>".__('Click here.','wpShop')."</a></h5>";					
	}else{
		$uri = get_real_base_url('force_http');
		echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='$uri?finished=1&url=$uri'>".
		__('Click here.','wpShop')."</a></h5>";					
	}



// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
echo '<hr><h1>AUDI</h1><hr>';
	echo "<h2>".__('Thank you for your Purchase!','wpShop')."</h2>";
	echo "<dl><dt>".__('Payment Details','wpShop'). "</dt>\n";
	echo "<dd>".__('Name:','wpShop')." xxxxxxxxx xxxxxxxxxxxxxx</dd>\n";
	echo "<dd>".__('Transaction Id:','wpShop')." xxxxxxxxxxxxxxxxx</dd>\n";			
	echo "<dd>".__('Order Amount:','wpShop')." ".format_price($amount)." USD</dd></dl>\n\n";
				
	echo "
	<h3 class='order_success'><span>"
	.__('Your Transaction has been Completed, and a Receipt of your Purchase has been Emailed to you.','wpShop').
	"</span></h3>";
	echo "
	<h4>".__('What Next?','wpShop')."</h4>	
	<p>".__('You may also Track your Order using the Tracking Number you are given in your Confirmation Email. Simply login to your Account and enter that number in the field under \'Track Your Order\' in the sidebar.','wpShop')."</p>";

	
	$shop_slug = $OPTION['wps_shop_slug'];
	if(strlen($shop_slug) > 0){
		echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='{$homeurl}/category/"; echo $OPTION['wps_shop_slug']; echo "'>".
		__('Click here.','wpShop')."</a></h5>";					
	}else{
	
		echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='"; echo get_real_base_url('force_http'); echo "'>".
		__('Click here.','wpShop')."</a></h5>";		
		
	}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
echo '<hr><h1>Authorize.net</h1><hr>';
			echo "<h2>".__('Thank you for your Purchase!','wpShop')."</h2>";
			echo "<dl><dt>".__('Payment Details','wpShop'). "</dt>\n";
			echo "<dd>".__('Name:','wpShop')." $AUTHN_DATA[f_name] $AUTHN_DATA[l_name]</dd>\n";
			echo "<dd>".__('Item:','wpShop')." $AUTHN_DATA[itemname]</dd>\n";
			echo "<dd>".__('Order Amount:','wpShop')." $AUTHN_DATA[amount] USD</dd></dl>\n\n";
						
			echo "
			<h3 class='order_success'><span>"
			.__('Your Transaction has been Completed, and a Receipt of your Purchase has been Emailed to you.','wpShop').
			"</span></h3>";
			echo "
			<h4>".__('What Next?','wpShop')."</h4>	
			<p>".__('You may also Track your Order using the Tracking Number you are given in your Confirmation Email. Simply login to your Account and enter that number in the field under \'Track Your Order\' in the sidebar.','wpShop')."</p>";

			
			$shop_slug = $OPTION['wps_shop_slug'];
			if(strlen($shop_slug) > 0){
				echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='{$homeurl}/category/"; echo $OPTION['wps_shop_slug']; echo "'>".
				__('Click here.','wpShop')."</a></h5>";					
			}else{
			
				echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='"; echo get_real_base_url('force_http'); echo "'>".
				__('Click here.','wpShop')."</a></h5>";		
				
			}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
echo '<hr><h1>WorldPay.com</h1><hr>';
	echo "<h2>".__('Thank you for your Purchase!','wpShop')."</h2>";
	echo "<dl><dt>".__('Payment Details','wpShop'). "</dt>\n";
	echo "<dd>".__('Name:','wpShop')." $WPAY_DATA[f_name] $WPAY_DATA[l_name]</dd>\n";
	echo "<dd>".__('Item:','wpShop')." $WPAY_DATA[itemname]</dd>\n";
	echo "<dd>".__('Order Amount:','wpShop').' '.format_price($WPAY_DATA[amount]).' '.$WPAY_DATA[currency]."</dd></dl>\n\n";
				
	echo "
	<h3 class='order_success'><span>"
	.__('Your Transaction has been Completed, and a Receipt of your Purchase has been Emailed to you.','wpShop').
	"</span></h3>";
	echo "
	<h4>".__('What Next?','wpShop')."</h4>	
	<p>".__('If you have an Account with us you may also Track your Order using the Tracking Number you are given in your Confirmation Email. Login to your Account and enter that Number in the field under \'Track Your Order\' in the sidebar.','wpShop')."</p>";
				
	$shop_slug = $OPTION['wps_shop_slug'];
	if(strlen($shop_slug) > 0){
		echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='{$baseurl}/category/"; echo $OPTION['wps_shop_slug']; echo "'>".__('Click here.','wpShop')."</a></h5>";					
	}else{
		
		echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='"; echo get_real_base_url('force_http'); 
		echo "'>".__('Click here.','wpShop')."</a></h5>";			
		
	}

?>