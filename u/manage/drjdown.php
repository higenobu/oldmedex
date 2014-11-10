<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>PostgreSQL Table Dump</title>
	</head>
	<body>
<a href="index.php?tab=1">メインに戻る</a>

<a href="test.csv"target="_blank">download</a>
<br>


<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';


$con = mx_db_connect();


//1001-2013

pg_set_client_encoding('EUC_JP');

// pg_set_client_encoding('SJIS');
 
$copy="copy (";
$to=" ) to '/home/medex/drj/testdown.csv' DELIMITER ';'";

$query1='SELECT pt_no as classno,pt_l as dai,pt_f as tyu,pt_kana as syo, "S0" as kanten,"A" as koban,"O0" as naiyo,"S1" as testcond, "S2" as kakunin, "S3"as kitaichi,"S4"as taisyo
FROM "カルテデモ表"  join tbl_patientv2 on pt_id="患者" ';
$cond00=' where "Superseded" is null and ';
$cond01 = '  pt_l like  ';
$condp1= ' pt_no like  ';
$condp2 ="%".$_POST['class']."%";
$cond02 = "%".$_POST['big']."%";
$mark ="'";
 $and=" and ";
$con1='pt_f like ';
$con3 = "%".$_POST[middle]."%";
$cona2='pt_kana like ';
$conb2 = "%".$_POST[small]."%";
$query = $query1.$cond00.$cond01.$mark.$cond02.$mark.$and.$con1.$mark.$con3.$mark.$and.$cona2.$mark.$conb2.$mark.$and.$condp1.$mark.$condp2.$mark;

$query2=$copy.$query.$to;

 /*
 $query = "copy (SELECT s0,s1,s2,s3,p0,p1,p2,p3,p4,p5,p6
   FROM drjms "; 
$to=" ) to '/home/medex/drj/testdown.csv' DELIMITER ';'";
$cond00=' where "Superseded" is null and ';	
$cond01 = " s0 like  ";

$cond02 = "%".$_POST['class']."%";
$mark ="'";
 $and=" and ";
$con1="s1 like ";
$con3 = "%".$_POST[big]."%";
$kakko=")";
$query = $query.$cond00.$cond01.$mark.$cond02.$mark.$and.$con1.$mark.$con3.$mark.$to;
 
*/
 









//print $query;

$rs = pg_query($con, $query2);
if (!$rs) {
	
	pg_close($con);
	echo("<p><span style=\"color:red\"> 1 failed </span></p>\n");
	echo("</body> </html>\n");
	exit;
}

//



//$query1 = "SELECT s0,s1,s2,s3,p0,p1,p2,p3,p4,p5,p6
//  FROM drjms "; 
 
$query1='SELECT pt_no as classno,pt_l as dai,pt_f as tyu,pt_kana as syo,"A" as koban, "S0" as kanten,"O0" as naiyo,"S1" as testcond, "S2" as kakunin, "S3"as kitaichi,"S4"as taisyo
FROM "カルテデモ表"  join tbl_patientv2 on pt_id="患者" ';
$cond00=' where "Superseded" is null and ';
$cond01 = '  pt_l like  ';
$condp1= ' pt_no like  ';
$condp2 ="%".$_POST['class']."%";
$cond02 = "%".$_POST['big']."%";
$mark ="'";
 $and=" and ";
$con1='pt_f like ';
$con3 = "%".$_POST[middle]."%";
$cona2='pt_kana like ';
$conb2 = "%".$_POST[small]."%";
$order=' order by "A"';
$query = $query1.$cond00.$cond01.$mark.$cond02.$mark.$and.$con1.$mark.$con3.$mark.$and.$cona2.$mark.$conb2.$mark.$and.$condp1.$mark.$condp2.$mark.$order;
//print $query;

/*

	
$cond01 = " where  s0 like  ";

$cond02 = "%".$_POST[bunrui]."%";
$mark ="'";
 $and=" and ";
$con1="s1 like ";
$con3 = "%".$_POST[big]."%";
$query = $query1.$cond01.$mark.$cond02.$mark.$and.$con1.$mark.$con3.$mark;
 */


$rs = pg_query($con, $query);
if (!$rs) {
	
	pg_close($con);
	echo("<p><span style=\"color:red\">2 failed </span></p>\n");
	echo("</body> </html>\n");
	exit;
}

$maxrows = pg_num_rows($rs);

$maxcols = pg_num_fields($rs);

echo("<h1>TEST一覧 </h1>");
?>

<table summary="<?= $_POST['table'] ?> display" border="1">
	<caption><?= $_POST['table'] ?> result</caption>
	<thead>
		<tr>
			<?
				
				for ($col = 0; $col < $maxcols; $col++) {
					 
					$f_name = htmlspecialchars(pg_field_name($rs, $col));
					echo("<th abbr=\"$f_name\">$f_name</th>\n");
				}
			?>
		</tr>
	</thead>
	<tbody>

      <?

  
 

//$mypath="/home/medex/drj/";
 $mypath="/s/medex/farm/php/html/u/manage/";

 $filename = $mypath."test.csv";
  $fh = fopen($filename, "w+") or die("can't open file");






 

        $rowscont = "";
 

			 

				$f_name = null;
				for ($col = 0; $col < $maxcols; $col++) {
					 
					$f_name = $f_name.pg_field_name($rs, $col).";";
					
				}
 

                      

			for ($row = 0; $row < $maxrows; $row++) {  
				echo("<tr>\n");
				 
				$rowdata = pg_fetch_row($rs, $i);
                                $rowscont = null;
				

				for ($col = 0; $col < $maxcols; $col++) {  
				 echo("<td>".htmlspecialchars($rowdata[$col])."<br></td>\n");
        

				
                              $rowscont = $rowscont.$rowdata[$col].";";
                        		  }
					
				echo("</tr>\n");
//1001-2013			

 			 $rowscont = $rowscont."\r\n";
$csvdata=mb_convert_encoding($rowscont,"sjis-win","EUC-JP");
 			 fwrite($fh, $csvdata); 

			}
			 
			pg_close($con);
              fclose($fh); 


//download to client
//echo('<a href="/home/medex/drj/testdown.csv" target="_blank">download</a>');
//echo("\n");
	//echo("</body> </html>\n"); 
?>
 
