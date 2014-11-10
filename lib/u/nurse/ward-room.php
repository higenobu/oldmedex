<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

$_lib_u_nurse_ward_room_cfg = array
(
 'COLS' => array('�¼�̾', '����̾', '����', '���'),
 'TABLE' => '�¼�����ɽ',
 'LIST_IDS' => array('ObjectID', '�¼�̾'),
 'LCOLS' => array('�¼�̾', '����̾',
		  array('Column' => '����',
			'Draw' => 'enum',
			'Enum' => array('M' => '��', 'F' => '��',
					NULL => '������')),
		  '���'),
 'LCHOICE' => array(0 => '�����¼���������', 1 => '���¼���������'),
 'ALLOW_SORT' => 1,
 'UNIQ_ID' => 'R."ObjectID"',
 'ENABLE_QBE' => array('�¼�̾', '����̾', '���',
		       array('Column' => '����',
			     'Draw' => 'enum',
			     'Enum' => array('M' => '��', 'F' => '��',
					     NULL => '������'),
			     )),
 );

class list_of_ward_rooms extends list_of_simple_objects {
  var $base_select_stmt = '
SELECT R."ObjectID", R."�¼�̾", R."����", W."����̾", R."����", R."���"
FROM "�¼�����ɽ" AS R
JOIN "�������ɽ" AS W
ON R."����" = W."ObjectID" AND W."Superseded" IS NULL
WHERE (NULL IS NULL)';

  function list_of_ward_rooms($prefix, $config=NULL) {
    global $_lib_u_nurse_ward_room_cfg;
    if (is_null($config))
      $config = $_lib_u_nurse_ward_room_cfg;
    $this->ward = $config['Ward'];
    list_of_simple_objects::list_of_simple_objects
      ($prefix, $config);
  }

  function base_fetch_stmt_1($ix) {
    if ($ix == 1)
      return $this->base_select_stmt;
    return ($this->base_select_stmt . ' AND  R."����" = ' .
	    mx_db_sql_quote($this->ward));
  }

  function draw() {
    mx_titlespan($this->Title);
    list_of_simple_objects::draw();
    mx_formi_submit($this->prefix . 'id-select', $this->Original,
		    "<span class=\"link\">�ѹ����ʤ�</span>");
  }

}
?>
