<?php
/*
Template Name: My Wishlist
*/
global $wpdb, $current_user, $OPTION;
get_header();
if (is_user_logged_in()) {
get_currentuserinfo(); // grabs the user info and puts into vars
$user_ID = $current_user->ID;

$WISHLIST = load_what_is_needed('wishlist');
// remove item from wishlist 
if((isset($_POST['remove_wl_item'])) && (!empty($_POST['remove_wl_item']))){
	$WISHLIST->remove_from_wishlist();
}

$LANG['wishlist_empty']		= __('Your wishlist is empty!','wpShop');
$LANG['continue_shopping'] 	= __('Continue Shopping','wpShop');
$LANG['remove'] 			= __('Remove Item from wishlist','wpShop');
$table = is_dbtable_there('wishlist');
?>

<div class="my-wishlist-wrap">
	<div class="page-top-text">
	<?php the_content(); ?>
	</div>
	<table class="my-wishlist-table" border="0">
		<?php
		$wishlist_items = $wpdb->get_results(sprintf("SELECT * FROM %s WHERE uid = %s ORDER BY wid DESC", $table, $current_user->ID));
		if ($wishlist_items) {
			foreach($wishlist_items as $wishlist_item) {
				$wid = $wishlist_item->wid;
				$item_name = $wishlist_item->item_name;
				$item_thumb = $wishlist_item->item_thumb;
				$permalink = get_permalink($wishlist_item->postID);
				?>
				<tr>
					<td><a href="<?php echo $permalink; ?>" title="<?php echo $item_name; ?>" class="image"><?php if ($item_thumb) { ?><img src="<?php echo get_post_thumb($item_thumb, 91, 91, true); ?>" alt="<?php echo $item_name; ?>" class="image" /><?php } ?></a></td>
					<td class="second"><strong><a href="<?php echo $permalink; ?>"><?php echo $item_name; ?></a></strong></td>					
					<td class="last">
						<form action="<?php the_permalink(); ?>" method="post" class="my-wishlist-remove-form">
							<input type="hidden" name="remove_wl_item" value="<?php echo $wid; ?>" />
							<input class="show_tooltip" type="image" src="<?php echo TEMPLURL; ?>/images/wishlist-remove.png" title="<?php echo $LANG['remove']; ?>" />
						</form>
					</td>	
				</tr>					
			<?php }
		} else { ?>
			<tr>
				<td colspan="3"><p><?php echo $LANG['wishlist_empty']; ?></p></td>
			</tr>
		<?php } ?>						
	</table>
	<a class="my-wishlist-continue btn-orange" href="<?php bloginfo('home'); ?>"><?php _e('Continue Shopping','wpShop');?></a>
</div>

<?php
} else {
	echo '<p>You are not allowed to view this page.</p>';
}
get_footer(); ?>