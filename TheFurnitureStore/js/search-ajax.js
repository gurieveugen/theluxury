var last_check_box_block        = "";
var default_widget_filter       = '';
var default_products_containter = '';

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
      jQuery(this).addClass('hide');      
    }
  });
}

/**
 * Display not null rows
 * @param  json
 */
function displayRows(rows)
{
  if(typeof(rows) == 'undefined') return false;
  if(jQuery('#row-266').hasClass('hide')) jQuery('#row-266').removeClass('hide');
  if(jQuery('#row-156').hasClass('hide')) jQuery('#row-156').removeClass('hide');
  
  for(var i in rows)
  {
    for(var x in rows[i])
    {
      if(jQuery('#row-' + x).hasClass('hide'))
      {
        jQuery('#row-' + x).removeClass('hide');
      }
    }
  }
  check_unchecked_alerts();
}

function showBlock(block)
{
  if(typeof(block) == 'undefined') return false;
  jQuery('.f-row').each(function(){
    if(jQuery(this).attr('data-tax') == block)
    {
      if(jQuery(this).hasClass('hide'))
      {
        jQuery(this).removeClass('hide');
        jQuery('.search-filter-form input[type=checkbox]').change();
      }
    }
  });
}

jQuery(function()
{
  default_products_containter = jQuery('#products-container').html();
  if(typeof(default_categories) != 'undefined')
  {
    displayRows(default_categories.categories);  
    showBlock(default_categories.taxonomy);  
  }

  jQuery('.search-filter-form input[type=checkbox]').change(function(e) 
  {    
    if(typeof(e.originalEvent) !== "undefined")
    {
      launchFilter(this);
    } 
  });

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
  var dont_remove_names = [];  

  if(jQuery('.search-filter-form input:checkbox:checked').length > 0)
  {
    jQuery('.search-filter-form input:checkbox:checked').each(function(){
      if(jQuery(this).data('block') != last_check_box_block)
      {
        if(!jQuery(this).parent().parent().hasClass('hide'))
        {
          v.push([jQuery(this).attr("name").replace('[]', ''), jQuery(this).val()]);    
        }
      }
    });

    obj.parent().parent().parent().find('input:checkbox:checked').each(function(){
      if(!jQuery(this).parent().parent().hasClass('hide'))
      {
        v.push([jQuery(this).attr("name").replace('[]', ''), jQuery(this).val()]);
      }
    });
    search_ajax(v, obj.parent().parent().attr('data-tax')); 

   
  }
  else
  {
    get_default_content();
  }
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
  hideAllRows(default_categories.taxonomy);
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
function get_latest_products()
{
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
function search_ajax(search, checked_block)
{ 
  var not_hide = [];
  jQuery('.search-filter-form').find('input:checkbox:checked').each(function(){
    not_hide.push(jQuery(this).attr("id")); 
  });

  jQuery.ajax(
  {
    type: "POST",
    dataType: 'json',
    url: "/wp-admin/admin-ajax.php?action=search_products",
    data: {"search": search},                                     
    success: function(response)
    {      
      jQuery('#products-container').html(response.loop);
      setLocation(response.args);
      hideAllRows(checked_block);
      displayRows(response.categories);
    },
    beforeSend: function() {
      removeDisables();
      jQuery('#products-container').addClass('loading');
      jQuery('body').append("<div class='cancel-all-actions'></div>");
    },
    complete: function()
    {
      jQuery('#products-container').removeClass('loading');
      jQuery('.cancel-all-actions').remove();      
      update_scroll();
      change_currency();

      for (var i = 0; i < not_hide.length; i++) 
      {
        if(jQuery('#' + not_hide[i]).parent().parent().hasClass('hide'))
        {
          jQuery('#' + not_hide[i]).parent().parent().removeClass('hide');  
          jQuery('#' + not_hide[i]).parent().parent().find('input').attr('disabled', true);
          jQuery('#' + not_hide[i]).parent().parent().find('a').addClass('disabled-jqTransformCheckbox');
          jQuery('#' + not_hide[i]).parent().parent().find('label').addClass('disabled');
        }
      }
    }
  });
}

function removeDisables()
{
  jQuery('.search-filter-form').find('.disabled').each(function(){ jQuery(this).removeClass('disabled'); });
  jQuery('.search-filter-form').find('.disabled-jqTransformCheckbox').each(function(){ jQuery(this).removeClass('disabled-jqTransformCheckbox'); });
  jQuery('.search-filter-form').find('input:checkbox:disabled').each(function(){ jQuery(this).attr('disabled', false); });
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
            get_display_categories();      
      update_scroll();
      change_currency();
    }
  });
}

function setLocation(curLoc)
{ 
  location.hash = '#!' + curLoc;
}

function arraysEqual(a, b) 
{
  if (a === b) return true;
  if (a == null || b == null) return false;
  if (a.length != b.length) return false;

  // If you don't care about the order of the elements inside
  // the array, you should sort both arrays here.

  for (var i = 0; i < a.length; ++i) {
    if (a[i] !== b[i]) return false;
  }
  return true;
}