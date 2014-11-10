<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/ms2-compat.php';

$param = array('table_name' => '¸¡ÂÎ¸¡ºº¥Þ¥¹¥¿¡¼',
	       'pk_name' => array('¹àÌÜ­â', '»Þ­â'),
	       'accept_name' => 'Åö±¡ºÎÍÑ',
	       'column' => array('¹àÌÜÌ¾ (ÆüËÜ¸ì)', 'Ã±°ÌÌ¾',
				 'ÃËÀµ¾ï²¼¸Â', 'ÃËÀµ¾ï¾å¸Â',
				 'ÃËÀµ¾ïÃÍÊ¸¾Ï',
				 '½÷Àµ¾ï²¼¸Â', '½÷Àµ¾ï¾å¸Â',
			         '½÷Àµ¾ïÃÍÊ¸¾Ï'));

$param1 = array('table_name' => 'test_master',
	       'pk_name' => array('test_lab', 'LaboSystemCode'),
	       'accept_name' => 'Åö±¡ºÎÍÑ',
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
			array('Column' => 'test_lab', 'Label' => '¸¡ºº²ñ¼Ò',
			      'Draw' => 'enum',
			      'Enum' => array(
				      '' => '',
				      '1' => 'CLIP',
				      '2' => '2ÈÖÌÜ',
				      '3' => '3ÈÖÌÜ',
				      ),
				),
			array('Column' => 'LaboSystemCode', 'Label' => '¸¡ºº¥³¡¼¥É'),
			array('Column' => 'ReceiptSystemCode', 'Label' => '°å»ö¥³¡¼¥É'),
			array('Column' => 'Name', 'Label' => '¹àÌÜÌ¾'),
			array('Column' => 'Search', 'Label' => '¸¡º÷Ì¾'),
			array('Column' => 'Unit', 'Label' => 'Ã±°Ì'),
			array('Column' => 'Container', 'Label' => 'ÍÆ´ï'),
			array('Column' => 'MaleNormalText', 'Label' => 'ÃËÀµ¾ïÊ¸¾Ï'),
			array('Column' => 'MaleNormalBottom', 'Label' => 'ÃËÀµ¾ï²¼¸Â'),
                                    array('Column' => 'MaleNormalBottom', 'Label' => 'ÃËÀµ¾ï¾å¸Â'),
			array('Column' => 'FemaleNormalText', 'Label' => '½÷Àµ¾ïÊ¸¾Ï'),
			array('Column' => 'FemaleNormalBottom', 'Label' => '½÷Àµ¾ï²¼¸Â'),
			array('Column' => 'FemaleNormalTop', 'Label' => '½÷Àµ¾ï¾å¸Â'),
			array('Column' => 'created_on','Label' => '¹¹¿·Æü'),
			),
		'enable_qbe' => array(
			array('Column' => '¸¡ºº²ñ¼Ò', 'Compare' => '"test_lab"',
			      'Draw' => 'enum',
			      'Enum' => array(
				      '' => '',
				      '1' => 'CLIP',
				      '2' => '2ÈÖÌÜ',
				      '3' => '3ÈÖÌÜ',
				      ),
				),
			array('Column' => '¸¡ºº¥³¡¼¥É', 'Compare' =>  '"LaboSystemCode"', 'Singleton' => 1, 'CompareMethod' =>'number'),
			array('Column' => '°å»ö¥³¡¼¥É', 'Compare' => '"ReceiptSystemCode"', 'Singleton' => 1),
			array('Column' => '¹àÌÜÌ¾', 'Compare' => '"Name"', 'Singleton' => 1),
			array('Column' => '¸¡º÷Ì¾', 'Compare' => '"Search"', 'Singleton' => 1),
			array('Column' => 'Ã±°Ì', 'Compare' => '"Unit"', 'Singleton' => 1),
			array('Column' => 'ÍÆ´ï', 'Compare' => '"Container"', 'Singleton' => 1),
			array('Column' => 'ÃËÀµ¾ïÊ¸¾Ï', 'Compare' => '"MailNormalText"', 'Singleton' => 1),
			array('Column' => 'ÃËÀµ¾ï²¼¸Â', 'Compare' => '"MailNormalBottom"', 'Singleton' => 1),
			array('Column' => 'ÃËÀµ¾ï¾å¸Â', 'Compare' => '"MailNormalTop"', 'Singleton' => 1),
			array('Column' => '½÷Àµ¾ïÊ¸¾Ï', 'Compare' => '"FemailNormalText"', 'Singleton' => 1),
			array('Column' => '½÷Àµ¾ï²¼¸Â', 'Compare' => '"FemailNormalBottom"', 'Singleton' => 1),
			array('Column' => '½÷Àµ¾ï¾å¸Â', 'Compare' => '"FemailNormalTop"', 'Singleton' => 1),
			array('Column' => '¹¹¿·Æü', 'Compare' =>  '"created_on"', 'Singleton' => 1)
				      )
	       );

if($_mx_test_master == 2)
  $param = $param1;

master_select_table($param);
?>
