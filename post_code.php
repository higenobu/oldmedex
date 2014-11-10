<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
mx_html_head('¢©ÈÖ¹æ¸¡º÷');
?>
<body>
<form name="zipform" onsubmit"return false">
¢©<input type="text"
         name="zip"
         size="7"
         maxlength="8"
         autocomplete="off"
         onkeyup="lookup_post_code(this.value)"
         value="">
<input type="button" value="·èÄê" onClick="window.opener.setPrefCityBlock(document);window.close()"><br>
<br>
<input type="text"
       name="pref"
       size="4"
       value="">
<input type="text"
       name="city"
       size="10"
       value="">
<input type="text"
       name="block"
       size="37"
       value="">
<div id="selzip"></div>
</form>
<div id="progressBarId"></div>
</body>
</html>
