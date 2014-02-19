<div class="meta noprint">
	<ul>
		<?php if(($OPTION['wps_publish_enable']) || ($OPTION['wps_posted_enable']) || ($OPTION['wps_tagged_enable'])){ ?>
			<p>
				<?php if($OPTION['wps_publish_enable']){ ?>
					<span class="post_date"><?php _e('' . __( 'Published:', 'wpShop' ) . ' ', 'wpShop'); the_time($OPTION['date_format']); ?></span>
				<?php } if($OPTION['wps_posted_enable']){ ?>
					<span class="post_cats"><?php _e( '' . __( 'Posted in:', 'wpShop' ) . ' ', 'wpShop' ); echo get_the_category_list(', '); ?></span>
				<?php } if($OPTION['wps_tagged_enable']){
					the_tags( '<p>' . __('' . __( 'Tagged as:', 'wpShop' ) . ' ', 'wpShop' ), ',' , '</p>' ); 
				} ?>
			</p>
		<?php } 
		
		if($OPTION['wps_prevNext_enable']){ ?>
			<p class="blogPostNav"><?php previous_post_link( '%link', '<span class="meta-nav">&laquo;</span>' . __( 'Previous:', 'wpShop' ) . ' %title', true ); ?> | <?php next_post_link( '%link', '' . __( 'Next:', 'wpShop' ) . ' %title <span class="meta-nav">&raquo;</span>', true ); ?></p>
		<?php } ?>
	</ul>
</div><!-- meta -->	