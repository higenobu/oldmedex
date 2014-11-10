<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

$_lib_u_nurse_independence_cfg = array
(
 'TABLE' => '�������輫Ω�ٴ���ɽ',
 'Patient_ID' => NULL,
 'Patient_ObjectID' => NULL,
 'Patient_Name' => NULL,
 'DEFAULT_SORT' => '����',
 'LCOLS' => array('����', '��Ͽ��̾', '�������輫Ω��'),
 'ICOLS' => array('����', '�������輫Ω��', '�и��ݼ�', '������Ω',
		  '����', '����',
		  '������Ϸ�ͤ��������輫Ω��Ƚ����',
		  '������û����ɸ', '���������ץ���'),
 'ALLOW_SORT' => array('����' => array('����' => '"����"'),
		       '��Ͽ��̾' => array
		       ('��Ͽ��̾' => '(E."��" || E."̾")'),
		       '�������輫Ω��' => array
		       ('�������輫Ω��' => '"�������輫Ω��"') ),

 'UNIQ_ID' => 'I."ObjectID"',
 );

$_lib_u_nurse_independence_bfd_prev = array
(1 => '����Ω�̶���',
 2 => 'Υ���������ȥ���ͶƳ',
 3 => '����ͽ�ɡ�����ͽ��',
 4 => '����ͽ�ɡ����϶���');

$_lib_u_nurse_independence_bfd_plan = array
(1 => '�ְػҼ�������Դ���',
 2 => '�٥åɥ����ɵ�Ω���ܾ���',
 3 => '�������ࡦ���϶���',
 4 => '����ROM�������ΰ��Ѵ�');

$_lib_u_nurse_independence_stmt_head = '';

function _lib_u_nurse_independence__init() {
  global $_lib_u_nurse_independence_cfg,
         $_lib_u_nurse_independence_stmt_head,
         $_lib_u_nurse_independence_bfd_prev,
         $_lib_u_nurse_independence_bfd_plan;

  $_lib_u_nurse_independence_stmt_head = '
SELECT I."ObjectID", I."Superseded", I."CreatedBy",
I."����", I."����", I."�������輫Ω��", I."�и��ݼ�", I."������Ω", I."����",
I."������Ϸ�ͤ��������輫Ω��Ƚ����",
I."������û����ɸ", I."���������ץ���",
(E."��" || E."̾") AS "��Ͽ��̾"';
  foreach ($_lib_u_nurse_independence_bfd_prev as $ix => $val)
    $_lib_u_nurse_independence_stmt_head .= sprintf(', I."ͽ��_%02d"', $ix);
  foreach ($_lib_u_nurse_independence_bfd_plan as $ix => $val)
    $_lib_u_nurse_independence_stmt_head .= sprintf(', I."�ײ�_%02d"', $ix);
  $_lib_u_nurse_independence_stmt_head .= '
FROM "�������輫Ω�ٴ���ɽ" AS I
LEFT JOIN "������Ģ" AS E
ON E."userid" = I."CreatedBy" AND E."Superseded" IS NULL
';

  $_lib_u_nurse_independence_cfg['HSTMT'] =
      $_lib_u_nurse_independence_stmt_head . ' WHERE NULL IS NULL';
  $_lib_u_nurse_independence_cfg['STMT'] =
      $_lib_u_nurse_independence_stmt_head . ' WHERE I."Superseded" IS NULL';
  $ecol = array(array('Column' => '�������輫Ω��',
		      'Draw' => 'enum',
		      'Enum' => array('-' => '-',
				      'J' => 'J',
				      'A1' => 'A1', 'A2' => 'A2',
				      'B1' => 'B1', 'C2' => 'B2',
				      'C1' => 'C1', 'C2' => 'C2') ),
		array('Column' => '�и��ݼ�', 'Draw' => 'check'),
		array('Column' => '������Ω', 'Draw' => 'check'),
		array('Column' => '����', 'Draw' => NULL),
		);
  foreach ($_lib_u_nurse_independence_bfd_prev as $bit => $name) {
    $cn = sprintf('ͽ��_%02d', $bit);
    $ecol[] = array('Label' => $name,
		    'Column' => $cn,
		    'Draw' => 'check');
    $_lib_u_nurse_independence_cfg['ICOLS'][] = $cn;
  }

  foreach ($_lib_u_nurse_independence_bfd_plan as $bit => $name) {
    $cn = sprintf('�ײ�_%02d', $bit);
    $ecol[] = array('Label' => $name,
		    'Column' => $cn,
		    'Draw' => 'check');
    $_lib_u_nurse_independence_cfg['ICOLS'][] = $cn;
  }

  $ecol[] = array('Column' => '����', 'Draw' => 'check');
  $ecol[] = array('Column' => "������Ϸ�ͤ��������輫Ω��Ƚ����",
		  'Draw' => 'enum',
		  'Enum' => array('-' => '-',
				  "I" => "I",
				  "IIa" => "IIa",
				  "IIb" => "IIb",
				  "IIIa" => "IIIa",
				  "IIIb" => "IIIb",
				  "IV" => "IV",
				  "V" => "V",
				  "VI" => "VI",
				  "M" => "M"));
  $ecol[] = array('Column' => '������û����ɸ', 'Draw' => 'textarea');
  $ecol[] = array('Column' => '���������ץ���', 'Draw' => 'textarea');

  $dcol = array_merge(array('����', '��Ͽ��̾'), $ecol);
  $ecol = array_merge(array(array('Column' => '����',
				  'Option' => array('ime' => 'disabled',
						'validate' => 'date'))),
		      $ecol);
  $_lib_u_nurse_independence_cfg['DCOLS'] = $dcol;
  $_lib_u_nurse_independence_cfg['ECOLS'] = $ecol;
}
_lib_u_nurse_independence__init();

function _lib_u_nurse_independence_annotate(&$it, &$row) {
  $row['����'] = $it->so_config['Patient_ObjectID'];
}

class list_of_independence_evaluations extends list_of_ppa_objects {

  var $default_row_per_page = 4;

  function list_of_independence_evaluations($prefix, $config=NULL) {
    global $_lib_u_nurse_independence_cfg;

    if (is_null($config)) $config = $_lib_u_nurse_independence_cfg;
    list_of_ppa_objects::list_of_ppa_objects($prefix, $config);

  }

  function row_paging_orders() {
    $paging_keys = $this->row_paging_keys();
    $paging_orders = array();
    foreach ($paging_keys as $col) {
      $paging_orders[] = (($col == '����') ? 1 : 0);
    }
    return $paging_orders;
  }

}

function _lib_u_nurse_independence_fetch(&$it, &$db, $id) {
  global $_lib_u_nurse_independence_stmt_head;
  $stmt = ($_lib_u_nurse_independence_stmt_head .
	   'WHERE I."ObjectID" = ' . mx_db_sql_quote($id));
  $it->dbglog($stmt);
  return mx_db_fetch_single($db, $stmt);
}

class independence_evaluation_display extends simple_object_display {

  function independence_evaluation_display($prefix, $config=NULL) {
    global $_lib_u_nurse_independence_cfg;

    if (is_null($config)) $config = $_lib_u_nurse_independence_cfg;
    simple_object_display::simple_object_display($prefix, $config);

  }

  function fetch_data($id) {
    $db = mx_db_connect();
    return _lib_u_nurse_independence_fetch(&$this, &$db, $id);
  }

  function annotate_row_data(&$row) {
    if ($row['����'] != $this->so_config['Patient_ObjectID'])
      die("3girID");
  }

}

class independence_evaluation_edit extends simple_object_edit {

  var $debug = 0;

  function independence_evaluation_edit($prefix, $config=NULL) {
    global $_lib_u_nurse_independence_cfg;
    if (is_null($config)) $config = $_lib_u_nurse_independence_cfg;
    simple_object_edit::simple_object_edit($prefix, $config);
  }

  function fetch_data($id) {
    $db = mx_db_connect();
    return _lib_u_nurse_independence_fetch(&$this, &$db, $id);
  }

  function anew_tweak($orig_id) {
    $this->data['����'] = mx_today_string();
    $this->annotate_row_data(&$this->data);
  }

  function annotate_row_data(&$row) {
    // This one is needed---we may be creating anew.
    $row['����'] = $this->so_config['Patient_ObjectID'];
  }

  function _validate() {

    $bad = 0;
    if ($st = mx_db_validate_date($this->data['����'])) {
      $this->err("(����): $st\n");
      $bad++;
    }

    if (! $bad)
      return 'ok';

  }

}

?>
