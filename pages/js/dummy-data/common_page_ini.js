function tableTopActionButtons(_tragetModel, _modelTitle,_appendTo) {
    var _markup = ' <div class="clearfix"><div class="btn-group"><a id="editable-sample_new" class="btn btn-primary" data-toggle="modal" href="#' + _tragetModel + '"> Add New <i class="fa fa-plus"></i></a></div><div class="btn-group pull-right"><button class="btn btn-default dropdown-toggle" data-toggle="dropdown"> Tools <i class="fa fa-angle-down"></i></button><ul class="dropdown-menu pull-right"><li><a href="#">Print</a></li><li><a href="#">Save as PDF</a></li><li><a href="#">Export to Excel</a></li></ul></div></div><div class="space15"></div>';
    var _dialogMarkUp = ' <div class="modal fade" id="' + _tragetModel + '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">' + _modelTitle + '</h4></div><div class="modal-body"> New data enter form gose here... </div><div class="modal-footer"><button data-dismiss="modal" class="btn btn-default" type="button">Close</button><button class="btn btn-success" type="button">Save changes</button></div></div></div></div>';
    jQuery(_appendTo).prepend(_dialogMarkUp);
    jQuery(_appendTo).prepend(_markup);
}

function injectAddNewBtn(_tragetModel, _modelTitle,_appendTo,$link) {
	if($link != undefined){
		$link = location.protocol + '//' + location.host + location.pathname + $link;
	}
	else
		$link  = '#';
    var _markup = '<div class="btn-group  pull-right"><a id="editable-sample_new" class="btn btn-primary" data-toggle="modal" href="' + $link + '"> Add New <i class="fa fa-plus"></i></a></div><div class="space15"></div>';
  //  var _dialogMarkUp = ' <div class="modal fade" id="' + _tragetModel + '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">' + _modelTitle + '</h4></div><div class="modal-body"> New data enter form gose here... </div><div class="modal-footer"><button data-dismiss="modal" class="btn btn-default" type="button">Close</button><button class="btn btn-success" type="button">Save changes</button></div></div></div></div>';
  //  jQuery('body').prepend(_dialogMarkUp);
    jQuery(_appendTo).prepend(_markup);
}

function injectBtn(_tragetModel, _modelTitle,_appendTo,$link,$btnText) {
	if($link != undefined){
		$link = location.protocol + '//' + location.host + location.pathname + $link;
	}
	else
		$link  = '#';
    var _markup = '<div class="btn-group  pull-right"><a id="editable-sample_new" class="btn btn-primary" data-toggle="modal" href="' + $link + '">'+$btnText+'<i class="fa fa-plus"></i></a></div><div class="space15"></div>';
  //  var _dialogMarkUp = ' <div class="modal fade" id="' + _tragetModel + '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">' + _modelTitle + '</h4></div><div class="modal-body"> New data enter form gose here... </div><div class="modal-footer"><button data-dismiss="modal" class="btn btn-default" type="button">Close</button><button class="btn btn-success" type="button">Save changes</button></div></div></div></div>';
  //  jQuery('body').prepend(_dialogMarkUp);
    jQuery(_appendTo).prepend(_markup);
}

function injectBackBtn(_tragetModel, _modelTitle,_appendTo,$link) {
	if($link != undefined){
		$link = location.protocol + '//' + location.host + location.pathname + $link;
	}
	else
		$link  = '#';
    var _markup = '<div class="btn-group  pull-right"><a id="editable-sample_new" class="btn btn-primary" data-toggle="modal" href="' + $link + '"> Back <i class="fa fa-mail-reply"></i></a></div><div class="space15"></div>';
  //  var _dialogMarkUp = ' <div class="modal fade" id="' + _tragetModel + '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">' + _modelTitle + '</h4></div><div class="modal-body"> New data enter form gose here... </div><div class="modal-footer"><button data-dismiss="modal" class="btn btn-default" type="button">Close</button><button class="btn btn-success" type="button">Save changes</button></div></div></div></div>';
  //  jQuery('body').prepend(_dialogMarkUp);
    jQuery(_appendTo).prepend(_markup);
}

