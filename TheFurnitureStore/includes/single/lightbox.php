<?php 
$thumb_yes 	= $OPTION['wps_imgThumbs_enable'];
//image tabs or number tabs?
if($thumb_yes == TRUE) {$the_ul_class="thumbTabs imgTabs noprint"; } else { $the_ul_class="thumbTabs numTabs noprint";}
//do we have more than one images?
if(($num > 1) && (empty($videoMatches[1][0]))){ ?>
	<ul class="<?php echo $the_ul_class; ?>">
		<?php
		for($i=0,$a=1;$i<$num;$i++,$a++){
			$img_src 	= $data[$i]['guid'];
			$img_size 	= $OPTION['wps_singleProdMainMulti_img_size'];
			$des_src 	= $OPTION['upload_path'].'/cache';
			$img_file 	= mkthumb($img_src,$des_src,$img_size,'width');    
			$imgURL[] 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;	

			if($OPTION['wps_caption_enable']) {$title = $data[$i]['post_title'];}else {$title = '';}
			
			if($thumb_yes == TRUE) { 						
				// thumb is produced 
				$t_img_size = $OPTION['wps_singleProd_t_img_size'];
				$t_des_src 	= $OPTION['upload_path'].'/cache';
				$t_img_file = mkthumb($img_src,$t_des_src,$t_img_size,'width');    
				$t_imgURL[] = get_option('siteurl').'/'.$t_des_src.'/'.$t_img_file;		
				?>
				<li>
					<a class="thumbTab imgTab fancybox" rel="imgGroup" title="<?php echo $title; ?>" href="<?php echo $data[$i]['guid']; ?>"><img src="<?php echo $t_imgURL[$i];?>" alt="<?php echo $data[$i]['post_title']; ?>"/></a>
				</li>
			<?php } else { ?>
				<li><a class="thumbTab numTab fancybox" rel="imgGroup" title="<?php echo $title; ?>" href="<?php echo $data[$i]['guid']; ?>"><?php echo $a; ?></a></li>
			<?php } 
		} ?>
	</ul><!-- thumbTabs  -->
	
<?php } 
// was a video added?
elseif(!empty($videoMatches[1][0])) { 
	if($OPTION['wps_imagesTab_enable']) { ?>
		
		<ul class="thumbTabs videotabs noprint">
			<li><a class="imagesTab" href="#"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/Photo.png" alt="<?php _e('Product Images','wpShop'); ?>"/><?php echo $OPTION['wps_imagesTabText']; ?></a></li>
			<li><a class="videoTab" href="#"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/Video.png" alt="<?php _e('Product Video','wpShop'); ?>"/><?php echo $OPTION['wps_videoTabText']; ?></a></li>
		</ul><!-- thumbTabs  -->
		
	<?php } ?>
	
	<div class="c_box v_box">
		<div class="contentWrap mediaPanes">
			<?php 
			if($OPTION['wps_imagesTab_enable']) { ?>
				<div class="theProdMedia innerProdMedia clearfix">
					<?php
					//do we have more than one images?
					if($num > 1){ 
						//image tabs or number tabs?
						if($thumb_yes == TRUE) {$the_ul_class="inner_thumbTabs inner_imgtabs noprint"; } else { $the_ul_class="inner_thumbTabs inner_numtabs noprint";}?>
						
						<ul class="<?php echo $the_ul_class; ?>">
							<?php
							$thumb_yes 	= $OPTION['wps_imgThumbs_enable'];
							for($i=0,$a=1;$i<$num;$i++,$a++){
								$img_src 	= $data[$i]['guid'];
								$img_size	= $OPTION['wps_singleProdMainMulti_img_size'];
								$des_src 	= $OPTION['upload_path'].'/cache';
								$img_file 	= mkthumb($img_src,$des_src,$img_size ,'width');    
								$imgURL[] 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;			
								
								if($OPTION['wps_caption_enable']) {$title = $data[$i]['post_title'];}else {$title = '';}
								
								if($thumb_yes == TRUE) { 						
									// thumb is produced
									$t_img_size = $OPTION['wps_singleProd_t_img_size'];
									$t_des_src 	= $OPTION['upload_path'].'/cache';
									$t_img_file = mkthumb($img_src,$t_des_src,$t_img_size,'width');
									$t_imgURL[] = get_option('siteurl').'/'.$t_des_src.'/'.$t_img_file;		
									?>
									<li>
										<a class="thumbTab imgTab fancybox" rel="imgGroup" title="<?php echo $title; ?>" href="<?php echo $data[$i]['guid']; ?>"><img src="<?php echo $t_imgURL[$i];?>" alt="<?php echo $data[$i]['post_title']; ?>"/></a>
									</li>
								<?php } else { ?>
									<li><a class="thumbTab numTab fancybox" rel="imgGroup" title="<?php echo $title; ?>" href="<?php echo $data[$i]['guid']; ?>"><?php echo $a; ?></a></li>
								<?php } 
							} ?>
						</ul><!-- thumbTabs  -->
							<div class="inner_mediaPanes">
								<?php for($i=0;$i<$num;$i++){ 
									if($OPTION['wps_caption_enable']) {$title = $data[$i]['post_title'];}else {$title = '';} ?>
									<a class="fancybox theInner_ProdMedia" title="<?php echo $title; ?>" href="<?php echo $data[$i]['guid']; ?>" rel="imgGroup"><img src="<?php echo $imgURL[$i];?>" alt="<?php echo $data[$i]['post_title']; ?>"/></a>
								<?php } ?>
							</div>
					<?php } else {
						// do we have 1 attached image?
						if($num != 0){
							for($i=0,$a=1;$i<$num;$i++,$a++){
								$img_src 	= $data[$i]['guid'];
								$img_size 	= $OPTION['wps_singleProdMain1_img_size'];
								$des_src 	= $OPTION['upload_path'].'/cache';
								$img_file 	= mkthumb($img_src,$des_src,$img_size,'width');    
								$imgURL[] 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;
								
								if($OPTION['wps_caption_enable']) {$title = $data[$i]['post_title'];}else {$title = '';}
							}
						// no attachments? pull image from custom field
						} elseif(strlen(get_custom_field('image_thumb', FALSE))>0) { 
							
							$img_src 	= get_custom_field('image_thumb', FALSE);
							$img_size 	= $OPTION['wps_singleProdMain1_img_size'];
							$des_src 	= $OPTION['upload_path'].'/cache';							
							$img_file 	= mkthumb($img_src,$des_src,$img_size,'width');    
							$imgURL 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;
							
							if($OPTION['wps_caption_enable']) {$title = wp_specialchars( get_the_title($post->ID), 1 );} else {$title = '';}
						} 
						if($num != 0){?>
							<a class="fancybox theProdMedia" title="<?php echo $title; ?>" href="<?php echo $data[0]['guid']; ?>"><img src="<?php echo $imgURL[0];?>" alt="<?php echo $data[0]['post_title']; ?>"/></a>
						<?php } else { ?>
							<a class="fancybox theProdMedia" title="<?php echo $title; ?>" href="<?php echo $img_src; ?>"><img src="<?php echo $imgURL;?>" alt="<?php echo wp_specialchars( get_the_title($post->ID), 1 ); ?>"/></a>
						<?php } 
					} ?>
				</div>
			<?php } 
			
			include (TEMPLATEPATH . '/includes/single/video.php'); ?>
		</div>
	</div><!-- c_box  -->
	
<?php } else {
	if($num != 0){
		for($i=0,$a=1;$i<$num;$i++,$a++){
			$img_src 	= $data[$i]['guid'];
			$img_size 	= $OPTION['wps_singleProdMain1_img_size'];
			$des_src 	= $OPTION['upload_path'].'/cache';
			$img_file 	= mkthumb($img_src,$des_src,$img_size,'width');    
			$imgURL[] 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;
		}
	// no attachments? pull image from custom field
	} elseif(strlen(get_custom_field('image_thumb', FALSE))>0) { 
		
		$img_src 	= get_custom_field('image_thumb', FALSE);
		$img_size 	= $OPTION['wps_singleProdMain1_img_size'];
		$des_src 	= $OPTION['upload_path'].'/cache';							
		$img_file 	= mkthumb($img_src,$des_src,$img_size,'width');    
		$imgURL 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;
		
		if($OPTION['wps_caption_enable']) {$title = wp_specialchars( get_the_title($post->ID), 1 );} else {$title = '';}
	} 
} 

if(empty($videoMatches[1][0])) { ?>
	<div class="c_box">
		<?php  if($num > 1){ ?>
			<div class="contentWrap mediaPanes">
				<?php for($i=0;$i<$num;$i++){ 
				if($OPTION['wps_caption_enable']) {$title = $data[$i]['post_title'];}else {$title = '';}
				?>
					<a class="fancybox theProdMedia" title="<?php echo $title; ?>" href="<?php echo $data[$i]['guid']; ?>" rel="imgGroup"><img src="<?php echo $imgURL[$i];?>" alt="<?php echo $data[$i]['post_title']; ?>"/></a>
				<?php } ?>
			</div>
		<?php } else { 
			if($num != 0){ ?>
				<div class="contentWrap"><a class="fancybox theProdMedia" title="<?php echo $title; ?>" href="<?php echo $data[0]['guid']; ?>"><img src="<?php echo $imgURL[0];?>" alt="<?php echo $data[0]['post_title']; ?>"/></a></div>
			<?php } else { ?>
				<div class="contentWrap"><a class="fancybox theProdMedia" title="<?php echo $title; ?>" href="<?php echo $img_src ?>"><img src="<?php echo $imgURL;?>" alt="<?php echo wp_specialchars( get_the_title($post->ID), 1 ); ?>"/></a></div>
			<?php }
		} ?>
	</div><!-- c_box  -->
<?php } ?>