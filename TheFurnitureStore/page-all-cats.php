<?php
/*
Template Name: All Cats
*/

$show_widget = true;
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
		
			<?php //set the counter according to the column selection from the theme options
			$a = 1;

			// allow user to order their Products as the want to
			$orderBy = $OPTION['wps_prods_orderbyOption'];
			$order 	 = $OPTION['wps_prods_orderOption'];
			$paged   = (get_query_var('paged')) ? get_query_var('paged') : 1;
			
			$cats = ($custom_cats != '') ? $_SESSION['custom_cats'] : 'womens-handbags';

			$args = array(
				'post_type'     => 'post',
				'orderby'       => $orderBy,
				'order'         => $order,				
				'paged'         => $paged,
				'category_name' => $cats
			);
			$_SESSION['show_latest_products'] = TRUE;
			$args                             = product_sort_process($args);	
			
			get_template_part('loop', 'products');
			?>
			
		</div><!-- theTags -->
	</div><!-- main_col -->		
<?php	
	include (TEMPLATEPATH . '/widget_ready_areas.php');		
get_footer(); ?>
		
