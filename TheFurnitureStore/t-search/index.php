<?php
exit();
include '../wp-load.php';

$table2 = $wpdb->prefix . $CONFIG_WPS['prefix'] . 'shopping_cart_log';
	
	if($_POST['search4record'] == '1'){
	
		$dbval 	= trim($_POST['dbval']);
		$table	= is_dbtable_there('orders');
		$qStr 	= "SELECT * FROM $table WHERE $_POST[dbfield] = '$dbval'";
		$res 	= mysql_query($qStr);
		$num 	= mysql_num_rows($res);
		
		$feedback = NULL;
		
		if($num > 0){
			$feedback .= "<br/><br/><table border='1' style='border: 1px solid black;'>";
			$feedback .= "<tr>
					<th style='border: 1px solid black; background:silver;'>when</th>
					<th style='border: 1px solid black; background:silver;'>firstname</th>
					<th style='border: 1px solid black; background:silver;'>lastname</th>
					<th style='border: 1px solid black; background:silver;'>street</th>
					<th style='border: 1px solid black; background:silver;'>state</th>
					<th style='border: 1px solid black; background:silver;'>zip</th>
					<th style='border: 1px solid black; background:silver;'>town</th>
					<th style='border: 1px solid black; background:silver;'>country</th>
					<th style='border: 1px solid black; background:silver;'>email</th>
					<th style='border: 1px solid black; background:silver;'>amount</th> 	 	
					<th style='border: 1px solid black; background:silver;'>level</th> 	 	
					<th style='border: 1px solid black; background:silver;'>&nbsp;</th> 	 	
				</tr>";
			
			while($row = mysql_fetch_assoc($res)){
				
				$parts 	= explode("-",$row['who']);
				$time	= (int) $parts[0];
				$when 	= date("F j, Y",$time);
			
			
				$feedback .= "<tr>
					<td style='border: 1px solid black;'>$when</td>
					<td style='border: 1px solid black;'>$row[f_name]</td>
					<td style='border: 1px solid black;'>$row[l_name]</td>
					<td style='border: 1px solid black;'>$row[street]</td>
					<td style='border: 1px solid black;'>$row[state]</td>
					<td style='border: 1px solid black;'>$row[zip]</td>
					<td style='border: 1px solid black;'>$row[town]</td>
					<td style='border: 1px solid black;'>$row[country]</td>
					<td style='border: 1px solid black;'>$row[email]</td>
					<td style='border: 1px solid black;'>$row[amount]</td> 	 	
					<td style='border: 1px solid black;'>$row[level]</td> 	 	
					<td style='border: 1px solid black;'>
					<a href='index.php?approve=1&oid={$row[oid]}&who={$row[who]}'>
					Approve</a></td> 	 	
				</tr>";
				
				$feedback .= "<tr><td colspan='12'>";
				$feedback .= "&nbsp;";
				$feedback .= "</td></tr>";
				
				$feedback .= "<tr><td colspan='12'>";
				$feedback .= "<b>Items associated with this order:</b>";
				$feedback .= "</td></tr>";
			
				$sql2 = "SELECT * FROM $table2 WHERE who = '$row[who]'";
				$res2 = mysql_query($sql2);
				while($row2 = mysql_fetch_assoc($res2)){
				
					$feedback .= "<tr><td colspan='12'>";
					$feedback .= "{$row2[item_amount]}x $row2[item_id] &nbsp;&nbsp;<b class='item_name'>$row2[item_name]</b>  (single item price: $row2[item_price] USD)";
					$feedback .= "</td></tr>";
				}
				
			}
			$feedback .= "</table>";
			
			

			
		}
		else {
			$feedback .= "No records found.";
		}
	}

	if($_GET['approve'] == '1'){
		$oid 		= (int) $_GET['oid'];
		$table		= is_dbtable_there('orders');
		$who_parts 	= explode("-",$_GET['who']);
		
		$qStr = "
		UPDATE $table
		SET level='4', tracking_id='$who_parts[0]', order_time='$who_parts[0]'
		WHERE oid = $oid";

		mysql_query($qStr);
		header('Location: index.php?orderapproved=yes');
		exit(NULL);
	}
	
	?>
	
	<html>
		<head>
			<style type="text/css">
			html,body {font-family: "arial","verdana",sans-serif; font-size: 100.01%;}
			h1 {color:navy;}
			b.item_name {color: navy;}
			.approved {background: CCFFCC; border: 1px solid green;}
			</style>
			<title>Search for an transaction</title>
		</head>
		<body>
		
		
	<?php
	echo "<h1>Search for an transaction</h1>";
	
	if($_GET['orderapproved'] == 'yes'){
		$feedback = "<div class='approved'>The order was approved.</div>";
	}
	
	echo "
	$feedback	
	<br/><br/>Search for a transaction:<br/><br/>
	<form  action='index.php' method='post' >
		<select name='dbfield'>
			<option value='txn_id'>transaction ID</option>
			<option value='l_name'>lastname</option>
			<option value='email'>email</option>
			<option value='amount'>amount</option>
		</select>
		<input type='text' name='dbval' value='' size='70' maxlength='255' />
		<input type='hidden' name='search4record' value='1' />
		<input type='submit' name='submit' value='Query DB' />
	</form>
	";
		
?>
	</body>
	</html>