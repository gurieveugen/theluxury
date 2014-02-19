<?php get_header();
$WPS_tagCol			= $OPTION['wps_tagCol_option'];
$WPS_sidebar		= $OPTION['wps_sidebar_option'];
$term 				= get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
$customTax 			= $term->taxonomy;
if($term->taxonomy == 'brand')
{
	echo '<input type="hidden" id="is_open_brands" name="is_open_brands" value="yes" data-value="'.$term->taxonomy.'">';	
}
switch($WPS_sidebar){
	case 'alignRight':
		$the_float_class 	= 'alignleft';
	break;
	case 'alignLeft':
		$the_float_class 	= 'alignright';
	break;
}

	if($OPTION['wps_teaser_enable_option']) {$the_eqcol_class = 'eqcol'; }
	//which column option?
	switch($WPS_tagCol){
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
	} ?>
	<div id="main_col" class="<?php echo $the_float_class;?>">
	
		<?php $term_featured_image = get_field('featured_image', 'brand_'.$term->term_id);
		if ($term_featured_image) { ?>
		<div class="featuredTag">
			<img src="<?php echo $term_featured_image['url']; ?>" alt="<?php echo $term->name; ?>" />
		</div>
		<?php } ?>
		
		<?php  if($OPTION['wps_termDescr_enable']) {
			echo term_description();
		} ?>
		
		<?php
		$_SESSION["ajax"]       = false;
		product_sort_select(); ?>
		<div class="<?php echo $the_div_class;?>" id="products-container">
			<?php get_template_part('loop', 'products'); ?>		
		</div><!-- theTags -->
	</div><!-- main_col -->			
	<?php
	include (TEMPLATEPATH . '/widget_ready_areas.php');
		
get_footer(); ?>