function randomString(length) {
    if (length <= 0) return "";
    var getChunk = function () {
        var i, //index iterator
            rand = Math.random() * 10e16, //execute random once
            bin = rand.toString(2).substr(2, 10), //random binary sequence
            lcase = (rand.toString(36) + "0000000000").substr(0, 10), //lower case random string
            ucase = lcase.toUpperCase(), //upper case random string
            a = [lcase, ucase], //position them in an array in index 0 and 1
            str = ""; //the chunk string
        b = rand.toString(2).substr(2, 10);
        for (i = 0; i < 10; i++)
            str += a[bin[i]][i]; //gets the next character, depends on the bit in the same position as the character - that way it will decide what case to put next
        return str;
    },
    str = ""; //the result string
    while (str.length < length)
        str += getChunk();
    str = str.substr(0, length);
    return str;
}
function getRandomDate(from, to) {
    if (!from) {
        from = new Date(1900, 0, 1).getTime();
    } else {
        from = from.getTime();
    }
    if (!to) {
        to = new Date(2100, 0, 1).getTime();
    } else {
        to = to.getTime();
    }
    return new Date(from + Math.random() * (to - from));
}

function formatDate($date) {
    var options = {
        weekday: "long", year: "numeric", month: "short",
        day: "numeric", hour: "2-digit", minute: "2-digit"
    };
    return $date.format("m/dd/yyyy"); // toLocaleDateString("en-us", options)
}

function dummyText($appendTo, length) {
    var str = 'Your Description Text Goes Here <br> ( maximum 50 characters allowed) ';
    if (length == undefined)  jQuery($appendTo).append(str);
    jQuery($appendTo).append( str.substr(0, length));
}

function getDummyText( length) {
    var str = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.';
    if (length == undefined)  return str;
    return str.substr(0, length);
}

function defaultTableSettings() {
    var $tblSettings = {
        "noOfRows": 25
    };
    return $tblSettings;
}


function DummyTable(clsTbl, colList, $appendTo, idTbl,tblSettings) {
    if (idTbl == undefined) {
        idTbl = randomString(8);
    }
    var elmTable = jQuery('<table/>').attr('id', idTbl).addClass(clsTbl);
    var elmTableHead = jQuery('<thead/>');
    var elmTableRow = jQuery('<tr/>');
    
    jQuery.each(colList, function () {
        var elmTh = jQuery('<th/>').html(this['sName']);
        if (this['sClass'] != undefined)
            elmTh.addClass(this['sClass']);       
        elmTableRow.append(elmTh);
    });
    elmTableHead.append(elmTableRow);
    elmTable.append(elmTableHead);

    var elmTableBody = jQuery('<tbody/>');

    if (tblSettings == undefined) tblSettings = defaultTableSettings();

    elmTableBody = bodyTD(elmTableBody, colList, tblSettings);

    elmTable.append(elmTableBody);

    elmTable.appendTo($appendTo);

   
    var $oTable = $(elmTable).dataTable({
            "aLengthMenu": [
                [5,10, 15, 20, -1],
                [5,10, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "iDisplayLength": 5,
            "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            }
       });

       //$oTable.$('td').editable('#', { "callback": function (sValue, y) { var aPos = oTable.fnGetPosition(this); oTable.fnUpdate(sValue, aPos[0], aPos[1]); }, "submitdata": function (value, settings) { return { "row_id": this.parentNode.getAttribute('id'), "column": oTable.fnGetPosition(this)[2] }; }, "height": "14px", "width": "100%" });
    
}

function bodyTD(elmTableBody, colList, tblSettings, nameList) {
    for (var i = 0; i < tblSettings['noOfRows']; i++) {
        var elmTableRow = jQuery('<tr/>'); var _name = '';
        jQuery(colList).each(function () {
            var elmTd = jQuery('<td/>');
            if (this['sDataType'] != undefined) {                
                switch (this['sDataType']) {
                    case 'country': {
                        var _count = $countries.length - 1;
                        var _randNum = chance.integer({ min: 0, max: _count-1 }); 
                        elmTd.html($countries[_randNum]);
                    } break;
                    case 'name': {
                        _name = chance.first();
                        elmTd.html(_name);
                    } break;
                    case 'email': {
                        elmTd.html(_name + '.' + chance.email());
                    } break;
                    case 'date': {
                        var _datenow = new Date();
                        var _from = new Date();
                        _from.setMonth(_from.getMonth() - 1);
                        var _date = getRandomDate(_from, _datenow);
                        elmTd.html(formatDate(_date));
                    } break;
                    case 'phone': {
                        elmTd.html(chance.phone());
                    } break;
                    case 'bool': {
                        elmTd.html(chance.bool()?'Yes':'No');
                    } break;
                    case 'status': {
                        var _count = this['sRandClasses'].length;
                        var _randNum = chance.integer({ min: 0, max: _count-1 }); 
                        var _lblrandClass = this['sRandClasses'][_randNum];
                        var _curelm = jQuery('<span/>').addClass(_lblrandClass).html(this['sRandData'][_lblrandClass]);
                        if (this['sDataClasses'] != undefined) _curelm.addClass(this['sDataClasses']);
                        elmTd.html(_curelm);
                    } break;
                    case 'prograss': {
                        var _elemProg = jQuery('<div/>').addClass('progress progress-striped progress-xs');
                        var _elemProgBar = jQuery('<div/>').addClass('progress-bar')
                        .attr({
                            'aria-valuemax' : 100,
                            'aria-valuemin': 0,
                            'aria-valuenow': 40,
                            'role': 'progressbar',
                            'style': 'width: ' + chance.integer({ min: 10, max: 100 }) + '%'
                        });
                        var _count = this['sRandClasses'].length-1;
                        var _pgbrandClass = 'progress-bar-'+this['sRandClasses'][chance.integer({ min: 0, max: _count })];
                        _elemProgBar.addClass(_pgbrandClass);
                        var _elemProgBarSpan = jQuery('<span/>').addClass('sr-only').html('90% Complete (success)');
                        _elemProgBar.append(_elemProgBarSpan);
                        _elemProg.append(_elemProgBar);
                        elmTd.html(_elemProg);                     
                    } break;
                    case 'mplocation': {
                        var _count = Object.keys($mpLocation).length - 1;
                        var _randNum = chance.integer({ min: 0, max: _count - 1 });
                        elmTd.html($mpLocation[_randNum][0]);
                    } break;
                    case 'action': {
                        elmTd.html('<span class="tools pull-right"><a title="Copy" class="fa fa-copy" href="javascript:;"></a><a title="Edit" class="fa fa-edit" href="javascript:;"></a><a  title="Delete" class="fa fa-trash-o" href="javascript:;"></a></span>');
                    } break;
                    case 'URL': {
                        var _url = 'www.' + chance.domain();
                        var _aelm = $('<a/>').attr('href', 'http://' + _url).html(_url); elmTd.html(_aelm);
                    } break;
                    case 'seqnumber': {
                        if (this['sIniNum'] != undefined && this['sSeed'] != undefined){
                            var _number = this['sIniNum'] + (this['sSeed'] * i);
                            if (this['sDataPrefix'] != undefined) _number = this['sDataPrefix'] + _number;
                            elmTd.html(_number);
                        }
                        
                    } break;
                    case 'const': {
                        if (this['sType'] != undefined && this['sType'] == 'number') elmTd.html(this['sValue']);
                        if (this['sType'] != undefined && this['sType'] == 'string') elmTd.html(this['sValue'])
                    } break;
                    case 'city': {
                        elmTd.html(chance.city());
                    } break;
                    case 'randNumber': {
                        var _randnumber = chance.integer();
                        if (this['sMin'] != undefined && this['sMax'] != undefined) _randnumber = chance.integer({ min: this['sMin'], max: this['sMax'] });
                        if (this['sPostFix'] != undefined) _randnumber = _randnumber + this['sPostFix'];
                        elmTd.html(_randnumber);
                    } break;
                    case 'randJSONarry': {
                        if (this['sJsonArray'] != undefined) {
                            var _lenght = this['sJsonArray'].length - 1;
                            var _randIndex = chance.integer({ min: 0, max: _lenght });
                            var _defIndex = 0;
                            if (this['sIndex'] != undefined) _defIndex = this['sIndex'];
                            elmTd.html(this['sJsonArray'][_randIndex][_defIndex]);
                        }
                    } break;
                    case 'dummyText':{
                    	 var _randnumber = chance.integer();
                         if (this['sMin'] != undefined && this['sMax'] != undefined) _randnumber = chance.integer({ min: this['sMin'], max: this['sMax'] });
                         if (this['sPostFix'] != undefined) elmTd.html(getDummyText(_randnumber)+this['sPostFix']) ;
                         elmTd.html(getDummyText(_randnumber));
                    } break;
                    default: elmTd.html('');
                }               
            }
            elmTableRow.append(elmTd);
        });
        
        elmTableBody.append(elmTableRow);
    }
    return elmTableBody;
}

function LoadList($parent, $itemElm, $data) {
    $($parent).children().remove();
    $.each($data, function ($key, $value) {
        $parent.append($($itemElm).append($('<a/>').html($value[0]).attr('href','#')));
    });
}

function LoadSelectList($parent, $itemElm, $data) {
    $($parent).children().remove();
    $.each($data, function ($key, $value) {
        $parent.append($($itemElm).html($value));
    });
}

