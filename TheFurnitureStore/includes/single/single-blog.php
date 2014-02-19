<?php get_header(); 
global $OPTION;

$topParent_cat 		= get_post_top_parent();
$topParent_catSlug 	= get_cat_slug($topParent_cat);
$Parent_cat 		= get_parent_cat_id();
$Parent_catSlug 	= get_cat_slug($Parent_cat);

if($OPTION['wps_blog_single_sidebar_enable']){
	$WPS_sidebar = $OPTION['wps_sidebar_option'];
	switch($WPS_sidebar){
		case 'alignRight':
			$the_div_class 	= 'narrow alignleft';
		break;
		case 'alignLeft':
			$the_div_class 	= 'narrow alignright';
		break;
	}
} else {$the_float_class 	= 'wide';}


the_post();
 
?>

	<div id="singleBlogPost" class="clearfix">		
		<div class="<?php echo $the_div_class;?>">		
			<h1 class="entry-title"><?php the_title(); ?></h1>
			
			<div <?php post_class('single_blog_post clearfix'); ?> id="post-<?php the_ID(); ?>">
				<?php 
				the_content('<p class="serif">'. __( 'Read the rest of this page &raquo;', 'wpShop' ) . '</p>');
				wp_link_pages(array('before' => '<p><strong>' . __( 'Pages:', 'wpShop' ) . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); 
				?>
			</div><!-- single_post -->
			
			<?php if(($OPTION['wps_blogEmailFriend_enable']) || ($OPTION['wps_blogPrint_enable']) || ($OPTION['wps_blogShare_enable']) || ($OPTION['wps_blogSubscribe_enable'])){ 
				include( TEMPLATEPATH . "/includes/single/singleBlogShare.php" );
			} 
			
			if(($OPTION['wps_publish_enable']) || ($OPTION['wps_posted_enable']) || ($OPTION['wps_tagged_enable']) || ($OPTION['wps_prevNext_enable'])){ 
				include( TEMPLATEPATH . "/includes/single/singleBlogMeta.php" );
			}
			
			// comments anyone?
			if ('open' == $post-> comment_status) { comments_template('', true); } 
			?>
			
		</div>
		
		<?php 
		if($OPTION['wps_blog_single_sidebar_enable']) {include( TEMPLATEPATH . "/includes/single/blog-single-sidebar.php" );} ?>
	</div><!-- singleBlogPost -->
	
	<div id="emailoverlay" class="overlay largeoverlay">
		<h1><?php echo $OPTION['wps_blogEmail_a_friend_title']; ?></h1>
		<p><?php echo $OPTION['wps_blogEmail_a_friend_text']; ?></p>
		<?php 
		if(function_exists('is_tellafriend')){
			if(is_tellafriend( $post->ID )) insert_cform(2); 
		} else {
			echo "
			<p>
				No form?<br/>Install the cformsII plugin' .<br/>
				Available <a href='http://www.deliciousdays.com/cforms-plugin' target='_blank'>here</a>
			</p>
			";
		} ?>
	</div><!-- emailoverlay -->
	
	<div id="shareoverlay" class="overlay">
	
		<h1><?php echo $OPTION['wps_blogShare_title']; ?></h1>
		<p><?php echo $OPTION['wps_blogShare_text']; ?></p>
		<p class="ico clearfix share_ico">
			<a href="http://delicious.com/save?url=<?php the_permalink(); ?>&amp;title=<?php the_title() ?>" rel="nofollow" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/delicious.png" alt="del.icio.us"/><?php _e('del.icio.us','wpShop'); ?></a>
			<a href="http://digg.com/submit?phase=2&amp;url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>" rel="nofollow" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/digg.png" alt="Digg"/><?php _e('Digg','wpShop'); ?></a>
			<a href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>" rel="nofollow" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/facebook.png" alt="Facebook"/><?php _e('Facebook','wpShop'); ?></a>
			<a href="http://www.mixx.com/submit?page_url=<?php the_permalink(); ?>" rel="nofollow" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/mixx.png" alt="Mixx"/><?php _e('Mixx','wpShop'); ?></a>
			<a href="http://reddit.com/submit?url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>" rel="nofollow" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/reddit.png" alt="Reddit"/><?php _e('Reddit','wpShop'); ?></a>
			<a href="http://www.stumbleupon.com/submit?url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>" rel="nofollow" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/stumbleupon.png" alt="Stumbleupon"/><?php _e('Stumbleupon','wpShop'); ?></a>
			<a href="http://technorati.com/ping/?url=<?php the_permalink(); ?>" rel="nofollow" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/technorati.png" alt="Technorati"/><?php _e('Technorati','wpShop'); ?></a>
			<a href="http://twitter.com/home?status=Reading: <?php the_title(); ?> <?php echo get_option('home'); ?>/s/<?php the_ID(); ?>" title="<?php _e('Twitter','wpShop'); ?>" rel="nofollow" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/twitter.png" alt="Twitter"/><?php _e('Twitter','wpShop'); ?></a>
		</p>
	</div><!-- shareoverlay -->
				
	<div id="subscribeoverlay" class="overlay">
		<h1><?php echo $OPTION['wps_blogSubscribe_title']; ?></h1>
		<p><?php echo $OPTION['wps_blogSubscribe_text']; ?></p>
		<p class="ico clearfix subscribe_ico">
			<a href="<?php echo $OPTION['wps_blogFeedburner_rsslink']; ?>" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/rss.png" alt="Subscribe to the RSS feed"/></a>
			<a href="<?php echo $OPTION['wps_blogFeedburner_emaillink']; ?>" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/mail.png" alt="Sign up for Email alerts"/></a>
			<a href="http://twitter.com/<?php echo $OPTION['wps_blogTwitter']; ?>" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/twitterbird.png" alt="Follow on Twitter"/></a>
		</p>
	</div><!-- subscribeoverlay -->
<?php get_footer(); ?>