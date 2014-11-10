<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>TEST RESULT INSERT from CSV to DB</title>
	</head>
	<body>
<a href="index.php?tab=1">メインに戻る</a>


<br>


<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';

$db = mx_db_connect();






$testname = $_POST[testname];

$unit = $_POST[unit];
$testcode = $_POST[testcode];
$mup = $_POST[mup];
$mdown = $_POST[mdown];
$mgood = $_POST[mgood];
$fup = $_POST[fup];
$fdown = $_POST[fdown];
$fgood = $_POST[fgood];


$medis=$_POST[medis];
$cat= (int)$_POST[cat];
$saiyo= $_POST[saiyo];
$add=$_POST[add];
$rcode=$_POST[rcode];
$sethead=(int )$_POST[sethead];
$cat=(int)$_POST[cat];
$exist=0;
$oya= $_POST[oya];
$oyaid=0;



if ($oya!=0){
 $stmt = <<<SQL
select  "ID"  as id from test_master0 where "LaboSystemCode"=  '$oya'
SQL;

$rows =  mx_db_fetch_all($db, $stmt);
 
  foreach($rows as $row)
   
	if ($row['id'] !=0) {
	print "　親のIDが存在しています。親子関係をセットできます";
	$oyaid=$row['id'];
	}


}
  
else {
 $oyaid=0;
}


 $stmt = <<<SQL
select  "Name"  as name, "Unit" as unit,"MaleNormalBottom" as mb,"MaleNormalTop" as mt, "MaleNormalText" as ms,"FemaleNormalBottom" as fb,"FemaleNormalTop" as ft , "FemaleNormalText" as fs, "Category" as ct, "DispCategory" as dt , "SortOrder" as so, "LaboSystemCode" as lc, "ReceiptSystemCode" as rc, "当院採用" as yy,  test_lab,   "SetHeader" as sh, "Parent" as pt, medis  from test_master0 where "LaboSystemCode"='$testcode' 
SQL;

$rows =  mx_db_fetch_all($db, $stmt);
 
  foreach($rows as $row)
   
if ($row['lc'] !='') {
	print "　この検査コードはすでに存在しています。更新できます。";
	$exist=1;
	}
else {
print "この検査コードは新規のものです。";
$exist=0;
}


if ($add=='add' &&  $exist==0 && $oyaid!=0){	
	
$stmt = <<<SQL
INSERT INTO test_master0("ID","ObjectID","Name","Unit","MaleNormalBottom","MaleNormalTop", "MaleNormalText","FemaleNormalBottom","FemaleNormalTop", "FemaleNormalText", "Category", "DispCategory", "SortOrder", "LaboSystemCode", "ReceiptSystemCode", "当院採用",   "SetHeader" ,"Parent",medis) values  (nextval('"test_master0_ID_seq"'),currval('"test_master0_ID_seq"'),'$testname','$unit','$mdown','$mup','$mgood','$fdown','$fup','$fgood', $cat,$cat,'$testcode','$testcode','$rcode','$saiyo','$sethead',$oyaid,'$medis' )
SQL;

if (pg_query($db, $stmt)){
 print '<p> 正常に追加しました。</p>';
	}
else {
print '<p > 追加できません。</p>';
die;
	}

}

if ($add=='add' &&  $exist==0 && $oyaid==0){	
	
$stmt = <<<SQL
INSERT INTO test_master0("ID","ObjectID","Name","Unit","MaleNormalBottom","MaleNormalTop", "MaleNormalText","FemaleNormalBottom","FemaleNormalTop", "FemaleNormalText", "Category", "DispCategory", "SortOrder", "LaboSystemCode", "ReceiptSystemCode", "当院採用",   "SetHeader" ,medis  ) values  (nextval('"test_master0_ID_seq"'),currval('"test_master0_ID_seq"'),'$testname','$unit','$mdown','$mup','$mgood','$fdown','$fup','$fgood', $cat,$cat,'$testcode','$testcode','$rcode','$saiyo','$sethead','$medis' )
SQL;

print $stmt;


if (pg_query($db, $stmt)){
 print '<p> 正常に追加。</p>';
	}
else {
print '<p > 追加できません。</p>';
die;
	}

}


if ($add=='update' &&  $exist==1){	

if ($testname==''){$testname=$row['name'];}

if ($unit==''){$unit=$row['unit'];}

if ($mdown==''){$mdown=$row['mb'];}
if ($mup==''){$mup=$row['mt'];}

if ($fdown==''){$fdown=$row['fb'];}
if ($fup==''){$fup=$row['ft'];}
if ($mgood==''){$mgood=$row['ms'];}
if ($fgood==''){$fgood=$row['fs'];}
if ($cat==''){$cat=(int)$row['ct'];}
if ($rcode==''){$rcode=$row['rc'];}
if ($saiyo==''){$saiyo= $row['yy'];}
if ($medis==''){$medis= $row['medis'];}
if ($sethead==''){$sethead=(int)$row['st'];}
if ($oyaid==0){
$stmt = <<<SQL
update  test_master0 set "Name"='$testname',"Unit"='$unit',"MaleNormalBottom"='$mdown',"MaleNormalTop"='$mup',"MaleNormalText"='$mgood',"FemaleNormalBottom"='$fdown',"FemaleNormalTop"='$fup', "FemaleNormalText"='$fgood', "Category"=$cat, "DispCategory"=$cat,  "ReceiptSystemCode"='$rcode', "当院採用"='$saiyo',"SetHeader" ='$sethead' , "medis"='$medis' where "LaboSystemCode"='$testcode'
SQL;

}
else {
$stmt = <<<SQL
update  test_master0 set "Name"='$testname',"Unit"='$unit',"MaleNormalBottom"='$mdown',"MaleNormalTop"='$mup',"MaleNormalText"='$mgood',"FemaleNormalBottom"='$fdown',"FemaleNormalTop"='$fup', "FemaleNormalText"='$fgood', "Category"=$cat, "DispCategory"=$cat,  "ReceiptSystemCode"='$rcode', "当院採用"='$saiyo',"SetHeader" ='$sethead' , "Parent"=$oyaid,"medis"='$medis' where "LaboSystemCode"='$testcode'
SQL;
}

print $stmt;

if (pg_query($db, $stmt)){
 print '<p> 更新が完了。</p>';
	}
else {
print '<p > 更新できず。</p>';
die;
	}

}




?>







































