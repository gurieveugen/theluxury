jQuery(document).ready(function(){
	jQuery(".pricing-add-icon").click(function(){
		jQuery("#pricing-action").val('add');
		pricing_clear_form();
	});
	jQuery(".pricing-list .pricing-edit-icon").click(function(){
		pricing_clear_form();
		var pid = jQuery(this).attr('rel');
		var pdata_line = jQuery('#pricing-data-'+pid).val();
		var pdata = pdata_line.split(';');

		jQuery(".pricing-form #pricing-action").val('edit');
		jQuery(".pricing-form #pricing-pid").val(pid);
		jQuery(".pricing-form #p_category option[value='"+pdata[0]+"']").attr('selected', 'selected');
		jQuery(".pricing-form #p-brand option[value='"+pdata[1]+"']").attr('selected', 'selected');
		jQuery(".pricing-form #p-style-name").val(pdata[2]);
		jQuery(".pricing-form #p-selection option[value='"+pdata[3]+"']").attr('selected', 'selected');
		jQuery(".pricing-form #p-colour option[value='"+pdata[4]+"']").attr('selected', 'selected');
		jQuery(".pricing-form #p-original-price").val(pdata[5]);
		jQuery(".pricing-form #p-high-price").val(pdata[6]);
		jQuery(".pricing-form #p-low-price").val(pdata[7]);
		jQuery(".pricing-form #p-includes-box").val(pdata[8]);
		jQuery(".pricing-form #p-includes-invoice").val(pdata[9]);
		jQuery(".pricing-form #p-includes-dustbag").val(pdata[10]);
		jQuery(".pricing-form #p-includes-card").val(pdata[11]);
		jQuery(".pricing-form #p-includes-booklet").val(pdata[12]);
		jQuery(".pricing-form #p-includes-packaging").val(pdata[13]);
		if (pdata[14] != '') {
			jQuery(".pricing-form #p-photo-img").attr('src', pdata[14]);
			jQuery(".pricing-form #p-photo").show();
		}
		jQuery(".pricing-form #p-notes").val(pdata[15]);
		var metal = pdata[16].split('|');
		var material = pdata[17].split('|');
		var movement = pdata[18].split('|');
		for (var i=0; i<metal.length; i++) {
			jQuery(".pricing-form #p-metal option[value='"+metal[i]+"']").attr('selected', 'selected');
		}
		for (var i=0; i<material.length; i++) {
			jQuery(".pricing-form #p-material option[value='"+material[i]+"']").attr('selected', 'selected');
		}
		for (var i=0; i<movement.length; i++) {
			jQuery(".pricing-form #p-movement option[value='"+movement[i]+"']").attr('selected', 'selected');
		}
	});
	jQuery(".pricing-list .pricing-delete-icon").click(function(){
		var pid = jQuery(this).attr('rel');
		var d = confirm("Delete this record?");
		if (d) {
			jQuery("#pricing-del-pid").val(pid);
			jQuery("#pricing-delete-form").submit();
		}
	});
});

function pricing_clear_form() {
	jQuery(".pricing-form select option").removeAttr('selected');
	jQuery(".pricing-form input[type='text']").val('');
	jQuery(".pricing-form textarea").val('');
	jQuery(".pricing-form #p-photo").hide();
}

function pricing_low_price() {
	var lprice = 0;
	var hprice = jQuery(".pricing-form #p-high-price").val();
	if (hprice > 0) {
		var price30 = (hprice / 100) * 30;
		lprice = hprice - price30;
		lprice = lprice.toFixed();
	}
	jQuery(".pricing-form #p-low-price").val(lprice);
}

function status_act() {
	var oss = jQuery('.order-status-act').val();
	if (oss == 'delete' || oss == '0') {
		jQuery('.order-cancel-reason').show();
	} else {
		jQuery('.order-cancel-reason').hide();
	}
}

function admin_ga_refund_event(txn) {
	ga('require', 'ec');
	ga('ec:setAction', 'refund', { 'id': txn });
}

function admin_change_orders_status() {
	var stat = jQuery('.order-status-act').val();
	alert(stat);
	if (stat == '0' || stat == 'delete') {
		jQuery('.nws_manage_orders .move-ch').each(function(){
			if (jQuery(this).is(':checked')) {
				var oid = jQuery(this).attr('name');
				var txnid = jQuery('.order-'+oid).attr('rel');
				alert(oid+' - '+txnid);
				admin_ga_refund_event(txnid);
			}
		});
	}
}