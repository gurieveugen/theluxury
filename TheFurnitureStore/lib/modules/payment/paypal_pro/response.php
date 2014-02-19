<?php
include_once(reset(pathinfo(__FILE__)).'/functions.php');
$PDT_PRO_DATA 	= paypal_pro_response();

if($PDT_PRO_DATA['status'] == 'Completed'){

	echo order_step_table(5);
		
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

	// delivery option = pickup? customer needs to know where to pickup 
	if($PDT_PRO_DATA[d_option] == 'pickup'){
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
	
	$custom_tracking = $OPTION['wps_custom_tracking'];
	if($custom_tracking !=''){
		echo $custom_tracking;
	}
	ga_ecommerce_tracking_code($PDT_PRO_DATA['who']);
}
?>