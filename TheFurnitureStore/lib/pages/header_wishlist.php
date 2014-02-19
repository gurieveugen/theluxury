<?php
$WISHLIST 		= load_what_is_needed('wishlist');
$WISHL 			= $WISHLIST->show_wishlist();	

$customerArea	= get_page_by_title($OPTION['wps_customerAreaPg']); 

echo "<a href='".get_permalink($customerArea->ID)."?myaccount=1&action=3' title='". __('You have ','wpShop');

	if($WISHL[status] == 'filled'){
		if($WISHL[total_item_num] == '1'){
			$t = str_replace("%w",$OPTION['wps_wishListLink_option'], __(' item in your %w ','wpShop'));
			echo $WISHL[total_item_num].$t;  
		}
		else {
			$t = str_replace("%w",$OPTION['wps_wishListLink_option'], __(' items in your %w ','wpShop'));
			echo $WISHL[total_item_num].$t; 
		}
	} else {
		$t = str_replace("%w",$OPTION['wps_wishListLink_option'], __('0 items in your %w ','wpShop'));
		echo $t; 
	}
	
echo "' >".$OPTION['wps_wishListLink_option']."</a>";
?>