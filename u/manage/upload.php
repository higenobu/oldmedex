<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>upload</title>
	</head>
	<body>
<a href="index.php?tab=1">Go back to main</a>


<br>






<?php



 


 


if (is_uploaded_file($_FILES['upfile']['tmp_name'])) {
   echo " uploaded successfully.\n";
  




//   readfile($_FILES['upfile']['tmp_name']);




} else {
   echo "Possible file upload attack: ";
   echo "filename '". $_FILES['upfile']['tmp_name'] . "'.";
}



$target_path =$_POST['name'];

$target_path = $target_path.$_FILES['upfile']['name']; 



if(move_uploaded_file($_FILES['upfile']['tmp_name'], $target_path)) {
 
    echo " has been uploaded\n"; 
} else{
    echo "There was an error uploading the file, please try again!";
}


?>
	</tbody>
     </table>

	</body>






</html>
