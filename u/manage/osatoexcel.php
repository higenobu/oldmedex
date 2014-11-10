<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/index-pt-app.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/Classes/PHPExcel.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/Classes//PHPExcel/IOFactory.php';
print "AAAAAAAA";
//$objPHPExcel = new PHPExcel();
$objReader = PHPExcel_IOFactory::createReader('Excel5');
//$objReader = new PHPExcel_Reader_OOCalc();
$objPHPExcel = $objReader->load("/tmp/osato.xls");
print "BBBBBBBBBBBBBBB";
$objPHPExcel->setActiveSheetIndex(0);
// アクティブにしたシートの情報を取得
$objSheet = $objPHPExcel->getActiveSheet();
 
// セル「A1」に「タイトル」という文字を挿入
//$objSheet->setCellValue("A2", "ccc");
// セル「B2」に今日の日付を挿入
//$objSheet->setCellValue("B2", date("Y/m/d"));
// セル「C3」に計算結果を挿入

 

print "CCC";

 $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
 var_dump($sheetData);
//print "EEE";
 
 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'OOCalc');
 $objWriter->save('/tmp/new2.xls');
 
 print "FFF";


  
?>
