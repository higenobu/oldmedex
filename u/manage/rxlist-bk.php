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

<a href="rxlist-app.php?tab=1">再実行</a>
<A HREF="javascript:window.print()">PRINT</A>

<br>

<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';


$con = mx_db_connect();




  
pg_set_client_encoding('EUC_JP');

$uketuke = $_POST[uketuke];
$byoto = $_POST[byoto];

if ($uketuke ==2){
$query='SELECT p."患者ID", p."姓", p."名", p."フリガナ", p."入外区分", p."入院日", p."退院予定日", p."病室", o."ObjectID", o."処方年月日", o."処方開始日",u."受付時刻",u."ステータス" FROM "薬剤処方箋" as  o LEFT JOIN "患者台帳" p ON o."患者" = p."ObjectID" JOIN "受付患者表"  u ON o."患者"=u."患者" WHERE o."Superseded" IS NULL ';
}
else {
$query='SELECT  distinct(o."ObjectID"),p."患者ID", p."姓", p."名", p."フリガナ", p."入外区分", p."入院日", p."退院予定日", p."病室", o."処方年月日", o."処方開始日" FROM "薬剤処方箋" as  o LEFT JOIN "患者台帳" p ON o."患者" = p."ObjectID"  WHERE o."Superseded" IS NULL ';
}




//$query =' select "患者ID",   "フリガナ",  "入外区分",  "入院日", "退院予定日",  "病室",  "処方年月日", "処方開始日"
//FROM tbl_rxv1  ';
// $query = "select * from tbl_rxv as rx ";


$cond01 = " and  処方開始日 >=  date'today' - ";	

//$cond01 = " where  処方開始日 >=  date'today' - ";

$cond02 = $_POST[plusdate];

$cond03 = " and  処方開始日 <= date'today' + ";
$cond04 = $_POST[plusdate2];

$cond011 = " and  date( 受付時刻 ) >=  date'today' - ";	

//$cond01 = " where  処方開始日 >=  date'today' - ";

$cond021 = $_POST[plusdate];

$cond031 = " and   date( 受付時刻 ) <= date'today' + ";
$cond041 = $_POST[plusdate2];

//date("受付時刻") >= date'today' -0 and date("受付時刻" )<= date'today' + 0
	


$cond05="  ";

$cond06=' and p. "希望病棟"  = ';
$cond07="'".$byoto."' ";

if ($uketuke ==2){
$query = $query.$cond01.$cond02.$cond03.$cond04.$cond05.$cond011.$cond021.$cond031.$cond041.$cond05;
}
else {
$query = $query.$cond01.$cond02.$cond03.$cond04.$cond05.$cond06.$cond07;
}

//0615-2011  	
$query = $query."  order by  処方開始日 desc  limit 1000";


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

echo("<h1>処方オーダ一覧 </h1>");
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
