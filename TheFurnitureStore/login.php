<?php
$p = explode('wp-content/',__FILE__);
include $p[0].'wp-load.php';



if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    session_start();

    $username 			= trim($_POST['signInUsername']);
	$_SESSION['uname']	= $username;
    $passwort 			= md5(trim($_POST['signInPassword']));
    $hostname 			= $_SERVER['HTTP_HOST'];
	$path 				= $_POST[redirect];	 
	  
	  
	///////////// Username + PW are checked /////////////////////////////////////////////////////////
	$table 			= is_dbtable_there('feusers');
	$sql 			= "SELECT * FROM $table WHERE uname = '$username' AND pw='$passwort' AND level = '0' LIMIT 0,1";			
	$res 			= mysql_query($sql);
	$login_check 	= mysql_num_rows($res); 
	$accountLog		= get_page_by_title($OPTION['wps_pgNavi_logOption']);
	
	if($login_check == 1){

		$uid 		= mysql_result($res,0,"uid");
		$uname 		= mysql_result($res,0,"uname");
		$name_first	= mysql_result($res,0,"fname");
		$name_last 	= mysql_result($res,0,"lname");
		$country 	= mysql_result($res,0,"country");
		$level 		= mysql_result($res,0,"level");

		$_SESSION['user_logged']			= true;
		$_SESSION['timeout'] 				= time() + (int) $OPTION['wps_login_duration'];
		$_SESSION['browser'] 				= md5(strtolower($_SERVER['HTTP_USER_AGENT'])); // browser?
		$_SESSION['level']					= $level;
		$_SESSION['uid']					= $uid;
		$_SESSION['uname']					= $uname;
		$_SESSION['fname']					= $name_first;
		$_SESSION['lname']					= $name_last;
		$_SESSION['memberBillingCountry']	= $country; 
		
		
		// update of last_login				
		$qStr 	= "UPDATE $table SET login_attempts = 0, last_login = NOW() WHERE uid = $uid";
		mysql_query($qStr);
		
		// redirect to page of choice 
		$customerArea	= get_page_by_title($OPTION['wps_customerAreaPg']);
		$accountReg		= get_page_by_title($OPTION['wps_pgNavi_regOption']);  
		$accountLog		= get_page_by_title($OPTION['wps_pgNavi_logOption']);
		
		if($customerArea == NULL){
			echo "<p class='error'>".error_explanation('0007')."</p>"; exit();	//change.9.10
		}
		if($accountReg == NULL){
			echo "<p class='error'>".error_explanation('0008')."</p>"; exit();	//change.9.10
		}
		if($accountLog == NULL){
			echo "<p class='error'>".error_explanation('0009')."</p>"; exit();	//change.9.10
		}
		
		
		$redirect2cArea 	= array();
		$redirect2cArea[]	= get_permalink($accountLog->ID);
		$redirect2cArea[]	= get_permalink($accountReg->ID);
		$url				= trim($_POST['gotoURL']);

		
		if ($_SERVER['SERVER_PROTOCOL'] == 'HTTP/1.1'){
			if (php_sapi_name() == 'cgi') {
				header('Status: 303 See Other');
			} else {
				header('HTTP/1.1 303 See Other');
			}
		}
	
		if(strpos($url,'orderNow=') !== FALSE || strpos($url,'showCart=') !== FALSE){
				header("Location: $url");					
				exit();
		}
		else {
			foreach($redirect2cArea as $v){
				$v = (substr($v,-1)== '/' ? substr($v,0,-1) : $v);
				if($v == $url){
					$redirect_url = get_permalink($customerArea->ID);
					header("Location: $redirect_url");				
					exit(NULL);										
				}
			}	
		}
		header("Location: $url");					
		exit();
				
	} else {		   			   
		// we log the failed login attempt
		$qStr = "UPDATE $table SET login_attempts = login_attempts+1, no_login_success = NOW() WHERE uname = '$username'"; 			
		mysql_query($qStr);
			   
		$url = $OPTION[siteurl].'/'.$accountLog->post_name.'?failed=1';
		header("Location: $url");
		exit(NULL);
	}
} ?>