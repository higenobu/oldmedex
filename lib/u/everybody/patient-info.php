<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/pp_attr.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/patient-basic.php';

$__pdf_params = NULL;
function _lib_u_everybody_patient_info_tweak_template($self, $template) {
    global $mx_authenticate_current_user;
    global $__pdf_params;

    foreach(array("システム.%s" => mx_get_install_data(),
		  "ユーザ.%s" => get_mx_authenticate_user($mx_authenticate_current_user),
		  "患者台帳.%s" => mx_draw_patientinfo_get_data($self->so_config['Patient_ObjectID'])) as $label => $v_list)
	    {
	      foreach($v_list as $k => $v) {
		if(is_null($v)) {
		  $__pdf_params[sprintf($label, $k)] = '';
		  $v = '&nbsp;';
		}else
		  $__pdf_params[sprintf($label, $k)] = $v;
		$template = str_replace(sprintf("@@$label@@", $k), $v, $template);
	      }
	    }
    $template = str_replace("@@記録日@@", '', $template);
    return $template;
}

class list_of_patientinfos extends pp_attr_los {

  var $group = array('生活保護', '保護者1', '保護者2', '紹介元', '患情問診');

}

class patientinfo_display extends pp_attr_sod {

  var $group = array('生活保護', '保護者1', '保護者2', '紹介元', '患情問診');

  function tweak_template($template) {
    return  _lib_u_everybody_patient_info_tweak_template($this, $template);
  }

  function draw_body_template($data, $hdata, $dcols) {
    global $_mx_resource_dir;
    global $__pdf_params;
    $template = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/templates/' . $this->so_config['D_TEMPLATE']);
    $template = $this->tweak_template($template);
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
	$this->draw_body_atom($desc, $data, FALSE);
	$v = ob_get_contents();
	ob_end_clean();
	$template = str_replace($gc, $v, $template);
	$v = str_replace('<br />', '', $v);
	$v = str_replace('<div>', '', $v);
	$v = str_replace('</div>', '', $v);
	$__pdf_params[$col] = $v;

    }
    print $template;
    if($this->want_pdf)
      $this->print_sod2($__pdf_params);
  } 

  function print_sod() {
    $this->want_pdf = 1;
  }

  function print_sod2($params) {
    $db = mx_db_connect();
    $oid = $this->id;
    $template = 'patient_basic_information.ods';
    // read DB

    $rand = rand(0,100000000);
    $pdf_path = sprintf("/tmp/%s_%d.pdf", $template, $rand);
    $params['PDF_PATH'] = $pdf_path;
    $params['TEMPLATE_DIR'] = $_SERVER['DOCUMENT_ROOT'] . '/templates/';
    $params['TEMPLATE'] = $template;
    $params['BODY'] = '\n';

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
 
class patientinfo_edit extends pp_attr_soe {
  
  var $group = array('生活保護', '保護者1', '保護者2', '紹介元', '患情問診');
  
  function tweak_template($template) {
    return  _lib_u_everybody_patient_info_tweak_template($this, $template);
  }
  
}
  ?>
