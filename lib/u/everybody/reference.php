<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ord_module.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ooo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/oootemplate.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/pp_attr.php';

function __lib_u_everybody_reference_cfg(&$cfg) { 
  
  $cfg = array_merge
    ($cfg, array
     ('TABLE' => 'Reference',
      'COLS' => array('´µ¼Ô', 'value_blob', 'PDF','CreatedOn'),
      'LCOLS' => array(array('Column' => 'CreatedOn','Label' => 'ºîÀ®Æü»þ'),
		       array('Column' => 'DISEASE','Label' => '½ýÉÂÌ¾'),
		       array('Column' => 'REF_HOSPITAL', 'Label' => '¾Ò²ðÀèÉÂ±¡'),
		       array('Column' => 'REF_DOCTOR_NAME', 'Label' => '¾Ò²ðÀè°å»Õ'),
		       array('Column' => 'PDF',
			     'Label' => 'PDF',
			     'Draw' => 'extdocument',
			     'Option' => array('annotate' =>
					       '__lib_u_manage_oootemplate_anno'),
			     ),
		       ),
      'DCOLS' => array(array('Column' => 'CREATE_DATE', 'Label' => 'ºîÀ®Æü'),
		       array('Column' => 'REF_HOSPITAL', 'Label' => '¾Ò²ðÀèÉÂ±¡'),
		       array('Column' => 'REF_DEPT', 'Label' => '¾Ò²ðÀè²ÊÌÜ'),
		       array('Column' => 'REF_DOCTOR_NAME', 'Label' => '¾Ò²ðÀè°å»Õ'),

		       array('Column' => 'HOSPITAL_ADDR','Label' => 'ÉÂ±¡½»½ê'),
		       array('Column' => 'CORPORATION_NAME','Label' => 'Ë¡¿ÍÌ¾¾Î'),
		       array('Column' => 'HOSPITAL_NAME','Label' => 'ÉÂ±¡Ì¾¾Î'),
		       array('Column' => 'HOSPITAL_TEL','Label' => 'ÉÂ±¡ÅÅÏÃÈÖ¹æ'),
		       array('Column' => 'HOSPITAL_FAX','Label' => 'ÉÂ±¡FAXÈÖ¹æ'),
		       array('Column' => 'HOSPITAL_EMAIL','Label' => 'ÉÂ±¡ÅÅ»Ò¥á¡¼¥ë'),
		       array('Column' => 'DOCTOR_NAME','Label' => '°å»Õ'),
		       array('Column' => 'EXPECTED_DATE','Label' => '¼õ¿ÇÍ½ÄêÆü',
			     'Draw' => 'date'),
		       array('Column' => 'PATIENT_KANA','Label' => '´µ¼ÔÌ¾(¥«¥Ê)'),
		       array('Column' => 'PATIENT_NAME','Label' => '´µ¼ÔÌ¾'),
		       array('Column' => 'PATIENT_ADDR','Label' => '´µ¼Ô½»½ê'),
		       array('Column' => 'PATIENT_DOB','Label' => '´µ¼ÔÀ¸Ç¯·îÆü'),
		       array('Column' => 'PATIENT_AGE','Label' => '´µ¼ÔÇ¯Îð'),
		       array('Column' => 'PATIENT_SEX','Label' => '´µ¼ÔÀ­ÊÌ',
			     'Draw' => 'enum',
			     'Enum' => array('M' => 'ÃË',
					     'F' => '½÷',
					     NULL => 'ÉÔÌÀ')),
		       array('Column' => 'PATIENT_TEL','Label' => '´µ¼ÔÅÅÏÃÈÖ¹æ'),
		       array('Column' => 'PATIENT_MOBILE','Label' => '´µ¼Ô·ÈÂÓÈÖ¹æ'),
		       array('Column' => 'URGENT','Label' => '¶ÛµÞ¤ÎÍ­Ìµ',
			     'Draw' => 'radio', 'Enum' => array('Í­', 'Ìµ')),
		       array('Column' => 'DISEASE','Label' => '½ýÉÂÌ¾'),
		       array('Column' => 'PURPOSE','Label' => '¾Ò²ðÌÜÅª'),
		       array('Column' => 'HISTORY','Label' => '´û±ýÎò'),
		       array('Column' => 'HAS_ALLERGY','Label' => '¥¢¥ì¥ë¥®¡¼¤ÎÍ­Ìµ',
				'Draw' => 'radio', 'Enum' => array('Í­', 'Ìµ')),
		       array('Column' => 'ALLERGY','Label' => '¥¢¥ì¥ë¥®¡¼'),
		       array('Column' => 'FAMILY_HISTORY','Label' => '²ÈÂ²Îò'),
		       array('Column' => 'CONDITION','Label' => 'ÉÂ¾õ·Ð²á'),
		       array('Column' => 'TEST_RESULT','Label' => '¸¡ºº·ë²Ì', 'Option' => array('pre' => 0)),
		       array('Column' => 'TREATMENT','Label' => '¼£ÎÅ·Ð²á'),
		       array('Column' => 'RX','Label' => '¸½ºß¤Î½èÊý', 'Option' => array('pre' => 1)),
		       array('Column' => 'PDF',
			     'Label' => 'PDF',
			     'Draw' => 'extdocument',
			     'Option' => array('annotate' =>
					       '__lib_u_manage_oootemplate_anno'),
			     ),
		       ),
      'ECOLS' => array(array('Column' => 'CREATE_DATE', 'Label' => 'ºîÀ®Æü', 'Draw'=>'date', 'Page' => 0),
		       array('Column' => 'REF_HOSPITAL', 'Label' => '¾Ò²ðÀèÉÂ±¡', 'Page' => 0,
			     'Option' => array('maxlength' => 22)),
		       array('Column' => 'REF_DEPT', 'Label' => '¾Ò²ðÀè²ÊÌÜ','Page' => 0,
			     'Option' => array('maxlength' => 7)),
		       array('Column' => 'REF_DOCTOR_NAME', 'Label' => '¾Ò²ðÀè°å»Õ','Page' => 0,
			     'Option' => array('maxlength' => 7)),
		       array('Column' => 'HOSPITAL_ADDR','Label' => 'ÉÂ±¡½»½ê',
			     'Draw' => 'static', 'Page' => 0),
		       array('Column' => 'CORPORATION_NAME','Label' => 'Ë¡¿ÍÌ¾¾Î',
			     'Draw' => 'static', 'Page' => 0),
		       array('Column' => 'HOSPITAL_NAME','Label' => 'ÉÂ±¡Ì¾¾Î',
			     'Draw' => 'static', 'Page' => 0),
		       array('Column' => 'HOSPITAL_TEL','Label' => 'ÉÂ±¡ÅÅÏÃÈÖ¹æ',
			     'Draw' => 'static', 'Page' => 0),
		       array('Column' => 'HOSPITAL_FAX','Label' => 'ÉÂ±¡FAXÈÖ¹æ',
			     'Draw' => 'static', 'Page' => 0),
		       array('Column' => 'HOSPITAL_EMAIL','Label' => 'ÉÂ±¡ÅÅ»Ò¥á¡¼¥ë',
			     'Draw' => 'static', 'Page' => 0),
		       array('Column' => 'DOCTOR_NAME','Label' => '°å»Õ', 'Page' => 0),

		       array('Column' => 'URGENT','Label' => '¶ÛµÞ¤ÎÍ­Ìµ', 'Page' => 0,
			     'Draw' => 'radio', 'Enum' => array('Í­', 'Ìµ')),
		       array('Column' => 'EXPECTED_DATE','Label' => '¼õ¿ÇÍ½ÄêÆü', 'Page' => 0,
			     'Draw' => 'date'),
		       array('Column' => 'PATIENT_KANA','Label' => '´µ¼ÔÌ¾(¥«¥Ê)', 'Page' => 0),
		       array('Column' => 'PATIENT_NAME','Label' => '´µ¼ÔÌ¾', 'Page' => 0),
//0426-2012 length 34
		       array('Column' => 'PATIENT_ADDR','Label' => '´µ¼Ô½»½ê', 'Page' => 0,
				'Option' => array('maxlength' => 34,'size'=>36)),
		       array('Column' => 'PATIENT_DOB','Label' => '´µ¼ÔÀ¸Ç¯·îÆü', 'Page' => 0),
		       array('Column' => 'PATIENT_AGE','Label' => '´µ¼ÔÇ¯Îð', 'Page' => 0),
		       array('Column' => 'PATIENT_SEX','Label' => '´µ¼ÔÀ­ÊÌ', 'Page' => 0, 
			     'Draw' => 'enum',
			     'Enum' => array('M' => 'ÃË',
					     'F' => '½÷',
					     NULL => 'ÉÔÌÀ')),
		       array('Column' => 'PATIENT_TEL','Label' => '´µ¼ÔÅÅÏÃÈÖ¹æ', 'Page' => 0),
		       
		       array('Column' => 'PATIENT_MOBILE','Label' => '´µ¼Ô·ÈÂÓÈÖ¹æ', 'Page' => 0),
		       
		       array('Column' => 'DISEASE','Label' => '½ýÉÂÌ¾', 'Page' => 0,
			     'Draw' => 'textarea', 'Option' => array('cols' => 32, 'rows'=>5,
								     'maxlength' => 96)),
		       array('Column' => 'PURPOSE','Label' => '¾Ò²ðÌÜÅª', 'Page' => 0,
			     'Draw' => 'textarea', 'Option' => array('cols' => 32, 'rows'=>5,
								     'maxlength' => 96)),
		       array('Column' => 'HISTORY','Label' => '´û±ýÎò','Page' => 1,
			     'Draw' => 'textarea', 'Option' => array('cols' => 32, 'rows'=>5,
								     'maxlength' => 96)),
		       array('Column' => 'HAS_ALLERGY','Label' => '¥¢¥ì¥ë¥®¡¼¤ÎÍ­Ìµ', 'Page' => 1,
				'Draw' => 'radio', 'Enum' => array('Í­', 'Ìµ')),
		       array('Column' => 'ALLERGY','Label' => '¥¢¥ì¥ë¥®¡¼', 'Page' => 1),
		       array('Column' => 'FAMILY_HISTORY','Label' => '²ÈÂ²Îò', 'Page' => 2,
			     'Draw' => 'textarea', 'Option' => array('cols' => 34, 'rows'=>2,
								     'maxlength' => 100)),
		       array('Column' => 'CONDITION','Label' => 'ÉÂ¾õ·Ð²á','Page' => 3,
			     'Draw' => 'textarea', 'Option' => array('cols' => 63, 'rows'=>7,
								     'maxlength' => 240)),
		       array('Column' => 'TEST_RESULT','Label' => '¸¡ºº·ë²Ì','Page' => 4,
			     'Draw' => 'textarea', 'Option' => array('cols' =>63, 'rows'=>7,
								     'maxlength' => 240)),
		       array('Column' => 'TREATMENT','Label' => '¼£ÎÅ·Ð²á','Page' => 5,
			     'Draw' => 'textarea', 'Option' => array('cols' => 63, 'rows'=>7,
								     'maxlength' => 240)),
		       array('Column' => 'RX','Label' => '¸½ºß¤Î½èÊý','Page' => 6,
			     'Draw' => 'textarea', 'Option' => array('cols' => 80, 'rows'=>11,'maxlength' => 240)),
		       ),
      'ALLOW_SORT' => array('ºîÀ®Æü»þ' => array('ºîÀ®Æü»þ' => '"CreatedOn"')),
      'DEFAULT_SORT' => 'ºîÀ®Æü»þ',

      'EPAGES' => array('¾Ò²ðÀèÅù', '´û±ýÎò', '²ÈÂ²Îò', 'ÉÂ¾õ·Ð²á',
		       '¸¡ºº·ë²Ì', '¼£ÎÅ·Ð²á', '¸½ºß¤Î½èÊý')
      )
     );
  
}

class list_of_everybody_references extends list_of_ppa_objects {

  function list_of_everybody_references($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_everybody_reference_cfg(&$cfg);
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

  function row_paging_orders() {
    $paging_keys = $this->row_paging_keys();
    $paging_orders = array();
    foreach ($paging_keys as $col) {
      if ($col == 'ºîÀ®Æü»þ' ||
	  $col == '"ObjectID"')
	$paging_orders[] = 1;
      else
	$paging_orders[] = 0;
    }
    return $paging_orders;
  }
}

class everybody_reference_display extends simple_object_display {
//  var $extra_table_classes =" wrap";
  function everybody_reference_display($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_everybody_reference_cfg(&$cfg);
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

  function print_sod($template='machida.odt') {
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
      if ($params['CREATE_DATE'])
	$params['CREATE_DATE'] = mx_wareki($params['CREATE_DATE']);
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
	$id = mx_db_insert_extdocument($db, '¾Ò²ð¾õ', $bid,
				       $pt=NULL, $comment=NULL);
	// update record...
	// this is irregular design. SOD should not update db in normal case
	$stmt = 'UPDATE "Reference" SET "PDF"=' . mx_db_sql_quote($id) .
	  ' WHERE "ObjectID"=' . mx_db_sql_quote($data["ObjectID"]);
	pg_query($db, $stmt);
	$data['PDF'] = $id;
      }else{
	print "PDF¤ÎÀ¸À®¤Ë¼ºÇÔ¤·¤Þ¤·¤¿";
      }
    }
    //HACK: open window and show PDF for client-side printing
    print '
<SCRIPT LANGUAGE="JavaScript">
 window.open("/blobmedia.php/' . $data['PDF'] .
'/¾Ò²ð¾õ.pdf","","width=640,height=640");
</SCRIPT>';

  }
}

class everybody_reference_edit extends simple_object_edit {
  var $debug = 1;
  function everybody_reference_edit($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_everybody_reference_cfg(&$cfg);
    simple_object_edit::simple_object_edit
      ($prefix, &$cfg);
  }

  function anew_tweak($orig_id) {
    $this->new_annotate_row_data($this->data);
    $ptinfo = _lib_pp_attr_find($this->so_config['Patient_ObjectID'], '´µ¾ðÌä¿Ç');

    foreach($ptinfo as $e) {
//0426-2012 from arerugi to arerugiarinashi
        if ($e['Ì¾¾Î'] =='¥¢¥ì¥ë¥®¡¼Í­Ìµ'){
		
        $allergyari = $e['Â°À­ÃÍ'];
	$this->data['HAS_ALLERGY'] = $allergyari;
	}
	else if  ($e['Ì¾¾Î'] =='¥¢¥ì¥ë¥®¡¼'){
	 $allergy = $e['Â°À­ÃÍ'];
	  $this->data['ALLERGY'] = $allergy;
 	}
	   
	else{
	   
	break;
   	 }
  }
}

//0426-2012
  function annotate_row_data($data) {
    $vb = $data['value_blob'];
    $vb_array = explode('&', $vb);
    foreach($vb_array as $vb) {
      list($k, $v) = mx_form_unescape_key($vb);
      $data[$k] = $v;
    }
  }

  function new_annotate_row_data(&$data) {
    $mx_form_map = array('¸½¾ÉÉÂÎò' => 'CONDITION',
			 '¿ÇÃÇ' => 'DISEASE',
			 '½ê¸«' => 'CURRENT',
			 'Êý¿Ë' => 'TREATMENT',
			 '´û±ýÉÂÎò' => 'HISTORY',
			 '²ÈÂ²ÉÂÎò' => 'FAMILY_HISTORY',
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
    $data['HOSPITAL_EMAIL'] = $idata['HOSPITAL_EMAIL'];

    // fill doctor info
    $data['DOCTOR_NAME'] = $this->auth[2]['»áÌ¾'];

    // fill patient info
    $stmt = ('SELECT * FROM "´µ¼ÔÂæÄ¢" WHERE "ObjectID" = '.
	     mx_db_sql_quote($this->so_config['Patient_ObjectID']));
    $d = mx_db_fetch_single(mx_db_connect(), $stmt);
    $data['PATIENT_ADDR'] = '¢©'.$d['½»½ê0'].' '.$d['½»½ê1'].$d['½»½ê2'] .
      $d['½»½ê3'].$d['½»½ê4'];
    $data['PATIENT_NAME'] = $d['À«'].' '.$d['Ì¾'];
    $data['PATIENT_KANA'] = $d['¥Õ¥ê¥¬¥Ê'];
    $data['PATIENT_DOB'] = mx_wareki($d['À¸Ç¯·îÆü']);
    $data['PATIENT_SEX'] = $d['À­ÊÌ'];
    $data['PATIENT_AGE'] = mx_calc_age($d['À¸Ç¯·îÆü']);
    $data['PATIENT_TEL'] = $d['²ÃÆþÅÅÏÃ'];
    $data['PATIENT_MOBILE'] = $d['·ÈÂÓÅÅÏÃ'];

    // fill karte, Rx info
    $__everybody_reference_applist = array
      (
       'karte_module_index_info' => NULL,
       'rx_module_index_info' => array('limit' => 1),
       'test_module_index_info' => NULL,
       );
    $dbh = mx_db_connect();
    $oid = $this->so_config['Patient_ObjectID'];
    $pid = $this->so_config['´µ¼ÔID'];
    foreach ($__everybody_reference_applist as $fn => $options) {
      $result = array();
      foreach ($fn($dbh, $oid, $pid, NULL, NULL, $options) as $r) {
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
    $this->data['´µ¼Ô'] = $this->so_config['Patient_ObjectID'];
    $this->data['CreatedOn'] = date('Y-m-d h:i:s');
    return simple_object_edit::commit($force);
  }

}
////////////////////////////////////////////////////////////////

class everybody_reference_application extends per_patient_application {

  var $use_list_of_checkin = 0;
  var $use_single_pane = 0;

  function everybody_reference_application() {
    global $_mx_template_input;
    global $_mx_use_checkin_list;
    global $_mx_auto_sodsoe_setup;
    //$this->use_template = $_mx_template_input;
    $this->use_list_of_checkin = $_mx_use_checkin_list;
    //$this->use_auto_sod_soe_setup = $_mx_auto_sodsoe_setup;
    $this->use_auto_sod_soe_setup = 0;
//07-02-2012
    $this->use_printer2 = 1;
    per_patient_application::per_patient_application();
  }

  function print_sod() {
    $this->sod->print_sod();
  }

  function list_of_objects($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    return new list_of_everybody_references($prefix, $cfg);
  }

  function object_display($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    return new everybody_reference_display($prefix, $cfg);
  }

  function object_edit($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    $soe = new everybody_reference_edit($prefix, $cfg);
    $soe->u = $this->u;
    $soe->auth = $this->auth;
    return $soe;
  }

}

?>
