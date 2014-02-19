<?php 
$WPS_relatedOpen_tab 	= $OPTION['wps_relatedOpen_tab'];
$tagProdNum 			= $OPTION['wps_tag_relatedProds_num'];

$catProdNum				= $OPTION['wps_cat_relatedProds_num'];
$catProdOrder_by		= $OPTION['wps_cat_relatedProds_orderby'];
$catProdOrder			= $OPTION['wps_cat_relatedProds_order'];

$img_size 				= $OPTION['wps_ProdRelated_img_size'];
$resizedImg_src 		= $OPTION['upload_path'].'/cache';

$cat_related_output 	= NWS_cat_related_posts($catProdNum,$catProdOrder_by,$catProdOrder,$resizedImg_src,$img_size);

if($OPTION['wps_blogTags_option']) {
	$WPS_taxTerm = $OPTION['wps_term_relatedProds'];
	switch($WPS_taxTerm){
		case 'outfit_related':
			$taxTerm = 'outfit';
		break;
		case 'fit_related':
			$taxTerm = 'fit';
		break;
		case 'size_related':
			$taxTerm = 'size';
		break;
		case 'colour_related':
			$taxTerm = 'colour';
		break;
		case 'brand_related':
			$taxTerm = 'brand';
		break;
		case 'selection_related':
			$taxTerm = 'selection';
		break;
		case 'style_related':
			$taxTerm = 'style';
		break;
		case 'price_related':
			$taxTerm = 'price';
		break;
	}
	
	//check to see if the taxonomy exists first
	$taxonomy_exist = taxonomy_exists($taxTerm);
	if($taxonomy_exist) {
		$tag_related_output = NWS_tag_related_posts($tagProdNum,$resizedImg_src,$img_size,$taxTerm);
	
	} else {
		echo "<p class='error'>".__('It seems you have checked the option to use Tags for regular Blog Posts under ','wpShop')."<strong>".__('Theme Options > Design > General ','wpShop')."</strong>".__('so you now need to activate (Theme Options > Design > General - "Shop by" Options) one of the other available custom taxonomies and select that from the drop down list under ','wpShop')."<strong>".__('Theme Options > Design > Single Product Pages - Related Products Settings - Alternative Tag Related Products','wpShop')."</strong></p>";
	}
	
} else {
	$tag_related_output = NWS_tag_related_posts($tagProdNum,$resizedImg_src,$img_size);
}

if(($OPTION['wps_catRelatedProds_enable'] && $cat_related_output[status]) || ($OPTION['wps_tagRelatedProds_enable'] && $tag_related_output[status])){?>

	<div class="related noprint">
		<ul class="tabs">
			<?php switch($WPS_relatedOpen_tab){
				case 'tag_related_tab':
					if($OPTION['wps_tagRelatedProds_enable']) {
						if($tag_related_output[status]){ ?>
							<li><a href="#"><?php echo $OPTION['wps_tag_relatedProds'];?></a></li>
						<?php }
					}
					if($OPTION['wps_catRelatedProds_enable']) {
						if($cat_related_output[status]){ ?>
							<li><a href="#"><?php echo $OPTION['wps_cat_relatedProds'];?></a></li>
						<?php }
					}
				break;
				case 'cat_related_tab':
					if($OPTION['wps_catRelatedProds_enable']) {
						if($cat_related_output[status]){  ?>
							<li><a href="#"><?php echo $OPTION['wps_cat_relatedProds'];?></a></li>
						<?php }
					}
					if($OPTION['wps_tagRelatedProds_enable']) {
						if($tag_related_output[status]){ ?>
							<li><a href="#"><?php echo $OPTION['wps_tag_relatedProds'];?></a></li>
						<?php }
					}
				break;
			} ?>
		</ul>
					
		<div class="panes">
			<?php switch($WPS_relatedOpen_tab){
				case 'tag_related_tab':
					if($OPTION['wps_tagRelatedProds_enable']) {
						if($tag_related_output[status]){ ?>
							<div><?php echo $tag_related_output[html]; ?></div>
						<?php }
					}
					if($OPTION['wps_catRelatedProds_enable']) {
						if($cat_related_output[status]){ ?>
							<div><?php echo $cat_related_output[html]; ?></div>
						<?php }
					}
				break;
				case 'cat_related_tab':
					if($OPTION['wps_catRelatedProds_enable']) {
						if($cat_related_output[status]){?>
							<div><?php echo $cat_related_output[html]; ?></div>
						<?php }
					}
					if($OPTION['wps_tagRelatedProds_enable']) {
						if($tag_related_output[status]){ ?>
							<div><?php echo $tag_related_output[html]; ?></div>
						<?php }
					}
				break;
			} ?>
		</div>
	</div>				
<?php } ?>