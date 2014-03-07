<?php
// db   db  .d88b.   .d88b.  db   dD .d8888. 
// 88   88 .8P  Y8. .8P  Y8. 88 ,8P' 88'  YP 
// 88ooo88 88    88 88    88 88,8P   `8bo.   
// 88~~~88 88    88 88    88 88`8b     `Y8b. 
// 88   88 `8b  d8' `8b  d8' 88 `88. db   8D 
// YP   YP  `Y88P'   `Y88P'  YP   YD `8888Y' 
add_action("wp_ajax_search_products", "search_products_ajax");
add_action("wp_ajax_nopriv_search_products", "search_products_ajax");
add_action("wp_ajax_search_ajax_by_hash", "search_ajax_by_hash");
add_action("wp_ajax_nopriv_search_ajax_by_hash", "search_ajax_by_hash");
add_action("wp_ajax_last_args", "last_args_ajax");
add_action("wp_ajax_nopriv_last_args", "last_args_ajax");
add_action("wp_ajax_change_ppp", "change_ppp_ajax");
add_action("wp_ajax_nopriv_change_ppp", "change_ppp_ajax");
add_action("wp_ajax_change_page", "change_page_ajax");
add_action("wp_ajax_nopriv_change_page", "change_page_ajax");
add_action("wp_ajax_change_sort", "change_sort_ajax");
add_action("wp_ajax_nopriv_change_sort", "change_sort_ajax");
add_action("wp_ajax_display_categories", "get_display_categories_ajax");
add_action("wp_ajax_nopriv_display_categories", "get_display_categories_ajax");
add_action("wp_ajax_get_default_content", "get_default_content_ajax");
add_action("wp_ajax_nopriv_get_default_content", "get_default_content_ajax");
add_action("wp_ajax_get_latest_products", "get_latest_products_ajax");
add_action("wp_ajax_nopriv_get_latest_products", "get_latest_products_ajax");
// .88b  d88. d88888b d888888b db   db  .d88b.  d8888b. .d8888. 
// 88'YbdP`88 88'     `~~88~~' 88   88 .8P  Y8. 88  `8D 88'  YP 
// 88  88  88 88ooooo    88    88ooo88 88    88 88   88 `8bo.   
// 88  88  88 88~~~~~    88    88~~~88 88    88 88   88   `Y8b. 
// 88  88  88 88.        88    88   88 `8b  d8' 88  .8D db   8D 
// YP  YP  YP Y88888P    YP    YP   YP  `Y88P'  Y8888D' `8888Y' 
function search_products()
{
	get_template_part('loop', 'products');
}

function search_products_ajax()
{
	unset($_SESSION["last_args"]['tax_query']['relation']);
	unset($_SESSION["last_args"]['post_status']);

	$json['loop']       = load_template_part('loop', 'products');
	$json['args']       = rawurlencode(http_build_query($_SESSION["last_args"]));
	$json['categories'] = (isset($_SESSION['all_display_categories'])) ? $_SESSION['all_display_categories'] : '';

	echo json_encode($json);

	die();
}

function search_ajax_by_hash()
{
	if(isset($_POST["search_hash"]))
	{
		$_GET["_escaped_fragment_"] = $_POST["search_hash"];
		get_template_part('loop', 'products');
		die();
	}
}

function change_ppp_ajax()
{
	$_SESSION["ppp"] = $_POST["ppp"];
	get_template_part('loop', 'products');
	die();
}

function change_page_ajax()
{
	$_SESSION["paged"] = $_POST["paged"];
	get_template_part('loop', 'products');
	die();
}

function change_sort_ajax()
{
	$_SESSION["new_psort"] = $_POST["psort"];
	get_template_part('loop', 'products');
	die();
}

function get_default_content_ajax()
{
	$_SESSION["get_default"] = true;

	unset($_SESSION["last_args"]['tax_query']['relation']);
	unset($_SESSION["last_args"]['post_status']);

	$json['loop']       = load_template_part('loop', 'products');
	$json['args']       = rawurlencode(http_build_query($_SESSION["last_args"]));
	$json['categories'] = (isset($_SESSION['all_display_categories'])) ? $_SESSION['all_display_categories'] : '';

	echo json_encode($json);
	
	die();	
}

function get_latest_products_ajax()
{	
	$_SESSION['show_latest_products'] = TRUE;
	$_SESSION['custom_args']          = $_SESSION['last_args'];

	get_template_part('loop', 'products');

	die();
}

/**
 * Get last args parameter to ajax request
 * @return echo parameters
 */
function last_args_ajax()
{
	unset($_SESSION["last_args"]['tax_query']['relation']);
	unset($_SESSION["last_args"]['post_status']);
	
	echo rawurlencode(http_build_query($_SESSION["last_args"]));
	die();
}

/**
 * Get all display categories AJAX
 */
function get_display_categories_ajax()
{
	if($_SESSION['all_display_categories'])
	{
		echo json_encode($_SESSION['all_display_categories']);	
	}
	
	die();
}

function get_cat_parent($id, $tax = 'category')
{
  $parent = get_term($id, $tax);
  if($parent->parent == "0")
  {
  	if($parent->slug == "men" OR $parent->slug == "women" OR $parent->taxonomy == "brand")
  	{
  		echo '<input type="hidden" id="is_open_brands" name="is_open_brands" value="yes" data-value="'.$parent->slug.'">';		
  	}
  } 
  else get_cat_parent($parent->parent, $tax);
}

/**
 * Get tamplate part to variable
 * @param  string $template_name
 * @param  string $part_name    
 * @return string               
 */
function load_template_part($template_name, $part_name = null) 
{
    ob_start();
    get_template_part($template_name, $part_name);
    $var = ob_get_contents();
    ob_end_clean();
    return $var;
}