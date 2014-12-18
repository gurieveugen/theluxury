<?php get_header(); ?>
	<?php if (have_posts()) : the_post(); ?>
	<div class="center-wrap bdbox">
		<div class="h-container-index cf">
			<div class="b-box-promo left">
				<a href="<?php the_field('sec_A_link'); ?>"><img src="<?php the_field('sec_A_image'); ?>" alt=""/></a>
			</div>
			<div class="h-cat-boxes right">
				<?php for($b=1; $b<=4; $b++) { ?>
					<div class="b-box-cat">
						<div class="image">
							<a href="<?php the_field('sec_B_link_'.$b); ?>"><img src="<?php the_field('sec_B_image_'.$b); ?>" alt="" /></a>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		<div class="title-line mb0">
			<h3><?php the_field('sec_C_title'); ?></h3>
		</div>
		<?php $sec_C_brands = get_field('sec_C_brands');
		if ($sec_C_brands) { ?>
			<div class="b-carousel carousel-brands bdb">
				<div class="jcarousel">
					<ul>
						<?php foreach($sec_C_brands as $sec_C_brand) { ?>
							<li class="b-block-brand">
								<a href="<?php echo get_term_link($sec_C_brand['brand'], 'brand'); ?>" title="<?php echo $sec_C_brand['brand']->name; ?>">
									<div class="image">
										<img src="<?php echo $sec_C_brand['brand_image']; ?>" alt="Image 1">
									</div>
									<strong><?php echo $sec_C_brand['brand']->name; ?></strong>
								</a>
							</li>
						<?php } ?>
					</ul>
				</div>
				<a href="#" class="flex-prev">&lsaquo;</a>
				<a href="#" class="flex-next">&rsaquo;</a>
			</div>
			<script>
			(function($) {
				$(function() {
					$('.carousel-brands .jcarousel').jcarousel({ wrap: 'circular' });
					$('.carousel-brands .flex-prev').jcarouselControl({ target: '-=1' });
					$('.carousel-brands .flex-next').jcarouselControl({ target: '+=1' });
				});
			})(jQuery);
			</script>
		<?php } ?>
		<div class="h-blocks-promo">
			<?php for($d=1; $d<=3; $d++) { ?>
				<div class="b-block-promo">
					<a href="<?php the_field('sec_D_link_'.$d); ?>"><img src="<?php the_field('sec_D_image_'.$d); ?>" alt="" /></a>
				</div>
			<?php } ?>
		</div>

		<?php //dynamic_sidebar('frontpage_bottom_widget_area'); ?>
		<script>
		/*(function($) {
			$(function() {
				$('.carousel-products .jcarousel').jcarousel({ wrap: 'circular' });
				$('.carousel-products .flex-prev').jcarouselControl({ target: '-=1' });
				$('.carousel-products .flex-next').jcarouselControl({ target: '+=1' });
			});
		})(jQuery);*/
		</script>
		<div class="title-line">
			<h3>Recommended for you</h3>
		</div>
		<div class="b-carousel carousel-products" id="carousel-recommended"></div>
		<script type="text/html" id="my-recommender-template" >
		<![CDATA[
		{{ if (SC.page.products.length) { }}
		<div class="jcarousel">
			<!--<div class="scarab-prev">?</div>-->
			<ul class="recently-added-list slides">
			{{ for (var i=0; i < SC.page.products.length; i++) { }}
			  {{ var p = SC.page.products[i]; }}
				<li class="b-product-item">
					<a href="{{= p.link }}" class="image">
						<img src="{{= p.image }}" alt="" style="height:150px;" />
					</a>
					<h5 class="title"><a href="{{= p.link }}">{{= p.title }}</a></h5>
					<div class="price-row cf">
						${{= p.price.toFixed(0) }}
					</div>
			  </li>
			{{ } }}
			</ul>
			<!--<div class="scarab-next">?</div>-->
		</div>
		<a href="#" class="scarab-prev flex-prev" onclick="return false;">&lsaquo;</a>
		<a href="#" class="scarab-next flex-next" onclick="return false;">&rsaquo;</a>
		{{ } }}
		]]>
		</script>
		<script>
		ScarabQueue.push(['recommend', {
			logic: 'PERSONAL',
			containerId: 'carousel-recommended',
			templateId: "my-recommender-template"
		}]);
		ScarabQueue.push(['go']);
		</script>

		<div class="h-cat-boxes-row cf">
			<?php for($h=1; $h<=4; $h++) { ?>
				<div class="b-box-cat">
					<a href="<?php the_field('sec_H_link_'.$h); ?>"><img src="<?php the_field('sec_H_image_'.$h); ?>" alt="" /></a>
				</div>
			<?php } ?>
		</div>
		<div class="title-line">
			<h3>As Featured In</h3>
		</div>
		<ul class="featured-logos">
			<li><img src="<?php echo LIGHTTEMPLURL; ?>/images/logo-cosmopolitan-black.png" alt="cosmopolitan"></li>
			<li><img src="<?php echo LIGHTTEMPLURL; ?>/images/logo-elle-black.png" alt="elle"></li>
			<li><img src="<?php echo LIGHTTEMPLURL; ?>/images/logo-whatson-black.png" alt="whatson"></li>
			<li><img src="<?php echo LIGHTTEMPLURL; ?>/images/logo-grazia-black.png" alt="grazia"></li>
			<li><img src="<?php echo LIGHTTEMPLURL; ?>/images/logo-hello-black.png" alt="hello"></li>
			<li><img src="<?php echo LIGHTTEMPLURL; ?>/images/logo-timeout-black.png" alt="timeout"></li>
			<li><img src="<?php echo LIGHTTEMPLURL; ?>/images/logo-ahlan-black.png" alt="ahlan"></li>
		</ul>
		<div class="title-line">
			<h3>3 Steps To Selling Your Item!</h3>
		</div>
		<div class="b-s-steps">
			<div class="item">
				<div class="holder cf">
					<span class="num">1.</span>
					<div class="icon">
						<img src="<?php echo LIGHTTEMPLURL; ?>/images/ico-s-step-1.png" alt="" />
					</div>
				</div>
				<strong>Submit Item</strong>
			</div>
			<div class="item">
				<div class="holder cf">
					<span class="num">2.</span>
					<div class="icon">
						<img src="<?php echo LIGHTTEMPLURL; ?>/images/ico-s-step-2.png" alt="" />
					</div>
				</div>
				<strong>Select your payout</strong>
			</div>
			<div class="item">
				<div class="holder cf">
					<span class="num">3.</span>
					<div class="icon">
						<img src="<?php echo LIGHTTEMPLURL; ?>/images/ico-s-step-3.png" alt="" />
					</div>
				</div>
				<strong>Get paid!</strong>
			</div>
			<a href="/sell-us/" class="link-more">Learn More</a>
		</div>
	</div>
	<?php endif; ?>
<?php get_footer(); ?>