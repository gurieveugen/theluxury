<?php
$sv = trim($_GET['s']);
if (!strlen($sv)) { $sv = 'Search Here...'; }
?>
<form name="search" method="get" id="sform" class="clearfix" action="<?php bloginfo('url'); ?>/" onsubmit="if(document.search.s.value=='Search Here...'){document.search.s.value='';}">
	<input type="text" value="<?php echo $sv; ?>" name="s" id="stext" class="text" onfocus="if(this.value=='Search Here...'){this.value='';}" onblur="if(this.value==''){this.value='Search Here...';}" />
	<input type="submit" id="searchgo" value="<?php _e('Go','wpShop');?>" class="formbutton" />
</form>