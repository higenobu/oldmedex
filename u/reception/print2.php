<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
$top=$_GET['top'];
$bottom=$_GET['bottom'];

if ($top) {
  print '<script language="javascript" type="text/javascript">
         <!--
         function printPopup() {
         parent.frames[1].focus();
         parent.frames[1].print();
         }
         -->
         </script>';
  mx_html_head("",false);
  print '<body><center>
         <form><input type="button" value="°õºþ" onClick="printPopup()">
         <input type="button" value="²èÌÌ¤òÊÄ¤¸¤ë" onClick="window.parent.close()">
         </form>
         </center>';
}
elseif ($bottom) {
  mx_html_head('Í½Ìó¼Ô°ìÍ÷',false);
  if (array_key_exists('blobmedia', $_REQUEST)) {
         $db = mx_db_connect();
         $media = NULL;
         $type = mx_db_fetch_blobmedia(&$db, &$media, $_REQUEST['blobmedia']);
         if ($media != '')
                 print $media;
  }
}
else {
  print '<frameset rows="60, *" noresize border="0">
         <frame src="print2.php?top=1" name="top_frame" scrolling="no">
         <frame src="print2.php?bottom=1&blobmedia=';
  print $_REQUEST['blobmedia'];
  print '" name="bottom_frame" ></frameset>';
}

if ($top || $bottom) print '</body></html>';
?>
