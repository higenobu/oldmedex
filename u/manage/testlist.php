<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>Table-to-csv converter</title>
	</head>
	<body>
<a href="index.php?tab=1">メインに戻る</a>

<a href="testlist-app.php?tab=1">再実行</a>


<br>

<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';
 

$con = mx_db_connect();
 
  
pg_set_client_encoding('EUC_JP');

$uketuke = $_POST[uketuke];
$byoto = $_POST[byoto];

$table="template";

$query='SELECT temp1  from wktemp';







 


 print $query;






$rs = pg_query($con, $query);
if (!$rs) {
	
	pg_close($con);
	echo("<p><span style=\"color:red\">rx-order table failed </span></p>\n");
	echo("</body></html>\n");
	exit;
}


$maxrows = pg_num_rows($rs);
 
$maxcols = pg_num_fields($rs);
 

 
?>

  <h1>report</h1>
    <form action="wktemp.php" method="POST">
 
    <table class="wktemp">
        
     
 
<thead>
  <colgroup>
   <col width="40px">
   <col width="40px">
   <col width="140px">
   <col width="100px">

   <col width="60px">
   <col width="180px">
   <col width="70px">
   <col width="100px">
   <col width="100px">
  </colgroup>  
</thead>
<tbody>
<tr><th>exam name</th><td class="plain" colspan="8">
<select name="shiji" id="shiji" onKeyPress="return submitStop(event);"> 

      <?

  








 

        $rowscont = "";
 

			 

				$f_name = null;
				for ($col = 0; $col < $maxcols; $col++) {
					 
					$f_name = $f_name.pg_field_name($rs, $col).";";
					
				}
 

			for ($row = 0; $row < $maxrows; $row++) { 
				 
				 
				$rowdata = pg_fetch_row($rs, $row);
 

                                 
				
			 

				for ($col = 0; $col < $maxcols; $col++) {  
				
				

				
print $rowdata[$col]."\n";
		
				 echo("<option value=".'"'.$col.'"'.">".htmlspecialchars($rowdata[$col])." </option>\n");
                           


				 




                          }



					
				 
			

 			 
  

			}
			 echo "</select>\n";
			pg_close($con);
 

?>

<tr align="center">
          <td colspan="2">
            <input type="submit" value="Submit">
            <input type="reset" value="Clear">
          </td>
        </tr>
</tbody>
</table>

    </form>




</html>
