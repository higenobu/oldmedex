
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>query tables</title>
	</head>
	





<body>
<a href="index.php?tab=1">go back to main</a>


    <h1>drj test demo</h1>
    <form action="osatodown.php" method="POST">
      <table border="0"
             summary="file create and write">
         
	<tr>
          <td>´µ¼ÔID</td>
          <td><input type="text" name="class" value=""></td>
        </tr>
	<tr>
          <td>last anme</td>
          <td><input type="text" name="big" value=""></td>
        </tr>
	<tr>
          <td>first name</td>
          <td><input type="text" name="middle" value=""></td>
        </tr>
	<tr>
          <td>kana</td>
          <td><input type="text" name="small" value=""></td>
        </tr>
        <tr align="center">
          <td colspan="4">
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



