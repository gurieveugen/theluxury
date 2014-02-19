<?php $OPTION = NWS_get_global_options();?>	

			</div><!-- container -->
		</div><!-- floatswrap-->
	</div><!-- pg_wrap -->
		<?php 
			switch($OPTION['wps_footer_option']){
				case 'small_footer':
					include (TEMPLATEPATH . '/includes/footers/smallFooter.php');        					
				break;
					
				case 'large_footer':
					include (TEMPLATEPATH . '/includes/footers/largeFooter.php');    
				break;
			}
		?>
		</div><!-- end container -->				
	</div><!-- end footer -->
	<div class="container">
	<p class="footer_notes">
		<span class="copyright">&copy; <?php echo date('Y'); ?>. <a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a>. | <?php _e('All Rights Reserved','wpShop');?>.</span>
		<?php /*?><span><?php _e('WordPress Theme by','wpShop');?> <a href="http://www.sarah-neuber.de"><?php _e('SN Design and Development','wpShop');?></a>.</span><?php */?>
		<div class="footer_links">
			<?php if ( is_sidebar_active('footer_links') ) : dynamic_sidebar('footer_links'); endif;?>
		</div>
	</p>
	</div>
	
<?php 
	remove_action( 'wp_footer', 'grofiles_attach_cards'); 
	remove_action( 'wp_footer', 'grofiles_extra_data'); 
	wp_footer(); 
	//global $wp_filter;
	//var_dump($wp_filter['wp_footer']);
?>

<!-- Begin MailChimp Signup Form -->
<?php
$user_agent = $_SERVER['HTTP_USER_AGENT'];
if( !preg_match('/ipod/i',$user_agent) && !preg_match('/iphone/i',$user_agent) && !preg_match('/android/i',$user_agent) && !preg_match('/opera mini/i',$user_agent) && !preg_match('/blackberry/i',$user_agent) && !preg_match('/(iris|3g_t|windows ce|opera mobi|windows ce; smartphone;|windows ce; iemobile)/i',$user_agent))
{
?>
<script src="<?php NWS_bloginfo('template_url','yes'); ?>Light/js/jquery_form.js" type="text/javascript"></script>
<script type="text/javascript">var js_siteurl = "<?php bloginfo('url'); ?>";</script>
<div id="mc_embed_signup" >
	<div id="mc_embed_signup_center">
    <div id="mc_embed_signup_center_inner">
		<div class="popup-banner">
			<img src="<?php bloginfo('template_url') ?>/images/img/popup-promo.jpg" alt="">
		</div>
		<!--<form action="http://theluxurycloset.us2.list-manage.com/subscribe/post?u=cb3d8356eafb18036e7cbc611&amp;id=d7112ff5c4" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">
			<div id="mc_embed_signup_main">
				<div class="mc-field-group">
					<label for="mce-EMAIL">EMAIL</label>
					<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
					<input type="submit" value="Join" name="subscribe" id="mc-embedded-subscribe" class="button">
				</div>
				<div class="facebook"><a href="#login" id="login-with-fb-popup">Sign Up using facebook</a></div>
				<div id="mce-responses">
					<div class="response" id="mce-error-response" style="display:none"></div>
					<div class="response" id="mce-success-response" style="display:none"></div>
				</div>
			</div>
			<?php if (!is_page('login') && !is_page('register')) { ?>
			<input type="hidden" name="redirect_to" id="redirect_to" value="<?php bloginfo('url'); ?>" />
			<?php } ?>
		</form>-->
		
		<form action="http://theluxurycloset.us2.list-manage.com/subscribe/post?u=cb3d8356eafb18036e7cbc611&amp;id=d7112ff5c4" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
			<div id="mc_embed_signup_main">
				<div class="mc-field-group">
					<label for="mce-EMAIL">EMAIL</label>
					<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
					<ul class="checkbox-list">
						<li><input type="checkbox" value="Female" name="group[]" CHECKED><label>Women</label></li>
						<li><input type="checkbox" value="Male" name="group[]"><label>Men</label></li>
					</ul>
					<input type="submit" value="Join" name="subscribe" id="mc-embedded-subscribe" class="button">
				</div>
				<div class="facebook"><a href="#login" id="login-with-fb-popup">Sign Up using facebook</a></div>
				<div id="mce-responses" class="clear">
					<div class="response" id="mce-error-response" style="display:none"></div>
					<div class="response" id="mce-success-response" style="display:none"></div>
				</div>
			</div>
			<?php if (!is_page('login') && !is_page('register')) { ?>
			<input type="hidden" name="redirect_to" id="redirect_to" value="<?php bloginfo('url'); ?>" />
			<?php } ?>
		</form>

		<div class="bottom-image">
			<img src="<?php bloginfo('template_url') ?>/images/img/img-popup.jpg" alt="" />
		</div>
        <a href="#" id="mc_embed_close" class="mc_embed_close">Close</a>	
   	</div>
	</div>
</div>
<script src="<?php NWS_bloginfo('template_url','yes'); ?>Light/js/mc_embed.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready( function($) {	
	if(!navigator.userAgent.match(/(iPhone|iPod|Android|BlackBerry)/i))
	setTimeout('mce_preload_check();', 250);	
});
</script>
<!--End mc_embed_signup-->		
<?php } ?>
<!-- End MailChimp Signup Form -->
<script type="text/javascript">
document.write(unescape("%3Cscript id=%27pap_x2s6df8d%27 src=%27" + (("https:" == document.location.protocol) ? "https://" : "http://") + "perf.clickmena.com/scripts/trackjs.js%27 type=%27text/javascript%27%3E%3C/script%3E"));
</script>
<script type="text/javascript">PostAffTracker.setAccountId('66acecfb');
try {PostAffTracker.track();} catch (err) { }
</script>

<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 956545849;
var google_conversion_label = "KTA3CI_ZgQUQufaOyAM";
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;"><img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/956545849/?value=0&label=KTA3CI_ZgQUQufaOyAM&guid=ON&script=0"/></div>
</noscript>

<?php // Sign up using facebook (popup) ?>
<?php if (!is_page('login') && !is_page('register')) { $fapikey = get_option('fbc_app_key_option'); ?>
<link type="text/css" rel="stylesheet" href="<?php bloginfo('url'); ?>/wp-content/plugins/xl-facebookconnect//fbconnect.css"></link>
<script src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php" type="text/javascript"></script>
<script src="<?php bloginfo('url'); ?>/wp-content/plugins/xl-facebookconnect//fbconnect.js" type="text/javascript"></script>
<script type="text/javascript">
FBConnect.init('<?php echo $fapikey; ?>', '<?php bloginfo('url'); ?>/wp-content/plugins/xl-facebookconnect/', '<?php bloginfo('url'); ?>', 0, FBConnect.appconfig_none);
var base = '<?=get_option('siteurl')?>';
window.fbAsyncInit = function() {
	FB.init({
	appId   : <?php echo $fapikey; ?>,
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
FACEBOOK_PERMS = "publish_stream,email";
</script>
<script type="text/javascript" src="<?=get_option('siteurl')?>/prelaunch/js/jquery.cookies.js"></script>
<script src="<?=get_option('siteurl')?>/prelaunch/js/referrals.js" type="text/javascript"></script>
<?php } ?>
<script type="text/javascript">
adroll_adv_id = "HRYGJKSPF5GA3PAFRN2NFQ";
adroll_pix_id = "VPUALEFPI5H65JK2FDZAZ6";
(function () {
var oldonload = window.onload;
window.onload = function(){
__adroll_loaded=true;
var scr = document.createElement("script");
var host = (("https:" == document.location.protocol) ? "https://s.adroll.com" : "http://a.adroll.com");
scr.setAttribute('async', 'true');
scr.type = "text/javascript";
scr.src = host + "/j/roundtrip.js";
((document.getElementsByTagName('head') || [null])[0] ||
document.getElementsByTagName('script')[0].parentNode).appendChild(scr);
if(oldonload){oldonload()}};
}());
</script>
<script type="text/javascript">
var _kmq = _kmq || [];
var _kmk = _kmk || '5db44d9edfb3673edfd4bb6fd222d43ba6a7d4eb';
function _kms(u){
	setTimeout(function(){
		var d = document, f = d.getElementsByTagName('script')[0],
		s = d.createElement('script');
		s.type = 'text/javascript'; s.async = true; s.src = u;
		f.parentNode.insertBefore(s, f);
	}, 1);
}
_kms('//i.kissmetrics.com/i.js');
_kms('//doug1izaerwt3.cloudfront.net/' + _kmk + '.1.js');
</script>
</body>
</html>