<?php 
//** 
//The Categories
//**

//what cat column option? Set counter
switch($WPS_catCol){
	case 'catCol3':
		$counter = 3;  
	break;
		
	case 'catCol4':
		$counter = 4;  
	break;
}


//set the counter according to the column selection from the theme options
$a = 1;

foreach ($childrenCats as $childrenCat) {
	//set the class and resize the category image according to the column selection from the theme options 
	switch($WPS_catCol){
		case 'catCol3':
			$the_class 		= alternating_css_class($counter,3,' c_box_first');
			$catSlugClass 	= $childrenCat->slug;
			$catIDClass 	= $childrenCat->term_id;
			
			if (($a==1) || ($a==2) || ($a==3)) {$the_row_class='top_row';}else{$the_row_class='';}
			$the_div_class 	= 'c_box c_box3 '. $the_class .' '. $the_row_class.' '. $catSlugClass.' ID_'. $catIDClass;
			
			$img_size 		= $OPTION['wps_prodCol3_img_size' ];
			
		break;
		
		case 'catCol4':
			$the_class 		= alternating_css_class($counter,4,' c_box_first');
			if (($a==1) || ($a==2) || ($a==3) || ($a==4)) {$the_row_class='top_row';}else{$the_row_class='';}
			$the_div_class 	= 'c_box c_box4 '. $the_class .' '. $the_row_class.' '. $catSlugClass.' ID_'. $catIDClass;
			
			$img_size 		= $OPTION['wps_prodCol4_img_size'];
			
		break;
	} 
	
	$img_src 	= get_option('siteurl').'/'.$OPTION['upload_path'].'/'.$childrenCat->slug.'.'.$OPTION['wps_catimg_file_type'];
	$des_src 	= $OPTION['upload_path'].'/cache';						
	$img_file 	= mkthumb($img_src,$des_src,$img_size,'width'); 
	$imgURL 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;	
	?>
	
	<div class="<?php echo $the_div_class; ?>">
		<div class="contentWrap">
			<div class="holder">
				<?php if ($img_file != 'error') {?>
					<a href="<?php echo get_category_link($childrenCat->term_id);?>">
						<img src="<?php echo $imgURL; ?>" alt="<?php echo $childrenCat->name; ?>" />
					</a>
				<?php  } else { ?>
					<p class="error">
						<?php _e('Oops! No Category Specific Image was found. Please create one, save it after the category slug and upload it inside your "uploads" folder. Make sure also that the folder\'s permissions are set to 777!','wpShop'); ?><br/>
					</p>
				<?php } ?>	
				<?php  if($OPTION['wps_catTeaser_enable']) { ?>
			</div>
			<div class="teaser">
				<h5 class="cat-title"><?php echo $childrenCat->name; ?></h5> 
				<?php echo category_description($childrenCat->term_id); ?>	
			</div><!-- teaser  -->
			<?php  } ?>
			
		</div><!-- contentWrap  -->
		
		<?php  if($OPTION['wps_catTitle_enable']) { ?>
			<h5 class="single_cat_title"><?php echo $childrenCat->name; ?></h5> 
		<?php  } ?>
		
	</div><!-- c_box  -->
	
<?php 
// clear for nicely displayed rows :)
switch($WPS_catCol){
	case 'catCol3':
		echo insert_clearfix($counter,3,' <div class="clear"></div>');
	break;
	
	case 'catCol4':
		echo insert_clearfix($counter,4,' <div class="clear"></div>');
	break;
}

$counter++;
$a++;

}

?>