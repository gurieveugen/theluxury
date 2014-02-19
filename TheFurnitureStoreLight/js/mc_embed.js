// JavaScript Document
/*var fnames = new Array();
var ftypes = new Array();
var mcepopup = false;
fnames[0]='EMAIL';
ftypes[0]='email';
fnames[1]='MMERGE1';
ftypes[1]='text';
fnames[2]='MMERGE2';
ftypes[2]='text';
fnames[3]='MMERGE3';
ftypes[3]='dropdown';
try 
{  
	var jqueryLoaded=jQuery;   
	jqueryLoaded=true;
} catch(err) 
{ var jqueryLoaded=false;}
var footer= document.getElementById('footer'); 
if (!jqueryLoaded) 
{
	var script = document.createElement('script');
	script.type = 'text/javascript';
	script.src = 'http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js';
	footer.appendChild(script);
	if (script.readyState && script.onload!==null)
	{
		script.onreadystatechange= function () { if (this.readyState == 'complete') mce_preload_check();}
	}
} 
var script = document.createElement('script'); 
script.type = 'text/javascript'; script.src = js_siteurl + '/wp-content/themes/TheFurnitureStoreLight/js/jquery.form-n-validate.js'; 
footer.appendChild(script); 
var err_style = ''; 
try{
	err_style != '';
} catch(e) {
	err_style = '#mc_embed_signup input.mce_inline_error{border-color:#6B0505;} #mc_embed_signup div.mce_inline_error{margin: 0 0 1em 0; padding: 5px 10px; background-color:#6B0505; font-weight: bold; z-index: 1; color:#fff;}';
}
var head = document.getElementsByTagName('head')[0];
var style = document.createElement('style');
style.type = 'text/css';
if (style.styleSheet) { style.styleSheet.cssText = err_style; } else { style.appendChild(document.createTextNode(err_style)); }
head.appendChild(style);

var mce_preload_checks = 0;
function mce_preload_check()
{   
	if (mce_preload_checks>40) return; 
	mce_preload_checks++;  
	try { var jqueryLoaded=jQuery; } catch(err) { setTimeout('mce_preload_check();', 250); return; } 
	try { var validatorLoaded=jQuery("#fake-form").validate({}); } catch(err) { setTimeout('mce_preload_check();', 250); return;} 		
	mce_init_form();
}

function mce_init_form()
{
	jQuery(document).ready( function($) 
	{
		var options = { errorClass: 'mce_inline_error', errorElement: 'div', onkeyup: function(){}, onfocusout:function(){}, onblur:function(){}  };
		var mce_validator = jQuery("#mc-embedded-subscribe-form").validate(options);
		jQuery("#mc-embedded-subscribe-form").unbind('submit'); 
		options = { 
			url: js_siteurl + '/ajax/ajax_controller.php', 
			type: 'POST', 
			dataType: 'json', 
			beforeSubmit: function()
				{
					$('#mce-responses .response').hide();
					$('#mce_tmp_error_msg').remove();
					$('.datefield','#mc_embed_signup').each(function()
						{
							var txt = 'filled';
							var fields = new Array();
							var i = 0;
							$(':text', this).each(function(){fields[i] = this;i++;});
							$(':hidden', this).each(function()
								{
									var bday = false;
									if (fields.length == 2){  bday = true;  fields[2] = {'value':1970};}	
									if ( fields[0].value=='MM' && fields[1].value=='DD' && (fields[2].value=='YYYY' || (bday && fields[2].value==1970) ) ){this.value = '';} 
									else if ( fields[0].value=='' && fields[1].value=='' && (fields[2].value=='' || (bday && fields[2].value==1970) ) ){this.value = '';} 
									else {this.value = fields[0].value+'/'+fields[1].value+'/'+fields[2].value;}
								});
						});  return mce_validator.form();
				}, 
			success: function(resp)
				{
					if(jQuery('#mce-success-response'))   jQuery('#mce-success-response').hide();
					if(jQuery('#mce-error-response')) jQuery('#mce-error-response').hide();
					if (resp.result=="success")
					{	
						//if(jQuery('#mc-embedded-subscribe')) jQuery('#mc-embedded-subscribe').hide();  
						jQuery('#mce-'+resp.result+'-response').show();  
						jQuery('#mce-'+resp.result+'-response').html('Success, You have been added to our VIP List');	 
						setTimeout( function(){ jQuery('#mc_embed_signup').fadeOut();} , 2000);  
						jQuery('#mc-embedded-subscribe-form').each(function(){this.reset();	});
						mcEvilPopupCookie();
					} 
					else 
					{  
						var index = -1;  
						var msg;  
						try 
						{
							var parts = resp.msg.split(' - ',2);
							if (parts[1]==undefined){msg = resp.msg;} 
							else {i = parseInt(parts[0]);
							if (i.toString() == parts[0]){index = parts[0];msg = parts[1];} 
							else {index = -1;msg = resp.msg;}
						}  } catch(e){index = -1;msg = resp.msg;  }  
						try
						{
							if (index== -1)
							{
								jQuery('#mce-'+resp.result+'-response').show();
								jQuery('#mce-'+resp.result+'-response').html(msg);
							} else 
							{
								err_id = 'mce_tmp_error_msg';
								html = '<div id="'+err_id+'" style="'+err_style+'"> '+msg+'</div>';
								var input_id = '#mc_embed_signup';
								var f = $(input_id);
								if (ftypes[index]=='address'){input_id = '#mce-'+fnames[index]+'-addr1';f = $(input_id).parent().parent().get(0);} 
								else if (ftypes[index]=='date'){input_id = '#mce-'+fnames[index]+'-month';f = $(input_id).parent().parent().get(0);} 
								else {input_id = '#mce-'+fnames[index];f = $().parent(input_id).get(0); }
								if (f){ $(f).append(html); $(input_id).focus();  } 
								else 
								{ 
									jQuery('#mce-'+resp.result+'-response').show(); 
									jQuery('#mce-'+resp.result+'-response').html(msg);
								} 
							} 
						} catch(e)
						{ jQuery('#mce-'+resp.result+'-response').show();  jQuery('#mce-'+resp.result+'-response').html(msg);}
					}			
				}
		};
		jQuery('#mc-embedded-subscribe-form').ajaxForm(options);  
	});
}*/
/*
var mcepopup = false;
jQuery(document).ready(function(){
	jQuery(document).click(function(){
		if (!mcepopup) {
			var show = true;
			var cks = document.cookie.split(';');
			for(i=0;i<cks.length;i++) {
				parts = cks[i].split('=');
				if(parts[0].indexOf('MCEvilPopupClosed')!= -1) { show = false; }
			}
			if (show) {
				jQuery('#mc_embed_signup a.mc_embed_close').show();
				jQuery('#mc_embed_signup').fadeIn();
				mcepopup = true;
				return false;
			}
		}
	});
	jQuery('#mc_embed_close').click(function(){	mcEvilPopupClose(); return false; });
	jQuery(document).keydown(function(e){
		if (e == null) { keycode = event.keyCode; }
		else { keycode = e.which; }
		if(keycode == 27){ mcEvilPopupClose(); }
	});
	jQuery('#mc-embedded-subscribe-form').submit(function(){
		var serror = '';
		var semail = jQuery('#mc-embedded-subscribe-form .email').val();
		var sgender = '';
		jQuery('#mc-embedded-subscribe-form .checkbox-list input').each(function(){
			if (jQuery(this).is(':checked')) {
				if (sgender != '') { sgender += ','; }
				sgender += jQuery(this).val();
			}
		});

		jQuery('#mce-error-response').hide();
		jQuery('#mce-success-response').hide();

		if (semail == '') {
			serror = 'This field is required.';
		} else if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(semail)) {
			serror = 'Please enter a valid email address.';
		}
		if (serror == '') {
			jQuery('#mce-success-response').show();
			setTimeout('mcEvilPopupClose()', 2000);
			jQuery.post(siteurl, 
				{
					FormAction: 'popup-subscribe',
					email: semail,
					gender: sgender
				}
			);
		} else {
			jQuery('#mce-error-response').html(serror);
			jQuery('#mce-error-response').show();
		}
		return false;
	});
	jQuery('#mc-embedded-subscribe-form #login-with-fb-popup').click(function(){
		fb_login(siteurl);
		return false;
	});
});

function mcEvilPopupClose(){
	jQuery('#mc_embed_signup').fadeOut();
	mcEvilPopupCookie();
	mcepopup = false;
	jQuery('#mce-error-response').hide();
	jQuery('#mce-success-response').hide();
}
function mcEvilPopupCookie(){
	var now = new Date();
	var expires_date = new Date( now.getTime() + 31536000000 );
	document.cookie = 'MCEvilPopupClosed=yes;expires=' + expires_date.toGMTString()+';path=/';  
}
*/