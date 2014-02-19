<?php
/*
Template Name: Profreseller My Items Page
*/
global $OPTION, $current_user;

get_header();

if (is_user_logged_in() && (in_array('profseller', $current_user->roles) || in_array('administrator', $current_user->roles))) {

$sellers_categories = sellers_get_categories();
$seller_posts = array();
$seller_posts_id = array();
$skey = trim($_GET['skey']);
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<h1 class="main-title"><?php the_title(); ?></h1>
<ul class="tabset">
	<li><a href="#my-items"<?php if ($_GET['view'] == '') { echo ' class="active"'; } ?>>My Items</a></li>
	<li class="my-orders"><a href="#my-orders"<?php if ($_GET['view'] == 'my-orders') { echo ' class="active"'; } ?>>My Orders</a></li>
	<li><a href="#my-info"<?php if ($_GET['view'] == 'my-info') { echo ' class="active"'; } ?>>My Info</a></li>
</ul>
<div class="profseller-my-items">
	<div id="my-items" class="tab-content">
		<div class="category-bar">
			<a href="<?php echo get_permalink($OPTION['wps_profreseller_add_item_page']); ?>" class="btn-add-item">Add Item</a>
			<div class="search-bar">
				<form name="search_bar_form" class="search-bar-form">
				<input type="text" name="skey" value="<?php echo $skey; ?>">
				<input type="submit" value="Search">
				</form>
			</div>
		</div>

		<?php
		$user_posts = $wpdb->get_results(sprintf("SELECT p.* FROM %sposts p LEFT JOIN %spostmeta pm ON pm.post_id = p.ID WHERE post_type = 'post' AND post_author = %s AND post_status IN ('pseller_draft', 'pseller_pending', 'pseller_approved', 'pseller_pickup', 'pseller_received', 'seller_deleted', 'publish') AND pm.meta_key = 'item_seller' AND pm.meta_value = 'p' ORDER BY ID DESC", $wpdb->prefix, $wpdb->prefix, $current_user->ID));
		if ($user_posts) {
			foreach($user_posts as $user_post) {
				$pid = $user_post->ID;
				$pstatus = $user_post->post_status;
				$item_inventory = get_item_inventory($pid);
				$item_deleted = get_post_meta($pid, '_prof_item_deleted', true);
				if ($pstatus == 'pseller_approved' || $pstatus == 'pseller_pickup' || $pstatus == 'pseller_received') { $pstatus = 'approved'; }
				if ($pstatus == 'publish' && $item_inventory == 0) { $pstatus = 'sold'; }
				if ($item_deleted == 'true') { $pstatus = 'seller_deleted'; }
				$seller_posts_id[] = $pid;
				$seller_posts[$pstatus][] = $user_post;
			}
		}
		?>

		<div class="a-box open">
			<div class="a-title">
				<div class="right">
					<span class="ico"></span>
				</div>
				<h3>Draft Items</h3>
			</div>
			<div class="a-content">
				<div class="seller-products-list">
					<?php if (count($seller_posts['pseller_draft']) > 0) { ?>
						<?php foreach($seller_posts['pseller_draft'] as $spost) { $spost_picture = nws_get_item_thumb($spost->ID); ?>
						<div class="product-item">
							<?php if ($spost_picture) { ?><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" class="thumbnail" alt="" /><?php } ?>
							<div class="last-column">
								<a href="#submit" class="ico-link submit" rel="<?php echo $spost->ID; ?>">Submit</a>
								<a href="#edit" class="ico-link edit" rel="<?php echo $spost->ID; ?>">Edit</a>
								<a href="#delete" class="ico-link delete" rel="<?php echo $spost->ID; ?>">Delete</a>
							</div>
							<div class="description">
								<h4><?php echo $spost->post_title; ?></h4>
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
					<?php if (count($seller_posts['pseller_pending']) > 0) { ?>
						<?php foreach($seller_posts['pseller_pending'] as $spost) { $spost_picture = nws_get_item_thumb($spost->ID); ?>
						<div class="product-item">
							<?php if ($spost_picture) { ?><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" class="thumbnail" alt="" /><?php } ?>
							<div class="last-column">
								<a href="#edit" class="ico-link edit" rel="<?php echo $spost->ID; ?>">Edit</a>
								<a href="#delete" class="ico-link delete" rel="<?php echo $spost->ID; ?>">Delete</a>
							</div>
							<div class="description">
								<h4><?php echo $spost->post_title; ?></h4>
								<p><?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
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
				<h3>Approved Items <span>(Your item will go on sale within the next 7 business days)</span></h3>
			</div>
			<div class="a-content">
				<div class="seller-products-list">
					<?php if (count($seller_posts['approved']) > 0) { ?>
						<?php foreach($seller_posts['approved'] as $spost) {
						$spost_picture = nws_get_item_thumb($spost->ID);
						$spost_your_price = get_post_meta($spost->ID, 'item_your_price', true);
						$spost_price = get_post_meta($spost->ID, 'price', true);
						$spost_new_price = get_post_meta($spost->ID, 'new_price', true);
						if (!$spost_new_price) { $spost_new_price = $spost_price; }

						$spost_your_price = sellers_currency_price($spost_your_price);
						$spost_price = sellers_currency_price($spost_price);
						$spost_new_price = sellers_currency_price($spost_new_price);
						?>
						<div class="product-item">
							<?php if ($spost_picture) { ?><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" class="thumbnail" alt="" /><?php } ?>
							<div class="last-column">
								<a href="#edit" class="ico-link edit" rel="<?php echo $spost->ID; ?>">Edit</a>
								<a href="#delete" class="ico-link delete" rel="<?php echo $spost->ID; ?>">Delete</a>
							</div>
							<div class="description">
								<h4><?php echo $spost->post_title; ?></h4>
								<p><?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
								<div class="price-row">
									<span class="price"><strong>Your Payout:</strong> <span class="item-your-price-<?php echo $spost->ID; ?>"><?php echo format_price($spost_your_price, true); ?></span></span>
									<span class="price"><strong>The Luxury Closet Selling Price:</strong> <span class="item-selling-price-<?php echo $spost->ID; ?>"><?php echo format_price($spost_new_price, true); ?></span></span>
									<span class="price"><strong>Original Retail Price:</strong> <span class="item-selling-price-<?php echo $spost->ID; ?>"><?php echo format_price($spost_price, true); ?></span></span>
								</div>
							</div>
						</div>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="a-box open" id="items-on-sale">
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
						$spost_picture = nws_get_item_thumb($spost->ID);
						$spost_your_price = get_post_meta($spost->ID, 'item_your_price', true);
						$spost_price = get_post_meta($spost->ID, 'price', true);
						$spost_new_price = get_post_meta($spost->ID, 'new_price', true);
						if (!$spost_new_price) { $spost_new_price = $spost_price; }

						$spost_your_price = sellers_currency_price($spost_your_price);
						$spost_price = sellers_currency_price($spost_price);
						$spost_new_price = sellers_currency_price($spost_new_price);
						if (!strlen($skey) || (strlen($skey) && stripos($spost->post_title, $skey) !== false)) {
						?>
						<div class="product-item">
							<?php if ($spost_picture) { ?><a href="<?php echo get_permalink($spost->ID); ?>" class="thumbnail"><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" alt="" /></a><?php } ?>
							<div class="description">
								<h4><a href="<?php echo get_permalink($spost->ID); ?>"><?php echo $spost->post_title; ?></a></h4>
								<p><?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
								<div class="price-row">
									<a href="#delete" class="ico-link delete delete-inventory" rel="<?php echo $spost->ID; ?>">Delete</a>
									<a href="#change-price" class="link-pink change-price-btn" rel="<?php echo $spost->ID; ?>">Change Payout</a>
									<div class="change-price-request">Price was successfully saved.</div>
									<div class="change-price" style="display:none;"><a href="#save-price" class="link-pink save-price" rel="<?php echo $spost->ID; ?>">Save Payout</a><input type="text" name="item_your_price" value="<?php echo $spost_your_price; ?>" id="item-your-price-<?php echo $spost->ID; ?>" rel="<?php echo $spost_your_price; ?>" style="width:80px;"></div>
									<span class="price"><strong>Your Payout:</strong> <span class="item-your-price-<?php echo $spost->ID; ?>"><?php echo format_price($spost_your_price, true); ?></span></span>
									<span class="price"><strong>The Luxury Closet Selling Price:</strong> <span class="item-selling-price-<?php echo $spost->ID; ?>"><?php echo format_price($spost_new_price, true); ?></span></span>
								</div>
							</div>
						</div>
						<?php }} ?>
					<?php } else { ?>
						<p>You currently have 0 items on sale.</p>
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
					<?php
					$sold_user_posts = $wpdb->get_results(sprintf("SELECT p.* FROM %sposts p LEFT JOIN %spostmeta pm ON pm.post_id = p.ID LEFT JOIN %swps_shopping_cart sc ON sc.postID = p.ID LEFT JOIN %swps_orders o ON o.who = sc.who WHERE p.post_type = 'post' AND post_author = %s AND p.post_status = 'publish' AND p.inventory = 0 AND pm.meta_key = 'item_seller' AND pm.meta_value = 'p' AND o.level = '4' GROUP BY p.ID ORDER BY p.ID DESC", $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $current_user->ID));
					if (count($sold_user_posts) > 0) {
						foreach($sold_user_posts as $spost) {
						$spost_picture = nws_get_item_thumb($spost->ID);
						$spost_your_price = get_post_meta($spost->ID, 'item_your_price', true);
						$spost_new_price = get_post_meta($spost->ID, 'new_price', true);

						$spost_your_price = sellers_currency_price($spost_your_price);
						$spost_new_price = sellers_currency_price($spost_new_price);
						?>
						<div class="product-item">
							<?php if ($spost_picture) { ?><a href="<?php echo get_permalink($spost->ID); ?>" class="thumbnail"><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" alt="" /></a><?php } ?>
							<div class="description">
								<h4><a href="<?php echo get_permalink($spost->ID); ?>"><?php echo $spost->post_title; ?></a></h4>
								<p><?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
								<div class="price-row">
									<span class="price"><strong>Your Payout:</strong> <span class="item-your-price-<?php echo $spost->ID; ?>"><?php echo format_price($spost_your_price, true); ?></span></span>
									<span class="price"><strong>The Luxury Closet Selling Price:</strong> <span class="item-selling-price-<?php echo $spost->ID; ?>"><?php echo format_price($spost_new_price, true); ?></span></span>
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
				<h3>Deleted Items</h3>
			</div>
			<div class="a-content">
				<div class="seller-products-list<?php echo seller_products_list_classes($cid, $cdata); ?>">
					<?php if (count($seller_posts['seller_deleted']) > 0) { ?>
						<?php foreach($seller_posts['seller_deleted'] as $spost) { $spost_picture = nws_get_item_thumb($spost->ID); ?>
						<div class="product-item">
							<?php if ($spost_picture) { ?><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" class="thumbnail" alt="" /><?php } ?>
							<div class="last-column">
								<a href="#edit" class="ico-link edit" rel="<?php echo $spost->ID; ?>">Edit</a>
							</div>
							<div class="description">
								<h4><?php echo $spost->post_title; ?></h4>
								<p><?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
							</div>
						</div>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>

		<form id="profreseller-action-form" method="POST">
			<input type="hidden" name="editurl" value="<?php echo get_permalink($OPTION['wps_profreseller_edit_item_page']); ?>" id="profreseller-editurl">
			<input type="hidden" name="SellersAction" id="profreseller-action">
			<input type="hidden" name="post_id" id="profreseller-post-id">
		</form>
	</div>
	<div id="my-orders" class="tab-content">
		<div class="sellers-other-tabs">
			<h3>My Orders</h3>
			<table border="1" class="sellers-orders">
				<tr>
					<td class="ttl" width="70">Order ID</td>
					<td class="ttl" width="120">Date</td>
					<td class="ttl">Item ID</td>
					<td class="ttl">Item Name</td>
					<td class="ttl" width="20">Qty</td>
					<td class="ttl">Your Price</td>
					<td class="ttl">Selling Price</td>
					<td class="ttl">Total</td>
				</tr>
				<?php
				$pseller_orders = array();
				$seller_orders = $wpdb->get_results(sprintf("SELECT * FROM %swps_orders o LEFT JOIN %swps_shopping_cart sc ON sc.who = o.who WHERE o.level IN ('4','5','6','7','8') AND sc.postID IN (%s) ORDER BY o.oid DESC", $wpdb->prefix, $wpdb->prefix, implode(',', $seller_posts_id)));
				if ($seller_orders) {
					foreach($seller_orders as $seller_order) {
						$item_price = $seller_order->item_price;
						$seller_price = $seller_order->seller_price;
						if ($seller_order->viewed == 0) {
							$pseller_orders[] = $seller_order->oid;
						}
						$item_price = sellers_currency_price($item_price);
						$seller_price = sellers_currency_price($seller_price);
					?>
					<tr>
						<td><?php echo $OPTION['wps_order_no_prefix'].$seller_order->oid; ?></td>
						<td><?php echo date("d.m.Y H:i:s", $seller_order->order_time); ?></td>
						<td><?php echo $seller_order->item_id; ?></td>
						<td><?php echo $seller_order->item_name; ?></td>
						<td style="text-align:center;"><?php echo $seller_order->item_amount; ?></td>
						<td><?php echo format_price($seller_price, true); ?></td>
						<td><?php echo format_price($item_price, true); ?></td>
						<td><?php echo format_price($item_price * $seller_order->item_amount, true); ?></td>
					</tr>
					<?php } ?>
				<?php } else { ?>
				<tr>
					<td colspan="8">No orders.</td>
				</tr>
				<?php } ?>
			</table>
			<div id="pseller-orders" style="display:none;"><?php echo implode(',', $pseller_orders); ?></div>
		</div>
	</div>
	<div id="my-info" class="tab-content">
		<div class="sellers-other-tabs">
			<?php
			$seller_first_name = get_user_meta($current_user->ID, 'first_name', true);
			$seller_last_name = get_user_meta($current_user->ID, 'last_name', true);
			$seller_address = get_user_meta($current_user->ID, 'seller_address', true);
			$seller_phone = get_user_meta($current_user->ID, 'phone', true);
			$seller_bank_type = get_user_meta($current_user->ID, 'seller_bank_type', true);
			$seller_bank_details = get_user_meta($current_user->ID, 'seller_bank_details', true);
			$seller_email = $current_user->data->user_email;
			if (!strlen($seller_bank_type)) { $seller_bank_type = 'Bank transfer'; }
			?>
			<form id="profseller-my-info" method="POST" class="form-add">
			<div class="row">
				<strong>Your details</strong>
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
				<strong>Preferred payment method</strong>
			</div>
			<div class="row" id="seller-bank-type">
				<input type="radio" name="seller_bank_type" value="Bank transfer"<?php if ($seller_bank_type == 'Bank transfer') { echo ' CHECKED'; } ?>>Bank transfer<br />
				<input type="radio" name="seller_bank_type" value="Paypal"<?php if ($seller_bank_type == 'Paypal') { echo ' CHECKED'; } ?>>Paypal
			</div>
			<div class="row" id="seller-bank-details">
				<label>Payment Details</label>
				<textarea name="seller_bank_details"><?php echo $seller_bank_details; ?></textarea>
			</div>
			<div class="row">
				<input type="submit" value="Submit">
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