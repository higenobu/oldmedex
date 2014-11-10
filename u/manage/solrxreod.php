<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>Table-to-csv converter</title>
	</head>
	<body>
<a href="index.php?tab=1">メインに戻る</a>

<a href="solrxreod-app.php?tab=1">再実行</a>


<br>

<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

$con = mx_db_connect();



  
pg_set_client_encoding('EUC_JP');




$orderdate = $_POST[orderdate];

if ($orderdate==''){$orderdate= date(Ymd);}




$startdate = $_POST[startdate];

if ($startdate==''){$startdate= date(Ymd);}
$nissu=$_POST[nissu];	




//***********************
if ($nissu!=0){


$query1='INSERT INTO "薬剤処方箋"(
              "処方年月日", "処方開始日", 
            "日数",   "患者", "区分", "院内院外", "定期臨時",  
              "注射",   "後発品", "病院使用レセコン保険情報", 
            "病院使用レセコン受診科情報", "Comment",   noclaim, setflag, setcomment, 
            funsai,orcakey ) select ';
$cond011=" date('$orderdate'),"; 
 $cond012=" date('$startdate'),"; 

 $cond01=$cond011.$cond012;

$cond020=" $nissu, ";


 $cond02='    "患者", "区分", "院内院外",';
$cond021="'10',";

$cond022='  "注射",   "後発品", "病院使用レセコン保険情報", 
            "病院使用レセコン受診科情報", "Comment",   noclaim, setflag, setcomment, 
            funsai, "pt_num"||rxx."ID"  from "薬剤処方箋" as rxx left join rx_temp2 on
	rxx."ID" =rx_id where rxx."Superseded" is null and  rxx."ID" in (select rx_id from rx_temp2 where selected=1 and "Superseded" is null)';


 
$query1 = $query1.$cond01.$cond020.$cond02.$cond021.$cond022;
	}
else {

	$query1='INSERT INTO "薬剤処方箋"(
              "処方年月日", "処方開始日", 
            "日数",   "患者", "区分", "院内院外", "定期臨時",  
              "注射",   "後発品", "病院使用レセコン保険情報", 
            "病院使用レセコン受診科情報", "Comment",   noclaim, setflag, setcomment, 
            funsai,orcakey ) select ';
$cond011=" date('$orderdate'),"; 
 $cond012=" date('$startdate'),"; 

 $cond01=$cond011.$cond012;

$cond020=' rxx."日数", ';

 $cond02='    "患者", "区分", "院内院外",';
$cond021="'10',";
 $cond022='     "注射",   "後発品", "病院使用レセコン保険情報", 
            "病院使用レセコン受診科情報", "Comment",   noclaim, setflag, setcomment, 
            funsai, "pt_num"||rxx."ID"  from "薬剤処方箋" as rxx left join rx_temp2 on
	rxx."ID" =rx_id where rxx."Superseded" is null and  rxx."ID" in (select rx_id from rx_temp2 where selected=1 and "Superseded" is null)';


 
$query1 = $query1.$cond01.$cond020.$cond02.$cond021.$cond022;
	}


print $query1;


$rs1 = pg_query($con, $query1);
if (!$rs1) {
	
	pg_close($con);
	echo("<p><span style=\"color:red\">rx_order table failed </span></p>\n");
	echo("</body></html>\n");
	exit;
}






echo("<p><span style=\"color:blue\">rx order created  orderdate: $orderdate startdate: $startdate </span></p>\n");



if ($nissu!=0){

$query2='INSERT INTO "薬剤処方箋内容"( "薬剤処方箋", "投与形態", "薬剤", "用量単位", "用量", "日数", "用法", "手技", "注射用法", "その他コメント", "RPID", "一包", "用法分類", "区分", "頓服", generic_ok) select  r."ID", "投与形態", "薬剤", "用量単位", "用量",';
$condx01="$nissu, ";
$condx02='  "用法", "手技", "注射用法", "その他コメント", "RPID", "一包", "用法分類", c."区分", c."頓服", generic_ok from "薬剤処方箋内容" as c   left join rx_temp2 as t on rx_id=  c."薬剤処方箋" left join "薬剤処方箋" as r   on  orcakey="pt_num"||"rx_id" where c."薬剤処方箋" in (select rx_id from rx_temp2 where  selected=1 and "Superseded" is null) ';
 
$query3=$query2.$condx01.$condx02;

	}
else {
$query2='INSERT INTO "薬剤処方箋内容"( "薬剤処方箋", "投与形態", "薬剤", "用量単位", "用量", "日数", "用法", "手技", "注射用法", "その他コメント", "RPID", "一包", "用法分類", "区分", "頓服", generic_ok) select  r."ID", "投与形態", "薬剤", "用量単位", "用量",';
$condx01=' c."日数", ';
$condx02='  "用法", "手技", "注射用法", "その他コメント", "RPID", "一包", "用法分類", c."区分", c."頓服", generic_ok from "薬剤処方箋内容" as c   left join rx_temp2 as t on rx_id=  c."薬剤処方箋" left join "薬剤処方箋" as r   on  orcakey="pt_num"||"rx_id" where c."薬剤処方箋" in (select rx_id from rx_temp2 where  selected=1 and "Superseded" is null) ';
 
$query3=$query2.$condx01.$condx02;

	}

print $query3;

$rs2 = pg_query($con, $query3);
if (!$rs2) {
	
	pg_close($con);
	echo("<p><span style=\"color:red\">rx_content table failed </span></p>\n");
	echo("</body></html>\n");
	exit;
}




echo("<p><span style=\"color:blue\">rx-order contents are created </span></p>\n");

?>

	</tbody>
     </table>

	</body>






</html>
