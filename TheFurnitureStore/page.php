<?php get_header();
$DEFAULT = show_default_view();

 include (TEMPLATEPATH . '/lib/pages/index_body.php'); 

 if($DEFAULT){
	$WPS_sidebar		= $OPTION['wps_sidebar_option'];
	switch($WPS_sidebar){
		case 'alignRight':
			$the_float_class 	= 'alignleft';
		break;
		case 'alignLeft':
			$the_float_class 	= 'alignright';
		break;
	}

	if (have_posts()) : while (have_posts()) : the_post(); 

			if($post->post_parent) {
				$children = wp_list_pages("title_li=&child_of=".$post->post_parent."&parent=".$post->post_parent."&echo=0"); 
			} else {
				$children = wp_list_pages("title_li=&child_of=".$post->ID."&echo=0");
			}
			if (($children) || is_sidebar_active('about_page_widget_area') || is_sidebar_active('contact_page_widget_area') || is_sidebar_active('page_widget_area')) {$the_div_class = 'narrow '. $the_float_class;} else {$the_div_class = 'wide';}
	?>

		<div <?php post_class('page_post '.$the_div_class); ?> id="post-<?php the_ID(); ?>">
			<?php the_content('<p class="serif">'. __( 'Read the rest of this page &raquo;', 'wpShop' ) . '</p>'); 
			wp_link_pages(array('before' => '<p><strong>' . __( 'Pages:', 'wpShop' ) . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
		</div><!-- page_post -->
	<?php endwhile; endif;

	include (TEMPLATEPATH . '/widget_ready_areas.php');
} 
get_footer(); ?>