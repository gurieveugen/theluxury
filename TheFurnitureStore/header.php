<?php
session_start();
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');
if ( is_404() )
{
	wp_redirect( get_option('siteurl') . '/prelaunch/');
	exit();
}
include (TEMPLATEPATH . '/lib/pages/header_head.php');
$OPTION = NWS_get_global_options();

// if a blog is used
$blog_Name 	= $OPTION['wps_blogCat'];
$blog_ID 	= get_cat_ID( $blog_Name );

if (is_page()) {
	$parentPage_title 	= get_the_title($post->post_parent);
	$currentPage_title 	= get_the_title($post->post_nicename);
}
if (is_category()) {
	$this_category 		= get_category($cat);
	$topParent 			= NWS_get_root_category($cat,'allData');
	$topParentSlug 		= $topParent->slug;
	$this_categorySlug 	= $this_category->slug;

	//collect options
	$orderBy 	= $OPTION['wps_secondaryCat_orderbyOption'];
	$order 		= $OPTION['wps_secondaryCat_orderOption'];
	//collect the child categories
	$childrenCats 	= get_terms('category', 'orderby='.$orderBy.'&order='.$order.'&parent='.$this_category->term_id.'&hide_empty=0');
	//collect the grandchild categories
	foreach ($childrenCats as $childrenCat) {
		$currentChildCat 	= $childrenCat->term_id;
		$grandChildCats = get_terms('category', 'orderby='.$orderBy.'&order='.$order.'&parent='.$currentChildCat.'&hide_empty=0');
		$grandNum = count($grandChildCats);
		if ($grandNum > 0) {
			$grandChildCats = TRUE;
		break;
		} else {
			$grandChildCats = FALSE;
		}
	}
}

//this is for the SEO Optimized category titles		
if(is_category() || is_tag() || is_tax()) {
	//get queried object
	$post_obj = $wp_query->get_queried_object();
	//get the array from options table
	$current_term_hTitle = get_option( 'term_hTitle' );
	//check if the taxonomy has a title saved in the database
	if ( is_array( $current_term_hTitle ) && array_key_exists( $post_obj->term_id, $current_term_hTitle ) ) {
		$term_hTitle = $current_term_hTitle[$post_obj->term_id];
		$term_hTitle = stripslashes($term_hTitle);
	}
	
	//get the array from options table
	$current_term_keywords = get_option( 'term_keywords' );
	//check if the taxonomy has a title saved in the database
	if ( is_array( $current_term_keywords ) && array_key_exists( $post_obj->term_id, $current_term_keywords ) ) {
		$term_keywords = $current_term_keywords[$post_obj->term_id];
		$term_keywords = stripslashes($term_keywords);
	}
}

if (is_single()) {
	global $post;

	$category 			= get_the_category($post->ID);
	$topParent_cat 		= get_post_top_parent();
	$topParent_catSlug 	= get_cat_slug($topParent_cat);
	$Parent_cat 		= get_parent_cat_id();
	$Parent_catSlug 	= get_cat_slug($Parent_cat);
}

if (is_attachment()) {
	$category 	= get_the_category($post->post_parent);
	$topParent 	= NWS_get_root_category($category[0]->term_id,'allData');
}

// sidebar location?
$WPS_sidebar		= $OPTION['wps_sidebar_option'];
switch($WPS_sidebar){
	case 'alignRight':
		$the_float_class 	= 'alignleft';
	break;
	case 'alignLeft':
		$the_float_class 	= 'alignright';
	break;
}

if ($OPTION['wps_customerAreaPg']!='Select a Page') {
$customerArea	= get_page_by_title(get_option('wps_customerAreaPg'));
}
$lostPass	= get_page_by_title($OPTION['wps_passLostPg']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head profile="http://gmpg.org/xfn/11">
		<title>
			<?php
			// SEO Optimized Titles
			if ( is_single() ) { single_post_title(); }
			elseif(is_category() || is_tag() || is_tax()) {  bloginfo('name'); print ' | '; echo $term_hTitle;}				
			elseif ( is_home() || is_front_page() ) { bloginfo('name'); print ' | '; bloginfo('description'); get_page_number(); }
			elseif ( is_page() ) { single_post_title(''); }
			elseif ( is_search() ) { bloginfo('name'); print ' | Search results for ' . wp_specialchars($s); get_page_number(); }
			elseif ( is_404() ) { bloginfo('name'); print ' | Not Found'; }
			else { bloginfo('name'); wp_title('|'); get_page_number(); }
			?>
		</title>
		
		<meta http-equiv="content-type" content="<?php bloginfo('html_type') ?>; charset=<?php bloginfo('charset') ?>" />
		
		<?php 
		// SEO Optimized Meta Descriptions and Keywords
		if (is_single() || is_page() ) {
		
			if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<meta name="description" content="<?php the_excerpt_rss(); ?>" />
				<?php 
				//SEO Optimized Meta Keywords for Single Posts
				if($OPTION['wps_tags_as_keywords']) {
					NWS_metaKeywordTags(); 
				}
			endwhile; endif; 
			
		} elseif(is_home()) { ?>
		
			<meta name="description" content="<?php bloginfo('description'); ?>" />
			<meta name="keywords" content="<?php echo $OPTION['wps_keywords'];?>" />

		<?php } elseif(is_category() || is_tag() || is_tax() ) { ?>
			<meta name="description" content="<?php echo wp_specialchars( strip_tags( term_description() ), 1 );?> " />
			<meta name="keywords" content="<?php echo $term_keywords;?>" />
		<?php } ?>
		
		<?php if(is_search()) { ?>
			<meta name="robots" content="noindex, nofollow" /> 
	    <?php }?>
		<?php if(is_archive() && !is_category()){ ?><meta name="robots" content="noindex" /><?php } ?>
	
		<link rel="stylesheet" type="text/css" media="all" href="<?php NWS_bloginfo('stylesheet_url','yes'); ?>" />
		<link rel="stylesheet" type="text/css" media="print" href="<?php NWS_bloginfo('template_url','yes'); ?>/css/print.css" />
		
		<?php
			wp_head(); 
			if(is_admin()){
				global $options;
				foreach ($options as $value) {
					if(!isset($value['id'])){$value['id'] 	= NULL;}		
					if(!isset($value['std'])){$value['std'] = NULL;}		
					if (get_settings( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } else { $$value['id'] = get_settings( $value['id'] ); }
				}
			}
		?>
		<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('rss2_url'); ?>" title="<?php printf( __( '%s latest posts', 'wpShop' ), wp_specialchars( get_bloginfo('name'), 1 ) ); ?>" />
        <link rel="alternate" type="application/rss+xml" href="<?php bloginfo('comments_rss2_url') ?>" title="<?php printf( __( '%s latest comments', 'wpShop' ), wp_specialchars( get_bloginfo('name'), 1 ) ); ?>" />
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
		<link rel="stylesheet" href="<?php bloginfo('template_url') ?>/style-pages.css" />
	</head>	
	<?php 
	// am I viewing the shopping cart? || going through checkout? || on confirmation page? || reading the terms and conditions? etc...
	if(is_cart_page()) { ?>
		<body class="shopping_cart">
	<?php } elseif (($_GET['orderNow'] == '1') || ($_GET['orderNow'] == '2') || ($_GET['orderNow'] == '3') || ($_GET['orderNow'] == '4') || ($_GET['orderNow'] == '5') || ($_GET['orderNow'] == '6') || ($_GET['orderNow'] == '7') || ($_GET[orderNow] == '8') || ($_GET[orderNow] == '81')){ ?>
		<body class="shopping_cart order_checkout">
	<?php } elseif(($_GET['confirm'] == '1') || ($_GET['confirm'] == '2') || ($_GET['confirm'] == '3')) { ?>
		<body class="shopping_cart order_confirmation">
	<?php } elseif($_GET['showTerms'] == '1') { ?>
		<body class="shopping_cart terms_and_conditions">
		<?php } elseif($_GET['showMap'] == '1') { ?>
		<body class="shopping_cart map">
		<?php } elseif($_GET['checkOrderStatus'] == '1'){
		?>
		<body class="shopping_cart order_status">
		
	<?php } else { ?>
		<body <?php body_class(); if (is_single()) { echo " ".$script; } ?>>
	<?php } ?>
	
	<div id="pg_wrap">
		<div id="header" class="clearfix noprint">
			<div class="container clearfix">
				<?php include (TEMPLATEPATH . '/includes/headers/header_contents.php');?>
			</div><!-- container -->
		</div><!-- header-->
		
		
		<div id="myloginoverlay" class="overlay mediumoverlay">
			<h2><?php _e('Login to your Account','wpShop');?></h2>	
			<p><?php _e('Do not have an account yet?','wpShop');?> <a href="<?php echo get_permalink($accountReg->ID); ?>"><?php _e('Create one','wpShop');?></a></p>
			<form id="quickLoginForm" method="post" action="<?php echo get_bloginfo('template_url') . '/login.php'; ?>">
				<fieldset>
					<label for="quicksignInUsername"><?php _e('Username','wpShop');?></label>
					<input type="text" name="signInUsername" id="quicksignInUsername" size="35" maxlength="10" value="<?php echo $_SESSION['uname']; ?>" />
					<label for="quicksignInPassword"><?php _e('Password','wpShop');?></label>
					<input id="quicksignInPassword" type="password" size="35" maxlength="8" value="" name="signInPassword"/>
					<span class="passhelp"> <?php _e('I lost my password. Please','wpShop');?> <a href="<?php echo get_permalink($lostPass->ID); ?>"><?php _e('email it to me','wpShop');?></a></span>
					
					<?php
					if(is_cart_page() || is_checkout_page()){
						$slash 		= (substr(get_real_base_url(),-1,1) == '/' ? '?' : '/?');
						$urlAdd 	= $_SERVER['REQUEST_URI'];
						$urlParts 	= explode("?",$urlAdd);
						$urlAdd		=  $slash . $urlParts[1];
						if(strpos($urlParts[1],'&') !== FALSE){
							$urlParts	= explode("&",$urlParts[1]);
							$urlAdd 	= $slash . $urlParts[0];
						}
					}
					else {
						$urlAdd = NULL;
					}
					?>
					<input type='hidden' name='gotoURL' value='<?php echo get_real_base_url().$urlAdd; ?>' />
					<input class="formbutton" type="submit" alt="<?php _e('Sign in','wpShop');?>" value="<?php _e('Sign in','wpShop');?>" name="" title="<?php _e('Sign in','wpShop');?>" />
				</fieldset>
			</form>
		</div><!-- myloginoverlay -->
		
		<div id="searchoverlay" class="overlay mediumoverlay">
			<h3><?php echo $OPTION['wps_search_title']; ?></h3>
			<p><?php echo $OPTION['wps_search_text']; ?></p>
			<?php //include (TEMPLATEPATH . '/headerSearchform.php'); ?>
			<div class="extLoadWrap">
			</div>
		</div><!-- searchoverlay -->
		<?php dynamic_sidebar('banner-area'); ?>
		
<?php 
$term 				= get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
$customTax 			= $term->taxonomy;

switch($OPTION['wps_footer_option']){
	case 'small_footer': ?> 
		<div id="floatswrap" class="smallftfl clearfix">
	<?php	       					
	break;
								
	case 'large_footer': ?> 
		<div id="floatswrap" class="bigftfl clearfix">
		<?php 
	break;
} ?>
	<div class="container clearfix">
		<?php include( TEMPLATEPATH . "/includes/headers/page-titles.php" );?>