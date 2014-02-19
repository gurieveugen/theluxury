<?php
$icon_fileType = $OPTION['wps_icons_file_type']; 
// Product Navigation
$previousPost 		= get_previous_post(TRUE);
$nextPost 			= get_next_post(TRUE);

$img_size 			= $OPTION['wps_ProdRelated_img_size'];
$resizedImg_src 	= $OPTION['upload_path'].'/cache';

$WPS_previousPost 	= NWS_adjacentProd($previousPost,$resizedImg_src,$img_size);
$WPS_nextPost 		= NWS_adjacentProd($nextPost,$resizedImg_src,$img_size);

if (!empty($previousPost) || !empty($nextPost)) { ?>	
	
	<div class="prodNav noprint">
	
		<span id="previousHover" class="adjacentProd previousProd">
			<?php 
				NWS_previous_post_link('%link', '<img src="'.get_bloginfo('stylesheet_directory').'/images/arrow_left.'.$icon_fileType.'" alt="'.$OPTION['wps_prevProdLinkText'].'" /> '.$OPTION['wps_prevProdLinkText'].'', TRUE);
				echo $WPS_previousPost[html]; 
			?>
		</span>

	| 

		<span id="nextHover" class="adjacentProd nextProd">
			<?php 
				NWS_next_post_link('%link', ''.$OPTION['wps_nextProdLinkText'].' <img src="'.get_bloginfo('stylesheet_directory').'/images/arrow_right.'.$icon_fileType.'" alt="'.$OPTION['wps_nextProdLinkText'].'" />', TRUE);
				echo $WPS_nextPost[html]; 
			?>
		</span>
	
	</div><!--prodNav-->
<?php } ?>