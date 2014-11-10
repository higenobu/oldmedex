<?php // -*- mode: php; coding: euc-japan -*-

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';












 
$top=$_GET['top'];
$bottom=$_GET['bottom'];





if ($top) {
print <<<HTML
<html>
<body>
<a href="index.php?tab=1">メインに戻る</a>
<br>

<script type="text/javascript">
// Popup window code
function newPopup(url) {
	popupWindow = window.open(
		url,'popUpWindow','height=700,width=800,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes')
}
</script>
<a href="JavaScript:newPopup('https:localhost:8181/BlogWebApp/');">お知らせ画面POPUP</a>
HTML;
}
elseif ($bottom) {
  






 

}


else {
  print ' 
         <frame src="print-news.php?top=1"  >
         <frame src="print-news.php?bottom=1&';
  
}

if ($top || $bottom) print '</body></html>';
?>
