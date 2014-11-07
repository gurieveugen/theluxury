<div class="accordion">
<?php
global $OPTION;
$wps_excluded_categories = unserialize($OPTION['wps_excluded_sellers_categories']);

$categories = get_the_category($post->ID);
$category_ids = array();
if ($categories) {
	foreach($categories as $category){
		if (!in_array($category->term_id, $wps_excluded_categories)) {
			$category_ids[] = $category->term_id;
		}
	}
}
$post_brand_id = $wpdb->get_var(sprintf("SELECT tt.term_id FROM %sterm_taxonomy tt LEFT JOIN %sterm_relationships tr ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tr.object_id = %s AND tt.taxonomy = 'brand' LIMIT 0, 1", $wpdb->prefix, $wpdb->prefix, $post->ID));
$post_colour_id = $wpdb->get_var(sprintf("SELECT tt.term_id FROM %sterm_taxonomy tt LEFT JOIN %sterm_relationships tr ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tr.object_id = %s AND tt.taxonomy = 'colour' LIMIT 0, 1", $wpdb->prefix, $wpdb->prefix, $post->ID));
$post_size_id = $wpdb->get_var(sprintf("SELECT tt.term_id FROM %sterm_taxonomy tt LEFT JOIN %sterm_relationships tr ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tr.object_id = %s AND tt.taxonomy = 'size' LIMIT 0, 1", $wpdb->prefix, $wpdb->prefix, $post->ID));
$post_ring_size_id = $wpdb->get_var(sprintf("SELECT tt.term_id FROM %sterm_taxonomy tt LEFT JOIN %sterm_relationships tr ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tr.object_id = %s AND tt.taxonomy = 'ring-size' LIMIT 0, 1", $wpdb->prefix, $wpdb->prefix, $post->ID));
$post_clothes_size_id = $wpdb->get_var(sprintf("SELECT tt.term_id FROM %sterm_taxonomy tt LEFT JOIN %sterm_relationships tr ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tr.object_id = %s AND tt.taxonomy = 'clothes-size' LIMIT 0, 1", $wpdb->prefix, $wpdb->prefix, $post->ID));
$post_tags = wp_get_post_terms($post->ID, 'post_tag');
// ---------------------------------------------------------------
// YOU MAY ALSO LIKE
// ---------------------------------------------------------------
$ymal_posts = array();
$ymal_ex_posts = array($post->ID);

$def_args = array(
	'posts_per_page'	=> 3, 
	'orderby' 			=> $OPTION['wps_cat_relatedProds_orderby'],
	'order' 			=> $OPTION['wps_cat_relatedProds_order']
);
// category, brand, colour, sizes
$args = $def_args;
$args['post__not_in'] = $ymal_ex_posts;
if ($category_ids) {
	$args['category__and'] = $category_ids;
}
if ($post_brand_id) {
	$args['tax_query'][] = array('taxonomy' => 'brand', 'terms' => $post_brand_id);
}
if ($post_colour_id) {
	$args['tax_query'][] = array('taxonomy' => 'colour', 'terms' => $post_colour_id);
}
if ($post_size_id) {
	$args['tax_query'][] = array('taxonomy' => 'size', 'terms' => $post_size_id);
}
if ($post_ring_size_id) {
	$args['tax_query'][] = array('taxonomy' => 'ring-size', 'terms' => $post_ring_size_id);
}
if ($post_clothes_size_id) {
	$args['tax_query'][] = array('taxonomy' => 'clothes-size', 'terms' => $post_clothes_size_id);
}
$recently_where = true;
add_filter('posts_where', 'recently_added_where');
$ymal_posts = get_posts($args);
if (!$ymal_posts || count($ymal_posts) < 3) {
	if ($ymal_posts) {
		foreach($ymal_posts as $ymal_post) {
			$ymal_ex_posts[] = $ymal_post->ID;
		}
	}
	// category, brand, colour
	$args = $def_args;
	$args['post__not_in'] = $ymal_ex_posts;
	if ($category_ids) { $args['category__and'] = $category_ids; }
	if ($post_brand_id) { $args['tax_query'][] = array('taxonomy' => 'brand', 'terms' => $post_brand_id); }
	if ($post_colour_id) { $args['tax_query'][] = array('taxonomy' => 'colour', 'terms' => $post_colour_id); }
	$recently_where = true;
	add_filter('posts_where', 'recently_added_where');
	$restricted_ymal_posts = get_posts($args);
	if ($restricted_ymal_posts) {
		foreach($restricted_ymal_posts as $restricted_ymal_post) {
			$ymal_posts[] = $restricted_ymal_post;
			$ymal_ex_posts[] = $restricted_ymal_post->ID;
		}
	}
	if (!$ymal_posts || count($ymal_posts) < 3) {
		// category, brand
		$args = $def_args;
		$args['post__not_in'] = $ymal_ex_posts;
		if ($category_ids) { $args['category__and'] = $category_ids; }
		if ($post_brand_id) { $args['tax_query'][] = array('taxonomy' => 'brand', 'terms' => $post_brand_id); }
		$recently_where = true;
		add_filter('posts_where', 'recently_added_where');
		$restricted_ymal_posts = get_posts($args);
		if ($restricted_ymal_posts) {
			foreach($restricted_ymal_posts as $restricted_ymal_post) {
				$ymal_posts[] = $restricted_ymal_post;
				$ymal_ex_posts[] = $restricted_ymal_post->ID;
			}
		}
		if (!$ymal_posts || count($ymal_posts) < 3) {
			// category
			$args = $def_args;
			$args['post__not_in'] = $ymal_ex_posts;
			if ($category_ids) { $args['category__and'] = $category_ids; }
			$recently_where = true;
			add_filter('posts_where', 'recently_added_where');
			$restricted_ymal_posts = get_posts($args);
			if ($restricted_ymal_posts) {
				foreach($restricted_ymal_posts as $restricted_ymal_post) {
					$ymal_posts[] = $restricted_ymal_post;
					$ymal_ex_posts[] = $restricted_ymal_post->ID;
				}
			}
			if (!$ymal_posts || count($ymal_posts) < 3) {
				if ($post_tags) {
					$tag_ids = array();
					foreach($post_tags as $post_tag) {
						$tag_ids[] = $post_tag->term_id;
					}
					// tags (AND)
					$args = $def_args;
					$args['post__not_in'] = $ymal_ex_posts;
					$args['tag__and'] = $tag_ids;
					$recently_where = true;
					add_filter('posts_where', 'recently_added_where');
					$restricted_ymal_posts = get_posts($args);
					if ($restricted_ymal_posts) {
						foreach($restricted_ymal_posts as $restricted_ymal_post) {
							$ymal_posts[] = $restricted_ymal_post;
							$ymal_ex_posts[] = $restricted_ymal_post->ID;
						}
					}
					if (!$ymal_posts || count($ymal_posts) < 3) {
						// tags (IN)
						$args = $def_args;
						$args['post__not_in'] = $ymal_ex_posts;
						$args['tag__in'] = $tag_ids;
						$recently_where = true;
						add_filter('posts_where', 'recently_added_where');
						$restricted_ymal_posts = get_posts($args);
						if ($restricted_ymal_posts) {
							foreach($restricted_ymal_posts as $restricted_ymal_post) {
								$ymal_posts[] = $restricted_ymal_post;
								$ymal_ex_posts[] = $restricted_ymal_post->ID;
							}
						}
						if (!$ymal_posts || count($ymal_posts) < 3) {
							if ($post_brand_id) {
								// brand
								$args = $def_args;
								$args['post__not_in'] = $ymal_ex_posts;
								$args['tax_query'][] = array('taxonomy' => 'brand', 'terms' => $post_brand_id);
								$recently_where = true;
								add_filter('posts_where', 'recently_added_where');
								$restricted_ymal_posts = get_posts($args);
								if ($restricted_ymal_posts) {
									foreach($restricted_ymal_posts as $restricted_ymal_post) {
										$ymal_posts[] = $restricted_ymal_post;
										$ymal_ex_posts[] = $restricted_ymal_post->ID;
									}
								}
							}
						}
					}
				}
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
				<?php $ymalnmb = 1; foreach($ymal_posts as $ymal_post) {
					$pimage = get_product_thumb($ymal_post->ID);
					$price = get_post_meta($ymal_post->ID, 'price', true);
					$new_price = get_post_meta($ymal_post->ID, 'new_price', true);
					if($new_price) { $price = $new_price; }
					if ($ymalnmb < 4) {
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
				<?php $ymalnmb++; }} ?>
			</div>
		</div>
	</div>
<?php
} ?>
<?php
// ---------------------------------------------------------------
// RECENTLY ADDED
// ---------------------------------------------------------------
if ($categories) {
	$category_ids = array();
	foreach($categories as $individual_category){
		$category_ids[] = $individual_category->term_id;
	}
		
	$args = array(
		'category__in' 		=> $category_ids,
		'post__not_in' 		=> array($post->ID),
		'posts_per_page'	=> $OPTION['wps_cat_relatedProds_num'], 
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