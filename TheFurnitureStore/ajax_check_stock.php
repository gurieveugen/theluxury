<?php
include '../../../wp-load.php';

$id		= $_GET['id'];
$option	= $_GET['option'];

unset($_GET['sid']);
unset($_GET['id']);
unset($_GET['option']);

$table 	= is_dbtable_there('inventory');
$where 	= NULL;

foreach($_GET as $k => $v){
	$where .= "AND $k = '$v' ";
}


switch($option){

	case '1':
		$qStr 	= "SELECT sum(amount) FROM $table WHERE ID_item = '$id' $where";
	break; 

	case '2':
		$qStr 	= "SELECT sum(amount) FROM $table WHERE ID_item = '$id' $where";	
	break; 
	
}

$res 		= mysql_query($qStr);
$erg		= (int) mysql_result($res,0,0);
if($erg != 0){
	
	$base_url = get_bloginfo('stylesheet_directory');
	echo "<input type='image' id='addC' name='add' style='visibility:visible;'  class='input_image' src='{$base_url}/images/". adjust_add2cart_img(). "' />%$erg";
	
}
else {
	echo $OPTION['wps_soldout_notice'];echo '&nbsp;%0';
}
?>