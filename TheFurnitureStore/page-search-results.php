<?php
/*

Template Name: Search Results

*/
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

	include( TEMPLATEPATH . "/includes/search/search-shop.php" );
	
	include (TEMPLATEPATH . '/widget_ready_areas.php');		
get_footer(); ?>
		
