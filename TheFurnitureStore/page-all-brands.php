<?php
/*
Template Name: All Brands
*/
global $OPTION;
$type = $_GET['type'];
?>
<?php get_header(); ?>

	<div id="post-<?php the_ID(); ?>">
		<div class="brands-left">
			<h3>DESIGNERS</h3>
			<ul>
				<li<?php if (!strlen($type)) { echo ' class="active"'; } ?>><a href="<?php the_permalink(); ?>">ALL DESIGNERS</a></li>
				<li<?php if ($type == 'bags') { echo ' class="active"'; } ?>><a href="<?php the_permalink(); ?>?type=bags">BAGS DESIGNERS</a></li>
				<li<?php if ($type == 'clothes') { echo ' class="active"'; } ?>><a href="<?php the_permalink(); ?>?type=clothes">CLOTHES DESIGNERS</a></li>
				<li<?php if ($type == 'shoes') { echo ' class="active"'; } ?>><a href="<?php the_permalink(); ?>?type=shoes">SHOES DESIGNERS</a></li>
				<li<?php if ($type == 'jewelry') { echo ' class="active"'; } ?>><a href="<?php the_permalink(); ?>?type=jewelry">FINE JEWELRY DESIGNERS</a></li>
				<li<?php if ($type == 'accessories') { echo ' class="active"'; } ?>><a href="<?php the_permalink(); ?>?type=accessories">ACCESSORIES DESIGNERS</a></li>
				<li<?php if ($type == 'watches') { echo ' class="active"'; } ?>><a href="<?php the_permalink(); ?>?type=watches">WATCHES DESIGNERS</a></li>
			</ul>
		</div>
		<div class="brands-right">
			<?php
			if (strlen($type)) {
				$cats = array(
						'bags' => array($OPTION['wps_women_bags_category'], $OPTION['wps_men_bags_category']),
						'clothes' => array($OPTION['wps_women_clothes_category'], $OPTION['wps_men_clothes_category']),
						'shoes' => array($OPTION['wps_women_shoes_category'], $OPTION['wps_men_shoes_category']),
						'jewelry' => array($OPTION['wps_women_jewelry_category'], $OPTION['wps_men_jewelry_category']),
						'accessories' => array($OPTION['wps_women_accessories_category'], $OPTION['wps_men_accessories_category']),
						'watches' => array($OPTION['wps_women_watches_category'], $OPTION['wps_men_watches_category'])
					);
				$tp_brands = array();
				$tp_posts = get_posts(array('category__in' => $cats[$type], 'posts_per_page' => -1));
				if ($tp_posts) {
					foreach($tp_posts as $tp_post) {
						$post_brands = wp_get_post_terms($tp_post->ID, 'brand');
						if ($post_brands) {
							foreach($post_brands as $post_brand) {
								$tp_brands[] = $post_brand->term_id;
							}
						}
					}
					if (count($tp_brands)) {
						$wp_brands = get_terms('brand', array('include' => $tp_brands));
					}
				}
			} else {
				$wp_brands = get_terms('brand');
			}
			if ($wp_brands) {
				$total_b = count($wp_brands);
				$in_column = ceil($total_b / 3);
				$wps_brands = array();
				foreach($wp_brands as $wp_brand) {
					$fl = strtoupper(substr($wp_brand->name, 0, 1));
					$wps_brands[$fl][] = $wp_brand;
				}
				?>
				<ul class="brands-top">
					<li class="goto">GO TO:</li>
					<?php foreach($wps_brands as $bl => $bd) { ?>
						<li><a href="#<?php echo $bl; ?>"><?php echo $bl; ?></a></li>
					<?php } ?>
				</ul>
				<div class="brands-list">
					<div class="column">
						<?php $cln_nmb = 1; ?>
						<?php foreach($wps_brands as $bl => $bdata) { ?>
							<a name="<?php echo $bl; ?>"></a>
							<h3><?php echo $bl; ?></h3>
							<ul>
								<?php foreach($bdata as $brnd) { ?>
									<li><a href="<?php echo get_term_link($brnd, 'brand'); ?>"><?php echo $brnd->name; ?></a></li>
								<?php $cln_nmb++; } ?>
							</ul>
							<?php if ($cln_nmb >= $in_column) { $cln_nmb = 1; ?>
					</div>
					<div class="column">
							<?php } ?>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
			<div class="back-to-top"><a href="#top">Back to top</a></div>
		</div>
	</div><!-- page_post -->

<?php get_footer(); ?>