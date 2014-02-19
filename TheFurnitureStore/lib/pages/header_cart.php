<?php
$wps_shop_mode = $OPTION['wps_shop_mode'];
		if($OPTION['wps_br_yes']) {$connective="<br/>";} else {$connective=": ";}
		$basket =$OPTION['wps_pgNavi_cartOption'];
		$empty_message = "<span><img src='".get_bloginfo('stylesheet_directory')."/images/".$OPTION['wps_shopping_icon']."' alt='".$basket."'/>".$basket.$connective.__('0 items','wpShop')."</span>";
		 
		if(!isset($_GET['confirm'])){
			$CART = show_cart();
			if(is_array($CART) && $CART['status'] == 'filled'){
				$basket_url = get_option('home').'?showCart=1';
				echo "<a href='$basket_url'><img src='".NWS_bloginfo('stylesheet_directory').'/images/'.$OPTION['wps_shopping_icon']."' alt='".$OPTION['wps_pgNavi_cartOption']."'/>";
				
				if($CART['total_item_num'] == '1'){ $item = ' '.__('item','wpShop'); } else { $item = ' '.__('items','wpShop'); }
				
				echo $basket.$connective.$CART['total_item_num'].$item;

				if($OPTION['wps_totalItemValue_enable']) {
					echo "<br/>".__('Total','wpShop').": ";
					echo format_price($CART['total_price'] * $_SESSION['currency-rate'], true);
				}
				echo "</a>";
			} else {
				echo $empty_message;
			}
		} else {
			echo $empty_message;
		}
?>