<?php get_header(); 
$category 			= get_the_category($post->ID); 
$topParent_cat 		= get_post_top_parent();
$topParent_catSlug 	= get_cat_slug($topParent_cat);
$Parent_cat 		= get_parent_cat_id();
$Parent_catSlug 	= get_cat_slug($Parent_cat);

if($OPTION['wps_shop_single_sidebar_enable']){
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


$attr_option 		= get_custom_field("add_attributes"); // attributes - simple or configurable price?
//collect options
$WPS_prodImg_effect	= $OPTION['wps_prodImg_effect'];

$accountReg		= get_page_by_title($OPTION['wps_pgNavi_regOption']);
$wps_shop_mode 	= $OPTION['wps_shop_mode'];
?>

	<div id="singleMainContent" class="clearfix">
		<?php  
		// are we using a sidebar?
		if($OPTION['wps_shop_single_sidebar_enable'] && is_sidebar_active('single_widget_area')){ ?>
		<div class="<?php echo $the_div_class;?>">
		<?php  }
			
			//display category breadcrumb?
			if($OPTION['wps_catTrail_enable']) { ?>
				<span class="catBreadcrumb noprint">
					<?php echo(get_category_parents($category[0]->term_id, TRUE, ' &raquo; ')); ?>
				</span>
			<?php } 
			
			// "added to wishlist or cart" - feedback 
			if($_GET['added'] == 'OK'){
				$basket_url = get_option('home') .'?showCart=1&cPage='. current_page(3);
				//$basket_url = get_option('home') . '/index.php?showCart=1';	//or this as alternative		
				if($_GET['l'] == 'cart'){
					if($wps_shop_mode =='Inquiry email mode'){ ?>
						<div class='success'><?php printf(__ ('Your item has been successfully added to your %s!','wpShop'), $OPTION['wps_pgNavi_inquireOption'])?><a href="<?php echo $basket_url;?>"><?php printf(__ (' View %s','wpShop'), $OPTION['wps_pgNavi_inquireOption'])?></a> | <a href="<?php the_permalink(); ?>"><?php _e('Continue Shopping','wpShop');?></a></div>
						
					<?php } elseif ($wps_shop_mode=='Normal shop mode'){ ?>
						<div class='success'><?php printf(__ ('Your item has been successfully added to your %s!','wpShop'), $OPTION['wps_pgNavi_cartOption'])?><a href="<?php echo $basket_url;?>"><?php printf(__ (' View %s','wpShop'), $OPTION['wps_pgNavi_cartOption'])?></a> | <a href="<?php the_permalink(); ?>"><?php _e('Continue Shopping','wpShop');?></a></div>
					<?php } else {}
				}
				if($_GET['l'] == 'wl'){
					$customerArea	= get_page_by_title($OPTION['wps_customerAreaPg']);
					$wishlist_url = get_permalink($customerArea->ID)."?myaccount=1&action=3"; ?>
					<div class='success'><?php printf(__ ('Your item has been successfully added to your %s!','wpShop'), $OPTION['wps_wishListLink_option'])?><a href="<?php echo $wishlist_url;?>"><?php printf(__ (' View %s','wpShop'), $OPTION['wps_wishListLink_option'])?></a> | <a href="<?php the_permalink(); ?>"><?php _e('Continue Shopping','wpShop');?></a></div>
				<?php }
			} 
			//change.9.9
			elseif($_GET['added'] == 'NOK') {
				$basket_url = get_option('home') .'?showCart=1&cPage='. current_page(3);
				if($_GET['l'] == 'cart'){
					if($wps_shop_mode =='Inquiry email mode'){ ?>
						<p class="success"><?php printf(__ ('Since this item is out of stock it has not been added to your %s - ','wpShop'), $OPTION['wps_pgNavi_inquireOption'])?><a href="<?php echo $basket_url;?>"><?php printf(__ (' View %s','wpShop'), $OPTION['wps_pgNavi_inquireOption'])?></a></p>
						
					<?php } elseif ($wps_shop_mode=='Normal shop mode'){ ?>
						<p><span class="success"><?php printf(__ ('Since this item is out of stock it has not been added to your %s - ','wpShop'), $OPTION['wps_pgNavi_cartOption'])?><a href="<?php echo $basket_url;?>"><?php printf(__ (' View %s','wpShop'), $OPTION['wps_pgNavi_cartOption'])?></a></span></p>
					<?php } else {}
				}		
			}
			else {}
			//\change.9.9
			?>
			
			<div class="imgSection">
				<?php the_post();
				
				// Set the post content to a variable
				$subject = $post->post_content;
				// Look for embeded videos or audio
				$pattern  = '/\[(\w*)\sid="([^"]+)"\swidth="([^"]+)"\sheight="([^"]+)"]/';
				// Run preg_match_all to grab all videos and save the results in $videoMatches
				preg_match_all( $pattern , $subject, $videoMatches); 
				 
				// display link to parent category?
				if($OPTION['wps_backLink_enable']) { 
					include (TEMPLATEPATH . '/includes/single/singleProdBackLink.php');
				}
				
				// IMAGES START
				//let's collect our attachments
				$data		= my_attachment_data(0);
				//count them
				$num 		= count($data);

				if($num > 0 || strlen(get_custom_field('image_thumb', FALSE))>0){
						
					// which effect?
					switch($WPS_prodImg_effect){
						case 'mz_effect': 
							include (TEMPLATEPATH . '/includes/single/mz_effect.php');
						break;
						
						case 'mzp_effect': 
							include (TEMPLATEPATH . '/includes/single/mzp_effect.php');
						break;
						
						case 'jqzoom_effect': 
							include (TEMPLATEPATH . '/includes/single/jqzoom_effect.php');
						break;
						
						case 'lightbox':
							include (TEMPLATEPATH . '/includes/single/lightbox.php');
						break;
						
						case 'no_effect': 
							include (TEMPLATEPATH . '/includes/single/no_effect.php');
						break;
					} 
					
				} else { ?>
					<p class="error">
						<?php _e('Oops! No Product Images were found. Make sure you have attached at least 1 image or you use the image_tumb custom field correctly. If you have attached some and they don\'t appear make sure that WordPress recognizes them as Post Attachments! Go to your Media Library, spot the image that should have been here and see if it is indeed attached to this post. If not please follow the instructions, in the Documentation provided, on how to upload images to your posts. Thanks.','wpShop'); ?><br/>
					</p>
				<?php }
				// IMAGES END
				
				// YOU MAY ALSO LIKE and RECENTLY ADDED
				if($OPTION['wps_relatedProds_enable']) {
					include (TEMPLATEPATH . '/includes/single/singleRelatedProds.php');
				} ?>
				
			</div><!-- imgSection -->
			
			<div class="prodDetails">
				
				<?php 
				if($OPTION['wps_prodNav_enable']) {
					include (TEMPLATEPATH . '/includes/single/singleProdNavi.php');
				} ?>
				
				<h1 class="prod-title"><?php the_title(); ?></h1>
				
				<?php
				//the ratings
				if($OPTION['wps_multiProd_rate_enable'] && function_exists('the_ratings')) { the_ratings(); }
				?>
				
				<div <?php post_class('single_post clearfix'); ?> id="post-<?php the_ID();
				$custom_fields = get_post_custom(the_ID); ?>">
					<?php 
						if (!empty($videoMatches[1][0])) { 
							echo filter_img_from_descr($pattern, $subject);
						} else {
							if ( in_category( 'tabbed-view' ))
							{  
							?>
							<div class="jwts_tabber" id="jwts_tab">
								<div class="jwts_tabbertab" title="Product Info">
									<h2><a href="#Product Info" name="advtab">Product Info</a></h2>
							<?php the_content('<p class="serif">'. __( 'Read the rest of this page &raquo;', 'wpShop' ) . '</p>'); ?>
									<div class="jwts_clearfix">&nbsp;</div>
								</div>
								<div class="jwts_tabbertab" title="Comments">
									<h2><a href="#Comments">Comments</a></h2>
									<div class="jwts_clearfix">&nbsp;</div>
									<div class="fb-comments" data-href="<?=get_permalink( );?>" data-num-posts="3" data-width="400"></div>
								</div>
								<?php if(isset($custom_fields['See It Worn'])) {?>
								<div class="jwts_tabbertab" title="See It Worn">
									<h2><a href="#See It Worn">See It Worn</a></h2>
									<div class="jwts_clearfix">&nbsp;</div>
									<div class="See-It-Worn"> <img src="<?php echo $custom_fields['See It Worn'][0];?>" width="513px" /></div>
								</div>
								<?php }?>
							</div>
							<div class="jwts_clr">&nbsp;</div>	 
							<?php 
							}
							else
							the_content('<p class="serif">'. __( 'Read the rest of this page &raquo;', 'wpShop' ) . '</p>'); 
							wp_link_pages(array('before' => '<p><strong>' . __( 'Pages:', 'wpShop' ) . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number'));
						}
					?>
				</div><!-- single_post -->
				
				<?php if(($OPTION['wps_emailFriend_enable']) || ($OPTION['wps_print_enable']) || ($OPTION['wps_share_enable']) || ($OPTION['wps_subscribe_enable'])){ 
					include (TEMPLATEPATH . '/includes/single/singleProdShare.php');
				} 

				include (TEMPLATEPATH . '/lib/pages/single_body.php'); 
				
				// comments anyone?
				if ('open' == $post-> comment_status) { comments_template('', true); } ?>
				
			</div><!-- prodDetails -->
			
			<?php
			// links at bottom?
			if ($OPTION['wps_linksBottom_enable']) { ?>
				<div class="linksBottom clearfix noprint">
					<?php 
					if($OPTION['wps_backLink_enable']) { 
						include (TEMPLATEPATH . '/includes/single/singleProdBackLink.php');
					}
					if($OPTION['wps_prodNav_enable']) {
						include (TEMPLATEPATH . '/includes/single/singleProdNavi.php');
					} ?>
				</div>
			<?php } 
			
		// are we using a sidebar?	
		if($OPTION['wps_shop_single_sidebar_enable'] && is_sidebar_active('single_widget_area')){ ?>
		</div><!-- narrow-->
		<?php include( TEMPLATEPATH . "/includes/single/shop-single-sidebar.php" );
		} ?>
		
	</div><!-- singleMainContent -->
	
	<div id="emailoverlay" class="overlay largeoverlay">
		<h2><?php echo $OPTION['wps_email_a_friend_title']; ?></h2>
		<p><?php echo $OPTION['wps_email_a_friend_text']; ?></p>
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
		<h2><?php echo $OPTION['wps_share_title']; ?></h2>
		<p><?php echo $OPTION['wps_share_text']; ?></p>
		<p class="ico clearfix share_ico">
			<a href="http://delicious.com/save?url=<?php the_permalink() ?>&amp;title=<?php the_title() ?>" rel="nofollow" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/delicious.png" alt="del.icio.us"/><?php _e('del.icio.us','wpShop'); ?></a>
			<a href="http://digg.com/submit?phase=2&amp;url=<?php the_permalink() ?>&amp;title=<?php the_title(); ?>" rel="nofollow" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/digg.png" alt="Digg"/><?php _e('Digg','wpShop'); ?></a>
			<a href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>" rel="nofollow" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/facebook.png" alt="Facebook"/><?php _e('Facebook','wpShop'); ?></a>
			<a href="http://www.mixx.com/submit?page_url=<?php the_permalink() ?>" rel="nofollow" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/mixx.png" alt="Mixx"/><?php _e('Mixx','wpShop'); ?></a>
			<a href="http://reddit.com/submit?url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>" rel="nofollow" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/reddit.png" alt="Reddit"/><?php _e('Reddit','wpShop'); ?></a>
			<a href="http://www.stumbleupon.com/submit?url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>" rel="nofollow" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/stumbleupon.png" alt="Stumbleupon"/><?php _e('Stumbleupon','wpShop'); ?></a>
			<a href="http://technorati.com/ping/?url=<?php the_permalink() ?>" rel="nofollow" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/technorati.png" alt="Technorati"/><?php _e('Technorati','wpShop'); ?></a>
			<a href="http://twitter.com/home?status=Reading: <?php the_title(); ?> <?php echo get_option('home'); ?>/s/<?php the_ID(); ?>" title="<?php _e('Twitter','wpShop'); ?>" rel="nofollow" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/twitter.png" alt="Twitter"/><?php _e('Twitter','wpShop'); ?></a>
		</p>
	</div><!-- shareoverlay -->
				
	<div id="subscribeoverlay" class="overlay">
		<h2><?php echo $OPTION['wps_subscribe_title']; ?></h2>
		<p><?php echo $OPTION['wps_subscribe_text']; ?></p>
		<p class="ico clearfix subscribe_ico">
			<a href="<?php echo $OPTION['wps_feedburner_rsslink']; ?>" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/rss.png" alt="Subscribe to the RSS feed"/></a>
			<a href="<?php echo $OPTION['wps_feedburner_emaillink']; ?>" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/mail.png" alt="Sign up for Email alerts"/></a>
			<a href="http://twitter.com/<?php echo $OPTION['wps_twitter']; ?>" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/twitterbird.png" alt="Follow on Twitter"/></a>
		</p>
	</div><!-- subscribeoverlay -->
	
	<div id="supplInfoOverlay" class="overlay">
		<div class="supplInfoWrap">
			<?php if(strlen(get_custom_field('supplementary_info_text', FALSE))>0) {
				get_custom_field('supplementary_info_text', TRUE);
			} ?>
		</div>
	</div><!-- sizesInfoOverlay -->
	
	<div id="shippingInfoOverlay" class="overlay">
		<div class="shippingInfoWrap">
			<h2><?php echo $OPTION['wps_shippingInfo_linkTxt'];?></h2>
			<p><?php echo $OPTION['wps_shipping_details']; ?></p>
		</div>
	</div><!-- shippingInfoOverlay -->
	
	<div id="wishListOverlay" class="overlay">
		<h2><?php _e('Keep track of your favorite items to buy later.','wpShop');?></h2>
		<p><?php _e('To use this convenient feature, sign in to your account using the form below.','wpShop');?></p>
		<h4><?php _e('Do not have an account yet?','wpShop');?> <a href="<?php echo get_permalink( $accountReg->ID ); ?>"><?php _e('Create one','wpShop');?></a></h4>
		<?php include TEMPLATEPATH.'/loginform.php'; ?>
	</div><!-- wishListOverlay -->
<?php get_footer();?>