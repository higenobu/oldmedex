<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/enum.php';

$sched_type = array(' ','Ⱦ��', '��', '��', '��', 'ͭ', '¾',
		    'HK', 'YK', '��', '��', '��',
		    'TK', 'NK', 'FK', 'RK',
		    'WY', 'WT', 'WF', 'WN', 'WR', 'WH',
		    'RY', 'RT', 'RF', 'RN', 'RH',
		    'NY', 'NT', 'NF');

$sched_types = array();
foreach($sched_type as $s)
  $sched_types[$s] = $s;

// -------- list of employee config
$list_of_employees_ns_cfg = $_lib_u_manage_employee_cfg;
// $list_of_employees_ns_cfg['HSTMT'] .= '
 //     AND C."����" in ' . enum_nurse_cat_sql();

$list_of_employees_ns_cfg['STMT'] =
  $list_of_employees_ns_cfg['HSTMT'] . ' AND E."Superseded" IS NULL';

$cfg = array
(
 'TABLE' => 'employee_schedule',
 'COLS' => array('employee_id','target_month')
);

$cfg['ECOLS'] = array(array('Column'=>'employee_id', 'Draw' => NULL),
	   array('Column' => 'employee',
		 'Label' => '����',
		 'Draw' => 'Subpick',
		 'Subpick' => array
		 ('Class' => 'list_of_employees',
		  'Message' => '���ο��������ꤹ��',
		  'Config' => $list_of_employees_ns_cfg,
		  'ListID' => array('ObjectID', '��̾'),
		  'Allow_NULL' => 0,
		  'ObjectColumn' => 'employee_id')
		 ),
	   array('Column' => 'target_month',
		 'Label' => '�оݷ�',
		 'Draw' => 'date',
		 'Option'=> array('to-months' => 1)
		 )
		      );
$cfg['DCOLS'] =array(array('Column'=>'employee_id', 'Label' => '����'),
			  
		     
		      array('Column' => 'target_month',
			    'Label' => '�оݷ�',
			    'Draw' => 'date',
			    'Option'=> array('to-months' => 1)
			    )
		      );
$cfg['LCOLS'] = array("����", "����","��̾",
			  array('Column'=>'employee_id', 'Label' => '����'),
		     
		      array('Column' => 'target_month',
			    'Label' => '�оݷ�',
			    'Draw' => 'date',
			    'Option'=> array('to-months' => 1)
			    )
		      );

$cfg['ICOLS'] = $cfg['COLS'];

for($i=1; $i <=31; $i++) {
  $d = sprintf("d%02d", $i);
  $cfg['COLS'][] = $d;
  $cfg['ICOLS'][] = $d;
  $dd =  array('Column' => $d,
		 'Label' => sprintf("%d��", $i),
		 'Draw' => 'enum',
		 'Enum' => $sched_types
		 );
  $cfg['LCOLS'][] = $dd;
  $cfg['DCOLS'][] = $dd;
  $cfg['ECOLS'][] = $dd;
}

$_lib_u_manage_employee_schedule_cfg = $cfg;

function _annotate_row_data(&$it, &$row) {
  $db = mx_db_connect();
  $stmt = ('SELECT E."ObjectID", E.userid, E."����ID", E."��", E."̾", '.
	   'E."�եꥬ��", C."����", R."����", '.
	   'D."��ʬ��", D."��ʬ��1", D."��ʬ��2", D."��ʬ��" ' .
	   'FROM "������Ģ" AS E '.
	   'JOIN "�������ɽ" AS C '.
	   'ON E."Superseded" IS NULL AND '.
	   'C."Superseded" IS NULL AND E."����" = C."ObjectID" '.
	   'JOIN "���̰���ɽ" AS R '.
	   'ON R."Superseded" IS NULL AND E."����" = R."ObjectID" '.
	   'JOIN "�������ɽ" AS D '.
	   'ON D."Superseded" IS NULL AND E."����" = D."ObjectID" '
	   );

  $umapdb = pg_fetch_all(pg_query($db, $stmt));
  if (is_null($it->umap) && $umapdb) {
    $it->umap = array();
    foreach ($umapdb as $d) {
      $it->umap[$d['ObjectID']] = $d;
    }
  }

  $row['����'] = $it->umap[$row['employee_id']]['����ID'];
  $row['����'] = $it->umap[$row['employee_id']]['����'];
  $row['����'] = $it->umap[$row['employee_id']]['��ʬ��2'];
 $row['emp1'] = $it->umap[$row['employee_id']]['��'];
 $row['emp2'] = $it->umap[$row['employee_id']]['̾'];
 $row['��̾'] = $row['emp1'].$row['emp2'];
}


class list_of_employee_schedules extends list_of_simple_objects {
  function list_of_employee_schedules($prefix, $cfg=NULL) {
    global $_lib_u_manage_employee_schedule_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_employee_schedule_cfg;
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }

  function annotate_row_data(&$row) {
    _annotate_row_data(&$this, &$row);
  }

}

class employee_schedule_display extends simple_object_display {
  function employee_schedule_display($prefix, $cfg=NULL) {
    global $_lib_u_manage_employee_schedule_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_employee_schedule_cfg;
    simple_object_display::simple_object_display($prefix, $cfg);
  }
  function annotate_row_data(&$row) {
    _annotate_row_data(&$this, &$row);
  }

}

class employee_schedule_edit extends simple_object_edit {
  function employee_schedule_edit($prefix, $cfg=NULL) {
    global $_lib_u_manage_employee_schedule_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_employee_schedule_cfg;
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }
}
?>
