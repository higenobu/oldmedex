<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ext-service.php';

$u = mx_authenticate_user(1);

if (!is_null($u)) {
	$db = mx_db_connect();
	$it = ext_service_get($db, $u, 'M3');
	$xu = $it['account'];
	$xp = $it['password'];
	if ($xu) $xu = htmlspecialchars($xu);
	if ($xp) $xp = htmlspecialchars($xp);
	$m3ok = ($xu != '' && $xp != '');
}

$path = dirname(__FILE__) . '/m3ok';
if (!$m3ok) {
    print <<<HTML
<html><head></head><body style="background: white"><pre>
メニューから、「共通」
「外部サービス変更」
を選択し、M3 の
アカウントと
パスワードを
設定すると、
ここで M3 の
コンテンツが
御覧になれます。
</pre></body></html>
HTML;
    exit;
} else if (file_exists($path)) {
  $on_load=" onLoad=\"return submitForm('m3login')\"";
} else {
  $msg = 'Cannot connect to M3 server <br>
<input type=submit value="Retry">
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
<form id="m3login" method="POST" action="https://www.m3.com/login/login">
<input type="hidden" name="loginId" value="${xu}">
<input type="hidden" name="password" value="${xp}">
<input type="hidden" name="origURL" value="https://www.m3.com/parts/medex/m3panel.jsp">
<input type="hidden" name="portalId" value="medex">
${msg}
</form>
</body>
HTML;
?>
