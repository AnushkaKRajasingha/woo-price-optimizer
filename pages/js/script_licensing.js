(function($){
	$(document).ready(function(){
		$('#btnpluginactivation').click(function(){
			$_data = _getFieldData('frm_pluginactivation');
			if($_data == null) return false;
			$action = localize_var.TextDomain+'_activateLicense';
			jQuery.post(
					localize_var.admin_ajaxurl +"?action="+$action,
					{				
						data : $_data
					},
					function( response ) {
						$_response = jQuery.parseJSON(response);
						$('#btnpluginSettings').attr('disabled','disabled');
						if ($_response.error != undefined) {
							$modalMsg[999] = [ 'Warning', $_response.error ];
							displayMsg('modal' + $('#page_key').val(), 999);
							return false;
						}
						var _mainpageurl = location.protocol + '//' + location.host + location.pathname+ '?page=' + localize_var.TextDomain;
						location.href = _mainpageurl;
					}
				);
		});
	});
})(jQuery);