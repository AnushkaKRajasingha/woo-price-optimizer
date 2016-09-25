(function($){
jQuery(document).ready(function($){
	$__elm = $('<div class="btn-group  pull-right"><a id="btn_exporttocsv" class="btn btn-primary" data-toggle="modal" href="">Export To CSV <i class="fa fa-download"></i></a></div><div class="space15"></div>');
	$('.panel-heading > h4').append($__elm);
	
	
	
	function funcLoadQuotes(){
		$action = localize_var.TextDomain+'_getvisitorslogs';
		
		var jqxhr = jQuery.getJSON(localize_var.admin_ajaxurl+"?action="+$action, function (data) {
			
	        var $aoColumns = [ 
	                          { "sTitle": "Visitor Ref","sName":"visitorid" ,"mData":"visitorid" ,"sClass" :"hidden-xs hidden-md hidden-lg"},
	                         // { "sTitle": "updatedate","sName":"updatedate" ,"mData":"updatedate" ,"sClass" :"hidden-xs hidden-sm col-md-2 date"},
	                         // { "sTitle": "Preferred Service","sName":"startpricing.hometype" ,"mData":"startpricing.hometype" ,"sClass" :"col-md-3 prefServ"},
	                          { "sTitle": "IP Address","sName":"ipaddress" ,"mData":"ipaddress" ,"sClass" :"col-md-2"},
	                          { "sTitle": "User Name","sName":"userlogin" ,"mData":"userlogin" ,"sClass" :"col-md-2","mRender": function( data, type, full ) {
	                              return data == null ? 'Anonymous' : data;                                       
	                          }},
	                          { "sTitle": "Page Accessed","sName":"requestpage" ,"mData":"requestpage" ,"sClass" :"col-md-4"},
	                          { "sTitle": "Visit Count","sName":"visitcount" ,"mData":"visitcount" ,"sClass" :"col-md-1"},
	                          { "sTitle": "Last Access Date","sName":"lastsessiondate" ,"mData":"lastsessiondate" ,"sClass" :"col-md-2 date"}	                         
	                          ];
	       var $fnRowCallback =  function( nRow, aData, iDisplayIndex ) {
	    	//   jQuery('td.prefServ',nRow).empty().append(function(){ return aData.startpricing.hometypetext +' / '+aData.startpricing.frequencytext;});
	    //	   jQuery('td.status',nRow).empty().append(_getStateLable(aData.isActive));
	   // 	   jQuery('td.action',nRow).empty().append(_getAjaxActionButtonsViewDelete(aData,'data_table_quotes',funcLoadQuotes,viewQuote,'_delquote'));
	       	};
	       	
	       	var $fnDrawCallback = function ( settings ) {
	            var api = this.api();
	            var rows = api.rows( {page:'current'} ).nodes();
	            var last=null;
	 
	            api.column(0, {page:'current'} ).data().each( function ( group, i ) {
	                if ( last !== group ) {
	                    $(rows).eq( i ).before(
	                        '<tr class="group"><td colspan="5"> Visitor - <i>'+group+'</i></td></tr>'
	                    );
	 
	                    last = group;
	                }
	            } );
	        };
	       	
	        _setupDataTable_v1($('#data_table_quotes'),$aoColumns,data,$fnRowCallback,$fnDrawCallback,[ [ 5, "desc" ] ]);
	        
		});
	}
	
	function viewQuote($_data){
		$action = localize_var.TextDomain+'_getQuoteSummeryFull';
 	   jQuery.post(
				localize_var.admin_ajaxurl +"?action="+$action,
				{	
					uniqueid : $_data.uniqueid
				},
				function( response ) {
					$_response = jQuery.parseJSON(response);
					if ($_response.error != undefined) {
						$modalMsg[999] = [ 'Warning', $_response.error ];
						displayMsg('modal' + jQuery('#page_key').val(), 999);
						laodingBar.hide();
						return false;
					}
					$modalMsg[999] = [ 'Quote Summary', $_response.quoteSummery ];
					displayMsg('modal' + jQuery('#page_key').val(), 999);
					
					laodingBar.hide();
				}
			);
	}
	
	funcLoadQuotes();
	
	 $('#btn_exporttocsv').click(function(){
		 $action = localize_var.TextDomain+'_getvisitorslogs';
	 	   jQuery.post(
					localize_var.admin_ajaxurl +"?action="+$action,
					{
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
						JSONToCSVConvertor($_response,'Henry Track Records',true);
					}
				);
    	  
   	});	 
	
})
})(jQuery);
function createISContact($this){	
	$data = jQuery($this).data();
	$elm = jQuery($this); 
	jQuery($this).hide();
	jQuery($this).parent().append('Please wait..');
	$action = localize_var.TextDomain+'_createISContactByContact';
	   jQuery.post(
				localize_var.admin_ajaxurl +"?action="+$action,
				{	
					data : $data
				},
				function( response ) {
					$_response = jQuery.parseJSON(response);
					if ($_response.error != undefined) {
						$modalMsg[999] = [ 'Warning', $_response.error ];
						displayMsg('modal' + jQuery('#page_key').val(), 999);
						laodingBar.hide();
						return false;
					}
					jQuery($this).parent().prev().html('Infusion Contact ID');
					jQuery($this).parent().html($_response.ISContact_id);					
					laodingBar.hide();
				}
			);
}
function makeISMarketable ($this){	
	$data = jQuery($this).data();
	$action = localize_var.TextDomain+'_makeISContactMarketable';
	   jQuery.post(
				localize_var.admin_ajaxurl +"?action="+$action,
				{	
					data : $data
				},
				function( response ) {
					$_response = jQuery.parseJSON(response);
					if ($_response.error != undefined) {
						$modalMsg[999] = [ 'Warning', $_response.error ];
						displayMsg('modal' + jQuery('#page_key').val(), 999);
						laodingBar.hide();
						return false;
					}
					jQuery($this).hide();
					laodingBar.hide();
				}
			);
}
