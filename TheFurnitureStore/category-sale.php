<?php 

if(is_category('member-bags') || is_category('reserved-bags') || is_category('sale'))
{
	auth_redirect_theme_login();
}
get_header();

//collect options
$WPS_prodCol       = $OPTION['wps_prodCol_option'];
$WPS_catCol        = $OPTION['wps_catCol_option'];
$WPS_sidebar       = $OPTION['wps_sidebar_option'];
$WPS_showposts     = $OPTION['wps_showpostsOverwrite_Option'];

$this_category     = get_category($cat);

$topParent         = NWS_get_root_category($cat,'allData');
$topParentSlug     = $topParent->slug;
$this_categorySlug = $this_category->slug;

//collect options
$orderBy           = $OPTION['wps_secondaryCat_orderbyOption'];
$order             = $OPTION['wps_secondaryCat_orderOption'];

get_cat_parent($this_category->term_id);

// sidebar location?
switch($WPS_sidebar)
{
	case 'alignRight':
		$the_float_class 	= 'alignleft';
	break;
	case 'alignLeft':
		$the_float_class 	= 'alignright';
	break;
}
// teaser?
if($OPTION['wps_teaser_enable_option']) {$the_eqcol_class = 'eqcol'; }

//set the div class	
$the_div_class 	= 'theProds clearfix '.$prodCol_class. ' '.$the_float_class.' '.$the_eqcol_class;
	
if($OPTION['wps_catDescr_enable']) 
{
	echo term_description();
} 
$_SESSION["ajax"]       = false;
product_sort_select();
?>
	<div class="<?php echo $the_div_class;?>" id="products-container">		
		<?php get_template_part('loop', 'products'); ?>
	</div><!-- theProds -->
</div><!-- main_col -->
<script>
	var cat_in_search = [];	
	cat_in_search.push(['filter-category', 'sale']);
</script>
<?php
include (TEMPLATEPATH . '/widget_ready_areas.php');
get_footer(); ?>