
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>cretate file </title>
	</head>
	

<a href="index.php?tab=1">go to main</a>



<body>




    <h1>Create chart record</h1>
    <form action="chartrec.php" method="POST">
      <table border="0"
             summary="file create and write">
 

         
	<tr>
          <td>days before from today</td>
          <td><input type="text" name="plusdate" value="100"></td>
        </tr>
	<tr>
          <td>days after today</td>
          <td><input type="text" name="plusdate2" value="0"></td>
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



