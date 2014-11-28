jQuery(document).ready(function(){
	// most popular items alerts
    jQuery('.n-box .column ul li a').click(function(){
		if (jQuery(this).hasClass('active')) {
			jQuery(this).removeClass('active');
		} else {
			jQuery(this).addClass('active');
		}
		jQuery(this).addClass('loading');
		var ids = "";
		jQuery('.n-box .column ul li a.active').each(function(){
			if (ids != '') { ids = ids + ';'; }
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
				jQuery('.n-box .column ul li a').removeClass('loading');
			}
		);
		return false;
	});
	// our brands alerts
    jQuery('.n-box ul.col-5 a').click(function(){
		if (jQuery(this).hasClass('active')) {
			jQuery(this).removeClass('active');
		} else {
			jQuery(this).addClass('active');
		}
		jQuery(this).addClass('loading');
		var ids = "";
		jQuery('.n-box ul.col-5 a.active').each(function(){
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
				jQuery('.n-box ul.col-5 a').removeClass('loading');
			}
		);
		return false;
	});
	jQuery('.custom-alert-form').submit(function(){
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
    jQuery('ul.notifications-list img').click(function(){
		var aid = jQuery(this).attr('rel');
		jQuery(this).parent().animate({height: 'hide'}, 300);
		jQuery.post(siteurl, {AlertsAction: 'remove_my_searches_alert', alert_id: aid});
	});
	jQuery('.my-alerts .my-alerts-create-request, .n-item .btn-yellow').click(function(){
		jQuery.colorbox({inline:true, href:'#my-alerts-add-popup'});
        return false;
	});
	jQuery('.sidebar-create-alert-button').click(function(){
		var ca_value = '';
		var ca_search_value = '';
		var ca_popup_names = '';
		if (isloggedin) {
			jQuery('.alert-requests-list li').each(function(){
				var liclass = jQuery(this).attr('class');
				var aname = jQuery(this).attr('rel');
				ca_popup_names += '<span class="tag">'+aname+'</span>';
				if (liclass == 'search-val') {
					ca_search_value = aname;
				} else {
					liclass = liclass.replace('category-', 'ct:');
					liclass = liclass.replace('brand-', 'br:');
					liclass = liclass.replace('colour-', 'cl:');
					liclass = liclass.replace('price-', 'pr:');
					liclass = liclass.replace('selection-', 'sl:');
					liclass = liclass.replace('size-', 'sz:');
					liclass = liclass.replace('ring-size-', 'rs:');
					liclass = liclass.replace('clothes-size-', 'cs:');
					liclass = liclass.replace('tag-', 'tg:');
					if (ca_value != '') { ca_value += ';'; }
					ca_value += '{'+liclass+'}';
				}
			});
			if (ca_value != '' || ca_search_value != '') {
				if (ca_value != '') {
					jQuery.post(siteurl, 
						{
							AlertsAction: 'create_alert',
							ca_ajax: 'true',
							ca_type: 1,
							ca_value: ca_value
						}
					);
				}
				if (ca_search_value != '') {
					jQuery.post(siteurl, 
						{
							AlertsAction: 'create_alert',
							ca_ajax: 'true',
							ca_type: 4,
							ca_value: ca_search_value
						}
					);
				}
				jQuery('#popup-notification .notification-tags').html(ca_popup_names);
				jQuery.colorbox({inline:true, href:'#popup-notification', closeButton: false, className: 'notifications-popup'});
			}
		} else {
			var redirurl = window.location + '';
			if (redirurl.indexOf('?s=') > 0) {
				redirurl = redirurl + '&alertslogin=true';
			} else {
				if (redirurl.indexOf('#!') > 0) {
					redirurl = redirurl + '%26alertslogin%3Dtrue';
				} else if (redirurl.indexOf('?') > 0) {
					redirurl = redirurl + '&alertslogin=true';
				} else {
					redirurl = redirurl + '?alertslogin=true';
				}
			}
			show_login_popup('notify', redirurl);
		}
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
	// request this product
	jQuery('#request-this-product').click(function() {
		var rvalue = jQuery('#request-value').val();
		show_custom_popup('request-this-product-popup');
		/*if (isloggedin) {
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
			var redirurl = window.location + '#requestlogin=true';
			show_login_popup('def', redirurl);
		}*/
		return false;
	});
	if (jQuery('#request-this-product-value').size() && jQuery('#request-this-product-popup').size()) {
		setTimeout(function(){
			show_custom_popup('request-this-product-popup');
		}, 3000);
	}
	jQuery('#request-this-product-popup .logged-notify').click(function() {
		if (!jQuery(this).hasClass('disabled')) {
			var rvalue = jQuery('#request-this-product-value').val();
			jQuery(this).addClass('disabled');
			jQuery('#request-this-product-popup .rtp-text').hide();
			jQuery('#request-this-product-popup .rtp-success').animate({height: 'show'}, 300);
			jQuery.post(siteurl, 
				{
					AlertsAction: 'create_alert',
					ca_ajax: 'true',
					ca_type: 4,
					ca_value: rvalue
				}
			);
		}
		return false;
	});
	jQuery('#request-this-product-popup .register-notify').click(function() {
		var error = '';
		var rvalue = jQuery('#rtp-register-form .rtp-value').val();
		var email = jQuery('#rtp-register-form .rtp-email').val();
		var pass = jQuery('#rtp-register-form .rtp-pass').val();
		var gender = jQuery('#rtp-register-form .rtp-gender input:checked').val();
		if (gender == undefined) { gender = ''; }

		if (email == '') {
			error += 'Email is required.\r\n';
		} else if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(email)) {
			error += 'Email is incorrect.\r\n';
		}
		if (pass == '') {
			error += 'Password is required.\r\n';
		}
		if (gender == '') {
			error += 'Gender is required.\r\n';
		}

		if (error == '') {
			jQuery('#rtp-register-form .action-loading').show();
			jQuery.post(siteurl, 
				{
					ajax_login_popup: 'register',
					email: email,
					pwd: pass,
					gender: gender
				},
				function(data){
					jQuery('#rtp-register-form .action-loading').hide();
					if (data == 'success') {
						jQuery('#request-this-product-popup .popup-forms').animate({height: 'hide'}, 300);
						jQuery('#request-this-product-popup .rtp-text').hide();
						jQuery('#request-this-product-popup .rtp-success').animate({height: 'show'}, 300);
						jQuery('#request-this-product-popup .rtp-notifications-link a').show();
						jQuery.post(siteurl, 
							{
								AlertsAction: 'create_alert',
								ca_ajax: 'true',
								ca_type: 4,
								ca_value: rvalue
							}
						);
					} else {
						alert(data);
					}
				}
			);
		} else {
			alert(error);
		}
		return false;
	});
	jQuery('#request-this-product-popup .login-notify').click(function() {
		var error = '';
		var rvalue = jQuery('#rtp-login-form .rtp-value').val();
		var email = jQuery('#rtp-login-form .rtp-email').val();
		var pass = jQuery('#rtp-login-form .rtp-pass').val();

		if (email == '') {
			error += 'Email is required.\r\n';
		} else if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(email)) {
			error += 'Email is incorrect.\r\n';
		}
		if (pass == '') {
			error += 'Password is required.\r\n';
		}

		if (error == '') {
			jQuery('#rtp-login-form .action-loading').show();
			jQuery.post(siteurl, 
				{
					ajax_login_popup: 'login',
					log: email,
					pwd: pass
				},
				function(data){
					jQuery('#rtp-login-form .action-loading').hide();
					if (data == 'success') {
						jQuery('#request-this-product-popup .popup-forms').animate({height: 'hide'}, 300);
						jQuery('#request-this-product-popup .rtp-text').hide();
						jQuery('#request-this-product-popup .rtp-success').animate({height: 'show'}, 300);
						jQuery('#request-this-product-popup .rtp-notifications-link a').show();
						jQuery.post(siteurl, 
							{
								AlertsAction: 'create_alert',
								ca_ajax: 'true',
								ca_type: 4,
								ca_value: rvalue
							}
						);
					} else {
						alert(data);
					}
				}
			);
		} else {
			alert(error);
		}
		return false;
	});
	jQuery('.p-login-form').submit(function(){ return false; });

	if (jQuery('.create-alert-widget').size()) {
		var searchval = jQuery('.search-form #s').val();
		if (searchval != '') {
			create_alert_action('search-val', searchval);
		}
		if (jQuery('.sale-category-pg').size()) {
			var scd = jQuery('.sale-category-pg').html().split(';');
			create_alert_action('category-'+scd[0], scd[1]);
		}
		if (jQuery('.tag-pg').size()) {
			var tdata = jQuery('.tag-pg').html().split(';');
			create_alert_action('tag-'+tdata[0], tdata[1]);
		}
		init_alerts_action();
	}
	jQuery('.search-filter-form .jqTransformCheckbox').click(function(){
		var sfid = jQuery(this).parent().find('input').attr('id');
		var sfname = jQuery(this).parent().parent().find('label').attr('title');
		if (jQuery(this).hasClass('jqTransformChecked')) {
			create_alert_action(sfid, sfname);
		} else {
			delete_alert_action(sfid);
		}
		check_unchecked_alerts();
		jQuery('.widget-selection .holder').mCustomScrollbar('update');
	});
	jQuery('.popup-notification a.close').click(function(){
		jQuery.colorbox.close();
		return false;
	});
	jQuery('.popup-notification .f-holder a.f-tag').click(function(){
		var fobr_id = jQuery(this).attr('rel');
		var fobr_name = jQuery(this).html();
		var fobr_ip = jQuery('#pnff-ip').val();
		var fobr_email = jQuery('#pnff-email').val();

		jQuery('.popup-notification .pn-follow-success').hide();
		// create alert
		jQuery.post(siteurl, 
			{
				AlertsAction: 'create_alert',
				ca_ajax: 'true',
				ca_follow: 'true',
				ca_type: 3,
				ca_value: fobr_id
			}
		);

		// follow brand
		jQuery.post(siteurl+'/wp-content/plugins/follow_brands/ajax_handler.php', 
			{
				follow_brands_action: 'Subscribe',
				follow_brands_ip: fobr_ip,
				follow_brands_email: fobr_email,
				follow_brands_brand: fobr_id,
				call_type: 'ajax'
			}
		);
		fobr_name = fobr_name.replace('Follow ', '');
		jQuery('.popup-notification .pn-follow-success span').html(fobr_name);
		jQuery('.popup-notification .pn-follow-success').animate({height: 'show'}, 300);
		return false;
	});
});

function init_alerts_action() {
	jQuery('.search-filter-form .jqTransformCheckbox').each(function(){
		var sfid = jQuery(this).parent().find('input').attr('id');
		var sfname = jQuery(this).parent().parent().find('label').attr('title');
		if (jQuery(this).hasClass('jqTransformChecked')) {
			create_alert_action(sfid, sfname);
		}
	});
}

function alertslogin_action() {
	var wl = window.location + '';
	if (wl.indexOf('alertslogin') > 0) {
		setTimeout(function(){
			jQuery('.sidebar-create-alert-button').trigger('click');
			setTimeout(function(){ jQuery('#cboxLoadingOverlay, #cboxLoadingGraphic').hide(); }, 500);
		}, 1000);
	}
}

function create_alert_action(sfid, sfname) {
	var delete_btn = '<span class="delete" onclick="delete_alert_action(\''+sfid+'\')"></span>';
	var is_sale = false;
	if (jQuery('.sale-category-pg').size()) {
		var scd = jQuery('.sale-category-pg').html().split(';');
		if (sfid == 'category-'+scd[0]) {
			is_sale = true;
		}
	}
	if(jQuery('#' + sfid).hasClass('frozen') || is_sale) { delete_btn = ''; }
	if (sfname.substring(0, 2) == '--') {
		sfname = sfname.substring(2);
	}
	if (sfname.substring(0, 2) == '-') {
		sfname = sfname.substring(1);
	}
	if (!jQuery('.alert-requests-list li.'+sfid).size()) {
		jQuery('.alert-requests-list').append('<li class="'+sfid+'" rel="'+sfname+'">'+sfname+delete_btn+'</li>');
		jQuery('.widget-selection .holder').mCustomScrollbar('update');
	}
}
function delete_alert_action(sfid) {
	if(sfid == 'search-val')
	{
		filter_properties.args['s'] = '';
	}
	jQuery('.alert-requests-list li.'+sfid).remove();
	jQuery('#'+sfid).attr('checked', false).parent().find('a.jqTransformCheckbox').removeClass('jqTransformChecked');
	launchFilter('#'+sfid);	
	jQuery('.widget-selection .holder').mCustomScrollbar('update');
	setTimeout(function(){ jQuery('.create-alert-widget .mCSB_container').css('top', '0px'); }, 500);
}

function check_unchecked_alerts() {
	if (jQuery('.alert-requests-list li').size()) {
		var scid = '';
		if (jQuery('.sale-category-pg').size()) {
			var scd = jQuery('.sale-category-pg').html().split(';');
			scid = 'category-'+scd[0];
		} else if (jQuery('body').hasClass('search-results')) {
			scid = 'search-val';
		}
		jQuery('.alert-requests-list li').each(function(){
			var oid = jQuery(this).attr('class');
			if (oid != scid) {
				if (!jQuery('#'+oid).parent().find('a.jqTransformCheckbox').hasClass('jqTransformChecked')) {
					delete_alert_action(oid);
				}
			}
		});
	}
}
