<?php
global $OPTION;
$LANG['choose_delivery_option'] 	= __('Select Delivery Option:','wpShop');
$LANG['choose_payment_option'] 		= __('Select Payment Option:','wpShop');
$LANG['enter_voucher'] 				= __('Enter Voucher Code','wpShop');
$LANG['choose_delivery_country'] 	= __('Select Delivery Country:','wpShop');
$LANG['next_step'] 					= __('Next Step','wpShop');
$LANG['cod_available']				= __('Cash on delivery is not available outside UAE','wpShop');
$siteurl = get_option('siteurl');

$go	= '2';
$dpch = 'none';
$order_level = what_order_level();
if($order_level == 2){
	$dpch = delivery_payment_chosen();
	$go	= '3&dpchange=1';
}

$CART = show_cart();
$cart_items = $CART["content"];
$show_pickup = true; // show pickup delivery
foreach($cart_items as $cart_item) {
	$cidata = explode("|", $cart_item);
	$pid = $cidata[8];
	if (in_category($OPTION['wps_women_watches_category'], $pid) || in_category($OPTION['wps_men_watches_category'], $pid)) {
		$show_pickup = false;
	}
}

if ($_SESSION['layaway_order'] > 0) { $dstyle = ' style="display:none;"'; }

wps_shop_process_steps(2); ?>
<div class="payment-section">
	<h1 class="title">Payment & Delivery Options</h1>
	<div class="holder">
		<?php
		if($OPTION['wps_shop_mode'] == 'Inquiry email mode')
		{
			echo "<p class='info'>";
			_e('This Website will send your Order first as an <strong>Email Enquiry</strong> to us. However, for an exact calculation of all necessary costs, please select your preferred Delivery and Payment Option below.','wpShop');
			echo "</p>";
		} ?>
		<form class="step1 checkoutSteps" action="?orderNow=<?php echo $go; ?>" method="POST">
			<input type="hidden" name="utm_source" id="utm_source">
			<input type="hidden" name="utm_medium" id="utm_medium">
			<input type="hidden" name="utm_campaign" id="utm_campaign">
			<input type="hidden" name="utm_content" id="utm_content">
			<input type="hidden" name="utm_term" id="utm_term">
			<div class="column left">
				<!-- Delivery Options -->
				<div id="editDelivery"<?php echo $dstyle; ?>>
					<h4><?php echo $LANG['choose_delivery_option']; ?></h4>
					<?php if ($show_pickup) { ?>
					<label class="delivery-row">
						<span class="icon"><img src="<?php bloginfo('template_url'); ?>/images/icon-pick-up.png" alt=""></span>
						<input id="dOptpickup" type="radio" name="d_option" onchange="changePaymentAvailability('pickup')" value="pickup" />
						<span><?php echo $OPTION['wps_pickUp_label']; ?></span>
					</label>
					<?php } ?>
					<label class="delivery-row">
						<span class="icon"><img src="<?php bloginfo('template_url'); ?>/images/icon-delivery.png" alt=""></span>
						<input id="dOptpost" type="radio" name="d_option" onchange="changePaymentAvailability('post')" value="post" checked="checked" />
						<span><?php echo $OPTION['wps_delivery_label']; ?></span>
					</label>
				</div>
				<!-- Payments Options -->
				<div id="editPayment">
					<h4><?php echo $LANG['choose_payment_option']; ?></h4>
					<div class="payment-methods">
						<?php if(isset($_SESSION['cod_not_available'])) {
							echo "<p id='cod_error e_message'><span class='error' style='padding-left:20px;'><b>".$LANG['cod_available']."</b></span></p>";		
							unset($_SESSION['cod_not_available']);
						}
						$show_transfer = true;
						$geoplugin = new geoPlugin();
						$ip = $_SERVER['REMOTE_ADDR'];
						$geoplugin->locate($ip);
						if (strlen($geoplugin->countryName)) {
							$ip_country = strtoupper(trim($geoplugin->countryName));
							if (strlen($ip_country)) {
								if ($ip_country == 'UNITED STATES') {
									$show_transfer = false;
								}
							}
						}
						$payment_options = get_payment_options($dpch); 
						foreach($payment_options as $poval => $podata) {
							if ($poval == 'transfer' && !$show_transfer) { continue; }
						?>
							<label title="<?php echo $podata['label']; ?>" class="payment-method payment-method-<?php echo $poval; ?>" id="<?php echo $poval; ?>">
								<input id="pOpt<?php echo $poval; ?>" type="radio" name="p_option" value="<?php echo $poval; ?>" <?php echo $podata['checked']; ?> />
								<img src="<?php echo $podata['src']; ?>" alt="<?php echo $podata['label']; ?>">
								<span><?php echo $podata['label']; ?></span>
							</label>
						<?php } ?>
					</div>
				</div>
				<?php
				// are we using vouchers? display voucher field
				if ($OPTION['wps_voucherCodes_enable']) {
					$custid = str_rot13($_SESSION['cust_id']); ?>
					<div class="voucher_wrap"<?php echo $dstyle; ?>>
						<h4><?php echo $LANG['enter_voucher']; ?></h4>
						<label for="vid"><?php echo __('Voucher / Discount Code: ','wpShop'); ?></label>
						<input type="text" name="v_no" id="vid" maxlength="50" onkeyup="checkVoucher('<?php echo is_in_subfolder(); ?>','<?php echo get_protocol(); ?>','<?php echo $custid; ?>');" 
						onblur="checkVoucher('<?php echo is_in_subfolder(); ?>','<?php echo get_protocol(); ?>','<?php echo $custid; ?>');" />
						<div id="txtHint" style="margin-top:12px;"></div>
					</div>
				<?php
				}
				?>
			</div>
			<div class="column right width-452">
				<div class="o-table-holder">
					<table class='o-table'>
						<tr>
							<th colspan="2">Your Order</th>
						</tr>
						<?php
						foreach($cart_items as $cart_item) {
							$cart_item_data = explode("|", $cart_item);
							$item_img = '';
							if (strlen($cart_item_data[6])) {
								$img_src 	= $cart_item_data[6];
								$img_size 	= $OPTION['wps_ProdRelated_img_size'];
								$des_src 	= $OPTION['upload_path'].'/cache';
								$img_file 	= mkthumb($img_src,$des_src,$img_size,'width');    
								$item_img 	= $siteurl.'/'.$des_src.'/'.$img_file;	
							}
						?>
						<tr>
							<td><?php if (strlen($item_img)) { ?><img src="<?php echo $item_img; ?>" alt="<?php echo $cart_item_data[2]; ?>" /><?php } ?></td>
							<td class="second">
								<h5><?php echo $cart_item_data[2]; ?></h5>
								<strong class="mark">Price:</strong> <?php echo format_price($cart_item_data[3] * $_SESSION['currency-rate'], true); ?>
							</td>
						</tr>
						<?php } ?>
						<?php $TOTAL_AM = $CART['total_price']; ?>
						<tr>
							<td colspan="2">
								<table class="yorder">
									<tr>
										<td align="right"><?php echo __('Subtotal','wpShop'); ?>:</td>
										<td><?php echo format_price($TOTAL_AM * $_SESSION['currency-rate'], true); ?></td>
									</tr>
									<?php if ($_SESSION['layaway_order'] > 0) {
										$oamounts = layaway_get_process_amounts($_SESSION['layaway_order']);
										$paid = $oamounts['paid'];
										$balance = $oamounts['balance'];
										$TOTAL_AM = $balance; ?>
										<tr>
											<td align="right"><?php echo __('Paid Amount','wpShop'); ?>:</td>
											<td><?php echo format_price($paid * $_SESSION['currency-rate'], true); ?></td>
										</tr>
										<tr>
											<td align="right"><?php echo __('Balance Amount','wpShop'); ?>:</td>
											<td><?php echo format_price($balance * $_SESSION['currency-rate'], true); ?></td>
										</tr>
									<?php } else { ?>
										<?php // TOTAL amount ?>
										<tr>
											<td align="right"><?php echo __('Total','wpShop'); ?>:</td>
											<td><?php echo format_price($TOTAL_AM * $_SESSION['currency-rate'], true); ?></td>
										</tr>
									<?php } ?>
									<?php // LAYAWAY amount ?>
									<?php if (layaway_is_enabled() && $CART['total_item_num'] == 1 && $_SESSION['layaway_process'] == 1) { ?>
										<?php
										$layaway_amount = $_SESSION['layaway_amount'];
										$TOTAL_AM = $layaway_amount; ?>
										<tr>
											<td align="right"><?php echo __('Installment Payment','wpShop'); ?>:</td>
											<td><?php echo format_price($layaway_amount * $_SESSION['currency-rate'], true); ?></td>
										</tr>
										<tr>
											<td align="right"><?php echo __('Order Total','wpShop'); ?>:</td>
											<td><?php echo format_price($TOTAL_AM * $_SESSION['currency-rate'], true); ?></td>
										</tr>
									<?php } ?>
								</table>
							</td>
						</tr>
					</table>
				</div>
				<div class="button-right">
					<input class="shop-button" type="submit" name="step1" value="Proceed" />
				</div>
			</div>
			<input type="hidden" name="order_step" value="1">
		</form>
	</div>
</div>
<script language="JavaScript"> 
	function changePaymentAvailability(deliveryMethod) {  
		if (deliveryMethod == 'pickup') {
			if (document.getElementById('pOptcod').checked) {  
				document.getElementById('pOptcash').checked = true;
			}
			jQuery('#cod').hide();
			jQuery('#cash').show();
		 } else {
			if (document.getElementById('pOptcash').checked) {
				document.getElementById('pOptcod').checked = true;
			}
			jQuery('#cash').hide();
			jQuery('#cod').show();
		 }
	}
	//initial defining COD or COL when page loaded
	if (document.getElementById('dOptpost').checked) {
		changePaymentAvailability('post');
	} else {
		changePaymentAvailability('pickup');
	}
</script> 
