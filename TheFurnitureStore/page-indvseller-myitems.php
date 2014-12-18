<?php
/*
Template Name: Indvidual Seller My Items Page
*/

global $OPTION, $current_user;

get_header();

if (is_user_logged_in() && !in_array('profseller', $current_user->roles)) {

$seller_posts = array();
$seller_categories = get_terms('seller-category', 'hide_empty=0&orderby=id&order=asc');

$scat = trim($_GET['scat']);
if (strlen($scat)) {
	$scat_tt_id = $wpdb->get_var(sprintf("SELECT term_taxonomy_id FROM %sterm_taxonomy WHERE term_id = %s", $wpdb->prefix, $scat));
	if ($scat_tt_id) {
		$sWhere .= " AND tr.term_taxonomy_id = ".$scat_tt_id;
	}
}
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<div class="user-info-row">
	<div class="right" style="margin:0px;">
		<button class="btn-orange" onclick="window.location.href='<?php echo get_permalink($OPTION['wps_indvseller_add_item_page']); ?>';">Submit Another Item</button>
	</div>
	<h1 class="main-title"><?php echo $current_user->data->user_login; ?></h1>
</div>
<?php if (isset($_GET['success'])) { ?>
<div class="text-step">
	<p>Thank you, your item has been submitted. You will receive a quotation within 4 business days and you will be able to select your payout below.</p>
</div>
<?php } ?>

<ul class="tabset">
	<li><a href="#my-items"<?php if ($_GET['view'] == '') { echo ' class="active"'; } ?>>My Items</a></li>
	<li><a href="#my-info"<?php if ($_GET['view'] == 'my-info') { echo ' class="active"'; } ?>>My Info</a></li>
</ul>
<div class="indvseller-my-items">
	<div id="my-items" class="tab-content items-list">
		<?php
		$user_posts = $wpdb->get_results(sprintf("SELECT p.* FROM %sposts p LEFT JOIN %spostmeta pm ON pm.post_id = p.ID LEFT JOIN %sterm_relationships tr ON tr.object_id = p.ID WHERE p.post_type = 'post' AND p.post_author = %s AND p.post_status IN ('iseller_draft', 'iseller_noquote', 'iseller_pending', 'iseller_approved', 'iseller_pickup', 'iseller_received', 'iseller_authed', 'publish') AND pm.meta_key = 'item_seller' AND pm.meta_value = 'i' %s GROUP BY p.ID ORDER BY p.ID DESC", $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $current_user->ID, $sWhere));
		if ($user_posts) {
			foreach($user_posts as $user_post) {
				$pid = $user_post->ID;
				$pstatus = $user_post->post_status;
				$item_inventory = get_item_inventory($user_post->ID);
				$item_suggested_price = get_post_meta($user_post->ID, 'item_suggested_price', true);
				if ($pstatus == 'iseller_approved' || $pstatus == 'iseller_pickup' || $pstatus == 'iseller_received' || $pstatus == 'iseller_authed') { $pstatus = 'approved'; }
				if ($pstatus == 'publish' && $item_inventory == 0) { $pstatus = 'sold'; }
				if ($pstatus == 'publish' && $item_suggested_price == 'true') { $pstatus = 'iseller_pending'; }
				if ($pstatus == 'iseller_noquote') { $pstatus = 'iseller_pending'; }
				$seller_posts[$pstatus][] = $user_post;
			}
		}
		?>
		<div class="a-box open" style="position:relative;">
			<div class="a-title">
				<div class="right">
					<a href="#help" class="ico-question help" rel="help-submitted-items">&nbsp;</a>
					<span class="ico"></span>
				</div>
				<h3>Submitted Items</h3>
			</div>
			<div class="a-content">
				<div class="seller-products-list">
					<p class="myitems-sec-desc"><?php echo $OPTION['wps_sellers_submitted_items_short_desc']; ?></p>
					<?php if (count($seller_posts['iseller_draft']) > 0) { ?>
						<?php foreach($seller_posts['iseller_draft'] as $spost) { $spost_picture = nws_get_item_thumb($spost->ID); ?>
						<div class="product-item">
							<?php if ($spost_picture) { ?><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" class="thumbnail" alt="" /><?php } ?>
							<div class="last-column">
								<a href="#edit" class="ico-link edit" rel="<?php echo $spost->ID; ?>">Edit</a>
							</div>
							<div class="description">
								<h4><?php echo $spost->post_title; ?></h4>
								<p>ID Number: <?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
							</div>
						</div>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
			<div class="num">
				<i>1</i><span></span>
			</div>
		</div>
		<div class="a-box open" style="position:relative;">
			<div class="a-title">
				<div class="right">
					<a href="#help" class="ico-question help" rel="help-select-payout-popup">&nbsp;</a>
					<span class="ico"></span>
				</div>
				<h3>Select your Payout</h3>
			</div>
			<div class="a-content">
				<div class="seller-products-list">
					<p class="myitems-sec-desc"><?php echo $OPTION['wps_sellers_select_your_payout_short_desc']; ?></p>
					<?php if (count($seller_posts['iseller_pending']) > 0) { ?>
						<?php foreach($seller_posts['iseller_pending'] as $spost) {
						$spost_picture = nws_get_item_thumb($spost->ID);
						$spost_tlc_quotation_price_low = get_post_meta($spost->ID, 'item_tlc_quotation_price_low', true);
						$spost_tlc_quotation_price_high = get_post_meta($spost->ID, 'item_tlc_quotation_price_high', true);
						$spost_item_your_price = get_post_meta($spost->ID, 'item_your_price', true);
						$spost_your_quotation_price = get_post_meta($spost->ID, 'item_your_quotation_price', true);
						$spost_suggested_your_quotation_price = get_post_meta($spost->ID, 'item_suggested_your_quotation_price', true);
						$spost_suggested_price = get_post_meta($spost->ID, 'item_suggested_price', true);

						$spost_tlc_quotation_price_low = sellers_currency_price($spost_tlc_quotation_price_low);
						$spost_tlc_quotation_price_high = sellers_currency_price($spost_tlc_quotation_price_high);
						$spost_your_quotation_price = sellers_currency_price($spost_your_quotation_price);
						$spost_suggested_your_quotation_price = sellers_currency_price($spost_suggested_your_quotation_price);
						$no_quote = true;
						if ($spost_tlc_quotation_price_low > 0 && $spost_tlc_quotation_price_high > 0) {
							$no_quote = false;
						}
						?>
						<div class="product-item<?php if ($spost_suggested_price == 'true') { echo ' suggested-price'; } ?>">
							<?php if ($spost_picture) { ?><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" class="thumbnail" alt="" /><?php } ?>
							<div class="description fixed-width">
								<h4><?php echo $spost->post_title; ?></h4>
								<p>ID Number: <?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
								<?php if ($spost_suggested_price == 'true') { ?>
									<p class="new-payout-requested">New Payout Requested</p>
								<?php } ?>
							</div>
							<?php if ($spost_suggested_price == 'true') { ?>
								<div class="suggested-column">
									<div class="suggested"><span>Current Payout:</span> <?php echo format_price($spost_your_quotation_price, true); ?></div>
									<div class="suggested"><span>Suggested New Payout:</span> <?php echo format_price($spost_suggested_your_quotation_price, true); ?></div>
								</div>
								<div class="last-column">
									<a href="#accept" class="ico-link accept-payout" rel="<?php echo $spost->ID; ?>" style="margin-right:10px;">Accept</a>
									<a href="#decline" class="ico-link decline-payout" rel="<?php echo $spost->ID; ?>">Decline</a>
								</div>
							<?php } else { ?>
								<div class="column" style="margin-right:10px;">
									<p class="help">Your Payout</p>
									<?php if ($no_quote) { ?>
										<p><a href="#no-quotation" class="no-quotation">Unable to accept item</a></p>
									<?php } else { ?>
										<p><span class="item-your-quotation-price-<?php echo $spost->ID; ?>"><?php echo format_price($spost_tlc_quotation_price_high, true).' - '.format_price($spost_tlc_quotation_price_low, true); ?></span></p>
									<?php } ?>
								</div>
								<?php if (!$no_quote) { ?>
								<div class="column select-payout-<?php echo $spost->ID; ?>" style="width:140px; margin-right:5px;">
									<p class="help">Select Payout</p>
									<div class="select-payout">
										<input type="hidden" id="item-tlc-min-price-<?php echo $spost->ID; ?>" value="<?php echo $spost_tlc_quotation_price_low; ?>">
										<input type="hidden" id="item-tlc-max-price-<?php echo $spost->ID; ?>" value="<?php echo $spost_tlc_quotation_price_high; ?>">
										<input type="text" name="item_your_quotation_price" id="item-your-quotation-price-<?php echo $spost->ID; ?>">
									</div>
								</div>
								<?php } ?>
								<div class="last-column">
									<?php if (!$no_quote) { ?>
										<a href="#submit" class="ico-link submit-payout submit-payout-<?php echo $spost->ID; ?>" rel="<?php echo $spost->ID; ?>" style="width:50px;">Approve</a>
										<span class="ico-link submited submited-payout-<?php echo $spost->ID; ?>" style="display:none;">Approved</span>
									<?php } ?>
									<a href="#edit" class="ico-link edit" rel="<?php echo $spost->ID; ?>">Edit</a>
								</div>
							<?php } ?>
						</div>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
			<div class="num">
				<i>2</i><span></span>
			</div>
		</div>
		<div class="a-box open" style="position:relative;">
			<div class="a-title">
				<div class="right">
					<a href="#help" class="ico-question help" rel="help-awaiting-pickup">&nbsp;</a>
					<span class="ico"></span>
				</div>
				<h3>Awaiting Pickup</h3>
			</div>
			<div class="a-content">
				<div class="seller-products-list">
					<p class="myitems-sec-desc"><?php echo $OPTION['wps_sellers_awaiting_pickup_items_short_desc']; ?></p>
					<?php if (count($seller_posts['approved']) > 0) { ?>
						<?php foreach($seller_posts['approved'] as $spost) {
						$spost_picture = nws_get_item_thumb($spost->ID);
						$spost_price = get_post_meta($spost->ID, 'price', true);
						$spost_new_price = get_post_meta($spost->ID, 'new_price', true);
						$spost_your_quotation_price = get_post_meta($spost->ID, 'item_your_quotation_price', true);
						if (!$spost_new_price) { $spost_new_price = $spost_price; }

						$spost_price = sellers_currency_price($spost_price);
						$spost_new_price = sellers_currency_price($spost_new_price);
						$spost_your_quotation_price = sellers_currency_price($spost_your_quotation_price);
						?>
						<div class="product-item">
							<?php if ($spost_picture) { ?><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" class="thumbnail" alt="" /><?php } ?>
							<div class="description">
								<h4><?php echo $spost->post_title; ?></h4>
								<p>ID Number: <?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
								<div class="price-row">
									<span class="price"><strong>Your Payout:</strong> <?php echo format_price($spost_your_quotation_price, true); ?></span>
									<span class="price"><strong>Selling Price:</strong> <?php echo format_price($spost_new_price, true); ?></span>
								</div>
							</div>
						</div>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
			<div class="num">
				<i>3</i><span></span>
			</div>
		</div>
		<div class="a-box open" style="position:relative;">
			<div class="a-title">
				<div class="right">
					<a href="#help" class="ico-question help" rel="help-your-items-on-sale">&nbsp;</a>
					<span class="ico"></span>
				</div>
				<h3>Your Items on sale</h3>
			</div>
			<div class="a-content">
				<div class="seller-products-list">
					<p class="myitems-sec-desc"><?php echo $OPTION['wps_sellers_your_items_on_sale_short_desc']; ?></p>
					<?php if (count($seller_posts['publish']) > 0) { ?>
						<?php foreach($seller_posts['publish'] as $spost) {
						$spost_picture = nws_get_item_thumb($spost->ID);
						$spost_price = get_post_meta($spost->ID, 'price', true);
						$spost_new_price = get_post_meta($spost->ID, 'new_price', true);
						$spost_your_quotation_price = get_post_meta($spost->ID, 'item_your_quotation_price', true);
						if (!$spost_new_price) { $spost_new_price = $spost_price; }

						$spost_price = sellers_currency_price($spost_price);
						$spost_new_price = sellers_currency_price($spost_new_price);
						$spost_your_quotation_price = sellers_currency_price($spost_your_quotation_price);
						?>
						<div class="product-item product-item-<?php echo $spost->ID; ?>">
							<?php if ($spost_picture) { ?><a href="<?php echo get_permalink($spost->ID); ?>" class="thumbnail"><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" alt="" /></a><?php } ?>
							<div class="description">
								<h4><a href="<?php echo get_permalink($spost->ID); ?>"><?php echo $spost->post_title; ?></a></h4>
								<p>ID Number: <?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
								<?php if ($spost->inventory > 0) { ?>
								<div class="price-row">
									<div class="reduce-success" style="display:none;">Your payout has been successfully changed.</div>
									<a href="#change-price" class="btn-orange change-price-btn" rel="<?php echo $spost->ID; ?>" style="right:28px;">Reduce Your Payout</a>
									<div class="reduce-hlp"><a href="#help" class="reduce-help">&nbsp;</a></div>
									<div class="change-price" style="display:none;">
										<a href="#save-price" class="btn-orange save-price" rel="<?php echo $spost->ID; ?>" style="margin-right:28px;">Save New Payout</a>
										<span style="float:right;margin:-5px 5px 0 0;"><?php echo $_SESSION["currency-code"]; ?></span>
										<input type="text" name="item_your_price" value="<?php echo $spost_your_quotation_price; ?>" id="item-your-price-<?php echo $spost->ID; ?>" style="width:60px;">
										<img src="<?php bloginfo('template_url'); ?>/images/cancel-icon.png" class="change-price-cancel" rel="<?php echo $spost->ID; ?>">
										<img src="<?php bloginfo('template_url'); ?>/images/loading-ajax.gif" class="change-price-loading">
										<input type="hidden" id="item-your-sale-price-<?php echo $spost->ID; ?>" value="<?php echo $spost_your_quotation_price; ?>">
									</div>
									<div class="payout-now">
										<span class="price"><strong>Your Payout:</strong> <?php echo format_price($spost_your_quotation_price, true); ?></span>
										<span class="price"><strong>Selling Price:</strong> <?php echo format_price($spost_new_price, true); ?></span>
									</div>
									<div class="payout-new" style="display:none;">
										<span class="price">Your Old Payout: <font class="yopayout"><?php echo format_price($spost_your_quotation_price, true); ?></font></span>
										<span class="price">Old Selling Price: <font class="osprice"><?php echo format_price($spost_new_price, true); ?></font></span>
										<div class="clear"></div>
										<span class="price"><strong>Your New Payout:</strong> <font class="ynpayout"><?php echo format_price($spost_your_quotation_price, true); ?></font></span>
										<span class="price"><strong>New Selling Price:</strong> <font class="nsprice"><?php echo format_price($spost_new_price, true); ?></font></span>
									</div>
								</div>
								<?php } ?>
							</div>
						</div>
						<?php } ?>
					<?php } else { ?>
						<p>You currently have 0 items on sale.</p>
					<?php } ?>
				</div>
			</div>
			<div class="num">
				<i>4</i><span></span>
			</div>
		</div>
		<div class="a-box open" style="position:relative;">
			<div class="a-title">
				<div class="right">
					<a href="#help" class="ico-question help" rel="help-sold-items">&nbsp;</a>
					<span class="ico"></span>
				</div>
				<h3>Sold Items</h3>
			</div>
			<div class="a-content">
				<div class="seller-products-list">
					<p class="myitems-sec-desc"><?php echo $OPTION['wps_sellers_sold_items_short_desc']; ?></p>
					<?php if (count($seller_posts['sold']) > 0) { ?>
						<?php foreach($seller_posts['sold'] as $spost) {
						$spost_picture = nws_get_item_thumb($spost->ID);
						$spost_new_price = get_post_meta($spost->ID, 'new_price', true);
						$spost_your_quotation_price = get_post_meta($spost->ID, 'item_your_quotation_price', true);

						$spost_new_price = sellers_currency_price($spost_new_price);
						$spost_your_quotation_price = sellers_currency_price($spost_your_quotation_price);
						?>
						<div class="product-item">
							<?php if ($spost_picture) { ?><a href="<?php echo get_permalink($spost->ID); ?>" class="thumbnail"><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" alt="" /></a><?php } ?>
							<div class="description">
								<h4><a href="<?php echo get_permalink($spost->ID); ?>"><?php echo $spost->post_title; ?></a></h4>
								<p>ID Number: <?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
								<div class="price-row">
									<span class="price"><strong>Your Payout:</strong> <?php echo format_price($spost_your_quotation_price, true); ?></span>
									<span class="price"><strong>Selling Price:</strong> <?php echo format_price($spost_new_price, true); ?></span>
								</div>
							</div>
						</div>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
			<div class="num">
				<i>5</i>
			</div>
		</div>
		<form id="indivseller-action-form" method="POST">
			<input type="hidden" name="editurl" value="<?php echo get_permalink($OPTION['wps_indvseller_edit_item_page']); ?>" id="indivseller-editurl">
			<input type="hidden" name="SellersAction" id="indivseller-action">
			<input type="hidden" name="post_id" id="indivseller-post-id">
		</form>
	</div>
	<div id="my-info" class="tab-content my-info" style="margin-left:-28px;">
		<?php
		$seller_first_name = get_user_meta($current_user->ID, 'first_name', true);
		$seller_last_name = get_user_meta($current_user->ID, 'last_name', true);
		$seller_address = get_user_meta($current_user->ID, 'seller_address', true);
		$seller_phone = get_user_meta($current_user->ID, 'phone', true);
		$seller_bank_type = get_user_meta($current_user->ID, 'seller_bank_type', true);
		$seller_bank_details = get_user_meta($current_user->ID, 'seller_bank_details', true);
		$seller_email = $current_user->data->user_email;
		if (!strlen($seller_bank_type)) { $seller_bank_type = 'Bank transfer'; } ?>
		<div class="sellers-other-tabs">
			<form id="indivseller-my-info" method="POST" class="form-add">
				<div class="row">
					<strong class="title">Your details</strong>
				</div>
				<div class="row">
					<div class="column" id="seller-first-name">
						<label>First Name *</label>
						<input type="text" name="seller_first_name" value="<?php echo $seller_first_name; ?>">
					</div>
					<div class="column" id="seller-last-name">
						<label>Last Name *</label>
						<input type="text" name="seller_last_name" value="<?php echo $seller_last_name; ?>">
					</div>
				</div>
				<div class="row" id="seller-address">
					<label>Address</label>
					<textarea name="seller_address"><?php echo $seller_address; ?></textarea>
				</div>
				<div class="row">
					<div class="column" id="seller-email">
						<label>E-mail *</label>
						<input type="text" name="seller_email" value="<?php echo $seller_email; ?>">
					</div>
					<div class="column" id="seller-phone">
						<label>Telephone *</label>
						<input type="text" name="seller_phone" value="<?php echo $seller_phone; ?>">
					</div>
				</div>
				<div class="row">
					<strong class="title">Preferred payment method</strong>
				</div>
				<div class="row" id="seller-bank-type">
					<label class="check-row">
						<input type="radio" name="seller_bank_type" value="Bank transfer"<?php if ($seller_bank_type == 'Bank transfer') { echo ' CHECKED'; } ?>>
						<span class="label">Bank transfer</span> 
					</label>
					<label class="check-row">
						<input type="radio" name="seller_bank_type" value="Paypal"<?php if ($seller_bank_type == 'Paypal') { echo ' CHECKED'; } ?>>
						<span class="label">Paypal</span> 
					</label>
				</div>
				<div class="row" id="seller-bank-details">
					<label>Payment Details</label>
					<textarea name="seller_bank_details"><?php echo $seller_bank_details; ?></textarea>
				</div>
				<div class="row">
					<input value="Submit" type="submit" class="btn-orange">
					<div class="seller-submitting">Updating...</div>
					<div class="seller-message">Your Info was successfully updated.</div>
				</div>
			</form>
		</div>
	</div>
</div>

<?php endwhile; endif; ?>

<?php
} else {
	echo '<p>You are not allowed to view this page.</p>';
}

get_footer(); ?>