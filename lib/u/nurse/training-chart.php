<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

function __lib_u_nurse_training_chart_cfg(&$cfg) { 

  $cfg = array_merge
    ($cfg, array
     ('TABLE' => '�������㡼��ɽ',
      'ALLOW_SORT' => 1,
      'DEFAULT_SORT' => '����',
      'LCOLS' => array('����', '��Ͽ��̾'),

      'UNIQ_ID' => 'F."ObjectID"',
      ));

  $stmt_head = '
SELECT F.*, (E."��" || E."̾") AS "��Ͽ��̾"
FROM "�������㡼��ɽ" AS F
LEFT JOIN "������Ģ" AS E
ON E."userid" = F."CreatedBy" AND E."Superseded" IS NULL
';

  $cfg['HSTMT'] = $stmt_head . 'WHERE NULL IS NULL';
  $cfg['STMT'] = $stmt_head . 'WHERE F."Superseded" IS NULL';

  // List of flip-pages: db column, label, widget type
  $flippage = array
    (
     '���ߥ�˥��������' => array
     (
      array("���ߥ塽���ķ���Ŭ��",
	    "���ķ���Ŭ��", "check"),
      ),

     '���' => array
     (
      array("��衽¾ư��ư�跱��",
	    "¾ư��ư�跱��", "check"),
      array("��衽�����ư��ư�跱��",
	    "�����ư��ư�跱��", "check"),
      array("��衽��ؤ��Ѥ����̣��ư",
	    "��ؤ��Ѥ����̣��ư", "check"),
      array("��衽Ž�골���ɤ골���ޤ����",
	    "Ž�골���ɤ골���ޤ����", "check"),
      ),

     '�δ�' => array
     (
      array("�δ���¾ư��ư�跱��",
	    "¾ư��ư�跱��", "check"),
      array("�δ����֥�å�",
	    "�֥�å�", "check"),
      array("�δ������ʱ�ư",
	    "���ʱ�ư", "check"),
      array("�δ����°��ݻ�����",
	    "�°��ݻ�����", "check"),
      ),

     '����' => array
     (
      array("���衽¾ư��ư�跱��",
	    "¾ư��ư�跱��", "check"),
      array("���衽�٥åɾ塧­�ض���ư",
	    "�٥åɾ塧­�ض���ư", "check"),
      array("���衽������ư",
	    "������ư", "check"),
      array("���衽SLR",
	    "SLR", "check"),
      array("���衽�°̡�ɨ��Ÿ��ư��ư",
	    "�°̡�ɨ��Ÿ��ư��ư", "check"),
      array("���衽�Զ��ʼ�ư��ư",
	    "�Զ��ʼ�ư��ư", "check"),
      array("���衽ɨ��Ÿ�񹳱�ư",
	    "ɨ��Ÿ�񹳱�ư", "check"),
      array("���衽Ω�̡����������ư",
	    "Ω�̡����������ư", "check"),
      ),

     '����ư��' => array
     (
      array("����ư�¾ư��ư�跱��",
	    "¾ư��ư�跱��", "check"),
      array("����ư��ΰ��Ѵ�",
	    "�ΰ��Ѵ�", "check"),
      array("����ư����֤�ư���",
	    "���֤�ư���", "check"),
      array("����ư�����ꡦ�°��ݻ�����",
	    "����ꡦ�°��ݻ�����", "check"),
      array("����ư���Ω��­Ƨ�߷���",
	    "��Ω��­Ƨ�߷���", "check"),
      array("����ư������Է���",
	    "�����Է���", "check"),
      array("����ư�������Է���",
	    "������Է���", "check"),
      array("����ư�����Ĵ������",
	    "����Ĵ������", "check"),
      ),
     );

  $cfg['ECOLS'] = array(array('Column' => '����',
			      'Option' => array('ime' => 'disabled',
						'validate' => 'date')));
  $cfg['DCOLS'] = array('����');
  $cfg['ICOLS'] = array('����', '����');
  $cfg['DPAGES'] = array_keys($flippage);
  $cfg['EPAGES'] = $cfg['DPAGES'];
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
      if (! is_null($c[0]))
	$cfg['ICOLS'][] = $c[0];
    }
  }
  $cfg['DCOLS'][] = '��Ͽ��̾';
}

class list_of_training_charts extends list_of_ppa_objects {

  function list_of_training_charts($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_training_chart_cfg(&$cfg);
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

class nurse_training_chart_display extends simple_object_display {

  function nurse_training_chart_display($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_training_chart_cfg(&$cfg);
    simple_object_display::simple_object_display
      ($prefix, $cfg);
  }

}

class nurse_training_chart_edit extends simple_object_edit {

  function nurse_training_chart_edit($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_training_chart_cfg(&$cfg);
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
