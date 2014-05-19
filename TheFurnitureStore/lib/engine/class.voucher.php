<?php

	class Voucher {	
		
		function save(){
		
			$filename      	= 'csvfile';
			$table_name1   	= is_dbtable_there('vouchers');
									
			$delimit       	= "\n";
			$prod_quantity	= 1000;
			$row 			= 1;
			$vouchers		= 1;
			$error			= 0;
			$err_message 	= __('There was an error with the file upload!','wpShop');
			
			
			if(strlen($_FILES[$filename]['tmp_name']) < 1){						
				$err_message .= "<br/>".__('You forgot to enter a .txt file.','wpShop');
				$error++;
			}
			if (substr($_FILES[$filename]['name'],-4) != '.txt'){ 
				$err_message .= "<br/>".__('The file needs to have the ending .txt','wpShop');
				$error++;
			}
			if ($_FILES[$filename]['size'] > 102400){ // size = not more than 100kB
				$err_message .= "<br/>".__('Your file exceeds the size of 100 kB.','wpShop');
				$error++;
			}						
			if ($_FILES[$filename]['error'] !== UPLOAD_ERR_OK){
				$err_message .= "<br/>".file_upload_error_message($_FILES[$filename]['error']);
				$error++;
			}
			
			if(!$_POST[voucher_amount]){
				$err_message .= "<br/>".__('Please enter amount of your vouchers.','wpShop');
				$error++;
			}
			$result = $err_message;
			
			if($error == 0){

				$handle = fopen($_FILES[$filename]['tmp_name'], "r");

				while(($data = fgetcsv($handle, 1000, $delimit)) !== FALSE){
				
					$row++;
					$len 		= strlen($data[0]);
					$data[0]	= trim($data[0]);	
					
					if($len > 0){
					###### Insert into voucher table #####################
					$column_array 	= array();
					$value_array   	= array();
					global $current_user;
					global $user_level;
					get_currentuserinfo(); // grabs the user info and puts into vars
					$user_ID = $current_user->ID;

					$column_array[0] 	= 'vid';      			$value_array[0]   	= '';
					$column_array[1] 	= 'vcode';      		$value_array[1]   	= $data[0];
					$column_array[2] 	= 'voption';      		$value_array[2]   	= trim($_POST['voucher_option']);
					$column_array[3] 	= 'vamount';      		$value_array[3]   	= trim($_POST['voucher_amount']);
					$column_array[4] 	= 'time_issued';   		$value_array[4]   	= date("F j, Y");
					$column_array[5] 	= 'user_id';   			$value_array[5]   	= $user_ID;
					if($user_level == 0)
					{
						$column_array[6] 	= 'c_by';   			$value_array[6]   	= 'S';
					}
					$result_db1 = db_insert($table_name1, $column_array, $value_array);

											 if($result_db1 != 1){
												 echo "<p>".__('There was a problem with','wpShop')." $table_name1 !</p>\n";
											 }
					$vouchers++;
					}  
				}      
				fclose($handle);
				$vouchers--;
				$result = "
					<h2>".__('Upload sucessful!','wpShop')."</h2>".__('You uploaded and saved ','wpShop').
					$vouchers.__(' new vouchers.','wpShop')."";

			}

		return $result;
		}		
		
		
		function reseller(){
		
			$table_name1   	= is_dbtable_there('vouchers');
			
			###### Insert into voucher table #####################
			$column_array 	= array();
			$value_array   	= array();
			
			$column_array[0] 	= 'vid';      			$value_array[0]   	= '';
			$column_array[1] 	= 'vcode';      		$value_array[1]   	= trim($_POST['voucher_code']);
			$column_array[2] 	= 'voption';      		$value_array[2]   	= trim($_POST['voucher_option']);
			$column_array[3] 	= 'vamount';      		$value_array[3]   	= trim($_POST['voucher_amount']);
			$column_array[4] 	= 'receiver';      		$value_array[4]   	= trim($_POST['receiver']);
			$column_array[5] 	= 'receiver_mail';      $value_array[5]   	= trim($_POST['receiver_mail']);
			$column_array[6] 	= 'duration';      		$value_array[6]   	= 'indefinite';			
			$column_array[7] 	= 'time_issued';   		$value_array[7]   	= time(); //date("F j, Y");		
			$column_array[8] 	= 'level';   			$value_array[8]   	= '1';	
			global $current_user;
			get_currentuserinfo(); // grabs the user info and puts into vars
			$user_ID = $current_user->ID;
			$column_array[9] 	= 'user_id';   			$value_array[9]   	= $user_ID;
			global $user_level;
			if($user_level == 0)
			{
				$column_array[10] 	= 'c_by';   			$value_array[10]   	= 'S';
			}		
			if(isset($_POST['save_vcode'])){
				$result_db = db_insert($table_name1,$column_array,$value_array);
			}
			$result = ($result_db == 1 ? TRUE : FALSE);
						
		return $result;
		}
		


		function reseller_form_check(){
		
			// empty fields
			if((!$_POST[voucher_code]) || (!$_POST[receiver]) || (!$_POST[receiver_mail])){
				_e('Make sure that all fields are filled out!','wpShop');
				$url = "themes.php?page=functions.php&section=vouchers&action=reseller";
				echo " <a href='$url'> ".__('return','wpShop')."</a>";		
				exit();
			}		
			
			// Vcode long enough?
			if(strlen($_POST[voucher_code]) < 4){
				_e('Your voucher code is too short - it should have at least 4 characters.','wpShop');
				$url = "themes.php?page=functions.php&section=vouchers&action=reseller";
				echo " <a href='$url'>".__('return','wpShop')."</a>";
			exit();
			}	
			
			// is Vcode already taken?
			if($this->code_exist($_POST[voucher_code])){
				_e('This voucher code is already taken.','wpShop');
				$url = "themes.php?page=functions.php&section=vouchers&action=reseller";
				echo " <a href='$url'>".__('return','wpShop')."</a>";
			exit();
			}
			
			// Voucher percentage OK?
			$vamount = (int) $_POST['voucher_amount'];
			if($vamount < 1){
				_e('Please check the percentage of voucher.','wpShop');
				$url = "themes.php?page=functions.php&section=vouchers&action=reseller";
				echo " <a href='$url'> ".__('return','wpShop')."</a>";
			exit();
			}
			
			// email of reseller correct?
			if(NWS_validate_email($_POST[receiver_mail])=== FALSE){
				_e('Please correct the format of the reseller email.','wpShop');
				$url = "themes.php?page=functions.php&section=vouchers&action=reseller";
				echo " <a href='$url'> ".__('return','wpShop')."</a>";
			exit();		
			}
			
		return 'Check-done';
		}
		
		
		function reseller_data(){

			$table 	= is_dbtable_there('vouchers');
			$qStr 	= "SELECT * FROM $table WHERE vcode = '$_REQUEST[vc]' LIMIT 0,1";
			$res	= mysql_query($qStr);
			$row	= mysql_fetch_assoc($res);
			
		return $row; 
		}
		
		
		
		function display_all_resellers(){
			global $current_user;			
			get_currentuserinfo(); // grabs the user info and puts into vars AND user_id = $user_ID
			$user_ID = $current_user->ID;
			global $user_level;
			if($user_level == 0)  $wh = "user_id = $user_ID";
			else $wh = "c_by = 'A'";  
				$table 	= is_dbtable_there('vouchers');
				$qStr 	= "SELECT * FROM $table WHERE level = '1' AND $wh;";		
				$res 	= mysql_query($qStr);
			
		return $res;
		}
		
		
		function sales_report($v_no){

			$report	= array();
			// total amount
			$table 	= is_dbtable_there('orders');
			$query 	= "SELECT SUM(amount) FROM $table WHERE voucher = '$v_no' AND level IN ('4','5','6','7','8')"; 
			$result = mysql_query($query) or die(mysql_error());
			$row 	= mysql_fetch_array($result);

			if(strlen($row['SUM(amount)']) > 0){
				$report[amount] = $row['SUM(amount)'];
			}
			else {
				$report[amount] = '0.00';
			}
			
			
			// rate: per month 
			$now	= time();
			$y_ago	= $now - 31536000;
			$query 	= "SELECT SUM(amount) FROM $table WHERE voucher = '$v_no' AND level IN ('4','5','6','7','8') AND order_time > $y_ago"; 
			$result = mysql_query($query) or die(mysql_error());
			$row 	= mysql_fetch_array($result);			
			$report[rate]  = round($row['SUM(amount)']/12,4);
			
			
			
			// how many orders
			$query 			= "SELECT oid FROM $table WHERE voucher = '$v_no' AND level IN ('4','5','6','7','8')"; 
			$result 		= mysql_query($query) or die(mysql_error());
			$num 			= mysql_num_rows($result);
			$report[orders] = $num;

		return $report;
		}		
		
		

		function reseller_delete($vid){
		
			
			$vid	= (int) trim($vid);	
			$table 	= is_dbtable_there('vouchers');
			$qStr 	= "DELETE FROM $table WHERE vid = $vid";
			$res	= mysql_query($qStr);
	
		return $res;
		}	
			
		
		function display($table,$num_per_page=10){
		
			$vouch_wanted 	= trim($_REQUEST['vouch_wanted']);

			$data			= "<table class='widefat'>
								<thead>
									<tr>
										<th>".__('Code','wpShop')."</th>
										<th>".__('Option','wpShop')."</th>
										<th>".__('Amount','wpShop')."</th>
										<th>".__('Duration','wpShop')."</th>
										<th>".__('Used','wpShop')."</th>
										<th>".__('Date Used','wpShop')."</th>
										<th>".__('Date Issued','wpShop')."</th>
										<th>".__('Remove','wpShop')."</th>
										<th></th>
									</tr>
								</thead>";
								
			$LIMIT 	= pagination_limit_clause($num_per_page);
			global $current_user;
			get_currentuserinfo(); // grabs the user info and puts into vars AND user_id = $user_ID
			$user_ID = $current_user->ID; 
			global $user_level;
			if($user_level == 0)  $wh = "user_id = $user_ID";
			else $wh = "c_by = 'A'"; 
			if(strlen($vouch_wanted) < 1){		
				$qStr 	= "SELECT * FROM $table WHERE duration = '1time' AND $wh ORDER BY vcode ASC $LIMIT";	
			} else {
				$qStr 	= "SELECT * FROM $table WHERE duration = '1time' AND $wh AND vcode LIKE '$vouch_wanted%' ORDER BY vcode ASC $LIMIT";		
			}		
			$res 			= mysql_query($qStr);
			while($row = mysql_fetch_assoc($res)){
				 	 	 	
				$voption = ($row['voption'] == 'P' ? __('Percentage','wpShop') : __('Amount','wpShop'));			
							
				$data .= "<tbody><tr>";
				$data .= "<td>$row[vcode]</td>";  
				$data .= "<td>$voption</td>";  
				$data .= "<td>$row[vamount]</td>";  
				$data .= "<td>$row[duration]</td>";  
				$data .= "<td>$row[used]</td>";  
				$data .= "<td>$row[time_used]</td>";  
				$data .= "<td>$row[time_issued]</td>";  
				$data .= "<td><a href='?page=functions.php&section=vouchers&action=display&subaction=del&vid={$row[vid]}'>X</a></td>";  
				$data .= "</tr></tbody>";
			}
			$data .= "</table>";
			
		return $data;
		}
		
		
		
		function display_resellers($num_per_page=10){
		
			global $OPTION;
		
			$table 			= is_dbtable_there('vouchers');
			$resell_wanted 	= trim($_REQUEST['resellers_wanted']);

			$data	= "<table class='widefat'>
							<thead>
								<tr>
									<th>".__('Name','wpShop')."</th>
									<th>".__('Email','wpShop')."</th>
									<th>".__('Since','wpShop')."</th>
									<th>".__('Code','wpShop')."</th>
									<th>".__('Option','wpShop')."</th>
									<th>".__('Amount','wpShop')."</th>
									<th>".__('Duration','wpShop')."</th>
									<th>".__('Sales total','wpShop')."</th>
									<th>".__('Av./Month','wpShop')."</th>
									<th>".__('Orders total','wpShop')."</th>
									<th>".__('Remove?','wpShop')."</th>
									<th></th>
								</tr>
							</thead>";
								
			$LIMIT 	= pagination_limit_clause($num_per_page);
			global $current_user;
			get_currentuserinfo(); // grabs the user info and puts into vars AND user_id = $user_ID
			$user_ID = $current_user->ID; 
			global $user_level;
			if($user_level == 0)  $wh = "user_id = $user_ID";
			else $wh = "c_by = 'A'"; 
			
			if(strlen($resell_wanted) < 1){		
				$qStr 	= "SELECT * FROM $table WHERE duration = 'indefinite' AND $wh ORDER BY receiver ASC $LIMIT";	
			} else {
				$qStr 	= "SELECT * FROM $table WHERE duration = 'indefinite' AND $wh AND vcode LIKE '$resell_wanted%' ORDER BY receiver ASC $LIMIT";		
			}		
			$res 		= mysql_query($qStr);
		
			while($row = mysql_fetch_assoc($res)){
							
				$voption 	= ($row['voption'] == 'P' ? __('Percentage','wpShop') : __('Amount','wpShop'));
	
				$report 	= $this->sales_report($row[vcode]);
							
				$data .= "<tbody><tr>";
				$data .= "<td>$row[receiver]</td>";  
				$data .= "<td><a href='mailto:{$row[receiver_mail]}'>$row[receiver_mail]</a></td>";  
				$data .= "<td>".date("M j,Y",$row[time_issued])."</td>";  
				$data .= "<td>$row[vcode]</td>";  								
				$data .= "<td>$voption</td>";  
				$data .= "<td>{$row[vamount]}</td>";  
				$data .= "<td>$row[duration]</td>";  								
				$data .= "<td>";
					$data .= '$'.format_price($report['amount']); 
				$data .= "</td>";
				$data .= "<td>";
					$data .= '$'.format_price($report['rate']); 
				$data .= "</td>";
				$data .= "<td>$report[orders]</td>";  
				$data .= "<td>
				<a href='?page=functions.php&section=vouchers&action=reseller&subaction=del&vid={$row[vid]}'>X</td>";  
				$data .= "</tr></tbody>";
			}
			$data .= "</table>";							
			
		return $data;
		}
		

		
		
		function delete($vid){
			global $current_user;
			get_currentuserinfo(); // grabs the user info and puts into vars AND user_id = $user_ID
			$user_ID = $current_user->ID; 
			global $user_level;
			if($user_level == 0)  $wh = "user_id = $user_ID";
			else $wh = "used = 1"; 
			$vid	= (int) trim($vid);	
			$table 	= is_dbtable_there('vouchers');
			$qStr 	= "DELETE FROM $table WHERE vid = $vid AND $wh";
			$res	= mysql_query($qStr);
	
		return $res;		
		}		
		

		
			
		function create_pdf($table,$bgImg,$format='A4'){
		
			require('../../lib/fpdf16/fpdf.php');
		
			#$format = 'A4';
			#$format = 'Letter';
			#$format = get_option('wps_pdfFormat');
			
			$pdf = new FPDF('P','mm',$format);
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',16);
			
			$qStr 			= "SELECT * FROM $table";			
			$res 			= mysql_query($qStr);
			
			$y1 				= 3;
			$y2 				= 33;
			$y3 				= 56;
			$a					= 1;
		
			while($row = mysql_fetch_assoc($res)){
						
					if($a % 6 == 0){
						$y1 				= 3;
						$y2 				= 33;
						$y3 				= 56;
						$a					= 1;
						$pdf->AddPage();
					}		
				
				#$pdf->Image("../../images/vouchers/{$bgImg}",5,$y1,206,50,'JPG'); 
				$pdf->Image("../../images/vouchers/{$bgImg}",5,$y1,200,50,'JPG'); // europe
				#$pdf->Text(67,$y2,"Voucher-No: $row[vcode] - $a"); 
				$pdf->Text(70,$y2,"Voucher-No: $row[vcode]"); 
				$pdf->Line(2,$y3,5,$y3);
				$pdf->Line(205,$y3,208,$y3);				
				
				$y1 += 55;
				$y2 += 55;
				$y3 += 55;
				$a++;
			}
			
			$pdf->Output();
		}
		
		
		
		function upload_bg_img($form_field,$IMAGE_PATH,$max_size=200000){

				$err_mess[0] = '';
				$err_mess[1] = 1;


			if (!$_FILES[$form_field]['name']) {
					  $image_name    = 'none';
				 }
				 else {

				 // produce random string + add it to name of picture
					#$randString = Security::makeRandomString();
					$randString = '1';
				 
				 // Empty spaces in the file name are replaced by a underscore
				 $image_name = str_replace(" ", "_", $_FILES[$form_field]['name']);

				 // we dont use explode since the image name could contain dots
				 $file_ext           = strtolower(strrchr($image_name,"."));
				 $image_remainder    = substr($image_name, 0,-(strlen($file_ext)));

				 $image_name = $image_remainder . '_' . $randString . $file_ext;
				 $_FILES[$form_field]['name']  = $image_name;

				 // Security against file upload attacks
				 if(!is_uploaded_file($_FILES[$form_field]['tmp_name'])){
				 $err_mess[0] .= 'Mistake in the image upload action';
				$err_mess[1]++;
				 }

				 // File size check
				 if (is_uploaded_file($_FILES[$form_field]['tmp_name'])) {
					  if ($_FILES[$form_field]['size']>$max_size) {

				 $err_mess[0] .= 'Sorry - Your image file is too big.<br/>Use a smaller image.\n';
				  $err_mess[1]++;
					  }
				 }

			   // File type check - continues with else further down
				 if (($_FILES[$form_field]['type']=="image/gif") || ($_FILES[$form_field]['type']=="image/pjpeg") || ($_FILES[$form_field]['type']=="image/jpeg")) {

				 $image_type_ok = 'valid';

				 } else {
				 $err_mess[0] .= 'Wrong file type for image file! Only .jpg, .gif or .png possible.<br>';
				  $err_mess[1]++;
				 }

				 // The permanent saving of the file

				  if($err_mess[1] == 1){
				 $save_ok = move_uploaded_file($_FILES[$form_field]['tmp_name'], $IMAGE_PATH . $_FILES[$form_field]['name']);
				  }

				 } // else end


				  if($save_ok !== FALSE){
					$result[0] = "<span class='success'>SUCCESS!</span>";
					   $result[1] = "$image_name";
				  }
				  else {
					$result[0] = 'FAILED';
					   $result[1] = $err_mess;
				  }

			return $result;

			}

		
		
		
		function code_exist($vcode){

			$table	= is_dbtable_there('vouchers');
			$qStr 	= "SELECT * FROM $table WHERE vcode = '$vcode' LIMIT 0,1";
			$res 	= mysql_query($qStr);
			$num 	= mysql_num_rows($res);	
			$result = ($num == 1 ? TRUE : FALSE);

		return $result;
		}		
		

		
		function subtract_voucher($TOTAL_AM,$order){

				// what amount is subtracted by the voucher?
				$result			= array();
				$table1 		= is_dbtable_there('orders');
				$table2 		= is_dbtable_there('vouchers');	
				
					// amount or percent? + calculate the amount 
					$qStr 	= "SELECT * FROM $table2 WHERE vcode = '$order[voucher]' LIMIT 0,1";
					$res 	= mysql_query($qStr);
					$vop	= mysql_fetch_assoc($res);
					
					$vop[vamount] 	= (float) $vop[vamount];
					$TOTAL_AM 		= (float) $TOTAL_AM;
					
					
					if($vop[voption] == 'P'){
						$subtr_am = round(($TOTAL_AM / 100) * $vop[vamount],2);
					} 
					else {
						$subtr_am =  $vop[vamount];
					}
		
					// is subtraction amount smaller than total? - yes? then, subtract the actual amount 
					if($TOTAL_AM > $subtr_am){					
						$new_total 	=  $TOTAL_AM - $subtr_am;	
					}
					else {
						$new_total = (float) '0.00';
					}							
					
					// update order table 
					#$oid		= (int) $order[oid];
					#$qStr 		= "UPDATE $table1 SET amount = $new_total WHERE oid = $oid";

					#mysql_query($qStr);	
				
				$result[subtr_am]	= $subtr_am;					
				$result[total] 		= $new_total; 		
						
		return $result;
		}
		


		function record_usage(){
		
			// is taken care at the moment by car_actions::process_payment():: //voucher management
		
		}
		
		function voucher_is_ok($option = 'post'){
		
			if($option == 'post'){
				$id		= trim($_POST['v_no']);
			}			
			if($option == 'get'){
				$id		= trim($_REQUEST['vid']);
			}
			
			$table 	= is_dbtable_there('vouchers');
			$qStr 	= "SELECT vcode,voption,vamount FROM $table WHERE vcode = '$id' AND used = '0' LIMIT 0,1";
			$res 	= mysql_query($qStr);
			
			$result 		= array();
			$result['erg'] 	= @mysql_num_rows($res);
			$result['res'] 	= $res;
			
		return $result;
		}
	}