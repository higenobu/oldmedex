<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/ms2-compat.php';

$param = array('table_name' => 'Medis医薬品マスター',
	       'pk_name' => array('基準番号'),
	       'accept_name' => array('当院採用','病院使用医薬品名',				 '病院使用レセコンコード',),
	       'column' => array('病院使用医薬品名',
				 '病院使用レセコンコード',
				 'レセプト電算処理システム医薬品名',
				 'レセプト電算処理システムコード（１）',
				 '規格単位',
				 '包装形態',
				 '包装単位数',
				 '包装単位単位',
				 '包装総量数',
				 '包装総量単位',
				 '販売会社',
				 '薬価基準収載医薬品コード',
				 '基準番号',
				 '更新年月日',
				 '病院更新日'
				 ),
	       'stat' => array( # '総医薬品数' => 'COUNT(*)',
			       '最終データ更新日' => 'MAX("更新年月日")',
		       ),
);

master_select_table($param,
		    $__uiconfig_ms_qbe_enum_medicine,
		    $__uiconfig_ms_header_fields_medicine);
?>
