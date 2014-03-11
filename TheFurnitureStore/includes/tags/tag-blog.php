<?php
$WPS_sidebar		= $OPTION['wps_sidebar_option'];
switch($WPS_sidebar){
	case 'alignRight':
		$the_div_class 	= 'theArchive narrow alignleft';
	break;
	case 'alignLeft':
		$the_div_class 	= 'theArchive narrow alignright';
	break;
}

?>

	<div class="<?php echo $the_div_class;?>">
		<?php if (have_posts()) : 
			while (have_posts()) : the_post(); ?>
				<div <?php post_class("clearfix blogTag_post"); ?> id="post-<?php the_ID(); ?>">
					<p class="date alignleft"><?php the_time($OPTION['date_format']); ?></p>
					<h3 class="entry-title archive-entry-title clearfix">
						<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( __('Permalink to %s', 'wpShop'), the_title_attribute('echo=0') ); ?>"><?php the_title(); ?></a>
					</h3>
					
				</div><!-- archive_post -->
						
			<?php 
			endwhile; 
				include (TEMPLATEPATH . '/wp-pagenavi.php');
				if(function_exists('wp_pagenavi')) { wp_pagenavi(); }
			?>
				
		<?php else : ?>
			<h4><?php _e('Nothing found','wpShop');?></h4>
			<p><?php _e('Perhaps searching may help','wpShop');?></p>
			<div class="main_col_searchform">
				<?php include (TEMPLATEPATH . '/searchform.php'); ?>
			</div>
		<?php endif; ?>
		
	</div><!-- blogTag_post -->
			
	<?php
	include (TEMPLATEPATH . '/widget_ready_areas.php');
		
get_footer(); ?>