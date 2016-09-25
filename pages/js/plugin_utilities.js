/* Version : v1.0 */
var $status = {
	0 : [ 'label-danger', 'Inactive' ],
	1 : [ 'label-success', 'Active' ],
	2 : [ 'label-info', 'Incomplete' ],
	3 : [ 'label-warning', 'Freezed' ],
	4 : [ 'label-inverse', 'Pending' ]
};

var $yesno = {
		0 : [ 'label-danger', 'No' ],
		1 : [ 'label-success', 'Yes' ],	
};

var $modalMsg = {
	0 : [ 'Saved', 'Item save completed.' ],
	1 : [ 'Warning', 'Invalid image URL.' ],
	2 : [ 'Update', 'Item update completed.' ],
	3 : [ 'Warning',
			'You must select at least one Item to create a Page' ],
	4 : [ 'Saved', 'Page save completed' ],
	5 : [ 'Update', 'Page update completed' ],
	6 : [ 'Update', 'Retargeting code update completed' ],
	7 : [ 'Please wait, Loading ', '' ],
	8 : [ 'Please wait, Saving ', '' ],
	9 : [ 'Warning','Please fill all required fields.'],
	10 : ['Warning','Field already exists.'],
	99 : [ 'Error', 'Unknown error, please contact the application support' ]
};

var $dberrors = {
	'PRIMARY':	 [ 'Duplicate Entry', 'System already has the record with same data values.' ]
};

var dialogOpen = false;
function resetPB(){
	jQuery('.progress > div').css('width', '0%').attr('aria-valuenow', 0);
	jQuery('.progress > div > span').html('0% Complete');
}

function increasPBValue(){
	$valuer = jQuery('.progress > div').attr('aria-valuenow');
	$valuer = $valuer + 1;
	jQuery('.progress > div').css('width', $valuer+'%').attr('aria-valuenow', $valuer);
	jQuery('.progress > div > span').html($valuer+'% Complete');
	if(dialogOpen)
		setTimeout(increasPBValue(), 1000);
}

function setModalText($modelid, $msgid) {
	jQuery('#' + $modelid + ' #modalTitle').html($modalMsg[$msgid][0]);
	jQuery('#' + $modelid + ' #modalText').html($modalMsg[$msgid][1]);
}

function displayMsg($modelid, $msgid) {
	if(jQuery('#' + $modelid + ' #modalTitle').length > 0){
	jQuery('#' + $modelid + ' #modalTitle').html($modalMsg[$msgid][0]);
	jQuery('#' + $modelid + ' #modalText').html($modalMsg[$msgid][1]);
	jQuery('#' + $modelid).modal('show');
	if($msgid == 7){
		dialogOpen = true;
		/*resetPB();
		increasPBValue();*/
	}
	}else
		{
		if(typeof($modalMsg[$msgid][1]) != "object")
			alert($modalMsg[$msgid][0]+'\n\n'+$modalMsg[$msgid][1]);
		else
			alert($modalMsg[$msgid][0]+'\n\n\t'+$modalMsg[$msgid][1].text().replace(/\./g, ".\n\t"));
		}
}

function checkResponseDbError(response){
	if (response.indexOf('error') > 0) {
		$modalMsg[999] = [ 'Warning', response ];
		for(var $key in $dberrors){
			if (response.indexOf($key) > 0){
				$modalMsg[999] = [ $dberrors[$key][0], $dberrors[$key][1]];
			}
		}		
		displayMsg('modal' + jQuery('#page_key').val(), 999);
		laodingBar.hide();
		return false;
	}
}

function _responseValidate($response,$modelid){
	if($response == undefined || $response == null){
		jQuery('#' + $modelid + ' #modalTitle').html('Error');
		jQuery('#' + $modelid + ' #modalText').html('Invalid response.<br/><code>Respone is '+ $response +'</code>.');
		jQuery('#' + $modelid).modal('show');
		return false;
	}
	if($response.msgError != undefined && $response.msgError !=''){
		jQuery('#' + $modelid + ' #modalTitle').html('Error');
		jQuery('#' + $modelid + ' #modalText').html($response.msgError);
		jQuery('#' + $modelid).modal('show');
		return false;
	}
	return true;
}
function _responseValidate_v1($response,$modelid){
	if($response == undefined || $response == null){
		jQuery('#' + $modelid + ' #modalTitle').html('Error');
		jQuery('#' + $modelid + ' #modalText').html('Invalid response.<br/><code>Respone is '+ $response +'</code>.');
		jQuery('#' + $modelid).modal('show');
		return false;
	}
	$response = jQuery.parseJSON($response);
	if($response.msgError != undefined && $response.msgError !=''){
		jQuery('#' + $modelid + ' #modalTitle').html('Error');
		jQuery('#' + $modelid + ' #modalText').html($response.msgError);
		jQuery('#' + $modelid).modal('show');
		return false;
	}
	return true;
}

function _responseValidate_v2($response){
	$modelid = 'modal' + jQuery('#page_key').val();
	if($response == undefined || $response == null){
		jQuery('#' + $modelid + ' #modalTitle').html('Error');
		jQuery('#' + $modelid + ' #modalText').html('Invalid response.<br/><code>Respone is '+ $response +'</code>.');
		jQuery('#' + $modelid).modal('show');
		return false;
	}
	$response = jQuery.parseJSON($response);
	if($response != null && $response.msgError != undefined && $response.msgError !=''){
		jQuery('#' + $modelid + ' #modalTitle').html('Error');
		jQuery('#' + $modelid + ' #modalText').html($response.msgError);
		jQuery('#' + $modelid).modal('show');
		return false;
	}
	return true;
}

function closeDialog($modelid){
	jQuery('#' + $modelid ).modal('hide');
	dialogOpen = false;
}



function isValidImageUrl($url) {
	var regx = /^((https?|ftp):)?\/\/.*(jpeg|jpg|png|gif)$/;
	if (regx.test($url))
		return true;
	return false;
}

function validate($_formid) {
	if (jQuery('#' + $_formid)[0].checkValidity()) {
		return true;
	}
	$_errorlist = jQuery('<ul>').addClass('error-msg');
	var $ErrorCat = '';
	jQuery('#' + $_formid).find( ":invalid" ).each( function( index, node ) {
		 var label = jQuery( 'label[for="' + node.id + '"] '); // Lable text correction on 01252016
         // Opera incorrectly does not fill the validationMessage property.
         message = node.validationMessage || 'Invalid value.';
         if($ErrorCat != message ){
        	 $ErrorCat = message;
        	 $_errorlist.append( "<li><span><strong>" + $ErrorCat + "</strong></span>" );
         }
		 $_errorlist.append( "<li><span>" + label.html() + "</span></li><br/>\n" );
	});
	$modalMsg[9999] = [ 'Warning', $_errorlist];
	displayMsg('modal' + jQuery('#page_key').val(), 9999);
	//jQuery('#' + $_formid + ' [type=submit]').trigger('click');
	return false;
}

function _getFieldData($_formid) {
	if (validate($_formid)) {
		var $__object = {};
		jQuery('#' + $_formid + ' [data-field-value="yes"]')
				.each(
						function() {
							/* Trace */	
							
							var $tempValue = jQuery(this).val() ;
							var $strDataFieldName = jQuery(this).data('field-name');
							if (jQuery(this).attr('type') != undefined){							
							
							/* Checkbox value */
							if (jQuery(this).attr('type') == 'checkbox') {
								$tempValue = jQuery(this).attr('checked') == 'checked' ? '1': '0';
							}
							
							/* Radio value */
							if (jQuery(this).attr('type') == 'radio') {
								$tempValue = jQuery("input:radio[name='"+this.name+"']:checked").val();
							}
							
							if (jQuery(this).attr('type') == 'radio') {
								$tempValue = jQuery("input:radio[name='"+this.name+"']:checked").val();
							}							
							
							}
							else if (jQuery(this).attr('data-type') != undefined ) {
									var $_tempvar = [];
									jQuery.each(jQuery("[data-type='"+jQuery(this).attr('data-type')+"']"),function(){
										$_tempvar.push(jQuery(this).attr('data-value'));
									});
									$tempValue = $_tempvar;
							}
							if($strDataFieldName != 'undefined')
							if ($strDataFieldName.indexOf(".") == -1)
								$__object[$strDataFieldName] = $tempValue;						
							else {
								$primArray = $strDataFieldName.substring(0,
										$strDataFieldName.indexOf("."));
								if (typeof $__object[$primArray] == 'undefined')
									$__object[$primArray] = {};
								$key = $strDataFieldName.substring($strDataFieldName.indexOf(".") + 1,$strDataFieldName.lenght);
								if ($key.indexOf(".") == -1){
									$__object[$primArray][$key] = $tempValue;
								}
								else{
									$secondArray = $key.substring(0,
											$key.indexOf("."));
									if (typeof $__object[$primArray][$secondArray] == 'undefined')
										$__object[$primArray][$secondArray] = {};
									$key2 = $key.substring($key.indexOf(".") + 1,$key.lenght);
									$__object[$primArray][$secondArray][$key2] = $tempValue;
								}
							}
						});
		return $__object;
	}
	else{		
		//displayMsg('modal' + jQuery('#page_key').val(), 9);
	}
	return null;
}



function getUrlVars() {
	var vars = [], hash;
	var hashes = window.location.href.slice(
			window.location.href.indexOf('?') + 1).split('&');
	for ( var i = 0; i < hashes.length; i++) {
		hash = hashes[i].split('=');
		vars.push(hash[0]);
		vars[hash[0]] = hash[1];
	}
	return vars;
}

function stripslashes(str) {
	// + original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// + improved by: Ates Goral (http://magnetiq.com)
	// + fixed by: Mick@el
	// + improved by: marrtins
	// + bugfixed by: Onno Marsman
	// + improved by: rezna
	// + input by: Rick Waldron
	// + reimplemented by: Brett Zamir (http://brett-zamir.me)
	// + input by: Brant Messenger (http://www.brantmessenger.com/)
	// + bugfixed by: Brett Zamir (http://brett-zamir.me)
	// * example 1: stripslashes('Kevin\'s code');
	// * returns 1: "Kevin's code"
	// * example 2: stripslashes('Kevin\\\'s code');
	// * returns 2: "Kevin\'s code"
	return (str + '').replace(/\\(.?)/g, function(s, n1) {
		switch (n1) {
		case '\\':
			return '\\';
		case '0':
			return '\u0000';
		case '':
			return '';
		default:
			return n1;
		}
	});
}


function _injectPageData($object, $formid, $prefix) {
	//jQuery('#'+$formid).trigger('reset');
	if ($prefix == undefined)
		$prefix = '';
	jQuery.each($object, function(key, value) {
		if (value != null) {
			if (typeof value == 'object') {
				jQuery.each(this, function(key1, value1) {
					_injectPageData(value, $formid, $prefix + key + '.');
				});
			} else {
				setElementValue($formid, $prefix + key, value);
			}
		}
	});
}

function setElementValue($formid, dfName, value) {
	if (value == undefined || value == '')
		return false;
	value = stripslashes(value);
	$elm = jQuery('#' + $formid + ' [data-field-name="' + dfName + '"]');
	$type = $elm.attr('type');
	var $data_type = $elm.attr('data-type');
	if($data_type != undefined) $type = $data_type;
	if ($type != undefined) {
		switch ($type) {
		case 'checkbox': {
			jQuery($elm).bootstrapSwitch('setState', value == 1, true);
		}break;
		case 'color':{
			jQuery($elm).val(value);
			$elm.parent().attr('data-color', value);
			$elm.parent().find('span button i').css('background-color',value);
		}break;
		case 'radio':{
			jQuery('#' + $formid + ' [data-field-name="' + dfName + '"]').prop('checked', false).parent().removeClass("active");
			if(jQuery('#' + $formid + ' [data-field-name="' + dfName + '"][value="' + value + '"]').length > 0)
				jQuery('#' + $formid + ' [data-field-name="' + dfName + '"][value="' + value + '"]').prop('checked', true).parent().addClass("active");
			else
				{ jQuery('#' + $formid + ' [data-field-name="' + dfName + '"]').last().val(value).prop('checked', true).parent().addClass("active"); 
				jQuery('#' + $formid + ' [data-field-name="' + dfName + '"]').last().next().val(value);
				}
			$elm = jQuery('#' + $formid + ' [data-field-name="' + dfName + '"]:checked');
		}break;
		default:
			jQuery($elm).val(value);
			break;
		}
	} else
		jQuery($elm).val(value);

	jQuery($elm).change();
}


function formReset($formid){
	jQuery('#'+$formid).trigger('reset');
	jQuery('#'+$formid+ ' input[type=hidden]').val('');
	jQuery('#'+$formid).css('background-color','rgba(255, 255, 255,1)');
}

function _getActionButtons(uniqueid, actionpage, copyAjaxMethod,
		deletAjaxMethod) {
	var $editlink = location.protocol + '//' + location.host + location.pathname
			+ '?page=' + localize_var.TextDomain + actionpage + "&uniqueid="
			+ uniqueid;
	var $coplink = localize_var.admin_ajaxurl + "?action="
			+ localize_var.TextDomain + copyAjaxMethod;
	var $deletelink = localize_var.admin_ajaxurl + "?action="
			+ localize_var.TextDomain + deletAjaxMethod;
	var $data = {};
	$data['uniqueid'] = uniqueid;

	var _span = jQuery('<span/>').addClass('tools text-center');
	var _copy = jQuery('<a/>').attr({
		'title' : 'Copy',
		'href' : 'javascript:;'
	}).addClass('fa fa-copy');
	_copy.click(function() {
		bootbox.confirm("Do you want to copy this?", function(result) {
			if (result) {
				jQuery.post($coplink, {
					data : $data
				}, function(response) {
					$_response = jQuery.parseJSON(response);
					location.reload();
				});
			}
		});
	});
	var _edit = jQuery('<a/>').attr({
		'title' : 'Edit',
		'href' : $editlink
	}).addClass('fa fa-edit');
	var _delete = jQuery('<a/>').attr({
		'title' : 'Delete',
		'href' : 'javascript:;'
	}).addClass('fa fa-trash-o');
	_delete.click(function() {
		bootbox.confirm("Do you want to delete this?", function(result) {
			if (result) {
				jQuery.post($deletelink, {
					data : $data
				}, function(response) {
					$_response = jQuery.parseJSON(response);
					if ($_response.error != undefined) {
						$modalMsg[999] = [ 'Warning', $_response.error ];
						displayMsg('modal' + jQuery('#page_key').val(), 999);
						return false;
					}
					location.reload();
				});
			}
		});
	});

	_span.append(_copy);
	_span.append(_edit);
	_span.append(_delete);
	return _span;
}

function _getAjaxActionButtons(aData,tableid,reload_callbackfunc,edit_callbackfunc, recordpullAjaxMethod, copyAjaxMethod,
		deletAjaxMethod) {
	var uniqueid = aData.uniqueid;
	function reloadTable(){
		jQuery('#'+tableid).hide();
		jQuery('#'+tableid).dataTable().fnDestroy();
		reload_callbackfunc();
		jQuery('#'+tableid).show();
	}
	/*var $editlink = localize_var.admin_ajaxurl + "?action="
	+ localize_var.TextDomain + recordpullAjaxMethod;*/
	
	var $coplink = localize_var.admin_ajaxurl + "?action="
			+ localize_var.TextDomain + copyAjaxMethod;
	var $deletelink = localize_var.admin_ajaxurl + "?action="
			+ localize_var.TextDomain + deletAjaxMethod;
	
	var $data = {};
	$data['uniqueid'] = uniqueid;

	/* Copy action starts*/
	var _span = jQuery('<span/>').addClass('tools text-center');
	var _copy = jQuery('<a/>').attr({
		'title' : 'Copy',
		'href' : 'javascript:;'
	}).addClass('fa fa-copy');
	_copy.click(function() {
		bootbox.confirm("Do you want to copy this?", function(result) {
			if (result) {
				jQuery.post($coplink, {
					data : $data
				}, function(response) {
					$_response = jQuery.parseJSON(response);
					reloadTable();
				});
			}
		});
	});
	/* Copy action ends */
	/* Edit action starts*/	
	var _edit = jQuery('<a/>').attr({
		'title' : 'Edit',
		'href' : 'javascript:;'
	}).addClass('fa fa-edit');
	
	_edit.click({aData:aData},function(e){
		edit_callbackfunc(e.data.aData);
	});
	/* Edit action ends*/
	/* Delete action starts*/
	var _delete = jQuery('<a/>').attr({
		'title' : 'Delete',
		'href' : 'javascript:;',
			'data-uniqueid': uniqueid,
			'id' : uniqueid
	}).addClass('fa fa-trash-o');
	_delete.click(function() {
		bootbox.confirm("Do you want to delete this?", function(result) {
			if (result) {
				jQuery.post($deletelink, {
					data : $data
				}, function(response) {
					$_response = jQuery.parseJSON(response);
					if ($_response.error != undefined) {
						$modalMsg[999] = [ 'Warning', $_response.error ];
						displayMsg('modal' + jQuery('#page_key').val(), 999);
						return false;
					}
					reloadTable();
				});
			}
		});
	});
	/* Delete action ends*/

	_span.append(_copy);
	_span.append(_edit);
	_span.append(_delete);
	return _span;
}

function _getAjaxActionButtonsViewDelete(aData,tableid,reload_callbackfunc,view_callbackfunc, deletAjaxMethod) {
	var uniqueid = aData.uniqueid;
	function reloadTable(){
		jQuery('#'+tableid).hide();
		jQuery('#'+tableid).dataTable().fnDestroy();
		reload_callbackfunc();
		jQuery('#'+tableid).show();
	}
	
	var $deletelink = localize_var.admin_ajaxurl + "?action="
			+ localize_var.TextDomain + deletAjaxMethod;
	
	var $data = {};
	$data['uniqueid'] = uniqueid;

	var _span = jQuery('<span/>').addClass('tools text-center');
	/* View action starts*/	
	var _view = jQuery('<a/>').attr({
		'title' : 'View',
		'href' : 'javascript:;'
	}).addClass('fa fa-folder-open-o');
	
	_view.click({aData:aData},function(e){
		view_callbackfunc(e.data.aData);
	});
	/* View action ends*/
	/* Delete action starts*/
	var _delete = jQuery('<a/>').attr({
		'title' : 'Delete',
		'href' : 'javascript:;'
	}).addClass('fa fa-trash-o');
	_delete.click(function() {
		bootbox.confirm("Do you want to delete this?", function(result) {
			if (result) {
				jQuery.post($deletelink, {
					data : $data
				}, function(response) {
					$_response = jQuery.parseJSON(response);
					if ($_response.error != undefined) {
						$modalMsg[999] = [ 'Warning', $_response.error ];
						displayMsg('modal' + jQuery('#page_key').val(), 999);
						return false;
					}
					reloadTable();
				});
			}
		});
	});
	/* Delete action ends*/

	
	_span.append(_view);
	_span.append(_delete);
	return _span;
}



function _getRestButton(uniqueid, actionpage) {
	var _span = jQuery('<span/>').addClass('tools text-center');
	var _reset = jQuery('<a/>').attr({
		'title' : 'Reset',
		'href' : 'javascript:;',
		'data-action' : actionpage,
		'uniqueid' : uniqueid
	}).addClass('fa fa-refresh');
	_reset.click(function() {
		$resetlink = localize_var.admin_ajaxurl + "?action="
				+ localize_var.TextDomain + jQuery(this).attr('data-action');
		var $data = {};
		$data['uniqueid'] = jQuery(this).attr('uniqueid');
		bootbox.confirm("Do you want to reset this?", function(result) {
			if (result) {
				jQuery.post($resetlink, {
					data : $data
				}, function(response) {
					$_response = jQuery.parseJSON(response);
					if ($_response.error != undefined) {
						$modalMsg[999] = [ 'Warning', $_response.error ];
						displayMsg('modal' + jQuery('#page_key').val(), 999);
						return false;
					}
					location.reload();
				});
			}
		});
	});
	_span.append(_reset);
	return _span;
}

function _getStateLable($st) {
	var _curelm = jQuery('<span/>').addClass($status[$st][0]).addClass(
			' label label-mini').html($status[$st][1]);
	return _curelm;
}

function _getYesNoLable($st) {
	var _curelm = jQuery('<span/>').addClass($yesno[$st][0]).addClass(
			' label label-mini').html($yesno[$st][1]);
	return _curelm;
	//return '';
}

function _setStatusSwitch($tableName,aData,nRow){
	 var _swicth = jQuery('<input/>').attr({
		   'type' : 'checkbox',
			'class': 'sw-action switch-mini',
			'data-uniqueid' : aData.uniqueid,
			'data-on-label' : 'Yes',
			'data-off-label' :'No',
			'data-off' : 'danger',
			'data-on' : 'default'
	});
	 
	   jQuery('td.incexc-action',nRow).empty().append(_swicth);	    	   
	   jQuery(_swicth).bootstrapSwitch();	    	   
	   jQuery(_swicth).bootstrapSwitch('setState',aData.isActive == 1 , true);
	    jQuery(_swicth).on('switch-change', function(e, data) {	     	    	
	    	$action = localize_var.TextDomain+'_changeStatus';
	    	jQuery.post(
					localize_var.admin_ajaxurl +"?action="+$action,
					{				
						tblName : $tableName,
						uniqueid : jQuery(this).data('uniqueid'),
						status : data.value == true ? 1: 0
					},
					function( response ) {
						$_response = jQuery.parseJSON(response);	    						
						laodingBar.hide();
					}
				);
	    	
	    });
}

function _setSortOrderValue(data,$sortfield){
	$_val = data.length == 0 ? 1 : data.length +1 ;
	 jQuery($sortfield).val($_val);
}

function _getItemThumb(url) {
	if (isValidImageUrl(url)) {
		var _imgcontainer = jQuery('<div/>').addClass('item-thumb-ctner');
		var _thumb = jQuery('<img/>').attr('src', url).attr('alt',
				'invalid URL');
		return _imgcontainer.append(_thumb);
	}
	return 'invalid URL';
}

function _getItemThumbByDataURL(url) {
	
		var _imgcontainer = jQuery('<div/>').addClass('item-thumb-ctner');
		var _thumb = jQuery('<object/>').attr({'data': url , 'class' : 'splash'});
		return _imgcontainer.append(_thumb);
	
}

function _getVideoThumb($youtubeurl,$uniqueid){
	var $match = $youtubeurl.match(/https?:\/\/(?:[a-zA_Z]{2,3}.)?(?:youtube\.com\/watch\?)((?:[\w\d\-\_\=]+&amp;(?:amp;)?)*v(?:&lt;[A-Z]+&gt;)?=([0-9a-zA-Z\-\_]+))/);
	if($match){
		$youtubeid =  youtube_parser($youtubeurl);
		
		var _imgcontainer = jQuery('<div/>').addClass('item-thumb-ctner');
		var _thumb = jQuery('<img/>').attr('src', 'https://i.ytimg.com/vi/'+$youtubeid+'/hqdefault.jpg').attr('alt',
				'invalid URL');
		return _imgcontainer.append(_thumb);
		//return "<video id='"+$uniqueid+"' src='' class='video-js vjs-default-skin' muted='muted' width='160' height='90'>  </video>";
		//return '<video id="'+$uniqueid+'" src="" class="video-js vjs-default-skin" controls preload="auto" width="160" height="90" > </video>';
	}
	else{
		return '<span>Invalid YouTube url.</span>';
	}
}

function _getVideoEmbededCode($uniqueid){
	$url = localize_var.admin_ajaxurl + "?action="
	+ localize_var.TextDomain + '_getEmbededCode';
	jQuery.post($url, {
		uniqueid : $uniqueid
	}, function(response) {
		jQuery('#txt_embededcode_'+$uniqueid).val(response);
		//return response;
	});
}

function youtube_parser(url){
    var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
    var match = url.match(regExp);
    if (match&&match[7].length==11){
        return match[7];
    }else{
        alert("Url incorrecta");
    }
}

function _getVideoPreview($video){
	var $match = $video.youtubeurl.match(/https?:\/\/(?:[a-zA_Z]{2,3}.)?(?:youtube\.com\/watch\?)((?:[\w\d\-\_\=]+&amp;(?:amp;)?)*v(?:&lt;[A-Z]+&gt;)?=([0-9a-zA-Z\-\_]+))/);
	if($match){
		return "<video id='"+$video.uniqueid+"' src='' class='video-js vjs-default-skin' preload='none' width='"+$video.size.width+"' height='"+$video.size.height+"'>  </video>";
		//return '<video id="'+$uniqueid+'" src="" class="video-js vjs-default-skin" controls preload="auto" width="160" height="90" > </video>';
	}
	else{
		return '<span>Invalid YouTube url.</span>';
	}
}

function _getCount($str){
	return $str.split(',').length;
}
function _fnDrawCallback(){
	
}
function _setupDataTable($table, $aoColumns, $data, $fnRowCallback) {
	var $aaSorting = arguments[4] != undefined ? arguments[4] : [ [ 0, "asc" ] ];
	var $oTable = jQuery($table)
			.dataTable(
					{
						"bStateSave" : true,
						"aoColumns" : $aoColumns,
						"bProcessing" : true,
						"aaData" : $data,
						"aLengthMenu" : [ [ 5, 10, 15, 20, -1 ],
								[ 5, 10, 15, 20, "All" ] // change per page
															// values here
						],
						// set the initial value
						"iDisplayLength" : 5,
						"sDom" : "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
						"sPaginationType" : "bootstrap",
						"oLanguage" : {
							"sLengthMenu" : "_MENU_ records per page",
							"oPaginate" : {
								"sPrevious" : "Prev",
								"sNext" : "Next"
							}
						},
						"aaSorting" : $aaSorting,
						"fnRowCallback" : $fnRowCallback,
						"fnDrawCallback": function( oSettings ) {
				            // Your function(s);
							_fnDrawCallback();
				        }($data)
					});
	return $oTable;
}

function _setupDataTable_v1($table, $aoColumns, $data, $fnRowCallback,$fnDrawCallback) {
	var $aaSorting = arguments[5] != undefined ? arguments[5] : [ [ 0, "asc" ] ];
	var $oTable = jQuery($table)
			.dataTable(
					{
						"bStateSave" : true,
						"aoColumns" : $aoColumns,
						"bProcessing" : true,
						"aaData" : $data,
						"aLengthMenu" : [ [ 5, 10, 15, 20, -1 ],
								[ 5, 10, 15, 20, "All" ] // change per page
															// values here
						],
						// set the initial value
						"iDisplayLength" : 20,
						"sDom" : "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
						"sPaginationType" : "bootstrap",
						"oLanguage" : {
							"sLengthMenu" : "_MENU_ records per page",
							"oPaginate" : {
								"sPrevious" : "Prev",
								"sNext" : "Next"
							}
						},
						"aaSorting" : $aaSorting,
						"fnRowCallback" : $fnRowCallback,
						"fnDrawCallback": $fnDrawCallback
					});
	return $oTable;
}

function laodingBar(){};
laodingBar.show = function(){
	jQuery('div#load_bar_ctrl').show();jQuery('div#load_bar_ctrl2').show();
	if(jQuery('#layerProcessing').length > 0){jQuery('#layerProcessing').show();}
};
laodingBar.hide = function(){
	jQuery('div#load_bar_ctrl').hide();jQuery('div#load_bar_ctrl2').hide();
	if(jQuery('#layerProcessing').length > 0){jQuery('#layerProcessing').hide();}
};

/* Splash parameter fields genarate */
function genarateParmFields($params){
	if($params != null && $params[0] != null){
		jQuery('p#emptyMsg').hide();jQuery('div#paramList').empty();
		
		for (var int = 0; int < $params[0].length; int++) {
			var $prefix = 'paramFields.';
			var re = new RegExp('{', 'g');
			var $field_name = $params[0][int].replace(re,'');
			re = new RegExp('}', 'g');
			$field_name = $field_name.replace(re,'');
			var $type =  $field_name.substring($field_name.lastIndexOf(' ')+1,$field_name.length);
			var $fieldlabletext = $field_name;
			var $defValue = arguments[1][$fieldlabletext];
			re = new RegExp(' ', 'g');
			$field_name = $field_name.replace(re,'_');
			var $paramField = jQuery('<input/>');
			if($type == 'color'){
				$paramField = jQuery('<div/>').attr({
					'data-color-format' : 'rgba',
					'data-color' : $defValue,
					'class' : 'input-append colorpicker-default color'
				});
				
				var _input = jQuery('<input/>').attr({
							'readonly' : 'readonly',
							'type' : 'text',
							'class' : 'form-control paramField',
							'data-field-value' : 'yes',
							'data-type' : 'color',
							'data-field-name' : $prefix+$field_name,
							'required' : 'required',
							'placeholder' : $defValue,
							'name' : $prefix+$field_name,
							'value' : $defValue
						});
				
				var _span = jQuery('<span/>').attr({
					'class' : 'input-group-btn add-on'
				});
				
				var _button = jQuery('<button/>').attr({
					'class' : 'btn btn-white',
					'type' : 'button',
					'style' :'padding: 8px;'
				}).append('<i style="background-color: '+$defValue+';"></i>');
				
				_span.append(_button);
				$paramField.append(_input);
				$paramField.append(_span);
			}else{	
			$extra_css_class = $type == 'image' ? 'wp-media' : '';
			$paramField = jQuery('<input/>').attr({
				'type' : $type == 'size' || $type == 'x' || $type == 'y' || $type == 'x2' || $type == 'y2' || $type == 'width' || $type == 'height' || $type == 'duration'  ? 'number' : 'text',
				'class' : 'form-control paramField '+$extra_css_class ,
				'data-field-value' : 'yes',
				'data-field-name' : $prefix+$field_name,
				'required' : 'required',
				'placeholder' : $defValue,
				'name' : $prefix+$field_name,
				'value' : $type == 'image' ? localize_var.tempImgDirUrl+$defValue : $defValue ,
				'step' : $type == 'size' || $type == 'x' || $type == 'y' || $type == 'x2' || $type == 'y2' || $type == 'width' || $type == 'height' || $type == 'duration'  ? 'any' : '',
			});
			}
			var $formGrp = jQuery('<div/>').attr({'class' : 'form-group'});
			var $fieldlable = jQuery('<label/>').attr({
				'class' : 'control-label col-md-3',
				'for' :  $prefix+$field_name				
			});
			$fieldlable.html($fieldlabletext); //<label class="control-label col-md-3" for="position.x">Position</label>
			var $fieldContainer = jQuery('<div/>').attr({'class' : 'col-lg-8'});
			$formGrp.append($fieldlable);
			$formGrp.append($fieldContainer.append($paramField));
			jQuery('div#paramList').append($formGrp);
		}
		jQuery('.colorpicker-default').colorpicker();
	}
}

function OpenMideaLibDialog($elem){
	$elem.click( function( event ) {
		wp.media.editor.open(jQuery(this));
	});
	
	wp.media.editor.send.attachment = function( a, b) {
		$elem.val(b.url);
	};
}

/* Number Functions */ 
function formatNumber(n) {
    return "$ "+n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function roundNumber(num, dec) {
	   var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	   return result;
}

function validatePhone(txtPhone) {
    var a = txtPhone;
    var filter = /\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/;
    if (filter.test(a)) {
        return true;
    }
    else {
        return false;
    }
}

/* Download JSON to CSV */
function DownloadJSON2CSV(objArray)
{
    var array = typeof objArray != 'object' ? JSON.parse(objArray) : objArray;

    var str = '';

    for (var i = 0; i < array.length; i++) {
        var line = '';

        for (var index in array[i]) {
            line += array[i][index] + ',';
        }

        // Here is an example where you would wrap the values in double quotes
        // for (var index in array[i]) {
        //    line += '"' + array[i][index] + '",';
        // }

        line.slice(0,line.Length-1); 

        str += line + '\r\n';
    }
    window.open( "data:text/csv;charset=utf-8," + escape(str));
}

// JSON to CSV Converter
function ConvertToCSV(objArray) {
    var array = typeof objArray != 'object' ? JSON.parse(objArray) : objArray;
    var str = '';

    for (var i = 0; i < array.length; i++) {
        var line = '';
        for (var index in array[i]) {
            if (line != '') line += ',';
            line += array[i][index];
        }

        str += line + '\r\n';
    }

    return str;
}

function JSONToCSVConvertor(JSONData, ReportTitle, ShowLabel) {     

	//If JSONData is not an object then JSON.parse will parse the JSON string in an Object
	var arrData = typeof JSONData != 'object' ? JSON.parse(JSONData) : JSONData;
	var CSV = '';    
	//This condition will generate the Label/Header
	if (ShowLabel) {
	    var row = "";

	    //This loop will extract the label from 1st index of on array
	    for (var index in arrData[0]) {
	        //Now convert each value to string and comma-seprated
	        row += index + ',';
	    }
	    row = row.slice(0, -1);
	    //append Label row with line break
	    CSV += row + '\r\n';
	}

	//1st loop is to extract each row
	for (var i = 0; i < arrData.length; i++) {
	    var row = "";
	    //2nd loop will extract each column and convert it in string comma-seprated
	    for (var index in arrData[i]) {
	        row += '"' + arrData[i][index] + '",';
	    }
	    row.slice(0, row.length - 1);
	    //add a line break after each row
	    CSV += row + '\r\n';
	}

	if (CSV == '') {        
	    alert("Invalid data");
	    return;
	}   

	//this trick will generate a temp "a" tag
	var link = document.createElement("a");    
	link.id="lnkDwnldLnk";

	//this part will append the anchor tag and remove it after automatic click
	document.body.appendChild(link);

	var csv = CSV;  
	blob = new Blob([csv], { type: 'text/csv' }); 
	var csvUrl = createObjectURL(blob);
	var filename = 'UserExport.csv';
	jQuery("#lnkDwnldLnk")
	.attr({
	    'download': filename,
	    'href': csvUrl
	}); 

	jQuery('#lnkDwnldLnk')[0].click();    
	document.body.removeChild(link);
}


/* wrapper functions */
function createObjectURL ( file ) {
    if ( window.webkitURL ) {
        return window.webkitURL.createObjectURL( file );
    } else if ( window.URL && window.URL.createObjectURL ) {
        return window.URL.createObjectURL( file );
    } else {
        return null;
    }
}

/* Change Date format ***/
function convertDate(dateString){
	var p = dateString.split(/\D/g);
	return [p[1],p[2],p[0] ].join("/");
}