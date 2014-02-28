<?php
##################################################################################################################################
// 	                                          common sense security precautions
##################################################################################################################################
//hide login errors
//add_filter('login_errors',create_function('$a', "return null;"));

//hide wordpress version
add_filter( 'the_generator', create_function('$a', "return null;") );


$order_payment_methods = array(
	'paypal' => __('PayPal Standard','wpShop'),
	'paypal_pro' => __('PayPal Pro','wpShop'),
	'audi' => __('CC by Audi','wpShop'),
	'cc_authn' => __('Authorize.net','wpShop'),
	'g2p_authn' => __('Gate2Play.com','wpShop'),
	'cc_wp' => __('WorldPay','wpShop'),
	'transfer' => __('Bank Transfer','wpShop'),
	'cash' => __('Cash on Location','wpShop'),
	'cod' => __('Cash on Delivery','wpShop')
);

##################################################################################################################################
// 	                                          SEND Email
##################################################################################################################################
function NWS_send_email($to, $subject, $message, $from_email = '', $from_name = '', $cc_email = '') {
	global $OPTION;
	if (!strlen($from_email)) { $from_email = $OPTION['wps_shop_email']; }
	if (!strlen($from_name)) { $from_name = $OPTION['wps_shop_name']; $from_name = str_replace(",", '', $from_name); }

	$EMAIL = load_what_is_needed('email');
	if (WPSHOP_EMAIL_FORMAT_OPTION == 'mime') {
		$EMAIL->mime_mail($to, $subject, $message, $message, $from_email, $from_name, 'zend', $cc_email);
	} else {
		$EMAIL->send_mail($to, $subject, $message, $from_email, $from_name, $cc_email);
	}
}

##################################################################################################################################
// 	                                          SEO Optimization 
##################################################################################################################################
function NWS_metaKeywordTags() {
	$posttags = get_the_tags();
	foreach((array)$posttags as $tag) {
		$meta_tags .= $tag->name . ',';
	}
	//remove last comma
	$meta_tags = substr($meta_tags, 0, -1); 
	
	echo '<meta name="keywords" content="'.$meta_tags.'" />';
}

function NWS_custom_cat_meta( $tag ) {

	//check for existing term title
	$cat_term_hTitle = get_option( 'term_hTitle' );
	
	$term_hTitle = '';
	if ( is_array( $cat_term_hTitle ) && array_key_exists( $tag->term_id, $cat_term_hTitle ) ) {
		$term_hTitle = $cat_term_hTitle[$tag->term_id];
		$term_hTitle = stripslashes($term_hTitle);	
	}
	
	//check for existing term keywords
	$cat_term_keywords = get_option( 'term_keywords' );
	
	$term_keywords = '';
	if ( is_array( $cat_term_keywords ) && array_key_exists( $tag->term_id, $cat_term_keywords ) ) {
		$term_keywords = $cat_term_keywords[$tag->term_id];
		$term_keywords = stripslashes($term_keywords);	
	}
	
	
?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="term_hTitle"><?php _e('SEO Header Title') ?></label></th>
        <td>
        	<input type="text" name="term_hTitle" id="term_hTitle" size="40" style="width:95%;" value="<?php echo $term_hTitle; ?>"><br />
            <span class="description"><?php _e('For SEO Optimized Header Titles. Enter your Keywords using a "|" as separator') ?></span>
        </td>
    </tr>
	
	<tr class="form-field">
        <th scope="row" valign="top"><label for="term_keywords"><?php _e('SEO Keywords') ?></label></th>
        <td>
        	<textarea style="width: 97%;" cols="50" rows="5" id="term_keywords" name="term_keywords"><?php echo $term_keywords; ?></textarea><br />
            <span class="description"><?php _e('For SEO Optimized Keywords. Enter your Keywords using a "," as separator') ?></span>
        </td>
    </tr>
	

<?php
}

function NWS_save_custom_cat_meta( $term_id ) {
	// term titles
	if ( isset( $_POST['term_hTitle'] ) ) {

		//load existing category term_hTitle
		$current_term_hTitle = get_option( 'term_hTitle' );

		//set term_hTitle to proper category ID in options array
		$current_term_hTitle[$term_id] = (string)( $_POST['term_hTitle'] );

		//save the option array
		update_option( 'term_hTitle', $current_term_hTitle );
	}
	
	//term keywords
	if ( isset( $_POST['term_keywords'] ) ) {

		//load existing category term_keywords
		$current_term_keywords = get_option( 'term_keywords' );

		//set term_keywords to proper category ID in options array
		$current_term_keywords[$term_id] = (string)( $_POST['term_keywords'] );

		//save the option array
		update_option( 'term_keywords', $current_term_keywords );
	}
}

function NWS_delete_custom_cat_meta( $term_id ) {
    //load existing category term_hTitle
    $current_term_hTitle = get_option( 'term_hTitle' );

    //if there is a term_hTitle for a deleted category ID in options array		
    if ( $current_term_hTitle[$term_id] ) {
        //remove this reference
        unset( $current_term_hTitle[$term_id] );
       
	   //has the category been deleted?, delete the option
        if ( empty( $current_term_hTitle ) ) {
            delete_option( 'term_hTitle' );
       
	   //else update it 
        } else {
            update_option( 'term_hTitle', $current_term_hTitle );
		}
    }
	
	//load existing category term_keywords
    $current_term_keywords = get_option( 'term_keywords' );

    //if there is a term_keywords for a deleted category ID in options array		
    if ( $current_term_keywords[$term_id] ) {
        //remove this reference
        unset( $current_term_keywords[$term_id] );
       
	   //has the category been deleted?, delete the option
        if ( empty( $current_term_keywords ) ) {
            delete_option( 'term_keywords' );
       
	   //else update it 
        } else {
            update_option( 'term_keywords', $current_term_keywords);
		}
    }
}

// Prepend the new column to the columns array
function NWSseo_columns($cols) {
	$cols['seo-title'] 		= __('SEO Header Title','wpShop');
	$cols['seo-keywords'] 	= __('SEO Keywords','wpShop');
	return $cols;
}
//get the value for the new column
function NWSseo_entry_return_value($value, $column_name, $tag) {
	
	if ($column_name == 'seo-title') {
	
		//this is for the SEO Optimized category titles
		$current_term_hTitle = get_option( 'term_hTitle' );
		
		if ( is_array( $current_term_hTitle ) && array_key_exists( $tag, $current_term_hTitle ) ) {
			$value = $current_term_hTitle[$tag];
			$value = stripslashes($value);
		}
	}
	
	if ($column_name == 'seo-keywords') {
	
		//this is for the SEO Optimized category titles
		$current_term_keywords = get_option( 'term_keywords' );
		
		if ( is_array( $current_term_keywords ) && array_key_exists( $tag, $current_term_keywords ) ) {
			$value = $current_term_keywords[$tag];
			$value = stripslashes($value);
		}
	}
	
	
	return $value;
}

//add all admin actions in 1 call
function NWScustom_add() {
	//get all registered taxonomies
	$taxonomies = get_taxonomies();
	// filter categories, post tags and custom taxonomies and return those only
	foreach($taxonomies as $k =>$v) {
		if($v == 'nav_menu' || $v == 'link_category') {
			unset($taxonomies[$k]);
		}
	}
	
	//this returns all registered taxonomies
	foreach ( $taxonomies as $taxonomy ) {
		add_action($taxonomy . '_edit_form_fields', 'NWS_custom_cat_meta', $taxonomy);
		add_action("edited_$taxonomy", 'NWS_save_custom_cat_meta');
		add_action("delete_$taxonomy", 'NWS_delete_custom_cat_meta');
		add_action("manage_edit-${taxonomy}_columns", 'NWSseo_columns');			
		add_filter("manage_${taxonomy}_custom_column", 'NWSseo_entry_return_value', 10, 3);
	}
}

add_action('admin_init', 'NWScustom_add');


##################################################################################################################################
// 	                                              Loading JS the right way
##################################################################################################################################
add_action('get_header', 'NWS_queue_js');

function NWS_queue_js(){
	
	global $OPTION;
	
	$DEFAULT = show_default_view();

	if (!is_admin()){
		if(($_SERVER['HTTPS'] == 'on')||($_SERVER['HTTPS'] == '1') || ($_SERVER['SSL'] == '1')){
			$siteurl = NWS_bloginfo('template_directory');		
			//jQuery Tools breaks with 1.5!
			//wp_enqueue_script( 'jquery-1.5.min',$siteurl.'/js/jquery-1.5.min.js', '1.5', true );
			wp_enqueue_script( 'jquery-1.4.4.min',$siteurl.'/js/jquery-1.4.4.min.js', '1.4.4', true );
		
			// do we want a customer area with a wishlist?			
			if($OPTION['wps_lrw_yes']) {
				wp_enqueue_script( 'check-email-ajax', $siteurl.'/js/check-email-ajax.js', array('jquery'), '1', true );
				wp_enqueue_script( 'check-user-ajax',$siteurl.'/js/check-user-ajax.js', array('jquery'), '1', true );
			}
			
			//display only on checkout
			if(!$DEFAULT){ 
				wp_enqueue_script( 'check-voucher-ajax', $siteurl.'/js/check-voucher-ajax.js', array('jquery'), '1', true );
				wp_enqueue_script( 'get_form_bAddress', $siteurl.'/js/get_form_bAddress.js', array('jquery'), '1', true );
				wp_enqueue_script( 'get_form_dAddress', $siteurl.'/js/get_form_dAddress.js', array('jquery'), '1', true ); 
			}
		
		} else {
			wp_deregister_script('jquery'); 
			//jQuery Tools breaks with 1.5!
			//wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"), false, '1.5'); 
			wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js?ver=1.4.4"), false, '1.4.4'); 
			wp_enqueue_script('jquery');
			
			if ( is_singular() AND comments_open() AND ($OPTION['thread_comments'] == 1)) {
			  wp_enqueue_script( 'comment-reply' );
			}
			
			// do we want the eCommerce engine?
			if($OPTION['wps_shoppingCartEngine_yes']) {
			
				//load only on product single page
				if (is_single()) {
					$WPS_prodImg_effect	= $OPTION['wps_prodImg_effect'];
					// are we using a Blog?
					$blog_Name 	= $OPTION['wps_blogCat'];
					
					if ($blog_Name != 'Select a Category') {
						
						$blog_ID 	= get_cat_ID( $blog_Name );
						// who's our ancestor, blog or shop?
						if (!(cat_is_ancestor_of( $blog_ID, (int)$category[0]->term_id )) || ($category[0]->term_id != $blog_ID)) {
							
							wp_enqueue_script( 'price', get_template_directory_uri().'/js/price.js', array('jquery'), '1', true );
							wp_enqueue_script( 'ajax_check_stock', get_template_directory_uri().'/js/ajax_check_stock.js', array('jquery'), '1', true );
							
						
						} else {}
						
					} else {
					
						wp_enqueue_script( 'price', get_template_directory_uri().'/js/price.js', array('jquery'), '1', true );
						wp_enqueue_script( 'ajax_check_stock', get_template_directory_uri().'/js/ajax_check_stock.js', array('jquery'), '1', true );
						
					}
				}
				//display only on checkout
				if(!$DEFAULT){ 
					wp_enqueue_script( 'check-voucher-ajax', get_template_directory_uri().'/js/check-voucher-ajax.js', array('jquery'), '1', true );
					wp_enqueue_script( 'get_form_bAddress', get_template_directory_uri().'/js/get_form_bAddress.js', array('jquery'), '1', true ); 
					wp_enqueue_script( 'get_form_dAddress', get_template_directory_uri().'/js/get_form_dAddress.js', array('jquery'), '1', true );
				}			
			}
			
			// do we want a customer area with a wishlist?			
			if($OPTION['wps_lrw_yes']) {
				wp_enqueue_script( 'check-email-ajax', get_template_directory_uri().'/js/check-email-ajax.js', array('jquery'), '1', true );
				wp_enqueue_script( 'check-user-ajax', get_template_directory_uri().'/js/check-user-ajax.js', array('jquery'), '1', true );
			}
		}
	}
}
##################################################################################################################################
// 	Some good functions from Theme Shaper (http://themeshaper.com/wordpress-themes-templates-tutorial/)
##################################################################################################################################
// Get the page number
function get_page_number() {
    if (get_query_var('paged')) {
        print ' | ' . __( 'Page ' , 'wpShop') . get_query_var('paged');
    }
} 

// For category lists on category archives: Returns other categories except the current one (redundant)
function cats_meow($glue) {
        $current_cat = single_cat_title( '', false );
        $separator = "\n";
        $cats = explode( $separator, get_the_category_list($separator) );
        foreach ( $cats as $i => $str ) {
                if ( strstr( $str, ">$current_cat<" ) ) {
                        unset($cats[$i]);
                        break;
                }
        }
        if ( empty($cats) )
                return false;

        return trim(join( $glue, $cats ));
}

// For tag lists on tag archives: Returns other tags except the current one (redundant)
function tag_ur_it($glue) {
	$current_tag = single_tag_title( '', '',  false );
	$separator = "\n";
	$tags = explode( $separator, get_the_tag_list( "", "$separator", "" ) );
	foreach ( $tags as $i => $str ) {
		if ( strstr( $str, ">$current_tag<" ) ) {
			unset($tags[$i]);
			break;
		}
	}
	if ( empty($tags) )
		return false;
 
	return trim(join( $glue, $tags ));
}

//check to see if a particular custom page template is active
function is_pagetemplate_active($pagetemplate = '') {
	global $wpdb;
	$sql = "select meta_key from $wpdb->postmeta where meta_key like '_wp_page_template' and meta_value like '" . $pagetemplate . "'";

	$result = $wpdb->query($sql);

	if ($result) {
		return TRUE;
	} else {
		return FALSE;
	}
}

// Excerpt or Content Word Limit in WordPress: Redux
// src: http://bavotasan.com/tutorials/limiting-the-number-of-words-in-your-excerpt-or-content-in-wordpress/

function NWS_excerpt($limit) {
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }	
  $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
  return $excerpt;
}
 
function NWS_content($limit) {
  $content = explode(' ', get_the_content(), $limit);
  if (count($content)>=$limit) {
    array_pop($content);
    $content = implode(" ",$content).'...';
  } else {
    $content = implode(" ",$content);
  }	
  $content = preg_replace('/\[.+\]/','', $content);
  $content = apply_filters('the_content', $content); 
  $content = str_replace(']]>', ']]&gt;', $content);
  return $content;
}
##################################################################################################################################
// 	                                             The Comments Template
##################################################################################################################################
//comments
function mytheme_comment($comment, $args, $depth) {

$noAvatarPath = get_bloginfo('stylesheet_directory').'/images/noAvatar.jpg';

   $GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
		<div id="comment-<?php comment_ID(); ?>" class="clearfix">
			<div class="who_when">
				<div class="comment-author vcard">
					<?php echo get_avatar($comment,$size='80',$default = $noAvatarPath ); ?>
					<?php printf(__('<cite class="fn">%s</cite>','wpShop'), get_comment_author_link()) ?>
				</div>
				<div class="comment-meta commentmetadata">
					<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
						<?php printf(__('%1$s at %2$s','wpShop'), get_comment_date(),  get_comment_time()) ?>
					</a>
				</div>
			</div>
			
			<div class="what">
				<?php if ($comment->comment_approved == '0') : ?>
					<em><?php _e('Your comment is awaiting moderation.', 'wpShop') ?></em>
					<br />
				<?php endif; ?>	
				<?php comment_text() ?>
				<div class="reply">
					<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
				</div>
			</div>
		</div>
<?php
}

function list_pings($comment, $args, $depth) {
       $GLOBALS['comment'] = $comment;
?>
        <li id="comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?>
<?php }

add_filter('get_comments_number', 'comment_count', 0);
function comment_count( $count ) {
        if ( ! is_admin() ) {
                global $id;
                $get_comments= get_comments('post_id=' . $id);
				$comments_by_type = &separate_comments($get_comments);
                return count($comments_by_type['comment']);
        } else {
                return $count;
        }
}


//stop comment spam!
function check_referrer() {
    if (!isset($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] == ��) {
        wp_die( __('Please enable referrers in your browser, or, if you\'re a spammer, bugger off!','wpShop') );
    }
}

add_action('check_comment_flood', 'check_referrer');

##################################################################################################################################
// 												REGISTER CUSTOM MENUS 
##################################################################################################################################
// is the option active?
if ($OPTION['wps_wp_custom_menus']) {

	add_action( 'init', 'register_NWS_menus' );

	function register_NWS_menus() {
		register_nav_menus(
			array(
				'primary-menu' => __( 'Primary Menu - below logo' ),
				'secondary-menu' => __( 'Secondary Menu - in small footer' ),
				'add-menu' => __( 'Add Menu - on page "Why Us" and "The Luxury Closet Promise"' )
			)
		);
	}
}


##################################################################################################################################
// 												VARIOUS FUNCTIONS :-) 
##################################################################################################################################
//Add excerpt box to page editor: http://justintadlock.com/archives/2009/11/09/excerpts-and-taxonomies-for-pages-in-wordpress-2-9
add_action( 'admin_menu', 'NWS_page_excerpt_meta_box' );

function NWS_page_excerpt_meta_box() {
	add_meta_box( 'postexcerpt', __('Excerpt'), 'post_excerpt_meta_box', 'page', 'normal', 'core' );
}

// Enable support for post-thumbnails
if ( function_exists('add_theme_support') ) {
	add_theme_support('post-thumbnails');
}

//create our own page menu a hybrid menu with categories and pages!
function NWS_page_cat_hybrid_menu($args = array(),$cat_args) {

	global $OPTION;

	$defaults = array('sort_column' => 'menu_order, post_title', 'menu_class' => 'menu', 'echo' => true, 'link_before' => '', 'link_after' => '');
	$args = wp_parse_args( $args, $defaults );
	$args = apply_filters( 'wp_page_menu_args', $args );

	$menu = '';

	$list_args = $args;
	$catlist_args = $cat_args;
	
	// Show Home in the menu
	if ( isset($args['show_home']) && ! empty($args['show_home']) ) {
		if ( true === $args['show_home'] || '1' === $args['show_home'] || 1 === $args['show_home'] )
			$text = __('Home','wpShop');
		else
			$text = $args['show_home'];
			$class = '';
			if ( is_front_page() && !is_paged() )
				$class = 'class="current_page_item"';
				$home_link = '<ul class="home_link"><li ' . $class . '><a href="' . get_option('home') . '">' . $args['link_before'] . $text . $args['link_after'] . '</a></li></ul>';
				// If the front page is a page, add it to the exclude list
				if ($OPTION['show_on_front'] == 'page') {
					if ( !empty( $list_args['exclude'] ) ) {
						$list_args['exclude'] .= ',';
					} else {
						$list_args['exclude'] = '';
					}
						$list_args['exclude'] .= $OPTION['page_on_front'];
				}
	}
	
	$catlist_args['echo'] 				= false;
	$catlist_args['title_li'] 			= '';
	$catlist_args['use_desc_for_title'] = false;
	
	$list_args['echo'] 					= false;
	$list_args['title_li'] 				= '';
	
	//pages
	$menu .= str_replace( array( "\r", "\n", "\t" ), '', wp_list_pages($list_args) );
	//remove title attr.
	$menu = preg_replace('/title=\"(.*?)\"/','',$menu);
	//cats
	$cat_menu = wp_list_categories($catlist_args); 
	//remove title attr.
	$cat_menu = preg_replace('/title=\"(.*?)\"/','',$cat_menu); 
	
	if ( $menu )
		$menu 		= '<ul class="pages">' . $menu . '</ul>';
	if ( $cat_menu )
		$cat_menu 	= '<ul class="categories">' . $cat_menu . '</ul>';
		
		$menu = '<div class="' . esc_attr($args['menu_class']) . '">' . $home_link . $cat_menu . $menu ."</div>\n";
		$menu = apply_filters( 'wp_page_menu', $menu, $args );
	if ( $args['echo'] )
		echo $menu;
	else
		return $menu;
}


function get_childtheme(){

	global $wpdb;
	
	$table 		= $wpdb->prefix . 'options';
	
	$qStr 		= "SELECT option_value FROM $table WHERE option_name = 'stylesheet' LIMIT 0,1";
	$res 		= mysql_query($qStr);	
	$row 		= mysql_fetch_assoc($res);
	$childTh	= $row['option_value'];			

return $childTh;
}

//filter search results - exclude categories from being searched.
function SearchFilter($query) {
	global $OPTION;
	if ($query->is_search) {
		//exclude all pages
		$query->set('post_type', 'post');
		
		$exclude = $OPTION['wps_search_excl'];
		$query->set('cat',$exclude);
	}
return $query;
}
add_filter('pre_get_posts','SearchFilter');

//pull the latest comment from twitter
function wp_echoTwitter($username){
     include_once(ABSPATH.WPINC.'/rss.php');
     $tweet = fetch_rss("http://search.twitter.com/search.atom?q=from:" . $username . "&rpp=1");
     echo $tweet->items[0]['atom_content'];
}

//Short Post URLs source: http://dancameron.org/code/short-post-urls
add_action( 'generate_rewrite_rules', 'custom_rewrite_rules' );
function custom_rewrite_rules( $wp_rewrite ){
	$newRules = array();
	$newRules[ 's/([0-9]+)$' ] = 'index.php?p=' . $wp_rewrite->preg_index( 1 );
	$wp_rewrite->rules = $newRules + $wp_rewrite->rules;
	
	return $wp_rewrite;
}


// enable full HTML on tag and custom taxonomy descriptions
remove_filter( 'pre_term_description', 'wp_filter_kses' );
remove_filter( 'term_description', 'wp_kses_data' );

//custom fields of current post
function get_custom_field($key, $echo = FALSE) {
	global $post;
	
	$custom_field = get_post_meta($post->ID, $key, true);
	if($echo == FALSE){ 
		return $custom_field; 
	} else {
		echo $custom_field;
	}
}

//custom fields of any post 
function get_custom_field2($post_id, $key, $echo = FALSE) {
	global $post;
	
	$custom_field = get_post_meta($post_id, $key, true);
	if($echo == FALSE){ 
		return $custom_field; 
	} else {
		echo $custom_field;
	}
}

//check to see if any category has a single.php asigned to it and use that over the other
add_filter('single_template', create_function('$t', 'foreach( (array) get_the_category() as $cat ) { if ( file_exists(TEMPLATEPATH . "/single-{$cat->term_id}.php") ) return TEMPLATEPATH . "/single-{$cat->term_id}.php"; } return $t;' ));


//  give every  xyz  css class a different value 
function alternating_css_class($counter,$number,$css_class_string){

	if(($counter % $number) == 0){
		$the_div_class = $css_class_string;
	}
	else {
		$the_div_class = NULL;
	}
return $the_div_class; 
}

function insert_clearfix($counter,$number,$clearing_element){
	$counter++;
	
	if((($counter % $number) == 0)&&($number < $counter)){
		$clear_output = $clearing_element;
	}
	else {
		$clear_output = NULL;
	}
return $clear_output; 
}

// conditional if page belongs in the tree
function is_tree($pid) {    // $pid = The page we're looking for pages underneath
	global $post;       // We load this as we're outside of the post
	if(is_page()&&($post->post_parent==$pid || is_page($pid) || $post->ancestors[0]==$pid || $post->ancestors[1]==$pid)) return true; // Yes, it's in the tree
	else return false;  // No, it's outside
};


// get the category slug when given an ID
function get_cat_slug($cat_id) {
	$cat_id = (int) $cat_id;
	$category = &get_category($cat_id);
	return $category->slug;
}



// get the cat_ID of the parent category
function get_parent_cat_id()
{
		foreach (get_the_category() as $cat) {
		  $parent 		= get_category($cat->category_parent);  
		  $parent_ID	= $parent->cat_ID;
		}

return $parent_ID;
}

// Get the slug, name, term_id or allData of the Root Category. This will check parents, grandparents, etc.. All the way up!
function NWS_get_root_category($cat,$option='slug'){

	$result = NULL;
	if(is_page()){
		$parentCatList 		= get_category_parents($cat,false,',');	
		$parentCatListArray = split(",",$parentCatList);
		$topParentName 		= $parentCatListArray[0];
		$topParentID 		= get_cat_ID( $topParentName );
		$topParent 			= get_category( $topParentID );
		$topParentSlug      = $topParent->slug;
		
		if($option == 'name'){
			$result = $topParentName;
		}elseif($option == 'term_id'){
			$result = $topParentID;
		}elseif($option == 'allData'){
			$result = $topParent;
		}else{
			$result = $topParentSlug;
		}
	}
return $result;	
}


// find the top category parent when on a single post and return it's ID when found
function get_post_top_parent(){
		
		$this_category  = get_category(get_parent_cat_id());
		
		#var_dump($this_category);
		
		
		$parent_cat 	= $this_category->category_parent;	
		//when first level category  return it's ID
		if($parent_cat == NULL){
			$catsy 		= get_the_category();
			$parent_cat = $catsy[0]->cat_ID;
		}
return $parent_cat;
}

//http://www.devdevote.com/wordpress/functions/get_depth/
function get_depth($id = '', $depth = '', $i = 0) {
	global $wpdb;

	if($depth == '') {
		
		if(is_category()) {

			if($id == '') {
				global $cat;
				$id = $cat;
			}
			$depth = $wpdb->get_var("SELECT parent FROM $wpdb->term_taxonomy WHERE term_id = '".$id."'");
			return get_depth($id, $depth, $i);
		}
		elseif(is_single()) {
			if($id == '') {
				$category = get_the_category();
				$id = $category[0]->cat_ID;
			}
			$depth = $wpdb->get_var("SELECT parent FROM $wpdb->term_taxonomy WHERE term_id = '".$id."'");
			return get_depth($id, $depth, $i);
		}
	}
	elseif($depth == '0') {
		return $i;
	}
	elseif(is_single() || is_category()) {
		$depth = $wpdb->get_var("SELECT parent FROM $wpdb->term_taxonomy WHERE term_id = '".$depth."'");
		$i++;
		return get_depth($id, $depth, $i);
	}
}

// get any category id by it's slug
if (!function_exists('any_cat')) {
	function any_cat($cat = 'some-category'){
	
	global $wpdb;
	
	$query 				= " SELECT 
						    * 
						   FROM 
						    $wpdb->terms 
						   WHERE 
						    slug = '$cat' 
						   LIMIT 0 , 1";

	$result    			= mysql_query($query);
	$row    			= mysql_fetch_assoc($result);
	$id_any_cat  		= $row[term_id];

	return $id_any_cat;
	}
}

// get the e.g. shop cat id 
if(!function_exists('shop_cat')) {
	function shop_cat($cat = 'shop'){
	
	global $wpdb;
	
	$query 				= " SELECT 
						    * 
						   FROM 
						    $wpdb->terms 
						   WHERE 
						    slug = '$cat' 
						   LIMIT 0 , 1";

	$result    			= mysql_query($query);
	$row    			= mysql_fetch_assoc($result);
	$id_shop_cat  	= $row[term_id];

	return $id_shop_cat;
	}
}



// get the featured category id
if (!function_exists('featured_cat')) {
	function featured_cat($cat = 'gallery'){
	
	global $wpdb;
	
	$query 				= " SELECT 
						    * 
						   FROM 
						    $wpdb->terms 
						   WHERE 
						    slug = '$cat' 
						   LIMIT 0 , 1";

	$result    			= mysql_query($query);
	$row    			= mysql_fetch_assoc($result);
	$id_featured_cat  	= $row[term_id];

	return $id_featured_cat;
	}
}

// blog category related posts
function NWS_blogCat_related_posts($showposts){
	global $post;
	// prepare the query
	$categories 	= get_the_category($post->ID);
	$category_ids 	= array();
	foreach($categories as $individual_category){
		$category_ids[] = $individual_category->term_id;
	}
		
	$param		= array(
		'category__in' 		=> $category_ids,
		'post__not_in' 		=> array($post->ID),
		'showposts'			=> $showposts, 
		'caller_get_posts'	=> 1
	);
		
	// query	
	$my_catRelated_query = new wp_query($param); 		
		
	// run the loop 	
	if($my_catRelated_query->have_posts()) 
	{ 			
		$result 		= array();
		$result[status] = TRUE;
		$result[html] 	= NULL;
		
		while ($my_catRelated_query->have_posts()) 
		{ 	
			$my_catRelated_query->the_post(); 
			$permalink 		= get_permalink();
			$title_attr		= str_replace("%s",the_title_attribute('echo=0'), __('Permalink to %s', 'wpShop'));
			$title			= get_the_title();
			$result[html]	.= "<li><a href='$permalink' title='$title_attr'>$title</a></li> ";
		} wp_reset_query();
	}
	else {$result[status] = FALSE;}
return $result;
}

// Tag related posts
function NWS_blogTag_related_posts($showposts,$option = 1){ 

	global $wpdb, $post;

	$tags = wp_get_post_tags($post->ID);
	
	if ($tags) {
		$tag_ids = array();
		foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;

		$args = array(
			'tag__in' 			=> $tag_ids,
			'post__not_in' 		=> array($post->ID),
			'showposts'			=> $showposts,
			'caller_get_posts'	=> 1
		);
		
		// query	
		$my_tagRelated_query = new wp_query($args); 		
			
		// run the loop 	
		if($my_tagRelated_query->have_posts()) 
		{ 			
			$result 		= array();
			$result[status] = TRUE;
			$result[html] 	= NULL;
			
			while ($my_tagRelated_query->have_posts()) 
			{ 	
				$my_tagRelated_query->the_post(); 
				$permalink 		= get_permalink();
				$title_attr		= str_replace("%s",the_title_attribute('echo=0'), __('Permalink to %s', 'wpShop'));
				$title			= get_the_title();
				$result[html]	.= "<li><a href='$permalink' title='$title_attr'>$title</a></li> ";
			} wp_reset_query();
		}
		else {$result[status] = FALSE;}
		
	return $result;
	}
}

// category related posts
function NWS_cat_related_posts($showposts,$order_by,$order,$resizedImg_src,$img_width=81){

	global $post,$OPTION;
	
	// prepare the query
	$categories 	= get_the_category($post->ID);
	$category_ids 	= array();
	foreach($categories as $individual_category){
			$category_ids[] = $individual_category->term_id;
	}
		
		$param		= array(
			'category__in' 		=> $category_ids,
			'post__not_in' 		=> array($post->ID),
			'showposts'			=> $showposts, 
			'orderby' 			=> $order_by,
			'order' 			=> $order,
			'caller_get_posts'	=> 1
		);
		
	// query	
	$my_catRelated_query = new wp_query($param); 		
		
	// run the loop 	
	if($my_catRelated_query->have_posts()) 
	{ 			
		$result 		= array();
		$result[status] = TRUE;
		$result[html] 	= NULL;
		
		while ($my_catRelated_query->have_posts()) 
		{ 	
			$my_catRelated_query->the_post(); 
			
			$output 		= my_attachment_images(0,1);
			$imgNum 		= count($output);
			//do we have 1 attached image?
			if($imgNum != 0){
				$imgURL		= array();
				foreach($output as $v){
					$img_src 	= $v;
					$des_src 	= $resizedImg_src;							
					$img_file 	= mkthumb($img_src,$des_src,$img_width,'width');    
					$imgURL[] 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;				
				}
			// no attachments? pull image from custom field
			} elseif(strlen(get_custom_field('image_thumb', FALSE))>0) { 
				$img_src 	= get_custom_field('image_thumb', FALSE);
				$des_src 	= $resizedImg_src;							
				$img_file 	= mkthumb($img_src,$des_src,$img_width,'width');      
				$imgURL 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;
			}	
			
			// put output together
			
			$permalink 		= get_permalink();
			$title_attr2	= the_title_attribute('echo=0');
			$title_attr		= str_replace("%s",the_title_attribute('echo=0'), __('Permalink to %s', 'wpShop'));
			
			// for attached images
			if($imgNum != 0){ 

				$result[html]	.= "
					<a href='$permalink' rel='bookmark' title='$title_attr'><img src='$imgURL[0]' alt='$title_attr2'/></a>";	
					
			// for  image from custom field
			} elseif(strlen(get_custom_field('image_thumb', FALSE))>0) { 
				$result[html]	.= "
					<a href='$permalink' rel='bookmark' title='$title_attr'><img src='$imgURL' alt='$title_attr2'/></a>";
				
			} else { 									
				$err_message 	= __('Oops! No Product Images were found.','wpShop');									
				$result[html] 	= "<a href='$permalink' rel='bookmark' title='$title_attr'>$err_message</a>";
			} 
		} wp_reset_query();
	}
	else {$result[status] = FALSE;}


return $result;
}	


//change.9.9
function NWS_create_taglist($id,$taxTerm){

	$tags 	= wp_get_post_terms($id,$taxTerm);
	
	$taglist = NULL;
	foreach($tags as $v){
		$taglist .= "'" . $v->term_id . "',";
	}
	$taglist = substr($taglist,0,-1);

return $taglist;
}
//\change.9.9


//change.9.9
function NWS_prepare_tag_related_DB_query($taxTerm,$taglist,$curPostId,$showposts = 4){

	global $wpdb;

	$qStr 	= "SELECT DISTINCT p.ID, p.post_title, p.post_date, p.comment_count, count(t_r.object_id) as cnt FROM 
				$wpdb->term_taxonomy t_t,
				$wpdb->term_relationships t_r, 
				$wpdb->posts p
			WHERE 
				t_t.taxonomy='$taxTerm' 
			AND 
				t_t.term_taxonomy_id = t_r.term_taxonomy_id 
			AND 
				t_r.object_id  = p.ID 
			AND 
				(t_t.term_id IN ($taglist)) 
			AND 
				p.ID != $curPostId 
			AND 
				p.post_type = 'post' 
			AND 
				p.post_status = 'publish' 
			AND 
				p.inventory > 0	
			GROUP BY 
				t_r.object_id 
			ORDER BY 
				cnt DESC, p.post_date_gmt DESC LIMIT 0, $showposts";

return $qStr;
}
//\change.9.9



// Tag/Custom Term related posts
//change.9.9
function NWS_tag_related_posts($showposts,$resizedImg_src,$img_width=81,$taxTerm='post_tag',$option = 1){ 

	global $wpdb,$post,$OPTION;

	// get tags of this current post
	$prodID = $post->ID;	
	$taglist = NWS_create_taglist($prodID,$taxTerm);
	
	//which posts are based on the tags most related?
	// prepare db query
	$q = NWS_prepare_tag_related_DB_query($taxTerm,$taglist,$prodID,$showposts);
	
	if($option == 1){
		
		$related_posts = $wpdb->get_results($q);
		
		if(!empty($related_posts))
		{	
				$result 		= array();
				$result['status'] = TRUE;
				$result['html'] 	= NULL;
			
				foreach ($related_posts as $related_post){
					$title_attr2 	= get_the_title($related_post->ID);
					$title_attr		= str_replace("%s",strip_tags($title_attr2), __('Permalink to %s', 'wpShop'));
					$output 		= my_attachment_images($related_post->ID,1);
					$imgNum 		= count($output);
					
					//do we have 1 attached image?
					if($imgNum != 0){
						$imgURL		= array();
						foreach($output as $v){
							$img_src 	= $v;
							$des_src 	= $resizedImg_src;							
							$img_file 	= mkthumb($img_src,$des_src,$img_width,'width');    
							$imgURL[] 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;				
					}
					// no attachments? pull image from custom field
					} elseif(strlen(get_custom_field2($related_post->ID,'image_thumb', FALSE))>0) { 
						$img_src 	= get_custom_field2($related_post->ID,'image_thumb', FALSE);
						$des_src 	= $resizedImg_src;							
						$img_file 	= mkthumb($img_src,$des_src,$img_width,'width');      
						$imgURL 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;
					}	
					
					// put output together
					$permalink 	= get_permalink($related_post->ID);
					
					// for attached images
					if($imgNum != 0){ 

						$result['html']	.= "
							<a href='$permalink' rel='bookmark' title='$title_attr'><img src='$imgURL[0]' alt='$title_attr2'/></a>";	
							
					// for  image from custom field
					} elseif(strlen(get_custom_field2($related_post->ID,'image_thumb', FALSE))>0) {
						$result['html']	.= "
							<a href='$permalink' rel='bookmark' title='$title_attr'><img src='$imgURL' alt='$title_attr2'/></a>";
						
					} else { 									
						$err_message 	= __('Oops! No Product Images were found.','wpShop');									
						$result['html']	= "<a href='$permalink' rel='bookmark' title='$title_attr'>$err_message</a>";
					}
					
				}
		}else {$result['status'] = FALSE;}
	}
	
return $result;
}
//\change.9.9

//change.9.9
// Shopping Cart Tag/Custom Term related posts
function NWS_cart_tag_related_posts($prodID,$showposts,$resizedImg_src,$img_width=81,$taxTerm='post_tag',$option = 1){ 

	global $wpdb,$OPTION;
		
	// get tags of this current post	
	$taglist = NWS_create_taglist($post->ID,$taxTerm);
	
	//which posts are based on the tags most related?
	// prepare db query
	$q = NWS_prepare_tag_related_DB_query($taxTerm,$taglist,$post->ID,$showposts);	
	
	if($option == 1){
		
		$related_posts = $wpdb->get_results($q);
		
		
		if(!empty($related_posts))
		{	
				$result 			= array();
				$result['status'] 	= TRUE;
				$result['html']		= NULL;
				$result['IDs'] 		= NULL;
				
				foreach ($related_posts as $related_post){
					$r_postID[] 		= $related_post->ID;
					
					//for the IDs output
					//for the html output
					$title_attr2 	= get_the_title($related_post->ID);
					$title_attr		= str_replace("%s",strip_tags($title_attr2), __('Permalink to %s', 'wpShop'));
					$output 		= my_attachment_images($related_post->ID,1);
					$imgNum 		= count($output);
					
					//do we have 1 attached image?
					if($imgNum != 0){
						$imgURL		= array();
						foreach($output as $v){
							$img_src 	= $v;
							$des_src 	= $resizedImg_src;							
							$img_file 	= mkthumb($img_src,$des_src,$img_width,'width');    
							$imgURL[] 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;				
						}
					// no attachments? pull image from custom field
					} elseif(strlen(get_custom_field2($related_post->ID,'image_thumb', FALSE))>0) { 
						$img_src 	= get_custom_field2($related_post->ID,'image_thumb', FALSE);
						$des_src 	= $resizedImg_src;							
						$img_file 	= mkthumb($img_src,$des_src,$img_width,'width');      
						$imgURL 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;
					}	
					
					// put output together
					$permalink 	= get_permalink($related_post->ID);
					
					// for attached images
					if($imgNum != 0){ 

						$result['html']	.= "
							<a href='$permalink' rel='bookmark' title='$title_attr'><img src='$imgURL[0]' alt='$title_attr2'/></a>";	
							
					// for  image from custom field
					} elseif(strlen(get_custom_field2($related_post->ID,'image_thumb', FALSE))>0) { 
						$result['html']	.= "
							<a href='$permalink' rel='bookmark' title='$title_attr'><img src='$imgURL' alt='$title_attr2'/></a>";
						
					} else { 									
						$err_message 	= __('Oops! No Product Images were found.','wpShop');									
						$result['html'] 	= "<a href='$permalink' rel='bookmark' title='$title_attr'>$err_message</a>";
					}
					
				}
				$result['IDs'] = $r_postID;
				
		}else {$result['status'] = FALSE;}
	}
	
return $result;
}
//\change.9.9

// adjacent post image
function NWS_adjacentProd($adjacentPost,$resizedImg_src,$img_width=81){

	global $post,$OPTION;
	$adjacent_post = $adjacentPost;
	
	if(($adjacent_post!='') || ($adjacent_post!=NULL)) 
	{ 			
		$result 		= array();
		$result[status] = TRUE;
		$result[html] 	= NULL;
		
		#my_attachment_data($postid=0,$numberposts = -1,$post_mime_type = 'image',$orderby = 'menu_order ID',$order = 'ASC')
		//let's collect our attachments- we just need one so we declare the parameters
		$data		= my_attachment_data($adjacent_post->ID,1);
		//count them
		$num 		= count($data);
		
		//do we have 1 attached image?
		if($num != 0){
			for($i=0,$a=1;$i<$num;$i++,$a++){
				$img_src 	= $data[$i]['guid'];
				$des_src 	= $resizedImg_src;	
				$img_file 	= mkthumb($img_src,$des_src,$img_width,'width');    
				$imgURL[] 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;	
			} 
		// no attachments? pull image from custom field
		} elseif(strlen(get_custom_field2($adjacent_post->ID,'image_thumb', FALSE))>0) { 
			$img_src 	= get_custom_field2($adjacent_post->ID,'image_thumb', FALSE);
			$des_src 	= $resizedImg_src;							
			$img_file 	= mkthumb($img_src,$des_src,$img_width,'width');      
			$imgURL 	= get_option('siteurl').'/'.$des_src.'/'.$img_file;
		}	
		
		// put output together
		$permalink 		= get_permalink($adjacent_post->ID);
		$title_attr2	= $adjacent_post->post_title;
		$title_attr		= str_replace("%s",$adjacent_post->post_title, __('Permalink to %s', 'wpShop'));
		
		// for 1 attached image
		if($num != 0){ 
			$result[html]	.= "
				<a class='adjacentImg' href='$permalink' rel='bookmark' title='$title_attr'><img src='$imgURL[0]' alt='$title_attr2'/></a>";
				
		// for image from custom field
		} elseif(strlen(get_custom_field2($adjacent_post->ID,'image_thumb', FALSE))>0) { 
			$result[html]	.= "
				<a class='adjacentImg' href='$permalink' rel='bookmark' title='$title_attr'><img src='$imgURL' alt='$title_attr2'/></a>";
		
		} else { 									
			$err_message 	= __('Oops! No Product Images were found.','wpShop');									
			$result[html] 	= "<a class='adjacentImg' href='$permalink' rel='bookmark' title='$title_attr'>$err_message</a>";
		}
	}
	else {$result[status] = FALSE;}


return $result;
}

// for the "Previous" and "Next" products on the single product page
#
# 
# was not sure how to use filters for this so... (if you know a better way to do this please advice: sarah@neuber-web-solutions.de)
#
//
function NWS_adjacent_post_link($format, $link, $in_same_cat = false, $excluded_categories = '', $previous = true) {
	
	global $OPTION;
	
	if ( $previous && is_attachment() )
	  $post = & get_post($GLOBALS['post']->post_parent);
	else
	  $post = get_adjacent_post($in_same_cat, $excluded_categories, $previous);

	if ( !$post )
	  return;

	$title = $post->post_title;

	if ( empty($post->post_title) )
	  $title = $previous ? __('Previous Post','wpShop') : __('Next Post','wpShop');

	$title = apply_filters('the_title', $title, $post);
	$date = mysql2date($OPTION['date_format'], $post->post_date);
	// for the ID
	$NWS_ID = $previous ? 'previousProd' : 'nextProd';
	//for the title
	$NWS_title = $previous ? __($OPTION['wps_prevProdLinkText'].' in this Category','wpShop') : __($OPTION['wps_nextProdLinkText'].' in this Category','wpShop');

	$string = '<a class="'.$NWS_ID.'" href="'.get_permalink($post).'" title="'.$NWS_title.'">';
	$link = str_replace('%title', $title, $link);
	$link = str_replace('%date', $date, $link);
	$link = $string . $link . '</a>';

	$format = str_replace('%link', $link, $format);

	$adjacent = $previous ? 'previous' : 'next';
	echo apply_filters( "{$adjacent}_post_link", $format, $link );
}

function NWS_previous_post_link($format='&laquo; %link', $link='%title', $in_same_cat = false, $excluded_categories = '') {
	NWS_adjacent_post_link($format, $link, $in_same_cat, $excluded_categories, true);
	
}

function NWS_next_post_link($format='%link &raquo;', $link='%title', $in_same_cat = false, $excluded_categories = '') {
    NWS_adjacent_post_link($format, $link, $in_same_cat, $excluded_categories, false);
	
}



##################################################################################################################################
// 											IMAGES
##################################################################################################################################
function get_upload_img_url($img){

	$parts 		= explode("http://",$img);													
	$findme 	= array('.gif','.jpg','.jpeg','.png');
	$picSuffix	= NULL;
													
		foreach($findme as $v){
			$pos 	= strpos($parts[1], $v);
				if ($pos !== false) {
					$picSuffix = $v; 
			} 
		}
																				
		$parts2 = explode("$picSuffix",$parts[1]);
		$imgURL = 'http://'.$parts2[0].$picSuffix;

return $imgURL;
}

function get_upload_img_type($imgURL){

	$picinfo = @getimagesize($imgURL);
				
	// what case is it 										
	if($picinfo[0] > $picinfo[1]){
		$picType = 'landscape';
	}
	elseif($picinfo[0] < $picinfo[1]){
		$picType = 'portrait';
	}
	elseif($picinfo[0] == $picinfo[1]){
		$picType = 'square';
	}
	else{}	


return $picType;
}


function mkthumb($img_src,$des_src,$img_dimension=120,$option='height')    	
{
	
	//find out if /wp-content/uploads/ is contained in URL, if not its a remote URL
	$isRemote = (strstr($img_src,'/wp-content/uploads/') !== FALSE ? FALSE : TRUE);
	
	// however, nice if someone wants to add a remote img resource, but this will only work 
	//if allow_url_fopen is enabled (CURL not implemented)
	if($isRemote){
		if(ini_get('allow_url_fopen') != '1'){	//warning if fopen is not enabled
			echo "<b style='color:red;'>";
			_e('You try to use a remote image but the ini-setting allow_url_fopen is disabled. Try to enable it or use a local picture.','wpShop');
			echo "</b>";
		}
	}
		
		
	//find name of img_file + create thumb path
	$img_file_alone = substr(strrchr($img_src,"/"),1);
	
	// append the dimension
	$img_file 		= substr($img_file_alone,0,strripos($img_file_alone,".")).'_'.$img_dimension.strrchr($img_file_alone,".");
	// get the destination path	
	$des_src 		= ($des_src[0] == "/" ? substr($des_src,1) : $des_src);
	// look for the resized image file	
	$thumb_path		= substr(WP_CONTENT_DIR,0,-10) . $des_src . '/' . $img_file;
	
	// file_exists chaches results, needs to be cleared first
	clearstatcache(); 
			
	// thumbnail creation - but only if not yet existing
	if(!file_exists($thumb_path)) 
	{

		$absolute_img_src = $img_src;	//must be a thing from the past 
		
		// before getting getting sizes check of resource really exists
		switch($isRemote){
			case TRUE : 
				if(fopen($absolute_img_src,'r') !== FALSE){	
					if(getimagesize($absolute_img_src) === FALSE){
						$img_file = 'error';
					}
				}			
				else {$img_file = 'error';}
			break;
			
			case FALSE :
			
				clearstatcache();
	
				$absolute_img_src = WP_CONTENT_DIR.'/uploads/'.$img_file_alone;
			
				if(file_exists($absolute_img_src)){
					if(getimagesize($absolute_img_src) === FALSE){
						$img_file = 'error';
					}
				}		
				else {$img_file = 'error';}					
			break;
		}

		// find sizes + type
		if($img_file != 'error'){
							
		   list($src_width,$src_height,$src_typ) = getimagesize($absolute_img_src);

		   
			// give them new (thumb) sizes
			switch($option){
			
				case 'height':
				$ratio 				= ($src_height > $img_dimension ? $src_height / $img_dimension : 1);
				$new_image_width 	= round(($src_width / $ratio),0); 
				$new_image_height	= round(($src_height / $ratio),0); 
				break;
				
				case 'width':
				$ratio 				= ($src_width > $img_dimension ? $src_width / $img_dimension : 1);
				$new_image_height 	= round(($src_height / $ratio),0); 
				$new_image_width	= round(($src_width / $ratio),0); 			
				break;
			}
			

			if($src_typ == 1)     // GIF
			{
				$image 		= imagecreatefromgif($absolute_img_src);
				$new_image 	= imagecreate($new_image_width, $new_image_height);
				imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_image_width,$new_image_height, $src_width, $src_height);
			  
				imagegif($new_image,$thumb_path, 100);
				imagedestroy($image);
				imagedestroy($new_image);
				
			}
			elseif($src_typ == 2) // JPG
			{
				$image 		= imagecreatefromjpeg($absolute_img_src);
				$new_image 	= imagecreatetruecolor($new_image_width, $new_image_height);
				imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_image_width,$new_image_height, $src_width, $src_height);
			  
				imagejpeg($new_image,$thumb_path, 100);
				imagedestroy($image);
				imagedestroy($new_image);
				
			}
			elseif($src_typ == 3) // PNG
			{
				$image 		= imagecreatefrompng($absolute_img_src);
				$new_image 	= imagecreatetruecolor($new_image_width, $new_image_height);
				imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_image_width,$new_image_height, $src_width, $src_height);
				
				imagepng($new_image,$thumb_path);
				imagedestroy($image);
				imagedestroy($new_image);
			
			}
			else
			{
			  $img_file = 'error';
			}			
		}			
	} 
		
return $img_file;
}


function filter_img_from_descr($searchPattern,$postContent){

	// we remove the images from the content
	$szDescription = preg_replace($searchPattern, '' ,$postContent);

	// Apply filters for correct content display
	$szDescription = apply_filters('the_content', $szDescription);

	// Echo the Content
	#echo $szDescription;
return $szDescription;
}


function my_attachment_image($postid=0, $size='thumbnail', $attributes='',$option='echo'){

	if ($postid<1){ $postid = get_the_ID(); }
	
	if ($images = get_children(array(
		'post_parent' => $postid,
		'post_type' => 'attachment',
		'order' => 'ASC', 
		'orderby' => 'menu_order ID',
		'numberposts' => 1,
		'post_mime_type' => 'image',))){
		
		foreach($images as $image) {
		
			$output 			= array();
		
			$attachment 		= wp_get_attachment_image_src($image->ID, $size);
			
			//SSL active? Use https:// for the image path
			if(($_SERVER['HTTPS'] == 'on')||($_SERVER['HTTPS'] == '1') || ($_SERVER['SSL'] == '1')){
				$img_parts = explode('http',$attachment[0]);
				$attachment[0] = 'https'.$img_parts[1];
			}
			
			// get together the $output[css_class]
			$parts 				= explode("/wp-content/uploads/",$attachment[0]);	
			$relPath 			= WP_CONTENT_DIR .'/uploads/'.$parts[1];
			$output[css_class] 	= get_upload_img_type($relPath);
			
			if($option == 'echo'){
				echo "<img class='img_{$output[css_class]}' src='$attachment[0]'  $attributes />";
			}
			elseif($option == 'return'){
				
				$output[img_path] 	= $attachment[0];
				$output[attr] 		= $attributes;
			
			}
			else {}
		}
	}
return $output;
}


//best used in WordPress loop 
function my_attachment_images($postid=0,$numberposts = -1){

	if ($postid<1){ $postid = get_the_ID(); }
	
	$images = get_children(array(
		'post_parent' => $postid,
		'post_type' => 'attachment',
		'order' => 'ASC', 
		'orderby' => 'menu_order ID',
		'numberposts' => $numberposts,
		'post_mime_type' => 'image',
	));
	
	$output = array();
	if (!empty($images)){
		foreach($images as $image){	
			$output[] = $image->guid;
		}
	}
return $output;
}


function my_attachment_data($postid=0,$numberposts = -1,$post_mime_type = 'image',$orderby = 'menu_order ID',$order = 'ASC'){

	if ($postid<1){ $postid = get_the_ID(); }
	
	$data = get_children(array(
		'post_parent' 		=> $postid,
		'post_type' 		=> 'attachment',
		'order' 			=> $order, 
		'orderby' 			=> $orderby,
		'numberposts' 		=> $numberposts,
		'post_mime_type' 	=> $post_mime_type,
	));
	
	$output = array();
	
	$i = 0;
	
	if (!empty($data)){
	
		foreach($data as $v){	
		
			foreach($v as $a => $d){
				$output[$i][$a] = $d;
			}
		$i++;
		}
			
	}
		
return $output;
}

function nws_get_item_thumb($post_id) {
	$item_thumb_id = get_post_thumbnail_id($post_id);
	if (!$item_thumb_id) {
		$iattaches = my_attachment_images($post_id, 1);
		if ($iattaches) {
			$item_thumb_id = $iattaches[0];
		}
	}
	return $item_thumb_id;
}

function product_price($product_id, $asnmb = false) {
	$currency_rate = $_SESSION['currency-rate'];
	if (!$currency_rate) { $currency_rate = 1; }
	$price = get_post_meta($product_id, 'price', true);
	$new_price = get_post_meta($product_id, 'new_price', true);
	if($new_price) {
		$price = $new_price;
	}
	if ($asnmb) {
		return $price * $currency_rate;
	}
	return format_price($price * $currency_rate, true);
}

function shop_get_currency_codes() {
	global $currency_options, $currency_codes;
	$ccodes = array();
	if (count($currency_options)) {
		foreach($currency_options as $actc) {
			$ccodes[$actc] = $currency_codes[$actc];
		}
	}
	return $ccodes;
}

function product_prices_list($price) {
	global $currency_options, $currency_rates;
	if ($price) { ?>
		<span class="currency-price price-USD">$<?php echo format_price($price); ?></span>
		<?php if (count($currency_options)) {
			foreach($currency_options as $actc) { ?>
				<span class="currency-price price-<?php echo strtoupper($actc); ?>"><?php echo format_price($price * $currency_rates[$actc]); ?> <?php echo strtoupper($actc); ?></span>
		<?php
			}
		}
	}
}

function get_limit_content($content, $max_char, $hard = false, $end = '...') {
	if (strlen($content) > $max_char) {
		if ($hard) {
			$content = substr($content, 0, $max_char).$end;
		} else if ($espacio = strpos($content, " ", $max_char)) {
			$content = substr($content, 0, $espacio).$end;
		}
	}
	return $content;
}

// PRODUCTS SORT
function product_sort_filters() {
	add_filter('posts_fields', 'product_sort_filter_fields');
	add_filter('posts_join', 'product_sort_filter_join');
	add_filter('posts_orderby', 'product_sort_filter_orderby');
}

function product_sort_filter_fields($fields) {
	global $wpdb;

	$psort = $_GET['psort'];
	if(isset($_SESSION['psort']) && $_SESSION['psort'] != "")
	{
		$psort = $_SESSION['psort'];
	}	

	$fields .= ", IF(wp_posts.inventory > 0, 1, 0) as invsort";
	if ($psort == 'pricelow' || $psort == 'pricehigh') {
		$fields .= ", IF((pm2.meta_value+0) > 0, pm2.meta_value+0, pm.meta_value+0) as price";
	}
	return $fields;
}

function product_sort_filter_join($join) {
	global $wpdb;
	$psort = $_GET['psort'];
	if(isset($_SESSION['psort']) && $_SESSION['psort'] != "")
	{
		$psort = $_SESSION['psort'];
	}
	if ($psort == 'pricelow' || $psort == 'pricehigh') {
		$join .= sprintf(" LEFT JOIN %spostmeta pm ON pm.post_id = %sposts.ID AND pm.meta_key = 'price'", $wpdb->prefix, $wpdb->prefix);
		$join .= sprintf(" LEFT JOIN %spostmeta pm2 ON pm2.post_id = %sposts.ID AND pm2.meta_key = 'new_price'", $wpdb->prefix, $wpdb->prefix);
	}
	return $join;
}

function product_sort_filter_orderby($order) {
	global $wpdb;
	$psort = $_GET['psort'];
	if(isset($_SESSION['psort']) && $_SESSION['psort'] != "")
	{
		$psort = $_SESSION['psort'];
	}
	$order = " invsort DESC";
	if ($psort == 'pricelow') {
		$order .= ", price ASC ";
	} else if ($psort == 'pricehigh') {
		$order .= ", price DESC ";
	} else if ($psort == 'oldest') {
		$order .= ", wp_posts.post_date ASC ";
	} else if (is_category('sale')) {
		$order .= ", wp_posts.post_modified DESC ";
	} else {
		$order .= ", wp_posts.post_date DESC ";
	}
	return $order;
}

function product_sort_filter_groupby($group) {
	if ($group != 'wp_posts.ID') {
		if (strlen($group)) { $group .= ', '; }
		$group .= 'wp_posts.ID';
	}
	return $group;
}

function product_sort_process($args) {
	add_filter('posts_fields', 'product_sort_filter_fields');
	add_filter('posts_join', 'product_sort_filter_join');
	add_filter('posts_orderby', 'product_sort_filter_orderby');
	add_filter('posts_groupby', 'product_sort_filter_groupby');

	/*$psort = $_GET['psort'];
	if (strlen($psort)) {
		switch ($psort) {
			case "newest":
				$args['orderby'] = 'date';
				$args['order'] = 'desc';
			break;
			case "oldest":
				$args['orderby'] = 'date';
				$args['order'] = 'asc';
			break;
			case "pricelow": // meta_value_num
				product_sort_filters();
			break;
			case "pricehigh": // meta_value_num
				product_sort_filters();
			break;
		}
	}*/
	return $args;
}

function product_sort_select() {
	global $OPTION;
	// view per page params
	$def_ppp = get_option('posts_per_page');
	
	if(strlen($_SESSION['posts_per_page']))
	{
		$ppp = $_SESSION['posts_per_page'];
	}
	$ppp_array = array($def_ppp, '30', '60', '120');

	// sort params
	$options = array(
		'newest' => 'Newest',
		'oldest' => 'Oldest',
		'pricelow' => 'Price Low to High',
		'pricehigh' => 'Price High to Low'
	);
	$psort = $_SESSION['psort'];
	if (!strlen($psort)) { $psort = 'newest'; }
?>
	<div class="view-row">
		<div class="num-view" id="products-ppp">
			<strong class="ppp-curr">View <?php echo $ppp; ?></strong>
			<ul>
				<?php foreach($ppp_array as $ppp_val) { ?>
				<li<?php if ($ppp == $ppp_val) { echo ' class="active"'; } ?>><a href="#view-<?php echo $ppp_val; ?>"><span>View <?php echo $ppp_val; ?></span></a></li>
				<?php } ?>
			</ul>
		</div>
		<form class="ppp-form">
			<?php if (is_array($_GET)) { ?>
				<?php foreach($_GET as $gpk => $gpv) { if ($gpk != 'ppp') { ?>
					<?php if (is_array($gpv)) { ?>
						<?php foreach($gpv as $gpval) { ?>
						<input type="hidden" name="<?php echo $gpk; ?>[]" value="<?php echo $gpval; ?>">
						<?php } ?>
					<?php } else { ?>
						<input type="hidden" name="<?php echo $gpk; ?>" value="<?php echo $gpv; ?>">
					<?php } ?>
				<?php }} ?>
			<?php } ?>
			<input type="hidden" name="ppp" value="" class="ppp-val">
		</form>		
	</div>
	<div class="sort-row">
		<form name="product_sort_form" class="sort-form">
			<?php if (is_array($_GET)) { ?>
				<?php foreach($_GET as $gpk => $gpv) { if ($gpk != 'psort') { ?>
					<?php if (is_array($gpv)) { ?>
						<?php foreach($gpv as $gpval) { ?>
						<input type="hidden" name="<?php echo $gpk; ?>[]" value="<?php echo $gpval; ?>">
						<?php } ?>
					<?php } else { ?>
						<input type="hidden" name="<?php echo $gpk; ?>" value="<?php echo $gpv; ?>">
					<?php } ?>
				<?php }} ?>
			<?php } ?>
			<input type="hidden" name="psort" value="<?php echo $psort; ?>" class="psort-val">
			<fieldset>
				<label>Sort by</label>
				<div class="sort-by-current"><?php echo $options[$psort]; ?></div>
				<ul class="solt-by-values">
					<?php foreach($options as $ov => $on) { ?>
					<li><a href="#<?php echo $ov; ?>"><?php echo $on; ?></a></li>
					<?php } ?>
				</ul>
			</fieldset>
		</form>
	</div>
	<div class="see-latest">

		<?php 
		global $post;
		$is_cats = array( 'all-handbags', 'all-clothes', 'all-jewelry', 'all-shoes', 'all-watches', 'all-accessories'); 
		$is_cats = array_flip($is_cats);
		?>		
		<?php if (!is_page($OPTION['wps_reserved_bags_page']) && !is_category() && !isset($is_cats[$post->post_name])) { ?><a href="<?php echo get_permalink($OPTION['wps_reserved_bags_page']); ?>" onclick="get_latest_products(); return false;" class="items-list-see-latest">See the latest products</a><?php } ?>
	</div>
<?php
}

function reserved_posts_where($where) {
	$where .= " AND wp_posts.post_date >= '" . date('Y-m-d', strtotime('-7 days')) . "'";
	return $where;
}

function get_product_thumb($prod_id, $img_size = 64) {
	global $OPTION;
	$resizedImg_src = $OPTION['upload_path'].'/cache';
	$output = my_attachment_images($prod_id,1);
	if (count($output) > 0) {
		foreach($output as $img_src) {
			$img_file = mkthumb($img_src,$resizedImg_src,$img_size,'width');
			return get_option('siteurl').'/'.$resizedImg_src.'/'.$img_file;
		}
	} else if ($image_thumb = get_post_meta($prod_id, 'image_thumb', true)) {
		$img_file = mkthumb($image_thumb,$resizedImg_src,$img_size,'width');
		return get_option('siteurl').'/'.$resizedImg_src.'/'.$img_file;
	}
}

// Ecommerce tracking code
function ga_ecommerce_tracking_code($who) {
	global $OPTION, $wpdb;
	if ($OPTION['wps_google_ecommerce_tracking'] == 1) {
		$olevels = array(3, 4, 5, 6, 7, 8, 9);
		$otable = is_dbtable_there('orders');
		$sctable = is_dbtable_there('shopping_cart');
		$order_data = $wpdb->get_row(sprintf("SELECT * FROM %s WHERE who = '%s'", $otable, $who));
		if ($order_data) {
			$order_items = $wpdb->get_results(sprintf("SELECT * FROM %s WHERE who = '%s' ORDER BY cid", $sctable, $who));
			if (in_array($order_data->level, $olevels)) {
?>
		<script type="text/javascript">
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', '<?php echo $OPTION['wps_google_analytics_id']; ?>']);
		  _gaq.push(['_trackPageview']);
		  _gaq.push(['_addTrans',
			'<?php echo $order_data->txn_id; ?>',
			'The Luxury Closet',
			'<?php echo $order_data->amount; ?>',
			'<?php echo $order_data->tax; ?>',
			'<?php echo $order_data->shipping_fee; ?>',
			'<?php echo $order_data->town; ?>',
			'<?php echo $order_data->state; ?>',
			'<?php echo $order_data->country; ?>'
		  ]);
		  <?php if ($order_items) {
		  foreach ($order_items as $order_item) {
			$post_categories = wp_get_post_categories($order_item->postID, array('fields' => 'all'));
			$cats = array();
			if ($post_categories) { foreach($post_categories as $post_category) { $cats[] = $post_category->name; } }
		  ?>
		  _gaq.push(['_addItem',
			'<?php echo $order_data->txn_id; ?>',
			'<?php echo $order_item->item_id; ?>',
			'<?php echo $order_item->item_name; ?>',
			'<?php echo implode(", ", $cats); ?>',
			'<?php echo $order_item->item_price; ?>',
			'<?php echo $order_item->item_amount; ?>'
		  ]);
		  <?php }} ?>
		  _gaq.push(['_trackTrans']);

		  (function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		</script>

		<!-- Google Code for Sale Conversion Page -->
		<script type="text/javascript">
		/* <![CDATA[ */
		var google_conversion_id = 956545849;
		var google_conversion_language = "en";
		var google_conversion_format = "3";
		var google_conversion_color = "ffffff";
		var google_conversion_label = "3MiiCK_HjgUQufaOyAM";
		var google_conversion_value = <?php echo $order_data->amount; ?>;
		var google_remarketing_only = false;
		/* ]]> */
		</script>
		<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
		<noscript><div style="display:inline;"><img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/956545849/?value=0&amp;label=3MiiCK_HjgUQufaOyAM&amp;guid=ON&amp;script=0"/></div></noscript>

		<script type="text/javascript">
		var fb_param = {};
		fb_param.pixel_id = '6009593456418';
		fb_param.value = '<?php echo sprintf("%01.2f", $order_data->amount); ?>';
		fb_param.currency = 'USD';
		(function(){
		  var fpw = document.createElement('script');
		  fpw.async = true;
		  fpw.src = '//connect.facebook.net/en_US/fp.js';
		  var ref = document.getElementsByTagName('script')[0];
		  ref.parentNode.insertBefore(fpw, ref);
		})();
		</script>
		<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/offsite_event.php?id=6009593456418&amp;value=0&amp;currency=USD" /></noscript>
<?php
			}
		}
	}
}

// INVENTORY FUNCTIONS
function get_item_inventory($post_id, $item_id = '') {
	global $wpdb;
	$item_inventory = 0;
	if (!strlen($item_id)) {
		$item_id = get_post_meta($post_id, 'ID_item', true);
		if (!strlen($item_id)) {
			$item_id = get_post_meta($post_id, 'ID_Item', true);
		}
	}
	if (strlen($item_id)) {
		$item_inventory = (int)$wpdb->get_var(sprintf("SELECT amount FROM %swps_inventory WHERE ID_item = '%s'", $wpdb->prefix, $item_id));
	}
	return $item_inventory;
}

function update_item_inventory($ID_item, $amount, $hard = false, $tp = '') {
	global $wpdb;
	$check_inv = $wpdb->get_var(sprintf("SELECT COUNT(iid) FROM %swps_inventory WHERE ID_item = '%s'", $wpdb->prefix, $ID_item));
	if ($check_inv == 0) {
		$insert = array();
		$insert['ID_item'] = $ID_item;
		$insert['amount'] = 0;
		$wpdb->insert($wpdb->prefix."wps_inventory", $insert);
	}

	$item_amount = (int)$wpdb->get_var(sprintf("SELECT amount FROM %swps_inventory WHERE ID_item = '%s'", $wpdb->prefix, $ID_item));
	if (!$hard) {
		$amount = $item_amount + $amount;
	}
	$post_id = $wpdb->get_var(sprintf("SELECT post_id FROM %spostmeta WHERE meta_key = 'ID_item' AND meta_value = '%s'", $wpdb->prefix, $ID_item));
	if ($post_id) {
		if ($amount > 1) { $amount = 1; } // item can't have more 1 stock
		// update inventory
		$wpdb->query(sprintf("UPDATE %swps_inventory SET amount = %s WHERE ID_item = '%s'", $wpdb->prefix, $amount, $ID_item));
		$wpdb->query(sprintf("UPDATE %sposts SET inventory = %s WHERE ID = %s", $wpdb->prefix, $amount, $post_id));
		// update sold out date
		if ($item_amount > 0 && $amount == 0) {
			update_post_meta($post_id, 'sold_date', time());
		} else {
			delete_post_meta($post_id, 'sold_date');
		}
		// send inventory notifications
		if ($item_amount == 0 && $amount > 0) {
			update_post_meta($post_id, 'alert_send', '1');
			inventory_notifications($ID_item);
		}
	}
}

function inventory_notifications($item_id) {
	global $wpdb, $OPTION;
	$itable = is_dbtable_there('inventory');
	$amount = $wpdb->get_var(sprintf("SELECT amount FROM %s WHERE ID_item = '%s'", $itable, $item_id));
	if ($amount > 0) {
		$post_data = $wpdb->get_row(sprintf("SELECT p.* FROM %sposts p INNER JOIN %spostmeta pm ON pm.post_id = p.ID WHERE p.post_type = 'post' AND p.post_status = 'publish' AND pm.meta_key = 'ID_item' AND pm.meta_value = '%s'", $wpdb->prefix, $wpdb->prefix, $item_id));
		if ($post_data) {
			// -----------------------------------------
			// Wishlist Notifications
			// -----------------------------------------
			$subject = stripcslashes($OPTION['wps_wishlist_notification_subject']);
			$message = stripcslashes($OPTION['wps_wishlist_notification_message']);

			$subject = str_replace('{ITEM_NAME}', $post_data->post_title, $subject);
			$message = str_replace('{ITEM_NAME}', $post_data->post_title, $message);
			$message = str_replace('{ITEM_ID}', $item_id, $message);
			$message = str_replace('{ITEM_URL}', get_permalink($post_data->ID), $message);

			// select attributes (brand, style, colour)
			$brands = array();
			$styles = array();
			$colours = array();
			$item_brands = wp_get_post_terms($post_data->ID, 'brand');
			$item_styles = wp_get_post_terms($post_data->ID, 'style');
			$item_colours = wp_get_post_terms($post_data->ID, 'colour');
			if ($item_brands) { foreach($item_brands as $item_brand) { $brands[] = $item_brand->term_id; } }
			if ($item_styles) { foreach($item_styles as $item_style) { $styles[] = $item_style->term_id; } }
			if ($item_colours) { foreach($item_colours as $item_colour) { $colours[] = $item_colour->term_id; } }

			if (count($brands) && count($styles) && count($colours)) {
				$wltable = is_dbtable_there('wishlist');
				$wl_users = $wpdb->get_results(sprintf("SELECT wl.*, u.user_email FROM %s wl LEFT JOIN %susers u ON u.ID = wl.uid WHERE wl.item_brand IN (%s) AND wl.item_style IN (%s) AND wl.item_colour IN (%s) ORDER BY wl.wid DESC", $wltable, $wpdb->prefix, implode(",", $brands), implode(",", $styles), implode(",", $colours)));
				if ($wl_users) {
					foreach($wl_users as $wl_user) {
						NWS_send_email($wl_user->user_email, $subject, $message);
					}
				}
			}
		}
	}
}


// SELLERS FUNCTIONS
function sellers_get_item_taxonomy_data($post_id, $taxonomy) {
	$post_taxs = wp_get_post_terms($post_id, $taxonomy);
	if ($post_taxs) {
		foreach($post_taxs as $post_tax) {
			return array('id' => $post_tax->term_id, 'name' => $post_tax->name);
		}
	}
}

function sellers_get_categories() {
	$sellers_categories = array();
	$cat_parents = array();
	$wps_excluded_sellers_categories = get_option('wps_excluded_sellers_categories');
	if (!is_array($wps_excluded_sellers_categories)) { $wps_excluded_sellers_categories = array(); }
	$wp_sellers_categories = get_categories('hide_empty=0&exclude='.implode(',', $wps_excluded_sellers_categories));
	foreach($wp_sellers_categories as $wp_sellers_category) {
		$sellers_categories[$wp_sellers_category->term_id] = array('name' => $wp_sellers_category->name, 'parents' => array());
		$cat_parents[$wp_sellers_category->term_id] = $wp_sellers_category->parent;
	}
	foreach($wp_sellers_categories as $wp_sellers_category) {
		if ($cat_parents[$wp_sellers_category->term_id] > 0) {
			$parents = array($cat_parents[$wp_sellers_category->term_id]);
			if ($cat_parents[$wp_sellers_category->term_id] > 0 && $cat_parents[$cat_parents[$wp_sellers_category->term_id]] > 0) {
				$parents[] = $cat_parents[$cat_parents[$wp_sellers_category->term_id]];
			}
			$sellers_categories[$wp_sellers_category->term_id]['parents'] = $parents;
		}
	}
	return $sellers_categories;
}

function sellers_get_categories_dropdown($name, $selected = '', $depth = 0) {
	$wps_excluded_sellers_categories = get_option('wps_excluded_sellers_categories');
	if (!is_array($wps_excluded_sellers_categories)) { $wps_excluded_sellers_categories = array(); }
	$wp_dropdown_categories = wp_dropdown_categories('hide_empty=0&echo=0&name='.$name.'&selected='.$selected.'&orderby=name&class=&hierarchical=1&exclude='.implode(',', $wps_excluded_sellers_categories).'&depth='.$depth.'&show_option_none=-- Select Category --');
	$wp_dropdown_categories = str_replace('<select ', '<select onchange="my_items_category_change(\''.$name.'\')" ', $wp_dropdown_categories);
	return $wp_dropdown_categories;
}

function sellers_get_root_split_categories() {
	$wps_men_bags_category = get_option("wps_men_bags_category");
	$wps_men_shoes_category = get_option("wps_men_shoes_category");
	$wps_men_watches_category = get_option("wps_men_watches_category");
	$wps_men_sunglasses_category = get_option("wps_men_sunglasses_category");
	$wps_men_jewelry_category = get_option("wps_men_jewelry_category");

	$wps_women_bags_category = get_option("wps_women_bags_category");
	$wps_women_shoes_category = get_option("wps_women_shoes_category");
	$wps_women_watches_category = get_option("wps_women_watches_category");
	$wps_women_sunglasses_category = get_option("wps_women_sunglasses_category");
	$wps_women_jewelry_category = get_option("wps_women_jewelry_category");

	$split_categories = array(
		'bags' => array($wps_men_bags_category, $wps_women_bags_category),
		'shoes' => array($wps_men_shoes_category, $wps_women_shoes_category),
		'watches' => array($wps_men_watches_category, $wps_women_watches_category),
		'sunglasses' => array($wps_men_sunglasses_category, $wps_women_sunglasses_category),
		'jewelry' => array($wps_men_jewelry_category, $wps_women_jewelry_category)
	);
	return $split_categories;
}

function sellers_get_split_categories() {
	global $wpdb;

	$split_categories = sellers_get_root_split_categories();
	foreach($split_categories as $sc_key => $sc_vals) {
		$scategories = $wpdb->get_results(sprintf("SELECT term_id FROM %sterm_taxonomy WHERE taxonomy = 'category' AND parent IN (%s)", $wpdb->prefix, implode(',', $sc_vals)));
		if ($scategories) {
			foreach($scategories as $scategory) {
				$split_categories[$sc_key][] = $scategory->term_id;
			}
		}
	}
	return $split_categories;
}

function seller_products_list_classes($cid, $cdata) {
	$clist_classes = ' seller-products-list-'.$cid;
	if (count($cdata['parents'])) {
		foreach($cdata['parents'] as $pcid) {
			$clist_classes .= ' seller-products-list-'.$pcid;
		}
	}
	return $clist_classes;
}

$seller_statuses = array(
	'pseller_draft' => 'Prof Seller Drafts',
	'pseller_pending' => 'Prof Seller Pending',
	'pseller_approved' => 'Prof Seller Approved',
	'iseller_draft' => 'Indiv Seller Draft',
	'iseller_noquote' => 'Indiv Seller No Quote',
	'iseller_pending' => 'Indiv Seller Pending',
	'iseller_approved' => 'Indiv Seller Approved',
	'iseller_pickup' => 'Indiv Seller Pickup',
	'iseller_received' => 'Indiv Seller Received',
	'seller_deleted' => 'Seller Deleted'
);

add_action('init', 'sellers_init');
function sellers_init() {
	global $wpdb, $OPTION, $seller_statuses;

	// register new post statuses
	foreach($seller_statuses as $ss_key => $ss_name) {
		register_post_status($ss_key, array(
			'label'       => _x($ss_name, 'post'),
			'protected'   => true,
			'_builtin'    => true,
			'show_in_admin_all_list' => false,
			'show_in_admin_status_list' => true,
			'label_count' => _n_noop( $ss_name.' <span class="count">(%s)</span>', $ss_name.' <span class="count">(%s)</span>' ),
		));
	}
}

// add new post statuses to edit post status drop-down
add_action('admin_footer-post.php', 'sellers_post_status_list');
function sellers_post_status_list(){
	global $post, $seller_statuses;

	if($post->post_type == 'post') {
		echo '<script>jQuery(document).ready(function($){';
		foreach($seller_statuses as $ss_key => $ss_name) {
			$sel = '';
			$label = '';
			if($post->post_status == $ss_key) {
				$sel = ' selected=\"selected\"';
				$label = '<span id=\"post-status-display\"> '.$ss_name.'</span>';
			}
			echo '
			$("select#post_status").append("<option value=\"'.$ss_key.'\"'.$sel.'>'.$ss_name.'</option>");
			$(".misc-pub-section label").append("'.$label.'");
			';
		}
		echo '});</script>';
	}
}

add_action('init', 'sellers_actions_init');
function sellers_actions_init() {
	global $wpdb, $OPTION, $current_user, $sellers_error, $seller_statuses;

	if (strlen($_POST['SellersAction'])) {
		switch ($_POST['SellersAction']) {
			case "profreseller_add_item":
				$item_category = $_POST['item_category'];
				$item_category_type = $_POST['item_category_type'];
				$item_name = trim($_POST['item_name']);
				$item_brand = $_POST['item_brand'];
				$item_desc = trim($_POST['item_desc']);
				$item_retail_price = trim($_POST['item_retail_price']);
				$item_your_price = trim($_POST['item_your_price']);
				$item_condition_desc = trim($_POST['item_condition_desc']);
				$item_selection = $_POST['item_selection'];
				$item_length = trim($_POST['item_length']);
				$item_height = trim($_POST['item_height']);
				$item_width = trim($_POST['item_width']);
				$item_handle_drop = trim($_POST['item_handle_drop']);
				$item_exterior_material = trim($_POST['item_exterior_material']);
				$item_interior_material = trim($_POST['item_interior_material']);
				$item_hardware = trim($_POST['item_hardware']);
				$item_includes = $_POST['item_includes'];
				$item_colour = $_POST['item_colour'];
				$item_style = $_POST['item_style'];
				$item_size = $_POST['item_size'];
				$item_heel_size = $_POST['item_heel_size'];
				$item_case_diameter = $_POST['item_case_diameter'];
				$item_watch_bracelet_size = $_POST['item_watch_bracelet_size'];
				$item_movement_type = $_POST['item_movement_type'];
				$item_upper_material = $_POST['item_upper_material'];
				$item_lining_material = $_POST['item_lining_material'];
				$item_sole_material = $_POST['item_sole_material'];
				$item_bracelet_material = $_POST['item_bracelet_material'];
				$item_case_material = $_POST['item_case_material'];
				$item_ring_size = $_POST['item_ring_size'];
				$item_ring_width = $_POST['item_ring_width'];
				$item_ring_height = $_POST['item_ring_height'];
				$item_necklace_length = $_POST['item_necklace_length'];
				$item_earring_width = $_POST['item_earring_width'];
				$item_earring_height = $_POST['item_earring_height'];
				$item_bracelet_size = $_POST['item_bracelet_size'];
				$item_bracelet_length = $_POST['item_bracelet_length'];
				$item_metal = $_POST['item_metal'];

				$save_draft = $_POST['save_draft'];

				if (!is_array($item_includes)) { $item_includes = array(); }

				if (!strlen($item_name)) {
					$sellers_error .= 'Item Name field is required.<br>';
				}
				if (!strlen($item_brand)) {
					$sellers_error .= 'Brand field is required.<br>';
				}
				if (!strlen($item_your_price)) {
					$sellers_error .= 'Your Price field is required.<br>';
				}
				if (!strlen($item_selection)) {
					$sellers_error .= 'Condition field is required.<br>';
				}
				if ($item_category_type == 'bags') {
					if (!strlen($item_length)) {
						$sellers_error .= 'Length field is required.<br>';
					}
					if (!strlen($item_height)) {
						$sellers_error .= 'Height field is required.<br>';
					}
					if (!strlen($item_width)) {
						$sellers_error .= 'Width field is required.<br>';
					}
				} else if ($item_category_type == 'shoes') {
					if (!strlen($item_size)) {
						$sellers_error .= 'Size field is required.<br>';
					}
				} else if ($item_category_type == 'watches') {
					if (!strlen($item_case_diameter)) {
						$sellers_error .= 'Case Diameter field is required.<br>';
					}
					if (!strlen($item_movement_type)) {
						$sellers_error .= 'Movement Type field is required.<br>';
					}
					if (!strlen($item_bracelet_material)) {
						$sellers_error .= 'Bracelet Material field is required.<br>';
					}
					if (!strlen($item_case_material)) {
						$sellers_error .= 'Case Material field is required.<br>';
					}
				}
				if ($item_category_type != 'jewelry') {
					if (!strlen($item_colour)) {
						$sellers_error .= 'Colour field is required.<br>';
					}
				}
				if (!count($item_includes)) {
					$sellers_error .= 'Includes is required.<br>';
				}
				$apfields = array();
				if (strlen($_FILES['item_picture']['name'][0])) {
					$ipic = 1;
					for($u=0; $u<count($_FILES['item_picture']['name']); $u++) {
						if (strpos($_FILES['item_picture']['type'][$u], 'image') !== false) {
							$_FILES['item_picture'.$ipic]['name'] = $_FILES['item_picture']['name'][$u];
							$_FILES['item_picture'.$ipic]['type'] = $_FILES['item_picture']['type'][$u];
							$_FILES['item_picture'.$ipic]['tmp_name'] = $_FILES['item_picture']['tmp_name'][$u];
							$_FILES['item_picture'.$ipic]['error'] = $_FILES['item_picture']['error'][$u];
							$_FILES['item_picture'.$ipic]['size'] = $_FILES['item_picture']['size'][$u];
							$apfields[] = 'item_picture'.$ipic;
							$ipic++;
						}
					}
				}
				if (!count($apfields)) {
					$sellers_error .= 'Please upload picture.<br>';
				}
				if (strlen($item_style)) { $item_category = $item_style; }
				if (is_array($item_metal)) { $item_metal = implode(';', $item_metal); }
				if ($item_includes) { $item_includes = implode("|", $item_includes); }

				if (!strlen($sellers_error)) {
					$item_status = 'pseller_pending';
					if ($save_draft == 'true') { $item_status = 'pseller_draft'; }

					$new_post = array();
					$new_post['post_title'] = $item_name;
					$new_post['post_name'] = sanitize_title($item_name);
					$new_post['post_content'] = $item_desc;
					$new_post['post_status'] = $item_status;
					$new_post['post_author'] = $current_user->ID;
					$new_post['post_category'] = array($item_category);
					$new_post['post_created'] = current_time('mysql');
					$new_post_id = wp_insert_post($new_post);

					$item_retail_price = sellers_to_usd_price($item_retail_price);
					$item_sell_price = sellers_to_usd_price($item_sell_price);
					$item_your_price = sellers_to_usd_price($item_your_price);

					$item_sell_price = sellers_get_selling_price($item_your_price);

					$item_id = sellers_assign_item_id($new_post_id, $current_user->ID, 'p');
					update_item_inventory($item_id, 1, true, 'Profseller added item');

					update_post_meta($new_post_id, 'item_seller', 'p');
					update_post_meta($new_post_id, 'price', $item_retail_price);
					update_post_meta($new_post_id, 'new_price', $item_sell_price);
					update_post_meta($new_post_id, 'item_your_price', $item_your_price);

					if ($item_condition_desc) {      update_post_meta($new_post_id, 'item_condition_desc', $item_condition_desc); }
					if ($item_length) {              update_post_meta($new_post_id, 'item_length', $item_length); }
					if ($item_height) {              update_post_meta($new_post_id, 'item_height', $item_height); }
					if ($item_width) {               update_post_meta($new_post_id, 'item_width', $item_width); }
					if ($item_handle_drop) {         update_post_meta($new_post_id, 'item_handle_drop', $item_handle_drop); }
					if ($item_exterior_material) {   update_post_meta($new_post_id, 'item_exterior_material', $item_exterior_material); }
					if ($item_interior_material) {   update_post_meta($new_post_id, 'item_interior_material', $item_interior_material); }
					if ($item_hardware) {            update_post_meta($new_post_id, 'item_hardware', $item_hardware); }
					if ($item_includes) {            update_post_meta($new_post_id, 'item_includes', $item_includes); }
					if ($item_size) {                update_post_meta($new_post_id, 'item_size', $item_size); }
					if ($item_heel_size) {           update_post_meta($new_post_id, 'item_heel_size', $item_heel_size); }
					if ($item_case_diameter) {       update_post_meta($new_post_id, 'item_case_diameter', $item_case_diameter); }
					if ($item_watch_bracelet_size) { update_post_meta($new_post_id, 'item_watch_bracelet_size', $item_watch_bracelet_size); }
					if ($item_movement_type) {       update_post_meta($new_post_id, 'item_movement_type', $item_movement_type); }
					if ($item_upper_material) {      update_post_meta($new_post_id, 'item_upper_material', $item_upper_material); }
					if ($item_lining_material) {     update_post_meta($new_post_id, 'item_lining_material', $item_lining_material); }
					if ($item_sole_material) {       update_post_meta($new_post_id, 'item_sole_material', $item_sole_material); }
					if ($item_bracelet_material) {   update_post_meta($new_post_id, 'item_bracelet_material', $item_bracelet_material); }
					if ($item_case_material) {       update_post_meta($new_post_id, 'item_case_material', $item_case_material); }
					if ($item_ring_size) {           update_post_meta($new_post_id, 'item_ring_size', $item_ring_size); }
					if ($item_ring_width) {          update_post_meta($new_post_id, 'item_ring_width', $item_ring_width); }
					if ($item_ring_height) {         update_post_meta($new_post_id, 'item_ring_height', $item_ring_height); }
					if ($item_necklace_length) {     update_post_meta($new_post_id, 'item_necklace_length', $item_necklace_length); }
					if ($item_earring_width) {       update_post_meta($new_post_id, 'item_earring_width', $item_earring_width); }
					if ($item_earring_height) {      update_post_meta($new_post_id, 'item_earring_height', $item_earring_height); }
					if ($item_bracelet_size) {       update_post_meta($new_post_id, 'item_bracelet_size', $item_bracelet_size); }
					if ($item_bracelet_length) {     update_post_meta($new_post_id, 'item_bracelet_length', $item_bracelet_length); }
					if ($item_metal) {               update_post_meta($new_post_id, 'item_metal', $item_metal); }

					sellers_set_post_to_taxonomy($new_post_id, $item_brand, 'brand');
					sellers_set_post_to_taxonomy($new_post_id, $item_selection, 'selection');
					sellers_set_post_to_taxonomy($new_post_id, $item_colour, 'colour');
					nws_update_post_prices_tax($new_post_id);

					if (count($apfields)) {
						sellers_upload_post_picture($new_post_id, $apfields);
					}

					update_utm_params('posts', $new_post_id);

					header("Location: ".get_permalink($OPTION['wps_profreseller_my_items_page']));
					exit;
				}
			break;
			case "profreseller_edit_item":
				$post_id = $_POST['post_id'];
				$item_category = $_POST['item_category'];
				$item_category_type = $_POST['item_category_type'];
				$item_name = trim($_POST['item_name']);
				$item_brand = $_POST['item_brand'];
				$item_desc = trim($_POST['item_desc']);
				$item_retail_price = trim($_POST['item_retail_price']);
				$item_your_price = trim($_POST['item_your_price']);
				$item_condition_desc = trim($_POST['item_condition_desc']);
				$item_selection = $_POST['item_selection'];
				$item_length = trim($_POST['item_length']);
				$item_height = trim($_POST['item_height']);
				$item_width = trim($_POST['item_width']);
				$item_handle_drop = trim($_POST['item_handle_drop']);
				$item_exterior_material = trim($_POST['item_exterior_material']);
				$item_interior_material = trim($_POST['item_interior_material']);
				$item_hardware = trim($_POST['item_hardware']);
				$item_includes = $_POST['item_includes'];
				$item_colour = $_POST['item_colour'];
				$item_style = $_POST['item_style'];
				$item_size = $_POST['item_size'];
				$item_heel_size = $_POST['item_heel_size'];
				$item_case_diameter = $_POST['item_case_diameter'];
				$item_watch_bracelet_size = $_POST['item_watch_bracelet_size'];
				$item_movement_type = $_POST['item_movement_type'];
				$item_upper_material = $_POST['item_upper_material'];
				$item_lining_material = $_POST['item_lining_material'];
				$item_sole_material = $_POST['item_sole_material'];
				$item_bracelet_material = $_POST['item_bracelet_material'];
				$item_case_material = $_POST['item_case_material'];
				$item_ring_size = $_POST['item_ring_size'];
				$item_ring_width = $_POST['item_ring_width'];
				$item_ring_height = $_POST['item_ring_height'];
				$item_necklace_length = $_POST['item_necklace_length'];
				$item_earring_width = $_POST['item_earring_width'];
				$item_earring_height = $_POST['item_earring_height'];
				$item_bracelet_size = $_POST['item_bracelet_size'];
				$item_bracelet_length = $_POST['item_bracelet_length'];
				$item_metal = $_POST['item_metal'];

				$save_draft = $_POST['save_draft'];

				$post_data = get_post($post_id);
				if ($post_data && $post_data->post_author == $current_user->ID) {
					if (!is_array($item_includes)) { $item_includes = array(); }

					if (!strlen($item_name)) {
						$sellers_error .= 'Item Name field is required.<br>';
					}
					if (!strlen($item_brand)) {
						$sellers_error .= 'Brand field is required.<br>';
					}
					if (!strlen($item_your_price)) {
						$sellers_error .= 'Your Price field is required.<br>';
					}
					if (!strlen($item_selection)) {
						$sellers_error .= 'Condition field is required.<br>';
					}
					if ($item_category_type == 'bags') {
						if (!strlen($item_length)) {
							$sellers_error .= 'Length field is required.<br>';
						}
						if (!strlen($item_height)) {
							$sellers_error .= 'Height field is required.<br>';
						}
						if (!strlen($item_width)) {
							$sellers_error .= 'Width field is required.<br>';
						}
					} else if ($item_category_type == 'shoes') {
						if (!strlen($item_size)) {
							$sellers_error .= 'Size field is required.<br>';
						}
					} else if ($item_category_type == 'watches') {
						if (!strlen($item_case_diameter)) {
							$sellers_error .= 'Case Diameter field is required.<br>';
						}
						if (!strlen($item_movement_type)) {
							$sellers_error .= 'Movement Type field is required.<br>';
						}
						if (!strlen($item_bracelet_material)) {
							$sellers_error .= 'Bracelet Material field is required.<br>';
						}
						if (!strlen($item_case_material)) {
							$sellers_error .= 'Case Material field is required.<br>';
						}
					}
					if ($item_category_type != 'jewelry') {
						if (!strlen($item_colour)) {
							$sellers_error .= 'Colour field is required.<br>';
						}
					}
					if (!count($item_includes)) {
						$sellers_error .= 'Includes is required.<br>';
					}

					if (strlen($item_style)) { $item_category = $item_style; }
					if (is_array($item_metal)) { $item_metal = implode(';', $item_metal); }
					if ($item_includes) { $item_includes = implode("|", $item_includes); }

					if (!strlen($sellers_error)) {
						// clear post taxonomies
						$post_term_relationships = $wpdb->get_results(sprintf("SELECT term_taxonomy_id FROM %sterm_relationships WHERE object_id = %s", $wpdb->prefix, $post_id));
						if ($post_term_relationships) {
							foreach($post_term_relationships as $post_term_relationship) {
								$wpdb->query(sprintf("UPDATE %sterm_taxonomy SET count = count - 1 WHERE term_taxonomy_id = %s", $wpdb->prefix, $post_term_relationship->term_taxonomy_id));
							}
							$wpdb->query(sprintf("DELETE FROM %sterm_relationships WHERE object_id = %s", $wpdb->prefix, $post_id));
						}

						$item_status = 'pseller_pending';
						if ($save_draft == 'true') { $item_status = 'pseller_draft'; }

						$update = array();
						$update['ID'] = $post_id;
						$update['post_title'] = $item_name;
						$update['post_name'] = sanitize_title($item_name);
						$update['post_content'] = $item_desc;
						$update['post_status'] = $item_status;
						$update['post_category'] = array($item_category);
						wp_update_post($update);

						$item_retail_price = sellers_to_usd_price($item_retail_price);
						$item_sell_price = sellers_to_usd_price($item_sell_price);
						$item_your_price = sellers_to_usd_price($item_your_price);

						$item_sell_price = sellers_get_selling_price($item_your_price);

						update_post_meta($post_id, 'price', $item_retail_price);
						update_post_meta($post_id, 'new_price', $item_sell_price);
						update_post_meta($post_id, 'item_your_price', $item_your_price);

						if ($item_condition_desc) {      update_post_meta($post_id, 'item_condition_desc', $item_condition_desc);
						} else { delete_post_meta($post_id, 'item_condition_desc'); }
						if ($item_length) {              update_post_meta($post_id, 'item_length', $item_length);
						} else { delete_post_meta($post_id, 'item_length'); }
						if ($item_height) {              update_post_meta($post_id, 'item_height', $item_height);
						} else { delete_post_meta($post_id, 'item_height'); }
						if ($item_width) {               update_post_meta($post_id, 'item_width', $item_width);
						} else { delete_post_meta($post_id, 'item_width'); }
						if ($item_handle_drop) {         update_post_meta($post_id, 'item_handle_drop', $item_handle_drop);
						} else { delete_post_meta($post_id, 'item_handle_drop'); }
						if ($item_exterior_material) {   update_post_meta($post_id, 'item_exterior_material', $item_exterior_material);
						} else { delete_post_meta($post_id, 'item_exterior_material'); }
						if ($item_interior_material) {   update_post_meta($post_id, 'item_interior_material', $item_interior_material);
						} else { delete_post_meta($post_id, 'item_interior_material'); }
						if ($item_hardware) {            update_post_meta($post_id, 'item_hardware', $item_hardware);
						} else { delete_post_meta($post_id, 'item_hardware'); }
						if ($item_includes) {            update_post_meta($post_id, 'item_includes', $item_includes);
						} else { delete_post_meta($post_id, 'item_includes'); }
						if ($item_size) {                update_post_meta($post_id, 'item_size', $item_size);
						} else { delete_post_meta($post_id, 'item_size'); }
						if ($item_heel_size) {           update_post_meta($post_id, 'item_heel_size', $item_heel_size);
						} else { delete_post_meta($post_id, 'item_heel_size'); }
						if ($item_case_diameter) {       update_post_meta($post_id, 'item_case_diameter', $item_case_diameter);
						} else { delete_post_meta($post_id, 'item_case_diameter'); }
						if ($item_watch_bracelet_size) { update_post_meta($post_id, 'item_watch_bracelet_size', $item_watch_bracelet_size);
						} else { delete_post_meta($post_id, 'item_watch_bracelet_size'); }
						if ($item_movement_type) {       update_post_meta($post_id, 'item_movement_type', $item_movement_type);
						} else { delete_post_meta($post_id, 'item_movement_type'); }
						if ($item_upper_material) {      update_post_meta($post_id, 'item_upper_material', $item_upper_material);
						} else { delete_post_meta($post_id, 'item_upper_material'); }
						if ($item_lining_material) {     update_post_meta($post_id, 'item_lining_material', $item_lining_material);
						} else { delete_post_meta($post_id, 'item_lining_material'); }
						if ($item_sole_material) {       update_post_meta($post_id, 'item_sole_material', $item_sole_material);
						} else { delete_post_meta($post_id, 'item_sole_material'); }
						if ($item_bracelet_material) {   update_post_meta($post_id, 'item_bracelet_material', $item_bracelet_material);
						} else { delete_post_meta($post_id, 'item_bracelet_material'); }
						if ($item_case_material) {       update_post_meta($post_id, 'item_case_material', $item_case_material);
						} else { delete_post_meta($post_id, 'item_case_material'); }
						if ($item_ring_size) {           update_post_meta($post_id, 'item_ring_size', $item_ring_size);
						} else { delete_post_meta($post_id, 'item_ring_size'); }
						if ($item_ring_width) {          update_post_meta($post_id, 'item_ring_width', $item_ring_width);
						} else { delete_post_meta($post_id, 'item_ring_width'); }
						if ($item_ring_height) {         update_post_meta($post_id, 'item_ring_height', $item_ring_height);
						} else { delete_post_meta($post_id, 'item_ring_height'); }
						if ($item_necklace_length) {     update_post_meta($post_id, 'item_necklace_length', $item_necklace_length);
						} else { delete_post_meta($post_id, 'item_necklace_length'); }
						if ($item_earring_width) {       update_post_meta($post_id, 'item_earring_width', $item_earring_width);
						} else { delete_post_meta($post_id, 'item_earring_width'); }
						if ($item_earring_height) {      update_post_meta($post_id, 'item_earring_height', $item_earring_height);
						} else { delete_post_meta($post_id, 'item_earring_height'); }
						if ($item_bracelet_size) {       update_post_meta($post_id, 'item_bracelet_size', $item_bracelet_size);
						} else { delete_post_meta($post_id, 'item_bracelet_size'); }
						if ($item_bracelet_length) {     update_post_meta($post_id, 'item_bracelet_length', $item_bracelet_length);
						} else { delete_post_meta($post_id, 'item_bracelet_length'); }
						if ($item_metal) {               update_post_meta($post_id, 'item_metal', $item_metal);
						} else { delete_post_meta($post_id, 'item_metal'); }

						sellers_set_post_to_taxonomy($post_id, $item_brand, 'brand');
						sellers_set_post_to_taxonomy($post_id, $item_selection, 'selection');
						sellers_set_post_to_taxonomy($post_id, $item_colour, 'colour');
						nws_update_post_prices_tax($post_id);

						$apfields = array();
						if (strlen($_FILES['item_picture']['name'][0])) {
							$ipic = 1;
							for($u=0; $u<count($_FILES['item_picture']['name']); $u++) {
								if (strpos($_FILES['item_picture']['type'][$u], 'image') !== false) {
									$_FILES['item_picture'.$ipic]['name'] = $_FILES['item_picture']['name'][$u];
									$_FILES['item_picture'.$ipic]['type'] = $_FILES['item_picture']['type'][$u];
									$_FILES['item_picture'.$ipic]['tmp_name'] = $_FILES['item_picture']['tmp_name'][$u];
									$_FILES['item_picture'.$ipic]['error'] = $_FILES['item_picture']['error'][$u];
									$_FILES['item_picture'.$ipic]['size'] = $_FILES['item_picture']['size'][$u];
									$apfields[] = 'item_picture'.$ipic;
									$ipic++;
								}
							}
						}
						if (count($apfields)) {
							sellers_upload_post_picture($post_id, $apfields);
						}

						header("Location: ".get_permalink($OPTION['wps_profreseller_my_items_page']));
						exit;
					}
				}
			break;
			case "profreseller_submit_item":
				$post_id = $_POST['post_id'];
				$post_data = get_post($post_id);
				if ($post_data && $post_data->post_author == $current_user->ID) {
					$update = array();
					$update['ID'] = $post_id;
					$update['post_status'] = 'pseller_pending';
					wp_update_post($update);
				}
			break;
			case "profreseller_delete_item":
				$post_id = $_POST['post_id'];
				$post_data = get_post($post_id);
				if ($post_data && $post_data->post_author == $current_user->ID) {
					$update = array();
					$update['ID'] = $post_id;
					$update['post_status'] = 'seller_deleted';
					wp_update_post($update);
				}
			break;
			case "profreseller_clear_item_inventory":
				$post_id = $_POST['post_id'];
				$post_data = get_post($post_id);
				if ($post_data && $post_data->post_author == $current_user->ID) {
					$ID_item = get_post_meta($post_id, 'ID_item', true);
					update_item_inventory($ID_item, 0, true, 'Profseller clear item inventory');
					update_post_meta($post_id, '_prof_item_deleted', 'true');
				}
			break;
			case "profreseller_change_item_price":
				$post_id = $_POST['post_id'];
				$item_your_price = $_POST['item_your_price'];
				if ($post_id && $item_your_price > 0) {
					$post_data = get_post($post_id);
					if ($post_data && $post_data->post_author == $current_user->ID) {
						$item_your_price = sellers_to_usd_price($item_your_price);
						$item_sell_price = sellers_get_selling_price($item_your_price);
						update_post_meta($post_id, 'item_your_price', $item_your_price);
						update_post_meta($post_id, 'new_price', $item_sell_price);
						nws_update_post_prices_tax($post_id);

						echo format_price(sellers_currency_price($item_your_price)).';'.format_price(sellers_currency_price($item_sell_price));
					}
				}
				exit;
			break;
			case "profreseller_update_info":
				$seller_first_name = $_POST['seller_first_name'];
				$seller_last_name = $_POST['seller_last_name'];
				$seller_address = $_POST['seller_address'];
				$seller_email = $_POST['seller_email'];
				$seller_phone = $_POST['seller_phone'];
				$seller_bank_type = $_POST['seller_bank_type'];
				$seller_bank_details = $_POST['seller_bank_details'];

				$email_exists = email_exists($seller_email);
				if (!$email_exists) {
					$userdata = array('ID' => $current_user->ID, 'user_email' => $seller_email);
					wp_update_user($userdata);
				}

				update_user_meta($current_user->ID, 'first_name', $seller_first_name);
				update_user_meta($current_user->ID, 'last_name', $seller_last_name);
				update_user_meta($current_user->ID, 'seller_address', $seller_address);
				update_user_meta($current_user->ID, 'phone', $seller_phone);
				update_user_meta($current_user->ID, 'seller_bank_type', $seller_bank_type);
				update_user_meta($current_user->ID, 'seller_bank_details', $seller_bank_details);
				exit;
			break;
			case "profreseller_delete_picture":
				$post_id = $_POST['post_id'];
				$attach_id = $_POST['attach_id'];
				$attach_count = $wpdb->get_var(sprintf("SELECT COUNT(ID) FROM %sposts WHERE post_type = 'attachment' AND post_parent = %s ORDER BY ID", $wpdb->prefix, $post_id));
				if ($attach_count > 1) {
					wp_delete_attachment($attach_id, true);
					$tid = get_post_meta($post_id, '_thumbnail_id', true);
					if (!$tid) {
						$fattach_id = $wpdb->get_var(sprintf("SELECT ID FROM %sposts WHERE post_type = 'attachment' AND post_parent = %s ORDER BY ID", $wpdb->prefix, $post_id));
						if ($fattach_id) {
							update_post_meta($post_id, '_thumbnail_id', $fattach_id);
						}
					}
				}
				exit;
			break;
			case "profreseller_viewed_orders":
				$psorders = $_POST['psorders'];
				if (strlen($psorders)) {
					$wpdb->query(sprintf("UPDATE %swps_orders SET viewed = 1 WHERE oid IN (%s)", $wpdb->prefix, $psorders));
				}
				exit;
			break;
			case "profreseller_pricing_database":
				profreseller_pricing_database();
				exit;
			break;
			case "profreseller_get_selling_price":
				$item_your_price = trim($_POST['item_your_price']);
				$item_your_price = sellers_to_usd_price($item_your_price);
				$item_sell_price = sellers_get_selling_price($item_your_price);
				$item_sell_price = sellers_currency_price($item_sell_price);
				echo format_price($item_sell_price, true);
				exit;
			break;
			// individual sellers
			case "indivseller_add_item":
				$logining = false;
				$item_number = trim($_POST['item_number']);
				$item_user = trim($_POST['item_user']);
				$user_email = trim($_POST['user_email']);
				$user_pass = trim($_POST['user_pass']);
				$user_phone = trim($_POST['user_phone']);
				$user_id = $current_user->ID;

				$item_seller = 'i';
				$item_status = 'iseller_draft';

				if (strlen($item_user)) {
					$uid = $wpdb->get_var(sprintf("SELECT ID FROM %susers WHERE user_login = '%s'", $wpdb->prefix, $item_user));
					if ($uid) {
						$user_id = $uid;
						$ucapabilities = get_user_meta($uid, 'wp_capabilities', true);
						if ($ucapabilities['profseller']) {
							$item_seller = 'p';
							$item_status = 'pseller_pending';
						}
					} else {
						$sellers_error .= 'Seller username is incorrect.<br>';
					}
				}

				if (!is_user_logged_in()) {
					$logining = true;
					if (!strlen($user_email)) {
						$sellers_error .= 'Your Email field is required.<br>';
					} else if (!is_email($user_email)) {
						$sellers_error .= 'Email address is incorrect.<br>';
					}
					if (!strlen($user_pass)) {
						$sellers_error .= 'Password field is required.<br>';
					}

					if (!strlen($sellers_error)) {
						$user_data = get_user_by_email($user_email);
						if ($user_data) { // user exist
							$user_id = $user_data->ID;
							if (!wp_check_password($user_pass, $user_data->data->user_pass, $user_id)) { // password isn't match
								$sellers_error .= 'Password is incorrect. Please try again.<br>';
							}
						} else { // create new user
							$user = new stdClass;
							$user->user_login = sanitize_user($user_email, true);
							$user->user_email = sanitize_text_field($user_email);
							$user->user_pass = $user_pass;
							$user->use_ssl = 0;
							$user->show_admin_bar_front = "false";	
							$user->show_admin_bar_admin = "false";
							$user_id = wp_insert_user(get_object_vars($user));
							$user_data = get_userdata($user_id);
							update_utm_params('users', $user_id);
						}
					}
				}

				if (!strlen($sellers_error)) {
					if ($logining) {
						wp_set_current_user($user_id, $user_email);
						wp_set_auth_cookie($user_id, $remember);
						do_action('wp_login', $user_email);
					}
					for ($i=1; $i<=$item_number; $i++) {
						if (isset($_POST['item_name'][$i])) {
							$item_category = $_POST['item_category'][$i];
							$item_brand = $_POST['item_brand'][$i];
							$item_name = trim($_POST['item_name'][$i]);
							$item_your_price = trim($_POST['item_your_price'][$i]);
							$item_selection = $_POST['item_selection'][$i];
							$item_includes = $_POST['item_includes'][$i];
							$item_pictures = $_POST['item_pictures'][$i];

							if (!is_array($item_includes)) { $item_includes = array(); }

							$new_post = array();
							$new_post['post_title'] = $item_name;
							$new_post['post_name'] = sanitize_title($item_name);
							$new_post['post_status'] = $item_status;
							$new_post['post_author'] = $user_id;
							$new_post['post_created'] = current_time('mysql');
							$new_post_id = wp_insert_post($new_post);

							$item_your_price = sellers_to_usd_price($item_your_price);

							$item_id = sellers_assign_item_id($new_post_id, $user_id, 'i');

							update_post_meta($new_post_id, 'item_seller', $item_seller);
							update_post_meta($new_post_id, 'price', $item_your_price);
							update_post_meta($new_post_id, 'item_your_price', $item_your_price);
							if (count($item_includes)) {
								update_post_meta($new_post_id, 'item_includes', implode("|", $item_includes));
							}

							sellers_set_post_to_taxonomy($new_post_id, $item_category, 'seller-category');
							sellers_set_post_to_taxonomy($new_post_id, $item_selection, 'selection');
							nws_update_post_prices_tax($new_post_id);

							if ($item_brand != 'other') {
								sellers_set_post_to_taxonomy($new_post_id, $item_brand, 'brand');
							}

							sellers_insert_post_pictures($new_post_id, $item_pictures);

							update_utm_params('posts', $new_post_id);
						}
					}
					if (strlen($user_phone)) {
						update_user_meta($user_id, 'phone', $user_phone);
					}

					// subscribe user
					$udata = get_userdata($user_id);
					nws_subscribe_action('submissionform', array('email' => $udata->data->user_email));

					$redirect = get_permalink($OPTION['wps_indvseller_my_items_page']).'?success';
					if (strlen($item_user) && $uid > 0) {
						$redirect = get_permalink($OPTION['wps_tlc_admin_files_page']);
					}

					header("Location: ".$redirect);
					exit;
				}
			break;
			case "indivseller_edit_item":
				$post_id = $_POST['post_id'];
				$item_category = $_POST['item_category'];
				$item_brand = $_POST['item_brand'];
				$item_name = trim($_POST['item_name']);
				$item_your_price = trim($_POST['item_your_price']);
				$item_selection = $_POST['item_selection'];
				$item_includes = $_POST['item_includes'];
				$item_pictures = $_POST['item_pictures'];

				if (!is_array($item_includes)) { $item_includes = array(); }

				$post_data = get_post($post_id);
				if ($post_data && $post_data->post_author == $current_user->ID) {
					if (!is_array($item_includes)) { $item_includes = array(); }

					if (!strlen($item_category)) {
						$sellers_error .= 'Category field is required.<br>';
					}
					if (!strlen($item_brand)) {
						$sellers_error .= 'Brand field is required.<br>';
					}
					if (!strlen($item_name)) {
						$sellers_error .= 'Item Name field is required.<br>';
					}
					if (!strlen($item_pictures)) {
						$sellers_error .= 'Please attach Picture(s).<br>';
					}

					if (!strlen($sellers_error)) {
						// clear post taxonomies
						$post_term_relationships = $wpdb->get_results(sprintf("SELECT term_taxonomy_id FROM %sterm_relationships WHERE object_id = %s", $wpdb->prefix, $post_id));
						if ($post_term_relationships) {
							foreach($post_term_relationships as $post_term_relationship) {
								$wpdb->query(sprintf("UPDATE %sterm_taxonomy SET count = count - 1 WHERE term_taxonomy_id = %s", $wpdb->prefix, $post_term_relationship->term_taxonomy_id));
							}
							$wpdb->query(sprintf("DELETE FROM %sterm_relationships WHERE object_id = %s", $wpdb->prefix, $post_id));
						}

						$update = array();
						$update['ID'] = $post_id;
						$update['post_title'] = $item_name;
						$update['post_name'] = sanitize_title($item_name);
						wp_update_post($update);

						$item_your_price = sellers_to_usd_price($item_your_price);

						update_post_meta($post_id, 'price', $item_your_price);
						update_post_meta($post_id, 'item_your_price', $item_your_price);
						update_post_meta($post_id, 'item_includes', implode("|", $item_includes));

						sellers_set_post_to_taxonomy($post_id, $item_category, 'seller-category');
						sellers_set_post_to_taxonomy($post_id, $item_selection, 'selection');
						nws_update_post_prices_tax($post_id);

						if ($item_brand != 'other') {
							sellers_set_post_to_taxonomy($post_id, $item_brand, 'brand');
						}

						sellers_update_post_pictures($post_id, $item_pictures);

						header("Location: ".get_permalink($OPTION['wps_indvseller_my_items_page']));
						exit;
					}
				}
			break;
			case "indivseller_delete_item":
				$post_id = $_POST['post_id'];
				$post_data = get_post($post_id);
				if ($post_data && $post_data->post_author == $current_user->ID) {
					wp_delete_post($post_id, true);
				}
			break;
			case "indivseller_submit_quotation_price":
				$post_id = $_POST['post_id'];
				$item_your_quotation_price = $_POST['item_your_quotation_price'];
				if ($post_id && $item_your_quotation_price > 0) {
					$post_data = get_post($post_id);
					if ($post_data && $post_data->post_author == $current_user->ID) {
						$spost_tlc_quotation_price_low = get_post_meta($post_id, 'item_tlc_quotation_price_low', true);
						$spost_tlc_quotation_price_high = get_post_meta($post_id, 'item_tlc_quotation_price_high', true);
						$item_your_quotation_price = sellers_to_usd_price($item_your_quotation_price);

						if ($item_your_quotation_price < $spost_tlc_quotation_price_low) {
							$item_your_quotation_price = $spost_tlc_quotation_price_low;
						}
						if ($item_your_quotation_price > $spost_tlc_quotation_price_high) {
							$item_your_quotation_price = $spost_tlc_quotation_price_high;
						}

						$item_new_price = sellers_get_selling_price($item_your_quotation_price);

						$update = array();
						$update['post_status'] = 'iseller_approved';
						$wpdb->update($wpdb->prefix."posts", $update, array("ID" => $post_id));
						update_post_meta($post_id, 'item_your_quotation_price', $item_your_quotation_price);
						update_post_meta($post_id, 'new_price', $item_new_price);
						update_post_meta($post_id, '_item_quotation_price', $item_your_quotation_price);
						update_post_meta($post_id, '_new_price', $item_new_price);
						update_post_meta($post_id, '_item_quotation_currency_code', $_SESSION["currency-code"]);
						update_post_meta($post_id, '_item_quotation_currency_rate', $_SESSION["currency-rate"]);
						nws_update_post_prices_tax($post_id);
					}
				}
				exit;
			break;
			case "indivseller_change_item_price":
				$post_id = $_POST['post_id'];
				$item_your_price = $_POST['item_your_price'];
				if ($post_id && $item_your_price > 0) {
					$post_data = get_post($post_id);
					if ($post_data && $post_data->post_author == $current_user->ID) {
						$item_your_price = sellers_to_usd_price($item_your_price);
						$item_your_quotation_price = get_post_meta($post_id, 'item_your_quotation_price', true);
						$old_price = get_post_meta($post_id, 'old_price', true);
						$item_old_price = get_post_meta($post_id, 'new_price', true);
						if (!$item_old_price) {
							$item_old_price = get_post_meta($post_id, 'price', true);
						}

						$new_qprice_5perc = $item_your_quotation_price - (($item_your_quotation_price / 100) * 5);
						$new_qprice_50perc = $item_your_quotation_price - (($item_your_quotation_price / 100) * 50);
						if ($item_your_price <= $new_qprice_5perc && $item_your_price >= $new_qprice_50perc) {
							$adate = mktime(23, 59, 59, 11, 7, 2013); // 7th November 2013
							$pdate = strtotime($post_data->post_date);
							if ($pdate <= $adate) {
								$item_new_price = sellers_get_old_selling_price($item_your_price);
							} else {
								$item_new_price = sellers_get_selling_price($item_your_price);
							}

							update_post_meta($post_id, 'item_your_price', $item_your_quotation_price);
							update_post_meta($post_id, 'item_your_quotation_price', $item_your_price);
							update_post_meta($post_id, 'new_price', $item_new_price);
							update_post_meta($post_id, 'item_request_price', 'completed');
							nws_update_post_prices_tax($post_id);
							if (!$old_price) {
								update_post_meta($post_id, 'old_price', $item_old_price);
							}
							// add to sale category
							$sale_ttid = $wpdb->get_var(sprintf("SELECT term_taxonomy_id FROM %sterm_taxonomy WHERE term_id = %s", $wpdb->prefix, $OPTION['wps_sale_category']));
							if ($sale_ttid) {
								$check_item = $wpdb->get_var(sprintf("SELECT COUNT(object_id) FROM %sterm_relationships WHERE object_id = %s AND term_taxonomy_id = %s", $wpdb->prefix, $post_id, $sale_ttid));
								if (!$check_item) {
									$insert = array();
									$insert['object_id'] = $post_id;
									$insert['term_taxonomy_id'] = $sale_ttid;
									$wpdb->insert($wpdb->prefix."term_relationships", $insert);
									$wpdb->query(sprintf("UPDATE %sterm_taxonomy SET count = count + 1 WHERE term_taxonomy_id = %s", $wpdb->prefix, $sale_ttid));
								}
							}
							// return values to js
							$item_your_price = sellers_currency_price($item_your_price);
							$item_new_price = sellers_currency_price($item_new_price);
							echo format_price($item_your_price, true).';'.format_price($item_new_price, true);
						} else {
							echo 'error';
						}
					}
				}
				exit;
			break;
			case "indivseller_accept_suggested_payout":
				$post_id = $_POST['post_id'];
				if ($post_id) {
					$post_data = get_post($post_id);
					if ($post_data && $post_data->post_author == $current_user->ID) {
						$item_your_quotation_price = get_post_meta($post_id, 'item_your_quotation_price', true);
						$item_suggested_your_quotation_price = get_post_meta($post_id, 'item_suggested_your_quotation_price', true);

						update_post_meta($post_id, 'item_your_quotation_price', $item_suggested_your_quotation_price);
						update_post_meta($post_id, 'item_your_price', $item_your_quotation_price);
						update_post_meta($post_id, 'item_request_price', 'completed');
						update_post_meta($post_id, 'item_suggested_price', 'completed');

						delete_post_meta($post_id, 'item_suggested_your_quotation_price');
						delete_post_meta($post_id, '_change_price_email');

						// send notification
						$subject = "Change Price Request (Individual Sellers)";
						$message = "Username: ".$current_user->data->user_login."\r\n";
						$message .= "Item: (".get_post_meta($post_id, 'ID_item', true).") ".$post_data->post_title."\r\n";
						$message .= "Price: ".$item_suggested_your_quotation_price." USD";
						sellers_send_notification($subject, $message);
					}
				}
			break;
			case "indivseller_decline_suggested_payout":
				$post_id = $_POST['post_id'];
				if ($post_id) {
					$post_data = get_post($post_id);
					if ($post_data && $post_data->post_author == $current_user->ID) {
						delete_post_meta($post_id, 'item_suggested_price');
						delete_post_meta($post_id, 'item_suggested_your_quotation_price');
						delete_post_meta($post_id, '_change_price_email');
					}
				}
			break;
			case "indivseller_update_info":
				$seller_first_name = $_POST['seller_first_name'];
				$seller_last_name = $_POST['seller_last_name'];
				$seller_address = $_POST['seller_address'];
				$seller_email = $_POST['seller_email'];
				$seller_phone = $_POST['seller_phone'];
				$seller_bank_type = $_POST['seller_bank_type'];
				$seller_bank_details = $_POST['seller_bank_details'];

				$email_exists = email_exists($seller_email);
				if (!$email_exists) {
					$userdata = array('ID' => $current_user->ID, 'user_email' => $seller_email);
					wp_update_user($userdata);
				}

				update_user_meta($current_user->ID, 'first_name', $seller_first_name);
				update_user_meta($current_user->ID, 'last_name', $seller_last_name);
				update_user_meta($current_user->ID, 'seller_address', $seller_address);
				update_user_meta($current_user->ID, 'phone', $seller_phone);
				update_user_meta($current_user->ID, 'seller_bank_type', $seller_bank_type);
				update_user_meta($current_user->ID, 'seller_bank_details', $seller_bank_details);
				exit;
			break;
			case "indivseller_delete_picture":
				$post_id = $_POST['post_id'];
				$attach_id = $_POST['attach_id'];
				$attach_count = $wpdb->get_var(sprintf("SELECT COUNT(ID) FROM %sposts WHERE post_type = 'attachment' AND post_parent = %s ORDER BY ID", $wpdb->prefix, $post_id));
				if ($attach_count > 1) {
					wp_delete_attachment($attach_id, true);
					$tid = get_post_meta($post_id, '_thumbnail_id', true);
					if (!$tid) {
						$fattach_id = $wpdb->get_var(sprintf("SELECT ID FROM %sposts WHERE post_type = 'attachment' AND post_parent = %s ORDER BY ID", $wpdb->prefix, $post_id));
						if ($fattach_id) {
							update_post_meta($post_id, '_thumbnail_id', $fattach_id);
						}
					}
				}
				exit;
			break;
			// summary page
			case "summary_delete_item":
				$post_id = $_POST['post_id'];
				$post_data = get_post($post_id);
				if ($post_data) {
					wp_delete_post($post_id, true);
				}
			break;
			case "summary_clear_item_inventory":
				$post_id = $_POST['post_id'];
				$post_data = get_post($post_id);
				if ($post_data) {
					$ID_item = get_post_meta($post_id, 'ID_item', true);
					update_item_inventory($ID_item, 0, true, 'Seller summary clear inventory');
				}
			break;
			case "summary_change_item_price":
				$post_id = $_POST['post_id'];
				$item_suggested_your_quotation_price = $_POST['price'];
				$post_data = get_post($post_id);
				if ($post_data) {
					$item_your_quotation_price = get_post_meta($post_id, 'item_your_quotation_price', true);
					$item_suggested_your_quotation_price = sellers_to_usd_price($item_suggested_your_quotation_price);
					update_post_meta($post_id, 'item_suggested_your_quotation_price', $item_suggested_your_quotation_price);
					update_post_meta($post_id, 'item_suggested_price', 'true');
					update_post_meta($post_id, 'item_suggested_price_date', date("d.m.Y"));

					// custom field for sending change price email
					update_post_meta($post_id, '_change_price_email', 'true');

					// send email to seller
					//sellers_send_change_price_email($post_id, $item_your_quotation_price, $item_suggested_your_quotation_price);
				}
			break;
			case "summary_prof_change_item_price":
				$post_id = $_POST['post_id'];
				$item_new_your_price = $_POST['price'];
				$post_data = get_post($post_id);
				if ($post_data) {
					$item_your_price = get_post_meta($post_id, 'item_your_price', true);
					$item_new_your_price = sellers_to_usd_price($item_new_your_price);
					update_post_meta($post_id, 'item_your_price', $item_new_your_price);
					update_post_meta($post_id, '_item_your_price', $item_your_price);
				}
			break;
			case "summary_update_seller_info":
				$seller_id = $_POST['seller_id'];
				$seller_first_name = $_POST['seller_first_name'];
				$seller_last_name = $_POST['seller_last_name'];
				$seller_address = $_POST['seller_address'];
				$seller_email = $_POST['seller_email'];
				$seller_phone = $_POST['seller_phone'];
				$seller_bank_type = $_POST['seller_bank_type'];
				$seller_bank_details = $_POST['seller_bank_details'];

				$email_exists = email_exists($seller_email);
				if (!$email_exists) {
					$userdata = array('ID' => $seller_id, 'user_email' => $seller_email);
					wp_update_user($userdata);
				}

				update_user_meta($seller_id, 'first_name', $seller_first_name);
				update_user_meta($seller_id, 'last_name', $seller_last_name);
				update_user_meta($seller_id, 'seller_address', $seller_address);
				update_user_meta($seller_id, 'phone', $seller_phone);
				update_user_meta($seller_id, 'seller_bank_type', $seller_bank_type);
				update_user_meta($seller_id, 'seller_bank_details', $seller_bank_details);
				exit;
			break;
			// tlc admin
			case "tlc_send_follow":
				$post_id = $_POST['post_id'];
				$subject = $_POST['subject'];
				$message = $_POST['message'];
				$post_data = get_post($post_id);
				if ($post_data) {
					$user_email = $wpdb->get_var(sprintf("SELECT user_email FROM %susers WHERE ID = %s", $wpdb->prefix, $post_data->post_author));
					NWS_send_email($user_email, $subject, $message);
					update_post_meta($post_id, 'item_follow_sent', 'yes');
				}
				exit;
			break;
			case "tlc_completed_item":
				$post_id = $_POST['post_id'];
				$post_data = get_post($post_id);
				if ($post_data) {
					update_post_meta($post_id, 'item_request_price', 'completed');
					delete_post_meta($post_id, 'item_new_your_quotation_price');
				}
			break;
			case "tlc_pickup_items":
				$postid = $_POST['postid'];
				if ($postid) {
					foreach($postid as $post_id) {
						$update = array();
						$update['ID'] = $post_id;
						$update['post_status'] = 'iseller_pickup';
						wp_update_post($update);
					}
				}
			break;
			case "tlc_received_items":
				$postid = $_POST['postid'];
				if ($postid) {
					foreach($postid as $post_id) {
						$update = array();
						$update['ID'] = $post_id;
						$update['post_status'] = 'iseller_received';
						wp_update_post($update);
					}
				}
			break;
			case "tlc_approved_items":
				$postid = $_POST['postid'];
				if ($postid) {
					foreach($postid as $post_id) {
						$update = array();
						$update['ID'] = $post_id;
						$update['post_status'] = 'pseller_approved';
						wp_update_post($update);
						update_post_meta($post_id, 'item_tlc_viwed', '1');
					}
				}
			break;
			case "tlc_published_items":
				$postid = $_POST['postid'];
				if ($postid) {
					foreach($postid as $post_id) {
						$update = array();
						$update['ID'] = $post_id;
						$update['post_status'] = 'publish';
						wp_update_post($update);

						update_post_meta($post_id, 'item_tlc_viwed', '1');
					}
				}
			break;
			case "tlc_returned_items":
				$postid = $_POST['postid'];
				if ($postid) {
					foreach($postid as $post_id) {
						$update = array();
						$update['ID'] = $post_id;
						$update['post_status'] = 'trash';
						wp_update_post($update);
					}
				}
			break;
			case "tlc_item_view":
				$post_id = $_POST['post_id'];
				if ($post_id) {
					update_post_meta($post_id, 'item_tlc_viwed', '1');
				}
				exit;
			break;
			case "tlc_set_qoutation":
				$post_id = $_POST['post_id'];
				$price_low = $_POST['q_price_low'];
				$price_high = $_POST['q_price_high'];
				$pricing_id = $_POST['pricing_id'];
				$qtype = $_POST['qtype'];
				if ($post_id) {
					$update = array();
					$update['post_status'] = 'iseller_pending';
					$wpdb->update($wpdb->prefix."posts", $update, array("ID" => $post_id));
					$price_low = sellers_to_usd_price($price_low);
					$price_high = sellers_to_usd_price($price_high);
					update_post_meta($post_id, 'item_tlc_quotation_price_high', $price_high);
					update_post_meta($post_id, 'item_tlc_quotation_price_low', $price_low);
					update_post_meta($post_id, 'item_tlc_quotation_currency_code', $_SESSION["currency-code"]);
					update_post_meta($post_id, 'item_tlc_quotation_currency_rate', $_SESSION["currency-rate"]);
					update_post_meta($post_id, '_item_tlc_quotation_price_high', $price_high);
					update_post_meta($post_id, '_item_tlc_quotation_price_low', $price_low);
					if ($pricing_id) {
						update_post_meta($post_id, 'item_pricing_database_id', $pricing_id);
					}
					$item_id = get_post_meta($post_id, 'ID_item', true);
					update_item_inventory($item_id, 1, true, 'TLC set quotation');
					if ($qtype == 'view') {
						sellers_send_completed_quotation_email($post_id);
					}
				}
				$retpage = get_permalink($OPTION['wps_tlc_admin_files_page']);
				if (strlen($_POST['retpage'])) {
					$retpage = $_POST['retpage'];
				}
				wp_redirect($retpage);
				exit;
			break;
			case "tlc_no_quotation":
				$post_id = $_POST['post_id'];
				if ($post_id) {
					$update = array();
					$update['post_status'] = 'iseller_noquote';
					$wpdb->update($wpdb->prefix."posts", $update, array("ID" => $post_id));
					update_post_meta($post_id, 'item_tlc_quotation_price_low', '');
					update_post_meta($post_id, 'item_tlc_quotation_price_high', '');
				}
				wp_redirect(get_permalink($OPTION['wps_tlc_admin_files_page']));
				exit;
			break;
			case "tlc_edit_item_category":
				$post_id = $_POST['post_id'];
				$seller_cat_id = $_POST['seller_cat_id'];
				if ($post_id && $seller_cat_id) {
					$old_tt_id = $wpdb->get_var(sprintf("SELECT tt.term_taxonomy_id FROM %sterm_taxonomy tt LEFT JOIN %sterm_relationships tr ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tt.taxonomy = 'seller-category' AND tr.object_id = '%s'", $wpdb->prefix, $wpdb->prefix, $post_id));
					if ($old_tt_id) {
						$wpdb->query(sprintf("UPDATE %sterm_taxonomy SET count = count - 1 WHERE term_taxonomy_id = %s", $wpdb->prefix, $old_tt_id));
						$wpdb->query(sprintf("DELETE FROM %sterm_relationships WHERE object_id = %s AND term_taxonomy_id = %s", $wpdb->prefix, $post_id, $old_tt_id));
					}
					sellers_set_post_to_taxonomy($post_id, $seller_cat_id, 'seller-category');
				}
				exit;
			break;
			case "tlc_pricing_database":
				sellers_tlc_pricing_database();
				exit;
			break;
			case "tlc_add_new_pricing":
				$post_id = $_POST['pid'];
				$category = $_POST['category'];
				$brand = $_POST['brand'];
				$style_name = $_POST['style_name'];
				$selection = $_POST['selection'];
				$colour = $_POST['colour'];
				$original_price = $_POST['original_price'];
				$high_price = $_POST['high_price'];
				$low_price = $_POST['low_price'];
				$includes_box = $_POST['includes_box'];
				$includes_invoice = $_POST['includes_invoice'];
				$includes_dustbag = $_POST['includes_dustbag'];
				$includes_card = $_POST['includes_card'];
				$includes_booklet = $_POST['includes_booklet'];
				$includes_packaging = $_POST['includes_packaging'];
				$notes = $_POST['notes'];
				$metal = $_POST['metal'];
				$material = $_POST['material'];
				$movement = $_POST['movement'];

				if ($category > 0) {
					$photo = '';
					$post_thumb_id = get_post_thumbnail_id($post_id);
					if ($post_thumb_id) {
						$post_thumb = wp_get_attachment_image_src($post_thumb_id, 'full');
						if ($post_thumb) {
							$post_thumb_url = $post_thumb[0];
							$post_thumb_name = basename($post_thumb_url);
							$post_thumb_name_new = date("YmdHis").'_'.$post_thumb_name;
							$post_thumb_path = substr($post_thumb_url, strpos($post_thumb_url, '/wp-content/'));
							$post_thumb_path = str_replace($post_thumb_name, '', $post_thumb_path);
							@copy($_SERVER["DOCUMENT_ROOT"].$post_thumb_path.$post_thumb_name, $_SERVER["DOCUMENT_ROOT"].$post_thumb_path.$post_thumb_name_new);
							$photo = str_replace($post_thumb_name, $post_thumb_name_new, $post_thumb_url);
						}
					}

					$original_price = sellers_to_usd_price($original_price);
					$high_price = sellers_to_usd_price($high_price);
					$low_price = sellers_to_usd_price($low_price);
					$includes_box = sellers_to_usd_price($includes_box);
					$includes_invoice = sellers_to_usd_price($includes_invoice);
					$includes_dustbag = sellers_to_usd_price($includes_dustbag);
					$includes_card = sellers_to_usd_price($includes_card);
					$includes_booklet = sellers_to_usd_price($includes_booklet);
					$includes_packaging = sellers_to_usd_price($includes_packaging);

					$data = array();
					$data['category'] = $category;
					$data['brand'] = $brand;
					$data['style_name'] = $style_name;
					$data['selection'] = $selection;
					$data['colour'] = $colour;
					$data['original_price'] = $original_price;
					$data['high_price'] = $high_price;
					$data['low_price'] = $low_price;
					$data['includes_box'] = $includes_box;
					$data['includes_invoice'] = $includes_invoice;
					$data['includes_dustbag'] = $includes_dustbag;
					$data['includes_card'] = $includes_card;
					$data['includes_booklet'] = $includes_booklet;
					$data['includes_packaging'] = $includes_packaging;
					$data['photo'] = $photo;
					$data['notes'] = $notes;
					$data['metal'] = $metal;
					$data['material'] = $material;
					$data['movement'] = $movement;
					$wpdb->insert($wpdb->prefix."wps_pricing", $data);
				}
				exit;
			break;
			case "profseller_form_submit":
				$psf_name = $_POST['psf_name'];
				$psf_company = $_POST['psf_company'];
				$psf_email = $_POST['psf_email'];
				$psf_contact_number = $_POST['psf_contact_number'];
				$psf_category = $_POST['psf_category'];

				if (strlen($psf_name) && strlen($psf_company) && strlen($psf_email) && strlen($psf_contact_number)) {
					$to_email = $OPTION['wps_profseller_form_email'];
					$subject = 'New Professional Seller Request';
					$message = 'Name: '.$psf_name.'<br>';
					$message .= 'Company Name: '.$psf_company.'<br>';
					$message .= 'E-mail: '.$psf_email.'<br>';
					$message .= 'Contact number: '.$psf_contact_number.'<br>';
					if (is_array($psf_category)) {
						$message .= 'Category: '.implode(', ', $psf_category);
					}
					NWS_send_email($to_email, $subject, $message);
					wp_redirect(get_permalink($OPTION['wps_professional_seller_form_page']).'?success');
					exit;
				}
			break;
		}
	}
	if ($_GET['ajax_sellers_action'] == 'getusers') { // get users login (tlc team add new item)
		$ulogin = trim($_GET["q"]);
		if (strlen($ulogin)) {
			$users = $wpdb->get_results(sprintf("SELECT user_login FROM %susers WHERE user_login LIKE '%s' OR user_email LIKE '%s' ORDER BY user_login", $wpdb->prefix, $ulogin.'%', $ulogin.'%'));
			if ($users) {
				foreach($users as $user) {
					echo $user->user_login.chr(10);
				}
			}
		}
		exit;
	}
}

function sellers_get_selling_price($price) {
	if ($price < (191.78 * 0.6)) {
		$selling_price = $price / 0.6;
	} else if ($price < 1898.63) {
		$selling_price = (191.78 + (($price - (191.78 * 0.6)) / 0.7));
	} else {
		$selling_price = (2739.73 + ($price - 1898.63) / 0.8);
	}
	return sellers_round_selling_price($selling_price);
}

function sellers_get_old_selling_price($price) {
	if ($price < 1918) {
		$selling_price = $price / 0.7;
	} else {
		$selling_price = (($price - 1918) / 0.85) + 2740;
	}
	return sellers_round_selling_price($selling_price);
}

function sellers_round_selling_price($selling_price) {
	global $OPTION;
	// round price to 100, 105, 110 not 101, 102, 109
	$selling_price_aed = round($selling_price * $OPTION['wps_exr_aed']);
	if ($selling_price_aed > 10) {
		$last_nmb = (int)substr($selling_price_aed, -1);
		$base_nmb = $selling_price_aed - $last_nmb;
		if ($last_nmb > 0) {
			if ($last_nmb < 3) {
				$last_nmb = 0;
			} else if ($last_nmb >= 3 && $last_nmb < 8) {
				$last_nmb = 5;
			} else {
				$last_nmb = 10;
			}
			$selling_price_aed = $base_nmb + $last_nmb;
			$selling_price = $selling_price_aed / $OPTION['wps_exr_aed'];
		}
	}
	return $selling_price;
}

function sellers_set_post_to_taxonomy($post_id, $tax_id, $taxonomy) {
	global $wpdb;
	$tt_id = $wpdb->get_var(sprintf("SELECT term_taxonomy_id FROM %sterm_taxonomy WHERE term_id = %s AND taxonomy = '%s'", $wpdb->prefix, $tax_id, $taxonomy));
	if ($tt_id) {
		$insert = array();
		$insert['object_id'] = $post_id;
		$insert['term_taxonomy_id'] = $tt_id;
		$wpdb->insert($wpdb->prefix."term_relationships", $insert);
		$wpdb->query(sprintf("UPDATE %sterm_taxonomy SET count = count + 1 WHERE term_taxonomy_id = %s", $wpdb->prefix, $tt_id));
	}
}

function sellers_currency_price($price) {
	$currency_rate = $_SESSION["currency-rate"];
	if (!$currency_rate) { $currency_rate = 1; }
	return number_format($price * $currency_rate, 0, '', '');
}

function sellers_to_usd_price($price) {
	$currency_rate = $_SESSION["currency-rate"];
	if (!$currency_rate) { $currency_rate = 1; }
	return $price / $currency_rate;
}

function sellers_upload_post_picture($post_id, $fnames) {
	require_once('./wp-admin/includes/post.php');
	require_once('./wp-admin/includes/image.php');
	require_once('./wp-admin/includes/file.php');
	require_once('./wp-admin/includes/media.php');

	if (!is_array($fnames)) { $fnames = array($fnames); }

	$attachs = array();
	foreach($fnames as $fname) {
		$attach_id = media_handle_upload($fname, $post_id);
		if (is_numeric($attach_id)) {
			$attachs[] = $attach_id;
		}
	}
	$tid = get_post_meta($post_id, '_thumbnail_id', true);
	if (!$tid && count($attachs)) {
		update_post_meta($post_id, '_thumbnail_id', $attachs[0]);
	}
}

function sellers_insert_post_pictures($post_id, $ipictures, $upd_thumb_id = true) {
	$attachs = array();
	$wp_upload_dir = wp_upload_dir();
	$ipictures = explode(';', $ipictures);
	require_once('./wp-admin/includes/image.php');
	require_once('./wp-admin/includes/file.php');
	require_once('./wp-admin/includes/media.php');
	foreach($ipictures as $ipicture) {
		$wp_filetype = wp_check_filetype(basename($ipicture), null);
		$attachment = array(
			 'guid' => $wp_upload_dir['url'] . '/' . basename($ipicture),
			 'post_mime_type' => $wp_filetype['type'],
			 'post_title' => preg_replace('/\.[^.]+$/', '', basename($ipicture)),
			 'post_content' => '',
			 'post_status' => 'inherit'
		);
		$abspath = $wp_upload_dir['path'].'/'.basename($ipicture);
		$attach_id = wp_insert_attachment($attachment, $abspath, $post_id);
		$attach_data = wp_generate_attachment_metadata($attach_id, $abspath);
		wp_update_attachment_metadata($attach_id, $attach_data);
		$attachs[] = $attach_id;
	}
	if ($upd_thumb_id) {
		$tid = get_post_meta($post_id, '_thumbnail_id', true);
		if (!$tid && count($attachs)) {
			update_post_meta($post_id, '_thumbnail_id', $attachs[0]);
		}
	}
}

function sellers_update_post_pictures($post_id, $ipictures) {
	global $wpdb;
	$ipictures = explode(';', $ipictures);

	$pattaches = array();
	$post_attaches = $wpdb->get_results(sprintf("SELECT * FROM %sposts WHERE post_type = 'attachment' AND post_mime_type LIKE '%s' AND post_parent = %s", $wpdb->prefix, 'image%', $post_id));
	if ($post_attaches) {
		foreach($post_attaches as $post_attach) {
			if (!in_array($post_attach->guid, $ipictures)) {
				wp_delete_attachment($post_attach->ID, true);
			}
		}
	}

	foreach($ipictures as $ipicture) {
		$paid = $wpdb->get_var(sprintf("SELECT ID FROM %sposts WHERE post_type = 'attachment' AND post_parent = %s AND guid = '%s'", $wpdb->prefix, $post_id, $ipicture));
		if (!$paid) {
			sellers_insert_post_pictures($post_id, $ipicture, false);
		}
	}
	$tid = get_post_meta($post_id, '_thumbnail_id', true);
	if ($tid) {
		$check_aid = $wpdb->get_var(sprintf("SELECT ID FROM %sposts WHERE ID = %s AND post_parent = %s", $wpdb->prefix, $tid, $post_id));
		if (!$check_aid) {
			$tid = '';
			delete_post_meta($post_id, '_thumbnail_id');
		}
	}
	if (!$tid) {
		$post_faid = $wpdb->get_var(sprintf("SELECT ID FROM %sposts WHERE post_type = 'attachment' AND post_parent = %s AND post_mime_type LIKE '%s' ORDER BY ID LIMIT 0, 1", $wpdb->prefix, $post_id, 'image%'));
		if ($post_faid) {
			update_post_meta($post_id, '_thumbnail_id', $post_faid);
		}
	}
}

function sellers_get_post_pictures($post_id) {
	global $wpdb;
	return $wpdb->get_results(sprintf("SELECT * FROM %sposts WHERE post_type = 'attachment' AND post_parent = %s ORDER BY ID", $wpdb->prefix, $post_id));
}

function sellers_get_email_format($format_id) {
	$email_format = get_post($format_id);
	if ($email_format) {
		return wpautop($email_format->post_content);
	}
}

function sellers_send_notification($subject, $message) {
	global $OPTION;
	$shop_email = $OPTION['wps_shop_email'];
	NWS_send_email($shop_email, $subject, $message, '', '', $OPTION['wps_sellers_cc_email']);
}

function sellers_send_order_email($post_id, $item_id) {
	global $wpdb, $OPTION;
	$post_data = get_post($post_id);
	if ($post_data) {
		$user_data = get_userdata($post_data->post_author);
		$user_roles = $user_data->roles;
		if (in_array('profseller', $user_roles)) {
			$subject = stripcslashes($OPTION['wps_sellers_order_email_subject']);
			$message = sellers_get_email_format($OPTION['wps_sellers_order_email_format']);
			$message = str_replace('{ITEM_NAME}', $post_data->post_title, $message);
			$message = str_replace('{ITEM_ID}', $item_id, $message);
			NWS_send_email($user_data->data->user_email, $subject, $message, '', '', $OPTION['wps_sellers_cc_email']);
		}
	}
}

// cron job for send changed price emails
add_action('wp', 'sellers_change_price_email_cron');
add_action('sellers_change_price_email_cron_action', 'sellers_send_change_price_email');
function sellers_change_price_email_cron() {
	if (!wp_next_scheduled('sellers_change_price_email_cron_action')) {
		wp_schedule_event(mktime(21, 0, 0, date("m"), date("d"), date("Y")), 'daily', 'sellers_change_price_email_cron_action');
	}
}

function sellers_send_change_price_email() {
	global $wpdb, $OPTION;

	$cpnotifications = array();
	$cpncd = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
	$sellers_send_change_price_cron_date = get_option("sellers_send_change_price_cron_date");

	if ($sellers_send_change_price_cron_date != $ancd || $_GET['cpnotify'] == 'send') {
		$cp_posts = $wpdb->get_results(sprintf("SELECT p.* FROM %sposts p LEFT JOIN %spostmeta pm ON pm.post_id = p.ID WHERE p.post_type = 'post' AND pm.meta_key = '_change_price_email' AND pm.meta_value = 'true'", $wpdb->prefix, $wpdb->prefix));
		if ($cp_posts) {
			foreach($cp_posts as $cp_post) {
				$cpnotifications[$cp_post->post_author][] = $cp_post;
			}
		}
		if (count($cpnotifications)) {
			$subject = stripcslashes($OPTION['wps_sellers_change_price_email_subject']);
			foreach($cpnotifications as $user_id => $cpnposts) {
				$user_data = get_userdata($user_id);
				if ($user_data) {
					$user_email = $user_data->user_email;
					$seller_name = $user_data->display_name;
					if (strlen($user_data->first_name) || strlen($user_data->last_name)) {
						$seller_name = $user_data->first_name.' '.$user_data->last_name;
					}
					if (count($cpnposts) > 1) {
						$message = sellers_get_email_format($OPTION['wps_sellers_change_price_multiple_email_format']);
						$items_list = '';
						foreach($cpnposts as $cpnpost) {
							$new_payout = get_post_meta($cpnpost->ID, 'item_suggested_your_quotation_price', true);
							$old_payout = get_post_meta($cpnpost->ID, 'item_your_quotation_price', true);
							if (strlen($items_list)) { $items_list .= '<br /><br />'; }
							$items_list .= '<a href="'.get_permalink($cpnpost->ID).'">'.$cpnpost->post_title.'</a><br />';
							$items_list .= format_price($new_payout * $OPTION['wps_exr_aed']).' AED instead of '.format_price($old_payout * $OPTION['wps_exr_aed']).' AED';
						}
						$message = str_replace('{ITEMS_LIST}', $items_list, $message);
					} else {
						$message = sellers_get_email_format($OPTION['wps_sellers_change_price_email_format']);
						foreach($cpnposts as $cpnpost) {
							$new_payout = get_post_meta($cpnpost->ID, 'item_suggested_your_quotation_price', true);
							$old_payout = get_post_meta($cpnpost->ID, 'item_your_quotation_price', true);
							$message = str_replace('{ITEM_NAME}', '<a href="'.get_permalink($cpnpost->ID).'">'.$cpnpost->post_title.'</a>', $message);
							$message = str_replace('{OLD_PAYOUT}', format_price($old_payout * $OPTION['wps_exr_aed']), $message);
							$message = str_replace('{NEW_PAYOUT}', format_price($new_payout * $OPTION['wps_exr_aed']), $message);
						}
					}
					$message = str_replace('{SELLER_NAME}', $seller_name, $message);
					NWS_send_email($user_email, $subject, $message, '', '', $OPTION['wps_sellers_cc_email']);
					echo 'Sent '.count($cpnposts).' item(s) to '.$user_email.'<br>';
				}
			}
		}
		update_option("sellers_send_change_price_cron_date", $cpncd);
	}
}

function sellers_send_completed_quotation_email($post_id) {
	global $OPTION, $wpdb;
	$esend = false;
	$subject = $OPTION['wps_sellers_completed_quotations_email_subject'];
	$message = sellers_get_email_format($OPTION['wps_sellers_completed_quotations_email_format']);

	$seller_data = $wpdb->get_row(sprintf("SELECT u.* FROM %susers u LEFT JOIN %sposts p ON p.post_author = u.ID WHERE p.ID = %s", $wpdb->prefix, $wpdb->prefix, $post_id));
	$seller_id = $seller_data->ID;
	$seller_email = $seller_data->user_email;
	$seller_name = $seller_data->user_login;

	$message = apply_filters('the_content', $message);
	$message = '<div style="font-family:Arial,Tahoma,Verdana;font-size:12px;">'.$message.'</div>';
	$message = str_replace('{SELLER_NAME}', $seller_name, $message);

	$check_aq = $wpdb->get_var(sprintf("SELECT COUNT(ID) FROM %sposts WHERE post_author = %s AND post_status = 'iseller_draft'", $wpdb->prefix, $seller_id));
	if ($check_aq == 0) {
		$pending_items = $wpdb->get_results(sprintf("SELECT * FROM %sposts WHERE post_type = 'post' AND post_status = 'iseller_pending' AND post_author = %s ORDER BY ID DESC", $wpdb->prefix, $seller_id));
		if ($pending_items) {
			$seller_items_table = '<table border="1" cellspacing="0" cellpadding="3" style="font-family:Arial,Tahoma,Verdana;font-size:12px;">';
			$seller_items_table .= '<tr>';
			$seller_items_table .= '<td valign="top" width="200"><b>Item</b></td>';
			$seller_items_table .= '<td valign="top" width="85"><b>Condition</b></td>';
			$seller_items_table .= '<td valign="top" width="100"><b>Includes</b></td>';
			$seller_items_table .= '<td valign="top" width="130"><b>Final Payment to you</b><br>(Choose your price between this range)</td>';
			$seller_items_table .= '<td valign="top" width="120"><b>Your price</b><br>(please write your final price here)</td>';
			$seller_items_table .= '</tr>';
			foreach($pending_items as $pending_item) {
				$item_id = $pending_item->ID;
				$quotation_price_high = get_post_meta($item_id, 'item_tlc_quotation_price_high', true);
				$quotation_price_low = get_post_meta($item_id, 'item_tlc_quotation_price_low', true);
				$quotation_currency_code = get_post_meta($item_id, 'item_tlc_quotation_currency_code', true);
				$quotation_currency_rate = get_post_meta($item_id, 'item_tlc_quotation_currency_rate', true);
				$condition = nws_get_tax_name($item_id, 'selection');
				$item_includes = get_post_meta($item_id, 'item_includes', true);
				$includes = sellers_get_includes_name($item_includes);
				if (!strlen($quotation_currency_code)) { $quotation_currency_code = 'USD'; $quotation_currency_rate = 1; }
				if ($quotation_price_low > 0 && $quotation_price_high > 0) {
					$seller_items_table .= '<tr>';
					$seller_items_table .= '<td valign="top"><b>'.$pending_item->post_title.'</b></td>';
					$seller_items_table .= '<td valign="top">'.$condition.'</td>';
					$seller_items_table .= '<td valign="top">'.implode(", ", $includes).'&nbsp;</td>';
					$seller_items_table .= '<td valign="top">'.format_price($quotation_price_high * $quotation_currency_rate).' - '.format_price($quotation_price_low * $quotation_currency_rate).' '.$quotation_currency_code.'</td>';
					$seller_items_table .= '<td valign="top">&nbsp;</td>';
					$seller_items_table .= '</tr>';
					$esend = true;
				}
			}
			$seller_items_table .= '</table>';
		}
		if ($esend) {
			$message = str_replace('{SELLER_ITEMS_TABLE}', $seller_items_table, $message);
			NWS_send_email($seller_email, $subject, $message, $OPTION['wps_sellers_quotations_email_from'], '', $OPTION['wps_sellers_cc_email']);
		}
	}
}

function sellers_admin_nav($total_posts, $pgparam, $tabname) {
	global $OPTION;
	$admin_items_per_page = $OPTION['wps_sellers_admin_files_items_per_page'];
	$transit_params = 'search-username='.$_GET['search-username'].'&search-option='.$_GET['search-option'].'&search-date-start='.$_GET['search-date-start'].'&search-date-end='.$_GET['search-date-end'].'&iscat='.$_GET['iscat'].'&pscat='.$_GET['pscat'].'&tab='.$tabname.'&';
	$admin_files_url = get_permalink($OPTION['wps_tlc_admin_files_page']).'?'.$transit_params.$pgparam.'=';
	$pg = $_GET[$pgparam];
	if (!$pg) { $pg = 1; }
	$total_pages = ceil($total_posts / $admin_items_per_page);
?>
	<ul class="pagenavi">
		<?php if ($pg > 1) { ?><li><a href="<?php echo $admin_files_url.($pg - 1); ?>" class="previous">Previous</a></li><?php } ?>
		<?php for ($p=1; $p<=$total_pages; $p++) { ?>
		<li><a href="<?php echo $admin_files_url.$p; ?>"<?php if ($pg == $p) { echo ' class="current"'; } ?>><?php echo $p; ?></a></li>
		<?php } ?>
		<?php if (($pg + 1) <= $total_pages) { ?><li><a href="<?php echo $admin_files_url.($pg + 1); ?>" class="next">Next</a></li><?php } ?>
	</ul>
<?php
}

function sellers_get_includes($forsingle = false) {
	global $OPTION;
	if ($forsingle) {
		$sellers_includes = array();
		$wps_includes_list = $OPTION['wps_includes_list'];
		$wps_includes_list = explode(chr(10), $wps_includes_list);
		if (count($wps_includes_list) > 0) {
			foreach($wps_includes_list as $il) { $il = str_replace(chr(13), '', $il);
				if (strlen($il)) {
					$il_array = explode('|', $il);
					$sellers_includes[$il_array[0]] = $il_array[1];
				}
			}
		}
	} else {
		$sellers_includes = array(
			'box'       => 'Original Box',
			'invoice'   => 'Original Invoice',
			'card'      => 'Original Brand Authenticity Card',
			'booklet'   => 'Info Booklet',
			'dustbag'   => 'Original Dustbag',
			'packaging' => 'LuxCloset Packaging'
		);
	}
	return $sellers_includes;
}

function sellers_get_includes_name($incl) {
	$includes_name = array();
	if (strlen($incl)) {
		$sellers_includes = sellers_get_includes();
		$sincludes = explode("|", $incl);
		foreach($sincludes as $sinclude) {
			$includes_name[] = $sellers_includes[$sinclude];
		}
	}
	return $includes_name;
}

function sellers_get_sizes() {
	$sizes = array(
		'European size 35.5 - US size 5',
		'European size 36 - US size 5.5',
		'European size 36.5 - US size 6',
		'European size 37 - US size 6.5',
		'European size 37.5 - US size 7',
		'European size 38 - US size 7.5',
		'European size 38.5 - US size 8',
		'European size 39 - US size 8.5',
		'European size 39.5 - US size 9',
		'European size 40 - US size 9.5',
		'European size 40.5 - US size 10',
		'European size 41 - US size 10.5',
		'European size 41.5 - US size 11'
	);
	return $sizes;
}

function sellers_get_ring_sizes() {
	$ring_sizes = array(
		'European Size 44 - U.S Size 3',
		'European Size 45 - U.S Size 3 1/2',
		'European Size 46 - U.S Size 3 3/4',
		'European Size 47 - U.S Size 4',
		'European Size 48 - U.S Size 4 1/2',
		'European Size 49 - U.S Size 5',
		'European Size 50 - U.S Size 5 1/4',
		'European Size 50.5 - U.S Size 5 1/2',
		'European Size 51 - U.S Size 5 3/4',
		'European Size 52 - U.S Size 6',
		'European Size 52.5 - U.S Size 6 1/4',
		'European Size 53 - U.S Size 6 1/2',
		'European Size 54 - U.S Size 6 3/4',
		'European Size 54.5 - U.S Size 7',
		'European Size 55 - U.S Size 7 1/4',
		'European Size 56 - U.S Size 7 1/2',
		'European Size 57 - U.S Size 8',
		'European Size 58 - U.S Size 8 1/4',
		'European Size 59 - U.S Size 8 3/4',
		'European Size 60 - U.S Size 9',
		'European Size 61 - U.S Size 9 1/2',
		'European Size 62 - U.S Size 10',
		'European Size 63 - U.S Size 10 1/4',
		'European Size 64 - U.S Size 10 3/4',
		'European Size 65 - U.S Size 11',
		'European Size 66 - U.S Size 11 1/2',
		'European Size 67 - U.S Size 11 3/4'
	);
	return $ring_sizes;
}

function sellers_get_category_data($cid) {
	$category_data = array($cid, 0, 0);
	$cat_data = get_category($cid);
	if ($cat_data->parent) {
		$category_data = array($cat_data->parent, $cid, 0);
		$parent_cat_data = get_category($cat_data->parent);
		if ($parent_cat_data->parent) {
			$category_data = array($parent_cat_data->parent, $cat_data->parent, $cid);
		}
	}
	return $category_data;
}

function sellers_assign_item_id($post_id, $user_id, $sl = 'i') {
	global $wpdb, $OPTION;
	$item_id = $OPTION['wps_sellers_indiv_item_id_prefix'];
	if (!strlen($item_id)) { $item_id = 'LC'; }
	if ($sl == 'p') {
		$item_id = get_user_meta($user_id, 'seller_item_id_prefix', true);
		if (!strlen($item_id)) {
			$user_login = $wpdb->get_var(sprintf("SELECT user_login FROM %susers WHERE ID = %s", $wpdb->prefix, $user_id));
			$item_id = strtoupper(substr($user_login, 0, 2));
		}
	}
	$item_id .= '-'.$user_id.'-'.$post_id;
	update_post_meta($post_id, 'ID_item', $item_id);
	return $item_id;
}

add_action('transition_post_status', 'sellers_transition_post_status', 10, 3);
function sellers_transition_post_status($new_status, $old_status, $post) {
	global $wpdb;
    if ($post->post_type == 'post' && $new_status == 'publish' && $old_status != 'publish') {
		$update = array();
		$update['ID'] = $post->ID;
		$update['post_date'] =  current_time('mysql');
		wp_update_post($update);
	}
}

function sellers_get_subcats($root) {
	$subcats = array($root);
	$scats = get_categories('child_of='.$root);
	if ($scats) {
		foreach($scats as $scat) {
			$subcats[] = $scat->term_id;
		}
	}
	return $subcats;
}

function sellers_tlc_pricing_database() {
	global $wpdb;
	$pricing_per_page = 20;
	$s_category = $_POST['s_category'];
	$s_brand = $_POST['s_brand'];
	$s_selection = $_POST['s_selection'];
	$s_term = $_POST['s_term'];
	$ppg = $_POST['ppg'];
	if (!$ppg) { $ppg = 1; }

	$psWhere = "";
	if ($s_category > 0) { if (strlen($psWhere)) { $psWhere .= " AND "; } $psWhere .= " p.category IN (".implode(',', sellers_get_subcats($s_category)).")"; }
	if (strlen($s_brand)) { if (strlen($psWhere)) { $psWhere .= " AND "; } $psWhere .= " p.brand = ".$s_brand; }
	if (strlen($s_selection)) { if (strlen($psWhere)) { $psWhere .= " AND "; } $psWhere .= " p.selection = ".$s_selection; }
	if (strlen($s_term)) {
		if (strlen($psWhere)) { $psWhere .= " AND "; }
		$psWhere .= " (p.style_name LIKE '%".$s_term."%' OR cat.name LIKE '%".$s_term."%' OR br.name LIKE '%".$s_term."%' OR sel.name LIKE '%".$s_term."%' OR cl.name LIKE '%".$s_term."%') ";
	}

	if (!strlen($psWhere)) { $psWhere = " p.pid = 0"; }

	$limit_start = ($ppg - 1) * $pricing_per_page;

	$psSql = sprintf("SELECT SQL_CALC_FOUND_ROWS p.*, cat.name as category_name, br.name as brand_name, sel.name as selection_name, cl.name as colour_name FROM %swps_pricing p 
	LEFT JOIN %sterms cat ON cat.term_id = p.category
	LEFT JOIN %sterms br ON br.term_id = p.brand
	LEFT JOIN %sterms sel ON sel.term_id = p.selection
	LEFT JOIN %sterms cl ON cl.term_id = p.colour
	WHERE %s
	ORDER BY cat.name, br.name, sel.name, cl.name, original_price LIMIT %s, %s", $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $psWhere, $limit_start, $pricing_per_page);
	$pricing_records = $wpdb->get_results($psSql);
	$pricing_records_total = $wpdb->get_var("SELECT FOUND_ROWS()");
	$pricing_pages = ceil($pricing_records_total / $pricing_per_page);
	?>
	<div class="pricing-details" style="display:none;">
		<div class="pricing-details-data"></div>
		<div class="pricing-details-button" style="clear:both;">
			<input type="button" value="Back" onclick="tlc_pricing_details_hide()">
		</div>
	</div>
	<table class="pricing-search-results">
		<tr>
			<td class="head pic">Picture</td>
			<td class="head cat">Category</td>
			<td class="head brand">Brand</td>
			<td class="head style">Style</td>
			<td class="head cond">Condition</td>
			<td class="head colour">Colour</td>
			<td class="head metal">Metal</td>
			<td class="head mater">Material</td>
			<td class="head movem">Movement</td>
			<td class="head notes">Link</td>
			<td class="head oprice">Origin</td>
			<td class="head hprice">High</td>
			<td class="head lprice">Low</td>
			<td class="head sel">Select</td>
		</tr>
		<?php if ($pricing_records) {
			foreach($pricing_records as $pricing_record) {
				$original_price = sellers_currency_price($pricing_record->original_price);
				$high_price = sellers_currency_price($pricing_record->high_price);
				$low_price = sellers_currency_price($pricing_record->low_price);
				$includes_box = sellers_currency_price($pricing_record->includes_box);
				$includes_invoice = sellers_currency_price($pricing_record->includes_invoice);
				$includes_dustbag = sellers_currency_price($pricing_record->includes_dustbag);
				$includes_card = sellers_currency_price($pricing_record->includes_card);
				$includes_booklet = sellers_currency_price($pricing_record->includes_booklet);
				$includes_packaging = sellers_currency_price($pricing_record->includes_packaging);
			?>
		<tr style="cursor:pointer;">
			<td><?php if (strlen($pricing_record->photo)) { ?><a href="<?php echo get_post_thumb($pricing_record->photo, 800, 800); ?>" target="_blank"><img src="<?php echo get_post_thumb($pricing_record->photo, 60, 60, true); ?>" title="Large picture"></a><?php } else { ?><img src="<?php bloginfo('template_url'); ?>/images/pricing-no-pic-60.jpg"><?php } ?></td>
			<td onclick="tlc_pricing_details_show('<?php echo $pricing_record->pid; ?>');"><?php echo $pricing_record->category_name; ?></td>
			<td onclick="tlc_pricing_details_show('<?php echo $pricing_record->pid; ?>');"><?php echo $pricing_record->brand_name; ?></td>
			<td onclick="tlc_pricing_details_show('<?php echo $pricing_record->pid; ?>');"><?php echo $pricing_record->style_name; ?></td>
			<td onclick="tlc_pricing_details_show('<?php echo $pricing_record->pid; ?>');"><?php echo $pricing_record->selection_name; ?></td>
			<td onclick="tlc_pricing_details_show('<?php echo $pricing_record->pid; ?>');"><?php echo $pricing_record->colour_name; ?></td>
			<td onclick="tlc_pricing_details_show('<?php echo $pricing_record->pid; ?>');"><?php echo str_replace('|', ', ', $pricing_record->metal); ?></td>
			<td onclick="tlc_pricing_details_show('<?php echo $pricing_record->pid; ?>');"><?php echo str_replace('|', ', ', $pricing_record->material); ?></td>
			<td onclick="tlc_pricing_details_show('<?php echo $pricing_record->pid; ?>');"><?php echo str_replace('|', ', ', $pricing_record->movement); ?></td>
			<td><?php if (strlen($pricing_record->notes)) { if (strpos($pricing_record->notes, 'http') !== false) { echo '<a href="'.$pricing_record->notes.'" target="_blank" title="'.$pricing_record->notes.'"><img src="'.get_bloginfo('template_url').'/images/view-icon.png"></a>'; } else { echo $pricing_record->notes; } } ?></td>
			<td onclick="tlc_pricing_details_show('<?php echo $pricing_record->pid; ?>');"><?php echo format_price($original_price); ?></td>
			<td onclick="tlc_pricing_details_show('<?php echo $pricing_record->pid; ?>');"><?php echo format_price($high_price); ?></td>
			<td onclick="tlc_pricing_details_show('<?php echo $pricing_record->pid; ?>');"><?php echo format_price($low_price); ?></td>
			<td><input type="button" value="Select" onclick="tlc_set_pd_qprice(<?php echo $pricing_record->pid; ?>, <?php echo $high_price; ?>, <?php echo $low_price; ?>);">
				<div class="pricing-details-<?php echo $pricing_record->pid; ?>" style="display:none;">
					<div class="details-column" style="width:270px; float:left;">
						<table style="width:auto;">
							<tr>
								<td width="80"><strong>Category:</strong></td>
								<td><?php echo $pricing_record->category_name; ?></td>
							</tr>
							<tr>
								<td><strong>Brand:</strong></td>
								<td><?php echo $pricing_record->brand_name; ?></td>
							</tr>
							<tr>
								<td><strong>Style Name:</strong></td>
								<td><?php echo $pricing_record->style_name; ?></td>
							</tr>
							<tr>
								<td><strong>Condition:</strong></td>
								<td><?php echo $pricing_record->selection_name; ?></td>
							</tr>
							<tr>
								<td><strong>Colour:</strong></td>
								<td><?php echo $pricing_record->colour_name; ?></td>
							</tr>
							<tr>
								<td><strong>Metal:</strong></td>
								<td><?php echo str_replace('|', ', ', $pricing_record->metal); ?></td>
							</tr>
							<tr>
								<td><strong>Material:</strong></td>
								<td><?php echo str_replace('|', ', ', $pricing_record->material); ?></td>
							</tr>
							<tr>
								<td><strong>Movement:</strong></td>
								<td><?php echo str_replace('|', ', ', $pricing_record->movement); ?></td>
							</tr>
						</table>
					</div>
					<div class="details-column" style="width:270px; float:left; margin-left:20px;">
						<table style="width:auto;">
							<tr>
								<td width="155"><strong>Original Price:</strong></td>
								<td><?php echo format_price($original_price, true); ?></td>
							</tr>
							<tr>
								<td><strong>High Price:</strong></td>
								<td><?php echo format_price($high_price, true); ?></td>
							</tr>
							<tr>
								<td><strong>Low Price:</strong></td>
								<td><?php echo format_price($low_price, true); ?></td>
							</tr>
							<tr>
								<td><strong>Original Box Inc:</strong></td>
								<td><?php echo format_price($includes_box, true); ?></td>
							</tr>
							<tr>
								<td><strong>Original Invoice Inc:</strong></td>
								<td><?php echo format_price($includes_invoice, true); ?></td>
							</tr>
							<tr>
								<td><strong>Original Brand Authenticity Card Inc:</strong></td>
								<td><?php echo format_price($includes_card, true); ?></td>
							</tr>
							<tr>
								<td><strong>Info Booklet Inc:</strong></td>
								<td><?php echo format_price($includes_booklet, true); ?></td>
							</tr>
							<tr>
								<td><strong>Original Dustbag Inc:</strong></td>
								<td><?php echo format_price($includes_dustbag, true); ?></td>
							</tr>
							<tr>
								<td><strong>LuxCloset Packaging Inc:</strong></td>
								<td><?php echo format_price($includes_packaging, true); ?></td>
							</tr>
						</table>
					</div>
					<div class="details-column" style="width:270px; float:left; margin-left:20px;">
						<table style="width:auto;">
							<tr>
								<td width="50"><strong>Notes:</strong></td>
								<td><?php if (strlen($pricing_record->notes)) { if (strpos($pricing_record->notes, 'http') !== false) { echo '<a href="'.$pricing_record->notes.'" target="_blank">'.$pricing_record->notes.'</a>'; } else { echo $pricing_record->notes; } } ?></td>
							</tr>
							<tr>
								<td valign="top"><strong>Photo:</strong></td>
								<td><?php if (strlen($pricing_record->photo)) { ?><a href="<?php echo get_post_thumb($pricing_record->photo, 800, 800); ?>" target="_blank"><img src="<?php echo get_post_thumb($pricing_record->photo, 150, 150, true); ?>" title="Large picture"></a><?php } else { ?><img src="<?php bloginfo('template_url'); ?>/images/pricing-no-pic-60.jpg"><?php } ?></td>
							</tr>
						</table>
					</div>
				</div>
			</td>
		</tr>
		<?php } ?>
		<?php if ($pricing_pages > 1) { ?>
			<tr>
				<td colspan="14" style="text-align:center;" class="pricing-pager">
					<?php for($p=1; $p<=$pricing_pages; $p++) { ?>
						<a href="#<?php echo $p; ?>" onclick="tlc_pricing_pager(<?php echo $p; ?>); return false;" style="margin-right:4px;<?php if ($p == $ppg) { echo 'color:#000;font-weight:bold;'; } ?>"><?php echo $p; ?></a>
					<?php } ?>
				</td>
			</tr>
		<?php } ?>
		<?php } else { ?>
		<tr>
			<td colspan="14">Nothing found. <a href="#add-new-item" onclick="tlc_show_add_pricing(); return false;">Add New Item</a></td>
		</tr>
		<?php } ?>
	</table>
<?php
}

function sellers_get_category_options($tp) {
	global $OPTION;
	$options = array();
	$option_list = preg_split('/'.chr(10).'/', $OPTION['wps_'.$tp.'_list']);
	if (count($option_list) > 0) {
		foreach($option_list as $lv) { $lv = str_replace(chr(13), '', $lv);
			if (strlen($lv)) {
				$options[] = $lv;
			}
		}
	}
	return $options;
}

function profreseller_pricing_database() {
	global $wpdb;
	$s_category = $_POST['s_category'];
	$s_brand = $_POST['s_brand'];
	$s_selection = $_POST['s_selection'];
	$s_colour = $_POST['s_colour'];
	$s_includes = explode(";", $_POST['s_includes']);

	$psWhere = "";
	if ($s_category > 0) { if (strlen($psWhere)) { $psWhere .= " AND "; } $psWhere .= " p.category = ".$s_category; }
	if (strlen($s_brand)) { if (strlen($psWhere)) { $psWhere .= " AND "; } $psWhere .= " p.brand = ".$s_brand; }
	if (strlen($s_selection)) { if (strlen($psWhere)) { $psWhere .= " AND "; } $psWhere .= " p.selection = ".$s_selection; }
	if (strlen($s_colour)) { if (strlen($psWhere)) { $psWhere .= " AND "; } $psWhere .= " p.colour = ".$s_colour; }

	if (!strlen($psWhere)) { $psWhere = " p.pid = 0"; }

	$psSql = sprintf("SELECT p.*, cat.name as category_name, br.name as brand_name, sel.name as selection_name, cl.name as colour_name FROM %swps_pricing p 
	LEFT JOIN %sterms cat ON cat.term_id = p.category
	LEFT JOIN %sterms br ON br.term_id = p.brand
	LEFT JOIN %sterms sel ON sel.term_id = p.selection
	LEFT JOIN %sterms cl ON cl.term_id = p.colour
	WHERE %s
	ORDER BY cat.name, br.name, sel.name, cl.name, original_price", $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $psWhere);
	$pricing_records = $wpdb->get_results($psSql);
	?>
	<table>
		<tr>
			<td class="head pic">Picture</td>
			<td class="head cat">Category</td>
			<td class="head brand">Brand</td>
			<td class="head cond">Condition</td>
			<td class="head colour">Colour</td>
			<td class="head hprice">High Price</td>
			<td class="head lprice">Low Price</td>
		</tr>
		<?php if ($pricing_records) {
			foreach($pricing_records as $pricing_record) {
				$high_price = $pricing_record->high_price;
				$low_price = $pricing_record->low_price;

				if (count($s_includes)) {
					foreach($s_includes as $s_include) {
						$fld = 'includes_'.$s_include;
						$incl_val = $pricing_record->$fld;
						$high_price = $high_price + $incl_val;
						$low_price = $low_price + $incl_val;
					}
				}

				$high_price = sellers_currency_price($high_price);
				$low_price = sellers_currency_price($low_price);
			?>
		<tr>
			<td><?php if (strlen($pricing_record->photo)) { ?><a href="<?php echo get_post_thumb($pricing_record->photo, 800, 800); ?>" target="_blank"><img src="<?php echo get_post_thumb($pricing_record->photo, 60, 60, true); ?>" title="Large picture"></a><?php } else { ?><img src="<?php bloginfo('template_url'); ?>/images/pricing-no-pic-60.jpg"><?php } ?></td>
			<td><?php echo $pricing_record->category_name; ?></td>
			<td><?php echo $pricing_record->brand_name; ?></td>
			<td><?php echo $pricing_record->selection_name; ?></td>
			<td><?php echo $pricing_record->colour_name; ?></td>
			<td><?php echo format_price($high_price, true); ?></td>
			<td><?php echo format_price($low_price, true); ?></td>
		</tr>
		<?php
			}
		} else { ?>
		<tr>
			<td colspan="8">Nothing found.</td>
		</tr>
		<?php } ?>
	</table>
<?php
}

// alerts functions
add_action('init', 'alerts_init');
function alerts_init() {
	global $wpdb, $OPTION, $current_user;
	if (strlen($_POST['AlertsAction'])) {
		$user_email = $_POST['follow_brands_email'];
		if ($current_user->ID > 0) {
			$user_email = $current_user->user_email;
		}
		switch ($_POST['AlertsAction']) {
			case "create_alert":
				$ca_type = $_POST['ca_type'];
				$ca_value = trim($_POST['ca_value']);
				$ca_ajax = $_POST['ca_ajax'];

				$act = 'none';
				if ($ca_type == 1) { // create alert button
					$ca_category = $_POST['ca_category'];
					$ca_brand = $_POST['ca_brand'];
					$ca_colour = $_POST['ca_colour'];
					if (strlen($ca_category)) {
						$ca_value = '{ct:'.$ca_category.'}';
					}
					if (strlen($ca_brand)) {
						if (strlen($ca_value)) { $ca_value .= ';'; }
						$ca_value .= '{br:'.$ca_brand.'}';
					}
					if (strlen($ca_colour)) {
						if (strlen($ca_value)) { $ca_value .= ';'; }
						$ca_value .= '{cl:'.$ca_colour.'}';
					}
					if (strlen($ca_value)) {
						$alert_id = $wpdb->get_var(sprintf("SELECT alert_id FROM %swps_user_alerts WHERE user_id = %s AND type = %s AND value = '%s'", $wpdb->prefix, $current_user->ID, $ca_type, $ca_value));
						if (!$alert_id) {
							$act = 'insert';
						}
					}
				} else if ($ca_type == 2) { // it bags
					if (strlen($ca_value)) {
						$cavalues = explode(";", $ca_value);
						$ca_value = '';
						foreach($cavalues as $cavalue) {
							if (strlen($ca_value)) { $ca_value .= ';'; }
							$ca_value .= '{'.$cavalue.'}';
						}
					}
					$alert_id = $wpdb->get_var(sprintf("SELECT alert_id FROM %swps_user_alerts WHERE user_id = %s AND type = %s", $wpdb->prefix, $current_user->ID, $ca_type));
					if ($alert_id) {
						$act = 'update';
					} else {
						$act = 'insert';
					}
				} else if ($ca_type == 3) { // top brands
					$alert_id = $wpdb->get_var(sprintf("SELECT alert_id FROM %swps_user_alerts WHERE user_email = '%s' AND type = %s", $wpdb->prefix, $user_email, $ca_type));
					if (strlen($ca_value)) {
						$ca_value = '{'.str_replace(',', '};{', $ca_value).'}';
						if ($alert_id) {
							if ($_POST['ca_follow'] == 'true') {
								$alert_value = $wpdb->get_var(sprintf("SELECT value FROM %swps_user_alerts WHERE alert_id = %s", $wpdb->prefix, $alert_id));
								if (strpos($alert_value, $ca_value) !== false) {
									$ca_value = $alert_value;
								} else {
									$ca_value = $alert_value.';'.$ca_value;
								}
							}
							$act = 'update';
						} else {
							$act = 'insert';
						}
					} else {
						$act = 'delete';
					}
				} else if ($ca_type == 4) { // search term
					if (strlen($ca_value)) {
						$alert_id = $wpdb->get_var(sprintf("SELECT alert_id FROM %swps_user_alerts WHERE user_id = %s AND type = %s AND value = '%s'", $wpdb->prefix, $current_user->ID, $ca_type, $ca_value));
						if (!$alert_id) {
							$act = 'insert';
						}
					}
				}

				if ($act == 'insert') {
					$insert = array();
					$insert['user_id'] = $current_user->ID;
					$insert['user_email'] = $user_email;
					$insert['type'] = $ca_type;
					$insert['value'] = $ca_value;
					$wpdb->insert($wpdb->prefix."wps_user_alerts", $insert);
				} else if ($act == 'update' && $alert_id) {
					$update = array();
					$update['value'] = $ca_value;
					$wpdb->update($wpdb->prefix."wps_user_alerts", $update, array('alert_id' => $alert_id));
				} else if ($act == 'delete' && $alert_id) {
					$wpdb->query(sprintf("DELETE FROM %swps_user_alerts WHERE alert_id = %s", $wpdb->prefix, $alert_id));
				}
				if ($ca_ajax == 'true') { exit; }
			break;
			case "remove_my_searches_alert":
				$alert_id = $_POST['alert_id'];
				$wpdb->query(sprintf("DELETE FROM %swps_user_alerts WHERE alert_id = %s", $wpdb->prefix, $alert_id));
				exit;
			break;
			case "get_login_encodedurl":
				$url = $_POST['url'];
				echo get_permalink($OPTION['wps_account_login_page']).'?redirect_to='.urlencode($url);
				exit;
			break;
		}
	}
}

add_action('wp', 'alerts_notifications_cron_job');
add_action('alerts_notifications_cron', 'alerts_send_notifications');
function alerts_notifications_cron_job() {
	if (!wp_next_scheduled('alerts_notifications_cron')) {
		wp_schedule_event(mktime(6, 0, 0, date("m"), date("d"), date("Y")), 'daily', 'alerts_notifications_cron');
	}
}

function alerts_send_notifications() { // Alerts Notifications
	global $wpdb, $OPTION;

	$anotifications = array();
	$ancd = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
	$alerts_notifications_cron_date = get_option("alerts_notifications_cron_date");

	if ($alerts_notifications_cron_date != $ancd || $_GET['notify'] == 'send') {
		$subject = stripcslashes($OPTION['wps_alerts_notification_subject']);

		$wpdb->query(sprintf("DELETE FROM %swps_user_alerts_temp", $wpdb->prefix)); // clear temp alert posts table
		$added_posts = $wpdb->get_results(sprintf("SELECT p.* FROM %sposts p LEFT JOIN %spostmeta pm ON pm.post_id = p.ID WHERE p.post_type = 'post' AND p.post_status = 'publish' AND pm.meta_key = 'alert_send' AND pm.meta_value = '1' ORDER BY ID", $wpdb->prefix, $wpdb->prefix));
		echo '- found '.count($added_posts).' new items<br>';
		if ($added_posts) {
			$users_search_keys_alerts = $wpdb->get_results(sprintf("SELECT * FROM %swps_user_alerts WHERE type = 4 ORDER BY alert_id DESC", $wpdb->prefix, $wpdb->prefix));
			$aposts = array();
			foreach($added_posts as $added_post) {
				$post_categories = wp_get_post_terms($added_post->ID, 'category');
				$post_brands = wp_get_post_terms($added_post->ID, 'brand');
				$post_colours = wp_get_post_terms($added_post->ID, 'colour');
				$post_prices = wp_get_post_terms($added_post->ID, 'price');
				$post_selections = wp_get_post_terms($added_post->ID, 'selection');
				$post_sizes = wp_get_post_terms($added_post->ID, 'size');
				$post_ring_sizes = wp_get_post_terms($added_post->ID, 'ring-size');
				$post_clothes_sizes = wp_get_post_terms($added_post->ID, 'clothes-size');

				// it bags (type = 2)
				if ($post_brands) {
					foreach($post_brands as $post_brand) {
						$users_it_bags_alerts = $wpdb->get_results(sprintf("SELECT * FROM %swps_user_alerts WHERE type = 2 AND value LIKE '%s' ORDER BY alert_id DESC", $wpdb->prefix, "%{".$post_brand->term_id."-".$added_post->post_title."}%"));
						if ($users_it_bags_alerts) {
							foreach($users_it_bags_alerts as $users_it_bags_alert) {
								$user_email = $users_it_bags_alert->user_email;
								$anotifications[$user_email][$added_post->ID] = $added_post;
							}
						}
					}
				}

				// top brands (type = 3)
				if ($post_brands) {
					foreach($post_brands as $post_brand) {
						$users_top_brands_alerts = $wpdb->get_results(sprintf("SELECT * FROM %swps_user_alerts WHERE type = 3 AND value LIKE '%s' ORDER BY alert_id DESC", $wpdb->prefix, "%{".$post_brand->term_id."}%"));
						if ($users_top_brands_alerts) {
							foreach($users_top_brands_alerts as $users_top_brands_alert) {
								$user_email = $users_top_brands_alert->user_email;
								$anotifications[$user_email][$added_post->ID] = $added_post;
							}
						}
					}
				}

				// search terms (type = 4)
				if ($users_search_keys_alerts) {
					foreach($users_search_keys_alerts as $users_search_keys_alert) {
						$sval = strtolower($users_search_keys_alert->value);
						$user_email = $users_search_keys_alert->user_email;
						if (strpos(strtolower($added_post->post_title), $sval) !== false || strpos(strtolower($added_post->post_content), $sval) !== false) {
							$anotifications[$user_email][$added_post->ID] = $added_post;
						}
					}
				}
				$insert = array(
						'br' => $post_brands[0]->term_id,
						'cl' => $post_colours[0]->term_id,
						'pr' => $post_prices[0]->term_id,
						'sl' => $post_selections[0]->term_id,
						'sz' => $post_sizes[0]->term_id,
						'rs' => $post_ring_sizes[0]->term_id,
						'cs' => $post_clothes_sizes[0]->term_id,
						'post_id' => $added_post->ID,
						'post' => serialize($added_post)
					);
				if ($post_categories) {
					$cnmb = 1;
					foreach($post_categories as $pcategory) {
						if ($cnmb < 6) {
							$insert['ct'.$cnmb] = $pcategory->term_id;
						}
						$cnmb++;
					}
				}
				$wpdb->insert($wpdb->prefix.'wps_user_alerts_temp', $insert);
				delete_post_meta($added_post->ID, 'alert_send');
			}
			// created requests (type = 1)
			$users_search_filter_alerts = $wpdb->get_results(sprintf("SELECT * FROM %swps_user_alerts WHERE type = 1 ORDER BY alert_id DESC", $wpdb->prefix));
			if ($users_search_filter_alerts) {
				foreach ($users_search_filter_alerts as $users_search_filter_alert) {
					$user_email = $users_search_filter_alert->user_email;
					$values = explode(';', $users_search_filter_alert->value);
					$where  = alerts_get_notification_where2($values);
					if (strlen($where)) {
						$tdatas = $wpdb->get_results(sprintf("SELECT * FROM %swps_user_alerts_temp WHERE %s", $wpdb->prefix, $where));
						if ($tdatas) {
							foreach($tdatas as $tdata) {
								$anotifications[$user_email][$tdata->post_id] = unserialize($tdata->post);
							}
						}
					}
				}
			}
			$wpdb->query(sprintf("DELETE FROM %swps_user_alerts_temp", $wpdb->prefix)); // clear temp alert posts table

			// send alert emails
			if (count($anotifications)) {
				foreach($anotifications as $user_email => $post_items) {
					echo '- sent '.count($post_items).' new items to user: '.$user_email.'<br>';
					$body = alerts_notifications_html($user_email, $post_items);
					NWS_send_email($user_email, $subject, $body);
				}
			}
		}
		update_option("alerts_notifications_cron_date", $ancd);
	}
}

function alerts_get_notification_where2($params) {
	$where = "";
	$grouped = array();
	foreach($params as $param) {
		$param = str_replace(array('{','}'), '', $param);
		$flks = explode(':', $param);
		$grouped[$flks[0]][] = $flks[1];
	}
	foreach($grouped as $gk => $gvals) {
		if (strlen($where)) { $where .= " AND "; }
		if ($gk == 'ct') {
			$where .= "(";
			for($c=1; $c<=5; $c++) {
				$where .= $or."ct".$c." IN (".implode(',', $gvals).")";
				$or = " OR ";
			}
			$where .= ")";
		} else {
			$where .= $gk." IN (".implode(',', $gvals).")";
		}
	}
	return $where;
}

function alerts_send_notifications2() { // Alerts Notifications
	global $wpdb, $OPTION;

	$ancd = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
	$alerts_notifications_cron_date = get_option("alerts_notifications_cron_date");

	if ($alerts_notifications_cron_date != $ancd || $_GET['notify'] == 'send') {
		$subject = stripcslashes($OPTION['wps_alerts_notification_subject']);

		$added_posts = $wpdb->get_results(sprintf("SELECT p.* FROM %sposts p LEFT JOIN %spostmeta pm ON pm.post_id = p.ID WHERE p.post_type = 'post' AND p.post_status = 'publish' AND pm.meta_key = 'alert_send' AND pm.meta_value = '1' ORDER BY ID", $wpdb->prefix, $wpdb->prefix));
		echo '- found '.count($added_posts).' new items<br>';
		if ($added_posts) {
			$users_search_keys_alerts = $wpdb->get_results(sprintf("SELECT * FROM %swps_user_alerts WHERE type = 4 ORDER BY alert_id DESC", $wpdb->prefix, $wpdb->prefix));
			$users_it_bags_alerts = $wpdb->get_results(sprintf("SELECT * FROM %swps_user_alerts WHERE type = 2 ORDER BY alert_id DESC", $wpdb->prefix, $wpdb->prefix));
			$anotifications = array();
			foreach($added_posts as $added_post) {
				$post_categories = wp_get_post_terms($added_post->ID, 'category');
				$post_brands = wp_get_post_terms($added_post->ID, 'brand');
				$post_colours = wp_get_post_terms($added_post->ID, 'colour');
				$post_prices = wp_get_post_terms($added_post->ID, 'price');
				$post_selections = wp_get_post_terms($added_post->ID, 'selection');
				$post_sizes = wp_get_post_terms($added_post->ID, 'size');
				$post_ring_sizes = wp_get_post_terms($added_post->ID, 'ring-size');
				$post_clothes_sizes = wp_get_post_terms($added_post->ID, 'clothes-size');

				// created requests (type = 1)
				$where  = alerts_get_notification_where($post_categories, 'ct');
				$where .= alerts_get_notification_where($post_brands, 'br');
				$where .= alerts_get_notification_where($post_colours, 'cl');
				$where .= alerts_get_notification_where($post_prices, 'pr');
				$where .= alerts_get_notification_where($post_selections, 'sl');
				$where .= alerts_get_notification_where($post_sizes, 'sz');
				$where .= alerts_get_notification_where($post_ring_sizes, 'rs');
				$where .= alerts_get_notification_where($post_clothes_sizes, 'cs');
				var_dump($where); exit;

				if (strlen($where)) {
					$users_search_filter_alerts = $wpdb->get_results(sprintf("SELECT * FROM %swps_user_alerts WHERE type = 1 %s ORDER BY alert_id DESC", $wpdb->prefix, $where));
					if ($users_search_filter_alerts) {
						foreach($users_search_filter_alerts as $users_search_filter_alert) {
							$user_email = $users_search_filter_alert->user_email;
							$anotifications[$user_email][$added_post->ID] = $added_post;
						}
					}
				}

				// it bags (type = 2)
				if ($post_brands) {
					foreach($post_brands as $post_brand) {
						$users_it_bags_alerts = $wpdb->get_results(sprintf("SELECT * FROM %swps_user_alerts WHERE type = 2 AND value LIKE '%s' ORDER BY alert_id DESC", $wpdb->prefix, "%{".$post_brand->term_id."-".$added_post->post_title."}%"));
						if ($users_it_bags_alerts) {
							foreach($users_it_bags_alerts as $users_it_bags_alert) {
								$user_email = $users_it_bags_alert->user_email;
								$anotifications[$user_email][$added_post->ID] = $added_post;
							}
						}
					}
				}

				// top brands (type = 3)
				if ($post_brands) {
					foreach($post_brands as $post_brand) {
						$users_top_brands_alerts = $wpdb->get_results(sprintf("SELECT * FROM %swps_user_alerts WHERE type = 3 AND value LIKE '%s' ORDER BY alert_id DESC", $wpdb->prefix, "%{".$post_brand->term_id."}%"));
						if ($users_top_brands_alerts) {
							foreach($users_top_brands_alerts as $users_top_brands_alert) {
								$user_email = $users_top_brands_alert->user_email;
								$anotifications[$user_email][$added_post->ID] = $added_post;
							}
						}
					}
				}

				// search terms (type = 4)
				if ($users_search_keys_alerts) {
					foreach($users_search_keys_alerts as $users_search_keys_alert) {
						$sval = strtolower($users_search_keys_alert->value);
						$user_email = $users_search_keys_alert->user_email;
						if (strpos(strtolower($added_post->post_title), $sval) !== false || strpos(strtolower($added_post->post_content), $sval) !== false) {
							$anotifications[$user_email][$added_post->ID] = $added_post;
						}
					}
				}
				//delete_post_meta($added_post->ID, 'alert_send');
			}
			if (count($anotifications)) {
				foreach($anotifications as $user_email => $post_items) {
					echo '- sent '.count($post_items).' new items to user: '.$user_email.'<br>';
					$body = alerts_notifications_html($user_email, $post_items);
					NWS_send_email($user_email, $subject, $body);
				}
			}
		}
		update_option("alerts_notifications_cron_date", $ancd);
	}
}

function alerts_get_notification_where($post_terms, $pref) {
	$notification_where = '';
	if ($post_terms) {
		$notification_where .= " AND (";
		$or = '';
		foreach($post_terms as $post_term) {
			$notification_where .= $or . "value LIKE '%{".$pref.":".$post_term->term_id."}%'";
			$or = ' OR ';
		}
		$notification_where .= ") ";
	}
	return $notification_where;
}

function alerts_notifications_html($user_email, $post_items) {
	global $OPTION, $wpdb;
	$user_name = $user_email;
	$userdata = $wpdb->get_row(sprintf("SELECT * FROM %susers WHERE user_email = '%s'", $wpdb->prefix, $user_email));
	if ($userdata) {
		$user_name = $userdata->user_login;
	}
	$total_items = count($post_items);
	ob_start();
?>
	<table align="center" width="700" style="font-family:Arial,Tahoma,Verdana;font-size:14px;" border="0">
	  <tr>
		<td align="center"><a href="<?php bloginfo('url'); ?>/" title="<?php bloginfo('name'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/logo.png" border="0"></a></td>
	  </tr>
	  <tr>
		<td align="center"><hr></td>
	  </tr>
	  <tr>
		<td align="center"><strong>Hi <?php echo $user_name; ?>,</strong></td>
	  </tr>
	  <tr>
		<td align="center">New items matching your personalised alerts<br>To manage your alerts go to <a href="<?php echo get_permalink($OPTION['wps_account_my_alerts_page']); ?>">My Notifications</a>.</td>
	  </tr>
	  <tr>
		<td align="center"><hr></td>
	  </tr>
	  <tr>
		<td align="center">
			<table cellpadding="0" cellspacing="10" style="font-family:Arial,Tahoma,Verdana;font-size:13px;" border="0">
			  <tr>
			    <?php $tr_nmb = 1;
			    foreach($post_items as $pid => $postdata) { $total_items--;
			      $post_thumb = get_product_thumb($pid, 156);
			      $price = get_post_meta($pid, 'price', true);
			      $new_price = get_post_meta($pid, 'new_price', true);
			      if ($new_price > 0) { $price = $new_price; }
			    ?>
			    <td align="center" valign="top">
				  <?php if ($post_thumb) { ?><div style="width:156px;height:160px;"><a href="<?php echo get_permalink($pid); ?>" title="<?php echo $postdata->post_title; ?>"><img src="<?php echo $post_thumb; ?>" alt="<?php echo $postdata->post_title; ?>" border="0"></a></div><?php } ?>
				  <a href="<?php echo get_permalink($pid); ?>" style="text-decoration:none;color:#000;"><strong><?php echo $postdata->post_title; ?></strong><br>
				  $<?php echo format_price($price); ?></a>
				</td>
				<?php if ($tr_nmb == 4 && $total_items > 0) { $tr_nmb = 0; ?>
			  </tr>
			  <tr>
			    <?php } ?>
			    <?php $tr_nmb++; } ?>
			  </tr>
			</table>
		</td>
	  </tr>
	  <tr>
		<td align="center"><hr></td>
	  </tr>
	  <tr>
		<td align="center">&copy; <?php echo date('Y'); ?>. <a href="<?php bloginfo('url'); ?>/" style="text-decoration:none;color:#000;"><?php bloginfo('name'); ?></a> | <?php _e('All Rights Reserved','wpShop'); ?></td>
	  </tr>
	</table>
<?php
	$notifications_html = ob_get_contents();
	ob_end_clean();
	return $notifications_html;
}

// user additional fields
add_action('show_user_profile', 'user_profile_additional_fields');
add_action('edit_user_profile', 'user_profile_additional_fields');
add_action('profile_update', 'user_profile_additional_fields_save'); // save user additional fields

function user_profile_additional_fields() {
	$uid = user_profile_additional_fields_get_user_id();
	$remove_charge = get_user_meta($uid, 'remove_charge', true);
	$seller_item_id_prefix = get_user_meta($uid, 'seller_item_id_prefix', true);
	$userdata = get_userdata($uid);
?>
	<h3><?php _e("Additional Options", "gd-complaints-system"); ?></h3>
	<table class="form-table" id="clonehere">
		<tr>
		  <th><?php _e("Remove the 5% charge", "gd-complaints-system"); ?>:</th>
		  <td><input type="checkbox" name="remove_charge" value="1"<?php if ($remove_charge == 1) { echo ' CHECKED'; }?> /></td>
		</tr>
		<?php if (in_array('profseller', $userdata->roles) || in_array('administrator', $userdata->roles)) { ?>
		<tr>
		  <th><?php _e("Seller Item_ID Prefix", "gd-complaints-system"); ?>:</th>
		  <td><input type="text" name="seller_item_id_prefix" value="<?php echo $seller_item_id_prefix; ?>"></td>
		</tr>
		<?php } ?>
	</table>
<?php
}

function user_profile_additional_fields_save() {
	$uid = user_profile_additional_fields_get_user_id();
	update_user_meta($uid, 'remove_charge', $_POST['remove_charge']);
	update_user_meta($uid, 'seller_item_id_prefix', $_POST['seller_item_id_prefix']);
}

function user_profile_additional_fields_get_user_id() {
	global $user_ID;
	$id = $_GET['user_id'];
	if (!strlen($id) && isset($_POST['user_id'])) { $id = $_POST['user_id']; }
	if (preg_match('&profile.php&', $_SERVER['REQUEST_URI'])) {
		$id = $user_ID;
	}
	return $id;
}

$recently_where = false;
function recently_added_where($where) {
	global $recently_where;
	if ($recently_where) {
		$where .= " AND wp_posts.inventory > 0 ";
		$recently_where = false;
	}
	return $where;
}

function tocsv($val, $sep = ',') {
	$val = str_replace('"', '\'', $val);
	$val = str_replace('%20', ' ', $val);
	return '"'.$val.'"'.$sep;
}

function nws_get_tax_name($post_id, $tax) {
	$post_taxs = wp_get_post_terms($post_id, $tax);
	if ($post_taxs) {
		foreach($post_taxs as $post_tax) {
			return $post_tax->name;
		}
	}
}

function check_logged_in($page = '') {
	global $OPTION;
	if (!is_user_logged_in()) {
		if (!strlen($page)) { $page = $_SERVER['REQUEST_URI']; }
		$redirect = get_permalink($OPTION['wps_account_login_page']).'?redirect_to='.urlencode($page);
		wp_redirect($redirect);
		wp_exit();
	}
}

// functions for order confirmation email
function email_billing_address($order) {
	$email_billing_address = $order['f_name'].' '.$order['l_name'].'<br>';
	$email_billing_address .= $order['street'].'<br>';
	$email_billing_address .= $order['state'].'<br>';
	$email_billing_address .= $order['town'].' '.$order['zip'].'<br>';
	$email_billing_address .= $order['country'].'<br>';
	if (strlen($order['telephone'])) {
		$email_billing_address .= 'Tel: '.$order['telephone'].'<br>';
	}
	return $email_billing_address;
}

function email_delivery_address($order) {
	global $wpdb;
	$email_delivery_address = '';

	$deliv_addr = $wpdb->get_row(sprintf("SELECT * FROM %swps_delivery_addr WHERE who = '%s'", $wpdb->prefix, $order['who']));
	if ($deliv_addr) {
		$email_delivery_address = $deliv_addr->f_name.' '.$deliv_addr->l_name.'<br>';
		$email_delivery_address .= $deliv_addr->street.'<br>';
		$email_delivery_address .= $deliv_addr->state.'<br>';
		$email_delivery_address .= $deliv_addr->town.' '.$deliv_addr->zip.'<br>';
		$email_delivery_address .= $deliv_addr->country.'<br>';
	}
	return $email_delivery_address;
}

function email_order_items_table($who) {
	global $wpdb, $OPTION;
	$summary_page_url = get_permalink($OPTION['wps_sellers_summary_page']).'?seller=';
	$email_order_items_table = '<table border="1"><tr><th>Qty</th><th>ID</th><th>Item</th><th>Unit</th><th>Total</th><th>Seller</th><th>Price</th></tr>';

	$order_items = $wpdb->get_results(sprintf("SELECT sc.*, p.post_author, u.user_login FROM %swps_shopping_cart sc LEFT JOIN %sposts p ON p.ID = sc.postID LEFT JOIN %susers u ON u.ID = p.post_author WHERE sc.who = '%s' ORDER BY sc.cid", $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $who));
	if ($order_items) {
		foreach($order_items as $order_item) {
			$item_seller = get_post_meta($order_item->postID, 'item_seller', true);
			$item_your_price = get_post_meta($order_item->postID, 'item_your_price', true);
			$email_order_items_table .= '<tr><td>'.$order_item->item_amount.'x</td><td>'.$order_item->item_id.'</td><td>'.$order_item->item_name.'</td><td>$'.format_price($order_item->item_price).'</td><td>$'.format_price($order_item->item_amount * $order_item->item_price).'</td><td>'.$order_item->user_login.'&nbsp;</td><td>$'.format_price($order_item->seller_price).'</td></tr>';
		}
	}
	$email_order_items_table .= '</table>';
	return $email_order_items_table;
}

function update_utm_params($type, $key) {
	global $wpdb;

	$params = array();
	$params['utm_source'] = trim($_REQUEST['utm_source']);
	$params['utm_medium'] = trim($_REQUEST['utm_medium']);
	$params['utm_campaign'] = trim($_REQUEST['utm_campaign']);
	$params['utm_content'] = trim($_REQUEST['utm_content']);
	$params['utm_term'] = trim($_REQUEST['utm_term']);

	switch ($type) {
		case "users":
			$wpdb->update($wpdb->prefix.'users', $params, array('ID' => $key));
		break;
		case "orders":
			$wpdb->update($wpdb->prefix.'wps_orders', $params, array('oid' => $key));
		break;
		case "posts":
			$wpdb->update($wpdb->prefix.'posts', $params, array('ID' => $key));
		break;
	}
}

function get_sc_item_category($item_id) {
	global $OPTION;

	$item_category = '';
	if (in_category($OPTION['wps_women_bags_category'], $item_id)) {
		$item_category = 'Women - Handbags';
	} else if (in_category($OPTION['wps_women_shoes_category'], $item_id)) {
		$item_category = 'Women - Shoes';
	} else if (in_category($OPTION['wps_women_watches_category'], $item_id)) {
		$item_category = 'Women - Watches';
	} else if (in_category($OPTION['wps_women_sunglasses_category'], $item_id)) {
		$item_category = 'Women - Sunglasses';
	} else if (in_category($OPTION['wps_women_jewelry_category'], $item_id)) {
		$item_category = 'Women - Jewelry';
	} else if (in_category($OPTION['wps_women_accessories_category'], $item_id)) {
		$item_category = 'Women - Accessories';
	} else if (in_category($OPTION['wps_women_clothes_category'], $item_id)) {
		$item_category = 'Women - Clothes';
	} else if (in_category($OPTION['wps_women_limited_edition_category'], $item_id)) {
		$item_category = 'Women - Limited Edition';
	} else if (in_category($OPTION['wps_men_bags_category'], $item_id)) {
		$item_category = 'Men - Handbags';
	} else if (in_category($OPTION['wps_men_shoes_category'], $item_id)) {
		$item_category = 'Men - Shoes';
	} else if (in_category($OPTION['wps_men_watches_category'], $item_id)) {
		$item_category = 'Men - Watches';
	} else if (in_category($OPTION['wps_men_sunglasses_category'], $item_id)) {
		$item_category = 'Men - Sunglasses';
	} else if (in_category($OPTION['wps_men_jewelry_category'], $item_id)) {
		$item_category = 'Men - Jewelry';
	} else if (in_category($OPTION['wps_men_accessories_category'], $item_id)) {
		$item_category = 'Men - Accessories';
	} else if (in_category($OPTION['wps_men_clothes_category'], $item_id)) {
		$item_category = 'Men - Clothes';
	} else if (in_category($OPTION['wps_men_limited_edition_category'], $item_id)) {
		$item_category = 'Men - Limited Edition';
	}

	return $item_category;
}

function wps_track_searches($svalue) {
	global $wpdb;
	$svalue = trim($svalue);
	$geo = new geoPlugin();
	$geo->locate();
	$location = $geo->countryCode;

	$insert = array();
	$insert["svalue"] = $svalue;
	$insert["sdate"] = current_time('mysql');
	$insert["slocation"] = $location;
	$wpdb->insert($wpdb->prefix."wps_searches", $insert);
}

function wps_get_countries() {
	return array(
		'AF'=>'AFGHANISTAN',
		'AL'=>'ALBANIA',
		'DZ'=>'ALGERIA',
		'AS'=>'AMERICAN SAMOA',
		'AD'=>'ANDORRA',
		'AO'=>'ANGOLA',
		'AI'=>'ANGUILLA',
		'AQ'=>'ANTARCTICA',
		'AG'=>'ANTIGUA AND BARBUDA',
		'AR'=>'ARGENTINA',
		'AM'=>'ARMENIA',
		'AW'=>'ARUBA',
		'AC'=>'ASCENSION ISLAND',
		'AU'=>'AUSTRALIA',
		'AT'=>'AUSTRIA',
		'AZ'=>'AZERBAIJAN',
		'BS'=>'BAHAMAS',
		'BH'=>'BAHRAIN',
		'BD'=>'BANGLADESH',
		'BB'=>'BARBADOS',
		'BY'=>'BELARUS',
		'BE'=>'BELGIUM',
		'BZ'=>'BELIZE',
		'BJ'=>'BENIN',
		'BM'=>'BERMUDA',
		'BT'=>'BHUTAN',
		'BO'=>'BOLIVIA',
		'BA'=>'BOSNIA AND HERZEGOWINA',
		'BW'=>'BOTSWANA',
		'BV'=>'BOUVET ISLAND',
		'BR'=>'BRAZIL',
		'IO'=>'BRITISH INDIAN OCEAN TERRITORY',
		'BN'=>'BRUNEI DARUSSALAM',
		'BG'=>'BULGARIA',
		'BF'=>'BURKINA FASO',
		'BI'=>'BURUNDI',
		'KH'=>'CAMBODIA',
		'CM'=>'CAMEROON',
		'CA'=>'CANADA',
		'CV'=>'CAPE VERDE',
		'KY'=>'CAYMAN ISLANDS',
		'CF'=>'CENTRAL AFRICAN REPUBLIC',
		'TD'=>'CHAD',
		'CL'=>'CHILE',
		'CN'=>'CHINA',
		'CX'=>'CHRISTMAS ISLAND',
		'CC'=>'COCOS (KEELING) ISLANDS',
		'CO'=>'COLOMBIA',
		'KM'=>'COMOROS',
		'CD'=>'CONGO THE DEMOCRATIC REPUBLIC OF THE',
		'CG'=>'CONGO',
		'CK'=>'COOK ISLANDS',
		'CR'=>'COSTA RICA',
		'CI'=>'COTE D\'IVOIRE',
		'HR'=>'CROATIA',
		'CU'=>'CUBA',
		'CY'=>'CYPRUS',
		'CZ'=>'CZECH REPUBLIC',
		'DK'=>'DENMARK',
		'DJ'=>'DJIBOUTI',
		'DM'=>'DOMINICA',
		'DO'=>'DOMINICAN REPUBLIC',
		'TP'=>'EAST TIMOR',
		'EC'=>'ECUADOR',
		'EG'=>'EGYPT',
		'SV'=>'EL SALVADOR',
		'GQ'=>'EQUATORIAL GUINEA',
		'ER'=>'ERITREA',
		'EE'=>'ESTONIA',
		'ET'=>'ETHIOPIA',
		'EU'=>'EUROPEAN UNION',
		'FK'=>'FALKLAND ISLANDS',
		'FO'=>'FAROE ISLANDS',
		'FJ'=>'FIJI',
		'FI'=>'FINLAND',
		'FX'=>'FRANCE METRO',
		'FR'=>'FRANCE',
		'GF'=>'FRENCH GUIANA',
		'PF'=>'FRENCH POLYNESIA',
		'TF'=>'FRENCH SOUTHERN TERRITORIES',
		'GA'=>'GABON',
		'GM'=>'GAMBIA',
		'GE'=>'GEORGIA',
		'DE'=>'GERMANY',
		'GH'=>'GHANA',
		'GI'=>'GIBRALTAR',
		'GR'=>'GREECE',
		'GL'=>'GREENLAND',
		'GD'=>'GRENADA',
		'GP'=>'GUADELOUPE',
		'GU'=>'GUAM',
		'GT'=>'GUATEMALA',
		'GG'=>'GUERNSEY',
		'GN'=>'GUINEA',
		'GW'=>'GUINEA-BISSAU',
		'GY'=>'GUYANA',
		'HT'=>'HAITI',
		'HM'=>'HEARD AND MC DONALD ISLANDS',
		'VA'=>'VATICAN CITY STATE',
		'HN'=>'HONDURAS',
		'HK'=>'HONG KONG',
		'HU'=>'HUNGARY',
		'IS'=>'ICELAND',
		'IN'=>'INDIA',
		'ID'=>'INDONESIA',
		'IR'=>'IRAN',
		'IQ'=>'IRAQ',
		'IE'=>'IRELAND',
		'IM'=>'ISLE OF MAN',
		'IL'=>'ISRAEL',
		'IT'=>'ITALY',
		'JM'=>'JAMAICA',
		'JP'=>'JAPAN',
		'JE'=>'JERSEY',
		'JO'=>'JORDAN',
		'KZ'=>'KAZAKHSTAN',
		'KE'=>'KENYA',
		'KI'=>'KIRIBATI',
		'KP'=>'KOREA',
		'KR'=>'KOREA',
		'KW'=>'KUWAIT',
		'KG'=>'KYRGYZSTAN',
		'LA'=>'LAO',
		'LV'=>'LATVIA',
		'LB'=>'LEBANON',
		'LS'=>'LESOTHO',
		'LR'=>'LIBERIA',
		'LY'=>'LIBYAN ARAB JAMAHIRIYA',
		'LI'=>'LIECHTENSTEIN',
		'LT'=>'LITHUANIA',
		'LU'=>'LUXEMBOURG',
		'MO'=>'MACAU',
		'MK'=>'MACEDONIA',
		'MG'=>'MADAGASCAR',
		'MW'=>'MALAWI',
		'MY'=>'MALAYSIA',
		'MV'=>'MALDIVES',
		'ML'=>'MALI',
		'MT'=>'MALTA',
		'MH'=>'MARSHALL ISLANDS',
		'MQ'=>'MARTINIQUE',
		'MR'=>'MAURITANIA',
		'MU'=>'MAURITIUS',
		'YT'=>'MAYOTTE',
		'MX'=>'MEXICO',
		'FM'=>'MICRONESIA',
		'MD'=>'MOLDOVA REPUBLIC OF',
		'MC'=>'MONACO',
		'MN'=>'MONGOLIA',
		'MS'=>'MONTSERRAT',
		'MA'=>'MOROCCO',
		'MZ'=>'MOZAMBIQUE',
		'MM'=>'MYANMAR',
		'ME'=>'Montenegro',
		'NA'=>'NAMIBIA',
		'NR'=>'NAURU',
		'NP'=>'NEPAL',
		'AN'=>'NETHERLANDS ANTILLES',
		'NL'=>'NETHERLANDS',
		'NC'=>'NEW CALEDONIA',
		'NZ'=>'NEW ZEALAND',
		'NI'=>'NICARAGUA',
		'NE'=>'NIGER',
		'NG'=>'NIGERIA',
		'NU'=>'NIUE',
		'AP'=>'NON-SPEC ASIA PAS LOCATION',
		'NF'=>'NORFOLK ISLAND',
		'MP'=>'NORTHERN MARIANA ISLANDS',
		'NO'=>'NORWAY',
		'OM'=>'OMAN',
		'PK'=>'PAKISTAN',
		'PW'=>'PALAU',
		'PS'=>'PALESTINA',
		'PA'=>'PANAMA',
		'PG'=>'PAPUA NEW GUINEA',
		'PY'=>'PARAGUAY',
		'PE'=>'PERU',
		'PH'=>'PHILIPPINES',
		'PN'=>'PITCAIRN',
		'PL'=>'POLAND',
		'PT'=>'PORTUGAL',
		'PR'=>'PUERTO RICO',
		'QA'=>'QATAR',
		'ZZ'=>'RESERVED',
		'RE'=>'REUNION',
		'RO'=>'ROMANIA',
		'RU'=>'RUSSIAN FEDERATION',
		'RW'=>'RWANDA',
		'KN'=>'SAINT KITTS AND NEVIS',
		'LC'=>'SAINT LUCIA',
		'VC'=>'SAINT VINCENT AND THE GRENADINES',
		'WS'=>'SAMOA',
		'SM'=>'SAN MARINO',
		'ST'=>'SAO TOME AND PRINCIPE',
		'SA'=>'SAUDI ARABIA',
		'SN'=>'SENEGAL',
		'SC'=>'SEYCHELLES',
		'SL'=>'SIERRA LEONE',
		'SG'=>'SINGAPORE',
		'SK'=>'SLOVAKIA',
		'SI'=>'SLOVENIA',
		'SB'=>'SOLOMON ISLANDS',
		'SO'=>'SOMALIA',
		'ZA'=>'SOUTH AFRICA',
		'GS'=>'SOUTH GEORGIA',
		'ES'=>'SPAIN',
		'LK'=>'SRI LANKA',
		'SH'=>'ST. HELENA',
		'PM'=>'ST. PIERRE AND MIQUELON',
		'SD'=>'SUDAN',
		'SR'=>'SURINAME',
		'SJ'=>'SVALBARD AND JAN MAYEN ISLANDS',
		'SZ'=>'SWAZILAND',
		'SE'=>'SWEDEN',
		'CH'=>'SWITZERLAND',
		'SY'=>'SYRIAN ARAB REPUBLIC',
		'CS'=>'SERBIA AND MONTENEGRO',
		'YU'=>'SERBIA AND MONTENEGRO',
		'RS'=>'Serbia',
		'TW'=>'TAIWAN',
		'TJ'=>'TAJIKISTAN',
		'TZ'=>'TANZANIA',
		'TH'=>'THAILAND',
		'TL'=>'TIMOR-LESTE',
		'TG'=>'TOGO',
		'TK'=>'TOKELAU',
		'TO'=>'TONGA',
		'TT'=>'TRINIDAD AND TOBAGO',
		'TN'=>'TUNISIA',
		'TR'=>'TURKEY',
		'TM'=>'TURKMENISTAN',
		'TC'=>'TURKS AND CAICOS ISLANDS',
		'TV'=>'TUVALU',
		'UG'=>'UGANDA',
		'UA'=>'UKRAINE',
		'AE'=>'UNITED ARAB EMIRATES',
		'GB'=>'UNITED KINGDOM',
		'UK'=>'UNITED KINGDOM',
		'UM'=>'UNITED STATES MINOR OUTLYING ISLANDS',
		'US'=>'UNITED STATES',
		'UY'=>'URUGUAY',
		'UZ'=>'UZBEKISTAN',
		'VU'=>'VANUATU',
		'VE'=>'VENEZUELA',
		'VN'=>'VIET NAM',
		'VG'=>'VIRGIN ISLANDS',
		'VI'=>'VIRGIN ISLANDS',
		'WF'=>'WALLIS AND FUTUNA ISLANDS',
		'EH'=>'WESTERN SAHARA',
		'YE'=>'YEMEN',
		'ZM'=>'ZAMBIA',
		'ZW'=>'ZIMBABWE',
		'AX'=>'ALAND ISLANDS',
		'MF'=>'SAINT MARTIN'
	);
}

// Custom images ALT and Title attributes
add_action('add_meta_boxes', 'nws_add_custom_meta_boxes');
function nws_add_custom_meta_boxes(){
	add_meta_box("post-custom-images-attributes", "Custom Images Attributes", "post_custom_images_attributes", "post", "normal", "high");
	add_meta_box("page-seo-meta-title", "SEO Meta Title", "page_seo_meta_title", "page", "normal", "high");
}

function post_custom_images_attributes(){
	global $post, $wpdb;
	$general_images_title = get_post_meta($post->ID, 'general_images_title', true);
	$general_images_alt = get_post_meta($post->ID, 'general_images_alt', true);
?>
	<input type="hidden" name="post_cia" value="true" />
	<div style="margin-left:10px;">
		General Images Title:
		<input type="text" name="general_images_title" value="<?php echo $general_images_title; ?>" style="width:99%;"><br />
		General Images Alt:
		<input type="text" name="general_images_alt" value="<?php echo $general_images_alt; ?>" style="width:99%;">
	</div>
<?php	
	$post_attachs = $wpdb->get_results(sprintf("SELECT * FROM %sposts WHERE post_parent = %s AND post_type = 'attachment' ORDER BY menu_order, ID", $wpdb->prefix, $post->ID));
	if ($post_attachs) {
?>
	<ul class="clearfix" style="margin-left:10px;">
		<?php foreach($post_attachs as $post_attach) {
		$custom_image_title = get_post_meta($post_attach->ID, '_custom_image_title', true);
		$custom_image_alt = get_post_meta($post_attach->ID, '_custom_image_alt', true);
		?>
		<li style="float:left; width:184px; padding-bottom:5px;">
			<img src="<?php echo get_post_thumb($post_attach->ID, 174, 174, true); ?>">
			<input type="text" name="custom_image_title_<?php echo $post_attach->ID; ?>" value="<?php echo $custom_image_title; ?>" placeholder="Enter Title" style="width:174px;"><br />
			<input type="text" name="custom_image_alt_<?php echo $post_attach->ID; ?>" value="<?php echo $custom_image_alt; ?>" placeholder="Enter Alt" style="width:174px;">
		</li>
		<?php } ?>
	</ul>
<?php
	}
}

function page_seo_meta_title(){
	global $post, $wpdb;
	$_page_seo_meta_title = get_post_meta($post->ID, '_page_seo_meta_title', true);
?>
	<input type="hidden" name="page_smt" value="true" />
	<input type="text" name="page_seo_meta_title" value="<?php echo $_page_seo_meta_title; ?>" style="width:100%;"><br />
<?php	
}

add_action('save_post', 'nws_save_post_actions');
function nws_save_post_actions($post_id) {
	global $wpdb;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}
	$post = get_post($post_id);
	// post custom images attributes save
	if ($post->post_type == 'post') {
		if ($_POST['post_cia'] == 'true') {
			update_post_meta($post_id, 'general_images_title', trim($_POST['general_images_title']));
			update_post_meta($post_id, 'general_images_alt', trim($_POST['general_images_alt']));
			$post_attachs = $wpdb->get_results(sprintf("SELECT * FROM %sposts WHERE post_parent = %s AND post_type = 'attachment' ORDER BY menu_order, ID", $wpdb->prefix, $post_id));
			if ($post_attachs) {
				foreach($post_attachs as $post_attach) {
					update_post_meta($post_attach->ID, '_custom_image_title', trim($_POST['custom_image_title_'.$post_attach->ID]));
					update_post_meta($post_attach->ID, '_custom_image_alt', trim($_POST['custom_image_alt_'.$post_attach->ID]));
				}
			}
		}
		// set Prices taxonomy
		nws_update_post_prices_tax($post_id);
	}
	// page seo meta title save
	if ($post->post_type == 'page') {
		if ($_POST['page_smt'] == 'true') {
			update_post_meta($post_id, '_page_seo_meta_title', trim($_POST['page_seo_meta_title']));
		}
	}
}

function nws_update_post_prices_tax($post_id) {
	global $wpdb;

	$tax_prices = get_terms('price', 'hide_empty=0');
	$price = get_post_meta($post_id, 'new_price', true);
	if (!$price) {
		$price = get_post_meta($post_id, 'price', true);
	}
	$price = (int)$price;
	$prices_vals = array();
	foreach($tax_prices as $tax_price) {
		$wpdb->query(sprintf("DELETE FROM %sterm_relationships WHERE term_taxonomy_id = %s AND object_id = %s", $wpdb->prefix, $tax_price->term_taxonomy_id, $post_id));
		$tpname = $tax_price->name;
		$tpname = str_replace(array(' ', '+'), '', $tpname);
		$tprices = explode('-', $tpname);
		$prices_vals[] = array('min' => (int)$tprices[0], 'max' => (int)$tprices[1], 'term_taxonomy_id' => $tax_price->term_taxonomy_id);
	}
	if ($price > 0) {
		$tt_id = 0;
		foreach($prices_vals as $prices_val) {
			if ($prices_val['max'] > 0) {
				if ($price >= $prices_val['min'] && $price <= $prices_val['max']) {
					$tt_id = $prices_val['term_taxonomy_id'];
				}
			} else {
				if ($price >= $prices_val['min']) {
					$tt_id = $prices_val['term_taxonomy_id'];
				}
			}
		}
		if ($tt_id > 0) {
			$insert = array();
			$insert['term_taxonomy_id'] = $tt_id;
			$insert['object_id'] = $post_id;
			$wpdb->insert($wpdb->prefix."term_relationships", $insert);
		}
	}
}

function get_custom_alt_title($type, $attach_id, $def_attr) {
	$custom_alt_title = $def_attr;
	$custom_image_alt_title = get_post_meta($attach_id, '_custom_image_'.$type, true);
	if (strlen($custom_image_alt_title)) {
		$custom_alt_title = $custom_image_alt_title;
	}
	return $custom_alt_title;
}

function shorturl($url) {
	global $OPTION;
	$bitly = 'http://api.bit.ly/shorten?version='.$OPTION['wps_bitly_version'].'&longUrl='.urlencode($url).'&login='.$OPTION['wps_bitly_username'].'&apiKey='.$OPTION['wps_bitly_apikey'].'&format=json';
	
	$response = file_get_contents($bitly);
	
	$json = @json_decode($response,true);
	return $json['results'][$url]['shortUrl'];
}

function nws_subscribe_action($type, $data) {
	// Mailchimp
	if ($type != 'register') {
		if (!class_exists('MCAPI')) { require_once(  './prelaunch/lib/MCAPI.class.php' ); }
		$api = new MCAPI("1b8715e58f34dc6d98d1db2f165d30ba-us2");
		$l = $api->lists();
		$mergeVars = array('FNAME' => '', 'LNAME' => '');
		if (strlen($data['gender'])) {
			$mergeVars['GROUPINGS'] = array(0 => array('name' => 'Sex', 'groups' => $data['gender']));
		}
		$api->listSubscribe($l['data'][0]['id'], $data['email'], $mergeVars);
	}

	// Infusionsoft
	/*require_once("infusionsoft/isdk.php");
	$app = new iSDK;
	if ($app->cfgCon()) {
		// select tags
		$gid = 12; // 01. Current Status
		$tags = array();
		$itags = $app->dsQuery("ContactGroup", 100, 0, array('GroupCategoryId' => $gid), array('Id', 'GroupName'));
		if (count($itags)) {
			foreach($itags as $itag) {
				$tags[$itag['GroupName']] = $itag['Id'];
			}
		}
		$assign_tag = $tags['Prospect']; // Prospect by default
		if ($type == 'checkout') {
			$assign_tag = $tags['Buyer']; // checkout page
		} else if ($type == 'submissionform') {
			$assign_tag = $tags['Seller']; // submission form
		}
		// check contact
		$icontact = $app->findByEmail($data['email'], array('ID'));
		if (count($icontact)) {
			$cid = $icontact[0]['ID'];
		} else {
			$cid = $app->addCon(array('Email' => $data['email'])); // add contact
		}
		if (strlen($data['gender'])) {
			$gender = str_replace(',', ' and ', $data['gender']);
			if ($gender == 'Female and Male') { $gender = 'Male and Female'; }
			$app->updateCon($cid, array('_Gender' => $gender));
		}
		// assign tags
		$assignflag = true;
		$ictags = $app->dsQuery("ContactGroupAssign", 10, 0, array('ContactId' => $cid), array('GroupId'));
		if (count($ictags)) {
			foreach($ictags as $ictag) {
				if ($ictag['GroupId'] == $assign_tag) {
					$assignflag = false;
				}
			}
		}
		if ($assignflag) {
			$app->grpAssign($cid, $assign_tag);
			if ($assign_tag == $tags['Buyer']) { // remove Prospect tag for Buyer
				$app->grpRemove($cid, $tags['Prospect']);
			}
		}
		// add "TT-Add to KLT campaign" tag to sign up user
		if ($type == 'register') {
			$app->grpAssign($cid, 116);
		}
	}*/
}

// show single post (draft, iseller_draft...)
add_filter('the_posts', 'nws_show_all_statuses_posts');
function nws_show_all_statuses_posts($posts) {
	global $wp_query, $wpdb;

	if(is_single() && $wp_query->post_count == 0 && is_user_logged_in()) {
		$posts = $wpdb->get_results($wp_query->request);
	}
	return $posts;
}

// checking staff users
function is_spec_staff_user() {
	global $current_user;
	$spec_staffs = array(
		17441, // hivista staff
		41448  // sally.l
	);
	if (in_array($current_user->ID, $spec_staffs)) {
		return true;
	}
	return false;
}

// hivista init
add_action('init', 'nws_additional_init');
function nws_additional_init() {
	global $wpdb, $current_user;
	if ($_GET['notify'] == 'send') {
		echo 'Notify notifications:<br><br>';
		alerts_send_notifications();
		exit;
	}
	if ($_GET['cpnotify'] == 'send') {
		echo 'Change price notifications:<br><br>';
		sellers_send_change_price_email();
		exit;
	}
	if ($_GET['hivista'] == 'test') {
		sellers_send_completed_quotation_email(19907);
		exit;
	}
}
?>