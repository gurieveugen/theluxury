<?php
$order = get_current_order();
$Your_Order = __('Order No.','wpShop') . $OPTION['wps_order_no_prefix'] . $order['oid'];
$date_order = NWS_translate_date();

$TOTAL_AM = format_price($order['amount']);

if ($order['p_option'] == 'paypal' || $order['p_option'] == 'audi') {
	include WP_CONTENT_DIR.'/themes/'.WPSHOP_THEME_NAME.'/lib/modules/payment/'.$order['p_option'].'/start.php';
} else {
?>
	<form class="submit-order-cod submit-order-cash submit-order-transfer" method="POST" action="<?php echo get_checkout_url(); ?>?orderNow=complete" target="_top">
		<input type="hidden" name="order_id" value="<?php echo $order['oid']; ?>" />
		<input type="hidden" name="item_name" value="<?php echo $Your_Order.' - '.$date_order; ?>" />
		<input type="hidden" name="amount" value="<?php echo $TOTAL_AM; ?>" />
		<input type="hidden" name="p_option" value="<?php echo $order['p_option']; ?>" />
		<input type="hidden" name="order_complete" value="true">
	</form>	
<?php } ?>
