<?php
// ini_set('display_startup_errors',1);
// ini_set('display_errors',1);
// error_reporting(-1);
include (TEMPLATEPATH . '/wp-pagenavi.php'); 	

global $OPTION;	
global $post;
global $wpdb, $wp_query, $query_string;	
global $wp_taxonomies;

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
$selected_ids   = array();
$custom_taxs    = array('category', 'brand', 'colour', 'style', 'price', 'selection', 'size', 'ring-size');
$inventory      = "";
$img_str        = "";
$the_div_class  = "";

$all_cats 		= array(
	'all-handbags' => array(
		'taxonomy' => 'category',
		'field'    => 'slug',
		'terms'    => array('mens-handbags', 'womens-handbags'),
	),	
	'all-clothes'  => array(
		'taxonomy' => 'category',
		'field'    => 'slug',
		'terms'    => array('mens-clothes', 'womens-clothes'),
	),
	'all-jewelry'  => array(
		'taxonomy' => 'category',
		'field'    => 'slug',
		'terms'    => array('mens-jewelry', 'womens-jewelry'),
	),
	'all-shoes'    => array(
		'taxonomy' => 'category',
		'field'    => 'slug',
		'terms'    => array('mens-shoes', 'womens-shoes'),
	),
	'all-watches'  => array(
		'taxonomy' => 'category',
		'field'    => 'slug',
		'terms'    => array('mens-watches', 'womens-watches'),
	),
	'all-accessories' => array(
		'taxonomy' => 'category',
		'field'    => 'slug',
		'terms'    => array('men-accessories', 'women-accessories'),
	)
);


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

if(isset($_GET["search"]))
{
	foreach ($_GET["search"] as $key => $value) 
	{
		$tax_and_terms[str_replace("filter-", "", $value[0])][] = $value[1];
	}
	if($tax_and_terms)
	{		
		foreach ($tax_and_terms as $key => $value) 
		{		
			array_push($tax_query, array(
				'taxonomy'         => $key,
				'field'            => 'slug',
				'include_children' => false,				
				'terms'            => $value));
		}
	}
	$args['tax_query'] = $tax_query; 
}

if(isset($_GET['_escaped_fragment_']))
{
	parse_str($_GET['_escaped_fragment_'], $args);
	if(isset($args['psort']))
	{
		$_GET['psort'] = $args['psort'];
		unset($args['psort']);
	}
}
$args = array_merge($wp_query->query_vars, (array)$args);

if(isset($_GET["paged"]) && $_GET["paged"] != "") 
{	
	$args 					= clearArgs($_GET['args']);	
	$args['paged']          = $_GET["paged"];	
}

if(isset($_GET["ppp"]) && $_GET["ppp"] != '')
{
	$args['posts_per_page'] = $_GET["ppp"] != "all" ? $_GET["ppp"] : -1;	
}


if(isset($_GET['args']['s'])) $args['s'] = $_GET['args']['s'];
if(isset($_GET['s']) && $_GET['s'] != '') $args['s'] = $_GET['s'];

$args = product_sort_process($args);

if(count($args['tax_query']) == 1 && !is_array($args['tax_query'][0]['terms']))
{	
	$tax_key = $args['tax_query'][0]['taxonomy'] == 'category' ? 'category_name' : $args['tax_query'][0]['taxonomy'];
	$args[$tax_key] = $args['tax_query'][0]['terms'];	
	unset($args['tax_query']);
}

$args = removeParentCats($args);
if(count($args['tax_query']) > 1 || is_array($args['tax_query'][0]['terms']))
{
	$args['tax_query'][0]['operator'] = 'AND';
	$args['tax_query']['relation'] = 'AND';	
}

$args['post_status'] = 'publish';
if(isset($OPTION['custom_args'])) 
{
	$args = $OPTION['custom_args'];	
	unset($OPTION['custom_args']);
}

if(isset($all_cats[$post->post_name]))
{
	$args['tax_query'][] = $all_cats[$post->post_name];
	unset($args['pagename']);
	unset($args['name']);
}

query_posts($args);

if(isset($args['tax_query']) && is_array($args['tax_query']))
{
	foreach ($args['tax_query'] as $tax) 
	{
		if(is_array($tax['terms']))
		{
			foreach ($tax['terms'] as $term) 
			{
				$t = get_term_by('slug', $term, $tax['taxonomy']);
				if($t)
				{
					$selected_ids[] = $t->term_id;
					$t = null;
				}
			}	
		}
		else
		{
			$t = get_term_by('slug', $tax['terms'], $tax['taxonomy']);
			if($t)
			{
				$selected_ids[] = $t->term_id;
				$t = null;
			}
		}
	}
}

$GLOBALS['psort'] = isset($_GET['psort']) ? $_GET['psort'] : 'newest';
$GLOBALS['filter']['all_display_categories'] = get_all_categories_from_posts($args);
$GLOBALS['filter']['args']                   = $args;
$location_hash = clearArgs($GLOBALS['filter']['args']);
$location_hash = array_merge($location_hash, array('psort' => $GLOBALS['psort']));
// =========================================================
// Save default filters
// =========================================================
$queried_object       = get_queried_object();
$json['taxonomy']     = $queried_object->taxonomy;
$json['term_id']      = $queried_object->term_id;
$json['parents']      = getParentsIDs($queried_object->term_id, $queried_object->taxonomy);
$json['cat_ids']   = (isset($GLOBALS['filter']['all_display_categories'])) ? $GLOBALS['filter']['all_display_categories'] : '{}';
$json['args']         = clearArgs($args);
$json['hash']         = http_build_query($location_hash);
$json['psort']        = $GLOBALS['psort'];
$json['hidden_terms'] = getHidedTerms($GLOBALS['filter']['all_display_categories']);
$json['selected_ids'] = $selected_ids;	
$json['s']            = $args['s'];

?>
<script>
	var filter_properties = <?php echo json_encode($json); ?>;
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