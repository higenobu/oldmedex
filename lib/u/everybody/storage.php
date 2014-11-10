<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';

function __lib_u_everybody_storage_anno(&$desc, &$data)
{
  $desc['Option']['filename'] = $data['filename'];
}

function __lib_u_everybody_storage_cfg(&$cfg) { 
  $cfg = array_merge
    (
     $cfg, array
     (
      'TABLE' => "extdocument_index",
      'COLS' => array("CreatedOn", "title", "extdocument", "filename", "患者"),
      'LCOLS' => array(array('Column' => 'CreatedOn',
			     'Label' => 'アップロード日時',
			     ),
		       array('Column' => 'title',
			     'Label' => 'タイトル'
			     ),
		       array('Column' => 'extdocument',
			     'Draw' => 'extdocument',
			     'Label' => '文書ファイル',
			     ),
		       ),

      'DCOLS' => array(array('Column' => 'CreatedOn',
			     'Label' => 'アップロード日時',
			     ),
		       array('Column' => 'title',
			     'Label' => 'タイトル'
			     ),
		       array('Column' => 'extdocument',
			     'Draw' => 'extdocument',
			     'Label' => '文書ファイル',
			     'Option' => array
			     ('annotate' =>
			      '__lib_u_everybody_storage_anno',
			      'download' => 1,
			      ),
			     ),
		       ),
      'ECOLS' => array(array('Column' => 'title',
			     'Label' => 'タイトル',
			     'Option' => array('validate' => 'nonnull'),
			     ),
		       array('Column' => 'extdocument',
			     'Label' => '文書ファイル',
			     'Draw' => 'extdocument',
			     'Extdocument' => '不明'
			     ),
		       ),
      )
     );
}

class list_of_everybody_storages extends list_of_ppa_objects {
  function list_of_everybody_storages($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_everybody_storage_cfg(&$cfg);
    list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
  }
}

class everybody_storage_display extends simple_object_display {
  function everybody_storage_display($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_everybody_storage_cfg(&$cfg);
    simple_object_display::simple_object_display($prefix, $cfg);
  }
}
class everybody_storage_edit extends simple_object_edit {
  function everybody_storage_edit($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_everybody_storage_cfg(&$cfg);
    // check extension and update cfg
    $f = $prefix . mx_form_encode_name('extdocument.blobmedia');
    if ($_FILES[$f]) {
      $n = explode('.', $_FILES[$f]['name']);
      if(is_array($n))
	$ext = $n[count($n) - 1];
      else
	$ext = $n;
      //get generic mx_doctype for the extension
      $dt_stmt = "SELECT extension, label_string from mx_doctype
                   WHERE \"Superseded\" is NULL AND generic =1
                  ORDER BY extension";
      $sth = pg_query(mx_db_connect(), $dt_stmt);
      $dt_data = pg_fetch_all($sth);
      $ext_types = array();
      foreach($dt_data as $row)
	$ext_types[$row['extension']] = $row['label_string'];

      if($ext_types['.' . $ext])
	$cfg['ECOLS'][1]['Extdocument']=$ext_types['.' . $ext];
    }
    simple_object_edit::simple_object_edit($prefix, &$cfg);
  }

  function annotate_form_data(&$data) {
    $f = $this->prefix . mx_form_encode_name('extdocument.blobmedia');
    $data['filename'] = $_FILES[$f]['name'];
  }

  function commit($force=NULL) {
    //NEEDSWORK: need to set application id, and app_use data?
    // they are meant for programmartical use...aren't they?
    $this->data['患者'] = $this->so_config['Patient_ObjectID'];
    $this->data['CreatedOn'] = date('Y-m-d h:i:s');
    return simple_object_edit::commit($force);
  }
}


class everybody_storage_application extends per_patient_application {
  var $use_upload = 1;
  function everybody_storage_application() {
    global $_mx_template_input;
    global $_mx_use_checkin_list;
    global $_mx_auto_sodsoe_setup;
    $this->use_auto_sod_soe_setup = $_mx_auto_sodsoe_setup;
    per_patient_application::per_patient_application();
  }

  function list_of_objects($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    return new list_of_everybody_storages($prefix, $cfg);
  }

  function object_display($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    return new everybody_storage_display($prefix, $cfg);
  }

  function object_edit($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    $soe = new everybody_storage_edit($prefix, $cfg);
    $soe->u = $this->u;
    $soe->auth = $this->auth;
    return $soe;
  }
}

?>
