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

$query1='SELECT pt_no as classno,pt_l as dai,pt_f as tyu,pt_kana as syo, 
 recorded_on, 
       order_date, preorderdate, category, addition, orderid, pdf, special_req, 
       notes, kk0, ss0, cc0, kk1, ss1, cc1, kk2, ss2, cc2, kk3, ss3, 
       cc3, kk4, ss4, cc4, pp0, pp1, pp2, pp3, pp4, kk5, pp5, cc5, ss5, 
       kk6, pp6, ss6, cc6, kk7, pp7, ss7, cc7, kk8, pp8, ss8, cc8, kk9, 
       pp9, cc9, ss9, kk10, pp10, ss10, cc10, kk11, pp11, ss11, cc11, 
       kk12, pp12, ss12, cc12, kk13, pp13, ss13, cc13, kk14, pp14, ss14, 
       cc14, kk15, pp15, ss15, cc15, kk20, pp20, ss20, cc20, kk21, pp21, 
       ss21, cc21, kk22, pp22, ss22, cc22, kk40, pp40, ss40, cc40, kk41, 
       pp41, ss41, cc41, kk42, pp42, ss42, cc42, kk50, pp50, ss50, cc50, 
       kk51, pp51, ss51, cc51, kk52, pp52, ss52, cc52, kk53, pp53, ss53, 
       cc53, kk54, pp54, ss54, cc54, kk55, pp55, ss55, cc55, kk56, pp56, 
       ss56, cc56, kk57, pp57, ss57, cc57, kk58, pp58, ss58, cc58, kk59, 
       pp59, ss59, cc59, kk30, pp30, ss30, cc30, kk31, pp31, ss31, cc31, 
       kk32, pp32, ss32, cc32, kk33, pp33, ss33, cc33, kk34, pp34, ss34, 
       cc34, kk35, pp35, ss35, cc35, kk36, pp36, ss36, cc36, kk37, pp37, 
       ss37, cc37, kk38, pp38, ss38, cc38, kk39, pp39, ss39, cc39, kk60, 
       pp60, ss60, cc60, kk61, pp61, ss61, cc61, kk62, pp62, ss62, cc62, 
       kk63, pp63, ss63, cc63, kk64, pp64, ss64, cc64, kk65, pp65, ss65, 
       cc65, kk66, pp66, ss66, cc66, kk67, pp67, ss67, cc67, kk70, pp70, 
       ss70, cc70, kk71, pp71, ss71, cc71, kk72, pp72, ss72, cc72, kk73, 
       pp73, ss73, cc73, kk74, pp74, ss74, cc74, kk75, pp75, ss75, cc75, 
       kk76, pp76, ss76, cc76, kk77, pp77, ss77, cc77, kk78, pp78, ss78, 
       cc78, kk79, pp79, ss79, cc79, kk90, pp90, ss90, cc90, kk91, pp91, 
       ss91, cc91, kk92, pp92, ss92, cc92, kk93, pp93, ss93, cc93, kk94, 
       pp94, ss94, cc94, kk95, pp95, ss95, cc95, k100, k101, k102, k103, 
       k104, k105, k106, p100, p101, p102, p103, p104, p105, p106, k200, 
       k201, k202, k203, k204, k205, k206, k207, k208, p200, p201, p202, 
       p203, p204, p205, p206, p207, p208, k300, k301, k302, p300, p301, 
       p302, k500, k501, k502, k503, k504, k505, k506, k507, k508, k509, 
       k510, k511, k512, k513, k514, k515, k516, k517, k518, k519, k520, 
       p500, p501, p502, p503, p504, p505, p506, p507, p508, p509, p510, 
       p511, p512, p513, p514, p515, p516, p517, p518, p519, p520, c500, 
       c501, c502, c503, c504, c505, c506, c507, c508, c509, c510, c511, 
       c512, c513, c514, c515, c516, c517, c518, c519, c520, k80, k81, 
       k82, k83, k84, k85, k86, k87, k88, k89, k90, k91, p80, p81, p82, 
       p83, p84, p85, p86, p87, p88, p89, p90, p91, k400, k401, k402, 
       k403, k404, k405, k406, k407, k408, k409, k410, k411, k412, k413, 
       k414, k415, plandate, kk600, pp600, kk601, pp601, kk602, pp602, 
       kk603, pp603, kk604, pp604, kk605, pp605, kk606, pp606, kk607, 
       pp607, kk608, pp608, kk609, pp609, kk610, pp610, kk611, pp611, 
       kk612, pp612, kk613, pp613, kk614, pp614, kk615, pp615, kk616, 
       pp616, kk617, pp617, kk618, pp618, kk619, pp619, kk620, pp620, 
       aa1, aa2, aa3, aa4, aa5, aa6, aa7, aa8, aa9, aa10, aa11, aa12, 
       aa13, aa14, aa15, aa16, aa17, aa18, aa19, aa20, aa21, aa22, aa23, 
       aa24, aa25, aa26, aa27, aa28, aa29, aa30, aa31, aa32, aa33, aa34, 
       aa35, aa36, aa37, aa38, aa39, aa40, aa41, aa42, aa43, aa44, aa45, 
       aa46, aa47, aa48, aa49, aa50, cc16, cc17, cc18, cc19, kk530, 
       cc530, pp530, kk540, kk531, cc531, pp531, kk541, kk532, cc532, 
       pp532, kk542, k107, p107, aa51, aa52, aa53, aa54, aa55, aa56, 
       aa57, aa58, aa59, shiji, cc400, k416, cc401, cc402, cc403, cc404, 
       cc405, cc406, cc407, cc408, cc409, cc410, cc411, cc412, cc413, 
       cc414, cc415, cc416, cc417, k417, kk417, k1000, p1000, k1001, 
       p1001, k1003, p1003, aa1003, k1002, p1002, c1002, k1004, p1004, 
       aa94, k1005, p1005, aa103, aa104, aa105, aa1005, k1006, p1006, 
       k1007, p1007, aa1006, aa1007, k418, cc418
  FROM otatest_order
  join tbl_patientv2 on pt_id="patient" ';
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
 









print $query;

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
 
$query1='SELECT pt_no as classno,pt_l as dai,pt_f as tyu,pt_kana as syo,recorded_on, 
       order_date, preorderdate, category, addition, orderid, pdf, special_req, 
       notes, kk0, ss0, cc0, kk1, ss1, cc1, kk2, ss2, cc2, kk3, ss3, 
       cc3, kk4, ss4, cc4, pp0, pp1, pp2, pp3, pp4, kk5, pp5, cc5, ss5, 
       kk6, pp6, ss6, cc6, kk7, pp7, ss7, cc7, kk8, pp8, ss8, cc8, kk9, 
       pp9, cc9, ss9, kk10, pp10, ss10, cc10, kk11, pp11, ss11, cc11, 
       kk12, pp12, ss12, cc12, kk13, pp13, ss13, cc13, kk14, pp14, ss14, 
       cc14, kk15, pp15, ss15, cc15, kk20, pp20, ss20, cc20, kk21, pp21, 
       ss21, cc21, kk22, pp22, ss22, cc22, kk40, pp40, ss40, cc40, kk41, 
       pp41, ss41, cc41, kk42, pp42, ss42, cc42, kk50, pp50, ss50, cc50, 
       kk51, pp51, ss51, cc51, kk52, pp52, ss52, cc52, kk53, pp53, ss53, 
       cc53, kk54, pp54, ss54, cc54, kk55, pp55, ss55, cc55, kk56, pp56, 
       ss56, cc56, kk57, pp57, ss57, cc57, kk58, pp58, ss58, cc58, kk59, 
       pp59, ss59, cc59, kk30, pp30, ss30, cc30, kk31, pp31, ss31, cc31, 
       kk32, pp32, ss32, cc32, kk33, pp33, ss33, cc33, kk34, pp34, ss34, 
       cc34, kk35, pp35, ss35, cc35, kk36, pp36, ss36, cc36, kk37, pp37, 
       ss37, cc37, kk38, pp38, ss38, cc38, kk39, pp39, ss39, cc39, kk60, 
       pp60, ss60, cc60, kk61, pp61, ss61, cc61, kk62, pp62, ss62, cc62, 
       kk63, pp63, ss63, cc63, kk64, pp64, ss64, cc64, kk65, pp65, ss65, 
       cc65, kk66, pp66, ss66, cc66, kk67, pp67, ss67, cc67, kk70, pp70, 
       ss70, cc70, kk71, pp71, ss71, cc71, kk72, pp72, ss72, cc72, kk73, 
       pp73, ss73, cc73, kk74, pp74, ss74, cc74, kk75, pp75, ss75, cc75, 
       kk76, pp76, ss76, cc76, kk77, pp77, ss77, cc77, kk78, pp78, ss78, 
       cc78, kk79, pp79, ss79, cc79, kk90, pp90, ss90, cc90, kk91, pp91, 
       ss91, cc91, kk92, pp92, ss92, cc92, kk93, pp93, ss93, cc93, kk94, 
       pp94, ss94, cc94, kk95, pp95, ss95, cc95, k100, k101, k102, k103, 
       k104, k105, k106, p100, p101, p102, p103, p104, p105, p106, k200, 
       k201, k202, k203, k204, k205, k206, k207, k208, p200, p201, p202, 
       p203, p204, p205, p206, p207, p208, k300, k301, k302, p300, p301, 
       p302, k500, k501, k502, k503, k504, k505, k506, k507, k508, k509, 
       k510, k511, k512, k513, k514, k515, k516, k517, k518, k519, k520, 
       p500, p501, p502, p503, p504, p505, p506, p507, p508, p509, p510, 
       p511, p512, p513, p514, p515, p516, p517, p518, p519, p520, c500, 
       c501, c502, c503, c504, c505, c506, c507, c508, c509, c510, c511, 
       c512, c513, c514, c515, c516, c517, c518, c519, c520, k80, k81, 
       k82, k83, k84, k85, k86, k87, k88, k89, k90, k91, p80, p81, p82, 
       p83, p84, p85, p86, p87, p88, p89, p90, p91, k400, k401, k402, 
       k403, k404, k405, k406, k407, k408, k409, k410, k411, k412, k413, 
       k414, k415, plandate, kk600, pp600, kk601, pp601, kk602, pp602, 
       kk603, pp603, kk604, pp604, kk605, pp605, kk606, pp606, kk607, 
       pp607, kk608, pp608, kk609, pp609, kk610, pp610, kk611, pp611, 
       kk612, pp612, kk613, pp613, kk614, pp614, kk615, pp615, kk616, 
       pp616, kk617, pp617, kk618, pp618, kk619, pp619, kk620, pp620, 
       aa1, aa2, aa3, aa4, aa5, aa6, aa7, aa8, aa9, aa10, aa11, aa12, 
       aa13, aa14, aa15, aa16, aa17, aa18, aa19, aa20, aa21, aa22, aa23, 
       aa24, aa25, aa26, aa27, aa28, aa29, aa30, aa31, aa32, aa33, aa34, 
       aa35, aa36, aa37, aa38, aa39, aa40, aa41, aa42, aa43, aa44, aa45, 
       aa46, aa47, aa48, aa49, aa50, cc16, cc17, cc18, cc19, kk530, 
       cc530, pp530, kk540, kk531, cc531, pp531, kk541, kk532, cc532, 
       pp532, kk542, k107, p107, aa51, aa52, aa53, aa54, aa55, aa56, 
       aa57, aa58, aa59, shiji, cc400, k416, cc401, cc402, cc403, cc404, 
       cc405, cc406, cc407, cc408, cc409, cc410, cc411, cc412, cc413, 
       cc414, cc415, cc416, cc417, k417, kk417, k1000, p1000, k1001, 
       p1001, k1003, p1003, aa1003, k1002, p1002, c1002, k1004, p1004, 
       aa94, k1005, p1005, aa103, aa104, aa105, aa1005, k1006, p1006, 
       k1007, p1007, aa1006, aa1007, k418, cc418
  FROM otatest_order join tbl_patientv2 on pt_id="patient" ';
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
$order=' ';
$query = $query1.$cond00.$cond01.$mark.$cond02.$mark.$and.$con1.$mark.$con3.$mark.$and.$cona2.$mark.$conb2.$mark.$and.$condp1.$mark.$condp2.$mark.$order;
print $query;

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
 
