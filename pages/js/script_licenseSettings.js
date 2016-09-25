(function($){
	$(document).ready(function(){
		$('#btn_removelicense').click(function(){
			
			$action = localize_var.TextDomain+'_deactivateLicense';
			jQuery.post(
					localize_var.admin_ajaxurl +"?action="+$action,
					{				
						data : ''
					},
					function( response ) {
						$_response = jQuery.parseJSON(response);
						
						if ($_response.error != undefined) {
							$modalMsg[999] = [ 'Warning', $_response.error ];
							displayMsg('modal' + $('#page_key').val(), 999);
							return false;
						}
						var _mainpageurl = location.protocol + '//' + location.host + location.pathname+ '?page=' + localize_var.TextDomain+'_licensing';
						location.href = _mainpageurl;
					}
				);
		});
	});
})(jQuery);