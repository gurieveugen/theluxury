<?php
/*
Template Name: Press Page Single
*/
?>
<?php get_header(); ?>

<div class="main single-press lfloat">
    <div class="row">
	<?php if ( have_posts() ) : the_post(); ?>	
    <div class="article">
        <h3><?php the_title(); ?></h3>
		<h4><?php echo get_post_meta($post->ID, 'press_subtitle', true); ?></h4>
		<?php the_content(); ?>
		<a href="<?php echo get_permalink($post->post_parent); ?>">Back to Luxury Closet in the Press</a>
	</div>
	<?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>