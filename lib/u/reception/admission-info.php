<?php // -*- mode: php; coding: euc-japan -*-
class list_of_admissioninfos extends list_of_ppa_objects
{
  function row_paging_orders() {
    $paging_keys = $this->row_paging_keys();
    $paging_orders = array();
    foreach ($paging_keys as $col) {
      $paging_orders[] = (($col == 'ObjectID') ? -1 : 0);
    }
    return $paging_orders;
  }
}

class admissioninfo_display extends simple_object_display
{
  function print_sod() {
    $this->want_pdf = 1;
  }

  function draw() {
    simple_object_display::draw();
    if ($this->want_pdf)
      $this->print_sod2();
  }

  function draw_body_2($data, $hdata, $dcols) {
    $this->pdf_params = NULL;
    foreach ($dcols as $desc) {
      if($desc['Draw'] == 'group_head')
         continue;
	$col = $desc['Column'];
	$gc = "@@$col@@";
	$pat = "/@@$col:(.*?)@@/";
	$m = array();
	if(preg_match($pat, $template, &$m)) {
	  $opt = explode(':', $m[1]);
	  foreach($opt as $x) {
	    list($k, $v) = explode('=', $x);
	    switch($k) {
	    case 'draw':
	      $desc['Draw'] = $v;
	      break;
	    case 'option':
	      if($desc['Draw'] == 'icd10')
      	          $desc['Option'] = array('disease' => $v,
		  		          'add_id' => 1);
	    }
	  }
	  $gc = $m[0];
	}
	
	ob_start();
	if (is_null($data[$col]))
	  $v = '';
	else{
	  $this->draw_body_atom($desc, $data, FALSE);
	  $v = ob_get_contents();
	}
	ob_end_clean();
	$template = str_replace($gc, $v, $template);
	$v = str_replace('<br />', '', $v);
	$v = str_replace('<div>', '', $v);
	$v = str_replace('</div>', '', $v);
	$this->pdf_params[$col] = $v;
    }
  }

  function print_sod2() {
    $params = $this->pdf_params;
    $pt = mx_draw_patientinfo_get_data($this->so_config['Patient_ObjectID']);
    $dr = mx_find_dr_for_patient($this->so_config['Patient_ObjectID']);
    $template = 'admission_info.ods';
    // read DB

    $rand = rand(0,100000000);
    $pdf_path = sprintf("/tmp/%s_%d.pdf", $template, $rand);
    $params['PDF_PATH'] = $pdf_path;
    $params['TEMPLATE_DIR'] = $_SERVER['DOCUMENT_ROOT'] . '/templates/';
    $params['TEMPLATE'] = $template;
    $params['BODY'] = '\n';

    // installation data
    $idata = mx_get_install_data();
    $params['HOSPITAL_NAME'] = $idata['HOSPITAL_NAME'];
    $params['HOSPITAL_ADDR'] = $idata['HOSPITAL_ADDR'];
    $params['HOSPITAL_TEL'] = $idata['HOSPITAL_TEL'];
    $params['CORPORATION_NAME'] = $idata['CORPORATION_NAME'];

    // patient data
    $params['PATIENT_ID'] = $pt['患者ID'];
    $params['PATIENT_NAME'] = $pt['氏名'];
    $params['PATIENT_KANA'] = $pt['フリガナ'];
    $params['PATIENT_SEX'] = $pt['性別'];
    $params['PATIENT_DOB'] = $pt['生年月日'];
    $params['PATIENT_AGE'] = mx_calc_age($pt['生年月日']);
    $params['PATIENT_PHONE'] = $pt['加入電話'];
    $params['PATIENT_CELL'] = $pt['携帯電話'];
    $zip = $pt['住所0'];
    if (strlen($pt['住所0']) > 3)
      $zip = substr($pt['住所0'], 0, 3) . '-' . substr($pt['住所0'], 3, 4);
    $params['PATIENT_ADDRESS'] = $zip.' '.$pt['住所1'].$pt['住所2'].$pt['住所3'].$pt['住所4'];
    $params['ADMISSION_DATE'] = $params['admitted_on'];


    // doctor
    $params['DOCTOR'] = $dr[0]['姓名'];

    // RPC 
    $descriptorspec = array(
			  0 => array("pipe", "r"),  // stdin 
			  1 => array("pipe", "w"),  // stdout 
			  2 => array("pipe", "w")   // stderr
			  );
    $process = proc_open('../../../tools/pdfgen2.py', $descriptorspec, $pipes);
  
    if (!is_resource($process)) {
      print "OOOが開けませんでした";
      return -1;
    }

    // pass arguments
    foreach($params as $k => $v) {
      $ek = urlencode(mb_convert_encoding($k, 'UTF-8', 'eucJP-win'));
      $ev = urlencode(mb_convert_encoding($v, 'UTF-8', 'eucJP-win'));
      fwrite($pipes[0], sprintf("%s=%s\n", urlencode($ek), $ev));
    }
    fclose($pipes[0]);
    //$txt = stream_get_contents($pipe[2]);
    while (!feof($pipes[2])) {
      $txt .= fread($pipes[2], 8192);
    }
    proc_close($process);

    if($txt) {
      print $txt;
      return;
    }
    
    if(file_exists($pdf_path)) {
      //---- read pdf file
      $handler = fopen($pdf_path, 'rb');
      $content = fread($handler, filesize($pdf_path));
      fclose($handler);
      //unlink($pdf_path);

      //---- store into db
      $db = mx_db_connect();
      $bid = mx_db_insert_blobmedia($db, 'application/pdf', $content);
      $type = 'PDF';
      $id = mx_db_insert_extdocument($db, $type, $bid,
				     $pt=NULL, $comment=NULL);
      //HACK: open window and show PDF for client-side printing
      print '
<SCRIPT LANGUAGE="JavaScript">
 window.open("/blobmedia.php/' . $id .
	'/generated.pdf","","width=640,height=640");
</SCRIPT>';
    
    }else{
      print "PDFの生成に失敗しました";
    }
  }

}

class admissioninfo_edit extends simple_object_edit
{
  function anew_tweak($orig_id) {
    $this->data['created_on'] = date('Y-m-d');
    $a = $this->so_config['AUTH'][2];
    $this->data['written_by'] = $a['姓'] . $a['名'];
  }
  function commit($force=NULL) {
    $this->data['患者'] = $this->so_config['Patient_ObjectID'];
    $this->data['CreatedOn'] = date('Y-m-d h:i:s');
    return simple_object_edit::commit($force);
  }
}
?>
