<?php
/*

Template Name: Customer Service

*/

get_header(); 
$WPS_sidebar		= $OPTION['wps_sidebar_option'];
switch($WPS_sidebar){
	case 'alignRight':
		$the_float_class 	= 'alignleft';
	break;
	case 'alignLeft':
		$the_float_class 	= 'alignright';
	break;
}

$currentPage	= get_page_by_title($post->post_title);


$mainPages = get_pages('title_li=&child_of='.$currentPage->ID.'&parent='.$currentPage->ID.'&sort_column=menu_order');
foreach($mainPages as $post){$childPages = get_pages('title_li=&child_of='.$post->ID.'&parent='.$post->ID.'&sort_column=menu_order');}
$counter = 3;
$a = 1;
if (!empty($mainPages)) {
	if (empty($childPages)) {
		$the_div_class = 'pageCont clearfix narrow '. $the_float_class;
	} else {
		$the_div_class = 'pageCont clearfix';
	} ?>
	
	<div class="<?php echo $the_div_class; ?>">
		
		<?php 
		
		if (empty($childPages)) {echo '<ul>';}
		
		foreach($mainPages as $post){
		
			$childPages = get_pages('title_li=&child_of='.$post->ID.'&parent='.$post->ID.'&sort_column=menu_order');
			
			setup_postdata($post);
			if (!empty($childPages)) {
				$the_div_class = alternating_css_class($counter,3,' c_box_first');
				if (($a==1) || ($a==2) || ($a==3)) {$the_row_class='top_row';}else{$the_row_class='';}?>
				<div class="c_box <?php echo $the_div_class;?> <?php echo $the_row_class;?>">
					<h2 class="section_title"><?php the_title(); ?></h2>
					<ul>
						<?php foreach($childPages as $post){
						setup_postdata($post); ?>
						<li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( __('Permalink to %s', 'wpShop'), the_title_attribute('echo=0') ); ?>"><?php the_title(); ?></a></li>
						<?php } ?>
					</ul>
				</div><!-- c_box -->
				
			<?php echo insert_clearfix($counter,3,' <div class="clear"></div>');
				$counter++;
				$a++;
			} else { ?>
				
					<li <?php post_class('faq'); ?> id="post-<?php the_ID(); ?>">
						<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( __('Permalink to %s', 'wpShop'), the_title_attribute('echo=0') ); ?>"><?php the_title(); ?></a>
					</li>
				
			<?php }
		} 
		
		// my foreach loop above was throwing the sidebar off so...
		wp_reset_query();
		
		if (empty($childPages)) {echo '</ul>';}
		?>	
	
	</div><!-- pageCont -->
	
	<?php 
	if (!empty($childPages)) { ?>
		<div class="cswa">
			<?php if ( is_sidebar_active('main_customer_service_widget_area') ) : dynamic_sidebar('main_customer_service_widget_area'); endif; ?>
		</div>
		<?php } 
		
	if (empty($childPages)) {
		include (TEMPLATEPATH . '/widget_ready_areas.php'); 
	}
	
 } else { 

	$children = wp_list_pages("title_li=&child_of=".$post->post_parent."&parent=".$post->post_parent."&echo=0"); 
	if ( is_sidebar_active('sub_customer_service_widget_area') || ($children)) { $the_div_class = 'narrow '. $the_float_class;} else {$the_div_class = 'wide';}
	
	while (have_posts()) : the_post(); ?>
		<div <?php post_class('page_post '.$the_div_class);?> id="post-<?php the_ID(); ?>">
			<?php the_content('<p class="serif">'. __( 'Read the rest of this page &raquo;', 'wpShop' ) . '</p>'); 
			wp_link_pages(array('before' => '<p><strong>' . __( 'Pages:', 'wpShop' ) . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
		</div><!-- page_post -->
	<?php endwhile; 
	
	include (TEMPLATEPATH . '/widget_ready_areas.php');
	
} 

get_footer(); ?>