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

<a href="solrxlist-app.php?tab=1">�Ƽ¹�</a>


<br>

<?php


$dateno = 1;
$con =  pg_connect("host=localhost dbname=medexdb5 user=medex ");
if (!$con) {
	echo("<p><span style=\"color:red\">medexdb5 cannot be connected</span></p>\n");
	echo("</body></html>\n");
	exit;
}
$query='delete from rx_temp';

$rs = pg_query($con, $query);
if (!$rs) {
	
	pg_close($con);
	echo("<p><span style=\"color:red\">delete is failed </span></p>\n");
	echo("</body></html>\n");
	exit;
}

  
pg_set_client_encoding('EUC_JP');

$uketuke = $_POST[uketuke];
$byoto = $_POST[byoto];



$cond01 = " and  date(����������) >=  date'today' - ";	

//$cond01 = " where  ���������� >=  date'today' - ";

$cond02 = $_POST[plusdate];

$cond03 = " and  date(����������) <= date'today' + ";
$cond04 = $_POST[plusdate2];

$cond011 = " and  date( ���ջ��� ) >=  date'today' - ";	

//$cond01 = " where  ���������� >=  date'today' - ";

$cond021 = $_POST[plusdate];

$cond031 = " and   date( ���ջ��� ) <= date'today' + ";
$cond041 = $_POST[plusdate2];

//date("���ջ���") >= date'today' -0 and date("���ջ���" )<= date'today' + 0
	


$cond05="  ";
if ($byoto==''){$byoto='����';}

$cond06=' and p. "��˾����"  = ';
$cond07="'".$byoto."' ";
//$condtest=' and o."����" not in (32834,32683) ';
$condtest=" ";


$querylast ="  order by  ���������� desc  limit 1000";

//***********************
$query1='INSERT INTO rx_temp(
            rx_id, pt_num, pt_last, pt_first, pt_kana, nyugai, indate, outdate, 
            room, orderdate, startdate)
 SELECT  distinct(o."ObjectID"),p."����ID", p."��", p."̾", p."�եꥬ��", p."������ʬ", 
p."������", p."�ౡͽ����", p."�¼�", o."����ǯ����", o."����������" FROM "���޽����" as  o 
LEFT JOIN "������Ģ" p ON o."����" = p."ObjectID"  WHERE o."Superseded" IS NULL';
 
$query1 = $query1.$cond01.$cond02.$cond03.$cond04.$cond05.$cond06.$cond07.$condtest;
print $query1;

$rs1 = pg_query($con, $query1);
if (!$rs1) {
	
	pg_close($con);
	echo("<p><span style=\"color:red\">rx_temp table failed </span></p>\n");
	echo("</body></html>\n");
	exit;
}
//***************************


$query='select 
            rx_id, pt_num, pt_last, pt_first, pt_kana, nyugai, indate, outdate, 
            room, orderdate, startdate  from rx_temp ';






$rs = pg_query($con, $query);
if (!$rs) {
	
	pg_close($con);
	echo("<p><span style=\"color:red\">rx-order table failed </span></p>\n");
	echo("</body></html>\n");
	exit;
}


print "DONE";



$maxrows = pg_num_rows($rs);

$maxcols = pg_num_fields($rs);

echo("<h1>�������������� </h1>");
?>

<table summary="<?= $_POST['table'] ?> display" border="1">
	<caption><?= $_POST['table'] ?> Status </caption>
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
				
				
			//	$url="/s/medex/farm/php/html/u/doctor/xctorder2-app.php?";
			//	echo("<a href=".$url."></a>");

				for ($col = 0; $col < $maxcols; $col++) { /* ����б� */
				
				

				

		
				 echo("<td>".htmlspecialchars($rowdata[$col])."<br></td>\n");
                           

				$rowscont = $rowscont.$rowdata[$col].";";




                         	 }
					
				echo("</tr>\n");
			

 			$rowscont = $rowscont."\n";
 //			 fwrite($fh, $rowscont); 

			}
			/* �ǡ����١����Ȥ���³���ڤ�Υ�� */
			pg_close($con);
 //               fclose($fh); 
 

?>

	</tbody>
     </table>

	</body>






</html>