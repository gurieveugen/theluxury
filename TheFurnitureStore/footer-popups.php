<div style="display:none;">
	<div class="popup-notification" id="popup-notification">
		<h2 class="message">Your notification has been created. </h2>
		<p>You will receive an e-mail when an item is added to: 
			<span class="notification-tags"></span>
		</p>
		<?php
		$popup_brands = unserialize($OPTION['wps_alerts_popup_brands']);
		if ($popup_brands) {
			$tax_brands = get_terms('brand', 'include='.implode(',', $popup_brands));
			if ($tax_brands) {
		?>
		<div class="f-holder">
			<?php foreach($tax_brands as $tax_brand) { ?>
			<a href="#follow" class="f-tag" rel="<?php echo $tax_brand->term_id; ?>">Follow <?php echo $tax_brand->name; ?></a>
			<?php } ?>
		</div>
		<div style="height:20px;">
			<div class="pn-follow-success" style="display:none;"><strong>You are now following *<span>Gucci</span>*</strong></div>
		</div>
		<?php }} ?>
		<div class="right">
			<a href="<?php echo get_permalink($OPTION['wps_account_my_alerts_page']); ?>">Create a custom notification</a>
		</div>
		<a href="#close" class="close">close</a>
		<form class="pn-follow-form">
			<input type="hidden" name="follow_brands_ip" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" id="pnff-ip">
			<input type="hidden" name="follow_brands_email" value="<?php echo $current_user->data->user_email; ?>" id="pnff-email">
		</form>
	</div>
	<div class="popup-size-chart" id="popup-size-chart" style="padding:10px; height:600px;">
		<?php include('size-chart.php'); ?>
	</div>
	<div class="lightbox-payout" id="reduce-payout-popup">
		<div class="title">
			<h4>Reducing your payout</h4>
		</div>
		<ul>
			<li>You can reduce your price by a minimum of 5% and a maximum of 50%.</li>
			<li>Reducing your payout will also reduce the selling price of your item.</li>
			<li>Reducing the price may lead to your item selling faster.</li>
		</ul>
		<p>Made a mistake? Call us on 800 LUX (<?php echo $OPTION['wps_shop_questions_phone']; ?>) or email us on <a href="mailto:<?php echo $OPTION['wps_shop_questions_email']; ?>"><?php echo $OPTION['wps_shop_questions_email']; ?></a></p>
	</div>
</div>

<?php if (!is_user_logged_in()) { // LOGIN / REGISTER POPUP ?>
	<div class="popup-login def" id="basic-login-popup" style="display:none;">
		<div class="buttons-holder">
			<ul class="buttons">
				<li class="first"><a href="#tab-register" class="register-tab-link active">Register</a></li>
				<li class="last"><a href="#tab-login" class="login-tab-link">Login</a></li>
				<li style="visibility:hidden; display: none;"><a href="#tab-forgot-pass" class="forgot-tab-link">Forgot</a></li>
			</ul>
		</div>
		<div class="tab-content-box">
			<div id="tab-register" class="active tab-content">
				<h4 class="lr-title def">Register For This Site</h4>
				<h4 class="lr-title layaway">Register now to buy in installments</h4>
				<h4 class="lr-title sale">Get exclusive access to all sale items!</h4>
				<h4 class="lr-title wishlist">Remember your favourite items and <br>share your wishlist with your friends!</h4>
				<h4 class="lr-title wn">Get access to our latest items <br>before everybody else!</h4>
				<h4 class="lr-title notify">Register now to receive your notifications</h4>
				<form class="popup-login-register">
					<input type="hidden" name="callpage" class="call-page">
					<div class="form-box register-screen-1" style="height:171px;">
						<input type="email" name="uemail" class="user-email" placeholder="Email">
						<div style="position:relative;">
							<input type="submit" value="Next" class="register-btn" rel="basic">
							<div class="action-loading"><img src="<?php echo TEMPLURL; ?>/images/loading-ajax.gif"></div>
						</div>
					</div>
					<div class="form-box register-screen-2" style="display:none;">
						<input type="password" name="upwd" class="user-pwd" placeholder="Password">
						<div class="row-check user-gender">
							<label>Gender:</label> 
							<label><input type="radio" name="ugender" value="Male" checked="checked"> Male</label> 
							<label><input type="radio" name="ugender" value="Female"> Female</label>
						</div>
						<div style="position:relative;">
							<input type="submit" value="Submit" class="join-btn" rel="basic">
							<div class="action-loading"><img src="<?php echo TEMPLURL; ?>/images/loading-ajax.gif"></div>
						</div>
						<div style="text-align:center;">
							<a href="#change-email" class="change-email" rel="basic">Change Email</a>
						</div>
					</div>
					<div><a href="#facebook" class="btn-social btn-facebook">Connect with Facebook</a></div>
				</form>
				<div class="footer">
					<p>Already registered? <a href="#login" class="blue login-here">Login here</a></p>
				</div>
			</div>
			<div id="tab-login" class="tab-content">
				<h4 class="lr-title def">Login to your account</h4>
				<h4 class="lr-title layaway">Buy in installments, log in now!</h4>
				<h4 class="lr-title sale">Get exclusive access to all sale items!</h4>
				<h4 class="lr-title wishlist">Remember your favourite items and <br>share your wishlist with your friends!</h4>
				<h4 class="lr-title wn">Get access to our latest items <br>before everybody else!</h4>
				<h4 class="lr-title notify">Login now to receive your notifications</h4>
				<form class="popup-login-login">
					<input type="hidden" name="callpage" class="call-page">
					<div class="form-box">
						<input type="email" name="uemail" class="user-email" value="<?php echo $_COOKIE['theluxury_log']; ?>" autocomplete="off" placeholder="Email">
						<input type="password" name="upwd" class="user-pwd" placeholder="Password">
						<div class="remember-me">
							<input name="uremember" class="user-remember" autocomplete="off" type="checkbox">
							<span class="label">Remember Me</span>
						</div>
						<div style="position:relative;">
							<input type="submit" value="Login" class="login-btn" rel="basic">
							<div class="action-loading"><img src="<?php echo TEMPLURL; ?>/images/loading-ajax.gif"></div>
						</div>
					</div>
					<div><a href="#facebook" class="btn-social btn-facebook">Connect with Facebook</a></div>
				</form>
				<div class="footer">
					<a href="#forgot" class="forgot-pass">Forgot your password?</a>
				</div>
			</div>
			<div id="tab-forgot-pass" class="tab-content">
				<h4>Forgot your password?</h4>
				<div class="success" style="width:290px; margin:-10px auto 10px auto; display:none;">Please check your e-mail to set your new password.</div>
				<form class="popup-login-forgot">
					<input type="hidden" name="callpage" class="call-page">
					<div class="form-box">
						<input type="email" name="uemail" class="user-email" placeholder="Email">
						<div style="position:relative;">
							<input type="submit" value="Change Password" class="forgot-btn">
							<div class="action-loading"><img src="<?php echo TEMPLURL; ?>/images/loading-ajax.gif"></div>
						</div>
					</div>
				</form>
				<div class="footer">
					<p>Already registered? <a href="#login" class="blue login-here">Login here</a></p>
				</div>
			</div>
		</div>
		<a href="#close" class="close">close</a>
	</div>
<?php } ?>

<div class="popup-login def new-login" id="first-login-popup" style="display:none;">
	<ul class="buttons">
		<li><a href="#tab-register-flp" class="register-tab-link active">Register</a></li>
		<li><a href="#tab-login-flp" class="login-tab-link">Login</a></li>
	</ul>
	<img src="<?php echo TEMPLURL; ?>/images/img-popup.png" alt="" class="img-popup">
	<div id="tab-register-flp" class="active tab-content">
		<div class="text">
			<h4>Register Now</h4>
			<ul>
				<li>70% off the world’s top luxury brands</li>
				<li>Instant access to new items</li>
				<li>Exclusive access to items on sale</li>
				<li>Create custom notifications</li>
			</ul>
		</div>
		
		<form class="popup-login-register">
			<input type="hidden" name="callpage" class="call-page" value="<?php bloginfo('home'); ?>">
			<div class="form-box register-screen-1">
				<input name="uemail" class="user-email" placeholder="Email" type="email">
				<div style="position:relative;">
					<input class="register-btn yellow" type="submit" value="Next" rel="first">
					<div class="action-loading"><img src="<?php echo TEMPLURL; ?>/images/loading-ajax.gif"></div>
				</div>
			</div>
			<div class="form-box register-screen-2" style="display:none;">
				<input name="upwd" class="user-pwd" placeholder="Password" type="password">
				<div class="row-check user-gender">
					<label>Gender</label>
					<label>
						<input name="ugender" value="Male" checked="checked" type="radio">
						Male</label>
					<label>
						<input name="ugender" value="Female" type="radio">
						Female</label>
				</div>
				<div style="position:relative;">
					<input value="Submit" class="join-btn yellow" type="submit" rel="first">
					<div class="action-loading"><img src="<?php echo TEMPLURL; ?>/images/loading-ajax.gif"></div>
				</div>
			</div>
		</form>
	</div>
	<div id="tab-login-flp" class="tab-content">
		<div class="text">
			<h4>Login to your account</h4>
			<ul>
				<li>70% off the world’s top luxury brands</li>
				<li>Instant access to new items</li>
				<li>Exclusive access to items on sale</li>
				<li>Create custom notifications</li>
			</ul>
		</div>
		<form class="popup-login-login">
			<input type="hidden" name="callpage" class="call-page" value="<?php bloginfo('home'); ?>">
			<div class="form-box">
				<input name="uemail" class="user-email" autocomplete="off" placeholder="Email" type="email">
				<input name="upwd" class="user-pwd" placeholder="Password" type="password">
				<div class="remember-me">
					<input name="uremember" class="user-remember" autocomplete="off" type="checkbox">
					<span class="label">Remember Me</span>
				</div>
				<div style="position:relative;">
					<input value="Login" class="login-btn yellow" type="submit" rel="first">
					<div class="action-loading"><img src="<?php echo TEMPLURL; ?>/images/loading-ajax.gif">
					</div>
				</div>
			</div>
		</form>

	</div>
	<a href="#close" class="close">close</a>
</div>

<?php if (is_page($OPTION['wps_indvseller_add_item_page'])) { // SELL US PAGE POPUPS ?>
	<div class="popup-box lightbox-sell" id="our-consignment-process" style="display:none;">
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
		<a href="#close" class="close">close</a>
	</div>
	<div class="popup-box lightbox-sell inner" id="what-you-can-sell" style="display:none;">
		<h3>Brands</h3>
		<div class="columns">
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
		</div>
		<div class="categories-block">
			<h3>Categories</h3>
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
		<a href="#close" class="close">close</a>
	</div>
<?php } ?>

<?php if (is_page($OPTION['wps_indvseller_my_items_page'])) { // INDIVIDUAL SELLERS POPUPS ?>
	<div class="popup-box select-payout-popup" id="help-select-payout-popup" style="display:none;">
		<div class="block">
			<h3>Select your payout</h3>
			<?php echo apply_filters('the_content', stripcslashes($OPTION['wps_sellers_select_your_payout_description'])); ?>
		</div>
		<h3>Example</h3>
		<div class="payout-row">
			<div class="column">
				<h5>Your Payout</h5>
				<span class="price">5,600 - 5,400 AED</span>
			</div>
			<div class="column col-2">
				<h5>Select Payout</h5>
				<div class="input-holder">
					<span class="input">5600</span>
					<span class="ico success"></span>
				</div>
			</div>
		</div>
		<div class="payout-row">
			<div class="column">
				<h5>Your Payout</h5>
				<span class="price">5,600 - 5,400 AED</span>
			</div>
			<div class="column col-2">
				<h5>Select Payout</h5>
				<div class="input-holder">
					<span class="input">5700</span>
					<span class="ico error"></span>
				</div>
				<p>Payout is outside the range</p>
			</div>
			<div class="column">
				<h5>Your Payout</h5>
				<span class="price">5,600 - 5,400 AED</span>
			</div>
			<div class="column">
				<h5>Select Payout</h5>
				<div class="input-holder">
					<span class="input">5700</span>
					<span class="ico error"></span>
				</div>
				<p>Payout is outside the range</p>
			</div>
		</div>
		<div class="payout-row">
			<div class="column">
				<h5>Your Payout</h5>
				<span class="price">5,600 - 5,400 AED</span>
			</div>
			<div class="column col-2">
				<h5>Select Payout</h5>
				<div class="input-holder">
					<span class="input">5,700</span>
					<span class="ico error"></span>
				</div>
				<p>Payout contains comma</p>
			</div>
		</div>
		<a href="#close" class="close">close</a>
	</div>
	<div class="popup-box no-quotation-message" id="no-quotation-message" style="display:none;">
		<p><?php echo $OPTION['wps_sellers_no_quote_message']; ?></p>
		<a href="#close" class="close">close</a>
	</div>
<?php } ?>

<?php if (is_single()) { // REQUEST THIS PRODUCT POPUP ?>
	<div class="popup-box popup-notify<?php if (!is_user_logged_in()) { echo '-login'; } ?>" id="request-this-product-popup" style="display:none">
		<div class="t-center">
			<h3 class="popup-message">Sorry, this item is out of stock!</h3>
			<p class="rtp-text">Receive an e-mail when another <?php the_title(); ?> is available.</p>
			<p class="mark rtp-success" style="display:none;">Your notification has been created.</p>
			<?php if (is_user_logged_in()) { ?>
				<a href="#notify-me" class="btn-orange logged-notify">Notify Me</a>
			<?php } ?>
		</div>
		<?php if (!is_user_logged_in()) { ?>
		<div class="popup-forms">
			<form class="p-login-form" id="rtp-register-form">
				<label class="title">Create an Account</label>
				<input type="email" name="rtp_email" class="rtp-email" placeholder="Email">
				<input type="password" name="rtp_pass" class="rtp-pass" placeholder="Password">
				<div class="check-row rtp-gender">
					<label>Gender:</label> 
					<label>
						<input type="radio" name="rtp_gender" value="Male">
						<span class="label">Male</span>
					</label>
					<label>
						<input type="radio" name="rtp_gender" value="Female">
						<span class="label">Female</span>
					</label>
				</div>
				<div style="position:relative;">
					<button class="btn-orange register-notify">Notify Me</button>
					<div class="action-loading"><img src="<?php echo TEMPLURL; ?>/images/loading-ajax.gif"></div>
				</div>
				<input type="hidden" name="rtp_value" class="rtp-value" value="<?php the_title(); ?>">
			</form>
			<form class="p-login-form" id="rtp-login-form">
				<label class="title">Log In</label>
				<input type="email" name="rtp_email" class="rtp-email" placeholder="Email">
				<input type="password" name="rtp_pass" class="rtp-pass" placeholder="Password">
				<div class="space">&nbsp;</div>
				<div style="position:relative;">
					<button class="btn-orange login-notify">Notify Me</button>
					<div class="action-loading"><img src="<?php echo TEMPLURL; ?>/images/loading-ajax.gif"></div>
				</div>
				<input type="hidden" name="rtp_value" class="rtp-value" value="<?php the_title(); ?>">
			</form>
		</div>
		<?php } ?>
		<?php
		$taxTerm = 'post_tag';
		$taglist = NWS_create_taglist($post->ID,$taxTerm);
		$q = NWS_prepare_tag_related_DB_query($taxTerm,$taglist,$post->ID,$OPTION['wps_tag_relatedProds_num']);
		$related_tags_posts = $wpdb->get_results($q);
		if(!empty($related_tags_posts)) { ?>
		<div class="products-section">
			<h3>You May Also Like</h3>
			<div class="products-items">
				<?php foreach ($related_tags_posts as $related_tags_post) {
					$pimage = get_product_thumb($related_tags_post->ID);
					$price = get_post_meta($related_tags_post->ID, 'price', true);
					$new_price = get_post_meta($related_tags_post->ID, 'new_price', true);
					if($new_price) { $price = $new_price; }
				?>
				<div class="item">
					<?php if($pimage) { ?><a href="<?php echo get_permalink($related_tags_post->ID); ?>" class="image" title="<?php echo $related_tags_post->post_title; ?>"><img src="<?php echo $pimage; ?>" alt=""></a><?php } ?>
					<h5><a href="<?php echo get_permalink($related_tags_post->ID); ?>"><?php echo get_limit_content($related_tags_post->post_title, 40, true); ?></a></h5>
					<strong class="price"><?php product_prices_list($price); ?></strong>
				</div>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
		<div class="p-footer t-right rtp-notifications-link">
			<a href="<?php echo get_permalink($OPTION['wps_account_my_alerts_page']); ?>"<?php if (!is_user_logged_in()) { echo ' style="display:none;"'; } ?>>+ Create a custom notification</a>
		</div>
		<a href="#close" class="close">close</a>
	</div>
<?php } ?>