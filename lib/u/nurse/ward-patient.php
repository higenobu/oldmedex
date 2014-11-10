<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

$_lib_u_nurse_ward_patient_cfg = array
(
 'COLS' => array('����ID', '����̾', '����ǯ��', '�¼�̾'),
 'TABLE' => '������Ģ',
 'LIST_IDS' => array('ObjectID',
		     '����ID', '����̾', '����ǯ��', '�¼�', '�¼�̾'),
 'LCOLS' => array('�¼�̾', '����ID', '����̾', '����ǯ��'),
 'LCHOICE' => array(0 => '���ﴵ�Ԥ�������',1 => '�����Ԥ�������'),
 'ALLOW_SORT' => array('�¼�̾' => array('�¼�̾' => 'R."�¼�̾"'),
		       '����ID' => array('����ID' => 'P."����ID"'),
		       '����̾' => array('����̾' => 
					 '(P."��" || \' \' || P."̾") '),
		       '����ǯ��' => array
		       ('����ǯ��' =>
			'(extract(year from age(now(), P."��ǯ����")))')),
 'UNIQ_ID' => 'P."ObjectID"',
 'ENABLE_QBE' => array(array('Column' => '����ID',
			     'Singleton' => 1,
			     'CompareMethod' => 'zeropad_exact',
			     'ZeroPad' => $_mx_patient_id_zeropad,
			     ),
		       array('Column' => '����̾',
			     'Compare' => '("��"||"̾")'),
		       ),
 );

class list_of_ward_patients extends list_of_simple_objects {
  var $base_select_stmt = '
SELECT P."ObjectID", (P."��" || \' \' || P."̾") AS "����̾", P."����ID",
(extract(year from age(now(), P."��ǯ����"))) AS "����ǯ��",
RP."�¼�", R."�¼�̾", R."����", W."����̾"
FROM "������Ģ" AS P
LEFT JOIN ("�¼����ԥǡ���" AS RPD
           JOIN "�¼�����ɽ" AS RP
           ON RPD."�¼�����ɽ" = RP."ObjectID" AND RP."Superseded" IS NULL
           JOIN "�¼�����ɽ" AS R
           ON RP."�¼�" = R."ObjectID" AND R."Superseded" IS NULL
	   JOIN "�������ɽ" AS W
           ON R."����" = W."ObjectID" AND W."Superseded" IS NULL )
ON RPD."����" = P."ObjectID" AND P."Superseded" IS NULL
WHERE (NULL IS NULL)';

  function list_of_ward_patients($prefix, $config=NULL) {
    global $_lib_u_nurse_ward_patient_cfg;
    if (is_null($config))
      $config = $_lib_u_nurse_ward_patient_cfg;
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
