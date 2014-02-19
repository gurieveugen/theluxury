<?php
/*

Template Name: Account Login

*/

$accountReg		= get_page_by_title($OPTION['wps_pgNavi_regOption']);

get_header();

	if ( is_sidebar_active('account_log_widget_area')){$the_div_class = 'alignleft signInAcc';}else {$the_div_class = '';}
	
	if($_GET['reg'] == 'ok'){		

		$success = __('Your password has been sent to your email account.','wpShop');
	
		echo "<div class='success'>$success</div>";
	}
	
	if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div <?php post_class('page_post'); ?> id="post-<?php the_ID(); ?>">
			<?php the_content('<p class="serif">'. __( 'Read the rest of this page &raquo;', 'wpShop' ) . '</p>'); 
			wp_link_pages(array('before' => '<p><strong>' . __( 'Pages:', 'wpShop' ) . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
		</div><!-- page_post -->
	<?php endwhile; endif; ?>
		
	<div class="<?php echo $the_div_class; ?>" id="loginFormWrap">
		<?php include TEMPLATEPATH.'/loginform.php'; ?>
	</div>
	<?php 
	 if ( is_sidebar_active('account_log_widget_area') ) : ?>
		<div class="acc_widgets_area alignright">
			<h2 class="crAcc"><?php _e('New Customer? ','wpShop');?><a href="<?php echo get_permalink( $accountReg->ID ); ?>"><?php _e('Create an Account','wpShop');?></a></h2>
			<?php dynamic_sidebar('account_log_widget_area'); ?>
		</div><!-- c_box  -->
	<?php endif; ?>
	
	
<?php get_footer(); ?>