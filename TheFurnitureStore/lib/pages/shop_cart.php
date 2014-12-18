<?php
$CART = show_cart();
if($OPTION['wps_shop_mode'] == 'Inquiry email mode') {
	$LANG['your_shopping_cart']	= __('Your Items for Enquiry','wpShop');
	$LANG['cart_empty'] 		= __('You have Added no Enquiries!','wpShop');	
	$LANG['continue_shopping'] 	= __('Continue Browsing','wpShop');	
	$LANG['start_shopping'] 	= __('Start Browsing','wpShop');	
	$LANG['order_now'] 			= __('Inquire Now','wpShop');
	$form_class = 'order_form c_order inquiry_form';
} else {
	$LANG['your_shopping_cart']	= $OPTION['wps_pgNavi_cartOption'];
	$LANG['cart_empty'] 		= str_replace("%s",$OPTION['wps_pgNavi_cartOption'], __('Your %s is Empty!','wpShop'));	
	$LANG['continue_shopping'] 	= __('Continue Shopping','wpShop');	
	$LANG['start_shopping'] 	= __('Start Shopping','wpShop');			
	$LANG['order_now'] 			= __('Order Now','wpShop');
	$form_class = 'order_form c_order';
}
$LANG['article'] 				= __('Item','wpShop');
$LANG['amount'] 				= __('Quantity','wpShop');
$LANG['unit_price'] 			= __('Item Price','wpShop');
$LANG['total'] 					= __('Item Total','wpShop');
$LANG['remove'] 				= __('Remove','wpShop');			
$LANG['subtotal_cart']			= __('Subtotal:','wpShop');
$LANG['incl']					= __('incl.','wpShop');
$LANG['excl']					= __('excl.','wpShop');
$LANG['shipping_costs']			= $OPTION['wps_shippingInfo_linkTxt'];
$LANG['update'] 				= __('Update','wpShop');	
$LANG['shipping_fee_1']			= __('Shipping','wpShop');		

wishlist_success('padding:0 18px');
wps_shop_process_steps(1); ?>
<div class="payment-section">
	<h1 class="title">Your Order</h1>
	<div class="holder">
		<?php
		// stock control warning 
		if((isset($_GET['sw'])) && (!empty($_GET['sw'])) && (strpos($_GET['sw'],'OK') === FALSE))
		{
			$fb1 		= str_replace("%s",$OPTION['wps_pgNavi_cartOption'], __('We are sorry but due to Limited Availibility you can Only Add %amount% more Items of Article %article% into your %s','wpShop'));
			$fb2 		= __('We are sorry but Article %article% is Out of Stock.','wpShop');		
			$stock_note = NULL;
			$parts 		= explode(",",$_GET['sw']);	
			foreach($parts as $v){
				$items 		= explode("-",$v);		
				$iid 		= cid2item_id($items[0]);
				if(strlen($items[1])>0){
					$fb = ($items[1] == 0 ? $fb2 : $fb1);	
					$stock_info = str_replace('%article%',$iid,$fb);	
					$stock_info = str_replace('%amount%',$items[1],$stock_info);	
					$stock_note .= $stock_info."<br/>";
				}
			}
			echo "<p class='failure'>$stock_note</p>";
		}

		if($CART['status'] == 'filled')
		{
			?>
			<form class="<?php echo $form_class; ?>" action="<?php echo get_cart_url(); ?>" style="margin-top: 0px;" method="POST">
				<input type="hidden" name="cart" value="update">
				<table class="order_table c_order" border="0">
					<tr>
						<th colspan="2"><?php echo $LANG['article']; ?></th>
						<th class="third"><?php echo $LANG['unit_price']; ?></th>
						<th class="last"><?php echo $LANG['remove']; ?></th>
					</tr>
					<?php loop_products($CART); ?>
					<tr class="sums top">
						<td colspan="2" style="text-align:right;"><b><?php echo $LANG['subtotal_cart']; ?></b></td>
						<td colspan="2"><b><?php echo format_price($CART['total_price'] * $_SESSION['currency-rate'], true); ?></b></td>
					</tr>
					<?php if (is_flat_limit_shipping_free($CART['total_price'])) { ?>
						<tr class="sums">
							<td colspan="2" style="text-align:right;"><b>Shipping:</b></td>
							<td colspan="2"><b style="color:#FF0000">FREE</b></td>
						</tr>
					<?php } ?>
					<?php if ($_SESSION['layaway_order'] == 0) { ?>
						<tr class="sums">
							<td colspan="2" style="text-align:right;">
								<?php
								if($OPTION['wps_tax_info_enable']) { show_cart_tax_info(); }
								$cart_comp = cart_composition($_SESSION['cust_id']);
								if ($cart_comp == 'mixed' || $cart_comp == 'digi_none') {
									if($OPTION['wps_shipping_details_enable']) { ?>
										<p><a href="#shipping-costs" class="cart-ship-costs"><?php echo $LANG['excl']; ?> <?php echo $LANG['shipping_costs']; ?></a></p>
										<div style="display:none;">
											<div id="cart-shipping-costs" class="cart-shipping-costs-desc" style="width:400px; padding:20px;">
												<h2><?php echo $LANG['shipping_costs']; ?></h2>
												<?php echo $OPTION['wps_shipping_details']; ?>
											</div>
										</div>
									<?php
									}					
								} ?>
							</td>
						</tr>
					<?php } ?>
					<?php
					// Layaway next payment
					if ($_SESSION['layaway_order'] > 0 && $CART['total_item_num'] == 1) {
						$oamounts = layaway_get_process_amounts($_SESSION['layaway_order']);
						echo "
						<tr class='sums'>
							<td colspan='2' style='text-align:right;'><b>".__('Paid amount','wpShop').":</b></td>
							<td colspan='2'><b>".format_price($oamounts['paid'] * $_SESSION['currency-rate'], true)."</b></td>
						</tr>
						<tr class='sums'>
							<td colspan='2' style='text-align:right;'><b>".__('Balance amount','wpShop').":</b></td>
							<td colspan='2'><b>".format_price($oamounts['balance'] * $_SESSION['currency-rate'], true)."</b></td>
						</tr>";
					}
					// Layaway Options
					if (layaway_is_enabled() && $CART['total_item_num'] == 1) {
						$pdetails = explode("|", $CART['content'][0]);
						$prod_id = $pdetails[8];
						$days = layaway_get_product_days($prod_id);
						if ($days >= 8) {
							if ($_SESSION['layaway_order'] > 0) {
								$layaway_amount = $oamounts['balance'];
							} else {
								$layaway_amount = layaway_get_amount($CART['total_price']);
							}
							$layaway_def_amount = $layaway_amount;
							if ($_SESSION['layaway_process'] == 1 && $_SESSION['layaway_amount']) {
								$layaway_amount = $_SESSION['layaway_amount'];
							}

							$chd1 = ''; $chd2 = ' CHECKED'; $clstyle = ' style="display:none;"'; $clpr = 0;
							if ($_GET['installments'] == 1 || $_SESSION['layaway_process'] == 1) { $chd1 = ' CHECKED'; $chd2 = ''; $clstyle = ''; $clpr = 1; }

							$payment_amount = format_price(sprintf("%01.2f", $layaway_amount * $_SESSION['currency-rate']));
							$payment_amount = str_replace(',', '', $payment_amount);
							if ($_GET['minerror'] == 1) {
								$minerrordiv = '<div class="cl-minerror">'.__('A minimum deposit amount of '.layaway_get_percent_number().'% is required to reserve the bag.','wpShop').'</div>';
							}
							echo '
					<tr class="sums">
						<td colspan="7">
							<div class="cart-layaway">
								<div class="cart-layaway-opt">
									<div class="cl-tit">'.__('Purchase on installments','wpShop').':</div>
									<div class="cl-radio"><input type="radio" name="layaway_process" value="1" class="clopt"'.$chd1.'>&nbsp;'.__('Yes','wpShop').'</div>
									<div class="cl-radio"><input type="radio" name="layaway_process" value="0" class="clopt"'.$chd2.'>&nbsp;'.__('No','wpShop').'</div>
									<div class="cl-question"><a href="#layaway-purchase" class="installments-popup-link"><img src="'.get_bloginfo('template_url').'/images/question-icon.gif"></a></div>
								</div>
								<div class="cart-layaway-body"'.$clstyle.'>
									<div class="cart-layaway-opt">
										<div class="cl-tit">'.__('Accept Terms','wpShop').':</div>
										<div class="cl-radio"><input type="radio" name="layawayt" value="1" class="clterm">&nbsp;'.__('Yes','wpShop').'</div>
										<div class="cl-radio"><input type="radio" name="layawayt" value="0" class="clterm">&nbsp;'.__('No','wpShop').'</div>
										<div class="cl-radio"><a href="#cart-layaway-terms" class="cl-read-terms"><img src="'.get_bloginfo('stylesheet_directory').'/images/read_terms.png"></a></div>
									</div>
									<div class="cart-layaway-amn">
										'.$minerrordiv.'
										<div class="cl-tit">'.__('Installment amount','wpShop').', '.$_SESSION['currency-code'].':</div>
										<div class="cl-radio"><input type="text" name="layaway_amount" value="'.$payment_amount.'" class="cl-amount"></div>
										<div class="cl-question"><a href="#layaway-payment" class="layaway-payment-q"><img src="'.get_bloginfo('template_url').'/images/question-icon.gif"></a></div>
									</div>
								</div>
							</div>
						</td>
					</tr>';
						}
					}
				echo '
				</table>
				<!--<input class="shop-button update_cart" type="submit" name="update" value="" />-->
			</form>
			<form id="proceed2Checkout" style="margin-top: 0px;" method="POST">
				<input type="hidden" name="proceed2Checkout" value="true">
				<input type="hidden" name="frompage" value="cart">
				<input type="hidden" name="layaway_process" id="layaway-process" value="'.$clpr.'">
				<input type="hidden" name="layaway_amount" id="layaway-amount" value="'.$layaway_amount.'">
				<input type="hidden" name="layaway_def_amount" value="'.$layaway_def_amount.'">
				<input type="hidden" name="layaway_cart_total" id="layaway-cart-total" value="'.$CART['total_price'].'">
				<div class="shop-button-holder right">
					<a class="btn-grey cartActionBtn cont_shop" href="'.$_SESSION[cPage].'">'.$LANG[continue_shopping].'</a>
					<input class="btn-orange lm checkout" type="submit" name="checkout" value="Proceed to Checkout" />
				</div>
			</form>
			';
		} else { // Cart is empty
			$_SESSION['cPage'] = get_category_link($OPTION['wps_women_bags_category']); ?>
			<p class="cart_empty"><?php echo $LANG['cart_empty']; ?></p>
			<a class="btn-orange cartActionBtn cont_shop" href="<?php echo $_SESSION['cPage']; ?>"><?php echo $LANG['start_shopping']; ?></a>
			<?php
		}
		?>
	</div>
</div>
