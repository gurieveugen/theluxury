<?php
if((!isset($_GET['dl'])) || (!isset($_GET['rd']))){exit("A mistake has occurred.");}
include '../../../wp-load.php';

$download 	= mysql_real_escape_string($_GET['dl']);
$who 		= mysql_real_escape_string($_GET['rd']);

// Create path based on WP_CONTENT_DIR
$wpPath	= explode('wp-content',WP_CONTENT_DIR);
$wpPath	= substr($wpPath[0],0,-1);
$p 		= explode("/",$wpPath);
$n 		= count($p);
$j 		= substr_count($OPTION['wps_master_dir'],"/"); 
$m 		= ($n+1) - $j;
$path 	= NULL;

	for($i=0;$i<$m;$i++){
			$path .= $p[$i].'/';
	}
 
$basedir = $path.'masterdata/';

 
// does this download link exist and is it valid?
// if not, we tell user
$table 	= is_dbtable_there('dlinks');
$qStr 	= "SELECT * FROM $table WHERE dlink = '$download' AND who = '$who' LIMIT 0,1";
$res 	= mysql_query($qStr);
$row 	= mysql_fetch_assoc($res);
$num	= mysql_num_rows($res);

$tlimit = $row[tstamp] + $row[duration];
$tnow	= time();


if($num < 1){
	echo 'This is not a valid download link.';
	exit();
}

if($tlimit < $tnow){
	echo 'Sorry, but your download link has expired.';
	exit();
}


// everything ok - we get the file + count download
$DIGITALGOODS 	= load_what_is_needed('digitalgoods');	//change.9.10
$file 			= $basedir . $row[dfile];

if(file_exists($file)){
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
	$DIGITALGOODS->download_counter($download,$who);		//change.9.10
    exit();
}
?>