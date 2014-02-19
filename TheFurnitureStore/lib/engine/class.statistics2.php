<?php
	class Statistics {
		
		function graph_monthly_sales($row_height,$txt_color){

			global $OPTION, $wpdb;

			$currency = $OPTION['wps_currency_code'];

			// 1. get all affected datasets from the last 12 months
			$table 	= is_dbtable_there('orders');
			
			// month now
			$month_now 	= date(n);
			$year_now	= date(Y);
			
			// month now - 11  |   ranges usually cross 2 diff. years
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

			$wps_time_addition = get_option('wps_time_addition');

			$qStr 	= "SELECT * FROM $table WHERE ((order_time BETWEEN $start AND $end) AND level IN ('4','5','6','7','8')) OR ((layaway_date BETWEEN $start AND $end) AND level = '3')";

			$qStr 	= "SELECT * FROM $table WHERE ((order_time BETWEEN $start AND $end) AND level IN ('4','5','6','7','8') AND layaway_process = 0) OR ((layaway_date BETWEEN $start AND $end) AND level IN ('3','4','5','6','7','8') AND layaway_process = 1)";
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
				while($row = mysql_fetch_assoc($res)){
					
					foreach($ranges as $k => $v){
					
						$r 		= explode("-",$v);
						$order_time = $row[order_time];
						if ($row[layaway_process] == 1) {
							$order_time = $row[layaway_date];
						}
						
						if(($order_time >= $r[0]) && ($order_time <= $r[1])){
							//$sum[$k] 		= $sum[$k] + $row[amount];
							$sum[$k] 		= $sum[$k] + ($row[net] + $row[shipping_fee]);
							$sales_data[$k] = sprintf("%01.2f", $sum[$k]);
						}		
					}	
				}
				
				// 4. Maximum value of data 
				$max_data 	= max($sales_data);	
				// 5. Maximum pixel of col 
				$max_col 	= 500;
				
				// 6. get some nice colors 
				$data		= array();	
				$colors		= array();	
				$colors[0]	= '#D54E21';
				$colors[1]	= '#464646';
				$colors[2]	= '#E4F2FD';
				$colors[3]	= '#D54E21';
				$colors[4]	= '#464646';
				$colors[5]	= '#E4F2FD';
				$colors[6]	= '#D54E21';
				$colors[7]	= '#464646';
				$colors[8]	= '#E4F2FD';
				$colors[9]	= '#D54E21';
				$colors[10]	= '#464646';
				$colors[11]	= '#E4F2FD';
				$a			= 0;
				
				// 7. Put the Column-Data together
				foreach($sales_data as $k => $v){		
					$factor 	= $max_data / $v;
					$height_col	= $max_col / $factor;
					$height_col	= round($height_col,0);
					$data[$k]	= $height_col.'|'.$colors[$a].'|'.$v;	
					$a++;
				}
				
				
				// 8. Sort the array
				ksort($data);
				$graph = NULL;
				
				// 9. Output graph
				foreach($data as $k => $v){
				
					$mdata 	= explode("|",$v);
					$mlabel = explode("-",$k);
					$graph	.= "<table border='0' cellspacing='0'><tr>
					<td width='60' style='color: $txt_color;'>$mlabel[1]/$mlabel[2]<td>
					<td style='width: {$mdata[0]}px; height: {$row_height}; background-color: {$mdata[1]}; 
					color: $txt_color;'></td><td style='color: $txt_color;'>$".format_price($mdata[2])."</td>
					</tr></table>
					";
				}
			}
			else{
				$graph = "<div class=''>".__('- Not yet enough data for graphs available -','wpShop')."</div>";
			}
			
		return $graph;
		}
	}
?>