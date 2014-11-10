
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja"><head><meta http-equiv="content-type" content="text/html; charset=euc-jp"><link rel="shortcut icon" href="/resource/8a783773/favicon.ico">
<script language="JavaScript" src="/resource/8a783773/AC_OETags.js"></script>
<script language="JavaScript" src="/resource/8a783773/mx.js"></script>
<script language="JavaScript" src="/resource/8a783773/PopupWindow.js"></script>
<script language="JavaScript" src="/resource/8a783773/date.js"></script>
<script language="JavaScript" src="/resource/8a783773/CalendarPopup.js"></script>
<script language="JavaScript" src="/resource/8a783773/AnchorPosition.js"></script>
<script language="JavaScript" src="/resource/8a783773/MochiKit.js"></script>
<script language="JavaScript" src="/resource/8a783773/post_code.js"></script>

<script language="JavaScript" src="/resource/8a783773/inc_search_sjis.js"></script>
<script language="JavaScript" src="/resource/8a783773/vocabulary.js"></script>
<script language="JavaScript" src="/resource/8a783773/apptcal.js"></script>
<script language="JavaScript" src="/resource/8a783773/drawapp-js.php"></script>
<link rel="stylesheet" href="/resource/8a783773/mxstyle.css" />
<link rel="stylesheet" href="/resource/8a783773/calend.css" />
<link rel="stylesheet" href="/resource/8a783773/qxr.css" />


		<title>rx Re-order</title>
	</head>
	<body>
<a href="index.php?tab=1">メインに戻る</a>
<br>
<script type="text/javascript">
<!--


function submitStop(e){
    if (!e) var e = window.event;
 
    if(e.keyCode == 13)
        return false;
}


// -->
</script>




<body>

<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/so.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pdf4.php';

?>


    <h1>RX Table</h1>
    <form action="solrxreod.php" method="POST">
      <table border="0"
             summary="xct-list">
        
       <tr><th>依頼日</th><td colspan="7"><div style="white-space: nowrap"><script>var calend = new MedexCalendarPopup("div-soe-a5aaa1bca5c0bdaacebbc6fc");</script>
<div id="div-soe-a5aaa1bca5c0bdaacebbc6fc" class="soecal" style="position:absolute; visibility:hidden"></div>
<input type="text" onKeyPress="return disableEnterKey(this,event)" id="soe-a5aaa1bca5c0bdaacebbc6fc" name="orderdate" value= "" >
<a href="#" onclick="calend.select(document.getElementById('soe-a5aaa1bca5c0bdaacebbc6fc'),'anchor-soe-a5aaa1bca5c0bdaacebbc6fc', 'yyyy-MM-dd'); return false;" id="anchor-soe-a5aaa1bca5c0bdaacebbc6fc" name="anchor-soe-a5aaa1bca5c0bdaacebbc6fc" >*</a></div></td></tr>

       <tr><th>開始日</th><td colspan="7"><div style="white-space: nowrap"><script>var calend = new MedexCalendarPopup("div-soe-a5aaa1bca5c0bdaacebbc6fc");</script>
<div id="div-soe-a5aaa1bca5c0bdaacebbc6fcx" class="soecal" style="position:absolute; visibility:hidden"></div>
<input type="text" onKeyPress="return disableEnterKey(this,event)" id="soe-a5aaa1bca5c0bdaacebbc6fcx" name="startdate" value="">
<a href="#" onclick="calend.select(document.getElementById('soe-a5aaa1bca5c0bdaacebbc6fcx'),'anchor-soe-a5aaa1bca5c0bdaacebbc6fcx', 'yyyy-MM-dd'); return false;" id="anchor-soe-a5aaa1bca5c0bdaacebbc6fcx" name="anchor-soe-a5aaa1bca5c0bdaacebbc6fcx" >*</a></div></td></tr>
	
	
	<tr>
          <td>日数</td>
          <td><input type="text" name="nissu" value="7" onKeyPress="return submitStop(event);"></td>
        </tr>

<tr><th>病棟</th><td class="plain" colspan="7"><select name="byoto"  onKeyPress="return disableEnterKey(this,event)">

<option value="外来">外来</option>
<option value="3階病棟">3階病棟</option>
<option value="4">4</option>
<option value=""></option>

</select>


</tr>
        <tr align="center">
          <td colspan="2">
            <input type="submit" value="Submit">
            <input type="reset" value="Clear">
          </td>
        </tr>
      </table>

    </form>

 
<?php
$default_tab = $_GET['tab']; // This gets the tab number from the url
$selected = 'class="selected"'; // This is the text to add to the tab html
?> 


  </body>
</html>



