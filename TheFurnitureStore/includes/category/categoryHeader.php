
	<?php 
	$imgSrc = get_option('siteurl').'/'.$OPTION['upload_path'].'/'.$this_category->slug.'.'.$OPTION['wps_catimg_file_type'];
	if (file_exists($_SERVER["DOCUMENT_ROOT"].'/'.$OPTION['upload_path'].'/'.$this_category->slug.'.'.$OPTION['wps_catimg_file_type'])) {
	?>
	<div class="featuredCat">
		<img src="<?php echo $imgSrc; ?>" alt="<?php echo $this_category->name; ?>" />
	</div>
	<?php } ?>
