<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';

$_lib_u_ct_record_haikei_cfg = array('TABLE' => '�����ط�',
	     'COLS' => array("Ʊ��ǯ����",
			     "ǯ��",
			     "��Ĺ",
			     "�ν�",
			     "������",
			     "�ķл���",
			     "�ҵ�Ŧ����",
			     "�ҵ�Ŧ�нѼ�",
			     "�ҵ�Ŧ��ǯ��",
			     "���㺸Ŧ����",
			     "���㺸Ŧ��ǯ��",
			     "���㱦Ŧ����",
			     "���㱦Ŧ��ǯ��",
			     "������",
			     "������(1)",
			     "������(2)",
			     "������(3)",
			     "������(4)",
			     "������(5)",
			     "������(6)",
			     "������(7)",
			     "������(8)",
			     "������(9)",
			     "������(10)",
			     "��ʻ��",
			     "��ʻ��(1)",
			     "��ʻ��(2)",
			     "��ʻ��(3)",
			     "��ʻ��(4)",
			     "��ʻ��(5)",
			     "��ʻ��(6)",
			     "��ʻ��(7)",
			     "��ʻ��(8)",
			     "��ʻ��(9)",
			     "��ʻ��(10)",
			     "HBs����",
			     "HBs����������",
			     "����",
			     "���Ǹ�����",
			     "HCV����",
			     "HCV���θ�����",
			     "HIV",
			     "HIV������",
			     "��Ϳ����",
			     "���޶�ʬ",
			     "��Ϳ��",
			     "������",
			     ),
	     'ICOLS' => array("Ʊ��ǯ����",
			     "ǯ��",
			     "��Ĺ",
			     "�ν�",
			     "������",
			     "�ķл���",
			     "�ҵ�Ŧ����",
			     "�ҵ�Ŧ�нѼ�",
			     "�ҵ�Ŧ��ǯ��",
			     "���㺸Ŧ����",
			     "���㺸Ŧ��ǯ��",
			     "���㱦Ŧ����",
			     "���㱦Ŧ��ǯ��",
			     "������",
			     "������(1)",
			     "������(2)",
			     "������(3)",
			     "������(4)",
			     "������(5)",
			     "������(6)",
			     "������(7)",
			     "������(8)",
			     "������(9)",
			     "������(10)",
			     "��ʻ��",
			     "��ʻ��(1)",
			     "��ʻ��(2)",
			     "��ʻ��(3)",
			     "��ʻ��(4)",
			     "��ʻ��(5)",
			     "��ʻ��(6)",
			     "��ʻ��(7)",
			     "��ʻ��(8)",
			     "��ʻ��(9)",
			     "��ʻ��(10)",
			     "HBs����",
			     "HBs����������",
			     "����",
			     "���Ǹ�����",
			     "HCV����",
			     "HCV���θ�����",
			     "HIV",
			     "HIV������",
			     "��Ϳ����",
			     "���޶�ʬ",
			     "��Ϳ��",
			     "������",
			     "����������",
			     ),
	     'ECOLS' => array(
			      array('Column' => "Ʊ��ǯ����",
				    'Draw' => 'date'
				    ),
			      "ǯ��",
			      "��Ĺ",
			      "�ν�",
			      "������",
			      array('Column' => "�ķл���",
				    'Draw' => 'date'
				    ),
			      array('Column' => "�ҵ�Ŧ����",
				    'Draw' => 'radio',
				    'Enum' => array(NULL => '̤����',
						    1 => '�ʤ�',
						    2 => '����'),
				    ),
			      "�ҵ�Ŧ�нѼ�",
			      array('Column' => "�ҵ�Ŧ��ǯ��",
				    'Draw' => 'date'
				    ),
			      array('Column' => "���㺸Ŧ����",
				    'Draw' => 'radio',
				    'Enum' => array(NULL => '̤����',
						    1 => '�ʤ�',
						    2 => '����'),
				    ),
			      array('Column' => "���㺸Ŧ��ǯ��",
				    'Draw' => 'date',
				    ),
			     "���㱦Ŧ����",
			      array('Column' => "���㱦Ŧ��ǯ��",
				    'Draw' => 'date',
				    ),
			      array('Column' => "������",
				    'Draw' => 'radio', 
				    'Enum' => array(NULL => '̤����',
						    1 => '�ʤ�',
						    2 => '����'),
				    ),
			     "������(1)",
			     "������(2)",
			     "������(3)",
			     "������(4)",
			     "������(5)",
			     "������(6)",
			     "������(7)",
			     "������(8)",
			     "������(9)",
			     "������(10)",
			      array('Column' => "��ʻ��",
				    'Draw' => 'radio', 
				    'Enum' => array(NULL => '̤����',
						    1 => '�ʤ�',
						    2 => '����'),
				    ),
			     "��ʻ��(1)",
			     "��ʻ��(2)",
			     "��ʻ��(3)",
			     "��ʻ��(4)",
			     "��ʻ��(5)",
			     "��ʻ��(6)",
			     "��ʻ��(7)",
			     "��ʻ��(8)",
			     "��ʻ��(9)",
			     "��ʻ��(10)",
			      array('Column' => "HBs����",
				    'Draw' => 'radio', 
				    'Enum' => array(NULL => '̤����',
						    1 => '����',
						    2 => '����'),
				    ),
			      array('Column' => "HBs����������",
				    'Draw' => 'date'
				    ),
			      array('Column' => "����",
				    'Draw' => 'radio', 
				    'Enum' => array(NULL => '̤����',
						    1 => '����',
						    2 => '����'),
				    ),
			      
			      array('Column' => "���Ǹ�����",
				    'Draw' => 'date'
				    ),
			      array('Column' => "HCV����",
				    'Draw' => 'radio', 
				    'Enum' => array(NULL => '̤����',
						    1 => '����',
						    2 => '����'),
				    ),
			      
			      array('Column' => "HCV���θ�����",
				    'Draw' => 'date'
				    ),
			      array('Column' => "HIV",
				    'Draw' => 'radio', 
				    'Enum' => array(NULL => '̤����',
						    1 => '����',
						    2 => '����'),
				    ),
			      
			     array('Column' => "HIV������",
				    'Draw' => 'date'
				    ),
			      
			     array('Column' => "��Ϳ����",
				   'Draw' => 'datetime'
				   ),
			     array('Column' => "���޶�ʬ",
				   'Draw' => 'radio',
				   'Enum' => array(NULL => '̤����',
						   1 => '����',
						   2 => '�ץ饻��'
						   ),
				   ),
			     "��Ϳ��",
			     "������",
			     ),

	     );
$_lib_u_ct_record_haikei_cfg['LCOLS'] = array('Ʊ��ǯ����');
$_lib_u_ct_record_haikei_cfg['DCOLS'] = $_lib_u_ct_record_haikei_cfg['COLS'];

class ct_record_haikei_edit extends simple_object_edit {
  var $debug = 1;
  function ct_record_haikei_edit($prefix, &$app, $cfg=NULL) {
    global $_lib_u_ct_record_haikei_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_ct_record_haikei_cfg;
    //_lib_u_ct_annotate_cfg(&$cfg);
    $this->app = $app;
    $this->data['����'] = $this->app->loo->CT_ObjectID;
    $this->data['����������'] = $this->app->sod->chosen();
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }

  function edit($chiken_id) {
    $db = mx_db_connect();
    
    // ID here means ���������� id.  What I really want is ����ʻ�� id.
    $stmt = 'select "ObjectID" from "�����ط�" where "Superseded" is NULL and "����������"=' . $chiken_id;
    
    $r = mx_db_fetch_single($db, $stmt);
    if(!$r) {
      //print "NO OLD RECORD_HAIKEI FOUND for chiken_id=$chiken_id";
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
