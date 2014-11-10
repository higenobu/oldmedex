<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

function __lib_u_nurse_fall_assessment_cfg(&$cfg) { 

  $cfg = array_merge
    ($cfg, array
     ('TABLE' => 'ž��ž�����ɽ',
      'ALLOW_SORT' => 1,
      'DEFAULT_SORT' => '����',
      'LCOLS' => array('����', '��Ͽ��̾'),

      'UNIQ_ID' => 'F."ObjectID"',
      ));

  $stmt_head = '
SELECT F.*, (E."��" || E."̾") AS "��Ͽ��̾"
FROM "ž��ž�����ɽ" AS F
LEFT JOIN "������Ģ" AS E
ON E."userid" = F."CreatedBy" AND E."Superseded" IS NULL
';

  $cfg['HSTMT'] = $stmt_head . 'WHERE NULL IS NULL';
  $cfg['STMT'] = $stmt_head . 'WHERE F."Superseded" IS NULL';

  // List of flip-pages: db column, label, widget type
  $flippage = array
    (
     '���α�ư������Ĵ' => array
     (
      array("���α�ư_01", "���������Ѥ��Ƥ���", "check"),
      array("���α�ư_02", "��ư�˰��������ɬ�פǤ���", "check"),
      array("���α�ư_03", "��Ի��ˤդ�Ĥ�������", "check"),
      array("���α�ư_04", "��������˰۾郎����", "check"),
      array("���α�ư_05", "��Ԥ�����", "check"),
      ),

     '���е�ǽ�㳲������' => array
     (
      array("���е�ǽ_01", "���Ͼ㳲������", "check"),
      array("���е�ǽ_02", "�����������", "check"),
      array("���е�ǽ_03", "�Ť����Ѳ��˽���Ǥ��ʤ�", "check"),
      array("���е�ǽ_04", "�������Ѥ��Ƥ���", "check"),
      array("���е�ǽ_05", "İ�Ͼ㳲������", "check"),
      array("���е�ǽ_06", "��İ�����Ѥ��Ƥ���", "check"),
      array("���е�ǽ_07", "���ÿ��о㳲������", "check"),
      ),

     '�۴�ư��' => array
     (
      array("�۴�ư��_01", "��Ω����찵������", "check"),
      array("�۴�ư��_02", "����̮������", "check"),
      array("�۴�ư��_03", "����ȯ��ηи�������", "check"),
      array("�۴�ư��_04", "��ޤ�������", "check"),
      array("�۴�ư��_05", "�Ϸ줬����", "check"),
      ),

     '�����򴹾㳲' => array
     (
      array("������_01", "PaO�����㲼������", "check"),
      array("������_02", "PaCO�����㲼������徺������", "check"),
      ),

     '��ʪ�λ��Ѿ���' => array
     (
      array("��ʪ����_01", "��̲�ޡ���������ޤ�����", "check"),
      array("��ʪ����_02", "�߰��ޤ����Ѥ��Ƥ���", "check"),
      array("��ʪ����_03", "��Ǣ�ޤ����Ѥ��Ƥ���", "check"),
      array("��ʪ����_04", "������륮���ޤ����Ѥ��Ƥ���", "check"),
      ),

     '��������' => array
     (
      array("��������_01", "��֤����Ѥ�����", "check"),
      array("��������_02", "Ƚ���Ϥ��㲼������", "check"),
      array("��������_03", "�����α��������뤳�Ȥˤ��������񹳴�������", "check"),
      ),

     'ž�ݡ�ž��ηи�' => array
     (
      array("ž����и�_01", "ž�ݡ�ž��ηи�������", "check"),
      ),

     '��̲�Υѥ�����' => array
     (
      array("��̲_01", "���Ĥ�������", "check"),
      array("��̲_02", "�Ͽ�֤��ʤ�", "check"),
      array("��̲_03", "�ͤܤ�������", "check"),
      array("��̲_04", "�뿲�ν���������", "check"),
      ),

     '�����ѥ�����' => array
     (
      array("����_01", "��������Τ���˳��ä���", "check"),
      array("����_02", "Ǣ�դ�����Ǥ��ʤ�", "check"),
      array("����_03", "��Ǣ���񤬤���", "check"),
      array("����_04", "��������Ĵ������", "check"),
      ),

     '����¾�ʤ���²������������' => array
     (
      array("����¾_01", "��ư�򤢤���䤹��", "check"),
      array("����¾_02", "�����򤹤������Ƥ�", "check"),
      array("����¾_03", "¾�Ԥ��鸫�ƴ��Ȼפ���ư��ʿ���Ǥ���", "check"),
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
    $cfg['EPAGE_BREAKS'] = array(1, 3, 5, 7);
  $page_num = -1;
  foreach ($flippage as $page_name => $page_desc) {
    $page_num++;
    foreach ($page_desc as $c) {
      $a = array('Page' => $page_num,
		 'Column' => $c[0],
		 'Label' => ($c[1] ? $c[1] : $c[0]),
		 'Draw' => $c[2]);
      $cfg['ECOLS'][] = $a;
      $cfg['DCOLS'][] = $a;
      if (! is_null($c[0]))
	$cfg['ICOLS'][] = $c[0];
    }
  }
  $cfg['DCOLS'][] = '��Ͽ��̾';
}

class list_of_nurse_fall_assessments extends list_of_ppa_objects {

  function list_of_nurse_fall_assessments($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_fall_assessment_cfg(&$cfg);
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

class nurse_fall_assessment_display extends simple_object_display {

  function nurse_fall_assessment_display($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_fall_assessment_cfg(&$cfg);
    simple_object_display::simple_object_display
      ($prefix, $cfg);
  }

}

class nurse_fall_assessment_edit extends simple_object_edit {

  function nurse_fall_assessment_edit($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_fall_assessment_cfg(&$cfg);
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

  function _pre_commit_hook($db) {
    return NULL;
    return '�ǡ����١����ؤν��ᤷ�Ϥޤ��Ǥ����顢����ߡפǽ��äƲ�����';
  }

}
?>
