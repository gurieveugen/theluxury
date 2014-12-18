<?php global $OPTION, $current_user, $wp_query; ?>

			</div><!-- .center-content -->
		</div><!-- #pg_wrap -->
		<?php if (!is_checkout_page()) { ?>
			<?php if (is_front_page() && !is_user_logged_in()) { ?>
				<div class="b-section-signup bdbox footer-signup-form">
					<h3>Sign up now</h3>
					<p>Exclusive access to sales, discounts, notifications &amp; the latest trends!</p>
					<p class="error"></p>
					<form class="form-signup cf" method="POST">
						<input type="text" name="your_email" value="" placeholder="Enter your email" class="your-email">
						<input type="submit" value="Sign up" class="btn-orange">
					</form>
				</div>
			<?php } ?>
			<div id="footer" class="footer-main center-wrap bdbox">
				<div class="footer-blocks cf">
					<div class="footer-block footer-block_socials">
						<div class="facebook-block">
							<div class="fb-like" data-href="https://www.facebook.com/theluxurycloset" data-width="250" data-show-faces="true" data-send="true"></div>
						</div>
						<?php dynamic_sidebar('footer_social_widget_area'); ?>
					</div>
					<div class="footer-block footer-block_menu">
						<?php dynamic_sidebar('footer_middle_widget_area'); ?>
					</div>
					<div class="footer-block footer-block_shop">
						<?php dynamic_sidebar('footer_right_widget_area'); ?>
					</div>
				</div>
				<div class="footer-copy">
					<?php dynamic_sidebar('footer_copyright_text_widget_area'); ?>
				</div>
			</div>
		<?php } else { ?>
			<div class="footer-wrap checkout-footer">
				<div class="holder">
					<div id="footer" class="bigft clearfix noprint">
						<div class="footer_box">
							<div class="footer_inner_box clearfix">
								<div class="fb-like" data-href="https://www.facebook.com/theluxurycloset" data-width="250" data-show-faces="true" data-send="true"></div>
							</div>
							<?php dynamic_sidebar('social-widget-area'); ?>
						</div>
							
						<div class="footer_box middle">
							<div class="footer_inner_box clearfix">
								<?php dynamic_sidebar('footer_middle_widget_area'); ?>	
							</div>
						</div>
						
						<div class="footer_box secure">
							<div class="footer_inner_box clearfix">
								<?php dynamic_sidebar('footer_right_checkout_widget_area'); ?>
							</div>
						</div>
					</div><!-- end footer -->
				</div>
				<div class="footer-bottom">
					<?php dynamic_sidebar('footer_copyright_text_widget_area'); ?>
				</div>
			</div>
		<?php } ?>
	</div>
	<!-- #wrapper -->
<?php
remove_action( 'wp_footer', 'grofiles_attach_cards'); 
remove_action( 'wp_footer', 'grofiles_extra_data'); 
wp_footer(); 
include('footer-popups.php'); ?>
</div> <!-- #wrapper -->
<div class="window-mask" style="display:none;"></div>

<?php if ($_GET['slp'] == 'true') { ?><script type="text/javascript">jQuery(document).ready(function(){ show_login_popup('def', '<?php echo $_GET['r']; ?>'); });</script><?php } ?>
<?php if (is_category($OPTION['wps_sale_category'])) { ?><div class="sale-category-pg" style="display:none;"><?php echo $OPTION['wps_sale_category'].';'.get_cat_name($OPTION['wps_sale_category']); ?></div><?php } ?>
<?php if (is_tag()) { $ctag_id = $wp_query->get_queried_object_id(); $tag_data = get_tag($ctag_id); ?><div class="tag-pg" style="display:none;"><?php echo $ctag_id.';'.$tag_data->name; ?></div><?php } ?>

<script type="text/javascript">
// clear autocomplete off fields
setTimeout(clear_chrome_auto_fill, 500);
jQuery.post('<?php echo get_cart_url(); ?>', { FormAction: 'get-total-cart-items' }, function(data){ jQuery('span.bag a').html(data); });
</script>
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
<noscript><div style="display:inline;"><img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/956545849/?value=0&label=KTA3CI_ZgQUQufaOyAM&guid=ON&script=0"/></div></noscript>

<?php // Sign up using facebook (popup) ?>
<?php if (!is_page('login') && !is_page('register')) { $fapikey = get_option('fbc_app_key_option'); ?>
<link type="text/css" rel="stylesheet" href="<?php bloginfo('url'); ?>/wp-content/plugins/xl-facebookconnect/fbconnect.css"></link>
<script src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php" type="text/javascript"></script>
<script src="<?php bloginfo('url'); ?>/wp-content/plugins/xl-facebookconnect/fbconnect.js" type="text/javascript"></script>
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
<?php $getkudos = false; if ($getkudos) { ?>
<!-- Start of GetKudos Script -->
<script>
(function(w,t,gk,d,s,fs){if(w[gk])return;d=w.document;w[gk]=function(){
(w[gk]._=w[gk]._||[]).push(arguments)};s=d.createElement(t);s.async=!0;
s.src='//static.getkudos.me/widget.js';fs=d.getElementsByTagName(t)[0];
fs.parentNode.insertBefore(s,fs)})(window,'script','getkudos');
getkudos('create', 'theluxurycloset');
</script>
<!-- End of GetKudos Script -->
<?php } ?>
<!--Start of Zopim Live Chat Script-->
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
$.src='//v2.zopim.com/?U8dQigYM4gzeUChrgZU4PoO3vopV0jG0';z.t=+new Date;$.
type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
</script>
<!--End of Zopim Live Chat Script-->
<!-- start utm params and track logged/not logged in user -->
<script type="text/javascript">
	if (jQuery('#utm_source').size()) {
		jQuery('#utm_source').val(get_utm_param('utm_source'));
		jQuery('#utm_medium').val(get_utm_param('utm_medium'));
		jQuery('#utm_campaign').val(get_utm_param('utm_campaign'));
		jQuery('#utm_content').val(get_utm_param('utm_content'));
		jQuery('#utm_term').val(get_utm_param('utm_term'));
	}
	ga('set', 'dimension1', '<?php if (is_user_logged_in()) { echo 'Logged_In'; } else { echo 'Not_Logged_In'; } ?>');
</script>
<!-- end utm params and track logged/not logged in user -->
<script>
	(function() {
	var _fbq = window._fbq || (window._fbq = []);
	if (!_fbq.loaded) {
	var fbds = document.createElement('script');
	fbds.async = true;
	fbds.src = '//connect.facebook.net/en_US/fbds.js';
	var s = document.getElementsByTagName('script')[0];
	s.parentNode.insertBefore(fbds, s);
	_fbq.loaded = true;
	}
	_fbq.push(['addPixelId', '540535596054575']);
	})();
	window._fbq = window._fbq || [];
	window._fbq.push(['track', 'PixelInitialized', {}]);
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?id=540535596054575&ev=PixelInitialized" /></noscript>
</body>
</html>
