
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




    <h1>XCT Table</h1>
    <form action="xct-print.php" method="POST">
      <table border="0"
             summary="xct-list">
        
       
	<tr>
          <td>今日より前の日数</td>
          <td><input type="text" name="plusdate" value="0" onKeyPress="return submitStop(event);"></td>
        </tr>
	<tr>
          <td>今日より後の日数</td>
          <td><input type="text" name="plusdate2" value="0" onKeyPress="return submitStop(event);"></td>
        </tr>
	<tr>
<td>入院/外来</td>

<td class="plain" colspan="7">
<select name="inout" id="inout" onKeyPress="return disableEnterKey(this,event)">
<option value="B">両方</option>
<option value="O">外来</option>
<option value="I">入院</option>



</select>
<tr>
<td>X/CT</td>

<td class="plain" colspan="7">
<select name="kubun" id="kubun" onKeyPress="return disableEnterKey(this,event)">

<option value="B">両方</option>
<option value="170027910">XP</option>
<option value="170011810">CT</option>


</select>
<tr>
<td>実施</td>

<td class="plain" colspan="7">
<select name="proof" id="proof" onKeyPress="return disableEnterKey(this,event)">

<option value=""></option>
<option value="N">未実施</option>
<option value="技師実施">技師実施</option>
<option value="医師実施">医師実施</option>
<option value="未実施">画像オーダ入力による未実施</option>
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



