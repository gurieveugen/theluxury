<?php
/*
Template Name: Professional Seller Page
*/
global $OPTION;
?>
<?php get_header(); ?>
<div class="center-wrap-880 prof-seller-content content-cabin-font">
	<div class="prof-seller-block">
		<h4>Selling your items on the Luxury Closet is easy, safe and completely free of charge!</h4>
		<p>Fill out the Professional Seller Form and we will contact you within 5 business days</p>
		<a href="<?php echo get_permalink($OPTION['wps_professional_seller_form_page']); ?>" class="btn-yellow">PROFESSIONAL SELLER FORM</a>
	</div>
	<?php the_content(); ?>
</div>
<?php get_footer(); ?>