
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


    <h1>RX Table</h1>
    <form action="rxlist.php" method="POST">
      <table border="0"
             summary="xct-list">
        
       
	<tr>
          <td>���������������</td>
          <td><input type="text" name="plusdate" value="0" onKeyPress="return submitStop(event);"></td>
        </tr>
	<tr>
          <td>�������������</td>
          <td><input type="text" name="plusdate2" value="0" onKeyPress="return submitStop(event);"></td>
        </tr>
	<tr>
          <td>���մ��ԤΤ�:���������</td>
          <td><input type="text" name="uketuke" value="2" onKeyPress="return submitStop(event);"></td>
        </tr>
        <tr align="center">
          <td colspan="2">
            <input type="submit" value="Submit">
            <input type="reset" value="Clear">
          </td>
        </tr>
      </table>
<table class="quickxray">
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
<tr><th>�ؼ���</th><td class="plain" colspan="8"><select name="@@Nshiji@@" id="@@shiji@@" onKeyPress="return disableEnterKey(this,event)">
<option value=""></option>
<option value="���ʿ�">���ʿ�</option>
<option value="㦾� ��">㦾� ��</option>
<option value="��������">��������</option>
<option value="�������ƹ�">��������</option>
<option value="��ë��">��ë��ë</option>
<option value="���ڷ���">���ڷ���</option>
<option value="����ȥ">����ȥ</option>
<option value="�غ���ɧ">�غ���ɧ</option>
<option value="����Ԣ��">����Ԣ��</option>
<option value="������">������</option>
<option value="�������">�������</option>
<option value="�ֿ彨��">�ֿ彨��</option>
<option value="ȿĮ���">ȿĮ���</option>
<option value="�İ�ӹ�">�İ�ӹ�</option>
<option value="����δ��">����δ��</option>
<option value="�������">�������</option>
<option value="��ȫͦ��">��ȫͦ��</option>
<option value="����">����</option>
<option value="��������">��������</option>
<option value="�ڸ���">�ڸ���</option>

</select>


</tr>

 <tr>
  <th class="darker">����</th>
  <td class="plain" colspan="4"><input type="hidden" name="@@N����@@"
  value="@@����@@">@@����@@</td>
<td class="plain" colspan="4"><input type="hidden" name="@@Ntime@@"
  value="@@time@@">@@time@@</td>

<tr><th>ͽ����</th><td class="plain" colspan="8"><div style="white-space: nowrap"><script>var calend = new MedexCalendarPopup("div-soe-a5aaa1bca5c0bdaacebbc6fc");</script>
<div id="div-soe-a5aaa1bca5c0bdaacebbc6fc" class="soecal" style="position:absolute; visibility:hidden"></div>
<input type="text" onKeyPress="return disableEnterKey(this,event)" id="soe-a5aaa1bca5c0bdaacebbc6fc" name="@@Nyotei@@" value="@@yotei@@">
<a href="#" onclick="calend.select(document.getElementById('soe-a5aaa1bca5c0bdaacebbc6fc'),'anchor-soe-a5aaa1bca5c0bdaacebbc6fc', 'yyyy-MM-dd'); return false;" id="anchor-soe-a5aaa1bca5c0bdaacebbc6fc" name="anchor-soe-a5aaa1bca5c0bdaacebbc6fc" >*</a></div></td></tr>

 <tr>
  <th class="darker">���</th>
  <td class="plain" colspan="7"><input type="text" name="@@Nstop@@"
  value="@@stop@@"></td>
 </tr>

 <tr>
  <th class="heading" colspan="2">����</th>
  <th class="heading" colspan="2">����</th>
  <th class="heading" colspan="3">����</th>
  <th class="heading" colspan="2">����</th>
 </tr>

  <tr>
  <td class="plain">����1</td>
  <td class="plain"></td>
<td class="plain"><input type="checkbox" name="@@N����1.1@@" @@����1.1@@
   >1Ω��<input type="checkbox" name="@@N����1.2@@" @@����1.2@@

   >1���</td>
<td class="plain"><input type="text" class="dirtext"
      name="@@N����1.T@@" value="@@����1.T@@"></td>
  

<td class="plain">����¦���</td>
 <td class="plain"></td>

 <td class="plain"><input type="checkbox" name="@@N����¦���.L@@" @@����¦���.L@@
   >��<input type="checkbox" name="@@N����¦���.R@@" @@����¦���.R@@
  
   >��</td>
  <td class="plain"><input type="checkbox" name="@@N����¦���.1@@" @@����¦���.1@@
  
   >1¦���</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N����¦���.T@@" value="@@����¦���.T@@"></td>

  
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
<td class="darker"></td>

  <td class="darker"><input type="text" class="dirtext"
      name="@@N������Ĺ.T@@" value="@@������Ĺ.T@@"></td>


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

<td class="darker">���к��֥��å�</td>
  <td class="darker"></td>
  <td class="plain"><input type="checkbox" name="@@N���к��֥��å�.1@@" @@���к��֥��å�.1@@
  
   > </td>

  <td class="plain"><input type="text" class="dirtext"
      name="@@N���к��֥��å�.T@@" value="@@���к��֥��å�.T@@"></td>
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
</tbody>
</table>

    </form>


 
<?php
$default_tab = $_GET['tab']; // This gets the tab number from the url
$selected = 'class="selected"'; // This is the text to add to the tab html
?> 


  </body>
</html>


