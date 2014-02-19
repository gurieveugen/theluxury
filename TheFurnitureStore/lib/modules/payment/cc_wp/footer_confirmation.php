<?php $OPTION = NWS_get_global_options();?>	

			</div><!-- container -->
		</div><!-- floatswrap-->
	</div><!-- pg_wrap -->
	
	<?php 
		switch($OPTION['wps_footer_option']){
			case 'small_footer':
				#include (TEMPLATEPATH . '/includes/footers/smallFooter.php');      
				?>
				<div id="footer" class="smallft clearfix noprint">
				<div class="container clearfix">
					<p class="footer_notes">
						<span class="copyright">&copy; <?php echo date('Y'); ?>. <a href="<?php bloginfo('url'); ?>">
						<?php bloginfo('name'); ?></a>. | <?php _e('All Rights Reserved','wpShop');?>.</span>
						<span></span>
					</p>				
			<?php
			break;
				
			case 'large_footer':
				#include (TEMPLATEPATH . '/includes/footers/largeFooter.php');    
				?>
				<div id="footer" class="bigft clearfix noprint">
					<div class="container clearfix">
						<div class="footer_box">
							<div class="footer_inner_box clearfix">
								<?php #if ( is_sidebar_active('footer_left_widget_area') ) : dynamic_sidebar('footer_left_widget_area'); endif;?>	
							</div><!-- footer_inner_box -->
						</div><!-- footer_box -->
							
						<div class="footer_box middle">
							<div class="footer_inner_box clearfix">
								<?php #if ( is_sidebar_active('footer_middle_widget_area') ) : dynamic_sidebar('footer_middle_widget_area'); endif;?>	
							</div><!-- footer_inner_box -->
						</div><!-- footer_box -->
							
						<div class="footer_box">
							<div class="footer_inner_box clearfix">
									<?php #if ( is_sidebar_active('footer_right_widget_area') ) : dynamic_sidebar('footer_right_widget_area'); endif;?>
							</div><!-- footer_inner_box -->
						</div><!-- footer_box -->
							
						<p class="footer_notes">
							<span class="copyright">&copy; <?php echo date('Y'); ?>. <a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a>. | <?php _e('All Rights Reserved','wpShop');?>.</span>
							<span></span>
						</p>
			<?php
			break;
		}
	?>
		</div><!-- end container -->				
	</div><!-- end footer -->
</body>
</html>