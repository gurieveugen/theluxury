<?php 
$qo = get_queried_object();

get_header(); ?>
	<div class="alignright" id="main_col">
		<?php 
		$term_featured_image = get_field('featured_image', 'post_tag_'.$qo->term_id);
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
		$_GET['cats']['tag']  = $qo->term_id;
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