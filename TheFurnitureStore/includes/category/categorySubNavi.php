<?php 
// Sub Navigation for product categories for orientation. Brace yourself! (modify at your own risk)
//only run the foreach if categories were found
?>
<div class="SubCatNav">
	<ul class="viewAll">
		<?php if (!empty($childrenCats)) { ?>
			<li class="active"><a href="<?php echo get_category_link($this_category->term_id);?>"><?php _e('View All','wpShop');?></a></li>
		<?php #} elseif((empty($childrenCats)) && ($this_category->category_parent!=0)) { ?>
			<!--<li class="active"><a href="<?php echo get_category_link($this_category->term_id);?>"><?php _e('View All','wpShop');?></a></li>-->
		<?php } else { ?>
			<li><a href="<?php echo get_category_link($this_category->parent);?>"><?php _e('View All','wpShop');?></a></li>
		<?php } ?>
	</ul>
	
	<?php
	$orderby 	= $OPTION['wps_catSubNav_orderbyOption'];
	$order 		= $OPTION['wps_catSubNav_orderOption'];
	
	if (!empty($childrenCats)) {
		$cats = explode("<br />",substr(trim(wp_list_categories('title_li=&echo=0&orderby='.$orderby.'&order='.$order.'&child_of='.$this_category->term_id.'&hide_empty=0&style=none')),0,-6));
	#}elseif((empty($childrenCats)) && ($this_category->category_parent!=0)) {
		#$cats = explode("<br />",substr(trim(wp_list_categories('title_li=&echo=0&orderby='.$orderby.'&order='.$order.'&child_of='.$this_category->term_id.'&hide_empty=0&style=none')),0,-6));
	} else {
		$cats = explode("<br />",substr(trim(wp_list_categories('title_li=&echo=0&orderby='.$orderby.'&order='.$order.'&child_of='.$this_category->parent.'&hide_empty=0&style=none&depth=1')),0,-6));
	}	
	
	
	//only run the foreach if categories were found
	if (trim($cats[0]) != 'No cate') {  
		//how many rows?
		$wanted_rows	= $OPTION['wps_catSubNav_rows'];
		$num			= count($cats);
		$counter		= 1;			
		$container 		= '<ul>';
		
		foreach($cats as $cat){
			//set an active class on the current category				
			$parts1 = explode("</a>",$cat);						
			$parts2 = explode(">",$parts1[0]);
			$catnam	= trim($parts2[1]);	
			$the_li_class = ($this_category->name == $catnam ? 'active' : NULL);
			
			if($counter % $wanted_rows == 0){
				$container .= "<li class='$the_li_class'>".$cat .'</li></ul><ul>';					
			} else {
				$container .= "<li class='$the_li_class'>".$cat.'</li>';
			}
			
			$counter++;
		}
		wp_reset_query();
		
		$ending = substr($container,-4);
		if($ending == '<ul>'){
			$container = substr($container,0,-4);
		}
		
		echo $container;
	} ?>
</div><!--end "Sub Navigation" section-->