<?php 
//** 
//The Products
//**

//what column option? Set counter
switch($WPS_prodCol){
	case 'prodCol1':
		$counter = 1;      
	break;
	case 'prodCol2':
		$counter = 2;      
	break;
	case 'prodCol3':
		$counter = 3;      
	break;
	case 'prodCol4':
		$counter = 4;      
	break;
}

$a = 1;

	// allow user to order their Products as the want to
	$orderBy 	= $OPTION['wps_prods_orderbyOption'];
	$order 		= $OPTION['wps_prods_orderOption'];

	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

	$posts_per_page = get_option('posts_per_page');
	$ppp = $_GET['ppp'];
	if (strlen($ppp)) {
		$_SESSION['prod_per_page'] = $ppp;
		if ($ppp == 'all') {
			$posts_per_page = -1;
		} else {
			$posts_per_page = (int)$ppp;
		}
	} else if (strlen($_SESSION['prod_per_page'])) {
		$posts_per_page = (int)$_SESSION['prod_per_page'];
	}

	$args = array(
		'cat' 				=> $cat,
		'orderby'			=> $orderBy,
		'order'				=> $order,
		'paged'				=> $paged,
		'posts_per_page' => $posts_per_page,
		'caller_get_posts' 	=> 1
	);

	$args = product_sort_process($args);
	$myModifiedQuery = new WP_Query($args);
	while ($myModifiedQuery->have_posts()) : $myModifiedQuery->the_post();

		$output = my_attachment_images(0,2);
		$imgNum = count($output);
		
		
		//set the class and resize the product image according to the column selection from the theme options 
		switch($WPS_prodCol){
			case 'prodCol1':
				$the_class 		= alternating_css_class($counter,1,' c_box_first');
				if($a==1) {$the_row_class='top_row';}else{$the_row_class='';}
				$the_div_class 	= 'c_box c_box1 '. $the_class .' '. $the_row_class;
				
				$img_size 		= $OPTION['wps_prodCol1_img_size'];
				
			break;
			
			case 'prodCol2':
				$the_class 		= alternating_css_class($counter,2,' c_box_first');
				if (($a==1) || ($a==2)) {$the_row_class='top_row';}else{$the_row_class='';}
				$the_div_class 	= 'c_box c_box2 '. $the_class .' '. $the_row_class;
				
				$img_size 		= $OPTION['wps_prodCol2_img_size'];
				
			break;
			
			case 'prodCol3':
				$the_class 		= alternating_css_class($counter,3,' c_box_first');
				if (($a==1) || ($a==2) || ($a==3)) {$the_row_class='top_row';}else{$the_row_class='';}
				$the_div_class 	= 'c_box c_box3 '. $the_class .' '. $the_row_class;
				
				$img_size 		= $OPTION['wps_prodCol3_img_size'];
				
			break;
			
			case 'prodCol4':
				$the_class 		= alternating_css_class($counter,4,' c_box_first');
				if (($a==1) || ($a==2) || ($a==3) || ($a==4)) {$the_row_class='top_row';}else{$the_row_class='';}
				$the_div_class 	= 'c_box c_box4 '. $the_class .' '. $the_row_class;
				
				$img_size 		= $OPTION['wps_prodCol4_img_size'];
				
			break;
		} 
		
		if($imgNum != 0){
			$imgURL		= array();
			foreach($output as $v){
			
				$img_src 	= $v;
				
				// do we want the WordPress Generated thumbs?
				if ($OPTION['wps_wp_thumb']) {
					//get the file type
					$img_file_type = strrchr($img_src, '.');
					//get the image name without the file type
					$parts = explode($img_file_type,$img_src);
					// get the thumbnail dimmensions
					$width = get_option('thumbnail_size_w');
					$height = get_option('thumbnail_size_h');
					//put everything together
					$imgURL[] = $parts[0].'-'.$width.'x'.$height.$img_file_type;
				
				// no? then display the default proportionally resized thumbnails
				} else {
					$des_src 	= $OPTION['upload_path'].'/cache';							
					$img_file 	= mkthumb($img_src,$des_src,$img_size,'width');    
					$imgURL[] 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;	
				}
		
			}
		}?>
		
			<div <?php post_class("$the_div_class"); ?>>
				<?php 
				include (TEMPLATEPATH . '/lib/pages/category_body.php'); ?>
			</div><!-- c_box  -->
		
		<?php
		
		// clear for nicely displayed rows :)
		switch($WPS_prodCol){
			case 'prodCol1':
				echo insert_clearfix($counter,1,' <div class="clear"></div>');
			break;
			
			case 'prodCol2':
				echo insert_clearfix($counter,2,' <div class="clear"></div>');
			break;
			
			case 'prodCol3':
				echo insert_clearfix($counter,3,' <div class="clear"></div>');
			break;
			
			case 'prodCol4':
				echo insert_clearfix($counter,4,' <div class="clear"></div>');
			break;
		}

		$counter++;
		$a++;

	endwhile; ?>
	<?php include (TEMPLATEPATH . '/products-nav.php'); ?>
	<?php wp_reset_query(); ?>
