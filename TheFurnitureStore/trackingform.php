<form method="post" id="trackingform" class="clearfix" action="<?php echo get_option('home'); ?>/index.php?checkOrderStatus=1">
	<label class="hidden" for="tid"><?php _e('Enter your Tracking ID: ','wpShop'); ?><img src="<?php bloginfo('stylesheet_directory'); ?>/images/questionmark.png" title="<?php _e('You will find the Tracking ID of your last online order in your Order confirmation email.','wpShop'); ?>" /></label>
	<input type="text" value="" name="tid" id="t" class="text" />
	<input type="submit" id="tracksubmit" value="<?php _e('Track','wpShop'); ?>" class="formbutton" />
</form>
