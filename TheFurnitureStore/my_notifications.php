<?php
/*
Template Name: My Notifications
*/
global $wpdb, $current_user, $OPTION;
get_header();
if (is_user_logged_in()) {
get_currentuserinfo(); // grabs the user info and puts into vars
$user_ID = $current_user->ID;
?>

<div class="notifications-wrap">
    <?php the_content(); ?><br/>
	<p class="n-item"><span class="num">1</span> <a href="#" class="btn-yellow">CREATE CUSTOM NOTIFICATION</a></p>
	<p class="n-item"><span class="num">2</span> Choose from our most popular items</p>
    <?php if (strlen($OPTION['wps_alerts_itbags'])) {
		$itbags = array();
		$alert_itbags = unserialize($OPTION['wps_alerts_itbags']);
		foreach($alert_itbags as $bid => $itbag_val) {
			if (strlen($itbag_val)) {
				$itbag_values = preg_split('/'.chr(10).'/', $itbag_val);
				foreach($itbag_values as $val) {
					$val = str_replace(chr(13), '', $val);
					if (strlen($val)) {
						$itbags[$bid][] = $val;
					}
				}
			}
		}
		$itbags_total = count($itbags);
		$user_it_bags = array();
		$user_it_bags_alerts = $wpdb->get_var(sprintf("SELECT value FROM %swps_user_alerts WHERE type = 2 AND user_id = %s", $wpdb->prefix, $current_user->ID));
		if (strlen($user_it_bags_alerts)) {
			$user_it_bags_alerts = str_replace(array('{','}'), '', $user_it_bags_alerts);
			$user_it_bags = explode(";", $user_it_bags_alerts);
		}
	?>
    <div class="n-box">
        <?php $bnmb = 1; ?>
        <?php foreach($itbags as $bid => $bitems) { $bterm = get_term($bid, 'brand'); ?>
    	<div class="column">
    		<h4><?php echo strtoupper($bterm->name); ?></h4>    		
    		<ul>
    			<?php foreach($bitems as $bitem) {
				$bitem_val = $bid.'-'.$bitem;
    			$aclass = ''; if (in_array($bitem_val, $user_it_bags)) { $aclass = ' class="active"'; } ?>
    			<li><a rel="<?php echo $bitem_val; ?>"<?php echo $aclass; ?>><?php echo $bitem; ?></a></li>
    			<?php } ?>
            </ul>    		
    	</div>
    	<?php if ($bnmb == 6 && $itbags_total > 1) { $bnmb = 0; ?>
            </div>
            <div class="n-box">
        <?php } ?>
		<?php $bnmb++; $itbags_total--; } ?>
    </div>
    <?php } ?>
    <?php
    $tax_brands = get_terms('brand');
	if ($tax_brands) { $cnmb = 1; $nmb_in_col = ceil(count($tax_brands) / 5);
		$user_top_brands = array();
		$user_top_brands_alerts = $wpdb->get_var(sprintf("SELECT value FROM %swps_user_alerts WHERE type = 3 AND user_id = %s", $wpdb->prefix, $current_user->ID));
		if (strlen($user_top_brands_alerts)) {
			$user_top_brands_alerts = str_replace(array('{','}'), '', $user_top_brands_alerts);
			$user_top_brands = explode(";", $user_top_brands_alerts);
		}
	?>
	<p class="n-item"><span class="num">3</span> Follow Your Favourite Brands</p>
	<div class="n-box">
		<ul class="col-5">
			<div class="column">
                <?php foreach ($tax_brands as $tax_brand) { $aclass = ''; if (in_array($tax_brand->term_id, $user_top_brands)) { $aclass = ' class="active"'; } ?>
					<li><a rel="<?php echo $tax_brand->term_id; ?>"<?php echo $aclass; ?>><?php echo $tax_brand->name; ?></a></li>
				<?php if ($cnmb == $nmb_in_col) { $cnmb = 0; ?>
				</div>
				<div class="column">
				<?php } ?>
				<?php $cnmb++; } ?>
            </div>            
		</ul>
	</div>
    <?php } ?>
	<p class="n-item" style="margin-bottom:10px;"><span class="num">4</span> Or tell us what you are looking for:</p>
	<div class="look-for-exmpl" style="font-size:14px;">Eg: Louis Vuitton Speedy<br />Rolex Datejust watch</div>
	<form method="POST" class="custom-alert-form notifiaction-save">
        <input type="hidden" name="AlertsAction" value="create_alert">
        <input type="hidden" name="ca_type" value="4">
		<input type="text" name="ca_value" class="my-custom-alert">
		<input type="submit" value="SAVE">
	</form>
    <?php
	$mysearches_alerts = $wpdb->get_results(sprintf("SELECT * FROM %swps_user_alerts WHERE type IN (1,4) AND user_id = %s ORDER BY alert_id DESC", $wpdb->prefix, $current_user->ID));
	if ($mysearches_alerts) { ?>
    <p><strong>My Custom Notifications</strong></p>	
	<?php
	$sterms = array();
	$wpterms = $wpdb->get_results(sprintf("SELECT * FROM %sterms", $wpdb->prefix));
	foreach($wpterms as $wpterm) { $sterms[$wpterm->term_id] = array('slug' => $wpterm->slug, 'name' => $wpterm->name); }
	$search_results_page = get_permalink($OPTION['wps_search_results_page']);
	$search_page = get_bloginfo('siteurl');
	?>	
	<ul class="notifications-list">
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
					$ahref .= $sterms[$svals[1]]['slug'];
					$ahtml .= $sterms[$svals[1]]['name'];
				}
				$atag = '<a href="'.$search_results_page.'?'.$ahref.'">'.$ahtml.'</a>';
			} else { // search term
				$atag = '<a href="'.$search_page.'?s='.$value.'">&#171;'.$value.'&#187;</a>';
			}
		?>        
		<li><?php echo $atag; ?><img src="<?php bloginfo('template_url'); ?>/images/ico-remove-n.png" rel="<?php echo $alert_id; ?>" title="Remove"></li>
		<?php } ?>
	</ul>		
	<?php } ?>	
</div>
<div style="display:none;">
	<div id="my-alerts-add-popup" class="my-alerts-add">
		<h2>Create an alert</h2>
		<form class="my-alerts-add-form" method="POST">
			<?php
			$alerts_excluded_categories = get_option('wps_alerts_excluded_categories');
			$tax_categories = get_categories('hide_empty=0&exclude='.implode(',', $alerts_excluded_categories));
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
} else {
	echo '<p>You are not allowed to view this page.</p>';
}
get_footer(); ?>