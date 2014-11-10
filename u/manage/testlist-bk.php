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

<a href="testlist-app.php?tab=1">再実行</a>


<br>

<?php

//0708-2011
$con =  pg_connect("host=localhost dbname=medexdb5 user=medex ");
if (!$con) {
	echo("<p><span style=\"color:red\">medexdb5 cannot be connected</span></p>\n");
	echo("</body></html>\n");
	exit;
}


  
pg_set_client_encoding('EUC_JP');

$uketuke = $_POST[uketuke];
$byoto = $_POST[byoto];



$query='SELECT   p."患者ID", p."姓", p."名", p."フリガナ", p."入外区分", p."入院日", p."退院予定日", p."病室",  date("OrderDate") as "オーダ日", date("SampleDate")  as "採取", kekkadate as "UP",  urgent as "緊急", "comment", "Cancelled" as "中止"  FROM test_order  as  o LEFT JOIN "患者台帳" p ON o."Patient" = p."ObjectID"  WHERE o."Superseded" IS NULL ';







$cond01 = ' and  "OrderDate" >='."  date'today' - ";	



$cond02 = $_POST[plusdate];

$cond03 = ' and  "OrderDate" <= '."date'today' + ";
$cond04 = $_POST[plusdate2];

$cond011 = " and  date( 受付時刻 ) >=  date'today' - ";	



$cond021 = $_POST[plusdate];

$cond031 = " and   date( 受付時刻 ) <= date'today' + ";
$cond041 = $_POST[plusdate2];


	


$cond05="  ";

$cond06=' and p. "希望病棟"  = ';
$cond07="'".$byoto."' ";


$query = $query.$cond01.$cond02.$cond03.$cond04.$cond05;
	
$query = $query."  order by  ".'"OrderDate" desc  limit 1000';


 print $query;






$rs = pg_query($con, $query);
if (!$rs) {
	
	pg_close($con);
	echo("<p><span style=\"color:red\">rx-order table failed </span></p>\n");
	echo("</body></html>\n");
	exit;
}


$maxrows = pg_num_rows($rs);

$maxcols = pg_num_fields($rs);

echo("<h1>検体検査オーダ・結果一覧 </h1>");
?>

<table summary="<?= $_POST['table'] ?> display" border="1">
	<caption><?= $_POST['table'] ?> Status </caption>
	<thead>
		<tr>
			<?
				/* テーブルのヘッダーを出力 */
				for ($col = 0; $col < $maxcols; $col++) {
					/* pg_field_name() はフィールド名を返す */
					$f_name = htmlspecialchars(pg_field_name($rs, $col));
					echo("<th abbr=\"$f_name\">$f_name</th>\n");
				}
			?>
		</tr>
	</thead>
	<tbody>

      <?

  









 $mypath="/home/medex/rx/";
//  mkdir($mypath,0777,TRUE);

$filename = $mypath."rx.txt";
// $fh = fopen($filename, "w+") or die("can't open file");







 /*
     $myFile = "test.txt";
      $fh = fopen($myFile, 'w') or die("can't open file");

 */


        $rowscont = "";
 

			/* first row as a fieid */

				$f_name = null;
				for ($col = 0; $col < $maxcols; $col++) {
					/* pg_field_name() はフィールド名を返す */
					$f_name = $f_name.pg_field_name($rs, $col).";";
					
				}

			/*$f_name= $f_name."\n";
 			 fwrite($fh, $f_name);
                        */

			for ($row = 0; $row < $maxrows; $row++) { /* 行に対応 */
				echo("<tr>\n");
				/* pg_fetch_row で一行取り出す */
				$rowdata = pg_fetch_row($rs, $i);
                                $rowscont = null;
				
			//	$url="/s/medex/farm/php/html/u/doctor/xctorder2-app.php?";
			//	echo("<a href=".$url."></a>");

				for ($col = 0; $col < $maxcols; $col++) { /* 列に対応 */
				
				

				

		
				 echo("<td>".htmlspecialchars($rowdata[$col])."<br></td>\n");
                           

				$rowscont = $rowscont.$rowdata[$col].";";




                          }
					
				echo("</tr>\n");
			

 			$rowscont = $rowscont."\n";
 //			 fwrite($fh, $rowscont); 

			}
			/* データベースとの接続を切り離す */
			pg_close($con);
 //               fclose($fh); 
 

?>

	</tbody>
     </table>

	</body>






</html>
