<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja"><head><meta http-equiv="content-type" content="text/html; charset=euc-jp"><link rel="shortcut icon" href="/resource/8a783773/favicon.ico">
<script language="JavaScript" src="/resource/8a783773/AC_OETags.js"></script>
<script language="JavaScript" src="/resource/8a783773/mx.js"></script>
<script language="JavaScript" src="/resource/8a783773/PopupWindow.js"></script>
<script language="JavaScript" src="/resource/8a783773/date.js"></script>
<script language="JavaScript" src="/resource/8a783773/CalendarPopup.js"></script>
<script language="JavaScript" src="/resource/8a783773/AnchorPosition.js"></script>
<script language="JavaScript" src="/resource/8a783773/MochiKit.js"></script>
<script language="JavaScript" src="/resource/8a783773/post_code.js"></script>

<script language="JavaScript" src="/resource/8a783773/inc_search_sjis.js"></script>
<script language="JavaScript" src="/resource/8a783773/vocabulary.js"></script>
<script language="JavaScript" src="/resource/8a783773/apptcal.js"></script>
<script language="JavaScript" src="/resource/8a783773/drawapp-js.php"></script>
<link rel="stylesheet" href="/resource/8a783773/mxstyle.css" />
<link rel="stylesheet" href="/resource/8a783773/calend.css" />
<link rel="stylesheet" href="/resource/8a783773/qxr.css" />


		<title>rx-list</title>
	</head>
	<body>
	<a href="index.php?tab=1">�ᥤ������</a>
	
	<br>


<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/rp_common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/print.php';
 $con = mx_db_connect();


  
pg_set_client_encoding('EUC_JP');

$uketuke = 1;
$byoto = $_POST[byoto];
$teiki=$_POSTT[teiki];

//0328-2012
if ($uketuke ==2){
$query='SELECT p."����ID", p."��", p."̾", p."�եꥬ��", p."������ʬ", p."������", p."�ౡͽ����", p."�¼�", o."ObjectID", o."����ǯ����", o."����������",u."���ջ���",u."���ơ�����" o."��Ͽ��" as "�ؼ���"  FROM "���޽����" as  o LEFT JOIN "������Ģ" p ON o."����" = p."ObjectID" JOIN "���մ���ɽ"  u ON o."����"=u."����" WHERE o."Superseded" IS NULL ';
}
else {
$query='SELECT  distinct(o."ObjectID"),p."����ID", p."��", p."̾", p."�եꥬ��", p."������ʬ", p."������", p."�ౡͽ����", p."�¼�", o."����ǯ����", o."����������",s."��"||s."̾" as "�ؼ���" FROM "���޽����" as  o LEFT JOIN "������Ģ" p ON o."����" = p."ObjectID" LEFT JOIN "������Ģ" s ON s."ID"=o."��Ͽ��" WHERE o."Superseded" IS NULL ';
}






$teikicond=' and "����׻�"=$teiki ';
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

$cond06=' and p. "��˾����"  = ';
$cond07="'".$byoto."' ";
//$condtest=' and o."����" not in (32834,32683) ';
$condtest=" ";

if ($uketuke ==2){
$query = $query.$cond01.$cond02.$cond03.$cond04.$cond05.$cond011.$cond021.$cond031.$cond041.$cond05.$condtest;
}
else {
$query = $query.$cond01.$cond02.$cond03.$cond04.$cond05.$cond06.$cond07.$condtest;
}

//0328-2012 	
$query = $query."  order by  ���������� desc  limit 1000";

//***********************
$query1='INSERT INTO rx_temp(
            rx_id, pt_num, pt_last, pt_first, pt_kana, nyugai, indate, outdate, 
            room, orderdate, startdate,kiroku)
 SELECT  distinct(o."ObjectID"),p."����ID", p."��", p."̾", p."�եꥬ��", p."������ʬ", 
p."������", p."�ౡͽ����", p."�¼�", o."����ǯ����", o."����������",o."��Ͽ��" FROM "���޽����" as  o 
LEFT JOIN "������Ģ" p ON o."����" = p."ObjectID"  WHERE o."Superseded" IS NULL';
 
$query1 = $query1.$cond01.$cond02.$cond03.$cond04.$cond05.$cond06.$cond07.$condtest;


//********************************








$rs = pg_query($con, $query);
if (!$rs) {
	
	pg_close($con);
	echo("<p><span style=\"color:red\">rx-order table failed </span></p>\n");
	echo("</body></html>\n");
	exit;
}






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
