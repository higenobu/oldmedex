
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>query tables</title>
	</head>
	





<body>


    <h1>test result table</h1>
    <form action="testrup.php" method="POST">
      <table border="0"
             summary="file create and write">
        <tr>
          <td>datbase name</td>
          <td><input type="text" name="dbname" value="medexdb5"></td>
        </tr>
        <tr>
          <td>table name</td>
          <td><input type="text" name="table" value="test_result"></td>
        </tr>
	<tr>
          <td>date</td>
          <td><input type="text" name="today-date" value="0"></td>
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



