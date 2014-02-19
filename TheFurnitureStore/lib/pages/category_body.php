<?php 
$attr_option 		= get_custom_field("add_attributes"); // attributes - simple or configurable price?
?>

<div class="contentWrap">
	<div class="holder">
		<div class="images">
		<?php if (!$post->inventory) { ?><span class="sold-out">Sold Out</span><?php } ?>
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
			<p class="error"><?php _e('No Product Image.','wpShop'); ?></p>
		<?php } ?>
		</div> <!-- .images-->
		<?php
		// do we want a teaser?
		if($OPTION['wps_teaser_enable_option']) { ?>
			<div class="teaser">
				<div class="prod-title-box">
					<h5 class="prod-title"><a href="<?php the_permalink(); ?>" title="<?php printf( __('Permalink to %s', 'wpShop'), the_title_attribute('echo=0') ); ?>" rel="bookmark"><?php the_title(); ?></a></h5>
				</div>
				<?php
				if ($OPTION['wps_teaser2_enable_option']) { 
					if(strlen(get_custom_field('item_remarks', FALSE))>0) { ?>
						<div class="item_description">
							<?php get_custom_field('item_remarks', TRUE); ?>
						</div>
					<?php }
				} ?>
				<p class="price_value">
					<?php // PRODUCT PRICE
					$currency_code = $_SESSION['currency-code'];
					$currency_rate = $_SESSION['currency-rate'];
					if (!$currency_rate) { $currency_rate = 1; }
					$price = get_custom_field('price', FALSE);
					$new_price = get_custom_field('new_price', FALSE);
					if ($new_price && $price > 0) { ?>
						<span class="was price">Was: <?php product_prices_list($price); ?></span>
					<?php } ?>
				</p><!-- price_value -->
			</div><!-- teaser  -->
		<?php } ?>
	</div> <!--.holder-->
	<?php if ($new_price) { if ($price > 0) { $perc = round(($price - $new_price) / ($price / 100)); }  ?>
		<div class="price-box">
			<?php if ($price > 0) { ?><span class="discounts"><?php echo $perc; ?>% off</span><?php } ?>
			<h3>Now: <strong><?php product_prices_list($new_price); ?></strong></h3>
		</div>
	<?php } else { ?>
		<div class="price-box">
			<h3><strong><?php product_prices_list($price); ?></strong></h3>
		</div>
	<?php } ?>
	<?php
	$post_udate = strtotime(get_the_date());
	$now_date = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
	$days_ago = ceil(($now_date - $post_udate) / 86400);
	if ($days_ago > 0 && $days_ago <= 30) { ?>
	<span class="date-info">added <?php echo $days_ago; ?> days ago</span>
	<?php } ?>
</div><!-- contentWrap  -->
<?php
$post_selections = wp_get_post_terms($post->ID, 'selection');
if ($post_selections) {
	foreach($post_selections as $post_selection) { $item_selection = $post_selection->name; } 
	$isarr = explode(" ", strtolower($item_selection));
	$icon_lt = substr($isarr[0], 0, 1);
	if (count($isarr) > 1) {
		$icon_lt .= substr($isarr[1], 0, 1);
	}
?>
<span class="ico-cond <?php echo $icon_lt; ?>" title="<?php echo $item_selection; ?>"></span>
<?php } ?>