<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td width="50%">&nbsp;</td>
			<td>
				<table  width="600" cellspacing="0" cellpadding="0" style="font:14px Arial, Helvetica, sans-serif;">
					<tr>
						<td>
							<br>
							<p>Dear <?php echo $order['f_name'].' '.$order['l_name']; ?>,</p>
							<p>Thank you for placing your order.</p>
							<?php if ($order['layaway_order'] > 0) { ?>
							<p>You have chosen to pay in installments.</p>
							<?php } ?>
							<strong>You ordered:</strong><br><br>
							Order number: <?php echo $order_id; ?><br>
							Order placed on: <?php echo date("d.m.Y - H:i:s", ($order['order_time'] + $OPTION['wps_time_addition'])); ?><br><br>

							<?php if ($CART['status'] == 'filled') { ?>
							<table width="100%" cellpadding="5" cellspacing="0" border="1" style="font:14px Arial, Helvetica, sans-serif;">
								<tr>
									<th>Product</th>
									<th>Quantity</th>
									<th>Item Total</th>
								</tr>
								<?php foreach($CART['content'] as $v) { $item_details = explode("|", $v); ?>
								<tr>
									<td>
										<?php if ($item_details[6]) { ?>
											<img src="<?php echo $item_details[6]; ?>" align="left" width="91" height="91"><br>
										<?php } ?>
										<strong><?php echo $item_details[2]; ?></strong><br>
										<?php echo $item_details[5]; ?>
									</td>
									<td align="center"><?php echo $item_details[1]; ?></td>
									<td align="center"><?php echo format_price($item_details[4] * $_SESSION["currency-rate"], true); ?></td>
								</tr>
								<?php } ?>
								<tr>
									<td colspan="2" align="right">Subtotal</th>
									<td align="left"><?php echo format_price($CART['total_price'] * $_SESSION["currency-rate"], true); ?></td>
								</tr>
								<?php if (strlen($order['voucher']) && $order['voucher'] != 'non') { ?>
									<tr>
										<td colspan="2" align="right">- Voucher</th>
										<td align="left"><?php echo format_price($order['voucher_amount'] * $_SESSION["currency-rate"], true); ?></td>
									</tr>
								<?php } ?>
								<tr>
									<td colspan="2" align="right">Shipping Fee</th>
									<td align="left"><?php echo format_price($order['shipping_fee'] * $_SESSION["currency-rate"], true); ?></td>
								</tr>
								<?php if ($OPTION['wps_tax_enable'] && $order['tax'] > 0) { ?>
									<tr>
										<td colspan="2" align="right">Tax</th>
										<td align="left"><?php echo format_price($order['tax'] * $_SESSION["currency-rate"], true); ?></td>
									</tr>
								<?php } ?>
								<?php // if layaway order
								if ($order['layaway_order'] > 0) {
									$oamounts = layaway_get_process_amounts($order['layaway_order']);
									$lorder_total = $oamounts['total'];
									$lorder_paid = $oamounts['paid'];
									$lorder_balance = $oamounts['balance'];
									$lorder_date = $oamounts['order_date'];
									$bdate = date("d.m.Y", mktime(0, 0, 0, date("m", $lorder_date), date("d", $lorder_date) + 30, date("Y", $lorder_date)));
									?>
									<tr>
										<th colspan="2" align="right"><strong>Total amount</strong></th>
										<td align="left"><strong><?php echo format_price($lorder_total * $_SESSION["currency-rate"], true); ?></strong></td>
									</tr>
									<tr>
										<th colspan="2" align="right"><strong>Paid amount</strong></th>
										<td align="left"><strong><?php echo format_price($lorder_paid * $_SESSION["currency-rate"], true); ?></strong></td>
									</tr>
									<tr>
										<th colspan="2" align="right"><strong>Balance amount</strong></th>
										<td align="left"><strong><?php echo format_price($lorder_balance * $_SESSION["currency-rate"], true); ?></strong></td>
									</tr>
								<?php } else { ?>
									<tr>
										<th colspan="2" align="right"><strong>Order Total</strong></th>
										<td align="left"><strong><?php echo format_price($order['amount'] * $_SESSION["currency-rate"], true); ?></strong></td>
									</tr>
								<?php } ?>
							</table>
							<br>
							<?php } ?>

							<?php if ($order['layaway_order'] > 0) { ?>

								<h3>What happens next?</h3>

								<strong>How do I complete the payment?</strong><br><br>
								<p>To pay the balance amount, please click <a href="<?php echo get_permalink($OPTION['wps_account_my_purchases_page']); ?>">here</a>.</p>
								<p>You can pay the balance amount through any of the following payment methods: bank transfer, credit card, paypal or cash on delivery*.</p>
								<p>*Please note that Cash on Delivery payment is only applicable for U.A.E residents.</p>

								<strong>How long do I have to complete the payment?</strong><br><br>
								<p>The balance amount should be paid by: <?php echo $bdate; ?>. An extension fee of 5% of the price of the item will apply monthly, if you choose to extend the payment beyond this date.</p>
								<p>Payment can be extended for a maximum of 3 months from the initial payment date. Orders not paid in full within 3 months will be cancelled and applicable restocking fee and extension fee applied.</p>
								
								<strong>When will I get my items?</strong><br><br>
								<p>You will receive your item once the full payment has been completed.</p>

								<strong>What if I want to cancel or return my order?</strong><br><br>
								<p>A purchase can be cancelled anytime, with a 10% cancellation charge. This means that you will receive a refund on your payment, minus 10% of the price of the item.</p>
								<p>Items that have been purchased in installments can also be returned for a refund for up to three days after delivery, minus a 10% restocking fee.</p><br><br>
								<p>Read our full guide on buying items in installments, <a href="http://www.theluxurycloset.com/buy-installments">here</a>.</p>

							<?php } else { ?>

								<?php if($order['p_option'] != 'cod' && $order['p_option'] != 'cash') { ?>
									<strong>It will be sent to:</strong><br><br>
									<?php if ($order['d_addr'] == 1 && count($shipping_data)) { ?>
										<?php echo $shipping_data['f_name'].' '.$shipping_data['l_name']; ?><br>
										<?php echo $order['telephone']; ?><br><br>
										<?php echo $shipping_data['street']; ?><br>
										<?php echo $shipping_data['town']; ?><br>
										<?php echo $shipping_data['state']; ?><br>
										<?php echo $shipping_data['country']; ?><br><br>
									<?php } else { ?>
										<?php echo $order['f_name'].' '.$order['l_name']; ?><br>
										<?php echo $order['telephone']; ?><br><br>
										<?php echo $order['street']; ?><br>
										<?php echo $order['town']; ?><br>
										<?php echo $order['state']; ?><br>
										<?php echo $order['country']; ?><br><br>
									<?php } ?>
								<?php } ?>
								
								<strong>Billing & Payment:</strong><br><br>

								<?php echo $order['f_name'].' '.$order['l_name']; ?><br>
								<?php echo $order['telephone']; ?><br><br>
								
								<?php echo $order['street']; ?><br>
								<?php echo $order['town']; ?><br>
								<?php echo $order['state']; ?><br>
								<?php echo $order['country']; ?><br><br>
								
								<h3>What happens next?</h3>

								<?php if($order['p_option'] == 'cod') { ?>
									<p>A customer service associate will contact you to confirm your order.</p>
								<?php } if ($order['p_option'] == 'cash') { ?>
									<p>A customer service associate will contact you to confirm your order. Your item is reserved and must be picked up within 2 days.</p> 

									<img src="<?php echo TEMPLURL; ?>/images/pickup-map.jpg" alt=""><br><br>

									Address:<br>
									Office 803<br>
									Sidra Tower (Frasier Suites Hotel Building)<br>
									Sheikh Zayed Road<br>
									Al Sufouh 1, Dubai, UAE<br><br>
									
									Office Hours:<br>
									Sunday-Thursday 9am-6pm<br><br>
								<?php } if ($order['p_option'] == 'transfer') { ?>
									<p>Your item is reserved for 3 days. Please send a receipt of the wire tranfser to <a href="mailto:<?php echo $OPTION['wps_shop_email']; ?>"><?php echo $OPTION['wps_shop_email']; ?></a> so that our team can process your payment.</p>
									
									Our bank account details are: <br>
									TRADELUX LLC<br>
									Account no: 0012-432415-061<br>
									IBAN: AE630400000012432415061<br>
									Bank: RAK BANK<br>
									Branch: Sheikh Zayed Road, Dubai, UAE<br>
									Swift Code: NRAKAEAK  <br><br>
									 
									For USD Transfers:<br>
									Cover to be effected through:<br>
									SWIFT: IRVTUS3N Bank of New York, New York, USA.<br><br>
								<?php } ?>

								<?php if ($order['p_option'] != 'cash') { ?>
									<strong>When will I get my items?</strong><br>
									<p>Your item will be delivered in 3 – 7 business days. While most items are delivered in 3 business days, items such as watches and jewelry take up to 7 days. Please contact us if you would like to know an exact date of delivery.</p>
									<p>Once your item has been dispatched, you will receive a notification e-mail which will contain your delivery reference number. You will be able to use this reference number to track your shipment.</p>
								<?php } ?>

								<strong>What if I want to return/exchange my order once received?</strong><br><br>
								<table style="font:14px Arial, Helvetica, sans-serif;">
									<tr>
										<td colspan="2">1. Give us your order number</td>
									</tr>
									<tr>
										<td><img src="<?php echo TEMPLURL; ?>/images/email-mail-icon.png"></td>
										<td><a href="mailto:<?php echo $OPTION['wps_shop_email']; ?>" style="color:#0563c1;"><?php echo $OPTION['wps_shop_email']; ?></a></td>
									</tr>
									<tr>
										<td><img src="<?php echo TEMPLURL; ?>/images/email-phone-icon.png"></td>
										<td><?php echo $OPTION['wps_shop_questions_phone']; ?></td>
									</tr>
								</table><br>
								<table style="font:14px Arial, Helvetica, sans-serif;">
									<tr>
										<td>2. Send your item(s) back within 3 days of receipt.</td>
									</tr>
									<tr>
										<td>&nbsp;- All the original tags must be attached.</td>
									</tr>
									<tr>
										<td>&nbsp;- The item(s) must be in the same condition as you received them.</td>
									</tr>
								</table><br>
								Refund: You will receive your full refund within 14 days of the Luxury Closet receiving your item(s) minus the delivery costs & import duties if applicable. Please visit our Delivery & Returns page for further details.<br><br>
								<strong>Exchange: You are welcome to exchange your item for another, and we will deliver it to you for free! Contact us for more information.</strong>
							<?php } ?>
						</td>
					<tr>
				</table>
			</td>
			<td width="50%">&nbsp;</td>
		</tr>
	</table>
	<br><br>
</body>
</html>