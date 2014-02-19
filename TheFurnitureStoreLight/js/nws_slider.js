(function($) {

    $.fn.mySlider   = function(vars) {
    
        var timeOut     = vars.timeOut || 4000;
        var capOpacity  = vars.captionOpacity || .7;
        var element     = this;
        var fxDuration  = timeOut/6;
        
        var items       = $("#" + element[0].id + " li");
        var captions    = $("#" + element[0].id + " li div");
        
        items.css('display','none');
        
        captions.css({
            'opacity': capOpacity,
            'display': 'none'
        });
        
        
        var fadeIn      = function(no) {
            $(items[no]).fadeIn(fxDuration, function() {
                $(captions[no]).fadeIn(fxDuration, function() {
                    setTimeout(function() {fadeOut(no);}, timeOut);
                });
            });
        }
        
        var fadeOut     = function(no) {
            $(captions[no]).fadeOut(fxDuration, function() {
                $(items[no]).fadeOut(fxDuration, function() {
                    fadeIn(calcNext(no));
                });
            });
        }
        
        var calcNext    = function(no) {
            return ((no + 1) == items.length) ? 0 : (no + 1);
        }
        
        fadeIn(0);
    
    
    }


})(jQuery);