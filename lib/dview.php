<?php // -*- coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';
class DView
{
  function DView($patient_id) {
    $ptoid = mx_find_patient_by_patient_id($patient_id);
    $this->pt = mx_draw_patientinfo_get_data($ptoid);

    // fix patient id
    $this->pt['患者ID'] = trim($this->pt['患者ID']);

    // fix kana
    $this->pt['フリガナ'] = mb_convert_kana($this->pt['フリガナ'], 'hk');

    // fix dob
    if ($this->pt['生年月日'] == '不明')
      $this->pt['生年月日'] = '';
    else
      $this->pt['生年月日'] = str_replace('-', '/', $this->pt['生年月日']);

    // fix sex
    if ($this->pt['性別'] == '男')
      $this->pt['性別'] = '1';
    else if ($this->pt['性別'] == '女')
      $this->pt['性別'] = '2';
    else
      $this->pt['性別'] = '';
  }
  
  function put_file($fname, $content)
  {
    header("content-type: text/dview; charset=shift_jis;");
    header("Content-Disposition: attachment; filename=\"$fname\"");
    print $content . "\r\n";
  }
  
  function pt_file_name() {
    $tm = date('YmdHis');
    return "PT${tm}.txt";
  }
  
  function register_patient()  {
    $content = array(
		     $this->pt["患者ID"],
		     $this->pt["フリガナ"],
		     $this->pt["氏名"],
		     $this->pt["加入電話"],
		     $this->pt["生年月日"],
		     $this->pt["性別"]
		     );
    $this->put_file($this->pt_file_name(),
		    implode(',', $content));
  }
  
  function view_patient() {
    $this->put_file('Viewid.txt',$this->pt["患者ID"]);
  }
}
?>