<?php
$chstep = 3;
$CART = show_cart();
$order = get_current_order();

$d_labels			= array();
$d_labels['pickup']		= __('Pick Up','wpShop');
$d_labels['post']		= __('Delivery','wpShop');
$d_labels['download']	= __('You will Receive your Download Links on this Website, after your Payment has been Successfully Completed.','wpShop');
$d_labels['email']		= __('Delivery by Email','wpShop');

$p_labels				= array();
$p_labels['paypal']		= __('PayPal','wpShop');
$p_labels['paypal_pro']	= __('Credit Card - PayPal Pro','wpShop');
$p_labels['transfer']	= __('Bank Transfer in Advance','wpShop');
$p_labels['cash']		= __('Payment at our Shop','wpShop');
$p_labels['cc_authn']	= __('Credit Card Authorize.net','wpShop');
$p_labels['g2p_authn']	= __('Gate2Play.com','wpShop');
$p_labels['alertpay']	= __('Credit Card Alertpay.com','wpShop');
$p_labels['2checkout']	= __('Credit Card 2checkout.com','wpShop');
$p_labels['audi']	= __('Credit Card Payment','wpShop');	
$p_labels['cc_wp']		= __('Credit Card WorldPay','wpShop');
$p_labels['cod']		= __('Cash on Delivery','wpShop') .' '. $OPTION['wps_cod_who_note'];

if ($_SESSION['layaway_order'] > 0) { $dstyle = ' style="display:none;"'; }
?>
	<?php wishlist_success(); ?>

	<div class="payment-content">
		<div class="payment_steps">
			<div class="payment-step">
				<div class="payment-step-title cf">
					<h3>Delivery</h3>
				</div>
			</div>
			<div class="payment-step">
				<div class="payment-step-title cf">
					<h3>Payment</h3>
				</div>
			</div>
			<div class="payment-step open">
				<div class="payment-step-title cf">
					<h3>Order Review</h3>
				</div>
				<div class="payment-step-content">
					<?php if(isset($_REQUEST['payerror']) && $_REQUEST['payerror'] != '') { ?>
						<p class="error">There has been an error processing this transaction.<br />
						<font style="font-weight:bold;">ERROR: <?php echo $_REQUEST['payerror']; ?></font></p>
					<?php } ?>
					<div class="review-order">
						<div class="box"<?php echo $dstyle; ?>>
							<h3 class="heading">Delivery: <a href="<?php echo get_checkout_url(); ?>?orderNow=1">edit</a></h3>
							<div class="holder">
								<p><?php echo $d_labels[$order['d_option']]; ?></p>
							</div>
						</div>
						<div class="box">
							<h3 class="heading">Payment: <a href="<?php echo get_checkout_url(); ?>?orderNow=2">edit</a></h3>
							<div class="holder">
								<p><?php echo $p_labels[$order['p_option']]; ?></p>
							</div>
						</div>
						<?php if ($order['d_option'] == 'post') { ?>
							<div class="box">
								<h3 class="heading">Address: <a href="<?php echo get_checkout_url(); ?>?orderNow=1">edit</a></h5>
								<div class="holder">
									<?php
									$ba_title = __('Billing and Shipping Address','wpShop');
									if($order['d_addr'] == '1') {
										$ba_title = __('Billing Address','wpShop');
									}
									?>
									<div style="width:50%; float:left;">
										<h5><?php echo $ba_title; ?></h5>
										<p>
											<?php echo address_format($order); ?>
											<?php echo '<br/>'.$order['email']; ?>
											<?php echo '<br/>'.$order['telephone']; ?>
										</p>
									</div>
									<?php if($order['d_addr'] == '1') { ?>
										<div style="width:50%; float:right;">
											<h5><?php echo __('Delivery Address:','wpShop'); ?></h5>
											<p>
												<?php $delivery_addr = retrieve_delivery_addr(); ?>
												<?php echo address_format($delivery_addr, 'd-addr'); ?>
											</p>
										</div>
									<?php } ?>
								</div>
							</div>
						<?php } else { ?>
							<div class="box">
								<h3 class="heading">Order Info: <a href="<?php echo get_checkout_url(); ?>?orderNow=1">edit</a></h5>
								<div class="holder">
									<p>
										<?php echo $order['f_name'].' '.$order['l_name']; ?><br />
										<?php echo $order['email']; ?><br />
										<?php echo $order['telephone']; ?>
									</p>
								</div>
							</div>
						<?php } ?>
					</div>
					<?php // call start of Payment method
					$Your_Order = __('Order No.','wpShop') . $OPTION['wps_order_no_prefix'] . $order['oid'];
					$date_order = NWS_translate_date();
					// paypal and audi payment needs custom redirect form
					if ($order['p_option'] == 'paypal' || $order['p_option'] == 'audi') {
						include WP_CONTENT_DIR.'/themes/'.WPSHOP_THEME_NAME.'/lib/modules/payment/'.$order['p_option'].'/start.php';
					} else {
					?>
						<form class="order_now" method="POST" action="<?php echo get_checkout_url(); ?>?orderNow=complete" target="_top">
							<input type="hidden" name="order_id" value="<?php echo $order['oid']; ?>" />
							<input type="hidden" name="item_name" value="<?php echo $Your_Order.' - '.$date_order; ?>" />
							<input type="hidden" name="amount" value="<?php echo $TOTAL_AM; ?>" />
							<input type="hidden" name="p_option" value="<?php echo $order['p_option']; ?>" />
							<input type="hidden" name="order_step" value="3">
							<div class="button-right">
								<input type="submit" class="btn-orange" name="add" value="Place Order" />
							</div>
						</form>	
					<?php } ?>
					<div id="editOrderOverlay" class="overlay overlayAlt">
						<div class="editOrderWrap"></div>
					</div>
				</div>
			</div>
			<div class="payment-step">
				<div class="payment-step-title cf">
					<h3>Confirmation</h3>
				</div>
			</div>
		</div>
	</div>
	<div class="payment-aside">
		<?php include('shop_your_order.php'); ?>
	</div>
