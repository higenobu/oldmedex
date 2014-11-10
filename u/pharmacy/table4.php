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
$query = "select * from $_POST[table] "; 


/* $cond01 = " where id < 5 ;";

$query = $query.$cond01;

*/

$query = "SELECT t."ObjectID" AS test_order_id, a."ObjectID" AS content_id,

t."Patient" AS patient_id, a."TestID" as TestID,
p."��" AS patient_lastname,p."̾" AS patient_firstname,
d."Name" as test_name,
d."ReceiptSystemCode" as receptcode,
t."OrderDate" as orderdate,
t."SampleDate" as sampledate,

t."PatientGroup" as patientgroup,

t.urgent as urgent1,

t."DrCode" as doctor

FROM "test_order" t
LEFT JOIN "test_order_content" a ON a."TestOrder" =

t."ObjectID"
LEFT JOIN "test_master" d ON a."TestID" = d."ObjectID"
LEFT JOIN "������Ģ" p ON t."Patient" = p."ObjectID"

where t."Cancelled" is null;";


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

			$f_name= $f_name."\n";
 			 fwrite($fh, $f_name);

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