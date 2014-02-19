<?php
/*
Template Name: Pricing Search Page
*/

global $wpdb, $OPTION;

get_header();

if (is_user_logged_in()) {

$sbrands = get_terms('brand', 'hide_empty=0');
$sselections = get_terms('selection', 'hide_empty=0');
$scolours = get_terms('colour', 'hide_empty=0');

$search_category = $_GET['search-category'];
$search_brand = $_GET['search-brand'];
$search_condition = $_GET['search-condition'];
$search_colour = $_GET['search-colour'];
$search_term = $_GET['search-term'];
$view = $_GET['view'];
if (!strlen($view)) { $view = 'list'; }
$pg = $_GET['pg'];
$pricing_search_items_per_page = $OPTION['wps_sellers_pricing_search_items_per_page'];
?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div class="pricing-search-page">
	<?php the_content(); ?>
	<div class="filter-section">
		<form action="<?php echo get_permalink($OPTION['wps_pricing_search_page']); ?>">
			<input type="hidden" name="pricing-search" value="true" />
			<?php echo sellers_get_categories_dropdown('search-category', $search_category); ?>
			<select name="search-brand">
				<option value="">-- Select Brand --</option>
				<?php foreach($sbrands as $sbrand) { $s = ''; if ($sbrand->term_id == $search_brand) { $s = ' SELECTED'; } ?>
				<option value="<?php echo $sbrand->term_id; ?>"<?php echo $s; ?>><?php echo $sbrand->name; ?></option>
				<?php } ?>
			</select>
			<select name="search-condition">
				<option value="">-- Select Condition --</option>
				<?php foreach($sselections as $sselection) { $s = ''; if ($sselection->term_id == $search_condition) { $s = ' SELECTED'; } ?>
				<option value="<?php echo $sselection->term_id; ?>"<?php echo $s; ?>><?php echo $sselection->name; ?></option>
				<?php } ?>
			</select>
			<select name="search-colour">
				<option value="">-- Select Colour --</option>
				<?php foreach($scolours as $scolour) { $s = ''; if ($scolour->term_id == $search_colour) { $s = ' SELECTED'; } ?>
				<option value="<?php echo $scolour->term_id; ?>"<?php echo $s; ?>><?php echo $scolour->name; ?></option>
				<?php } ?>
			</select>
			<input type="text" name="search-term" value="<?php echo $search_term; ?>">
			<input type="hidden" name="view" value="<?php echo $view; ?>" id="psfield-view">
			<input class="button-search" type="submit" value="Search" />
		</form>
		<ul class="pricing-view">
			<li<?php if ($view == 'list') { echo ' class="active"'; } ?>><img src="<?php bloginfo('template_url'); ?>/images/pricing-list.png" rel="list"></li>
			<li<?php if ($view == 'row') { echo ' class="active"'; } ?>><img src="<?php bloginfo('template_url'); ?>/images/pricing-row.png" rel="row"></li>
		</ul>
	</div>
	<?php
	if ($_GET['pricing-search'] == 'true' && (strlen($search_category) || strlen($search_brand) || strlen($search_style) || strlen($search_condition) || strlen($search_colour) || strlen($search_term))) {
		$psWhere = "";
		if (strlen($search_category)) { if (strlen($psWhere)) { $psWhere .= " AND "; } $psWhere .= " p.category = ".$search_category; }
		if (strlen($search_brand)) { if (strlen($psWhere)) { $psWhere .= " AND "; } $psWhere .= " p.brand = ".$search_brand; }
		if (strlen($search_condition)) { if (strlen($psWhere)) { $psWhere .= " AND "; } $psWhere .= " p.selection = ".$search_condition; }
		if (strlen($search_colour)) { if (strlen($psWhere)) { $psWhere .= " AND "; } $psWhere .= " p.colour = ".$search_colour; }
		if (strlen($search_term)) { if (strlen($psWhere)) { $psWhere .= " AND "; } $psWhere .= " p.style_name LIKE '%".$search_term."%'"; }
		if (strlen($psWhere)) { $psWhere = " WHERE ".$psWhere; }

		if (!strlen($pg)) { $pg = 1; }
		if (!$pricing_search_items_per_page) { $pricing_search_items_per_page = 50; }
		$psLimit = " LIMIT ".(($pg - 1) * $pricing_search_items_per_page).", ".$pricing_search_items_per_page;

		$psSql = sprintf("SELECT SQL_CALC_FOUND_ROWS p.*, cat.name as category_name, br.name as brand_name, sel.name as selection_name, cl.name as colour_name FROM %swps_pricing p 
		LEFT JOIN %sterms cat ON cat.term_id = p.category
		LEFT JOIN %sterms br ON br.term_id = p.brand
		LEFT JOIN %sterms sel ON sel.term_id = p.selection
		LEFT JOIN %sterms cl ON cl.term_id = p.colour
		%s
		ORDER BY cat.name, br.name, sel.name, cl.name, original_price %s", $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $psWhere, $psLimit);
		$pricing_records = $wpdb->get_results($psSql);
		$pricing_total = $wpdb->get_var("SELECT FOUND_ROWS()");
	?>
	<div class="results-section">
		<div class="view-section view-list"<?php if ($view == 'row') { echo ' style="display:none;"'; } ?>>
			<table>
				<tr>
					<td class="head pic">Picture</td>
					<td class="head cat">Category</td>
					<td class="head brand">Brand</td>
					<td class="head style">Style Name</td>
					<td class="head cond">Condition</td>
					<td class="head colour">Colour</td>
					<td class="head oprice">Orig Price</td>
					<td class="head hprice">High Price</td>
					<td class="head lprice">Low Price</td>
				</tr>
				<?php if ($pricing_total) {
					foreach($pricing_records as $pricing_record) {
						$original_price = sellers_currency_price($pricing_record->original_price);
						$high_price = sellers_currency_price($pricing_record->high_price);
						$low_price = sellers_currency_price($pricing_record->low_price);
					?>
				<tr>
					<td><?php if (strlen($pricing_record->photo)) { ?><a href="<?php echo get_post_thumb($pricing_record->photo, 800, 800); ?>" class="pic-zoom"><img src="<?php echo get_post_thumb($pricing_record->photo, 91, 91, true); ?>" title="Zoom +"></a><?php } else { ?><img src="<?php bloginfo('template_url'); ?>/images/pricing-no-pic.jpg"><?php } ?></td>
					<td><?php echo $pricing_record->category_name; ?></td>
					<td><?php echo $pricing_record->brand_name; ?></td>
					<td><?php echo $pricing_record->style_name; ?></td>
					<td><?php echo $pricing_record->selection_name; ?></td>
					<td><?php echo $pricing_record->colour_name; ?></td>
					<td><?php echo format_price($original_price, true); ?></td>
					<td><?php echo format_price($high_price, true); ?></td>
					<td><?php echo format_price($low_price, true); ?></td>
				</tr>
				<?php
					}
				} else { ?>
				<tr>
					<td colspan="9">Nothing found.</td>
				</tr>
				<?php } ?>
			</table>
		</div>
		<div class="view-section view-row"<?php if ($view == 'list') { echo ' style="display:none;"'; } ?>>
			<?php if ($pricing_total) { ?>
				<ul>
				<?php
					foreach($pricing_records as $pricing_record) {
						$original_price = sellers_currency_price($pricing_record->original_price);
						$high_price = sellers_currency_price($pricing_record->high_price);
						$low_price = sellers_currency_price($pricing_record->low_price);
					?>
					<li><a href="#details" rel="view-row-details-<?php echo $pricing_record->pid; ?>"><?php if (strlen($pricing_record->photo)) { ?><img src="<?php echo get_post_thumb($pricing_record->photo, 91, 91, true); ?>"><?php } else { ?><img src="<?php bloginfo('template_url'); ?>/images/pricing-no-pic.jpg"><?php } ?></a>
					<h3><?php echo $pricing_record->style_name; ?></h3>
					<h4>O: <?php echo format_price($original_price, true); ?></h4>
					<h4>H: <?php echo format_price($high_price, true); ?></h4>
					<div class="view-row-details view-row-details-<?php echo $pricing_record->pid; ?>">
						<div class="details-pic"><?php if (strlen($pricing_record->photo)) { ?><img src="<?php echo get_post_thumb($pricing_record->photo, 180, 180, true); ?>"><?php } else { ?><img src="<?php bloginfo('template_url'); ?>/images/pricing-no-pic-180.jpg"><?php } ?></div>
						<div class="details-data">
						<strong>Category:</strong> <?php echo $pricing_record->category_name; ?><br>
						<strong>Brand:</strong> <?php echo $pricing_record->brand_name; ?><br>
						<strong>Style Name:</strong> <?php echo $pricing_record->style_name; ?><br>
						<strong>Condition:</strong> <?php echo $pricing_record->selection_name; ?><br>
						<strong>Colour:</strong> <?php echo $pricing_record->colour_name; ?><br>
						<strong>Orig Price:</strong> <?php echo format_price($original_price, true); ?><br>
						<strong>High Price:</strong> <?php echo format_price($high_price, true); ?><br>
						<strong>Low Price:</strong> <?php echo format_price($low_price, true); ?></div>
					</div>
					</li>
				<?php } ?>
				</ul>
			<?php } else { ?>
				<p>Nothing found.</p>
			<?php } ?>
		</div>
		<?php if ($pricing_total > $pricing_search_items_per_page) {
		$total_pages = ceil($pricing_total / $pricing_search_items_per_page); ?>
		<div class="clear"></div>
		<div class="pagenavi-holder">
			<form id="pricing-nav">
			<input type="hidden" name="pricing-search" value="true">
			<input type="hidden" name="search-category" value="<?php echo $search_category; ?>">
			<input type="hidden" name="search-brand" value="<?php echo $search_brand; ?>">
			<input type="hidden" name="search-condition" value="<?php echo $search_condition; ?>">
			<input type="hidden" name="search-colour" value="<?php echo $search_colour; ?>">
			<input type="hidden" name="search-term" value="<?php echo $search_term; ?>">
			<input type="hidden" name="view" value="<?php echo $view; ?>" id="pricing-nav-view">
			<input type="hidden" name="pg" value="" id="pricing-nav-pg">
			<ul class="pagenavi">
				<?php if ($pg > 1) { ?><li><a href="#<?php echo ($pg - 1); ?>" class="previous" title="Previous"></a></li><?php } ?>
				<?php for ($p=1; $p<=$total_pages; $p++) { ?>
				<li><a href="#<?php echo $p; ?>"<?php if ($pg == $p) { echo ' class="current"'; } ?>><?php echo $p; ?></a></li>
				<?php } ?>
				<?php if (($pg + 1) <= $total_pages) { ?><li><a href="#<?php echo ($pg + 1); ?>" class="next" title="Next"></a></li><?php } ?>
			</ul>
			</form>
		</div>
		<?php } ?>
	</div>
	<?php } else { ?>
	<p class="def-staige">Please select search criteria.</p>
	<?php } ?>
	<div style="display:none;">
		<div class="row-view-details" id="row-view-details"></div>
	</div>
</div>
<?php endwhile; endif; ?>

<?php
} else {
	echo '<p>You are not allowed to view this page.</p>';
}

get_footer(); ?>