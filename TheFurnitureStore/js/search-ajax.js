var last_check_box_block        = "";
var default_widget_filter       = '';
var default_products_containter = '';
var busy                        = false;
var cpc                         = 0;

function update_scroll()
{
  jQuery('.f-container').each(function(){
    jQuery(this).mCustomScrollbar('update');
  });

  jQuery('.shop-by-category').each(function(){
    jQuery(this).mCustomScrollbar('update');
  });

  jQuery('.widget-selection .holder').each(function(){
    jQuery(this).mCustomScrollbar('update');
  });

  jQuery('.mCustomScrollbar').each(function(){
    jQuery(this).mCustomScrollbar('update');
  });
}

function hideAllRows(dont_hide_tax)
{
  if(!dont_hide_tax) dont_hide_tax = '';
  jQuery('.f-row').each(function(){
    tax = jQuery(this).attr('data-tax');

    if(typeof(tax) != 'undefined' && tax != dont_hide_tax)
    {
      hideItem(jQuery(this));
    }
  });
}

function hideItem(obj)
{
  var id  = obj.data('id');
  var tax = obj.data('tax');
  obj.addClass('disabled');   
  jQuery('#a-' + tax + '-' + id).addClass('disabled-check');
  jQuery('#' + tax + '-' + id).attr('disabled', true);
}

function showItem(obj)
{
  var id  = obj.data('id');
  var tax = obj.data('tax');
  obj.removeClass('disabled');      
  jQuery('#a-' + tax + '-' + id).removeClass('disabled-check');
  jQuery('#' + tax + '-' + id).removeAttr('disabled');
}

/**
 * Display not null rows
 * @param  json
 */
function displayRows(rows)
{
  if(typeof(rows) == 'undefined') return false;
  if(jQuery('#row-418').hasClass('disabled')) showItem(jQuery('#row-418'));
  if(jQuery('#row-156').hasClass('disabled')) showItem(jQuery('#row-156'));
  
  for(var i in rows)
  {
    for(var x in rows[i])
    {
      if(jQuery('#row-' + x).hasClass('disabled'))
      {
        showItem(jQuery('#row-' + x));
      }
    }
  }
  check_unchecked_alerts();
}

/**
 * Display not null rows
 * @param  json
 */
function displayHiddenRows(rows)
{
  if(typeof(rows) == 'undefined') return false;  
  
  for(var i in rows)
  {
    for(var x in rows[i])
    {
      if(jQuery('#row-' + x).hasClass('disabled'))
      {
        jQuery('#row-' + x).removeClass('disabled');
      }
      jQuery('#row-' + x).find('input').attr('disabled', true);
      jQuery('#row-' + x).find('a').addClass('disabled-jqTransformCheckbox');
      jQuery('#row-' + x).find('label').addClass('disabled');
    }
  }
  check_unchecked_alerts();
}

function checkedToTop()
{
  var tmp;
  jQuery('.search-filter-form input:checkbox:checked').each(function(){
    jQuery(this).parent().parent().parent().prepend(jQuery(this).parent().parent());    
  });
}

function hideNotNeeded()
{
  if(default_categories.taxonomy != 'category' || default_categories.term_id == 129) return;
  jQuery('.f-row').each(function(){     
    if(jQuery(this).data('tax') == default_categories.taxonomy)
    {
      jQuery(this).addClass('hide');
    }
  });

  jQuery('#row-' + default_categories.term_id).removeClass('hide');
  jQuery('#row-418').removeClass('hide');
  jQuery('#row-156').removeClass('hide');
  jQuery('#row-' + default_categories.term_id).find('.f-row').each(function(){ 
    if(jQuery(this).hasClass('hide')) jQuery(this).removeClass('hide');
  });  
}

jQuery(function()
{  
  // =========================================================
  // SAVE DEFAULT CONTENT
  // =========================================================
  default_products_containter = jQuery('#products-container').html();

  if(typeof(default_categories) != 'undefined')
  {     
    hideAllRows('');
    displayRows(default_categories.categories);  
    removeBlockDisables(default_categories.taxonomy);
    hideNotNeeded();
  }
  // =========================================================
  // CLICK TO VIEW
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
  jQuery('#products-ppp strong').click(function(e){
    jQuery(this).parent().find('ul').toggle();
  });
  jQuery('.sort-form label').click(function(e){
    jQuery(this).parent().find('ul.solt-by-values').toggle();
  });
  // =========================================================
  // FILTER RECTANGLE CLICK
  // =========================================================
  jQuery('a.jqTransformCheckbox').click(function(e){    
    var sub_cats          = jQuery(this).parent().parent().find('.sub-category');
    if(typeof(sub_cats) != 'undefined')
    {
      jQuery(this).parent().parent().find('.sub-category').slideToggle(300,function(){
        jQuery(this).parent().parent().toggleClass('open');
        jQuery(this).parents('.shop-by-category').mCustomScrollbar('update');
      });
    }
  });
  
  // =========================================================
  // CHECKBOX CHANGE
  // =========================================================
  jQuery('.search-filter-form input:checkbox').change(function(e) 
  { 
    if(typeof(e.originalEvent) !== "undefined")
    {
      launchFilter(this);
    } 
  });
  // =========================================================
  // SORT CLICK
  // =========================================================
  jQuery('.solt-by-values li a').click(function()
  {
    
    var psv = jQuery(this).attr('href');    
    psv     = psv.replace('#', '');

    change_sort(psv);

    jQuery('.sort-by-current').text(jQuery(this).text());
    
    return false;
  });

  jQuery('#products-ppp ul li a').click(function(){    
    var ppp = jQuery(this).attr('href');
    ppp     = ppp.replace('#view-', '');
    jQuery('.ppp-form .ppp-val').val(ppp);      
    jQuery('#products-ppp .ppp-curr').html('View '+ppp);

    jQuery('#products-ppp ul li').each(function(){
      jQuery(this).removeClass('active');
    });
    jQuery(this).parent().addClass('active');

    change_ppp(ppp);
    return false;
  });

  var hash = window.location.hash.replace("#!", "");
  if( hash != "")
  {
    search_ajax_by_hash(hash);
  }

  var hash = hash.split('&');
  var myArray = new Array();
  for (var x = 0; x < hash.length; x++) 
  {
      var itemArray = hash[x].split('=');
      var item = new Object();
      item.key = itemArray[0];
      item.value = itemArray[1];
      myArray.push(item);
  }
  
  for (var x = 0; x < myArray.length; x++)
  {    
    if(myArray[x].key.indexOf('tax_query%5B0%5D%5Bterms') > -1)
    {     
      jQuery('.search-filter-form input[type=checkbox]').each(function(){
        if(jQuery(this).val() == myArray[x].value)
        {
          jQuery(this).parent().find('a').addClass('jqTransformChecked');
          jQuery(this).checked = true;
        }
      });
    }
  }  
  init_alerts_action();

  jQuery('.search-filter-form').find('.f-row').each(function(){
    jQuery(this).removeClass('open');
  });

  jQuery('.search-filter-form').find('.f-block').each(function(){
    jQuery(this).removeClass('open');
  });

  jQuery('.search-filter-form').find("input:checkbox:checked").each(function(){
    jQuery(this).parents('.f-row').addClass('open');
    jQuery(this).parents('.f-block').addClass('open');   
  });

  if(jQuery('#is_open_brands').val() == "yes")
  {
    jQuery('.shop-by-brand').addClass('open');
  }
  
  update_scroll();

  jQuery('.shop-by-category').addClass('open');   
});

function launchFilter(obj)
{  
  obj                   = jQuery(obj);
  last_check_box_block  = obj.data('block');     
  var v                 = [];  
  var all               = [];
  var dont_remove_names = [];  
  var filter            = [];  

  if(obj.parent().parent().hasClass('disabled')) return;  
  setTimeout(function(){ busy = false }, 1000);

  if(busy) return; 
  
  jQuery('.search-filter-form input:checkbox:checked').each(function(){ all.push([jQuery(this).attr("name").replace('[]', ''), jQuery(this).val()]); });
  jQuery('.search-filter-form input:checkbox:checked:enabled').each(function(){      
    if(jQuery(this).data('block') != last_check_box_block)
    {
      if(!jQuery(this).parent().parent().hasClass('disabled'))
      {
        if(jQuery(this).attr('disabled') != 'disabled') v.push([jQuery(this).attr("name").replace('[]', ''), jQuery(this).val()]); 
      } 
    }
  });

  obj.parent().parent().parent().find('input:checkbox:checked').each(function(){ all.push([jQuery(this).attr("name").replace('[]', ''), jQuery(this).val()]);  });
  obj.parent().parent().parent().find('input:checkbox:checked:enabled').each(function(){
    if(!jQuery(this).parent().parent().hasClass('disabled'))
    {
      if(jQuery(this).attr('disabled') != 'disabled') v.push([jQuery(this).attr("name").replace('[]', ''), jQuery(this).val()]);
    }
  });

  jQuery('.' + last_check_box_block + ' input:checkbox:checked').each(function(){ filter.push([jQuery(this).attr("name").replace('[]', ''), jQuery(this).val()]); });
  if(typeof(cat_in_search) != 'undefined') 
  {    
    v = v.concat(cat_in_search);
  }
  search_ajax(v, all, filter, obj.parent().parent().attr('data-tax'));     

  if(jQuery('.search-filter-form input:checkbox:checked').length == 0)
  {
    jQuery('.search-filter-form').find('.f-row').each(function(){
      if(jQuery(this).hasClass('hide')) jQuery(this).removeClass('hide');
    });
  }
  busy = true;
}

/**
 * Get ajax page
 */
function get_page(paged)
{
  jQuery("html, body").animate({ scrollTop: 0 }, "slow");
  jQuery.ajax(
    {
      type: "POST",
      dataType: 'html',
      url: "/wp-admin/admin-ajax.php?action=change_page",
      data: {"paged": paged},                                     
      success: function(response)
      {
        jQuery('#products-container').html(response);
      },
      beforeSend: function() {
        jQuery('#products-container').addClass('loading');
        jQuery('body').append("<div class='cancel-all-actions'></div>");
      },
      complete: function()
      {
        jQuery('#products-container').removeClass('loading');
        jQuery('.cancel-all-actions').remove();

          
        update_scroll();
        change_currency();
      }
    });
}

function change_ppp(ppp)
{
  jQuery.ajax(
    {
      type: "POST",
      dataType: 'html',
      url: "/wp-admin/admin-ajax.php?action=change_ppp",
      data: {"ppp": ppp},                                     
      success: function(response)
      {
        jQuery('#products-container').html(response);        
      },
      beforeSend: function() {
        jQuery('#products-container').addClass('loading');
        jQuery('body').append("<div class='cancel-all-actions'></div>");
      },
      complete: function()
      {
        jQuery('#products-container').removeClass('loading');
        jQuery('.cancel-all-actions').remove();
            
        update_scroll();
        change_currency();   
      }
    });
}

function get_default_content()
{  
  jQuery('#products-container').html(default_products_containter);
  hideAllRows('');
  displayRows(default_categories.categories);
  update_scroll();
  change_currency();   
  location.hash = '';
  

}

function change_sort(psort)
{
  jQuery.ajax(
    {
      type: "POST",
      dataType: 'html',
      url: "/wp-admin/admin-ajax.php?action=change_sort",
      data: {"psort": psort},                                     
      success: function(response)
      {
        jQuery('#products-container').html(response);
      },
      beforeSend: function() {
        jQuery('#products-container').addClass('loading');
        jQuery('body').append("<div class='cancel-all-actions'></div>");
      },
      complete: function()
      {
        jQuery('#products-container').removeClass('loading');
        jQuery('.cancel-all-actions').remove();
            
        update_scroll();
        change_currency();   
      }
    });
}
function get_latest_products(obj)
{  
  if (!isloggedin) {
    var ahref = jQuery(obj).attr('href');
    show_login_popup('sln', ahref);
    return false;
  }
  
  jQuery.ajax(
  {
    type: "POST",
    dataType: 'html',
    url: "/wp-admin/admin-ajax.php?action=get_latest_products",
    success: function(response)
    {
      jQuery('#products-container').html(response);
    },
    beforeSend: function() {
      jQuery('#products-container').addClass('loading');
      jQuery('body').append("<div class='cancel-all-actions'></div>");
    },
    complete: function()
    {
      jQuery('#products-container').removeClass('loading');
      jQuery('.cancel-all-actions').remove();
           
      update_scroll();
      change_currency();
    }
  });
}
/**
 * AJAX search
 */
function search_ajax(search, all, filter, checked_block)
{ 
  console.log(search);
  jQuery('#products-container').removeClass('loading');
  if(typeof(window.search_ajax_req) != 'undefined') window.search_ajax_req.abort();
  window.search_ajax_req = jQuery.ajax(
  {
    type: "POST",
    dataType: 'json',
    url: "/wp-admin/admin-ajax.php?action=search_products",
    data: {"search": search, all: all, filter: filter},                                     
    success: function(response)
    {      
      jQuery('#products-container').html(response.loop);
      setLocation(response.args);
      hideAllRows('');
      displayRows(response.categories);
      displayHiddenRows(response.hidden_terms);
    },
    beforeSend: function() {           
      removeDisables();
      jQuery('#products-container').addClass('loading');
      //jQuery('body').append("<div class='cancel-all-actions'></div>");
    },
    complete: function()
    {
      jQuery('#products-container').removeClass('loading');
      // jQuery('.cancel-all-actions').remove();            
      change_currency();
          
       
      checkedToTop();
      update_scroll();
    }
  });
}

function removeDisables()
{
  jQuery('.search-filter-form').find('.disabled').each(function(){ jQuery(this).removeClass('disabled'); });
  jQuery('.search-filter-form').find('.disabled-jqTransformCheckbox').each(function(){ jQuery(this).removeClass('disabled-jqTransformCheckbox'); });
  jQuery('.search-filter-form').find('input:checkbox:disabled').each(function(){ jQuery(this).attr('disabled', false); });
}

function removeBlockDisables(block)
{
  jQuery('.shop-by-' + block).find('.disabled').each(function(){ jQuery(this).removeClass('disabled'); });
  jQuery('.shop-by-' + block).find('.disabled-jqTransformCheckbox').each(function(){ jQuery(this).removeClass('disabled-jqTransformCheckbox'); });
  jQuery('.shop-by-' + block).find('.disabled-check').each(function(){ jQuery(this).removeClass('disabled-check'); });
  jQuery('.shop-by-' + block).find('input:checkbox:disabled').each(function(){ jQuery(this).attr('disabled', false); });
}

/**
 * AJAX search by hash
 */
function search_ajax_by_hash(search)
{
  var articles = "";
  var x        = 0;
  jQuery.ajax(
  {
    type: "POST",
    dataType: 'html',
    url: "/wp-admin/admin-ajax.php?action=search_ajax_by_hash",
    data: {"search_hash": search},                                     
    success: function(response)
    {
      jQuery('#products-container').html(response);
    },
    beforeSend: function() {
      jQuery('#products-container').addClass('loading');
      jQuery('body').append("<div class='cancel-all-actions'></div>");
    },
    complete: function()
    {
      jQuery('#products-container').removeClass('loading');
      jQuery('.cancel-all-actions').remove();  
      update_scroll();
      change_currency();
    }
  });
}

function setLocation(curLoc)
{ 
  location.hash = '#!' + curLoc;
}
