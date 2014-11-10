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


<br>


<?php

include_once $_SERVER['DOCUMENT_RO$_POST[plusdate]OT'].'/lib/common.php';

pg_set_client_encoding('EUC_JP');

$con =  pg_connect("host=localhost dbname=orca user=orca ");
if (!$con) {
	echo("<p><span style=\"color:red\">orca cannot be connected</span></p>\n");
	echo("</body></html>\n");
	exit;
}




if ($_POST[kikan]=='1'){
$min=$_POST[ym].'01';
$max=$_POST[ym].'11';}

if ($_POST[kikan]=='2'){
$min=$_POST[ym].'11';
$max=$_POST[ym].'20';}
if ($_POST[kikan]=='3'){
$min=$_POST[ym].'21';
$max=$_POST[ym].'31';}






$query = "SELECT k.ptid, p.ptnum, k.rrknum, k.rrkedanum, btunum, btuname, brmnum, nyuinka, hkncombinum, 
       nyuinymd, taiinymd,tennyuymd,tenstuymd, i.name, i.kananame,i.birthday,i.sex,
home_post ,
  home_adrs,
  home_banti ,
  home_tel1
  FROM tbl_ptnyuinrrk as k left join tbl_ptnum  p on k.ptid=p.ptid 
left join tbl_ptinf i on k.ptid=i.ptid
where

(tennyuymd<'$min' and tenstuymd >='$min' and tenstuymd<'$max')
or
(tennyuymd >='$min' and tenstuymd <'$max') or
(tennyuymd >='$min' and tennyuymd <'$max' and tenstuymd >='$max') or
(tennyuymd<'$min' and  tenstuymd>='$max')
  order by ptid
"; 
	









/* $query = "SELECT t.ObjectID AS test_order_id, a.ObjectID  AS content_id, t.Patient  AS patient_id, a.TestID  as TestID".
"FROM    test_order t LEFT JOIN  test_order_content  a ON a.TestOrder  = t.ObjectID "."LEFT JOIN  test_master  d ON".
" a.TestID  = d.ObjectID LEFT JOIN  患者台帳  p ON t.Patient = p.ObjectID where t.Cancelled  is null;";　*/






print $query;

$rs = pg_query($con, $query);
if (!$rs) {
	
	pg_close($con);
	echo("<p><span style=\"color:red\">orca failed </span></p>\n");
	echo("</body></html>\n");
	exit;
}


$maxrows = pg_num_rows($rs);

$maxcols = pg_num_fields($rs);

echo("<h1>差額一覧 </h1>");
?>

<table summary="<?= sagaku ?> display" border="1">
	<caption><?= sagaku ?> result</caption>
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

  


 $mypath="/home/medex/files/";
 

$filename = $mypath."sagaku001.csv";
 $fh = fopen($filename, "w+") or die("can't open file");







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

			/*  $f_name= $f_name."\n";
 			 fwrite($fh, $f_name);  */

                      

			for ($row = 0; $row < $maxrows; $row++) { /* 行に対応 */
				echo("<tr>\n");
				/* pg_fetch_row で一行取り出す */
				$rowdata = pg_fetch_row($rs, $i);
                                $rowscont = null;
				

				for ($col = 0; $col < $maxcols; $col++) { /* 列に対応 */
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
