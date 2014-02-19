<?php
/*
Template Name: Sell item step 2
*/
global $OPTION, $current_user;

get_header();
?>
<div class="user-info-row">
	<div class="right">
		<a href="#">Questions? Take the Tour.</a>
	</div>
	<h1 class="main-title">graphicartist.sunil@gmail.com</h1>
</div>
<div class="add-item-steps">
	<div class="step">
		<a href="#" class="ico">1</a>
		<strong>Submit an item</strong>
	</div>
	<div class="step active">
		<a href="#" class="ico">2</a>
		<strong>Manage items</strong>
	</div>
	<div class="step last">
		<a href="#" class="ico">3</a>
		<strong>Payment</strong>
	</div>
</div>
<div class="text-step">
	<p>
		Thank you, your item has been submitted. You will receive a quotation within 4 business days and you will be able to select your payout below.
	</p>
</div>
<div class="indvseller-my-items">
	<div id="my-items" class="items-list">
		<div class="a-box open">
			<div class="a-title">
				<h3>Submitted Items</h3>
				<span class="ico-question"></span>
				<span class="ico"></span>
			</div>
			<div class="a-content">
				<div class="seller-products-list">
					<div class="product-item">
						<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/Screenshot_1-91x91.png" class="thumbnail" alt="">
						<div class="last-column">
							<a href="#edit" class="ico-link edit" rel="19799">Edit</a>
						</div>
						<div class="description">
							<h4>test Name</h4>
						</div>
					</div>
					<div class="product-item">
						<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/10304-91x91.jpg" width="70px" class="thumbnail" alt="">
						<div class="last-column">
							<a href="#edit" class="ico-link edit" rel="19670">Edit</a>
						</div>
						<div class="description">
							<h4>Test item from tlc 222</h4>
						</div>
					</div>
					<div class="product-item">
						<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/77812-91x91.jpg" width="70px" class="thumbnail" alt="">
						<div class="last-column">
							<a href="#edit" class="ico-link edit" rel="19614">Edit</a>
						</div>
						<div class="description">
							<h4>Testing 001</h4>
						</div>
					</div>
					<div class="product-item">
						<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/4601-91x91.jpg" width="70px" class="thumbnail" alt="">
						<div class="last-column">
							<a href="#edit" class="ico-link edit" rel="19611">Edit</a>
						</div>
						<div class="description">
							<h4>UTM Testing</h4>
						</div>
					</div>
				</div>
			</div>
			<div class="num">
				<i>1</i><span></span>
			</div>
		</div>
		<div class="a-box open">
			<div class="a-title">
				<h3>Select your Payout</h3>
				<span class="ico-question"></span>
				<span class="ico"></span>
			</div>
			<div class="a-content">
				<div class="seller-products-list">
					<div class="product-item">
						<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/devon-tread-1-xl-91x91.jpg" width="70px" class="thumbnail" alt="">
						<div class="description fixed-width">
							<h4>Watches Test</h4>
							<p>
								LC-6871-19716
							</p>
						</div>
						<div class="column" style="margin-right:15px;">
							<p class="help">
								Your Payout <a href="#your-payout" class="help" rel="yp-desc-19716">&nbsp;</a>
							</p>
							<div class="help-desc yp-desc-19716">
								<p>
									Lorem
									ipsum dolor sit amet, consectetur adipiscing elit. Curabitur luctus
									nisi ut libero viverra pharetra. Praesent suscipit bibendum semper.
									Donec bibendum magna enim.
								</p>
								<p>
									Quisque vehicula enim magna, venenatis feugiat magna. Integer lorem
									urna, pellentesque sit amet consectetur et, interdum dignissim ante.
									Suspendisse pharetra, mi et dictum sagittis, justo nisl tempus massa, a
									porttitor mauris libero ac mauris.
								</p>
							</div><p></p>
							<p>
								<a href="#no-quotation" class="no-quotation">No Quotation</a>
							</p>
						</div>
						<div class="last-column">
							<a href="#edit" class="ico-link edit" rel="19716">Edit</a>
						</div>
					</div>
					<div class="product-item">
						<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/2c6a325e888d8bf5d5ed-91x91.jpg" width="70px" class="thumbnail" alt="">
						<div class="description fixed-width">
							<h4>Testing 001</h4>
							<p>
								LC-6871-19567
							</p>
						</div>
						<div class="column" style="margin-right:15px;">
							<p class="help">
								Your Payout <a href="#your-payout" class="help" rel="yp-desc-19567">&nbsp;</a>
							</p>
							<div class="help-desc yp-desc-19567">
								<p>
									Lorem
									ipsum dolor sit amet, consectetur adipiscing elit. Curabitur luctus
									nisi ut libero viverra pharetra. Praesent suscipit bibendum semper.
									Donec bibendum magna enim.
								</p>
								<p>
									Quisque vehicula enim magna, venenatis feugiat magna. Integer lorem
									urna, pellentesque sit amet consectetur et, interdum dignissim ante.
									Suspendisse pharetra, mi et dictum sagittis, justo nisl tempus massa, a
									porttitor mauris libero ac mauris.
								</p>
							</div><p></p>
							<p>
								<span class="item-your-quotation-price-19567">10 - 8</span> OMR
							</p>
						</div>
						<div class="column select-payout-19567" style="width:140px; margin-right:5px;">
							<p class="help">
								Select Payout <a href="#select-payout" class="help" rel="sp-desc-19567">&nbsp;</a>
							</p>
							<div class="help-desc sp-desc-19567">
								<p>
									Nam
									lacus sapien, eleifend tempus dapibus nec, vehicula id nisl. Sed ante
									urna, convallis vitae lobortis ac, semper et dui. Praesent sed
									adipiscing est. Nulla facilisi. Sed nibh neque, blandit at condimentum
									in, iaculis quis odio. Vivamus ac ullamcorper est.
								</p>
							</div><p></p>
							<div class="select-payout">
								<input id="item-tlc-min-price-19567" value="8" type="hidden">
								<input id="item-tlc-max-price-19567" value="10" type="hidden">
								<input name="item_your_quotation_price" id="item-your-quotation-price-19567" type="text">
							</div>
						</div>
						<div class="last-column">
							<a href="#submit" class="ico-link submit-payout submit-payout-19567" rel="19567" style="width:60px;">Approve</a>
							<span class="ico-link submited submited-payout-19567" style="display:none;">Approved</span>
							<a href="#edit" class="ico-link edit" rel="19567">Edit</a>
						</div>
					</div>
					<div class="product-item">
						<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/Omlun-2012-91x91.jpg" width="70px" class="thumbnail" alt="">
						<div class="description fixed-width">
							<h4>Hivista Men Bag</h4>
							<p>
								LC-6871-19462
							</p>
						</div>
						<div class="column" style="margin-right:15px;">
							<p class="help">
								Your Payout <a href="#your-payout" class="help" rel="yp-desc-19462">&nbsp;</a>
							</p>
							<div class="help-desc yp-desc-19462">
								<p>
									Lorem
									ipsum dolor sit amet, consectetur adipiscing elit. Curabitur luctus
									nisi ut libero viverra pharetra. Praesent suscipit bibendum semper.
									Donec bibendum magna enim.
								</p>
								<p>
									Quisque vehicula enim magna, venenatis feugiat magna. Integer lorem
									urna, pellentesque sit amet consectetur et, interdum dignissim ante.
									Suspendisse pharetra, mi et dictum sagittis, justo nisl tempus massa, a
									porttitor mauris libero ac mauris.
								</p>
							</div><p></p>
							<p>
								<span class="item-your-quotation-price-19462">57 - 42</span> OMR
							</p>
						</div>
						<div class="column select-payout-19462" style="width:140px; margin-right:5px;">
							<p class="help">
								Select Payout <a href="#select-payout" class="help" rel="sp-desc-19462">&nbsp;</a>
							</p>
							<div class="help-desc sp-desc-19462">
								<p>
									Nam
									lacus sapien, eleifend tempus dapibus nec, vehicula id nisl. Sed ante
									urna, convallis vitae lobortis ac, semper et dui. Praesent sed
									adipiscing est. Nulla facilisi. Sed nibh neque, blandit at condimentum
									in, iaculis quis odio. Vivamus ac ullamcorper est.
								</p>
							</div><p></p>
							<div class="select-payout">
								<input id="item-tlc-min-price-19462" value="42" type="hidden">
								<input id="item-tlc-max-price-19462" value="57" type="hidden">
								<input name="item_your_quotation_price" id="item-your-quotation-price-19462" type="text">
							</div>
						</div>
						<div class="last-column">
							<a href="#submit" class="ico-link submit-payout submit-payout-19462" rel="19462" style="width:60px;">Approve</a>
							<span class="ico-link submited submited-payout-19462" style="display:none;">Approved</span>
							<a href="#edit" class="ico-link edit" rel="19462">Edit</a>
						</div>
					</div>
					<div class="product-item">
						<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/kostas_shoe_men_khaki-91x91.jpg" width="70px" class="thumbnail" alt="">
						<div class="description fixed-width">
							<h4>Testing</h4>
							<p></p>
						</div>
						<div class="column" style="margin-right:15px;">
							<p class="help">
								Your Payout <a href="#your-payout" class="help" rel="yp-desc-19379">&nbsp;</a>
							</p>
							<div class="help-desc yp-desc-19379">
								<p>
									Lorem
									ipsum dolor sit amet, consectetur adipiscing elit. Curabitur luctus
									nisi ut libero viverra pharetra. Praesent suscipit bibendum semper.
									Donec bibendum magna enim.
								</p>
								<p>
									Quisque vehicula enim magna, venenatis feugiat magna. Integer lorem
									urna, pellentesque sit amet consectetur et, interdum dignissim ante.
									Suspendisse pharetra, mi et dictum sagittis, justo nisl tempus massa, a
									porttitor mauris libero ac mauris.
								</p>
							</div><p></p>
							<p>
								<a href="#no-quotation" class="no-quotation">No Quotation</a>
							</p>
						</div>
						<div class="last-column">
							<a href="#edit" class="ico-link edit" rel="19379">Edit</a>
						</div>
					</div>
					<div class="product-item">
						<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/w4-91x91.jpg" width="70px" class="thumbnail" alt="">
						<div class="description fixed-width">
							<h4>Hivista Indiv Test Watch 2</h4>
							<p></p>
						</div>
						<div class="column" style="margin-right:15px;">
							<p class="help">
								Your Payout <a href="#your-payout" class="help" rel="yp-desc-19245">&nbsp;</a>
							</p>
							<div class="help-desc yp-desc-19245">
								<p>
									Lorem
									ipsum dolor sit amet, consectetur adipiscing elit. Curabitur luctus
									nisi ut libero viverra pharetra. Praesent suscipit bibendum semper.
									Donec bibendum magna enim.
								</p>
								<p>
									Quisque vehicula enim magna, venenatis feugiat magna. Integer lorem
									urna, pellentesque sit amet consectetur et, interdum dignissim ante.
									Suspendisse pharetra, mi et dictum sagittis, justo nisl tempus massa, a
									porttitor mauris libero ac mauris.
								</p>
							</div><p></p>
							<p>
								<a href="#no-quotation" class="no-quotation">No Quotation</a>
							</p>
						</div>
						<div class="last-column">
							<a href="#edit" class="ico-link edit" rel="19245">Edit</a>
						</div>
					</div>
					<div class="product-item">
						<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/s4-91x91.jpg" width="70px" class="thumbnail" alt="">
						<div class="description fixed-width">
							<h4>Hivista Individual Test 4</h4>
							<p>
								LC-3333-19241
							</p>
						</div>
						<div class="column" style="margin-right:15px;">
							<p class="help">
								Your Payout <a href="#your-payout" class="help" rel="yp-desc-19241">&nbsp;</a>
							</p>
							<div class="help-desc yp-desc-19241">
								<p>
									Lorem
									ipsum dolor sit amet, consectetur adipiscing elit. Curabitur luctus
									nisi ut libero viverra pharetra. Praesent suscipit bibendum semper.
									Donec bibendum magna enim.
								</p>
								<p>
									Quisque vehicula enim magna, venenatis feugiat magna. Integer lorem
									urna, pellentesque sit amet consectetur et, interdum dignissim ante.
									Suspendisse pharetra, mi et dictum sagittis, justo nisl tempus massa, a
									porttitor mauris libero ac mauris.
								</p>
							</div><p></p>
							<p>
								<a href="#no-quotation" class="no-quotation">No Quotation</a>
							</p>
						</div>
						<div class="last-column">
							<a href="#edit" class="ico-link edit" rel="19241">Edit</a>
						</div>
					</div>
					<div class="product-item">
						<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/s3-91x91.jpg" width="70px" class="thumbnail" alt="">
						<div class="description fixed-width">
							<h4>Hivista Individual Test 3</h4>
							<p>
								HIT-3
							</p>
						</div>
						<div class="column" style="margin-right:15px;">
							<p class="help">
								Your Payout <a href="#your-payout" class="help" rel="yp-desc-19239">&nbsp;</a>
							</p>
							<div class="help-desc yp-desc-19239">
								<p>
									Lorem
									ipsum dolor sit amet, consectetur adipiscing elit. Curabitur luctus
									nisi ut libero viverra pharetra. Praesent suscipit bibendum semper.
									Donec bibendum magna enim.
								</p>
								<p>
									Quisque vehicula enim magna, venenatis feugiat magna. Integer lorem
									urna, pellentesque sit amet consectetur et, interdum dignissim ante.
									Suspendisse pharetra, mi et dictum sagittis, justo nisl tempus massa, a
									porttitor mauris libero ac mauris.
								</p>
							</div><p></p>
							<p>
								<a href="#no-quotation" class="no-quotation">No Quotation</a>
							</p>
						</div>
						<div class="last-column">
							<a href="#edit" class="ico-link edit" rel="19239">Edit</a>
						</div>
					</div>
					<div class="product-item">
						<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/b4-91x91.jpg" width="70px" class="thumbnail" alt="">
						<div class="description fixed-width">
							<h4>Hivista Individual Test 2</h4>
							<p>
								HIT2
							</p>
						</div>
						<div class="column" style="margin-right:15px;">
							<p class="help">
								Your Payout <a href="#your-payout" class="help" rel="yp-desc-19237">&nbsp;</a>
							</p>
							<div class="help-desc yp-desc-19237">
								<p>
									Lorem
									ipsum dolor sit amet, consectetur adipiscing elit. Curabitur luctus
									nisi ut libero viverra pharetra. Praesent suscipit bibendum semper.
									Donec bibendum magna enim.
								</p>
								<p>
									Quisque vehicula enim magna, venenatis feugiat magna. Integer lorem
									urna, pellentesque sit amet consectetur et, interdum dignissim ante.
									Suspendisse pharetra, mi et dictum sagittis, justo nisl tempus massa, a
									porttitor mauris libero ac mauris.
								</p>
							</div><p></p>
							<p>
								<a href="#no-quotation" class="no-quotation">No Quotation</a>
							</p>
						</div>
						<div class="last-column">
							<a href="#edit" class="ico-link edit" rel="19237">Edit</a>
						</div>
					</div>
					<div class="product-item">
						<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/b3-91x91.jpg" width="70px" class="thumbnail" alt="">
						<div class="description fixed-width">
							<h4>Hivista Individual Test 1</h4>
							<p>
								HIT-111
							</p>
						</div>
						<div class="column" style="margin-right:15px;">
							<p class="help">
								Your Payout <a href="#your-payout" class="help" rel="yp-desc-19235">&nbsp;</a>
							</p>
							<div class="help-desc yp-desc-19235">
								<p>
									Lorem
									ipsum dolor sit amet, consectetur adipiscing elit. Curabitur luctus
									nisi ut libero viverra pharetra. Praesent suscipit bibendum semper.
									Donec bibendum magna enim.
								</p>
								<p>
									Quisque vehicula enim magna, venenatis feugiat magna. Integer lorem
									urna, pellentesque sit amet consectetur et, interdum dignissim ante.
									Suspendisse pharetra, mi et dictum sagittis, justo nisl tempus massa, a
									porttitor mauris libero ac mauris.
								</p>
							</div><p></p>
							<p>
								<a href="#no-quotation" class="no-quotation">No Quotation</a>
							</p>
						</div>
						<div class="last-column">
							<a href="#edit" class="ico-link edit" rel="19235">Edit</a>
						</div>
					</div>
				</div>
			</div>
			<div class="num">
				<i>2</i><span></span>
			</div>
		</div>
		<div class="a-box open">
			<div class="a-title">
				<h3>Awaiting Pickup</h3>
				<span class="ico-question"></span>
				<span class="ico"></span>
			</div>
			<div class="a-content">
				<div class="seller-products-list">
					<div class="product-item">
						<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/77813-91x91.jpg" width="70px" class="thumbnail" alt="">
						<div class="description">
							<h4>Hivista Test 111222333</h4>
							<p>
								LC-6871-19661
							</p>
							<div class="price-row">
								<span class="price"><strong>Your Payout:</strong> 380 OMR</span>
								<span class="price"><strong>The Luxury Closet Selling Price:</strong> 553 OMR</span>
							</div>
						</div>
					</div>
					<div class="product-item">
						<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/2221-91x91.jpg" width="70px" class="thumbnail" alt="">
						<div class="description">
							<h4>Testing 004</h4>
							<p>
								LC-6871-19573
							</p>
							<div class="price-row">
								<span class="price"><strong>Your Payout:</strong> 27 OMR</span>
								<span class="price"><strong>The Luxury Closet Selling Price:</strong> 38 OMR</span>
							</div>
						</div>
					</div>
					<div class="product-item">
						<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/3331-91x91.jpg" width="70px" class="thumbnail" alt="">
						<div class="description">
							<h4>Testing 003</h4>
							<p>
								LC-6871-19571
							</p>
							<div class="price-row">
								<span class="price"><strong>Your Payout:</strong> 10 OMR</span>
								<span class="price"><strong>The Luxury Closet Selling Price:</strong> 15 OMR</span>
							</div>
						</div>
					</div>
					<div class="product-item">
						<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/444-91x91.jpg" width="70px" class="thumbnail" alt="">
						<div class="description">
							<h4>Testing 002</h4>
							<p>
								LC-6871-19569
							</p>
							<div class="price-row">
								<span class="price"><strong>Your Payout:</strong> 11 OMR</span>
								<span class="price"><strong>The Luxury Closet Selling Price:</strong> 15 OMR</span>
							</div>
						</div>
					</div>
					<div class="product-item">
						<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/222-91x91.jpg" width="70px" class="thumbnail" alt="">
						<div class="description">
							<h4>Test XXX</h4>
							<p>
								LC-6871-19560
							</p>
							<div class="price-row">
								<span class="price"><strong>Your Payout:</strong> 31 OMR</span>
								<span class="price"><strong>The Luxury Closet Selling Price:</strong> 38 OMR</span>
							</div>
						</div>
					</div>
					<div class="product-item">
						<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/h2-91x91.jpg" width="70px" class="thumbnail" alt="">
						<div class="description">
							<h4>TEST Handbags 2</h4>
							<p>
								LC-6871-19526
							</p>
							<div class="price-row">
								<span class="price"><strong>Your Payout:</strong> 10 OMR</span>
								<span class="price"><strong>The Luxury Closet Selling Price:</strong> 21 OMR</span>
							</div>
						</div>
					</div>
					<div class="product-item">
						<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/h1-91x91.jpg" width="70px" class="thumbnail" alt="">
						<div class="description">
							<h4>TEST Handbags 1</h4>
							<p>
								LC-6871-19524
							</p>
							<div class="price-row">
								<span class="price"><strong>Your Payout:</strong> 10 OMR</span>
								<span class="price"><strong>The Luxury Closet Selling Price:</strong> 10 OMR</span>
							</div>
						</div>
					</div>
					<div class="product-item">
						<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/s5-91x91.jpg" width="70px" class="thumbnail" alt="">
						<div class="description">
							<h4>TEST 5</h4>
							<p>
								LC-6871-19510
							</p>
							<div class="price-row">
								<span class="price"><strong>Your Payout:</strong> 52 OMR</span>
								<span class="price"><strong>The Luxury Closet Selling Price:</strong> 58 OMR</span>
							</div>
						</div>
					</div>
					<div class="product-item">
						<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/s41-91x91.jpg" width="70px" class="thumbnail" alt="">
						<div class="description">
							<h4>TEST 4</h4>
							<p>
								LC-6871-19508
							</p>
							<div class="price-row">
								<span class="price"><strong>Your Payout:</strong> 42 OMR</span>
								<span class="price"><strong>The Luxury Closet Selling Price:</strong> 46 OMR</span>
							</div>
						</div>
					</div>
					<div class="product-item">
						<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/s31-91x91.jpg" width="70px" class="thumbnail" alt="">
						<div class="description">
							<h4>TEST 3</h4>
							<p>
								LC-6871-19506
							</p>
							<div class="price-row">
								<span class="price"><strong>Your Payout:</strong> 31 OMR</span>
								<span class="price"><strong>The Luxury Closet Selling Price:</strong> 35 OMR</span>
							</div>
						</div>
					</div>
					<div class="product-item">
						<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/s21-91x91.jpg" width="70px" class="thumbnail" alt="">
						<div class="description">
							<h4>TEST 2</h4>
							<p>
								LC-6871-19504
							</p>
							<div class="price-row">
								<span class="price"><strong>Your Payout:</strong> 21 OMR</span>
								<span class="price"><strong>The Luxury Closet Selling Price:</strong> 23 OMR</span>
							</div>
						</div>
					</div>
					<div class="product-item">
						<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/s11-91x91.jpg" width="70px" class="thumbnail" alt="">
						<div class="description">
							<h4>TEST 1</h4>
							<p>
								LC-6871-19502
							</p>
							<div class="price-row">
								<span class="price"><strong>Your Payout:</strong> 10 OMR</span>
								<span class="price"><strong>The Luxury Closet Selling Price:</strong> 11 OMR</span>
							</div>
						</div>
					</div>
					<div class="product-item">
						<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/diamond-jewelry.jpg" width="70px" class="thumbnail" alt="">
						<div class="description">
							<h4>Test Jewelry</h4>
							<p>
								LC-6871-19475
							</p>
							<div class="price-row">
								<span class="price"><strong>Your Payout:</strong> 15,960 OMR</span>
								<span class="price"><strong>The Luxury Closet Selling Price:</strong> 19,000 OMR</span>
							</div>
						</div>
					</div>
					<div class="product-item">
						<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/ms3-91x91.jpg" width="70px" class="thumbnail" alt="">
						<div class="description">
							<h4>Testing 2</h4>
							<p></p>
							<div class="price-row">
								<span class="price"><strong>Your Payout:</strong> 1,520 OMR</span>
								<span class="price"><strong>The Luxury Closet Selling Price:</strong> 1,900 OMR</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="num">
				<i>3</i><span></span>
			</div>
		</div>
		<div class="a-box open">
			<div class="a-title">
				<h3>Your items on sale</h3>
				<span class="ico-question"></span>
				<span class="ico"></span>
			</div>
			<div class="a-content">
				<div class="seller-products-list">
					<p>
						You currently have 0 items on sale.
					</p>
				</div>
			</div>
			<div class="num">
				<i>4</i><span></span>
			</div>
		</div>
		<div class="a-box open last">
			<div class="a-title">
				<h3>Sold Items</h3>
				<span class="ico-question"></span>
				<span class="ico"></span>
			</div>
			<div class="a-content">
				<div class="seller-products-list">
					<div class="product-item">
						<a href="http://luxcloset.staging.wpengine.com/women/womens-handbags/shoes-testing" class="thumbnail"><img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/img_temp/cheap-supra-304-shoes-91x91.jpg" width="70px" alt=""></a>
						<div class="description">
							<h4><a href="http://luxcloset.staging.wpengine.com/women/womens-handbags/shoes-testing">Shoes Testing</a></h4>
							<p>
								LC-6871-19489
							</p>
							<div class="price-row">
								<span class="price"><strong>Your Payout:</strong> 38 OMR</span>
								<span class="price"><strong>The Luxury Closet Selling Price:</strong> 46 OMR</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="num">
				<i>5</i><span></span>
			</div>
		</div>
		<form id="indivseller-action-form" method="POST">
			<input name="editurl" value="http://luxcloset.staging.wpengine.com/sell/edit-item" id="indivseller-editurl" type="hidden">
			<input name="SellersAction" id="indivseller-action" type="hidden">
			<input name="post_id" id="indivseller-post-id" type="hidden">
		</form>
	</div>
</div>
<?php
get_footer(); ?>
?>