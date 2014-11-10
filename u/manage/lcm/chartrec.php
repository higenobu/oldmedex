<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>PostgreSQL Table Dump</title>
	</head>
	<body>
<a href="index.php?tab=1">go to main</a>

 
<br>


<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';


$con = mx_db_connect();

$tblnm='otatest_order';


//pg_set_client_encoding('EUC_JP');

$query = "SELECT   order_date,x.pt_nm,x.dob, 
x.company,shiji,category,k501,k509,k510,k561,k560,k506,
k508,k513,k502,k504,k1002,k403
 FROM otatest_order
join tbl_pt  x on ptid=patient where ";
 
$condsuper='"Superseded" is null and';	
 $cond01 = "  order_date >=  date'today' - ";

 $cond02 = $_POST[plusdate];

 $cond03 = " and  order_date <= date'today' + ";
 $cond04 = $_POST[plusdate2];

 $query = $query.$condsuper.$cond01.$cond02.$cond03.$cond04;
//****************************
//print $query;

$rs = pg_query($con, $query);
if (!$rs) {
	
	pg_close($con);
	echo("<p><span style=\"color:red\">chart table failed </span></p>\n");
	echo("</body> </html>\n");
	exit;
}


$maxrows = pg_num_rows($rs);

$maxcols = pg_num_fields($rs);

echo("<h1>Chart record list </h1>");
?>

<table summary="<?= $tblnm ?> display" border="1">
	<caption><?= $tblnm ?> result</caption>
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

  


 $mypath="/home/medex/";
 

$filename = $mypath."lcmtest.csv";
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
 $csvdata=mb_convert_encoding($rowscont,"sjis-win","EUC-JP");
 			 fwrite($fh, $csvdata); 
//			 fwrite($fh, $rowscont); 

			}
			/* データベースとの接続を切り離す */
			pg_close($con);
               fclose($fh); 
 
  
    

?>

	</tbody>
     </table>

	</body>






</html>
