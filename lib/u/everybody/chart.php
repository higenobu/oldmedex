<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/nurse/fim.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/ps-anew.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/diseasepick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/ps-dw.php';

function _lib_u_everybody_plansheet_ident_enum() {
  $a = array();
  $n = func_num_args();
  for ($i = 0; $i < $n; $i++) {
    $v = func_get_arg($i);
    $a[$v] = $v;
  }
  return $a;
}

function __lib_u_everybody_plansheet_ps_ct($column, $label=NULL) {
	if (is_null($label)) $label = $column;
  return array(array('Column' => $column,
		     'Label' => $label,
		     'Draw' => 'enum',
		     'Enum' => _lib_u_everybody_plansheet_ident_enum
		     ("����","�ʤ�","����","̤��ǧ")),
	       array('Column' => $column . "������",
		     'Label' => $label . "������",
		     'Draw' => 'textarea',
		     'Option' => array('cols' => 50, 'rows' => 6)));
}

function __lib_u_everybody_plansheet_ps_ihh($label) {
  global $_lib_u_nurse_fim__fim_enum;
  return array(array('Column' => $label, 'Draw' => 'enum',
		     'Enum' => $_lib_u_nurse_fim__fim_enum,
		     'CSV_NO_ENUM' => 1));
}

function __lib_u_everybody_plansheet_ps_pc($column, $label=NULL) {
  global $_lib_u_nurse_fim__fim_enum;
  if (is_null($label)) $label = $column;
  return array(
	  array('Column' => $column . "_P",
		'Label' => $label,
		'Draw' => 'enum',
		'Enum' => $_lib_u_nurse_fim__fim_enum,
		'CSV_NO_ENUM' => 1),
	  array('Column' => $column . "_TP",
		'Label' => $label . "(��ˡ��)",
		'Draw' => 'enum',
		'Enum' => $_lib_u_nurse_fim__fim_enum,
		'CSV_NO_ENUM' => 1),
	  array('Column' => $column . "_C",
		'Label' => $label . "������",
		'Draw' => 'textarea',
		'Option' => array('cols' => 50, 'rows' => 6)));
}

$_lib_u_everybody_plansheet_employee_array =
array(
"�缣��" => 'PD',
"���ô����" => 'RD',
"PT" => 'PT',
"OT" => 'OT',
"ST" => 'ST',
"�Ǹ��" => 'NS',
"SW" => 'SW',
);

function __lib_u_everybody_plansheet_employee_refetch_emp_name($m)
{
	if ($m == '')
		return NULL;
	$stmt = '
SELECT "��" || \' \' || "̾" AS "N"
FROM "������Ģ"
WHERE "ObjectID" = ' . mx_db_sql_quote($m) . '
AND "Superseded" IS NULL';
	$db = mx_db_connect();
	$o = mx_db_fetch_single($db, $stmt);
	if (is_array($o) && array_key_exists('N', $o))
		return $o['N'];
	return NULL;
}

function __lib_u_everybody_plansheet_employee($tbl, $label,
					      $column=NULL, $xtra=NULL)
{
  global $_lib_u_manage_employee_cfg;
  if (is_null($column))
    $column = $label."̾";
  return array('Column' => $column,
	       'Label' => $label,
	       'Draw' => 'subpick',
	       '_NO_ICOL_' => 1,
	       '_SQL_SELECT_' => $tbl.'."��" || \' \' || '.$tbl.'."̾"',
		 '_SQL_EXTRA_' =>
		 array($tbl.'."ObjectID"' => $label),
		 'Subpick' => array
		 ('Class' => 'list_of_employees',
		  'Message' => $column,
		  'Config' => $_lib_u_manage_employee_cfg,
		  'ListID' => array('ObjectID', '��̾'),
		  'ObjectColumn' => $label,
		  ),
	       );
}

function __lib_u_everybody_plansheet_cfg(&$cfg) {
  global $_lib_u_doctor_diseasepick_dps_cfg,
         $_lib_u_everybody_plansheet_employee_array;
  $cfg = array_merge
	  ($cfg,
	   array('TABLE' => 'otatest_order',
		 'ALLOW_SORT' => 1,
		  
		 'LCOLS' => array(  'order_date,recorded_on',
				  ),
		 'ICOLS' => array(patient),
		 'NO_3WAY_ICOLS' => array(patient),

		 'UNIQ_ID' => 'PS."ObjectID"'));

  // List of flip-pages COLS elements.
  $basic = array(array('Column' => 'order_date',
		       'Draw' => 'date',
		        ),
		 );
  foreach ($_lib_u_everybody_plansheet_employee_array as $l => $t) {
	  $basic[] = __lib_u_everybody_plansheet_employee($t, $l);
  }

  $basic = array_merge
	  ($basic,
	   array(array('Column' => "notes",
		       'Draw' => 'subpick',
		       'Subpick' => array
		       ('Class' => 'diseasepick',
			'Message' => '����̾���դ���',
			'Config' => $_lib_u_doctor_diseasepick_dps_cfg,
			'ListID' => array('��̾ɽ��', '��̾ɽ��'),
			'Allow_NULL' => 1,
			),
		       ),
		 
		  
	  
		 array('Column' => "ss7",
		       'Draw' => 'subpick',
		       'Subpick' => array
		       ('Class' => 'diseasepick',
			'Message' => '��ʻ��̾���դ���',
			'Config' => $_lib_u_doctor_diseasepick_dps_cfg,
			'ListID' => array('��̾ɽ��', '��̾ɽ��'),
			'Allow_NULL' => 1,
			),
		       ),
		 array('Column' => "special_req",
		       'Draw' => 'text',
		       'Option' => array('size' => 50,
					 'maxlength' => 40)),
		 array('Column' => "ss0",
		       'Draw' => 'text',
		       'Option' => array('size' => 50,
					 'maxlength' => 40)),
		 array('Column' => "category", 'Draw' => 'enum',
		       'Enum' =>
		       _lib_u_everybody_plansheet_ident_enum
		       ('-','J','A1','A2','B1','B2','C1','C2')),
		 array('Column' => "addition",
		       'Label' => "��Ω��Ƚ����",
		       'Draw' => 'enum',
		       'Enum' => 
		       _lib_u_everybody_plansheet_ident_enum
		       ('I', 'IIa', 'IIb', 'IIIa', 'IIIb', 'IV',
			'V', 'VI', 'M'))
		   ));

  $flippage = array
    (
     '���ܹ���' => $basic,

     '���ȹ�¤����ǽ' =>
     array_merge(
		 array
		 (array('Column' => 'ss0',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6)),
		 ),

		 
		 array
		 (array('Column' => 'ss1',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6)),
		  array('Column' => 'ss7',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6)))
		 ),
/*

     '��ư' =>
     array_merge(array(array('Column' => "ss0",
			     'Draw' => 'text',
			     'Option' => array('size' => 50,
					       'maxlength' => 40)),
		       array('Column' => 'ss1',
			     'Draw' => 'textarea',
			     'Option' => array('cols' => 50, 'rows' => 6)),
		       array('Column' => 'ss0',
			     'Draw' => 'textarea',
			     'Option' => array('cols' => 50, 'rows' => 6))),

		 __lib_u_everybody_plansheet_ps_pc("����"),
		 __lib_u_everybody_plansheet_ps_pc("����"),
		 __lib_u_everybody_plansheet_ps_pc("����"),
		 __lib_u_everybody_plansheet_ps_pc("���ᡦ��Ⱦ��"),
		 __lib_u_everybody_plansheet_ps_pc("���ᡦ��Ⱦ��"),
		 __lib_u_everybody_plansheet_ps_pc("�ȥ���ư��"),

		 array
		 (array('Column' => 'notes',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6)),
		  array('Column' => 'notes',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6))),

		 __lib_u_everybody_plansheet_ps_pc("��Ǣ����"),
		 __lib_u_everybody_plansheet_ps_pc("��������", "���ش���"),

		 array
		 (array('Column' => '������û����ɸ',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6)),
		  array('Column' => '���������ץ���',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6))),

		 __lib_u_everybody_plansheet_ps_pc("�٥åɡ��ػҡ��ְػ�",
						   "�ְػҰܾ�"),

		 array
		 (array('Column' => '�ְػҰܾ衦û����ɸ',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6)),
		  array('Column' => '�ְػҰܾ衦���ץ���',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6))),


		 __lib_u_everybody_plansheet_ps_pc("�ȥ���", "�ȥ���ܾ�"),
		 __lib_u_everybody_plansheet_ps_pc("���奷��",
						   "����ܾ�"),

		 array
		 (array('Column' => '�ܾ衦û����ɸ',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6)),
		  array('Column' => '�ܾ衦���ץ���',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6))),

		 __lib_u_everybody_plansheet_ps_pc("�ְػ�"),
		 array
		 (array('Column' => '�ְػҡ�û����ɸ',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6)),
		  array('Column' => '�ְػҡ����ץ���',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6))),

		 __lib_u_everybody_plansheet_ps_pc("���"),
		 __lib_u_everybody_plansheet_ps_pc("����"),
		 array
		 (array('Column' => '��ư��û����ɸ',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6)),
		  array('Column' => '��ư�����ץ���',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6))),

		 __lib_u_everybody_plansheet_ps_pc("����"),
		 __lib_u_everybody_plansheet_ps_pc("ɽ��"),
		 array
		 (array('Column' => '���ߥ�˥��������û����ɸ',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6)),
		  array('Column' => '���ߥ�˥�������󡦥��ץ���',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6))),

		 __lib_u_everybody_plansheet_ps_pc("�Ҳ�Ū��ή"),
		 __lib_u_everybody_plansheet_ps_pc("������"),
		 __lib_u_everybody_plansheet_ps_pc("����"),
		 array
		 (array('Column' => '�Ҳ�Ūǧ�Ρ�û����ɸ',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6)),
		  array('Column' => '�Ҳ�Ūǧ�Ρ����ץ���',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6)),

		  array('Column' => '��ư����',
			'Draw' => 'enum',
			'Enum' => array('L' => '���', 'W' => '�ְػ�')),
		  )
	   ),

     '����' =>
     array(array('Column' => "����", 'Draw' => 'enum',
		 'Enum' => array('0' => '̵��', '1' => '�·���',
				 '2' => '�ٿ���', '3' => 'ȯ�ɸ��࿦',
				 '4' => '�࿦ͽ��')),
	   array('Column' => "����ȼ�Ż�����",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "�кѾ���",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "�Ҳ񻲲á����ơ���������",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => ";�˳�ư�����ơ���������",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "�ౡ��", 'Draw' => 'enum',
		 'Enum' => array('0' => '����', '1' => '��²��',
				 '2' => '���ŵ���', '3' => '����¾')),
	   array('Column' => "����", 'Draw' => 'enum',
		 'Enum' => array('0' => '��������', '1' => 'ž��',
				 '2' => '����ž��',
				 '3' => '�����Բ�', '4' => '����¾')),
	   array('Column' => "��������",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "�Ż�����",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "�̶���ˡ",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "���������",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "�Ҳ��ư",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "��̣",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),

	   array('Column' => '���á�û����ɸ',
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array('Column' => '���á����ץ���',
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6))

	   ),

     '����' =>
     array(array('Column' => "��ݵ",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "�㳲����ǧ",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "����¾����",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),

	   array('Column' => '������û����ɸ',
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array('Column' => '���������ץ���',
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6))
	   ),

     '�Ķ�' =>
     array(array('Column' => "Ʊ���²",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "��²�ط�",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "�������",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "����",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "�＼�μ���",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "�ȥ����ͼ�",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "��������β���",
		 'Draw' => 'enum',
		 'Enum' => array('0' => '�Բ�', '1' => '��')),
	   array('Column' => "�ȼ���",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "����",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),

	   array('Column' => "�����¤", 'Draw' => 'enum',
		 'Enum' => array('0' => '����', '1' => '��')),
	   array('Column' => "�����¤����",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "ʡ�㵡��", 'Draw' => 'enum',
		 'Enum' => array('0' => '����', '1' => '��')),
	   array('Column' => "ʡ�㵡������", 'Draw' => 'enum',
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "�Ҳ��ݾ㥵���ӥ�", 'Draw' => 'enum',
		 'Enum' => array('0' => '����', '1' => '�Ⱦ��Ģ',
				 '2' => '�㳲ǯ��', '3' => '����¾')),
	   array('Column' => "�Ҳ��ݾ㥵���ӥ�����",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "����ݸ������ӥ�", 'Draw' => 'enum',
		 'Enum' => array('0' => '����', '1' => '��')),
	   array('Column' => "����ݸ������ӥ�����",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),

	   array('Column' => '�Ķ���û����ɸ',
		 'Label' => '�õ�����',
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array('Column' => '�Ķ������ץ���',
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6))
	   ),
     
     '�軰�Ԥ�����' =>
     array(array('Column' => "ȯ�¤ˤ���²���Ѳ�",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "�Ҳ�����",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "�򹯾�������ȯ��",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "����Ū�����ȯ��",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "�軰�Ԥ�������ɾ��������",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),

	   array('Column' => "�ౡ��μ����", 'Draw' => 'enum',
		 'Enum' => array('0' => '����', '1' => '��')),
	   array('Column' => "�ౡ��μ��������",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "��²�������Ѳ�", 'Draw' => 'enum',
		 'Enum' => array('0' => '����', '1' => '��')),
	   array('Column' => "��²�������Ѳ�����",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "��²�������Ѳ�", 'Draw' => 'enum',
		 'Enum' => array('0' => '����', '1' => '��')),
	   array('Column' => "��²�������Ѳ�����",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "��²�μҲ��ư�Ѳ�", 'Draw' => 'enum',
		 'Enum' => array('0' => '����', '1' => '��')),
	   array('Column' => "��²�μҲ��ư�Ѳ�����",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),

	   array('Column' => '�軰�Ԥ�������û����ɸ',
		 'Label' => '����',
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array('Column' => '�軰�Ԥ����������ץ���',
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6))
	     ),

     '��ɸ����ǧ' =>
     array(array('Column' => "1��������ɸ",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array('Column' => "�ܿͤδ�˾",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array('Column' => "��²�δ�˾",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array('Column' => "��ϥӥ�ơ������μ�������",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array('Column' => "����ײ�",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array('Column' => "�ౡ����ɸ����������",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array('Column' => "����ײ�",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array('Column' => "����ޤ����ౡ��μҲ񻲲äθ�����",
		 'Label' => "���衦�ౡ��Ҳ񻲲ø���",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array('Column' => "�ܿ�/��²�ؤ�����",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array('Column' => "�������������",
		 'Draw' => 'enum',
		 'Enum' => array('0' => '�ܿ�', '1' => '��²',
				 '2' => '����¾')),
	   array('Column' => "�����Խ�̾",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   ),

     '����¾' =>
     array(array("Column" => "��դˤ�륳����",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array("Column" => "�Ǹ�դˤ�륳����",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array("Column" => "PT�ˤ�륳����",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array("Column" => "OT�ˤ�륳����",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array("Column" => "ST�ˤ�륳����",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array("Column" => "MSW�ˤ�륳����",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array("Column" => "���ܻΤˤ�륳����",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array("Column" => "������",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   ),
*/

     );

  $page_num = -1;
  $selcol = array();
  $lselcol = array();
  foreach ($flippage as $page_name => $page_desc) {
    $page_num++;
    foreach ($page_desc as $a) {
      $a['Page'] = $page_num;

      if (!array_key_exists('Label', $a))
	      $a['Label'] = $a['Column'];
      if (!array_key_exists('Draw', $a))
	      $a['Draw'] = 'text';

      if (array_key_exists('_SQL_EXTRA_', $a)) {
	foreach ($a['_SQL_EXTRA_'] as $sql => $col) {
	  $cfg['ECOLS'][] = array('Column' => $col,
				  'Label' => $col,
				  'Draw' => 'text',
				  'Page' => -1);
	  $cfg['ICOLS'][] = $col;
	}
      }

      $cfg['ECOLS'][] = $a;
      $cfg['DCOLS'][] = $a;

      if (array_key_exists('_SQL_TABLE_', $a) ||
	  array_key_exists('_NO_ICOL_', $a))
	;
      else if (array_key_exists('Subpick', $a) &&
	       array_key_exists('ObjectColumn', $a['Subpick']))
	;
      else
	$cfg['ICOLS'][] = $a['Column'];

      if (array_key_exists('_SQL_SELECT_', $a)) {
	$sel = ('(' . $a['_SQL_SELECT_'] . ') AS ' .
		mx_db_sql_quote_name($a['Column']));
      }
      else {
	$table = 'PS';
	if (array_key_exists('_SQL_TABLE_', $a)) {
	  $table = $a['_SQL_TABLE_'];
	}
	$sel = ("$table." . mx_db_sql_quote_name($a['Column']) . ' AS '.
		mx_db_sql_quote_name($a['Column']));
      }
      $selcol[] = $sel;
      $found = array_search($a['Column'], $cfg['LCOLS']);
      if ($found || ($found === 0))
	$lselcol[] = $sel;

      if (array_key_exists('_SQL_EXTRA_', $a)) {
	foreach ($a['_SQL_EXTRA_'] as $sql => $col) {
	  $sel = "$sql AS " . mx_db_sql_quote_name($col);
	  $selcol[] = $sel;
	  $found = array_search($col, $cfg['LCOLS']);
	  if ($found || ($found === 0))
	    $lselcol[] = $sel;
	}
      }

    }
  }
  $cfg['DCOLS'][] = array('Column' => 'recorded_on',
			  'Label' => '��Ͽ��̾',
			  'Draw' => 'text');
  $select_join =
    ("\nFROM " . mx_db_sql_quote_name(otatest_order) . " AS PS\n" .
     "JOIN \"������Ģ\" AS P\n" .
     "ON P.\"ObjectID\" = PS.\"patient\" AND P.\"Superseded\" IS NULL\n" 
     );

  $lselect =
    ('SELECT PS."ObjectID", PS."CreatedBy", ' .
     '(E."��" || E."̾") AS "recorded_on", ' .
     implode(",\n", $lselcol) . $select_join);

  $select =
    ('SELECT PS."ObjectID", PS."CreatedBy", ' .
     '(E."��" || E."̾") AS "recorded_on", ' .
     implode(",\n", $selcol) . $select_join);

  $cfg['HSTMT'] = $select . "WHERE (NULL IS NULL)";
  $cfg['STMT'] = $lselect . "WHERE (PS.\"Superseded\" IS NULL)";
  $cfg['DPAGES'] = array_keys($flippage);
  $cfg['EPAGES'] = $cfg['DPAGES'];
  $cfg['DPAGE_BREAKS'] = $cfg['EPAGE_BREAKS'] = array(4);

  $cfg['SKIP_LEDCOLS'] = array('DCOLS' => 1, 'ECOLS' => 1); 
}

class list_of_rehab_plansheets extends list_of_ppa_objects {
  var $debug = 0;

  function list_of_rehab_plansheets($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_everybody_plansheet_cfg(&$cfg);
    list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
  }

  function row_paging_orders() {
    $paging_keys = $this->row_paging_keys();
    $paging_orders = array();
    foreach ($paging_keys as $col) {
      $paging_orders[] = (($col == 'order_date') ? 1 : 0);
    }
    return $paging_orders;
  }

}

class rehab_plansheet_display extends simple_object_display {
  function rehab_plansheet_display($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_everybody_plansheet_cfg(&$cfg);
    simple_object_display::simple_object_display($prefix, $cfg);
  }
  function history($move_direction=NULL) {
    $st = simple_object_display::history($move_direction);
    if ($st & 2)
	    return $st + 32; // we have our own CSV button.
    else
	    return $st;
  }

  function csv_data() {
    switch ($_REQUEST['CSV_HACK']) {
    case 1:
	    return $this->csv_data_1();
	    break;
    case 2:
	    return $this->csv_data_2();
	    break;
    }
  }

  function annotate_row_data(&$data) {
	$cog = array('����' => 1,
		     'ɽ��' => 1,
		     '�Ҳ�Ū��ή' => 1,
		     '������' => 1,
		     '����' => 1);
	$cogsum = $physum = 0;
	foreach ($data as $col => $val) {
		if (substr($col, -2, 2) != '_P')
			continue;
		$base = substr($col, 0, -2);
		if (array_key_exists($base, $cog)) {
			$cogsum += $val;
		}
		else {
			$physum += $val;
		}
	}
	// Yuck.
	$physum -= min($data['�ְػ�_P'], $data['���_P']);
	$data['FIM_NURSE_SUM_PHY'] = $physum;
	$data['FIM_NURSE_SUM_COG'] = $cogsum;
	$data['FIM_NURSE_SUM'] = $cogsum + $physum;
  }

  // returns list of value in predefined order
  function csv_data_1() {
    global $_lib_u_everybody_plansheet_csv_cols;
    $data = $this->prepare_data_for_draw();
    $r = array();
    $dcols = array();
    foreach ($this->so_config['DCOLS'] as $cfg)
      $dcols[$cfg['Column']] = $cfg;
    foreach ($_lib_u_everybody_plansheet_csv_cols as $col) {
      $v = $data[$col];
      if ((($dcols[$col]['Draw'] == 'enum') ||
	   ($dcols[$col]['Draw'] == 'static_enum')) &&
	  !$dcols[$col]['CSV_NO_ENUM']) {
        $v = $dcols[$col]['Enum'][$v];
      }
      $r[] = $v;
    }
    return array($r);
  }

  // returns (name1 => value1, name2 => value2, ...) mapping
  function csv_data_2() {
    global $_lib_u_everybody_plansheet_csv_cols;
    $data = $this->prepare_data_for_draw();
    $r = array();
    $dcols = array();
    foreach ($this->so_config['DCOLS'] as $cfg)
      $dcols[$cfg['Column']] = $cfg;
    foreach ($_lib_u_everybody_plansheet_csv_cols as $col) {
      $v = $data[$col];
      if ((($dcols[$col]['Draw'] == 'enum') ||
	   ($dcols[$col]['Draw'] == 'static_enum')) &&
	  !$dcols[$col]['CSV_NO_ENUM']) {
        $v = $dcols[$col]['Enum'][$v];
      }
      $r[$col] = $v;
    }
    foreach (array('FIM_NURSE_SUM_PHY', 'FIM_NURSE_SUM_COG', 'FIM_NURSE_SUM')
	     as $col) {
	    $r[$col] = $data[$col];
    }
    return $r;
  }

}

class rehab_plansheet_edit extends simple_object_ppa_edit {
  var $debug = 0;
  var $default_threeway_ok = 0; // subpick fails.
var $patient_column_name = 'patient';
  function rehab_plansheet_edit($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_everybody_plansheet_cfg(&$cfg);
    simple_object_edit::simple_object_edit
      ($prefix, &$cfg);
  }

  function anew_tweak($orig_id) {
    $this->data['order_date'] = mx_today_string();

    // Slurp existing data from various tables.  Yuck.
    $pt = $this->so_config['Patient_ObjectID'];
    __lib_u_everybody_ps_anew($pt, &$this->data);
  }

  function annotate_row_data(&$d) {
    $d['patient'] = $this->so_config['Patient_ObjectID'];
  }

  function annotate_form_data(&$d) {
    simple_object_edit::annotate_form_data($d);
    $this->annotate_row_data($d);
  }

  function _validate() {
    return true ;
  }

  function threeway_tweak($col, &$o, &$a, &$b, &$m) {
	  global $_lib_u_everybody_plansheet_employee_array;
	  if (!array_key_exists($col,
				$_lib_u_everybody_plansheet_employee_array))
		  return $m;

	  // We need to turn $o, $a, $b, and $m into names to be
	  // displayed.
	  $name = __lib_u_everybody_plansheet_employee_refetch_emp_name($m);
	  $m = $this->data[$col . '̾'] = $name;
	  $o = __lib_u_everybody_plansheet_employee_refetch_emp_name($o);
	  $a = __lib_u_everybody_plansheet_employee_refetch_emp_name($a);
	  $b = __lib_u_everybody_plansheet_employee_refetch_emp_name($b);
  }

}

$_lib_u_everybody_plansheet_csv_cols = array(

"��̾",
"����",
"��ǯ����",
"ǯ��",
"������",
"�缣��̾",
"���ô����̾",
"PT̾",
"OT̾",
"ST̾",
"�Ǹ��̾",
"SW̾",
"����̾",
"ȯ����",
"��¸��������ʻ��",
"����ȥ������",
"��ϥӥ�ơ��������",
"�������輫Ω��",
"������Ϸ�ͤ��������輫Ω��Ƚ����",
"�ռ��㳲",
"�ռ��㳲������",
"����", // ������
"���򥳥���",
"�����㳲", // ������
"�����㳲������",
"��ư�㳲",
"��ư�㳲������",
"ɽ�ߴ��о㳲",
"ɽ�ߴ��о㳲������",
"�������о㳲",
"�������о㳲������",
"�ݿ���ǽ�㳲",
"�ݿ���ǽ�㳲������",
"��Ǣ��ǽ�㳲",
"��Ǣ��ǽ�㳲������",
"���ص�ǽ�㳲",
"���ص�ǽ�㳲������",
"�Ƶ۽۴Ĵﵡǽ�㳲",
"�Ƶ۽۴Ĵﵡǽ�㳲������",
"�����㳲",
"�����㳲������",
"�����ư������",
"�����ư�����¥�����",
"�����㲼",
"�����㲼������",
"����",
"���ϥ�����",
"�ˤ�",
"�ˤߥ�����",
"Ⱦ¦�����̵��",
"Ⱦ¦�����̵�륳����",
"��վ㳲",
"��վ㳲������",
"�����㳲",
"�����㳲������",
"���ȵ�ǽ������¾",
"���֤�",
"�����夬��",
"�°�",
"Ω���夬��",
"Ω��",
"����ư�û����ɸ",
"����ư����ץ���",
"��ư��",
"��ư�١�û����ɸ",
"��ư�١����ץ���",
"����_P",
"����_TP",
"����_C",
"����_P",
"����_TP",
"����_C",
"����_P",
"����_TP",
"����_C",
"���ᡦ��Ⱦ��_P",
"���ᡦ��Ⱦ��_TP",
"���ᡦ��Ⱦ��_C",
"���ᡦ��Ⱦ��_P",
"���ᡦ��Ⱦ��_TP",
"���ᡦ��Ⱦ��_C",
"�ȥ���ư��_P",
"�ȥ���ư��_TP",
"�ȥ���ư��_C",
"����ե�����û����ɸ",
"����ե��������ץ���",
"��Ǣ����_P",
"��Ǣ����_TP",
"��Ǣ����_C",
"��������_P",
"��������_TP",
"��������_C",
"������û����ɸ",
"���������ץ���",
"�٥åɡ��ػҡ��ְػ�_P",
"�٥åɡ��ػҡ��ְػ�_TP",
"�٥åɡ��ػҡ��ְػ�_C",
"�ְػҰܾ衦û����ɸ",
"�ְػҰܾ衦���ץ���",
"�ȥ���_P",
"�ȥ���_TP",
"�ȥ���_C",
"���奷��_P",
"���奷��_TP",
"���奷��_C",
"�ܾ衦û����ɸ",
"�ܾ衦���ץ���",
"�ְػ�_P",
"�ְػ�_TP",
"�ְػ�_C",
"�ְػҡ�û����ɸ",
"�ְػҡ����ץ���",
"���_P",
"���_TP",
"���_C",
"����_P",
"����_TP",
"����_C",
"��ư��û����ɸ",
"��ư�����ץ���",
"����_P",
"����_TP",
"����_C",
"ɽ��_P",
"ɽ��_TP",
"ɽ��_C",
"���ߥ�˥��������û����ɸ",
"���ߥ�˥�������󡦥��ץ���",
"�Ҳ�Ū��ή_P",
"�Ҳ�Ū��ή_TP",
"�Ҳ�Ū��ή_C",
"������_P",
"������_TP",
"������_C",
"����_P",
"����_TP",
"����_C",
"�Ҳ�Ūǧ�Ρ�û����ɸ",
"�Ҳ�Ūǧ�Ρ����ץ���",
"��ư����",
"����",
"����ȼ�Ż�����",
"�кѾ���",
"�Ҳ񻲲á����ơ���������",
";�˳�ư�����ơ���������",
"�ౡ��",
"����",
"��������",
"�Ż�����",
"�̶���ˡ",
"���������",
"�Ҳ��ư",
"��̣",
"���á�û����ɸ",
"���á����ץ���",
"��ݵ",
"�㳲����ǧ",
"����¾����",
"������û����ɸ",
"���������ץ���",
"Ʊ���²",
"��²�ط�",
"�������",
"����",
"�＼�μ���",
"�ȥ����ͼ�",
"��������β���",
"�ȼ���",
"����",
"�����¤",
"�����¤����",
"ʡ�㵡��",
"ʡ�㵡������",
"�Ҳ��ݾ㥵���ӥ�",
"�Ҳ��ݾ㥵���ӥ�����",
"����ݸ������ӥ�",
"����ݸ������ӥ�����",
"�Ķ���û����ɸ",
"�Ķ������ץ���",
"ȯ�¤ˤ���²���Ѳ�",
"�Ҳ�����",
"�򹯾�������ȯ��",
"����Ū�����ȯ��",
"�軰�Ԥ�������ɾ��������",
"�ౡ��μ����",
"�ౡ��μ��������",
"��²�������Ѳ�",
"��²�������Ѳ�����",
"��²�������Ѳ�",
"��²�������Ѳ�����",
"��²�μҲ��ư�Ѳ�",
"��²�μҲ��ư�Ѳ�����",
"�軰�Ԥ�����������",
"�軰�Ԥ�������û����ɸ",
"�軰�Ԥ����������ץ���",
"1��������ɸ",
"�ܿͤδ�˾",
"��²�δ�˾",
"��ϥӥ�ơ������μ�������",
"����ײ�",
"�ౡ����ɸ����������",
"����ײ�",
"����ޤ����ౡ��μҲ񻲲äθ�����",
"��դˤ�륳����",
"�Ǹ�դˤ�륳����",
"PT�ˤ�륳����",
"OT�ˤ�륳����",
"ST�ˤ�륳����",
"MSW�ˤ�륳����",
"���ܻΤˤ�륳����",
"������",
"�����Խ�̾",
"�ܿ�/��²�ؤ�����",
"�������������",
);

////////////////////////////////////////////////////////////////

class everybody_plansheet_application extends per_patient_application {

  function setup() {
    if (!array_key_exists('CSV_HACK', $_REQUEST))
      return per_patient_application::setup();

    // HORRIBLE HACK HERE
    $this->setup_patient();
    $this->sod = $this->object_display('sod-', &$this);
    switch ($_REQUEST['CSV_HACK']) {
    case 1:
	    $this->emit_CSV_from_sod();
	    break;
    case 2:
	    $this->emit_printer_page();
	    break;
    }
    return 1;
  }

  function emit_printer_page() {
	  draw_plansheet($this->sod->csv_data());
  }

  function list_of_objects($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    return new list_of_rehab_plansheets($prefix, $cfg);
  }

  function object_display($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    return new rehab_plansheet_display($prefix, $cfg);
  }

  function object_edit($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    return new rehab_plansheet_edit($prefix, $cfg);
  }

  function extra_buttons_html() {
    $program = preg_replace('/\.php\/.*$/', '.php', $_SERVER['PHP_SELF']);

    // HORRIBLE HACK HERE

    $sod = $this->sod;
    $prefix = $sod->prefix;
    $param = array();
    $param[] = $prefix . 'id=' . $sod->id;
    if (!is_null($sod->history_ix))
      $param[] = $prefix . 'history-at=' . $sod->history_ix;
    $param[] = 'PatientObjectID=' . htmlspecialchars($this->patient_ObjectID);
    $param[] = 'PatientID=' . htmlspecialchars($this->patient_ID);
    $param[] = 'PatientName=' . htmlspecialchars($this->patient_Name);
    $param = implode('&amp;', $param);

    print '<a title="CSV����"';
    print 'href="' . $program . '/ps.txt?CSV_HACK=1&amp;';
    print $param;
    print '">';
    print mx_img_url('csv.png');
    print '</a>';

    print '&nbsp;&nbsp;';

    print '<a target="_blank" title="����"';
    print 'href="' . $program . '/ps.txt?CSV_HACK=2&amp;';
    print $param;
    print '">';
    print mx_img_url('printer.png');
    print '</a>';

  }

}
?>
