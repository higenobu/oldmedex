<?php // -*- coding: utf8 -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

header("content-type: application/x-javascript");

$list = mx_drawapp_image_list();
if (!is_array($list) || !count($list)) {
	print "var imageList = '';\n";
} else {
	print "var imageList = '\\\n";
	foreach ($list as $title => $elems) {
		$e = mb_convert_encoding($title, 'HTML-ENTITIES', 'eucJP-win');
		print "<i label=\"$e\">\\\n";
		foreach ($elems as $e) {
			print "  <i label=\"$e\" source=\"$e\"/>\\\n";
		}
		print "</i>\\\n";
	}
	print "';\n";
	print "imageList = '&imageList=' + escape(imageList);\n";
}

?>
function copy_val(id, textbox_id) {
    var e = document.getElementById(textbox_id);
    if (e == null)
	return;
    eval('e.value=' + id + ".getData()");
    return false;
}

function _draw_drawapp(appid, textbox_id, data, readonly) {
    var requiredMajorVersion = 9;
    var requiredMinorVersion = 0;
    var requiredRevision = 124;
    var hasProductInstall = DetectFlashVer(6, 0, 65);
    
    // Version check based upon the values defined in globals
    var hasRequestedVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
    
    if ( hasProductInstall && !hasRequestedVersion ) {
	// DO NOT MODIFY THE FOLLOWING FOUR LINES
	// Location visited after installation is complete if installation is required
	var MMPlayerType = (isIE == true) ? "ActiveX" : "PlugIn";
	var MMredirectURL = window.location;
	document.title = document.title.slice(0, 47) + " - Flash Player Installation";
	var MMdoctitle = document.title;
	
	AC_FL_RunContent(
		"src", "playerProductInstall",
		"FlashVars", "MMredirectURL="+MMredirectURL+'&MMplayerType='+MMPlayerType+'&MMdoctitle='+MMdoctitle+"",
		"width", "600",
		"height", "600",
		"align", "middle",
		"id", "drawapp",
		"quality", "high",
		"bgcolor", "#ffffff",
		"name", "drawapp",
		"allowScriptAccess","sameDomain",
		"type", "application/x-shockwave-flash",
		"pluginspage", "http://www.adobe.com/go/getflashplayer"
		);
    } else if (hasRequestedVersion) {
	// if we've detected an acceptable version
	// embed the Flash Content SWF when all tests are passed
	if (readonly) {
	    AC_FL_RunContent(
		"FlashVars", "baseUrl=/medimg/&readonly=1&data="+data,
		"src", "/DrawApp",
		"width", "600",
		"height", "600",
		"align", "middle",
		"id", appid,
		"quality", "high",
		"bgcolor", "#ffffff",
		"name", appid,
		"allowScriptAccess","sameDomain",
		"type", "application/x-shockwave-flash",
		"pluginspage", "http://www.adobe.com/go/getflashplayer"
		);
	} else {
	    AC_FL_RunContent(
		"FlashVars",
		"baseUrl=/medimg/&data="+data+imageList,
		"src", "/DrawApp",
		"width", "600",
		"height", "600",
		"align", "middle",
		"id", appid,
		"quality", "high",
		"bgcolor", "#ffffff",
		"name", appid,
		"allowScriptAccess","sameDomain",
		"type", "application/x-shockwave-flash",
		"pluginspage", "http://www.adobe.com/go/getflashplayer",
		"onblur", "return copy_val('" + appid + "', '" + textbox_id + "');"
		);
	}

    } else {  // flash is too old or we can't detect the plugin
	var alternateContent = 'Alternate HTML content should be placed here. '
	    + 'This content requires the Adobe Flash Player. '
	    + '<a href=http://www.adobe.com/go/getflash/>Get Flash</a>';
	document.write(alternateContent);  // insert non-flash content
    }
}

function draw_drawapp(appid, textbox_id, data, readonly) {
    document.write('<div class="drawapp_div">');
    _draw_drawapp(appid, textbox_id, data, readonly);
    document.write('</div>');
}
