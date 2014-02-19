<?php
/// SSL only for order procedure //////////////////
if(($_SERVER['HTTPS'] == 'on')||($_SERVER['HTTPS'] == '1') || ($_SERVER['SSL'] == '1')){
	if((isset($_GET['orderNow']))||(isset($_GET['confirm']))||(isset($_GET[updateQty])) || ($_POST['paypal-pro'] == 'ok') || ($_GET['pst'] == md5(LOGGED_IN_KEY.'-'.NONCE_KEY))){
	}
	else {
		$url = 'Location:' . 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		header($url);  
		exit(NULL);		
	}
}
if(isset($_SESSION['cust_id']))
{
	$table = is_dbtable_there('who_activity');
	if(isset($_GET['orderNow']) && $_GET['orderNow']<4 && $_GET['orderNow']>0) $check_out = 2;
	else $check_out = 1;
	
	$cust_id = $_SESSION['cust_id'];
	if(is_user_logged_in())	{	$user = wp_get_current_user(); 	$user_id = $user->ID;	}
	else $user_id = 0;
	$sql 	= "SELECT * FROM $table WHERE Who = '$cust_id'";
	$res 	= mysql_query($sql);
	$active = date('Y-m-d H:i:s');
	if($res && mysql_num_rows($res)>0)
	{ 
		$sql 	= "UPDATE $table SET User_Id = $user_id,Last_Activity = '$active',Check_Out = '$check_out'  WHERE Who = '$cust_id'";
		mysql_query($sql);
	}
	else
	{
		$sql 	= "INSERT INTO $table(who,User_Id,Last_Activity,Check_Out) VALUES('$cust_id',$user_id,'$active',$check_out)";
		mysql_query($sql);
	} 
}

if(isset($_GET['orderNow']))
{
	switch($_GET['orderNow'])
	{	
		case 'reload_form':
			$url = 'Location:' . get_real_base_url() .'/?orderNow=2';
			header($url);  
			exit(NULL);		
		break;
		case '1':
		case '2':
			//..is there still something in the cart?
			$table 	= is_dbtable_there('shopping_cart');
			$qStr 	= "SELECT cid FROM $table WHERE who = '$_SESSION[cust_id]'";			
			$res 	= mysql_query($qStr);
			$num2 	= mysql_num_rows($res);
			if($num2 == 0){
				$go2Url = get_option('home').'/?showCart=1';
				header('Location: '.$go2Url);
				exit;
			}
			break;
		case '3':
			//..is there still something in the cart?
			// needs to do....
			process_order(3);
			$table 	= is_dbtable_there('shopping_cart');
			$qStr 	= "SELECT cid FROM $table WHERE who = '$_SESSION[cust_id]'";			
			$res 	= mysql_query($qStr);
			$num2 	= mysql_num_rows($res);
			if($num2 == 0){
				$go2Url = get_option('home').'/?showCart=1';
				header('Location: '.$go2Url);
			}
			// if no record at all - we bounce user back to shopping cart 
			$table 	= (get_option('wps_shop_mode') == 'Inquiry email mode' ? is_dbtable_there('inquiries') : is_dbtable_there('orders'));
			$qStr 	= "SELECT level,d_option,p_option,country FROM $table WHERE who = '$_SESSION[cust_id]' LIMIT 0,1";			
			$res 	= mysql_query($qStr);
			$num 	= mysql_num_rows($res);
			
			if($num == 0){
				$go2Url = get_option('home').'/?showCart=1';
				header('Location: '.$go2Url);
			}
			else{
				// is there a chosen d_option + p_option? No --> back to step 1
				$allOK	= TRUE;
				$row 	= mysql_fetch_assoc($res);
				if(strlen($row['d_option']) < 2){
					$allOK	= FALSE;
				}				
				if(strlen($row['p_option']) < 2){
					$allOK	= FALSE;
				}
				if($allOK === FALSE){
					$go2Url = get_option('home').'/?orderNow=1';
					header('Location: '.$go2Url);
				}
			}
			if(!cod_available())
			{
				$_SESSION['cod_not_available'] = TRUE;
				$go2Url = get_option('home').'/?orderNow=1&error=cod_not_available#editPayment';
				header('Location: '.$go2Url);
				exit;
			}
		break;	
	}
} 

if((isset($_POST['wishlist'])) && ($_POST['wishlist'] == 'yes')){
//if((isset($_POST['wishlist_x'])) && (isset($_POST['wishlist_y']))){
	$WISHLIST = load_what_is_needed('wishlist');	
	$WISHLIST->add_toWishlist();					
} else{
	add_toCart();
}
if(isset($_POST['step2'])){			
	$feedback = check_address_form();
	
	if($feedback['error'] == 0){	// if address data ok + Terms accepted we move on 	
		process_order(2);
		$url = 'Location:' . get_real_base_url() .'/?orderNow=3';
		header($url);  
		exit(NULL);
	}
}
if(isset($_POST['step3'])){				
		process_order(4);
		$url = 'Location:' . get_real_base_url() .'/?orderNow=4';
		header($url);  
		exit(NULL);
}
if($_POST['paypal-pro'] == 'ok'){		
				include(WP_CONTENT_DIR.'/themes/'.WPSHOP_THEME_NAME.'/lib/modules/payment/paypal_pro/functions.php');
				$api_url 	= 'https://api-3t.paypal.com/nvp';
				$response 	= sendPayPalProTransaction($api_url,createPProString());					
				paypal_pro_redirect($response);
}
if($_GET['showCart'] == '1'){			
	
		if(isset($_POST['update'])){
			update_cart();
		}		
		$CART = show_cart();
}
if($_GET['display_invoice'] == '1'){
	$INVOICE = load_what_is_needed('invoice');	//change.9.10
	$INVOICE->retrieve_invoice_pdf();			//change.9.10
}
// we hide cart button if the product has attrib option = 2
$attr_option 	= get_custom_field("add_attributes"); // attributes - simple or configurable price?
$script 		= ($attr_option == '2' ?  'onload="cartButtonVisbility()"' : NULL);
$OPTION 		= NWS_get_global_options();
?>