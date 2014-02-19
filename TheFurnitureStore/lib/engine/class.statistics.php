<?php
	class Statistics {
		
		function graph_monthly_sales($row_height,$txt_color){

			global $OPTION, $wpdb;

			$table = is_dbtable_there('orders');
			$wps_time_addition = $OPTION['wps_time_addition'];
			$currency = $OPTION['wps_currency_code'];

			// 1. get all affected datasets from the last 12 months
			$start = mktime(0, 0, 0, date('m') + 1, 1, date('Y') - 1);
			$end   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));

			$sorders = $wpdb->get_results("SELECT * FROM $table WHERE ((order_time BETWEEN $start AND $end) AND level IN ('4','5','6','7','8') AND layaway_process = 0) OR ((layaway_date BETWEEN $start AND $end) AND level IN ('3','4','5','6','7','8') AND layaway_process = 1)");
			$num = count($sorders);
			if($num > 0) {
				$sales_data = array();
				foreach($sorders as $sorder) {
					$order_time = $sorder->order_time;
					if ($sorder->layaway_process == 1) {
						$order_time = $sorder->layaway_date;
					}
					$order_time = $order_time + $wps_time_addition;
					$ok = date("Ym", $order_time);
					$sum = $sorder->net + $sorder->shipping_fee + $sorder->tax;
					$sales_data[$ok] += $sum;
				}
				ksort($sales_data);

				$colors		= array();
				$colors[0]	= '#D54E21';
				$colors[1]	= '#464646';
				$colors[2]	= '#E4F2FD';

				$max_col  = 500;
				$max_data = max($sales_data);
				
				// output graph
				$c = 0;
				$graph = '';
				foreach($sales_data as $d => $v){
					$factor = $max_data / $v;
					$wd	= $max_col / $factor;
					$graph .= '<table border="0" cellspacing="0"><tr>';
					$graph .= '<td width="60">'.substr($d, 4).'/'.substr($d, 0, 4).'<td>';
					$graph .= '<td style="width:'.$wd.'px;height:25px;background-color:'.$colors[$c].'">&nbsp;</td>';
					$graph .= '<td>&nbsp;$'.format_price($v).'</td>';
					$graph .= '</tr></table>';
					$c++;
					if ($c > 2) { $c = 0; }
				}
			}
			else{
				$graph = "<div class=''>".__('- Not yet enough data for graphs available -','wpShop')."</div>";
			}
			
		return $graph;
		}
	}
?>