<?php 
/**
 * Template name: All Cats
 */
$all_cats 		= array(
	'all-handbags' => array(
		'cats' => array('tax_cat_2' => $OPTION["wps_women_bags_category"].','.$OPTION["wps_men_bags_category"])
	),	
	'all-clothes'  => array(
		'cats' => array('tax_cat_2' => $OPTION["wps_women_clothes_category"].','.$OPTION["wps_men_clothes_category"])
	),
	'all-jewelry'  => array(
		'cats' => array('tax_cat_2' => $OPTION["wps_women_jewelry_category"].','.$OPTION["wps_men_jewelry_category"])
	),
	'all-shoes'    => array(
		'cats' => array('tax_cat_2' => $OPTION["wps_women_shoes_category"].','.$OPTION["wps_men_shoes_category"])
	),
	'all-watches'  => array(
		'cats' => array('tax_cat_2' => $OPTION["wps_women_watches_category"].','.$OPTION["wps_men_watches_category"])
	),
	'all-accessories' => array(
		'cats' => array('tax_cat_2' => $OPTION["wps_women_accessories_category"].','.$OPTION["wps_men_accessories_category"])
	)
);
if(isset($all_cats[$post->post_name]))
{
	$_GET = array_merge($_GET, $all_cats[$post->post_name]);
}

get_header(); ?>
	<div class="alignright" id="main_col">
		<?php
		product_sort_select();
		
		global $OPTION;
		$kostul_query  = new KostulQuery();
		$request       = $kostul_query->makeRequestFromArgs($_GET);
		$columns       = intval(str_replace('tagCol', '', $OPTION['wps_tagCol_option']));
		$html          = '';
		$pagination    = new Pagination($request['count'], $request['last_args']['count'], $request['last_args']['offset']);
		

		if(is_array($request['posts']) AND count($request['posts']))
		{
			foreach ($request['posts'] as $p) 
			{
				$post = new KostulHTML($p, $columns, $OPTION);
				$html.= $post->getHTML();
			}
		}
		?>
		<div id="products-container" class="theProds clearfix  alignright eqcol">
			<script>
				var last_args = <?php echo json_encode($kostul_query->getLastArgs()); ?>;
				var visible_terms = <?php echo json_encode($request['visible_terms']); ?>;
			</script>
			<?php echo $html.$pagination->getHTML(); ?>
		</div>
	</div>
	<div class="sidebar page_sidebar noprint alignleft" data-ttttt="">
		<?php dynamic_sidebar('category_widget_area'); ?>
	</div>
<?php get_footer(); ?>