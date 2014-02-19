<?php
##################################################################################################################################
// 	                                              Loading JS and OTHER HEADER STUFF
##################################################################################################################################
add_action('get_header', 'childtheme_queue_js');
add_action('wp_head', 'childtheme_head');
function childtheme_queue_js() {
	global $OPTION;
	// GW START
	wp_enqueue_script( 'mjs',get_stylesheet_directory_uri().'/js/mjs.js', '1', true );
	// GW END
	if (!is_admin())
	{
		if(($_SERVER['HTTPS'] == 'on')||($_SERVER['HTTPS'] == '1') || ($_SERVER['SSL'] == '1'))
		{
			$siteurl = NWS_bloginfo('stylesheet_directory');
			//load only if the NWS Recent Products widget is active and the scroll option in on!
			if (is_active_widget(false, false, 'nws-recent-products')  ) 
				wp_enqueue_script( 'jquery.tools.min2',$siteurl.'/js/jquery.tools.min2.js', array('jquery'), '1.2.5', true );
			else 
				wp_enqueue_script( 'jquery.tools.min',$siteurl.'/js/jquery.tools.min2.js', array('jquery'), '1.2.5', true );
		} else 
		{
			//load only if the NWS Recent Products widget is active and the scroll option in on!
			if (is_active_widget(false, false, 'nws-recent-products')  ) 
				wp_enqueue_script( 'jquery.tools.min2',get_stylesheet_directory_uri().'/js/xl_cuf_hel_tmin2_val.js', array('jquery'), '1.2.5', true );
			else 
				wp_enqueue_script( 'jquery.tools.min',get_stylesheet_directory_uri().'/js/xl_cuf_hel_tmin1_val.js', array('jquery'), '1.2.5', true );
			
			
			//load only on front page
			if (is_home()) 
			{ 
				$featuredEffect 	= $OPTION['wps_featureEffect_option'];
				switch($featuredEffect){
					case 'innerfade_effect':
						wp_enqueue_script( 'jquery_innerfade_all',get_stylesheet_directory_uri().'/js/jquery_innerfade_all.js', array('jquery'), '1', true );
					break;
					case 'Slider_effect':
						wp_enqueue_script( 'nws_slider_all',get_stylesheet_directory_uri().'/js/nws_slider_all.js', array('jquery'), '1', true );
					break;
					case 'cycle_effect':
						wp_enqueue_script( 'jquery_cycle_all',get_stylesheet_directory_uri().'/js/jquery_cycle_all.js', array('jquery'), '2.50', true );
					break;
				}
			}
			
			//load only on product single page
			if (is_single()) 
			{
				$WPS_prodImg_effect	= $OPTION['wps_prodImg_effect'];
				// are we using a Blog?
				$blog_Name 	= $OPTION['wps_blogCat'];
				
				if ($blog_Name != 'Select a Category') {
					
					$blog_ID 	= get_cat_ID( $blog_Name );
					// who's our ancestor, blog or shop?
					if (!(cat_is_ancestor_of( $blog_ID, (int)$category[0]->term_id )) || ($category[0]->term_id != $blog_ID)) {
						
						// do we want flowplayer?
						if($OPTION['wps_flowplayer_enable']) {
							wp_enqueue_script( 'flowplayer',get_stylesheet_directory_uri().'/js/flowplayer-3.1.4.min.js', '3.1.4', true );
						}
						
						switch($WPS_prodImg_effect){
							case 'mz_effect': 
								wp_enqueue_script( 'magiczoom',get_stylesheet_directory_uri().'/js/magiczoom.js', '1', true );
							break;
							
							case 'mzp_effect':
								wp_enqueue_style('magiczoomplus',get_stylesheet_directory_uri().'/js/magiczoomplus/magiczoomplus.css', false, '1.7.3', 'screen');
								wp_enqueue_script( 'magiczoomplus',get_stylesheet_directory_uri().'/js/magiczoomplus.js', '1.7.3', true );
							break;
										
							case 'jqzoom_effect':
								wp_enqueue_style('jqzoom',get_stylesheet_directory_uri().'/css/jqzoom.css', false, '1.0', 'screen');
								wp_enqueue_script('jqzoom.pack.1.0.1',get_stylesheet_directory_uri().'/js/jqzoom.pack.1.0.1.js', array('jquery'), '1.0.1', true );
								wp_enqueue_script('jqzoom_call',get_stylesheet_directory_uri().'/js/jqzoom_call.js', array('jquery'), '1', true );
							break;
							
							case 'lightbox':
								wp_enqueue_style('jquery.fancybox',get_stylesheet_directory_uri().'/js/jquery.fancybox/jquery.fancybox.css', false, '1.0', 'screen');
								wp_enqueue_script('jquery.fancybox-1.2.1.pack',get_stylesheet_directory_uri().'/js/jquery.fancybox/jquery.fancybox-1.2.1.pack.js', array('jquery'), '1.2.1', true );
								wp_enqueue_script('fancybox_call',get_stylesheet_directory_uri().'/js/fancybox_call.js', array('jquery'), '1', true );
							break;
										
							case 'no_effect':
							break;
						}
					} else {
						
					}
					
				} else {
				
					// do we want flowplayer?
					if($OPTION['wps_flowplayer_enable']) {
						wp_enqueue_script( 'flowplayer',get_stylesheet_directory_uri().'/js/flowplayer-3.1.4.min.js', '3.1.4', true );
					}
					
					switch($WPS_prodImg_effect){
						case 'mz_effect': 
							wp_enqueue_script( 'magiczoom',get_stylesheet_directory_uri().'/js/magiczoom.js', '1', true );
						break;
						
						case 'mzp_effect':
							wp_enqueue_style('magiczoomplus',get_stylesheet_directory_uri().'/js/magiczoomplus/magiczoomplus.css', false, '1.7.3', 'screen');
							wp_enqueue_script( 'magiczoomplus',get_stylesheet_directory_uri().'/js/magiczoomplus.js', '1.7.3', true );
						break;
									
						case 'jqzoom_effect':
							wp_enqueue_style('jqzoom',get_stylesheet_directory_uri().'/css/jqzoom.css', false, '1.0', 'screen');
							wp_enqueue_script('jqzoom.pack.1.0.1',get_stylesheet_directory_uri().'/js/jqzoom.pack.1.0.1.js', array('jquery'), '1.0.1', true );
							wp_enqueue_script('jqzoom_call',get_stylesheet_directory_uri().'/js/jqzoom_call.js', array('jquery'), '1', true );
						break;
						
						case 'lightbox':
							wp_enqueue_style('jquery.fancybox',get_stylesheet_directory_uri().'/js/jquery.fancybox/jquery.fancybox.css', false, '1.0', 'screen');
							wp_enqueue_script('jquery.fancybox-1.2.1.pack',get_stylesheet_directory_uri().'/js/jquery.fancybox/jquery.fancybox-1.2.1.pack.js', array('jquery'), '1.2.1', true );
							wp_enqueue_script('fancybox_call',get_stylesheet_directory_uri().'/js/fancybox_call.js', array('jquery'), '1', true );
						break;
									
						case 'no_effect':
						break;
					}
				}
			}
			
			wp_enqueue_script( 'myjquery', get_stylesheet_directory_uri().'/js/myjquery.js', array('jquery'), '1', true );
			 
		}
	}
}

/*
function childtheme_queue_js() {
	global $OPTION;
	
	// GW START
	wp_enqueue_script( 'mjs',get_stylesheet_directory_uri().'/js/mjs.js', '1', true );
	// GW END
	
	if (!is_admin()){
		if(($_SERVER['HTTPS'] == 'on')||($_SERVER['HTTPS'] == '1') || ($_SERVER['SSL'] == '1')){
			$siteurl = NWS_bloginfo('stylesheet_directory');
			wp_enqueue_script( 'cufon-yui',$siteurl.'/js/cufon-yui.js', array('jquery'), '1.09', true );
			wp_enqueue_script( 'Helvetica_Remake.font',$siteurl.'/js/Helvetica_Remake.font.js', array('jquery'), '1.02', true );
			
			//load only if the NWS Recent Products widget is active and the scroll option in on!
			if (is_active_widget(false, false, 'nws-recent-products')  ) {
				wp_enqueue_script( 'jquery.tools.min2',$siteurl.'/js/jquery.tools.min2.js', array('jquery'), '1.2.5', true );
			} else {
				wp_enqueue_script( 'jquery.tools.min',$siteurl.'/js/jquery.tools.min.js', array('jquery'), '1.2.5', true );
			}
			wp_enqueue_script( 'jquery.validate.pack',$siteurl.'/js/jquery.validate.pack.js', array('jquery'), '1.7', true );
			wp_enqueue_script( 'myjquery', $siteurl.'/js/myjquery.js', array('jquery'), '1', true );
		} else {
			wp_enqueue_script( 'cufon-yui',get_stylesheet_directory_uri().'/js/cufon-yui.js', array('jquery'), '1.09', true );
			wp_enqueue_script( 'Helvetica_Remake.font',get_stylesheet_directory_uri().'/js/Helvetica_Remake.font.js', array('jquery'), '1.02', true );
			
			//load only if the NWS Recent Products widget is active and the scroll option in on!
			if (is_active_widget(false, false, 'nws-recent-products')  ) {
				wp_enqueue_script( 'jquery.tools.min2',get_stylesheet_directory_uri().'/js/jquery.tools.min2.js', array('jquery'), '1.2.5', true );
			} else {
				wp_enqueue_script( 'jquery.tools.min',get_stylesheet_directory_uri().'/js/jquery.tools.min.js', array('jquery'), '1.2.5', true );
			}
			wp_enqueue_script( 'jquery.validate.pack',get_stylesheet_directory_uri().'/js/jquery.validate.pack.js', array('jquery'), '1.7', true );
			
			//load only on front page
			if (is_home()) { 
				$featuredEffect 	= $OPTION['wps_featureEffect_option'];
				switch($featuredEffect){
					case 'innerfade_effect':
						wp_enqueue_script( 'jquery.innerfade',get_stylesheet_directory_uri().'/js/jquery.innerfade.js', array('jquery'), '1', true );
						wp_enqueue_script('innerfade_call',get_stylesheet_directory_uri().'/js/innerfade_call.js', array('jquery'), '1', true );
					break;
					case 'Slider_effect':
						wp_enqueue_script( 'nws_slider',get_stylesheet_directory_uri().'/js/nws_slider.js', array('jquery'), '1', true );
						wp_enqueue_script('nws_slider_call',get_stylesheet_directory_uri().'/js/nws_slider_call.js', array('jquery'), '1', true );
					break;
					case 'cycle_effect':
						wp_enqueue_script( 'jquery.cycle.all.min',get_stylesheet_directory_uri().'/js/jquery.cycle.all.min.js', array('jquery'), '2.50', true );
						wp_enqueue_script('cycle_call',get_stylesheet_directory_uri().'/js/cycle_call.js', array('jquery'), '1', true );
					break;
				}
			}
			
			//load only on product single page
			if (is_single()) {
				$WPS_prodImg_effect	= $OPTION['wps_prodImg_effect'];
				// are we using a Blog?
				$blog_Name 	= $OPTION['wps_blogCat'];
				
				if ($blog_Name != 'Select a Category') {
					
					$blog_ID 	= get_cat_ID( $blog_Name );
					// who's our ancestor, blog or shop?
					if (!(cat_is_ancestor_of( $blog_ID, (int)$category[0]->term_id )) || ($category[0]->term_id != $blog_ID)) {
						
						// do we want flowplayer?
						if($OPTION['wps_flowplayer_enable']) {
							wp_enqueue_script( 'flowplayer',get_stylesheet_directory_uri().'/js/flowplayer-3.1.4.min.js', '3.1.4', true );
						}
						
						switch($WPS_prodImg_effect){
							case 'mz_effect': 
								wp_enqueue_script( 'magiczoom',get_stylesheet_directory_uri().'/js/magiczoom.js', '1', true );
							break;
							
							case 'mzp_effect':
								wp_enqueue_style('magiczoomplus',get_stylesheet_directory_uri().'/js/magiczoomplus/magiczoomplus.css', false, '1.7.3', 'screen');
								wp_enqueue_script( 'magiczoomplus',get_stylesheet_directory_uri().'/js/magiczoomplus.js', '1.7.3', true );
							break;
										
							case 'jqzoom_effect':
								wp_enqueue_style('jqzoom',get_stylesheet_directory_uri().'/css/jqzoom.css', false, '1.0', 'screen');
								wp_enqueue_script('jqzoom.pack.1.0.1',get_stylesheet_directory_uri().'/js/jqzoom.pack.1.0.1.js', array('jquery'), '1.0.1', true );
								wp_enqueue_script('jqzoom_call',get_stylesheet_directory_uri().'/js/jqzoom_call.js', array('jquery'), '1', true );
							break;
							
							case 'lightbox':
								wp_enqueue_style('jquery.fancybox',get_stylesheet_directory_uri().'/js/jquery.fancybox/jquery.fancybox.css', false, '1.0', 'screen');
								wp_enqueue_script('jquery.fancybox-1.2.1.pack',get_stylesheet_directory_uri().'/js/jquery.fancybox/jquery.fancybox-1.2.1.pack.js', array('jquery'), '1.2.1', true );
								wp_enqueue_script('fancybox_call',get_stylesheet_directory_uri().'/js/fancybox_call.js', array('jquery'), '1', true );
							break;
										
							case 'no_effect':
							break;
						}
					} else {
						
					}
					
				} else {
				
					// do we want flowplayer?
					if($OPTION['wps_flowplayer_enable']) {
						wp_enqueue_script( 'flowplayer',get_stylesheet_directory_uri().'/js/flowplayer-3.1.4.min.js', '3.1.4', true );
					}
					
					switch($WPS_prodImg_effect){
						case 'mz_effect': 
							wp_enqueue_script( 'magiczoom',get_stylesheet_directory_uri().'/js/magiczoom.js', '1', true );
						break;
						
						case 'mzp_effect':
							wp_enqueue_style('magiczoomplus',get_stylesheet_directory_uri().'/js/magiczoomplus/magiczoomplus.css', false, '1.7.3', 'screen');
							wp_enqueue_script( 'magiczoomplus',get_stylesheet_directory_uri().'/js/magiczoomplus.js', '1.7.3', true );
						break;
									
						case 'jqzoom_effect':
							wp_enqueue_style('jqzoom',get_stylesheet_directory_uri().'/css/jqzoom.css', false, '1.0', 'screen');
							wp_enqueue_script('jqzoom.pack.1.0.1',get_stylesheet_directory_uri().'/js/jqzoom.pack.1.0.1.js', array('jquery'), '1.0.1', true );
							wp_enqueue_script('jqzoom_call',get_stylesheet_directory_uri().'/js/jqzoom_call.js', array('jquery'), '1', true );
						break;
						
						case 'lightbox':
							wp_enqueue_style('jquery.fancybox',get_stylesheet_directory_uri().'/js/jquery.fancybox/jquery.fancybox.css', false, '1.0', 'screen');
							wp_enqueue_script('jquery.fancybox-1.2.1.pack',get_stylesheet_directory_uri().'/js/jquery.fancybox/jquery.fancybox-1.2.1.pack.js', array('jquery'), '1.2.1', true );
							wp_enqueue_script('fancybox_call',get_stylesheet_directory_uri().'/js/fancybox_call.js', array('jquery'), '1', true );
						break;
									
						case 'no_effect':
						break;
					}
				}
			}
			
			wp_enqueue_script( 'myjquery', get_stylesheet_directory_uri().'/js/myjquery.js', array('jquery'), '1', true );
			 
		}
	}
}

*/
function childtheme_head() {
	
	if (!is_admin()){
		
		//change.9.9
		if(($_SERVER['HTTPS'] == 'on')||($_SERVER['HTTPS'] == '1') || ($_SERVER['SSL'] == '1')){
		
			if(strpos(get_bloginfo('template_directory'),'https://') !== FALSE){
				$NWS_template_directory_alt = explode("https://", get_bloginfo('template_directory'));
				$NWS_template_directory		= 'https://' . $NWS_template_directory_alt[1];
			}
			else{
				$NWS_template_directory_alt = explode("http://", get_bloginfo('template_directory'));
				$NWS_template_directory		= 'https://' . $NWS_template_directory_alt[1];
			}
			//\change.9.9	
		} else {
			$NWS_template_directory_alt = explode('http://', get_bloginfo('template_directory'));
		} ?>
		
		<script type='text/javascript'>
			var NWS_template_directory 		= '<?php bloginfo('template_directory'); ?>';
			var NWS_template_directory_alt 	= '<?php echo $NWS_template_directory_alt[1]; ?>';
		</script>
		
		<link rel="shortcut icon" href="<?php NWS_bloginfo('stylesheet_directory','yes'); ?>/images/favicon.ico" />
		
		<!--[if IE]> 
			<link rel="stylesheet" media="all" type="text/css" href="<?php NWS_bloginfo('stylesheet_directory','yes'); ?>/main_ie.css" /> 
		<![endif]-->
		
		<!--[if lte IE 6]> 
			<link rel="stylesheet" media="all" type="text/css" href="<?php NWS_bloginfo('stylesheet_directory','yes'); ?>/main_lte_ie6.css" /> 
		<![endif]-->

	<?php }
}

##################################################################################################################################
// 	                                              FOOTER STUFF
##################################################################################################################################

add_action('wp_footer', 'NWS_footer');

function NWS_footer() {
	global $OPTION;
	// for the cufon font replacement
	$content = '<script type="text/javascript" src="'.NWS_bloginfo('stylesheet_directory').'/js/cufon-yui.js"></script>';
	$content .= '<script type="text/javascript">jQuery(document).ready(function() { Cufon.now(); });</script>';
	echo $content;
	
	//google analytics
	if(($OPTION['wps_google_analytics']) == 'active'){
		echo google_analytics($OPTION['wps_google_analytics_id']);
	}
	
	/**
	*
	// DO NOT TOUCH ANY OF THESE- OR ELSE....
	*
	**/
	dinfo_footer($OPTION['wps_support_id']);
	$_SESSION['go2page'] = get_real_base_url();
	if(ini_get('register_globals') === '1'){
		echo "<p class='error'>".__('Security warning: set the value *register_globals* in the php ini to *Off* !!','wpShop'); 
		echo " ";
		echo __('Contact your Host if you do not know where this is done!','wpShop');
		echo "</p>";
	}
	#echo "<pre>"; print_r($_SESSION); echo "</pre>";
	#echo "<pre>"; print_r($_POST); echo "</pre>";
	#echo "<pre>"; print_r($_SERVER); echo "</pre>";
	#echo "<pre>"; print_r($OPTION); echo "</pre>";
	#echo "<pre>"; print_r(session_get_cookie_params()); echo "</pre>";
}

// titles for filter taxonomy
function get_filter_titles() {
	global $wpdb;
	$filter_titles = '';
	$custom_taxs = array('category', 'brand', 'colour', 'style', 'price', 'selection', 'size');
	foreach($custom_taxs as $custom_tax) {
		if ($_GET['filter-'.$custom_tax]) {
			if (is_array($_GET['filter-'.$custom_tax])) {
				foreach($_GET['filter-'.$custom_tax] as $ctval) {
					$term_name = $wpdb->get_var(sprintf("SELECT name FROM %sterms WHERE slug = '%s'", $wpdb->prefix, $ctval));
					$filter_titles .= ' : '.$term_name;
				}
			} else {
				$term_name = $wpdb->get_var(sprintf("SELECT name FROM %sterms WHERE slug = '%s'", $wpdb->prefix, $_GET['filter-'.$custom_tax]));
				$filter_titles .= ' : '.$term_name;
			}
		}
	}
	return $filter_titles;
}