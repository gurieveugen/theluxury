<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head profile="http://gmpg.org/xfn/11">
		<title><?php bloginfo('name'); wp_title('|'); get_page_number();  ?></title>
		<meta http-equiv="content-type" content="<?php bloginfo('html_type') ?>; charset=<?php bloginfo('charset') ?>" />	
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo 'https://'.substr(get_bloginfo('stylesheet_url'),7); ?>" />
		<link rel="stylesheet" type="text/css" media="print" href="<?php  echo 'https://'.substr(get_bloginfo('template_url'),7); ?>/css/print.css" />
	</head>
	<body <?php body_class();?>>
	<div id="pg_wrap">
		<div id="header" class="clearfix noprint">
			<div class="container clearfix">
				<!-- the logo -->
				<h1 id="branding">
				<a href="<?php bloginfo( 'url' );?>/" title="<?php bloginfo( 'name' ); ?>" rel="home">
				<?php bloginfo('name'); bloginfo( 'description' ); ?></a></h1>
				<!-- the logo -->
				
			</div><!-- container -->
		</div><!-- header-->

<?php
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