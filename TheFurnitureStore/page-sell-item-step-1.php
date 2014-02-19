<?php
/*
Template Name: Sell item step 1
*/
global $OPTION, $current_user;

get_header();
?>
		<div class="add-item-steps">
			<div class="step active">
				<a href="#" class="ico">1</a>
				<strong>Submit an item</strong>
			</div>
			<div class="step">
				<a href="#" class="ico">2</a>
				<strong>Manage items</strong>
			</div>
			<div class="step last">
				<a href="#" class="ico">3</a>
				<strong>Payment</strong>
			</div>
		</div>
		<div class="cf add-item-main">
			
		<div class="add-item-content">
			<h3>How It Works</h3>
			<img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/images/img-steps.png" alt="">
			<div class="center-link"><a href="#">Our Consignment Process</a></div>
			<h4 class="center">FIVE REASONS YOU SHOULD SELL YOUR ITEMS AT THE LUXURY CLOSET</h4>
			<div class="reasons-block mini">
				<div class="item"> <span class="icon"> <i> <img alt="" src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/images/ico-money-mini.png"> </i> </span>
					<h4>Get the maximum value for your products by selling on consignment</h4>
				</div>
				<div class="item">
					<span class="icon"> <i> <img alt="" src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/images/ico-delivery-mini.png"> </i> </span>
					<h4>Free pick-up of your item in the U.A.E and no up-front shipping costs for G.C.C customers</h4>
				</div>
				<div class="item">
					<span class="icon"> <i> <img alt="" src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/images/ico-secure-mini.png"> </i> </span>
					<h4>Secure, insured storage of your item at our temperature controlled facility</h4>
				</div>
				<div class="item">
					<span class="icon"> <i> <img alt="" src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/images/ico-photography-mini.png"> </i> </span>
					<h4>Professional photography and presentation of your item</h4>
				</div>
				<div class="item">
					<span class="icon"> <i> <img alt="" src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/images/ico-globe-mini.png"> </i> </span>
					<h4> Global reach
					<br>
					(we ship items to all corners of the globe every month!) </h4>
				</div>
			</div>
			<div class="center-link"><a href="#">What You Can Sell</a></div>
			<div class="contact-data item">
				<div class="contact-row">
					<p>
						Questions?
						<span class="i-phone">+971 800 589</span>
						<a class="i-email" href="mailto:sell@theluxurycloset.com">sell@theluxurycloset.com</a>
					</p>
				</div>
				<div class="contact-row">
					<h4>ARE YOU A PROFESSIONAL SELLER?</h4>
					<a class="btn-yellow" href="http://luxcloset.staging.wpengine.com/professional-seller">CLICK HERE</a>
				</div>
			</div>
		</div>

	<form id="indivseller-add-item" method="POST" class="form-add" enctype="multipart/form-data" onsubmit="return indivseller_presubmit_form();">
		<input name="SellersAction" value="indivseller_add_item" type="hidden">
		<h3>Submit an Item</h3>
		<div class="row">
			<div id="item-user-phone">
				<label class="left"> Telephone no.*</label>
				<input name="user_phone" type="text" class="right width-222">
			</div>
		</div>
		<div class="row">
			<div class="column width-170" id="item-category">
				<label>Category *</label>
				<div class="custom-select">
					<select name="item_category">
						<option selected="selected" value="">-- Select Category --</option>
						<option value="284">Handbags</option>
						<option value="285">Shoes</option>
						<option value="286">Watches</option>
						<option value="287">Jewelry</option>
						<option value="296">Clothing</option>
						<option value="297">Accessories</option>
						<option value="298">Sunglasses</option>
					</select>
				</div>
			</div>
			<div class="column width-170" id="item-brand">
				<label>Brand *</label>
				<div class="custom-select">
					<select name="item_brand">
						<option selected="selected" value="">-- Select Brand --</option>
						<option value="180">Alexander McQueen</option>
						<option value="221">Alexander Wang</option>
						<option value="131">Asprey</option>
						<option value="139">Balenciaga</option>
						<option value="167">Bally</option>
						<option value="190">BMOF</option>
						<option value="86">Bottega Veneta</option>
						<option value="70">Burberry</option>
						<option value="75">Bvlgari</option>
						<option value="202">Carolina Herrera</option>
						<option value="77">Caroline Herrera</option>
						<option value="68">Cartier</option>
						<option value="57">Celine</option>
						<option value="26">Chanel</option>
						<option value="69">Chloe</option>
						<option value="179">D&amp;G</option>
						<option value="67">Dior</option>
						<option value="178">DKNY</option>
						<option value="218">Dolce and Gabbana</option>
						<option value="176">Emporio Armani</option>
						<option value="65">Fendi</option>
						<option value="181">Furla</option>
						<option value="78">Georgio Armani</option>
						<option value="76">Gianfranco Ferre</option>
						<option value="162">Givenchy</option>
						<option value="99">Goyard</option>
						<option value="22">Gucci</option>
						<option value="48">Hermes</option>
						<option value="184">Jane August</option>
						<option value="64">Jimmy Choo</option>
						<option value="158">Just Cavalli</option>
						<option value="224">Kenzo</option>
						<option value="187">Lana Marks</option>
						<option value="175">Lancel</option>
						<option value="154">Loewe</option>
						<option value="16">Louis Vuitton</option>
						<option value="80">Marc Jacobs</option>
						<option value="213">Missoni</option>
						<option value="83">Miu Miu</option>
						<option value="210">Moschino</option>
						<option value="171">Mulberry</option>
						<option value="135">Pauric Sweeny</option>
						<option value="82">Prada</option>
						<option value="61">Ralph Lauren</option>
						<option value="159">Roberto Cavalli</option>
						<option value="125">Salvatore Ferragamo</option>
						<option value="174">Sonia Rykiel</option>
						<option value="183">Stella McCartney</option>
						<option value="160">Tod's</option>
						<option value="219">Tory Burch</option>
						<option value="165">UGG</option>
						<option value="140">Valentino</option>
						<option value="256">Van Cleef &amp; Arpels</option>
						<option value="74">Versace</option>
						<option value="130">Versace for H&amp;M</option>
						<option value="128">Yves Saint Laurent</option>
						<option value="203">Zagliani</option>
						<option value="other" style="margin-top:7px;">Other</option>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<label>Item Name/Description *</label>
			<p class="small">Louis Vuitton Damier Ebene Speedy 30 <i>or</i> <br>Prada Small Purple Leather Bag</p>
			<div id="item-name">
				<input name="item_name" type="text">
			</div>
		</div>
		<!--<div class="row" style="border-top:1px solid #C1C1C1;border-bottom:1px solid #C1C1C1;padding:10px 0px;margin-bottom:10px;">
			<div class="column width-260" id="item-user-email">
				<label>Your Email</label>
				<input name="user_email" value="Hivista" type="text">
			</div>
			<div class="column width-260" id="item-user-pass">
				<label>Password</label>
				<input name="user_pass" value="" type="password">
			</div>
		</div>-->
		<div class="row">
			<div id="item-your-price">
				<label>Your Asking Price, USD</label>
				<input name="item_your_price" type="text">
			</div>
		</div>
		<div class="row border-bottom">
			<div id="item-condition">
				<label>Condition: *</label>
				<div class="row-check">
					<div class="item-conditions">
						<label class="check-row">
							<input name="item_selection" value="31" type="radio">
							<span class="label">New (Unused item)</span>
						</label>
						<label class="check-row">
							<input name="item_selection" value="32" type="radio">
							<span class="label">Like New (Hard to notice, very slight signs of wear)</span>
						</label>
						<label class="check-row">
							<input name="item_selection" value="33" type="radio">
							<span class="label">Gently Used (Noticeable, slight signs of wear)</span>
						</label>
						<label class="check-row">
							<input name="item_selection" value="34" type="radio">
							<span class="label">Well Used (Obvious signs of wear)</span>
						</label>
					</div>
				</div>
			</div>
		</div>
		<div class="row border-bottom">
			<label>Includes:</label>
			<div class="row-check item-includes">
				<label class="check-row">
					<input name="item_includes[]" value="box" type="checkbox">
					<span class="label">Original Box</span>
				</label>
				<label class="check-row">
					<input name="item_includes[]" value="invoice" type="checkbox">
					<span class="label">Original Invoice</span>
				</label>
				<label class="check-row">
					<input name="item_includes[]" value="card" type="checkbox">
					<span class="label">Original Brand Authenticity Card</span>
				</label>
				<label class="check-row">
					<input name="item_includes[]" value="dustbag" type="checkbox">
					<span class="label">Original Dustbag</span>
				</label>
				<label class="check-row">
					<input name="item_includes[]" value="packaging" type="checkbox">
					<span class="label">LuxCloset Packaging</span>
				</label>
			</div>
		</div>
		<div class="images-block">
			<p>Attach Pictures (Max 5 pictures)</p>
			<div id="item-pictures">
				<div class="image-file">
					<input name="item_picture[]" multiple="" type="file">
				</div>
				<div class="image-file">
					<input name="item_picture[]" multiple="" type="file">
				</div>
				<div class="image-file">
					<input name="item_picture[]" multiple="" type="file">
				</div>
			</div>
		</div>
		<a href="#" class="btn-aaitem">Add another item</a>
		<div class="row row-1">
			<label class="check-row">
				<input type="checkbox">
				<span class="label">I agree to the Luxury Closet’s <a href="http://luxcloset.staging.wpengine.com/terms-and-conditions">terms and conditions</a>.</span>
			</label>
		</div>
		<input value="Submit" class="btn-submit" type="submit">
		<input value="(direct)" name="utm_source" id="utm_source" type="hidden">
		<input value="(none)" name="utm_medium" id="utm_medium" type="hidden">
		<input value="(direct)" name="utm_campaign" id="utm_campaign" type="hidden">
		<input value="" name="utm_content" id="utm_content" type="hidden">
		<input value="" name="utm_term" id="utm_term" type="hidden">
	</form>
</div>
<!--<div class="lightbox-sell">
	<div class="block">
		<h3>How it works</h3>
		<ul>
			<li>Request a quote for your item(s) by filling out the form on our website.</li>
			<li>After reviewing your item, our team will get back to you with a quotation.</li>
			<li>Choose the amount you want to get paid from within the range sent to you.</li>
			<li>Send us your item. We offer free pick up in the G.C.C!</li>
			<li>We authenticate & photograph it, then put it on the website for sale!</li>
			<li>After your item sells, we pay you with your preferred payment method.</li>
		</ul>
		<p>It’s really that simple!</p>
	</div>
	<h3>What you need to know</h3>
	<ul>
		<li>The quotation we send you is based on the resale value of your item, its condition and style.</li>
		<li>You get paid after your item sells. This is to ensure that you receive the highest value for your item!</li>
		<li>We store your items in our safe & insured, temperature controlled facility.</li>
	</ul>
	<br><br>
	<a href="#" class="close">close</a>
</div>
<div class="lightbox-sell inner">
	<h3>Brands</h3>
	<div class="columns">
		<ul class="column">
			<li><a href="#">AUDEMARS PIGUET</a></li>
			<li><a href="#">BALENCIAGA</a></li>
			<li><a href="#">BAUME & MERCIER</a></li>
			<li><a href="#">BELL & ROSS</a></li>
			<li><a href="#">BLANCPAIN</a></li>
			<li><a href="#">BREITLING</a></li>
			<li><a href="#">BVLGARI</a></li>
			<li><a href="#">CARTIER</a></li>
			<li><a href="#">CELINE</a></li>
			<li><a href="#">CHANEL</a></li>
			<li><a href="#">CHLOÉ</a></li>
			<li><a href="#">CHOPARD</a></li>
		</ul>
		<ul class="column">
			<li><a href="#">CHRISTIAN DIOR</a></li>
			<li><a href="#">CHRISTIAN LOUBOUTIN</a></li>
			<li><a href="#">FENDI</a></li>
			<li><a href="#">FREDERIQUE CONSTANT</a></li>
			<li><a href="#">GUCCI</a></li>
			<li><a href="#">HERMES</a></li>
			<li><a href="#">IWC SCHAFFHAUSEN</a></li>
			<li><a href="#">JAEGER LECOULTRE</a></li>
			<li><a href="#">JIMMY CHOO</a></li>
			<li><a href="#">LOUIS VUITTON</a></li>
			<li><a href="#">MANOLO BLAHNIK</a></li>
			<li><a href="#">MARC JACOBS</a></li>
		</ul>
		<ul class="column">
			<li><a href="#">MIU MIU</a></li>
			<li><a href="#">MONT BLANC</a></li>
			<li><a href="#">OMEGA</a></li>
			<li><a href="#">PATEK PHILIPPE</a></li>
			<li><a href="#">PRADA</a></li>
			<li><a href="#">ROLEX</a></li>
			<li><a href="#">TAG HEUER</a></li>
			<li><a href="#">Tiffany & Co</a></li>
			<li><a href="#">TIFFANY & CO.</a></li>
			<li><a href="#">ULYSSE NARDIN</a></li>
			<li><a href="#">VALENTINO</a></li>
			<li><a href="#">YVES SAINT LAURENT</a></li>
		</ul>
	</div>
	<div class="categories-block">
		<h3>Categories</h3>
		<div class="categories-list">
			<a href="http://luxcloset.staging.wpengine.com/category/women/womens-handbags" class="item">
				<span class="icon">
					<i><img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/images/ico-handbags.png" alt="" /></i>
				</span>
				<strong>HANDBAGS</strong>
			</a>
			<a href="http://luxcloset.staging.wpengine.com/category/women/womens-shoes" class="item">
				<span class="icon">
					<i><img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/images/ico-shoes.png" alt="" /></i>
				</span>
				<strong>SHOES</strong>
			</a>
			<a href="http://luxcloset.staging.wpengine.com/category/women/womens-watches" class="item">
				<span class="icon">
					<i><img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/images/ico-watches.png" alt="" /></i>
				</span>
				<strong>WATCHES</strong>
			</a>
			<a href="http://luxcloset.staging.wpengine.com/category/women/womens-clothes" class="item">
				<span class="icon">
					<i><img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/images/ico-clothes.png" alt="" /></i>
				</span>
				<strong>CLOTHES</strong>
			</a>
			<a href="http://luxcloset.staging.wpengine.com/category/women/womens-jewelry" class="item">
				<span class="icon">
					<i><img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/images/ico-jewelry.png" alt="" /></i>
				</span>
				<strong>JEWELRY</strong>
			</a>
			<a href="http://luxcloset.staging.wpengine.com/category/women/womens-accessories" class="item">
				<span class="icon">
					<i><img src="http://luxcloset.staging.wpengine.com/wp-content/themes/TheFurnitureStore/images/ico-accessories.png" alt="" /></i>
				</span>
				<strong>ACCESSORIES</strong>
			</a>
		</div>
	</div>
</div>
<div class="window-mask"></div>-->
<?php
get_footer(); ?>
?>