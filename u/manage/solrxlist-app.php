
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>Table-to-csv converter</title>
	</head>
	<body>
<a href="index.php?tab=1">�ᥤ������</a>
<br>




<body>




    <h1>RX Table</h1>
    <form action="solrxlist.php" method="POST">
      <table border="0"
             summary="xct-list">
        
       
	<tr>
          <td>���������������</td>
          <td><input type="text" name="plusdate" value="0"></td>
        </tr>
	<tr>
          <td>�������������</td>
          <td><input type="text" name="plusdate2" value="0"></td>
        </tr>
	
	
<tr><th>������׻���ξ��</th><td class="plain" colspan="7"><select name="teiki"  onKeyPress="return disableEnterKey(this,event)">

<option value="1">���</option>
<option value="0">�׻�</option>
<option value="2">ξ��</option>
��

</select>

<tr><th>����</th><td class="plain" colspan="7"><select name="byoto"  onKeyPress="return disableEnterKey(this,event)">

<option value="����">����</option>
<option value="3������">3������</option>
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



