<?php
##################################################################################################################################
// 												THEME - ADMIN - OPTIONS
##################################################################################################################################
if(!function_exists('get_currentuserinfo')){exit();}

function get_all_themes(){

	global $OPTION;

	if(is_admin()){
		$result = array();
		
		$path 	= '../wp-content/themes';
		
		if($handle = opendir($path)){
			while (false !== ($file = readdir($handle))){
			
				if($file != '.' && $file != '..' && $file != 'classic' && $file != 'default' && $file != 'index.php' && $file != WPSHOP_THEME_NAME){
					$result[] = "$file";
				}	
			}
			closedir($handle);
		}
		return $result;
	}
}

load_theme_textdomain('wpShop', get_template_directory().'/languages/');
$countries 	= get_countries();
$zone1		= get_countries(2,$OPTION['wps_shop_country']);
$masterpath	= find_masterdata_path(1);

// depending on installation status user sees different things in backend
installation_status_change();
$install_status = installation_status();

if($install_status < 8){
	$useSection		= 1;
}
else {
	update_the_theme_if_necessary(); 
	$useSection		= 1; // for possible later changes
}
// get categories
$categories 	= get_categories('hide_empty=0&orderby=name');
$nws_wp_cats 	= array();
$shop_wp_cats 	= array();
foreach ($categories as $category_list ) {
    $nws_wp_cats[$category_list->cat_ID] = $category_list->cat_name;
	$shop_wp_cats[$category_list->cat_ID] = $category_list->cat_name;
}
array_unshift($nws_wp_cats, "Select a Category"); 

//get pages
$pages = get_pages('parent=0&sort_column=post_title');
$nws_wp_pages = array();
$wp_full_pages = array();
foreach ($pages as $pages_list ) {
    $nws_wp_pages[$pages_list->ID] = $pages_list->post_title;
    $wp_full_pages[$pages_list->ID] = $pages_list->post_title;
	$subpages = get_pages('child_of='.$pages_list->ID);
	if ($subpages) {
		foreach($subpages as $subpage) {
		    $wp_full_pages[$subpage->ID] = '&nbsp;&nbsp;&nbsp;&nbsp;'.$subpage->post_title;
		}
	}
}
array_unshift($nws_wp_pages, "Select a Page"); 
// email formats
$email_formats = array();
$wp_email_formats = get_posts('post_type=email-format&posts_per_page=-1&orderby=title&order=asc');
if ($wp_email_formats) {
	foreach ($wp_email_formats as $wp_email_format ) {
		$email_formats[$wp_email_format->ID] = $wp_email_format->post_title;
	}
}

$options   = array (

/***
Design Settings
***/
				
array ( 	"name" 	=> __('Design','wpShop'),
			"type" 	=> "heading",
			"class" =>"design"),
					
	array (		"type" 	=> "section_start",
				"class" =>"hasadmintabs hasadmintabs1"),
//###############################################################################################################
		// Tab 1 General				
		array (		"type" 	=> "fieldset_start",
					"class" =>"design",
					"id" 	=>"sec_generalDesign_settings"),

			array ( 	"name" 	=> __('General','wpShop'),
						"type" 	=> "title"),

				array(    	"type" 	=> "open"),
								
					array(  	"name" 	=> __('Activate the Shopping Cart?','wpShop'),
								"desc" 	=> __('Check this setting if you want to use the NWS Shopping Cart','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shoppingCartEngine_yes",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(  	"name" 	=> __('Activate the Customer Membership Area?','wpShop'),
								"desc" 	=> __('Check this setting if you want to enable the Customer Area for Registered Users of your Shop.','wpShop')."<br/><b>".__('Note: Registered Users are independent to the WordPress Users and can me managed under the "Members" main link above','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_lrw_yes",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(    	"name" 	=> __('Blog Category','wpShop'),
								"desc" 	=> __('If you want to maintain a blog along with your Shop then please select the Main Category you have already created for your blog','wpShop')."<br/><b>".__('Your blog category will only appear in the drop down after you have ','wpShop')."<a href='".get_option('siteurl')."/wp-admin/categories.php'>".__('created it.','wpShop')."</a></b><br/><b>".__('To create/use Blog Subcategories: If you want to categorize your Regular Blog Posts you should create subcategories under the Main Category that you have identified above.','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_blogCat",
								"type" 	=> "select",
								"vals" 	=> $nws_wp_cats,
								"std" 	=> "Select a category"), 

					array(  	"name" 	=> __('Use Tags on Regular Blog Posts?','wpShop'),
								"desc" 	=> __('Since Tags are not (at the time of creating this theme) hierarchical you can only use them for your Shop Product Posts or for your Regular Blog Posts not both! Only check this setting if you plan to use them for your Regular Blog Posts.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_blogTags_option",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(    	"name" 	=> __('Customer Service page','wpShop'),
								"desc" 	=> __('Please select the page you created for your "Customer Service" page.','wpShop')."<br/><b>".__('Note: Remember to use the "Customer Service" template for this page!','wpShop')."</b><br/><b>".__('The page will only appear in the drop down after you have ','wpShop')."<a href='".get_option('siteurl')."/wp-admin/page-new.php'>".__('created it.','wpShop')."</a></b>",
								"id" 	=> $CONFIG_WPS['shortname']."_customerServicePg",
								"type" 	=> "select",
								"vals" 	=> $nws_wp_pages,
								"std" 	=> "Select a Page"),
					
					array(    	"name" 	=> __('About page','wpShop'),
								"desc" 	=> __('In order to use the independent widget ready area for the "About" page sidebar then select the page you created and that will serve as your "About".','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_aboutPg",
								"type" 	=> "select",
								"vals" 	=> $nws_wp_pages,
								"std" 	=> "Select a Page"),
					
					array(    	"name" 	=> __('Contact page','wpShop'),
								"desc" 	=> __('In order to use the independent widget ready area for the "Contact" page sidebar then select the page you created and that will serve as your "Contact".','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_contactPg",
								"type" 	=> "select",
								"vals" 	=> $nws_wp_pages,
								"std" 	=> "Select a Page"),

					array(    	"name" 	=> __('Reserved Bags page','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_reserved_bags_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

					array(    	"name" 	=> __('What Happens Next page','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_what_happens_next_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

					array(    	"name" 	=> __('What\'s New page','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_whats_new_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

					array(    	"name" 	=> __('Shopping Cart page','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shopping_cart_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

					array(    	"name" 	=> __('Checkout page','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_checkout_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

					array(    	"name" 	=> __('Multiple and Single Product pages - Page Title','wpShop'),
								"desc" 	=> __('Enter the Page Title you\'d like to appear in your shop when users are browsing these pages.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_store_pgs_title",
								"std" 	=> "Store",
								"type" 	=> "text"),
								
					array(  	"name" 	=> __('Multiple and Single Product pages - Alternative Page Title','wpShop'),
								"desc" 	=> __('Check this setting if you prefer to display the Current Category Title when browsing Multiple and Single Product pages','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_store_pgs_titleAlt",
								"type" 	=> "checkbox",
								"std" 	=> "false"),

				array(    	"type" 	=> "close"),
			array(   	"type" => "close"),
			
			// Featured Area
			array( 		"name" 	=> __('Your Front Page Featured Section','wpShop'),
						"type" 	=> "title"),
									
				array(    	"type" 	=> "open"),

					array(  	"name" 	=> __('What to feature?','wpShop'),
								"desc" 	=> __('Select the type of content to feature','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_feature_option",
								"type" 	=> "select2",
								"std" 	=> "All Main (1st level) Shop Categories",
								"vals" 	=> array(__('All Main (1st level) Shop Categories','wpShop')."|main_cats",__('Individual Products (must be made Sticky!)','wpShop')."|sticky_posts",__('Specific Categories (please define which ones below)','wpShop')."|cats",__('Custom Image with Custom Link (please define the image path and link below)','wpShop')."|custom_img",__('Custom 4 Images with Custom Links (defined below)','wpShop')."|custom_images")),
								
					array(    	"name" 	=> __('Featuring Specific Categories - The ID\'s','wpShop'),
								"desc" 	=> __('If you have selected to feature specific categories from above, please enter their category ID\'s.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_myFeaturedCats_include",
								"std" 	=> "",
								"type" 	=> "text"),
								
					array(  	"name" 	=> __('Featuring Specific Categories - Order by','wpShop'),
								"desc" 	=> "All available options are in the drop down",
								"id" 	=> $CONFIG_WPS['shortname']."_myFeaturedCats_orderbyOption",
								"type" 	=> "select2",
								"std" 	=> "Sort categories by name",
								"vals" 	=> array(__('Sort categories by name','wpShop')."|name",__('Sort by ID','wpShop')."|ID",__('Sort by slug','wpShop')."|slug","Sort by count|count")),

					array(    	"name" 	=> __('Featuring Specific Categories - Order','wpShop'),
								"desc" 	=> "All available options are in the drop down",
								"id" 	=> $CONFIG_WPS['shortname']."_myFeaturedCats_orderOption",
								"type" 	=> "select2",
								"std" 	=> "Ascending",
								"vals" 	=> array(__('Ascending','wpShop')."|ASC",__('Descending','wpShop')."|DESC")),
								
					array(    	"name" 	=> __('Featuring a Custom Image - the image path','wpShop'),
								"desc" 	=> __('If you have selected to feature a custom image please enter it\'s image file path here. Note: Omit the file type (.jpg, .png, etc.) - you will define it later on!','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_custom_img_path",
								"std" 	=> "",
								"type" 	=> "text"),
								
					array(  	"name" 	=> __('Link the Custom Image?','wpShop'),
								"desc" 	=> __('Check this setting if you want to create a custom link for the image above.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_custom_img_link_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(    	"name" 	=> __('Featuring a Custom Image - the link','wpShop'),
								"desc" 	=> __('If you have selected to feature a custom image please enter the URL you want the image to link to.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_custom_img_link",
								"std" 	=> "",
								"type" 	=> "text"),

					array(    	"name" 	=> __('Custom Image 1 - image URL','wpShop'),
								"desc" 	=> __('Please enter the URL of image.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_custom_images_url_1",
								"std" 	=> "",
								"type" 	=> "text"),

					array(    	"name" 	=> __('Custom Image 1 - the Link','wpShop'),
								"desc" 	=> __('Please enter the URL you want the image to link to.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_custom_images_link_1",
								"std" 	=> "",
								"type" 	=> "text"),

					array(    	"name" 	=> __('Custom Image 2 - image URL','wpShop'),
								"desc" 	=> __('Please enter the URL of image.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_custom_images_url_2",
								"std" 	=> "",
								"type" 	=> "text"),

					array(    	"name" 	=> __('Custom Image 2 - the Link','wpShop'),
								"desc" 	=> __('Please enter the URL you want the image to link to.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_custom_images_link_2",
								"std" 	=> "",
								"type" 	=> "text"),

					array(    	"name" 	=> __('Custom Image 3 - image URL','wpShop'),
								"desc" 	=> __('Please enter the URL of image.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_custom_images_url_3",
								"std" 	=> "",
								"type" 	=> "text"),

					array(    	"name" 	=> __('Custom Image 3 - the Link','wpShop'),
								"desc" 	=> __('Please enter the URL you want the image to link to.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_custom_images_link_3",
								"std" 	=> "",
								"type" 	=> "text"),

					array(    	"name" 	=> __('Custom Image 4 - image URL','wpShop'),
								"desc" 	=> __('Please enter the URL of image.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_custom_images_url_4",
								"std" 	=> "",
								"type" 	=> "text"),

					array(    	"name" 	=> __('Custom Image 4 - the Link','wpShop'),
								"desc" 	=> __('Please enter the URL you want the image to link to.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_custom_images_link_4",
								"std" 	=> "",
								"type" 	=> "text"),


					array(  	"name" 	=> __('Which effect?','wpShop'),
								"desc" 	=> __('Select your preferred effect for the images on your frontpage.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_featureEffect_option",
								"type" 	=> "select2",
								"std" 	=> "Simple Fade In - Out effect",
								"vals" 	=> array(__('Simple Fade In - Out effect','wpShop')."|innerfade_effect",__('Fade In - Out with Caption','wpShop')."|Slider_effect",__('Using the Cycle Plugin','wpShop')."|cycle_effect")),
								
					array(    	"name" 	=> __('Caption Position','wpShop'),
								"desc" 	=> __('If you have selected "Fade In - Out with Caption" from above, please enter the Caption\'s position. Available options are: "top" and "bottom" CASE SENSITIVE!','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_caption_position",
								"std" 	=> "top",
								"type" 	=> "text"),
								
				array(    	"type" 	=> "close"),
			array(   	"type" => "close"),
			
			// Sidebar
			array( 		"name" 	=> __('Your Sidebar','wpShop'),
						"type" 	=> "title"),
									
				array(    	"type" 	=> "open"),

					array(  	"name" 	=> __('Sidebar Location Options','wpShop'),
								"desc" 	=> __('Select the position of your Sidebar.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_sidebar_option",
								"type" 	=> "select2",
								"std" 	=> "Align Right",
								"vals" 	=> array(__('Align Right','wpShop')."|alignRight",__('Align Left','wpShop')."|alignLeft")),
								
					array(  	"name" 	=> __('Remove the Front Page Sidebar?','wpShop'),
								"desc" 	=> __('Check this option if you do not want to display the Sidebar on the Front Page','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_front_sidebar_disable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
				array(    	"type" 	=> "close"),
			array(   	"type" => "close"),

			// Customer Area
			array( 		"name" 	=> __('Customer Area','wpShop'),
						"type" 	=> "title"),
									
				array(    	"type" 	=> "open"),

					array(    	"name" 	=> __('Login page','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_account_login_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

					array(    	"name" 	=> __('Register page','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_account_register_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

					array(    	"name" 	=> __('Reset Password page','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_account_reset_pass_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

					array(    	"name" 	=> __('My Profile page','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_account_my_profile_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

					array(    	"name" 	=> __('My History page','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_account_my_history_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

					array(    	"name" 	=> __('My Purchases page','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_account_my_purchases_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

					array(    	"name" 	=> __('My Alerts page','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_account_my_alerts_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

					array(    	"name" 	=> __('My Wishlist page','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_account_my_wishlist_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

				array(    	"type" 	=> "close"),
			array(   	"type" => "close"),

			array( 		"name" 	=> __('Alert Options','wpShop'),
						"type" 	=> "title"),
									
				array(    	"type" 	=> "open"),

					array(  	"name" 	=> __('Activate Alerts','wpShop'),
								"desc" 	=> __('Check this setting for activating Alerts function.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_alerts_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),

					array(  	"name" 	=> __('Excluded Categories','wpShop'),
								"desc" 	=> __('Categories list shown on create alert form.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_alerts_excluded_categories",
								"vals"  => $shop_wp_cats,
								"type" 	=> "multi-categories",
								"std" 	=> "false"),

					array(  	"name" 	=> __('Most popular items','wpShop'),
								"desc" 	=> __('Categories list shown on create alert form.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_alerts_itbags",
								"vals"  => $shop_wp_cats,
								"type" 	=> "alert-itbags-items",
								"std" 	=> "false"),

					array(  	"name" 	=> __('Favourite Brands','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_alerts_favourite_brands",
								"type" 	=> "multi-brands",
								"std" 	=> "false"),

					array(  	"name" 	=> __('Alerts Notification Subject','wpShop'),
								"desc" 	=> __('Subject of Alerts Notification Email.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_alerts_notification_subject",
								"std" 	=> "",
								"type" 	=> "text"),					

					array(  	"name" 	=> __('Alert Popup Follow Brands','wpShop'),
								"desc" 	=> __('Brands list shown on create alert popup.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_alerts_popup_brands",
								"type" 	=> "multi-brands",
								"std" 	=> "false"),

				array(    	"type" 	=> "close"),
			array(   	"type" => "close"),

			// Custom Taxonomies
			array( 		"name" 	=> __('"Shop by" Options','wpShop'),
						"type" 	=> "title"),
									
				array(    	"type" 	=> "open"),

					array(    	"name" 	=> __('Search Results page','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_search_results_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

					array(  	"name" 	=> __('Shop by Outfit','wpShop'),
								"desc" 	=> __('Check this setting if you want to use the "Shop by Outfit" custom taxonomy.','wpShop')."<br/><b>".__('After activating one or more custom taxonomies please remember to save your ','wpShop')."<a href='".get_option('siteurl')."/wp-admin/options-permalink.php'>".__('permalink structure','wpShop')."</a>".__(' again!','wpShop')."</b><br/><b>".__('If any of the options below do not meet your online shop needs please feel free to request custom made ones.','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_shopByOutfit_option",
								"type" 	=> "checkbox",
								"std" 	=> "false"),

					array(  	"name" 	=> __('Shop by Fit','wpShop'),
								"desc" 	=> __('Check this setting if you want to use the "Shop by Fit" custom taxonomy.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shopByFit_option",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(  	"name" 	=> __('Shop by Size','wpShop'),
								"desc" 	=> __('Check this setting if you want to use the "Shop by Size" custom taxonomy.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shopBySize_option",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(  	"name" 	=> __('Shop by Colour','wpShop'),
								"desc" 	=> __('Check this setting if you want to use the "Shop by Colour" custom taxonomy.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shopByColour_option",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(  	"name" 	=> __('Shop by Brand','wpShop'),
								"desc" 	=> __('Check this setting if you want to use the "Shop by Brand" custom taxonomy.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shopByBrand_option",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(  	"name" 	=> __('Shop by Selection','wpShop'),
								"desc" 	=> __('Check this setting if you want to use the "Shop by Selection" custom taxonomy.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shopBySelection_option",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(  	"name" 	=> __('Shop by Style','wpShop'),
								"desc" 	=> __('Check this setting if you want to use the "Shop by Style" custom taxonomy.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shopByStyle_option",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(  	"name" 	=> __('Shop by Price','wpShop'),
								"desc" 	=> __('Check this setting if you want to use the "Shop by Price" custom taxonomy.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shopByPrice_option",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
				array(    	"type" 	=> "close"),
			array(   	"type" => "close"),
			
			// Search
			array( 		"name" 	=> __('Your Search','wpShop'),
						"type" 	=> "title"),
									
				array(    	"type" 	=> "open"),
				
					array(		"name" 	=> __('Use Search input in header','wpShop'),
								"desc" 	=> __('Check this setting if you want to display a regular search input field over the text link.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_search_input",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(  	"name" 	=> __('Display a "Search" link?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display a "search" link. Leave unchecked if you checked the option above to display a regular search input field.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_search_link_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(    	"name" 	=> __('Page Navigation - "Search" Page','wpShop'),
								"desc" 	=> __('If you have checked the setting to display a Search link, select the Page you have created for this purpose.','wpShop')."<br/><b>".__('Note: Remember to use the "My Search" custom page template for this page!','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_pgNavi_searchOption",
								"type" 	=> "select",
								"vals" 	=> $nws_wp_pages,
								"std" 	=> "Search"),

					array(		"name" 	=> __('Search Title','wpShop'),
								"desc" 	=> __('Enter the title you would like to have appear in place of "Looking for something specific?" (inside the overlay that opens when you click on the Search link)','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_search_title",
								"std" 	=> "Looking for something specific?",
								"type"	=> "text"),
								
					array(		"name" 	=> __('Search Text','wpShop'),
								"desc" 	=> __('Enter the text you would like to have appear below the "Looking for something specific?" title','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_search_text",
								"std" 	=> "",
								"type" 	=> "textarea"),

					array(		"name" 	=> __('Search only in Categories','wpShop'),
								"desc" 	=> __('Enter category ID (x,y,z)','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_search_only_cats",
								"std" 	=> "",
								"type"	=> "text"),

					array(		"name" 	=> __('Exclude Categories from Search Results','wpShop'),
								"desc" 	=> __('If you are using a Blog, please exclude it\'s category ID as well as the IDs of it\'s subcategories from being searched like so: -x,-y,-z where x,y,z the IDs','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_search_excl",
								"std" 	=> "",
								"type"	=> "text"),
								
					array(  	"name" 	=> __('Search Results Excerpt','wpShop'),
								"desc" 	=> __('Check this setting if you want to display an excerpt (teaser text) taken from the Product\'s Description or the excerpt write panel below the Product\'s image in Search Results.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_search_teaser_option",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
				array(    	"type" 	=> "close"),
			array(   	"type" => "close"),	
			
			// Footer
			array( 		"name" 	=> __('Your Footer','wpShop'),
						"type" 	=> "title"),
									
				array(    	"type" 	=> "open"),

					array(  	"name" 	=> __('Footer Size Options','wpShop'),
								"desc" 	=> __('Select the size of your footer.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_footer_option",
								"type" 	=> "select2",
								"std" 	=> "Small Footer",
								"vals" 	=> array(__('Small Footer','wpShop')."|small_footer",__('Large Footer','wpShop')."|large_footer")),
								
				array (    	"type" 	=> "close"),
			array(   	"type" => "close"),

			// Bit.ly Settings
			array( 		"name" 	=> __('Bit.ly Settings','wpShop'),
						"type" 	=> "title"),
									
				array(    	"type" 	=> "open"),

					array(  	"name" 	=> __('API Version','wpShop'),
								"desc" 	=> __('Bit.ly Api Version.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_bitly_version",
								"std" 	=> "",
								"type" 	=> "text"),

					array(  	"name" 	=> __('API Username','wpShop'),
								"desc" 	=> __('Bit.ly Api Username.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_bitly_username",
								"std" 	=> "",
								"type" 	=> "text"),

					array(  	"name" 	=> __('API Key','wpShop'),
								"desc" 	=> __('Bit.ly Api Key.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_bitly_apikey",
								"std" 	=> "",
								"type" 	=> "text"),

				array (    	"type" 	=> "close"),
			array(   	"type" => "close"),

			// InfusionSoft Settings
			array( 		"name" 	=> __('InfusionSoft Settings','wpShop'),
						"type" 	=> "title"),
									
				array(    	"type" 	=> "open"),

					array(  	"name" 	=> __('API Name','wpShop'),
								"desc" 	=> __('https://APINAME.infusionsoft.com','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_infusionsoft_api_name",
								"std" 	=> "",
								"type" 	=> "text"),

					array(  	"name" 	=> __('API Key','wpShop'),
								"desc" 	=> __('InfusionSoft Api Key.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_infusionsoft_api_key",
								"std" 	=> "",
								"type" 	=> "text"),

				array (    	"type" 	=> "close"),
			array(   	"type" => "close"),

			// Channel Advisor Settings
			array( 		"name" 	=> __('Channel Advisor Settings','wpShop'),
						"type" 	=> "title"),
									
				array(    	"type" 	=> "open"),

					array(  	"name" 	=> __('Profile ID','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_channel_advisor_profile_id",
								"std" 	=> "",
								"type" 	=> "text"),

					array(  	"name" 	=> __('Developer Key','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_channel_advisor_developer_key",
								"std" 	=> "",
								"type" 	=> "text"),

					array(  	"name" 	=> __('Password','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_channel_advisor_password",
								"std" 	=> "",
								"type" 	=> "text"),

				array (    	"type" 	=> "close"),
			array(   	"type" => "close"),

			// Emarsys Settings
			array( 		"name" 	=> __('Emarsys Settings','wpShop'),
						"type" 	=> "title"),
									
				array(    	"type" 	=> "open"),

					array(  	"name" 	=> __('API URL','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_emarsys_url",
								"std" 	=> "",
								"type" 	=> "text"),

					array(  	"name" 	=> __('API Username','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_emarsys_username",
								"std" 	=> "",
								"type" 	=> "text"),

					array(  	"name" 	=> __('API Password','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_emarsys_password",
								"std" 	=> "",
								"type" 	=> "text"),

				array (    	"type" 	=> "close"),
			array(   	"type" => "close"),

		array (		"type" 	=> "fieldset_end"),	
//###############################################################################################################
		// Tab 2 Navigation			
		array (		"type" 	=> "fieldset_start",
					"class" =>"design",
					"id" 	=>"sec_navigation_settings"),
							
			array ( 	"name" 	=> __('Navigation','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),

					array(  	"name" 	=> __('Activate New Menu','wpShop'),
								"desc" 	=> __('Check this setting if you want to activate a use the New Menu with Men and Women sections.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_nav_new_menu",
								"type" 	=> "checkbox",
								"std" 	=> "false"),

					array(  	"name" 	=> __('Activate Custom Menus','wpShop'),
								"desc" 	=> __('Check this setting if you want to activate a use the Custom Menus introduced in WordPress 3.0','wpShop')."<br/><strong>".__('Please note that activating this setting makes the Page Navigation settings you see in the settings block directly below inactive. This does not affect however the "Special Navigation Links (Top Right)" settings not the "Category Subnavigation Settings" further down! (Meaning that you still need to set those settings.)','wpShop')."<strong>",
								"id" 	=> $CONFIG_WPS['shortname']."_wp_custom_menus",
								"type" 	=> "checkbox",
								"std" 	=> "false"),

					array(    	"name" 	=> __('Main Page Navigation - Show "Home" Link title','wpShop'),
								"desc" 	=> __('If you are not using a Static Front Page but you still like to have "Home" as the first item in the list of your page navigation then enter 1 or type the link text you\'d like to have if it\'s other than "Home". Enter 0 if you want none. The URL assigned to "Home" is pulled from the Blog address (URL)','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pgNavi_homeOption",
								"std" 	=> "",
								"type" 	=> "text"),
								
					array(    	"name" 	=> __('Main Page Navigation - Include Only','wpShop'),
								"desc" 	=> __('Enter the','wpShop')."<a target='_blank' href='http://en.support.wordpress.com/pages/'>".__(' page IDs ','wpShop')."</a>".__('of the 1st (main) level pages to INCLUDE in the main page navigation.','wpShop')."<br/><b>".__('Note: You may use either "Include" or "Exclude" NOT both!','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_pgNavi_inclOption",
								"std" 	=> "",
								"type" 	=> "text"),					
								
					array(    	"name" 	=> __('Main Page Navigation - Exclude','wpShop'),
								"desc" 	=> __('This is an alternative option to the above. Enter the','wpShop')."<a target='_blank' href='http://en.support.wordpress.com/pages/'>".__(' page IDs ','wpShop')."</a>".__('of the 1st (main) level pages to EXCLUDE from the main page navigation.','wpShop')."<br/><b>".__('Note: You may use either "Include" or "Exclude" NOT both! You may want for example to exclude the "Login", "Register", and "Search" pages as well as the "My Account" and "Recover Password" pages!','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_pgNavi_exclOption",
								"std" 	=> "",
								"type" 	=> "text"),

					array(  	"name" 	=> __('Main Page Navigation - Sorting','wpShop'),
								"desc" 	=> __('Select how your pages should be sorted. All available options are in the drop down','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pgNavi_sortOption",
								"type" 	=> "select2",
								"std" 	=> "Sort Pages by Page Order",
								"vals" 	=> array(__('Sort Pages by Page Order','wpShop')."|menu_order",__('Sort by numeric Page ID','wpShop')."|id",__('Sort Pages alphabetically by title','wpShop')."|post_title",__('Sort by creation time','wpShop')."|post_date",__('Sort alphabetically by Post slug','wpShop')."|post_name")),

					array(  	"name" 	=> __('Page - Category Hybrid Navigation?','wpShop'),
								"desc" 	=> __('Some of you have asked for the option of combining the main page navigation with categories. Checking this setting will give you the following: (Home link) (list of Top Level Categories) (list of Top Level Pages) (Blog link -if a blog is used). Use the page settings above to sort your pages. Use the category navigation settings below for sorting your category links.','wpShop')."<br/><b>".__('Note: This type of menu makes the category list in the Frontpage Sidebar redundant so you may want to remove it using the setting lower in this section.','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_hybrid_menu_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(  	"name" 	=> __('Use drop downs on the Page - Category Hybrid Navigation?','wpShop'),
								"desc" 	=> __('Check this setting if you want subcategories and subpages to show in javascript drop downs when hovering main navigation links','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_hybrid_dropMenu_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
				
				array(   	"type" => "close"),
			array(   	"type" => "close"),
			
			// Special Navigation Settings
			array( 		"name" 	=> __('Special Navigation Links (Top Right)','wpShop'),
						"type" 	=> "title"),
								
				array(    	"type" 	=> "open"),
					
					array(    	"name" 	=> __('"My Account" Page','wpShop'),
								"desc" 	=> __('Select the Page you have created for this purpose. (Applies if you have activated the Customer Membership Area)','wpShop')."<br/><b>".__('Note: Appears after a Customer has logged in. This must be a 1st level page and please remember to use the "Account Customer Area" custom page template for this page!','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_customerAreaPg",
								"type" 	=> "select",
								"vals" 	=> $nws_wp_pages,
								"std" 	=> "Select a Page"),
					
					array(    	"name" 	=> __('"Login" Page','wpShop'),
								"desc" 	=> __('Select the Page you have created for this purpose. (Applies if you have activated the Customer Membership Area)','wpShop')."<br/><b>".__('Note: This must be a 1st level page and please remember to use the "Account Login" custom page template for this page!','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_pgNavi_logOption",
								"type" 	=> "select",
								"vals" 	=> $nws_wp_pages,
								"std" 	=> "Login"),
								
					array(    	"name" 	=> __('"Register" Page','wpShop'),
								"desc" 	=> __('Select the Page you have created for this purpose. (Applies if you have activated the Customer Membership Area)','wpShop')."<br/><b>".__('Note: This must be a 1st level page and please remember to use the "Account Register" custom page template for this page!','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_pgNavi_regOption",
								"type" 	=> "select",
								"vals" 	=> $nws_wp_pages,
								"std" 	=> "Register"),
								
					array(    	"name" 	=> __('"Wishlist" Link Text','wpShop'),
								"desc" 	=> __('Enter the link text you\'d like in place of "Wishlist". (Applies if you have activated the Customer Membership Area)','wpShop')."<br/><b>".__('Note: This is NOT a separate page so do not create one. Appears after a Customer has logged in.','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_wishListLink_option",
								"std" 	=> "My Wishlist",
								"type" 	=> "text"),
					
					array(    	"name" 	=> __('"Shopping Basket" Link Title','wpShop'),
								"desc" 	=> __('Enter the link title you\'d like to have in it\'s place.','wpShop')."<br/><b>".__('Note: You are not needed to create a separate page for this.','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_pgNavi_cartOption",
								"std" 	=> "Shopping Basket",
								"type" 	=> "text"),
								
					array(    	"name" 	=> __('"Enquiry Basket" Link Title','wpShop'),
								"desc" 	=> __('Enter the link title you\'d like to have in it\'s place.','wpShop')."<br/><b>".__('Note: You are not needed to create a separate page for this. Displays if you have selected the "Enquiry Shop Mode" (See Shop settings).','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_pgNavi_inquireOption",
								"std" 	=> "Enquiry Basket",
								"type" 	=> "text"),
								
					array(    	"name" 	=> __('Shopping Basket / Enquiry Basket Icon','wpShop'),
								"desc" 	=> __('Enter the image file name you created.','wpShop')."<br/><b>".__('Make sure you have uploaded it inside your activated child theme\'s images folder!','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_shopping_icon",
								"std" 	=> "shopping_icon.jpg",
								"type" 	=> "text"),
								
					array(  	"name" 	=> __('Set the Shopping Cart Item Number text on a new line?','wpShop'),
								"desc" 	=> __('','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_br_yes",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(  	"name" 	=> __('Display the Shopping Basket Total Item Value','wpShop'),
								"desc" 	=> __('','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_totalItemValue_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),

				array(   	"type" => "close"),
			array(   	"type" => "close"),
			
			//	Category Navigation Settings
			array( 		"name" 	=> __('Category Navigation Settings','wpShop'),
						"type" 	=> "title"),
						"id" 	=> "catNaviOptions",
						
				array(    	"type" 	=> "open"),
				
					array(  	"name" 	=> __('Use the Category Navigation in the Sidebar?','wpShop'),
								"desc" 	=> __('Check this option if you want to display your Shop\'s categories in the Front Page Sidebar. You may want to leave it unchecked if you are using the Page - Category Hybrid Navigation.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_sidebar_catNavi_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),				

					array(  	"name" 	=> __('Categories in Hybrid Navi - Order by','wpShop'),
								"desc" 	=> __('Set if WP3.0 Custom Menus are not used and the Hybrid Navi is activated','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_catNavi_orderbyOption",
								"type" 	=> "select2",
								"std" 	=> __('Sort categories by name','wpShop'),
								"vals" 	=> array(__('Sort categories by name','wpShop')."|name",__('Sort by ID','wpShop')."|ID",__('Sort by slug','wpShop')."|slug",__('Sort by count','wpShop')."|count")),

					array(    	"name" 	=> __('Categories in Hybrid Navi - Order','wpShop'),
								"desc" 	=> __('Set if WP3.0 Custom Menus are not used and the Hybrid Navi is activated','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_catNavi_orderOption",
								"type" 	=> "select2",
								"std" 	=> __('Ascending','wpShop'),
								"vals" 	=> array(__('Ascending','wpShop')."|ASC",__('Descending','wpShop')."|DESC")),
								
					array(    	"name" 	=> __('Categories in Hybrid Navi - Include Only','wpShop'),
								"desc" 	=> __('Set if WP3.0 Custom Menus are not used and the Hybrid Navi is activated','wpShop')."<br/>".__('Your Category list will by default include all your categories so if you want to include only certain ones then enter the','wpShop')."<a target='_blank' href='http://www.wprecipes.com/how-to-find-wordpress-category-id'>".__(' category IDs ','wpShop')."</a>".__('you would like to have included. Leave empty if you want the Default Option.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_catNavi_inclOption",
								"std" 	=> "",
								"type" 	=> "text"),
								
					array(    	"name" 	=> __('Categories in Hybrid Navi - Exclude Only','wpShop'),
								"desc" 	=> __('Set if WP3.0 Custom Menus are not used and the Hybrid Navi is activated','wpShop')."<br/>".__('Your Category list will by default exclude none of your categories so if you want to exclude certain ones then enter the','wpShop')."<a target='_blank' href='http://www.wprecipes.com/how-to-find-wordpress-category-id'>".__(' category IDs ','wpShop')."</a>".__('you would like to have excluded. Leave empty if you want the Default Option.','wpShop')."<br/><b>".__('Note: If you are using a Blog section along with your Shop you will want to exclude it\'s category ID here!','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_catNavi_exclOption",
								"std" 	=> "",
								"type" 	=> "text"),
								
					array(    	"name" 	=> __('Category Subnavigation - Number of rows','wpShop'),
								"desc" 	=> __('When you view a category page that displays products and the current main category has subcategories then these are displayed in a convenient sub-navigation for orientation and usability reasons below the category specific image, as you see ','wpShop')."<a href='http://thefurniturestore.sarah-neuber.de/category/main-category-one/subcategory-one' target='_blank'>".__('here','wpShop')."</a>".__(' Enter the number of rows these subcategories should be displayed in.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_catSubNav_rows",
								"std" 	=> "2",
								"type" 	=> "text"),
								
					array(  	"name" 	=> __('Category Subnavigation - Order by','wpShop'),
								"desc" 	=> __('All available options are in the drop down','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_catSubNav_orderbyOption",
								"type" 	=> "select2",
								"std" 	=> __('Sort categories by name','wpShop'),
								"vals" 	=> array(__('Sort categories by name','wpShop')."|name",__('Sort by ID','wpShop')."|ID",__('Sort by slug','wpShop')."|slug",__('Sort by count','wpShop')."|count")),

					array(    	"name" 	=> __('Category Subnavigation - Order','wpShop'),
								"desc" 	=> __('All available options are in the drop down','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_catSubNav_orderOption",
								"type" 	=> "select2",
								"std" 	=> __('Ascending','wpShop'),
								"vals" 	=> array(__('Ascending','wpShop')."|ASC",__('Descending','wpShop')."|DESC")),
								
				array(    	"type" 	=> "close"),
			array(   	"type" => "close"),
		array (		"type" 	=> "fieldset_end"),
//###############################################################################################################
		//Tab 3 Multiple Product Pages
		array (		"type" 	=> "fieldset_start",
					"class" =>"design",
					"id" 	=>"sec_multiProd_settings"),
					
			array ( 	"name" 	=> __('Multiple Product Pages','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),
				
					array(  	"name" 	=> __('Display Category Decription','wpShop'),
								"desc" 	=> __('Check this setting if you want to display the current category description below the category specific image','wpShop')."<br/><b>".__('Note: The Description text can be entered in the "Description" text area when you edit each category. You may use some html.','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_catDescr_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),

					array(  	"name" 	=> __('Category Specific Images - File Type','wpShop'),
								"desc" 	=> __('Select the file type you are using for your category specific images. This setting is used on the Frontpage if you chose to showcase main category specific images, and on the Multiple Product Category Pages for the category specific image that is featured at the top.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_catimg_file_type",
								"type" 	=> "select2",
								"std" 	=> "jpg",
								"vals" 	=> array("jpg|jpg","png|png","gif|gif")),
								
					array(  	"name" 	=> __('Category Columns on Category pages','wpShop'),
								"desc" 	=> __('Select the number of columns you would like to have ','wpShop')."<br/><b>".__('This setting will apply on Product Category Pages that display categories!','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_catCol_option",
								"type" 	=> "select2",
								"std" 	=> "3 Columns",
								"vals" 	=> array(__('3 Columns','wpShop')."|catCol3",__('4 Columns','wpShop')."|catCol4")),
								
					array(  	"name" 	=> __('Main Categories - Order by','wpShop'),
								"desc" 	=> __('All available options are in the drop down. This setting (and the one below) is used if you chose to showcase main category specific images on the Front Page. It gives you more control over the order they appear.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_mainCat_orderbyOption",
								"type" 	=> "select2",
								"std" 	=> "Sort by ID",
								"vals" 	=> array(__('Sort by ID','wpShop')."|ID",__('Sort by Count','wpShop')."|count",__('Sort categories by Name','wpShop')."|name",__('Sort categories by Slug','wpShop')."|slug")),

					array(    	"name" 	=> __('Main Categories - Order','wpShop'),
								"desc" 	=> __('All available options are in the drop down','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_mainCat_orderOption",
								"type" 	=> "select2",
								"std" 	=> "Ascending",
								"vals" 	=> array(__('Ascending','wpShop')."|ASC",__('Descending','wpShop')."|DESC")),
								
					array(  	"name" 	=> __('Your Subcategories - Order by','wpShop'),
								"desc" 	=> __('All available options are in the drop down. This setting (and the one below) is used on Product Category Pages that display Subcategories','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_secondaryCat_orderbyOption",
								"type" 	=> "select2",
								"std" 	=> "Sort by ID",
								"vals" 	=> array(__('Sort by ID','wpShop')."|ID",__('Sort by Count','wpShop')."|count",__('Sort categories by Name','wpShop')."|name",__('Sort categories by Slug','wpShop')."|slug")),

					array(    	"name" 	=> __('Your Subcategories - Order','wpShop'),
								"desc" 	=> __('All available options are in the drop down','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_secondaryCat_orderOption",
								"type" 	=> "select2",
								"std" 	=> "Ascending",
								"vals" 	=> array(__('Ascending','wpShop')."|ASC",__('Descending','wpShop')."|DESC")),
								
					array(  	"name" 	=> __('Show Subcategory Title and Description on hover?','wpShop'),
								"desc" 	=> __('When on a Product Category page that displays Subcategories you have the option to display the subcategory\'s description on hover. Check this setting to enable this.','wpShop')."<br/><b>".__('Note: The Description text can be entered in the "Description" text area when you edit each category. You may use some html.','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_catTeaser_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(  	"name" 	=> __('Show Subcategory Title below the subcategory specific image?','wpShop'),
								"desc" 	=> __('When on a Product Category page that displays Subcategories you have the option to display the subcategory\'s title below it\'s corresponding image. Check this setting to enable this.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_catTitle_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),

				array(    	"type" 	=> "close"),
			array(   	"type" => "close"),

			// tag pages
			array( 		"name" 	=> __('Tag and Custom Taxonomy pages','wpShop'),
						"type" 	=> "title"),
								
				array(    	"type" 	=> "open"),	

					array(  	"name" 	=> __('Term Specific Images - File Type','wpShop'),
								"desc" 	=> __('Select the file type of your term (tags and custom taxonomy terms) specific images.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_tagimg_file_type",
								"type" 	=> "select2",
								"std" 	=> "jpg",
								"vals" 	=> array("jpg|jpg","png|png","gif|gif")),
								
					array(  	"name" 	=> __('Display Tag / Custom Term Decription?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display the current tag / custom term\s description below the tag / custom term specific image','wpShop')."<br/><b>".__('Note: The Description text can be entered in the "Description" text area when you edit each tag or custom term. You may use some html.','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_termDescr_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),

				array(    	"type" 	=> "close"),
			array(   	"type" => "close"),

			// Product Posts
			array( 		"name" 	=> __('Product Post Settings on Category and other Multiple Product Pages','wpShop'),
						"type" 	=> "title"),
								
				array(    	"type" 	=> "open"),	

					array(  	"name" 	=> __('Product Columns on Category pages','wpShop'),
								"desc" 	=> __('Select the number of columns you would like to have ','wpShop')."<br/><b>".__('This setting will apply on category pages that display products!','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_prodCol_option",
								"type" 	=> "select2",
								"std" 	=> "4 Columns",
								"vals" 	=> array(__('4 Columns','wpShop')."|prodCol4",__('3 Columns','wpShop')."|prodCol3",__('2 Columns','wpShop')."|prodCol2",__('1 Column','wpShop')."|prodCol1")),
										
					array(  	"name" 	=> __('Product Columns on Tag and Custom Taxonomy pages','wpShop'),
								"desc" 	=> __('Select the number of columns you would like to have','wpShop')."<b>".__(' when on a tag or custom taxonomy page ','wpShop')."</b>".__('eg. ','wpShop')."<a href='http://thefurniturestore.sarah-neuber.de/brands/a-brand' target='_blank'>".__('here','wpShop')."</a>",
								"id" 	=> $CONFIG_WPS['shortname']."_tagCol_option",
								"type" 	=> "select2",
								"std" 	=> "4 Columns",
								"vals" 	=> array(__('4 Columns','wpShop')."|tagCol4",__('3 Columns','wpShop')."|tagCol3",__('2 Columns','wpShop')."|tagCol2",__('1 Columns','wpShop')."|tagCol1")),
										
					array(  	"name" 	=> __('Your Products - Order by','wpShop'),
								"desc" 	=> __('All available options are in the drop down','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_prods_orderbyOption",
								"type" 	=> "select2",
								"std" 	=> "Order by ID",
								"vals" 	=> array(__('Order by ID','wpShop')."|ID",__('Order by Title','wpShop')."|title",__('Order by Date','wpShop'). "|date",__('Order by Parent','wpShop'). "|parent",__('Order by Comment Count','wpShop'). "|comment_count",__('Random Order','wpShop'). "|rand",__('None','wpShop'). "|none")),
					
					array(    	"name" 	=> __('Your Products - Order','wpShop'),
								"desc" 	=> __('All available options are in the drop down','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_prods_orderOption",
								"type" 	=> "select2",
								"std" 	=> "Ascending",
								"vals" 	=> array(__('Ascending','wpShop')."|ASC",__('Descending','wpShop')."|DESC")),
								
					array(    	"name" 	=> __('Overwrite the "Blog pages show at most" Admin Setting','wpShop'),
								"desc" 	=> __('When on "View All" you may want to overwrite the "Blog pages show at most" setting in Settings > Reading in order to show all products in that particular category.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_showpostsOverwrite_Option",
								"type" 	=> "select2",
								"std" 	=> "Yes",
								"vals" 	=> array(__('Yes','wpShop')."|showpostsOverwrite_yes",__('No','wpShop')."|showpostsOverwrite_no")),
								
					array(  	"name" 	=> __('Remove "Hover" effect?','wpShop'),
								"desc" 	=> __('On multiple product pages, when a product has more than 1 attached images then a hover effect is applied to it. In some situations you may not need it (eg the product original images are of varying heights ). Here is where you can remove it.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_hover_remove_option",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
							
					array(  	"name" 	=> __('Use WordPress generated image thumbnails?','wpShop'),
								"desc" 	=> __('Do you prefer to use the WordPress generated image thumbnails over the default proportionally resized thumbnails? Checking this option will display square-sized product images.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_wp_thumb",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
							
				array(    	"type" 	=> "close"),
			array(   	"type" => "close"),
			
			//Product Teaser & Ratings
			array( 		"name" 	=> __('Product Teaser &amp; Ratings','wpShop'),
						"type" 	=> "title"),
								
				array(    	"type"	=> "open"),
				
					array(  	"name" 	=> __('Show teaser','wpShop'),
								"desc" 	=> __('Check this setting if you want to display the product title, price and an optional short remark below each product image','wpShop')."<br/><b>".__('Note: For the optional short remark you must use the item_remarks custom field with your remark for the value','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_teaser_enable_option",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(  	"name" 	=> __('Show teaser between Product Title and Price','wpShop'),
								"desc" 	=> __('By default the theme will display the short teaser remark below the price.Check this setting if you want to display it between the product title and the price ','wpShop')."<br/><b>".__('Note: Unlike the default teaser remark this one is wrapped in a div so you can use some html like p. You\'ll use the item_remarks custom field again as above.','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_teaser2_enable_option",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(		"name" 	=> __('Display Product Title?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display the Product Title','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_prod_title",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(		"name" 	=> __('Display Product Price?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display the Product Price','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_prod_price",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(		"name" 	=> __('Display Product "Add to Basket" button?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display the "Add to Basket" button','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_prod_btn",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
				
					array(		"name" 	=> __('Activate Product Rating on Multiple Product Pages?','wpShop'),
								"desc" 	=> __('Requires that you install and active the','wpShop')."<a target='_blank' href='http://wordpress.org/extend/plugins/wp-postratings/'>".__(' WP-Postratings ','wpShop')."</a>".__('plugin','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_multiProd_rate_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
				array(   	"type" => "close"),
			array(   	"type" => "close"),
			
		array (		"type" 	=> "fieldset_end"),	
//###############################################################################################################
		//Tab 4 Customer Membership
		array (		"type" 	=> "fieldset_start",
					"class" =>"design",
					"id" 	=>"sec_membership_settings"),
							
			array ( 	"name" 	=> __('Customer Membership','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),
					
					array(    	"name" 	=> __('"Recover Password" Page','wpShop'),
								"desc" 	=> __('Select the Page you have created for this purpose. (Applies if you have activated the Customer Membership Area)','wpShop')."<br/><b>".__('Note: This must be a 1st level page and please remember to use the "Recover Password" custom page template for this page!','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_passLostPg",
								"type" 	=> "select",
								"vals" 	=> $nws_wp_pages,
								"std" 	=> "Recover Password"),
										
					array(  	"name" 	=> __('Show Logout Link?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display a log-out link in the Customer Membership Area landing page. (Applies if you have activated the "Customer Membership Area")','wpShop')."<br/><b>".__('Note: When a Customer is logged in, a Log-out link will always display in the Main Navigation in the header.','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_logoutLink_option",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
										
					array(    	"name" 	=> __('Wishlist intro text','wpShop'),
								"desc" 	=> __('Optional. Enter some intro text here that will display when on the "My Wishlist" section.','wpShop')."<br/><b>".__('Note: The text will be wrapped in a paragraph so if you like to use html tags please use only inline elements.','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_wishlistIntroText",
								"std" 	=> "Some optional text can be entered here from your Theme Options",
								"type" 	=> "textarea"),
								
					array(  	"name" 	=> __('Login Duration','wpShop'),
								"desc" 	=> __('Select how long you would like your customers to stay logged in before the system automatically logs them out!.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_login_duration",
								"type" 	=> "select2",
								"std" 	=> "30 min",
								"vals" 	=> array(__('30 min','wpShop')."|1800",__('1 hour','wpShop')."|3600",__('2 hours','wpShop')."|7200",__('4 hours','wpShop')."|14400",__('6 hours','wpShop')."|21600",__('8 hours','wpShop')."|28800",__('10 hours','wpShop')."|36000",__('12 hours','wpShop')."|43200",__('14 hours','wpShop')."|50400",__('16 hours','wpShop')."|57600",__('18 hours','wpShop')."|64800",__('20 hours','wpShop')."|72000",__('22 hours','wpShop')."|79200",__('24 hours','wpShop')."|86400")),
								
				array(   	"type" => "close"),
			array(   	"type" => "close"),
			
			//requesting additional information from registered customers
			array ( 	"name" 	=> __('Optional Member Info Section','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),
				
					array(    	"name" 	=> __('Main title','wpShop'),
								"desc" 	=> __('Heading to preceed the section. If left empty, the Optional Member Info blok will not appear.','wpShop')."<br/><b>".__('The purpose of this extra section is to be able to get some additional information from your registered customers and use as needed. This information will be saved in your Members Overview table','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_extrainfo_header",
								"std" 	=> "",
								"type" 	=> "text"),	
								
					array(    	"name" 	=> __('Additional Instructions','wpShop'),
								"desc" 	=> __('This can be used for some intro text or additional instructions for the form input fields that follow below. The text will be wrapped in a paragraph html tag so if you need any formating please use inline elements only.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_extrainfo_instruct",
								"std" 	=> "",
								"type" 	=> "textarea"),						
					
					array(    	"name" 	=> __('The Form Fields -Text Inputs','wpShop'),
								"desc" 	=> __('Enter the label text for each text input field. Each line break will give you a new text input field so if you want multiple text inputs enter the label text for each on a new line.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_extra_formfields",
								"std" 	=> "",
								"type" 	=> "textarea"),
								
					array(    	"name" 	=> __('Number of Columns','wpShop'),
								"desc" 	=> __('Please select','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_extra_formfieldsCol",
								"type" 	=> "select",
								"std" 	=> "1",
								"vals" => array("1", "2")),
								
				array(   	"type" => "close"),
			array(   	"type" => "close"),
		array (		"type" 	=> "fieldset_end"),
//###############################################################################################################
		// Tab 5 Single Products			
		array (		"type" 	=> "fieldset_start",
					"class" =>"design",
					"id" 	=>"sec_singleProd_settings"),
							
			//layout, navigation settings				
			array ( 	"name" 	=> __('Single Product Pages','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),

					array(  	"name" 	=> __('Activate Sidebar for Single Product Pages?','wpShop'),
								"desc" 	=> __('Check this setting if you want to have a sidebar when browsing Single Product Pages','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shop_single_sidebar_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),

					array(  	"name" 	=> __('Display Category Breadcrumb?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display the product\'s category link trail. It will appear above the product\'s image and description.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_catTrail_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
										
					array(  	"name" 	=> __('Display Link back to Parent Category?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display a link back to the product\'s parent category. It will appear above the product\'s image to the left.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_backLink_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
										
					array(  	"name" 	=> __('Display Links to Previous and Next Products','wpShop'),
								"desc" 	=> __('Check this setting if you want to display previous and next product links. They will appear above the product\'s description to the right','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_prodNav_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
										
					array(  	"name" 	=> __('Display also at bottom?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display previous and next product links as well as a link back to the product\'s parent category also at the bottom of the page','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_linksBottom_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(		"name" 	=> __('Previous Product Link Text','wpShop'),
								"desc" 	=> __('Enter the link text for the Previous Product','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_prevProdLinkText",
								"std" 	=> "Previous Product",
								"type" 	=> "text"),
					
					array(		"name" 	=> __('Next Product Link Text','wpShop'),
								"desc" 	=> __('Enter the link text for the Next Product','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_nextProdLinkText",
								"std" 	=> "Next Product",
								"type" 	=> "text"),
				
				array(   	"type" => "close"),
			array(   	"type" => "close"),
				
			//image effects				
			array ( 	"name" 	=> __('Product Image and Video/ Audio Settings','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),
				
					array(		"name" 	=> __('I am self-hosting video / audio','wpShop'),
								"desc" 	=> __('Check this option if you are.','wpShop')."<a target='_blank' href='http://flowplayer.org/'>".__(' Flowplayer ','wpShop')."</a>".__('will be activated for you.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_flowplayer_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
			
					array(    	"name" 	=> __('Product Image','wpShop'),
								"desc" 	=> __('Select the effect to be used on the Product\'s image(s)','wpShop')."<br/><b>".__('Note: There is no need to install any plugins. The necessary javascript files are already in place.','wpShop')."</b><br/><b>".__('If you select any of the Magic Zoom effects you will need to','wpShop')."<a target='_blank' href='http://www.magictoolbox.com/?ac=2WA2V3J'>".__(' purchase a license','wpShop')."</a></b>",
								"id" 	=> $CONFIG_WPS['shortname']."_prodImg_effect",
								"type" 	=> "select2",
								"std" 	=> "Magic Zoom",
								"vals" 	=> array(__('Magic Zoom','wpShop')."|mz_effect",__('Magic Zoom Plus','wpShop')."|mzp_effect",__('JQZoom','wpShop')."|jqzoom_effect",__('Lightbox','wpShop')."|lightbox",__('None! No effects please.','wpShop')."|no_effect")),
								
					array(  	"name" 	=> __('Multiple Product Images - Use Image Thumbs?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display Image Thumbnails over the default Numbered Thumbs next to the product\'s main image.','wpShop')."<br/><b>".__('Note: They only appear if more than one product image has been attached!','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_imgThumbs_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
										
					array(  	"name" 	=> __('Lightbox - Use Caption?','wpShop'),
								"desc" 	=> __('Check this setting if you want to show a caption below the image after it opens in the lightbox (fancybox) ','wpShop')."<br/><b>".__('Note: The caption is taken from the image\'s title attribute.','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_caption_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
										
					array(		"name" 	=> __('Video Tab','wpShop'),
								"desc" 	=> __('If you have embedded a Video then enter the text you\'d like for it\'s Tab','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_videoTabText",
								"std" 	=> "View a Sample",
								"type" 	=> "text"),
										
					array(		"name" 	=> __('Display Images Tab?','wpShop'),
								"desc" 	=> __('If you have embedded a Video then you have the option to display other Product Images alongside it. Check this option if you would like that.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_imagesTab_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
										
					array(		"name" 	=> __('Images Tab','wpShop'),
								"desc" 	=> __('If you have embedded a Video and you have selected to display other Product Images alongside it, enter the text you\'d like for the Images Tab','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_imagesTabText",
								"std" 	=> "Images",
								"type" 	=> "text"),
								
				array(   	"type" => "close"),
			array(   	"type" => "close"),
			
			//Product Variations
			array( 		"name" 	=> __('Product Variations (Select Dropdowns)','wpShop'),
						"type" 	=> "title"),
								
				array(    	"type"	=> "open"),
				
					array(  	"name" 	=> __('Order by','wpShop'),
								"desc" 	=> __('All the available options are found in the dropdown','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_prodVariations_orderBy",
								"type" 	=> "select2",
								"std" 	=> "Order by ID",
								"vals" 	=> array(__('Order Alphabeticaly by Key Name','wpShop')."|meta_key", __('Order by Time Created','wpShop')."|meta_id")),
								
					array(    	"name" 	=> __('Order','wpShop'),
								"desc" 	=> __('All the available options are found in the dropdown','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_prodVariations_order",
								"type" 	=> "select2",
								"std" 	=> "ASC",
								"vals" 	=> array(__('Ascending','wpShop')."|ASC", __('Descending','wpShop')."|DESC")),
				
				array(   	"type" => "close"),
			array(   	"type" => "close"),
			
			//Product Personalization
			array( 		"name" 	=> __('Product Personalization (Text Inputs and Textareas)','wpShop'),
						"type" 	=> "title"),
								
				array(    	"type"	=> "open"),
				
					array(  	"name" 	=> __('Order by','wpShop'),
								"desc" 	=> __('All the available options are found in the dropdown','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_prodPersonalization_orderBy",
								"type" 	=> "select2",
								"std" 	=> "Order by ID",
								"vals" 	=> array(__('Order Alphabeticaly by Key Name','wpShop')."|meta_key", __('Order by Time Created','wpShop')."|meta_id")),
								
					array(    	"name" 	=> __('Order','wpShop'),
								"desc" 	=> __('All the available options are found in the dropdown','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_prodPersonalization_order",
								"type" 	=> "select2",
								"std" 	=> "ASC",
								"vals" 	=> array(__('Ascending','wpShop')."|ASC", __('Descending','wpShop')."|DESC")),
				
				array(   	"type" => "close"),
			array(   	"type" => "close"),
			
			//Product Ratings
			array( 		"name" 	=> __('Product Rating','wpShop'),
						"type" 	=> "title"),
								
				array(    	"type"	=> "open"),
				
					array(		"name" 	=> __('Activate Product Rating on Single Product Pages?','wpShop'),
								"desc" 	=> __('Requires that you install and active the','wpShop')."<a target='_blank' href='http://wordpress.org/extend/plugins/wp-postratings/'>".__(' WP-Postratings ','wpShop')."</a>".__('plugin','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_singleProd_rate_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
				array(   	"type" => "close"),
			array(   	"type" => "close"),
			
			//related prods settings				
			array ( 	"name" 	=> __('Related Products Settings','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),

					array(  	"name" 	=> __('Display Related Products?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display (Category and/or Tag) Related Products. The section will appear below the Product\'s image(s) and only if (Category and/or Tag) Related Products exist.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_relatedProds_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
										
					array(  	"name" 	=> __('Display Tag Related Products?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display Tag Related Products. The section will appear below the product\'s image(s) and only if tag Related Products exist.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_tagRelatedProds_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
										
					array(		"name" 	=> __('Tag Related Tab','wpShop'),
								"desc" 	=> __('The first tab displays Tag Related Products (if Tags are used for Product Posts and any are found). Enter the text for this tab.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_tag_relatedProds",
								"std" 	=> "Complete the Set",
								"type" 	=> "text"),
										
					array(  	"name" 	=> __('Alternative Tag Related Products','wpShop'),
								"desc" 	=> __('Since WordPress Tags are not (at the point of creating this theme) hierarchical you can only use them either for your Product Posts or your Blog Posts not both! If you have checked the option to use Tags for regular Blog Posts under "General Options" then you need to select one of the other available custom taxonomies to be queried in place of Tags for "Tag Related Products".','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_term_relatedProds",
								"type" 	=> "select2",
								"std" 	=> "",
								"vals" 	=> array(__('Outfit','wpShop')."|outfit_related",__('Fit','wpShop')."|fit_related",__('Size','wpShop')."|size_related",__('Colour','wpShop')."|colour_related",__('Brand','wpShop')."|brand_related",__('Selection','wpShop')."|selection_related",__('Style','wpShop')."|style_related",__('Price','wpShop')."|price_related")),
								
					array(		"name" 	=> __('Number of Tag Related Products','wpShop'),
								"desc" 	=> __('Enter the desired number of Tag Related Products to display. If you want more than 4, you will need to make sure there\'s enough space for them to fit by either decreasing the image size (see image options) and/or adjusting the css','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_tag_relatedProds_num",
								"std" 	=> "4",
								"type" 	=> "text"),					
										
					array(  	"name" 	=> __('Display Category Related Products?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display Category Related Products. The section will appear below the product\'s image(s) and only if Category Related Products exist.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_catRelatedProds_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
										
					array(		"name" 	=> __('Category Related Tab','wpShop'),
								"desc" 	=> __('The second tab displays Category Related Products (if any are found). Enter the text for this tab.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_cat_relatedProds",
								"std" 	=> "You may also like",
								"type" 	=> "text"),
										
					array(		"name" 	=> __('Category Related Products -Number','wpShop'),
								"desc" 	=> __('Enter the desired number of Category Related Products to display. If you want more than 4, you will need to make sure there\'s enough space for them to fit by either decreasing the image size (see image options) and/or adjusting the css','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_cat_relatedProds_num",
								"std" 	=> "4",
								"type" 	=> "text"),	
								
					array(  	"name" 	=> __('Category Related Products - Order by','wpShop'),
								"desc" 	=> __('All available options are in the dropdown','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_cat_relatedProds_orderby",
								"type" 	=> "select2",
								"std" 	=> "Random Order",
								"vals" 	=> array(__('Random Order','wpShop')."|rand", __('Order by ID','wpShop')."|ID", __('Order by Title','wpShop')."|title", __('Order by Date','wpShop')."|date", __('Order by Parent','wpShop')."|parent", __('Order by Comment Count','wpShop')."|comment_count", __('None','wpShop')."|none")),

					array(    	"name" 	=> __('Category Related Products - Order','wpShop'),
								"desc" 	=> __('All available options are in the dropdown','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_cat_relatedProds_order",
								"type" 	=> "select2",
								"std" 	=> "Ascending",
								"vals" 	=> array(__('Ascending','wpShop')."|ASC",__('Descending','wpShop')."|DESC")),
										
					array(  	"name" 	=> __('Select the Open Related Product Tab','wpShop'),
								"desc" 	=> __('Select which of the 2 tabs you\'d like to have open when the page loads','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_relatedOpen_tab",
								"type" 	=> "select2",
								"std" 	=> "",
								"vals" 	=> array(__('Tag Related Tab','wpShop')." |tag_related_tab",__('Category Related Tab','wpShop')."|cat_related_tab")),
										
					array(  	"name" 	=> __('Display Shopping Bag Related Products?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display Related Products when viewing the Shopping Bag page.','wpShop')."<br/><b>".__('Note: This option requires the use of tags so leave it unchecked until you are ready to start tagging your products! If you have checked the option to use Tags for regular Blog Posts under "General Settings" then you need to select one of the other available custom taxonomies from below to be queried instead".','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_cartRelatedProds_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
										
					array(  	"name" 	=> __('Alternative Shopping Bag Related Products','wpShop'),
								"desc" 	=> __('See explanation above.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_term_cart_relatedProds",
								"type" 	=> "select2",
								"std" 	=> "",
								"vals" 	=> array(__('Outfit','wpShop')."|outfit_related",__('Fit','wpShop')."|fit_related",__('Size','wpShop')."|size_related",__('Colour','wpShop')."|colour_related",__('Brand','wpShop')."|brand_related",__('Selection','wpShop')."|selection_related",__('Style','wpShop')."|style_related",__('Price','wpShop')."|price_related")),
								
				array(   	"type" => "close"),
			array(   	"type" => "close"),
			
			//Shipping and Supplementary Info Links	
			array( 		"name" 	=> __('Shipping and Supplementary Info Links','wpShop'),
						"type" 	=> "title"),
								
				array(    	"type"	=> "open"),
				
					array(  	"name" 	=> __('Display Product ID?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display the Product ID (ID_item)','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_prod_ID",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
				
					array(  	"name" 	=> __('Display Shipping &amp; Handling link','wpShop'),
								"desc" 	=> __('Check this setting if you want to display the Shipping &amp; Handling Details link on the single product view and the Shopping Basket View','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shipping_details_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(    	"name" 	=> __('Shipping &amp; Handling link title text','wpShop'),
								"desc" 	=> __('Enter the Shipping &amp; Handling link title text','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shippingInfo_linkTxt",
								"std" 	=> "Shipping &amp; Handling Info",
								"type" 	=> "text"),
										
					array(  	"name" 	=> __('Shipping &amp; Handling Details','wpShop'),
								"desc" 	=> __('Enter a brief description of your shipping and handling charges. The text you enter here will be wrapped in a "div" html element so if you want to have paragraphs you may wrap your text blocks in "p" html elements.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shipping_details",
								"std" 	=> "Here you may include some useful information for your customers regarding your Shipping &amp; Handling Fees, Returns Policy whether you ship Internationaly or not etc. Let them know that these charges will be calculated on Step 3 (Order Review).",
								"type" 	=> "textarea"),

					array(  	"name" 	=> __('Shipping &amp; Handling Details for Watches category','wpShop'),
								"desc" 	=> __('Enter a brief description of your shipping and handling charges for Watches category items. The text you enter here will be wrapped in a "div" html element so if you want to have paragraphs you may wrap your text blocks in "p" html elements.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_watches_shipping_details",
								"std" 	=> "",
								"type" 	=> "textarea"),

					array(  	"name" 	=> __('Display "Supplementary Info" link','wpShop'),
								"desc" 	=> __('Check this setting if you want to display a "Supplementary Info" link. It is used to "pull" any kind of supplementary information contained anywhere else on the site and display it within an "overlay" such as "Size Charts", "Product Care Instructions" etc. The path to the page you want to get the contents of must be provided on a per product basis as the value of the custom field ','wpShop')."<b>".__('"supplementary_info"','wpShop')."</b><br/>".__('If you do not have or need the info on a seperate page you can still put together some text and display it using a different custom field called ','wpShop')."<b>".__('"supplementary_info_text"','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_supplInfo_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
										
					array(    	"name" 	=> __('"Supplementary Info" link title text','wpShop'),
								"desc" 	=> __('Enter the "Supplementary Info" link text you would like to have appear on the single product view','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_supplInfo_linkTxt",
								"std" 	=> "Size Chart",
								"type" 	=> "text"),
								
					array(  	"name" 	=> __('Redirect to Shopping Basket?','wpShop'),
								"desc" 	=> __('When a Product is added to the Shopping Basket then by default the page will reload and a confirmation message appears. Check this setting if you want to overwrite this and send the user to the Shopping Basket Page instead.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_send_to_view_cart",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
				array(   	"type" => "close"),
			array(   	"type" => "close"),
			
			//Social and subscribe settings		
			array( 		"name" 	=> __('Your "Share" and "Subscribe" Settings','wpShop'),
						"type" 	=> "title"),
								
				array(    	"type"	=> "open"),

					array(  	"name" 	=> __('"Email a Friend"?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display the "Email a Friend" link','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_emailFriend_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
										
					array(  	"name" 	=> __('"Print"?','wpShop'),
								"desc" 	=> __('Check this settting if you want to display the "Print" link','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_print_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
										
					array(  	"name" 	=> __('"Share"?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display the "Share" link','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_share_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
										
					array(  	"name" 	=> __('"Subscribe"?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display the "Subscribe" link','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_subscribe_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),

					array(  	"name" => __('Icons - File Type','wpShop'),
								"desc" => __('Select the file type you are using for the "Email a Friend", "Print", "Share", "Subscribe"  icons as well as the product navigation arrows. Please note that this setting will apply for the Blog Single Posts as well.','wpShop'),
								"id" => $CONFIG_WPS['shortname']."_icons_file_type",
								"type" => "select2",
								"std" => "jpg",
								"vals" => array("jpg|jpg","png|png","gif|gif")),

					array(		"name" 	=> __('Feedburner RSS Link','wpShop'),
								"desc" 	=> __('Enter Your Feedburner Link It will look something like this: ','wpShop')."'http://feeds2.feedburner.com/snDesign'",
								"id" 	=> $CONFIG_WPS['shortname']."_feedburner_rsslink",
								"std" 	=> "http://feeds2.feedburner.com/snDesign",
								"type" 	=> "text"),
										
					array(		"name" 	=> __('Feedburner Email Subscription Link','wpShop'),
								"desc" 	=> __('Enter Your Feedburner Email Subscription link you are given after you have activated "Email Subscriptions" from your account. It will look something like this: ','wpShop')."'http://feedburner.google.com/fb/a/mailverify?uri=snDesign&amp;loc=en_US'",
								"id" 	=> $CONFIG_WPS['shortname']."_feedburner_emaillink",
								"std" 	=> "http://feedburner.google.com/fb/a/mailverify?uri=snDesign&loc=en_US",
								"type" 	=> "text"),
										
					array(		"name" 	=> __('Twitter','wpShop'),
								"desc" 	=> __('Enter Your Twitter username','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_twitter",
								"std" 	=> "srhnbr",
								"type" 	=> "text"),
										
					array(		"name" 	=> __('Subscribe Title','wpShop'),
								"desc" 	=> __('Enter the title you would like to have in place of "Stay Informed" (appears inside the overlay that opens when the "Subscribe" link is clicked)','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_subscribe_title",
								"std" 	=> "Stay Informed",
								"type"	=> "text"),
										
					array(		"name" 	=> __('Subscribe Text','wpShop'),
								"desc" 	=> __('Enter the text you would like to have appear below the "Stay Informed" title','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_subscribe_text",
								"std" 	=> "",
								"type" 	=> "textarea"),
										
					array(		"name" 	=> __('Share Title','wpShop'),
								"desc" 	=> __('Enter the title you would like to have in place of "Bookmark and Share" (appears inside the overlay that opens when the "Share" link is clicked)','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_share_title",
								"std" 	=> "Bookmark & Share",
								"type" 	=> "text"),
										
					array(		"name" 	=> __('Share Text','wpShop'),
								"desc" 	=> __('Enter the text you would like to have appear appear below the "Bookmark and Share"','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_share_text",
								"std" 	=> "",
								"type" 	=> "textarea"),
										
					array(		"name" 	=> __('Email a Friend Title','wpShop'),
								"desc" 	=> __('Enter the title you would like to have in place of "Email a Friend About this Item" (appears inside the overlay that opens when the "Email a Friend" link is clicked)','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_email_a_friend_title",
								"std" 	=> "Email a Friend About this Item",
								"type" 	=> "text"),
										
					array(		"name" 	=> __('Email a Friend Text','wpShop'),
								"desc" 	=> __('Enter the text you would like to have appear below the "Email a Friend About this Item" title','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_email_a_friend_text",
								"std" 	=> "",
								"type" 	=> "textarea"),
										
				array(   	"type" => "close"),
			array(   	"type" => "close"),
		array (		"type" 	=> "fieldset_end"),	
//###############################################################################################################
		// Tab 6 Blog			
		array (		"type" 	=> "fieldset_start",
					"class" =>"design",
					"id" 	=>"sec_blog_settings"),
							
			// Category settings				
			array ( 	"name" 	=> __('Blog','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),

					array(  	"name" 	=> __('"Date Published"?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display the Post\'s "Publish" Date (appears on Multiple Post Pages)','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_date_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),

					array(  	"name" 	=> __('Number of comments?','wpShop'),
								"desc" 	=> __('Check this box if you want to display the Post\'s Comments number (appears on Multiple Post Pages)','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_commentsNum_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),					

					array(  	"name" 	=> __('Teaser','wpShop'),
								"desc" 	=> __('Select the type of teaser content you\'d like to have (appears on Multiple Post Pages)','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_blogPostContent_option",
								"type" 	=> "select2",
								"std" 	=> "Post Excerpt with 'read more' link",
								"vals" 	=> array(__('Post Excerpt with "read more" link','wpShop')." |excerpt_link",__('Post Content with "read more" link','wpShop')."|content_link")),

					array(  	"name" 	=> __('Teaser Word Limit','wpShop'),
								"desc" 	=> __('If you have chosen "Post content" above enter the number of words to appear before the "cut" off point','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_blogWordLimit",
								"std" 	=> "30",
								"type" 	=> "text"),
										
					array(  	"name" 	=> __('"Read More" Link Text','wpShop'),
								"desc" 	=> __('Enter the link text to display for your "Read More" link','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_readMoreLink",
								"std" 	=> "read more",
								"type" 	=> "text"),
										
					array(  	"name" 	=> __('Display Meta Information?','wpShop'),
								"desc" 	=> __('Check this box if you want to display the Post\'s categories and tags - if any are used (appears on Multiple Post Pages)','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_blog_cat_meta_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
										
					array(  	"name" 	=> __('Activate Sidebar for Blog Category Pages?','wpShop'),
								"desc" 	=> __('Check this box if you want to have a sidebar when browsing Blog Category Pages','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_blog_cat_sidebar_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
										
				array(   	 "type" => "close"),
			array(   	"type" => "close"),
			
			// Single Blog Post Pages - Sidebar Content
			array( 		"name" 	=> __('Single Blog Post Pages - Sidebar Content','wpShop'),
						"type" 	=> "title"),
								
				array(    	"type" 	=> "open"),	

					array(  	"name" 	=> __('Activate Sidebar for Blog Single Pages?','wpShop'),
								"desc" 	=> __('Check this setting if you want to have a sidebar when browsing Single Blog Pages','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_blog_single_sidebar_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
										
					array(  	"name" 	=> __('Independent Sidebar for Blog Single Pages?','wpShop'),
								"desc" 	=> __('Check this setting if you want to have an independent sidebar when browsing Single Blog Pages (make sure you have also activated the above setting!)','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_blog_indSingle_sidebar_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
										
					array(  	"name" 	=> __('Display Category Related Posts?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display Category Related Posts. The section will appear in the sidebar of single blog posts and only if category related posts exist.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_blogCatRelated_posts_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
										
					array(		"name" 	=> __('How many Category Related Posts','wpShop'),
								"desc" 	=> __('Enter a number','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_blogCatRelated_num",
								"std" 	=> "4",
								"type" 	=> "text"),
										
					array(		"name" 	=> __('Category Related Title','wpShop'),
								"desc" 	=> __('Enter the text title you\'d like to have over the "Category Related Posts" list.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_blogCatRelated_title",
								"std" 	=> "Category Related",
								"type" 	=> "text"),
										
					array(  	"name" 	=> __('Display Tag Related Posts?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display Tag Related Posts. The section will appear in the sidebar of single blog posts and only if tag related posts exist.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_blogTagRelated_posts_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),

					array(		"name" 	=> __('How many Tag Related Posts','wpShop'),
								"desc" 	=> __('Enter a number','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_blogTagRelated_num",
								"std" 	=> "4",
								"type" 	=> "text"),
										
					array(		"name" 	=> __('Tag Related Title','wpShop'),
								"desc" 	=> __('Enter the text title you\'d like to have over the "Tag Related Posts" list','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_blogTagRelated_title",
								"std" 	=> "Tag Related",
								"type" 	=> "text"),
										
				array(   	"type" => "close"),
			array(   	"type" => "close"),
			
			// Single Blog Post Pages - Main Content (meta)
			array( 		"name" 	=> __('Single Blog Post Pages - Meta Info','wpShop'),
						"type" 	=> "title"),
								
				array(    	"type" 	=> "open"),	

					array(  	"name" 	=> __('"Date Published"?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display the Post\'s "Publish" Date','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_publish_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
										
					array(  	"name" 	=> __('"Posted in"?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display the Categories the Post belongs to.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_posted_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
										
					array(  	"name" 	=> __('"Tagged as"?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display the Tags the Post is tagged as.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_tagged_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
										
					array(  	"name" 	=> __('"Previous and Next" Post Links?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display previous and next post links','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_prevNext_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
										
				array(   	"type" => "close"),
			array(   	"type" => "close"),
			
			//Single Blog Post Pages - "Share" and "Subscribe" Settings
			array( 		"name" 	=> __('Single Blog Post Pages - Your "Share" and "Subscribe" Settings','wpShop'),
						"type" 	=> "title"),
								
				array(    	"type"	=> "open"),

					array(  	"name" 	=> __('"Email a Friend"?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display the "Email a Friend" link','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_blogEmailFriend_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
										
					array(  	"name" 	=> __('"Print"?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display the "Print" link','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_blogPrint_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
										
					array(  	"name" 	=> __('"Share"?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display the "Share" link','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_blogShare_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
										
					array(  	"name" 	=> __('"Subscribe"?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display the "Subscribe" link','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_blogSubscribe_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),

					array(		"name" 	=> __('Feedburner RSS Link','wpShop'),
								"desc" 	=> __('Enter Your Feedburner Link It will look something like this: ','wpShop')."'http://feeds2.feedburner.com/snDesign'",
								"id" 	=> $CONFIG_WPS['shortname']."_blogFeedburner_rsslink",
								"std" 	=> "http://feeds2.feedburner.com/snDesign",
								"type" 	=> "text"),
										
					array(		"name" 	=> __('Feedburner Email Subscription Link','wpShop'),
								"desc" 	=> __('Enter Your Feedburner Email Subscription link you are given after you have activated "Email Subscriptions" from your account. It will look something like this: ','wpShop')."'http://feedburner.google.com/fb/a/mailverify?uri=snDesign&amp;loc=en_US'",
								"id" 	=> $CONFIG_WPS['shortname']."_blogFeedburner_emaillink",
								"std" 	=> "http://feedburner.google.com/fb/a/mailverify?uri=snDesign&loc=en_US",
								"type" 	=> "text"),
										
					array(		"name" 	=> __('Twitter','wpShop'),
								"desc" 	=> __('Enter Your Twitter username','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_blogTwitter",
								"std" 	=> "srhnbr",
								"type" 	=> "text"),
										
					array(		"name" 	=> __('Subscribe Title','wpShop'),
								"desc" 	=> __('Enter the title you would like to have in place of "Stay Informed" (inside the overlay that opens when the "Subscribe" link is clicked)','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_blogSubscribe_title",
								"std" 	=> "Stay Informed",
								"type"	=> "text"),
										
					array(		"name" 	=> __('Subscribe Text','wpShop'),
								"desc" 	=> __('Enter the text you would like to have appear below the "Stay Informed" title','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_blogSubscribe_text",
								"std" 	=> "",
								"type" 	=> "textarea"),
										
					array(		"name" 	=> __('Share Title','wpShop'),
								"desc" 	=> __('Enter the title you would like to have in place of "Bookmark and Share" (inside the overlay that opens when the "Share" link is clicked','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_blogShare_title",
								"std" 	=> "Bookmark & Share",
								"type" 	=> "text"),
										
					array(		"name" 	=> __('Share Text','wpShop'),
								"desc" 	=> __('Enter the text you would like to have appear appear below the "Bookmark and Share"','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_blogShare_text",
								"std" 	=> "",
								"type" 	=> "textarea"),
										
					array(		"name" 	=> __('Email a Friend Title','wpShop'),
								"desc" 	=> __('Enter the title you would like to have in place of "Email a Friend About this Item" (inside the overlay that opens when the "Email a Friend" link is clicked)','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_blogEmail_a_friend_title",
								"std" 	=> "Share this Post with a Friend",
								"type" 	=> "text"),
										
					array(		"name" 	=> __('Email a Friend Text','wpShop'),
								"desc" 	=> __('Enter the text you would like to have appear below the "Email a Friend About this Item" title','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_blogEmail_a_friend_text",
								"std" 	=> "",
								"type" 	=> "textarea"),
										
				array(   	"type" => "close"),
			array(   	"type" => "close"),
		array (		"type" 	=> "fieldset_end"),
//###############################################################################################################
		// Tab 7 Image Sizes			
		array (		"type" 	=> "fieldset_start",
					"class" =>"design",
					"id" 	=>"sec_imageSizes_settings"),
							
			array ( 	"name" 	=> __('Image sizes','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),	

					array(    	"name" 	=> __('In Featured Area','wpShop'),
								"desc" 	=> __('Do not edit if your css skills are below average as changing the size requires layout changes! Sizes are calculated in px.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_featured_img_size",
								"std" 	=> "770",
								"type" 	=> "text"),
										
					array(    	"name" 	=> __('1 Column in Store Category Pages','wpShop'),
								"desc" 	=> __('Do not edit if your css skills are below average as changing the size requires layout changes! Sizes are calculated in px.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_prodCol1_img_size",
								"std" 	=> "405",
								"type" 	=> "text"),
										
					array(    	"name" 	=> __('2 Columns in Store Category Pages','wpShop'),
								"desc" 	=> __('Do not edit if your css skills are below average as changing the size requires layout changes! Sizes are calculated in px.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_prodCol2_img_size",
								"std" 	=> "369",
								"type" 	=> "text"),
										
					array(    	"name" 	=> __('3 Columns in Store Category Pages','wpShop'),
								"desc" 	=> __('Do not edit if your css skills are below average as changing the size requires layout changes! Sizes are calculated in px.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_prodCol3_img_size",
								"std" 	=> "237",
								"type" 	=> "text"),
										
					array(    	"name" 	=> __('4 Columns in Store Category Pages','wpShop'),
								"desc" 	=> __('Do not edit if your css skills are below average as changing the size requires layout changes! Sizes are calculated in px.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_prodCol4_img_size",
								"std" 	=> "174",
								"type" 	=> "text"),
										
					array(    	"name" 	=> __('Main Image in Single Product Pages (when 1 image is used)','wpShop'),
								"desc" 	=> __('Do not edit if your css skills are below average as changing the size requires layout changes! Sizes are calculated in px.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_singleProdMain1_img_size",
								"std" 	=> "405",
								"type" 	=> "text"),
										
					array(    	"name" 	=> __('Main Image in Single Product Pages (when multiple images are used)','wpShop'),
								"desc" 	=> __('Do not edit if your css skills are below average as changing the size requires layout changes! Sizes are calculated in px.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_singleProdMainMulti_img_size",
								"std" 	=> "355",
								"type" 	=> "text"),
										
					array(    	"name" 	=> __('Image thumbnails in Single Product Pages (when multiple images are used)','wpShop'),
								"desc" 	=> __('Do not edit if your css skills are below average as changing the size requires layout changes! Sizes are calculated in px.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_singleProd_t_img_size",
								"std" 	=> "30",
								"type" 	=> "text"),
										
					array(    	"name" 	=> __('Product Related, Shopping Basket and Wishlist Product Images','wpShop'),
								"desc" 	=> __('Do not edit if your css skills are below average as changing the size requires layout changes! Sizes are calculated in px.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_ProdRelated_img_size",
								"std" 	=> "91",
								"type" 	=> "text"),
										
				array(   	"type" => "close"),
			array(   	"type" => "close"),
		array (		"type" 	=> "fieldset_end"),

//###############################################################################################################
		// Tab 8 Admin Settings
		array (		"type" 	=> "fieldset_start",
					"class" =>"design",
					"id" 	=>"sec_admin_settings"),
							
			array ( 	"name" 	=> __('Admin Settings','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),	

					array(    	"name" 	=> __('Orders per page','wpShop'),
								"desc" 	=> __('Orders list per page on Manage Orders page','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_admin_orders_per_page",
								"std" 	=> "20",
								"type" 	=> "text"),
										
										
				array(   	"type" => "close"),
			array(   	"type" => "close"),
		array (		"type" 	=> "fieldset_end"),

	array (		"type" 	=> "section_end"),

/*** 
Shop Settings
***/
array ( 	"name" 	=> __('Shop','wpShop'),
			"type" 	=> "heading",
			"class" =>"shop"),
					
	array (		"type" 	=> "section_start",
				"class" =>"hasadmintabs hasadmintabs2"),					

		// Tab 1 General
		array (		"type" 	=> "fieldset_start",
					"class" =>"shop",
					"id" 	=>"sec_general_shop_settings"),

			array ( 	"name" 	=> __('General','wpShop'),
						"type" 	=> "title"),

				array (    	"type" 	=> "open"),

					array(   	"name" 	=> __('Shop mode','wpShop'),
								"desc" 	=> __('Select','wpShop')."<b>".__(' "Enquiry email mode" ','wpShop')."</b>".__('if you want the contents of the shopping basket to be collected and be send to you as an Enquiry email instead of redirecting your customers to a payment gateway- otherwise leave on "Regular shop mode".','wpShop')."<br/><b>".__('"Affiliate mode" ','wpShop')."</b>".__('will transform your shop to one selling ONLY affiliate products. Note: If you plan to sell your own products as well as affiliate products you only need to use an additional custom field "buy_now" on your affiliate product posts with the link to the product for the value. So in this case please leave this setting on "Regular Shop Mode"','wpShop')."<br/><b>".__('"PayLoadz mode" ','wpShop')."</b>".__('means that you redirect your customers to PayLoadz when it comes to paying and downloading your digital goods. Can be used if you ONLY sell Digital Goods','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shop_mode",
								"std" 	=> "Regular shop mode",
								"vals" 	=> array(__('Regular shop mode','wpShop')."|Normal shop mode",__('Enquiry email mode','wpShop')."|Inquiry email mode",__('Affiliate mode','wpShop')."|affiliate_mode",__('PayLoadz mode','wpShop')."|payloadz_mode"),
								"type" 	=> "select2"),	
										
					array(  	"name" 	=> __('Open Affiliate Products in new Window or Tab?','wpShop'),
								"desc" 	=> __('Check this setting if you want to open Affiliate Product Links in a new window or tab','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_affili_newTab",
								"type" 	=> "checkbox",
								"std" 	=> "false"),

					array(    	"name" 	=> __('Name','wpShop'),
								"desc" 	=> __('What your shop is called. Usually the same as the Blog-Title you set in WordPress','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shop_name",
								"std" 	=> "The Furniture Store",
								"type" 	=> "text"),
										
					array(    	"name" 	=> __('Address','wpShop'),
								"desc" 	=> __('Enter the Shop\'s street address and number','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shop_street",
								"std" 	=> "Some Street 1",
								"type" 	=> "text"),
										
					array(    	"name" 	=> __('State / Province','wpShop'),
								"desc" 	=> __('Enter the Shop\'s state / province.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shop_province",
								"std" 	=> "Some State",
								"type" 	=> "text"),

					array(    	"name" 	=> __('Postcode','wpShop'),
								"desc" 	=> __('Enter the Shop\'s Postcode','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shop_zip",
								"std" 	=> "11111",
								"type" 	=> "text"),
										
					array(    	"name" 	=> __('City / Town','wpShop'),
								"desc" 	=> __('Enter the Shop\'s City / Town','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shop_town",
								"std" 	=> "Some Town",
								"type" 	=> "text"),
										
					array(  	"name" 	=> __('Country of your Shop','wpShop'),
								"desc" 	=> __('Where the shop is based.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shop_country",
								"type" 	=> "select2",
								"std" 	=> "US",
								"vals" 	=> $countries),	
										
					array(  	"name" 	=> __('Email of your Shop','wpShop'),
								"desc" 	=> __('Enter the primary email of your shop. Different notifications (new orders etc.) from the system will be send to this email ','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shop_email",
								"type" 	=> "text",
								"std" 	=> ""),	

					array(  	"name" 	=> __('Questions Email','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shop_questions_email",
								"type" 	=> "text",
								"std" 	=> ""),	

					array(  	"name" 	=> __('Questions Phone','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shop_questions_phone",
								"type" 	=> "text",
								"std" 	=> ""),	

					array(  	"name" 	=> __('Unavailable Text','wpShop'),
								"desc" 	=> __('Enter popup unavailable text.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_unavailable_text",
								"type" 	=> "textarea",
								"std" 	=> ""),

					array(  	"name" 	=> __('Includes List','wpShop'),
								"desc" 	=> __('Includes name list for post single page. Format include_key|include_name.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_includes_list",
								"type" 	=> "textarea",
								"std" 	=> ""),

					array(  	"name" 	=> __('Material List','wpShop'),
								"desc" 	=> __('Materials list for items.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_material_list",
								"type" 	=> "textarea",
								"std" 	=> ""),

					array(  	"name" 	=> __('Metal List','wpShop'),
								"desc" 	=> __('Metals list for items.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_metal_list",
								"type" 	=> "textarea",
								"std" 	=> ""),

					array(  	"name" 	=> __('Movement List','wpShop'),
								"desc" 	=> __('Movements list for items.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_movement_list",
								"type" 	=> "textarea",
								"std" 	=> ""),

				array(    	"type" 	=> "close"),
			array(   	"type" => "close"),
				
			// Currency
			array ( 	"name" 	=> __('Currency Settings','wpShop'), 
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),
										
					// Currency 
					array(  	"name" => __('Currency','wpShop'),
								"desc" => __('Select your currency','wpShop')."<br/><b>".__('Please Note: If you are using PayPal as a Payment Gateway please make sure that your currency is supported (by PayPal) in order to receive payments. ','wpShop')."<a href='https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_nvp_currency_codes' target='_blank'>".__('PayPal-Supported Currencies and Currency Codes','wpShop')."</a></b>",
								"id" => $CONFIG_WPS['shortname']."_currency_code",
								"type" => "select",
								"std" => "EUR",
								"vals" => array('AED','AFN','ALL','AMD','ANG','AOA','ARS','AUD','AWG','AZN','BAM','BBD','BDT','BGN','BHD','BIF','BMD','BND','BOB','BRL','BSD',
								'BTN','BWP','BYR','BZD','CAD','CDF','CHF','CLP','CNY','COP','CRC','CUP','CVE','CZK','DJF','DKK','DOP','DZD','EEK','EGP','ERN','ETB','EUR',
								'FJD','FKP','GBP','GEL','GGP','GHS','GIP','GMD','GNF','GTQ','GYD','HKD','HNL','HRK','HTG','HUF','IDR','ILS','IMP','INR','IQD','IRR','ISK',
								'JEP','JMD','JOD','JPY','KES','KGS','KHR','KMF','KPW','KRW','KWD','KYD','KZT','LAK','LBP','LKR','LRD','LSL','LTL','LVL','LYD','MAD','MDL',
								'MGA','MKD','MMK','MNT','MOP','MRO','MTL','MUR','MVR','MWK','MXN','MYR','MZN','NAD','NGN','NIO','NOK','NPR','NZD','OMR','PAB','PEN','PGK',
								'PHP','PKR','PLN','PYG','QAR','RON','RSD','RUB','RWF','SAR','SBD','SCR','SDG','SEK','SGD','SHP','SLL','SOS','SPL','SRD','STD','SVC','SYP',
								'SZL','THB','TJS','TMM','TND','TOP','TRY','TTD','TVD','TWD','TZS','UAH','UGX','USD','UYU','UZS','VEF','VND','VUV','WST','XAF','XAG','XAU',
								'XCD','XDR','XOF','XPD','XPF','XPT','YER','ZAR','ZMK','ZWD')),
										
					array(  	"name" 	=> __('Display the Currency Code','wpShop'),
								"desc" 	=> __('Check this setting if you want to display the Currency Code.(displays last after the price!)','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_currency_code_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(  	"name" 	=> __('Currency symbol','wpShop'),
								"desc" 	=> __('Enter the symbol for your chosen currency (displays first before the price!) eg.','wpShop')."<b> $ </b>".__('for dollars or','wpShop')."<b> &euro; </b>".__('for euros','wpShop')."<br/><b>".__('Note: Some currencies need to be written in a special code in order to be rendered correctly in the browser so please refer','wpShop')."<a href='http://tlt.its.psu.edu/suggestions/international/web/codehtml.html#currency' target='_blank'>".__(' to this guide ','wpShop')."</a></b>",
								"id" 	=> $CONFIG_WPS['shortname']."_currency_symbol",
								"type" 	=> "text",
								"std" 	=> ""),
								
					array(  	"name" 	=> __('Alternative Currency Code or Symbol','wpShop'),
								"desc" 	=> __('Some countries display a certain code or symbol at the end of prices. Use this setting if you want to add something to display after the price.','wpShop')."<br/><b>".__(' Make sure that you properly encode your symbol so it renders correctly in the browser!','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_currency_symbol_alt",
								"type" 	=> "text",
								"std" 	=> ""),
								
					array(    	"name" 	=> __('Price Formating','wpShop'),
								"desc" 	=> __('Prices are formatted differently from country to country. Select the Price Formating fitting to your currency.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_price_format",
								"std" 	=> "2",
								"vals" 	=> array(	__('Decimal seperator with , no divider for thousands','wpShop')."|1",
													__('Decimal seperator with . no divider for thousands','wpShop')."|2",
													__('Decimal seperator with , and . divider for thousands - used e.g in Brazil, Germany','wpShop')."|3",
													__('Decimal seperator with . and , divider for thousands - used e.g in UK, USA','wpShop')."|4",
													__('Decimal seperator with , and divider for thousands whith whitespace - used e.g in France','wpShop')."|5",
													__('Decimal seperator with , and \' divider for thousands - used e.g in Switzerland','wpShop')."|6",
													__('No decimal digits and an empty space as divider for thousands','wpShop')."|7",
													__('No decimal digits and , divider for thousands','wpShop')."|8",
													__('No decimal digits and no divider for thousands','wpShop')."|9"
												),
								"type" 	=> "select2"),
				array(    	"type" 	=> "close"),
			array(   	"type" => "close"),
			
			
			// Tax
			array ( 	"name" 	=> __('Tax Settings','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),
											
					"tax_module_data_follows" => array(    	"name" 	=> __('Shop is based in country: ','wpShop'),
								"desc" 	=> "<strong style='font-size: 1.2em;'>".get_countries(2,$OPTION['wps_shop_country'])."</strong><br/><small>".__('If the above field is empty or states the wrong country, make sure you set the Country where you Shop is located (Country of your Shop) and save your settings. Then return here.','wpShop')."</small>",
								"id" 	=> $CONFIG_WPS['shortname']."_tax_country",
								"value" => $OPTION['wps_shop_country'],
								"type" 	=> "text-link"),
								
					1 ,		
					
					// following fields are in there since ages			
					array(  	"name" 	=> __('Enable Tax','wpShop'),
								"desc" 	=> __('Check this setting if you want to display the tax information.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_tax_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
					array(  	"name" 	=> __('Assign Tax Country Zones','wpShop'),
								"desc" 	=> __('Give countries a zone number. Don\'t forget to hit the "Save" button at the end of the iFrame!','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_tax_country_zones",
								"type" 	=> "iframe",
								"vals" 	=> "../wp-content/themes/".$CONFIG_WPS['themename']."/shop-tax-country-zones.php",
								"std" 	=> ""),		
					array(    	"name" 	=> __('Zone 1','wpShop'),
								"desc" 	=> __('Set the Tax percentage for this country zone.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_tax_zone1",
								"std" 	=> "",
								"type" 	=> "text"),
					array(    	"name" 	=> __('Zone 2','wpShop'),
								"desc" 	=> __('Set the Tax percentage for this country zone.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_tax_zone2",
								"std" 	=> "",
								"type" 	=> "text"),
					array(    	"name" 	=> __('Zone 3','wpShop'),
								"desc" 	=> __('Set the Tax percentage for this country zone.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_tax_zone3",
								"std" 	=> "",
								"type" 	=> "text"),
					array(    	"name" 	=> __('Zone 4','wpShop'),
								"desc" 	=> __('Set the Tax percentage for this country zone.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_tax_zone4",
								"std" 	=> "",
								"type" 	=> "text"),
					array(    	"name" 	=> __('Zone 5','wpShop'),
								"desc" 	=> __('Set the Tax percentage for this country zone.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_tax_zone5",
								"std" 	=> "",
								"type" 	=> "text"),
					array(    	"name" 	=> __('Zone 6','wpShop'),
								"desc" 	=> __('Set the Tax percentage for this country zone.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_tax_zone6",
								"std" 	=> "",
								"type" 	=> "text"),
					array(  	"name" 	=> __('Popup Text','wpShop'),
								"desc" 	=> __('Enter tax popup text.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_tax_popup_text",
								"type" 	=> "textarea",
								"std" 	=> ""),

				array(    	"type" 	=> "close"),
			array(   	"type" => "close"),
			
			// Shipping, delivery, payment				
			array ( 	"name" 	=> __('Delivery & Payment Settings','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),
				
					
					array(  	"name" 	=> __('Delivery Options','wpShop'),
								"desc" 	=> __('Activate the Delivery Options you want to make available to your customers.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_delivery_options",
								#"vals" 	=> array(__('Pick up','wpShop')."|pickup",__('Delivery','wpShop')."|post",__('Delivery by Email','wpShop')."|email"), 
								"vals" 	=> array(__('Pick up','wpShop')."|pickup",__('Delivery','wpShop')."|post"), 
								#"vals" 	=> array(__('Pick up','wpShop')."|pickup",__('Delivery 1','wpShop')."|post",__('Delivery 2','wpShop')."|post2",__('Delivery 3','wpShop')."|post3",__('Delivery 4','wpShop')."|post4"),
								"type" 	=> "multi-checkbox",
								"std" 	=> "false"),

					array(  	"name" 	=> __('"Pick up" Label Text','wpShop'),
								"desc" 	=> __('This will be used for the label text of the "Pick up" Delivery Option','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pickUp_label",
								"type" 	=> "text",
								"std" 	=> "Pick up (&euro;0.00)"),
							
					array(  	"name" 	=> __('"Delivery" Label Text','wpShop'),
								"desc" 	=> __('This will be used for the label text of the "Delivery" Delivery Option','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_delivery_label",
								"type" 	=> "text",
								"std" 	=> "Delivery (2-3 Business Days, Delivery Charges Apply)"),

					array(  	"name" 	=> __('"Delivery" FREE Label Text','wpShop'),
								"desc" 	=> __('This will be used for the label text of the "Delivery" FREE Delivery Option','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_delivery_free_label",
								"type" 	=> "text",
								"std" 	=> "Delivery (2-3 Business Days, Free Of Charge)"),

					/*			
					array(  	"name" 	=> __('"Delivery by Email" Label Text','wpShop'),
								"desc" 	=> __('This will be used for the label text of the "Delivery by Email" Delivery Option','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_emailDelivery_label",
								"type" 	=> "text",
								"std" 	=> "Delivery by Email (Instant after Payment Confirmation, &euro;0.00)"),
					*/
					/*
					array(  	"name" 	=> __('"Delivery 1" Label Text','wpShop'),
								"desc" 	=> __('This will be used for the label text of the 1st Delivery Option','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_delivery_label",
								"type" 	=> "text",
								"std" 	=> "Same Day Delivery (Same Business Day - Surcharge on Regular Shipping &amp; Handling: &euro;50.00)"),
								
					array(  	"name" 	=> __('"Delivery 2" Label Text','wpShop'),
								"desc" 	=> __('This will be used for the label text of the "Delivery" Delivery Option','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_delivery_label2",
								"type" 	=> "text",
								"std" 	=> "Next Day Delivery (Next Business Day - Surcharge on Regular Shipping &amp; Handling: &euro;25.00)"),
								
					array(  	"name" 	=> __('"Delivery 3" Label Text','wpShop'),
								"desc" 	=> __('This will be used for the label text of the "Delivery" Delivery Option','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_delivery_label3",
								"type" 	=> "text",
								"std" 	=> "Express Delivery (2-3 Business Days - Surcharge on Regular Shipping &amp; Handling: &euro;10.00)"),
								
					array(  	"name" 	=> __('"Delivery 4" Label Text','wpShop'),
								"desc" 	=> __('This will be used for the label text of the "Delivery" Delivery Option','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_delivery_label4",
								"type" 	=> "text",
								"std" 	=> "Regular Delivery (7-10 Business Days, Surcharge on Regular Shipping &amp; Handling: &euro;0.00)"),
					*/
					
										
					array(  	"name" 	=> __('Payment Options','wpShop'),
								"desc" 	=> __('Activate the Payment Options you want to make available to your customers.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_payment_options",
								"vals" 	=> array(__('PayPal Payments Standard','wpShop')."|paypal",__('PayPal Payments Pro (Accept Credit Cards directly on your Website - for US, Canada and UK Merchants)','wpShop')."|paypal_pro",__('Credit Card Payments with Authorize.net (Only for Merchants with a US Bank Account)','wpShop')."|cc_authn",__('Credit Card Payments with Gate2Play.com','wpShop')."|g2p_authn",__('Credit Card Payments with WorldPay.com (For International Merchants)','wpShop')."|cc_wp",__('Bank Transfer in Advance','wpShop')."|transfer",__('Payment on Location','wpShop')."|cash",__('Cash on Delivery','wpShop')."|cod",__('Alert Pay','wpShop')."|alertpay",__('2CHECKOUT','wpShop')."|2checkout",__('AUDI','wpShop')."|audi"), 
								"type" 	=> "multi-checkbox",
								"std" 	=> "false"),
								
					array(  	"name" 	=> __('Payment Options Preselected','wpShop'),
								"desc" 	=> __('Select the Payment option you will want to show as preselected.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_payment_op_preselected",
								"vals"  => array(__('Cash on Location','wpShop')."|cash",
								__('Creditcard Authorize.net','wpShop')."|cc_authn",
								__('Creditcard WorldPay','wpShop')."|cc_wp",
								__('Cash on Delivery','wpShop')."|cod",
								__('Creditcard Gate2Play.com','wpShop')."|g2p_authn",
								__('Paypal','wpShop')."|paypal",
								__('Paypal Pro','wpShop')."|paypal_pro",
								__('Bank Transfer','wpShop')."|transfer",
								__('Alert Pay','wpShop')."|alertpay",
								__('2CHECKOUT','wpShop')."|2checkout",
								__('AUDI','wpShop')."|audi",								
								__('None','wpShop')."|none"
								),
								"type" 	=> "select2",
								"std" 	=> "none"),	
								
					array(  	"name" 	=> __('"PayPal Payments Standard" Label Text','wpShop'),
								"desc" 	=> __('This will be used for the label text of the "PayPal Payments Standard" Payment Option','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pps_label",
								"type" 	=> "text",
								"std" 	=> "PayPal (PayPal Payments Standard)"),
								
					array(  	"name" 	=> __('"PayPal Payments Pro" Label Text','wpShop'),
								"desc" 	=> __('This will be used for the label text of the "PayPal Payments Pro" Payment Option','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_ppp_label",
								"type" 	=> "text",
								"std" 	=> "Credit Card (PayPal Payments Pro)"),
								
					array(  	"name" 	=> __('"Authorize.net" Label Text','wpShop'),
								"desc" 	=> __('This will be used for the label text of the "Authorize.net" Payment Option','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_auth_label",
								"type" 	=> "text",
								"std" 	=> "Credit Card (Authorize.net)"),
					array(  	"name" 	=> __('"Gate2Play.com" Label Text','wpShop'),
								"desc" 	=> __('This will be used for the label text of the "Gate2Play.com" Payment Option','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_g2p_label",
								"type" 	=> "text",
								"std" 	=> "Credit Card (Gate2Play.com)"),			
					array(  	"name" 	=> __('"WorldPay.com" Label Text','wpShop'),
								"desc" 	=> __('This will be used for the label text of the "WorldPay.com" Payment Option','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."wps_pol_label",
								"type" 	=> "text",
								"std" 	=> "Credit Card (WorldPay.com)"),
					array(  	"name" 	=> __('"alertpay.com" Label Text','wpShop'),
								"desc" 	=> __('This will be used for the label text of the "Alertpay.com" Payment Option','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_alertpay_label",
								"type" 	=> "text",
								"std" 	=> "Credit Card (Alertpay.com)"),
					array(  	"name" 	=> __('"2checkout.com" Label Text','wpShop'),
								"desc" 	=> __('This will be used for the label text of the "2CHECKOUT.com" Payment Option','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_2checkout_label",
								"type" 	=> "text",
								"std" 	=> "Credit Card (2CHECKOUT.com)"),
					array(  	"name" 	=> __('"banqueaudi.com" Label Text','wpShop'),
								"desc" 	=> __('This will be used for the label text of the "banqueaudi.com" Payment Option','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_audi_label",
								"type" 	=> "text",
								"std" 	=> "Credit Card (banqueaudi.com)"),								
					array(  	"name" 	=> __('"Bank Transfer" Label Text','wpShop'),
								"desc" 	=> __('This will be used for the label text of the "Bank Transfer" Payment Option','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_bt_label",
								"type" 	=> "text",
								"std" 	=> "Bank Transfer"),
								
					array(  	"name" 	=> __('"Cash on Delivery" Label Text','wpShop'),
								"desc" 	=> __('This will be used for the label text of the "Cash on Delivery" Payment Option','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_cod_label",
								"type" 	=> "text",
								"std" 	=> "Cash on Delivery"),
								
					array(  	"name" 	=> __('"Payment on Location" Label Text','wpShop'),
								"desc" 	=> __('This will be used for the label text of the "Payment on Location" Payment Option','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pol_label",
								"type" 	=> "text",
								"std" 	=> "Payment on Location"),
										
											
					array(    	"name" 	=> __('SSL for checkout?','wpShop'),
								"desc" 	=> __('If you want the Checkout process to run over the secure SSL Protocol, select "Yes". This requires that SSL is enabled for your domain. To find out if it is, simply replace http:// with https:// in the url and see if your web contents still appear.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_enforce_ssl",
								"std" 	=> "normal",
								"vals" 	=> array(__('No','wpShop')."|normal",__('Yes','wpShop')."|force_ssl"),
								"type" 	=> "select2"),
								
				array(    	"type" 	=> "close"),
			array(   	"type" => "close"),
			
			// Custom Note & Terms & Conditions				
			array ( 	"name" 	=> __('Vouchers, Custom Note in Order Checkout, Terms & Conditions (Order Checkout Steps 1, 2)','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),
				
					array(  	"name" 	=> __('Activate Voucher / Coupon Codes','wpShop'),
								"desc" 	=> __('Check this setting if you plan on giving out voucher codes.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_voucherCodes_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(  	"name" 	=> __('Request Customer Telephone Number in Checkout Step 2','wpShop'),
								"desc" 	=> __('If you select Yes, then one extra telephone input field is shown in the checkout form.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_checkout_showtel",
								"std" 	=> "No",
								"vals" 	=> array("No", "Yes"),
								"type" 	=> "select"),
				
					array(  	"name" 	=> __('Activate "Custom Note"','wpShop'),
								"desc" 	=> __('Check this setting if you want to give your customers the option to add a "Custom Note" to an order.','wpShop')."<br/><b>".__('Note: This will display a single textarea in Step 2 of the checkout process. Any text entered by the customer refers to the entire order. If you want Product Personalization (textareas and / or text inputs on a per product basis) please use the appropriate custom fields as described in the product custom fields PDF provided in the Documentation','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_customNote_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
						
					array(  	"name" 	=> __('"Custom Note" Label Text','wpShop'),
								"desc" 	=> __('This will be used for the label text of the textarea','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_customNote_label",
								"type" 	=> "text",
								"std" 	=> "Custom Note"),
								
					array(  	"name" 	=> __('"Custom Note" Additional Text','wpShop'),
								"desc" 	=> __('If you want to provide any further info to your customers regarding the use of the "Custom Note" field use the textarea here.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_customNote_remark",
								"type" 	=> "textarea",
								"std" 	=> "This optional custom note can be used to send a message to the Shop Merchant or as a personal note to be delivered along with the order eg. when an order is a gift to someone."),
					
					array(  	"name" 	=> __('Your terms &amp; conditions','wpShop'),
								"desc" 	=> __('Enter the terms and conditions, your customers must agree to before ordering.','wpShop')."<br/>".__('This is wrapped in a div html element so feel free to use headings (h4, h5) and paragraphs (p) for better control on the text formatting','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_terms_conditions",
								"type" 	=> "textarea",
								"std" 	=> "Our terms & conditions are..."),
										
				array(    	"type" 	=> "close"),
			// Lawaway Settings		
			array ( 	"name" 	=> __('Layaway Settings','wpShop'),
						"type" 	=> "title"),
				array(    	"type" 	=> "open"),

					array(  	"name" 	=> __('Activate Layaway','wpShop'),
								"desc" 	=> __('Check this setting for activating Layaway process.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_layaway_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
					array(  	"name" 	=> __('Percent number','wpShop'),
								"desc" 	=> __('This will be used for the calculating amount for layaway.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_layaway_percent",
								"type" 	=> "text",
								"std" 	=> "25%"),
					array(  	"name" 	=> __('Reminder Email Subject','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_layaway_reminder_email_subject",
								"type" 	=> "text",
								"std" 	=> ""),
					array(  	"name" 	=> __('Reminder Email Message','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_layaway_reminder_email_message",
								"type" 	=> "textarea",
								"std" 	=> ""),

				array(    	"type" 	=> "close"),
			array(   	"type" => "close"),			

			// Email Notifications Settings		
			array ( 	"name" 	=> __('Email Notifications','wpShop'),
						"type" 	=> "title"),
				array(    	"type" 	=> "open"),

					array(  	"name" 	=> __('Wishlist Notification Subject','wpShop'),
								"desc" 	=> __('Subject of Wishlist Notification Email. Use {ITEM_NAME} in text.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_wishlist_notification_subject",
								"std" 	=> "",
								"type" 	=> "text"),					

					array(  	"name" 	=> __('Wishlist Notification Message','wpShop'),
								"desc" 	=> __('Message of Wishlist Notification Email. Use {ITEM_NAME}, {ITEM_ID}, {ITEM_URL} in message.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_wishlist_notification_message",
								"std" 	=> "",
								"type" 	=> "textarea"),					

				array(    	"type" 	=> "close"),
			array(   	"type" => "close"),			

			// What You Can Sell popup
			array ( 	"name" 	=> __('What You Can Sell popup','wpShop'),
						"type" 	=> "title"),
				array(    	"type" 	=> "open"),

					array(  	"name" 	=> __('Brands','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_wycsp_brands",
								"std" 	=> "",
								"type" 	=> "wycsp_brands"),					

				array(    	"type" 	=> "close"),
			array(   	"type" => "close"),			


		array (		"type" 	=> "fieldset_end"),	
//###############################################################################################################
		//Tab 2 Inventory			
		array (		"type" 	=> "fieldset_start",
					"class" =>"shop",
					"id" 	=>"sec_inventory_settings"),
							
			array ( 	"name" 	=> __('Inventory','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),

					array(  	"name" 	=> __('Activate Stock Control','wpShop'),
								"desc" 	=> __('Select "yes" if you want to keep track of your Product Stock. You can then manage your Inventory from the link that will appear at the top of this page after activation.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_track_inventory",
								"std" 	=> "not_active",
								"vals" 	=> array(__('Yes','wpShop')."|active",__('No','wpShop')."|not_active"),
								"type" 	=> "select2"),

					array(  	"name" 	=> __('Display Stock Amounts','wpShop'),
								"desc" 	=> __('Select "yes" if you want to display the "Stock on Hand" of each product.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_display_product_amounts",
								"std" 	=> "not_active",
								"vals" 	=> array(__('Yes','wpShop')."|active",__('No','wpShop')."|not_active"),
								"type" 	=> "select2"),

					array(  	"name" 	=> __('Sold-out Notice','wpShop'),
								"desc" 	=> __('Enter the notice that is to be displayed when a product is sold out.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_soldout_notice",
								"std" 	=> "SOLD OUT",
								"type" 	=> "text"),	
					
					array(  	"name" 	=> __('Cleaning time interval','wpShop'),
								"desc" 	=> __('The shop has a routine to return "abandoned" items into the inventory. Here you select the time interval when this should happen here. Below you will be asked to select the method.','wpShop')."<br/><b>".__('"Abandoned items" can be for example items that customers added in their Shopping Baskets but never went through or completed the Checkout.','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_inventory_cleaning_interval",
								"type" 	=> "select2",
								"std" 	=> "14400",
								"vals" 	=> array(__('5 minutes','wpShop')."|300", __('10 minutes','wpShop')."|600",__('15 minutes','wpShop')."|900",__('20 minutes','wpShop')."|1200",__('30 minutes','wpShop')."|1800",__('45 minutes','wpShop')."|2700", __('1 hour','wpShop')."|3600",__('2 hours','wpShop')."|7200",__('4 hours','wpShop')."|14400",__('8 hours','wpShop')."|28800",__('16 hours','wpShop')."|57600",__('24 hours','wpShop')."|86400",__('48 hours','wpShop')."|172800",__('72 hours','wpShop')."|259200",__('96 hours','wpShop')."|345600")),
					
					
					array(  	"name" 	=> __('Cleaning method','wpShop'),
								"desc" 	=> __('For calling the inventory cleaning routine you can either use a Cronjob or an internal function.','wpShop')."<br/><b>".__('The Internal function is programmed to be called once when the Frontpage is "requested" for the first time.','wpShop')."</b><br/><b>".__('If you have high trafic then a','wpShop')."<a target='_blank' href='http://faq.1and1.com/what_is_/32.html'>".__(' "Cronjob" ','wpShop')."</a>".__('is a better choice for performance reasons. Contact your host for more information.','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_inventory_cleaning_method",
								"std" 	=> "internal",
								"vals" 	=> array(__('Cronjob','wpShop')."|cronjob",__('Internal','wpShop')."|internal"),
								"type" 	=> "select2"),
				
					array(  	"name" 	=> __('Low-Stock Alert Threshold','wpShop'),
								"desc" 	=> __('You will receive a Low-Stock Alert Email if the stock of a product reaches this amount.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_stock_warn_threshold",
								"std" 	=> "2",
								"type" 	=> "text"),		

					array(  	"name" 	=> __('Low-Stock Alert Email','wpShop'),
								"desc" 	=> __('The Low-Stock Alert Email will be sent to this email address.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_stock_warn_email",
								"std" 	=> "",
								"type" 	=> "text"),					

				array(    	"type" 	=> "close"),
			array(   	"type" => "close"),
		array (		"type" 	=> "fieldset_end"),	
//###############################################################################################################    
		//Tab 3 Digital Products			
		array (		"type" 	=> "fieldset_start",
					"class" =>"shop",
					"id" 	=>"sec_digiProds_settings"),
							
			array ( 	"name" 	=> __('Digital Products','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),
						
					array(  	"name" 	=> __('Path to Master Data Directory','wpShop'),
								"desc" 	=> __('This is the path to your master data directory, please copy what is given to you here and paste it above without any whitespaces: ','wpShop')."<b>$masterpath</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_master_dir",
								"type" 	=> "text",
								"std" 	=> "../path/to/masterdata/"),

					array(  	"name" 	=> __('Licensing mode','wpShop'),
								"desc" 	=> __('Select your Licensing Mode. ','wpShop')."<br/><b>".__('SIMPLE','wpShop')."</b>".__(' means: your customers do not get a license key with your product. ','wpShop')."<br/><b>".__('GIVE_KEYS','wpShop')."</b>".__(' means: you provide licence keys for your digital products.','wpShop')."<br/>".__('What kind of keys these will be and how your product uses them is upto you and your software. We have just provided the ability for your customers to receive one licence key per digital product purchased','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_l_mode",
								"type" 	=> "select",
								"std" 	=> "SIMPLE",
								"vals" 	=> array("SIMPLE", "GIVE_KEYS")),
										
					array(  	"name" 	=> __('License Key Warning threshold','wpShop'),
								"desc" 	=> __('Enter the minimum number of unused License keys at which point an email reminder will be send to you.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_lkeys_warn_num",
								"type" 	=> "text",
								"std" 	=> "30"),

					array(  	"name" 	=> __('Download Link Duration','wpShop'),
								"desc" 	=> __('Select the time after which download links will become invalid or removed.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_duration_links",
								"type" 	=> "select2",
								"std" 	=> "300",
								"vals" 	=> array(__('5 minutes','wpShop')."|300", __('10 minutes','wpShop')."|600", __('1 hour','wpShop')."|3600",__('2 hours','wpShop')."|7200",__('4 hours','wpShop')."|14400",
								__('8 hours','wpShop')."|28800",__('16 hours','wpShop')."|57600",__('24 hours','wpShop')."|86400",__('48 hours','wpShop')."|172800",__('72 hours','wpShop')."|259200")),
					
					array(  	"name" 	=> __('Display short Address Form','wpShop'),
								"desc" 	=> __('Activating this setting shortens the Address Form (in step 2 in Checkout) to just Name &amp; Email. This will take effect only when digital products are in the basket.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_short_addressform",
								"type" 	=> "select2",
								"std" 	=> "not_active",
								"vals" 	=> array(__('Active','wpShop')."|active", __('Not active','wpShop')."|not_active")),
						
				array(    	"type" 	=> "close"),
			array(   	"type" => "close"),			
		array (		"type" 	=> "fieldset_end"),							
//###############################################################################################################
		//Tab 4 Payloadz			
		array (		"type" 	=> "fieldset_start",
					"class" =>"shop",
					"id" 	=>"sec_payloadz_settings"),
							
			array ( 	"name" 	=> __('PayLoadz','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),

					array(    	"name" 	=> __('View cart - ID','wpShop'),
								"desc" 	=> __('PayLoadz provides this under "CodeGenerator" &gt; "View Cart" Code.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_payloadz_viewcart_id",
								"std" 	=> "1234567890",
								"type" 	=> "text"),
										
					array(    	"name" 	=> __('View-Cart Link option','wpShop'),
								"desc" 	=> __('"View Cart" can be shown as an image or text link.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_payloadz_viewcart_option",
								"std" 	=> "img",
								"vals" 	=> array(__('Image','wpShop')."|img",__('Text link','wpShop')."|tl"),
								"type" 	=> "select2"),
										
					array(    	"name" 	=> __('View-Cart Image Link','wpShop'),
								"desc" 	=> __('If you have selected an image for your "View-Cart" link, you can change the url to point to a different button (graphic).','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_payloadz_viewcart_imglink",
								"std" 	=> 'https://www.payloadz.com/images/viewcart.gif',
								"type" 	=> "text"),
										
					array(    	"name" 	=> __('Add-2-Cart Link option','wpShop'),
								"desc" 	=> __('"Add to Cart" can be shown as an image or text link.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_payloadz_addcart_option",
								"std" 	=> "img",
								"vals" 	=> array(__('Image','wpShop')."|img",__('Text link','wpShop')."|tl"),
								"type" 	=> "select2"),
										
					array(    	"name" 	=> __('Add-2-Cart Image Link','wpShop'),
								"desc" 	=> __('If you have selected an image for your "Add-to-Cart" link, you can change the url to point to a different button (graphic).','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_payloadz_addcart_imglink",
								"std" 	=> 'http://www.paypal.com/images/x-click-but22.gif',
								"type" 	=> "text"),
										
				array(    	"type" 	=> "close"),
			array(   	"type" => "close"),			
		array (		"type" 	=> "fieldset_end"),	
//###############################################################################################################  			
		//Tab 5 Payloadz			
		array (		"type" 	=> "fieldset_start",
					"class" =>"shop",
					"id" 	=>"sec_paypal_settings"),
							
			array ( 	"name" 	=> __('Paypal','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),

					array(    	"name" 	=> __('Email','wpShop'),
								"desc" 	=> __('Enter your email -it must be the SAME AS the one you use in your PayPal account!','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_paypal_email",
								"std" 	=> "",
								"type" 	=> "text"),
							
					array(    	"name" 	=> __('PDT-Identity Token','wpShop'),
								"desc" 	=> __('Enter your Payment Data Transfer (PDT) Identity Token here - available in your PayPal account (Premier or Business) ','wpShop')."- <a href='https://www.paypal.com/cgi-bin/customerprofileweb?cmd=_profile-website-payments' target='_blank'>".__('PayPal direct link (you need to be logged in)','wpShop')."</a>",
								"id" 	=> $CONFIG_WPS['shortname']."_paypal_pdttoken",
								"std" 	=> "",
								"type" 	=> "text"),
					
					array(    	"name" 	=> __('PayPal Encode Key','wpShop'),
								"desc" 	=> __('This is used for additional security on transactions. Set a word string of your own.','wpShop')."<br/><b>".__('You may use letters and numbers but not whitespaces and no symbols!','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_paypal_encode_key",
								"std" 	=> "HastaLaVista",
								"type" 	=> "text"),	
										
					array(    	"name" 	=> __('Path: Return Url','wpShop'),
								"desc" 	=> __('Enter this in your PayPal account under "Profile > Website Payment Preferences > Return Url"','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_confirm_url",
								"std" 	=> get_option('home') . '/?confirm=1',
								"type" 	=> "text"),

					array(    	"name" 	=> __('Path: IPN Notification Url','wpShop'),
								"desc" 	=> __('Enter this in your PayPal account under "Profile > Instant Payment Notification Preferences > Notification Url"','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_ipn_url", 	
								"vals" 	=> get_option('siteurl') . '/wp-content/themes/'. $CONFIG_WPS['themename'] .'/ipn.php?pst='.md5(LOGGED_IN_KEY.'-'.NONCE_KEY),
								"type" 	=> "pathinfo2"),
					
										
				array(   	"type" => "close"),
			array(   	"type" => "close"),

			array( 		"name" => __('Paypal PRO','wpShop'),
						"type" => "title"),
								
				array(    	"type" => "open"),
									
					array(    	"name" => __('API username','wpShop'),
								"desc" => __('Enter your API username.','wpShop'),
								"id" => $CONFIG_WPS['shortname']."_paypal_api_user",
								"std" => "",
								"type" => "text"),
										
					array(    	"name" => __('API password','wpShop'),
								"desc" => __('Enter your API password.','wpShop'),
								"id" => $CONFIG_WPS['shortname']."_paypal_api_pw",
								"std" => "",
								"type" => "text"),					
										
					array(    	"name" => __('API signature','wpShop'),
								"desc" => __('Enter your API signature.','wpShop'),
								"id" => $CONFIG_WPS['shortname']."_paypal_api_signature",
								"std" => "",
								"type" => "text"),
							
				array(   	"type" => "close"),
			array(   	"type" => "close"),
		array (		"type" 	=> "fieldset_end"),			
//###############################################################################################################		
		//Tab 6 Authorize.net			
		array (		"type" 	=> "fieldset_start",
					"class" =>"shop",
					"id" 	=>"sec_authorize_settings"),
							
			array ( 	"name" 	=> __('Authorize.net','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),
				
					array(    	"name" 	=> __('API Login','wpShop'),
								"desc" 	=> __('Enter your API Login provided by Authorize.net','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_authn_api_login",
								"std" 	=> "",
								"type" 	=> "text"),
										
					array(    	"name" 	=> __('Transaction Key','wpShop'),
								"desc" 	=> __('Enter the transaction Key you received from Authorize.net','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_authn_transaction_key",
								"std" 	=> "",
								"type" 	=> "text"),					
										
					array(    	"name" 	=> __('Url','wpShop'),
								"desc" 	=> __('Change to "https://secure.authorize.net/gateway/transact.dll" when you are ready to go ','wpShop')."<b>".__('live/public','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_authn_url",
								"std" 	=> "https://test.authorize.net/gateway/transact.dll",
								"vals" 	=> array("https://secure.authorize.net/gateway/transact.dll","https://test.authorize.net/gateway/transact.dll"),
								"type" 	=> "select"),	
										
					array(    	"name" 	=> __('Test-Request','wpShop'),
								"desc" 	=> __('Change to "true" if you want to test your ','wpShop')."<b>".__('live/public','wpShop')."</b>".__(' Authorize.net account - otherwise leave on "false".','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_authn_test_request",
								"std" 	=> "false",
								"vals" 	=> array("false","true"),
								"type" 	=> "select"),
									
				array(   	"type" => "close"),	
			array(   	"type" => "close"),
		array (		"type" 	=> "fieldset_end"),	
//###############################################################################################################		
		//Tab 6 Gate2Play.com		
		array (		"type" 	=> "fieldset_start",
					"class" =>"shop",
					"id" 	=>"sec_g2p_settings"),
							
			array ( 	"name" 	=> __('Gate2Play.com','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),
				
					array(    	"name" 	=> __('Merchant ID','wpShop'),
								"desc" 	=> __('Enter your Merchant ID provided by Gate2Play.com','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_authn_mid_login",
								"std" 	=> "",
								"type" 	=> "text"),
										
					array(    	"name" 	=> __('Application ID','wpShop'),
								"desc" 	=> __('Enter the Application ID provided by Gate2Play.com','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_authn_appid_key",
								"std" 	=> "",
								"type" 	=> "text"),	
					array(    	"name" 	=> __('Application Secret Key','wpShop'),
								"desc" 	=> __('Enter the Application Secret Key provided by Gate2Play.com','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_authn_secret_key",
								"std" 	=> "",
								"type" 	=> "text"),	
					array(    	"name" 	=> __('Merchant&acute;s User ID','wpShop'),
								"desc" 	=> __('Enter the Merchant&acute;s User ID provided by Gate2Play.com','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_authn_muid_key",
								"std" 	=> "",
								"type" 	=> "text"),					
										
					/*array(    	"name" 	=> __('Url','wpShop'),
								"desc" 	=> __('Change to "https://secure.authorize.net/gateway/transact.dll" when you are ready to go ','wpShop')."<b>".__('live/public','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_authn_url",
								"std" 	=> "https://test.authorize.net/gateway/transact.dll",
								"vals" 	=> array("https://secure.authorize.net/gateway/transact.dll","https://test.authorize.net/gateway/transact.dll"),
								"type" 	=> "select"),	*/
										
					array(    	"name" 	=> __('Test-Request','wpShop'),
								"desc" 	=> __('Change to "true" if you want to test your ','wpShop')."<b>".__('live/public','wpShop')."</b>".__(' Authorize.net account - otherwise leave on "false".','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_authn_test_request",
								"std" 	=> "false",
								"vals" 	=> array("false","true"),
								"type" 	=> "select"),
									
				array(   	"type" => "close"),	
			array(   	"type" => "close"),
		array (		"type" 	=> "fieldset_end"),	

//############################################################################################################### 
		//Tab 7 WorldPay			
		array (		"type" 	=> "fieldset_start",
					"class" =>"shop",
					"id" 	=>"sec_worldpay_settings"),
							
			array ( 	"name" 	=> __('WorldPay','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),

					array(    	"name" 	=> __('Installation-ID','wpShop'),
								"desc" 	=> __('Enter the installation id you have received from WorldPay.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_wpay_instId",
								"std" 	=> "1234",
								"type" 	=> "text"),
										
					array(    	"name" 	=> __('Testmode','wpShop'),
								"desc" 	=> __('Ready to go live/public with WorldPay? Change this value to "false".','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_wpay_testmode",
								"std" 	=> "true",
								"vals" 	=> array("false","true"),
								"type" 	=> "select"),					
									
					array(    	"name" 	=> __('Payment Response Url','wpShop'),
								"desc" 	=> __('Enter this url as described here:','wpShop').' '."<a target='_blank' 
								href='http://www.rbsworldpay.com/support/kb/bg/paymentresponse/pr5101.html'>
								http://www.rbsworldpay.com/support/kb/bg/paymentresponse/pr5101.html</a>",
								"id" 	=> $CONFIG_WPS['shortname']."_wp_callback_url", 	
								"vals" 	=> get_option('siteurl').'/wpay.php?pst='.md5(LOGGED_IN_KEY.'-'.NONCE_KEY),
								"type" 	=> "pathinfo2"),
									
				array(   	"type" => "close"),
			array(   	"type" => "close"),			
		array (		"type" 	=> "fieldset_end"),	
//###############################################################################################################		
		//Tab 8 Authorize.net			
		array (		"type" 	=> "fieldset_start",
					"class" =>"shop",
					"id" 	=>"sec_alertpay_settings"),
							
			array ( 	"name" 	=> __('AlertPay','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),
				
					array(    	"name" 	=> __('API Login','wpShop'),
								"desc" 	=> __('Enter your API Login provided by AlertPay.com','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_alertpay_api_login",
								"std" 	=> "",
								"type" 	=> "text"),
										
					array(    	"name" 	=> __('Transaction Key','wpShop'),
								"desc" 	=> __('Enter the transaction Key you received from Alertpay.com','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_alertpay_transaction_key",
								"std" 	=> "",
								"type" 	=> "text"),
					array(    	"name" 	=> __('Path: Return Url','wpShop'),
								"desc" 	=> __('Enter Return Url to your site"','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_alertpay_confirm_url",
								"std" 	=> get_option('home') . '/?payment=ok',
								"type" 	=> "text"),

					array(    	"name" 	=> __('Path: IPN Notification Url','wpShop'),
								"desc" 	=> __('Enter this in your Instant Payment Notification Url"','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_alertpay_ipn_url", 	
								"vals" 	=> get_option('siteurl') . '/',
								"type" 	=> "text"),					
					array(    	"name" 	=> __('Cancel URL','wpShop'),
								"desc" 	=> __('Enter cancel page URL','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_alertpay_api_cancelurl",
								"std" 	=> get_option('home')."/?payment=canceled",
								"type" 	=> "text"),
										
					
										
					array(    	"name" 	=> __('Test-Request','wpShop'),
								"desc" 	=> __('Change to "true" if you want to test your ','wpShop')."<b>".__('live/public','wpShop')."</b>".__(' alertpay.com account - otherwise leave on "false".','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_alertpay_test_request",
								"std" 	=> "false",
								"vals" 	=> array("false","true"),
								"type" 	=> "select"),
									
				array(   	"type" => "close"),	
			array(   	"type" => "close"),
		array (		"type" 	=> "fieldset_end"),	
//###############################################################################################################		
		//Tab 9 2CHECKOUT.net			
		array (		"type" 	=> "fieldset_start",
					"class" =>"shop",
					"id" 	=>"sec_2checkout_settings"),
							
			array ( 	"name" 	=> __('2CHECKOUT','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),
				
					array(    	"name" 	=> __('Account Number','wpShop'),
								"desc" 	=> __('Your 2CHECKOUT account number','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_2checkout_sid",
								"std" 	=> "",
								"type" 	=> "text"),
										
					array(    	"name" 	=> __('Transaction Key','wpShop'),
								"desc" 	=> __('Enter your secret word from 2CHECKOUT account','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_2checkout_key",
								"std" 	=> "",
								"type" 	=> "text"),
					array(    	"name" 	=> __('Path: Return Url','wpShop'),
								"desc" 	=> __('Used to controll where the Continue Shopping button will send the customer when clicked"','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_2checkout_return_url",
								"std" 	=> get_cart_url(),
								"type" 	=> "text"),

					/*array(    	"name" 	=> __('Path: IPN Notification Url','wpShop'),
								"desc" 	=> __('Enter this in your Instant Payment Notification Url"','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_2checkout_ipn_url", 	
								"vals" 	=> get_option('siteurl') . '/',
								"type" 	=> "text"),				*/	
					array(    	"name" 	=> __('Cancel URL','wpShop'),
								"desc" 	=> __('Enter cancel page URL','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_2checkout_cancel_url",
								"std" 	=> get_option('home')."/?payment=canceled",
								"type" 	=> "text"),
									
				array(   	"type" => "close"),	
			array(   	"type" => "close"),
		array (		"type" 	=> "fieldset_end"),	
//###############################################################################################################		
		//Tab 9 AUDI			
		array (		"type" 	=> "fieldset_start",
					"class" =>"shop",
					"id" 	=>"sec_audi_settings"),
							
			array ( 	"name" 	=> __('AUDI','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),
				
					array(    	"name" 	=> __('Merchant ID','wpShop'),
								"desc" 	=> __('Your AUDI Merchant ID number','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_audi_mid",
								"std" 	=> "",
								"type" 	=> "text"),
					array(    	"name" 	=> __('Access Code ','wpShop'),
								"desc" 	=> __('Enter your Access Code from AUDI account','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_audi_code",
								"std" 	=> "",
								"type" 	=> "text"),
					array(    	"name" 	=> __('Secure Hash Secret','wpShop'),
								"desc" 	=> __('Enter your Secure Hash Secret from AUDI account','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_audi_secret",
								"std" 	=> "",
								"type" 	=> "text"),										
					array(    	"name" 	=> __('Path: Return Url','wpShop'),
								"desc" 	=> __('Used to controll where the Continue Shopping button will send the customer when clicked "cancel"','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_audi_return_url",
								"std" 	=> get_cart_url(),
								"type" 	=> "text"),
					array(    	"name" 	=> __('Cancel URL','wpShop'),
								"desc" 	=> __('Enter cancel page URL','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_audi_cancel_url",
								"std" 	=> get_option('home')."/?payment=canceled",
								"type" 	=> "text"),
									
				array(   	"type" => "close"),	
			array(   	"type" => "close"),
		array (		"type" 	=> "fieldset_end"),			
//###############################################################################################################
		//Tab 9 Bank Transfer			
		array (		"type" 	=> "fieldset_start",
					"class" =>"shop",
					"id" 	=>"sec_banktransfer_settings"),
							
			array ( 	"name" 	=> __('Bank Transfer','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),

					array(    	"name" 	=> __('Name of Bank','wpShop'),
								"desc" 	=> __('Enter the name of your Bank.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_banktransfer_bankname",
								"std" 	=> "",
								"type" 	=> "text"),
								
					array(  	"name" 	=> __('Display Routing Number','wpShop'),
								"desc" 	=> __('Check this setting if you want to display your Bank\'s Routing Number','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_banktransfer_routing_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(  	"name" 	=> __('Routing Number Text','wpShop'),
								"desc" 	=> __('Here you can enter the Routing number text or initials commonly used in your country.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_banktransfer_routing_text",
								"std" 	=> "Routing Number",
								"type" 	=> "text"),
										
					array(    	"name" 	=> __('Routing Number','wpShop'),
								"desc" 	=> __('Enter your Bank\'s Routing number','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_banktransfer_bankno",
								"std" 	=> "",
								"type" 	=> "text"),					
										
					array(    	"name" 	=> __('Account Number','wpShop'),
								"desc" 	=> __('Enter your Bank Account Number.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_banktransfer_accountno",
								"std" 	=> "",
								"type" 	=> "text"),
										
					array(    	"name" 	=> __('Bank Account Owner','wpShop'),
								"desc" 	=> __('Enter the name of the person/company/institution who is the official Owner of the Bank Account.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_banktransfer_account_owner",
								"std" 	=> "",
								"type" 	=> "text"),

					array(    	"name" 	=> __('IBAN','wpShop'),
								"desc" 	=> __('Enter your IBAN code. Leave empty if not needed.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_banktransfer_iban",
								"std" 	=> "",
								"type" 	=> "text"),					
										
					array(    	"name" 	=> __('BIC/SWIFT','wpShop'),
								"desc" 	=> __('Enter your BIC code - also called SWIFT. Leave empty if not needed.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_banktransfer_bic",
								"std" 	=> "",
								"type" 	=> "text"),
										
										
					array(    	"name" 	=> __('Url to your Online Banking','wpShop'),
								"desc" 	=> __('Enter here your Url to your Online Banking in the form of www.bank.com - makes checking your bank account a bit easier.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_online_banking_url",
								"std" 	=> "www.bankofengland.co.uk",
								"type" 	=> "text"),					

				array(   	"type" => "close"),
			array(   	"type" => "close"),
		array (		"type" 	=> "fieldset_end"),			
//###############################################################################################################
		//Tab 10 Cash on Delivery			
		array (		"type" 	=> "fieldset_start",
					"class" =>"shop",
					"id" 	=>"sec_cashOnDelivery_settings"),
							
			array ( 	"name" 	=> __('Cash on Delivery','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),

					array(    	"name" 	=> __('Delivery service','wpShop'),
								"desc" 	=> __('Enter the name of the company who will do the cash on delivery service for you. It will be added to the delivery options for your customers to select.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_cod_service",
								"std" 	=> "Deutsche Bundespost",
								"type" 	=> "text"),
										
					array(    	"name" 	=> __('Delivery Service 2','wpShop'),
								"desc" 	=> __('A large Delivery Company may offer more than one delivery option. Here you may specify which one. Leave empty if not using.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_cod_who_note",
								"std" 	=> "Deutsche Bundespost",
								"type" 	=> "text"),

				array(   	"type" => "close"),
			array(   	"type" => "close"),
		array (		"type" 	=> "fieldset_end"),		
//###############################################################################################################
		//Tab 11 Shipping			
		array (		"type" 	=> "fieldset_start",
					"class" =>"shop",
					"id" 	=>"sec_shipping_settings"),
							
			array ( 	"name" 	=> __('Shipping','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),

					array(  	"name" 	=> __('Shipping Calculation Options','wpShop'),
								"desc" 	=> __('Select your Shipping Calculation Method.','wpShop')."<br/><br/><b>".__('FREE: ','wpShop')."</b>".__('what it says. Shipping is free regardless of item number or weight','wpShop')."<br/><br/><b>".__('FLAT: ','wpShop')."</b>".__('a one time fee aplied regardless of item number or weight','wpShop')."<br/><br/><b>".__('FLAT_LIMIT: ','wpShop')."</b>".__('same as FLAT above with the only difference that for orders above a certain value shipping is free (you will be asked to set this value later on)','wpShop')."<br/><br/><b>".__('WEIGHT_FLAT: ','wpShop')."</b>".__('a set fee amount per kilogramm (the only acceptable weight meassurement for now). Using this option will mean that you need to set the weight in gramms eg. 500 for each product using the custom field "item_weight"','wpShop')."<br/><br/><b>".__('WEIGHT_CLASS: ','wpShop')."</b>".__('a different fee amount applied to different weight classes. Using this option will means that you need to set the weight in gramms eg. 500 for each product using the custom field "item_weight','wpShop')."<br/><br/><b>".__('PER_ITEM: ','wpShop')."</b>".   __('a fee applied according to item number in cart','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shipping_method",
								"type" 	=> "select",
								"std" 	=> "FLAT",
								"vals" 	=> array("FREE", "FLAT", "FLAT_LIMIT", "WEIGHT_FLAT", "WEIGHT_CLASS", "PER_ITEM")),
										
					array(  	"name" 	=> __('FLAT - Parameters','wpShop'),
								"desc" 	=> __('Enter a flat-rate shipping fee using the format 0.00','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shipping_flat_parameter",
								"type" 	=> "text",
								"std" 	=> "5.00"),

					array(  	"name" 	=> __('FLAT_LIMIT - Parameters','wpShop'),
								"desc" 	=> __('Enter a flat-rate shipping fee &amp; the amount limit for free shipping in the format 0.00|0.00.','wpShop')."<br/><br/>".__('Eg. 4.00|40.00 means that:','wpShop')."<br/>".__('Shipping &amp; Handling of 4.00 EUR (if currency is set to euros) is charged on all orders and is free on orders over 40.00 EUR (if currency is set to euros)','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shipping_flatlimit_parameter",
								"type" 	=> "text",
								"std" 	=> "4.00|40.00"),
								
					array(  	"name" 	=> __('Weight Meassurement Unit','wpShop'),
								"desc" 	=> __('Select your Weight Meassurement Unit','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_meassuring_unit",
								"type" 	=> "select",
								"std" 	=> "grams",
								"vals" 	=> array("grams", "pounds")),

					array(  	"name" 	=> __('WEIGHT_FLAT- Parameters','wpShop'),
								"desc" 	=> __('Enter the shipping fee per kg (for metric) / lb (for avoirdupois) using the format 0.00','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shipping_weightflat_parameter",
								"type" 	=> "text",
								"std" 	=> "1.00"),

					array(  	"name" 	=> __('WEIGHT_CLASS - Parameters','wpShop'),
								"desc" 	=> __('SEPERATE the weight_classes from the amount using | as shown.','wpShop')."<br/>".__(' SEPERATE each weight class &amp; fee amount from the next with # as shown.','wpShop')."<br/><b>".__(' DO NOT LEAVE EMPTY SPACES!! ','wpShop')."</b><br/>".__('You can have as many weight classes as you want.','wpShop')."<br/><br/>".__('Eg.','wpShop')." 0-10|5#11-15|8#16-ul|15 ".__('means that:','wpShop')."<br/>".__('From 0-10 kg (for metric) / lb (for avoirdupois) the shipping fee is 5 EUR (if the currency is set to euros)','wpShop')."<br/><br/>".__('From 11-15 kg (for metric) / lb (for avoirdupois) the shipping fee is 8 EUR (if the currency is set to euros)','wpShop')."<br/><br/>".__('From 16-unlimited kg (for metric) / lb (for avoirdupois) the shipping fee is 15 EUR (if the currency is set to euros)','wpShop')."<br/><br/>".__('ul stands for: unlimited.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shipping_weightclass_parameter",
								"type" 	=> "text",
								"std" 	=> "0-10|5#11-15|8#16-ul|15"),
										
					array(  	"name" 	=> __('PER_ITEM - Parameters','wpShop'),
								"desc" 	=> __('Enter the shipping fee per item in the format 0.00','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shipping_peritem_parameter",
								"type" 	=> "text",
								"std" 	=> "1.00"),
								
					array(  	"name" 	=> __('Free Shipping Categories','wpShop'),
								"desc" 	=> __('Enter the category IDs whose products will have free shipping. Separate multiple IDs with a comma.','wpShop')."<br/><strong>".__('Note: The Shipping will be free when the shopping cart contains products only from the free shipping categories. If the shopping cart is mixed then the selected Shipping Calculation Option will apply','wpShop')."</strong>",
								"id" 	=> $CONFIG_WPS[shortname]."_free_shipping_categories",
								"type" 	=> "text",
								"std" 	=> ""),
								
				array(   	"type" => "close"),
			array(   	"type" => "close"),
								
			// International Shipping Settings					
			array ( 	"name" 	=> __('International Shipping Settings','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),	
										
					array(  	"name" 	=> __('Assign Country Zones','wpShop'),
								"desc" 	=> __('Give countries a zone number. Don\'t forget to hit the "Save" button at the end of the iFrame!','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shipping_country_zones",
								"type" 	=> "iframe",
								"vals" 	=> "../wp-content/themes/".$CONFIG_WPS['themename']."/shop-country-zones.php",
								"std" 	=> ""),		
										
					array(  	"name" 	=> __('Zone 1','wpShop'),
								"desc" 	=> __('Zone 1 is the country your shop is based - identical with what you entered in the "Shop Country" setting under "Shop > General".','wpShop'),
								"id" 	=> "$zone1",
								"type" 	=> "pathinfo",
								"std" 	=> "0.00"),
										
					array(  	"name" 	=> __('Zone 2','wpShop'),
								"desc" 	=> __('Enter the additional shipping fee amount for an order send to this country zone.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shipping_zone2_addition",
								"type" 	=> "text",
								"std" 	=> "2.00"),
										
					array(  	"name" 	=> __('Zone 3','wpShop'),
								"desc" 	=> __('Enter the additional shipping fee amount for an order send to this country zone.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shipping_zone3_addition",
								"type" 	=> "text",
								"std" 	=> "3.00"),
										
					array(  	"name" 	=> __('Zone 4','wpShop'),
								"desc" 	=> __('Enter the additional shipping fee amount for an order send to this country zone.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shipping_zone4_addition",
								"type" 	=> "text",
								"std" 	=> "4.00"),
										
					array(  	"name" 	=> __('Zone 5','wpShop'),
								"desc" 	=> __('Enter the additional shipping fee amount for an order send to this country zone.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shipping_zone5_addition",
								"type" 	=> "text",
								"std" 	=> "5.00"),
										
					array(  	"name" 	=> __('Zone 6','wpShop'),
								"desc" 	=> __('Enter the additional shipping fee amount for an order send to this country zone.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_shipping_zone6_addition",
								"type" 	=> "text",
								"std" 	=> "6.00"),
						
				array(   	"type" => "close"),
			array(   	"type" => "close"),
		array (		"type" 	=> "fieldset_end"),		
//###############################################################################################################
		//Tab 11 Emails			
		array (		"type" 	=> "fieldset_start",
					"class" =>"shop",
					"id" 	=>"sec_email_settings"),
							
			array ( 	"name" 	=> __('Order Confirmation Emails','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),
				
					array(  	"name" 	=> __('Order Number Prefix','wpShop'),
								"desc" 	=> __('Order Confirmation Emails are send after each succesful order and are given a number starting from 1. Enter a prefix here if you want to.','wpShop')."<br/><b>".__('You may use numbers as well as letters and symbols. Please avoid empty spaces.','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_order_no_prefix",
								"type" 	=> "text",
								"std" 	=> "1000"),
			
					array(  	"name" 	=> __('Email-Logo','wpShop'),
								"desc" 	=> __('You can have your own logo on the html emails send to your customers. Enter the name of your logo image file - must saved in the Parent theme &gt; images &gt; logo folder inside the parent theme.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_email_logo",
								"type" 	=> "text",
								"std" 	=> "email-logo.jpg"),		
						
					array(  	"name" 	=> __('Email-Header Txt-Mail','wpShop'),
								"desc" 	=> __('The shop will sent notification emails as text emails to you. Here you can change the header text of these emails.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_email_txt_header",
								"type" 	=> "textarea",
								"std" 	=> "The Furniture Store"),
										
					array(  	"name" 	=> __('Email Delivery Type','wpShop'),
								"desc" 	=> __('By default this value is mime. Should there be problems with the email display, please select the "txt" option','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_email_delivery_type",
								"type" 	=> "select",
								"std" 	=> "mime",
								"vals" 	=> array("mime", "txt")),
								
					array(  	"name" 	=> __('Send a copy of the customer confirmation mail to admin?','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_email_confirmation_dbl",
								"type" 	=> "select",
								"std" 	=> "mime",
								"vals" 	=> array("no", "yes")),

				array(   	"type" => "close"),
			array(   	"type" => "close"),
		array (		"type" 	=> "fieldset_end"),	
//###############################################################################################################

//GW START
//###############################################################################################################


		//Tab 12 Currency Rates			
		array (		"type" 	=> "fieldset_start",
					"class" =>"shop",
					"id" 	=>"sec_exr_settings"),
							
			array ( 	"name" 	=> __('Exchange Rates','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),
				
					array(  	"name" 	=> __('Active Currencies','wpShop'),
								"desc" 	=> __('Activate the currencies you want to make available for currency exchange.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_currency_options",
								"vals" 	=> array(__('BHD Bahrani Dinar','wpShop')."|bhd",__('EGP Egyptian Pound','wpShop')."|egp",__('Jordanian Dinar','wpShop')."|jod",__('Kuwati Dinar','wpShop')."|kwd",__('Lebanese Pound','wpShop')."|lbp",__('Qatari Riyal','wpShop')."|qar",__('Saudi Riyal','wpShop')."|sar",__('Syrian Pound','wpShop')."|syp",__('UAE Dirham','wpShop')."|aed",__('Omani Rial','wpShop')."|omr"), 
								"type" 	=> "multi-checkbox",
								"std" 	=> "false"),

					array(  	"name" 	=> __('BHD Bahrani Dinar','wpShop'),
								"desc" 	=> __('Enter exchange rate - base currency is '.get_option('wps_currency_code').'','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_exr_bhd",
								"type" 	=> "text",
								"std" 	=> ""),
								
								
					array(  	"name" 	=> __('EGP Egyptian Pound','wpShop'),
								"desc" 	=> __('Enter exchange rate - base currency is '.get_option('wps_currency_code').'','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_exr_egp",
								"type" 	=> "text",
								"std" 	=> ""),
			
					array(  	"name" 	=> __('Jordanian Dinar','wpShop'),
								"desc" 	=> __('Enter exchange rate - base currency is '.get_option('wps_currency_code').'','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_exr_jod",
								"type" 	=> "text",
								"std" 	=> ""),

					array(  	"name" 	=> __('Kuwati Dinar','wpShop'),
								"desc" 	=> __('Enter exchange rate - base currency is '.get_option('wps_currency_code').'','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_exr_kwd",
								"type" 	=> "text",
								"std" 	=> ""),
								
					array(  	"name" 	=> __('Lebanese Pound','wpShop'),
								"desc" 	=> __('Enter exchange rate - base currency is '.get_option('wps_currency_code').'','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_exr_lbp",
								"type" 	=> "text",
								"std" 	=> ""),
								
					array(  	"name" 	=> __('Qatari Riyal','wpShop'),
								"desc" 	=> __('Enter exchange rate - base currency is '.get_option('wps_currency_code').'','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_exr_qar",
								"type" 	=> "text",
								"std" 	=> ""),
			
					array(  	"name" 	=> __('Saudi Riyal','wpShop'),
								"desc" 	=> __('Enter exchange rate - base currency is '.get_option('wps_currency_code').'','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_exr_sar",
								"type" 	=> "text",
								"std" 	=> ""),

					array(  	"name" 	=> __('Syrian Pound','wpShop'),
								"desc" 	=> __('Enter exchange rate - base currency is '.get_option('wps_currency_code').'','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_exr_syp",
								"type" 	=> "text",
								"std" 	=> ""),

					array(  	"name" 	=> __('UAE Dirham','wpShop'),
								"desc" 	=> __('Enter exchange rate - base currency is '.get_option('wps_currency_code').'','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_exr_aed",
								"type" 	=> "text",
								"std" 	=> ""),

					array(  	"name" 	=> __('Omani Rial','wpShop'),
								"desc" 	=> __('Enter exchange rate - base currency is '.get_option('wps_currency_code').'','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_exr_omr",
								"type" 	=> "text",
								"std" 	=> ""),
					// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
					array(  	"name" 	=> __('USD - Location','wpShop'),
								"desc" 	=> __('Enter location of this currency','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_exr_loc_usd",
								"type" 	=> "text",
								"std" 	=> ""),

					array(  	"name" 	=> __('BHD Bahrani Dinar - Location','wpShop'),
								"desc" 	=> __('Enter location of this currency','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_exr_loc_bhd",
								"type" 	=> "text",
								"std" 	=> ""),
								
								
					array(  	"name" 	=> __('EGP Egyptian Pound - Location','wpShop'),
								"desc" 	=> __('Enter location of this currency','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_exr_loc_egp",
								"type" 	=> "text",
								"std" 	=> ""),
			
					array(  	"name" 	=> __('Jordanian Dinar - Location','wpShop'),
								"desc" 	=> __('Enter location of this currency','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_exr_loc_jod",
								"type" 	=> "text",
								"std" 	=> ""),

					array(  	"name" 	=> __('Kuwati Dinar - Location','wpShop'),
								"desc" 	=> __('Enter location of this currency','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_exr_loc_kwd",
								"type" 	=> "text",
								"std" 	=> ""),
								
					array(  	"name" 	=> __('Lebanese Pound - Location','wpShop'),
								"desc" 	=> __('Enter location of this currency','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_exr_loc_lbp",
								"type" 	=> "text",
								"std" 	=> ""),
								
					array(  	"name" 	=> __('Qatari Riyal - Location','wpShop'),
								"desc" 	=> __('Enter location of this currency','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_exr_loc_qar",
								"type" 	=> "text",
								"std" 	=> ""),
			
					array(  	"name" 	=> __('Saudi Riyal - Location','wpShop'),
								"desc" 	=> __('Enter location of this currency','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_exr_loc_sar",
								"type" 	=> "text",
								"std" 	=> ""),

					array(  	"name" 	=> __('Syrian Pound - Location','wpShop'),
								"desc" 	=> __('Enter location of this currency','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_exr_loc_syp",
								"type" 	=> "text",
								"std" 	=> ""),

					array(  	"name" 	=> __('UAE Dirham - Location','wpShop'),
								"desc" 	=> __('Enter location of this currency','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_exr_loc_aed",
								"type" 	=> "text",
								"std" 	=> ""),

					array(  	"name" 	=> __('Omani Rial - Location','wpShop'),
								"desc" 	=> __('Enter location of this currency','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_exr_loc_omr",
								"type" 	=> "text",
								"std" 	=> ""),


				array(   	"type" => "close"),
			array(   	"type" => "close"),
		array (		"type" 	=> "fieldset_end"),	
//###############################################################################################################
//GW END

//Tab 11 PDF			
		array (		"type" 	=> "fieldset_start",
					"class" =>"shop",
					"id" 	=>"sec_pdf_settings"),
							
			array ( 	"name" 	=> __('PDF / HTML Invoices &amp; Vouchers','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),
				
					array(  	"name" 	=> __('PDF / HTML Invoice Prefix','wpShop'),
								"desc" 	=> __('PDF / HTML Invoices are generated after each succesful order for your customers to download Enter the Document prefix here.','wpShop')."<br/><b>".__('Please avoid empty spaces and do not end with an underscore or hyphen!','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_invoice_prefix",
								"type" 	=> "text",
								"std" 	=> "Invoice"),
				
					array(  	"name" 	=> __('PDF / HTML Invoice Number Prefix','wpShop'),
								"desc" 	=> __('PDF / HTML Invoices are generated after each succesful order for your customers to download and are given a number starting from 1. Enter a prefix here if you want to.','wpShop')."<br/><b>".__('You may use numbers as well as letters and symbols. Please avoid empty spaces.','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_invoice_no_prefix",
								"type" 	=> "text",
								"std" 	=> "20100"),
								
					array(  	"name" 	=> __('Display Invoice Number?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display the Invoice Number on the generated PDF','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_PDF_invoiceNum_enable",
								"type" 	=> "checkbox",
								"std" 	=> "true"),
								
					array(  	"name" 	=> __('Invoice Number Label','wpShop'),
								"desc" 	=> __('Enter Invoice Label','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_PDF_invoiceLabel",
								"type" 	=> "text",
								"std" 	=> "Invoice-No.:"),
								
					array(  	"name" 	=> __('Invoice Number Label alignment','wpShop'),
								"desc" 	=> __('Select the text alignment','wpShop')."<br/><b>".__('Note: L stands for Left, C for Center and R for Right','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_PDF_invoiceLabel_align",
								"std" 	=> "L",
								"vals" 	=> array("L", "C", "R"),
								"type" 	=> "select"),
								
					array(  	"name" 	=> __('Display Order Number?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display the Order Number on the generated PDF','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_PDF_orderNum_enable",
								"type" 	=> "checkbox",
								"std" 	=> "true"),
								
					array(  	"name" 	=> __('Order Number Label','wpShop'),
								"desc" 	=> __('Enter Order Label','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_PDF_orderLabel",
								"type" 	=> "text",
								"std" 	=> "Order-No.:"),
								
					array(  	"name" 	=> __('Order Number Label alignment','wpShop'),
								"desc" 	=> __('Select the text alignment','wpShop')."<br/><b>".__('Note: L stands for Left, C for Center and R for Right','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_PDF_orderLabel_align",
								"std" 	=> "L",
								"vals" 	=> array("L", "C", "R"),
								"type" 	=> "select"),
		
					array(  	"name" 	=> __('Display Order Tracking ID?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display the Order Tracking ID on the generated PDF','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_PDF_trackID_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(  	"name" 	=> __('Order Tracking ID Label','wpShop'),
								"desc" 	=> __('Enter Order Tracking Label','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_PDF_trackLabel",
								"type" 	=> "text",
								"std" 	=> "Tracking-ID:"),
								
					array(  	"name" 	=> __('Order Tracking ID Label alignment','wpShop'),
								"desc" 	=> __('Select the text alignment','wpShop')."<br/><b>".__('Note: L stands for Left, C for Center and R for Right','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_PDF_trackLabel_align",
								"std" 	=> "L",
								"vals" 	=> array("L", "C", "R"),
								"type" 	=> "select"),

					array(  	"name" 	=> __('Display Payment Method?','wpShop'),
								"desc" 	=> __('Check this setting if you want to display the Payment Method on the generated PDF','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_PDF_payment_method_enable",
								"type" 	=> "checkbox",
								"std" 	=> "true"),
								
					array(  	"name" 	=> __('Payment Method Label','wpShop'),
								"desc" 	=> __('Enter Payment Method Label','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_PDF_payment_method_label",
								"type" 	=> "text",
								"std" 	=> "P:"),

					array(  	"name" 	=> __('Payment Method Label alignment','wpShop'),
								"desc" 	=> __('Select the text alignment','wpShop')."<br/><b>".__('Note: L stands for Left, C for Center and R for Right','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_PDF_payment_method_label_align",
								"std" 	=> "L",
								"vals" 	=> array("L", "C", "R"),
								"type" 	=> "select"),

					array(  	"name" 	=> __('VAT-ID Label','wpShop'),
								"desc" 	=> __('Enter the fitting label for the VAT-ID below. In e.g. Germany this could be "Steuernummer" or "USt-ID-Nr."','wpShop')."<br/><b>".__('Note: In some countries like Germany, the Law requires this to appear on Business Invoices. It will display attached to your Shop\'s Name in the PDF Footer','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_vat_id_label",
								"type" 	=> "text",
								"std" 	=> ""),	
										
					array(  	"name" 	=> __('VAT-ID','wpShop'),
								"desc" 	=> __('Enter your VAT-ID.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_vat_id",
								"type" 	=> "text",
								"std" 	=> ""),
								
					array(  	"name" 	=> __('Display Delivery Address?','wpShop'),
								"desc" 	=> __('By default the PDF will show the customer\'s billing address only. Check this setting if you want the display the Delivery Address in addition.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_PDF_delAddr_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),

				array(   	"type" => "close"),
			array(   	"type" => "close"),
			
			// General			
			array ( 	"name" 	=> __('PDF Invoice Format, Margins and Table Column Widths','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),
				
					array(  	"name" 	=> __('PDF Invoice Format','wpShop'),
								"desc" 	=> __('','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_invoiceFormat",
								"std" 	=> "International (A4)",
								"vals" 	=> array(__('International (A4)','wpShop')."|A4",__('American (Letter)','wpShop')."|Letter"),
								"type" 	=> "select2"),
					
					array(  	"name" 	=> __('Left Margin','wpShop'),
								"desc" 	=> __('Set the left margin in mm','wpShop')."<br/><b>".__('Note: For calculating the dimmensions here and for the settings bellow it may help if you remember the following sizes:','wpShop')."</b><br/><b>".__('International A4 format: 210 × 297 mm','wpShop')."</b><br/><b>".__('US Letter Format: 216 × 279 mm','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_leftMargin",
								"type" 	=> "text",
								"std" 	=> "10"),
								
					array(  	"name" 	=> __('Right Margin','wpShop'),
								"desc" 	=> __('Set the Right margin in mm','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_rightMargin",
								"type" 	=> "text",
								"std" 	=> "10"),
								
					array(  	"name" 	=> __('Top Margin','wpShop'),
								"desc" 	=> __('Set the top margin in mm','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_topMargin",
								"type" 	=> "text",
								"std" 	=> "10"),
								
					array(  	"name" 	=> __('Item No. Column Width','wpShop'),
								"desc" 	=> __('Set the Item No. Column Width in mm','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_colWidth1",
								"type" 	=> "text",
								"std" 	=> "20"),
								
					array(  	"name" 	=> __('Item Column Width','wpShop'),
								"desc" 	=> __('Set the Item Column Width in mm','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_colWidth2",
								"type" 	=> "text",
								"std" 	=> "115"),
								
					array(  	"name" 	=> __('Quantity Column Width','wpShop'),
								"desc" 	=> __('Set the Quantity Column Width in mm','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_colWidth3",
								"type" 	=> "text",
								"std" 	=> "15"),
								
					array(  	"name" 	=> __('Item Price Column Width','wpShop'),
								"desc" 	=> __('Set the Item Price Column Width in mm','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_colWidth4",
								"type" 	=> "text",
								"std" 	=> "20"),
								
					array(  	"name" 	=> __('Item Total Column Width','wpShop'),
								"desc" 	=> __('Set the Total Column Width in mm','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_colWidth5",
								"type" 	=> "text",
								"std" 	=> "20"),
					
				array(   	"type" => "close"),
			array(   	"type" => "close"),
			
			
			// Header			
			array ( 	"name" 	=> __('PDF Invoice Header','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),
					
					array(  	"name" 	=> __('PDF / HTML Invoice Logo','wpShop'),
								"desc" 	=> __('Customize the logo on the PDF / HTMl invoice. Enter the name of your image file - must be saved in the Parent theme &gt; images &gt; logo folder inside the parent theme.','wpShop')."<br/><b>".__('Supported are: jpegs, pngs, gifs, transparency. NOT Supported are: Interlacing and Alpha Channel','wpShop')."</b><br/><b>".__('When creating your image, remember that normally for viewing 72/96/150dpi is used, for printing 200-600dpi','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_logo",
								"type" 	=> "text",
								"std" 	=> "pdf-logo.jpg"),

					array(  	"name" 	=> __('PDF Logo- Image Width','wpShop'),
								"desc" 	=> __('Set the desired width of your image file in mm. The height is calculated automatically to respect the image proportions','wpShop')."<br/><b>".__('If you use the provided psd file to create your logo you get an image with 300dpi quality and you don\'t have to change the default dimmension set here','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_logoWidth",
								"type" 	=> "text",
								"std" 	=> "70"),// (200px / 300dpi) x 25.4 = width in mm
					
					array(  	"name" 	=> __('Display Only Logo','wpShop'),
								"desc" 	=> __('By default the PDF will print your Shop\'s Address as you have entered it under your General Settings. Check this setting if you only want to display the Logo in the PDF Header','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_header_addr_disable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(  	"name" 	=> __('Display only my Shop\'s Name','wpShop'),
								"desc" 	=> __('Check this setting if you want to display only the Shop\'s Name and not the entire Address on the generated PDF','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_shop_name_only",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(  	"name" 	=> __('Custom Header Text','wpShop'),
								"desc" 	=> __('If you need any other custom text to appear in the PDF Header enter it here.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_header_custom_text",
								"type" 	=> "text",
								"std" 	=> ""),
								
					array(  	"name" 	=> __('Set the Text Colour for the Address','wpShop'),
								"desc" 	=> __('Colours can be expressed in ','wpShop')."<a target='_blank' href='http://www.colorpicker.com/'>".__('RGB components or gray scale.','wpShop')."</a>",
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_header_txtColour",
								"type" 	=> "text",
								"std" 	=> "0,0,0"),
								
					array(  	"name" 	=> __('Set the Font Size for the Address','wpShop'),
								"desc" 	=> __('','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_header_fontSize",
								"type" 	=> "text",
								"std" 	=> "12"),
								
					array(  	"name" 	=> __('Border Around Address?','wpShop'),
								"desc" 	=> __('Check this setting if you want to have a around the Shop\'s Address on the generated PDF','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_header_addrBorder",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(  	"name" 	=> __('Border Width','wpShop'),
								"desc" 	=> __('Set the Border width in mm. The default is 0.2 (mm)','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_header_addrBorderWidth",
								"type" 	=> "text",
								"std" 	=> "0.2"),
								
					array(  	"name" 	=> __('Background Fill behind Shop Address?','wpShop'),
								"desc" 	=> __('Check this setting if you want to have a background colour behind the Shop\'s Address on the generated PDF','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_header_bgdColour_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(  	"name" 	=> __('Set the Background Colour for the Address','wpShop'),
								"desc" 	=> __('Colours can be expressed in ','wpShop')."<a target='_blank' href='http://www.colorpicker.com/'>".__('RGB components or gray scale.','wpShop')."</a>",
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_header_bgdColour",
								"type" 	=> "text",
								"std" 	=> "255,255,255"),

				array(   	"type" => "close"),
			array(   	"type" => "close"),
			
			// Footer			
			array ( 	"name" 	=> __('PDF Invoice Footer','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),
					
					array(  	"name" 	=> __('Custom Footer Text','wpShop'),
								"desc" 	=> __('By default the PDF will display your Shop\'s Name (along with your Tax Label and ID if one is provided) centered at the bottom. If you need any other custom text to appear in the PDF Footer enter it here.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_footer_custom_text",
								"type" 	=> "text",
								"std" 	=> ""),
					
					array(  	"name" 	=> __('Set the Text Colour for the Footer','wpShop'),
								"desc" 	=> __('Colours can be expressed in ','wpShop')."<a target='_blank' href='http://www.colorpicker.com/'>".__('RGB components or gray scale.','wpShop')."</a>",
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_footer_txtColour",
								"type" 	=> "text",
								"std" 	=> "0,0,0"),
								
					array(  	"name" 	=> __('Set the Font Size for the Footer','wpShop'),
								"desc" 	=> __('','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_footer_fontSize",
								"type" 	=> "text",
								"std" 	=> "6"),
								
					array(  	"name" 	=> __('Border Around Footer?','wpShop'),
								"desc" 	=> __('Check this setting if you want to have a around the Shop\'s Address on the generated PDF','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_footer_Border",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(  	"name" 	=> __('Border Width','wpShop'),
								"desc" 	=> __('Set the Border width in mm. The default is 0.2 (mm)','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_footer_BorderWidth",
								"type" 	=> "text",
								"std" 	=> "0.2"),
								
					array(  	"name" 	=> __('Background Fill behind Footer?','wpShop'),
								"desc" 	=> __('Check this setting if you want to have a background colour behind the Shop\'s Address on the generated PDF','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_footer_bgdColour_enable",
								"type" 	=> "checkbox",
								"std" 	=> "false"),
								
					array(  	"name" 	=> __('Set the Background Colour for the Footer','wpShop'),
								"desc" 	=> __('Colours can be expressed in ','wpShop')."<a target='_blank' href='http://www.colorpicker.com/'>".__('RGB components or gray scale.','wpShop')."</a>",
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_footer_bgdColour",
								"type" 	=> "text",
								"std" 	=> "255,255,255"),
					
				array(   	"type" => "close"),
			array(   	"type" => "close"),
		
			//Generate PDF
			array ( 	"name" 	=> __('Generate PDF','wpShop'),
						"type" 	=> "title"),
						
				array(    	"type" 	=> "open"),
					
					array(    	"name" 	=> __('Your Customized PDF','wpShop'),
								"desc" 	=> "<a href='".get_option('siteurl')."/wp-content/themes/".get_option('template')."/design-your-pdf.php' target='_blank'>".__('Call test pdf page','wpShop')."</a><br/>".__('Want to see how you PDF now looks like? Use this link to generate a test PDF. Only remember to save first the settings you modified from above once, in order to see your changes!','wpShop')."<br/><strong>".__('How to use this setting:','wpShop')."</strong><br/>".__('1. Complete 1 test order (use Bank Transfer as it goes fast)','wpShop')."<br/>".__('2. Click the link you see above','wpShop')."<br/>".__('3. Follow the instructions you see on the page the link will take you.','wpShop'),
								"id" 	=> NULL,
								"std" 	=> NULL,
								"type" 	=> "text-link"),
				array(   	"type" => "close"),
			array(   	"type" => "close"),
			
			// Vouchers			
			array ( 	"name" 	=> __('PDF Vouchers','wpShop'),
						"type" 	=> "title"),
						
				array(    	"type" 	=> "open"),
				
					array(  	"name" 	=> __('PDF Voucher Format','wpShop'),
								"desc" 	=> __('','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pdfFormat",
								"std" 	=> "",
								"vals" 	=> array(__('International (A4)','wpShop')."|A4",__('American (Letter)','wpShop')."|Letter"),
								"type" 	=> "select2"),
										
					array(  	"name" 	=> __('PDF Voucher-Background Image','wpShop'),
								"desc" 	=> __('Enter the file name of the pdf voucher background image which you have uploaded in the "Vouchers" section.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_pdf_voucher_bg",
								"type" 	=> "text",
								"std" 	=> "sample_coupon_voucher_1.jpg"),
								
				array(   	"type" => "close"),
			array(   	"type" => "close"),
		array (		"type" 	=> "fieldset_end"),		

//###############################################################################################################
		//Tab 11 Sellers
		array (		"type" 	=> "fieldset_start",
					"class" =>"shop",
					"id" 	=>"sec_sellers_settings"),

			array ( 	"name" 	=> __('Sellers','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),

					array(  	"name" 	=> __('Indiv Seller Item_ID Prefix','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_sellers_indiv_item_id_prefix",
								"type" 	=> "text",
								"std" 	=> "LC"),

					array(  	"name" 	=> __('Prof Seller Item_ID Prefix','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_sellers_prof_item_id_prefix",
								"type" 	=> "text",
								"std" 	=> "PL"),

					array(  	"name" 	=> __('Admin Files Items per page','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_sellers_admin_files_items_per_page",
								"type" 	=> "text",
								"std" 	=> "10"),

					array(  	"name" 	=> __('Pricing Search Items per page','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_sellers_pricing_search_items_per_page",
								"type" 	=> "text",
								"std" 	=> "50"),

					array(  	"name" 	=> __('Rhino Support API Key','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_sellers_rhinosupport_api_key",
								"type" 	=> "text",
								"std" 	=> ""),

					array(  	"name" 	=> __('Sellers Tour Youtube Video URL','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_sellers_tour_youtube_video_url",
								"type" 	=> "text",
								"std" 	=> ""),

					array(  	"name" 	=> __('Excluded Selections','wpShop'),
								"desc" 	=> __('Selections you want to exclude.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_excluded_selections",
								"vals"  => 'selection',
								"type" 	=> "tax-list",
								"std" 	=> "false"),

				array(   	"type" => "close"),
			array(   	"type" => "close"),

			array ( 	"name" 	=> __('Categories','wpShop'),
						"type" 	=> "title"),
				array(    	"type" 	=> "open"),

					array(  	"name" 	=> __('Seller Excluded Categories','wpShop'),
								"desc" 	=> __('Excluded Seller Categories.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_excluded_sellers_categories",
								"vals"  => $shop_wp_cats,
								"type" 	=> "multi-categories",
								"std" 	=> "false"),

					array(    	"name" 	=> __('Sale Category','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_sale_category",
								"type" 	=> "category-select",
								"vals" 	=> $shop_wp_cats,
								"std" 	=> "Select a Category"), 

					array(    	"name" 	=> __('Women Category','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_women_category",
								"type" 	=> "category-select",
								"vals" 	=> $shop_wp_cats,
								"std" 	=> "Select a Category"), 

					array(    	"name" 	=> __('Women Bags Category','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_women_bags_category",
								"type" 	=> "category-select",
								"vals" 	=> $shop_wp_cats,
								"std" 	=> "Select a Category"), 

					array(    	"name" 	=> __('Women Shoes Category','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_women_shoes_category",
								"type" 	=> "category-select",
								"vals" 	=> $shop_wp_cats,
								"std" 	=> "Select a Category"), 

					array(    	"name" 	=> __('Women Watches Category','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_women_watches_category",
								"type" 	=> "category-select",
								"vals" 	=> $shop_wp_cats,
								"std" 	=> "Select a Category"), 

					array(    	"name" 	=> __('Women Sunglasses Category','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_women_sunglasses_category",
								"type" 	=> "category-select",
								"vals" 	=> $shop_wp_cats,
								"std" 	=> "Select a Category"), 

					array(    	"name" 	=> __('Women Jewelry Category','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_women_jewelry_category",
								"type" 	=> "category-select",
								"vals" 	=> $shop_wp_cats,
								"std" 	=> "Select a Category"), 

					array(    	"name" 	=> __('Women Accessories Category','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_women_accessories_category",
								"type" 	=> "category-select",
								"vals" 	=> $shop_wp_cats,
								"std" 	=> "Select a Category"), 

					array(    	"name" 	=> __('Women Clothes Category','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_women_clothes_category",
								"type" 	=> "category-select",
								"vals" 	=> $shop_wp_cats,
								"std" 	=> "Select a Category"), 

					array(    	"name" 	=> __('Women Limited Edition Category','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_women_limited_edition_category",
								"type" 	=> "category-select",
								"vals" 	=> $shop_wp_cats,
								"std" 	=> "Select a Category"), 

					array(    	"name" 	=> __('Men Category','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_men_category",
								"type" 	=> "category-select",
								"vals" 	=> $shop_wp_cats,
								"std" 	=> "Select a Category"), 

					array(    	"name" 	=> __('Men Bags Category','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_men_bags_category",
								"type" 	=> "category-select",
								"vals" 	=> $shop_wp_cats,
								"std" 	=> "Select a Category"), 

					array(    	"name" 	=> __('Men Shoes Category','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_men_shoes_category",
								"type" 	=> "category-select",
								"vals" 	=> $shop_wp_cats,
								"std" 	=> "Select a Category"), 

					array(    	"name" 	=> __('Men Watches Category','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_men_watches_category",
								"type" 	=> "category-select",
								"vals" 	=> $shop_wp_cats,
								"std" 	=> "Select a Category"), 

					array(    	"name" 	=> __('Men Sunglasses Category','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_men_sunglasses_category",
								"type" 	=> "category-select",
								"vals" 	=> $shop_wp_cats,
								"std" 	=> "Select a Category"), 

					array(    	"name" 	=> __('Men Jewelry Category','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_men_jewelry_category",
								"type" 	=> "category-select",
								"vals" 	=> $shop_wp_cats,
								"std" 	=> "Select a Category"), 

					array(    	"name" 	=> __('Men Accessories Category','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_men_accessories_category",
								"type" 	=> "category-select",
								"vals" 	=> $shop_wp_cats,
								"std" 	=> "Select a Category"), 

					array(    	"name" 	=> __('Men Clothes Category','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_men_clothes_category",
								"type" 	=> "category-select",
								"vals" 	=> $shop_wp_cats,
								"std" 	=> "Select a Category"), 

					array(    	"name" 	=> __('Men Limited Edition Category','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_men_limited_edition_category",
								"type" 	=> "category-select",
								"vals" 	=> $shop_wp_cats,
								"std" 	=> "Select a Category"), 

				array(   	"type" => "close"),
			array(   	"type" => "close"),

			array ( 	"name" 	=> __('Pages','wpShop'),
						"type" 	=> "title"),

				array(    	"type" 	=> "open"),

					array(    	"name" 	=> __('Professional Seller page','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_professional_seller_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

					array(    	"name" 	=> __('Professional Seller Form page','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_professional_seller_form_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

					array(    	"name" 	=> __('Profreseller Add Item page','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_profreseller_add_item_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

					array(    	"name" 	=> __('Profreseller Edit Item page','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_profreseller_edit_item_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

					array(    	"name" 	=> __('Profreseller My Items page','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_profreseller_my_items_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

					array(    	"name" 	=> __('Profreseller Summary page','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_profreseller_summary_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

					array(    	"name" 	=> __('Indvseller Add Item page','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_indvseller_add_item_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

					array(    	"name" 	=> __('Indvseller Edit Item page','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_indvseller_edit_item_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

					array(    	"name" 	=> __('Indvseller My Items page','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_indvseller_my_items_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

					array(    	"name" 	=> __('Indvseller My Info page','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_indvseller_my_info_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

					array(    	"name" 	=> __('Indvseller Summary page','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_sellers_summary_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

					array(    	"name" 	=> __('TLC Admin Files page','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_tlc_admin_files_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

					array(    	"name" 	=> __('Pricing Search page','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_pricing_search_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

					array(    	"name" 	=> __('Terms and Conditions page','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_terms_and_conditions_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

					array(    	"name" 	=> __('What Happens Next page','wpShop'),
								"desc" 	=> '',
								"id" 	=> $CONFIG_WPS['shortname']."_what_happens_next_page",
								"type" 	=> "pages",
								"vals" 	=> $wp_full_pages,
								"std" 	=> "Select a Page"),

				array(   	"type" => "close"),
			array(   	"type" => "close"),

			array ( 	"name" 	=> __('Email Settings','wpShop'),
						"type" 	=> "title"),

				array(    	"type" 	=> "open"),

					array(  	"name" 	=> __('Profseller Form Email','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_profseller_form_email",
								"type" 	=> "text",
								"std" 	=> "sell@theluxurycloset.com"),

					array(  	"name" 	=> __('Quotations Email From','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_sellers_quotations_email_from",
								"type" 	=> "text",
								"std" 	=> "sell@theluxurycloset.com"),

					array(  	"name" 	=> __('Sellers CC Email','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_sellers_cc_email",
								"type" 	=> "text",
								"std" 	=> "notifications@theluxurycloset.com"),

					array(  	"name" 	=> __('Profseller Order Email Subject','wpShop'),
								"desc" 	=> __('Order email will inform the profseller that an order with your item has been received.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_sellers_order_email_subject",
								"type" 	=> "text",
								"std" 	=> ""),

					array(    	"name" 	=> __('Profseller Order Email Format','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_sellers_order_email_format",
								"type" 	=> "pages",
								"vals"  => $email_formats,
								"desc" 	=> __('{ITEM_NAME}, {ITEM_ID}','wpShop'),
								"std" 	=> "Select a Format"),

					array(  	"name" 	=> __('Change Price Email Subject','wpShop'),
								"desc" 	=> __('Email will inform the individual seller that price was changed by TLC team.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_sellers_change_price_email_subject",
								"type" 	=> "text",
								"std" 	=> ""),
						
					array(    	"name" 	=> __('Change Price Email Format (single)','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_sellers_change_price_email_format",
								"type" 	=> "pages",
								"vals"  => $email_formats,
								"std" 	=> "Select a Format"),

					array(    	"name" 	=> __('Change Price Email Format (multiple)','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_sellers_change_price_multiple_email_format",
								"type" 	=> "pages",
								"vals"  => $email_formats,
								"std" 	=> "Select a Format"),

					array(  	"name" 	=> __('Completed Quotations Email Subject','wpShop'),
								"desc" 	=> __('Email will inform the individual seller that all quotations are completed.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_sellers_completed_quotations_email_subject",
								"type" 	=> "text",
								"std" 	=> ""),
						
					array(    	"name" 	=> __('Completed Quotations Email Format','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_sellers_completed_quotations_email_format",
								"type" 	=> "pages",
								"vals"  => $email_formats,
								"desc" 	=> __('{SELLER_NAME}, {SELLER_ITEMS_TABLE}','wpShop'),
								"std" 	=> "Select a Format"),

				array(   	"type" => "close"),
			array(   	"type" => "close"),

			array ( 	"name" 	=> __('Submission form Brands','wpShop'),
						"type" 	=> "title"),

				array(    	"type" 	=> "open"),

					array(  	"name" 	=> __('Brands','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_submission_form_brands",
								"std" 	=> "",
								"type" 	=> "submission_form_brands"),					

				array(   	"type" => "close"),
			array(   	"type" => "close"),
		array (		"type" 	=> "fieldset_end"),	

//###############################################################################################################
		//Tab 12 Texts
		array (		"type" 	=> "fieldset_start",
					"class" =>"shop",
					"id" 	=>"sec_texts"),

			array ( 	"name" 	=> __('Texts','wpShop'),
						"type" 	=> "title"),
							
				array(    	"type" 	=> "open"),

					array(  	"name" 	=> __('Terms and conditions text','wpShop'),
								"desc"  => __('Terms and conditions text on submit item form.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_sellers_terms_and_conditions_text",
								"type" 	=> "textarea",
								"std" 	=> ""),

					array(  	"name" 	=> __('No Quote message','wpShop'),
								"desc"  => __('Message is shown to seller if no quotation.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_sellers_no_quote_message",
								"type" 	=> "textarea",
								"std" 	=> ""),

				array(   	"type" => "close"),
			array(   	"type" => "close"),

			array ( 	"name" 	=> __('Checkout Pages','wpShop'),
						"type" 	=> "title"),
				array(    	"type" 	=> "open"),

					array(  	"name" 	=> __('3 Day Returns','wpShop'),
								"desc" 	=> __('Checkout 3 Day Returns popup text.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_checkout_3_day_returns_popup_text",
								"type" 	=> "textarea",
								"std" 	=> ""),

					array(  	"name" 	=> __('Authenticity Guarantee','wpShop'),
								"desc" 	=> __('Checkout Authenticity Guarantee popup text.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_checkout_authenticity_guarantee_popup_text",
								"type" 	=> "textarea",
								"std" 	=> ""),

					array(  	"name" 	=> __('Full Refunds','wpShop'),
								"desc" 	=> __('Checkout Full Refunds popup text.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_checkout_full_refunds_popup_text",
								"type" 	=> "textarea",
								"std" 	=> ""),

					array(  	"name" 	=> __('Pick up text','wpShop'),
								"desc" 	=> __('Text is shown for Pick up.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_checkout_pickup_text",
								"type" 	=> "textarea",
								"std" 	=> ""),

					array(  	"name" 	=> __('Delivery text','wpShop'),
								"desc" 	=> __('Text is shown for Delivery.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_checkout_delivery_text",
								"type" 	=> "textarea",
								"std" 	=> ""),

					array(  	"name" 	=> __('Delivery outside UAE text','wpShop'),
								"desc" 	=> __('Text is shown for Delivery.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_checkout_delivery_outside_uae_text",
								"type" 	=> "textarea",
								"std" 	=> ""),

					array(  	"name" 	=> __('Credit Card text','wpShop'),
								"desc" 	=> __('Text is shown for Credit Card payment.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_checkout_audi_text",
								"type" 	=> "textarea",
								"std" 	=> ""),

					array(  	"name" 	=> __('PayPal text','wpShop'),
								"desc" 	=> __('Text is shown for PayPal payment.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_checkout_paypal_text",
								"type" 	=> "textarea",
								"std" 	=> ""),

					array(  	"name" 	=> __('Cash On Location text','wpShop'),
								"desc" 	=> __('Text is shown for Pay On Location payment.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_checkout_cash_text",
								"type" 	=> "textarea",
								"std" 	=> ""),

					array(  	"name" 	=> __('Bank Transfer text','wpShop'),
								"desc" 	=> __('Text is shown for Bank Transfer payment.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_checkout_transfer_text",
								"type" 	=> "textarea",
								"std" 	=> ""),

					array(  	"name" 	=> __('Cash On Delivery text','wpShop'),
								"desc" 	=> __('Text is shown for Bank Transfer payment.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_checkout_cod_text",
								"type" 	=> "textarea",
								"std" 	=> ""),

				array(   	"type" => "close"),
			array(   	"type" => "close"),

			array ( 	"name" 	=> __('Installments','wpShop'),
						"type" 	=> "title"),
				array(    	"type" 	=> "open"),

					array(  	"name" 	=> __('Popup Heading','wpShop'),
								"desc" 	=> __('Heading text of popup. {PRODUCT_NAME}, {USD_AMOUNT}, {AED_AMOUNT}','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_layaway_popup_heading",
								"type" 	=> "text",
								"std" 	=> ""),

					array(  	"name" 	=> __('Popup Text','wpShop'),
								"desc" 	=> __('Text of popup. {PRODUCT_NAME}, {USD_AMOUNT}, {AED_AMOUNT}','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_layaway_popup_text",
								"type" 	=> "textarea",
								"std" 	=> ""),

					array(  	"name" 	=> __('Terms Text','wpShop'),
								"desc" 	=> __('Enter the terms text, your customers must agree to before continue.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_layaway_terms_text",
								"type" 	=> "textarea",
								"std" 	=> ""),

					array(  	"name" 	=> __('Payment Popup Text','wpShop'),
								"desc" 	=> __('Payment popup text for question mark.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_layaway_payment_popup_text",
								"type" 	=> "textarea",
								"std" 	=> ""),

				array(   	"type" => "close"),
			array(   	"type" => "close"),

		array (		"type" 	=> "fieldset_end"),	
//###############################################################################################################
	array (		"type" 	=> "section_end"),

/*** 
Google Settings
***/
array ( 	"name" 	=> __('Google / SEO','wpShop'),
			"type" 	=> "heading",
			"class" =>"google"),
					
	array (		"type" 	=> "section_start",
				"class" =>"hasadmintabs hasadmintabs3"),					
		// Tab 1 SEO
		array (		"type" 	=> "fieldset_start",
					"class" =>"google",
					"id" 	=>"sec_seo_settings"),

			array ( 	"name" 	=> __('SEO','wpShop'),
						"type" 	=> "title"),

				array (   	"type" 	=> "open"),
				
					array(  	"name" 	=> __('Meta Keywords for the Front Page','wpShop'),
								"desc" 	=> __('Set some Meta Keywords to describe your Site as a whole to be used when on the Frontpage.','wpShop')."<br/><b>".__('Make sure to seperate each Keyword with a comma like so: list,of,keywords','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_keywords",
								"type" 	=> "textarea",
								"std" 	=> ""),	
								
					array(  	"name" 	=> __('Meta Keywords for Single Product Posts','wpShop'),
								"desc" 	=> __('Check this box if you want to use WordPress Tags as Meta Keywords for your site.','wpShop')."<br/><b>".__('Please note that by checking this option you may not be able to use the NWS Shop by All-Purpose widget or tag your Regular Blog posts the usual way.','wpShop')."</b>",
								"id" 	=> $CONFIG_WPS['shortname']."_tags_as_keywords",
								"type" 	=> "checkbox",
								"std" 	=> "false"),								
												
				array(   	"type" => "close"),
			array(   	"type" => "close"),
		array (		"type" 	=> "fieldset_end"),	
		
		// Tab 2 Google Maps
		array (		"type" 	=> "fieldset_start",
					"class" =>"google",
					"id" 	=>"sec_googleMaps_settings"),

			array ( 	"name" 	=> __('Google Maps','wpShop'),
						"type" 	=> "title"),

				array (   	"type" 	=> "open"),
				
					array(  	"name" 	=> __('Google Maps Link','wpShop'),
								"desc" 	=> __('Enter the link for Google Maps here (should of course show location of your biz). Will only appear (on order confirmation page) if you give your customers the option to pick up their order from your shop.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_google_maps_link",
								"type" 	=> "textarea",
								"std" 	=> "http://www.google.com/maps?f=q&hl=de&geocode=&q=Platzl+9,80331+M%C3%BCnchen,+Germany&sll=37.0625,-95.677068&sspn=51.04407,78.75&ie=UTF8&ll=48.137683,11.57959&spn=0.678178,1.230469&z=10&iwloc=addr"),		
												
				array(   	"type" => "close"),
			array(   	"type" => "close"),
		array (		"type" 	=> "fieldset_end"),	
		
		// Tab 3 Google Analytics
		array (		"type" 	=> "fieldset_start",
					"class" =>"google",
					"id" 	=>"sec_googleAnalytics_settings"),

			array ( 	"name" 	=> __('Google Analytics','wpShop'),
						"type" 	=> "title"),

				array (   	"type" 	=> "open"),
		
					array(    	"name" 	=> __('Active','wpShop'),
								"desc" 	=> __('Activate your Google analytics here. The code will appear in the footer.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_google_analytics",
								"std" 	=> "not_active",
								"vals" 	=> array(__('Active','wpShop')."|active",__('Not active','wpShop')."|not_active"),
								"type" 	=> "select2"),							
												
					array(    	"name" 	=> __('Analytics Profile ID','wpShop'),
								"desc" 	=> __('Enter here the id of your analytics profile','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_google_analytics_id",
								"std" 	=> "UA-xxxxxx-01",
								"type" 	=> "text"),

					array(  	"name" 	=> __('Ecommerce Tracking','wpShop'),
								"desc" 	=> __('Check this box if you want to use Goggle Ecommerce Tracking Code.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_google_ecommerce_tracking",
								"type" 	=> "checkbox",
								"std" 	=> "false"),								

				array(   	"type" => "close"),
			array(   	"type" => "close"),
			
			array ( 	"name" 	=> __('Custom Tracking Code','wpShop'),
						"type" 	=> "title"),

				array (   	"type" 	=> "open"),
				
					array(  	"name" 	=> __('On Order Confirmation Page','wpShop'),
								"desc" 	=> __('If you want to track how many of your customers reach the confirmation page enter your tracking script here.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_custom_tracking",
								"type" 	=> "textarea",
								"std" 	=> ""),		
												
				array(   	"type" => "close"),
			array(   	"type" => "close"),
		array (		"type" 	=> "fieldset_end"),		

		// Tab 4 Google Adsense
		array (		"type" 	=> "fieldset_start",
					"class" =>"google",
					"id" 	=>"sec_googleAdsense_settings"),

			array ( 	"name" 	=> __('Google Adsense','wpShop'),
						"type" 	=> "title"),

				array (   	"type" 	=> "open"),
			
					array(  	"name" 	=> __('Ad 1','wpShop'),
								"desc" 	=> __('Enter the code for your 1st Google ad here. You still need to place the ','wpShop')."<b>google_adsense(1);</b>".__(' marker in the template file where you like it to appear. Remember that it needs to be wrapped in php tags','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_google_adsense_1",
								"type" 	=> "textarea",
								"std" 	=> ""),		
										
					array(  	"name" 	=> __('Ad 2','wpShop'),
								"desc" 	=> __('Enter the code for your 2nd Google ad here. You still need to place the ','wpShop')."<b>google_adsense(2);</b>".__(' marker in the template file where you like it to appear. Remember that it needs to be wrapped in php tags','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_google_adsense_2",
								"type" 	=> "textarea",
								"std" 	=> ""),	
										
					array(  	"name" 	=> __('Ad 3','wpShop'),
								"desc" 	=> __('Enter the code for your 3rd Google ad here. You still need to place the ','wpShop')."<b>google_adsense(3);</b>".__(' marker in the template file where you like it to appear. Remember that it needs to be wrapped in php tags','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_google_adsense_3",
								"type" 	=> "textarea",
								"std" 	=> ""),	
								
				array(   	"type" => "close"),
			array(   	"type" => "close"),
		array (		"type" 	=> "fieldset_end"),		
array (		"type" 	=> "section_end"),

/*** 
Support
***/
array ( 	"name" 	=> __('Support','wpShop'),
			"type" 	=> "heading",
			"class" =>"support"),
					
	array (		"type" 	=> "section_start",
				"class" =>"hasadmintabs hasadmintabs4"),					

		// Tab 1 support
		array (		"type" 	=> "fieldset_start",
					"class" =>"support",
					"id" 	=>"sec_support_settings"),

			array ( 	"name" 	=> __('Theme Support','wpShop'),
						"type" 	=> "title"),

				array (   	"type" 	=> "open"),
				
					array(    	"name" 	=> __('Support ID','wpShop'),
								"desc" 	=> __('Upon support request a support ID may be given to you. In this case you will enter the ID in the above field.','wpShop')."<br/>".__('Support emails must be send over our ','wpShop')."<a href='http://themeforest.net/user/srhnbr' target='_blank'>".__('Theme Forest profile ','wpShop')."</a><br/><strong>".__('Emails send differently will not be replied to.','wpShop')."</strong>",
								"id" 	=> $CONFIG_WPS['shortname']."_support_id",
								"std" 	=> "0",
								"type" 	=> "text"),					
	
					array(    	"name" 	=> __('Attribute Value Cleanup','wpShop'),
								"desc" 	=> "<a href='?page=functions.php&section=checks'>".__('Start this check now','wpShop')."</a><br/><strong>".__('Removes extra "pipes" (|) at the end of attribute values.','wpShop')."</strong>",
								"id" 	=> NULL,
								"std" 	=> NULL,
								"type" 	=> "text-link"),	
					
					array(    	"name" 	=> __('Dashboard Widget','wpShop'),
								"desc" 	=> __('','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_dash_widget",
								"std" 	=> "<p>Need help? <a target='_blank' href='http://themeforest.net/user/srhnbr'>Contact the developer</a></p>",
								"type" 	=> "textarea"),

					array(    	"name" 	=> __('Time addition','wpShop'),
								"desc" 	=> __('In case you like to balance out the time between your web server and your local time, use this field and add the time addition in seconds.','wpShop'),
								"id" 	=> $CONFIG_WPS['shortname']."_time_addition",
								"std" 	=> "32400",
								"type" 	=> "text"),
								
				array(   	"type" => "close"),
			array(   	"type" => "close"),
		array (		"type" 	=> "fieldset_end"),		
	array (		"type" 	=> "section_end"),	

);



function NWS_theme_admin()
{
    global $CONFIG_WPS,$wpdb,$options,$useSection,$install_status,$OPTION,$current_user,$voucher_errors;
	$section = str_replace('#',NULL,$_GET['section']); 	
	switch($section)
	{	
		case 'orders':				
			if($_GET['subsection'] == 'dlinks'){	send_user_dlinks($_GET['token']);	}
			if(isset($_POST['status_change'])){ multi_change_order_level();	}
			if(isset($_REQUEST['returned_order']) && $_REQUEST['returned_order'] == 'Save' ) returned_order();
			$table_header = "
				<table class='widefat orders-list' >
					<thead>
						<tr>
							<th>&nbsp;</th>
							<th>".__('Order No.','wpShop')."</th>
							<th>".__('Date','wpShop')."</th>
							<th>".__('Billing','wpShop')."</th>
							<th>".__('Delivery','wpShop')."</th>
							<th>".__('Total Value','wpShop')."</th>
							<th>".__('Details','wpShop')."</th>
							<th>".__('Invoice','wpShop')."</th>
							<th>".__('Payment and Delivery','wpShop')."</th>";
							if($OPTION['wps_customNote_enable'] == TRUE) {
								$table_header .= "<th>".__('Custom Note','wpShop')."</th>";
							}
						$table_header .= "</tr>
					</thead>
					<tbody>
				";	
			$table_footer 	= "</tbody></table>";				
			$empty_message 	= "<h4>".__('No orders with this status.','wpShop')."</h4>";
			$date_format	= "j.m.Y - G:i:s";

			$otab = $_GET['otab'];
			if (!strlen($otab)) { $otab = 'new'; }

			$oper_page = (int)$OPTION['wps_admin_orders_per_page'];
			$opg = $_GET['opg'];

			if (!$oper_page) { $oper_page = 20; }
			if (!$opg) { $opg = 1; }

			$olimit_start = ($opg - 1) * $oper_page;

			$olevel_vals = array('new' => 4, 'pending' => 8, 'shipped' => 5, 'received' => 6, 'completed' => 7, 'layaway' => 3, 'cancelled' => 0);

			// IN ('0','3','4','5','6','7','8')
			$table 	= is_dbtable_there('orders');
			$res 	= mysql_query(sprintf("SELECT SQL_CALC_FOUND_ROWS * FROM %s WHERE level = '%s' ORDER BY order_time DESC LIMIT %s, %s", $table, $olevel_vals[$otab], $olimit_start, $oper_page));
			$calc_rows_res 	= mysql_query("SELECT FOUND_ROWS() as total_rows");
			$calc_rows = mysql_fetch_assoc($calc_rows_res);
			$ototal = (int)$calc_rows['total_rows'];
			$opages = ceil($ototal / $oper_page);
			$odata = classify_orders($res);
			$pending_ord = $wpdb->get_var(sprintf("SELECT COUNT(oid) FROM %s WHERE level = '8'", $table));

			echo make_section_header('orders'); ?>
			<style>
			.nws_manage_orders fieldset { display:none; }
			.nws_manage_orders fieldset.active { display:block; }
			</style>
			<form class="nws_admin_options nws_manage_orders" action="themes.php?page=functions.php&section=orders&otab=<?php echo $otab; ?>" method="post">
				<div class="nws_manage_orders_tabs">
					<?php if ($pending_ord > 0) { ?>
						<fieldset id="pending" rel="admin.php?page=functions.php&section=orders&otab=pending"<?php if ($otab == 'pending') { echo ' class="active"'; } ?>>
							<?php 
							echo "<h3>".__('Status 0: Payment Pending','wpShop')."</h3>";
							display_order_entries($odata,5,$date_format,$table_header,$table_footer,$empty_message); 
							display_order_pagination($opages, 'pending');
							?>
						</fieldset>
					<?php } ?>
					<fieldset id="new" rel="admin.php?page=functions.php&section=orders&otab=new"<?php if ($otab == 'new') { echo ' class="active"'; } ?>>
						<?php 
						echo "<h3>".__('Status 1: Newly Orders','wpShop')."</h3>"; 
						display_order_entries($odata,1,$date_format,$table_header,$table_footer,$empty_message); 
						display_order_pagination($opages, 'new');
						?>
					</fieldset>
					<fieldset id="shipped" rel="admin.php?page=functions.php&section=orders&otab=shipped"<?php if ($otab == 'shipped') { echo ' class="active"'; } ?>>
						<?php 
						echo "<h3>".__('Status 2: Shipped Orders','wpShop')."</h3>"; 
						display_order_entries($odata,2,$date_format,$table_header,$table_footer,$empty_message); 
						display_order_pagination($opages, 'shipped');
						?>
					</fieldset>
					<fieldset id="received" rel="admin.php?page=functions.php&section=orders&otab=received"<?php if ($otab == 'received') { echo ' class="active"'; } ?>>
						<?php 
						echo "<h3>".__('Status 3: Payment Received','wpShop')."</h3>"; 
						display_order_entries($odata,3,$date_format,$table_header,$table_footer,$empty_message); 
						display_order_pagination($opages, 'received');
						?>
					</fieldset>
					<fieldset id="completed" rel="admin.php?page=functions.php&section=orders&otab=completed"<?php if ($otab == 'completed') { echo ' class="active"'; } ?>>
						<?php 
						echo "<h3>".__('Status 4: Completed Orders','wpShop')."</h3>"; 
						display_order_entries($odata,4,$date_format,$table_header,$table_footer,$empty_message); 
						display_order_pagination($opages, 'completed');
						?>
					</fieldset>
					<fieldset id="layaway" rel="admin.php?page=functions.php&section=orders&otab=layaway"<?php if ($otab == 'layaway') { echo ' class="active"'; } ?>>
						<?php 
						echo "<h3>".__('Layaway Orders','wpShop')."</h3>"; 
						display_order_entries($odata,6,$date_format,$table_header,$table_footer,$empty_message); 
						display_order_pagination($opages, 'layaway');
						?>
					</fieldset>
					<fieldset id="cancelled" rel="admin.php?page=functions.php&section=orders&otab=cancelled"<?php if ($otab == 'cancelled') { echo ' class="active"'; } ?>>
						<?php 
						echo "<h3>".__('Cancelled Orders','wpShop')."</h3>"; 
						display_order_entries($odata,7,$date_format,$table_header,$table_footer,$empty_message); 
						display_order_pagination($opages, 'cancelled');
						?>
					</fieldset>
					<div class="tablenav">
						<label><?php _e('Bulk Actions:','wpShop'); ?></label>
						<select name="status" class="order-status-act" onchange="status_act()">
							<option value='4'><?php _e('Newly Orders','wpShop'); ?></option>
							<option value='5'><?php _e('Shipped Orders','wpShop'); ?></option>
							<option value='6'><?php _e('Payment Received','wpShop'); ?></option>
							<option value='7'><?php _e('Completed Orders','wpShop'); ?></option>
							<?php if ($_GET['otab'] != 'cancelled') { ?>
							<option value='0'><?php _e('Cancelled Orders','wpShop'); ?></option>
							<option class="error" value='delete'><?php _e('Delete','wpShop'); ?></option>
							<?php } ?>
						</select>
						<?php $order_cancel_reasons = get_order_cancel_reasons(); ?>
						<select name="cancel_reason" class="order-cancel-reason" style="width:175px; display:none;">
							<option value="">-- <?php _e('Select Cancel Reason','wpShop'); ?> --</option>
							<?php foreach($order_cancel_reasons as $creason) { ?>
							<option value="<?php echo $creason; ?>"><?php echo $creason; ?></option>
							<?php } ?>
						</select>
						<input type="submit" name="status_change" value="<?php _e('Apply','wpShop'); ?>" onclick="admin_change_orders_status();" />
					</div>
					<?php if ($current_user->ID == 1 || $current_user->ID == 6871) { ?><div class="export-csv" style="padding:10px 0px 0px;"><a href="admin.php?page=functions.php&section=orders&csvexport=orders">Export CSV</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="admin.php?page=functions.php&section=orders&csvexport=orders&otype=cancelled">Cancelled Export CSV</a></div><?php } ?>
				</div><!-- hasadmintabs -->
			</form>
			<?php echo make_section_footer();
			if($odata[1] > 0) 
			{
			?>
			<div class="popup_form" id="popup_form">  
			   <div class="popup_title">
					<div id="TB_ajaxWindowTitle">Returned this Order #number?</div>
					<a id="popup_close"><img src="<?=get_bloginfo('template_directory')?>/images/closebox.png" title="Close" alt="Close" /></a>
				</div>
				<div class="popup_forms">  
				<form name="return_orders" action="themes.php?page=functions.php&section=orders" method="post">
				<input type="hidden" name="pop_rt_order_id" id="pop_rt_order_id" value="" />
				<input type="hidden" name="pop_rt_txn_id" id="pop_rt_txn_id" value="" />
				<table id="popup_rt_data" class="widefat" width="100%" border="0">
					<thead>
						<tr>
							<th width="15%">ID</th>
							<th width="51%">Item</th>
							<th width="17%" align="center">Total Qty</th>
							<th width="17%">Returned Qty</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="pop_item_id">Id</td>
							<td class="pop_item_name"><b>Name</b> <br>  </td>
							<td class="pop_item_qty">qty</td>
							<td class="pop_item_rqty"><input type="text" name="pop_item_rtqty_" class="pop_item_rtqty" value="0" /></td>	
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="2" align="right">
							<?php $order_cancel_reasons = get_order_cancel_reasons(); ?>
							<select name="cancel_reason" style="width:175px;">
								<option value="">-- <?php _e('Select Cancel Reason','wpShop'); ?> --</option>
								<?php foreach($order_cancel_reasons as $creason) { ?>
								<option value="<?php echo $creason; ?>"><?php echo $creason; ?></option>
								<?php } ?>
							</select>
							</td>
							<td><input type="submit" class="button" name="returned_order" value="Save" onclick="admin_ga_refund_event(document.return_orders.pop_rt_txn_id.value);" /></td>
							<td><input type="button" class="button" id="cancel" name="returned_order" value="Cancel"  /></td>
						</tr>
					</tfoot>
				</table>
				</form>
				</div>
			</div>
			<div id="popup_bg"></div>
			<?php	add_action('admin_footer', 'order_return_popup_script');
			}			
		break;
		case 'inquiries':
			if(isset($_POST['status_change'])){			multi_change_inquiry_level();	}
			$odata =  classify_inquiries();
			$table_header = "
			<table border='1' class='widefat' >
				<thead>
					<tr>
						<th>".__('Order','wpShop')."</th>
						<th>".__('No.','wpShop')."</th>
						<th>".__('Date','wpShop')."</th>
						<th>".__('Who?','wpShop')."</th>
						<th>".__('Billing','wpShop')."</th>
						<th>".__('Delivery','wpShop')."</th>
						<th>".__('Total Value','wpShop')."</th>
						<th>".__('Details','wpShop')."</th>
						<th>".__('Preferred Payment and Delivery','wpShop')."</th>
						<th>".__('Custom Note','wpShop')."</th>
					</tr>
				</thead>
				<tbody>
			";
			$table_footer 	= "</tbody></table>";				
			$empty_message 	= "<h4>".__('No inquiries with this status.','wpShop')."</h4>";
			$date_format	= "j.m.Y - G:i:s";
			echo make_section_header('inquiries'); ?>
			<form class="nws_admin_options nws_manage_inquiries" action="themes.php?page=functions.php&section=inquiries" method="post">
				<div class="hasadmintabs hasadmintabs1">
					<fieldset id="new">
						<?php 
						echo "<h3 class='new'>".__('Status 1: Newly Received','wpShop')."</h3>"; 
						display_inquiry_entries($odata,1,$date_format,$table_header,$table_footer,$empty_message); 
						?>
					</fieldset>
					<fieldset id="replied">
						<?php 
						echo "<h3>".__('Status 2: Already Replied To','wpShop')."</h3>"; 
						display_inquiry_entries($odata,2,$date_format,$table_header,$table_footer,$empty_message); 
						?>
					</fieldset>
					<div class="tablenav">
						<label><?php _e('Bulk Actions:','wpShop'); ?></label> 
						<select name='status' size='1'>
							<option value='4'><?php _e('Newly Received','wpShop'); ?></option>
							<option value='5'><?php _e('Replied To','wpShop'); ?></option>
							<option class="error" value='delete'><?php _e('Delete','wpShop'); ?></option>
						</select>
						<input type='submit' name='status_change' value='<?php _e('Apply','wpShop'); ?>' />
					</div>
				</div><!-- hasadmintabs -->
			</form>
			<?php echo make_section_footer();
		break;
		case 'inventory':
			if($_GET['update'] == '1'){ inventory_amount_update(); }
			if($_GET['clean_inventory'] == '1'){ clean_inventory(); }
			// lets get our inventory in order / updated aso.
			if($_GET['enigma'] == '1')
			{	
				# 1. Attributes-Check 						
				adapt_inventory2attributes();	// adds missing attr. cols to inventory table
				# 2. Article-Check / is article there at all
					// = means: are all the articles with all possible attr. combos saved?					
				inventory_article_check();	
			}
			//$items_on_sale = $wpdb->get_var(sprintf("SELECT COUNT(iid) FROM %swps_inventory WHERE amount > 0", $wpdb->prefix));
			$items_on_sale = $wpdb->get_var(sprintf("SELECT COUNT(ID) FROM %sposts WHERE post_type = 'post' AND post_status = 'publish' AND inventory = 1", $wpdb->prefix));
			echo make_section_header('inventory'); ?>
			<div class="tablenav" style="margin-bottom:25px;">
				<div class="alignleft actions">
					<?php
						echo "
						<a id='nws_inv_return' class='button-secondary action' href='?page=functions.php&section=inventory&clean_inventory=1' title='".__('Returns Products from "abandoned" Carts.','wpShop')."'>".__('Return Stock','wpShop')."</a>
						<a id='nws_inv_refresh' class='button-secondary action' href='?page=functions.php&section=inventory&enigma=1' title='".__('Refresh if you have added a New Product and/or Attribute (Product Variation)','wpShop')."'>".__('Refresh List','wpShop')."</a>
						<form class='nws_search nws_inv_search' action='?page=functions.php&section=inventory' method='get'>
							<input type='hidden' name='page' value='functions.php' />
							<input type='hidden' name='section' value='inventory' />
							<input type='text' name='art_wanted' value='$_GET[art_wanted]' maxlength='255' />
							<input class='button-secondary action' type='submit' name='search_inv' value='Search' /><br/>
							<small>".__('Enter at least the first 3 digits of a Product ID_item','wpShop')."</small>
						</form>
						";
					?>	
				</div>
				<div class="alignright" style="padding-top:10px;">
					<strong>Total Items on sale: <?php echo $items_on_sale; ?>&nbsp;</strong>
				</div>
			</div>
			<?php 
			// get all articles				
			echo "<div id='nws_inv_wrap'>";
				$res 		= inventory_main_query(pagination_limit_clause(20));
				while($row = mysql_fetch_assoc($res))
				{						
				$show_not1 = (strlen(get_custom_field2($row['post_id'],'item_file')) > 1 ? 1 : 0);	
				$show_not2 = (strlen(get_custom_field2($row['post_id'],'buy_now')) > 1 ? 1 : 0);	
					if(($show_not1 == 0)&&($show_not2 == 0)){										
						$product_title 	= $row['post_title'];		
						$product_image	= inventory_product_image($row['post_id']);
						echo "
						<form class='nws_inv_prod' action='?page=functions.php&section=inventory&update=1' method='post' style='border: 1px solid gainsboro;'>
							<h4 class='nws_inv_prod_ID'>".__('Article No.','wpShop').": $row[meta_value]</h4> <input class='nws_inv_prod_update' type='submit' name='submit_this' value='".__('update','wpShop')."' />
							<div class='nws_inv_details'>
								<p class='nws_inv_prod_title'>$product_title";
									if(strlen($product_image) > 1){
										echo " | <a href='$product_image' title='$product_title' class='thickbox'><img src='images/media-button-image.gif' alt='".__('Product Image','wpShop')."' /></a>";
									}
								echo "</p>";								
								if(inventory_has_attributs($row[post_id]) != 0){	// attributes - yes/no
									if($row_head !== FALSE){ // the array is not empty
										echo "<table>";
										echo header_for_attributes($row[meta_value]);
										echo display_attributes_data($row[meta_value],inventory_order_clause());									
										echo "</table>";
									}
									else { echo __('Please refresh your inventory!','wpShop');}
								}
								else {
									$check_inv_amount = $wpdb->get_var(sprintf("SELECT COUNT(iid) FROM %swps_inventory WHERE ID_item = '%s'", $wpdb->prefix, $row[meta_value]));
									if ($check_inv_amount == 0) {
										$insert = array();
										$insert["Size"] = '';
										$insert["Material"] = '';
										$insert["Colour"] = '';
										$insert["Duration"] = '';
										$insert["ID_item"] = $row['meta_value'];
										$insert["amount"] = '0';
										$wpdb->insert($wpdb->prefix."wps_inventory", $insert);
									}
									$res2 = display_amount($row[meta_value]);
									echo "<label>".__('Amount: ','wpShop')."</label>";
									while($row2 = mysql_fetch_assoc($res2)){								
										echo "<input type='text' name='$row2[iid]' value='$row2[amount]' />";
									}
								}
							echo "</div>
						</form>
						";
					}
				}
			echo "</div>"; ?>
			<div class="tablenav">
				<div class="tablenav-pages" style="float:none;">
					<?php NWS_inventory_pagination(20); ?>	
				</div>
			</div>
			<?php
			if ($current_user->ID == 1 || $current_user->ID == 6871) { echo '<div class="export-csv" style="padding:10px 0px 0px;"><a href="admin.php?page=functions.php&section=inventory&csvexport=inventory">Export CSV</a></div>'; }
			echo make_section_footer();	
		break;
		case 'lkeys':
			$upload_result = NULL;
			if($_GET[action] == 'upload'){						
				$upload_result = save_lkeys();						
			}  
			echo make_section_header('lkeys');
			echo $upload_result;
			echo "	
			<form action='?page=functions.php&section=lkeys&action=upload' enctype='multipart/form-data' method='post'>
				<h3>".__('License Key Uploader','wpShop')."</h3>
				<fieldset>
					<table>
						<tr>
							<td>".__('Name of Download File','wpShop')."</td>
							<td><input name='name_file' type='text' maxlength='255'/></td>
						</tr>
						<tr>
						<tr>
							<td>".__('File with License Keys','wpShop')."</td>
							<td><input name='csvfile' type='file' /></td>
						</tr>
						<tr>
							<td colspan='2'><small>".__('[Please keep in mind: It has to be a .txt file - Size: not more than 100kB.]','wpShop')."</small></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><input type='submit' value='Submit' /></td>
						</tr>
						<tr>
							<td colspan='2'>&nbsp</td>
						</tr>
					</table>
				</fieldset>
			</form>
			";
			echo  make_section_footer();
		break;
		case 'vouchers':
			$vtypes = array('1' => 'Single-Use', '2' => 'Multi-Use');
			$voptions = array('P' => 'Percentage, %', 'A' => 'Fixed amount, $');
			echo make_section_header('vouchers'); ?>
			<?php if (strlen($voucher_errors)) { ?><div style="color:#FF0000;padding-bottom:10px;"><?php echo $voucher_errors; ?></div><?php } ?>
			<table>
				<tr>
					<td><h3 style="margin:0 0 5px 0;">Create Voucher Code</h3></td>
					<td width="50">&nbsp;</td>
					<td><h3 style="margin:0 0 5px 0;">Vouchers CSV Upload </h3></td>
				</tr>
				<tr>
					<td valign="top">
						<form action="admin.php?page=functions.php&section=vouchers&voucher_action=create" method="POST">
						<table>
							<tr>
								<td>Voucher Code:</td>
								<td><input type="text" name="voucher_code" maxlength="100" style="width:247px;" value="<?php echo $_POST['voucher_code']; ?>" /></td>
							</tr>
							<tr>
								<td>Voucher Type:</td>
								<td>
									<select name="voucher_type" style="width:247px;">
										<?php foreach($vtypes as $vtkey => $vtname) { $s = ''; if ($vtkey == $_POST['voucher_type']) { $s = ' SELECTED'; } ?>
										<option value="<?php echo $vtkey; ?>"<?php echo $s; ?>><?php echo $vtname; ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td>Voucher Option:</td>
								<td>
									<select name="voucher_option" style="width:247px;">
										<?php foreach($voptions as $vokey => $voname) { $s = ''; if ($vokey == $_POST['voucher_option']) { $s = ' SELECTED'; } ?>
										<option value="<?php echo $vokey; ?>"<?php echo $s; ?>><?php echo $voname; ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td>Amount:</td>
								<td><input type="text" name="voucher_amount" style="width:247px;" value="<?php echo $_POST['voucher_amount']; ?>" /></td>
							</tr>
							<tr>
								<td>Expired Date:</td>
								<td>
									<select name="voucher_expired_dd" style="padding:1px;width:42px;height:26px;">
										<option value="">D</option>
										<?php for($d=1; $d<=31; $d++) { $dv = sprintf("%02d", $d); $s = ''; if ($d == $_POST['voucher_expired_dd']) { $s = ' SELECTED'; } ?>
										<option value="<?php echo $dv; ?>"<?php echo $s; ?>><?php echo $dv; ?></option>
										<?php } ?>
									</select>
									<select name="voucher_expired_mm" style="padding:1px;width:42px;height:26px;">
										<option value="">M</option>
										<?php for($m=1; $m<=12; $m++) { $mv = sprintf("%02d", $m); $s = ''; if ($m == $_POST['voucher_expired_mm']) { $s = ' SELECTED'; } ?>
										<option value="<?php echo $mv; ?>"<?php echo $s; ?>><?php echo $mv; ?></option>
										<?php } ?>
									</select>
									<select name="voucher_expired_yy" style="padding:1px;width:60px;height:26px;">
										<option value="">YYYY</option>
										<?php $cy = (int)date("Y"); for($y=$cy; $y<=$cy+5; $y++) { $s = ''; if ($y == $_POST['voucher_expired_yy']) { $s = ' SELECTED'; } ?>
										<option value="<?php echo $y; ?>"<?php echo $s; ?>><?php echo $y; ?></option>
										<?php } ?>
									</select>
									<select name="voucher_expired_hh" style="padding:1px;width:42px;height:26px;">
										<option value="">H</option>
										<?php for($h=0; $h<=23; $h++) { $hv = sprintf("%02d", $h); $s = ''; if ($h == $_POST['voucher_expired_hh']) { $s = ' SELECTED'; } ?>
										<option value="<?php echo $hv; ?>"<?php echo $s; ?>><?php echo $hv; ?></option>
										<?php } ?>
									</select>
									<select name="voucher_expired_ii" style="padding:1px;width:42px;height:26px;">
										<option value="">I</option>
										<?php for($i=0; $i<60; $i=$i+15) { $iv = sprintf("%02d", $i); $s = ''; if ($i == $_POST['voucher_expired_ii']) { $s = ' SELECTED'; } ?>
										<option value="<?php echo $iv; ?>"<?php echo $s; ?>><?php echo $iv; ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td>Shipping Zone:</td>
								<td>
									<select name="voucher_zone" style="width:247px;">
										<option value="">-- None --</option>
										<?php for($z=1; $z<=6; $z++) { $s = ''; if ($z == $_POST['voucher_zone']) { $s = ' SELECTED'; } ?>
										<option value="<?php echo $z; ?>"<?php echo $s; ?>>Zone <?php echo $z; ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td align="right"><input type="submit" name="save_vcode" value="&nbsp;&nbsp;Save&nbsp;&nbsp;" /></td>
							</tr>
						</table>
						</form>
					</td>
					<td>&nbsp;</td>
					<td valign="top">
						<form action="admin.php?page=functions.php&section=vouchers&voucher_action=upload" method="POST" enctype="multipart/form-data">
						<table>
							<tr>
								<td>CSV File:</td>
								<td><input type="file" name="vouchers_csv"></td>
								<td><input type="submit" name="save_vcode" value="&nbsp;&nbsp;Upload&nbsp;&nbsp;" /></td>
							</tr>
							<?php if ($_GET['vuploaded']) { ?>
								<tr>
									<td colspan="3" style="color:#336600;">Uploaded <?php echo $_GET['vuploaded']; ?> new voucher(s).</td>
								</tr>
							<?php } ?>
						</table>
						</form>
					</td>
				</tr>
			</table>
			<?php
			$opvals = array('A' => 'Amount', 'P' => 'Percent');
			$vper_page = 20;
			$vpage = $_GET['vpage'];
			if (!$vpage) { $vpage = 1; }
			$vstart = ($vpage - 1) * $vper_page;

			$where = "";
			$voucher_search = trim($_GET['voucher_search']);
			if (strlen($voucher_search)) {
				$where = " WHERE code LIKE '".$voucher_search."%'";
			}
			$vouchers = $wpdb->get_results(sprintf("SELECT SQL_CALC_FOUND_ROWS * FROM %swps_vouchers %s ORDER BY code LIMIT %s, %s", $wpdb->prefix, $where, $vstart, $vper_page));
			$vouchers_total = $wpdb->get_var("SELECT FOUND_ROWS()");
			$vouchers_total_pages = ceil($vouchers_total / $vper_page);
			?>
			<h3>Voucher Codes List</h3>
			<div class="tablenav">
				<div class="alignleft actions">
					<form action="admin.php" class="nws_search nws_voucher_search">
					<input type="hidden" name="page" value="functions.php">
					<input type="hidden" name="section" value="vouchers">
					<input type="text" maxlength="100" name="voucher_search" value="<?php echo $voucher_search; ?>">
					<input type="submit" value="Search" class="button-secondary action"><br><small>Enter a Voucher Code</small>
					</form>
				</div>
			</div>
			<table class="widefat">
				<tr>
					<th style="border-bottom:1px solid #E4E5E5;">Voucher Code</th>
					<th style="border-bottom:1px solid #E4E5E5;">Type</th>
					<th style="border-bottom:1px solid #E4E5E5;">Option</th>
					<th style="border-bottom:1px solid #E4E5E5;">Amount</th>
					<th style="border-bottom:1px solid #E4E5E5;">Expired</th>
					<th style="border-bottom:1px solid #E4E5E5;">Zone</th>
					<th style="border-bottom:1px solid #E4E5E5;text-align:center;">Used</th>
					<th style="border-bottom:1px solid #E4E5E5;width:50px;">Remove</th>
				</tr>
				<?php if ($vouchers) {
					foreach($vouchers as $voucher) {
						$voucher_expired = '';
						if (strlen($voucher->expired) && $voucher->expired != '0000-00-00 00:00:00') { $voucher_expired = date("d.m.Y H:i", strtotime($voucher->expired)); } ?>
						<tr>
							<td><?php echo $voucher->code; ?></td>
							<td><?php echo $vtypes[$voucher->type]; ?></td>
							<td><?php echo $opvals[$voucher->option]; ?></td>
							<td><?php echo $voucher->amount; ?></td>
							<td><?php echo $voucher_expired; ?></td>
							<td><?php if ($voucher->zone > 0) { echo 'Zone '.$voucher->zone; } else { echo '&nbsp;'; } ?></td>
							<td align="center"><?php echo $voucher->used; ?></td>
							<td><a href="admin.php?page=functions.php&section=vouchers&voucher_action=remove&vid=<?php echo $voucher->vid; ?>">remove</a></td>
						</tr>
					<?php } ?>
				<?php } else { ?>
					<tr><td colspan="8">No found vouchers.</td></tr>
				<?php } ?>
			</table>
			<?php if ($vouchers_total_pages > 1) { ?>
			<div class="tablenav">
				<div class="tablenav-pages">
					<a style="text-decoration:none;" href="?page=functions.php&amp;section=vouchers&amp;voucher_search=<?php echo $voucher_search; ?>&amp;vpage=<?php echo ($vpage - 1); ?>" class="prev<?php if ($vpage == 1) { echo ' disabled'; } ?>">&laquo;</a>
					<?php for($p=1; $p<=$vouchers_total_pages; $p++) { ?>
						<?php if ($p == $vpage) { ?>
							<span class="page-numbers current"><?php echo $vpage; ?></span>
						<?php } else { ?>
							<a href="admin.php?page=functions.php&amp;section=vouchers&amp;voucher_search=<?php echo $voucher_search; ?>&amp;vpage=<?php echo $p; ?>" class="page-numbers"><?php echo $p; ?></a>
						<?php } ?>
					<?php } ?>
					<a style="text-decoration:none;" href="admin.php?page=functions.php&amp;section=vouchers&amp;voucher_search=<?php echo $voucher_search; ?>&amp;vpage=<?php echo ($vpage + 1); ?>" class="next<?php if ($vpage >= $vouchers_total_pages) { echo ' disabled'; } ?>">&raquo;</a>
				</div>
			</div>
			<?php } ?>
			<?php
			echo  make_section_footer();
		break;
		
		case 'members':
			echo make_section_header('members'); ?>
			<h3><?php _e('Registered Customers','wpShop')?></h3>
			<div class="tablenav">
				<div class="alignleft actions">
					<?php
						echo "
						<form class='nws_search nws_memb_search' action='?page=functions.php&section=members' method='get'>
							<input type='hidden' name='page' value='functions.php' />
							<input type='hidden' name='section' value='members' />
							<input type='text' name='memb_wanted' value='$_GET[memb_wanted]' maxlength='255' />
							<input class='button-secondary action' type='submit' name='search_memb' value='Search' /><br/>
							<small>".__('Enter a Last Name','wpShop')."</small>
						</form>
						";
					?>	
				</div>
				<div class='tablenav-pages'>
					<?php NWS_members_pagination(20); ?>	
				</div>
			</div>
			
			<?php switch($_GET['action']){
				case 'del':
				
					if($_GET['confirmed'] == 'yes'){
						member_delete($_GET[uid]);	
						$url = "themes.php?page=functions.php&section=members";
						echo "<meta http-equiv='refresh' content='0; URL=$url'>";
					}
					else{
						echo "
						<div class='warning confirm_delete'>
							<p>
								".__('Are you sure that you want to delete this member?','wpShop')." 
								<a href='?page=functions.php&section=members&action=del&uid={$_GET[vid]}&confirmed=yes'>".__('Yes','wpShop')."</a> |
								<a href='?page=functions.php&section=members'>".__('No','wpShop')."</a>
							</p>
						</div>";
					}			
				break;
			}
		
			echo display_members(20);
			echo make_section_footer();
		break;

		case 'statistics':
			provide_statistics('Statistics','orange','25px','black');
			display_tax_data_backend();
		break;
				
				
		case 'checks':
			attributesDataChecker(1);
			echo "<div id='message' class='updates_saved'><p><strong>".__('Cleanup Successfully Completed','wpShop')."</p></div>
			<p><a class='button-secondary action' href='admin.php?page=functions.php'>".__('Return','wpShop')."</a></p>";
		break;


		default: ?>
			<div class="wrap">
				<?php if ($_REQUEST['saved']){
					echo '<div id="message" class="updates_saved"><p><strong>'.$CONFIG_WPS['themename'].' settings saved.</strong></p></div>';
				}
				
				if($install_status > 7){ 
					//if the theme is installed then create the main tabs 
					?>
					<ul class="tabs mainTabs"> 
						<?php 
						
						echo "<li><a class='active' href='#'>".__('Theme Options','wpShop')."</a></li>";
						//using the shopping cart?
						if($OPTION['wps_shoppingCartEngine_yes']) {
							if($OPTION['wps_shop_mode'] == 'Normal shop mode'){ 
								echo "<li><a href='?page=functions.php&section=orders'>".__('Manage Orders','wpShop')."</a></li>";
							}elseif($OPTION['wps_shop_mode'] == 'Inquiry email mode'){
								echo "<li><a href='?page=functions.php&section=inquiries'>".__('Manage Enquiries','wpShop')."</a></li>";
							}elseif($OPTION['wps_shop_mode'] == 'payloadz_mode'){
								echo "<li><a href='https://www.payloadz.com/' target=_blank>".__('PayLoadz','wpShop')."</a></li>";
							}else {}
						
							// tracking inventory?
							if($OPTION['wps_track_inventory']=='active'){
								echo "<li><a href='?page=functions.php&section=inventory'>".__('Manage Inventory','wpShop')."</a></li>";
							}
							//using License Keys?
							$l_mode = $OPTION['wps_l_mode'];
							if(($l_mode == 'GIVE_KEYS')&&($OPTION['wps_shop_mode'] != 'payloadz_mode')&&($OPTION['wps_shop_mode'] != 'Inquiry email mode')){ 
								echo "<li><a href='?page=functions.php&section=lkeys'>".__('Upload L-Keys','wpShop')."</a></li>";
							}
							// using vouchers?
							if ($OPTION['wps_voucherCodes_enable']) {
								echo "<li><a href='?page=functions.php&section=vouchers'>".__('Vouchers','wpShop')."</a></li>";
							}
						}
						//using a membership area?
						if($OPTION['wps_lrw_yes']) {
							echo "<li><a href='?page=functions.php&section=members'>".__('Members','wpShop')."</a></li>";
						}
						
						//using the shopping cart?
						if($OPTION['wps_shoppingCartEngine_yes']) {
							echo "<li><a href='?page=functions.php&section=statistics'>".__('Statistics','wpShop')."</a></li>";
						}
						echo "<li><a href='?page=functions.php&section=pricing'>".__('Pricing','wpShop')."</a></li>";
						echo "<li><a href='?page=functions.php&section=searches'>".__('Searches','wpShop')."</a></li>";
						echo "<li><a href='?page=functions.php&section=logs'>".__('Logs','wpShop')."</a></li>";
						
						?>
					</ul>
						
				<?php } else {
					// install status below 8 = we install
					
					include '../wp-content/themes/'.WPSHOP_THEME_NAME.'/lib/engine/install_actions.php';
					
					$FS_INSTALL 		= new folioShopInstall();
					
					$install_step_info 	= $install_status + 1;
					echo $FS_INSTALL->install_wizard_header($install_step_info);	
					
					$shopID 		= shop_cat($OPTION['wps_shop_slug']);
					$featuredID 	= featured_cat($OPTION['wps_featured_slug']);
					$install_fb 	= $FS_INSTALL->theme_install($install_status,$shopID,$featuredID);
					
					if($install_fb == 3){
						$install_status = 3;
					}
				}

				if($install_status == 8){ 
				?>
					<form method="post" action="themes.php?page=functions.php" class="nws_admin_options nws_admin_settings">
						<?php foreach ($options as $value) {

							switch ( $value['type'] ) {

								case "open":?>
									<table width="100%" border="0" style="background-color:#f9f9f9; padding:10px;">
								<?php break;

								case "close":?>
									</table><br />
								<?php break;
								
								case "heading": ?>
									<h2 class="<?php echo $value['class']; ?>" style="font-family:Georgia,'Times New Roman',Times,serif;"><?php echo __($value['name'], 'wpShop'); ?></h2>
								<?php break;
								
								case "section_start": ?>
									<div class="<?php echo $value['class'] ?>">
								<?php break;

								case "section_end": ?>
									</div>
								<?php break;
								
								case "fieldset_start": ?>
									<fieldset class="<?php echo $value['class'] ?>" id="<?php echo $value['id'] ?>">
								<?php break;

								case "fieldset_end": ?>
									</fieldset>
								<?php break;

								case "title": ?>
									<table id="<?php echo $value['id']; ?>" width="100%" border="0" style="background-color:#ececec; padding:5px 10px;">
									<tr>
										<td colspan="2"><h3 style="font-family:Georgia,'Times New Roman',Times,serif;"><?php echo __($value['name'], 'wpShop'); ?></h3></td>
									</tr>
								<?php break;

								case 'text':
								?>
									<tr>
										<td width="20%" rowspan="2" valign="middle"><strong><?php echo __($value['name'], 'wpShop'); ?></strong></td>
										<td width="80%"><input style="width:400px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo stripslashes(get_settings( $value['id'] )); } else { echo stripslashes($value['std']); } ?>" /></td>
									</tr>

									<tr>
										<td><small><?php echo $value['desc']; ?></small></td>
									</tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px solid #DFDFDF;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>
								<?php
								break;
								
								case 'text-link': ?>
									<tr>
										<td width="20%" valign="middle"><strong><?php echo __($value['name'], 'wpShop'); ?></strong></td>
										<td width="80%"><small><?php echo $value['desc']; ?></small></td>
									</tr>
									<tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px solid #DFDFDF;">&nbsp;</td></tr>
									<tr><td colspan="2">&nbsp;</td></tr>
								<?php break;
								
								case 'text_invisible': ?>
									<tr>
										<td width="20%" rowspan="2" valign="middle"><strong><?php echo __($value['name'], 'wpShop'); ?></strong></td>
										<td width="80%">&nbsp;</td>
									</tr>

									<tr>
										<td><small><?php echo $value['desc']; ?></small></td>
									</tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px solid #DFDFDF;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>
								<?php break;

								case 'textarea': 
								?>
									<tr>
										<td width="20%" rowspan="2" valign="middle"><strong><?php echo __($value['name'], 'wpShop'); ?></strong></td>
										<td width="80%"><textarea name="<?php echo $value['id']; ?>" style="width:400px; height:200px;" type="<?php echo $value['type']; ?>" cols="" rows=""><?php if(get_settings( $value['id'] ) != "") { echo stripslashes(get_settings( $value['id'])); } else { echo stripslashes($value['std']); } ?></textarea></td>
									</tr>
									<tr>
										<td><small><?php echo $value['desc']; ?></small></td>
									</tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px solid #DFDFDF;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>
								<?php
								break;

								case 'pages': ?>
									<tr>
										<td width="20%" rowspan="2" valign="middle"><strong><?php echo __($value['name'], 'wpShop'); ?></strong></td>
										<td width="80%">
											<select style="width:240px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
												<option value=""><?php echo $value['std']; ?></option>
												<?php 
												$sval = get_option($value['id']);
												foreach ($value['vals'] as $oval => $oname) { ?>
													<option value="<?php echo $oval; ?>"<?php if ($sval == $oval) { echo ' selected="selected"'; } ?>><?php echo $oname ?></option>
												<?php 
												} 			
											?></select>
										</td>
									</tr>
									<tr>
										<td><small><?php echo $value['desc']; ?></small></td>
									</tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px solid #DFDFDF;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>
								<?php break;

								case 'select': ?>
									<tr>
										<td width="20%" rowspan="2" valign="middle"><strong><?php echo __($value['name'], 'wpShop'); ?></strong></td>
										<td width="80%"><select style="width:240px;" 
										name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
										<?php 
										
												$o 		= get_settings($value['id']);
												$len	= strlen($o);
										
											foreach ($value['vals'] as $option) {
											
											
												?><option<?php 	
												if($len > 0){
													if(get_settings( $value['id']) == $option) { 
														echo ' selected="selected"'; 
													} 			
												}
												else {
													if($option == $value['std']){
														echo ' selected="selected"'; 
													}
												}
												?>><?php echo $option ?></option><?php 
											} 			
											?></select></td>
									</tr>
									<tr>
										<td><small><?php echo $value['desc']; ?></small></td>
									</tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px solid #DFDFDF;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>
								<?php break;

								case 'select2': ?>
									<tr>
										<td width="20%" rowspan="2" valign="middle"><strong><?php echo __($value['name'], 'wpShop'); ?></strong></td>
										<td width="80%"><select style="width:240px;" 
										name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
										<?php
										
											$o 		= get_settings($value['id']);
											$len	= strlen($o);
										
											foreach ($value['vals'] as $option) {
														
												$parts = explode("|",$option);
												
												if($len > 0){	// a value was already chosen 
												
													if(get_settings($value['id']) == $parts[1]){
														$selected = 'selected="selected"';
													}
													else{
														$selected = NULL;
													}
												}
												else {  // a value was not previously chosen, we fall back on std
													if($parts[1] == $value['std']){
														$selected = 'selected="selected"';
													}
													else{
														$selected = NULL;
													}			
												}
																
												$op = "<option value='$parts[1]' $selected >$parts[0]</option>";
												echo $op;	
											} 			
											?>
											</select></td>
									</tr>
									<tr>
										<td><small><?php echo $value['desc']; ?></small></td>
									</tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px solid #DFDFDF;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>
								<?php break;

								case 'category-select': ?>
									<?php $sval = get_settings($value['id']); ?>
									<tr>
										<td width="20%" rowspan="2" valign="middle"><strong><?php echo __($value['name'], 'wpShop'); ?></strong></td>
										<td width="80%"><?php wp_dropdown_categories('name='.$value['id'].'&hide_empty=0&hierarchical=1&orderby=name&show_option_none=Select a Category&selected='.$sval); ?></td>
									</tr>
									<tr>
										<td><small><?php echo $value['desc']; ?></small></td>
									</tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px solid #DFDFDF;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>
								<?php break;

								case 'pathinfo': ?>
									<tr>
										<td width="20%" rowspan="2" valign="middle"><strong><?php echo __($value['name'], 'wpShop'); ?></strong></td>
										<td width="80%"><input readonly="readonly" style="width:400px;" name="<?php echo $value['id']; ?>" 
										id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" 
										value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" />
										</td>
									</tr>

									<tr>
										<td><small><?php echo $value['desc']; ?></small></td>
									</tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px solid #DFDFDF;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>
								<?php break;
								
								
							
								case 'pathinfo2': ?>
									<tr>
										<td width="20%" rowspan="2" valign="middle"><strong><?php echo __($value['name'], 'wpShop'); ?></strong></td>
										<td width="80%"><input readonly="readonly" style="width:700px;" name="<?php echo $value['id']; ?>" 
										id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" 
										value="<?php echo $value['vals'] ?>" />
										</td>
									</tr>
									<tr>
										<td><small><?php echo $value['desc']; ?></small></td>
									</tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px solid #DFDFDF;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>
								<?php break;
							
								
								
								case "iframe": ?>
									<tr>
									<td width="20%" rowspan="2" valign="middle"><strong><?php echo __($value['name'], 'wpShop'); ?></strong></td>
										<td id='the_zones' width="80%">
											<iframe src="<?php echo $value['vals'] ?>" width="90%" height="400" name="<?php echo $value['id'] ?>"></iframe>
											</td>       
									</tr>
									<tr>
										<td><small><?php echo $value['desc']; ?></small></td>
								   </tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px solid #DFDFDF;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>
								<?php break;

								case "checkbox": ?>
									<tr>
									<td width="20%" rowspan="2" valign="middle"><strong><?php echo __($value['name'], 'wpShop'); ?></strong></td>
										<td width="80%"><?php if(get_settings($value['id']) == 'true'){ $checked = "checked=\"checked\""; }else{ $checked = ""; } ?>
												<input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
												</td>
									</tr>

									<tr>
										<td><small><?php echo $value['desc']; ?></small></td>
								   </tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px solid #DFDFDF;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

								<?php break;

								case "multi-checkbox": ?>
									<tr>
									<td width="20%" rowspan="2" valign="middle"><strong><?php echo __($value['name'], 'wpShop'); ?></strong></td>
										<td width="80%">

											<?php			
											$table  = $wpdb->prefix . 'options';
											$qStr 	= "SELECT option_value FROM $table WHERE option_name = '$value[id]' LIMIT 0,1";
											$res 	= mysql_query($qStr);
											$row 	= mysql_fetch_assoc($res);
											$payP	= explode("|",$row['option_value']);
									
											foreach($value['vals'] as $k => $v){	

												$data 		= explode("|",$v);
											
												if(in_array($data[1],$payP)){	
													$checked = "checked=\"checked\""; 
												}else{ 
													$checked = ""; 
												} 
											?>		
												<input type="checkbox" name="<?php echo $value['id'];?>|<?php echo $data[1];?>" id="<?php echo $value['id']; ?>|<?php echo $data[1];?>" value="true" <?php echo $checked; ?> /><?php echo "$data[0] <br/>";  
											} ?>
										</td>
									</tr>
									<tr>
										<td><small><?php echo $value['desc']; ?></small></td>
								   </tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px solid #DFDFDF;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

								<?php break;								

								case "tax-list": ?>
									<tr>
										<td width="20%" rowspan="2" valign="middle"><strong><?php echo __($value['name'], 'wpShop'); ?></strong></td>
										<td width="80%">
											<style>
											ul.tax-list{list-style:none;}
											ul.tax-list label{margin-left:2px;}
											ul.tax-list li{margin:0px;}
											</style>
											<ul class="tax-list">
											<?php
											$tax = $value['vals'];
											$svals = get_option($value['id']);
											if (!is_array($svals)) { $svals = array(); }
											$wp_taxs = get_terms($tax, 'hide_empty=0');
											if ($wp_taxs) {
												foreach($wp_taxs as $wp_tax) {
													$checked = ""; 
													if(in_array($wp_tax->term_id, $svals)){ $checked = 'checked="checked"'; } ?>
													<li><input type="checkbox" name="<?php echo $value['id']; ?>[]" value="<?php echo $wp_tax->term_id; ?>" <?php echo $checked; ?> /><label><?php echo $wp_tax->name; ?></label></li>
												<?php
												}
											} ?>
											</ul>
										</td>
									</tr>
									<tr>
										<td><small><?php echo $value['desc']; ?></small></td>
								    </tr>
									<tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px solid #DFDFDF;">&nbsp;</td></tr>
									<tr><td colspan="2">&nbsp;</td></tr>
								<?php break;

								case "multi-categories": ?>
									<tr>
									<td width="20%" rowspan="2" valign="middle"><strong><?php echo __($value['name'], 'wpShop'); ?></strong></td>
										<td width="80%">
											<style>
											div.multi-categories-box{height:350px;overflow:auto;}
											ul.multi-categories{list-style:none;}
											ul.multi-categories label{margin-left:2px;}
											ul.multi-categories li{margin:0px;}
											ul.multi-categories li.subcat{margin-left:15px;}
											ul.multi-categories li.subsubcat{margin-left:30px;}
											</style>
											<div class="multi-categories-box">
												<ul class="multi-categories">
												<?php
												$svals = get_option($value['id']);
												if (!is_array($svals)) { $svals = array(); }
												$wp_cats = get_categories('hide_empty=0');
												if ($wp_cats) {
													foreach($wp_cats as $wp_cat) {
														if ($wp_cat->parent == 0) {
															$checked = ""; 
															if(in_array($wp_cat->term_id, $svals)){ $checked = 'checked="checked"'; } ?>
															<li><input type="checkbox" name="<?php echo $value['id']; ?>[]" value="<?php echo $wp_cat->term_id; ?>" <?php echo $checked; ?> /><label><?php echo $wp_cat->name; ?></label></li>
															<?php
															foreach($wp_cats as $wp_subcat) {
																if ($wp_subcat->parent == $wp_cat->term_id) {
																	$checked = ""; 
																	if(in_array($wp_subcat->term_id, $svals)){ $checked = 'checked="checked"'; } ?>
																	<li class="subcat"><input type="checkbox" name="<?php echo $value['id']; ?>[]" value="<?php echo $wp_subcat->term_id; ?>" <?php echo $checked; ?> /><label><?php echo $wp_subcat->name; ?></label></li>
																	<?php
																	foreach($wp_cats as $wp_subsubcat) {
																		if ($wp_subsubcat->parent == $wp_subcat->term_id) {
																			$checked = ""; 
																			if(in_array($wp_subsubcat->term_id, $svals)){ $checked = 'checked="checked"'; } ?>
																			<li class="subsubcat"><input type="checkbox" name="<?php echo $value['id']; ?>[]" value="<?php echo $wp_subsubcat->term_id; ?>" <?php echo $checked; ?> /><label><?php echo $wp_subsubcat->name; ?></label></li>
																		<?php
																		}
																	}
																}
															}
														}
													}
												} ?>
												</ul>
											</div>
										</td>
									</tr>
									<tr>
										<td><small><?php echo $value['desc']; ?></small></td>
								   </tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px solid #DFDFDF;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

								<?php break;	

								case "alert-itbags-items": ?>

									<tr>
									<td width="20%" rowspan="2" valign="middle"><strong><?php echo __($value['name'], 'wpShop'); ?></strong></td>
										<td width="80%">
											<style>
											.alert-itbags-items {height:450px;overflow:auto;}
											.alert-itbags-items table td{border-bottom:1px solid #DFDFDF;}
											</style>
											<div class="alert-itbags-items">
											<table>
											<?php
											$svals = get_option($value['id']);
											if (!is_array($svals)) { $svals = array(); }
											$tax_brands = get_terms('brand');
											if ($tax_brands) {
												foreach($tax_brands as $tax_brand) { ?>
													<tr>
														<td><strong><?php echo $tax_brand->name; ?></strong></td>
														<td><textarea name="<?php echo $value['id']; ?>[<?php echo $tax_brand->term_id; ?>]" style="width:500px; height:150px;"><?php echo $svals[$tax_brand->term_id]; ?></textarea></td>
													</tr>
													<?php
												}
											} ?>
											</table>
											</div>
										</td>
									</tr>
									<tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px solid #DFDFDF;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

								<?php break;	

								case "multi-brands": ?>

									<tr>
										<td width="20%" rowspan="2" valign="middle"><strong><?php echo __($value['name'], 'wpShop'); ?></strong></td>
										<td width="80%">
											<?php
											$tax_brands = get_terms('brand');
											if ($tax_brands) {
												$svals = get_option($value['id']);
												if (!is_array($svals)) { $svals = array(); }
											?>
											<style>
											.multi-brands-box {height:350px;overflow:auto;}
											.multi-brands-box ul {width:810px;list-style:none;}
											.multi-brands-box ul li {float:left;width:200px;}
											</style>
											<div class="multi-brands-box">
												<ul>
												<?php
												foreach($tax_brands as $tax_brand) {
													$checked = '';
													if (in_array($tax_brand->term_id, $svals)) { $checked = 'checked="checked"'; } ?>
													<li><input type="checkbox" name="<?php echo $value['id']; ?>[]" value="<?php echo $tax_brand->term_id; ?>" <?php echo $checked; ?>> <?php echo $tax_brand->name; ?></li>
													<?php
												}
												?>
												</ul>
											</div>
											<?php } ?>
										</td>
									</tr>
									<tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px solid #DFDFDF;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

								<?php break;	

								case "wycsp_brands":
									$taxbrands = get_terms('brand', 'hide_empty=0');
									$svals = get_option($value['id']);
									if (!$taxbrands) { $taxbrands = array(); }
									if (!is_array($svals)) { $svals = array(); }
									?>
									<tr>
										<td>
											<style>
											.wycsp-brands .brlist{
												width:165px;
												height:300px;
												overflow:auto;
											}
											</style>
											<table class="wycsp-brands" width="100%">
												<tr>
													<td><strong>HANDBAGS</strong></td>
													<td><strong>SHOES</strong></td>
													<td><strong>WATCHES</strong></td>
													<td><strong>CLOTHES</strong></td>
													<td><strong>JEWELRY</strong></td>
													<td><strong>ACCESSORIES</strong></td>
												</tr>
												<tr>
													<td><div class="brlist">
														<?php foreach($taxbrands as $taxbrand) { ?>
														<input type="checkbox" name="<?php echo $value['id']; ?>[handbags][]" value="<?php echo $taxbrand->term_id; ?>"<?php if (@in_array($taxbrand->term_id, $svals['handbags'])) { echo ' CHECKED'; } ?>> <?php echo $taxbrand->name; ?><br />
														<?php } ?>
													</div></td>
													<td><div class="brlist">
														<?php foreach($taxbrands as $taxbrand) { ?>
														<input type="checkbox" name="<?php echo $value['id']; ?>[shoes][]" value="<?php echo $taxbrand->term_id; ?>"<?php if (@in_array($taxbrand->term_id, $svals['shoes'])) { echo ' CHECKED'; } ?>> <?php echo $taxbrand->name; ?><br />
														<?php } ?>
													</div></td>
													<td><div class="brlist">
														<?php foreach($taxbrands as $taxbrand) { ?>
														<input type="checkbox" name="<?php echo $value['id']; ?>[watches][]" value="<?php echo $taxbrand->term_id; ?>"<?php if (@in_array($taxbrand->term_id, $svals['watches'])) { echo ' CHECKED'; } ?>> <?php echo $taxbrand->name; ?><br />
														<?php } ?>
													</div></td>
													<td><div class="brlist">
														<?php foreach($taxbrands as $taxbrand) { ?>
														<input type="checkbox" name="<?php echo $value['id']; ?>[clothes][]" value="<?php echo $taxbrand->term_id; ?>"<?php if (@in_array($taxbrand->term_id, $svals['clothes'])) { echo ' CHECKED'; } ?>> <?php echo $taxbrand->name; ?><br />
														<?php } ?>
													</div></td>
													<td><div class="brlist">
														<?php foreach($taxbrands as $taxbrand) { ?>
														<input type="checkbox" name="<?php echo $value['id']; ?>[jewelry][]" value="<?php echo $taxbrand->term_id; ?>"<?php if (@in_array($taxbrand->term_id, $svals['jewelry'])) { echo ' CHECKED'; } ?>> <?php echo $taxbrand->name; ?><br />
														<?php } ?>
													</div></td>
													<td><div class="brlist">
														<?php foreach($taxbrands as $taxbrand) { ?>
														<input type="checkbox" name="<?php echo $value['id']; ?>[accessories][]" value="<?php echo $taxbrand->term_id; ?>"<?php if (@in_array($taxbrand->term_id, $svals['accessories'])) { echo ' CHECKED'; } ?>> <?php echo $taxbrand->name; ?><br />
														<?php } ?>
													</div></td>
												</tr>
											</table>
										</td>
									</tr>
									<tr><td colspan="2">&nbsp;</td></tr>
								<?php break;

								case "submission_form_brands":
									$taxbrands = get_terms('brand', 'hide_empty=0');
									$taxscategories = get_terms('seller-category', 'hide_empty=0');

									$svals = get_option($value['id']);
									if (!$taxbrands) { $taxbrands = array(); }
									if (!$taxscategories) { $taxscategories = array(); }
									if (!is_array($svals)) { $svals = array(); }
									?>
									<tr>
										<td>
											<style>
											.sf-brands .brlist{
												width:146px;
												height:300px;
												overflow:auto;
											}
											</style>
											<div class="sf-brands">
												<table width="100%">
													<tr>
														<?php foreach($taxscategories as $taxscategory) { ?>
														<td><strong><?php echo $taxscategory->name; ?></strong></td>
														<?php } ?>
													</tr>
													<tr>
														<?php foreach($taxscategories as $taxscategory) { ?>
														<td><div class="brlist">
															<?php foreach($taxbrands as $taxbrand) { ?>
															<input type="checkbox" name="<?php echo $value['id']; ?>[<?php echo $taxscategory->term_id; ?>][]" value="<?php echo $taxbrand->term_id; ?>"<?php if (@in_array($taxbrand->term_id, $svals[$taxscategory->term_id])) { echo ' CHECKED'; } ?>> <?php echo $taxbrand->name; ?><br />
															<?php } ?>
														</div></td>
														<?php } ?>
													</tr>
												</table>
											</div>
										</td>
									</tr>
									<tr><td colspan="2">&nbsp;</td></tr>
								<?php break;

							}
						} ?>

						<div id="themeOptionsSave">
							<p class="submit">
								<input name="save" type="submit" value="Save changes" />
								<input type="hidden" name="action" value="save" /><br/>
								<small><?php _e('The save button will save all theme options so there\'s no need to save each section separately.','wpShop') ?></small>
							</p>
						</div>
					</form>

				<?php } ?>
			</div><!-- wrap -->
		<?php 
		break;
		case 'pricing':
			echo make_section_header('pricing');

			$templurl = get_bloginfo('template_url');
			$p_categories = get_categories('hide_empty=0');
			$p_brands = get_terms('brand', 'hide_empty=0');
			$p_selections = get_terms('selection', 'hide_empty=0');
			$p_colours = get_terms('colour', 'hide_empty=0');

			$s_category = $_GET['s_category'];
			$s_brand = $_GET['s_brand'];
			$s_selection = $_GET['s_selection'];
			$s_colour = $_GET['s_colour'];
			$s_style = trim($_GET['s_style']);
			$pg = $_GET['pg'];
			?>
			<h2 style="padding:0px;"><?php _e('Pricing Database','wpShop'); ?> <a class="add-new-h2 pricing-add-icon thickbox" href="#TB_inline?height=500&width=900&inlineId=pricing-form" title="Add New Pricing">Add New</a></h2>
			<div class="tablenav">
				<div class="alignleft actions">
					<form action="?page=functions.php&section=pricing">
						<input type="hidden" name="page" value="functions.php" />
						<input type="hidden" name="section" value="pricing" />
						<?php wp_dropdown_categories('hide_empty=0&name=s_category&orderby=name&class=&hierarchical=1&show_option_none=-- Select Category --'); ?>
						<select name="s_brand">
							<option value="">-- Select Brand --</option>
							<?php foreach($p_brands as $p_brand) { $s = ''; if ($p_brand->term_id == $s_brand) { $s = ' SELECTED'; } ?>
							<option value="<?php echo $p_brand->term_id; ?>"<?php echo $s; ?>><?php echo $p_brand->name; ?></option>
							<?php } ?>
						</select>
						<select name="s_selection">
							<option value="">-- Select Condition --</option>
							<?php foreach($p_selections as $p_selection) { $s = ''; if ($p_selection->term_id == $s_selection) { $s = ' SELECTED'; } ?>
							<option value="<?php echo $p_selection->term_id; ?>"<?php echo $s; ?>><?php echo $p_selection->name; ?></option>
							<?php } ?>
						</select>
						<select name="s_colour">
							<option value="">-- Select Colour --</option>
							<?php foreach($p_colours as $p_colour) { $s = ''; if ($p_colour->term_id == $s_colour) { $s = ' SELECTED'; } ?>
							<option value="<?php echo $p_colour->term_id; ?>"<?php echo $s; ?>><?php echo $p_colour->name; ?></option>
							<?php } ?>
						</select>
						<input type="text" name="s_style" value="<?php echo $s_style; ?>" placeholder="Style Name">
						<input class="button-secondary action" type="submit" value="Search" />
					</form>
				</div>
			</div>
			<table border="1" class="pricing-list">
				<tr class="heads">
					<td><strong>Category</strong></td>
					<td><strong>Brand</strong></td>
					<td><strong>Style Name</strong></td>
					<td><strong>Condition</strong></td>
					<td><strong>Colour</strong></td>
					<td><strong>Orig. Price</strong></td>
					<td><strong>High Price</strong></td>
					<td><strong>Low Price</strong></td>
					<td><strong>Inc.Box</strong></td>
					<td><strong>Inc.Inv</strong></td>
					<td><strong>Inc.Dust</strong></td>
					<td><strong>Inc.Card</strong></td>
					<td><strong>Inc.Book</strong></td>
					<td><strong>Inc.LCP</strong></td>
					<td><strong>Photo</strong></td>
					<td><strong>Action</strong></td>
				</tr>
				<?php
				$pwhere = "";
				if ($s_category > 0) { if (strlen($pwhere)) { $pwhere .= " AND "; } $pwhere .= " p.category = ".$s_category; }
				if ($s_brand > 0) { if (strlen($pwhere)) { $pwhere .= " AND "; } $pwhere .= " p.brand = ".$s_brand; }
				if ($s_selection > 0) { if (strlen($pwhere)) { $pwhere .= " AND "; } $pwhere .= " p.selection = ".$s_selection; }
				if ($s_colour > 0) { if (strlen($pwhere)) { $pwhere .= " AND "; } $pwhere .= " p.colour = ".$s_colour; }
				if (strlen($s_style)) { if (strlen($pwhere)) { $pwhere .= " AND "; } $pwhere .= " p.style_name LIKE '%".$s_style."%'"; }
				if (strlen($pwhere)) { $pwhere = " WHERE ".$pwhere; }

				$pper_page = 100;
				if (!strlen($pg)) { $pg = 1; }
				$plimit = " LIMIT ".(($pg - 1) * $pper_page).", ".$pper_page;

				$psql = sprintf("SELECT SQL_CALC_FOUND_ROWS p.*, cat.name as category_name, br.name as brand_name, sel.name as selection_name, cl.name as colour_name FROM %swps_pricing p 
				LEFT JOIN %sterms cat ON cat.term_id = p.category
				LEFT JOIN %sterms br ON br.term_id = p.brand
				LEFT JOIN %sterms sel ON sel.term_id = p.selection
				LEFT JOIN %sterms cl ON cl.term_id = p.colour
				%s
				ORDER BY cat.name, br.name %s", $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $pwhere, $plimit);
				$pricing_records = $wpdb->get_results($psql);
				$pricing_total = $wpdb->get_var("SELECT FOUND_ROWS()");
				if ($pricing_total) {
					foreach($pricing_records as $pricing_record) {
						$pricing_data = $pricing_record->category.';'.$pricing_record->brand.';'.$pricing_record->style_name.';'.$pricing_record->selection.';'.$pricing_record->colour.';'.$pricing_record->original_price.';'.$pricing_record->high_price.';'.$pricing_record->low_price.';'.$pricing_record->includes_box.';'.$pricing_record->includes_invoice.';'.$pricing_record->includes_dustbag.';'.$pricing_record->includes_card.';'.$pricing_record->includes_booklet.';'.$pricing_record->includes_packaging.';'.$pricing_record->photo.';'.$pricing_record->notes.';'.$pricing_record->metal.';'.$pricing_record->material.';'.$pricing_record->movement;
				?>
				<tr>
					<td><?php echo $pricing_record->category_name; ?></td>
					<td><?php echo $pricing_record->brand_name; ?></td>
					<td><?php echo $pricing_record->style_name; ?></td>
					<td><?php echo $pricing_record->selection_name; ?></td>
					<td><?php echo $pricing_record->colour_name; ?></td>
					<td><?php echo $pricing_record->original_price; ?></td>
					<td><?php echo $pricing_record->high_price; ?></td>
					<td><?php echo $pricing_record->low_price; ?></td>
					<td><?php echo $pricing_record->includes_box; ?></td>
					<td><?php echo $pricing_record->includes_invoice; ?></td>
					<td><?php echo $pricing_record->includes_dustbag; ?></td>
					<td><?php echo $pricing_record->includes_card; ?></td>
					<td><?php echo $pricing_record->includes_booklet; ?></td>
					<td><?php echo $pricing_record->includes_packaging; ?></td>
					<td><?php if (strlen($pricing_record->photo)) { ?><a href="<?php echo $pricing_record->photo; ?>" class="thickbox">view</a><?php } else { echo '-'; } ?></td>
					<td><a href="#TB_inline?height=500&width=900&inlineId=pricing-form" title="Edit Pricing" class="thickbox"><img src="<?php echo $templurl; ?>/images/pr-edit.png" class="pricing-edit-icon" rel="<?php echo $pricing_record->pid; ?>"></a>&nbsp;&nbsp;<img src="<?php echo $templurl; ?>/images/pr-del.png" title="Delete Pricing" class="pricing-delete-icon" rel="<?php echo $pricing_record->pid; ?>"><input type="hidden" name="pdata" id="pricing-data-<?php echo $pricing_record->pid; ?>" value="<?php echo $pricing_data; ?>"></td>
				</tr>
				<?php
					}
				} else {
				?>
				<tr>
					<td colspan="16">No records.</td>
				</tr>
				<?php
				}
				?>
			</table>
			<?php
			if ($pricing_total > $pper_page) {
				$ptotal_pages = ceil($pricing_total / $pper_page); ?>
				<div class="tablenav">
					<div class='tablenav-pages'>
						<?php if ($pg > 1) { ?><a href="admin.php?page=functions.php&section=pricing&pg=<?php echo ($pg - 1); ?>" class="prev page-numbers">prev</a><?php } ?>
						<?php for ($p=1; $p<=$ptotal_pages; $p++) {
							if ($p == $pg) { ?>
								<span class="page-numbers current"><?php echo $p; ?></span>
							<?php } else { ?>
								<a href="admin.php?page=functions.php&section=pricing&pg=<?php echo $p; ?>" class="page-numbers"><?php echo $p; ?></a>
							<?php } ?>
						<?php } ?>
						<?php if (($pg + 1) <= $ptotal_pages) { ?><a href="admin.php?page=functions.php&section=pricing&pg=<?php echo ($pg + 1); ?>" class="next page-numbers">next</a><?php } ?>
					</div>
				</div>
			<?php } ?>
			<form method="POST" action="admin.php?page=functions.php&section=pricing<?php if ($pg > 1) { echo '&pg='.$pg; } ?>" id="pricing-delete-form">
				<input type="hidden" name="PricingAction" value="delete">
				<input type="hidden" name="pid" id="pricing-del-pid">
			</form>
			<div id="pricing-form" style="display:none;">
				<form method="POST" action="admin.php?page=functions.php&section=pricing<?php if ($pg > 1) { echo '&pg='.$pg; } ?>" class="pricing-form" enctype="multipart/form-data">
					<input type="hidden" name="PricingAction" value="add" id="pricing-action">
					<input type="hidden" name="pid" id="pricing-pid">
					<ul class="left-side">
						<li><label>Category:</label>
						<?php wp_dropdown_categories('hide_empty=0&name=p_category&orderby=name&class=&hierarchical=1&show_option_none=-- Select Category --'); ?></li>
						<li><label>Brand:</label>
						<select name="p_brand" id="p-brand">
							<option value="">-- Select Brand --</option>
							<?php foreach($p_brands as $p_brand) { ?>
							<option value="<?php echo $p_brand->term_id; ?>"><?php echo $p_brand->name; ?></option>
							<?php } ?>
						</select></li>
						<li><label>Style Name:</label>
						<input type="text" name="p_style_name" id="p-style-name"></li>
						<li><label>Condition:</label>
						<select name="p_selection" id="p-selection">
							<option value="">-- Select Condition --</option>
							<?php foreach($p_selections as $p_selection) { ?>
							<option value="<?php echo $p_selection->term_id; ?>"><?php echo $p_selection->name; ?></option>
							<?php } ?>
						</select></li>
						<li><label>Colour:</label>
						<select name="p_colour" id="p-colour">
							<option value="">-- Select Colour --</option>
							<?php foreach($p_colours as $p_colour) { ?>
							<option value="<?php echo $p_colour->term_id; ?>"><?php echo $p_colour->name; ?></option>
							<?php } ?>
						</select></li>
						<li><label>Metal:</label>
						<select name="p_metal[]" id="p-metal" size="5" multiple style="height:64px;">
							<?php $mcatoptions = sellers_get_category_options('metal');
							foreach($mcatoptions as $mop) { ?>
							<option value="<?php echo $mop; ?>"><?php echo $mop; ?></option>
							<?php } ?>
						</select>
						</li>
						<li><label>Material:</label>
						<select name="p_material[]" id="p-material" size="5" multiple style="height:64px;">
							<?php $mcatoptions = sellers_get_category_options('material');
							foreach($mcatoptions as $mop) { ?>
							<option value="<?php echo $mop; ?>"><?php echo $mop; ?></option>
							<?php } ?>
						</select>
						</li>
						<li><label>Movement:</label>
						<select name="p_movement[]" id="p-movement" size="5" multiple style="height:64px;">
							<?php $mcatoptions = sellers_get_category_options('movement');
							foreach($mcatoptions as $mop) { ?>
							<option value="<?php echo $mop; ?>"><?php echo $mop; ?></option>
							<?php } ?>
						</select>
						</li>
					</ul>
					<ul class="center-side">
						<li><label>Original Price:</label>
						<input type="text" name="p_original_price" id="p-original-price"> USD</li>
						<li><label>High Price:</label>
						<input type="text" name="p_high_price" id="p-high-price" onblur="pricing_low_price()"> USD</li>
						<li><label>Low Price:</label>
						<input type="text" name="p_low_price" id="p-low-price"> USD</li>
						<?php $sellers_includes = sellers_get_includes();
						foreach($sellers_includes as $si_key => $si_val) { ?>
						<li><label><?php echo $si_val; ?> Inc:</label>
						<input type="text" name="p_includes_<?php echo $si_key; ?>" id="p-includes-<?php echo $si_key; ?>"> USD</li>
						<?php } ?>
						<li style="margin-top:45px;"><input type="submit" value="&nbsp;&nbsp;Submit&nbsp;&nbsp;"></li>
					</ul>
					<ul class="right-side">
						<li><label>Notes:</label>
						<textarea name="p_notes" id="p-notes" style="width:200px;height:80px;"></textarea></li>
						<li><label>Photo Upload:</label>
						<input type="file" name="p_photo"></li>
						<li id="p-photo" style="display:none;"><label>Photo:</label>
						<img src="" id="p-photo-img" style="height:150px;"></li>
					</ul>
				</form>
			</div>
			<?php
			echo make_section_footer();
		break;
		case 'searches':
			$filter = $_GET['filter'];
			$fsdate = $_GET['fsdate'];
			$fedate = $_GET['fedate'];
			$floc = $_GET['floc'];
			$filter_vals = array('today' => 'Today', '7days' => 'Last 7 days', '30days' => 'Last 30 days', 'month' => 'Last month', 'year' => 'Last year');

			$sdate = date("Y-m-d H:i:s", mktime(0, 0, 0, 1, 1, date("Y")));
			$edate = date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")));
			if (strlen($filter)) {
				switch ($filter) {
					case 'today':
						$sdate = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
					break;
					case '7days':
						$sdate = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") - 7, date("Y")));
					break;
					case '30days':
						$sdate = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") - 30, date("Y")));
					break;
					case 'month':
						$sdate = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), 1, date("Y")));
					break;
					case 'year':
						$sdate = date("Y-m-d H:i:s", mktime(0, 0, 0, 1, 1, date("Y")));
					break;
				}
			} else if (strlen($fsdate) || strlen($fedate)) {
				$sdates = explode("-", $fsdate);
				$edates = explode("-", $fedate);
				if (strlen($fsdate) && strlen($fedate)) {
					$sdate = date("Y-m-d H:i:s", mktime(0, 0, 0, $sdates[1], $sdates[2], $sdates[0]));
					$edate = date("Y-m-d H:i:s", mktime(23, 59, 59, $edates[1], $edates[2], $edates[0]));
				} else if (strlen($fsdate)) {
					$sdate = date("Y-m-d H:i:s", mktime(0, 0, 0, $sdates[1], $sdates[2], $sdates[0]));
				} else if (strlen($fedate)) {
					$edate = date("Y-m-d H:i:s", mktime(23, 59, 59, $edates[1], $edates[2], $edates[0]));
				}
			}
			if (strlen($floc)) {
				$locwhere = " AND slocation = '".$floc."'";
			}

			$searches = $wpdb->get_results(sprintf("SELECT svalue, COUNT(*) as snumber FROM %swps_searches WHERE sdate >= '%s' AND sdate <= '%s' %s GROUP BY svalue ORDER BY snumber DESC", $wpdb->prefix, $sdate, $edate, $locwhere));

			$detail_searches = false;
			if (strlen($_GET['sval'])) {
				$detail_searches = $wpdb->get_results(sprintf("SELECT * FROM %swps_searches WHERE sdate >= '%s' AND sdate <= '%s' AND svalue = '%s' %s ORDER BY sdate DESC", $wpdb->prefix, $sdate, $edate, $_GET['sval'], $locwhere));
			}
			$countries = wps_get_countries();
			echo make_section_header('searches');
			?>
			<h2 style="padding:0px;"><?php _e('Searches Statistics','wpShop'); ?></h2>
			<ul class="searches-fliter">
				<form name="searches_filter" action="admin.php">
				<input type="hidden" name="page" value="functions.php">
				<input type="hidden" name="section" value="searches">
				<li class="txt">Filter:</li>
				<li>
					<select name="filter">
						<option value="">-- Select Filter --</option>
						<?php foreach($filter_vals as $fvk => $fvv) { $s = ''; if ($fvk == $filter) { $s = ' SELECTED'; } ?>
						<option value="<?php echo $fvk; ?>"<?php echo $s; ?>><?php echo $fvv; ?></option>
						<?php } ?>
					</select>
				</li>
				<li class="txt">&nbsp;OR&nbsp;</li>
				<li><input type="text" name="fsdate" value="<?php echo $fsdate; ?>" placeholder="YYYY-MM-DD"></li>
				<li class="txt">-</li>
				<li><input type="text" name="fedate" value="<?php echo $fedate; ?>" placeholder="YYYY-MM-DD"></li>
				<li class="txt">&nbsp;&nbsp;</li>
				<li><select name="floc" style="width:200px;">
					<option value="">-- Location --</option>
					<?php foreach($countries as $ckey => $cval) { ?>
						<option value="<?php echo $ckey; ?>"<?php if ($ckey == $floc) { echo ' SELECTED'; } ?>><?php echo $cval; ?></option>
					<?php } ?>
				</select></li>
				<li><input type="submit" value="&nbsp;&nbsp;Filter&nbsp;&nbsp;"></li>
				</form>
			</ul>
			<div style="clear:both;"></div>
			<div style="width:1030px; margin-top:15px; clear:both;">
				<div style="width:500px; height:655px; overflow:auto; float:left;">
					<table cellpadding="0" cellspacing="0" class="searches-list" width="100%">
						<tr class="heads">
							<td><strong>Search term</strong></td>
							<td><strong>Number of searches</strong></td>
						</tr>
						<?php if ($searches) { ?>
							<?php foreach($searches as $sdata) { ?>
							<tr>
								<!--<td><a href="admin.php?page=functions.php&section=searches&filter=<?php echo $_GET['filter']; ?>&fsdate=<?php echo $_GET['fsdate']; ?>&fedate=<?php echo $_GET['fedate']; ?>&floc=<?php echo $_GET['floc']; ?>&sval=<?php echo $sdata->svalue; ?>"><?php echo $sdata->svalue; ?></a></td>-->
								<td><?php echo $sdata->svalue; ?></td>
								<td><?php echo $sdata->snumber; ?></td>
							</tr>
							<?php } ?>
						<?php } ?>
					</table>
				</div>
				<div style="width:500px; height:655px; overflow:auto; float:left; margin-left:20px;">
					<?php if ($detail_searches) { $snmb = 1; ?>
					<table cellpadding="0" cellspacing="0" class="searches-list" width="100%">
						<tr class="heads">
							<td><strong>N</strong></td>
							<td><strong>Search term</strong></td>
							<td><strong>Location</strong></td>
							<td><strong>Date</strong></td>
						</tr>
						<?php foreach($detail_searches as $sdata) { ?>
							<tr>
								<td><?php echo $snmb; ?></td>
								<td><?php echo $sdata->svalue; ?></td>
								<td><?php echo $sdata->slocation; ?></td>
								<td><?php echo date("Y/m/d", strtotime($sdata->sdate)); ?></td>
							</tr>
						<?php $snmb++; } ?>
					</table>
					<?php } ?>
				</div>
			</div>
			<div style="clear:both;"></div>
			<div class="export-csv" style="padding-top:5px; margin-top:15px; border-top:1px solid #C1C1C1;"><a href="admin.php?page=functions.php&section=searches&sdate=<?php echo $sdate; ?>&edate=<?php echo $edate; ?>&floc=<?php echo $floc; ?>&csvexport=searches">Export CSV</a></div>
			<?php
			echo make_section_footer();
		break;
		case 'logs':
			$item_id = trim($_GET['item_id']);
			echo make_section_header('logs');
			?>
			<h2 style="padding:0px;"><?php _e('Actions Logs','wpShop'); ?></h2>
			<ul class="searches-fliter">
				<form name="searches_filter" action="admin.php">
				<input type="hidden" name="page" value="functions.php">
				<input type="hidden" name="section" value="logs">
				<li class="txt">Item ID:</li>
				<li><input type="text" name="item_id" value="<?php echo $item_id; ?>"></li>
				<li><input type="submit" value="&nbsp;&nbsp;Search&nbsp;&nbsp;"></li>
				</form>
			</ul>
			<div style="clear:both;"></div>
			<?php if (strlen($item_id)) { ?><br />
				<table cellpadding="0" cellspacing="0" class="searches-list" width="100%">
					<tr class="heads">
						<td><strong>Date</strong></td>
						<td><strong>Action</strong></td>
						<td><strong>Info</strong></td>
					</tr>
					<?php
					$log_codes = array(
						'order_cancel' => 'Order Cancelled',
						'order_delete' => 'Order Deleted',
						'order_return' => 'Order Returned',
						'order_received' => 'Order Received',
						'inventory_update' => 'Inventory Updated'
					);
					$logs = $wpdb->get_results(sprintf("SELECT * FROM %swps_log_actions WHERE log_desc LIKE '%s' ORDER BY log_id", $wpdb->prefix, '%'.$item_id.'%'));
					if ($logs) {
						foreach($logs as $log) {
							$log_desc = $log->log_desc;
							if (strpos($log_desc, 'Order ID:') !== false) {
								$oid = substr($log_desc, strpos($log_desc, 'Order ID:') + 10);
								$oid = substr($oid, 0, strpos($oid, ';'));
								$log_desc = str_replace('Order ID: '.$oid, 'Order ID: '.$OPTION['wps_order_no_prefix'].$oid, $log_desc);
							}
							if (strpos($log_desc, 'User ID:') !== false) {
								$uid = substr($log_desc, strpos($log_desc, 'User ID:') + 9);
								$user_login = $wpdb->get_var(sprintf("SELECT user_login FROM %susers WHERE ID = %s", $wpdb->prefix, $uid));
								$log_desc = str_replace('User ID: '.$uid, 'User: '.$user_login, $log_desc);
							}
							?>
							<tr>
								<td><?php echo $log->log_date; ?></td>
								<td><?php echo $log_codes[$log->log_code]; ?></td>
								<td><?php echo $log_desc; ?></td>
							</tr>
						<?php } ?>
					<?php } else { ?>
						<tr><td colspan="3">Nothing found.</td></tr>
					<?php } ?>
				</table>
			<?php } ?>
			<?php
			echo make_section_footer();
		break;
	}
}

function NWS_theme_editor()
{
    global $CONFIG_WPS,$wpdb,$options,$useSection,$install_status,$OPTION;
	if($_GET['update'] == '1') { inventory_amount_update(); }
	if($_GET['clean_inventory'] == '1') { clean_inventory(); }
	if($_GET['enigma'] == '1') {	
		# 1. Attributes-Check
		adapt_inventory2attributes();	// adds missing attr. cols to inventory table
		# 2. Article-Check / is article there at all
		inventory_article_check();
	}
	echo make_section_header('inventory'); ?>
	<div class="tablenav">
		<div class="alignleft actions">
			<?php
				echo "
				<a id='nws_inv_return' class='button-secondary action' href='?page=nws-inventory&clean_inventory=1' title='".__('Returns Products from "abandoned" Carts.','wpShop')."'>".__('Return Stock','wpShop')."</a>
				<a id='nws_inv_refresh' class='button-secondary action' href='?page=nws-inventory&enigma=1' title='".__('Refresh if you have added a New Product and/or Attribute (Product Variation)','wpShop')."'>".__('Refresh List','wpShop')."</a>
				<form class='nws_search nws_inv_search' action='?page=nws-inventory' method='get'>
					<input type='hidden' name='page' value='nws-inventory' />
					<input type='text' name='art_wanted' value='$_GET[art_wanted]' maxlength='255' />
					<input class='button-secondary action' type='submit' name='search_inv' value='Search' /><br/>
					<small>".__('Enter at least the first 3 digits of a Product ID_item','wpShop')."</small>
				</form>
				";
			?>	
		</div>
		<div class='tablenav-pages' style="float:left;">
			<?php NWS_inventory_pagination(20); ?>	
		</div>
	</div>
	<?php 
	// get all articles				
	echo "<div id='nws_inv_wrap'>";
		$res 		= inventory_main_query(pagination_limit_clause(20));
		while($row = mysql_fetch_assoc($res))
		{						
		$show_not1 = (strlen(get_custom_field2($row['post_id'],'item_file')) > 1 ? 1 : 0);	
		$show_not2 = (strlen(get_custom_field2($row['post_id'],'buy_now')) > 1 ? 1 : 0);	
			if(($show_not1 == 0)&&($show_not2 == 0)){										
				$product_title 	= $row['post_title'];		
				$product_image	= inventory_product_image($row['post_id']);
				echo "
				<form class='nws_inv_prod' action='?page=nws-inventory&update=1' method='post' style='border: 1px solid gainsboro;'>
					<h4 class='nws_inv_prod_ID'>".__('Article No.','wpShop').": $row[meta_value]</h4> <input class='nws_inv_prod_update' type='submit' name='submit_this' value='".__('update','wpShop')."' />
					<div class='nws_inv_details'>
						<p class='nws_inv_prod_title'>$product_title";
							if(strlen($product_image) > 1){
								echo " | <a href='$product_image' title='$product_title' class='thickbox'><img src='images/media-button-image.gif' alt='".__('Product Image','wpShop')."' /></a>";
							}
						echo "</p>";								
						if(inventory_has_attributs($row[post_id]) != 0){	// attributes - yes/no
							if($row_head !== FALSE){ // the array is not empty
								echo "<table>";
								echo header_for_attributes($row[meta_value]);
								echo display_attributes_data($row[meta_value],inventory_order_clause());									
								echo "</table>";
							}
							else { echo __('Please refresh your inventory!','wpShop');}
						}
						else {
							$check_inv_amount = $wpdb->get_var(sprintf("SELECT COUNT(iid) FROM %swps_inventory WHERE ID_item = '%s'", $wpdb->prefix, $row[meta_value]));
							if ($check_inv_amount == 0) {
								$insert = array();
								$insert["Size"] = '';
								$insert["Material"] = '';
								$insert["Colour"] = '';
								$insert["Duration"] = '';
								$insert["ID_item"] = $row['meta_value'];
								$insert["amount"] = '0';
								$wpdb->insert($wpdb->prefix."wps_inventory", $insert);
							}
							$res2 = display_amount($row[meta_value]);
							echo "<label>".__('Amount: ','wpShop')."</label>";
							while($row2 = mysql_fetch_assoc($res2)){								
								echo "<input type='text' name='$row2[iid]' value='$row2[amount]' />";
							}
						}
					echo "</div>
				</form>
				";
			}
		}
	echo "</div>";
	echo make_section_footer();	
} // editor options

function NWS_theme_staff()
{
    global $CONFIG_WPS,$wpdb,$options,$useSection,$install_status,$OPTION,$current_user;
	$section = str_replace('#',NULL,$_GET['section']); 	
	switch($section)
	{	
		case 'orders':				
			if($_GET['subsection'] == 'dlinks'){	send_user_dlinks($_GET['token']);	}
			if(isset($_POST['status_change'])){ multi_change_order_level();	}
			if(isset($_REQUEST['returned_order']) && $_REQUEST['returned_order'] == 'Save' ) returned_order();
			$table_header = "
				<table class='widefat' >
					<thead>
						<tr>
							<th>".__('Order','wpShop')."</th>
							<th>".__('No.','wpShop')."</th>
							<th>".__('Date','wpShop')."</th>
							<th>".__('Billing','wpShop')."</th>
							<th>".__('Delivery','wpShop')."</th>
							<th>".__('Total Value','wpShop')."</th>
							<th>".__('Details','wpShop')."</th>
							<th>".__('Invoice','wpShop')."</th>
							<th>".__('Payment and Delivery','wpShop')."</th>";
							if($OPTION['wps_customNote_enable'] == TRUE) {
								$table_header .= "<th>".__('Custom Note','wpShop')."</th>";
							}
						$table_header .= "</tr>
					</thead>
					<tbody>
				";	
			$table_footer 	= "</tbody></table>";				
			$empty_message 	= "<h4>".__('No orders with this status.','wpShop')."</h4>";
			$date_format	= "j.m.Y - G:i:s";

			$otab = $_GET['otab'];
			if (!strlen($otab)) { $otab = 'new'; }

			$oper_page = (int)$OPTION['wps_admin_orders_per_page'];
			$opg = $_GET['opg'];

			if (!$oper_page) { $oper_page = 20; }
			if (!$opg) { $opg = 1; }

			$olimit_start = ($opg - 1) * $oper_page;

			$olevel_vals = array('new' => 4, 'pending' => 8, 'shipped' => 5, 'received' => 6, 'completed' => 7, 'layaway' => 3, 'cancelled' => 0);

			// IN ('0','3','4','5','6','7','8')
			$table 	= is_dbtable_there('orders');
			$res 	= mysql_query(sprintf("SELECT SQL_CALC_FOUND_ROWS * FROM %s WHERE level = '%s' ORDER BY oid DESC LIMIT %s, %s", $table, $olevel_vals[$otab], $olimit_start, $oper_page));
			$calc_rows_res 	= mysql_query("SELECT FOUND_ROWS() as total_rows");
			$calc_rows = mysql_fetch_assoc($calc_rows_res);
			$ototal = (int)$calc_rows['total_rows'];
			$opages = ceil($ototal / $oper_page);
			$odata = classify_orders($res);

			$pending_ord = $wpdb->get_var(sprintf("SELECT COUNT(oid) FROM %s WHERE level = '8'", $table));

			echo make_section_staff_header('orders'); ?>
			<style>
			.nws_manage_orders fieldset { display:none; }
			.nws_manage_orders fieldset.active { display:block; }
			</style>
			<form class="nws_admin_options nws_manage_orders" action="themes.php?page=functions.php&section=orders&otab=<?php echo $otab; ?>" method="post">
				<div class="nws_manage_orders_tabs">
					<?php if ($pending_ord > 0) { ?>
						<fieldset id="pending" rel="admin.php?page=functions.php&section=orders&otab=pending"<?php if ($otab == 'pending') { echo ' class="active"'; } ?>>
							<?php 
							echo "<h3>".__('Status 0: Payment Pending','wpShop')."</h3>";
							display_order_entries($odata,5,$date_format,$table_header,$table_footer,$empty_message); 
							display_order_pagination($opages, 'pending');
							?>
						</fieldset>
					<?php } ?>
					<fieldset id="new" rel="admin.php?page=functions.php&section=orders&otab=new"<?php if ($otab == 'new') { echo ' class="active"'; } ?>>
						<?php 
						echo "<h3>".__('Status 1: Newly Orders','wpShop')."</h3>"; 
						display_order_entries($odata,1,$date_format,$table_header,$table_footer,$empty_message); 
						display_order_pagination($opages, 'new');
						?>
					</fieldset>
					<fieldset id="shipped" rel="admin.php?page=functions.php&section=orders&otab=shipped"<?php if ($otab == 'shipped') { echo ' class="active"'; } ?>>
						<?php 
						echo "<h3>".__('Status 2: Shipped Orders','wpShop')."</h3>"; 
						display_order_entries($odata,2,$date_format,$table_header,$table_footer,$empty_message); 
						display_order_pagination($opages, 'shipped');
						?>
					</fieldset>
					<fieldset id="received" rel="admin.php?page=functions.php&section=orders&otab=received"<?php if ($otab == 'received') { echo ' class="active"'; } ?>>
						<?php 
						echo "<h3>".__('Status 3: Payment Received','wpShop')."</h3>"; 
						display_order_entries($odata,3,$date_format,$table_header,$table_footer,$empty_message); 
						display_order_pagination($opages, 'received');
						?>
					</fieldset>
					<fieldset id="completed" rel="admin.php?page=functions.php&section=orders&otab=completed"<?php if ($otab == 'completed') { echo ' class="active"'; } ?>>
						<?php 
						echo "<h3>".__('Status 4: Completed Orders','wpShop')."</h3>"; 
						display_order_entries($odata,4,$date_format,$table_header,$table_footer,$empty_message); 
						display_order_pagination($opages, 'completed');
						?>
					</fieldset>
					<fieldset id="layaway" rel="admin.php?page=functions.php&section=orders&otab=layaway"<?php if ($otab == 'layaway') { echo ' class="active"'; } ?>>
						<?php 
						echo "<h3>".__('Layaway Orders','wpShop')."</h3>"; 
						display_order_entries($odata,6,$date_format,$table_header,$table_footer,$empty_message); 
						display_order_pagination($opages, 'layaway');
						?>
					</fieldset>
					<fieldset id="cancelled" rel="admin.php?page=functions.php&section=orders&otab=cancelled"<?php if ($otab == 'cancelled') { echo ' class="active"'; } ?>>
						<?php 
						echo "<h3>".__('Cancelled Orders','wpShop')."</h3>"; 
						display_order_entries($odata,7,$date_format,$table_header,$table_footer,$empty_message); 
						display_order_pagination($opages, 'cancelled');
						?>
					</fieldset>
					<div class="tablenav">
						<label><?php _e('Bulk Actions:','wpShop'); ?></label> 
						<select name='status' size='1' class="order-status-act" onchange="status_act()">									
							<option value='4'><?php _e('Newly Received','wpShop'); ?></option>
							<option value='5'><?php _e('Shipped Orders','wpShop'); ?></option>
							<option value='6'><?php _e('Payment Received','wpShop'); ?></option>
							<option value='7'><?php _e('Completed','wpShop'); ?></option>
							<?php if ($_GET['otab'] != 'cancelled') { ?>
							<option value='0'><?php _e('Cancelled Orders','wpShop'); ?></option>
							<?php } ?>
						</select>
						<?php $order_cancel_reasons = get_order_cancel_reasons(); ?>
						<select name="cancel_reason" class="order-cancel-reason" style="width:175px; display:none;">
							<option value="">-- <?php _e('Select Cancel Reason','wpShop'); ?> --</option>
							<?php foreach($order_cancel_reasons as $creason) { ?>
							<option value="<?php echo $creason; ?>"><?php echo $creason; ?></option>
							<?php } ?>
						</select>
						<input type="submit" name="status_change" value="<?php _e('Apply','wpShop'); ?>" onclick="admin_change_orders_status();" />
					</div>
				</div><!-- hasadmintabs -->
			</form>
			<?php echo make_section_footer();			
			if($odata[1] > 0) 
			{
			?>
			<div class="popup_form" id="popup_form">  
			   <div class="popup_title">
					<div id="TB_ajaxWindowTitle">Returned this Order #number?</div>
					<a id="popup_close"><img src="<?=get_bloginfo('template_directory')?>/images/closebox.png" title="Close" alt="Close" /></a>
				</div>
				<div class="popup_forms">  
				<form name="return_orders" action="themes.php?page=functions.php&section=orders" method="post">
				<input type="hidden" name="pop_rt_order_id" id="pop_rt_order_id" value="" />
				<table id="popup_rt_data" class="widefat" width="100%" border="0">
					<thead>
						<tr>
							<th width="15%">ID</th>
							<th width="51%">Item</th>
							<th width="17%" align="center">Total Qty</th>
							<th width="17%">Returned Qty</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="pop_item_id">Id</td>
							<td class="pop_item_name"><b>Name</b> <br>  </td>
							<td class="pop_item_qty">qty</td>
							<td class="pop_item_rqty"><input type="text" name="pop_item_rtqty_" class="pop_item_rtqty" value="0" /></td>	
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="2" align="right">
							<?php $order_cancel_reasons = get_order_cancel_reasons(); ?>
							<select name="cancel_reason" style="width:175px;">
								<option value="">-- <?php _e('Select Cancel Reason','wpShop'); ?> --</option>
								<?php foreach($order_cancel_reasons as $creason) { ?>
								<option value="<?php echo $creason; ?>"><?php echo $creason; ?></option>
								<?php } ?>
							</select>
							</td>
							<td><input type="submit" class="button" name="returned_order" value="Save" /></td>
							<td><input type="button" class="button" id="cancel" name="returned_order" value="Cancel"  /></td>
						</tr>
					</tfoot>
				</table>
				</form>
				</div>
			</div>
			<div id="popup_bg"></div>
			<?php	add_action('admin_footer', 'order_return_popup_script');
			}			
		break;
		case 'inquiries':
			if(isset($_POST['status_change'])){			multi_change_inquiry_level();	}
			$odata =  classify_inquiries();
			$table_header = "
			<table border='1' class='widefat' >
				<thead>
					<tr>
						<th>".__('Order','wpShop')."</th>
						<th>".__('No.','wpShop')."</th>
						<th>".__('Date','wpShop')."</th>
						<th>".__('Who?','wpShop')."</th>
						<th>".__('Billing','wpShop')."</th>
						<th>".__('Delivery','wpShop')."</th>
						<th>".__('Total Value','wpShop')."</th>
						<th>".__('Details','wpShop')."</th>
						<th>".__('Preferred Payment and Delivery','wpShop')."</th>
						<th>".__('Custom Note','wpShop')."</th>
					</tr>
				</thead>
				<tbody>
			";
			$table_footer 	= "</tbody></table>";				
			$empty_message 	= "<h4>".__('No inquiries with this status.','wpShop')."</h4>";
			$date_format	= "j.m.Y - G:i:s";
			echo make_section_staff_header('inquiries'); ?>
			<form class="nws_admin_options nws_manage_inquiries" action="themes.php?page=functions.php&section=inquiries" method="post">
				<div class="hasadmintabs hasadmintabs1">
					<fieldset id="new">
						<?php 
						echo "<h3 class='new'>".__('Status 1: Newly Received','wpShop')."</h3>"; 
						display_inquiry_entries($odata,1,$date_format,$table_header,$table_footer,$empty_message); 
						?>
					</fieldset>
					<fieldset id="replied">
						<?php 
						echo "<h3>".__('Status 2: Already Replied To','wpShop')."</h3>"; 
						display_inquiry_entries($odata,2,$date_format,$table_header,$table_footer,$empty_message); 
						?>
					</fieldset>
					<div class="tablenav">
						<label><?php _e('Bulk Actions:','wpShop'); ?></label> 
						<select name='status' size='1'>
							<option value='4'><?php _e('Newly Received','wpShop'); ?></option>
							<option value='5'><?php _e('Replied To','wpShop'); ?></option>
						</select>
						<input type='submit' name='status_change' value='<?php _e('Apply','wpShop'); ?>' />
					</div>
				</div><!-- hasadmintabs -->
			</form>
			<?php echo make_section_footer();
		break;
		case 'inventory':
			if($_GET['update'] == '1'){ inventory_amount_update(); }
			if($_GET['clean_inventory'] == '1'){ clean_inventory(); }
			if($_GET['enigma'] == '1')
			{	
				adapt_inventory2attributes();	// adds missing attr. cols to inventory table
				inventory_article_check();	
			}
			echo make_section_staff_header('inventory'); ?>
			<div class="tablenav">
				<div class="alignleft actions">
					<?php
						echo "
						<a id='nws_inv_return' class='button-secondary action' href='?page=functions.php&section=inventory&clean_inventory=1' title='".__('Returns Products from "abandoned" Carts.','wpShop')."'>".__('Return Stock','wpShop')."</a>
						<a id='nws_inv_refresh' class='button-secondary action' href='?page=functions.php&section=inventory&enigma=1' title='".__('Refresh if you have added a New Product and/or Attribute (Product Variation)','wpShop')."'>".__('Refresh List','wpShop')."</a>
						<form class='nws_search nws_inv_search' action='?page=functions.php&section=inventory' method='get'>
							<input type='hidden' name='page' value='functions.php' />
							<input type='hidden' name='section' value='inventory' />
							<input type='text' name='art_wanted' value='$_GET[art_wanted]' maxlength='255' />
							<input class='button-secondary action' type='submit' name='search_inv' value='Search' /><br/>
							<small>".__('Enter at least the first 3 digits of a Product ID_item','wpShop')."</small>
						</form>
						";
					?>	
				</div>
				<div class='tablenav-pages' style="float:left;">
					<?php NWS_inventory_pagination(20); ?>	
				</div>
			</div>
			<?php 
			// get all articles				
			echo "<div id='nws_inv_wrap'>";
				$res 		= inventory_main_query(pagination_limit_clause(20));
				while($row = mysql_fetch_assoc($res))
				{						
				$show_not1 = (strlen(get_custom_field2($row['post_id'],'item_file')) > 1 ? 1 : 0);	
				$show_not2 = (strlen(get_custom_field2($row['post_id'],'buy_now')) > 1 ? 1 : 0);	
					if(($show_not1 == 0)&&($show_not2 == 0)){										
						$product_title 	= $row['post_title'];		
						$product_image	= inventory_product_image($row['post_id']);
						echo "
						<form class='nws_inv_prod' action='?page=functions.php&section=inventory&update=1' method='post' style='border: 1px solid gainsboro;'>
							<h4 class='nws_inv_prod_ID'>".__('Article No.','wpShop').": $row[meta_value]</h4> <input class='nws_inv_prod_update' type='submit' name='submit_this' value='".__('update','wpShop')."' />
							<div class='nws_inv_details'>
								<p class='nws_inv_prod_title'>$product_title";
									if(strlen($product_image) > 1){
										echo " | <a href='$product_image' title='$product_title' class='thickbox'><img src='images/media-button-image.gif' alt='".__('Product Image','wpShop')."' /></a>";
									}
								echo "</p>";								
								if(inventory_has_attributs($row[post_id]) != 0){	// attributes - yes/no
									if($row_head !== FALSE){ // the array is not empty
										echo "<table>";
										echo header_for_attributes($row[meta_value]);
										echo display_attributes_data($row[meta_value],inventory_order_clause());									
										echo "</table>";
									}
									else { echo __('Please refresh your inventory!','wpShop');}
								}
								else {
									$check_inv_amount = $wpdb->get_var(sprintf("SELECT COUNT(iid) FROM %swps_inventory WHERE ID_item = '%s'", $wpdb->prefix, $row[meta_value]));
									if ($check_inv_amount == 0) {
										$insert = array();
										$insert["Size"] = '';
										$insert["Material"] = '';
										$insert["Colour"] = '';
										$insert["Duration"] = '';
										$insert["ID_item"] = $row['meta_value'];
										$insert["amount"] = '0';
										$wpdb->insert($wpdb->prefix."wps_inventory", $insert);
									}
									$res2 = display_amount($row[meta_value]);
									echo "<label>".__('Amount: ','wpShop')."</label>";
									while($row2 = mysql_fetch_assoc($res2)){								
										echo "<input type='text' name='$row2[iid]' value='$row2[amount]' />";
									}
								}
							echo "</div>
						</form>
						";
					}
				}
			echo "</div>";
			echo make_section_footer();	
		break;
		case 'pricing':
			echo make_section_staff_header('pricing');

			$templurl = get_bloginfo('template_url');
			$p_categories = get_categories('hide_empty=0');
			$p_brands = get_terms('brand', 'hide_empty=0');
			$p_selections = get_terms('selection', 'hide_empty=0');
			$p_colours = get_terms('colour', 'hide_empty=0');

			$s_category = $_GET['s_category'];
			$s_brand = $_GET['s_brand'];
			$s_selection = $_GET['s_selection'];
			$s_colour = $_GET['s_colour'];
			$s_style = trim($_GET['s_style']);
			$pg = $_GET['pg'];
			?>
			<h2 style="padding:0px;"><?php _e('Pricing Database','wpShop'); ?> <a class="add-new-h2 pricing-add-icon thickbox" href="#TB_inline?height=500&width=900&inlineId=pricing-form" title="Add New Pricing">Add New</a></h2>
			<div class="tablenav">
				<div class="alignleft actions">
					<form action="?page=functions.php&section=pricing">
						<input type="hidden" name="page" value="functions.php" />
						<input type="hidden" name="section" value="pricing" />
						<?php wp_dropdown_categories('hide_empty=0&name=s_category&orderby=name&class=&hierarchical=1&show_option_none=-- Select Category --'); ?>
						<select name="s_brand">
							<option value="">-- Select Brand --</option>
							<?php foreach($p_brands as $p_brand) { $s = ''; if ($p_brand->term_id == $s_brand) { $s = ' SELECTED'; } ?>
							<option value="<?php echo $p_brand->term_id; ?>"<?php echo $s; ?>><?php echo $p_brand->name; ?></option>
							<?php } ?>
						</select>
						<select name="s_selection">
							<option value="">-- Select Condition --</option>
							<?php foreach($p_selections as $p_selection) { $s = ''; if ($p_selection->term_id == $s_selection) { $s = ' SELECTED'; } ?>
							<option value="<?php echo $p_selection->term_id; ?>"<?php echo $s; ?>><?php echo $p_selection->name; ?></option>
							<?php } ?>
						</select>
						<select name="s_colour">
							<option value="">-- Select Colour --</option>
							<?php foreach($p_colours as $p_colour) { $s = ''; if ($p_colour->term_id == $s_colour) { $s = ' SELECTED'; } ?>
							<option value="<?php echo $p_colour->term_id; ?>"<?php echo $s; ?>><?php echo $p_colour->name; ?></option>
							<?php } ?>
						</select>
						<input type="text" name="s_style" value="<?php echo $s_style; ?>" placeholder="Style Name">
						<input class="button-secondary action" type="submit" value="Search" />
					</form>
				</div>
			</div>
			<table border="1" class="pricing-list">
				<tr class="heads">
					<td><strong>Category</strong></td>
					<td><strong>Brand</strong></td>
					<td><strong>Style Name</strong></td>
					<td><strong>Condition</strong></td>
					<td><strong>Colour</strong></td>
					<td><strong>Orig. Price</strong></td>
					<td><strong>High Price</strong></td>
					<td><strong>Low Price</strong></td>
					<td><strong>Inc.Box</strong></td>
					<td><strong>Inc.Inv</strong></td>
					<td><strong>Inc.Dust</strong></td>
					<td><strong>Inc.Card</strong></td>
					<td><strong>Inc.Book</strong></td>
					<td><strong>Inc.LCP</strong></td>
					<td><strong>Photo</strong></td>
					<td><strong>Action</strong></td>
				</tr>
				<?php
				$pwhere = "";
				if ($s_category > 0) { if (strlen($pwhere)) { $pwhere .= " AND "; } $pwhere .= " p.category = ".$s_category; }
				if (strlen($s_brand)) { if (strlen($pwhere)) { $pwhere .= " AND "; } $pwhere .= " p.brand = ".$s_brand; }
				if (strlen($s_selection)) { if (strlen($pwhere)) { $pwhere .= " AND "; } $pwhere .= " p.selection = ".$s_selection; }
				if (strlen($s_colour)) { if (strlen($pwhere)) { $pwhere .= " AND "; } $pwhere .= " p.colour = ".$s_colour; }
				if (strlen($s_style)) { if (strlen($pwhere)) { $pwhere .= " AND "; } $pwhere .= " p.style_name LIKE '%".$s_style."%'"; }
				if (strlen($pwhere)) { $pwhere = " WHERE ".$pwhere; }

				$pper_page = 100;
				if (!strlen($pg)) { $pg = 1; }
				$plimit = " LIMIT ".(($pg - 1) * $pper_page).", ".$pper_page;

				$psql = sprintf("SELECT SQL_CALC_FOUND_ROWS p.*, cat.name as category_name, br.name as brand_name, sel.name as selection_name, cl.name as colour_name FROM %swps_pricing p 
				LEFT JOIN %sterms cat ON cat.term_id = p.category
				LEFT JOIN %sterms br ON br.term_id = p.brand
				LEFT JOIN %sterms sel ON sel.term_id = p.selection
				LEFT JOIN %sterms cl ON cl.term_id = p.colour
				%s
				ORDER BY cat.name, br.name %s", $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $pwhere, $plimit);
				$pricing_records = $wpdb->get_results($psql);
				$pricing_total = $wpdb->get_var("SELECT FOUND_ROWS()");
				if ($pricing_total) {
					foreach($pricing_records as $pricing_record) {
						$pricing_data = $pricing_record->category.';'.$pricing_record->brand.';'.$pricing_record->style_name.';'.$pricing_record->selection.';'.$pricing_record->colour.';'.$pricing_record->original_price.';'.$pricing_record->high_price.';'.$pricing_record->low_price.';'.$pricing_record->includes_box.';'.$pricing_record->includes_invoice.';'.$pricing_record->includes_dustbag.';'.$pricing_record->includes_card.';'.$pricing_record->includes_booklet.';'.$pricing_record->includes_packaging.';'.$pricing_record->photo.';'.$pricing_record->notes.';'.$pricing_record->metal.';'.$pricing_record->material.';'.$pricing_record->movement;
				?>
				<tr>
					<td><?php echo $pricing_record->category_name; ?></td>
					<td><?php echo $pricing_record->brand_name; ?></td>
					<td><?php echo $pricing_record->style_name; ?></td>
					<td><?php echo $pricing_record->selection_name; ?></td>
					<td><?php echo $pricing_record->colour_name; ?></td>
					<td><?php echo $pricing_record->original_price; ?></td>
					<td><?php echo $pricing_record->high_price; ?></td>
					<td><?php echo $pricing_record->low_price; ?></td>
					<td><?php echo $pricing_record->includes_box; ?></td>
					<td><?php echo $pricing_record->includes_invoice; ?></td>
					<td><?php echo $pricing_record->includes_dustbag; ?></td>
					<td><?php echo $pricing_record->includes_card; ?></td>
					<td><?php echo $pricing_record->includes_booklet; ?></td>
					<td><?php echo $pricing_record->includes_packaging; ?></td>
					<td><?php if (strlen($pricing_record->photo)) { ?><a href="<?php echo $pricing_record->photo; ?>" class="thickbox">view</a><?php } else { echo '-'; } ?></td>
					<td><a href="#TB_inline?height=500&width=900&inlineId=pricing-form" title="Edit Pricing" class="thickbox"><img src="<?php echo $templurl; ?>/images/pr-edit.png" class="pricing-edit-icon" rel="<?php echo $pricing_record->pid; ?>"></a>&nbsp;&nbsp;<img src="<?php echo $templurl; ?>/images/pr-del.png" title="Delete Pricing" class="pricing-delete-icon" rel="<?php echo $pricing_record->pid; ?>"><input type="hidden" name="pdata" id="pricing-data-<?php echo $pricing_record->pid; ?>" value="<?php echo $pricing_data; ?>"></td>
				</tr>
				<?php
					}
				} else {
				?>
				<tr>
					<td colspan="16">No records.</td>
				</tr>
				<?php
				}
				?>
			</table>
			<?php
			if ($pricing_total > $pper_page) {
				$ptotal_pages = ceil($pricing_total / $pper_page); ?>
				<div class="tablenav">
					<div class='tablenav-pages'>
						<?php if ($pg > 1) { ?><a href="admin.php?page=functions.php&section=pricing&pg=<?php echo ($pg - 1); ?>" class="prev page-numbers">prev</a><?php } ?>
						<?php for ($p=1; $p<=$ptotal_pages; $p++) {
							if ($p == $pg) { ?>
								<span class="page-numbers current"><?php echo $p; ?></span>
							<?php } else { ?>
								<a href="admin.php?page=functions.php&section=pricing&pg=<?php echo $p; ?>" class="page-numbers"><?php echo $p; ?></a>
							<?php } ?>
						<?php } ?>
						<?php if (($pg + 1) <= $ptotal_pages) { ?><a href="admin.php?page=functions.php&section=pricing&pg=<?php echo ($pg + 1); ?>" class="next page-numbers">next</a><?php } ?>
					</div>
				</div>
			<?php } ?>
			<form method="POST" action="admin.php?page=functions.php&section=pricing<?php if ($pg > 1) { echo '&pg='.$pg; } ?>" id="pricing-delete-form">
				<input type="hidden" name="PricingAction" value="delete">
				<input type="hidden" name="pid" id="pricing-del-pid">
			</form>
			<div id="pricing-form" style="display:none;">
				<form method="POST" action="admin.php?page=functions.php&section=pricing<?php if ($pg > 1) { echo '&pg='.$pg; } ?>" class="pricing-form" enctype="multipart/form-data">
					<input type="hidden" name="PricingAction" value="add" id="pricing-action">
					<input type="hidden" name="pid" id="pricing-pid">
					<ul class="left-side">
						<li><label>Category:</label>
						<?php wp_dropdown_categories('hide_empty=0&name=p_category&orderby=name&class=&hierarchical=1&show_option_none=-- Select Category --'); ?></li>
						<li><label>Brand:</label>
						<select name="p_brand" id="p-brand">
							<option value="">-- Select Brand --</option>
							<?php foreach($p_brands as $p_brand) { ?>
							<option value="<?php echo $p_brand->term_id; ?>"><?php echo $p_brand->name; ?></option>
							<?php } ?>
						</select></li>
						<li><label>Style Name:</label>
						<input type="text" name="p_style_name" id="p-style-name"></li>
						<li><label>Condition:</label>
						<select name="p_selection" id="p-selection">
							<option value="">-- Select Condition --</option>
							<?php foreach($p_selections as $p_selection) { ?>
							<option value="<?php echo $p_selection->term_id; ?>"><?php echo $p_selection->name; ?></option>
							<?php } ?>
						</select></li>
						<li><label>Colour:</label>
						<select name="p_colour" id="p-colour">
							<option value="">-- Select Colour --</option>
							<?php foreach($p_colours as $p_colour) { ?>
							<option value="<?php echo $p_colour->term_id; ?>"><?php echo $p_colour->name; ?></option>
							<?php } ?>
						</select></li>
						<li><label>Metal:</label>
						<select name="p_metal[]" id="p-metal" size="5" multiple style="height:64px;">
							<?php $mcatoptions = sellers_get_category_options('metal');
							foreach($mcatoptions as $mop) { ?>
							<option value="<?php echo $mop; ?>"><?php echo $mop; ?></option>
							<?php } ?>
						</select>
						</li>
						<li><label>Material:</label>
						<select name="p_material[]" id="p-material" size="5" multiple style="height:64px;">
							<?php $mcatoptions = sellers_get_category_options('material');
							foreach($mcatoptions as $mop) { ?>
							<option value="<?php echo $mop; ?>"><?php echo $mop; ?></option>
							<?php } ?>
						</select>
						</li>
						<li><label>Movement:</label>
						<select name="p_movement[]" id="p-movement" size="5" multiple style="height:64px;">
							<?php $mcatoptions = sellers_get_category_options('movement');
							foreach($mcatoptions as $mop) { ?>
							<option value="<?php echo $mop; ?>"><?php echo $mop; ?></option>
							<?php } ?>
						</select>
						</li>
					</ul>
					<ul class="center-side">
						<li><label>Original Price:</label>
						<input type="text" name="p_original_price" id="p-original-price"> USD</li>
						<li><label>High Price:</label>
						<input type="text" name="p_high_price" id="p-high-price" onblur="pricing_low_price()"> USD</li>
						<li><label>Low Price:</label>
						<input type="text" name="p_low_price" id="p-low-price"> USD</li>
						<?php $sellers_includes = sellers_get_includes();
						foreach($sellers_includes as $si_key => $si_val) { ?>
						<li><label><?php echo $si_val; ?> Inc:</label>
						<input type="text" name="p_includes_<?php echo $si_key; ?>" id="p-includes-<?php echo $si_key; ?>"> USD</li>
						<?php } ?>
						<li style="margin-top:45px;"><input type="submit" value="&nbsp;&nbsp;Submit&nbsp;&nbsp;"></li>
					</ul>
					<ul class="right-side">
						<li><label>Notes:</label>
						<textarea name="p_notes" id="p-notes" style="width:200px;height:80px;"></textarea></li>
						<li><label>Photo Upload:</label>
						<input type="file" name="p_photo"></li>
						<li id="p-photo" style="display:none;"><label>Photo:</label>
						<img src="" id="p-photo-img" style="height:150px;"></li>
					</ul>
				</form>
			</div>
			<?php
			echo make_section_footer();
		break;
		default:
			global $OPTION;
			$STATISTICS = load_what_is_needed('statistics');
			echo make_section_staff_header('statistics');		
			$currency 	= $OPTION['wps_currency_code'];
			$header_str	= __('Sales in %CURRENCY% :: Development last 12 months','wpShop');					
			$header 	= str_replace("%CURRENCY%",$currency,$header_str);		
			echo "<h3>".__('Statistics','wpShop')."</h3>";	
			echo $STATISTICS->graph_monthly_sales('25px','black');		
			echo make_section_footer();
		
			display_tax_data_backend();
		break;
	}
} // editor options

##################################################################################################################################
// 												DASHBOARD -WIDGETS
##################################################################################################################################

/**
 * Content of Dashboard-Widget
 */
function NWS_dashboard() {
	global $CONFIG_WPS,$OPTION; 
	//collect info
	if($OPTION['wps_shoppingCartEngine_yes']) {
	
		if($OPTION['wps_shop_mode'] == 'Normal shop mode'){ 
			$totalOrders 		= NWS_total_orders_there();
			$totalEarnings 		= NWS_total_earnings_there();
		} elseif($OPTION['wps_shop_mode'] == 'Inquiry email mode'){
			$totalEnquiries 	= NWS_total_enquiries_there(); 
		} else {}
		
		if($OPTION['wps_track_inventory'] == 'active'){
			$totalProds 		= NWS_total_prods_there();
		}
		
		if ($OPTION['wps_voucherCodes_enable']) {
			$totalVouchers 		= NWS_total_vouchers_there();
		}
		
		if($OPTION['wps_lrw_yes']) {
			$totalMembers 		= NWS_total_members_there();
			$activeWishlists	= NWS_activeWishlists_there();
		}
	} ?>
	
	<div class="clearfix">
		<?php 
		
		if($OPTION['wps_dash_widget'] !='') { echo $OPTION['wps_dash_widget'];}
		
		//using the shopping cart?
		if($OPTION['wps_shoppingCartEngine_yes']) {
			if($OPTION['wps_shop_mode'] == 'Normal shop mode'){
				echo "<p>
					<img class='new_orders_img' src='../wp-content/themes/".$CONFIG_WPS['themename']."/images/admin/lightbulb_48.png' border='0'/>";
				
					if($totalOrders['new'] == 1){
						$txt = __('New Order to be Processed','wpShop');
					}
					if($totalOrders['new'] > 1){
						$txt = __('New Orders to be Processed','wpShop');
					}
					if($totalOrders['new'] == 0){
						$txt = __('No New Orders!','wpShop');
					} 
					echo __('You have','wpShop')."<strong> ".$totalOrders['new']." </strong>".$txt;
					
				echo "</p>";
				echo "<p>".__('You can manage your orders from the','wpShop')." <a href='".get_option('siteurl') ."/wp-admin/themes.php?page=functions.php&section=orders'>".__('"Manage Orders"','wpShop')."</a> ".__('panel.','wpShop')."</p>";
				
			} elseif($OPTION['wps_shop_mode'] == 'Inquiry email mode'){
				echo "<p>
					<img class='new_orders_img' src='../wp-content/themes/".$CONFIG_WPS['themename']."/images/admin/lightbulb_48.png' border='0'/>";
				
					if($totalEnquiries['new'] == 1){
						$txt = __('New Enquiry to be Processed','wpShop');
					}
					if($totalEnquiries['new'] > 1){
						$txt = __('New Enquiries to be Processed','wpShop');
					}
					if($totalEnquiries['new'] == 0){
						$txt = __('No New Enquiries!','wpShop');
					} 
					echo __('You have','wpShop')."<strong> ".$totalEnquiries['new']." </strong>".$txt;
				echo "</p>";
				echo "<p>".__('You can manage your Enquiries from the','wpShop')." <a href='".get_option('siteurl') ."/wp-admin/themes.php?page=functions.php&section=inquiries'>".__('"Manage Enquiries"','wpShop')."</a> ".__('panel.','wpShop')."</p>";
			
			} ?>
			
			<div class="at_a_glance orders_at_a_glance clearfix">
				<?php if($OPTION['wps_shop_mode'] == 'Normal shop mode'){ ?>
					<h4><?php _e('Orders Overview','wpShop');?></h4>
				<?php } elseif($OPTION['wps_shop_mode'] == 'Inquiry email mode'){ ?>
					<h4><?php _e('Enquiries Overview','wpShop');?></h4>
				<?php } ?>
				
				<table class="widefat">
					<thead>
						<tr class="alternate">
						<?php if($OPTION['wps_shop_mode'] == 'Normal shop mode'){
							if($totalOrders['pending']){ ?>
							<th><?php _e('Pending Payments','wpShop');?></th>
							<?php } ?>
							<th><?php _e('New','wpShop');?></th>
							<th><?php _e('In Process','wpShop');?></th>
							<th><?php _e('Shipped','wpShop');?></th>
							<th><?php _e('Completed','wpShop');?></th>
							
						<?php } elseif($OPTION['wps_shop_mode'] == 'Inquiry email mode'){ ?>
							<th><?php _e('New','wpShop');?></th>
							<th><?php _e('Replied To','wpShop');?></th>
						<?php } ?>
							<th><?php _e('Total','wpShop');?></th>
						</tr>
					</thead>
					
					<tbody>
					   <tr>
					   <?php 
					   if($OPTION['wps_shop_mode'] == 'Normal shop mode'){
							if($totalOrders['pending']){ ?>
							<td><p><?php if ($totalOrders['pending']) { echo $totalOrders['pending']; } else { echo __('0','wpShop'); } ?></p></td>
							<?php } ?>
							<td><p><?php if ($totalOrders['new']) { echo $totalOrders['new']; } else { echo __('0','wpShop'); } ?></p></td>
							<td><p><?php if ($totalOrders['in_process']) { echo $totalOrders['in_process']; } else { echo __('0','wpShop'); } ?></p></td>
							<td><p><?php if ($totalOrders['shipped']) { echo $totalOrders['shipped']; } else { echo __('0','wpShop'); } ?></p></td>
							<td><p><?php if ($totalOrders['completed']) { echo $totalOrders['completed']; } else { echo __('0','wpShop'); } ?></p></td>
							<td><p><?php if ($totalOrders['all']) { echo $totalOrders['all']; } else { echo __('0','wpShop'); } ?></p></td>
						<?php } elseif($OPTION['wps_shop_mode'] == 'Inquiry email mode'){ ?>
							<td><p><?php if ($totalEnquiries['new']) { echo $totalEnquiries['new']; } else { echo __('0','wpShop'); } ?></p></td>
							<td><p><?php if ($totalEnquiries['replied']) { echo $totalEnquiries['replied']; } else { echo __('0','wpShop'); } ?></p></td>
							<td><p><?php if ($totalEnquiries['all']) { echo $totalEnquiries['all']; } else { echo __('0','wpShop'); } ?></p></td>
						<?php } ?>
						</tr>
					</tbody>
				</table>
				<?php if($OPTION['wps_shop_mode'] == 'Normal shop mode'){
					$link_txt = __('Manage Orders','wpShop');
					$link = get_option('siteurl').'/wp-admin/admin.php?page=functions.php&section=orders';
				} elseif($OPTION['wps_shop_mode'] == 'Inquiry email mode'){
					$link_txt = __('Manage Enquiries','wpShop');
					$link = get_option('siteurl').'/wp-admin/admin.php?page=functions.php&section=inquiries';
				} ?>
				<a class="button-secondary" href="<?php echo $link;?>"><?php echo $link_txt;?></a>
			</div><!-- at_a_glance-->
			
			<?php 
			// if using the Regular Shop Mode
			if($OPTION['wps_shop_mode'] == 'Normal shop mode'){ ?>
				<div class="at_a_glance sales_at_a_glance clearfix">
					<h4><?php _e('Earnings Overview','wpShop');?></h4>
					
					<table class="widefat">
						<thead>
							<tr class="alternate">
								<th><?php _e('Today','wpShop');?></th>
								<th><?php _e('This Week','wpShop');?></th>
								<th><?php _e('This Month','wpShop');?></th>
								<th><?php _e('This Year','wpShop');?></th>
								<th><?php _e('All Time','wpShop');?></th>
							</tr>
						</thead>
						
						<tbody>
						   <tr>
								<td><p><?php if ($totalEarnings['today']) { echo '$'.format_price($totalEarnings['today']); } else { echo __('No Sales yet Today','wpShop'); }?></p></td>
								<td><p><?php if ($totalEarnings['week']) { echo '$'.format_price($totalEarnings['week']); } else { echo __('No Sales yet this Week','wpShop'); }?></p></td>
								<td><p><?php if ($totalEarnings['month']) { echo '$'.format_price($totalEarnings['month']); } else { echo __('No Sales yet this Month','wpShop'); }?></p></td>
								<td><p><?php if ($totalEarnings['year']) { echo '$'.format_price($totalEarnings['year']); } else { echo __('No Sales yet this Year','wpShop'); }?></p></td>
								<td><p><?php if ($totalEarnings['all']) { echo '$'.format_price($totalEarnings['all']); } else { echo __('No Sales yet','wpShop'); }?></p></td>
						   </tr>
						</tbody>
					</table>
				</div><!-- at_a_glance-->
			<?php } 
			
			//if inventory tracking is on
			if($OPTION['wps_track_inventory'] == 'active'){ ?>
				<div class="at_a_glance inventory_at_a_glance clearfix">
					<h4><?php _e('Inventory Overview','wpShop');?></h4>
					
					<table class="widefat">
						<thead>
							<tr class="alternate">
								<th><?php _e('Product Variations','wpShop');?></th>
								<th><?php _e('Low in Stock','wpShop');?></th>
								<th><?php _e('Out of Stock','wpShop');?></th>
							</tr>
						</thead>
						
						<tbody>
						   <tr>
								<td><p><?php if ($totalProds['all']) { echo $totalProds['all']; } else { echo __('None Found','wpShop'); }?></p></td>
								<td><p><?php if ($totalProds['low']) { echo $totalProds['low']; } else { echo __('None Low in Stock','wpShop'); }?></p></td>
								<td><p><?php if ($totalProds['out']) { echo $totalProds['out']; } else { echo __('None Out of Stock','wpShop'); }?></p></td>
						   </tr>
						</tbody>
					</table>
					<?php
					$link_txt = __('Manage Inventory','wpShop');
					$link = get_option('siteurl').'/wp-admin/admin.php?page=functions.php&section=inventory';
					?>
					<a class="button-secondary" href="<?php echo $link;?>"><?php echo $link_txt;?></a>
				</div><!-- at_a_glance-->
			<?php }
			
			//if vouchers are used
			if ($OPTION['wps_voucherCodes_enable']) { ?>
				<div class="at_a_glance vouchers_at_a_glance clearfix">
					<h4><?php _e('Voucher Overview','wpShop');?></h4>
					
					<table class="widefat">
						<thead>
							<tr class="alternate">
								<th><?php _e('Total','wpShop');?></th>
								<th><?php _e('Single-Use','wpShop');?></th>
								<th><?php _e('Multi-Use','wpShop');?></th>
							</tr>
						</thead>
						
						<tbody>
						   <tr>
								<td><p><?php echo $totalVouchers['all']; ?></p></td>
								<td><p><?php echo $totalVouchers['single_use']; ?></p></td>
								<td><p><?php echo $totalVouchers['multi_use']; ?></p></td>
						   </tr>
						</tbody>
					</table>
					<?php
					$link_txt = __('Manage Vouchers','wpShop');
					$link = get_option('siteurl').'/wp-admin/admin.php?page=functions.php&section=vouchers';
					?>
					<a class="button-secondary" href="<?php echo $link;?>"><?php echo $link_txt;?></a>
				</div><!-- at_a_glance-->
				
			<?php }
			// using the membership area?
			if($OPTION['wps_lrw_yes']) { ?>
				<div class="at_a_glance members_at_a_glance clearfix">
					<h4><?php _e('Members Overview (Registered Customers)','wpShop');?></h4>
					
					<table class="widefat">
						<thead>
							<tr class="alternate">
								<th><?php _e('Total Registered','wpShop');?></th>
								<th><?php _e('Active Wishlists','wpShop');?></th>
							</tr>
						</thead>
						
						<tbody>
						   <tr>
								<td><p><?php if ($totalMembers['all']) { echo $totalMembers['all']; } else { echo __('No Registered Members','wpShop'); }?></p></td>
								<td><p><?php if ($activeWishlists != '') { echo $activeWishlists; } else { echo __('None Active','wpShop'); }?></p></td>
							 </tr>
						</tbody>
					</table>
					<?php
					$link_txt = __('Manage Members','wpShop');
					$link = get_option('siteurl').'/wp-admin/admin.php?page=functions.php&section=members';
					?>
					<a class="button-secondary" href="<?php echo $link;?>"><?php echo $link_txt;?></a>
				</div><!-- at_a_glance-->
				
			<?php }
		} ?>
	</div>
<?php }
 
/**
 * add Dashboard Widget via function wp_add_dashboard_widget()
 */
function NWS_wp_dashboard_Init() {
	global $CONFIG_WPS;
	//only display for Admin
	if (current_user_can('level_10')) {
		wp_add_dashboard_widget( 'NWS_dashboard', __( 'Welcome to ' ,'wpShop').$CONFIG_WPS['themename'].__( ' Dashboard' ,'wpShop'), 'NWS_dashboard');
	}
}
 
/**
 * use hook, to integrate new widget
 */
add_action('wp_dashboard_setup', 'NWS_wp_dashboard_Init'); 


function NWS_hook_module($identifier,$module_data){

	global $options;

	//where in the $options array do we add the module data
	$counter 	= 1;
	$position 	= 0;
	
	foreach($options as $k => $v){
		if($k == $identifier){
			$position = $counter;
		}
		$counter++;
	}
	
	//now add module data into array
	if($position !== 0){
		array_splice($options,$position,1,$module_data);
	}
	else {
		echo 'No hook for ' . $identifier . ' found.';
	}

return $position;
}

NWS_hook_module('tax_module_data_follows',$tax_MODULE_DATA);

function NWS_pricing_database() {
}

function NWS_searches_statistics() {
}
?>