<?php
 
//collect options
$WPS_prodCol		= $OPTION['wps_prodCol_option'];
$WPS_catCol			= $OPTION['wps_catCol_option'];
$WPS_sidebar		= $OPTION['wps_sidebar_option'];
$WPS_showposts		= $OPTION['wps_showpostsOverwrite_Option'];

$this_category 		= get_category($cat);

$topParent 			= NWS_get_root_category($cat,'allData');
$topParentSlug 		= $topParent->slug;
$this_categorySlug 	= $this_category->slug;

//collect options
$orderBy 	= $OPTION['wps_secondaryCat_orderbyOption'];
$order 		= $OPTION['wps_secondaryCat_orderOption'];

// sidebar location?
switch($WPS_sidebar){
	case 'alignRight':
		$the_float_class 	= 'alignleft';
	break;
	case 'alignLeft':
		$the_float_class 	= 'alignright';
	break;
}
// teaser?
if($OPTION['wps_teaser_enable_option']) {$the_eqcol_class = 'eqcol'; }

//what cat column option?
switch($WPS_catCol){
	case 'catCol3':
		$catCol_class = 'theCats3';
	break;
		
	case 'catCol4':
		$catCol_class = 'theCats4';
	break;
}

//what prod column option?
switch($WPS_prodCol){
	case 'prodCol1':
		$prodCol_class = 'theProds1';
	break;
	case 'prodCol2':
		$prodCol_class = 'theProds2';
	break;
	case 'prodCol3':
		$prodCol_class = 'theProds3';
	break;
	case 'prodCol4':
		$prodCol_class = 'theProds4';
	break;
}

//set the div class	
	$the_div_class 	= 'theProds clearfix '.$prodCol_class. ' '.$the_float_class.' '.$the_eqcol_class;
	
	if($OPTION['wps_catDescr_enable']) {
		echo term_description();
	} ?>

	<?php product_sort_select(); ?>
	<div class="<?php echo $the_div_class;?>" id="products-container">
		<?php search_products();
		
		//include (TEMPLATEPATH . '/includes/category/productDisplay.php'); 
		?>
	</div><!-- theProds -->
</div><!-- main_col -->
<?php

	include (TEMPLATEPATH . '/widget_ready_areas.php');
get_footer(); ?>