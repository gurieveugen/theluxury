<?php

	//what mysql version do we have? 		
	$version = mysql_get_server_info();
	
	if ((strpos($version,'4.0.27')) !== FALSE) {  // some changes when version 4.0.27 is  used 
		$collate1 = NULL;			
		$collate2 = NULL;
	}
	else {
		$collate1 = 'COLLATE utf8_general_ci';								
		$collate2 = 'DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci';		
	}


	switch($table_name)
	{
	
		case 'canadian_tax':
		
			$qStr = "
			CREATE TABLE $table (
				`tid` tinyint(4) NOT NULL auto_increment,
				`province` varchar(255) $collate1 default NULL,
				`tax` varchar(255) $collate1 default NULL,
				PRIMARY KEY  (`tid`)
			) ENGINE=MyISAM  $collate2 AUTO_INCREMENT=1 ;
			";	
			
			$qStr2 = "
			INSERT INTO $table (`tid`, `province`, `tax`) VALUES
				(1, 'AB', '5.00'),
				(2, 'BC', '5.00'),
				(3, 'MB', '5.00'),
				(4, 'NB', '13.00'),
				(5, 'NL', '13.00'),
				(6, 'NT', '5.00'),
				(7, 'NS', '13.00'),
				(8, 'NU', '5.00'),
				(9, 'PE', '5.00'),
				(10, 'SK', '5.00'),
				(11, 'ON', '13.00'),
				(12, 'QC', '5.00'),
				(13, 'YT', '5.00');	
			";	
		break;
	
	
		case 'delivery_addr':
		
			$qStr = " 
				CREATE TABLE $table (
					`aid` int(11) NOT NULL auto_increment,
					`who` varchar(255) $collate1 NOT NULL,
					`l_name` varchar(255) $collate1 NOT NULL,
					`f_name` varchar(255) $collate1 NOT NULL,
					`street` varchar(255) $collate1 NOT NULL,
					`hsno` varchar(255) $collate1 NOT NULL,
					`strno` varchar(255) $collate1 NOT NULL,
					`strnam` varchar(255) $collate1 NOT NULL,
					`po` varchar(255) $collate1 NOT NULL,
					`pb` varchar(255) $collate1 NOT NULL,
					`pzone` varchar(255) $collate1 NOT NULL,
					`crossstr` varchar(255) $collate1 NOT NULL,
					`colonyn` varchar(255) $collate1 NOT NULL,
					`district` varchar(255) $collate1 NOT NULL,
					`region` varchar(255) $collate1 NOT NULL,
					`state` varchar(255) $collate1 NOT NULL,
					`zip` varchar(10) $collate1 NOT NULL,
					`town` varchar(255) $collate1 NOT NULL,
					`country` varchar(125) $collate1 NOT NULL,
					`level` enum('0','1','2','3') $collate1 NOT NULL default '1',
					PRIMARY KEY  (`aid`)
				) ENGINE=MyISAM  $collate2 AUTO_INCREMENT=1 ;
			";	
		break;


		case 'inventory':
		
			$qStr = " 
				CREATE TABLE $table (
					`iid` int(11) NOT NULL auto_increment,
					`Size` varchar(255) $collate1 NOT NULL,
					`Material` varchar(255) $collate1 NOT NULL,
					`Colour` varchar(255) $collate1 NOT NULL,
					`Duration` varchar(255) $collate1 NOT NULL,
					`ID_item` varchar(255) $collate1 default NULL,
					`amount` int(11) default NULL,
					PRIMARY KEY  (`iid`)
				) ENGINE=MyISAM  $collate2 AUTO_INCREMENT=1 ;
			";	
		break;
		
		case 'personalize':
		
			$qStr = " 
				CREATE TABLE $table (
					`pers_id` int(11) NOT NULL auto_increment,
					`cid` int(11) NOT NULL,
					`pers_name` varchar(255) $collate1 NOT NULL,
					`pers_label` text $collate1 NOT NULL,
					`pers_value` text $collate1 NOT NULL,
					PRIMARY KEY  (`pers_id`)
				) ENGINE=MyISAM  $collate2 AUTO_INCREMENT=1 ;
			";	
		break;



		case 'pppro_errors':
		
			$qStr = " 
				CREATE TABLE $table (
					`eid` int(11) NOT NULL auto_increment,
					`timestamp` varchar(255) $collate1 NOT NULL,
					`who` varchar(255) $collate1 NOT NULL,
					`errors` varchar(255) $collate1 NOT NULL,
					PRIMARY KEY  (`eid`)
				) ENGINE=MyISAM  $collate2 AUTO_INCREMENT=1 ;
			";	
		break;
		
		case 'pppro_fcheck':
		
			$qStr = " 
				CREATE TABLE $table (
					`cid` int(11) NOT NULL auto_increment,
					`who` varchar(255) $collate1 NOT NULL,
					`transid` varchar(255) $collate1 NOT NULL,
					PRIMARY KEY  (`cid`)
				) ENGINE=MyISAM  $collate2 AUTO_INCREMENT=1 ;
			";	
		break;
		
		case 'invoices':
		
			$qStr = " 
				CREATE TABLE $table (
					`inv_id` int(11) NOT NULL auto_increment,
					`oid` int(11) NOT NULL,
					`no` int(11) NOT NULL,
					PRIMARY KEY  (`inv_id`)
				) ENGINE=MyISAM  $collate2 AUTO_INCREMENT=1 ;
			";	
			
			$qStr2 = "
			INSERT INTO $table (`inv_id`, `oid`, `no`) VALUES (1,0,0);
			";
		break;

		
		case 'shopping_cart':
		
			$qStr = " 
			CREATE TABLE $table (
				`cid` int(11) NOT NULL auto_increment,
				`item_id` varchar(255) $collate1 NOT NULL,
				`postID` int(11) NOT NULL,
				`item_name` varchar(255) $collate1 NOT NULL,
				`item_amount` int(11) NOT NULL,
				`item_price` decimal(12,2) NOT NULL,
				`item_weight` decimal(10,2) NOT NULL,
				`item_thumb` varchar(255) $collate1 NOT NULL,
				`item_file` varchar(255) $collate1 NOT NULL default 'none',
				`item_attributs` text $collate1 NOT NULL,
				`item_personal` varchar(50) $collate1 NOT NULL,
				`who` varchar(255) $collate1 NOT NULL,
				`level` enum('0','1','2') $collate1 NOT NULL default '1',
				PRIMARY KEY  (`cid`)
			) ENGINE=MyISAM  $collate2 AUTO_INCREMENT=1 ;				
			";
		break;
					
					
		case 'shopping_cart_log':
		
			$qStr = " 
			CREATE TABLE $table (
				`cid` int(11) NOT NULL,
				`item_id` varchar(255) $collate1 NOT NULL,
				`postID` int(11) NOT NULL,
				`item_name` varchar(255) $collate1 NOT NULL,
				`item_amount` int(11) NOT NULL,
				`item_price` decimal(12,2) NOT NULL,
				`item_weight` int(11) NOT NULL,
				`item_thumb` varchar(255) $collate1 NOT NULL,
				`item_file` varchar(255) $collate1 NOT NULL default 'none',
				`item_attributs` text $collate1 NOT NULL,
				`item_personal` varchar(50) $collate1 NOT NULL,
				`who` varchar(255) $collate1 NOT NULL,
				`level` enum('0','1','2') $collate1 NOT NULL default '1',
				PRIMARY KEY  (`cid`)
			) ENGINE=MyISAM  $collate2;				
			";
		break;
		
		
		case 'orders':
		
			$qStr = "
			CREATE TABLE $table (
				`oid` int(11) NOT NULL auto_increment,
				`who` varchar(255) $collate1 NOT NULL,
				`l_name` varchar(255) $collate1 NOT NULL,
				`f_name` varchar(255) $collate1 NOT NULL,
				`street` varchar(255) $collate1 NOT NULL,
				`hsno` varchar(255) $collate1 NOT NULL,	
				`strno` varchar(255) $collate1 NOT NULL,
				`strnam` varchar(255) $collate1 NOT NULL,
				`po` varchar(255) $collate1 NOT NULL,
				`pb` varchar(255) $collate1 NOT NULL,
				`pzone` varchar(255) $collate1 NOT NULL,
				`crossstr` varchar(255) $collate1 NOT NULL,
				`colonyn` varchar(255) $collate1 NOT NULL,
				`district` varchar(255) $collate1 NOT NULL,
				`region` varchar(255) $collate1 NOT NULL,
				`state` varchar(255) $collate1 NOT NULL,
				`zip` varchar(10) $collate1 NOT NULL, 
				`town` varchar(255) $collate1 NOT NULL,				  
				`country` varchar(125) $collate1 NOT NULL,
				`d_addr` enum('0','1') collate latin1_german2_ci NOT NULL default '0',
				`email` varchar(120) $collate1 NOT NULL,
				`telephone` varchar(120) $collate1 NOT NULL,
				`custom_note` text character set utf8 NOT NULL,
				`weight` varchar(10) $collate1 NOT NULL,
				`net` decimal(12,2) NOT NULL,
				`shipping_fee` decimal(12,2) NOT NULL,
				`tax` varchar(50) $collate1 NOT NULL,
				`amount` decimal(12,2) NOT NULL,
				`d_option` varchar(20) $collate1 NOT NULL,
				`p_option` varchar(20) $collate1 NOT NULL,
				`txn_id` varchar(255) $collate1 NOT NULL default '1',
				`pending_r` varchar(255) $collate1 NOT NULL default 'na',
				`terms` enum('0','1') $collate1 NOT NULL default '0',
				`dlinks_sent` int(11) NOT NULL default '0',
				`voucher` varchar(255) $collate1 NOT NULL default 'non',
				`tracking_id` varchar(20) $collate1 NOT NULL,
				`order_time` varchar(40) $collate1 NOT NULL,
				`level` enum('0','1','2','3','4','5','6','7','8') $collate1 NOT NULL default '1',
				PRIMARY KEY  (`oid`)
			) ENGINE=MyISAM  $collate2 AUTO_INCREMENT=1 
			";
		break;

		case 'inquiries':
		
			$qStr = "
			CREATE TABLE $table (
				`oid` int(11) NOT NULL auto_increment,
				`who` varchar(255) $collate1 NOT NULL,
				`l_name` varchar(255) character set utf8 NOT NULL,
				`f_name` varchar(255) $collate1 NOT NULL,
				`street` varchar(255) $collate1 NOT NULL,
				`hsno` varchar(255) $collate1 NOT NULL,
				`strno` varchar(255) $collate1 NOT NULL,
				`strnam` varchar(255) $collate1 NOT NULL,
				`po` varchar(255) $collate1 NOT NULL,
				`pb` varchar(255) $collate1 NOT NULL,
				`pzone` varchar(255) $collate1 NOT NULL,
				`crossstr` varchar(255) $collate1 NOT NULL,
				`colonyn` varchar(255) $collate1 NOT NULL,
				`district` varchar(255) $collate1 NOT NULL,
				`region` varchar(255) $collate1 NOT NULL,
				`state` varchar(255) $collate1 NOT NULL,
				`zip` varchar(10) $collate1 NOT NULL,
				`town` varchar(255) $collate1 NOT NULL,
				`country` varchar(125) $collate1 NOT NULL,
				`d_addr` enum('0','1') $collate1 NOT NULL default '0',
				`email` varchar(120) $collate1 NOT NULL,
				`telephone` varchar(120) $collate1 NOT NULL,
				`custom_note` text $collate1 NOT NULL,
				`weight` varchar(10) $collate1 NOT NULL,
				`net` decimal(12,2) NOT NULL,
				`shipping_fee` decimal(12,2) NOT NULL,
				`tax` varchar(50) $collate1 NOT NULL,
				`amount` decimal(12,2) NOT NULL,
				`d_option` varchar(20) $collate1 NOT NULL,
				`p_option` varchar(20) $collate1 NOT NULL,
				`terms` enum('0','1') $collate1 NOT NULL default '0',
				`email_sent` tinyint(4) NOT NULL default '0',
				`tracking_id` varchar(20) $collate1 NOT NULL,
				`inquiry_time` varchar(40) $collate1 NOT NULL,
				`level` enum('0','1','2','3','4','5','6','7','8') $collate1 NOT NULL default '1',
				PRIMARY KEY  (`oid`)
			) ENGINE=MyISAM  $collate2 AUTO_INCREMENT=1 
			";	
		break;
		
		case 'ipn':
		
			$qStr = " 
			CREATE TABLE $table (
				`ipn_id` int(11) NOT NULL auto_increment,
				`txn_id` varchar(255) $collate1 NOT NULL,
				`who` varchar(255) $collate1 NOT NULL,
				`status` varchar(255) $collate1 NOT NULL,
				`tstamp` varchar(70) $collate1 NOT NULL,
				PRIMARY KEY  (`ipn_id`)
			) ENGINE=MyISAM  $collate2 AUTO_INCREMENT=1
			";

		break;
		

		case 'log_payment':
			$qStr = " 
			CREATE TABLE $table (
			  `log_id` int(11) NOT NULL auto_increment,
			  `payment_mod` varchar(255) character set utf8 NOT NULL,
			  `log_data` text character set utf8 NOT NULL,
			  `cart_items` text character set utf8 NOT NULL,
			  `who` varchar(255) character set utf8 NOT NULL,
			  `tstamp` varchar(70) character set utf8 NOT NULL,
			  PRIMARY KEY  (`log_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 
			";
		break;		
		
		
		
		case 'dlinks':
			$qStr = " 
				CREATE TABLE $table (
					`did` int(11) NOT NULL auto_increment,
					`dfile` varchar(255) $collate1 NOT NULL,					  
					`dlink` text $collate1 NOT NULL,
					`who` varchar(255) $collate1 NOT NULL,
					`tstamp` varchar(255) $collate1 NOT NULL,
					`duration` varchar(255) $collate1 NOT NULL,
					`counter` int(11) NOT NULL default '0',
					PRIMARY KEY  (`did`)
				) ENGINE=MyISAM  $collate2 AUTO_INCREMENT=1
			";
		break;

		
		case 'lkeys':
			$qStr = "
				CREATE TABLE $table (
					`lid` int(11) NOT NULL auto_increment,
					`filename` varchar(255) $collate1 NOT NULL,
					`lkey` varchar(255) $collate1 NOT NULL,
					`used` enum('0','1') $collate1 NOT NULL default '0',
					`who` varchar(255) $collate1 NOT NULL,
					PRIMARY KEY  (`lid`)
				) ENGINE=MyISAM  $collate2 AUTO_INCREMENT=1		
			";
		break;

		case 'feusers':
			$qStr = "
				CREATE TABLE $table (
					`uid` int(11) NOT NULL auto_increment,
					`uname` varchar(255) $collate1 NOT NULL,
					`pw` varchar(255) $collate1 NOT NULL,
					`email` varchar(255) $collate1 NOT NULL,
					`fname` varchar(255) $collate1 NOT NULL,
					`lname` varchar(255) $collate1 NOT NULL,
					`street` varchar(255) $collate1 NOT NULL,
					`hsno` varchar(255) $collate1 NOT NULL,
					`strno` varchar(255) $collate1 NOT NULL,
					`strnam` varchar(255) $collate1 NOT NULL,
					`po` varchar(255) $collate1 NOT NULL,
					`pb` varchar(255) $collate1 NOT NULL,
					`pzone` varchar(255) $collate1 NOT NULL,
					`crossstr` varchar(255) $collate1 NOT NULL,
					`colonyn` varchar(255) $collate1 NOT NULL,
					`district` varchar(255) $collate1 NOT NULL,
					`region` varchar(255) $collate1 NOT NULL,
					`state` varchar(255) $collate1 NOT NULL,
					`zip` varchar(255) $collate1 NOT NULL,
					`town` varchar(255) $collate1 NOT NULL,
					`country` varchar(255) $collate1 NOT NULL,	
					`since` date NOT NULL,
					`login_attempts` tinyint(4) NOT NULL default '0',
					`no_login_success` datetime NOT NULL,
					`last_login` datetime NOT NULL,
					`level` enum('0','1','2') $collate1 NOT NULL default '0',
					PRIMARY KEY  (`uid`)
				) ENGINE=MyISAM  $collate2 AUTO_INCREMENT=1			
			";
		break;			
		
		
		case 'vouchers':
			$qStr = "
				CREATE TABLE $table (
					`vid` int(11) NOT NULL auto_increment,
					`vcode` varchar(255) $collate1 NOT NULL,
					`voption` enum('P','A') $collate1 NOT NULL,
					`vamount` varchar(10) $collate1 NOT NULL,
					`receiver` varchar(255) $collate1 NOT NULL,
					`receiver_mail` varchar(70) $collate1 NOT NULL,
					`duration` enum('1time','indefinite') $collate1 NOT NULL default '1time',
					`used` enum('0','1') $collate1 NOT NULL default '0',
					`time_used` varchar(255) $collate1 NOT NULL,
					`time_issued` varchar(100) $collate1 NOT NULL,
					`who` varchar(255) $collate1 NOT NULL,
					`level` enum('0','1','2') $collate1 NOT NULL default '0',
					PRIMARY KEY  (`vid`)
				) ENGINE=MyISAM  $collate2 AUTO_INCREMENT=1	
			";
			
		break;				
		
		
		case 'wishlist':
			$qStr = "
				CREATE TABLE $table (
					`wid` int(11) NOT NULL auto_increment,
					`item_id` varchar(255) $collate1 NOT NULL,
					`postID` int(11) NOT NULL,
					`item_name` varchar(255) $collate1 NOT NULL,
					`item_amount` int(11) NOT NULL default '1',
					`item_price` decimal(12,2) NOT NULL,
					`item_weight` int(11) NOT NULL,
					`item_thumb` varchar(255) $collate1 NOT NULL,
					`item_file` varchar(255) $collate1 NOT NULL default 'none',
					`buy_now` varchar(225) $collate1 NOT NULL,
					`item_attributs` text $collate1 NOT NULL,
					`item_personal` text $collate1 NOT NULL,
					`uid` varchar(255) $collate1 NOT NULL,
					`level` enum('0','1','2') $collate1 NOT NULL default '1',
					PRIMARY KEY  (`wid`)
				) ENGINE=MyISAM  $collate2 AUTO_INCREMENT=1
			";
			
		break;	

	
		case 'countries':
			$qStr = "
				CREATE TABLE IF NOT EXISTS $table (
				  `countryid` int(11) NOT NULL AUTO_INCREMENT,
				  `country` varchar(255) $collate1 NOT NULL,
				  `de` varchar(255) $collate1 NOT NULL,
				  `fr` varchar(255) $collate1 NOT NULL,
				  `abbr` char(5) $collate1 NOT NULL,
				  `zone` tinyint(4) NOT NULL DEFAULT '0',
				  `address_format` varchar(255) $collate1 NOT NULL DEFAULT '1',
				  `states` text $collate1 NOT NULL,
				  `display_state_list` tinyint(4) NOT NULL DEFAULT '0',
				  PRIMARY KEY (`countryid`)
				) ENGINE=MyISAM  $collate2 AUTO_INCREMENT=1 ";
			
			$qStr2 = "
			INSERT INTO $table VALUES(1, 'AFGHANISTAN', 'AFGHANISTAN', 'AFGHANISTAN ', 'AF', 0, 'NAME#%STREET%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(3, 'ALBANIA', 'ALBANIEN', 'ALBANIE', 'AL', 0, 'NAME#%STREET%#%ZIP%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(4, 'ALGERIA', 'ALGERIEN', 'ALGÉRIE', 'DZ', 0, 'NAME#%STREET%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(5, 'AMERICAN SAMOA', 'AMERICAN SAMOA', 'SAMOA AMÉRICAINES', 'AS', 0, 'NAME#%STREET%#%PLACE% %STATE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(6, 'ANDORRA', 'ANDORRA', 'ANDORRE', 'AD', 0, 'NAME#%STREET%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(7, 'ANGOLA', 'ANGOLA', 'ANGOLA', 'AO', 0, 'NAME#%STREET%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(8, 'ANGUILLA', 'ANGUILLA', 'ANGUILLA', 'AI', 0, 'NAME#%STREET%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(9, 'ANTARCTICA', 'ANTARKTIS', 'ANTARCTIQUE', 'AQ', 0, 'NAME#%STREET%#%PLACE% %STATE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(10, 'ANTIGUA AND BARBUDA', 'ANTIGUA UND BARBUDA', 'ANTIGUA ET BARBUDA', 'AG', 0, 'NAME#%STREET%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(11, 'ARGENTINA', 'ARGENTINIEN', 'ARGENTINE', 'AR', 0, 'NAME#%STREET%#%STATE%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(12, 'ARMENIA', 'ARMENIEN', 'ARMÉNIE', 'AM', 0, 'NAME#%STREET%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(13, 'ARUBA', 'ARUBA', 'ARUBA', 'AW', 0, 'name#%street%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(14, 'AUSTRALIA', 'AUSTRALIEN', 'AUSTRALIE', 'AU', 0, 'NAME#%STREET%#%PLACE% %STATE% %ZIP%#%COUNTRY%', 'ACT|Australian Capital Territory#NSW|New South Wales#NT|Northern Territory#QLD|Queensland#SA|South Australia#TAS|Tasmania#VIC|Victoria#WA|Western Australia', 0)+
			INSERT INTO $table VALUES(15, 'AUSTRIA', 'ÖSTERREICH', 'AUTRICHE', 'AT', 0, 'NAME#%STREET%#%ZIP% %PLACE%#%COUNTRY%', 'B|Burgenland#K|K&auml;rnten#N&Ouml;|Nieder&ouml;sterreich#O&Ouml;|Ober&ouml;sterreich#S|Salzburg#ST|Steiermark#T|Tirol#V|Vorarlberg#W|Wien', 0)+
			INSERT INTO $table VALUES(16, 'AZERBAIJAN', 'ASERBAIDSCHAN', 'AZERBAÏDJAN', 'AZ', 0, 'NAME#%STREET%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(17, 'BAHAMAS', 'BAHAMAS', 'BAHAMAS', 'BS', 0, 'name#%street%#%PLACE%, %STATE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(18, 'BAHRAIN', 'BAHRAIN', 'BAHREÏN', 'BH', 0, 'name#%hsno%#%strno%#%PLACE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(19, 'BANGLADESH', 'BANGLADESCH', 'BANGLADESH', 'BD', 0, 'name#%street%#%PO%#%PLACE% - %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(20, 'BARBADOS', 'BARBADOS', 'BARBADE', 'BB', 0, 'NAME#%STREET%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(21, 'BELARUS', 'WEISSRUSSLAND', 'BÉLARUS', 'BY', 0, 'NAME#%STREET%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(22, 'BELGIUM', 'BELGIEN', 'BELGIQUE', 'BE', 0, 'NAME#%STREET%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(23, 'BELIZE', 'BELIZE', 'BELIZE', 'BZ', 0, 'name#%street%#%PLACE%#%STATE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(24, 'BENIN', 'BENIN', 'BÉNIN', 'BJ', 0, 'NAME#%STREET%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(25, 'BERMUDA', 'BERMUDA', 'BERMUDES', 'BM', 0, 'name#%street%#%PLACE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(26, 'BHUTAN', 'BHUTAN', 'BHOUTAN', 'BT', 0, 'name#%street%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(27, 'BOLIVIA', 'BOLIVIEN', 'BOLIVIE, l''ÉTAT PLURINATIONAL DE', 'BO', 0, 'NAME#%STREET%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(28, 'BOSNIA AND HERZEGOVINA', 'BOSNIEN UND HERZEGOWINA', 'BOSNIE-HERZÉGOVINE', 'BA', 0, 'NAME#%STREET%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(29, 'BOTSWANA', 'BOTSWANA', 'BOTSWANA', 'BW', 0, 'NAME#%STREET%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(30, 'BOUVET ISLAND', 'BOUVET ISLAND', 'BOUVET, ÎLE', 'BV', 0, 'NAME#%STREET%#%PLACE% %STATE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(31, 'BRAZIL', 'BRASILIEN', 'BRÉSIL', 'BR', 0, 'name#%street%#%PLACE%-%STATE%#%COUNTRY%', 'AC|Acre#AL|Alagoas#AM|Amazonas#AP|Amapá#BA|Bahia#CE|Ceará#DF|Distrito Federal#ES|Espírito Santo#GO|Goiás#MA|Maranhão#MG|Minas Gerais#MS|Mato Grosso do Sul#MT|Mato Grosso#PA|Pará#PB|Paraíba#PE|Pernambuco#PI|Piauí#PR|Paraná#RJ|Rio de Janeiro#RN|Rio Grande do Norte#RO|Rondônia#RR|Roraima#RS|Rio Grande do Sul#SC|Santa Catarina#SE|Sergipe#SP|São Paulo#TO|Tocantins', 1)+
			INSERT INTO $table VALUES(32, 'BRITISH INDIAN OCEAN TERRITORY', 'BRITISH INDIAN OCEAN TERRITORY', 'OCÉAN INDIEN, TERRITOIRE BRITANNIQUE DE L´OCÉAN INDIEN', 'IO', 0, 'NAME#%STREET%#%PLACE% %STATE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(33, 'BRUNEI DARUSSALAM', 'BRUNEI', 'BRUNÉI DARUSSALAM', 'BN', 0, 'NAME#%STREET%#%PLACE%#%STATE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(34, 'BULGARIA', 'BULGARIEN', 'BULGARIE', 'BG', 0, 'NAME#%STREET%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(35, 'BURKINA FASO', 'BURKINA FASO', 'BURKINA FASO', 'BF', 0, 'NAME#%STREET%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(36, 'BURUNDI', 'BURUNDI', 'BURUNDI', 'BI', 0, 'NAME#%STREET%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(37, 'CAMBODIA', 'KAMBODSCHA', 'CAMBODGE', 'KH', 0, 'name#%street%#%PLACE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(38, 'CAMEROON', 'KAMERUN', 'CAMEROUN', 'CM', 0, 'NAME#%STREET%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(39, 'CANADA', 'KANADA', 'CANADA', 'CA', 0, 'NAME#%STREET%#%PLACE% %STATE% %ZIP%#%COUNTRY%', 'AB|Alberta#BC|British Columbia#MB|Manitoba#NB|New Brunswick#NL|Newfoundland and Labrador#NT|Northwest Territories#NS|Nova Scotia#NU|Nunavut#PE|Prince Edward Island#SK|Saskatchewan#ON|Ontario#QC|Quebec#YT|Yukon', 1)+
			INSERT INTO $table VALUES(40, 'CAPE VERDE', 'KAP VERDE', 'CAP-VERT', 'CV', 0, 'name#%street%#%ZIP% %PLACE%#%STATE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(41, 'CAYMAN ISLANDS', 'CAYMAN ISLANDS', 'CAÏMANES, ÎLES', 'KY', 0, 'NAME#%STREET%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(42, 'CENTRAL AFRICAN REPUBLIC', 'ZENTRALAFRIKANISCHE REPUBLIK', 'CENTRAFRICAINE, RÉPUBLIQUE', 'CF', 0, 'NAME#%STREET%#%ZIP%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(43, 'CHAD', 'TSCHAD', 'TCHAD', 'TD', 0, 'NAME#%STREET%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(44, 'CHILE', 'CHILE', 'CHILI', 'CL', 0, 'name#%street%#%ZIP% %PLACE%#%STATE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(45, 'CHINA', 'CHINA', 'CHINE', 'CN', 0, 'name#%street%, %place%#%ZIP% %STATE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(46, 'CHRISTMAS ISLAND', 'CHRISTMAS ISLAND', 'CHRISTMAS, ÎLE', 'CX', 0, 'NAME#%STREET%#%PLACE% %STATE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(47, 'COCOS (KEELING) ISLANDS', 'COCOS (KEELING) ISLANDS', 'COCOS (KEELING) ÎLES', 'CC', 0, 'NAME#%STREET%#%PLACE% %STATE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(48, 'COLOMBIA', 'KOLUMBIEN', 'COLOMBIE', 'CO', 0, 'name#%street%#%PLACE%-%STATE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(49, 'COMOROS', 'KOMOREN', 'COMORES', 'KM', 0, 'NAME#%STREET%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(50, 'CONGO', 'KONGO', 'CONGO', 'CG', 0, 'NAME#%STREET%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(51, 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'KONGO, DEMOKRATISCHE REPUBLIK', 'CONGO, LA RÉPUBLIQUE DÉMOCRATIQUE DU', 'CD', 0, 'NAME#%STREET%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(52, 'COOK ISLANDS', 'COOK ISLANDS', 'COOK, ÎLES', 'CK', 0, 'name#%street%#%district%#%PLACE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(53, 'COSTA RICA', 'COSTA RICA', 'COSTA RICA', 'CR', 0, 'name#%street%#%state%#%ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(54, 'COTE D´IVOIRE', 'ELFENBEINKÜSTE', 'CÔTE D´IVOIRE', 'CI', 0, 'NAME#%STREET%#%PO% %PB% %PLACE% %PO%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(55, 'CROATIA', 'KROATIEN', 'CROATIE', 'HR', 0, 'name#%street%#HR-%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(56, 'CUBA', 'KUBA', 'CUBA', 'CU', 0, 'name#%street%#%crossstr%#%pzone%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(57, 'CYPRUS', 'ZYPERN', 'CHYPRE', 'CY', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', 'Famagusta|Famagusta District#Kyrenia|Kyrenia District#Larnaca|Larnaca District#Limassol|Limassol District#Nicosia|Nicosia District#Paphos|Paphos District', 0)+
			INSERT INTO $table VALUES(58, 'CZECH REPUBLIC', 'TSCHECHIEN', 'TCHÈQUE, RÉPUBLIQUE', 'CZ', 0, 'name#%street%#%ZIP% %PLACE% %PZONE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(59, 'DENMARK', 'DÄNEMARK', 'DANEMARK', 'DK', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(60, 'DJIBOUTI', 'DSCHIBUTI', 'DJIBOUTI', 'DJ', 0, 'name#%STREET%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(61, 'DOMINICA', 'DOMINICA', 'DOMINIQUE', 'DM', 0, 'name#%street%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(62, 'DOMINICAN REPUBLIC', 'DOMINIKANISCHE REPUBLIK', 'DOMINICAINE, RÉPUBLIQUE', 'DO', 0, 'name#%street%#%state%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(63, 'ECUADOR', 'ECUADOR', 'ÉQUATEUR', 'EC', 0, 'name#%street%#%ZIP%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(64, 'EGYPT', 'ÄGYPTEN', 'ÉGYPTE', 'EG', 0, 'name#%street%#%place%#%STATE%#%ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(65, 'EL SALVADOR', 'EL SALVADOR', 'EL SALVADOR', 'SV', 0, 'name#%colonyn%#%street%#%ZIP% - %PLACE%#%STATE%#%COUNTRY%', 'Ahuachapan|Ahuachapan#Cabanas|Cabanas#Chalatenango|Chalatenango#Cuscatlan|Cuscatlan#La Libertad|La Libertad#La Paz|La Paz#La Union|La Union#Morazan|Morazan#San Miguel|San Miguel#San Salvador|San Salvador#San Vicente|San Vicente#Santa Ana|Santa Ana#Sonsonate|Sonsonate#Usulutan|Usulutan', 1)+
			INSERT INTO $table VALUES(66, 'EQUATORIAL GUINEA', 'ÄQUATORIALGUINEA', 'GUINÉE ÉQUATORIALE', 'GQ', 0, 'name#%street%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(67, 'ERITREA', 'ERITREA', 'ÉRYTHRÉE', 'ER', 0, 'name#%street%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(68, 'ESTONIA', 'ESTLAND', 'ESTONIE', 'EE', 0, 'name#%street%#%place%#%ZIP% %STATE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(69, 'ETHIOPIA', 'ÄTHOPIEN', 'ÉTHIOPIE', 'ET', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(70, 'FALKLAND ISLANDS', 'FALKLAND INSELN', 'FALKLAND ÎLES', 'FK', 0, 'name#%street%#%PLACE%#%ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(71, 'FAROE ISLANDS', 'FARÖER INSELN', 'FÉROÉ, ÎLES', 'FO', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(72, 'FIJI', 'FIDSCHI', 'FIDJI', 'FJ', 0, 'NAME#%STREET%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(73, 'FINLAND', 'FINNLAND', 'FINLANDE', 'FI', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(74, 'FRANCE', 'FRANKREICH', 'FRANCE', 'FR', 0, 'name#%STREET%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(75, 'FRENCH GUIANA', 'FRANZÖSISCH-GUAYANA', 'GUYANE FRANÇAISE', 'GF', 0, 'name#%STREET%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(76, 'FRENCH POLYNESIA', 'FRANZÖSISCH-POLYNESIEN', 'POLYNÉSIE FRANÇAISE', 'PF', 0, 'name#%STREET%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(77, 'FRENCH SOUTHERN TERRITORIES', 'FRENCH SOUTHERN TERRITORIES', 'TERRES AUSTRALES FRANÇAISES', 'TF', 0, 'name#%STREET%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(78, 'GABON', 'GABUN', 'GABON', 'GA', 0, 'name#%STREET%#%ZIP% %PLACE% %PO%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(79, 'GAMBIA', 'GAMBIA', 'GAMBIE', 'GM', 0, 'name#%street%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(80, 'GEORGIA', 'GEORGIEN', 'GÉORGIE', 'GE', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(81, 'GERMANY', 'DEUTSCHLAND', 'ALLEMAGNE', 'DE', 2, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', 'BW|Baden-W&uuml;rtemberg#BY|Bayern#B|Berlin#BB|Brandenburg#HB|Bremen#HH|Hamburg#HS|Hessen#MV|Mecklenburg-Vorpommern#NS|Niedersachsen#NRW|Nordrhein-Westfalen#RLP|Rheinland-Pfalz#SAR|Saarland#SX|Sachsen#SXA|Sachsen-Anhalt#SH|Schleswig-Holstein#TH|Th&uuml;ringen', 1)+
			INSERT INTO $table VALUES(82, 'GHANA', 'GHANA', 'GHANA', 'GH', 0, 'name#%STREET%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(83, 'GIBRALTAR', 'GIBRALTAR', 'GIBRALTAR', 'GI', 0, 'name#%street%#%PLACE%#%ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(84, 'GREECE', 'GRIECHENLAND', 'GRÈCE', 'GR', 0, 'name#%STREET%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(85, 'GREENLAND', 'GRÖNLAND', 'GROENLAND', 'GL', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(86, 'GRENADA', 'GRENADA', 'GRENADE', 'GD', 0, 'name#%street%#%PLACE%#%COUNTRY% (WEST INDIES)', '', 0)+
			INSERT INTO $table VALUES(87, 'GUADELOUPE', 'GUADELOUPE', 'GUADELOUPE', 'GP', 0, 'name#%STREET%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(88, 'GUAM', 'GUAM', 'GUAM', 'GU', 0, 'NAME#%STREET%#%PLACE% %STATE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(89, 'GUATEMALA', 'GUATEMALA', 'GUATEMALA', 'GT', 0, 'name#%street%#%ZIP% - %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(90, 'GUERNSEY', 'GUERNSEY', 'GUERNESEY', 'GG', 0, 'name#%street%#%PLACE%#%ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(91, 'GUINEA', 'GUINEA', 'GUINÉE', 'GN', 0, 'name#%ZIP% %PB% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(92, 'GUINEA-BISSAU', 'GUINEA-BISSAU', 'GUINÉE-BISSAU', 'GW', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(93, 'GUYANA', 'GUYANA', 'GUYANA', 'GY', 0, 'name#%street%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(94, 'HAITI', 'HAITI', 'HAÏTI', 'HT', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(95, 'HEARD ISLAND AND MCDONALD ISLANDS', 'HEARD ISLAND UND MCDONALD ISLANDS', 'HEARD, ÎLE ET MCDONALD, ÎLES', 'HM', 0, 'NAME#%STREET%#%PLACE% %STATE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(96, 'HOLY SEE', 'VATIKAN', 'ÉTAT DE LA CITÉ DU VATICAN', 'VA', 0, 'NAME#%STREET%#%PLACE% %STATE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(97, 'HONDURAS', 'HONDURAS', 'HONDURAS', 'HN', 0, 'name#%ZIP% %PLACE%, %STATE%#%COUNTRY%', 'Atlántida|Atlántida#Choluteca|Choluteca#Colón|Colón#Comayagua|Comayagua#Copán|Copán#Cortés|Cortés#El Paraíso|El Paraíso#Francisco Morazán|Francisco Morazán#Gracias a Dios|Gracias a Dios#Intibucá|Intibucá#Islas de la Bahia|Islas de la Bahia#La Paz|La Paz#Lempira|Lempira#Ocotepeque|Ocotepeque#Olancho|Olancho#Santa Barbara|Santa Barbara#Valle|Valle#Yoro|Yoro', 1)+
			INSERT INTO $table VALUES(98, 'HONG KONG', 'HONG KONG', 'HONG-KONG', 'HK', 0, 'name#%street%#%PLACE%#%STATE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(99, 'HUNGARY', 'UNGARN', 'HONGRIE', 'HU', 0, 'NAME#%PLACE%#%STREET%#%ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(100, 'ICELAND', 'ISLAND', 'ISLANDE', 'IS', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(101, 'INDIA', 'INDIEN', 'INDE', 'IN', 0, 'name#%street%#%STATE%#%PLACE% %ZIP%#%COUNTRY%', 'AN|Andaman &amp; Nicobar#AP|Andhra Pradesh#AR|Arunachal Pradesh#AS|Assam#BR|Bihar#CH|Chandigarh#CG|Chattisgarh#DN|Dadra and Nagar Haveli#DD|Daman &amp; Diu#DL|Delhi#GA|Goa#GJ|Gujarat#HR|Haryana#HP|Himachal Pradesh#JK|Jammu &amp; Kashmir#JH|Jharkhand#KA|Karnataka#KL|Kerala#LD|Lakshadweep#MP|Madhya Pradesh#MH|Maharashtra#MN|Manipur#ML|Meghalaya#MZ|Mizoram#NL|Nagaland#OR|Orissa#PY|Puducherry#PB|Punjab#RJ|Rajasthan#SK|Sikkim#TN|Tamil Nadu#TR|Tripura#UK|Uttarakhand#UP|Uttar Pradesh#WP|West Bengal', 0)+
			INSERT INTO $table VALUES(102, 'INDONESIA', 'INDONESIEN', 'INDONÉSIE', 'ID', 0, 'name#%street%#%PLACE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(103, 'IRAN, ISLAMIC REPUBLIC OF', 'IRAN', 'IRAN, RÉPUBLIQUE ISLAMIQUE D''', 'IR', 0, 'name#%place%#%street%#%hsno%#%ZIP% %PO%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(104, 'IRAQ', 'IRAK', 'IRAQ', 'IQ', 0, 'name#%street%#%PLACE%, %STATE%#%ZIP%#%COUNTRY%', 'Al Anbar|Al Anbar#Al Basrah|Al Basrah#Al Munthana|Al Munthana#Al Najaf|Al Najaf#Al Quadisiya|Al Quadisiya#Al Sulaymaniah|Al Sulaymaniah#Al Ta´amim|Al Ta´amim#Arbil|Arbil#Babil|Babil#Baghdad|Baghdad#Dahouk|Dahouk#Deyala|Deyala#Karbala|Karbala#Maysan|Maysan#Mousl (Nainawa)|Mousl (Nainawa)#Salah Al Deen|Salah Al Deen#Thi Qar|Thi Qar#Wasit|Wasit', 1)+
			INSERT INTO $table VALUES(105, 'IRELAND', 'IRLAND', 'IRLANDE', 'IE', 0, 'name#%street%#%place%#%STATE%#%COUNTRY%', 'Co Antrim|Antrim#Co Armagh|Armagh#Co Carlow|Carlow#Co Cavan|Cavan#Co Clare|Clare#Co Cork|Cork#Co Donegal|Donegal#Co Down|Down#Co Dublin|County Dublin#Dublin 1|Dublin 1#Dublin 2|Dublin 2#Dublin 3|Dublin 3#Dublin 4|Dublin 4#Dublin 5|Dublin 5#Dublin 6|Dublin 6#Dublin 6W|Dublin 6W#Dublin 7|Dublin 7#Dublin 8|Dublin 8#Dublin 9|Dublin 9#Dublin 10|Dublin 10#Dublin 11|Dublin 11#Dublin 12|Dublin 12#Dublin 13|Dublin 13#Dublin 14|Dublin 14#Dublin 15|Dublin 15#Dublin 16|Dublin 16#Dublin 17|Dublin 17#Dublin 18|Dublin 18#Dublin 20|Dublin 20#Dublin 22|Dublin 22#Dublin 24|Dublin 24#Co Fermanagh|Fermanagh#Co Galway|Galway#Co Kerry|Kerry#Co Kildare|Kildare#Co Kilkenny|Kilkenny#Co Laois|Laois#Co Leitrim|Leitrim#Co Limerick|Limerick#Co Londonderry|Londonderry#Co Longford|Longford#Co Louth|Louth#Co Mayo|Mayo#Co Meath|Meath#Co Monaghan|Monaghan#Co Offaly|Offaly#Co Roscommon|Roscommon#Co Sligo|Sligo#Co Tipperary|Tipperary#Co Tyrone|Tyrone#Co Waterford|Waterford#Co Westmeath|Westmeath#Co Wexford|Wexford#Co Wicklow|Wicklow', 1)+
			INSERT INTO $table VALUES(106, 'ISLE OF MAN', 'ISLE OF MAN', 'ÎLE DE MAN', 'IM', 0, 'name#%street%#%PLACE%#%ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(107, 'ISRAEL', 'ISRAEL', 'ISRAËL', 'IL', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(108, 'ITALY', 'ITALIEN', 'ITALIE', 'IT', 2, 'name#%STREET%#%ZIP% %PLACE% %STATE%#%COUNTRY%', 'AG|Agrigento#AL|Alessandria#AN|Ancona#AO|Aosta#AP|Ascoli Piceno#AQ|L´Aquila#AR|Arezzo#AT|Asti#AV|Avellino#BA|Bari#BG|Bergamo#BI|Biella#BL|Belluno#BN|Benevento#BO|Bologna#BR|Brindisi#BS|Brescia#BZ|Bolzano#CA|Cagliari#CB|Campobasso#CE|Caserta#CH|Chieti#CL|Caltanissetta#CN|Cuneo#CO|Como#CR|Cremona#CS|Cosenza#CT|Catania#CZ|Catanzaro#EN|Enna#FE|Ferrara#FG|Foggia#FI|Firenze#FO|Forli#FR|Frosinone#GE|Genova#GO|Gorizia#GR|Grosseto#IM|Imperia#IS|Isernia#KR|Crotone#LC|Lecco#LE|Lecce#LI|Livorno#LO|Lodi#LT|Latina#LU|Lucca#MC|Macerata#ME|Messina#MI|Milano#MN|Mantova#MO|Modena#MS|Massa Carrara#MT|Matera#NA|Napoli#NO|Novara#NU|Nuoro#OR|Oristano#PA|Palermo#PC|Piacenza#PD|Padova#PE|Pescara#PG|Perugia#PI|Pisa#PN|Pordenone#PR|Parma#PS|Pesaro#PT|Pistoia#PV|Pavia#PO|Prato#PZ|Potenza#RA|Ravenna#RC|Reggio Calabria#RE|Reggio Emilia#RG|Ragusa#RI|Rieti#RM|Roma#RN|Rimini#RO|Rovigo#SA|Salerno#SI|Siena#SO|Sondrio#SP|La Spezia#SR|Siracusa#SS|Sassari#SV|Savona#TA|Taranto#TE|Teramo#TN|Trento#TO|Torino#TP|Trapani#TR|Terni#TS|Trieste#TV|Treviso#UD|Udine#VA|Varese#VC|Vercelli#VE|Venezia#VI|Vicenza#VB|Verbania#VR|Verona#VT|Viterbo#VV|Vibo Valentia', 1)+
			INSERT INTO $table VALUES(109, 'JAMAICA', 'JAMAIKA', 'JAMAÏQUE', 'JM', 0, 'name#%street%#%STATE%#%PLACE%#%COUNTRY%', 'Clarendon|Clarendon#Hanover|Hanover#Kingston Metropolitan Area|Kingston Metropolitan Area#Manchester|Manchester#Portland|Portland#St Ann|St Ann#St Elizabeth|St Elizabeth#St James|St James#St Catherine|St Catherine#St Mary|St Mary#St Thomas|St Thomas#Trelawny|Trelawny#Westmoreland|Westmoreland', 1)+
			INSERT INTO $table VALUES(110, 'JAPAN', 'JAPAN', 'JAPON', 'JP', 0, 'name#%street%#%PLACE%#%ZIP% %COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(111, 'JERSEY', 'JERSEY', 'JERSEY', 'JE', 0, 'name#%street%#%PLACE%#%ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(112, 'JORDAN', 'JORDANIEN', 'JORDANIE', 'JO', 0, 'name#%region%#%district%#%strnam%#%STRNO%#%PLACE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(113, 'KAZAKHSTAN', 'KASACHSTAN', 'KAZAKHSTAN', 'KZ', 0, 'name#%street%#%place%, %district%#%STATE%#%COUNTRY%#%ZIP%', '', 0)+
			INSERT INTO $table VALUES(114, 'KENYA', 'KENIA', 'KENYA', 'KE', 0, 'name#%PB%#%PO%#%ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(115, 'KIRIBATI', 'KIRIBATI', 'KIRIBATI', 'KI', 0, 'NAME#%DISTRICT%#%PLACE%#%STATE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(116, 'KOREA, DEMOCRATIC PEOPLE''S REPUBLIC OF', 'NORDKOREA', 'CORÉE, RÉPUBLIQUE POPULAIRE DÉMOCRATIQUE DE', 'KP', 0, 'name#%district%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(117, 'KOREA, REPUBLIC OF', 'SÜDKOREA', 'CORÉE, RÉPUBLIQUE DE', 'KR', 0, 'Korea Post#%street%#name#SEOUL %ZIP%#KOREA (REP.)', '', 0)+
			INSERT INTO $table VALUES(118, 'KUWAIT', 'KUWAIT', 'KOWEÏT', 'KW', 0, 'name#%district%#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(119, 'KYRGYZSTAN', 'KIRGISTAN', 'KIRGHIZISTAN', 'KG', 0, '%ZIP% %PLACE%#%street%#name#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(120, 'LAO PEOPLE''S DEMOCRATIC REPUBLIC', 'LAOS', 'LAO, RÉPUBLIQUE DÉMOCRATIQUE POPULAIRE', 'LA', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(121, 'LATVIA', 'LETTLAND', 'LETTONIE', 'LV', 0, 'name#%street%#%PLACE%, %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(122, 'LEBANON', 'LIBANON', 'LIBAN', 'LB', 0, 'name#%street%#%PLACE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(123, 'LESOTHO', 'LESOTHO', 'LESOTHO', 'LS', 0, 'name#%PB%#%PO% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(124, 'LIBERIA', 'LIBERIA', 'LIBÉRIA', 'LR', 0, 'name#%STREET%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(125, 'LIBYAN ARAB JAMAHIRIYA', 'LIBYEN', 'LIBYENNE, JAMAHIRIYA ARABE', 'LY', 0, 'name#%street%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(126, 'LIECHTENSTEIN', 'LIECHTENSTEIN', 'LIECHTENSTEIN', 'LI', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(127, 'LITHUANIA', 'LITAUEN', 'LITUANIE', 'LT', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(128, 'LUXEMBOURG', 'LUXEMBURG', 'LUXEMBOURG', 'LU', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(129, 'MACAO', 'MACAO', 'MACAO', 'MO', 0, 'name#%street%#%district%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(130, 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'MAZEDONIEN', 'MACÉDOINE, L''EX-RÉPUBLIQUE YOUGOSLAVE DE ', 'MK', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(131, 'MADAGASCAR', 'MADASGASKAR', 'MADAGASCAR', 'MG', 0, 'name#%street%#%place%#%ZIP% %PO%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(132, 'MALAWI', 'MALAWI', 'MALAWI ', 'MW', 0, 'name#%PB%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(133, 'MALAYSIA', 'MALAYSIA', 'MALAISIE', 'MY', 0, 'name#%street%#%ZIP% %PLACE% %STATE%#%COUNTRY%', 'Federal Territory of Kuala Lumpur|Federal Territory of Kuala Lumpur#Federal Territory of Labuan|Federal Territory of Labuan#Federal Territory of Putrajaya|Federal Territory of Putrajaya#Johor|Johor#Kedah|Kedah#Kelantan|Kelantan#Melaka|Melaka#Negeri Sembilan|Negeri Sembilan#Pahang|Pahang#Perak|Perak#Perlis|Perlis#Pulau Pinang|Pulau Pinang#Sabah|Sabah#Sarawak|Sarawak#Selangor|Selangor#Terengganu|Terengganu', 1)+
			INSERT INTO $table VALUES(134, 'MALDIVES', 'MALEDIVEN', 'MALDIVES', 'MV', 0, 'NAME#%STREET%#%PLACE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(135, 'MALI', 'MALI', 'MALI', 'ML', 0, 'name#%street%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(136, 'MALTA', 'MALTA', 'MALTE', 'MT', 0, 'name#%street%#%place%#%ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(137, 'MARSHALL ISLANDS', 'MARSHALLINSELN', 'MARSHALL, ÎLES', 'MH', 0, 'NAME#%STREET%#%PLACE% %STATE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(138, 'MARTINIQUE', 'MARTINIQUE', 'MARTINIQUE', 'MQ', 0, 'name#%STREET%#%ZIP% %PLACE%#FRANCE', '', 0)+
			INSERT INTO $table VALUES(139, 'MAURITANIA', 'MAURETANIEN', 'MAURITANIE', 'MR', 0, 'name#%PB%#%PO%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(140, 'MAURITIUS', 'MAURITIUS', 'MAURICE', 'MU', 0, 'name#%street%#%place%#%PO%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(141, 'MAYOTTE', 'MAYOTTE', 'MAYOTTE', 'YT', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(142, 'MEXICO', 'MEXIKO', 'MEXIQUE', 'MX', 0, 'name#%street%#%district%#%ZIP% %PLACE% %STATE%#%COUNTRY%', 'AGS|Aguascalientes#BCN|Baja California Norte#BCS|Baja California Sur#CAM|Campeche#CHIS|Chiapas#CHIH|Chihuahua#COAH|Coahuila#COL|Colima#DF|Distrito Federal#DGO|Durango#GTO|Guanajuato#GRO|Guerrero#HGO|Hidalgo#JAL|Jalisco#EDM|M&eacute;xico - Estado de#MICH|Michoac&aacute;n#MOR|Morelos#NAY|Nayarit#NL|Nuevo Le&oacute;n#OAX|Oaxaca#PUE|Puebla#QRO|Quer&eacute;taro#QROO|Quintana Roo#SLP|San Luis Potos&iacute;#SIN|Sinaloa#SON|Sonora#TAB|Tabasco#TAMPS|Tamaulipas#TLAX|Tlaxcala#VER|Veracruz#YUC|Yucat&aacute;n#ZAC|Zacatecas', 0)+
			INSERT INTO $table VALUES(143, 'MICRONESIA, FEDERATED STATES OF', 'MIKRONESIEN', 'MICRONÉSIE, ÉTATS FÉDÉRÉS DE', 'FM', 0, 'NAME#%STREET%#%PLACE% %STATE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(144, 'MOLDOVA, REPUBLIC OF', 'MOLDAWIEN', 'MOLDOVA', 'MD', 0, 'NAME#%STREET%#MD-%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(145, 'MONACO', 'MONACO', 'MONACO', 'MC', 0, 'name#%STREET%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(146, 'MONGOLIA', 'MONGOLEI', 'MONGOLIE', 'MN', 0, 'name#%street%#%PLACE%, %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(147, 'MONTSERRAT', 'MONTSERRAT', 'MONTSERRAT', 'MS', 0, 'name#%street%#%PLACE%#%ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(148, 'MOROCCO', 'MAROKKO', 'MAROC', 'MA', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(149, 'MOZAMBIQUE', 'MOSAMBIK', 'MOZAMBIQUE', 'MZ', 0, 'name#%street%#%hsno%#%ZIP% %PLACE%#%STATE%#%COUNTRY%', 'Cabo Delgado|Cabo Delgado#Gaza|Gaza#Inhambane|Inhambane#Manica|Manica#Maputo|Maputo#Nampula|Nampula#Niassa|Niassa#Sofala|Sofala#Tete|Tete#Zambezia|Zambezia', 1)+
			INSERT INTO $table VALUES(150, 'MYANMAR', 'MYANMAR', 'MYANMAR', 'MM', 0, 'name#%street%#%PO%#%PLACE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(151, 'NAMIBIA', 'NAMIBIA', 'NAMIBIE ', 'NA', 0, 'NAME#%PB%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(152, 'NAURU', 'NAURU', 'NAURU', 'NR', 0, 'name#%pb%#%DISTRICT%#%COUNTRY% CENTRAL PACIFIC', '', 0)+
			INSERT INTO $table VALUES(153, 'NEPAL', 'NEPAL', 'NÉPAL', 'NP', 0, 'name#%street%#%district%#%PLACE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(154, 'NETHERLANDS', 'NIEDERLANDE', 'PAYS-BAS', 'NL', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(155, 'NETHERLANDS ANTILLES', 'NIEDERLÄNDISCHE ANTILLEN', 'ANTILLES NÉERLANDAISES', 'AN', 0, 'name#%street%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(156, 'NEW CALEDONIA', 'NEUKALEDONIEN', 'NOUVELLE-CALÉDONIE', 'NC', 0, 'name#%STREET%#%PB%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(157, 'NEW ZEALAND', 'NEUSEELAND', 'NOUVELLE-ZÉLANDE', 'NZ', 0, 'name#%street%#%district%#%PLACE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(158, 'NICARAGUA', 'NICARAGUA', 'NICARAGUA', 'NI', 0, 'name#%street%#%district%#%ZIP%#%PLACE% %STATE%#%COUNTRY%', 'Boaco|Boaco#Carazo|Carazo#Chinandega|Chinandega#Chontales|Chontales#Esteli|Esteli#Granada|Granada#Jinotega|Jinotega#Leon|Leon#Madriz|Madriz#Managua|Managua#Masaya|Masaya#Matagalpa|Matagalpa#Nueva Segovia|Nueva Segovia#Rio San Juan|Rio San Juan#Rivas|Rivas', 1)+
			INSERT INTO $table VALUES(159, 'NIGER', 'NIGER', 'NIGER', 'NE', 0, 'NAME#%PB%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(160, 'NIGERIA', 'NIGERIA', 'NIGÉRIA', 'NG', 0, 'name#%PB%#%PLACE% %ZIP%#%STATE%#%COUNTRY%', 'Abia State|Abia#Adamawa State|Adamawa#Akwa State|Akwa#Anambra State|Anambra#Bauchi State|Bauchi#Bayelsa State|Bayelsa#Benue State|Benue#Borno State|Borno#Cross River State|Cross River#Delta State|Delta#Ebonyi State|Ebonyi#Edo State|Edo#Ekiti State|Ekiti#Enugu State|Enugu#Federal Capital Territory|Federal Capital Territory#Gombe State|Gombe#Imo State|Imo#Jigawa State|Jigawa#Kaduna State|Kaduna#Kano State|Kano#Katsina State|Katsina#Kebbi State|Kebbi#Kogi State|Kogi#Kwara State|Kwara#Lagos State|Lagos#Nassarawa State|Nassarawa#Niger State|Niger#Ogun State|Ogun#Ondo State|Ondo#Osun State|Osun#Oyo State|Oyo#Plateau State|Plateau#Rivers State|Rivers#Sokoto State|Sokoto#Taraba State|Taraba#Yobe State|Yobe#Zamfara State|Zamfara', 1)+
			INSERT INTO $table VALUES(161, 'NIUE', 'NIUE', 'NIUÉ', 'NU', 0, 'name#%street%#%district%#%PLACE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(162, 'NORFOLK ISLAND', 'NORFOLK INSELN', 'NORFOLK, ÎLE', 'NF', 0, 'NAME#%STREET%#%PLACE% %STATE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(163, 'NORTHERN MARIANA ISLANDS', 'NORTHERN MARIANA ISLANDS', 'MARIANNES DU NORD, ÎLES', 'MP', 0, 'NAME#%STREET%#%PLACE% %STATE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(164, 'NORWAY', 'NORWEGEN', 'NORVÈGE', 'NO', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(165, 'OMAN', 'OMAN', 'OMAN', 'OM', 0, 'name#%PB%#%ZIP%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(166, 'PAKISTAN', 'PAKISTAN', 'PAKISTAN', 'PK', 0, 'name#%hsno%#%street%#%district%#%PLACE%-%ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(167, 'PALAU', 'PALAU', 'PALAOS', 'PW', 0, 'NAME#%STREET%#%PLACE% %STATE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(168, 'PALESTINIAN TERRITORY, OCCUPIED', 'PALESTINIAN TERRITORY, OCCUPIED', 'PALESTINIEN OCCUPÉ, TERRITOIRE', 'PS', 0, 'name#%STREET%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(169, 'PANAMA', 'PANAMA', 'PANAMA', 'PA', 0, 'NAME#%STREET%#%ZIP%, %DISTRICT%#%STATE%#%COUNTRY% (REP.)', 'Bocas del Toro|Bocas del Toro#Chiriqui|Chiriqui#Cocle|Cocle#Colon|Colon#Darien|Darien#Herrera|Herrera#Los Santos|Los Santos#Panama|Panama#San Blas|San Blas#Veraguas|Veraguas', 1)+
			INSERT INTO $table VALUES(170, 'PAPUA NEW GUINEA', 'PAPUA-NEUGUINEA', 'PAPOUASIE-NOUVELLE-GUINÉE', 'PG', 0, 'name#%pb%#%PLACE% %ZIP% %STATE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(171, 'PARAGUAY', 'PARAGUAY', 'PARAGUAY', 'PY', 0, 'name#%street%#%district%#%ZIP% %PZONE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(172, 'PERU', 'PERU', 'PÉROU', 'PE', 0, 'name#%street%#%STATE% %ZIP%#%COUNTRY%', 'Amazonas|Amazonas#Ancash|Ancash#Apurimac|Apurimac#Arequipa|Ayacucho#Cajamarca|Cajamarca#Callao|Callao#Cusco|Cusco#Huancavelica|Huancavelica#Huanuco|Huanuco#Ica|Ica#Junin|Junin#La Libertad|La Libertad#Lambayeque|Lambayeque#Lima|Lima#Loreto|Loreto#Madre de dios|Madre de dios#Moquegua|Moquegua#Pasco|Pasco#Piura|Piura#Puno|Puno#San Martin|San Martin#Tacna|Tacna#Tumbes|Tumbes#Ucayali|Ucayali', 1)+
			INSERT INTO $table VALUES(173, 'PHILIPPINES', 'PHILIPPINEN', 'PHILIPPINES', 'PH', 0, 'name#%street%#%district% %place%#%ZIP% %STATE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(174, 'PITCAIRN', 'PITCAIRN', 'PITCAIRN', 'PN', 0, 'name#%street%#%PLACE%#%ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(175, 'POLAND', 'POLEN', 'POLOGNE', 'PL', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(176, 'PORTUGAL', 'PORTUGAL', 'PORTUGAL', 'PT', 0, 'name#%street%#%PLACE%#%ZIP% %PO%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(177, 'PUERTO RICO', 'PUERTO RICO', 'PORTO RICO', 'PR', 0, 'NAME#%STREET%#%PLACE% %STATE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(178, 'QATAR', 'QATAR', 'QATAR', 'QA', 0, 'name#%PB%#%PO%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(179, 'REUNION', 'REUNION', 'RÉUNION', 'RE', 0, 'name#%STREET%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(180, 'ROMANIA', 'RUMÄNIEN', 'ROUMANIE', 'RO', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(181, 'RUSSIAN FEDERATION', 'RUSSLAND', 'RUSSIE, FÉDÉRATION DE', 'RU', 0, 'name#%street%#%PLACE%#%COUNTRY%#%ZIP%', '', 0)+
			INSERT INTO $table VALUES(182, 'RWANDA', 'RUANDA', 'RWANDA', 'RW', 0, 'name#%PB%#%PO%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(183, 'SAINT HELENA', 'ST. HELENA', 'SAINTE-HÉLÈNE', 'SH', 0, 'name#%street%#%PLACE%#%ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(184, 'SAINT KITTS AND NEVIS', 'SAINT KITTS AND NEVIS', 'SAINT-KITTS-ET-NEVIS', 'KN', 0, 'name#%street%#%DISTRICT%, %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(185, 'SAINT LUCIA', 'ST. LUCIA', 'SAINTE-LUCIE', 'LC', 0, 'name#%street%#%DISTRICT%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(186, 'SAINT PIERRE AND MIQUELON', 'SAINT PIERRE AND MIQUELON', 'SAINT-PIERRE-ET-MIQUELON', 'PM', 0, 'name#%STREET%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(187, 'SAINT VINCENT AND THE GRENADINES', 'SAINT VINCENT AND THE GRENADINES', 'SAINT-VINCENT-ET-LES GRENADINES', 'VC', 0, 'name#%PB%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(188, 'SAMOA', 'SAMOA', 'SAMOA', 'WS', 0, 'name#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(189, 'SAN MARINO', 'SAN MARINO', 'SAINT-MARIN', 'SM', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(190, 'SAO TOME AND PRINCIPE', 'SAO TOME AND PRINCIPE', 'SAO TOMÉ-ET-PRINCIPE ', 'ST', 0, 'name#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(191, 'SAUDI ARABIA', 'SAUDI-ARABIEN', 'ARABIE SAOUDITE', 'SA', 0, 'name#%pb%#%PLACE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(192, 'SENEGAL', 'SENEGAL', 'SÉNÉGAL', 'SN', 0, 'name#%STREET%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(193, 'SERBIA', 'SERBIEN', 'SERBIE', 'CS', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(194, 'SEYCHELLES', 'SEYCHELLEN', 'SEYCHELLES', 'SC', 0, 'name#%street%#%place%#%STATE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(195, 'SIERRA LEONE', 'SIERRA LEONE', 'SIERRA LEONE', 'SL', 0, 'name#%street%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(196, 'SINGAPORE', 'SINGAPUR', 'SINGAPOUR', 'SG', 0, 'name#%street%#%PLACE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(197, 'SLOVAKIA', 'SLOWAKEI', 'SLOVAQUIE', 'SK', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(198, 'SLOVENIA', 'SLOVENIEN', 'SLOVÉNIE', 'SI', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(199, 'SOLOMON ISLANDS', 'SOLOMON ISLANDS', 'SALOMON, ÎLES', 'SB', 0, 'name#%pb%#%STATE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(200, 'SOMALIA', 'SOMALIA', 'SOMALIE', 'SO', 0, 'name#%pb%#%PLACE%, %STATE% %ZIP%#%COUNTRY%', 'AD|AWDAL#BK|BAKOOL#BN|BANAADIR#BR|BARI#BY|BAY#GG|GALGADUUD#GD|GEDO#HR|HIIRAAN#JD|JUBBADA DHEXE#JH|JUBBADA HOOSE#MD|MUDUG#NG|NUGAAL#SG|SANAAG#SD|SHABEELLADA DHEXE#SH|SHABEELLADA HOOSE#SL|SOOL#TG|TOGDHEER#WG|WAQOOYI GALBEED', 1)+
			INSERT INTO $table VALUES(201, 'SOUTH AFRICA', 'SÜDAFRIKA', 'AFRIQUE DU SUD', 'ZA', 0, 'name#%street%#%DISTRICT%#%place%#%ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(202, 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'GÉORGIE DU SUD ET LES ÎLES SANDWICH DU SUD', 'GS', 0, 'name#%street%#%ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(203, 'SPAIN', 'SPANIEN', 'ESPAGNE', 'ES', 0, 'name#%street%#%ZIP% %PLACE% (%STATE%)#%COUNTRY%', 'Alava|Alava#Albacete|Albacete#Alicante|Alicante#Almería|Almería#Asturias|Asturias#Avila|Avila#Badajoz|Badajoz#Baleares|Baleares#Barcelona|Barcelona#Burgos|Burgos#Cáceres|Cáceres#Cádiz|Cádiz#Cantabria|Cantabria#Castellón|Castellón#Ceuta|Ceuta#Ciudad Real|Ciudad Real#Córdoba|Córdoba#Cuenca|Cuenca#Girona|Girona#Granada|Granada#Guadelajara|Guadelajara#Guipúzcoa|Guipúzcoa#Huelva|Huelva#Huesca|Huesca#Jaén|Jaén#La Coruña|La Coruña#León|León#Lleida|Lleida#Lugo|Lugo#Madrid|Madrid#Málaga|Málaga#Melilla|Melilla#Murcia|Murcia#Navarra|Navarra#Orense|Orense#Palencia|Palencia#Las Palmas|Las Palmas#Pontevedra|Pontevedra#La Rioja|La Rioja#Salamanca|Salamanca#Santa Cruz|Santa Cruz#Segovia|Segovia#Sevilla|Sevilla#Soria|Soria#Tarragona|Tarragona#Tenerife|Tenerife#Teruel|Teruel#Toledo|Toledo#Valencia|Valencia#Valladolid|Valladolid#Vizcaya|Vizcaya#Zamora|Zamora#Zaragoza|Zaragoza', 1)+
			INSERT INTO $table VALUES(204, 'SRI LANKA', 'SRI LANKA', 'SRI LANKA', 'LK', 0, 'name#%hsno%#%street%#%PLACE%#%ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(205, 'SUDAN', 'SUDAN', 'SOUDAN', 'SD', 0, 'name#%PB%#%ZIP%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(206, 'SURINAME', 'SURINAM', 'SURINAME', 'SR', 0, 'name#%street%#%place%#%DISTRICT%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(207, 'SVALBARD AND JAN MAYEN', 'SVALBARD AND JAN MAYEN', 'SVALBARD ET ÎLE JAN MAYEN', 'SJ', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(208, 'SWAZILAND', 'SWASILAND', 'SWAZILAND', 'SZ', 0, 'NAME#%pb%#%PLACE%#%ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(209, 'SWEDEN', 'SCHWEDEN', 'SUÈDE', 'SE', 0, 'NAME#%STREET%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(210, 'SWITZERLAND', 'SCHWEIZ', 'SUISSE', 'CH', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', 'AG|Aargau#AR|Appenzell Ausserrhoden#AI|Appenzell Innerrhoden#BL|Basel Landschaft#BS|Basel Stadt#BE|Bern#FR|Fribourg#GE|Gen&egrave;ve#GL|Glarus#GR|Graub&uuml;nden#JU|Jura#LU|Luzern#NE|Neuch&acirc;tel#NW|Nidwalden#OW|Obwalden#SG|Sankt Gallen#SH|Schaffhausen#SZ|Schwyz#SO|Solothurn#TG|Thurgau#TI|Ticino#UR|Uri#VS|Valais#VD|Vaud#ZG|Zug#ZH|Z&uuml;rich', 0)+
			INSERT INTO $table VALUES(211, 'SYRIAN ARAB REPUBLIC', 'SYRIEN', 'SYRIENNE, RÉPUBLIQUE ARABE', 'SY', 0, 'name#%hsno%#%street%#%district%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(212, 'TAIWAN, PROVINCE OF CHINA', 'TAIWAN', 'TAÏWAN, PROVINCE DE CHINE', 'TW', 0, 'name#%street%#%PLACE%#%ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(213, 'TAJIKISTAN', 'TADSCHIKISTAN', 'TADJIKISTAN', 'TJ', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(214, 'TANZANIA, UNITED REPUBLIC OF', 'TANSANIA', 'TANZANIE, RÉPUBLIQUE-UNIE DE', 'TZ', 0, 'name#%pb%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(215, 'THAILAND', 'THAILAND', 'THAÏLANDE', 'TH', 0, 'name#%hsno%#%street% %district%#%state%#%PLACE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(216, 'TIMOR-LESTE  ', 'TIMOR-LESTE  ', 'TIMOR-LESTE', 'TL', 0, 'name#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(217, 'TOGO', 'TOGO', 'TOGO', 'TG', 0, 'name#%PB%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(218, 'TOKELAU', 'TOKELAU', 'TOKELAU', 'TK', 0, 'name#%street%#%district%#%PLACE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(219, 'TONGA', 'TONGA', 'TONGA', 'TO', 0, 'name#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(220, 'TRINIDAD AND TOBAGO', 'TRINIDAD UND TOBAGO', 'TRINITÉ-ET-TOBAGO', 'TT', 0, 'name#%street%#%PLACE%#%STATE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(221, 'TUNISIA', 'TUNESIEN', 'TUNISIE', 'TN', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(222, 'TURKEY', 'TÜRKEI', 'TURQUIE', 'TR', 0, 'name#%district%, %street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(223, 'TURKMENISTAN', 'TURKMENISTAN', 'TURKMÉNISTAN', 'TM', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(224, 'TURKS AND CAICOS ISLANDS', 'TURKS UND CAICOS INSELN', 'TURKS ET CAÏQUES, ÎLES', 'TC', 0, 'name#%street%#%PLACE%#%ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(225, 'TUVALU', 'TUVALU', 'TUVALU', 'TV', 0, 'name#%pb%#%place%#%STATE%#%COUNTRY%', 'Funafuti|Funafuti#Funafuti Centre|Funafuti Centre#Nanumaga|Nanumaga#Nanumea|Nanumea#Niulakita|Niulakita#Niutao|Niutao#Nui|Nui#Nukulaelae|Nukulaelae#Vaitupu|Vaitupu', 1)+
			INSERT INTO $table VALUES(226, 'UGANDA', 'UGANDA', 'OUGANDA', 'UG', 0, 'name#%pb%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(227, 'UKRAINE', 'UKRAINE', 'UKRAINE', 'UA', 0, 'name#%street%#%STATE%#%ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(228, 'UNITED ARAB EMIRATES', 'VEREINIGTE ARABISCHE EMIRATE', 'ÉMIRATS ARABES UNIS', 'AE', 0, 'NAME#%PB%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(229, 'UNITED KINGDOM', 'GROSSBRITANIEN', 'ROYAUME-UNI', 'GB', 2, 'name#%street%#%PLACE%#%ZIP%#%COUNTRY%', 'Avon|Avon#Bedfordshire|Bedfordshire#Berkshire|Berkshire#Borders|Borders#Buckinghamshire|Buckinghamshire#Cambridgeshire|Cambridgeshire#Central|Central#Cheshire|Cheshire#Cleveland|Cleveland#Clwyd|Clwyd#Cornwall|Cornwall#County Antrim|County Antrim#County Armagh|County Armagh#County Down|County Down#County Fermanagh|County Fermanagh#County Londonderry|County Londonderry#County Tyrone|County Tyrone#Cumbria|Cumbria#Derbyshire|Derbyshire#Devon|Devon#Dorset|Dorset#Dumfries and Galloway|Dumfries and Galloway#Durham|Durham#Dyfed|Dyfed#East Sussex|East Sussex#Essex|Essex#Fife|Fife#Gloucestershire|Gloucestershire#Grampian|Grampian#Greater Manchester|Greater Manchester#Gwent|Gwent#Gwynedd County|Gwynedd County#Hampshire|Hampshire#Herefordshire|Herefordshire#Highlands and Islands|Highlands and Islands#Humberside|Humberside#Isle of Wight|Isle of Wight#Kent|Kent#Lancashire|Lancashire#Leicestershire|Leicestershire#Lincolnshire|Lincolnshire#Lothian|Lothian#Merseyside|Merseyside#Mid Glamorgan|Mid Glamorgan#Norfolk|Norfolk#North Yorkshire|North Yorkshire#Northamptonshire|Northamptonshire#Northumberland|Northumberland#Nottinghamshire|Nottinghamshire#Oxfordshire|Oxfordshire#Powys|Powys#Rutland|Rutland#Shropshire|Shropshire#Somerset|Somerset#South Glamorgan|South Glamorgan#South Yorkshire|South Yorkshire#Staffordshire|Staffordshire#Strathclyde|Strathclyde#Suffolk|Suffolk#Surrey|Surrey#Tayside|Tayside#Tyne and Wear|Tyne and Wear#Warwickshire|Warwickshire#West Glamorgan|West Glamorgan#West Midlands|West Midlands#West Sussex|West Sussex#West Yorkshire|West Yorkshire#Wiltshire|Wiltshire#Worcestershire|Worcestershire', 0)+
			INSERT INTO $table VALUES(230, 'UNITED STATES OF AMERICA', 'USA', 'ÉTATS-UNIS', 'US', 1, 'NAME#%STREET%#%PLACE% %STATE% %ZIP%#%COUNTRY%', 'AK|Alaska#AL|Alabama#AR|Arkansas#AZ|Arizona#CA|California#CO|Colorado#CT|Connecticut#DC|District of Columbia#DE|Delaware#FL|Florida#GA|Georgia#HI|Hawaii#IA|Iowa#ID|Idaho#IL|Illinois#IN|Indiana#KS|Kansas#KY|Kentucky#LA|Louisiana#MA|Massachusetts#MD|Maryland#ME|Maine#MI|Michigan#MN|Minnesota#MO|Missouri#MS|Mississippi#MT|Montana#NE|Nebraska#NV|Nevada#NH|New Hampshire#NJ|New Jersey#NM|New Mexico#NY|New York#NC|North Carolina#ND|North Dakota#OH|Ohio#OK|Oklahoma#OR|Oregon#PA|Pennsylvania#RI|Rhode Island#SC|South Carolina#SD|South Dakota#TN|Tennessee#TX|Texas#UT|Utah#VA|Virginia#VT|Vermont#WA|Washington#WI|Wisconsin#WV|West Virginia#WY|Wyoming', 1)+
			INSERT INTO $table VALUES(231, 'UNITED STATES MINOR OUTLYING ISLANDS', 'UNITED STATES MINOR OUTLYING ISLANDS', 'ÎLES MINEURES ÉLOIGNÉES DES ÉTATS-UNIS', 'UM', 0, 'NAME#%STREET%#%PLACE% %STATE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(232, 'URUGUAY', 'URUGUAY', 'URUGUAY', 'UY', 0, 'NAME#%street%#%ZIP% %PLACE% %STATE%#%COUNTRY%', 'Artigas|Artigas#Canelones|Canelones#Cerro Largo|Cerro Largo#Colonia|Colonia#Durazno|Durazno#Flores|Flores#Florida|Florida#Lavalleja|Lavalleja#Maldonado|Maldonado#Paysandú|Paysandú#Rio Negro|Rio Negro#Rivera|Rivera#Rocha|Rocha#Salto|Salto#San José|San José#Soriano|Soriano#Tacuarembó|Tacuarembó#Treinta y tres|Treinta y tres', 1)+
			INSERT INTO $table VALUES(233, 'UZBEKISTAN', 'UZBEKISTAN', 'OUZBÉKISTAN', 'UZ', 0, 'name#%district%#%street%#%PLACE%#%STATE%#%COUNTRY%#%ZIP%', '', 0)+
			INSERT INTO $table VALUES(234, 'VANUATU', 'VANUATU', 'VANUATU', 'VU', 0, 'name#%pb%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(235, 'VENEZUELA', 'VENEZUELA', 'VENEZUELA, RÉPUBLIQUE BOLIVARIENNE DU', 'VE', 0, 'NAME#%STREET%#%HSNO%#%PLACE% %ZIP%, %STATE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(236, 'VIET NAM', 'VIETNAM', 'VIET NAM', 'VN', 0, 'name#%street%#%district%#%PLACE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(237, 'VIRGIN ISLANDS, BRITISH', 'VIRGIN ISLANDS, BRITISCH', 'ÎLES VIERGES BRITANNIQUES', 'VG', 0, 'name#%street%#%PLACE%#%ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(238, 'VIRGIN ISLANDS, U.S.', 'VIRGIN ISLANDS, U.S.', 'ÎLES VIERGES DES ÉTATS-UNIS', 'VI', 0, 'NAME#%STREET%#%PLACE% %STATE% %ZIP%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(239, 'WALLIS AND FUTUNA', 'WALLIS UND FUTUNA', 'WALLIS ET FUTUNA', 'WF', 0, 'name#%STREET%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(240, 'WESTERN SAHARA', 'WESTSAHARA', 'SAHARA OCCIDENTAL', 'EH', 0, 'name#%pb%#%PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(241, 'YEMEN', 'JEMEN', 'YÉMEN', 'YE', 0, 'name#%PB%#%STATE%#%COUNTRY%', 'Aby|Aby#`Adam|`Adam#Ad-Dali´|Ad-Dali´#Al-Bayda´|Al-Bayda´#Al-Hudaydah|Al-Hudaydah#Al-Jawf|Al-Jawf#Al-Mahrah|Al-Mahrah#Al-Mahwit|Al-Mahwit#`Amran|`Amran#Dhamar|Dhamar#Hadramawt|Hadramawt#Hajjah|Hajjah#Ibb|Ibb#Lahij|Lahij#Ma´rib|Ma´rib#Sa´dah|Sa´dah#San´a´|San´a´#Shabwah|Shabwah#Ta´izz|Ta´izz', 1)+
			INSERT INTO $table VALUES(242, 'ZAMBIA', 'SAMBIA', 'ZAMBIE', 'ZM', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)+
			INSERT INTO $table VALUES(243, 'ZIMBABWE', 'SIMBABWE', 'ZIMBABWE', 'ZW', 0, 'name#%street%#%place%#%STATE%#%COUNTRY%', 'Bulawayo|Bulawayo#Harare|Harare#Manicaland|Manicaland#Mashonaland Central|Mashonaland Central#Mashonaland East|Mashonaland East#Mashonaland West|Mashonaland West#Masvingo|Masvingo#Matabeleland North|Matabeleland North#Matabeleland South|Matabeleland South#Midlands|Midlands', 1)+
			INSERT INTO $table VALUES(244, 'MONTENEGRO', 'MONTENEGRO', 'MONTÉNÉGRO', 'ME', 0, 'name#%street%#%ZIP% %PLACE%#%COUNTRY%', '', 0)
			";
		
		break;


		case 'status':
		
			$qStr = "
				CREATE TABLE $table (
					`id` tinyint(4) NOT NULL,
					`status` tinyint(4) NOT NULL default '0',
					`th_vers` varchar(50) NOT NULL,
					PRIMARY KEY  (`id`)
				) ENGINE=MyISAM $collate2;
			";
						
			$qStr2 = "
				INSERT INTO $table VALUES(1, 0, '1.0.5');
			";
		break;
		
		
		case 'feusers_meta':
			$qStr = "
				CREATE TABLE IF NOT EXISTS $table (
				  `info_id` int(11) NOT NULL auto_increment,
				  `uid` int(11) NOT NULL,
				  `meta_key` varchar(255) $collate1 NOT NULL,
				  `meta_value` longtext $collate1 NOT NULL,
				  PRIMARY KEY  (`info_id`)
				) ENGINE=MyISAM  $collate2 AUTO_INCREMENT=1
			";			
		break;
		

		case 'zip2tax':
			$qStr = "
				CREATE TABLE IF NOT EXISTS $table (
				  `z2t_ID` bigint(20) NOT NULL AUTO_INCREMENT,
				  `ZipCode` int(11) NOT NULL,
				  `SalesTaxRate` varchar(255) $collate1 NOT NULL,
				  `RateState` varchar(255) $collate1 NOT NULL,
				  `ReportingCodeState` varchar(255) $collate1 NOT NULL,
				  `RateCounty` varchar(255) $collate1 NOT NULL,
				  `ReportingCodeCounty` varchar(255) $collate1 NOT NULL,
				  `RateCity` varchar(255) $collate1 NOT NULL,
				  `ReportingCodeCity` varchar(255) $collate1 NOT NULL,
				  `RateSpecialDistrict` varchar(255) $collate1 NOT NULL,
				  `ReportingCodeSpecialDistrict` varchar(255) $collate1 NOT NULL,
				  `City` varchar(255) $collate1 NOT NULL,
				  `PostOffice` varchar(255) $collate1 NOT NULL,
				  `State` varchar(255) $collate1 NOT NULL,
				  `County` varchar(255) $collate1 NOT NULL,
				  `ShippingTaxable` varchar(255) $collate1 NOT NULL,
				  `PrimaryRecord` varchar(255) $collate1 NOT NULL,
				  PRIMARY KEY (`z2t_ID`)
				) ENGINE=MyISAM $collate2
			";
		break;
		case 'who_activity':
		
			$qStr = " 
			    CREATE TABLE $table (
					`Id` BIGINT( 20 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`Who` VARCHAR( 255 ) NOT NULL ,
					`User_Id` BIGINT( 20 ) NULL ,
					`Last_Activity` DATETIME NOT NULL ,
					`Check_Out` INT( 1 ) NOT NULL DEFAULT '1',
					UNIQUE (`Who`)
				)  ENGINE=MyISAM  $collate2;				
			";
		break;
		
	}
	mysql_query($qStr);	


	if($table_name == 'status')
	{
		$queries = explode("#",$qStr2);

		foreach($queries as $v){
			mysql_query(utf8_encode($v));
		}			
	}
	
	if($table_name == 'countries')
	{
		$queries = explode("+",$qStr2);

		foreach($queries as $v){
			mysql_query(utf8_encode($v));
		}			
	}		
	
	if($table_name == 'canadian_tax')
	{
		$queries = explode("+",$qStr2);

		foreach($queries as $v){
			mysql_query(utf8_encode($v));
		}			
	}		
	
	if($table_name == 'invoices')
	{
		$queries = explode("+",$qStr2);

		foreach($queries as $v){
			mysql_query(utf8_encode($v));
		}			
	}