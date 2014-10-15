<?php
include (TEMPLATEPATH . '/wp-pagenavi.php'); 	


global $OPTION;	
global $post;
global $wpdb, $wp_query, $query_string;	

$WPS_tagCol     = $OPTION['wps_tagCol_option'];
$WPS_sidebar    = $OPTION['wps_sidebar_option'];
$a              = 1;
$str            = "";
$arr            = array();
$orderBy        = $OPTION['wps_prods_orderbyOption'];
$order          = $OPTION['wps_prods_orderOption'];
$paged          = (get_query_var('paged')) ? get_query_var('paged') : 1;
$posts_per_page = get_option('posts_per_page');	
$tax_query      = array();
$all_categories = array();
$custom_taxs    = array('category', 'brand', 'colour', 'style', 'price', 'selection', 'size', 'ring-size');
$inventory		= "";
$img_str 		= "";
$the_div_class  = "";



switch($OPTION['wps_sidebar_option'])
{
	case 'alignRight':
		$the_float_class 	= 'alignleft';
	break;
	case 'alignLeft':
		$the_float_class 	= 'alignright';
	break;
}

if($OPTION['wps_teaser_enable_option']) { $the_eqcol_class = 'eqcol'; }
//which column option?
switch($WPS_tagCol)
{
	case 'tagCol1':
		$the_div_class 	= 'theTags clearfix tagCol1 '.$the_float_class.' '.$the_eqcol_class;
		$counter = 1;      
	break;
	
	case 'tagCol2':
		$the_div_class 	= 'theTags clearfix tagCol2 '.$the_float_class.' '.$the_eqcol_class;
		$counter = 2;      
	break;
	
	case 'tagCol3':
		$the_div_class 	= 'theTags clearfix tagCol3 '.$the_float_class.' '.$the_eqcol_class;
		$counter = 3;      
	break;
		
	case 'tagCol4':
		$the_div_class 	= 'theTags clearfix tagCol4 '.$the_float_class.' '.$the_eqcol_class;
		$counter = 4;      
	break;
}

if(isset($_POST["search"]))
{
	foreach ($_POST["search"] as $key => $value) 
	{
		$arr[str_replace("filter-", "", $value[0])][] = $value[1];
		
	}
}

if($arr)
{
	if(isset($arr['psort']) && $arr['psort'][0] != "")
	{	
		$_SESSION["psort"]         = $arr['psort'][0];
		unset($arr['psort']);
	}
	if(isset($arr['ppp']) && $arr['ppp'][0] != "")
	{
		$_SESSION["prod_per_page"] = $arr['ppp'][0];
		unset($arr['ppp']);
	}
	if(isset($arr['paged']) && $arr['paged'][0] != "")
	{
		$paged = $arr['paged'][0];
		unset($arr['paged']);
	}

	foreach ($arr as $key => $value) 
	{
		$tax_query[] = array(
			'taxonomy' => $key,
			'field'    => 'slug',
			'terms'    => $value
			);
	}	
}


if (strlen($_SESSION['posts_per_page'])) 
{
	$posts_per_page = (int)$_SESSION['posts_per_page'];
}	
if(!isset($_GET['_escaped_fragment_']))
{
	$args = array(
		'orderby'             => $orderBy,		
		'order'               => $order,
		'paged'               => $paged,
		'posts_per_page'      => $posts_per_page,		
		'post_status'		  => 'publish'
	);

	$args = array_merge( $wp_query->query_vars, $args );

	if (count($tax_query) > 0) 
	{
		$tax_query['relation'] = 'AND';
		$args['tax_query']     = $tax_query;
	}
}
else
{
	parse_str($_GET['_escaped_fragment_'], $args);
	$args['tax_query']['relation'] = "AND";
	$args['post_status']           = "publish";
}


if($_SESSION["ppp"] != "") 
{
	$args                   = $_SESSION["old_args"];
	if($_SESSION["ppp"] != "all")
	{
		$args['posts_per_page'] = $_SESSION["ppp"];
		$_SESSION['posts_per_page'] = $_SESSION["ppp"];
	}
	else
	{
		$args['posts_per_page'] = -1;
		$_SESSION['posts_per_page'] = -1;
	}
	$_SESSION["ppp"]        = "";
}	

if($_SESSION["paged"] != "") 
{
	$args              = $_SESSION["old_args"];
	$args['paged']     = $_SESSION["paged"];
	$_SESSION["paged"] = "";
}	

if($_SESSION["new_psort"] != "")
{
	$_SESSION["psort"]     = $_SESSION["new_psort"];	
	$args                  = $_SESSION["old_args"];	
	$_SESSION["new_psort"] = "";
}
if($_GET['s'] != "")
{
	$args['s']     = $_GET['s'];
	$_SESSION["s"] = $_GET['s'];
}


if($_SESSION["get_default"])
{
	$args = $_SESSION["first_args"];
	$_SESSION["get_default"] = false;
}

$args      = product_sort_process($args);
if($_SESSION['custom_args'])
{
	$args = $_SESSION['custom_args'];
	$_SESSION['custom_args'] = NULL;
}

if(!isset($_SESSION['show_latest_products']))
{
	add_filter( 'posts_where', 'filter_where' );	
}
else
{
	unset($_SESSION['show_latest_products']);
}


query_posts($args);

global $wp_taxonomies;

$_SESSION["last_args"] = $args;
$_SESSION["old_args"]  = $args;

if(!$_SESSION["ajax"])
{
	$_SESSION["ajax"]       = true;
	$_SESSION["first_args"] = $args;	
}


$filter_cats                        = getFilterCats();
$_SESSION['all_display_categories'] = getEachBlock($filter_cats, $args);
$_SESSION['hidden_terms']           = getHidedTerms($args);


// =========================================================
// Save default filters
// =========================================================
$queried_object     = get_queried_object();
$json['taxonomy']   = $queried_object->taxonomy;
$json['term_id']    = $queried_object->term_id;
$json['categories'] = (isset($_SESSION['all_display_categories'])) ? $_SESSION['all_display_categories'] : '{}';
	
?>
<script>
	var default_categories = <?php echo json_encode($json); ?>;
</script>

<?php

if(have_posts())
{


	while (have_posts()) 
	{
		the_post();
		
		$output          = my_attachment_images(get_the_ID(), 2);
		$imgNum          = count($output);
		$currency_code   = $_SESSION['currency-code'];
		$currency_rate   = $_SESSION['currency-rate'];
		$price           = get_custom_field('price', FALSE);
		$new_price       = get_custom_field('new_price', FALSE);
		$post_udate      = strtotime(get_the_date());
		$now_date        = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
		$days_ago        = ceil(($now_date - $post_udate) / 86400);
		$attr_option     = get_custom_field("add_attributes");
		$post_selections = wp_get_post_terms(get_the_ID(), 'selection');
		
		//set the class and resize the product image according to the column selection from the theme options 
		switch($WPS_tagCol)
		{
			case 'tagCol1':
				$the_class 		= alternating_css_class($counter,1,' c_box_first');
				if($a==1) {$the_row_class='top_row';}else{$the_row_class='';}
				$the_div_class 	= 'c_box c_box1 '. $the_class .' '. $the_row_class;
				$img_size 		= $OPTION['wps_prodCol1_img_size'];
			break;
			
			case 'tagCol2':
				$the_class 		= alternating_css_class($counter,2,' c_box_first');
				if (($a==1) || ($a==2)) {$the_row_class='top_row';}else{$the_row_class='';}
				$the_div_class 	= 'c_box c_box2 '. $the_class .' '. $the_row_class;
				$img_size 		= $OPTION['wps_prodCol2_img_size'];
			break;
			
			case 'tagCol3':
				$the_class 		= alternating_css_class($counter,3,' c_box_first');
				if (($a==1) || ($a==2) || ($a==3)) {$the_row_class='top_row';}else{$the_row_class='';}
				$the_div_class 	= 'c_box c_box3 '. $the_class .' '. $the_row_class;
				$img_size 		= $OPTION['wps_prodCol3_img_size'];
			break;
													
			case 'tagCol4':
				$the_class 		= alternating_css_class($counter,4,' c_box_first');
				if (($a==1) || ($a==2) || ($a==3) || ($a==4)) {$the_row_class='top_row';}else{$the_row_class='';}
				$the_div_class 	= 'c_box c_box4 '. $the_class .' '. $the_row_class;
				$img_size 		= $OPTION['wps_prodCol4_img_size'];
			break;
		} 
		$post_class  = 'class="' . join( ' ', get_post_class( $the_div_class ) ) . '"';		

		?>
		<div <?php echo $post_class; ?> >
			<div class="contentWrap">
				<div class="holder">
					<div class="images">
						<?php
						if (!(int)get_item_inventory(get_the_ID()))
						{?>
							<span class="sold-out">Sold Out</span>
						<?php
						}	
						if($imgNum != 0)
						{
							$imgURL		= array();
							foreach($output as $v)
							{
							
								$img_src 	= $v;
								
								if ($OPTION['wps_wp_thumb']) // do we want the WordPress Generated thumbs?
								{
									$img_file_type = strrchr($img_src, '.'); //get the file type
									$parts         = explode($img_file_type,$img_src); //get the image name without the file type
									$width         = get_option('thumbnail_size_w'); // get the thumbnail dimmensions
									$height        = get_option('thumbnail_size_h');
									$imgURL[]      = $parts[0].'-'.$width.'x'.$height.$img_file_type; //put everything together
									
								// no? then display the default proportionally resized thumbnails
								} 
								else 
								{
									$des_src  = $OPTION['upload_path'].'/cache';	
									$img_file = mkthumb($img_src, $des_src, $img_size, 'width');
									$imgURL[] = get_option('siteurl').'/'.$des_src.'/'.$img_file;	
								}
						
							}

							if(($imgNum == 1) || ($OPTION['wps_hover_remove_option']))
							{?>
								<a href="<?php echo get_permalink() ?>" rel="bookmark" title="<?php echo sprintf( __('Permalink to %s', 'wpShop'), the_title_attribute('echo=0') ) ?>">
								<img src="<?php echo $imgURL[0] ?>" alt="<?php echo the_title_attribute('echo=0') ?>"/></a>
							<?php
							}
							else
							{?>
								<a class="hover_link" href="<?php echo get_permalink() ?>" rel="bookmark" title="<?php echo sprintf( __('Permalink to %s', 'wpShop'), the_title_attribute('echo=0') ) ?>">
								<img src="<?php echo $imgURL[0] ?>" alt="<?php echo the_title_attribute('echo=0') ?>"/></a>
								<a href="<?php echo get_permalink() ?>" rel="bookmark" title="<?php sprintf( __('Permalink to %s', 'wpShop'), the_title_attribute('echo=0') ) ?>">
								<img src="<?php echo $imgURL[1] ?>" alt="<?php echo the_title_attribute('echo=0') ?>"/></a>
							<?php
							}
						} 
						else if(strlen(get_custom_field('image_thumb', FALSE)) > 0)
						{
							// resize the image.
							$img_src 	= get_custom_field('image_thumb', FALSE);
							$des_src 	= $OPTION['upload_path'].'/cache';							
							$img_file 	= mkthumb($img_src,$des_src,$img_size,'width');    
							$imgURL 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;	
							
							// do we want the WordPress Generated thumbs?
							if ($OPTION['wps_wp_thumb']) 
							{
								
								$img_file_type = strrchr($img_src  , '.'); //get the file type							
								$parts         = explode($img_file_type, $img_src); //get the image name without the file type							
								$width         = get_option('thumbnail_size_w'); // get the thumbnail dimmensions
								$height        = get_option('thumbnail_size_h');							
								$imgURL        = $parts[0].'-'.$width.'x'.$height.$img_file_type; //put everything together
							}
							$img_src = '<a href="'.get_permalink().'" rel="bookmark" title="'.sprintf( __('Permalink to %s', 'wpShop'), the_title_attribute('echo=0') ).'">';
							$img_src.= '<img src="'.$imgURL.'" alt="'.the_title_attribute('echo=0').'"/></a>';
							
						}
						else
						{
							$img_src = '<p class="error">'.__('No Product Image.','wpShop').'</p>';
						}							
						?>
					</div><!-- images end -->
					<?php
					if($OPTION['wps_teaser_enable_option']) 
					{
						if (!$currency_rate) $currency_rate = 1; 
						?>
						<div class="teaser">
							<div class="prod-title-box">
								<h5 class="prod-title">
									<a href="<?php echo get_permalink() ?>" title="<?php echo sprintf( __('Permalink to %s', 'wpShop'), the_title_attribute('echo=0') ) ?>" rel="bookmark"><?php the_title() ?></a>
								</h5>
							</div><!-- prod-title-box end -->
							<?php
							if ($OPTION['wps_teaser2_enable_option']) 
							{ 
								if(strlen(get_custom_field('item_remarks', FALSE)) > 0) 
								{?>
									<div class="item_description"><?php echo get_custom_field('item_remarks', false); ?></div><!-- item_description -->
								<?php
								}
							} 
							?>
							<p class="price_value">
							<?php
							if ($new_price && $price) 
							{ 
								?>
								<span class="was price">Was: <?php product_prices_list($price) ?></span>
								<?php
							}
							?>					
							</p><!-- price_value -->
						</div><!-- teaser end -->
						<?php
					}
					?>
				</div><!-- holder end -->
				<?php
				if ($new_price) 
				{ ?>
					<div class="price-box">
					<?php
						if ($price > $new_price) { $perc = round(($price - $new_price) / ($price / 100));
						?> 
							<span class="discounts"><?php echo $perc ?>% off</span>
						<?php
						}
						?>
						<h3>
							Now: <?php product_prices_list($new_price)?></strong>
						</h3>
					</div><!-- price-box end -->
				<?php
				}
				else
				{
				?>
					<div class="price-box">
						<h3>
							<strong><?php product_prices_list($price) ?></strong>
						</h3>
					</div><!-- price-box end -->
					<?php
				}			
				if ($days_ago > 0 && $days_ago <= 30)
				{?>
					<span class="date-info">added <?php echo $days_ago ?> days ago</span>
				<?php
				}
				?>
			</div><!-- contentWrap end -->
			<?php
			if ($post_selections) 
			{
				foreach($post_selections as $post_selection) 
				{ 
					$item_selection = $post_selection->name; 
				} 
				$isarr   = explode(" ", strtolower($item_selection));
				$icon_lt = substr($isarr[0], 0, 1);
				if (count($isarr) > 1) 
				{
					$icon_lt .= substr($isarr[1], 0, 1);
				}
				?>
				<span class="ico-cond <?php echo $icon_lt ?>" title="<?php echo $item_selection ?>"></span>
			<?php
			}
			?>
		</div><!-- c_box -->
		<?php
		$counter++;
		$a++;
		 
	}
}
else
{
	?>
	<p style="text-align: center; padding: 60px;">There are currently no items available for your selection. To get notified when these items are added, use the 'Notify Me' button on the left.</p>
	<?php
}
?>
<div class="clear"></div><div class="nav-bottom-area"><?php wp_pagenavi()?></div>
<?php 
wp_reset_query();

function get_all_categories_from_posts($args)
{	
	$args['posts_per_page'] = 1000;
	$args['fields']         = 'ids';						
	$ids                    = get_posts($args);			
	$all_categories         = array();
	$taxonomies             = array('brand', 'price', 'colour', 'selection', 'category', 'clothes-size', 'size', 'ring-size');			
	$terms                  = _wp_get_object_terms($ids, $taxonomies, array('fields' => 'ids'));		
	
	foreach($terms as &$value)
	{		
		$term = get_term_by_id_only($value);						
		$all_categories[$term->taxonomy][$term->term_id]['name'] = $term->name;
		$all_categories[$term->taxonomy][$term->term_id]['slug'] = $term->slug;
		if($term->parent != 0)
		{
			$arr = getParents($term->parent, $term->taxonomy);			
			foreach ($arr as $key2 => $value2) 
			{
				$all_categories[$value2->taxonomy][$value2->term_id]['name'] = $value2->name;
				$all_categories[$value2->taxonomy][$value2->term_id]['slug'] = $value2->slug;
			}
		}
	}

	return $all_categories;
}

function getHidedTerms($args)
{
	$selected_terms = getSelectedTerms();

	foreach ($_SESSION['all_display_categories'] as $key => $value) 
	{
		if(is_array($value))
		{
			foreach ($value as $key2 => $value2) 
			{
				$arr[$key][] = $value2['slug'];
			}	
		}
		
	}

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

function getSelectedTerms()
{
	$arr = null;
	if(is_array($_POST['all']))
	{
		foreach ($_POST['all'] as $key => $value) 
		{
			$k = str_replace('filter-', '', $value[0]);			
			$arr[$k][] = get_term_by('slug', $value[1], $k);
		}	
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

function filter_where( $where = '' ) 
{
 
    $week  = 7;
	$today = date('Y-m-d');

    $where .= " AND wp_posts.post_date <= '" . date('Y-m-d',strtotime($today) - (24*3600*$week)) . "'"; 
    return $where;
}

function getFilterCats()
{
	$arr = array();
	if(isset($_POST['all']) && is_array($_POST['all']))
	{
		foreach ($_POST['all'] as $key => $value) 
		{
			$arr[str_replace('filter-', '', $value[0])][] = $value[1];
		}
	}
	return $arr;
}

function getFilterTaxQuery($arr)
{	
	if(is_array($arr))
	{
		foreach ($arr as $key => &$value) 
		{
			$tax_query[] = array(
				'taxonomy' => $key,
				'field'    => 'slug',
				'terms'    => $value);			
		}	
		if(count($tax_query)) $tax_query['relation'] = 'AND';
	}
	return $tax_query;
}

function getEachBlock($arr, $args)
{
	
	$all  = null;
	$taxs = array('price', 'colour', 'brand', 'category', 'selection');

	foreach ($taxs as $tax) 
	{
		$new_arr = $arr;
		unset($new_arr[$tax]);


		$args['tax_query'] = getFilterTaxQuery($new_arr);		
		$queried           = get_all_categories_from_posts($args);		
		$all[$tax]         = $queried[$tax];
	}
	
	return $all;
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
 * Just add DISTINCT int ot MYSQL Query 
 */
function _wp_get_object_terms($object_ids, $taxonomies, $args = array()) 
{
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

	return apply_filters('wp_get_object_terms', $terms, $object_ids, $taxonomies, $args);
}