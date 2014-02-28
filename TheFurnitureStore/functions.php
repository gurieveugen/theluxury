<?php
session_start();

define('TEMPLURL', get_bloginfo('template_url'));
define('WPSHOP_LIB', 'lib/');

// custom thumbnails size
add_image_size('item-single-small-thumbnail', 61, 61, true);
add_image_size('item-related-box-thumbnail', 64, 64, true);
add_image_size('item-list-thumbnail', 91, 91, true);
add_image_size('item-tlc-quotation-thumbnail', 140, 140, true);
add_image_size('item-search-list-thumbnail', 174, 174, true);
add_image_size('item-single-big-thumbnail', 605, 605, true);
add_image_size('item-preview-thumbnail', 800, 800, true);

// SSL 	
add_filter('option_siteurl', 'adjust2ssl');
add_filter('option_home', 'adjust2ssl');
add_filter('option_url', 'adjust2ssl');
add_filter('option_wpurl', 'adjust2ssl');
add_filter('option_stylesheet_url', 'adjust2ssl');
add_filter('option_template_url', 'adjust2ssl');


if(!isset($_SERVER['HTTPS'])){$_SERVER['HTTPS'] = NULL;}	
if(!isset($_SERVER['SSL'])){$_SERVER['SSL'] = NULL;}		

require_once(WPSHOP_LIB . 'engine/options.php');

$OPTION 					= NWS_get_global_options();
$CONFIG_WPS 				= array();
$CONFIG_WPS['themename'] 	= $OPTION['template'];
$CONFIG_WPS['shortname'] 	= 'wps';
$CONFIG_WPS['prefix'] 		= 'wps_';

// Current theme
define('WPSHOP_THEME_NAME',$OPTION['template']);

// Email delivery typ	
define('WPSHOP_EMAIL_FORMAT_OPTION',$OPTION['wps_email_delivery_type']); // txt,mime

// Load required files, modules + define necessary constants
require_once(WPSHOP_LIB . 'engine/cart_actions.php');
require_once(WPSHOP_LIB . 'engine/NWS_functions.php');
require_once(WPSHOP_LIB . 'engine/NWS_login_functions.php');
require_once(WPSHOP_LIB . 'engine/NWS_taxonomies.php');
require_once(WPSHOP_LIB . 'engine/NWS_shortcodes.php');
require_once(WPSHOP_LIB . 'engine/NWS_widgets.php');
require_once(WPSHOP_LIB . 'engine/nws-email-formats.php');
require_once(WPSHOP_LIB . 'engine/class.rhinosupport.php');
require_once(WPSHOP_LIB . 'engine/layaway.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/prelaunch/common.php');
require_once(TEMPLATEPATH . '/geoplugin.class.php');
require_once(TEMPLATEPATH . '/functions/search-ajax.php');
require_once(TEMPLATEPATH . '/menu-walker.php' ); 

// register payment+delivery modules
$CONFIG_WPS['p_modules'] = list_modules('payment');
$CONFIG_WPS['d_modules'] = list_modules('delivery');
$CONFIG_WPS['c_modules'] = array('bhd','egp','jod','kwd','lbp','qar','sar','syp','aed','omr');
			
if(!isset ($OPTION['wps_shop_country'])) {
	require_once(WPSHOP_LIB . 'modules/tax/globalTax/DE.php');
} else {
	require_once(WPSHOP_LIB . 'modules/tax/globalTax/'.$OPTION['wps_shop_country'].'.php');
}
if(is_admin()) {
	require_once(WPSHOP_LIB . 'engine/theme_options.php');
	require_once(WPSHOP_LIB . 'engine/backend_actions.php');
}

// CURRENCIES
$currency_codes = array('usd' => 'USD', 'aed' => 'UAE Dirham', 'bhd' => 'Bahraini Dinar', 'egp' => 'Egyptian Pound', 'jod' => 'Jordanian Dinar', 'kwd' => 'Kuwaiti Dinar', 'lbp' => 'Lebanese Pound', 'omr' => 'Omani Rial', 'qar' => 'Qatari Riyal', 'sar' => 'Saudi Riyal', 'syp' => 'Syrian Pound');
$currency_options = explode("|", get_option('wps_currency_options'));
$currency_rates = array();
$currency_rates['usd'] = 1;
foreach($currency_codes as $cc => $cn) { if ($cc != 'usd') { $currency_rates[$cc] = get_option('wps_exr_'.$cc); } }

register_sidebar( array(
		'name' => __( 'Banner Area', 'twentyten' ),
		'id' => 'banner-area',
		'before_widget' => '<div id="%1$s" class="container promo-widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

register_sidebar( array(
		'name' => __( 'social-widget-area', 'twentyten' ),
		'id' => 'social-widget-area',
		'description' => __( 'The primary widget area', 'twentyten' ),
		'before_widget' => ' ',
		'after_widget' => ' ',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

register_sidebar( array(
		'name' => __( 'footer_links', 'twentyten' ),
		'id' => 'footer_links',
		'description' => __( 'The primary widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

register_sidebar( array(
		'name' => __( 'Footer Area', 'twentyten' ),
		'id' => 'footer-area',
		'before_widget' => '<div id="%1$s" class="container %2$s"><div class="promiseWrap"><div class="row center">',
		'after_widget' => '</div></div></div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

register_nav_menus( array(
	'primary_nav' => __( 'Primary Navigation', 'theme' )
) );

if(is_admin())
{
	// Add admin css
	function nws_admin_styles()
	{
		echo '<link rel="stylesheet" media="all" type="text/css" href="'.get_bloginfo('template_url').'/css/admin.css" />';
		echo '<script src="'.get_bloginfo('template_url').'/js/admin.js"></script>';
	}
	// Add admin scripts
	function nws_admin_scripts()
	{
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-tabs');
		wp_enqueue_script('thickbox');
		wp_enqueue_script( 'myadmin.js', get_template_directory_uri().'/js/myadmin.js', array('jquery'), '1',true );
	}
	// Add theme options
	function nws_add_admin()
	{
		global $CONFIG_WPS,$OPTION,$options,$wp_roles;
		$currrole = current_user_role();
		if($currrole == 'administrator')
		{
			if($_GET['page'] == basename(__FILE__))
			{
				if('save' == $_REQUEST['action'])
				{	
					// we need to know which country is currently zone 1 country
					$cAbbrOld 	= $OPTION['wps_shop_country'];
					foreach($options as $value)
					{	
						if(($value['type'] == 'checkbox') && (!isset($_POST[ $value['id'] ]))){ $actualValue = 'false'; }
						else {	$actualValue = $_REQUEST[ $value['id'] ]; }
						update_option($value['id'],$actualValue); 
					}
					// we set the shop country as zone 1 in countries db-table
					update_zone_one($cAbbrOld);
					summarize_multi_checkbox('_payment_options',$CONFIG_WPS[p_modules]);
					summarize_multi_checkbox('_delivery_options',$CONFIG_WPS[d_modules]);
					//GW START	
					summarize_multi_checkbox('_currency_options',$CONFIG_WPS[c_modules]);
					//GW END
					optimize_table();
					//header("Location: themes.php?page=functions.php&saved=true");
					header("Location: admin.php?page=functions.php&saved=true");
					die;
				} else if( 'reset' == $_REQUEST['action'] )
				{
					foreach ($options as $value) { 	delete_option( $value['id'] ); }
					//header("Location: themes.php?page=functions.php&reset=true");
					header("Location: admin.php?page=functions.php&reset=true");
					die;
				}
			}		
			add_object_page($CONFIG_WPS['themename'],__('eCommerce','wpShop'), 'administrator', basename(__FILE__), 'NWS_theme_admin', get_bloginfo('template_directory'). '/images/admin/shopping_cart.png'); 
			add_submenu_page(basename(__FILE__), $CONFIG_WPS['themename'],__('Theme Options','wpShop'), 'administrator', basename(__FILE__), 'NWS_theme_admin');

			if($OPTION['wps_shoppingCartEngine_yes']) 
			{
				if($OPTION['wps_shop_mode'] == 'Normal shop mode'){ 
					add_submenu_page(basename(__FILE__), $CONFIG_WPS['themename'],__('Manage Orders','wpShop'), 'administrator', basename(__FILE__).'&section=orders','NWS_theme_admin');
				}elseif($OPTION['wps_shop_mode'] == 'Inquiry email mode'){
					add_submenu_page(basename(__FILE__), $CONFIG_WPS['themename'],__('Manage Enquiries','wpShop'), 'administrator', basename(__FILE__).'&section=inquiries','NWS_theme_admin');  
				} else{}
				if($OPTION['wps_track_inventory']=='active'){
					add_submenu_page(basename(__FILE__), $CONFIG_WPS['themename'],__('Manage Inventory','wpShop'), 'administrator', basename(__FILE__).'&section=inventory','NWS_theme_admin'); 
				}
				$l_mode = $OPTION['wps_l_mode'];
				if(($l_mode == 'GIVE_KEYS')&&($OPTION['wps_shop_mode'] != 'payloadz_mode')&&($OPTION['wps_shop_mode'] != 'Inquiry email mode')){ 
					add_submenu_page(basename(__FILE__), $CONFIG_WPS['themename'],__('Manage LKeys','wpShop'), 'administrator', basename(__FILE__).'&section=lkeys','NWS_theme_admin'); 
				} 
				if ($OPTION['wps_voucherCodes_enable']) {
					add_submenu_page(basename(__FILE__), $CONFIG_WPS['themename'],__('Manage Vouchers','wpShop'), 'administrator', basename(__FILE__).'&section=vouchers','NWS_theme_admin');
				}
			}
			// using the membership area?
			if($OPTION['wps_lrw_yes']) { 
				add_submenu_page(basename(__FILE__), $CONFIG_WPS['themename'],__('Manage Members','wpShop'), 'administrator', basename(__FILE__).'&section=members','NWS_theme_admin'); 
			}
			if($OPTION['wps_shoppingCartEngine_yes']) {
				add_submenu_page(basename(__FILE__), $CONFIG_WPS['themename'],__('Statistics','wpShop'), 'administrator', basename(__FILE__).'&section=statistics','NWS_theme_admin');
			}
			add_submenu_page(basename(__FILE__), $CONFIG_WPS['themename'],__('Pricing','wpShop'), 'administrator', basename(__FILE__).'&section=pricing','NWS_theme_admin');
			add_submenu_page(basename(__FILE__), $CONFIG_WPS['themename'],__('Searches','wpShop'), 'administrator', basename(__FILE__).'&section=searches','NWS_theme_admin');
		} else if($currrole == 'editor') { // EDITOR Role
			add_object_page($CONFIG_WPS['themename'],__('eCommerce','wpShop'), 'manage_links', 'nws-inventory', 'NWS_theme_editor', get_bloginfo('template_directory'). '/images/admin/shopping_cart.png'); 

			add_submenu_page('nws-inventory', $CONFIG_WPS['themename'],__('Manage Inventory','wpShop'), 'manage_links', 'nws-inventory&submenu=true','NWS_theme_editor');
		} else if($currrole == 'staff' || $currrole == 'buyer') { // STAFF and BUYER Role
			add_object_page($CONFIG_WPS['themename'],__('eCommerce','wpShop'), $currrole, basename(__FILE__), 'NWS_theme_staff', get_bloginfo('template_directory'). '/images/admin/shopping_cart.png'); 

			add_submenu_page(basename(__FILE__), $CONFIG_WPS['themename'],__('Statistics','wpShop'), $currrole, basename(__FILE__),'NWS_theme_staff');
			add_submenu_page(basename(__FILE__), $CONFIG_WPS['themename'],__('Manage Orders','wpShop'), $currrole, basename(__FILE__).'&section=orders','NWS_theme_staff');
			if($currrole == 'buyer' || is_spec_staff_user()) {
				add_submenu_page(basename(__FILE__), $CONFIG_WPS['themename'],__('Manage Inventory','wpShop'), $currrole, basename(__FILE__).'&section=inventory','NWS_theme_staff');
			}
			add_submenu_page(basename(__FILE__), $CONFIG_WPS['themename'],__('Pricing','wpShop'), $currrole, basename(__FILE__).'&section=pricing','NWS_theme_staff');
		}

		// add custom roles
		$custom_roles = array('staff' => 'Staff', 'buyer' => 'Buyer', 'profseller' => 'Professional Seller');
		foreach($custom_roles as $crole => $crole_name) {
			$role_object = get_role($crole);
			if (!$role_object) {
				$wp_roles->add_role($crole, $crole_name, array('read' => 1, 'level_0' => 1));
			}
		}
	}

	wp_enqueue_style('thickbox');
	add_action('admin_head', 'nws_admin_styles');
	add_action('init', 'nws_admin_scripts');
	add_action('admin_menu', 'nws_add_admin');
	add_filter('postmeta_form_limit', 'nws_postmeta_form_limit');
}

// Add translations
function NWS_theme_init(){
	global $OPTION, $currency_rates, $currency_codes, $currency_options;

	load_theme_textdomain('wpShop', get_template_directory().'/languages/');
	$currency_code = 'USD';
	$currency_rate = '1';
	if (strlen($_COOKIE['theluxcurrency'])) {
		$currency_code = $_COOKIE['theluxcurrency'];
		$currency_rate = $currency_rates[strtolower($currency_code)];
	}
	$_SESSION['currency-code'] = $currency_code;
	$_SESSION['currency-rate'] = $currency_rate;

	// add to wishlist
	if(isset($_GET['wishlist']) && $_GET['wishlist'] == 'add' && $_GET['pid'] > 0 && is_user_logged_in()){
		$WISHLIST = load_what_is_needed('wishlist');
		$WISHLIST->add_toWishlist();
	}
	// SHOPPING CART ACTIONS
	// add to cart
	if(isset($_POST['cmd']) && $_POST['cmd'] == 'add'){
		add_toCart();
	}
	// shopping cart update
	if ($_GET['showCart'] == '1' && $_POST['cart'] == 'update') {
		update_cart();
	}
	// ORDER ACTIONS
	// order steps action
	if ($_POST['order_step'] == 1) {
		process_order();
	} else if ($_POST['order_step'] == 2) {
		$feedback = check_address_form();
		if($feedback['error'] == 0) {
			process_order(2);
			wp_redirect(get_real_base_url() .'/?orderNow=3');
			wp_exit();
		}
	} else if ($_POST['order_step'] == 3 || isset($_GET['confirm'])) {
		if ($_POST['order_step'] == 3) {
			$p_option = $_POST['p_option'];
			include WP_CONTENT_DIR.'/themes/'.WPSHOP_THEME_NAME.'/lib/modules/payment/'.$p_option.'/functions.php';
			switch ($p_option) {
				case "cod": // cash on delivery
					$payment_feedback = cod_response();
				break;
				case "cash": // cash on location
					$payment_feedback = cas_response();
				break;
				case "transfer": // bank transfer
					$payment_feedback = banktransfer_response();
				break;
			}
		} else if (isset($_GET['confirm'])) {
			$pm_names = array('1' => 'paypal', '100500' => 'audi');
			$confirm = $_GET['confirm'];
			include WP_CONTENT_DIR.'/themes/'.WPSHOP_THEME_NAME.'/lib/modules/payment/'.$pm_names[$confirm].'/functions.php';
			switch ($confirm) {
				case "1": // Response from PayPal
					$payment_feedback = pdt_response();
				break;
				case "100500": // Response from Audi
					$payment_feedback = audi_response();
				break;
			}
		}
		$_SESSION['order_payment_data'] = $payment_feedback;
		wp_redirect(get_real_base_url() .'/?orderNow=confirm');
		wp_exit();
	}
	if ($_GET['orderNow'] == '3' && $_POST['order_review'] == 'update') {
		order_review_update();
	}

	// AJAX ACTIONS
	if (isset($_GET['FormAction']) || isset($_POST['FormAction'])) {
		if ($_GET['FormAction'] == 'product-views') {
			$ip = $_SERVER['REMOTE_ADDR'];
			$product_id = $_GET['product_id'];
			if ($product_id) {
				$product_views = get_post_meta($product_id, '_product_views', true);
				if ($product_views) {
					if (!is_array($product_views)) { $product_views = unserialize($product_views); }
					$product_views[$ip] = time();
					$sdate = mktime(date("H"), date("i") - 5, date("s"), date("m"), date("d"), date("Y"));
					foreach($product_views as $pvip => $pvtm) {
						if ($pvtm <= $sdate) {
							unset($product_views[$pvip]);
						}
					}
				} else {
					$product_views = array($ip => time());
				}
				update_post_meta($product_id, '_product_views', $product_views);
				echo count($product_views);
			}
		} else if ($_GET['FormAction'] == 'header-shop-cart-items') {
			include (TEMPLATEPATH . '/lib/pages/header_cart.php');
		} else if ($_GET['FormAction'] == 'user-region-currency') {
			$urcurrency = 'USD';
			$ip = $_SERVER['REMOTE_ADDR'];
			$geoplugin = new geoPlugin();
			$geoplugin->locate($ip);
			$currencyCode = $geoplugin->currencyCode;
			if (strlen($currencyCode)) {
				if (in_array(strtolower($currencyCode), $currency_options)) {
					$urcurrency = $currencyCode;
				}
			}
			echo $urcurrency;
		} else if ($_POST['FormAction'] == 'popup-subscribe') {
			// subscribe user
			nws_subscribe_action('popup', array('email' => $_POST['email'], 'gender' => $_POST['gender']));
		}
		exit;
	}
}
add_action('init', 'NWS_theme_init');

if(!function_exists('current_user_role'))
{
	function current_user_role()
	{
		if ( is_user_logged_in() ) 
		{
			global $current_user;
			$role = $current_user->roles[0];
			switch($role) 
			{
				case ('administrator'||'editor'||'contributor'||'author'):
					return $role;
				break;
			}
		}
		return false;
	}
}

// limit of custom field drop-down on edit post page
function nws_postmeta_form_limit($limit) {
	return 200;
}

//call cron for cleaning inventory (returning not purchased items from basket
add_filter('cron_schedules', 'cron_schedules_add_cutom');

function cron_schedules_add_cutom($schedules) {
	$schedules['five'] = array(
		'interval' => 300,
		'display' => __( '5_minutes' )
	);
	$schedules['fifteen'] = array(
		'interval' => 900,
		'display' => __( '15_minutes' )
	);
	return $schedules;
}

add_action('my_fifteen_event', 'do_this_fifteen');
add_action('cron_clean_shopping_cart_event', 'cron_clean_shopping_cart');
add_action('cron_update_inventory_in_posts', 'cron_update_inventory');

function custom_cron_activation() {
	if ( !wp_next_scheduled( 'my_fifteen_event' ) ) {
		wp_schedule_event( time(), 'fifteen', 'my_fifteen_event');
	}
	if ( !wp_next_scheduled( 'cron_clean_shopping_cart_event' ) ) {
		wp_schedule_event( time(), 'five', 'cron_clean_shopping_cart_event');
	}
	if ( !wp_next_scheduled( 'cron_update_inventory_in_posts' ) ) {
		wp_schedule_event( time(), 'five', 'cron_update_inventory_in_posts');
	}
	if ( !wp_next_scheduled( 'cron_update_brands_count' ) ) {
		wp_schedule_event( time(), 'five', 'cron_update_brands_count');
	}
}
add_action('wp', 'custom_cron_activation');

function do_this_fifteen() {
	$cdate = date("YmdHi");
	$cron_cinv_date = get_option('cron_cinv_date');
	$df = $cdate - $cron_cinv_date;
	if ($df >= 5) {
		xl_clean_inventory();
		update_option('cron_cinv_date', $cdate);
	}
}

function cron_clean_shopping_cart() {
	$cdate = date("YmdHi");
	$cron_csc_date = get_option('cron_csc_date');
	$df = $cdate - $cron_csc_date;
	if ($df >= 5) {
		clean_shopping_cart('cron');
		update_option('cron_csc_date', $cdate);
	}
}

function cron_update_inventory() {
	global $wpdb;
	$ctime = date("YmdHi");
	$updated_time = get_option('cron_updated_inventory');
	if (($ctime - $updated_time) >= 5) {
		$items = $wpdb->get_results(sprintf("SELECT * FROM %spostmeta WHERE meta_key = 'ID_item' ORDER BY post_id", $wpdb->prefix));
		foreach($items as $item) {
			$item_id = $item->meta_value;
			$post_id = $item->post_id;
			$inv_amount = (int)$wpdb->get_var(sprintf("SELECT amount FROM %swps_inventory WHERE ID_item = '%s' LIMIT 0, 1", $wpdb->prefix, $item_id));
			$wpdb->query(sprintf("UPDATE %sposts SET inventory = %s WHERE ID = %s", $wpdb->prefix, $inv_amount, $post_id));
		}
		update_option('cron_updated_inventory', $ctime);
	}
}

function cron_update_brands_count() {
	global $wpdb;
	$tt_items = $wpdb->get_results("SELECT * FROM wp_term_taxonomy WHERE taxonomy IN ('brand', 'selection', 'price', 'colour', 'size', 'ring-size') ORDER BY term_taxonomy_id");
	foreach($tt_items as $tt_item) {
		$term_taxonomy_id = $tt_item->term_taxonomy_id;
		$count = 0;
		$trelationships = $wpdb->get_results("SELECT * FROM wp_term_relationships WHERE term_taxonomy_id = '".$term_taxonomy_id."'");
		if ($trelationships) {
			foreach($trelationships as $trelationship) {
				$oid = $trelationship->object_id;
				$post_status = $wpdb->get_var("SELECT post_status FROM wp_posts WHERE ID = '".$oid."'");
				if ($post_status == 'publish') {
					$count++;
				}
			}
		}
		$wpdb->query("UPDATE wp_term_taxonomy SET count = ".$count." WHERE term_taxonomy_id = ".$term_taxonomy_id);
	}
}

function get_post_thumb($attach_id, $width = 30000, $height = 30000, $crop = false) {
	if (is_numeric($attach_id)) {
		$image_src = wp_get_attachment_image_src($attach_id, 'full');
		$file_path = get_attached_file($attach_id);
	} else {
		$imagesize = getimagesize($attach_id);
		$image_src[0] = $attach_id;
		$image_src[1] = $imagesize[0];
		$image_src[2] = $imagesize[1];
		$file_path = $_SERVER["DOCUMENT_ROOT"].str_replace(get_bloginfo('siteurl'), '', $attach_id);
		
	}
	
	$file_info = pathinfo($file_path);
	$extension = '.'. $file_info['extension'];

	// image path without extension
	$no_ext_path = $file_info['dirname'].'/'.$file_info['filename'];

	$cropped_img_path = $no_ext_path.'-'.$width.'x'.$height.$extension;

	// if file size is larger than the target size
	if ($image_src[1] > $width || $image_src[2] > $height) {
		// if resized version already exists
		if (file_exists($cropped_img_path)) {
			return str_replace(basename($image_src[0]), basename($cropped_img_path), $image_src[0]);
		}

		if (!$crop) {
			// calculate size proportionaly
			$proportional_size = wp_constrain_dimensions($image_src[1], $image_src[2], $width, $height);
			$resized_img_path = $no_ext_path.'-'.$proportional_size[0].'x'.$proportional_size[1].$extension;			

			// if file already exists
			if (file_exists($resized_img_path)) {
				return str_replace(basename($image_src[0]), basename($resized_img_path), $image_src[0]);
			}
		}

		// resize image if no such resized file
		$new_img_path = image_resize($file_path, $width, $height, $crop);
		$new_img_size = getimagesize($new_img_path);
		return str_replace(basename($image_src[0]), basename($new_img_path), $image_src[0]);
	}

	// return without resizing
	return $image_src[0];
}

function scripts_method() {
	wp_deregister_script( 'jquery' );
	wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js');
	wp_enqueue_script( 'jquery' );
}
add_action('wp_enqueue_scripts', 'scripts_method');

function js_get_currency_reload() {
	$js_currency_reload = 'false';
	if (isset($_GET['showCart']) || isset($_GET['orderNow'])) {
		$js_currency_reload = 'true';
	}
	if (strpos($_SERVER['REQUEST_URI'], 'sell') || strpos($_SERVER['REQUEST_URI'], 'prof') || strpos($_SERVER['REQUEST_URI'], 'tlc-admin-files') || strpos($_SERVER['REQUEST_URI'], 'seller-summary') || strpos($_SERVER['REQUEST_URI'], 'pricing-search')) {
		$js_currency_reload = 'true';
	}
	return $js_currency_reload;
}

add_action('generate_rewrite_rules', 'nws_rewrite_rules');
function nws_rewrite_rules($wp_rewrite) {
	$rules = array('csv-feed/?$' => 'index.php?csv-feed=true');
    $wp_rewrite->rules = $rules + $wp_rewrite->rules;
    return $wp_rewrite;
}

function log_action($log_code, $log_desc) {
	global $wpdb;
	$insert = array();
	$insert['log_date'] = date("Y-m-d H:i:s");
	$insert['log_code'] = $log_code;
	$insert['log_desc'] = $log_desc;
	$wpdb->insert($wpdb->prefix."wps_log_actions", $insert);
}

function nws_users_contactmethods($usercontactmethods) {
	$usercontactmethods['phone'] = 'Telephone';
	return $usercontactmethods;
}
add_filter("user_contactmethods", "nws_users_contactmethods");

//facebook meta information
function mfields_facebook_meta() 
{
    if ( is_singular() ) 
	{
        $image = '';
        if ( has_post_thumbnail( get_the_ID() ) ) 
		{
            $image = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
        }
        print "\n" . '<meta property="fb:app_id" content="276284119066763"/>';
        print "\n" . '<meta property="og:type" content="website"/>';
        print "\n" . '<meta property="og:title" content="' . esc_attr( get_the_title() ) . '"/>';
        print "\n" . '<meta property="og:site_name" content="' . esc_attr( get_bloginfo() ) . '"/>';
        if ( ! empty( $image ) ) {
            print "\n" . '<meta property="og:image" content="' . esc_url( $image ) . '"/>';
        }
		$content = get_the_content();
		$content = strip_shortcodes($content);
		$content = str_replace(']]>', ']]>', $content);
		$content = strip_tags($content);
		print "\n" . '<meta property="og:description" content="'.$content.'"/>';
		print "\n" . '<meta property="og:url" content="'.get_permalink().'"/>';
		print "\n" . '<meta property="og:locale" content="en_US"/>';
    }
	else
	{
		$image = 'http://www.theluxurycloset.com/wp-content/uploads/Hermes_19_front_04112011.jpg';
		print "\n" . '<meta property="og:title" content="Upto 70% off on Pre owned luxury bags. Guaranteed Authentic."/>';
		print "\n" . '<meta property="fb:app_id" content="276284119066763"/>';
        print "\n" . '<meta property="og:type" content="website"/>';
        print "\n" . '<meta property="og:site_name" content="' . esc_attr( get_bloginfo() ) . '"/>';
        if ( ! empty( $image ) ) {
            print "\n" . '<meta property="og:image" content="' . esc_url( $image ) . '"/>';
        }
		print "\n" . '<meta property="og:description" content="Shop for 100% authentic pre-owned luxury bag and handbags, from top brands including Louis Vuitton, Chanel, Gucci, Dior, and Hermes. Based in Dubai, UAE, Middle East"/>';
        print "\n" . '<meta property="og:url" content="'.get_bloginfo('home').'"/>';
		print "\n" . '<meta property="og:locale" content="en_US"/>';
	}
}
add_action( 'wp_head', 'mfields_facebook_meta' );

add_action('init', 'init_hivista');
function init_hivista() {
	if ($_GET['hivista'] == 'init') {
		layaway_reminder_send();
	}
}
?>