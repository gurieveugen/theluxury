var mcepopup = false;
jQuery(document).ready(function(){
	jQuery('#nav li.womenitem a:eq(0), #nav li.menitem a:eq(0)').click(function(){
		return false;
	});
	// cart shipping costs
	jQuery('.cart-ship-costs').click(function(){
		jQuery.colorbox({inline:true, href:'#cart-shipping-costs'});
		return false;
	});
	// cart remove button
	jQuery('.order_form .remove').click(function(){
		var rm = confirm('Are you sure?');
		if (rm) {
			var rmid = jQuery(this).attr('rel');
			jQuery('#'+rmid).val('1');
			jQuery('.order_form').submit();
		}
		return false;
	});
	// login/register popup
	jQuery('.popup-login .buttons').each(function(){
		var _list = jQuery(this);
		var _links = _list.find('a');
	
		_links.each(function() {
			var _link = jQuery(this);
			var _href = _link.attr('href');
			var _tab = jQuery(_href);
	
			if(_link.hasClass('active')) _tab.show();
			else _tab.hide();
	
			_link.click(function(){
				_links.filter('.active').each(function(){
					jQuery(jQuery(this).removeClass('active').attr('href')).hide();
				});
				_link.addClass('active');
				_tab.show();
				return false;
			});
		});
	});
	jQuery('#basic-login-popup .close').click(function(){
		hide_login_popup();
		return false;
	});
	jQuery('.popup-login .register-btn').click(function(){
		var trel = jQuery(this).attr('rel');
		if (trel == '') { trel = 'basic'; }
		trel = '#'+trel+'-login-popup';

		var uemail = jQuery(trel+' .popup-login-register .user-email').val();

		if (uemail == '') {
			alert('Please enter an e-mail address.');
		} else if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(uemail)) {
			alert('The e-mail address is incorrect.');
		} else {
			jQuery(trel+' .popup-login-register .register-screen-1 .action-loading').show();
			jQuery.post(siteurl, 
				{
					ajax_login_popup: 'check_exist_email',
					email: uemail
				},
				function(data){
					jQuery(trel+' .popup-login-register .register-screen-1 .action-loading').hide();
					if (data == 'exist') {
						alert('This email is already registered, please choose another one');
					} else {
						jQuery(trel+' .register-screen-1').hide();
						jQuery(trel+' .register-screen-2').show();
					}
				}
			);
		}
	});
	jQuery('.popup-login .change-email').click(function(){
		var trel = jQuery(this).attr('rel');
		if (trel == '') { trel = 'basic'; }
		trel = '#'+trel+'-login-popup';

		jQuery(trel+' .register-screen-2').hide();
		jQuery(trel+' .register-screen-1').show();
		return false;
	});
	jQuery('#basic-login-popup .login-here').click(function(){
		jQuery('.popup-login .login-tab-link').trigger('click');
		return false;
	});
	jQuery('.popup-login .forgot-pass').click(function(){
		jQuery('.popup-login .forgot-tab-link').trigger('click');
		return false;
	});
	jQuery('.popup-login .forgot-tab-link').parent().hide();
	jQuery('.popup-login-register, .popup-login-login, .popup-login-forgot').submit(function(){
		return false;
	});
	
	jQuery('.log-buttons a').click(function(){
		if (!isloggedin) {
			var ahref = jQuery(this).attr('href');
			var lnkclass = jQuery(this).attr('class');
			show_login_popup('def', ahref);
			if (lnkclass == 'register-lnk') {
				jQuery('.popup-login .register-tab-link').trigger('click');
			} else {
				jQuery('.popup-login .login-tab-link').trigger('click');
			}
			return false;
		}
	});
	jQuery('#nav .whats-new-nav a').click(function(){
		if (!isloggedin) {
			var ahref = jQuery(this).attr('href');
			show_login_popup('wn', ahref);
			return false;
		}
	});
	jQuery('#nav .sale-nav a').click(function(){
		if (!isloggedin) {
			var ahref = jQuery(this).attr('href');
			show_login_popup('sale', ahref);
			return false;
		}
	});
	jQuery('.link-wishlist').click(function(){
		if (!isloggedin) {
			var ahref = jQuery(this).attr('href');
			show_login_popup('wishlist', ahref);
			return false;
		}
	});
	// login
	jQuery('.popup-login .login-btn').click(function(){
		var trel = jQuery(this).attr('rel');
		if (trel == '') { trel = 'basic'; }
		trel = '#'+trel+'-login-popup';

		var log = jQuery(trel+' .popup-login-login .user-email').val();
		var pwd = jQuery(trel+' .popup-login-login .user-pwd').val();
		var remf = jQuery(trel+' .popup-login-login .user-remember').is(':checked');
		var callpg = jQuery(trel+' .popup-login-login .call-page').val();
		var remme = 0;
		if (remf) { remme = 1; }
		jQuery(trel+' .popup-login-login .action-loading').show();
		jQuery.post(siteurl, 
			{
				ajax_login_popup: 'login',
				log: log,
				pwd: pwd,
				remme: remme,
				callpg: callpg
			},
			function(data){
				jQuery(trel+' .popup-login-login .action-loading').hide();
				if (data == 'success') {
					hide_login_popup();
					mcEvilPopupCookie();
					if (callpg.indexOf('%26alertslogin') > 0) {
						location.reload();
					} else {
						setTimeout(function(){ window.location.href = callpg; }, 500);
					}
				} else {
					alert(data);
				}
			}
		);
	});
	// register
	jQuery('.popup-login .join-btn').click(function(){
		var trel = jQuery(this).attr('rel');
		if (trel == '') { trel = 'basic'; }
		trel = '#'+trel+'-login-popup';

		var email = jQuery(trel+' .popup-login-register .user-email').val();
		var pwd = jQuery(trel+' .popup-login-register .user-pwd').val();
		var gender = jQuery(trel+' .popup-login-register .user-gender input:checked').val();
		var callpg = jQuery(trel+' .popup-login-register .call-page').val();
		jQuery(trel+' .popup-login-register .action-loading').show();
		jQuery.post(siteurl, 
			{
				ajax_login_popup: 'register',
				email: email,
				pwd: pwd,
				gender: gender,
				callpg: callpg
			},
			function(data){
				jQuery(trel+' .popup-login-register .action-loading').hide();
				if (data == 'success') {
					hide_login_popup();
					mcEvilPopupCookie();
					if (callpg.indexOf('%26alertslogin') > 0) {
						location.reload();
					} else {
						setTimeout(function(){ window.location.href = callpg; }, 500);
					}
				} else {
					alert(data);
				}
			}
		);
	});
	// forgot password
	jQuery('.popup-login .forgot-btn').click(function(){
		var email = jQuery('.popup-login .popup-login-forgot .user-email').val();
		jQuery('.popup-login .popup-login-forgot .action-loading').show();
		jQuery.post(siteurl, 
			{
				ajax_login_popup: 'forgot',
				user_login: email
			},
			function(data){
				jQuery('.popup-login .popup-login-forgot .action-loading').hide();
				if (data == 'success') {
					jQuery('.popup-login #tab-forgot-pass .success').animate({height: 'show'}, 300);
					setTimeout(function(){
						hide_login_popup();
						jQuery('.popup-login #tab-forgot-pass .success').hide();
					}, 15000);
				} else {
					alert(data);
				}
			}
		);
	});
	jQuery('.popup-login .btn-facebook').click(function(){
		var callpg = jQuery('.popup-login .popup-login-login .call-page').val();
		fb_login(callpg);
		return false;
	});
	jQuery('.popup-login .btn-twitter').click(function(){
		return false;
	});
	jQuery('.product-socials .pinit a').attr('class', '');
	jQuery('.product-socials .pinit a').addClass('pinterest');
	jQuery('.product-socials .pinit').show();

	// first login popup
	//jQuery('*').click(function(){
	jQuery(document).click(function(){
		if (check_first_popup()) {
			popup_top_position('first-login-popup');
			jQuery('.bg-popup-login, #first-login-popup').fadeIn();
			mcepopup = true;
			return false;
		}
	});
	jQuery('#first-login-popup .close').click(function(){
		mcEvilPopupClose();
		return false;
	});
	// my wishlist remove
	jQuery('.my-wishlist-remove-form').submit(function(){
		var d = confirm('Are you sure?');
		if (d) {
			return true;
		}
		return false;
	});
});

function check_first_popup() {
	var fpopup = false;
	var clnmb = mcevilpopupclick + 1;
	mcEvilPopupClickCookie(clnmb);
	if (!mcepopup && clnmb >= 3) {
		fpopup = true;
		var cks = document.cookie.split(';');
		for(i=0;i<cks.length;i++) {
			parts = cks[i].split('=');
			if(parts[0].indexOf('MCEvilPopupClosed') != -1) { fpopup = false; }
		}
	}
	return fpopup;
}

function show_login_popup(c, ahref) {
	if (!check_first_popup()) {
		jQuery('#basic-login-popup .call-page').val(ahref);
		jQuery('#basic-login-popup').removeClass('sale').removeClass('wn').removeClass('wishlist').removeClass('notify');
		jQuery('#basic-login-popup').addClass(c);
		jQuery('#basic-login-popup .lr-title').hide();
		jQuery('#basic-login-popup h4.'+c).show();
		jQuery('#basic-login-popup .buttons li a.register-tab-link').trigger('click');

		popup_top_position('basic-login-popup');
		jQuery('.bg-popup-login, #basic-login-popup').show();
	}
}

function hide_login_popup() {
	jQuery('.bg-popup-login, .popup-login').hide();
}

function check_reload_action(callpg) {
	if (callpg.indexOf('%26alertslogin') > 0) {
		location.reload();
	}
}

function popup_top_position(pid) {
	var wh = jQuery(window).height();
	var ph = jQuery('#'+pid).height();
	var dtp = jQuery(document).scrollTop();
	var ptp = (((wh - ph) / 2) + dtp) - 30;
	jQuery('#'+pid).css('top', ptp+'px');
}

function mcEvilPopupClose() {
	hide_login_popup();
	mcEvilPopupCookie();
	mcepopup = false;
}

function mcEvilPopupCookie() {
	var now = new Date();
	var expires_date = new Date( now.getTime() + 31536000000 );
	document.cookie = 'MCEvilPopupClosed=yes;expires=' + expires_date.toGMTString()+';path=/';  
}

function mcEvilPopupClickCookie(clnmb) {
	var now = new Date();
	var expires_date = new Date( now.getTime() + 31536000000 );
	document.cookie = 'MCEvilPopupClick='+clnmb+';expires=' + expires_date.toGMTString()+';path=/';  
}

function fb_login(redirurl) {
    FB.login(function(response) {
        if (response.authResponse) {
            access_token = response.authResponse.accessToken; // get access token
            user_id = response.authResponse.userID; // get FB UID
			var query = FB.Data.query('select name, email from user where uid={0}', user_id);
			query.wait(function(rows) {
				jQuery.post(siteurl, 
					{
						ajax_login_popup: 'fblogin',
						email: rows[0].email
					},
					function(data){
						if (data == 'success') {
							window.location.href = redirurl;
						} else {
							alert(data);
						}
					}
				);
			});
        }
    }, {
        scope: 'publish_stream,email'
    });
}

window.fbAsyncInit = function() {
    FB.init({
        appId   : fapp_id,
        oauth   : true,
        status  : true,
        cookie  : true,
        xfbml   : true
    });
};
