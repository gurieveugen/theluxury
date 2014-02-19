<?php get_header();
$category 	= get_the_category($post->post_parent);  

// are we using a Blog?
$blog_Name 	= $OPTION['wps_blogCat'];

if ($blog_Name != 'Select a Category') {
	// template switch between Blog and Shop attachments
	$topParent 			= NWS_get_root_category($category[0]->term_id,'allData');
	
	// who's our ancestor, blog or shop? First check for root category.
	if (($category->name == $blog_Name) ||($topParent->name == $blog_Name)) {
		include(TEMPLATEPATH . "/includes/single/blog-attachment.php" );
	} else {
		include( TEMPLATEPATH . "/includes/single/shop-attachment.php" );
	}
} else {
	include( TEMPLATEPATH . "/includes/single/shop-attachment.php" );
} ?>