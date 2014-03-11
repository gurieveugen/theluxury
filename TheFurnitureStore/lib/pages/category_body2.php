<?php 
$attr_option 		= get_custom_field("add_attributes"); // attributes - simple or configurable price?
?>

<div class="contentWrap">
	<div class="holder">
		<div class="images">
	<?php 
	//get image attachments!
	if($imgNum != 0){ 
	
		if(($imgNum == 1) || ($OPTION['wps_hover_remove_option'])){ ?>
		
			<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( __('Permalink to %s', 'wpShop'), the_title_attribute('echo=0') ); ?>">
				<img src="<?php echo $imgURL[0];?>" alt="<?php the_title_attribute(); ?>"/>
			</a>
			
		<?php } else { ?>
		
			<a class="hover_link" href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( __('Permalink to %s', 'wpShop'), the_title_attribute('echo=0') ); ?>">
				<img src="<?php echo $imgURL[0];?>" alt="<?php the_title_attribute(); ?>"/>
			</a>
			<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( __('Permalink to %s', 'wpShop'), the_title_attribute('echo=0') ); ?>">
				<img src="<?php echo $imgURL[1];?>" alt="<?php the_title_attribute(); ?>"/>
			</a>
		
		<?php } 
		
		// no attachments? pull image from custom field
	} elseif(strlen(get_custom_field('image_thumb', FALSE))>0){ 
		// resize the image.
		$img_src 	= get_custom_field('image_thumb', FALSE);
		$des_src 	= $OPTION['upload_path'].'/cache';							
		$img_file 	= mkthumb($img_src,$des_src,$img_size,'width');    
		$imgURL 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;	
		
		// do we want the WordPress Generated thumbs?
		if ($OPTION['wps_wp_thumb']) {
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
		?>
		
		<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( __('Permalink to %s', 'wpShop'), the_title_attribute('echo=0') ); ?>">
			<img src="<?php echo $imgURL;?>" alt="<?php the_title_attribute(); ?>"/>
		</a>
	<?php 
	// no images altogether? Let them know!
	} else { ?>
		<p class="error">
			<?php _e('Oops! No Product Images were found. Please make sure you attach a minimum of 1 image to this product! If you have attached images and you still get this message please make sure that your "Miscellaneous Settings" (from the admin backend) are filled in correctly. Specifically the "Store uploads in this folder" should have the default path filled-in and the "Organize my uploads into month- and year-based folders" must be left unchecked!','wpShop'); ?>
		</p>
	<?php } ?>
	</div> <!-- .images-->
	<?php
	// do we want a teaser?
	if($OPTION['wps_teaser_enable_option']) { ?>
			
		<div class="teaser">
			<?php 
			//display prod title?
			if($OPTION['wps_prod_title']) { ?>
				<h5 class="prod-title"><a href="<?php the_permalink(); ?>" title="<?php printf( __('Permalink to %s', 'wpShop'), the_title_attribute('echo=0') ); ?>" rel="bookmark"><?php the_title(); ?></a></h5>
			<?php }
			
			if($OPTION['wps_teaser2_enable_option']) { 
			
				if(strlen(get_custom_field('item_remarks', FALSE))>0){ ?>
					<div class="item_description">
						<?php get_custom_field('item_remarks', TRUE); ?>
					</div>
				<?php }
			}
			
			//display prod price?
			if($OPTION['wps_prod_price'] && strlen(get_custom_field('price', FALSE))>0){ ?>
			
				<p class="price_value">
					
					
                    <?php
					//GW START
					
					if(isset($_SESSION['currency-code']))
					{
						//show converted
						if(strlen(get_custom_field('new_price', FALSE))>0){ ?>
							<span class="was price">
								<?php echo '<strong>Was:</strong> ' . format_price(get_custom_field('price') * $_SESSION['currency-rate']);?></span>
							<span class="is price">
								<?php if($attr_option == 2){_e('From: ','wpShop');} 
									echo format_price(get_custom_field('new_price') * $_SESSION['currency-rate']); 
									echo ' '. $_SESSION['currency-code']; 
								?>
							</span>
													
					<?php } elseif(strlen(get_custom_field('price', FALSE))>0){ ?>
													
							<span class="price solo"><?php if($attr_option == 2){_e('From: ','wpShop');} 
								echo format_price(get_custom_field('price') * $_SESSION['currency-rate']); 
								echo ' '. $_SESSION['currency-code'];?>
							</span>
													
					<?php }	
					}
					else
					{
						if(strlen(get_custom_field('new_price', FALSE))>0){ ?>
							<span class="was price">
								<?php if($OPTION['wps_currency_symbol'] !='') { echo $OPTION['wps_currency_symbol'];} echo format_price(get_custom_field('price'));?></span>
							<span class="is price">
								<?php if($attr_option == 2){_e('From: ','wpShop');} 
									if($OPTION['wps_currency_symbol'] !='') { echo $OPTION['wps_currency_symbol'];} echo format_price(get_custom_field('new_price')); 
									if($OPTION['wps_currency_code_enable']) { echo ' '. $OPTION['wps_currency_code']; }
									if($OPTION['wps_currency_symbol_alt'] !='') { echo " " . $OPTION['wps_currency_symbol_alt']; } ?>
							</span>
													
					<?php } elseif(strlen(get_custom_field('price', FALSE))>0){ ?>
													
							<span class="price solo"><?php if($attr_option == 2){_e('From: ','wpShop');} 
								if($OPTION['wps_currency_symbol'] !='') { echo $OPTION['wps_currency_symbol'];} echo format_price(get_custom_field('price')); 
								if($OPTION['wps_currency_code_enable']) { echo ' '. $OPTION['wps_currency_code']; }
								if($OPTION['wps_currency_symbol_alt'] !='') { echo " " . $OPTION['wps_currency_symbol_alt']; } ?></span>
													
					<?php }	
					}
					
					//GW END
					?>
				</p><!-- price_value -->
		
			<?php }
			
			//display add to basket btn?
			if($OPTION['wps_shoppingCartEngine_yes'] && $OPTION['wps_prod_btn'] && strlen(get_custom_field('disable_cart', FALSE))==0) { 
				// shop mode
				$wps_shop_mode 	= $OPTION['wps_shop_mode'];
				
				if($wps_shop_mode =='Inquiry email mode'){ ?>
					<span class="shopform_btn add_to_enquire_alt"><a href="<?php the_permalink(); ?>"><?php printf(__ ('Add to %s!','wpShop'), $OPTION['wps_pgNavi_inquireOption'])?></a></span>
				<?php } elseif ($wps_shop_mode=='Normal shop mode' && !is_it_affiliate()){ ?>
					<span class="shopform_btn add_to_cart_alt"><a  href="<?php the_permalink(); ?>"><?php printf(__ ('Add to %s!','wpShop'), $OPTION['wps_pgNavi_cartOption'])?></a></span>
				<?php } elseif ($wps_shop_mode=='affiliate_mode' || is_it_affiliate()){ ?>
					<span class="shopform_btn buy_now_alt"><a href="<?php get_custom_field('buy_now', TRUE); ?>" <?php if($OPTION['wps_affili_newTab']) { ?> title="<?php _e('Opens is new tab','wpShop'); ?>" target="_blank"<?php } ?>><?php _e('Buy Now','wpShop'); ?></a></span>
				<?php } else {}
			}
			
			if ($OPTION['wps_teaser2_enable_option'] != TRUE){
				if((strlen(get_custom_field('item_remarks', FALSE))>0) || ($out_of_stock === TRUE)){ ?>
						<p class="item_remarks">
							<?php if($out_of_stock === TRUE && $OPTION['wps_sold_out_enable']){  
								echo "<span class='sold_out'>$OPTION[wps_soldout_notice]</span>";
							} ?>
							<span><?php get_custom_field('item_remarks', TRUE); ?></span>
							
						</p><!-- item_remarks  -->
				<?php }	
			} 
			
			//if on a search page, do we want the post excerpt?
			if (is_search()) {
				if($OPTION['wps_search_teaser_option']) { 
					the_excerpt(); ?>
					<p class="read_more">
						<a href="<?php the_permalink(); ?>"><?php echo $OPTION['wps_readMoreLink']; ?></a>
					</p>
				<?php }
			} 
			
			//the ratings
			if($OPTION['wps_multiProd_rate_enable'] && function_exists('the_ratings')) { the_ratings(); }
			
			?>
		</div><!-- teaser  -->
		</div> <!--.holder-->
		<div class="price-box">
			<span class="discounts">30%<br /> off</span>
			<h3>Now: <strong>1,534 USD</strong></h3>
		</div>
	<?php } ?>
</div><!-- contentWrap  -->