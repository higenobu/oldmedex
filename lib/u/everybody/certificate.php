<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ord_module.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ooo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/oootemplate.php';

function __lib_u_everybody_certificate_cfg(&$cfg) { 
  
  $cfg = array_merge
    ($cfg, array
     ('TABLE' => 'Certificate',
      'COLS' => array('¥µº‘', 'value_blob', 'PDF','CreatedOn'),
      'LCOLS' => array(array('Column' => 'CreatedOn','Label' => '∫Ó¿Æ∆¸ª˛'),
		       array('Column' => 'DISEASE','Label' => 'Ω˝…¬Ãæ'),
		       array('Column' => 'PDF',
			     'Label' => 'PDF',
			     'Draw' => 'extdocument',
			     'Option' => array('annotate' =>
					       '__lib_u_manage_oootemplate_anno'),
			     ),
		       ),
      'DCOLS' => array(array('Column' => 'CREATE_DATE', 'Label' => '∫Ó¿Æ∆¸'),
		       array('Column' => 'CORPORATION_NAME','Label' => 'À°øÕÃææŒ'),
		       array('Column' => 'HOSPITAL_NAME','Label' => '…¬±°ÃææŒ'),
		       array('Column' => 'HOSPITAL_ADDR','Label' => '…¬±°ΩªΩÍ'),
		       array('Column' => 'HOSPITAL_TEL','Label' => '…¬±°≈≈œ√»÷πÊ'),
		       array('Column' => 'HOSPITAL_FAX','Label' => '…¬±°FAX»÷πÊ'),
		       array('Column' => 'DOCTOR_NAME','Label' => '∞Âª’'),
		       array('Column' => 'PATIENT_NAME','Label' => '¥µº‘Ãæ'),
		       array('Column' => 'PATIENT_ADDR','Label' => '¥µº‘ΩªΩÍ'),
		       array('Column' => 'PATIENT_DOB','Label' => '¥µº‘¿∏«Ø∑Ó∆¸'),
		       array('Column' => 'PATIENT_AGE','Label' => '¥µº‘«ØŒ'),

		       array('Column' => 'DISEASE','Label' => 'Ω˝…¬Ãæ', 'Option' => array('pre' => 1)),
		       array('Column' => 'COMMENT','Label' => '•≥•·•Û•»', 'Option' => array('pre' => 1)),
		       array('Column' => 'PDF',
			     'Label' => 'PDF',
			     'Draw' => 'extdocument',
			     'Option' => array('annotate' =>
					       '__lib_u_manage_oootemplate_anno'),
			     ),
		       ),
      'ECOLS' => array(array('Column' => 'CREATE_DATE', 'Label' => '∫Ó¿Æ∆¸', 'Page' => 0),
		       array('Column' => 'HOSPITAL_ADDR','Label' => '…¬±°ΩªΩÍ',
			     'Draw' => 'static', 'Page' => 0),
		       array('Column' => 'CORPORATION_NAME','Label' => 'À°øÕÃææŒ',
			     'Draw' => 'static', 'Page' => 0),
		       array('Column' => 'HOSPITAL_NAME','Label' => '…¬±°ÃææŒ',
			     'Draw' => 'static', 'Page' => 0),
		       array('Column' => 'HOSPITAL_ADDR','Label' => '…¬±°ΩªΩÍ',
			     'Draw' => 'static', 'Page' => 0),
		       array('Column' => 'HOSPITAL_TEL','Label' => '…¬±°≈≈œ√»÷πÊ',
			     'Draw' => 'static', 'Page' => 0),
		       array('Column' => 'HOSPITAL_FAX','Label' => '…¬±°FAX»÷πÊ',
			     'Draw' => 'static', 'Page' => 0),
		       array('Column' => 'DOCTOR_NAME','Label' => '∞Âª’', 'Page' => 0),
		       array('Column' => 'PATIENT_NAME','Label' => '¥µº‘Ãæ', 'Page' => 0),
		       array('Column' => 'PATIENT_ADDR','Label' => '¥µº‘ΩªΩÍ', 'Page' => 0),
		       array('Column' => 'PATIENT_DOB','Label' => '¥µº‘¿∏«Ø∑Ó∆¸', 'Page' => 0),
		       array('Column' => 'PATIENT_AGE','Label' => '¥µº‘«ØŒ', 'Page' => 0),
		       
		       array('Column' => 'DISEASE','Label' => 'Ω˝…¬Ãæ', 'Page' => 0,
			     'Draw' => 'textarea', 'Option' => array('cols' => 60, 'rows'=>6)),
		       array('Column' => 'COMMENT','Label' => '•≥•·•Û•»','Page' => 0,
			     'Draw' => 'textarea', 'Option' => array('cols' => 60, 'rows'=>6)),
		       ),
      'ALLOW_SORT' => array('∫Ó¿Æ∆¸ª˛' => array('∫Ó¿Æ∆¸ª˛' => '"CreatedOn"')),
      'DEFAULT_SORT' => '∫Ó¿Æ∆¸ª˛',

      'EPAGES' => array('ø«√«ΩÒ')
      )
     );
  
}

class list_of_everybody_certificate extends list_of_ppa_objects {

  function list_of_everybody_certificate($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_everybody_certificate_cfg(&$cfg);
    list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
  }
    
  function annotate_row_data($data) {
    $vb = $data['value_blob'];
    $vb_array = explode('&', $vb);
    foreach($vb_array as $vb) {
      list($k, $v) = mx_form_unescape_key($vb);
      $data[$k] = $v;
    }
  }
}

class everybody_certificate_display extends simple_object_display {

  function everybody_certificate_display($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_everybody_certificate_cfg(&$cfg);
    simple_object_display::simple_object_display
      ($prefix, $cfg);
  }

  function annotate_row_data($data) {
    $vb = $data['value_blob'];
    $vb_array = explode('&', $vb);
    foreach($vb_array as $vb) {
      list($k, $v) = mx_form_unescape_key($vb);
      $data[$k] = $v;
    }
  }

  function prepare_template($path, $template) {
    // template chache mechanism. still work in progress
    if(file_exists($path . $template))
      return;
    // ID?
    $id=103;
    // get extdocument
    $db = mx_db_connect();
    $content = NULL;
    mx_db_fetch_extmedia($db, $content, $id);
    //---- write a template file
    $handler = fopen($pdf_path, 'wb');
    fwrite($handler, $content, count($content));
    fclose($handler);
    //NEEDSWORK: error handling
  }

  function print_sod($template='shindan_t.odt') {
    $data = $this->prepare_data_for_draw();

    // check if PDF has been generated
    if(!$data['PDF']) {
      // generate PDF and 
      $params = array();
      foreach ($this->so_config['DCOLS'] as $row) {
	$col = '';
	if(is_array($row)) 
	  $col = $row['Column'];
	else
	  $col = $row;

	$v = (is_array($row) && is_array($row['Enum'])) ?
		  $row['Enum'][$data[$col]] : $data[$col];

	$params[$col] = $v;
      }
      $rand = rand(0,100000000);
      $pdf_path = sprintf("/tmp/%s_%d.pdf", $template, $rand);
      $params['PDF_PATH'] = $pdf_path;
      $params['TEMPLATE_DIR'] = $_SERVER['DOCUMENT_ROOT'] . '/templates/';
      $params['TEMPLATE'] = $template;
      $this->prepare_template($params['TEMPLATE_DIR'], $params['TEMPLATE']);
      $tmp_fname = ooo_print_pdf($params);
      if(file_exists($pdf_path)) {
	//---- read pdf file
	$handler = fopen($pdf_path, 'rb');
	$content = fread($handler, filesize($pdf_path));
	fclose($handler);
	
	//---- store into db
	$db = mx_db_connect();
	$bid = mx_db_insert_blobmedia($db, 'application/pdf', $content);
	$id = mx_db_insert_extdocument($db, 'ø«√«ΩÒ', $bid,
				       $pt=NULL, $comment=NULL);
	// update record...
	// this is irregular design. SOD should not update db in normal case
	$stmt = 'UPDATE "Reference" SET "PDF"=' . mx_db_sql_quote($id) .
	  ' WHERE "ObjectID"=' . mx_db_sql_quote($data["ObjectID"]);
	pg_query($db, $stmt);
	$data['PDF'] = $id;
      }else{
	print "PDF§Œ¿∏¿Æ§Àº∫«‘§∑§ﬁ§∑§ø";
      }
    }
    //HACK: open window and show PDF for client-side printing
    print '
<SCRIPT LANGUAGE="JavaScript">
 window.open("/blobmedia.php/' . $data['PDF'] .
'/ø«√«ΩÒ.pdf","","width=640,height=640");
</SCRIPT>';

  }
}

class everybody_certificate_edit extends simple_object_edit {
  var $debug = 1;
  function everybody_certificate_edit($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_everybody_certificate_cfg(&$cfg);
    simple_object_edit::simple_object_edit
      ($prefix, &$cfg);
  }

  function anew_tweak($orig_id) {
    $this->new_annotate_row_data($this->data);
  }

  function annotate_row_data($data) {
    $vb = $data['value_blob'];
    $vb_array = explode('&', $vb);
    foreach($vb_array as $vb) {
      list($k, $v) = mx_form_unescape_key($vb);
      $data[$k] = $v;
    }
  }

  function new_annotate_row_data(&$data) {
    $mx_form_map = array('∏Ωæ……¬ŒÚ' => 'CONDITION',
			 'ø«√«' => 'DISEASE',
			 'ΩÍ∏´' => 'CURRENT',
			 ' ˝øÀ' => 'TREATMENT',
			 '¥˚±˝…¬ŒÚ' => 'HISTORY',
			 '≤»¬≤…¬ŒÚ' => 'FAMILY_HISTORY',
			 'RX' => 'RX',
			 'TEST_RESULT' => 'TEST_RESULT'
			 );

    // fill hospital info
    $idata = mx_get_install_data();
    $data['HOSPITAL_ADDR'] = $idata['HOSPITAL_ADDR'];
    $data['HOSPITAL_NAME'] = $idata['HOSPITAL_NAME'];
    $data['HOSPITAL_TEL'] = $idata['HOSPITAL_TEL'];
    $data['CORPORATION_NAME'] = $idata['CORPORATION_NAME'];
    $data['HOSPITAL_FAX'] = $idata['HOSPITAL_FAX'];

    // fill doctor info
    $data['DOCTOR_NAME'] = $this->auth[2]['ª·Ãæ'];

    // fill patient info
    $stmt = ('SELECT * FROM "¥µº‘¬Êƒ¢" WHERE "ObjectID" = '.
	     mx_db_sql_quote($this->so_config['Patient_ObjectID']));
    $d = mx_db_fetch_single(mx_db_connect(), $stmt);
    $data['PATIENT_ADDR'] = '¢©'.$d['ΩªΩÍ0'].' '.$d['ΩªΩÍ1'].$d['ΩªΩÍ2'] .
      $d['ΩªΩÍ3'].$d['ΩªΩÍ4'];
    $data['PATIENT_NAME'] = $d['¿´'].' '.$d['Ãæ'];
    $data['PATIENT_KANA'] = $d['•’•Í•¨• '];
    $data['PATIENT_DOB'] = mx_wareki($d['¿∏«Ø∑Ó∆¸']);
    $data['PATIENT_SEX'] = $d['¿≠ Ã'];
    $data['PATIENT_AGE'] = mx_calc_age($d['¿∏«Ø∑Ó∆¸']);
    $data['PATIENT_TEL'] = $d['≤√∆˛≈≈œ√']."\n∑»¬” ".$d['∑»¬”≈≈œ√'];

    // fill karte, Rx info
    $__everybody_certificate_applist = array
      (
       'karte_module_index_info',
       'rx_module_index_info',
       'injection_module_index_info',
       'test_module_index_info',
       );
    $dbh = mx_db_connect();
    $oid = $this->so_config['Patient_ObjectID'];
    $pid = $this->so_config['¥µº‘ID'];
    $result = array();
    foreach ($__everybody_certificate_applist as $fn) {
      $result = array();
      foreach ($fn($dbh, $oid, $pid, NULL, NULL) as $r) {
	$result[] = $r;
      }
      // karte needs to be in reverse order
      if($fn == 'karte_module_index_info')
	$result = array_reverse($result);

      if(count($result) > 0) {
	foreach($result as $r) {
	  $vb_array = explode('&', $r['value_blob']);
	  foreach($vb_array as $vb) {
	    list($k, $v) = mx_form_unescape_key($vb);
	    // find most recent data for each interested columns
	    if($k == 'RX' || $k == 'TEST_RESULT')
	      $data[$mx_form_map[$k]] .= "\n" . $v;
	    elseif(array_key_exists($k, $mx_form_map) and (is_null($data[$mx_form_map[$k]]) or $data[$mx_form_map[$k]] == ''))
	      $data[$mx_form_map[$k]] = $v;
	  }
	}
      }
    }
  }


  function commit($force=NULL) {
    // serialize ecols 
    $vb_array = array();
    foreach($this->so_config['ECOLS'] as $ecol) {
      $k = is_array($ecol) ? $ecol['Column'] : $ecol;
      $vb_array[] = mx_form_escape_key( array($k, $this->data[$k]) );
    }
    $this->data['value_blob'] = implode('&', $vb_array);
    $this->data['¥µº‘'] = $this->so_config['Patient_ObjectID'];
    $this->data['CreatedOn'] = date('Y-m-d h:i:s');
    return simple_object_edit::commit($force);
  }

}
////////////////////////////////////////////////////////////////

class everybody_certificate_application extends per_patient_application {

  var $use_list_of_checkin = 0;

  function everybody_certificate_application() {
    global $_mx_template_input;
    global $_mx_use_checkin_list;
    global $_mx_auto_sodsoe_setup;
    //$this->use_template = $_mx_template_input;
    $this->use_list_of_checkin = $_mx_use_checkin_list;
    $this->use_auto_sod_soe_setup = $_mx_auto_sodsoe_setup;
//0702-2012
    $this->use_printer2 = 1;
    per_patient_application::per_patient_application();
  }

  function print_sod() {
    $this->sod->print_sod();
  }

  function list_of_objects($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    return new list_of_everybody_certificate($prefix, $cfg);
  }

  function object_display($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    return new everybody_certificate_display($prefix, $cfg);
  }

  function object_edit($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    $soe = new everybody_certificate_edit($prefix, $cfg);
    $soe->u = $this->u;
    $soe->auth = $this->auth;
    return $soe;
  }

}

?>
