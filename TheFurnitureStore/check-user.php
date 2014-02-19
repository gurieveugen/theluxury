<?php
include '../../../wp-load.php';	

		$q 		= $_GET["q"];
		$len 	= strlen($q);

		if($len > 5)
		{
			$table 			= is_dbtable_there('feusers');
			$sql 			= "SELECT * FROM $table WHERE uname = '$q'";			
			$res 			= mysql_query($sql);
			$num 			= mysql_num_rows($res); 
		
			if($num > 0)
			{
				echo "<span class='failure'>".__('Username already taken.','wpShop')."</span>";
			}
			else
			{
				echo "<span class='success'>".__('Username ok.','wpShop')."</span>";
			}
		}
		else {
				echo "<span class='waiting'>".__('Username too short (minimum 6 characters)','wpShop')."</span>";
		}

?>