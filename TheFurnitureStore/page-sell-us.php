<?php
/*
Template Name: Sell Us Page
*/
global $OPTION, $current_user, $sellers_error, $wpdb;
$soldnmb = $wpdb->get_var(sprintf("SELECT COUNT(sc.item_id) FROM %swps_shopping_cart sc LEFT JOIN %swps_orders o ON o.oid = sc.order_id WHERE o.level = '7'", $wpdb->prefix, $wpdb->prefix));
?>
<?php get_header(); ?>

<?php if ($_GET['tlcadditem'] == 'true') { ?>
	<link rel="stylesheet" type="text/css" href="<?php echo TEMPLURL; ?>/css/jquery.autocomplete.css" />
	<script type="text/javascript" src="<?php echo TEMPLURL; ?>/js/jquery.autocomplete.js"></script>
<?php } ?>
<script type="text/javascript" src="<?php echo TEMPLURL; ?>/js/swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?php echo TEMPLURL; ?>/js/jquery.swfupload.js"></script>

<div class="cf add-item-main">
	<div class="add-item-content">
		<div class="row-counter">
			<strong class="items-counter">
				<?php for ($s=1; $s<=strlen($soldnmb); $s++) {
					$n = substr($soldnmb, $s - 1, 1);
					$class = 'num'.$s;
					if ($s == 1) { $class .= ' first'; }
					if ($s == strlen($soldnmb)) { $class .= ' last'; }
					?>
					<span class="<?php echo $class; ?>"><i data-num="<?php echo $n; ?>"><?php echo $s; ?></i></span>
				<?php } ?>
			</strong> designer items sold
		</div>
		<script type="text/javascript">
			var cstop = 0;
			jQuery(document).ready(function(){
				var cnmb = jQuery('.items-counter span').size();
				for (var s=1; s<=cnmb; s++) {
					sellcounter(s, 1);
				}
				cstop = 1;
			});
			function sellcounter(n, tm) {
				if (cstop < n) {
					jQuery('.items-counter .num'+n+' i').css('margin-top', '-324px');
					jQuery('.items-counter .num'+n+' i').animate({'margin-top':-36}, '', function(){
						tm++;
						sellcounter(n, tm);
					});
				} else {
					var num = jQuery('.items-counter .num'+n+' i').attr('data-num');
					var mtop = 324 - (num * 36);
					jQuery('.items-counter .num'+n+' i').animate({'margin-top':-mtop});
					setTimeout(function(){ cstop++; }, 200);
				}
			}
		</script>
		<ul>
			<li>Most items sell within 60 days</li>
			<li>Get paid by cheque or bank transfer after your item sells</li>
			<li>Selling made easy: we do all the work for you!</li>
		</ul>
		<h3 style="padding: 21px 0"><a href="#what-you-can-sell" class="what-you-can-sell">What You Can Sell</a></h3>
		<h4>Questions?</h4>
		<div class="contact-row v1">
			<p>
				See our <a href="http://luxcloset.staging.wpengine.com/faqs">FAQs</a> or
				<span class="i-phone"><?php echo $OPTION['wps_shop_questions_phone']; ?></span>
				<a class="i-email" href="mailto:<?php echo $OPTION['wps_shop_questions_email']; ?>"><?php echo $OPTION['wps_shop_questions_email']; ?></a>
			</p>
		</div>
		<div class="contact-row v2">
			<h4>ARE YOU A PROFESSIONAL SELLER?</h4>
			<a class="btn-orange" href="<?php echo get_permalink($OPTION['wps_professional_seller_page']); ?>">CLICK HERE</a>
		</div>
	</div>
<?php
$item_number = 1;
if ($_POST['SellersAction'] == 'indivseller_add_item') {
	$item_number = $_POST['item_number'];
	$user_email = $_POST['user_email'];
	$user_pass = $_POST['user_pass'];
	$user_phone = $_POST['user_phone'];
	$item_user = $_POST['item_user'];
}
if (!is_array($item_includes)) { $item_includes = array(); }
$item_user_phone = get_user_meta($current_user->ID, 'phone', true);

?>
	<form id="indivseller-add-item" method="POST" class="form-add add-item" enctype="multipart/form-data" onsubmit="return indivseller_presubmit_form();">
		<input type="hidden" name="SellersAction" value="indivseller_add_item">
		<input type="hidden" name="item_number" value="" class="item-number">
		<h3>Submit an Item</h3>
		<?php if (strlen($sellers_error)) {
			echo '<p class="errors" style="color:#FF0000">'.$sellers_error.'</p>';
		} ?>
		<?php if (!is_user_logged_in()) { ?>
		<div class="row border-bottom">
			<div class="column width-206" id="item-user-email">
				<label>Your Email</label>
				<input type="text" name="user_email" value="<?php echo $user_email; ?>">
			</div>
			<div class="column width-206" id="item-user-pass">
				<label>Create or enter existing password</label>
				<input type="password" name="user_pass" value="<?php echo $user_pass; ?>">
			</div>
		</div>
		<?php } ?>
		<?php if (!strlen($item_user_phone) && $_GET['tlcadditem'] != 'true') { ?>
		<div class="row">
			<div id="item-user-phone">
				<label class="left"> Telephone no.*</label>
				<input name="user_phone" type="text" class="right width-320" value="<?php echo $user_phone; ?>" placeholder="only numbers">
			</div>
		</div>
		<?php } ?>
		<?php if ($_GET['tlcadditem'] == 'true') { ?>
		<div class="row" id="item-user">
			<label>Select User *</label>
			<input type="text" name="item_user" value="<?php echo $item_user; ?>" style="width:260px; float:left;"><img src="<?php bloginfo('template_url'); ?>/images/loading-ajax.gif" style="float:left; margin:4px 0 0 5px; display:none;">
		</div>
		<?php } ?>
		<div id="forms-box">
			<?php for($in=1; $in<=$item_number; $in++) {
				$show_form = false;
				if ($_POST['SellersAction'] == 'indivseller_add_item') {
					$item_category = $_POST['item_category'][$in];
					$item_brand = $_POST['item_brand'][$in];
					$item_name = $_POST['item_name'][$in];
					$item_your_price = $_POST['item_your_price'][$in];
					$item_selection = $_POST['item_selection'][$in];
					$item_pictures = $_POST['item_pictures'][$in];
					if (strlen($item_name)) {
						$show_form = true;
					}
				} else {
					$show_form = true;
				}
				if ($show_form) {
				?>
					<div id="item-form-<?php echo $in; ?>">
						<div class="row<?php if ($in > 1) { echo ' border-top'; } ?>">
							<div class="column width-206 item-category">
								<label>Category *</label>
								<div class="custom-select">
									<?php $seller_categories = get_terms('seller-category', 'hide_empty=0&orderby=id&order=asc');
									if ($seller_categories) { ?>
									<select name="item_category[<?php echo $in; ?>]">
										<option value="">-- Select Category --</option>
										<?php foreach($seller_categories as $seller_category) { $s = ''; if ($seller_category->term_id == $item_category) { $s = ' SELECTED'; } ?>
										<option value="<?php echo $seller_category->term_id; ?>"<?php echo $s; ?>><?php echo $seller_category->name; ?></option>
										<?php } ?>
									</select>
									<?php } ?>
								</div>
							</div>
							<div class="column width-206 item-brand">
								<label>Brand *</label>
								<div class="custom-select">
									<?php $tax_brands = get_terms('brand', 'hide_empty=0');
									if ($tax_brands) { ?>
									<select name="item_brand[<?php echo $in; ?>]">
										<option value="">-- Select Brand --</option>
										<?php foreach($tax_brands as $tax_brand) { $s = ''; if ($tax_brand->term_id == $item_brand) { $s = ' SELECTED'; } ?>
										<option value="<?php echo $tax_brand->term_id; ?>"<?php echo $s; ?>><?php echo $tax_brand->name; ?></option>
										<?php } ?>
										<option value="other" style="margin-top:7px;">Other</option>
									</select>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="row border-bottom v1">
							<div class="column width-206 item-name">
								<label>Item Name/Description *</label>
								<div id="item-name">
									<input type="text" name="item_name[<?php echo $in; ?>]" value="<?php echo $item_name; ?>">
								</div>
							</div>
							<div class="column width-206 item-your-price">
								<label>Your Asking Price, <?php echo $_SESSION["currency-code"]; ?></label>
								<input type="text" name="item_your_price[<?php echo $in; ?>]" value="<?php echo $item_your_price; ?>">
							</div>
							<p class="small">Louis Vuitton Damier Ebene Speedy 30 <i>or</i> <br>Prada Small Purple Leather Bag</p>
						</div>
						<div class="row border-bottom item-condition">
							<label>Condition: *</label>
							<div class="row-check">
								<?php
								if ($OPTION['wps_excluded_selections']) {
									$excl = unserialize($OPTION['wps_excluded_selections']);
									$excl = implode(',', $excl);
								}
								$tax_selections = get_terms('selection', 'hide_empty=0&orderby=id&order=asc&exclude='.$excl);
								if ($tax_selections) { ?>
									<div class="item-conditions">
									<?php foreach($tax_selections as $tax_selection) { $c = ''; if ($tax_selection->term_id == $item_selection) { $c = ' CHECKED'; } ?>
										<span class="check-row">
											<input type="radio" name="item_selection[<?php echo $in; ?>]" value="<?php echo $tax_selection->term_id; ?>"<?php echo $c; ?>>
											<span class="label"><?php echo $tax_selection->name; ?> (<?php echo $tax_selection->description; ?>)</span>
										</span>
									<?php } ?>
									</div>
								<?php } ?>
							</div>
						</div>
						<div class="row item-photos">
							<label>Attach Pictures: (max 5 pictures)</label>
							<div id="item-pictures-box-<?php echo $in; ?>">
								<div class="max-upload-error">Please upload a maximum of 5 pictures.</div>
								<input type="button" class="ipupload" />
								<ol class="uploaded-pics">
									<?php if ($item_pictures) { $item_pictures = explode(';', $item_pictures); ?>
										<?php foreach($item_pictures as $item_picture) { ?>
										<li><img src="<?php echo get_post_thumb($item_picture, 61, 61, true); ?>" rel="<?php echo get_post_thumb($item_picture); ?>"><span class="cancel" title="Remove" style="display:block;">&nbsp;</span></li>
										<?php } ?>
									<?php } ?>
								</ol>
								<input type="hidden" name="item_pictures[<?php echo $in; ?>]" class="ipictures" value="">
							</div>
						</div>
					</div>
					<script type="text/javascript">jQuery(function(){ swf_upload_init(<?php echo $in; ?>); });</script>
				<?php } // if (strlen($_POST['item_name'][$in])) { ?>
			<?php } // for($in=1; $in<=$item_number; $in++) { ?>
		</div>
		<a href="#add-another-item" class="btn-aaitem">Add another item</a>
		<div class="row row-1" id="item-terms-agree">
			<span class="check-row">
				<input type="checkbox" name="terms_agree">
				<label class="label">I agree to the Luxury Closet’s <a href="<?php echo get_permalink($OPTION['wps_terms_and_conditions_page']); ?>" target="_blank">Terms & Conditions</a></label>
			</span>
		</div>
		<input type="submit" value="Submit" class="btn-orange w-128">
		<input type="hidden" name="utm_source" id="utm_source">
		<input type="hidden" name="utm_medium" id="utm_medium">
		<input type="hidden" name="utm_campaign" id="utm_campaign">
		<input type="hidden" name="utm_content" id="utm_content">
		<input type="hidden" name="utm_term" id="utm_term">
	</form>
</div>
<script type="text/javascript">
itemnmb = <?php echo $item_number; ?>;
</script>

<div class="new-item-form" style="display:none;">
	<div id="item-form-(IN)">
		<div class="row border-top">
			<div class="column width-206 item-category">
				<label>Category *</label>
				<div class="custom-select">
					<?php if ($seller_categories) { ?>
					<select name="item_category[(IN)]">
						<option value="">-- Select Category --</option>
						<?php foreach($seller_categories as $seller_category) { ?>
						<option value="<?php echo $seller_category->term_id; ?>"><?php echo $seller_category->name; ?></option>
						<?php } ?>
					</select>
					<?php } ?>
				</div>
			</div>
			<div class="column width-206 item-brand">
				<label>Brand *</label>
				<div class="custom-select">
					<?php if ($tax_brands) { ?>
					<select name="item_brand[(IN)]">
						<option value="">-- Select Brand --</option>
						<?php foreach($tax_brands as $tax_brand) { ?>
						<option value="<?php echo $tax_brand->term_id; ?>"><?php echo $tax_brand->name; ?></option>
						<?php } ?>
						<option value="other" style="margin-top:7px;">Other</option>
					</select>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="row border-bottom v1">
			<div class="column width-206 item-name">
				<label>Item Name/Description *</label>
				<div id="item-name">
					<input type="text" name="item_name[(IN)]" value="">
				</div>
			</div>
			<div class="column width-206 item-your-price">
				<label>Your Asking Price, <?php echo $_SESSION["currency-code"]; ?></label>
				<input type="text" name="item_your_price[(IN)]" value="">
			</div>
			<p class="small">Louis Vuitton Damier Ebene Speedy 30 <i>or</i> <br>Prada Small Purple Leather Bag</p>
		</div>
		<div class="row border-bottom item-condition">
			<label>Condition: *</label>
			<div class="row-check">
				<?php if ($tax_selections) { ?>
					<div class="item-conditions">
					<?php foreach($tax_selections as $tax_selection) { ?>
						<span class="check-row">
							<input type="radio" name="item_selection[(IN)]" value="<?php echo $tax_selection->term_id; ?>">
							<span class="label"><?php echo $tax_selection->name; ?> (<?php echo $tax_selection->description; ?>)</span>
						</span>
					<?php } ?>
					</div>
				<?php } ?>
			</div>
		</div>
		<div class="row item-photos">
			<label>Attach Pictures: (max 5 pictures)</label>
			<div id="item-pictures-box-(IN)">
				<div class="max-upload-error">Please upload a maximum of 5 pictures.</div>
				<input type="button" class="ipupload" />
				<ol class="uploaded-pics"></ol>
				<input type="hidden" name="item_pictures[(IN)]" class="ipictures" value="">
			</div>
		</div>
		<div class="remove-item-box">
			<a href="#remove-item" class="remove-item" onclick="sell_us_remove_item((IN)); return false;">Remove Item</a>
		</div>
	</div>
</div>

<?php get_footer(); ?>