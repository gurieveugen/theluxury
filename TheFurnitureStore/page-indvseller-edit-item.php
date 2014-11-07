<?php
/*
Template Name: Indvidual Seller Edit Item Page
*/

global $OPTION, $current_user, $sellers_error;

get_header();

$post_id = $_POST['post_id'];
$post_data = get_post($post_id);

if (is_user_logged_in() && !in_array('profseller', $current_user->roles) && $post_data && $post_data->post_author == $current_user->ID && ($post_data->post_status == 'iseller_draft' || $post_data->post_status == 'iseller_pending' || $post_data->post_status == 'iseller_approved')) {

$item_name = $post_data->post_title;

$item_retail_price = get_post_meta($post_id, 'price', true);
$item_your_price = get_post_meta($post_id, 'item_your_price', true);
$item_includes = explode("|", get_post_meta($post_id, 'item_includes', true));

$item_retail_price = sellers_currency_price($item_retail_price);
$item_your_price = sellers_currency_price($item_your_price);

$post_categories = wp_get_post_terms($post_id, 'seller-category');
$post_brands = wp_get_post_terms($post_id, 'brand');
$post_selections = wp_get_post_terms($post_id, 'selection');
if ($post_categories) { foreach($post_categories as $post_category) { $item_category = $post_category->term_id; } }
if ($post_brands) { foreach($post_brands as $post_brand) { $item_brand = $post_brand->term_id; } }
if ($post_selections) { foreach($post_selections as $post_selection) { $item_selection = $post_selection->term_id; } }

if (!$item_brand) { $item_brand = 'other'; }

$item_pictures = sellers_get_post_pictures($post_id);

if ($_POST['SellersAction'] == 'indivseller_edit_item') {
	$item_category = $_POST['item_category'];
	$item_name = $_POST['item_name'];
	$item_brand = $_POST['item_brand'];
	$item_retail_price = $_POST['item_retail_price'];
	$item_your_price = $_POST['item_your_price'];
	$item_price_currency = $_POST['item_price_currency'];
	$item_selection = $_POST['item_selection'];
	$item_includes = $_POST['item_includes'];
}
// item brands
$item_brands = array();
$tax_brands = get_terms('brand', 'hide_empty=0');
if ($tax_brands) {
	foreach($tax_brands as $tax_brand) {
		$item_brands[$tax_brand->term_id] = $tax_brand->name;
	}
}

$catbrands = array();
$submission_form_brands = unserialize($OPTION['wps_submission_form_brands']);
if ($submission_form_brands) {
	foreach($submission_form_brands as $cid => $blist) {
		foreach($blist as $bid) {
			$catbrands[$cid][$bid] = $item_brands[$bid];
		}
	}
}

$seller_categories = array();
$scategories = get_terms('seller-category', 'hide_empty=0');
if ($scategories) {
	foreach($scategories as $scategory) {
		if ($scategory->parent) {
			$seller_categories[$scategory->parent]['childs'][$scategory->term_id] = $scategory->name;
		} else {
			$seller_categories[$scategory->term_id]['name'] = $scategory->name;
		}
	}
}
?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<script type="text/javascript" src="<?php echo TEMPLURL; ?>/js/swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?php echo TEMPLURL; ?>/js/jquery.swfupload.js"></script>
<script type="text/javascript">
jQuery(function(){ swf_upload_init(1); });
</script>

<div class="user-info-row">
	<h1 class="main-title"><?php echo $current_user->data->user_login; ?></h1>
</div>
<form id="indivseller-edit-item" method="POST" class="form-add edit-item" enctype="multipart/form-data" onsubmit="return indivseller_presubmit_form();">
	<input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
	<input type="hidden" name="SellersAction" value="indivseller_edit_item">
	<h3>Edit an Item</h3>
	<?php if (strlen($sellers_error)) {
		echo '<p class="errors" style="color:#FF0000">'.$sellers_error.'</p>';
	} ?>
	<div id="item-form-1">
		<div class="row">
			<div class="column width-216 item-category">
				<label>Category *</label>
				<div class="custom-select">
					<?php if ($seller_categories) { ?>
					<select name="item_category" onchange="indivseller_change_cat(0, this.value);">
						<option value="">-- Select Category --</option>
						<?php foreach($seller_categories as $scid => $seller_category) { $s = ''; if ($scid == $item_category) { $s = ' SELECTED'; } ?>
						<option value="<?php echo $scid; ?>"<?php echo $s; ?>><?php echo $seller_category['name']; ?></option>
							<?php if ($seller_category['childs']) {
								foreach($seller_category['childs'] as $subcid => $subname) { $s = ''; if ($subcid == $item_category) { $s = ' SELECTED'; } ?>
									<option value="<?php echo $subcid; ?>"<?php echo $s; ?>>-- <?php echo $subname; ?></option>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					</select>
					<?php } ?>
				</div>
			</div>
			<div class="column width-216 item-brand">
				<label>Brand *</label>
				<div class="custom-select">
					<?php
					if ($catbrands) { ?>
					<select name="item_brand" class="item-brand-0">
						<option value="">-- Select Brand --</option>
						<?php foreach($catbrands as $cid => $tbrands) {
							foreach($tbrands as $brand_id => $brand_name) {
								$s = '';
								$dnstyle = ' style="display:none;"';
								if ($cid == $item_category) {
									 $dnstyle = '';
									if ($brand_id == $item_brand) { $s = ' SELECTED'; }
								}
								?>
								<option value="<?php echo $brand_id; ?>" class="catop cid-<?php echo $cid; ?>"<?php echo $dnstyle.$s; ?>><?php echo $brand_name; ?></option>
							<?php } ?>
						<?php } ?>
						<option value="other" style="margin-top:7px;">Other</option>
					</select>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="row border-bottom">
			<div class="column width-286 item-name">
				<label>Item Name/Description *</label>
				<div id="item-name">
					<input type="text" name="item_name" value="<?php echo $item_name; ?>">
				</div>
				<p class="small">Louis Vuitton Damier Ebene Speedy 30 <i>or</i> <br>Prada Small Purple Leather Bag</p>
			</div>
			<div class="column width-146 item-your-price">
				<label>Your Asking Price, <?php echo $_SESSION["currency-code"]; ?></label>
				<input type="text" name="item_your_price" value="<?php echo $item_your_price; ?>">
			</div>
		</div>
		<div class="row border-bottom item-condition">
			<label>Condition: *</label>
			<div class="row-check">
				<?php
				$excl = unserialize($OPTION['wps_excluded_selections']);
				$tax_selections = get_terms('selection', 'hide_empty=0&orderby=id&order=asc&exclude='.implode(',', $excl));
				if ($tax_selections) { ?>
					<div class="item-conditions">
					<?php foreach($tax_selections as $tax_selection) { ?>
						<span class="check-row">
							<input type="radio" name="item_selection" value="<?php echo $tax_selection->term_id; ?>"<?php if ($item_selection == $tax_selection->term_id) { echo ' CHECKED'; } ?>>
							<span class="label"><?php echo $tax_selection->name; ?> (<?php echo $tax_selection->description; ?>)</span>
						</span>
					<?php } ?>
					</div>
				<?php } ?>
			</div>
		</div>
		<!--<div class="row border-bottom">
			<label>Includes:</label>
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
		</div>-->
		<div class="row item-photos">
			<label>Pictures: (max 5 pictures)</label>
			<div id="item-pictures-box-1">
				<input type="button" class="ipupload" />
				<ol class="uploaded-pics">
					<?php if ($item_pictures) { ?>
						<?php foreach($item_pictures as $item_picture) { ?>
						<li><img src="<?php echo get_post_thumb($item_picture->ID, 61, 61, true); ?>" rel="<?php echo get_post_thumb($item_picture->ID); ?>"><span class="cancel" title="Remove" style="display:block;">&nbsp;</span></li>
						<?php } ?>
					<?php } ?>
				</ol>
				<input type="hidden" name="item_pictures" class="ipictures" value="">
			</div>
		</div>
	</div>
	<div class="row">
		<input type="submit" value="Submit" class="btn-orange">
	</div>
</form>

<?php endwhile; endif; ?>

<?php
} else {
	echo '<p>You are not allowed to view this page.</p>';
}

get_footer(); ?>