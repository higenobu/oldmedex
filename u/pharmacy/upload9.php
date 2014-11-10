<?php

echo "**************\n";

print $_FILES['upfile']['name'];





if (is_uploaded_file($_FILES['upfile']['tmp_name'])) {
   echo "File ". $_FILES['upfile']['name'] ." uploaded successfully.\n";
   echo "Displaying contents\n";

  $fileData = file_get_contents($_FILES['upfile']['tmp_name']);
print $fileData;

   readfile($_FILES['userfile']['tmp_name']);
} else {
   echo "Possible file upload attack: ";
   echo "filename '". $_FILES['userfile']['tmp_name'] . "'.";
}

?>


