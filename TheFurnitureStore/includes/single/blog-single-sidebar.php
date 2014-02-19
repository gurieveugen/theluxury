<?php
switch($WPS_sidebar){
	case 'alignRight':
		$the_float_class 	= 'alignright';
	break;
	case 'alignLeft':
		$the_float_class 	= 'alignleft';
	break;
}

$the_div_class 	= 'sidebar blog_sidebar noprint '. $the_float_class; ?>

<div class="<?php echo $the_div_class;?>">
	<div class="padding">
		<?php 
		//display category related posts?
		if($OPTION['wps_blogCatRelated_posts_enable']) {
			$showposts 			= $OPTION['wps_blogCatRelated_num'];
			$cat_related_output = NWS_blogCat_related_posts($showposts);
			
			if($cat_related_output[status]){ ?>
				<div class="widget widget_related">
					<h3 class="widget-title"><?php echo $OPTION['wps_blogCatRelated_title'];?></h3>
					<ul class="related">
						<?php echo $cat_related_output[html]; ?>
					</ul>
				</div>
			<?php  }
		}
		
		//display tag  related posts?
		if($OPTION['wps_blogTagRelated_posts_enable']) {
			$showposts 			= $OPTION['wps_blogTagRelated_num'];
			$tag_related_output = NWS_blogTag_related_posts($showposts);
			
			if($tag_related_output[status]){ ?>
				<div class="widget widget_related">
					<h3 class="widget-title"><?php echo $OPTION['wps_blogTagRelated_title'];?></h3>
					<ul class="related">
						<?php echo $tag_related_output[html]; ?>
					</ul>
				</div>
			<?php  }
		}
		
		if ($OPTION['wps_blog_indSingle_sidebar_enable']) {
			if (is_sidebar_active('single_blog_widget_area') ) : dynamic_sidebar('single_blog_widget_area'); endif;	
		} else {
			if (is_sidebar_active('blog_category_widget_area') ) : dynamic_sidebar('blog_category_widget_area'); endif;	
		} ?>
	</div><!-- padding -->
</div><!-- category_sidebar -->