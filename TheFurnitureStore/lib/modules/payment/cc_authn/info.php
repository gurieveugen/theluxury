<?php
$pgateway_be_info 		= __('Credit Card Payments with Authorize.net (Only for Merchants with a US Bank Account)','wpShop');
$pgateway_be_short_label='P: '.__('Authorize.net','wpShop');
$pgateway_short_label	= __('Credit Card Authorize.net','wpShop');
$pgateway_icon_file		= 'authorize.png';
$pgateway_step2_label	= $OPTION['wps_auth_label'];
$pgateway_step2_alt		= $OPTION['wps_auth_label'];
$pgateway_be_options 	= array(
							array (		"type" 	=> "fieldset_start",
										"class" =>"shop",
										"id" 	=>"sec_authorize_settings"),
												
								array ( 	"name" 	=> __('Authorize.net','wpShop'),
											"type" 	=> "title"),
												
								array(    	"type" 	=> "open"),
								
									array(    	"name" 	=> __('API Login','wpShop'),
												"desc" 	=> __('Enter your API Login provided by Authorize.net','wpShop'),
												"id" 	=> $CONFIG_WPS['shortname']."_authn_api_login",
												"std" 	=> "",
												"type" 	=> "text"),
														
									array(    	"name" 	=> __('Transaction Key','wpShop'),
												"desc" 	=> __('Enter the transaction Key you received from Authorize.net','wpShop'),
												"id" 	=> $CONFIG_WPS['shortname']."_authn_transaction_key",
												"std" 	=> "",
												"type" 	=> "text"),					
														
									array(    	"name" 	=> __('Url','wpShop'),
												"desc" 	=> __('Change to "https://secure.authorize.net/gateway/transact.dll" when you are ready to go ','wpShop')."<b>".__('live/public','wpShop')."</b>",
												"id" 	=> $CONFIG_WPS['shortname']."_authn_url",
												"std" 	=> "https://test.authorize.net/gateway/transact.dll",
												"vals" 	=> array("https://secure.authorize.net/gateway/transact.dll","https://test.authorize.net/gateway/transact.dll"),
												"type" 	=> "select"),	
														
									array(    	"name" 	=> __('Test-Request','wpShop'),
												"desc" 	=> __('Change to "true" if you want to test your ','wpShop')."<b>".__('live/public','wpShop')."</b>".__(' Authorize.net account - otherwise leave on "false".','wpShop'),
												"id" 	=> $CONFIG_WPS['shortname']."_authn_test_request",
												"std" 	=> "false",
												"vals" 	=> array("false","true"),
												"type" 	=> "select"),
									array(  	"name" 	=> __('"Authorize.net" Label Text','wpShop'),
												"desc" 	=> __('This will be used for the label text of the "Authorize.net" Payment Option','wpShop'),
												"id" 	=> $CONFIG_WPS['shortname']."_auth_label",
												"type" 	=> "text",
												"std" 	=> "Credit Card (Authorize.net)"),
														
									array(   	"type" => "close"),	
								array(   	"type" => "close"),
							array (		"type" 	=> "fieldset_end"));