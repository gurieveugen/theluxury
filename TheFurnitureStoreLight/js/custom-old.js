jQuery(document).ready(function(){
	// currency
	jQuery('.currency-val').click(function(){
		Enterprise.PopUpMenu.hide();
		// set currency cookie
		var currname = jQuery(this).attr('rel');
		set_currency_cookie(currname);
		change_currency();
		if (currency_reload) {
			window.location.reload();
		}
		return false;
	});
	change_currency();

	// installments
	jQuery("#installments-button").click(function(){
		jQuery().colorbox({inline:true, href:'#installments-popup'});
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
	jQuery('.shop_by_widget, .select, #mc-embedded-subscribe-form .checkbox-list').jqTransform();
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
	
	jQuery('.a-box .a-title').click(function(){
		jQuery(this).next('.a-content').slideToggle(function(){
			jQuery(this).parent().toggleClass('open');
		});
	});
	// alerts
	jQuery('.my-alerts .it-bags-alert a').click(function(){
		if (jQuery(this).hasClass('active')) {
			jQuery(this).removeClass('active');
		} else {
			jQuery(this).addClass('active');
		}
		jQuery(this).addClass('loading');
		var ids = "";
		jQuery('.my-alerts .it-bags-alert a.active').each(function(){
			if (ids != '') { ids = ids + ','; }
			ids = ids + jQuery(this).attr('rel');
		});
		jQuery.post(siteurl, 
			{
				AlertsAction: 'create_alert',
				ca_ajax: 'true',
				ca_type: 2,
				ca_value: ids
			},
			function(){
				jQuery('.my-alerts .it-bags-alert a').removeClass('loading');
			}
		);
		return false;
	});
	jQuery('.my-alerts .top-brands-alert a').click(function(){
		if (jQuery(this).hasClass('active')) {
			jQuery(this).removeClass('active');
		} else {
			jQuery(this).addClass('active');
		}
		jQuery(this).addClass('loading');
		var ids = "";
		jQuery('.my-alerts .top-brands-alert a.active').each(function(){
			if (ids != '') { ids = ids + ','; }
			ids = ids + jQuery(this).attr('rel');
		});
		jQuery.post(siteurl, 
			{
				AlertsAction: 'create_alert',
				ca_ajax: 'true',
				ca_type: 3,
				ca_value: ids
			},
			function(){
				jQuery('.my-alerts .top-brands-alert a').removeClass('loading');
			}
		);
		return false;
	});
	jQuery('.my-alerts .custom-alert-form').submit(function(){
		var ca_value = jQuery('.my-custom-alert').val();
		if (ca_value == '') {
			alert('Please enter any name of brand or item you are looking for!');
			return false;
		}
	});
	jQuery('.my-alerts .my-searches-alert img').click(function(){
		var aid = jQuery(this).attr('rel');
		jQuery(this).parent().parent().animate({height: 'hide'}, 300);
		jQuery.post(siteurl, {AlertsAction: 'remove_my_searches_alert', alert_id: aid});
	});
	jQuery('.my-alerts .my-alerts-create-request').click(function(){
		jQuery().colorbox({inline:true, href:'#my-alerts-add-popup'});
	});
	jQuery('.sidebar-create-alert-button').click(function(){
		var ca_value = '';
		var calogged = jQuery('#ca-logged').val();
		jQuery('.sidebar-create-alert-button').html('loading...');
		if (calogged == 'true') {
			jQuery('.alert-requests-list li').each(function(){
				var liclass = jQuery(this).attr('class');
				liclass = liclass.replace('category-', 'ct:');
				liclass = liclass.replace('brand-', 'br:');
				liclass = liclass.replace('colour-', 'cl:');
				liclass = liclass.replace('price-', 'pr:');
				liclass = liclass.replace('selection-', 'sl:');
				liclass = liclass.replace('size-', 'sz:');
				liclass = liclass.replace('ring-size-', 'rs:');
				if (ca_value != '') { ca_value += ';'; }
				ca_value += '{'+liclass+'}';
			});
			if (ca_value != '') {
				jQuery('.alert-created').hide();
				jQuery.post(siteurl, 
					{
						AlertsAction: 'create_alert',
						ca_ajax: 'true',
						ca_type: 1,
						ca_value: ca_value
					},
					function(data){
						jQuery('.alert-created').animate({height: 'show'}, 300);
						jQuery('.sidebar-create-alert-button').html(jQuery('.sidebar-create-alert-button').attr('alt'));
					}
				);
			}
		} else {
			var requesturl = jQuery('#ca-request-url').val();
			var addit_url = '';
			jQuery('.alert-requests-list li').each(function(){
				var liclass = jQuery(this).attr('class');
				if (addit_url != '') { addit_url += ';'; }
				addit_url += liclass;
			});
			requesturl += addit_url;
			//window.location.href = redirurl;
			jQuery.post(siteurl, 
				{
					AlertsAction: 'get_login_encodedurl',
					url: requesturl
				},
				function(encodedurl){
					window.location.href = encodedurl;
				}
			);
		}
		return false;
	});
	jQuery('.alert-continue-shopping').click(function(){
		jQuery(this).parent().animate({height: 'hide'}, 300);
		return false;
	});
	jQuery('.follow-brands #follow_brands_submit').submit(function(){
		var follow_email = jQuery('.follow-brands #follow_brands_email').val();
		var follow_brand_id = jQuery('.follow-brands #follow_brands_brand').val();

		jQuery('.follow-brands .error, .follow-brands .result').hide();

		if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(follow_email)) {
			jQuery('.follow-brands .loading').show();
			jQuery.post(siteurl, 
				{
					AlertsAction: 'create_alert',
					ca_ajax: 'true',
					ca_type: 3,
					ca_value: follow_brand_id,
					follow_brands_email: follow_email,
					ca_follow: 'true'
				},
				function(data){
					jQuery('.follow-brands .loading').hide();
					jQuery('.follow-brands .result').animate({height: 'show'}, 300).fadeOut(6000);
				}
			);
		} else {
			jQuery('.follow-brands .error').animate({height: 'show'}, 300).fadeOut(6000);
		}
		return false;
	});
	jQuery('#request-this-product').click(function() {
		var raction = jQuery('#request-action').val();
		var rvalue = jQuery('#request-value').val();
		if (raction == 'a') {
			jQuery.post(siteurl, 
				{
					AlertsAction: 'create_alert',
					ca_ajax: 'true',
					ca_type: 4,
					ca_value: rvalue
				},
				function(data){
					jQuery('.request-success').animate({height:'show'}, 300);
				}
			);
		} else {
			window.location.href = rvalue;
		}
		return false;
	});
	jQuery('.request-continue-shopping').click(function() {
		jQuery('.request-success').animate({height:'hide'}, 300);
		return false;
	});
	if (jQuery('#request-action').size()) {
		var windloc = window.location + "";
		if (windloc.indexOf('?page_referrer=registered') > 0) {
			jQuery('#request-this-product').trigger('click');
		}
	}
	// search filter
	show_size_filter(true);
	jQuery('.widget-filter .f-block h4').click(function(){
		jQuery(this).next('.f-container').slideToggle(function(){
			jQuery(this).parent().toggleClass('open');
		});
	});
	jQuery('.widget-filter .f-row').jqTransform();
	jQuery('.shop-by-category .jqTransformCheckbox').click(function(){
		show_size_filter(false);
	});
	jQuery('.search-filter-form .f-block .jqTransformCheckbox').click(function(){
		var sfid = jQuery(this).parent().find('input').attr('id');
		var sfname = jQuery(this).parent().parent().find('label').html();
		if (jQuery(this).hasClass('jqTransformChecked')) {
			create_alert_action(sfid, sfname);
		} else {
			delete_alert_action(sfid);
		}
	});
	if (jQuery('.create-alert-widget').size()) {
		if (jQuery('#ca-salerts').val() != '') {
			var salerts = jQuery('#ca-salerts').val();
			salerts = salerts.split(';');
			for (var a=0; a<salerts.length; a++) {
				var sfid = salerts[a];
				var sfname = jQuery('#'+sfid).parent().parent().find('label').html();
				create_alert_action(sfid, sfname);
			}
		} else {
			jQuery('.search-filter-form .f-block .jqTransformCheckbox').each(function(){
				var sfid = jQuery(this).parent().find('input').attr('id');
				var sfname = jQuery(this).parent().parent().find('label').html();
				if (jQuery(this).hasClass('jqTransformChecked')) {
					create_alert_action(sfid, sfname);
				}
			});
		}
	}
});
function show_size_filter(loadfl) {
	var shoes = false;
	var rings = false;
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
}

function create_alert_action(sfid, sfname) {
	sfname = sfname.replace('--', '');
	sfname = sfname.replace('-', '');
	if (!jQuery('.alert-requests-list li.'+sfid).size()) {
		jQuery('.alert-requests-list').append('<li class="'+sfid+'">'+sfname+'<span class="delete" onclick="delete_alert_action(\''+sfid+'\')"></span></li>');
	}
}
function delete_alert_action(sfid) {
	jQuery('.alert-requests-list li.'+sfid).remove();
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
