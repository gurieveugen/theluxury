<?php 
if(isset($_GET['showCart']) && $_GET['showCart'] == '1'){

	echo "
	<table class='order_table c_order' border='0'>
	<thead>
	<tr>
		<th>&nbsp;</th>
		<th>$data[article]</th>
		<th>$data[amount]</th>
		<th>$data[unit_price]</th>
		<th>$data[total]</th>
		<th>$data[remove]</th>
	</tr>
	</thead>"; 
	
}else{

	echo "
	<table class='order_table' border='1'>
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th>$LANG[article]</th>
			<th>$LANG[amount]</th>
			<th>$LANG[unit_price]</th>
			<th class='txt_right'>$LANG[total]</th>
		</tr>
	</thead>";
} ?>