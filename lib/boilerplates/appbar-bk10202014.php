<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/app-auth.php';
class JSArray
{
  function JSArray($a=array()) {
    $this->a = $a;
  }
  function add($v) {
    $this->a[] = $v;
  }
  function js() {
    $ret = NULL;
    foreach($this->a as $v)
      if (is_object($v))
	$ret[] =  $v->js();
      else
	$ret[] =  $v;
    return "[" . implode(",\n", $ret) . "]";
  }
}
class JSDict
{
  function JSDict($d=array()) {
    $this->d = $d;
  }
  function add($k, $v) {
    $this->d[$k] = $v;
  }
  function js() {
    $ret = NULL;
    foreach($this->d as $k => $v) {
      if (is_object($v))
	$ret[] =  "$k : " . $v->js();
      else
	$ret[] =  "$k : $v";
    }
    return "{" . implode(",\n", $ret) . "}";
  }
}
function mx_yui_appbar($all_apps, $top_apps)
{
  global $__uiconfig_appbar_app_classes;
  global $__lib_u_manage_app_auth__applink_names;
  $menu_array = new JSArray();
  $ix = 0;
  foreach ($__uiconfig_appbar_app_classes as $k) {
    $v = $__lib_u_manage_app_auth__applink_names[$k];
    if (count($all_apps[$k])) {
      $app = $all_apps[$k][0];
      $path = $app[0];
      $name = $app[2];
      $target = ($app[3] || $k == 'E') ? ' target="_blank"': '';
      // create a parent menu item
      $dict = new JSDict(array('text' => '"' . htmlspecialchars($name) . '"'));
      if (count($all_apps[$k]) > 0) {
	// has children
	$itemdata = NULL;
	foreach ($all_apps[$k] as $app) {
	  $path = $app[0];
	  $name = $app[1];
	  $target = $app[3] ? '_blank': '';
	  $itemdata[] = new JSDict(array('id' => "\"id$ix\"",
					 'text' => '"' . htmlspecialchars($name) . '"',
					 'url' => '"' . htmlspecialchars($path) . '"',
					 'target' => '"' . htmlspecialchars($target) . '"'));
	  $ix += 1;
	}
	$ix += 1;
	$dict->add('submenu', new JSDict(array('id' => "\"id$ix\"",'itemdata' => new JSArray($itemdata))));
      }
      $menu_array->add($dict);
    }

  }

  // top level app buttons
  foreach ($top_apps as $app) {
    $path = $app[0];
    $abbrev = $app[2];
    $target = $app[3] ? '_blank': '';
    $dict = new JSDict(array('id' => "\"id$ix\"",
			     'text' => '"' . htmlspecialchars($abbrev) . '"',
			     'url' => '"' . htmlspecialchars($path) . '"',
			     'target' => '"' . htmlspecialchars($target) . '"',
			     ));
    $ix += 1;
    $menu_array->add($dict);
  }
  $menu_array_js = $menu_array->js();
  print <<<HTML
<style type="text/css">
em#productlogo {
      text-indent: -6em;
      display: block;
      background: url(/favicon.ico) center center no-repeat;
      width: 2em;
      overflow: hidden;
}
/*
  Setting the "zoom" property to "1" triggers the "hasLayout"
  property in IE.  This is necessary to fix a bug IE where
  mousing mousing off a the text node of MenuItem instance's
  text label, or help text without the mouse actually exiting the
  boundaries of the MenuItem instance will result in the losing
  the background color applied when it is selected.
*/
#filemenu.visible .yuimenuitemlabel, #editmenu.visible .yuimenuitemlabel {
    *zoom: 1;
}
/*
  Remove "hasLayout" from the submenu of the file menu.
*/

#filemenu.visible .yuimenu .yuimenuitemlabel {
    *zoom: normal;
}
</style>
<!-- Page-specific script -->
<script type="text/javascript">
/*
  Initialize and render the MenuBar when the page's DOM is ready
  to be scripted.
*/
YAHOO.util.Event.onDOMReady(function () {
	var onMenuItemClick = function () {
	    alert("Callback for MenuItem: " + this.cfg.getProperty("text"));
	};
	/*
	  Define an array of object literals, each containing
	  the data necessary to create the items for a MenuBar.
	*/
	var aItemData = ${menu_array_js};
	/*
	  Instantiate a Menu:  The first argument passed to the constructor
	  is the id for the Menu element to be created, the second is an
	  object literal of configuration properties.
	*/
	var oMenuBar = new YAHOO.widget.MenuBar("mymenubar", {
		lazyload: true,
		itemdata: aItemData
	    });
	/*
	  Since this MenuBar instance is built completely from
	  script, call the "render" method passing in a node
	  reference for the DOM element that its should be
	  appended to.
	*/
	oMenuBar.render(document.body);
	// Add a "show" event listener for each submenu.
	function onSubmenuShow() {
	    var oIFrame,
		oElement,
		nOffsetWidth;
	    // Keep the left-most submenu against the left edge of the browser viewport
	    if (this.id == "yahoo") {
		YAHOO.util.Dom.setX(this.element, 0);
		oIFrame = this.iframe;
		if (oIFrame) {
		    YAHOO.util.Dom.setX(oIFrame, 0);
		}
		this.cfg.setProperty("x", 0, true);
	    }
	    /*
	      Need to set the width for submenus of submenus in IE to prevent the mouseout
	      event from firing prematurely when the user mouses off of a MenuItem's
	      text node.

	      Measuring the difference of the offsetWidth before and after
	      setting the "width" style attribute allows us to compute the
	      about of padding and borders applied to the element, which in
	      turn allows us to set the "width" property correctly.
	    */
	    /*
	    if ((this.id == "filemenu" || this.id == "editmenu") && YAHOO.env.ua.ie) {
		oElement = this.element;
		nOffsetWidth = oElement.offsetWidth;
		oElement.style.width = nOffsetWidth + "px";
		oElement.style.width = (nOffsetWidth - (oElement.offsetWidth - nOffsetWidth)) + "px";
		}
	    */
	}
	// Subscribe to the "show" event for each submenu
	oMenuBar.subscribe("show", onSubmenuShow);
    });
</script>
HTML;
}

function mx_appbar($it)
{
	global $__lib_u_manage_app_auth__applink_names;
	global $__uiconfig_appbar_app_classes;
	global $_mx_flat_management_applist;
	global $_mx_disable_appbar_during_edit;
	global $_mx_yui;

	$disabled = $_mx_disable_appbar_during_edit && $it->edit_in_progress();

	if ($disabled) {
		print "<div>";
		print "<h1>edit process</h1>";
		print "</div>";
		return;
	}

	$pt = $it->patient_ID;
	$poid = $it->patient_ObjectID;
	$u = $it->u;
	$apps = mx_find_application($it->u, 'A');
	$a = array();
	$me = $_SERVER['PHP_SELF'];
	$match = array();
	if (! preg_match('/^(\/au\/[^\/]+\/)(.*)$/', $me, &$match)) {
		return;
	}
	$cookie = $match[1];
	$me = $match[2];

	$all_apps = array();
	$top_apps = array();
	foreach ($apps as $cat => $ac) {
		if (!is_array($ac))
			continue;
		$apps = array();
		foreach ($ac as $d) {
			if ($_mx_flat_management_applist &&
			    $cat == 'M' && $d['sortorder'] < 0)
				continue;
			$path = $d['path'];
			$name = $d['name'];
			$abbrev = $d['abbrev'];
			$target = NULL;
			if (!$abbrev)
				$abbrev = $name;

			if (!$poid &&
			    ($d['ppa'] == 'F' || $d['ppa'] == 'Y'))
				;
			else if ($d['ppa'] == 'F')
				$pid = '?PID=' .
					htmlspecialchars($pt);
			else if ($d['ppa'] == 'Y')
				$pid = '?SetPatient=1&amp;PatientID=' .
					htmlspecialchars($pt);
			else if ($d['ppa'] == 'O' && $poid)
				$pid = '?SetPatient=1&amp;PatientID=' .
					htmlspecialchars($pt);
			else if ($d['ppa'] == 'X') {
				$eval = eval($path);
				$path = $eval['path'];
				$target = $eval['target'];
				if ($path == '')
					continue;
				$ap = htmlspecialchars($path);
			}
			else
				$pid = '';
			if ($d['ppa'] != 'X')
				$ap = htmlspecialchars($cookie.$path).$pid;
			$ap = array($ap, $name, $abbrev, $target);

			if (!$it->appbar_filter($path, $name, $pt))
				continue;

			if ($cat != 'T')
				$all_apps[$cat][] = $ap;
			else
				$top_apps[] = $ap;
		}
	}
	$all_apps['S'][] = array(htmlspecialchars($cookie.'logout.php'),
				 'logout');
	$e = 0;
	foreach ($__lib_u_manage_app_auth__applink_names as $k => $v) {
		$e++;
	}
	if($_mx_yui) {
	  mx_yui_appbar($all_apps, $top_apps);
	  return;
	}
	print "<div class=\"appbar-all\">";
	$ix = 0;
	foreach ($__uiconfig_appbar_app_classes as $k) {
		$v = $__lib_u_manage_app_auth__applink_names[$k];
		if (count($all_apps[$k])) {
		  if (count($all_apps[$k]) == 1) {
		    $app = $all_apps[$k][0];
		    $path = $app[0];
		    $name = $app[2];
		    $target = ($app[3] || $k == 'E') ? ' target="_blank"': '';
		    print "<div class=\"appbar-one\">";
		    print "<a href=\"$path\" class=\"appbar-anc\"${target}>";
		    print htmlspecialchars($name);
		    print "</a></div>";
		  } else {
		   print "<div class=\"appbar-one\">";
		   print "<a href=\"#\" onclick=\"appbar_pop($ix,$e);\"";
		   print " id=\"appbar-anc-$ix\" class=\"appbar-anc\">";
		   print htmlspecialchars($v);
		   print "</a></div>";

		   print "<div id=\"appbar-list-$ix\" class=\"appbar-elem\"";
		   print " onclick=\"appbar_pop('$ix');\"";
		   print " style=\"position: absolute; visibility: hidden;\">";
		   foreach ($all_apps[$k] as $app) {
			   $path = $app[0];
			   $name = $app[1];
			   $target = $app[3] ? ' target="_blank"': '';
			   print "<div class=\"appbar-item\"${target} ";
			   print "onclick=\"activateInnerAnchor(this);\">";
			   print "<a href=\"$path\">";
			   print htmlspecialchars($name);
			   print "</a></div>\n";
		   }
		   print "</div>";
		  }
		}
		$ix++;
	}
	print "</div>";
	print "<div class=\"appbar-all\">";
	foreach ($top_apps as $app) {
		$path = $app[0];
		$abbrev = $app[2];
		$target = $app[3] ? ' target="_blank"': '';
		print "<div class=\"appbar-top\">";
		print "<a href=\"$path\"${target} class=\"appbar-anc\">";
		print htmlspecialchars($abbrev);
		print "</a\n></div>";
	}
	print "</div>";
}
?>
