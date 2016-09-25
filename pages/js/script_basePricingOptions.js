(function($){
	/*Start Pricing Starts */	
	function funcStartPricing(){
		$action = localize_var.TextDomain+'_getstartpricing';
		var jqxhr = $.getJSON(localize_var.admin_ajaxurl+"?action="+$action, function (data) {
			_setSortOrderValue(data,$('input[name=busspSortOrder]'));
	        var $aoColumns = [   
	                          { "sTitle": "Sort Order","sName":"sortOrder" ,"mData":"sortOrder","sClass" : "col-md-2","bSortable":true  },
	                          { "sTitle": "Building Type","sName":"hometype" ,"mData":"hometypetext","sClass" :"col-md-2"},
	                          { "sTitle": "Type of Cleaning","sName":"frequency" ,"mData":"frequencytext","sClass" :"col-md-2"},	                          
	                          { "sTitle": "Price","sName":"price" ,"mData":"price" ,"sClass" :"currency col-md-1"},
	                          { "sTitle": "Active","sName":"isActive" ,"mData":"isActive" ,"sClass" :"status incexc-action col-md-1"},
	                          { "sTitle": "","sName":"uniqueid" ,"mData":"uniqueid","sClass" : "action col-xs-2","bSortable":false  }
	                          ];
	       var $fnRowCallback =  function( nRow, aData, iDisplayIndex ) {
	       	jQuery('td.action',nRow).empty().append(_getAjaxActionButtons(aData,'data_table_startpricing',funcStartPricing,prepToEdit_StartPricing,'_addNewVideo','_copystartpricing','_delstartpricing'));
	       	_setStatusSwitch('_startPricing',aData,nRow);
	    };
	    _setupDataTable($('#data_table_startpricing'),$aoColumns,data,$fnRowCallback);
		}).done(function () {}).fail(function () {}).always(function () {});
	    jqxhr.complete(function () { /* console.log("second complete");*/ }); 
	}
	
	function prepToEdit_StartPricing(obj){
		_injectPageData(obj, 'frm_startpricing');
		jQuery('#frm_startpricing').animate({opacity:0.5}, 500,function(){ jQuery('#frm_startpricing').css('background-color','rgba(169, 216, 110,0.5)'); jQuery('#frm_startpricing').animate({opacity:1}, 1000);});
	}	
	
	
	$('#btn_save_startpricing').click(function(){ 
		 	$_data = _getFieldData('frm_startpricing');
			if($_data == null) return false;
			$action = localize_var.TextDomain+'_savestartprice';
			laodingBar.show();
			jQuery.post(
					localize_var.admin_ajaxurl +"?action="+$action,
					{				
						data : $_data
					},
					function( response ) {
						$_response = jQuery.parseJSON(response);
						$('#btn_reset_startpricing').trigger('click');
						laodingBar.hide();
					}
				);
		});	
	
	$('#btn_reset_startpricing').click(function(){
		formReset('frm_startpricing');
		jQuery('#data_table_startpricing').dataTable().fnDestroy();
		funcStartPricing();
	});
	/*Start Pricing Ends */	
	
	/* First-Time Price Starts Here */
	function FitsrTimePriceSetup(){}
	
	$('#btn_save_firsttimeprices').click(function(){
		$_data = _getFieldData('frm_psfirsttimeprices');
		if($_data == null) return false;
		$action = localize_var.TextDomain+'_savefirsttimeprices';
		laodingBar.show();
		jQuery.post(
				localize_var.admin_ajaxurl +"?action="+$action,
				{				
					data : $_data
				},
				function( response ) {
					$_response = jQuery.parseJSON(response);
					laodingBar.hide();
				}
			);
	});
	
	/* First time Price Ends */
	
	/*Bedroom Pricing Starts */
	function funcBedroomPricing(){
		$action = localize_var.TextDomain+'_getbedroompricing';
		
		var jqxhr = $.getJSON(localize_var.admin_ajaxurl+"?action="+$action, function (data) {
			_setSortOrderValue(data,$('input[name=brSortOrder]'));
	        var $aoColumns = [
	                          { "sTitle": "Sort Order","sName":"sortOrder" ,"mData":"sortOrder","sClass" : "col-md-2","bSortable":true  },
	                          { "sTitle": "# of Bedrooms","sName":"brcount" ,"mData":"brcount","sClass" :"col-md-2" },
	                          { "sTitle": "","sName":"description" ,"mData":"description","sClass" :"col-md-2"},	                          
	                          { "sTitle": "Price","sName":"price" ,"mData":"price" ,"sClass" :"currency col-md-1"},
	                          { "sTitle": "Active","sName":"isActive" ,"mData":"isActive" ,"sClass" :"status incexc-action col-md-1"},
	                          { "sTitle": "","sName":"uniqueid" ,"mData":"uniqueid","sClass" : "action col-xs-2","bSortable":false  }
	                          ];
	       var $fnRowCallback =  function( nRow, aData, iDisplayIndex ) {
	    	   jQuery('td.action',nRow).empty().append(_getAjaxActionButtons(aData,'data_table_bedroompricing',funcBedroomPricing,prepToEdit_BedroomPricing,'_addNewVideo','_copybedroompricing','_delbedroompricing'));
	    	   _setStatusSwitch('_bedroomPricing',aData,nRow);	
	    };
	        _setupDataTable($('#data_table_bedroompricing'),$aoColumns,data,$fnRowCallback);
	        
		}).done(function () {}).fail(function () {}).always(function () {});
	    jqxhr.complete(function () { /* console.log("second complete");*/ }); 
	}
	function prepToEdit_BedroomPricing(obj){
		_injectPageData(obj, 'frm_BedroomPricing');
		jQuery('#frm_BedroomPricing').animate({opacity:0.5}, 500,function(){ jQuery('#frm_BedroomPricing').css('background-color','rgba(169, 216, 110,0.5)'); jQuery('#frm_BedroomPricing').animate({opacity:1}, 1000);});
	}
	
	$('#btn_save_bedroompricing').click(function(){ 
	 	$_data = _getFieldData('frm_BedroomPricing');
		if($_data == null) return false;
		$action = localize_var.TextDomain+'_savebedroomprice';
		laodingBar.show();
		jQuery.post(
				localize_var.admin_ajaxurl +"?action="+$action,
				{				
					data : $_data
				},
				function( response ) {
					$_response = jQuery.parseJSON(response);
					$('#btn_reset_bedroompricing').trigger('click');
					laodingBar.hide();
				}
			);
	});	

$('#btn_reset_bedroompricing').click(function(){
	formReset('frm_BedroomPricing');
	jQuery('#data_table_bedroompricing').dataTable().fnDestroy();
	funcBedroomPricing();
});
	
	/*Bedroom Pricing Ends */
	/*Bathroom Pricing Starts */
	function funcBathroomPricing(){
		$action = localize_var.TextDomain+'_getbathroompricing';
		
		var jqxhr = $.getJSON(localize_var.admin_ajaxurl+"?action="+$action, function (data) {
			_setSortOrderValue(data,$('input[name=bathrSortOrder]'));
	        var $aoColumns = [
	                          { "sTitle": "Sort Order","sName":"sortOrder" ,"mData":"sortOrder","sClass" : "col-md-2","bSortable":true  },
	                          { "sTitle": "# of Bathrooms","sName":"bathrcount" ,"mData":"bathrcount","sClass" :"col-md-2" },
	                          { "sTitle": "","sName":"description" ,"mData":"description","sClass" :"col-md-2"},	                          
	                          { "sTitle": "Price","sName":"price" ,"mData":"price" ,"sClass" :"currency col-md-1"},
	                          { "sTitle": "Active","sName":"isActive" ,"mData":"isActive" ,"sClass" :"status incexc-action col-md-1"},
	                          { "sTitle": "","sName":"uniqueid" ,"mData":"uniqueid","sClass" : "action col-xs-2","bSortable":false  }
	                          ];
	       var $fnRowCallback =  function( nRow, aData, iDisplayIndex ) {
	    	   jQuery('td.action',nRow).empty().append(_getAjaxActionButtons(aData,'data_table_bathroompricing',funcBathroomPricing,prepToEdit_BathroomPricing,'_addNewVideo','_copybathroompricing','_delbathroompricing'));
	    	   _setStatusSwitch('_bathroomPricing',aData,nRow);
	    };
	        _setupDataTable($('#data_table_bathroompricing'),$aoColumns,data,$fnRowCallback);
	        
		}).done(function () {}).fail(function () {}).always(function () {});
	    jqxhr.complete(function () { /* console.log("second complete");*/ }); 
	}
	function prepToEdit_BathroomPricing(obj){
		_injectPageData(obj, 'frm_BathroomPricing');
		jQuery('#frm_BathroomPricing').animate({opacity:0.5}, 500,function(){ jQuery('#frm_BathroomPricing').css('background-color','rgba(169, 216, 110,0.5)'); jQuery('#frm_BathroomPricing').animate({opacity:1}, 1000);});
	}
	
	$('#btn_save_bathroompricing').click(function(){ 
	 	$_data = _getFieldData('frm_BathroomPricing');
		if($_data == null) return false;
		$action = localize_var.TextDomain+'_savebathroomprice';
		laodingBar.show();
		jQuery.post(
				localize_var.admin_ajaxurl +"?action="+$action,
				{				
					data : $_data
				},
				function( response ) {
					$_response = jQuery.parseJSON(response);
					$('#btn_reset_bathroompricing').trigger('click');
					laodingBar.hide();
				}
			);
	});	
	
	$('#btn_reset_bathroompricing').click(function(){
	formReset('frm_BathroomPricing');
	jQuery('#data_table_bathroompricing').dataTable().fnDestroy();
	funcBathroomPricing();
	});
	
	/*Bathroom Pricing Ends */
	/*Frequency Pricing Starts */
	function funcFrequencyPricing(){
		$action = localize_var.TextDomain+'_getfrqpricing';
		
		var jqxhr = $.getJSON(localize_var.admin_ajaxurl+"?action="+$action, function (data) {
			_setSortOrderValue(data,$('input[name=frqSortOrder]'));
	        var $aoColumns = [
	                          { "sTitle": "Sort Order","sName":"sortOrder" ,"mData":"sortOrder","sClass" : "col-md-2","bSortable":true  },
	                          { "sTitle": "Description","sName":"description" ,"mData":"description","sClass" :"col-md-2"},	                          
	                          { "sTitle": "Price","sName":"price" ,"mData":"price" ,"sClass" :"currency col-md-1"},
	                          { "sTitle": "Active","sName":"isActive" ,"mData":"isActive" ,"sClass" :"status incexc-action  col-md-1"},
	                          { "sTitle": "","sName":"uniqueid" ,"mData":"uniqueid","sClass" : "action col-xs-2","bSortable":false  }
	                          ];
	       var $fnRowCallback =  function( nRow, aData, iDisplayIndex ) {
	    	   jQuery('td.action',nRow).empty().append(_getAjaxActionButtons(aData,'data_table_frqpricing',funcFrequencyPricing,prepToEdit_FrqPricing,'_addNewVideo','_copyfrqpricing','_delfrqpricing'));
	    	   _setStatusSwitch('_frqPricing',aData,nRow);
	    };
	        _setupDataTable($('#data_table_frqpricing'),$aoColumns,data,$fnRowCallback);
	        
		}).done(function () {}).fail(function () {}).always(function () {});
	    jqxhr.complete(function () { /* console.log("second complete");*/ }); 
	}
	function prepToEdit_FrqPricing(obj){
		_injectPageData(obj, 'frm_FrqPricing');
		jQuery('#frm_FrqPricing').animate({opacity:0.5}, 500,function(){ jQuery('#frm_FrqPricing').css('background-color','rgba(169, 216, 110,0.5)'); jQuery('#frm_FrqPricing').animate({opacity:1}, 1000);});
	}
	
	$('#btn_save_frqpricing').click(function(){ 
	 	$_data = _getFieldData('frm_FrqPricing');
		if($_data == null) return false;
		$action = localize_var.TextDomain+'_savefrqprice';
		laodingBar.show();
		jQuery.post(
				localize_var.admin_ajaxurl +"?action="+$action,
				{				
					data : $_data
				},
				function( response ) {
					$_response = jQuery.parseJSON(response);
					$('#btn_reset_frqpricing').trigger('click');
					laodingBar.hide();
				}
			);
	});	
	
	$('#btn_reset_frqpricing').click(function(){
	formReset('frm_FrqPricing');
	jQuery('#data_table_frqpricing').dataTable().fnDestroy();
	funcFrequencyPricing();
	});
	/*Frequency Pricing Ends */
	/*Pet Pricing Starts */
	function funcPetPricing(){
		$action = localize_var.TextDomain+'_getpetpricing';
		
		var jqxhr = $.getJSON(localize_var.admin_ajaxurl+"?action="+$action, function (data) {
			_setSortOrderValue(data,$('input[name=petSortOrder]'));
	        var $aoColumns = [
	                          { "sTitle": "Sort Order","sName":"sortOrder" ,"mData":"sortOrder","sClass" : "col-md-2","bSortable":true  },
	                          { "sTitle": "Description","sName":"description" ,"mData":"description","sClass" :"col-md-2"},	                          
	                          { "sTitle": "Price","sName":"price" ,"mData":"price" ,"sClass" :"currency col-md-1"},
	                          { "sTitle": "Active","sName":"isActive" ,"mData":"isActive" ,"sClass" :"status incexc-action col-md-1"},
	                          { "sTitle": "","sName":"uniqueid" ,"mData":"uniqueid","sClass" : "action col-xs-2","bSortable":false  }
	                          ];
	       var $fnRowCallback =  function( nRow, aData, iDisplayIndex ) {
	    	   jQuery('td.action',nRow).empty().append(_getAjaxActionButtons(aData,'data_table_petpricing',funcPetPricing,prepToEdit_PetPricing,'_addNewVideo','_copypetpricing','_delpetpricing'));
	    	   _setStatusSwitch('_petPricing',aData,nRow);
	    };
	        _setupDataTable($('#data_table_petpricing'),$aoColumns,data,$fnRowCallback);
	        
		}).done(function () {}).fail(function () {}).always(function () {});
	    jqxhr.complete(function () { /* console.log("second complete");*/ }); 
	}
	function prepToEdit_PetPricing(obj){
		_injectPageData(obj, 'frm_PetPricing');
		jQuery('#frm_PetPricing').animate({opacity:0.5}, 500,function(){ jQuery('#frm_PetPricing').css('background-color','rgba(169, 216, 110,0.5)'); jQuery('#frm_PetPricing').animate({opacity:1}, 1000);});
	}
	
	$('#btn_save_petpricing').click(function(){ 
	 	$_data = _getFieldData('frm_PetPricing');
		if($_data == null) return false;
		$action = localize_var.TextDomain+'_savepetprice';
		laodingBar.show();
		jQuery.post(
				localize_var.admin_ajaxurl +"?action="+$action,
				{				
					data : $_data
				},
				function( response ) {
					$_response = jQuery.parseJSON(response);
					$('#btn_reset_petpricing').trigger('click');
					laodingBar.hide();
				}
			);
	});	
	
	$('#btn_reset_petpricing').click(function(){
	formReset('frm_PetPricing');
	jQuery('#data_table_petpricing').dataTable().fnDestroy();
	funcPetPricing();
	});
	/*Pet Pricing Ends */	
	
	/*Square Footage Pricing Starts */
	function funcSquareFootagePricing(){
		$action = localize_var.TextDomain+'_getsqfpricing';
		
		var jqxhr = $.getJSON(localize_var.admin_ajaxurl+"?action="+$action, function (data) {
			 _setSortOrderValue(data,$('input[name=sqfSortOrder]'));
	        var $aoColumns = [
	                          { "sTitle": "Sort Order","sName":"sortOrder" ,"mData":"sortOrder","sClass" : "col-md-2","bSortable":true  },
	                        //  { "sTitle": "Home Type","sName":"hometype" ,"mData":"hometypetext","sClass" :"col-md-2"},
	                          { "sTitle": "Type of Cleaning","sName":"frequency" ,"mData":"frequencytext","sClass" :"col-md-2"},
	                          { "sTitle": "Description","sName":"description" ,"mData":"description","sClass" :"col-md-2"},	                          
	                          { "sTitle": "Price","sName":"price" ,"mData":"price" ,"sClass" :"currency col-md-1"},
	                          { "sTitle": "Active","sName":"isActive" ,"mData":"isActive" ,"sClass" :"status incexc-action col-md-1"},
	                          { "sTitle": "","sName":"uniqueid" ,"mData":"uniqueid","sClass" : "action col-xs-2","bSortable":false  }
	                          ];
	       var $fnRowCallback =  function( nRow, aData, iDisplayIndex ) {
	    	   jQuery('td.action',nRow).empty().append(_getAjaxActionButtons(aData,'data_table_sqfpricing',funcSquareFootagePricing,prepToEdit_SqfPricing,'_addNewVideo','_copysqfpricing','_delsqfpricing'));
	    	   _setStatusSwitch('_sqfootagePricing',aData,nRow);
	    };
	        _setupDataTable($('#data_table_sqfpricing'),$aoColumns,data,$fnRowCallback);
	        
		}).done(function () {}).fail(function () {}).always(function () {});
	    jqxhr.complete(function () { /* console.log("second complete");*/ }); 
	}
	function prepToEdit_SqfPricing(obj){
		_injectPageData(obj, 'frm_SqfPricing');
		jQuery('#frm_SqfPricing').animate({opacity:0.5}, 500,function(){ jQuery('#frm_SqfPricing').css('background-color','rgba(169, 216, 110,0.5)'); jQuery('#frm_SqfPricing').animate({opacity:1}, 1000);});
	}
	
	$('#btn_save_sqfpricing').click(function(){ 
	 	$_data = _getFieldData('frm_SqfPricing');
		if($_data == null) return false;
		$action = localize_var.TextDomain+'_savesqfprice';
		laodingBar.show();
		jQuery.post(
				localize_var.admin_ajaxurl +"?action="+$action,
				{				
					data : $_data
				},
				function( response ) {
					checkResponseDbError(response);
					$_response = jQuery.parseJSON(response);
					$('#btn_reset_sqfpricing').trigger('click');
					laodingBar.hide();
				}
			);
	});	
	
	$('#btn_reset_sqfpricing').click(function(){
	formReset('frm_SqfPricing');
	jQuery('#data_table_sqfpricing').dataTable().fnDestroy();
	funcSquareFootagePricing();
	});
	
	$('#sqfHouseType').change(function(){
		$('#sqfFrequency > option').remove();
		$action = localize_var.TextDomain+'_getStartPricingFrequency';
		laodingBar.show();
		jQuery.post(
				localize_var.admin_ajaxurl +"?action="+$action,
				{				
					hometype : $(this).val()
				},
				function( response ) {
					
					$_response = jQuery.parseJSON(response);
					if ($_response.error != undefined) {
						$modalMsg[999] = [ 'Warning', $_response.error ];
						displayMsg('modal' + jQuery('#page_key').val(), 999);
						return false;
					}
					 $.each($_response, function(i, value) {
				            $('#sqfFrequency').append($('<option>').text(value.frequencytext).attr('value', value.frequency));
				      });
					laodingBar.hide();
				}
			);
	});
	
	
	/*Square Footage Pricing Ends */
	
	/*Additional Service Pricing Starts */
	function funcAdditionalServises(){
		$action = localize_var.TextDomain+'_getadditionalserv';
		
		var jqxhr = $.getJSON(localize_var.admin_ajaxurl+"?action="+$action, function (data) {
			_setSortOrderValue(data,$('input[name=asSortOrder]'));
	        var $aoColumns = [
	                          { "sTitle": "Sort Order","sName":"sortOrder" ,"mData":"sortOrder","sClass" : "col-md-2","bSortable":true  },
	                          { "sTitle": "Service Name","sName":"description" ,"mData":"description","sClass" : "col-md-2", },
	                          { "sTitle": "Type of Cleaning","sName":"frequency" ,"mData":"frequencytext","sClass" :"col-md-2"},
	                          { "sTitle": "Icon","sName":"uniqueid" ,"mData":"uniqueid" ,"sClass" :"icon-image col-md-1"},
	                          { "sTitle": "Price","sName":"price" ,"mData":"price" ,"sClass" :"currency col-md-1"},
	                          { "sTitle": "Active","sName":"isActive" ,"mData":"isActive" ,"sClass" :"status incexc-action col-md-1"},
	                          { "sTitle": "","sName":"uniqueid" ,"mData":"uniqueid","sClass" : "action col-xs-2","bSortable":false  }
	                          ];
	       var $fnRowCallback =  function( nRow, aData, iDisplayIndex ) {
	    	   jQuery('td.action',nRow).empty().append(_getAjaxActionButtons(aData,'data_table_additionalserv',funcAdditionalServises,prepToEdit_SurchargeRates,'_addNewVideo','_copyadditionalserv','_deladditionalserv'));
	    	   _setStatusSwitch('_additionalserv',aData,nRow);	    	   
		    	jQuery('td.icon-image',nRow).empty().append(_getItemThumb(aData.iconImageUrl));
	    };
	        _setupDataTable($('#data_table_additionalserv'),$aoColumns,data,$fnRowCallback);
	        
		}).done(function () {}).fail(function () {}).always(function () {});
	    jqxhr.complete(function () { /* console.log("second complete");*/ }); 
	}
	function prepToEdit_SurchargeRates(obj){
		_injectPageData(obj, 'frm_additionalserv');
		jQuery('#frm_additionalserv').animate({opacity:0.5}, 500,function(){ jQuery('#frm_additionalserv').css('background-color','rgba(169, 216, 110,0.5)'); jQuery('#frm_additionalserv').animate({opacity:1}, 1000);});
	}
	
	$('#btn_save_additionalserv').click(function(){ 
	 	$_data = _getFieldData('frm_additionalserv');
		if($_data == null) return false;
		$action = localize_var.TextDomain+'_saveadditionalserv';
		laodingBar.show();
		jQuery.post(
				localize_var.admin_ajaxurl +"?action="+$action,
				{				
					data : $_data
				},
				function( response ) {
					$_response = jQuery.parseJSON(response);
					$('#btn_reset_additionalserv').trigger('click');
					laodingBar.hide();
				}
			);
	});	
	
	$('#btn_reset_additionalserv').click(function(){
		formReset('frm_additionalserv');
		jQuery('#data_table_additionalserv').dataTable().fnDestroy();
		funcAdditionalServises();
	});
	
	/*Additional Service Pricing Ends */	
	
	
	var url = document.location.toString();
	if (url.match('#')) {
	    $('.nav-tabs a[href=#'+url.split('#')[1]+']').tab('show') ;
	} 
	// Change hash for page-reload
	$('.nav-tabs a').on('shown', function (e) {
	    window.location.hash = e.target.hash;
	})
	
	$(document).ready(function(){
		$('input.custom-time').on('blur',function(){
			$(this).prev().val($(this).val());
		});
		
		OpenMideaLibDialog($('input.customurls'));	
		funcStartPricing();
		setTimeout(function(){
		funcBedroomPricing();
		funcBathroomPricing();
		funcFrequencyPricing();
		funcPetPricing();
		funcSquareFootagePricing();
		funcAdditionalServises();
		},3000);
	});	
	
	
})(jQuery);
