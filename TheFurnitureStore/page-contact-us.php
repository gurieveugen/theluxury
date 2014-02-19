<?php
/*
Template Name: Contact Page
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
        <div class="rfloat mapWrap">
          <iframe width="300" height="472" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps/ms?msid=210891605401531059454.0004cde7608b3a7292fe7&amp;msa=0&amp;ie=UTF8&amp;ll=25.10814,55.179809&amp;spn=0.006791,0.013078&amp;t=m&amp;iwloc=0004cde76577d48ec19d3&amp;output=embed"></iframe>
        </div>
      </div>
      
    </div>
	<?php endwhile; ?>
    
    <!-- Content Section End Here--> 
    
  </div>

<?php get_footer(); ?>