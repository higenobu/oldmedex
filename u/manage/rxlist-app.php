
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>Table-to-csv converter</title>
	</head>
	<body>
<a href="index.php?tab=1">�ᥤ������</a>
<br>
<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';
 

$con = mx_db_connect();
 
  //pg_set_client_encoding('EUC_JP');

 
 

$query='SELECT temp1  from wktemp where kubun=1';
  
$rows =  mx_db_fetch_all($con,$query);

  foreach($rows as $row)
 {

	echo $row['temp1']."<br>"; 
  
	 

}   
  
 
$query='SELECT temp1 from wktemp where kubun=2';
  
$rows2 =  mx_db_fetch_all($con,$query);

  foreach($rows2 as $row2)
 {

	echo $row2['temp2']."<br>"; 
  
	 

}   
  

?>



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


    <h1>report</h1>
    <form action="rxlist.php" method="POST">
 
    <table class="abc">
        
     
 
<thead>
  <colgroup>
   <col width="40px">
   <col width="40px">
   <col width="140px">
   <col width="100px">

   <col width="60px">
   <col width="180px">
   <col width="70px">
   <col width="100px">
   <col width="100px">
  </colgroup>  
</thead>
<tbody>
 <tr>
  <th class="darker">kenshindate</th>
  <td class="plain" colspan="7"><input type="text" name="kenshindate"
  value="1"></td>
 </tr>


  <tr>
  <th class="darker">ptno</th>
  <td class="plain" colspan="7"><input type="text" name="ptno"
  value="000010"></td>
 </tr>

<tr><th>exam name</th><td class="plain" colspan="8">
<select name="shiji" id="shiji" onKeyPress="return submitStop(event);">>
 
<option value="a">a</option>
<option value="b">b</option>
<option value="c">c</option>
</select>
</tr>

<tr><th>phraze0</th><td class="plain" colspan="8">
<select name="phraze0[]" multiple id="phraze0" onKeyPress="return submitStop(event);">>
<option value=""></option>



 

<?php

 
 
  
  foreach($rows as $row)
 {

echo("<option value=".'"'.
htmlspecialchars($row['temp1']).'"'.">".htmlspecialchars($row['temp1']).
" </option>\n");	 

 }
			 echo "</select>\n";
 
 

?>












</tr>
<tr><th>phraze1</th><td class="plain" colspan="8">
<select name="phraze1[]" id="phraze1" multiple onKeyPress="return submitStop(event);">>
<option value=""></option>
 


<?php

 

 
 
  
  foreach($rows as $row)
 {

echo("<option value=".'"'.
htmlspecialchars($row['temp1']).'"'.">".htmlspecialchars($row['temp1']).
" </option>\n");	 

 }
			 echo "</select>\n";
 
 

?>
 
</tr>
<tr><th>phraze2</th><td class="plain" colspan="8">
<select name="phraze2[]" id="phraze2" multiple onKeyPress="return submitStop(event);">>
<option value=""></option>
 

 <?php

 
 
  
  foreach($rows2 as $row2)
 {

echo("<option value=".'"'.
htmlspecialchars($row2['temp1']).'"'.">".htmlspecialchars($row2['temp1']).
" </option>\n");	 

 }
			 echo "</select>\n";
 
 

?>

</tr>

<tr><th>phraze3</th><td class="plain" colspan="8">
<select name="phraze3[]" id="phraze3" multiple onKeyPress="return submitStop(event);">>
<option value=""></option>
 

 <?php

 
 
  
  foreach($rows as $row)
 {

echo("<option value=".'"'.
htmlspecialchars($row['temp1']).'"'.">".htmlspecialchars($row['temp1']).
" </option>\n");	 

 }
			 echo "</select>\n";
 
 

?>

</tr>
 


 <tr>
  <th class="heading" colspan="2">A</th>
  <th class="heading" colspan="2">B</th>
  <th class="heading" colspan="2">C</th>
  <th class="heading" colspan="2">D</th>
 </tr>
<br>
 <tr>
  <td class="plain">����</td>
  <td class="plain"></td>
<td class="plain"><input type="checkbox" name="kyobu1"  value="upper"
   >upper<input type="checkbox" name="kyobu2" value="lower"

   >lower</td>
<td class="plain"><input type="text" class="dirtext"
      name="kyobut" value="kyobut"></td>
  

<td class="plain">body</td>
 <td class="plain"></td>

 <td class="plain"><input type="checkbox" name="aaa"  value="aaa">aaa<input type="checkbox" name="bbb" value="bbb">bbb</td>
   
</tr>


<tr>
 <td class="darker">����</td>
  <td class="darker"></td>
  <td class="plain"><input type="checkbox" name="@@N����.2@@" @@����.2@@
   >2<input type="checkbox" name="@@N����.3@@" @@����.3@@
>3<input type="checkbox" name="@@N����.4@@" @@����.4@@
   >�ݡ���</td>

  <td class="plain"><input type="text" class="dirtext"
      name="@@N����.T@@" value="@@����.T@@"></td>

<td class="darker">������Ĺ</td>
  <td class="darker"></td>
  <td class="darker"><input type="checkbox" name="@@N������Ĺ.1@@" @@������Ĺ.1@@
  
   > </td>
 

</tr>
 
<tr>
  <td class="plain">ʢ��1</td>
  <td class="plain"></td>
<td class="plain"><input type="checkbox" name="@@Nʢ��1.1@@" @@ʢ��1.1@@
   >1Ω��<input type="checkbox" name="@@Nʢ��1.2@@" @@ʢ��1.2@@
>1Ω��(KUB)<input type="checkbox" name="@@Nʢ��1.3@@" @@ʢ��1.3@@
>1���<input type="checkbox" name="@@Nʢ��1.4@@" @@ʢ��1.4@@
   >1���(KUB)</td>
<td class="plain"><input type="text" class="dirtext"
      name="@@Nʢ��1.T@@" value="@@ʢ��1.T@@"></td>
  

 
  

  <td class="plain">����</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N����.L@@" @@����.L@@
   >��<input type="checkbox" name="@@N����.R@@" @@����.R@@
   >��<input type="checkbox" name="@@N����.B@@" @@����.B@@
   >ξ</td>
  <td class="plain"><input type="checkbox" name="@@N����.2@@" @@����.2@@
>2<input type="checkbox" name="@@N����.2@@" @@����.2@@
   >3</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N����.T@@" value="@@����.T@@"></td>
 </tr>

<tr>
  <td class="plain">ʢ��¦���</td>
  <td class="plain align-right"

 ><input type="checkbox" name="@@Nʢ��¦���.L@@" @@ʢ��¦���.L@@
   >��<input type="checkbox" name="@@Nʢ��¦���.R@@" @@ʢ��¦���.R@@
  
   >��</td>
  <td class="plain"><input type="checkbox" name="@@Nʢ��¦���.1@@" @@ʢ��¦���.1@@
  
   >1¦���</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@Nʢ��¦���.T@@" value="@@ʢ��¦���.T@@"></td>

<td class="plain">������</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N������.1@@" @@������.1@@
  
   > </td>
<td class="plain"></td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N������.T@@" value="@@������.T@@"></td>



</tr>


<tr>
  <td class="darker">ʢ��</td>
   <td class="darker"></td>
 
  <td class="plain"><input type="checkbox" name="@@Nʢ��.2@@" @@ʢ��.2@@
   >2<input type="checkbox" name="@@Nʢ��.3@@" @@ʢ��.3@@
>3<input type="checkbox" name="@@Nʢ��.4@@" @@ʢ��.4@@
   >�ݡ���</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@Nʢ��.T@@" value="@@ʢ��.T@@"></td>

  <td class="darker">��</td>
  <td class="darker"></td>
  <td class="darker"><input type="checkbox" name="@@N��.L@@" @@��.L@@
   >��<input type="checkbox" name="@@N��.R@@" @@��.R@@
   >��<input type="checkbox" name="@@N��.B@@" @@��.B@@
   >ξ</td>
  <td class="darker"><input type="checkbox" name="@@N��.2@@" @@��.2@@
   >��<input type="checkbox" name="@@N��.4@@" @@��.4@@
 >��(����)<input type="checkbox" name="@@N��.3@@" @@��.3@@
   >��</td>
  <td class="darker"><input type="text" class="dirtext"
      name="@@N��.T@@" value="@@��.T@@"></td>
 </tr>

 <tr>
  <td class="plain">Ƭ��</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@NƬ��.2@@" @@Ƭ��.2@@
   >��</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@NƬ��.T@@" value="@@Ƭ��.T@@"></td>
  <td class="plain">����</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N����.L@@" @@����.L@@
   >��<input type="checkbox" name="@@N����.R@@" @@����.R@@
   >��<input type="checkbox" name="@@N����.B@@" @@����.B@@
   >ξ</td>
  <td class="plain"><input type="checkbox" name="@@N����.2@@" @@����.2@@
  
   >2</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N����.T@@" value="@@����.T@@"></td>
 </tr>

 <tr>
  <td class="darker">����</td>
  <td class="darker"></td>
  <td class="darker"><input type="checkbox" name="@@N����.2@@" @@����.2@@
   >��<input type="checkbox" name="@@N����.4@@" @@����.4@@
   >��<input type="checkbox" name="@@N����.8@@" @@����.8@@
   >4�����<input type="checkbox" name="@@N����.6@@" @@����.6@@
>6<input type="checkbox" name="@@N����.7@@" @@����.7@@
   >7</td>
  <td class="darker"><input type="text" class="dirtext"
      name="@@N����.T@@" value="@@����.T@@"></td>
  <td class="darker">ɪ</td>
  <td class="darker"></td>
  <td class="darker"><input type="checkbox" name="@@Nɪ.L@@" @@ɪ.L@@
   >��<input type="checkbox" name="@@Nɪ.R@@" @@ɪ.R@@
   >��<input type="checkbox" name="@@Nɪ.B@@" @@ɪ.B@@
   >ξ</td>
  <td class="darker"><input type="checkbox" name="@@Nɪ.2@@" @@ɪ.2@@
   >��<input type="checkbox" name="@@Nɪ.4@@" @@ɪ.4@@
   >��</td>
  <td class="darker"><input type="text" class="dirtext"
      name="@@Nɪ.T@@" value="@@ɪ.T@@"></td>
 </tr>

 <tr>
  <td class="plain">����</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N����.2@@" @@����.2@@
   
   >2</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N����.T@@" value="@@����.T@@"></td>
  <td class="plain">����</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N����.L@@" @@����.L@@
   >��<input type="checkbox" name="@@N����.R@@" @@����.R@@
   >��<input type="checkbox" name="@@N����.B@@" @@����.B@@
   >ξ</td>
  <td class="plain"><input type="checkbox" name="@@N����.2@@" @@����.2@@
  
   >2</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N����.T@@" value="@@����.T@@"></td>
 </tr>

 <tr>
  <td class="darker">�����ǰܹ���</td>
  <td class="darker"></td>
  <td class="darker"><input type="checkbox" name="@@N������.2@@" @@������.2@@
   >��</td>
  <td class="darker"><input type="text" class="dirtext"
      name="@@N������.T@@" value="@@������.T@@"></td>

  <td class="darker">�����</td>
  <td class="darker"></td>
  <td class="darker"><input type="checkbox" name="@@N�����.L@@" @@�����.L@@
   >��<input type="checkbox" name="@@N�����.R@@" @@�����.R@@
   >��<input type="checkbox" name="@@N�����.B@@" @@�����.B@@
   >ξ</td>
  <td class="darker"><input type="checkbox" name="@@N�����.2@@" @@�����.2@@
   >��<input type="checkbox" name="@@N�����.4@@" @@�����.4@@
   >��</td>
  <td class="darker"><input type="text" class="dirtext"
      name="@@N�����.T@@" value="@@�����.T@@"></td>
 </tr>

 <tr>
  <td class="plain">����</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N����.2@@" @@����.2@@
   >��<input type="checkbox" name="@@N����.4@@" @@����.4@@
 
   >4<input type="checkbox" name="@@N����.8@@" @@����.8@@
 >4�����<input type="checkbox" name="@@N����.6@@" @@����.6@@
   >6</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N����.T@@" value="@@����.T@@"></td>
  <td class="plain">��</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N��.L@@" @@��.L@@
   >��<input type="checkbox" name="@@N��.R@@" @@��.R@@
   >��<input type="checkbox" name="@@N��.B@@" @@��.B@@
   >ξ</td>
  <td class="plain"><input type="checkbox" name="@@N��.2@@" @@��.2@@
   
   >2</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N��.T@@" value="@@��.T@@"></td>
 </tr>

 <tr>
  <td class="plain">����</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N����.1@@" @@����.1@@
   >��<input type="checkbox" name="@@N����.2@@" @@����.2@@
 >2<input type="checkbox" name="@@N����.3@@" @@����.3@@
   >��</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N����.T@@" value="@@����.T@@"></td>
  <td class="plain">��</td>
  <td class="plain"><input type="checkbox" name="@@N��.D1@@" @@��.D1@@
   >��<input type="checkbox" name="@@N��.D2@@" @@��.D2@@
   >��<input type="checkbox" name="@@N��.D3@@" @@��.D3@@
   >��<input type="checkbox" name="@@N��.D4@@" @@��.D4@@
   >��<input type="checkbox" name="@@N��.D5@@" @@��.D5@@
   >��</td>
  <td class="plain"><input type="checkbox" name="@@N��.L@@" @@��.L@@
   >��<input type="checkbox" name="@@N��.R@@" @@��.R@@
   >��<input type="checkbox" name="@@N��.B@@" @@��.B@@
   >ξ</td>
  <td class="plain"><input type="checkbox" name="@@N��.2@@" @@��.2@@
   
   >2</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N��.T@@" value="@@��.T@@"></td>
 </tr>

 <tr>
  <td class="plain">������</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N������.1@@" @@������.1@@
>1����<input type="checkbox" name="@@N������.2@@" @@������.2@@
>1¦��<input type="checkbox" name="@@N������.3@@" @@������.3@@
   >��</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N������.T@@" value="@@������.T@@"></td>

  <td class="plain">�Դ���</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N�Դ���.L@@" @@�Դ���.L@@
   >��<input type="checkbox" name="@@N�Դ���.R@@" @@�Դ���.R@@


   >��<input type="checkbox" name="@@N�Դ���.B@@" @@�Դ���.B@@
   >ξ</td>
  <td class="plain"><input type="checkbox" name="@@N�Դ���.1@@" @@�Դ���.1@@
   >����</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N�Դ���.T@@" value="@@�Դ���.T@@"></td>
 </tr>

 <tr>
  <td class="plain">­�ٽ�</td>
  <td class="plain align-right"
   ><input type="checkbox" name="@@N­�ٽ�.L@@" @@­�ٽ�.L@@
   >��<input type="checkbox" name="@@N­�ٽ�.R@@" @@­�ٽ�.R@@
   >��<input type="checkbox" name="@@N­�ٽ�.B@@" @@­�ٽ�.B@@
   >ξ</td>
<td class="plain"><input type="checkbox" name="@@N­�ٽ�.1@@" @@­�ٽ�.1@@
 
 >1<input type="checkbox" name="@@N­�ٽ�.2@@" @@­�ٽ�.2@@
   >��</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N­�ٽ�.T@@" value="@@­�ٽ�.T@@"></td>

<td class="plain">�Դ���1</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N�Դ���1.L@@" @@�Դ���1.L@@
   >��<input type="checkbox" name="@@N�Դ���1.R@@" @@�Դ���1.R@@


   >��<input type="checkbox" name="@@N�Դ���1.B@@" @@�Դ���1.B@@
   >ξ</td>
  <td class="plain"><input type="checkbox" name="@@N�Դ���1.1@@" @@�Դ���1.1@@
   >�а�(�饦����)<input type="checkbox" name="@@N�Դ���1.2@@" @@�Դ���1.2@@

  
   > ����</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N�Դ���1.T@@" value="@@�Դ���1.T@@"></td>
  
 </tr>

 <tr>
  <td class="plain">Ͼ��</td>

  <td class="plain align-right"
   ><input type="checkbox" name="@@NϾ��.L@@" @@Ͼ��.L@@
   >��<input type="checkbox" name="@@NϾ��.R@@" @@Ͼ��.R@@
   >��<input type="checkbox" name="@@NϾ��.B@@" @@Ͼ��.B@@
   >ξ</td>
  <td class="plain"><input type="checkbox" name="@@NϾ��.2@@" @@Ͼ��.2@@
>2<input type="checkbox" name="@@NϾ��.3@@" @@Ͼ��.3@@
   >3</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@NϾ��.T@@" value="@@Ͼ��.T@@"></td>

  <td class="plain">����</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N����.L@@" @@����.L@@
   >��<input type="checkbox" name="@@N����.R@@" @@����.R@@
   >��<input type="checkbox" name="@@N����.B@@" @@����.B@@
   >ξ</td>
  <td class="plain"><input type="checkbox" name="@@N����.2@@" @@����.2@@
   
   >2</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N����.T@@" value="@@����.T@@"></td>
 </tr>
<tr>
<td class="darker">�ߥХꥦ��</td>
  <td class="darker"></td>
  <td class="plain"><input type="checkbox" name="@@N�ߥХꥦ��.1@@" @@�ߥХꥦ��.1@@
  
   > </td>

  <td class="plain"><input type="text" class="dirtext"
      name="@@N�ߥХꥦ��.T@@" value="@@�ߥХꥦ��.T@@"></td>

<td class="darker">�ߥ����ȥ�</td>
  <td class="darker"></td>
  <td class="plain"><input type="checkbox" name="@@N�ߥ����ȥ�.1@@" @@�ߥ����ȥ�.1@@
  
   > </td>

  <td class="plain"><input type="text" class="dirtext"
      name="@@N�ߥ����ȥ�.T@@" value="@@�ߥ����ȥ�.T@@"></td>
 <td class="darker"></td>
</tr>



<tr>
<td class="darker">��Ĳ�Хꥦ��</td>
  <td class="darker"></td>
  <td class="plain"><input type="checkbox" name="@@N��Ĳ�Хꥦ��.1@@" @@��Ĳ�Хꥦ��.1@@
  
   > </td>

  <td class="plain"><input type="text" class="dirtext"
      name="@@N��Ĳ�Хꥦ��.T@@" value="@@��Ĳ�Хꥦ��.T@@"></td>

<td class="darker">��Ĳ�����ȥ�</td>
  <td class="darker"></td>
  <td class="plain"><input type="checkbox" name="@@N��Ĳ�����ȥ�.1@@" @@��Ĳ�����ȥ�.1@@
  
   > </td>

  <td class="plain"><input type="text" class="dirtext"
      name="@@N��Ĳ�����ȥ�.T@@" value="@@��Ĳ�����ȥ�.T@@"></td>
 <td class="darker"></td>
</tr>
<tr>
<td class="darker">��Ĳ����</td>
  <td class="darker"></td>
  <td class="plain"><input type="checkbox" name="@@N��Ĳ����.1@@" @@��Ĳ����.1@@
  
   > </td>

  <td class="plain"><input type="text" class="dirtext"
      name="@@N��Ĳ����.T@@" value="@@��Ĳ����.T@@"></td>

<td class="darker">���к��֥�å�</td>
  <td class="darker"></td>
  <td class="plain"><input type="checkbox" name="@@N���к��֥�å�.1@@" @@���к��֥�å�.1@@
  
   > </td>

  <td class="plain"><input type="text" class="dirtext"
      name="@@N���к��֥�å�.T@@" value="@@���к��֥�å�.T@@"></td>
 <td class="darker"></td>
</tr>

<tr>
<td class="darker">DIP</td>
  <td class="darker"></td>
  <td class="plain"><input type="checkbox" name="@@NDIP.1@@" @@DIP.1@@
  
   > </td>

  <td class="plain"><input type="text" class="dirtext"
      name="@@NDIP.T@@" value="@@DIP.T@@"></td>

<td class="darker">��������</td>
  <td class="darker"></td>
  <td class="plain"><input type="checkbox" name="@@��������.1@@" @@��������.1@@
  
   > </td>

  <td class="plain"><input type="text" class="dirtext"
      name="@@N��������.T@@" value="@@��������.T@@"></td>

</tr>







 <tr>
  <th class="heading" colspan="4">����¾��������</th>

  <td class="darker">ɨ</td>
  <td class="darker"></td>
  <td class="darker"><input type="checkbox" name="@@Nɨ.L@@" @@ɨ.L@@
   >��<input type="checkbox" name="@@Nɨ.R@@" @@ɨ.R@@
   >��<input type="checkbox" name="@@Nɨ.B@@" @@ɨ.B@@
   >ξ</td>
  <td class="darker"><input type="checkbox" name="@@Nɨ.2@@" @@ɨ.2@@
   >��<input type="checkbox" name="@@Nɨ.3@@" @@ɨ.3@@
   >��</td>
  <td class="darker"><input type="text" class="dirtext"
      name="@@Nɨ.T@@" value="@@ɨ.T@@"></td>
 </tr>

 <tr>
  <td class="plain" colspan="4" rowspan="6"
  ><textarea class="plain miscinfo" cols="60" rows="8"
    name="@@N����¾@@">@@����¾@@</textarea
  ></td>
  <td class="plain">����</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N����.L@@" @@����.L@@
   >��<input type="checkbox" name="@@N����.R@@" @@����.R@@
   >��<input type="checkbox" name="@@N����.B@@" @@����.B@@
   >ξ</td>
  <td class="plain"><input type="checkbox" name="@@N����.2@@" @@����.2@@
   
   >2</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N����.T@@" value="@@����.T@@"></td>
 </tr>

 <tr>
  <td class="darker">­����</td>
  <td class="darker"></td>
  <td class="darker"><input type="checkbox" name="@@N­����.L@@" @@­����.L@@
   >��<input type="checkbox" name="@@N­����.R@@" @@­����.R@@
   >��<input type="checkbox" name="@@N­����.B@@" @@­����.B@@
   >ξ</td>
  <td class="darker"><input type="checkbox" name="@@N­����.2@@" @@­����.2@@
   >��<input type="checkbox" name="@@N­����.3@@" @@­����.3@@
   >3</td>
  <td class="darker"><input type="text" class="dirtext"
      name="@@N­����.T@@" value="@@­����.T@@"></td>
 </tr>

 <tr>
  <td class="plain">­��</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N­��.L@@" @@­��.L@@
   >��<input type="checkbox" name="@@N­��.R@@" @@­��.R@@
   >��<input type="checkbox" name="@@N­��.B@@" @@­��.B@@
   >ξ</td>
  <td class="plain"><input type="checkbox" name="@@N­��.2@@" @@­��.2@@
  
   >2</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N­��.T@@" value="@@­��.T@@"></td>
 </tr>

 <tr>
  <td class="plain">��</td>
  <td class="plain"><input type="checkbox" name="@@N��.D1@@" @@��.D1@@
   >��<input type="checkbox" name="@@N��.D2@@" @@��.D2@@
   >��<input type="checkbox" name="@@N��.D3@@" @@��.D3@@
   >��<input type="checkbox" name="@@N��.D4@@" @@��.D4@@
   >��<input type="checkbox" name="@@N��.D5@@" @@��.D5@@
   >��</td>
  <td class="plain"><input type="checkbox" name="@@N��.L@@" @@��.L@@
   >��<input type="checkbox" name="@@N��.R@@" @@��.R@@
   >��<input type="checkbox" name="@@N��.B@@" @@��.B@@
   >ξ</td>
  <td class="plain"><input type="checkbox" name="@@N��.2@@" @@��.2@@
   >��</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N��.T@@" value="@@��.T@@"></td>
 </tr>

 <tr>
  <td class="plain">����</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N����.L@@" @@����.L@@
   >��<input type="checkbox" name="@@N����.R@@" @@����.R@@
   >��<input type="checkbox" name="@@N����.B@@" @@����.B@@
   >ξ</td>
  <td class="plain"><input type="checkbox" name="@@N����.1@@" @@����.1@@
   >��<input type="checkbox" name="@@N����.2@@" @@����.2@@
   
   >2</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N����.T@@" value="@@����.T@@"></td>
 </tr>
<tr>
  <td class="plain">����</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N����.L@@" @@����.L@@
   >��<input type="checkbox" name="@@N����.R@@" @@����.R@@
   >��<input type="checkbox" name="@@N����.B@@" @@����.B@@
   >ξ</td>
  <td class="plain"><input type="checkbox" name="@@N����.1@@" @@����.1@@
   >��<input type="checkbox" name="@@N����.2@@" @@����.2@@
   
   >2</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N����.T@@" value="@@����.T@@"></td>
 </tr>
<tr align="center">
          <td colspan="2">
            <input type="submit" value="Submit">
            <input type="reset" value="Clear">
          </td>
        </tr>
</tbody>
</table>

    </form>


 
<?php
$default_tab = $_GET['tab']; // This gets the tab number from the url
$selected = 'class="selected"'; // This is the text to add to the tab html
?> 


  </body>
</html>



