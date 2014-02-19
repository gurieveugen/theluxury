<?php
/*
Template Name: Single Product
*/
?>
<?php get_header(); ?>

<div class="main-product">
	<h1>Christian Dior Red Tote</h1>
	<div class="product-holder">
		<div class="product-content">
			<div class="image-box">
				<div class="holder">
					<div class="frame">
						<img src="<?php bloginfo('template_url') ?>/images/product/img-1.png" width="391" height="394" alt="image description" />
						<span class="zoom">zoom</span>
					</div>
				</div>
			</div>
			<div class="thumbnails">
				<a href="#" class="active"><img src="<?php bloginfo('template_url') ?>/images/product/img-1.jpg" width="61" height="61" alt="image description" /></a>
				<a href="#"><img src="<?php bloginfo('template_url') ?>/images/product/img-2.jpg" width="61" height="61" alt="image description" /></a>
				<a href="#"><img src="<?php bloginfo('template_url') ?>/images/product/img-3.jpg" width="61" height="61" alt="image description" /></a>
				<a href="#"><img src="<?php bloginfo('template_url') ?>/images/product/img-4.jpg" width="61" height="61" alt="image description" /></a>
				<a href="#"><img src="<?php bloginfo('template_url') ?>/images/product/img-5.jpg" width="61" height="61" alt="image description" /></a>
				<a href="#"><img src="<?php bloginfo('template_url') ?>/images/product/img-6.jpg" width="61" height="61" alt="image description" /></a>
				<a href="#"><img src="<?php bloginfo('template_url') ?>/images/product/img-7.jpg" width="61" height="61" alt="image description" /></a>
				<a href="#"><img src="<?php bloginfo('template_url') ?>/images/product/img-8.jpg" width="61" height="61" alt="image description" /></a>
			</div>
			<div class="accordion">
				<div class="accordion-item">
					<div class="heading">
						<span class="icon"></span>
						<h3>You may also like</h3>
					</div>
					<div class="content">
						Lorem ipsum dolor sit amet
					</div>
				</div>
				<div class="accordion-item open">
					<div class="heading">
						<span class="icon"></span>
						<h3>Recently Added</h3>
					</div>
					<div class="content">
						<div class="products-list">
							<div class="item">
								<a href="#" class="image">
									<img src="<?php bloginfo('template_url') ?>/images/product/img-9.jpg" width="64" height="64" alt="image description" />
								</a>
								<div class="holder">
									<p>Chanel Timeless</p>
									<p>CC Tote Pony Hair</p>
									<h4>1,486 USD</h4>
								</div>
							</div>
							<div class="item">
								<a href="#" class="image">
									<img src="<?php bloginfo('template_url') ?>/images/product/img-9.jpg" width="64" height="64" alt="image description" />
								</a>
								<div class="holder">
									<p>Chanel Timeless</p>
									<p>CC Tote Pony Hair</p>
									<h4>1,486 USD</h4>
								</div>
							</div>
							<div class="item">
								<a href="#" class="image">
									<img src="<?php bloginfo('template_url') ?>/images/product/img-9.jpg" width="64" height="64" alt="image description" />
								</a>
								<div class="holder">
									<p>Chanel Timeless</p>
									<p>CC Tote Pony Hair</p>
									<h4>1,486 USD</h4>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="product-sidebar">
			<div class="price-row">
				<h2>Price: <span class="old-price">782</span></h2>
				<h2 class="price">400 AED</h2>
			</div>
			<div class="data-section">
				<div class="buttons">
					<a href="#" class="btn-sold-out">Sold out</a>
					<a href="#" class="btn-installments">Buy in Installments</a>
					<a href="#" class="btn-installments disable">Buy in Installments</a>
					<a href="#" class="btn-request">Request this product</a>
					<a href="#" class="btn-unavailable">Unavailable</a>
					<a href="#" class="btn-buy-now">Buy now</a>
				</div>
				<div class="check-column">
					<div class="check-row">
						<input type="checkbox" id="lbl-1" name="" />
						<label for="lbl-1">Add to Wishlist</label>
					</div>
					<div class="check-row">
						<input type="checkbox" id="lbl-2" name="" />
						<label for="lbl-2">Add to Wishlist</label>
					</div>
				</div>
			</div>
			<div class="data-section section-1">
				<a href="#" class="btn-delivery">AVAILABLE Cash on Delivery</a>
				<div class="text-holder">
					<p>Stock on hand : <strong>1</strong></p>
					<p>People viewing this bag : <strong>8</strong></p>
				</div>
			</div>
			<div class="data-section">
				<div class="accordion">
					<div class="accordion-item">
						<div class="heading">
							<span class="icon"></span>
							<h3>Product Info</h3>
						</div>
						<div class="content">
							Lorem ipsum dolor sit amet
						</div>
					</div>
					<div class="accordion-item">
						<div class="heading">
							<span class="icon"></span>
							<h3>Comments</h3>
						</div>
						<div class="content">
							Lorem ipsum dolor sit amet
						</div>
					</div>
					<div class="accordion-item">
						<div class="heading">
							<span class="icon"></span>
							<h3>Shipping Info</h3>
						</div>
						<div class="content">
							Lorem ipsum dolor sit amet
						</div>
					</div>
					<div class="accordion-item open">
						<div class="heading">
							<span class="icon"></span>
							<h3>Bag Measurements</h3>
						</div>
						<div class="content">
							<p>Handle Drop is measured from high point of the strap to bag opening.</p>
							<div class="center">
								<img src="<?php bloginfo('template_url') ?>/images/product/img-10.jpg" width="249" height="201" alt="image description" />
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>