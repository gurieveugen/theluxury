<?php
switch($WPS_sidebar){
	case 'alignRight':
		$the_float_class 	= 'alignright';
	break;
	case 'alignLeft':
		$the_float_class 	= 'alignleft';
	break;
}

$the_div_class 	= 'sidebar blog_sidebar noprint '. $the_float_class; ?>

<div class="<?php echo $the_div_class;?>">
	<div class="padding">
		<?php if (is_sidebar_active('blog_category_widget_area') ) : dynamic_sidebar('blog_category_widget_area'); endif;	?>

	</div><!-- padding -->
</div><!-- category_sidebar -->