jQuery(function() {
	jQuery('.accordion .heading').click(function(){
		jQuery(this).next('.content').slideToggle(function(){
			jQuery(this).parent().toggleClass('open');
		});
	});
	jQuery('.shop_by_widget, .sort-form').jqTransform();
});