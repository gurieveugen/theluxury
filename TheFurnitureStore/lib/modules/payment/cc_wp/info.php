<?php
$pgateway_be_info 		= __('Credit Card Payments with WorldPay.com (For International Merchants)','wpShop');
$pgateway_be_short_label='P: '.__('WorldPay','wpShop');
$pgateway_short_label	= __('Credit Card WorldPay','wpShop');
$pgateway_icon_file		= 'worldpay.png';
$pgateway_step2_label	= $OPTION['wps_wp_label'];
$pgateway_step2_alt		= $OPTION['wps_wp_label'];
$pgateway_be_options 	= array(	
							array (		"type" 	=> "fieldset_start",
							"class" =>"shop",
							"id" 	=>"sec_worldpay_settings"),
									
							array ( 	"name" 	=> __('WorldPay','wpShop'),
								"type" 	=> "title"),
									
							array(    	"type" 	=> "open"),

							array(    	"name" 	=> __('Installation-ID','wpShop'),
										"desc" 	=> __('Enter the installation id you have received from WorldPay.','wpShop'),
										"id" 	=> $CONFIG_WPS['shortname']."_wpay_instId",
										"std" 	=> "1234",
										"type" 	=> "text"),
												
							array(    	"name" 	=> __('Testmode','wpShop'),
										"desc" 	=> __('Ready to go live/public with WorldPay? Change this value to "false".','wpShop'),
										"id" 	=> $CONFIG_WPS['shortname']."_wpay_testmode",
										"std" 	=> "true",
										"vals" 	=> array("false","true"),
										"type" 	=> "select"),					
											
							array(    	"name" 	=> __('Payment Response Url','wpShop'),
										"desc" 	=> __('Enter this url as described here:','wpShop').' '."<a target='_blank' 
										href='http://www.rbsworldpay.com/support/kb/bg/paymentresponse/pr5101.html'>
										http://www.rbsworldpay.com/support/kb/bg/paymentresponse/pr5101.html</a>",
										"id" 	=> $CONFIG_WPS['shortname']."_wp_callback_url", 	
										"vals" 	=> get_option('siteurl').'/wpay.php?pst='.md5(LOGGED_IN_KEY.'-'.NONCE_KEY),
										"type" 	=> "pathinfo2"),
										
							array(    	"name" 	=> __('Link to secure logo file','wpShop'),
										"desc" 	=> __('WorldPays callbacks run over SSL. In order to avoid browser warnings you need to add a link 
										to a logo file which starts with https://','wpShop'),
										"id" 	=> $CONFIG_WPS['shortname']."_wpay_logo_file",
										"std" 	=> "secure-https://-link-to-logo",
										"type" 	=> "text"),
							array(  	"name" 	=> __('"WorldPay.com" Label Text','wpShop'),
										"desc" 	=> __('This will be used for the label text of the "WorldPay.com" Payment Option','wpShop'),
										"id" 	=> $CONFIG_WPS['shortname']."_wp_label",
										"type" 	=> "text",
										"std" 	=> "Credit Card (WorldPay.com)"),
							array(   	"type" => "close"),
							array(   	"type" => "close"),			
							array (		"type" 	=> "fieldset_end")
						);