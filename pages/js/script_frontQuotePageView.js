(function($){	
	
	
	$(document).ready(function(){	
		
		var _wizardStepsgetCurrentIndex = 0;
		
		var _wizardSteps = $("#wizard").steps({
            headerTag: "h2",
            bodyTag: "section",
            transitionEffect: "slideLeft",
            titleTemplate: '<span class="number">#index#.</span><span class="chevron"></span> #title#',
            onStepChanged:wizard_onStepChanged ,
            enableFinishButton :false
        });
		
		
		if($('input#cardNumber').length > 0){
			 $('input#cardNumber').payment('formatCardNumber');
		}
		
		 $.fn.toggleInputError = function(erred) {
			 this.parent().parent('.form-group').toggleClass('has-error', erred);
		        return this;
		 };
		
		
		function wizard_onStepChanged(event, currentIndex, priorIndex) {
			_wizardStepsgetCurrentIndex = currentIndex;
           if(currentIndex == 5 && currentIndex > priorIndex){
        	  
           }
           
           //if(priorIndex == 2 && currentIndex == 3){
        	   $("ul[aria-label=Pagination]").hide();
          // }
        	   if(currentIndex == 2) ValidateTab2();
        	   if(currentIndex == 3) ValidateTab3();
        	  // if(currentIndex == 4)  $("ul[aria-label=Pagination]").show();
        	   if(currentIndex == 4){
        		   try { // add try because bugid#0003
        			    /*Google analatic event traking code - Event : InformationPrompt*/
        			   _gaTracking(2);
						console.log('InformationPrompt');
						/*Google analatic event traking code */
        		   }
       				catch(err) {console.log('Error in google analytic setup - InformationPrompt :'+err.message);}
        	   }
        	   
        		if(currentIndex == 3 && $('input[type=radio][name=maxsqft]').length > 0 && $('input[type=radio][name=maxsqft]:checked').val() == 1)
           		{
           		$("ul[aria-label=Pagination]").show();
           		}
        		
        		if(currentIndex == 4 && priorIndex == 3 && ($('input[type=radio][name=maxsqft]').length > 0 && $('input[type=radio][name=maxsqft]:checked').val() == 1)){
        			//laodingBar.show();
        			$action = localize_var.TextDomain+'_updatehourlyrate';
        	    	jQuery.post(
        					localize_var.admin_ajaxurl +"?action="+$action,
        					{				
        						uniqueid : $('#uniqueid').val(),
        						noofhours : $('input#numbofhours').val()
        					},
        					function( response ) {
        						$_response = jQuery.parseJSON(response);	    						
        						//laodingBar.hide();
        					}
        				);
        		}
        }
		
		$("ul[aria-label=Pagination]").hide();
		
		/* Set Quote Values */
		
		function quoteValuememberCallBack(){
			$action = localize_var.TextDomain+'_setQuoteValues';
			$propname = $(this).data('propname');
			$zipcode = $propname == 'servicearea' ? $(this).find('option:selected').text().trim() : ''; 
		//	laodingBar.show();
			$_uniqueid = $(this).val();
			if($_uniqueid == -1){
				if ($(this).attr('title')) {
					$modalMsg[998] = [ 'Warning', 'Please select a valid value for the "'+$(this).attr('title')+'".'];
				}
				else{
					$modalMsg[998] = [ 'Warning', 'Please select a valid value.'];
				}
				
				displayMsg('modal' + jQuery('#page_key').val(), 998);
				laodingBar.hide();
				$("ul[aria-label=Pagination]").hide();
				return false;
			}
			jQuery.post(
					localize_var.admin_ajaxurl +"?action="+$action,
					{	
						uniqueid : $('#uniqueid').val(),
						propUniqueid : $_uniqueid,
						propname : $propname
					},
					function( response ) {
						$_response = jQuery.parseJSON(response);
						if(ValidateSetQuoteValues($_response)){
						//laodingBar.hide();
						if($propname == 'servicearea'){
							$('input#zipcode').val($zipcode);
						}
						ValidateTab2();
						ValidateTab3();
						}
						else{
							return false;
						}
					}
				);
		}
		
		function ValidateSetQuoteValues($_response){
			try {
				if ($_response.error != undefined) {
					if($_response.ctrlToValidate != undefined){ 
						$('#'+$_response.ctrlToValidate).change(); 
						return true;
						}
					else{
						$modalMsg[999] = [ 'Warning', $_response.error ];
						displayMsg('modal' + jQuery('#page_key').val(), 999);
					//	laodingBar.hide();
						return false;
					}
				}
				return true;
			} catch (e) {
				console.log(e.message);
				return false;
			}
		}
		
		
		
		$('.quoteValueMember').change(quoteValuememberCallBack);
		
		$('.rdbaddservice').change(function(){
			$action = localize_var.TextDomain+'_setQuoteAddServValues';
			//laodingBar.show();
			$_uniqueid = $(this).data('field-name');
			$_addremove = $(this).val();
			jQuery.post(
					localize_var.admin_ajaxurl +"?action="+$action,
					{	
						uniqueid : $('#uniqueid').val(),
						propUniqueid : $_uniqueid,
						propname : $(this).data('propname'),
						addremove : $_addremove
					},
					function( response ) {
						$_response = jQuery.parseJSON(response);
						if ($_response.error != undefined) {
							$modalMsg[999] = [ 'Warning', $_response.error ];
							displayMsg('modal' + jQuery('#page_key').val(), 999);
						//	laodingBar.hide();
							return false;
						}
						//laodingBar.hide();
					}
				);
		});
		/* Set Quote Values */
		
		$('.start-price.icon-image').click(function(){
			$('.spfrequency.body > *').remove();
			$action = localize_var.TextDomain+'_getStartPricingFrequency';
		//	laodingBar.show();
			$_hometype = $(this).data('hometype');
			jQuery.post(
					localize_var.admin_ajaxurl +"?action="+$action,
					{				
						hometype :$_hometype
					},
					function( response ) {
						$_response = jQuery.parseJSON(response);
						if ($_response.error != undefined) {
							$modalMsg[999] = [ 'Warning', $_response.error ];
							displayMsg('modal' + jQuery('#page_key').val(), 999);
							return false;
						}
						$colCount = 12 / $_response.length;
						 $.each($_response, function(i, value) {
							 $_spanTitle = $('<span>').addClass('text').html(value.frequencytext);
							 $_divIcon = $('<div>').addClass('frequency icon-image').css('background-image',"url('"+value.iconImageUrl+"')").data('hometype',$_hometype).data('frequency',value.frequency).data('price',value.price).data('uniqueid',value.uniqueid).data('isRecurring',value.isRecurring).bind('click',funcfrequencyiconimage_click);
							 $_spanPrice = $('<span>').addClass('text text-medume').html("( $"+value.price+" )");
					         $_div = $('<div>').addClass('col-md-'+$colCount+' text-center icon-huge').append($_spanTitle,$_divIcon,$_spanPrice);
					         $('.spfrequency.body').append($_div);
					      });
						 
						$("#wizard").steps('next');
						try{ // add try because bugid#0003
						/*Google analatic event traking code - Event : Request*/
						_gaTracking(1);
						console.log('Request');
						/*Google analatic event traking code */
						}
						catch(err) {console.log('Error in google analytic setup - Request :'+err.message);}
						
						//laodingBar.hide();
					}
				);
			
		});
		
		function funcfrequencyiconimage_click(){
			//alert($(this).data('frequency'));
			$_hometype = $(this).data('hometype');
			$_frequency = $(this).data('frequency');
			$_uniqueid = $(this).data('uniqueid');
			$_isRecurring = $(this).data('isRecurring');
			
			$('#ctrlSquareFootage option').remove();
			$('#ctrlSquareFootage').append(
					$('<option>').val('-1').html('Please select a Square Footage')
			);
			$action = localize_var.TextDomain+'_getsqfpricingbyf'; // $action = localize_var.TextDomain+'_getsqfpricingbyhnf';
			laodingBar.show();
			jQuery.post(
					localize_var.admin_ajaxurl +"?action="+$action,
					{				
						hometype : $_hometype,
						frequency : $_frequency
					},
					function( response ) {
						$_response = jQuery.parseJSON(response);
						if ($_response.error != undefined) {
							$modalMsg[999] = [ 'Warning', $_response.error ];
							displayMsg('modal' + jQuery('#page_key').val(), 999);
							laodingBar.hide();
							return false;
						}
						$colCount = 12 / $_response.length;
						 $.each($_response, function(i, value) {
							 $('#ctrlSquareFootage').append(
										$('<option>').val(value.uniqueid).html(value.description +" (+ $"+value.price+" )")
								);
					      });
						 
						 _initQuotation($_uniqueid);
						 if($_isRecurring == '1'){
							 $('.whenNwhere > div.col-md-6').removeClass('col-md-6').addClass('col-md-4');
							 $('#recurringSchedule').show();							 
						 }
						 else{
							 $('.whenNwhere > div.col-md-4').removeClass('col-md-4').addClass('col-md-6');
							 $('#recurringSchedule').hide();
						 }
						 
						 $('div.addServices:not(.All)').hide();
						 $('.'+$_frequency).show();
						 
						 $('input[type=radio][name=extrasrvselect]').data('isRecurring',$_isRecurring);
						 
						 $("#wizard").steps('next');
						 laodingBar.hide();
					}
				);		
		
		};
		
		function _initQuotation($_uniqueid){
			laodingBar.show();
			$action = localize_var.TextDomain+'_initQuote';
			jQuery.post(
					localize_var.admin_ajaxurl +"?action="+$action,
					{				
						uniqueid : $_uniqueid
					},
					function( response ) {
						$_response = jQuery.parseJSON(response);
						if ($_response.error != undefined) {
							$modalMsg[999] = [ 'Warning', $_response.error ];
							displayMsg('modal' + jQuery('#page_key').val(), 999);
							laodingBar.hide();
							return false;
						}
						$('#uniqueid').val($_response.uniqueid);
					}
			);
		}
		
		$('input[type=radio][name=extrasrvselect]').change(function(){
			$_extrasrvcls = $(this).data('isRecurring') == '1' ? '.Recurring' : '.Not-Recurring';
			$_selectall = $(this).val();
			$('div#extra-services '+$_extrasrvcls+' input[type=radio]:checked').each(function(){
				if($(this).val() != $_selectall)
					{
					setElementValue('extra-services',$(this).data('field-name'),$_selectall);
					}
			});
		});
		
		function ValidateTab2(){	
			
			if(_wizardStepsgetCurrentIndex == 2 && ($('#ctrlScheduale').val() != -1 && $('#ctrlServArea').val() != -1 && ($('#recurringSchedule').css('display') == 'none' || $('#ctrlfrequency').val() != -1))){
				$("ul[aria-label=Pagination]").show();
				return true;
			}
		}
		
		function ValidateTab3(){			
			if(_wizardStepsgetCurrentIndex == 3 && ($('#ctrlSquareFootage').val() != -1 && $('#ctrlBedRooms').val() != -1 && $('#ctrlBathrooms').val()!= -1 && $('#ctrlPets').val() != -1)){
				$("ul[aria-label=Pagination]").show();
				return true;
			} 
		}
		
		
		
		
		function _getQuoteSummary(){
		  laodingBar.show();
      	   $action = localize_var.TextDomain+'_getQuoteSummery';
      	   jQuery.post(
 					localize_var.admin_ajaxurl +"?action="+$action,
 					{	
 						uniqueid : $('#uniqueid').val()
 					},
 					function( response ) {
 						$_response = jQuery.parseJSON(response);
 						if ($_response.error != undefined) {
 							$modalMsg[999] = [ 'Warning', $_response.error ];
 							displayMsg('modal' + jQuery('#page_key').val(), 999);
 							laodingBar.hide();
 							return false;
 						}
 						$('.tab-quote-summery > div#summary-container').empty();
 						$('.tab-quote-summery > div#summary-container').append($_response.quoteSummery);
 						//$("ul[aria-label=Pagination]").show();
 						laodingBar.hide();
 					}
 				);
		}
		
		$('#btn_save_contactInfo').click(function(){			
			
			 $_data = _getFieldData('frm_quoteContacts');		 
			if($_data == null) return false;
			 if(!validatePhone($('input[data-field-name=phoneNumber]').val())){
					$modalMsg[996] = [ 'Warning', 'Invalid Phone Number' ];
					displayMsg('modal' + jQuery('#page_key').val(), 996);
					laodingBar.hide();
					return false;
				}
		/*		if(!validatePhone($('input[data-field-name=mobileNumber]').val())){
					$modalMsg[996] = [ 'Warning', 'Invalid Mobile Number' ];
					displayMsg('modal' + jQuery('#page_key').val(), 996);
					laodingBar.hide();
					return false;
				}*/
				if($('#ctrlMarketingRef').val() == -1){
					$modalMsg[995] = [ 'Warning', 'Please tell us , How did you find us.' ];
					displayMsg('modal' + jQuery('#page_key').val(), 995);
					laodingBar.hide();
					return false;
				}
			laodingBar.show();	
     	   $action = localize_var.TextDomain+'_saveQuoteContacts';
     	   jQuery.post(
					localize_var.admin_ajaxurl +"?action="+$action,
					{	
						uniqueid : $('#uniqueid').val(),
						data : $_data   						
					},
					function( response ) {
						$_response = jQuery.parseJSON(response);
						if ($_response.error != undefined) {
							
							$modalMsg[999] = [ 'Warning', $_response.error ];
							displayMsg('modal' + jQuery('#page_key').val(), 999);	
							laodingBar.hide();
							return false;
						} 
						if($('h3#pmtquotetotal').length > 0){
							$('h3#pmtquotetotal').html($_response._totalpriceStr);
						}
						_getQuoteSummary();
						//laodingBar.hide();
						$("#wizard").steps('next');
						try{ // add try because bugid#0003
						/*Google analatic event traking code - Event : QuoteDel*/
							_gaTracking(3);
						console.log('QuoteDel');
						/*Google analatic event traking code */
						}catch(err) {console.log('Error in google analytic setup - QuoteDel :'+err.message);}
					}
				);
		});
		
		$('input[type=radio][name=maxsqft]').change(function(){	
			$__val = $('input[type=radio][name=maxsqft]:checked').val();
			$('.hourly-rate-effects').toggle();
			if( $__val == 1 )
				$("ul[aria-label=Pagination]").show();
			else
				{ $("ul[aria-label=Pagination]").hide(); ValidateTab3(); }
			
			laodingBar.show();
			$action = localize_var.TextDomain+'_changeStatus';
	    	jQuery.post(
					localize_var.admin_ajaxurl +"?action="+$action,
					{				
						tblName : '_quotes',
						uniqueid : $('#uniqueid').val(),
						status : $__val,
						propName : 'isHourlyRate'
					},
					function( response ) {
						$_response = jQuery.parseJSON(response);	    						
						laodingBar.hide();
					}
				);
		});
		
		$('input#numbofhours').change(function(){
			$('#hourlyrateTotal').html(formatNumber(parseFloat($(this).val() * $('input[type=radio][name=maxsqft]').data('hourlyrate')).toFixed(2)));
		});
		
		$('#btn_submit_payment').click(function(){
			
			var cardType = $.payment.cardType($('input#cardNumber').val());
			$ccvalid = $.payment.validateCardNumber($('input#cardNumber').val());
			$cvcvalid  = $.payment.validateCardCVC($('input#cvv').val(), cardType);
			$('input#cvv').toggleInputError(!$cvcvalid);
		    $('input#cardNumber').toggleInputError(!$ccvalid);
		    if(!$ccvalid){
		    	$modalMsg[999] = [ 'Warning', 'Invalid Card Number' ];
				displayMsg('modal' + jQuery('#page_key').val(), 999);
				laodingBar.hide();
				return false;
		    }
		    if(!$cvcvalid){
		    	$modalMsg[999] = [ 'Warning', 'Invalid Card Security Code' ];
				displayMsg('modal' + jQuery('#page_key').val(), 999);
				laodingBar.hide();
				return false;
		    }
			
			$_data = _getFieldData('frm_pmtsubmition');	
			laodingBar.show();
			 $action = localize_var.TextDomain+'_pmtsubmit';
	     	   jQuery.post(
						localize_var.admin_ajaxurl +"?action="+$action,
						{	
							uniqueid : $('#uniqueid').val(),
							data : $_data   						
						},
						function( response ) {
							$_response = jQuery.parseJSON(response);
							if ($_response.error != undefined) {								
								$modalMsg[999] = [ 'Warning', $_response.error ];
								displayMsg('modal' + jQuery('#page_key').val(), 999);	
								laodingBar.hide();
								return false;
							} 
							
							laodingBar.hide();
							jQuery('#frm_pmtsubmition').toggleClass('hide-form');
							jQuery('#frm_thankyou').toggleClass('hide-form');
							try{ // add try because bugid#0003
							/*Google analatic event traking code - Event : ApptReq*/
								_gaTracking(4);
							console.log('ApptReq');
							/*Google analatic event traking code */
							}catch(err) {console.log('Error in google analytic setup - ApptReq :'+err.message);}
						}
					);
		});
		
		
		/* Request Service Location Save */
		
		$('#btn_save_ServLocInfo').click(function(){			
			
			 $_data = _getFieldData('frm_quoteLocationInfo');		 
			if($_data == null) return false;
			 if(!validatePhone($('input#loc_info_phoneNumber[data-field-name=phoneNumber]').val())){
					$modalMsg[996] = [ 'Warning', 'Invalid Phone Number' ];
					displayMsg('modal' + jQuery('#page_key').val(), 996);
					laodingBar.hide();
					return false;
				}

			laodingBar.show();	
    	   $action = localize_var.TextDomain+'_saveServiceLocInfo';
    	   jQuery.post(
					localize_var.admin_ajaxurl +"?action="+$action,
					{	
						uniqueid : $('#uniqueid').val(),
						data : $_data   						
					},
					function( response ) {
						$_response = jQuery.parseJSON(response);
						if ($_response.error != undefined) {
							
							$modalMsg[999] = [ 'Warning', $_response.error ];
							displayMsg('modal' + jQuery('#page_key').val(), 999);	
							laodingBar.hide();
							return false;
						} 
						laodingBar.hide();
						jQuery('#frm_quoteLocationInfo').toggleClass('hide-form');
						jQuery('#frm_loc_info_thankyou').toggleClass('hide-form');
						
						try{ // add try because bugid#0003
						/*Google analatic event traking code - Event : QuoteDel*/
							_gaTracking(4);
							console.log('QuoteDel');
						/*Google analatic event traking code */
						}catch(err) {console.log('Error in google analytic setup - QuoteDel :'+err.message);}
						
					}
				);
		});
		
		
	});	
})(jQuery);