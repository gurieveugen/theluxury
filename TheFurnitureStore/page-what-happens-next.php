<?php
/*
Template Name: What Happens Next Page
*/
global $OPTION;
?>
<?php get_header(); ?>

<div class="next-section">
	<div class="next-s-text">
		<p>Thank you for submitting your item. We will send you a quotation within 3 business days.</p>
	</div>
	<div class="next-s-buttons">
		<a href="<?php echo get_permalink($OPTION['wps_indvseller_my_items_page']); ?>" class="btn-orange">View submitted items</a>
		<a href="<?php echo get_permalink($OPTION['wps_indvseller_add_item_page']); ?>" class="btn-orange">Submit another item</a>
	</div>
	<?php $sell_questions = get_posts('post_type=sell-question&posts_per_page=-1&orderby=menu_order&order=asc');
	if ($sell_questions) { ?>
	<div class="section-tabs">
		<h2>Questions?</h2>
		<ul class="nav-tabs cf">
			<?php $sqnmb = 1; foreach($sell_questions as $sell_question) { ?>
				<li><a href="#tab-<?php echo $sqnmb; ?>"<?php if ($sqnmb == 1) { echo ' class="active"'; } ?>><?php echo $sell_question->post_title; ?></a></li>
			<?php $sqnmb++; } ?>
		</ul>
		<div class="tabs-content">
			<?php $sqnmb = 1; foreach($sell_questions as $sell_question) { ?>
				<div id="tab-<?php echo $sqnmb; ?>" class="tab-content">
					<?php echo apply_filters('the_content', $sell_question->post_content); ?>
				</div>
			<?php $sqnmb++; } ?>
		</div>
	</div>
	<?php } ?>
</div>
<script src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.mCustomScrollbar.min.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.mousewheel.min.js"></script>
<script>
(function($){
	$(function(){
		$('.section-tabs .tab-content').mCustomScrollbar();
		$('.nav-tabs').each(function(){
			var _list = $(this);
			var _links = _list.find('a');
		
			_links.each(function() {
				var _link = $(this);
				var _href = _link.attr('href');
				var _tab = $(_href);
		
				if(_link.hasClass('active')) _tab.show();
				else _tab.hide();
		
				_link.click(function(){
					_links.filter('.active').each(function(){
						$($(this).removeClass('active').attr('href')).hide();
					});
					_link.addClass('active');
					_tab.show();
					console.log('asdfasdfasdf');
					return false;
				});
			});
		});
	});
})(jQuery);
</script>
<div class="contact-data v3">
	<div class="contact-row">
		<p>
			See our <a href="http://luxcloset.staging.wpengine.com/faqs">FAQs</a> or <span class="i-phone"><?php echo $OPTION['wps_shop_questions_phone']; ?></span>
			<a href="mailto:<?php echo $OPTION['wps_shop_questions_email']; ?>" class="i-email"><?php echo $OPTION['wps_shop_questions_email']; ?></a>
		</p>
	</div>
</div>

<?php get_footer(); ?>