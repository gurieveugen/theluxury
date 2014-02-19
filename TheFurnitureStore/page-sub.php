<?php
/*
Template Name: Defaul Page Second lvl
*/
?>
<?php get_header(); ?>

<div class="mainWrap lfloat">

	<!--Left Sidebar Begin Here-->
	
	<?php $page_parent = get_page_by_title('About us'); ?>
	
	<?php if($page_parent_id = $page_parent->ID): ?>
		<?php if($children = wp_list_pages("title_li=&child_of=".$page_parent_id."&echo=0&depth=1")): ?>
		
			<div class="leftSidebar lfloat">
				<ul>
					<li class="subHeading"><?php echo $page_parent->post_title; ?></li>
					<?php echo $children; ?>
				</ul>
			</div>
			
		<?php endif; ?>
	<?php endif; ?>

	<!--Left Sidebar End Here-->

	<!-- Content Section Begin Here-->
	
	<?php while ( have_posts() ) : the_post(); ?>

	<div class="rfloat content">
		<div class="row none">
			<div class="content-wrap lfloat">
				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</div>
			<?php the_post_thumbnail(array(286, 9999), array('class' => 'rfloat')); ?>
		</div>

	</div>
	
	<?php endwhile; ?>

	<!-- Content Section End Here-->

</div>

<?php get_footer(); ?>