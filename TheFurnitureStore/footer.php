<?php global $OPTION, $current_user; ?>

			<!--</div>--><!-- container -->
		<!--</div>--><!-- floatswrap-->
		</div><!-- .center-content -->
	</div><!-- #pg_wrap -->
	<?php if(!isset($_GET['showCart']) && !isset($_GET['orderNow'])): ?>
		<?php dynamic_sidebar('footer-area'); ?>
	<?php endif; ?>
	<div class="footer-wrap">
		<div class="holder">
			<div id="footer" class="bigft clearfix noprint">
				<div class="footer_box">
					<div class="footer_inner_box clearfix">
						<div class="fb-like" data-href="https://www.facebook.com/theluxurycloset" data-width="250" data-show-faces="true" data-send="true"></div>
					</div>
					<?php if (is_sidebar_active('social-widget-area')) : dynamic_sidebar('social-widget-area'); endif;?>
				</div>
					
				<div class="footer_box middle">
					<div class="footer_inner_box clearfix">
						<?php if ( is_sidebar_active('footer_middle_widget_area') ) : dynamic_sidebar('footer_middle_widget_area'); endif;?>	
					</div>
				</div>
				
				<div class="footer_box secure">
					<div class="footer_inner_box clearfix">
							<?php if ( is_sidebar_active('footer_right_widget_area') ) : dynamic_sidebar('footer_right_widget_area'); endif;?>
					</div>
				</div>
			</div><!-- end footer -->
		</div>
		<div class="footer-bottom">
			<p><?php if (is_sidebar_active('footer_copyright_text_widget_area')) : dynamic_sidebar('footer_copyright_text_widget_area'); endif;?></p>
		</div>
	</div>
</div> <!-- #wrapper -->
<?php
remove_action( 'wp_footer', 'grofiles_attach_cards'); 
remove_action( 'wp_footer', 'grofiles_extra_data'); 
wp_footer(); 
include('footer-popups.php');
?>
</div> <!-- #wrapper -->
<div class="bg-popup-login" style="display:none;"></div>
<div class="window-mask" style="display:none;"></div>

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
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
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
<script type="text/javascript" src="https://zx157.infusionsoft.com/app/webTracking/getTrackingCode?trackingId=f64be57f4e91eb9ad9e42aca637f9489"></script>
</body>
</html>