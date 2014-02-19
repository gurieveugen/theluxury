<?php
// our home page - get the above declared variable
if ($showHome === true || $showHome === '1' || $showHome === 1 ) {$text = __('Home');} else {$text = $showHome;}

// If the front page is a page, add it to the exclude list
if ($OPTION['show_on_front'] == 'page') {
	if ( !empty( $pg_exclude ) ) {
		$pg_exclude.= ',';
	} else {
		$pg_exclude= '';
	}
	$pg_exclude .= $OPTION['page_on_front'];
} ?>
<div class="clearfix main_navi hybrid_navi">
	<ul class="home_link">
		<li <?php echo (is_front_page())? 'class="current_page_item"' : ''; ?>><a href="<?php echo get_option('home'); ?>"><?php echo $text; ?></a></li>
	</ul><!-- home_link-->
	
	<ul class="categories">
		<?php foreach ((get_categories('parent=0&orderby='.$cat_orderBy.'&order='.$cat_order.'&hide_empty=0&include='.$cat_include.'&exclude='.$cat_exclude)) as $category ) { ?> 
		
			<li <?php echo ((cat_is_ancestor_of($category->cat_ID, get_query_var('cat')) || $category->cat_ID == get_query_var('cat')) ? 'class="current-cat"': ''); ?>> 
				<a href="<?php echo get_category_link($category->cat_ID); ?>"><?php echo $category->cat_name; ?></a> 
				<?php if (get_term_children($category->cat_ID,'category')) { ?> 
					<ul><?php wp_list_categories('hide_empty=0&title_li&depth=1&child_of='.$category->cat_ID ); ?></ul> 
				<?php } ?> 
			</li>
			
		<?php } ?>
	</ul><!-- categories-->	
	
	<ul class="pages">
		<?php foreach ((get_pages('title_li=&include='.$pg_include.'&exclude='.$pg_exclude.'&parent=0&sort_column='.$pg_sortColumn)) as $page ) { ?>
		
			<li <?php echo ((is_tree($page->ID) || $page->post_name == get_query_var('pagename'))? 'class="current_page_item"': ''); ?>> 
				<a href="<?php echo get_page_link($page->ID); ?>"><?php echo $page->post_title; ?></a> 
				<?php 
				$childPages = get_pages('title_li=&child_of='.$page->ID.'&parent='.$page->ID.'&sort_column=menu_order');
				if (!empty($childPages)) {?> 
					<ul><?php wp_list_pages('title_li=&child_of='. $page->ID .'&sort_column=menu_order&depth=1');?></ul> 
				<?php } ?> 
			</li>
			
		<?php } ?>
	</ul><!-- pages-->	
</div><!-- main_navi-->