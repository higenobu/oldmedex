<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>PostgreSQL Table Dump</title>
	</head>
	<body>
<?php

echo "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA";


/* データベース名の入力チェック */
if ($_POST['dbname'] == "" || $_POST['table'] == "") {
	echo("<p><span style=\"color:red\">必須項目が入力されてません。</span></p>\n");
	echo("</body></html>\n");
	exit;
}














$con =  pg_connect("host=localhost dbname=$_POST[dbname] user=medex ");
if (!$con) {
	echo("<p><span style=\"color:red\">$_POST[dbname] cannot be connected</span></p>\n");
	echo("</body></html>\n");
	exit;
}


pg_set_client_encoding('EUC_JP');

/* 検索を実行 */
$query = "select * from $_POST[table]";
$rs = pg_query($con, $query);
if (!$rs) {
	
	pg_close($con);
	echo("<p><span style=\"color:red\">$_POST[table] failed </span></p>\n");
	echo("</body></html>\n");
	exit;
}


$maxrows = pg_num_rows($rs);

$maxcols = pg_num_fields($rs);

echo("<h1>[$_POST[table]] Table </h1>");
?>

<table summary="<?= $_POST['table'] ?> display" border="1">
	<caption><?= $_POST['table'] ?> result</caption>
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

  
/*

$mypath="testdir\\subdir\\test";
mkdir($mypath,0777,TRUE);
$filename = $mypath.'\test.txt';
 $fh = fopen($filename, "x+") or die("can't open file");

 */






     $myFile = "test.txt";
      $fh = fopen($myFile, 'w') or die("can't open file");



        $rowscont = "";
 

			/* テーブルのデータを出力 */
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
