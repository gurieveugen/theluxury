<?php get_header(); ?>

<div class="whyUsWrap lfloat">
<?php
switch($OPTION['wps_sidebar_option']){
	case 'alignRight':
		$the_float_class 	= 'alignright';
	break;
	case 'alignLeft':
		$the_float_class 	= 'alignleft';
	break;
}
		
		if ($OPTION['wps_front_sidebar_disable'] != TRUE) {


			$the_div_class 	= 'sidebar frontPage_sidebar noprint '. $the_float_class; ?>

			<div class="<?php echo $the_div_class;?>">
				<div class="padding">
					<?php if ( is_sidebar_active('frontpage_widget_area') ) : dynamic_sidebar('frontpage_widget_area'); endif; ?>
				</div><!-- padding -->
			</div><!-- frontPage_sidebar -->
			
		<?php } ?>
	
	<div id="main_col" class="featured_wrap alignright">
		<?php the_content(); ?>
		<?php wp_nav_menu(array(
		'theme_location'  => 'add-menu',
		'menu'            => '',
		'menu_class'            => 'add-menu',
		'container_class' => 'row contentLinks',
		'depth'           => 1,
		'before'	=> '<span>·</span>'
	)); ?>
	</div>

</div>

<?php get_footer(); ?>