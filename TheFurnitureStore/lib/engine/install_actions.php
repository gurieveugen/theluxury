<?php
class folioShopInstall {

	function set_correct_uploadPath(){

		delete_option('upload_path');
		delete_option('uploads_use_yearmonth_folders');

		add_option('upload_path','wp-content/uploads');
		add_option('uploads_use_yearmonth_folders',0);
		
	return 'DONE';
	}
	
	
function theme_install($install_status,$shopID,$featuredID){

	switch($install_status){
	
		case 0:
		$this->set_correct_uploadPath();
		
		echo "
			<h3 class='instWizSubheading'>".__('Please check your directory and file permissions','wpShop')."</h3>
			<p>".__('The following folders and files must be made writable while the theme is being set up, so please change their permissions to 777.','wpShop')."<br/>".__('Once done, click on the "Check permissions again" link bellow.','wpShop')."</p>
		";
		$writablePaths 		= array();
		$writablePaths[0]	= '../wp-content/uploads/#../wp-content/uploads/#DIR';
		$writablePaths[1]	= '../wp-content/uploads/cache/#../wp-content/uploads/cache/#DIR';
		$writablePaths[2]	= '../wp-content/themes/'.WPSHOP_THEME_NAME.'/dl/#..wp-content/themes/'.WPSHOP_THEME_NAME.'/dl/#DIR';
		$writablePaths[3]	= '../wp-content/themes/'.WPSHOP_THEME_NAME.'/images/vouchers/#..wp-content/themes/'.WPSHOP_THEME_NAME.'/images/vouchers/#DIR';
		$writablePaths[4]	= '../wp-content/themes/'.WPSHOP_THEME_NAME.'/pdf/bills/#..wp-content/themes/'.WPSHOP_THEME_NAME.'/pdf/bills/#DIR';
		$writablePaths[5]	= '../wp-content/themes/'.WPSHOP_THEME_NAME.'/pdf/tests/#..wp-content/themes/'.WPSHOP_THEME_NAME.'/pdf/tests/#DIR';
		
		$wpNum 		= count($writablePaths);
		$realNum	= 0;
		
		$icon			= array();
		$icon['DIR']	= "<img src='../wp-content/themes/".WPSHOP_THEME_NAME."/images/admin/folder_28.png' alt='Directory' />";
		$icon['FILE']	= "<img src='../wp-content/themes/".WPSHOP_THEME_NAME."/images/admin/paper_28.png' alt='File' />";

		
		foreach($writablePaths as $v){
		
			$data	= explode("#",$v);
		
			if((is_writable($data[0])) === TRUE){
				echo $icon["$data[2]"] ."<b>$data[1]</b> - <span class='writable' style='color: green;'>".__('Ok, writable!','wpShop')."</span>"; 
				$realNum++;
			}
			else {
				echo $icon["$data[2]"] ."<b>$data[1]</b> - <span class='not_writable' style='color: red;'>".__('Not writable!','wpShop')."</span>"; 
			}
			echo "<br/>";
		}
		
		echo "<br><a href='?page=functions.php'>".__('Check permissions again','wpShop')."</a>";
		echo "<br/><br/>"; 
		
		if($wpNum == $realNum){
			echo "
			<form action='?page=functions.php' method='post'>
				<input type='hidden' name='step' value='1'/>
				<input type='submit' name='install-step' value='".__('OK - Continue with Step 2','wpShop')."'/>
			</form>
			";
		}
		break;

		case 1:

			if(($this->theme_options_saved()) !== TRUE){
				$this->add_base_theme_options();	
			}
						
			echo "<h3 class='instWizSubheading'>".__('Import the XML sample data file','wpShop')."</h3>";
			
			$wxi = $this->was_xml_imported();
			
			if($wxi){
			
				echo "
				<p><b>".__('Well you have done, my friend.','wpShop')."</b></p>
				
				<form action='?page=functions.php' method='post'>
				<input type='hidden' name='step' value='2'/>
				<input type='submit' name='install-step' value='".__('OK - Continue with Step 3','wpShop')."'/>
				</form>
				";	
			}
			else {		
				echo "
				<p>".__('Go to ','wpShop')."<strong>".__('Tools &gt; Import &gt; WordPress','wpShop')."<//strong>".__(' and then select "WordPress"','wpShop')."</p>
				<ol>
					<li>".__('Click on the "Install" button and the plugin will be installed automatically','wpShop')."</li>
					<li>".__('Click on "Activate plugin & run importer"','wpShop')."</li>
					<li>".__('Browse to the "TheFurnitureStore_sampleData.xml" file (in Documentation folder), click "upload"','wpShop')."</li>
					<li>".__('Assign Author','wpShop')."</li>
					<li><strong>".__('Important!','wpShop')."</strong>".__(' Check "Download and import file attachments"','wpShop')."</li>
					<li>".__(' and click on "Submit"','wpShop')."</li>
				</ol>
				<p>".__('...then return here by clicking on "eCommerce &gt; Theme Options"','wpShop')."</p>
				<p><strong>".__('Tip:','wpShop')."</strong>".__(' Open the "Import" link in a new tab. This way in case you forgot the instructions on this page you can easily view them again! When you do come back, make sure you refresh this page.','wpShop')."</p>
				";
			}
		break;
		
		
		case 2:
						
			//windows or linux - this is here the question...
			if(DIRECTORY_SEPARATOR != "/"){
				$WINDOWS = TRUE;
			}
			
			if($WINDOWS){
					echo "<h3>".__('Your WordPress runs on a Windows operating system.','wpShop')."</h3>";
					echo "<p>".__('On a Linux Server we would normally ask you to change the writing permissons of the following directories to chmod 755.','wpShop');
					echo "<br/>";
					echo __('Try to perfom an equivalent action on your windows server.','wpShop');
					echo "</p>";
			}
			else{
					echo "
					<h3 class='instWizSubheading'>".__('Change the file permissions below back to chmod 0755','wpShop')."</h3>
					<p>".__('The following folders must be non-writable - so please check if the permissions are on 755.','wpShop')."<br/>".__('Once done, click on the "Check permissions again" link below.','wpShop')."</p>";
			}
			
			
			
			$writablePaths 		= array();
			$writablePaths[0]	= '../wp-content/#..wp-content/#DIR';
			$writablePaths[1]	= '../wp-content/themes/'.WPSHOP_THEME_NAME.'/#..wp-content/themes/'.WPSHOP_THEME_NAME.'/#DIR';
			
			$wpNum 		= count($writablePaths);
			$realNum	= 0;
			
			foreach($writablePaths as $v){
			
				$data	= explode("#",$v);
				$icon			= array();
				$icon['DIR']	= "<img src='../wp-content/themes/".WPSHOP_THEME_NAME."/images/admin/folder_28.png' alt='Directory' />";
				$icon['FILE']	= "<img src='../wp-content/themes/".WPSHOP_THEME_NAME."/images/admin/paper_28.png' alt='File' />";
			
			
				if($WINDOWS){
					$ccmod = "0755";
						
					if($ccmod == "0755"){
						echo $icon["$data[2]"] ."<b>$data[1]</b> - <span class='writable' style='color: green;'>".__('Still writable!','wpShop')."</span>"; 
						$realNum++;
					}
				}
				else {	
					$ccmod = substr(decoct(fileperms($data[0])),1); // lets get the current chmod
						
					if($ccmod == "0755"){
						echo $icon["$data[2]"] ."<b>$data[1]</b> - <span class='not_writable' style='color: green;'>".__('Ok, not writable!','wpShop')." (chmod: $ccmod)</span>"; 
						$realNum++;
					}
					else {
						echo $icon["$data[2]"] ."<b>$data[1]</b> - <span class='writable' style='color: red;'>".__('Not ok, still writable!','wpShop')." (chmod: $ccmod)</span>"; 
					}
				}
					
				echo "<br/>";
				
			}
			if(!$WINDOWS){
				echo "<br/><a href='?page=functions.php'>".__('Check permissions again','wpShop')."</a>";
			}
			
				echo "<br/><br/>"; 	

		if($wpNum == $realNum){
			echo "
			<form action='?page=functions.php' method='post'>
				<input type='hidden' name='step' value='4'/>
				<input type='submit' name='install-step' value='".__('Conclude installation','wpShop')."'/>
			</form>
			";
		}		
		break;
			
		case 4:
		
			echo "
			<br/>
			<br/>
			<br/>
			<h3 class='instWizSubheading'>".__('All\'s done!','wpShop')."</h3>
			<p>".__('Please continue with configuring your Theme Options using the provided documentation as a guide.','wpShop')."</p>
			<br/>
			<p><strong>".__('Note:','wpShop')."</strong><br/>".__('Should you want to use the Shopping Cart, remember to visit your "Theme Options > Design > General" settings and activate the shopping cart option.','wpShop')."</p>
			<br/>
			";
			
			echo "
			<form action='?page=functions.php' method='post'>
				<input type='hidden' name='step' value='8'/>
				<input type='submit' name='install-step' value='".__('Exit Installation Wizard','wpShop')."'/>
			</form>
			";
		
		break;
		
	}

return $install_status;
}



function install_wizard_header($install_step_info){

	global $CONFIG_WPS;

	$wp_version = get_bloginfo('version');
	
	if(substr($wp_version,0,3) == '2.8'){
		$filler = "<br/><br/>";
	}
	else {
		$filler = NULL;
	}
	
	if($install_step_info > 3){
		$install_step_info = NULL;
	}
	
	$output = "
				<h2>$CONFIG_WPS[themename] ".__('Installation 123 wizard','wpShop')."</h2>
				<p class='instWizStep'>$install_step_info</p>
				
			";

return $output;
}


function update_media_settings(){

	global $wpdb;

	$table = $wpdb->prefix.'options';
	
	$media_settings  	= array();
	$media_settings[0]	= 'thumbnail_size_w|160';
	$media_settings[1]	= 'thumbnail_size_h|120';
	$media_settings[2]	= 'medium_size_w|400';
	$media_settings[3]	= 'medium_size_h|300';
	$media_settings[4]	= 'large_size_w|480';
	$media_settings[5]	= 'large_size_h|360';
	
	
	foreach($media_settings as $data){
	
		$v = explode("|",$data);

		$qStr = "UPDATE $table SET option_value = '$v[1]' WHERE option_name = '$v[0]'";
		mysql_query($qStr);
	}
		
return $status;
}


function media_settings_correct(){
	global $OPTION;
	
	if	(($OPTION['thumbnail_size_w']== '160')&&
		($OPTION['thumbnail_size_h']== '120')&&
		($OPTION['medium_size_w']== '400')&&
		($OPTION['medium_size_h']== '300')&&
		($OPTION['large_size_w']== '480')&&
		($OPTION['large_size_h']== '360'))
		{
			$status = TRUE;
		}
		else {
			$status = FALSE;
		}
		
return $status;
}



function check_permalink(){
	global $OPTION;
	$pls = $OPTION['permalink_structure'];
	
	if($pls == '/%category%/%postname%'){
		$res = TRUE;
	}
	else {$res = FALSE;}
		
return $res;
}



function theme_options_saved(){
	global $OPTION;
	if($OPTION['wps_wpay_instId'] =='1234'){
		$result = TRUE;
	}else {$result = FALSE;}
	
return $result;
}



function add_base_theme_options(){

$base_options 											= array();
$base_options['upload_path'] 							= 'wp-content/uploads';
$base_options['sec_generalDesign_settings'] 			= '';
$base_options['wps_shoppingCartEngine_yes'] 			= 'false';
$base_options['wps_lrw_yes'] 							= 'false';
$base_options['wps_blogCat'] 							= 'Select a Category';
$base_options['wps_blogTags_option'] 					= 'false';
$base_options['wps_customerServicePg'] 					= 'Select a Page';
$base_options['wps_aboutPg'] 							= 'Select a Page';
$base_options['wps_contactPg'] 							= 'Select a Page';
$base_options['wps_store_pgs_title'] 					= 'Store';
$base_options['wps_store_pgs_titleAlt'] 				= 'false';
$base_options['wps_feature_option'] 					= 'main_cats';
$base_options['wps_myFeaturedCats_include'] 			= '';
$base_options['wps_myFeaturedCats_orderbyOption'] 		= 'name';
$base_options['wps_myFeaturedCats_orderOption'] 		= 'ASC';
$base_options['wps_custom_img_path'] 					= '';
$base_options['wps_custom_img_link_enable'] 			= 'false';
$base_options['wps_custom_img_link'] 					= '';
$base_options['wps_featureEffect_option'] 				= 'innerfade_effect';
$base_options['wps_caption_position'] 					= 'top';
$base_options['wps_sidebar_option'] 					= 'alignRight';
$base_options['wps_front_sidebar_disable'] 				= 'false';
$base_options['wps_shopByOutfit_option'] 				= 'false';
$base_options['wps_shopByFit_option'] 					= 'false';
$base_options['wps_shopBySize_option'] 					= 'false';
$base_options['wps_shopByColour_option'] 				= 'false';
$base_options['wps_shopByBrand_option'] 				= 'false';
$base_options['wps_shopBySelection_option'] 			= 'false';
$base_options['wps_shopByStyle_option'] 				= 'false';
$base_options['wps_shopByPrice_option'] 				= 'false';
$base_options['wps_search_input'] 						= 'false';
$base_options['wps_search_link_enable'] 				= 'false';
$base_options['wps_pgNavi_searchOption'] 				= 'Select a Page';
$base_options['wps_search_title'] 						= 'Looking for something specific?';
$base_options['wps_search_text'] 						= '';
$base_options['wps_search_excl'] 						= '';
$base_options['wps_search_teaser_option'] 				= 'false';
$base_options['wps_footer_option'] 						= 'small_footer';
$base_options['sec_navigation_settings'] 				= '';
$base_options['wps_wp_custom_menus'] 					= 'false';
$base_options['wps_pgNavi_homeOption'] 					= '';
$base_options['wps_pgNavi_inclOption'] 					= '';
$base_options['wps_pgNavi_exclOption'] 					= '';
$base_options['wps_pgNavi_sortOption'] 					= 'menu_order';
$base_options['wps_hybrid_menu_enable'] 				= 'false';
$base_options['wps_hybrid_dropMenu_enable'] 			= 'false';
$base_options['wps_customerAreaPg'] 					= 'Select a Page';
$base_options['wps_pgNavi_logOption'] 					= 'Select a Page';
$base_options['wps_pgNavi_regOption'] 					= 'Select a Page';
$base_options['wps_wishListLink_option'] 				= 'My Wishlist';
$base_options['wps_pgNavi_cartOption'] 					= 'Shopping Basket';
$base_options['wps_pgNavi_inquireOption'] 				= 'Enquiry Basket';
$base_options['wps_shopping_icon'] 						= 'shopping_icon.jpg';
$base_options['wps_br_yes'] 							= 'false';
$base_options['wps_totalItemValue_enable'] 				= 'false';
$base_options['c'] 										= '';
$base_options['wps_sidebar_catNavi_enable'] 			= 'false';
$base_options['wps_catNavi_orderbyOption'] 				= 'name';
$base_options['wps_catNavi_orderOption'] 				= 'ASC';
$base_options['wps_catNavi_inclOption'] 				= '';
$base_options['wps_catNavi_exclOption'] 				= '';
$base_options['wps_catSubNav_rows'] 					= '2';
$base_options['wps_catSubNav_orderbyOption'] 			= 'name';
$base_options['wps_catSubNav_orderOption'] 				= 'ASC';
$base_options['sec_multiProd_settings'] 				= '';
$base_options['wps_catDescr_enable'] 					= 'false';
$base_options['wps_catimg_file_type'] 					= 'jpg';
$base_options['wps_catCol_option'] 						= 'catCol3';
$base_options['wps_mainCat_orderbyOption'] 				= 'ID';
$base_options['wps_mainCat_orderOption'] 				= 'ASC';
$base_options['wps_secondaryCat_orderbyOption'] 		= 'ID';
$base_options['wps_secondaryCat_orderOption'] 			= 'ASC';
$base_options['wps_catTeaser_enable'] 					= 'false';
$base_options['wps_catTitle_enable'] 					= 'false';
$base_options['wps_tagimg_file_type'] 					= 'jpg';
$base_options['wps_termDescr_enable'] 					= 'false';
$base_options['wps_prodCol_option'] 					= 'prodCol4';
$base_options['wps_tagCol_option'] 						= 'tagCol4';
$base_options['wps_prods_orderbyOption'] 				= 'ID';
$base_options['wps_prods_orderOption'] 					= 'ASC';
$base_options['wps_showpostsOverwrite_Option'] 			= 'showpostsOverwrite_yes';
$base_options['wps_hover_remove_option'] 				= 'false';
$base_options['wps_wp_thumb'] 							= 'false';
$base_options['wps_teaser_enable_option'] 				= 'false';
$base_options['wps_teaser2_enable_option'] 				= 'false';
$base_options['wps_prod_title'] 						= 'false';
$base_options['wps_prod_price'] 						= 'false';
$base_options['wps_prod_btn'] 							= 'false';
$base_options['wps_multiProd_rate_enable'] 				= 'false';
$base_options['sec_membership_settings'] 				= '';
$base_options['wps_passLostPg'] 						= 'Select a Page';
$base_options['wps_logoutLink_option'] 					= 'false';
$base_options['wps_wishlistIntroText'] 					= 'Some optional text can be entered here from your Theme Options';
$base_options['wps_login_duration'] 					= '1800';
$base_options['wps_extrainfo_header'] 					= '';
$base_options['wps_extrainfo_instruct'] 				= '';
$base_options['wps_extra_formfields'] 					= '';
$base_options['wps_extra_formfieldsCol'] 				= '1';
$base_options['sec_singleProd_settings'] 				= '';
$base_options['wps_shop_single_sidebar_enable'] 		= 'false';
$base_options['wps_catTrail_enable'] 					= 'false';
$base_options['wps_backLink_enable'] 					= 'false';
$base_options['wps_prodNav_enable'] 					= 'false';
$base_options['wps_linksBottom_enable'] 				= 'false';
$base_options['wps_prevProdLinkText'] 					= 'Previous Product';
$base_options['wps_nextProdLinkText'] 					= 'Next Product';
$base_options['wps_flowplayer_enable'] 					= 'false';
$base_options['wps_prodImg_effect'] 					= 'mz_effect';
$base_options['wps_imgThumbs_enable'] 					= 'false';
$base_options['wps_caption_enable'] 					= 'false';
$base_options['wps_videoTabText'] 						= 'View a Sample';
$base_options['wps_imagesTab_enable'] 					= 'false';
$base_options['wps_imagesTabText'] 						= 'Images';
$base_options['wps_prodVariations_orderBy'] 			= 'meta_key';
$base_options['wps_prodVariations_order'] 				= 'ASC';
$base_options['wps_prodPersonalization_orderBy'] 		= 'meta_key';
$base_options['wps_prodPersonalization_order'] 			= 'ASC';
$base_options['wps_singleProd_rate_enable'] 			= 'false';
$base_options['wps_relatedProds_enable'] 				= 'false';
$base_options['wps_tagRelatedProds_enable'] 			= 'false';
$base_options['wps_tag_relatedProds'] 					= 'Complete the Set';
$base_options['wps_term_relatedProds'] 					= 'outfit_related';
$base_options['wps_tag_relatedProds_num'] 				= '4';
$base_options['wps_catRelatedProds_enable'] 			= 'false';
$base_options['wps_cat_relatedProds'] 					= 'You may also like';
$base_options['wps_cat_relatedProds_num'] 				= '4';
$base_options['wps_cat_relatedProds_orderby'] 			= 'rand';
$base_options['wps_cat_relatedProds_order'] 			= 'ASC';
$base_options['wps_relatedOpen_tab'] 					= 'tag_related_tab';
$base_options['wps_cartRelatedProds_enable'] 			= 'false';
$base_options['wps_term_cart_relatedProds'] 			= 'outfit_related';
$base_options['wps_prod_ID'] 							= 'false';
$base_options['wps_shipping_details_enable'] 			= 'false';
$base_options['wps_shippingInfo_linkTxt'] 				= 'Shipping & Handling Info';
$base_options['wps_shipping_details'] 					= 'Here you may include some useful information for your customers regarding your Shipping & Handling Fees, Returns Policy whether you ship Internationaly or not etc. Let them know that these charges will be calculated on Step 3 (Order Review).';
$base_options['wps_supplInfo_enable'] 					= 'false';
$base_options['wps_supplInfo_linkTxt'] 					= 'Size Chart';
$base_options['wps_send_to_view_cart'] 					= 'false';
$base_options['wps_emailFriend_enable'] 				= 'false';
$base_options['wps_print_enable'] 						= 'false';
$base_options['wps_share_enable'] 						= 'false';
$base_options['wps_subscribe_enable'] 					= 'false';
$base_options['wps_icons_file_type'] 					= 'jpg';
$base_options['wps_feedburner_rsslink'] 				= 'http://feeds2.feedburner.com/snDesign';
$base_options['wps_feedburner_emaillink'] 				= 'http://feedburner.google.com/fb/a/mailverify?uri=snDesign&loc=en_US';
$base_options['wps_twitter'] 							= 'srhnbr';
$base_options['wps_subscribe_title'] 					= 'Stay Informed';
$base_options['wps_subscribe_text'] 					= '';
$base_options['wps_share_title'] 						= 'Bookmark & Share';
$base_options['wps_share_text'] 						= '';
$base_options['wps_email_a_friend_title'] 				= 'Email a Friend About this Item';
$base_options['wps_email_a_friend_text'] 				= '';
$base_options['sec_blog_settings'] 						= '';
$base_options['wps_date_enable'] 						= 'false';
$base_options['wps_commentsNum_enable'] 				= 'false';
$base_options['wps_blogPostContent_option'] 			= 'excerpt_link';
$base_options['wps_blogWordLimit'] 						= '30';
$base_options['wps_readMoreLink'] 						= 'read more';
$base_options['wps_blog_cat_meta_enable'] 				= 'false';
$base_options['wps_blog_cat_sidebar_enable'] 			= 'false';
$base_options['wps_blog_single_sidebar_enable'] 		= 'false';
$base_options['wps_blog_indSingle_sidebar_enable'] 		= 'false';
$base_options['wps_blogCatRelated_posts_enable'] 		= 'false';
$base_options['wps_blogCatRelated_num'] 				= '4';
$base_options['wps_blogCatRelated_title'] 				= 'Category Related';
$base_options['wps_blogTagRelated_posts_enable'] 		= 'false';
$base_options['wps_blogTagRelated_num'] 				= '4';
$base_options['wps_blogTagRelated_title'] 				= 'Tag Related';
$base_options['wps_publish_enable'] 					= 'false';
$base_options['wps_posted_enable'] 						= 'false';
$base_options['wps_tagged_enable'] 						= 'false';
$base_options['wps_prevNext_enable'] 					= 'false';
$base_options['wps_blogEmailFriend_enable'] 			= 'false';
$base_options['wps_blogPrint_enable'] 					= 'false';
$base_options['wps_blogShare_enable'] 					= 'false';
$base_options['wps_blogSubscribe_enable'] 				= 'false';
$base_options['wps_blogFeedburner_rsslink'] 			= 'http://feeds2.feedburner.com/snDesign';
$base_options['wps_blogFeedburner_emaillink'] 			= 'http://feedburner.google.com/fb/a/mailverify?uri=snDesign&loc=en_US';
$base_options['wps_blogTwitter'] 						= 'srhnbr';
$base_options['wps_blogSubscribe_title'] 				= 'Stay Informed';
$base_options['wps_blogSubscribe_text'] 				= '';
$base_options['wps_blogShare_title'] 					= 'Bookmark & Share';
$base_options['wps_blogShare_text'] 					= '';
$base_options['wps_blogEmail_a_friend_title'] 			= 'Share this Post with a Friend';
$base_options['wps_blogEmail_a_friend_text'] 			= '';
$base_options['sec_imageSizes_settings'] 				= '';
$base_options['wps_featured_img_size'] 					= '770';
$base_options['wps_prodCol1_img_size'] 					= '405';
$base_options['wps_prodCol2_img_size'] 					= '369';
$base_options['wps_prodCol3_img_size'] 					= '237';
$base_options['wps_prodCol4_img_size'] 					= '174';
$base_options['wps_singleProdMain1_img_size'] 			= '405';
$base_options['wps_singleProdMainMulti_img_size'] 		= '355';
$base_options['wps_singleProd_t_img_size'] 				= '30';
$base_options['wps_ProdRelated_img_size'] 				= '91';
$base_options['sec_general_shop_settings'] 				= '';
$base_options['wps_shop_mode'] 							= 'Normal shop mode';
$base_options['wps_affili_newTab'] 						= 'false';
$base_options['wps_shop_name'] 							= 'The Furniture Store';
$base_options['wps_shop_street'] 						= 'Some Street 1';
$base_options['wps_shop_province'] 						= 'Some State';
$base_options['wps_shop_zip'] 							= '11111';
$base_options['wps_shop_town'] 							= 'Some Town';
$base_options['wps_shop_country'] 						= 'US';
$base_options['wps_shop_email'] 						= get_option('admin_email');
$base_options['wps_currency_code'] 						= 'EUR';
$base_options['wps_currency_code_enable'] 				= 'true';
$base_options['wps_currency_symbol'] 					= '';
$base_options['wps_currency_symbol_alt'] 				= '';
$base_options['wps_price_format'] 						= '2';
$base_options['wps_tax_country'] 						= '';
$base_options['wps_tax_info_enable'] 					= 'false';
$base_options['wps_tax_abbr'] 							= 'VAT';
$base_options['wps_tax_percentage'] 					= '';
$base_options['wps_delivery_options'] 					= 'pickup|post';
$base_options['wps_pickUp_label'] 						= 'Pick up (no additional cost)';
$base_options['wps_delivery_label'] 					= 'Delivery (2-3 Business Days, Delivery Charges Apply)';
$base_options['wps_payment_options'] 					= 'transfer';
$base_options['wps_payment_op_preselected'] 			= 'none';
$base_options['wps_pps_label'] 							= 'PayPal (PayPal Payments Standard)';
$base_options['wps_ppp_label'] 							= 'Credit Card (PayPal Payments Pro)';
$base_options['wps_auth_label'] 						= 'Credit Card (Authorize.net)';
$base_options['wps_g2p_label'] 							= 'Credit Card (Gate2Play.com)';
$base_options['wps_wp_label'] 							= 'Credit Card (WorldPay.com)';
$base_options['wps_bt_label'] 							= 'Bank Transfer';
$base_options['wps_cod_label'] 							= 'Cash on Delivery';
$base_options['wps_pol_label'] 							= 'Payment on Location';
$base_options['wps_enforce_ssl'] 						= 'normal';
$base_options['wps_voucherCodes_enable'] 				= 'false';
$base_options['wps_checkout_showtel'] 					= 'No';
$base_options['wps_customNote_enable'] 					= 'false';
$base_options['wps_customNote_label'] 					= 'Custom Note';
$base_options['wps_customNote_remark'] 					= 'This optional custom note can be used to send a message to the Shop Merchant or as a personal note to be delivered along with the order eg. when an order is a gift to someone.';
$base_options['wps_terms_conditions'] 					= 'Our terms & conditions are...';
$base_options['sec_inventory_settings'] 				= '';
$base_options['wps_track_inventory'] 					= 'not_active';
$base_options['wps_display_product_amounts'] 			= 'not_active';
$base_options['wps_soldout_notice'] 					= 'SOLD OUT';
$base_options['wps_inventory_cleaning_interval'] 		= '14400';
$base_options['wps_inventory_cleaning_method'] 			= 'internal';
$base_options['wps_stock_warn_threshold'] 				= '2';
$base_options['wps_stock_warn_email'] 					= '';
$base_options['sec_digiProds_settings'] 				= '';
$base_options['wps_master_dir'] 						= '../path/to/masterdata/';
$base_options['wps_l_mode'] 							= 'SIMPLE';
$base_options['wps_lkeys_warn_num'] 					= '30';
$base_options['wps_duration_links'] 					= '300';
$base_options['wps_short_addressform'] 					= 'not_active';
$base_options['sec_payloadz_settings'] 					= '';
$base_options['wps_payloadz_viewcart_id'] 				= '1234567890';
$base_options['wps_payloadz_viewcart_option'] 			= 'img';
$base_options['wps_payloadz_viewcart_imglink'] 			= 'https://www.payloadz.com/images/viewcart.gif';
$base_options['wps_payloadz_addcart_option'] 			= 'img';
$base_options['wps_payloadz_addcart_imglink'] 			= 'http://www.paypal.com/images/x-click-but22.gif';
$base_options['sec_paypal_settings'] 					= '';
$base_options['wps_paypal_email'] 						= '';
$base_options['wps_paypal_pdttoken'] 					= '';
$base_options['wps_paypal_encode_key'] 					= 'HastaLaVista';
$base_options['wps_confirm_url'] 						= get_option('home') . '/?confirm=1';
$base_options['wps_ipn_url'] 							= get_option('siteurl') . '/wp-content/themes/'. $CONFIG_WPS['themename'] .'/ipn.php?pst='.md5(LOGGED_IN_KEY.'-'.NONCE_KEY);
$base_options['wps_paypal_api_user'] 					= '';
$base_options['wps_paypal_api_pw'] 						= '';
$base_options['wps_paypal_api_signature'] 				= '';
$base_options['sec_authorize_settings'] 				= '';
$base_options['wps_authn_api_login'] 					= '';
$base_options['wps_authn_transaction_key'] 				= '';
$base_options['wps_authn_url'] 							= 'https://test.authorize.net/gateway/transact.dll';
$base_options['wps_authn_test_request'] 				= 'false';
$base_options['sec_worldpay_settings'] 					= '';
$base_options['wps_wpay_instId'] 						= '1234';
$base_options['wps_wpay_testmode'] 						= 'true';
$base_options['wps_wp_callback_url'] 					= get_option('siteurl').'/wpay.php?pst='.md5(LOGGED_IN_KEY.'-'.NONCE_KEY);
$base_options['sec_banktransfer_settings'] 				= '';
$base_options['wps_banktransfer_bankname'] 				= '';
$base_options['wps_banktransfer_routing_enable'] 		= 'false';
$base_options['wps_banktransfer_routing_text'] 			= 'Routing Number';
$base_options['wps_banktransfer_bankno'] 				= '';
$base_options['wps_banktransfer_accountno'] 			= '';
$base_options['wps_banktransfer_account_owner'] 		= '';
$base_options['wps_banktransfer_iban'] 					= '';
$base_options['wps_banktransfer_bic'] 					= '';
$base_options['wps_online_banking_url'] 				= 'www.bankofengland.co.uk';
$base_options['sec_cashOnDelivery_settings'] 			= '';
$base_options['wps_cod_service'] 						= 'Deutsche Bundespost';
$base_options['wps_cod_who_note'] 						= 'Deutsche Bundespost';
$base_options['sec_shipping_settings'] 					= '';
$base_options['wps_shipping_method'] 					= 'FLAT';
$base_options['wps_shipping_flat_parameter'] 			= '5.00';
$base_options['wps_shipping_flatlimit_parameter'] 		= '4.00|40.00';
$base_options['wps_meassuring_unit'] 					= 'grams';
$base_options['wps_shipping_weightflat_parameter'] 		= '1.00';
$base_options['wps_shipping_weightclass_parameter'] 	= '0-10|5#11-15|8#16-ul|15';
$base_options['wps_shipping_peritem_parameter'] 		= '1.00';
$base_options['wps_free_shipping_categories'] 			= '';
$base_options['wps_shipping_country_zones'] 			= '';
$base_options['wps_shipping_zone2_addition'] 			= '2.00';
$base_options['wps_shipping_zone3_addition'] 			= '3.00';
$base_options['wps_shipping_zone4_addition'] 			= '4.00';
$base_options['wps_shipping_zone5_addition'] 			= '5.00';
$base_options['wps_shipping_zone6_addition'] 			= '6.00';
$base_options['sec_email_settings'] 					= '';
$base_options['wps_order_no_prefix'] 					= '1000';
$base_options['wps_email_logo'] 						= 'email-logo.jpg';
$base_options['wps_email_txt_header'] 					= 'The Furniture Store';
$base_options['wps_email_delivery_type'] 				= 'mime';
$base_options['wps_email_confirmation_dbl'] 			= 'no';
$base_options['sec_pdf_settings'] 						= '';
$base_options['wps_invoice_prefix'] 					= 'Invoice';
$base_options['wps_invoice_no_prefix'] 					= '20100';
$base_options['wps_PDF_invoiceNum_enable'] 				= 'false';
$base_options['wps_PDF_invoiceLabel'] 					= 'Invoice-No.:';
$base_options['wps_PDF_invoiceLabel_align'] 			= 'L';
$base_options['wps_PDF_orderNum_enable'] 				= 'false';
$base_options['wps_PDF_orderLabel'] 					= 'Order-No.:';
$base_options['wps_PDF_orderLabel_align'] 				= 'L';
$base_options['wps_PDF_trackID_enable'] 				= 'false';
$base_options['wps_PDF_trackLabel'] 					= 'Tracking-ID:';
$base_options['wps_PDF_trackLabel_align'] 				= 'L';
$base_options['wps_vat_id_label'] 						= '';
$base_options['wps_vat_id'] 							= '';
$base_options['wps_PDF_delAddr_enable'] 				= 'false';
$base_options['wps_pdf_invoiceFormat'] 					= 'A4';
$base_options['wps_pdf_leftMargin'] 					= '10';
$base_options['wps_pdf_rightMargin'] 					= '10';
$base_options['wps_pdf_topMargin'] 						= '10';
$base_options['wps_pdf_colWidth1'] 						= '20';
$base_options['wps_pdf_colWidth2'] 						= '115';
$base_options['wps_pdf_colWidth3'] 						= '15';
$base_options['wps_pdf_colWidth4'] 						= '20';
$base_options['wps_pdf_colWidth5'] 						= '20';
$base_options['wps_pdf_logo'] 							= 'pdf-logo.jpg';
$base_options['wps_pdf_logoWidth'] 						= '70';
$base_options['wps_pdf_header_addr_disable'] 			= 'false';
$base_options['wps_pdf_shop_name_only'] 				= 'false';
$base_options['wps_pdf_header_custom_text'] 			= '';
$base_options['wps_pdf_header_txtColour'] 				= '0,0,0';
$base_options['wps_pdf_header_fontSize'] 				= '12';
$base_options['wps_pdf_header_addrBorder'] 				= 'false';
$base_options['wps_pdf_header_addrBorderWidth'] 		= '0.2';
$base_options['wps_pdf_header_bgdColour_enable'] 		= 'false';
$base_options['wps_pdf_header_bgdColour'] 				= '255,255,255';
$base_options['wps_pdf_footer_custom_text'] 			= '';
$base_options['wps_pdf_footer_txtColour'] 				= '0,0,0';
$base_options['wps_pdf_footer_fontSize'] 				= '6';
$base_options['wps_pdf_footer_Border'] 					= 'false';
$base_options['wps_pdf_footer_BorderWidth'] 			= '0.2';
$base_options['wps_pdf_footer_bgdColour_enable'] 		= 'false';
$base_options['wps_pdf_footer_bgdColour'] 				= '255,255,255';
$base_options['wps_pdfFormat'] 							= 'A4';
$base_options['wps_pdf_voucher_bg'] 					= 'sample_coupon_voucher_1.jpg';
$base_options['sec_seo_settings'] 						= '';
$base_options['wps_keywords'] 							= '';
$base_options['wps_tags_as_keywords'] 					= 'false';
$base_options['sec_googleMaps_settings'] 				= '';
$base_options['wps_google_maps_link'] 					= 'http://www.google.com/maps?f=q&hl=de&geocode=&q=Platzl+9,80331+M%C3%BCnchen,+Germany&sll=37.0625,-95.677068&sspn=51.04407,78.75&ie=UTF8&ll=48.137683,11.57959&spn=0.678178,1.230469&z=10&iwloc=addr';
$base_options['sec_googleAnalytics_settings'] 			= '';
$base_options['wps_google_analytics'] 					= 'not_active';
$base_options['wps_google_analytics_id'] 				= 'UA-xxxxxx-01';
$base_options['wps_custom_tracking'] 					= '';
$base_options['sec_googleAdsense_settings'] 			= '';
$base_options['wps_google_adsense_1'] 					= '';
$base_options['wps_google_adsense_2'] 					= '';
$base_options['wps_google_adsense_3'] 					= '';
$base_options['sec_support_settings'] 					= '';
$base_options['wps_support_id'] 						= '0';
$base_options['wps_dash_widget'] 						= "<p>Need help? <a target=\\''_blank\\'' href=\\''http://themeforest.net/user/srhnbr\\''>Contact the developer</a></p>";
$base_options['wps_time_addition'] 						= '32400';
$base_options['wps_salestax_rate'] 						= '0';
$base_options['uploads_use_yearmonth_folders'] 			= '0';

$num = count($base_options);

	foreach($base_options as $k => $v){
		update_option($k,$v);
	}
}





function was_xml_imported(){
				
				global $wpdb;
				
				$table 	= $wpdb->prefix . 'postmeta';
				$qStr 	= "SELECT * FROM $table WHERE meta_key = 'ID_item'";
				$res 	= mysql_query($qStr);
				$num 	= mysql_num_rows($res);
				
				if($num > 1){
					$result = TRUE;
				}else {
					$result = FALSE;
				}
				
return $result;
}



function get_blog_category_ids(){

	global $wpdb;

	$table = $wpdb->prefix.'terms';
	
	$cat_slugs 		= array();
	$cat_slugs[0]	= 'blog-category-i';
	$cat_slugs[1]	= 'blog-category-ii';
	$cat_slugs[2]	= 'blog-category-iii';
	
	$feedback = NULL;
	
	
	foreach($cat_slugs as $v){
		$qStr 		= "SELECT term_id FROM $table WHERE slug = '$v' LIMIT 0,1";
		$res 		= mysql_query($qStr);	
		$row 		= mysql_fetch_assoc($res);
		$feedback	.= $row[term_id].",";
	}

	$feedback = substr($feedback,0,-1);
	
return $feedback;
}



function save_blog_cat_ids($the_ids){

	global $wpdb;

	$table = $wpdb->prefix.'options';
	
	$qStr = "UPDATE $table SET option_value = '$the_ids' WHERE option_name = 'wps_blog_cat'";
	mysql_query($qStr);
	
}


function save_static_pages(){

	global $wpdb;
	
	$table1 = $wpdb->prefix.'options';
	$table2 = $wpdb->prefix.'posts';
	
	// change to static page
	$qStr = "UPDATE $table1 SET option_value = 'page' WHERE option_name = 'show_on_front'";
	mysql_query($qStr);
	
	// Delete old options
	$qStr = "DELETE FROM $table1 WHERE option_name IN ('page_on_front','page_for_posts')";
	mysql_query($qStr);	
	
	// Home 
	$qStr 		= "SELECT ID FROM $table2 WHERE post_title = 'Home' AND post_type = 'page' LIMIT 0,1";
	$res 		= mysql_query($qStr);	
	$row 		= mysql_fetch_assoc($res);
	$ID			= $row[ID];
	
	$table 	= $wpdb->prefix.'options';	
	$qStr 	= "INSERT INTO $table1 (option_name,option_value) VALUES ('page_on_front','$ID')";
	mysql_query($qStr);	
	
	
	// Blog 
	$qStr 		= "SELECT ID FROM $table2 WHERE post_title = 'Blog' AND post_type = 'page' LIMIT 0,1";
	$res 		= mysql_query($qStr);	
	$row 		= mysql_fetch_assoc($res);
	$ID			= $row[ID];
	
	$table 	= $wpdb->prefix.'options';	
	$qStr 	= "INSERT INTO $table1 (option_name,option_value) VALUES ('page_for_posts','$ID')";
	mysql_query($qStr);	

}



	function rename_page($page,$new_id){

			switch($page){
			
				case 'category-3.php':
				
					if(file_exists(TEMPLATEPATH . '/category-3.php')){
						$rename_fb = rename(TEMPLATEPATH . '/category-3.php' , TEMPLATEPATH . '/category-'.$new_id.'.php');		
					}			
				break;
			
				case 'category-4.php':
				
					if(file_exists(TEMPLATEPATH . '/category-4.php')){
						$rename_fb = rename(TEMPLATEPATH . '/category-4.php' , TEMPLATEPATH . '/category-'.$new_id.'.php');		
					}			
				break;
				
				case 'single-3.php':
					if(file_exists(TEMPLATEPATH . '/single-3.php')){
						$rename_fb = rename(TEMPLATEPATH . '/single-3.php' , TEMPLATEPATH . '/single-'.$new_id.'.php');							
					}
				break;
				
				case 'single-4.php':
					if(file_exists(TEMPLATEPATH . '/single-4.php')){
							$rename_fb = rename(TEMPLATEPATH . '/single-4.php' , TEMPLATEPATH . '/single-'.$new_id.'.php');				
					}
				break;
			}

	return $rename_fb;
	}
}
?>