<?php
include_once(reset(pathinfo(__FILE__)).'/functions.php');

$template_directory = get_bloginfo('template_directory');	
$PDT_DATA 			= pdt_response();
$paymentStatus 		= ($PDT_DATA['error'] != 0 ? 'ERROR' : $PDT_DATA['payment_status'] );
		
//echo 'PDT_DATA :'; print_r($PDT_DATA); echo '<br />';
		
if($paymentStatus == 'Completed')	// the paypal transaction was successful 
{
	echo order_step_table(4); 
	echo "<h2>".__('Thank you for your Purchase!','wpShop')."</h2>";
	echo "<dl><dt>".__('Payment Details','wpShop'). "</dt>\n";
	echo "<dd>".__('Name:','wpShop')." $PDT_DATA[firstname] $PDT_DATA[lastname]</dd>\n";
	echo "<dd>".__('Item:','wpShop')." $PDT_DATA[itemname]</dd>\n";
	echo "<dd>".__('Order Amount:','wpShop').' '.format_price($PDT_DATA[amount]).' '.$PDT_DATA[currency]."</dd></dl>\n\n";
	
	echo "
	<h3 class='order_success'><span>"
	.__('Your Transaction has been Completed, and a receipt of your purchase has been emailed to you.','wpShop').
	"</span></h3>";
	
					
	// digital goods also present? - we need permanent dlinks - PDT is too temporary 	
	if($PDT_DATA[digital_there] == 1){
	
		$DIGITALGOODS 		= load_what_is_needed('digitalgoods');	//change.9.10
	
		$_SESSION[dlinks] 	= $PDT_DATA[dlinks];
		$_SESSION[d_option]	= 'download';
		$_SESSION[who]		= $PDT_DATA[who];
				
		$l_mode			= $OPTION['wps_l_mode'];
		$text_addition1	= ($l_mode == 'GIVE_KEYS' ? __(' and License Keys','wpShop') : NULL);
		$text_addition2	= ($l_mode == 'GIVE_KEYS' ? __('Pls. Copy your License Keys and store them at a safe place.','wpShop') : NULL);
		
		echo "<div class='dlinks'>
		<h5>".__('These are your Download Links','wpShop')."{$text_addition1}:</h5>
		<img src='{$template_directory}/images/download_arrow.png' align='left'/><br/>
		";
	
		if($l_mode == 'GIVE_KEYS'){
		
			if(isset($_SESSION[keys])){			
			}
			else {
				$_SESSION[keys]	= array();
				$length			= array();
			}
		}
	
		foreach($_SESSION[dlinks] as $v){
				
			$fname = substr(strrchr($v,'/'),1);
			echo "<a href='$v'><strong>$fname</strong></a><br/><br/>";		

			if($l_mode == 'GIVE_KEYS'){
			
				$l_check 			= $_SESSION[keys]["$fname"];											
				$length[$fname] 	= strlen($l_check);
				
				if($length[$fname] < 1){
					$_SESSION['keys'][$fname] = $DIGITALGOODS->get_lkeys($fname,$_SESSION['who']);		//change.9.10
				}
				$keys = explode("#",$_SESSION[keys][$fname]);
				
				_e('&nbsp;&nbsp; License keys: &nbsp;&nbsp;','wpShop');
				
				foreach($keys as $n){
					echo "$n &nbsp;&nbsp;";
				}
				echo "<hr/><br/>";											
			}	
		}						
		echo "$text_addition2 </div><br/><br/>";			
					
	}
	
	if(pdf_usable_language()){
		$bill_format_label = __('Your Bill in PDF Format','wpShop');
	}else{
		$bill_format_label = __('Your Bill in HTML Format','wpShop');					
	}

	
	$themename = WPSHOP_THEME_NAME;
	echo "
	<h4>".__('What Next?','wpShop')."</h4>	
	<p>
		".__('You may Log into your Account at <a href="https://www.paypal.com">PayPal</a> to view Details of this Transaction.','wpShop')."<br/>".__('If you have an Account with us you may also Track your Order using the Tracking Number you are given in your Confirmation Email. Login to your Account and enter that Number in the field under "Track Your Order" in the Sidebar.','wpShop').						
	"</p>";
	
	// delivery option = pickup? customer needs to know where to pickup 
	if($PDT_DATA[d_option] == 'pickup'){
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
	}
	
	$shop_slug = $OPTION['wps_shop_slug'];
	if(strlen($shop_slug) > 0){
		echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='category/"; echo $OPTION['wps_shop_slug']; echo "'>".
		__('Click here.','wpShop')."</a></h5>";					
	}else{
	
		echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='"; echo get_real_base_url('force_http'); echo "'>".
		__('Click here.','wpShop')."</a></h5>";		
		
	}
	
	$custom_tracking = $OPTION['wps_custom_tracking'];
	if($custom_tracking !=''){
		echo $custom_tracking;
	}
	ga_ecommerce_tracking_code($PDT_DATA['who']);
	?>
	<script type="text/javascript">
document.write(unescape("%3Cscript id=%27pap_x2s6df8d%27 src=%27" + (("https:" == document.location.protocol) ? "https://" : "http://") + "perf.clickmena.com/scripts/trackjs.js%27 type=%27text/javascript%27%3E%3C/script%3E"));
</script>
<script data-cfasync="false" type="text/javascript">
PostAffTracker.setAccountId('66acecfb'); 
var sale = PostAffTracker.createSale();
price = '<?=$PDT_DATA['amount']?>';
sale.setTotalCost(price);
if(price <= 2000){    sale.setCustomCommission('%8');}
else if(price > 2000 && price <= 3000) { sale.setCustomCommission('%6');}
else if(price > 3000 && price <= 10000) { sale.setCustomCommission('%4'); }
else{sale.setCustomCommission('%2');}
sale.setCurrency('<?=$PDT_DATA['currency']?>');
sale.setOrderID('<?=$PDT_DATA['tracking_id']?>');
sale.setProductID('<?=$PDT_DATA['itemname']?>');
PostAffTracker.register();
</script>
	<?php
}
elseif($paymentStatus == 'Pending'){

			$prLabel 			= array();
			$prLabel['echeck'] 	= $LANG[pending_because];

			echo "<h2 class='order_pending'>".__('We received your Order - however your PayPal Payment is still Pending.','wpShop')."</h2>
			
			<p>".__('Your Payment is Pending because it was made with an eCheck that has not yet been cleared<br/> 
			or you have Sent Money via a Bank Transfer.<br/>As soon as the Transaction is Completed we will begin to Process your Order.','wpShop').
			"<br/>"; echo $prLabel[$PDT_DATA[pending_reason]];	
			
			echo "<h5>".__('Like to Continue Shopping?','wpShop')."<a href='"; echo get_real_base_url('force_http'); echo "'>".
			__('Click here.','wpShop')."</a></h5>";					
			
}
elseif($paymentStatus == 'ERROR'){
	switch($PDT_DATA['error_code']){
	
		case 'PP02':
			echo "<div class='failure'>";
			echo $PDT_DATA['error_message'];
			echo "</div>";
		break;		
		
		case 'PP03':
			echo "<div class='failure'><h5>";
			echo $PDT_DATA['error_message'];
			echo "<br/>XXXXXXXXXX";
			echo __('Please be aware that price spoofing constitutes Internet Fraud.','wpShop');
			echo "<br/>";
			echo __('The shop owner was notified.','wpShop');
			echo "</h5></div>";
			
			//mail to merchant
			$table 	= is_dbtable_there('orders');
			$qStr 	= "SELECT * FROM $table WHERE who = '$PDT_DATA[who]' LIMIT 0,1";
			$res 	= mysql_query($qStr);
			$row 	= mysql_fetch_assoc($res);
			
			$mail_text 		= __('Possible price spoofing was detected.','wpShop')."\n".__('The PayPal amount did not fit the amount in the your database records:','wpShop');
			$mail_text 		.= "\n\n";
			$mail_text 		.= $PDT_DATA[itemname] .__(' has an amount of ','wpShop').$row['amount'].' '.$PDT_DATA[currency];
			$mail_text		.= __(' - however only ','wpShop').$PDT_DATA[amount].' '.$PDT_DATA[currency].__(' was paid!','wpShop');
			$mail_header	= 'FROM: '.$OPTION['wps_shop_email'];
			mail($OPTION['wps_shop_email'],__('Fraudulent Transaction detected',''),$mail_text,$mail_header);
			
			//log in DB 
			$securityMessage 			= array();
			$securityMessage['warning']	= __('PPO3 error - possible price spoofing','wpShop');
			log_payment_data($securityMessage,$PDT_DATA['who']);
			// give order level 9
			$qStr = "UPDATE $table SET level = '9' WHERE who ='$PDT_DATA[who]'";
			mysql_query($qStr);
		break;		
		
		case 'PP04':
			echo "<div class='failure'>";
			echo $PDT_DATA['error_message'];
			echo "</div>";
		break;
	}
}		
else {
			// another case 
			echo "<h2>".__('We received from PayPal the following Payment Status:','wpShop')." $PDT_DATA[payment_status]</h2>				
			<p>".__('Reason might be:','wpShop')." $PDT_DATA[pending_reason]</p>";		
}
?>