<?php // -*- mode: php; coding: euc-japan -*-

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

$_lib_u_doctor_imgtestpick_dps_cfg = array
('COLS' => array('部位複合名称', 'モダリティ', '大分類', '小分類', '拡張',
		 '部位コード'),
 'ALLOW_SORT' => 1,
 'TABLE' => 'Medis画像検査マスター',
 'ENABLE_QBE' => array('部位複合名称',
		       '部位コード',
		       array('Column' => '当院採用',
			     'Compare' => '"当院採用"',
			     'Draw' => 'enum',
			     'Enum' => array('F' => '頻出分のみ',
					     'YF' => '採用分のみ',
					     '' => 'マスタ全て'),
			     'CompareMethod' => 'enum_single_char',
			     'Singleton' => 1) ),
 'DEFAULT_QBE' => array(array('当院採用', 'F')),
 'LIST_IDS' => array('ObjectID', '部位複合名称'),
 );

class imgtestpick extends list_of_simple_objects {

  function imgtestpick($prefix) {
    global $_lib_u_doctor_imgtestpick_dps_cfg;
    $cfg = $_lib_u_doctor_imgtestpick_dps_cfg;
    list_of_simple_objects::list_of_simple_objects
      ($prefix, $cfg);
  }

  function draw_no_data_message() {
    print '<br />該当する検査がありません。';
  }

}
?>
