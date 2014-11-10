<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

function __lib_u_nurse_bedsore_eval_cfg(&$cfg) { 

  $cfg = array_merge
    ($cfg, array
     ('TABLE' => '����в�ɾ��ɽ',
      'ALLOW_SORT' => 1,
      'DEFAULT_SORT' => '����',
      'UNIQ_ID' => 'F."ObjectID"',
      ));

  $columns = array
    (array('Column' => 'Depth',
	   'Label' => '����',
	   'Draw' => 'enum',
	   'Enum' => array
	   ('d0' => 'd0: ����»����ȯ�֤ʤ�',
	    'd1' => 'd1: ��³����ȯ��',
	    'd2' => 'd2: ����ޤǤ�»��',
	    'D3' => 'D3: �鲼�ȿ��ޤǤ�»��',
	    'D4' => 'D4: �鲼�ȿ���Ķ����»��',
	    'D5' => 'D5: ����С��ιФ˻��»���ޤ��ϡ�����Ƚ�꤬��ǽ�ʾ��')
	   ),
     array('Column' => 'Exudate',
	   'Label' => '���б�',
	   'Draw' => 'enum',
	   'Enum' => array
	   ('e0' => 'e0: �ʤ�',
	    'e1' => 'e1: ���̡������Υɥ�å��󥰸򴹤��פ��ʤ�',
	    'E2' => 'E2: �����̡���������Υɥ�å��󥰸򴹤��פ���',
	    'E3' => 'E3: ¿�̡���������ʾ�Υɥ�å��󥰸򴹤��פ���')
	   ),
     array('Column' => 'Size',
	   'Label' => '������',
	   'Draw' => 'enum',
	   'Enum' => array
	   ('s0' => 's0: ����»���ʤ�',
	    's1' => 's1: 4̤��',
	    's2' => 's2: ���ʾ塢����̤��',
	    's3' => 's3: �����ʾ塢����̤��',
	    's4' => 's4: �����ʾ塢����̤��',
	    's5' => 's5: �����ʾ塢100̤��',
	    'S6' => 'S6: 100�ʾ�')
	   ),
     array('Column' => 'Inflammation',
	   'Label' => '���',
	   'Draw' => 'enum',
	   'Enum' => array
	   ('i0' => 'i0: �ɽ�α��ħ���ʤ�',
	    'i1' => 'i1: �ɽ�α��ħ���ʤ����ħ�������ȯ�֡���ı��Ǯ�������ˡ�',
	    'I2' => 'I2: �ɽ�����餫�ʴ���ħ������ʱ��ħ����ǿ�������ʤɡ�',
	    'I3' => 'I3: ����Ū�ƶ������ȯǮ�ʤɡ�')
	   ),
     array('Column' => 'Granulation',
	   'Label' => '�����ȿ�',
	   'Draw' => 'enum',
	   'Enum' => array
	   ('g0' => 'g0: ���Ť��뤤���Ϥ������������������ɾ�����Ǥ��ʤ�',
	    'g1' => 'g1: �������꤬�����̤�90�������',
	    'g2' => 'g2: �������꤬�����̤�50��ʾ�90��̤��������',
	    'G3' => 'G3: �������꤬�����̤�10��ʾ�50��̤��������',
	    'G4' => 'G4: �������꤬�����̤�10��ʾ�51��̤��������',
	    'G5' => 'G5: �������꤬���ޤä�����������Ƥ��ʤ�')
	   ),
     array('Column' => 'NecroticTissue',
	   'Label' => '�����ȿ�',
	   'Draw' => 'enum',
	   'Enum' => array
	   ('n0' => 'n0: �����ȿ��ʤ�',
	    'N1' => 'N1: ���餫�������ȿ�����',
	    'N2' => 'N2: �Ť�����̩�夷�������ȿ�����')
	   ),
     array('Column' => 'Pocket',
	   'Label' => '�ݥ��å�',
	   'Draw' => 'enum',
	   'Enum' => array
	   ('  ' => '�ʤ�',
	    'P1' => 'P1: 4̤��',
	    'P2' => 'P2: ���ʾ塢16̤��',
	    'P3' => 'P3: �����ʾ塢36̤��',
	    'P4' => 'P4: 36�ʾ�')
	   ),
     );

  $stmt_head = '
SELECT F.*, (E."��" || E."̾") AS "��Ͽ��̾", (\'\'';

  foreach ($columns as $a) {
    $stmt_head .= '|| \' \' || COALESCE(' .
      mx_db_sql_quote_name($a['Column']) . ',\'\')';
  }

  $stmt_head .= ') as "ɾ��"
FROM "����в�ɾ��ɽ" AS F
LEFT JOIN "������Ģ" AS E
ON E."userid" = F."CreatedBy" AND E."Superseded" IS NULL
';

  $cfg['HSTMT'] = $stmt_head . 'WHERE NULL IS NULL';
  $cfg['STMT'] = $stmt_head . 'WHERE F."Superseded" IS NULL';

  $cfg['ECOLS'] = array(array('Column' => '����',
			      'Option' => array('ime' => 'disabled',
						'validate' => 'date')));
  $cfg['DCOLS'] = array('����');
  $cfg['ICOLS'] = array('����', '����');
  $cfg['LCOLS'] = array('����', '��Ͽ��̾', 'ɾ��');

  foreach ($columns as $a) {
    $cfg['ECOLS'][] = $a;
    $cfg['DCOLS'][] = $a;
    if (! is_null($a['Column']))
      $cfg['ICOLS'][] = $a['Column'];
  }
  $cfg['DCOLS'][] = '��Ͽ��̾';
}

class list_of_nurse_bedsore_evals extends list_of_ppa_objects {

  function list_of_nurse_bedsore_evals($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_bedsore_eval_cfg(&$cfg);
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

class nurse_bedsore_eval_display extends simple_object_display {

  function nurse_bedsore_eval_display($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_bedsore_eval_cfg(&$cfg);
    simple_object_display::simple_object_display
      ($prefix, $cfg);
  }

}

class nurse_bedsore_eval_edit extends simple_object_edit {

  function nurse_bedsore_eval_edit($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_bedsore_eval_cfg(&$cfg);
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
