<?php 
/**
 * Template name: Filter
 */
$qo = get_queried_object();

if($qo->taxonomy == 'category')
{
	$cat_parents = KostulQuery::countParents($qo)+1; 
	$tax = 'tax_cat_'.$cat_parents;
}
else
{
	$taxonomies = array(
		'colour'          => 'tax_colours',
		'size'            => 'tax_sizes',
		'ring-size'       => 'tax_ring_sizes',
		'clothes-size'    => 'tax_clothes_sizes',
		'selection'       => 'tax_selections',
		'brand'           => 'tax_brands',
		'style'           => 'tax_styles',
		'price'           => 'tax_prices',
		'seller-category' => 'tax_seller_category',
	);
	$tax = $taxonomies[$qo->taxonomy];
}

get_header(); ?>
	<div class="alignright" id="main_col">
		<?php 
		$term_featured_image = get_field('featured_image', 'brand_'.$qo->term_id);
		if ($term_featured_image) 
		{
			?>
			<div class="featuredTag">
				<img src="<?php echo $term_featured_image['url']; ?>" alt="<?php echo $term->name; ?>" />
			</div>
			<?php
		}
		if($OPTION['wps_termDescr_enable']) 
		{
			echo term_description();
		}
		?>
		<?php
		product_sort_select();
		
		global $OPTION;
		$_GET['cats'][$tax]    = $qo->term_id;
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
				var last_args = <?php echo json_encode($request['last_args']); ?>;
				var visible_terms = <?php echo json_encode($request['visible_terms']); ?>;
			</script>
			<?php echo $html.$pagination->getHTML(); ?>
		</div>
	</div>
	<div class="sidebar page_sidebar noprint alignleft" data-ttttt="">
		<?php dynamic_sidebar('category_widget_area'); ?>
	</div>
<?php get_footer(); ?>