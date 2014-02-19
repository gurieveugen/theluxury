<?php
/*
Template Name: Press Page
*/
?>
<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

  <div class="main lfloat press-main">
    <h1><?php the_title(); ?></h1>

	<?php
	$psubpages = get_pages('child_of='.$post->ID.'&sort_column=post_date&sort_order=asc');
	if ($psubpages) {
		$press_subpages = array();
		foreach($psubpages as $psubpage) {
			$psubpage_date = date("F Y", strtotime($psubpage->post_date));
			$press_subpages[$psubpage_date][] = $psubpage;
		}
		foreach($press_subpages as $dv => $press_subpages_list) {
	?>
	<div class="article-wrap lfloat">
		<div class="row margin-none">
			<h2><?php echo $dv; ?></h2>
		</div>
		<div class="row">
			<?php $spnmb = count($press_subpages_list); $sepcnt = 1;
			foreach($press_subpages_list as $press_subpage) {
				$press_subpage_url = get_permalink($press_subpage->ID);
				$press_subpage_thumb_id = get_post_thumbnail_id($press_subpage->ID);
				$press_subpage_subtitle = get_post_meta($press_subpage->ID, 'press_subtitle', true);
				$spnmb--;
			?>
			<div class="article-column lfloat">
				<?php if ($press_subpage_thumb_id) { ?><a href="<?php echo $press_subpage_url; ?>" title="<?php echo $press_subpage->post_title; ?>"><img src="<?php echo get_post_thumb($press_subpage_thumb_id, 210, 243, true); ?>" alt=""/></a><?php } ?>
				<div class="article-detail lfloat">
					<a href="<?php echo $press_subpage_url; ?>"><?php echo $press_subpage->post_title; ?></a>
					<p><?php echo $press_subpage_subtitle; ?></p>
				</div>
			</div>
			<?php if ($sepcnt == 4 && $spnmb > 0) { $sepcnt = 0; ?>
		</div>
	</div>
	<div class="article-wrap lfloat">
		<div class="row">
			<?php } ?>
			<?php $sepcnt++; } // foreach($press_subpages_list as $press_subpage) { ?>
		</div>
	</div>
    
	<?php }} ?>

  </div>

<?php endwhile; endif; ?>

<?php get_footer(); ?>