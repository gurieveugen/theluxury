<?php

	class Digitalgoods {
	
		function create_dlink($file,$who,$option=1){
			global $OPTION;

			$table 			= is_dbtable_there('dlinks');
			
			
			if($option == 1){
				$dload_mdir		= 'wp-content/themes/'. WPSHOP_THEME_NAME .'/dl/';

				// 1. We create a random string
				$rs			= date("ymd-") . make_random_str(25) . '/';
				$dirpath	= $dload_mdir . $rs; 
				
				// 2. A directory with random string is created 
				$fb = mkdir($dirpath);
				if($fb == FALSE){echo error_explanation('0005','yes');}			
				
					
				// 2. We copy the file to download from a secure location to the download directory
				$dfile 		= $dirpath . $file;
				
				$secure_dir = $OPTION['wps_master_dir'];
				$source		= $secure_dir . $file;
			
			copy($source,$dfile);
			}
			
			if($option == 2){
			
				$dfile			= date("ymd-") . make_random_str(25);
			}
			
			
			// 3. Additionally we save the link in a db_table 'dlinks'
			
						// add new dlink in table 
						$column_array	= array();
						$value_array	= array();           
							
						$column_array[0]	= 'did';		$value_array[0]	= '';
						$column_array[1]	= 'dlink';		$value_array[1]	= $dfile;
						$column_array[2]	= 'who';		$value_array[2]	= $who;
						$column_array[3]	= 'tstamp';		$value_array[3]	= time();
						$column_array[4]	= 'dfile';		$value_array[4]	= $file;
						$column_array[5]	= 'duration';	$value_array[5]	= $OPTION['wps_duration_links'];			
						
						db_insert($table, $column_array, $value_array);
		 

			// 4. We could also sent an email with the download link - optional 
			if($option == 3){
				send_email();
			}


			// 6. Can be used to echo the link 
			return $dfile;
		}

		function delete_dlink(){
			global $OPTION;

			$table 		= is_dbtable_there('dlinks');
			
			$now 		= time();
			$duration 	= $OPTION['wps_duration_links'];
			$limit 		= $now - ($duration); // duration = seconds
			
			$qStr 	= "SELECT * FROM $table WHERE tstamp < $limit";
			$res 	= mysql_query($qStr);
			$num	= mysql_num_rows($res);
			
			if($num > 0){
				while($row = mysql_fetch_assoc($res)){
			
					// remove file 
					if (file_exists($row['dlink'])) {
						unlink($row['dlink']);
					}
					// remove directory 
					$l_len		= strlen($row['dlink']);
					$f_len 		= strlen(substr(strrchr($row['dlink'],'/'),1));
					$dirpath	= substr($row['dlink'], 0, ($l_len - $f_len)); 
					if (file_exists($dirpath)) {
						rmdir($dirpath);
					}
						
				}
			}
			// in case there is key and related Session vars araound
				unset($_SESSION['d_option']);
				unset($_SESSION['dlinks']);
				unset($_SESSION['keys']);
				unset($_SESSION['who']);
		return TRUE;
		}

		function get_lkeys($fname,$who){

			// 1. We get the amounts from the shopping cart table 
			$table 		= is_dbtable_there('shopping_cart');
			$qStr 		= "SELECT item_amount FROM $table WHERE who = '$who' AND item_file = '$fname' LIMIT 1";
			$res 		= mysql_query($qStr);	
			$row 		= mysql_fetch_assoc($res);
			$amount 	= $row[item_amount];
			

			// 2. Get keys from lkeys table based on fname and amount 
			$lkeys		= NULL;
			$table 		= is_dbtable_there('lkeys');
			$qStr 		= "SELECT * FROM $table WHERE filename = '$fname' AND used = '0' LIMIT $amount";
			$res 		= mysql_query($qStr);
			
			while($row = mysql_fetch_assoc($res)){
			
				$lkeys .= $row[lkey] . '#';	
			}
			$lkeys = substr($lkeys, 0, -1); 
			
			// 3. Update lkey table - the used lkeys get used = 1	
			$keys = explode("#",$lkeys);
			foreach($keys as $v){
							$column_value_array 	= array();
							$where_conditions 		= array();
							
							$column_value_array[used] 	= '1';	
							$column_value_array[who] 	= $who;						
							$where_conditions[0]		= "lkey = '$v'";
												
							db_update($table, $column_value_array, $where_conditions);
			}
			
			// 4. If necessary lkey warning to merchant
			lkeys_enough($fname);
			
			
		return $lkeys; 
		}

		function download_counter($dlink,$who){

			$table 	= is_dbtable_there('dlinks');	
			$qStr 	= "UPDATE $table 
						SET counter=counter+1 
						WHERE 
						dlink = '$dlink' AND who ='$who'";
			mysql_query($qStr);	
			
		return 'DONE';
		}
	}
?>