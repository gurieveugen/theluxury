<div class="accordion">
<?php
// ---------------------------------------------------------------
// YOU MAY ALSO LIKE
// ---------------------------------------------------------------
$post_tags = wp_get_post_terms($post->ID, 'post_tag');
if ($post_tags) {
	$tag_ids = array();
	foreach($post_tags as $post_tag) {
		$tag_ids[] = $post_tag->term_id;
	}
	$args = array(
		'tag__in' 		 => $tag_ids,
		'post__not_in' 	 => array($post->ID),
		'posts_per_page' => $OPTION['wps_tag_relatedProds_num'], 
		'orderby' 		 => 'date',
		'order' 		 => 'DESC'
	);
	$recently_where = true;
	add_filter('posts_where', 'recently_added_where');
	$related_tags_query = new WP_Query($args); 		
	if ($related_tags_query->have_posts()) { ?>
	<div class="accordion-item open">
		<div class="heading">
			<span class="icon"></span>
			<h3>You may also like</h3>
		</div>
		<div class="content">
			<div class="products-list">
				<?php while ($related_tags_query->have_posts()) { $related_tags_query->the_post();
					$pimage = get_product_thumb(get_the_ID());
					$price = get_post_meta(get_the_ID(), 'price', true);
					$new_price = get_post_meta(get_the_ID(), 'new_price', true);
					if($new_price) { $price = $new_price; }
				?>
				<div class="item">
					<a href="<?php the_permalink(); ?>" class="image" title="<?php the_title(); ?>">
						<?php if($pimage) { ?><img src="<?php echo $pimage; ?>" alt="" /><?php } ?>
					</a>
					<div class="holder">
						<p><?php echo get_limit_content(get_the_title(), 40, true); ?></p>
						<h4><?php product_prices_list($price); ?></h4>
					</div>
				</div>
				<?php } wp_reset_query(); ?>
			</div>
		</div>
	</div>
<?php }
} ?>
<?php
// ---------------------------------------------------------------
// RECENTLY ADDED
// ---------------------------------------------------------------
$categories = get_the_category($post->ID);
if ($categories) {
	$category_ids = array();
	foreach($categories as $individual_category){
		$category_ids[] = $individual_category->term_id;
	}
		
	$args = array(
		'category__in' 		=> $category_ids,
		'post__not_in' 		=> array($post->ID),
		'showposts'			=> $OPTION['wps_cat_relatedProds_num'], 
		'orderby' 			=> $OPTION['wps_cat_relatedProds_orderby'],
		'order' 			=> $OPTION['wps_cat_relatedProds_order']
	);
	$recently_where = true;
	add_filter('posts_where', 'recently_added_where');
	$related_cats_query = new WP_Query($args); 		
	if ($related_cats_query->have_posts()) { ?>
		<div class="accordion-item open">
			<div class="heading">
				<span class="icon"></span>
				<h3>Recently Added</h3>
			</div>
			<div class="content">
				<div class="products-list">
					<?php while ($related_cats_query->have_posts()) { $related_cats_query->the_post();
						$pimage = get_product_thumb(get_the_ID());
						$price = get_post_meta(get_the_ID(), 'price', true);
						$new_price = get_post_meta(get_the_ID(), 'new_price', true);
						if($new_price) { $price = $new_price; }
					?>
					<div class="item">
						<a href="<?php the_permalink(); ?>" class="image" title="<?php the_title(); ?>">
							<?php if($pimage) { ?><img src="<?php echo $pimage; ?>" alt="" /><?php } ?>
						</a>
						<div class="holder">
							<p><?php echo get_limit_content(get_the_title(), 40, true); ?></p>
							<h4><?php product_prices_list($price); ?></h4>
						</div>
					</div>
					<?php } wp_reset_query(); ?>
				</div>
			</div>
		</div>
<?php }
} ?>
</div>
<?php
?>