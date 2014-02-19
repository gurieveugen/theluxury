<?php  	
include '../../../wp-load.php';	
session_start();

	//there are plugins out which apparently overwrite the logout page session values 
	if($OPTION['wps_useGet4logout'] == 'yes'){
	
		$url = trim($_GET['go2page']);
		
		unset($_SESSION[uname]);
		unset($_SESSION[user_logged]);
		unset($_SESSION[timeout]);
		unset($_SESSION[browser]);
		unset($_SESSION[level]);
		unset($_SESSION[uid]);
		unset($_SESSION[fname]);
		unset($_SESSION[lname]);
		unset($_SESSION['go2page']);
		 
		header('Location: '.$url);
		exit(NULL);
	}
	
	$url = trim($_SESSION['go2page']);
	
	unset($_SESSION[uname]);
    unset($_SESSION[user_logged]);
    unset($_SESSION[timeout]);
    unset($_SESSION[browser]);
    unset($_SESSION[level]);
    unset($_SESSION[uid]);
    unset($_SESSION[fname]);
    unset($_SESSION[lname]);
    unset($_SESSION['go2page']);
	 
    header('Location: '.$url);
	exit(NULL);

?>