<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

$_lib_u_nurse_fim__fim_enum = array
(
 '' => '(̤ɾ��)',
 '7' => '7: ������Ω',
 '6' => '6: ������Ω',
 '5' => '5: �ƻ롦����',
 '4' => '4: �Ǿ����',
 '3' => '3: �����ٲ��',
 '2' => '2: ������',
 '1' => '1: �����',
 );

function _lib_u_nurse_fim__fim_pair($name) {
  global $_lib_u_nurse_fim__fim_enum;
  return array
    (
     array('Column' => $name . '_P', 'Label' => $name, 'Draw' => 'enum',
	   'Enum' => &$_lib_u_nurse_fim__fim_enum),
     array('Column' => $name . '_C', 'Label' => NULL, 'Draw' => 'textarea'),
     );
}

function __lib_u_nurse_fim_cfg(&$cfg) { 

  $cfg = array_merge
    ($cfg, array
     ('TABLE' => 'FIMɾ��ɽ',
      // 'ALLOW_SORT' => 1,
      'DEFAULT_SORT' => '����',
      'LCOLS' => array('����',
		       '��Ͽ��̾',
		       '��ư���ܹ����',
		       'ǧ�ι��ܹ����',
		       '�����'),
      'LLAYO' => array
      ('2', '����', '1', '��Ͽ��̾', '//',
       array('Column' => '��ư���ܹ����', 'Label' => '��ư����'),
       array('Column' => 'ǧ�ι��ܹ����', 'Label' => 'ǧ�ι���'),
       '�����'),

      'UNIQ_ID' => 'F."ObjectID"',
      ));

  // List of flip-pages COLS elements.
  $flippage = array
    (
     '��ư����(1)' =>
     array_merge
     (
      _lib_u_nurse_fim__fim_pair('����'),
      _lib_u_nurse_fim__fim_pair('����'),
      _lib_u_nurse_fim__fim_pair('����'),
      _lib_u_nurse_fim__fim_pair('���ᡦ��Ⱦ��'),
      _lib_u_nurse_fim__fim_pair('���ᡦ��Ⱦ��'),
      _lib_u_nurse_fim__fim_pair('�ȥ���ư��')
      ),

     '��ư����(2)' =>
     array_merge
     (
      _lib_u_nurse_fim__fim_pair('��Ǣ����'),
      _lib_u_nurse_fim__fim_pair('��������'),
      _lib_u_nurse_fim__fim_pair('�٥åɡ��ػҡ��ְػ�'),
      _lib_u_nurse_fim__fim_pair('�ȥ���'),
      _lib_u_nurse_fim__fim_pair('���奷��'),
      _lib_u_nurse_fim__fim_pair('�ְػ�'),
      _lib_u_nurse_fim__fim_pair('���'),
      _lib_u_nurse_fim__fim_pair('����'),
      array
      (
       array('Column' => '��ư����',
	     'Draw' => 'enum',
	     'Enum' => array('L' => '���', 'W' => '�ְػ�')),
       )),

     'ǧ�ι���' =>
     array_merge
     (
      _lib_u_nurse_fim__fim_pair('����'),
      _lib_u_nurse_fim__fim_pair('ɽ��'),
      _lib_u_nurse_fim__fim_pair('�Ҳ�Ū��ή'),
      _lib_u_nurse_fim__fim_pair('������'),
      _lib_u_nurse_fim__fim_pair('����')
      ),

     );

  $pmco = array();
  foreach (array('��ư����(1)', '��ư����(2)') as $page) {
    foreach ($flippage[$page] as $desc)
      if (substr($desc['Column'], -2) == '_P')
	$pmco[] = 'COALESCE(F.' . mx_db_sql_quote_name($desc['Column']) .
	  ', 0)';
  }

  // Yuck.
  $m1 = ('COALESCE(F.' . mx_db_sql_quote_name('�ְػ�_P') . ', 0)');
  $m2 = ('COALESCE(F.' . mx_db_sql_quote_name('���_P') . ', 0)');
  $pmco[] = "(- (CASE WHEN ($m1 < $m2) THEN $m1 ELSE $m2 END))";

  $pmst = '(' . implode(" +\n  ", $pmco) . ') AS "��ư���ܹ����"';

  $pcco = array();
  foreach (array('ǧ�ι���') as $page) {
    foreach ($flippage[$page] as $desc)
      if (substr($desc['Column'], -2) == '_P')
	$pcco[] = 'COALESCE(F.' . mx_db_sql_quote_name($desc['Column']) .
	  ', 0)';
  }
  $pcst = '(' . implode(" +\n  ", $pcco) . ') AS "ǧ�ι��ܹ����"';

  $pbst = '(' . implode(" +\n  ", array_merge($pmco, $pcco)) . ') AS "�����"';

  $cfg['ALLOW_SORT'] = array
    (
     '����' => array('����' => '"����"'),
     '��Ͽ��̾' => array('��Ͽ��̾' => '"��Ͽ��̾"'),
     '��ư���ܹ����' => array('��ư���ܹ����' => '(' .
		       implode(" +\n  ", $pmco) . ')'),
     'ǧ�ι��ܹ����' => array('ǧ�ι��ܹ����' => '(' .
		       implode(" +\n  ", $pcco) . ')'),
     '�����' => array('�����' => '(' .
		       implode(" +\n  ", array_merge($pmco, $pcco)) . ')'),
     );

  $stmt_head = '
SELECT F.*, (E."��" || E."̾") AS "��Ͽ��̾",' . "
$pmst,
$pcst,
$pbst" . '
FROM "FIMɾ��ɽ" AS F
LEFT JOIN "������Ģ" AS E
ON E."userid" = F."CreatedBy" AND E."Superseded" IS NULL
';

  $cfg['HSTMT'] = $stmt_head . 'WHERE NULL IS NULL';
  $cfg['STMT'] = $stmt_head . 'WHERE F."Superseded" IS NULL';

  $cfg['ECOLS'] = array(array('Column' => '����',
			      'Option' =>  array('ime' => 'disabled',
						 'validate' => 'date'),
			array('Column' => 'ɾ����', 'Draw' => NULL),
			array('Column' => '����', 'Draw' => NULL)));
  $cfg['DCOLS'] = array('����');
  $cfg['ICOLS'] = array('����', '����', 'ɾ����');
  $cfg['DPAGES'] = array_keys($flippage);
  $cfg['EPAGES'] = $cfg['DPAGES'];
  $page_num = -1;
  foreach ($flippage as $page_name => $page_desc) {
    $page_num++;
    foreach ($page_desc as $a) {
      $a['Page'] = $page_num;
      $cfg['ECOLS'][] = $a;
      $cfg['DCOLS'][] = $a;
      $cfg['ICOLS'][] = $a['Column'];
    }
  }
  $cfg['DCOLS'][] = '��Ͽ��̾';
}

class list_of_nurse_fims extends list_of_ppa_objects {

  var $side = 'T'; // or 'N'
  var $default_row_per_page = 4;

  function list_of_nurse_fims($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_fim_cfg(&$cfg);
    list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
  }
  
  function base_fetch_stmt_0() {
    return (list_of_ppa_objects::base_fetch_stmt_0() .
	    ' AND "ɾ����" = ' . mx_db_sql_quote($this->side));
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

class nurse_fim_display extends simple_object_display {

  function nurse_fim_display($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_fim_cfg(&$cfg);
    simple_object_display::simple_object_display
      ($prefix, $cfg);
  }

}

class nurse_fim_edit extends simple_object_edit {

  var $default_threeway_ok = 1;

  function nurse_fim_edit($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_fim_cfg(&$cfg);
    simple_object_edit::simple_object_edit
      ($prefix, &$cfg);
  }

  function anew_tweak($orig_id) {
    $this->data['����'] = mx_today_string();
  }

  function annotate_row_data(&$d) {
    $d['����'] = $this->so_config['Patient_ObjectID'];
    $d['ɾ����'] = $this->side; // relies on fim-application
    $this->dbglog("ARD: ");
    $this->dbglog(mx_var_dump($d));
    $this->dbglog(mx_var_dump($this->so_config));
  }

  function annotate_form_data(&$d) {
    simple_object_edit::annotate_form_data($d);
    $this->annotate_row_data($d);
  }

  function _validate() {

    $bad = 0;
    foreach ($this->so_config['ICOLS'] as $col) {
      if (strstr($col, "_P")) {
        if ($this->data[$col] == "")
	  $this->data[$col] = NULL;
	else if ($st = mx_db_validate_range($this->data[$col], 1, 7)) {
	  $len = strlen($col);
	  $col = substr($col, 0, $len - 2);
	  $this->err("($col): $st\n");
	  $bad = 1;
	}
      }
    }

    if ($st = mx_db_validate_date($this->data['����'])) {
      $this->err("(����): $st\n");
      $bad++;
    }

    if ($bad == 0)
      return 'ok';
  }

}

?>
