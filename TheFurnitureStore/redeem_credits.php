<?php
/*
Template Name: Redeem Credits
*/
set_referral();
auth_redirect_theme_login();
if(isset($_REQUEST['resend'])) resend_invitation();
if(isset($_REQUEST['invdelete'])) delete_invitation();
if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'credit') $credit = true;
else $credit = false;
get_header();
global $current_user;
//print_r($current_user);  
get_currentuserinfo(); // grabs the user info and puts into vars
$user_ID = $current_user->ID;
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
global $VOUCHER;
$result = '';
if(isset($_REQUEST['action'])) $action = $_REQUEST['action']; else $action = "";
if(isset($_REQUEST['create']) && $_REQUEST['create'] == 'Submit')
{		
	$table_name   	= is_dbtable_there('vouchers');							
	$vouchers		= 1;
	$error			= 0;
	$err_message 	= '';	
	global $wpdb;
	global $VOUCHER;
	$user = wp_get_current_user();
	if(isset($_POST['voucher_amount']) && $_POST['voucher_amount'] != '') $vamt = (float)$_POST['voucher_amount'];
	else $vamt = 0;
	$my_credit = (float)get_user_meta($user->id,'my_points',true);
	if(!$vamt){
		$err_message .= "<div class='box-red'>".__('Please enter amount of your voucher','wpShop')."</div>";
		$error++;
	}
	else if($vamt > $my_credit)
	{
		$err_message .= "<div class='box-red'>".__("Please enter amount within your credit limit (Your credit : $my_credit)",'wpShop')."</div>";
		$error++;
	}
	
	$result = $err_message;
	
	if($error == 0)
	{
		###### Insert into voucher table #####################
		do{
			$code = generate_code(7);
		}while(vcode_exist($code));
		$result_db1 = $wpdb->insert($table_name, array( 'vcode' => $code, 'voption' => trim($_POST['voucher_option']), 'vamount' => trim($_POST['voucher_amount']), 'time_issued' => date("F j, Y"),'user_id' => $user_ID,'c_by' => 'S' ), array( '%s', '%s','%s', '%s','%d','%s' ));
		if($result_db1 != 1)
		{ 
			echo "<div class='box-red'>".__('There was a problem with','wpShop')." $table_name1 !</div>\n"; 
			$error++; 
		}
		else 
		{
			$result = "	<p class='good_message'>".__('Your voucher has been created ','wpShop')."</p>";
			update_user_meta($user->id,'my_points',($my_credit-$vamt));
			$current_user->my_points -= $vamt;
		}
	}
}		
get_currentuserinfo(); // grabs the user info and puts into vars
?>
<link rel="stylesheet" type="text/css" media="all" href="<?=get_option('siteurl')."/prelaunch/"?>css/styles.css" />
<div id="<?php echo $the_div_id;?>" class="<?php echo $the_div_class;?>">
	<div id="main_rg_container">
		<div class="main_rg_container">
			<h1 class="main_rg_container_head">Redeem Credits</h1>
			<div class="arw_corn"></div>
			<?php if($result != '') echo $result;?>
			<div class="innerdiv">
				<div class="name">Account Status</div>
				<div class="rig_text">Active</div>
			</div>
			<div class="innerdiv">
				<div class="name">Credits</div>
				<div class="rig_text"><?=$current_user->my_points.' '.$OPTION['wps_currency_code']?></div>
			</div>	
			<?php  
			global $OPTION;									
			if(isset($_REQUEST['action']))
			{
				
				$VOUCHER = load_what_is_needed('voucher');
			}
			if($action == 'display' || (isset($_REQUEST['create']) && $_REQUEST['create'] == 'Submit' && $error == 0 ))
			{
				if($_REQUEST['subaction'] == 'del')
				{
					$VOUCHER->delete($_REQUEST[vid]);
					echo "<meta http-equiv='refresh' content='0; URL=$url'>";
				}
				?>				
				<div class="tablenav">
					<h2 class="innerdiv">Single-Use Vouchers</h2>
					<form class='nws_search nws_voucher_search' action='' method='get'>
						<div class="innerdiv">						
							<input type='hidden' name='action' value='display' />
							<input type='text' name='vouch_wanted' value='<?=isset($_REQUEST['vouch_wanted'])? $_REQUEST['vouch_wanted']: ''?>' maxlength='255' class="field" />
							<small><?=__('Enter a Voucher Code','wpShop')?></small>	
						</div>
						<div class="innerdiv">
							<input class='save_but' type='submit' name='search_vouchers' value='Search' style="float:left;" />
						</div>
					</form>
				</div>
				<div class="credit_link">
                     <a href="<?=get_option('siteurl')."/redeem-credits/"?>">Create a Voucher?</a>
                </div>
				<div class="line"></div>
				<div class="credit_out">
					<?php	display_vouchers(is_dbtable_there('vouchers'),25);?>
				</div>
				<div class="pg_ig_credit">
					<?php my_voucher_pagination(25); ?>	
				</div>
				<?php 
			}
			else
			{	
				$currency = '"'. $OPTION['wps_currency_code'] .'"';
				?>	
				<form action='' enctype='multipart/form-data' name='voucher_form' id='voucher_form' method='post'>
					<h2 class="innerdiv">Single-Use Vouchers</h2>
					<div class="innerdiv">
						<div class="name">Amount</div>
						<input name="voucher_amount" id="voucher_amount"  type="text" maxlength="50" value="<?=$vamt?>"  class="field"/>
						<input type="hidden" name='voucher_option' id='voucher_option' value="A" /><?=$currency?>
						<input type='hidden' name='action' value='upload' />
					</div>
					<div class="innerdiv">
						<div class="credit_link">
							 <a href="<?=get_option('siteurl')."/redeem-credits?action=display"?>">View Your Vouchers?</a>
						</div>
						<input name="create" type="submit" value="Submit" class="save_but"/>
					</div>
				</form>
				<?php
			}
			?>	
		</div>
	</div>
</div>
<?php if ($OPTION['wps_front_sidebar_disable'] != TRUE) 
{
	switch($OPTION['wps_sidebar_option']){
		case 'alignRight':	$the_float_class 	= 'alignright';	break;
		case 'alignLeft':	$the_float_class 	= 'alignleft';	break;
	}
	$the_div_class 	= 'sidebar frontPage_sidebar noprint '. $the_float_class; ?>
	<div class="<?php echo $the_div_class;?>">
		<div class="padding">
			<?php if ( is_sidebar_active('frontpage_widget_area') ) : dynamic_sidebar('frontpage_widget_area'); endif; ?>
		</div><!-- padding -->
	</div><!-- frontPage_sidebar -->	
<?php  }  
get_footer();
?>
	