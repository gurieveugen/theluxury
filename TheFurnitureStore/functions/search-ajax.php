<?php
// =========================================================
// HOOKS
// =========================================================
add_action("wp_ajax_search_products", "search_products_ajax");
add_action("wp_ajax_nopriv_search_products", "search_products_ajax");
add_action("wp_ajax_search_ajax_by_hash", "search_ajax_by_hash");
add_action("wp_ajax_nopriv_search_ajax_by_hash", "search_ajax_by_hash");
add_action("wp_ajax_change_ppp", "change_ppp_ajax");
add_action("wp_ajax_nopriv_change_ppp", "change_ppp_ajax");
add_action("wp_ajax_change_page", "change_page_ajax");
add_action("wp_ajax_nopriv_change_page", "change_page_ajax");
add_action("wp_ajax_change_sort", "change_sort_ajax");
add_action("wp_ajax_nopriv_change_sort", "change_sort_ajax");
add_action("wp_ajax_get_latest_products", "get_latest_products_ajax");
add_action("wp_ajax_nopriv_get_latest_products", "get_latest_products_ajax");
add_action('wp_enqueue_scripts', 'defaultScriptSettings');
add_action("wp_ajax_pageSortView", "pageSortView");
add_action("wp_ajax_nopriv_pageSortView", "pageSortView");

// =========================================================
// METHODS
// =========================================================
if(session_id() == "")
{
	session_start();
}

function defaultScriptSettings()
{
	wp_enqueue_script('search-ajax', get_bloginfo('template_url').'/js/search-ajax.js', array('jquery'));
	wp_localize_script('search-ajax', 'defaults', array(
		'page' => 1,
		'view' => get_option('posts_per_page'),
		'sort' => 'newest'
		));
}

function search_products()
{
	get_template_part('loop', 'products');
}

function search_products_ajax()
{		
	$_GET['ppp']   = $_GET['data']['view'];
	$_GET['psort'] = $_GET['data']['sort'];
	unset($_GET['data']);

	$json['loop']         = load_template_part('loop', 'products');	
	echo json_encode($json);
	die();
}

function search_ajax_by_hash()
{
	if(isset($_GET["search_hash"]))
	{
		$_GET["_escaped_fragment_"] = $_GET["search_hash"];
		get_template_part('loop', 'products');
		die();
	}
}

function pageSortView()
{
	$_GET['paged'] = $_GET['data']['page'];
	$_GET['ppp']   = $_GET['data']['view'];
	$_GET['psort'] = $_GET['data']['sort'];

	$json['loop'] = load_template_part('loop', 'products');
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
 * Clear ARGS before ajax return
 * @param  array $args --- properties
 * @return array
 */
function clearArgs($args)
{
	$fields = array('error', 'm', 'p', 'post_parent', 'subpost', 'subpost_id', 'attachment', 'attachment_id', 'name', 'static', 'pagename', 'page_id', 'second', 'minute', 'hour', 'day', 'monthnum', 'year', 'w', 'author', 'author_name', 'feed', 'tb', 'comments_popup', 'meta_key', 'meta_value', 'preview', 'sentence', 'fields', 'menu_order', 'ignore_sticky_posts', 'suppress_filters', 'cache_results', 'update_post_term_cache', 'update_post_meta_cache', 'post_type', 'nopaging', 'comments_per_page', 'no_found_rows', 'post_status');
	foreach ($fields as &$field) 
	{
		unset($args[$field]);
	}
	return $args;
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

/**
 * Set Cache
 * @param string  $key    
 * @param string  $val    
 * @param integer $time   
 * @param string  $prefix 
 */
function setCache($key, $val, $time = 3600, $prefix = 'cheched-')
{
	set_transient($prefix.$key, $val, $time);
}

/**
 * Get Cache
 * @param  string $key    
 * @param  string $prefix 
 * @return mixed
 */
function getCache($key, $prefix = 'cheched-')
{		
	$cached   = get_transient($prefix.$key);
	if (false !== $cached) return $cached;		
	return false;
}

function getSelectedTerms()
{
	$arr = null;
	if(isset($_GET['all']) && is_array($_GET['all']))
	{
		foreach ($_GET['all'] as $key => $value) 
		{
			$k = str_replace('filter-', '', $value[0]);			
			$arr[$k][] = get_term_by('slug', $value[1], $k);
		}	
	}
	else
	{
		$queried_object = get_queried_object();
		$arr[$queried_object->taxonomy][] = $queried_object;
	}	
	return $arr;
}

function getParents($id, $tax)
{
	$term  = get_term_by('id', $id, $tax);	
	$arr[] = $term;
	
	if($term->parent != 0) $arr = array_merge($arr, getParents($term->parent, $term->taxonomy));
	return $arr;
}

function getParentsIDs($id, $tax)
{
	$term  = get_term_by('id', $id, $tax);	
	$arr[] = $term->term_id;
	
	if($term->parent != 0) $arr = array_merge($arr, getParentsIDs($term->parent, $term->taxonomy));
	return $arr;
}

function filter_where( $where = '' ) 
{
 
    $week  = 7;
	$today = date('Y-m-d');

    $where .= " AND wp_posts.post_date <= '" . date('Y-m-d',strtotime($today) - (24*3600*$week)) . "'"; 
    return $where;
}
/**
 * Get term just by id only
 */
function get_term_by_id_only($term, $output = OBJECT, $filter = 'raw') 
{
    global $wpdb;
    $null = null;

    if(empty($term)) 
    {
        $error = new WP_Error('invalid_term', __('Empty Term'));
        return $error;
    }

    if (is_object($term) && empty($term->filter)) 
    {
        wp_cache_add($term->term_id, $term, 'my_custom_queries');
        $_term = $term;
    } 
    else 
    {
        if (is_object($term)) $term = $term->term_id;
        $term = (int) $term;
        if (!$_term = wp_cache_get($term, 'my_custom_queries')) 
        {
            $_term = $wpdb->get_row( $wpdb->prepare( "SELECT t.*, tt.* FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE t.term_id = %s LIMIT 1", $term) );
            if(!$_term) return $null;
            wp_cache_add($term, $_term, 'my_custom_queries');
        }
    }

    if ( $output == OBJECT ) 
    {
        return $_term;
    } 
    else if ($output == ARRAY_A) 
	{
        $__term = get_object_vars($_term);
        return $__term;
    } 
    else if ( $output == ARRAY_N ) 
    {
        $__term = array_values(get_object_vars($_term));
        return $__term;
    } 
    else 
    {
        return $_term;
    }
}

/**
 * OPTIMIZATION default wp function wp_get_object_terms
 * Just add DISTINCT in to MYSQL Query 
 */
function _wp_get_object_terms($object_ids, $taxonomies, $args = array()) 
{
	$hash_ids = md5(implode(',', $object_ids));
	$hash = getCache($hash_ids);
	if($hash !== false) return $hash;

	global $wpdb;

	if ( empty( $object_ids ) || empty( $taxonomies ) )
		return array();

	if ( !is_array($taxonomies) )
		$taxonomies = array($taxonomies);

	foreach ( (array) $taxonomies as $taxonomy ) {
		if ( ! taxonomy_exists($taxonomy) )
			return new WP_Error('invalid_taxonomy', __('Invalid taxonomy'));
	}

	if ( !is_array($object_ids) )
		$object_ids = array($object_ids);
	$object_ids = array_map('intval', $object_ids);

	$defaults = array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all');
	$args = wp_parse_args( $args, $defaults );

	$terms = array();
	if ( count($taxonomies) > 1 ) {
		foreach ( $taxonomies as $index => $taxonomy ) {
			$t = get_taxonomy($taxonomy);
			if ( isset($t->args) && is_array($t->args) && $args != array_merge($args, $t->args) ) {
				unset($taxonomies[$index]);
				$terms = array_merge($terms, wp_get_object_terms($object_ids, $taxonomy, array_merge($args, $t->args)));
			}
		}
	} else {
		$t = get_taxonomy($taxonomies[0]);
		if ( isset($t->args) && is_array($t->args) )
			$args = array_merge($args, $t->args);
	}

	extract($args, EXTR_SKIP);

	if ( 'count' == $orderby )
		$orderby = 'tt.count';
	else if ( 'name' == $orderby )
		$orderby = 't.name';
	else if ( 'slug' == $orderby )
		$orderby = 't.slug';
	else if ( 'term_group' == $orderby )
		$orderby = 't.term_group';
	else if ( 'term_order' == $orderby )
		$orderby = 'tr.term_order';
	else if ( 'none' == $orderby ) {
		$orderby = '';
		$order = '';
	} else {
		$orderby = 't.term_id';
	}

	// tt_ids queries can only be none or tr.term_taxonomy_id
	if ( ('tt_ids' == $fields) && !empty($orderby) )
		$orderby = 'tr.term_taxonomy_id';

	if ( !empty($orderby) )
		$orderby = "ORDER BY $orderby";

	$order = strtoupper( $order );
	if ( '' !== $order && ! in_array( $order, array( 'ASC', 'DESC' ) ) )
		$order = 'ASC';

	$taxonomies = "'" . implode("', '", $taxonomies) . "'";
	$object_ids = implode(', ', $object_ids);

	$select_this = '';
	if ( 'all' == $fields )
		$select_this = 't.*, tt.*';
	else if ( 'ids' == $fields )
		$select_this = 't.term_id';
	else if ( 'names' == $fields )
		$select_this = 't.name';
	else if ( 'slugs' == $fields )
		$select_this = 't.slug';
	else if ( 'all_with_object_id' == $fields )
		$select_this = 't.*, tt.*, tr.object_id';

	$query = "SELECT DISTINCT $select_this FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON tt.term_id = t.term_id INNER JOIN $wpdb->term_relationships AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tt.taxonomy IN ($taxonomies) AND tr.object_id IN ($object_ids) $orderby $order";

	if ( 'all' == $fields || 'all_with_object_id' == $fields ) {
		$_terms = $wpdb->get_results( $query );
		foreach ( $_terms as $key => $term ) {
			$_terms[$key] = sanitize_term( $term, $taxonomy, 'raw' );
		}
		$terms = array_merge( $terms, $_terms );
		update_term_cache( $terms );
	} else if ( 'ids' == $fields || 'names' == $fields || 'slugs' == $fields ) {
		$_terms = $wpdb->get_col( $query );
		$_field = ( 'ids' == $fields ) ? 'term_id' : 'name';
		foreach ( $_terms as $key => $term ) {
			$_terms[$key] = sanitize_term_field( $_field, $term, $term, $taxonomy, 'raw' );
		}
		$terms = array_merge( $terms, $_terms );
	} else if ( 'tt_ids' == $fields ) {
		$terms = $wpdb->get_col("SELECT tr.term_taxonomy_id FROM $wpdb->term_relationships AS tr INNER JOIN $wpdb->term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tr.object_id IN ($object_ids) AND tt.taxonomy IN ($taxonomies) $orderby $order");
		foreach ( $terms as $key => $tt_id ) {
			$terms[$key] = sanitize_term_field( 'term_taxonomy_id', $tt_id, 0, $taxonomy, 'raw' ); // 0 should be the term id, however is not needed when using raw context.
		}
	}

	if ( ! $terms )
		$terms = array();

	$val = apply_filters('wp_get_object_terms', $terms, $object_ids, $taxonomies, $args);
	set_transient($hash_ids, $val);
	return $val;
}

function get_all_categories_from_posts($args)
{		
	$defaults = array(
		'posts_per_page'     => -1,
		'fields'             => 'ids',
		'nopaging'           => true,
		'no_found_rows'      => true,
		'ignore_sticky_post' => true,
		'cache_results'      => false);	

	$args     = array_merge($args, $defaults);	
	$hash_key = md5(json_encode($args));	
	$cache    = getCache($has_key);
	if($cache !== false) return $cache;

	$ids   = get_posts($args);	
	$ids   = array_chunk($ids, 500);
	$terms = array();
	foreach ($ids as $values) 
	{
	 	$terms = $terms + (array)_wp_get_object_terms(
	 		$values, 
	 		array(
	 			'brand', 'price', 
	 			'colour', 'selection', 
	 			'category', 'clothes-size', 
	 			'size', 'ring-size'
	 		), 
	 		array('fields' => 'ids')
	 	);
	} 

	setCache($hash_key, $terms);

	return $terms;
}

function getHidedTerms($all_cats)
{
	$hidden = array();
	$arr = array();

	$selected_terms = getSelectedTerms();

	foreach ($all_cats as $key => $value) 
	{		
		if(is_array($value))
		{
			foreach ($value as $key2 => $value2) 
			{

				$arr[$key][] = (array)$value2['slug'];
			}	
		}
		
	}
	//var_dump('<pre>', $selected_terms, $arr, $all_cats, '</pre>');
	if(!$arr) return null;	
	if($selected_terms)
	{
		foreach ($selected_terms as $key => $value) 
		{			
			foreach ($value as $key2 => $value2) 
			{				
				if(!isset($arr[$key])) $hidden[$key][$value2->term_id] = $value2->slug;
				else if(!in_array($value2->slug, $arr[$key])) $hidden[$key][$value2->term_id] = $value2->slug;
			}
		}
	}
	else return null;

	return $hidden;
}


function removeParentCats($args)
{	
	$parents = array();
	if(!isset($args['tax_query'])) return $args;
	
	foreach ($args['tax_query'] as $key => $value) 
	{
		if(is_array($value))
		{
			if(is_array($value['terms']))
			{
				foreach ($value['terms'] as $key2 => $value2) 
				{
					
					$t = get_term_by('slug', $value2, $value['taxonomy']);
					if($t)
					{
						$taxs[$value['taxonomy']][] = $t;	
					}
					
				}
			}
			else
			{
				$taxs[$value['taxonomy']][] = get_term_by('slug', $value, $value['taxonomy']);
			}
		}
	}
	
	if(!isset($taxs['category'])) return $args;

	if(is_array($taxs['category']))
	{
		foreach ($taxs['category'] as $key => $value) 
		{			
			$parents[$value->term_id] = $value;			
		}
		if($parents)
		{
			foreach ($parents as $key => $value) 
			{
				unset($parents[$value->parent]);
			}
		}		

		$taxs['category'] = $parents;		
		$args['tax_query'] = arrayToTaxQuery($taxs);		
	}		
	return $args;
}

function arrayToTaxQuery($taxs)
{
	
	if($taxs)
	{		
		foreach ($taxs as $key => $terms) 
		{
			foreach ($terms as $term) 
			{
				$terms_slug[] = $term->slug;	
				$taxonomy = $term->taxonomy;			
			}

			$tax_query[] = array(
				'taxonomy'         => $key,
				'terms'            => $terms_slug,
				'field'            => 'slug',
				'include_children' => false);
			$terms_slug = array();
			
		}

		return $tax_query;
	}
	return false;
}


