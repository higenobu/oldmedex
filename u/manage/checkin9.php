<!DOCTYPE HTML>
<html>
<link rel="stylesheet" href="mxstyle.css" />
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>checkin all</title>
</head>

<body>

    <header class="body">
    </header>

    <section class="body">
    </section>
 

 
    <h1>������</h1>
    <form action="checkinall.php" method="POST">
      <table border="0"
             summary="checkinall">
         
        <tr>
          <td>����ID1</td>
          <td><input type="text" name="id1" value=""></td>
        </tr>
	<tr>
          <td>����ID2</td>
          <td><input type="text" name="id2" value=""></td>
        </tr>
	<tr>
          <td>����ID3</td>
          <td><input type="text" name="id3" value=""></td>
        </tr>
	<tr>
          <td>����ID4</td>
          <td><input type="text" name="id4" value=""></td>
        </tr>
	<tr>
          <td>����ID5</td>
          <td><input type="text" name="id5" value=""></td>
        </tr>
	 

<tr><th>ͽ����</th><td class="plain" colspan="8"><select name="yoyaku" id="yoyaku" onKeyPress="return disableEnterKey(this,event)">
<option value=""></option>
 
 <option value="60">���ʿ�</option>
<option value="61">㦾� ��</option>
<option value="62">��������</option>
<option value="63">��������</option>
<option value="64">��ë��ë</option>
<option value="65">���ڷ���</option>
<option value="95">���Ӵ�</option>
<option value="67">�غ���ɧ</option>
<option value="68">����Ԣ��</option>
<option value="69">������</option>
<option value="70">�������</option>
<option value="71">�ֿ彨��</option>

<option value="73">�İ�ӹ�</option>
<option value="96">������ɧ</option>
<option value="97">����ȸ�</option>
<option value="98">ƣ�ܿ�</option>
<option value="81">�����ٻ�</option>
<option value="82">����͵ͺ</option>
<option value="84">�����ʤ�</option>
<option value="90">      </option>
<option value="92">���չ���</option>
<option value="85">�ܺ��һ�</option>
<option value="93">��߷����</option>
<option value="91">���ڷ���</option>
<option value="76">��ȫͦ��</option>
<option value="77">����</option>
<option value="78">��������</option>
<option value="79">�ڸ���</option>
 
</select>
</table>

<input id="submit" name="submit" type="submit" value="Submit">
       
</form>

    <footer class="body">
    </footer>

 
<?php
$default_tab = $_GET['tab']; // This gets the tab number from the url
$selected = 'class="selected"'; // This is the text to add to the tab html


 
?> 


  </body>
</html>



