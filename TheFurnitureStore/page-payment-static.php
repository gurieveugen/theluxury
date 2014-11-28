<?php
/*
Template Name: Page Payment Static
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US">

<head profile="http://gmpg.org/xfn/11">
	<title>step 1</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" media="all" href="http://luxclosettest.wpengine.com/wp-content/themes/TheFurnitureStoreLight/style.css" />
	<link href='http://fonts.googleapis.com/css?family=Cabin:400,600,700,400italic,700italic' rel='stylesheet' type='text/css'/>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'/>
</head>
<body>
	<div id="wrapper" class="payment-wrap">
		<div id="header">
			<div class="container">
				<h1 id="branding"><a href="http://luxclosettest.wpengine.com/" title="The Luxury Closet : Buy and Sell Authentic Pre-Owned (Used) Luxury: Louis Vuitton, Chanel, Gucci, Prada, and Hermes." rel="home">The Luxury Closet : Buy and Sell Authentic Pre-Owned (Used) Luxury: Louis Vuitton, Chanel, Gucci, Prada, and Hermes.Shop for 100% authentic pre-owned luxury bag and handbags, from top brands including Louis Vuitton, Chanel, Gucci, Dior, and Hermes. Based in Dubai, UAE, Middle East</a></h1>
				<ul class="payment-info-row">
					<li><a href="#">100% Authenticity Guarantee</a></li>
					<li><a href="#" class="link-returns">Days Returns</a></li>
					<li>NEED HELP?</li>
					<li class="ico-phone">Call 800 LUX (+971 800 589)</li>
					<li><a href="#" class="link-email">Email Us</a></li>
				</ul>
			</div>
		</div>
		<!-- header-->
		<div id="pg_wrap">
			<div class="container payment-container cf">
				<div class="payment-content">
					<div class="payment_steps">
						<div class="payment-step sign-in open">
							<div class="payment-step-title cf">
								<h3>Sign In</h3>
								<a href="#">Edit</a>
							</div>
							<div class="payment-step-content">
								<div class="payment-sign-in cf">
									<div class="column">
										<div class="description">
											<h4>New to the Luxury Closet?</h4>
											<p>Check out using your e-mail address:</p>
										</div>
										<form action="#" class="form-default">
											<label>E-mail</label>
											<input type="text" value="" />
											<ul class="check-row">
												<li>
													<input type="radio" name="gender" checked="checked" id="male"/>
													<label for="male">Male</label>
												</li>
												<li>
													<input type="radio" name="gender" id="female"/>
													<label for="female">Female</label>
												</li>
											</ul>
											<div class="btn-holder right v1">
												<input type="submit" disabled="disabled" value="Continue" class="btn-orange"/>
											</div>
										</form>
									</div>
									<div class="column">
										<div class="description">
											<h4>Login to your Account</h4>
											<p>If you have an account, please log in below:</p>
										</div>
										<form action="#" class="form-default">
											<label>E-mail</label>
											<input type="text" value="" placeholder="Enter your e-mail" class="mb-18" />
											<label>Password</label>
											<input type="password" value="" placeholder="Enter your password" class="mb-18"/>
											<div class="btn-holder right">
												<input type="submit" value="Continue" class="btn-orange"/><br />
												<a href="#">Forgot your password?</a>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<div class="payment-step open">
							<div class="payment-step-title cf">
								<h3>Delivery</h3>
							</div>
							<div class="payment-step-content">
								<div class="payment-delivery">
									<form action="#" class="form-default cf">
										<div class="check-row-lines">
											<div class="check-row-line checked cf">
												<input type="radio" name="delivery" id="pick-up" />
												<label for="pick-up">
													<strong>Pick up</strong>
													<span>From our office in Dubai</span>
												</label>
											</div>
											<div class="check-row-line cf">
												<input type="radio" name="delivery" id="delivery" />
												<label for="delivery">
													<strong>Delivery</strong>
													<span>3-7 working days</span>
												</label>
											</div>
										</div>
										<div class="check-row-content open">
											<div class="check-row-box cf">
												You have three days to pick up the item <br />once you have placed the order.Our <br />address is:
												<h3>The Luxury Closet</h3>
												<div class="map">
													<img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/img-map.jpg" height="136" width="189" alt="" />
												</div>
												<small>Updated Nov 7, 2012</small>
												<address>
													Tradelux LLC <br />
													803 <br />
													Sidra Tower <br />
													<small>(Frasier Suites Hotel Building)</small> <br />
													Sheikh Zayed Road <br />
													Al Sufou 1, Dubai, UAE <br />
													PO Box 500027
												</address>
											</div>
											<div class="cf" style="margin: 35px 0 0;">
												<input type="submit" value="Next" class="btn-orange right">
											</div>
										</div>
										<div class="check-row-content">
											<div class="check-row-box cf">
												<p>Estimated delivery time is between 4-7 business days. We send all our items through FedEx &amp; Aramex.  Items are dispatched within</p>
												<p>Shipping</p>
												<p>Estimated delivery cost outside of the UAE is</p>
												<p>Estimated delivery cost within UAE is</p>
											</div>
										</div>
										<div class="ovh" style="width: 100%;">
											<p>Fill in your delivery address below:</p>
											<div class="check-row-box v1 open cf">
												<p>Estimated delivery time is between 4-7 business days. We send all out items through FedEx & Aramex. Items are dispatched within</p>
												<p>Shipping</p>
												<p>Estimated delivery cost outside of the UAE is</p>
											</div>
										</div>
										<div class="payment-address">
											<div class="columns cf">
												<div class="column">
													<h3>Billing Address:</h3>
													<label>First Name:</label>
													<input type="text" value=""/>
													<label>Last Name:</label>
													<input type="text" value=""/>
													<label>Country:</label>
													<select>
														<option value="1">Option 1</option>
														<option value="2">Option 2</option>
														<option value="3">Option 3</option>
													</select>
													<label>Address:</label>
													<input type="text" value=""/>
													<label>State/Province:</label>
													<input type="text" value=""/>
													<label>City:</label>
													<input type="text" value=""/>
													<label>Postcode:</label>
													<input type="text" value=""/>
													<label>Email:</label>
													<input type="text" value=""/>
													<label>Telephone:</label>
													<input type="text" value=""/>
													<div class="check-row">
														<input type="checkbox" id="another-address"/>
														<label for="another-address">I have a different delivery address.</label>
													</div>
													<div class="check-row">
														<input type="checkbox" id="accept"/>
														<label for="accept">*Accept terms &amp; conditions</label>
													</div>
												</div>
												<div class="column">
													<h3>Delivery Address:</h3>
													<p>Fill In Only If Different From Billing Address</p>
													<label>First Name:</label>
													<input type="text" value=""/>
													<label>Last Name:</label>
													<input type="text" value=""/>
													<label>Country:</label>
													<div class="custom-select">
														<select>
															<option value="1">Option 1</option>
															<option value="2">Option 2</option>
															<option value="3">Option 3</option>
														</select>
													</div>
													<label>Address:</label>
													<input type="text" value=""/>
													<label>State/Province:</label>
													<input type="text" value=""/>
													<label>City:</label>
													<input type="text" value=""/>
													<label>Postcode:</label>
													<input type="text" value=""/>
												</div>
											</div>
											<div class="btn-holder cf">
												<button class="btn-orange right-arrow right">proceed to checkout</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="payment-step open">
							<div class="payment-step-title cf">
								<h3>Payment</h3>
							</div>
							<div class="payment-step-content">
								<form action="#" class="form-default cf">
									<div class="cf">
										<div class="check-row-lines v1">
											<div class="check-row-line checked cf">
												<input type="radio" name="delivery" id="pick-up" />
												<label for="pick-up">
													<strong>Credit Card (Selected)</strong>
												</label>
											</div>
											<div class="check-row-line cf">
												<input type="radio" name="delivery" id="delivery" />
												<label for="delivery">
													<strong>Paypal</strong>
												</label>
											</div>
											<div class="check-row-line cf">
												<input type="radio" name="delivery" id="delivery" />
												<label for="delivery">
													<strong>Pay On Location</strong>
												</label>
											</div>
											<div class="check-row-line cf">
												<input type="radio" name="delivery" id="delivery" />
												<label for="delivery">
													<strong>Bank Transfer</strong>
												</label>
											</div>
										</div>
										<div class="check-row-content v1 open">
											<div class="check-row-box cf">
												<p>Lorem ipsum dolor Visa or Master Card</p>
											</div>
										</div>
										<div class="check-row-content v1">
											<div class="check-row-box cf">
												<p>Lorem ipsum dolor Visa or Master Card</p>
											</div>
										</div>
										<div class="check-row-content v1">
											<div class="check-row-box cf">
												<p>Lorem ipsum dolor Visa or Master Card</p>
											</div>
										</div>
										<div class="check-row-content v1">
											<div class="check-row-box cf">
												<p>Lorem ipsum dolor Visa or Master Card</p>
											</div>
										</div>
									</div>
									<div class="buttons-bottom cf">
										<div class="btn-text-field">
											<input type="text" value="" placeholder="Enter voucher code">
											<button class="btn-orange">Use</button>
										</div>
										<div class="pay-status false"></div>
										<input type="submit" value="Next" class="btn-orange right">
									</div>
								</form>
							</div>
						</div>
						<div class="payment-step open">
							<div class="payment-step-title cf">
								<h3>Confirmation</h3>
							</div>
							<div class="payment-step-content">
								<div class="content-text">
									<h3>Thank you for your Order</h3>
									<p>Your items have been reserved for 3 days and an e-mail confirmation has been sent.</p>
									<p>Please follow the instructions in the e-mail to receive your purchase.</p>
									<p>If you have any questions:</p>
									<ul class="payment-contact-info">
										<li>
											<a href="#" class="email">info@theluxurycloset.com</a>
										</li>
										<li>
											<span class="phone">+971 800 589</a>
										</li>
									</ul>
								</div>
								<input type="submit" value="Continue Shopping" class="btn-orange right">
							</div>
						</div>
					</div>
				</div>
				<div class="payment-aside">
					<div class="payment-order cf">
						<div class="head">
							<h3>Your Order</h3>
						</div>
						<div class="order-content cf">
							<div class="p-item cf">
								<a href="#" class="image"><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/img-temp-1.png" alt="" /></a>
								<div class="holder">
									<h4><a href="#">Louis Vuitton Monogram  Canvas Koala Wallet</a></h4>
									<a href="#" class="text-bottom">Move to Wishlist</a>
								</div>
							</div>
							<table class="table-order">
								<tr class="bdb">
									<td colspan="2">$382</td>
								</tr>
								<tr>
									<th>Subtotal:</th>
									<td>$382</td>
								</tr>
								<tr>
									<th>Shipping:</th>
									<td>$14</td>
								</tr>
								<tr>
									<th>Total:</th>
									<td>$396</td>
								</tr>
								<tr>
									<th><strong>Installment Payment:</strong></th>
									<td>$96</td>
								</tr>
								<tr>
									<th><strong>Order Total:</strong></th>
									<td>$96</td>
								</tr>
							</table>
							<div class="text-holder text-right">
								<a href="#" class="link-orange"><strong>Learn more about buying in Installments</strong></a>
							</div>
						</div>
					</div>
					<div class="payment-f-logos">
						<p>As seen in:</p>
						<ul class="featured-logos mini">
							<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/logo-ahlan-mini.png" alt="ahlan"/></li>
							<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/logo-cosmopolitan-mini.png" alt="cosmopolitan"/></li>
							<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/logo-hello-mini.png" alt="hello"/></li>
							<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/logo-whatson-mini.png" alt="whatson"/></li>
							<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/logo-elle-mini.png" alt="elle"/></li>
							<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/logo-timeout-mini.png" alt="timeout"/></li>
							<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/logo-grazia-mini.png" alt="grazia"/></li>
						</ul>
					</div>
					<div class="payment-info-links cf">
						<a href="#" class="link-returns">Days Returns</a>
						<a href="#" class="link-refunds right">Full Refunds</a>
					</div>
					<div class="payment-aside-text">
						Return any item to us within 3 days of receipt in its original packaging, to receive a full refund of your payment
					</div>
					<ul class="payment-logos">
						<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/logo-secure-p.png" height="33" width="65" alt="ahlan"/></li>
						<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/logo-visa-p.png" alt="cosmopolitan"/></li>
						<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/logo-mastercard-p.png" alt="hello"/></li>
					</ul>
				</div>
			</div>
		</div><!-- #pg_wrap -->
			
		<div class="footer-wrap cf">
			<div class="holder">
				<div id="footer" class="bigft clearfix noprint">
					<div class="footer_box">
						<div class="footer_inner_box clearfix">
							<div class="fb-like" data-href="https://www.facebook.com/theluxurycloset" data-width="250" data-show-faces="true" data-send="true"></div>
						</div>
						<div class="textwidget">
							<ul class="footer-socials cf">
								<li><a target="_blank" class="link-facebook" href="https://www.facebook.com/theluxurycloset">facebook</a>
								</li>
								<li><a target="_blank" class="link-twitter" href="https://twitter.com/theluxurycloset">twitter</a>
								</li>
								<li><a target="_blank" class="link-google" href="https://plus.google.com/+Theluxuryclosetinc/">google</a>
								</li>
								<li><a target="_blank" class="link-pinterest" href="http://www.pinterest.com/theluxurycloset/">pinterest</a>
								</li>
								<li><a target="_blank" class="link-instagram" href="http://instagram.com/the_luxury_closet">instagram</a>
								</li>
							</ul>
						</div>
					</div>
					<div class="footer_box middle">
						<div class="clearfix">
							<div id="nws-pages-list-3" class="widget widget_pages_list ">
								<ul class="pagesList">
									<li class="page_item page-item-16491"><a href="http://luxclosettest.wpengine.com/about-us/who-are-we">Who Are We?</a>
									</li>
									<li class="page_item page-item-895"><a href="http://luxclosettest.wpengine.com/faqs">Frequently Asked Questions</a>
									</li>
									<li class="page_item page-item-696"><a href="http://luxclosettest.wpengine.com/about-us/contact-us">Contact Us</a>
									</li>
									<li class="page_item page-item-4970"><a href="http://luxclosettest.wpengine.com/careers">Careers</a>
									</li>
									<li class="page_item page-item-913"><a href="http://luxclosettest.wpengine.com/delivery-and-returns">Delivery &#038; Returns</a>
									</li>
									<li class="page_item page-item-899"><a href="http://luxclosettest.wpengine.com/how-does-it-work">How does it work?</a>
									</li>
									<li class="page_item page-item-901"><a href="http://luxclosettest.wpengine.com/privacy-policy">Privacy Policy</a>
									</li>
									<li class="page_item page-item-904"><a href="http://luxclosettest.wpengine.com/terms-and-conditions">Terms &#038; Conditions</a>
									</li>
								</ul>
							</div>
						</div>
					</div>

					<div class="footer_box secure">
						<div class="footer_inner_box clearfix">
							<div id="text-2" class="widget widget_text ">
								<div class="textwidget">
									<h4><a href="http://www.theluxurycloset.com/worldwide-delivery">World Wide Delivery</a></h4>
									<ul class="list-payment-methods">
										<li>fedex</li>
										<li>aramex</li>
										<li>paypal</li>
										<li>mastercard</li>
										<li>visa</li>
										<li>Cash On Delivery</li>
										<li>Bank Transfer</li>
										<li>Pay On Location</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- end footer -->
			</div>
			<div class="footer-bottom">
				<div class="textwidget">&copy; 2014 Copyrights TheLuxuryCloset.com - All the brands presented belong to their owners.</div>
			</div>
		</div>
	</div>
	<!-- #wrapper -->



<div id="cboxOverlay" style="opacity: 0.9; cursor: pointer; visibility: visible; display: none;"></div>
<div id="colorbox" class="" role="dialog" tabindex="-1" style="display: none; visibility: visible; top: 134px; left: 528px; position: absolute; width: 572px; height: 371px;">
	<div id="cboxWrapper" style="height: 371px; width: 572px;">
		<div>
			<div id="cboxTopLeft" style="float: left;"></div>
			<div id="cboxTopCenter" style="float: left; width: 530px;"></div>
			<div id="cboxTopRight" style="float: left;"></div>
		</div>
		<div style="clear: left;">
			<div id="cboxMiddleLeft" style="float: left; height: 279px;"></div>
			<div id="cboxContent" style="float: left; width: 530px; height: 279px;">
				<div id="cboxLoadedContent" style="width: 530px; overflow: auto; height: 279px;">
					<div id="lightbox-3-day" class="lightbox-default">
						<div class="lightbox-holder">
							<h3 class="title">3 Day Returns</h3>
							<p>You can return your item by contacting us within 3 days of receipt. We will cover the cost of return shipping and give you a full refund minus the shipping fee and/or any custom duties paid for importation.</p>
							<p>We process the refund after receiving the item, meaning you will get your money back 5-7 days after we receive it.</p>
							<p>All items must be returned in their original packaging and in the same condition as received</p>
							<p>*Please note that there are no returns on Hermes Birkin and Kelly handbags.</p>
						</div>
					</div>
				</div>
				<div id="cboxTitle" style="float: left; display: block;"></div>
				<div id="cboxCurrent" style="float: left; display: none;"></div>
				<button type="button" id="cboxPrevious" style="display: none;"></button>
				<button type="button" id="cboxNext" style="display: none;"></button>
				<button id="cboxSlideshow" style="display: none;"></button>
				<div id="cboxLoadingOverlay" style="float: left; display: none;"></div>
				<div id="cboxLoadingGraphic" style="float: left; display: none;"></div>
				<button type="button" id="cboxClose">close</button>
			</div>
			<div id="cboxMiddleRight" style="float: left; height: 279px;"></div>
		</div>
		<div style="clear: left;">
			<div id="cboxBottomLeft" style="float: left;"></div>
			<div id="cboxBottomCenter" style="float: left; width: 530px;"></div>
			<div id="cboxBottomRight" style="float: left;"></div>
		</div>
	</div>
</div>

<div id="colorbox" class="" role="dialog" tabindex="-1" style="display: none; visibility: visible; top: 134px; left: 528px; position: absolute; width: 572px; height: 371px;">
	<div id="cboxWrapper" style="height: 371px; width: 572px;">
		<div>
			<div id="cboxTopLeft" style="float: left;"></div>
			<div id="cboxTopCenter" style="float: left; width: 530px;"></div>
			<div id="cboxTopRight" style="float: left;"></div>
		</div>
		<div style="clear: left;">
			<div id="cboxMiddleLeft" style="float: left; height: 279px;"></div>
			<div id="cboxContent" style="float: left; width: 530px; height: 279px;">
				<div id="cboxLoadedContent" style="width: 530px; overflow: auto; height: 279px;">
					<div id="lightbox-guarantee" class="lightbox-default">
						<div class="lightbox-holder">
							<img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/img-guarantee.png" height="243" width="144" alt="" class="alignright" />
							<div class="ovh">
								<h3 class="title">100% Authenticity Guarantee</h3>
								<p>Everything on the Luxury Closet is authentic. Our in-house authentication experts inspect every item before it is made available online.</p>
								<p>In the case of the sale of an inauthentic item, we offer a lifetime guarantee of a 100% refund, including the cost of return shipping.</p>
							</div>
						</div>
					</div>
				</div>
				<div id="cboxTitle" style="float: left; display: block;"></div>
				<div id="cboxCurrent" style="float: left; display: none;"></div>
				<button type="button" id="cboxPrevious" style="display: none;"></button>
				<button type="button" id="cboxNext" style="display: none;"></button>
				<button id="cboxSlideshow" style="display: none;"></button>
				<div id="cboxLoadingOverlay" style="float: left; display: none;"></div>
				<div id="cboxLoadingGraphic" style="float: left; display: none;"></div>
				<button type="button" id="cboxClose">close</button>
			</div>
			<div id="cboxMiddleRight" style="float: left; height: 279px;"></div>
		</div>
		<div style="clear: left;">
			<div id="cboxBottomLeft" style="float: left;"></div>
			<div id="cboxBottomCenter" style="float: left; width: 530px;"></div>
			<div id="cboxBottomRight" style="float: left;"></div>
		</div>
	</div>
</div>

	<div id="colorbox" class="" role="dialog" tabindex="-1">
		<div id="cboxWrapper" style="height: 621px; width: 722px;">
			<div>
				<div id="cboxTopLeft" style="float: left;"></div>
				<div id="cboxTopCenter" style="float: left; width: 680px;"></div>
				<div id="cboxTopRight" style="float: left;"></div>
			</div>
			<div style="clear: left;">
				<div id="cboxMiddleLeft" style="float: left; height: 579px;"></div>
				<div id="cboxContent" style="float: left; width: 680px; height: 579px;">
					<div id="cboxLoadedContent" style="width: 680px; overflow: auto; height: 579px;">
						<div id="lightbox-contact" class="lightbox-default lightbox-contact">
							<div class="lightbox-holder">
								<div class="short-text">
									<h3 class="title">Contact Us</h3>
									<p>Having a technical problem, or just need help finding the right item for you? Let us know, we’d love to hear from you!</p>
								</div>
								<div class="contact-info cf">
									<div class="column">
										<form action="#" class="contact-form-l">
											<label>Your Name (required)</label>
											<input type="text" value="">
											<label>Your Email (required)</label>
											<input type="email" value="">
											<label>Subject</label>
											<input type="text" value="">
											<label>Your Message</label>
											<textarea cols="30" rows="10"></textarea>
											<input type="submit" value="Send" class="btn-orange">
										</form>
									</div>
									<div class="column right">
										<address>
											<strong>Tradelux LLC803</strong><br />
											Sidra Tower (Fraser Suites Hotel Building) <br />
											Sheikh Zayed Road <br />
											Al Sufou 1, Dubai, UAE <br />
											PO Box 502626
										</address>
										<div class="contact-info-row">
											<img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/ico-phone-l.png" height="29" width="28" alt="" />
											+ 971 800 589, + 971 44214281
										</div>
										<div class="contact-info-row">
											<img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/ico-facebook-l.png" height="29" width="28" alt="" />
											<a href="#">Connect on Facebook</a>
										</div>
										<div class="contact-info-row">
											<img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/ico-twitter-l.png" height="29" width="28" alt="" />
											<a href="#">Tweet us</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="cboxTitle" style="float: left; display: block;"></div>
					<div id="cboxCurrent" style="float: left; display: none;"></div>
					<button type="button" id="cboxPrevious" style="display: none;"></button>
					<button type="button" id="cboxNext" style="display: none;"></button>
					<button id="cboxSlideshow" style="display: none;"></button>
					<div id="cboxLoadingOverlay" style="float: left; display: none;"></div>
					<div id="cboxLoadingGraphic" style="float: left; display: none;"></div>
					<button type="button" id="cboxClose">close</button>
				</div>
				<div id="cboxMiddleRight" style="float: left; height: 579px;"></div>
			</div>
			<div style="clear: left;">
				<div id="cboxBottomLeft" style="float: left;"></div>
				<div id="cboxBottomCenter" style="float: left; width: 680px;"></div>
				<div id="cboxBottomRight" style="float: left;"></div>
			</div>
		</div>
	</div>

	<div id="colorbox" class="" role="dialog" tabindex="-1" style="display: none; visibility: visible; top: 134px; left: 528px; position: absolute; width: 572px; height: 471px;">
	<div id="cboxWrapper" style="height: 471px; width: 572px;">
		<div>
			<div id="cboxTopLeft" style="float: left;"></div>
			<div id="cboxTopCenter" style="float: left; width: 530px;"></div>
			<div id="cboxTopRight" style="float: left;"></div>
		</div>
		<div style="clear: left;">
			<div id="cboxMiddleLeft" style="float: left; height: 379px;"></div>
			<div id="cboxContent" style="float: left; width: 530px; height: 379px;">
				<div id="cboxLoadedContent" style="width: 530px; overflow: auto; height: 379px;">
					<div id="lightbox-buy-in-installment" class="lightbox-default lightbox-buy-in-installment">
						<div class="lightbox-holder">
							<h3 class="title no-border">Buy in Installments</h3>
							<table class="table-products-l">
								<tr>
									<th class="cell-image">Item</th>
									<th>&nbsp;</th>
									<th class="cell-price">Item Price</th>
								</tr>
								<tr>
									<td class="cell-image">
										<a href="#"><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/temp-product.jpg" height="91" width="91" alt="" /></a>
									</td>
									<td class="cell-description">
										<h5><a href="#">Louis Vuitton Monogram</a></h5>
										<p>Speedy 35</p>
									</td>
									<td class="cell-price">
										$382
									</td>
								</tr>
							</table>
							<table class="table-total-l">
								<tr>
									<th><strong>Subtotal:</strong></th>
									<td class="cell-2 text-right"><strong>$382</strong></td>
									<td class="cell-3"></td>
								</tr>
								<tr>
									<th>excl. <a href="#">Shipping &amp; Returns</a></th>
									<td class="cell-2"></td>
									<td class="cell-3"></td>
								</tr>
								<tr>
									<th>Purchase on installments:</th>
									<td class="cell-2 form-default wsnw">
										<input type="radio" name="purchase-inst-l" id="purchase-inst-l-yes">
										<label for="purchase-inst-l-yes">Yes</label>
										<input type="radio" name="purchase-inst-l" id="purchase-inst-l-no">
										<label for="purchase-inst-l-no">No</label>
										<a href="#" class="btn-help">?</a>
									</td>
									<td class="cell-3"></td>
								</tr>
								<tr>
									<th>Accept Terms:</th>
									<td class="cell-2 form-default wsnw">
										<input type="radio" name="accept-l" id="accept-l-yes">
										<label for="accept-l-yes">Yes</label>
										<input type="radio" name="accept-l" id="accept-l-no">
										<label for="accept-l-no">No</label>
										<a href="#" class="link-read-terms">READ TERMS</a>
									</td>
									<td class="cell-3"></td>
								</tr>
								<tr>
									<th>Installment amount, USD:</th>
									<td class="cell-2 form-default"><input type="text" value="$96" class="width-170"></td>
									<td class="cell-3">
										<a href="#" class="btn-help">?</a>
									</td>
								</tr>
								<tr>
									<td colspan="3">
										<button href="#" class="btn-orange right">Continue</button>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<div id="cboxTitle" style="float: left; display: block;"></div>
				<div id="cboxCurrent" style="float: left; display: none;"></div>
				<button type="button" id="cboxPrevious" style="display: none;"></button>
				<button type="button" id="cboxNext" style="display: none;"></button>
				<button id="cboxSlideshow" style="display: none;"></button>
				<div id="cboxLoadingOverlay" style="float: left; display: none;"></div>
				<div id="cboxLoadingGraphic" style="float: left; display: none;"></div>
				<button type="button" id="cboxClose">close</button>
			</div>
			<div id="cboxMiddleRight" style="float: left; height: 379px;"></div>
		</div>
		<div style="clear: left;">
			<div id="cboxBottomLeft" style="float: left;"></div>
			<div id="cboxBottomCenter" style="float: left; width: 530px;"></div>
			<div id="cboxBottomRight" style="float: left;"></div>
		</div>
	</div>
</div>

</body>

</html>
