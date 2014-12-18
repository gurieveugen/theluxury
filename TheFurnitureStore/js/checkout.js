jQuery(document).ready(function(){
	// checkout login
	jQuery('.checkout-login-form').submit(function(){
		var log = jQuery('.checkout-login-form #ch-login-email').val();
		var pwd = jQuery('.checkout-login-form #ch-login-pwd').val();

		jQuery('.checkout-loading').show();
		jQuery.post(siteurl, 
			{
				ajax_login_popup: 'login',
				log: log,
				pwd: pwd,
				remme: 0
			},
			function(data){
				if (data == 'success') {
					ga_send_event('login', 'checkout_process', 'email');
					checkout_reload_your_order();
					jQuery.post(siteurl, { FormAction: 'get_user_order_info' },
						function(jsonuoinfo){
							jQuery('.checkout-loading').hide();
							if (jsonuoinfo) {
								var uoinfo = JSON.parse(jsonuoinfo);
								checkout_fill_fields(uoinfo);
							}
							jQuery('.step-sign-in').animate({height:'hide'}, 300, function(){
								jQuery('.step-delivery .payment-step-content').animate({height:'show'}, 500, function(){
									jQuery('.step-delivery').addClass('open');
									checkout_scroll_top();
								});
							});
						}
					);
				} else {
					jQuery('.checkout-loading').hide();
					alert(data);
				}
			}
		);
		return false;
	});
	// checkout register
	jQuery('.checkout-register-form').submit(function(){
		var email = jQuery('.checkout-register-form #ch-register-email').val();
		var pwd = jQuery('.checkout-register-form #ch-register-pwd').val();
		var gender = jQuery('.checkout-register-form #ch-register-gender input:checked').val();

		jQuery('.checkout-loading').show();
		jQuery.post(siteurl, 
			{
				ajax_login_popup: 'register',
				email: email,
				pwd: pwd,
				gender: gender
			},
			function(data){
				jQuery('.checkout-loading').hide();
				if (data == 'success') {
					ga_send_event('registration', 'checkout_process', 'email');
					checkout_reload_your_order();
					jQuery('.step-sign-in').animate({height:'hide'}, 300, function(){
						jQuery('.step-delivery .payment-step-content').animate({height:'show'}, 300, function(){
							jQuery('.step-delivery').addClass('open');
							checkout_scroll_top();
						});
					});
				} else {
					alert(data);
				}
			}
		);
		return false;
	});
	// checkout forgot password
	jQuery('.checkout-login-form .ch-forgot-link').click(function(){
		jQuery('.ch-login-block').hide();
		jQuery('.ch-forgot-block').animate({height: 'show'}, 200);
		return false;
	});
	jQuery('.checkout-forgot-form .ch-login-back').click(function(){
		jQuery('.ch-forgot-block').hide();
		jQuery('.ch-login-block').animate({height: 'show'}, 200);
		return false;
	});
	
	jQuery('.checkout-forgot-form').submit(function(){
		var email = jQuery('.checkout-forgot-form #ch-forgot-email').val();
		jQuery('.checkout-forgot-form .action-loading').show();
		jQuery.post(siteurl, 
			{
				ajax_login_popup: 'forgot',
				user_login: email
			},
			function(data){
				jQuery('.checkout-forgot-form .action-loading').hide();
				if (data == 'success') {
					jQuery('.ch-forgot-block .success').animate({height: 'show'}, 300);
					setTimeout(function(){
						jQuery('.ch-forgot-block .success').hide();
					}, 5000);
				} else {
					alert(data);
				}
			}
		);
		return false;
	});

	// check states list
	checkout_check_state('billing');
	checkout_check_state('shipping');

	// checkout delivery edit link
	jQuery('.ch-delivery-edit').click(function(){
		jQuery('.step-payment .payment-step-content').animate({height:'hide'}, 300, function(){
			jQuery('.step-payment').removeClass('open');
			jQuery('.step-delivery .payment-step-content').animate({height:'show'}, 300, function(){
				jQuery('.step-delivery').addClass('open');
				jQuery('.ch-delivery-edit').css('visibility', 'hidden');
				checkout_scroll_top();
			});
		});
		return false;
	});

	// checkout installments edit link
	jQuery('.ch-installments-edit').click(function(){
		jQuery('.payment-step-content').animate({height:'hide'}, 300, function(){
			jQuery('.payment-step').removeClass('open');
			jQuery('.step-installments .payment-step-content').animate({height:'show'}, 300, function(){
				jQuery('.step-installments').addClass('open');
				jQuery('.ch-installments-edit').css('visibility', 'hidden');
				checkout_scroll_top();
			});
		});
		return false;
	});

	ldtype = checkout_get_selected_delivery();
	lpm = checkout_get_selected_payment();

	// header-cart-items close button
	jQuery('.header-cart-items .hci-close').click(function(){
		close_cart_popup();
		return false;
	});
	jQuery(window).scroll(function(){
		cart_items_position();
	});
});

function elem_get_pos(elem) {
	var off_trial = document.getElementById(elem);
	var off_left = 0;
	var off_right = 0;
	var off_top = 0;

	while(off_trial)
	{
		off_left += off_trial.offsetLeft;
		off_right += off_trial.offsetRight;
		off_top += off_trial.offsetTop;
		offTrial = off_trial.offsetParent;
	}

	if (navigator.userAgent.indexOf("Mac")!=-1 && typeof document.body.leftMargin!="undefined") 
	{
	off_left+=document.body.leftMargin;
	off_top+=document.body.topMargin;
	}

	return {left:off_left , top:off_top}
}

function add_to_cart_action(post_id) {
	cart_items_position();
	if (post_id) {
		jQuery('.header-cart-items').hide();
		jQuery('#the_product .satc-loading').show();
		jQuery.post(siteurl, 
			{
				cmd: 'add',
				post_id: post_id
			},
			function(data){
				jQuery('.header-cart-items .hci-list').html(data);
				jQuery('#the_product .satc-loading').hide();
				jQuery('#header-bag-info .bag-number').html(jQuery('.header-cart-items .hci-list li').size());
				jQuery('.header-cart-items').animate({height: 'show'}, 300);
				cartpopup = true;
			}
		);
	}
	return false;
}

function close_cart_popup() {
	cartpopup = false;
	jQuery('.header-cart-items').hide();
}

function cart_items_position(){
	
	if(jQuery(window).scrollTop() > 37){
		jQuery('.header-cart-items-holder').addClass('fixed');
	}
	else{
		jQuery('.header-cart-items-holder').removeClass('fixed');
	}
}

function checkout_scroll_top() {
	jQuery('html, body').animate({ scrollTop: 0 }, 200);
}

function checkout_reload_your_order() {
	jQuery.post(siteurl, 
		{
			FormAction: 'checkout_reload_your_order'
		},
		function(data){
			jQuery('.payment-aside').html(data);
		}
	);
}

var ldtype = '';
function checkout_change_delivery(dtype) {
	if (dtype != ldtype) {
		jQuery('.delivery-line-'+ldtype).removeClass('checked');
		jQuery('.delivery-content-'+ldtype).removeClass('open');
		jQuery('.delivery-line-'+dtype).addClass('checked');
		jQuery('.delivery-content-'+dtype).addClass('open');
	}
	ldtype = dtype;
}

var lpm = '';
function checkout_change_paymemt(pm) {
	if (pm != lpm) {
		jQuery('.ch-payment-methods .check-row-line').removeClass('checked');
		jQuery('.ch-payment-methods .check-row-content').removeClass('open');
		jQuery('.ch-payment-methods .ch-pay-method-'+pm).addClass('checked');
		jQuery('.ch-payment-methods .ch-pm-content-'+pm).addClass('open');
	}
	lpm = pm;
}

function checkout_delivery_address() {
	var shipp_addr_ch = jQuery('.checkout-form #display_switch').is(':checked');
	if (shipp_addr_ch) {
		jQuery('.checkout-form #delivery_address').css('visibility', 'visible');
	} else {
		jQuery('.checkout-form #delivery_address').css('visibility', 'hidden');
	}
}

function checkout_get_selected_delivery() {
	var doption = 'post';
	if (jQuery('.checkout-form .delivery-methods').size()) {
		if (jQuery('.checkout-form #pick-up').is(':checked')) {
			doption = 'pickup';
		}
	}
	return doption;
}

function checkout_get_selected_payment() {
	var poption = 'cod';
	jQuery('.checkout-form .ch-payment-methods input').each(function(){
		if (jQuery(this).is(':checked')) {
			poption = jQuery(this).val();
		}
	});
	return poption;
}

function checkout_fill_fields(uoinfo) {
	// pickup fields
	jQuery('.checkout-form .pickup-f-name').val(uoinfo.fname);
	jQuery('.checkout-form .pickup-l-name').val(uoinfo.lname);
	jQuery('.checkout-form .pickup-email').val(uoinfo.email);
	jQuery('.checkout-form .pickup-telephone').val(uoinfo.telephone);

	// post fields
	jQuery('.checkout-form .ch-f-name').val(uoinfo.fname);
	jQuery('.checkout-form .ch-l-name').val(uoinfo.lname);
	jQuery('.checkout-form .ch-street').val(uoinfo.street);
	jQuery('.checkout-form .ch-state').val(uoinfo.state);
	jQuery('.checkout-form .ch-town').val(uoinfo.town);
	jQuery('.checkout-form .ch-zip').val(uoinfo.zip);
	jQuery('.checkout-form .ch-email').val(uoinfo.email);
	jQuery('.checkout-form .ch-telephone').val(uoinfo.telephone);

	jQuery('.checkout-form .ch-country option').removeAttr('selected');
	if (uoinfo.country != '') {
		jQuery('.checkout-form .ch-country option').each(function(){
			if (jQuery(this).html() == uoinfo.country) {
				jQuery(this).attr('selected', 'selected');
			}
		});
	}
	checkout_check_state('billing');

	// shipping
	jQuery('.checkout-form .ch-shipp-f-name').val(uoinfo.shipp_fname);
	jQuery('.checkout-form .ch-shipp-l-name').val(uoinfo.shipp_lname);
	jQuery('.checkout-form .ch-shipp-street').val(uoinfo.shipp_street);
	jQuery('.checkout-form .ch-shipp-state').val(uoinfo.shipp_state);
	jQuery('.checkout-form .ch-shipp-town').val(uoinfo.shipp_town);
	jQuery('.checkout-form .ch-shipp-zip').val(uoinfo.shipp_zip);

	jQuery('.checkout-form .ch-shipp-country option').removeAttr('selected');
	if (uoinfo.shipp_country != '') {
		jQuery('.checkout-form .ch-shipp-country option').each(function(){
			if (jQuery(this).html() == uoinfo.shipp_country) {
				jQuery(this).attr('selected', 'selected');
			}
		});
	}
	checkout_check_state('shipping');
}

function checkout_check_state(at) {
	var cntr = '';
	var st = '';
	if (at == 'shipping') {
		jQuery('.checkout-form .ch-shipp-state-select').hide();
		jQuery('.checkout-form .ch-shipp-state-txt').show();
		cntr = jQuery('.checkout-form .ch-shipp-country').val();
		st = jQuery('.checkout-form .ch-shipp-state').val();
	} else {
		jQuery('.checkout-form .ch-state-select').hide();
		jQuery('.checkout-form .ch-state-txt').show();
		cntr = jQuery('.checkout-form .ch-country').val();
		st = jQuery('.checkout-form .ch-state').val();
	}
	if (cntr != '' && cntr != undefined) {
		jQuery.post(siteurl, 
			{
				FormAction: 'checkout_get_states',
				country: cntr
			},
			function(data){
				if (data != '') {
					var slist = data.split('#');
					var soptions = '';
					for (var s=0; s<slist.length; s++) {
						var sval = slist[s].split('|');
						var sel = '';
						if (sval[0] == st) { sel = ' SELECTED'; }
						soptions += '<option value="'+sval[0]+'"'+sel+'>'+sval[1]+'</option>';
					}
					if (at == 'shipping') {
						jQuery('.checkout-form .ch-shipp-state-list').html(soptions);
						jQuery('.checkout-form .ch-shipp-state-txt').hide();
						jQuery('.checkout-form .ch-shipp-state-select').show();
					} else {
						jQuery('.checkout-form .ch-state-list').html(soptions);
						jQuery('.checkout-form .ch-state-txt').hide();
						jQuery('.checkout-form .ch-state-select').show();
					}
				}
			}
		);
	}
}

// installments tab process
function checkout_submit_installments() {
	var layaway_amount = jQuery('.ch-installments-form .ch-layaway-amount').val();
	var layaway_def_amount = jQuery('.ch-installments-form .ch-layaway-def-amount').val();
	var layaway_cart_total = jQuery('.ch-installments-form .ch-layaway-cart-total').val();
	jQuery('.ch-installments-form .ch-installments-errors').hide();
	if (layaway_amount > 0) {
		jQuery('.checkout-loading').show();
		jQuery.post(siteurl,
			{
				checkout_process: 'installments',
				frompage: 'checkout',
				layaway_process: 1,
				layaway_amount: layaway_amount,
				layaway_def_amount: layaway_def_amount,
				layaway_cart_total: layaway_cart_total
			},
			function(data){
				jQuery('.checkout-loading').hide();
				if (data != 'error') {
					checkout_reload_your_order();
					jQuery('.ch-installments-form .i-balance-total').html(data);
					jQuery('.step-installments .payment-step-content').animate({height:'hide'}, 300, function(){
						jQuery('.step-installments').removeClass('open');
						jQuery('.ch-installments-edit').css('visibility', 'visible');
						if (jQuery('.step-sign-in').size()) {
							jQuery('.step-sign-in .payment-step-content').animate({height:'show'}, 300, function(){
								jQuery('.step-sign-in').addClass('open');
								checkout_scroll_top();
							});
						} else {
							jQuery('.step-delivery .payment-step-content').animate({height:'show'}, 300, function(){
								jQuery('.step-delivery').addClass('open');
								checkout_scroll_top();
							});
						}
					});
				} else {
					jQuery('.ch-installments-form .ch-installments-errors').html('A minimum deposit amount of 25% is required to reserve the bag.');
					jQuery('.ch-installments-form .ch-installments-errors').animate({height: 'show'}, 300);
				}
			}
		);
	} else {
		jQuery('.ch-installments-form .ch-installments-errors').html('Please enter installment amount.');
		jQuery('.ch-installments-form .ch-installments-errors').animate({height: 'show'}, 300);
	}
	return false;
}

// delivery tab process
function checkout_submit_delivery() {
	var delivery_data = {};
	var doption = checkout_get_selected_delivery();
	var terms_ch = jQuery('.checkout-form .ch-terms-accepted').is(':checked');
	var shipp_addr_ch = jQuery('.checkout-form .ch-delivery-address-yes').is(':checked');

	var terms_accepted = '';
	var delivery_address_yes = '';
	if (terms_ch) { terms_accepted = 1; }
	if (shipp_addr_ch) { delivery_address_yes = 1; }

	if (doption == 'pickup') {
		delivery_data = {
			checkout_process: 'delivery',
			d_option: doption,
			f_name: jQuery('.checkout-form .pickup-f-name').val(),
			l_name: jQuery('.checkout-form .pickup-l-name').val(),
			email: jQuery('.checkout-form .pickup-email').val(),
			telephone: jQuery('.checkout-form .pickup-telephone').val(),
			delivery_address_yes: delivery_address_yes,
			terms_accepted: terms_accepted
		};
	} else {
		// fill state value
		var b_state = jQuery('.checkout-form .ch-state-txt').val();
		var d_state = jQuery('.checkout-form .ch-shipp-state-txt').val();
		if (jQuery('.checkout-form .ch-state-select').is(':visible')) {
			b_state = jQuery('.checkout-form .ch-state-list').val();
		}
		if (jQuery('.checkout-form .ch-shipp-state-select').is(':visible')) {
			d_state = jQuery('.checkout-form .ch-shipp-state-list').val();
		}
		jQuery('.checkout-form .ch-state').val(b_state);
		jQuery('.checkout-form .ch-shipp-state').val(d_state);

		delivery_data = {
			checkout_process: 'delivery',
			d_option: doption,
			f_name: jQuery('.checkout-form .ch-f-name').val(),
			l_name: jQuery('.checkout-form .ch-l-name').val(),
			email: jQuery('.checkout-form .ch-email').val(),
			telephone: jQuery('.checkout-form .ch-telephone').val(),
			country: jQuery('.checkout-form .ch-country').val(),
			street: jQuery('.checkout-form .ch-street').val(),
			state: jQuery('.checkout-form .ch-state').val(),
			town: jQuery('.checkout-form .ch-town').val(),
			zip: jQuery('.checkout-form .ch-zip').val(),
			shipp_f_name: jQuery('.checkout-form .ch-shipp-f-name').val(),
			shipp_l_name: jQuery('.checkout-form .ch-shipp-l-name').val(),
			shipp_country: jQuery('.checkout-form .ch-shipp-country').val(),
			shipp_street: jQuery('.checkout-form .ch-shipp-street').val(),
			shipp_state: jQuery('.checkout-form .ch-shipp-state').val(),
			shipp_town: jQuery('.checkout-form .ch-shipp-town').val(),
			shipp_zip: jQuery('.checkout-form .ch-shipp-zip').val(),
			delivery_address_yes: delivery_address_yes,
			terms_accepted: terms_accepted
		};

	}
	// submit data
	jQuery('.checkout-loading').show();
	jQuery('.checkout-form .ch-delivery-errors').hide();
	jQuery.post(siteurl, delivery_data,
		function(data){
			jQuery('.checkout-loading').hide();
			if (data == 'empty-cart') {
				window.location.href = jQuery('.checkout-form .ch-cart-url').val();
			} else if (data == 'success') {
				checkout_reload_your_order();
				jQuery('.checkout-form .ch-payment-errors').hide();
				jQuery('.step-delivery .payment-step-content').animate({height:'hide'}, 300, function(){
					jQuery('.step-delivery').removeClass('open');
					jQuery('.step-payment .payment-step-content').animate({height:'show'}, 300, function(){
						jQuery('.step-payment').addClass('open');
						jQuery('.ch-delivery-edit').css('visibility', 'visible');
						checkout_scroll_top();
					});
				});
			} else {
				jQuery('.checkout-form .ch-delivery-errors').html(data);
				jQuery('.checkout-form .ch-delivery-errors').animate({height: 'show'}, 300);
			}
		}
	);
	return false;
}

// payment tab process
function checkout_submit_payment() {
	var doption = checkout_get_selected_delivery();
	var poption = checkout_get_selected_payment();
	var country = jQuery('.checkout-form .ch-country').val();
	var voucher_code = jQuery('#voucher-code').val();
	var shipp_addr_ch = jQuery('.checkout-form .ch-delivery-address-yes').is(':checked');
	if (shipp_addr_ch) {
		country = jQuery('.checkout-form .ch-shipp-country').val();
	}
	jQuery('.checkout-loading').show();
	jQuery('.checkout-form .ch-payment-errors').hide();
	jQuery.post(siteurl, 
		{
			checkout_process: 'payment',
			d_option: doption,
			p_option: poption,
			country: country,
			voucher: voucher_code
		},
		function(data){
			if (data == 'empty-cart') {
				window.location.href = jQuery('.checkout-form .ch-cart-url').val();
			} else if (data == 'error') {
				jQuery('.checkout-loading').hide();
				jQuery('.checkout-form .ch-payment-errors').html('Cash on delivery is not available outside UAE');
				jQuery('.checkout-form .ch-payment-errors').animate({height: 'show'}, 300);
			} else {
				jQuery('.checkout-payment-form').html(data);
				jQuery('.submit-order-'+poption).submit();
			}
		}
	);
	return false;
}

function check_voucher_code() {
	var vcode = jQuery('#voucher-code').val();
	jQuery('.voucher-block .pay-status').hide().removeClass('false');
	jQuery('.voucher-block .action-loading').show();
	jQuery.post(siteurl, 
		{
			FormAction: 'check_voucher',
			vcode: vcode
		},
		function(data){
			jQuery('.voucher-block .action-loading').hide();
			if (data == 'success') {
				jQuery('.voucher-block .pay-status').show();
			} else {
				if (vcode != '') {
					jQuery('.voucher-block .pay-status').addClass('false').show();
				}
			}
			checkout_reload_your_order();
		}
	);
}
