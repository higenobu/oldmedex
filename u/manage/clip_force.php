<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
$clip_force_path = '/var/tmp/clip/clip_force_request';

class toucher {
  function toucher($prefix) {
    global $clip_force_path;
    $this->prefix = $prefix;
    $this->chosen_ = NULL;
    $this->errs = array();
    $this->success = NULL;
    if(array_key_exists($this->prefix . 'date', $_REQUEST))
      $content = $_REQUEST[$this->prefix . 'date'];

    if($content && array_key_exists($this->prefix . 'force', $_REQUEST)) {
      $fp = fopen($clip_force_path, 'w');
      if(is_null($fp)) {
	$this->success = -1;
      }else{
	fwrite($fp, $content);
	fclose($fp);
	chmod($clip_force_path, 0666);
	$this->success = 1;
      }
    }
  }
  function draw() {
    global $clip_force_path;
    if(file_exists($clip_force_path))
      $content = file_get_contents($clip_force_path);

    if(is_null($this->success)) {
      if($content) {
	printf("検査日[%s]の強制送信リクエストがまだ完了していません<br>", $content);
	print "しばらく待ってからやり直してください<br><br>";
	print '<input type=submit value="更新">';
      }else{
	print "現在強制送信処理待ちのリクエストはありません。<br><br>";
	print "強制送信する検査日を指定してください  ";
	mx_formi_date($this->prefix . 'date',
		      $force_date, array('Option' => array()));
	print "<br>";
	print "<br>";
	
	print '<input type="submit" name="' .$this->prefix . 'force" value="強制送信">';
      }
    }else if($this->success == 1){
      printf("<br><br><br><br>検査日[%s]の強制送信をリクエストしました。<br>数分以内に送信されます。", $content);
      print '<br><br><input type=submit value="更新">';
    }else if($this->success == -1) {
      printf("<br><br><br><br>検査日[%s]の強制送信が失敗しました。", $content);
      print '<br><br><input type=submit value="更新">';
    }
  }
  
  function chosen() {return $this->chosen_;}
  function changed() {return 1;}
  function lost_selection() { return 0; }
}
class clip_force_application extends single_table_application {
  function list_of_objects($prefix) {
    $loo = new toucher($prefix);
    $loo->application = &$this;
    return $loo;
      
  }
  function allow_new() {
    return NULL;
  }
}
$main = new clip_force_application();
$main->main();


?>
