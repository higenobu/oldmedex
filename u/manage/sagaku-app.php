
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>karte-print</title>
	</head>
	<body>
<a href="index.php?tab=1">メインに戻る</a>
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


    <h1>sagaku Table</h1>
    <form action="sagaku.php" method="POST">
      <table border="0"
             summary="xct-list">
        
       
	<tr>
          <td>年月</td>
          <td><input type="text" name="ym" value="201109" onKeyPress="return submitStop(event);"></td>
        </tr>
	
	
       

	<tr><th>期間</th><td class="plain" colspan="7"><select name="kikan"  onKeyPress="return disableEnterKey(this,event)">

<option value="1">1-10</option>
<option value="2">11-20</option>
<option value="3">21-end</option>


</select>

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



