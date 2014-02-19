jQuery(function ($) {
$.fn.cycle.updateActivePagerLink = function(pager, currSlideIndex) { 
	    $(pager).find('li').removeClass('active_slide') 
	        .filter('li:eq('+currSlideIndex+')').addClass('active_slide'); 
	}; 
	
	$('#cycle').after('<ul id="featuredNavi" class="clearfix">').cycle({ 
	    fx:			'scrollLeft', // change the effect here. For your options please see: http://malsup.com/jquery/cycle/anchor.html Why only these options are available is explained under the "Limitations" heading.
		timeout: 	4000, //adjust this if you want each slider to stay on longer or less. Set it 0 for manual transitions
		pager:  	'#featuredNavi',
		pagerAnchorBuilder: function(idx, slide) { 
			var src = $('img',slide).attr('src'); 
	        return '<li><a href="#"><img src="' + src + '" width="60" height="40" /></a></li>'; //adjust the width and height according to your image size. Keeping your images in same dimmensions is helpful.
	    } 
	});
	
});