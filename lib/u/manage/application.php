<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/app-auth.php';

function __lib_u_manage_application_init()
{
	global $_lib_u_manage_application_cfg;
	global $__lib_u_manage_app_auth__applink_names;
	$catnames = array();
	foreach ($__lib_u_manage_app_auth__applink_names as $k => $v) {
		$catnames[$k] = $k;
	}
	// ones that should not be shown as directly accessible
	$catnames['X'] = 'X';
	$ppa = array('Column' => "ppa",
		     'Draw' => 'enum',
		     'Enum' => array('Y' => 'Framework',
				     'F' => 'non Framework',
				     'N' => 'ÉÑÍÑÈó´µ¼ÔËè',
				     'O' => 'Framework (optional)',
				     'X' => 'Dynamic-External',
				     NULL => '´µ¼ÔËè¤Ç¤Ê¤¤'));
	$cat = array('Column' => "category",
		     'Draw' => 'enum',
		     'Enum' => $catnames);
	$limited = array('Column' => "pt_limited",
			 'Draw' => 'enum',
			 'Enum' => array('Y' => 'Îã³°¤Ê¤¯Ã´Åö¿¦°÷¤Î¤ß',
					 'X' => 'Ã´Åö¿¦°÷¤Î¤ß¡£Ã¢¤·Ã´ÅöÌµ¤·¤ÏÁ´¿¦°÷',
					 NULL => 'Á´¿¦°÷'));
	$_lib_u_manage_application_cfg = array
		(
			'LCOLS' => array("name", $cat, "sortorder",
					 "path"),
			'DCOLS' => array("name", $cat, "sortorder",
					 "path", "abbrev", "disamb",
					 $ppa, $limited),
			'ECOLS' => array("name", $cat, "sortorder",
					 "path", "abbrev", "disamb",
					 $ppa, $limited),
			'TABLE' => 'mx_application',
			'ALLOW_SORT' => 1,
			'ENABLE_QBE' => 1,
			);
	$a = array();
	foreach ($_lib_u_manage_application_cfg['DCOLS'] as $v) {
		if (!is_array($v)) {
			$a[] = $v;
		}
		else {
			$a[] = $v['Column'];
		}
	}
	$_lib_u_manage_application_cfg['COLS'] = $a;
}
__lib_u_manage_application_init();

class list_of_applications extends list_of_simple_objects {

	function list_of_applications($prefix, $cfg=NULL) {
		global $_lib_u_manage_application_cfg;
		if (is_null($cfg))
			$cfg = $_lib_u_manage_application_cfg;
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
	}
}

class application_display extends simple_object_display {
	function application_display($prefix, $cfg=NULL) {
		global $_lib_u_manage_application_cfg;
		if (is_null($cfg))
			$cfg = $_lib_u_manage_application_cfg;
		simple_object_display::simple_object_display($prefix, $cfg);
	}
}

class application_edit extends simple_object_edit {
	function application_edit($prefix, $cfg=NULL) {
		global $_lib_u_manage_application_cfg;
		if (is_null($cfg))
			$cfg = $_lib_u_manage_application_cfg;
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}

	function anew_tweak($orig_id) {
		$this->data["path"] = NULL;
	}

	function _validate() {
		if ($this->data['sortorder'] == '')
			$this->data['sortorder'] = NULL;
		return 'ok';
	}
}
?>
