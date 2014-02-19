<?php	
global $OPTION;
include_once('functions.php');
expulsion_needed();

$CAS_DATA = cas_response();

$oamount = round($CAS_DATA['amount'] * $OPTION['wps_exr_aed']);

echo order_step_table(4);

echo "<h2>".__('Thank you for your order!','wpShop')."</h2>";
echo "<h4>Please have the Amount of <span class='pay_amount'>".format_price($oamount)." AED</span> ready upon Delivery. This amount can be paid in Dirhams.</h4>";

echo "<br/><br/>";

echo "<strong>What happens next?</strong><br/><br/>";

echo "A customer service associate will contact you to confirm your order. Your item is reserved and must be picked up within 2 days.<br/><br/>";

echo "Our Address:<br/>";
echo "803<br/>";
echo "Sidra Tower (Frasier Suites Hotel Building)<br/>";
echo "Sheikh Zayed Road<br/>";
echo "Al Sufou 1, Dubai, UAE<br/><br/>";

echo "Opening Hours:<br/>";
echo "9am-6pm Sunday-Thursday<br/><br/>";

echo "Location map:<br/>";
echo '<iframe width="565" height="445" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps/ms?msid=210891605401531059454.0004cde7608b3a7292fe7&amp;msa=0&amp;ie=UTF8&amp;ll=25.10814,55.179809&amp;spn=0.006791,0.013078&amp;t=m&amp;iwloc=0004cde76577d48ec19d3&amp;output=embed"></iframe><br/><br/><br/><br/>';

if(pdf_usable_language()){
	$bill_format_label = __('Your Bill in PDF Format','wpShop');
}else{
	$bill_format_label = __('Your Bill in HTML Format','wpShop');					
}

$shop_slug = $OPTION['wps_shop_slug'];
if (strlen($shop_slug) > 0) {
	echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='category/"; echo $shop_slug; echo "'>".__('Click here.','wpShop')."</a></h5>";
} else {
	echo "<h5>".__('Like to Continue Shopping?','wpShop')." <a href='".get_real_base_url('force_http')."'>".__('Click here.','wpShop')."</a></h5>";
}

$custom_tracking = $OPTION['wps_custom_tracking'];
if($custom_tracking !=''){
	echo $custom_tracking;
}
ga_ecommerce_tracking_code($CAS_DATA['who']);
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
sale.setOrderID('<?=$CAS_DATA['tracking_id']?>');
sale.setProductID('<?=$CAS_DATA['itemname']?>');
PostAffTracker.register();
</script>

