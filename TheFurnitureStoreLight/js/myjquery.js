jQuery(function ($) {
	// $.ajaxSetup forces the browser NOT to cache AJAX calls.
	$.ajaxSetup ({  
		cache: false  
	});
	
	/* jQuery Tools - Overlay */
	$("#header a.quickLogin, #footnotes a[rel], #singleMainContent .shipping_info a, #singleMainContent .add_to_wishlist_inactive a, #floatswrap .order_table a[rel], #singleMainContent .supplinfo a, #step2form a[rel]").each(function(i) {
			
		$(this).overlay({
			mask: '#000'
		});			
	});
	
	// load external content
	$("#header a.extLoadTrigger").overlay({
		mask: '#000',
		
		onBeforeLoad: function() { 
			
			// let the user know that something is about to load 
			var ajax_load = "<img class='loadingImg' src='"+ NWS_template_directory +"/images/ajax-loader.gif' alt='loading...' />";
            // grab wrapper element inside content 
            //var wrap = this.getContent().find("div.extLoadWrap"); 
			var wrap = this.getOverlay().find("div.extLoadWrap"); 
			// get the page specified in the trigger and a specific element from inside it 
			//var toLoad = $(this.getTrigger()).attr('href')+' .page_post';
			var toLoad = $(this.getTrigger()).attr('href')+' .container .whereAmI + div';
            // load it! 
			wrap.html(ajax_load).load(toLoad); 
        }
	});
	
	// load external content
	$("#singleMainContent .suppl_InfoLoad a").overlay({
		mask: '#000',
		
		onBeforeLoad: function() { 
			
			// let the user know that something is about to load 
			var ajax_load = "<img class='loadingImg' src='"+ NWS_template_directory +"/images/ajax-loader.gif' alt='loading...' />";
            // grab wrapper element inside content 
            //var wrap = this.getContent().find("div.supplInfoWrap");
			var wrap = this.getOverlay().find("div.supplInfoWrap"); 			
			// get the page specified in the trigger and a specific element from inside it 
			//var toLoad = $(this.getTrigger()).attr('href')+' .page_post';
			var toLoad = $(this.getTrigger()).attr('href')+' .container .whereAmI + div';
            // load it! 
			wrap.html(ajax_load).load(toLoad); 
        }
	});

	// order review and editing
	$("#floatswrap h4.step3 a.step3_edit").overlay({
		mask: '#000',
		
		onBeforeLoad: function() { 
			
			// let the user know that something is about to load 
			var ajax_load = "<img class='loadingImg' src='"+ NWS_template_directory +"/images/ajax-loader.gif' alt='loading...' />";
            // grab wrapper element inside content 
           // var wrap = this.getContent().find("div.editOrderWrap");
			var wrap = this.getOverlay().find("div.editOrderWrap"); 
			// get the page specified in the trigger and a specific element from inside it 
			var Link = $(this.getTrigger()).attr('href');
			//separate the # to get the class
			var urlParts 	= Link.split("#");			
			var baseUrl		= urlParts[0];
			var Id 			= urlParts[1];
			
			//set the form action based on the Id
			var Action 	= null;
		
			
			if ((Id == 'editDelivery') || (Id == 'editPayment')) {
			
				var Action 			= '?orderNow=3&amp;dpchange=1';
				var anotherField	= '';
			}
			else if ((Id == 'editAddress') || (Id == 'editNote')) {
				var Action 			= '?orderNow=2';
				var anotherField	= "<br/><input type='checkbox' name='terms_accepted' value='on' checked='checked'/><a href='?showTerms=1' target='_blank'>I accept the Terms &amp; Conditions</a><br/><input type='hidden' name='step2' value='1' /><br/>";
			}
			else {
				var Action 			= '?showCart=1';
				var anotherField	= '';
			}
			
			//put it together
			var toLoad = baseUrl +' #' + Id;
			//alert(toLoad);
		
			// load it! 
			wrap.html(ajax_load).load(toLoad, function() {
				$('#editOrderOverlay .editOrderWrap .editCont').append('<input type="hidden" name="saveWhat" value="'+Id+'" />'+anotherField+'<input type="submit" value="Save" name="saveEdit" class="formbutton saveEdit" />').wrap('<form method="post" action="'+Action+'"></form>'); 
			});
			
		}
	});
	

/* Category - Image Hover */
	$('#floatswrap .theCats .contentWrap').hover(function(){
		var $go = $(this).find('.teaser');
		$go.stop().animate({'opacity':'0.8'},{queue:false,duration:500});
	}, function(){
		var $go = $(this).find('.teaser');
		$go.stop().animate({'opacity':'0'},{queue:false,duration:500});
	});

/* Multiple Product Pages - Image Hover */
	$('#floatswrap .contentWrap').hover(function(){
		var $go = $(this).find('.hover_link');
		$go.stop().animate({opacity:'0'},{queue:false,duration:500});
	}, function(){
		var $go = $(this).find('.hover_link');
		$go.stop().animate({opacity:'1'},{queue:false,duration:500});
	});

	$('#floatswrap .contentWrap').hover(function(){
		var $go = $(this).find('.hover_link');
		$go.stop().animate({opacity:'0'},{queue:false,duration:500});
	}, function(){
		var $go = $(this).find('.hover_link');
		$go.stop().animate({opacity:'1'},{queue:false,duration:500});
	});

	jQuery.ajaxSetup({ complete: function(){ 
		/* Category - Image Hover */
			$('#floatswrap .theCats .contentWrap').hover(function(){
				var $go = $(this).find('.teaser');
				$go.stop().animate({'opacity':'0.8'},{queue:false,duration:500});
			}, function(){
				var $go = $(this).find('.teaser');
				$go.stop().animate({'opacity':'0'},{queue:false,duration:500});
			});

		/* Multiple Product Pages - Image Hover */
			$('#floatswrap .contentWrap').hover(function(){
				var $go = $(this).find('.hover_link');
				$go.stop().animate({opacity:'0'},{queue:false,duration:500});
			}, function(){
				var $go = $(this).find('.hover_link');
				$go.stop().animate({opacity:'1'},{queue:false,duration:500});
			});

			$('#floatswrap .contentWrap').hover(function(){
				var $go = $(this).find('.hover_link');
				$go.stop().animate({opacity:'0'},{queue:false,duration:500});
			}, function(){
				var $go = $(this).find('.hover_link');
				$go.stop().animate({opacity:'1'},{queue:false,duration:500});
			});
	}});

/* Single Product Page - jQuery Tools - Tabs*/
	$("#singleMainContent .related .tabs").tabs("div.panes > div", { event:'mouseover' });
	
	$("#singleMainContent .thumbTabs").tabs("div.mediaPanes > .theProdMedia", { event:'mouseover' });
	$("#singleMainContent .innerProdMedia .inner_thumbTabs").tabs("div.inner_mediaPanes > .theInner_ProdMedia", { event:'mouseover' });
	
/*return false when no effect is used!*/
	$("#singleMainContent .no_effect").click(function(){
		return false;
	});

/* jQuery Tools - ToolTip */
	$("#header li.wishlist a[title], #trackingform img[title], .wishList_table a[title], #floatswrap .c_box_p label[title]").tooltip({ 
 
        // use div.tooltip as our tooltip 
        //tip: '#tooltip', 
 
        // use fade effect instead of the default 
        //effect: 'fade', 
 
        // make fadeOutSpeed similar to browser's default 
        fadeOutSpeed: 2000, 
 
        // the time before tooltip is shown 
        predelay: 0, 
 
        // tweak the position 
        position: "bottom center"         
         
    });
	
/* Single Product Page - Adjacent Products */
	$('.adjacentProd').hover(function(){
		var $showME = $(this).find('.adjacentImg');
		$showME.stop(false,true).fadeIn("slow");
	}, function(){
		var $showME = $(this).find('.adjacentImg');
		$showME.stop(false,true).fadeOut("slow");
	});
	
/* wp_list_categories - add class  */
	$('.widget_categories .children .current-cat-parent').parents("li").addClass("current-cat-ancestor");
	
/* Single Product Page - Main Product Image Tabs Add current class  */
	$("#singleMainContent .thumbTabs li:first-child .thumbTab, #singleMainContent .inner_thumbTabs li:first-child .thumbTab").addClass('current');
	$("#singleMainContent .thumbTabs .thumbTab, #singleMainContent .inner_thumbTabs .thumbTab").mouseover(function() {
		$(this).addClass('current').parent().siblings().children().removeClass('current');
	});
	
/*Comment Trackbacks*/
	$("ol.trackback").hide();
		$("a.show_trackbacks").click(function(){
			$("ol.trackback").slideToggle('fast');
			return false;
		});
		
/*DROP DOWN NAVI*/
	function mainmenu(){
		if(!$.browser.msie){// IE  - 2nd level Fix
			$(" #header .hybrid_navi ul ul ").css({opacity:"0.95"});
		}
		$("#header .hybrid_navi ul a").removeAttr('title');
		$(" #header .hybrid_navi ul ul ").css({display: "none"}); // Opera Fix
		
		$(" #header .hybrid_navi ul li").hover(function(){
			$(this).find('ul:first:hidden').slideDown("slow");
		},function(){
			$(this).find('ul:first').slideUp();
		});
	}
	
	mainmenu();
	
/*Form Validation*/	
	$("#signInForm, #quickLoginForm, #editEmail, #editPassword").each(function(i) {
		$(this).validate({
			rules: {
				signInUsername: {
					required: true,
					minlength: 6,
					maxlength: 10
				},
				signInPassword: {
					required: true,
					minlength: 4,
					maxlength: 8
				},
				newEmail: {
					required: true,
					email: true
				},
				rnewEmail: {
					required: true,
					email: true,
					equalTo: "#newEmail"
				},
				newPassword: {
					required: true,
					minlength: 4
				},
				rnewPassword: {
					required: true,
					minlength: 4,
					equalTo: "#newPassword"
				}
			},
				
			messages: {
				signInUsername: {
					required: "Please enter your username.",
					minlength: "Your username must have at least 6 characters!"
				},
				signInPassword: {
					required: "Please enter your password.",
					minlength: "Your password must have at least 4 characters!"
				},
				newEmail: {
					required: "Please enter an email.",
					email: "Please enter a valid email address!"
				},
				rnewEmail: {
					required: "Please enter an email.",
					email: "Please enter a valid email address!",
					equalTo: "Oops! Be sure to type the same email again!"
				},
				newPassword: {
					required: "Please enter a password.",
					minlength: "Your password must have at least 4 characters!"
				},
				rnewPassword: {
					required: "Please enter a password.",
					minlength: "Your password must have at least 4 characters!",
					equalTo: "Oops! Be sure to type the same password again!"
				}
			}
		});			
	});
	
/* Recent Prods slider */
	
	if ($(".prods_scrollable").length != 0) {		
		$('.prods_scrollable').scrollable({mousewheel: true});
	}	
});	

jQuery(window).load(function(){

/* EQUAL HEIGHTS (fire this when everything has loaded for correct height calculation) */
	jQuery.fn.equalHeights = function() {
		var maxheight = 0;
		jQuery(this).children().each(function(){
			maxheight = ( jQuery(this).height() > maxheight) ? jQuery(this).height() : maxheight;
		});
		jQuery(this).children().css('height', 'auto'); // maxheight
	}
	jQuery('#floatswrap .eqcol').equalHeights();

});

/* FONT REPLACEMENT */
Cufon.replace('#singleMainContent .prod-title, .entry-title, .comments_title, .respond_title, .trackback_title', {
hover: true
});

function initialize_hover()
{
	/* Category - Image Hover */
	$('#floatswrap .theCats .contentWrap').hover(function(){
		var $go = $(this).find('.teaser');
		$go.stop().animate({'opacity':'0.8'},{queue:false,duration:500});
	}, function(){
		var $go = $(this).find('.teaser');
		$go.stop().animate({'opacity':'0'},{queue:false,duration:500});
	});

	/* Multiple Product Pages - Image Hover */
	$('#floatswrap .contentWrap').hover(function(){
		var $go = $(this).find('.hover_link');
		$go.stop().animate({opacity:'0'},{queue:false,duration:500});
	}, function(){
		var $go = $(this).find('.hover_link');
		$go.stop().animate({opacity:'1'},{queue:false,duration:500});
	});
}