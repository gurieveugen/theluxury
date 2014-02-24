jQuery(document).ready(function(){
	// currency
	jQuery('.currency-val').click(function(){
		Enterprise.PopUpMenu.hide();
		// set currency cookie
		var currname = jQuery(this).attr('rel');
		set_currency_cookie(currname);
		change_currency();
		if (currency_reload) {
			if (is_prof_add_item_page == 'true') {
				profseller_currency_change_form();
			} else {
				window.location.reload();
			}
		}
		return false;
	});
	change_currency();

	// installments
	jQuery("#installments-button").click(function(){
		jQuery.colorbox({inline:true, href:'#installments-popup'});
		return false;
	});
	jQuery("#installments-continue").click(function(){
		jQuery("#installments-buy").val('1');
		jQuery("#addC").trigger('click');
		return false;
	});
	jQuery(".cl-read-terms").colorbox({inline:true, href:'#cart-layaway-terms'});
	jQuery(".layaway-purchase-q").colorbox({inline:true, href:'#cart-layaway-purchase-question'});
	jQuery(".layaway-payment-q").colorbox({inline:true, href:'#cart-layaway-payment-question'});
	jQuery(".tax-info-q").colorbox({inline:true, href:'#checkout-tax-info'});
	jQuery(".cart-layaway .clopt").click(function(){
		var clval = jQuery(this).val();
		if (clval == 1) {
			jQuery('.cart-layaway-body').animate({height: 'show'}, 300);
		} else {
			jQuery('.cart-layaway-body').animate({height: 'hide'}, 300);
		}
	});
	jQuery("#proceed2Checkout").submit(function(){
		var cloptch = jQuery(".clopt").eq(0).is(':checked');
		var cltermch = jQuery(".clterm").eq(0).is(':checked');
		var lprocess = 0;
		if (cloptch) { lprocess = 1; }

		jQuery("#layaway-process").val(lprocess);
		jQuery("#layaway-amount").val(jQuery(".cl-amount").val());
		if (cloptch && !cltermch) {
			alert('Please check Accept Terms');
			return false;
		}
		if (lprocess == 1 && !isloggedin) {
			show_login_popup('layaway', '?orderNow=1');
			return false;
		}
	});
	jQuery('.odetails-link').click(function() {
		var orel = jQuery(this).attr('rel');
		odetails_show_hide(orel);
		return false;
	});
	jQuery('.odetails-close').click(function() {
		var orel = jQuery(this).attr('rel');
		odetails_show_hide(orel);
	});
	jQuery('.continue-payment').click(function() {
		jQuery(this).parent().find('form').submit();
		return false;
	});
	jQuery('.main-product .thumbnails a').hover(function() {
		jQuery('.main-product .thumbnails a').removeClass('active');
		jQuery(this).addClass('active');
	});
	jQuery('.authenticity-nav li .anlink').click(function(){
		var arel = jQuery(this).attr('rel');
		jQuery('.authenticity-pages').find('div.active').removeClass('active').hide();
		jQuery('#'+arel).addClass('active').fadeIn(500);
		return false;
	});
	jQuery('.head-signup a').click(function(){
		jQuery('.mce_inline_error').html('');
		jQuery('#mce-error-response').html('');
		jQuery('#mce-success-response').html('');
		jQuery('#mc_embed_signup').fadeIn();
		jQuery('.head-signup').hide();
		mcEvilPopupCookie();
		return false;
	});
	jQuery('#login-with-fb-popup').click(function(){
		mcEvilPopupCookie();
	});
	jQuery('.dzs-scroller-img').colorbox();

	jQuery('.accordion .heading').click(function(){
		jQuery(this).next('.content').slideToggle(function(){
			jQuery(this).parent().toggleClass('open');
		});
	});
	jQuery('.shop_by_widget, .select, #mc-embedded-subscribe-form .checkbox-list, .prof-seller-form .label, .popup-login .remember-me').jqTransform();
	// jQuery('.solt-by-values li a').click(function(){
	// 	var psv = jQuery(this).attr('href');
	// 	psv = psv.replace('#', '');
	// 	jQuery('.psort-val').val(psv);
	// 	jQuery('form.sort-form').submit();
	// 	return false;
	// });

	jQuery('ul.tabset').each(function(){
		var _list = jQuery(this);
		var _links = _list.find('a');

		_links.each(function() {
			var _link = jQuery(this);
			var _href = _link.attr('href');
			if (_href.indexOf('#') > -1) {
				var _tab = jQuery(_href);

				if(_link.hasClass('active')) _tab.show();
				else _tab.hide();

				_link.click(function(){
					_links.filter('.active').each(function(){
						jQuery(jQuery(this).removeClass('active').attr('href')).hide();
					});
					_link.addClass('active');
					
					if(jQuery('ul.tabset').hasClass('inner')){
						jQuery('.main-title').html(_link.html());
					}
					
					_tab.show();
					return false;
				});
			}
		});
	});
	
	// search filter
	show_size_filter(true);
	
	//---------------------------------------------------------------
	
	jQuery('.widget-filter .f-row').jqTransform();
	
	jQuery('.f-block .f-container, .shop-by-category, .widget-selection .holder').mCustomScrollbar({
		scrollButtons:{
			enable:true
		}
	});
		
	jQuery('.widget-filter .f-block h4').click(function(){
		jQuery(this).next('.f-container').slideToggle(300,function(){
			jQuery(this).parent().toggleClass('open');
			jQuery(this).parents('.f-block').find('.f-container').mCustomScrollbar('update');
		});
	});
	
	// jQuery('.widget-filter .shop-by-category h4').click(function(){
	// 	jQuery(this).next('.f-container').slideToggle(300,function(){
	// 		jQuery('.shop-by-category').toggleClass('open');
	// 		jQuery(this).parents('.shop-by-category').mCustomScrollbar('update');
	// 	});		
	// });
	
	jQuery('.widget-filter .has-drop').click(function(){
		jQuery(this).next('.sub-category').slideToggle(300,function(){
			jQuery(this).parent().toggleClass('open');
			jQuery(this).parents('.shop-by-category').mCustomScrollbar('update');
		});
	});
	
	//---------------------------------------------------------------
	jQuery('.shop-by-category .jqTransformCheckbox').click(function(){
		show_size_filter(false);
		jQuery('.widget-selection .holder').mCustomScrollbar('update');
	});
	if (jQuery('.create-alert-widget').size()) {
		jQuery('.widget-selection .holder').mCustomScrollbar('update');
	}
	jQuery('.size-guide-link a').click(function(){
		var tp = jQuery(this).attr('rel');
		jQuery('.size-chart .size-chart-box').hide();
		jQuery('.size-chart .'+tp+'-type').show();
		jQuery.colorbox({inline:true, href:'#popup-size-chart'});
		return false;
	});
	// utm params
	jQuery('#utm_source').val(get_utm_param('utm_source'));
	jQuery('#utm_medium').val(get_utm_param('utm_medium'));
	jQuery('#utm_campaign').val(get_utm_param('utm_campaign'));
	jQuery('#utm_content').val(get_utm_param('utm_content'));
	jQuery('#utm_term').val(get_utm_param('utm_term'));
});
function show_size_filter(loadfl) {
	var shoes = false;
	var rings = false;
	var clothes = false;
	if (loadfl) {
		jQuery('.shop-by-category input').each(function(){
			if (jQuery(this).is(':checked')) {
				var csel = jQuery(this).attr('rel');
				if (csel == 'shoes') {
					jQuery('.shop-by-size').animate({height: 'show'}, 200);
					shoes = true;
				} else if (csel == 'rings') {
					jQuery('.shop-by-ring-size').animate({height: 'show'}, 200);
					rings = true;
				} else if (csel == 'clothes') {
					jQuery('.shop-by-clothes-size').animate({height: 'show'}, 200);
					clothes = true;
				}
			}
		});
	} else {
		jQuery('.shop-by-category .jqTransformCheckbox').each(function(){
			if (jQuery(this).hasClass('jqTransformChecked')) {
				var csel = jQuery(this).parent().find('input').attr('rel');
				if (csel == 'shoes') {
					jQuery('.shop-by-size').animate({height: 'show'}, 200);
					shoes = true;
				} else if (csel == 'rings') {
					jQuery('.shop-by-ring-size').animate({height: 'show'}, 200);
					rings = true;
				} else if (csel == 'clothes') {
					jQuery('.shop-by-clothes-size').animate({height: 'show'}, 200);
					clothes = true;
				}
			}
		});
	}
	if (!shoes) {
		jQuery('.shop-by-size').hide();
	}
	if (!rings) {
		jQuery('.shop-by-ring-size').hide();
	}
	if (!clothes) {
		jQuery('.shop-by-clothes-size').hide();
	}
}

function set_currency_cookie(currname) {
	var now = new Date();
	var expires_date = new Date(now.getTime() + 31536000000);
	document.cookie = 'theluxcurrency='+currname+';expires=' + expires_date.toGMTString()+';path=/';
}

var lcurrency = 'USD';
function change_currency() {
	var sitecookies = document.cookie.split(';');
	var iscookies = false;
	for(i=0;i<sitecookies.length;i++) {
		parts = sitecookies[i].split('=');
		if(parts[0].indexOf('theluxcurrency') != -1) { ccurrency = parts[1]; iscookies = true; }
	}
	if (!iscookies) {
		jQuery.get(siteurl + "/index.php?non_cache=true&FormAction=user-region-currency", "", function(data){
			set_currency_cookie(data);
			change_currency();
		});
		return false;
	}
	// alert(ccurrency + "|" + lcurrency);
	if (ccurrency == '') { ccurrency = 'USD'; }
	if (ccurrency != lcurrency) {
		jQuery('#currencySelect strong').attr('class', 'current currency-'+ccurrency);
		jQuery('#currencySelect strong .opacity-fader').html(ccurrency);
		jQuery('#currencySelect .currency-list li').removeClass('current');
		jQuery('#currencySelect .currency-list .currency-'+ccurrency).addClass('current');

		jQuery('.price-'+ccurrency).show();
		jQuery('.price-'+lcurrency).hide();
		update_header_cart_info();
		lcurrency = ccurrency;
		jQuery('#invite a.invite').attr('id', ccurrency);
	}
	else
	{
		jQuery('.price_value span span').each(function(){ jQuery(this).hide(); })
		jQuery('.price-box h3 strong span').each(function(){ jQuery(this).hide(); })
		jQuery('.price-'+ccurrency).show();
	}
}

function update_header_cart_info() {
	jQuery("#header-bag-info").load(siteurl + "/index.php?non_cache=true&FormAction=header-shop-cart-items");
}

function odetails_show_hide(orel) {
	if (jQuery('#'+orel).is(':visible')) {
		jQuery('#'+orel).parent().find('.odetails-link').html('Show Details');
		jQuery('#'+orel).hide();
	} else {
		jQuery('.odetails-div').hide();
		jQuery('.odetails-link').html('Show Details');

		jQuery('#'+orel).parent().find('.odetails-link').html('Hide Details');
		jQuery('#'+orel).animate({height: 'show'}, 300);
	}
}

var GATrafficSource = (function(){
	var pairs = (/(?:^|; )__utmz=([^;]*)/.exec(document.cookie)||[]).slice(1).pop().split('.').slice(4).join('.').split('|');
	var vals = {};
	for (var i = 0; i < pairs.length; i++) {
		var temp = pairs[i].split('=');
		vals[temp[0]] = temp[1];
	}
	return  {
		'utm_source': (vals.utmgclid) ? "google" : vals.utmcsr,
		'utm_medium': (vals.utmgclid) ? "cpc" : vals.utmcmd,
		'utm_campaign': vals.utmccn,
		'utm_content': vals.utmcct,
		'utm_term': vals.utmctr
	};
}());

function get_utm_param(param) {
	eval("var utm_param = GATrafficSource."+param+";");
	if (utm_param != undefined) {
		return utm_param;
	}
	return '';
}