<?php
/*
Template Name: TLC Admin files Page
*/

global $OPTION, $current_user;

get_header();

if (is_user_logged_in() && (in_array('administrator', $current_user->roles) || in_array('staff', $current_user->roles) || in_array('buyer', $current_user->roles) || in_array('editor', $current_user->roles))) {

$admin_items_per_page = $OPTION['wps_sellers_admin_files_items_per_page'];
$summary_page_url = get_permalink($OPTION['wps_sellers_summary_page']).'?seller=';
$prof_summary_page_url = get_permalink($OPTION['wps_profreseller_summary_page']).'?seller=';
$seller_categories = get_terms('seller-category', 'hide_empty=0');
$split_categories = sellers_get_split_categories();

$search_username = trim($_GET['search-username']);
$search_quotation = trim($_GET['search-quotation']);
$search_title = trim($_GET['search-title']);
$search_option = trim($_GET['search-option']);
$search_date_start = trim($_GET['search-date-start']);
$search_date_end = trim($_GET['search-date-end']);
$iscat = trim($_GET['iscat']);
$pscat = trim($_GET['pscat']);
$mtab = trim($_GET['mtab']);

if ($search_username == 'Username / Email') { $search_username = ''; }
$search_username = str_replace("'", "''", $search_username);
if ($search_quotation == 'Quotation Number') { $search_quotation = ''; }
if ($search_title == 'Post Title') { $search_title = ''; }

$sWhere = "";
if (strlen($search_username)) {
	$sWhere .= " AND (u.user_login LIKE '%".$search_username."%' OR u.user_email LIKE '%".$search_username."%')";
}
if (strlen($search_option)) {
	switch ($search_option) {
		case "today":
			$sdate = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
		break;
		case "days":
			$sdate = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") - 7, date("Y")));
		break;
		case "month":
			$sdate = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")));
		break;
	}
	$edate = date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")));
	$sWhere .= " AND p.post_date >= '".$sdate."' AND p.post_date <= '".$edate."'";
}
if (strlen($search_date_start)) {
	$sdatedata = explode("/", $search_date_start);
	$sdate = date("Y-m-d H:i:s", mktime(0, 0, 0, $sdatedata[1], $sdatedata[0], $sdatedata[2]));
	$sWhere .= " AND p.post_date >= '".$sdate."'";
}
if (strlen($search_date_end)) {
	$edatedata = explode("/", $search_date_end);
	$edate = date("Y-m-d H:i:s", mktime(0, 0, 0, $edatedata[1], $edatedata[0], $edatedata[2]));
	$sWhere .= " AND p.post_date <= '".$edate."'";
}
if (strlen($iscat)) {
	$scat_tt_id = $wpdb->get_var(sprintf("SELECT term_taxonomy_id FROM %sterm_taxonomy WHERE taxonomy = 'seller-category' AND term_id = %s", $wpdb->prefix, $iscat));
	if ($scat_tt_id) {
		$iseller_where = " AND tr.term_taxonomy_id = ".$scat_tt_id;
	}
}
if (strlen($pscat)) {
	$term_taxonomies = $wpdb->get_results(sprintf("SELECT term_taxonomy_id FROM %sterm_taxonomy WHERE taxonomy = 'seller-category' AND term_id IN (%s)", $wpdb->prefix, implode(",", $split_categories[$pscat])));
	if ($term_taxonomies) {
		$tt_ids = array();
		foreach($term_taxonomies as $term_taxonomy) {
			$tt_ids[] = $term_taxonomy->term_taxonomy_id;
		}
		$pseller_where = " AND tr.term_taxonomy_id IN (".implode(',', $tt_ids).")";
	}
}
if (strlen($search_quotation)) {
	$iseller_where .= " AND pm.meta_value LIKE '%".$search_quotation."%'";
	$pseller_where .= " AND pm.meta_value LIKE '%".$search_quotation."%'";
}
$title_where = '';
if (strlen($search_title)) {
	if (strpos($search_title, " ")) {
		$starray = explode(" ", $search_title);
		foreach($starray as $stword) {
			$title_where .= " AND p.post_title LIKE '%".$stword."%'";
		}
	} else {
		$title_where = " AND p.post_title LIKE '%".$search_title."%'";
	}
}

$sellers_includes = sellers_get_includes();
$tab = $_GET['tab'];
if ($_POST['tab'] != '') { $tab = $_POST['tab']; }

$isellers_cat_nums = array();
$psellers_cat_nums = array();
foreach($split_categories as $spcat => $spcats) {
	$psellers_cat_nums[$spcat] = 0;
}
foreach($seller_categories as $seller_category) {
	$isellers_cat_nums[$seller_category->term_id] = 0;
}

// individual sellers draft posts
$all_draft_posts = $wpdb->get_results(sprintf("SELECT * FROM %sposts WHERE post_type = 'post' AND post_status = 'iseller_draft' ORDER BY ID DESC", $wpdb->prefix));
if ($all_draft_posts) {
	foreach($all_draft_posts as $spost) {
		$item_tlc_viwed = (int)get_post_meta($spost->ID, 'item_tlc_viwed', true);
		if (!$item_tlc_viwed) {
			foreach($seller_categories as $seller_category) {
				if (has_term($seller_category->term_id, 'seller-category', $spost->ID)) {
					$isellers_cat_nums[$seller_category->term_id]++;
				}
			}
		}
	}
}
?>
<script>tlctab = "<?php echo $tab; ?>";</script>
<div class="main-tlc">
	<div id="content" style="position:relative;">
		<a href="<?php echo get_permalink($OPTION['wps_indvseller_add_item_page']); ?>?tlcadditem=true" class="tlc-add-item">Add Item</a>
		<ul class="main-tabs">
			<li id="tlc-indiv-tab"<?php if ($mtab != 'ps') { echo ' class="active"'; } ?>><a href="#isellers" rel="isellers">Individual Sellers</a></li>
			<li id="tlc-prof-tab"<?php if ($mtab == 'ps') { echo ' class="active"'; } ?>><a href="#psellers" rel="psellers">Professional Sellers</a></li>
		</ul>
		<div class="clear"></div>
		<div class="sellers-container" id="isellers"<?php if ($mtab == 'ps') { echo ' style="display:none;"'; } ?>>
			<ul class="category-items inner">
				<li<?php if ($iscat == '') { echo ' class="active"'; } ?>><a href="<?php the_permalink(); ?>" rel="is">All Categories</a></li>
				<?php if ($seller_categories) { ?>
					<?php foreach($seller_categories as $seller_category) { ?>
					<li<?php if ($iscat == $seller_category->term_id) { echo ' class="active"'; } ?>><a href="<?php the_permalink(); ?>?iscat=<?php echo $seller_category->term_id; ?>"><?php echo $seller_category->name; ?><?php if ($isellers_cat_nums[$seller_category->term_id] > 0) { ?> <span class="num"><?php echo $isellers_cat_nums[$seller_category->term_id]; ?></span><?php } ?></a></li>
					<?php } ?>
				<?php } ?>
			</ul>
			<ul class="tabset inner" style="padding-left:5px;">
				<li><a href="#tab-submitted-items-is" class="active">Submitted</a></li>
				<li><a href="#tab-pending-approval-items-is">Pending Approval</a></li>
				<li><a href="#tab-requests-items-is">Requests</a></li>
				<li><a href="#tab-approved-items-is">Approved</a></li>
				<li><a href="#tab-pickup-items-is">Pickup</a></li>
				<li><a href="#tab-received-items-is">Received</a></li>
				<li><a href="#tab-authenticated-items-is">To be photographed</a></li>
				<li><a href="#tab-on-sale-items-is">On Sale</a></li>
				<li><a href="#tab-sold-items-is">Sold</a></li>
			</ul>
			<!-- Items Submitted -->
			<div id="tab-submitted-items-is" class="tab-content">
				<div class="seller-products-list">
					<?php
					$pg = $_GET['issipg']; if (!$pg) { $pg = 1; }
					$limit = ' LIMIT '.(($pg - 1) * $admin_items_per_page).', '.$admin_items_per_page;

					$draft_user_posts = $wpdb->get_results(sprintf("SELECT SQL_CALC_FOUND_ROWS p.*, u.user_login FROM %sposts p LEFT JOIN %susers u ON u.ID = p.post_author LEFT JOIN %sterm_relationships tr ON tr.object_id = p.ID LEFT JOIN %spostmeta pm ON pm.post_id = p.ID AND pm.meta_key = 'ID_item' WHERE p.post_type = 'post' AND p.post_status = 'iseller_draft' %s %s GROUP BY p.ID ORDER BY p.ID DESC %s", $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $sWhere, $iseller_where.$title_where, $limit));
					$total_posts = $wpdb->get_var("SELECT FOUND_ROWS()");
					if ($draft_user_posts) {
						foreach($draft_user_posts as $spost) {
						$spost_picture = nws_get_item_thumb($spost->ID);
						$spost_price = get_post_meta($spost->ID, 'price', true);
						$spost_your_price = get_post_meta($spost->ID, 'item_your_price', true);
						$spost_includes = get_post_meta($spost->ID, 'item_includes', true);
						$item_tlc_viwed = (int)get_post_meta($spost->ID, 'item_tlc_viwed', true);
						$spost_username = $spost->user_login;
						$post_created = $spost->post_created;

						$item_includes = explode('|', $spost_includes);
						if (!strlen($post_created)) { $post_created = $spost->post_date; }

						$spost_price = sellers_currency_price($spost_price);
						$spost_your_price = sellers_currency_price($spost_your_price);
						?>
						<div class="product-item table product-item-<?php echo $spost->ID; ?> submitted-item-<?php echo $spost->ID; ?><?php if ($item_tlc_viwed == 0) { echo ' item-new'; } ?>">
							<?php if ($spost_picture) { ?><a href="<?php echo get_post_thumb($spost_picture, 800, 800); ?>" class="pic-zoom"><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" class="thumbnail" alt="" /></a><?php } ?>
							<div class="description v-middle">
								<h4><a href="<?php echo $summary_page_url.$spost->post_author; ?>"><?php echo $spost_username; ?></a></h4>
								<h4 class="item-title"><a href="<?php echo get_permalink($spost->ID); ?>"><?php echo $spost->post_title; ?></a></h4>
								<p><?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
								<div class="price-row">
									<span class="price"><strong>Seller Price:</strong> <?php echo format_price($spost_your_price, true); ?></span>
								</div>
								<p>Created on: <?php echo $post_created; ?></p>
							</div>
							<div class="btn-column v-middle">
								<a href="#view" class="link-pink view" name="<?php echo $spost->ID; ?>">View</a>
							</div>
							<div class="quotation-item-view-<?php echo $spost->ID; ?>" style="display:none;">
								<?php if ($spost_picture) { ?><a href="<?php echo get_post_thumb($spost_picture, 800, 800, true); ?>" target="_blank"><img src="<?php echo get_post_thumb($spost_picture, 140, 140, true); ?>" class="thumbnail" alt="" /></a><?php } ?>
								<div class="description">
									<h4><?php echo $spost->post_title; ?></h4>
									<?php
									$item_category_data = sellers_get_item_taxonomy_data($spost->ID, 'seller-category');
									$item_brand_data = sellers_get_item_taxonomy_data($spost->ID, 'brand');
									$item_selection_data = sellers_get_item_taxonomy_data($spost->ID, 'selection');
									$item_colour_data = sellers_get_item_taxonomy_data($spost->ID, 'colour');
									?>
									<ul>
										<li class="item-origin-price" rel="<?php echo $spost_price; ?>"><strong class="price">Original Price:</strong> <?php echo format_price($spost_price, true); ?></li>
										<li><strong class="price">Seller Asking Price:</strong> <?php echo format_price($spost_your_price, true); ?></li>
										<li class="item-category-data" rel="<?php echo $item_category_data['id']; ?>">
											<span>
												<strong>Category:</strong> <span class="item-category-name-<?php echo $spost->ID; ?>"><?php echo $item_category_data['name']; ?></span>
												&nbsp;&nbsp;<a href="#edit" onclick="tlc_edit_category_show(<?php echo $spost->ID; ?>); return false;">edit</a>
											</span>
											<span style="position:absolute; margin-left:15px; width:200px;">
												<span class="item-edit-category-<?php echo $spost->ID; ?>" style="display:none;"><select name="editcat" style="border:1px solid #C1C1C1; font:12px Arial,Helvetica,sans-serif; width:130px; float:left; margin-right:5px;"><?php foreach($seller_categories as $selcategory) { ?><option value="<?php echo $selcategory->term_id; ?>"<?php if ($selcategory->term_id == $item_category_data['id']) { echo ' SELECTED'; } ?>><?php echo $selcategory->name; ?></option><?php } ?></select>
												&nbsp;<input type="button" value="Save" style="border:1px solid #A5A5A5; padding:1px 5px; font:11px Arial,Helvetica,sans-serif;" onclick="tlc_edit_category_save(<?php echo $spost->ID; ?>);"></span>
											</span>
										</li>
										<li class="item-brand-data" rel="<?php echo $item_brand_data['id']; ?>"><strong>Brand:</strong> <?php echo $item_brand_data['name']; ?></li>
										<li class="item-selection-data" rel="<?php echo $item_selection_data['id']; ?>"><strong>Condition:</strong> <?php echo $item_selection_data['name']; ?></li>
										<?php if ($item_colour_data['id']) { ?><li class="item-colour-data" rel="<?php echo $item_colour_data['id']; ?>"><strong>Colour:</strong> <?php echo $item_colour_data['name']; ?></li><?php } ?>
										<li class="item-includes-data" rel="<?php echo $spost_includes; ?>"><strong>Includes:</strong> 
										<?php $csep = ''; foreach($item_includes as $item_include) { if (strlen($item_include)) { echo $csep.$sellers_includes[$item_include]; $csep = ', '; }} ?>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<?php } ?>
					<?php } ?>
					<?php if ($total_posts > $admin_items_per_page) { ?>
					<div class="pagenavi-holder">
						<?php sellers_admin_nav($total_posts, 'issipg', 'tab-submitted-items-is'); ?>
					</div>
					<?php } ?>
				</div>
			</div>
			<div class="clear"></div>
			<!-- Pending Approval -->
			<div id="tab-pending-approval-items-is" class="tab-content">
				<div class="sellers-other-tabs">
					<?php
					$pg = $_GET['ispaipg']; if (!$pg) { $pg = 1; }
					$limit = ' LIMIT '.(($pg - 1) * $admin_items_per_page).', '.$admin_items_per_page;

					$pending_user_posts = $wpdb->get_results(sprintf("SELECT SQL_CALC_FOUND_ROWS p.*, u.user_login, u.user_email FROM %sposts p LEFT JOIN %spostmeta pmi ON pmi.post_id = p.ID LEFT JOIN %susers u ON u.ID = p.post_author LEFT JOIN %sterm_relationships tr ON tr.object_id = p.ID LEFT JOIN %spostmeta pm ON pm.post_id = p.ID AND pm.meta_key = 'ID_item' WHERE p.post_type = 'post' AND p.post_status IN ('pending', 'iseller_pending') AND pmi.meta_key = 'item_seller' AND pmi.meta_value = 'i' %s %s GROUP BY p.ID ORDER BY p.ID DESC %s", $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $sWhere, $iseller_where.$title_where, $limit));
					$total_posts = $wpdb->get_var("SELECT FOUND_ROWS()");
					if ($pending_user_posts) {
						foreach($pending_user_posts as $spost) {
							$spost_username = $spost->user_login;
							$spost_picture = nws_get_item_thumb($spost->ID);
							$spost_price = get_post_meta($spost->ID, 'price', true);
							$spost_your_price = get_post_meta($spost->ID, 'item_your_price', true);
							$spost_includes = get_post_meta($spost->ID, 'item_includes', true);
							$spost_tlc_quotation_price_low = get_post_meta($spost->ID, 'item_tlc_quotation_price_low', true);
							$spost_tlc_quotation_price_high = get_post_meta($spost->ID, 'item_tlc_quotation_price_high', true);
							$follow_sent = get_post_meta($spost->ID, 'item_follow_sent', true);
							$post_quoted = get_post_meta($spost->ID, '_post_quoted', true);

							$qpdata = explode("-", $spost_tlc_quotation_price);
							$item_includes = explode('|', $spost_includes);

							$spost_price = sellers_currency_price($spost_price);
							$spost_your_price = sellers_currency_price($spost_your_price);
							$spost_tlc_quotation_price_low = sellers_currency_price($spost_tlc_quotation_price_low);
							$spost_tlc_quotation_price_high = sellers_currency_price($spost_tlc_quotation_price_high);

							$quotation_value = 'No Quote';
							if ($spost_tlc_quotation_price_low > 0 && $spost_tlc_quotation_price_high > 0) {
								$quotation_value = format_price($spost_tlc_quotation_price_high, true).' - '.format_price($spost_tlc_quotation_price_low, true);
							}
							?>
						<div class="product-item table product-item-<?php echo $spost->ID; ?><? if ($follow_sent) { echo ' follow-sent'; } ?>">
							<?php if ($spost_picture) { ?><a href="<?php echo get_post_thumb($spost_picture, 800, 800); ?>" class="pic-zoom"><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" class="thumbnail" alt="" /></a><?php } ?>
							<div class="description">
								<h4><a href="<?php echo $summary_page_url.$spost->post_author; ?>"><?php echo $spost_username; ?></a></h4>
								<h4 class="item-title"><a href="<?php echo get_permalink($spost->ID); ?>"><?php echo $spost->post_title; ?></a></h4>
								<p><?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
								<div class="price-row">
									<span class="price"><strong>Seller Price:</strong> <?php echo format_price($spost_your_price, true); ?></span>
									<span class="price"><strong>Quotation:</strong> <span class="quotation-value-<?php echo $spost->ID; ?>"><?php echo $quotation_value; ?></span></span>
								</div>
								<?php if ($post_quoted) { ?>
									<p>Quoted on: <?php echo $post_quoted; ?></p>
								<?php } ?>
							</div>
							<div class="btn-column v-middle">
								<a href="#change-quotation" class="link-pink change-quotation" name="<?php echo $spost->ID; ?>">Change Quotation</a>
								<a href="mailto:<?php echo $spost->user_email; ?>" class="link-pink" name="<?php echo $spost->ID; ?>">Follow Up</a>
							</div>
							<div class="quotation-item-view-<?php echo $spost->ID; ?>" style="display:none;">
								<?php if ($spost_picture) { ?><a href="<?php echo get_post_thumb($spost_picture, 800, 800, true); ?>" target="_blank"><img src="<?php echo get_post_thumb($spost_picture, 140, 140, true); ?>" class="thumbnail" alt="" /></a><?php } ?>
								<div class="description">
									<h4><?php echo $spost->post_title; ?></h4>
									<?php
									$item_category_data = sellers_get_item_taxonomy_data($spost->ID, 'category');
									$item_brand_data = sellers_get_item_taxonomy_data($spost->ID, 'brand');
									$item_selection_data = sellers_get_item_taxonomy_data($spost->ID, 'selection');
									$item_colour_data = sellers_get_item_taxonomy_data($spost->ID, 'colour');
									?>
									<ul>
										<li class="item-origin-price" rel="<?php echo $spost_price; ?>"><strong class="price">Original Price:</strong> <?php echo format_price($spost_price, true); ?></li>
										<li><strong class="price">Seller Asking Price:</strong> <?php echo format_price($spost_your_price, true); ?></li>
										<li class="item-category-data" rel="<?php echo $item_category_data['id']; ?>"><strong>Category:</strong> <?php echo $item_category_data['name']; ?></li>
										<li class="item-brand-data" rel="<?php echo $item_brand_data['id']; ?>"><strong>Brand:</strong> <?php echo $item_brand_data['name']; ?></li>
										<li class="item-selection-data" rel="<?php echo $item_selection_data['id']; ?>"><strong>Condition:</strong> <?php echo $item_selection_data['name']; ?></li>
										<?php if ($item_colour_data['id']) { ?><li class="item-colour-data" rel="<?php echo $item_colour_data['id']; ?>"><strong>Colour:</strong> <?php echo $item_colour_data['name']; ?></li><?php } ?>
										<li class="item-includes-data" rel="<?php echo $spost_includes; ?>"><strong>Includes:</strong> 
										<?php $csep = ''; foreach($item_includes as $item_include) { if (strlen($item_include)) { echo $csep.$sellers_includes[$item_include]; $csep = ', '; }} ?>
										</li>
									</ul>
								</div>
							</div>
						</div>
					<?php
						} 
					} ?>
					<?php if ($total_posts > $admin_items_per_page) { ?>
					<div class="pagenavi-holder">
						<?php sellers_admin_nav($total_posts, 'ispaipg', 'tab-pending-approval-items-is'); ?>
					</div>
					<?php } ?>
				</div>
			</div>
			<div class="clear"></div>
			<!-- Requests -->
			<div id="tab-requests-items-is" class="tab-content">
				<div class="sellers-other-tabs">
					<div class="a-box inner open">
						<div class="a-title">
							<div class="right">
								<span class="ico"></span>
							</div>
							<h3>Price requests by seller</h3>
						</div>
						<div class="a-content">
							<?php
							$pg = $_GET['isprpg']; if (!$pg) { $pg = 1; }
							$limit = ' LIMIT '.(($pg - 1) * $admin_items_per_page).', '.$admin_items_per_page;

							$pending_user_posts = $wpdb->get_results(sprintf("SELECT SQL_CALC_FOUND_ROWS p.*, u.user_login FROM %sposts p LEFT JOIN %spostmeta pmi ON pmi.post_id = p.ID LEFT JOIN %spostmeta pm2 ON pm2.post_id = p.ID LEFT JOIN %susers u ON u.ID = p.post_author LEFT JOIN %sterm_relationships tr ON tr.object_id = p.ID LEFT JOIN %spostmeta pm ON pm.post_id = p.ID AND pm.meta_key = 'ID_item' WHERE p.post_type = 'post' AND p.post_status = 'publish' AND pmi.meta_key = 'item_seller' AND pmi.meta_value = 'i' AND pm2.meta_key = 'item_request_price' AND pm2.meta_value = 'true' %s %s GROUP BY p.ID ORDER BY p.ID DESC %s", $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $sWhere, $iseller_where.$title_where, $limit));
							$total_posts = $wpdb->get_var("SELECT FOUND_ROWS()");
							if ($pending_user_posts) {
								foreach($pending_user_posts as $spost) { $spost_picture = nws_get_item_thumb($spost->ID);
								$spost_username = $spost->user_login;
								$spost_item_your_price = get_post_meta($spost->ID, 'item_your_price', true);
								$spost_item_your_quotation_price = get_post_meta($spost->ID, 'item_your_quotation_price', true);

								$spost_item_your_price = sellers_currency_price($spost_item_your_price);
								$spost_item_your_quotation_price = sellers_currency_price($spost_item_your_quotation_price);
								?>
								<div class="product-item table">
									<?php if ($spost_picture) { ?><a href="<?php echo get_post_thumb($spost_picture, 800, 800); ?>" class="pic-zoom"><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" class="thumbnail" alt="" /></a><?php } ?>
									<div class="description">
										<h4><a href="<?php echo $summary_page_url.$spost->post_author; ?>"><?php echo $spost_username; ?></a></h4>
										<h4 class="item-title"><a href="<?php echo get_permalink($spost->ID); ?>"><?php echo $spost->post_title; ?></a></h4>
										<p><?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
										<div class="price-row">
											<span class="price"><strong>Seller Old Payout:</strong> <?php echo format_price($spost_item_your_price, true); ?></span>
											<span class="price"><strong>Seller New Payout:</strong> <?php echo format_price($spost_item_your_quotation_price, true); ?></span>
										</div>
									</div>
									<div class="btn-column v-middle">
										<form method="POST">
										<input type="hidden" name="SellersAction" value="tlc_completed_item">
										<input type="hidden" name="post_id" value="<?php echo $spost->ID; ?>">
										<a href="#completed" class="link-pink completed">Completed</a>
										</form>
									</div>
								</div>
							<?php
								}
							} ?>
						</div>
						<?php if ($total_posts > $admin_items_per_page) { ?>
						<div class="pagenavi-holder">
							<?php sellers_admin_nav($total_posts, 'isprpg', 'tab-requests-items-is'); ?>
						</div>
						<div class="clear"></div>
						<?php } ?>
					</div>
					<div class="a-box inner open">
						<div class="a-title">
							<div class="right">
								<span class="ico"></span>
							</div>
							<h3>History</h3>
						</div>
						<div class="a-content">
							<?php
							$pg = $_GET['ishpg']; if (!$pg) { $pg = 1; }
							$limit = ' LIMIT '.(($pg - 1) * $admin_items_per_page).', '.$admin_items_per_page;

							$history_user_posts = $wpdb->get_results(sprintf("SELECT SQL_CALC_FOUND_ROWS p.*, u.user_login FROM %sposts p LEFT JOIN %spostmeta pmi ON pmi.post_id = p.ID LEFT JOIN %spostmeta pm2 ON pm2.post_id = p.ID LEFT JOIN %susers u ON u.ID = p.post_author LEFT JOIN %sterm_relationships tr ON tr.object_id = p.ID LEFT JOIN %spostmeta pm ON pm.post_id = p.ID WHERE p.post_type = 'post' AND p.post_status = 'publish' AND pmi.meta_key = 'item_seller' AND pmi.meta_value = 'i' AND pm2.meta_key = 'item_request_price' AND pm2.meta_value = 'completed' %s %s GROUP BY p.ID ORDER BY p.ID DESC %s", $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $sWhere, $iseller_where.$title_where, $limit));
							$total_posts = $wpdb->get_var("SELECT FOUND_ROWS()");
							if ($history_user_posts) {
								foreach($history_user_posts as $spost) { $spost_picture = nws_get_item_thumb($spost->ID);
								$spost_username = $spost->user_login;
								$spost_item_id = get_post_meta($spost->ID, 'ID_item', true);
								$spost_item_your_price = get_post_meta($spost->ID, 'item_your_price', true);
								$spost_item_your_quotation_price = get_post_meta($spost->ID, 'item_your_quotation_price', true);
								$spost_suggested_price = get_post_meta($spost->ID, 'item_suggested_price', true);

								$spost_item_your_price = sellers_currency_price($spost_item_your_price);
								$spost_item_your_quotation_price = sellers_currency_price($spost_item_your_quotation_price);
								?>
								<div class="product-item table">
									<?php if ($spost_picture) { ?><a href="<?php echo get_post_thumb($spost_picture, 800, 800); ?>" class="pic-zoom"><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" class="thumbnail" alt="" /></a><?php } ?>
									<div class="description">
										<h4><a href="<?php echo $summary_page_url.$spost->post_author; ?>"><?php echo $spost_username; ?></a></h4>
										<h4 class="item-title"><a href="<?php echo get_permalink($spost->ID); ?>"><?php echo $spost->post_title; ?></a></h4>
										<p><?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
										<div class="price-row">
											<?php
											$opayout_title = 'Seller Old Payout';
											$npayout_title = 'Seller New Payout';
											if ($spost_suggested_price == 'completed') {
												$opayout_title = 'Current Payout';
												$npayout_title = 'Requested Payout';
											}
											?>
											<span class="price"><strong><?php echo $opayout_title; ?>:</strong> <?php echo format_price($spost_item_your_price, true); ?></span>
											<span class="price"><strong><?php echo $npayout_title; ?>:</strong> <?php echo format_price($spost_item_your_quotation_price, true); ?></span>
										</div>
									</div>
								</div>
							<?php
								}
							} ?>
						</div>
						<?php if ($total_posts > $admin_items_per_page) { ?>
						<div class="pagenavi-holder">
							<?php sellers_admin_nav($total_posts, 'ishpg', 'tab-requests-items-is'); ?>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="clear"></div>
			<!-- Approved -->
			<div id="tab-approved-items-is" class="tab-content approved-items">
				<div class="sellers-other-tabs">
					<form method="POST">
					<input type="hidden" name="SellersAction" value="tlc_pickup_items">
					<?php
					$pg = $_GET['isaipg']; if (!$pg) { $pg = 1; }
					$limit = ' LIMIT '.(($pg - 1) * $admin_items_per_page).', '.$admin_items_per_page;

					$approved_user_posts = $wpdb->get_results(sprintf("SELECT SQL_CALC_FOUND_ROWS p.*, u.user_login FROM %sposts p LEFT JOIN %susers u ON u.ID = p.post_author LEFT JOIN %sterm_relationships tr ON tr.object_id = p.ID LEFT JOIN %spostmeta pm ON pm.post_id = p.ID AND pm.meta_key = 'ID_item' WHERE p.post_type = 'post' AND p.post_status = 'iseller_approved' %s %s GROUP BY p.ID ORDER BY p.ID DESC %s", $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $sWhere, $iseller_where.$title_where, $limit));
					$total_posts = $wpdb->get_var("SELECT FOUND_ROWS()");
					if ($approved_user_posts) {
						foreach($approved_user_posts as $spost) {
							$spost_picture = nws_get_item_thumb($spost->ID);
							$spost_username = $spost->user_login;
							$spost_item_your_quotation_price = get_post_meta($spost->ID, 'item_your_quotation_price', true);
							$spost_price = get_post_meta($spost->ID, 'price', true);
							$spost_new_price = get_post_meta($spost->ID, 'new_price', true);
							$item_seller = $spost->meta_value;
							$post_modified = $spost->post_modified;

							if (!$spost_new_price) { $spost_new_price = $spost_price; }

							$spost_new_price = sellers_currency_price($spost_new_price);
							$spost_item_your_quotation_price = sellers_currency_price($spost_item_your_quotation_price);
						?>
						<div class="product-item">
							<div class="published-check"><input type="checkbox" name="postid[]" value="<?php echo $spost->ID; ?>"></div>
							<?php if ($spost_picture) { ?><a href="<?php echo get_post_thumb($spost_picture, 800, 800); ?>" class="pic-zoom"><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" class="thumbnail" alt="" /></a><?php } ?>
							<div class="description">
								<h4><a href="<?php echo $summary_page_url.$spost->post_author; ?>"><?php echo $spost_username; ?></a></h4>
								<h4 class="item-title"><a href="<?php echo get_permalink($spost->ID); ?>"><?php echo $spost->post_title; ?></a></h4>
								<p><?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
								<div class="price-row">
									<span class="price"><strong>Seller Payout:</strong> <?php echo format_price($spost_item_your_quotation_price, true); ?></span>
									<span class="price"><strong>The Luxury Closet Selling Price:</strong> <?php echo format_price($spost_new_price, true); ?></span>
								</div>
								<?php if ($post_modified) { ?>
									<p>Modified on: <?php echo $post_modified; ?></p>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
					<div class="pickup-button">
						<input type="submit" value="Pickup Scheduled">
					</div>
					<?php if ($total_posts > $admin_items_per_page) { ?>
					<div class="pagenavi-holder">
						<?php sellers_admin_nav($total_posts, 'isaipg', 'tab-approved-items-is'); ?>
					</div>
					<?php }
					} ?>
					</form>
				</div>
			</div>
			<div class="clear"></div>
			<!-- Pickup -->
			<div id="tab-pickup-items-is" class="tab-content">
				<div class="sellers-other-tabs">
					<form method="POST">
					<input type="hidden" name="SellersAction" value="tlc_received_items">
					<?php
					$pg = $_GET['ispipg']; if (!$pg) { $pg = 1; }
					$limit = ' LIMIT '.(($pg - 1) * $admin_items_per_page).', '.$admin_items_per_page;

					$approved_user_posts = $wpdb->get_results(sprintf("SELECT SQL_CALC_FOUND_ROWS p.*, u.user_login FROM %sposts p LEFT JOIN %susers u ON u.ID = p.post_author LEFT JOIN %sterm_relationships tr ON tr.object_id = p.ID LEFT JOIN %spostmeta pm ON pm.post_id = p.ID AND pm.meta_key = 'ID_item' WHERE p.post_type = 'post' AND p.post_status = 'iseller_pickup' %s %s GROUP BY p.ID ORDER BY p.ID DESC %s", $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $sWhere, $iseller_where.$title_where, $limit));
					$total_posts = $wpdb->get_var("SELECT FOUND_ROWS()");
					if ($approved_user_posts) {
						foreach($approved_user_posts as $spost) {
							$spost_picture = nws_get_item_thumb($spost->ID);
							$spost_username = $spost->user_login;
							$spost_item_your_quotation_price = get_post_meta($spost->ID, 'item_your_quotation_price', true);
							$spost_price = get_post_meta($spost->ID, 'price', true);
							$spost_new_price = get_post_meta($spost->ID, 'new_price', true);
							$item_seller = $spost->meta_value;
							$post_modified = $spost->post_modified;

							if (!$spost_new_price) { $spost_new_price = $spost_price; }

							$spost_new_price = sellers_currency_price($spost_new_price);
							$spost_item_your_quotation_price = sellers_currency_price($spost_item_your_quotation_price);
						?>
						<div class="product-item">
							<div class="published-check"><input type="checkbox" name="postid[]" value="<?php echo $spost->ID; ?>"></div>
							<?php if ($spost_picture) { ?><a href="<?php echo get_post_thumb($spost_picture, 800, 800); ?>" class="pic-zoom"><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" class="thumbnail" alt="" /></a><?php } ?>
							<div class="description">
								<h4><a href="<?php echo $summary_page_url.$spost->post_author; ?>"><?php echo $spost_username; ?></a></h4>
								<h4 class="item-title"><a href="<?php echo get_permalink($spost->ID); ?>"><?php echo $spost->post_title; ?></a></h4>
								<p><?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
								<div class="price-row">
									<span class="price"><strong>Seller Payout:</strong> <?php echo format_price($spost_item_your_quotation_price, true); ?></span>
									<span class="price"><strong>The Luxury Closet Selling Price:</strong> <?php echo format_price($spost_new_price, true); ?></span>
								</div>
								<?php if ($post_modified) { ?>
									<p>Modified on: <?php echo $post_modified; ?></p>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
					<div class="received-button">
						<input type="submit" value="Received">
					</div>
					<?php if ($total_posts > $admin_items_per_page) { ?>
					<div class="pagenavi-holder">
						<?php sellers_admin_nav($total_posts, 'ispipg', 'tab-pickup-items-is'); ?>
					</div>
					<?php }
					} ?>
					</form>
				</div>
			</div>
			<div class="clear"></div>
			<!-- Received -->
			<div id="tab-received-items-is" class="tab-content">
				<div class="sellers-other-tabs">
					<form name="received_items_form" method="POST">
					<input type="hidden" name="SellersAction" value="">
					<?php
					$pg = $_GET['isripg']; if (!$pg) { $pg = 1; }
					$limit = ' LIMIT '.(($pg - 1) * $admin_items_per_page).', '.$admin_items_per_page;

					$approved_user_posts = $wpdb->get_results(sprintf("SELECT SQL_CALC_FOUND_ROWS p.*, u.user_login, pmrd.meta_value as post_received, STR_TO_DATE(pmrd.meta_value, '%s') as rdate FROM %sposts p LEFT JOIN %susers u ON u.ID = p.post_author LEFT JOIN %sterm_relationships tr ON tr.object_id = p.ID LEFT JOIN %spostmeta pm ON pm.post_id = p.ID AND pm.meta_key = 'ID_item' LEFT JOIN %spostmeta pmrd ON pmrd.post_id = p.ID AND pmrd.meta_key = '_post_received' WHERE p.post_type = 'post' AND p.post_status = 'iseller_received' %s %s GROUP BY p.ID ORDER BY rdate %s", '%Y-%m-%d', $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $sWhere, $iseller_where.$title_where, $limit));
					$total_posts = $wpdb->get_var("SELECT FOUND_ROWS()");
					if ($approved_user_posts) {
						foreach($approved_user_posts as $spost) {
							$spost_picture = nws_get_item_thumb($spost->ID);
							$spost_username = $spost->user_login;
							$spost_item_your_quotation_price = get_post_meta($spost->ID, 'item_your_quotation_price', true);
							$spost_price = get_post_meta($spost->ID, 'price', true);
							$spost_new_price = get_post_meta($spost->ID, 'new_price', true);
							$item_seller = $spost->meta_value;
							//$post_received = get_post_meta($spost->ID, '_post_received', true);
							$post_received = $spost->post_received;

							if (!$spost_new_price) { $spost_new_price = $spost_price; }

							$spost_new_price = sellers_currency_price($spost_new_price);
							$spost_item_your_quotation_price = sellers_currency_price($spost_item_your_quotation_price);
						?>
						<div class="product-item">
							<div class="published-check"><input type="checkbox" name="postid[]" value="<?php echo $spost->ID; ?>"></div>
							<?php if ($spost_picture) { ?><a href="<?php echo get_post_thumb($spost_picture, 800, 800); ?>" class="pic-zoom"><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" class="thumbnail" alt="" /></a><?php } ?>
							<div class="description">
								<h4><a href="<?php echo $summary_page_url.$spost->post_author; ?>"><?php echo $spost_username; ?></a></h4>
								<h4 class="item-title"><a href="<?php echo get_permalink($spost->ID); ?>"><?php echo $spost->post_title; ?></a></h4>
								<p><?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
								<div class="price-row">
									<span class="price"><strong>Seller Payout:</strong> <?php echo format_price($spost_item_your_quotation_price, true); ?></span>
									<span class="price"><strong>The Luxury Closet Selling Price:</strong> <?php echo format_price($spost_new_price, true); ?></span>
								</div>
								<?php if (strlen($post_received)) { ?>
									<p>Received on: <?php echo $post_received; ?></p>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
					<div class="returned-button">
						<input type="submit" value="Returned" onclick="document.received_items_form.SellersAction.value='tlc_returned_items';">
					</div>
					<div class="authenticated-button">
						<input type="submit" value="Authenticated" onclick="document.received_items_form.SellersAction.value='tlc_authenticated_items';">
					</div>
					<?php if ($total_posts > $admin_items_per_page) { ?>
					<div class="pagenavi-holder">
						<?php sellers_admin_nav($total_posts, 'isripg', 'tab-received-items-is'); ?>
					</div>
					<?php }
					} ?>
					</form>
				</div>
			</div>
			<div class="clear"></div>
			<!-- To be photographed -->
			<div id="tab-authenticated-items-is" class="tab-content">
				<div class="sellers-other-tabs">
					<form method="POST">
					<input type="hidden" name="SellersAction" value="tlc_authenticated_items">
					<?php
					$pg = $_GET['isaipg']; if (!$pg) { $pg = 1; }
					$limit = ' LIMIT '.(($pg - 1) * $admin_items_per_page).', '.$admin_items_per_page;

					$authenticated_user_posts = $wpdb->get_results(sprintf("SELECT SQL_CALC_FOUND_ROWS p.*, u.user_login, pmrd.meta_value as post_received, STR_TO_DATE(pmrd.meta_value, '%s') as rdate FROM %sposts p LEFT JOIN %susers u ON u.ID = p.post_author LEFT JOIN %sterm_relationships tr ON tr.object_id = p.ID LEFT JOIN %spostmeta pm ON pm.post_id = p.ID AND pm.meta_key = 'ID_item' LEFT JOIN %spostmeta pmrd ON pmrd.post_id = p.ID AND pmrd.meta_key = '_post_received' WHERE p.post_type = 'post' AND p.post_status = 'iseller_authed' %s %s GROUP BY p.ID ORDER BY rdate %s", '%Y-%m-%d', $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $sWhere, $iseller_where.$title_where, $limit));
					$total_posts = $wpdb->get_var("SELECT FOUND_ROWS()");
					if ($authenticated_user_posts) {
						foreach($authenticated_user_posts as $spost) {
							$spost_picture = nws_get_item_thumb($spost->ID);
							$spost_username = $spost->user_login;
							$spost_item_your_quotation_price = get_post_meta($spost->ID, 'item_your_quotation_price', true);
							$spost_price = get_post_meta($spost->ID, 'price', true);
							$spost_new_price = get_post_meta($spost->ID, 'new_price', true);
							$item_seller = $spost->meta_value;
							$post_received = $spost->post_received;

							if (!$spost_new_price) { $spost_new_price = $spost_price; }

							$spost_new_price = sellers_currency_price($spost_new_price);
							$spost_item_your_quotation_price = sellers_currency_price($spost_item_your_quotation_price);
						?>
						<div class="product-item">
							<div class="published-check"><input type="checkbox" name="postid[]" value="<?php echo $spost->ID; ?>"></div>
							<?php if ($spost_picture) { ?><a href="<?php echo get_post_thumb($spost_picture, 800, 800); ?>" class="pic-zoom"><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" class="thumbnail" alt="" /></a><?php } ?>
							<div class="description">
								<h4><a href="<?php echo $summary_page_url.$spost->post_author; ?>"><?php echo $spost_username; ?></a></h4>
								<h4 class="item-title"><a href="<?php echo get_permalink($spost->ID); ?>"><?php echo $spost->post_title; ?></a></h4>
								<p><?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
								<div class="price-row">
									<span class="price"><strong>Seller Payout:</strong> <?php echo format_price($spost_item_your_quotation_price, true); ?></span>
									<span class="price"><strong>The Luxury Closet Selling Price:</strong> <?php echo format_price($spost_new_price, true); ?></span>
								</div>
								<?php if ($post_received) { ?>
									<p>Received on: <?php echo $post_received; ?></p>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
					<?php if ($total_posts > $admin_items_per_page) { ?>
					<div class="pagenavi-holder">
						<?php sellers_admin_nav($total_posts, 'isaipg', 'tab-authenticated-items-is'); ?>
					</div>
					<?php }
					} ?>
					</form>
				</div>
			</div>
			<div class="clear"></div>
			<!-- On Sale -->
			<div id="tab-on-sale-items-is" class="tab-content">
				<div class="sellers-other-tabs">
					<?php
					$pg = $_GET['isoipg']; if (!$pg) { $pg = 1; }
					$limit = ' LIMIT '.(($pg - 1) * $admin_items_per_page).', '.$admin_items_per_page;

					$on_sale_user_posts = $wpdb->get_results(sprintf("SELECT SQL_CALC_FOUND_ROWS p.*, u.user_login FROM %sposts p LEFT JOIN %spostmeta pmi ON pmi.post_id = p.ID LEFT JOIN %susers u ON u.ID = p.post_author LEFT JOIN %sterm_relationships tr ON tr.object_id = p.ID LEFT JOIN %spostmeta pm ON pm.post_id = p.ID AND pm.meta_key = 'ID_item' WHERE p.post_type = 'post' AND p.post_status = 'publish' AND p.inventory > 0 AND pmi.meta_key = 'item_seller' AND pmi.meta_value = 'i' %s %s GROUP BY p.ID ORDER BY p.ID DESC %s", $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $sWhere, $iseller_where, $limit));
					$total_posts = $wpdb->get_var("SELECT FOUND_ROWS()");
					if ($on_sale_user_posts) {
						foreach($on_sale_user_posts as $spost) { $spost_picture = nws_get_item_thumb($spost->ID);
						$spost_username = $spost->user_login;
						$spost_item_your_quotation_price = get_post_meta($spost->ID, 'item_your_quotation_price', true);
						$spost_price = get_post_meta($spost->ID, 'price', true);
						$spost_new_price = get_post_meta($spost->ID, 'new_price', true);
						$item_seller = $spost->meta_value;
						$post_published = $spost->post_date;

						if (!$spost_new_price) { $spost_new_price = $spost_price; }

						$spost_new_price = sellers_currency_price($spost_new_price);
						$spost_item_your_quotation_price = sellers_currency_price($spost_item_your_quotation_price);
						?>
						<div class="product-item">
							<?php if ($spost_picture) { ?><a href="<?php echo get_post_thumb($spost_picture, 800, 800); ?>" class="pic-zoom"><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" class="thumbnail" alt="" /></a><?php } ?>
							<div class="description">
								<h4><a href="<?php echo $summary_page_url.$spost->post_author; ?>"><?php echo $spost_username; ?></a></h4>
								<h4 class="item-title"><a href="<?php echo get_permalink($spost->ID); ?>"><?php echo $spost->post_title; ?></a></h4>
								<p><?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
								<div class="price-row">
									<span class="price"><strong>Seller Payout:</strong> <?php echo format_price($spost_item_your_quotation_price, true); ?></span>
									<span class="price"><strong>The Luxury Closet Selling Price:</strong> <?php echo format_price($spost_new_price, true); ?></span>
								</div>
								<p>Published on: <?php echo $post_published; ?></p>
							</div>
						</div>
					<?php }
					if ($total_posts > $admin_items_per_page) { ?>
					<div class="pagenavi-holder">
						<?php sellers_admin_nav($total_posts, 'isoipg', 'tab-on-sale-items-is'); ?>
					</div>
					<?php }
					} ?>
				</div>
			</div>
			<div class="clear"></div>
			<!-- Sold -->
			<div id="tab-sold-items-is" class="tab-content">
				<div class="sellers-other-tabs">
					<?php
					$olevel_vals = array(
						0 => 'Cancelled',
						3 => 'Layaway',
						4 => 'New',
						5 => 'Shipped',
						6 => 'Received',
						7 => 'Completed',
						8 => 'Pending'
					);
					$pg = $_GET['issoipg']; if (!$pg) { $pg = 1; }
					$limit = ' LIMIT '.(($pg - 1) * $admin_items_per_page).', '.$admin_items_per_page;

					$sold_user_posts = $wpdb->get_results(sprintf("SELECT SQL_CALC_FOUND_ROWS p.*, u.user_login, o.level, o.layaway_order FROM %sposts p LEFT JOIN %spostmeta pmi ON pmi.post_id = p.ID LEFT JOIN %susers u ON u.ID = p.post_author LEFT JOIN %sterm_relationships tr ON tr.object_id = p.ID LEFT JOIN %spostmeta pm ON pm.post_id = p.ID AND pm.meta_key = 'ID_item' LEFT JOIN %swps_shopping_cart sc ON sc.postID = p.ID AND sc.order_id > 0 LEFT JOIN %swps_orders o ON o.oid = sc.order_id WHERE p.post_type = 'post' AND p.post_status = 'publish' AND p.inventory = 0 AND pmi.meta_key = 'item_seller' AND pmi.meta_value = 'i' %s %s GROUP BY p.ID ORDER BY p.ID DESC %s", $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $sWhere, $iseller_where.$title_where, $limit));
					$total_posts = $wpdb->get_var("SELECT FOUND_ROWS()");
					if ($sold_user_posts) {
						foreach($sold_user_posts as $spost) { $spost_picture = nws_get_item_thumb($spost->ID);
						$spost_username = $spost->user_login;
						$spost_item_your_quotation_price = get_post_meta($spost->ID, 'item_your_quotation_price', true);
						$spost_price = get_post_meta($spost->ID, 'price', true);
						$spost_new_price = get_post_meta($spost->ID, 'new_price', true);
						$item_seller = $spost->meta_value;
						if (!$spost_new_price) { $spost_new_price = $spost_price; }

						$order_level = '';
						$olevel = $spost->level;
						if ($spost->layaway_order > 0) {
							$olevel = $wpdb->get_var(sprintf("SELECT level FROM %swps_orders WHERE oid = %s", $wpdb->prefix, $spost->layaway_order));
						}
						if ($olevel) { $order_level = $olevel_vals[$olevel]; }

						$spost_new_price = sellers_currency_price($spost_new_price);
						$spost_item_your_quotation_price = sellers_currency_price($spost_item_your_quotation_price);
						?>
						<div class="product-item">
							<?php if ($spost_picture) { ?><a href="<?php echo get_post_thumb($spost_picture, 800, 800); ?>" class="pic-zoom"><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" class="thumbnail" alt="" /></a><?php } ?>
							<div class="description">
								<h4><a href="<?php echo $summary_page_url.$spost->post_author; ?>"><?php echo $spost_username; ?></a></h4>
								<h4 class="item-title"><a href="<?php echo get_permalink($spost->ID); ?>"><?php echo $spost->post_title; ?></a></h4>
								<p><?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
								<div class="price-row">
									<span class="price"><strong>Seller Payout:</strong> <?php echo format_price($spost_item_your_quotation_price, true); ?></span>
									<span class="price"><strong>The Luxury Closet Selling Price:</strong> <?php echo format_price($spost_new_price, true); ?></span>
								</div>
								<?php if (strlen($order_level)) { ?>
									<?php echo $order_level; ?> Order
								<?php } ?>
							</div>
						</div>
					<?php }
					if ($total_posts > $admin_items_per_page) { ?>
					<div class="pagenavi-holder">
						<?php sellers_admin_nav($total_posts, 'issoipg', 'tab-sold-items-is'); ?>
					</div>
					<?php }
					} ?>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<!-- PROFESSIONAL SELLERS -->
<?php
		$all_pending_user_posts = $wpdb->get_results(sprintf("SELECT * FROM %sposts WHERE post_type = 'post' AND post_status = 'pseller_pending' ORDER BY ID DESC", $wpdb->prefix));
		if ($all_pending_user_posts) {
			foreach($all_pending_user_posts as $spost) {
				$item_tlc_viwed = (int)get_post_meta($spost->ID, 'item_tlc_viwed', true);
				if (!$item_tlc_viwed) {
					foreach($split_categories as $spcat => $spcats) {
						if (in_category($spcats, $spost->ID)) {
							$psellers_cat_nums[$spcat]++;
						}
					}
				}
			}
		}
?>
		<div class="sellers-container" id="psellers"<?php if ($mtab != 'ps') { echo ' style="display:none;"'; } ?>>
			<ul class="category-items inner">
				<li<?php if ($pscat == '') { echo ' class="active"'; } ?>><a href="<?php the_permalink(); ?>?mtab=ps">All Categories</a></li>
				<li<?php if ($pscat == 'bags') { echo ' class="active"'; } ?>><a href="<?php the_permalink(); ?>?pscat=bags&mtab=ps" rel="ps">Handbags<?php if ($psellers_cat_nums['bags'] > 0) { ?> <span class="num"><?php echo $psellers_cat_nums['bags']; ?></span><?php } ?></a></li>
				<li<?php if ($pscat == 'shoes') { echo ' class="active"'; } ?>><a href="<?php the_permalink(); ?>?pscat=shoes&mtab=ps" rel="ps">Shoes<?php if ($psellers_cat_nums['shoes'] > 0) { ?> <span class="num"><?php echo $psellers_cat_nums['shoes']; ?></span><?php } ?></a></li>
				<li<?php if ($pscat == 'watches') { echo ' class="active"'; } ?>><a href="<?php the_permalink(); ?>?pscat=watches&mtab=ps" rel="ps">Watches<?php if ($psellers_cat_nums['watches'] > 0) { ?> <span class="num"><?php echo $psellers_cat_nums['watches']; ?></span><?php } ?></a></li>
				<li<?php if ($pscat == 'sunglasses') { echo ' class="active"'; } ?>><a href="<?php the_permalink(); ?>?pscat=sunglasses&mtab=ps" rel="ps">Sunglasses<?php if ($psellers_cat_nums['sunglasses'] > 0) { ?> <span class="num"><?php echo $psellers_cat_nums['sunglasses']; ?></span><?php } ?></a></li>
				<li<?php if ($pscat == 'jewelry') { echo ' class="active"'; } ?>><a href="<?php the_permalink(); ?>?pscat=jewelry&mtab=ps" rel="ps">Jewelry<?php if ($psellers_cat_nums['jewelry'] > 0) { ?> <span class="num"><?php echo $psellers_cat_nums['jewelry']; ?></span><?php } ?></a></li>
			</ul>
			<ul class="tabset inner" style="padding-left:5px;">
				<li><a href="#tab-submitted-items-ps" class="active">Submitted</a></li>
				<li><a href="#tab-approved-items-ps">Approved</a></li>
				<li><a href="#tab-on-sale-items-ps">On Sale</a></li>
				<li><a href="#tab-sold-items-ps">Sold</a></li>
			</ul>
			<!-- Submitted -->
			<div id="tab-submitted-items-ps" class="tab-content">
				<form method="POST">
				<input type="hidden" name="SellersAction" value="tlc_approved_items">
				<?php
				$approved_user_posts = $wpdb->get_results(sprintf("SELECT SQL_CALC_FOUND_ROWS p.*, u.user_login, u.user_email FROM %sposts p LEFT JOIN %spostmeta pm ON pm.post_id = p.ID AND pm.meta_key = 'ID_item' LEFT JOIN %susers u ON u.ID = p.post_author LEFT JOIN %sterm_relationships tr ON tr.object_id = p.ID WHERE p.post_type = 'post' AND p.post_status = 'pseller_pending' %s %s GROUP BY p.ID ORDER BY p.ID DESC", $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $sWhere, $pseller_where.$title_where));
				if ($approved_user_posts) {
					foreach($approved_user_posts as $spost) {
						$spost_picture = nws_get_item_thumb($spost->ID);
						$spost_username = $spost->user_login;
						$spost_price = get_post_meta($spost->ID, 'price', true);
						$spost_new_price = get_post_meta($spost->ID, 'new_price', true);
						$seller_price = get_post_meta($spost->ID, 'item_your_price', true);
						$item_seller = $spost->meta_value;
						if (!$spost_new_price) { $spost_new_price = $spost_price; }

						$spost_price = sellers_currency_price($spost_price);
						$spost_new_price = sellers_currency_price($spost_new_price);
						$seller_price = sellers_currency_price($seller_price);
					?>
					<div class="product-item">
						<div class="published-check"><input type="checkbox" name="postid[]" value="<?php echo $spost->ID; ?>"></div>
						<?php if ($spost_picture) { ?><a href="<?php echo get_post_thumb($spost_picture, 800, 800); ?>" class="pic-zoom"><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" class="thumbnail" alt="" /></a><?php } ?>
						<div class="btn-column" style="float:right;">
							<a href="mailto:<?php echo $spost->user_email; ?>" class="link-pink">Make a Request</a>
						</div>
						<div class="description">
							<h4><a href="<?php echo $prof_summary_page_url.$spost->post_author; ?>"><?php echo $spost_username; ?></a></h4>
							<h4 class="item-title"><a href="<?php echo get_permalink($spost->ID); ?>"><?php echo $spost->post_title; ?></a></h4>
							<p><?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
							<div class="price-row">
								<span class="price"><strong>Seller Price:</strong> <?php echo format_price($seller_price, true); ?></span>
								<span class="price"><strong>The Luxury Closet Selling Price:</strong> <?php echo format_price($spost_new_price, true); ?></span>
							</div>
						</div>
					</div>
				<?php } ?>
				<div class="approved-button">
					<input type="submit" value="Approved">
				</div>
				<?php
				} ?>
				</form>
			</div>
			<div class="clear"></div>
			<!-- Approved -->
			<div id="tab-approved-items-ps" class="tab-content">
				<form method="POST">
				<input type="hidden" name="SellersAction" value="tlc_published_items">
				<?php
				$pg = $_GET['pssipg']; if (!$pg) { $pg = 1; }
				$limit = ' LIMIT '.(($pg - 1) * $admin_items_per_page).', '.$admin_items_per_page;

				$approved_user_posts = $wpdb->get_results(sprintf("SELECT SQL_CALC_FOUND_ROWS p.*, u.user_login FROM %sposts p LEFT JOIN %spostmeta pm ON pm.post_id = p.ID AND pm.meta_key = 'ID_item' LEFT JOIN %susers u ON u.ID = p.post_author LEFT JOIN %sterm_relationships tr ON tr.object_id = p.ID WHERE p.post_type = 'post' AND p.post_status = 'pseller_approved' %s %s GROUP BY p.ID ORDER BY p.ID DESC %s", $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $sWhere, $pseller_where.$title_where, $limit));
				$total_posts = $wpdb->get_var("SELECT FOUND_ROWS()");
				if ($approved_user_posts) {
					foreach($approved_user_posts as $spost) {
						$spost_picture = nws_get_item_thumb($spost->ID);
						$spost_username = $spost->user_login;
						$spost_price = get_post_meta($spost->ID, 'price', true);
						$spost_new_price = get_post_meta($spost->ID, 'new_price', true);
						$seller_price = get_post_meta($spost->ID, 'item_your_price', true);
						$item_seller = $spost->meta_value;
						if (!$spost_new_price) { $spost_new_price = $spost_price; }

						$spost_price = sellers_currency_price($spost_price);
						$spost_new_price = sellers_currency_price($spost_new_price);
						$seller_price = sellers_currency_price($seller_price);
					?>
					<div class="product-item">
						<div class="published-check"><input type="checkbox" name="postid[]" value="<?php echo $spost->ID; ?>"></div>
						<?php if ($spost_picture) { ?><a href="<?php echo get_post_thumb($spost_picture, 800, 800); ?>" class="pic-zoom"><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" class="thumbnail" alt="" /></a><?php } ?>
						<div class="description">
							<h4><a href="<?php echo $prof_summary_page_url.$spost->post_author; ?>"><?php echo $spost_username; ?></a></h4>
							<h4 class="item-title"><a href="<?php echo get_permalink($spost->ID); ?>"><?php echo $spost->post_title; ?></a></h4>
							<p><?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
							<div class="price-row">
								<span class="price"><strong>Seller Price:</strong> <?php echo format_price($seller_price, true); ?></span>
								<span class="price"><strong>The Luxury Closet Selling Price:</strong> <?php echo format_price($spost_new_price, true); ?></span>
							</div>
						</div>
					</div>
				<?php } ?>
				<div class="published-button">
					<input type="submit" value="Published">
				</div>
				<?php if ($total_posts > $admin_items_per_page) { ?>
				<div class="pagenavi-holder">
					<?php sellers_admin_nav($total_posts, 'psaipg', 'tab-approved-items-ps'); ?>
				</div>
				<?php }
				} ?>
				</form>
			</div>
			<div class="clear"></div>
			<!-- On Sale -->
			<div id="tab-on-sale-items-ps" class="tab-content">
				<div class="sellers-other-tabs">
					<?php
					$pg = $_GET['psoipg']; if (!$pg) { $pg = 1; }
					$limit = ' LIMIT '.(($pg - 1) * $admin_items_per_page).', '.$admin_items_per_page;

					$on_sale_user_posts = $wpdb->get_results(sprintf("SELECT SQL_CALC_FOUND_ROWS p.*, pm.meta_value, u.user_login FROM %sposts p LEFT JOIN %spostmeta pm ON pm.post_id = p.ID AND pm.meta_key = 'ID_item' LEFT JOIN %spostmeta pm2 ON pm2.post_id = p.ID LEFT JOIN %susers u ON u.ID = p.post_author LEFT JOIN %sterm_relationships tr ON tr.object_id = p.ID WHERE p.post_type = 'post' AND p.post_status = 'publish' AND p.inventory > 0 AND pm2.meta_key = 'item_seller' AND pm2.meta_value = 'p' %s %s GROUP BY p.ID ORDER BY p.ID DESC %s", $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $sWhere, $pseller_where, $limit));
					$total_posts = $wpdb->get_var("SELECT FOUND_ROWS()");
					if ($on_sale_user_posts) {
						foreach($on_sale_user_posts as $spost) { $spost_picture = nws_get_item_thumb($spost->ID);
						$spost_username = $spost->user_login;
						$spost_price = get_post_meta($spost->ID, 'price', true);
						$spost_new_price = get_post_meta($spost->ID, 'new_price', true);
						$spost_item_your_price = get_post_meta($spost->ID, 'item_your_price', true);
						if (!$spost_new_price) { $spost_new_price = $spost_price; }

						$spost_new_price = sellers_currency_price($spost_new_price);
						$spost_item_your_price = sellers_currency_price($spost_item_your_price);
						?>
						<div class="product-item">
							<?php if ($spost_picture) { ?><a href="<?php echo get_post_thumb($spost_picture, 800, 800); ?>" class="pic-zoom"><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" class="thumbnail" alt="" /></a><?php } ?>
							<div class="description">
								<h4><a href="<?php echo $prof_summary_page_url.$spost->post_author; ?>"><?php echo $spost_username; ?></a></h4>
								<h4 class="item-title"><a href="<?php echo get_permalink($spost->ID); ?>"><?php echo $spost->post_title; ?></a></h4>
								<p><?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
								<div class="price-row">
									<span class="price"><strong>Seller Price:</strong> <?php echo format_price($spost_item_your_price, true); ?></span>
									<span class="price"><strong>The Luxury Closet Selling Price:</strong> <?php echo format_price($spost_new_price, true); ?></span>
								</div>
							</div>
						</div>
					<?php }
					if ($total_posts > $admin_items_per_page) { ?>
					<div class="pagenavi-holder">
						<?php sellers_admin_nav($total_posts, 'psoipg', 'tab-on-sale-items-ps'); ?>
					</div>
					<?php }
					} ?>
				</div>
			</div>
			<div class="clear"></div>
			<!-- Sold -->
			<div id="tab-sold-items-ps" class="tab-content">
				<div class="sellers-other-tabs">
					<?php
					$pg = $_GET['pssoipg']; if (!$pg) { $pg = 1; }
					$limit = ' LIMIT '.(($pg - 1) * $admin_items_per_page).', '.$admin_items_per_page;

					$sold_user_posts = $wpdb->get_results(sprintf("SELECT SQL_CALC_FOUND_ROWS p.*, pm.meta_value, u.user_login FROM %sposts p LEFT JOIN %spostmeta pm ON pm.post_id = p.ID AND pm.meta_key = 'ID_item' LEFT JOIN %spostmeta pm2 ON pm2.post_id = p.ID LEFT JOIN %susers u ON u.ID = p.post_author LEFT JOIN %sterm_relationships tr ON tr.object_id = p.ID WHERE p.post_type = 'post' AND p.post_status = 'publish' AND p.inventory = 0 AND pm2.meta_key = 'item_seller' AND pm2.meta_value = 'p' AND p.ID NOT IN (SELECT post_id FROM %spostmeta WHERE meta_key = '_prof_item_deleted') %s %s GROUP BY p.ID ORDER BY p.ID DESC %s", $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $sWhere, $pseller_where.$title_where, $limit));
					$total_posts = $wpdb->get_var("SELECT FOUND_ROWS()");
					if ($sold_user_posts) {
						foreach($sold_user_posts as $spost) { $spost_picture = nws_get_item_thumb($spost->ID);
						$spost_username = $spost->user_login;
						$spost_price = get_post_meta($spost->ID, 'price', true);
						$spost_new_price = get_post_meta($spost->ID, 'new_price', true);
						$spost_item_your_price = get_post_meta($spost->ID, 'item_your_price', true);
						if (!$spost_new_price) { $spost_new_price = $spost_price; }

						$spost_new_price = sellers_currency_price($spost_new_price);
						$spost_item_your_price = sellers_currency_price($spost_item_your_price);
						?>
						<div class="product-item">
							<?php if ($spost_picture) { ?><a href="<?php echo get_post_thumb($spost_picture, 800, 800); ?>" class="pic-zoom"><img src="<?php echo get_post_thumb($spost_picture, 91, 91, true); ?>" class="thumbnail" alt="" /></a><?php } ?>
							<div class="description">
								<h4><a href="<?php echo $prof_summary_page_url.$spost->post_author; ?>"><?php echo $spost_username; ?></a></h4>
								<h4 class="item-title"><a href="<?php echo get_permalink($spost->ID); ?>"><?php echo $spost->post_title; ?></a></h4>
								<p><?php echo get_post_meta($spost->ID, 'ID_item', true); ?></p>
								<div class="price-row">
									<span class="price"><strong>Seller Price:</strong> <?php echo format_price($spost_item_your_price, true); ?></span>
									<span class="price"><strong>The Luxury Closet Selling Price:</strong> <?php echo format_price($spost_new_price, true); ?></span>
								</div>
							</div>
						</div>
					<?php }
					if ($total_posts > $admin_items_per_page) { ?>
					<div class="pagenavi-holder">
						<?php sellers_admin_nav($total_posts, 'pssoipg', 'tab-sold-items-ps'); ?>
					</div>
					<?php }
					} ?>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<script src="<?php bloginfo('template_url'); ?>/js/jquery-datepicker.js"></script>
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/datepicker.css" />
    <script>jQuery(function() { jQuery( ".datepicker" ).datepicker({dateFormat: 'dd/mm/yy'}); });</script>
	<div id="sidebar">
		<div class="sort-box">
			<form class="search-form">
				<div class="section">
					<label>Search Username</label>
					<div class="form-row">
						<?php if (!strlen($search_username)) { $search_username = 'Username / Email'; } ?>
						<input type="text" name="search-username" class="text" value="<?php echo $search_username; ?>" onfocus="if(this.value=='Username / Email'){this.value='';}" onblur="if(this.value==''){this.value='Username / Email';}" style="height:26px;">
						<input type="submit" value="Search">
					</div>
				</div>
				<div class="section">
					<label>Search Quotation</label>
					<div class="form-row">
						<?php if (!strlen($search_quotation)) { $search_quotation = 'Quotation Number'; } ?>
						<input type="text" name="search-quotation" class="text" value="<?php echo $search_quotation; ?>" onfocus="if(this.value=='Quotation Number'){this.value='';}" onblur="if(this.value==''){this.value='Quotation Number';}" style="height:26px;">
						<input type="submit" value="Search">
					</div>
				</div>
				<div class="section">
					<label>Search By Title</label>
					<div class="form-row">
						<?php if (!strlen($search_title)) { $search_title = 'Post Title'; } ?>
						<input type="text" name="search-title" class="text" value="<?php echo $search_title; ?>" onfocus="if(this.value=='Post Title'){this.value='';}" onblur="if(this.value==''){this.value='Post Title';}" style="height:26px;">
						<input type="submit" value="Search">
					</div>
				</div>
				<div class="section">
					<label>Search By Option</label>
					<div class="select">
						<select name="search-option">
							<option value="">----- Select Option -----</option>
							<option value="today"<?php if ($search_option == 'today') { echo ' SELECTED'; } ?>>Today</option>
							<option value="days"<?php if ($search_option == 'days') { echo ' SELECTED'; } ?>>Last 7 days</option>
							<option value="month"<?php if ($search_option == 'month') { echo ' SELECTED'; } ?>>Last 1 month</option>
						</select>
					</div>
				</div>
				<div class="section">
					<label>Search By Date Range</label>
					<input type="text" name="search-date-start" class="date datepicker" value="<?php echo $search_date_start; ?>">
					<input type="text" name="search-date-end" class="date datepicker" value="<?php echo $search_date_end; ?>">
				</div>
				<input type="hidden" name="mtab" value="<?php echo $_GET['mtab']; ?>" id="search-tab"/>
				<input class="btn-search" type="submit" value="Search"/>
			</form>
		</div>
	</div>
	<div style="display:none;">
		<div class="quotation-item-view" id="quotation-item-view">
			<div class="quotation-item-data"></div>
			<div class="clear"></div>
			<div class="quotation-fields">
				<div class="ttl"><strong>Pricing Database</strong></div>
				<?php
				$tax_categories = get_terms('category', 'hide_empty=0');
				$tax_brands = get_terms('brand', 'hide_empty=0');
				$tax_selections = get_terms('selection', 'hide_empty=0');
				$tax_colours = get_terms('colour', 'hide_empty=0');
				?>
				<div class="pd-search">
					<form method="POST" id="pd-search-form">
					<input type="hidden" name="ppg" id="q_ppg">
					<ul>
						<li><?php wp_dropdown_categories('hide_empty=0&name=q_category&orderby=name&class=&hierarchical=1&show_option_none=-- Select Category --'); ?></li>
						<li><select name="q_brand" id="q_brand">
							<option value="">-- Select Brand --</option>
							<?php foreach($tax_brands as $tax_brand) { ?>
							<option value="<?php echo $tax_brand->term_id; ?>"><?php echo $tax_brand->name; ?></option>
							<?php } ?>
						</select></li>
						<!--<li><select name="q_selection" id="q_selection">
							<option value="">-- Select Condition --</option>
							<?php foreach($tax_selections as $tax_selection) { ?>
							<option value="<?php echo $tax_selection->term_id; ?>"><?php echo $tax_selection->name; ?></option>
							<?php } ?>
						</select></li>-->
						<li><input type="text" name="q_term" id="q_term"></li>
						<li><input type="submit" value="Search" class="pd-search-btn"></li>
					</ul>
					</form>
				</div>
				<div class="pdbdata">
					<div class="pd-data">
						<p>Please select search criteria.</p>
					</div>
					<div class="pd-data-load"><img src="<?php bloginfo('template_url'); ?>/images/loading2.gif"></div>
					<div class="pd-add-new">
						<div class="tit"><strong>Add New Pricing</strong></div>
						<form method="POST" class="pd-add-new-form">
							<ul class="left-side">
								<li><label>Category:</label>
								<?php wp_dropdown_categories('hide_empty=0&name=p_category&orderby=name&class=&hierarchical=1&show_option_none=-- Select Category --'); ?></li>
								<li><label>Brand:</label>
								<select name="p_brand" id="p_brand">
									<option value="">-- Select Brand --</option>
									<?php foreach($tax_brands as $tax_brand) { ?>
									<option value="<?php echo $tax_brand->term_id; ?>"><?php echo $tax_brand->name; ?></option>
									<?php } ?>
								</select></li>
								<li><label>Style Name:</label>
								<input type="text" name="p_style_name" id="p_style_name"></li>
								<li><label>Condition:</label>
								<select name="p_selection" id="p_selection">
									<option value="">-- Select Condition --</option>
									<?php foreach($tax_selections as $tax_selection) { ?>
									<option value="<?php echo $tax_selection->term_id; ?>"><?php echo $tax_selection->name; ?></option>
									<?php } ?>
								</select></li>
								<li><label>Colour:</label>
								<select name="p_colour" id="p_colour">
									<option value="">-- Select Colour --</option>
									<?php foreach($tax_colours as $tax_colour) { ?>
									<option value="<?php echo $tax_colour->term_id; ?>"><?php echo $tax_colour->name; ?></option>
									<?php } ?>
								</select></li>
								<li><label>Metal:</label>
								<select name="p_metal[]" id="p_metal" size="5" multiple style="height:64px;">
									<?php $mcatoptions = sellers_get_category_options('metal');
									foreach($mcatoptions as $mop) { ?>
									<option value="<?php echo $mop; ?>"><?php echo $mop; ?></option>
									<?php } ?>
								</select>
								</li>
								<li><label>Material:</label>
								<select name="p_material[]" id="p_material" size="5" multiple style="height:64px;">
									<?php $mcatoptions = sellers_get_category_options('material');
									foreach($mcatoptions as $mop) { ?>
									<option value="<?php echo $mop; ?>"><?php echo $mop; ?></option>
									<?php } ?>
								</select>
								</li>
								<li><label>Movement:</label>
								<select name="p_movement[]" id="p_movement" size="5" multiple style="height:64px;">
									<?php $mcatoptions = sellers_get_category_options('movement');
									foreach($mcatoptions as $mop) { ?>
									<option value="<?php echo $mop; ?>"><?php echo $mop; ?></option>
									<?php } ?>
								</select>
								</li>
							</ul>
							<ul class="center-side">
								<li><label>Original Price:</label>
								<input type="text" name="p_original_price" id="p_original_price"> <span><?php echo $_SESSION["currency-code"]; ?></span></li>
								<li><label>High Price:</label>
								<input type="text" name="p_high_price" id="p_high_price" onblur="pd_low_price()"> <span><?php echo $_SESSION["currency-code"]; ?></span></li>
								<li><label>Low Price:</label>
								<input type="text" name="p_low_price" id="p_low_price"> <span><?php echo $_SESSION["currency-code"]; ?></span></li>
								<?php $sellers_includes = sellers_get_includes();
								foreach($sellers_includes as $si_key => $si_val) { ?>
								<li><label><?php echo $si_val; ?> Inc:</label>
								<input type="text" name="p_includes_<?php echo $si_key; ?>" id="p_includes_<?php echo $si_key; ?>"> <span><?php echo $_SESSION["currency-code"]; ?></span></li>
								<?php } ?>
								<li style="margin-top:30px;"><div class="pd-add-new-load"><img src="<?php bloginfo('template_url'); ?>/images/loading-ajax.gif" class="pd-add-new-loading">&nbsp;</div><input type="button" value="Save" class="pd-add-new-save"></li>
							</ul>
							<ul class="right-side">
								<li><label>Notes:</label>
								<textarea name="p_notes" id="p_notes" style="width:175px;height:80px;border:1px solid #A5A5A5;"></textarea></li>
							</ul>
						</form>
					</div>
				</div>
				<div class="clear"></div>
				<div class="confirm">
					<form method="POST" id="quotation-item-view-form">
					<input type="hidden" name="SellersAction" value="tlc_set_qoutation">
					<input type="hidden" name="post_id" id="quotation-post-id">
					<input type="hidden" name="qtype" id="quotation-type">
					<input type="hidden" name="pricing_id" id="q-pricing-id">
					<input type="hidden" name="retpage" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
					<ul>
						<li class="txt"><a href="#add-new-item" onclick="tlc_show_add_pricing(); return false;">Add New Item</a>&nbsp;&nbsp;</li>
						<li class="txt">Quotation Price, <?php echo $_SESSION["currency-code"]; ?>:</li>
						<li><input type="text" name="q_price_high" id="q-price-high" value="High" onfocus="if(this.value=='High'){this.value='';}" onblur="if(this.value==''){this.value='High';}"></li>
						<li class="txt"><strong>-</strong></li>
						<li><input type="text" name="q_price_low" id="q-price-low" value="Low" onfocus="if(this.value=='Low'){this.value='';}" onblur="if(this.value==''){this.value='Low';}"></li>
						<li><input type="submit" value="Confirm" class="confirm-btn"></li>
						<li>&nbsp;</li>
						<li><input type="button" value="No Quote" class="no-quote-btn"></li>
					</ul>
					</form>
					<div style="float:right"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
} else {
	echo '<p>You are not allowed to view this page.</p>';
}

get_footer(); ?>