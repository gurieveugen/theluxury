<?php
/*
Template Name: Page Buy Installments
*/
?>
<?php get_header(); ?>

<script type="text/javascript">
	var itab_curr = '1';
	jQuery(document).ready(function() {
		jQuery('#installments-tabs-links li a').click(function(){
			var itabrel = jQuery(this).attr('rel');
			var itabid = itabrel.replace('itab-', '');
			if (itabid != itab_curr) {
				jQuery('#tabli-'+itab_curr).removeClass('ui-tabs-active ui-state-active');
				jQuery(this).parent().addClass('ui-tabs-active ui-state-active');
				jQuery('#itab-'+itab_curr).animate({height: 'hide'}, 300, function(){
					jQuery('#itab-'+itabid).animate({height: 'show'}, 300);
					itab_curr = itabid;
				});
			}
			return false;
		});
	});
</script>

<!--Container Begin Here-->

<div class="container">
  <div class="lawawayMain lfloat whiteBackground">
    <div class="row">
      <div id="tabs-left" class="tabs">
		<?php
		$installments_pages = get_pages('child_of='.$post->ID.'&sort_column=menu_order&sort_order=asc');
		if ($installments_pages) {
		?>
		<ul class="ui-tabs-nav lfloat" id="installments-tabs-links">
          <?php $i = 1; foreach($installments_pages as $installments_page) { ?>
			<li class="ui-state-default <?php if($i == 1) echo 'ui-tabs-active ui-state-active'; ?>" id="tabli-<?php echo $i; ?>">
				<a href="#<?php echo $installments_page->post_name; ?>" rel="itab-<?php echo $i; ?>"><h4><?php echo $installments_page->post_title; ?></h4></a>
			</li>
			<?php $i++; } ?>
		</ul>
		
        <div class="lawawayContent rfloat" id="installments-tabs"> 
          <?php $i = 1; foreach($installments_pages as $installments_page) { ?>
          <div id="itab-<?php echo $i; ?>" class="lfloat ui-tabs-panel"<?php echo $itab_style; ?>>
            <h1><?php echo $installments_page->post_title; ?></h1>
			<?php echo apply_filters('the_content', $installments_page->post_content); ?>
          </div>
		  <?php $i++; $itab_style = 'style="display:none;"'; } ?>
        </div>
		<?php } ?>
      </div>
    </div>
  </div>
</div>

<!--Container End Here-->

<?php get_footer(); ?>