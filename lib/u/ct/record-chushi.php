<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';

$_lib_u_ct_record_chushi_cfg = array
('TABLE' => '�������æ��',
 'COLS' => array("��ߡ�æ���̵ͭ",
		 "��ߡ�æ����",
		 "�����ͳ1",
		 "�����ͳ2",
		 "�����ͳ3",
		 "�����ͳ4",
		 "�����ͳ5",

		 "æ����ͳ1",
		 "æ����ͳ2",
		 "æ����ͳ3",
		 "������",
		 ),
 'ICOLS' => array(
		  "��ߡ�æ���̵ͭ",
		  "��ߡ�æ����",
		  "�����ͳ1",
		  "�����ͳ2",
		  "�����ͳ3",
		  "�����ͳ4",
		  "�����ͳ5",
		  
		  "æ����ͳ1",
		  "æ����ͳ2",
		  "æ����ͳ3",
		  "������",
		  "����������"
		 ),
 'ECOLS' => array(
		  array('Column' => "��ߡ�æ���̵ͭ",
			'Draw' => 'radio',
			'Enum' => array(NULL => '̤����',
					1 => '�ʤ�',
					2 => '����'
					),
			),
		  array('Column' => "��ߡ�æ����",
			'Draw' => 'date',
			),
		  array('Column' => "�����ͳ1",
			'Draw' => 'check',
			'Caption' => '�︡�Ԥ��鼭��ο����Ф����ä�'
			),
		  array('Column' => "�����ͳ2",
			'Draw' => 'check',
			'Caption' => '���Ƥ�ͭ�����ݤ�ȯ��������Ϳ��³�����Ƚ�Ǥ��줿'
			),
		  array('Column' => "�����ͳ3",
			'Draw' => 'check',
			'Caption' => '����������ζ�ȯ���Τ��뤤�����ʤɤˤ����Ϳ��³������Ȥʤä�'
			),
		  array('Column' => "�����ͳ4",
			'Draw' => 'check',
			'Caption' => '�������ϸ塢�︡�Ԥ��оݳ��Ǥ������Ƚ������'
			),
		  array('Column' => "�����ͳ5",
			'Draw' => 'check',
			'Caption' => '����¾��������Ǥ��ա�����ʬô��դ�Ƚ�Ǥˤ����ߤ���'
			),
		  array('Column' => "æ����ͳ1",
			'Draw' => 'check',
			'Caption' => '�象����'
			),
		  array('Column' => "æ����ͳ2",
			'Draw' => 'check',
			'Caption' => '�︡�Ԥ�������Ǥ��ա�����ʬô��դλؼ��˽���ʤ�(����)'
			),
		  array('Column' => "æ����ͳ3",
			'Draw' => 'check',
			'Caption' => '����¾�︡�Ԥ��Թ�ˤ�����Ǥ��줿'
			),

		  array('Column' => "������",
			'Draw' => 'textarea'
			)
		  ),
 );

$_lib_u_ct_record_chushi_cfg['LCOLS'] = $_lib_u_ct_record_chushi_cfg['COLS'];
$_lib_u_ct_record_chushi_cfg['DCOLS'] = $_lib_u_ct_record_chushi_cfg['COLS'];

class ct_record_chushi_edit extends simple_object_edit {
  var $debug = 1;
  function ct_record_chushi_edit($prefix, &$app, $cfg=NULL) {
    global $_lib_u_ct_record_chushi_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_ct_record_chushi_cfg;
    //_lib_u_ct_annotate_cfg(&$cfg);
    $this->app = $app;
    $this->data['����'] = $this->app->loo->CT_ObjectID;
    $this->data['����������'] = $this->app->sod->chosen();
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }

  function edit($chiken_id) {
    $db = mx_db_connect();
    
    // ID here means ���������� id.  What I really want is ����ʻ�� id.
    $stmt = 'select "ObjectID" from "�������æ��" where "Superseded" is NULL and "����������"=' . $chiken_id;
    
    $r = mx_db_fetch_single($db, $stmt);
    if(!$r) {
      //print "NO OLD RECORD_CHUSHI FOUND for chiken_id=$chiken_id";
      return $this->anew(null);
    }
    
    $this->id = $r["ObjectID"];
    $this->data = $this->fetch_data($this->id);
    $this->data['����������'] = $chiken_id;
    $this->annotate_row_data(&$this->data);
    $this->Subpick = NULL;
    $this->page = 0;
    $this->edit_tweak();
    $this->origin = $this->fetch_origin_info();
    $this->chosen = 1;
  }

  function annotate_form_data(&$data) {
    $data['����������'] = $this->app->sod->chosen();
    $data['CreatedBy'] = $this->u;
  }

}
?>
