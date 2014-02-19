<?php
$pgateway_be_info 		= __('Cheque','wpShop');
$pgateway_be_short_label='P: '.__('Cheque','wpShop');
$pgateway_short_label	= __('Cheque','wpShop');
$pgateway_icon_file		= 'cheque.png';
$pgateway_step2_label	= $OPTION['wps_pcheque_label'];
$pgateway_step2_alt		= $OPTION['wps_pcheque_label'];
$pgateway_be_options 	= $pgateway_be_options 	= array(
							array(		"type" 	=> "fieldset_start",
										"class" =>"shop",
										"id" 	=>"sec_cheque_settings"),
							array( 		"name" 	=> __('Cheque','wpShop'),
										"type" 	=> "title"),										
							array(    	"type" 	=> "open"),
							array(  	"name" 	=> __('"Pay with Cheque" Label Text','wpShop'),
										"desc" 	=> __('This will be used for the label text of the "Cheque" Payment Option','wpShop'),
										"id" 	=> $CONFIG_WPS['shortname']."_pcheque_label",
										"type" 	=> "text",
										"std" 	=> "Pay with Cheque"),
							array(   	"type" => "close"),
							array(   	"type" => "close"),
							array(		"type" 	=> "fieldset_end"));