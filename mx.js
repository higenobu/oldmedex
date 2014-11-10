// -*- coding: euc-jp -*-
var getPosition;
var __document__    =   document;

function    is_getBoundingClientRect()
{
    //return    (typeof Element.prototype.getBoundingClientRect ==  'function')
    var dummy   =   __document__.createElement("div");
    return  (dummy.getBoundingClientRect)!==undefined;
};

if( is_getBoundingClientRect()  )   //  getBoundingClientRectでの座標取得
    {
	//  モード判別:サファリ等は強制的に互換モードのルーチンを使う
	if( __document__.compatMode=='CSS1Compat'   &&  undefined===window.defaultstatus    )
	    {   //  DOCTYPE 標準準拠モード
		var __getPosition_base__    =   __document__.documentElement;
		//
		getPosition =   function(el)
		    {
			var pos =   el.getBoundingClientRect();
			return  {   x:(pos.left +   __getPosition_base__.scrollLeft -   __getPosition_base__.clientLeft)
				,   y:(pos.top  +   __getPosition_base__.scrollTop  -   __getPosition_base__.clientTop) };
		    };
	    }
	else
	    {   //  DOCTYPE その他互換モード
		//  var __getPosition_base__    =   document;
		//
		getPosition =   function(el)
		    {
			var pos =   el.getBoundingClientRect();
			var bd  =   __document__.body;
			return  {   x:(pos.left +   bd.scrollLeft)
				,   y:(pos.top  +   bd.scrollTop)   };
		    };
	    }
    }
else
    {   //  getBoundingClientRectが無い
	if( undefined !== window.opera  )
	    {
		getPosition =   function(el)
		    {
			var ex  =   0;
			var ey  =   0;
			do
			    {
				ex  +=  el.offsetLeft;
				ey  +=  el.offsetTop;
			    }
			while(  el  =   el.offsetParent );
			//
			return  {x:ex,y:ey};
		    };
	    }
	else
	    {
		getPosition =   function(target)
		    {
			var ex  =   0;
			var ey  =   0;
			//
			var el  =   target;
			do
			    {
				ex  +=  el.offsetLeft;
				ey  +=  el.offsetTop;
			    }
			while(  el  =   el.offsetParent );
			//  要素内スクロール対応
			var el  =   target;
			var bd  =   __document__.body;
			do
			    {
				ex  -=  el.scrollLeft;
				ey  -=  el.scrollTop;
				el  =   el.parentNode;
			    }
			while(  el!=bd  );
			//
			return  {x:ex,y:ey};
		    };
	    }
    }

function getElementsByClass(searchClass,node,tag) {
	var classElements = new Array();
	if ( node == null )
		node = document;
	if ( tag == null )
		tag = '*';
	var els = node.getElementsByTagName(tag);
	var elsLen = els.length;
	var pattern = new RegExp("(^|\\s)"+searchClass+"(\\s|$)");
	for (i = 0, j = 0; i < elsLen; i++) {
		if ( pattern.test(els[i].className) ) {
			classElements[j] = els[i];
			j++;
		}
	}
	return classElements;
}

function printThisWindow(it) {
	it.focus();
	it.print();
}

function disableEnterKey(field, event) {
  var keyCode = event.keyCode ? event.keyCode
                              : event.which ? event.which
                                            : event.charCode;
  if (keyCode == 13) {
    var i;
    for (i = 0; i < field.form.elements.length; i++)
      if (field == field.form.elements[i]) break
    i = (i + 1) % field.form.elements.length;
    field.form.elements[i].focus();
    return false;
  } else
    return true;
}

function setAndSubmit(me, name, value, append) {
    // geez! JavaScript cannot handle names with - in it??
    var i;
    form = getFirstParentByTagAndClassName(me, 'FORM');
    for (i = 0; i < form.elements.length; i++)
	if (form.elements[i].name == name) {
	    if (append)
		form.elements[i].value += value;
	    else
		form.elements[i].value = value;
	    break;
	}
}

function activateInnerAnchor(it) {
    document.location = it.firstChild.href;
}

function activateInnerButton(it) {
    it.firstChild.click();
}

function findPosXY(obj)
{
	foundLeft = foundTop = 0;
	if (obj.offsetParent) {
		while (obj.offsetParent) {
			foundLeft += obj.offsetLeft;
			foundTop  += obj.offsetTop;
			obj = obj.offsetParent;
		}
	}
	else {
		foundLeft = obj.x;
		foundTop = obj.y;
	}
}

function toggle_ppa_list()
{
	it = document.getElementById('ppa-list-here');
	div = document.getElementById('ppa-applink');
	if (!it || !div)
		return;
	onoff = div.style.visibility == "visible";
	onoff = !onoff;
	findPosXY(it);
	if (!onoff)
		div.style.visibility = "hidden";
	else {
		div.style.left = foundLeft;
		div.style.top = foundTop + 20;
		div.style.visibility = "visible";
	}
}

function appbar_pop(ix, appbar_end)
{
	it = document.getElementById('appbar-anc-' + ix);
	div = document.getElementById('appbar-list-' + ix);
	if (!it || !div || !it.parentNode)
		return;

	it = it.parentNode;
	onoff = div.style.visibility == "visible";
	onoff = !onoff;
	findPosXY(it);

	if (!onoff) {
		div.style.visibility = "hidden";
	}
	else {
		div.style.left = foundLeft;
		div.style.visibility = "visible";
	}
	for (iy = 0; iy < appbar_end; iy++) {
		if (iy == ix)
			continue;
		div = document.getElementById('appbar-list-' + iy);
		if (!div)
			continue;
		div.style.visibility = "hidden";
	}

}

function foldDiv(div_id, cntl_id)
{
	div = document.getElementById(div_id);
	cntl = document.getElementById(cntl_id);

	if (div == null || cntl == null)
		return;

	if (cntl.checked) {
		div.style.display = '';
	}
	else {
		div.style.display = 'none';
	}
}

function check_blob_and_replace(blobid, target) {
	var s = null;
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.open("GET", "/blobmedia.php?exists=" + blobid, false);
	xmlhttp.send(null);
	s = xmlhttp.responseText;
	if(s != "Exists") {
		return false;
	}

	u = '/blobmedia.php?id=' + blobid + '&rand=' + Math.random();

	// image src
	e = document.getElementById(target);
	e.src = u;

	// anchor for the image
	ae = document.getElementById("a-" + target);
	ae.href = "/svc/launcher.php/ext_edit.MedexDraw?blobid=" +
		   escape(blobid) + "&img_url=" + escape(u);

	e2 = document.createElement("input");
	e2.type="hidden";
	e2.name = target + "2d6964"; // -id
	e2.value = blobid;
	document.forms[0].appendChild(e2);

	return false;
}

function show_hide(idname, rsrc) {

	var show = rsrc + "/images/show.png";
	var hide = rsrc + "/images/hide.png";
	var id = 'SHD-' + idname;
	var icon = 'SHC-' + idname;
	var alt = 'SHA-' + idname;

	if(document.all) {
		// IE
		var objId = document.all(id).style;
		var objIcon = document.all(icon);
		var altObj  = document.all(alt);
	}
	else {
		// Others
		var objId = document.getElementById(id).style;
		var objIcon = document.getElementById(icon);
		var altObj = document.getElementById(alt);
	}

	if (objId.display=='none') {
		// show
		objId.display = '';
		objIcon.src = show;
		if (altObj)
			altObj.style.display = 'none';
	}
	else {
		objId.display ='none';
		objIcon.src = hide;
		if (altObj)
			altObj.style.display = '';
	}
}

function showhide_next_row(row) {
	var next = row.nextSibling;
	var shown = next.style.display;
	if (shown == 'none')
		shown = '';
	else
		shown = 'none';
	next.style.display = shown;
}

function pick_multi_checkbox(it) {
	var them = it.value.split(' ');
	var base = them[0];
	for (var i = 1; i < them.length; i++) {
		var s = them[i];
		var onoff = "on";
		var checked = true;
		if (s.substr(0,1) == '-') {
			onoff = "off";
			checked = false;
		}
		s = base + '-' + s.substr(1);
		var e = document.getElementById(s);
		if (e) {
			e.value = onoff;
			e.checked = checked;
		}
	}
}

function mx_submit_button(it, name, value) {
	var hidden = document.createElement('input');
	hidden.setAttribute('name', name);
	hidden.setAttribute('value', value);
	hidden.setAttribute('type', 'hidden');
	p = it.parentNode
	p.appendChild(hidden)
	while (p && (p.nodeType != 1 || p.nodeName != 'FORM'))
		p = p.parentNode
	if (p)
		p.submit();
	return false;
}

//------- show index-pt on right click
function lazily_load_index_pt(div)
{

	var pid = div.attributes['pid'].value;
	var poid = div.attributes['poid'].value;
	var uprefix = div.attributes['uprefix'].value;

	if (pid != '' && poid != '' && uprefix != '') {

		var xmlhttp = new XMLHttpRequest();
		xmlhttp.open("GET",
				uprefix +
				"svc/ppa-index.php?poid=" +
				poid + "&pid=" + pid, false);
		xmlhttp.send(null);
		s = xmlhttp.responseText;
		div.innerHTML = s;
		div.setAttribute('uprefix', '');
		div.setAttribute('pid', '');
		div.setAttribute('poid', '');
	}



}

function toggle_index_pt()
{

	div = document.getElementById('ppa-index');
	if (!div)
		return;

	xxx = getElementsByClass('drawapp_div', document, 'div');
	onoff = div.style.visibility == "visible";
	onoff = !onoff;

	if (!onoff) {
		div.style.visibility = "hidden";
		o = document.forms[0].elements[32];
		//alert(o);
		o.focus();
		for(i=0; i < xxx.length; i++)
		    xxx[i].style.visibility = "visible";
	} else {
		lazily_load_index_pt(div);

		div.style.left = 0;
		div.style.top = 40;
		div.style.visibility = "visible";
		for(i=0; i < xxx.length; i++)
		    xxx[i].style.visibility = "hidden";
	}




}

function noright(){
//07-01-2012	toggle_index_pt();
	return false;
}

//07-2012
document.oncontextmenu = true;

var cookie = {};

cookie.get = function(name) {
    var regexp = new RegExp('; ' + name + '=(.*?);');
    var match  = ('; ' + document.cookie + ';').match(regexp);
    return match ? decodeURIComponent(match[1]) : '';
}

cookie.set = function(name, value) {
    var buf = name + '=' + encodeURIComponent(value);
    document.cookie = buf + '; expires=Mon, 31-Dec-2029 23:59:59 GMT';
}

// -- show/hide caledit elements
function showhide_caledit(it) {
	showhide_caledit_one = function (name, hide) {
		e = document.getElementById(name);
		if (e) {
			if (hide)
				e.style.visibility = 'hidden';
			else
				e.style.visibility = 'visible';
		}
	}

	e = document.getElementById("D_rule_" + it);
	if (e) {
		week_mode = (e.value == 'W');
		showhide_caledit_one("E_mday_" + it, week_mode);
		showhide_caledit_one("E_nth_" + it, !week_mode);
		showhide_caledit_one("E_wday_" + it, !week_mode);
	}
}
function showhide_caledit_all(them) {
	for (i = 0; i < them; i++)
		showhide_caledit(i);
}

function mx_date_is_holiday(y, m, d, dow)
{
	var date = new Date(y,m-1,d,0,0,0,0);
	d = formatDate(date, "yyyy-MM-dd");
	if (typeof window['mx_holiday_table'] === 'undefined')
		mx_holiday_table = {};
	if (mx_holiday_table[d])
		return (mx_holiday_table[d] > 0);
	if (dow == 0)
		return 1;
	return 0;
}

function mx_preload_holiday_table()
{
	var ym;

	if (!arguments.length)
		ym = formatDate(new Date(), 'yyyy-MM');
	else
		ym = arguments[0];
	if (typeof window['mx_holiday_table'] === 'undefined')
		mx_holiday_table = {};
	if (mx_holiday_table[ym])
		return;
	var url = "/svc/json_holiday_table.php?range=5&ym=" + ym;
	var d = loadJSONDoc(url);
	var gotdata = function (data) {
		for (var date in data)
			mx_holiday_table[date] = data[date];
	}
	mx_holiday_table[ym] = 1;
	d.addCallback(gotdata);
}

function mx_preload_holiday_opportunistic(ym)
{
	// How much do we know?
	var y = parseInt(ym.substring(0,4));
	var m = ym.substring(5,7);
	if (m.substring(0,1) == '0')
		m = m.substring(1);
	m = parseInt(m)
	var counter = function(y0, m0, direction) {
		var y = y0;
		var m = m0;
		var count = 0;
		while (1) {
			var fom = new Date(y, m-1, 1, 0, 0, 0, 0);
			var ym = formatDate(fom, 'yyyy-MM');
			if (!mx_holiday_table[ym])
				return count;
			count++;
			m = m + direction;
			while (m < 1) {
				m += 12;
				y--;
			}
			while (12 < m) {
				m -= 12;
				y++;
			}
		}
	};
	var past = counter(y, m, -1);
	var future = counter(y, m, 1);
	var fom = null;
	var ym = null;
	var m0 = null;
	var y0 = null;
	if (past < 5) {
		m0 = m - (past + 5);
		y0 = y
		while (m0 < 1) {
			m0 += 12;
			y0--;
		}
		fom = new Date(y0, m0-1, 1, 0, 0, 0, 0);
		ym = formatDate(fom, 'yyyy-MM');
		mx_preload_holiday_table(ym);
	}
	if (future < 5) {
		m0 = m + (future + 5);
		y0 = y
		while (12 < m0) {
			m0 -= 12;
			y0++;
		}
		fom = new Date(y0, m0-1, 1, 0, 0, 0, 0);
		ym = formatDate(fom, 'yyyy-MM');
		mx_preload_holiday_table(ym);
	}
}

function Medex_CP_getCalendar() {
	var current = null;
	var year = null;
	var month = null;

	if (this.currentDate==null)
		current = new Date();
	else
		current = this.currentDate;
	month = current.getMonth() + 1;
	year = current.getFullYear();

	// Figure out what year and months CP_getCalendar wants...
	if (arguments.length > 1 &&
	    arguments[1]-0 == arguments[1] && arguments[1] > 1000)
		year = arguments[1];
	if (arguments.length &&
	    1 <= arguments[0] && arguments[0] <= 12)
		month = arguments[0];

	if (typeof window['mx_holiday_table'] === 'undefined')
		mx_holiday_table = {};

	var fom = new Date(year, month-1, 1, 0, 0, 0, 0);
	var ym = formatDate(fom, 'yyyy-MM');

	mx_preload_holiday_opportunistic(ym);

	// previous month
	if (month == 1)
		fom = new Date(year - 1, 11, 1, 0, 0, 0, 0);
	else
		fom = new Date(year, month-2, 1, 0, 0, 0, 0);
	var ym0 = formatDate(fom, 'yyyy-MM');

	// next month
	if (month == 11)
		fom = new Date(year + 1, 0, 1, 0, 0, 0, 0);
	else
		fom = new Date(year, month, 1, 0, 0, 0, 0);
	var ym1 = formatDate(fom, 'yyyy-MM');
	var contents;

	if ((mx_holiday_table[ym] != 2) ||
	    (mx_holiday_table[ym0] != 2) ||
	    (mx_holiday_table[ym1] != 2)) {

		var url = "/svc/json_holiday_table.php?ym=" + ym;
		var d = loadJSONDoc(url);
		var arg = arguments;
		var it = this;
		var gotdata = function (data) {
			for (var date in data)
				mx_holiday_table[date] = data[date];
			var contents;
			if (arg.length)
				contents = it.gotCalendar(arg[0],
							  arg[1],
							  arg[2],
							  arg[3],
							  arg[4]);
			else
				contents = it.gotCalendar();
			it.populate(contents);
			it.refresh();
		}
		mx_holiday_table[ym] = 1;
		contents = '<div class="cpwait"></div>';
		d.addCallback(gotdata);
	}

	else if (arguments.length)
		contents = this.gotCalendar(arguments[0],
					    arguments[1],
					    arguments[2],
					    arguments[3],
					    arguments[4]);
	else
		contents = this.gotCalendar();

	return contents;
}

function MedexCalendarPopup() {
	var c;
	if (arguments.length) {
		c = new CalendarPopup(arguments[0]);
	} else {
		c = new CalendarPopup();
	}
	c.getCalendar = Medex_CP_getCalendar;
	c.gotCalendar = CP_getCalendar;
	return c;
}

function print_frame(id) {
    var frame = document.getElementById(id);
    if(frame == null) {
	alert("Framd id " + id + " not found");
	return;
    }
    parent.frames[id].focus();
    parent.frames[id].print();
    return false;
}

function subpick_enum(it, sel, desel, go, activate)
{
	var e = document.getElementById(sel);
	var ix = e.selectedIndex;
	if (activate < 0)
		activate = e.options.length + activate;
	if (activate == e.selectedIndex) {
		mx_submit_button(it, go, desel);
	}
}

var toolTip = null;
var shadow = null;

function tooltip_on(e, event) {
    toolTip = document.createElement('DIV');
    shadow = document.createElement('DIV');
    with (toolTip.style) {
	position = 'absolute';
	backgroundColor =  'ivory';
	border = '1px solid #333';
	padding = '1px 3px 1px 3px';
	font = '500 11px arial';
	zIndex = 10000;
    };
    
    with (shadow.style) {
	position = 'absolute';
	MozOpacity = 0.3;
	MozBorderRadius = '3px';
	background = '#000';
	zIndex = toolTip.style.zIndex - 1;
    };


    var _title = e.getAttribute('title');
    e.setAttribute('title', '');

    if (_title == null || _title == '')
	return;

    document.body.appendChild(toolTip);
    document.body.appendChild(shadow);

    toolTip.style.left = 20 + document.documentElement.scrollLeft + event.clientX + 'px';
    toolTip.style.top = 10 + document.documentElement.scrollTop + event.clientY + 'px';
    toolTip.innerHTML = _title.replace(/\|/g,'<br />').replace(/\s/g,' ');

    with (shadow.style){
	width = toolTip.offsetWidth -2 + 'px';
	height = toolTip.offsetHeight -2 + 'px';
	left = parseInt(toolTip.style.left) + 5 + 'px';
	top = parseInt(toolTip.style.top) + 5 + 'px';
    }
}

function tooltip_off(e, event) {
    e.title = toolTip.innerHTML;
    document.body.removeChild(toolTip);
    document.body.removeChild(shadow);
}

var icd10_element_id;
var disease_element_id;
function ICD10Popup(auth, _icd10, _disease) {
    icd10_element_id = _icd10;
    disease_element_id = _disease;
    day = new Date();
    id = day.getTime();
    URL = auth + '/svc/icd10.php';
    eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=300');");
}

function setICD10(icd, dis) {
       o = window.document;
       o.getElementById(icd10_element_id).value = icd;
       e = o.getElementById(disease_element_id);
       if (e.type == 'text')
	   e.value = dis;
       else
	   e.innerHTML = dis;
}

function emptify(id) {
	o = window.document;
	o.getElementById(id).value = '';
}

function update_generic_ok(e) {
	v = e.value;
	for (i = 0; i < 100; i++) {
		cb = document.getElementById('med' + i + 'generic_ok');
		if (cb == null)
			continue;
		cb.checked = v == 1;
	}
}


function limitChars(target,maxlength,remain_div) {
    remain = maxlength - target.value.length;
    if (remain <0) {
	msg = "<font color=red>" + Math.abs(remain) + "\u6587\u5b57\u8d85\u904e</font>";
        //target.value = target.value.substr(0,maxlength);
    }
    else
	msg = "\u6b8b\u308a"+remain+"\u6587\u5b57";

    document.getElementById(remain_div).innerHTML = msg;
    target.focus();
}
