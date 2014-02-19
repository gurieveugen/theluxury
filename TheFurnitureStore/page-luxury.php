<?php
/*
Template Name: Page Luxury
*/
?>
<?php get_header(); ?>

<div class="codePageWrap lfloat"> 
	
	<?php while ( have_posts() ) : the_post(); ?>
		<?php the_content(); ?>
	<?php endwhile; ?>
	
</div>

<?php get_footer(); ?>