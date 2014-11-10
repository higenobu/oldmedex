<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/therapist/norder-2.php';

function __lib_u_therapist_nexec_list_cfg(&$cfg) {
  $cfg = array_merge
    ($cfg, array
     ('TABLE' => '��ϼ»ܵ�Ͽ',
      'ALLOW_SORT' => array
      ('ObjectID' => array('�»ܵ�ϿID' => 'X."ObjectID"'),
       '����' => array('����' => 'X."����"'),
       '�»���ˡ��' => array('�»���ˡ��' => '(T."��" || T."̾")')),
      'DEFAULT_SORT' => '����',
      'LCOLS' => array(array('Column' => 'ObjectID',
			     'Label' => '�»ܵ�ϿID'),
		       '����', '�»���ˡ��'),
      'ENABLE_QBE' =>
      array(array('Column' => '����', 'Compare' => 'X."����"',
		  'Draw' => 'text'),
	    array('Column' => '�»���ˡ��',
		  'Compare' => '(T."��" || T."̾")',
		  'Draw' => 'text')),
      'UNIQ_ID' => 'X."ObjectID"',
      ));

  $stmt_head = '
SELECT X."ObjectID",
       X."����",
       (T."��" || T."̾") as "�»���ˡ��"
FROM "��ϼ»ܵ�Ͽ" as X
JOIN "��Ͻ����" as RX ON X."��Ͻ����" = RX."ID"
LEFT JOIN "������Ģ" as T ON T."ObjectID" = X."�»���ˡ��"
';

  $cfg['HSTMT'] = $stmt_head . 'WHERE NULL IS NULL';
  $cfg['STMT'] = $stmt_head . 'WHERE X."Superseded" IS NULL';
}

class list_of_rehab_nexecs extends list_of_simple_objects {

  var $default_row_per_page = 4;
  var $debug = 1;

  function list_of_rehab_nexecs($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    $this->loo = new list_of_rehab_norders($prefix . 'loo-', $cfg);
    $this->sod = new rehab_norder_display($prefix . 'sod-', $cfg);

    if ($this->loo->changed() && $this->loo->chosen())
	    $this->sod->reset($this->loo->chosen());
    if (array_key_exists($prefix. 'OHistory', $_REQUEST))
	    $this->sod->history($_REQUEST[$prefix . 'OHistory']); 

    __lib_u_therapist_nexec_list_cfg(&$cfg);
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);

    if (!$this->sod->chosen())
	    return; 
    $this->Rx_ObjectID = $this->sod->chosen();
  }

  function reset($id) {
	  if (is_null($id)) {
		  $this->loo->reset($id);
		  $this->sod->reset($id);
		  $this->Rx_ObjectID = $this->sod->chosen();
	  }
	  list_of_simple_objects::reset($id);
  }

  function draw() {
	  $this->loo->draw();
	  if (!$this->Rx_ObjectID)
		  return;

	  print "<br />\n";
	  mx_titlespan('[��������]');
	  $this->sod->draw();
	  $sod_history = $this->sod->history();
	  $oh = $this->prefix . 'OHistory';
	  if (($sod_history & 3) == 3)
		  mx_formi_submit($oh, 'Prev',
				  mx_img_url('history.png'),'����');
	  else {
		  if (($sod_history & 5) == 5)
			  mx_formi_submit($oh, 'Prev',
					  mx_img_url('history-prev.png'),
					  '����');
		  if (($sod_history & 9) == 9)
			  mx_formi_submit($oh, 'Next',
					  mx_img_url('history-next.png'),
					  '���');
	  }
	  print "<br />\n";
	  mx_titlespan('[��ϼ»ܵ�Ͽ]');
	  return list_of_simple_objects::draw();
  }

  function base_fetch_stmt_0() {
    return (list_of_simple_objects::base_fetch_stmt_0() .
	    ' AND RX."ObjectID" = ' .
	    mx_db_sql_quote($this->Rx_ObjectID));
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
?>
