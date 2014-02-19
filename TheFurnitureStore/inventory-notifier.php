<?php

// In case you like to use a cron job, move this page into the wordpress root directory and uncomment the link below 
include 'wp-load.php';
	
	// how many items are now on 0 or the threshold? 		
	$threshold 	= get_option('wps_stock_warn_threshold');
	$table		= is_dbtable_there('inventory');
	$sql		= "SELECT * FROM $table WHERE amount = '$threshold' ORDER BY ID_item";
	$res 		= mysql_query($sql);
	$num 		= mysql_num_rows($res);

	echo "$num articles found.";		
	
	if($num > 0){
		$notification = "Howdy!\n ";		
		$notification .= "Please note - the amount of the following articles is on $threshold and will run out soon: \n\n";
			while($row = mysql_fetch_assoc($res)){

				$notification .= $row['ID_item'].' : ';
				
				unset($row['iid']);
				unset($row['amount']);
				unset($row['ID_item']);

					foreach($row as $k => $v){											
						$info = (strlen($v)< 1 ? NULL : ' '.$k.': '.$v.' - ' );											
						$notification .= $info;
					}
				$notification .= "\n";
			}
	}
	else {
		$notification = "The amount for all articles is sufficient. \n\n";	
	}
	
		//for testing purposes		
		#echo $notification;

		//mail to shop owner
		$to 			= $OPTION['wps_stock_warn_email'];											
		$from 			= $OPTION['wps_shop_email'];
		$shopname		= $OPTION['wps_shop_name'];
		$subject 		= 'Inventory notification';
		$text 			= $notification;
		mail($to,$subject,$text,"From: $shopname <$from>");		
	
?>