<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>add emploee modality</title>
	</head>
	<body>
<a href="index.php?tab=1">go back to main</a>


<br>


<?php



include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
 
 
 

$db = mx_db_connect();

 
$name=$_POST[name];
$gp=$_POST[group];
$typ=$_POST[type];
$sl=$_POST[sel];
$ln=$_POST[len];
 
 
echo "name=".$name."<br>";
 
 
 

/* 
 $stmt10 = <<<SQL
SELECT  "ID" 
  FROM "語彙" where  "Superseded" is null order by "ID"   desc limit 1
SQL;
// print $stmt10;

 
  $rs = mx_db_fetch_single($db, $stmt10);
if ($rs != null){
 
 $nextid=$rs['ID']+1;
}

 
 $stmt11 = <<<SQL
SELECT  "ID" 
  FROM"語句群" where  "Superseded" is null order by "ID"   desc limit 1
SQL;


 
  $rs1 = mx_db_fetch_single($db, $stmt11);
if ($rs1 != null){
 
 $nextid2=$rs1['ID']+1;
}
*/
/*

$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
$index="k100".$kk;
$pindex="p100".$kk;
$params[$pindex]=$past[$index];
 }
$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
$index="k8".$kk;
$pindex="p8".$kk;
$params[$pindex]=$past[$index];
 }

$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
$index="k10".$kk;
$pindex="p10".$kk;
$params[$pindex]=$past[$index];
 }
$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
$index="k20".$kk;
$pindex="p20".$kk;
$params[$pindex]=$past[$index];
 }
$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
$index="k30".$kk;
$pindex="p30".$kk;
$params[$pindex]=$past[$index];
 }
$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
$index="k50".$kk;
$pindex="p50".$kk;
$params[$pindex]=$past[$index];
 }

$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
$index="k51".$kk;
$pindex="p51".$kk;
$params[$pindex]=$past[$index];
 }

$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
$index="kk1".$kk;
$pindex="pp1".$kk;
$params[$pindex]=$past[$index];
 }
$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
$index="kk3".$kk;
$pindex="pp3".$kk;
$params[$pindex]=$past[$index];
 }
$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
$index="kk5".$kk;
$pindex="pp5".$kk;
$params[$pindex]=$past[$index];
 }
$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
$index="kk6".$kk;
$pindex="pp6".$kk;
$params[$pindex]=$past[$index];
 }
$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
$index="kk7".$kk;
$pindex="pp7".$kk;
$params[$pindex]=$past[$index];
 }
$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
$index="kk9".$kk;
$pindex="pp9".$kk;
$params[$pindex]=$past[$index];
 }
$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
$index="kk11".$kk;
$pindex="pp11".$kk;
$params[$pindex]=$past[$index];
 }
$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
$index="kk60".$kk;
$pindex="pp60".$kk;
$params[$pindex]=$past[$index];
 }
$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
$index="kk61".$kk;
$pindex="pp61".$kk;
$params[$pindex]=$past[$index];
 }
$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
$index="kk10".$kk;
$pindex="pp10".$kk;
$params[$pindex]=$past[$index];
 }
$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
$index="kk11".$kk;
$pindex="pp11".$kk;
$params[$pindex]=$past[$index];
 }
$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
$index="kk12".$kk;
$pindex="pp12".$kk;
$params[$pindex]=$past[$index];
 }


*/
$gp='kenshin';
$typ='T';
 
$sla='-\n(Sus)\n(Conf)\n';
 
 
 
$slb='-\n(Normal)\n(Needs recheck)\n';
 
 
 
$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
$index=$name.$kk;


 $stmt1 = <<<SQL
INSERT INTO "患者属性一覧"(
            "グループ", "名称", "属性型", "選択肢", "表示順位", 
             length)
 values ('$gp' ,'$index','$typ','$sl','100',$ln) 
SQL;
 print $stmt1;
if (pg_query($db, $stmt1)){
// print $stmt1;
}
else {
print '<p > DB access error</p>';
die;
}
       
 
}
 
 


 

?>


<a href="index.php?tab=1">メインに戻る</a>
<br>

	</tbody>
</table>

	</body>
</html>
