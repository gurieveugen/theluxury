<?php
/*

Template Name: Products Template

*/

get_header();

//collect the shop category options- same as the sidebar navigation
$orderBy 	= get_option('wps_catNavi_orderbyOption');
$order 		= get_option('wps_catNavi_orderOption');
$include	= get_option('wps_catNavi_inclOption');
$exclude	= get_option('wps_catNavi_exclOption');

//collect the main categories storring them in the $childrenCats variable for use in the categoryDisplay.php
$childrenCats = get_terms('category', 'orderby='.$orderBy.'&order='.$order.'&parent=0&hide_empty=0&exclude='.$exclude.'&include='.$include);

//sidebar?
$WPS_sidebar		= get_option('wps_sidebar_option');
switch($WPS_sidebar){
	case 'alignRight':
		$the_float_class 	= 'alignleft';
	break;
	case 'alignLeft':
		$the_float_class 	= 'alignright';
	break;
}

//what cat column option?
$WPS_catCol			= get_option('wps_catCol_option');
switch($WPS_catCol){
	case 'catCol3':
		$catCol_class = 'theCats3';
	break;
		
	case 'catCol4':
		$catCol_class = 'theCats4';
	break;
}

$the_div_class 	= 'narrow clearfix '.$catCol_class.' '.$the_float_class;
?>

	<div class="<?php echo $the_div_class;?>">
		<?php include (TEMPLATEPATH . '/includes/category/categoryDisplay.php');?>
	</div><!-- page_post -->
	
	<?php include (TEMPLATEPATH . '/widget_ready_areas.php');

get_footer(); ?>