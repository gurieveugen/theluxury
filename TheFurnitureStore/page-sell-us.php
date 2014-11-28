<?php
/*
Template Name: Sell Us Page
*/
global $OPTION, $current_user, $sellers_error, $wpdb;
$soldnmb = $wpdb->get_var(sprintf("SELECT COUNT(sc.item_id) FROM %swps_shopping_cart sc LEFT JOIN %swps_orders o ON o.oid = sc.order_id WHERE o.level IN ('6', '7')", $wpdb->prefix, $wpdb->prefix));
?>
<?php get_header(); ?>

<?php if ($_GET['tlcadditem'] == 'true') { ?>
	<link rel="stylesheet" type="text/css" href="<?php echo TEMPLURL; ?>/css/jquery.autocomplete.css" />
	<script type="text/javascript" src="<?php echo TEMPLURL; ?>/js/jquery.autocomplete.js"></script>
<?php } ?>
<script type="text/javascript" src="<?php echo TEMPLURL; ?>/js/swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?php echo TEMPLURL; ?>/js/jquery.swfupload.js"></script>

<div class="add-item-main">
	<img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/img-add-item.png" height="539" width="324" alt="" class="img-bg" />
	<div class="holder">
	<div class="frame cf">
	<div class="add-item-content">
		<?php the_content(); ?>
		<div class="prof-contact-row">
			<p>Are you a professional seller? <a href="<?php echo get_permalink($OPTION['wps_professional_seller_page']); ?>">Click Here</a></p>
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
$step_nmb = 0;
?>
	<form id="indivseller-add-item" method="POST" class="form-add add-item indivseller-add-item" enctype="multipart/form-data" onsubmit="return indivseller_presubmit_form();">
		<input type="hidden" name="SellersAction" value="indivseller_add_item">
		<input type="hidden" name="item_number" value="" class="item-number">
		<h3>Submit an Item <small>to get a quick quote</small></h3>
		<?php if (!strlen($item_user_phone) && $_GET['tlcadditem'] != 'true') { ?>
		<div class="f-block">
			<strong class="num"><?php $step_nmb++; echo $step_nmb; ?>.</strong>
			<div id="item-user-phone" class="ovh">
				<input name="user_phone" type="text" class="width-270" value="<?php echo $user_phone; ?>" placeholder="Telephone no. * only numbers">
			</div>
		</div>
		<?php } ?>
		<?php if ($_GET['tlcadditem'] == 'true') { ?>
		<div class="f-block" id="item-user">
			<strong class="num"><?php $step_nmb++; echo $step_nmb; ?>.</strong>
			<div class="ovh">
				<input type="text" name="item_user" value="<?php echo $item_user; ?>" style="width:260px; float:left;" placeholder="Select User *">
				<img src="<?php bloginfo('template_url'); ?>/images/loading-ajax.gif" style="float:left; margin:4px 0 0 5px; display:none;">
			</div>
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
					if ($in > 1) { $step_nmb = 0; }
				?>
					<div id="item-form-<?php echo $in; ?>">
						<div class="f-block">
							<strong class="num"><?php $step_nmb++; echo $step_nmb; ?>.</strong>
							<div class="ovh">
								<div class="column width-176 item-category">
									<div class="custom-select">
										<?php if ($seller_categories) { ?>
										<select name="item_category[<?php echo $in; ?>]" onchange="indivseller_change_cat(<?php echo $in; ?>, this.value);">
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
								<div class="column width-176 item-brand">
									<div class="custom-select">
										<?php
										if ($catbrands) { ?>
										<select name="item_brand[<?php echo $in; ?>]" class="item-brand-<?php echo $in; ?>">
											<option value="">-- Select Brand --</option>
										</select>
										<div style="display:none;">
											<?php foreach($catbrands as $cid => $tbrands) { ?>
												<div class="cat-brands-<?php echo $in; ?>-<?php echo $cid; ?>">
												<?php foreach($tbrands as $brand_id => $brand_name) {
													$s = '';
													if ($cid == $item_category && $brand_id == $item_brand) { $s = ' SELECTED'; } ?>
													<option value="<?php echo $brand_id; ?>"<?php echo $s; ?>><?php echo $brand_name; ?></option>
												<?php } ?>
												</div>
											<?php } ?>
										</div>
										<?php if ($item_category) { ?>
											<script>indivseller_change_cat(<?php echo $in; ?>, <?php echo $item_category; ?>);</script>
										<?php } ?>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
						<div class="f-block">
							<strong class="num"><?php $step_nmb++; echo $step_nmb; ?>.</strong>
							<div class="ovh">
								<div class="column width-176 item-name">
									<div id="item-name">
										<input type="text" name="item_name[<?php echo $in; ?>]" value="<?php echo $item_name; ?>" placeholder="Item Name/Description">
									</div>
								</div>
								<div class="column width-176 item-your-price">
									<!--<label>Your Asking Price, <?php echo $_SESSION["currency-code"]; ?></label>-->
									<input type="text" name="item_your_price[<?php echo $in; ?>]" value="<?php echo $item_your_price; ?>" placeholder="Your Asking Price, <?php echo $_SESSION["currency-code"]; ?>">
								</div>
								<p class="small">Louis Vuitton Damier Ebene Speedy 30 <i>or</i> <br>Prada Small Purple Leather Bag</p>
							</div>
						</div>
						<div class="f-block item-condition">
							<strong class="num"><?php $step_nmb++; echo $step_nmb; ?>.</strong>
							<div class="ovh">
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
						</div>
						<div class="f-block item-photos">
							<strong class="num"><?php $step_nmb++; echo $step_nmb; ?>.</strong>
							<div class="ovh">
								<label class="label-pictures">Attach Pictures: (max 5 pictures)</label>
								<?php if (wp_is_mobile()) { ?>
									<input type="file" name="item_pictures[<?php echo $in; ?>][]" multiple />
								<?php } else { ?>
									<div id="item-pictures-box-<?php echo $in; ?>" class="item-pictures-box">
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
								<?php } ?>
							</div>
						</div>
						<div class="remove-item-box"<?php if ($item_number < 2) { echo ' style="display:none;"'; } ?>>
							<a href="#remove-item" class="remove-item" onclick="sell_us_remove_item(1); return false;">Remove Item</a>
						</div>
					</div>
					<script type="text/javascript">jQuery(function(){ swf_upload_init(<?php echo $in; ?>); });</script>
				<?php } // if (strlen($_POST['item_name'][$in])) { ?>
			<?php } // for($in=1; $in<=$item_number; $in++) { ?>
		</div>
		<div class="f-block last" id="item-terms-agree">
			<span class="check-row lh-26">
				<input type="checkbox" name="terms_agree">
				<label class="label">I agree to the Luxury Closet’s <a href="<?php echo get_permalink($OPTION['wps_terms_and_conditions_page']); ?>" target="_blank">Terms & Conditions</a></label>
			</span>
		</div>
		<div class="cf"><a href="#add-another-item" class="btn-add-another-item">Add another item+</a></div>
		<p class="errors" style="color:#FF0000;<?php if (!strlen($sellers_error)) { echo ' display:none;'; } ?>"><?php echo $sellers_error; ?></p>
		<input type="submit" value="Submit" class="btn-orange w-115">
		<input type="hidden" name="utm_source" id="utm_source">
		<input type="hidden" name="utm_medium" id="utm_medium">
		<input type="hidden" name="utm_campaign" id="utm_campaign">
		<input type="hidden" name="utm_content" id="utm_content">
		<input type="hidden" name="utm_term" id="utm_term">
	</form>
</div><!-- .frame -->
</div><!-- .holder -->
</div><!-- .add-item-main -->
<script type="text/javascript">
itemnmb = <?php echo $item_number; ?>;
</script>

<div class="new-item-form" style="display:none;">
	<div class="item-form-block" id="item-form-(IN)">
		<div class="f-block">
			<strong class="num">1.</strong>
			<div class="ovh">
				<div class="column width-176 item-category">
					<div class="custom-select">
						<?php if ($seller_categories) { ?>
						<select name="item_category[(IN)]" onchange="indivseller_change_cat((IN), this.value);">
							<option value="">-- Select Category --</option>
							<?php foreach($seller_categories as $scid => $seller_category) { ?>
							<option value="<?php echo $scid; ?>"><?php echo $seller_category['name']; ?></option>
								<?php if ($seller_category['childs']) {
									foreach($seller_category['childs'] as $subcid => $subname) { ?>
										<option value="<?php echo $subcid; ?>">-- <?php echo $subname; ?></option>
									<?php } ?>
								<?php } ?>
							<?php } ?>
						</select>
						<?php } ?>
					</div>
				</div>
				<div class="column width-176 item-brand">
					<div class="custom-select">
						<?php if ($catbrands) { ?>
						<select name="item_brand[(IN)]" class="item-brand-(IN)">
							<option value="">-- Select Brand --</option>
						</select>
						<div style="display:none;">
							<?php foreach($catbrands as $cid => $tbrands) { ?>
								<div class="cat-brands-(IN)-<?php echo $cid; ?>">
								<?php foreach($tbrands as $brand_id => $brand_name) { ?>
									<option value="<?php echo $brand_id; ?>"><?php echo $brand_name; ?></option>
								<?php } ?>
								</div>
							<?php } ?>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<div class="f-block">
			<strong class="num">2.</strong>
			<div class="ovh">
				<div class="column width-176 item-name">
					<div id="item-name">
						<input type="text" name="item_name[(IN)]" value="" placeholder="Item Name/Description *">
					</div>
				</div>
				<div class="column width-176 item-your-price">
					<input type="text" name="item_your_price[(IN)]" value="" placeholder="Your Asking Price, <?php echo $_SESSION["currency-code"]; ?>">
				</div>
				<p class="small">Louis Vuitton Damier Ebene Speedy 30 <i>or</i> <br>Prada Small Purple Leather Bag</p>
			</div>
		</div>
		<div class="f-block item-condition">
			<strong class="num">3.</strong>
			<label>Condition: *</label>
			<div class="ovh">
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
		</div>
		<div class="f-block item-photos">
			<strong class="num">4.</strong>
			<div class="ovh">
				<label class="label-pictures">Attach Pictures: (max 5 pictures)</label>
				<?php if (wp_is_mobile()) { ?>
					<input type="file" name="item_pictures[(IN)][]" multiple />
				<?php } else { ?>
					<div id="item-pictures-box-(IN)" class="item-pictures-box">
						<div class="max-upload-error">Please upload a maximum of 5 pictures.</div>
						<input type="button" class="ipupload" />
						<ol class="uploaded-pics"></ol>
						<input type="hidden" name="item_pictures[(IN)]" class="ipictures" value="">
					</div>
				<?php } ?>
			</div>
		</div>
		<div class="remove-item-box">
			<a href="#remove-item" class="remove-item" onclick="sell_us_remove_item((IN)); return false;">Remove Item</a>
		</div>
	</div>
</div>

<div class="featured-logos-block">
	<h3 class="title-block-center">As Featured In</h3>
	<ul class="featured-logos">
		<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/logo-cosmopolitan.png" alt="cosmopolitan"></li>
		<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/logo-elle.png" alt="elle"></li>
		<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/logo-whatson.png" alt="whatson"></li>
		<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/logo-grazia.png" alt="grazia"></li>
		<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/logo-hello.png" alt="hello"></li>
		<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/logo-timeout.png" alt="timeout"></li>
		<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/logo-ahlan.png" alt="ahlan"></li>
	</ul>
</div>
<script>
	jQuery(function(){
		jQuery('.accordion-information .acc-content').mCustomScrollbar();
		jQuery('.accordion-information .head').click(function(){
			jQuery(this).next().slideToggle(300,function(){
				jQuery(this).parents('.block').toggleClass('open');
				jQuery(this).mCustomScrollbar('update');
			});
		});	
	});
</script>
<?php $sell_questions = get_posts('post_type=sell-question&posts_per_page=-1&orderby=menu_order&order=asc');
if ($sell_questions) { $openc = ' open'; ?>
<div class="information-block">
	<h3 class="title-block-center">Questions?</h3>
	<div class="accordion-information">
		<?php foreach($sell_questions as $sell_question) { ?>
		<div class="block<?php echo $openc; ?>">
			<div class="head cf">
				<h4><?php echo $sell_question->post_title; ?></h4>
				<i class="ico"></i>
			</div>
			<div class="acc-content">
				<div class="holder cf">
					<?php echo wpautop($sell_question->post_content); ?>
				</div>
			</div>
		</div>
		<?php $openc = ''; } ?>
	</div>
</div>
<?php } ?>
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
<?php get_footer(); ?>