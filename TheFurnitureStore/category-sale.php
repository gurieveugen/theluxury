<?php 

if(is_category('member-bags') || is_category('reserved-bags') || is_category('sale'))
{
	auth_redirect_theme_login();
}
get_header();


$DEFAULT = show_default_view();

 if($DEFAULT){
	$WPS_sidebar		= $OPTION['wps_sidebar_option'];
	switch($WPS_sidebar){
		case 'alignRight':
			$the_float_class 	= 'alignleft';
		break;
		case 'alignLeft':
			$the_float_class 	= 'alignright';
		break;
	}

	$the_div_class 	= 'sidebar tag_sidebar category_sidebar noprint alignleft ';
	if (is_sidebar_active('category_widget_area')) 
	{
		printf('<div class="%s" data-ttttt="">', $the_div_class );
		dynamic_sidebar('category_widget_area');	
		printf('</div><!-- category_sidebar -->');
	} 
	?>
	<div class="alignright" id="main_col">
		<?php
		product_sort_select();
		
		global $OPTION;
		$_GET['cats']['tax_sale'] = '129';
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
} 
get_footer(); ?>