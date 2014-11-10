<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/diseasepick.php';
mx_html_head('ICD10����');

print '<form name="icd10form" onsubmit="return false">';
$dp = new diseasepick('icd10-');
if($dp->chosen_data()) {
  $data = $dp->chosen_data();
  $disease = $data['��̾ɽ��'];
  $icd10 = $data['ICD10'];
  print <<<HTML
${icd10} ${disease}<br>
<input type="button" value="����" onClick="window.opener.setICD10('${icd10}', '${disease}');window.close()"><br>
HTML;
}else{
  $dp->draw();
  print '</form></body></html>';
}
?>
