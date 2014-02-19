<?php
include_once(reset(pathinfo(__FILE__)).'/functions.php');
// protection from direct access when not activated 
$pops = $OPTION['wps_payment_options'];
if(strpos($pops,'paypal_pro') === FALSE){
		$url = get_option('home');
		echo "<meta http-equiv='refresh' content='0; URL=$url' />";
		exit(NULL);
}
	
echo order_step_table(4); // additional step needed

// error has occurred?	
if($_SESSION['paypal_pro_err'] == 1){
	echo "<h4 class='step4'>".__('An error has occurred:','wpShop')."</h4>"; 
	echo "<p>". $_SESSION['error_info']."</p>";
	echo "<p>".__('Please check your entries again.','wpShop')."<br /><a href='".$_SESSION['error_target']."'>".__('Go back to correct.','wpShop')."</a><p>";	
	$_SESSION['paypal_pro_err'] = 0;
	
} else {		
	
	echo"<h4 class='step4'>".__('Credit Card (processed securely by PayPal)','wpShop')."</h4>";


	$amount = get_cc_amount();
	$parts 	= explode("://",get_option('home'));
	$url 	= ($OPTION['wps_enforce_ssl'] == 'force_ssl' ? 'https://'.$parts[1]  : 'https://'.$parts[1]);

	echo "
	<form name='checkout_confirmation' action='{$url}/index.php' method='post'>"; ?>
		<input type='hidden' name='paypal-pro' value='ok' />
		   
		<label for="pppro_amoumt"><?php _e('Amount:','wpShop');?></label>               
		<p id="pppro_amount"><?php echo $amount; ?></p>

		<label for="pppro_cc_owner"><?php _e('Card Owner:','wpShop');?></label>
		<input type="text" id="pppro_cc_owner" name="cc_owner" value="<?php if(isset($_SESSION['cc_owner'])){echo $_SESSION['cc_owner'];} ?>" /><br />

		<label for="pppro_cc_type"><?php _e('Card Type:','wpShop');?></label>
		<select name="cc_type" id="pppro_cc_type">
			<option value="VISA" <?php if($_SESSION['cc_type'] == 'VISA'){echo "selected='selected'";} ?>><?php _e('Visa','wpShop');?></option>
			<option value="MASTERCARD" <?php if($_SESSION['cc_type'] == 'MASTERCARD'){echo "selected='selected'";} ?>><?php _e('MasterCard','wpShop');?></option>
			<?php 
			if(get_option('wps_shop_country')==='US'){
			?>
				<option value="DISCOVER"><?php _e('Discover Card','wpShop');?></option>
				<option value="AMEX"><?php _e('American Express','wpShop');?></option>		
			<?php				
			}
			?>
			<!--
			<option value="SWITCH"><?php _e('Maestro','wpShop');?></option>
			<option value="SOLO"><?php _e('Solo','wpShop');?></option>-->
		</select><br />

		<label for="pppro_cc_number"><?php _e('Card Number:','wpShop');?></label>
		<input type="text" name="cc_number" id="pppro_cc_number" maxlength="16" /><br />
		  <!-- would be necessary for Maestro or Solo card
			<label class="main"><?php _e('Card Valid From Date:','wpShop');?></label>
			<select name="cc_starts_month">
				<option value="01"><?php _e('January','wpShop');?></option>
				<option value="02"><?php _e('February','wpShop');?></option>
				<option value="03"><?php _e('March','wpShop');?></option>
				<option value="04"><?php _e('April','wpShop');?></option>
				<option value="05"><?php _e('May','wpShop');?></option>
				<option value="06"><?php _e('June','wpShop');?></option>
				<option value="07"><?php _e('July','wpShop');?></option>
				<option value="08"><?php _e('August','wpShop');?></option>
				<option value="09"><?php _e('September','wpShop');?></option>
				<option value="10"><?php _e('October','wpShop');?></option>
				<option value="11"><?php _e('November','wpShop');?></option>
				<option value="12"><?php _e('December','wpShop');?></option>
			</select>
			<select name="cc_starts_year">
				<option value="1999">1999</option>
				<option value="2000">2000</option>
				<option value="2001">2001</option>
				<option value="2002">2002</option>
				<option value="2003">2003</option>
				<option value="2004">2004</option>
				<option value="2005">2005</option>
				<option value="2006">2006</option>
				<option value="2007">2007</option>
				<option value="2008">2008</option>
				<option value="2009">2009</option>
			</select> (if available)
		 
		  --> 

		<label for="ppro_cc_exp_month"><?php _e('Card Expiry Date:','wpShop');?></label>
		<select name="cc_exp_month" id="ppro_cc_exp_month">
			<option value="01" <?php if($_SESSION['cc_exp_month'] == '01'){echo "selected='selected'";} ?>><?php _e('January','wpShop');?></option>
			<option value="02" <?php if($_SESSION['cc_exp_month'] == '02'){echo "selected='selected'";} ?>><?php _e('February','wpShop');?></option>
			<option value="03" <?php if($_SESSION['cc_exp_month'] == '03'){echo "selected='selected'";} ?>><?php _e('March','wpShop');?></option>
			<option value="04" <?php if($_SESSION['cc_exp_month'] == '04'){echo "selected='selected'";} ?>><?php _e('April','wpShop');?></option>
			<option value="05" <?php if($_SESSION['cc_exp_month'] == '05'){echo "selected='selected'";} ?>><?php _e('May','wpShop');?></option>
			<option value="06" <?php if($_SESSION['cc_exp_month'] == '06'){echo "selected='selected'";} ?>><?php _e('June','wpShop');?></option>
			<option value="07" <?php if($_SESSION['cc_exp_month'] == '07'){echo "selected='selected'";} ?>><?php _e('July','wpShop');?></option>
			<option value="08" <?php if($_SESSION['cc_exp_month'] == '08'){echo "selected='selected'";} ?>><?php _e('August','wpShop');?></option>
			<option value="09" <?php if($_SESSION['cc_exp_month'] == '09'){echo "selected='selected'";} ?>><?php _e('September','wpShop');?></option>
			<option value="10" <?php if($_SESSION['cc_exp_month'] == '10'){echo "selected='selected'";} ?>><?php _e('October','wpShop');?></option>
			<option value="11" <?php if($_SESSION['cc_exp_month'] == '11'){echo "selected='selected'";} ?>><?php _e('November','wpShop');?></option>
			<option value="12" <?php if($_SESSION['cc_exp_month'] == '12'){echo "selected='selected'";} ?>><?php _e('December','wpShop');?></option>
		</select>
		<select name="cc_exp_year">
			<?php echo cc_expire_ddown(); ?>
		</select><br />
			
		<label for="pppro_cc_cvc"><?php _e('Card Security Code (CVV2):','wpShop');?></label>
		<input type="text" name="cc_cvc" id="pppro_cc_cvc" size="3" maxlength="3" /><br />

		<div class="shopform_btn">
			<?php 
			echo "<input type='image'  name='add' src='"; 
			echo get_bloginfo('stylesheet_directory'); echo"/images/pay_now.png' />";
			?>
		 </div>
	</form>
<?php } ?>