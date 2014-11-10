
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>Table-to-csv converter</title>
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


    <h1>RX Table</h1>
    <form action="rxlist.php" method="POST">
      <table border="0"
             summary="xct-list">
        
       
	<tr>
          <td>今日より前の日数</td>
          <td><input type="text" name="plusdate" value="0" onKeyPress="return submitStop(event);"></td>
        </tr>
	<tr>
          <td>今日より後の日数</td>
          <td><input type="text" name="plusdate2" value="0" onKeyPress="return submitStop(event);"></td>
        </tr>
	<tr>
          <td>受付患者のみ:２を入れる</td>
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
<tr><th>指示医</th><td class="plain" colspan="8"><select name="@@Nshiji@@" id="@@shiji@@" onKeyPress="return disableEnterKey(this,event)">
<option value=""></option>
<option value="松永仁">松永仁</option>
<option value="礒沼 弘">礒沼 弘</option>
<option value="今井壽正">今井壽正</option>
<option value="今給黎篤弘">今給黎篤</option>
<option value="梅谷薫">梅谷薫谷</option>
<option value="岩切啓二">岩切啓二</option>
<option value="加瀬肇">加瀬肇</option>
<option value="関根和彦">関根和彦</option>
<option value="高木國夫">高木國夫</option>
<option value="西山誠">西山誠</option>
<option value="矢数俊明">矢数俊明</option>
<option value="志水秀行">志水秀行</option>
<option value="反町武史">反町武史</option>
<option value="田井俊宏">田井俊宏</option>
<option value="塚田隆憲">塚田隆憲</option>
<option value="山崎武志">山崎武志</option>
<option value="稲畠勇仁">稲畠勇仁</option>
<option value="岩崎剛">岩崎剛</option>
<option value="楠瀬浩一">楠瀬浩一</option>
<option value="木原一">木原一</option>

</select>


</tr>

 <tr>
  <th class="darker">日付</th>
  <td class="plain" colspan="4"><input type="hidden" name="@@N日付@@"
  value="@@日付@@">@@日付@@</td>
<td class="plain" colspan="4"><input type="hidden" name="@@Ntime@@"
  value="@@time@@">@@time@@</td>

<tr><th>予定日</th><td class="plain" colspan="8"><div style="white-space: nowrap"><script>var calend = new MedexCalendarPopup("div-soe-a5aaa1bca5c0bdaacebbc6fc");</script>
<div id="div-soe-a5aaa1bca5c0bdaacebbc6fc" class="soecal" style="position:absolute; visibility:hidden"></div>
<input type="text" onKeyPress="return disableEnterKey(this,event)" id="soe-a5aaa1bca5c0bdaacebbc6fc" name="@@Nyotei@@" value="@@yotei@@">
<a href="#" onclick="calend.select(document.getElementById('soe-a5aaa1bca5c0bdaacebbc6fc'),'anchor-soe-a5aaa1bca5c0bdaacebbc6fc', 'yyyy-MM-dd'); return false;" id="anchor-soe-a5aaa1bca5c0bdaacebbc6fc" name="anchor-soe-a5aaa1bca5c0bdaacebbc6fc" >*</a></div></td></tr>

 <tr>
  <th class="darker">中止</th>
  <td class="plain" colspan="7"><input type="text" name="@@Nstop@@"
  value="@@stop@@"></td>
 </tr>

 <tr>
  <th class="heading" colspan="2">部位</th>
  <th class="heading" colspan="2">方向</th>
  <th class="heading" colspan="3">部位</th>
  <th class="heading" colspan="2">方向</th>
 </tr>

  <tr>
  <td class="plain">胸部1</td>
  <td class="plain"></td>
<td class="plain"><input type="checkbox" name="@@N胸部1.1@@" @@胸部1.1@@
   >1立位<input type="checkbox" name="@@N胸部1.2@@" @@胸部1.2@@

   >1臥位</td>
<td class="plain"><input type="text" class="dirtext"
      name="@@N胸部1.T@@" value="@@胸部1.T@@"></td>
  

<td class="plain">胸部側臥位</td>
 <td class="plain"></td>

 <td class="plain"><input type="checkbox" name="@@N胸部側臥位.L@@" @@胸部側臥位.L@@
   >左<input type="checkbox" name="@@N胸部側臥位.R@@" @@胸部側臥位.R@@
  
   >右</td>
  <td class="plain"><input type="checkbox" name="@@N胸部側臥位.1@@" @@胸部側臥位.1@@
  
   >1側臥位</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N胸部側臥位.T@@" value="@@胸部側臥位.T@@"></td>

  
 </tr>


<tr>
 <td class="darker">胸部</td>
  <td class="darker"></td>
  <td class="plain"><input type="checkbox" name="@@N胸部.2@@" @@胸部.2@@
   >2<input type="checkbox" name="@@N胸部.3@@" @@胸部.3@@
>3<input type="checkbox" name="@@N胸部.4@@" @@胸部.4@@
   >ポータ</td>

  <td class="plain"><input type="text" class="dirtext"
      name="@@N胸部.T@@" value="@@胸部.T@@"></td>

<td class="darker">下肢全長</td>
  <td class="darker"></td>
  <td class="darker"><input type="checkbox" name="@@N下肢全長.1@@" @@下肢全長.1@@
  
   > </td>
<td class="darker"></td>

  <td class="darker"><input type="text" class="dirtext"
      name="@@N下肢全長.T@@" value="@@下肢全長.T@@"></td>


</tr>
 
<tr>
  <td class="plain">腹部1</td>
  <td class="plain"></td>
<td class="plain"><input type="checkbox" name="@@N腹部1.1@@" @@腹部1.1@@
   >1立位<input type="checkbox" name="@@N腹部1.2@@" @@腹部1.2@@
>1立位(KUB)<input type="checkbox" name="@@N腹部1.3@@" @@腹部1.3@@
>1臥位<input type="checkbox" name="@@N腹部1.4@@" @@腹部1.4@@
   >1臥位(KUB)</td>
<td class="plain"><input type="text" class="dirtext"
      name="@@N腹部1.T@@" value="@@腹部1.T@@"></td>
  

 
  

  <td class="plain">鎖骨</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N鎖骨.L@@" @@鎖骨.L@@
   >左<input type="checkbox" name="@@N鎖骨.R@@" @@鎖骨.R@@
   >右<input type="checkbox" name="@@N鎖骨.B@@" @@鎖骨.B@@
   >両</td>
  <td class="plain"><input type="checkbox" name="@@N鎖骨.2@@" @@鎖骨.2@@
>2<input type="checkbox" name="@@N鎖骨.2@@" @@鎖骨.2@@
   >3</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N鎖骨.T@@" value="@@鎖骨.T@@"></td>
 </tr>

<tr>
  <td class="plain">腹部側臥位</td>
  <td class="plain align-right"

 ><input type="checkbox" name="@@N腹部側臥位.L@@" @@腹部側臥位.L@@
   >左<input type="checkbox" name="@@N腹部側臥位.R@@" @@腹部側臥位.R@@
  
   >右</td>
  <td class="plain"><input type="checkbox" name="@@N腹部側臥位.1@@" @@腹部側臥位.1@@
  
   >1側臥位</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N腹部側臥位.T@@" value="@@腹部側臥位.T@@"></td>

<td class="plain">全脊椎</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N全脊椎.1@@" @@全脊椎.1@@
  
   > </td>
<td class="plain"></td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N全脊椎.T@@" value="@@全脊椎.T@@"></td>



</tr>


<tr>
  <td class="darker">腹部</td>
   <td class="darker"></td>
 
  <td class="plain"><input type="checkbox" name="@@N腹部.2@@" @@腹部.2@@
   >2<input type="checkbox" name="@@N腹部.3@@" @@腹部.3@@
>3<input type="checkbox" name="@@N腹部.4@@" @@腹部.4@@
   >ポータ</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N腹部.T@@" value="@@腹部.T@@"></td>

  <td class="darker">肩</td>
  <td class="darker"></td>
  <td class="darker"><input type="checkbox" name="@@N肩.L@@" @@肩.L@@
   >左<input type="checkbox" name="@@N肩.R@@" @@肩.R@@
   >右<input type="checkbox" name="@@N肩.B@@" @@肩.B@@
   >両</td>
  <td class="darker"><input type="checkbox" name="@@N肩.2@@" @@肩.2@@
   >２<input type="checkbox" name="@@N肩.4@@" @@肩.4@@
 >２(軸位)<input type="checkbox" name="@@N肩.3@@" @@肩.3@@
   >３</td>
  <td class="darker"><input type="text" class="dirtext"
      name="@@N肩.T@@" value="@@肩.T@@"></td>
 </tr>

 <tr>
  <td class="plain">頭部</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N頭部.2@@" @@頭部.2@@
   >２</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N頭部.T@@" value="@@頭部.T@@"></td>
  <td class="plain">上腕</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N上腕.L@@" @@上腕.L@@
   >左<input type="checkbox" name="@@N上腕.R@@" @@上腕.R@@
   >右<input type="checkbox" name="@@N上腕.B@@" @@上腕.B@@
   >両</td>
  <td class="plain"><input type="checkbox" name="@@N上腕.2@@" @@上腕.2@@
  
   >2</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N上腕.T@@" value="@@上腕.T@@"></td>
 </tr>

 <tr>
  <td class="darker">頚椎</td>
  <td class="darker"></td>
  <td class="darker"><input type="checkbox" name="@@N頚椎.2@@" @@頚椎.2@@
   >２<input type="checkbox" name="@@N頚椎.4@@" @@頚椎.4@@
   >４<input type="checkbox" name="@@N頚椎.8@@" @@頚椎.8@@
   >4前後屈<input type="checkbox" name="@@N頚椎.6@@" @@頚椎.6@@
>6<input type="checkbox" name="@@N頚椎.7@@" @@頚椎.7@@
   >7</td>
  <td class="darker"><input type="text" class="dirtext"
      name="@@N頚椎.T@@" value="@@頚椎.T@@"></td>
  <td class="darker">肘</td>
  <td class="darker"></td>
  <td class="darker"><input type="checkbox" name="@@N肘.L@@" @@肘.L@@
   >左<input type="checkbox" name="@@N肘.R@@" @@肘.R@@
   >右<input type="checkbox" name="@@N肘.B@@" @@肘.B@@
   >両</td>
  <td class="darker"><input type="checkbox" name="@@N肘.2@@" @@肘.2@@
   >２<input type="checkbox" name="@@N肘.4@@" @@肘.4@@
   >４</td>
  <td class="darker"><input type="text" class="dirtext"
      name="@@N肘.T@@" value="@@肘.T@@"></td>
 </tr>

 <tr>
  <td class="plain">胸椎</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N胸椎.2@@" @@胸椎.2@@
   
   >2</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N胸椎.T@@" value="@@胸椎.T@@"></td>
  <td class="plain">前腕</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N前腕.L@@" @@前腕.L@@
   >左<input type="checkbox" name="@@N前腕.R@@" @@前腕.R@@
   >右<input type="checkbox" name="@@N前腕.B@@" @@前腕.B@@
   >両</td>
  <td class="plain"><input type="checkbox" name="@@N前腕.2@@" @@前腕.2@@
  
   >2</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N前腕.T@@" value="@@前腕.T@@"></td>
 </tr>

 <tr>
  <td class="darker">胸腰椎移行部</td>
  <td class="darker"></td>
  <td class="darker"><input type="checkbox" name="@@N胸腰椎.2@@" @@胸腰椎.2@@
   >２</td>
  <td class="darker"><input type="text" class="dirtext"
      name="@@N胸腰椎.T@@" value="@@胸腰椎.T@@"></td>

  <td class="darker">手関節</td>
  <td class="darker"></td>
  <td class="darker"><input type="checkbox" name="@@N手関節.L@@" @@手関節.L@@
   >左<input type="checkbox" name="@@N手関節.R@@" @@手関節.R@@
   >右<input type="checkbox" name="@@N手関節.B@@" @@手関節.B@@
   >両</td>
  <td class="darker"><input type="checkbox" name="@@N手関節.2@@" @@手関節.2@@
   >２<input type="checkbox" name="@@N手関節.4@@" @@手関節.4@@
   >４</td>
  <td class="darker"><input type="text" class="dirtext"
      name="@@N手関節.T@@" value="@@手関節.T@@"></td>
 </tr>

 <tr>
  <td class="plain">腰椎</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N腰椎.2@@" @@腰椎.2@@
   >２<input type="checkbox" name="@@N腰椎.4@@" @@腰椎.4@@
 
   >4<input type="checkbox" name="@@N腰椎.8@@" @@腰椎.8@@
 >4前後屈<input type="checkbox" name="@@N腰椎.6@@" @@腰椎.6@@
   >6</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N腰椎.T@@" value="@@腰椎.T@@"></td>
  <td class="plain">手</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N手.L@@" @@手.L@@
   >左<input type="checkbox" name="@@N手.R@@" @@手.R@@
   >右<input type="checkbox" name="@@N手.B@@" @@手.B@@
   >両</td>
  <td class="plain"><input type="checkbox" name="@@N手.2@@" @@手.2@@
   
   >2</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N手.T@@" value="@@手.T@@"></td>
 </tr>

 <tr>
  <td class="plain">骨盤</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N骨盤.1@@" @@骨盤.1@@
   >１<input type="checkbox" name="@@N骨盤.2@@" @@骨盤.2@@
 >2<input type="checkbox" name="@@N骨盤.3@@" @@骨盤.3@@
   >３</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N骨盤.T@@" value="@@骨盤.T@@"></td>
  <td class="plain">指</td>
  <td class="plain"><input type="checkbox" name="@@N指.D1@@" @@指.D1@@
   >１<input type="checkbox" name="@@N指.D2@@" @@指.D2@@
   >２<input type="checkbox" name="@@N指.D3@@" @@指.D3@@
   >３<input type="checkbox" name="@@N指.D4@@" @@指.D4@@
   >４<input type="checkbox" name="@@N指.D5@@" @@指.D5@@
   >５</td>
  <td class="plain"><input type="checkbox" name="@@N指.L@@" @@指.L@@
   >左<input type="checkbox" name="@@N指.R@@" @@指.R@@
   >右<input type="checkbox" name="@@N指.B@@" @@指.B@@
   >両</td>
  <td class="plain"><input type="checkbox" name="@@N指.2@@" @@指.2@@
   
   >2</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N指.T@@" value="@@指.T@@"></td>
 </tr>

 <tr>
  <td class="plain">仙尾骨</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N仙尾骨.1@@" @@仙尾骨.1@@
>1正面<input type="checkbox" name="@@N仙尾骨.2@@" @@仙尾骨.2@@
>1側面<input type="checkbox" name="@@N仙尾骨.3@@" @@仙尾骨.3@@
   >２</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N仙尾骨.T@@" value="@@仙尾骨.T@@"></td>

  <td class="plain">股関節</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N股関節.L@@" @@股関節.L@@
   >左<input type="checkbox" name="@@N股関節.R@@" @@股関節.R@@


   >右<input type="checkbox" name="@@N股関節.B@@" @@股関節.B@@
   >両</td>
  <td class="plain"><input type="checkbox" name="@@N股関節.1@@" @@股関節.1@@
   >正面</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N股関節.T@@" value="@@股関節.T@@"></td>
 </tr>

 <tr>
  <td class="plain">足荷重</td>
  <td class="plain align-right"
   ><input type="checkbox" name="@@N足荷重.L@@" @@足荷重.L@@
   >左<input type="checkbox" name="@@N足荷重.R@@" @@足荷重.R@@
   >右<input type="checkbox" name="@@N足荷重.B@@" @@足荷重.B@@
   >両</td>
<td class="plain"><input type="checkbox" name="@@N足荷重.1@@" @@足荷重.1@@
 
 >1<input type="checkbox" name="@@N足荷重.2@@" @@足荷重.2@@
   >２</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N足荷重.T@@" value="@@足荷重.T@@"></td>

<td class="plain">股関節1</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N股関節1.L@@" @@股関節1.L@@
   >左<input type="checkbox" name="@@N股関節1.R@@" @@股関節1.R@@


   >右<input type="checkbox" name="@@N股関節1.B@@" @@股関節1.B@@
   >両</td>
  <td class="plain"><input type="checkbox" name="@@N股関節1.1@@" @@股関節1.1@@
   >斜位(ラウエン)<input type="checkbox" name="@@N股関節1.2@@" @@股関節1.2@@

  
   > 軸位</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N股関節1.T@@" value="@@股関節1.T@@"></td>
  
 </tr>

 <tr>
  <td class="plain">肋骨</td>

  <td class="plain align-right"
   ><input type="checkbox" name="@@N肋骨.L@@" @@肋骨.L@@
   >左<input type="checkbox" name="@@N肋骨.R@@" @@肋骨.R@@
   >右<input type="checkbox" name="@@N肋骨.B@@" @@肋骨.B@@
   >両</td>
  <td class="plain"><input type="checkbox" name="@@N肋骨.2@@" @@肋骨.2@@
>2<input type="checkbox" name="@@N肋骨.3@@" @@肋骨.3@@
   >3</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N肋骨.T@@" value="@@肋骨.T@@"></td>

  <td class="plain">大腿</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N大腿.L@@" @@大腿.L@@
   >左<input type="checkbox" name="@@N大腿.R@@" @@大腿.R@@
   >右<input type="checkbox" name="@@N大腿.B@@" @@大腿.B@@
   >両</td>
  <td class="plain"><input type="checkbox" name="@@N大腿.2@@" @@大腿.2@@
   
   >2</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N大腿.T@@" value="@@大腿.T@@"></td>
 </tr>
<tr>
<td class="darker">胃バリウム</td>
  <td class="darker"></td>
  <td class="plain"><input type="checkbox" name="@@N胃バリウム.1@@" @@胃バリウム.1@@
  
   > </td>

  <td class="plain"><input type="text" class="dirtext"
      name="@@N胃バリウム.T@@" value="@@胃バリウム.T@@"></td>

<td class="darker">胃ガストロ</td>
  <td class="darker"></td>
  <td class="plain"><input type="checkbox" name="@@N胃ガストロ.1@@" @@胃ガストロ.1@@
  
   > </td>

  <td class="plain"><input type="text" class="dirtext"
      name="@@N胃ガストロ.T@@" value="@@胃ガストロ.T@@"></td>
 <td class="darker"></td>
</tr>



<tr>
<td class="darker">注腸バリウム</td>
  <td class="darker"></td>
  <td class="plain"><input type="checkbox" name="@@N注腸バリウム.1@@" @@注腸バリウム.1@@
  
   > </td>

  <td class="plain"><input type="text" class="dirtext"
      name="@@N注腸バリウム.T@@" value="@@注腸バリウム.T@@"></td>

<td class="darker">注腸ガストロ</td>
  <td class="darker"></td>
  <td class="plain"><input type="checkbox" name="@@N注腸ガストロ.1@@" @@注腸ガストロ.1@@
  
   > </td>

  <td class="plain"><input type="text" class="dirtext"
      name="@@N注腸ガストロ.T@@" value="@@注腸ガストロ.T@@"></td>
 <td class="darker"></td>
</tr>
<tr>
<td class="darker">小腸追跡</td>
  <td class="darker"></td>
  <td class="plain"><input type="checkbox" name="@@N小腸追跡.1@@" @@小腸追跡.1@@
  
   > </td>

  <td class="plain"><input type="text" class="dirtext"
      name="@@N小腸追跡.T@@" value="@@小腸追跡.T@@"></td>

<td class="darker">神経根ブロック</td>
  <td class="darker"></td>
  <td class="plain"><input type="checkbox" name="@@N神経根ブロック.1@@" @@神経根ブロック.1@@
  
   > </td>

  <td class="plain"><input type="text" class="dirtext"
      name="@@N神経根ブロック.T@@" value="@@神経根ブロック.T@@"></td>
 <td class="darker"></td>
</tr>

<tr>
<td class="darker">DIP</td>
  <td class="darker"></td>
  <td class="plain"><input type="checkbox" name="@@NDIP.1@@" @@DIP.1@@
  
   > </td>

  <td class="plain"><input type="text" class="dirtext"
      name="@@NDIP.T@@" value="@@DIP.T@@"></td>

<td class="darker">骨塩定量</td>
  <td class="darker"></td>
  <td class="plain"><input type="checkbox" name="@@骨塩定量.1@@" @@骨塩定量.1@@
  
   > </td>

  <td class="plain"><input type="text" class="dirtext"
      name="@@N骨塩定量.T@@" value="@@骨塩定量.T@@"></td>

</tr>







 <tr>
  <th class="heading" colspan="4">その他検査情報</th>

  <td class="darker">膝</td>
  <td class="darker"></td>
  <td class="darker"><input type="checkbox" name="@@N膝.L@@" @@膝.L@@
   >左<input type="checkbox" name="@@N膝.R@@" @@膝.R@@
   >右<input type="checkbox" name="@@N膝.B@@" @@膝.B@@
   >両</td>
  <td class="darker"><input type="checkbox" name="@@N膝.2@@" @@膝.2@@
   >２<input type="checkbox" name="@@N膝.3@@" @@膝.3@@
   >３</td>
  <td class="darker"><input type="text" class="dirtext"
      name="@@N膝.T@@" value="@@膝.T@@"></td>
 </tr>

 <tr>
  <td class="plain" colspan="4" rowspan="6"
  ><textarea class="plain miscinfo" cols="60" rows="8"
    name="@@Nその他@@">@@その他@@</textarea
  ></td>
  <td class="plain">下腿</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N下腿.L@@" @@下腿.L@@
   >左<input type="checkbox" name="@@N下腿.R@@" @@下腿.R@@
   >右<input type="checkbox" name="@@N下腿.B@@" @@下腿.B@@
   >両</td>
  <td class="plain"><input type="checkbox" name="@@N下腿.2@@" @@下腿.2@@
   
   >2</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N下腿.T@@" value="@@下腿.T@@"></td>
 </tr>

 <tr>
  <td class="darker">足関節</td>
  <td class="darker"></td>
  <td class="darker"><input type="checkbox" name="@@N足関節.L@@" @@足関節.L@@
   >左<input type="checkbox" name="@@N足関節.R@@" @@足関節.R@@
   >右<input type="checkbox" name="@@N足関節.B@@" @@足関節.B@@
   >両</td>
  <td class="darker"><input type="checkbox" name="@@N足関節.2@@" @@足関節.2@@
   >２<input type="checkbox" name="@@N足関節.3@@" @@足関節.3@@
   >3</td>
  <td class="darker"><input type="text" class="dirtext"
      name="@@N足関節.T@@" value="@@足関節.T@@"></td>
 </tr>

 <tr>
  <td class="plain">足背</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N足背.L@@" @@足背.L@@
   >左<input type="checkbox" name="@@N足背.R@@" @@足背.R@@
   >右<input type="checkbox" name="@@N足背.B@@" @@足背.B@@
   >両</td>
  <td class="plain"><input type="checkbox" name="@@N足背.2@@" @@足背.2@@
  
   >2</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N足背.T@@" value="@@足背.T@@"></td>
 </tr>

 <tr>
  <td class="plain">趾</td>
  <td class="plain"><input type="checkbox" name="@@N趾.D1@@" @@趾.D1@@
   >１<input type="checkbox" name="@@N趾.D2@@" @@趾.D2@@
   >２<input type="checkbox" name="@@N趾.D3@@" @@趾.D3@@
   >３<input type="checkbox" name="@@N趾.D4@@" @@趾.D4@@
   >４<input type="checkbox" name="@@N趾.D5@@" @@趾.D5@@
   >５</td>
  <td class="plain"><input type="checkbox" name="@@N趾.L@@" @@趾.L@@
   >左<input type="checkbox" name="@@N趾.R@@" @@趾.R@@
   >右<input type="checkbox" name="@@N趾.B@@" @@趾.B@@
   >両</td>
  <td class="plain"><input type="checkbox" name="@@N趾.2@@" @@趾.2@@
   >２</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N趾.T@@" value="@@趾.T@@"></td>
 </tr>

 <tr>
  <td class="plain">踵骨</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N踵骨.L@@" @@踵骨.L@@
   >左<input type="checkbox" name="@@N踵骨.R@@" @@踵骨.R@@
   >右<input type="checkbox" name="@@N踵骨.B@@" @@踵骨.B@@
   >両</td>
  <td class="plain"><input type="checkbox" name="@@N踵骨.1@@" @@踵骨.1@@
   >１<input type="checkbox" name="@@N踵骨.2@@" @@踵骨.2@@
   
   >2</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N踵骨.T@@" value="@@踵骨.T@@"></td>
 </tr>
<tr>
  <td class="plain">乳腺</td>
  <td class="plain"></td>
  <td class="plain"><input type="checkbox" name="@@N乳腺.L@@" @@乳腺.L@@
   >左<input type="checkbox" name="@@N乳腺.R@@" @@乳腺.R@@
   >右<input type="checkbox" name="@@N乳腺.B@@" @@乳腺.B@@
   >両</td>
  <td class="plain"><input type="checkbox" name="@@N乳腺.1@@" @@乳腺.1@@
   >１<input type="checkbox" name="@@N乳腺.2@@" @@乳腺.2@@
   
   >2</td>
  <td class="plain"><input type="text" class="dirtext"
      name="@@N乳腺.T@@" value="@@乳腺.T@@"></td>
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



