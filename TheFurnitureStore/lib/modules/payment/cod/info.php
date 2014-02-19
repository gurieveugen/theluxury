<?php
$pgateway_be_info 		= __('Cash on Delivery','wpShop');
$pgateway_be_short_label='P: '.__('Cash on Delivery','wpShop');
$pgateway_short_label	= __('Cash on Delivery','wpShop') . ' ' . $OPTION['wps_cod_who_note'];
$pgateway_icon_file		= 'cod.png';
$pgateway_step2_label	= $OPTION['wps_cod_label'] .' '. $OPTION['wps_cod_who_note'];
$pgateway_step2_alt		= $OPTION['wps_cod_label'] .' '. $OPTION['wps_cod_who_note'];
$pgateway_be_options 	= array(
		array (		"type" 	=> "fieldset_start",
					"class" =>"shop",
					"id" 	=>"sec_cashOnDelivery_settings"),
							
			array ( 	"name" 	=> __('Cash on Delivery','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),

					array(    	"name" 	=> __('Delivery service','wpShop'),
								"desc" 	=> __('Enter the name of the company who will do the cash on delivery service for you. It will be added to the delivery options for your customers to select.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_cod_service",
								"std" 	=> "Deutsche Bundespost",
								"type" 	=> "text"),
										
					array(    	"name" 	=> __('Delivery Service 2','wpShop'),
								"desc" 	=> __('A large Delivery Company may offer more than one delivery option. Here you may specify which one. Leave empty if not using.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_cod_who_note",
								"std" 	=> "Deutsche Bundespost",
								"type" 	=> "text"),
					array(  	"name" 	=> __('"Cash on Delivery" Label Text','wpShop'),
								"desc" 	=> __('This will be used for the label text of the "Cash on Delivery" Payment Option','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_cod_label",
								"type" 	=> "text",
								"std" 	=> "Cash on Delivery"),
				array(   	"type" => "close"),
			array(   	"type" => "close"),
		array (		"type" 	=> "fieldset_end"));