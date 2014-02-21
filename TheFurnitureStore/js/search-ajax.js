var last_check_box_block = "";

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

jQuery(function()
{
  jQuery('.search-filter-form input[type=checkbox]').change(function(e) 
  {
    last_check_box_block = jQuery(this).data('block'); 

    if(typeof(e.originalEvent) !== "undefined")
    {
      var v = [];
      if(jQuery('.search-filter-form').find("input:checkbox:checked").length > 0)
      {
        jQuery('.search-filter-form').find("input:checkbox:checked").each(function()
        {
          v.push([jQuery(this).attr("name").replace('[]', ''), jQuery(this).val()]);
        });
        search_ajax(v);        
      }
      else
      {
        get_default_content();
      }
      return false;   
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
  
  // get_display_categories();
  update_scroll();

  jQuery('.shop-by-category').addClass('open');  
});

/**
 * Get ajax page
 */
function get_page(paged)
{
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

        get_last_arg();   
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
        get_last_arg();     
        update_scroll();
        change_currency();   
      }
    });
}

function get_default_content()
{
  jQuery.ajax(
    {
      type: "POST",
      dataType: 'html',
      url: "/wp-admin/admin-ajax.php?action=get_default_content",
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
        get_last_arg();       

        jQuery('.disabled-jqTransformCheckbox').each(function(){
          jQuery(this).removeClass('disabled-jqTransformCheckbox');
        });

        jQuery('.disabled').each(function(){
          jQuery(this).removeClass('disabled');
        });

        jQuery('.search-filter-form').find("input").each(function(){
          jQuery(this).removeAttr('disabled');
        });
        update_scroll();
        change_currency();
      }
    });
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
        get_last_arg();     
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
      get_last_arg();      
      update_scroll();
      change_currency();
    }
  });
}
/**
 * AJAX search
 */
function search_ajax(search)
{
  var articles = "";
  var x        = 0;
  jQuery.ajax(
  {
    type: "POST",
    dataType: 'html',
    url: "/wp-admin/admin-ajax.php?action=search_products",
    data: {"search": search},                                     
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
      get_last_arg();
      get_display_categories();
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
      get_last_arg();
      get_display_categories();      
      update_scroll();
      change_currency();
    }
  });
}

/**
 * JUST FOR DEBUGING..
 * This function is equivalent to the exact same in PHP ( var_dump )
 */
function dump(obj) {
    var out = "";
    if(obj && typeof(obj) == "object"){
        for (var i in obj) {
            out += i + ": " + obj[i] + "\n";
        }
    } else {
        out = obj;
    }
    alert(out);
}


function get_last_arg()
{
  jQuery.ajax(
  {
    type: "POST",
    dataType: 'html',
    url: "/wp-admin/admin-ajax.php?action=last_args",              
    success: function(response)
    {
      setLocation(response);  
      update_scroll();
      change_currency();    
    }
  });
}

function get_display_categories()
{
  var str = "";
  jQuery.ajax(
  {
    type: "POST",
    dataType: 'json',
    url: "/wp-admin/admin-ajax.php?action=display_categories", 
    beforeSend: function() {
      jQuery('.widget-filter').addClass('search-filter-form-loading'); 
      str = '<style id="temp-styles">.search-filter-form-loading:after { height:' + jQuery('.widget-filter').height() + 'px; }</style>';
      jQuery('head').append(str);     
    },
    complete: function()
    {
      jQuery('.widget-filter').removeClass('search-filter-form-loading');
      jQuery('#temp-styles').remove(); 
      change_currency();       
    },             
    success: function(response) 
    {   
      update_scroll();
        
      if(response === null) 
      {
        jQuery('.widget-filter').removeClass('search-filter-form-loading');
        jQuery('#temp-styles').remove();
        return;
      }

      var filtered_blocks_disabled_arr = new Array();
      var blocks_disabled_arr          = ['.shop-by-category', '.shop-by-brand', '.shop-by-colour', '.shop-by-price', '.shop-by-selection', '.shop-by-size', '.shop-by-ring-size', '.shop-by-clothes-size'];
      var blocks_disabled              = "";
      var count_checked                = 0;
      var last_checked_block           = "";
      var cheched_filtered_block       = 0;
      var last_data_block              = "";

      blocks_disabled                  = blocks_disabled_arr.join(", ");     
      count_checked                    = jQuery(blocks_disabled).find('input:checked').length;
      last_checked_block               = jQuery(blocks_disabled).find('input:checked').data('block');
      if(typeof(last_checked_block) != 'undefined' && last_check_box_block != "")
      {
        if(jQuery("." + last_check_box_block).find('input:checked').length > 0)
        {
          last_checked_block = last_check_box_block; 
        }    
        else
        {
          last_checked_block = jQuery(blocks_disabled).find('input:checked').data('block');
        }
      }

      


      jQuery.each(blocks_disabled_arr, function( index, value ) {
        if(value != "." + last_checked_block)
        { 
          filtered_blocks_disabled_arr.push(blocks_disabled_arr[index]);
        }          
      });

      
      if(jQuery(blocks_disabled).find('input:checked').length > 0)
      {
        jQuery(blocks_disabled).find('input:checked').each(function(){

          if(last_data_block == "")
          {
            last_data_block = jQuery(this).data('block');
          }
          else
          {
            if(last_data_block != jQuery(this).data('block'))
            {
              last_data_block = jQuery(this).data('block'); 
              cheched_filtered_block++;
            }
          }        
        });
      }

      blocks_disabled = filtered_blocks_disabled_arr.join(", ");
      // ========================================================
      // Clear all
      // ========================================================      
      jQuery(blocks_disabled).find('label').each(function(){
       if(!jQuery(this).hasClass('has-drop'))
       {
         jQuery(this).removeClass('disabled');  
         jQuery("#" + jQuery(this).data('input')).removeAttr('disabled');
         jQuery("#" + jQuery(this).data('a')).removeClass('disabled-jqTransformCheckbox');
       }
      });

      jQuery(blocks_disabled).find('label').each(function()
      {
        if(!jQuery(this).hasClass('has-drop') && !jQuery(this).parent().find('span a.jqTransformCheckbox').hasClass('jqTransformChecked') && !jQuery(this).parent().find('div div span a.jqTransformCheckbox').hasClass('jqTransformChecked'))
        {
          jQuery(this).addClass('disabled');  
          jQuery("#" + jQuery(this).data('input')).attr('disabled', 'disabled');
          jQuery("#" + jQuery(this).data('a')).addClass('disabled-jqTransformCheckbox');
        }
      });  

      if(cheched_filtered_block == 0 && jQuery(blocks_disabled).find('input:checked').length > 0)
      {        
        jQuery("." + last_data_block).find('label').each(function(){
         if(!jQuery(this).hasClass('has-drop'))
         {
           jQuery(this).removeClass('disabled');  
           jQuery("#" + jQuery(this).data('input')).removeAttr('disabled');
           jQuery("#" + jQuery(this).data('a')).removeClass('disabled-jqTransformCheckbox');
         }
        });
      }

      jQuery.each(response, function(i, item){
        jQuery.each(response[i], function(y){          
          jQuery('#label-' + response[i][y].slug).removeClass('disabled'); 
          jQuery("#" + jQuery('#label-' + response[i][y].slug).data('input')).removeAttr('disabled');
          jQuery("#" + jQuery('#label-' + response[i][y].slug).data('a')).removeClass('disabled-jqTransformCheckbox');
          
        });
      });
      jQuery('.widget-filter').removeClass('search-filter-form-loading');
      jQuery('#temp-styles').remove();

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