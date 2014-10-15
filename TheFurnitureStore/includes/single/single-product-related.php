<div class="accordion">
<?php
// ---------------------------------------------------------------
// YOU MAY ALSO LIKE
// ---------------------------------------------------------------
$ymal_posts = array();
$ymal_ex_posts = array();
$post_brand_id = $wpdb->get_var(sprintf("SELECT tt.term_id FROM %sterm_taxonomy tt LEFT JOIN %sterm_relationships tr ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tr.object_id = %s AND tt.taxonomy = 'brand' LIMIT 0, 1", $wpdb->prefix, $wpdb->prefix, $post->ID));
// find items with such all tags
$post_tags = wp_get_post_terms($post->ID, 'post_tag');
if ($post_tags) {
	$tag_ids = array();
	foreach($post_tags as $post_tag) {
		$tag_ids[] = $post_tag->term_id;
	}
	$args = array(
		'tag__and' 		   => $tag_ids,
		'post__not_in' 	   => array($post->ID),
		'posts_per_page'   => $OPTION['wps_tag_relatedProds_num'], 
		'orderby' 		   => 'date',
		'order' 		   => 'DESC',
		'suppress_filters' => false
	);
	$recently_where = true;
	add_filter('posts_where', 'recently_added_where');
	$ymal_posts = get_posts($args);
	if (!$ymal_posts) {
		$args = array(
			'tag__in' 		   => $tag_ids,
			'post__not_in' 	   => array($post->ID),
			'posts_per_page'   => $OPTION['wps_tag_relatedProds_num'], 
			'orderby' 		   => 'date',
			'order' 		   => 'DESC',
			'suppress_filters' => false
		);
		$recently_where = true;
		add_filter('posts_where', 'recently_added_where');
		$ymal_posts = get_posts($args);
	}
	if ($ymal_posts) {
		foreach($ymal_posts as $ymal_post) {
			$ymal_ex_posts[] = $ymal_post->ID;
		}
	}
	// find items with such tags and brand
	if (count($ymal_posts) < 3 && $post_brand_id) {
		if ($post_brand_id) {
		$args = array(
			'tag__in' 		   => $tag_ids,
			'post__not_in' 	   => array($post->ID),
			'posts_per_page'   => $OPTION['wps_tag_relatedProds_num'],
			'orderby' 		   => 'date',
			'order' 		   => 'DESC',
			'suppress_filters' => false,
			'tax_query' => array(
				array(
					'taxonomy' => 'brand',
					'terms' => $post_brand_id
				)
			)
		);
		$recently_where = true;
		add_filter('posts_where', 'recently_added_where');
		$tags_and_brands_posts = get_posts($args);
		if ($tags_and_brands_posts) {
			foreach($tags_and_brands_posts as $tags_and_brands_post) {
				if (!in_array($tags_and_brands_post->ID, $ymal_ex_posts) && count($ymal_posts) < 3) {
					$ymal_posts[] = $tags_and_brands_post;
					$ymal_ex_posts[] = $tags_and_brands_post->ID;
				}
			}
		}
	}
}
if (count($ymal_posts) < 3) {
	// find items with such brand
	$ppp = $OPTION['wps_tag_relatedProds_num'] - count($ymal_posts);
	$args = array(
		'post__not_in' 	   => array($post->ID),
		'posts_per_page'   => $OPTION['wps_tag_relatedProds_num'],
		'orderby' 		   => 'date',
		'order' 		   => 'DESC',
		'suppress_filters' => false,
		'tax_query' => array(
			array(
				'taxonomy' => 'brand',
				'terms' => $post_brand_id
			)
		)
	);
	$recently_where = true;
	add_filter('posts_where', 'recently_added_where');
	$brands_posts = get_posts($args);
	if ($brands_posts) {
		foreach($brands_posts as $brands_post) {
			if (!in_array($brands_post->ID, $ymal_ex_posts) && count($ymal_posts) < 3) {
				$ymal_posts[] = $brands_post;
				$ymal_ex_posts[] = $brands_post->ID;
			}
		}
	}
}
if (count($ymal_posts)) {
?>
	<div class="accordion-item open">
		<div class="heading">
			<span class="icon"></span>
			<h3>You may also like</h3>
		</div>
		<div class="content">
			<div class="products-list">
				<?php foreach($ymal_posts as $ymal_post) {
					$pimage = get_product_thumb($ymal_post->ID);
					$price = get_post_meta($ymal_post->ID, 'price', true);
					$new_price = get_post_meta($ymal_post->ID, 'new_price', true);
					if($new_price) { $price = $new_price; }
				?>
				<div class="item">
					<a href="<?php echo get_permalink($ymal_post->ID); ?>" class="image" title="<?php echo $ymal_post->post_title; ?>">
						<?php if($pimage) { ?><img src="<?php echo $pimage; ?>" alt="" /><?php } ?>
					</a>
					<div class="holder">
						<p><?php echo get_limit_content($ymal_post->post_title, 40, true); ?></p>
						<h4><?php product_prices_list($price); ?></h4>
					</div>
				</div>
				<?php } ?>
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