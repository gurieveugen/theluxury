jQuery(function()
{
  jQuery( "input[type='checkbox']" ).change(function() 
  {
    var v = [];
    jQuery('.search-filter-form').find("input:checkbox:checked").each(function()
    {
      v.push([jQuery(this).attr("name").replace('[]', ''), jQuery(this).val()]);
    });

    search_ajax(v); 
    return false;   
  });

  jQuery('.solt-by-values li a').click(function()
  {
    var v   = [];
    var psv = jQuery(this).attr('href');
    psv     = psv.replace('#', '');
    

    jQuery('.search-filter-form').find("input:checkbox:checked").each(function(){
      v.push([jQuery(this).attr("name").replace('[]', ''), jQuery(this).val()]);
    });
    v.push(['psort', psv]);

    search_ajax(v);

    jQuery('.sort-by-current').text(jQuery(this).text());
    
    return false;
  });

  jQuery('#products-ppp ul li a').click(function(){
    var v   = [];
    var ppp = jQuery(this).attr('href');
    ppp     = ppp.replace('#view-', '');
    jQuery('.ppp-form .ppp-val').val(ppp);      
    jQuery('#products-ppp .ppp-curr').html('View '+ppp);

    jQuery('#products-ppp ul li').each(function(){
      jQuery(this).removeClass('active');
    });
    jQuery(this).parent().addClass('active');

    jQuery('.search-filter-form').find("input:checkbox:checked").each(function(){
      v.push([jQuery(this).attr("name").replace('[]', ''), jQuery(this).val()]);
    });
    v.push(['ppp', ppp]);

    search_ajax(v);
    return false;
  });

});

/**
 * Get ajax page
 */
function get_page(paged)
{
  var v   = [];
  jQuery('.search-filter-form').find("input:checkbox:checked").each(function(){
    v.push([jQuery(this).attr("name").replace('[]', ''), jQuery(this).val()]);
  });
  v.push(['paged', paged]);

  search_ajax(v);    
  return false;
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
    },
    complete: function()
    {
      jQuery('#products-container').removeClass('loading');
      get_last_arg();
    }
  });
}

/**
 * JUST FOR DEBUGING.
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
    }
  });
}


function setLocation(curLoc)
{ 
  location.hash = '#!' + curLoc;
}


