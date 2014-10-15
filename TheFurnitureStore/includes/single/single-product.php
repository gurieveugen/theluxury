<?php
/*
Template Name: Single Product
*/
global $wpdb;
?>

<?php get_header(); ?>

<?php
//$stock_amount = get_item_inventory($post->ID);
$stock_amount = $post->inventory;
$attr_option = get_custom_field("add_attributes"); // attributes - simple or configurable price?

$custom_title_attr = get_post_meta($post->ID, 'general_images_title', true);
$custom_alt_attr = get_post_meta($post->ID, 'general_images_alt', true);
if (!strlen($custom_title_attr)) { $custom_title_attr = $post->post_title; }
if (!strlen($custom_alt_attr)) { $custom_alt_attr = $post->post_title; }

// inventory control
if($OPTION['wps_track_inventory'] == 'active') {
	echo '<script>var tracking = "on";</script>';
} else {
	echo '<script>var tracking = "off";</script>';
}
?>
<script>
jQuery(document).ready(function() {
	jQuery.get("<?php bloginfo('url'); ?>/index.php",
	{
		FormAction: 'product-views',
		non_cache: 'true',
		product_id: <?php echo $post->ID; ?>
	},
	function(data) {
		var pviews = parseInt(data);
		if (pviews > 1) {
			jQuery('#prod-views-nmb').html(pviews);
			jQuery('.prod-views').css('visibility','visible');
		}
	});
});
</script>

<div class="main-product">
	<?php if($_GET['added'] == 'OK' && $_GET['l'] == 'wl') {
		$customerArea = get_page_by_title($OPTION['wps_customerAreaPg']); ?>
		<div class="success-message"><?php printf(__ ('Your item has been successfully added to your %s!','wpShop'), $OPTION['wps_wishListLink_option'])?> <a href="<?php echo get_permalink($OPTION['wps_account_my_wishlist_page']); ?>"><?php printf(__ (' View %s','wpShop'), $OPTION['wps_wishListLink_option'])?></a> | <a href="<?php the_permalink(); ?>"><?php _e('Continue Shopping','wpShop');?></a></div>
	<?php } ?>
	<h1><?php the_title(); ?></h1>
	<div class="product-holder">
		<div class="product-content">
			<?php
			// PRODUCT THUMBS (ZOOM)
			$WPS_prodImg_effect	= $OPTION['wps_prodImg_effect'];

			$post_featured = get_post_thumbnail_id($post->ID);
			$post_attachs = $wpdb->get_results(sprintf("SELECT * FROM %sposts WHERE post_parent = %s AND post_type = 'attachment' ORDER BY menu_order, ID", $wpdb->prefix, $post->ID));

			$post_images = array();
			if ($post_featured) {
				$post_images[] = $post_featured;
			}
			if ($post_attachs) {
				foreach($post_attachs as $post_attach) {
					if (!in_array($post_attach->ID, $post_images)) {
						$post_images[] = $post_attach->ID;
					}
				}
			}

			if(count($post_images) > 0) { ?>
				<div class="image-box">
					<div class="image-frame">
						<div class="holder">
							<div class="frame">
								<a href="<?php echo get_post_thumb($post_images[0]); ?>" class="MagicZoom MagicThumb" id="zoom1" rel="show-title: false; zoom-fade: true; zoom-position: inner; thumb-change: mouseover"><img src="<?php echo get_post_thumb($post_images[0], 605, 605, true); ?>" alt="<?php echo get_custom_alt_title('alt', $post_images[0], $custom_alt_attr); ?>" title="<?php echo get_custom_alt_title('title', $post_images[0], $custom_title_attr); ?>" /></a>
							</div>
						</div>
					</div>
				</div>
				<?php if(count($post_images) > 1) { ?>
				<div class="thumbnails">
					<?php
					$active = ' class="active"';
					foreach($post_images as $post_image) { ?>
						<a<?php echo $active; ?> href="<?php echo get_post_thumb($post_image); ?>" rel="zoom-id:zoom1" rev="<?php echo get_post_thumb($post_image, 605, 605, true); ?>"><img src="<?php echo get_post_thumb($post_image, 61, 61, true); ?>" alt="<?php echo get_custom_alt_title('alt', $post_image, $custom_alt_attr); ?>" title="<?php echo get_custom_alt_title('title', $post_image, $custom_title_attr); ?>" style="width:61px;height:61px;" /></a>
						<?php
						$active = '';
					} ?>
				</div>
				<?php } ?>
			<?php } ?>

			<?php if($OPTION['wps_relatedProds_enable']) { include (TEMPLATEPATH . '/includes/single/single-product-related.php'); } ?>

		</div>
		<div class="product-sidebar">
			<div class="price-row">
			<?php
			// product price
			$currency_rate = $_SESSION['currency-rate'];
			if (!$currency_rate) { $currency_rate = 1; }
			$price = get_post_meta($post->ID, 'price', true);
			$new_price = get_post_meta($post->ID, 'new_price', true);
			if($new_price) { ?>
				<h2>Price <span class="old-price"><?php product_prices_list($price); ?></span></h2>
				<h2 class="price"><?php product_prices_list($new_price); ?></h2>
				<?php $price = $new_price; ?>
			<?php } else { ?>
				<h2>Price: <?php product_prices_list($price); ?></h2>
			<?php } ?>
			</div>
			<?php
			$image_thumb = get_custom_field('image_thumb', FALSE);
			if (!strlen($image_thumb)) {
				$attach_img = my_attachment_image(0, 'full', 'alt="' . $post->post_title . '"','return');
				$image_thumb = $attach_img['img_path'];
			}
			?>
			<div class="clear"></div>
			<div class="data-section">
				<?php if(strlen(get_custom_field('disable_cart', FALSE)) == 0) { ?>
				<form action="" name="the_product" id="the_product" method="post">
					<input type="hidden" name="cmd" value="add" />
					<input type="hidden" name="add" value="1" />
					<input type="hidden" name="add_action" value="cart" id="add-action" />
					<input type="hidden" name="postID" value="<?php the_ID(); ?>" />
					<input type="hidden" name="item_name" value="<?php the_title(); ?>" />									
					<input type="hidden" name="item_id" id="item_id" value="<?php get_custom_field('ID_item', TRUE); ?>" />
					<input type="hidden" name="item_number" value="1"/>									
					<input type="hidden" name="currency_code" value="<?php echo $OPTION['wps_currency_code']; ?>" />
					<input type="hidden" name="amount" value="<?php echo $price; ?>" id="amount" />	
					<input type="hidden" name="image_thumb" value="<?php echo $image_thumb; ?>" />
					<input type="hidden" name="attr_option" id="attr_option" value="<?php echo $attr_option; ?>" />
					<?php if(is_it_digital()) { ?>
						<input type="hidden" name="item_file" value="<?php get_custom_field('item_file', TRUE); ?>" />
					<?php } else { ?>
						<input type="hidden" name="item_weight" value="<?php get_custom_field('item_weight', TRUE); ?>" />
					<?php } ?>
					<div class="buttons">
						<?php if($OPTION['wps_shoppingCartEngine_yes']) { ?>
							<?php if ($stock_amount > 0) { ?>
								<?php if(product_is_available($post->ID)) { ?>
									<!--<input type="image" src="<?php bloginfo('template_url'); ?>/images/product/btn-buy-now.png" class="btn-buy-now" id="addC">-->
									<button class="btn-orange" id="addC">Buy Now</button>
									<?php if (layaway_is_enabled()) {
										$days = layaway_get_product_days($post->ID, get_the_date('F j, Y g:i:s a'));
										if ($days < 8) { $days_left = 8 - $days; ?>
										<strong class="btn-grey no-btn btn-1">Available for installments in <?php echo $days_left; ?> day<?php if ($days_left > 1) { echo 's'; } ?></strong>
										<?php } else { ?>
											<button class="btn-grey" id="installments-button">Buy In Installments</button>
											<!--<input type="image" src="<?php bloginfo('template_url'); ?>/images/product/btn-buy-installments.png" class="btn-installments" id="installments-button" />-->
											<input type="hidden" name="installments_buy" id="installments-buy" value="0">
											<?php
											$perc = layaway_get_percent_number();
											$ihead = get_option('wps_layaway_popup_heading');
											$itext = get_option('wps_layaway_popup_text');

											$usd_amount = format_price(layaway_get_amount($price));
											$aed_amount = format_price(layaway_get_amount($price, true));

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
													<!--<div class="shopform_btn installments_continue"><input class="input_image" type="image" id="installments-continue" name="addIns" src="<?php bloginfo('stylesheet_directory'); ?>/images/continue_installments.png" /></div>-->
													<div class="cf"><input class="input_image btn-orange right" type="submit" id="installments-continue" value="Continue" name="addIns" /></div>
												</div>
											</div>
										<?php } ?>
									<?php } ?>
								<?php } else { // if(product_is_available($post->ID)) { ?>
									<img src="<?php bloginfo('template_url'); ?>/images/product/btn-unavailable.png" class="btn-unavailable" title="<?php echo get_option('wps_unavailable_text'); ?>" />
									<?php if (layaway_is_enabled()) { ?>
										<img src="<?php bloginfo('template_url'); ?>/images/product/btn-buy-installments-unavail.png" class="btn-installments-unavail" title="<?php echo get_option('wps_unavailable_text'); ?>" />
									<?php } ?>
								<?php } ?>
							<?php } else { // if ($stock_amount > 0) { ?>
								<strong class="prod-status-text">Sold Out</strong>
								<?php if ($OPTION['wps_alerts_enable']) { ?>
									<input type="hidden" name="request_value" value="<?php the_title(); ?>" id="request-this-product-value" />
									<!--<a href="#request" class="btn-request" id="request-this-product">Request this product</a>-->
								<?php } ?>
							<?php } ?>
							<?php if($OPTION['wps_lrw_yes']) { ?>
								<a class="link-wishlist" href="<?php echo site_url('index.php?wishlist=add&fpg=single&pid='.$post->ID); ?>">Add to Wishlist</a>
							<?php } ?>
						<?php } ?>
					</div>
					<?php
					// attribute dropdown
					$orderBy = $OPTION['wps_prodVariations_orderBy'];
					$order 	 = $OPTION['wps_prodVariations_order'];
					if($attr_option == 1) {
						echo get_attribute_dropdown($post->ID,$orderBy,$order);
					} else if($attr_option == 2) {
						echo get_attribute_dropdown($post->ID,$orderBy,$order,2,$price);
					}
					// product personalization?
					if (has_personalization($post->ID)) {
						$p_orderBy 		= $OPTION['wps_prodPersonalization_orderBy'];
						$p_order 		= $OPTION['wps_prodPersonalization_order'];
						echo get_item_personalization($post->ID,$p_orderBy,$p_order);
					}
					?>
				</form>
				<?php } ?>
				<ul class="product-socials">
					<li><a href="http://www.facebook.com/share.php?u=<?php the_permalink(); ?>&t=<?php the_title(); ?>" class="link-facebook" target="_blank">facebook</a></li>
					<li><a href="http://twitter.com/share?text=Check out <?php the_title(); ?> at @TheLuxuryCloset&url=<?php echo shorturl(get_permalink($post->ID)); ?>" class="link-twitter" target="_blank">twitter</a></li>
					<li><a href="https://plus.google.com/share?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>" class="link-google" target="_blank">google+</a></li>
					<li class="pinit" style="position:relative;"><a href="#" class="pinterest pinmask"></a><span style="position:absolute;left:-10000px;"><a href="http://www.pinterest.com/pin/create/link/?url=<?php echo urlencode(get_permalink()); ?>&description=<?php the_title(); ?>&media=<?php echo get_post_thumb($post_images[0]); ?>" target="_blank" data-pin-do="buttonPin" data-pin-config="above">pinterest</a></span></li>
					<script type="text/javascript" async src="//assets.pinterest.com/js/pinit.js"></script>
				</ul>
			</div>
			<div class="clear"></div>
			<div class="data-section section-1">
				<?php
				$ip = $_SERVER['REMOTE_ADDR'];
				$IP2COUNTRY = load_what_is_needed('ip2country');
				$user_country = $IP2COUNTRY->load($ip);
				if ($user_country == 'UNITED ARAB EMIRATES' || in_category('Cash on Delivery')) { ?>
					<strong class="delivery">AVAILABLE Cash on delivery</strong>
				<?php } ?>
				<div class="text-holder">
					<?php // display what is currently_in_stock	
					if($OPTION['wps_track_inventory'] == 'active' && $stock_amount !== 'not_set' && !is_it_digital() && $OPTION['wps_display_product_amounts'] == 'active') {
						if($attr_option == 1 || $attr_option == 2 ){ $stock_amt = '&nbsp;'; }else{ $stock_amt = $stock_amount; } ?>
						<p>Stock: <strong><?php echo $stock_amt; ?></strong></p>
					<?php } ?>
					<div class="prod-views" style="visibility:hidden;">
						<p>People viewing this item: <strong id="prod-views-nmb">0</strong></p>
					</div>
				</div>
			</div>
			<div class="data-section">
				<div class="accordion">
					<div class="accordion-item open">
						<div class="heading">
							<span class="icon"></span>
							<h3>Product Info</h3>
						</div>
						<div class="content">
							<?php
							$old_price = get_post_meta($post->ID, 'old_price', true);
							$item_request_price = get_post_meta($post->ID, 'item_request_price', true);
							if ($old_price > 0 && $item_request_price == 'completed' && stripos($post->post_content, 'Earlier priced') === false) {
							?>
							<h4>Earlier priced at $<?php echo format_price($old_price); ?> (<?php echo format_price($old_price * $OPTION['wps_exr_aed']); ?> AED)</h4>
							<?php } ?>
							<?php the_content(); ?>
							<?php
							$item_gender = get_post_meta($post->ID, 'item_gender', true);
							$item_seller = get_post_meta($post->ID, 'item_seller', true);
							$item_shoulder_strap = get_post_meta($post->ID, 'item_shoulder_strap', true);
							if (strlen($item_seller)) {
								$item_condition_desc = get_post_meta($post->ID, 'item_condition_desc', true);
								$item_length = get_post_meta($post->ID, 'item_length', true);
								$item_height = get_post_meta($post->ID, 'item_height', true);
								$item_width = get_post_meta($post->ID, 'item_width', true);
								$item_handle_drop = get_post_meta($post->ID, 'item_handle_drop', true);
								$item_material = get_post_meta($post->ID, 'item_material', true);
								$item_exterior_material = get_post_meta($post->ID, 'item_exterior_material', true);
								$item_interior_material = get_post_meta($post->ID, 'item_interior_material', true);
								$item_hardware = get_post_meta($post->ID, 'item_hardware', true);
								$item_includes = get_post_meta($post->ID, 'item_includes', true);

								$item_size = get_post_meta($post->ID, 'item_size', true);
								$item_heel_size = get_post_meta($post->ID, 'item_heel_size', true);
								$item_case_diameter = get_post_meta($post->ID, 'item_case_diameter', true);
								$item_watch_bracelet_size = get_post_meta($post->ID, 'item_watch_bracelet_size', true);
								$item_movement_type = get_post_meta($post->ID, 'item_movement_type', true);
								$item_upper_material = get_post_meta($post->ID, 'item_upper_material', true);
								$item_lining_material = get_post_meta($post->ID, 'item_lining_material', true);
								$item_sole_material = get_post_meta($post->ID, 'item_sole_material', true);
								$item_bracelet_material = get_post_meta($post->ID, 'item_bracelet_material', true);
								$item_case_material = get_post_meta($post->ID, 'item_case_material', true);
								$item_necklace_length = get_post_meta($post->ID, 'item_necklace_length', true);
								$item_earring_width = get_post_meta($post->ID, 'item_earring_width', true);
								$item_earring_height = get_post_meta($post->ID, 'item_earring_height', true);
								$item_bracelet_size = get_post_meta($post->ID, 'item_bracelet_size', true);
								$item_bracelet_length = get_post_meta($post->ID, 'item_bracelet_length', true);

								$post_selections = wp_get_post_terms($post->ID, 'selection');
								if ($post_selections) { foreach($post_selections as $post_selection) { $item_selection = $post_selection->name; } }

								$item_includes = explode('|', $item_includes);
								$sellers_includes = sellers_get_includes(true);
							}
							?>
							<?php if (strlen($item_gender)) { ?><strong>Gender</strong>: <?php echo $item_gender; ?><br /><?php } ?>
							<?php if (strlen($item_selection)) { ?><strong>Condition</strong>: <?php echo $item_selection; ?> <?php if (strlen($item_condition_desc)) { ?>(<?php echo $item_condition_desc; ?>)<?php } ?><br /><?php } ?>
							<?php if (strlen($item_length) || strlen($item_height) || strlen($item_width)) { ?>
								<strong>Dimensions</strong>:
								<?php if (strlen($item_length)) { ?> <?php echo $item_length; ?> CM(L)<?php } ?>
								<?php if (strlen($item_height)) { ?> <?php echo $item_height; ?> CM(H)<?php } ?>
								<?php if (strlen($item_width)) { ?> <?php echo $item_width; ?> CM(W)<?php } ?>
								<br />
							<?php } ?>
							<?php if (strlen($item_shoulder_strap)) { ?><strong>Shoulder Strap</strong>: <?php echo $item_shoulder_strap; if (strpos($item_shoulder_strap, 'CM') === false) { echo ' CM'; } ?><br /><?php } ?>
							<?php if (strlen($item_handle_drop)) { ?><strong>Handle Drop</strong>: <?php echo $item_handle_drop; ?> CM<br /><?php } ?>
							<?php if (strlen($item_material)) { ?><strong>Material</strong>: <?php echo $item_material; ?><br /><?php } ?>
							<?php if (strlen($item_exterior_material)) { ?><strong>Exterior Material</strong>: <?php echo $item_exterior_material; ?><br /><?php } ?>
							<?php if (strlen($item_interior_material)) { ?><strong>Interior Material</strong>: <?php echo $item_interior_material; ?><br /><?php } ?>
							<?php if (strlen($item_hardware)) { ?><strong>Hardware</strong>: <?php echo $item_hardware; ?><br /><?php } ?>
							<?php if (strlen($item_size)) { ?><strong>Size</strong>: <?php echo $item_size; ?><br /><?php } ?>
							<?php if (strlen($item_heel_size)) { ?><strong>Heel Size</strong>: <?php echo $item_heel_size; ?> CM<br /><?php } ?> 
							<?php if (strlen($item_case_diameter)) { ?><strong>Case Diameter</strong>: <?php echo $item_case_diameter; ?><br /><?php } ?>
							<?php if (strlen($item_watch_bracelet_size)) { ?><strong>Bracelet Size</strong>: <?php echo $item_watch_bracelet_size; ?><br /><?php } ?>
							<?php if (strlen($item_movement_type)) { ?><strong>Movement Type</strong>: <?php echo $item_movement_type; ?><br /><?php } ?>
							<?php if (strlen($item_upper_material)) { ?><strong>Upper Material</strong>: <?php echo $item_upper_material; ?><br /><?php } ?>
							<?php if (strlen($item_lining_material)) { ?><strong>Lining Material</strong>: <?php echo $item_lining_material; ?><br /><?php } ?>
							<?php if (strlen($item_sole_material)) { ?><strong>Sole Material</strong>: <?php echo $item_sole_material; ?><br /><?php } ?>
							<?php if (strlen($item_bracelet_material)) { ?><strong>Bracelet Material</strong>: <?php echo $item_bracelet_material; ?><br /><?php } ?>
							<?php if (strlen($item_case_material)) { ?><strong>Case Material</strong>: <?php echo $item_case_material; ?><br /><?php } ?>
							<?php if (strlen($item_necklace_length)) { ?><strong>Necklaces Length</strong>: <?php echo $item_necklace_length; ?> CM<br /><?php } ?>
							<?php if (strlen($item_earring_width) || strlen($item_earring_height)) { ?>
								<strong>Earring Dimensions</strong>:
								<?php if (strlen($item_earring_width)) { ?><strong>Width</strong>: <?php echo $item_earring_width; ?> MM<br /><?php } ?>
								<?php if (strlen($item_earring_height)) { ?><strong>Height</strong>: <?php echo $item_earring_height; ?> MM<br /><?php } ?>
							<?php } ?>
							<?php if (strlen($item_bracelet_size)) { ?><strong>Bracelet Size</strong>: <?php echo $item_bracelet_size; ?><br /><?php } ?>
							<?php if (strlen($item_bracelet_length)) { ?><strong>Bracelet Length</strong>: <?php echo $item_bracelet_length; ?> CM<br /><?php } ?>

							<?php if (count($item_includes)) { ?><strong>Includes</strong>: <?php foreach ($item_includes as $item_include) { echo $isep.$sellers_includes[$item_include]; $isep = ', '; } ?><br /><?php } ?>
						</div>
					</div>
					<?php if ('open' == $post->comment_status) { ?>
					<div class="accordion-item open">
						<div class="heading">
							<span class="icon"></span>
							<h3>Comments</h3>
						</div>
						<div class="content">
							<?php comments_template('', true); ?>
						</div>
					</div>
					<?php } ?>
					<?php if($OPTION['wps_shipping_details_enable']) { ?>
					<div class="accordion-item">
						<div class="heading">
							<span class="icon"></span>
							<h3>Delivery & Returns</h3>
						</div>
						<div class="content">
							<?php
							$post_delivery_time = wp_get_post_terms($post->ID, 'delivery-time');
							if ($post_delivery_time) { ?>
								<p>This item will be delivered in <?php echo $post_delivery_time[0]->name; ?></p>
							<?php } else {
								if (in_category($OPTION['wps_men_watches_category'], $post->ID) || in_category($OPTION['wps_women_watches_category'], $post->ID)) {
									echo $OPTION['wps_watches_shipping_details'];
								} else {
									echo $OPTION['wps_shipping_details'];
								}
							}
							?>
						</div>
					</div>
					<?php } ?>
					<div class="accordion-item">
						<?php if (in_category($OPTION['wps_men_shoes_category'], $post->ID) || in_category($OPTION['wps_women_shoes_category'], $post->ID)) { ?>
						<div class="heading">
							<span class="icon"></span>
							<h3>Sizing</h3>
						</div>
						<div class="content">
							<?php if (in_category($OPTION['wps_men_shoes_category'], $post->ID)) { ?>
								<p><strong>Men's Shoes</strong></p>
								<p><i>Men's designer shoes sold on the Luxury Closet use European Sizing. To determine your correct size, take your heel to toe measurement, and using the following table, convert into your European or U.S. size.</i></p>
								<table class="shoes-sizes">
									<tr><td width="30%">Inches</td><td width="25%">EUR</td><td width="25%">USA</td><td width="25%">UK</td></tr>
									<tr><td>9.31"</td><td>39</td><td>6</td><td>5</td></tr>
									<tr><td>9.50"</td><td>39,5</td><td>6.5</td><td>5.5</td></tr>
									<tr><td>9.69"</td><td>40</td><td>7</td><td>6</td></tr>
									<tr><td>9.81"</td><td>40,5</td><td>7.5</td><td>6.5</td></tr>
									<tr><td>10.00"</td><td>41</td><td>8</td><td>7</td></tr>
									<tr><td>10.19"</td><td>41,5</td><td>8.5</td><td>7.5</td></tr>
									<tr><td>10.31"</td><td>42</td><td>9</td><td>8</td></tr>
									<tr><td>10.50"</td><td>42,5</td><td>9.5</td><td>8.5</td></tr>
									<tr><td>10.69"</td><td>43</td><td>10</td><td>9</td></tr>
									<tr><td>10.81"</td><td>43,5</td><td>10.5</td><td>9.5</td></tr>
									<tr><td>11.00"</td><td>44</td><td>11</td><td>10</td></tr>
									<tr><td>11.19"</td><td>44,5</td><td>11.5</td><td>10.5</td></tr>
									<tr><td>11.31"</td><td>45</td><td>12</td><td>11</td></tr>
									<tr><td>11.50"</td><td>45,5</td><td>12.5</td><td>11.5</td></tr>
									<tr><td>11.69"</td><td>46</td><td>13</td><td>12</td></tr>
									<tr><td>11.81"</td><td>46,5</td><td>13.5</td><td>12.5</td></tr>
									<tr><td>12.00"</td><td>47</td><td>14</td><td>13</td></tr>
								</table>
							<?php } else { ?>
								<p><strong>Women's Shoes</strong></p>
								<p><i>Women's designer shoes sold on the Luxury Closet use European Sizing. To determine your correct size, take your heel to toe measurement, and using the following table, convert into your European or U.S. size.</i></p>
								<table class="shoes-sizes">
									<tr><td width="30%">Inches</td><td width="25%">EUR</td><td width="25%">USA</td><td width="25%">UK</td></tr>
									<tr><td>8.50"</td><td>35</td><td>4.5</td><td>2</td></tr>
									<tr><td>8.63"</td><td>35.5</td><td>5</td><td>2.5</td></tr>
									<tr><td>8.88"</td><td>36</td><td>5.5</td><td>3</td></tr>
									<tr><td>9.00"</td><td>36.5</td><td>6</td><td>3.5</td></tr>
									<tr><td>9.13"</td><td>37</td><td>6.5</td><td>4</td></tr>
									<tr><td>9.38"</td><td>37.5</td><td>7</td><td>4.5</td></tr>
									<tr><td>9.50"</td><td>38</td><td>7.5</td><td>5</td></tr>
									<tr><td>9.625"</td><td>38.5</td><td>8</td><td>5.5</td></tr>
									<tr><td>9.875"</td><td>39</td><td>8.5</td><td>6</td></tr>
									<tr><td>10.00"</td><td>40</td><td>9</td><td>6.5</td></tr>
									<tr><td>10.125"</td><td>41</td><td>9.5</td><td>7</td></tr>
									<tr><td>10.25"</td><td>42</td><td>10</td><td>7.5</td></tr>
									<tr><td>10.375"</td><td>43</td><td>10.5</td><td>8</td></tr>
									<tr><td>10.50"</td><td>43.5</td><td>11</td><td>8.5</td></tr>
									<tr><td>10.625"</td><td>44</td><td>11.5</td><td>9</td></tr>
									<tr><td>10.875"</td><td>44.5</td><td>12</td><td>10</td></tr>
									<tr><td>11.00"</td><td>45</td><td>12.5</td><td>10.5</td></tr>
									<tr><td>11.125"</td><td>46</td><td>13</td><td>11</td></tr>
								</table>
							<?php } ?>
						</div>
						<?php } else if (in_category($OPTION['wps_men_bags_category'], $post->ID) || in_category($OPTION['wps_women_bags_category'], $post->ID)) { ?>
						<div class="heading">
							<span class="icon"></span>
							<h3>Bag Measurements</h3>
						</div>
						<div class="content">
							<p>Handle Drop is measured from high point of the strap to bag opening.</p>
							<div class="center">
								<img src="<?php bloginfo('template_url') ?>/images/product/img-10.jpg" width="249" height="201" alt="" />
							</div>
						</div>
						<?php } else if (in_category($OPTION['wps_men_jewelry_category'], $post->ID) || in_category($OPTION['wps_women_jewelry_category'], $post->ID)) { ?>
							<?php if (in_category('rings', $post->ID)) { ?>
							<div class="heading">
								<span class="icon"></span>
								<h3>Ring Sizing</h3>
							</div>
							<div class="content">
								<p><i>Designer rings sold on the Luxury Closet use European Sizing. To determine your correct size, measure the diameter of your finger, and using the following table, convert into your European or U.S. size. If you are hesitating between two sizes, choose the larger one.</i></p>
								<table class="shoes-sizes">
									<tr><td width="30%">Size in mm</td><td>European Size</td><td width="30%">U.S Size</td></tr>
									<tr><td>44</td><td>44</td><td>3</td></tr>
									<tr><td>44.9</td><td>45</td><td>3 1/2</td></tr>
									<tr><td>45.8</td><td>46</td><td>3 3/4</td></tr>
									<tr><td>46.8</td><td>47</td><td>4</td></tr>
									<tr><td>48</td><td>48</td><td>4 1/2</td></tr>
									<tr><td>49</td><td>49</td><td>5</td></tr>
									<tr><td>50.2</td><td>50</td><td>5 1/4</td></tr>
									<tr><td>50.5</td><td>50.5</td><td>5 1/2</td></tr>
									<tr><td>50.9</td><td>51</td><td>5 3/4</td></tr>
									<tr><td>51.8</td><td>52</td><td>6</td></tr>
									<tr><td>52.4</td><td>52.5</td><td>6 1/4</td></tr>
									<tr><td>52.8</td><td>53</td><td>6 1/2</td></tr>
									<tr><td>54</td><td>54</td><td>6 3/4</td></tr>
									<tr><td>54.3</td><td>54.5</td><td>7</td></tr>
									<tr><td>54.6</td><td>55</td><td>7 1/4</td></tr>
									<tr><td>55.9</td><td>56</td><td>7 1/2</td></tr>
									<tr><td>56.8</td><td>57</td><td>8</td></tr>
									<tr><td>58.1</td><td>58</td><td>8 1/4</td></tr>
									<tr><td>59</td><td>59</td><td>8 3/4</td></tr>
									<tr><td>60.3</td><td>60</td><td>9</td></tr>
									<tr><td>61.2</td><td>61</td><td>9 1/2</td></tr>
									<tr><td>62.2</td><td>62</td><td>10</td></tr>
									<tr><td>62.8</td><td>63</td><td>10 1/4</td></tr>
									<tr><td>64.1</td><td>64</td><td>10 3/4</td></tr>
									<tr><td>64.7</td><td>65</td><td>11</td></tr>
									<tr><td>65.9</td><td>66</td><td>11 1/2</td></tr>
									<tr><td>66.6</td><td>67</td><td>11 3/4</td></tr>
								</table>
							</div>
							<?php } ?>
						<?php } else if (in_category($OPTION['wps_men_clothes_category'], $post->ID) || in_category($OPTION['wps_women_clothes_category'], $post->ID)) { ?>
							<div class="heading">
								<span class="icon"></span>
								<h3>Sizing</h3>
							</div>
							<div class="content">
								<table class="shoes-sizes">
									<tr><td>Size</td><td>USA</td><td>UK</td><td>Italy</td><td>France</td><td>Jeans</td></tr>
									<tr><td>XS</td><td>0</td><td>4</td><td>36</td><td>32</td><td>23</td></tr>
									<tr><td>S</td><td>0</td><td>6</td><td>38</td><td>34</td><td>24-25</td></tr>
									<tr><td>S</td><td>2-4</td><td>8</td><td>40</td><td>36</td><td>26-27</td></tr>
									<tr><td>M</td><td>4-6</td><td>10</td><td>42</td><td>38</td><td>27-28</td></tr>
									<tr><td>M</td><td>8</td><td>12</td><td>44</td><td>40</td><td>29-30</td></tr>
									<tr><td>L</td><td>10</td><td>14</td><td>46</td><td>42</td><td>31-32</td></tr>
									<tr><td>L</td><td>12</td><td>16</td><td>48</td><td>44</td><td>32-33</td></tr>
									<tr><td>XL</td><td>14</td><td>18</td><td>50</td><td>46</td><td>&nbsp;</td></tr>
									<tr><td>XL</td><td>16</td><td>20</td><td>52</td><td>48</td><td>&nbsp;</td></tr>
								</table>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $product_views++; update_post_meta($post->ID, 'product_views', $product_views); ?>

<?php get_footer(); ?>