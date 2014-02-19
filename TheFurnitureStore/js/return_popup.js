// JavaScript Document
var popupStatus = 0;
jQuery(document).ready(function(){

	jQuery("#popup_close").click(function(){  disablePopup();  });  
	jQuery("#popup_bg").click(function(){  disablePopup();  });  
	jQuery(".popup_forms #cancel").click(function(){   disablePopup(); return false;  }); 
	jQuery(document).keypress(function(e){  
		if(e.keyCode==27 && popupStatus==1){  disablePopup();  }  
	}); 
	jQuery(".return").click(function(event){ 
		oid = jQuery(this).parent().parent().find('#order_id').val();
		jQuery('#popup_form #pop_rt_order_id').val(oid);
		toid = jQuery(this).parent().parent().find('#torder_id').html();
		jQuery('#popup_form #TB_ajaxWindowTitle').html('Returned this Order #'+toid+'?');
		num_of_prod = 1;
		jQuery("#popup_rt_data").find("tbody tr:gt(0)").remove();
		jQuery(this).parent().find('tbody tr').each(function(){
			qty = jQuery(this).find('#item_qty').html();
			prid = jQuery(this).find('#item_id').html();
			cid = jQuery(this).find('#cid').val();
			prnm = jQuery(this).find('#item_name').html();
			if(num_of_prod > 1)
				row = jQuery('#popup_rt_data').find('tbody tr:first').clone(true).insertAfter('#popup_rt_data tbody tr:last');
			else row = jQuery('#popup_rt_data').find('tbody tr:first');
			num_of_prod++;
			jQuery(row).find('.pop_item_id').html(prid);
			jQuery(row).find('.pop_item_name').html(prnm);
			jQuery(row).find('.pop_item_qty').html(qty);
			jQuery(row).find('.pop_item_rtqty').attr('name','pop_item_rtqty_'+cid);
			jQuery(row).find('.pop_item_rtqty').val(1);
		});
		jQuery('.popup_form1 .main_error').remove();
		jQuery('.popup_form1 .message').remove(); 
		centerPopup();  
		loadPopup(); 
		return false; 
	});

});
function loadPopup()
{  
	if(popupStatus==0)
	{  
		jQuery("#popup_bg").css({ "opacity": "0.7" });  
		jQuery("#popup_bg").fadeIn("slow");  
		jQuery("#popup_form").fadeIn("slow");  
		popupStatus = 1;  
	}  
}
function disablePopup()
{  
	if(popupStatus==1)
	{  
		jQuery("#popup_bg").fadeOut("slow");  
		jQuery("#popup_form").fadeOut("slow");  
		popupStatus = 0;  
	}  
}
function centerPopup()
{  
	var windowWidth = document.documentElement.clientWidth;  
	var windowHeight = document.documentElement.clientHeight;  
	var popupHeight = jQuery("#popup_form").height();  
	var popupWidth = jQuery("#popup_form").width();  
	jQuery("#popup_form").css({  "position": "fixed","top": windowHeight/2-popupHeight/2,"left": windowWidth/2-popupWidth/2	});  
	jQuery("#popup_bg").css({  "height": windowHeight  });  
}