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

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';


$con = mx_db_connect();

$tablename=$_POST[table];


pg_set_client_encoding('EUC_JP');

/* 検索を実行 */
 $query = "select * from $_POST[table] "; 


if ( $_POST['table'] == "tbl_xctv"  ) {
	

$cond01 = " where plandate >=  date'today' - ";

$cond02 = $_POST[plusdate];

$cond03 = " and plandate <= date'today' + ";
$cond04 = $_POST[plusdate2];

$query = $query.$cond01.$cond02.$cond03.$cond04;


}


if (  $_POST['table'] == "tbl_yakuzaiv") {
	

$cond01 = " where  処方開始日 >=  date'today' - ";

$cond02 = $_POST[plusdate];

$cond03 = " and  処方開始日 <= date'today' + ";
$cond04 = $_POST[plusdate2];


if ($_POST[inout] == 'I' || $_POST[inout] == 'O')
{$cond06 = $_POST[inout];

$cond05= " and 入外区分 = "."'".$cond06."'";
$query = $query.$cond01.$cond02.$cond03.$cond04.$cond05." ";
} 
else {
$query = $query.$cond01.$cond02.$cond03.$cond04." ";
}

}


if (  $_POST['table'] == "tbl_plist") {
	



if ($_POST[inout] == 'I' || $_POST[inout] == 'O' )
	{$cond06 = $_POST[inout];

$cond05= " where  入外区分 = "."'".$cond06."'";
$query = $query.$cond05;

	
	}

 if ($_POST[room] == '0')
{
$query = $query. " ";
}
else
{
$cond07 = " and 病室 = "."'".$_POST[room]."'";
$query = $query.$cond07." ";


}



}

if (  $_POST['table'] == "empschedv") {
	
$cond01 = " where  target_month >=  date'today' - ";

$cond02 = $_POST[plusdate];

$cond03 = " and  target_month <= date'today' + ";
$cond04 = $_POST[plusdate2];

$query = $query.$cond01.$cond02.$cond03.$cond04;

 if ($_POST[room] == '0')
{
$query = $query." order by employee_id ";
}
else
{
$cond07 = " and emp_ka = "."'".$_POST[room]."'";
$query = $query.$cond07."  order by employee_id ";


}



}

	

if (  $_POST['table'] == "tbl_testv") {
$query = "SELECT test_order_id, content_id,  testid, receptcode, orderdate, sampledate, 
        patient_id,patientgroup, urgent1, doctor,patient_lastname, 
       patient_firstname, test_name
  FROM tbl_testv "; 
	
$cond01 = " where  orderdate >=  date'today' - ";

$cond02 = $_POST[plusdate];

$cond03 = " and  orderdate <= date'today' + ";
$cond04 = $_POST[plusdate2];

$query = $query.$cond01.$cond02.$cond03.$cond04;

 if ($_POST[room] == '0')
{
$query = $query." order by test_order_id ";
}
else
{
$cond07 = " and patientgroup = "."'".$_POST[room]."'";
$query = $query.$cond07."  group by test_order_id ";


}



}

if (  $_POST['table'] == "drjms") {
$query = "SELECT s0,s1,s2,s3,s4,p1,p2,p3,p4
  FROM drjms "; 
	
$cond01 = " where  s0 like  ";

$cond02 = "%".$_POST[bunrui]."%";
$mark ="'";
 $and=" and ";
$con1="s1 like ";
$con3 = "%".$_POST[big]."%";
$query = $query.$cond01.$mark.$cond02.$mark.$and.$con1.$mark.$con3.$mark;
}






/* $query = "SELECT t.ObjectID AS test_order_id, a.ObjectID  AS content_id, t.Patient  AS patient_id, a.TestID  as TestID".
"FROM    test_order t LEFT JOIN  test_order_content  a ON a.TestOrder  = t.ObjectID "."LEFT JOIN  test_master  d ON".
" a.TestID  = d.ObjectID LEFT JOIN  患者台帳  p ON t.Patient = p.ObjectID where t.Cancelled  is null;";　*/






print $query;

$rs = pg_query($con, $query);
if (!$rs) {
	
	pg_close($con);
	echo("<p><span style=\"color:red\">$_POST[table] failed </span></p>\n");
	echo("</body> </html>\n");
	exit;
}


$maxrows = pg_num_rows($rs);

$maxcols = pg_num_fields($rs);

echo("<h1>TESTオーダ一覧 </h1>");
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

  


 $mypath="/home/medex/drj/";
 

$filename = $mypath."drjtest.csv";
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
 
//download to client
$download_path = "/home/medex/drj/";
  
       
 
      // Make sure we can't download files above the current directory location.
 
     // if(eregi("\.\.", $filename)) die("I'm sorry, you may not download that file.");
  
      $file = str_replace("..", "", $filename);
 
      

 
      // Make sure we can't download .ht control files.
 
      if(eregi("\.ht.+", $filename)) die("I'm sorry, you may not download that file.");
  
       
  
      // Combine the download path and the filename to create the full path to the file.
 
      $file = "$download_path$file";


     

 
      // Test to ensure that the file exists.
  
      if(!file_exists($file)) die("I'm sorry, the file doesn't seem to exist.");
   
       
 
      // Extract the type of file which will be sent to the browser as a header
  
     $type = filetype($file);


    //$type=trim(`stat -c%F $file`);
 
      // Get a date and timestamp
  
      $today = date("F j, Y, g:i a");
  
      $time = time();
  
        
      // Send file headers
   
      header("Content-type: $type");
  
      header("Content-Disposition: attachment;filename=$filename");
 
      header("Content-Transfer-Encoding: binary");
  
      header('Pragma: no-cache');
  
      header('Expires: 0');
 
      // Send the file contents.
 
      set_time_limit(0);
  
      readfile($file);



?>

	</tbody>
     </table>

	</body>






</html>
