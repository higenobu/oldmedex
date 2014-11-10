<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

function __lib_u_nurse_assessment_cfg(&$cfg) { 

  $cfg = array_merge
    ($cfg, array
     ('TABLE' => '���ԥ�����ɽ',
      'ALLOW_SORT' => 1,
      'DEFAULT_SORT' => '����',
      'LCOLS' => array('����', '��Ͽ��̾'),

      'UNIQ_ID' => 'F."ObjectID"',
      ));

  $stmt_head = '
SELECT F.*, (E."��" || E."̾") AS "��Ͽ��̾"
FROM "���ԥ�����ɽ" AS F
LEFT JOIN "������Ģ" AS E
ON E."userid" = F."CreatedBy" AND E."Superseded" IS NULL
';

  $cfg['HSTMT'] = $stmt_head . 'WHERE NULL IS NULL';
  $cfg['STMT'] = $stmt_head . 'WHERE F."Superseded" IS NULL';

  // List of flip-pages: db column, label, widget type
  $flippage = array
    ('����/����' => array
     (
      array("���������������������ˡ",
	    "���������������ˡ", "text"),
      array("���������٤륹�ԡ���",
	    "���٤륹�ԡ���", "text"),
      array("����������ο������פ������",
	    "����ο������פ������", "text"),
      array("���������������ʤ�",
	    "���������ʤ�", "text"),
      array("��������Ǣ������",
	    "��Ǣ������", "text"),
      array("����������Ĥ������������",
	    "����Ĥ������������", "text"),
      array("�����������Υѥ�����",
	    "�����Υѥ�����", "text"),
      ),

     '��̲����©/ư���ư' => array
     (
      array("��̲����©����̲�Υѥ�����",
	    "��̲�Υѥ�����", "text"),
      array("��̲����©�����ӥ�",
	    "���ӥ�", "text"),
      array("ư���ư������ѡ��ְػ�",
	    "����ѡ��ְػ�", "text"),
      array("ư���ư�������ư",
	    "�����ư", "text"),
      array("ư���ư����Ω���ɬ��",
	    "��Ω���ɬ��", "text"),
      ),

     '����/����/����ξ���' => array
     (
      array("����������",
	    "����", "text"),
      array("���顽���ᡦ�����",
	    "���ᡦ�����", "text"),
      array("���顽����",
	    "����", "text"),
      array("���顽����",
	    "����", "text"),
      array("����ξ��֡�����ξ���",
	    "����ξ���", "text"),
      array("����ξ��֡�����������δ���",
	    "����������δ���", "text"),
      ),

     '�Ķ�/��������' => array
     (
      array("�Ķ������ѥ�������",
	    "���ѥ�������", "text"),
      array("�Ķ����٥åɺ���������",
	    "�٥åɺ���������", "text"),
      array("�Ķ����٥åɼ��Ϥ����֤�����",
	    "�٥åɼ��Ϥ����֤�����", "text"),
      array("�Ķ�����Ƭ����֤�ʪ",
	    "��Ƭ����֤�ʪ", "text"),
      array("������������������ˡ",
	    "��������ˡ", "text"),
      array("���������������λ���",
	    "�����λ���", "text"),
      ),

     '��²�ط�/���ߥ�˥��������' => array
     (
      array("��²�ط��������ѡ�����",
	    "�����ѡ�����", "text"),
      array("��²�ط����̲���֡����",
	    "�̲���֡����", "text"),
      array("���ߥ塽���á�ɮ��",
	    "���á�ɮ��", "text"),
      array("���ߥ塽ʸ�����礭��",
	    "ʸ�����礭��", "text"),
      ),

     '����¾' => array
     (
      array("�ڤ��ߡ��򤷤����ʾ���",
	    "�򤷤����ʾ���", "text"),
      array("�ڤ��ߡ��褯�ä�����",
	    "�褯�ä�����", "text"),
      array("��������ħ���μ�Ū�ʤ���",
	    "�μ�Ū�ʤ���", "text"),
      array("��������ħ������Ū�ʤ���",
	    "����Ū�ʤ���", "text"),
      array("����¾�����ƤϤޤ�ʤ�����",
	    "���ƤϤޤ�ʤ�����", "text"),
      ),
     );

  $cfg['ECOLS'] = array(array('Column' => '����',
			      'Option' => array('ime' => 'disabled',
						'validate' => 'date')));
  $cfg['DCOLS'] = array('����');
  $cfg['ICOLS'] = array('����', '����');
  $cfg['DPAGES'] = array_keys($flippage);
  $cfg['EPAGES'] = $cfg['DPAGES'];
  $cfg['DPAGE_BREAKS'] = 
    $cfg['EPAGE_BREAKS'] = array(2);
  $page_num = -1;
  foreach ($flippage as $page_name => $page_desc) {
    $page_num++;
    foreach ($page_desc as $c) {
      $a = array('Page' => $page_num,
		 'Column' => $c[0],
		 'Label' => $c[1],
		 'Draw' => $c[2]);
      $cfg['ECOLS'][] = $a;
      $cfg['DCOLS'][] = $a;
      $cfg['ICOLS'][] = $c[0];
    }
  }
  $cfg['DCOLS'][] = '��Ͽ��̾';
}

class list_of_nurse_assessments extends list_of_ppa_objects {

  function list_of_nurse_assessments($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_assessment_cfg(&$cfg);
    list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
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

class nurse_assessment_display extends simple_object_display {

  function nurse_assessment_display($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_assessment_cfg(&$cfg);
    simple_object_display::simple_object_display
      ($prefix, $cfg);
  }

}

class nurse_assessment_edit extends simple_object_edit {

  function nurse_assessment_edit($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_assessment_cfg(&$cfg);
    simple_object_edit::simple_object_edit
      ($prefix, &$cfg);
  }

  function anew_tweak($orig_id) {
    $this->data['����'] = mx_today_string();
  }

  function annotate_row_data(&$d) {
    $d['����'] = $this->so_config['Patient_ObjectID'];
  }

  function annotate_form_data(&$d) {
    simple_object_edit::annotate_form_data($d);
    $this->annotate_row_data($d);
  }

  function _validate() {

    $bad = 0;
    if ($st = mx_db_validate_date($this->data['����'])) {
      $this->err("(����): $st\n");
      $bad++;
    }

    if ($bad == 0)
      return 'ok';
  }

}
?>
