<div id="footer" class="bigft clearfix noprint">
	<div class="footer_box">
		<div class="footer_inner_box clearfix">
			<div class="fb-like" data-href="https://www.facebook.com/theluxurycloset" data-width="250" data-show-faces="true" data-send="true"></div>
			<?php //if ( is_sidebar_active('footer_left_widget_area') ) : dynamic_sidebar('footer_left_widget_area'); endif;?>	
		</div><!-- footer_inner_box -->
	</div><!-- footer_box -->
		
	<div class="footer_box middle">
		<div class="footer_inner_box clearfix">
			<?php if ( is_sidebar_active('footer_middle_widget_area') ) : dynamic_sidebar('footer_middle_widget_area'); endif;?>	
		</div><!-- footer_inner_box -->
	</div><!-- footer_box -->
	
	<div class="footer_box secure">
		<div class="footer_inner_box clearfix">
				<?php if ( is_sidebar_active('footer_right_widget_area') ) : dynamic_sidebar('footer_right_widget_area'); endif;?>
		</div><!-- footer_inner_box -->
	</div><!-- footer_box -->
</div><!-- end footer -->