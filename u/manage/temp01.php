<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>TEST RESULT INSERT from CSV to DB</title>
	</head>
	<body>
<a href="index.php?tab=1">メインに戻る</a>


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
 
function hexToStr($hex){
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
}
$db = mx_db_connect();

 


// read otestr table 


 $stmt5 = <<<SQL
SELECT application, category, "name", template_src, template_obj, disabled, 
       "ID", "ObjectID", "Superseded", "CreatedBy"
  FROM mx_template where "Superseded" is null;
SQL;

$rows5 =  mx_db_fetch_all($db, $stmt5);
  $ptids = array();
$ordates = array();
 
  foreach($rows5 as $row5)
 {

	echo $row5['template_src']."<br>"; 
$field=$row5['template_src'];
$field = mb_convert_encoding($field, "UTF8");
//echo substr($field,2)."<br>";
//echo mb_detect_encoding($field, "auto");

$temp1=hexToStr(substr($field,2));
// $temp1=pack("H*", $field );
//echo mb_detect_encoding($temp1, "auto");
//$temp1 = mb_convert_encoding($temp1, "UTF8");
echo $temp1."<br>"; 
//echo "temp:".$temp1."<br>"; 
//$temp2=pack("H*",$temp);
//echo "temp2:".$temp2."<br>"; 
$temp1=unserialize($temp1);
// echo $temp1[0]."<br>"; 
//echo mb_detect_encoding($temp1[0], "auto");
//echo mb_detect_encoding($temp1[0], "auto");

//  echo $row5['template_src']."<br>"; 

	}



?>


<a href="index.php?tab=1">メインに戻る</a>
<br>

	</tbody>
</table>

	</body>
</html>
