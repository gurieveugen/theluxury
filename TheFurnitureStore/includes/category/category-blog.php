<?php get_header();
if($OPTION['wps_blog_cat_sidebar_enable']){
	$WPS_sidebar		= $OPTION['wps_sidebar_option'];
	switch($WPS_sidebar){
		case 'alignRight':
			$the_float_class 	= 'narrow alignleft';
		break;
		case 'alignLeft':
			$the_float_class 	= 'narrow alignright';
		break;
	}
} else {$the_float_class 	= 'wide';}
?>

	<div class="<?php echo $the_float_class;?>">
		<?php
		while (have_posts()) : the_post();
			include (TEMPLATEPATH . '/includes/category/blog-posts.php'); 	
		endwhile; 
		
		include (TEMPLATEPATH . '/wp-pagenavi.php'); 
		if(function_exists('wp_pagenavi')) { wp_pagenavi(); }
		?> 
	</div>
		
	<?php if($OPTION['wps_blog_cat_sidebar_enable']){
		include (TEMPLATEPATH . '/includes/category/blog-category-sidebar.php');     
	}	 

get_footer();?>