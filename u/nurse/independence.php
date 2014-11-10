<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/nurse/independence.php';

class independence_evaluation_application extends per_patient_application {

  var $_loo_title = '�������輫Ω�ٴ�����Ͽ�Υꥹ��';
  var $_sod_title = '�������輫Ω�ٴ���ɽ';
  var $_soe_title = '�������輫Ω�ٴ���ɽ���Խ�';
  var $_soc_title = '�������輫Ω�ٴ���ɽ�κ���';

  function list_of_objects($prefix, &$it) {
    global $_lib_u_nurse_independence_cfg;
    $this->cfg_pt($_lib_u_nurse_independence_cfg, $it);
    return new list_of_independence_evaluations($prefix, $cfg);
  }
  function object_display($prefix, &$it) {
    global $_lib_u_nurse_independence_cfg;
    $this->cfg_pt($_lib_u_nurse_independence_cfg, $it);
    return new independence_evaluation_display($prefix, $cfg);
  }
  function object_edit($prefix, &$it) {
    global $_lib_u_nurse_independence_cfg;
    $this->cfg_pt($_lib_u_nurse_independence_cfg, $it);
    return new independence_evaluation_edit($prefix, $cfg);
  }
}

$app = new independence_evaluation_application();
$app->main();
?>
