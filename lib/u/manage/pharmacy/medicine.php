<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/pharmacy/rxenum.php';

////////////////////////////////////////////////////////////////
function _lib_u_manage_pharmacy_medicine_cfg_setup() {
  global $_lib_u_manage_pharmacy_medicine_cfg;
  global $_lib_u_manage_pharmacy_rxenum_unit_cfg;
  global $__uiconfig_ms_qbe_enum_medicine;

  $choice =  $__uiconfig_ms_qbe_enum_medicine;
  unset($choice['']);
  unset($choice['U']);
  unset($choice['N']);

  $choice_edit = $choice; // copy of array
  foreach(array_keys($choice_edit) as $k)
    if(strlen($k) > 1)
      unset($choice_edit[$k]);

  $kubun_enum = array('��' => '��',
		      '��' => '��',
		'��' => '��' );
$label_enum = array('Y' => '��',
		      'N' => '����');
  $cols = array(
		"����ֹ�",
		"�������ֹ�",
		"��Ҽ������ֹ�",
		"Ĵ�����ֹ�",
		"ʪή���ֹ�",
		"�ʣ��Υ�����",
		"���������ܰ����ʥ�����",
		"���̰����ʥ�����",
		"�쥻�ץ��Ż����������ƥॳ���ɡʣ���",
		"�쥻�ץ��Ż����������ƥॳ���ɡʣ���",
		"��̾��",
		"����̾",
		"�쥻�ץ��Ż����������ƥ������̾",
		"����ñ��",
		"��������",
		"����ñ�̿�",
		"����ñ��ñ��",
		"�������̿�",
		"��������ñ��",
		"��ʬ",
		"��¤���",
		"������",
		"������ʬ",
		"����ǯ����",
		"��������",
		"�±����Ѱ�����̾",
		"�±���������ñ��ñ��",
		"�±����ѥ쥻���󥳡���",
		"�±����ѥ�٥��װ���",
"kananame"
		);

  $dcols = array(
		array('Column' =>"����ֹ�",
		      'Draw' => 'static'
		      ),
		array('Column' =>"��ʬ",
		      'Label' => "��ʬ",
		      ),
		"���������ܰ����ʥ�����",
		
		"�쥻�ץ��Ż����������ƥ������̾",
		"����ñ��ñ��",
		array('Column' => "��������",
		      'Label' => "��������",
		      'Draw' => 'enum',
		      'Enum' => $choice),
		array('Column' => "�±����Ѱ�����̾",
		      'Label' => "�±����Ѹ���̾��"),
		array('Column' => "�±���������ñ��ñ��",
		      'Draw' => 'subpick',
		      'Subpick' => array
		      ('Class' => 'list_of_rxenum_units',
		       'Message' => '����ñ�̤����ꤹ��',
		       'Config' => $_lib_u_manage_pharmacy_rxenum_unit_cfg,
		       'ListID' => array('ObjectID','����ñ��'),
		       'Allow_NULL' => 0,
		       )),

		"�±����ѥ쥻���󥳡���",
		array('Column' => "�±����ѥ�٥��װ���",
		      'Draw' => 'enum',
		      'Enum' => $label_enum,
		      ),
//1230-2012
array('Column' => "kananame",
		      'Label' => '��������̾',
		     
		      ),
		);

  $ecols = array(
		array('Column' =>"����ֹ�",
		      'Draw' => 'static'
		      ),
		"���������ܰ����ʥ�����",
		"�쥻�ץ��Ż����������ƥ������̾",
		"����ñ��ñ��",
		array('Column' => "��������",
		      'Label' => "��������",
		      'Draw' => 'enum',
		      'Enum' => $choice_edit),
		array('Column' => "�±����Ѱ�����̾",
		      'Label' => "�±����Ѹ���̾��"),
		array('Column' => "�±���������ñ��ñ��",
		      'Draw' => 'subpick',
		      'Subpick' => array
		      ('Class' => 'list_of_rxenum_units',
		       'Message' => '����ñ�̤����ꤹ��',
		       'Config' => $_lib_u_manage_pharmacy_rxenum_unit_cfg,
		       'ListID' => array('ObjectID','����ñ��'),
		       'Allow_NULL' => 0,
		       )),
		array('Column' => "��ʬ",
		      'Draw' => 'enum',
		      'Enum' => $kubun_enum
		      ),
		"�±����ѥ쥻���󥳡���",
		array('Column' => "�±����ѥ�٥��װ���",
		      'Draw' => 'enum',
		      'Enum' => $label_enum,
		      ),
//1230-2012
array('Column' => "kananame",
		      'Label' => '��������̾',
		     
		      ),
		array('Column' => "�������ֹ�", 'Draw' => NULL),
		array('Column' => "��Ҽ������ֹ�", 'Draw' => NULL),
		array('Column' => "Ĵ�����ֹ�", 'Draw' => NULL),
		array('Column' => "ʪή���ֹ�", 'Draw' => NULL),
		array('Column' => "�ʣ��Υ�����", 'Draw' => NULL),
		array('Column' => "���̰����ʥ�����", 'Draw' => NULL),
		array('Column' => "�쥻�ץ��Ż����������ƥॳ���ɡʣ���",
		      'Draw' => NULL),
		array('Column' => "�쥻�ץ��Ż����������ƥॳ���ɡʣ���",
		      'Draw' => NULL),
		array('Column' => "��̾��", 'Draw' => NULL),
		array('Column' => "����̾", 'Draw' => NULL),
		array('Column' => "����ñ��", 'Draw' => NULL),
		array('Column' => "��������", 'Draw' => NULL),
		array('Column' => "����ñ�̿�", 'Draw' => NULL),
		array('Column' => "�������̿�", 'Draw' => NULL),
		array('Column' => "��������ñ��", 'Draw' => NULL),

		array('Column' => "��¤���", 'Draw' => NULL),
		array('Column' => "������", 'Draw' => NULL),
		array('Column' => "������ʬ", 'Draw' => NULL),
		array('Column' => "����ǯ����", 'Draw' => NULL),
		);

    $c = array(
	       TABLE => 'Medis�����ʥޥ�����',
	       COLS => $cols,
	       LCOLS => array("�쥻�ץ��Ż����������ƥ������̾",
			      array('Column' => "�±����Ѱ�����̾",
				    'Label' => "�±����Ѹ���̾��",
				    ),
			"��ʬ",
			     "�±���������ñ��ñ��",
			     "�±����ѥ쥻���󥳡���",
		      array('Column' => "��������",
				    'Label' => "��������",
				    'Draw' => 'enum',
				    'Enum' => $choice),
			      array('Column' => "�±����ѥ�٥��װ���",
				    'Draw' => 'enum',
				    'Enum' => $label_enum,
				    ),
//1230-2012
			array('Column' => "kananame",
		      'Label' => '��������̾',
		      ),
			      ),
	       DCOLS => $dcols,
	       ECOLS => $ecols,
	       LCHOICE => $choice,
	       X_LCHOICE_FORCE_DROPDOWN => 1,
	       ALLOW_SORT => 1,
	       ENABLE_QBE => 1
	       );
    $_lib_u_manage_pharmacy_medicine_cfg = $c;
}

_lib_u_manage_pharmacy_medicine_cfg_setup();

class list_of_medicine extends list_of_simple_objects {
  function list_of_medicine($prefix, $cfg=NULL) {
    global $_lib_u_manage_pharmacy_medicine_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_pharmacy_medicine_cfg;
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }

  function const_where($qn, $k) {
    if (mb_strlen($k, 'EUC-JP') == 1) {
	$wadd = "$qn = " . mx_db_sql_quote($k);
    }
    else {
      $sa = array();
      for ($ix = 0; $ix < mb_strlen($k, 'EUC-JP'); $ix++)
	$sa[] = mx_db_sql_quote(mb_substr($k, $ix, 1, 'EUC-JP'));
      $w = ('IN ( ' .
	    implode(', ', $sa) .
	    ' )');
      $wadd = "$qn $w";
    }
    return $wadd;
  }

  function base_fetch_stmt_1($i) {
    $base = $this->so_config['STMT'];
    if ($i != '') {
      $base .= " AND " . $this->const_where('"��������"', $i);
    }
    return $base;
  }
}

class medicine_display extends simple_object_display {
  function medicine_display($prefix, $cfg=NULL) {
    global $_lib_u_manage_pharmacy_medicine_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_pharmacy_medicine_cfg;
    simple_object_display::simple_object_display($prefix, $cfg);
  }

}

class medicine_edit extends simple_object_edit {
  function medicine_edit($prefix, $cfg=NULL) {
    global $_lib_u_manage_pharmacy_medicine_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_pharmacy_medicine_cfg;
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }

  function annotate_form_data(&$data) {
    if (trim($data["����ñ��ñ��"]) == '') {
      $data["����ñ��ñ��"] = trim($data["�±���������ñ��ñ��"]);
    }
    if (trim($data["�쥻�ץ��Ż����������ƥ������̾"]) == '') {
      $data["�쥻�ץ��Ż����������ƥ������̾"] =
	trim($data["�±����Ѱ�����̾"]);
    }
  }

  function _validate() {
    $d =& $this->data;

    $bad = 0;
    // Ugh.
    if (! $this->id) {
      $db = mx_db_connect();
      $r = mx_db_fetch_single($db,
	  "SELECT MAX(\"����ֹ�\") AS x FROM \"Medis�����ʥޥ�����\"
           WHERE \"����ֹ�\" LIKE 'S%'");
      if (!is_null($r)) {
	$new_num = sprintf("S%012d", intval(substr($r['x'],1))+1);
      }else{
	$new_num = 'S000000000001';
      }
      $d['����ֹ�'] = $new_num;
    }

    if (simple_object_edit::_validate() != 'ok')
	    $bad++;

    if (trim($d['�±����Ѱ�����̾']) == '') {
      $this->err("�±����Ѱ�����̾����ꤷ�Ƥ�������\n");
      $bad++;
    }
    if (trim($d['�±���������ñ��ñ��']) == '') {
      $this->err("�±���������ñ��ñ�̤���ꤷ�Ƥ�������\n");
      $bad++;
    }
    if (! $bad)
      return 'ok';
  }
  
}

?>
