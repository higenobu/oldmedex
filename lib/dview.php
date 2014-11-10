<?php // -*- coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';
class DView
{
  function DView($patient_id) {
    $ptoid = mx_find_patient_by_patient_id($patient_id);
    $this->pt = mx_draw_patientinfo_get_data($ptoid);

    // fix patient id
    $this->pt['����ID'] = trim($this->pt['����ID']);

    // fix kana
    $this->pt['�եꥬ��'] = mb_convert_kana($this->pt['�եꥬ��'], 'hk');

    // fix dob
    if ($this->pt['��ǯ����'] == '����')
      $this->pt['��ǯ����'] = '';
    else
      $this->pt['��ǯ����'] = str_replace('-', '/', $this->pt['��ǯ����']);

    // fix sex
    if ($this->pt['����'] == '��')
      $this->pt['����'] = '1';
    else if ($this->pt['����'] == '��')
      $this->pt['����'] = '2';
    else
      $this->pt['����'] = '';
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
		     $this->pt["����ID"],
		     $this->pt["�եꥬ��"],
		     $this->pt["��̾"],
		     $this->pt["��������"],
		     $this->pt["��ǯ����"],
		     $this->pt["����"]
		     );
    $this->put_file($this->pt_file_name(),
		    implode(',', $content));
  }
  
  function view_patient() {
    $this->put_file('Viewid.txt',$this->pt["����ID"]);
  }
}
?>