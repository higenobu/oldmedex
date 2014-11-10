


<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/rp_common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
global $dp;
//mx_dbname_cfg();
//mx_db_connect();



$Link = pg_connect("host=localhost dbname=medexdb5 user=medex ");
pg_set_client_encoding($Link,'EUC_JP');

if (!$Link) {
    die('接続失敗です。'.pg_last_error());
}

print $Link;

echo("******<br>\n");

$Query = "select * from tbl_bui_01";


$rs = pg_query($Link, $Query);

echo("******<br>\n");
$maxrows = pg_num_rows($rs);

echo("$maxrows: ******<br>\n");

for ($i = 0; $i < $maxrows ; $i++) {

$Row = pg_fetch_row($rs,$i);
 echo("<td> $Row[0] | $Row[1] | $Row[2]  <br></td>\n");



}



pg_close($Link);

?>
