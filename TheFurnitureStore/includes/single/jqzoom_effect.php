<?php  
// was a video added?
if(!empty($videoMatches[1][0])) { 
	if($OPTION['wps_imagesTab_enable']) { ?>
		
		<ul class="thumbTabs videotabs noprint">
			<li><a class="imagesTab" href="#"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/Photo.png" alt="<?php _e('Product Image','wpShop'); ?>"/><?php echo $OPTION['wps_imagesTabText']; ?></a></li>
			<li><a class="videoTab" href="#"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/Video.png" alt="<?php _e('Product Video','wpShop'); ?>"/><?php echo $OPTION['wps_videoTabText']; ?></a></li>
		</ul><!-- imgtabs  -->
		
	<?php } ?>
	
	<div class="c_box v_box">
		<div class="contentWrap mediaPanes">
			<?php 
			if($OPTION['wps_imagesTab_enable']) { ?>
				<div class="theProdMedia innerProdMedia">
					<?php
					if($num != 0){
						if($num == 1){ 
							for($i=0,$a=1;$i<$num;$i++,$a++){
								$img_src 	= $data[$i]['guid'];
								$img_size 	= $OPTION['wps_singleProdMain1_img_size'];
								$des_src 	= $OPTION['upload_path'].'/cache';
								$img_file 	= mkthumb($img_src,$des_src,$img_size,'width');      
								$imgURL[] 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;
							} ?> 
							<a href="<?php echo $data[0]['guid']; ?>" class="jqZoom" title="<?php echo $data[0]['post_title']; ?>"><img src="<?php echo $imgURL[0];?>" alt="<?php echo $data[0]['post_title']; ?>"/></a>
						<?php } else {
							$err_message 	= __('Oops! Seems you have attached  more than 1 image. If you need multiple product images please consider using either the Magic Zoom Script or the Lightbox / FancyBox effect. You can of course skip the effects altogether if your prefer.','wpShop');									
							echo "<p class='error'>$err_message</p>";
						} 
					// no attachments? pull image from custom field
					} elseif(strlen(get_custom_field('image_thumb', FALSE))>0) { 
							
						$img_src 	= get_custom_field('image_thumb', FALSE);
						$img_size 	= $OPTION['wps_singleProdMain1_img_size'];
						$des_src 	= $OPTION['upload_path'].'/cache';							
						$img_file 	= mkthumb($img_src,$des_src,$img_size,'width');    
						$imgURL 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;
						 ?> 
							<a href="<?php echo $img_src; ?>" class="jqZoom" title="<?php echo wp_specialchars( get_the_title($post->ID), 1 ); ?>"><img src="<?php echo $imgURL;?>" alt="<?php echo wp_specialchars( get_the_title($post->ID), 1 ); ?>"/></a>
						<?php 	
					} ?>
				</div>
			<?php } 
			
			include (TEMPLATEPATH . '/includes/single/video.php'); ?>
		</div>
	</div><!-- c_box  -->
	
<?php } else {
	if($num != 0){
		if($num == 1){ 
			for($i=0,$a=1;$i<$num;$i++,$a++){
				$img_src 	= $data[$i]['guid'];
				$img_size 	= $OPTION['wps_singleProdMain1_img_size'];
				$des_src 	= $OPTION['upload_path'].'/cache';
				$img_file 	= mkthumb($img_src,$des_src,$img_size,'width');      
				$imgURL[] 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;
			}  ?>
			<div class="c_box">
				<div class="contentWrap"><a href="<?php echo $data[0]['guid']; ?>" class="jqZoom" title="<?php echo $data[0]['post_title']; ?>"><img src="<?php echo $imgURL[0];?>" alt="<?php echo $data[0]['post_title']; ?>"/></a></div>
			</div><!-- c_box  -->
		<?php } else {
			$err_message 	= __('Oops! Seems you have attached  more than 1 image. If you need multiple product images please consider using the Magic Zoom Script','wpShop');									
			echo "<p class='error'>$err_message</p>";
		}
	// no attachments? pull image from custom field
	} elseif(strlen(get_custom_field('image_thumb', FALSE))>0) { 
			
		$img_src 	= get_custom_field('image_thumb', FALSE);
		$img_size 	= $OPTION['wps_singleProdMain1_img_size'];
		$des_src 	= $OPTION['upload_path'].'/cache';							
		$img_file 	= mkthumb($img_src,$des_src,$img_size,'width');    
		$imgURL 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;?> 
		<div class="c_box">
			<div class="contentWrap"><a href="<?php echo $img_src; ?>" class="jqZoom" title="<?php echo wp_specialchars( get_the_title($post->ID), 1 ); ?>"><img src="<?php echo $imgURL;?>" alt="<?php echo wp_specialchars( get_the_title($post->ID), 1 ); ?>"/></a></div>
		</div><!-- c_box  -->
			
	<?php }	
} ?>