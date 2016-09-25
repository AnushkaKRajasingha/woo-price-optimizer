(function($){
	/*Available Dates Starts */
	function funcAvailableDates(){
		$action = localize_var.TextDomain+'_getavailabledates';
		
		var jqxhr = $.getJSON(localize_var.admin_ajaxurl+"?action="+$action, function (data) {
	        var $aoColumns = [
	                          { "sTitle": "Date [YYYY-MM-DD]","sName":"availabledate" ,"mData":"availabledate","sClass" : "col-md-2","bSortable":true  },
	                          { "sTitle": "Availability","sName":"isActive" ,"mData":"isActive" ,"sClass" :"status  incexc-action  col-md-1"},
	                          { "sTitle": "","sName":"uniqueid" ,"mData":"uniqueid","sClass" : "action col-xs-2","bSortable":false  }
	                          ];
	       var $fnRowCallback =  function( nRow, aData, iDisplayIndex ) {
	    	   jQuery('td.action',nRow).empty().append(_getAjaxActionButtons(aData,'data_table_avaDate',funcAvailableDates,prepToEdit_AvailableDates,'_addNewVideo','_copyavailabledates','_delavailabledates'));
	    	   _setStatusSwitch('_availabledates',aData,nRow);
	    };
	        _setupDataTable($('#data_table_avaDate'),$aoColumns,data,$fnRowCallback);
	        
		}).done(function () {}).fail(function () {}).always(function () {});
	    jqxhr.complete(function () { /* console.log("second complete");*/ }); 
	}
	function prepToEdit_AvailableDates(obj){
		_injectPageData(obj, 'frm_avalableDates');
		$('.dpd1').val(convertDate(obj.availabledate));
		$('.dpd2').val(convertDate(obj.availabledate));
		jQuery('#frm_avalableDates').animate({opacity:0.5}, 500,function(){ jQuery('#frm_avalableDates').css('background-color','rgba(169, 216, 110,0.5)'); jQuery('#frm_avalableDates').animate({opacity:1}, 1000);});
	}
	
	$('#btn_save_avaDate').click(function(){ 
	 	$_data = _getFieldData('frm_avalableDates');
		if($_data == null) return false;
		$action = localize_var.TextDomain+'_saveavailabledates';
		laodingBar.show();
		jQuery.post(
				localize_var.admin_ajaxurl +"?action="+$action,
				{				
					data : $_data
				},
				function( response ) {
					$_response = jQuery.parseJSON(response);
					jQuery('#data_table_avaDate').dataTable().fnDestroy();
					funcAvailableDates();
					formReset('frm_avalableDates');
					laodingBar.hide();
				}
			);
	});	
	
	$('#btn_reset_avaDate').click(function(){
		formReset('frm_avalableDates');
	});
	
	/*Available Dates Ends */	
	/*Day Surcharge Rate Starts */
	var $_uniqueField_dayofweek = [];
	function funcDaySurchargeRates(){
		$action = localize_var.TextDomain+'_getsurchargerates';
		
		var jqxhr = $.getJSON(localize_var.admin_ajaxurl+"?action="+$action, function (data) {
			_setSortOrderValue(data,$('input[name=sdsrSortOrder]'));
	        var $aoColumns = [
	                          { "sTitle": "Sort Order","sName":"sortOrder" ,"mData":"sortOrder","sClass" : "col-md-2","bSortable":true  },
	                          { "sTitle": "Day of Week","sName":"dayofweek" ,"mData":"dayofweek","sClass" : "col-md-2","mRender": function( data, type, full ) {
	                              $_uniqueField_dayofweek[data] = full.price; return data;                                      
	                          } },
	                          { "sTitle": "Price","sName":"price" ,"mData":"price" ,"sClass" :"currency col-md-1"},
	                          { "sTitle": "Active","sName":"isActive" ,"mData":"isActive" ,"sClass" :"status  incexc-action   col-md-1"},
	                          { "sTitle": "","sName":"uniqueid" ,"mData":"uniqueid","sClass" : "action col-xs-2","bSortable":false  }
	                          ];
	       var $fnRowCallback =  function( nRow, aData, iDisplayIndex ) {
	    	   jQuery('td.action',nRow).empty().append(_getAjaxActionButtons(aData,'data_table_surchargerate',funcDaySurchargeRates,prepToEdit_SurchargeRates,'_addNewVideo','_copysurchargerates','_delsurchargerates'));
	    	   _setStatusSwitch('_surchargerates',aData,nRow);
	    	   //jQuery('td.status',nRow).empty().append(_getStateLable(aData.isActive));
	    	   
	    };
	        _setupDataTable($('#data_table_surchargerate'),$aoColumns,data,$fnRowCallback);
	        
		}).done(function () {}).fail(function () {}).always(function () {});
	    jqxhr.complete(function () { /* console.log("second complete");*/ }); 
	}
	function prepToEdit_SurchargeRates(obj){
		_injectPageData(obj, 'frm_daySurchargeRate');		
		jQuery('#frm_daySurchargeRate').animate({opacity:0.5}, 500,function(){ jQuery('#frm_daySurchargeRate').css('background-color','rgba(169, 216, 110,0.5)'); jQuery('#frm_daySurchargeRate').animate({opacity:1}, 1000);});
	}
	
	$('#btn_save_surchargerate').click(function(){ 
		if($('select#sdsrdayofweek').val() in $_uniqueField_dayofweek && (parseFloat($_uniqueField_dayofweek[$('select#sdsrdayofweek').val()]) == parseFloat($('input[name=sdsrPrice]').val()) )){
			$modalMsg[999] = [ 'Warning', 'Value '+$('select#sdsrdayofweek').val()+' is already in database.' ];
			displayMsg('modal' + jQuery('#page_key').val(), 999);
			return false;
		}
	 	$_data = _getFieldData('frm_daySurchargeRate');
		if($_data == null) return false;
		$action = localize_var.TextDomain+'_savesurchargerates';
		laodingBar.show();
		jQuery.post(
				localize_var.admin_ajaxurl +"?action="+$action,
				{				
					data : $_data
				},
				function( response ) {
					checkResponseDbError(response);
					$_response = jQuery.parseJSON(response);
					jQuery('#data_table_surchargerate').dataTable().fnDestroy();
					funcDaySurchargeRates();
					formReset('frm_daySurchargeRate');
					laodingBar.hide();
				}
			);
	});	
	
	$('#btn_reset_surchargerate').click(function(){
		formReset('frm_daySurchargeRate');
	});
	/*Day Surcharge Rate Ends */
	
	/*Square Footage Pricing Starts */
	function funclocZipcodes(){
		$action = localize_var.TextDomain+'_getservareapricing';
		
		var jqxhr = $.getJSON(localize_var.admin_ajaxurl+"?action="+$action, function (data) {
			_setSortOrderValue(data,$('input[name=servareaSortOrder]'));
	        var $aoColumns = [
	                          { "sTitle": "Sort Order","sName":"sortOrder" ,"mData":"sortOrder","sClass" : "col-md-2","bSortable":true  },
	                          { "sTitle": "ZipCode","sName":"zipcode" ,"mData":"zipcode","sClass" :"col-md-2"},
	                        /*  { "sTitle": "Description","sName":"description" ,"mData":"description","sClass" :"col-md-2"},*/
	                          { "sTitle": "Status","sName":"isActive" ,"mData":"isActive" ,"sClass" :"status   incexc-action col-md-1"},
	                          { "sTitle": "","sName":"uniqueid" ,"mData":"uniqueid","sClass" : "action col-xs-2","bSortable":false  }
	                          ];
	       var $fnRowCallback =  function( nRow, aData, iDisplayIndex ) {
	    	   jQuery('td.action',nRow).empty().append(_getAjaxActionButtons(aData,'data_table_servareapricing',funclocZipcodes,prepToEdit_ServAreaPricing,'_addNewVideo','_copyservareapricing','_delservareapricing'));
	    	   _setStatusSwitch('_servareaPricing',aData,nRow);
	    	   //jQuery('td.status',nRow).empty().append(_getStateLable(aData.isActive));
	    };
	        _setupDataTable($('#data_table_servareapricing'),$aoColumns,data,$fnRowCallback);
	        
		}).done(function () {}).fail(function () {}).always(function () {});
	    jqxhr.complete(function () { /* console.log("second complete");*/ }); 
	}
	function prepToEdit_ServAreaPricing(obj){
		_injectPageData(obj, 'frm_ServareaPricing');
		jQuery('#frm_ServareaPricing').animate({opacity:0.5}, 500,function(){ jQuery('#frm_ServareaPricing').css('background-color','rgba(169, 216, 110,0.5)'); jQuery('#frm_ServareaPricing').animate({opacity:1}, 1000);});
	}
	
	$('#btn_save_servareapricing').click(function(){ 
	 	$_data = _getFieldData('frm_ServareaPricing');
		if($_data == null) return false;
		$action = localize_var.TextDomain+'_saveservareaprice';
		laodingBar.show();
		jQuery.post(
				localize_var.admin_ajaxurl +"?action="+$action,
				{				
					data : $_data
				},
				function( response ) {
					$_response = jQuery.parseJSON(response);
					jQuery('#data_table_servareapricing').dataTable().fnDestroy();
					funclocZipcodes();
					formReset('frm_ServareaPricing');
					laodingBar.hide();
				}
			);
	});	
	
	$('#btn_reset_servareapricing').click(function(){
	formReset('frm_ServareaPricing');
	});
	/*Square Footage Pricing Ends */	

	
	
	$(document).ready(function(){	
		$.fn.datepicker.defaults.format = "mm/dd/yyyy"; // "yyyy-mm-dd";
		var nowDate = new Date();
		var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
		//$('.dpYears').datepicker();
		var $_checkindate = $('.dpd1').datepicker({
			startDate: today,
	        onRender: function(date) {
	            return date.valueOf() < now.valueOf() ? 'disabled' : '';
	        }
	    });
		var $_checkoutdate = $('.dpd2').datepicker({
		    	startDate: today,
		        onRender: function(date) {
		            return date.valueOf() <= $_checkindate.date.valueOf() ? 'disabled' : '';
		        }
		    });
		 
		$_checkindate.on('changeDate', function(ev) {
	            if (ev.date.valueOf() > $_checkoutdate.data('datepicker').getDate().valueOf()) {
	                var newDate = new Date(ev.date)
	                newDate.setDate(newDate.getDate() + 1);
	                $_checkoutdate.data('datepicker').setDate(newDate);
	            }
			$_checkindate.data('datepicker').hide();
	            $('.dpd2')[0].focus();
	        }).data('datepicker');
		 
		 $_checkoutdate.on('changeDate', function(ev) {
			 $_checkoutdate.data('datepicker').hide();
	        }).data('datepicker');
		 
		funcAvailableDates();
		funcDaySurchargeRates();
		funclocZipcodes();
	});	
	
	
})(jQuery);
