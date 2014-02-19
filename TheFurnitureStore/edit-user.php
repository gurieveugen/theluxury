<?php 
include '../../../wp-load.php';
session_start();
auth(1);

$customerArea	= get_page_by_title($OPTION['wps_customerAreaPg']);

	$table 	= is_dbtable_there('feusers');
	$url1 	= $OPTION[siteurl].'/'.$customerArea->post_name.'?action=1';
	$url2 	= $OPTION[siteurl].'/'.$customerArea->post_name.'?action=2';

	
	switch($_POST[editOption]){

		case 'email':
		
				$email1 			= trim($_POST[newEmail]);
				$email2 			= trim($_POST[rnewEmail]);
				$_SESSION[email] 	= $email1;
		
				// checking //					
				// valid format 
				if(validate_user_email($email1) !== TRUE){
					header("Location: $url1".'&err=1');exit(NULL);
				}
				
				// repetition ok?
				if($email1 !== $email2){
					header("Location: $url1".'&err=2');exit(NULL);					
				}				
				$qStr 	= "UPDATE $table SET email = '$email2' WHERE uid = '$_SESSION[uid]'"; 			
				mysql_query($qStr);
	   			   
				
				header("Location: $url1".'&success=email');
		
		break;
		
		case 'password':
				$pw1 				= trim($_POST[newPassword]);
				$pw2 				= trim($_POST[rnewPassword]);

				// checking //				
									
				// valid format 
				if(strlen($pw1) < 6){
					header("Location: $url1".'&err=3');exit(NULL);
				}
				
				// repetition ok?
				if($pw1 !== $pw2){
					header("Location: $url1".'&err=4');exit(NULL);					
				}
							
				
				$dbpw	= md5($pw2);
				$qStr 	= "UPDATE $table SET pw = '$dbpw' WHERE uid = '$_SESSION[uid]'"; 			
				mysql_query($qStr);
	   			   
				
				header("Location: $url1".'&success=password');
		break;
	
		case 'otherInfo':
			
				$fname 				= trim($_POST[memberName]);
				$lname				= trim($_POST[memberLastName]);
							
				$qStr 	= "UPDATE $table SET fname = '$fname', lname = '$lname'  WHERE uid = '$_SESSION[uid]'"; 					
				mysql_query($qStr);
	   			   
				header("Location: $url2".'&updated=1');		
		break;
		
		
		case 'billingAddressFE':	
			
				$_SESSION['memberBillingCountry'] = $_POST['country'];	
				
				// are fields empty?
				$complete = NULL;
				
				foreach($_POST as $v){
					if(empty($v)){
						$complete = '&complete=0';
					}
				}
				
				//update database 
				$table				= is_dbtable_there('feusers');
				$column_value_array = array();
				$where_conditions	= array();
				
				$where_conditions[0]= "uid = '$_SESSION[uid]'";


				$column_value_array['fname'] 	= $_POST['memberName'];
				$column_value_array['lname'] 	= $_POST['memberLastName'];
				
				$column_value_array['street'] 	= $_POST['street'];
				$column_value_array['hsno'] 	= $_POST['hsno'];
				$column_value_array['strno'] 	= $_POST['strno'];
				$column_value_array['strnam'] 	= $_POST['strnam']; 	
				$column_value_array['po']	 	= $_POST['po']; 	
				$column_value_array['pb'] 		= $_POST['pb']; 	
				$column_value_array['pzone'] 	= $_POST['pzone']; 	
				$column_value_array['crossstr'] = $_POST['crossstr']; 	
				$column_value_array['colonyn'] 	= $_POST['colonyn']; 	
				$column_value_array['district'] = $_POST['district']; 	
				$column_value_array['region'] 	= $_POST['region']; 	
				$column_value_array['state'] 	= $_POST['state']; 	
				$column_value_array['zip'] 		= $_POST['zip']; 	
				$column_value_array['town'] 	= $_POST['town']; 	
				$column_value_array['country'] 	= $_POST['country'];
								
				db_update($table,$column_value_array,$where_conditions);	
				
				header("Location: $url2".'&updated=1'.$complete);		
		break;	
				
		case 'extraInfos':
		
			$table 	= is_dbtable_there('feusers_meta');	
			$uid  	= (int) $_SESSION['uid'];				
					
			unset($_POST['editOption']);
			unset($_POST['submit']);
									
			foreach($_POST as $k => $v){	
			
				$qStr0 	= "SELECT info_id FROM $table WHERE uid = $uid AND meta_key = '$k'";
				$res 	= mysql_query($qStr0);
				$num 	= mysql_num_rows($res);
			
				if($num == 0){
					$qStr 	= "INSERT INTO $table (uid, meta_key, meta_value) VALUES ($uid,'$k','$v')";
					mysql_query($qStr);
				}
				else {
					$qStr 	= "UPDATE $table SET meta_value = '$v' WHERE uid = $uid AND meta_key = '$k'";
					mysql_query($qStr);					
				}	
			}
			
			header("Location: $url2".'&updated=1');	
		break;
	}

?>