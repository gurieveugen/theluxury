<?php
/*
Template Name: Sell Your Item Page
*/
global $OPTION;
?>
<?php get_header(); ?>

<div class="text-center">
	<?php the_content(); ?>
	<div class="btn-sell-holder"><a href="<?php echo get_permalink($OPTION['wps_indvseller_add_item_page']); ?>" class="btn-sell">SELL YOUR ITEM</a></div>
</div>
<div class="text-row center">
	<h3>FIVE REASONS YOU SHOULD SELL YOUR ITEMS AT THE LUXURY CLOSET</h3>
</div>
<div class="reasons-block">
	<div class="item">
		<span class="icon">
			<i><img src="<?php bloginfo('template_url'); ?>/images/ico-money.png" alt="" /></i>
		</span>
		<h4>Get the maximum value for your products by selling on consignment</h4>
	</div>
	<div class="item">
		<span class="icon">
			<i><img src="<?php bloginfo('template_url'); ?>/images/ico-delivery.png" alt="" /></i>
		</span>
		<h4>Free pick-up of your item in the U.A.E and no up-front shipping costs for G.C.C customers</h4>
	</div>
	<div class="item">
		<span class="icon">
			<i><img src="<?php bloginfo('template_url'); ?>/images/ico-secure.png" alt="" /></i>
		</span>
		<h4>Secure, insured storage of your item at our temperature controlled facility</h4>
	</div>
	<div class="item">
		<span class="icon">
			<i><img src="<?php bloginfo('template_url'); ?>/images/ico-photography.png" alt="" /></i>
		</span>
		<h4>Professional photography and presentation of your item</h4>
	</div>
	<div class="item">
		<span class="icon">
			<i><img src="<?php bloginfo('template_url'); ?>/images/ico-globe.png" alt="" /></i>
		</span>
		<h4>Global reach <br />(we ship items to all corners of the globe every month!)</h4>
	</div>
</div>
<div class="info-area">
	<h3>WE ACCEPT</h3>
	 <div class="holder">
		<?php
		$tax_brands = get_terms('brand');
		if ($tax_brands) {
			$total_brands = count($tax_brands);
			$in_column = ceil($total_brands / 3);
			$cnmb = 1;
			?>
			<ul class="column">
				<?php foreach($tax_brands as $tax_brand) { ?>
				<li><a href="<?php echo get_term_link($tax_brand); ?>"><?php echo strtoupper($tax_brand->name); ?></a></li>
				<?php if ($cnmb == $in_column && $total_brands > 1) { $cnmb = 0; ?>
			</ul>
			<ul class="column">
				<?php } ?>
				<?php $cnmb++; $total_brands--; } ?>
			</ul>
		<?php } ?>
		<div class="categories-list">
			<a href="<?php echo get_category_link($OPTION['wps_women_bags_category']); ?>" class="item">
				<span class="icon">
					<i><img src="<?php bloginfo('template_url'); ?>/images/ico-handbags.png" alt="" /></i>
				</span>
				<strong>HANDBAGS</strong>
			</a>
			<a href="<?php echo get_category_link($OPTION['wps_women_shoes_category']); ?>" class="item">
				<span class="icon">
					<i><img src="<?php bloginfo('template_url'); ?>/images/ico-shoes.png" alt="" /></i>
				</span>
				<strong>SHOES</strong>
			</a>
			<a href="<?php echo get_category_link($OPTION['wps_women_watches_category']); ?>" class="item">
				<span class="icon">
					<i><img src="<?php bloginfo('template_url'); ?>/images/ico-watches.png" alt="" /></i>
				</span>
				<strong>WATCHES</strong>
			</a>
			<a href="<?php echo get_category_link($OPTION['wps_women_clothes_category']); ?>" class="item">
				<span class="icon">
					<i><img src="<?php bloginfo('template_url'); ?>/images/ico-clothes.png" alt="" /></i>
				</span>
				<strong>CLOTHES</strong>
			</a>
			<a href="<?php echo get_category_link($OPTION['wps_women_jewelry_category']); ?>" class="item">
				<span class="icon">
					<i><img src="<?php bloginfo('template_url'); ?>/images/ico-jewelry.png" alt="" /></i>
				</span>
				<strong>JEWELRY</strong>
			</a>
			<a href="<?php echo get_category_link($OPTION['wps_women_accessories_category']); ?>" class="item">
				<span class="icon">
					<i><img src="<?php bloginfo('template_url'); ?>/images/ico-accessories.png" alt="" /></i>
				</span>
				<strong>ACCESSORIES</strong>
			</a>
		</div>
	</div>
</div>
<div class="contact-data">
	<div class="contact-row">
		<p>
			Questions? <span class="i-phone"><?php echo $OPTION['wps_shop_questions_phone']; ?></span>
			<a href="mailto:<?php echo $OPTION['wps_shop_questions_email']; ?>" class="i-email"><?php echo $OPTION['wps_shop_questions_email']; ?></a>
		</p>
	</div>
	<div class="contact-row">
		<h4>ARE YOU A PROFESSIONAL SELLER?</h4>
		<a href="<?php echo get_permalink($OPTION['wps_professional_seller_page']); ?>" class="btn-yellow">CLICK HERE</a>
	</div>
</div>

<?php get_footer(); ?>