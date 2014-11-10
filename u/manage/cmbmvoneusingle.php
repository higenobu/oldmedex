
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>Table-to-csv converter</title>
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




    <h1>kenshin update</h1>
    <form action="cmbmvsingle.php" method="POST">
      <table border="0"
             summary="xct-list">
        
       
	 
          <td>Date which need to retrieve from CMB data to Chart database </td>
          <td><input type="text" name="dos" value="" onKeyPress="return submitStop(event);"></td>
<tr>
<td>pt name </td>
          <td><input type="text" name="PTnm" value="" onKeyPress="return submitStop(event);"></td>
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



