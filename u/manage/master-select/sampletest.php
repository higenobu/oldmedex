<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/ms2-compat.php';

$param = array('table_name' => '検体検査マスター',
	       'pk_name' => array('項目��', '枝��'),
	       'accept_name' => '当院採用',
	       'column' => array('項目名 (日本語)', '単位名',
				 '男正常下限', '男正常上限',
				 '男正常値文章',
				 '女正常下限', '女正常上限',
			         '女正常値文章'));

$param1 = array('table_name' => 'test_master',
	       'pk_name' => array('test_lab', 'LaboSystemCode'),
	       'accept_name' => '当院採用',
	       'column' => array(
			'test_lab',
			'LaboSystemCode',
			'ReceiptSystemCode',
			'Name',
			'Search',
			'Unit',
			'Container',
			'MaleNormalText',
			'MaleNormalBottom',
			'MaleNormalTop',
			'FemaleNormalText',
			'FemaleNormalBottom',
			'FemaleNormalTop',
			'created_on'
		       ),
		'lcols' => array(
			array('Column' => 'test_lab', 'Label' => '検査会社',
			      'Draw' => 'enum',
			      'Enum' => array(
				      '' => '',
				      '1' => 'CLIP',
				      '2' => '2番目',
				      '3' => '3番目',
				      ),
				),
			array('Column' => 'LaboSystemCode', 'Label' => '検査コード'),
			array('Column' => 'ReceiptSystemCode', 'Label' => '医事コード'),
			array('Column' => 'Name', 'Label' => '項目名'),
			array('Column' => 'Search', 'Label' => '検索名'),
			array('Column' => 'Unit', 'Label' => '単位'),
			array('Column' => 'Container', 'Label' => '容器'),
			array('Column' => 'MaleNormalText', 'Label' => '男正常文章'),
			array('Column' => 'MaleNormalBottom', 'Label' => '男正常下限'),
                                    array('Column' => 'MaleNormalBottom', 'Label' => '男正常上限'),
			array('Column' => 'FemaleNormalText', 'Label' => '女正常文章'),
			array('Column' => 'FemaleNormalBottom', 'Label' => '女正常下限'),
			array('Column' => 'FemaleNormalTop', 'Label' => '女正常上限'),
			array('Column' => 'created_on','Label' => '更新日'),
			),
		'enable_qbe' => array(
			array('Column' => '検査会社', 'Compare' => '"test_lab"',
			      'Draw' => 'enum',
			      'Enum' => array(
				      '' => '',
				      '1' => 'CLIP',
				      '2' => '2番目',
				      '3' => '3番目',
				      ),
				),
			array('Column' => '検査コード', 'Compare' =>  '"LaboSystemCode"', 'Singleton' => 1, 'CompareMethod' =>'number'),
			array('Column' => '医事コード', 'Compare' => '"ReceiptSystemCode"', 'Singleton' => 1),
			array('Column' => '項目名', 'Compare' => '"Name"', 'Singleton' => 1),
			array('Column' => '検索名', 'Compare' => '"Search"', 'Singleton' => 1),
			array('Column' => '単位', 'Compare' => '"Unit"', 'Singleton' => 1),
			array('Column' => '容器', 'Compare' => '"Container"', 'Singleton' => 1),
			array('Column' => '男正常文章', 'Compare' => '"MailNormalText"', 'Singleton' => 1),
			array('Column' => '男正常下限', 'Compare' => '"MailNormalBottom"', 'Singleton' => 1),
			array('Column' => '男正常上限', 'Compare' => '"MailNormalTop"', 'Singleton' => 1),
			array('Column' => '女正常文章', 'Compare' => '"FemailNormalText"', 'Singleton' => 1),
			array('Column' => '女正常下限', 'Compare' => '"FemailNormalBottom"', 'Singleton' => 1),
			array('Column' => '女正常上限', 'Compare' => '"FemailNormalTop"', 'Singleton' => 1),
			array('Column' => '更新日', 'Compare' =>  '"created_on"', 'Singleton' => 1)
				      )
	       );

if($_mx_test_master == 2)
  $param = $param1;

master_select_table($param);
?>
