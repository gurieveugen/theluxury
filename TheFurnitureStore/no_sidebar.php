<?php 
/*

Template Name: No Sidebar

*/

get_header();

	if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div <?php post_class('page_post wide'); ?> id="post-<?php the_ID(); ?>">
			<?php the_content('<p class="serif">'. __( 'Read the rest of this page &raquo;', 'wpShop' ) . '</p>'); 
			wp_link_pages(array('before' => '<p><strong>' . __( 'Pages:', 'wpShop' ) . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
		</div><!-- page_post -->
	<?php endwhile; endif;

get_footer(); ?>