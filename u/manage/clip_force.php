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
	printf("������[%s]�ζ��������ꥯ�����Ȥ��ޤ���λ���Ƥ��ޤ���<br>", $content);
	print "���Ф餯�ԤäƤ�����ľ���Ƥ�������<br><br>";
	print '<input type=submit value="����">';
      }else{
	print "���߶������������Ԥ��Υꥯ�����ȤϤ���ޤ���<br><br>";
	print "�����������븡��������ꤷ�Ƥ�������  ";
	mx_formi_date($this->prefix . 'date',
		      $force_date, array('Option' => array()));
	print "<br>";
	print "<br>";
	
	print '<input type="submit" name="' .$this->prefix . 'force" value="��������">';
      }
    }else if($this->success == 1){
      printf("<br><br><br><br>������[%s]�ζ���������ꥯ�����Ȥ��ޤ�����<br>��ʬ�������������ޤ���", $content);
      print '<br><br><input type=submit value="����">';
    }else if($this->success == -1) {
      printf("<br><br><br><br>������[%s]�ζ������������Ԥ��ޤ�����", $content);
      print '<br><br><input type=submit value="����">';
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
