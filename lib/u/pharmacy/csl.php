<?php // -*- mode: php; coding: euc-japan -*-

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

function _lib_u_pharmacy_csl_config() {
  $_lib_u_pharmacy_csl_locs_llayo = array
    (2, "��̾��", "�쥻�ץ��Ż����������ƥ������̾", '//',
     1, "��¤���", "������",
     "����ñ��", "��������");

  $_lib_u_pharmacy_csl_locs_cols = array();
  foreach ($_lib_u_pharmacy_csl_locs_llayo as $elt) {
    if (! _lib_so__is_table_control($elt))
      $_lib_u_pharmacy_csl_locs_cols[] = $elt;
  }

  $_lib_u_pharmacy_csl_base_stmt =
    ('SELECT C."ObjectID", ' .
     // 'C."����", C."����", C."͢���ѷ��", C."������ʪͳ������", ' .
     '"��̾��", "�쥻�ץ��Ż����������ƥ������̾", "��¤���", "������",
     ("����ñ�̿�" || "����ñ��ñ��") AS "����ñ��",
     ("�������̿�" || "��������ñ��") AS "��������"
     FROM "Medis�����ʥޥ�����" AS D
     JOIN "�������ʥޥ�����" AS C
     ON D."ObjectID" = C."ObjectID" WHERE (NULL IS NULL)');

  $_lib_u_pharmacy_csl_locs_cfg = array
    (
     'TABLE' => '�������ʥޥ�����',
     'STMT' => $_lib_u_pharmacy_csl_base_stmt . ' AND "Superseded" IS NULL',
     'COLS' => array('unused'),
     'LCOLS' => $_lib_u_pharmacy_csl_locs_cols,
     'LLAYO' =>   $_lib_u_pharmacy_csl_locs_llayo,
     'ENABLE_QBE' => array
     ("��̾��", "�쥻�ץ��Ż����������ƥ������̾",
      array('Column' => "��������",
	    'Compare' => '"��������"',
	    'Draw' => 'enum',
	    'Enum' => array('F' => '�ѽ�ʬ�Τ�',
			    'YF' => '����ʬ�Τ�',
			    '' => '�ޥ�������'),
	    'CompareMethod' => 'enum_single_char',
	    'Singleton' => 1),
      ),
     'DEFAULT_QBE' => array(array('��������', 'F')),
     'LIST_IDS' => array('ObjectID', "��̾��", "�쥻�ץ��Ż����������ƥ������̾"),
     'UNIQ_ID' => 'C."ObjectID"',
     'ALLOW_SORT' => array
     ('��̾��' => array('��̾��' => '"��̾��"'),
      '�쥻�ץ��Ż����������ƥ������̾' => array('�쥻�ץ��Ż����������ƥ������̾' => '"�쥻�ץ��Ż����������ƥ������̾"'),
      '��¤���' => array('��¤���' => '"��¤���"'),
      '������' => array('������' => '"������"') ),
     );
  return $_lib_u_pharmacy_csl_locs_cfg;
}

class list_of_controlled_substances extends list_of_simple_objects {

  function list_of_controlled_substances($prefix, $config=NULL) {
    if (is_null($config))
      $config = _lib_u_pharmacy_csl_config();
    list_of_simple_objects::list_of_simple_objects
      ($prefix, $config);
  }

  function base_fetch_stmt_0() {
    $bfs = list_of_simple_objects::base_fetch_stmt_0();
    if ('' != ($lmt = $this->so_config['LimitTo']))
      $bfs = $bfs . ' AND C.' . mx_db_sql_quote_name($lmt) . " = 'Y'";
    return $bfs;
  }

}

class list_of_blood_or_bio extends list_of_controlled_substances {
  function list_of_blood_or_bio($prefix, $config=NULL) {
    list_of_controlled_substances::list_of_controlled_substances
      ($prefix, $config);
  }
  function base_fetch_stmt_0() {
    $bfs = list_of_controlled_substances::base_fetch_stmt_0();
    if ('' != ($lmt = $this->so_config['LimitTo']))
      $bfs = $bfs . ' AND C.' . mx_db_sql_quote_name($lmt) . " = 'Y'";
    return ($bfs . ' AND (C."͢���ѷ��" = \'Y\' OR ' .
	    'C."������ʪͳ������" = \'Y\')');
  }
}

class list_of_narcotic_or_poison extends list_of_controlled_substances {
  function list_of_narcotic_or_poison($prefix, $config=NULL) {
    list_of_controlled_substances::list_of_controlled_substances
      ($prefix, $config);
  }
  function base_fetch_stmt_0() {
    $bfs = list_of_controlled_substances::base_fetch_stmt_0();
    if ('' != ($lmt = $this->so_config['LimitTo']))
      $bfs = $bfs . ' AND C.' . mx_db_sql_quote_name($lmt) . " = 'Y'";
    return ($bfs . ' AND (C."����" = \'Y\' OR ' .
	    'C."����" = \'Y\')');
  }
}

?>

