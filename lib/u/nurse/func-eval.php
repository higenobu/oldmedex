<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

function __lib_u_nurse_func_eval_cfg(&$cfg) { 

  $cfg = array_merge
    ($cfg, array
     ('TABLE' => '��ǽɾ��ɽ',
      'ALLOW_SORT' => 1,
      'DEFAULT_SORT' => '����',
      'LCOLS' => array('����', '��Ͽ��̾'),

      'UNIQ_ID' => 'F."ObjectID"',
      ));

  $stmt_head = '
SELECT F.*, (E."��" || E."̾") AS "��Ͽ��̾"
FROM "��ǽɾ��ɽ" AS F
LEFT JOIN "������Ģ" AS E
ON E."userid" = F."CreatedBy" AND E."Superseded" IS NULL
';

  $cfg['HSTMT'] = $stmt_head . 'WHERE NULL IS NULL';
  $cfg['STMT'] = $stmt_head . 'WHERE F."Superseded" IS NULL';

  // List of flip-pages: db column, label, widget type
  $flippage = array
    ('���ߥ�˥��������' =>
     array
     (array("���ߥ�_01", '�ջ����̺���', "check"),
      array("���ߥ�_02", '�������Ǥ���', "check"),
      array("���ߥ�_03", '���郎�Ǥ���', "check"),
      array("���ߥ�_04", '�ؼ��ˤ��ư������', "check"),
      ),

     '��赡ǽ' =>
     array
     (array("���_L_01", '�٥åɾ�Ǿ�褬ư���ʤ��ʺ���', "check"),
      array("���_R_01", '�٥åɾ�Ǿ�褬ư���ʤ��ʱ���', "check"),
      array("���_L_02", '������Ǥ���ʺ���', "check"),
      array("���_R_02", '������Ǥ���ʱ���', "check"),
      array("���_L_03", '��˼꤬�Ϥ��ʺ���', "check"),
      array("���_R_03", '��˼꤬�Ϥ��ʱ���', "check"),
      array("���_L_04", '���˼꤬�Ϥ��ʺ���', "check"),
      array("���_R_04", '���˼꤬�Ϥ��ʱ���', "check"),
      array("���_L_05", '�ܡ�����Ϥ��ʺ���', "check"),
      array("���_R_05", '�ܡ�����Ϥ��ʱ���', "check"),
      array("���_L_06", '��ɮ��Ŧ�߾夲����ʺ���', "check"),
      array("���_R_06", '��ɮ��Ŧ�߾夲����ʱ���', "check"),
      array("���_L_07", 'Ȥ�ǿ������Ǥ���ʺ���', "check"),
      array("���_R_07", 'Ȥ�ǿ������Ǥ���ʱ���', "check"),
      ),

     '�δ���ǽ' =>
     array
     (array("�δ�_01", '��ư��ǽ', "check"),
      array("�δ�_02", '�٥åɾ�ǥ֥�å����Ǥ���', "check"),
      array("�δ�_03", '�ְػҤǥХå��쥹�Ȥ��������Υ����', "check"),
      array("�δ�_04", '10ʬ�ʾ�κ°��ݻ����Ǥ���', "check"),
      ),

     '���赡ǽ' =>
     array
     (array("����_L_01", '�٥åɾ�ǲ��褬ư���ʤ��ʺ���', "check"),
      array("����_R_01", '�٥åɾ�ǲ��褬ư���ʤ��ʱ���', "check"),
      array("����_L_02", '­�ض��Ǥ����¾ư�ˡʺ���', "check"),
      array("����_R_02", '­�ض��Ǥ����¾ư�ˡʱ���', "check"),
      array("����_L_03", '�ض��㣰��ʺ���', "check"),
      array("����_R_03", '�ض��㣰��ʱ���', "check"),
      array("����_L_04", '����������Ǥ����¾ư�ˡʺ���', "check"),
      array("����_R_04", '����������Ǥ����¾ư�ˡʱ���', "check"),
      array("����_L_05", 'ɨ��-30�롡��ɨ�����Ӥʤ��ˡʺ���', "check"),
      array("����_R_05", 'ɨ��-30�롡��ɨ�����Ӥʤ��ˡʱ���', "check"),
      array("����_L_06", 'ɨ��90�롡����ɨ���ʤ���ʤ��ˡʺ���', "check"),
      array("����_R_06", 'ɨ��90�롡����ɨ���ʤ���ʤ��ˡʱ���', "check"),
      array("����_L_07", '�����夬�Ǥ���ʼ�ư�ˡʺ���', "check"),
      array("����_R_07", '�����夬�Ǥ���ʼ�ư�ˡʱ���', "check"),
      array("����_L_08", '�°̤�ɨ��Ÿ�Ǥ���ʺ���', "check"),
      array("����_R_08", '�°̤�ɨ��Ÿ�Ǥ���ʱ���', "check"),
      array("����_L_09", '­Ƨ�ߤǤ���ʺ���', "check"),
      array("����_R_09", '­Ƨ�ߤǤ���ʱ���', "check"),
      array("����_L_10", '�񹳤��Ф�ɨ�ο�Ÿ���Ǥ���ʺ���', "check"),
      array("����_R_10", '�񹳤��Ф�ɨ�ο�Ÿ���Ǥ���ʱ���', "check"),
      array("����_L_11", 'Ω�̤ǲ���ζ������Ǥ���ʺ���', "check"),
      array("����_R_11", 'Ω�̤ǲ���ζ������Ǥ���ʱ���', "check"),
      ),

     '����ư��' =>
     array
     (array("����ư��_01", '���ɻ�̹���', "check"),
      array("��������", '��������', "text"),
      array("����ư��_02", '�龲', "check"),
      array("����ư��_03", '���֤�', "check"),
      array("����ư��_04", '�°��ݻ�', "check"),
      array("����ư��_05", '���夬��', "check"),
      array("����ư��_06", 'Ω���ݻ�', "check"),
      array("����ư��_07", '��Ω', "check"),
      array("����ư��_08", '���', "check"),
      array("����ư��_09", '����', "check"),
      array("����ư��_10",
	    '����ꡢ��Ω���ΰ��Ѵ��ˤ�20mHg�ʾ�η찵�㲼', "check"),
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
    $cfg['EPAGE_BREAKS'] = array(1);
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

class list_of_nurse_func_evals extends list_of_ppa_objects {

  function list_of_nurse_func_evals($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_func_eval_cfg(&$cfg);
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

class nurse_func_eval_display extends simple_object_display {

  function nurse_func_eval_display($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_func_eval_cfg(&$cfg);
    simple_object_display::simple_object_display
      ($prefix, $cfg);
  }

}

class nurse_func_eval_edit extends simple_object_edit {

  function nurse_func_eval_edit($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_func_eval_cfg(&$cfg);
    simple_object_edit::simple_object_edit
      ($prefix, &$cfg);
  }

  function anew_tweak($orig_id) {
    $this->data['����'] = mx_today_string();
  }

  function annotate_row_data(&$d) {
    $d['����'] = $this->so_config['Patient_ObjectID'];
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
    if ($this->data['����ư��_01'] == 'Y' &&
	mx_db_validate_length($this->data['��������'], 1, 0)) {
      $this->err("(��������): ���ǤϤ����ޤ���\n");
      $bad++;
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
