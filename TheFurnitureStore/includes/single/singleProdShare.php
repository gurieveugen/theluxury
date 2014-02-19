<?php $icon_fileType = $OPTION['wps_icons_file_type']; ?>
<div id="footnotes" class="noprint">
	<ul class="l1 clearfix">
		<?php if($OPTION['wps_emailFriend_enable']){ ?>
			<li class="l1_li email"><a href="#" rel="div.overlay:eq(2)"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/email.<?php echo $icon_fileType;?>" alt="Email to a friend"/><?php _e('Email a friend','wpShop');?></a></li>
		<?php } if($OPTION['wps_print_enable']){ ?>
			<li class="l1_li print"><a href="#" onclick="window.print(); return false;"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/print.<?php echo $icon_fileType;?>" alt="Print"/><?php _e('Print','wpShop');?></a></li>
		<?php } if($OPTION['wps_share_enable']){ ?>
			<li class="l1_li share"><a href="#" rel="div.overlay:eq(3)"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/share.<?php echo $icon_fileType;?>" alt="Share"/><?php _e('Share','wpShop');?></a></li>
		<?php } if($OPTION['wps_subscribe_enable']){ ?>
			<li class="l1_li subscribe"><a href="#" rel="div.overlay:eq(4)"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/socialIcons/subscribe.<?php echo $icon_fileType;?>" alt="Subscribe"/><?php _e('Subscribe','wpShop');?></a></li>
		<?php } ?>
	</ul>
</div><!-- footnotes -->