<?php
$pgateway_be_info 		= __('Bank Transfer in Advance','wpShop');
$pgateway_be_short_label= 'P: '.__('Bank Transfer','wpShop');
$pgateway_be_short_label.="<br/><a href='http://$OPTION[wps_online_banking_url]' target='_blank' title='".__('Url to your Online Banking','wpShop')."'>";
$pgateway_be_short_label.=__('Check Account','wpShop')."</a>";
$pgateway_short_label	= __('Bank Transfer in Advance','wpShop');
$pgateway_icon_file		= 'bt.png';
$pgateway_step2_label	= $OPTION['wps_bt_label'];
$pgateway_step2_alt		= $OPTION['wps_bt_label'];
$pgateway_be_options 	= array(

							array (		"type" 	=> "fieldset_start",
										"class" =>"shop",
										"id" 	=>"sec_banktransfer_settings"),
												
							array ( 	"name" 	=> __('Bank Transfer','wpShop'),
										"type" 	=> "title"),
											
								array(    	"type" 	=> "open"),

									array(    	"name" 	=> __('Name of Bank','wpShop'),
												"desc" 	=> __('Enter the name of your Bank.','wpShop'),
												"id" 	=> $CONFIG_WPS['shortname']."_banktransfer_bankname",
												"std" 	=> "",
												"type" 	=> "text"),
												
									array(  	"name" 	=> __('Display Routing Number','wpShop'),
												"desc" 	=> __('Check this setting if you want to display your Bank\'s Routing Number','wpShop'),
												"id" 	=> $CONFIG_WPS['shortname']."_banktransfer_routing_enable",
												"type" 	=> "checkbox",
												"std" 	=> "false"),
												
									array(  	"name" 	=> __('Routing Number Text','wpShop'),
												"desc" 	=> __('Here you can enter the Routing number text or initials commonly used in your country.','wpShop'),
												"id" 	=> $CONFIG_WPS['shortname']."_banktransfer_routing_text",
												"std" 	=> "Routing Number",
												"type" 	=> "text"),
														
									array(    	"name" 	=> __('Routing Number','wpShop'),
												"desc" 	=> __('Enter your Bank\'s Routing number','wpShop'),
												"id" 	=> $CONFIG_WPS['shortname']."_banktransfer_bankno",
												"std" 	=> "",
												"type" 	=> "text"),					
														
									array(    	"name" 	=> __('Account Number','wpShop'),
												"desc" 	=> __('Enter your Bank Account Number.','wpShop'),
												"id" 	=> $CONFIG_WPS['shortname']."_banktransfer_accountno",
												"std" 	=> "",
												"type" 	=> "text"),
														
									array(    	"name" 	=> __('Bank Account Owner','wpShop'),
												"desc" 	=> __('Enter the name of the person/company/institution who is the official Owner of the Bank Account.','wpShop'),
												"id" 	=> $CONFIG_WPS['shortname']."_banktransfer_account_owner",
												"std" 	=> "",
												"type" 	=> "text"),

									array(    	"name" 	=> __('IBAN','wpShop'),
												"desc" 	=> __('Enter your IBAN code. Leave empty if not needed.','wpShop'),
												"id" 	=> $CONFIG_WPS['shortname']."_banktransfer_iban",
												"std" 	=> "",
												"type" 	=> "text"),					
														
									array(    	"name" 	=> __('BIC/SWIFT','wpShop'),
												"desc" 	=> __('Enter your BIC code - also called SWIFT. Leave empty if not needed.','wpShop'),
												"id" 	=> $CONFIG_WPS['shortname']."_banktransfer_bic",
												"std" 	=> "",
												"type" 	=> "text"),
														
														
									array(    	"name" 	=> __('Url to your Online Banking','wpShop'),
												"desc" 	=> __('Enter here your Url to your Online Banking in the form of www.bank.com - makes checking your bank account a bit easier.','wpShop'),
												"id" 	=> $CONFIG_WPS['shortname']."_online_banking_url",
												"std" 	=> "www.bankofengland.co.uk",
												"type" 	=> "text"),					
						array(  	"name" 	=> __('"Bank Transfer" Label Text','wpShop'),
									"desc" 	=> __('This will be used for the label text of the "Bank Transfer" Payment Option','wpShop'),
									"id" 	=> $CONFIG_WPS['shortname']."_bt_label",
									"type" 	=> "text",
									"std" 	=> "Bank Transfer"),
						array(   	"type" => "close"),
						array(   	"type" => "close"),
						array (		"type" 	=> "fieldset_end"));