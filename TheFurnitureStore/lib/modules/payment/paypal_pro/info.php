<?php
$pgateway_be_info 		= __('PayPal Payments Pro (Accept Credit Cards directly on your Website - for US, Canada and UK Merchants)','wpShop');
$pgateway_be_short_label='P: '.__('PayPal Pro','wpShop');
$pgateway_short_label	= __('Credit Card - PayPal Pro','wpShop');
$pgateway_icon_file 	= 'ppp.png';
$pgateway_step2_label	= $OPTION['wps_ppp_label'];
$pgateway_step2_alt		= $OPTION['wps_ppp_label'];
$pgateway_be_options 	= array(
	
							array (		"type" 	=> "fieldset_start",
										"class" =>"shop",
										"id" 	=>"sec_paypal_pro_settings"),
							array( 		"name" => __('Paypal PRO','wpShop'),
													"type" => "title"),
															
							array(    	"type" => "open"),
												
								array(    	"name" => __('API username','wpShop'),
											"desc" => __('Enter your API username.','wpShop'),
											"id" => $CONFIG_WPS['shortname']."_paypal_api_user",
											"std" => "",
											"type" => "text"),
													
								array(    	"name" => __('API password','wpShop'),
											"desc" => __('Enter your API password.','wpShop'),
											"id" => $CONFIG_WPS['shortname']."_paypal_api_pw",
											"std" => "",
											"type" => "text"),					
													
								array(    	"name" => __('API signature','wpShop'),
											"desc" => __('Enter your API signature.','wpShop'),
											"id" => $CONFIG_WPS['shortname']."_paypal_api_signature",
											"std" => "",
											"type" => "text"),
								array(  	"name" 	=> __('"PayPal Payments Pro" Label Text','wpShop'),
											"desc" 	=> __('This will be used for the label text of the "PayPal Payments Pro" Payment Option','wpShop'),
											"id" 	=> $CONFIG_WPS['shortname']."_ppp_label",
											"type" 	=> "text",
											"std" 	=> "Credit Card (PayPal Payments Pro)"),														
										array(   	"type" => "close"),
									array(   	"type" => "close"),
									array (		"type" 	=> "fieldset_end"));