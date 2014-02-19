<?php
/*

Template Name: Recover Password 

*/

$accountReg		= get_page_by_title($OPTION['wps_pgNavi_regOption']);
$accountLog		= get_page_by_title($OPTION['wps_pgNavi_logOption']);
get_header();

	$EMAIL 		= load_what_is_needed('email');		//change.9.10

	if ( is_sidebar_active('account_log_widget_area')){$the_div_class = 'alignleft signInAcc';}else {$the_div_class = '';}?>
	<div class="<?php echo $the_div_class; ?>" id="loginFormWrap">
		<h2><?php _e('Enter your email address','wpShop');?></h2>	
		
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<div <?php post_class('page_post'); ?> id="post-<?php the_ID(); ?>">
				<?php the_content('<p class="serif">'. __( 'Read the rest of this page &raquo;', 'wpShop' ) . '</p>'); 
				wp_link_pages(array('before' => '<p><strong>' . __( 'Pages:', 'wpShop' ) . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
			</div><!-- page_post -->
		<?php endwhile; endif;
		
		if($_POST){
			// we remove any whitespaces
			$uname 	= trim($_POST[signInUname]);
			$email 	= trim($_POST[signInEmail]);			
		
			$table 	= is_dbtable_there('feusers');
			$qStr 	= "SELECT * FROM $table WHERE uname='$uname' AND email ='$email'";							
			$res 	= mysql_query($qStr);
			$num	= mysql_num_rows($res);
		
			//following steps only if there is such a uname/email combination
			if($num != 0){		
			
				$done 	= 0;
			// create password
				$pw 	= generateRandomString();
				$pwdb	= md5(trim($pw));

			// db update
				$qStr 	= "UPDATE $table SET pw='$pwdb' WHERE uname='$uname' AND email ='$email'";
				$done 	= mysql_query($qStr);

			// email 
				$search		= array("[##header##]","[##pw##]","[##login_link##]");
				//change.9.10
				$replace 	= array($EMAIL->email_header(),$pw,get_option('siteurl').'/'.$accountLog->post_name.'');	  
				$EMAIL->email_password_reset($email,$search,$replace);
				//\change.9.10
			}
				
			// redirect
			if($done){
				$url = get_real_base_url().'?reset=1';
				echo "<meta http-equiv='refresh' content='0; URL=$url'>";
			}
			else{
				$url = get_real_base_url().'?reset=0';
				echo "<meta http-equiv='refresh' content='0; URL=$url'>";
			}
		}

		if($_GET['reset'] == '1'){
			echo "<div class='success'>".__('Your password was reset and sent. Please check your email!','wpShop')."</div>";
		}
		if($_GET['reset'] == '0'){
			echo "<div class='error'>".__('Your user name and/or email address could not be found in our records. Try again.','wpShop')."</div>";
		}
		?>

		<form method="post" action="<?php echo get_real_base_url(); ?>">
			<fieldset>
				<label for="signInUname"><?php _e('Username','wpShop');?></label>
				<input type="text" name="signInUname" id="signInUname" size="35" maxlength="40" value="" />
				<label for="signInEmail"><?php _e('Email','wpShop');?></label>
				<input type="text" name="signInEmail" id="signInEmail" size="35" maxlength="40" value="" />
				<span class="passhelp"> <?php _e('A reset password will be sent to this email address.','wpShop');?></span>
				<input class="formbutton" type="submit" alt="Sign in" value="Reset" name="" title="Reset" />
			</fieldset>
		</form>

	</div>
	<?php 
	 if ( is_sidebar_active('account_log_widget_area') ) : ?>
		<div class="acc_widgets_area alignright">
			<h2 class="crAcc"><?php _e('New Customer? ','wpShop');?><a href="<?php echo get_permalink( $accountReg->ID ); ?>"><?php _e('Create an Account','wpShop');?></a></h2>
			<?php dynamic_sidebar('account_log_widget_area'); ?>
		</div><!-- c_box  -->
	<?php endif; ?>
	
	
<?php get_footer(); ?>