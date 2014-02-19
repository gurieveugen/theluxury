<?php
add_action('init', 'nws_eformats_custom_init');
function nws_eformats_custom_init() {
  global $wp_rewrite;

  $labels = array(
    'name' => __('Email Formats', 'post type general name'),
    'singular_name' => __('Email Format', 'post type singular name'),
    'add_new' => __('Add New', 'Email Format'),
    'add_new_item' => __('Add New Email Format'),
    'edit_item' => __('Edit Email Format'),
    'new_item' => __('New Email Format'),
    'view_item' => __('View Email Format'),
    'search_items' => __('Search Email Format'),
    'not_found' =>  __('No Email Format found'),
    'not_found_in_trash' => __('No Email Format found in Trash'), 
    'parent_item_colon' => ''
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'exclude_from_search' => true, 
    'show_ui' => true, 
    'query_var' => true,
	'rewrite' => array('slug' => 'email-format', 'with_front' => FALSE),
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array('title','editor')
  ); 
  register_post_type('email-format', $args);
  
  $wp_rewrite->flush_rules();
}

//add filter to insure the text Email Format, or Email Format, is displayed when user updates a Email Format 
add_filter('post_updated_messages', 'nws_eformats_updated_messages');
function nws_eformats_updated_messages( $messages ) {

  $messages['Email Format'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Email Format updated. <a href="%s">View Email Format</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Email Format updated.'),
    5 => isset($_GET['revision']) ? sprintf( __('Email Format restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Email Format published. <a href="%s">View Email Format</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Email Format saved.'),
    8 => sprintf( __('Email Format submitted. <a target="_blank" href="%s">Preview Email Format</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Email Format scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Email Format</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Email Format draft updated. <a target="_blank" href="%s">Preview Email Format</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}

//display contextual help for Email Format
add_action( 'contextual_help', 'add_nws_eformats_help_text', 10, 3 );

function add_nws_eformats_help_text($contextual_help, $screen_id, $screen) { 
  if ('Email Format' == $screen->id ) {
    $contextual_help =
      '<p>' . __('Things to remember when adding or editing a Email Format:') . '</p>' .
      '<ul>' .
      '<li>' . __('Specify the correct genre such as Mystery, or Historic.') . '</li>' .
      '<li>' . __('Specify the correct writer of the Email Format.  Remember that the Author module refers to you, the author of this Email Format review.') . '</li>' .
      '</ul>' .
      '<p>' . __('If you want to schedule the Email Format review to be published in the future:') . '</p>' .
      '<ul>' .
      '<li>' . __('Under the Publish module, click on the Edit link next to Publish.') . '</li>' .
      '<li>' . __('Change the date to the date to actual publish this article, then click on Ok.') . '</li>' .
      '</ul>' .
      '<p><strong>' . __('For more information:') . '</strong></p>' .
      '<p>' . __('<a href="http://codex.wordpress.org/Posts_Edit_SubPanel" target="_blank">Edit Posts Documentation</a>') . '</p>' .
      '<p>' . __('<a href="http://wordpress.org/support/" target="_blank">Support Forums</a>') . '</p>' ;
  } elseif ( 'edit-Email Format' == $screen->id ) {
    $contextual_help = 
      '<p>' . __('This is the help screen displaying the table of Email Format blah blah blah.') . '</p>' ;
  }
  return $contextual_help;
}
?>