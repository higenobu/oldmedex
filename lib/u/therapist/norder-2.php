<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

function __lib_u_therapist_norder_cfg(&$cfg) {
  $cfg = array_merge
    ($cfg, array
     ('TABLE' => '��Ͻ����',
      'ALLOW_SORT' => array
      ('ObjectID' => array('����ID' => 'X."ObjectID"'),
       '����' => array('����' => 'X."������"'),
       '������' => array('������' => '(DR."��" || DR."̾")')),
      'DEFAULT_SORT' => '����',
      'ENABLE_QBE' =>
      array(array('Column' => '����', 'Compare' => 'X."������"',
		  'Draw' => 'text'),
	    array('Column' => '������',
		  'Compare' => '(DR."��" || DR."̾")',
		  'Draw' => 'text')),
      'LCOLS' => array(array('Column' => 'ObjectID',
			     'Label' => '����ID'),
		       '����',
		       '������ʬ',
		       '������', '��', '��', '��'),

      'UNIQ_ID' => 'X."ObjectID"',
      ));
  
  $cfg['AlreadyHasSelectionFor'] = array('����' => 1,
					 '������' => 1,
					 '������ʬ' => 1,
					 '������ˡ' => 1,
					 '�����ˡ' => 1,
					 '����İ����ˡ' => 1);
  $cfg['STMT_SEL'] = '
SELECT X."ObjectID",
       X."������" as "����",
       X."������ʬ",
       (DR."��" || DR."̾") as "������",
       CASE WHEN X."������ˡ" = \'on\' THEN \'��\' ELSE \'��\' END as "��",
       CASE WHEN X."�����ˡ" = \'on\' THEN \'��\' ELSE \'��\' END as "��",
       CASE WHEN X."����İ����ˡ" = \'on\' THEN \'��\' ELSE \'��\' END as "��"
';
  $cfg['STMT_FROM'] = '
FROM "��Ͻ����" as X
LEFT JOIN "������Ģ" as DR ON DR."ObjectID" = X. "���"
';

  $stmt_head = $cfg['STMT_SEL'] . $cfg['STMT_FROM'];
  $cfg['HSTMT'] = $stmt_head . 'WHERE NULL IS NULL';
  $cfg['STMT'] = $stmt_head . 'WHERE X."Superseded" IS NULL';
}

class list_of_rehab_norders extends list_of_ppa_objects {

  var $default_row_per_page = 4;
  var $debug = 1;

  function list_of_rehab_norders($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_therapist_norder_cfg(&$cfg);
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

$__lib_u_therapist_norder_yucky_cols = array
(
"�����ư�跱��PT",
"�����ư�跱��OT",
"������������PT",
"������������OT",
"���жںƶ���PT",
"���жںƶ���OT",
"��Ĵ������PT",
"��Ĵ������OT",
"����Ĵ������PT",
"����Ĵ������OT",
"����ư���PT",
"����ư���OT",
"����ư���PT",
"����ư���OT",
"���������ư����PT",
"���������ư����OT",
"�����Ϣư���PT",
"�����Ϣư���OT",
"ǧ�η���PT",
"ǧ�η���OT",
"����Ķ�����PT",
"����Ķ�����OT",
"�����񡦼�����θ�ƤPT",
"�����񡦼�����θ�ƤOT",
"����ɾ��������PT",
"����ɾ��������OT",
"������ɾ��PT",
"������ɾ��OT",
"����¾�����ɾ������PT",
"����¾�����ɾ������OT",
"�⼡Ǿ��ǽ����OT",
"�⼡Ǿ��ǽ����ST",
);

function __lib_u_therapist_norder_cfg_detailed_flippage(&$cfg, $name, $ary)
{
	global $__lib_u_therapist_norder_yucky_cols;
	if (!array_key_exists('DPAGES', $cfg))
		$cfg['DPAGES'] = array();
	$ix = count($cfg['DPAGES']); 
	$cfg['DPAGES'][] = $name;
	foreach ($ary as $a) {
		if (!is_array($a))
			$a = array('Column' => $a);
		$a['Page'] = $ix;
		if (!array_key_exists('Label', $a))
			$a['Label'] = $a['Column'];
		if (!array_key_exists('Draw', $a))
			$a['Draw'] = 'text';
		$cfg['DCOLS'][] = $a;
	}
}

function __lib_u_therapist_norder_cfg_detailed(&$cfg) {
	__lib_u_therapist_norder_cfg(&$cfg);

	__lib_u_therapist_norder_cfg_detailed_flippage
		(&$cfg, '����', array
		 ('����',
		  '��Ͽ��',
		  '������',
		  array('Label' => '��Ͻ���',
			'Column' => array('������ˡ',
					    '�����ˡ',
					    '����İ����ˡ'),
			'Draw' => 'check_box'),
		  array('Label' => '�����ͳ',
			'Column' => array('���Ū����ɬ��',
					    '�������֤ε޷�ʰ���',
					    '��������������',
					    '��������ϫ�����ˤ��ʤ�',
					    '�����ͳ����¾'),
			'Draw' => 'check_box'),
		  '�����ͳ������',
		  '������ʬ'));

	__lib_u_therapist_norder_cfg_detailed_flippage
		(&$cfg, '��ǽ�㳲1', array
		 ("�ռ��㳲",
		  array('OmitIfEmpty' => 1,
			'Column' => "�ռ��㳲������"),
		  "JCS",
		  array('Column' => "����",
			'Label' => "����(�������㳲)"),
		  array('OmitIfEmpty' => 1,
			'Column' => "���򥳥���"),
		  "��Ū�㳲",
		  array('OmitIfEmpty' => 1,
			'Column' => "��Ū�㳲������"),
		  array('Label' => '�⼡��ǽ�㳲'),
		  array('Column' => "��վ㳲",
			'Label' => "����վ㳲"),
		  array('Column' => "��վ㳲������",
			'OmitIfEmpty' => 1,
			'Label' => "����վ㳲������"),
		  array('Column' => "�����㳲",
			'OmitIfEmpty' => 1,
			'Label' => "�������㳲"),
		  array('Column' => "�����㳲������",
			'OmitIfEmpty' => 1,
			'Label' => "�������㳲������"),
		  array('Column' => "��ǧ",
			'Label' => "����ǧ"),
		  array('Column' => "��ǧ������",
			'OmitIfEmpty' => 1,
			'Label' => "����ǧ������"),
		  array('Column' => "����",
			'Label' => "������"),
		  array('Column' => "���ԥ�����",
			'OmitIfEmpty' => 1,
			'Label' => "�����ԥ�����"),
		  array('Column' => "����",
			'Label' => "������"),
		  array('Column' => "���쥳����",
			'OmitIfEmpty' => 1,
			'Label' => "�����쥳����"),
		  array('Column' => "Ⱦ¦�����̵��",
			'Label' => "��Ⱦ¦�����̵��"),
		  array('Column' => "Ⱦ¦�����̵�륳����",
			'OmitIfEmpty' => 1,
			'Label' => "��Ⱦ¦�����̵�륳����")));

	__lib_u_therapist_norder_cfg_detailed_flippage
		(&$cfg, '��ǽ�㳲2', array
		 ("�ռ��㳲",
		  array('Label' => '�γо㳲'),
		  array('Column' => "��о㳲",
			'Label' => "����о㳲"),
		  array('Column' => "��о㳲������",
			'OmitIfEmpty' => 1,
			'Label' => "����о㳲������"),
		  array('Column' => "İ�о㳲",
			'Label' => "��İ�о㳲"),
		  array('Column' => "İ�о㳲������",
			'OmitIfEmpty' => 1,
			'Label' => "��İ�о㳲������"),
		  array('Column' => "ɽ�ߴ��о㳲",
			'Label' => "��ɽ�ߴ��о㳲"),
		  array('Column' => "ɽ�ߴ��о㳲������",
			'OmitIfEmpty' => 1,
			'Label' => "��ɽ�ߴ��о㳲������"),
		  array('Column' => "�������о㳲",
			'Label' => "���������о㳲"),
		  array('Column' => "�������о㳲������",
			'OmitIfEmpty' => 1,
			'Label' => "���������о㳲������"),
		  "�ˤ�",
		  array('OmitIfEmpty' => 1,
			'Column' => "�ˤߥ�����"),
		  "�����㳲",
		  array('OmitIfEmpty' => 1,
			'Column' => "�����㳲������"),
		  array('Column' => "�Ƶ۽۴Ĵﵡǽ�㳲",
			'Label' => "�Ƶ۽۴Ĵﵡǽ�㳲�ʵ�Ω����찵�������۴ľ㳲��"),
		  array('OmitIfEmpty' => 1,
			'Column' => "�Ƶ۽۴Ĵﵡǽ�㳲������"),
		  "�ݿ���ǽ�㳲",
		  array('OmitIfEmpty' => 1,
			'Column' => "�ݿ���ǽ�㳲������"),
		  array('Label' => '������ǽ�㳲'),
		  array('Column' => "��Ǣ��ǽ�㳲",
			'Label' => "����Ǣ��ǽ�㳲"),
		  array('OmitIfEmpty' => 1,
			'Column' => "��Ǣ��ǽ�㳲������",
			'Label' => "����Ǣ��ǽ�㳲������"),
		  array('Column' => "���ص�ǽ�㳲",
			'Label' => "�����ص�ǽ�㳲"),
		  array('OmitIfEmpty' => 1,
			'Column' => "���ص�ǽ�㳲������",
			'Label' => "�����ص�ǽ�㳲������"),
		  "���������",
		  array('OmitIfEmpty' => 1,
			'Column' => "��������㥳����"),
		  "����",
		  array('OmitIfEmpty' => 1,
			'Column' => "���̥�����"),
		  "�����㲼",
		  array('OmitIfEmpty' => 1,
			'Column' => "�����㲼������"),
		  array('Label' => '�ڶ�ĥ�ξ㳲'),
		  array('Column' => "�д�",
			'Label' => '���д�'),
		  array('OmitIfEmpty' => 1,
			'Column' => "�д˥�����",
			'Label' => '���д˥�����'),
		  array('Column' => "����",
			'Label' => '������'),
		  array('OmitIfEmpty' => 1,
			'Column' => "����������",
			'Label' => '������������'),
		  array('Column' => "�ǽ�",
			'Label' => '���ǽ�'),
		  array('OmitIfEmpty' => 1,
			'Column' => "�ǽ̥�����",
			'Label' => '���ǽ̥�����'),
		  array('Column' => "�Կ�ձ�ư",
			'Label' => "�Կ�ձ�ư���ʼ�Ĵ�������"),
		  array('OmitIfEmpty' => 1,
			'Column' => "�Կ�ձ�ư������"),
		  "����",
		  array('OmitIfEmpty' => 1,
			'Column' => "���ϥ�����"),
		  array('OmitIfEmpty' => 1,
			'Column' => "��ǽ�㳲������")));

	__lib_u_therapist_norder_cfg_detailed_flippage
		(&$cfg, '������ߴ��', array
		 ('��������',
		  array('Column' => '��ư����˥���ɬ��',
			'Label' => '��ư����˥���',
			'Draw' => 'check_box',
			'Column' => array('��ư����˥���ɬ��')),
		  '��˥��˥󥰤�����',
		  array('Label' => '������ߴ��',
			'Draw' => 'stop_basis',
			'Check' => array("�ռ���٥��㲼","�������ν���",
					 "�β�", "�β���ͳ����", 
					 "���̴��찵", "��ĥ���찵",
					 "SPO2", "SPO2��ͳ����",
					 "Anderson�δ��","Anderson�δ�ॳ����")),
		  ));

	__lib_u_therapist_norder_cfg_detailed_flippage
		(&$cfg, '������ɸ', array
		 ('����ư��ǽ��',
		  '���َ̎���ǽ��',
		  'ǧ��ǽ��',
		  array('Label' => '������',
			'Column' => '��ɸ������'),
		  ));

	__lib_u_therapist_norder_cfg_detailed_flippage
		(&$cfg, '����1', array
		 (array('Column' => "�����ư�跱��",
			'Draw' => 'pos'),
		  array('Column' => "������������",
			'Draw' => 'pos'),
		  array('Column' => "���жںƶ���",
			'Draw' => 'pos'),
		  array('Column' => "��Ĵ������",
			'Draw' => 'pos'),
		  array('Label' => '����Ĵ������',
			'Draw' => 'coordinate',
			'Check' => 
			array("����Ĵ������PT",
			      "����Ĵ������OT",
			      "ͭ���Ǳ�ư���",
			      "ͭ���Ǳ�ư�ְػҶ�ư",
			      "ͭ���Ǳ�ư����ư��",
			      "ͭ���Ǳ�ư�°̤Ǥ����ȱ�ư",
			      "ͭ���Ǳ�ư�����˱�����",
			      "ͭ���Ǳ�ư������",
			      "ͭ���Ǳ�ư���Time",
			      "ͭ���Ǳ�ư���Time��ͳ����",
			      "ͭ���Ǳ�ư�ְػҶ�ưTime",
			      "ͭ���Ǳ�ư�ְػҶ�ưTime��ͳ����",
			      "ͭ���Ǳ�ư����ư��Time",
			      "ͭ���Ǳ�ư����ư��Time��ͳ����",
			      "ͭ���Ǳ�ư�°̤Ǥ����ȱ�ưTime",
			      "ͭ���Ǳ�ư�°̤Ǥ����ȱ�ưTime��ͳ����",
			      "ͭ���Ǳ�ư�����˱�����Time",
			      "ͭ���Ǳ�ư�����˱�����Time��ͳ����",
			      "ͭ���Ǳ�ư".
			      "ͭ���Ǳ�ưTime",
			      "ͭ���Ǳ�ư�����",
			      "ͭ���Ǳ�ư�����MAX",
			      "����ȥơ��֥�",
			      "����ȥơ��֥�Time",
			      "����ȥơ��֥�Set",
			      "�٥åɥ���å�",
			      "�٥åɥ���å���ͳ����",
			      "�٥åɥ���å�Time",
			      "�٥åɥ���å�Time��ͳ����",
			      "�٥åɥ���å�Set",
			      "�٥åɥ���å�Set��ͳ����",
			      "�٥åɥ���å������",
			      "�٥åɥ���å������MAX",
			      "SPO2MAX")),
		  array('Column' => "����ư���",
			'Draw' => 'pos'),
		  array('Column' => "����ư���",
			'Draw' => 'pos'),
		  array('Column' => "���������ư����",
			'Draw' => 'pos'),
		  array('Column' => "�����Ϣư���",
			'Draw' => 'pos'),
		  array('Column' => "ǧ�η���",
			'Draw' => 'pos'),
		  array('Column' => "����Ķ�����",
			'Draw' => 'pos'),
		  array('Column' => "�����񡦼�����θ�Ƥ",
			'Draw' => 'pos'),
		  "���������",
		  array('Column' => "����ɾ��������",
			'Draw' => 'pos'),
		  array('Column' => "������ɾ��",
			'Draw' => 'pos'),
		  array('Column' => "����¾�����ɾ������",
			'Draw' => 'pos'),
		  "ɾ������"));

	__lib_u_therapist_norder_cfg_detailed_flippage
		(&$cfg, '����2', array
		 (array('Label' => '����ɷ���',
			'Draw' => 'check_box',
			'Column' => array("����ɷ���ɾ��",
					    "����ɷ���",
					    "ȯ�켺�Է���",
					    "������ʤθ�Ƥ",
					    "�Ķ�Ĵ��")),
		  "����ɷ���������",
		  array('Label' => '����ɸ���'),
		  array('Label' => '���Ū����',
			'Draw' => 'check_box',
			'Column' => array("SLTA",
					    "SLTA�������",
					    "SALA",
					    "Ϸ���Ǽ���ɴ��̿��Ǹ���",
					    "WAB")),
		  array('Label' => '���겼������İ������',
			'Draw' => 'check_box',
			'Column' => array("�ȡ�����ƥ���",
					    "������ø���",
					    "İ��Ū�Ļ��ϸ���",
					    "ñ��Υ⡼��ʬ��ǽ�ϸ���",
					    "ñ��Υ⡼�����ǽ�ϸ���",
					    "�첻���̸���")),
		  array('Label' => '�ä�����',
			'Draw' => 'check_box',
			'Column' => array("100ñ��ƾθ���",
					    "��������",
					    "ȯ�켺�Ը���")),
		  array('Label' => '�ɤ߽񤭲���',
			'Draw' => 'check_box',
			'Column' => array("����-��̾����",
					    "���ɸ���",
					    "�ɲ��ϸ���",
					    "100ñ���θ���")),
		  array('Label' => '��ʸǽ��',
			'Draw' => 'check_box',
			'Column' => array("����ɹ�ʸ����")),
		  array('Label' => '����Ū�ʎ��Ў��Ǝ��������ݤ˴ؤ��븡��',
			'Draw' => 'check_box',
			'Column' => array("CADL")),
		  array('Label' => '������',
			'Draw' => 'check_box',
			'Column' => array("���ټ���ɸ���")),
		  "����ɸ���������"));

	__lib_u_therapist_norder_cfg_detailed_flippage
		(&$cfg, '����3', array
		 (array('Label' => '��������',
			'Draw' => 'check_box',
			'Column' => array("��������ɾ��",
					    "��������")),
		  "��������������",
		  array('Label' => '��������'),
		  array('Label' => '��������',
			'Draw' => 'check_box',
			'Column' => array("�����ﴱ����",
					    "ñ�������ٸ���",
					    "����������Ƚ��")),
		  "��������������" ));

	__lib_u_therapist_norder_cfg_detailed_flippage
		(&$cfg, '����4', array
		 (array('Label' => '�⼡Ǿ��ǽ����'),
		  array('Label' => 'ô��',
			'Column' => '�⼡Ǿ��ǽ����',
			'Draw' => 'pos'),
		  array('Label' => '�⼡Ǿ��ǽ����',
			'Draw' => 'check_box',
			'Column' => array("�⼡Ǿ��ǽ����",
					    "�⼡Ǿ��ǽ����ɾ��")),
		  "�⼡Ǿ��ǽ����������",
		  array('Label' => '�⼡Ǿ��ǽɾ��'),
		  array('Label' => '��ǽ����',
			'Draw' => 'check_box',
			'Column' => array("�졼�֥󿧺�",
					    "������Ω����",
					    "MMSE",
					    "HDS-R",
					    "WAIS-R")),
		  array('Label' => 'Ⱦ¦����̵�롦Ⱦ�ո���',
			'Draw' => 'check_box',
			'Column' => array("��ʬ2��ʬ",
					    "BIT")),
		  array('Label' => '��ո���',
			'Draw' => 'check_box',
			'Column' => array("TMT-A",
					    "TMT-B",
					    "���ʽ�����ͭ��̣��",
					    "���ʽ�����̵��̣��")),
		  array('Label' => '��������',
			'Draw' => 'check_box',
			'Column' => array("���𼰵����ϸ���",
					    "�٥�ȥ��е��ø���",
					    "Reyʣ���޷�",
					    "��С��ߡ��ɹ�ư��������",
					    "���������顼��������")),
		  array('Label' => '���ԡ���ǧ',
			'Draw' => 'check_box',
			'Column' => array("ɸ��⼡ư��������",
					    "�⼡���γи���")),
		  array('Label' => '��Ƭ�յ�ǽ',
			'Draw' => 'check_box',
			'Column' => array("Wisconsin��Card��Sorting��Test",
					    "Word��Fluency��Test",
					    "BADS",
					    "�ϥΥ�����")),
		  "�⼡Ǿ��ǽɾ��������" ));

	__lib_u_therapist_norder_cfg_detailed_flippage
		(&$cfg, '����5', array
		 (array('Label' => '�ݿ��벼����',
			'Draw' => 'check_box',
			'Column' => array("�ݿ��벼����",
					    "�ݿ��벼����ɾ��",
					    "ľ�ܷ���",
					    "���ܷ���")),
		  "�ݿ��벼����������",
		  "VF�ܹ���",
		  "VF��Ū"));

	__lib_u_therapist_norder_cfg_detailed_flippage
		(&$cfg, '����6', array
		 (array('Label' => 'İ��ɾ��'),
		  array('Label' => 'İ��ɾ��',
			'Draw' => 'check_box',
			'Column' => array("İ��ɾ��")),
		  "İ��ɾ��������",
		  array('Label' => '��������',
			'Draw' => 'check_box',
			'Column' => array("�㲻İ�ϸ���",
					    "�첻İ�ϸ���")),
		  "İ�ϸ���������"));

	__lib_u_therapist_norder_cfg_detailed_flippage
		(&$cfg, '����7', array
		 (array('Label' => 'ʪ����ˡ'),
		  array('Label' => "�ۥåȥѥå�",
			'Draw' => 'with_position',
			'Column' => array("�ۥåȥѥå�",
					  "�ۥåȥѥå�����")),
		  array('Label' => "�ޥ�����������",
			'Draw' => 'with_position',
			'Column' => array("�ޥ�����������",
					  "�ޥ���������������")),
		  array('Label' => "Ķ����ˡ��",
			'Draw' => 'with_position',
			'Column' => array("Ķ����ˡ��",
					  "Ķ����ˡ������")),
		  array('Label' => "�����ˡ��",
			'Draw' => 'with_position',
			'Column' => array("�����ˡ��",
					  "�����ˡ������")),
		  array('Label' => "��ή��",
			'Draw' => 'with_position',
			'Column' => array("��ή��",
					  "��ή������")),
		  array('Label' => "�������ѥå�",
			'Draw' => 'with_position',
			'Column' => array("�������ѥå�",
					  "�������ѥå�����")),
		  array('Label' => "�ϥɥޡ�",
			'Draw' => 'with_position',
			'Column' => array("�ϥɥޡ�",
					  "�ϥɥޡ�����")),

		  array('Label' => '����',
			'Draw' => 'traction',
			'Column' => array("����", "����", "��������",
					  "����", "��������")),
		  "����¾�õ�����"));

	$cfg['DPAGE_BREAKS'] = array(4);

	$sel = array();

	foreach ($cfg['DCOLS'] as $elem) {
		if (!array_key_exists('Column', $elem))
			continue;
		$col = $elem['Column'];
		if (!is_array($col)) {
			$col = array($col);
		}
		foreach ($col as $c) {
			if (array_key_exists($c,
					     $cfg['AlreadyHasSelectionFor']))
				continue;

			/* This specific hack is needed only because
			 * the base code and the schema definition is yucky.
			 */
			if ($elem['Draw'] == 'pos')
				continue;
			$sel[] = "X.\"$c\" AS \"$c\"";
			$cfg['AlreadyHasSelectionFor'][$c] = 1;
		}
	}
	foreach ($__lib_u_therapist_norder_yucky_cols as $c) {
		$sel[] = "X.\"$c\" AS \"$c\"";
		$cfg['AlreadyHasSelectionFor'][$c] = 1;
	}

	$sel = implode(",\n  ", $sel);
	$stmt_head = ($cfg['STMT_SEL'] . ",\n  "
		      . $sel . "\n" . $cfg['STMT_FROM']);

	$cfg['HSTMT'] = $stmt_head . 'WHERE NULL IS NULL';
	$cfg['STMT'] = $stmt_head . 'WHERE X."Superseded" IS NULL';

}

class rehab_norder_display extends simple_object_display {

  var $debug = 1;

  function rehab_norder_display($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_therapist_norder_cfg_detailed(&$cfg);
    simple_object_display::simple_object_display($prefix, $cfg);
  }

  function omit_if_empty($desc, $data, $hdata) {
    $col = $desc['Column'];
    if ($data[$col]) { return 0; }
    return 1;
  }

  function history($direction=NULL) {
    $result = simple_object_display::history($direction);
    return $result + 16;
  }

  function dx_check_box($desc, $data, $changed) {
	  foreach ($desc['Column'] as $item)
		  if ($data[$item] == 'on')
			  print htmlspecialchars($item)."&nbsp;";
  }

  function dx_stop_basis($desc, $data, $changed) {
    if ($data["�ռ���٥��㲼"] == 'on')
      print "�ռ���٥��㲼<br>";
    if ($data["�������ν���"] == 'on')
      print "�������ν���<br>";

    if (($v = $data["�β���ͳ����"]) || ($v = $data["�β�"]))
      printf("<br />�β� %s &deg;�ʾ�",$v);

    if ($v = $data["���̴��찵"])
      printf("<br />���̴��찵 %s mmHg�ʾ�",$v);
    if ($v = $data["��ĥ���찵"])
      printf("<br />��ĥ���찵 %s mmHg�ʾ�",$v);
    if ($v = $data["�����inc"])
      printf("<br />��ư������� %s/ʬ�徺",$v);
    if ($v = $data["�����dec"])
      printf("<br />��ư������� %s/ʬ����",$v);
    if (($v = $data["SPO2��ͳ����"]) ||
	($v = $data["SPO2"]))
      printf("<br />SPO2�� %s��ʾ�",$v);
    if ($v = $data["Anderson�δ��"])
      printf("<br />Anderson�δ�� %s",$v);
    if ($v = $data["Anderson�δ�ॳ����"])
      printf("<br />Anderson�δ�ॳ���� %s",$v);
  }

  function dx_pos($d, $data, $changed) {
    if ($data[$d['Column'].'PT'] == 'on')
      print " PT";
    if ($data[$d['Column'].'OT'] == 'on')
      print " OT";
    if ($data[$d['Column'].'ST'] == 'on')
      print " ST";
  }

  function dx_coordinate($d, $data, $changed) {
    $this->dx_pos(array('Column' => '����Ĵ������'));
    if ($data["ͭ���Ǳ�ư���"])
      printf("<br />��� %s ʬ��",
	     $data["ͭ���Ǳ�ư���Time��ͳ����"] ?
	     $data["ͭ���Ǳ�ư���Time��ͳ����"] :
	     $data["ͭ���Ǳ�ư���Time"]);
    if ($data["ͭ���Ǳ�ư�ְػҶ�ư"])
      printf("<br />�ְػҶ�ư %s ʬ��",
	     $data["ͭ���Ǳ�ư�ְػҶ�ưTime��ͳ����"] ?
	     $data["ͭ���Ǳ�ư�ְػҶ�ưTime��ͳ����"] :
	     $data["ͭ���Ǳ�ư�ְػҶ�ưTime"]);
    if ($data["ͭ���Ǳ�ư����ư��"])
      printf("<br />����ư�� %s ʬ��",
	     $data["ͭ���Ǳ�ư����ư��Time��ͳ����"] ?
	     $data["ͭ���Ǳ�ư����ư��Time��ͳ����"] :
	     $data["ͭ���Ǳ�ư����ư��Time"]);
    if ($data["ͭ���Ǳ�ư�°̤Ǥ����ȱ�ư"])
      printf("<br />�°̤Ǥ����ȱ�ư %s ʬ��",
	     $data["ͭ���Ǳ�ư�°̤Ǥ����ȱ�ưTime��ͳ����"] ?
	     $data["ͭ���Ǳ�ư�°̤Ǥ����ȱ�ưTime��ͳ����"] :
	     $data["ͭ���Ǳ�ư�°̤Ǥ����ȱ�ưTime"]);
    if ($data["ͭ���Ǳ�ư�����˱�����"])
      printf("<br />�����˱����� %s ʬ��",
	     $data["ͭ���Ǳ�ư�����˱�����Time��ͳ����"] ?
	     $data["ͭ���Ǳ�ư�����˱�����Time��ͳ����"] :
	     $data["ͭ���Ǳ�ư�����˱�����Time"]);
    if ($data["ͭ���Ǳ�ư"])
      printf("<br />%s %s ʬ��",
	     $data["ͭ���Ǳ�ư"],
	     $data["ͭ���Ǳ�ưTime"]);
    if ($data["ͭ���Ǳ�ư�����"])
      printf("<br />��ɸ����� %s b��ʬ MaxHR %s ���ն�",
	     $data["ͭ���Ǳ�ư�����"],
	     $data["ͭ���Ǳ�ư�����MAX"]);
    if ($data["����ȥơ��֥�"])
      printf("<br />����ȥơ��֥� %s &deg; %s ʬ�� %s ���å�",
	     $data["����ȥơ��֥�"],
	     $data["����ȥơ��֥�Time"],
	     $data["����ȥơ��֥�Set"]);
    if (($v = $data["�٥åɥ���å���ͳ����"]) ||
	($v = $data["�٥åɥ���å�"]) && $v != '-')
      printf("<br />�٥åɥ���å� %s &deg; %s ʬ�� %s ���å�", $v,
	     $data["�٥åɥ���å�Time��ͳ����"] ?
	     $data["�٥åɥ���å�Time��ͳ����"] :
	     $data["�٥åɥ���å�Time"],
	     $data["�٥åɥ���å�Set��ͳ����"] ?
	     $data["�٥åɥ���å�Set��ͳ����"] :
	     $data["�٥åɥ���å�Set"]);
    if ($data["�٥åɥ���å������"] &&
	$data["�٥åɥ���å������MAX"])
      printf("<br />��ɸ����� %s b��ʬ MaxHR %s ���ն�",
	     $data["�٥åɥ���å������"],
	     $data["�٥åɥ���å������MAX"]);
    if ($data["SPO2MAX"])
      printf("<br />SPO2 %s ��ʲ��β��ߤǵٷƤ�Ȥ�",
	     $data["SPO2MAX"]);
  }

  function dx_with_position($d, $data, $changed) {
    $c = $d['Column'][0];
    $cp = $d['Column'][1];
    if ($data[$c]) {
      print "* ";
      print $data[$cp];
    }
  }

  function dx_traction($d, $data, $changed) {
      if ($data["����"]) {
	print "���� (����";
	print $data["��������"];
	print ")<br />";
      }
      if ($data["����"]) {
	print $data["��������"];
	print "���� (����";
	print $data["��������"];
	print ")<br />";
      }
  }
}


?>
