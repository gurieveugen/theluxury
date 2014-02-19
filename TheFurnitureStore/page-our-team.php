<?php
/*
Template Name: Page Our Team
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
<div class="rfloat content ourTeamWrap">
	<?php the_post_thumbnail(array(770, 9999), array('class' => 'teamBanner')); ?>
	<div class="row">
		<h1><?php the_title(); ?></h1>
		
		<?php $query = new WP_Query( array('post_type' => 'page', 'post_parent' => $post->ID, 'posts_per_page' => -1, orderby => 'menu_order date', order => 'DESC'));
		if(!empty($query->post)):
		?>
		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			<div class="teamMember lfloat">
				<?php if(has_post_thumbnail()): ?>
					<div class="teamMemberPic lfloat">
						<?php the_post_thumbnail(array(162,208)); ?>
					</div>
				<?php endif; ?>
				<div class="teamMemberDetail rfloat">
					<h2><?php the_title(); ?></h2>
					<?php if($position = get_post_meta($post->ID, 'position', true)): ?>
						<h3><?php echo $position; ?></h3>
					<?php endif; ?>
					<?php the_content(); ?>
				</div>
			</div>

		<?php endwhile; ?>
		<?php endif; wp_reset_postdata(); ?>

	</div>
	<?php wp_nav_menu(array(
		'theme_location'  => 'add-menu',
		'menu'            => '',
		'menu_class'            => 'add-menu',
		'container_class' => 'row contentLinks',
		'depth'           => 1,
		'before'	=> '<span>·</span>'
	)); ?>
</div>
<?php endwhile; ?>
    <!-- Content Section End Here-->
	
</div>

<?php get_footer(); ?>