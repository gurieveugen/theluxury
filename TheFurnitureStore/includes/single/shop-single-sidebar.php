<?php
//collect the shop category options
$orderBy 	= $OPTION['wps_catNavi_orderbyOption'];
$order 		= $OPTION['wps_catNavi_orderOption'];
$include	= $OPTION['wps_catNavi_inclOption'];
$exclude	= $OPTION['wps_catNavi_exclOption'];
$titleLi	='';

switch($WPS_sidebar){
	case 'alignRight':
		$the_float_class 	= 'alignright';
	break;
	case 'alignLeft':
		$the_float_class 	= 'alignleft';
	break;
}

$the_div_class 	= 'sidebar single_sidebar noprint '. $the_float_class; ?>

<div class="<?php echo $the_div_class;?>">
	<div class="padding">
		<?php if (is_sidebar_active('single_widget_area') ) : dynamic_sidebar('single_widget_area'); endif;?>
	</div><!-- padding -->
</div><!-- category_sidebar -->