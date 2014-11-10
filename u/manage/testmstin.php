

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>testmasterupdate</title>
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


    <h1>TEST Master Table</h1>
    <form action="testmst.php" method="POST">
      <table border="0"
             summary="xct-list">
        
	
	<tr><th>追加・更新</th><td class="plain" colspan="7"><select name="add" id="add" onKeyPress="return disableEnterKey(this,event)">
<option value="add">追加（検査コードが存在しないこと）</option>
<option value="update">更新(検査コードが存在すること）</option>
       </tr>
	<tr>
          <td>検査コード(必ず必要)</td>
          <td><input type="text" name="testcode" value="" onKeyPress="return submitStop(event);"></td>
        </tr>
	<tr>
          <td>名前</td>
          <td><input type="text" name="testname" value="" onKeyPress="return submitStop(event);"></td>
        </tr>
	<tr>
          <td>単位</td>
          <td><input type="text" name="unit" value="" onKeyPress="return submitStop(event);"></td>
        </tr>
	
	<tr>
          <td>男下限</td>
          <td><input type="text" name="mdown" value="" onKeyPress="return submitStop(event);"></td>
        </tr>
	<tr>
          <td>男上限</td>
          <td><input type="text" name="mup" value="" onKeyPress="return submitStop(event);"></td>
        </tr>
	<tr>
          <td>男正常範囲</td>
          <td><input type="text" name="mgood" value="" onKeyPress="return submitStop(event);"></td>
        </tr>
	<tr>
          <td>女下限</td>
          <td><input type="text" name="fdown" value="" onKeyPress="return submitStop(event);"></td>
        </tr>
	<tr>
          <td>女上限</td>
          <td><input type="text" name="fup" value="" onKeyPress="return submitStop(event);"></td>
        </tr>
	<tr>
          <td>女正常範囲</td>
          <td><input type="text" name="fgood" value="" onKeyPress="return submitStop(event);"></td>
        </tr>
	
	<tr><th>カテゴリ</th><td class="plain" colspan=""><select name="cat" id="cat" onKeyPress="return disableEnterKey(this,event)">
<option value=""></option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>

<option value=""></option>
       </tr>
	<tr><th>採用</th><td class="plain" colspan="7"><select name="saiyo" id="saiyo" onKeyPress="return disableEnterKey(this,event)">

<option value=""></option>
<option value="Y">採用</option>
<option value="N">不採用</option>
       </tr>

	
	<tr><th>セットの先頭</th><td class="plain" colspan="7"><select name="sethead" id="sethead" onKeyPress="return disableEnterKey(this,event)">
<option value=""></option>
<option value="1">先頭</option>

       </tr>
	<tr>
          <td>親の検査コード(ないときは0)</td>
          <td><input type="text" name="oya" value="0" onKeyPress="return submitStop(event);"></td>
        </tr>
	<tr>
          <td>receptcode</td>
          <td><input type="text" name="rcode" value="" onKeyPress="return submitStop(event);"></td>
        </tr>
	<tr>
          <td>Medis code</td>
          <td><input type="text" name="medis" value="" onKeyPress="return submitStop(event);"></td>
        </tr>
        <tr align="center">
          <td colspan="2">
            <input type="submit" value="Submit">
            <input type="reset" value="Clear">
          </td>
        </tr>
      </table>

    </form>



  </body>
</html>



