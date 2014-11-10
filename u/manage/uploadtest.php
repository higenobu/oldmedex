<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>upload</title>
	</head>
	<body>
<a href="testrup.php?tab=1">GO TO NEXT STEP </a>


<br>






<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';



if ($_FILES['upfile']['name'] == "" ) {
	echo("<p><span style=\"color:red\">no name</span></p>\n");
	echo("</body></html>\n");
	exit;
}










print $_FILES['upfile']['name'];


print $_FILES['upfile']['tmp_name'];


if (is_uploaded_file($_FILES['upfile']['tmp_name'])) {
   echo "File ". $_FILES['upfile']['name'] ." uploaded successfully.\n";
   echo "Displaying contents\n";

 

   


} else {
   echo "Possible file upload attack: ";
   echo "filename '". $_FILES['upfile']['tmp_name'] . "'.";
}



$target_path = "/home/medex/files/testresult.csv";


print $target_path;


if(move_uploaded_file($_FILES['upfile']['tmp_name'], $target_path)) {
    echo "The file ".  $_FILES['upfile']['name']. 
    " has been uploaded";
} else{
    echo "There was an error uploading the file, please try again!";
}



  $file='/home/medex/files/testresult.csv';
print $file;

    $fileData = file_get_contents($file);




     $fileData1=mb_convert_encoding($fileData, "EUC-JP","auto");
print "**********************************************************************";


print $fileData1;

  file_put_contents($file, $fileData1);

print "COMPLETED*******************************************";

?>


<a href="index.php?tab=1">BACK TO MAIN</a>
<br>

	</tbody>
</table>

	</body>
</html>
