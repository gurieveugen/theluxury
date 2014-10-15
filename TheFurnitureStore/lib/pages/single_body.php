<?php
//display only if we have not disabled the Shopping Cart for this prod post
if(strlen(get_custom_field('disable_cart', FALSE))==0){

	$accountReg		= get_page_by_title($OPTION['wps_pgNavi_regOption']);

	// inventory control
	if($OPTION['wps_track_inventory'] == 'active'){

		$stock_amount = get_item_inventory($post->ID);	
		echo '<script>var tracking = "on";</script>';
	}
	else {
		echo '<script>var tracking = "off";</script>';
	}

						
	if(get_post_meta($post->ID, 'new_price', true)) { 
	$values 		= get_post_custom_values("new_price"); 
	$basis_price 	= $values[0]; 										
	} 
	elseif(get_post_meta($post->ID, 'price', true)){ 
	$values 		= get_post_custom_values("price"); 
	$basis_price 	= $values[0];	
	}

	// digital affiliate prod?
	if(is_it_digital() && is_it_affiliate()){ ?>
		<span class="alignleft affili_digi_product"><?php _e('Affiliate / Digital product','wpShop');?></span>
	<?php }

	// digital prod?
	elseif(is_it_digital()){ ?>

		<p class="digi_prod"><?php _e('Digital product','wpShop');?></p>
		
	<?php } 

	// affiliate prod?
	elseif(is_it_affiliate()) { ?>

		<p class="affili_prod"><?php _e('Affiliate product','wpShop');?></p>

	<?php } else {} ?>
 
	<p class="price_value clearfix">

		<?php 
		// otherwise display price
		$price = get_post_meta($post->ID, 'price', true);
		$new_price = get_post_meta($post->ID, 'new_price', true);
		if($attr_option != 2){
			if($new_price) { ?>
				<span class="was price"><?php echo format_price($price * $_SESSION['currency-rate'], true); ?></span>
				<span class="is price">
					<?php if ($OPTION['wps_prod_ID']) { ?><span class="alignright itemID"><?php get_custom_field('ID_item', TRUE); ?></span><?php } ?>
					<?php echo format_price($new_price * $_SESSION['currency-rate'], true); ?>
				</span>
			<?php } elseif($price) { ?>
				<span class="price solo">
					<?php if ($OPTION['wps_prod_ID']) { ?><span class="alignright itemID"><?php get_custom_field('ID_item', TRUE); ?></span><?php } ?>
					<?php echo format_price($price * $_SESSION['currency-rate'], true); ?>
				</span>
			<?php } 
		} ?>	
	</p><!-- price_value -->
	
	<?php 
	//if tax percentage display is on || inventory is active
	if ((($OPTION['wps_shop_country']=='US' && ($OPTION['wps_tax_info_enable'] && $OPTION['wps_salestax_sourcing_r'] =='taxincluded')) || $OPTION['wps_shop_country']!='US' && $OPTION['wps_tax_info_enable']) || ($OPTION['wps_track_inventory'] == 'active')){ ?>
		
		<p class="clearfix">
			<?php 
			// display tax info
			if (($OPTION['wps_shop_country']=='US' && ($OPTION['wps_tax_info_enable'] && $OPTION['wps_salestax_sourcing_r'] =='taxincluded')) || $OPTION['wps_shop_country']!='US' && $OPTION['wps_tax_info_enable']){ ?>
				<span class="alignleft tax_info"><?php _e('incl.','wpShop');?> <?php echo $OPTION['wps_tax_percentage']; ?> % <?php echo $OPTION['wps_tax_abbr']; ?> </span>
			<?php } 
			// display what is currently_in_stock	
			if($OPTION['wps_track_inventory'] == 'active' && $stock_amount !== 'not_set' && !is_it_digital()){															
				if($OPTION['wps_display_product_amounts'] =='active'){																	
						//we need additional method for prods without attributes
						if($attr_option == 1 || $attr_option == 2 ){$stock_amt = '&nbsp;';}else{$stock_amt = $stock_amount;}
						echo "<span class='alignright stock_info'>".__('Stock on Hand: ','wpShop')."<span id='stock_amount'>$stock_amt</span></span>";		
				}
			}  ?>
		</p>
		
	<?php }
 
	if(!is_it_affiliate()) { 
		if(($OPTION['wps_supplInfo_enable']) || ($OPTION['wps_shipping_details_enable'])) { ?>
			<p class="clearfix">	
				<?php
				//if size chart display is on		
				if(($OPTION['wps_supplInfo_enable']) && (strlen(get_custom_field('supplementary_info', FALSE))>0)) { ?>
					<span class="alignleft suppl_InfoLoad">
						<a href="<?php get_custom_field('supplementary_info', TRUE); ?>" rel="div.overlay:eq(5)" 
						title="<?php _e('Click here for ','wpShop'); echo $OPTION['wps_supplInfo_linkTxt'];?>" ><?php echo $OPTION['wps_supplInfo_linkTxt'];?></a>
					</span>
				<?php 
				} elseif(($OPTION['wps_supplInfo_enable']) && (strlen(get_custom_field('supplementary_info_text', FALSE))>0)) { ?>
					<span class="alignleft supplinfo">
						<a href="#" rel="div.overlay:eq(5)" title="<?php _e('Click here for ','wpShop'); echo $OPTION['wps_supplInfo_linkTxt'];?>" ><?php echo $OPTION['wps_supplInfo_linkTxt'];?></a>
					</span>
				<?php  }
				//if shipping details display is on		
				if($OPTION['wps_shipping_details_enable']) { ?>
					<span class="alignright shipping_info">
						<a href="#" rel="div.overlay:eq(6)" title="<?php _e('Click here for ','wpShop'); echo $OPTION['wps_shippingInfo_linkTxt']; ?>" ><?php echo $OPTION['wps_shippingInfo_linkTxt']; ?></a>
					</span>
				<?php }?>
			</p>
			
		<?php }
	} ?>

	<div class="product_btns noprint">
		<?php  
		// affiliate prod?
		if(is_it_affiliate()){ ?>
			<form action="" name="the_product" id="the_product" method="post">
				<input type="hidden" name="cmd" value="add" />
				<input type="hidden" name="postID" value="<?php the_ID(); ?>" />
				<input type="hidden" name="item_name" value="<?php the_title(); ?>" />
				<input type="hidden" name="item_id" id="item_id" value="<?php get_custom_field('ID_item', TRUE); ?>"/>
				<input type="hidden" name="item_number" value="1"/>									
					
				<?php 		
				if(get_post_meta($post->ID, 'new_price', true)){ 
					$value 	= get_post_custom_values("new_price"); 
				} 	
				elseif(get_post_meta($post->ID, 'price', true)){	
					$value 	= get_post_custom_values("price");
				} ?>
				
				
				<input type="hidden" id="amount" name="amount" value="<?php echo $value[0];?>"/>	
				<input type="hidden" name="buy_now" value="<?php get_custom_field('buy_now', TRUE); ?>" />		
				<input type="hidden" name="currency_code" value="<?php echo $OPTION['wps_currency_code']; ?>" />
				
				<?php 
				//has the user remembered to use the image_thumb custom field?
				if(strlen(get_custom_field('image_thumb', FALSE))>0) { ?>
					<input type="hidden" name="image_thumb" value="<?php get_custom_field('image_thumb', TRUE); ?>" />
				<?php } 
				//he did not!? 
				else { 
				// get attachment
				$image_thumb = my_attachment_image(0, 'full', 'alt="' . $post->post_title . '"','return');
				
				?>
					<input type="hidden" name="image_thumb" value="<?php echo $image_thumb[img_path]; ?>" />
				<?php } ?>
					
				<input type="hidden" name="add" value="1" />
				
				
				<div class="shopform_btn buy_now">
					<a href="<?php get_custom_field('buy_now', TRUE); ?>" <?php if($OPTION['wps_affili_newTab']) { ?> title="<?php _e('Opens is new tab','wpShop'); ?>" target="_blank"<?php } ?>><?php _e('Buy Now','wpShop'); ?></a>
				</div>
				<?php 
				// do we want a customer area with a wishlist?			
				if($OPTION['wps_lrw_yes']) {
					if ($_SESSION[user_logged]) { ?>
						<div class="shopform_btn add_to_wishlist">								
							<input class="input_image" type="image"  name="wishlist" value='yes'
							src="<?php bloginfo('stylesheet_directory'); ?>/images/add_to_wishlist.png" />
						</div>
					<?php } else { ?>
						<div class="shopform_btn add_to_wishlist add_to_wishlist_inactive">								
							<a href="<?php echo get_permalink($accountReg->ID); ?>" rel="div.overlay:eq(7)" title="<?php _e('Login or Register to save this item to your Wish List','wpShop'); ?>" ><?php _e('Save to Wish List','wpShop'); ?></a>
						</div>
					<?php } 
				} ?>
			</form>
		<?php } else { 
			// do we want the eCommerce engine? or the membership area?
			if($OPTION['wps_shoppingCartEngine_yes'] || $OPTION['wps_lrw_yes']) { ?>
			
				<form action="" name="the_product" id="the_product" method="post">
					<?php 
					$orderBy 	= $OPTION['wps_prodVariations_orderBy'];
					$order 		= $OPTION['wps_prodVariations_order'];
					
					// select drop-downs?
					if($attr_option == 1){
						echo get_attribute_dropdown($post->ID,$orderBy,$order);																
					}
					elseif($attr_option == 2){
						echo get_attribute_dropdown($post->ID,$orderBy,$order,2,$basis_price);	
					}
					else{} 
					
					
					// product personalization?
					if (has_personalization($post->ID)) {
						$p_orderBy 		= $OPTION['wps_prodPersonalization_orderBy'];
						$p_order 		= $OPTION['wps_prodPersonalization_order'];
						echo get_item_personalization($post->ID,$p_orderBy,$p_order);
					}
					
					?>
					
					<input type="hidden" name="cmd" value="add" />
					<input type="hidden" name="postID" value="<?php the_ID(); ?>" />
					<input type="hidden" name="item_name" value="<?php the_title(); ?>" />									
					<input type="hidden" name="item_id" id="item_id" value="<?php get_custom_field('ID_item', TRUE); ?>"/>
					<input type="hidden" name="item_number" value="1"/>									

					<?php
					if(get_post_meta($post->ID, 'new_price', true)){ 
						$value 	= get_post_custom_values("new_price"); 
					} 	
					elseif(get_post_meta($post->ID, 'price', true)){	
						$value 	= get_post_custom_values("price");
					} ?>
					
					<input type="hidden" id="amount" name="amount" value="<?php echo $value[0];?>"/>	
					
					<?php if(is_it_digital()){ ?>
						<input type="hidden" name="item_file" value="<?php get_custom_field('item_file', TRUE); ?>" />		
					<?php } else{ ?>
						<input type="hidden" name="item_weight" value="<?php get_custom_field('item_weight', TRUE); ?>" />	
					<?php } ?>
					
					<input type="hidden" name="currency_code" value="<?php echo $OPTION['wps_currency_code']; ?>" />
					
					<?php 
					//has the user remembered to use the image_thumb custom field?
					if(strlen(get_custom_field('image_thumb', FALSE))>0) { ?>
						<input type="hidden" name="image_thumb" value="<?php get_custom_field('image_thumb', TRUE); ?>" />
					<?php } 
					//he did not!? 
					else { 
					// get attachment
					$image_thumb = my_attachment_image(0, 'full', 'alt="' . $post->post_title . '"','return');
					?>
						<input type="hidden" name="image_thumb" value="<?php echo $image_thumb[img_path]; ?>" />
					<?php } ?>
					
					<input type="hidden" name="add" value="1" />
					<input type="hidden" name="attr_option" id="attr_option" value="<?php echo $attr_option; ?>" />
					<?php
					// add to shopping cart button, inventory check is also done here.
					if($OPTION['wps_shoppingCartEngine_yes']) {
						if($attr_option == 1 || $attr_option == 2 ) {
							$uri = get_bloginfo('stylesheet_directory');
							echo "<span id='child_theme_url' style='visibility:hidden;height:1px;width:1px;'>$uri</span>";
							
							if(($OPTION['wps_track_inventory'] == 'active') && ($stock_amount !== 'not_set')){
								
								echo "<div class='shopform_btn' id='txtHint'>
									<img src='$uri/images/add_to_cart_grey.png' id='greyAdd' name='greyAdd' style='visibility:visible;' />
								</div>";
												
							} else { 
								echo "<div class='shopform_btn' id='txtHint'>";
									echo "<img src='$uri/images/add_to_cart_grey.png'id='greyAdd' name='greyAdd' style='visibility:visible;' />";	
								echo "</div>";
							?>
								
							<div id="addC"></div>
							<?php } 
							echo '<input type="hidden" id="attrData" name="attrData" value=""/>';
						} else {
							$show_add_button = true;
							if ($OPTION['wps_track_inventory'] == 'active' && !is_it_digital()) {
								if ($stock_amount <= 0) { $show_add_button = false; }
							}
							if ($show_add_button) { ?>
								<div class="shopform_btn"><input class="input_image" type="image" id="addC" name="add" src="<?php bloginfo('stylesheet_directory'); ?>/images/<?php echo adjust_add2cart_img();?>" /></div>
								<?php
								// showing layaway option
								if (layaway_is_enabled()) {
									$days = layaway_get_product_days($post->ID, get_the_date('F j, Y g:i:s a')); // number of days as post/product is published
									if ($days < 8) { $days_left = 8 - $days;
								?>
										<div class="installments_left"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/available_installments<?php echo $days_left; ?>.png" id="daysLeft" name="daysLeft" /></div>
									<?php } else { ?>
										<div class="shopform_btn installments_btn"><input class="input_image" type="image" id="installments-button" name="addIns" src="<?php bloginfo('stylesheet_directory'); ?>/images/buy_in_installments.png" /></div>
										<input type="hidden" name="installments_buy" id="installments-buy" value="0">
										<?php
										$perc = layaway_get_percent_number();
										$ihead = get_option('wps_layaway_popup_heading');
										$itext = get_option('wps_layaway_popup_text');

										$usd_amount = format_price(layaway_get_amount($value[0]));
										$aed_amount = format_price(layaway_get_amount($value[0], true));

										$ihead = str_replace('{PRODUCT_NAME}', $post->post_title, $ihead);
										$ihead = str_replace('{USD_AMOUNT}', $usd_amount, $ihead);
										$ihead = str_replace('{AED_AMOUNT}', $aed_amount, $ihead);

										$itext = str_replace('{PRODUCT_NAME}', $post->post_title, $itext);
										$itext = str_replace('{USD_AMOUNT}', $usd_amount, $itext);
										$itext = str_replace('{AED_AMOUNT}', $aed_amount, $itext);
										?>
										<div style="display:none;">
											<div id="installments-popup" class="installments-popup">
												<div class="i-head"><?php echo $ihead; ?></div>
												<div class="i-text"><?php echo wpautop($itext); ?></div>
												<div class="shopform_btn installments_continue"><input class="input_image" type="image" id="installments-continue" name="addIns" src="<?php bloginfo('stylesheet_directory'); ?>/images/continue_installments.png" /></div>
											</div>
										</div>
								<?php }
								}
							} else { ?>
								<p class="sold_out"><?php echo $OPTION['wps_soldout_notice']; ?></p>
							<?php }
						}
					}
                    if( in_category( 'Cash on Delivery' )) { ?>
					&nbsp;&nbsp;<img src="<?php bloginfo('stylesheet_directory'); ?>/images/COD.jpg" alt="cash on delivery"/>
					<?php
					}
					// do we want a customer area with a wishlist?			
					if($OPTION['wps_lrw_yes']) {
						if ($_SESSION[user_logged]) { ?>
							<div class="shopform_btn add_to_wishlist">	
								<input type='hidden' name='wishlistRTUrl' value='<?php echo get_real_base_url(); ?>' />
								<input class="input_image" type="image"  name="wishlist" value='yes'
								src="<?php bloginfo('stylesheet_directory'); ?>/images/add_to_wishlist.png" />
							</div>
						<?php } else { ?>
							<div class="shopform_btn add_to_wishlist add_to_wishlist_inactive">								
								<a href="<?php echo get_permalink($accountReg->ID); ?>" rel="div.overlay:eq(7)" title="<?php printf(__ ('Login or Register to save this item to your %s!','wpShop'), $OPTION['wps_wishListLink_option'])?>" ><?php printf(__ ('Save to %s!','wpShop'), $OPTION['wps_wishListLink_option'])?></a>
							</div>
						<?php } 
					} ?>
				</form>
					
				<?php if($OPTION['wps_shoppingCartEngine_yes']) {
				
					$basket_url = get_cart_url().'?cPage='. current_page(3); ?>	
				
					<form method="post" name="view"  id="vCart" class="clearfix" action="<?php echo $basket_url; ?>" target="_top">
						<div class="shopform_btn">								
							<input class="input_image" type="image"  name="submit" src="<?php bloginfo('stylesheet_directory'); ?>/images/view_cart.png" />
						</div>
					</form>
				<?php }
			}
		} ?>
	</div><!-- product_btns -->	
<?php }
 
//display only if we have disabled the Shopping Cart for this prod post
if(strlen(get_custom_field('disable_cart', FALSE))>0){ 
	if(strlen(get_custom_field('item_remarks', FALSE))>0){ ?>
		<h4><?php get_custom_field('item_remarks', TRUE); ?></h4>
	<?php } 
		//get the enquiry form!
		if(function_exists('is_tellafriend') && strlen(get_custom_field('enquire', FALSE))>0){ ?>
			<div id="prod_enquiry">
				<?php if(is_tellafriend( $post->ID )) insert_cform(3); ?>
			</div><!-- prod_enquiry -->	
		<?php } else {}
} ?>					