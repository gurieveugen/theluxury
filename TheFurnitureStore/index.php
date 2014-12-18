<?php 
$DIGITALGOODS = load_what_is_needed('digitalgoods');	//change.9.10
$DIGITALGOODS->delete_dlink();							//change.9.10

$featuredCont = $OPTION['wps_feature_option'];
$featuredEffect	= $OPTION['wps_featureEffect_option'];

// sidebar location?
$WPS_sidebar = $OPTION['wps_sidebar_option'];
switch($WPS_sidebar){
	case 'alignRight':
		$the_float_class 	= 'alignleft';
	break;
	case 'alignLeft':
		$the_float_class 	= 'alignright';
	break;
}

switch($featuredEffect){
	case 'innerfade_effect':
		$the_ul_id 		= 'innerfade_effect';
	break;
	case 'Slider_effect':
		$the_ul_id 		= 'slider';
	break;
	case 'cycle_effect':
		$the_ul_id 		= 'cycle';
	break;
}

if($OPTION['wps_front_sidebar_disable']) {
	$the_div_class 	= 'featured_wrap featured_wrap_alt';
	$the_div_id 	= 'main_col_alt';
} else {
	$the_div_class 	= 'featured_wrap alignleft';
	$the_div_id 	= 'main_col';
}
get_header(); ?>
	<div id="<?php echo $the_div_id;?>" class="<?php echo $the_div_class;?>">
		<ul id="<?php echo $the_ul_id;?>">
			<?php include (TEMPLATEPATH . '/includes/index/featured.php');?>
		</ul>
	</div><!-- main_col -->	
	<?php include (TEMPLATEPATH . '/widget_ready_areas.php');
get_footer(); ?>