jQuery(function() {
	jQuery('.accordion .heading').click(function(){
		jQuery(this).next('.content').slideToggle(function(){
			jQuery(this).parent().toggleClass('open');
		});
	});
	jQuery('.select select').customSelect();
	jQuery('ul.tabset').each(function(){
		var _list = $(this);
		var _links = _list.find('a');

		_links.each(function() {
			var _link = $(this);
			var _href = _link.attr('href');
			var _tab = $(_href);

			if(_link.hasClass('active')) _tab.show();
			else _tab.hide();

			_link.click(function(){
				_links.filter('.active').each(function(){
					$($(this).removeClass('active').attr('href')).hide();
				});
				_link.addClass('active');
				
				if($('ul.tabset').hasClass('inner')){
					$('.main-title').html(_link.html());
				}
				
				_tab.show();
				return false;
			});
		});
	});
	jQuery('select#billingCountry').trigger("change");
});