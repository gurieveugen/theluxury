<?php
session_start();
auth(1);
/*

Template Name: Account Customer Area

*/

$WISHLIST 		= load_what_is_needed('wishlist');					
$customerArea	= get_page_by_title($OPTION['wps_customerAreaPg']);


// update wishlist item
if((isset($_POST['update_wl_item'])) && (!empty($_POST['update_wl_item']))){
	$WISHLIST->update_wishlist_item();
}

// remove item from wishlist 
if((isset($_POST['remove_wl_item'])) && (!empty($_POST['remove_wl_item']))){
	$WISHLIST->remove_from_wishlist();
}

// move item to cart
if((isset($_POST['transfer_wl_item'])) && (!empty($_POST['transfer_wl_item']))){
	$WISHLIST->wl_cart_transfer();
}

// update an item in Wishlist 
if((isset($_POST['refresh_wl_item']))&&($_POST['refresh_wl_item'] === 'yes')){
	$WISHLIST->update_wl_item();
}

get_header();

// database query - get customer data
$row = NWS_get_user_details();


// sidebar location
switch($OPTION['wps_sidebar_option']){
	case 'alignRight':
		$the_float_class 	= 'alignleft';
	break;
	case 'alignLeft':
		$the_float_class 	= 'alignright';
	break;
}

$url 			= get_permalink( $customerArea->ID);
$the_div_class 	= 'narrow '. $the_float_class;
?>
	<div <?php post_class('page_post '.$the_div_class); ?> id="post-<?php the_ID(); ?>">
		<?php 
		
		// sub-action switch 
		switch($_GET[action]){
		
			case 1: // edit login data ?>
				<h4><?php _e('You may edit your email address or your password or both! Simply fill in the forms that apply to you.','wpShop');?></h4>
		
				<?php
				if(isset($_GET['err'])){
				
					$error_text = NULL;
				
					switch($_GET['err']){
					
						case '1':
							$error_text = __('Your email format is invalid.','wpShop');	
						break;		
						case '2':
							$error_text = __('You have not repeated your email correctly! Perhaps typing slower may help.','wpShop');
						break;		
						
						case '3':
							$error_text = __('Your password is too short.','wpShop');
						break;
						
						case '4':
							$error_text = __('You have not repeated your password correctly! Perhaps typing slower may help.','wpShop');
						break;		
							
					}
				
					echo "<div class='login_err'>Error: $error_text</div>";
				}
				
				if(isset($_GET['success'])){		

					$success = __('Success!','wpShop');
					$updated = __('Updated','wpShop');
					
					if($_GET['success'] == 'password'){
						$newWhat = __('Password','wpShop');
					}				
					if($_GET['success'] == 'email'){
						$newWhat = __('Email','wpShop');
					}						
					echo "<div class='success'>$success ". $newWhat ." {$updated}.</div>";
				}			
				?>
												
				<form id="editEmail" class="clearfix" action="<?php echo get_bloginfo('template_url') . '/edit-user.php'; ?>" method="post">
					<fieldset class="clearfix">
						<legend><span><?php _e('Edit Email Address','wpShop');?></span></legend>
						
						<div class="alignleft">
							<label for="newEmail"><?php _e('New Email Address','wpShop');?></label>
							<input type="text" id="newEmail" name="newEmail" size="35" maxlength="40" tabindex="1" 
							value="<?php echo $_SESSION[email]; ?>"/>
						</div>
						
						<div class="alignright">
							<label for="rnewEmail"><?php _e(' Repeat New Email Address','wpShop');?></label>
							<input type="text" id="rnewEmail" name="rnewEmail" size="35" maxlength="40" tabindex="2" value=""/>
						</div>
						<input type="hidden" id="" name="editOption" value="email"/>
						<input class="formbutton" type="submit" alt="<?php _e('Send','wpShop');?>" value="<?php _e('Send','wpShop');?>" name="" title="<?php _e('Send','wpShop');?>" />
					</fieldset>
				</form>
				<hr/>
				<form id="editPassword" class="clearfix" action="<?php echo get_bloginfo('template_url') . '/edit-user.php'; ?>" method="post">
					<fieldset class="clearfix">
						<legend><span><?php _e('Edit Password','wpShop');?></span></legend>
						
						<div class="alignleft">
							<label for="newPassword"><?php _e('New Password','wpShop');?></label>
							<input id="newPassword" type="password" size="35" maxlength="8" value="" tabindex="3" name="newPassword"/>
						</div>	
						
						<div class="alignright">
							<label for="rnewPassword"><?php _e('Repeat New Password','wpShop');?></label>
							<input id="rnewPassword" type="password" size="35" maxlength="8" value="" tabindex="4" name="rnewPassword"/>
						</div>	
						<input type="hidden" id="" name="editOption" value="password"/>
						<input class="formbutton" type="submit" alt="<?php _e('Send','wpShop');?>" value="<?php _e('Send','wpShop');?>" name="" title="<?php _e('Send','wpShop');?>" />
					</fieldset>
				</form>
			<?php 	
			break;
			
			// Customer Info
			case 2: 
				
				if(($_GET['updated'] == '1') && ($_GET['complete'] != '0')){		

					$success = __('Your information has been succesfully updated.','wpShop');
				
					echo "<div class='success'>$success</div>";
				}
				
				echo "<div class='c_box c_box2 c_box_first top_row'>";	
					echo "<h4>".__('Customer: ', 'wpShop')."$row[uname]</h4>"; ?>		
					
					<form id="customerInfo" action="<?php echo get_bloginfo('template_url') . '/edit-user.php'; ?>" method="post">
						<label for="memberName"><?php _e('Firstname:','wpShop');?></label>
						<input id="memberName" type='text' name='memberName' value='<?php echo $row[fname]; ?>' maxlength='255' />
						<label for="memberLastName"><?php _e('Lastname:','wpShop');?></label>
						<input id="memberLastName" type='text' name='memberLastName' value='<?php echo $row[lname]; ?>' maxlength='255' />
						<label for="memberEmail"><?php _e('Email:','wpShop');?></label>
						<input id="memberEmail" type='text' name='name' value='<?php echo $row[email]; ?>' readonly="readonly" maxlength='255' />
						<input type="hidden" id="" name="editOption" value="otherInfo"/>							
						<input class="formbutton" type='submit' name='submit' value='<?php _e('Update','wpShop');?>' />
					</form>
					
				</div><!-- c_box -->	
				<?php if ($OPTION['wps_shoppingCartEngine_yes']) { ?>
				<div class="c_box c_box2 top_row">
					<h4><?php _e('Billing-Address:','wpShop'); ?></h4>
					
					<?php if($_GET['complete'] == '0'){
						echo "<p class='error'>Your billing address is not yet complete.</p>";
					} ?>
					
					<form id="billingAddressMain" action="<?php echo get_bloginfo('template_url') . '/edit-user.php'; ?>" method="post">	
	
						<label for="memberName"><?php _e('Firstname:','wpShop');?></label>
						<input id="memberName" type='text' name='memberName' value='<?php echo $row[fname]; ?>' maxlength='255' />
						<label for="memberLastName"><?php _e('Lastname:','wpShop');?></label>
						<input id="memberLastName" type='text' name='memberLastName' value='<?php echo $row[lname]; ?>' maxlength='255' />	


						<?php
						echo "<br/><span id='billingAddress'>";

                  echo "</span>";	// provided by AJAX
						echo "<br/><span id='billingAddressCheck'>";
							redisplay_address_form();
						echo "</span>";

						$LANG[street_hsno] 			= __('Address','wpShop');
						$LANG[street]				= __('Street','wpShop');
						$LANG[hsno]					= __('House No.','wpShop');
						$LANG[strno]				= __('Street No.','wpShop');
						$LANG[strnam]				= __('Street Name','wpShop');
						$LANG[po]					= __('Post Office','wpShop');
						$LANG[pb]					= __('Post Box','wpShop');
						$LANG[pzone]				= __('Postal Zone','wpShop');
						$LANG[crossstr]				= __('Cross Streets','wpShop');
						$LANG[colonyn]				= __('Colony name','wpShop');
						$LANG[district]				= __('District','wpShop');		
						$LANG[region]				= __('Region','wpShop');			
						$LANG[island]				= __('Island','wpShop');		
						$LANG[state_province] 		= __('State/Province','wpShop'); 
						$LANG[zip] 					= __('Postcode','wpShop');
						$LANG[town]					= __('City','wpShop');
						$LANG[country]				= __('Country','wpShop');


					echo "<span id='savedAddress'>";						
					if(!empty($_SESSION['memberBillingCountry'])){
											
						$ad			= get_address_format($_SESSION['memberBillingCountry']);
						$billing	= get_member_billing_addr();

						// search for items in string - produce input fields accordingly 
						if (strpos($ad,'street') !== false) {
								echo "<label for='street_hsno'>$LANG[street_hsno]:</label><input id='street_hsno' type='text' name='street' value='$billing[street]' maxlength='255' /><br/>";
						}
						if (strpos($ad,'hsno') !== false) {
								echo "<label for='hsno'>$LANG[hsno]:</label><input id='hsno' type='text' name='hsno' value='$billing[hsno]' maxlength='255' /><br/>";
						}					
						if (strpos($ad,'strnam') !== false) {
								echo "<label for='strnam'>$LANG[strnam]:</label><input id='strnam' type='text' name='strnam' value='$billing[strnam]' maxlength='255' /><br/>";
						}	
						if (strpos($ad,'strno') !== false) {
								echo "<label for='strno'>$LANG[strno]:</label><input id='strno' type='text' name='strno' value='$billing[strno]' maxlength='255' /><br/>";
						}				
						if (strpos($ad,'po') !== false) {
								echo "<label for='po'>$LANG[po]:</label><input id='po' type='text' name='po' value='$billing[po]' maxlength='255' /><br/>";
						}				
						if (strpos($ad,'pb') !== false) {
								echo "<label for='pb'>$LANG[pb]:</label><input id='pb' type='text' name='pb' value='$billing[pb]' maxlength='255' /><br/>";
						}				
						if (strpos($ad,'pzone') !== false) {
								echo "<label for='pzone'>$LANG[pzone]:</label><input id='pzone' type='text' name='pzone' value='$billing[pzone]' maxlength='255' /><br/>";
						}				
						if (strpos($ad,'crossstr') !== false) {
								echo "<label for='crossstr'>$LANG[crossstr]:</label><input id='crossstr' type='text' name='crossstr' value='$billing[crossstr]' maxlength='255' /><br/>";
						}				         
						if (strpos($ad,'colonyn') !== false) {
								echo "<label for='colonyn'>$LANG[colonyn]:</label><input id='colonyn' type='text' name='colonyn' value='$billing[colonyn]' maxlength='255' /><br/>";
						}                    
						if (strpos($ad,'district') !== false) {
								echo "<label for='district'>$LANG[district]:</label><input id='district' type='text' name='district' value='$billing[district]' maxlength='255' /><br/>";
						}				
						if (strpos($ad,'region') !== false) {
								echo "<label for='region'>$LANG[region]:</label><input id='region' type='text' name='region' value='$billing[region]' maxlength='255' /><br/>";
						}                          
						if (strpos($ad,'state') !== false) {

							echo "<label for='state'>$LANG[state_province]:</label>"; 	
							$ct = $OPTION['wps_shop_country'];
							$ct = get_countries(3,$d_country);
							$dc	= get_delivery_countries();
											
							if((($ct == 'US') || ($ct == 'AT') || ($ct == 'AU') || ($ct == 'CA') || ($ct == 'DE') || ($ct == 'GB') || ($ct == 'MX') ||
							($ct == 'CH') || ($ct == 'IN') || ($ct == 'CY')) && ($dc[num] <1)){
							
								echo "<select id='statelist' name='state' size='1' title='State selection'>";
								echo province_me($ct,$billing[state]);						
							}			
							else {
								echo "<input  id='state' type='text' name='state' value='$billing[state]' maxlength='255' /><br>";
							}								
						}
						if (strpos($ad,'zip') !== false) {
								echo "<label for='zip'>$LANG[zip]:</label><input  id='zip' type='text' name='zip' value='$billing[zip]' maxlength='255' /><br/>";
						}	
						if (strpos($ad,'place') !== false) {
								echo "<label for='town'>$LANG[town]:</label><input  id='town' type='text' name='town' value='$billing[town]' maxlength='255' /><br/>";
						} 
					
					}
					echo "</span>";		
						?>
						<label for="memberBillingCountry"><?php _e('Country:','wpShop');?></label>
						
						<?php
							$dc	= get_delivery_countries();
						
							echo "<select name='country' size='1' id='billingCountry' 
							onChange=\"getBaddressForm("."'".is_in_subfolder()."','".get_protocol()."');\">";
							echo "<option value='bc'>".__('-- Select a Country --','wpShop')."</option>";	
						?>

					
						<?php
							$shop_country 	= get_countries(2,$OPTION['wps_shop_country']);
							#$selected 		= ($_POST['country'] == $shop_country ? 'selected="selected"' : NULL);
							$selected = ($shop_country == $_SESSION['memberBillingCountry'] ? "selected='selected'" : NULL);
							echo "<option value='$shop_country' $selected >$shop_country</option>";
							
							if($dc['num'] > 0){	
							
								while($row = mysql_fetch_assoc($dc[res])){
								
									if(WPLANG == 'de_DE'){
										$countryName = $row[de];
									}
									elseif(WPLANG == 'fr_FR'){
										$countryName = $row[fr];
									}
									else {
										$countryName = $row[country];
									}
								
									#$selected	= ($_POST['country'] == $countryName ? 'selected="selected"' : NULL);
									$selected = ($countryName == $_SESSION['memberBillingCountry'] ? "selected='selected'" : NULL);
									echo "<option value='$countryName' $selected >$countryName</option>";						
								}
							}
						echo "</select>";
						?>
						<input type="hidden" id="editOption" name="editOption" value="billingAddressFE"/>							
						<input class="formbutton" type='submit' name='submit' value='<?php _e('Update','wpShop');?>' />
					</form>
				</div><!-- c_box -->		
			
				<?php }
				
				if(strlen($OPTION['wps_extrainfo_header'])>0){
					$table 	= is_dbtable_there('feusers_meta');	
					$uid  	= (int) $_SESSION['uid'];				
				?>
					<div class="clear"></div>
					<div class="c_box_single">
						<?php 
						echo "<h4>".$OPTION['wps_extrainfo_header']."</h4>";
						
						if(strlen($OPTION['wps_extrainfo_instruct'])>0){
							echo "<p>".$OPTION['wps_extrainfo_instruct']."</p>";
						}
						
						$theFields 	= trim(nl2br($OPTION['wps_extra_formfields']));
						$formArr 	= explode("<br />",$theFields);
						
						
						foreach($formArr as $k => $v){
							if(strlen($v)=='') {unset($formArr[$k]);}
						}
						
						$fieldsNum = count($formArr);
						
						$fieldCol = $OPTION['wps_extra_formfieldsCol'];
						// 1 or 2 columns?
						switch($fieldCol){
							case '1':
								$counter = 1; 
							break;
							
							case '2':
								$counter = 2; 
							break;
						}
						
						echo "<form class='clearfix' action='" . get_bloginfo('template_url') . "/edit-user.php' method='post'>";		
								
								foreach($formArr as $k => $v){
								
									$v 		= trim($v);
									$v_name = str_replace(" ", "_", $v); 
									
									//get already existing data from DB 
									$qStr	= "SELECT meta_value FROM $table WHERE uid = $uid AND meta_key = '$v_name' LIMIT 0,1";
									$res 	= mysql_query($qStr);
									$row 	= mysql_fetch_assoc($res);
									
									$v_alt = sanitize_title_with_dashes($v);
									
									if(!empty($v)){
										// 1 or 2 columns?
										switch($fieldCol){
											case '1':
												$the_class 		= alternating_css_class($counter,1,' c_box_first');
												$the_div_class 	= 'c_box c_box1 '. $the_class;
											break;
											
											case '2':
												$the_class 		= alternating_css_class($counter,2,' c_box_first');
												$the_div_class 	= 'c_box c_box2 '. $the_class;
											break;
										}
										
										echo "<div class='$the_div_class'>";
											echo "<label for='$v_alt'>$v</label><input id='$v_alt' type='text' name='$v_name' value='$row[meta_value]' maxlength='255' />";
											//echo "<label for='$v'>$v</label><input type='text' name='$v' value='$row[meta_value]' maxlength='255' />";
										echo "</div>";
									}
									
									$counter++;
								}
								
						echo "
							<div class='clear'></div>
							<input type='hidden' id='editOption' name='editOption' value='extraInfos'/>							
							<input class='formbutton' type='submit' name='submit' value='". __('Update','wpShop')."' />
						
						</form>";	?>
					</div>
				
				<?php }
			
			break;
			
			case 3: 	// WISH-LIST
				
				if($OPTION['wps_wishlistIntroText'] != '') { ?>
					<p><?php echo $OPTION['wps_wishlistIntroText']; ?></p>
				<?php } 
				
					if($_GET['transfer'] == 'OK'){
						$basket_url = get_option('home') .'?showCart=1&cPage='. current_page(3);
						if($OPTION['wps_shop_mode'] =='Inquiry email mode'){ ?>
							<div class='success'><?php printf(__ ('Your item has been successfully added to your %s!','wpShop'), $OPTION['wps_pgNavi_inquireOption'])?><a href="<?php echo $basket_url;?>"><?php printf(__ (' View %s','wpShop'), $OPTION['wps_pgNavi_inquireOption'])?></a></div>
						<?php } elseif ($OPTION['wps_shop_mode']=='Normal shop mode'){ ?>
							<div class='success'><?php printf(__ ('Your item has been successfully added to your %s!','wpShop'), $OPTION['wps_pgNavi_cartOption'])?><a href="<?php echo $basket_url;?>"><?php printf(__ (' View %s','wpShop'), $OPTION['wps_pgNavi_cartOption'])?></a></div>
						<?php } else {}
					}
					
					if($_GET['transfer'] == 'NOK'){
						$basket_url = get_option('home') .'?showCart=1&cPage='. current_page(3);
						if($OPTION['wps_shop_mode'] =='Inquiry email mode'){ ?>
							<div class='success'><?php printf(__ ('Sorry! Your item is out of stock so it could not be added to your %s!','wpShop'), $OPTION['wps_pgNavi_inquireOption'])?></div>
						<?php } elseif ($OPTION['wps_shop_mode']=='Normal shop mode'){ ?>
							<div class='success'><?php printf(__ ('Sorry! Your item is out of stock so it could not be added to your %s!','wpShop'), $OPTION['wps_pgNavi_cartOption'])?></div>
						<?php } else {}					
					}								
					if($_GET['updated'] == '1'){		
						echo "<div class='success'>".__('Your item has been succesfully updated.','wpShop')."</div>";
					}
				?>
				
			
					<table class='order_table wishList_table' border='0'>
						<thead>
							<tr>
								<th>&nbsp;</th><th><?php _e('Item','wpShop');?></th><th><?php if(strlen(get_custom_field('price', FALSE))>0){ _e('Item Price','wpShop'); } else { _e('Category','wpShop'); } ?></th><th colspan='2'><?php _e('Actions','wpShop'); ?></th>
							</tr>
						</thead>
						
						<?php
						$LANG[your_shopping_cart]	= $pgNavi_cartOption;
						$LANG[wishlist_empty]		= str_replace("%w",$OPTION['wps_wishListLink_option'], __('Your %w is empty!','wpShop'));
						$LANG[continue_shopping] 	= __('Continue Shopping','wpShop');	
						$LANG[start_shopping] 		= __('Start Shopping','wpShop');			
						$LANG[order_now] 			= __('Order Now','wpShop');
						$LANG[article] 				= __('Item','wpShop');
						$LANG[amount] 				= __('Quantity','wpShop');
						$LANG[unit_price] 			= __('Item Price','wpShop');
						$LANG[total] 				= __('Item Total','wpShop');
						$LANG[remove] 				= __('Remove','wpShop');			
						$LANG[subtotal_cart]		= __('Subtotal:','wpShop');
						$LANG[incl]					= __('incl.','wpShop');
						$LANG[excl]					= __('excl.','wpShop');
						$LANG[shipping_costs] 		= __('Shipping &amp; Handling Costs','wpShop');
						$LANG[update] 				= __('Update','wpShop');	
						$LANG[shipping_fee_1]		= __('Shipping Fee','wpShop');	
			
						$WISHL 						= $WISHLIST->show_wishlist();		
					
						
						if($WISHL[status] == 'filled'){
							$tax_brands = get_terms('brand', 'hide_empty=0');
							$tax_styles = get_terms('style', 'hide_empty=0');
							$tax_colours = get_terms('colour', 'hide_empty=0');
												
							foreach($WISHL['content'] as $v){
								$details 	= explode("|",$v);
								
								//we need to check if a wishlist item a) is still in the active product catalog  b) price the same
								$feedback 	= $WISHLIST->wl_product_check($details);

								$art_no 		= $details[5];
								
								$personalize 	= (!empty($details[10]) ? "<br/>".$details[10] : NULL);
									
								$attributes	= NULL;
								$attributes = display_attributes($details[7]);	

								#$is_digital 	= is_it_digital('CART',$details[0]);			
								#$digital_label	= ($is_digital === TRUE ? '<span class="cart_digital_product">Digital product</span>' : NULL);
								
								$img_src 	= $details[6];
								$img_size 	= $OPTION['wps_ProdRelated_img_size'];
								$des_src 	= $OPTION['upload_path'].'/cache';							
								$img_file 	= mkthumb($img_src,$des_src,$img_size,'width');    
								$imgURL 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;	
								$thumb_img 	= $imgURL;
									
								$prodID 	= $details[8];
								
								//get category
								$category = get_the_category($prodID); 
								
								//get permalink
								$permalink 	= get_permalink($prodID);
								$buy_now    = $details[9];
								$frm_action = get_option('home').'/'.$customerArea->post_name.'?action=3';
								
								if($feedback['product_changed'] == 2 && $OPTION['wps_shoppingCartEngine_yes']){
									echo "<tr class='note'>";
								}else{
									echo "<tr>";
								} ?>
									<td><a href="<?php echo $permalink; ?>" title="<?php echo $details[2]; ?>"><img src="<?php echo $thumb_img; ?>" alt="<?php echo $details[2]; ?>" /></a></td>
									<form action="<?php echo $frm_action; ?>" method="post">
									<td>
										<dl>
											<dt><strong><a href="<?php echo $permalink; ?>"><?php echo $details[2]; ?></a></strong></dt>
											<?php if ($tax_brands) { ?>
												<dd><label>Brand:</label> <select name="wl_item_brand" style="width:200px;"><?php foreach($tax_brands as $tax_brand) { ?><option value="<?php echo $tax_brand->term_id; ?>"<?php if ($details[11] == $tax_brand->term_id) { echo ' SELECTED'; } ?>><?php echo $tax_brand->name; ?></option><?php } ?></select></dd>
											<?php } ?>
											<?php if ($tax_styles) { ?>
												<dd><label>Style:</label> <select name="wl_item_style" style="width:200px;"><?php foreach($tax_styles as $tax_style) { ?><option value="<?php echo $tax_style->term_id; ?>"<?php if ($details[12] == $tax_style->term_id) { echo ' SELECTED'; } ?>><?php echo $tax_style->name; ?></option><?php } ?></select></dd>
											<?php } ?>
											<?php if ($tax_colours) { ?>
												<dd><label>Colour:</label> <select name="wl_item_colour" style="width:200px;"><?php foreach($tax_colours as $tax_colour) { ?><option value="<?php echo $tax_colour->term_id; ?>"<?php if ($details[13] == $tax_colour->term_id) { echo ' SELECTED'; } ?>><?php echo $tax_colour->name; ?></option><?php } ?></select></dd>
											<?php } ?>
										</dl>
									</td>					
									<td><?php echo $category[0]->name; ?></td>
									<td>
											<input type="hidden" name="update_wl_item" value="<?php echo $details[0]; ?>" />
											<input class="show_tooltip" type="image" src="<?php bloginfo('stylesheet_directory'); ?>/images/save_icon.png" title="<?php echo __('Save Item params.', 'wpShop'); ?>" />
									</td>
									</form>
									<td>
										<form action="<?php echo $frm_action; ?>" method="post">
											<input type="hidden" name="remove_wl_item" value="<?php echo $details[0]; ?>" />
											<input class="show_tooltip" type="image" src="<?php bloginfo('stylesheet_directory'); ?>/images/remove.png" title="<?php printf(__('Delete Item from %s.', 'wpShop'), $OPTION['wps_wishListLink_option']); ?>" />
										</form>
									</td>	
								</tr>					
							<?php }
							
						} else {
							echo "
								<tr>
									<td></td>";
									if ($OPTION['wps_shoppingCartEngine_yes'] || $buy_now!='') {
										echo "<td colspan='3'>";
									} else {
										echo "<td colspan='2'>";
									}
									echo "<h3 class='error cart_empty'>$LANG[wishlist_empty]</h3></td>
									<td><a class='cartActionBtn start_shop' href='"; echo get_option('home'); echo "'>$LANG[start_shopping]</a></td>
								</tr>
							";
						}
						?>						
						
						<tr class='sums'><?php if ($OPTION['wps_shoppingCartEngine_yes'] || $buy_now!='') { ?><td colspan='5'><?php } else { ?><td colspan='4'><?php } ?></td></tr>
					</table>
					
					<?php if($WISHL[status] == 'filled'){ ?>
						<a class="cartActionBtn cont_shop wishList_cont_shop" 
						href='<?php echo $_SESSION['wishlistRTUrl']; ?>'><?php _e('Continue Shopping','wpShop');?></a>
					<?php }
			break;
			
			// for future use
			case 4:
			break;
			
			case 5:
			break;
			
			default:
				echo "<h3>".__('Welcome back, ', 'wpShop')."$row[uname]</h3>";				
					if($OPTION['wp_logoutLink_option']){
						if($OPTION['wps_useGet4logout'] == 'yes'){
							$logoutUrl = get_real_base_url();
							echo "<p><a href='".get_bloginfo( 'template_directory' )."/logout.php?go2page=$logoutUrl'>";
							echo __('Logout', 'wpShop')."</a></p>";  
						}
						else {
							echo "<p><a href='".get_bloginfo( 'template_directory' )."/logout.php'>".__('Logout', 'wpShop')."</a></p>"; 
						}
					}
				the_post(); 
				the_content();
			break;
		} ?>
		
	</div><!-- page_post -->
	
	<?php include (TEMPLATEPATH . '/widget_ready_areas.php');	
get_footer(); 
?>