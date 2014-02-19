<?php
/*
Template Name: Authenticity Page
*/
?>
<?php get_header(); ?>

<div class="authenticity-pages">
<?php
$ap = 1;
$apclass = ' active';
$apages = get_pages('child_of='.$post->ID.'&sort_column=menu_order&sort_order=asc');
if ($apages) :
	foreach($apages as $apage) {
		$text_after_title = get_post_meta($apage->ID, 'text_after_title', true);
		$featured_image = get_post_thumbnail_id($apage->ID); ?>
		<div class="authenticity-page<?php echo $apclass; ?>" id="apage-<?php echo $apage->ID; ?>"<?php echo $apstyle; ?>>
			<div class="authenticity-heading">
				<div class="holder">
					<h2><?php echo $apage->post_title; echo ' ' . $text_after_title; ?></h2>
					<ul class="authenticity-nav">
						<?php foreach($apages as $lpage) { if ($lpage->ID != $apage->ID) { ?>
						<li><a href="#<?php echo $lpage->post_name; ?>" class="anlink" rel="apage-<?php echo $lpage->ID; ?>"><?php echo $lpage->post_title; ?></a></li>
						<?php }} ?>
					</ul>
				</div>
				<?php if ($featured_image) { $featured_image_src = wp_get_attachment_image_src($featured_image, 'full'); ?><img title="<?php echo $apage->post_title; ?>" alt="<?php echo $apage->post_title; ?>" class="attachment-post-thumbnail wp-post-image" src="<?php echo $featured_image_src[0]; ?>"><?php } ?>
			</div>
			<?php echo apply_filters('the_content', $apage->post_content); ?>
		</div>
	<?php $ap++; $apstyle = ' style="display:none;"'; $apclass = ''; } ?>
<?php endif; ?>
</div>

<?php get_footer(); ?>