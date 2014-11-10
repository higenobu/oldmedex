
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>query tables</title>
	</head>
	





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




    <h1>Employee schedule Table</h1>
    <form action="tablev1.php" method="POST">
      <table border="0"
             summary="file create and write">
        
        <tr>
          <td>Table name</td>
          <td><input type="text" name="table" value="empschedv" onKeyPress="return submitStop(event);"></td>
        </tr>
	<tr>
          <td>pre date</td>
          <td><input type="text" name="plusdate" value="0" onKeyPress="return submitStop(event);"></td>
        </tr>
	<tr>
          <td>post date</td>
          <td><input type="text" name="plusdate2" value="0" onKeyPress="return submitStop(event);"></td>
        </tr>
	<tr>
          <td>class </td>
          <td><input type="text" name="class" value="0" onKeyPress="return submitStop(event);"></td>
        </tr>
	<tr>
          <td>ka (dept) </td>
          <td><input type="text" name="room" value="0" onKeyPress="return submitStop(event);"></td>
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



