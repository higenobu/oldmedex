<?php
function o($name) {
	global $__this_output_data;
	$d = $__this_output_data[$name];
	if ($d == '') {
		print '&nbsp;';
	}
	else {
		print htmlspecialchars($d);
	}
}
function draw_plansheet($data) {
	global $_mx_resource_dir;
	global $__this_output_data;
	$__this_output_data = $data;
?>
<html xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv=Content-Type content="text/html; charset=euc-jp">
<meta name=ProgId content=Excel.Sheet>
<script language="JavaScript"
 src="/<? print $_mx_resource_dir ?>/mx.js"></script>
<title>��ϥӥ�ơ���������»ܷײ��</title>
<style type="text/css">
<!--
body,td,th {
	font-family: �ͣ� �Х����å�, Osaka, �ҥ饮�γѥ� Pro W3;
	font-size: 8pt;
}
.style1 {font-size: 14pt}
.news {border: 0.5px solid black}
.north {border-top: 0.5px solid black}
.south {border-bottom: 0.5px solid black}
.east {border-right: 0.5px solid black}
.west {border-left: 0.5px solid black}
-->
</style></head>
<body bgcolor="#FFFFFF">

  <table border=0 cellpadding=0 cellspacing=0 width=720pt>
    <tbody>
    <tr>
      <td height="37" class=news colspan=20><p class="style1"
>��ϥӥ�ơ���������»ܷײ��</p></td>
      <td colspan=3 class=news>ɾ���»���</td>
      <td colspan=5 class=news><?php o("����") ?></td>
    </tr>
    <tr>
      <td colspan=2 class=news>��̾</td>
      <td colspan=5 class=news><?php o('��̾') ?></td>
      <td class=news>(<?php o('����') ?>)</td>
      <td colspan=2 class=news>��ǯ����</td>
      <td colspan=5 class=news><?php o('��ǯ����') ?></td>
      <td colspan=2 class=news><?php o('ǯ��') ?></td>
      <td colspan=2 class=news>��</td>
      <td colspan=4 class=news>������</td>
      <td colspan=5 class=news><?php o('������') ?></td>
    </tr>
    <tr>
      <td colspan=2 class=news>�缣��</td>
      <td colspan=2 class=news><?php o('�缣��̾') ?></td>
      <td class=news>��ϰ�</td>
      <td colspan=3 class=news><?php o('���ô����̾') ?></td>
      <td class=news>PT</td>
      <td colspan=3 class=news><?php o('PT̾') ?></td>
      <td class=news>OT</td>
      <td colspan=3 class=news><?php o('OT̾') ?></td>
      <td class=news>ST</td>
      <td colspan=3 class=news><?php o('ST̾') ?></td>
      <td colspan="2" class=news>NS</td>
      <td colspan=4 class=news><?php o('�Ǹ��̾') ?></td>
      <td class=news>SW</td>
      <td class=news><?php o('SW̾') ?></td>
    </tr>
    <tr>
      <td colspan=9 class=news>����̾</td>
      <td colspan=11 class=news>ʻ¸��������ʻ��</td>
      <td colspan=8 class=news>��ϥӥ�ơ��������</td>
    </tr>
    <tr>
      <td colspan=9 rowspan=3 class=news><?php o('����̾') ?></td>
      <td colspan=11 class=news><?php o('��¸��������ʻ��') ?></td>
      <td colspan=8 rowspan=4 class=news
	><?php o('��ϥӥ�ơ��������') ?></td>
    </tr>
    <tr>
      <td colspan=11 class=news>����ȥ������</td>
    </tr>
    <tr>
      <td colspan=11 rowspan=2 class=news><?php o('����ȥ������') ?></td>
    </tr>
    <tr>
      <td colspan=2 class=news>ȯ����</td>
      <td colspan=7 class=news><?php o('ȯ����') ?></td>
    </tr>
    <tr>
      <td colspan=4 class=news>�������輫Ω��</td>
      <td colspan=10 class=news><?php o('�������輫Ω��') ?></td>
      <td colspan=10 class=news>������Ϸ�ͤ��������輫Ω��Ƚ����</td>
      <td colspan=4 class=news
	><?php o('������Ϸ�ͤ��������輫Ω��Ƚ����') ?></td>
    </tr>
    </tbody>
  </table>

  <br />

  <table border=0 cellpadding=0 cellspacing=0 width=720pt>
    <tr>
      <td colspan=28 class=news
	>ɾ�����ܡ�����(�����ʡ��ˤθ��˶���Ū���Ƥ���</td>
    </tr>
    <tr>
      <td class=news rowspan=15>��<br>��<br>��<br>ǽ<br><br>��<br>¤</td>
      <td class=news colspan=4>�ռ��㳲����</td>
      <td class=news><?php o('�ռ��㳲') ?></td>
      <td class=news colspan=7><?php o('�ռ��㳲������') ?></td>
      <td class=news colspan=4>�����㳲����</td>
      <td class=news colspan=2><?php o('�����㳲') ?></td>
      <td class=news colspan=9><?php o('�����㳲������') ?></td>
    </tr>
    <tr>
      <td class=news colspan=4>�������㳲��</td>
      <td class=news><?php o('����') ?></td>
      <td class=news colspan=7><?php o('���򥳥���') ?></td>
      <td class=news colspan=4>�����ư�����¡�</td>
      <td class=news colspan=2><?php o('�����ư������') ?></td>
      <td class=news colspan=9><?php o('�����ư�����¥�����') ?></td>
    </tr>
    <tr>
      <td class=news colspan=4>�����Ͼ㳲��</td>
      <td class=news><?php o('�����㳲') ?></td>
      <td class=news colspan=7><?php o('�����㳲������') ?></td>
      <td class=news colspan=4>�����㲼��</td>
      <td class=news colspan=2><?php o('�����㲼') ?></td>
      <td class=news colspan=9><?php o('�����㲼������') ?></td>
    </tr>
    <tr>
      <td class=news colspan=4>��ư�㳲��</td>
      <td class=news><?php o('��ư�㳲') ?></td>
      <td class=news colspan=7><?php o('��ư�㳲������') ?></td>
      <td class=news colspan=4>���ϡ�</td>
      <td class=news colspan=2><?php o('����') ?></td>
      <td class=news colspan=9><?php o('���ϥ�����') ?></td>
    </tr>
    <tr>
      <td class=news colspan=4>ɽ�ߴ��о㳲��</td>
      <td class=news><?php o('ɽ�ߴ��о㳲') ?></td>
      <td class=news colspan=7><?php o('ɽ�ߴ��о㳲������') ?></td>
      <td class=news colspan=4>�ˤߡ�</td>
      <td class=news colspan=2><?php o('�ˤ�') ?></td>
      <td class=news colspan=9><?php o('�ˤߥ�����') ?></td>
    </tr>
    <tr>
      <td class=news colspan=4>�������о㳲��</td>
      <td class=news><?php o('�������о㳲') ?></td>
      <td class=news colspan=7><?php o('�������о㳲������') ?></td>
      <td class=news colspan=4>Ⱦ¦�����̵�롧</td>
      <td class=news colspan=2><?php o('Ⱦ¦�����̵��') ?></td>
      <td class=news colspan=9><?php o('Ⱦ¦�����̵�륳����') ?></td>
    </tr>
    <tr>
      <td class=news colspan=4>�ݿ��벼�㳲��</td>
      <td class=news><?php o('�ݿ���ǽ�㳲') ?></td>
      <td class=news colspan=7><?php o('�ݿ���ǽ�㳲������') ?></td>
      <td class=news colspan=4>����Ͼ㳲��</td>
      <td class=news colspan=2><?php o('��վ㳲') ?></td>
      <td class=news colspan=9><?php o('��վ㳲������') ?></td>
    </tr>
    <tr>
      <td class=news colspan=4>��Ǣ��ǽ�㳲��</td>
      <td class=news><?php o('��Ǣ��ǽ�㳲') ?></td>
      <td class=news colspan=7><?php o('��Ǣ��ǽ�㳲������') ?></td>
      <td class=news colspan=4>�����㳲��</td>
      <td class=news colspan=2><?php o('�����㳲') ?></td>
      <td class=news colspan=9><?php o('�����㳲������') ?></td>
    </tr>
    <tr>
      <td class=news colspan=4>���ص�ǽ�㳲��</td>
      <td class=news><?php o('���ص�ǽ�㳲') ?></td>
      <td class=news colspan=7><?php o('���ص�ǽ�㳲������') ?></td>
      <td class=news colspan=3 rowspan=2>����¾��</td>
      <td class=news colspan=12 rowspan=2><?php o('���ȵ�ǽ������¾') ?></td>
    </tr>
    <tr>
      <td class=news colspan=4>�Ƶۡ��۴Ĵ�㳲��</td>
      <td class=news><?php o('�Ƶ۽۴Ĵﵡǽ�㳲') ?></td>
      <td class=news colspan=7><?php o('�Ƶ۽۴Ĵﵡǽ�㳲������') ?></td>
    </tr>
    <tr>
      <td rowspan=5 class=news>��<br>��<br>ư<br>��</td>
      <td class=news colspan=3>���֤ꡧ��</td>
      <td class=news colspan=8><?php o('���֤�') ?></td>
      <td class=news colspan=6>û����ɸ</td>
      <td class=news colspan=9>����Ū���ץ���</td>
    </tr>
    <tr>
      <td class=news colspan=3>�����夬�ꡧ</td>
      <td class=news colspan=8><?php o('�����夬��') ?></td>
      <td class=news colspan=6 rowspan=4><?php o('����ư�û����ɸ') ?></td>
      <td class=news colspan=9 rowspan=4><?php o('����ư����ץ���') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>�°̡�</td>
      <td class=news colspan=8><?php o('�°�') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>Ω���夬�ꡧ</td>
      <td class=news colspan=8><?php o('Ω���夬��') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>Ω�̡���</td>
      <td class=news colspan=8><?php o('Ω��') ?></td>
    </tr>
    <tr>
      <td class=news rowspan=24>��<br>ư</td>
      <td class=news colspan=12>��ư�١�(�����٤����¤Ȥ�����ͳ���ꥹ���ˤĤ��ơ�</td>
      <td class=news colspan=6 rowspan=2><?php o('��ư�١�û����ɸ') ?></td>
      <td class=news colspan=9 rowspan=2><?php o('��ư�١����ץ���') ?></td>
    </tr>
    <tr>
      <td class=news colspan=12><?php o('��ư��') ?></td>
    </tr>
    <tr>
      <td class=news colspan=5>��������ư��¹Ծ���</td>
      <td class=news colspan=2>�ƣɣ�</td>
      <td class=news colspan=5>�����Ѷ�(������˲��</td>
      <td class=news colspan=6>û����ɸ</td>
      <td class=news colspan=9>����Ū���ץ���</td>
    </tr>
    <tr>
      <td class=news colspan=2 rowspan=6>����ե���</td>
      <td class=news colspan=3>����</td>
      <td class=news><?php o('����_P') ?></td>
      <td class=news><?php o('����_TP') ?></td>
      <td class=news colspan=5><?php o('����_C') ?></td>
      <td class=news colspan=6 rowspan=6><?php o('����ե�����û����ɸ') ?></td>
      <td class=news colspan=9 rowspan=6><?php o('����ե��������ץ���') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>����</td>
      <td class=news><?php o('����_P') ?></td>
      <td class=news><?php o('����_TP') ?></td>
      <td class=news colspan=5><?php o('����_C') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>����</td>
      <td class=news><?php o('����_P') ?></td>
      <td class=news><?php o('����_TP') ?></td>
      <td class=news colspan=5><?php o('����_C') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>�����</td>
      <td class=news><?php o('���ᡦ��Ⱦ��_P') ?></td>
      <td class=news><?php o('���ᡦ��Ⱦ��_TP') ?></td>
      <td class=news colspan=5><?php o('���ᡦ��Ⱦ��_C') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>���᲼</td>
      <td class=news><?php o('���ᡦ��Ⱦ��_P') ?></td>
      <td class=news><?php o('���ᡦ��Ⱦ��_TP') ?></td>
      <td class=news colspan=5><?php o('���ᡦ��Ⱦ��_C') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>�ȥ���ư��</td>
      <td class=news><?php o('�ȥ���ư��_P') ?></td>
      <td class=news><?php o('�ȥ���ư��_TP') ?></td>
      <td class=news colspan=5><?php o('�ȥ���ư��_C') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2 rowspan=2>����</td>
      <td class=news colspan=3>��Ǣ����</td>
      <td class=news><?php o('��Ǣ����_P') ?></td>
      <td class=news><?php o('��Ǣ����_TP') ?></td>
      <td class=news colspan=5><?php o('��Ǣ����_C') ?></td>
      <td class=news colspan=6 rowspan=2><?php o('������û����ɸ') ?></td>
      <td class=news colspan=9 rowspan=2><?php o('���������ץ���') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>���ش���</td>
      <td class=news><?php o('��������_P') ?></td>
      <td class=news><?php o('��������_TP') ?></td>
      <td class=news colspan=5><?php o('��������_C') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2 rowspan=3>�ܾ�</td>
      <td class=news colspan=3>�ְػҰܾ�</td>
      <td class=news><?php o('�٥åɡ��ػҡ��ְػ�_P') ?></td>
      <td class=news><?php o('�٥åɡ��ػҡ��ְػ�_TP') ?></td>
      <td class=news colspan=5><?php o('�٥åɡ��ػҡ��ְػ�_C') ?></td>
      <td class=news colspan=6><?php o('�ְػҰܾ衦û����ɸ') ?></td>
      <td class=news colspan=9><?php o('�ְػҰܾ衦���ץ���') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>�ȥ���ܾ�</td>
      <td class=news><?php o('�ȥ���_P') ?></td>
      <td class=news><?php o('�ȥ���_TP') ?></td>
      <td class=news colspan=5><?php o('�ȥ���_C') ?></td>
      <td class=news colspan=6 rowspan=2><?php o('�ܾ衦û����ɸ') ?></td>
      <td class=news colspan=9 rowspan=2><?php o('�ܾ衦���ץ���') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>����ܾ�</td>
      <td class=news><?php o('���奷��_P') ?></td>
      <td class=news><?php o('���奷��_TP') ?></td>
      <td class=news colspan=5><?php o('���奷��_C') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2 rowspan=3>��ư</td>
      <td class=news colspan=3>�ְػ�</td>
      <td class=news><?php o('�ְػ�_P') ?></td>
      <td class=news><?php o('�ְػ�_TP') ?></td>
      <td class=news colspan=5><?php o('�ְػ�_C') ?></td>
      <td class=news colspan=6><?php o('�ְػҡ�û����ɸ') ?></td>
      <td class=news colspan=9><?php o('�ְػҡ����ץ���') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>���</td>
      <td class=news><?php o('���_P') ?></td>
      <td class=news><?php o('���_TP') ?></td>
      <td class=news colspan=5><?php o('���_C') ?></td>
      <td class=news colspan=6 rowspan=2><?php o('��ư��û����ɸ') ?></td>
      <td class=news colspan=9 rowspan=2><?php o('��ư�����ץ���') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>����</td>
      <td class=news><?php o('����_P') ?></td>
      <td class=news><?php o('����_TP') ?></td>
      <td class=news colspan=5><?php o('����_C') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2 rowspan=2>���ߥ�˥��������</td>
      <td class=news colspan=3>����</td>
      <td class=news><?php o('����_P') ?></td>
      <td class=news><?php o('����_TP') ?></td>
      <td class=news colspan=5><?php o('����_C') ?></td>
      <td class=news colspan=6 rowspan=2><?php o('���ߥ�˥��������û����ɸ') ?></td>
      <td class=news colspan=9 rowspan=2><?php o('���ߥ�˥�������󡦥��ץ���') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>ɽ��</td>
      <td class=news><?php o('ɽ��_P') ?></td>
      <td class=news><?php o('ɽ��_TP') ?></td>
      <td class=news colspan=5><?php o('ɽ��_C') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2 rowspan=3>�Ҳ�Ūǧ��</td>
      <td class=news colspan=3>�Ҳ�Ū��ή</td>
      <td class=news><?php o('�Ҳ�Ū��ή_P') ?></td>
      <td class=news><?php o('�Ҳ�Ū��ή_TP') ?></td>
      <td class=news colspan=5><?php o('�Ҳ�Ū��ή_C') ?></td>
      <td class=news colspan=6 rowspan=3><?php o('�Ҳ�Ūǧ�Ρ�û����ɸ') ?></td>
      <td class=news colspan=9 rowspan=3><?php o('�Ҳ�Ūǧ�Ρ����ץ���') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>������</td>
      <td class=news><?php o('������_P') ?></td>
      <td class=news><?php o('������_TP') ?></td>
      <td class=news colspan=5><?php o('������_C') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>����</td>
      <td class=news><?php o('����_P') ?></td>
      <td class=news><?php o('����_TP') ?></td>
      <td class=news colspan=5><?php o('����_C') ?></td>
    </tr>
    <tr>
      <td class=news colspan=5>���</td>
      <td class=news><?php o('FIM_NURSE_SUM') ?></td>
      <td class=news><?php o('FIM_THERAPIST_SUM') ?></td>
      <td>�ʱ�ư</td>
      <td><?php o('FIM_NURSE_SUM_PHY') ?></td>
      <td>ǧ��</td>
      <td><?php o('FIM_NURSE_SUM_COG') ?></td>
      <td>��</td>
      <td class=news colspan=3>��ư����</td>
      <td class=news colspan=12><?php o('��ư����') ?></td>
    </tr>
    <tr>
      <td class=news colspan=27>7.��Ω��6.������Ω��5.�ƻ롦������4.�Ǿ������3.�����ٲ����2����������1.�����</td>
    </tr>

  </table>

  <br /><hr /><br />

  <table border=0 cellpadding=0 cellspacing=0 width=720pt>

    <tr>
      <td class=news>&nbsp;</td>
      <td class=news colspan=8>ɾ��</td>
      <td class=news colspan=11>û����ɸ</td>
      <td class=news colspan=8>����Ū���ץ���</td>
    </tr>
    <tr>
      <td class=news rowspan=10>��<br>��</td>
      <td class=east colspan=8>����</td>
      <td class=news colspan=3>�ౡ��</td>
      <td class=news colspan=8><?php o('�ౡ��') ?></td>
      <td class=news colspan=8 rowspan=10><?php o('���á����ץ���') ?></td>
    </tr>
    <tr>
      <td class="south east" colspan=8><?php o('����') ?></td>
      <td class=news colspan=3>����</td>
      <td class=news colspan=8><?php o('����') ?></td>
    </tr>
    <tr>
      <td class=east colspan=8>����ȼ�Ż�����</td>
      <td class=news colspan=3>��������</td>
      <td class=news colspan=8><?php o('��������') ?></td>
    </tr>
    <tr>
      <td class="south east" colspan=8><?php o('����ȼ�Ż�����') ?></td>
      <td class=news colspan=3>�Ż�����</td>
      <td class=news colspan=8><?php o('�Ż�����') ?></td>
    </tr>
    <tr>
      <td class=east colspan=8>�кѾ���</td>
      <td class=news colspan=3>�̶���ˡ</td>
      <td class=news colspan=8><?php o('�̶���ˡ') ?></td>
    </tr>
    <tr>
      <td class="south east" colspan=8><?php o('�кѾ���') ?></td>
      <td colspan=3>���������</td>
      <td colspan=8><?php o('���������') ?></td>
    </tr>
    <tr>
      <td class=east colspan=8>�Ҳ񻲲�(���ơ���������</td>
      <td class=news colspan=3>�Ҳ��ư</td>
      <td class=news colspan=8><?php o('�Ҳ��ư') ?></td>
    </tr>
    <tr>
      <td class="south east" colspan=8><?php o('�Ҳ񻲲á����ơ���������') ?></td>
      <td class=news colspan=3>��̣</td>
      <td class=news colspan=8><?php o('��̣') ?></td>
    </tr>
    <tr>
      <td class=east colspan=8>;�˳�ư(���ơ���������</td>
      <td class=news colspan=11 rowspan=2><?php o('���á�û����ɸ') ?></td>
    </tr>
    <tr>
      <td class="south east" colspan=8><?php o(';�˳�ư�����ơ���������') ?></td>
    </tr>
    
    <tr>
      <td class=news rowspan=4>��<br>��</td>
      <td class=news colspan=2>��ݵ</td>
      <td colspan=6 class=news><?php o('��ݵ') ?></td>
      <td colspan=11 rowspan=4 class=news><?php o('������û����ɸ') ?></td>
      <td class=news colspan=8 rowspan=4><?php o('���������ץ���') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2>�㳲����ǧ</td>
      <td class=news colspan=6><?php o('�㳲����ǧ') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2>����¾</td>
      <td class=east colspan=6><?php o('����¾����') ?></td>
    </tr>
    <tr>
      <td class="south east" colspan=8>��</td>
    </tr>
    <tr>
      <td class=news rowspan=14>��<br>��</td>
      <td class=news colspan=2>Ʊ���²</td>
      <td class=east colspan=6></td>
      <td class=news colspan=3>�����¤</td>
      <td class=east colspan=8
	><?php o('�����¤') ?><?php o('�����¤����') ?></td>
      <td class=news colspan=8 rowspan=14><?php o('�Ķ������ץ���') ?></td>
    </tr>
    <tr>
      <td class="south east" colspan=8><?php o('Ʊ���²') ?></td>
      <td class=news colspan=3>ʡ�㵡��</td>
      <td class=news colspan=8
	><?php o('ʡ�㵡��') ?><?php o('ʡ�㵡������') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2>��²�ط�</td>
      <td class=news colspan=17><?php o('��²�ط�') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2>�������</td>
      <td class=east colspan=6>��</td>
      <td class=news colspan=3>�Ҳ��ݾ㥵���ӥ�</td>
      <td class=east colspan=8>��</td>
    </tr>
    <tr>
      <td class="south east" colspan=8 rowspan=3><?php o('�������') ?></td>
      <td class="south east" colspan=11
	><?php o('�Ҳ��ݾ㥵���ӥ�') ?><?php o('�Ҳ��ݾ㥵���ӥ�����') ?></td>
    </tr>
    
    <tr>
      <td class=news colspan=3>����ݸ������ӥ�</td>
      <td class=east colspan=8>��</td>
    </tr>
    <tr>
      <td class="south east" colspan=11
	><?php o('����ݸ������ӥ�') ?><?php o('����ݸ������ӥ�����') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2>����</td>
      <td class=news colspan=6><?php o('����') ?></td>
      <td class=news colspan=3>�õ�����</td>
      <td class=east colspan=8>��</td>
    </tr>
    <tr>
      <td class=news colspan=2>�＼�μ���</td>
      <td class=news colspan=6><?php o('�＼�μ���') ?></td>
      <td class=news colspan=11 rowspan=6><?php o('�Ķ���û����ɸ') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2>�ȥ����ͼ�</td>
      <td class=news colspan=6><?php o('�ȥ����ͼ�') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2>��������β���</td>
      <td class=news colspan=6><?php o('��������β���') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2>�ȼ���</td>
      <td class=east colspan=6 rowspan=2><?php o('�ȼ���') ?></td>
    </tr>
    <tr>
      <td class=south colspan="2">��</td>
    </tr>
    <tr>
      <td class=news colspan=2>����</td>
      <td class=news colspan=6><?php o('����') ?></td>
    </tr>
    <tr>
      <td class=news rowspan=6>��<br>��<br>��<br>��<br>��<br>��</td>
      <td class=news colspan=2>ȯ�¤ˤ���²���Ѳ�</td>
      <td class=news colspan=6><?php o('ȯ�¤ˤ���²���Ѳ�') ?></td>
      <td class=news colspan=3>�ౡ��μ����</td>
      <td class=news colspan=8
	><?php o('�ౡ��μ����') ?><?php o('�ౡ��μ��������') ?></td>
      <td class=news colspan=8 rowspan=6
	><?php o('�軰�Ԥ����������ץ���') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2>�Ҳ�����</td>
      <td class=news colspan=6><?php o('�Ҳ�����') ?></td>
      <td class=news colspan=3>��²�������Ѳ�</td>
      <td class=news colspan=8
	><?php o('��²�������Ѳ�') ?><?php o('��²�������Ѳ�����') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2>�򹯾�������ȯ��</td>
      <td class=news colspan=6><?php o('�򹯾�������ȯ��') ?></td>
      <td class=news colspan=3>��²�������Ѳ�</td>
      <td class=news colspan=8
	><?php o('��²�������Ѳ�') ?><?php o('��²�������Ѳ�����') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2>����Ū�����ȯ��</td>
      <td class=news colspan=6><?php o('����Ū�����ȯ��') ?></td>
      <td class=news colspan=3>��²�μҲ��ư�Ѳ�</td>
      <td class=news colspan=8
	><?php o('��²�μҲ��ư�Ѳ�') ?><?php o('��²�μҲ��ư�Ѳ�����') ?></td>
    </tr>
    <tr>
      <td class=east colspan=8>����</td>
      <td class=east colspan=11>����</td>
    </tr>
    <tr>
      <td colspan=8 class="south east"
	><?php o('�軰�Ԥ�������ɾ��������') ?></td>
      <td class="south east" colspan=11
><?php o('�軰�Ԥ�������û����ɸ') ?></td>
    </tr>

    <tr>
      <td class="east west" colspan=12>1��������ɸ</td>
      <td class="east west" colspan=16>�ܿͤδ�˾</td>
    </tr>
    <tr>
      <td class=news colspan=12 rowspan=3><?php o('1��������ɸ') ?></td>
      <td class="south east" colspan=16><?php o('�ܿͤδ�˾') ?></td>
    </tr>
    <tr>
      <td class=east colspan=16>��²�δ�˾</td>
    </tr>
    <tr>
      <td class="south east" colspan=16><?php o('��²�δ�˾') ?></td>
    </tr>
    <tr>
      <td class="east west" colspan=12>��ϥӥ�ơ������������</td>
      <td class="east west" colspan=16>����ײ�</td>
    </tr>
    <tr>
      <td class="west south east" colspan=12
	><?php o('��ϥӥ�ơ������μ�������') ?></td>
      <td class="west south east" colspan=16
	><?php o('����ײ�') ?></td>
    </tr>
    <tr>
      <td class="east west" colspan=28>�ౡ������ɸ�ȸ����߻���</td>
    </tr>
    <tr>
      <td class="west south east" colspan=28
	><?php o('�ౡ����ɸ����������') ?></td>
    </tr>
    <tr>
      <td class="east west" colspan=28
	>����ޤ����ౡ��Υ�ϥӥ�ơ������ײ�ʤ�</td>
    </tr>
    <tr>
      <td class="west south east" colspan=28><?php o('����ײ�') ?></td>
    </tr>
    <tr>
      <td class="east west" colspan=28>����ޤ����ౡ��μҲ񻲲äθ�����</td>
    </tr>
    <tr>
      <td class="west south east" colspan=28
	><?php o('����ޤ����ౡ��μҲ񻲲äθ�����') ?></td>
    </tr>
    <tr>
      <td class=west colspan=12>��</td>
      <td colspan=8>�ܿ�/��²�ؤ�����</td>
      <td class=east colspan=8>��������ǯ���������</td>
    </tr>
    <tr>
      <td class="east west" colspan=28>��</td>
    </tr>
    <tr>
      <td class="south west" colspan=12
	>������������͡��ܿ͡���²������̾��</td>
      <td class="south east" colspan=16>�����Խ�̾</td>
    </tr>
  </table>

</body>
<script language="javascript" type="text/javascript">
<!--
printThisWindow(window);
-->
</script>
</html>
<?php
}
?>
