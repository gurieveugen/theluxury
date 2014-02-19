<div <?php post_class('blog_post clearfix'); ?> id="post-<?php the_ID(); ?>">

	<?php if($OPTION['wps_date_enable']){ ?>
		<p class="date alignleft"><?php the_time($OPTION['date_format']); ?></p>
	<?php } 
	
	if($OPTION['wps_commentsNum_enable']){ ?>
		<p class="comments-link alignright"><?php comments_popup_link( __( '0', 'wpShop' ), __( '1', 'wpShop' ), __( '%', 'wpShop' ) ) ?></p>
	<?php } ?>
	
	<h2 class="entry-title blog-entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( __('Permalink to %s', 'wpShop'), the_title_attribute('echo=0') ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
	
	<div class="entry">
		<?php 
		$output = my_attachment_image(0, 'thumbnail', 'alt="' . $post->post_title . '"','return');
		if (strlen($output[img_path])>0) { ?>
															
			<a class="thumb_img alignleft" href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( __('Permalink to %s', 'wpShop'), the_title_attribute('echo=0') ); ?>">
				<?php my_attachment_image(0, 'thumbnail', 'alt="' . $post->post_title . '"'); ?>
			</a> 
		<?php } ?>
		
		<div class="clearfix teaser">
		 
			<?php  $wordLimit 	= $OPTION['wps_blogWordLimit'];
			include (TEMPLATEPATH . '/includes/category/blog-post-teaser.php');?>
		</div><!-- teaser -->
		
		<?php if($OPTION['wps_blog_cat_meta_enable']){ ?>
			<p class="meta">
				<?php the_tags( '<span class="post_tags">' . __('Tagged as: ', 'wpShop' ), ',' , '</span>' ); ?><span class="post_cats"><?php _e( 'Posted in: ', 'wpShop' ); ?><?php echo get_the_category_list(', '); ?></span>
			</p>
		<?php } ?>
		
	</div><!-- entry -->
</div><!-- post -->

