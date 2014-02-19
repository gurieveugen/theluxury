<?php
//Gather options and assign to variables
function NWS_get_global_options(){

	global $wpdb;
	
	$OPTION = array();
		
	$wanted = "	
	'siteurl',
	'home',
	'upload_path',
	'date_format',
	'blogname',
	'template',
	'thread_comments',
	'comment_registration',
	'show_on_front',
	'sticky_posts',
	'thumbnail_size_w',
	'thumbnail_size_h',
	'medium_size_w',
	'medium_size_h',
	'large_size_w',
	'large_size_h',
	'permalink_structure'
	";

	
	$sql = "SELECT option_name, option_value FROM $wpdb->options WHERE option_name IN ($wanted) OR option_name LIKE 'wps_%'";
	$res = mysql_query($sql);
	
	while($row = mysql_fetch_assoc($res)){
		if($row['option_value'] == 'true'){
			$OPTION["$row[option_name]"] = TRUE;
		}
		elseif($row['option_value'] == 'false'){
			$OPTION["$row[option_name]"] = FALSE;
		}
		else{
			$OPTION["$row[option_name]"] = $row['option_value'];
		}
	}
	
	//these values should be changed only by theme support 
	$OPTION['wps_useGet4logout'] = 'yes';	//yes|no
	
return $OPTION;
}
?>