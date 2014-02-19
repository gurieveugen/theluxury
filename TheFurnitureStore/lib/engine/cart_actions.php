<?php
##################################################################################################################################
//												SHOPPING-CART FUNCTIONS																			
##################################################################################################################################
add_action('init', 'shop_cart_init');
function shop_cart_init() {
	global $OPTION;
	if ($_POST['proceed2Checkout'] == 'true') {
		layaway_set_session();
		// redirect to login if user not logged and selected layaway option
		if (!is_user_logged_in() && $_POST['layaway_process'] == 1) {
			$login_page = get_page_by_title('Login');
			if ($login_page) {
				$login_url = get_permalink($login_page->ID).'?redirect_to='.urlencode($_SERVER['REQUEST_URI']);
			} else {
				$login_url = wp_login_url(urlencode($_SERVER['REQUEST_URI']));
			}
			$login_url .= '&installments=1';
			wp_redirect($login_url);
			exit();
		}
		wp_redirect(get_real_base_url($OPTION['wps_enforce_ssl']).'?orderNow=1');
		exit();
	}
}

// Customer gets a unique identifier 
function create_customer_id(){

	global $OPTION;
	
	if(custid_used_for_order()){  // introduced b'c of Authn Session-Handling
		unset($_SESSION['cust_id']);	
	}

	if(!isset($_SESSION['cust_id'])){	
		$_SESSION['cust_id'] 	= time().'-'.substr(md5(time()),22);
		if(($OPTION['wps_inventory_cleaning_method'] == 'internal')&&($OPTION['wps_track_inventory'] == 'active')&&($OPTION['wps_shop_mode'] == 'Normal shop mode')){
			xl_clean_inventory();
		}
	}
}

function cod_available()
{
	// needs to do....
	if($OPTION['wps_shop_mode']=='Inquiry email mode'){	$table = is_dbtable_there('inquiries');	}
	else{	$table = is_dbtable_there('orders');}	
	$qStr 	= "SELECT * FROM $table WHERE who = '$_SESSION[cust_id]' LIMIT 1";
	$res 	= mysql_query($qStr);  
	$order 	= mysql_fetch_assoc($res);
	if($order['p_option'] != 'cod') return TRUE;
	else if($order['d_addr'] == '1') 
	{
		$delivery_addr = retrieve_delivery_addr();
		if($delivery_addr['country'] == 'UNITED ARAB EMIRATES') return TRUE;
		else return FALSE;
	}
	else if($order['country'] == 'UNITED ARAB EMIRATES') return TRUE;
	else return FALSE;
}

function is_dbtable_there($table_name){

	global $wpdb,$CONFIG_WPS;

	$table 			= $wpdb->prefix . $CONFIG_WPS['prefix'] .$table_name;				
	$db_table_num 	= mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$table."'"));
	
	// if table not exist create it	
	if($db_table_num < 1)
	{
		include WP_CONTENT_DIR . '/themes/'.WPSHOP_THEME_NAME.'/lib/db/creation.php';
	}
return $table;
}


function add_toCart(){

	global $OPTION, $wpdb;

	create_customer_id();
		
	$table 	= is_dbtable_there('shopping_cart');
	$table2 = is_dbtable_there('shopping_cart_log');	
	
	if(($_POST['cmd'] == 'add') && ($_POST['amount'] != '0.00')){
		if ($_POST['layaway_payment'] == 'true') { // reset shopping cart if continue layaway payment
			reset_shopping_cart();
		}
		$check_sc = $wpdb->get_var(sprintf("SELECT COUNT(cid) FROM %s WHERE who = '%s'", $table, $_SESSION['cust_id']));
		if ($check_sc == 0) {
			layaway_clean_session();
		}
		if (!$_SESSION['layaway_order']) { // if cart doesn't have layaway product
			// clear layaway params
			layaway_clean_session();
			// layaway order id
			$loid = 0; $layaway_sc = 0;
			if ($_POST['loid'] > 0) { $loid = $_POST['loid']; $layaway_sc = 1; }
			$_SESSION['layaway_order'] = $loid;
			if ($loid > 0) {
				$otable = is_dbtable_there('orders');
				$orderdata = $wpdb->get_row(sprintf("SELECT * FROM %s WHERE oid = %s", $otable, $loid));
				if ($orderdata) {
					$sess_order = array(
						'fname' => $orderdata->f_name,
						'lname' => $orderdata->l_name,
						'street' => $orderdata->street,
						'state' => $orderdata->state,
						'zip' => $orderdata->zip,
						'town' => $orderdata->town,
						'country' => $orderdata->country,
						'email' => $orderdata->email,
						'telephone' => $orderdata->telephone
					);
					if ($orderdata->d_addr == 1) {
						$datable = is_dbtable_there('delivery_addr');
						$deliverydata = $wpdb->get_row(sprintf("SELECT * FROM %s WHERE who = '%s'", $datable, $orderdata->who));
						if ($deliverydata) {
							$sess_order['shipp_fname'] = $deliverydata->f_name;
							$sess_order['shipp_lname'] = $deliverydata->l_name;
							$sess_order['shipp_street'] = $deliverydata->street;
							$sess_order['shipp_state'] = $deliverydata->state;
							$sess_order['shipp_zip'] = $deliverydata->zip;
							$sess_order['shipp_town'] = $deliverydata->town;
							$sess_order['shipp_country'] = $deliverydata->country;
						}
					}
					$_SESSION['layaway_order_data'] = $sess_order;
				}
			}

			if(!isset($_POST['attrData'])){	// different attribute collection methods - either from several $_POST values or one single combined one... 				
				$item_attributes 	= collect_attributes();
			}
			else{
				$item_attributes 	= collect_attributes(2);
			}
			
			//save any available personalization data
			$personal 			= personalization_chksum();	
			$already_there 		= item_already_there($table,$_POST['item_id'],$item_attributes,$personal,$_SESSION['cust_id']);

			//change.9.9
			// stock control - minus 1 item if enough items on stock, otherwise $stock is overwritten with value 0
			$stock = 1;
			if($OPTION['wps_track_inventory'] == 'active' && $loid == 0) {
				$stock = get_item_inventory($_POST['postID']);
			}
			
			if($stock > 0) {
				if($already_there == 0){
					// add as new item in table 
					$column_array	= array();
					$value_array	= array();

					$seller_price = get_post_meta($_POST['postID'], 'item_your_price', true);
					if (!$seller_price) { $seller_price = 0; }
					$item_number = $_POST['item_number'];
					if ($item_number > 1) { $item_number = 1; } // item can't added more 1 stock
						
					$column_array[]	= 'cid';			$value_array[]	= '';
					$column_array[]	= 'item_id';		$value_array[]	= $_POST['item_id'];
					$column_array[]	= 'postID';			$value_array[]	= $_POST['postID'];
					$column_array[]	= 'item_name';		$value_array[]	= $_POST['item_name'];
					$column_array[]	= 'item_amount';	$value_array[]	= $item_number;
					$column_array[]	= 'item_price';		$value_array[]	= $_POST['amount'];
					$column_array[]	= 'item_weight';	$value_array[]	= $_POST['item_weight'];
					$column_array[]	= 'item_thumb';		$value_array[]	= $_POST['image_thumb'];	
					$column_array[]	= 'item_attributs';	$value_array[]	= $item_attributes;						
					$column_array[]	= 'item_personal';	$value_array[]	= $personal;		
					$column_array[]	= 'who';			$value_array[]	= $_SESSION['cust_id'];
					$column_array[]	= 'level';			$value_array[]	= '1';
					$column_array[]	= 'layaway';		$value_array[]	= $layaway_sc;
					$column_array[]	= 'seller_price';	$value_array[]	= $seller_price;
					$column_array[]	= 'item_category';	$value_array[]	= get_sc_item_category($_POST['postID']);
					
					if(isset($_POST['item_file'])){
					$column_array[]	= 'item_file';		$value_array[]	= $_POST['item_file'];
					}
										
					db_insert($table,$column_array, $value_array);	

					//get the inserted ID from table 
					$sql 	= "SELECT cid FROM $table WHERE who = '$_SESSION[cust_id]' ORDER BY cid DESC LIMIT 1";
					$res	= mysql_query($sql);
					$row	= mysql_fetch_assoc($res);
					
					save_personalization($row); 
					
					// for logging
					$column_array	= array();
					$value_array	= array();           
						
					$column_array[]	= 'cid';			$value_array[]	= $row['cid'];
					$column_array[]	= 'item_id';		$value_array[]	= $_POST['item_id'];
					$column_array[]	= 'postID';			$value_array[]	= $_POST['postID'];
					$column_array[]	= 'item_name';		$value_array[]	= $_POST['item_name'];
					$column_array[]	= 'item_amount';	$value_array[]	= $_POST['item_number'];
					$column_array[]	= 'item_price';		$value_array[]	= $_POST['amount'];
					$column_array[]	= 'item_weight';	$value_array[]	= $_POST['item_weight'];
					$column_array[]	= 'item_thumb';		$value_array[]	= $_POST['image_thumb'];	
					$column_array[]	= 'item_attributs';	$value_array[]	= $item_attributes;						
					$column_array[]	= 'item_personal';	$value_array[]	= $personal;		
					$column_array[]	= 'who';			$value_array[]	= $_SESSION['cust_id'];
					$column_array[]	= 'level';			$value_array[]	= '1';
					$column_array[]	= 'item_category';	$value_array[]	= get_sc_item_category($_POST['postID']);

					if(isset($_POST['item_file'])){
					$column_array[]	= 'item_file';		$value_array[]	= $_POST['item_file'];
					}
					db_insert($table2,$column_array, $value_array);
				} else {	
					//digital goods : update only if lkeys are activated			
					$qStr77 		= "SELECT item_file FROM $table WHERE item_id = '$_POST[item_id]' AND who = '$_SESSION[cust_id]' LIMIT 0,1";
					$res77 			= mysql_query($qStr77);
					$row			= mysql_fetch_assoc($res77);
					$digital 		= ($row['item_file'] == 'none' ? FALSE : TRUE);
					$digital_ok		= 'positive';
								
					if($digital === TRUE){			
						$license_op 	= $OPTION['wps_l_mode'];				
						if($license_op == 'SIMPLE'){		
							$digital_ok	= 'negative';
						}
					}			

					if($digital_ok == 'positive'){
						// update (item amount)
						$qStr = "UPDATE $table 
										SET item_amount=1 
									WHERE 
										item_id = '$_POST[item_id]' AND item_attributs = '$item_attributes' 
										AND item_personal = '$personal'
										AND who = '$_SESSION[cust_id]'";
						mysql_query($qStr);
										
						//for logging
						$qStr = "UPDATE $table2 
										SET item_amount=1 
									WHERE 
										item_id = '$_POST[item_id]' AND item_attributs = '$item_attributes' 
										AND item_personal = '$personal'
										AND who = '$_SESSION[cust_id]'";
						mysql_query($qStr);
					}
				}
			}
		}
		update_cart_activity_date();
		$installments_buy = $_POST['installments_buy'];
		//\change.9.9
		// to avoid unitentional reposts
		$_POST 	= array();	
		$url 	= current_page(3);	
		//change.9.9
		if ($OPTION['wps_send_to_view_cart'] ) {
			$url = get_option('home').'?showCart=1&cPage='. $url;
		} else {			
			$url 	= str_replace ('?added=OK&l=cart','',$url);		
			//in case of no add to cart we need message for single product page
			if($stock > 0){
				$url 	.= '?added=OK&l=cart';
			}
			else {
				$url 	.= '?added=NOK&l=cart';
			}
		}
		if ($installments_buy == 1) {
			$url .= '&installments=1';
		}
		//\change.9.9
		header('Location: ' . $url);
		exit($url);		
	}	
}

function update_cart_activity_date() {
	global $wpdb;
	$sctable = is_dbtable_there('shopping_cart');
	if ($_SESSION['cust_id']) {
		$wpdb->query(sprintf("UPDATE %s SET activity_date = %s WHERE who = '%s'", $sctable, time(), $_SESSION['cust_id']));
	}
}

function collect_attributes($option=1,$prefix = 'item_attr_'){

	$output = NULL;
					
	if($option == 1){					
		foreach($_POST as $k => $v){
			if(substr($k,0,10) == $prefix)
			{	
				$parts = explode("_",$k);
				$label = ucwords($parts[2]);
		
				$output .= $label.'='.$v.'#';
			}			
		}
		$attributes = substr($output, 0, -1);
	}
	elseif($option == 2){
	
		$attr 	= trim($_POST[attrData]);
		$attr 	= substr($attr,1);
		$parts 	= explode("#",$attr);
				
		foreach($parts as $v){
			$output .= ucwords($v).'#';
		}
		$attributes = substr($output, 0, -1);		
	}
	elseif($option == 3){
		$attributes = substr($output, 0, -1);
	}
	

return $attributes; 
} 

function display_attributes($raw_attributes,$mode='html'){

	switch($mode){
	
		case 'html':
		
			$attributes	= "";
			
			$parts 		= explode("#",$raw_attributes);
			foreach($parts as $v){
				$attributes .= $v . "<br/>";
			}
			$attributes = str_replace('=',': ',$attributes);		
		
		break;
		
		
		case 'html_be':
		
			$attributes	= "";
			
			$parts 		= explode("#",$raw_attributes);
			foreach($parts as $v){
				$attributes .= $v.'<br/>';
			}
			$attributes = str_replace('=',': ',$attributes);		
		
		break;
		
		
		case 'pdf':
		
			$len = strlen($raw_attributes);
		
			if($len > 0){
				$attributes	= array();		
				
				$parts 		= explode("#",$raw_attributes);		
				foreach($parts as $v){			
					$v 				= str_replace('=',': ',$v);	
					$attributes[] 	= ucwords($v);
				}		
			}
			else{
				$attributes = 'none';
			}
		break;
		
		case 'txt':
		
			$attributes	= '-';
			
			$parts 		= explode("#",$raw_attributes);
			foreach($parts as $v){
				$attributes .= $v.',';
			}
			$attributes = str_replace('=',':',$attributes);		
			$attributes = substr($attributes,0,-1);
			$attributes = $attributes . '-';
		
		break;
	
	}

return $attributes;
}

function item_already_there($table,$item_id,$item_attributes,$personal,$who)
{

	$qStr 	= "SELECT * FROM $table 
					WHERE 
				item_id = '$item_id' AND item_attributs = '$item_attributes' AND item_personal = '$personal' AND who = '$who'
			";	
	$res	= mysql_query($qStr);
	$num 	= mysql_num_rows($res);
	
	if($num > 0){
		$feedback = 1;
	}
	else{
		$feedback = 0;
	}

return $feedback;
}

function db_insert($table_name, $column_array, $value_array){
	global $wpdb;

	$insert = array();
    for($i=0; $i<count($column_array); $i++) {
		$insert[$column_array[$i]] = $value_array[$i];
	}
	$wpdb->insert($table_name, $insert);
	return $wpdb->insert_id;
}

function db_update($table_name, $column_value_array, $where_conditions){
	$sql_string = 'UPDATE '.$table_name.' SET ';

	foreach ($column_value_array as $k => $v){
		$v = mysql_prep_escape($v);
		
		$sql_string .= "$k = '$v'";
		$sql_string .= ' , ';
	}

	$sql_string .= 'WHERE ';

	$num_conditions = count($where_conditions);

	for ($i = 0, $j = 1; $i < $num_conditions; $i++, $j++){
		if ($j == $num_conditions){
			$sql_string .= $where_conditions[$i];
		}else{
		   $sql_string .= $where_conditions[$i];
			$sql_string .= ' AND ';
		}
	}

	$sql_string = str_replace(', WHERE', ' WHERE', $sql_string);
	$result1 = mysql_query("$sql_string") or die ("<b>A fatal error has occured - 443789</b>");

	return $result1;
}

	
function dinfo_opener($key){
	
	$cw = md5(date("W"));
	if($cw == $key){
		$feedback = TRUE;
	}
	else {
		$feedback = FALSE;
	}
	
return $feedback;
}
	
function dinfo_footer($key){
	
	$cw = md5(date("W"));
	if($cw == $key){	
		print_r($_SESSION);
		echo "<br/><br/>";
		print_r($_POST);
	}
}

function get_price($itemID){
	//
	$qStr 	= "SELECT price FROM .... WHERE item_id = '$itemID' LIMIT 1";
	$res 	= mysql_query($qStr);
	$row 	= mysql_fetch_assoc($res);

return $row[price];
}


function show_cart($order=0,$cust_id=0){
	if($cust_id == 0){
		$cust_id = $_SESSION['cust_id'];
	}

	$table 	= is_dbtable_there('shopping_cart');	
	$CART 	= array();
	$qStr 	= "SELECT * FROM $table WHERE who = '$cust_id'";
	
	if(is_array($order)){
	$qStr 			= "SELECT * FROM $table WHERE who = '$order[who]'";
	}
	
	$res 			= mysql_query($qStr);
	$num 			= mysql_num_rows($res);
	
	// remember page visitor came from 
	if((!isset($_GET['orderNow'])) && (isset($_GET['cPage']) && $_GET['cPage'] != '0') && (!isset($_POST['update']))){
   
		   if(isset($_GET['cPage'])){
				$_SESSION['cPage'] = $_GET['cPage'];
		   } 
	}

	if($num < 1){
		$CART['status'] 	= 'empty';
	}
	else {
	
		$CART['status']				= 'filled';
		$CART['total_item_num'] 	= 0;
		$CART['total_price'] 		= 0;
		$CART['total_weight'] 		= 0;
		$CART['content'] 			= array();

		$item			= array();
		$i				= 0;
		
		
			while($row = mysql_fetch_assoc($res)) {
					
				$personalize 			=  retrieve_personalization($row['cid']); // Personalization 
								
				$item[$i]['num']		=  $row['item_amount'];
				$item[$i]['price'] 		=  $row['item_price'] * $row['item_amount'];
				$item[$i]['weight'] 	=  $row['item_weight'] * $row['item_amount'];				
				$CART['content'][$i]	=  $row['cid'].'|'.$row['item_amount'].'|'.$row['item_name'].'|'.$row['item_price'];
				$CART['content'][$i]	.= '|'.sprintf("%01.2f",$item[$i]['price']).'|'.$row['item_id'].'|'.$row['item_thumb'];
				$CART['content'][$i]	.= '|'.$row['item_attributs'].'|'.$row['postID'].'|'.$personalize;
				$i++;				
			}	
			
			
			for($a=0;$a<$i;$a++)
			{
				$CART['total_item_num'] 	= $CART['total_item_num'] + $item[$a]['num'];
				$CART['total_weight']		= $CART['total_weight'] + $item[$a]['weight'];
				$CART['total_price'] 		= $CART['total_price'] + $item[$a]['price'];				
			}
			
			$CART['total_price'] = sprintf("%01.2f", $CART['total_price']);
			
		}		
return $CART;
}


function update_cart(){
	global $OPTION, $wpdb;

	$table 			= is_dbtable_there('shopping_cart');
	$table3 		= is_dbtable_there('shopping_cart_log');	
	$stock_feedback = array();

	foreach($_POST as $k => $v){
		// amount action
		$findMe   	= 'amount_';
		$pos 		= strpos($k, $findMe);
		if ($pos !== false){
			$parts 	= explode("_",$k);
			
			// in case sb. types in 0 or worse negative amounts - we transform it to 1
			$v = (int) $v;
			if($v < 1){$v = 1;}
			
			// we only update if the amount is an integer and not a null and not a float
			$needle 		= '.';
			$dot_found 		= strpos($v,$needle);
			$dot_found		= ($dot_found === FALSE ? 'no' : 'yes');
			
			// no point in updating the amount of an digital product which has no lkey's 
			$digital_ok		= 'positive';
			$digital 		= is_it_digital('UPDATE-CHECK',$parts[1]);
						
			if($digital === TRUE){			
				$license_op 	= $OPTION['wps_l_mode'];
				
				if($license_op == 'SIMPLE'){		
					$digital_ok	= 'negative';
				}
			}			

			if((is_numeric($v)) && ($v != '0') && ($dot_found == 'no') && ($digital_ok == 'positive')){
			
			if ($v > 1) { $v = 1; } // item can't added more 1 stock
			// stock control
				if($OPTION['wps_track_inventory']=='active'){
					$attr = retrieve_attributes($parts[1]);						
					$sc_item = $wpdb->get_row(sprintf("SELECT * FROM %s WHERE cid = %s", $table, $parts[1]));
					$item_id = $sc_item->item_id;
					$post_id = $sc_item->postID;
					$prod_stock = get_item_inventory($post_id, $item_id);
					if ($v <= $prod_stock) {
						$wpdb->query("UPDATE $table SET item_amount='$v' WHERE cid = $parts[1]");
						// for logging
						$wpdb->query("UPDATE $table3 SET item_amount='$v' WHERE cid = $parts[1]");
						$url_add = NULL;
					} else {
						$stock_feedback[$parts[1]] = $prod_stock;
						$items = NULL;
						foreach($stock_feedback as $k => $v){
							if(!empty($v)){
								$items .= $k.'-'.$v.',';
							}
						}
						$url_add 	= '&sw='.substr($items,0,-1);
					}
				}
				else {
					$wpdb->query("UPDATE $table SET item_amount='$v' WHERE cid = $parts[1]");
				}
			}			
		}
	
		// remove action
		if (substr($k, 0, 3) == 'rm_' && $v == 1) {
			$parts 	= explode("_",$k);
			$cid	= (int)$parts[1];

			$wpdb->query("DELETE FROM $table WHERE cid='$cid'"); //deleting
			$wpdb->query("DELETE FROM $table3 WHERE cid='$cid'");
			
			//remove also any existing records in the personalize table
			$table2	= is_dbtable_there('personalize'); 
			$wpdb->query("DELETE FROM $table2 WHERE cid = $cid");
			
		}				
	}
	update_cart_activity_date();
	// redirect to same page + stock control
	$url 	= current_page(3).$url_add;
	if($_GET['updateQty'] == '1'){
	
		if($OPTION['wps_enforce_ssl'] == 'force_ssl'){
			$parts 	= explode("://",get_option('home'));
			$url 	= 'https://'.$parts[1];
		}
		else {
			$url 	= $OPTION['home'];
		}
		$url = $url.'/index.php?orderNow=3'.$url_add;
	}
	//echo 'fhgfhg';
	header('Location: ' . $url);
	exit(NULL);	
}

function order_review_update(){
	global $wpdb;
	$table 	= is_dbtable_there('shopping_cart');
	$table2	= is_dbtable_there('personalize'); 
	$table3 = is_dbtable_there('shopping_cart_log');	

	foreach($_POST as $k => $v) {
		// remove action
		if (substr($k, 0, 3) == 'rm_' && $v == 1) {
			$parts 	= explode("_",$k);
			$cid	= (int)$parts[1];

			$wpdb->query("DELETE FROM $table WHERE cid='$cid'"); //deleting
			$wpdb->query("DELETE FROM $table3 WHERE cid='$cid'");
			
			//remove also any existing records in the personalize table
			$wpdb->query("DELETE FROM $table2 WHERE cid = $cid");
		}				
	}
}

function loop_products($CART){

	global $OPTION;
	
	$output	= array();
	$y 		= 0;
	$pids 	= array();
	
	foreach($CART['content'] as $v){
	
		$details 	= explode("|",$v);
		
		$art_no 		= $details[5];
		$personalize 	= (!empty($details[9]) ? "<br/>".$details[9] : NULL);
					
		$attributes		= NULL;
		$attributes 	= display_attributes($details[7]);		

		$is_digital 	= is_it_digital('CART',$details[0]);
		$digital_label	= ($is_digital === TRUE ? '<span class="cart_digital_product">'.__('Digital product','wpShop').'</span>' : NULL);

		
		
		if ($details[6]) {
			$img_src 	= $details[6];
			
			$img_size 	= $OPTION['wps_ProdRelated_img_size'];
			$des_src 	= $OPTION['upload_path'].'/cache';
			
			$img_file 	= mkthumb($img_src,$des_src,$img_size,'width');    
			$imgURL 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;	
			$thumb_img 	= $imgURL;
		}
			
		$prodID 	= $details[8];
		$permalink  = get_permalink($prodID);
		$pids[]		= $prodID;
		$prod 		= get_post($prodID); 
		$permalink 	= get_permalink($prodID);
		
		// show related products?
		if($OPTION['wps_cartRelatedProds_enable']) {
			
			$resizedImg_src = $des_src;
			
			if($OPTION['wps_blogTags_option']){
				$WPS_taxTerm = $OPTION['wps_term_cart_relatedProds'];
				
				switch($WPS_taxTerm){
					case 'outfit_related':
						$taxTerm = 'outfit';
					break;
					case 'fit_related':
						$taxTerm = 'fit';
					break;
					case 'size_related':
						$taxTerm = 'size';
					break;
					case 'colour_related':
						$taxTerm = 'colour';
					break;
					case 'brand_related':
						$taxTerm = 'brand';
					break;
					case 'selection_related':
						$taxTerm = 'selection';
					break;
					case 'style_related':
						$taxTerm = 'style';
					break;
					case 'price_related':
						$taxTerm = 'price';
					break;
				}
				
				//check to see if the taxonomy exists first
				$taxonomy_exist = taxonomy_exists($taxTerm);
				if($taxonomy_exist) {
					$r_posts  	= NWS_cart_tag_related_posts($prodID,10,$resizedImg_src,$img_size,$taxTerm);
				
				} else {
					echo "<p class='error'>".__('It seems you have checked the option to use Tags for regular Blog Posts under ','wpShop')."<strong>".__('Theme Options > Design > General ','wpShop')."</strong>".__('so you now need to activate (Theme Options > Design > General - "Shop by" Options) one of the other available custom taxonomies and select that from the drop down list under ','wpShop')."<strong>".__('Theme Options > Design > Single Product Pages - Related Products Settings - Alternative Tag Related Products','wpShop')."</strong></p>";
				}
				
			} else {
				$r_posts 	= NWS_cart_tag_related_posts($prodID,10,$resizedImg_src,$img_size);
			}
			
			if (!empty($r_posts[status])) {
				
				if($y == 0){
					$related = $r_posts[IDs];
				}
				else{
					if(!isset($related)){
					  $related = array();
					}

					foreach($r_posts[IDs] as $v){
						array_push($related,$v);
					}									
				}
				$output['related'] 	= $related;
				$output['pids'] 	= $pids;
			}
		}
		
		echo "<tr>";
			if ($details[6]) {
				echo "<td><img src='$thumb_img' alt='$details[2]' /></td>";									
			} else {
				echo "<td>&nbsp;</td>";	
			}
			echo "		
			<td class='second'>
				<h5>$details[2]</h5>
				<p>$art_no</p>";
			if ($OPTION['wps_lrw_yes']) {
				echo '<a href="'.site_url('index.php?wishlist=add&fpg=cart&cid='.$details[0].'&pid='.$details[8]).'">Move to Wishlist</a>';
			}
			echo "</td>
			<input class='text' type='hidden' name='amount_{$details[0]}' size='3' maxlength='3' value='$details[1]'>";
			echo "
			<td class='third'>";
			echo format_price($details[3] * $_SESSION['currency-rate'], true);
			echo "</td>
			<td class='last'><a href='#remove' class='remove' rel='rm_{$details[0]}'>remove</a><input type='hidden' name='rm_{$details[0]}' id='rm_{$details[0]}'></td>
		</tr>";
		$y++;
	}
	
	return $output;
}

function cid2item_id($cid){

	$cid 	= (int) $cid;
	$table 	= is_dbtable_there('shopping_cart');
	$qStr 	= "SELECT item_id FROM $table WHERE cid = $cid LIMIT 0,1";
	$res 	= mysql_query($qStr);
	$row	= mysql_fetch_assoc($res);
	$item_id= $row[item_id];

return $item_id;
}

function get_delivery_options($d_labels,$dpch='none'){
	global $OPTION;
	
	$oStr 		= $OPTION['wps_delivery_options'];
	$d_options 	= explode("|",$oStr);
	$result		= NULL;
	$a 			= 1;

	$CART = show_cart();
	$cart_items = $CART["content"];

	foreach($d_options as $v){
	
		switch($v){
		
			case 'pickup':
				$label 		= $d_labels['pickup'];
				$checked	= ($dpch['d_option'] == 'pickup' ? 'checked="checked"' : NULL);
			break; 
			
			case 'post':
				$label 		= $d_labels['post'];
				$checked	= ($dpch['d_option'] == 'post' ? 'checked="checked"' : NULL);
			break; 				
		}

		// in case none was chosen yet
		if($dpch == 'none'){
			$checked = 'checked="checked"';
		}

		// hide pickup delivery option for Watches category items
		$show_do = true;
		if ($v == 'pickup') {
			foreach($cart_items as $cart_item) {
				$cidata = explode("|", $cart_item);
				$pid = $cidata[8];
				if (in_category($OPTION['wps_women_watches_category'], $pid) || in_category($OPTION['wps_men_watches_category'], $pid)) {
					$show_do = false;
				}
			}
		}

		if ($show_do) {
			$result .= "<input id='dOpt$v' type='radio' name='d_option' onchange=\"changePaymentAvailability('$v')\" value='$v' $checked /><label for='dOpt$v'>$label</label><br/>";
			$a++;
		}
	}	
	
	return $result;
}

function get_payment_options($dpch='none'){
	global $OPTION;
	
	$wps_payment_options = $OPTION['wps_payment_options'];
	$p_options 	= explode("|", $wps_payment_options);
	if($dpch == 'none' && $OPTION['wps_payment_op_preselected'] !== 'none') {
		if(in_array($OPTION['wps_payment_op_preselected'], $p_options) === TRUE) {
			$dpch = array();
			$dpch['p_option'] 	= get_option('wps_payment_op_preselected');
		}
	}

	$payment_options = array();
	$poinds = array(
		'audi' => 0,
		'transfer' => 1,
		'cod' => 3,
		'cash' => 4,
		'paypal' => 2
	);
	$ind = 5;
	foreach($p_options as $v) {
		if (array_key_exists($v, $poinds)) {
			$i = $poinds[$v];
		} else {
			$i = $ind;
			$ind++;
		}
		$payment_options[$i] = $v;
	}
	ksort($payment_options);

	$results = array();
	foreach($payment_options as $v){
		switch($v){ 
			case 'paypal':
				$label 		= $OPTION['wps_pps_label'];
				$checked	= ($dpch['p_option'] == 'paypal' ? 'checked="checked"' : NULL);
				$src		= NWS_bloginfo('stylesheet_directory').'/images/payment/pps.png';
			break;

			case 'paypal_pro':
				$label 		= $OPTION['wps_ppp_label'];
				$checked	= ($dpch['p_option'] == 'paypal_pro' ? 'checked="checked"' : NULL);
				$src		= NWS_bloginfo('stylesheet_directory').'/images/payment/ppp.png';
			break;

			case 'cc_authn':
				$label 		= $OPTION['wps_auth_label'];
				$checked	= ($dpch['p_option'] == 'cc_authn' ? 'checked="checked"' : NULL);
				$src		= NWS_bloginfo('stylesheet_directory').'/images/payment/authorize.png';	
			break;	
			case 'g2p_authn':
				$label 		= $OPTION['wps_g2p_label'];
				$checked	= ($dpch['p_option'] == 'g2p_authn' ? 'checked="checked"' : NULL);
				$src		= NWS_bloginfo('stylesheet_directory').'/images/payment/g2p.png';	
			break;	
			case 'cc_wp':
				$label 		= $OPTION['wps_wp_label'];
				$checked	= ($dpch['p_option'] == 'cc_wp' ? 'checked="checked"' : NULL);
				$src		= NWS_bloginfo('stylesheet_directory').'/images/payment/worldpay.png';	
			break;				
			
			case 'transfer':
				$label 		= $OPTION['wps_bt_label'];
				$checked	= ($dpch['p_option'] == 'transfer' ? 'checked="checked"' : NULL);
				$src		= NWS_bloginfo('stylesheet_directory').'/images/payment/bt.png';
			break; 
			
			case 'cash':
				$label 		= $OPTION['wps_pol_label'];
				$checked	= ($dpch['p_option'] == 'cash' ? 'checked="checked"' : NULL);
				$src		= NWS_bloginfo('stylesheet_directory').'/images/payment/col.png';		
			break;	

			case 'cod':
				$label 		= $OPTION['wps_cod_label'];
				$checked	= ($dpch['p_option'] == 'cod' ? 'checked="checked"' : NULL);
				$src		= NWS_bloginfo('stylesheet_directory').'/images/payment/cod.png';		
			break;
			case 'alertpay':
				$label 		= $OPTION['wps_alertpay_label'];
				$checked	= ($dpch['p_option'] == 'alertpay' ? 'checked="checked"' : NULL);
				$src		= NWS_bloginfo('stylesheet_directory').'/images/payment/alertpay.png';		
			break;
			case '2checkout':
				$label 		= $OPTION["wps_2checkout_label"];
				$checked	= ($dpch['p_option'] == '2checkout' ? 'checked="checked"' : NULL);
				$src		= NWS_bloginfo('stylesheet_directory').'/images/payment/2checkout.png';		
			break;	
			case 'audi':
				$label 		= $OPTION["wps_audi_label"];
				$checked	= ($dpch['p_option'] == 'audi' ? 'checked="checked"' : NULL);
				$src		= NWS_bloginfo('stylesheet_directory').'/images/payment/audi.png';		
			break;					
		}
	
		// in case none was chosen yet
		if($dpch == 'none'){
			$checked = 'checked="checked"';
		}
		$results[$v] = array('label' => $label, 'checked' => $checked, 'src' => $src);
	}	

	return $results;		
}

function delivery_payment_chosen(){

	$output	= array();

	$table 	= is_dbtable_there('orders');
	$sql 	= "SELECT d_option,p_option FROM $table WHERE who = '$_SESSION[cust_id]' LIMIT 0,1";
	$res	= mysql_query($sql);
	$row	= mysql_fetch_assoc($res);
	
	$output['d_option'] = $row['d_option'];
	$output['p_option'] = $row['p_option'];
	
return $output; 
}

function process_order($step = 1) // function manages also inquiries
{
	global $OPTION, $wpdb;
	$VOUCHER = load_what_is_needed('voucher');	
	$feedback = false;
	$table = is_dbtable_there('orders');
	$table2 = is_dbtable_there('delivery_addr');	
	$table3	= is_dbtable_there('shopping_cart');
	if($OPTION['wps_shop_mode']=='Inquiry email mode'){	$table = is_dbtable_there('inquiries');	}

	update_cart_activity_date();

	switch($step)
	{
		case 1: 
			// add delivery + payment option to orders table  - check also for dublicates 
			$order_there = order_exists($table);
			if($order_there == 0)
			{
				$column_array	= array();
				$value_array	= array();           
				$column_array[]	= 'oid';		$value_array[]	= '';
				$column_array[]	= 'who';		$value_array[]	= "$_SESSION[cust_id]";				
				$column_array[]	= 'd_option';	$value_array[]	= "$_POST[d_option]";
				$column_array[]	= 'p_option';	$value_array[]	= "$_POST[p_option]";
				$column_array[]	= 'country';	$value_array[]	= "$_POST[country]";
				$column_array[]	= 'level';		$value_array[]	= '1';
				$column_array[]	= 'created';	$value_array[]	= time();

				// does voucher code exist? - AJAX is user friendly, but we don't rely on it				
				$v_result 	= $VOUCHER->voucher_is_ok(); 
				if($v_result['erg'] == 1)
				{					
						$CART 	= show_cart($order);
						$row 	= mysql_fetch_assoc($v_result['res']);
						$camount	= (float) $CART['total_price'];
						$vamount 	= (float) $row['vamount'];
						if($row['voption'] == 'P'){
							$calc 		= $camount / 100;
							$vamount 	= $calc * $vamount;
						}
						if($vamount < $camount){
							array_push($column_array,'voucher');
							array_push($value_array,trim($_POST['v_no']));
						}
				}			
				$order_id = db_insert($table, $column_array, $value_array);
				update_utm_params('orders', $order_id);
				// add record in delivery address table
				$column_array	= array();
				$value_array	= array();   
				$column_array[0]	= 'aid';			$value_array[0]	= '';
				$column_array[1]	= 'who';			$value_array[1]	= "$_SESSION[cust_id]";					
				db_insert($table2, $column_array, $value_array);
				$_POST = array(); // in case user uses back button of browser
			}
			else
			{
				$order_id = $wpdb->get_var(sprintf("SELECT oid FROM %s WHERE who = '%s'", $table, $_SESSION['cust_id']));
				if(isset($_POST['step1']))
				{
					$column_value_array 	= array();
					$where_conditions 		= array();
					$column_value_array['d_option'] 	= "$_POST[d_option]";
					$column_value_array['p_option'] 	= "$_POST[p_option]";

					if(isset($_POST['country'])){	$column_value_array['country'] 	= "$_POST[country]";	}
					// does voucher code exist? - AJAX is user friendly, but we don't rely on it				
					$v_result 	= $VOUCHER->voucher_is_ok(); 
					if($v_result['erg'] == 1)
					{					
							$CART 	= show_cart($order);
							$row 	= mysql_fetch_assoc($v_result['res']);
							$camount	= (float) $CART['total_price'];
							$vamount 	= (float) $row['vamount'];
							if($row['voption'] == 'P'){
								$calc 		= $camount / 100;
								$vamount 	= $calc * $vamount;
							}
							if($vamount < $camount){
								$column_value_array['voucher'] 	= trim($_POST['v_no']);
							}
					}			

					$where_conditions[0]			= "who = '$_SESSION[cust_id]'";
					db_update($table, $column_value_array, $where_conditions);	
				}
			}			
			// update order_id in shopping cart
			$update = array();
			$update['order_id'] = $order_id;
			$wpdb->update($table3, $update, array('who' => $_SESSION['cust_id']));
			break;
		// show the name + address form 
		case 2:
			// update orders table
			$column_value_array 	= array();
			$where_conditions 		= array();
			if($_POST['saveWhat'] == 'editNote') {
				$column_value_array['custom_note'] = $_POST['custom_note'];
			} else {
				$column_value_array['l_name'] 	 = $_POST['l_name'];
				$column_value_array['f_name'] 	 = $_POST['f_name'];
				$column_value_array['street'] 	 = $_POST['street'];
				$column_value_array['hsno'] 	 = $_POST['hsno'];
				$column_value_array['strno'] 	 = $_POST['strno'];
				$column_value_array['strnam']	 = $_POST['strnam'];
				$column_value_array['po'] 		 = $_POST['po'];
				$column_value_array['pb'] 		 = $_POST['pb'];
				$column_value_array['pzone'] 	 = $_POST['pzone'];
				$column_value_array['crossstr']  = $_POST['crossstr'];
				$column_value_array['colonyn'] 	 = $_POST['colonyn'];
				$column_value_array['district']  = $_POST['district'];
				$column_value_array['region'] 	 = $_POST['region'];
				$column_value_array['state'] 	 = $_POST['state'];
				$column_value_array['zip'] 		 = $_POST['zip'];
				$column_value_array['town'] 	 = $_POST['town'];
				$column_value_array['country']	 = $_POST['country'];
				$column_value_array['email'] 	 = $_POST['email'];
				$column_value_array['telephone'] = $_POST['telephone'];
				if($_POST['saveWhat'] != 'editAddress') { $column_value_array['custom_note'] = $_POST['custom_note']; }
				if($_POST['delivery_address_yes'] == 'on') {	$column_value_array['d_addr'] = '1'; }
			}
			$where_conditions[0] = "who = '$_SESSION[cust_id]'";
			db_update($table, $column_value_array, $where_conditions);
			// update delivery address table
			if($_POST['saveWhat'] != 'editNote')
			{
				$column_value_array 	= array();
				$where_conditions 		= array();
				$column_value_array['l_name'] 	= $_POST['l_name|2'];
				$column_value_array['f_name'] 	= $_POST['f_name|2'];
				$column_value_array['street'] 	= $_POST['street|2'];
				$column_value_array['hsno'] 	= $_POST['hsno|2'];
				$column_value_array['strno'] 	= $_POST['strno|2'];
				$column_value_array['strnam']	= $_POST['strnam|2'];
				$column_value_array['po'] 		= $_POST['po|2'];
				$column_value_array['pb'] 		= $_POST['pb|2'];
				$column_value_array['pzone'] 	= $_POST['pzone|2'];
				$column_value_array['crossstr'] = $_POST['crossstr|2'];
				$column_value_array['colonyn'] 	= $_POST['colonyn|2'];
				$column_value_array['district'] = $_POST['district|2'];
				$column_value_array['region'] 	= $_POST['region|2'];
				$column_value_array['state'] 	= $_POST['state|2'];
				$column_value_array['zip'] 		= $_POST['zip|2'];
				$column_value_array['town'] 	= $_POST['town|2'];
				$column_value_array['country']	= $_POST['country|2'];
				$where_conditions[0]			= "who = '$_SESSION[cust_id]'";			
				db_update($table2, $column_value_array, $where_conditions);
			}
			break;
		case 3:
			if(isset($_GET['dpchange']) && $_GET['dpchange'] == 1)
			{
			// update orders table - add address data 
				$column_value_array 	= array();
				$where_conditions 		= array();
				if(isset($_POST['saveWhat'])){
					switch($_POST['saveWhat']){
						case 'editDelivery':
							$column_value_array['d_option'] 	= $_POST['d_option'];							
						break;
						case 'editPayment':
							$column_value_array['p_option'] 	= $_POST['p_option'];							
						break;
					}
				} else {
				
					$column_value_array['d_option'] 	= $_POST['d_option'];
					$column_value_array['p_option'] 	= $_POST['p_option'];
				}
				$where_conditions[0]			= "who = '$_SESSION[cust_id]'";
				db_update($table, $column_value_array, $where_conditions);								
			}

			// display final summary
			$qStr 	= "SELECT * FROM $table WHERE who = '$_SESSION[cust_id]' LIMIT 1";
			$res 	= mysql_query($qStr);
			$row 	= mysql_fetch_assoc($res);
			$feedback = $row;	
			break;
		case 4:		break; 
		case 5:		break; 
	}
	return $feedback;	
}

function order_exists($table){

	$qStr 	= "SELECT oid FROM $table WHERE who = '$_SESSION[cust_id]'";
	$res 	= mysql_query($qStr);
	$num 	= mysql_num_rows($res);

	if($num < 1){
		$feedback = 0;
	}
	else {
		$feedback = 1;
	}
	
return $feedback;
}

function address_form_labels(){

		$LANG 						= array();

		$LANG['lastname'] 			= __('Last Name','wpShop');
		$LANG['firstname'] 			= __('First Name','wpShop');
		$LANG['street_hsno'] 			= __('Address','wpShop');
		$LANG['street']				= __('Street','wpShop');
		$LANG['hsno']					= __('House No.','wpShop');
		$LANG['strno']				= __('Street No.','wpShop');
		$LANG['strnam']				= __('Street Name','wpShop');
		$LANG['po']					= __('Post Office','wpShop');
		$LANG['pb']					= __('Post Box','wpShop');
		$LANG['pzone']				= __('Postal Zone','wpShop');
		$LANG['crossstr']				= __('Cross Streets','wpShop');
		$LANG['colonyn']				= __('Colony name','wpShop');
		$LANG['district']				= __('District','wpShop');		
		$LANG['region']				= __('Region','wpShop');			
		$LANG['island']				= __('Island','wpShop');		
		$LANG['state_province'] 		= __('State/Province','wpShop'); 
		$LANG['zip'] 					= __('Postcode','wpShop');
		$LANG['town']					= __('City','wpShop');
		$LANG['country']				= __('Country','wpShop');
		$LANG['email']				= __('Email','wpShop');
		$LANG['terms']				= __('Terms &amp; Conditions:','wpShop');
		$LANG['accept_terms']			= __('I accept the Terms &amp; Conditions of','wpShop');
		$LANG['next_step'] 			= __('Next Step','wpShop');		
		$LANG['field_not_empty']		= __(' - Field cannot be Empty.','wpShop');	
		$LANG['format_email']			= __('The Format of your Email Address is not Correct.','wpShop');	
		$LANG['terms_need_accepted']	= __('Terms need to be Accepted.','wpShop');

return $LANG;
}


function create_address_form($option = 'billing',$only_one_c = 'no'){	
	
	$LANG = address_form_labels();

	// different form according to address rules of delivery country - if shop country country is only country, use this one
	if($only_one_c !== 'no'){
		$d_country = $only_one_c;
	}
	else {
		$d_country 	= trim($_GET['country']);
	}
	$ad	= get_address_format($d_country,'filter');
	
	if($option == 'billing'){
		// loop thru address fields and produce input fields accordingly
		foreach($ad as $v){
		
			if($v == 'street'){
				echo "<label for='street_hsno'>$LANG[street_hsno]:</label><input id='street_hsno' type='text' name='street' value='$_POST[street]'
				maxlength='255' />";
			}
			if($v == 'hsno'){
				echo "<label for='hsno'>$LANG[hsno]:</label><input id='hsno' type='text' name='hsno' value='$_POST[hsno]' maxlength='255' />";
			}					
			if($v == 'strnam'){
				echo "<label for='strnam'>$LANG[strnam]:</label><input id='strnam' type='text' name='strnam' value='$_POST[strnam]' maxlength='255' />";
			}	
			if($v == 'strno'){
				echo "<label for='strno'>$LANG[strno]:</label><input id='strno' type='text' name='strno' value='$_POST[strno]' maxlength='255' />";
			}				
			if($v == 'po'){
				echo "<label for='po'>$LANG[po]:</label><input id='po' type='text' name='po' value='$_POST[po]' maxlength='255' />";
			}				
			if($v == 'pb'){
				echo "<label for='pb'>$LANG[pb]:</label><input id='pb' type='text' name='pb' value='$_POST[pb]' maxlength='255' />";
			}				
			if($v == 'pzone'){
				echo "<label for='pzone'>$LANG[pzone]:</label><input id='pzone' type='text' name='pzone' value='$_POST[pzone]' maxlength='255' />";
			}				
			if($v == 'crossstr'){
				echo "<label for='crossstr'>$LANG[crossstr]:</label><input id='crossstr' type='text' name='crossstr' value='$_POST[crossstr]' maxlength='255' />";
			}				         
			if($v == 'colonyn'){
				echo "<label for='colonyn'>$LANG[colonyn]:</label><input id='colonyn' type='text' name='colonyn' value='$_POST[colonyn]' maxlength='255' />";
			}                    
			if($v == 'district'){
				echo "<label for='district'>$LANG[district]:</label><input id='district' type='text' name='district' value='$_POST[district]' maxlength='255' />";
			}				
			if($v == 'region'){
				echo "<label for='region'>$LANG[region]:</label><input id='region' type='text' name='region' value='$_POST[region]' maxlength='255' />";
			}                          
			if($v == 'state'){

				echo "<label for='state'>$LANG[state_province]:</label>"; 

				$ct 	= get_countries(3,$d_country);						
				if(display_state_list($ct)){
					echo province_me($ct,$_POST['state']);						
				}			
				else {
					echo "<input  id='state' type='text' name='state' value='$_POST[state]' maxlength='255' />";
				}								
			}
			if($v == 'zip'){
					echo "<label for='zip'>$LANG[zip]:</label><input  id='zip' type='text' name='zip' value='$_POST[zip]' maxlength='255' />";
			}	
			if($v == 'place'){
					echo "<label for='town'>$LANG[town]:</label><input  id='town' type='text' name='town' value='$_POST[town]' maxlength='255' />";
			}			
		}
	}
	

	if($option == 'shipping'){
		// loop thru address fields and produce input fields accordingly 
		foreach($ad as $v){		
			
			if($v == 'street'){
					echo "<label for='dstreet_hsno'>$LANG[street_hsno]:</label><input id='dstreet_hsno' type='text' name='street|2' value='".$_POST['street|2']."' maxlength='255' />";
			}
			if($v == 'hsno'){
					echo "<label for='dhsno'>$LANG[hsno]:</label><input id='dhsno' type='text' name='hsno|2' value='".$_POST['hsno|2']."' maxlength='255' />";
			}					
			if($v == 'strnam'){
					echo "<label for='dstrnam'>$LANG[strnam]:</label><input id='dstrnam' type='text' name='strnam|2' value='".$_POST['strnam|2']."' maxlength='255' />";
			}	
			if($v == 'strno'){
					echo "<label for='dstrno'>$LANG[strno]:</label><input id='dstrno' type='text' name='strno|2' value='".$_POST['strno|2']."' maxlength='255' />";
			}				
			if($v == 'po'){
					echo "<label for='dpo'>$LANG[po]:</label><input id='dpo' type='text' name='po|2' value='".$_POST['po|2']."' maxlength='255' />";
			}				
			if($v == 'pb'){
					echo "<label for='dpb'>$LANG[pb]:</label><input id='dpb' type='text' name='pb|2' value='".$_POST['pb|2']."' maxlength='255' />";
			}				
			if($v == 'pzone'){
					echo "<label for='dpzone'>$LANG[pzone]:</label><input id='dpzone' type='text' name='pzone|2' value='".$_POST['pzone|2']."' maxlength='255' />";
			}				
			if($v == 'crossstr'){
					echo "<label for='dcrossstr'>$LANG[crossstr]:</label><input id='dcrossstr' type='text' name='crossstr|2' value='".$_POST['crossstr|2']."' maxlength='255' />";
			}				         
			if($v == 'colonyn'){
					echo "<label for='dcolonyn'>$LANG[colonyn]:</label><input id='dcolonyn' type='text' name='colonyn|2' value='".$_POST['colonyn|2']."' maxlength='255' />";
			}                    
			if($v == 'district'){
					echo "<label for='ddistrict'>$LANG[district]:</label><input id='ddistrict' type='text' name='district|2' value='".$_POST['district|2']."' maxlength='255' />";
			}				
			if($v == 'region'){
					echo "<label for='dregion'>$LANG[region]:</label><input id='dregion' type='text' name='region|2' value='".$_POST['region|2']."' maxlength='255' />";
			}                          
			if($v == 'state'){

				echo "<label for='dstate'>$LANG[state_province]:</label>"; 	
				
				$ct 	= get_countries(3,$d_country);						
				if(display_state_list($ct)){
					echo province_me($ct,$_POST['state|2'],'delivery');						
				}			
				else {
					echo "<input id='dstate' type='text' name='state|2' value='".$_POST['state|2']."' maxlength='255' />";
				}	
					
			}
			if($v == 'zip'){
					echo "<label for='dzip'>$LANG[zip]:</label><input id='dzip' type='text' name='zip|2' value='".$_POST['zip|2']."' maxlength='255' />";
			}	
			if($v == 'place'){
					echo "<label for='dtown'>$LANG[town]:</label><input id='dtown' type='text' name='town|2' value='".$_POST['town|2']."' maxlength='255' />";
			}	
		}
	}
}

function check_address_form()
{
	global $LANG;
	// verify data from address form 
	$feedback 				= array();
	$feedback['e_message']	= NULL;		
	$feedback['e_message2']	= NULL;			
	$feedback['error']		= 0;
	
	// Labels for field
	$labels = array();
	$labels['l_name'] 	= $LANG['lastname'];
	$labels['f_name'] 	= $LANG['firstname'];
	$labels['street'] 	= $LANG['street'];
	
	$labels['hsno'] 	= $LANG['hsno'];
	$labels['strno'] 	= $LANG['strno'];			
	$labels['strnam'] 	= $LANG['strnam'];			
	$labels['po'] 		= $LANG['po'];			
	$labels['pb'] 		= $LANG['pb'];	
	$labels['pzone'] 	= $LANG['pzone'];				
	$labels['crossstr'] = $LANG['crossstr'];
	$labels['colonyn'] 	= $LANG['colonyn'];	
	$labels['district'] = $LANG['district'];
	$labels['region'] 	= $LANG['region'];
	$labels['state']  	= $LANG['state_province'];
	$labels['zip'] 		= $LANG['zip'];
	$labels['town'] 	= $LANG['town'];
	$labels['email'] 	= $LANG['email'];
	$labels['telephone']= $LANG['telephone'];
	$_POST['step2']		= TRUE; // actually a fix, if designer removes value from submit button

	// delete fields from $_POST array which shouldn't be checked
	unset($_POST['v_no']);
	$tmp = '';
	if(isset($_POST['custom_note']))
	{
		$tmp = $_POST['custom_note']; 
		unset($_POST['custom_note']);
	}
	unset($_POST['step1']);

	if(!isset($_POST['delivery_address_yes']))
	{
		unset($_POST['f_name|2']);
		unset($_POST['l_name|2']);
		unset($_POST['country|2']);
		unset($_POST['street|2']);
		unset($_POST['hsno|2']);
		unset($_POST['strnam|2']);
		unset($_POST['strno|2']);
		unset($_POST['po|2']);
		unset($_POST['pb|2']);
		unset($_POST['pzone|2']);
		unset($_POST['crossstr|2']);
		unset($_POST['colonyn|2']);
		unset($_POST['district|2']);
		unset($_POST['region|2']);
		unset($_POST['state|2']);
		unset($_POST['zip|2']);
		unset($_POST['place|2']);
		unset($_POST['town|2']);
	}	

	// save adrress data to session
	$_SESSION['order_data']['fname'] = $_POST['f_name'];
	$_SESSION['order_data']['lname'] = $_POST['l_name'];
	$_SESSION['order_data']['country'] = $_POST['country'];
	$_SESSION['order_data']['street'] = $_POST['street'];
	$_SESSION['order_data']['state'] = $_POST['state'];
	$_SESSION['order_data']['town'] = $_POST['town'];
	$_SESSION['order_data']['zip'] = $_POST['zip'];
	$_SESSION['order_data']['email'] = $_POST['email'];
	$_SESSION['order_data']['telephone'] = $_POST['telephone'];
	$_SESSION['order_data']['shipp_fname'] = $_POST['f_name|2'];
	$_SESSION['order_data']['shipp_lname'] = $_POST['l_name|2'];
	$_SESSION['order_data']['shipp_country'] = $_POST['country|2'];
	$_SESSION['order_data']['shipp_street'] = $_POST['street|2'];
	$_SESSION['order_data']['shipp_state'] = $_POST['state|2'];
	$_SESSION['order_data']['shipp_town'] = $_POST['town|2'];
	$_SESSION['order_data']['shipp_zip'] = $_POST['zip|2'];

	// were countries chosen?
	if(isset($_POST['country']) && $_POST['country'] == 'bc'){
		$feedback['e_message'] .= "<span class='error'>$LANG[choose_billing_c]</span><br/>";  	
		$feedback['error'] 	= 1;
	}
	if(isset($_POST['country|2']) && $_POST['country|2'] == 'dc'){
		$feedback['e_message2'] .= "<span class='error'>$LANG[choose_delivery_c]</span><br/>";  			
	}			
	$cntr = (isset($_POST['country']))? $_POST['country']: 'UNITED ARAB EMIRATES';
	//which variables should be in the post array (based on the country)?		
	$table			= is_dbtable_there('countries');
	$country_col 	= 'country';
	if(WPLANG == 'de_DE'){$country_col = 'de';}
	if(WPLANG == 'fr_FR'){$country_col = 'fr';}
	
	$qStr 	= "SELECT address_format FROM $table WHERE $country_col = '$cntr' LIMIT 0,1";
	$res 	= mysql_query($qStr);
	$row 	= mysql_fetch_assoc($res);
	
	$address_format = str_replace('#',NULL,$row['address_format']);		
	$address_format = str_replace('%PLACE%','%TOWN%',$address_format);
	$address_format = str_replace('%place%','%town%',$address_format);
	$address_format = str_replace('name',NULL,$address_format);
	$address_format = str_replace('NAME',NULL,$address_format);
			
	$address_format = str_replace('(',NULL,$address_format);
	$address_format = str_replace(')',NULL,$address_format);
	$pAddress 		= explode('%',$address_format);
	
	$array['error'] = 0;
	for($i=0;$i<10;$i++){
		if(!isset($pAddress[$i])) break; 
		if((strlen($pAddress[$i]) > 1)&&(array_key_exists(strtolower($pAddress[$i]),$_POST) === FALSE)){
			$array['error'] = 1;
			$abc 			= $pAddress[$i];
			if($i<5) { $array['error'] = 0; break; } 
		}	
	}
	if($array['error'] == 1 ){
		$feedback['error'] 		= 1;
		$feedback['e_message'] 	.= "<span class='error'>$LANG[wait_for_field] $LANG[refresh_and_wait]</span><br/>"; 	
	}
			
	// Any fields empty?
	foreach($_POST as $k => $v){
		$_POST[$k] = strip_tags($v); // HTML tags are removed - if any 
		if(substr($k,-2) !== '|2'){
			if($v == '' && $k != 'zip'){ 
				$feedback['e_message'] .= "<span class='error'>$labels[$k] $LANG[field_not_empty]</span><br/>";  
				$feedback['error'] = 1;	
			}				
		}
		else{				
			if($v == ''){
				$k = substr($k,0,-2);
				$feedback['e_message2'] .= "<span class='error'>$labels[$k]  $LANG[field_not_empty]</span><br/>";  
				$feedback['error'] = 1;	
			}				
		}			
	}
	// Format of email ok?
	if(isset($_POST['email'])){
		$em_ok = NWS_validate_email($_POST['email']);
		if($em_ok == FALSE){
		
			$feedback['e_message'] .= "<span class='error'>$LANG[format_email]</span><br/>";  
			$feedback['error'] = 1;	
		}
	}
	// were the terms accepted? 
	$accepted = terms_accepted();
	if($accepted == FALSE){
			$feedback['e_message'] .= "<span class='error' id='errorTermsaccepted'>$LANG[terms_need_accepted]</span><br/>";  
			$feedback['error'] = 1;							
	}
	$_POST['custom_note'] = htmlspecialchars(strip_tags($tmp),ENT_QUOTES); 			
			
	return $feedback;
}

function redisplay_address_form($option = 'billing') {
	global $LANG;

	$b_country = 'UNITED ARAB EMIRATES';
	$d_country = 'UNITED ARAB EMIRATES';

	if ($_POST['order_step'] == 2) {
		$b_country	= trim($_POST['country']);
		$email		= trim($_POST['email']);
		$telephone	= trim($_POST['telephone']);
		$street		= trim($_POST['street']);
		$state		= trim($_POST['state']);
		$town		= trim($_POST['town']);
		$zip		= trim($_POST['zip']);

		$d_country	= trim($_POST['country|2']);
		$street_2	= trim($_POST['street|2']);
		$state_2	= trim($_POST['state|2']);
		$town_2		= trim($_POST['town|2']);
		$zip_2		= trim($_POST['zip|2']);
	} else if (isset($_SESSION['order_data'])) {
		$b_country	= $_SESSION['order_data']['country'];
		$email		= $_SESSION['order_data']['email'];
		$telephone	= $_SESSION['order_data']['telephone'];
		$street		= $_SESSION['order_data']['street'];
		$state		= $_SESSION['order_data']['state'];
		$town		= $_SESSION['order_data']['town'];
		$zip		= $_SESSION['order_data']['zip'];

		$d_country	= $_SESSION['order_data']['shipp_country'];
		$street_2	= $_SESSION['order_data']['shipp_street'];
		$state_2	= $_SESSION['order_data']['shipp_state'];
		$town_2		= $_SESSION['order_data']['shipp_town'];
		$zip_2		= $_SESSION['order_data']['shipp_zip'];
	} else if (isset($_SESSION['layaway_order_data'])) {
		$b_country	= $_SESSION['layaway_order_data']['country'];
		$email		= $_SESSION['layaway_order_data']['email'];
		$telephone	= $_SESSION['layaway_order_data']['telephone'];
		$street		= $_SESSION['layaway_order_data']['street'];
		$state		= $_SESSION['layaway_order_data']['state'];
		$town		= $_SESSION['layaway_order_data']['town'];
		$zip		= $_SESSION['layaway_order_data']['zip'];

		$d_country	= $_SESSION['layaway_order_data']['shipp_country'];
		$street_2	= $_SESSION['layaway_order_data']['shipp_street'];
		$state_2	= $_SESSION['layaway_order_data']['shipp_state'];
		$town_2		= $_SESSION['layaway_order_data']['shipp_town'];
		$zip_2		= $_SESSION['layaway_order_data']['shipp_zip'];
	}

	
	if($option == 'billing'){
		$ad	= get_address_format($b_country, 'filter');
		// loop thru address fields and produce input fields accordingly
		foreach($ad as $v){
			if($v == 'street') {
				echo "<label for='street_hsno'>$LANG[street_hsno]:</label><input id='street_hsno' type='text' 
				name='street' value='$street' maxlength='255' />";
			}
			if($v == 'hsno') {
				echo "<label for='hsno'>$LANG[hsno]:</label><input id='hsno' type='text' name='hsno' value='$hsno' 
				maxlength='255' />";
			}
			if($v == 'strnam') {
				echo "<label for='strnam'>$LANG[strnam]:</label><input id='strnam' type='text' name='strnam' 
				value='$strnam' maxlength='255' />";
			}
			if($v == 'strno') {
				echo "<label for='strno'>$LANG[strno]:</label><input id='strno' type='text' name='strno' 
				value='$strno' maxlength='255' />";
			}
			if($v == 'po') {
				echo "<label for='strno'>$LANG[strno]:</label><input id='strno' type='text' name='strno' value='$strno' 
				maxlength='255' />";
			}
			if($v == 'pb') {
				echo "<label for='pb'>$LANG[pb]:</label><input id='pb' type='text' name='pb' value='$pb' maxlength='255' />";
			}
			if($v == 'pzone') {
				echo "<label for='pzone'>$LANG[pzone]:</label><input id='pzone' type='text' name='pzone' 
				value='$pzone' maxlength='255' />";
			}
			if($v == 'crossstr') {
				echo "<label for='crossstr'>$LANG[crossstr]:</label><input id='crossstr' type='text' 
				name='crossstr' value='$_POST[crossstr]' maxlength='255' />";
			}
			if($v == 'colonyn') {
				echo "<label for='colonyn'>$LANG[colonyn]:</label><input id='colonyn' type='text' name='colonyn' 
				value='$_POST[colonyn]' maxlength='255' />";
			}
			if($v == 'district') {
				echo "<label for='district'>$LANG[district]:</label><input id='district' type='text' name='district' 
				value='$_POST[district]' maxlength='255' />";
			}
			if($v == 'region') {
				echo "<label for='region'>$LANG[region]:</label><input id='region' type='text' name='region' 
				value='$_POST[region]' maxlength='255' />";
			}
			if($v == 'state') {
				echo "<label for='state'>$LANG[state_province]:</label>";
				$ct 	= get_countries(3, $b_country);
				if(display_state_list($ct)) {
					echo province_me($ct, $state);
				} else {
					echo "<input  id='state' type='text' name='state' value='$state' maxlength='255' />";
				}
			}
			if($v == 'zip') {
				echo "<label for='zip'>$LANG[zip]:</label><input  id='zip' type='text' name='zip' 
				value='$zip' maxlength='255' />";
			}
			if($v == 'place') {
				echo "<label for='town'>$LANG[town]:</label><input  id='town' type='text' name='town' 
				value='$town' maxlength='255' />";
			}
		}
		echo "<label for='email'>$LANG[email]:</label><input  id='email'type='text' name='email' 
		value='$email' maxlength='255' />";
	
		echo "<label for='telephone'>$LANG[telephone]:</label><input id='telephone' type='text' name='telephone' 
		value='$telephone' maxlength='255' />";
	}

	if($option == 'shipping'){
		$ad		    = get_address_format($d_country, 'filter');
		// loop thru address fields and produce input fields accordingly
		foreach($ad as $v){	
			if($v == 'street') {
				echo "<label for='dstreet_hsno'>$LANG[street_hsno]:</label><input id='dstreet_hsno' type='text' 
				name='street|2' value='".$street_2."' maxlength='255' />";
			}	
			if($v == 'hsno') {
				echo "<label for='dhsno'>$LANG[hsno]:</label><input id='dhsno' type='text' name='hsno|2' 
				value='".$_POST['hsno|2']."' maxlength='255' />";				
			}	
			if($v == 'strnam') {
				echo "<label for='dstrnam'>$LANG[strnam]:</label><input id='dstrnam' type='text' 
				name='strnam|2' value='".$_POST['strnam|2']."' maxlength='255' />";
			}
			if($v == 'strno') {
				echo "<label for='dstrno'>$LANG[strno]:</label><input id='dstrno' type='text' name='strno|2' 
				value='".$_POST['strno|2']."' maxlength='255' />";
			}
			if($v == 'po') {
				echo "<label for='dpo'>$LANG[po]:</label><input id='dpo' type='text' name='po|2' 
				value='".$_POST['po|2']."' maxlength='255' />";
			}
			if($v == 'pb|2') {
				echo "<label for='dpb'>$LANG[pb]:</label><input id='dpb' type='text' name='pb|2' 
				value='".$_POST['pb|2']."' maxlength='255' />";
			}
			if($v == 'pzone') {
				echo "<label for='dpzone'>$LANG[pzone]:</label><input id='dpzone' type='text' name='pzone|2' 
				value='".$_POST['pzone|2']."' maxlength='255' />";		
			}
			if($v == 'crossstr') {
				echo "<label for='dcrossstr'>$LANG[crossstr]:</label><input id='dcrossstr' type='text' 
				name='crossstr|2' value='".$_POST['crossstr|2']."' maxlength='255' />";
			}
			if($v == 'colonyn') {
				echo "<label for='dcolonyn'>$LANG[colonyn]:</label><input id='dcolonyn' type='text' name='colonyn|2' 
				value='".$_POST['colonyn|2']."' maxlength='255' />";
			}
			if($v == 'district') {
				echo "<label for='ddistrict'>$LANG[district]:</label><input id='ddistrict' type='text' 
				name='district|2' value='".$_POST['district|2']."' maxlength='255' />";
			}
			if($v == 'region') {
				echo "<label for='dregion'>$LANG[region]:</label><input id='dregion' type='text' name='region|2' 
				value='".$_POST['region|2']."' maxlength='255' />";
			}
			if($v == 'state') {
				echo "<label for='dstate'>$LANG[state_province]:</label>"; 	
				$ct 	= get_countries(3,$d_country);		
				if(display_state_list($ct)) {
					echo province_me($ct,$state_2,'delivery');						
				}			
				else {
					echo "<input id='dstate' type='text' name='state|2' value='".$state_2."' maxlength='255' />";
				}
						}
			if($v == 'zip') {
				echo "<label for='dzip'>$LANG[zip]:</label><input id='dzip' type='text' 
				name='zip|2' value='".$zip_2."' maxlength='255' />";			
			}
			if($v == 'place') {
				echo "<label for='dtown'>$LANG[town]:</label><input id='dtown' type='text' 
				name='town|2' value='".$town_2."' maxlength='255' />";			
			}
		}
	}
}

function what_order_level($option=1,$txn_id=0,$who=0){
	$table 	= is_dbtable_there('orders');
	
	if($option == 1){
	$qStr 	= "SELECT level FROM $table WHERE who = '$_SESSION[cust_id]'";
	}
	if($option == 2){
	$qStr 	= "SELECT level FROM $table WHERE txn_id = '$txn_id' AND who = '$who'";
	}
	
	$res 	= mysql_query($qStr);
	$row 	= mysql_fetch_assoc($res);

return $row['level'];
}

function order_step_table($step,$option='4steps'){

	$LANG['payment_delivery'] = __('Payment &amp; Delivery Options &raquo;','wpShop');
	$LANG['address'] 			= __('Shipping &amp; Billing Address &raquo;','wpShop');
	$LANG['summary'] 			= __('Order Review &raquo;','wpShop');
	$LANG['payment'] 			= __('Payment &raquo;','wpShop');
	$LANG['finished'] 		= __('Confirmation','wpShop');

	
	$dp = delivery_payment_chosen();
	if($dp['p_option'] == 'paypal_pro'){$option = '5steps';}
	
	switch($option){
	
		case '4steps':
			$html =  "
			<ul class='oSteps'>
				<li class='[s1]'><span>1.</span>$LANG[payment_delivery]</li>
				<li class='[s2]'><span>2.</span>$LANG[address]</li>
				<li class='[s3]'><span>3.</span>$LANG[summary]</li>
				<li class='[s4]'><span>4.</span>$LANG[finished]</li>
			</ul>
			";	
			$max = 5;
			
		break; 
		
		case '5steps':
			$html = "
			<ul class='oSteps'>
				<li class='[s1]'><span>1.</span>$LANG[payment_delivery]</li>
				<li class='[s2]'><span>2.</span>$LANG[address]</li>
				<li class='[s3]'><span>3.</span>$LANG[summary]</li>
				<li class='[s4]'><span>4.</span>$LANG[payment]</li>
				<li class='[s5]'><span>5.</span>$LANG[finished]</li>
			</ul>
			";
			$max = 6;
		break; 		
	}	
	
	$aktiv_passiv = array();
	
	
	for($i=1; $i<$max; $i++){
		if($i == $step){
	     	$aktiv_passiv[$i] = 'aktiv';
		}else{
	     	$aktiv_passiv[$i] = 'passiv';
		}
	}
	
	foreach($aktiv_passiv as $k => $v){
		$key 	= '[s'.$k.']';
		$html	= str_replace($key,$v,"$html");						
	}
	
return $html;
}

function terms_accepted(){
	if(isset($_POST['terms_accepted']) && $_POST['terms_accepted'] == 'on')
	{
		$result = TRUE;
		
					$table = is_dbtable_there('orders');
					$column_value_array 	= array();
					$where_conditions 		= array();
					
					$column_value_array[terms] 	= '1';				
					$where_conditions[0]			= "who = '$_SESSION[cust_id]'";
										
					db_update($table, $column_value_array, $where_conditions);			
	}
	else {
		$result = FALSE;
	}
	
return $result; 
}

function calculate_shipping($d_option,$subtotal,$weight,$num_items,$country='US'){
	global $OPTION, $wpdb; 

	$freeShipping = false;
	$country = get_chosen_delivery_country();

	// we get main method 
	$sOption = $OPTION['wps_shipping_method'];

	// Get the list of categories that have free shipping
	$myIDs = $OPTION['wps_free_shipping_categories'];
	$myIDarray = explode(',' ,$myIDs);

	// overriding depending on exempted categories
	// check cart for products in the free shipping categories
	$sctable = is_dbtable_there('shopping_cart');
	$order_items = $wpdb->get_results(sprintf("SELECT * FROM %s WHERE who = '%s'", $sctable, $_SESSION['cust_id']));
	foreach($order_items as $order_item) {
		if (in_category($myIDarray, $order_item->postID)) {
			$freeShipping = true;
		}
	}
	if ($d_option == 'pickup')
	{
		$freeShipping = true;
	}

	if ($freeShipping)
	{
		$sOption = 'FREE';
	}
	
	// apply parameter according to smain-method
	switch($sOption){
		case 'FREE':
			$sFee = '0.00';
		break;

		case 'FLAT':
			$sFee = $OPTION['wps_shipping_flat_parameter'];		
		break;		

		case 'FLAT_LIMIT':		
			$param 	= $OPTION['wps_shipping_flatlimit_parameter'];
			$p		= explode("|",$param);
			
			if($subtotal >= $p[1]) {
				$sFee = '0.00';
				$freeShipping = true;
			} else {
				$sFee = $p[0];
				if ($country != 'UNITED ARAB EMIRATES') {
					$sFee = $num_items * $p[0];
				}
			}		
		break;		

		case 'WEIGHT_FLAT':
			$param 	= $OPTION['wps_shipping_weightflat_parameter'];
			
			$meassuring_unit = $OPTION['wps_meassuring_unit'];
			switch($meassuring_unit){
				case 'grams':
					$kg	= $weight / 1000;
				break;
				case 'pounds':
					$kg	= $weight;
				break;
			}
			
			$sFee	= $kg * $param;
			$sFee 	= round($sFee, 2);
		break;		

		case 'WEIGHT_CLASS':
			$wClasses	= array();
			$param 		= $OPTION['wps_shipping_weightclass_parameter'];
			
			//remove any whitespace at beginning+end and also # at the end
			$param 		= trim($param);
			if(substr($param, -1) == '#') {
				$param = substr($param, 0, -1);
			}

			$meassuring_unit = $OPTION['wps_meassuring_unit'];
			switch($meassuring_unit){
				case 'grams':
					$kg	= $weight / 1000;
				break;
				case 'pounds':
					$kg	= $weight;
				break;
			}
			
			$p = explode("#", $param);
			
			foreach($p as $v) {					// split into different realm/dollar chunks	
				$a  				= explode("|",$v);
				$wClasses["$a[1]"]	= $a[0];	//we want array values like $array[dollar] = kg-kg
			}

			foreach($wClasses as $k => $v){		
				$b 		= explode("-",$v);		// create minimum/maximum kg values

				if($b[1] == 'ul'){				// in case the maximum value is ul we add 10.00 kg to the weight to make it fit the realm
					$b[1] = $kg + 10.00;
				}
				
				$b[0]	= (float) $b[0];		// typecast to float
				$b[1]	= (float) $b[1];				
				
				
				if($kg >= $b[0] && $kg <= $b[1])	// the actual minimum - maximum checking 
				{
					$sFee = $k;
				}				
			}
		break;			

		case 'PER_ITEM':
			$param 	= $OPTION['wps_shipping_peritem_parameter'];		
			$sFee	= $param;
			if ($country != 'UNITED ARAB EMIRATES') {
				$sFee = $num_items * $param;
			}
		break;		

		case 'PER_ITEM_SINGLE':		
			// will come later 
		break;	
	}

	// so far, so good - Delivery to country other than shop-country?
	$dc = get_delivery_countries();

	if($dc['num'] != 0 && !$freeShipping){
		$col = 'country';
		if(WPLANG == 'de_DE' || WPLANG == 'fr_FR') {
			$col = substr(WPLANG, 0, 2);
		}
	
		$ctable	= is_dbtable_there('countries');
		$czone = $wpdb->get_var(sprintf("SELECT zone FROM %s WHERE %s = '%s' LIMIT 0, 1", $ctable, $col, $country));

		// get surcharge
		if (strlen($czone)) {
			$surcharge = $OPTION['wps_shipping_zone'.$czone.'_addition'];
			if ($surcharge) {
				$sFee = $sFee + $surcharge;
			}
		}
	}

	return sprintf("%01.2f", $sFee);
}


function update_order($weight,$shipping,$subtotal,$voucher=0.00,$tax='0.00'){
	global $current_user;
	$table 					= is_dbtable_there('orders');
	$column_value_array 	= array();
	$where_conditions 		= array();

	$net = $subtotal - $voucher;
	$amount = ($subtotal - $voucher) + $shipping + $tax;
	if ($_SESSION['layaway_order'] > 0) {
		$amounts = layaway_get_process_amounts($_SESSION['layaway_order']);
		$amount = $amounts['balance'];
	}
	if ($_SESSION['layaway_process'] == 1) {
		$amount = $_SESSION['layaway_amount'];
		if ($amount > $net) { $amount = $net; }
	}

	$column_value_array['user_id'] 		   = $current_user->ID;
	$column_value_array['weight'] 		   = $weight;
	$column_value_array['shipping_fee']    = $shipping;					
	$column_value_array['tax'] 			   = $tax;
	$column_value_array['net'] 			   = $net;
	$column_value_array['amount'] 		   = $amount;
	$column_value_array['voucher_amount']  = $voucher;
	$column_value_array['currency_code']   = $_SESSION['currency-code'];
	$column_value_array['currency_rate']   = $_SESSION['currency-rate'];
	$column_value_array['layaway_process'] = $_SESSION['layaway_process'];
	$column_value_array['layaway_order']   = $_SESSION['layaway_order'];

	$where_conditions[0] = "who = '$_SESSION[cust_id]'";

	db_update($table, $column_value_array, $where_conditions);

	return $amount;
}

function process_payment($feedback,$option){
	global $wpdb, $OPTION, $current_user;
		// update orders table according to payment status
		$table 					= is_dbtable_there('orders');
		$column_value_array 	= array();
		$where_conditions 		= array();

					
			switch($option){				
									
				case 'paypal':
					if (!$_SESSION['cust_id']) { $_SESSION['cust_id'] = $feedback['who']; }
					$parts	= explode("-",$_SESSION['cust_id']);
					$txn_id = $feedback['txn_id'];
					if($feedback['payment_status'] == 'Completed'){
						$column_value_array['txn_id'] 		= $txn_id;
						$column_value_array['tracking_id'] 	= $parts[0];
						$column_value_array['order_time'] 	= time();
												
						$cart_comp = cart_composition($_SESSION['cust_id']);
						if($cart_comp == 'digi_only'){
							$column_value_array['level'] 	= '7';
						} else {
							$column_value_array['level'] 	= '4';
						}
					}
					elseif($feedback['payment_status'] == 'Pending'){
						$column_value_array['txn_id'] 		= $txn_id;
						$column_value_array['tracking_id'] 	= $parts[0];
						$column_value_array['order_time'] 	= time();
						$column_value_array['pending_r']	= 'na';
						$column_value_array['level'] 		= '8';
					}
					elseif($feedback['payment_status'] == 'free'){
						$column_value_array['txn_id'] 		= $txn_id;
						$column_value_array['tracking_id'] 	= $parts[0];
						$column_value_array['order_time'] 	= time();
						$column_value_array['level'] 		= '7';
					}

					$where_conditions[0]				= "who = '$_SESSION[cust_id]'";

					db_update($table, $column_value_array, $where_conditions);	

					$qStr 	= "SELECT * FROM $table WHERE who = '$_SESSION[cust_id]' LIMIT 1";
					$res 	= mysql_query($qStr);
					$order 	= mysql_fetch_assoc($res);

				break;	
					
										
				case 'authn':						
						
					// we need to get the 'who' token 
					$qStr 	= "SELECT who FROM $table WHERE txn_id = '$feedback[temp_txn_id]' LIMIT 1";
					$res 	= mysql_query($qStr);
					$row 	= mysql_fetch_assoc($res);	
					$parts	= explode("-",$row[who]);
					
					if($feedback['status'] == 1){
						$column_value_array['txn_id'] 		= $feedback['trans_id'];
						$column_value_array['tracking_id'] 	= $parts[0];	
						$column_value_array['order_time'] 	= time();
												
						$cart_comp = cart_composition($row['who']);
						if($cart_comp == 'digi_only'){
							$column_value_array['level'] 			= '7';						
						}
						else{						
							$column_value_array['level'] 			= '4';
						}
					}
					elseif($payment_status == 'Pending'){
						$column_value_array['txn_id'] 		= $txn_id;
						$column_value_array['tracking_id'] 	= $parts[0];	
						$column_value_array['order_time'] 	= time();
						$column_value_array['pending_r']		= $pending_r; 
						$column_value_array['level'] 			= '8';					
					}
					else {}
					
				
					$where_conditions[0]				= "txn_id = '$feedback[temp_txn_id]'";
																				
					db_update($table, $column_value_array, $where_conditions);	
					
					$qStr 	= "SELECT * FROM $table WHERE txn_id = '$feedback[trans_id]' LIMIT 1";
					$res 	= mysql_query($qStr);
					$order 	= mysql_fetch_assoc($res);				
				
				break;
				
				case 'audi':	
					$parts	= explode("-", $_SESSION[cust_id]);
					$column_value_array['txn_id'] 		= $feedback['trans_id'];
					$column_value_array['order_time'] 	= time();
					$column_value_array['tracking_id'] 	= $parts[0];
					$column_value_array['level'] 		= '4';
				
					$where_conditions[0]				= "who = '$_SESSION[cust_id]'";
																				
					db_update($table, $column_value_array, $where_conditions);	
					
					$qStr 	= "SELECT * FROM $table WHERE who = '$_SESSION[cust_id]' LIMIT 1";
					$res 	= mysql_query($qStr);
					$order 	= mysql_fetch_assoc($res);
				
				break;				
				
				case 'g2p_authn':						
					
					$qStr 	= "SELECT who FROM $table WHERE txn_id = '$feedback[temp_txn_id]' LIMIT 1";
					$res 	= mysql_query($qStr);
					$row 	= mysql_fetch_assoc($res);	
					$parts	= explode("-",$row[who]);		
					$where_conditions[0]				= "txn_id = '$feedback[temp_txn_id]'";
					// we need to get the 'who' token 
					if($feedback['status'] == 1){
						$column_value_array['txn_id'] 		= $feedback['trans_id'];
						$column_value_array['tracking_id'] 	= $parts[0];	
						$column_value_array['order_time'] 	= time();
												
						$cart_comp = cart_composition($row['who']);
						if($cart_comp == 'digi_only'){
							$column_value_array['level'] 			= '7';						
						}
						else{						
							$column_value_array['level'] 			= '4';
						}
					}
					elseif($payment_status == 'Pending'){
						$column_value_array['txn_id'] 		= $txn_id;
						$column_value_array['tracking_id'] 	= $parts[0];	
						$column_value_array['order_time'] 	= time();
						$column_value_array['pending_r']		= $pending_r; 
						$column_value_array['level'] 			= '8';					
					}
					else {}
					db_update($table, $column_value_array, $where_conditions);	
					
					$qStr 	= "SELECT * FROM $table WHERE txn_id = '$feedback[trans_id]' LIMIT 1";
					$res 	= mysql_query($qStr);
					$order 	= mysql_fetch_assoc($res);				
				
				break;
				case 'wpay': 
				
					if($feedback[status] == 'Y'){					
					
						// we need to get the 'who' token 
						$qStr 	= "SELECT who FROM $table WHERE txn_id = '$feedback[temp_txn_id]' LIMIT 1";
						$res 	= mysql_query($qStr);
						$row 	= mysql_fetch_assoc($res);	
						$parts	= explode("-",$row[who]);	
					
					
						$column_value_array['txn_id'] 		= $feedback['trans_id'];
						$column_value_array['tracking_id'] 	= $parts[0];
						$column_value_array['order_time'] 	= time();
												
						$cart_comp = cart_composition($row['who']);
						if($cart_comp == 'digi_only'){
							$column_value_array['level'] 			= '7';						
						}
						else{						
							$column_value_array['level'] 			= '4';
						}
					}
					elseif($payment_status == 'Pending'){
						$column_value_array['txn_id'] 		= $txn_id;
						$column_value_array['tracking_id'] 	= $parts[0];
						$column_value_array['order_time'] 	= time();
						$column_value_array['pending_r']		= $pending_r; 
						$column_value_array['level'] 			= '8';					
					}
					else {}
					
				
					$where_conditions[0]				= "txn_id = '$feedback[temp_txn_id]'";
																				
					db_update($table, $column_value_array, $where_conditions);	
					
					$qStr 	= "SELECT * FROM $table WHERE txn_id = '$feedback[trans_id]' LIMIT 1";
					$res 	= mysql_query($qStr);
					$order 	= mysql_fetch_assoc($res);				
				
				break;
				case 'alertpay':
				
					if($feedback['status'] == 'Success'){					
					
						// we need to get the 'who' token 
						$qStr 	= "SELECT who FROM $table WHERE who = '$feedback[temp_txn_id]' LIMIT 1";
						$res 	= mysql_query($qStr);
						$row 	= mysql_fetch_assoc($res);	
						$parts	= explode("-",$row[who]);	
					
					
						$column_value_array['txn_id'] 		= $feedback['trans_id'];
						$column_value_array['tracking_id'] 	= $parts[0];
						$column_value_array['order_time'] 	= time();
												
						$cart_comp = cart_composition($row['who']);
						if($cart_comp == 'digi_only'){
							$column_value_array['level'] 			= '7';						
						}
						else{						
							$column_value_array['level'] 			= '4';
						}
					}
					elseif($payment_status == 'Pending'){
						$column_value_array['txn_id'] 		= $txn_id;
						$column_value_array['tracking_id'] 	= $parts[0];
						$column_value_array['order_time'] 	= time();
						$column_value_array['pending_r']		= $pending_r; 
						$column_value_array['level'] 			= '8';					
					}
					else {}
					
				
					$where_conditions[0]				= " who = '$feedback[temp_txn_id]'";
																				
					db_update($table, $column_value_array, $where_conditions);	
					
					$qStr 	= "SELECT * FROM $table WHERE txn_id = '$feedback[trans_id]' LIMIT 1";
					$res 	= mysql_query($qStr);
					$order 	= mysql_fetch_assoc($res);				
				
				break;
				case '2checkout':
				
					if($feedback['status'] == 'Success'){					
					
						// we need to get the 'who' token 
						$qStr 	= "SELECT who FROM $table WHERE who = '$feedback[temp_txn_id]' LIMIT 1";
						$res 	= mysql_query($qStr);
						$row 	= mysql_fetch_assoc($res);	
						$parts	= explode("-",$row['who']);	
					
					
						$column_value_array['txn_id'] 		= $feedback['trans_id'];
						$column_value_array['tracking_id'] 	= $parts[0];
						$column_value_array['order_time'] 	= time();
												
						$cart_comp = cart_composition($row['who']);
						if($cart_comp == 'digi_only'){
							$column_value_array['level'] 			= '7';						
						}
						else{						
							$column_value_array['level'] 			= '4';
						}
					}
					elseif($payment_status == 'Pending'){
						$column_value_array['txn_id'] 		= $txn_id;
						$column_value_array['tracking_id'] 	= $parts[0];
						$column_value_array['order_time'] 	= time();
						$column_value_array['pending_r']		= $pending_r; 
						$column_value_array['level'] 			= '8';					
					}
					else {}
					
				
					$where_conditions[0]				= " who = '$feedback[temp_txn_id]'";
																				
					db_update($table, $column_value_array, $where_conditions);	
					
					$qStr 	= "SELECT * FROM $table WHERE txn_id = '$feedback[trans_id]' LIMIT 1";
					$res 	= mysql_query($qStr);
					$order 	= mysql_fetch_assoc($res);				
				
				break;
				
				
				case 'bt':
				
					$parts	= explode("-",$_SESSION[cust_id]);
				
					if($feedback['status'] == 'Completed'){
						$column_value_array['txn_id'] 		= md5(microtime());
						$column_value_array['tracking_id'] 	= $parts[0];
						$column_value_array['order_time'] 	= time();
						$column_value_array['level'] 			= '4';
					}
					elseif($payment_status == 'Pending'){
						$column_value_array['txn_id'] 		= $txn_id;
						$column_value_array['tracking_id'] 	= $parts[0];
						$column_value_array['order_time'] 	= time();
						$column_value_array['pending_r']		= $pending_r; 
						$column_value_array['level'] 			= '8';					
					}
					elseif($payment_status == 'free'){
						$column_value_array['txn_id'] 		= $txn_id;
						$column_value_array['tracking_id'] 	= $parts[0];
						$column_value_array['order_time'] 	= time();
						$column_value_array['level'] 			= '7';							
					}
					else {}
					
				
					$where_conditions[0]				= "who = '$_SESSION[cust_id]'";
																				
					db_update($table, $column_value_array, $where_conditions);	
					
					$qStr 	= "SELECT * FROM $table WHERE who = '$_SESSION[cust_id]' LIMIT 1";
					$res 	= mysql_query($qStr);
					$order 	= mysql_fetch_assoc($res);				
				
				break;
				
				
				case 'cas':
				
					$parts	= explode("-",$_SESSION[cust_id]);
				
					if($feedback['status'] == 'Completed'){
						$column_value_array['txn_id'] 		= md5(microtime());
						$column_value_array['tracking_id'] 	= $parts[0];
						$column_value_array['order_time'] 	= time();											
						$column_value_array['level'] 			= '4';
						
					}
					elseif($payment_status == 'Pending'){
						$column_value_array['txn_id'] 		= $txn_id;
						$column_value_array['tracking_id'] 	= $parts[0];
						$column_value_array['order_time'] 	= time();
						$column_value_array['pending_r']		= $pending_r; 
						$column_value_array['level'] 			= '8';					
					}
					elseif($payment_status == 'free'){
						$column_value_array['txn_id'] 		= $txn_id;
						$column_value_array['tracking_id'] 	= $parts[0];
						$column_value_array['order_time'] 	= time();
						$column_value_array['level'] 			= '7';							
					}
					else {}
					
				
					$where_conditions[0]				= "who = '$_SESSION[cust_id]'";
																				
					db_update($table, $column_value_array, $where_conditions);	
					
					$qStr 	= "SELECT * FROM $table WHERE who = '$_SESSION[cust_id]' LIMIT 1";
					$res 	= mysql_query($qStr);
					$order 	= mysql_fetch_assoc($res);				
				
				break;			
				
				case 'cod':
				
					$parts	= explode("-",$_SESSION['cust_id']);
				
					if($feedback['status'] == 'Completed'){
						$column_value_array['txn_id'] 		= md5(microtime());
						$column_value_array['tracking_id'] 	= $parts[0];
						$column_value_array['order_time'] 	= time();											
						$column_value_array['level'] 			= '4';
						
					}
					elseif($payment_status == 'Pending'){
						$column_value_array['txn_id'] 		= $txn_id;
						$column_value_array['tracking_id'] 	= $parts[0];
						$column_value_array['order_time'] 	= time();
						$column_value_array['pending_r']		= $pending_r; 
						$column_value_array['level'] 			= '8';					
					}
					elseif($payment_status == 'free'){
						$column_value_array['txn_id'] 		= $txn_id;
						$column_value_array['tracking_id'] 	= $parts[0];
						$column_value_array['order_time'] 	= time();
						$column_value_array['level'] 			= '7';							
					}
					else {}
					
				
					$where_conditions[0]				= "who = '$_SESSION[cust_id]'";
																				
					db_update($table, $column_value_array, $where_conditions);	
					
					$qStr 	= "SELECT * FROM $table WHERE who = '$_SESSION[cust_id]' LIMIT 1";
					$res 	= mysql_query($qStr);
					$order 	= mysql_fetch_assoc($res);				
				
				break;				
				
				
				case 'paypal_pro':
				
					$parts	= explode("-",$_SESSION[cust_id]);
				
					if($feedback['status'] == 'Completed'){
						$column_value_array['txn_id'] 		= md5(microtime());
						$column_value_array['tracking_id'] 	= $parts[0];
						$column_value_array['order_time'] 	= time();
						$column_value_array['level'] 			= '4';
					}
					elseif($payment_status == 'Pending'){
						$column_value_array['txn_id'] 		= $txn_id;
						$column_value_array['tracking_id'] 	= $parts[0];
						$column_value_array['order_time'] 	= time();
						$column_value_array['pending_r']		= $pending_r; 
						$column_value_array['level'] 			= '8';					
					}
					elseif($payment_status == 'free'){
						$column_value_array['txn_id'] 		= $txn_id;
						$column_value_array['tracking_id'] 	= $parts[0];
						$column_value_array['order_time'] 	= time();
						$column_value_array['level'] 			= '7';							
					}
					else {}
					
				
					$where_conditions[0]				= "who = '$_SESSION[cust_id]'";
																				
					db_update($table, $column_value_array, $where_conditions);	
					
					$qStr 	= "SELECT * FROM $table WHERE who = '$_SESSION[cust_id]' LIMIT 1";
					$res 	= mysql_query($qStr);
					$order 	= mysql_fetch_assoc($res);				
				
				break;				
			}

		// layaway order
		if ($order['layaway_process'] == 1 || $order['layaway_order'] > 0) {
			mysql_query("UPDATE $table SET level = '2' WHERE oid = '".$order['oid']."'");
			$order_id = layaway_process_action($order);
			$qStr 	= "SELECT * FROM $table WHERE oid = '$order[oid]' LIMIT 1";
			$res 	= mysql_query($qStr);
			$order 	= mysql_fetch_assoc($res);
		}

		// update products inventory
		$sctable = is_dbtable_there('shopping_cart');
		$cart_products = $wpdb->get_results(sprintf("SELECT * FROM %s WHERE who = '%s'", $sctable, $order['who']));
		if ($cart_products) {
			foreach($cart_products as $cart_product) {
				$current_inventory = get_item_inventory($cart_product->postID, $cart_product->item_id);
				$amount = $current_inventory - $cart_product->item_amount;
				if ($amount < 0) { $amount = 0; }
				update_item_inventory($cart_product->item_id, $amount, true, 'Order completed - oid = '.$order['oid']);
			}
		}

		// voucher management 
		$qStr 	= "SELECT voucher FROM $table WHERE who = '$order[who]' LIMIT 0,1"; 
		$res 	= mysql_query($qStr);
		$row	= mysql_fetch_assoc($res);
		
		if($row['voucher'] != 'non'){  //..then update vouchers table 
		
			$table2	= is_dbtable_there('vouchers');	
			$qStr 	= "SELECT duration FROM $table2 WHERE vcode = '$row[voucher]' LIMIT 0,1"; 
			$res2 	= mysql_query($qStr);
			$row2	= mysql_fetch_assoc($res2);
			
			$column_value_array 	= array();
			$where_conditions 		= array();						
			
			if($row2['duration'] == '1time'){
			$column_value_array['used'] 		= '1';
			}
			$column_value_array['time_used'] 	= date("F j, Y");
			$column_value_array['who'] 			= $_SESSION['cust_id'];
			$where_conditions[0]				= "vcode = '$row[voucher]'";
																				
			db_update($table2, $column_value_array, $where_conditions);	
		}

		// subscribe user
		nws_subscribe_action('checkout', array('email' => $order['email']));
		if (is_user_logged_in()) {
			$cuemail = $current_user->data->user_email;
			if ($order['email'] != $cuemail) {
				nws_subscribe_action('checkout', array('email' => $cuemail));
			}
		}

return $order;
}

function get_chosen_doption(){
				
					$table = is_dbtable_there('orders');
						
					$d_op 				= array(); 	
					$qStr 				= "SELECT * FROM $table WHERE who = '$_SESSION[cust_id]' LIMIT 1";
					$res 				= mysql_query($qStr);
					$row 				= mysql_fetch_assoc($res);
					$d_op[method]		= $row[d_option];
					$d_op[tracking_id]	= $row[tracking_id];

return $d_op;
}

function url_be($option='orders'){

	global $OPTION;

	switch($option){
	
		case 'orders':
			$url = get_option('siteurl').'/wp-admin/themes.php?page=functions.php&section=orders';
		break;
		
		case 'lkeys':
			$url = get_option('siteurl').'/wp-admin/themes.php?page=functions.php&section=lkeys';
		break;		
		
		
	}
return $url;
}


function NWS_validate_email($email){
		if(preg_match ("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email)){
            	$feedback = TRUE;
	     }
	     else

	     {
            	$feedback = FALSE;
	     }
		 
return $feedback;
}

function change_order_level($oid,$new_level){

					// update orders table
					$table = is_dbtable_there('orders');
					$column_value_array 	= array();
					$where_conditions 		= array();
					
					$column_value_array[level] 		= $new_level;
					
					$where_conditions[0]			= "oid = $oid";
										
					db_update($table, $column_value_array, $where_conditions);
	
return TRUE;
}

function show_orders($who){
	
	$table 	= is_dbtable_there('shopping_cart');	
	$CART 	= array();
	

	$qStr 			= "SELECT * FROM $table WHERE who = '$who'";
	$res 			= mysql_query($qStr);
	$num 			= mysql_num_rows($res);
	
		$item			= array();
		$i				= 0;
		
		
			while ($row = mysql_fetch_assoc($res)) {
				
				$personalize 			=  retrieve_personalization($row[cid]); // Personalization 
			
				$item[$i][num]			= $row[item_amount];
				$item[$i][price] 		= $row[item_price] * $row[item_amount];
				$item[$i][weight] 		= $row[item_weight] * $row[item_amount];
				$CART[$i]				= $row[cid].'|'.$row[item_amount].'|'.$row[item_name].'|'.$row[item_price].'|';
				$CART[$i]				.= sprintf("%01.2f",$item[$i][price]).'|'.$row[item_id].'|'.$row[item_attributs].'|'.$personalize;
				$i++;
			}	
			
return $CART;
}

function list_order_items($order_items)
{
	$data = "<table>
				<thead>
					<tr>
						<th>".__('Qty','wpShop')."</th>
						<th>".__('ID','wpShop')."</th>
						<th>".__('Item','wpShop')."</th>
						<th>".__('Unit','wpShop')."</th>
						<th>".__('Total','wpShop')."</th>
					</tr>
				</thead>
	";
	foreach($order_items as $v)
	{
		$details 		= explode("|",$v);	
		$attributes 	= "<br/>".display_attributes($details[6],'html_be');
		$personalize 	= (!empty($details[7])? "<br/>".$details[7] : NULL);	
		$attributes = str_replace('<br/><br/>','<br/>',$attributes);					
		$digital_delivered 	= NULL;
		$extra_style		= NULL;						
		$data .= "<tr $extra_style>
					<td id='item_qty'>$details[1]x</td>
					<td id='item_id'>$details[5]</td>
					<td id='item_name'><b>$details[2]</b> $attributes $personalize $digital_delivered</td>
					<td>";
		$data .= '$'.format_price($details[3]);
		$data .= "</td><td>";
		$data .= '$'.format_price($details[4]); 
		$data .= "</td>
		<input type='hidden' name='cid' id='cid' value='$details[0]' />
		</tr>";
	}
	$data .= "</table>";				
	return $data;
}
function check_order_status($tid){

	$labels 		= array();
	$labels['4'] 	= __('Your Order has been received and is currently in Queue to be Processed.','wpShop');
	$labels['5'] 	= __('Your Order is currently being Processed.','wpShop');
	$labels['6'] 	= __('Your Order has been Shipped.','wpShop');
	$labels['7'] 	= __('Order Completed. Items Delivered.','wpShop');
	$labels['8'] 	= __('Your Payment is still Pending.','wpShop');

	$table 	= is_dbtable_there('orders');
	$qStr 	= "SELECT * FROM $table WHERE tracking_id = '$tid' LIMIT 1";
	$res 	= mysql_query($qStr);
	$row 	= mysql_fetch_assoc($res);	
	$status = $row[level];
	
	$feedback = $labels[$status];

return $feedback;
}


function get_countries($option=1,$land='US'){

	$table 	= is_dbtable_there('countries');
	
	
	if($option == 1){
		if(WPLANG == 'de_DE'){
			$orderby = 'ORDER BY de ASC';
		}
		elseif(WPLANG == 'fr_FR'){
			$orderby = 'ORDER BY fr ASC';
		}
		else{
			$orderby = 'ORDER BY country ASC';
		}


		$output			= array();
		$qStr 			= "SELECT * FROM $table $orderby";
		$res 			= mysql_query($qStr);
		
		while($row = mysql_fetch_assoc($res)){
			if(WPLANG == 'de_DE'){
				$output[] 	= $row['de'].'|'.$row['abbr'];	
			}
			if(WPLANG == 'fr_FR'){
				$output[] 	= $row['fr'].'|'.$row['abbr'];	
			}
			else{
				$output[] 	= $row['country'].'|'.$row['abbr'];
			}
		}
	} else if($option == 2){
		$qStr 			= "SELECT * FROM $table WHERE abbr = '$land' LIMIT 0,1";  
		$res 			= mysql_query($qStr);
		$row			= mysql_fetch_assoc($res);	
		$output			= $row['country'];
		if(WPLANG == 'de_DE'){
			$output			= $row['de'];	
		}
		if(WPLANG == 'fr_FR'){
			$output			= $row['fr'];	
		}
	} else if($option == 3){
		$qStr 			= "SELECT * FROM $table WHERE country = '$land' LIMIT 1";
		if(WPLANG == 'de_DE'){
			$qStr 			= "SELECT * FROM $table WHERE de = '$land' LIMIT 1";	
		}
		if(WPLANG == 'fr_FR'){
			$qStr 			= "SELECT * FROM $table WHERE fr = '$land' LIMIT 1";	
		}
		$res 			= mysql_query($qStr);
		$row			= mysql_fetch_assoc($res);	
		$output			= $row['abbr'];
	} else if($option == 4){ // for zone selection 
		if(WPLANG == 'de_DE'){
			$orderby = 'ORDER BY de ASC';
		}
		elseif(WPLANG == 'fr_FR'){					
			$orderby = 'ORDER BY fr ASC';
		}
		else{
			$orderby = 'ORDER BY country ASC';
		}

		$output			= array();
		$qStr 			= "SELECT * FROM $table $orderby";
		$res 			= mysql_query($qStr);
		
		while($row = mysql_fetch_assoc($res)){
			if(WPLANG == 'de_DE') {
				$output[] 	= $row['de'].'|'.$row['abbr'].'|'.$row['zone'].'|'.$row['tax_zone'];
			} elseif(WPLANG == 'fr_FR') {
				$output[] 	= $row['fr'].'|'.$row['abbr'].'|'.$row['zone'].'|'.$row['tax_zone'];
			} else {
				$output[] 	= $row['country'].'|'.$row['abbr'].'|'.$row['zone'].'|'.$row['tax_zone'];
			}
		}
	}

	return $output;
}

function update_zone_one($old){

		global $CONFIG_WPS,$OPTION;

				$new 	= $OPTION['wps_shop_country'];
				$table 	= is_dbtable_there('countries');
				
				if(strlen($new) > 0){ 
				
					$qStr1 = "UPDATE $table 
							SET zone = '0'
							WHERE abbr = '$old'";
					mysql_query($qStr1);
				
					$qStr2 = "UPDATE $table 
							SET zone = '1'
							WHERE abbr = '$new'";
					mysql_query($qStr2);
				}
}


function get_delivery_countries($option='with_zones'){

		global $CONFIG_WPS,$OPTION;
		
		switch(WPLANG){	
			case 'de_DE':
				$col = 'de';
			break;
			case 'fr_FR':
				$col = 'fr';
			break;
			default:
				$col = 'country';
			break;
		}
		
		switch($option){
			
			case 'with_zones':
		
				$output = array();
				
				$table 	= is_dbtable_there('countries');
				$qStr 	= "SELECT * FROM $table WHERE zone IN (2,3,4,5,6) ORDER BY $col";
				$res 	= mysql_query($qStr);					
				$num 	= mysql_num_rows($res);

				if($num > 0){
					$output['res'] = $res;
				}
				else{
					$output['res'] = 'none';
				}
				
				$output['num'] = $num;
			
			break;
			
			case 'no_zone':
			
				$table 	= is_dbtable_there('countries');
				$abbr	= $OPTION['wps_shop_country'];
				$qStr 	= "SELECT * FROM $table WHERE abbr = '$abbr' LIMIT 0,1";	
				$res 	= mysql_query($qStr);
				$row 	= mysql_fetch_assoc($res);
				$output = $row[$col];
				
			break;
		}
				
return $output;			
}


function get_chosen_delivery_country(){
	global $OPTION, $wpdb;

	$otable = is_dbtable_there('orders');
	$dtable = is_dbtable_there('delivery_addr');

	$delivery_country = $wpdb->get_var(sprintf("SELECT country FROM %s WHERE who = '%s' LIMIT 0, 1", $otable, $_SESSION['cust_id']));

	$delivery_addr_country = $wpdb->get_var(sprintf("SELECT country FROM %s WHERE who = '%s' LIMIT 0, 1", $dtable, $_SESSION['cust_id']));
	if(strlen($delivery_addr_country)){
		$delivery_country = $delivery_addr_country;
	}

	return $delivery_country;
}

function thumbs_height($pic_path,$new_width){

	  $size		= getimagesize($pic_path);
	  $w		= $size[0];
	  $h		= $size[1];
	  
	  $height	= intval($h*$new_width/$w);

return $height;
}

function cart_thumb_height($thumb_file,$thumb_path,$addition = NULL){

	$pic_path	= $thumb_path . $addition . $thumb_file;
	$th_height	= thumbs_height($pic_path,$OPTION['wps_cart_thumb_width']);

return $th_height;
}

function current_page($option=1){

	global $OPTION;

	if($option == 1){	// this is used for getting the url for "continue shopping button"
		if((!isset($_GET[orderNow]))  && (!isset($_POST[update]))){
			$path  = dirname($_SERVER['PHP_SELF']);
			$cPage = ($path == '/' ? '' : $path).$_SERVER['REQUEST_URI'];
		}
		else {
			$cPage = '0';
		}
	}					// 2-3 is used e.g. for add to cart 
	elseif($option == 2){
			$path  = dirname($_SERVER['PHP_SELF']);
			$cPage = ($path == '/' ? '' : $path).$_SERVER['REQUEST_URI'];	
	}
	elseif($option == 3){	
	/*
			$path  		= $OPTION['home'];
			$addition 	= $_SERVER['REQUEST_URI'];				
			$url 		=  parse_url($path);
			$cPage 		= 'http://'.$url[host].$addition;	
	*/
			$path  		= get_option('home');
			$addition 	= explode($path,$_SERVER['REQUEST_URI']);	
			$cPage 		= $addition[0];				
	}
	elseif($option == 4){	// header cart url
			$path  		= get_option('home');
			$addition 	= explode($path,$_SERVER['REQUEST_URI']);	
			$add		= str_replace('?added=OK','',$addition[0]);
			$add 		= str_replace('&l=cart','',$add);
			$add 		= str_replace('?failed=1','',$add);
			$cPage 		= $add;				
	}
	else {}
		
return $cPage;
}

function payment_pending($txn_id){

	$table 	= is_dbtable_there('orders');
	
	$qStr 			= "SELECT * FROM $table WHERE txn_id = '$txn_id' LIMIT 1";
	$res 			= mysql_query($qStr);
	$row			= mysql_fetch_assoc($res);

	if($row[pending_r] != 'na'){
		$status = 1;
	}
	else {
		$status = 0;
	}
	 
return $status;
}

function get_doption_tracking_id(){

	$table 			= is_dbtable_there('orders');
	
	$data			= array();
	
	$qStr 			= "SELECT * FROM $table WHERE who = '$_SESSION[cust_id]' LIMIT 1";
	$res 			= mysql_query($qStr);
	$row			= mysql_fetch_assoc($res);

	$data[d_option]		= $row[d_option];
	$data[tracking_id]	= $row[tracking_id];
	
	
return $data;
}

##################################################################################################################################
// 												DIGITAL - GOODS 
##################################################################################################################################
function make_random_str($length = 4){
  	$salt = "abchefghjkmnpqrstuvwxyz0123456789";
  		srand((double)microtime()*1000000);
      	$i = 0;
	      while ($i <= $length) {
	            $num = rand() % 33;
	            $tmp = substr($salt, $num, 1);
	            $pass = $pass . $tmp;
	            $i++;
	      }
		  
return $pass;
}

function lkeys_enough($fname){

	global $OPTION;
	
	$EMAIL 		= load_what_is_needed('email');		//change.9.10

	$table 		= is_dbtable_there('lkeys');
	$qStr 		= "SELECT lid FROM $table WHERE filename = '$fname' AND used = '0'";
	$res 		= mysql_query($qStr);
	$num 		= mysql_num_rows($res);	
	
	$minimum 	= $OPTION['wps_lkeys_warn_num'];
	
	if($num < $minimum){

				$search		= array("[##header##]","[##fname##]","[##minimum##]","[##url##]");						
				$replace 	= array($EMAIL->email_header(),$fname,$minimum,url_be('lkeys'));			//change.9.10
				
				$EMAIL->email_owner_lkey_warning($fname,$search,$replace);			//change.9.10
	}
}

function find_masterdata_path($option=1){
		global $OPTION;
		
		$wpDir	= explode('/wp-content',WP_CONTENT_DIR);
		$dr 	= $wpDir[0];
		
		
		
		$pparts = count(explode("/",$dr));		
		
		$dir 	= array();
		$dir[0]	= ''; 
		$dir[1]	= '../'; 
		$dir[2]	= '../../'; 
		$dir[3]	= '../../../'; 
		$dir[4]	= '../../../../'; 
		$dir[5]	= '../../../../../'; 
		$dir[6]	= '../../../../../../'; 
		$dir[7]	= '../../../../../../../'; 
		$dir[8]	= '../../../../../../../../'; 
		$dir[9]	= '../../../../../../../../../'; 
		$dir[10]	= '../../../../../../../../../../'; 
		$dir[11]	= '../../../../../../../../../../../'; 
	
		$needle = 'masterdata/find_me.txt';
		$len1	= strlen('find_me.txt');
		
		for($i=0; $i< $pparts; $i++){
										
			$path	= $dir[$i].$needle;
				
			if(($fp = @fopen($path, "r")) === false){
				$feedback = 'Folder masterdata wasnt found. Change directory or contact theme author for support.';
			}
			else {
				$len2 		= strlen($path);
				$len		= $len2 - $len1;
				$start		= 0;
				
				
				if($option == 1){	// to fit echo at the theme options 
					$len 	= $len -3;
					$start	= 3;
				}
				
				$feedback 	= substr($path,$start,$len);		
				
				if($feedback{0} != '.'){
					$feedback = $feedback . " &nbsp;&nbsp; <span style='color: red;'>Your masterdata directory is at the wrong spot in your webspace 
								- Move it!</span>";
				}
				else {				
				
					// it was correctly configured in theme options we give additional positive feedback
					$saved_path = $OPTION['wps_master_dir'];
					if($feedback == $saved_path){
						$feedback = $feedback . " &nbsp;&nbsp; <span style='color: green;'>Correctly saved!</span>";			
					}
					else {
						$feedback = $feedback . " &nbsp;&nbsp; <span style='color: red;'>Not yet correctly saved!</span>";
					}
					
				$add1 = NULL;
				}
				break;
			}		
		}
return $feedback;	
}

function get_lmode(){

	global $OPTION;

	$l_mode		= $OPTION['wps_l_mode'];

return $l_mode;
}

function is_it_affiliate() {
	$len = strlen(get_custom_field('buy_now', FALSE));

	if($len > 0){
		$status = TRUE;
	}
	else{
		$status = FALSE;
	}
	return $status;
}

function is_it_digital($option='FE',$cid=0){

	switch($option){

		case 'FE':
			$len = strlen(get_custom_field('item_file', FALSE));

			if($len > 0){
				$status = TRUE;
			}
			else{
				$status = FALSE;
			}
		break;
		
		case 'BE':
			$table 	= is_dbtable_there('shopping_cart');
			$qStr 	= "SELECT item_file FROM $table WHERE cid = $cid LIMIT 1";				
			$res 	= mysql_query($qStr);
			$row	= mysql_fetch_assoc($res);

			if($row[item_file] != 'none'){
				$status = TRUE;
			}
			else{
				$status = FALSE;
			}			
			
		break;	
		
		case 'CART':  // this is at the moment exactly like BE-however in future there will/could be differences
			$table 	= is_dbtable_there('shopping_cart');
			$qStr 	= "SELECT item_file FROM $table WHERE cid = $cid LIMIT 1";				
			$res 	= mysql_query($qStr);
			$row	= mysql_fetch_assoc($res);

			if($row['item_file'] != 'none'){
				$status = TRUE;
			}
			else{
				$status = FALSE;
			}			
			
		break;	
		
		
		case 'UPDATE-CHECK': 
			
			$table 	= is_dbtable_there('shopping_cart');
			$qStr 	= "SELECT item_file FROM $table WHERE cid = $cid LIMIT 1";
			$res 	= mysql_query($qStr);
			$row	= mysql_fetch_assoc($res);
			
			if($row[item_file] != 'none'){
				$status = TRUE;
			}
			else{
				$status = FALSE;
			}			
			
		break;
	}

return $status;
}

function digital_in_cart($who){

	$table 	= is_dbtable_there('shopping_cart');
	$qStr 	= "SELECT * FROM $table WHERE who = '$who' AND item_file != 'none'";				
	$res 	= mysql_query($qStr);
	$num	= mysql_num_rows($res);


	if($num > 0){
		$status = TRUE;
	}
	else{
		$status = FALSE;
	}

return $status;
}

function cart_composition($who){
	/*
	$table 	= is_dbtable_there('shopping_cart');
	$qStr 	= "SELECT * FROM $table WHERE who = '$who'";
	$res 	= mysql_query($qStr);
	$num	= mysql_num_rows($res);
	$dgoods	= 0;
	
	while($row = mysql_fetch_assoc($res)){
	
		if($row['item_file'] != 'none'){
			$dgoods++;
		}	
	}
	
	if($num == $dgoods){
		$status = 'digi_only';
	}
	elseif(($num > $dgoods) && ($dgoods != 0)){
		$status = 'mixed';
	}
	elseif($dgoods == 0){
		$status = 'digi_none';
	}
	else{}
	*/
	$status = 'digi_none';
	
return $status;
}

function get_real_base_url($option='normal'){

global $OPTION;

	if($option == 'normal'){	
				
		if(($_SERVER['HTTPS'] == 'on')||($_SERVER['HTTPS'] == '1') || ($_SERVER['SSL'] == '1')){
			$protocol 	= 'https://';
		}
		elseif($_SERVER['HTTPS'] == 'off'){
			$protocol 	= 'http://';
		}
		elseif(empty($_SERVER['HTTPS'])){
			$protocol 	= 'http://';
		}
		else {}
				
	}
	if($option == 'force_ssl'){
		$protocol 	= 'https://';
	}
	if($option == 'force_http'){
		$protocol 	= 'http://';
	}
	$base_url	= parse_url(get_option('home'));
	$base_url	= $protocol.$base_url['host'].$_SERVER['REQUEST_URI'];
	

	// if with ? parameter? - remove ? + all what follows
	if((strpos($base_url,'?')) !== FALSE){
		$url = substr($base_url,0,strpos($base_url,'?'));
	}
	else {
		$url = $base_url;
	}

	// with an / ending?  and not in a subfolder = remove it  
	if(((substr($url,-1)) == '/') && (strlen(@$base_url['path']) > 1)){
		$url = substr($url,0,-1);
	}
	
	/*
	// with a / ending?  remove it  
	if((substr($url,-1)) == '/'){
		$url = substr($url,0,-1);
	}
	*/
		
return $url;
}

function expulsion_needed($url_addition = NULL){

	if(!$_POST){
		expulsion($url_addition);
	}
}

function expulsion($url_addition = NULL){

global $OPTION;

			$url = get_option('home').$url_addition ;
			
			echo "	
			<script type='text/javascript'> 
			<!--  
			location.href='$url';  
			//-->  
			</script>  
			<noscript>
			<meta http-equiv='refresh' content='0; url=$url'/>
			</noscript>
			";			
			exit(NULL);
}

function retrieve_address_data(){

			$table1 = is_dbtable_there('orders');
			$table2 = is_dbtable_there('delivery_addr');
		
			$sql1 	= "SELECT * FROM $table1 WHERE who = '$_SESSION[cust_id]' LIMIT 0,1";
			$sql2 	= "SELECT * FROM $table2 WHERE who = '$_SESSION[cust_id]' LIMIT 0,1";
		
			$res1 	= mysql_query($sql1);
			$res2 	= mysql_query($sql2);
		
			$row1 	= mysql_fetch_assoc($res1);
			$row2 	= mysql_fetch_assoc($res2);
					
			$_POST 	= array();
			
			foreach($row1 as $k => $v){
				if(!empty($v)){
					$_POST[$k] = $v;
				}
			}			
			
			foreach($row2 as $k => $v){
				
				if(!empty($v)){
					$a = $k.'|2';
					$_POST[$a] = $v; 
				}
			}
			
return $_POST;
}

function send_user_dlinks($who,$option='BE'){

	global $OPTION;
	
	$EMAIL 			= load_what_is_needed('email');			//change.9.10	
	$DIGITALGOODS 	= load_what_is_needed('digitalgoods');	//change.9.10
	
	// create links + save in db table 
	$table 	= is_dbtable_there('shopping_cart');
	$qStr 	= "SELECT * FROM $table WHERE who = '$who'";
	$res 	= mysql_query($qStr);


	$links				= array();
	$message_links 		= NULL;
	$domain1			= get_bloginfo('template_directory');
	$domain 			= substr($domain1, 7);  
	
	while($row = mysql_fetch_assoc($res)){ 
		
		$linkParam 				= $DIGITALGOODS->create_dlink($row[item_file],$who,2);	//change.9.10
		$links[$row[item_file]] = "$row[item_name]#$row[item_id]#$domain1/dl.php?dl=$linkParam&rd=$who";	
	}
	
	// get the email of customer
	$table 	= is_dbtable_there('orders');
	$qStr 	= "SELECT * FROM $table WHERE who = '$who' LIMIT 0,1";
	$res2 	= mysql_query($qStr);	
	$row2 	= mysql_fetch_assoc($res2);
	
	// send an email with links
		$to 					= $row2[email];
		$subject 				= __('Your download links','wpShop');	
		
	
		foreach($links as $k => $v){ 					
			$p					= explode("#",$v);
			$message_links		.= "<tr><td>$p[0]</td><td>$p[1]</td><td><a href='$p[2]'>Download $k</a></td></tr>";	
		}				
														
														
		if(strlen(WPLANG)< 1){  // shop runs in English 				
				$filename			= WP_CONTENT_DIR . '/themes/' . WPSHOP_THEME_NAME .'/email/email-download-links.html';
		}
		else {					// shops runs in another language 
				$filename 			= WP_CONTENT_DIR . '/themes/' .  WPSHOP_THEME_NAME .'/email/'.WPLANG.'-email-download-links.html';
		}
		
		$message		= file_get_contents($filename);
								
		$em_logo_path 	= get_bloginfo('template_directory') .'/images/logo/' . $OPTION['wps_email_logo'];	
		$message		= str_replace('[##Email-Logo##]', $em_logo_path, "$message");						
		$message		= str_replace('[##biz##]',$OPTION['wps_shop_name'], "$message");
		$cust_name		= $row2[f_name] .' '. $row2[l_name];
		$message		= str_replace('[##name##]', $cust_name, "$message");	

		// we tell the user about the duration time of his links
		if($OPTION['wps_duration_links'] > 3600){
			$hours			= $OPTION['wps_duration_links'] / 3600;
			$duration		= $hours .' '. __('hours',wpShop);
		}
		else{
			$minutes		= $OPTION['wps_duration_links'] / 60;
			$duration		= $minutes .' '. __('minutes',wpShop);
		}
		$message		= str_replace('[##duration##]',$duration, "$message");					
		$message		= str_replace('[##links##]', $message_links , "$message");	
		#$message		= str_replace('[##tracking-id##]', $order[tracking_id] , "$message");	
		

		$admin_email_address	= $OPTION['wps_shop_email'];
		$domain					= $OPTION['wps_shop_name']; 
		
		$EMAIL->html_mail($to,$subject,$message,$admin_email_address,$domain);		//change.9.10
		
		// enter the tstamp into orders db-table
		$table 	= is_dbtable_there('orders');
		$qStr 	= "UPDATE $table SET dlinks_sent = ".time()." WHERE who = '$_GET[token]'";			
		mysql_query($qStr);		
}

##################################################################################################################################
//												RELATED - PRODUCTS																			
##################################################################################################################################
function retrieve_related_products($related,$pids){

global $OPTION;

	// remove doubles
	$result = array_unique($related);
	// remove the shopping cart prods from the related prods array
	foreach($pids as $k => $v){				
		if(in_array($v,$result)== TRUE){
			$key = array_search($v,$result);
			unset($result[$key]);
		}
	}	

	//get related products data		
	foreach($result as $rcat){
		$GetPost 		= get_post($rcat); 
		$title_attr2 	= get_the_title($GetPost->ID);
		$title_attr		= str_replace("%s",get_the_title($GetPost->ID), __('Permalink to %s', 'wpShop'));
		$output 		= my_attachment_images($GetPost->ID,1);
		
		$imgNum 	= count($output);
		//do we have 1 attached image?
		if($imgNum != 0){
			$imgURL		= array();
			foreach($output as $v){
				$img_src 	= $v;
				$img_size 	= $OPTION['wps_ProdRelated_img_size'];
				$des_src 	= $OPTION['upload_path'].'/cache';							
				$img_file 	= mkthumb($img_src,$des_src,$img_size,'width');    
				$imgURL[] 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;				
			}
		// no attachments? pull image from custom field
		} elseif(strlen(get_custom_field2($GetPost->ID,'image_thumb', FALSE))>0) { 
			$img_src 	= get_custom_field2($GetPost->ID,'image_thumb', FALSE);
			$img_size 	= $OPTION['wps_ProdRelated_img_size'];
			$des_src 	= $OPTION['upload_path'].'/cache';							
			$img_file 	= mkthumb($img_src,$des_src,$img_size,'width');     
			$imgURL 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;
		}

		// put output together
		$permalink 	= get_permalink($GetPost->ID);
					
		
		// for attached images
		if($imgNum != 0){ 

			$result[html]	.= "
				<a href='$permalink' rel='bookmark' title='$title_attr'><img src='$imgURL[0]' alt='$title_attr2'/></a>";	
				
		// for  image from custom field
		} elseif(strlen(get_custom_field2($GetPost->ID,'image_thumb', FALSE))>0) {  
			$result[html]	.= "
				<a href='$permalink' rel='bookmark' title='$title_attr'><img src='$imgURL' alt='$title_attr2'/></a>";
			
		} else { 									
			$err_message 	= __('Oops! No Product Images were Found.','wpShop');									
			$result[html] 	= "<p title='$title_attr2' class='error'>$err_message</p>";
		} 
		
	} 

return $result['html'];
}

##################################################################################################################################
// 												ATTRIBUTE - MANAGMENT
##################################################################################################################################
function get_attribute_dropdown($post,$orderby,$order,$option=1,$basispr=10000.00){

	global $wpdb;

	$subfolder	= '/'.is_in_subfolder().'/';
	$num 		= 0;
	
	switch($option){
	
		case 1:
			$qStr 			= "SELECT * FROM $wpdb->postmeta WHERE post_id = $post AND meta_key LIKE 'item_attr_%' ORDER BY $orderby $order";
			$res 			= mysql_query($qStr);
			$num 			= mysql_num_rows($res); 
			$i				= 1;					
			$output 		= NULL; 	
			$choose_label	= __('Select','wpShop');

			while($row = mysql_fetch_assoc($res)){
				$mkey_p     = explode("_",$row[meta_key]);
				$value 		= get_custom_field($row[meta_key], FALSE);
				$parts 		= explode("|",$value);
											
				$output		.=  "<div class='prod_variation prod_select_drop_down'><label>$choose_label $mkey_p[2]:</label>";
				$output		.=  "<select name='$row[meta_key]' id='attr_{$i}' onchange='checkStock(this.value,$subfolder,$basispr,$num);' size='1'>\n";

				$output		.= "\n<option value='pch'>".__('Please Select','wpShop')."</option>\n";
				foreach($parts as $v){
					$output		.= "<option value='$v'>$v</option>\n";
				}
				$output		.= "</select></div>";
				$i++;
			}
		break;
	
		case 2:
			$qStr 			= "SELECT * FROM $wpdb->postmeta WHERE post_id = $post AND meta_key LIKE 'item_attr_%' ORDER BY $orderby $order";
			$res 			= mysql_query($qStr);
			$num 			= mysql_num_rows($res);
			$output 		= NULL;
			$i				= 1;
			$prefix 		= 'attr_';
			$choose_label	= __('Select','wpShop');

			while($row = mysql_fetch_assoc($res)){
				$mkey_p     = explode("_",$row[meta_key]);
				$value 		= get_custom_field($row[meta_key], FALSE); 	
				$parts 		= explode("|",$value);								
							
				$output		.= "<div class='prod_variation prod_select_drop_down'><label>$choose_label $mkey_p[2]:</label>";
				$output		.= "<select name='$row[meta_key]' id='attr_{$i}' onchange='checkStock(this.value,$subfolder,$basispr,$num);'>";

				$output		.= "\n<option value='pch'>".__('Please Select','wpShop')."</option>\n";
				foreach($parts as $v){
					
					$values 	= explode("-",$v);
					$attr		= $prefix.$i;
					$val 		= $values[1].'#'.$mkey_p[2].':'.$values[0];
					if($_SESSION[$attr] == $val){
						$selected = "selected='selected'";
					}
					else{
						$selected = NULL;
					}
					$output	.= "<option value='$values[1]#$mkey_p[2]=$values[0]' $selected>$values[0]</option>\n";
				}
				$output		.= "</select></div>";		
				
				$i++;
			}
		break;

	}

return $output; 
}

function has_attributes($post,$option=1){

	if($option == 1){
		$attr_option 	= get_post_meta($post->ID,'add_attributes',true);
	}
	else {
		$attr_option 	= get_post_meta($post,'add_attributes',true);
	}
	
	
	
	if($attr_option == '1' || $attr_option == '2'){
		$result = 'yes';
	}
	else{
		$result = 'no';
	}
	
	
	if($option != 1){
		$feedback 			= array();
		$feedback[status]	= $result;
		$feedback[attr_op]	= $attr_option;
		$result				= $feedback;
	}
	
return $result;	
}

function find_post_id($ID_item){
	
	global $wpdb;

	$qStr 		= "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'ID_item' AND meta_value = '$ID_item' LIMIT 0,1";
	$res 		= mysql_query($qStr);
	$row 		= mysql_fetch_assoc($res);
	$result		= $row[post_id];

return $result;
}

function retrieve_attributes($cid){

	$table 		= is_dbtable_there('shopping_cart');
	$qStr		= "SELECT item_attributs FROM $table WHERE cid='$cid' LIMIT 0,1";
	$res 		= mysql_query($qStr);	
	$row		= mysql_fetch_assoc($res);
	$attr		= $row[item_attributs];
	
return $attr;
}

##################################################################################################################################
// 												INQUIRIES - MANAGMENT
##################################################################################################################################
//change.9.9
function sent_inquiry_email($inq){

	global $OPTION;
	
	$EMAIL 	= load_what_is_needed('email');		//change.9.10

	// Email to shop owner	
	$to 					= $OPTION['wps_shop_email'];	
	$subject_str			= __('You received a new Enquiry from %FIRSTNAME% %LASTNAME%','wpShop');
	$subject				= str_replace('%FIRSTNAME%',$inq[f_name], "$subject_str");
	$subject				= str_replace('%LASTNAME%',$inq[l_name], "$subject");			
								
	if(strlen(WPLANG)< 1){  // shop runs in English 
		$filename			= WP_CONTENT_DIR.'/themes/'. WPSHOP_THEME_NAME .'/email/email-new-inquiry.html';
	}
	else {					// shops runs in another language - /*adjust*/
		$filename 			= WP_CONTENT_DIR.'/themes/'. WPSHOP_THEME_NAME .'/email/'.WPLANG.'-email-new-inquiry.html';
	}

	$message				= file_get_contents($filename);					
	$admin_email_address	= $OPTION['wps_shop_email'];
	$domain					= $OPTION['wps_shop_name'];   
					
	$em_logo_path 	= get_bloginfo('template_directory') .'/images/logo/' . $OPTION['wps_email_logo'];	
	$message		= str_replace('[##Email-Logo##]', $em_logo_path, "$message");						
	$message		= str_replace('[##biz##]',$OPTION['wps_shop_name'], "$message");
	$message		= str_replace('[##name##]', $inq['f_name'].' '.$inq['l_name'] , "$message");					
	$message		= str_replace('[##address##]', address_format($inq), "$message");	
	$message		= str_replace('[##email##]', $inq['email'], "$message");					
	$message		= str_replace('[##delivery##]', $inq['d_option'], "$message");
	$message		= str_replace('[##payment##]', $inq['p_option'], "$message");
	$message		= str_replace('[##remarks##]', $inq['custom_note'], "$message");						
	$message		= str_replace('[##home-url##]',substr(get_option('siteurl'),7), "$message");						
	$message		= str_replace('[##tracking-id##]', $inq['tracking_id'] , "$message");	

	if($inq['d_addr'] == '1'){
		$d_addr = address_format(retrieve_delivery_addr('FE'),'d-addr');
	}
	else {
		$d_addr  = ' -- '.__('same as billing address','wpShop').' -- ';
	}
	$message	= str_replace('[##delivery_address##]', $d_addr , "$message");	
	
	
	$CART 		= show_cart();
	if($CART[status] == 'filled'){
	$email_order = NULL;
		foreach($CART[content] as $v){
		
			$details = explode("|",$v);		

			$attributes 	= display_attributes($details[7]);
			$attributes 	= "<br/><span style='font-size: 0.8em; margin-left: 10px;'>" . $attributes . "</span>";	
			$personalize 	= (!empty($details[9]) ? "<br/>".$details[9] : NULL);								
			
			if(strlen(WPLANG) > 0){
				#$attributes = utf8_decode($attributes);	
				#$details[2] = utf8_decode($details[2]);	
			}
			$details[3] = format_price($details[3]);		
			$details[4] = format_price($details[4]);
			
			$email_order .= "
				<tr>
					<td>$details[5]</td
					<td>$details[2] $attributes $personalize</td>
					<td>$details[1]</td>
					<td>";
						$email_order .= format_price($details[3]);
					$email_order .= "</td>
					<td>";
						$email_order .= format_price($details[4]); 
					$email_order .= "</td>
				</tr>								
			";
			
		}
		
	$Subtotal 		= __('Subtotal:','wpShop');
	$Shipping_fee	= __('Shipping Fee','wpShop');
	$Total			= __('Total:','wpShop');	
	
	$CART['total_price']	= format_price($CART['total_price']);
	$inq['shipping_fee']	= format_price($inq['shipping_fee']);
	$inq['amount'] 			= format_price($inq['amount']);
		
	$email_order .= "	
		<tr>
			<td colspan='4' align='right'>
			<b>$Subtotal</b>
			</td>
			<td>$".$CART['total_price']."</td>
		</tr>
		<tr>
			<td colspan='4' align='right'>$Shipping_fee</td>
			<td>$".$inq['shipping_fee']."</td>
		</tr>";

 	
	$email_order .= "
		<tr>
			<td colspan='4' align='right'>
				<b>$Total</b><br/>";
				if ($OPTION['wps_tax_info_enable']){ 
					$email_order .= __('incl.','wpShop') . $OPTION['wps_tax_percentage'] . "% ".$OPTION['wps_tax_abbr'];
				}
			$email_order .= "</td>";
			$email_order .= "<td>";
			$email_order .= $inq['amount']; 							
			$email_order .= " " . $OPTION['wps_currency_code'];
			$email_order .= "</td>
		</tr>
		";
	}
	$message			= str_replace('[##order##]', $email_order , "$message");	
	$html_email_order	= $email_order; 											
	
	// get alternative txt for mime mail or for just txt mails /////////////////////////////////////////////////////////
	if(WPSHOP_EMAIL_FORMAT_OPTION == 'txt' || WPSHOP_EMAIL_FORMAT_OPTION == 'mime'){

		$filename  		= substr($filename,0,strrpos($filename,"."));
		$filename_txt 	= $filename.'.txt';
		$message_txt 	= file_get_contents($filename_txt);				
		
		
		if($CART[status] == 'filled'){
		$email_order = NULL;
			foreach($CART[content] as $v){
			
				$details = explode("|",$v);		

				$attributes 	= display_attributes($details[7]);
				
				$personalize 	= (!empty($details[9]) ? "\n".$details[9] : NULL);						
				
				if(strlen(WPLANG) > 0){
					#$attributes = utf8_decode($attributes);	
					#$details[2] = utf8_decode($details[2]);	
				}
				$details[3] = amount_format($details[3]);
				$details[4] = amount_format($details[4]);

				$email_order .= "$details[1] x $details[5] - $details[2] $attributes (".__('item price','wpShop').": $details[3] ". $OPTION['wps_currency_code'] . ") = $details[4] " . $OPTION['wps_currency_code']."\n\n";
						
			}

		$email_order .= "\n\n\n";
		$email_order .= "$Subtotal $CART[total_price] " . $OPTION['wps_currency_code']."\n";
		$email_order .= __('Shipping Fee','wpShop').": $inq[shipping_fee] " . $OPTION['wps_currency_code']."\n";
		$email_order .= "$Total $inq[amount] " . $OPTION['wps_currency_code']." (".__('incl.','wpShop').$OPTION['wps_tax_percentage'] ."% ". $OPTION['wps_tax_abbr'].")"."\n";

		}									
		
		$message_txt	= str_replace('[##biz##]', $OPTION['wps_shop_name'], "$message_txt");		
		$message_txt	= str_replace('[##name##]', $inq['f_name'].' '.$inq['l_name'] , "$message_txt");					
		$message_txt	= str_replace('[##address##]', address_format($inq), "$message_txt");	
		$message_txt	= str_replace('[##email##]', $inq['email'], "$message_txt");					
		$message_txt	= str_replace('[##delivery##]', $inq['d_option'], "$message_txt");
		$message_txt	= str_replace('[##payment##]', $inq['p_option'], "$message_txt");
		$message_txt	= str_replace('[##remarks##]', $inq['custom_note'], "$message_txt");						
		$message_txt	= str_replace('[##home-url##]',substr(get_option('siteurl'),7), "$message_txt");						
		$message_txt	= str_replace('[##tracking-id##]', $inq['tracking_id'] , "$message_txt");	
		$message_txt	= str_replace('[##order##]', $email_order , "$message_txt");						
		$message_txt	= str_replace('<br/>',' - ', "$message_txt");
	}
	switch(WPSHOP_EMAIL_FORMAT_OPTION){

		//change.9.10
		
		case 'mime':
			$EMAIL->mime_mail($to,$subject,$message,$message_txt,$admin_email_address,$domain,'zend'); // native	//change.9.10
		break;
		
		case 'txt':
			$EMAIL->send_mail($to,$subject,$message_txt,$admin_email_address,$domain);		//change.9.10
		break;
	}	
		
	// Email to customer				
	$subject				= __('Thank you for your Enquiry!','wpShop');				
	$to 					= $inq[email];
									
	if(strlen(WPLANG)< 1){  // shop runs in English 
		$filename			= WP_CONTENT_DIR.'/themes/'. WPSHOP_THEME_NAME .'/email/email-new-inquiry-thanks.html';
	}
	else {					// shops runs in another language - /*adjust*/
		$filename 			= WP_CONTENT_DIR.'/themes/'. WPSHOP_THEME_NAME .'/email/'.WPLANG.'-email-new-inquiry-thanks.html';
	}

	$message				= file_get_contents($filename);					
	$admin_email_address	= $OPTION['wps_shop_email'];
	$domain					= $OPTION['wps_shop_name']; 
	
	$em_logo_path 	= get_bloginfo('template_directory') .'/images/logo/' . $OPTION['wps_email_logo'];	
	$message		= str_replace('[##Email-Logo##]', $em_logo_path, "$message");						
	$message		= str_replace('[##biz##]',$OPTION['wps_shop_name'], "$message");			
	$message		= str_replace('[##name##]', $inq['f_name'].' '.$inq[l_name] , "$message");
	$message		= str_replace('[##email##]', $inq['email'], "$message");						
	$message		= str_replace('[##tracking-id##]', $inq['tracking_id'] , "$message");	
	$message		= str_replace('[##remarks##]', $inq['custom_note'], "$message");	
	$message		= str_replace('[##order##]', $html_email_order , "$message");
												
				
	// get alternative txt for mime mail or for just txt mails /////////////////////////////////////////////////////////
	if(WPSHOP_EMAIL_FORMAT_OPTION == 'txt' || WPSHOP_EMAIL_FORMAT_OPTION == 'mime'){

		$filename  		= substr($filename,0,strrpos($filename,"."));
		$filename_txt 	= $filename.'.txt';
		$message_txt 	= file_get_contents($filename_txt);	
		
		$message_txt	= str_replace('[##biz##]',$OPTION['wps_shop_name'], "$message_txt");		
		$message_txt	= str_replace('[##remarks##]', $inq['custom_note'], "$message_txt");						
		$message_txt	= str_replace('[##order##]', $email_order , "$message_txt");
		$message_txt	= str_replace('[##tracking-id##]', $inq['tracking_id'] , "$message_txt");	
		$message_txt	= str_replace('<br/>',' - ', "$message_txt");	
	}				

	switch(WPSHOP_EMAIL_FORMAT_OPTION){

		//change.9.10
		
		case 'mime':
			$EMAIL->mime_mail($to,$subject,$message,$message_txt,$admin_email_address,$domain,'zend'); // native	//change.9.10
		break;
		
		case 'txt':
			$EMAIL->send_mail($to,$subject,$message_txt,$admin_email_address,$domain);	//change.9.10
		break;
	}

	// update inquiry table - email was sent 				
	$table 					= is_dbtable_there('inquiries');
	$column_value_array 	= array();
	$where_conditions 		= array();
		
	$column_value_array[email_sent]		= 1;
	$where_conditions[0]				= "who = '$_SESSION[cust_id]'";
																				
	db_update($table, $column_value_array, $where_conditions);	

	// delete cust_id in session 
	unset($_SESSION['cust_id']);					
}
//\change.9.9


function adjust_add2cart_img(){

	global $OPTION;
	
	$shopMode 	= $OPTION['wps_shop_mode'];
	$pic 		= ($shopMode =='Inquiry email mode' ? 'add_inquiry.png':'add_to_cart.png' );
	
return $pic;
}


function process_inquiry(){

	// update orders table according to payment status
	$table 					= is_dbtable_there('inquiries');
	$column_value_array 	= array();
	$where_conditions 		= array();
	

	$column_value_array['amount'] 		= $_POST['amount'];
	$column_value_array['shipping_fee']	= $_POST['shipping'];
	$column_value_array['custom_note'] 	= $_POST['custom_note'];
	$column_value_array['tracking_id'] 	= 'I-'.time();
	$column_value_array['inquiry_time'] = time();
	$column_value_array['level'] 		= '4';
	
	$where_conditions[0]				= "who = '$_SESSION[cust_id]'";
																				
	db_update($table, $column_value_array, $where_conditions);	
			
	
	$qStr 		= "SELECT * FROM $table WHERE who = '$_SESSION[cust_id]' LIMIT 1";
	$res 		= mysql_query($qStr);
	$inquiry 	= mysql_fetch_assoc($res);
														
return $inquiry;
}


function check_inquiry_status($tid){

	$labels 		= array();
	$labels['4'] 	= __('Your Enquiry has been received and is being currently Processed.','wpShop');
	$labels['5'] 	= __('Your Enquiry was Replied to.','wpShop');
	
	$table 	= is_dbtable_there('inquiries');
	$qStr 	= "SELECT * FROM $table WHERE tracking_id = '$tid' LIMIT 1";
	$res 	= mysql_query($qStr);
	$row 	= mysql_fetch_assoc($res);	
	$status = $row[level];
	
	$feedback = $labels[$status];

return $feedback;
}

##################################################################################################################################
// 												FORMAT - MANAGMENT
##################################################################################################################################
function amount_format($amount){
	switch(WPLANG){
		case 'de_DE':
			$a 		= sprintf("%01.2f", $amount);
			$output = str_replace('.',',',$a);
		break;
		
		case 'fr_FR':
			$a 		= sprintf("%01.2f", $amount);
			$output = str_replace('.',',',$a);
		break;
		
		case 'it_IT':
			$a 		= sprintf("%01.2f", $amount);
			$output = str_replace('.',',',$a);
		break;

		default:
			$output = sprintf("%01.2f", $amount);
		break;
	}

return $output;
}

function get_address_format($d_country,$option = 0){
		
	// get the address format of the country
	$table 	= is_dbtable_there('countries');
	if((WPLANG == 'de_DE')||(WPLANG == 'fr_FR')){
		$col	= substr(WPLANG,0,2);
	}
	else {
		$col	= 'country';
	}
	$qStr 	= "SELECT address_format FROM $table WHERE $col = '$d_country' LIMIT 0,1";
	$res 	= mysql_query($qStr);
	$row 	= mysql_fetch_assoc($res);
	
	// make all strings to lower case 
	$ad		= strtolower($row['address_format']);

	if($option === 'filter'){
		$arr 	= explode("%",str_replace('#',NULL,$ad));

		foreach($arr as $k => $v){
			if((strlen($v)<2) || ($v == 'name') || ($v == 'country')){
				unset($arr[$k]);
			}
		}
		$new = array_merge(array(),$arr);	

		return $new;
	}
	else {
		return $ad;
	}
}

function address_format($ad,$option='html',$pdf=0){

	global $OPTION;

	$address = NULL;
	
	// get format string for country from DB 
	if($OPTION['wps_shop_mode']=='Inquiry email mode'){
		$table1 	= is_dbtable_there('inquiries');
	}
	else {
		$table1 	= is_dbtable_there('orders');
	}
	
		$table2 	= is_dbtable_there('countries');
		
	
	switch(WPLANG){
		case 'de_DE':
			$where_col = $table2 . '.de'; 
		break;

		case 'fr_FR':
			$where_col = $table2 . '.fr';
		break;
		
		default:
			$where_col = $table2 . '.country';
		break;
	}

	// BE  - needs slightly different query
	if(strpos(dirname($_SERVER['PHP_SELF']),'/wp-admin') !== FALSE) {
		$oid	= (int) $ad[oid];
		$qStr 	= "SELECT address_format FROM $table1,$table2 WHERE $table1.country = $where_col AND $table1.oid = $oid LIMIT 0,1";	
		
		if($option == 'd-addr'){
			$table3 = is_dbtable_there('delivery_addr');
			$aid	= (int) $ad[aid];
			$qStr 	= "SELECT address_format FROM $table3,$table2 WHERE $table3.country = $where_col AND $table3.aid = $aid LIMIT 0,1";	
		}
		
		
	}
	else{ // FE
		if($option == 'bill_header'){ // this is for the pdf bill header
		
			$where_col 		= $table2 . '.abbr';
			$shop_country	= $OPTION['wps_shop_country'];
			$qStr 			= "SELECT address_format FROM $table2 WHERE abbr = '$shop_country' LIMIT 0,1";									
		}
		elseif($option == 'pdf_cust_address'){
			$qStr 	= "SELECT address_format FROM $table1,$table2 
						WHERE $table1.country = $where_col AND $table1.who = '$ad[who]' LIMIT 0,1";			
		}
	
		elseif($option == 'd-addr'){
			$table3 = is_dbtable_there('delivery_addr');
			$qStr 	= "SELECT address_format FROM $table2,$table3 
						WHERE $table3.country = $where_col AND $table3.who = '$_SESSION[cust_id]' LIMIT 0,1";	
		}
		else {
			
			$who_custId = ($ad['p_option'] == 'paypal' ? $ad['who'] : $_SESSION['cust_id']);
			$qStr 		= "SELECT address_format FROM $table1,$table2 WHERE $table1.country = $where_col AND $table1.who = '$who_custId' LIMIT 0,1";	
			
		}
	}
	$res 	= mysql_query($qStr);
	$row 	= mysql_fetch_assoc($res);
	
	// replace tokens with data - we have a html and pdf option
	$address 	= str_replace("#","<br/>",$row['address_format']);
	
	$name		= $ad['f_name'] . ' ' . $ad['l_name'];
	if (strpos($address,'NAME') !== false) {
			$address 	= str_replace("NAME",strtoupper($name),$address);
	}
	if (strpos($address,'name') !== false) {
			$address 	= str_replace("name",$name,$address);
	}
	
	$address = address_token_replacer($address,'%STREET%',$ad);
	$address = address_token_replacer($address,'%HSNO%',$ad);
	$address = address_token_replacer($address,'%STRNO%',$ad);
	$address = address_token_replacer($address,'%STRNAM%',$ad);
	$address = address_token_replacer($address,'%PB%',$ad);
	$address = address_token_replacer($address,'%PO%',$ad);	
	$address = address_token_replacer($address,'%PZONE%',$ad);
	$address = address_token_replacer($address,'%CROSSSTR%',$ad);		
	$address = address_token_replacer($address,'%COLONYN%',$ad);
	$address = address_token_replacer($address,'%DISTRICT%',$ad);
	$address = address_token_replacer($address,'%REGION%',$ad);	
	$address = address_token_replacer($address,'%PLACE%',$ad);
	$address = address_token_replacer($address,'%STATE%',$ad);
	$address = address_token_replacer($address,'%ZIP%',$ad);
	$address = address_token_replacer($address,'%COUNTRY%',$ad);
	
	
	if($option == 'pdf_delivery_address'){	
	
		$table3 	= is_dbtable_there('delivery_addr');
		$who_custId = ($ad['p_option'] == 'paypal' ? $ad['who'] : $_SESSION['cust_id']);
		$qStr 		= "SELECT * FROM $table2,$table3 WHERE $table3.country = $where_col AND $table3.who = '$who_custId' LIMIT 0,1";		
		$res 		= mysql_query($qStr);
		$dAd		= mysql_fetch_assoc($res);
	
		
		$addressD 	= str_replace("#","<br/>",$dAd[address_format]);
		
		$name		= $dAd[f_name] . ' ' . $dAd[l_name];
		if (strpos($addressD,'NAME') !== false) {
				$addressD 	= str_replace("NAME",strtoupper($name),$addressD);
		}
		if (strpos($addressD,'name') !== false) {
				$addressD 	= str_replace("name",$name,$addressD);
		}
		
		$addressD = address_token_replacer($addressD,'%STREET%',$dAd);
		$addressD = address_token_replacer($addressD,'%HSNO%',$dAd);
		$addressD = address_token_replacer($addressD,'%STRNO%',$dAd);
		$addressD = address_token_replacer($addressD,'%STRNAM%',$dAd);
		$addressD = address_token_replacer($addressD,'%PB%',$dAd);
		$addressD = address_token_replacer($addressD,'%PO%',$dAd);
		$addressD = address_token_replacer($addressD,'%PZONE%',$dAd);
		$addressD = address_token_replacer($addressD,'%CROSSSTR%',$dAd);		
		$addressD = address_token_replacer($addressD,'%COLONYN%',$dAd);
		$addressD = address_token_replacer($addressD,'%DISTRICT%',$dAd);
		$addressD = address_token_replacer($addressD,'%REGION%',$dAd);	
		$addressD = address_token_replacer($addressD,'%PLACE%',$dAd);
		$addressD = address_token_replacer($addressD,'%STATE%',$dAd);
		$addressD = address_token_replacer($addressD,'%ZIP%',$dAd);
		$addressD = address_token_replacer($addressD,'%COUNTRY%',$dAd);
	}
	
	
	// pdf should be produced? we process the html first and simply replace <br/>
	if($option == 'pdf' || $option == 'pdf_cust_address'){		
		$addr_parts = explode("<br/>",$address);
		
		foreach($addr_parts as $p){
			$pdf->Cell(0,6,utf8_decode($p),0,1);		
		}
	}
	
	
	if($option == 'pdf_delivery_address'){		

		$addr_parts 	= explode("<br/>",$address);
		$addrD_parts 	= explode("<br/>",$addressD);
		
		// we count the differnt arrays to determine if the address have a differnt amount of lines
		$num1 			= count($addr_parts);
		$num2 			= count($addrD_parts);
		
		$pdf->SetFont('Arial','I',8);
		
		$pdf->Cell(50,6,__('Billing address:','wpShop'),'R',0);	
		$pdf->Cell(5,6,NULL,0,0);	
		$pdf->Cell(50,6,__('Delivery address:','wpShop'),0,1);	
		
		$pdf->SetFont('Arial','',10);
		
		foreach($addr_parts as $key => $p){
			
			$d = $addrD_parts[$key];
		
			$pdf->Cell(50,6,utf8_decode($p),'R',0);	
			$pdf->Cell(5,6,NULL,0,0);	
			$pdf->Cell(50,6,utf8_decode($d),0,1);	
		}
		// yes, the delivery address has more line
		if($num2 > $num1){
		
			$diff = $num2 - $num1;
			$key++;
			for($i=0,$j=$key;$i<$diff;$i++,$j++){
			
				$d = $addrD_parts[$key];
				
				$pdf->Cell(50,6,NULL,'R',0);	
				$pdf->Cell(5,6,NULL,0,0);	
				$pdf->Cell(50,6,utf8_decode($d),0,1);				
			}
		}
	}	
	
		
return $address;
}


function address_token_replacer($address,$needle,$replace){
	$needle_lower 	= strtolower($needle);
	$key 			= $needle_lower;
	$key 			= substr(substr($key, 1), 0, -1);	//remove % form end and beginning
 
	if(($needle == '%PLACE%') || ($needle == '%place%')){
		$key = 'town';
	}

	if (stripos($address,$needle) !== false) {	
		if(strpos($address,$needle) !== false){
				
				if(extension_loaded('mbstring')){
					$address = str_replace($needle,mb_strtoupper($replace["$key"]),$address);
				}
				else {
					$address = str_replace($needle,strtoupper($replace["$key"]),$address);
				}
				
		}
		else{
			$address = str_replace($needle_lower,$replace["$key"],$address);
		}
	}	
return $address;
}

function province_me($ct,$sel=1,$option='billing'){
	
	$table 	= is_dbtable_there('countries');
	$sql 	= "SELECT states FROM $table WHERE abbr = '$ct' LIMIT 0,1";
	$res	= mysql_query($sql);
	$row	= mysql_fetch_assoc($res);
	
	$arr 	= explode('#',$row['states']);
	
	if($option == 'billing'){
		$data 	= "<select id='statelist' name='state' size='1' title='State selection'>";	
	}
	if($option == 'delivery'){	
		$data 	= "<select id='statelist' name='state|2' size='1' title='State selection'>";
	}
	
	foreach($arr as $v){
	
		$kv 		= explode('|',$v);
		$selected 	= ($kv[0] == $sel ? 'selected="selected"' : NULL);

		$data .= "<option value='$kv[0]' {$selected}>$kv[1]</option>";
	}
	
	$data .= "</select><br />";
	
return $data;
}

function display_state_list($d_country){

	$result = FALSE;
	$table 	= is_dbtable_there('countries');
	$sql 	= "SELECT display_state_list FROM $table WHERE abbr = '$d_country'";
	$res	= mysql_query($sql);
	$row	= mysql_fetch_assoc($res);

	if($row['display_state_list'] == 1){
		$result = TRUE;
	}

return $result;
}

##################################################################################################################################
// 												DELIVERY-ADDRESS
##################################################################################################################################
function retrieve_delivery_addr($option='FE',$order=1)
{
	$table 	= is_dbtable_there('delivery_addr');
	if($option == 'FE'){
		$sql	= "SELECT * FROM $table WHERE who = '$_SESSION[cust_id]' LIMIT 0,1";
		$res	= mysql_query($sql);
		$row 	= mysql_fetch_assoc($res);
	}
	else {
		$sql	= "SELECT * FROM $table WHERE who = '$order[who]' LIMIT 0,1";
		$res	= mysql_query($sql);
		$row 	= mysql_fetch_assoc($res);
	}
return $row; 
}

function diversity_check($order,$billing,$option='FE'){

	$fields 	= 'l_name|f_name|street|hsno|strno|strnam|po|pb|pzone|crossstr|colonyn|district|region|state|zip|town|country';
	$diverse	= FALSE;
	
	
	// BE needs a bit a different handling	
	if($option == 'BE'){
		$table 	= is_dbtable_there('delivery_addr');
		$sql	= "SELECT * FROM $table WHERE who = '$order[who]' LIMIT 0,1";
		$res	= mysql_query($sql);
		$billing= mysql_fetch_assoc($res);
	}	
	
	
	foreach($order as $k => $v){
		if((strpos($fields,$k) !== FALSE) && (!empty($order[$k]))){
		
			$order[$k] 		= str_replace(' ', '',$order[$k]);
			$billing[$k] 	= str_replace(' ', '',$billing[$k]);
		
			if($order[$k] != $billing[$k]){			
				$diverse = TRUE;
			}		
		}
	}
return $diverse; 
}

##################################################################################################################################
// 												ERROR - HANDLING
##################################################################################################################################
//change.9.10
function error_explanation($errNo,$withDiv='no'){

	include WP_CONTENT_DIR . '/themes/'.WPSHOP_THEME_NAME.'/lib/static-info/errors/error_details.php';		

	if($withDiv == 'no'){
		$explanation = $ERROR_DETAILS[$errNo];
	}
	else {
		$explanation = "<div class='error_explanation' style='color: green; background: lime;'>";
		$explanation .= $ERROR_DETAILS[$errNo];
		$explanation .= "</div>";
	}
	
return $explanation;
}
//\change.9.10


##################################################################################################################################
// 												STOCK-CONTROL
##################################################################################################################################

function attr_sql_addition($attr,$option=1){
	$at = '';
	if(strlen($attr)){
		
		switch($option){
			
			case 1:
				$parts = explode("#",$attr);
				foreach($parts as $v){
					$p = explode("=",$v);
					$at .= ' AND ' . strtolower($p[0]) . ' = ' . "'$p[1]'";
				}			
			break;
			
			case 2:
				$at .= " AND item_attributs = '$attr'";
			break;
		}
	}
	
	return $at;
}

function all_gone($pid){
	
	$ID_item 	= postid_2_IDitem($pid);
	
	$table 		= is_dbtable_there('inventory');
	$qStr 		= "SELECT sum(amount) FROM $table WHERE ID_item = '$ID_item'";
	$res 		= mysql_query($qStr);
	$erg		= (int) mysql_result($res,0,0);
		
	$feedback 	= ($erg == 0 ? TRUE : FALSE);
	
return $feedback;
}

function inventory_notifier(){

	global $OPTION;

	// how many items are now on 0 ? 
	$table	= is_dbtable_there('inventory');
	$sql	= "SELECT * FROM $table WHERE amount = '0' ORDER BY ID_item";
	$res 	= mysql_query($sql);
	$num 	= mysql_num_rows($res);
	
	
	if($num > 0){
		$notification = "The amount of the following article is on 0: \n\n";
			while($row = mysql_fetch_assoc($res)){

				$notification .= $row['ID_item'].' : ';
				
				unset($row['iid']);
				unset($row['amount']);
				unset($row['ID_item']);

					foreach($row as $k => $v){
						$notification .= ' '.$k.': '.$v.' - ';
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
		/*
		$to 			= $OPTION['wps_stock_warn_email'];
		$from 			= $OPTION['wps_shop_email'];
		$shopname		= $OPTION['wps_shop_name'];
		$subject 		= 'Inventory notification';
		$text 			= $notification;
		mail($to,$subject,$text,"From: $shopname <$from>");
		*/
}

function postid_2_IDitem($post_id){

	global $wpdb;

	$qStr 	= "SELECT meta_value FROM $wpdb->postmeta WHERE post_id = '$post_id' AND meta_key = 'ID_item' LIMIT 0,1";					
	$res	= mysql_query($qStr);
	$row	= mysql_fetch_assoc($res);
	$ID_item= $row[meta_value];

return $ID_item;
}

##################################################################################################################################
// 												GOOGLE  
##################################################################################################################################
function google_analytics($profile_id='1111'){

$code = "
	<script type=\"text/javascript\">
	var gaJsHost = ((\"https:\" == document.location.protocol) ? \"https://ssl.\" : \"http://www.\");
	document.write(unescape(\"%3Cscript src='\" + gaJsHost + \"google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E\"));
	</script>
	<script type=\"text/javascript\">
	try {
	var pageTracker = _gat._getTracker(\"$profile_id\");
	pageTracker._trackPageview();
	} catch(err) {}</script>
";

return $code ;
}

function google_adsense($ad_no){

	echo stripslashes(get_option('wps_google_adsense_'.$ad_no));

}

##################################################################################################################################
// 												SESSION - HANDLING 
##################################################################################################################################
function custid_used_for_order(){

		if(!isset($_SESSION['cust_id'])){$_SESSION['cust_id'] = NULL;}
		$table 	= is_dbtable_there('orders');
		$qStr 	= "SELECT oid FROM $table WHERE who = '$_SESSION[cust_id]' AND level IN ('0','2','3','4','5','6','7','8','9')";
		$res 	= mysql_query($qStr);
		$num 	= mysql_num_rows($res);
		
		$result = ($num != 0 ? TRUE : FALSE);
		
	return $result;
	}

##################################################################################################################################
// 												ENCODING 
##################################################################################################################################
function utf2latin($text)
{ 
		$text=htmlentities($text,ENT_COMPAT,'UTF-8'); 
return html_entity_decode($text,ENT_COMPAT,'ISO-8859-1'); 
} 


function encode_email_subject($subject){		
	
	switch(WPLANG){
		
		case 'ja':
			mb_language("ja");
			$subject = mb_convert_encoding($subject,"ISO-2022-JP","AUTO");
			$subject = base64_encode($subject); 
			// Add the encoding markers to the subject 
			$subject = "=?ISO-2022-JP?B?" . $subject . "?=";
		break;
	
		default:
			if(extension_loaded('mbstring')){ 
				$subject = mb_convert_encoding($subject,"iso-8859-1","auto");	
			} 
		
			// Now, base64 encode the subject 
			$subject 	= base64_encode($subject); 
			// Add the encoding markers to the subject 
			$subject 	= "=?iso-8859-1?B?" . $subject . "?=";		
		break;
	}
	
return $subject;
}



function pdf_encode($data){
		
		if(extension_loaded('mbstring')){ 
			$data = mb_convert_encoding($data,"iso-8859-1","auto");	
		}
		// utf8_decode() might be also interesting...
		
		$data = stripslashes($data);
	
return $data;
}


function utf8_decode_custom($value){
	
	switch(WPLANG){
	
		case 'he_IL':
			$result = $value;
		break;
		case 'ja':
			$result = $value;
		break;
		default:
			$result = utf8_decode($value);
		break;
	}

return $result;
}


function pdf_usable_language(){

	$feedback 		= FALSE;
	
	$language_arr 	= array();
	$language_arr[]	= '';
	$language_arr[]	= 'de_DE';
	$language_arr[]	= 'it_IT';
	$language_arr[]	= 'fr_FR';
	$language_arr[]	= 'es_ES';
	$language_arr[]	= 'nl_NL';
	$language_arr[]	= 'en_US';
	$language_arr[]	= 'en_UK';
	$language_arr[]	= 'en_ZA';
	#$language_arr[]	= '';		//add next language here
		
	if (in_array(WPLANG,$language_arr)){
		$feedback = TRUE;
	}

return $feedback;
}


##################################################################################################################################
// 												MEMBERSHIP
##################################################################################################################################
function clean_data($getPOST){
	if (is_array($_POST)) {
	     foreach ($_POST as $k => $v) {
			$POST[$k] = htmlentities(strip_tags(stripslashes($v)));
			$POST[$k] = addslashes($POST[$k]);
	     }
	}
return $POST;
}

function validate_user_email($email,$option=1){
	
		$valid = FALSE;
		if($option == 2){
				$valid = 'invalid';
		}
		
	    if(eregi("^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,6}$", $email))
		{
		     $valid = TRUE;
			 
			 if($option == 2){
				$valid = 'valid';
			 }
		}
	 
	return $valid;
	}

function generateRandomString($length = 6, $letters = '1234567890qwertyuiopasdfghjklzxcvbnm'){
	  $s = '';
	  $lettersLength = strlen($letters)-1;
	 
	  for($i = 0 ; $i < $length ; $i++)
	  {
	  $s .= $letters[rand(0,$lettersLength)];
	  }
	 
return $s;
}	

function auth($option=0){
	global $OPTION;

	$accountLog				= get_page_by_title($OPTION['wps_pgNavi_logOption']);
	$hostname 				= $_SERVER['HTTP_HOST'];
	$path 					= dirname($_SERVER['PHP_SELF']);
	$timenow       			= time();
	$browser_ver			= get_browser_version();       
	$authorized 			= 1;
	$throw_out				= $option;

	 if(!isset($_SESSION['user_logged'])){
		$authorized = 0;
	 }

	 if($_SESSION['user_logged'] != true){
		$authorized = 0;
	 }

	 if($timenow > $_SESSION['timeout']){
		$authorized = 0;
	 }

	 if($_SESSION['browser'] != $browser_ver){
		$authorized = 0;
	 }


	 if ($authorized == 0){
		unset_user_session(); 
		
		if($throw_out == 1)
		{
			$url = get_option('siteurl').'/'.$accountLog->post_name.'';
			header("Location: $url");
			exit(NULL);
		}		   
	  }

return $authorized;
}
	
function get_browser_version($option=1){
	if($option == 0)
	{
	$browser_ver	= strtolower($_SERVER['HTTP_USER_AGENT']); 
	}
	
	if($option == 1)
	{
	$browser_ver	= md5(strtolower($_SERVER['HTTP_USER_AGENT'])); 
	}
return $browser_ver; 
}

function unset_user_session(){
	$_SESSION[user_logged] = false;
	unset($_SESSION[username]);
	unset($_SESSION[timeout]);
	unset($_SESSION[browser]);
}

function logout(){	
	unset_user_session();
	
	$hostname 	= $_SERVER['HTTP_HOST'];
	$path 		= dirname($_SERVER['PHP_SELF']);
	header('Location: http://'.$hostname.($path == '/' ? '' : $path).'/'.$this_page);
	exit();
 }

function is_in_subfolder(){

	global $OPTION;

	$subfolder  = substr(strstr(get_option('siteurl'),$_SERVER['HTTP_HOST']),strlen($_SERVER['HTTP_HOST']));
	$result  	= (strlen($subfolder) > 0 ? $subfolder : 'none');
	 
	if($result[0] == "/"){
	   $result = substr($result,1);
	   
	   if(substr_count($result,"/")>0){
			$result = str_replace("/","##slash##",$result);
	   }
	}
	
return $result;
} 
 
function get_member_billing_addr($option=1){

	$table 	= is_dbtable_there('feusers');
	$sql 	= "SELECT * FROM $table WHERE uid = '$_SESSION[uid]' LIMIT 0,1";
	$res 	= mysql_query($sql);

	if($option == 1){
		$row 	= mysql_fetch_assoc($res);
	}
	if($option == 2){
		$row 	= mysql_fetch_assoc($res);
	if (!$row || empty($row)) return FALSE;
		foreach($row as $k => $v){
		
			if(!empty($v)){
				$_POST[$k] = $v;
			}
		}
		$_POST['f_name'] = $_POST['fname'];
		$_POST['l_name'] = $_POST['lname'];
	}		

return $row; 
}
 
function NWS_get_user_details(){

	$table 	= is_dbtable_there('feusers');
	$qStr 	= "SELECT * FROM $table WHERE uid = $_SESSION[uid] LIMIT 0,1";
	$res 	= mysql_query($qStr);
	$row 	= mysql_fetch_assoc($res); 
	
return $row;
} 
 

	
##################################################################################################################################
// 												SSL
##################################################################################################################################
function adjust2ssl($val){
	if($_SERVER['HTTPS'] == 'on' || $_SERVER['SSL'] == '1'){
		$val = preg_replace('|/+$|', '', $val);
		$val = preg_replace('|http://|', 'https://', $val);
	}
return $val;
}	


function NWS_bloginfo($option,$echo = 'no'){

	$val = get_bloginfo($option); 
	if($_SERVER['HTTPS'] == 'on' || $_SERVER['SSL'] == '1') {
		$val = preg_replace('|/+$|', '', $val);
		$val = preg_replace('|http://|', 'https://', $val);
	}	

	if($echo == 'yes'){
		echo $val;
	}
	else {
		return $val;
	}
}


function get_protocol(){

	if(($_SERVER['HTTPS'] == 'on')||($_SERVER['HTTPS'] == '1') || ($_SERVER['SSL'] == '1')){
		$protocol 	= 'https://';
	}
	elseif($_SERVER['HTTPS'] == 'off'){
		$protocol 	= 'http://';
	}
	elseif(empty($_SERVER['HTTPS'])){
		$protocol 	= 'http://';
	}
	else {}
	
return $protocol;
}


##################################################################################################################################
// 												PERSONALIZATION
##################################################################################################################################
function get_item_personalization($post,$orderby,$order){

	global $wpdb;
	
	
	$qStr 	= "SELECT * FROM $wpdb->postmeta WHERE post_id = $post AND meta_key LIKE 'item_pers_%' ORDER BY $orderby $order";
	$res 	= mysql_query($qStr);
	$i		= 1;
	$output = NULL; 	
	
	while($row = mysql_fetch_assoc($res)){
		$mkey_p     = explode("_",$row[meta_key]);
		$value 		= get_custom_field($row[meta_key], FALSE);
		$output		.=  "<label for='item_pers_{$i}'>$value</label>";
		switch($mkey_p[2]){
			case 'single':
			$output		.=  "<input class='pers_text' type='text' id='item_pers_{$i}' name='item_pers_{$i}' />";
			$output		.=  "<input type='hidden' id='item_pers_hid_{$i}' name='personalize_label_{$i}' value='$value' />";
			break;
			
			case 'multi':
				$output		.=  "<textarea class='pers_textarea' rows='8' cols='30' id='item_pers_{$i}' name='item_pers_{$i}' >";
				$output		.= "</textarea>";
				$output		.=  "<input type='hidden' id='item_pers_hid_{$i}' name='personalize_label_{$i}' value='$value' />";
			break;
		}
		
		$i++;
	}
	return $output; 
}

function has_personalization($post){
	global $wpdb;

	$qStr 	= "SELECT * FROM $wpdb->postmeta WHERE post_id = $post AND meta_key LIKE 'item_pers_%'";
	$res 	= mysql_query($qStr);
	$num 	= mysql_num_rows($res);
	
	$result = ($num > 0 ? $result=TRUE : $result=FALSE);
	
	return $result;	
}

function personalization_chksum(){

	$chk	= NULL;
	
	foreach($_POST as $k => $v){

		if((strpos($k,'item_pers_') !== false)&&(!empty($v))){
				$parts 		= explode("_",$k);
				$labelKey	= 'personalize_label_'.$parts[2];
				
				$chk .= $k.$_POST[$labelKey].$v;
		}		
		$i++;
	}
	
	$chksum = ($chk != NULL ? md5($chk) : 'no');
	
return $chksum;
}

function save_personalization($row){

	$table	= is_dbtable_there('personalize');
	$cid	= $row['cid'];							

	foreach($_POST as $k => $v){

		if((strpos($k,'item_pers_') !== false)&&(!empty($v))){
				$parts 		= explode("_",$k);
				$labelKey	= 'personalize_label_'.$parts[2];

				$column_array 		= array();
				$value_array 		= array();

				$column_array[0]	= 'pers_id';		$value_array[0] = '';
				$column_array[1]	= 'cid';			$value_array[1] = $cid;
				$column_array[2]	= 'pers_name';		$value_array[2] = $k;
				$column_array[3]	= 'pers_label';		$value_array[3] = $_POST[$labelKey];
				$column_array[4]	= 'pers_value';		$value_array[4] = $v;
					
				db_insert($table,$column_array,$value_array);		
		}		
	}
	
return $cid;
}

function update_personalization($oldVal,$newVal){

	// when moving an item from cart to wishlist the cid value needs to be updated + vice versa
	$table	= is_dbtable_there('personalize');
		
	$qStr = "UPDATE $table 
				SET cid = $newVal
			WHERE 
				cid = $oldVal";

	mysql_query($qStr);
	
return $newVal;
}

function retrieve_personalization($cid,$mode='html'){  	

	$personalize 	= NULL;
	$table			= is_dbtable_there('personalize');	
	$sql 			= "SELECT * FROM $table WHERE cid = $cid ORDER BY pers_name";
	$res			= mysql_query($sql);
	$num 			= mysql_num_rows($res);
	
	switch($mode){
	
		case 'html':
			if($num > 0){
				while($data = mysql_fetch_assoc($res)){			
					$personalize .= $data['pers_label'] .":<br/>". $data['pers_value']."<br/>";
				}
			}
		break;

		case 'txt_mail':
			if($num > 0){
				while($data = mysql_fetch_assoc($res)){			
					$personalize .= $data['pers_label'] .': '. $data['pers_value']."\n";
				}
			}
		break;
		
		case 'pdf':
			if($num > 0){
				$personalize 	= array();
				$i				= 0;
				while($data = mysql_fetch_assoc($res)){			
					$personalize[$i] = $data['pers_label'] .': '. $data['pers_value'];
					$i++;
				}
			}
			else {
				$personalize = 'none';
			}
		break;
	}
return $personalize;
}

##################################################################################################################################
// 												ENCRYPTION - DECRYPTION
##################################################################################################################################
function NWS_encode($string){ 

	$key 	= get_option('wps_paypal_encode_key');
	$key 	= sha1($key);
    $strLen = strlen($string);
    $keyLen = strlen($key);
	$hash = '';
	$j = 0;
    for ($i = 0; $i < $strLen; $i++){
        $ordStr = ord(substr($string,$i,1));
        if ($j == $keyLen) { $j = 0; }
        $ordKey = ord(substr($key,$j,1));
        $j++;
        $hash .= strrev(base_convert(dechex($ordStr + $ordKey),16,36));
    }
	
return $hash;
}

function NWS_decode($string){

	$key 	= get_option('wps_paypal_encode_key');
    $key 	= sha1($key);
    $strLen = strlen($string);
    $keyLen = strlen($key);
	
    for ($i = 0; $i < $strLen; $i+=2) {
        $ordStr = hexdec(base_convert(strrev(substr($string,$i,2)),36,16));
        if ($j == $keyLen) { $j = 0; }
        $ordKey = ord(substr($key,$j,1));
        $j++;
        $hash .= chr($ordStr - $ordKey);
    }
	
return $hash;
}

##################################################################################################################################
// 												PRICE - FORMAT
##################################################################################################################################
function format_price($price, $ccode = false){
	global $OPTION;

	if ($price) {
		switch($OPTION['wps_price_format']){
		
			case '1':
				$price = number_format($price, 2, ',', '');
			break;		
			
			case '2':
				$price = number_format($price, 2, '.', '');
			break;		
			
			case '3':
				$price = number_format($price, 2, ',', '.');
			break;		
			
			case '4':
				$price = number_format($price, 2, '.', ',');
			break;		
			
			case '5':
				$price = number_format($price, 2, ',', ' ');
			break;		
			
			case '6':
				$price = number_format($price, 2, ',', "'");
			break;
			
			case '7':
				$price = number_format($price, 0, ',',' ');
			break;
			
			case '8':
				$price = number_format($price, 0, '.', ',');
			break;		
			
			case '9':
				$price = number_format($price, 0);
			break;
			
			default:
			break;
		}
		if ($ccode) {
			if ($_SESSION['currency-code'] != 'USD') {
				$price = $price . ' ' . $_SESSION['currency-code'];
			} else {
				$price = '$'.$price;
			}
		}
	}
	
	return $price;
}

##################################################################################################################################
// 												TRANSLATE MONTHS
##################################################################################################################################

function NWS_translate_date(){
	
	global $OPTION;
	
	$en_langs 	= array();
	$en_langs[] = 'en_GB';
	$en_langs[] = 'en_US';
	$en_langs[] = 'en_AU';
	$en_langs[] = 'en_CA';
	$en_langs[] = 'en_IE';
	$en_langs[] = 'en_NZ';
	$en_langs[] = 'en_PH';
	$en_langs[] = 'en_ZA';
	$en_langs[] = '';
		
	if(in_array(WPLANG,$en_langs) === FALSE){
	
		$monthEN 					= date('F');

		$months_trans				= array();		
		$months_trans['January']	= __('January','wpShop');
		$months_trans['February']	= __('February','wpShop');
		$months_trans['March']		= __('March','wpShop');
		$months_trans['April']		= __('April','wpShop');
		$months_trans['May']		= __('May','wpShop');
		$months_trans['June']		= __('June','wpShop');
		$months_trans['July']		= __('July','wpShop');
		$months_trans['August']		= __('August','wpShop');
		$months_trans['September']	= __('September','wpShop');
		$months_trans['October']	= __('October','wpShop');
		$months_trans['November']	= __('November','wpShop');
		$months_trans['December']	= __('December','wpShop');
		
		
		switch(WPLANG){
			case 'de_DE': 
				#$date_order			= date($OPTION['date_format']);		// use maybe a theme option
				$date_order			= date("j.n.Y");
			break;
			default:
				$date_order			= date($OPTION['date_format']);
			break;
		}
		
		// in case a month name should appear it will be translated 
		$date_order 				= str_replace($monthEN,$months_trans[$monthEN],$date_order);
	}
	else {
		$date_order					= date($OPTION['date_format']);	
	}

return $date_order;
}

##################################################################################################################################
// 												FILTERING
##################################################################################################################################
function mysql_prep_escape($value) 
{ 
	if(get_magic_quotes_gpc() == 1){ 
		$value = mysql_real_escape_string($value);
	} else { 
		$value = addslashes($value); 
	} 
return $value; 
}

function dbquery_now($val){
	$result = NULL;
return $result;
}
 
##################################################################################################################################
// 												MODULES
##################################################################################################################################
function list_modules($cat = 'something'){

	$arr = array();

		// this change was necessary b'c a PHP warning showed up at theme preview before activation - only difference is @
		if(isset($_GET['preview']) && ($_GET['preview'] == '1') && (isset($_GET['template'])) && (isset($_GET['stylesheet']))){
	
			if($handle = @opendir(WP_CONTENT_DIR . '/themes/'.WPSHOP_THEME_NAME.'/lib/modules/'.$cat.'/')) 
			{
			 while(($file = readdir($handle)) !== FALSE){
			 
				if(($file != ".")&&($file != "..")){
					$arr[] = $file;	
				}
			 }
			 closedir($handle);
			}
		}
		else{
		
			if($handle = opendir(WP_CONTENT_DIR . '/themes/'.WPSHOP_THEME_NAME.'/lib/modules/'.$cat.'/')) 
			{
			 while(($file = readdir($handle)) !== FALSE){
			 
				if(($file != ".")&&($file != "..")){
					$arr[] = $file;	
				}
			 }
			 closedir($handle);
			}
		}
	
return $arr;
}

//change.9.10
function load_what_is_needed($class){
	
	$path 	= WP_CONTENT_DIR . '/themes/'.WPSHOP_THEME_NAME.'/lib/engine/';
	$class	= trim($class);
	
	require_once($path.'class.' . $class . '.php');
	$object = new $class();
	
return $object;
}
//\change.9.10

##################################################################################################################################
// 												THEME - INSTALLATION STATUS - UPGRADES
##################################################################################################################################
//rev16022011-9.9
function installation_status(){

		$table 	= is_dbtable_there('status');
		$qStr	= "SELECT status FROM $table WHERE id = 1 LIMIT 0,1";
		$res 	= mysql_query($qStr);
		$row 	= mysql_fetch_assoc($res);
			
return (int)$row['status'];			
}

function installation_status_change(){

	if((isset($_POST['install-step'])) && (isset($_POST['step']))){
	
		$status = (int)$_POST['step'];
		$table 	= is_dbtable_there('status');
		$qStr	= "UPDATE $table SET status = $status WHERE id = 1";
		$res 	= mysql_query($qStr);
	}	
}

//this function should be merged later with silent db ugrade
function update_the_theme_if_necessary($return=0){

	global $wpdb,$CONFIG_WPS,$OPTION;

	$table 	= is_dbtable_there('status');
	$qStr 	= "SELECT th_vers FROM $table LIMIT 0,1";
	$res 	= mysql_query($qStr);
	if($res === FALSE){
		$pfix 	= $wpdb->prefix . $CONFIG_WPS[prefix];	
		$sql	= array();
		$sql[0] = "ALTER TABLE {$pfix}orders ADD `custom_note` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `email`";
		$sql[1] = "ALTER TABLE {$pfix}orders ADD `d_addr` ENUM( '0', '1' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' AFTER `country` ";
		$sql[2] = "ALTER TABLE {$pfix}shopping_cart ADD `item_personal` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `item_attributs`";
		$sql[3] = "ALTER TABLE {$pfix}inquiries ADD `custom_note` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `email`";
		$sql[4] = "ALTER TABLE {$pfix}wishlist ADD `item_personal` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `item_attributs`";

		$sql[5] = "ALTER TABLE {$pfix}feusers ADD `street` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `lname`";
		$sql[6] = "ALTER TABLE {$pfix}feusers ADD `hsno` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `street`";
		$sql[7] = "ALTER TABLE {$pfix}feusers ADD `strno` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `hsno`";
		$sql[8] = "ALTER TABLE {$pfix}feusers ADD `strnam` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `strno`";
		$sql[9] = "ALTER TABLE {$pfix}feusers ADD `po` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `strnam`";
		$sql[10] = "ALTER TABLE {$pfix}feusers ADD `pb` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `po`";
		$sql[11] = "ALTER TABLE {$pfix}feusers ADD `pzone` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `pb`";
		$sql[12] = "ALTER TABLE {$pfix}feusers ADD `crossstr` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `pzone`";
		$sql[13] = "ALTER TABLE {$pfix}feusers ADD `colonyn` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `crossstr`";
		$sql[14] = "ALTER TABLE {$pfix}feusers ADD `district` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `colonyn`";
		$sql[15] = "ALTER TABLE {$pfix}feusers ADD `region` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `district`";
		$sql[16] = "ALTER TABLE {$pfix}feusers ADD `state` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `region`";
		$sql[17] = "ALTER TABLE {$pfix}feusers ADD `zip` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `state`";
		$sql[18] = "ALTER TABLE {$pfix}feusers ADD `town` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `zip`";
		$sql[19] = "ALTER TABLE {$pfix}feusers ADD `country` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `town`";
		
		$sql[20] = "DROP TABLE {$pfix}countries";
		
		$sql[21] = "ALTER TABLE {$pfix}inquiries CHANGE `amount` `amount` DECIMAL( 12, 2 ) NOT NULL";
		$sql[22] = "ALTER TABLE {$pfix}inquiries CHANGE `shipping_fee` `shipping_fee` DECIMAL( 12, 2 ) NOT NULL";		 
		$sql[23] = "ALTER TABLE {$pfix}orders CHANGE `shipping_fee` `shipping_fee` DECIMAL( 12, 2 ) NOT NULL";		 
		$sql[24] = "ALTER TABLE {$pfix}orders CHANGE `amount` `amount` DECIMAL( 12, 2 ) NOT NULL";
		$sql[25] = "ALTER TABLE {$pfix}shopping_cart CHANGE `item_price` `item_price` DECIMAL( 12, 2 ) NOT NULL";
		$sql[26] = "ALTER TABLE {$pfix}wishlist CHANGE `item_price` `item_price` DECIMAL( 12, 2 ) NOT NULL";
		
		$val 	 = get_option('siteurl') . '/wp-content/themes/'. $CONFIG_WPS[themename] .'/ipn.php?pst='.md5(get_option('wps_paypal_pdttoken').NONCE_KEY);
		$sql[27] = "UPDATE $wpdb->options SET option_value = '$val' WHERE option_name = 'wps_ipn_url'";
		
		$sql[28] = "ALTER TABLE {$pfix}status ADD `th_vers` VARCHAR( 50 ) NOT NULL";
		$sql[29] = "UPDATE {$pfix}status SET `th_vers` = '1.0.5' WHERE id =1";
		
		foreach($sql as $v){
			mysql_query($v);
		}
		if($return == 0){
		echo "<div style='color: green; font-weight:800;background:#A5D69C;'>".
				__('Success! Database values for theme `TheFurnitureStore` were updated.<br/>
				Attention! Pls. reset the country zones for shipping if necessary.','wpShop')."</div>";	
		}
		else{
			$feedback =  "<div style='color: green; font-weight:800;background:#A5D69C;'>".
						__('Success! Database values for theme `TheFurnitureStore` were updated.<br/>
						Attention! Pls. reset the country zones for shipping if necessary.','wpShop')."</div>";		

			return $feedback;
		}
	}
}


//silent DB-upgrade for change9.4 + 9.6 ,if needed
function silent_db_upgrade(){	
	
	global $wpdb,$CONFIG_WPS;
	
	$table 	= is_dbtable_there('status');
	$sql	= "SELECT th_vers FROM $table WHERE id = 1 LIMIT 0,1";
	$res	= mysql_query($sql);
	$row 	= mysql_fetch_assoc($res);
	
	if($row['th_vers'] !== 'change.9.6'){
	
		$collate1 = 'COLLATE utf8_general_ci';									
		$collate2 = 'DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci';		

		$pfix 	= $wpdb->prefix . $CONFIG_WPS['prefix'];				
		$sql94	= array();
		
		$sql94[0] = "ALTER TABLE {$pfix}orders ADD `net` decimal(12,2) NOT NULL AFTER `weight`";
		$sql94[1] = "ALTER TABLE {$pfix}orders ADD `tax` varchar(50) $collate1 NOT NULL AFTER `shipping_fee`";		
		$sql94[2] = "ALTER TABLE {$pfix}orders ADD `telephone` VARCHAR( 100 ) NOT NULL AFTER `email`";
		$sql94[3] = "ALTER TABLE {$pfix}inquiries ADD `net` decimal(12,2) NOT NULL AFTER `weight`";		
		$sql94[4] = "ALTER TABLE {$pfix}inquiries ADD `tax` varchar(50) $collate1 NOT NULL AFTER `shipping_fee`";
		$sql94[5] = "ALTER TABLE {$pfix}inquiries ADD `telephone` VARCHAR( 100 ) NOT NULL AFTER `email`";		
		$sql94[6] = "ALTER TABLE {$pfix}shopping_cart CHANGE `item_weight` `item_weight` DECIMAL( 10, 2 ) NOT NULL";	
		$sql94[7] = "ALTER TABLE {$pfix}orders CHANGE `level` `level` ENUM( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' )";		
		$sql94[8] = "DROP TABLE {$pfix}countries";		
		$sql94[9] = "UPDATE {$pfix}status SET `th_vers` = 'change.9.6' WHERE id = 1";	

		foreach($sql94 as $v){
			mysql_query($v);
		}
	}
}

##################################################################################################################################
// 												PAGE - INTEGRATION 
##################################################################################################################################
function show_default_view()
{
	$count 			= 0;
	
	$shop_actions 	= array();
	
	$shop_actions[] = 'showCart';
	$shop_actions[] = 'orderNow';
	$shop_actions[] = 'confirm';
	$shop_actions[] = 'showTerms';
	$shop_actions[] = 'showMap';
	$shop_actions[] = 'checkOrderStatus';
	
	$url 			= $_SERVER['REQUEST_URI'];

	foreach($shop_actions as $v){
		
		$findMe = '?'.$v.'='; 
		$pos 	= strpos($url,$findMe);
		
		if($pos !== FALSE){
			$count++;
		}	
	}
		
	$status = ($count > 0 ? FALSE : TRUE);

return $status;
}

function display_html($filename=NULL,$data=NULL,$ending='.php'){
	global $LANG,$OPTION;
	include(WP_CONTENT_DIR.'/themes/'.WPSHOP_THEME_NAME.'/tpl/'.$filename.$ending);
}

function xl_clean_inventory(){
	global $OPTION;
	return FALSE;
}

function clean_inventory(){
	global $OPTION, $wpdb;

	$otable = is_dbtable_there('orders');
	$sctable = is_dbtable_there('shopping_cart');

	$wpdb->query(sprintf("DELETE FROM %s WHERE who IN (SELECT who FROM %s WHERE level = '1' OR level IS NULL)", $sctable, $otable));
	$wpdb->query(sprintf("DELETE FROM %s WHERE level = '1' OR level IS NULL", $otable));
	layaway_clean_session();
}

// clean shopping cart after 30 min no user activity
function clean_shopping_cart($act = '') {
	global $OPTION, $wpdb;
	$table1 = is_dbtable_there('orders');
	$table2 = is_dbtable_there('shopping_cart');

	$scitems = $wpdb->get_results(sprintf("SELECT * FROM %s WHERE order_id = 0", $table2));
	if ($scitems) {
		foreach($scitems as $scitem) {
			$cid = $scitem->cid;
			$activity_date = $scitem->activity_date;
			if ($activity_date) {
				$minutes = ceil((time() - $activity_date) / 60);
				if ($minutes > 15) {
					$wpdb->query(sprintf("DELETE FROM %s WHERE cid = '%s'", $table2, $cid));
				}
			}
		}
	}
	// check old orders (level = 1) and delete them
	$cdate = mktime(date("G") - 2, date("i"), date("s"), date("m"), date("d"), date("Y"));
	$old_orders = $wpdb->get_results(sprintf("SELECT * FROM %s WHERE level IS NULL OR (level = '1' AND (created <= %s OR created IS NULL))", $table1, $cdate));
	if ($old_orders) {
		foreach($old_orders as $old_order) {
			$sc_items = $wpdb->get_results(sprintf("SELECT * FROM %s WHERE who = '%s'", $table2, $old_order->who));
			if ($sc_items) {
				foreach($sc_items as $sc_item) {
					$wpdb->query(sprintf("DELETE FROM %s WHERE cid = '%s'", $table2, $sc_item->cid));
				}
			}
		}
		$wpdb->query(sprintf("DELETE FROM %s WHERE level IS NULL OR (level = '1' AND (created <= %s OR created IS NULL))", $table1, $cdate));
	}
}

// clean shopping cart after 30 min no user activity
function reset_shopping_cart() {
	global $wpdb;
	$sc_table = is_dbtable_there('shopping_cart');
	if ($_SESSION['cust_id']) {
		$sc_items = $wpdb->get_results(sprintf("SELECT * FROM %s WHERE who = '%s'", $sc_table, $_SESSION['cust_id']));
		if ($sc_items) {
			foreach($sc_items as $sc_item) {
				$cid = $sc_item->cid;
				$wpdb->query(sprintf("DELETE FROM %s WHERE cid = '%s'", $sc_table, $cid));
			}
		}
	}
}

function product_is_available($product_id) {
	global $wpdb;
	$sdate = mktime(date("H"), date("i") - 30, date("s"), date("m"), date("d"), date("Y"));
	$sc_table = is_dbtable_there('shopping_cart');
	$otable = is_dbtable_there('orders');
	clean_shopping_cart('available');
	$check_product = $wpdb->get_var(sprintf("SELECT COUNT(cid) FROM %s WHERE (order_id = 0 OR who IN (SELECT who FROM %s WHERE level = '1' OR level IS NULL)) AND postID = %s AND activity_date >= %s", $sc_table, $otable, $product_id, $sdate));
	if ($check_product > 0) {
		return false;
	}
	return true;
}

function calculate_tax($order, $cart_total) {
	global $OPTION, $wpdb;
	$ctable = is_dbtable_there('countries');
	$tax = 0;
	$country = get_chosen_delivery_country();
	if ($OPTION['wps_tax_enable'] && strlen($country)) {
		$tax_zone = $wpdb->get_var(sprintf("SELECT tax_zone FROM %s WHERE country = '%s'", $ctable, $country));
		if ($tax_zone > 0) {
			$tax_perc = $OPTION['wps_tax_zone'.$tax_zone];
			if ($tax_perc > 0) {
				$tax = ($cart_total / 100) * $tax_perc;
			}
		}
	}
	return $tax;
}

function wps_shop_process_steps($step = 1) {
	$siteurl = get_bloginfo('url');
	$steps = array(
		1 => array('title' => 'Your Order', 'url' => $siteurl.'/?showCart=1'),
		2 => array('title' => 'Payment &amp; Delivery Options', 'url' => $siteurl.'/?orderNow=1'),
		3 => array('title' => 'Delivery &amp; Billing', 'url' => $siteurl.'/?orderNow=2&dpchange=1'),
		4 => array('title' => 'Order Review', 'url' => $siteurl.'/?orderNow=3'),
		5 => array('title' => 'Confirmation', 'url' => '')
	);
?>
	<ul class="payment-steps">
		<?php foreach($steps as $snmb => $sdata) { ?>
			<li<?php if ($snmb == $step) { echo ' class="active"'; } ?>>
				<?php if ($snmb < $step) { ?>
					<span><a href="<?php echo $sdata['url']; ?>"><?php echo $snmb; ?></a></span>
					<a href="<?php echo $sdata['url']; ?>"><?php echo $sdata['title']; ?></a>
				<?php } else { ?>
					<span><?php echo $snmb; ?></span>
					<?php echo $sdata['title']; ?>
				<?php } ?>
			</li>
		<?php } ?>
	</ul>
<?php
}

?>