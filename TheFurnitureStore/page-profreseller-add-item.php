<?php
/*
Template Name: Profreseller Add Item Page
*/
global $OPTION, $current_user, $sellers_error;

get_header();

if (is_user_logged_in() && (in_array('profseller', $current_user->roles) || in_array('administrator', $current_user->roles))) {

$item_category = $_GET['item_category'];
if ($_POST['SellersAction'] == 'profreseller_add_item' || $_POST['prof_currency_change'] == 'true') {
	$item_category = $_POST['item_category'];
	$item_name = $_POST['item_name'];
	$item_brand = $_POST['item_brand'];
	$item_desc = $_POST['item_desc'];
	$item_retail_price = $_POST['item_retail_price'];
	$item_your_price = $_POST['item_your_price'];
	$item_condition_desc = $_POST['item_condition_desc'];
	$item_selection = $_POST['item_selection'];
	$item_length = $_POST['item_length'];
	$item_height = $_POST['item_height'];
	$item_width = $_POST['item_width'];
	$item_handle_drop = $_POST['item_handle_drop'];
	$item_exterior_material = $_POST['item_exterior_material'];
	$item_interior_material = $_POST['item_interior_material'];
	$item_hardware = $_POST['item_hardware'];
	$item_includes = $_POST['item_includes'];
	$item_colour = $_POST['item_colour'];
	$item_style = $_POST['item_style'];
	$item_size = $_POST['item_size'];
	$item_heel_size = $_POST['item_heel_size'];
	$item_case_diameter = $_POST['item_case_diameter'];
	$item_watch_bracelet_size = $_POST['item_watch_bracelet_size'];
	$item_movement_type = $_POST['item_movement_type'];
	$item_upper_material = $_POST['item_upper_material'];
	$item_lining_material = $_POST['item_lining_material'];
	$item_sole_material = $_POST['item_sole_material'];
	$item_bracelet_material = $_POST['item_bracelet_material'];
	$item_case_material = $_POST['item_case_material'];
	$item_ring_size = $_POST['item_ring_size'];
	$item_ring_width = $_POST['item_ring_width'];
	$item_ring_height = $_POST['item_ring_height'];
	$item_necklace_length = $_POST['item_necklace_length'];
	$item_earring_width = $_POST['item_earring_width'];
	$item_earring_height = $_POST['item_earring_height'];
	$item_bracelet_size = $_POST['item_bracelet_size'];
	$item_bracelet_length = $_POST['item_bracelet_length'];
	$item_metal = $_POST['item_metal'];

	$item_selling_price = sellers_get_selling_price($item_your_price);

	if (strlen($item_includes) && !is_array($item_includes) && strpos($item_includes, ';')) {
		$item_includes = explode(';', $item_includes);
	}
}
if (!is_array($item_includes)) { $item_includes = array(); }
if (!is_array($item_metal)) { $item_metal = array(); }
$split_categories = sellers_get_split_categories();
?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<script>
var scat_type = "";
var bags_cats = ["<?php echo implode('","', $split_categories['bags']); ?>"];
var shoes_cats = ["<?php echo implode('","', $split_categories['shoes']); ?>"];
var watches_cats = ["<?php echo implode('","', $split_categories['watches']); ?>"];
var sunglasses_cats = ["<?php echo implode('","', $split_categories['sunglasses']); ?>"];
var jewelry_cats = ["<?php echo implode('","', $split_categories['jewelry']); ?>"];
</script>

<h1 class="main-title"><?php the_title(); ?></h1>
<ul class="tabset">
	<li><a href="<?php echo get_permalink($OPTION['wps_profreseller_my_items_page']); ?>">My Items</a></li>
	<li><a href="<?php echo get_permalink($OPTION['wps_profreseller_my_items_page']); ?>?view=my-orders">My Orders</a></li>
	<li><a href="<?php echo get_permalink($OPTION['wps_profreseller_my_items_page']); ?>?view=my-info">My Info</a></li>
</ul>
<?php
if (strlen($sellers_error)) {
	echo '<p class="errors" style="color:#FF0000">'.$sellers_error.'</p>';
}
?>
<form id="profseller-add-item" method="POST" class="form-add profsell-form" enctype="multipart/form-data">
	<input type="hidden" name="SellersAction" value="profreseller_add_item">
	<input type="hidden" name="save_draft" id="item-save-draft" value="">
	<input type="hidden" name="item_category_type" id="item-category-type" value="">
	<div class="row" id="item-category">
		<div class="column width-532">
			<label>Category *</label>
			<div class="custom-select">
				<?php echo sellers_get_categories_dropdown('item_category', $item_category, 2); ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="column width-260" id="item-style">
			<label>Subcategory</label>
			<div class="custom-select">
				<?php $all_sellers_categories = sellers_get_categories();
				if ($all_sellers_categories) { ?>
				<select name="item_style" onchange="seller_subcategory_change();">
					<option value="">-- Select Subcategory --</option>
					<?php foreach($all_sellers_categories as $sc_id => $sc_data) { ?>
					<?php if (count($sc_data['parents']) == 2) { ?>
					<option class="sc sc-<?php echo implode(' sc-', $sc_data['parents']); ?>" value="<?php echo $sc_id; ?>"<?php if ($item_style == $sc_id) { echo ' SELECTED'; } ?>><?php echo $sc_data['name']; ?></option>
					<?php }} ?>
				</select>
				<?php } ?>
			</div>
		</div>
		<div class="column width-260" id="item-colour">
			<label>Colour *</label>
			<div class="custom-select">
				<?php $tax_colours = get_terms('colour', 'hide_empty=0');
				if ($tax_colours) { ?>
				<select name="item_colour">
					<option value="">-- Select Colour --</option>
					<?php foreach($tax_colours as $tax_colour) { ?>
					<option value="<?php echo $tax_colour->term_id; ?>"<?php if ($item_colour == $tax_colour->term_id) { echo ' SELECTED'; } ?>><?php echo $tax_colour->name; ?></option>
					<?php } ?>
				</select>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="column width-310" id="item-name">
			<label>Item Name *</label>
			<input type="text" name="item_name" value="<?php echo $item_name; ?>">
		</div>
		<div class="column width-211" id="item-brand">
			<label>Brand *</label>
			<div class="custom-select">
				<?php $tax_brands = get_terms('brand', 'hide_empty=0');
				if ($tax_brands) { ?>
				<select name="item_brand">
					<option value="">&nbsp;&nbsp;-- Select Brand --</option>
					<?php foreach($tax_brands as $tax_brand) { ?>
					<option value="<?php echo $tax_brand->term_id; ?>"<?php if ($item_brand == $tax_brand->term_id) { echo ' SELECTED'; } ?>><?php echo $tax_brand->name; ?></option>
					<?php } ?>
				</select>
				<?php } ?>
			</div>
		</div>
	</div>
	<div id="item-desc">
		<label>Description</label>
		<textarea name="item_desc" placeholder="Please describe the material, style and overall look of your item."><?php echo $item_desc; ?></textarea>
	</div>
	<div class="row">
		<div class="column width-166" id="item-retail-price" style="width:174px;">
			<label>Original Retail Price, <?php echo $_SESSION["currency-code"]; ?></label>
			<input type="text" name="item_retail_price" value="<?php echo $item_retail_price; ?>">
		</div>
		<div class="column width-166" id="item-your-price">
			<label>Your Payout, <?php echo $_SESSION["currency-code"]; ?> *</label>
			<input type="text" name="item_your_price" value="<?php echo $item_your_price; ?>" onblur="calculate_selling_price();">
		</div>
		<div class="column">
			<label>LuxuryCloset Selling Price</label>
			<div id="item-selling-price" style="padding:3px 5px 4px 0px;"><span><?php echo format_price($item_selling_price, true); ?></span><img src="<?php bloginfo('template_url'); ?>/images/loading-ajax.gif" style="display:none;"></div>
		</div>
	</div>
	<div class="row">
		<div class="column width-320" id="item-condition">
			<label>Condition *</label>
			<div class="item-conditions">
				<?php
				$excl = unserialize($OPTION['wps_excluded_selections']);
				$tax_selections = get_terms('selection', 'hide_empty=0&orderby=id&order=asc&exclude='.implode(',', $excl));
				if ($tax_selections) { ?>
					<?php foreach($tax_selections as $tax_selection) { ?>
					<span class="check-row">
						<input type="radio" name="item_selection" value="<?php echo $tax_selection->term_id; ?>"<?php if ($item_selection == $tax_selection->term_id) { echo ' CHECKED'; } ?>>
						<span class="label"><?php echo $tax_selection->name; ?> (<?php echo $tax_selection->description; ?>)</span>
					</span>
					<?php } ?>
				</select>
				<?php } ?>
			</div>
		</div>
		<div class="column" id="item-condition-desc">
			<label>Condition Description *</label>
			<textarea name="item_condition_desc" style="width:185px; height:60px;"><?php echo $item_condition_desc; ?></textarea>
			<div class="icd-notes">Please accurately describe the condition of your item's exterior and interior (scratches, marks, stains, damage, missing parts etc).</div>
		</div>
	</div>
	<div class="row item-dimensions" id="bags-dimensions" style="display:none;">
		<div class="column width-342">
			<label class="full-width">Dimensions (CM): * <a href="#dimensions-desc" class="help" rel="dimensions-desc">&nbsp;</a><div class="help-desc dimensions-desc" style="background:#FFF;margin-left:130px;"><p>Handle Drop is measured from high point of the strap to bag opening.</p><div class="center"><img src="<?php bloginfo('template_url') ?>/images/product/img-10.jpg" width="249" height="201" alt="" /></div></div></label>
			<div class="column first width-114" id="item-length" style="width:110px;">
				<label>Length:</label>
				<input type="text" name="item_length" value="<?php echo $item_length; ?>">
			</div>
			<div class="column width-114" id="item-height" style="width:110px;">
				<label>Height:</label>
				<input type="text" name="item_height" value="<?php echo $item_height; ?>">
			</div>
			<div class="column right width-114" id="item-width" style="width:110px;">
				<label>Width:</label>
				<input type="text" name="item_width" value="<?php echo $item_width; ?>">
			</div>
		</div>
		<div class="column width-177" id="item-handle-drop" style="padding-top:30px;">
			<label class="full-width">Handle Drop (CM)</label>
			<input type="text" name="item_handle_drop" value="<?php echo $item_handle_drop; ?>">
		</div>
	</div>
	<div class="row item-dimensions" id="shoes-dimensions" style="display:none;">
		<label class="full-width">Dimensions:</label>
		<div class="column first width-114" id="item-size">
			<label>Size: *</label>
			<div class="custom-select" style="float:left;">
				<?php $sizes = sellers_get_sizes(); ?>
				<select name="item_size">
					<option value="">-- Select Size --</option>
					<?php foreach($sizes as $size) { $s = ''; if ($size == $item_size) { $s = ' SELECTED'; } ?>
					<option value="<?php echo $size; ?>"<?php echo $s; ?>><?php echo $size; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="column width-114" id="item-heel-size">
			<label>Heel Size (CM):</label>
			<input type="text" name="item_heel_size" value="<?php echo $item_heel_size; ?>">
		</div>
	</div>
	<div class="row" id="rings-dimensions" style="display:none;">
		<label class="full-width">Ring Dimensions:</label>
		<div class="row" style="padding-bottom:7px;">
			<div class="column" id="item-ring-size" style="width:450px;">
				<label style="width:80px; float:left;">Ring Size: *</label>
				<div class="custom-select" style="float:left;">
					<?php $sizes = sellers_get_ring_sizes(); ?>
					<select name="item_ring_size" style="width:200px;">
						<option value="">-- Select Size --</option>
						<?php foreach($sizes as $size) { $s = ''; if ($size == $item_ring_size) { $s = ' SELECTED'; } ?>
						<option value="<?php echo $size; ?>"<?php echo $s; ?>><?php echo $size; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="column width-114" id="item-ring-width" style="width:125px;">
				<label>Ring Width (mm): *</label>
				<input type="text" name="item_ring_width" value="<?php echo $item_ring_width; ?>">
			</div>
			<div class="question-icon" style="float:left; margin:5px 5px 0 5px;"><a href="#question-diagram-rings" title="Rings diagram" onclick="question_colorbox('question-diagram-rings');"><img src="<?php echo TEMPLURL; ?>/images/question-icon.gif"></a></div>
			<div style="float:left; margin-left:20px;">
				<div class="column width-114" id="item-ring-height" style="width:120px;">
					<label>Ring Height (mm):</label>
					<input type="text" name="item_ring_height" value="<?php echo $item_ring_height; ?>">
				</div>
				<div class="question-icon" style="float:left; margin:5px 5px 0 5px;"><a href="#question-diagram-rings" title="Rings diagram" onclick="question_colorbox('question-diagram-rings');"><img src="<?php echo TEMPLURL; ?>/images/question-icon.gif"></a></div>
			</div>
		</div>
	</div>
	<div class="row" id="necklaces-dimensions" style="display:none;">
		<div class="column width-114" id="item-necklace-length" style="width:155px;">
			<label>Necklaces Length (CM): *</label>
			<input type="text" name="item_necklace_length" value="<?php echo $item_necklace_length; ?>">
		</div>
		<div class="question-icon" style="float:left; margin:5px 5px 0 5px;"><a href="#question-diagram-necklaces" title="Necklaces diagram" onclick="question_colorbox('question-diagram-necklaces');"><img src="<?php echo TEMPLURL; ?>/images/question-icon.gif"></a></div>
	</div>
	<div class="row" id="earrings-dimensions" style="display:none;">
		<div class="column width-114" id="item-earring-width" style="width:130px;">
			<label>Earring Width (mm):</label>
			<input type="text" name="item_earring_width" value="<?php echo $item_earring_width; ?>">
		</div>
		<div class="question-icon" style="float:left; margin:5px 5px 0 5px;"><a href="#question-diagram-earrings" title="Earrings diagram" onclick="question_colorbox('question-diagram-earrings');"><img src="<?php echo TEMPLURL; ?>/images/question-icon.gif"></a></div>
		<div style="float:left; margin-left:20px;">
			<div class="column width-114" id="item-earring-height" style="width:135px;">
				<label>Earring Height (mm):</label>
				<input type="text" name="item_earring_height" value="<?php echo $item_earring_height; ?>">
			</div>
			<div class="question-icon" style="float:left; margin:5px 5px 0 5px;"><a href="#question-diagram-earrings" title="Earrings diagram" onclick="question_colorbox('question-diagram-earrings');"><img src="<?php echo TEMPLURL; ?>/images/question-icon.gif"></a></div>
		</div>
	</div>
	<div class="row" id="bracelets-dimensions" style="display:none;">
		<div class="column width-114" id="item-bracelet-size">
			<label>Bracelet Size: *</label>
			<input type="text" name="item_bracelet_size" value="<?php echo $item_bracelet_size; ?>">
		</div>
		<div class="question-icon" style="float:left; margin:5px 5px 0 5px;"><a href="#question-diagram-bracelets" title="Bracelets size description" onclick="question_colorbox('question-diagram-bracelets');"><img src="<?php echo TEMPLURL; ?>/images/question-icon.gif"></a></div>
		<div style="float:left; margin-left:20px;">
			<div class="column width-114" id="item-bracelet-length" style="width:135px;">
				<label>Bracelet Length (CM):</label>
				<input type="text" name="item_bracelet_length" value="<?php echo $item_bracelet_length; ?>">
			</div>
		</div>
	</div>
	<div class="row item-dimensions" id="watches-dimensions" style="display:none;">
		<div class="column width-342">
			<label class="full-width">Dimensions:</label>
			<div class="column first width-114" id="item-case-diameter">
				<label>Case Diameter: *</label>
				<input type="text" name="item_case_diameter" value="<?php echo $item_case_diameter; ?>">
			</div>
			<div class="column width-114" id="item-watch-bracelet-size">
				<label>Bracelet Size:</label>
				<input type="text" name="item_watch_bracelet_size" value="<?php echo $item_watch_bracelet_size; ?>">
			</div>
		</div>
		<div class="column width-177" id="item-movement-type" style="padding-top:21px;">
			<label class="full-width">Movement Type: *</label>
			<input type="text" name="item_movement_type" value="<?php echo $item_movement_type; ?>">
		</div>
	</div>
	<div class="row item-material" id="bags-material" style="display:none;">
		<div class="column width-166" id="item-exterior-material">
			<label>Exterior Material</label>
			<input type="text" name="item_exterior_material" value="<?php echo $item_exterior_material; ?>">
		</div>
		<div class="column width-166" id="item-interior-material">
			<label>Interior Material</label>
			<input type="text" name="item_interior_material" value="<?php echo $item_interior_material; ?>">
		</div>
		<div class="column width-177" id="item-hardware">
			<label>Hardware</label>
			<input type="text" name="item_hardware" value="<?php echo $item_hardware; ?>">
		</div>
	</div>
	<div class="row item-material" id="shoes-material" style="display:none;">
		<div class="column width-166" id="item-upper-material">
			<label>Upper Material</label>
			<input type="text" name="item_upper_material" value="<?php echo $item_upper_material; ?>">
		</div>
		<div class="column width-166" id="item-lining-material">
			<label>Lining Material</label>
			<input type="text" name="item_lining_material" value="<?php echo $item_lining_material; ?>">
		</div>
		<div class="column width-177" id="item-sole-material">
			<label>Sole Material</label>
			<input type="text" name="item_sole_material" value="<?php echo $item_sole_material; ?>">
		</div>
	</div>
	<div class="row item-material" id="watches-material" style="display:none;">
		<div class="column width-166" id="item-bracelet-material">
			<label>Bracelet Material *</label>
			<input type="text" name="item_bracelet_material" value="<?php echo $item_bracelet_material; ?>">
		</div>
		<div class="column width-166" id="item-case-material">
			<label>Case Material *</label>
			<input type="text" name="item_case_material" value="<?php echo $item_case_material; ?>">
		</div>
	</div>
	<div class="row" id="jewelry-watches-metal" style="display:none;">
		<div class="column width-260">
			<div id="item-metal">
				<label>Metal: *</label>
			</div>
			<div class="row-check item-metal">
				<?php $metals = sellers_get_category_options('metal');
				foreach($metals as $metal) { ?>
				<span class="check-row">
					<input type="checkbox" name="item_metal[]" value="<?php echo $metal; ?>"<?php if (in_array($metal, $item_metal)) { echo ' CHECKED'; } ?>>
					<span class="label"><?php echo $metal; ?></span>
				</span>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="column width-260">
			<div id="item-includes">
				<label>Includes: *</label>
			</div>
			<div class="row-check item-includes">
				<?php $sellers_includes = sellers_get_includes();
				$excluded_incl = array('booklet');
				foreach($sellers_includes as $si_key => $si_val) { if (!in_array($si_key, $excluded_incl)) { ?>
				<span class="check-row">
					<input type="checkbox" name="item_includes[]" value="<?php echo $si_key; ?>"<?php if (in_array($si_key, $item_includes)) { echo ' CHECKED'; } ?>>
					<span class="label"><?php echo $si_val; ?></span>
				</span>
				<?php }} ?>
			</div>
		</div>
	</div>
	<div class="row">
		<label>Upload Picture(s): *</label>
		<input type="file" name="item_picture[]" multiple>
	</div>
	<div class="row">
		<input type="button" class="btn-grey" value="Save Draft" onclick="jQuery('form.form-add #item-save-draft').val('true'); seller_presubmit_form();">
		<input type="button" class="btn-orange" value="Submit" onclick="jQuery('form.form-add #item-save-draft').val(''); seller_presubmit_form();">
	</div>
	<input type="hidden" name="utm_source" id="utm_source">
	<input type="hidden" name="utm_medium" id="utm_medium">
	<input type="hidden" name="utm_campaign" id="utm_campaign">
	<input type="hidden" name="utm_content" id="utm_content">
	<input type="hidden" name="utm_term" id="utm_term">
</form>
<form class="prof-currency-change-form" method="POST">
	<input type="hidden" name="prof_currency_change" value="true">
	<input type="hidden" name="item_category" id="cc_item_category">
	<input type="hidden" name="item_name" id="cc_item_name">
	<input type="hidden" name="item_brand" id="cc_item_brand">
	<input type="hidden" name="item_desc" id="cc_item_desc">
	<input type="hidden" name="item_retail_price" id="cc_item_retail_price">
	<input type="hidden" name="item_your_price" id="cc_item_your_price">
	<input type="hidden" name="item_condition_desc" id="cc_item_condition_desc">
	<input type="hidden" name="item_selection" id="cc_item_selection">
	<input type="hidden" name="item_length" id="cc_item_length">
	<input type="hidden" name="item_height" id="cc_item_height">
	<input type="hidden" name="item_width" id="cc_item_width">
	<input type="hidden" name="item_handle_drop" id="cc_item_handle_drop">
	<input type="hidden" name="item_exterior_material" id="cc_item_exterior_material">
	<input type="hidden" name="item_interior_material" id="cc_item_interior_material">
	<input type="hidden" name="item_hardware" id="cc_item_hardware">
	<input type="hidden" name="item_includes" id="cc_item_includes">
	<input type="hidden" name="item_colour" id="cc_item_colour">
	<input type="hidden" name="item_style" id="cc_item_style">
	<input type="hidden" name="item_size" id="cc_item_size">
	<input type="hidden" name="item_heel_size" id="cc_item_heel_size">
	<input type="hidden" name="item_case_diameter" id="cc_item_case_diameter">
	<input type="hidden" name="item_watch_bracelet_size" id="cc_item_watch_bracelet_size">
	<input type="hidden" name="item_movement_type" id="cc_item_movement_type">
	<input type="hidden" name="item_upper_material" id="cc_item_upper_material">
	<input type="hidden" name="item_lining_material" id="cc_item_lining_material">
	<input type="hidden" name="item_sole_material" id="cc_item_sole_material">
	<input type="hidden" name="item_bracelet_material" id="cc_item_bracelet_material">
	<input type="hidden" name="item_case_material" id="cc_item_case_material">
	<input type="hidden" name="item_ring_size" id="cc_item_ring_size">
	<input type="hidden" name="item_ring_width" id="cc_item_ring_width">
	<input type="hidden" name="item_ring_height" id="cc_item_ring_height">
	<input type="hidden" name="item_necklaces_length" id="cc_item_necklaces_length">
	<input type="hidden" name="item_earring_width" id="cc_item_earring_width">
	<input type="hidden" name="item_earring_height" id="cc_item_earring_height">
	<input type="hidden" name="item_bracelet_size" id="cc_item_bracelet_size">
	<input type="hidden" name="item_bracelet_length" id="cc_item_bracelet_length">
</form>
<div style="display:none;">
	<div class="profseller-calculate-price" id="profseller-calculate-price">
		<div class="pcp-search">
			<ul>
				<li><label>Category:</label>
				<?php echo sellers_get_categories_dropdown('pcp_category', '', 2); ?></li>
				<li><label>Brand:</label>
				<select name="pcp_brand" id="pcp_brand">
					<option value="">-- Select Brand --</option>
					<?php foreach($tax_brands as $tax_brand) { ?>
					<option value="<?php echo $tax_brand->term_id; ?>"><?php echo $tax_brand->name; ?></option>
					<?php } ?>
				</select></li>
				<li><label>Condition:</label>
				<select name="pcp_selection" id="pcp_selection">
					<option value="">-- Select Condition --</option>
					<?php foreach($tax_selections as $tax_selection) { ?>
					<option value="<?php echo $tax_selection->term_id; ?>"><?php echo $tax_selection->name; ?></option>
					<?php } ?>
				</select></li>
				<li><label>Colour:</label>
				<select name="pcp_colour" id="pcp_colour">
					<option value="">-- Select Colour --</option>
					<?php foreach($tax_colours as $tax_colour) { ?>
					<option value="<?php echo $tax_colour->term_id; ?>"><?php echo $tax_colour->name; ?></option>
					<?php } ?>
				</select></li>
			</ul>
			<ul>
				<li class="pcp-includes"><label>Includes:</label>
				<?php foreach($sellers_includes as $si_key => $si_val) { ?>
				<input type="checkbox" name="pcp_includes[]" value="<?php echo $si_key; ?>"> <?php echo $si_val; ?>
				<div class="clear"></div>
				<?php } ?>
				</li>
				<li><input type="button" value="Calculate Price" class="pcp-calculate"><img src="<?php bloginfo('template_url'); ?>/images/loading-ajax.gif" class="pcp-loading"></li>
			</ul>
		</div>
		<div class="clear"></div>
		<div class="pcp-data">
			<p>Please select search criteria and click Calculate Price button.</p>
		</div>
	</div>
	<div id="question-diagram-rings"><img src="<?php echo TEMPLURL; ?>/images/diagram-rings.png"></div>
	<div id="question-diagram-necklaces"><img src="<?php echo TEMPLURL; ?>/images/diargam-necklaces.png"></div>
	<div id="question-diagram-earrings"><img src="<?php echo TEMPLURL; ?>/images/diargam-earrings.png"></div>
	<div id="question-diagram-bracelets"><div style="padding:40px 20px; width:500px;">Please enter the size of the bracelet. Eg: Small/Medium/Large or 16, 17, 18 etc.</div></div>
</div>

<?php endwhile; endif; ?>

<?php
} else {
	echo '<p>You are not allowed to view this page.</p>';
}

get_footer(); ?>