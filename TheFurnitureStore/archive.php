<?php get_header();

if (is_sidebar_active('archive_widget_area')) {
	$WPS_sidebar		= $OPTION['wps_sidebar_option'];
	switch($WPS_sidebar){
		case 'alignRight':
			$the_div_class 	= 'theArchive narrow alignleft';
		break;
		case 'alignLeft':
			$the_div_class 	= 'theArchive narrow alignright';
		break;
	}
} else {$the_div_class='theArchive wide';}
?>

	<div class="<?php echo $the_div_class;?>">
		<?php if (have_posts()) :
		
			// are we using a Blog?
			$blog_Name 	= $OPTION['wps_blogCat'];

			if ($blog_Name != 'Select a Category') {
				$blog_ID 	= get_cat_ID( $blog_Name );
				
				//collect the child categories
				$childrenCats 	= get_terms('category', 'parent='.$blog_ID);


				foreach ($childrenCats as $childrenCat) {
					$ChildCat 		= $childrenCat->term_id;
					$childString   .= $ChildCat.',';
				}
				
				$childCatString 	= substr($childString, 0, -1);
				

				global $query_string;
				if ($ChildCat) {
					$posts = query_posts($query_string . '&cat='.$blog_ID.','.$childCatString);
				} else {
					$posts = query_posts($query_string . '&cat='.$blog_ID);
				}	
				
			}
			
			while (have_posts()) : the_post(); ?>
				<div <?php post_class("clearfix archive_post"); ?> id="post-<?php the_ID(); ?>">
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
		
	</div><!-- theArchive -->
			
	<?php
	if ( is_sidebar_active('archive_widget_area')){ 
	
		switch($OPTION['wps_sidebar_option']){
			case 'alignRight':
				$the_float_class 	= 'alignright';
			break;
			case 'alignLeft':
				$the_float_class 	= 'alignleft';
			break;
		}
	?>
	
		<div class="sidebar noprint <?php echo $the_float_class;?>">
			<div class="padding">
				
				<?php dynamic_sidebar('archive_widget_area'); ?>
				
			</div><!-- padding -->
		</div><!-- sidebar -->
	<?php }
		
get_footer(); ?>