<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';

function __lib_u_manage_rx_set(&$cfg) {
  $cfg = array_merge
    (array(
	   'TABLE' => '���޽����',
	   'COLS' => array('setflag', 'setcomment'),
	   'LCOLS' => array(array('Column' => 'setcomment',
				  'Label' => '���å�̾'
				  ),
			    array('Column' => 'DUMMY',
				  'Label' => '���',
				  'Draw' => 'delete',
				  ),
			    ),
	   'NOLINK' => 1,
	   ),
     $cfg);
}

class list_of_rx_sets extends list_of_simple_objects {
  function list_of_rx_sets($prefix, $cfg=NULL) {
    if (is_null($cfg)) {
      $cfg = array();
      __lib_u_manage_rx_set(&$cfg);
    }
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
    foreach($_REQUEST as $k=>$v) {
      $m = array();
      if (preg_match("/^delete-set-(\d+)$/", $k, &$m)) {
	$oid = $m[1];
	$stmt = <<<SQL
UPDATE "���޽����"
SET setflag=NULL
WHERE "ObjectID"=$oid
SQL;
	$db = mx_db_connect();
	if(!pg_query($db, $stmt)) {
	  print "����Ǥ��ޤ���Ǥ���";
	}	  
      }
    }
  }

  function base_fetch_stmt_0() {
    return list_of_simple_objects::base_fetch_stmt_0() . ' AND "Superseded" IS NULL AND setflag=1';
  }

  function dx_delete($desc, $value, $row) {
    $v = $row['ObjectID'];
    $s = $row['setcomment'];
  print <<<HTML
    <input type="SUBMIT" value="���" name="delete-set-$v"
    onClick="return confirm('${s}�������ޤ���������Ǥ�����');">
HTML;
  }
}

class rx_set_application extends single_table_application {
  function allow_new() {
    return 0;
  }
  function list_of_objects($prefix) {
    return new list_of_rx_sets($prefix);
  }
}
