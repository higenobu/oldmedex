<?php

print "aaaaa";

   $file = "/home/medex/sample.csv";
print $file;

   $file_length = filesize($file);
print $file_length;

   header("Content-Disposition: attachment; filename=$file");
   header("Content-Length:$file_length");
   header("Content-Type: application/octet-stream");

   readfile ($file);

?>
