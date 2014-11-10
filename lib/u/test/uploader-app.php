<?php // -*- mode: php; coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';

$_test_labs = array();

class test_uploader { // extends nottin'

  function test_uploader($prefix) {
    global $_mx_test_result_uploader;
    $this->prefix = $prefix;
    $this->chosen_ = NULL;
    $this->errs = array();
    $this->success = NULL;
    if (array_key_exists($this->prefix . 'show', $_REQUEST)) {
      $file = $_FILES[$this->prefix . 'file'];
      if(!$file)
	$this->errs[] = "ファイルを指定してください";

      if(!$this->errs) {
	$desc = array(
		      0 => array("pipe", "r"),  // stdin 
		      1 => array("pipe", "w"),  // stdout 
		      2 => array("pipe", "w")   // stderr
		      );
        $spath = $_SERVER['DOCUMENT_ROOT'] .
                             $_mx_test_result_uploader . " " .
                             escapeshellarg($file['tmp_name']);

	$process = proc_open($spath,
			     $desc,
			     $pipes);
	if( !is_resource($process) ) {
	  $this->errs[] = "スクリプトの呼び出しに失敗しました";
	  $this->chosen_ = NULL;
	}else{
	  fclose($pipes[0]);
	  while (!feof($pipes[2]))
	    $txt .= fread($pipes[2], 8192);
	  proc_close($process);
	  if($txt) {
	    $txt = mb_convert_encoding($txt, 'eucJP-win', 'UTF-8');
	    $this->errs[] = "<pre>" . $txt . "</pre>";
	    $this->chosen_ = NULL;
	  }else{
	    $this->success = "アップロードしました。";
	  }
	}
      }
    }
  }


  function changed() { return 1; }

  function chosen() {
    return $this->chosen_;
  }

  function draw() {
    print "<br>";
    if ($this->errs) {
      foreach ($this->errs as $msg) {
	print "$msg<br>";
      }
      print "<br>\n";
    }

    if($this->success) {
      print $this->success;
      print "<br>";
    }


    $file = $_FILE[$this->prefix . 'file'];
    $store = $_REQUEST[$this->prefix . 'store'];
    if ($store != 1 && $store != 2)
      $store = 1;

    print "検査結果ファイルを指定してください。<br>";

    print "<br>";
    mx_formi_upload($this->prefix . 'file', $file);
    mx_formi_submit($this->prefix . 'show', '送信');
    mx_formi_hidden('Print', 1);
  }

  function lost_selection() { return 0; }

}

class test_uploader_application extends single_table_application {
  var $use_upload = 1;
  var $use_print = 1;

  function list_of_objects($prefix) {
    $loo = new test_uploader($prefix);
    $loo->application = &$this;
    return $loo;
  }

  function allow_new() {
    return NULL;
  }
}

?>
