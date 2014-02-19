<?php
/*
Template Name: What Happens Next Page
*/
global $OPTION;
?>
<?php get_header(); ?>

<div class="next-section">
	<h2><?php the_title(); ?></h2>
	<div class="next-info">
		<div class="item">
			<div class="ico">
				<i><img src="<?php bloginfo('template_url'); ?>/images/ico-next-1.png" alt=""></i>
			</div>
			<h5>fill in item submission form</h5>
		</div>
		<div class="item">
			<div class="ico">
				<i><img src="<?php bloginfo('template_url'); ?>/images/ico-next-2.png" alt=""></i>
			</div>
			<h5>receive a quotation for your item</h5>
		</div>
		<div class="item">
			<div class="ico">
				<i><img src="<?php bloginfo('template_url'); ?>/images/ico-next-3.png" alt=""></i>
			</div>
			<h5>send us <br/>your item</h5>
		</div>
		<div class="item">
			<div class="ico">
				<i><img src="<?php bloginfo('template_url'); ?>/images/ico-next-4.png" alt=""></i>
			</div>
			<h5>your item gets photographed</h5>
		</div>
		<div class="item">
			<div class="ico">
				<i><img src="<?php bloginfo('template_url'); ?>/images/ico-next-5.png" alt=""></i>
			</div>
			<h5>your item <br/>goes on sale</h5>
		</div>
		<div class="item">
			<div class="ico">
				<i><img src="<?php bloginfo('template_url'); ?>/images/ico-next-6.png" alt=""></i>
			</div>
			<h5>your item sells</h5>
		</div>
		<div class="item">
			<div class="ico">
				<i><img src="<?php bloginfo('template_url'); ?>/images/ico-next-7.png" alt=""></i>
			</div>
			<h5>we send you your money</h5>
		</div>
	</div>
	<div class="center">
		<a href="<?php echo get_permalink($OPTION['wps_indvseller_add_item_page']); ?>" class="btn-yellow btn-y-1">Sell Another Item</a>
	</div>
</div>
<div class="contact-data">
	<div class="contact-row">
		<p>
			Questions? <span class="i-phone"><?php echo $OPTION['wps_shop_questions_phone']; ?></span>
			<a href="mailto:<?php echo $OPTION['wps_shop_questions_email']; ?>" class="i-email"><?php echo $OPTION['wps_shop_questions_email']; ?></a>
		</p>
	</div>
</div>

<?php get_footer(); ?>