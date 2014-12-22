var filter = new Object();
var search_list = new Object();
var last_args = {};

filter.tmp = 1;

/**
 * Disable one term item
 * @param  integer id --- item id
 */
filter.diableItem = function(id){
	var input        = jQuery('#row-'+ id + ' > span > input');
	var check_square = jQuery('#row-'+ id + ' > span > a');
	var label        = jQuery('#row-'+ id + ' > label');

	input.prop('disabled', true);
	if(input.is(':checked'))
	{
		if(!check_square.hasClass('disabled-jqTransformCheckbox'));
		{
			check_square.addClass('disabled-jqTransformCheckbox');
		}
	}
	else
	{
		if(!check_square.hasClass('disabled-check')) 
		{
			check_square.addClass('disabled-check');
		}	
	}
	

	if(!label.hasClass('disabled')) 
	{
		label.addClass('disabled');
	}
};

filter.enableItem = function(id){
	var input        = jQuery('#row-'+ id + ' > span > input');
	var check_square = jQuery('#row-'+ id + ' > span > a');
	var label        = jQuery('#row-'+ id + ' > label');
	if(input.hasClass('frozen')) return false;
	input.prop('disabled', false);
	check_square.removeClass('disabled-check');
	check_square.removeClass('disabled-jqTransformCheckbox');
	label.removeClass('disabled');
};

filter.disableAll = function(exclude_block){
	if(typeof (exclude_block) == 'undefined') exclude_block = '';
	var block = '';
	jQuery('.f-row').each(function(){
		block = jQuery(this).children('span').find('input').data('block');
		if(block != exclude_block && typeof(block) != 'undefined')
		{
			filter.diableItem(jQuery(this).attr('id').replace('row-', ''));	
		}
	});
};

filter.enableItems = function(items){
	if(typeof(items) != 'undefined')
	{
		for (var i = 0; i < items.length; i++) 
		{
			filter.enableItem(items[i]);
		}	
	}
};

filter.visibleNeeded = function(items, exclude_block){
	filter.disableAll(exclude_block);
	filter.enableItems(items);
};

filter.removeNotNeeded = function(rows_selector, visible_terms)
{
	var id = 0;
	jQuery(rows_selector).each(function(){
		id = jQuery(this).attr('id').replace('row-', '');
		id = parseInt(id);
		if(!visible_terms.inArray(id))
		{
			jQuery('#row-' + id).remove();
		}
	});
};

filter.sort = function(key){
	var sort = {
		newest: {column: 'post_date', type: 'DESC'},
		oldest: {column: 'post_date', type: 'ASC'},
		pricelow: {column: 'sort_price', type: 'ASC'},
		pricehigh: {column: 'sort_price', type: 'DESC'}
	};
	last_args.order_by_col  = sort[key].column;
	last_args.order_by_type = sort[key].type;
	last_args.cats          = filter.getCheckedData();

	filter.filterAJAX(null, null);
};

filter.filter = function(event, obj){
	if(jQuery(obj).hasClass('frozen')) return false;
	if(jQuery(obj).is(':disabled')) return false;
	last_args.offset = 0;
	last_args.wnew = jQuery('.whats-new-pg').html();
	last_args.s = jQuery('.search-form #s').val();
	last_args.cats = filter.getCheckedData();
	filter.filterAJAX(event, obj);
	filter.updateScrollBars();
	init_alerts_action();
};

filter.updateScrollBars = function(){
	jQuery('.f-block .f-container, .shop-by-category, .widget-selection .holder, .mCustomScrollbar').each(function(){
	  jQuery(this).mCustomScrollbar('update');
	});
};

filter.getPage = function(event){
	jQuery("html, body").animate({ scrollTop: 0 }, "slow");
	var page = parseInt(jQuery(event.target).data('page').replace('#', ''));
	last_args.offset = page*last_args.count-last_args.count;
	last_args.cats = filter.getCheckedData();
	filter.filterAJAX(event, null);
	event.preventDefault();
};

filter.filterHash = function(hash){
	last_args = hash;
	filter.filterAJAX(null, null);
};

filter.filterAJAX = function(event, obj){
	filter.tmp++;
	if(typeof(filter.search_ajax_req) != 'undefined') filter.search_ajax_req.abort();
	filter.search_ajax_req = jQuery.ajax(
	{
		type: "POST",
		dataType: 'json',
		url: "/wp-admin/admin-ajax.php",
		data: {
			action: 'getProducts',
			args: last_args
		}, 
		beforeSend: function() {           
			jQuery('#products-container').addClass('loading');      
		},                                    
		success: function(response)
		{      	
			jQuery('#products-container').html(response.html);
			last_args = response.last_args;
			filter.visibleNeeded(response.visible_terms, jQuery(obj).data('block'));
			filter.setLocation(last_args);
			filter.imageLoad();
			filter.paginationInit();
		},
		complete: function()
		{
			jQuery('#products-container').removeClass('loading');
			filter.updateItemsEffects();
			change_currency();
			init_alerts_action();
		}
	});
};

filter.removeEmptyArgs = function(args){
	for(var cat in args.cats)
	{
		if(args.cats[cat] == '') delete args.cats[cat];
	}
	return args;
};

filter.param = function(args){
	args = filter.removeEmptyArgs(args);
	return jQuery.param(args);
};

filter.unparam = function(p){
    var params = {};
    var pairs = p.split('&');
    for (var i=0; i<pairs.length; i++) 
    {
        var pair = pairs[i].split('=');
        var accessors = [];
        var name = decodeURIComponent(pair[0]), value = decodeURIComponent(pair[1]);

        var name = name.replace(/\[([^\]]*)\]/g, function(k, acc) { accessors.push(acc); return ""; });
        accessors.unshift(name);
        var o = params;

        for (var j=0; j<accessors.length-1; j++) 
        {
            var acc = accessors[j];
            var nextAcc = accessors[j+1];
            if (!o[acc]) {
                if ((nextAcc == "") || (/^[0-9]+$/.test(nextAcc)))
                    o[acc] = [];
                else
                    o[acc] = {};
            }
            o = o[acc];
        }
        acc = accessors[accessors.length-1];
        if (acc == "")
            o.push(value);
        else
            o[acc] = value;
    }
    return params;
};

filter.setLocation = function(args){
	location.hash = '#!' + filter.param(args);
};

/**
 * Get all checked filter data
 */
filter.getCheckedData = function(){

	var tax_cat = {
		cat_men_1:           [],
		cat_men_2:           [],
		cat_men_3:           [],
		cat_men_4:           [],
		cat_men_5:           [],
		cat_women_1:         [],
		cat_women_2:         [],
		cat_women_3:         [],
		cat_women_4:         [],
		cat_women_5:         [],
		tax_sale:            [],
		tax_colours:         [], 
		tax_sizes:           [],
		tax_ring_sizes:      [],
		tax_clothes_sizes:   [],
		tax_selections:      [],
		tax_brands:          [],
		tax_prices:          [],
		tax_seller_category: [],
		tag:                 [],
	};

	var blocks = {
		0: ['shop-by-brand', 'tax_brands', 'brand-'],
		1: ['shop-by-colour', 'tax_colours', 'colour-'],
		2: ['shop-by-price', 'tax_prices', 'price-'],
		3: ['shop-by-selection', 'tax_selections', 'selection-'],
		4: ['shop-by-size', 'tax_sizes', 'size-'],
		5: ['shop-by-ring-size', 'tax_ring_sizes', 'ring-size-'],
		6: ['shop-by-clothes-size', 'tax_clothes_sizes', 'clothes-size-']
	};

	var tax_key       = 'tax_cat_';
	var block_class   = '';
	var block_tax     = '';
	var block_replace = '';
	
	if(last_args.cats) {
		if(typeof(last_args.cats.tag) != 'undefined')
		{
			if(!isNaN(parseInt(last_args.cats.tag)))
			{
				tax_cat['tag'].push(parseInt(last_args.cats.tag));
			}
		}

		if(typeof(last_args.cats.tax_cat_1) != 'undefined')
		{
			tax_cat['tax_cat_1'] = [];
			tax_cat['tax_cat_1'].push(last_args.cats.tax_cat_1);
		}
	}
	// ==============================================================
	// Categories
	// ==============================================================
	jQuery('.shop-by-category input:checked').each(function(){
		tax_key = 'cat_' + jQuery(this).data('sex') + '_' + (1 + parseInt(jQuery(this).data('depth')));
		tax_cat[tax_key].push(jQuery(this).attr('id').replace('category-', ''));
	});
	if (jQuery('.sale-category-pg').size()) {
		var scd = jQuery('.sale-category-pg').html().split(';');
		tax_cat['tax_sale'].push(scd[0]);
	}

	for (var i = 0; i < Object.keys(blocks).length; i++) 
	{
		block_class = '.' + blocks[i][0] + ' input:checked';
		block_tax   = blocks[i][1];
		block_replace = blocks[i][2];

		jQuery(block_class).each(function(){
			tax_cat[block_tax].push(jQuery(this).attr('id').replace(block_replace, ''));
		});
	}
	for(var key in tax_cat)
	{
		tax_cat[key] = tax_cat[key].join(',');
	}
	return tax_cat;
};

filter.getHash = function(){
	return window.location.hash.replace("#!", "");
};

filter.loadFromHash = function(){
	if(filter.getHash() != '')
	{
		filter.filterHash(filter.unparam(filter.getHash()));
		filter.restoreSelects();
		filter.restoreSortBy();
		filter.restoreView();
		alertslogin_action(); 
	}
};

filter.restoreSelects = function(){
	var ids = [];
	for(var key in last_args.cats)
	{
		ids = ids.concat(last_args.cats[key].split(','));
	}
	
	for (var i = 0; i < ids.length; i++) 
	{
		jQuery('#row-' + ids[i] + ' > span > input').prop("checked", true); 
		jQuery('#row-' + ids[i] + ' > span > a').addClass('jqTransformChecked');
	}

	init_alerts_action();
	alertslogin_action();

	jQuery('.search-filter-form input:checkbox:checked').each(function(){    
		jQuery(this).parent().parent().parent().prepend(jQuery(this).parent().parent());    
	});
};

filter.restoreSortBy = function(){
	var sort = {
		post_date: {
			DESC: 'newest',
			ASC : 'oldest'
		},
		sort_price: {
			DESC: 'pricehigh',
			ASC: 'pricelow'
		}
	};

	if(typeof(sort[last_args.order_by_col][last_args.order_by_type]) != 'undefined')
	{
		var key = sort[last_args.order_by_col][last_args.order_by_type];
		var title = filter.sortByLabels[key];
		jQuery('.sort-row .sort-by-current').text(title);	
	}
};

filter.restoreView = function(){
	var count = 0;
	jQuery('#products-ppp ul li a').each(function(){
		count = parseInt(jQuery(this).attr('href').replace('#view-', ''));
		if(last_args.count == count) 
		{
			jQuery(this).parent().addClass('active');
		}
	});
};

filter.updateItemsEffects = function(){
	// ==============================================================
	// HOVER EFFECT
	// ==============================================================
	jQuery('#floatswrap .contentWrap').hover(function(){
		var $go = jQuery(this).find('.hover_link');
		$go.stop().animate({opacity:'0'},{queue:false,duration:500});
	}, function(){
		var $go = jQuery(this).find('.hover_link');
		$go.stop().animate({opacity:'1'},{queue:false,duration:500});
	});

	jQuery('#floatswrap .contentWrap').hover(function(){
		var $go = jQuery(this).find('.hover_link');
		$go.stop().animate({opacity:'0'},{queue:false,duration:500});
	}, function(){
		var $go = jQuery(this).find('.hover_link');
		$go.stop().animate({opacity:'1'},{queue:false,duration:500});
	});
}

filter.imageLoad = function(){
	jQuery('.image-reload').each(function(){
		if(filter.inView(this))
		{
			jQuery(this).attr('src', jQuery(this).data('original'));
		}
	});
};

filter.inView = function(a) {
	var st = (document.documentElement.scrollTop || document.body.scrollTop);
	var ot = jQuery(a).offset().top;
	var wh = (window.innerHeight && window.innerHeight < jQuery(window).height()) ? window.innerHeight : jQuery(window).height();

	return ot < (st + wh);
}

filter.paginationInit = function(){
	var re_hash = /#.*/,
		url = location.href.replace(re_hash, ''),
		page, link, args;

	if(location.hash == '')
	{
		last_args.wnew = jQuery('.whats-new-pg').html();
		last_args.s = jQuery('.search-form #s').val();
		last_args.cats = filter.getCheckedData();
	}
	args = last_args;

	jQuery('.wp-pagenavi a').each(function(){
		page = jQuery(this).data('page').replace('#', '');
		args.offset = (parseInt(page)-1) * parseInt(last_args.count);
		link = url + '#!' + filter.param(args);
		jQuery(this).attr('href', link);
	});	
};

filter.sortByLabels = {
	newest: 'Newest',
	oldest: 'Oldest',
	pricelow: 'Price Low to High',
	pricehigh: 'Price High to Low'
};
// ==============================================================
// Search List
// ==============================================================
search_list.search = function(obj){
	var value  = jQuery(obj).val();
	var block  = '.'+jQuery(obj).data('block');
	var labels = {};
	var re = new RegExp(value, "i");
	
	jQuery(block).find('.f-row > label').each(function(){
		if(jQuery(this).parent().hasClass("hide"))
			jQuery(this).parent().removeClass("hide")
		labels[jQuery(this).parent().attr('id')] = jQuery(this).text();
	});
	if(value.length > 0)
	{
		for (var key in labels) 
		{
			if(labels[key].search(re) == -1)
			{
				jQuery('#' + key).addClass('hide');
			}
		}	
		jQuery(obj).parent().children('button').addClass('remove-search');
	}
	else
	{
		jQuery(obj).parent().children('button').removeClass('remove-search');
	}
	filter.updateScrollBars();
};

search_list.searchButton = function(event){
	var input = jQuery(event.target).parent().children('input');
	input.val('');
	search_list.search(input);
};
// ==============================================================
// OTHER METHODS
// ==============================================================

/**
 * Add in_arra to Array object
 * @param  string p_val --- needle
 * @return boolean
 */
Array.prototype.inArray = function(p_val) {
	for(var i = 0, l = this.length; i < l; i++)	
	{
		if(this[i] == p_val) 
		{
			return true;
		}
	}
	return false;
};


function changeCurrency()
{
	var current = jQuery('#currencySelect .current .opacity-fader').text().toLowerCase();

	jQuery('.f-row').each(function(){
		if(jQuery(this).data('tax') == 'price')
		{
			jQuery(this).find('label').text(jQuery(this).find('label').data(current) + ' ' + current.toUpperCase());
		}
	});
}


function launchFilter(obj)
{
	filter.filter(null, obj);
}

function hasDrop(event, obj)
{
	jQuery(obj).next('.sub-category').slideToggle(300, function(){
		jQuery(obj).parent().toggleClass('open');
		jQuery(obj).parents('.shop-by-category').mCustomScrollbar('update');
	}); 
	event.preventDefault();
}

jQuery(document).ready(function(){
	change_currency();
	if(typeof(visible_terms) != 'undefined')
	{
		filter.visibleNeeded(visible_terms);
		filter.removeNotNeeded('.shop-by-brand .f-row', visible_terms);	
	}
	filter.loadFromHash();
	filter.updateItemsEffects();
	filter.paginationInit();
	// =========================================================
	// Drop down controls
	// =========================================================
	jQuery('#products-ppp > ul > li > a').click(function(e){
		jQuery('#products-ppp ul').hide();
	});
	jQuery('.solt-by-values > li > a').click(function(e){
		jQuery('ul.solt-by-values').hide();
	});
	jQuery(document).click(function(e){       
		if(e.target.className != 'ppp-curr')
		{
			jQuery('#products-ppp ul').hide();
		}   
		if(e.target.className != 'sort-by-label')
		{
			jQuery('ul.solt-by-values').hide();
		}
	});
	jQuery('#products-ppp strong, .sort-form label').click(function(e){    
		jQuery(this).parent().find('ul').show();
	});
	// ==============================================================
	// Click to change View
	// ==============================================================
	jQuery('#products-ppp ul li a').click(function(e){
		jQuery('#products-ppp ul li').each(function(){ jQuery(this).removeClass('active'); });
		jQuery(this).parent().addClass('active');
		last_args.count = jQuery(this).attr('href').replace('#view-', '');
		last_args.cats  = filter.getCheckedData();
		filter.filterAJAX(e, null);

		e.preventDefault();
	});
	// ==============================================================
	// Click to change Sort
	// ==============================================================
	jQuery('.sort-row ul.solt-by-values li a').click(function(e){
		var value = jQuery(this).attr('href').replace('#', '');
		jQuery(this).parent().parent().prev('.sort-by-current').text(filter.sortByLabels[value]);
		filter.sort(value);
		e.preventDefault();
	});
	// ==============================================================
	// Image load
	// ==============================================================
	filter.imageLoad();
	jQuery(window).scroll(function(){
		filter.imageLoad();
	});
	// ==============================================================
	// Brand search lists hide/show
	// ==============================================================
	jQuery('.shop-by-brand h4').click(function(){
		jQuery('.shop-by-brand .checkbox-list-search').toggle();
	});
	// ==============================================================
	// Click to filter check rectangle
	// ==============================================================
	jQuery('.f-row .jqTransformCheckboxWrapper .jqTransformCheckbox').click(function(e){
		var sub_category = jQuery(this).parent().parent().children('.sub-category');
		sub_category.slideToggle(300, function(){
			jQuery(this).parent().toggleClass('open');
			jQuery(this).parents('.shop-by-category').mCustomScrollbar('update');
		}); 
	});
});