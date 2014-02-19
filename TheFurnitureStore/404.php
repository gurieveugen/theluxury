<?php get_header();
$WPS_sidebar		= $OPTION['wps_sidebar_option'];
switch($WPS_sidebar){
	case 'alignRight':
		$the_float_class 	= 'alignleft';
	break;
	case 'alignLeft':
		$the_float_class 	= 'alignright';
	break;
} ?>
	<div class="narrow <?php echo $the_float_class;?>">
				
		<p>
			<?php _e('The page you are looking for is no longer available or may have moved. If a bookmark brought you here, please update your information.','wpShop');?>
			<br/>
			<?php _e('To continue shopping, return to the ','wpShop');?><a href="<?php bloginfo( 'url' ); ?>/"><?php _e('Home page','wpShop');?></a>
		</p>
		
		<p><?php _e('Or perhaps searching might help.','wpShop');?></p>	
		<div class="main_col_searchform">
			<?php include (TEMPLATEPATH . '/searchform.php'); ?>
		</div><!-- main_col_searchform -->
	</div>			
	<?php include (TEMPLATEPATH . '/widget_ready_areas.php');	
get_footer(); ?>




