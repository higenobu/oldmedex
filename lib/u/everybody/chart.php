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
		     ("ある","なし","不明","未確認")),
	       array('Column' => $column . "コメント",
		     'Label' => $label . "コメント",
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
		'Label' => $label . "(療法士)",
		'Draw' => 'enum',
		'Enum' => $_lib_u_nurse_fim__fim_enum,
		'CSV_NO_ENUM' => 1),
	  array('Column' => $column . "_C",
		'Label' => $label . "コメント",
		'Draw' => 'textarea',
		'Option' => array('cols' => 50, 'rows' => 6)));
}

$_lib_u_everybody_plansheet_employee_array =
array(
"主治医" => 'PD',
"リハ担当医" => 'RD',
"PT" => 'PT',
"OT" => 'OT',
"ST" => 'ST',
"看護師" => 'NS',
"SW" => 'SW',
);

function __lib_u_everybody_plansheet_employee_refetch_emp_name($m)
{
	if ($m == '')
		return NULL;
	$stmt = '
SELECT "姓" || \' \' || "名" AS "N"
FROM "職員台帳"
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
    $column = $label."名";
  return array('Column' => $column,
	       'Label' => $label,
	       'Draw' => 'subpick',
	       '_NO_ICOL_' => 1,
	       '_SQL_SELECT_' => $tbl.'."姓" || \' \' || '.$tbl.'."名"',
		 '_SQL_EXTRA_' =>
		 array($tbl.'."ObjectID"' => $label),
		 'Subpick' => array
		 ('Class' => 'list_of_employees',
		  'Message' => $column,
		  'Config' => $_lib_u_manage_employee_cfg,
		  'ListID' => array('ObjectID', '姓名'),
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
			'Message' => '診断名を付ける',
			'Config' => $_lib_u_doctor_diseasepick_dps_cfg,
			'ListID' => array('病名表記', '病名表記'),
			'Allow_NULL' => 1,
			),
		       ),
		 
		  
	  
		 array('Column' => "ss7",
		       'Draw' => 'subpick',
		       'Subpick' => array
		       ('Class' => 'diseasepick',
			'Message' => '合併症名を付ける',
			'Config' => $_lib_u_doctor_diseasepick_dps_cfg,
			'ListID' => array('病名表記', '病名表記'),
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
		       'Label' => "自立度判定基準",
		       'Draw' => 'enum',
		       'Enum' => 
		       _lib_u_everybody_plansheet_ident_enum
		       ('I', 'IIa', 'IIb', 'IIIa', 'IIIb', 'IV',
			'V', 'VI', 'M'))
		   ));

  $flippage = array
    (
     '基本項目' => $basic,

     '心身構造・機能' =>
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

     '活動' =>
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

		 __lib_u_everybody_plansheet_ps_pc("食事"),
		 __lib_u_everybody_plansheet_ps_pc("整容"),
		 __lib_u_everybody_plansheet_ps_pc("清拭"),
		 __lib_u_everybody_plansheet_ps_pc("更衣・上半身"),
		 __lib_u_everybody_plansheet_ps_pc("更衣・下半身"),
		 __lib_u_everybody_plansheet_ps_pc("トイレ動作"),

		 array
		 (array('Column' => 'notes',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6)),
		  array('Column' => 'notes',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6))),

		 __lib_u_everybody_plansheet_ps_pc("排尿管理"),
		 __lib_u_everybody_plansheet_ps_pc("排泄管理", "排便管理"),

		 array
		 (array('Column' => '排泄・短期目標',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6)),
		  array('Column' => '排泄・アプローチ',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6))),

		 __lib_u_everybody_plansheet_ps_pc("ベッド・椅子・車椅子",
						   "車椅子移乗"),

		 array
		 (array('Column' => '車椅子移乗・短期目標',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6)),
		  array('Column' => '車椅子移乗・アプローチ',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6))),


		 __lib_u_everybody_plansheet_ps_pc("トイレ", "トイレ移乗"),
		 __lib_u_everybody_plansheet_ps_pc("浴槽シャワー",
						   "浴槽移乗"),

		 array
		 (array('Column' => '移乗・短期目標',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6)),
		  array('Column' => '移乗・アプローチ',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6))),

		 __lib_u_everybody_plansheet_ps_pc("車椅子"),
		 array
		 (array('Column' => '車椅子・短期目標',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6)),
		  array('Column' => '車椅子・アプローチ',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6))),

		 __lib_u_everybody_plansheet_ps_pc("歩行"),
		 __lib_u_everybody_plansheet_ps_pc("階段"),
		 array
		 (array('Column' => '移動・短期目標',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6)),
		  array('Column' => '移動・アプローチ',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6))),

		 __lib_u_everybody_plansheet_ps_pc("理解"),
		 __lib_u_everybody_plansheet_ps_pc("表出"),
		 array
		 (array('Column' => 'コミュニケーション・短期目標',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6)),
		  array('Column' => 'コミュニケーション・アプローチ',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6))),

		 __lib_u_everybody_plansheet_ps_pc("社会的交流"),
		 __lib_u_everybody_plansheet_ps_pc("問題解決"),
		 __lib_u_everybody_plansheet_ps_pc("記憶"),
		 array
		 (array('Column' => '社会的認知・短期目標',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6)),
		  array('Column' => '社会的認知・アプローチ',
			'Draw' => 'textarea',
			'Option' => array('cols' => 50, 'rows' => 6)),

		  array('Column' => '移動手段',
			'Draw' => 'enum',
			'Enum' => array('L' => '歩行', 'W' => '車椅子')),
		  )
	   ),

     '参加' =>
     array(array('Column' => "職業", 'Draw' => 'enum',
		 'Enum' => array('0' => '無職', '1' => '病欠中',
				 '2' => '休職中', '3' => '発症後退職',
				 '4' => '退職予定')),
	   array('Column' => "職種・業種・仕事内容",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "経済状況",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "社会参加（内容、頻度等）",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "余暇活動（内容、頻度等）",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "退院先", 'Draw' => 'enum',
		 'Enum' => array('0' => '自宅', '1' => '親族宅',
				 '2' => '医療機関', '3' => 'その他')),
	   array('Column' => "復職", 'Draw' => 'enum',
		 'Enum' => array('0' => '現場復帰', '1' => '転職',
				 '2' => '配置転換',
				 '3' => '復職不可', '4' => 'その他')),
	   array('Column' => "復職時期",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "仕事内容",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "通勤方法",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "家庭内役割",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "社会活動",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "趣味",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),

	   array('Column' => '参加・短期目標',
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array('Column' => '参加・アプローチ',
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6))

	   ),

     '心理' =>
     array(array('Column' => "抑鬱",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "障害の否認",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "その他心理",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),

	   array('Column' => '心理・短期目標',
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array('Column' => '心理・アプローチ',
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6))
	   ),

     '環境' =>
     array(array('Column' => "同居家族",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "親族関係",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "住居形態",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "階数",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "居室の種類",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "トイレ様式",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "住宅改修の可否",
		 'Draw' => 'enum',
		 'Enum' => array('0' => '不可', '1' => '可')),
	   array('Column' => "家周囲",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "交通",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),

	   array('Column' => "自宅改造", 'Draw' => 'enum',
		 'Enum' => array('0' => '不要', '1' => '要')),
	   array('Column' => "自宅改造内容",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "福祉機器", 'Draw' => 'enum',
		 'Enum' => array('0' => '不要', '1' => '要')),
	   array('Column' => "福祉機器内容", 'Draw' => 'enum',
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "社会保障サービス", 'Draw' => 'enum',
		 'Enum' => array('0' => '不要', '1' => '身障手帳',
				 '2' => '障害年金', '3' => 'その他')),
	   array('Column' => "社会保障サービス内容",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "介護保険サービス", 'Draw' => 'enum',
		 'Enum' => array('0' => '不要', '1' => '要')),
	   array('Column' => "介護保険サービス内容",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),

	   array('Column' => '環境・短期目標',
		 'Label' => '特記事項',
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array('Column' => '環境・アプローチ',
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6))
	   ),
     
     '第三者の不利' =>
     array(array('Column' => "発病による家族の変化",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "社会生活",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "健康上の問題の発生",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "心理的問題の発生",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "第三者の不利・評価・備考",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),

	   array('Column' => "退院後の主介護者", 'Draw' => 'enum',
		 'Enum' => array('0' => '不要', '1' => '要')),
	   array('Column' => "退院後の主介護者内容",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "家族構成の変化", 'Draw' => 'enum',
		 'Enum' => array('0' => '不要', '1' => '要')),
	   array('Column' => "家族構成の変化内容",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "家族内役割の変化", 'Draw' => 'enum',
		 'Enum' => array('0' => '不要', '1' => '要')),
	   array('Column' => "家族内役割の変化内容",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   array('Column' => "家族の社会活動変化", 'Draw' => 'enum',
		 'Enum' => array('0' => '不要', '1' => '要')),
	   array('Column' => "家族の社会活動変化内容",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),

	   array('Column' => '第三者の不利・短期目標',
		 'Label' => '備考',
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array('Column' => '第三者の不利・アプローチ',
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6))
	     ),

     '目標・確認' =>
     array(array('Column' => "1ヶ月後の目標",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array('Column' => "本人の希望",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array('Column' => "家族の希望",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array('Column' => "リハビリテーションの治療方針",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array('Column' => "外泊計画",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array('Column' => "退院時目標・見込時期",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array('Column' => "将来計画",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array('Column' => "将来または退院後の社会参加の見込み",
		 'Label' => "将来・退院後社会参加見込",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array('Column' => "本人/家族への説明",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array('Column' => "説明を受けた人",
		 'Draw' => 'enum',
		 'Enum' => array('0' => '本人', '1' => '家族',
				 '2' => 'その他')),
	   array('Column' => "説明者署名",
		 'Draw' => 'text',
		 'Option' => array('size' => 50,
				   'maxlength' => 40)),
	   ),

     'その他' =>
     array(array("Column" => "医師によるコメント",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array("Column" => "看護師によるコメント",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array("Column" => "PTによるコメント",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array("Column" => "OTによるコメント",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array("Column" => "STによるコメント",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array("Column" => "MSWによるコメント",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array("Column" => "栄養士によるコメント",
		 'Draw' => 'textarea',
		 'Option' => array('cols' => 50, 'rows' => 6)),
	   array("Column" => "統括メモ",
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
			  'Label' => '記録者名',
			  'Draw' => 'text');
  $select_join =
    ("\nFROM " . mx_db_sql_quote_name(otatest_order) . " AS PS\n" .
     "JOIN \"患者台帳\" AS P\n" .
     "ON P.\"ObjectID\" = PS.\"patient\" AND P.\"Superseded\" IS NULL\n" 
     );

  $lselect =
    ('SELECT PS."ObjectID", PS."CreatedBy", ' .
     '(E."姓" || E."名") AS "recorded_on", ' .
     implode(",\n", $lselcol) . $select_join);

  $select =
    ('SELECT PS."ObjectID", PS."CreatedBy", ' .
     '(E."姓" || E."名") AS "recorded_on", ' .
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
	$cog = array('理解' => 1,
		     '表出' => 1,
		     '社会的交流' => 1,
		     '問題解決' => 1,
		     '記憶' => 1);
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
	$physum -= min($data['車椅子_P'], $data['歩行_P']);
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
	  $m = $this->data[$col . '名'] = $name;
	  $o = __lib_u_everybody_plansheet_employee_refetch_emp_name($o);
	  $a = __lib_u_everybody_plansheet_employee_refetch_emp_name($a);
	  $b = __lib_u_everybody_plansheet_employee_refetch_emp_name($b);
  }

}

$_lib_u_everybody_plansheet_csv_cols = array(

"氏名",
"性別",
"生年月日",
"年齢",
"利き手",
"主治医名",
"リハ担当医名",
"PT名",
"OT名",
"ST名",
"看護師名",
"SW名",
"診断名",
"発症日",
"並存疾患・合併症",
"コントロール状態",
"リハビリテーション歴",
"日常生活自立度",
"痴呆性老人の日常生活自立度判定基準",
"意識障害",
"意識障害コメント",
"痴呆", // 見当識
"痴呆コメント",
"記憶障害", // 記銘力
"記憶障害コメント",
"運動障害",
"運動障害コメント",
"表在感覚障害",
"表在感覚障害コメント",
"深部感覚障害",
"深部感覚障害コメント",
"摂食機能障害",
"摂食機能障害コメント",
"排尿機能障害",
"排尿機能障害コメント",
"排便機能障害",
"排便機能障害コメント",
"呼吸循環器機能障害",
"呼吸循環器機能障害コメント",
"構音障害",
"構音障害コメント",
"関節可動域制限",
"関節可動域制限コメント",
"筋力低下",
"筋力低下コメント",
"褥創",
"褥創コメント",
"痛み",
"痛みコメント",
"半側視空間無視",
"半側視空間無視コメント",
"注意障害",
"注意障害コメント",
"構成障害",
"構成障害コメント",
"心身機能・その他",
"寝返り",
"起き上がり",
"座位",
"立ち上がり",
"立位",
"基本動作・短期目標",
"基本動作・アプローチ",
"活動度",
"活動度・短期目標",
"活動度・アプローチ",
"食事_P",
"食事_TP",
"食事_C",
"整容_P",
"整容_TP",
"整容_C",
"清拭_P",
"清拭_TP",
"清拭_C",
"更衣・上半身_P",
"更衣・上半身_TP",
"更衣・上半身_C",
"更衣・下半身_P",
"更衣・下半身_TP",
"更衣・下半身_C",
"トイレ動作_P",
"トイレ動作_TP",
"トイレ動作_C",
"セルフケア・短期目標",
"セルフケア・アプローチ",
"排尿管理_P",
"排尿管理_TP",
"排尿管理_C",
"排泄管理_P",
"排泄管理_TP",
"排泄管理_C",
"排泄・短期目標",
"排泄・アプローチ",
"ベッド・椅子・車椅子_P",
"ベッド・椅子・車椅子_TP",
"ベッド・椅子・車椅子_C",
"車椅子移乗・短期目標",
"車椅子移乗・アプローチ",
"トイレ_P",
"トイレ_TP",
"トイレ_C",
"浴槽シャワー_P",
"浴槽シャワー_TP",
"浴槽シャワー_C",
"移乗・短期目標",
"移乗・アプローチ",
"車椅子_P",
"車椅子_TP",
"車椅子_C",
"車椅子・短期目標",
"車椅子・アプローチ",
"歩行_P",
"歩行_TP",
"歩行_C",
"階段_P",
"階段_TP",
"階段_C",
"移動・短期目標",
"移動・アプローチ",
"理解_P",
"理解_TP",
"理解_C",
"表出_P",
"表出_TP",
"表出_C",
"コミュニケーション・短期目標",
"コミュニケーション・アプローチ",
"社会的交流_P",
"社会的交流_TP",
"社会的交流_C",
"問題解決_P",
"問題解決_TP",
"問題解決_C",
"記憶_P",
"記憶_TP",
"記憶_C",
"社会的認知・短期目標",
"社会的認知・アプローチ",
"移動手段",
"職業",
"職種・業種・仕事内容",
"経済状況",
"社会参加（内容、頻度等）",
"余暇活動（内容、頻度等）",
"退院先",
"復職",
"復職時期",
"仕事内容",
"通勤方法",
"家庭内役割",
"社会活動",
"趣味",
"参加・短期目標",
"参加・アプローチ",
"抑鬱",
"障害の否認",
"その他心理",
"心理・短期目標",
"心理・アプローチ",
"同居家族",
"親族関係",
"住居形態",
"階数",
"居室の種類",
"トイレ様式",
"住宅改修の可否",
"家周囲",
"交通",
"自宅改造",
"自宅改造内容",
"福祉機器",
"福祉機器内容",
"社会保障サービス",
"社会保障サービス内容",
"介護保険サービス",
"介護保険サービス内容",
"環境・短期目標",
"環境・アプローチ",
"発病による家族の変化",
"社会生活",
"健康上の問題の発生",
"心理的問題の発生",
"第三者の不利・評価・備考",
"退院後の主介護者",
"退院後の主介護者内容",
"家族構成の変化",
"家族構成の変化内容",
"家族内役割の変化",
"家族内役割の変化内容",
"家族の社会活動変化",
"家族の社会活動変化内容",
"第三者の不利・備考",
"第三者の不利・短期目標",
"第三者の不利・アプローチ",
"1ヶ月後の目標",
"本人の希望",
"家族の希望",
"リハビリテーションの治療方針",
"外泊計画",
"退院時目標・見込時期",
"将来計画",
"将来または退院後の社会参加の見込み",
"医師によるコメント",
"看護師によるコメント",
"PTによるコメント",
"OTによるコメント",
"STによるコメント",
"MSWによるコメント",
"栄養士によるコメント",
"統括メモ",
"説明者署名",
"本人/家族への説明",
"説明を受けた人",
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

    print '<a title="CSV出力"';
    print 'href="' . $program . '/ps.txt?CSV_HACK=1&amp;';
    print $param;
    print '">';
    print mx_img_url('csv.png');
    print '</a>';

    print '&nbsp;&nbsp;';

    print '<a target="_blank" title="印刷"';
    print 'href="' . $program . '/ps.txt?CSV_HACK=2&amp;';
    print $param;
    print '">';
    print mx_img_url('printer.png');
    print '</a>';

  }

}
?>
