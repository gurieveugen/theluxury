<?php
include '../../../wp-load.php';
session_start();

// add item to wishlist
if(($_GET['transfer_cart_item'] == '1') && (isset($_GET['cid']))){
	
	$table 	= is_dbtable_there('shopping_cart');
	$cid	= (int) trim($_GET[cid]);
	$qStr 	= "SELECT * FROM $table WHERE cid = $cid LIMIT 0,1";
	$res 	= mysql_query($qStr);
	$row	= mysql_fetch_assoc($res);
	
	
	// add as new item in wishlist table 
	$table 			= is_dbtable_there('wishlist');
	$column_array	= array();
	$value_array	= array();    
	
	
	$qStr 	= "SELECT wid 
					FROM 
					$table 
				WHERE item_id = '$row[item_id]' AND item_attributs = '$row[item_attributs]' 
					AND item_personal = '$row[item_personal]' AND uid = '$_SESSION[uid]'";	
	$res	= mysql_query($qStr);
	$num	= mysql_num_rows($res);

	//change.9.9
	if($num == 0){
		$column_array[0]	= 'wid';			$value_array[0]	= '';
		$column_array[1]	= 'item_id';		$value_array[1]	= $row['item_id'];
		$column_array[2]	= 'postID';			$value_array[2]	= $row['postID'];
		$column_array[3]	= 'item_name';		$value_array[3]	= $row['item_name'];
		$column_array[4]	= 'item_amount';	$value_array[4]	= 1;
		$column_array[5]	= 'item_price';		$value_array[5]	= $row['item_price'];
		$column_array[6]	= 'item_weight';	$value_array[6]	= $row['item_weight'];
		$column_array[7]	= 'item_thumb';		$value_array[7]	= $row['item_thumb'];	
		$column_array[8]	= 'item_attributs';	$value_array[8]	= $row['item_attributs'];			
		$column_array[9]	= 'item_personal';	$value_array[9]	= $row['item_personal'];				
		$column_array[10]	= 'uid';			$value_array[10]= $_SESSION['uid'];
		$column_array[11]	= 'level';			$value_array[11]= '1';	
		$column_array[12]	= 'item_file';		$value_array[12]= $row['item_file'];	
								
		db_insert($table,$column_array,$value_array);	
		update_personalization($cid,mysql_insert_id());
	}
	//\change.9.9

	
	// remove from shoppingcart 
	$table 	= is_dbtable_there('shopping_cart');
	$qStr 	= "DELETE FROM $table WHERE cid = $cid";
	mysql_query($qStr);	
	
	$url 	= get_cart_url().'/?wltransfer=OK'; 
	header("Location: $url");
	exit(NULL);
}
?>