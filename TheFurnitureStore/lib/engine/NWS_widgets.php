<?php
##################################################################################################################################
// 												Register Widget Areas
##################################################################################################################################

// Register widgetized areas - upto 29!
function theme_widgets_init() {
	
	global $OPTION;

// header
	register_sidebar( array (
		'name' 			=> 'Header Ad',
		'id' 			=> 'header_widget_area',
		'before_widget' => '<div id="%1$s" class="headerAd widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	));

	register_sidebar( array (
		'name' 			=> 'Header Top Menu',
		'id' 			=> 'header_top_menu_area',
		'before_widget' => '',
		'after_widget' 	=> '',
		'before_title' 	=> '',
		'after_title' 	=> '',
	));

// index page sidebar
	register_sidebar( array (
		'name' 			=> 'Frontpage Aside',
		'id' 			=> 'frontpage_widget_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	));

	// Frontpage Bottom
	register_sidebar( array (
		'name' 			=> 'Frontpage Bottom',
		'id' 			=> 'frontpage_bottom_widget_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3>',
		'after_title' 	=> '</h3>',
	));
	// Frontpage Middle
	register_sidebar( array (
		'name' 			=> 'Frontpage Middle',
		'id' 			=> 'frontpage_middle_widget_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	));

// index page 3 col left
	register_sidebar( array (
		'name' 			=> 'Frontpage Bottom Left(3 col)',
		'id' 			=> 'frontpage_3left_widget_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	));
	
// index page 3 col middle
	register_sidebar( array (
		'name' 			=> 'Frontpage Bottom Middle(3 col)',
		'id' 			=> 'frontpage_3middle_widget_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	));
	
// index page 3 col right
	register_sidebar( array (
		'name' 			=> 'Frontpage Bottom Right(3 col)',
		'id' 			=> 'frontpage_3right_widget_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	));
	
// index page 2 col left
	register_sidebar( array (
		'name' 			=> 'Frontpage Bottom Left(2 col)',
		'id' 			=> 'frontpage_2left_widget_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	));
	
// index page 2 col right
	register_sidebar( array (
		'name' 			=> 'Frontpage Bottom Right(2 col)',
		'id' 			=> 'frontpage_2right_widget_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	));
	
// index page single
	register_sidebar( array (
		'name' 			=> 'Frontpage Bottom Single(1 col)',
		'id' 			=> 'frontpage_single_widget_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	));
	
// page
	register_sidebar( array (
		'name' 			=> 'Page',
		'id' 			=> 'page_widget_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	));

if($OPTION['wps_aboutPg']!='Select a Page') {	
// about page
	register_sidebar( array (
		'name' 			=> 'About Page',
		'id' 			=> 'about_page_widget_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	));
}

if($OPTION['wps_contactPg']!='Select a Page') {		
// contact page
	register_sidebar( array (
		'name' 			=> 'Contact Page',
		'id' 			=> 'contact_page_widget_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	));
}

if($OPTION['wps_lrw_yes']) {

	if($OPTION['wps_pgNavi_regOption']!='Select a Page') {	
		// account register
		register_sidebar( array (
			'name' 			=> 'Account Register Page',
			'id' 			=> 'account_reg_widget_area',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div>',
			'before_title' 	=> '<h2>',
			'after_title' 	=> '</h2>',
		));
	}

	if($OPTION['wps_pgNavi_logOption']!='Select a Page') {
	
		// account sign in
		register_sidebar( array (
			'name' 			=> 'Account Login Page',
			'id' 			=> 'account_log_widget_area',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div>',
			'before_title' 	=> '<h2 class="widget-title">',
			'after_title' 	=> '</h2>',
		));
	}

	if($OPTION['wps_customerAreaPg']!='Select a Page') {
		// customer area
		register_sidebar( array (
			'name' 			=> 'Customer Membership Area',
			'id' 			=> 'customer_area_widget_area',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div>',
			'before_title' 	=> '<h2 class="widget-title">',
			'after_title' 	=> '</h2>',
		));
	}
}

if($OPTION['wps_customerServicePg']!='Select a Page') {
// main customer service
	register_sidebar( array (
		'name' 			=> 'Customer Service Main Page',
		'id' 			=> 'main_customer_service_widget_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	));
	
// sub customer service
	register_sidebar( array (
		'name' 			=> 'Customer Service SubPage',
		'id' 			=> 'sub_customer_service_widget_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	));
}

// search
	register_sidebar( array (
		'name' 			=> 'Search Page',
		'id' 			=> 'search_widget_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	));

// page 404
	register_sidebar( array (
		'name' 			=> 'Page 404',
		'id' 			=> 'page404_widget_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	));
	
// specific categories - Blog
if ($OPTION['wps_blogCat']!= 'Select a Category') {
	if ($OPTION['wps_blog_indSingle_sidebar_enable']) {
		register_sidebar( array (
			'name' 			=> 'Blog Category Pages',
			'id' 			=> 'blog_category_widget_area',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div>',
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
		));
	} else {
		register_sidebar( array (
			'name' 			=> 'Blog Pages',
			'id' 			=> 'blog_category_widget_area',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div>',
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
		));
	}
}
	
// category
	register_sidebar( array (
		'name' 			=> 'Shop Category pages',
		'id' 			=> 'category_widget_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	));
	
if($OPTION['wps_blogTags_option']) {	

// custom taxonomies
	register_sidebar( array (
		'name' 			=> 'Custom Taxonomy pages',
		'id' 			=> 'tax_widget_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	));
	
} else {
// shop tags and custom taxonomies
	register_sidebar( array (
		'name' 			=> 'Tag and Custom Taxonomy pages',
		'id' 			=> 'tag_widget_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	));
}
	
// archive
	register_sidebar( array (
		'name' 			=> 'Archive pages',
		'id' 			=> 'archive_widget_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	));
	
// independent single - Blog
if ($OPTION['wps_blog_indSingle_sidebar_enable']) {
	register_sidebar( array (
		'name' 			=> 'Blog Single Pages',
		'id' 			=> 'single_blog_widget_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	));
}


if ($OPTION['wps_shop_single_sidebar_enable']) {
// single
	register_sidebar( array (
		'name' 			=> 'Single pages',
		'id' 			=> 'single_widget_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	));
}
	
//have we activated the large footer option?
if ($OPTION['wps_footer_option'] =='large_footer') {
	
// footer left
	register_sidebar( array (
		'name' 			=> 'Footer Left(3 col)',
		'id' 			=> 'footer_left_widget_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	));	
	
// footer middle
	register_sidebar( array (
		'name' 			=> 'Footer Middle(3 col)',
		'id' 			=> 'footer_middle_widget_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	));	
	
// footer right
	register_sidebar( array (
		'name' 			=> 'Footer Right(3 col)',
		'id' 			=> 'footer_right_widget_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	));
	register_sidebar( array (
		'name' 			=> 'Footer Right(Checkout)',
		'id' 			=> 'footer_right_checkout_widget_area',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	));

}	
	// Footer Copyright Text
	register_sidebar( array (
		'name' 			=> 'Footer Social Area',
		'id' 			=> 'footer_social_widget_area',
		'before_widget' => ' ',
		'after_widget' 	=> ' ',
		'before_title' 	=> ' ',
		'after_title' 	=> ' ',
	));

	// Footer Copyright Text
	register_sidebar( array (
		'name' 			=> 'Footer Copyright Text',
		'id' 			=> 'footer_copyright_text_widget_area',
		'before_widget' => ' ',
		'after_widget' 	=> ' ',
		'before_title' 	=> ' ',
		'after_title' 	=> ' ',
	));
} // end theme_widgets_init

//add_action( 'init', 'theme_widgets_init' );

add_action( 'widgets_init', 'theme_widgets_init' );

// Check for static widgets in widget-ready areas
function is_sidebar_active( $index ){
  global $wp_registered_sidebars;

  $widgetcolums = wp_get_sidebars_widgets();
                 
	if ($widgetcolums[$index]){ 
		return true;
	} else {
        return false;
	}
} // end is_sidebar_active

##################################################################################################################################
// 												WIDGETS (24 custom ones!)
##################################################################################################################################

class CategoryRssListWidget extends WP_Widget {

	function CategoryRssListWidget() {
		$widget_ops 	= array('classname' => 'widget_category_rss', 'description' => __( 'Display a category rss list', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'nws-category-rss-list');
		$this->WP_Widget('nws-category-rss-list', __('NWS Category Rss List', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
		extract($args);
		$title 		= apply_filters('widget_title', $instance['title'] );
		$orderby 	= $instance['orderby'];
		$order 		= $instance['order'];
		$include 	= $instance['include'];
		$exclude 	= $instance['exclude'];
		$feed_image = $instance['feed_image'];
		
		# Before the widget
		echo $before_widget;

		# The title
		if ( $title )
		echo $before_title . $title . $after_title;

		# Make the Category RSS List widget
		$catRssArg 	= array(
			'include'    	=> $include,
			'exclude'		=> $exclude,
			'title_li'		=> '', 
			'orderby'       => $orderby,
			'order'         => $order,
			'hide_empty'    => 0,
			'depth'			=> 1,
			'feed_image'	=> $feed_image,
			'feed'			=> 'XML Feed',
			'optioncount'	=> 1,
			'children'		=> 0
		); ?>
		<ul class="catRssFeed">
			<?php wp_list_categories($catRssArg); ?>
		</ul>
		<?php
		# After the widget
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 		= strip_tags($new_instance['title']);
		$instance['orderby'] 	= $new_instance['orderby'];
		$instance['order'] 		= $new_instance['order'];
		$instance['include'] 	= $new_instance['include'];
		$instance['exclude'] 	= $new_instance['exclude'];
		$instance['feed_image'] = $new_instance['feed_image'];
		return $instance;
	}
	
	function form($instance){
		//Set up some default widget settings.
		$defaults = array( 'title' => __('Subscribe', 'wpShop'), 'orderby' => 'name', 'order' => 'ASC', 'include' => '', 'exclude' => '', 'feed_image' => '' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'include' ); ?>"><?php _e('Include:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'include' ); ?>" name="<?php echo $this->get_field_name( 'include' ); ?>" value="<?php echo $instance['include']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'exclude' ); ?>"><?php _e('Exclude:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'exclude' ); ?>" name="<?php echo $this->get_field_name( 'exclude' ); ?>" value="<?php echo $instance['exclude']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'feed_image' ); ?>"><?php _e('Feed Image(full path):', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'feed_image' ); ?>" name="<?php echo $this->get_field_name( 'feed_image' ); ?>" value="<?php echo $instance['feed_image']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e('Order By:', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>" class="widefat" style="width:97%;">
				<option value="ID" <?php selected('ID', $instance["orderby"]); ?>><?php _e('ID', 'wpShop'); ?></option>
				<option value="name" <?php selected('name', $instance["orderby"]); ?>><?php _e('name', 'wpShop'); ?></option>
				<option value="slug" <?php selected('slug', $instance["orderby"]); ?>><?php _e('slug', 'wpShop'); ?></option>
				<option value="count" <?php selected('count', $instance["orderby"]); ?>><?php _e('count', 'wpShop'); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e('Order:', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>" class="widefat" style="width:97%;">
				<option value="ASC" <?php selected('ASC', $instance["order"]); ?>><?php _e('ASC', 'wpShop'); ?></option>
				<option value="DESC" <?php selected('DESC', $instance["order"]); ?>><?php _e('DESC', 'wpShop'); ?></option>
			</select>
		</p>
		
		
	<?php }
}

//store categories widget
class StoreCategoriesWidget extends WP_Widget {

	function StoreCategoriesWidget() {
		$widget_ops 	= array('classname' => 'widget_categories', 'description' => __( 'Display your Store Categories', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'nws-store-categories');
		$this->WP_Widget('nws-store-categories', __('NWS Store Categories', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
		extract($args);
		$title 		= apply_filters('widget_title', $instance['title'] );
		$orderby 	= $instance['orderby'];
		$order 		= $instance['order'];
		$include 	= $instance['include'];
		$exclude 	= $instance['exclude'];
		
		# Before the widget
		echo $before_widget;

		# The title
		if ( $title )
		echo $before_title . $title . $after_title;

		# Output
		$titleLi	='';
		
		if (is_category()) {
			$topParent 		= NWS_get_root_category($cat,'allData');
			$catDepth 		= get_depth($this_category->term_id);
			$depth = $catDepth + 2;
					
			$catMenuArg = array(
				'include'   		=>$include,
				'exclude'			=>$exclude,
				'title_li'			=>$titleLi,
				'orderby'			=>$orderby,
				'order'				=>$order,
				'depth'				=>$depth,
				'hide_empty'		=>0,
			);
		} else {
			$catMenuArg 	= array(
				'exclude'    	=> $exclude,
				'include'    	=> $include,
				'title_li'		=> $titleLi, 
				'orderby'       => $orderby,
				'order'         => $order,
				'depth'			=>1,
				'hide_empty'	=>0,
				
			); 
		} ?>
		
		<ul>
			<?php wp_list_categories($catMenuArg);?>
		</ul>
		
		<?php
		# After the widget
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 		= strip_tags($new_instance['title']);
		$instance['include'] 	= $new_instance['include'];
		$instance['exclude'] 	= $new_instance['exclude'];
		$instance['orderby'] 	= $new_instance['orderby'];
		$instance['order'] 		= $new_instance['order'];
		
		return $instance;
	}
	
	function form($instance){
		//Set up some default widget settings. 
		$defaults = array( 'title' => '', 'include' => '' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'include' ); ?>"><?php _e('Include:', 'wpShop'); ?></label><br/>
			<small><?php _e('Enter the Category IDs you want to include. Comma separate multiple categories. You can only use either include or exclude, not both!', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'include' ); ?>" name="<?php echo $this->get_field_name( 'include' ); ?>" value="<?php echo $instance['include']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'exclude' ); ?>"><?php _e('Exclude:', 'wpShop'); ?></label><br/>
			<small><?php _e('Enter the Category IDs you want to include. Comma separate multiple categories. You can only use either include or exclude, not both! If you are using a Blog you will probably want to exclude it here.', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'exclude' ); ?>" name="<?php echo $this->get_field_name( 'exclude' ); ?>" value="<?php echo $instance['exclude']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e('Order:', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>" class="widefat" style="width:97%;">
				<option value="ASC" <?php selected('ASC', $instance["order"]); ?>><?php _e('ASC', 'wpShop'); ?></option>
				<option value="DESC" <?php selected('DESC', $instance["order"]); ?>><?php _e('DESC', 'wpShop'); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e('Order by:', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>" class="widefat" style="width:97%;">
				<option value="name" <?php selected('name', $instance["orderby"]); ?>><?php _e('name', 'wpShop'); ?></option>
				<option value="ID" <?php selected('ID', $instance["orderby"]); ?>><?php _e('ID', 'wpShop'); ?></option>
				<option value="slug" <?php selected('slug', $instance["orderby"]); ?>><?php _e('slug', 'wpShop'); ?></option>
				<option value="count" <?php selected('count', $instance["orderby"]); ?>><?php _e('count', 'wpShop'); ?></option>
			</select>
		</p>
		
	<?php }
}

//pages widget
class PagesListWidget extends WP_Widget {

	function PagesListWidget() {
		$widget_ops 	= array('classname' => 'widget_pages_list', 'description' => __( 'Display a pages list', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'nws-pages-list');
		$this->WP_Widget('nws-pages-list', __('NWS Pages List', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
		extract($args);
		$title 		= apply_filters('widget_title', $instance['title'] );
		$orderby 	= $instance['orderby'];
		$order 		= $instance['order'];
		$include 	= $instance['include'];

		# Before the widget
		echo $before_widget;

		# The title
		if ( $title )
		echo $before_title . $title . $after_title;

		# Make the Category RSS List widget
		$pagesArg 	= array(
			'include'    	=> $include,
			'title_li'		=> '', 
			'orderby'       => 'menu_order',
			'order'         => 'asc',
			'depth'			=> -1,
			
		); ?>
		<ul class="nav-footer">
			<?php wp_list_pages($pagesArg); ?>
		</ul>
		<?php
		# After the widget
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 		= strip_tags($new_instance['title']);
		$instance['include'] 	= $new_instance['include'];
		
		return $instance;
	}
	
	function form($instance){
		//Set up some default widget settings. 
		$defaults = array( 'title' => '', 'include' => '' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'include' ); ?>"><?php _e('Include:', 'wpShop'); ?></label><br/>
			<small><?php _e('Enter the Page IDs you want to include. Comma separate multiple page ids.', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'include' ); ?>" name="<?php echo $this->get_field_name( 'include' ); ?>" value="<?php echo $instance['include']; ?>" style="width:97%;" />
		</p>
		
	<?php }
}

// the Gift Cards Widget
class GiftCardsWidget extends WP_Widget {

	function GiftCardsWidget() {
		$widget_ops 	= array('classname' => 'widget_promotions widget_gift_cards', 'description' => __( 'For Gift Cards or Certificates.', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'nws-gift-cards');
		$this->WP_Widget('nws-gift-cards', __('NWS Gift Cards & Certificates', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
		extract($args);
		$title 		= apply_filters('widget_title', $instance['title'] );
		$btnID 		= $instance['btnID'];
		$btnImg 	= $instance['btnImg'];
		$altText 	= $instance['altText'];
		
		# Before the widget
		echo $before_widget;

		# The title
		if ( $title )
		echo $before_title . $title . $after_title;

		# Output
		?>
		
		<div class="widget_promotions_imgWrap widget_promotions_alt"><a href="<?php echo $btnID;?>"><img src="<?php echo $btnImg;?>" alt="<?php echo $altText;?>"/></a></div>
	
	<?php
		# After the widget
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 		= strip_tags($new_instance['title']);
		$instance['btnID'] 		= $new_instance['btnID'];
		$instance['btnImg'] 	= $new_instance['btnImg'];
		$instance['altText'] 	= $new_instance['altText'];
		return $instance;
	}
	
	function form($instance){
		// Set up some default widget settings.
		$defaults = array( 'title' => __('', 'wpShop'), 'btnID' => '', 'btnImg' => '');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title (Optional):', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'btnID' ); ?>"><?php _e('Link to):', 'wpShop'); ?></label><br/>
			<small><?php _e('The path to the category you created with your Gift Certificate Products', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'btnID' ); ?>" name="<?php echo $this->get_field_name( 'btnID' ); ?>" value="<?php echo $instance['btnID']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'btnImg' ); ?>"><?php _e('Image File Path:', 'wpShop'); ?></label><br/>
			<small><?php _e('The path to wherever you saved your image.', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'btnImg' ); ?>" name="<?php echo $this->get_field_name( 'btnImg' ); ?>" value="<?php echo $instance['btnImg']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'altText' ); ?>"><?php _e('Image ALT Text:', 'wpShop'); ?></label><br/>
			<small><?php _e('This will be used for the image\s ALT attribute', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'altText' ); ?>" name="<?php echo $this->get_field_name( 'altText' ); ?>" value="<?php echo $instance['altText']; ?>" style="width:97%;" />
		</p>
	<?php }
}

// the Email Subscriptions Widget
class EmailSubscriptionsWidget extends WP_Widget {

	function EmailSubscriptionsWidget() {
		$widget_ops 	= array('classname' => 'widget_promotions widget_email_subscriptions', 'description' => __( 'For Feedburner Email Subsciptions', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'nws-email-subscriptions');
		$this->WP_Widget('nws-email-subscriptions', __('NWS Email Subscriptions', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
		extract($args);
		$title 			= apply_filters('widget_title', $instance['title'] );
		$subsc_message 	= $instance['subsc_message'];
		$label_text 	= $instance['label_text'];
		$feedburnerID 	= $instance['feedburnerID'];
		$lang 			= $instance['lang'];
		$btnText 		= $instance['btnText'];
		
		# Before the widget
		echo $before_widget;

		# The title
		if ( $title )
		echo $before_title . $title . $after_title;

		# Output
		?>
		<div class="subscriptions">
			<p><?php echo $subsc_message;?></p>
			<form class="clearfix" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $feedburnerID;?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
				<p><?php echo $label_text;?></p>
				<input class="input_text" type="text" name="email"/>
				<input type="hidden" value="<?php echo $feedburnerID;?>" name="uri"/>
				<input type="hidden" name="loc" value="<?php echo $lang;?>"/>
				<div class="shopform_btn subscribe_btn">								
					<input class="input_image" type="image"  name="subscribeButton" value='<?php echo $btnText;?>' src="<?php bloginfo('stylesheet_directory'); ?>/images/sign_me_up.png" />
				</div>
			</form>
		</div>
		<?php
		# After the widget
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 			= strip_tags($new_instance['title']);
		$instance['subsc_message'] 	= strip_tags($new_instance['subsc_message']);
		$instance['label_text'] 	= strip_tags($new_instance['label_text']);
		$instance['feedburnerID'] 	= $new_instance['feedburnerID'];
		$instance['lang'] 			= $new_instance['lang'];
		$instance['btnText'] 		= strip_tags($new_instance['btnText']);
		return $instance;
	}
	
	function form($instance){
		// Set up some default widget settings. 
		$defaults = array( 'title' => __('Subscribe', 'wpShop'), 'subsc_message' => __('Be the first to know about Sales, Special Offers and New Arrivals', 'wpShop'), 'label_text' => __('Enter your email', 'wpShop'), 'feedburnerID' => 'snDesign', 'lang' => 'en_US', 'btnText' => __('Sign me up!', 'wpShop'));
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'subsc_message' ); ?>"><?php _e('Message below Title', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'subsc_message' ); ?>" name="<?php echo $this->get_field_name( 'subsc_message' ); ?>" value="<?php echo $instance['subsc_message']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'label_text' ); ?>"><?php _e('Text above input field:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'label_text' ); ?>" name="<?php echo $this->get_field_name( 'label_text' ); ?>" value="<?php echo $instance['label_text']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'feedburnerID' ); ?>"><?php _e('Your Feedburner ID:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'feedburnerID' ); ?>" name="<?php echo $this->get_field_name( 'feedburnerID' ); ?>" value="<?php echo $instance['feedburnerID']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'lang' ); ?>"><?php _e('Your Language Selection:', 'wpShop'); ?></label><br/>
			<small><?php _e('When you set up your email subscriptions with Feedburner, they give you the option to select the language in which the popup information will appear in. See the help file for more details.', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'lang' ); ?>" name="<?php echo $this->get_field_name( 'lang' ); ?>" value="<?php echo $instance['lang']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'btnText' ); ?>"><?php _e('The Button Text:', 'wpShop'); ?></label>
			<small><?php _e('Please note that the button is really an image however defining the text here is good for accessibility and usability purposes.', 'wpShop'); ?></small>
			
			<input id="<?php echo $this->get_field_id( 'btnText' ); ?>" name="<?php echo $this->get_field_name( 'btnText' ); ?>" value="<?php echo $instance['btnText']; ?>" style="width:97%;" />
		</p>
	<?php }
}

// the Promotions Widget
class PromotionsWidget extends WP_Widget {

	function PromotionsWidget() {
		$widget_ops 	= array('classname' => 'widget_promotions widget_promotions1Link', 'description' => __( 'For 1 link Promotions (Specials/New Arrivals/Clearance/Sales)', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'nws-promotions');
		$this->WP_Widget('nws-promotions', __('NWS Promotions', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
		extract($args);
		$title 			= apply_filters('widget_title', $instance['title'] );
		$link 			= $instance['link'];
		$linkText 		= $instance['linkText'];
		$img 			= $instance['img'];
		$hover_effect 	= isset( $instance['hover_effect'] ) ? $instance['hover_effect'] : FALSE;
		# Before the widget
		echo $before_widget;

		# The title
		if ( $title )
		echo $before_title . $title . $after_title;

		# Output
		if ( $hover_effect ) { ?>
			<div class="widget_promotions_imgWrap widget_promotions_alt"><a href="<?php echo $link;?>"><img src="<?php echo $img;?>" alt="<?php echo $linkText;?>"/></a></div>
		<?php } else { ?>
			<div class="widget_promotions_imgWrap"><a href="<?php echo $link;?>"><img src="<?php echo $img;?>" alt="<?php echo $linkText;?>"/></a></div>
		<?php }
		# After the widget
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 			= strip_tags($new_instance['title']);
		$instance['link'] 			= $new_instance['link'];
		$instance['linkText'] 		= $new_instance['linkText'];
		$instance['img'] 			= $new_instance['img'];
		$instance['hover_effect'] 	= $new_instance['hover_effect'];
		
		return $instance;
	}
	
	function form($instance){
		// Set up some default widget settings. 
		$defaults = array( 'title' => __('', 'wpShop'), 'link' => '', 'linkText' => '', 'img' => '', 'hover_effect' => FALSE,);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e('Link to:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" value="<?php echo $instance['link']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'linkText' ); ?>"><?php _e('Link Text:', 'wpShop'); ?></label><br/>
			<small><?php _e('This will be used for the image\s ALT attribute', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'linkText' ); ?>" name="<?php echo $this->get_field_name( 'linkText' ); ?>" value="<?php echo $instance['linkText']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'img' ); ?>"><?php _e('Image File Path:', 'wpShop'); ?></label><br/>
			<small><?php _e('The path to wherever you saved your image.', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'img' ); ?>" name="<?php echo $this->get_field_name( 'img' ); ?>" value="<?php echo $instance['img']; ?>" style="width:97%;" />
		</p>
		<!-- Hover effect? Checkbox -->
		<p>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['hover_effect'], true ); ?> id="<?php echo $this->get_field_id( 'hover_effect' ); ?>" name="<?php echo $this->get_field_name( 'hover_effect' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'hover_effect' ); ?>"><?php _e('Image Rollover on hover?', 'wpShop'); ?></label>
			<small><?php _e('This will require that you use Image Sprites', 'wpShop'); ?></small>
		</p>
	<?php }
}

// the Promotions 2 Links Widget
class Promotions2LinksWidget extends WP_Widget {

	function Promotions2LinksWidget() {
		$widget_ops 	= array('classname' => 'widget_promotions widget_promotions2Links', 'description' => __( 'For 2 link Promotions (Specials/New Arrivals/Clearance/Sales)', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'nws-2link-promotions');
		$this->WP_Widget('nws-2link-promotions', __('NWS 2Link Promotions', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
		extract($args);
		$title 		= apply_filters('widget_title', $instance['title'] );
		$offer 		= $instance['offer'];
		$link1 		= $instance['link1'];
		$linkText1 	= $instance['linkText1'];
		$link2 		= $instance['link2'];
		$linkText2 	= $instance['linkText2'];
		$img 		= $instance['img'];
		
		
		# Before the widget
		echo $before_widget;

		# The title
		if ( $title )
		echo $before_title . $title . $after_title;

		# Output
		?>	
			<div class="widget_promotions_imgWrap">
				<img src="<?php echo $img;?>" alt="<?php echo $offer;?>"/>
				<a class="link1" href="<?php echo $link1;?>"><img src="<?php echo $img;?>" alt="<?php echo $linkText1;?>"/></a>
				<a class="link2" href="<?php echo $link2;?>"><img src="<?php echo $img;?>" alt="<?php echo $linkText2;?>"/></a>
			</div>
		<?php
		# After the widget
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 			= strip_tags($new_instance['title']);
		$instance['offer'] 			= strip_tags($new_instance['offer']);
		$instance['link1'] 			= $new_instance['link1'];
		$instance['linkText1'] 		= strip_tags($new_instance['linkText1']);
		$instance['link2'] 			= $new_instance['link2'];
		$instance['linkText2'] 		= strip_tags($new_instance['linkText2']);
		$instance['img'] 			= $new_instance['img'];
		
		return $instance;
	}
	
	function form($instance){
		// Set up some default widget settings. 
		$defaults = array( 'title' => __('', 'wpShop'), 'offer' => '', 'link1' => '', 'link2' => '', 'linkText1' => '','linkText2' => '', 'img' => '');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'offer' ); ?>"><?php _e('Offer:', 'wpShop'); ?></label><br/>
			<small><?php _e('This will be used for the image\s ALT attribute', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'offer' ); ?>" name="<?php echo $this->get_field_name( 'offer' ); ?>" value="<?php echo $instance['offer']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link1' ); ?>"><?php _e('First Link to:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'link1' ); ?>" name="<?php echo $this->get_field_name( 'link1' ); ?>" value="<?php echo $instance['link1']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'linkText1' ); ?>"><?php _e('First Link Text:', 'wpShop'); ?></label><br/>
			<small><?php _e('This will be used for the link image\s ALT attribute', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'linkText1' ); ?>" name="<?php echo $this->get_field_name( 'linkText1' ); ?>" value="<?php echo $instance['linkText1']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link2' ); ?>"><?php _e('Second Link to:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'link2' ); ?>" name="<?php echo $this->get_field_name( 'link2' ); ?>" value="<?php echo $instance['link2']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'linkText2' ); ?>"><?php _e('Second Link Text:', 'wpShop'); ?></label><br/>
			<small><?php _e('This will be used for the link image\s ALT attribute', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'linkText2' ); ?>" name="<?php echo $this->get_field_name( 'linkText2' ); ?>" value="<?php echo $instance['linkText2']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'img' ); ?>"><?php _e('Image File Path:', 'wpShop'); ?></label><br/>
			<small><?php _e('The path to wherever you saved your image sprite.', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'img' ); ?>" name="<?php echo $this->get_field_name( 'img' ); ?>" value="<?php echo $instance['img']; ?>" style="width:97%;" />
		</p>
	<?php }
}

// the Promotions 3 Links Widget
class Promotions3LinksWidget extends WP_Widget {

	function Promotions3LinksWidget() {
		$widget_ops 	= array('classname' => 'widget_promotions widget_promotions3Links', 'description' => __( 'For 3 link Promotions (Specials/New Arrivals/Clearance/Sales)', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'nws-3link-promotions');
		$this->WP_Widget('nws-3link-promotions', __('NWS 3Link Promotions', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
		extract($args);
		$title 		= apply_filters('widget_title', $instance['title'] );
		$offer 		= $instance['offer'];
		$link1 		= $instance['link1'];
		$linkText1 	= $instance['linkText1'];
		$link2 		= $instance['link2'];
		$linkText2 	= $instance['linkText2'];
		$link3 		= $instance['link3'];
		$linkText3 	= $instance['linkText3'];
		$img 		= $instance['img'];
		
		
		# Before the widget
		echo $before_widget;

		# The title
		if ( $title )
		echo $before_title . $title . $after_title;

		# Output
		?>	
			<div class="widget_promotions_imgWrap">
				<img src="<?php echo $img;?>" alt="<?php echo $offer;?>"/>
				<a class="link1" href="<?php echo $link1;?>"><img src="<?php echo $img;?>" alt="<?php echo $linkText1;?>"/></a>
				<a class="link2" href="<?php echo $link2;?>"><img src="<?php echo $img;?>" alt="<?php echo $linkText2;?>"/></a>
				<a class="link3" href="<?php echo $link3;?>"><img src="<?php echo $img;?>" alt="<?php echo $linkText3;?>"/></a>
			</div>
		<?php
		# After the widget
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 			= strip_tags($new_instance['title']);
		$instance['offer'] 			= strip_tags($new_instance['offer']);
		$instance['link1'] 			= $new_instance['link1'];
		$instance['linkText1'] 		= strip_tags($new_instance['linkText1']);
		$instance['link2'] 			= $new_instance['link2'];
		$instance['linkText2'] 		= strip_tags($new_instance['linkText2']);
		$instance['link3'] 			= $new_instance['link3'];
		$instance['linkText3'] 		= strip_tags($new_instance['linkText3']);
		$instance['img'] 			= $new_instance['img'];
		
		return $instance;
	}
	
	function form($instance){
		// Set up some default widget settings. 
		$defaults = array( 'title' => __('', 'wpShop'), 'offer' => '', 'link1' => '', 'link2' => '', 'linkText1' => '','linkText2' => '', 'img' => '');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'offer' ); ?>"><?php _e('Offer:', 'wpShop'); ?></label><br/>
			<small><?php _e('This will be used for the image\s ALT attribute', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'offer' ); ?>" name="<?php echo $this->get_field_name( 'offer' ); ?>" value="<?php echo $instance['offer']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link1' ); ?>"><?php _e('First Link to:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'link1' ); ?>" name="<?php echo $this->get_field_name( 'link1' ); ?>" value="<?php echo $instance['link1']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'linkText1' ); ?>"><?php _e('First Link Text:', 'wpShop'); ?></label><br/>
			<small><?php _e('This will be used for the link image\s ALT attribute', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'linkText1' ); ?>" name="<?php echo $this->get_field_name( 'linkText1' ); ?>" value="<?php echo $instance['linkText1']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link2' ); ?>"><?php _e('Second Link to:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'link2' ); ?>" name="<?php echo $this->get_field_name( 'link2' ); ?>" value="<?php echo $instance['link2']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'linkText2' ); ?>"><?php _e('Second Link Text:', 'wpShop'); ?></label><br/>
			<small><?php _e('This will be used for the link image\s ALT attribute', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'linkText2' ); ?>" name="<?php echo $this->get_field_name( 'linkText2' ); ?>" value="<?php echo $instance['linkText2']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link3' ); ?>"><?php _e('Third Link to:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'link3' ); ?>" name="<?php echo $this->get_field_name( 'link3' ); ?>" value="<?php echo $instance['link3']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'linkText3' ); ?>"><?php _e('Third Link Text:', 'wpShop'); ?></label><br/>
			<small><?php _e('This will be used for the link image\s ALT attribute', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'linkText3' ); ?>" name="<?php echo $this->get_field_name( 'linkText3' ); ?>" value="<?php echo $instance['linkText3']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'img' ); ?>"><?php _e('Image File Path:', 'wpShop'); ?></label><br/>
			<small><?php _e('The path to wherever you saved your image sprite.', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'img' ); ?>" name="<?php echo $this->get_field_name( 'img' ); ?>" value="<?php echo $instance['img']; ?>" style="width:97%;" />
		</p>
	<?php }
}

// the Alternative Contact Widget
class ContactWidget extends WP_Widget {

	function ContactWidget() {
		$widget_ops 	= array('classname' => 'widget-alternative-contact-info', 'description' => __( 'For Contact Address and Phone Number', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'nws-alternative-contact-info');
		$this->WP_Widget('nws-alternative-contact-info', __('NWS Alternative Contact Info', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
		extract($args);
		$title 			= apply_filters('widget_title', $instance['title'] );
		$telTitle 		= $instance['telTitle'];
		$telNum 		= $instance['telNum'];
		$AddressTitle 	= $instance['AddressTitle'];
		$AddressDetails = $instance['AddressDetails'];
		
		# Before the widget
		echo $before_widget;

		# The title
		if ( $title )
		echo $before_title . $title . $after_title;

		# Output
		?>
		<ul class="contactAddress clearfix">
			<li>
				<span><?php echo $telTitle;?></span>
				<p><?php echo $telNum;?></p>
			</li>
							
			<li>
				<span><?php echo $AddressTitle;?></span>
				<p>
					<?php echo $AddressDetails;?>
				</p>
			</li>
		</ul>
	
		<?php
		# After the widget
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 				= strip_tags($new_instance['title']);
		$instance['telTitle'] 			= strip_tags($new_instance['telTitle']);
		$instance['telNum'] 			= strip_tags($new_instance['telNum']);
		$instance['AddressTitle'] 		= strip_tags($new_instance['AddressTitle']);
		$instance['AddressDetails'] 	= $new_instance['AddressDetails'];
		return $instance;
	}
	
	function form($instance){
		// Set up some default widget settings.
		$defaults = array( 'title' => __('Alternative Ways of Contacting Us', 'wpShop'), 'telTitle' => 'Telephone', 'telNum' => '555 555 5555', 'AddressTitle' => 'Address', 'AddressDetails' => '');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'telTitle' ); ?>"><?php _e('Telephone Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'telTitle' ); ?>" name="<?php echo $this->get_field_name( 'telTitle' ); ?>" value="<?php echo $instance['telTitle']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'telNum' ); ?>"><?php _e('Telephone Number:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'telNum' ); ?>" name="<?php echo $this->get_field_name( 'telNum' ); ?>" value="<?php echo $instance['telNum']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'AddressTitle' ); ?>"><?php _e('Address Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'AddressTitle' ); ?>" name="<?php echo $this->get_field_name( 'AddressTitle' ); ?>" value="<?php echo $instance['AddressTitle']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'AddressDetails' ); ?>"><?php _e('Address Details:', 'wpShop'); ?></label>
			<textarea id="<?php echo $this->get_field_id( 'AddressDetails' ); ?>" name="<?php echo $this->get_field_name( 'AddressDetails' ); ?>" value="<?php echo $instance['AddressDetails']; ?>" style="width:97%;"rows="10" cols="50"><?php echo $instance['AddressDetails']; ?></textarea>
		</p>
		
	<?php }
}

// Shop by All Purpose Widget
class ShopByAllPurposeWidget extends WP_Widget {

	function ShopByAllPurposeWidget() {
		$widget_ops 	= array('classname' => 'shop_by_widget widget-shop-by-all-purpose', 'description' => __( 'A "Shop by" all-purpose widget.', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'nws-shop-by-all-purpose');
		$this->WP_Widget('nws-shop-by-all-purpose', __('NWS Shop by All Purpose', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
	
		global $OPTION;
	
		extract($args);
		$title 				= apply_filters('widget_title', $instance['title'] );
		$cat_slug 			= $instance['cat_slug'];
		$cat_IDs 			= $instance['cat_IDs'];
		$current_cat 		= isset( $instance['current_cat'] ) ? $instance['current_cat'] : FALSE;
		$current_maincat 	= isset( $instance['current_maincat'] ) ? $instance['current_maincat'] : FALSE;
		$display 			= $instance['display'];
		$thumb_width 		= $instance['thumb_width'];
		$num_img_in_row 	= $instance['num_img_in_row'];
		$img_file_type 		= $instance['img_file_type'];
		
		# Before the widget
		echo $before_widget;

		# The title
		if ( $title )
		echo $before_title . $title . $after_title;

		# Output
		if ($cat_slug!='') {
			query_posts('category_name='.$cat_slug.'&showposts=-1');
		} elseif($cat_IDs!='') {
			query_posts('cat='.$cat_IDs.'&showposts=-1');
		} elseif (($current_cat) && (is_category())) {
			$cat = get_category( get_query_var( 'cat' ), false );
			query_posts('cat='.$cat->cat_ID.'&showposts=-1');
		}elseif (($current_maincat) && (is_category())) {
			$cat 		= get_category( get_query_var( 'cat' ), false );
			$topParent 	= NWS_get_root_category($cat,'allData');
			query_posts('cat='.$topParent->cat_ID.'&showposts=-1');
		}
		if (have_posts()) : while (have_posts()) : the_post();
			$posttags = get_the_tags();
			
			if($posttags !== FALSE) {	
				foreach($posttags as $tag) {
					$all_tags_arr[] 	= $tag->name; //USING JUST $tag MAKING $all_tags_arr A MULTI-DIMENSIONAL ARRAY, WHICH DOES WORK WITH array_unique
					$all_tagslugs_arr[] = $tag->slug;
					$all_tagids_arr[] 	= $tag->term_id;
				}
			}
		endwhile;  wp_reset_query(); endif; 
		
		
		
		if(!empty($all_tags_arr)){
			
			$tags_arr 		= array_unique($all_tags_arr); //REMOVES DUPLICATES
			$tagslugs_arr 	= array_unique($all_tagslugs_arr); //REMOVES DUPLICATES
			$tagids_arr 	= array_unique($all_tagids_arr); //REMOVES DUPLICATES
			
			//sort asc
			sort($tags_arr);
			sort($tagslugs_arr);
			sort($tagids_arr);
			
			if ( $display == 'Images' ) { 
				global $wp_query;
				//get the queried object
				$queried_obj = $wp_query->get_queried_object(); ?>
				
				<div class="img_wrap">
					<?php
					$counter = $num_img_in_row;
					$a = 1;
					foreach ($tags_arr as $k => $tag) {
						$term_obj = get_term_by('name',$tags_arr[$k], __('post_tag', 'wpShop'));
							
						$img_src 		= get_option('siteurl').'/'. $OPTION['upload_path'] .'/'. $term_obj->slug .'.'. $img_file_type;
						$des_src 		= $OPTION['upload_path'].'/cache';	
						$img_file 		= mkthumb($img_src,$des_src,$thumb_width,'width');    
						$imgURL 		= get_option('siteurl').'/'.$des_src.'/'.$img_file;
						$the_a_class 	= alternating_css_class($counter,$num_img_in_row,'first');
						$cur_class 		= (($tag == $queried_obj->name) ? 'current_term': '');
						
						echo '<a class="'.$the_a_class.' '.$cur_class.'" href="'.get_term_link($term_obj,__('post_tag','wpShop')).'" title="'.$term_obj->name .'"><img src="'.$imgURL .'" alt="'.$term_obj->slug .'" /></a>';
						
						$a++;
						$counter++;
					} ?>
				</div>
				
			<?php } elseif ( $display == 'List' ) {
			
				global $wp_query;
				//get the queried object
				$queried_obj = $wp_query->get_queried_object();
			?>
				
				<ul>
					<?php foreach ($tags_arr as $k => $tag) { 
						$term_obj = get_term_by('name',$tags_arr[$k], __('post_tag', 'wpShop')); 
					?>
						<li <?php echo (($tag == $queried_obj->name) ? 'class="current_term"': ''); ?>>
							<?php echo '<a href="'.get_term_link($term_obj,__('post_tag','wpShop')).'">'.$term_obj->name .'</a>';?>
						</li>
					<?php } ?>
				</ul>
				
			<?php } else { ?>
				<div>
					<select name="tag-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">
						<option value="#"><?php _e('Please Select...','wpShop'); ?></option>
							<?php foreach ($tags_arr as $k => $tag) {
								$term_obj = get_term_by('name',$tags_arr[$k], __('post_tag', 'wpShop'));
								
								echo '<option value="'.get_term_link($term_obj,__('post_tag','wpShop')).'">'.$term_obj->name .'</option>';
								
							} ?>
					</select>
				</div>
			<?php }
		} 
	
		
		# After the widget
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 				= strip_tags($new_instance['title']);
		$instance['cat_slug'] 			= strip_tags($new_instance['cat_slug']);
		$instance['cat_IDs'] 			= $new_instance['cat_IDs'];
		$instance['current_cat'] 		= $new_instance['current_cat'];
		$instance['current_maincat'] 	= $new_instance['current_maincat'];
		$instance['display'] 			= $new_instance['display'];
		$instance['thumb_width'] 		= strip_tags($new_instance['thumb_width']);
		$instance['num_img_in_row'] 	= $new_instance['num_img_in_row'];
		$instance['img_file_type'] 		= $new_instance['img_file_type'];
		return $instance;
	}
	
	function form($instance){
		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('', 'wpShop'), 'cat_slug' => '', 'cat_IDs' => '', 'current_cat' => FALSE, 'current_maincat' => FALSE, 'thumb_width' => '80', 'num_img_in_row' => '3');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cat_slug' ); ?>"><?php _e('Category Name or Slug:', 'wpShop'); ?></label><br/>
			<small><?php _e('If you like to query Product tags/terms from one category enter it\'s name or slug here. Otherwise leave empty!', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'cat_slug' ); ?>" name="<?php echo $this->get_field_name( 'cat_slug' ); ?>" value="<?php echo $instance['cat_slug']; ?>" style="width:97%;" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'cat_IDs' ); ?>"><?php _e('Category IDs:', 'wpShop'); ?></label><br/>
			<small><?php _e('If you like to query Product tags/terms from several categories enter their IDs seperated by comma eg. 1,3,4. Otherwise leave empty!', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'cat_IDs' ); ?>" name="<?php echo $this->get_field_name( 'cat_IDs' ); ?>" value="<?php echo $instance['cat_IDs']; ?>" style="width:97%;" />
		</p>
		
		<!-- query current cat || current main cat? Checkbox -->
		<p style="background:#F1F1F1;">
			<strong><?php _e('For use on Product Category Pages Only', 'wpShop'); ?></strong><br/>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['current_cat'], true ); ?> id="<?php echo $this->get_field_id( 'current_cat' ); ?>" name="<?php echo $this->get_field_name( 'current_cat' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'current_cat' ); ?>"><?php _e('Query Current Category', 'wpShop'); ?></label><br/>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['current_maincat'], true ); ?> id="<?php echo $this->get_field_id( 'current_maincat' ); ?>" name="<?php echo $this->get_field_name( 'current_maincat' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'current_maincat' ); ?>"><?php _e('Query Current Main (top level) Category', 'wpShop'); ?></label><br/>
			<small><?php _e('Check the appropriate box (not both!) if you like to query Product tags/terms from the current category or the current main (top level) category being viewed. This will only work if you have activated this widget on category pages! If you are using the widget on other pages please use one of the options above.', 'wpShop'); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'display' ); ?>"><?php _e('Display as:', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'display' ); ?>" name="<?php echo $this->get_field_name( 'display' ); ?>" class="widefat" style="width:97%;">
				<option value="Drop Down" <?php selected('Drop Down', $instance["display"]); ?>><?php _e('Drop Down', 'wpShop'); ?></option>
				<option value="List" <?php selected('List', $instance["display"]); ?>><?php _e('List', 'wpShop'); ?></option>
				<option value="Images" <?php selected('Images', $instance["display"]); ?>><?php _e('Images', 'wpShop'); ?></option>
			</select>
			<small style="color:red;"><?php _e('If you have selected to display your tags/terms as images, remember to create tag/term specific images for each!', 'wpShop'); ?></small>
		</p>
		
		<small><?php _e('The fields below apply only if you have checked "Display Images" from above.', 'wpShop'); ?></small>
		<p>
			<label for="<?php echo $this->get_field_id( 'thumb_width' ); ?>"><?php _e('Thumb Width:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'thumb_width' ); ?>" name="<?php echo $this->get_field_name( 'thumb_width' ); ?>" value="<?php echo $instance['thumb_width']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'num_img_in_row' ); ?>"><?php _e('Number of images per row:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'num_img_in_row' ); ?>" name="<?php echo $this->get_field_name( 'num_img_in_row' ); ?>" value="<?php echo $instance['num_img_in_row']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'img_file_type' ); ?>"><?php _e('Image File Type', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'img_file_type' ); ?>" name="<?php echo $this->get_field_name( 'img_file_type' ); ?>" class="widefat" style="width:97%;">
				<option value="jpg" <?php selected('jpg', $instance["img_file_type"]); ?>><?php _e('jpg', 'wpShop'); ?></option>
				<option value="png" <?php selected('png', $instance["img_file_type"]); ?>><?php _e('png', 'wpShop'); ?></option>
				<option value="gif" <?php selected('gif', $instance["img_file_type"]); ?>><?php _e('gif', 'wpShop'); ?></option>
			</select>
		</p>
		
		
	<?php }
}

// Shop by Outfit Widget
class ShopByOutfitWidget extends WP_Widget {

	function ShopByOutfitWidget() {
		$widget_ops 	= array('classname' => 'shop_by_widget widget-shop-by-outfit', 'description' => __( 'For the "Shop by Outfit" custom Taxonomy', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'nws-shop-by-outfit');
		$this->WP_Widget('nws-shop-by-outfit', __('NWS Shop by Outfit', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
	
		global $OPTION;
		
		extract($args);
		$title 				= apply_filters('widget_title', $instance['title'] );
		$cat_slug 			= $instance['cat_slug'];
		$cat_IDs 			= $instance['cat_IDs'];
		$current_cat 		= isset( $instance['current_cat'] ) ? $instance['current_cat'] : FALSE;
		$current_maincat 	= isset( $instance['current_maincat'] ) ? $instance['current_maincat'] : FALSE;
		$display 			= $instance['display'];
		$thumb_width 		= $instance['thumb_width'];
		$num_img_in_row 	= $instance['num_img_in_row'];
		$img_file_type 		= $instance['img_file_type'];
		
		# Before the widget
		echo $before_widget;

		# The title
		if ( $title )
		echo $before_title . $title . $after_title;

		# Output
		//check to see if the taxonomy exists first
		$taxonomy_exist = taxonomy_exists(__('outfit','wpShop'));
		if($taxonomy_exist) {
			if ($cat_slug!='') {
				query_posts('category_name='.$cat_slug.'&showposts=-1');
			} elseif($cat_IDs!='') {
				query_posts('cat='.$cat_IDs.'&showposts=-1');
			} elseif (($current_cat) && (is_category())) {
				$cat = get_category( get_query_var( 'cat' ), false );
				query_posts('cat='.$cat->cat_ID.'&showposts=-1');
			}elseif (($current_maincat) && (is_category())) {
				$cat 		= get_category( get_query_var( 'cat' ), false );
				$topParent 	= NWS_get_root_category($cat,'allData');
				query_posts('cat='.$topParent->cat_ID.'&showposts=-1');
			}
			if (have_posts()) : while (have_posts()) : the_post();
			
				$posttags = get_the_outfit();
				
				if($posttags !== FALSE) {					
					foreach($posttags as $tag) {
						$all_tags_arr[] 	= $tag->name; //USING JUST $tag MAKING $all_tags_arr A MULTI-DIMENSIONAL ARRAY, WHICH DOES WORK WITH array_unique
						$all_tagslugs_arr[] = $tag->slug;
					}
				}
			endwhile;  wp_reset_query(); endif;

			if(!empty($all_tags_arr)){
			
				$tags_arr 		= array_unique($all_tags_arr); //REMOVES DUPLICATES
				$tagslugs_arr 	= array_unique($all_tagslugs_arr); //REMOVES DUPLICATES
				
				//sort asc
				sort($tags_arr);
				sort($tagslugs_arr);
				
				
				
				if ( $display == 'Images' ) { 
					global $wp_query;
					//get the queried object
					$queried_obj = $wp_query->get_queried_object(); ?>
					
					<div class="img_wrap">
						<?php
						$counter = $num_img_in_row;
						$a = 1;
						foreach ($tags_arr as $k => $tag) {
							$term_obj = get_term_by('name',$tags_arr[$k], __('outfit', 'wpShop'));
							
							$img_src 		= get_option('siteurl').'/'. $OPTION['upload_path'] .'/'. $term_obj->slug .'.'. $img_file_type;
							$des_src 		= $OPTION['upload_path'].'/cache';	
							$img_file 		= mkthumb($img_src,$des_src,$thumb_width,'width');    
							$imgURL 		= get_option('siteurl').'/'.$des_src.'/'.$img_file;
							$the_a_class 	= alternating_css_class($counter,$num_img_in_row,'first');
							$cur_class 		= (($tag == $queried_obj->name) ? 'current_term': '');
							
							echo '<a class="'.$the_a_class.' '.$cur_class.'" href="'.get_term_link($term_obj,__('outfit','wpShop')).'" title="'.$term_obj->name .'"><img src="'.$imgURL .'" alt="'.$term_obj->slug .'" /></a>';
							
							$a++;
							$counter++;
						} ?>
					</div>
				
				<?php } elseif ( $display == 'List' ) { 
					
					global $wp_query;
					//get the queried object
					$queried_obj = $wp_query->get_queried_object();
				?>
				
					<ul>
						<?php foreach ($tags_arr as $k => $tag) { 
							$term_obj = get_term_by('name',$tags_arr[$k], __('outfit', 'wpShop')); 
						?>
							<li <?php echo (($tag == $queried_obj->name) ? 'class="current_term"': ''); ?>>
								<?php echo '<a href="'.get_term_link($term_obj,__('outfit','wpShop')).'">'.$term_obj->name .'</a>';?>
							</li>
						<?php } ?>
					</ul>
				
				<?php } else { ?>
					<div>
						<select name="tag-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">
							<option value="#"><?php _e('Please Select...','wpShop'); ?></option>
								<?php foreach ($tags_arr as $k => $tag) {
									$term_obj = get_term_by('name',$tags_arr[$k], __('outfit', 'wpShop'));
									
									echo '<option value="'.get_term_link($term_obj,__('outfit','wpShop')).'">'.$term_obj->name .'</option>';
									
								} ?>
						</select>
					</div>
				<?php }
			} 
		} else {
			echo "<p class='error'>".__('The widget you have activated needs that the "outfit" custom taxonomy is active! Please activate it from your Theme Options > Design > General settings','wpShop')."</p>";
		}
		
		# After the widget
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 				= strip_tags($new_instance['title']);
		$instance['cat_slug'] 			= strip_tags($new_instance['cat_slug']);
		$instance['cat_IDs'] 			= $new_instance['cat_IDs'];
		$instance['current_cat'] 		= $new_instance['current_cat'];
		$instance['current_maincat'] 	= $new_instance['current_maincat'];
		$instance['display'] 			= $new_instance['display'];
		$instance['thumb_width'] 		= strip_tags($new_instance['thumb_width']);
		$instance['num_img_in_row'] 	= $new_instance['num_img_in_row'];
		$instance['img_file_type'] 		= $new_instance['img_file_type'];
		return $instance;
	}
	
	function form($instance){
		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('', 'wpShop'), 'cat_slug' => '', 'cat_IDs' => '', 'current_cat' => FALSE, 'current_maincat' => FALSE, 'thumb_width' => '80', 'num_img_in_row' => '3');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cat_slug' ); ?>"><?php _e('Category Name or Slug:', 'wpShop'); ?></label><br/>
			<small><?php _e('If you like to query Product tags/terms from one category enter it\'s name or slug here. Otherwise leave empty!', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'cat_slug' ); ?>" name="<?php echo $this->get_field_name( 'cat_slug' ); ?>" value="<?php echo $instance['cat_slug']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cat_IDs' ); ?>"><?php _e('Category IDs:', 'wpShop'); ?></label><br/>
			<small><?php _e('If you like to query Product tags/terms from several categories enter their IDs seperated by comma eg. 1,3,4. Otherwise leave empty!', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'cat_IDs' ); ?>" name="<?php echo $this->get_field_name( 'cat_IDs' ); ?>" value="<?php echo $instance['cat_IDs']; ?>" style="width:97%;" />
		</p>
		
		<!-- query current cat || current main cat? Checkbox -->
		<p style="background:#F1F1F1;">
			<strong><?php _e('For use on Product Category Pages Only', 'wpShop'); ?></strong><br/>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['current_cat'], true ); ?> id="<?php echo $this->get_field_id( 'current_cat' ); ?>" name="<?php echo $this->get_field_name( 'current_cat' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'current_cat' ); ?>"><?php _e('Query Current Category', 'wpShop'); ?></label><br/>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['current_maincat'], true ); ?> id="<?php echo $this->get_field_id( 'current_maincat' ); ?>" name="<?php echo $this->get_field_name( 'current_maincat' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'current_maincat' ); ?>"><?php _e('Query Current Main (top level) Category', 'wpShop'); ?></label><br/>
			<small><?php _e('Check the appropriate box (not both!) if you like to query Product tags/terms from the current category or the current main (top level) category being viewed. This will only work if you have activated this widget on category pages! If you are using the widget on other pages please use one of the options above.', 'wpShop'); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'display' ); ?>"><?php _e('Display as:', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'display' ); ?>" name="<?php echo $this->get_field_name( 'display' ); ?>" class="widefat" style="width:97%;">
				<option value="Drop Down" <?php selected('Drop Down', $instance["display"]); ?>><?php _e('Drop Down', 'wpShop'); ?></option>
				<option value="List" <?php selected('List', $instance["display"]); ?>><?php _e('List', 'wpShop'); ?></option>
				<option value="Images" <?php selected('Images', $instance["display"]); ?>><?php _e('Images', 'wpShop'); ?></option>
			</select>
			<small style="color:red;"><?php _e('If you have selected to display your tags/terms as images, remember to create tag/term specific images for each!', 'wpShop'); ?></small>
		</p>
		
		<small><?php _e('The fields below apply only if you have checked "Display Images" from above.', 'wpShop'); ?></small>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'thumb_width' ); ?>"><?php _e('Thumb Width:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'thumb_width' ); ?>" name="<?php echo $this->get_field_name( 'thumb_width' ); ?>" value="<?php echo $instance['thumb_width']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'num_img_in_row' ); ?>"><?php _e('Number of images per row:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'num_img_in_row' ); ?>" name="<?php echo $this->get_field_name( 'num_img_in_row' ); ?>" value="<?php echo $instance['num_img_in_row']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'img_file_type' ); ?>"><?php _e('Image File Type', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'img_file_type' ); ?>" name="<?php echo $this->get_field_name( 'img_file_type' ); ?>" class="widefat" style="width:97%;">
				<option value="jpg" <?php selected('jpg', $instance["img_file_type"]); ?>><?php _e('jpg', 'wpShop'); ?></option>
				<option value="png" <?php selected('png', $instance["img_file_type"]); ?>><?php _e('png', 'wpShop'); ?></option>
				<option value="gif" <?php selected('gif', $instance["img_file_type"]); ?>><?php _e('gif', 'wpShop'); ?></option>
			</select>
		</p>
		
		
	<?php }
}

// Shop by Fit Widget
class ShopByFitWidget extends WP_Widget {

	function ShopByFitWidget() {
		$widget_ops 	= array('classname' => 'shop_by_widget widget-shop-by-fit', 'description' => __( 'For the "Shop by Fit" custom Taxonomy', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'nws-shop-by-fit');
		$this->WP_Widget('nws-shop-by-fit', __('NWS Shop by Fit', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
	
		global $OPTION;
	
		extract($args);
		$title 				= apply_filters('widget_title', $instance['title'] );
		$cat_slug 			= $instance['cat_slug'];
		$cat_IDs 			= $instance['cat_IDs'];
		$current_cat 		= isset( $instance['current_cat'] ) ? $instance['current_cat'] : FALSE;
		$current_maincat 	= isset( $instance['current_maincat'] ) ? $instance['current_maincat'] : FALSE;
		$display 			= $instance['display'];
		$thumb_width 		= $instance['thumb_width'];
		$num_img_in_row 	= $instance['num_img_in_row'];
		$img_file_type 		= $instance['img_file_type'];
		
		# Before the widget
		echo $before_widget;

		# The title
		if ( $title )
		echo $before_title . $title . $after_title;

		# Output
		//check to see if the taxonomy exists first
		$taxonomy_exist = taxonomy_exists(__('fit','wpShop'));
		if($taxonomy_exist) {
			if ($cat_slug!='') {
				query_posts('category_name='.$cat_slug.'&showposts=-1');
			} elseif($cat_IDs!='') {
				query_posts('cat='.$cat_IDs.'&showposts=-1');
			} elseif (($current_cat) && (is_category())) {
				$cat = get_category( get_query_var( 'cat' ), false );
				query_posts('cat='.$cat->cat_ID.'&showposts=-1');
			}elseif (($current_maincat) && (is_category())) {
				$cat 		= get_category( get_query_var( 'cat' ), false );
				$topParent 	= NWS_get_root_category($cat,'allData');
				query_posts('cat='.$topParent->cat_ID.'&showposts=-1');
			}
			if (have_posts()) : while (have_posts()) : the_post();
				$posttags = get_the_fit();
				if($posttags !== FALSE) {						
					foreach($posttags as $tag) {
						$all_tags_arr[] 	= $tag->name; //USING JUST $tag MAKING $all_tags_arr A MULTI-DIMENSIONAL ARRAY, WHICH DOES WORK WITH array_unique
						$all_tagslugs_arr[] = $tag->slug;
					}
				}
			endwhile;  wp_reset_query();  endif; 
		
			if(!empty($all_tags_arr)){
				
				$tags_arr 		= array_unique($all_tags_arr); //REMOVES DUPLICATES
				$tagslugs_arr 	= array_unique($all_tagslugs_arr); //REMOVES DUPLICATES
				
				//sort asc
				sort($tags_arr);
				sort($tagslugs_arr);
				
				if ( $display == 'Images' ) { 
					global $wp_query;
					//get the queried object
					$queried_obj = $wp_query->get_queried_object(); ?>
					
					<div class="img_wrap">
						<?php
						$counter = $num_img_in_row;
						$a = 1;
						foreach ($tags_arr as $k => $tag) {
							$term_obj = get_term_by('name',$tags_arr[$k], __('fit', 'wpShop'));
							
							$img_src 		= get_option('siteurl').'/'. $OPTION['upload_path'].'/'.$term_obj->slug .'.'.$img_file_type;
							$des_src 		= $OPTION['upload_path'].'/cache';	
							$img_file 		= mkthumb($img_src,$des_src,$thumb_width,'width');    
							$imgURL 		= get_option('siteurl').'/'.$des_src.'/'.$img_file;
							$the_a_class 	= alternating_css_class($counter,$num_img_in_row,'first');
							$cur_class 		= (($tag == $queried_obj->name) ? 'current_term': '');
							
							echo '<a class="'.$the_a_class.' '.$cur_class.'" href="'.get_term_link($term_obj,__('fit','wpShop')).'" title="'.$term_obj->name .'"><img src="'.$imgURL .'" alt="'.$term_obj->slug .'" /></a>';
							
							$a++;
							$counter++;
						} ?>
					</div>
				
				<?php } elseif ( $display == 'List' ) { 
					
					global $wp_query;
					//get the queried object
					$queried_obj = $wp_query->get_queried_object();
				?>
			
					<ul>
						<?php foreach ($tags_arr as $k => $tag) { 
							$term_obj = get_term_by('name',$tags_arr[$k], __('fit', 'wpShop')); 
						?>
							<li <?php echo (($tag == $queried_obj->name) ? 'class="current_term"': ''); ?>>
								<?php echo '<a href="'.get_term_link($term_obj,__('fit','wpShop')).'">'.$term_obj->name .'</a>';?>
							</li>
						<?php } ?>
					</ul>
				
				<?php } else { ?>
					<div>
						<select name="tag-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">
							<option value="#"><?php _e('Please Select...','wpShop'); ?></option>
								<?php foreach ($tags_arr as $k => $tag) {
									$term_obj = get_term_by('name',$tags_arr[$k], __('fit', 'wpShop'));
									
									echo '<option value="'.get_term_link($term_obj,__('fit','wpShop')).'">'.$term_obj->name .'</option>';
									
								} ?>
						</select>
					</div>
				<?php }
			} 
		} else {
			echo "<p class='error'>".__('The widget you have activated needs that the "fit" custom taxonomy is active! Please activate it from your Theme Options > Design > General settings','wpShop')."</p>";
		}
		
		# After the widget
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 				= strip_tags($new_instance['title']);
		$instance['cat_slug'] 			= strip_tags($new_instance['cat_slug']);
		$instance['cat_IDs'] 			= $new_instance['cat_IDs'];
		$instance['current_cat'] 		= $new_instance['current_cat'];
		$instance['current_maincat'] 	= $new_instance['current_maincat'];
		$instance['display'] 			= $new_instance['display'];
		$instance['thumb_width'] 		= strip_tags($new_instance['thumb_width']);
		$instance['num_img_in_row'] 	= $new_instance['num_img_in_row'];
		$instance['img_file_type'] 		= $new_instance['img_file_type'];
		return $instance;
	}
	
	function form($instance){
		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('', 'wpShop'), 'cat_slug' => '', 'cat_IDs' => '', 'current_cat' => FALSE, 'current_maincat' => FALSE, 'thumb_width' => '80', 'num_img_in_row' => '3');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cat_slug' ); ?>"><?php _e('Category Name or Slug:', 'wpShop'); ?></label><br/>
			<small><?php _e('If you like to query Product tags/terms from one category enter it\'s name or slug here. Otherwise leave empty!', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'cat_slug' ); ?>" name="<?php echo $this->get_field_name( 'cat_slug' ); ?>" value="<?php echo $instance['cat_slug']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cat_IDs' ); ?>"><?php _e('Category IDs:', 'wpShop'); ?></label><br/>
			<small><?php _e('If you like to query Product tags/terms from several categories enter their IDs seperated by comma eg. 1,3,4. Otherwise leave empty!', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'cat_IDs' ); ?>" name="<?php echo $this->get_field_name( 'cat_IDs' ); ?>" value="<?php echo $instance['cat_IDs']; ?>" style="width:97%;" />
		</p>
		
		<!-- query current cat || current main cat? Checkbox -->
		<p style="background:#F1F1F1;">
			<strong><?php _e('For use on Product Category Pages Only', 'wpShop'); ?></strong><br/>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['current_cat'], true ); ?> id="<?php echo $this->get_field_id( 'current_cat' ); ?>" name="<?php echo $this->get_field_name( 'current_cat' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'current_cat' ); ?>"><?php _e('Query Current Category', 'wpShop'); ?></label><br/>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['current_maincat'], true ); ?> id="<?php echo $this->get_field_id( 'current_maincat' ); ?>" name="<?php echo $this->get_field_name( 'current_maincat' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'current_maincat' ); ?>"><?php _e('Query Current Main (top level) Category', 'wpShop'); ?></label><br/>
			<small><?php _e('Check the appropriate box (not both!) if you like to query Product tags/terms from the current category or the current main (top level) category being viewed. This will only work if you have activated this widget on category pages! If you are using the widget on other pages please use one of the options above.', 'wpShop'); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'display' ); ?>"><?php _e('Display as:', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'display' ); ?>" name="<?php echo $this->get_field_name( 'display' ); ?>" class="widefat" style="width:97%;">
				<option value="Drop Down" <?php selected('Drop Down', $instance["display"]); ?>><?php _e('Drop Down', 'wpShop'); ?></option>
				<option value="List" <?php selected('List', $instance["display"]); ?>><?php _e('List', 'wpShop'); ?></option>
				<option value="Images" <?php selected('Images', $instance["display"]); ?>><?php _e('Images', 'wpShop'); ?></option>
			</select>
			<small style="color:red;"><?php _e('If you have selected to display your tags/terms as images, remember to create tag/term specific images for each!', 'wpShop'); ?></small>
		</p>
		
		<small><?php _e('The fields below apply only if you have checked "Display Images" from above.', 'wpShop'); ?></small>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'thumb_width' ); ?>"><?php _e('Thumb Width:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'thumb_width' ); ?>" name="<?php echo $this->get_field_name( 'thumb_width' ); ?>" value="<?php echo $instance['thumb_width']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'num_img_in_row' ); ?>"><?php _e('Number of images per row:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'num_img_in_row' ); ?>" name="<?php echo $this->get_field_name( 'num_img_in_row' ); ?>" value="<?php echo $instance['num_img_in_row']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'img_file_type' ); ?>"><?php _e('Image File Type', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'img_file_type' ); ?>" name="<?php echo $this->get_field_name( 'img_file_type' ); ?>" class="widefat" style="width:97%;">
				<option value="jpg" <?php selected('jpg', $instance["img_file_type"]); ?>><?php _e('jpg', 'wpShop'); ?></option>
				<option value="png" <?php selected('png', $instance["img_file_type"]); ?>><?php _e('png', 'wpShop'); ?></option>
				<option value="gif" <?php selected('gif', $instance["img_file_type"]); ?>><?php _e('gif', 'wpShop'); ?></option>
			</select>
		</p>
		
		
	<?php }
}

// Shop by Size Widget
class ShopBySizeWidget extends WP_Widget {

	function ShopBySizeWidget() {
		$widget_ops 	= array('classname' => 'shop_by_widget widget-shop-by-size', 'description' => __( 'For the "Shop by Size" custom Taxonomy', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'nws-shop-by-size');
		$this->WP_Widget('nws-shop-by-size', __('NWS Shop by Size', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
	
		global $OPTION;

		if (is_category($OPTION['wps_men_shoes_category']) || is_category($OPTION['wps_women_shoes_category'])) {
	
		extract($args);
		$title 				= apply_filters('widget_title', $instance['title'] );
		$cat_slug 			= $instance['cat_slug'];
		$cat_IDs 			= $instance['cat_IDs'];
		$current_cat 		= isset( $instance['current_cat'] ) ? $instance['current_cat'] : FALSE;
		$current_maincat 	= isset( $instance['current_maincat'] ) ? $instance['current_maincat'] : FALSE;
		$display 			= $instance['display'];
		$thumb_width 		= $instance['thumb_width'];
		$num_img_in_row 	= $instance['num_img_in_row'];
		$img_file_type 		= $instance['img_file_type'];
		
		# Before the widget
		echo $before_widget;

		# The title
		if ( $title )
		echo $before_title . $title . $after_title;

		# Output
		//check to see if the taxonomy exists first
		$taxonomy_exist = taxonomy_exists(__('size','wpShop'));
		if($taxonomy_exist) {
			if ($cat_slug!='') {
				query_posts('category_name='.$cat_slug.'&showposts=-1');
			} elseif($cat_IDs!='') {
				query_posts('cat='.$cat_IDs.'&showposts=-1');
			} elseif (($current_cat) && (is_category())) {
				$cat = get_category( get_query_var( 'cat' ), false );
				query_posts('cat='.$cat->cat_ID.'&showposts=-1');
			}elseif (($current_maincat) && (is_category())) {
				$cat 		= get_category( get_query_var( 'cat' ), false );
				$topParent 	= NWS_get_root_category($cat,'allData');
				query_posts('cat='.$topParent->cat_ID.'&showposts=-1');
			}
			if (have_posts()) : while (have_posts()) : the_post();
				$posttags = get_the_size();
				
				
				
				if($posttags !== FALSE) {					
					foreach($posttags as $tag) {
						$all_tags_arr[] 	= $tag->name; //USING JUST $tag MAKING $all_tags_arr A MULTI-DIMENSIONAL ARRAY, WHICH DOES WORK WITH array_unique
						$all_tagslugs_arr[] = $tag->slug;
					}
				}
			endwhile;  wp_reset_query();  endif;
		
			if(!empty($all_tags_arr)){
			
				$tags_arr 		= array_unique($all_tags_arr); //REMOVES DUPLICATES
				$tagslugs_arr 	= array_unique($all_tagslugs_arr); //REMOVES DUPLICATES
				
				//sort asc
				sort($tags_arr);
				sort($tagslugs_arr);
			
				if ( $display == 'Images' ) { 
					global $wp_query;
					//get the queried object
					$queried_obj = $wp_query->get_queried_object(); ?>
					
					<div class="img_wrap">
						<?php
						$counter = $num_img_in_row;
						$a = 1;
						foreach ($tags_arr as $k => $tag) {
							$term_obj = get_term_by('name',$tags_arr[$k], __('size', 'wpShop'));
							
							$img_src 		= get_option('siteurl').'/'. $OPTION['upload_path'].'/'.$term_obj->slug .'.'.$img_file_type;
							$des_src 		= $OPTION['upload_path'].'/cache';	
							$img_file 		= mkthumb($img_src,$des_src,$thumb_width,'width');    
							$imgURL 		= get_option('siteurl').'/'.$des_src.'/'.$img_file;
							$the_a_class 	= alternating_css_class($counter,$num_img_in_row,'first');
							$cur_class 		= (($tag == $queried_obj->name) ? 'current_term': '');
							
							echo '<a class="'.$the_a_class.' '.$cur_class.'" href="'.get_term_link($term_obj,__('size','wpShop')).'" title="'.$term_obj->name .'"><img src="'.$imgURL .'" alt="'.$term_obj->slug .'" /></a>';
							
							$a++;
							$counter++;
						} ?>
					</div>
				
				<?php } elseif ( $display == 'List' ) { 
					
					global $wp_query;
					//get the queried object
					$queried_obj = $wp_query->get_queried_object();
				?>
					
					<ul>
						<?php foreach ($tags_arr as $k => $tag) { 
							$term_obj = get_term_by('name',$tags_arr[$k], __('size', 'wpShop')); 
						?>
							<li <?php echo (($tag == $queried_obj->name) ? 'class="current_term"': ''); ?>>
								<?php echo '<a href="'.get_term_link($term_obj,__('size','wpShop')).'">'.$term_obj->name .'</a>';?>
							</li>
						<?php } ?>
					</ul>
					
				<?php } else { ?>
					<div>
						<select name="tag-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">
							<option value="#"><?php _e('Please Select...','wpShop'); ?></option>
								<?php foreach ($tags_arr as $k => $tag) {
									$term_obj = get_term_by('name',$tags_arr[$k], __('size', 'wpShop'));
									
									echo '<option value="'.get_term_link($term_obj,__('size','wpShop')).'">'.$term_obj->name .'</option>';
									
								} ?>
						</select>
					</div>
				<?php }
			} 
		} else {
			echo "<p class='error'>".__('The widget you have activated needs that the "size" custom taxonomy is active! Please activate it from your Theme Options > Design > General settings','wpShop')."</p>";
		}
	
		
		# After the widget
		echo $after_widget;
		}
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 				= strip_tags($new_instance['title']);
		$instance['cat_slug'] 			= strip_tags($new_instance['cat_slug']);
		$instance['cat_IDs'] 			= $new_instance['cat_IDs'];
		$instance['current_cat'] 		= $new_instance['current_cat'];
		$instance['current_maincat'] 	= $new_instance['current_maincat'];
		$instance['display'] 			= $new_instance['display'];
		$instance['thumb_width'] 		= strip_tags($new_instance['thumb_width']);
		$instance['num_img_in_row'] 	= $new_instance['num_img_in_row'];
		$instance['img_file_type'] 		= $new_instance['img_file_type'];
		return $instance;
	}
	
	function form($instance){
		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('', 'wpShop'), 'cat_slug' => '', 'cat_IDs' => '', 'current_cat' => FALSE, 'current_maincat' => FALSE, 'thumb_width' => '80', 'num_img_in_row' => '3');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cat_slug' ); ?>"><?php _e('Category Name or Slug:', 'wpShop'); ?></label><br/>
			<small><?php _e('If you like to query Product tags/terms from one category enter it\'s name or slug here. Otherwise leave empty!', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'cat_slug' ); ?>" name="<?php echo $this->get_field_name( 'cat_slug' ); ?>" value="<?php echo $instance['cat_slug']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cat_IDs' ); ?>"><?php _e('Category IDs:', 'wpShop'); ?></label><br/>
			<small><?php _e('If you like to query Product tags/terms from several categories enter their IDs seperated by comma eg. 1,3,4. Otherwise leave empty!', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'cat_IDs' ); ?>" name="<?php echo $this->get_field_name( 'cat_IDs' ); ?>" value="<?php echo $instance['cat_IDs']; ?>" style="width:97%;" />
		</p>
		
		<!-- query current cat || current main cat? Checkbox -->
		<p style="background:#F1F1F1;">
			<strong><?php _e('For use on Product Category Pages Only', 'wpShop'); ?></strong><br/>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['current_cat'], true ); ?> id="<?php echo $this->get_field_id( 'current_cat' ); ?>" name="<?php echo $this->get_field_name( 'current_cat' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'current_cat' ); ?>"><?php _e('Query Current Category', 'wpShop'); ?></label><br/>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['current_maincat'], true ); ?> id="<?php echo $this->get_field_id( 'current_maincat' ); ?>" name="<?php echo $this->get_field_name( 'current_maincat' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'current_maincat' ); ?>"><?php _e('Query Current Main (top level) Category', 'wpShop'); ?></label><br/>
			<small><?php _e('Check the appropriate box (not both!) if you like to query Product tags/terms from the current category or the current main (top level) category being viewed. This will only work if you have activated this widget on category pages! If you are using the widget on other pages please use one of the options above.', 'wpShop'); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'display' ); ?>"><?php _e('Display as:', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'display' ); ?>" name="<?php echo $this->get_field_name( 'display' ); ?>" class="widefat" style="width:97%;">
				<option value="Drop Down" <?php selected('Drop Down', $instance["display"]); ?>><?php _e('Drop Down', 'wpShop'); ?></option>
				<option value="List" <?php selected('List', $instance["display"]); ?>><?php _e('List', 'wpShop'); ?></option>
				<option value="Images" <?php selected('Images', $instance["display"]); ?>><?php _e('Images', 'wpShop'); ?></option>
			</select>
			<small style="color:red;"><?php _e('If you have selected to display your tags/terms as images, remember to create tag/term specific images for each!', 'wpShop'); ?></small>
		</p>
		
		<small><?php _e('The fields below apply only if you have checked "Display Images" from above.', 'wpShop'); ?></small>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'thumb_width' ); ?>"><?php _e('Thumb Width:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'thumb_width' ); ?>" name="<?php echo $this->get_field_name( 'thumb_width' ); ?>" value="<?php echo $instance['thumb_width']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'num_img_in_row' ); ?>"><?php _e('Number of images per row:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'num_img_in_row' ); ?>" name="<?php echo $this->get_field_name( 'num_img_in_row' ); ?>" value="<?php echo $instance['num_img_in_row']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'img_file_type' ); ?>"><?php _e('Image File Type', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'img_file_type' ); ?>" name="<?php echo $this->get_field_name( 'img_file_type' ); ?>" class="widefat" style="width:97%;">
				<option value="jpg" <?php selected('jpg', $instance["img_file_type"]); ?>><?php _e('jpg', 'wpShop'); ?></option>
				<option value="png" <?php selected('png', $instance["img_file_type"]); ?>><?php _e('png', 'wpShop'); ?></option>
				<option value="gif" <?php selected('gif', $instance["img_file_type"]); ?>><?php _e('gif', 'wpShop'); ?></option>
			</select>
		</p>
		
		
	<?php }
}

// Shop by Colour Widget
class ShopByColourWidget extends WP_Widget {

	function ShopByColourWidget() {
		$widget_ops 	= array('classname' => 'shop_by_widget widget-shop-by-colour', 'description' => __( 'For the "Shop by Colour" custom Taxonomy', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'nws-shop-by-colour');
		$this->WP_Widget('nws-shop-by-colour', __('NWS Shop by Colour', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
		
		global $OPTION;
	
		extract($args);
		$title 				= apply_filters('widget_title', $instance['title'] );
		$cat_slug 			= $instance['cat_slug'];
		$cat_IDs 			= $instance['cat_IDs'];
		$current_cat 		= isset( $instance['current_cat'] ) ? $instance['current_cat'] : FALSE;
		$current_maincat 	= isset( $instance['current_maincat'] ) ? $instance['current_maincat'] : FALSE;
		$display 			= $instance['display'];
		$thumb_width 		= $instance['thumb_width'];
		$num_img_in_row 	= $instance['num_img_in_row'];
		$img_file_type 		= $instance['img_file_type'];
		
		# Before the widget
		echo $before_widget;

		# The title
		if ( $title )
		echo $before_title . $title . $after_title;

		# Output
		//check to see if the taxonomy exists first
		$taxonomy_exist = taxonomy_exists(__('colour','wpShop'));
		if($taxonomy_exist) {
			if ($cat_slug!='') {
				query_posts('category_name='.$cat_slug.'&showposts=-1');
			} elseif($cat_IDs!='') {
				query_posts('cat='.$cat_IDs.'&showposts=-1');
			} elseif (($current_cat) && (is_category())) {
				$cat = get_category( get_query_var( 'cat' ), false );
				query_posts('cat='.$cat->cat_ID.'&showposts=-1');
			}elseif (($current_maincat) && (is_category())) {
				$cat 		= get_category( get_query_var( 'cat' ), false );
				$topParent 	= NWS_get_root_category($cat,'allData');
				query_posts('cat='.$topParent->cat_ID.'&showposts=-1');
			}
			if (have_posts()) : while (have_posts()) : the_post();
				$posttags = get_the_colour();
				if($posttags !== FALSE) {					
					foreach($posttags as $tag) {
						$all_tags_arr[] 	= $tag->name; //USING JUST $tag MAKING $all_tags_arr A MULTI-DIMENSIONAL ARRAY, WHICH DOES WORK WITH array_unique
						$all_tagslugs_arr[] = $tag->slug;
					}
				}
			endwhile;  wp_reset_query();  endif; 
		
			if(!empty($all_tags_arr)){
				
				$tags_arr 		= array_unique($all_tags_arr); //REMOVES DUPLICATES
				$tagslugs_arr 	= array_unique($all_tagslugs_arr); //REMOVES DUPLICATES
				
				//sort asc
				sort($tags_arr);
				sort($tagslugs_arr);

				if ( $display == 'Images' ) { 
					global $wp_query;
					//get the queried object
					$queried_obj = $wp_query->get_queried_object(); ?>
					
					<div class="img_wrap">
						<?php
						$counter = $num_img_in_row;
						$a = 1;
						foreach ($tags_arr as $k => $tag) {
							$term_obj = get_term_by('name',$tags_arr[$k], __('colour', 'wpShop'));
							
							$img_src 		= get_option('siteurl').'/'. $OPTION['upload_path'].'/'.$term_obj->slug .'.'.$img_file_type;
							$des_src 		= $OPTION['upload_path'].'/cache';	
							$img_file 		= mkthumb($img_src,$des_src,$thumb_width,'width');    
							$imgURL 		= get_option('siteurl').'/'.$des_src.'/'.$img_file;
							$the_a_class 	= alternating_css_class($counter,$num_img_in_row,'first');
							$cur_class 		= (($tag == $queried_obj->name) ? 'current_term': '');
							
							echo '<a class="'.$the_a_class.' '.$cur_class.'" href="'.get_term_link($term_obj,__('colour','wpShop')).'" title="'.$term_obj->name .'"><img src="'.$imgURL .'" alt="'.$term_obj->slug .'" /></a>';
							
							$a++;
							$counter++;
						} ?>
					</div>
				
				<?php } elseif ( $display == 'List' ) { 
					
					global $wp_query;
					//get the queried object
					$queried_obj = $wp_query->get_queried_object();
				?>
				
					<ul>
						<?php foreach ($tags_arr as $k => $tag) { 
							$term_obj = get_term_by('name',$tags_arr[$k], __('colour', 'wpShop')); 
						?>
							<li <?php echo (($tag == $queried_obj->name) ? 'class="current_term"': ''); ?>>
								<?php echo '<a href="'.get_term_link($term_obj,__('colour','wpShop')).'">'.$term_obj->name .'</a>';?>
							</li>
						<?php } ?>
					</ul>
			
				<?php } else { ?>
					<div>
						<select name="tag-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">
							<option value="#"><?php _e('Please Select...','wpShop'); ?></option>
								<?php foreach ($tags_arr as $k => $tag) {
									$term_obj = get_term_by('name',$tags_arr[$k], __('colour', 'wpShop'));
									
									echo '<option value="'.get_term_link($term_obj,__('colour','wpShop')).'">'.$term_obj->name .'</option>';
									
								} ?>
						</select>
					</div>
				<?php }
			} 
		} else {
			echo "<p class='error'>".__('The widget you have activated needs that the "colour" custom taxonomy is active! Please activate it from your Theme Options > Design > General settings','wpShop')."</p>";
		}
	
		
		# After the widget
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 				= strip_tags($new_instance['title']);
		$instance['cat_slug'] 			= strip_tags($new_instance['cat_slug']);
		$instance['cat_IDs'] 			= $new_instance['cat_IDs'];
		$instance['current_cat'] 		= $new_instance['current_cat'];
		$instance['current_maincat'] 	= $new_instance['current_maincat'];
		$instance['display'] 			= $new_instance['display'];
		$instance['thumb_width'] 		= strip_tags($new_instance['thumb_width']);
		$instance['num_img_in_row'] 	= $new_instance['num_img_in_row'];
		$instance['img_file_type'] 		= $new_instance['img_file_type'];
		return $instance;
	}
	
	function form($instance){
		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('', 'wpShop'), 'cat_slug' => '', 'cat_IDs' => '', 'current_cat' => FALSE, 'current_maincat' => FALSE, 'thumb_width' => '80', 'num_img_in_row' => '3');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cat_slug' ); ?>"><?php _e('Category Name or Slug:', 'wpShop'); ?></label><br/>
			<small><?php _e('If you like to query Product tags/terms from one category enter it\'s name or slug here. Otherwise leave empty!', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'cat_slug' ); ?>" name="<?php echo $this->get_field_name( 'cat_slug' ); ?>" value="<?php echo $instance['cat_slug']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cat_IDs' ); ?>"><?php _e('Category IDs:', 'wpShop'); ?></label><br/>
			<small><?php _e('If you like to query Product tags/terms from several categories enter their IDs seperated by comma eg. 1,3,4. Otherwise leave empty!', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'cat_IDs' ); ?>" name="<?php echo $this->get_field_name( 'cat_IDs' ); ?>" value="<?php echo $instance['cat_IDs']; ?>" style="width:97%;" />
		</p>
		
		<!-- query current cat || current main cat? Checkbox -->
		<p style="background:#F1F1F1;">
			<strong><?php _e('For use on Product Category Pages Only', 'wpShop'); ?></strong><br/>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['current_cat'], true ); ?> id="<?php echo $this->get_field_id( 'current_cat' ); ?>" name="<?php echo $this->get_field_name( 'current_cat' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'current_cat' ); ?>"><?php _e('Query Current Category', 'wpShop'); ?></label><br/>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['current_maincat'], true ); ?> id="<?php echo $this->get_field_id( 'current_maincat' ); ?>" name="<?php echo $this->get_field_name( 'current_maincat' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'current_maincat' ); ?>"><?php _e('Query Current Main (top level) Category', 'wpShop'); ?></label><br/>
			<small><?php _e('Check the appropriate box (not both!) if you like to query Product tags/terms from the current category or the current main (top level) category being viewed. This will only work if you have activated this widget on category pages! If you are using the widget on other pages please use one of the options above.', 'wpShop'); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'display' ); ?>"><?php _e('Display as:', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'display' ); ?>" name="<?php echo $this->get_field_name( 'display' ); ?>" class="widefat" style="width:97%;">
				<option value="Drop Down" <?php selected('Drop Down', $instance["display"]); ?>><?php _e('Drop Down', 'wpShop'); ?></option>
				<option value="List" <?php selected('List', $instance["display"]); ?>><?php _e('List', 'wpShop'); ?></option>
				<option value="Images" <?php selected('Images', $instance["display"]); ?>><?php _e('Images', 'wpShop'); ?></option>
			</select>
			<small style="color:red;"><?php _e('If you have selected to display your tags/terms as images, remember to create tag/term specific images for each!', 'wpShop'); ?></small>
		</p>
		
		<small><?php _e('The fields below apply only if you have checked "Display Images" from above.', 'wpShop'); ?></small>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'thumb_width' ); ?>"><?php _e('Thumb Width:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'thumb_width' ); ?>" name="<?php echo $this->get_field_name( 'thumb_width' ); ?>" value="<?php echo $instance['thumb_width']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'num_img_in_row' ); ?>"><?php _e('Number of images per row:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'num_img_in_row' ); ?>" name="<?php echo $this->get_field_name( 'num_img_in_row' ); ?>" value="<?php echo $instance['num_img_in_row']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'img_file_type' ); ?>"><?php _e('Image File Type', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'img_file_type' ); ?>" name="<?php echo $this->get_field_name( 'img_file_type' ); ?>" class="widefat" style="width:97%;">
				<option value="jpg" <?php selected('jpg', $instance["img_file_type"]); ?>><?php _e('jpg', 'wpShop'); ?></option>
				<option value="png" <?php selected('png', $instance["img_file_type"]); ?>><?php _e('png', 'wpShop'); ?></option>
				<option value="gif" <?php selected('gif', $instance["img_file_type"]); ?>><?php _e('gif', 'wpShop'); ?></option>
			</select>
		</p>
		
		
	<?php }
}

// Shop by Brand Widget
class ShopByBrandWidget extends WP_Widget {

	function ShopByBrandWidget() {
		$widget_ops 	= array('classname' => 'shop_by_widget widget-shop-by-brand', 'description' => __( 'For "Shop by Brand" custom Taxonomy', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'nws-shop-by-brand');
		$this->WP_Widget('nws-shop-by-brand', __('NWS Shop by Brand', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
		
		global $OPTION;
	
		extract($args);
		$title 				= apply_filters('widget_title', $instance['title'] );
		$cat_slug 			= $instance['cat_slug'];
		$cat_IDs 			= $instance['cat_IDs'];
		$current_cat 		= isset( $instance['current_cat'] ) ? $instance['current_cat'] : FALSE;
		$current_maincat 	= isset( $instance['current_maincat'] ) ? $instance['current_maincat'] : FALSE;
		$display 			= $instance['display'];
		$thumb_width 		= $instance['thumb_width'];
		$num_img_in_row 	= $instance['num_img_in_row'];
		$img_file_type 		= $instance['img_file_type'];
		
		# Before the widget
		echo $before_widget;

		# The title
		if ( $title )
		echo $before_title . $title . $after_title;

		# Output
		//check to see if the taxonomy exists first
		$taxonomy_exist = taxonomy_exists(__('brand','wpShop'));
		
		if($taxonomy_exist) {
			if ($cat_slug!='') {
				query_posts('category_name='.$cat_slug.'&showposts=-1');
			} elseif($cat_IDs!='') {
				query_posts('cat='.$cat_IDs.'&showposts=-1');
			} elseif (($current_cat) && (is_category())) {
				$cat = get_category( get_query_var( 'cat' ), false );
				query_posts('cat='.$cat->cat_ID.'&showposts=-1');
			}elseif (($current_maincat) && (is_category())) {
				$cat 		= get_category( get_query_var( 'cat' ), false );
				$topParent 	= NWS_get_root_category($cat,'allData');
				query_posts('cat='.$topParent->cat_ID.'&showposts=-1');
			}
			
			if (have_posts()) : while (have_posts()) : the_post();
				$posttags = get_the_brand();
				if($posttags !== FALSE) {						
					foreach($posttags as $tag) {
						$all_tags_arr[] 	= $tag->name; //USING JUST $tag MAKING $all_tags_arr A MULTI-DIMENSIONAL ARRAY, WHICH DOES WORK WITH array_unique
						$all_tagslugs_arr[] = $tag->slug;
					}
				}
			endwhile;  wp_reset_query();  endif; 
		
			if(!empty($all_tags_arr)){
				
				$tags_arr 		= array_unique($all_tags_arr); //REMOVES DUPLICATES
				$tagslugs_arr 	= array_unique($all_tagslugs_arr); //REMOVES DUPLICATES
				
				//sort asc
				sort($tags_arr);
				sort($tagslugs_arr);
				
				if ( $display == 'Images' ) { 
					global $wp_query;
					//get the queried object
					$queried_obj = $wp_query->get_queried_object(); ?>
					
					<div class="img_wrap">
						<?php
						$counter = $num_img_in_row;
						$a = 1;
						foreach ($tags_arr as $k => $tag) {
							$term_obj = get_term_by('name',$tags_arr[$k], __('brand', 'wpShop'));
							
							$img_src 		= get_option('siteurl').'/'. $OPTION['upload_path'].'/'.$term_obj->slug .'.'.$img_file_type;
							$des_src 		= $OPTION['upload_path'].'/cache';	
							$img_file 		= mkthumb($img_src,$des_src,$thumb_width,'width');    
							$imgURL 		= get_option('siteurl').'/'.$des_src.'/'.$img_file;
							$the_a_class 	= alternating_css_class($counter,$num_img_in_row,'first');
							$cur_class 		= (($tag == $queried_obj->name) ? 'current_term': '');
							
							echo '<a class="'.$the_a_class.' '.$cur_class.'" href="'.get_term_link($term_obj,__('brand','wpShop')).'" title="'.$term_obj->name .'"><img src="'.$imgURL .'" alt="'.$term_obj->slug .'" /></a>';
							
							$a++;
							$counter++;
						} ?>
					</div>
					
				<?php } elseif ( $display == 'List' ) { 
					
					global $wp_query;
					//get the queried object
					$queried_obj = $wp_query->get_queried_object();
				?>
		
					<ul>
						<?php foreach ($tags_arr as $k => $tag) { 
							$term_obj = get_term_by('name',$tags_arr[$k], __('brand', 'wpShop')); 
						?>
							<li <?php echo (($tag == $queried_obj->name) ? 'class="current_term"': ''); ?>>
								<?php echo '<a href="'.get_term_link($term_obj,__('brand','wpShop')).'">'.$term_obj->name .'</a>';?>
							</li>
						<?php } ?>
					</ul>
			
				<?php } else { ?>
					<div>
						<select name="tag-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">
							<option value="#"><?php _e('Please Select...','wpShop'); ?></option>
								<?php foreach ($tags_arr as $k => $tag) {
									$term_obj = get_term_by('name',$tags_arr[$k], __('brand', 'wpShop'));
									
									echo '<option value="'.get_term_link($term_obj,__('brand','wpShop')).'">'.$term_obj->name .'</option>';
									
								} ?>
						</select>
					</div>
				<?php }
			} 
		} else {
			echo "<p class='error'>".__('The widget you have activated needs that the "brand" custom taxonomy is active! Please activate it from your Theme Options > Design > General settings','wpShop')."</p>";
		}
	
		
		# After the widget
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 				= strip_tags($new_instance['title']);
		$instance['cat_slug'] 			= strip_tags($new_instance['cat_slug']);
		$instance['cat_IDs'] 			= $new_instance['cat_IDs'];
		$instance['current_cat'] 		= $new_instance['current_cat'];
		$instance['current_maincat'] 	= $new_instance['current_maincat'];
		$instance['display'] 			= $new_instance['display'];
		$instance['thumb_width'] 		= strip_tags($new_instance['thumb_width']);
		$instance['num_img_in_row'] 	= $new_instance['num_img_in_row'];
		$instance['img_file_type'] 		= $new_instance['img_file_type'];
		return $instance;
	}
	
	function form($instance){
		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('', 'wpShop'), 'cat_slug' => '', 'cat_IDs' => '', 'current_cat' => FALSE, 'current_maincat' => FALSE, 'thumb_width' => '80', 'num_img_in_row' => '3');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cat_slug' ); ?>"><?php _e('Category Name or Slug:', 'wpShop'); ?></label><br/>
			<small><?php _e('If you like to query Product tags/terms from one category enter it\'s name or slug here. Otherwise leave empty!', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'cat_slug' ); ?>" name="<?php echo $this->get_field_name( 'cat_slug' ); ?>" value="<?php echo $instance['cat_slug']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cat_IDs' ); ?>"><?php _e('Category IDs:', 'wpShop'); ?></label><br/>
			<small><?php _e('If you like to query Product tags/terms from several categories enter their IDs seperated by comma eg. 1,3,4. Otherwise leave empty!', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'cat_IDs' ); ?>" name="<?php echo $this->get_field_name( 'cat_IDs' ); ?>" value="<?php echo $instance['cat_IDs']; ?>" style="width:97%;" />
		</p>
		
		<!-- query current cat || current main cat? Checkbox -->
		<p style="background:#F1F1F1;">
			<strong><?php _e('For use on Product Category Pages Only', 'wpShop'); ?></strong><br/>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['current_cat'], true ); ?> id="<?php echo $this->get_field_id( 'current_cat' ); ?>" name="<?php echo $this->get_field_name( 'current_cat' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'current_cat' ); ?>"><?php _e('Query Current Category', 'wpShop'); ?></label><br/>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['current_maincat'], true ); ?> id="<?php echo $this->get_field_id( 'current_maincat' ); ?>" name="<?php echo $this->get_field_name( 'current_maincat' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'current_maincat' ); ?>"><?php _e('Query Current Main (top level) Category', 'wpShop'); ?></label><br/>
			<small><?php _e('Check the appropriate box (not both!) if you like to query Product tags/terms from the current category or the current main (top level) category being viewed. This will only work if you have activated this widget on category pages! If you are using the widget on other pages please use one of the options above.', 'wpShop'); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'display' ); ?>"><?php _e('Display as:', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'display' ); ?>" name="<?php echo $this->get_field_name( 'display' ); ?>" class="widefat" style="width:97%;">
				<option value="Drop Down" <?php selected('Drop Down', $instance["display"]); ?>><?php _e('Drop Down', 'wpShop'); ?></option>
				<option value="List" <?php selected('List', $instance["display"]); ?>><?php _e('List', 'wpShop'); ?></option>
				<option value="Images" <?php selected('Images', $instance["display"]); ?>><?php _e('Images', 'wpShop'); ?></option>
			</select>
			<small style="color:red;"><?php _e('If you have selected to display your tags/terms as images, remember to create tag/term specific images for each!', 'wpShop'); ?></small>
		</p>
		
		<small><?php _e('The fields below apply only if you have checked "Display Images" from above.', 'wpShop'); ?></small>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'thumb_width' ); ?>"><?php _e('Thumb Width:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'thumb_width' ); ?>" name="<?php echo $this->get_field_name( 'thumb_width' ); ?>" value="<?php echo $instance['thumb_width']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'num_img_in_row' ); ?>"><?php _e('Number of images per row:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'num_img_in_row' ); ?>" name="<?php echo $this->get_field_name( 'num_img_in_row' ); ?>" value="<?php echo $instance['num_img_in_row']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'img_file_type' ); ?>"><?php _e('Image File Type', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'img_file_type' ); ?>" name="<?php echo $this->get_field_name( 'img_file_type' ); ?>" class="widefat" style="width:97%;">
				<option value="jpg" <?php selected('jpg', $instance["img_file_type"]); ?>><?php _e('jpg', 'wpShop'); ?></option>
				<option value="png" <?php selected('png', $instance["img_file_type"]); ?>><?php _e('png', 'wpShop'); ?></option>
				<option value="gif" <?php selected('gif', $instance["img_file_type"]); ?>><?php _e('gif', 'wpShop'); ?></option>
			</select>
		</p>
		
		
	<?php }
}

// Shop by Selection Widget
class ShopBySelectionWidget extends WP_Widget {

	function ShopBySelectionWidget() {
		$widget_ops 	= array('classname' => 'shop_by_widget widget-shop-by-selection', 'description' => __( 'For "Shop by Selection" custom Taxonomy', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'nws-shop-by-selection');
		$this->WP_Widget('nws-shop-by-selection', __('NWS Shop by Selection', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
	
		global $OPTION;
	
		extract($args);
		$title 				= apply_filters('widget_title', $instance['title'] );
		$cat_slug 			= $instance['cat_slug'];
		$cat_IDs 			= $instance['cat_IDs'];
		$current_cat 		= isset( $instance['current_cat'] ) ? $instance['current_cat'] : FALSE;
		$current_maincat 	= isset( $instance['current_maincat'] ) ? $instance['current_maincat'] : FALSE;
		$display 			= $instance['display'];
		$thumb_width 		= $instance['thumb_width'];
		$num_img_in_row 	= $instance['num_img_in_row'];
		$img_file_type 		= $instance['img_file_type'];
		
		# Before the widget
		echo $before_widget;

		# The title
		if ( $title )
		echo $before_title . $title . $after_title;

		# Output
		//check to see if the taxonomy exists first
		$taxonomy_exist = taxonomy_exists(__('selection','wpShop'));
		if($taxonomy_exist) {
			if ($cat_slug!='') {
				query_posts('category_name='.$cat_slug.'&showposts=-1');
			} elseif($cat_IDs!='') {
				query_posts('cat='.$cat_IDs.'&showposts=-1');
			} elseif (($current_cat) && (is_category())) {
				$cat = get_category( get_query_var( 'cat' ), false );
				query_posts('cat='.$cat->cat_ID.'&showposts=-1');
			}elseif (($current_maincat) && (is_category())) {
				$cat 		= get_category( get_query_var( 'cat' ), false );
				$topParent 	= NWS_get_root_category($cat,'allData');
				query_posts('cat='.$topParent->cat_ID.'&showposts=-1');
			}
			if (have_posts()) : while (have_posts()) : the_post();
				$posttags = get_the_selection();
				if($posttags !== FALSE) {						
					foreach($posttags as $tag) {
						$all_tags_arr[] 	= $tag->name; //USING JUST $tag MAKING $all_tags_arr A MULTI-DIMENSIONAL ARRAY, WHICH DOES WORK WITH array_unique
						$all_tagslugs_arr[] = $tag->slug;
					}
				}
			endwhile;  wp_reset_query();  endif; 
		
			if(!empty($all_tags_arr)){
			
				$tags_arr 		= array_unique($all_tags_arr); //REMOVES DUPLICATES
				$tagslugs_arr 	= array_unique($all_tagslugs_arr); //REMOVES DUPLICATES
				
				//sort asc
				sort($tags_arr);
				sort($tagslugs_arr);
				
				if ( $display == 'Images' ) {
					global $wp_query;
					//get the queried object
					$queried_obj = $wp_query->get_queried_object(); ?>
					
					<div class="img_wrap">
						<?php
						$counter = $num_img_in_row;
						$a = 1;
						foreach ($tags_arr as $k => $tag) {
							$term_obj = get_term_by('name',$tags_arr[$k], __('selection', 'wpShop'));
							
							$img_src 		= get_option('siteurl').'/'. $OPTION['upload_path'].'/'.$term_obj->slug .'.'.$img_file_type;
							$des_src 		= $OPTION['upload_path'].'/cache';	
							$img_file 		= mkthumb($img_src,$des_src,$thumb_width,'width');    
							$imgURL 		= get_option('siteurl').'/'.$des_src.'/'.$img_file;
							$the_a_class 	= alternating_css_class($counter,$num_img_in_row,'first');
							$cur_class 		= (($tag == $queried_obj->name) ? 'current_term': '');
							
							echo '<a class="'.$the_a_class.' '.$cur_class.'" href="'.get_term_link($term_obj,__('selection','wpShop')).'" title="'.$term_obj->name .'"><img src="'.$imgURL .'" alt="'.$term_obj->slug .'" /></a>';
							
							$a++;
							$counter++;
						} ?>
					</div>
					
				<?php } elseif ( $display == 'List' ) { 
					
					global $wp_query;
					//get the queried object
					$queried_obj = $wp_query->get_queried_object();
				?>
			
					<ul>
						<?php foreach ($tags_arr as $k => $tag) { 
							$term_obj = get_term_by('name',$tags_arr[$k], __('selection', 'wpShop')); 
						?>
							<li <?php echo (($tag == $queried_obj->name) ? 'class="current_term"': ''); ?>>
								<?php echo '<a href="'.get_term_link($term_obj,__('selection','wpShop')).'">'.$term_obj->name .'</a>';?>
							</li>
						<?php } ?>
					</ul>
				
				<?php } else { ?>
					<div>
						<select name="tag-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">
							<option value="#"><?php _e('Please Select...','wpShop'); ?></option>
								<?php foreach ($tags_arr as $k => $tag) {
									$term_obj = get_term_by('name',$tags_arr[$k], __('selection', 'wpShop'));
									
									echo '<option value="'.get_term_link($term_obj,__('selection','wpShop')).'">'.$term_obj->name .'</option>';
									
								} ?>
						</select>
					</div>
				<?php }
			} 
		} else {
			echo "<p class='error'>".__('The widget you have activated needs that the "selection" custom taxonomy is active! Please activate it from your Theme Options > Design > General settings','wpShop')."</p>";
		}
		
		# After the widget
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 				= strip_tags($new_instance['title']);
		$instance['cat_slug'] 			= strip_tags($new_instance['cat_slug']);
		$instance['cat_IDs'] 			= $new_instance['cat_IDs'];
		$instance['current_cat'] 		= $new_instance['current_cat'];
		$instance['current_maincat'] 	= $new_instance['current_maincat'];
		$instance['display'] 			= $new_instance['display'];
		$instance['thumb_width'] 		= strip_tags($new_instance['thumb_width']);
		$instance['num_img_in_row'] 	= $new_instance['num_img_in_row'];
		$instance['img_file_type'] 		= $new_instance['img_file_type'];
		return $instance;
	}
	
	function form($instance){
		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('', 'wpShop'), 'cat_slug' => '', 'cat_IDs' => '', 'current_cat' => FALSE, 'current_maincat' => FALSE, 'thumb_width' => '80', 'num_img_in_row' => '3');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cat_slug' ); ?>"><?php _e('Category Name or Slug:', 'wpShop'); ?></label><br/>
			<small><?php _e('If you like to query Product tags/terms from one category enter it\'s name or slug here. Otherwise leave empty!', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'cat_slug' ); ?>" name="<?php echo $this->get_field_name( 'cat_slug' ); ?>" value="<?php echo $instance['cat_slug']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cat_IDs' ); ?>"><?php _e('Category IDs:', 'wpShop'); ?></label><br/>
			<small><?php _e('If you like to query Product tags/terms from several categories enter their IDs seperated by comma eg. 1,3,4. Otherwise leave empty!', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'cat_IDs' ); ?>" name="<?php echo $this->get_field_name( 'cat_IDs' ); ?>" value="<?php echo $instance['cat_IDs']; ?>" style="width:97%;" />
		</p>
		
		<!-- query current cat || current main cat? Checkbox -->
		<p style="background:#F1F1F1;">
			<strong><?php _e('For use on Product Category Pages Only', 'wpShop'); ?></strong><br/>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['current_cat'], true ); ?> id="<?php echo $this->get_field_id( 'current_cat' ); ?>" name="<?php echo $this->get_field_name( 'current_cat' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'current_cat' ); ?>"><?php _e('Query Current Category', 'wpShop'); ?></label><br/>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['current_maincat'], true ); ?> id="<?php echo $this->get_field_id( 'current_maincat' ); ?>" name="<?php echo $this->get_field_name( 'current_maincat' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'current_maincat' ); ?>"><?php _e('Query Current Main (top level) Category', 'wpShop'); ?></label><br/>
			<small><?php _e('Check the appropriate box (not both!) if you like to query Product tags/terms from the current category or the current main (top level) category being viewed. This will only work if you have activated this widget on category pages! If you are using the widget on other pages please use one of the options above.', 'wpShop'); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'display' ); ?>"><?php _e('Display as:', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'display' ); ?>" name="<?php echo $this->get_field_name( 'display' ); ?>" class="widefat" style="width:97%;">
				<option value="Drop Down" <?php selected('Drop Down', $instance["display"]); ?>><?php _e('Drop Down', 'wpShop'); ?></option>
				<option value="List" <?php selected('List', $instance["display"]); ?>><?php _e('List', 'wpShop'); ?></option>
				<option value="Images" <?php selected('Images', $instance["display"]); ?>><?php _e('Images', 'wpShop'); ?></option>
			</select>
			<small style="color:red;"><?php _e('If you have selected to display your tags/terms as images, remember to create tag/term specific images for each!', 'wpShop'); ?></small>
		</p>
		
		<small><?php _e('The fields below apply only if you have checked "Display Images" from above.', 'wpShop'); ?></small>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'thumb_width' ); ?>"><?php _e('Thumb Width:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'thumb_width' ); ?>" name="<?php echo $this->get_field_name( 'thumb_width' ); ?>" value="<?php echo $instance['thumb_width']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'num_img_in_row' ); ?>"><?php _e('Number of images per row:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'num_img_in_row' ); ?>" name="<?php echo $this->get_field_name( 'num_img_in_row' ); ?>" value="<?php echo $instance['num_img_in_row']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'img_file_type' ); ?>"><?php _e('Image File Type', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'img_file_type' ); ?>" name="<?php echo $this->get_field_name( 'img_file_type' ); ?>" class="widefat" style="width:97%;">
				<option value="jpg" <?php selected('jpg', $instance["img_file_type"]); ?>><?php _e('jpg', 'wpShop'); ?></option>
				<option value="png" <?php selected('png', $instance["img_file_type"]); ?>><?php _e('png', 'wpShop'); ?></option>
				<option value="gif" <?php selected('gif', $instance["img_file_type"]); ?>><?php _e('gif', 'wpShop'); ?></option>
			</select>
		</p>
		
		
	<?php }
}

// Shop by Style Widget
class ShopByStyleWidget extends WP_Widget {

	function ShopByStyleWidget() {
		$widget_ops 	= array('classname' => 'shop_by_widget widget-shop-by-style', 'description' => __( 'For "Shop by Style" custom Taxonomy', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'nws-shop-by-style');
		$this->WP_Widget('nws-shop-by-style', __('NWS Shop by Style', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
		global $OPTION;
	
		extract($args);
		$title 				= apply_filters('widget_title', $instance['title'] );
		$cat_slug 			= $instance['cat_slug'];
		$cat_IDs 			= $instance['cat_IDs'];
		$current_cat 		= isset( $instance['current_cat'] ) ? $instance['current_cat'] : FALSE;
		$current_maincat 	= isset( $instance['current_maincat'] ) ? $instance['current_maincat'] : FALSE;
		$display 			= $instance['display'];
		$thumb_width 		= $instance['thumb_width'];
		$num_img_in_row 	= $instance['num_img_in_row'];
		$img_file_type 		= $instance['img_file_type'];
		
		# Before the widget
		echo $before_widget;

		# The title
		if ( $title )
		echo $before_title . $title . $after_title;

		# Output
		//check to see if the taxonomy exists first
		$taxonomy_exist = taxonomy_exists(__('style','wpShop'));
		if($taxonomy_exist) {
			if ($cat_slug!='') {
				query_posts('category_name='.$cat_slug.'&showposts=-1');
			} elseif($cat_IDs!='') {
				query_posts('cat='.$cat_IDs.'&showposts=-1');
			} elseif (($current_cat) && (is_category())) {
				$cat = get_category( get_query_var( 'cat' ), false );
				query_posts('cat='.$cat->cat_ID.'&showposts=-1');
			}elseif (($current_maincat) && (is_category())) {
				$cat 		= get_category( get_query_var( 'cat' ), false );
				$topParent 	= NWS_get_root_category($cat,'allData');
				query_posts('cat='.$topParent->cat_ID.'&showposts=-1');
			}
			if (have_posts()) : while (have_posts()) : the_post();
				$posttags = get_the_style();
				if($posttags !== FALSE) {					
					foreach($posttags as $tag) {
						$all_tags_arr[] 	= $tag->name; //USING JUST $tag MAKING $all_tags_arr A MULTI-DIMENSIONAL ARRAY, WHICH DOES WORK WITH array_unique
						$all_tagslugs_arr[] = $tag->slug;
					}
				}
			endwhile;  wp_reset_query();  endif; 
			
			if(!empty($all_tags_arr)){
				
				$tags_arr 		= array_unique($all_tags_arr); //REMOVES DUPLICATES
				$tagslugs_arr 	= array_unique($all_tagslugs_arr); //REMOVES DUPLICATES
				
				//sort asc
				sort($tags_arr);
				sort($tagslugs_arr);
				
				if ( $display == 'Images' ) { 
					global $wp_query;
					//get the queried object
					$queried_obj = $wp_query->get_queried_object(); ?>
					
					<div class="img_wrap">
						<?php
						$counter = $num_img_in_row;
						$a = 1;
						foreach ($tags_arr as $k => $tag) {
							$term_obj = get_term_by('name',$tags_arr[$k], __('style', 'wpShop'));
							
							$img_src 		= get_option('siteurl').'/'. $OPTION['upload_path'].'/'.$term_obj->slug .'.'.$img_file_type;
							$des_src 		= $OPTION['upload_path'].'/cache';	
							$img_file 		= mkthumb($img_src,$des_src,$thumb_width,'width');    
							$imgURL 		= get_option('siteurl').'/'.$des_src.'/'.$img_file;
							$the_a_class 	= alternating_css_class($counter,$num_img_in_row,'first');
							$cur_class 		= (($tag == $queried_obj->name) ? 'current_term': '');
							
							echo '<a class="'.$the_a_class.' '.$cur_class.'" href="'.get_term_link($term_obj,__('style','wpShop')).'" title="'.$term_obj->name .'"><img src="'.$imgURL .'" alt="'.$term_obj->slug .'" /></a>';
							
							$a++;
							$counter++;
						} ?>
					</div>
					
				<?php } elseif ( $display == 'List' ) { 
					
					global $wp_query;
					//get the queried object
					$queried_obj = $wp_query->get_queried_object();
				?>
					
					<ul>
						<?php foreach ($tags_arr as $k => $tag) { 
							$term_obj = get_term_by('name',$tags_arr[$k], __('style', 'wpShop')); 
						?>
							<li <?php echo (($tag == $queried_obj->name) ? 'class="current_term"': ''); ?>>
								<?php echo '<a href="'.get_term_link($term_obj,__('style','wpShop')).'">'.$term_obj->name .'</a>';?>
							</li>
						<?php } ?>
					</ul>
					
				
				<?php } else { ?>
					<div>
						<select name="tag-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">
							<option value="#"><?php _e('Please Select...','wpShop'); ?></option>
								<?php foreach ($tags_arr as $k => $tag) {
									$term_obj = get_term_by('name',$tags_arr[$k], __('style', 'wpShop'));
									
									echo '<option value="'.get_term_link($term_obj,__('style','wpShop')).'">'.$term_obj->name .'</option>';
									
								} ?>
						</select>
					</div>
				<?php }
			} 
		} else {
			echo "<p class='error'>".__('The widget you have activated needs that the "style" custom taxonomy is active! Please activate it from your Theme Options > Design > General settings','wpShop')."</p>";
		}
		
		# After the widget
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 				= strip_tags($new_instance['title']);
		$instance['cat_slug'] 			= strip_tags($new_instance['cat_slug']);
		$instance['cat_IDs'] 			= $new_instance['cat_IDs'];
		$instance['current_cat'] 		= $new_instance['current_cat'];
		$instance['current_maincat'] 	= $new_instance['current_maincat'];
		$instance['display'] 			= $new_instance['display'];
		$instance['thumb_width'] 		= strip_tags($new_instance['thumb_width']);
		$instance['num_img_in_row'] 	= $new_instance['num_img_in_row'];
		$instance['img_file_type'] 		= $new_instance['img_file_type'];
		return $instance;
	}
	
	function form($instance){
		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('', 'wpShop'), 'cat_slug' => '', 'cat_IDs' => '', 'current_cat' => FALSE, 'current_maincat' => FALSE, 'thumb_width' => '80', 'num_img_in_row' => '3');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cat_slug' ); ?>"><?php _e('Category Name or Slug:', 'wpShop'); ?></label><br/>
			<small><?php _e('If you like to query Product tags/terms from one category enter it\'s name or slug here. Otherwise leave empty!', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'cat_slug' ); ?>" name="<?php echo $this->get_field_name( 'cat_slug' ); ?>" value="<?php echo $instance['cat_slug']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cat_IDs' ); ?>"><?php _e('Category IDs:', 'wpShop'); ?></label><br/>
			<small><?php _e('If you like to query Product tags/terms from several categories enter their IDs seperated by comma eg. 1,3,4. Otherwise leave empty!', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'cat_IDs' ); ?>" name="<?php echo $this->get_field_name( 'cat_IDs' ); ?>" value="<?php echo $instance['cat_IDs']; ?>" style="width:97%;" />
		</p>
		
		<!-- query current cat || current main cat? Checkbox -->
		<p style="background:#F1F1F1;">
			<strong><?php _e('For use on Product Category Pages Only', 'wpShop'); ?></strong><br/>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['current_cat'], true ); ?> id="<?php echo $this->get_field_id( 'current_cat' ); ?>" name="<?php echo $this->get_field_name( 'current_cat' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'current_cat' ); ?>"><?php _e('Query Current Category', 'wpShop'); ?></label><br/>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['current_maincat'], true ); ?> id="<?php echo $this->get_field_id( 'current_maincat' ); ?>" name="<?php echo $this->get_field_name( 'current_maincat' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'current_maincat' ); ?>"><?php _e('Query Current Main (top level) Category', 'wpShop'); ?></label><br/>
			<small><?php _e('Check the appropriate box (not both!) if you like to query Product tags/terms from the current category or the current main (top level) category being viewed. This will only work if you have activated this widget on category pages! If you are using the widget on other pages please use one of the options above.', 'wpShop'); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'display' ); ?>"><?php _e('Display as:', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'display' ); ?>" name="<?php echo $this->get_field_name( 'display' ); ?>" class="widefat" style="width:97%;">
				<option value="Drop Down" <?php selected('Drop Down', $instance["display"]); ?>><?php _e('Drop Down', 'wpShop'); ?></option>
				<option value="List" <?php selected('List', $instance["display"]); ?>><?php _e('List', 'wpShop'); ?></option>
				<option value="Images" <?php selected('Images', $instance["display"]); ?>><?php _e('Images', 'wpShop'); ?></option>
			</select>
			<small style="color:red;"><?php _e('If you have selected to display your tags/terms as images, remember to create tag/term specific images for each!', 'wpShop'); ?></small>
		</p>
		
		<small><?php _e('The fields below apply only if you have checked "Display Images" from above.', 'wpShop'); ?></small>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'thumb_width' ); ?>"><?php _e('Thumb Width:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'thumb_width' ); ?>" name="<?php echo $this->get_field_name( 'thumb_width' ); ?>" value="<?php echo $instance['thumb_width']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'num_img_in_row' ); ?>"><?php _e('Number of images per row:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'num_img_in_row' ); ?>" name="<?php echo $this->get_field_name( 'num_img_in_row' ); ?>" value="<?php echo $instance['num_img_in_row']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'img_file_type' ); ?>"><?php _e('Image File Type', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'img_file_type' ); ?>" name="<?php echo $this->get_field_name( 'img_file_type' ); ?>" class="widefat" style="width:97%;">
				<option value="jpg" <?php selected('jpg', $instance["img_file_type"]); ?>><?php _e('jpg', 'wpShop'); ?></option>
				<option value="png" <?php selected('png', $instance["img_file_type"]); ?>><?php _e('png', 'wpShop'); ?></option>
				<option value="gif" <?php selected('gif', $instance["img_file_type"]); ?>><?php _e('gif', 'wpShop'); ?></option>
			</select>
		</p>
		
		
	<?php }
}

// Shop by Price Widget
class ShopByPriceWidget extends WP_Widget {

	function ShopByPriceWidget() {
		$widget_ops 	= array('classname' => 'shop_by_widget widget-shop-by-price', 'description' => __( 'For "Shop by Price" custom Taxonomy', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'nws-shop-by-price');
		$this->WP_Widget('nws-shop-by-price', __('NWS Shop by Price', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
	
		global $OPTION;
	
		extract($args);
		$title 				= apply_filters('widget_title', $instance['title'] );
		$cat_slug 			= $instance['cat_slug'];
		$cat_IDs 			= $instance['cat_IDs'];
		$current_cat 		= isset( $instance['current_cat'] ) ? $instance['current_cat'] : FALSE;
		$current_maincat 	= isset( $instance['current_maincat'] ) ? $instance['current_maincat'] : FALSE;
		$display 			= $instance['display'];
		$thumb_width 		= $instance['thumb_width'];
		$num_img_in_row 	= $instance['num_img_in_row'];
		$img_file_type 		= $instance['img_file_type'];
		
		# Before the widget
		echo $before_widget;

		# The title
		if ( $title )
		echo $before_title . $title . $after_title;

		# Output
		//check to see if the taxonomy exists first
		$taxonomy_exist = taxonomy_exists(__('price','wpShop'));
		if($taxonomy_exist) {
			if ($cat_slug!='') {
				query_posts('category_name='.$cat_slug.'&showposts=-1');
			} elseif($cat_IDs!='') {
				query_posts('cat='.$cat_IDs.'&showposts=-1');
			} elseif (($current_cat) && (is_category())) {
				$cat = get_category( get_query_var( 'cat' ), false );
				query_posts('cat='.$cat->cat_ID.'&showposts=-1');
			}elseif (($current_maincat) && (is_category())) {
				$cat 		= get_category( get_query_var( 'cat' ), false );
				$topParent 	= NWS_get_root_category($cat,'allData');
				query_posts('cat='.$topParent->cat_ID.'&showposts=-1');
			}
			if (have_posts()) : while (have_posts()) : the_post();
				$posttags = get_the_price();
				if($posttags !== FALSE) {					
					foreach($posttags as $tag) {
						$all_tags_arr[] 	= $tag->name; //USING JUST $tag MAKING $all_tags_arr A MULTI-DIMENSIONAL ARRAY, WHICH DOES WORK WITH array_unique
						$all_tagslugs_arr[] = $tag->slug;
					}
				}
			endwhile;  wp_reset_query(); endif; 
		
			if(!empty($all_tags_arr)){
				
				$tags_arr 		= array_unique($all_tags_arr); //REMOVES DUPLICATES
				$tagslugs_arr 	= array_unique($all_tagslugs_arr); //REMOVES DUPLICATES
				
				//sort asc
				sort($tags_arr);
				sort($tagslugs_arr);
				
				if ( $display == 'Images' ) { 
					global $wp_query;
					//get the queried object
					$queried_obj = $wp_query->get_queried_object(); ?>
					
					<div class="img_wrap">
						<?php
						$counter = $num_img_in_row;
						$a = 1;
						foreach ($tags_arr as $k => $tag) {
							$term_obj = get_term_by('name',$tags_arr[$k], __('price', 'wpShop'));
							
							$img_src 		= get_option('siteurl').'/'. $OPTION['upload_path'].'/'.$term_obj->slug .'.'.$img_file_type;
							$des_src 		= $OPTION['upload_path'].'/cache';	
							$img_file 		= mkthumb($img_src,$des_src,$thumb_width,'width');    
							$imgURL 		= get_option('siteurl').'/'.$des_src.'/'.$img_file;
							$the_a_class 	= alternating_css_class($counter,$num_img_in_row,'first');
							$cur_class 		= (($tag == $queried_obj->name) ? 'current_term': '');
							
							echo '<a class="'.$the_a_class.' '.$cur_class.'" href="'.get_term_link($term_obj,__('price','wpShop')).'" title="'.$term_obj->name .'"><img src="'.$imgURL .'" alt="'.$term_obj->slug .'" /></a>';
							
							$a++;
							$counter++;
						} ?>
					</div>
				
				<?php } elseif ( $display == 'List' ) { 
					
					global $wp_query;
					//get the queried object
					$queried_obj = $wp_query->get_queried_object();
				?>
					
					<ul>
						<?php foreach ($tags_arr as $k => $tag) { 
							$term_obj = get_term_by('name',$tags_arr[$k], __('price', 'wpShop')); 
						?>
							<li <?php echo (($tag == $queried_obj->name) ? 'class="current_term"': ''); ?>>
								<?php echo '<a href="'.get_term_link($term_obj,__('price','wpShop')).'">'.$term_obj->name .'</a>';?>
							</li>
						<?php } ?>
					</ul>
				
				
				<?php } else { 
				?>
					<div>
						<select name="tag-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">
							<option value="#"><?php _e('Please Select...','wpShop'); ?></option>
								<?php foreach ($tags_arr as $k => $tag) {
									$term_obj = get_term_by('name',$tags_arr[$k], __('price', 'wpShop'));
									
									echo '<option value="'.get_term_link($term_obj,__('price','wpShop')).'">'.$term_obj->name .'</option>';
									
								} ?>
						</select>
					</div>
				<?php }
			} 
	
		} else {
			echo "<p class='error'>".__('The widget you have activated needs that the "price" custom taxonomy is active! Please activate it from your Theme Options > Design > General settings','wpShop')."</p>";
		}
		# After the widget
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 				= strip_tags($new_instance['title']);
		$instance['cat_slug'] 			= strip_tags($new_instance['cat_slug']);
		$instance['cat_IDs'] 			= $new_instance['cat_IDs'];
		$instance['current_cat'] 		= $new_instance['current_cat'];
		$instance['current_maincat'] 	= $new_instance['current_maincat'];
		$instance['display'] 			= $new_instance['display'];
		$instance['thumb_width'] 		= strip_tags($new_instance['thumb_width']);
		$instance['num_img_in_row'] 	= $new_instance['num_img_in_row'];
		$instance['img_file_type'] 		= $new_instance['img_file_type'];
		return $instance;
	}
	
	function form($instance){
		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('', 'wpShop'), 'cat_slug' => '', 'cat_IDs' => '', 'current_cat' => FALSE, 'current_maincat' => FALSE, 'thumb_width' => '80', 'num_img_in_row' => '3');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cat_slug' ); ?>"><?php _e('Category Name or Slug:', 'wpShop'); ?></label><br/>
			<small><?php _e('If you like to query Product tags/terms from one category enter it\'s name or slug here. Otherwise leave empty!', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'cat_slug' ); ?>" name="<?php echo $this->get_field_name( 'cat_slug' ); ?>" value="<?php echo $instance['cat_slug']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cat_IDs' ); ?>"><?php _e('Category IDs:', 'wpShop'); ?></label><br/>
			<small><?php _e('If you like to query Product tags/terms from several categories enter their IDs seperated by comma eg. 1,3,4. Otherwise leave empty!', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'cat_IDs' ); ?>" name="<?php echo $this->get_field_name( 'cat_IDs' ); ?>" value="<?php echo $instance['cat_IDs']; ?>" style="width:97%;" />
		</p>
		
		<!-- query current cat || current main cat? Checkbox -->
		<p style="background:#F1F1F1;">
			<strong><?php _e('For use on Product Category Pages Only', 'wpShop'); ?></strong><br/>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['current_cat'], true ); ?> id="<?php echo $this->get_field_id( 'current_cat' ); ?>" name="<?php echo $this->get_field_name( 'current_cat' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'current_cat' ); ?>"><?php _e('Query Current Category', 'wpShop'); ?></label><br/>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['current_maincat'], true ); ?> id="<?php echo $this->get_field_id( 'current_maincat' ); ?>" name="<?php echo $this->get_field_name( 'current_maincat' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'current_maincat' ); ?>"><?php _e('Query Current Main (top level) Category', 'wpShop'); ?></label><br/>
			<small><?php _e('Check the appropriate box (not both!) if you like to query Product tags/terms from the current category or the current main (top level) category being viewed. This will only work if you have activated this widget on category pages! If you are using the widget on other pages please use one of the options above.', 'wpShop'); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'display' ); ?>"><?php _e('Display as:', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'display' ); ?>" name="<?php echo $this->get_field_name( 'display' ); ?>" class="widefat" style="width:97%;">
				<option value="Drop Down" <?php selected('Drop Down', $instance["display"]); ?>><?php _e('Drop Down', 'wpShop'); ?></option>
				<option value="List" <?php selected('List', $instance["display"]); ?>><?php _e('List', 'wpShop'); ?></option>
				<option value="Images" <?php selected('Images', $instance["display"]); ?>><?php _e('Images', 'wpShop'); ?></option>
			</select>
			<small style="color:red;"><?php _e('If you have selected to display your tags/terms as images, remember to create tag/term specific images for each!', 'wpShop'); ?></small>
		</p>
		
		<small><?php _e('The fields below apply only if you have checked "Display Images" from above.', 'wpShop'); ?></small>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'thumb_width' ); ?>"><?php _e('Thumb Width:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'thumb_width' ); ?>" name="<?php echo $this->get_field_name( 'thumb_width' ); ?>" value="<?php echo $instance['thumb_width']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'num_img_in_row' ); ?>"><?php _e('Number of images per row:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'num_img_in_row' ); ?>" name="<?php echo $this->get_field_name( 'num_img_in_row' ); ?>" value="<?php echo $instance['num_img_in_row']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'img_file_type' ); ?>"><?php _e('Image File Type', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'img_file_type' ); ?>" name="<?php echo $this->get_field_name( 'img_file_type' ); ?>" class="widefat" style="width:97%;">
				<option value="jpg" <?php selected('jpg', $instance["img_file_type"]); ?>><?php _e('jpg', 'wpShop'); ?></option>
				<option value="png" <?php selected('png', $instance["img_file_type"]); ?>><?php _e('png', 'wpShop'); ?></option>
				<option value="gif" <?php selected('gif', $instance["img_file_type"]); ?>><?php _e('gif', 'wpShop'); ?></option>
			</select>
		</p>
		
		
	<?php }
}

// the FAQs Widget
class FAQs extends WP_Widget {

	function FAQs() {
		$widget_ops 	= array('classname' => 'widget_faq', 'description' => __( 'For Commonly Asked Questions', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'nws-faqs');
		$this->WP_Widget('nws-faqs', __('NWS FAQs', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
		extract($args);
		$title 		= apply_filters('widget_title', $instance['title'] );
		$q1 		= $instance['q1'];
		$link1 		= $instance['link1'];
		$q2 		= $instance['q2'];
		$link2 		= $instance['link2'];
		$q3 		= $instance['q3'];
		$link3 		= $instance['link3'];
		# Before the widget
		echo $before_widget;

		# The title
		if ( $title )
		echo $before_title . $title . $after_title;

		# Output
		?>
	
		<ul>
			<li><a href="<?php echo $link1;?>"><?php echo $q1;?></a></li>
			<li><a href="<?php echo $link2;?>"><?php echo $q2;?></a></li>
			<li><a href="<?php echo $link3;?>"><?php echo $q3;?></a></li>
		</ul>
			
		
		<?php
		# After the widget
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 		= strip_tags($new_instance['title']);
		$instance['q1'] 		= strip_tags($new_instance['q1']);
		$instance['link1'] 		= $new_instance['link1'];
		$instance['q2'] 		= strip_tags($new_instance['q2']);
		$instance['link2'] 		= $new_instance['link2'];
		$instance['q3'] 		= strip_tags($new_instance['q3']);
		$instance['link3'] 		= $new_instance['link3'];
		return $instance;
	}
	
	function form($instance){
		// Set up some default widget settings.
		$defaults = array( 'title' => __('', 'wpShop'), 'q1' => '', 'link1' => '', 'q2' => '', 'link2' => '', 'q3' => '', 'link3' => '');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'q1' ); ?>"><?php _e('Question 1:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'q1' ); ?>" name="<?php echo $this->get_field_name( 'q1' ); ?>" value="<?php echo $instance['q1']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link1' ); ?>"><?php _e('Links to (full path):', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'link1' ); ?>" name="<?php echo $this->get_field_name( 'link1' ); ?>" value="<?php echo $instance['link1']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'q2' ); ?>"><?php _e('Question 2:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'q2' ); ?>" name="<?php echo $this->get_field_name( 'q2' ); ?>" value="<?php echo $instance['q2']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link2' ); ?>"><?php _e('Links to (full path):', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'link2' ); ?>" name="<?php echo $this->get_field_name( 'link2' ); ?>" value="<?php echo $instance['link2']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'q3' ); ?>"><?php _e('Question 3:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'q3' ); ?>" name="<?php echo $this->get_field_name( 'q3' ); ?>" value="<?php echo $instance['q3']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link3' ); ?>"><?php _e('Links to (full path):', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'link3' ); ?>" name="<?php echo $this->get_field_name( 'link3' ); ?>" value="<?php echo $instance['link3']; ?>" style="width:97%;" />
		</p>
	<?php }
}

// most recent Prods in current category Widget
class RecentProds extends WP_Widget {

	function RecentProds() {
		$widget_ops 	= array('classname' => 'widget_recentProds', 'description' => __( 'Display Recent Products', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'nws-recent-products');
		$this->WP_Widget('nws-recent-products', __('NWS Recent Products', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
		global $OPTION;
		
		extract($args);
		$title 				= apply_filters('widget_title', $instance['title'] );
		
		$cat_slug 			= $instance['cat_slug'];
		$cat_IDs 			= $instance['cat_IDs'];
		$current_cat 		= isset( $instance['current_cat'] ) ? $instance['current_cat'] : FALSE;
		$current_maincat 	= isset( $instance['current_maincat'] ) ? $instance['current_maincat'] : FALSE;
		
		$display 			= $instance['display'];
		$showposts 			= $instance['showposts'];
		$img_size 			= $instance['img_size'];
		$num_img_in_row 	= $instance['num_img_in_row'];
		
		$wp_thumb 			= isset( $instance['wp_thumb'] ) ? $instance['wp_thumb'] : FALSE;
		$scroll 		= isset( $instance['scroll'] ) ? $instance['scroll'] : FALSE;
		
		$prod_title 	= isset( $instance['prod_title'] ) ? $instance['prod_title'] : FALSE;
		$prod_price 	= isset( $instance['prod_price'] ) ? $instance['prod_price'] : FALSE;
		$prod_btn 		= isset( $instance['prod_btn'] ) ? $instance['prod_btn'] : FALSE;
		
		
		# Before the widget
		echo $before_widget;

		# The title
		if ( $title )
		echo $before_title . $title . $after_title;

		# Output
		// prepare the query parameters according to widget settings
		
		if ($cat_slug!='') {
			$param		= array(
				'category_name' 	=> $cat_slug,
				'showposts'			=> $showposts, 
				'caller_get_posts'	=> 1
			);
		} elseif($cat_IDs!='') {
			$param		= array(
				'cat' 				=> $cat_IDs,
				'showposts'			=> $showposts, 
				'caller_get_posts'	=> 1
			);
		} elseif (($current_cat) && (is_category())) {
			$cat = get_category( get_query_var( 'cat' ), false );
			$param		= array(
				'cat' 				=> $cat->term_id,
				'showposts'			=> $showposts, 
				'caller_get_posts'	=> 1
			);
		}elseif (($current_maincat) && (is_category())) {
			$cat 		= get_category( get_query_var( 'cat' ), false );
			$topParent 	= NWS_get_root_category($cat,'allData');
			$param		= array(
				'cat' 				=> $topParent->term_id,
				'showposts'			=> $showposts, 
				'caller_get_posts'	=> 1
			);
		}
		
		// query	
		$my_catRecent_query = new wp_query($param);
		
		// do we have posts?	
		if($my_catRecent_query->have_posts()) 
		{
		
		// some html before the loop
		if ($scroll && $display == 'Images') { ?>
			<div class="widget_content_wrap prods_scrollable_wrap">
			<a class="prev browse left"></a>
			<div class="prods_scrollable">
		<?php } 
		
			
			if ( $display == 'Images' ) { ?>
				<ul <?php echo ($scroll ? '': 'class="widget_content_wrap clearfix"');?>>
			<?php }
			
			if ( $display == 'List' ) { 
				echo "<ul>";
			}
			if ( $display == 'Drop Down' ) { ?>
				<div>
					<select name="prod-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">
						<option value="#"><?php _e('Please Select...','wpShop'); ?></option>
			<?php }
			
			// set counter
			$counter = $num_img_in_row;
			$a = 1;	
			// run the loop 			
			while ($my_catRecent_query->have_posts()) 
			{ 	
				$my_catRecent_query->the_post(); 
				
				//get post id
				$postid = get_the_ID();
				
				// do we want images?
				if ( $display == 'Images' ) { 
					
					$output 		= my_attachment_images(0,1);
					$imgNum 		= count($output);
					if($imgNum != 0){
						$imgURL		= array();
						foreach($output as $v){
							
							$img_src 	= $v;
							
							// do we want the WordPress Generated thumbs?
							if ($wp_thumb) {
								//get the file type
								$img_file_type = strrchr($img_src, '.');
								//get the image name without the file type
								$parts = explode($img_file_type,$img_src);
								// get the thumbnail dimmensions
								$width = get_option('thumbnail_size_w');
								$height = get_option('thumbnail_size_h');
								//put everything together
								$imgURL[] = $parts[0].'-'.$width.'x'.$height.$img_file_type;
							
							// no? then display the default proportionally resized thumbnails
							} else {
								$des_src 	= $OPTION['upload_path'].'/cache';							
								$img_file 	= mkthumb($img_src,$des_src,$img_size,'width');    
								$imgURL[] 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;	
							}
							
						}
					}	
				
					// put output together
					if($imgNum != 0){ 

						$permalink 		= get_permalink();
						$title_attr2	= the_title_attribute('echo=0');
						$title_attr		= str_replace("%s",the_title_attribute('echo=0'), __('Permalink to %s', 'wpShop'));
						$the_a_class 	= alternating_css_class($counter,$num_img_in_row,'first');
						
						echo "<li class='recent_prod_wrap $the_a_class' style='width:{$img_size}px'>
							<a href='$permalink' rel='bookmark' title='$title_attr'><img src='$imgURL[0]' alt='$title_attr2'/></a>"; 
							
							if($prod_title || $prod_price || $prod_btn){ ?>
								<?php
								$price = get_custom_field('price', FALSE);
								$new_price = get_custom_field('new_price', FALSE);
								?>
								<div class="teaser">
									<?php if($prod_title) { ?>
										<h5 class="prod-title"><a href="<?php the_permalink(); ?>" title="<?php printf( __('Permalink to %s', 'wpShop'), the_title_attribute('echo=0') ); ?>" rel="bookmark"><?php the_title(); ?></a></h5>
									<?php }	
									if($prod_price && $price > 0) { ?>
										<p class="price_value">
											<?php if($new_price > 0) { ?>
												<span class="was price">
													<?php echo format_price($price * $_SESSION['currency-rate'], true); ?>
												</span>
												<span class="is price">
													<?php echo format_price($new_price * $_SESSION['currency-rate'], true); ?>
												</span>
											<?php } else { ?>
													<span class="price solo"><?php echo format_price($price * $_SESSION['currency-rate'], true); ?></span>
											<?php }	?>
										</p><!-- price_value -->
									<?php }	
									if($OPTION['wps_shoppingCartEngine_yes'] && $prod_btn && strlen(get_custom_field2($postid, 'disable_cart', FALSE))==0){ 
										// shop mode
										$wps_shop_mode 	= $OPTION['wps_shop_mode'];
										
										if($wps_shop_mode =='Inquiry email mode'){ ?>
											<span class="shopform_btn add_to_enquire_alt"><a href="<?php the_permalink(); ?>"><?php printf(__ ('Add to %s!','wpShop'), $OPTION['wps_pgNavi_inquireOption'])?></a></span>
										<?php } elseif ($wps_shop_mode=='Normal shop mode' && !is_it_affiliate()){ ?>
											<span class="shopform_btn add_to_cart_alt"><a  href="<?php the_permalink(); ?>"><?php printf(__ ('Add to %s!','wpShop'), $OPTION['wps_pgNavi_cartOption'])?></a></span>
										<?php } elseif ($wps_shop_mode=='affiliate_mode' || is_it_affiliate()){ ?>
											<span class="shopform_btn buy_now_alt"><a href="<?php get_custom_field('buy_now', TRUE); ?>" <?php if($OPTION['wps_affili_newTab']) { ?> title="<?php _e('Opens is new tab','wpShop'); ?>" target="_blank"<?php } ?>><?php _e('Buy Now','wpShop'); ?></a></span>
										<?php } else {}
									
									} ?>
								</div>
							<?php }
						echo "</li>";									
						$a++;
						$counter++;
						
					// no attachments? pull image from custom field
					} elseif(strlen(get_custom_field2($postid,'image_thumb', FALSE))>0) { 
						$permalink 		= get_permalink();
						$title_attr2	= the_title_attribute('echo=0');
						$title_attr		= str_replace("%s",the_title_attribute('echo=0'), __('Permalink to %s', 'wpShop'));
						
						// resize the image.
						$img_src 		= get_custom_field2($postid,'image_thumb', FALSE);
						$des_src 		= $OPTION['upload_path'].'/cache';								
						$img_file 		= mkthumb($img_src,$des_src,$img_size,'width');    
						$imgURL 		= get_option('siteurl').'/'.$des_src.'/'.$img_file;	
						
						// do we want the WordPress Generated thumbs?
						if ($wp_thumb) {
							//get the file type
							$img_file_type = strrchr($img_src  , '.');
							//get the image name without the file type
							$parts = explode($img_file_type,$img_src);
							// get the thumbnail dimmensions
							$width = get_option('thumbnail_size_w');
							$height = get_option('thumbnail_size_h');
							//put everything together
							$imgURL = $parts[0].'-'.$width.'x'.$height.$img_file_type;
						}
						
						$the_a_class 	= alternating_css_class($counter,$num_img_in_row,'first');
						
						echo "<li class='recent_prod_wrap $the_a_class' style='width:{$img_size}px'>
							<a href='$permalink' rel='bookmark' title='$title_attr'><img src='$imgURL' alt='$title_attr2'/></a>"; 
							
							if($prod_title || $prod_price || $prod_btn){ ?>
								<?php
								$price = get_custom_field('price', FALSE);
								$new_price = get_custom_field('new_price', FALSE);
								?>
								<div class="teaser">
									<?php if($prod_title){ ?>
										<h5 class="prod-title"><a href="<?php the_permalink(); ?>" title="<?php printf( __('Permalink to %s', 'wpShop'), the_title_attribute('echo=0') ); ?>" rel="bookmark"><?php the_title(); ?></a></h5>
									<?php }	
									if($prod_price && $price > 0) { ?>
										<p class="price_value">
											<?php if(strlen(get_custom_field2($postid,'new_price', FALSE))>0){ ?>
													<span class="was price">
														<?php echo format_price($price, true); ?>
													</span>
													<span class="is price">
														<?php echo format_price($new_price, true); ?>
													</span>
											<?php } else { ?>
													<span class="price solo"><?php echo format_price($price, true); ?></span>
											<?php }	?>
										</p><!-- price_value -->
									<?php }	
									if($OPTION['wps_shoppingCartEngine_yes'] && $prod_btn && strlen(get_custom_field2($postid, 'disable_cart', FALSE))==0){ 
										// shop mode
										$wps_shop_mode 	= $OPTION['wps_shop_mode'];
										
										if($wps_shop_mode =='Inquiry email mode'){ ?>
											<span class="shopform_btn add_to_enquire_alt"><a href="<?php the_permalink(); ?>"><?php printf(__ ('Add to %s!','wpShop'), $OPTION['wps_pgNavi_inquireOption'])?></a></span>
										<?php } elseif ($wps_shop_mode=='Normal shop mode' && !is_it_affiliate()){ ?>
											<span class="shopform_btn add_to_cart_alt"><a  href="<?php the_permalink(); ?>"><?php printf(__ ('Add to %s!','wpShop'), $OPTION['wps_pgNavi_cartOption'])?></a></span>
										<?php } elseif ($wps_shop_mode=='affiliate_mode' || is_it_affiliate()){ ?>
											<span class="shopform_btn buy_now_alt"><a href="<?php get_custom_field('buy_now', TRUE); ?>" <?php if($OPTION['wps_affili_newTab']) { ?> title="<?php _e('Opens is new tab','wpShop'); ?>" target="_blank"<?php } ?>><?php _e('Buy Now','wpShop'); ?></a></span>
										<?php } else {}
									
									} ?>
								</div>
							<?php }
						echo "</li>";
						$a++;
						$counter++;
						 
					// no images altogether? Let them know!
					} else {
						
						$err_message 	= __('Oops! No Product Images were found.','wpShop');									
						echo "<p class='error'>$err_message</p>";
					}
				
				// otherwise put together a list
				} elseif ( $display == 'List' ) {
					$permalink 		= get_permalink();
					$title_attr		= str_replace("%s",the_title_attribute('echo=0'), __('Permalink to %s', 'wpShop'));
					$title			= get_the_title();
					echo "<li><a href='{$permalink}' title='{$title_attr}'>{$title}</a></li>";
				
				// otherwise we put together a drop down
				} else {
					$permalink 		= get_permalink();
					$title			= get_the_title();
					echo "<option value='{$permalink}'>{$title}</option>";
				}
				
			} wp_reset_query();
			
			if ( $display == 'Images' ) { 
				echo "</ul'>";
			}
			
			if ( $display == 'List' ) { 
				echo "</ul>";
			}
			if ( $display == 'Drop Down' ) { 
				echo "</select></div>";
			}
			
			if ($scroll && $display == 'Images') { ?>
				</div><!--scrollable-->
				<a class="next browse right"></a>
				</div><!--prods_scrollable_wrap-->
			<?php }
			
		} else {
			$err_message 	= __('There\'s no Recent Products yet in this Category','wpShop');									
			echo "<p class='error'>$err_message</p>";
		}
		 
		# After the widget
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 				= strip_tags($new_instance['title']);
		
		$instance['cat_slug'] 			= strip_tags($new_instance['cat_slug']);
		$instance['cat_IDs'] 			= $new_instance['cat_IDs'];
		$instance['current_cat'] 		= $new_instance['current_cat'];
		$instance['current_maincat'] 	= $new_instance['current_maincat'];
		
		$instance['display'] 			= $new_instance['display'];
		$instance['showposts'] 			= $new_instance['showposts'];
		$instance['img_size'] 			= strip_tags($new_instance['img_size']);
		$instance['num_img_in_row'] 	= $new_instance['num_img_in_row'];
		
		$instance['wp_thumb'] 			= $new_instance['wp_thumb'];
		$instance['scroll'] 			= $new_instance['scroll'];
		
		$instance['prod_title'] 		= $new_instance['prod_title'];
		$instance['prod_price'] 		= $new_instance['prod_price'];
		$instance['prod_btn'] 			= $new_instance['prod_btn'];
		return $instance;
	}
	
	function form($instance){
		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('', 'wpShop'), 'cat_slug' => '', 'cat_IDs' => '', 'current_cat' => FALSE, 'current_maincat' => FALSE, 'show_images' => FALSE, 'img_size' => '184', 'showposts' => '5', 'num_img_in_row' => '5','prod_title' => FALSE, 'prod_price' => FALSE, 'prod_btn' => FALSE);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cat_slug' ); ?>"><?php _e('Category Name or Slug:', 'wpShop'); ?></label><br/>
			<small><?php _e('If you like to query recent Products from one category enter it\'s name or slug here. Otherwise leave empty!', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'cat_slug' ); ?>" name="<?php echo $this->get_field_name( 'cat_slug' ); ?>" value="<?php echo $instance['cat_slug']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cat_IDs' ); ?>"><?php _e('Category IDs:', 'wpShop'); ?></label><br/>
			<small><?php _e('If you like to query recent Products from several categories enter their IDs seperated by comma eg. 1,3,4. Otherwise leave empty!', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'cat_IDs' ); ?>" name="<?php echo $this->get_field_name( 'cat_IDs' ); ?>" value="<?php echo $instance['cat_IDs']; ?>" style="width:97%;" />
		</p>
		
		<!-- query current cat || current main cat? Checkbox -->
		<p style="background:#F1F1F1;">
			<strong><?php _e('For use on Product Category Pages Only', 'wpShop'); ?></strong><br/>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['current_cat'], true ); ?> id="<?php echo $this->get_field_id( 'current_cat' ); ?>" name="<?php echo $this->get_field_name( 'current_cat' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'current_cat' ); ?>"><?php _e('Query Current Category', 'wpShop'); ?></label><br/>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['current_maincat'], true ); ?> id="<?php echo $this->get_field_id( 'current_maincat' ); ?>" name="<?php echo $this->get_field_name( 'current_maincat' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'current_maincat' ); ?>"><?php _e('Query Current Main (top level) Category', 'wpShop'); ?></label><br/>
			<small><?php _e('Check the appropriate box (not both!) if you like to query recent Products from the current category or the current main (top level) category being viewed. This will only work if you have activated this widget on category pages! If you are using the widget on other pages please use one of the options above.', 'wpShop'); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'showposts' ); ?>"><?php _e('Number of Recent Products', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'showposts' ); ?>" name="<?php echo $this->get_field_name( 'showposts' ); ?>" value="<?php echo $instance['showposts']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'display' ); ?>"><?php _e('Display as:', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'display' ); ?>" name="<?php echo $this->get_field_name( 'display' ); ?>" class="widefat" style="width:97%;">
				<option value="List" <?php selected('List', $instance["display"]); ?>><?php _e('List', 'wpShop'); ?></option>
				<option value="Images" <?php selected('Images', $instance["display"]); ?>><?php _e('Images', 'wpShop'); ?></option>
				<option value="Drop Down" <?php selected('Drop Down', $instance["display"]); ?>><?php _e('Drop Down', 'wpShop'); ?></option>
			</select>
		</p>
		
		<small><?php _e('The fields below apply only if you have selected "Images" from above.', 'wpShop'); ?></small>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'img_size' ); ?>"><?php _e('Thumb Width:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'img_size' ); ?>" name="<?php echo $this->get_field_name( 'img_size' ); ?>" value="<?php echo $instance['img_size']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'num_img_in_row' ); ?>"><?php _e('Number of images per row:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'v' ); ?>" name="<?php echo $this->get_field_name( 'num_img_in_row' ); ?>" value="<?php echo $instance['num_img_in_row']; ?>" style="width:97%;" />
		</p>
		<p>
			<strong><?php _e('Optional', 'wpShop'); ?></strong><br/>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['wp_thumb'], true ); ?> id="<?php echo $this->get_field_id( 'wp_thumb' ); ?>" name="<?php echo $this->get_field_name( 'wp_thumb' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'wp_thumb' ); ?>"><?php _e('Use WordPress generated image thumbs?', 'wpShop'); ?></label>
		</p>
		<p>
			<strong><?php _e('Optional', 'wpShop'); ?></strong><br/>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['scroll'], true ); ?> id="<?php echo $this->get_field_id( 'scroll' ); ?>" name="<?php echo $this->get_field_name( 'scroll' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'scroll' ); ?>"><?php _e('Make scrollable', 'wpShop'); ?></label><br/>
			<small><?php _e('Will only work is you have selected "Display as: Images" further up!', 'wpShop'); ?></small>
		
		</p>
		
		<p style="background:#F1F1F1;">
			<strong><?php _e('Optional', 'wpShop'); ?></strong><br/>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['prod_title'], true ); ?> id="<?php echo $this->get_field_id( 'prod_title' ); ?>" name="<?php echo $this->get_field_name( 'prod_title' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'prod_title' ); ?>"><?php _e('Display Product Title', 'wpShop'); ?></label><br/>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['prod_price'], true ); ?> id="<?php echo $this->get_field_id( 'prod_price' ); ?>" name="<?php echo $this->get_field_name( 'prod_price' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'prod_price' ); ?>"><?php _e('Display Product Price', 'wpShop'); ?></label><br/>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['prod_btn'], true ); ?> id="<?php echo $this->get_field_id( 'prod_btn' ); ?>" name="<?php echo $this->get_field_name( 'prod_btn' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'prod_btn' ); ?>"><?php _e('Display "Add to Cart" button', 'wpShop'); ?></label><br/>
		</p>
	<?php }
}

class RecentBlogPosts extends WP_Widget {

	function RecentBlogPosts() {
		$widget_ops 	= array('classname' => 'latest_from_blog_widget', 'description' => __( 'For Recent Blog Posts', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'nws-recent-blog-posts');
		$this->WP_Widget('nws-recent-blog-posts', __('NWS Recent Blog Posts', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
		global $OPTION;
		
		extract($args);
		$title 		= apply_filters('widget_title', $instance['title'] );
		$showposts 	= $instance['showposts'];
		$wordLimit 	= $instance['wordLimit'];
		$post_tw 	= $instance['post_tw'];
		$post_th 	= $instance['post_th'];
		$order		= $instance['order'];
		
		# Before the widget
		echo $before_widget;

		# The title
		if ( $title )
		echo $before_title . $title . $after_title;

		# Output
		// prepare the query
		//get blog ID
		$blog_ID 	= get_cat_ID($OPTION['wps_blogCat']);
		$param		= array(
			'cat' 				=> $blog_ID,
			'showposts'			=> $showposts,
			'order'				=> $order,			
			'caller_get_posts'	=> 1
		);
		?>
		
		<div class="widget_content_wrap">
			<?php	
			// query	
			$my_recBlogPosts_query = new wp_query($param);
		
			// do we have posts?	
			if($my_recBlogPosts_query->have_posts()) 
			{ 
				// run the loop 			
				while ($my_recBlogPosts_query->have_posts()) 
				{ 	
					$my_recBlogPosts_query->the_post();
				?>
					<div <?php post_class('blog_post clearfix'); ?> id="post-<?php the_ID(); ?>">
						<h5><a href="<?php the_permalink(); ?>" title="<?php printf( __('Permalink to %s', 'wpShop'), the_title_attribute('echo=0') ); ?>" rel="bookmark"><?php the_title(); ?></a></h5>
						<?php 
						//do we have a post thumbnail uploaded?
						if((function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) { 
							$altText 	= the_title_attribute('echo=0');
						?>
							<a class="thumb_img alignright" href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( __('Permalink to %s', 'wpShop'), the_title_attribute('echo=0') ); ?>">
								<?php the_post_thumbnail(array($post_tw, $post_th), array('class' => '', 'alt' => $altText)); ?>
							</a> 
						
						<?php	
						//no? then do we have an image attached?
						} else {
							$output = my_attachment_image(0, 'thumbnail', 'alt="' . $post->post_title . '"','return');
							if (strlen($output['img_path'])>0) { ?>
																				
								<a class="thumb_img alignright" href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( __('Permalink to %s', 'wpShop'), the_title_attribute('echo=0') ); ?>">
									<?php my_attachment_image(0, 'thumbnail', 'alt="' . $post->post_title . '"'); ?>
								</a> 
							<?php } 
						} ?>
						
						<div class="teaser">
							<!--<p><?php //DEPRECATED!! the_content_rss('', TRUE, '', $wordLimit);?></p>-->
							<p><?php echo NWS_excerpt($wordLimit); ?></p>
								
							<p class="read_more">
								<a href="<?php the_permalink(); ?>"><?php _e('read more','wpShop'); ?></a>
							</p>
						</div><!-- teaser -->
					</div><!-- post -->
			
				<?php
				} wp_reset_query();
			} else {
				$err_message 	= __('There\'s no Recent Posts yet in the Blog','wpShop');									
				echo "<p class='error'>$err_message</p>";
			} ?>
		</div><!--widget_content_wrap-->
		<?php
		# After the widget
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 		= strip_tags($new_instance['title']);
		$instance['showposts'] 	= $new_instance['showposts'];
		$instance['wordLimit'] 	= $new_instance['wordLimit'];
		$instance['post_tw'] 	= $new_instance['post_tw'];
		$instance['post_th'] 	= $new_instance['post_th'];
		$instance['order'] 		= $new_instance['order'];
		return $instance;
	}
	
	function form($instance){
		
		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('', 'wpShop'));
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'showposts' ); ?>"><?php _e('Number of Recent Products', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'showposts' ); ?>" name="<?php echo $this->get_field_name( 'showposts' ); ?>" value="<?php echo $instance['showposts']; ?>" style="width:97%;" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'wordLimit' ); ?>"><?php _e('Teaser Word limit', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'wordLimit' ); ?>" name="<?php echo $this->get_field_name( 'wordLimit' ); ?>" value="<?php echo $instance['wordLimit']; ?>" style="width:97%;" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'post_tw' ); ?>"><?php _e('Post Thumbnail Width', 'wpShop'); ?></label>
			<small><?php _e('If your posts use the Post Thumbnail feature, control the width here', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'post_tw' ); ?>" name="<?php echo $this->get_field_name( 'post_tw' ); ?>" value="<?php echo $instance['post_tw']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'post_th' ); ?>"><?php _e('Post Thumbnail Height', 'wpShop'); ?></label>
			<small><?php _e('If your posts use the Post Thumbnail feature, control the height here', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'post_th' ); ?>" name="<?php echo $this->get_field_name( 'post_th' ); ?>" value="<?php echo $instance['post_th']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e('Order:', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>" class="widefat" style="width:97%;">
				<option value="ASC" <?php selected('ASC', $instance["order"]); ?>><?php _e('ASC', 'wpShop'); ?></option>
				<option value="DESC" <?php selected('DESC', $instance["order"]); ?>><?php _e('DESC', 'wpShop'); ?></option>
			</select>
		</p>
		
	<?php }
}

//TrackOrder
class TrackOrder extends WP_Widget {

	function TrackOrder() {
		$widget_ops 	= array('classname' => 'widget_trackOrder', 'description' => __( 'Allow your Customers to Track their Order', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'nws-track-order');
		$this->WP_Widget('nws-track-order', __('NWS Track Order', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
		global $OPTION;
		
		extract($args);
		$title 		= apply_filters('widget_title', $instance['title'] );
		
		
		# Before the widget
		echo $before_widget;

		# The title
		if ( $title )
		echo $before_title . $title . $after_title;

		# Output
		?>
		
		<form method="post" id="trackingform" class="clearfix" action="<?php echo get_option('home'); ?>/index.php?checkOrderStatus=1">
			<label for="tid"><?php _e('Enter your Tracking ID: ','wpShop'); ?><img alt="" src="<?php bloginfo('stylesheet_directory'); ?>/images/questionmark.png" title="<?php _e('You can find the Tracking ID of your last Online Order in your Order Confirmation Email.','wpShop'); ?>" /></label><br/>
			<input type="text" value="" name="tid" id="t" class="text" />
			<input type="submit" id="tracksubmit" value="<?php _e('Find my Order','wpShop'); ?>" class="formbutton" />
		</form>
		
		<?php
		# After the widget
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 		= strip_tags($new_instance['title']);
		return $instance;
	}
	
	function form($instance){
		
		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Track your Order', 'wpShop'));
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		
	<?php }
}

// Blog Categories
class BlogCategoriesWidget extends WP_Widget {

	function BlogCategoriesWidget() {
		$widget_ops 	= array('classname' => 'widget_categories', 'description' => __( 'Display your Blog Categories- for use on Pages.', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'nws-blog-categories');
		$this->WP_Widget('nws-blog-categories', __('NWS Blog Categories', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
		global $OPTION;
		
		extract($args);
		$title 		= apply_filters('widget_title', $instance['title'] );
		$orderby 	= $instance['orderby'];
		$order 		= $instance['order'];
		$include 	= $instance['include'];
		$exclude 	= $instance['exclude'];
		
		# Before the widget
		echo $before_widget;

		# The title
		if ( $title )
		echo $before_title . $title . $after_title;

		# Output
		$blog_ID 		= get_cat_ID($OPTION['wps_blogCat']);
		if (is_single()) {
			$categ_object 	= get_the_category();
			$myCat			= $categ_object[0]->term_id;
			$catMenuArg 	= array(
				'exclude'   		=> $exclude,
				'include'   		=> $include,
				'title_li'			=> '', 
				'orderby'   		=> $orderby,
				'order'     		=> $order,
				'child_of'			=> $blog_ID,
				'current_category' 	=> $myCat,
				'echo'				=> 0,
			);
		} else {
			$catMenuArg 	= array(
				'exclude'   		=> $exclude,
				'include'   		=> $include,
				'title_li'			=> '', 
				'orderby'   		=> $orderby,
				'order'     		=> $order,
				'child_of'			=> $blog_ID,
				'echo'				=> 0,
			);
		}
		
		
		$subcategories 	= wp_list_categories($catMenuArg);
		
		if ($subcategories != '<li>'.__('No categories','wpShop').'</li>') { ?>
			<ul>
				<?php echo $subcategories; ?>
			</ul>
		<?php } 
		
		# After the widget
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 		= strip_tags($new_instance['title']);
		$instance['include'] 	= $new_instance['include'];
		$instance['exclude'] 	= $new_instance['exclude'];
		$instance['orderby'] 	= $new_instance['orderby'];
		$instance['order'] 		= $new_instance['order'];
		
		return $instance;
	}
	
	function form($instance){
		//Set up some default widget settings. 
		$defaults = array( 'title' => '', 'include' => '' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'include' ); ?>"><?php _e('Include:', 'wpShop'); ?></label><br/>
			<small><?php _e('Enter the Category IDs you want to include. Comma separate multiple categories. You can only use either include or exclude, not both!', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'include' ); ?>" name="<?php echo $this->get_field_name( 'include' ); ?>" value="<?php echo $instance['include']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'exclude' ); ?>"><?php _e('Exclude:', 'wpShop'); ?></label><br/>
			<small><?php _e('Enter the Category IDs you want to include. Comma separate multiple categories. You can only use either include or exclude, not both!', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'exclude' ); ?>" name="<?php echo $this->get_field_name( 'exclude' ); ?>" value="<?php echo $instance['exclude']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e('Order:', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>" class="widefat" style="width:97%;">
				<option value="ASC" <?php selected('ASC', $instance["order"]); ?>><?php _e('ASC', 'wpShop'); ?></option>
				<option value="DESC" <?php selected('DESC', $instance["order"]); ?>><?php _e('DESC', 'wpShop'); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e('Order by:', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>" class="widefat" style="width:97%;">
				<option value="name" <?php selected('name', $instance["orderby"]); ?>><?php _e('name', 'wpShop'); ?></option>
				<option value="ID" <?php selected('ID', $instance["orderby"]); ?>><?php _e('ID', 'wpShop'); ?></option>
				<option value="slug" <?php selected('slug', $instance["orderby"]); ?>><?php _e('slug', 'wpShop'); ?></option>
				<option value="count" <?php selected('count', $instance["orderby"]); ?>><?php _e('count', 'wpShop'); ?></option>
			</select>
		</p>
		
	<?php }
}

// the ShopByCat Widget
class ShopByCat extends WP_Widget {

	function ShopByCat() {
		$widget_ops 	= array('classname' => 'shop_by_widget widget_shopbyTax widget_shopbyCat', 'description' => __( 'Display Store Categories as image thumbnails', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'nws-shopby-categories');
		$this->WP_Widget('nws-shopby-categories', __('NWS ShopBy Categories', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
	
		global $OPTION;
		
		extract($args);
		$title 			= apply_filters('widget_title', $instance['title'] );
		$imgPath 		= $instance['imgPath'];
		$img_file_type 	= $instance['img_file_type'];
		$thumb_width 	= $instance['thumb_width'];
		$num_img_in_row = $instance['num_img_in_row'];
		$order 			= $instance['order'];
		$catTitle 		= isset( $instance['catTitle'] ) ? $instance['catTitle'] : FALSE;
		
		# Before the widget
		echo $before_widget;

		# The title
		if ( $title )
		echo $before_title . $title . $after_title;

		# Output
		?>
	
		<div class="clearfix widget_content_wrap">
			<?php
			// are we using a Blog?
			$blog_Name 	= $OPTION['wps_blogCat'];
			if ($blog_Name != 'Select a Category') {
				$blog_ID 	= get_cat_ID( $blog_Name );
				//collect the main categories & exclude the Blog
				$mainCategories = get_terms('category', 'orderby='.$orderBy.'&order='.$order.'&parent=0&hide_empty=0&exclude='.$blog_ID);
			} else {
			//collect the main categories
			$mainCategories = get_terms('category', 'orderby='.$orderBy.'&order='.$order.'&parent=0&hide_empty=0');
			}
			
			$counter = $num_img_in_row;
			$a = 1;
			foreach ($mainCategories as $mainCategory) {
				//get the taxonomy object
				$tax_obj = get_taxonomy($taxonomy);
				
				$img_src 		= $imgPath.$mainCategory->slug.'.'.$img_file_type;
				$des_src 		= $OPTION['upload_path'].'/cache';	
				$img_file 		= mkthumb($img_src,$des_src,$thumb_width,'width');    
				$imgURL 		= get_option('siteurl').'/'.$des_src.'/'.$img_file;
				$the_a_class 	= alternating_css_class($counter,$num_img_in_row,'first');
				$the_div_class 	= 'c_box c_box'.$num_img_in_row.' '. $the_a_class;
				
				//echo '<a class="'.$the_a_class.'" href="'.get_category_link($mainCategory->term_id).'"><img src="'.$imgURL .'" alt="'.$mainCategory->name.'" /></a>';
				?>
					<div class="<?php echo $the_div_class; ?>">
						<div class="contentWrap">
							<?php if ($img_file != 'error') {?>
								<a class="<?php echo $the_a_class;?>" href="<?php echo get_category_link($mainCategory->term_id);?>">
									<img src="<?php echo $imgURL; ?>" alt="<?php echo $mainCategory->name; ?>" />
								</a>
							<?php  } else { ?>
								<p class="error">
									<?php _e('Oops! No Category Specific Image was found. Please create one, save it after the category slug and upload it inside your "uploads" folder. Make sure also that the folder\'s permissions are set to 777!','wpShop'); ?><br/>
								</p>
							<?php } ?>	
						</div><!-- contentWrap  -->
						
						<?php  if($catTitle) { ?>
							<h5 class="single_cat_title"><?php echo $mainCategory->name; ?></h5> 
						<?php  } ?>
						
					</div><!-- c_box  -->
				<?php
				$a++;
				$counter++;
			}
			
			?>
		</div><!-- widget_content_wrap -->
			
		
		<?php
		# After the widget
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance){
		$instance 					= $old_instance;
		$instance['title'] 			= strip_tags($new_instance['title']);
		$instance['imgPath'] 		= $new_instance['imgPath'];
		$instance['img_file_type'] 	= $new_instance['img_file_type'];
		$instance['thumb_width'] 	= strip_tags($new_instance['thumb_width']);
		$instance['num_img_in_row'] = $new_instance['num_img_in_row'];
		$instance['order'] 			= $new_instance['order'];
		$instance['catTitle'] 		= $new_instance['catTitle'];
		return $instance;
	}
	
	function form($instance){
		// Set up some default widget settings.
		$defaults = array( 'title' => __('Shop by', 'wpShop'), 'imgPath' => 'http://www.your-site/wp-content/uploads/', 'thumb_width' => '112', 'num_img_in_row' => '3', 'order' => 'ASC','catTitle' => FALSE,);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'imgPath' ); ?>"><?php _e('Image File Path:', 'wpShop'); ?></label><br/>
			<small><?php _e('The folder path to wherever you saved your image. Remember to end with a /', 'wpShop'); ?></small>
			<input id="<?php echo $this->get_field_id( 'imgPath' ); ?>" name="<?php echo $this->get_field_name( 'imgPath' ); ?>" value="<?php echo $instance['imgPath']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'img_file_type' ); ?>"><?php _e('Image File Type', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'img_file_type' ); ?>" name="<?php echo $this->get_field_name( 'img_file_type' ); ?>" class="widefat" style="width:97%;">
				<option value="jpg" <?php selected('jpg', $instance["img_file_type"]); ?>><?php _e('jpg', 'wpShop'); ?></option>
				<option value="png" <?php selected('png', $instance["img_file_type"]); ?>><?php _e('png', 'wpShop'); ?></option>
				<option value="gif" <?php selected('gif', $instance["img_file_type"]); ?>><?php _e('gif', 'wpShop'); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'thumb_width' ); ?>"><?php _e('Resize Thumb to this Width:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'thumb_width' ); ?>" name="<?php echo $this->get_field_name( 'thumb_width' ); ?>" value="<?php echo $instance['thumb_width']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'num_img_in_row' ); ?>"><?php _e('Number of images per row:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'num_img_in_row' ); ?>" name="<?php echo $this->get_field_name( 'num_img_in_row' ); ?>" value="<?php echo $instance['num_img_in_row']; ?>" style="width:97%;" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e('Order:', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>" class="widefat" style="width:97%;">
				<option value="ASC" <?php selected('ASC', $instance["order"]); ?>><?php _e('ASC', 'wpShop'); ?></option>
				<option value="DESC" <?php selected('DESC', $instance["order"]); ?>><?php _e('DESC', 'wpShop'); ?></option>
				<option value="Random" <?php selected('Random', $instance["order"]); ?>><?php _e('Random', 'wpShop'); ?></option>
			</select>
		</p>
		
		<p style="background:#F1F1F1;">
			<strong><?php _e('Optional', 'wpShop'); ?></strong><br/>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['catTitle'], true ); ?> id="<?php echo $this->get_field_id( 'catTitle' ); ?>" name="<?php echo $this->get_field_name( 'catTitle' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'catTitle' ); ?>"><?php _e('Display Category Title', 'wpShop'); ?></label>
		</p>
	<?php }
}


//shop recently added widget
class ShopRecentlyAddedWidget extends WP_Widget {

	function ShopRecentlyAddedWidget() {
		$widget_ops 	= array('classname' => 'widget_recently_added', 'description' => __( 'Shop Recently Added', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'nws-shop-recently-added');
		$this->WP_Widget('nws-shop-recently-added', __('NWS Shop Recently Added', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
		global $OPTION, $recently_where;
		extract($args);
		$title 		= apply_filters('widget_title', $instance['title'] );
		$cats = $instance['cats'];
		$brands = $instance['brands'];
		$number = $instance['number'];
		if (!$number) { $number = 5; }

		$currency_code = $_SESSION['currency-code'];
		$currency_rate = $_SESSION['currency-rate'];
		if (!$currency_rate) { $currency_rate = 1; }

		$args = array('post_type' => 'post', 'showposts' => $number);
		if (strlen($cats)) {
			$args['category__in'] = explode(",", str_replace(' ', '', $cats));
		}
		if (strlen($brands)) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'brand',
					'terms'    => explode(",", str_replace(' ', '', $brands)),
				),
			);
		}
		$recently_where = true;
		add_filter('posts_where', 'recently_added_where');
		$recently_added = new wp_query($args); 		
		if($recently_added->have_posts()) {
			# Before the widget
			echo $before_widget;
			echo '<div class="recently-added-widget">';
			# The title
			if ($title) { echo $before_title . $title . $after_title; }
		?>
			<ul class="recently-added-list">
				<?php while ($recently_added->have_posts()) { $recently_added->the_post();
					$pimage = get_product_thumb(get_the_ID(), 174);
				?>
				<li class="c_box4">
					<div class="contentWrap">
						<div class="holder">
							<a href="<?php the_permalink(); ?>" class="image" title="<?php the_title(); ?>">
								<?php if($pimage) { ?><img src="<?php echo $pimage; ?>" alt="" /><?php } ?>
							</a>
							<div class="teaser">
								<div class="prod-title-box">
									<h5 class="prod-title"><a href="<?php the_permalink(); ?>" title="<?php printf( __('Permalink to %s', 'wpShop'), the_title_attribute('echo=0') ); ?>" rel="bookmark"><?php echo get_limit_content(get_the_title(), 48, true); ?></a></h5>
								</div>
								<p class="price_value">
									<?php // PRODUCT PRICE
									$price = get_custom_field('price', FALSE);
									$new_price = get_custom_field('new_price', FALSE);
									if ($new_price && $price) { ?>
										<span class="was price">Was: <?php product_prices_list($price); ?></span>
									<?php } ?>
								</p>
							</div>
						</div>
						<?php if ($new_price) { ?>
							<div class="price-box">
								<?php if ($price > $new_price) { $perc = round(($price - $new_price) / ($price / 100)); ?>
									<span class="discounts"><?php echo $perc; ?>% off</span>
								<?php } ?>
								<h3>Now: <strong><?php product_prices_list($new_price); ?></strong></h3>
							</div>
						<?php } else { ?>
							<div class="price-box">
								<h3><strong><?php product_prices_list($price); ?></strong></h3>
							</div>
						<?php } ?>
					</div>
				</li>
				<?php } ?>
			</ul>
		<?php
			echo '</div>';
			# After the widget
			echo $after_widget;
		}
		wp_reset_query();
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 	= strip_tags($new_instance['title']);
		$instance['cats'] = $new_instance['cats'];
		$instance['brands'] = $new_instance['brands'];
		$instance['number'] = $new_instance['number'];
		return $instance;
	}
	
	function form($instance){
		//Set up some default widget settings. 
		$defaults = array( 'title' => 'Recently Added', 'cats' => '', 'number' => '5' );
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cats' ); ?>"><?php _e('From Categories:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'cats' ); ?>" name="<?php echo $this->get_field_name( 'cats' ); ?>" value="<?php echo $instance['cats']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'brands' ); ?>"><?php _e('From Brands:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'brands' ); ?>" name="<?php echo $this->get_field_name( 'brands' ); ?>" value="<?php echo $instance['brands']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e('Number:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" style="width:17%;" />
		</p>
	<?php
	}
}

//shop recently added widget
class ShopFeaturedProductsWidget extends WP_Widget {

	function ShopFeaturedProductsWidget() {
		$widget_ops 	= array('classname' => 'widget_featured_products', 'description' => __( 'Shop Featured Products', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'nws-shop-featured-products');
		$this->WP_Widget('nws-shop-featured-products', __('NWS Shop Featured Products', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
		global $OPTION;
		extract($args);
		$title 		= apply_filters('widget_title', $instance['title'] );
		$prods = $instance['prods'];
		$number = $instance['number'];
		if (!$number) { $number = 5; }

		$currency_code = $_SESSION['currency-code'];
		$currency_rate = $_SESSION['currency-rate'];
		if (!$currency_rate) { $currency_rate = 1; }

		$args = array('post_type' => 'post', 'showposts' => $number);
		if (strlen($prods)) {
			$args['post__in'] = explode(",", str_replace(' ', '', $prods));
		}
		$recently_added = new wp_query($args);
		if($recently_added->have_posts()) {
			# Before the widget
			echo $before_widget;
			# The title
			if ($title) { echo '<div class="title-line">'.$before_title . $title . $after_title.'</div>'; }
		?>
		<div class="b-carousel carousel-products" id="carousel-recommended">
			<div class="jcarousel">
				<ul class="recently-added-list slides">
					<?php while ($recently_added->have_posts()) { $recently_added->the_post();
						$pimage = get_product_thumb(get_the_ID(), 150);
						$price = get_custom_field('price', FALSE);
						$new_price = get_custom_field('new_price', FALSE);
						?>
						<li class="b-product-item">
							<a href="<?php the_permalink(); ?>" class="image">
								<?php if($pimage) { ?><img src="<?php echo $pimage; ?>" alt="" /><?php } ?>
							</a>
							<h5 class="title"><a href="<?php the_permalink(); ?>"><?php echo get_limit_content(get_the_title(), 48, true); ?></a></h5>
							<div class="price-row price-row_was">
								<?php if ($new_price && $price) { ?>
									Was: <span class="was price"><?php product_prices_list($price); ?></span>
								<?php } ?>
							</div>
							<div class="price-row cf">
								<?php if ($new_price) { ?>
									Now: <?php product_prices_list($new_price); ?>
									<?php if ($price > $new_price) { $perc = round(($price - $new_price) / ($price / 100)); ?>
										<div class="right"><?php echo $perc; ?>% OFF</div>
									<?php } ?>
								<?php } else { ?>
									<?php product_prices_list($price); ?>
								<?php } ?>
							</div>
						</li>
					<?php } ?>
				</ul>
			</div>
			<a href="#" class="flex-prev">&lsaquo;</a>
			<a href="#" class="flex-next">&rsaquo;</a>
		</div>
		<?php
			# After the widget
			echo $after_widget;
		}
		wp_reset_query();
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 	= strip_tags($new_instance['title']);
		$instance['prods'] = $new_instance['prods'];
		$instance['number'] = $new_instance['number'];
		return $instance;
	}
	
	function form($instance){
		//Set up some default widget settings. 
		$defaults = array( 'title' => 'Featured Products', 'prods' => '', 'number' => '5' );
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'prods' ); ?>"><?php _e('Products (IDs):', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'prods' ); ?>" name="<?php echo $this->get_field_name( 'prods' ); ?>" value="<?php echo $instance['prods']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e('Number:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" style="width:7%;" />
		</p>
	<?php
	}
}

//images gallery scroller widget
class ImagesGalleryScrollerWidget extends WP_Widget {

	function ImagesGalleryScrollerWidget() {
		$widget_ops 	= array('classname' => 'widget_gallery_scroller', 'description' => __( 'Images Gallery Scroller', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'images-gallery-scroller');
		$this->WP_Widget('images-gallery-scroller', __('Images Gallery Scroller', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
		global $OPTION;
		extract($args);
		$title 		= apply_filters('widget_title', $instance['title'] );
		$width = $instance['width'];
		$height = $instance['height'];
		$gpid = $instance['gpid'];
		if (!$width) { $width = 400; }
		if (!$height) { $height = 200; }

		# Before the widget
		echo $before_widget;
		# The title
		if ($title) { echo $before_title . $title . $after_title; }
		echo do_shortcode('[scroller_gallery w="'.$width.'" h="'.$height.'" pid="'.$gpid.'"]');
		# After the widget
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 	= strip_tags($new_instance['title']);
		$instance['width'] = $new_instance['width'];
		$instance['height'] = $new_instance['height'];
		$instance['gpid'] = $new_instance['gpid'];
		return $instance;
	}
	
	function form($instance){
		//Set up some default widget settings. 
		$defaults = array( 'title' => '', 'width' => '400', 'height' => '200' );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$wppages = get_pages();
		?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e('Scroller Width:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php echo $instance['width']; ?>" style="width:10%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e('Scroller Height:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" value="<?php echo $instance['height']; ?>" style="width:10%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'gpid' ); ?>"><?php _e('Gallery of page:', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'gpid' ); ?>" name="<?php echo $this->get_field_name( 'gpid' ); ?>">
				<?php foreach($wppages as $wppage) { $s = ''; if ($wppage->ID == $instance['gpid']) { $s = ' SELECTED'; } ?>
					<option value="<?php echo $wppage->ID; ?>"<?php echo $s; ?>><?php if ($wppage->post_parent) { echo '&nbsp;&nbsp;&nbsp;&nbsp;'; } ?><?php echo $wppage->post_title; ?></option>
				<?php } ?>
			</select>
		</p>
	<?php
	}
}

//frontpage press slider
class InThePressSliderWidget extends WP_Widget {

	function InThePressSliderWidget() {
		$widget_ops 	= array('classname' => 'widget_in_the_press_slider', 'description' => __( 'In The Press Slider', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'in-the-press-slider');
		$this->WP_Widget('in-the-press-slider', __('In The Press Slider', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
		global $OPTION;
		extract($args);
		$title 		= apply_filters('widget_title', $instance['title'] );
		$ppid = $instance['ppid'];
		$moretext = $instance['moretext'];

		if ($ppid) {
			$pp_subpages = get_pages('child_of='.$ppid.'&meta_key=press_show_in_slider&meta_value=yes');
			if ($pp_subpages) {
				# Before the widget
				echo $before_widget;
				# The title
				if ($title) { echo $before_title . $title . $after_title; } ?>
				<script type="text/javascript" src="<?php bloginfo('template_url')?>/js/slider.js"></script>
				<div class="revolver-holder">
					<div id="revolver" class="revolver stack">
						<?php foreach($pp_subpages as $pp_subpage) { $pp_subpage_thumbnail_id = get_post_thumbnail_id($pp_subpage->ID); ?>
						<div class="slide cover<?php echo $hidden; ?>">
							<?php if ($pp_subpage_thumbnail_id) { ?><a href="<?php echo get_permalink($ppid); ?>"><img src="<?php echo get_post_thumb($pp_subpage_thumbnail_id, 85, 98, true); ?>" /></a><?php } ?>
							<div class="text">
								<h2><a href="<?php echo get_permalink($ppid); ?>">"<?php echo $pp_subpage->post_title; ?>"</a></h2>
								<h3><?php echo get_post_meta($pp_subpage->ID, 'press_subtitle', true); ?></h3>
								<a href="<?php echo get_permalink($ppid); ?>" class="link"><?php echo $moretext; ?></a>
							</div>
						</div>
						<?php $hidden = ' hidden'; } ?>
					</div>
					<a href="/authenticity/" class="right-link">100% authenticity guarantee</a>
				</div>
				<script type="text/javascript">
					jQuery(function(){
						var $revolver = jQuery('#revolver').revolver(),
							revolver  = $revolver.data('revolver'),
							$controls = jQuery('.controls');
						$controls.find('.goto').click(function(e){
							e.preventDefault();
							revolver.goTo(jQuery(this).data('goto'));
						});
					});
				</script>
				<?php # After the widget
				echo $after_widget;
			}
		}
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 	= strip_tags($new_instance['title']);
		$instance['ppid'] = $new_instance['ppid'];
		$instance['moretext'] = $new_instance['moretext'];
		return $instance;
	}
	
	function form($instance){
		//Set up some default widget settings. 
		$defaults = array( 'title' => '', 'moretext' => 'See what all the fuss is about');
		$instance = wp_parse_args( (array) $instance, $defaults );
		$wppages = get_pages();
		?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'ppid' ); ?>"><?php _e('In The Press page:', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'ppid' ); ?>" name="<?php echo $this->get_field_name( 'ppid' ); ?>">
				<?php foreach($wppages as $wppage) { $s = ''; if ($wppage->ID == $instance['ppid']) { $s = ' SELECTED'; } ?>
					<option value="<?php echo $wppage->ID; ?>"<?php echo $s; ?>><?php if ($wppage->post_parent) { echo '&nbsp;&nbsp;&nbsp;&nbsp;'; } ?><?php echo $wppage->post_title; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'moretext' ); ?>"><?php _e('More text:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'moretext' ); ?>" name="<?php echo $this->get_field_name( 'moretext' ); ?>" value="<?php echo $instance['moretext']; ?>" style="width:97%;" />
		</p>
	<?php
	}
}

//shop search filter widget
class ShopSearchFilterWidget extends WP_Widget {
	var $custom_taxs = array();
	function ShopSearchFilterWidget() {
		$this->custom_taxs = array('brand', 'colour', 'price', 'selection', 'size', 'ring-size', 'clothes-size');
		$widget_ops 	= array('classname' => 'widget_search_filter', 'description' => __( 'Search Filter', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'nws-shop-search-filter');
		$this->WP_Widget('nws-shop-search-filter', __('NWS Shop Search Filter', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
		extract($args);
		$title 		= apply_filters('widget_title', $instance['title'] );
		$cat_selected = '';
		global $wp_query;

		
		# Before the widget
		echo '<div class="widget widget-filter">';
		# The title
		if ($title) { echo '<h3>' . $title . '</h3>'; }
		?>
		<form class="search-filter-form" action="<?php echo get_permalink($instance['search_page']); ?>">
			<?php

			$queried_object = get_queried_object();					
			if(is_null($queried_object) OR is_page()) $queried_object = get_term_by('id', 86, 'brand');

			$parents = getParents($queried_object->term_id, $queried_object->taxonomy);
			$father = get_category('id', 0, 'category');
			if($parents)
			{
				$parents = array_reverse($parents);
				$father = $parents[0];
			}			

			$shop_cats = array_merge($parents, get_categories(array('child_of' => $queried_object->parent, 'exclude' => $instance['exclude-category'], 'order' => 'DESC')));
			
			$filter_cats    = array();
			$child_nodes    = $this->getAllChildNodes($shop_cats);							
			$child_ids      = $this->getAllChilds($queried_object->term_id, $queried_object->taxonomy);
			$display_cats   = getParentsIDs($queried_object->term_id, $queried_object->taxonomy);			
			$display_cats   = array_merge($display_cats, $child_ids);
			$display_cats   = ($queried_object->taxonomy != 'category') ? array() : $display_cats;
			$display_cats   = (count($child_ids) < 1) ? array() : $display_cats; 			
		
			$display_tree   = $this->dispalyAllNodes($child_nodes, $father->parent, 0, '<div class="sub-category" style="%s">', "</div>", '<div class="f-row %s" %s>', "</div>", $display_cats);
			
			if(is_array($display_tree))
			{				
				$display_tree = implode(' ', $display_tree);
			}

			?>
			<div class="shop-by-category open">
				<div class="holder">
					<h4><span><?php echo $instance['title-category']; ?></span></h4>
					<div class="f-container">						
						<?php echo $display_tree; ?>
					</div><!-- f-container -->
				</div><!-- holder -->
			</div><!-- shop-by-category -->

			<?php 
			array_walk($this->getCustomTaxBlocks($this->custom_taxs, $instance, NULL), array($this, 'displayCustomBlocks'));
			?>
		</form>
		<?php
		echo '</div>';
		?>
		<script type="text/javascript">
			// =========================================================
			// STYLIZE CHECKBOX
			// =========================================================
			jQuery('.widget-filter .f-row').jqTransform();
			jQuery('.f-block .f-container, .shop-by-category, .widget-selection .holder').mCustomScrollbar({
				scrollButtons:{
					enable: true
				}
			});	
		</script>
		
		<?php
	}

	function clear_cname($name) {
		$repl = array("Women's", "Women", "Men's", "Men");
		return trim(str_replace($repl, '', $name));
	}

	function arrayUnique($array)
	{
		foreach ($array as $key => &$value) 
		{
			$new_arr[$key] = serialize($value);
		}

		$new_arr = array_unique($new_arr);
		foreach ($new_arr as $key => &$value) 
		{
			$value = unserialize($value);
		}
		return $new_arr;
	}

	function getAllChilds($id, $tax)
	{
		$ids  = array();
		$args = array(
			'type'                     => 'post',
			'child_of'                 => $id,
			'parent'                   => '',
			'orderby'                  => 'name',
			'order'                    => 'ASC',
			'hide_empty'               => 1,
			'hierarchical'             => 1,
			'exclude'                  => '',
			'include'                  => '',
			'number'                   => '',
			'taxonomy'                 => $tax,
			'pad_counts'               => false); 
		$categories = get_categories( $args );
		foreach ($categories as &$cat) 
		{
			$ids[] = $cat->term_id;
		}
		return $ids;
	}

	/**
	 * Display all child nodes from Adjacency List
	 * @param  array $data          - MySQL data
	 * @param  array $child_nodes   - Child Nodes
	 * @param  integer $parent_id   - Parent ID
	 * @param  string $before_nodes - Before Nodes HTML
	 * @param  string $after_nodes  - After Nodes HTML
	 * @param  string $before_node  - Before Node HTML
	 * @param  string $after_node   - After Node HTML
	 * @return string               - HTML
	 */
	function dispalyAllNodes($child_nodes, $parent_id, $depth = 0, $before_nodes = '<div class="sub-category" style="%s">', $after_nodes = "</div>", $before_node = '<div class="f-row %s" %s>', $after_node = "</div>", $display_cats = array())
	{
		$queried_object = get_queried_object();		
		$str            = "";		
		$arr            = array();
		$parent_id      = $parent_id === NULL ? "NULL" : $parent_id;

		$mcats = get_categories('child_of=156&hide_empty=0');
		if ($mcats) 
		{
			foreach($mcats as $mcat) 
			{
				$mens_category_ids[] = $mcat->term_id;
			}
		}
		$wcats = get_categories('child_of=418&hide_empty=0');
		if ($wcats) 
		{
			foreach($wcats as $wcat) 
			{
				$womens_category_ids[] = $wcat->term_id;
			}
		}

		if (isset($child_nodes[$parent_id])) 
		{
		    foreach ($child_nodes[$parent_id] as $id) 
		    {

				$cat      = get_category($id);		    			        
				$child_dn = self::dispalyAllNodes($child_nodes, $id, ($depth+1), $before_nodes, $after_nodes, $before_node, $after_node, $display_cats);
				$checked  = (is_category($cat->name)) ? 'checked' : '';
				$disabled = '';
				if($this->isNodeCheck($cat->term_id))
				{
					$checked  = 'checked';
					$disabled = 'disabled="disabled"';
				}
				$frozen   = '';
				if($this->isFrozenCat($cat->term_id))
				{
					$frozen   = 'frozen';
				}
				$rel            = '';
				$rel            = (strpos($cat->slug, 'shoes') !== false) ? 'shoes' : $cat->slug;
				$rel            = (strpos($cat->slug, 'clothes') !== false) ? 'clothes' : $rel;
				$rel            = (strpos($cat->slug, 'rings') !== false) ? 'rings' : $rel;

				$sex = in_array($cat->term_id, $mens_category_ids) ? 'men' : '';
				$sex = in_array($cat->term_id, $womens_category_ids) ? 'women' : $sex;

				
				
				$input          = ($depth > 0) ? '<input '.$disabled.' class="'.$frozen.'" data-block="shop-by-category" autocomplete="off" onchange="filter.filter(event, this)" type="checkbox" name="filter-category[]" data-sex="'.$sex.'" data-depth="'.$depth.'" value="'.$cat->slug.'" id="category-'.$cat->term_id.'" rel="'.$rel.'" '.$checked.' />' : '';
				$search_replace = array('Women\'s ', 'Men\'s ', 'Womens');
				$name           = str_replace($search_replace, '', $cat->name);
				$parent_ids     = getParentsIDs($queried_object->term_id, $queried_object->taxonomy);
				$display_block  = ($queried_object->term_id == $cat->term_id) ? 'display: block;' : 'display: none;';				
				$display_block  = in_array($cat->term_id, $parent_ids) ? 'display: block;' : $display_block;

		        if($child_dn)
		        {
		        	ksort($child_dn);
					$has_drop = 'has-drop';
					$nodes    = sprintf($before_nodes, $display_block).implode(' ', $child_dn).$after_nodes;
		        }
		        else
		        {
					$has_drop = '';
					$nodes    = '';
		        }
		        
				$wrap_node       = $this->getWrapNode($depth, $has_drop, $cat->name);
				$hide_row        = in_array($id, $display_cats) ? '' : 'hide';
				$hide_row        = count($display_cats) > 0 ? $hide_row : '';
				$arr[$cat->slug] = sprintf($before_node, $hide_row, 'id="row-'.$cat->term_id.'" data-tax="'.$cat->taxonomy.'" data-id="'.$cat->term_id.'"').$input.$wrap_node['start'].$name.$wrap_node['end'].$nodes.$after_node;            
		    }
		}
		return $arr;
	}	

	function isNodeCheck($id)
	{
		$qo = get_queried_object();
		$cats[] = intval($qo->term_id);
		$defaults = array(
			'tax_cat_1',
			'tax_cat_2',	
			'tax_cat_3',	
			'tax_cat_4',	
			'tax_cat_5',
			'tax_sale',
			'tax_colours',
			'tax_sizes',
			'tax_ring_sizes',
			'tax_clothes_sizes',
			'tax_selections',
			'tax_brands',
			'tax_styles',
			'tax_prices',
			'tax_seller_category'
		);
		
		if(is_array($_GET))
		{
			foreach ($defaults as $key) 
			{
				if(isset($_GET['cats'][$key]))
				{
					$cats = array_merge($cats, explode(',', $_GET['cats'][$key]));
				}
			}
		}
		if(in_array($id, $cats)) return true;
		
		return false;
	}

	function isFrozenCat($id)
	{
		global $OPTION;
		$qo = get_queried_object();
		$frozen_cats = array(
			$OPTION['wps_sale_category'],
			$OPTION['wps_women_bags_category'],
			$OPTION['wps_women_shoes_category'],
			$OPTION['wps_women_watches_category'],
			$OPTION['wps_women_sunglasses_category'],
			$OPTION['wps_women_jewelry_category'],
			$OPTION['wps_women_accessories_category'],
			$OPTION['wps_women_clothes_category'],
			$OPTION['wps_women_limited_edition_category'],
			$OPTION['wps_men_bags_category'],
			$OPTION['wps_men_shoes_category'],
			$OPTION['wps_men_watches_category'],
			$OPTION['wps_men_sunglasses_category'],
			$OPTION['wps_men_jewelry_category'],
			$OPTION['wps_men_accessories_category'],
			$OPTION['wps_men_clothes_category'],
			$OPTION['wps_men_limited_edition_category']
		);
		if(($id == $qo->term_id) && in_array($id, $frozen_cats)) return true;
		
		return false;
	}

	/**
	 * Get Node Wrap
	 * @param  integer $index 
	 * @param  string $class 
	 * @return array        
	 */
	function getWrapNode($index, $class = '', $title = '')
	{		
		$arrow = $class == '' ? '' : '<div class="arrow has-drop" style="display:inline; cursor: pointer" onclick="hasDrop(event, this)"></div>';
		$depth_wrap[0]  = array('start' => '<label title="'.$title.'" data-depth="'.$index.'"><strong>', 'end' => '</strong></label>'.$arrow);
		$depth_wrap[-1] = array('start' => '<label title="'.$title.'" data-depth="'.$index.'">', 'end' => '</label>'.$arrow);

		if(isset($depth_wrap[$index]))
		{
			return $depth_wrap[$index];
		}
		return $depth_wrap[-1];
	}

	/**
	 * Get all child nodes data
	 * @param  array $data
	 * @return array
	 */
	function getAllChildNodes($data)
	{
		$child_nodes = array();
		if($data)
		{
			foreach ($data as $key => $value) 
			{	
				$child_nodes[$value->parent][] = $value->term_id;
			}
		}

		return $child_nodes;
	}

	/**
	 * Get all parent nodes
	 * @param  object $parent  
	 * @param  array $parents 
	 * @return mixed
	 */
	function getAllParentNodes($parent, &$parents, $first = true)
	{
		if($first) $first = false;
		else $parents[] = $parent;
		if($parent->parent != 0)
		{			
			$new_parent = get_category($parent->parent, false);		
			$this->getAllParentNodes($new_parent, $parents, $first);	
		}
	}

	/**
	 * Display category blocks
	 * @param  string $value 
	 * @param  string $key   
	 */
	function displayCustomBlocks($value, $key)
	{
		echo $value;
	}

	function customSort($arr, $fields)
	{
		$indexes = null;
		if($arr)
		{
			foreach ($arr as $key => $value) 
			{
				$indexes[$value->slug] = $key;
			}
		}

		foreach ($fields as $field) 
		{
			$i = $indexes[$field];
			$res_arr[] = $arr[$i];
			unset($arr[$i]);
		}
		if(count($arr) > 0)
		{
			foreach ($arr as $item) 
			{
				$res_arr[] = $item;
			}
		}
		return $res_arr;
	}

	function renameTax($taxonomies, $new_names)
	{
		if(!$new_names) return false;
		if(!$taxonomies) return false;

		foreach ($new_names as $slug => $name) 
		{
			foreach ($taxonomies as &$tax) 
			{
				if($tax->slug == $slug) $tax->name = $name;
			}
		}
		return $taxonomies;
	}

	/**
	 * Get blocks from taxonomies
	 * @param  array $taxonomies 
	 * @param  array $instance   
	 * @return array             
	 */
	function getCustomTaxBlocks($taxonomies, $instance, $display_cats = NULL)
	{
		$blocks_arr = array();
		$block      = '';

		foreach($taxonomies as $custom_tax) 
		{
			if ($instance['include-'.$custom_tax] == '1') 
			{				
				$cust_taxs = get_terms($custom_tax, '' );

				if($custom_tax == 'selection')
				{
					$cust_taxs = $this->customSort($cust_taxs, array('new', 'like-new', 'like', 'gently-used', 'well-used'));	
					$cust_taxs = $this->renameTax($cust_taxs, array(
						'new'         => 'New-N',
						'like-new'    => 'Like New- LN',						
						'gently-used' => 'Gently Used- GU',
						'well-used'   => 'Well Used- WU'));
				}
				
				if ($cust_taxs) 
				{	
					if(is_array($display_cats))
					{
						$only_slugs    = $this->getSlugs($display_cats);							
						foreach ($cust_taxs as &$value) 
						{
							if(isset($only_slugs[$custom_tax][$value->slug])) $value->visible = true;								
							else $value->visible = false;
						}						
					}

					$block = '<div class="f-block shop-by-'.$custom_tax.'">';					
					if (strpos($custom_tax, 'size') !== false) $block.= '<div class="size-guide-link"><a href="#size-guide" rel="'.$custom_tax.'">Size guide</a></div>';
					$block.= '<h4><span>'.$instance['title-'.$custom_tax].'</span></h4>';
					if($custom_tax == 'brand')
					{
						$block .= $this->getSearchInBrands();
					}
					$block.= '<div class="f-container">';
					$block.= '<div class="f-holder">';
					
					foreach($cust_taxs as $cust_tax) 
					{		
						if($cust_tax->term_id != NULL)
						{
							$block.='<div id="row-'.$cust_tax->term_id.'" class="f-row" data-tax="'.$cust_tax->taxonomy.'" data-id="'.$cust_tax->term_id.'">';
							if ($custom_tax == 'price')
							{
								$currency_factor = array(
									'data-usd' => 1,
									'data-jod' => 0.708749872,
									'data-lbp' => 1512.8593,
									'data-qar' => 3.64150146,
									'data-sar' => 3.75089553,
									'data-aed' => 3.67309458,
									'data-omr' => 0.385100018
								);

								$currency_rate = (!$_SESSION["currency-rate"]) ? $_SESSION["currency-rate"] : 1;
								$ctsarray      = explode('-', $cust_tax->slug);
								$ctname        = $ctsarray[0] * $currency_rate;

								if (count($ctsarray) > 1) 
								{
									$ctname .= ' - '.$ctsarray[1] * $currency_rate;

									$currency_list['data-usd'] = round(($ctsarray[0] * $currency_factor['data-usd'])).' - '.round(($ctsarray[1] * $currency_factor['data-usd']));
									$currency_list['data-jod'] = round(($ctsarray[0] * $currency_factor['data-jod'])).' - '.round(($ctsarray[1] * $currency_factor['data-jod']));
									$currency_list['data-lbp'] = round(($ctsarray[0] * $currency_factor['data-lbp'])).' - '.round(($ctsarray[1] * $currency_factor['data-lbp']));
									$currency_list['data-qar'] = round(($ctsarray[0] * $currency_factor['data-qar'])).' - '.round(($ctsarray[1] * $currency_factor['data-qar']));
									$currency_list['data-sar'] = round(($ctsarray[0] * $currency_factor['data-sar'])).' - '.round(($ctsarray[1] * $currency_factor['data-sar']));
									$currency_list['data-aed'] = round(($ctsarray[0] * $currency_factor['data-aed'])).' - '.round(($ctsarray[1] * $currency_factor['data-aed']));
									$currency_list['data-omr'] = round(($ctsarray[0] * $currency_factor['data-omr'])).' - '.round(($ctsarray[1] * $currency_factor['data-omr']));
								} 
								else if (strpos($cust_tax->name, '+')) 
								{
									$ctname .= ' +';

									$currency_list['data-usd'] = round(($ctsarray[0] * $currency_factor['data-usd'])).' +';
									$currency_list['data-jod'] = round(($ctsarray[0] * $currency_factor['data-jod'])).' +';
									$currency_list['data-lbp'] = round(($ctsarray[0] * $currency_factor['data-lbp'])).' +';
									$currency_list['data-qar'] = round(($ctsarray[0] * $currency_factor['data-qar'])).' +';
									$currency_list['data-sar'] = round(($ctsarray[0] * $currency_factor['data-sar'])).' +';
									$currency_list['data-aed'] = round(($ctsarray[0] * $currency_factor['data-aed'])).' +';
									$currency_list['data-omr'] = round(($ctsarray[0] * $currency_factor['data-omr'])).' +';
								}
	
								$checked  = $this->isNodeCheck($cust_tax->term_id) ? 'checked' : '';


								if(isset($_GET['sbrand']) AND strlen($_GET['sbrand']))
								{
									if($cust_tax->slug == $_GET['sbrand']) $checked = ' CHECKED';
								}

								$currency_str = '';
								foreach ($currency_list as $key => $value) 
								{
									$currency_str.= $key.'="'.$value.'" ';
								}

								$block.= '<input data-block="shop-by-'.$custom_tax.'" onchange="filter.filter(event, this)" autocomplete="off" type="checkbox" name="filter-'.$custom_tax.'[]" value="'.$cust_tax->slug.'" id="'.$custom_tax.'-'.$cust_tax->term_id.'"'.$checked.' />';
								$block.= '<label '.$currency_str.' title="'.$cust_tax->name.'" id="label-'.$cust_tax->slug.'" data-input="'.$custom_tax.'-'.$cust_tax->term_id.'" data-a="a-'.$custom_tax.'-'.$cust_tax->term_id.'">'.$ctname.' '.$_SESSION["currency-code"].'</label>';	
							}
							else
							{
								$checked  = $this->isNodeCheck($cust_tax->term_id) ? 'checked' : '';

								if(isset($_GET['sbrand']) AND strlen($_GET['sbrand']))
								{
									if($cust_tax->slug == $_GET['sbrand']) $checked = ' CHECKED';
								}

								$block.= '<input data-block="shop-by-'.$custom_tax.'" onchange="filter.filter(event, this)" autocomplete="off" type="checkbox" name="filter-'.$custom_tax.'[]" value="'.$cust_tax->slug.'" id="'.$custom_tax.'-'.$cust_tax->term_id.'"'.$checked.'/>';
								$block.= '<label title="'.$cust_tax->name.'" id="label-'.$cust_tax->slug.'" data-input="'.$custom_tax.'-'.$cust_tax->term_id.'" data-a="a-'.$custom_tax.'-'.$cust_tax->term_id.'">'.$cust_tax->name.'</label>';	
							}
							$block.='</div><!-- f-row -->';
						}
						
					}

					$block.= '</div><!-- f-container -->';
					$block.= '</div><!-- f-holder -->';
					$block.= '</div><!-- f-block -->';
					$blocks_arr[$custom_tax] = $block;
				}
			}
		} 		
		return $blocks_arr;
	}

	function getSearchInBrands()
	{
		ob_start();
		?>
		<div class="checkbox-list-search" style="display:none;">
            <input type="text" placeholder="Search" data-block="shop-by-brand" name="checkbox-list-search-input" autocomplete="off" oninput="search_list.search(this)">
            <button name="checkbox-list-search-button" type="button" onclick="search_list.searchButton(event)">Search</button>
        </div>
		<?php
		
		$var = ob_get_contents();
		ob_end_clean();
		return $var;
	}

	/**
	 * Get only slug's from display categories
	 * @param  array $display_cats 
	 * @return array               
	 */
	function getSlugs($display_cats)
	{
		foreach ($display_cats as $key => &$value) 
		{
			if(is_array($value))
			{
				foreach ($value as $key2 => &$value2) 
				{
					$slug               = $value2['slug'];
					$slugs[$key][$slug] = $key2;
				}
			}
		}
		return $slugs;
	}

	/**
	 * Get hide CSS class
	 * @param  boolean $bool 
	 * @return string       
	 */
	function hide($bool)
	{
		if($bool) return 'hide';
		return '';
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 		 = strip_tags($new_instance['title']);
		$instance['button_text'] = $new_instance['button_text'];
		$instance['search_page'] = $new_instance['search_page'];
		$instance['title-category'] = $new_instance['title-category'];
		$instance['exclude-category'] = $new_instance['exclude-category'];
		foreach($this->custom_taxs as $custom_tax) {
			$instance['include-'.$custom_tax] = $new_instance['include-'.$custom_tax];
			$instance['title-'.$custom_tax] = $new_instance['title-'.$custom_tax];
		}
		
		return $instance;
	}
	
	function form($instance){
		//Set up some default widget settings. 
		$defaults = array( 'title' => 'Filter Your Search By', 'button_text' => 'SEARCH', 'title-category' => 'Category' );
		foreach($this->custom_taxs as $custom_tax) {
			$defaults['title-'.$custom_tax] = ucfirst($custom_tax);
		}
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'search_page' ); ?>"><?php _e('Search Results Page:', 'wpShop'); ?></label>
			<select id="<?php echo $this->get_field_id( 'search_page' ); ?>" name="<?php echo $this->get_field_name( 'search_page' ); ?>" style="width:100%;">
				<option value="">-- Select Page --</option>
				<?php
				$spages = get_pages('child_of=0');
				if ($spages) {
					foreach($spages as $spage) { ?>
						<option value="<?php echo $spage->ID; ?>"<?php if ($instance['search_page'] == $spage->ID) { echo ' SELECTED'; } ?>><?php echo $spage->post_title; ?></option>
					<?php
					}
				}
				?>
			</select>
		</p>
		<p>
			<label>Shop by Category</label><br/>
			<label>Title:</label>
			<input id="<?php echo $this->get_field_id( 'title-category' ); ?>" name="<?php echo $this->get_field_name( 'title-category' ); ?>" value="<?php echo $instance['title-category']; ?>" style="width:80%;" /><br/>
			<label>Exclude:</label>
			<input id="<?php echo $this->get_field_id( 'exclude-category' ); ?>" name="<?php echo $this->get_field_name( 'exclude-category' ); ?>" value="<?php echo $instance['exclude-category']; ?>" style="width:80%;" /><br/>
		</p>
		<?php foreach($this->custom_taxs as $custom_tax) { ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'include-'.$custom_tax ); ?>"><input type="checkbox" id="<?php echo $this->get_field_id( 'include-'.$custom_tax ); ?>" name="<?php echo $this->get_field_name( 'include-'.$custom_tax ); ?>" value="1"<?php if ($instance['include-'.$custom_tax] == '1') { echo ' CHECKED'; } ?>> Shop by <?php echo ucfirst($custom_tax); ?><?php if($custom_tax == 'size') { echo ' (if selected Shoes category)'; } else if ($custom_tax == 'ring-size') { echo ' (if selected Jewelry category)'; } ?></label><br/>
			<label for="<?php echo $this->get_field_id( 'title-'.$custom_tax ); ?>">Title:</label>
			<input id="<?php echo $this->get_field_id( 'title-'.$custom_tax ); ?>" name="<?php echo $this->get_field_name( 'title-'.$custom_tax ); ?>" value="<?php echo $instance['title-'.$custom_tax]; ?>" style="width:80%;" />
		</p>
		<?php } ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'button_text' ); ?>"><?php _e('Button Text:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'button_text' ); ?>" name="<?php echo $this->get_field_name( 'button_text' ); ?>" value="<?php echo $instance['button_text']; ?>" style="width:97%;" />
		</p>
	<?php }
}

// sidebar create alert widget
class ShopCreateAlertWidget extends WP_Widget {

	function ShopCreateAlertWidget() {
		$widget_ops 	= array('classname' => 'widget_shop_create_alert', 'description' => __( 'NWS Shop Create Alert', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'shop-create-alert-widget');
		$this->WP_Widget('shop-create-alert-widget', __('NWS Shop Create Alert', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
		global $OPTION, $wpdb, $current_user, $wp_query;
		extract($args);
		$title = apply_filters('widget_title', $instance['title'] );
		$text = $instance['text'];
		$but_text = $instance['but_text'];

		$request_url = $_SERVER['REQUEST_URI'];
		if (strpos($request_url, '?') !== false) { $request_url .= '&'; } else { $request_url .= '?'; }
		$request_url .= 'salerts=';

		if ($OPTION['wps_alerts_enable']) {
			# Before the widget
			echo $before_widget; ?>
			<div class="widget-selection create-alert-widget">
				<div class="holder">
					<div class="frame">
						<?php if (strlen($title)) { echo '<h3>' . $title . '</h3>'; } ?>
						<p><?php echo $text; ?></p>
						<ul class="alert-requests-list"></ul>
						<input type="hidden" name="ca_salerts" id="ca-salerts" value="<?php echo $_GET['salerts']; ?>">
						<input type="hidden" name="ca_request_url" id="ca-request-url" value="<?php echo $request_url; ?>">
					</div>
				</div>
				<a href="#notify" class="button btn-orange sidebar-create-alert-button" alt="<?php echo $but_text; ?>"><?php echo $but_text; ?></a>
			</div>
			<?php # After the widget
			echo $after_widget;
		}
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['text'] = $new_instance['text'];
		$instance['but_text'] = $new_instance['but_text'];
		return $instance;
	}
	
	function form($instance){
		//Set up some default widget settings. 
		$defaults = array('title' => 'My Selection', 'text' => 'Get alerted when a new item is added', 'but_text' => 'Notify Me');
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e('Widget Text:', 'wpShop'); ?></label>
			<textarea id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" style="width:100%;"><?php echo $instance['text']; ?></textarea>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'but_text' ); ?>"><?php _e('Button Text:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'but_text' ); ?>" name="<?php echo $this->get_field_name( 'but_text' ); ?>" value="<?php echo $instance['but_text']; ?>" style="width:97%;" />
		</p>
	<?php
	}
}

// sidebar follow brands widget
class ShopFollowBrandsWidget extends WP_Widget {

	function ShopFollowBrandsWidget() {
		$widget_ops 	= array('classname' => 'shop_follow_brands_widget', 'description' => __( 'NWS Shop Follow Brands', 'wpShop') );
		$control_ops 	= array('width' => 300, 'height' => 300, 'id_base' => 'shop-follow-brands-widget');
		$this->WP_Widget('shop-follow-brands-widget', __('NWS Shop Follow Brands', 'wpShop'), $widget_ops, $control_ops);
    }
	
	function widget($args, $instance){
		global $OPTION, $wpdb, $wp_query;
		extract($args);
		$title = apply_filters('widget_title', $instance['title'] );
		$content = $instance['content'];
		$success = $instance['success'];

		if ($OPTION['wps_alerts_enable']) {
			if (is_tax('brand') || strlen($_GET['filter-brand'])) {
				if (is_tax('brand')) {
					$brand_data = get_queried_object();
				} else {
					$brand_data = get_term_by('slug', $_GET['filter-brand'], 'brand');
				}
				$brand_id = $brand_data->term_id;
				$brand_name = $brand_data->name;

				$title .= ' ' . $brand_name;
				
				# Before the widget
				echo $before_widget;
				echo $before_title . $title . $after_title;
				?>
				<div class="text-holder"><img alt="heart" src="<?php bloginfo('template_url'); ?>/images/img/heart.gif"><p><?php echo $content; ?></p></div>
				<div title="<?php echo $title; ?>" class="follow-brands">
					<form id="follow_brands_submit" name="follow_brands_submit" method="POST">
						<input type="hidden" value="<?php echo $brand_id; ?>" id="follow_brands_brand" name="follow_brands_brand">
						<input type="text" id="follow_brands_email" name="follow_brands_email" onblur="if (this.value == '') {this.value = 'Enter email address...';}" onfocus="if (this.value == 'Enter email address...') {this.value = '';}" size="20" value="Enter email address...">
						<div class="btn-holder">
							<input type="submit" value="Follow" id="follow_brands_subscribe">
						</div>
					</form>
					<div class="actions">
						<div class="loading" style="display: none;"><img src="<?php bloginfo('template_url'); ?>/images/loading-ajax.gif"></div>
						<div class="error" style="display: none;"><p class="follow_brands_error">Please enter correct your email address</p></div>
						<div class="result" style="display: none;"><p class="follow_brands_message"><?php echo str_replace('%brand-name%', $brand_name, $success); ?></p></div>
					</div>
				</div>
				<?php # After the widget
				echo $after_widget;
			}
		}
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['content'] = $new_instance['content'];
		$instance['success'] = $new_instance['success'];
		return $instance;
	}
	
	function form($instance){
		//Set up some default widget settings. 
		$defaults = array('title' => 'Follow', 'content' => 'Get alerted when a new bag is added', 'success' => 'You are now following *%brand-name%*');
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wpShop'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'content' ); ?>"><?php _e('Widget Text:', 'wpShop'); ?></label>
			<textarea id="<?php echo $this->get_field_id( 'content' ); ?>" name="<?php echo $this->get_field_name( 'content' ); ?>" style="width:100%;"><?php echo $instance['content']; ?></textarea>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'success' ); ?>"><?php _e('Success Message:', 'wpShop'); ?></label>
			<textarea id="<?php echo $this->get_field_id( 'success' ); ?>" name="<?php echo $this->get_field_name( 'success' ); ?>" style="width:100%;"><?php echo $instance['success']; ?></textarea>
		</p>
	<?php
	}
}

function TheFurnitureStoreWidgets() {
	register_widget('CategoryRssListWidget');
	register_widget('StoreCategoriesWidget');
	register_widget('PagesListWidget');
	//register_widget('GiftCardsWidget');
	register_widget('EmailSubscriptionsWidget');
	register_widget('PromotionsWidget');
	register_widget('Promotions2LinksWidget');
	register_widget('Promotions3LinksWidget');
	register_widget('ContactWidget');
	register_widget('ShopByAllPurposeWidget');
	register_widget('ShopByOutfitWidget');
	register_widget('ShopByFitWidget');
	register_widget('ShopBySizeWidget');
	register_widget('ShopByColourWidget');
	register_widget('ShopByBrandWidget');
	register_widget('ShopBySelectionWidget');
	register_widget('ShopByStyleWidget');
	register_widget('ShopByPriceWidget');
	register_widget('FAQs');
	register_widget('RecentProds');
	register_widget('RecentBlogPosts');
	register_widget('TrackOrder');
	register_widget('BlogCategoriesWidget');
	register_widget('ShopByCat');
	register_widget('ShopRecentlyAddedWidget');
	register_widget('ShopFeaturedProductsWidget');
	register_widget('ImagesGalleryScrollerWidget');
	register_widget('InThePressSliderWidget');
	register_widget('ShopSearchFilterWidget');
	register_widget('ShopCreateAlertWidget');
	register_widget('ShopFollowBrandsWidget');
}
add_action('widgets_init', 'TheFurnitureStoreWidgets');

load_theme_textdomain('wpShop', get_template_directory().'/languages/');
?>