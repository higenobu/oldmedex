<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
//------------------
// args are EUC. Mandatory key & values are:
// $params['PDF_PATH'] = path of the PDF to be generated
// $params['TEMPLATE_DIR'] = $_SERVER['DOCUMENT_ROOT'] . '/templates/';
// $params['TEMPLATE']  shokaijo.odt, shohosen.ods, etc
//
// pls copy template files to TEMPLATE_DIR for now.
// cache mechanism is to be implemented.


function expand_params(&$params) {
  foreach($params as $k => $v) {
    if(strpos($k,':') > 0) {
      list($name, $fmt) = split(':', $k);
      $ftdv = sprintf($fmt, $v);
      $sl = strlen($ftdv);
      for($i=0; $i<$sl; $i++) {
	$params[$name .'_'. $i] = substr($ftdv, $sl - $i -1,1); 
      }
    }
  }
}



function ooo_print_pdf($params, $script=null) {
  global $_mx_pdfgen_cmd;
//0917-2011 test
$_mx_pdfgen_cmd= "/s/medex/farm/php/tools/pdfgen.py";
//

  // preprocess number, multi-line params //
  expand_params($params);
  $descriptorspec = array(
			  0 => array("pipe", "r"),  // stdin 
			  1 => array("pipe", "w"),  // stdout 
			  2 => array("pipe", "w")   // stderr
			  );
  if($script)
    $process = proc_open($script, $descriptorspec, $pipes);
  else
 $process = proc_open($_mx_pdfgen_cmd, $descriptorspec, $pipes);
 //  $process = proc_open("/s/medex/farm/php/tools/shohousen_pdfgen.py", $descriptorspec, $pipes);  
  if (!is_resource($process))
    return -1;

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

  return $txt;

}
//
function ooo_print_pdf3($params, $script=null) {
  global $_mx_pdfgen_cmd;

//

  // preprocess number, multi-line params //
  expand_params($params);
  $descriptorspec = array(
			  0 => array("pipe", "r"),  // stdin 
			  1 => array("pipe", "w"),  // stdout 
			  2 => array("pipe", "w")   // stderr
			  );
  if($script)
    $process = proc_open($script, $descriptorspec, $pipes);
  else
 //  1117-2011   $process = proc_open($_mx_pdfgen_cmd, $descriptorspec, $pipes);
   $process = proc_open("/s/medex/farm/php/tools/shohousen_pdfgen.py", $descriptorspec, $pipes);  
  if (!is_resource($process))
    return -1;

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

  return $txt;

}

//
function ooo_print_pdf2($params, $script=null) {
  global $_mx_pdfgen_cmd;
//0618-2011
  // $pdfgen4_cmd= $_SERVER['DOCUMENT_ROOT'] . "/" ."../tools/pdfgen4.py";
$pdfgen4_cmd1= "/s/medex/farm/php/tools/pdfgen4.py";
  // preprocess number, multi-line params //
  expand_params($params);
  $descriptorspec = array(
			  0 => array("pipe", "r"),  // stdin 
			  1 => array("pipe", "w"),  // stdout 
			  2 => array("pipe", "w")   // stderr
			  );
  if($script)
    $process = proc_open($script, $descriptorspec, $pipes);
  else
    $process = proc_open($pdfgen4_cmd1, $descriptorspec, $pipes);
  
  if (!is_resource($process))
    return -1;

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

  return $txt;

}
function print_ocr($params) {
  global $_mx_ocrgen_cmd;
  
  // preprocess number, multi-line params //
  expand_params($params);
  $descriptorspec = array(
			  0 => array("pipe", "r"),  // stdin 
			  1 => array("pipe", "w"),  // stdout 
			  2 => array("file", "/tmp/ocr-error-output.txt", "w") // file
			  );
  
  #$process = proc_open("$_mx_ocrgen_cmd | ps2pdf - -| sed -e 's/MediaBox \[%d+ %d+ %d+ %d+\]/MediaBox \[0 0 637.795 864.5669\]/' >/tmp/srl.pdf", $descriptorspec, $pipes);
  $process = proc_open($_mx_ocrgen_cmd, $descriptorspec, $pipes);
  
  if (!is_resource($process))
    return -1;

  // pass arguments
  foreach($params as $k => $v) {
    $ev = urlencode(mb_convert_encoding($v, 'UTF-8', 'eucJP-win'));
    fwrite($pipes[0], sprintf("%s=%s\n", urlencode($k), $ev));
  }
  fclose($pipes[0]);
  fclose($pipes[1]);
  return proc_close($process);

}

?>
