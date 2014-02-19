<?php get_header();
// are we using a Blog?
$blog_Name 	= $OPTION['wps_blogCat'];

if ($blog_Name != 'Select a Category') {
	// template switch between Blog and Shop posts
	$blog_ID 	= get_cat_ID( $blog_Name );
	// our post belongs to this category...
	$categ_object = get_the_category();
	// who's our ancestor, blog or shop?
	if ((cat_is_ancestor_of( $blog_ID, (int)$categ_object[0]->term_id ))|| ($categ_object[0]->term_id ==$blog_ID)) {include(TEMPLATEPATH . "/includes/single/single-blog.php" );} else {include(TEMPLATEPATH . "/includes/single/single-shop.php" );}

} else { 
	include(TEMPLATEPATH . "/includes/single/single-shop.php" );
} ?>
