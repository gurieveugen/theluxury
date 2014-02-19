<?php
include 'wp-load.php';
//security check
if(md5(LOGGED_IN_KEY.'-'.NONCE_KEY) != $_GET['pst']){	
	mail($OPTION['wps_shop_email'],'WorldPay error','Please check if your WorldPay callback url is correctly entered.');
	exit("Check WorldPay callback url.");
}
include ABSPATH .'wp-content/themes/'.WPSHOP_THEME_NAME.'/lib/modules/payment/cc_wp/functions.php';	
$WPAY_DATA 	= wpay_response();	
include ABSPATH .'wp-content/themes/'.WPSHOP_THEME_NAME.'/lib/modules/payment/cc_wp/header_confirmation.php';		

		$LANG						= array();
		$LANG['payment_delivery'] 	= __('Payment &amp; Delivery','wpShop');
		$LANG['address'] 			= __('Address','wpShop');
		$LANG['summary'] 			= __('Summary','wpShop');
		$LANG['finished'] 			= __('Finished','wpShop');
				
		$oStep_table_html =  "
			<table cellspacing='0' cellpadding='3' class='oSteps' border='1'>
			<tr>
			<td class='[s1]'><span>1</span>$LANG[payment_delivery]</td>
			<td class='[s2]'><span>2</span>$LANG[address]</td>
			<td class='[s3]'><span>3</span>$LANG[summary]</td>
			<td class='[s4]' style='color:darkgreen;'><span>4</span>$LANG[finished]</td>
			</tr>
			</table>
			";


		if($WPAY_DATA['status'] == 'Y'){
		
			echo "<h2>".__('Thank you for your Purchase!','wpShop')."</h2>";
			echo "<b>".__('Payment Details','wpShop')."</b><br/>";
			echo "<span>".__('Name:','wpShop')." $WPAY_DATA[f_name] $WPAY_DATA[l_name]</span><br/>";
			echo "<span>".__('Item:','wpShop')." $WPAY_DATA[itemname]</span><br/>";
			echo "<span>".__('Order Amount:','wpShop')." $WPAY_DATA[amount] $WPAY_DATA[currency]</span><br/><br/>";
						
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
			<p>".__('If you have an Account with us you may also Track your Order using the Tracking Number you are given in your Confirmation Email. Login to your Account and enter that Number in the field under \'Track Your Order\' in the sidebar.','wpShop').						
			"</p>
			<h4>".__('Print your order?','wpShop')."</h4>
			<p><a href='".get_option('siteurl').'/index.php?display_invoice=1&invoice='.$WPAY_DATA['pdf_bill']."' target='_blank'>$bill_format_label</a></p>
			";
						
			$shop_slug = get_option('wps_shop_slug');
			if(strlen($shop_slug) > 0){
				echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='{$baseurl}/category/"; echo get_option('wps_shop_slug'); echo "'>".__('Click here.','wpShop')."</a></h5>";					
			}else{
				echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='"; echo get_option('home'); echo "'>".__('Click here.','wpShop')."</a></h5>";					
			}
			
		}
		elseif($WPAY_DATA['status'] == 'C'){
			echo __('Your payment was cancelled. Please contact the shop owner to resolve the issue.','wpShop');
			echo ' '."<a href='"; echo get_option('home'); echo "'>".__('Return to Startpage.','wpShop')."</a></h5>";					
		}
		else {
			// another status 
		}
		
include ABSPATH .'wp-content/themes/'.WPSHOP_THEME_NAME.'/lib/modules/payment/cc_wp/footer_confirmation.php';		
?>