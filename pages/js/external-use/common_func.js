var genaratePluginOptinForm = function(){
		var $useroptincode = $('<div/>').append($(arguments[0]));
		var $vid = arguments[1];
		$form = $useroptincode.find('form');
		if($form.length > 0){

		$optincode_form = $('<form/>').attr({'target':'iframe_'+$vid});

		//Check form
		triggeringBeforeSubmit($optincode_form, $vid);

		$.each($form[0].attributes,function(i,attrib){
			$optincode_form.attr(attrib.name,attrib.value);
		});

		$optincode_form.addClass('cvplayer-optin-form optin-form-'+$vid);
		$_inputs = $useroptincode.find('input');
		$.each($_inputs,function(){
			$__elem = $('<div/>').addClass('ctrl-containers');
			$(this).addClass('optin-elements optin-element-'+$vid);

			if ( $(this).attr('name') == 'name') {
				var name = 'Your name here';
				$(this).attr('placeholder', name);
			}

			if ( $(this).attr('name') == 'email') {
				var email = 'Your email here';
				$(this).attr('placeholder', email);
			}

			$__elem.append($(this));
			/*if($(this).attr('type').toLowerCase() != 'hidden'){
				$__elem.append($(this));
			}
			else{
				$__elem = $(this);
			}*/
			if($(this).attr('type').toLowerCase() == 'email'){$__elem.addClass('email-container');}
			if($(this).attr('type').toLowerCase() == 'submit'){$__elem.addClass('submit-container');}
			if($(this).attr('type').toLowerCase() == 'text'){$__elem.addClass('text-container');}
			$optincode_form.append($__elem);
		});
			$optincode_form.find('input[type=submit]').style('width', '160px', 'important');
			return $optincode_form;
		}
		else{
			return 'Invalid Opt-in code';
		}
};

var genarateEmailOnlyOptinForm = function(){
	var $useroptincode = $('<div/>').append($(arguments[0]));
	var $vid = arguments[1];
	$form = $useroptincode.find('form');
	if($form.length > 0){
	$optincode_form = $('<form/>').attr({'target':'iframe_'+$vid});

	//Check form
	triggeringBeforeSubmit($optincode_form, $vid);

	$.each($form[0].attributes,function(i,attrib){
		$optincode_form.attr(attrib.name,attrib.value);
	});
	$optincode_form.addClass('cvplayer-optin-form optin-form-'+$vid);
	$_input = $useroptincode.find('input[type=email]');
	if($_input.length <= 0){
		$_input = $useroptincode.find('input[name*=email]');
		if($_input.length <= 0){
			$_input = $useroptincode.find('input[name*=EMAIL]');
			if($_input.length <= 0){
				$_input = $useroptincode.find('input[class*=email]');
				if($_input.length <= 0){
					$_input = $useroptincode.find('input[class*=EMAIL]');
				}
			}
		}
	}

	if($_input.length > 0){ 
		$_email = $($_input[0]);
		$__elem = $('<div/>').addClass('ctrl-containers email-container');
		$_email.addClass('optin-elements optin-element-'+$vid+' optin-email-field');
		$_email.attr({'placeholder':'Enter your email','type':'email'});
		$__elem.append($_email);
		
		$optincode_form.append($__elem);
		
		$_inputs = $useroptincode.find('input[type=hidden]');
		$.each($_inputs,function(){
			$__elem = $('<div/>').addClass('ctrl-containers');
			$(this).addClass('optin-elements optin-element-'+$vid);
			$__elem.append($(this));
			$optincode_form.append($__elem);
		});
		
		$_inputs = $useroptincode.find('input[type=submit]');
		$.each($_inputs,function(){
			$__elem = $('<div/>').addClass('ctrl-containers submit-container');
			$(this).addClass('optin-elements optin-element-'+$vid+' optin-submit-button');
			$__elem.append($(this));
			$optincode_form.append($__elem);
		});
	}
		$($optincode_form).find('input[type=submit]').style('width', '160px', 'important');
		return $optincode_form;
	}
	else{
		return 'Invalid Opt-in code';
	}
};

/**
 * Do triggering of OPT form Submit
 * @param $optincode_form
 * @param $vid
 */
function triggeringBeforeSubmit($optincode_form, $vid) {
	var oldBorderColor = '';
	//triggering before submit do validation of email
	$optincode_form.submit(function(){
		window['videojs_'+$vid].pause();
		var $email = $(this).find('input[name=email]');
		if ( !validateEmail($email.val()) ) {
			if ( !oldBorderColor ) {
				oldBorderColor = $email.css("border-left-color");
			}
			$email.css('border', 'solid red');
			return false;
		}
		if ( oldBorderColor ) {
			$email.css('border', 'solid '+ oldBorderColor);
		}

		window['videojs_'+$vid].play();
		$('#vidOptinContainer_'+$vid).hide();

		return true;
	});

	function validateEmail(email) {
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}
}

(function($) {
	if ($.fn.style) {
		return;
	}

	// Escape regex chars with \
	var escape = function(text) {
		return text.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
	};

	// For those who need them (< IE 9), add support for CSS functions
	var isStyleFuncSupported = !!CSSStyleDeclaration.prototype.getPropertyValue;
	if (!isStyleFuncSupported) {
		CSSStyleDeclaration.prototype.getPropertyValue = function(a) {
			return this.getAttribute(a);
		};
		CSSStyleDeclaration.prototype.setProperty = function(styleName, value, priority) {
			this.setAttribute(styleName, value);
			var priority = typeof priority != 'undefined' ? priority : '';
			if (priority != '') {
				// Add priority manually
				var rule = new RegExp(escape(styleName) + '\\s*:\\s*' + escape(value) +
				'(\\s*;)?', 'gmi');
				this.cssText =
					this.cssText.replace(rule, styleName + ': ' + value + ' !' + priority + ';');
			}
		};
		CSSStyleDeclaration.prototype.removeProperty = function(a) {
			return this.removeAttribute(a);
		};
		CSSStyleDeclaration.prototype.getPropertyPriority = function(styleName) {
			var rule = new RegExp(escape(styleName) + '\\s*:\\s*[^\\s]*\\s*!important(\\s*;)?',
				'gmi');
			return rule.test(this.cssText) ? 'important' : '';
		}
	}

	// The style function
	$.fn.style = function(styleName, value, priority) {
		// DOM node
		var node = this.get(0);
		// Ensure we have a DOM node
		if (typeof node == 'undefined') {
			return this;
		}
		// CSSStyleDeclaration
		var style = this.get(0).style;
		// Getter/Setter
		if (typeof styleName != 'undefined') {
			if (typeof value != 'undefined') {
				// Set style property
				priority = typeof priority != 'undefined' ? priority : '';
				style.setProperty(styleName, value, priority);
				return this;
			} else {
				// Get style property
				return style.getPropertyValue(styleName);
			}
		} else {
			// Get CSSStyleDeclaration
			return style;
		}
	};
})(jQuery);
