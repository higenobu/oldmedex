<?php
echo "<pre>hajimari</pre>";
$output = shell_exec('bash /home/medex/sftp2.sh');
echo "<pre>$output</pre>";
$output1 = shell_exec('ls -l  /home/medex/cmbtest2');
echo "<pre>$output1</pre>";

echo "<pre>owari</pre>";
?>
