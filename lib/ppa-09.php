<?php // -*- mode: php; coding: euc-japan -*-
// Per-patient application base class
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/so.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/index-pt.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/app-auth.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/patient-basic.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/appbar.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ui_config.php';
//current 0523-2013
function mx_draw_ppa_applist($patient_id) {
	global $mx_authenticate_current_user;
	global $__lib_u_manage_app_auth__applink_names;
	global $__lib_u_manage_app_auth__app_related;

	$me = $_SERVER['PHP_SELF'];
	$match = array();
	if (! preg_match('/^(\/au\/[^\/]+\/)(.*)$/', $me, &$match)) {
		print "UNM $me";
		return;
	}
	$cookie = $match[1];
	$me = $match[2];
	$apps = mx_find_application($mx_authenticate_current_user, 'A');
	$applist = array();
	$name_count = array();
	foreach ($apps as $category => $cls) {
		foreach ($cls as $d) {
			if ($d['ppa'] == ' ' || is_null($d['ppa']))
				continue;
			if (!array_key_exists($category, $applist)) {
				$applist[$category] = array();
			}
			$applist[$category][] = $d;
			$n = $d['name'];
			if (!array_key_exists($n, $name_count))
				$name_count[$n] =0;
			$name_count[$n]++;
		}
	}
	$hidden=(' style="position: absolute; visibility:hidden;"');
	$triggers = " onclick='toggle_ppa_list()'";
	print "<div id=\"ppa-applink\"$hidden$triggers>\n";
	$related = array();
	foreach ($__lib_u_manage_app_auth__applink_names as $category => $label) {
		if (!array_key_exists($category, $applist))
			continue;
		$apps = $applist[$category];
		if (!count($apps))
			continue;
		print "<p>��" . htmlspecialchars($label) . "<br />\n";
		foreach ($apps as $d) {
			$name = $d['name'];
			if (1 < $name_count[$name] && $d['disamb'])
				$name = ($d['abbrev'] . "(" .
					 $d['disamb'] . ")");
			$path = $d['path'];
			if (!$patient_id && $d['ppa'] != 'O') {
				$pid = '';
			}
			else if ($d['ppa'] == 'F') {
				$pid = '?PID=' . htmlspecialchars($patient_id);
			}
			else if ($d['ppa'] == 'O') {
				$pid = '?SetPatient=1&amp;PatientID=' .
					htmlspecialchars($patient_id);
			}
			else if ($d['ppa'] == 'Y') {
				$pid = '?SetPatient=1&amp;PatientID=' .
					htmlspecialchars($patient_id);
			}

			if ($d['path'] == $me)
				$it = ("&nbsp;" . htmlspecialchars($name) .
				       "&nbsp;");
			else {
				$it = ('&nbsp;<span class="link"><a href="' .
				       htmlspecialchars($cookie . $path) .
				       $pid .
				       '">' . htmlspecialchars($name) .
				       '</a></span>');
				if (array_key_exists
				    ($me, $__lib_u_manage_app_auth__app_related) &&
				    array_key_exists($d['path'], $__lib_u_manage_app_auth__app_related[$me])) {
					$related[] = $it;
				}
			}
			print $it;
			print "\n";

		}
		print "</p>\n";
	}
	print "</div>\n";
	$triggers = " onclick='toggle_ppa_list()'";
	$ul = ' style="text-decoration: underline;"';
//0722-2012
	print "<span id='ppa-list-here'$triggers$ul>APP</span>";
//	foreach ($related as $it) {
//		print $it;
//	}
//0722-2012
}

$__ppa_patient_list_hstmt_base = <<<SQL
SELECT P."ObjectID", P."CreatedBy",
	P."����ID", P."�եꥬ��", P."����",
	P."��ǯ����", P."������ʬ",
	P."��", P."̾",
	(P."��" || P."̾") AS "��̾",
	L."�����象����" AS "�����象����",
	P."��˾����" AS "���ԥ��롼��",
	P."���ԥޡ���",
P."��̳��̾",
	EXTRACT(YEAR FROM AGE(P."��ǯ����")) AS "ǯ��",
	E.employee_id AS "�缣��",
	E.employee_lastname AS "�缣����",
	E.employee_firstname AS "�缣��̾",
	(E.employee_lastname || E.employee_firstname) AS "�缣����̾"
FROM "������Ģ" AS P
JOIN patient_employee AS E
ON E.patient_id = P."ObjectID" AND
   (E.role_id IS NULL OR E.role_name = '�缣��')
LEFT JOIN LAST_VISIT L
ON P."ObjectID" = L.patient_id
WHERE P."Superseded" IS NULL
SQL;

function ppa_patient_list_hstmt() {
	global $__ppa_patient_list_hstmt_base;
	return $__ppa_patient_list_hstmt_base .
		mx_limit_patient_with_authorization('P."ObjectID"');
}

$__ppa_list_cfg = array
(
	'TABLE' => '������Ģ',
	'HSTMT' => $__ppa_patient_list_hstmt_base,
	'ENABLE_QBE' => array(array('Column' => '����ID',
'Label' => 'PID',
				    'Singleton' => 1,
				    'CompareMethod' => 'zeropad_exact',
				    'ZeroPad' => $_mx_patient_id_zeropad,
				    'Option' => array('size' => 6),
				    ),
//0523-2013 add patient selection qbe
array('Column' => '�եꥬ��','Label' => 'fullname',
			    'Compare' => 'P."�եꥬ��"',
//0522-2013 ??			    'NormalizeCompareKey' => 'AC',
				    'Draw' => 'text',
//				    'IncSearch' => 'patient',
				    'Option' => array('size' => 10),
				    'Singleton' => 1),

			      array('Column' => "����",
'Label' => 'SEX',
				    'Compare' => 'P."����"',
				    'Draw' => 'enum',
				    'Singleton' => 1,
				    'Enum' => array('M' => 'M',
						    'F' => 'F',
						    '' => '(both)')),
			       
 
			      array('Column' => "ǯ��",
'Label' => 'AGE',
				    'Compare' => 'EXTRACT(YEAR FROM AGE(P."��ǯ����"))',
				    'Draw' => 'text',
				    'Option' => array('size' => 3),
				    ),
			       
			      ),
//0325-2013
	'LCOLS' => array(array('Column' => '����ID','Label' => 'PID',
			       'Option' => array('Class' => 'nowrap')),
			 array('Column' => '��̾', 'Label' => "name",
			       'Option' => array('Class' => 'nowrap')),
			 array('Column' => '�եꥬ��', 'Label' => "call name",
			       'Option' => array('Class' => 'nowrap')),
array('Column' => '��̳��̾', 'Label' => "ocupation",
			       'Option' => array('Class' => 'nowrap')),
			 array('Column' => "����", 'Label' => "SEX",
			       'Draw' => 'enum',
			       'Option' => array('Class' => 'nowrap'),
//0328-2013 English
			       'Enum' => array('M' => 'M',
					       'F' => 'F',
					       '' => '')),
array('Column' => '��ǯ����', 'Label' => "DOB",
			       'Option' => array('Class' => 'nowrap')),
			 array('Column' => 'ǯ��', 'Label' => "age",
			       'Option' => array('Class' => 'nowrap')),
			 array('Column' => '���ԥ��롼��', 'Label' => "Group",
			       'Option' => array('Class' => 'nowrap')),
			 array('Column' => '���ԥޡ���', 'Label' => "Pmark",
			       'Option' => array('Class' => 'nowrap')),
			 array('Column' => "������ʬ", 'Label' => "IN/OUT",
			       'Draw' => 'enum',
			       'Option' => array('Class' => 'nowrap'),
//0328-2013 English
			       'Enum' => array('I' => 'IN',
					       'O' => 'OUT',
					       '' => '')),
			 array('Column' => "�缣����̾", 'Label' => "Main DR.",
			       'Option' => array('Class' => 'nowrap'),
			       'Label' => "�缣��"),
			 array('Column' => '�����象����', 'Label' => "vsited",
			       'Option' => array('Class' => 'nowrap')),
			 ),
	/*
	 * depending on hospitals, it might want to narrow to only
	 * in-patients.
	 */
	'DEFAULT_QBE' => array(),

	'ALLOW_SORT' => array('����ID' =>
			      array('����ID' => 'P."����ID"'),
			      '��̾' =>
			      array('��̾' => '(P."��"||P."̾")'),
			      '����' =>
			      array('����' => 'P."����"'),
			      '�եꥬ��' =>
			      array('�եꥬ��' => 'P."�եꥬ��"'),
'��̳��̾' =>
			      array('��̳��̾' => 'P."��̳��̾"'),
			      'ǯ��' =>
			      array("ǯ��" => '"AGE"'),
			      "���ԥ��롼��" =>
			      array("���ԥ��롼��" => 'P."��˾����"'),
			     // '�缣����̾' =>
			      //array("�缣����̾" => '"�缣����̾"'),
			      "�����象����" =>
			      array("�����象����" => 'L."�����象����"')),

	'UNIQ_KEY' => 'P."ObjectID"',
//0325-2013
	'LLAYO' => array(array('Column' => '����ID','Label'=>'PID',
			       'Option' => array('Class' => 'nowrap')),
//
array('Column' => '��̾','Label'=>'name',
			       'Option' => array('Class' => 'nowrap')),
array('Column' => '��ǯ����','Label'=>'DOB',
			       'Option' => array('Class' => 'nowrap')),
 
 
			 array('Column' => "����",
			'Label'=> 'SEX',
			       'Draw' => 'enum',
			       'Enum' => array('M' => 'M', 'F' => 'F',
					       NULL => 'n/a') ),
			 
 
			   ),
//0325-2013

	'LIST_IDS' => array('����ID', 'ObjectID', '��', '̾', '�եꥬ��'),
);

class ppa_patient_list_base extends list_of_simple_objects {
	var $default_row_per_page = 40;
	var $list_name = '������Ģ';

	function ppa_patient_list_base($prefix, $encounter_mode,
				       $lop_in_main=NULL, $cfg=NULL) {
		global $__ppa_list_cfg;
		global $_mx_max_patient_row_per_page;

		if (!is_null($_mx_max_patient_row_per_page))
		  $this->default_row_per_page = $_mx_max_patient_row_per_page;
		if (!$lop_in_main)
			$this->default_row_per_page = 4;
		if (is_null($cfg))
			$cfg = $__ppa_list_cfg;
		if ($encounter_mode == 'I')
			$cfg['DEFAULT_QBE'] = array(array('������ʬ', 'I'));
		$cfg['HSTMT'] = ppa_patient_list_hstmt();
		$cfg['STMT'] = $cfg['HSTMT'];
		$this->tweak_config($cfg);
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
	}

	function tweak_config(&$cfg) {
	}

	function qbe_limit_too_vague() {
		return (count($this->qbe_current) == 0);
	}

	function enum_list($desc) {
		if ($desc['Enum'] == 'lazy') {
			switch ($desc['Column']) {
			case '���ԥޡ���':
				return mx_dbenum_patientmark('=');
			case '���ԥ��롼��':
				return mx_dbenum_patientgroup();
			case '�缣��':
				return mx_dbenum_primarydoctor();
			case '����':
				return mx_dbenum_doctordepartment();
			}
		}
		return $desc['Enum'];
	}

	function set_patient($patient_id) {
		global $__ppa_patient_list_hstmt_base;
		$stmt = $__ppa_patient_list_hstmt_base . ' AND
P."����ID" = ' . mx_db_sql_quote($patient_id);
		$db = mx_db_connect();
		$row = mx_db_fetch_single($db, $stmt);
		$this->set_selection($row);
	}

}

class ppa_patient_list extends ppa_patient_list_base {
	var $use_token = 'UsingListOfPatients';

	function ppa_patient_list($prefix, $lop_in_main=NULL, $cfg=NULL) {
		ppa_patient_list_base::ppa_patient_list_base
			($prefix, NULL, $lop_in_main, $cfg);
	}
}

class ppa_inpatient_list extends ppa_patient_list_base {
	var $use_token = 'UsingListOfInPatients';

	function ppa_inpatient_list($prefix, $lop_in_main=NULL, $cfg=NULL) {
		ppa_patient_list_base::ppa_patient_list_base
			($prefix, 'I', $lop_in_main, $cfg);
	}
}

if ($_mx_keep_done_patients_on_reception_list) {
  	# �ޤ���Ƥʤ���������������ʹߤ��褿�ҤȤ���
	$exclude_done = 'AND (C."���ջ���" IS NULL OR CURRENT_DATE <= C."���ջ���")';
} else {
	$exclude_done = 'AND C."���ơ�����" IS NULL';
}
//0813-2012
 
if ($_mx_limit_reception_list_to_day) {
	$limit_today = "AND  C.\"ͽ�����\" < CURRENT_DATE + INTERVAL '1 DAY'" ;
} else {
	$limit_today = "";
}
 
 


function __lib_ppa_status_dbenum_order() {
	global $__lib_ppa_status_dbenum_order;
	global $__lib_ppa_status_dbenum_enum;
	if (!$__lib_ppa_status_dbenum_order) {
		$ix = 0;
		$enum = mx_dbenum('���մ��Ծ���','���մ��Ծ���');
		$enum = explode("\n", $enum['�����']);
		$__lib_ppa_status_dbenum_enum = array('' => '');
		foreach ($enum as $name) {
			$name = trim($name);
			if ($name == '')
				continue;
			$__lib_ppa_status_dbenum_enum["=$name"] = $name;
			$ix++;
		}
		$stmt = 'CS."����"';
		$__lib_ppa_status_dbenum_order = $stmt;		
	}
	return $__lib_ppa_status_dbenum_order;
}

__lib_ppa_status_dbenum_order();

$__lib_ppa_dept_sql = <<<SQL
(CASE WHEN
	 ((D."��ʬ��" IS NULL) OR D."��ʬ��" = '')
	 THEN D."��ʬ��2"
	 ELSE D."��ʬ��"
	 END)
SQL;


//0523-2013 updated add furi
$__ppa_checkin_list_hstmt_base = <<<SQL
SELECT C."ObjectID", C."CreatedBy", C."����", C."����ID",
	C."���ջ���", C."ͽ�����", C."��", C."̾",
	(C."��" || ' ' || C."̾") AS "��̾", C."�եꥬ��",(C."�եꥬ��" || '') as "furi", C."��Ū",
	M.name AS "��", R.name AS "�񸻼���",
	L."�����象����" AS "�����象����",
	EXTRACT(YEAR FROM AGE(P."��ǯ����")) AS "ǯ��",
	P."��˾����" AS "���ԥ��롼��", P."���ԥޡ���",
	P."����", P."������ʬ",
	CS."����" AS "���մ��Ծ���",
	C."���ռ��ǲ�", C."�ݸ�����",
	($__lib_ppa_status_dbenum_order) AS "���մ��Ծ��ֽ�",
	E.employee_id AS "�缣��",
	E.employee_lastname AS "�缣����",
	E.employee_firstname AS "�缣��̾",
	(E.employee_lastname || E.employee_firstname) AS "�缣����̾",
	(CASE WHEN EXISTS (
		SELECT 1 FROM mx_usermode WHERE
		modeclass = '���մ���ɽID' AND
		modevalue = (C."ID" || ',' || C."����ID"))
	 THEN 'Y' ELSE (CASE WHEN C."���ơ�����" IS NULL
			THEN 'B'
			ELSE 'A'
			END) END) AS "������",
	DATE_TRUNC('second', (C."���Ž�λ����" - C."���ջ���")) AS "�߱�����",
	($__lib_ppa_dept_sql) AS "����̾",
	D."ObjectID" AS "����"
FROM "���մ���ɽ"  AS C
JOIN "������Ģ" AS P
ON P."ObjectID" = C."����"
JOIN patient_employee AS E
ON E.patient_id = P."ObjectID" AND
   (E.role_id IS NULL OR E.role_name = '�缣��')
LEFT JOIN modalities M
ON M.id = C."ͽ����"
LEFT JOIN resource_type R
ON M.rtype = R.id
LEFT JOIN modalities_to_medex_employee ME
ON ME.modality = M.id
LEFT JOIN "������Ģ" AS EE
ON EE."ObjectID" = ME.employee
LEFT JOIN "�������ɽ" AS D
ON D."ObjectID" = EE."����"
LEFT JOIN LAST_VISIT L
ON C."����" = L.patient_id
LEFT JOIN "���մ��Ծ���" AS CS
ON P."ObjectID" = CS."����" AND
   ((CURRENT_DATE <= CS."��Ͽ����") AND
    (CS."��Ͽ����" < (CURRENT_DATE + INTERVAL '1 DAY')) AND
    (CS."ObjectID" = (SELECT MAX("ObjectID") FROM
		      "���մ��Ծ���" WHERE "����" = C."����" AND
		      (CS."��Ͽ����" < (CURRENT_DATE + INTERVAL '1 DAY')))))
WHERE C."Superseded" IS NULL $exclude_done AND (C."CreatedBy" IS NULL OR (C."CreatedBy" = 1 AND C."ͽ�����" >= (CURRENT_DATE + INTERVAL '1 DAY')))
AND   (C."ͽ�����" IS NULL OR (C."ͽ�����" >= CURRENT_DATE $limit_today))
SQL;


 





function ppa_checkin_list_hstmt() {
	global $__ppa_checkin_list_hstmt_base;
	return $__ppa_checkin_list_hstmt_base .
		mx_limit_patient_with_authorization('C."����"');
}

$__status = ($_mx_use_checked_in_patient_status ? '���մ��Ծ���' : '������');

$__ppa_checkin_list_cfg = array
(
	'TABLE' => '���մ���ɽ',
	'HSTMT' => $__ppa_checkin_list_hstmt_base,
	'ENABLE_QBE' => array(array('Column' => '����ID','Label'=>'PID',
				    'Compare' => 'C."����ID"',
				    'CompareMethod' => 'zeropad_exact',
				    'ZeroPad' => $_mx_patient_id_zeropad,
				    'Singleton' => 1,
				    'Option' => array('size' => 6),
				    ),
			      array('Column' => '�եꥬ��','Label' => 'lname',
 			    'Compare' => 'C."�եꥬ��"',
//0522-2013 ??			    'NormalizeCompareKey' => 'AC',
				    'Draw' => 'text',
//				    'IncSearch' => 'patient',
				    'Option' => array('size' => 10),
				    'Singleton' => 1),
//0523-2013 add furi
array('Column' => 'furi','Label' => 'fname',
 			    'Compare' => 'C."�եꥬ��"',
//0522-2013			    'NormalizeCompareKey' => 'AC',
				    'Draw' => 'text',
//				    'IncSearch' => 'patient',
				    'Option' => array('size' => 10),
				    'Singleton' => 1),
//0523-2013
			      array('Column' => "����",'Label'=>'sex',
				    'Compare' => 'P."����"',
				    'Draw' => 'enum',
				    'Singleton' => 1,
				    'Enum' => array('M' => 'M',
						    'F' => 'F',
						    '' => '(Both)')),
			      array('Column' => "ǯ��",'Label'=>'AGE',
				    'Compare' => 'EXTRACT(YEAR FROM AGE(P."��ǯ����"))',
				    'Draw' => 'text',
				    'Option' => array('size' => 3),
				    ),
			      array('Column' => "���ԥ��롼��",'Label'=>'Group',
				    'Compare' => 'P."��˾��"','Label'=>'Tower',
				    'Draw' => 'enum',
				    'Singleton' => 1,
				    'Enum' => 'lazy'),
			      array('Column' => '���ԥޡ���','Label'=>'Pmark',
				    'Compare' => 'P."���ԥޡ���"',
				    'Draw' => 'enum',
				    'Singleton' => 1,
				    'Enum' => 'lazy'),
			      array('Column' => '�缣��','Label'=>'Main Dr.',
				    'Compare' => 'E.employee_id',
				    'Draw' => 'enum',
				    'Singleton' => 1,
				    'Enum' => 'lazy'),
			      array('Column' => 'ͽ����','Label' => 'Reserv',
				    'Draw' => 'enum',
				    'Singleton' => 1,
				    'Enum' => 'lazy'),
			      array('Column' => '����','Label'=>'Dept',
				    'Compare' => 'D."ObjectID"',
				    'Draw' => 'enum',
				    'Singleton' => 1,
				    'Enum' => 'lazy'),

			      ),

	'ALLOW_SORT' => array('����ID' => array('����ID' => 'P."����ID"'),
			      '��̾' =>  array('��̾' => '(C."��"||C."̾")'),
 			      '��' =>  array('��' => '(M.name)'),
			      '���ջ���' => array('���ջ���' => 'C."���ջ���"'),
			      'ͽ�����' => array('ͽ�����' => 'C."ͽ�����"'),
			      '�եꥬ��' => array('�եꥬ��' => 'C."�եꥬ��"'),
			      "���ԥ��롼��" =>
			      array("���ԥ��롼��" => 'P."��˾����"'),
			      '�缣����̾' =>
			      array("�缣����̾" => 
			    '(E.employee_lastname || E.employee_firstname)'),
			      '�����象����' =>
			      array('�����象����' => 'L."�����象����"'),
			      ),

	'DEFAULT_SORT' => '���ջ���',
	'SORT_TIEBREAK' => array('C."ͽ�����"', 'C."���ջ���"', 'P."����ID"'),
	'UNIQ_KEY' => 'C."ObjectID"',

	'LCOLS' => array(
			 array('Column' => "������",
			       'Label' => "in process",
			       'Option' => array('Class' => 'nowrap'),
			       'Draw' => 'pt_status'),
			 array('Column' => "���մ��Ծ���", 'Label' => "Status",
			       'Draw' => 'dbenum',
			       'Option' => array('Class' => 'nowrap'),
			       'DBEnum' => array('���մ��Ծ���',
						 '���մ��Ծ���')),
			 array('Column' => '����ID', 'Label' => "PID",
			       'Option' => array('Class' => 'nowrap')),
			 array('Column' => '��̾', 'Label' => "name",
			       'Option' => array('Class' => 'nowrap')),
			 array('Column' => '�եꥬ��', 'Label' => "callname",
			       'Option' => array('Class' => 'nowrap')),
			 array('Column' => '��Ū', 'Label' => "Objective",
			       'Option' => array('Class' => 'nowrap')),
			 array('Column' => "����",
			       'Compare' => 'P."����"',
			       'Draw' => 'enum',
			       'Option' => array('Class' => 'nowrap'),
			       'Enum' => array('M' => 'M',
					       'F' => 'F',
					       '' => '(�ɤ���Ǥ�)')),
			 array('Column' => 'ǯ��','Label'=>'AGE',
			       'Option' => array('Class' => 'nowrap')),
			 array('Column' => '���ԥ��롼��',
			       'Option' => array('Class' => 'nowrap')),
			 array('Column' => '���ԥޡ���', 'Label' => "Pmark",
			       'Option' => array('Class' => 'nowrap')),
			 array('Column' => "������ʬ",
			       'Draw' => 'enum',
			       'Option' => array('Class' => 'nowrap'),
			       'Enum' => array('I' => 'IN',
					       'O' => 'OUT',
					       '' => '(Both)')),
			 //array('Column' => "�缣����̾",
			   //    'Option' => array('Class' => 'nowrap'),
			     //  'Label' => "�缣��"),
			 array('Column' => "��",
			       'Option' => array('Class' => 'nowrap'),
			       'Label' => "Reserv"),
			 array('Column' => "���ջ���", 'Label' => "checktime",
			       'Draw' => 'timestamp',
			       'Option' => array('to-seconds' => 0,
						 'Class' => 'nowrap')),
			 array('Column' => "�����象����", 'Label' => "visited",
			       'Draw' => 'timestamp',
			       'Option' => array('to-seconds' => 0,
						 'Class' => 'nowrap')),
			 array('Column' => "ͽ�����", 'Label' => "Reserved",
			       'Draw' => 'timestamp',
			       'Option' => array('to-seconds' => 0,
						 'Class' => 'nowrap')),
			 //array('Column' => '����̾',
			   //    'Option' => array('Class' => 'nowrap')),
			// array('Column' => '���ռ��ǲ�',
			      // 'Option' => array('Class' => 'nowrap')),
			// array('Column' => '�ݸ�����',
			  //     'Option' => array('Class' => 'nowrap')),
		),
	'LIST_IDS' => array('����ID', '����', '��', '̾', "ObjectID", '�եꥬ��'),
);

if ($_mx_checkin_list_default_sort)
  $__ppa_checkin_list_cfg['DEFAULT_SORT'] = $_mx_checkin_list_default_sort;

if ($_mx_use_checked_in_patient_status) {
	$__ppa_checkin_list_cfg['ENABLE_QBE'][] =
		array('Column' => '���մ��Ծ���',
		      'Compare' => "($__lib_ppa_status_dbenum_order)",
		      'Draw' => 'enum',
		      'Singleton' => 1,
		      'Enum' => $__lib_ppa_status_dbenum_enum);
	$__ppa_checkin_list_cfg['ALLOW_SORT']['���մ��Ծ���'] =
		array('���մ��Ծ���' => 'CS."����"');
}

if ($_mx_use_dept_in_reception) {
	$__ppa_checkin_list_cfg['LLAYO'] =
		_lib_so_zip_layo($__ppa_checkin_list_cfg['LCOLS'], array
				 ($__status, '��̾', '����', '���ԥ��롼��',
				  '��Ū', 'ͽ�����', '���ԥޡ���', '//',
				  '����ID', '�եꥬ��', 'ǯ��', '������ʬ',
				  '��', '����̾',
				  '���ջ���', '�����象����'));
	$__ppa_checkin_list_cfg['ALLOW_SORT']['����̾'] =
		array('����̾' => "($__lib_ppa_dept_sql)");
	$__ppa_cil_eqbe = array('����ID', '�եꥬ��', '����',
				'ǯ��', '���ԥ��롼��', 
				'ͽ����', '����');
} else if ($_mx_use_tsuru_reception_layout) {
	$__ppa_checkin_list_cfg['LLAYO'] =
		_lib_so_zip_layo($__ppa_checkin_list_cfg['LCOLS'], array
				 ('ͽ�����',
				  '���ջ���',
				  '����ID', '��̾', '�եꥬ��', 'ǯ��',
				  '��',
				  '��Ū', $__status));
//0522-2013
	$__ppa_cil_eqbe = array('����ID', '�եꥬ��','furi', 'ǯ��', 'ͽ����');
} else {
	$__ppa_checkin_list_cfg['LLAYO'] =
		_lib_so_zip_layo($__ppa_checkin_list_cfg['LCOLS'], array
				 ($__status, '����ID', '��̾', '�եꥬ��',
				  '��Ū', '����', 'ǯ��', '�缣����̾',
				  '��',
				  '���ռ��ǲ�', '�ݸ�����',
				  '���ջ���', '�����象����', 'ͽ�����'));
//0522-2013
	$__ppa_cil_eqbe = array('����ID', '�եꥬ��','furi',
				'����', 'ǯ��', 'ͽ����');
}
if ($_mx_use_checked_in_patient_status) {
	$__ppa_cil_eqbe = array_merge(array($__status), $__ppa_cil_eqbe);
}
$__ppa_checkin_list_cfg['ENABLE_QBE'] =
	_lib_so_zip_layo($__ppa_checkin_list_cfg['ENABLE_QBE'],
			 $__ppa_cil_eqbe);

class ppa_checkin_list extends list_of_simple_objects {
	var $default_row_per_page = 40;
	var $list_name = '������Ģ';
	var $use_token = 'UsingListOfCheckIn';
	var $use_refresh = 1;

	function ppa_checkin_list($prefix, $lop_in_main=NULL, $cfg=NULL) {
		global $_mx_count_patient_checkin_list_rows;
		global $__ppa_checkin_list_cfg;
		global $_mx_max_checkin_row_per_page;

		if (!is_null($_mx_max_checkin_row_per_page))
		  $this->default_row_per_page = $_mx_max_checkin_row_per_page;
		if (!$lop_in_main)
			$this->default_row_per_page = 4;
		if (is_null($cfg))
			$cfg = $__ppa_checkin_list_cfg;
		$this->setup_config(&$cfg);

		if ($_mx_count_patient_checkin_list_rows)
//0401-2013 english
			$this->count_total_rows =
				'display in %2$d  from %1$d';

		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
	}

	function setup_config(&$cfg) {
		$cfg['HSTMT'] = ppa_checkin_list_hstmt();
		$cfg['STMT'] = $cfg['HSTMT'];
	}

	function enum_list($desc) {
		if ($desc['Enum'] == 'lazy') {
			switch ($desc['Column']) {
			case '���ԥޡ���':
				return mx_dbenum_patientmark('=');
			case '���ԥ��롼��':
				return mx_dbenum_patientgroup();
			case '�缣��':
				return mx_dbenum_primarydoctor();
			case '����':
				return mx_dbenum_doctordepartment();
			case 'ͽ����':
				return mx_dbenum_apptcandidatedr();
			}
		}
		return $desc['Enum'];
	}

	function dx_pt_status($desc, $value, $row) {
		switch ($value) {
		case 'Y':
			$status = '��';
			break;
		case 'A':
			$z = $row['�߱�����'];
			if ($z)
				$status = sprintf('��(%s)', $row['�߱�����']);
			else
				$status = '��';
			break;
		default:
			$status = '';
			break;
		}
		print $status;
	}

}

class ppa_checkin_self_list extends ppa_checkin_list {
	var $use_token = 'UsingListOfCheckInForMe';

	function ppa_checkin_self_list($prefix, $lop_in_main=NULL, $cfg=NULL) {
		global $__ppa_checkin_list_cfg;

		if (is_null($cfg)) {
			$cfg = $__ppa_checkin_list_cfg;
 //	0611-2011		$cfg['ENABLE_QBE'] =
 //				_lib_so_dropzip_layo($cfg['ENABLE_QBE'],
 //						     array('ͽ����'));
		}
		ppa_checkin_list::ppa_checkin_list
			($prefix, $lop_in_main, $cfg);
	}

	function setup_config(&$cfg) {
		global $mx_authenticate_current_user;
		global $_mx_null_patient_in_self_list;
//0611-2011
		$_mx_null_patient_in_self_list=1;
		$INCLUDE_NULL = '';

//0701-2011
		if ($_mx_null_patient_in_self_list)
			$INCLUDE_NULL = 'OR "ͽ����"=67 ';

		ppa_checkin_list::setup_config(&$cfg);

		$u = mx_db_sql_quote($mx_authenticate_current_user);

		$db = mx_db_connect();
		$stmt = <<<SQL
			SELECT 1
			FROM modalities_to_medex_employee AS M
			JOIN "������Ģ" AS E
			ON E."ObjectID" = M.employee
			WHERE E.userid = $u
SQL;
		$r = pg_query($db, $stmt);
//0611-2011
		if (!$r)
			return;
		$r = pg_fetch_all($r);
		if (!$r || !count($r))
			return;

		$this->list_name = 'ͽ���象���ԥꥹ��';
	$S = <<<SQL
			AND ( EXISTS (
				SELECT 1
				FROM modalities_to_medex_employee AS M
				JOIN "������Ģ" AS E
				ON E."ObjectID" = M.employee
				WHERE E.userid = $u
				AND M.modality = "ͽ����"
				)
			    $INCLUDE_NULL
			)
SQL;



		$cfg['STMT'] .= $S;
		$cfg['HSTMT'] .= $S;
	}

}

class per_patient_application extends single_table_application {

  var $use_list_of_patients = 1;
  var $use_list_of_checkin = 0;
  var $top_inside_form = 1;
  var $use_list_of_patients_in_main = 1;
  var $use_patient_history = 1;

  /*
   * Bitmask: 01 means show the first record that appear in loo with
   * sod, immediately after switching patients.  02 means start the
   * application with soe to create a new record.  If both bits are set,
   * then first check if there is an existing record, and show it with
   * sod if there is, otherwise start soe.  However, bit 04 can be used
   * in addition to start by editing the existing record with soe,
   * bypassing sod.  This will be handy for singleton applications.
   *
   * A subclass POA enhances this and assigns bits 8 and 16.
   */
  var $use_auto_sod_soe_setup = 0;

  function setup_patient() {
    global $_mx_patient_id_zeropad;

    if (array_key_exists('PatientID', $_REQUEST)) {
	    $patient_id = trim(mx_check_request('PatientID'));
	    $patient_id = mb_convert_kana($patient_id, 'as', 'euc');
	    $patient_id = mx_zeropad($patient_id, $_mx_patient_id_zeropad);
	    $_REQUEST['PatientID'] = $patient_id;
    }

    $uselop = NULL;
    if (!mx_check_request('SetPatient') && !mx_check_request('UnuseLOP')) {
	    if ($this->use_list_of_patients &&
		mx_check_request('UseListOfPatients'))
		    $uselop = 'ppa_patient_list';
	    if ($this->use_list_of_checkin &&
		mx_check_request('UseListOfCheckIn'))
		    $uselop = 'ppa_checkin_list';
	    if ($this->use_list_of_checkin_for_me &&
		mx_check_request('UseListOfCheckInForMe'))
		    $uselop = 'ppa_checkin_self_list';
	    if (is_null($uselop)) {
		    if (mx_check_request('UsingListOfPatients'))
			    $uselop = 'ppa_patient_list';
		    elseif (mx_check_request('UsingListOfInPatients'))
			    $uselop = 'ppa_inpatient_list';
		    elseif (mx_check_request('UsingListOfCheckIn'))
			    $uselop = 'ppa_checkin_list';
		    elseif (mx_check_request('UsingListOfCheckInForMe'))
			    $uselop = 'ppa_checkin_self_list';
	    }
    }
    if (is_null($uselop) && $this->auto_use_lop &&
	!mx_check_request('PatientID')) {
	    if ($this->encounter_mode == 'I')
		    $uselop = 'ppa_inpatient_list';
	    else
		    $uselop = $this->auto_use_lop;
    }
    if (mx_check_request('SetPatient')) {
      $db = mx_db_connect();
      $stmt = '
SELECT P."ObjectID", (P."��" || P."̾") AS "����̾", P."����ID", P."�եꥬ��"
FROM "������Ģ" AS P
WHERE P."Superseded" IS NULL AND P."����ID" = ' . mx_db_sql_quote($patient_id);
      $pt = mx_db_fetch_single($db, $stmt);
      if ($pt) {
	$this->patient_ID = trim($pt['����ID']);
	$this->patient_ObjectID = $pt['ObjectID'];
	$this->patient_Name = $pt['����̾'];
	$this->patient_Kana = $pt['�եꥬ��'];
      } else {
	$this->patient_ID = $patient_id;
      }
      $this->switch_patient = 1;
      if (mx_check_request('SetSODObject'))
	$this->setSodObject = trim(mx_check_request('SetSODObject'));
      elseif (mx_check_request('NewSOEObject'))
        $this->newSoeObject = 1;
    } elseif ($uselop) {
	    $lop = new $uselop("$uselop-",
			       $this->use_list_of_patients_in_main);
	    if ($lop->changed() && $lop->chosen()) {
		    $v = $lop->chosen_data();
		    $this->patient_ID = trim($v['����ID']);

		    /*
		     * ppa_checkin_list uses ObjectID to point at the
		     * checkin list item, and uses ���� for the patient.
		     * ppa_patient_list and ppa_inpatient_list use
		     * ObjectID for the patient.
		     */
		    if (array_key_exists('����', $v))
			    $this->patient_ObjectID = trim($v['����']);
		    else
			    $this->patient_ObjectID = trim($v['ObjectID']);
		    $this->patient_Name = trim($v['��']) . trim($v['̾']);
		    $this->patient_Kana = trim($v['�եꥬ��']);

		    print "<!--\n";
		    var_dump($uselop);
		    var_dump($v);
		    print "-->\n";

		    if ($v['ObjectID'] &&
			($uselop == 'ppa_checkin_list' ||
			 $uselop == 'ppa_checkin_self_list'))
			    mx_note_checkin_list_use($this->u,
						     $v['ObjectID'],
						     $this->patient_ID);
		    else
			    mx_note_checkin_list_use($this->u, '', '');
		    $this->switch_patient = 1;
	    } else
		    $this->lop = $lop;
    } else {
      $this->patient_ID = mx_check_request('PatientID');
      $this->patient_ObjectID = mx_check_request('PatientObjectID');
      $this->patient_Name = mx_check_request('PatientName');
      $this->patient_Kana = mx_check_request('PatientKana');
    }
    if (trim($this->patient_ObjectID) == '')
      $this->patient_ObjectID = NULL;

    if (!is_null($this->patient_ObjectID) &&
	mx_authorize_patient_access($this->patient_ObjectID)) {
	    $this->denied_patient_ObjectID = $this->patient_ObjectID;

	    $this->patient_ID = NULL;
	    $this->patient_Name = NULL;
	    $this->patient_Kana = NULL;
	    $this->patient_ObjectID = NULL;
	    $this->setSodObject = NULL;
	    $this->lop = NULL;
    }
  }

  function appbar_filter($path, $name, $pid) {
	  if (trim($pid) == '') {
		  /*
		   * Do not show applications that set encounter to
		   * "finished" and such when seeing no patient.
		   */
		  if (is_encounter_state_application($path))
			  return 0;
	  } else {
		  /*
		   * Do not show applications that switch encounter
		   * mode between Inpatient and Outpatient when
		   * already seeing a patient.
		   */
		  if ($path == 'u/everybody/encounter-mode-flip.php')
			  return 0;
	  }
	  return 1;
  }

  function setup() {
    global $_mx_ppa_all_use_extra_pane_in_unused_soe;

    if ($_mx_ppa_all_use_extra_pane_in_unused_soe)
      $this->extra_pane_in_unused_soe = 1;

    $this->setup_patient();
    if (is_null($this->patient_ObjectID))
      return;
    $v = single_table_application::setup();
    if ($v) {
      return $v;
    }
    if ($this->switch_patient)
      $this->switch_patient_reset();
    if ($this->setSodObject)
	    $this->sod->reset($this->setSodObject);
    else if ($this->switch_patient) {
	    if ($this->newSoeObject)
		    $this->soe->anew(NULL);
	    else
		    $this->auto_sod_soe_setup();
    }
  }

  function auto_sod_soe_setup() { // override
    if (!$this->use_auto_sod_soe_setup)
      return;
    if (($this->use_auto_sod_soe_setup & 01) &&
	$this->loo->select_first()) {
	    if (($this->use_auto_sod_soe_setup & 04) &&
		(!$this->_browse_only))
		    $this->soe->edit($this->loo->chosen());
	    else
		    $this->sod->reset($this->loo->chosen());
    }
    else if (($this->use_auto_sod_soe_setup & 02) &&
	     (!$this->_browse_only))
      $this->soe->anew(NULL);
  }

  function switch_patient_reset() { // override
    $this->loo->reset(NULL);
    $this->sod->reset(NULL);
    $this->soe->reset(NULL);
  }

  function setup_widgets() {
    $this->loo = $this->list_of_objects('loo-', &$this);
    $this->sod = $this->object_display('sod-', &$this);
    $this->soe = $this->object_edit('soe-', &$this);
    if ($this->loo->lost_selection()) {
	    $this->sod->reset(NULL);
	    $this->soe->reset(NULL);
    }
  }

  function cfg_pt(&$cfg, &$it) {
    $cfg['Patient_ID'] = $it->patient_ID;
    $cfg['Patient_ObjectID'] = $it->patient_ObjectID;
    $cfg['Patient_Name'] = $it->patient_Name;
    $cfg['Patient_Kana'] = $it->patient_Kana;
  }

  function draw_ppa_applist() {
	  global $_mx_use_appbar;
	  if (!$_mx_use_appbar)
		  mx_draw_ppa_applist($this->patient_ID);
  }

  function draw_patientinfo() { // override
    global $_mx_cheap_layout;
    mx_draw_patientinfo_brief($this->patient_ObjectID);
    if (!$_mx_cheap_layout)
	    $this->draw_ppa_applist();
  }
//03-28-13 English
  function application_name() {
	  if ($this->encounter_mode == 'I')
		  return $this->auth[1] . ' (IN)';
	  else if ($this->encounter_mode == 'O')
		  return $this->auth[1] . ' (OUT)';
	  else
		  return $this->auth[1];
  }

  function top_pane() {
    global $_mx_cheap_layout;
    global $_mx_resource_dir;
    global $_mx_use_appbar;
    global $_mx_bmd_layout;
    global $_mx_disable_appbar_during_edit;

    print "<table width=\"100%\"><tr valign=\"top\"><td width=\"30%\">";

    single_table_application::top_pane_left($_mx_use_appbar ? 0 : 1);
    $invis = mx_hide_patient_selection() ? ' invisible' : '';
    print "</td><td class=\"ptsel$invis\">";
//0325-2013
    if ($this->lop) {
	    if (!$this->use_list_of_patients_in_main) {
		mx_titlespan(' ' . 'SELECT');
		mx_formi_submit('UnUseLOP', 1,
				'<img src="/' . $_mx_resource_dir .
				'/images/ptid_select.png">');
		$this->lop->draw();
	    }
	    mx_formi_hidden($this->lop->use_token, 1);
    }
    if (!$this->lop || $this->use_list_of_patients_in_main) {

	    if ($_mx_disable_appbar_during_edit && $this->edit_in_progress()) {
		    mx_formi_hidden('PatientID', $this->patient_ID);
	    } else {
		    // 0323-2013 print '����ID:';
print 'Patient ID:';
		    mx_formi_text('PatientID', $this->patient_ID,
				  array('ime' => 'disabled'));
		    mx_formi_pt_submit('SetPatient');
		    if ($this->use_list_of_patients)
			    mx_formi_pt_submit('UseListOfPatients');
		    if ($this->use_list_of_checkin)
			    mx_formi_pt_submit('UseListOfCheckIn');
		    if ($this->use_list_of_checkin_for_me)
			    mx_formi_pt_submit('UseListOfCheckInForMe');
		    if ($_mx_cheap_layout && !is_null($this->patient_ObjectID))
			    $this->draw_ppa_applist();
	    }
	    if ($this->use_patient_history &&
		!is_null($this->patient_ObjectID))
		    mx_draw_ppa_index($this->patient_ObjectID,
				      $this->patient_ID);
    }

    if (! is_null($this->patient_ObjectID))
      $this->draw_patientinfo();
    elseif (! is_null($this->denied_patient_ObjectID))
      print "<br />���ꤵ�줿���Ԥˤϥ��������Ǥ��ޤ���";
    elseif ($this->patient_ID != '')
      print "<br />���ꤵ�줿���Ԥ�¸�ߤ��ޤ���";
    else
	    /* No patient chosen yet */
	    $this->left_pane_only = 1;
    print "</td></tr></table>\n";
    mx_formi_hidden('PatientObjectID', $this->patient_ObjectID);
    mx_formi_hidden('PatientName', $this->patient_Name);
    mx_formi_hidden('PatientKana', $this->patient_Kana);
    if ($_mx_use_appbar)
	    mx_appbar($this);
  }

  function extra_pane() {
	  if (is_null($this->patient_ObjectID))
		  return;
	  index_pt_left_pane_1($this->patient_ObjectID, $this->patient_ID);
  }

  function draw_plain_new_control($vertical=0) {
	  if (is_null($this->patient_ObjectID))
		  return;
	  single_table_application::draw_plain_new_control($vertical);
  }

  function left_pane() {
	  if ($this->lop) {
		  mx_titlespan(' ' . 'SELECT');
		  $this->lop->draw();
	  }
	  else if (is_null($this->patient_ObjectID))
		  return;
	  else
		  $this->left_pane_1();
  }

  function left_pane_1() { // override
    single_table_application::left_pane();
  }

  function right_pane() {
    if (is_null($this->patient_ObjectID))
      return;
    $this->right_pane_1();
  }

  function right_pane_1() { // override
    single_table_application::right_pane();
  }

  function allow_new() {
    return (!is_null($this->patient_ID) &&
	    single_table_application::allow_new());
  }

}

class ppa_patient_list_static extends ppa_patient_list {
	var $default_row_per_page = -1;

	function tweak_config(&$cfg) {
		$c = array();
		foreach ($cfg as $k => $v) {
			if ($k == 'ALLOW_SORT')
				continue;
			if ($k == 'ENABLE_QBE')
				continue;
			if ($k == 'LIST_IDS')
				continue;
			$c[$k] = $v;
		}
		$cfg = $c;
		$cfg['REQUEST_VIA_GET'] = '%d/index.htm';
	}
}

class ppa_static extends single_table_application
{
	var $use_single_pane = 1;
	var $_browse_only = 1;

	function ppa_static() {
		single_table_application::single_table_application();

		$this->patient_ObjectID = NULL;
		$this->application_ObjectID = NULL;
		if (array_key_exists('PATH_INFO', $_SERVER)) {
			$this->parse_pathinfo();
		} else {
			$this->special_request = 'index.htm';
		}
	}

	function parse_pathinfo() {
		$pathinfo = explode('/', $_SERVER['PATH_INFO']);
		if (is_null($pathinfo[1])) {
			$this->special_request = 'index.htm';
		} else if ($pathinfo[1] == 'rsrc') {
			$this->special_request = 'rsrc';
		} else if ($pathinfo[1] == 'blob') {
			$this->special_request = 'blob';
		} else {
			$this->parse_rest($pathinfo);
		}
	}

	function parse_rest($pathinfo) {
		$this->application = $pathinfo[1];
		if ($pathinfo[2] && $pathinfo[2] != 'index.htm') {
			$this->patient_ObjectID = $pathinfo[2];
			$this->patient_ID = 'unused';
			$this->patient_Name = 'unused';
			$this->patient_Kana = 'unused';
		}

		if ($pathinfo[3] && $pathinfo[3] != 'index.htm') {
			$this->application_ObjectID =
				preg_replace('/\.htm$/', '', $pathinfo[3]);
		}
	}

	function main() {
		global $_mx_resource_dir;

		switch ($this->special_request) {
		case 'index.htm':
			$me = preg_replace('/^(?:.*\/)/', '',
					   $_SERVER['PHP_SELF']);
			return mx_http_redirect("$me/karte/index.htm");
			break;
		case 'rsrc':
			$d = substr($_SERVER['PATH_INFO'], 5);
			return mx_http_redirect("/" . $_mx_resource_dir . $d);
			break;
		case 'blob':
			$d = $_SERVER['PATH_INFO'];
			$m = array();
			preg_match('/^\/blob\/(\d+)(\..*)$/', $d, &$m);
			$d = $m[1];
			$m = $m[2];
			return mx_http_redirect("/blobmedia.php/$d/x$m");
		}

		single_table_application::main();
	}

	function html_head() {
		$rsrc = $this->rsrc_prefix();
		$option = array('rsrc' => $rsrc,
				'ctype' => 'text/html; charset=Shift_JIS');
		return mx_html_head_1($this->auth[1], $option);
	}

	function rsrc_prefix() {
		/*
		 * E.g.
		 * /index.htm         ==> "rsrc/...."
		 * /karte/index.htm   ==> "../rsrc/...."
		 * /karte/1/index.htm ==> "../../rsrc/...."
		 */
		$path = $_SERVER['PATH_INFO'];
		$depth = count(explode('/', $path));
		$up = '';
		if (2 < $depth)
			$up = str_repeat('../', $depth - 2);
		return $up . "rsrc";
	}

	function open_form_head() {
		return '<div>';
	}
	function close_form() {
		return '</div>';
	}

	function up() {
		if (is_null($this->patient_ObjectID))
			return "index.htm";
		else if (is_null($this->application_ObjectID))
			return "../index.htm";
		else
			return "index.htm";
	}

	function top_pane() {
		$appname = $this->application_name();
		$up = $this->up();
		if ($up)
			print "<a href=\"$up\">";
		mx_titlespan($appname);
		if ($up)
			print "</a>\n";
		if (!is_null($this->patient_ObjectID))
			mx_draw_patientinfo_brief($this->patient_ObjectID);
	}

	function pt_cfg_items() {
		$cfg = array();
		$cfg['Patient_ObjectID'] = $this->patient_ObjectID;
		$cfg['Patient_ID'] = 'unused';
		$cfg['Patient_Name'] = 'unused';
		$cfg['Patient_Kana'] = 'unused';
		return $cfg;
	}

	function single_pane() {
		if (is_null($this->patient_ObjectID)) {
			$d = new ppa_patient_list_static('loo-',
							 'always_lop_in_main');
			$d->draw();
		} else if (is_null($this->application_ObjectID)) {
			$cfg = $this->pt_cfg_items();
			$cfg['REQUEST_VIA_GET'] = "%d.htm";
			$d = $this->list_of_objects('los-', $cfg);
			$d->draw();
		} else {
			$cfg = $this->pt_cfg_items();
			$_REQUEST['sod-id'] = $this->application_ObjectID;
			$d = $this->object_display('sod-', $cfg);
			$d->draw();
		}
	}

	function setup_widgets() {
		$this->loo = new _lib_so_list_of_dummy_objects('dummy-loo-');
		$this->sod = new _lib_so_dummy_object_display('dummy-sod-');
		$this->soe = new _lib_so_dummy_object_edit('dummy-soe-');
	}
}

?>
