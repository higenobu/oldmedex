<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>Table-to-csv converter</title>
	</head>
	<body>
<a href="index.php?tab=1">�ᥤ������</a>


<br>


<?php




/* �ǡ����١���̾�����ϥ����å� */
if ($_POST['dbname'] == "" || $_POST['table'] == "") {
	echo("<p><span style=\"color:red\">ɬ�ܹ��ܤ����Ϥ���Ƥޤ���</span></p>\n");
	echo("</body></html>\n");
	exit;
}


/* $dateno = $_POST[today-date]; */  
$dateno = 1;












$con =  pg_connect("host=localhost dbname=$_POST[dbname] user=medex ");
if (!$con) {
	echo("<p><span style=\"color:red\">$_POST[dbname] cannot be connected</span></p>\n");
	echo("</body></html>\n");
	exit;
}


pg_set_client_encoding('EUC_JP');

/* ������¹� */
 $query = "select * from $_POST[table] "; 


if ( $_POST['table'] == "xctorder"  ) {
	

$cond01 = " where plandate >=  date'today' + ";

$cond02 = $_POST[plusdate];

$cond03 = " and plandate <= date'today' + ";
$cond04 = $_POST[plusdate2];

$query = $query.$cond01.$cond02.$cond03.$cond04;


}


if (  $_POST['table'] == "tbl_yakuzaiv") {
	

$cond01 = " where  ���������� >=  date'today' + ";

$cond02 = $_POST[plusdate];

$cond03 = " and  ���������� <= date'today' + ";
$cond04 = $_POST[plusdate2];


if ($_POST[inout] == 'I' || $_POST[inout] == 'O')
{$cond06 = $_POST[inout];

$cond05= " and ������ʬ = "."'".$cond06."'";
$query = $query.$cond01.$cond02.$cond03.$cond04.$cond05;
} 
else {
$query = $query.$cond01.$cond02.$cond03.$cond04;
}

}


if (  $_POST['table'] == "tbl_plist") {
	



if ($_POST[inout] == 'I' || $_POST[inout] == 'O' )
	{$cond06 = $_POST[inout];

$cond05= " where  ������ʬ = "."'".$cond06."'";
$query = $query.$cond05;

	
	}

 if ($_POST[room] == '0')
{
$query = $query." ";
}
else
{
$cond07 = " and �¼� = "."'".$_POST[room]."'";
$query = $query.$cond07;


}



}


	
$query = $query."limit 1000";






/* $query = "SELECT t.ObjectID AS test_order_id, a.ObjectID  AS content_id, t.Patient  AS patient_id, a.TestID  as TestID".
"FROM    test_order t LEFT JOIN  test_order_content  a ON a.TestOrder  = t.ObjectID "."LEFT JOIN  test_master  d ON".
" a.TestID  = d.ObjectID LEFT JOIN  ������Ģ  p ON t.Patient = p.ObjectID where t.Cancelled  is null;";��*/






print $query;

$rs = pg_query($con, $query);
if (!$rs) {
	
	pg_close($con);
	echo("<p><span style=\"color:red\">$_POST[table] failed </span></p>\n");
	echo("</body></html>\n");
	exit;
}


$maxrows = pg_num_rows($rs);

$maxcols = pg_num_fields($rs);

echo("<h1>Table���� </h1>");
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

  


 $mypath="/home/www-data/test/";
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
				for ($col = 0; $col < $maxcols; $col++) {
					/* pg_field_name() �ϥե������̾���֤� */
					$f_name = $f_name.pg_field_name($rs, $col).";";
					
				}

			/*$f_name= $f_name."\n";
 			 fwrite($fh, $f_name);
                        */

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
