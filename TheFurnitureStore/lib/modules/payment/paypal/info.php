<?php
$pgateway_be_info 		= __('PayPal Payments Standard','wpShop');
$pgateway_be_short_label="P: <a href='https://www.paypal.com/de/vst/id={$order[txn_id]}' target='_blank' title='Check PayPal txn_id {$order[txn_id]}'>";
$pgateway_be_short_label.= __('PayPal Standard','wpShop')."</a>";
$pgateway_short_label	= __('PayPal','wpShop');
$pgateway_step2_label	= $OPTION['wps_pps_label'];
$pgateway_step2_alt		= $OPTION['wps_pps_label'];
$pgateway_icon_file		= 'pps.png';

$pgateway_be_options =	array(
					array (	"type" 	=> "fieldset_start",
					"class" =>"shop",
					"id" 	=>"sec_paypal_settings"),
							
					array ( 	"name" 	=> __('Paypal Standard','wpShop'),
						"type" 	=> "title"),
							
					array(    	"type" 	=> "open"),

					array(    	"name" 	=> __('Email','wpShop'),
								"desc" 	=> __('Enter your email -it must be the SAME AS the one you use in your PayPal account!','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_paypal_email",
								"std" 	=> "",
								"type" 	=> "text"),
										
					array(    	"name" 	=> __('PDT-Identity Token','wpShop'),
								"desc" 	=> __('Enter your Payment Data Transfer (PDT) Identity Token here - available in your PayPal account (Premier or Business) ','wpShop')."- <a href='https://www.paypal.com/de/cgi-bin/webscr?cmd=_profile-website-payments' target='_blank'>".__('PayPal direct link (you need to be logged in)','wpShop')."</a>",
								"id" 	=> $CONFIG_WPS['shortname']."_paypal_pdttoken",
								"std" 	=> "",
								"type" 	=> "text"),

					array(    	"name" 	=> __('PayPal Encode Key','wpShop'),
								"desc" 	=> __('This is used for additional security on transactions. Set a word string of your own.','wpShop')."<br/><b>".__('You may use letters and numbers but not whitespaces and no symbols!','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_paypal_encode_key",
								"std" 	=> "HastaLaVista",
								"type" 	=> "text"),	
										
					array(    	"name" 	=> __('Path: Return Url','wpShop'),
								"desc" 	=> __('Enter this in your PayPal account under "Profile > Website Payment Preferences > Return Url"','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_confirm_url",
								"std" 	=> get_option('home') . '/?confirm=1',
								"type" 	=> "pathinfo"),

					array(    	"name" 	=> __('Path: IPN Notification Url','wpShop'),
								"desc" 	=> __('Enter this in your PayPal account under "Profile > Instant Payment Notification Preferences > Notification Url"','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_ipn_url", 	
								"vals" 	=> get_option('siteurl') . '/wp-content/themes/'. $CONFIG_WPS['themename'] .'/ipn.php?pst='.md5(LOGGED_IN_KEY.'-'.NONCE_KEY),
								"type" 	=> "pathinfo2"),
					array(  	"name" 	=> __('"PayPal Payments Standard" Label Text','wpShop'),
								"desc" 	=> __('This will be used for the label text of the "PayPal Payments Standard" Payment Option','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pps_label",
								"type" 	=> "text",
								"std" 	=> "PayPal (PayPal Payments Standard)"),					
					array(   	"type" => "close"),
					array(   	"type" => "close"),
					array (		"type" 	=> "fieldset_end"));			