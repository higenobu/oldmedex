<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';

$_lib_u_ct_record_chushi_cfg = array
('TABLE' => '治験中止脱落',
 'COLS' => array("中止・脱落の有無",
		 "中止・脱落日",
		 "中止理由1",
		 "中止理由2",
		 "中止理由3",
		 "中止理由4",
		 "中止理由5",

		 "脱落理由1",
		 "脱落理由2",
		 "脱落理由3",
		 "コメント",
		 ),
 'ICOLS' => array(
		  "中止・脱落の有無",
		  "中止・脱落日",
		  "中止理由1",
		  "中止理由2",
		  "中止理由3",
		  "中止理由4",
		  "中止理由5",
		  
		  "脱落理由1",
		  "脱落理由2",
		  "脱落理由3",
		  "コメント",
		  "治験オーダ"
		 ),
 'ECOLS' => array(
		  array('Column' => "中止・脱落の有無",
			'Draw' => 'radio',
			'Enum' => array(NULL => '未記入',
					1 => 'なし',
					2 => 'あり'
					),
			),
		  array('Column' => "中止・脱落日",
			'Draw' => 'date',
			),
		  array('Column' => "中止理由1",
			'Draw' => 'check',
			'Caption' => '被検者から辞退の申し出があった'
			),
		  array('Column' => "中止理由2",
			'Draw' => 'check',
			'Caption' => '重篤な有害事象が発現し、投与継続困難と判断された'
			),
		  array('Column' => "中止理由3",
			'Draw' => 'check',
			'Caption' => '治験期間中の偶発事故あるいは罹患などにより投与継続が困難となった'
			),
		  array('Column' => "中止理由4",
			'Draw' => 'check',
			'Caption' => '治験開始後、被検者が対象外である事が判明した'
			),
		  array('Column' => "中止理由5",
			'Draw' => 'check',
			'Caption' => 'その他、治験責任医師・治験分担医師の判断により中止した'
			),
		  array('Column' => "脱落理由1",
			'Draw' => 'check',
			'Caption' => '来院せず'
			),
		  array('Column' => "脱落理由2",
			'Draw' => 'check',
			'Caption' => '被検者が治験責任医師・治験分担医師の指示に従わない(非協力)'
			),
		  array('Column' => "脱落理由3",
			'Draw' => 'check',
			'Caption' => 'その他被検者の都合により中断された'
			),

		  array('Column' => "コメント",
			'Draw' => 'textarea'
			)
		  ),
 );

$_lib_u_ct_record_chushi_cfg['LCOLS'] = $_lib_u_ct_record_chushi_cfg['COLS'];
$_lib_u_ct_record_chushi_cfg['DCOLS'] = $_lib_u_ct_record_chushi_cfg['COLS'];

class ct_record_chushi_edit extends simple_object_edit {
  var $debug = 1;
  function ct_record_chushi_edit($prefix, &$app, $cfg=NULL) {
    global $_lib_u_ct_record_chushi_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_ct_record_chushi_cfg;
    //_lib_u_ct_annotate_cfg(&$cfg);
    $this->app = $app;
    $this->data['治験'] = $this->app->loo->CT_ObjectID;
    $this->data['治験オーダ'] = $this->app->sod->chosen();
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }

  function edit($chiken_id) {
    $db = mx_db_connect();
    
    // ID here means 治験オーダ id.  What I really want is 治験併用 id.
    $stmt = 'select "ObjectID" from "治験中止脱落" where "Superseded" is NULL and "治験オーダ"=' . $chiken_id;
    
    $r = mx_db_fetch_single($db, $stmt);
    if(!$r) {
      //print "NO OLD RECORD_CHUSHI FOUND for chiken_id=$chiken_id";
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
