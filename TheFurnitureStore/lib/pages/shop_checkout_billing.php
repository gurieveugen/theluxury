<?php
$LANG['billing_address'] 			= __('Billing Address:','wpShop');
$LANG['shipping_address'] 			= __('Delivery Address:','wpShop');
$LANG['shipping_address_message'] 	= __('Fill In Only If Different From Billing Address','wpShop');
$LANG['lastname'] 			= __('Last Name','wpShop');
$LANG['firstname'] 			= __('First Name','wpShop');
$LANG['street_hsno'] 		= __('Address','wpShop');
$LANG['street']				= __('Street','wpShop');
$LANG['hsno']				= __('House No.','wpShop');
$LANG['strno']				= __('Street No.','wpShop');
$LANG['strnam']				= __('Street Name','wpShop');
$LANG['po']					= __('Post Office','wpShop');
$LANG['pb']					= __('Post Box','wpShop');
$LANG['pzone']				= __('Post Zone','wpShop');
$LANG['crossstr']			= __('Cross Streets','wpShop');
$LANG['colonyn']			= __('Colony name','wpShop');
$LANG['district']			= __('District','wpShop');		
$LANG['region']				= __('Region','wpShop');			
$LANG['island']				= __('Island','wpShop');		
$LANG['state_province'] 	= __('State/Province','wpShop'); 
$LANG['zip'] 				= __('Postcode','wpShop');
$LANG['town']				= __('City','wpShop');
$LANG['country']			= __('Country','wpShop');
$LANG['email']				= __('Email','wpShop');
$LANG['telephone']			= __('Telephone','wpShop');		
$LANG['terms']				= __('Terms &amp; Conditions','wpShop');
$LANG['next_step'] 			= __('Next Step','wpShop');		
$LANG['field_not_empty']	= __(' - Field cannot be Empty.','wpShop');	
$LANG['format_email']		= __('Your Email Address Format is not Correct.','wpShop');
$LANG['choose_billing_c']	= __('You must select a country for your Billing Address.','wpShop');
$LANG['choose_delivery_c']	= __('You must select a country for your Delivery Address','wpShop');
$LANG['terms_need_accepted']= __('Terms must be Accepted.','wpShop');
$LANG['wait_for_field'] 	= __('Error: You clicked on "Next" before all fields of the address form were fully loaded.','wpShop'); 
$LANG['refresh_and_wait'] 	= __('Please <a href="?orderNow=reload_form">click here to refresh the page </a> and wait for the address fields to appear after you have selected your country.','wpShop'); 

if ($_POST['order_step'] == 2) {
	$feedback = check_address_form();
}

// countries
$b_country = 'UNITED ARAB EMIRATES';
$d_country = 'UNITED ARAB EMIRATES';

$geoplugin = new geoPlugin();
$geoplugin->locate($_SERVER['REMOTE_ADDR']);
if (strlen($geoplugin->countryName)) {
	$ip_country = strtoupper(trim($geoplugin->countryName));
	if (strlen($ip_country)) {
		$b_country = $ip_country;
		$country_2 = $ip_country;
	}
}

if ($_POST['order_step'] == 2) {
	$f_name		= trim($_POST['f_name']);
	$l_name		= trim($_POST['l_name']);
	$b_country	= trim($_POST['country']);

	$f_name2	= trim($_POST['f_name']);
	$l_name2	= trim($_POST['l_name']);
	$d_country	= trim($_POST['country|2']);
} else if (isset($_SESSION['order_data'])) {
	$f_name		= $_SESSION['order_data']['fname'];
	$l_name		= $_SESSION['order_data']['lname'];
	$b_country	= $_SESSION['order_data']['country'];

	$f_name2	= $_SESSION['order_data']['shipp_fname'];
	$l_name2	= $_SESSION['order_data']['shipp_lname'];
	$d_country	= $_SESSION['order_data']['shipp_country'];
} else if (isset($_SESSION['layaway_order_data'])) {
	$f_name		= $_SESSION['layaway_order_data']['fname'];
	$l_name		= $_SESSION['layaway_order_data']['lname'];
	$b_country	= $_SESSION['layaway_order_data']['country'];

	$f_name2	= $_SESSION['layaway_order_data']['shipp_fname'];
	$l_name2	= $_SESSION['layaway_order_data']['shipp_lname'];
	$d_country	= $_SESSION['layaway_order_data']['shipp_country'];
}

//get cart composition
$cart_comp = cart_composition($_SESSION['cust_id']);
if(isset($_SESSION['user_logged']) && $_SESSION['user_logged'] === TRUE){ get_member_billing_addr(2); }
if(isset($_GET['dpchange']) && $_GET['dpchange'] == 1){ retrieve_address_data(); }

wps_shop_process_steps(3); ?>

<div class="payment-section">
	<h1 class="title">Delivery & Billing Address</h1>
	<div class="holder">
		<form class="step2 checkoutSteps clearfix" action="?orderNow=2" name="step2form" id="step2form" method="POST">
			<div id="editAddress" class="editCont clearfix">
				<div class="clearfix">
					<?php if (strlen($feedback['e_message'])) { ?>
						<p class="error"><?php echo $feedback['e_message']; ?></p>
					<?php } ?>
					<div class='column left'>
						<h4><?php echo $LANG['billing_address']; ?></h4>
						<label for="firstname"><?php echo $LANG['firstname']; ?>:</label>
						<input id="firstname" type="text" name="f_name" value="<?php echo $f_name; ?>" maxlength="255" />
						<label for="lastname"><?php echo $LANG['lastname']; ?>:</label>
						<input id="lastname" type="text" name="l_name" value="<?php echo $l_name; ?>" maxlength="255" />
						<?php
						$dc	= get_delivery_countries();
						$shop_country 	= get_countries(2, $OPTION['wps_shop_country']);
						$selected 		= ($b_country == $shop_country ? 'selected="selected"' : NULL);
						$selected2 		= ($d_country == $shop_country ? 'selected="selected"' : NULL);
						if ($dc['num'] > 0) {
							$countries = array();
							while($row = mysql_fetch_assoc($dc['res'])) {
								$countryName = $row['country'];
								if(WPLANG == 'de_DE') { $countryName = $row['de']; }
								elseif(WPLANG == 'fr_FR') { $countryName = $row['fr']; }
								$countries[] = $countryName;
							} ?>
							<label><?php echo $LANG['country']; ?>:</label>
							<select name="country" size="1" id="billingCountry" onChange="getBaddressForm('<?php echo is_in_subfolder(); ?>', '<?php echo get_protocol(); ?>');">
								<option value="bc"><?php echo __('-- Select a Country --','wpShop'); ?></option>
								<option value="<?php echo $shop_country; ?>" <?php echo $selected; ?>><?php echo $shop_country; ?></option>
								<?php foreach($countries  as $country) {
									$selected = ($b_country == $country ? 'selected="selected"' : NULL); ?>
									<option value="<?php echo $country; ?>" <?php echo $selected; ?>><?php echo $country; ?></option>
								<?php } ?>
							</select>
						<?php } ?>
						<input type="hidden" id="editOption" name="editOption" value="billingAddressCT" />
						<span id="billingAddress"></span>
						<span id="billingAddressCheck">
							<?php redisplay_address_form(); ?>
						</span>
						<?php
						$checked 	= (isset($_POST['delivery_address_yes']) && $_POST['delivery_address_yes'] == 'on' ? 'checked="yes"' : NULL );
						$visibility = (isset($_POST['delivery_address_yes']) && $_POST['delivery_address_yes'] == 'on' ? 'visible;' : 'hidden;' );
						if(isset($_GET['dpchange']) && $_GET['dpchange'] == 1) {
							$checked 	= (isset($_POST['d_addr']) && $_POST['d_addr'] == '1' ? 'checked="yes"' : NULL );
							$visibility = ( isset($_POST['d_addr']) && $_POST['d_addr'] == '1' ? 'visible;' : 'hidden;' );
						} ?>

						<div class="check-row">
							<input type="checkbox" id="display_switch" name="delivery_address_yes" <?php echo $checked; ?> onClick="display_delivery_address();" /> 
							<label for="delivery_address_yes">I have a different delivery address.</label>
							<label for="delivery_address_yes" onClick="copy_addr_form();" style="visibility: <?php echo $visibility; ?> text-decoration: underline;" id="delivery_address_yes">Copy data to Delivery Address</label>
						</div>
						<div class="terms_conditions clearfix">
							<p id="e_message">
								<strong class='mark' id='errorTermsaccepted'>Terms must be Accepted.</strong>
							</p>
							<div class="check-row">
								<input type="checkbox" name="terms_accepted" /> 
								<label>I accept the <a rel='div.overlay:eq(2)' href='?showTerms=1' target='_blank'><?php echo $LANG['terms']; ?></a> of The Luxury Closet<br />(Tradelux LLC).</label>
							</div>
						</div>
						<?php if($OPTION['wps_customNote_enable']) { ?>
							<div id="editNote" class="editCont custom_note">
								<h4><?php echo $OPTION['wps_customNote_label']; ?></h4>
								<?php if($OPTION['wps_customNote_remark'] != '') {
									echo "<p>".$OPTION['wps_customNote_remark']."</p>";
								} ?>
								<textarea name="custom_note" cols="50" rows="10"><?php echo $_POST['custom_note']; ?></textarea>
							</div>
						<?php } ?>
					</div>
					<?php
					// Display Delivery Address Form
					$visibility = (isset($_POST['delivery_address_yes']) && $_POST['delivery_address_yes'] == 'on'? 'visible' : 'hidden');
					if(isset($_GET['dpchange']) && $_GET['dpchange'] == 1){		
						$visibility = (isset($_POST['d_addr']) && $_POST['d_addr'] == '1' ? 'visible' : 'hidden');
					}
					?>
					<div id="delivery_address" class="column right" name="delivery_address" style="visibility: <?php echo $visibility; ?>;">
						<h4><?php echo $LANG['shipping_address']; ?></h4>
						<p><?php echo $LANG['shipping_address_message']; ?><br/><?php echo $feedback['e_message2']; ?></p>

						<label for="dfirstname"><?php echo $LANG['firstname']; ?>:</label>
						<input id="dfirstname" type="text" name="f_name|2" value="<?php echo $f_name_2; ?>" maxlength="255" />
						<label for="dlastname"><?php echo $LANG['lastname']; ?>:</label>
						<input id="dlastname" type="text" name="l_name|2" value="<?php echo $l_name_2; ?>" maxlength="255" />
						<?php
						if ($dc['num'] > 0) { ?>
							<label><?php echo $LANG['country']; ?>:</label>
							<select name="country|2" size="1" id="deliveryCountry" onChange="getDaddressForm('<?php echo is_in_subfolder(); ?>', '<?php echo get_protocol(); ?>');">
								<option value="dc"><?php echo __('-- Select a Country --','wpShop'); ?></option>
								<option value="<?php echo $shop_country; ?>" <?php echo $selected2; ?>><?php echo $shop_country; ?></option>
								<?php foreach($countries  as $country) {
									$selected = ($d_country == $country ? 'selected="selected"' : NULL); ?>
									<option value="<?php echo $country; ?>" <?php echo $selected; ?>><?php echo $country; ?></option>
								<?php } ?>
							</select>
							<input type="hidden" id="editOption" name="editOption" value="billingAddressCT" />
							<span id="deliveryAddress"></span>
							<span id="deliveryAddressCheck">
								<?php redisplay_address_form('shipping'); ?>
							</span>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="button-right">
				<input class="btn-orange" type="submit" name="step2" value="NEXT" />
			</div>
			<input type="hidden" name="order_step" value="2">
		</form>
	</div>
</div>
