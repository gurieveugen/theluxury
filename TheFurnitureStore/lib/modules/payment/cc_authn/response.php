<?php
include_once('functions.php');
$AUTHN_DATA = authn_response();


	if($AUTHN_DATA['status'] == 1)
	{
			$baseurl			= get_option('siteurl');
			$homeurl			= get_option('home');
			$template_directory = get_bloginfo('template_directory');	
			$themename 			= WPSHOP_THEME_NAME;
		
			echo order_step_table($oStep_table_html,4);			
				
			echo "<h2>".__('Thank you for your Purchase!','wpShop')."</h2>";
			echo "<dl><dt>".__('Payment Details','wpShop'). "</dt>\n";
			echo "<dd>".__('Name:','wpShop')." $AUTHN_DATA[f_name] $AUTHN_DATA[l_name]</dd>\n";
			echo "<dd>".__('Item:','wpShop')." $AUTHN_DATA[itemname]</dd>\n";
			echo "<dd>".__('Order Amount:','wpShop')." $AUTHN_DATA[amount] USD</dd></dl>\n\n";
						
			echo "
			<h3 class='order_success'><span>"
			.__('Your Transaction has been Completed, and a Receipt of your Purchase has been Emailed to you.','wpShop').
			"</span></h3>";
						
						
			// digital goods also present? - lets create dlinks 
			if($AUTHN_DATA[digital_there] == 1){
			
				$DIGITALGOODS 		= load_what_is_needed('digitalgoods');	//change.9.10
			
				$_SESSION[dlinks] 	= $AUTHN_DATA[dlinks];
				$_SESSION[d_option]	= 'download';
				$_SESSION[who]		= $AUTHN_DATA[who];
						
			
				$l_mode			= get_option('wps_l_mode');
				$text_addition1	= ($l_mode == 'GIVE_KEYS' ? __(' and License Keys','wpShop') : NULL);
				$text_addition2	= ($l_mode == 'GIVE_KEYS' ? __('Pls. Copy your License Keys and store them at a safe place.','wpShop') : NULL);

				echo "<div class='dlinks'>
				<h5>".__('These are your Download Links','wpShop')."{$text_addition1}:</h5>
				<img src='{$baseurl}/wp-content/themes/{$themename}/images/download_arrow.png' align='left'/><br/>
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
					echo "<a href='{$baseurl}/{$v}'><strong>$fname</strong></a><br/><br/>";		

					if($l_mode == 'GIVE_KEYS'){
					
						$l_check 			= $_SESSION[keys]["$fname"];											
						$length[$fname] 	= strlen($l_check);
						
						if($length[$fname] < 1){
							$_SESSION['keys'][$fname] = $DIGITALGOODS->get_lkeys($fname,$_SESSION['who']);		//change.9.10
						}
						$keys = explode("#",$_SESSION[keys][$fname]);
						
						_e('&nbsp;&nbsp License Keys: &nbsp;&nbsp','wpShop');
						
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
			
		$custom_tracking = $OPTION['wps_custom_tracking'];
		if($custom_tracking !=''){
			echo $custom_tracking;
		}
		ga_ecommerce_tracking_code($AUTHN_DATA['who']);

	}
	else{}

?>