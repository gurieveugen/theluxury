jQuery(function ($) {

/*jQzoom*/
	var options = {
	    zoomWidth: 535,
	    zoomHeight: 250,
        xOffset: 20,
        yOffset: 0,
        position: 'right',
		showEffect: 'fadein',
		hideEffect:'fadeout',
		fadeinSpeed:'slow',
		fadeoutSpeed:'slow'
	};
	
	$('#singleMainContent .jqZoom').jqzoom(options);


});