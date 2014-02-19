<?php
/*

Template Name: Account Register

*/

get_header(); ?>


	<?php
	if(isset($_GET['err'])){
	
		$error_text = NULL;
	
		switch($_GET['err']){
		
			case '1':
				$error_text = __('Your email is too short.','wpShop');
			break;
			case '2':
				$error_text = __('Your username is too short.','wpShop');
			break;			
			case '3':
				$error_text = __('Your username is too long.','wpShop');
			break;			
			case '4':
				$error_text = __('Your email format is invalid.','wpShop');
			break;			
			case '5':
				$error_text = __('Your username is already taken.','wpShop');
			break;				
		}
	
		echo "<span class='login_err'>Error: $error_text</span>";
	}
	
	if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div <?php post_class('page_post'); ?> id="post-<?php the_ID(); ?>">
			<?php the_content('<p class="serif">'. __( 'Read the rest of this page &raquo;', 'wpShop' ) . '</p>'); 
			wp_link_pages(array('before' => '<p><strong>' . __( 'Pages:', 'wpShop' ) . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
		</div><!-- page_post -->
	<?php endwhile; endif; ?>


	<form id="createAccount" class="clearfix" action="<?php echo get_bloginfo('template_url') . '/register.php'; ?>" method="post">
		<fieldset id="pt1">
			<legend><span><?php _e('Step 1. : Email details','wpShop');?></span></legend>
			<h2><?php _e('Enter your email address.','wpShop');?></h2>
			<p class="help"><?php _e('You must enter a valid email address.','wpShop');?></p>
			<label for="createAccountEmail1"><?php _e('Email','wpShop');?></label>
			<input type="text" id="createAccountEmail1" name="email" size="35" maxlength="50" value="" onchange="showHint(this.value,'<?php echo is_in_subfolder();?>')"/>
			<span id='txtHint'></span>
		</fieldset>
				
		<fieldset id="pt2">
			<legend><span><?php _e('Step 2. : Username','wpShop');?></span></legend>
			<h2><?php _e('Choose a username.','wpShop');?></h2>
			<p class="help"><?php _e('Usernames between 6-10 characters in length.','wpShop');?></p>
			<label for="createAccountUsername"><?php _e('Username','wpShop');?></label>
			<input type="text" id="createAccountUsername" size="35" name="username" maxlength="10" value="" onkeyup="showUser(this.value,'<?php echo is_in_subfolder();?>')"/>
			<span id='userHint'></span>
		</fieldset>
		<fieldset id="pt3">
			<legend><span><?php _e('Step 3. : Submit Form','wpShop');?></span></legend>
			<h2><?php _e('Submit the Form','wpShop');?></h2>
			<p class="help"><?php _e('Your password will be emailed to you so please check your email.','wpShop');?></p>
			<input type="submit" class="formbutton" value="<?php _e('Create Account','wpShop');?>" />
		</fieldset>
	</form>
	
	<?php if ( is_sidebar_active('account_reg_widget_area')){$the_div_class = 'alignleft signInAcc';}else {$the_div_class = '';}?>
	<div class="<?php echo $the_div_class; ?>">
		<h2><?php _e('Already a Customer? ','wpShop');_e('Sign in','wpShop');?></h2>	
		
		<?php include TEMPLATEPATH.'/loginform.php'; ?>
		
	</div>
	
	<?php if ( is_sidebar_active('account_reg_widget_area') ) : ?>
		<div class="acc_widgets_area alignright">
			<?php dynamic_sidebar('account_reg_widget_area'); ?>
		</div><!-- c_box  -->
	<?php endif;
	
	
get_footer(); ?>