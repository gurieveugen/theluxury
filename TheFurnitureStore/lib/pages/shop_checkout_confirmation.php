<?php global $OPTION;
$oid = $_GET['oid'];
$order_payment_data = $_SESSION['order_payment_data'];
if (!$order_payment_data && $oid) {
	$table = is_dbtable_there('orders');
	$res = mysql_query("SELECT * FROM $table WHERE oid = '$oid' LIMIT 1");
	$order_payment_data = mysql_fetch_assoc($res);
}
if (!$order_payment_data) {
	wp_redirect(home_url('/'));
	wp_exit();
}
wps_shop_process_steps(5);
?>
<div class="payment-section">
	<h1 class="title">Order Review</h1>
	<div class="holder">
		<div class="content-step-5">
			<?php if (strlen($order_payment_data['error'])) { ?>
				<p style="color:#FF0000;"><?php echo $order_payment_data['error']; ?></p>
			<?php } else { ?>
				<?php if ($order_payment_data['p_option'] == 'paypal' || $order_payment_data['p_option'] == 'audi') { ?>
					<h2>Thank you for your Order!</h2>
					<p>An e-mail confirmation has been sent your address. A tracking number will be sent via e-mail once your items have been shipped.</p>
				<?php } else if ($order_payment_data['p_option'] == 'cod') { ?>
					<h2>Thank you for shopping with us!</h2>
					<p>We will call you to confirm your order.</p>
				<?php } else if ($order_payment_data['p_option'] == 'cash') { ?>
					<h2>Thank you for shopping with us!</h2>
					<p>We will call you to confirm your order. Your items have been reserved for 3 days.</p>
				<?php } else if ($order_payment_data['p_option'] == 'transfer') { ?>
					<h2>Thank you for your Order!</h2>
					<p>Your items have been reserved for 3 days and an e-mail confirmation has been sent to your address.</p>
					<p>Please follow the instructions in the e-mail to complete your order.</p>
				<?php } ?>
			<?php } ?>
			<p>If you have any further questions or concerns, kindly send us an e-mail on <a href="mailto:<?php echo $OPTION['wps_shop_email']; ?>" class="mark"><?php echo $OPTION['wps_shop_email']; ?></a> or call us on <span class="mark"><?php echo $OPTION['wps_shop_questions_phone']; ?></span></p>
			<div class="shop-button-holder top-padding">
				<a href="<?php echo home_url('/'); ?>" class="btn-orange">Continue Shopping</a>
			</div>
		</div>
	</div>
</div>
<?php
$custom_tracking = $OPTION['wps_custom_tracking'];
if($custom_tracking !=''){	echo $custom_tracking;}

ga_ecommerce_tracking_code($order_payment_data['who']);
?>
<script type="text/javascript">
document.write(unescape("%3Cscript id=%27pap_x2s6df8d%27 src=%27" + (("https:" == document.location.protocol) ? "https://" : "http://") + "perf.clickmena.com/scripts/trackjs.js%27 type=%27text/javascript%27%3E%3C/script%3E"));
</script>
<script data-cfasync="false" type="text/javascript">
PostAffTracker.setAccountId('66acecfb'); 
var sale = PostAffTracker.createSale();
price = '<?php echo $order_payment_data['amount']; ?>';
sale.setTotalCost(price);
if(price <= 2000){    sale.setCustomCommission('%8');}
else if(price > 2000 && price <= 3000) { sale.setCustomCommission('%6');}
else if(price > 3000 && price <= 10000) { sale.setCustomCommission('%4'); }
else{sale.setCustomCommission('%2');}
sale.setCurrency('<?php echo ($OPTION['wps_currency_code'] == '')? 'USD':$OPTION['wps_currency_code']; ?>');
sale.setOrderID('<?php echo $order_payment_data['tracking_id']; ?>');
sale.setProductID('<?php echo $order_payment_data['itemname']; ?>');
PostAffTracker.register();
</script>
<?php
//unset($_SESSION['order_payment_data']);
?>