<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';

$_lib_u_ct_record_haikei_cfg = array('TABLE' => '治験背景',
	     'COLS' => array("同意年月日",
			     "年齢",
			     "身長",
			     "体重",
			     "肥満度",
			     "閉経時期",
			     "子宮摘出歴",
			     "子宮摘出術式",
			     "子宮摘出年月",
			     "卵巣左摘出歴",
			     "卵巣左摘出年月",
			     "卵巣右摘出歴",
			     "卵巣右摘出年月",
			     "既往歴",
			     "既往歴(1)",
			     "既往歴(2)",
			     "既往歴(3)",
			     "既往歴(4)",
			     "既往歴(5)",
			     "既往歴(6)",
			     "既往歴(7)",
			     "既往歴(8)",
			     "既往歴(9)",
			     "既往歴(10)",
			     "合併症",
			     "合併症(1)",
			     "合併症(2)",
			     "合併症(3)",
			     "合併症(4)",
			     "合併症(5)",
			     "合併症(6)",
			     "合併症(7)",
			     "合併症(8)",
			     "合併症(9)",
			     "合併症(10)",
			     "HBs抗原",
			     "HBs抗原検査日",
			     "梅毒",
			     "梅毒検査日",
			     "HCV抗体",
			     "HCV抗体検査日",
			     "HIV",
			     "HIV検査日",
			     "投与日時",
			     "薬剤区分",
			     "投与量",
			     "コメント",
			     ),
	     'ICOLS' => array("同意年月日",
			     "年齢",
			     "身長",
			     "体重",
			     "肥満度",
			     "閉経時期",
			     "子宮摘出歴",
			     "子宮摘出術式",
			     "子宮摘出年月",
			     "卵巣左摘出歴",
			     "卵巣左摘出年月",
			     "卵巣右摘出歴",
			     "卵巣右摘出年月",
			     "既往歴",
			     "既往歴(1)",
			     "既往歴(2)",
			     "既往歴(3)",
			     "既往歴(4)",
			     "既往歴(5)",
			     "既往歴(6)",
			     "既往歴(7)",
			     "既往歴(8)",
			     "既往歴(9)",
			     "既往歴(10)",
			     "合併症",
			     "合併症(1)",
			     "合併症(2)",
			     "合併症(3)",
			     "合併症(4)",
			     "合併症(5)",
			     "合併症(6)",
			     "合併症(7)",
			     "合併症(8)",
			     "合併症(9)",
			     "合併症(10)",
			     "HBs抗原",
			     "HBs抗原検査日",
			     "梅毒",
			     "梅毒検査日",
			     "HCV抗体",
			     "HCV抗体検査日",
			     "HIV",
			     "HIV検査日",
			     "投与日時",
			     "薬剤区分",
			     "投与量",
			     "コメント",
			     "治験オーダ",
			     ),
	     'ECOLS' => array(
			      array('Column' => "同意年月日",
				    'Draw' => 'date'
				    ),
			      "年齢",
			      "身長",
			      "体重",
			      "肥満度",
			      array('Column' => "閉経時期",
				    'Draw' => 'date'
				    ),
			      array('Column' => "子宮摘出歴",
				    'Draw' => 'radio',
				    'Enum' => array(NULL => '未記入',
						    1 => 'なし',
						    2 => 'あり'),
				    ),
			      "子宮摘出術式",
			      array('Column' => "子宮摘出年月",
				    'Draw' => 'date'
				    ),
			      array('Column' => "卵巣左摘出歴",
				    'Draw' => 'radio',
				    'Enum' => array(NULL => '未記入',
						    1 => 'なし',
						    2 => 'あり'),
				    ),
			      array('Column' => "卵巣左摘出年月",
				    'Draw' => 'date',
				    ),
			     "卵巣右摘出歴",
			      array('Column' => "卵巣右摘出年月",
				    'Draw' => 'date',
				    ),
			      array('Column' => "既往歴",
				    'Draw' => 'radio', 
				    'Enum' => array(NULL => '未記入',
						    1 => 'なし',
						    2 => 'あり'),
				    ),
			     "既往歴(1)",
			     "既往歴(2)",
			     "既往歴(3)",
			     "既往歴(4)",
			     "既往歴(5)",
			     "既往歴(6)",
			     "既往歴(7)",
			     "既往歴(8)",
			     "既往歴(9)",
			     "既往歴(10)",
			      array('Column' => "合併症",
				    'Draw' => 'radio', 
				    'Enum' => array(NULL => '未記入',
						    1 => 'なし',
						    2 => 'あり'),
				    ),
			     "合併症(1)",
			     "合併症(2)",
			     "合併症(3)",
			     "合併症(4)",
			     "合併症(5)",
			     "合併症(6)",
			     "合併症(7)",
			     "合併症(8)",
			     "合併症(9)",
			     "合併症(10)",
			      array('Column' => "HBs抗原",
				    'Draw' => 'radio', 
				    'Enum' => array(NULL => '未記入',
						    1 => '陰性',
						    2 => '陽性'),
				    ),
			      array('Column' => "HBs抗原検査日",
				    'Draw' => 'date'
				    ),
			      array('Column' => "梅毒",
				    'Draw' => 'radio', 
				    'Enum' => array(NULL => '未記入',
						    1 => '陰性',
						    2 => '陽性'),
				    ),
			      
			      array('Column' => "梅毒検査日",
				    'Draw' => 'date'
				    ),
			      array('Column' => "HCV抗体",
				    'Draw' => 'radio', 
				    'Enum' => array(NULL => '未記入',
						    1 => '陰性',
						    2 => '陽性'),
				    ),
			      
			      array('Column' => "HCV抗体検査日",
				    'Draw' => 'date'
				    ),
			      array('Column' => "HIV",
				    'Draw' => 'radio', 
				    'Enum' => array(NULL => '未記入',
						    1 => '陰性',
						    2 => '陽性'),
				    ),
			      
			     array('Column' => "HIV検査日",
				    'Draw' => 'date'
				    ),
			      
			     array('Column' => "投与日時",
				   'Draw' => 'datetime'
				   ),
			     array('Column' => "薬剤区分",
				   'Draw' => 'radio',
				   'Enum' => array(NULL => '未記入',
						   1 => '実薬',
						   2 => 'プラセボ'
						   ),
				   ),
			     "投与量",
			     "コメント",
			     ),

	     );
$_lib_u_ct_record_haikei_cfg['LCOLS'] = array('同意年月日');
$_lib_u_ct_record_haikei_cfg['DCOLS'] = $_lib_u_ct_record_haikei_cfg['COLS'];

class ct_record_haikei_edit extends simple_object_edit {
  var $debug = 1;
  function ct_record_haikei_edit($prefix, &$app, $cfg=NULL) {
    global $_lib_u_ct_record_haikei_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_ct_record_haikei_cfg;
    //_lib_u_ct_annotate_cfg(&$cfg);
    $this->app = $app;
    $this->data['治験'] = $this->app->loo->CT_ObjectID;
    $this->data['治験オーダ'] = $this->app->sod->chosen();
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }

  function edit($chiken_id) {
    $db = mx_db_connect();
    
    // ID here means 治験オーダ id.  What I really want is 治験併用 id.
    $stmt = 'select "ObjectID" from "治験背景" where "Superseded" is NULL and "治験オーダ"=' . $chiken_id;
    
    $r = mx_db_fetch_single($db, $stmt);
    if(!$r) {
      //print "NO OLD RECORD_HAIKEI FOUND for chiken_id=$chiken_id";
      return $this->anew(null);
    }
    
    $this->id = $r["ObjectID"];
    $this->data = $this->fetch_data($this->id);
    $this->data['治験オーダ'] = $chiken_id;
    $this->annotate_row_data(&$this->data);
    $this->Subpick = NULL;
    $this->page = 0;
    $this->edit_tweak();
    $this->origin = $this->fetch_origin_info();
    $this->chosen = 1;
  }

  function annotate_form_data(&$data) {
    $data['治験オーダ'] = $this->app->sod->chosen();
    $data['CreatedBy'] = $this->u;
  }

}
?>
