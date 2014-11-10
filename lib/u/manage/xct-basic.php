<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';


$_lib_u_manage_xct_basic_cfg = array
(
 'TABLE' => 'xctorder',
 'COLS' => array(
	"orderdate" ,
 "plandate",
 "procdate" ,
  "����" ,
  "teikikubun",
  "xctkubun" ,
  "techname",
  "techid" ,
  "bui1" ,
  "bui2" ,
 "bui3" ,
 "bui4" ,
 "bui5",
"memo1",
"memo2" ,
"memo3" ,
"memo4" ,
"memo5" ,
"memo11" ,
"memo21" ,
"memo31" ,
"memo41" ,
"memo51",
"memo12" ,
"memo22" ,
"memo32" ,
"memo42" ,
"memo52",
"syoken1" ,
"syoken2" ,
"syoken3" ,
"syoken4" ,
"syoken5" ,
"techsyoken" ,
"drsyoken" ,
"shiji",
"gishi",

"proof" 
 ),

 
 'LCOLS' => array("orderdate", "plandate", "procdate", "����", "bui1", "bui2","bui3",, "bui4", "bui5"),

 
 'DCOLS' => array("orderdate", "plandate", "procdate", "����", "bui1", "bui2","bui3",, "bui4", "bui5"),

 'ECOLS' => array("orderdate", "plandate", "procdate", "����", "bui1", "bui2","bui3",, "bui4", "bui5"),
		 
 );



class list_of_xct_basics extends list_of_simple_objects {
  function list_of_xct_basics($prefix, $cfg=array()) {
    global $_lib_u_manage_xct_basic_cfg;
    $cfg = array_merge($_lib_u_manage_xct_basic_cfg, $cfg);
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }

  function enum_list($desc) {
	  if ($desc['Column'] == '���ԥޡ���') {
		  return mx_dbenum_xctmark('=');
	  }
	  return $desc['Enum'];
  }

}

class xct_basic_display extends simple_object_display {
  function xct_basic_display($prefix, $cfg=array()) {
    global $_lib_u_manage_xct_basic_cfg;
    $cfg = array_merge($_lib_u_manage_xct_basic_cfg, $cfg);
    simple_object_display::simple_object_display($prefix, $cfg);
  }
  
  
  
}

class xct_basic_edit extends simple_object_edit {
  function xct_basic_edit($prefix, $cfg=array()) {
    global $_lib_u_manage_xct_basic_cfg;
    $cfg = array_merge($_lib_u_manage_xct_basic_cfg, $cfg);
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }

  


  function trim_data(&$row) {
    foreach ($row as $k => $v) {
      if (! is_null($v))
	$row[$k] = trim($v);
    }
  }

  function annotate_row_data(&$data) { $this->trim_data(&$data); }
  function annotate_form_data(&$data) {
	  foreach (array('��','̾','�եꥬ��') as $c) {
		  if (is_null($data[$c]))
			  continue;
		  $data[$c] = trim(mx_xlate_jzspace($data[$c]));
	  }
	  $fn = $data['��'];
	  $gn = $data['̾'];

	  if ($gn == '' && $fn != '') {
		  $m = array();
		  if (preg_match('/^(.*?) +(.*)$/', $fn, &$m)) {
			  $data['��'] = $m[1];
			  $data['̾'] = $m[2];
		  }
	  }
    simple_object_edit::annotate_form_data(&$data);
    $this->trim_data(&$data);
  }

  
  function try_commit(&$db) {
    global $mx_authenticate_current_user;

   
  }

}
?>
