<?php
/*

Template Name: Reserved Bags

*/

check_logged_in();

get_header();

$DEFAULT = show_default_view();

if($DEFAULT) { ?>
	<div class="alignright" id="main_col">
		<?php
		product_sort_select();
		
		global $OPTION;
		$_GET['wnew']    = 'true';
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
	<?php
}  ?>
<div class="sidebar page_sidebar noprint alignleft" data-ttttt="">
	<?php dynamic_sidebar('category_widget_area'); ?>
</div>
<div class="whats-new-pg" style="display:none;">true</div>
<?php get_footer(); ?>
		
