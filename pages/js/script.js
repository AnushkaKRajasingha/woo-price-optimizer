var Script = function ($) {
	// tool tips
    $('.tooltips').tooltip();
    
    $(".nav-tabs a[data-toggle=tab]").on("click", function(e) {
    	  if ($(this).parent().hasClass("disabled")) {
    	    e.preventDefault();
    	    return false;
    	  }
    	});
    
}(jQuery);