<?php
auth_redirect_theme_login();

auth(1);
global $OPTION;
/*

Template Name: New Additions

*/
get_header();

// database query - get customer data
$row = NWS_get_user_details();


// sidebar location
switch($OPTION['wps_sidebar_option']){
	case 'alignRight':
		$the_float_class 	= 'alignleft';
	break;
	case 'alignLeft':
		$the_float_class 	= 'alignright';
	break;
}

// recent additions
$display 			= 'Images';
$showposts 			= 15;
$img_size 			= 184;
$num_img_in_row 	= 5;
$wp_thumb 			= TRUE;
$scroll 			= TRUE;
$prod_title 		= TRUE;
$prod_price 		= TRUE;
$prod_btn 			= FALSE;
$param		= array(
				'category_name' 	=> 'reserved-bags',
				'showposts'			=> $showposts, 
				'caller_get_posts'	=> 1
			);
// query	
$my_catRecent_query = new wp_query($param);
$url 			= get_permalink( $new_additions->ID);
$the_div_class 	= 'new_additions '. $the_float_class;
?>
<div <?php post_class('page_post '.$the_div_class); ?> id="post-<?php the_ID(); ?>">
	<?php 
	// query	
	$my_catRecent_query = new wp_query($param);
	
	// do we have posts?	
	if($my_catRecent_query->have_posts()) 
	{
	
	// some html before the loop
	if ($scroll && $display == 'Images') { ?>
		<div class="new_additions_prods">
			<div class="new_additions_prods_scrollable">
				<ul <?php echo ($scroll ? '': 'class="widget_content_wrap clearfix"');?>>
	<?php }
		// set counter
		$counter = $num_img_in_row;
		$a = 1;	
		// run the loop 			
		while ($my_catRecent_query->have_posts()) 
		{ 	
			$my_catRecent_query->the_post(); 
			
			//get post id
			$postid = get_the_ID();
			$output 		= my_attachment_images(0,1);
			$imgNum 		= count($output);
			if($imgNum != 0){
				$imgURL		= array();
				foreach($output as $v){
					
					$img_src 	= $v;
					
					// do we want the WordPress Generated thumbs?
					if ($wp_thumb) {
						//get the file type
						$img_file_type = strrchr($img_src, '.');
						//get the image name without the file type
						$parts = explode($img_file_type,$img_src);
						// get the thumbnail dimmensions
						$width = get_option('thumbnail_size_w');
						$height = get_option('thumbnail_size_h');
						//put everything together
						$imgURL[] = $parts[0].'-'.$width.'x'.$height.$img_file_type;
					
					// no? then display the default proportionally resized thumbnails
					} else {
						$des_src 	= $OPTION['upload_path'].'/cache';							
						$img_file 	= mkthumb($img_src,$des_src,$img_size,'width');    
						$imgURL[] 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;	
					}
					
				} 
			}
			// put output together
			if($imgNum != 0){ 

				$permalink 		= get_permalink();
				$title_attr2	= the_title_attribute('echo=0');
				$title_attr		= str_replace("%s",the_title_attribute('echo=0'), __('Permalink to %s', 'wpShop'));
				$the_a_class 	= alternating_css_class($counter,$num_img_in_row,'first');
				
				echo "<li class='recent_prod_wrap $the_a_class' style='width:{$img_size}px'>
					<a href='$permalink' rel='bookmark' title='$title_attr'><img src='$imgURL[0]' alt='$title_attr2'/></a>"; 
					
					if($prod_title || $prod_price || $prod_btn){ ?>
						<div class="teaser">
							<?php if($prod_title){ ?>
								<h5 class="prod-title"><a href="<?php the_permalink(); ?>" title="<?php printf( __('Permalink to %s', 'wpShop'), the_title_attribute('echo=0') ); ?>" rel="bookmark"><?php the_title(); ?></a></h5>
							<?php }	
							$price = get_custom_field('price', FALSE);
							$new_price = get_custom_field('new_price', FALSE);
							if($prod_price && $price > 0) { ?>
								<p class="price_value">
									<?php if($new_price > 0) { ?>
										<span class="was price">
											<?php echo format_price($price * $_SESSION['currency-rate'], true); ?>
										</span>
										<span class="is price">
											<?php echo format_price($new_price * $_SESSION['currency-rate'], true); ?>
										</span>
									<?php } else { ?>
										<span class="price solo"><?php echo format_price($price * $_SESSION['currency-rate'], true); ?></span>
									<?php }	?>
								</p><!-- price_value -->
							<?php }	
							if($OPTION['wps_shoppingCartEngine_yes'] && $prod_btn && strlen(get_custom_field2($postid, 'disable_cart', FALSE))==0){ 
								// shop mode
								$wps_shop_mode 	= $OPTION['wps_shop_mode'];
								
								if($wps_shop_mode =='Inquiry email mode'){ ?>
									<span class="shopform_btn add_to_enquire_alt"><a href="<?php the_permalink(); ?>"><?php printf(__ ('Add to %s!','wpShop'), $OPTION['wps_pgNavi_inquireOption'])?></a></span>
								<?php } elseif ($wps_shop_mode=='Normal shop mode' && !is_it_affiliate()){ ?>
									<span class="shopform_btn add_to_cart_alt"><a  href="<?php the_permalink(); ?>"><?php printf(__ ('Add to %s!','wpShop'), $OPTION['wps_pgNavi_cartOption'])?></a></span>
								<?php } elseif ($wps_shop_mode=='affiliate_mode' || is_it_affiliate()){ ?>
									<span class="shopform_btn buy_now_alt"><a href="<?php get_custom_field('buy_now', TRUE); ?>" <?php if($OPTION['wps_affili_newTab']) { ?> title="<?php _e('Opens is new tab','wpShop'); ?>" target="_blank"<?php } ?>><?php _e('Buy Now','wpShop'); ?></a></span>
								<?php } else {}
							
							} ?>
						</div>
					<?php }
				echo "</li>";									
				$a++;
				$counter++;
				
			// no attachments? pull image from custom field
			} elseif(strlen(get_custom_field2($postid,'image_thumb', FALSE))>0) { 
				$permalink 		= get_permalink();
				$title_attr2	= the_title_attribute('echo=0');
				$title_attr		= str_replace("%s",the_title_attribute('echo=0'), __('Permalink to %s', 'wpShop'));
				
				// resize the image.
				$img_src 		= get_custom_field2($postid,'image_thumb', FALSE);
				$des_src 		= $OPTION['upload_path'].'/cache';								
				$img_file 		= mkthumb($img_src,$des_src,$img_size,'width');    
				$imgURL 		= get_option('siteurl').'/'.$des_src.'/'.$img_file;	
				
				// do we want the WordPress Generated thumbs?
				if ($wp_thumb) {
					//get the file type
					$img_file_type = strrchr($img_src  , '.');
					//get the image name without the file type
					$parts = explode($img_file_type,$img_src);
					// get the thumbnail dimmensions
					$width = get_option('thumbnail_size_w');
					$height = get_option('thumbnail_size_h');
					//put everything together
					$imgURL = $parts[0].'-'.$width.'x'.$height.$img_file_type;
				}
				
				$the_a_class 	= alternating_css_class($counter,$num_img_in_row,'first');
				
				echo "<li class='recent_prod_wrap $the_a_class' style='width:{$img_size}px'>
					<a href='$permalink' rel='bookmark' title='$title_attr'><img src='$imgURL' alt='$title_attr2'/></a>"; 
					
					if($prod_title || $prod_price || $prod_btn){ ?>
						<div class="teaser">
							<?php if($prod_title){ ?>
								<h5 class="prod-title"><a href="<?php the_permalink(); ?>" title="<?php printf( __('Permalink to %s', 'wpShop'), the_title_attribute('echo=0') ); ?>" rel="bookmark"><?php the_title(); ?></a></h5>
							<?php }	
							$price = get_custom_field('price', FALSE);
							$new_price = get_custom_field('new_price', FALSE);
							if($prod_price && $price > 0) { ?>
								<p class="price_value">
									<?php if($new_price > 0) { ?>
										<span class="was price">
											<?php echo format_price(get_custom_field2($postid,'price'), true);?></span>
										<span class="is price">
											<?php echo format_price(get_custom_field2($postid,'new_price'), true); ?>
										</span>
									<?php } elseif(strlen(get_custom_field2($postid,'price', FALSE))>0){ ?>
										<span class="price solo"><?php echo format_price(get_custom_field2($postid,'price'), true); ?></span>
									<?php }	?>
								</p><!-- price_value -->
							<?php }	
							if($OPTION['wps_shoppingCartEngine_yes'] && $prod_btn && strlen(get_custom_field2($postid, 'disable_cart', FALSE))==0){ 
								// shop mode
								$wps_shop_mode 	= $OPTION['wps_shop_mode'];
								
								if($wps_shop_mode =='Inquiry email mode'){ ?>
									<span class="shopform_btn add_to_enquire_alt"><a href="<?php the_permalink(); ?>"><?php printf(__ ('Add to %s!','wpShop'), $OPTION['wps_pgNavi_inquireOption'])?></a></span>
								<?php } elseif ($wps_shop_mode=='Normal shop mode' && !is_it_affiliate()){ ?>
									<span class="shopform_btn add_to_cart_alt"><a  href="<?php the_permalink(); ?>"><?php printf(__ ('Add to %s!','wpShop'), $OPTION['wps_pgNavi_cartOption'])?></a></span>
								<?php } elseif ($wps_shop_mode=='affiliate_mode' || is_it_affiliate()){ ?>
									<span class="shopform_btn buy_now_alt"><a href="<?php get_custom_field('buy_now', TRUE); ?>" <?php if($OPTION['wps_affili_newTab']) { ?> title="<?php _e('Opens is new tab','wpShop'); ?>" target="_blank"<?php } ?>><?php _e('Buy Now','wpShop'); ?></a></span>
								<?php } else {}
							
							} ?>
						</div>
					<?php }
				echo "</li>";
				$a++;
				$counter++;
				 
			// no images altogether? Let them know!
			} else {
				echo "<li class='recent_prod_wrap' style='width:{$img_size}px' ><img src='' alt='Oops! No Product Images were found.' />".the_title()."</li>";
			}
					
		} // end for query
		wp_reset_query();
		if ( $display == 'Images' ) { 
				echo "</ul'>";
				?>
				</div><!--scrollable-->
			</div><!--prods_scrollable_wrap-->
			<?php
			}
	} //end if query
	else 
	{
		$err_message 	= __('There\'s no Recent Products yet in this Category','wpShop');									
		echo "<p class='error'>$err_message</p>";
	}
	?>
	
</div><!-- page_post -->
	
<?php
get_footer(); 
?>