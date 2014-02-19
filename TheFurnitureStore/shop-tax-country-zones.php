<?php
include '../../../wp-load.php';
get_currentuserinfo();
global $user_level;

if(!is_user_logged_in()) {
	$url = get_option('siteurl') . '/wp-login.php';
	header("Location: $url");exit();
}

// here is our zone update action 
if(isset($_POST['update_zones'])){

	unset($_POST['update_zones']);
	$table 	= is_dbtable_there('countries');
				
	foreach($_POST as $k => $v){				
		$qStr = "UPDATE $table SET tax_zone = '$v' WHERE abbr = '$k'";			
		mysql_query($qStr);
	}
}
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
	<html xmlns='http://www.w3.org/1999/xhtml' <?php language_attributes(); ?>>

	<head profile='http://gmpg.org/xfn/11'>	
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link media="all" type="text/css" href="<?php bloginfo('template_url'); ?>/css/shop_admin.css" rel="stylesheet"></link>
	<title><?php echo $CONFIG_WPS[themename]; ?> :: <?php _e('Shop-Backend','wpShop');?> :: <?php _e('Statistics','wpShop');?></title>
	</head>
	<body class="wp-admin">
	<form action="" method="post">
	<table>	
		<?php
		
		$output = get_countries(4);
		$i		= 1;
		$zone1	= $OPTION['wps_shop_country'];	
			
		foreach($output as $v){
			
			$color = ($i % 2 == 0 ? '#fff':'#C9FDD5');	
			$parts = explode("|",$v);
			
			$selected		= array();
			$selected[0] 	= NULL;
			$selected[2] 	= NULL;
			$selected[3] 	= NULL;
			$selected[4] 	= NULL;
			$selected[5] 	= NULL;
			$selected[6] 	= NULL;
			
			$selected[$parts[3]] = "selected='selected'";
				
			echo "<tr style='background: $color;'><td>$parts[0]</td><td>";
			
			if($parts[1] == $zone1){
				echo "<b>Zone 1</b>";
			}
			else {
				echo "
				<select name='$parts[1]'>			
					<option value='0' $selected[0]>no delivery</option>
					<option value='2' $selected[2]>Zone 2</option>
					<option value='3' $selected[3]>Zone 3</option>
					<option value='4' $selected[4]>Zone 4</option>
					<option value='5' $selected[5]>Zone 5</option>
					<option value='6' $selected[6]>Zone 6</option>
				</select>
				";
				}
			echo "</td></tr>";
			$i++;
		}	
		?>
		</table>
		<br/>
		<input type="submit" name="update_zones" value="Update zones"/>
		</form>
	
	</body>
	</html>