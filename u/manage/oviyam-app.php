
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>karte-print</title>
	</head>
	<body>
<a href="index.php?tab=1">�ᥤ������</a>
<br>




<body>




    <h1>DICOM����</h1>
    <form action="oviyam.php" method="POST">
      <table border="0"
             summary="xct-list">
        
       
	<tr>
          <td>����ID</td>
          <td><input type="text" name="ptid" value="00253921"></td>
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



