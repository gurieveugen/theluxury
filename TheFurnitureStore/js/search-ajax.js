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
      jQuery(this).find('input:checkbox:checked').each(function(){
        jQuery(this).attr('checked', false);         
        jQuery(this).parent().find('a').removeClass('jqTransformChecked');
      });
    }
  });
}

/**
 * Display not null rows
 * @param  json
 */
function displayRows(rows)
{
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
  displayRows(default_categories.categories);  
  showBlock(default_categories.taxonomy);

  jQuery('.search-filter-form input[type=checkbox]').change(function(e) 
  {
    last_check_box_block = jQuery(this).data('block');     
    if(typeof(e.originalEvent) !== "undefined")
    {
      var v                 = [];  
      var dont_remove_names = [];    

      if(jQuery(this).parent().parent().parent().find('input:checkbox:checked').length > 0)
      {
        jQuery(this).parent().parent().parent().find('input:checkbox:checked').each(function(){
           v.push([jQuery(this).attr("name").replace('[]', ''), jQuery(this).val()]);        
           dont_remove_names.push(jQuery(this).attr("id"));
        });
        search_ajax(v, jQuery(this).parent().parent().attr('data-tax')); 
      }
      else
      {
        get_default_content();
      }
      
      
      jQuery('.search-filter-form').find('input:checkbox:checked').each(function(){
        if(dont_remove_names.indexOf(jQuery(this).attr('id')) < 0 )
        {          
          jQuery(this).attr('checked', false);         
          jQuery(this).parent().find('a').removeClass('jqTransformChecked');
        }
      }); 
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