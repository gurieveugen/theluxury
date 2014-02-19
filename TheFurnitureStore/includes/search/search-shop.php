<?php
$WPS_tagCol			= $OPTION['wps_tagCol_option'];
$WPS_sidebar		= $OPTION['wps_sidebar_option'];

	switch($WPS_sidebar){
		case 'alignRight':
			$the_float_class 	= 'alignleft';
		break;
		case 'alignLeft':
			$the_float_class 	= 'alignright';
		break;
	}

	if($OPTION['wps_teaser_enable_option']) {$the_eqcol_class = 'eqcol'; }
	//which column option?
	switch($WPS_tagCol){
		case 'tagCol1':
			$the_div_class 	= 'theTags clearfix tagCol1 '.$the_float_class.' '.$the_eqcol_class;
			$counter = 1;      
		break;
		
		case 'tagCol2':
			$the_div_class 	= 'theTags clearfix tagCol2 '.$the_float_class.' '.$the_eqcol_class;
			$counter = 2;      
		break;
		
		case 'tagCol3':
			$the_div_class 	= 'theTags clearfix tagCol3 '.$the_float_class.' '.$the_eqcol_class;
			$counter = 3;      
		break;
			
		case 'tagCol4':
			$the_div_class 	= 'theTags clearfix tagCol4 '.$the_float_class.' '.$the_eqcol_class;
			$counter = 4;      
		break;
	}
	
	?>
	
	<div id="main_col" class="<?php echo $the_float_class;?>">
	
		<?php product_sort_select(); ?>
		<div class="<?php echo $the_div_class;?>" id="products-container">

			<?php //set the counter according to the column selection from the theme options
			$a = 1;

			// allow user to order their Products as the want to
			$orderBy = $OPTION['wps_prods_orderbyOption'];
			$order 	 = $OPTION['wps_prods_orderOption'];
			$paged   = (get_query_var('paged')) ? get_query_var('paged') : 1;

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
				'orderby'   => $orderBy,
				'order'     => $order,
				'paged'     => $paged,
				'posts_per_page' => $posts_per_page
			);

			$tax_query = array();
			$custom_taxs = array('category', 'brand', 'colour', 'style', 'price', 'selection', 'size', 'ring-size');
			foreach($custom_taxs as $custom_tax) {
				$ct_vars = $_GET['filter-'.$custom_tax];
				if (count($ct_vars)) {
					$tax_query[] = array(
						'taxonomy' => $custom_tax,
						'field' => 'slug',
						'terms' => $ct_vars
					);
				}
			}
			if (count($tax_query) > 0) {
				$tax_query['relation'] = 'AND';
				$args['tax_query'] = $tax_query;
			}
			$args = product_sort_process($args);					
			query_posts($args);		

			while (have_posts()) : the_post();
			
				$output = my_attachment_images(0,2);
				$imgNum = count($output);
				
				//set the class and resize the product image according to the column selection from the theme options 
				switch($WPS_tagCol){
					case 'tagCol1':
						$the_class 		= alternating_css_class($counter,1,' c_box_first');
						if($a==1) {$the_row_class='top_row';}else{$the_row_class='';}
						$the_div_class 	= 'c_box c_box1 '. $the_class .' '. $the_row_class;
						$img_size 		= $OPTION['wps_prodCol1_img_size'];
					break;
					
					case 'tagCol2':
						$the_class 		= alternating_css_class($counter,2,' c_box_first');
						if (($a==1) || ($a==2)) {$the_row_class='top_row';}else{$the_row_class='';}
						$the_div_class 	= 'c_box c_box2 '. $the_class .' '. $the_row_class;
						$img_size 		= $OPTION['wps_prodCol2_img_size'];
					break;
					
					case 'tagCol3':
						$the_class 		= alternating_css_class($counter,3,' c_box_first');
						if (($a==1) || ($a==2) || ($a==3)) {$the_row_class='top_row';}else{$the_row_class='';}
						$the_div_class 	= 'c_box c_box3 '. $the_class .' '. $the_row_class;
						$img_size 		= $OPTION['wps_prodCol3_img_size'];
					break;
															
					case 'tagCol4':
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
				} 
				?>
				
				<div <?php post_class("$the_div_class"); ?>>
					<?php include (TEMPLATEPATH . '/lib/pages/category_body.php'); ?>
				</div><!-- c_box  -->
				
			<?php 
			// clear for nicely displayed rows :)
			switch($WPS_tagCol){
				case 'tagCol1':
					echo insert_clearfix($counter,1,' <div class="clear"></div>');
				break;
				
				case 'tagCol2':
					echo insert_clearfix($counter,2,' <div class="clear"></div>');
				break;
				
				case 'tagCol3':
					echo insert_clearfix($counter,3,' <div class="clear"></div>');
				break;
				
				case 'tagCol4':
					echo insert_clearfix($counter,4,' <div class="clear"></div>');
				break;
			}
			$counter++;
			$a++;
			
			endwhile; ?>
			<?php include (TEMPLATEPATH . '/products-nav.php'); ?>
			<?php wp_reset_query(); ?>
		</div><!-- theTags -->
	</div><!-- main_col -->		
