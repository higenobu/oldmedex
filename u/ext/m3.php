<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ext-service.php';

$path = dirname(__FILE__) . '/../../m3ok';

$u = mx_authenticate_user(1);

if (!is_null($u)) {
	$db = mx_db_connect();
	$it = ext_service_get($db, $u, 'So-Net');
	$xu = $it['account'];
	$xp = $it['password'];
	if ($xu) $xu = htmlspecialchars($xu);
	if ($xp) $xp = htmlspecialchars($xp);
	$m3ok = ($xu != '' && $xp != '');
}

if (0) {
    print <<<HTML
<html><head></head><body></body></html>
HTML;
    return;
} else if (file_exists($path)) {
  $on_load=" onLoad=\"return submitForm('m3login')\"";
} else {
  $msg = 'Cannot connect to So-net server<br>
            <INPUT type=submit value=入場 name=SUBMIT>
';
  $on_load='';
}
print <<<HTML
<html>
<head>
<script>
function submitForm(id)
{
   var form = document.getElementById(id);
   form.submit();
   return false;
}
</script>
</head>
<body ${on_load}>
<form id="m3login" method="POST" action="https://www.so-net.ne.jp/m3/k-shinryo/cgi-bin/bitway2.cgi">
<input type="hidden" name="USR_ID" value="${xu}">
<input type="hidden" name="PASSWD" value="${xp}">
<INPUT TYPE="HIDDEN" NAME="CID" VALUE="3H0070003">
<INPUT TYPE="HIDDEN" NAME="KEY" VALUE="enter">
<INPUT TYPE="HIDDEN" NAME="AID" VALUE="1013">
<INPUT TYPE="HIDDEN" NAME="RURL" VALUE="http://www.so-net.ne.jp/m3/k-shinryo/">
<INPUT TYPE="HIDDEN" NAME="TURL" VALUE="/k_shinryo/">
<INPUT TYPE="HIDDEN" NAME="HURL" VALUE="http://www.so-net.ne.jp/">
<INPUT TYPE="HIDDEN" NAME="PRICE" VALUE="0">
<INPUT TYPE="HIDDEN" NAME="ADULT" VALUE="0">
<INPUT TYPE="HIDDEN" NAME="CTYPE" VALUE="3">
<INPUT TYPE="HIDDEN" NAME="GENRE" VALUE="健康・医学情報">
<INPUT TYPE="HIDDEN" NAME="CNAME" VALUE="今日の診療（So-net版）">

${msg}
</form>
</body>
HTML;
?>
