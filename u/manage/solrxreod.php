<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>Table-to-csv converter</title>
	</head>
	<body>
<a href="index.php?tab=1">�ᥤ������</a>

<a href="solrxreod-app.php?tab=1">�Ƽ¹�</a>


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


$query1='INSERT INTO "���޽����"(
              "����ǯ����", "����������", 
            "����",   "����", "��ʬ", "���ⱡ��", "����׻�",  
              "���",   "��ȯ��", "�±����ѥ쥻�����ݸ�����", 
            "�±����ѥ쥻������ǲʾ���", "Comment",   noclaim, setflag, setcomment, 
            funsai,orcakey ) select ';
$cond011=" date('$orderdate'),"; 
 $cond012=" date('$startdate'),"; 

 $cond01=$cond011.$cond012;

$cond020=" $nissu, ";


 $cond02='    "����", "��ʬ", "���ⱡ��",';
$cond021="'10',";

$cond022='  "���",   "��ȯ��", "�±����ѥ쥻�����ݸ�����", 
            "�±����ѥ쥻������ǲʾ���", "Comment",   noclaim, setflag, setcomment, 
            funsai, "pt_num"||rxx."ID"  from "���޽����" as rxx left join rx_temp2 on
	rxx."ID" =rx_id where rxx."Superseded" is null and  rxx."ID" in (select rx_id from rx_temp2 where selected=1 and "Superseded" is null)';


 
$query1 = $query1.$cond01.$cond020.$cond02.$cond021.$cond022;
	}
else {

	$query1='INSERT INTO "���޽����"(
              "����ǯ����", "����������", 
            "����",   "����", "��ʬ", "���ⱡ��", "����׻�",  
              "���",   "��ȯ��", "�±����ѥ쥻�����ݸ�����", 
            "�±����ѥ쥻������ǲʾ���", "Comment",   noclaim, setflag, setcomment, 
            funsai,orcakey ) select ';
$cond011=" date('$orderdate'),"; 
 $cond012=" date('$startdate'),"; 

 $cond01=$cond011.$cond012;

$cond020=' rxx."����", ';

 $cond02='    "����", "��ʬ", "���ⱡ��",';
$cond021="'10',";
 $cond022='     "���",   "��ȯ��", "�±����ѥ쥻�����ݸ�����", 
            "�±����ѥ쥻������ǲʾ���", "Comment",   noclaim, setflag, setcomment, 
            funsai, "pt_num"||rxx."ID"  from "���޽����" as rxx left join rx_temp2 on
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

$query2='INSERT INTO "���޽��������"( "���޽����", "��Ϳ����", "����", "����ñ��", "����", "����", "��ˡ", "�굻", "�����ˡ", "����¾������", "RPID", "����", "��ˡʬ��", "��ʬ", "����", generic_ok) select  r."ID", "��Ϳ����", "����", "����ñ��", "����",';
$condx01="$nissu, ";
$condx02='  "��ˡ", "�굻", "�����ˡ", "����¾������", "RPID", "����", "��ˡʬ��", c."��ʬ", c."����", generic_ok from "���޽��������" as c   left join rx_temp2 as t on rx_id=  c."���޽����" left join "���޽����" as r   on  orcakey="pt_num"||"rx_id" where c."���޽����" in (select rx_id from rx_temp2 where  selected=1 and "Superseded" is null) ';
 
$query3=$query2.$condx01.$condx02;

	}
else {
$query2='INSERT INTO "���޽��������"( "���޽����", "��Ϳ����", "����", "����ñ��", "����", "����", "��ˡ", "�굻", "�����ˡ", "����¾������", "RPID", "����", "��ˡʬ��", "��ʬ", "����", generic_ok) select  r."ID", "��Ϳ����", "����", "����ñ��", "����",';
$condx01=' c."����", ';
$condx02='  "��ˡ", "�굻", "�����ˡ", "����¾������", "RPID", "����", "��ˡʬ��", c."��ʬ", c."����", generic_ok from "���޽��������" as c   left join rx_temp2 as t on rx_id=  c."���޽����" left join "���޽����" as r   on  orcakey="pt_num"||"rx_id" where c."���޽����" in (select rx_id from rx_temp2 where  selected=1 and "Superseded" is null) ';
 
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
