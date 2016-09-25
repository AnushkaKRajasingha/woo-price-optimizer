(function($){
	/*Home Types Starts */	
	function funcHomeTypes(){
		$action = localize_var.TextDomain+'_gethometypes';
		var jqxhr = $.getJSON(localize_var.admin_ajaxurl+"?action="+$action, function (data) {
			_setSortOrderValue(data,$('input[name=initparamSortOrder]'));
	        var $aoColumns = [   
	                          { "sTitle": "Sort Order","sName":"sortOrder" ,"mData":"sortOrder","sClass" : "col-md-2","bSortable":true  },
	                          { "sTitle": "Building Type","sName":"hometype" ,"mData":"hometype","sClass" :"col-md-2"},
	                          { "sTitle": "Icon","sName":"uniqueid" ,"mData":"uniqueid" ,"sClass" :"icon-image col-md-1"},
	                          { "sTitle": "Active","sName":"isActive" ,"mData":"isActive" ,"sClass" :"status incexc-action col-md-1"},
	                          { "sTitle": "","sName":"uniqueid" ,"mData":"uniqueid","sClass" : "action col-xs-2","bSortable":false  }
	                          ];
	       var $fnRowCallback =  function( nRow, aData, iDisplayIndex ) {
	    	_setStatusSwitch('_hometypes',aData,nRow);
	       	jQuery('td.action',nRow).empty().append(_getAjaxActionButtons(aData,'data_table_initparamsHometype',funcHomeTypes,prepToEdit_HomeTypes,'_addNewVideo','_copyhometypes','_delhometypes'));
	    	jQuery('td.icon-image',nRow).empty().append(_getItemThumb(aData.iconImageUrl));
	    };
	    _setupDataTable($('#data_table_initparamsHometype'),$aoColumns,data,$fnRowCallback);
		}).done(function () {}).fail(function () {}).always(function () {});
	    jqxhr.complete(function () { /* console.log("second complete");*/ }); 
	}
	
	function prepToEdit_HomeTypes(obj){
		_injectPageData(obj, 'frm_hometypes');
		jQuery('#frm_hometypes').animate({opacity:0.5}, 500,function(){ jQuery('#frm_hometypes').css('background-color','rgba(169, 216, 110,0.5)'); jQuery('#frm_hometypes').animate({opacity:1}, 1000);});
	}	
	
	
	$('#btn_save_hometypes').click(function(){ 
		 	$_data = _getFieldData('frm_hometypes');
			if($_data == null) return false;
			$action = localize_var.TextDomain+'_savehometypes';
			laodingBar.show();
			jQuery.post(
					localize_var.admin_ajaxurl +"?action="+$action,
					{				
						data : $_data
					},
					function( response ) {
						_responseValidate_v2(response);
						//$_response = jQuery.parseJSON(response);
						$('#btn_reset_hometypes').trigger('click');
						laodingBar.hide();
					}
				);
		});	
	
	$('#btn_reset_hometypes').click(function(){
		formReset('frm_hometypes');
		jQuery('#data_table_initparamsHometype').dataTable().fnDestroy();
		funcHomeTypes();
	});
	/*Home Types Ends */	
	/*Frequency Starts */	
		function funcFrequency(){
		$action = localize_var.TextDomain+'_getfrequencies';
		var jqxhr = $.getJSON(localize_var.admin_ajaxurl+"?action="+$action, function (data) {
			_setSortOrderValue(data,$('input[name=initparamfrqSortOrder]'));
	        var $aoColumns = [   
	                          { "sTitle": "Sort Order","sName":"sortOrder" ,"mData":"sortOrder","sClass" : "col-md-1","bSortable":true  },
	                          { "sTitle": "Type of Cleaning","sName":"frequency" ,"mData":"frequency","sClass" :"col-md-2"},
	                          { "sTitle": "Icon","sName":"uniqueid" ,"mData":"uniqueid" ,"sClass" :"icon-image col-md-1"},
	                          { "sTitle": "Recurring","sName":"isRecurring" ,"mData":"isRecurring" ,"sClass" :"recurring col-md-1"},
	                          { "sTitle": "Active","sName":"isActive" ,"mData":"isActive" ,"sClass" :"status incexc-action col-md-1"},
	                          { "sTitle": "","sName":"uniqueid" ,"mData":"uniqueid","sClass" : "action col-xs-2","bSortable":false  }
	                          ];
	       var $fnRowCallback =  function( nRow, aData, iDisplayIndex ) {
	    	  
	    	   _setStatusSwitch('_frequencies',aData,nRow);   
	       	jQuery('td.action',nRow).empty().append(_getAjaxActionButtons(aData,'data_table_initparamFrequencies',funcFrequency,prepToEdit_Frequencies,'_addNewVideo','_copyfrequencies','_delfrequencies'));
	    	jQuery('td.recurring',nRow).empty().append(_getYesNoLable(aData.isRecurring));
	    	jQuery('td.icon-image',nRow).empty().append(_getItemThumb(aData.iconImageUrl));
	    	
	    };
	    _setupDataTable($('#data_table_initparamFrequencies'),$aoColumns,data,$fnRowCallback);
		}).done(function () {}).fail(function () {}).always(function () {});
	    jqxhr.complete(function () { /* console.log("second complete");*/ }); 
	}
	
	function prepToEdit_Frequencies(obj){
		_injectPageData(obj, 'frm_frequency');
		jQuery('#frm_frequency').animate({opacity:0.5}, 500,function(){ jQuery('#frm_frequency').css('background-color','rgba(169, 216, 110,0.5)'); jQuery('#frm_frequency').animate({opacity:1}, 1000);});
	}	
	
	
	$('#btn_save_initparamfrq').click(function(){ 
		 	$_data = _getFieldData('frm_frequency');
			if($_data == null) return false;
			$action = localize_var.TextDomain+'_savefrequencies';
			laodingBar.show();
			jQuery.post(
					localize_var.admin_ajaxurl +"?action="+$action,
					{				
						data : $_data
					},
					function( response ) {
						_responseValidate_v2(response);
						//$_response = jQuery.parseJSON(response);
						$('#btn_reset_initparamfrq').trigger('click');
						laodingBar.hide();
					}
				);
		});	
	
	$('#btn_reset_initparamfrq').click(function(){
		jQuery('#data_table_initparamFrequencies').dataTable().fnDestroy();
		funcFrequency();
		formReset('frm_frequency');
	});
	/*Frequency Ends */	
	/*Marketing Starts */
	function funcMarketing(){
		$action = localize_var.TextDomain+'_getmarketingref';
		
		var jqxhr = $.getJSON(localize_var.admin_ajaxurl+"?action="+$action, function (data) {
			_setSortOrderValue(data,$('input[name=mrSortOrder]'));
	        var $aoColumns = [
	                          { "sTitle": "Sort Order","sName":"sortOrder" ,"mData":"sortOrder","sClass" : "col-md-2","bSortable":true  },
	                          { "sTitle": "From","sName":"description" ,"mData":"description","sClass" : "col-md-2", },
	                          { "sTitle": "Active","sName":"isActive" ,"mData":"isActive" ,"sClass" :"status  incexc-action col-md-1"},
	                          { "sTitle": "","sName":"uniqueid" ,"mData":"uniqueid","sClass" : "action col-xs-2","bSortable":false  }
	                          ];
	       var $fnRowCallback =  function( nRow, aData, iDisplayIndex ) {
	    	   _setStatusSwitch('_marketingref',aData,nRow);   
	    	   jQuery('td.action',nRow).empty().append(_getAjaxActionButtons(aData,'data_table_marketingref',funcMarketing,prepToEdit_MarketingRefs,'_addNewVideo','_copymarketingref','_delmarketingref'));
		    	jQuery('td.icon-image',nRow).empty().append(_getItemThumb(aData.iconImageUrl));
	    };
	        _setupDataTable($('#data_table_marketingref'),$aoColumns,data,$fnRowCallback);
	        
		}).done(function () {}).fail(function () {}).always(function () {});
	    jqxhr.complete(function () { /* console.log("second complete");*/ }); 
	}
	function prepToEdit_MarketingRefs(obj){
		_injectPageData(obj, 'frm_marketingref');
		jQuery('#frm_marketingref').animate({opacity:0.5}, 500,function(){ jQuery('#frm_marketingref').css('background-color','rgba(169, 216, 110,0.5)'); jQuery('#frm_marketingref').animate({opacity:1}, 1000);});
	}
	
	$('#btn_save_marketingref').click(function(){ 
	 	$_data = _getFieldData('frm_marketingref');
		if($_data == null) return false;
		$action = localize_var.TextDomain+'_savemarketingref';
		laodingBar.show();
		jQuery.post(
				localize_var.admin_ajaxurl +"?action="+$action,
				{				
					data : $_data
				},
				function( response ) {
					_responseValidate_v2(response);
					//$_response = jQuery.parseJSON(response);
					$('#btn_reset_marketingref').trigger('click');
					laodingBar.hide();
				}
			);
	});	
	
	$('#btn_reset_marketingref').click(function(){
		formReset('frm_marketingref');
		jQuery('#data_table_marketingref').dataTable().fnDestroy();
		funcMarketing();
	});
	/*Marketing Ends */	
	
	$(document).ready(function(){
		
		OpenMideaLibDialog($('input.customurls'));	
		funcHomeTypes();
		funcFrequency();
		funcMarketing();
		
		//wysihtml5 start
		$('.wysihtml5').wysihtml5();
		//wysihtml5 end
		
		
		
		$('.btn_save_syssettings').click(function(){
			var $form_data = {};
			$('.frm_sysSettings').each(function(){				
					$_data = _getFieldData($(this)[0].id);
					if($_data == null){
						//displayMsg('modal' + jQuery('#page_key').val(), 9);
						//return false;
					}
					$.extend($form_data,$_data);
			});
			
			
			
			if($form_data.stripeapistatus != undefined){
			$action = localize_var.TextDomain+'_savesyssettings';
			laodingBar.show();
			jQuery.post(
					localize_var.admin_ajaxurl +"?action="+$action,
					{				
						data : $form_data
					},
					function( response ) {
						//$_response = jQuery.parseJSON(response);
						laodingBar.hide();
					}
				);
			}
			
		});		
	});	
	
	$('input[type=radio][name=sysInfusionsoftApiStatus]').change(function(){
		$('li#isapifieldstab').toggleClass('disabled');
	});
	
	$('button#btn_mapisfield').click(function(){
		console.log('Map it button clicked.');
		$sel_mqisfield_opt = $('select#sel_mqisfields option:selected');
		$uiniqueid =  $sel_mqisfield_opt.data('class')+$sel_mqisfield_opt.val();
		if($('#cntr_'+$uiniqueid).length > 0){
			console.log('Field already exists');
			displayMsg('modal' + jQuery('#page_key').val(), 10);
			return false;
		}
		$dataFieldName =  $sel_mqisfield_opt.data('class')+'.'+$sel_mqisfield_opt.val();
		$ismapfield = $($ismapfield_temp);
		$ismapfield.attr({
			id : 'cntr_'+$uiniqueid
		});
		$ismapfield.find('input.form-control').attr({
			value : $('input#isfieldname').val(),
			id : 'mqisfields.'+ $uiniqueid,
			placeholder : $sel_mqisfield_opt[0].text,
			value : $('select#isfieldname').val()
		})
		.data('uniqueid',$uiniqueid)
		.data('field-name','mqisfields.'+$dataFieldName);
		$ismapfield.find('span.input-group-addon.field-desc')[0].innerHTML = $sel_mqisfield_opt[0].text;
		$ismapfield.find('span.btnisfieldremove')
		.attr({id:'btnisfieldremove_'+$uiniqueid})
		.data('uniqueid',$uiniqueid)		
		.click(removemapfield);
		
		$ismapfield.find('span.btnisfieldremove > i.tooltips').tooltip();
		$('div#is_mapped_field_ctr').append($ismapfield);
		$('input#isfieldname').val('');
	});
	
	$('span.btnisfieldremove').click(removemapfield);
	
	function removemapfield(){
		$('#cntr_'+$(this).data('uniqueid')).remove();
	}	
	$('input[type=radio][name=sysStripeApitype]').change(function(){
		$('div.valstripeapitype').toggleClass('hidden');
	});
	
	
	/* Genarate export codes */
	
	$('.btn_export_syssettings').click(function(){
		$action = localize_var.TextDomain+'_gen_syssetexpcode';
		laodingBar.show();
		jQuery.post(
				localize_var.admin_ajaxurl +"?action="+$action,
				{},
				function( response ) {
					$('#impexp_sysset').val(response);
					laodingBar.hide();
				}
			);
	});	
	
	$('.btn_export_pricesettings').click(function(){
		$action = localize_var.TextDomain+'_gen_pricesetexpcode';
		laodingBar.show();
		jQuery.post(
				localize_var.admin_ajaxurl +"?action="+$action,
				{},
				function( response ) {
					$('#impexp_pricset').val(response);
					laodingBar.hide();
				}
			);
	});	
	
	$('.btn_export_srvavailability').click(function(){
		$action = localize_var.TextDomain+'_gen_servavailexpcode';
		laodingBar.show();
		jQuery.post(
				localize_var.admin_ajaxurl +"?action="+$action,
				{},
				function( response ) {
					
					$('#impexp_srvavil').val(response);
					laodingBar.hide();
				}
			);
	});	
	
	/* Import setting from code */ 
	$('.btn_import_syssettings').click(function(){
		var $_data = {};
		$_data['impexp_sysset'] = $('#impexp_sysset').val();
		if($_data == null) return false;
		
		$action = localize_var.TextDomain+'_import_sysset';
		laodingBar.show();
		jQuery.post(
				localize_var.admin_ajaxurl +"?action="+$action,
				{data : $_data},
				function( response ) {					
					laodingBar.hide();
				}
			);
	});	
	
	$('.btn_import_pricesettings').click(function(){
		var $_data = {};
		$_data['impexp_pricset'] = $('#impexp_pricset').val();
		if($_data == null) return false;
		
		$action = localize_var.TextDomain+'_import_priceset';
		laodingBar.show();
		jQuery.post(
				localize_var.admin_ajaxurl +"?action="+$action,
				{data : $_data},
				function( response ) {					
					laodingBar.hide();
				}
			);
	});
	
	$('.btn_import_srvavailability').click(function(){
		var $_data = {};
		$_data['impexp_srvavil'] = $('#impexp_srvavil').val();
		if($_data == null) return false;
		
		$action = localize_var.TextDomain+'_import_srvavil';
		laodingBar.show();
		jQuery.post(
				localize_var.admin_ajaxurl +"?action="+$action,
				{data : $_data},
				function( response ) {					
					laodingBar.hide();
				}
			);
	});
	
	
	
})(jQuery);
