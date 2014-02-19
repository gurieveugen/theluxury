<form method="get" id="searchform" class="clearfix" action="<?php bloginfo('url'); ?>/">
	<label for="s"><?php _e('Search:','wpShop');?></label>
	<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" class="text" />
	<input class="formbutton" type="submit" alt="<?php _e('Find','wpShop');?>" value="<?php _e('Find','wpShop');?>" title="<?php _e('Find','wpShop');?>" />
</form>