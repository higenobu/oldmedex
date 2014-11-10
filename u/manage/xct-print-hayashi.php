<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>xct print</title>
	</head>
	<body>
<a href="index.php?tab=1">メインに戻る</a>

<a href="xct-status.php?tab=1">再実行</a>

<A HREF="javascript:window.print()">印刷</A>


<br>

<?php
//******************************0620-2011******************************
$bui=array();
$bui=array('170011810'=>'CT',
'170027910'=>'XP');
$kiroku=array();


$dateno = 1;
//need to change dbname 0701-2011

$con =  pg_connect("host=localhost dbname=medexdb0 user=medex ");
if (!$con) {
	echo("<p><span style=\"color:red\">medexdb cannot be connected</span></p>\n");
	echo("</body></html>\n");
	exit;
}


  $stmt = <<<SQL
    select E."bui_code" as id ,  E.bui_name as name
    from bui_master4 E 
SQL;
pg_set_client_encoding('EUC_JP');



	$rs = pg_query($con, $stmt);
  $rows = pg_fetch_all($rs);
  
  foreach($rows as $row)
    $bui[$row['id']] = $row['name'];
//*********************** kirokusya**********0622-2011

  $stmt = <<<SQL
    select "姓" || "名" as empname , userid
    from "職員台帳"
SQL;
	$rs = pg_query($con, $stmt);
 	$rows = pg_fetch_all($rs);
  
  foreach($rows as $row)
    $kiroku[$row['userid']] = $row['empname'];
  

/************************update 0616-2011
CREATE OR REPLACE VIEW tbl_rxv AS 
 SELECT p."患者ID", p."姓"::text || p."名"::text AS "氏名", p."フリガナ", p."入外区分", p."入院日", p."退院予定日", o."処方年月日", o."処方開始日", m."販売名", m."レセプト電算処理システムコード（１）" AS code, o."日数", d."用量", n."用法", u."用量単位"
   FROM "薬剤処方箋" o
   JOIN "薬剤処方箋内容" d ON o."ObjectID" = d."薬剤処方箋"
   LEFT JOIN "Medis医薬品マスター" m ON m."ObjectID" = d."薬剤" AND m."Superseded" IS NULL
   LEFT JOIN "処方箋用量単位" u ON d."用量単位" = u."ID" AND u."Superseded" IS NULL
   LEFT JOIN "処方箋用法" n ON d."用法" = n."ID" AND n."Superseded" IS NULL
   LEFT JOIN "患者台帳" p ON o."患者" = p."ObjectID"
  WHERE o."Superseded" IS NULL;

AL

*/


//****************************************************************************updated 0615-2011
  
 pg_set_client_encoding('EUC_JP');



$query = 'select orderdate as オーダ日 , plandate as "予定日", procdate as "実施日", p."患者ID",  p."フリガナ",  p."入外区分", p."病室", p."退院予定日",shiji as 指示医,xct."CreatedBy"as 記録者,
xctkubun as 区分, substring(memo1 for 10 from 1)  as 指示, substring(memo3 for 10 from 1) as 電圧,bui1 as 部位1,syoken1 as 方向1,
 bui2 as 部位2,syoken2 as 方向2,bui3 as 部位3,syoken3 as 方向3,bui4 as 部位4,syoken4 as 方向4, bui5 as 部位5, syoken5 as 方向5, proof,stop  from xctorder as xct
LEFT JOIN "患者台帳" p ON xct."患者" = p."ObjectID"';

$cond11='where xct."患者" !=32834    and xct."患者" !=32683 and stop is null and ';	

$cond01 = "   plandate >=  date'today' - ";


$cond02 = $_POST[plusdate];

$cond03 = " and plandate <= date'today' + ";
$cond04 = $_POST[plusdate2];
$cond05="  and xct.\"Superseded\" is null";
$cond06="  and xct.\"proof\" =";
$cond07=$cond02 = $_POST[proof];
$query = $query.$cond11.$cond01.$cond02.$cond03.$cond04.$cond05.$cond06.$cond07;


//0615-2011  	
$query = $query."  order by plandate desc  limit 1000";







$rs = pg_query($con, $query);
if (!$rs) {
	
	pg_close($con);
	echo("<p><span style=\"color:red\">$_POST[table] failed </span></p>\n");
	echo("</body></html>\n");
	exit;
}


$maxrows = pg_num_rows($rs);

$maxcols = pg_num_fields($rs);

echo("<h1>画像実施予定一覧 </h1>");
?>

<table summary="<?= $_POST['table'] ?> display" border="1">
	<caption><?= $_POST['table'] ?>  </caption>
	<thead>
		<tr>
			<?
				/* テーブルのヘッダーを出力 */
				for ($col = 0; $col < $maxcols-1; $col++) {
					/* pg_field_name() はフィールド名を返す */
					$f_name = htmlspecialchars(pg_field_name($rs, $col));
					echo("<th abbr=\"$f_name\">$f_name</th>\n");
				}
			?>
		</tr>
	</thead>
	<tbody>

      <?

  









 $mypath="/home/medex/test/";
  mkdir($mypath,0777,TRUE);

$filename = $mypath."test.txt";
 $fh = fopen($filename, "w+") or die("can't open file");







 /*
     $myFile = "test.txt";
      $fh = fopen($myFile, 'w') or die("can't open file");

 */


        $rowscont = "";
 

			/* first row as a fieid */

				$f_name = null;
				for ($col = 0; $col < $maxcols-1; $col++) {
					/* pg_field_name() はフィールド名を返す do not show stop */
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
				

				for ($col = 0; $col < $maxcols-1; $col++) { /* 列に対応 delete stop  */
				
				

				

		if ($col == 10||  $col == 13||$col == 15||$col == 17||$col == 19||$col ==21 ){ 

                         
				$buiname = $bui[$rowdata[$col]];
				$rowdata[$col]= $buiname ;
				}

		if ($col==9){
			$kirokuname=$kiroku[$rowdata[$col]];
			$rowdata[$col]= $kirokuname ;
			}

				 echo("<td>".htmlspecialchars($rowdata[$col])."<br></td>\n");
                           

				$rowscont = $rowscont.$rowdata[$col].";";




                          }
					
				echo("</tr>\n");
			

 			$rowscont = $rowscont."\n";
 			 fwrite($fh, $rowscont); 

			}
			/* データベースとの接続を切り離す */
			pg_close($con);
                fclose($fh); 
 

?>

	</tbody>
     </table>

	</body>






</html>
