
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>XCTLIST</title>
	</head>
	<body>
<a href="http://mmhome.from-mn.com:8080/oviyam/oviyam?patientID=0306">OVIYAM</a>
<br>
<a href="index.php?tab=1">�ᥤ������</a>
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




    <h1>XCT Table</h1>
    <form action="xct-print.php" method="POST">
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
<td>����/����</td>

<td class="plain" colspan="7">
<select name="inout" id="inout" onKeyPress="return disableEnterKey(this,event)">
<option value="B">ξ��</option>
<option value="O">����</option>
<option value="I">����</option>



</select>
<tr>
<td>X/CT</td>

<td class="plain" colspan="7">
<select name="kubun" id="kubun" onKeyPress="return disableEnterKey(this,event)">

<option value="B">ξ��</option>
<option value="170027910">XP</option>
<option value="170011810">CT</option>


</select>
<tr>
<td>�»�</td>

<td class="plain" colspan="7">
<select name="proof" id="proof" onKeyPress="return disableEnterKey(this,event)">

<option value=""></option>
<option value="N">̤�»�</option>
<option value="���ռ»�">���ռ»�</option>
<option value="��ռ»�">��ռ»�</option>
<option value="̤�»�">�������������Ϥˤ��̤�»�</option>
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



