<?php
add_action('init', 'nws_sell_questions_custom_init');
function nws_sell_questions_custom_init() {
  global $wp_rewrite;

  $labels = array(
    'name' => __('Sell Questions', 'post type general name'),
    'singular_name' => __('Sell Question', 'post type singular name'),
    'add_new' => __('Add New', 'Sell Question'),
    'add_new_item' => __('Add New Sell Question'),
    'edit_item' => __('Edit Sell Question'),
    'new_item' => __('New Sell Question'),
    'view_item' => __('View Sell Question'),
    'search_items' => __('Search Sell Question'),
    'not_found' =>  __('No Sell Question found'),
    'not_found_in_trash' => __('No Sell Question found in Trash'), 
    'parent_item_colon' => ''
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'exclude_from_search' => true, 
    'show_ui' => true, 
    'query_var' => true,
	'rewrite' => array('slug' => 'sell-question', 'with_front' => FALSE),
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array('title','editor','page-attributes')
  ); 
  register_post_type('sell-question', $args);
  
  $wp_rewrite->flush_rules();
}

//add filter to insure the text Sell Question, or Sell Question, is displayed when user updates a Sell Question 
add_filter('post_updated_messages', 'nws_sell_questions_updated_messages');
function nws_sell_questions_updated_messages( $messages ) {

  $messages['sell-question'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Sell Question updated. <a href="%s">View Sell Question</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Sell Question updated.'),
    5 => isset($_GET['revision']) ? sprintf( __('Sell Question restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Sell Question published. <a href="%s">View Sell Question</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Sell Question saved.'),
    8 => sprintf( __('Sell Question submitted. <a target="_blank" href="%s">Preview Sell Question</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Sell Question scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Sell Question</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Sell Question draft updated. <a target="_blank" href="%s">Preview Sell Question</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}

//display contextual help for Sell Question
add_action( 'contextual_help', 'add_nws_sell_questions_help_text', 10, 3 );

function add_nws_sell_questions_help_text($contextual_help, $screen_id, $screen) { 
  if ('sell-question' == $screen->id ) {
    $contextual_help =
      '<p>' . __('Things to remember when adding or editing a Sell Question:') . '</p>' .
      '<ul>' .
      '<li>' . __('Specify the correct genre such as Mystery, or Historic.') . '</li>' .
      '<li>' . __('Specify the correct writer of the Sell Question.  Remember that the Author module refers to you, the author of this Sell Question review.') . '</li>' .
      '</ul>' .
      '<p>' . __('If you want to schedule the Sell Question review to be published in the future:') . '</p>' .
      '<ul>' .
      '<li>' . __('Under the Publish module, click on the Edit link next to Publish.') . '</li>' .
      '<li>' . __('Change the date to the date to actual publish this article, then click on Ok.') . '</li>' .
      '</ul>' .
      '<p><strong>' . __('For more information:') . '</strong></p>' .
      '<p>' . __('<a href="http://codex.wordpress.org/Posts_Edit_SubPanel" target="_blank">Edit Posts Documentation</a>') . '</p>' .
      '<p>' . __('<a href="http://wordpress.org/support/" target="_blank">Support Forums</a>') . '</p>' ;
  } elseif ( 'edit-Sell Question' == $screen->id ) {
    $contextual_help = 
      '<p>' . __('This is the help screen displaying the table of Sell Question blah blah blah.') . '</p>' ;
  }
  return $contextual_help;
}
?>