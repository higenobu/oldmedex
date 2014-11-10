<?php

echo "***************************";

$myFile = "test.txt";
$fh = fopen($myFile, 'w') or die("can't open file");
$stringData = "Bobby Bopper\n";
fwrite($fh, $stringData);
$stringData = "Tracy Tanner\n";
fwrite($fh, $stringData);
fclose($fh);

?>

$filename = "test.txt";

if (is_writable($filename)) {

    // この例では$filenameを追加モードでオープンします。
    // ファイルポインタはファイルの終端になりますので
    // そこがfwrite()で$somecontentが追加される位置になります。
    if (!$handle = fopen($filename, 'a')) {
         echo "Cannot open file ($filename)";
         exit;
    }
if (fwrite($handle, $somecontent) === FALSE) {
        echo "Cannot write to file ($filename)";
        exit;
    }

    echo "Success, wrote ($somecontent) to file ($filename)";

    fclose($handle);

} else {
    echo "The file $filename is not writable";
}

?php>

