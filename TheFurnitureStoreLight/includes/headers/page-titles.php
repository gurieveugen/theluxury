<?php
$term      = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
$customTax = $term->taxonomy;
$customerArea	= get_page_by_title($OPTION['wps_customerAreaPg']);

$store_title = $OPTION['wps_store_pgs_title'];
if (is_category($OPTION['wps_sale_category'])) {
	$store_title = 'Sale';
}
$page_obj = $wp_query->get_queried_object();
// Page Titles. Brace yourself! (modify at your own risk)
if (is_page() && !$page_obj->post_parent) {
	$hide_title = get_post_meta($post->ID, 'hide_title', true);
	if (!strlen($hide_title)) {
?>
	<h1 class="page-title whereAmI">
		<?php 
		//	for the customer membership area if one is created
		if (($OPTION['wps_customerAreaPg']!='Select a Page') && (is_page($customerArea->post_title))) {
			switch($_GET[action]){
				case 1: ?>
					<a href="<?php echo get_permalink($customerArea->ID); ?>"><?php echo $OPTION['wps_customerAreaPg'];?></a><?php echo ' &raquo '; _e('Edit Login Info', 'wpShop');
				break;

				case 2: ?>
					<a href="<?php echo get_permalink( $customerArea->ID); ?>"><?php echo $OPTION['wps_customerAreaPg'];?></a><?php echo ' &raquo '; _e('My Information', 'wpShop');
				break;

				case 3: ?>
					<a href="<?php echo get_permalink( $customerArea->ID); ?>"><?php echo $OPTION['wps_customerAreaPg'];?></a><?php echo ' &raquo '; echo $OPTION['wps_wishListLink_option'];
				break;

				case 4: ?>
					<a href="<?php echo get_permalink( $customerArea->ID); ?>"><?php echo $OPTION['wps_customerAreaPg'];?></a><?php echo ' &raquo '; _e('', 'wpShop');
				break;

				case 5: ?>
					<a href="<?php echo get_permalink( $customerArea->ID); ?>"><?php echo $OPTION['wps_customerAreaPg'];?></a><?php echo ' &raquo '; _e('', 'wpShop');
				break;
				
				default:
					echo $OPTION['wps_customerAreaPg'];
				break;
			}
		
		//	for the customer membership area subpages if some exist	
		} elseif (($OPTION['wps_customerAreaPg']!='Select a Page') && (is_tree($customerArea->ID))) { ?> 
			<a href="<?php echo get_permalink( $customerArea->ID); ?>"><?php echo $OPTION['wps_customerAreaPg'];?></a> &raquo; <?php echo $currentPage_title; 
		//	for the search page
		} elseif (isset($_GET['filter-category'])) {
			echo $currentPage_title . get_filter_titles();
		// otherwise
		} else {
			if ($parentPage_title == $currentPage_title) { echo $currentPage_title;} else { ?> <a href="<?php echo get_permalink($post->post_parent); ?>" title="<?php echo $parentPage_title; ?>"><?php echo $parentPage_title;?></a> &raquo; <?php echo $currentPage_title; }
		} ?>
	</h1>
<?php
	}
} elseif (is_category()) { ?>
	<!--<h1 class="shop-cat-title whereAmI"><?php echo $store_title; if ($OPTION['wps_store_pgs_titleAlt']) { echo " &raquo; ";$string = get_category_parents($this_category->term_id, TRUE, ' &raquo; ');$newstring = substr($string, 0, -8);echo $newstring; }?></h1>-->
	<?php
} elseif (is_tag()) { ?>
	<!--<h1 class="tag-title whereAmI"><?php single_tag_title();?></h1>-->
<?php } elseif (taxonomy_exists($customTax)){ 
	$tax_obj = get_taxonomy($customTax ); ?>
	<h1 class="tag-title whereAmI"><?php _e('Shop by ','wpShop'); echo $tax_obj->label; ?>: <?php echo $term->name; ?></h1>
<?php } elseif (is_search()) { ?>
	<h1 class="search-title whereAmI"><?php _e('Search Results for: ','wpShop'); ?><?php the_search_query(); ?></h1>
<?php } elseif (is_404()) { ?>
	<h1 class="notFound-title whereAmI"><?php _e('We are Sorry!','wpShop'); ?></h1>
<?php 
}// end "Page Titles" section ?>