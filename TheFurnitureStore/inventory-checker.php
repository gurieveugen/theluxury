<?php
exit;
// In case you like to use a cron job, move this page into the wordpress root directory and uncomment the link below 
#include 'wp-load.php'; 
include '../../../wp-load.php';


	$table1 = is_dbtable_there('orders');
	$table2 = is_dbtable_there('shopping_cart');
	$table3 = is_dbtable_there('inventory');

	$sql1 	= "SELECT who FROM $table1 WHERE level IN('0','4','5','6','7','8')";
	$sql2 	= "SELECT who, item_id, item_amount, item_attributs FROM $table2";
	
	// we check who ordered
	$res 	= mysql_query($sql1);
	while($row = mysql_fetch_assoc($res)){
			$arr1[] = $row['who'];
	}	
	
	// we check who has what items in shopping cart
	$res 	= mysql_query($sql2);
	while($row = mysql_fetch_assoc($res)){
			$arr2[] = array('who' => $row['who'], 'item_id' => $row[item_id], 'amount' => $row['item_amount'], 'attr' => $row['item_attributs']);
	}	
	
	// timelimit is defined
	$tlimit 	= time() - $OPTION['wps_inventory_cleaning_interval'];

	
	$del_attr 	= NULL;
	$info		= NULL;
	$found		= 0;
	
	
	// is the who of the order table found in shopping cart table with a time after time limit? 
	foreach($arr2 as $k => $v){
	
		if(!in_array($v['who'],$arr1)){
		
			$found++;
			$info .= $v['who'] . "<br/>";	
		
			$t = explode("-",$v['who']);
			if($t[0] < $tlimit){
		
				$w = "UPDATE $table3 SET amount = amount+$v[amount] WHERE ID_item = '$v[item_id]' AND ";
			
				if(!empty($v[attr])){
				
					$del_attr = NULL;
					
					$p = explode("#",$v[attr]);
					foreach($p as $at){
							$a = explode("=",$at);
					
							$w .= strtolower($a[0])." = '$a[1]' AND ";
					}
					$del_attr = "AND item_attributs = '$v[attr]'";
				}
				$w = substr($w,0,-4);
				
				$y = "DELETE FROM $table2 WHERE who = '$v[who]' $del_attr";			
				$info .= "<span style='font-size:13px;'>$v[who] is not in orders table - Item-ID: $k - $v[amount] - $w | $y</span>" . "<br/>";
				
				//mysql_query($w);
				mysql_query($y);
				
				$info .= "$v[who] - done." . "<br/>";
			}
		}		
	}
	
	if($found == 0){
		$info = 'No orphaned articles found.';
	}
	
	
	// test feedback for owner 
	#echo $info;	
	
	// test notification via email
	/*
	$to 			= get_option('wps_shop_email');
	$subject 		= 'Inventory cleaned';
	$text 			= "Inventory was cleaned up. $info";
	mail($to,$subject,$text,"From: Test <$to>");
	*/
?>