<?php 
/**
 * Template name: All Cats
 */
$all_cats 		= array(
	'all-handbags' => array(
		'cats' => array('tax_cat_2' => '1,421')
	),	
	'all-clothes'  => array(
		'cats' => array('tax_cat_2' => '828,880'),
	),
	'all-jewelry'  => array(
		'cats' => array('tax_cat_2' => '605,559'),
	),
	'all-shoes'    => array(
		'cats' => array('tax_cat_2' => '228,248'),
	),
	'all-watches'  => array(
		'cats' => array('tax_cat_2' => '262,419'),
	),
	'all-accessories' => array(
		'cats' => array('tax_cat_2' => '411,420'),
	)
);
if(isset($all_cats[$post->post_name]))
{
	$_GET = array_merge($_GET, $all_cats[$post->post_name]);
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
		printf('<div class="%s">', $the_div_class );
		dynamic_sidebar('category_widget_area');	
		printf('</div><!-- category_sidebar -->');
	} 
	?>
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
	<?php
} 
get_footer(); ?>