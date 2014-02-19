<?php
/*
Template Name: MY History
*/
global $wpdb, $current_user, $OPTION;
set_referral();
get_header();
if (is_user_logged_in()) {
if(isset($_REQUEST['resend'])) resend_invitation();
if(isset($_REQUEST['invdelete'])) delete_invitation();
if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'credit') $credit = true;
else $credit = false;
//print_r($current_user);  
get_currentuserinfo(); // grabs the user info and puts into vars
$user_ID = $current_user->ID;
// check to see if the form has been posted. If so, validate the fields
include (TEMPLATEPATH . '/lib/pages/index_body.php'); 
// this is a "fake cronjob" = whenever default index page is called - the age of dlinks is checked - and removed if necessary
$DIGITALGOODS = load_what_is_needed('digitalgoods');	//change.9.10
$DIGITALGOODS->delete_dlink();							//change.9.10
//content to feature?
$featuredCont 		= $OPTION['wps_feature_option'];
//type of effect?
$featuredEffect 	= $OPTION['wps_featureEffect_option'];
// sidebar location?
$WPS_sidebar		= $OPTION['wps_sidebar_option'];
switch($WPS_sidebar)
{
	case 'alignRight':	$the_float_class 	= 'alignleft';	break;
	case 'alignLeft':
	default:	$the_float_class 	= 'alignright';	break;
}
if($OPTION['wps_front_sidebar_disable']) 
{
	$the_div_class 	= 'featured_wrap featured_wrap_alt';
	$the_div_id 	= 'main_col_alt';
} else 
{
	$the_div_class 	= 'featured_wrap ' .$the_float_class;
	$the_div_id 	= 'main_col';
}
if(isset($_REQUEST['per_page']) && (int)$_REQUEST['per_page']) 
{ 	$_SESSION['per_page'] = $per_page = (int)$_REQUEST['per_page'];
	if(isset($_SESSION['page']) && (int)$_SESSION['page']) {unset($_SESSION['page']); }
}
else if(isset($_SESSION['per_page']) && (int)$_SESSION['per_page']) {$per_page = (int)$_SESSION['per_page']; }
else $per_page = 10;
if(isset($_REQUEST['page']) && (int)$_REQUEST['page']) {$_SESSION['page'] = $page = (int)$_REQUEST['page']; }
else if(isset($_SESSION['page']) && (int)$_SESSION['page']) {$page = (int)$_SESSION['page']; }
else $page = 1;
$tot_inv = get_my_invites_count();
$tot_inv_pages = ceil($tot_inv/$per_page); 
if($page>$tot_inv_pages) $page = 1;
global $OPTION;	
if(isset($_REQUEST['istatus'])) $istatus = $_REQUEST['istatus'];
else $istatus = 'A';
if($current_user->my_points == 0 || $current_user->my_points == '') $pnt = 0;
else $pnt = $current_user->my_points;								
?>
<link rel="stylesheet" type="text/css" media="all" href="<?=get_option('siteurl')."/prelaunch/"?>css/styles.css" />
<div id="<?php echo $the_div_id;?>" class="<?php echo $the_div_class;?>">
	<div id="main_rg_container">
		<div class="main_rg_container">
			<div class="pnt_box">Total Credits:<?=$pnt.' '.$OPTION['wps_currency_code']?> </div>
			<div class="text_inn">
					<div class="red_but">
						<?php //if($current_user->my_points == 0) {?>
						<img src="<?=get_option('siteurl')."/prelaunch/"?>images/reddem.jpg" alt="Redeem Your Credits"  />
						<?php //} else {?>
						<!--<a href="redeem_credits.php" title="Click here to reddem your credits">
							<img src="images/reddem.jpg" alt="Redeem Your Credits"  />
						</a>-->
						<?php //} ?>
					</div>
				<h5>MY CREDIT HISTORY</h5>
					
					<?php if($current_user->my_points == 0)
						echo "Currently You don't have any Credits in Your Account...!";
					?>
				   <?php show_invite_result();?>
				   
				<div class="container_togg">
					<h2 class="trigger <?=(!$credit)? 'active':'';?>"><a href="javascript:void(0);">Track My E-mail Invites</a></h2>
					<div class="toggle_container " <?=($credit)? 'style="display:none"':'';?>>
						<div class="track_out">
							<?php if($tot_inv>0) { ?>
							<div class="track_inn">
								<div class="track_em">E-mail</div>
								<div class="track_dt">Date</div>
								<div class="track_stu">Status</div>
								<div class="track_act">Actions</div>
							</div>
							<?php show_my_invities();?>
							<?php } else echo "Currently You don't have any Invities ...!"; ?>
						</div>
						<?php if($tot_inv>0) { ?>						
					   <!-- //////////////page ignation\\\\\\\\\\\\-->
						<div class="pg_ig">
						<form name="chng_per" method="post" action="" >	
							<select class="pg_ig_drop" name="per_page" onchange="this.form.submit();">
								<option value="10" <?=($per_page == 10)? 'selected="selected"': '' ?>>10 per page</option>
								<option value="20" <?=($per_page == 20)? 'selected="selected"': '' ?>>20 per page</option>
								<option value="25" <?=($per_page == 25)? 'selected="selected"': '' ?>>25 per page</option>
								<option value="50" <?=($per_page == 50)? 'selected="selected"': '' ?>>50 per page</option>
								<option value="100" <?=($per_page == 100)? 'selected="selected"': '' ?>>100 per page</option>
							</select>
							<input type="hidden" name="istatus" value="<?=$istatus?>"  />
						</form>
							<div class="pg_ig_text2">
								<?php if($page > 1) {?>
								<a href="<?=get_my_theme_history_link().'?page=1&istatus='.$istatus?>">First </a> |	
								<a href="<?=get_my_theme_history_link().'?page='.($page-1).'&istatus='.$istatus?>"> Prev</a> |
								<?php }?>  PAGE <?=$page?> / <?=$tot_inv_pages?> 
								<?php if($page < $tot_inv_pages) {?> |
								<a href="<?=get_my_theme_history_link().'?page='.($page+1).'&istatus='.$istatus?>"> Next</a> |
								<a href="<?=get_my_theme_history_link().'?page='.$tot_inv_pages.'&istatus='.$istatus?>">Last</a> 
								<?php } ?>
							</div>
						<div class="gotext">Type</div>
						<form name="chng_per" method="post" action="" >	
							<select class="pg_ig_drop" name="istatus" onchange="this.form.submit();" style="margin-right:20px;">
								<option value="A" <?=($istatus == 'A')? 'selected="selected"': '' ?>>All</option>
								<option value="R" <?=($istatus == 'R')? 'selected="selected"': '' ?>>Registered</option>
								<option value="D" <?=($istatus == 'D')? 'selected="selected"': '' ?>>Delivered</option>
							</select>
							<input type="hidden" name="per_page" value="<?=$per_page?>"  />
						</form>	
						<div class="gotext">Go To</div>
						<form name="goto_page" method="post" action="">	
							<select class="goto" id="goto" name="page" onChange="this.form.submit()" title="Go To Page">
								<?php for($i = 1;$i<=$tot_inv_pages;$i++){?>
								<option value="<?=$i?>" <?=($i==$page)? 'selected="selected"': ''?>><?=$i?></option>
								<?php } ?>
							</select>
							<input type="hidden" name="per_page" value="<?=$per_page?>"  />
							<input type="hidden" name="istatus" value="<?=$istatus?>"  />
							<input type="button" tabindex="100" class="go_but" id="go_but" name="go" title="go">
						</form>
						</div>
						<!--////////////end page ignation\\\\\\\\\-->
						<?php } ?>
					</div>
					<?php 
						$tot_his = get_my_credit_history_count();
						$tot_his_pages = ceil($tot_his/$per_page); 
						if($page>$tot_his_pages) $page = 1;
					?>
					<h2 class="trigger <?=($credit)? 'active':'';?>"><a href="javascript:void(0);">Credit Detail</a></h2>
					<div class="toggle_container" <?=(!$credit)? 'style="display:none"':'';?>>
						<div class="track_out">
						<?php if($tot_his>0) { ?>
							<div class="pnt_inn">
								<div class="pnt_date">Date</div>
								<div class="pnt_amt">Amount</div>
								<div class="pnt_detail">Details</div>
								<div class="pnt_user">User</div>
								<div class="pnt_email">E-mail</div>
							</div>
						<?php 
							show_my_credit_history();
							}
							else echo "No credit history for you ...";
						?>	    
						</div>
						<?php if($tot_his>0) { ?>
						<!-- //////////////page ignation\\\\\\\\\\\\-->
						<div class="pg_ig">
						<form name="chng_per" method="post" action="" >
							<input type="hidden" name="type" value="credit" />	
							<select class="pg_ig_drop" name="per_page" onchange="this.form.submit();">
								<option value="10" <?=($per_page == 10)? 'selected="selected"': '' ?>>10 per page</option>
								<option value="20" <?=($per_page == 20)? 'selected="selected"': '' ?>>20 per page</option>
								<option value="25" <?=($per_page == 25)? 'selected="selected"': '' ?>>25 per page</option>
								<option value="50" <?=($per_page == 50)? 'selected="selected"': '' ?>>50 per page</option>
								<option value="100" <?=($per_page == 100)? 'selected="selected"': '' ?>>100 per page</option>
							</select>
						</form>
							<div class="pg_ig_text">
								<?php if($page > 1) {?>
								<a href="<?=get_my_theme_history_link().'?page=1&type=credit'?>">First </a> |	
								<a href="<?=get_my_theme_history_link().'?page='.($page-1).'&type=credit'?>"> Prev</a> |
								<?php }?>  PAGE <?=$page?> / <?=$tot_his_pages?> 
								<?php if($page < $tot_his_pages) {?> |
								<a href="<?=get_my_theme_history_link().'?page='.($page+1).'&type=credit'?>"> Next</a> |
								<a href="<?=get_my_theme_history_link().'?page='.$tot_his_pages.'&type=credit'?>">Last</a> 
								<?php } ?>
							</div>
							<div class="gotext">Go To</div>
						<form name="goto_page" method="post" action="">	
							<input type="hidden" name="type" value="credit" />	
							<select class="goto" id="goto" name="page" onChange="this.form.submit()" title="Go To Page">
								<?php for($i = 1;$i<=$tot_his_pages;$i++){?>
								<option value="<?=$i?>" <?=($i==$page)? 'selected="selected"': ''?>><?=$i?></option>
								<?php } ?>
							</select>
							<input type="button" tabindex="100" class="go_but" id="go_but" name="go" title="go">
						</form>
						</div>
						<!--////////////end page ignation\\\\\\\\\-->
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function(){
	//Switch the "Open" and "Close" state per click then slide up/down (depending on open/close state)
	jQuery("h2.trigger").click(function(){
		jQuery(this).toggleClass("active").next().slideToggle("slow");
		return false; //Prevent the browser jump to the link anchor
	});

});
</script>
<?php if ($OPTION['wps_front_sidebar_disable'] != TRUE) 
{
	switch($OPTION['wps_sidebar_option']){
		case 'alignRight':	$the_float_class 	= 'alignright';	break;
		case 'alignLeft':	$the_float_class 	= 'alignleft';	break;
	}
	$the_div_class 	= 'sidebar front-widgets frontPage_sidebar noprint '. $the_float_class; ?>
	<div class="<?php echo $the_div_class;?>">
		<?php if ( is_sidebar_active('frontpage_widget_area') ) : dynamic_sidebar('frontpage_widget_area'); endif; ?>
	</div><!-- frontPage_sidebar -->	
<?php  }  
} else {
	echo '<p>You are not allowed to view this page.</p>';
}
get_footer();
?>
