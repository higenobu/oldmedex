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


/* �ǡ����١���̾�����ϥ����å� */
if ($_POST['dbname'] == "" || $_POST['table'] == "") {
	echo("<p><span style=\"color:red\">ɬ�ܹ��ܤ����Ϥ���Ƥޤ���</span></p>\n");
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

/* ������¹� */
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
				/* �ơ��֥�Υإå�������� */
				for ($col = 0; $col < $maxcols; $col++) {
					/* pg_field_name() �ϥե������̾���֤� */
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
 

			/* �ơ��֥�Υǡ�������� */
			for ($row = 0; $row < $maxrows; $row++) { /* �Ԥ��б� */
				echo("<tr>\n");
				/* pg_fetch_row �ǰ�Լ��Ф� */
				$rowdata = pg_fetch_row($rs, $i);
                                $rowscont = null;
				

				for ($col = 0; $col < $maxcols; $col++) { /* ����б� */
				 echo("<td>".htmlspecialchars($rowdata[$col])."<br></td>\n");
                              $rowscont = $rowscont.$rowdata[$col].";";
                          }
					
				echo("</tr>\n");
			

 			$rowscont = $rowscont."\n";
 			 fwrite($fh, $rowscont);

			}
			/* �ǡ����١����Ȥ���³���ڤ�Υ�� */
			pg_close($con);
                 fclose($fh); 

?>

	</tbody>
</table>

	</body>
</html>
