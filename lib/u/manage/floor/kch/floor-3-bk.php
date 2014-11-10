<?php
$__lib_u_manage_floor_ed_floors['3'] =
	array('caption_color' => '#6c6',
	      'floor_color' => '#fcf',
	      'label' => '３階',
	      'section' => '関西コミュニティ病院 - 看護・介護部 - 3階病棟');

function draw_floor_3() {
	function o($thing) {
		draw_floor_element($thing);
	}
?>
<table class="floor">
 <tr><td colspan="15"><? o('legend') ?></td></tr>
 <tr height=4>
  <td width=60px><img src="/images/strut60.png"></td>
  <td width=60px><img src="/images/strut60.png"></td>
  <td width=60px><img src="/images/strut60.png"></td>
  <td width=60px><img src="/images/strut60.png"></td>
  <td width=60px><img src="/images/strut60.png"></td>
  <td width=60px><img src="/images/strut60.png"></td>
  <td width=60px><img src="/images/strut60.png"></td>
  <td width=60px><img src="/images/strut60.png"></td>
  <td width=60px><img src="/images/strut60.png"></td>
  <td width=60px><img src="/images/strut60.png"></td>
  <td width=60px><img src="/images/strut60.png"></td>
  <td width=60px><img src="/images/strut60.png"></td>
  <td width=60px><img src="/images/strut60.png"></td>
  <td width=60px><img src="/images/strut60.png"></td>
  <td width=60px><img src="/images/strut60.png"></td>
 </tr>
 <tr height=18 style='height:13.5pt'>
  <td height=18 colspan="2" class="caption">退避</td>
  <td colspan="2" class="caption">314 (O<sub><small>2</small></sub>)</td>
  <td></td>
  <td height=18 colspan="1" rowspan="3" class="caption">浴室</td>
  <td height=18 colspan="1" rowspan="3" class="caption"></td>
  <td height=18 colspan="1" class="caption">リネン室</td>
  <td></td>
  <td colspan="2" class="caption">301  (O<sub><small>2</small></sub>)</td>
  <td colspan=4 rowspan=15 valign="top"><span id='tip-here'></span><? o('calendar') ?><br/><div><? o('soon') ?></div></td>
 </tr>
 <tr height=18 style='height:13.5pt'>
  <td rowspan="14" colspan="2" id="wait" class="wait">
   <div style='height: 300px; overflow: auto'><? o('wait') ?></div></td>
  <td colspan="2" class="bedcell"><? o('314.1') ?></td>
  <!-- Bath & Rest room here -->
  <td colspan="2"></td>
  <!-- Bath room & Rest room here -->
  <td rowspan="2" class="caption">toilet</td>
  <td></td>
  <td class="bedcell"><? o('301.1') ?></td>
  <td class="bedcell"><? o('301.2') ?></td>
 </tr>
 <tr height=18 style='height:13.5pt'>
  <td colspan="2" class="caption">313 (O<sub><small>2</small></sub>)</td>
  <td></td>
  <!-- Bath & Rest room here -->
  <td></td>
  <td class="bedcell"><? o('301.4') ?></td>
  <td class="bedcell"><? o('301.3') ?></td>
 </tr>
 <tr height=18 style='height:13.5pt'>
  <td colspan="2" class="bedcell"><? o('313.1') ?></td>
  <td></td>
  <td height=18 rowspan="2" class="caption"></td>
  <!-- stairs here -->
  <td height=18 colspan="2" rowspan="2" class="caption">階段</td>
  <td></td>
  <td colspan="2" class="caption">302 (O<sub><small>2</small></sub>)</td>
 </tr>
 <tr height=18 style='height:13.5pt'>
  <td colspan="2" class="caption">312 (O<sub><small>2</small></sub>)</td>
  <td></td>
  <!-- stairs here -->
  <td></td>
  <td class="bedcell"><? o('302.1') ?></td>
  <td class="bedcell"><? o('302.2') ?></td>
 </tr>
 <tr height=18 style='height:13.5pt'>
  <td class="bedcell"><? o('312.3') ?></td>
  <td class="bedcell"><? o('312.4') ?></td>
  <!-- Passage here -->
  <td colspan="5"></td>
  <td class="bedcell"><? o('302.4') ?></td>
  <td class="bedcell"><? o('302.3') ?></td>
 </tr>
 <tr height=18 style='height:13.5pt'>
  <td class="bedcell"><? o('312.2') ?></td>
  <td class="bedcell"><? o('312.1') ?></td>
  <!-- Passage here -->
  <td colspan="5"></td>
  <td colspan="2" class="caption">303 (O<sub><small>2</small></sub>)</td>
 </tr>
 <tr height=18 style='height:13.5pt'>
  <td height=18 colspan="2" rowspan="4" class="caption">デイルーム</td>
  <td></td>
  <!-- Elevatorhere -->
  <td height=18 rowspan="2" class="caption"></td>
  <td height=18 rowspan="2" class="caption">エレベータ</td>
  <td height=18 rowspan="2" class="caption">エレベータ</td>
  <td></td>
  <td class="bedcell"><? o('303.1') ?></td>
  <td class="bedcell"><? o('303.2') ?></td>
 </tr>
 <tr height=18 style='height:13.5pt'>
  <!-- Dining room here -->
  <td></td>
  <!-- Elevator & Rest room here -->
  <td></td>
  <td class="bedcell"><? o('303.4') ?></td>
  <td class="bedcell"><? o('303.3') ?></td>
 </tr>
 <tr height=18 style='height:13.5pt'>
  <!-- Dining room here -->
  <td></td>
  <!-- Staff room here -->
  <td height=18 colspan="3" rowspan="2" class="caption">スタッフステーション</td>
  <td></td>
  <td colspan="2" class="caption">304 (O<sub><small>2</small></sub>)</td>
 </tr>
 <tr height=18 style='height:13.5pt'>
  <!-- Dining room here -->
  <td></td>
  <!-- Staff room here -->
  <td></td>
  <td class="bedcell"><? o('304.1') ?></td>
  <td class="bedcell"><? o('304.2') ?></td>
 </tr>
 <tr height=18 style='height:13.5pt'>
  <!-- Elevator here -->
  <td height=18 rowspan="2" class="caption">エレベータ</td>
  <td colspan="2"></td>
  <!-- room here -->
  <td height=18 colspan="2" rowspan="2" class="caption">カンファレンスルーム</td>
  <td height=18 rowspan="2" class="caption"></td>
  <td></td>
  <td class="bedcell"><? o('304.4') ?></td>
  <td class="bedcell"><? o('304.3') ?></td>
 </tr>
 <tr height=18 style='height:13.5pt'>
  <!-- Elevator here -->
  <td colspan="2"></td>
  <!-- room here -->
  <td></td>
  <td colspan="2" class="caption">305 (O<sub><small>2</small></sub>)</td>
 </tr>
 <tr height=18 style='height:13.5pt'>
  <!-- Rest room here -->
  <td height=18 rowspan="2" class="caption">トイレ</td>
  <td height=18 rowspan="2" class="caption">トイレ</td>
  <td></td>
  <!-- Rest room here -->
  <td height=18 rowspan="2" class="caption"></td>
  <td height=18 rowspan="2" class="caption">トイレ</td>
  <td height=18 rowspan="2" class="caption"></td>
  <td></td>
  <td class="bedcell"><? o('305.1') ?></td>
  <td class="bedcell"><? o('305.2') ?></td>
  <td></td>
 </tr>
 <tr height=18 style='height:13.5pt'>
  <!-- Rest room here -->
  <td></td>
  <!-- Rest room here -->
  <td></td>
  <td class="bedcell"><? o('305.4') ?></td>
  <td class="bedcell"><? o('305.3') ?></td>
 </tr>
 <tr height=18 style='height:13.5pt'>
  <!-- Passage here -->
  <td colspan="15"></td>
 </tr>
 <tr height=18 style='height:13.5pt'>
  <td height=18 class="caption" colspan="2">311</td>
  <td height=18 class="caption" colspan="2">310</td>
  <td height=18 class="caption" colspan="2">309</td>
  <td height=18 class="caption" colspan="2">308</td>
  <td height=18 class="caption" colspan="2">307</td>
  <td height=18 class="caption">306</td>
 </tr>
 <tr height=18 style='height:13.5pt'>
  <td class="bedcell"><? o('311.4') ?></td>
  <td class="bedcell"><? o('311.1') ?></td>
  <td class="bedcell"><? o('310.4') ?></td>
  <td class="bedcell"><? o('310.1') ?></td>
  <td class="bedcell"><? o('309.4') ?></td>
  <td class="bedcell"><? o('309.1') ?></td>
  <td class="bedcell"><? o('308.4') ?></td>
  <td class="bedcell"><? o('308.1') ?></td>
  <td class="bedcell"><? o('307.4') ?></td>
  <td class="bedcell"><? o('307.1') ?></td>
  <td class="bedcell"><? o('306.1') ?></td>
 </tr>
 <tr height=18 style='height:13.5pt'>
  <td class="bedcell"><? o('311.3') ?></td>
  <td class="bedcell"><? o('311.2') ?></td>
  <td class="bedcell"><? o('310.3') ?></td>
  <td class="bedcell"><? o('310.2') ?></td>
  <td class="bedcell"><? o('309.3') ?></td>
  <td class="bedcell"><? o('309.2') ?></td>
  <td class="bedcell"><? o('308.3') ?></td>
  <td class="bedcell"><? o('308.2') ?></td>
  <td class="bedcell"><? o('307.3') ?></td>
  <td class="bedcell"><? o('307.2') ?></td>
  <td class="bedcell"><? o('306.2') ?></td>
 </tr>
</table>
<?php
}
?>
