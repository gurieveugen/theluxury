<?php
//public functions
function provide_tax_data($order,$taxable_amount){

	global $OPTION;								
	
	$tax_data = array();

		//what state?
		$state 				= get_option('wps_salestax_state');
		$tax_data['state']	= $state;
		
		//is buyer from same state as merchant?
		// we also check table of delivery address
		$table2 = is_dbtable_there('delivery_addr');
		$qStr 	= "SELECT * FROM $table2 WHERE who = '$order[who]' LIMIT 0,1";
		$res 	= mysql_query($qStr);
		$row	= mysql_fetch_assoc($res);
		
		if(strlen($row['state']) > 0){
			$buyer_state 		= $row['state'];
			$destination_zip	= $row['zip'];		
		}
		else {
			$buyer_state 		= $order['state'];
			$destination_zip	= $order['zip'];			
		}
		
		

		//if yes - we continue 
		if($state === $buyer_state){
		
			$tax_data['sameState'] = '1';
		
		
			//what tax sourcing rule
			switch(get_option('wps_salestax_sourcing_r')){
			
				case 'origin':
					//calculate tax amount
					//if voucher present - subtract before 
					$tax_data['rate']				= (float) get_option('wps_salestax_rate');
					$tax_data['amount']				= ($taxable_amount / 100) *  $tax_data['rate'];
					$tax_data['shipping_taxable']	= (get_option('wps_salestax_onshipping') == 'No' ? 0 : 1);
				break;			
				
				case 'destination':
					//external data from e.g. zip2tax needed
					$table 	= is_dbtable_there('zip2tax');
					$zip 	= $destination_zip;
					$qStr 	= "SELECT * FROM $table WHERE ZipCode = '$zip'";
					$res 	= mysql_query($qStr);
					$num 	= mysql_num_rows($res);
					
					if($num > 0){
						$row 	= mysql_fetch_assoc($res);
						
						//calculate tax amount
						//if voucher present - subtract before 
						$tax_data['amount']				= ($taxable_amount / 100) * (float) $row['SalesTaxRate'];
						$tax_data['rate']				= $row['SalesTaxRate'];
						$tax_data['shipping_taxable']	= $row['ShippingTaxable'];					
					} else {
						echo "<div class='error'>";
						echo __('Please check if your table "_wps_zip2tax" is empty.','wpShop');
						echo ' ';
						echo __('You need data from Zip2Tax to calculate destination based US tax rates. Go to ','wpShop');
						echo "<a href='http://www.zip2tax.com/z2t_services.asp' target='_blank'>".__('http://www.zip2tax.com/z2t_services.asp','wpShop')."</a>";
						echo __(' and from there select the #3 Sales Tax Table - for your State','wpShop');
						echo "</div>";
					}
				break;			
				
				case 'none':

					
				break;
			}
		}
		else {
			$tax_data['sameState'] = '0';
		}

			/*
			echo "
			<br/>
			<small>
				$LANG[incl]
				"; 
				echo $OPTION['wps_tax_percentage']; 
				echo "% "; 
				echo $OPTION['wps_tax_abbr']; 
				echo "
			</small>
			";		
			*/
					
return $tax_data;
}



function display_tax_data_backend(){

		global $OPTION;
		
			$table 	= is_dbtable_there('orders');
				
			//Total sum 
			$qStr 	= "SELECT tax FROM $table WHERE level IN ('4','5','6','7')";
			$res 	= mysql_query($qStr);
			
			$total_sum = NULL;
			
			while($row = mysql_fetch_assoc($res)){
				if(strlen($row['tax']) > 1){
					$total_sum = (float) $total_sum + (float) $row['tax'];
				}
			}
			
			echo "<h3>US: Sales Tax</h3>";
			echo "Tax due  - total: ";		
			echo $total_sum .' '.$OPTION['wps_currency_code'];
			
			
		//monthly view itemized 
			echo "<br/><br/>The last 12 months:<br/>";
			
		
			$currency = $OPTION['wps_currency_code'];

			// 1. get all affected datasets from the last 12 months
			
			// month now
			$month_now 	= date(n);
			$year_now	= date(Y);
			
			// month now - 11  |   ranges usually across 2 diff. years
			if($month_now > 11){  // this should be only in december the case :-) 
				$start_month 	= $month_now - 11;
				$year_start		= $year_now;
			}
			else {
				$mrest 			= 11 - $month_now;		
				$start_month	= 12 - $mrest;
				$year_start		= (int) $year_now;
				$year_start--;
			}
				
			// 1st of month 
			$start 		= mktime(0, 0, 0, $start_month, 1, $year_start);	
			// last of month 
			$end		= mktime(23, 59, 59, $month_now, date(t), $year_now);	

			
			$qStr 	= "SELECT * FROM $table
						WHERE 
							(order_time BETWEEN $start AND $end) AND level IN ('4','5','6','7')";
			$res 	= mysql_query($qStr);
			$num 	= mysql_num_rows($res);
			
			if($num > 0)
			{
			
				// 2. Create monthly ranges 
				$ranges = array();
				$sorter = 'abcdefghijklmn';	// the array key has to start with a character 	
				
				
				for($m = $start_month,$i=0;$i < 12; $i++,$m++){
										
					if($m > 12){
						$mon 			= $m - 12;
						$yearOfmonth	= (int) $year_start;
						$yearOfmonth++;		
					}
					else{
						$mon 			= $m;
						$yearOfmonth	= $year_start;
					}
							
					// 1st of month 
					$day1 		= mktime(0, 0, 0, $mon, 1, $yearOfmonth);	
					// how many days 
					$num_days 	= date(t,$day1);
					// last of month 
					$day_last 	= mktime(23, 59, 59, $mon, $num_days, $yearOfmonth);		
					
					$key			= $sorter[$i].'-'.$mon.'-'.$yearOfmonth;
					$ranges[$key] 	= "$day1-$day_last";		
				}
				
				// 3. Sort data according to month 	
				$sales_data = array();
				$sum 		= array();	
				$state		= array();
				$county		= array();
				$city		= array();
				
				
				$src_rule 	= get_option('wps_salestax_sourcing_r');
				$table2 	= is_dbtable_there('zip2tax');
				
				while($row = mysql_fetch_assoc($res)){
					foreach($ranges as $k => $v){
						
							$r 		= explode("-",$v);
							
							if(($row[order_time] >= $r[0]) && ($row[order_time] <= $r[1])){
							
								// for destination rule
								if($src_rule == 'destination'){
										$zip	= (int) trim($row['zip']);
																				
										$qStr 	= "SELECT RateState,RateCounty,RateCity FROM $table2 WHERE ZipCode = $zip LIMIT 0,1";
										$res2 	= mysql_query($qStr); 
										$row2 	= mysql_fetch_assoc($res2);										
										
										$shipping		= (get_option('wps_salestax_onshipping') == 'No' ? 0.00 : (float) $row['shipping_fee']);
										
										$state[$k]		= $state[$k] + ((((float) $row['net'] + $shipping) / 100) * (float) $row2['RateState']);
										$county[$k]		= $county[$k] + ((((float) $row['net'] + $shipping) / 100) * (float) $row2['RateCounty']);
										$city[$k]		= $city[$k] + ((((float) $row['net'] + $shipping) / 100) * (float) $row2['RateCity']);		
								}
								
								// for origin rule 
								$sum[$k] 		= $sum[$k] + $row['tax'];
								
								if(get_option('wps_salestax_onshipping') == 'No'){
									$sales_data[$k] = $sales_data[$k] + (float) $row['net'];
								}
								else {
									$sales_data[$k] = $sales_data[$k] + (float) $row['net'] + (float) $row['shipping_fee'];								
								}
							}		
					}	
				}			
			
				echo "<table border='1' style='width: 600px;'><tr><th>Month</th><th>Composite</th><th>State</th><th>County</th><th>City</th></tr>";
			
				if($src_rule == 'origin'){
					$state 		= (float) get_option('wps_salestax_rate_state');
					$county 	= (float) get_option('wps_salestax_rate_county');
					$city		= (float) get_option('wps_salestax_rate_city');
					$tax_rate 	= (float) get_option('wps_salestax_rate');
				}
				if($src_rule == 'destination'){
					$table 	= is_dbtable_there('zip2tax');
				}
				
			
				foreach($sum as $k => $v){
					
					$date_parts = explode("-",$k);
					
					//origin based
					if($src_rule == 'origin'){				
						$tax4state	= ($sales_data[$k] / 100) * $state;
						$tax4county	= ($sales_data[$k] / 100) * $county;
						$tax4city	= ($sales_data[$k] / 100) * $city;
					}
					
					// destination based 
					if($src_rule == 'destination'){						
						$tax4state	= ($sales_data[$k] / 100) * (float) $row2['RateState'];
						$tax4county	= ($sales_data[$k] / 100) * (float) $row2['RateCounty'];
						$tax4city	= ($sales_data[$k] / 100) * (float) $row2['RateCity'];		
										
						$tax4state	= $state[$k];
						$tax4county	= $county[$k];
						$tax4city	= $city[$k];							
					}
					
					
					
					echo "<tr>";
						echo "<td>{$date_parts[1]}/{$date_parts[2]}</td><td>$v</td><td>$tax4state</td><td>$tax4county</td><td>$tax4city</td>";
					echo "</tr>";
				}
			echo "</table>";			
			}
			else {
				echo __('- Not enough data yet -','wpShop');
			}
}


function round_tax_amount($amount){

	$rounding_op 	= get_option('wps_salestax_dec_rounding');
	
	if($rounding_op != 'not'){
		$precision 		= (int) $rounding_op;
		$result 		= round($amount,$precision);		
	}
	else{
		$result = $amount;
	}

return $result;
}

function show_cart_tax_info(){

	global $LANG,$OPTION;

	switch($OPTION['wps_salestax_sourcing_r']){
	
		case 'origin':
			echo __('without tax','wpShop');
		break;			
		case 'destination':
			echo __('without tax','wpShop');
		break;		
		case 'taxincluded':
			echo "$LANG[incl]";
			echo $OPTION['wps_tax_percentage']; 
			echo "% "; 
			echo $OPTION['wps_tax_abbr']; 
		break;		
		case 'none':
			echo __('No sales taxes charged','wpShop');
		break;			
	}
}



function tax_invoice_pdf_addition($CART,$order){

	global $OPTION;

	$data = array();
	
	if(isset($OPTION['wps_salestax_onshipping'])){
	$taxable_amount = ($OPTION['wps_salestax_onshipping'] == 'Yes' ? $CART['total_price'] + $order['shipping_fee'] : $CART['total_price']);
	$tax_data 		= provide_tax_data($order,$taxable_amount);
	
	
	if($tax_data['sameState'] != 0){
		$tax_info = $tax_data['rate'] .'% '.$OPTION['wps_tax_abbr'].': '. round_tax_amount($tax_data['amount']) . ' '.$OPTION['wps_currency_code'];  			
	}
	else{
		$tax_info = 'no_show';
	}
	
	
		$data['display']	= 'yes';
		
		if($OPTION['wps_salestax_onshipping'] == 'Yes'){
			$data['without_ship']	= 'no';
			$data['with_ship']		= 'yes';	
		}
		else {
			$data['without_ship']	= 'yes';
			$data['with_ship']		= 'no';			
		}
		
		$data['tax_info'] 	= $tax_info;
		$data['amount'] 	= $tax_data['amount'];	
	}
	else {
		$data['display']	= 'no';
	}
	
return $data;
}



function tax_email_confirm_addition($CART,$order){

	global $OPTION;

	$data = array();
	
	if(isset($OPTION['wps_salestax_onshipping'])){
		$taxable_amount = ($OPTION['wps_salestax_onshipping'] == 'Yes' ? $CART['total_price'] + $order['shipping_fee'] : $CART['total_price']);
		$tax_data 		= provide_tax_data($order,$taxable_amount);
		
		
		if($tax_data['sameState'] != 0){
			$tax_label 	= $tax_data['rate'] .'% '.$OPTION['wps_tax_abbr'].': ';
		}
		else {
			$tax_label 	= 'no_show';	
		}
	
		$data['display']	= 'yes';
		
		if($OPTION['wps_salestax_onshipping'] == 'Yes'){
			$data['without_ship']	= 'no';
			$data['with_ship']		= 'yes';	
		}
		else {
			$data['without_ship']	= 'yes';
			$data['with_ship']		= 'no';			
		}
		
		$data['tax_info'] 	= $tax_label;
		$data['amount'] 	= $tax_data['amount'];	
	}
	else {
		$data['display']	= 'no';
	}
	
return $data;
}




//private functions
function calculate_composite_tax_rate(){

	$state 		= (float) get_option('wps_salestax_rate_state');
	$county 	= (float) get_option('wps_salestax_rate_county');
	$city		= (float) get_option('wps_salestax_rate_city');
		
	$rate 		= $state + $county + $city;
	
	//we also add the composite rate into the '..options' db table
	update_option('wps_salestax_rate',$rate);

return $rate;
}




function get_us_states(){

	global $OPTION;
	
	$table 	= is_dbtable_there('countries');
	$qStr 	= "SELECT states FROM $table WHERE abbr = '$OPTION[wps_shop_country]' LIMIT 0,1";
	$res 	= mysql_query($qStr);
	$row 	= mysql_fetch_assoc($res);

	$states	= array();
	$parts1 = explode("#",$row['states']);	
	
	foreach($parts1 as $v){
		$parts2 	= explode('|',$v);
		$states[] 	= $parts2[1] .'|'.$parts2[0];
	}
	
return $states;
}




$tax_MODULE_DATA = array(
					/*
					array(  	"name" 	=> __('Yes - calculate sales tax','wpShop'),
								"desc" 	=> __('Check this setting if you want the system to calculate taxes for orders delivered to the same state where your Shop is located.','wpShop'),
								"id" 	=> $CONFIG_WPS[shortname]."_tax_calculation_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					*/
								
					array(  	"name" 	=> __('Merchant State','wpShop'),
								"desc" 	=> __('Where the shop is based.','wpShop'),			
								"id" 	=> $CONFIG_WPS['shortname']."_salestax_state",	
								"type" 	=> "select2",
								"std" 	=> "AL",
								"vals" 	=> get_us_states()),
								
					array(    	"name" 	=> __('State Sourcing Rule and Approach','wpShop'),
								"desc" 	=> "<b>".__('Based on the ','wpShop')."<a target='_blank' href='http://blog.sabrix.com/blog/transaction-tax-talk/0/0/sales-tax-sourcing-101-origin-vs-destination'>".__('Sales Tax Sourcing 101 - Origin vs. Destination','wpShop')."</a>".__(' article','wpShop')."</b><br/><b>".  
								__('Origin based: ','wpShop')."</b>".__('the tax rate of your official merchant address is applied. The value of "Composite sales tax rate" (see below) is taken.','wpShop')."<br/><b>". 
								__('Destination based: ','wpShop')."</b>".__(' the tax rate of the order delivery address is used.','wpShop')."<br/><b>". 
								__('My state has no sales tax: ','wpShop')."</b>".__(' in some States there is no sales tax at all','wpShop')."<br/><b>".
								__('Tax is included in prices: ','wpShop')."</b>".__(' for simplicities sake you might tell your buyers this','wpShop')."<br/>",
								"id" 	=> $CONFIG_WPS['shortname']."_salestax_sourcing_r",		
								"std" 	=> "origin",
								"vals" 	=> array(	
													__('Origin based','wpShop')."|origin",
													__('Destination based','wpShop')."|destination",
													__('My state has no sales tax','wpShop')."|none",
													__('Tax is included in prices','wpShop')."|taxincluded"
												),
												
								"type" 	=> "select2"),	
					array(    	"name" 	=> __('State tax rate','wpShop'),
								"desc" 	=> __('Set the (percentage) state sales tax rate using a number or a decimal number here, which is applied for your official merchant address (nexus).','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_salestax_rate_state",	
								"std" 	=> "6.00",
								"vals" 	=> array("", "Yes"),
								"type" 	=> "text"),
								
					array(    	"name" 	=> __('County tax rate','wpShop'),
								"desc" 	=> __('Set the (percentage) county sales tax rate using a number or a decimal number here, which is applied for your official merchant address (nexus).','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_salestax_rate_county",	
								"std" 	=> "1.00",
								"type" 	=> "text"),
								
					array(    	"name" 	=> __('City tax rate','wpShop'),
								"desc" 	=> __('Set the (percentage) city sales tax rate using a number or a decimal number here, which is applied for your official merchant address (nexus).','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_salestax_rate_city",		
								"std" 	=> "1.00",
								"type" 	=> "text"),
								
					array(    	"name" 	=> __('Composite sales tax rate','wpShop'),
								"desc" 	=> "<strong style='font-size: 1.2em;'>".calculate_composite_tax_rate()." %</strong><br/><small>".__('If the above field is empty or has the wrong amount, make sure you set the correct values in the above 3 tax value fields and save your settings. Then return here.','wpShop')."</small>",
								"id" 	=> NULL,
								"std" 	=> NULL,
								"type" 	=> "text-link"),
																
					array(  	"name" 	=> __('Include shipping in tax calculation','wpShop'),
								"desc" 	=> "<b>".__('Selecting "No": ','wpShop')."</b>".__('Tax is calculated BEFORE Shipping Fees are applied','wpShop')."<br/><b>". 
								__('Selecting "Yes": ','wpShop')."</b>".__(' Tax is calculated AFTER Shipping Fees are applied','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_salestax_onshipping",		
								"std" 	=> "Yes",
								"vals" 	=> array("No", "Yes"),
								"type" 	=> "select"),								

								
					array(  	"name" 	=> __('External Tax Data Provider','wpShop'),
								"desc" 	=> __('If your Shop is based on a "Destination" State (please see "State Sourcing Rule and Approach" setting above) then you will need to use an External Tax Data Provider for the most accurate calculation of Sales Tax.','wpShop')."<br/>".__('Currently the only integrated service is','wpShop')."<a href='http://www.zip2tax.com/z2t_services.asp' target='_blank'>".__(' zip2tax ','wpShop')."</a>".__('-to use this service you need a subscription for a "Sales Tax Table" of your state with them (see #3 Sales Tax Tables when on their site). Please refer to their site for more information.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_salestax_dprovider",		
								"std" 	=> "Yes",
								"vals" 	=> array("zip2tax"),
								"type" 	=> "select"),	
								
					array(  	"name" 	=> __('Round-up Tax Amount Digits','wpShop'),
								"desc" 	=> __('The calculated tax amount may have several decimal digits like 4.015478 - you can optionally round up the decimal digits here or leave them as they are (with "not").','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_salestax_dec_rounding",	
								"std" 	=> "not",
								"vals" 	=> array('not','2','3','4'),
								"type" 	=> "select"),	
					/*			
					array(  	"name" 	=> __('Display tax estimator in shopping cart','wpShop'),
								"desc" 	=> __('If this should not be done, choose NO','wpShop'),
								"id" 	=> $CONFIG_WPS[shortname]."_salestax_estimator",
								"std" 	=> "Yes",
								"vals" 	=> array("No", "Yes"),
								"type" 	=> "select"),	
					*/
								
					);
?>