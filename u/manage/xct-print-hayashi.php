<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>xct print</title>
	</head>
	<body>
<a href="index.php?tab=1">�ᥤ������</a>

<a href="xct-status.php?tab=1">�Ƽ¹�</a>

<A HREF="javascript:window.print()">����</A>


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
    select "��" || "̾" as empname , userid
    from "������Ģ"
SQL;
	$rs = pg_query($con, $stmt);
 	$rows = pg_fetch_all($rs);
  
  foreach($rows as $row)
    $kiroku[$row['userid']] = $row['empname'];
  

/************************update 0616-2011
CREATE OR REPLACE VIEW tbl_rxv AS 
 SELECT p."����ID", p."��"::text || p."̾"::text AS "��̾", p."�եꥬ��", p."������ʬ", p."������", p."�ౡͽ����", o."����ǯ����", o."����������", m."����̾", m."�쥻�ץ��Ż����������ƥॳ���ɡʣ���" AS code, o."����", d."����", n."��ˡ", u."����ñ��"
   FROM "���޽����" o
   JOIN "���޽��������" d ON o."ObjectID" = d."���޽����"
   LEFT JOIN "Medis�����ʥޥ�����" m ON m."ObjectID" = d."����" AND m."Superseded" IS NULL
   LEFT JOIN "���������ñ��" u ON d."����ñ��" = u."ID" AND u."Superseded" IS NULL
   LEFT JOIN "�������ˡ" n ON d."��ˡ" = n."ID" AND n."Superseded" IS NULL
   LEFT JOIN "������Ģ" p ON o."����" = p."ObjectID"
  WHERE o."Superseded" IS NULL;

AL

*/


//****************************************************************************updated 0615-2011
  
 pg_set_client_encoding('EUC_JP');



$query = 'select orderdate as �������� , plandate as "ͽ����", procdate as "�»���", p."����ID",  p."�եꥬ��",  p."������ʬ", p."�¼�", p."�ౡͽ����",shiji as �ؼ���,xct."CreatedBy"as ��Ͽ��,
xctkubun as ��ʬ, substring(memo1 for 10 from 1)  as �ؼ�, substring(memo3 for 10 from 1) as �Ű�,bui1 as ����1,syoken1 as ����1,
 bui2 as ����2,syoken2 as ����2,bui3 as ����3,syoken3 as ����3,bui4 as ����4,syoken4 as ����4, bui5 as ����5, syoken5 as ����5, proof,stop  from xctorder as xct
LEFT JOIN "������Ģ" p ON xct."����" = p."ObjectID"';

$cond11='where xct."����" !=32834    and xct."����" !=32683 and stop is null and ';	

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

echo("<h1>�����»�ͽ����� </h1>");
?>

<table summary="<?= $_POST['table'] ?> display" border="1">
	<caption><?= $_POST['table'] ?>  </caption>
	<thead>
		<tr>
			<?
				/* �ơ��֥�Υإå�������� */
				for ($col = 0; $col < $maxcols-1; $col++) {
					/* pg_field_name() �ϥե������̾���֤� */
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
					/* pg_field_name() �ϥե������̾���֤� do not show stop */
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
				

				for ($col = 0; $col < $maxcols-1; $col++) { /* ����б� delete stop  */
				
				

				

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
			/* �ǡ����١����Ȥ���³���ڤ�Υ�� */
			pg_close($con);
                fclose($fh); 
 

?>

	</tbody>
     </table>

	</body>






</html>
