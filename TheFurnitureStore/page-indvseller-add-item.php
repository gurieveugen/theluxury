<?php
/*
Template Name: Indvidual Seller Add Item Page
*/

global $OPTION, $current_user;

get_header();

$item_category = $_GET['item_category'];
if ($_POST['SellersAction'] == 'indivseller_add_item') {
	$item_category = $_POST['item_category'];
	$item_name = $_POST['item_name'];
	$item_brand = $_POST['item_brand'];
	$item_retail_price = $_POST['item_retail_price'];
	$item_your_price = $_POST['item_your_price'];
	$item_price_currency = $_POST['item_price_currency'];
	$item_selection = $_POST['item_selection'];
	$item_includes = $_POST['item_includes'];
	$user_email = $_POST['user_email'];
	$user_pass = $_POST['user_pass'];
	$user_phone = $_POST['user_phone'];
	$item_user = $_POST['item_user'];
}
if (!is_array($item_includes)) { $item_includes = array(); }
$item_selling_price = sellers_get_selling_price($item_your_price);
$item_user_phone = get_user_meta($current_user->ID, 'phone', true);
?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<?php if ($_GET['tlcadditem'] == 'true') { ?>
	<link rel="stylesheet" type="text/css" href="<?php echo TEMPLURL; ?>/css/jquery.autocomplete.css" />
	<script type="text/javascript" src="<?php echo TEMPLURL; ?>/js/jquery.autocomplete.js"></script>
<?php } ?>

<h1 class="main-title"><?php the_title(); ?></h1>
<?php if (is_user_logged_in()) { ?>
<ul class="tabset">
	<li><a href="<?php echo get_permalink($OPTION['wps_indvseller_my_items_page']); ?>">My Items</a></li>
	<li><a href="<?php echo get_permalink($OPTION['wps_indvseller_my_items_page']); ?>?view=my-info">My Info</a></li>
</ul>
<?php } ?>

<?php
if (strlen($sellers_error)) {
	echo '<p class="errors" style="color:#FF0000">'.$sellers_error.'</p>';
}
?>
<form id="indivseller-add-item" method="POST" class="form-add" enctype="multipart/form-data" onsubmit="return indivseller_presubmit_form();">
	<input type="hidden" name="SellersAction" value="indivseller_add_item">
	<?php if (!is_user_logged_in()) { ?>
	<div class="row" style="border-top:1px solid #C1C1C1;border-bottom:1px solid #C1C1C1;padding:10px 0px;margin-bottom:10px;">
		<div class="column width-260" id="item-user-email">
			<label>Your Email</label>
			<input type="text" name="user_email" value="<?php echo $user_email; ?>">
		</div>
		<div class="column width-260" id="item-user-pass">
			<label>Password</label>
			<input type="password" name="user_pass" value="<?php echo $user_pass; ?>">
		</div>
	</div>
	<?php } ?>
	<?php if ($_GET['tlcadditem'] == 'true') { ?>
	<div class="row" id="item-user">
		<label>Select User *</label>
		<input type="text" name="item_user" value="<?php echo $item_user; ?>" style="width:260px; float:left;"><img src="<?php bloginfo('template_url'); ?>/images/loading-ajax.gif" style="float:left; margin:4px 0 0 5px; display:none;">
	</div>
	<?php } ?>
	<div class="row" id="item-category">
		<label>Category *</label>
		<div class="select">
			<?php $seller_categories = get_terms('seller-category', 'hide_empty=0&orderby=id&order=asc');
			if ($seller_categories) { ?>
			<select name="item_category">
				<option value="">-- Select Category --</option>
				<?php foreach($seller_categories as $seller_category) { ?>
				<option value="<?php echo $seller_category->term_id; ?>"<?php if ($item_category == $seller_category->term_id) { echo ' SELECTED'; } ?>><?php echo $seller_category->name; ?></option>
				<?php } ?>
			</select>
			<?php } ?>
		</div>
	</div>
	<div class="row">
		<div class="column width-310" id="item-name">
			<label>Item Name/Description *</label>
			<input type="text" name="item_name" value="<?php echo $item_name; ?>">
		</div>
		<div class="column width-210" id="item-brand">
			<label>Brand *</label>
			<div class="custom-select">
				<?php $tax_brands = get_terms('brand', 'hide_empty=0');
				if ($tax_brands) { ?>
				<select name="item_brand">
					<option value="">-- Select Brand --</option>
					<?php foreach($tax_brands as $tax_brand) { ?>
					<option value="<?php echo $tax_brand->term_id; ?>"<?php if ($item_brand == $tax_brand->term_id) { echo ' SELECTED'; } ?>><?php echo $tax_brand->name; ?></option>
					<?php } ?>
					<option value="other" style="margin-top:7px;"<?php if ($item_brand == 'other') { echo ' SELECTED'; } ?>>Other</option>
				</select>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="column width-260" id="item-retail-price">
			<label>Original Purchase Price, <?php echo $_SESSION["currency-code"]; ?></label>
			<input type="text" name="item_retail_price" value="<?php echo $item_retail_price; ?>">
		</div>
		<div class="column width-260" id="item-your-price">
			<label>Your Asking Price, <?php echo $_SESSION["currency-code"]; ?></label>
			<input type="text" name="item_your_price" value="<?php echo $item_your_price; ?>">
		</div>
	</div>
	<div class="row">
		<div class="column" id="item-condition" style="width:320px;">
			<label>Condition: *</label>
			<div class="row-check">
				<?php
				$excl = unserialize($OPTION['wps_excluded_selections']);
				$tax_selections = get_terms('selection', 'hide_empty=0&orderby=id&order=asc&exclude='.implode(',', $excl));
				if ($tax_selections) { ?>
					<div class="item-conditions">
					<?php foreach($tax_selections as $tax_selection) { ?>
					<input type="radio" name="item_selection" value="<?php echo $tax_selection->term_id; ?>"<?php if ($item_selection == $tax_selection->term_id) { echo ' CHECKED'; } ?>><span><?php echo $tax_selection->name; ?> (<?php echo $tax_selection->description; ?>)</span><br>
					<?php } ?>
					</div>
				<?php } ?>
			</div>
		</div>
		<div class="column" style="width:200px;">
			<label>Includes:</label>
			<div class="row-check item-includes" style="line-height:22px;">
				<?php $sellers_includes = sellers_get_includes();
				$excluded_incl = array('booklet');
				foreach($sellers_includes as $si_key => $si_val) { if (!in_array($si_key, $excluded_incl)) { ?>
				<input type="checkbox" name="item_includes[]" value="<?php echo $si_key; ?>"<?php if (in_array($si_key, $item_includes)) { echo ' CHECKED'; } ?>>
				<label style="width:170px;"><?php echo $si_val; ?></label><br>
				<?php }} ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="column width-260" id="item-pictures">
			<label>Upload Pictures: *</label>
			<input type="file" name="item_picture[]" multiple>
		</div>
		<?php if (!strlen($item_user_phone)) { ?>
			<div class="column width-260" id="item-user-phone">
				<label>Your Telephone *</label>
				<input type="text" name="user_phone" value="<?php echo $user_phone; ?>">
			</div>
		<?php } ?>
	</div>
	<div class="row">
		<input type="submit" value="Submit" class="btn-submit">
	</div>
	<?php if (strlen($OPTION['wps_sellers_terms_and_conditions_text'])) { ?>
	<div class="row">
		<p><?php echo $OPTION['wps_sellers_terms_and_conditions_text']; ?></p>
	</div>
	<?php } ?>
	<input type="hidden" name="utm_source" id="utm_source">
	<input type="hidden" name="utm_medium" id="utm_medium">
	<input type="hidden" name="utm_campaign" id="utm_campaign">
	<input type="hidden" name="utm_content" id="utm_content">
	<input type="hidden" name="utm_term" id="utm_term">
</form>

<?php if ($_GET['tlcadditem'] == 'true') { ?>
	<script type="text/javascript">
		jQuery("#item-user input").autocomplete(
			siteurl+'/index.php?non_cache=true&ajax_sellers_action=getusers',
			{
				delay:10,
				minChars:2,
				matchSubset:1,
				matchContains:1,
				cacheLength:10,
				autoFill:true,
				multiple: false,
				scroll: false
			}
		);
	</script>
<?php } ?>

<?php endwhile; endif; ?>

<?php
	get_footer();
?>