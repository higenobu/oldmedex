
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf8">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>manual</title>
	</head>
	<body>
<a href="index.php?tab=1">go back to main</a>
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


<a href="man/index.html">manual</a>

     

<h1>addmap</h1>
    <form action="addmaptbl.php" method="POST">
      <table border="0"
             summary="xct-list">
        
       
	<tr>
          <td>aname</td>
          <td><input type="text" name="aname" value="" onKeyPress="return submitStop(event);"></td>
        </tr>
	 <tr>
          <td>attrbute class</td>
          <td><input type="text" name="bname" value="" onKeyPress="return submitStop(event);"></td>
        </tr>
<tr>
          <td>attribute variable</td>
          <td><input type="text" name="para" value="" onKeyPress="return submitStop(event);"></td>
        </tr>
<tr>
          <td>attrbute label</td>
          <td><input type="text" name="med" value="" onKeyPress="return submitStop(event);"></td>
        </tr>
<tr>
          <td>aid</td>
          <td><input type="text" name="aid" value="" onKeyPress="return submitStop(event);"></td>
        </tr>
<tr>
          <td>bid</td>
          <td><input type="text" name="bid" value="" onKeyPress="return submitStop(event);"></td>
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



