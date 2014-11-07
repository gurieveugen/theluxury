<?php
/*if(isset($_GET['showCart']) || (isset($_GET['non_cache']) && $_GET['non_cache'] == 'lang')){
	setcookie("wordpress_logged_in_eec_no_cache", "guest_no_cache", time()+10800, "/");
	if($_GET['non_cache'] == 'lang'){  
		$redirect_url .= get_bloginfo('template_url') . "/select-currency.php?return=".$_GET['return']."&code=".$_GET['code'];
		header("Location: " . $redirect_url);
	}
}*/

@Header('Cache-Control: no-cache');
@Header('Pragma: no-cache');

global $OPTION;

//if ( is_404() ) { wp_redirect( get_option('siteurl') . '/404error/'); exit(); }
include (STYLESHEETPATH . '/lib/pages/header_head.php');

// if a blog is used
$blog_Name 	= $OPTION['wps_blogCat'];
$blog_ID 	= get_cat_ID( $blog_Name );
if (is_page()) {
	$parentPage_title 	= get_the_title($post->post_parent);
	$currentPage_title 	= get_the_title($post->post_nicename);
	$page_seo_meta_title = get_post_meta($post->ID, '_page_seo_meta_title', true);
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
		if ($grandNum > 0) { $grandChildCats = TRUE; break; } 
		else { 	$grandChildCats = FALSE; 	}
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
			elseif ( is_category() || is_tag() || is_tax() ) { if (strlen($term_hTitle)) { echo $term_hTitle; } else { bloginfo('name'); } }
			elseif ( is_home() || is_front_page() ) { bloginfo('name'); print ' | '; bloginfo('description'); get_page_number(); }
			elseif ( is_page() ) { if (strlen($page_seo_meta_title)) { echo $page_seo_meta_title; } else { single_post_title(''); } }
			elseif ( is_search() ) { bloginfo('name'); print ' | Search results for ' . wp_specialchars($s); get_page_number(); }
			elseif ( is_404() ) { bloginfo('name'); print ' | Not Found'; }
			else { bloginfo('name'); wp_title('|'); get_page_number(); }
			?>
		</title>
		<meta http-equiv="content-type" content="<?php bloginfo('html_type') ?>; charset=<?php bloginfo('charset') ?>" />
		<?php 
		// SEO Optimized Meta Descriptions and Keywords 
		if (is_single()) {
			if (have_posts()) { the_post(); ?>
				<meta name="description" content="<?php the_excerpt_rss(); ?>" />
			<?php
			}
			if($OPTION['wps_tags_as_keywords']) {
				NWS_metaKeywordTags();
			}
		} elseif (is_page() ) {
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
		<link rel="stylesheet" type="text/css" media="all" href="<?php NWS_bloginfo('stylesheet_url', 'yes'); ?>?ver=<?php echo time(); ?>" />
		
		<?php 
		remove_action( 'wp_head', 'feed_links_extra'); // Display the links to the extra feeds such as category feeds
		remove_action( 'wp_head', 'feed_links'); // Display the links to the general feeds: Post and Comment Feed
		remove_action( 'wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
		remove_action( 'wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
		remove_action( 'wp_head', 'wp_shortlink_wp_head'); // Display the link to the Windows Live Writer manifest file.
		remove_action( 'wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
		wp_head(); 
		if(is_admin()){
			global $options;
			foreach ($options as $value) {
				if(!isset($value['id'])){$value['id'] 	= NULL;}		
				if(!isset($value['std'])){$value['std'] = NULL;}		
				if (get_settings( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } else { $$value['id'] = get_settings( $value['id'] ); }
			}
		}
		$split_categories = sellers_get_split_categories();
		?>
		<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('rss2_url'); ?>" title="<?php printf( __( '%s latest posts', 'wpShop' ), wp_specialchars( get_bloginfo('name'), 1 ) ); ?>" />
		<link rel="stylesheet" media="print" href="<?php bloginfo('template_url') ?>/css/print.css">
		<link rel="stylesheet" media="print" href="<?php bloginfo('template_url') ?>/css/jquery.mCustomScrollbar.css">
		<link href='http://fonts.googleapis.com/css?family=Cabin:400,600,700,400italic,700italic' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/jquery.mCustomScrollbar.css">
		<script type="text/javascript">
		var siteurl = "<?php bloginfo('url'); ?>";
		var templurl = "<?php echo TEMPLURL; ?>";
		var isloggedin = <?php if (is_user_logged_in()) { echo 'true'; } else { echo 'false'; } ?>;
		var currency_reload = <?php echo js_get_currency_reload(); ?>;
		var scat_type = "";
		var bags_cats = ["<?php echo implode('","', $split_categories['bags']); ?>"];
		var shoes_cats = ["<?php echo implode('","', $split_categories['shoes']); ?>"];
		var watches_cats = ["<?php echo implode('","', $split_categories['watches']); ?>"];
		var sunglasses_cats = ["<?php echo implode('","', $split_categories['sunglasses']); ?>"];
		var jewelry_cats = ["<?php echo implode('","', $split_categories['jewelry']); ?>"];
		var fapp_id = '<?php echo get_option('fbc_app_key_option'); ?>';
		var is_prof_add_item_page = '<?php if (is_page($OPTION['wps_profreseller_add_item_page'])) { echo 'true'; } ?>';
		var mcevilpopupclick = <?php echo (int)$_COOKIE['MCEvilPopupClick']; ?>;
		var utmz = '<?php if (strlen($_COOKIE['__utmz'])) { echo substr($_COOKIE['__utmz'], strrpos($_COOKIE['__utmz'], '.') + 1); } ?>';
		</script>
		<script type="text/javascript" src="<?php bloginfo('template_url') ?>/js/jquery-ui.min.js"></script>
		<script src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.colorbox-min.js"></script>
		<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/drop-down.js"></script>
		<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/doubletaptogo.js"></script>
		<script src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.mCustomScrollbar.concat.min.js"></script>
		<script src="<?php bloginfo('stylesheet_directory'); ?>/js/custom.js"></script>
		<script src="<?php bloginfo('template_url') ?>/js/jquery.jqtransform.js"></script>
		<script src="<?php bloginfo('template_url'); ?>/js/main.theme.js"></script>
		<script src="<?php bloginfo('template_url'); ?>/js/sellers-process.js"></script>
		<script src="<?php bloginfo('template_url'); ?>/js/alerts.js"></script>		
		<?php if (wp_is_mobile()) { ?>
		<script type="text/javascript">
		(function() {
			var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;
			po.src = "https://apis.google.com/js/plusone.js?publisherid=114474869893468804277";
			var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);
		})();
		</script>
		<?php } ?>
		<!--[if lt IE 10]>
			<script src="<?php bloginfo('stylesheet_directory'); ?>/js/PIE.js"></script>
			<script type="text/javascript">
				jQuery(function() {
					if (window.PIE) {
						jQuery('#mc_embed_signup form, #mc_embed_signup .button, .wp-pagenavi a, .wp-pagenavi .extend, .wp-pagenavi .current, .search-filter input[type="submit"], #nav .drop, .popup-login .buttons a, .popup-login, .popup-login input[type="submit"], .a-box .num i').each(function() {
							PIE.attach(this);
						});
					}
				});
			</script>
		<![endif]-->
		<script type="text/javascript">
		var ScarabQueue = ScarabQueue || [];
		(function(subdomain, id) {
		  if (document.getElementById(id)) return;
		  var js = document.createElement('script'); js.id = id;
		  js.src = subdomain + '.scarabresearch.com/js/1AA1DDA02D2AADC3/scarab-v2.js';
		  var fs = document.getElementsByTagName('script')[0];
		  fs.parentNode.insertBefore(js, fs);
		})('https:' == document.location.protocol ? 'https://recommender' : 'http://cdn', 'scarab-js-api');
		</script>
		<?php emarsys_script(); ?>
		<?php if (is_front_page()) { ?><!--<a href="https://plus.google.com/114474869893468804277" rel="publisher">Google+</a>--><?php } ?>
	</head>	
	<?php  
	// am I viewing the shopping cart? || going through checkout? || on confirmation page? || reading the terms and conditions? etc...
	if(is_cart_page()) { ?>
		<body class="shopping_cart">
	<?php } elseif (is_checkout_page()){ ?>
		<body class="shopping_cart order_checkout">
	<?php } elseif(($_GET['confirm'] == '1') || ($_GET['confirm'] == '2') || ($_GET['confirm'] == '3')) { ?>
		<body class="shopping_cart order_confirmation">
	<?php } elseif($_GET['showTerms'] == '1') { ?>
		<body class="shopping_cart terms_and_conditions">
	<?php } elseif($_GET['showMap'] == '1') { ?>
		<body class="shopping_cart map">
	<?php } elseif($_GET['checkOrderStatus'] == '1'){ ?>
		<body class="shopping_cart order_status">
	<?php } else { ?>
		<body <?php body_class(); if (is_single()) { echo " ".$script; } ?>>
	<?php } ?>
	<div id="fb-root"></div>
	<?php
	if(strripos(get_option('siteurl'),"localhost",0)) $apikey = "276284119066763"; 
	else if(strripos(get_option('siteurl'),"ancorps",0)) $apikey ="251447664896335";
	else $apikey ="250313664982898";
	if(!is_page("invite-friends")) { ?>
	<script>
	(function(d, s, id) {
	    var js, fjs = d.getElementsByTagName(s)[0];
	    if (d.getElementById(id)) {return;}
	    js = d.createElement(s); js.id = id;
	    js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?=$apikey?>";
	    fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
	</script>
	<?php } ?>
	<div id="wrapper">
		<div id="header" class="container clearfix noprint">
			<?php if (is_front_page()) { ?>
				<h1 id="branding"><a href="<?php bloginfo( 'url' );?>/" title="<?php bloginfo( 'name' ); ?>" rel="home"><?php bloginfo('name'); bloginfo( 'description' ); ?></a></h1>
			<?php } else { ?>
				<h2 id="branding"><a href="<?php bloginfo( 'url' );?>/" title="<?php bloginfo( 'name' ); ?>" rel="home"><?php bloginfo('name'); bloginfo( 'description' ); ?></a></h2>
			<?php } ?>
			<div class="header-center">
				<?php if (is_sidebar_active('header_widget_area')) : dynamic_sidebar('header_widget_area'); endif; ?>
				<?php if($OPTION['wps_search_input']) { ?>
				<form action="<?php bloginfo('url'); ?>/" class="search-form">
					<input type="text" name="s" id="s" value="<?php the_search_query(); ?>" placeholder="Search" />
					<input type="submit" value="Search" />
				</form>
				<?php } ?>
			</div>
			<?php include (STYLESHEETPATH . '/includes/headers/header_contents.php');?>
		</div><!-- header-->
		<div class="nav-holder">
			<?php 
			wp_nav_menu(array(
				'theme_location'  => 'primary_nav',
				'container'       => 'div',
				'container_class' => 'frame',
				'menu_id'         => 'nav',
				'walker'          => new Custom_Walker_Nav_Menu
			));
			?>
		</div>
		<div id="pg_wrap">
			<div class="center-content">
				<div class="header-banner"><?php dynamic_sidebar('banner-area'); ?></div>
				<?php switch($OPTION['wps_footer_option']) {
					case 'small_footer': ?>
						<div id="floatswrap" class="smallftfl clearfix">
					<?php
					break;
					case 'large_footer': ?>
						<div id="floatswrap" class="bigftfl clearfix">
					<?php
					break;
				} ?>
				<?php if (strlen($_SESSION['follow_brand_unsubscribe_msg'])) { ?>
					<div class="follow-message"><?php echo $_SESSION['follow_brand_unsubscribe_msg']; ?></div>
				<?php unset($_SESSION['follow_brand_unsubscribe_msg']); } ?>
				<!--<div class="container clearfix">-->
					<?php if( !is_page_template('page-sell-us.php') && !is_page_template('page-sell-item.php') && !is_page_template('page-what-happens-next.php') && !is_page_template('page-shop-1.php') && !is_page_template('page-shop-2.php') && !is_page_template('page-shop-3.php') && !is_page_template('page-shop-4.php') && !is_page_template('page-shop-5.php')): ?>
					<?php include( STYLESHEETPATH . "/includes/headers/page-titles.php" ); ?>
					<?php endif; ?>