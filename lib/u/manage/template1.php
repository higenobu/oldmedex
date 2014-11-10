<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/template-compile.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

$_lib_u_manage_template_cfg = array
(
	'TABLE' => 'mx_template',
	'COLS' => array('application',
			'category',
			'name',
			'template_src',
			'template_obj',
			'disabled'),

	'ALLOW_SORT' => 1,
	'DEFAULT_SORT' => 'category',

	'LCOLS' => array(array('Column' => 'category',
			       'Label' => '分類'),
			 array('Column' => 'name',
			       'Label' => '名前'),
			 array('Column' => 'disabled',
			       'Label' => '使用',
			       'Draw' => 'enum',
			       'Enum' => array('' => 'する',
					       'Y' => 'しない',
					       'N' => 'する')),
			 ),

	'DCOLS' => array(array('Column' => 'category',
			       'Label' => '分類'),
			 array('Column' => 'name',
			       'Label' => '名前'),
			 array('Column' => 'disabled',
			       'Label' => '使用',
			       'Draw' => 'enum',
			       'Enum' => array('' => 'する',
					       'Y' => 'しない',
					       'N' => 'する')),
			 array('Column' => NULL,
			       'Label' => 'プレビュー',
			       'Draw' => 'group_head'),
			 array('Column' => 'template_obj',
			       'Label' => NULL,
			       'Draw' => 'template'),
			 ),

	'ECOLS' => array(array('Column' => 'category',
			       'Label' => '分類',
			       'Option' => array('size' => 30)),
			 array('Column' => 'name',
			       'Label' => '名前',
			       'Option' => array('size' => 30)),
			 array('Column' => 'disabled',
			       'Label' => '使用',
			       'Draw' => 'enum',
			       'Enum' => array('' => 'する',
					       'Y' => 'しない',
					       'N' => 'する')),
			 ),
);

if ($_mx_karte_layout == 'soap001') {
    $_lib_u_manage_template_appl = array(
	    'u/doctor/karteview.php' =>
	    array('SPEC' => array('S0' => '主訴',
				  'S1' => '現症病歴',
				  'S2' => '既往病歴',
				  'S3' => '家族病歴',
				  'S4' => 'その他病歴',
				  'O0' => '所見',
				  'A' => '診断',
				  'P' => '方針',
				  'T' => '自由形式',
				  ),
		  ),
    );
} else {
//1115-2013 for LCM   
 $_lib_u_manage_template_appl = array(
	    'u/doctor/karteview.php' =>
	    array('SPEC' => array('S0' => 'S0',
				  'S1' => 'S1',
				  'S2' => 'S2',
				  'S3' => 'S3',
				  'S4' => 'S4',
				  'O0' => 'o0',
				  'O1' => 'o1',
				  'O2' => 'o2',
				  'O3' => 'o3',
				  'O4' => 'o4',
				  'O5' => '05',
				  'O6' => 'o6',
				  'A' => 'A',
				  'P' => 'P',
				  'T' => 'T',
				  ),
		  ),
    );
}

function _lib_u_manage_template_cfg($application)
{
	global $_lib_u_manage_template_cfg;
	global $_lib_u_manage_template_appl;

	$cfg = $_lib_u_manage_template_cfg;
	$cfg['APPLICATION'] = mx_get_application_id($application);
	$cfg['APPLICATION_PATH'] = $application;
	$ad = $_lib_u_manage_template_appl[$application];
	$cfg['APPLICATION_DATA'] = $ad;

	$ecols = $cfg['ECOLS'];
	$ecols[] = array('Column' => NULL,
			 'Label' => '入力欄',
			 'Draw' => 'group_head');
	foreach ($ad['SPEC'] as $colname => $label) {
		$cn = "TTXT_$colname";
		$ecols[] = array('Column' => $cn,
				 'Label' => "$label ($colname)",
				 'Draw' => 'textarea',
				 'Option' => array('AbbrevField' => -1,
						   'rows' => 12),
				 );
	}
	$ecols[] = array('Column' => NULL,
			 'Label' => 'プレビュー',
			 'Draw' => 'group_head');
	$ecols[] = array('Column' => NULL,
			 'Label' => NULL,
			 'Draw' => 'preview_template');
	$ecols[] = array('Column' => 'update_preview',
			 'Label' => NULL,
			 'Draw' => 'update_preview');
	$cfg['ECOLS'] = $ecols;

	return $cfg;
}

class list_of_templates extends list_of_simple_objects {
	function list_of_templates($prefix, $application) {
		$cfg = _lib_u_manage_template_cfg($application);
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
	}
	function base_fetch_stmt_0() {
		$a = $this->so_config['APPLICATION'];
		return $this->so_config['STMT'] . " AND application = $a";
	}
}

class template_display extends simple_object_display {
	function template_display($prefix, $application) {
		$cfg = _lib_u_manage_template_cfg($application);
		simple_object_display::simple_object_display($prefix, $cfg);
	}

	function dx_template($desc, $value, $changed) {
		$up = $this->containing_application;
		if ($up && $up->soe && $up->soe->chosen())
			return;
 
var_dump($value);


// $ss_obj="aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";
// $s_obj=serialize($ss_obj);
 
 		 
// var_dump($template_obj);
 $template_obj = mx_form_decode_name($value);
print "\n";
 var_dump($template_obj); 
//$seria = stripslashes($template_obj);
//print "DDD:".$seria."\n";
		$template_obj = unserialize($template_obj);
print "\n";
 var_dump($template_obj);
//print_r($template_obj);
//print "ZZZ"."\n";encode

		$template_obj = $template_obj[0];

var_dump($template_obj);


		print '<table class="tabular-data" width="100%">';
		print '<tr><th width="15%">欄</th>';
		print '<th width="85%">テンプレート</th></tr>';
		print $template_obj;
		print '</table>';
	}
}

class template_edit extends simple_object_edit {
	function template_edit($prefix, $application) {
		$cfg = _lib_u_manage_template_cfg($application);
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}
	function annotate_row_data(&$row) {
		$template_src = mx_form_decode_name($row['template_src']);
print "HHH:".$template_src."\n";
		$template_src = unserialize($template_src);
var_dump($template_src);
		$ad = $this->so_config['APPLICATION_DATA'];
		foreach ($ad['SPEC'] as $colname => $label) {
			$cn = "TTXT_$colname";
			$row[$cn] = '';
		}
		foreach ($template_src as $colname => $text) {
			$cn = "TTXT_$colname";preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $row["test"] );
			$row[$cn] = $text;
		}
		$ad = $this->so_config['APPLICATION_DATA'];
		$spec = $ad['SPEC'];
//print_r($spec);
		foreach ($spec as $colname => $label) {
			$cn = "TTXT_$colname";
			$wn = $this->en("TTXT_$colname");
			$_REQUEST[$wn] = $this->data[$cn];
		}
	}
	function anew_tweak($orig_id) {
		$name = $this->data['name'];
		if (trim($name) != '') {
			$match = array();
			if (preg_match('/(.*) #(\d+)$/', $name,
				       &$match)) {
				$num = $match[2] + 1;
				$name = $match[1];
				$name .= " #$num";
			} else
				$name .= ' #2';
			$this->data['name'] = $name;
		}
	}
	function dx_update_preview($desc, $name, $value) {
		mx_formi_submit('update', 'update',
				'プレビュー更新',
				'プレビュー更新');
	}
	function template_compile() {
		$ad = $this->so_config['APPLICATION_DATA'];
		$spec = $ad['SPEC'];
		$src = array();
		foreach ($spec as $colname => $label) {
			$cn = $this->en("TTXT_$colname");
			$txt = $_REQUEST[$cn];
			$src[$colname] = $txt;
		}
		$error = '';
		list($form, $desc) = template_compile($spec, $src, &$error);
		return array($form, $desc, $spec, $src, $error);
	}
	function dx_preview_template($desc, $name, $value) {
		list($form, $desc, $spec, $src, $error) =
			$this->template_compile();
		if ($error == '') {
			print '<table class="tabular-data" width="100%">';
			print '<tr><th width="15%">欄</th>';
			print '<th width="85%">テンプレート</th></tr>';
			print $form;
			print '</table>';
		} else {
			print "Error: ";
			print htmlspecialchars($error);
		}
	}
	function _validate($force=NULL) {
		$d = &$this->data;
		$d['application'] = $this->so_config['APPLICATION'];
		list($form, $desc, $spec, $src, $error) =
			$this->template_compile();
		$bad = 0;
		if ($error != '') {
			$this->err($error);
			$bad = 1;
		} else {
			$src = serialize($src);
			$obj = serialize(array($form, $desc));
			$d['template_src'] = mx_form_encode_name($src);
			$d['template_obj'] = mx_form_encode_name($obj);
		}
		$ok = simple_object_edit::_validate($force);
		if ($ok != 'ok' || $bad)
			return '';
		return $ok;
	}

}
?>
