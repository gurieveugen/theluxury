<?php
global $OPTION;
include_once('functions.php');	
expulsion_needed();

$PDT_DATA = cod_response();

$oamount = round($PDT_DATA['amount'] * $OPTION['wps_exr_aed']);

echo order_step_table(4);

echo "<h2>".__('Thank you for your Order!','wpShop')."</h2>";
echo "<h4>Please have the Amount of <span class='pay_amount'>".format_price($oamount)." AED</span> ready upon Delivery. This amount can be paid in Dirhams.</h4>";

echo "<br/><br/>";

echo "<strong>What Happens Next?</strong><br/><br/>";
echo "Our customer service representative will give you a call to confirm the order.<br/><br/>";

echo "<strong>When will I get my items?</strong><br/><br/>";
echo "Your item will be delivered in 2-10 business days.<br/><br/>";
echo "Once your item has been dispatched, you will receive a notification e-mail which will contain your delivery reference number. You will be able to use this reference number to track your shipment.<br/><br/><br/><br/>";

if(pdf_usable_language()){ 	
	$bill_format_label = __('Your Bill in PDF Format','wpShop');
}else{
	$bill_format_label = __('Your Bill in HTML Format','wpShop');					
}

$shop_slug = (isset($OPTION['wps_shop_slug']))? $OPTION['wps_shop_slug']:'';
if(strlen($shop_slug) > 0){
	echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='category/"; echo $OPTION['wps_shop_slug']; echo "'>".
	__('Click here.','wpShop')."</a></h5>";					
}else{				
	echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='".get_real_base_url('force_http')."'>".
	__('Click here.','wpShop')."</a></h5>";					
}

$custom_tracking = $OPTION['wps_custom_tracking'];
if($custom_tracking !=''){	echo $custom_tracking;}
ga_ecommerce_tracking_code($PDT_DATA['who']);
?>
<script type="text/javascript">
document.write(unescape("%3Cscript id=%27pap_x2s6df8d%27 src=%27" + (("https:" == document.location.protocol) ? "https://" : "http://") + "perf.clickmena.com/scripts/trackjs.js%27 type=%27text/javascript%27%3E%3C/script%3E"));
</script>
<script data-cfasync="false" type="text/javascript">
PostAffTracker.setAccountId('66acecfb'); 
var sale = PostAffTracker.createSale();
price = '<?=$_POST['amount']?>';
sale.setTotalCost(price);
if(price <= 2000){    sale.setCustomCommission('%8');}
else if(price > 2000 && price <= 3000) { sale.setCustomCommission('%6');}
else if(price > 3000 && price <= 10000) { sale.setCustomCommission('%4'); }
else{sale.setCustomCommission('%2');}
sale.setCurrency('<?=($OPTION['wps_currency_code'] == '')? 'USD':$OPTION['wps_currency_code'];?>');
sale.setOrderID('<?=$PDT_DATA['tracking_id']?>');
sale.setProductID('<?=$PDT_DATA['itemname']?>');
PostAffTracker.register();
</script>