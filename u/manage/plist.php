
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>query tables</title>
	</head>
	
 
 




<body>
 
 
    <h1>Query Table</h1>
    <form action="tablev.php" method="POST">
      <table border="0"
             summary="file create and write">
        <tr>
          <td>DB name</td>
          <td><input type="text" name="dbname" value="medexdb5"></td>
        </tr>
        <tr>
          <td>Table name</td>
          <td><input type="text" name="table" value="tbl_plist"></td>
        </tr>
	<tr>
          <td>pre date</td>
          <td><input type="text" name="plusdate" value="0"></td>
        </tr>
	<tr>
          <td>post date</td>
          <td><input type="text" name="plusdate2" value="0"></td>
        </tr>
	<tr>
          <td>in out(I,O) </td>
          <td><input type="text" name="inout" value="I"></td>
        </tr>
	<tr>
          <td>room </td>
          <td><input type="text" name="room" value="0"></td>
        </tr>
        <tr align="center">
          <td colspan="2">
            <input type="submit" value="Submit">
            <input type="reset" value="Clear">
          </td>
        </tr>
      </table>

    </form>
<form action="#" method="post" name="fdata" id="fdata">
<input type="hidden" name="hidden_var" value="1">
<input type="button" name="btn" value="submit" onclick="window_open();">
</form>
 
<?php
$default_tab = $_GET['tab']; // This gets the tab number from the url
$selected = 'class="selected"'; // This is the text to add to the tab html


 
?> 


  </body>
</html>



