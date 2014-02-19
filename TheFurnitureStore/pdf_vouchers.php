<?php
include '../../../wp-load.php';	
$VOUCHER = load_what_is_needed('voucher');
$VOUCHER->create_pdf(is_dbtable_there('vouchers'));					
?>