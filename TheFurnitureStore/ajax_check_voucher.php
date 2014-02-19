<?php
include '../../../wp-load.php';
$VOUCHER 	= load_what_is_needed('voucher');		
$v_result 	= $VOUCHER->voucher_is_ok('get'); 

if($v_result['erg'] == 1){
	
		// is cart value higher than voucher amount?
		$order 			= array();
		$order['who']	= str_rot13($_GET['cid']);
		$CART 			= show_cart($order);
		$row 			= $v_result['res'];
		
		$vamount 		= (float) $row['vamount'];
		$camount		= (float) $CART['total_price'];
	
		if($row['voption'] == 'P')
		{
			$calc 		= $camount / 100;
			$vamount 	= $calc * $vamount;
		}
	
	
	if($camount < $vamount){
		echo "<b class='failure'>".__('Sorry! Your voucher cannot be used yet - The total value of your items must be more than the voucher amount!','wpShop')."</b>
		<input type='hidden' name='isVoucherOk' value='notOK' />";
	}
	else {
		echo "<b class='success'>".__('Ok! Your voucher has been verified!','wpShop')."</b><input type='hidden' name='isVoucherOk' value='OK' />";
	}
}
else {
	echo "<b class='failure'>".__('Sorry! Your voucher is invalid!','wpShop')."</b><input type='hidden' name='isVoucherOk' value='notOK' />";
}
?>