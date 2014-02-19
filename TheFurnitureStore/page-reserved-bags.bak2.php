<?php
/*

Template Name: Reserved Bags

*/

check_logged_in();

get_header();
	$WPS_sidebar		= $OPTION['wps_sidebar_option'];
	switch($WPS_sidebar){
		case 'alignRight':
			$the_float_class 	= 'alignleft';
		break;
		case 'alignLeft':
			$the_float_class 	= 'alignright';
		break;
	}

	$WPS_tagCol			= $OPTION['wps_tagCol_option'];
	$WPS_sidebar		= $OPTION['wps_sidebar_option'];

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
	}
	
	?>
	
	<div id="main_col" class="<?php echo $the_float_class;?>">
	
		<?php product_sort_select(); ?>
		<div class="<?php echo $the_div_class;?>" id="products-container">
			<?php get_template_part('loop', 'products'); ?>
		</div><!-- theProds -->
	</div><!-- main_col -->		
<?php	
	include (TEMPLATEPATH . '/widget_ready_areas.php');		
get_footer(); ?>
		
