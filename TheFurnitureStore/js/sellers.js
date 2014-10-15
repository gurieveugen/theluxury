var tlctab = "";
jQuery(document).ready(function(){
	jQuery('.sellers-other-tabs').show();
	jQuery('a.change-price-btn').click(function(){ // change price button
		jQuery(this).hide();
		jQuery(this).parent().find('.change-price').show();
		jQuery(this).parent().parent().find('.change-price-request').hide();
		return false;
	});
	jQuery('a.pic-zoom').colorbox();
	jQuery('a.help').click(function(){
		var arel = jQuery(this).attr('rel');
		jQuery('.'+arel).show(100);
		return false;
	});
	jQuery('a.help').hover(
		function(){},
		function(){
			var arel = jQuery(this).attr('rel');
			jQuery('.'+arel).hide();
		}
	);

	// professional sellers
	jQuery('.profseller-my-items a.edit').click(function(){ // edit item button
		var pid = jQuery(this).attr('rel');
		var editurl = jQuery('#profreseller-action-form #profreseller-editurl').val();
		jQuery('#profreseller-action-form').attr('action', editurl);
		jQuery('#profreseller-action-form #profreseller-post-id').val(pid);
		jQuery('#profreseller-action-form').submit();
		return false;
	});
	jQuery('.profseller-my-items a.submit').click(function(){ // submit item button
		var pid = jQuery(this).attr('rel');
		jQuery('#profreseller-action-form #profreseller-action').val('profreseller_submit_item');
		jQuery('#profreseller-action-form #profreseller-post-id').val(pid);
		jQuery('#profreseller-action-form').submit();
		return false;
	});
	jQuery('.profseller-my-items a.delete').click(function(){ // delete item button
		var pid = jQuery(this).attr('rel');
		var dc = confirm("Delete this item?");
		if (dc) {
			if (jQuery(this).hasClass('delete-inventory')) {
				jQuery('#profreseller-action-form #profreseller-action').val('profreseller_clear_item_inventory');
			} else {
				jQuery('#profreseller-action-form #profreseller-action').val('profreseller_delete_item');
			}
			jQuery('#profreseller-action-form #profreseller-post-id').val(pid);
			jQuery('#profreseller-action-form').submit();
		}
		return false;
	});
	jQuery('.profseller-my-items a.save-price').click(function(){ // save item price button
		var iid = jQuery(this).attr('rel');
		var yprice = jQuery('#item-your-price-'+iid).val();
		var defprice = jQuery('#item-your-price-'+iid).attr('rel');

		if (yprice <= defprice) {
			jQuery(this).parent().hide();
			jQuery(this).parent().parent().find('.change-price-btn').show();
			jQuery(this).parent().parent().find('.change-price-request').show();
			jQuery.post(siteurl, 
				{
					SellersAction: 'profreseller_change_item_price',
					post_id: iid,
					item_your_price: yprice
				},
				function(data){
					var prices = data.split(';');
					jQuery('.item-your-price-'+iid).html(prices[0]);
					jQuery('.item-selling-price-'+iid).html(prices[1]);
				}
			);
		} else {
			alert('Price can not more than '+defprice);
		}
		return false;
	});
	jQuery('.item-change-price-btn').click(function(){ // change your price button
		jQuery.colorbox({inline:true, href:'#profseller-calculate-price'});
	});
	
	jQuery('.profseller-calculate-price .pcp-calculate').click(function(){ // calculate price button
		var s_category = jQuery(".profseller-calculate-price #pcp_category").val();
		var s_brand = jQuery(".profseller-calculate-price #pcp_brand").val();
		var s_selection = jQuery(".profseller-calculate-price #pcp_selection").val();
		var s_colour = jQuery(".profseller-calculate-price #pcp_colour").val();
		var s_includes = '';
		for (var i=0; i<jQuery(".profseller-calculate-price .pcp-includes input").size(); i++) {
			if (jQuery(".profseller-calculate-price .pcp-includes input").eq(i).is(':checked')) {
				if (s_includes != '') { s_includes += ';'; }
				s_includes += jQuery(".profseller-calculate-price .pcp-includes input").eq(i).val();
			}
		}
		
		jQuery('.profseller-calculate-price .pcp-loading').show();
		jQuery.post(siteurl,
			{
				SellersAction: 'profreseller_pricing_database',
				s_category: s_category,
				s_brand: s_brand,
				s_selection: s_selection,
				s_colour: s_colour,
				s_includes: s_includes
			},
			function(data){
				jQuery(".profseller-calculate-price .pcp-data").html(data);
				jQuery(".profseller-calculate-price .pcp-loading").hide();
			}
		);
	});
	if (jQuery('#pseller-orders').size()) {
		var psorders = jQuery('#pseller-orders').html();
		if (psorders != '') {
			var pso = psorders.split(',');
			jQuery('.tabset li.my-orders a').html('My Orders<span class="num">'+pso.length+'</span>');
		}
		jQuery('.tabset li.my-orders a').click(function(){
			if (psorders != '') {
				jQuery(this).html('My Orders');
				jQuery.post(siteurl,
					{
						SellersAction: 'profreseller_viewed_orders',
						psorders: psorders
					}
				);
			}
		});
	}
	jQuery('form#profseller-my-info').submit(function(){
		var errors = '';
		var seller_first_name = jQuery('#seller-first-name input').val();
		var seller_last_name = jQuery('#seller-last-name input').val();
		var seller_address = jQuery('#seller-address textarea').val();
		var seller_email = jQuery('#seller-email input').val();
		var seller_phone = jQuery('#seller-phone input').val();
		var seller_bank_type = jQuery('#seller-bank-type input:checked').val();
		var seller_bank_details = jQuery('#seller-bank-details textarea').val();

		jQuery('form.form-add label').removeClass('error');

		if (seller_first_name == '') { errors += ';first-name'; }
		if (seller_last_name == '') { errors += ';last-name'; }
		if (seller_email == '') { errors += ';email'; }
		else if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(seller_email)) { errors += ';email'; }
		if (seller_phone == '') { errors += ';phone'; }

		if (errors != '') {
			var errs = errors.split(';');
			for (var i=1; i<=errs.length; i++) {
				jQuery('#seller-'+errs[i]+' label').addClass('error');
			}
			return false;
		} else {
			jQuery('.seller-message').hide();
			jQuery('.seller-submitting').show();
			jQuery.post(siteurl,
				{
					SellersAction: 'profreseller_update_info',
					seller_first_name: seller_first_name,
					seller_last_name: seller_last_name,
					seller_address: seller_address,
					seller_email: seller_email,
					seller_phone: seller_phone,
					seller_bank_type: seller_bank_type,
					seller_bank_details: seller_bank_details
				},
				function(data) {
					jQuery('.seller-submitting').hide(0, function(){
						jQuery('.seller-message').show();
					});
				}
			);
		}
		return false;
	});
	change_style_select(0);
	seller_select_category_action();
	// individual sellers
	jQuery('.take-tour-box a').click(function(){ // take the tour
		jQuery.colorbox({inline:true, href:'#take-tour-video'});
		return false;
	});
	jQuery('.indvseller-my-items .submit-payout').click(function(){ // submit quotation price button
		var pid = jQuery(this).attr('rel');
		var yqprice = jQuery('#item-your-quotation-price-'+pid).val();
		var tlcminprice = parseInt(jQuery('#item-tlc-min-price-'+pid).val());
		var tlcmaxprice = parseInt(jQuery('#item-tlc-max-price-'+pid).val());
		if (yqprice > 0) {
			var sf = true;
			if (tlcminprice > 0 && tlcmaxprice > 0 && (yqprice > tlcmaxprice || yqprice < tlcminprice)) {
				sf = false;
			}
			if (sf) {
				jQuery.post(siteurl,
					{
						SellersAction: 'indivseller_submit_quotation_price',
						post_id: pid,
						item_your_quotation_price: yqprice
					},
					function(data) {
						jQuery('.select-payout-'+pid).hide();
						jQuery('.submit-payout-'+pid).hide();
						jQuery('.submited-payout-'+pid).show();
						jQuery('.item-your-quotation-price-'+pid).html(yqprice);
					}
				);
			} else {
				alert('Price should be in range.');
			}
		} else {
			alert('Please enter price.');
		}
		return false;
	});
	jQuery('.indvseller-my-items a.save-price').click(function(){ // save item price button
		var pid = jQuery(this).attr('rel');
		var yprice = parseInt(jQuery('#item-your-price-'+pid).val());
		var sprice = parseInt(jQuery('#item-your-sale-price-'+pid).val());
		if (yprice <= sprice) {
			jQuery(this).parent().hide();
			jQuery(this).parent().parent().find('.change-price-btn').show();
			jQuery(this).parent().parent().find('.change-price-request').show();
			jQuery.post(siteurl, { SellersAction: 'indivseller_change_item_price', post_id: pid, item_your_price: yprice });
		} else {
			alert('Price can not more than '+sprice);
		}
		return false;
	});
	jQuery('.indvseller-my-items .change-price-cancel').click(function(){ // cancel change price button
		var pid = jQuery(this).attr('rel');
		jQuery(this).parent().hide();
		jQuery(this).parent().parent().find('.change-price-btn').show();
	});
	jQuery('.indvseller-my-items a.accept-payout').click(function(){ // accept payout button
		var pid = jQuery(this).attr('rel');
		jQuery('#indivseller-action-form #indivseller-action').val('indivseller_accept_suggested_payout');
		jQuery('#indivseller-action-form #indivseller-post-id').val(pid);
		jQuery('#indivseller-action-form').submit();
		return false;
	});
	jQuery('.indvseller-my-items a.decline-payout').click(function(){ // accept payout button
		var pid = jQuery(this).attr('rel');
		jQuery('#indivseller-action-form #indivseller-action').val('indivseller_decline_suggested_payout');
		jQuery('#indivseller-action-form #indivseller-post-id').val(pid);
		jQuery('#indivseller-action-form').submit();
		return false;
	});

	jQuery('.indvseller-my-items a.edit').click(function(){ // edit item button
		var pid = jQuery(this).attr('rel');
		var editurl = jQuery('#indivseller-action-form #indivseller-editurl').val();
		jQuery('#indivseller-action-form').attr('action', editurl);
		jQuery('#indivseller-action-form #indivseller-post-id').val(pid);
		jQuery('#indivseller-action-form').submit();
		return false;
	});
	jQuery('.indvseller-my-items a.delete').click(function(){ // delete item button
		var pid = jQuery(this).attr('rel');
		var dc = confirm("Delete this item?");
		if (dc) {
			jQuery('#indivseller-action-form #indivseller-action').val('indivseller_delete_item');
			jQuery('#indivseller-action-form #indivseller-post-id').val(pid);
			jQuery('#indivseller-action-form').submit();
		}
		return false;
	});
	jQuery('.no-quotation').click(function(){ // no quotation link
		jQuery.colorbox({inline:true, href:'#no-quotation-message'});
		return false;
	});
	jQuery('form#indivseller-my-info').submit(function(){
		var errors = '';
		var seller_first_name = jQuery('#seller-first-name input').val();
		var seller_last_name = jQuery('#seller-last-name input').val();
		var seller_address = jQuery('#seller-address textarea').val();
		var seller_email = jQuery('#seller-email input').val();
		var seller_phone = jQuery('#seller-phone input').val();
		var seller_bank_type = jQuery('#seller-bank-type input:checked').val();
		var seller_bank_details = jQuery('#seller-bank-details textarea').val();

		jQuery('form.form-add label').removeClass('error');

		if (seller_first_name == '') { errors += ';first-name'; }
		if (seller_last_name == '') { errors += ';last-name'; }
		if (seller_email == '') { errors += ';email'; }
		else if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(seller_email)) { errors += ';email'; }
		if (seller_phone == '') { errors += ';phone'; }

		if (errors != '') {
			var errs = errors.split(';');
			for (var i=1; i<=errs.length; i++) {
				jQuery('#seller-'+errs[i]+' label').addClass('error');
			}
			return false;
		} else {
			jQuery('.seller-message').hide();
			jQuery('.seller-submitting').show();
			jQuery.post(siteurl,
				{
					SellersAction: 'indivseller_update_info',
					seller_first_name: seller_first_name,
					seller_last_name: seller_last_name,
					seller_address: seller_address,
					seller_email: seller_email,
					seller_phone: seller_phone,
					seller_bank_type: seller_bank_type,
					seller_bank_details: seller_bank_details
				},
				function(data) {
					jQuery('.seller-submitting').hide(0, function(){
						jQuery('.seller-message').show();
					});
				}
			);
		}
		return false;
	});
	// summary page
	jQuery('.sellers-summary-page a.edit').click(function(){ // edit item button
		var pid = jQuery(this).attr('rel');
		var editurl = jQuery('#summary-action-form #summary-editurl').val();
		jQuery('#summary-action-form').attr('action', editurl);
		jQuery('#summary-action-form #summary-post-id').val(pid);
		jQuery('#summary-action-form').submit();
		return false;
	});
	jQuery('.sellers-summary-page a.delete').click(function(){ // delete item button
		var pid = jQuery(this).attr('rel');
		var dc = confirm("Delete this item?");
		if (dc) {
			if (jQuery(this).hasClass('delete-inventory')) {
				jQuery('#summary-action-form #summary-action').val('summary_clear_item_inventory');
			} else {
				jQuery('#summary-action-form #summary-action').val('summary_delete_item');
			}
			jQuery('#summary-action-form #summary-post-id').val(pid);
			jQuery('#summary-action-form').submit();
		}
		return false;
	});
	jQuery('.sellers-summary-page a.save-price').click(function(){ // save item price button
		var pid = jQuery(this).attr('rel');
		var yprice = jQuery('#item-your-price-'+pid).val();
		jQuery('#summary-action-form #summary-action').val('summary_change_item_price');
		jQuery('#summary-action-form #summary-post-id').val(pid);
		jQuery('#summary-action-form #summary-price').val(yprice);
		jQuery('#summary-action-form').submit();
		return false;
	});
	jQuery('.sellers-summary-page a.prof-save-price').click(function(){ // save item price button
		var pid = jQuery(this).attr('rel');
		var yprice = jQuery('#item-your-price-'+pid).val();
		jQuery('#summary-action-form #summary-action').val('summary_prof_change_item_price');
		jQuery('#summary-action-form #summary-post-id').val(pid);
		jQuery('#summary-action-form #summary-price').val(yprice);
		jQuery('#summary-action-form').submit();
		return false;
	});
	jQuery('.sellers-summary-info .sellers-summary-info-edit').click(function(){ // edit seller info
		if (jQuery('#sellers-summary-info-box').is(':visible')) {
			jQuery('#sellers-summary-info-box').animate({height: 'hide'}, 300);
		} else {
			jQuery('#sellers-summary-info-box').animate({height: 'show'}, 300);
		}
		return false;
	});
	jQuery('#sellers-summary-info-form').submit(function(){
		var seller_id = jQuery('#seller-id').val();
		var seller_first_name = jQuery('#seller-first-name input').val();
		var seller_last_name = jQuery('#seller-last-name input').val();
		var seller_address = jQuery('#seller-address textarea').val();
		var seller_email = jQuery('#seller-email input').val();
		var seller_phone = jQuery('#seller-phone input').val();
		var seller_bank_type = jQuery('#seller-bank-type input:checked').val();
		var seller_bank_details = jQuery('#seller-bank-details textarea').val();

		jQuery('#sellers-summary-info-form .seller-submitting').show();
		jQuery.post(siteurl,
			{
				SellersAction: 'summary_update_seller_info',
				seller_id: seller_id,
				seller_first_name: seller_first_name,
				seller_last_name: seller_last_name,
				seller_address: seller_address,
				seller_email: seller_email,
				seller_phone: seller_phone,
				seller_bank_type: seller_bank_type,
				seller_bank_details: seller_bank_details
			},
			function(data) {
				jQuery('#sellers-summary-info-form .seller-submitting').hide(0, function(){
					jQuery('.si-name span').html(seller_first_name+' '+seller_last_name);
					jQuery('.si-email span').html('<a href="mailto:'+seller_email+'">'+seller_email+'</a>');
					jQuery('.si-phone span').html(seller_phone);
					jQuery('.si-address span').html(nl2br(seller_address));
					jQuery('.si-payment span').html(seller_bank_type+'<br />'+nl2br(seller_bank_details));
					jQuery('#sellers-summary-info-box').animate({height: 'hide'}, 300);
				});
			}
		);
		return false;
	});

	// tlc admin
	jQuery('.main-tlc .main-tabs li a').click(function(){
		var stab = 'is';
		var trel = jQuery(this).attr('rel');
		if (trel == 'psellers') { stab = 'ps'; }
		if (!jQuery('.main-tlc #'+trel).is(':visible')) {
			jQuery('.main-tlc .sellers-container').hide();
			jQuery('.main-tlc .main-tabs li').removeClass('active');
			jQuery(this).parent().addClass('active');
			jQuery('.main-tlc #'+trel).show();
			jQuery('#search-tab').val(stab);
		}
		return false;
	});
	if (tlctab != '') {
		var maintab_arr = tlctab.split('-');
		var maintab = maintab_arr[maintab_arr.length - 1];
		if (tlctab != 'submitted-items') {
			jQuery(".main-tlc .tabset li a[href='#"+tlctab+"']").trigger('click');
			jQuery(".main-tlc .main-tabs li a[href='#"+maintab+"ellers']").trigger('click');
		}
	}
	jQuery('.main-tlc a.view, .main-tlc .change-quotation').click(function(){
		var pid = jQuery(this).attr('name');
		var qtype = 'view';
		if (jQuery(this).hasClass('change-quotation')) { qtype = 'change'; }

		jQuery('.quotation-item-view .quotation-item-data').html(jQuery('.quotation-item-view-'+pid).html());

		// clear form
		jQuery(".quotation-item-view select option").removeAttr('selected');
		jQuery(".quotation-item-view input[type='checkbox']").removeAttr('checked');
		jQuery(".quotation-item-view input[type='text']").val('');
		jQuery(".quotation-item-view .pd-data").html('<p>Please select search criteria.</p>');
		jQuery("#q-price-high").val('High');
		jQuery("#q-price-low").val('Low');

		jQuery('.quotation-item-view .pd-data').html('<p>Please select search criteria.</p>');
		jQuery('.quotation-item-view .pd-data').show();
		jQuery('.quotation-item-view .pd-add-new').hide();

		// predefine
		var icat = jQuery('.quotation-item-view .quotation-item-data .item-category-data').attr('rel');
		var ibrand = jQuery('.quotation-item-view .quotation-item-data .item-brand-data').attr('rel');
		var isel = jQuery('.quotation-item-view .quotation-item-data .item-selection-data').attr('rel');

		jQuery(".quotation-item-view #q_category option[value='"+icat+"']").attr('selected', 'selected');
		jQuery(".quotation-item-view .item-edit-category-"+pid+" select option[value='"+icat+"']").attr('selected', 'selected');
		jQuery(".quotation-item-view #q_brand option[value='"+ibrand+"']").attr('selected', 'selected');
		jQuery(".quotation-item-view #q_selection option[value='"+isel+"']").attr('selected', 'selected');

		jQuery('.quotation-item-view #quotation-post-id').val(pid);
		jQuery('.quotation-item-view #quotation-type').val(qtype);

		jQuery.colorbox({inline:true, href:'#quotation-item-view'});

		// update item viewed
		jQuery('.submitted-item-'+pid).removeClass('item-new');
		jQuery.post(siteurl, {SellersAction: 'tlc_item_view', post_id: pid});
		return false;
	});
	jQuery('.quotation-item-view #pd-search-form').submit(function(){
		var s_category = jQuery(".quotation-item-view #q_category").val();
		var s_brand = jQuery(".quotation-item-view #q_brand").val();
		var s_selection = jQuery(".quotation-item-view #q_selection").val();
		var s_term = jQuery(".quotation-item-view #q_term").val();
		jQuery('.quotation-item-view .pd-add-new').hide();
		jQuery('.quotation-item-view .pd-data').show();
		jQuery(".pd-data-load").show();
		jQuery.post(siteurl,
			{
				SellersAction: 'tlc_pricing_database',
				s_category: s_category,
				s_brand: s_brand,
				s_selection: s_selection,
				s_term: s_term
			},
			function(data){
				jQuery(".pd-data").html(data);
				jQuery(".pd-data-load").hide();
			}
		);
		return false;
	});
	jQuery('.quotation-item-view #quotation-item-view-form').submit(function(){
		var hprice = jQuery("#q-price-high").val();
		var lprice = jQuery("#q-price-low").val();
		if (hprice > 0 && lprice > 0) {
			return true;
		} else {
			alert('Incorrect Quotation Price');
			return false;
		}
	});
	jQuery('.quotation-item-view .no-quote-btn').click(function(){
		var pid = jQuery('#quotation-post-id').val();
		jQuery.post(siteurl,
			{
				SellersAction: 'tlc_no_quotation',
				post_id: pid
			}
		);
		jQuery('.quotation-value-'+pid).html('No Quote');
		jQuery.colorbox.remove();
	});
	jQuery('.main-tlc a.follow').click(function(){
		var pid = jQuery(this).attr('name');
		jQuery('#follow-post-id').val(pid);
	});
	jQuery('.main-tlc a.follow').colorbox({inline:true, href:'#follow-email-form'});
	jQuery('#follow-form').submit(function(){
		var post_id = jQuery('#follow-post-id').val();
		var subject = jQuery('#follow-subject').val();
		var message = jQuery('#follow-message').val();

		jQuery.post(siteurl,
			{
				SellersAction: 'tlc_send_follow',
				post_id: post_id,
				subject: subject,
				message: message
			}
		);

		jQuery('.product-item-'+post_id).addClass('follow-sent');
		jQuery.colorbox.remove();

		return false;
	});

	jQuery('.main-tlc a.completed').click(function(){ // completed button
		jQuery(this).parent().submit();
		return false;
	});
	jQuery('.main-tlc .pd-add-new-save').click(function(){
		var pid = jQuery('.quotation-item-view #quotation-post-id').val();
		var category = jQuery('.quotation-item-view #p_category').val();
		var brand = jQuery('.quotation-item-view #p_brand').val();
		var style_name = jQuery('.quotation-item-view #p_style_name').val();
		var selection = jQuery('.quotation-item-view #p_selection').val();
		var colour = jQuery('.quotation-item-view #p_colour').val();
		var original_price = jQuery('.quotation-item-view #p_original_price').val();
		var high_price = jQuery('.quotation-item-view #p_high_price').val();
		var low_price = jQuery('.quotation-item-view #p_low_price').val();
		var includes_box = jQuery('.quotation-item-view #p_includes_box').val();
		var includes_invoice = jQuery('.quotation-item-view #p_includes_invoice').val();
		var includes_dustbag = jQuery('.quotation-item-view #p_includes_dustbag').val();
		var includes_card = jQuery('.quotation-item-view #p_includes_card').val();
		var includes_booklet = jQuery('.quotation-item-view #p_includes_booklet').val();
		var notes = jQuery('.quotation-item-view #p_notes').val();
		jQuery('.quotation-item-view .pd-add-new-loading').show();
		jQuery.post(siteurl,
			{
				SellersAction: 'tlc_add_new_pricing',
				pid: pid,
				category: category,
				brand: brand,
				style_name: style_name,
				selection: selection,
				colour: colour,
				original_price: original_price,
				high_price: high_price,
				low_price: low_price,
				includes_box: includes_box,
				includes_invoice: includes_invoice,
				includes_dustbag: includes_dustbag,
				includes_card: includes_card,
				includes_booklet: includes_booklet,
				notes: notes
			},
			function(data){
				jQuery('.quotation-item-view .pd-add-new').hide();
				jQuery('.quotation-item-view .pd-add-new-loading').hide();
				jQuery('.quotation-item-view .pd-search-btn').trigger('click');
				jQuery('.quotation-item-view .pd-data').show();
			}
		);
	});
	// pricing search
	jQuery('.pricing-view li img').click(function(){
		var lview = jQuery(this).attr('rel');
		if (!jQuery(this).parent().hasClass('active')) {
			jQuery('.pricing-view li').removeClass('active');
			jQuery(this).parent().addClass('active');
			jQuery('.results-section .view-section').hide();
			jQuery('.results-section .view-'+lview).show();
			jQuery('#psfield-view').val(lview);
			jQuery('#pricing-nav-view').val(lview);
		}
	});
	jQuery('#pricing-nav a').click(function(){
		var hpg = jQuery(this).attr('href');
		hpg = hpg.replace('#', '');
		jQuery('#pricing-nav-pg').val(hpg);
		jQuery('#pricing-nav').submit();
		return false;
	});
	jQuery('.view-row ul li a').click(function(){
		var ddiv = jQuery(this).attr('rel');
		jQuery('.row-view-details').html(jQuery('.'+ddiv).html());
	});
	jQuery('.view-row ul li a').colorbox({inline:true, href:'#row-view-details'});
});

function seller_select_category_action() {
	seller_change_dimensions_meterial();
	seller_check_colour_select();
	seller_subcategory_change();
	if (jQuery('#item-style select').size()) {
		change_style_select(1);
	}
}

function my_items_category_change(nm) {
	if (nm == 'my_items_category') {
		var cid = jQuery('#'+nm).val();

		jQuery('.seller-products-list').hide();
		if (cid > 0) {
			jQuery('.seller-products-list-'+cid).animate({height: 'show'}, 300);
		} else {
			jQuery('.seller-products-list').animate({height: 'show'}, 300);
		}
		return false;
	} else if (nm == 'item_category') {
		seller_select_category_action();
	}
}

function seller_check_colour_select() {
	var scat = jQuery('#item_category').val();
	var scat_type = seller_get_selected_cat_type(scat);
	if (scat_type == 'jewelry') {
		jQuery('#item-colour').hide();
	} else {
		jQuery('#item-colour').show();
	}
}

function seller_change_dimensions_meterial() {
	if (jQuery('.item-dimensions').size()) {
		var scat = jQuery('#item_category').val();
		var scat_type = seller_get_selected_cat_type(scat);
		jQuery('.item-dimensions').hide();
		jQuery('.item-material').hide();
		if (scat_type != '') {
			jQuery('#'+scat_type+'-dimensions').show();
			jQuery('#'+scat_type+'-material').show();
		}
	}
}

function seller_get_selected_cat_type(c) {
	if (c != '') {
		if (jQuery.inArray(c, bags_cats) > -1) {
			return 'bags';
		} else if (jQuery.inArray(c, shoes_cats) > -1) {
			return 'shoes';
		} else if (jQuery.inArray(c, watches_cats) > -1) {
			return 'watches';
		} else if (jQuery.inArray(c, jewelry_cats) > -1) {
			return 'jewelry';
		}
	}
	return '';
}

function seller_subcategory_change() {
	var scat = jQuery('#item-style select option:selected').html();
	if (scat == 'Rings') {
		jQuery('#rings-dimensions').show();
	} else {
		jQuery('#rings-dimensions').hide();
	}
	if (scat == 'Necklaces') {
		jQuery('#necklaces-dimensions').show();
	} else {
		jQuery('#necklaces-dimensions').hide();
	}
	if (scat == 'Bracelets') {
		jQuery('#bracelets-dimensions').show();
	} else {
		jQuery('#bracelets-dimensions').hide();
	}
	if (scat == 'Earrings') {
		jQuery('#earrings-dimensions').show();
	} else {
		jQuery('#earrings-dimensions').hide();
	}
}

function seller_presubmit_form() {
	var errors = '';
	var item_category = jQuery('#item-category select').val();
	var item_name = jQuery('#item-name input').val();
	var item_brand = jQuery('#item-brand select').val();
	var item_your_price = jQuery('#item-your-price input').val();
	var item_condition = jQuery('#item-condition input:checked').val();
	var item_condition_desc = jQuery('#item-condition-desc textarea').val();
	var item_colour = jQuery('#item-colour select').val();
	var item_length = jQuery('#item-length input').val();
	var item_height = jQuery('#item-height input').val();
	var item_width = jQuery('#item-width input').val();
	var item_size = jQuery('#item-size select').val();
	var item_case_diameter = jQuery('#item-case-diameter input').val();
	var item_movement_type = jQuery('#item-movement-type input').val();
	var item_bracelet_material = jQuery('#item-bracelet-material input').val();
	var item_case_material = jQuery('#item-case-material input').val();
	var item_ring_size = jQuery('#item-ring-size select').val();
	var item_ring_width = jQuery('#item-ring-width input').val();
	var item_necklace_length = jQuery('#item-necklace-length input').val();
	var item_bracelet_size = jQuery('#item-bracelet-size input').val();

	var scat_type = seller_get_selected_cat_type(item_category);

	jQuery('form.form-add label').removeClass('error');
	if (item_condition == undefined) { item_condition = ''; }
	if (item_category == '' || item_category == '-1') { errors += ';category'; }
	if (item_name == '') { errors += ';name'; }
	if (item_brand == '') { errors += ';brand'; }
	if (item_your_price == '') { errors += ';your-price'; }
	if (item_condition == '') { errors += ';condition'; }
	if (item_condition_desc == '') { errors += ';condition-desc'; }

	var item_includes_check = false;
	for (var i=0; i<jQuery('.item-includes input').size(); i++) {
		if (jQuery('.item-includes input').eq(i).is(':checked')) {
			item_includes_check = true;
		}
	}
	if (!item_includes_check) { errors += ';includes'; }

	if (scat_type == 'bags'){
		if (item_length == '') { errors += ';length'; }
		if (item_height == '') { errors += ';height'; }
		if (item_width == '') { errors += ';width'; }
	} else if (scat_type == 'shoes'){
		if (item_size == '') { errors += ';size'; }
	} else if (scat_type == 'watches'){
		if (item_case_diameter == '') { errors += ';case-diameter'; }
		if (item_movement_type == '') { errors += ';movement-type'; }

		if (item_bracelet_material == '') { errors += ';bracelet-material'; }
		if (item_case_material == '') { errors += ';case-material'; }
	}
	if (scat_type != 'jewelry'){
		if (item_colour == '') { errors += ';colour'; }
	}
	if (jQuery('#rings-dimensions').is(':visible')) {
		if (item_ring_size == '') { errors += ';ring-size'; }
		if (item_ring_width == '') { errors += ';ring-width'; }
	}
	if (jQuery('#necklaces-dimensions').is(':visible')) {
		if (item_necklace_length == '') { errors += ';necklace-length'; }
	}
	if (jQuery('#bracelets-dimensions').is(':visible')) {
		if (item_bracelet_size == '') { errors += ';bracelet-size'; }
	}

	if (errors != '') {
		var errs = errors.split(';');
		for (var i=1; i<=errs.length; i++) {
			jQuery('#item-'+errs[i]+' label').addClass('error');
		}
		return false;
	} else {
		jQuery('#item-category-type').val(scat_type);
		jQuery('form.form-add').submit();
		return true;
	}
}

function indivseller_presubmit_form() {
	var errors = '';
	var item_category = jQuery('#item-category select').val();
	var item_name = jQuery('#item-name input').val();
	var item_brand = jQuery('#item-brand select').val();
	var item_condition = jQuery('#item-condition input:checked').val();
	var item_user_email = jQuery('#item-user-email input').val();
	var item_user_pass = jQuery('#item-user-pass input').val();
	var item_user_phone = jQuery('#item-user-phone input').val();
	var item_user = jQuery('#item-user input').val();

	jQuery('form.form-add label').removeClass('error');

	if (item_condition == undefined) { item_condition = ''; }
	if (item_category == '') { errors += ';category'; }
	if (item_name == '') { errors += ';name'; }
	if (item_brand == '') { errors += ';brand'; }
	if (item_condition == '') { errors += ';condition'; }
	if (jQuery('#item-user-email').size() > 0) {
		if (item_user_email == '') { errors += ';user-email'; }
		if (item_user_pass == '') { errors += ';user-pass'; }
	}
	if (jQuery('#item-user-phone').size() > 0) {
		if (item_user_phone == '') { errors += ';user-phone'; }
	}
	if (jQuery('#item-user').size() > 0) {
		if (item_user == '') { errors += ';user'; }
	}

	if (errors != '') {
		var errs = errors.split(';');
		for (var i=1; i<=errs.length; i++) {
			jQuery('#item-'+errs[i]+' label').addClass('error');
		}
		return false;
	} else {
		return true;
	}
}

function seller_remove_picture(pid, aid, act) {
	var ipd = confirm("Delete this picture?");
	if (ipd) {
		jQuery('.item-picture-'+aid).animate({height: 'hide'}, 300);
		jQuery.post(siteurl,
			{
				SellersAction: act+'_delete_picture',
				post_id: pid,
				attach_id: aid
			}
		);
	}
}

function change_style_select(ch) {
	var item_category = jQuery('#item-category select').val();

	if (ch) {
		jQuery('#item-style ul li a').removeClass('selected');
		jQuery('#item-style select option').removeAttr('selected');
		jQuery("#item-style ul li a[index='0']").addClass('selected');
		jQuery("#item-style .jqTransformSelectWrapper div span").html('-- Select Subcategory --');
	}

	for (var s=0; s<jQuery('#item-style select option').size(); s++) {
		if (s > 0) {
			var oclass = jQuery('#item-style select option').eq(s).attr('class');
			if (oclass.indexOf('{'+item_category+'}') > -1) {
				jQuery("#item-style ul li a[index='"+s+"']").parent().show();
			} else {
				jQuery("#item-style ul li a[index='"+s+"']").parent().hide();
			}
		}
	}
}

function calculate_selling_price() {
	var item_your_price = jQuery('#item-your-price input').val();

	jQuery('#item-selling-price span').html('');
	jQuery('#item-selling-price img').show();
	jQuery.post(siteurl,
		{
			SellersAction: 'profreseller_get_selling_price',
			item_your_price: item_your_price
		},
		function(data){
			jQuery('#item-selling-price img').hide();
			jQuery('#item-selling-price span').html(data);
		}
	);
}

function tlc_set_pd_qprice(pid, hp, lp) {
	jQuery('#q-pricing-id').val(pid);
	jQuery('#q-price-high').val(hp);
	jQuery('#q-price-low').val(lp);
}

function tlc_show_add_pricing() {
		jQuery(".quotation-item-view .pd-add-new-form select option").removeAttr('selected');
		jQuery(".quotation-item-view .pd-add-new-form input[type='text']").val('');

		var scategory = jQuery('.quotation-item-view #q_category').val();
		var sbrand = jQuery('.quotation-item-view #q_brand').val();
		var sselection = jQuery('.quotation-item-view #q_selection').val();
		var sterm = jQuery('.quotation-item-view #q_term').val();
		var scol = jQuery('.quotation-item-view .quotation-item-data .item-colour-data').attr('rel');
		var soprice = jQuery('.quotation-item-view .quotation-item-data .item-origin-price').attr('rel');

		jQuery(".quotation-item-view #p_category option[value='"+scategory+"']").attr('selected', 'selected');
		jQuery(".quotation-item-view #p_brand option[value='"+sbrand+"']").attr('selected', 'selected');
		jQuery(".quotation-item-view #p_selection option[value='"+sselection+"']").attr('selected', 'selected');
		jQuery(".quotation-item-view #p_colour option[value='"+scol+"']").attr('selected', 'selected');
		jQuery(".quotation-item-view #p_style_name").val(sterm);
		jQuery(".quotation-item-view #p_original_price").val(soprice);

	jQuery('.quotation-item-view .pd-data').hide();
	jQuery('.quotation-item-view .pd-add-new').animate({height:'show'}, 300);
}

function pd_low_price() {
	var lprice = 0;
	var hprice = jQuery(".pd-add-new-form #p_high_price").val();
	if (hprice > 0) {
		var price30 = (hprice / 100) * 30;
		lprice = hprice - price30;
		lprice = lprice.toFixed();
	}
	jQuery(".pd-add-new-form #p_low_price").val(lprice);
}

function profseller_form_submit() {
	var errors = '';
	var name = jQuery('#psf-name input').val();
	var company = jQuery('#psf-company input').val();
	var email = jQuery('#psf-email input').val();
	var cnumber = jQuery('#psf-contact-number input').val();
	var scategory = false;

	jQuery('.prof-seller-form label.tlt').removeClass('error');

	jQuery('#psf-category input').each(function(){
		if (jQuery(this).is(':checked')) {
			scategory = true;
		}
	});
	if (name == '') {
		errors = 'name';
	}
	if (company == '') {
		if (errors != '') { errors += ';'; }
		errors += 'company';
	}
	if (email == '') {
		if (errors != '') { errors += ';'; }
		errors += 'email';
	} else if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(email)) {
		if (errors != '') { errors += ';'; }
		errors += 'email';
	}
	if (cnumber == '') {
		if (errors != '') { errors += ';'; }
		errors += 'contact-number';
	}
	if (!scategory) {
		if (errors != '') { errors += ';'; }
		errors += 'category';
	}

	if (errors != '') {
		var errs = errors.split(';');
		for (var e=0; e<errs.length; e++) {
			jQuery('#psf-'+errs[e]+' label.tlt').addClass('error');
		}
		return false;
	}
}

function nl2br(str) {
	return str.replace(/([^>])\n/g, '$1<br/>');
}

function profseller_currency_change_form() {
	jQuery('.prof-currency-change-form #cc_item_category').val(jQuery('#item-category select').val());
	jQuery('.prof-currency-change-form #cc_item_name').val(jQuery('#item-name input').val());
	jQuery('.prof-currency-change-form #cc_item_brand').val(jQuery('#item-brand select').val());
	jQuery('.prof-currency-change-form #cc_item_desc').val(jQuery('#item-desc textarea').val());
	jQuery('.prof-currency-change-form #cc_item_retail_price').val(jQuery('#item-retail-price input').val());
	jQuery('.prof-currency-change-form #cc_item_your_price').val(jQuery('#item-your-price input').val());
	jQuery('.prof-currency-change-form #cc_item_condition_desc').val(jQuery('#item-condition-desc textarea').val());
	jQuery('.prof-currency-change-form #cc_item_selection').val(jQuery('#item-condition input:checked').val());
	jQuery('.prof-currency-change-form #cc_item_length').val(jQuery('#item-length input').val());
	jQuery('.prof-currency-change-form #cc_item_height').val(jQuery('#item-height input').val());
	jQuery('.prof-currency-change-form #cc_item_width').val(jQuery('#item-width input').val());
	jQuery('.prof-currency-change-form #cc_item_handle_drop').val(jQuery('#item-handle-drop input').val());
	jQuery('.prof-currency-change-form #cc_item_exterior_material').val(jQuery('#item-exterior-material input').val());
	jQuery('.prof-currency-change-form #cc_item_interior_material').val(jQuery('#item-interior-material input').val());
	jQuery('.prof-currency-change-form #cc_item_hardware').val(jQuery('#item-hardware input').val());
	jQuery('.prof-currency-change-form #cc_item_colour').val(jQuery('#item-colour select').val());
	jQuery('.prof-currency-change-form #cc_item_style').val(jQuery('#item-style select').val());
	jQuery('.prof-currency-change-form #cc_item_size').val(jQuery('#item-size select').val());
	jQuery('.prof-currency-change-form #cc_item_heel_size').val(jQuery('#item-heel-size input').val());
	jQuery('.prof-currency-change-form #cc_item_case_diameter').val(jQuery('#item-case-diameter input').val());
	jQuery('.prof-currency-change-form #cc_item_watch_bracelet_size').val(jQuery('#item-watch-bracelet-size input').val());
	jQuery('.prof-currency-change-form #cc_item_movement_type').val(jQuery('#item-movement-type input').val());
	jQuery('.prof-currency-change-form #cc_item_upper_material').val(jQuery('#item-upper-material input').val());
	jQuery('.prof-currency-change-form #cc_item_lining_material').val(jQuery('#item-lining-material input').val());
	jQuery('.prof-currency-change-form #cc_item_sole_material').val(jQuery('#item-sole-material input').val());
	jQuery('.prof-currency-change-form #cc_item_bracelet_material').val(jQuery('#item-bracelet-material input').val());
	jQuery('.prof-currency-change-form #cc_item_case_material').val(jQuery('#item-case-material input').val());
	jQuery('.prof-currency-change-form #cc_item_ring_size').val(jQuery('#item-ring-size select').val());
	jQuery('.prof-currency-change-form #cc_item_ring_width').val(jQuery('#item-ring-width input').val());
	jQuery('.prof-currency-change-form #cc_item_ring_height').val(jQuery('#item-ring-height input').val());
	jQuery('.prof-currency-change-form #cc_item_necklaces_length').val(jQuery('#item-necklaces-length input').val());
	jQuery('.prof-currency-change-form #cc_item_earring_width').val(jQuery('#item-earring-width input').val());
	jQuery('.prof-currency-change-form #cc_item_earring_height').val(jQuery('#item-earring-height input').val());
	jQuery('.prof-currency-change-form #cc_item_bracelet_size').val(jQuery('#item-bracelet-size input').val());
	jQuery('.prof-currency-change-form #cc_item_bracelet_length').val(jQuery('#item-bracelet-length input').val());

	var iincludes = '';
	var isep = '';
	jQuery('.item-includes input:checked').each(function(){
		iincludes += isep + jQuery(this).val();
		isep = ';';
	});
	jQuery('.prof-currency-change-form #cc_item_includes').val(iincludes);

	jQuery('.prof-currency-change-form').submit();
}

function question_colorbox(hrefvar) {
	jQuery.colorbox({inline:true, href:'#'+hrefvar});
	return false;
}

function tlc_pricing_details_show(pid) {
	jQuery('.pricing-details .pricing-details-data').html(jQuery('.pricing-details-'+pid).html());
	jQuery('.pricing-search-results').hide();
	jQuery('.pricing-details').show();
}

function tlc_pricing_details_hide() {
	jQuery('.pricing-details').hide();
	jQuery('.pricing-search-results').show();
}

function tlc_edit_category_show(pid) {
	jQuery('.item-edit-category-'+pid).show();
	return false;
}

function tlc_edit_category_save(pid) {
	var scid = jQuery('.quotation-item-view .item-edit-category-'+pid+' select').val();
	var scname = jQuery('.quotation-item-view .item-edit-category-'+pid+' select option:selected').html();

	jQuery('.item-category-name-'+pid).html(scname);
	jQuery('.item-edit-category-'+pid).hide();
	jQuery.post(siteurl,
		{
			SellersAction: 'tlc_edit_item_category',
			post_id: pid,
			seller_cat_id: scid
		}
	);
}
