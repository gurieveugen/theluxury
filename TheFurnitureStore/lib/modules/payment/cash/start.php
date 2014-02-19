<form class="order_now" method="post" action="?orderNow=5" target="_top">
	<input type="hidden" name="item_name" value="<?php echo $Your_Order.' - '.$date_order; ?>" />
	<input type="hidden" name="amount" value="<?php echo $TOTAL_AM; ?>" />
	<input type="hidden" name="currency_code" value="<?php echo $OPTION['wps_currency_code']; ?>" />
	<div class="button-right">
		<input type="submit" class="shop-button" name="add" value="Place Order" />
	</div>
</form>	
