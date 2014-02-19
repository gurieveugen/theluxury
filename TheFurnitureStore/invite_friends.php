<?php
/*
Template Name: Invite Friends
*/
set_referral();
auth_redirect_theme_login();
get_header();
global $current_user;
get_currentuserinfo(); // grabs the user info and puts into vars
$user_ID = $current_user->ID;
if(isset($_REQUEST['send_invitation'])) 
{
	if(send_invitations())
	{	nocache_headers();
		wp_redirect( $_SERVER['REQUEST_URI']);
		exit();
	}
}
$my_key = get_my_referral_key();
get_header(); ?>
<script type="text/javascript" src="<?=get_option('siteurl')."/prelaunch/"?>js/popup.js"></script>
<script type="text/javascript" src="<?=get_option('siteurl')."/prelaunch/"?>js/jquery.cookies.js"></script>
<script src="<?=get_option('siteurl')."/prelaunch/"?>js/referrals.js" type="text/javascript"></script>
<script type="text/javascript" src="<?=get_option('siteurl')."/prelaunch/"?>js/jquery.facebook.multifriend.select.js"></script>
<link href="<?=get_option('siteurl')."/prelaunch/"?>css/jquery.facebook.multifriend.select.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
		Referrals.invite_limit_reached = false;
		isUserLoggedIn = <?php if($my_key) {?> true; <?php } else { ?> false; <?php } ?>
		Referrals.fb_send_dialog_data = {"name":"Check out The Luxury Closet","title":"<?=get_my_theme_register_link()?>reffer=<?=$my_key?>","subject":"The Luxury Closet","caption":"I think you deserve a new bag!","redirect":"<?=get_option('siteurl')?>/prelaunch/invite_friends.php","description":"Hi, I want to share this site with you called the luxury closet. You can sell your used luxury bags for a great price and also find authentic new and pre-owned bags from top luxury brands at up to 70% off, click here: <?=get_my_theme_register_link()?>?reffer=<?=$my_key?>","link":"<?=get_my_theme_register_link()?>reffer=<?=$my_key?>","message":"","picture":"<?=get_option('siteurl')?>/wp-content/themes/TheFurnitureStoreLight/images/Facebookprofileimage.jpg"};
		var fb_stream_post_data = {"name":"Check out The Luxury Closet","title":"<?=get_my_theme_register_link()?>reffer=<?=$my_key?>","caption":"I think you deserve a new bag!","redirect":"<?=get_option('siteurl')?>/prelaunch/invite_friends.php","description":"Hi, I want to share this site with you called the luxury closet. You can sell your used luxury bags for a great price and also find authentic new and pre-owned bags from top luxury brands at up to 70% off","link":"<?=get_my_theme_register_link()?>reffer=<?=$my_key?>","message":"","picture":"<?=get_option('siteurl')?>/wp-content/themes/TheFurnitureStoreLight/images/Facebookprofileimage.jpg"};
</script>

<script type="text/javascript" src="<?=get_option('siteurl')."/prelaunch/"?>js/util.js"></script>
<script type="text/javascript" src="<?=get_option('siteurl')."/prelaunch/"?>js/basic.js"></script>
<script type="text/javascript" src="<?=get_option('siteurl')."/prelaunch/"?>js/abc_launcher.js"></script>
<?php
	if(strripos(get_option('siteurl'),"localhost",0)) $call_loc = "/bags/prelaunch/js/call_back.html"; 
	else if(strripos(get_option('siteurl'),"ancorps",0)) $call_loc ="/demos/bags/prelaunch/js/call_back.html";	
?>

<script type="text/javascript">
function onABCommComplete(data) {	
	var friends_list = jQuery('#emails');
	var emails = friends_list.val().split(/,\s*/);
	friends_list.val('');
	eml = '';
	jQuery.each(emails,function(){
		emailsArray = this.match(/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9._-]+)/gi);
		eml += emailsArray[0]+ ", ";
	});
	friends_list.val(eml);
	
}
jQuery(document).ready(function(){
	jQuery('.pl_hand').click(function(e){
		e.preventDefault();
		showPlaxoABChooser('emails', '<?=$call_loc?>');
	});
});
</script>
<link rel="stylesheet" type="text/css" media="all" href="<?=get_option('siteurl')."/prelaunch/"?>css/styles.css" />	
<div class="main">
	<div class="section clearAfter" id="bd">
		<img title="get free shoes when you invite your friends to join The Luxury Closet!" style="margin-bottom:15px;" src="<?=get_option('siteurl')."/prelaunch"?>/images/form_header_v2.jpg?e334b161" alt="Get credits when you invite your friends to join The Luxury Closet!">
		<div class="container_940 clearAfter referrals">
			<div id="referral_form" class="left_col_560">
				<form method="post" action="" name="invite_my_friends">
					<fieldset style="margin-bottom: 0;">
						<label  for="emails" class="auto_width">To: &nbsp; </label>  &nbsp; (separate email addresses by commas) or  
						<a class="pl_hand" href="#"> invite friends from your address book</a>
						<br>
						<?php show_invite_result();?>
						<textarea name="emails" id="emails"><?php show_emails()?></textarea>
					</fieldset>
					<div  class="bg_light" id="email_import_bar">
						<a class="pl_hand fl"  href="#"><img src="<?=get_option('siteurl')."/prelaunch"?>/images/email_guys.png" alt="Email_guys"></a>
						<a class="pl_hand fl im" href="#">Import Contacts From:</a>
						<a class="pl_hand fl" href="#"><img src="<?=get_option('siteurl')."/prelaunch/"?>images/email_import_logos.png" alt="Email_import_logos"></a>
						<div style="clear: both"></div>
					</div>

					<fieldset>
						<label for="message">Message:</label>
						<textarea name="message" id="message">Hi, I want to share this site with you called the luxury closet. You can sell your used luxury bags for a great price and also find authentic new and pre-owned bags from top luxury brands at up to 70% off
						</textarea>
					</fieldset>

					<div class="bott_invite">
						<a class="term_conditions" href="#" >Terms &amp; Conditions</a>
						<input name="send_invitation" class="floatright" type="submit" value="" />
					</div>
				</form>
			</div>

			<div id="referral_right" class="right_col_350" >
				<div id="fb_invite_container">
					<div id="fb-connect-widget">
						<div class="fb-widget" id="fb-connect-button"></div>
					</div>
				</div>
				<div class="share">
					<div class="tw_share" id="twitter_share"></div>
					<div class="fb_share" id="facebook_share"></div>
				</div>
				<div class="rig_head">Your Personal Invite Link</div>
				<p>Copy and paste your personal invitation link to share online! Your friends must click through your personal link for you to earn free credits.  </p>
				<input type="text" value="<?=get_my_theme_register_link()?>reffer=<?=$my_key?>" readonly="readonly" id="invite_url_input" title="Click here copy your refrral link">
				<hr>
				<div class="rig_head">Already Sent Invites?</div>
				<p class="already_invited">Follow your invite statuses, remind your friends to join or invite more friends! For every friend who places her first order, you will get 100  toward a free credit. Your points will appear on your account when your friends orders ship. <a href="<?=get_my_theme_history_link()?>" >See your invitation history</a> to track your email invites.   </p>
			</div>
	</div>
</div>
<div id="fb-root"></div>
<div id="arena" style="display: none;">
	<div class="pop_closer">
		<a href="javascript: void(0);" onclick="Popup.hide('arena');return false;"><img src="<?=get_option('siteurl')."/prelaunch/"?>images/close_bu.png" align="right" /></a>
	</div>
	<div class="pop_inner" style="background-color: white; width: 725px; height: 506px; overflow: auto; padding: 20px; border: 1px solid #ff9600;">	
		<div id="fb_invite_wrapper" >
			<div id="facebook_wrapper">
				<div id="facebook-friend-selector">
					<div id="jfmfs-container"></div>
					<div id="bottom-bar" class="silver_box">
						<span class="inner_text">Add a personal message...</span>
						<input id="fb_message" type="text">
						<a href="#" id="fb-send-button" class="button-glossy fb-blue">Invite</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="confirm_box_wrapper"  style="display:none;">
	<div id="confirm_box">
		<div id="confirm_title"><span>Preview</span></div>
		<div id="facebook_post" style="overflow:hidden;margin:4px 0 0 15px; font-size:12px;">
			<div id="fb_confirm_name" style="text-align: left;">The Luxury Closet</div>
			<div id="fb_confirm_message" style="color:gray; margin: 2px 0 9px 0; word-wrap:break-word; text-align:left; font-weight:normal; font-size:11px;"></div>
			<div id="fb_image" style="float:left; background-color:#000000;">
				<img src="<?=get_option('siteurl')?>/wp-content/themes/TheFurnitureStoreLight/images/Facebookprofileimage.jpg" width="130">
			</div>
			<ul id="fb_text" style="float:left;text-align:left;width:240px;margin-left:10px;">
				<li id="fb_title">Check out The Luxury Closet</li>
				<li style="color:gray; margin-top: 4px; font-weight:normal; font-size:11px;">Hi, I want to share this site with you called the luxury closet. You can sell your used luxury bags for a great price and also find authentic new and pre-owned bags from top luxury brands at up to 70% off</li>
			</ul>
		</div>
		<div id="confirm_bottom_bar">
			<a href="#" id="cancel" class="button-glossy grey">Cancel</a>
			<a href="#" id="confirm" class="button-glossy fb-blue">Post to their wall</a>
		</div>
	</div>
</div>
<?php
	if(strripos(get_option('siteurl'),"localhost",0)) $apikey = "276284119066763"; 
	else if(strripos(get_option('siteurl'),"ancorps",0)) $apikey ="251447664896335";
	else $apikey ="250313664982898";	
?>
<script type="text/javascript">
window.fbAsyncInit = function() {
	FB.init({
	appId   : <?=$apikey?>,
	session : null, // don't refetch the session when PHP already has it
	status  : true, // check login status
	cookie  : true, // enable cookies to allow the server to access the session
	xfbml   : true // parse XFBML
	});
	FB.getLoginStatus(function(response) {
		if (response && (response.status !== "unknown")) 
		{	jQuery.cookie("fbs", response.status);} 
		else {	jQuery.cookie("fbs", null);}
	});				
	jQuery(document).trigger('fbInit');
};

(function() {
	var e = document.createElement('script');
	e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
	e.async = true;
	document.getElementById('fb-root').appendChild(e);
}());
twtrul = "<?=urlencode(get_my_theme_register_link().'reffer='.$my_key)?>";
FACEBOOK_PERMS = "publish_stream,email";
//FACEBOOK_PERMS = "user_birthday,user_about_me,user_likes,user_education_history, user_hometown, user_interests, user_activities, user_location,publish_stream,email";
jQuery('#twitter_share').click(function(event) {
var url = "http://twitter.com/share?url="+twtrul+"&via=TheLuxuryCloset&text=Hi, I want to share this site with you called the luxury closet&count=none";
	var l = 200, t = 200, w = 520, h = 350;
	window.open(url, "twitter_tweet", "left=" + l + ",top=" + t + ",width=" + w + ",height=" + h + ",personalbar=0,toolbar=0,scrollbars=1,resizable=1");
	event.preventDefault();
});	
jQuery('#facebook_share').click(function(event) {
	FB.ui({
		method: 'feed',
		name: "The Luxury Closet!",
		link: "<?=get_my_theme_register_link()?>reffer=<?=$my_key?>",
		caption: "Check out The Luxury Closet",
		description: "Hi, I want to share this site with you called the luxury closet. You can sell your used luxury bags for a great price and also find authentic new and pre-owned bags from top luxury brands at up to 70% off"
	});
	event.preventDefault();
});
</script>
<script type="text/javascript" src="./prelaunch/js/zclip.js"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
	if(isUserLoggedIn)
	{ 	jQuery('#invite_url_input').zclip({
		  path:'./prelaunch/js/ZeroClipboard.swf',
		  copy:function(){return $('#invite_url_input').val();}
		});
	}
});
</script>
<div style="display:none">
	<img src="<?=get_option('siteurl')."/prelaunch/"?>images/button_pick_friends_over.png"  />
	<img src="<?=get_option('siteurl')."/prelaunch/"?>images/btn_send_invite_over.gif"  />
</div>
<?php get_footer();?>