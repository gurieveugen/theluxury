<?php
/*
Template Name: Indvidual Seller Summary Page
*/

global $OPTION, $current_user;

get_header();

if (is_user_logged_in() && (in_array('staff', $current_user->roles) || in_array('buyer', $current_user->roles) || in_array('administrator', $current_user->roles))) {

$seller_id = (int)$_GET['seller'];
$seller_data = get_userdata($seller_id);
if ($seller_data) {

$seller_posts = array();
$seller_categories = get_terms('seller-category', 'hide_empty=0');

$scat = trim($_GET['scat']);
if (strlen($scat)) {
	$scat_tt_id = $wpdb->get_var(sprintf("SELECT term_taxonomy_id FROM %sterm_taxonomy WHERE term_id = %s", $wpdb->prefix, $scat));
	if ($scat_tt_id) {
		$scat_tt_ids = array($scat_tt_id);
		$subcat_tt_ids = $wpdb->get_results(sprintf("SELECT term_taxonomy_id FROM %sterm_taxonomy WHERE taxonomy = 'seller-category' AND parent = %s", $wpdb->prefix, $scat));
		if ($subcat_tt_ids) {
			foreach($subcat_tt_ids as $subcat_tt_id) {
				$scat_tt_ids[] = $subcat_tt_id->term_taxonomy_id;
			}
		}
		$sWhere .= " AND tr.term_taxonomy_id IN (".implode(',', $scat_tt_ids).")";
	}
}
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<h1 class="main-title" style="float:left; margin-right:15px;"><?php echo $seller_data->user_login; ?></h1>
<a href="mailto:<?php echo $seller_data->user_email; ?>" class="link-pink" style="float:left; font-size:12px;">Send Email</a>
<div class="clear"></div>
<ul class="tabset">
	<li><a href="#items-tab" class="active">Items</a></li>
	<li><a href="#info-tab">Info</a></li>
</ul>
<div class="sellers-summary-page">
	<div id="items-tab" class="tab-content">
		<div class="category-bar">
			<ul class="category-items inner">
				<li<?php if ($scat == '') { echo ' class="active"'; } ?>><a href="<?php the_permalink(); ?>?seller=<?php echo $seller_id; ?>">All Categories</a></li>
				<?php if ($seller_categories) { ?>
					<?php foreach($seller_categories as $seller_category) { if ($seller_category->parent == 0) { ?>
					<li<?php if ($scat == $seller_category->term_id) { echo ' class="active"'; } ?>><a href="<?php the_permalink(); ?>?seller=<?php echo $seller_id; ?>&scat=<?php echo $seller_category->term_id; ?>"><?php echo $seller_category->name; ?></a></li>
					<?php }} ?>
				<?php } ?>
			</ul>
		</div>
		<?php
		$user_posts = $wpdb->get_results(sprintf("SELECT p.* FROM %sposts p LEFT JOIN %spostmeta pm ON pm.post_id = p.ID LEFT JOIN %sterm_relationships tr ON tr.object_id = p.ID WHERE p.post_type = 'post' AND p.post_author = %s AND p.post_status IN ('iseller_draft', 'pending', 'iseller_pending', 'iseller_approved', 'iseller_pickup', 'iseller_received', 'publish') AND pm.meta_key = 'item_seller' AND pm.meta_value = 'i' %s GROUP BY p.ID ORDER BY p.ID DESC", $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $seller_id, $sWhere));
		if ($user_posts) {
			foreach($user_posts as $user_post) {
				$pid = $user_post->ID;
				$pstatus = $user_post->post_status;
				$item_inventory = get_item_inventory($user_post->ID);
				if ($pstatus == 'iseller_approved' || $pstatus == 'iseller_pickup' || $pstatus == 'iseller_received') { $pstatus = 'approved'; }
				if ($pstatus == 'pending') { $pstatus = 'iseller_pending'; }
				$seller_posts[$pstatus][] = $user_post;
			}
		}
		?>
		<div class="a-box open">
			<div class="a-title">
				<div class="right">
					<span class="ico"></span>
				</div>
				<h3>Items Pending Quotation</h3>
			</div>
			<div class="a-content">
				<div class="seller-products-list">
					<?php if (count($seller_posts['iseller_draft']) > 0) { ?>
						<?php foreach($seller_posts['iseller_draft'] as $spost) {
						$spost_picture = nws_get_item_thumb($spost->ID);
						$spost_your_price = get_post_meta($spost->ID, 'item_your_price', true);

						$spost_your_price = sellers_currency_price($spost_your_price);
						?>
						<div class="product-item">
							<?php if ($spost_picture) { ?><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" class="thumbnail" alt="" /><?php } ?>
							<div class="description">
								<h4><?php echo $spost->post_title; ?></h4>
								<p><?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
								<div class="price-row">
									<span class="price"><strong>Seller Price:</strong> <?php echo format_price($spost_your_price, true); ?></span>
								</div>
							</div>
						</div>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="a-box open">
			<div class="a-title">
				<div class="right">
					<span class="ico"></span>
				</div>
				<h3>Items Pending Approval</h3>
			</div>
			<div class="a-content">
				<div class="seller-products-list">
					<?php if (count($seller_posts['iseller_pending']) > 0) { ?>
						<?php foreach($seller_posts['iseller_pending'] as $spost) {
						$spost_picture = nws_get_item_thumb($spost->ID);
						$spost_your_price = get_post_meta($spost->ID, 'item_your_price', true);
						$spost_tlc_quotation_price_low = get_post_meta($spost->ID, 'item_tlc_quotation_price_low', true);
						$spost_tlc_quotation_price_high = get_post_meta($spost->ID, 'item_tlc_quotation_price_high', true);

						$spost_your_price = sellers_currency_price($spost_your_price);
						$spost_tlc_quotation_price_low = sellers_currency_price($spost_tlc_quotation_price_low);
						$spost_tlc_quotation_price_high = sellers_currency_price($spost_tlc_quotation_price_high);
						$quotation_value = 'Unable to accept item';
						if ($spost_tlc_quotation_price_low > 0 && $spost_tlc_quotation_price_high > 0) {
							$quotation_value = format_price($spost_tlc_quotation_price_high, true).' - '.format_price($spost_tlc_quotation_price_low, true);
						}
						?>
						<div class="product-item">
							<?php if ($spost_picture) { ?><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" class="thumbnail" alt="" /><?php } ?>
							<div class="description fixed-width">
								<h4><?php echo $spost->post_title; ?></h4>
								<p><?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
								<div class="price-row">
									<span class="price"><strong>Seller Price:</strong> <?php echo format_price($spost_your_price, true); ?></span>
									<div class="clear"></div>
									<span class="price"><strong>Quotation:</strong> <?php echo $quotation_value; ?></span>
								</div>
							</div>
						</div>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="a-box open">
			<div class="a-title">
				<div class="right">
					<span class="ico"></span>
				</div>
				<h3>Approved Items</h3>
			</div>
			<div class="a-content">
				<div class="seller-products-list<?php echo seller_products_list_classes($cid, $cdata); ?>">
					<?php if (count($seller_posts['approved']) > 0) { ?>
						<?php foreach($seller_posts['approved'] as $spost) {
						$spost_picture = nws_get_item_thumb($spost->ID);
						$spost_item_your_quotation_price = get_post_meta($spost->ID, 'item_your_quotation_price', true);
						$spost_price = get_post_meta($spost->ID, 'price', true);
						$spost_new_price = get_post_meta($spost->ID, 'new_price', true);
						if (!$spost_new_price) { $spost_new_price = $spost_price; }

						$spost_new_price = sellers_currency_price($spost_new_price);
						$spost_item_your_quotation_price = sellers_currency_price($spost_item_your_quotation_price);
						?>
						<div class="product-item">
							<?php if ($spost_picture) { ?><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" class="thumbnail" alt="" /><?php } ?>
							<div class="description fixed-width">
								<h4><?php echo $spost->post_title; ?></h4>
								<p><?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
								<div class="price-row">
									<span class="price"><strong>Seller Payout:</strong> <?php echo format_price($spost_item_your_quotation_price, true); ?></span>
									<span class="price"><strong>The Luxury Closet Selling Price:</strong> <?php echo format_price($spost_new_price, true); ?></span>
								</div>
							</div>
						</div>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
		$sold_array = array();
		$sold_items = $wpdb->get_results(sprintf("SELECT p.* FROM %sposts p LEFT JOIN %spostmeta pm ON pm.post_id = p.ID LEFT JOIN %sterm_relationships tr ON tr.object_id = p.ID LEFT JOIN %swps_shopping_cart sc ON sc.postID = p.ID LEFT JOIN %swps_orders o ON o.who = sc.who WHERE p.post_type = 'post' AND p.post_author = %s AND p.post_status = 'publish' AND pm.meta_key = 'item_seller' AND pm.meta_value = 'i' AND sc.item_amount > 0 AND o.level IN ('6', '7') %s GROUP BY p.ID ORDER BY p.ID DESC", $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $seller_id, $sWhere));
		if ($sold_items) {
			foreach($sold_items as $sold_item) {
				$sold_array[] = $sold_item->ID;
			}
		}
		?>
		<div class="a-box open">
			<div class="a-title">
				<div class="right">
					<span class="ico"></span>
				</div>
				<h3>Items on Sale</h3>
			</div>
			<div class="a-content">
				<div class="seller-products-list">
					<?php if (count($seller_posts['publish']) > 0) { ?>
						<?php foreach($seller_posts['publish'] as $spost) {
						if (in_array($spost->ID, $sold_array)) { continue; }
						$spost_picture = nws_get_item_thumb($spost->ID);
						$spost_price = get_post_meta($spost->ID, 'price', true);
						$spost_new_price = get_post_meta($spost->ID, 'new_price', true);
						$spost_your_quotation_price = get_post_meta($spost->ID, 'item_your_quotation_price', true);
						$spost_suggested_your_quotation_price = get_post_meta($spost->ID, 'item_suggested_your_quotation_price', true);
						$spost_suggested_price = get_post_meta($spost->ID, 'item_suggested_price', true);
						$spost_suggested_price_date = get_post_meta($spost->ID, 'item_suggested_price_date', true);

						if (!$spost_new_price) { $spost_new_price = $spost_price; }

						$spost_new_price = sellers_currency_price($spost_new_price);
						$spost_your_quotation_price = sellers_currency_price($spost_your_quotation_price);
						$spost_suggested_your_quotation_price = sellers_currency_price($spost_suggested_your_quotation_price);
						?>
						<div class="product-item">
							<?php if ($spost_picture) { ?><a href="<?php echo get_permalink($spost->ID); ?>" class="thumbnail"><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" alt="" /></a><?php } ?>
							<div class="description">
								<h4><a href="<?php echo get_permalink($spost->ID); ?>"><?php echo $spost->post_title; ?></a></h4>
								<p><?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
								<div class="price-row">
									<a href="#delete" class="ico-link delete delete-inventory" rel="<?php echo $spost->ID; ?>">Delete</a>
									<div style="float:right; width:205px;">
										<a href="#change-price" class="link-pink change-price-btn" rel="<?php echo $spost->ID; ?>" style="float:left;">Change seller payout</a>
										<div class="change-price" style="display:none;">
											<a href="#save-price" class="link-pink save-price" rel="<?php echo $spost->ID; ?>">Save Payout</a>
											<input type="text" name="item_your_price" value="<?php echo $spost_your_quotation_price; ?>" id="item-your-price-<?php echo $spost->ID; ?>" style="width:80px;">
										</div>
										<?php if (strlen($spost_suggested_price_date)) { ?><br><div style="position:absolute; width:150px; margin:7px 0 0 20px;"><small>Requested: <?php echo date("d.m.Y", strtotime($spost_suggested_price_date) + $OPTION['wps_time_addition']); ?></small></div><?php } ?>
									</div>
									<span class="price"><strong>Seller Payout:</strong> <?php echo format_price($spost_your_quotation_price, true); ?></span>
									<span class="price"><strong>The Luxury Closet Selling Price:</strong> <?php echo format_price($spost_new_price, true); ?></span>
									<?php if ($spost_suggested_price == 'true') { ?>
										<br /><span class="price"><strong>Suggested New Payout:</strong> <?php echo format_price($spost_suggested_your_quotation_price, true); ?></span>
									<?php } ?>
								</div>
							</div>
						</div>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="a-box open">
			<div class="a-title">
				<div class="right">
					<span class="ico"></span>
				</div>
				<h3>Sold Items</h3>
			</div>
			<div class="a-content">
				<div class="seller-products-list">
					<?php if (count($sold_items) > 0) { ?>
						<?php foreach($sold_items as $spost) {
						$spost_picture = nws_get_item_thumb($spost->ID);
						$spost_price = get_post_meta($spost->ID, 'price', true);
						$spost_new_price = get_post_meta($spost->ID, 'new_price', true);
						$spost_your_quotation_price = get_post_meta($spost->ID, 'item_your_quotation_price', true);
						if (!$spost_new_price) { $spost_new_price = $spost_price; }

						$spost_new_price = sellers_currency_price($spost_new_price);
						$spost_your_quotation_price = sellers_currency_price($spost_your_quotation_price);
						?>
						<div class="product-item">
							<?php if ($spost_picture) { ?><a href="<?php echo get_permalink($spost->ID); ?>" class="thumbnail"><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" alt="" /></a><?php } ?>
							<div class="description">
								<h4><a href="<?php echo get_permalink($spost->ID); ?>"><?php echo $spost->post_title; ?></a></h4>
								<p><?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
								<div class="price-row">
									<a href="#delete" class="ico-link delete" rel="<?php echo $spost->ID; ?>">Delete</a>
									<span class="price"><strong>Seller Payout:</strong> <?php echo format_price($spost_your_quotation_price, true); ?></span>
									<span class="price"><strong>The Luxury Closet Selling Price:</strong> <?php echo format_price($spost_new_price, true); ?></span>
								</div>
							</div>
						</div>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>
		<form id="summary-action-form" method="POST">
			<input type="hidden" name="editurl" value="<?php echo get_permalink($OPTION['wps_indvseller_edit_item_page']); ?>" id="summary-editurl">
			<input type="hidden" name="SellersAction" id="summary-action">
			<input type="hidden" name="price" id="summary-price">
			<input type="hidden" name="post_id" id="summary-post-id">
			<input type="hidden" name="return" value="<?php the_permalink(); ?>?seller=<?php echo $_GET['seller']; ?>">
		</form>
	</div>
	<div id="info-tab" class="tab-content">
		<div class="sellers-other-tabs">
			<?php
			$seller_phone = get_user_meta($seller_data->ID, 'phone', true);
			$seller_address = get_user_meta($seller_data->ID, 'seller_address', true);
			$seller_bank_type = get_user_meta($seller_data->ID, 'seller_bank_type', true);
			$seller_bank_details = get_user_meta($seller_data->ID, 'seller_bank_details', true);
			?>
			<table class="sellers-summary-info">
				<tr>
					<td class="si-name"><strong>Name:</strong> <span><?php echo $seller_data->first_name; ?> <?php echo $seller_data->last_name; ?></span></td>
				</tr>
				<tr>
					<td class="si-email"><strong>Email:</strong> <span><a href="mailto:<?php echo $seller_data->user_email; ?>"><?php echo $seller_data->user_email; ?></a></span></td>
				</tr>
				<tr>
					<td class="si-phone"><strong>Telephone:</strong> <span><?php echo $seller_phone; ?></span></td>
				</tr>
				<tr>
					<td class="si-address"><strong>Address:</strong><br /><span><?php echo wpautop($seller_address); ?></span></td>
				</tr>
				<tr>
					<td class="si-payment"><strong>Preferred payment method:</strong> <span><?php echo $seller_bank_type; ?><br /><?php echo wpautop($seller_bank_details); ?></span></td>
				</tr>
				<tr>
					<td><a href="#edit" class="sellers-summary-info-edit">Edit</a></td>
				</tr>
			</table>
			<div id="sellers-summary-info-box" style="display:none;">
				<form id="sellers-summary-info-form" method="POST" class="form-add">
				<input type="hidden" name="seller_id" value="<?php echo $seller_id; ?>" id="seller-id">
				<div class="row">
					<strong>Edit seller details</strong>
				</div>
				<div class="row">
					<div class="column" id="seller-first-name">
						<label>First Name</label>
						<input type="text" name="seller_first_name" value="<?php echo $seller_data->first_name; ?>">
					</div>
					<div class="column" id="seller-last-name">
						<label>Last Name</label>
						<input type="text" name="seller_last_name" value="<?php echo $seller_data->last_name; ?>">
					</div>
				</div>
				<div class="row">
					<div class="column" id="seller-email">
						<label>E-mail</label>
						<input type="text" name="seller_email" value="<?php echo $seller_data->user_email; ?>">
					</div>
					<div class="column" id="seller-phone">
						<label>Telephone</label>
						<input type="text" name="seller_phone" value="<?php echo $seller_phone; ?>">
					</div>
				</div>
				<div class="row" id="seller-address">
					<label>Address</label>
					<textarea name="seller_address"><?php echo $seller_address; ?></textarea>
				</div>
				<div class="row">
					<strong>Preferred payment method</strong>
				</div>
				<div class="row" id="seller-bank-type">
					<input type="radio" name="seller_bank_type" value="Cheque"<?php if ($seller_bank_type == 'Cheque') { echo ' CHECKED'; } ?>> Cheque<br />
					<input type="radio" name="seller_bank_type" value="Bank transfer"<?php if ($seller_bank_type == 'Bank transfer') { echo ' CHECKED'; } ?>> Bank transfer<br />
					<input type="radio" name="seller_bank_type" value="Paypal"<?php if ($seller_bank_type == 'Paypal') { echo ' CHECKED'; } ?>> Paypal
				</div>
				<div class="row" id="seller-bank-details">
					<label>Payment Details</label>
					<textarea name="seller_bank_details"><?php echo $seller_bank_details; ?></textarea>
				</div>
				<div class="row">
					<input type="submit" value="Save">
					<div class="seller-submitting">Updating...</div>
					<div class="seller-message">Seller Info was successfully updated.</div>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php endwhile; endif; ?>

<?php
	} else {
		echo '<p>Seller data is incorrect.</p>';
	} // if ($seller_data) {
} else {
	echo '<p>You are not allowed to view this page.</p>';
}

get_footer(); ?>