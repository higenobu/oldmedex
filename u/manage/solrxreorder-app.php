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


		<title>RX Re-order temp table</title>
	</head>
	<body>
<a href="index.php?tab=1">�ᥤ������</a>
<br>




<body>
<script type="text/javascript">
<!--


function submitStop(e){
    if (!e) var e = window.event;
 
    if(e.keyCode == 13)
        return false;
}


// -->
</script>




    <h1>RX Table</h1>
    <form action="solrxreorder.php" method="POST">
      <table border="0"
             summary="xct-list">
        
       
	<tr>
          <td>���������������</td>
          <td><input type="text" name="plusdate" value="0" onKeyPress="return submitStop(event);"></td>
        </tr>
	<tr>
          <td>�������������</td>
          <td><input type="text" name="plusdate2" value="0" onKeyPress="return submitStop(event);"></td>
        </tr>
	<tr>
          <td>��������Ͽ�ơ��֥����</td>
          <td><input type="text" name="newtable" value="1" onKeyPress="return submitStop(event);"></td>
        </tr>
<tr><th>������׻���ξ��</th><td class="plain" colspan="7"><select name="teiki"  onKeyPress="return disableEnterKey(this,event)">

<option value="1">���</option>
<option value="0">�׻�</option>
<option value="2">ξ��</option>
��

</select>
<tr><th>������ʬ</th><td class="plain" colspan="7"><select name="byoto"  onKeyPress="return disableEnterKey(this,event)">

<option value="O">����</option>
<option value="I">����</option>



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



