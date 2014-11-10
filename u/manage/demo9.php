<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>DOWNLOAD file</title>
	</head>
	<body>
<a href="index.php?tab=1">go back to main</a>
<a href="osato.ods">osato.ods</a>
 <a href="osato2.ods">osato2.ods</a>
<a href="program1.tar.gz">program1</a>

<br>


    <h1>download </h1>
    <form action="download2.php" method="POST">
      <table border="0"
             summary="Dowload">
        <tr>
          <td>file name</td>
          <td><input type="text" name="filename" value="lcmtest.csv"></td>
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



