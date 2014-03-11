<?php
/*
Template Name: MY Alerts
*/
global $wpdb, $current_user, $OPTION;
set_referral();
get_header();
if (is_user_logged_in()) {
//print_r($current_user);  
get_currentuserinfo(); // grabs the user info and puts into vars
$user_ID = $current_user->ID;
// sidebar location?
$WPS_sidebar		= $OPTION['wps_sidebar_option'];
switch($WPS_sidebar)
{
	case 'alignRight':	$the_float_class 	= 'alignleft';	break;
	case 'alignLeft':
	default:	$the_float_class 	= 'alignright';	break;
}
if($OPTION['wps_front_sidebar_disable']) 
{
	$the_div_class 	= 'featured_wrap featured_wrap_alt';
	$the_div_id 	= 'main_col_alt';
} else 
{
	$the_div_class 	= 'featured_wrap ' .$the_float_class;
	$the_div_id 	= 'main_col';
}
if ($OPTION['wps_alerts_enable']) {
?>
<link rel="stylesheet" type="text/css" media="all" href="<?=get_option('siteurl')."/prelaunch/"?>css/styles.css" />
<div id="<?php echo $the_div_id;?>" class="<?php echo $the_div_class;?>">
	<div id="main_rg_container">
		<div class="my-alerts-container">
			<div class="text_inn">
				<div class="my-alerts">
					<?php the_content(); ?>
					<div class="my-alerts-sections">
						<div class="section my-alert-create">
							<h4 class="inline">1. Click here and select from popular categories</h4>
							<div class="my-alerts-create"><input type="button" value="CREATE A REQUEST" class="my-alerts-create-request"></div>
						</div>
						<?php if (strlen($OPTION['wps_alerts_itbags'])) {
							$itbags = array();
							$alert_itbags = unserialize($OPTION['wps_alerts_itbags']);
							foreach($alert_itbags as $alert_itbag) {
								$alert_itbag_arr = explode("-", $alert_itbag);
								$itbags[$alert_itbag_arr[0]][] = $alert_itbag_arr[1];
							}
							$user_it_bags = array();
							$user_it_bags_alerts = $wpdb->get_var(sprintf("SELECT value FROM %swps_user_alerts WHERE type = 2 AND user_id = %s", $wpdb->prefix, $current_user->ID));
							if (strlen($user_it_bags_alerts)) {
								$user_it_bags_alerts = str_replace(array('{','}'), '', $user_it_bags_alerts);
								$user_it_bags = explode(";", $user_it_bags_alerts);
							}
						?>
						<div class="clear"></div>
						<div class="section it-bags-alert">
							<h4>2. Select from the below list of our 'It Bags!'</h4>
							<div class="grey-box">
								<table>
									<?php foreach($itbags as $bid => $bitems) { $bterm = get_term($bid, 'brand'); ?>
									<tr>
										<td class="brand-name"><?php echo $bterm->name; ?></td>
										<td>
											<ul>
												<?php foreach($bitems as $bitem) {
												$bitem_title = get_the_title($bitem);
												$ibid = $bid.'-'.$bitem;
												$ibval = $bid.'|'.$bitem_title;
												$aclass = ''; if (in_array($ibval, $user_it_bags)) { $aclass = ' class="active"'; } ?>
												<li><a rel="<?php echo $ibid; ?>"<?php echo $aclass; ?>><?php echo $bitem_title; ?></a></li>
												<?php } ?>
											</ul>
										</td>
									</tr>
									<?php } ?>
								</table>
							</div>
						</div>
						<?php } ?>
						<?php $tax_brands = get_terms('brand');
						if ($tax_brands) { $cnmb = 1; $nmb_in_col = ceil(count($tax_brands) / 4);
							$user_top_brands = array();
							$user_top_brands_alerts = $wpdb->get_var(sprintf("SELECT value FROM %swps_user_alerts WHERE type = 3 AND user_id = %s", $wpdb->prefix, $current_user->ID));
							if (strlen($user_top_brands_alerts)) {
								$user_top_brands_alerts = str_replace(array('{','}'), '', $user_top_brands_alerts);
								$user_top_brands = explode(";", $user_top_brands_alerts);
							}
						?>
						<div class="clear"></div>
						<div class="section top-brands-alert">
							<h4>3. Select from the below top brands to get updated on all new additions.</h4>
							<div class="subtitle">"Top Brands" item requests</div>
							<div class="grey-box columns">
								<table>
									<tr>
										<td>
										<?php foreach ($tax_brands as $tax_brand) { $aclass = ''; if (in_array($tax_brand->term_id, $user_top_brands)) { $aclass = ' class="active"'; } ?>
											<a rel="<?php echo $tax_brand->term_id; ?>"<?php echo $aclass; ?>><?php echo $tax_brand->name; ?></a><br />
										<?php if ($cnmb == $nmb_in_col) { $cnmb = 0; ?>
										</td>
										<td>
										<?php } ?>
										<?php $cnmb++; } ?>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<?php } ?>
						<div class="clear"></div>
						<div class="section own-custom-alert">
							<h4>4. Make your own custom alert, just type in any name of brand or item you are looking for!</h4>
							<div class="form-save">
								<form method="POST" class="custom-alert-form">
									<input type="hidden" name="AlertsAction" value="create_alert">
									<input type="hidden" name="ca_type" value="4">
									<input type="text" name="ca_value" class="my-custom-alert">
									<input type="submit" value="SAVE" class="my-custom-alert-save">
								</form>
							</div>
						</div>
						<div class="clear"></div>
						<?php
						$mysearches_alerts = $wpdb->get_results(sprintf("SELECT * FROM %swps_user_alerts WHERE type IN (1,4) AND user_id = %s ORDER BY alert_id DESC", $wpdb->prefix, $current_user->ID));
						if ($mysearches_alerts) { ?>
						<div class="section my-searches-alert">
							<h4>My Searches</h4>
							<?php
							$sterms = array();
							$wpterms = $wpdb->get_results(sprintf("SELECT * FROM %sterms", $wpdb->prefix));
							foreach($wpterms as $wpterm) { $sterms[$wpterm->term_id] = array('slug' => $wpterm->slug, 'name' => $wpterm->name); }
							$search_results_page = get_permalink($OPTION['wps_search_results_page']);
							$search_page = get_bloginfo('siteurl');
							?>
							<div class="grey-box">
								<ul>
									<?php foreach($mysearches_alerts as $mysearches_alert) {
										$alert_id = $mysearches_alert->alert_id;
										$atype = $mysearches_alert->type;
										$value = $mysearches_alert->value;
										if ($atype == 1) {
											$ahref = '';
											$ahtml = '';
											$value = str_replace(array('{','}'), '', $value);
											$svalues = explode(";", $value);
											foreach($svalues as $sval) {
												if (strlen($ahref)) { $ahref .= '&'; $ahtml .= ' + '; }
												$svals = explode(":", $sval);
												if ($svals[0] == 'ct')      { $ahref .= 'filter-category[]='; }
												else if ($svals[0] == 'br') { $ahref .= 'filter-brand[]='; }
												else if ($svals[0] == 'cl') { $ahref .= 'filter-colour[]='; }
												else if ($svals[0] == 'pr') { $ahref .= 'filter-price[]='; }
												else if ($svals[0] == 'sl') { $ahref .= 'filter-selection[]='; }
												else if ($svals[0] == 'sz') { $ahref .= 'filter-size[]='; }
												else if ($svals[0] == 'rs') { $ahref .= 'filter-ring-size[]='; }
												else if ($svals[0] == 'cs') { $ahref .= 'filter-clothes-size[]='; }
												$ahref .= $sterms[$svals[1]]['slug'];
												$ahtml .= $sterms[$svals[1]]['name'];
											}
											$atag = '<a href="'.$search_results_page.'?'.$ahref.'">'.$ahtml.'</a>';
										} else { // search term
											$atag = '<a href="'.$search_page.'?s='.$value.'">&#171;'.$value.'&#187;</a>';
										}
									?>
									<li><p><?php echo $atag; ?><img src="<?php bloginfo('template_url'); ?>/images/ico-trash.png" rel="<?php echo $alert_id; ?>" title="Remove"></p></li>
									<?php } ?>
								</ul>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div style="display:none;">
	<div id="my-alerts-add-popup" class="my-alerts-add">
		<h2>Create an alert</h2>
		<form class="my-alerts-add-form" method="POST">
			<?php
			$alert_categories = get_option('wps_alerts_categories');
			$tax_categories = get_categories('hide_empty=0&include='.implode(',', $alert_categories));
			$tax_brands = get_terms('brand', 'hide_empty=0');
			$tax_colours = get_terms('colour', 'hide_empty=0');
			$tax_prices = get_terms('price', 'hide_empty=0');
			$excluded_selections = unserialize($OPTION['wps_excluded_selections']);
			$tax_selections = get_terms('selection', 'hide_empty=0&exclude='.implode(',', $excluded_selections));
			?>
			<ul>
				<li>
					<label>Category:</label>
					<select name="ca_category">
						<option value="">-- Select Category --</option>
						<?php
						if ($tax_categories) {
							foreach($tax_categories as $tax_category) {
								if ($tax_category->parent == 0) { ?>
									<option value="<?php echo $tax_category->term_id; ?>"><?php echo $tax_category->name; ?></option>
									<?php
									foreach($tax_categories as $tax_subcategory) {
										if ($tax_subcategory->parent == $tax_category->term_id) { ?>
											<option value="<?php echo $tax_subcategory->term_id; ?>">&nbsp;&nbsp;<?php echo $tax_subcategory->name; ?></option>
											<?php
											foreach($tax_categories as $tax_subsubcategory) {
												if ($tax_subsubcategory->parent == $tax_subcategory->term_id) { ?>
													<option value="<?php echo $tax_subsubcategory->term_id; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $tax_subsubcategory->name; ?></option>
												<?php
												}
											}
										}
									}
								}
							}
						} ?>
					</select>
				</li>
				<li>
					<label>Brand:</label>
					<select name="ca_brand">
						<option value="">-- Select Brand --</option>
						<?php foreach($tax_brands as $tax_brand) { ?>
						<option value="<?php echo $tax_brand->term_id; ?>"><?php echo $tax_brand->name; ?></option>
						<?php } ?>
					</select>
				</li>
				<li>
					<label>Colour:</label>
					<select name="ca_colour">
						<option value="">-- Select Colour --</option>
						<?php foreach($tax_colours as $tax_colour) { ?>
						<option value="<?php echo $tax_colour->term_id; ?>"><?php echo $tax_colour->name; ?></option>
						<?php } ?>
					</select>
				</li>
			</ul>
			<div class="submit-div"><input type="submit" value="Submit" class="save_but"></div>
			<input type="hidden" name="AlertsAction" value="create_alert">
			<input type="hidden" name="ca_type" value="1">
		</form>
	</div>
</div>
<?php
}
if ($OPTION['wps_front_sidebar_disable'] != TRUE) 
{
	switch($OPTION['wps_sidebar_option']){
		case 'alignRight':	$the_float_class 	= 'alignright';	break;
		case 'alignLeft':	$the_float_class 	= 'alignleft';	break;
	}
	$the_div_class 	= 'sidebar front-widgets frontPage_sidebar noprint '. $the_float_class; ?>
	<div class="<?php echo $the_div_class;?>">
		<?php if ( is_sidebar_active('frontpage_widget_area') ) : dynamic_sidebar('frontpage_widget_area'); endif; ?>
	</div><!-- frontPage_sidebar -->	
<?php
}  
} else {
	echo '<p>You are not allowed to view this page.</p>';
}
get_footer();
?>
