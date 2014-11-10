<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';

function __lib_u_manage_oootemplate_anno(&$desc, &$data)
{
	$desc['Option']['filename_prefix'] ='';
}

$_lib_u_manage_oootemplate_cfg = array
(
 'TABLE' => 'OooTemplate',
 'COLS' => array('name', 'Template'),
 'LCOLS' => array(array('Column' => 'name',
			'Label' => '文書名')
		  ),
 'DCOLS' => array( array('Column' => 'name',
			 'Label' => '文書名'),
		  array('Column' => 'Template',
			 'Draw' => 'extdocument',
			 'Option' => array('annotate' =>
					   '__lib_u_manage_oootemplate_anno')
			 )
		   ),
 'ECOLS' => array(array('Column' => 'name',
			 'Label' => '文書名'),
		  array('Column' => 'Template',
			'Draw' => 'extdocument',
			'Extdocument' => '紹介状')),

 'ALLOW_SORT' => 1,

 );

class list_of_oootemplates extends list_of_simple_objects {
  var $debug = 1;
  function list_of_oootemplates($prefix, $config=NULL) {
    global $_lib_u_manage_oootemplate_cfg;
    if (is_null($config))
      $config = $_lib_u_manage_oootemplate_cfg;
    list_of_simple_objects::list_of_simple_objects
      ($prefix, $config);
  }

}

class oootemplate_display extends simple_object_display {

  function oootemplate_display($prefix, $config=NULL) {
    global $_lib_u_manage_oootemplate_cfg;
    if (is_null($config))
      $config = $_lib_u_manage_oootemplate_cfg;
    simple_object_display::simple_object_display
      ($prefix, $config);
  }

}

class oootemplate_edit extends simple_object_edit {
  var $debug = 1;
  function oootemplate_edit($prefix, $config=NULL) {
    global $_lib_u_manage_oootemplate_cfg;
    if (is_null($config))
      $config = $_lib_u_manage_oootemplate_cfg;
    simple_object_edit::simple_object_edit
      ($prefix, $config);
  }
}
?>