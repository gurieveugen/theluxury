<?php
/* Do NOT rename any of this. 
*
*  Please use the translation guidelines provided here: http://faq4you.sarah-neuber.de/read.php?ident=be794d175d577130ab658ce1a1f4ee2e
*  If you need to create additional ones please do so in your child theme's functions.php!
*
*/
##################################################################################################################################
// 												   CUSTOM - TAXONOMIES
##################################################################################################################################

add_action( 'init', 'create_my_taxonomies', 0 );

function create_my_taxonomies() {
	
	global $OPTION;
	
	if($OPTION['wps_shopByOutfit_option']) {
		register_taxonomy( __('outfit', 'wpShop'), 'post', array( 'hierarchical' => false, 'label' => __('Outfits', 'wpShop'), 'query_var' => __('outfit', 'wpShop'), 'rewrite' => array( 'slug' => __('outfits', 'wpShop')  ) ) );
	}
	if($OPTION['wps_shopByFit_option']) {
		register_taxonomy( __('fit', 'wpShop'), 'post', array( 'hierarchical' => false, 'label' => __('Fits', 'wpShop'), 'query_var' => __('fit', 'wpShop'), 'rewrite' => array( 'slug' => __('fits', 'wpShop') ) ) );
	}
	if($OPTION['wps_shopByColour_option']) {
		register_taxonomy( __('colour', 'wpShop'), 'post', array( 'hierarchical' => false, 'label' => __('Colours', 'wpShop'), 'query_var' => __('colour', 'wpShop'), 'rewrite' => array( 'slug' => __('colours', 'wpShop') ) ) );
	}
	if($OPTION['wps_shopBySize_option']) {
		register_taxonomy( __('size', 'wpShop'), 'post', array( 'hierarchical' => false, 'label' => __('Sizes', 'wpShop'), 'query_var' => __('size', 'wpShop'), 'rewrite' => array( 'slug' => __('sizes', 'wpShop') ) ) );
		register_taxonomy( __('ring-size', 'wpShop'), 'post', array( 'hierarchical' => false, 'label' => __('Ring Sizes', 'wpShop'), 'query_var' => __('ring-size', 'wpShop'), 'rewrite' => array( 'slug' => __('ring-sizes', 'wpShop') ) ) );
		register_taxonomy( __('clothes-size', 'wpShop'), 'post', array( 'hierarchical' => true, 'label' => __('Clothes Sizes', 'wpShop'), 'query_var' => __('clothes-size', 'wpShop'), 'rewrite' => array( 'slug' => __('clothes-sizes', 'wpShop') ) ) );
	}
	if($OPTION['wps_shopBySelection_option']) {
		register_taxonomy( __('selection', 'wpShop'), 'post', array( 'hierarchical' => true, 'label' => __('Selections', 'wpShop'), 'query_var' => __('selection', 'wpShop'), 'rewrite' => array( 'slug' => __('selections', 'wpShop') ) ) );
	}
	if($OPTION['wps_shopByBrand_option']) {
		register_taxonomy( __('brand', 'wpShop'), 'post', array( 'hierarchical' => false, 'label' => __('Brands', 'wpShop'), 'query_var' => __('brand', 'wpShop'), 'rewrite' => array( 'slug' =>  __('brands', 'wpShop') ) ) );
	}
	if($OPTION['wps_shopByStyle_option']) {
		register_taxonomy( __('style', 'wpShop'), 'post', array( 'hierarchical' => false, 'label' => __('Styles', 'wpShop'), 'query_var' => __('style', 'wpShop'), 'rewrite' => array( 'slug' => __('styles', 'wpShop') ) ) );
	}
	if($OPTION['wps_shopByPrice_option']) {
		register_taxonomy( __('price', 'wpShop'), 'post', array( 'hierarchical' => false, 'label' => __('Prices', 'wpShop'), 'query_var' => __('price', 'wpShop'), 'rewrite' => array( 'slug' => __('prices', 'wpShop') ) ) );
	}
    $labels = array(
		'name' => __('Seller Categories'),
		'singular_name' => __('Seller Category'),
		'search_items' =>  __('Search Categories'),
		'all_items' => __('All Categories'),
		'parent_item' => __('Parent Category'),
		'parent_item_colon' => __('Parent Category:'),
		'edit_item' => __('Edit Category'), 
		'update_item' => __('Update Category'),
		'add_new_item' => __('Add New Category'),
		'new_item_name' => __('New Category Name'),
		'menu_name' => __('Seller Categories'),
    ); 	
	register_taxonomy('seller-category', 'post', array('hierarchical' => true, 'labels' => $labels, 'query_var' => true, 'rewrite' => true));

    $labels = array(
		'name' => __('Delivery Time'),
		'singular_name' => __('Delivery Time'),
		'search_items' =>  __('Search Delivery Time'),
		'all_items' => __('All Delivery Time'),
		'parent_item' => __('Parent Delivery Time'),
		'parent_item_colon' => __('Parent Delivery Time:'),
		'edit_item' => __('Edit Delivery Time'), 
		'update_item' => __('Update Delivery Time'),
		'add_new_item' => __('Add New Delivery Time'),
		'new_item_name' => __('New Delivery Time Name'),
		'menu_name' => __('Delivery Time'),
    ); 	
	register_taxonomy('delivery-time', 'post', array('hierarchical' => true, 'labels' => $labels, 'query_var' => true, 'rewrite' => true));
}

function get_the_outfit( $id = 0 ) {
    return apply_filters( 'get_the_outfit', get_the_terms( $id, __('outfit', 'wpShop') ) );
}

function get_the_fit( $id = 0 ) {
    return apply_filters( 'get_the_fit', get_the_terms( $id, __('fit', 'wpShop') ) );
}

function get_the_colour( $id = 0 ) {
    return apply_filters( 'get_the_colour', get_the_terms( $id, __('colour', 'wpShop') ) );
}

function get_the_size( $id = 0 ) {
    return apply_filters( 'get_the_size', get_the_terms( $id, __('size', 'wpShop') ) );
}
function get_the_brand( $id = 0 ) {
    return apply_filters( 'get_the_brand', get_the_terms( $id, __('brand', 'wpShop') ) );
}
function get_the_selection( $id = 0 ) {
    return apply_filters( 'get_the_selection', get_the_terms( $id, __('selection', 'wpShop') ) );
}
function get_the_style( $id = 0 ) {
    return apply_filters( 'get_the_style', get_the_terms( $id, __('style', 'wpShop') ) );
}
function get_the_price( $id = 0 ) {
    return apply_filters( 'get_the_price', get_the_terms( $id,__('price', 'wpShop') ) );
}


/* 
add new table colums on Edit Posts for our new taxonomies 
*/
if(($OPTION['wps_shopByOutfit_option']) || ($OPTION['wps_shopByFit_option']) || ($OPTION['wps_shopBySize_option']) || ($OPTION['wps_shopByColour_option']) || ($OPTION['wps_shopByBrand_option']) || ($OPTION['wps_shopBySelection_option']) || ($OPTION['wps_shopByStyle_option'])  || ($OPTION['wps_shopByPrice_option']) ) {
	add_filter('manage_posts_columns', 'NWS_columns');
	add_action('manage_posts_custom_column', 'NWS_custom_column', 10, 2);
}

function NWS_columns($defaults) {

	global $OPTION;
	
	if(($OPTION['wps_shopByOutfit_option']) || ($OPTION['wps_shopByFit_option']) || ($OPTION['wps_shopBySize_option']) || ($OPTION['wps_shopByColour_option']) || ($OPTION['wps_shopByBrand_option']) || ($OPTION['wps_shopBySelection_option']) || ($OPTION['wps_shopByStyle_option'])  || ($OPTION['wps_shopByPrice_option']) ) {
		$defaults['custom-tags'] = __('Custom Tags','wpShop');
	}
	return $defaults;
}

function NWS_custom_column($column_name, $post_id) {
    global $wpdb,$post,$OPTION;
	
	if(($OPTION['wps_shopByMaterial_option']) || ($OPTION['wps_shopByGemstone_option']) || ($OPTION['wps_shopByCollection_option']) || ($OPTION['wps_shopByOccasion_option']) ||($OPTION['wps_shopByOutfit_option']) || ($OPTION['wps_shopByFit_option']) || ($OPTION['wps_shopBySize_option']) || ($OPTION['wps_shopByColour_option']) || ($OPTION['wps_shopByBrand_option']) || ($OPTION['wps_shopBySelection_option']) || ($OPTION['wps_shopByStyle_option'])  || ($OPTION['wps_shopByPrice_option']) ) {
		if( $column_name == 'custom-tags' ) {
		
			if($OPTION['wps_shopByOutfit_option']) {
				$outfit_tags = get_the_outfit($post->ID);
				if ( !empty( $outfit_tags ) ) {
					$out = array();
					foreach ( $outfit_tags as $c )
					$out[] = "<a href='edit.php?".__('outfit', 'wpShop')."=$c->slug'> " . esc_html(sanitize_term_field('name', $c->name, $c->term_id, __('outfit', 'wpShop'), 'display')) . "</a>";
					echo join( ', ', $out );
				} else {
					_e('No Outfit Tags','wpShop');
				}
				echo "<br/>";
			}
			
			if($OPTION['wps_shopByFit_option']) {
				$fit_tags = get_the_fit($post->ID);
				if ( !empty( $fit_tags ) ) {
					$out = array();
					foreach ( $fit_tags as $c )
					$out[] = "<a href='edit.php?".__('fit', 'wpShop')."=$c->slug'> " . esc_html(sanitize_term_field('name', $c->name, $c->term_id, __('fit', 'wpShop'), 'display')) . "</a>";
					echo join( ', ', $out );
				} else {
					_e('No Fit Tags','wpShop');
				}
				echo "<br/>";
			}
			
			if($OPTION['wps_shopBySize_option']) {
				$size_tags = get_the_size($post->ID);
				if ( !empty( $size_tags ) ) {
					$out = array();
					foreach ( $size_tags as $c )
					$out[] = "<a href='edit.php?".__('size', 'wpShop')."=$c->slug'> " . esc_html(sanitize_term_field('name', $c->name, $c->term_id, __('size', 'wpShop'), 'display')) . "</a>";
					echo join( ', ', $out );
				} else {
					_e('No Size Tags','wpShop');
				}
				echo "<br/>";
			}
			
			if($OPTION['wps_shopByColour_option']) {
				$colour_tags = get_the_colour($post->ID);
				if ( !empty( $colour_tags ) ) {
					$out = array();
					foreach ( $colour_tags as $c )
					$out[] = "<a href='edit.php?".__('colour', 'wpShop')."=$c->slug'> " . esc_html(sanitize_term_field('name', $c->name, $c->term_id, __('colour', 'wpShop'), 'display')) . "</a>";
					echo join( ', ', $out );
				} else {
					_e('No Colour Tags','wpShop');
				}
				echo "<br/>";
			}
			
			if($OPTION['wps_shopByBrand_option']) {
				$brand_tags = get_the_brand($post->ID);
				if ( !empty( $brand_tags ) ) {
					$out = array();
					foreach ( $brand_tags as $c )
					$out[] = "<a href='edit.php?".__('brand', 'wpShop')."=$c->slug'> " . esc_html(sanitize_term_field('name', $c->name, $c->term_id, __('brand', 'wpShop'), 'display')) . "</a>";
					echo join( ', ', $out );
				} else {
					_e('No Brand Tags','wpShop');
				}
				echo "<br/>";
			}
			
			if($OPTION['wps_shopBySelection_option']) {
				$selection_tags = get_the_selection($post->ID);
				if ( !empty( $selection_tags ) ) {
					$out = array();
					foreach ( $selection_tags as $c )
					$out[] = "<a href='edit.php?".__('selection', 'wpShop')."=$c->slug'> " . esc_html(sanitize_term_field('name', $c->name, $c->term_id, __('selection', 'wpShop'), 'display')) . "</a>";
					echo join( ', ', $out );
				} else {
					_e('No Selection Tags','wpShop');
				}
				echo "<br/>";
			}
			
			if($OPTION['wps_shopByStyle_option']) {
				$style_tags = get_the_style($post->ID);
				if ( !empty( $style_tags ) ) {
					$out = array();
					foreach ( $style_tags as $c )
					$out[] = "<a href='edit.php?".__('style', 'wpShop')."=$c->slug'> " . esc_html(sanitize_term_field('name', $c->name, $c->term_id, __('style', 'wpShop'), 'display')) . "</a>";
					echo join( ', ', $out );
				} else {
					_e('No Style Tags','wpShop');
				}
				echo "<br/>";
			}
			
			if($OPTION['wps_shopByPrice_option']) {
				$price_tags = get_the_price($post->ID);
				if ( !empty( $price_tags ) ) {
					$out = array();
					foreach ( $price_tags as $c )
					$out[] = "<a href='edit.php?".__('price', 'wpShop')."=$c->slug'> " . esc_html(sanitize_term_field('name', $c->name, $c->term_id, __('price', 'wpShop'), 'display')) . "</a>";
					echo join( ', ', $out );
				} else {
					_e('No Price Tags','wpShop');
				}
			}
		}
	}
}
?>