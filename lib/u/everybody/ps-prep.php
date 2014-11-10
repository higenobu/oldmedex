<?php // -*- mode: php; coding: euc-japan -*-

// To be used by ps-anew and pre-conference.
$_lib_u_everybody_ps_prep_cols = array
(

'���' => array(

"����̾", "ȯ����", "��¸��������ʻ��",
"����ȥ��������", "��ϥӥ�ơ��������",

'//����',

"��ư�㳲", "��ư�㳲������",
"�����ư������", "�����ư�����¥�����",
"�����㳲", "�����㳲������",
"���ȵ�ǽ������¾",

"��ư��", "��ư�١�û����ɸ",
"��ư�١����ץ�����",

'//���ȵ�ǽ����ư',

"���á�û����ɸ", "���á����ץ�����",
"��ݵ", "�㳲����ǧ", "����¾����",
"������û����ɸ", "���������ץ�����",

'//���á�����',

"�����¤", "�����¤����",
"ʡ�㵡��", "ʡ�㵡������", "�Ҳ��ݾ㥵���ӥ�", "�Ҳ��ݾ㥵���ӥ�����",
"����ݸ������ӥ�", "����ݸ������ӥ�����", "�Ķ���û����ɸ",
"�Ķ������ץ�����",

'//�Ķ�',

"ȯ�¤ˤ���²���Ѳ�", "�Ҳ�����",
"�򹯾�������ȯ��", "����Ū�����ȯ��",
"�軰�Ԥ�������ɾ��������",
"�ౡ��μ����",
"�ౡ��μ��������", "��²�������Ѳ�", "��²�������Ѳ�����",
"��²�������Ѳ�", "��²�������Ѳ�����", "��²�μҲ��ư�Ѳ�",
"��²�μҲ��ư�Ѳ�����",
"�軰�Ԥ�������û����ɸ",
"�軰�Ԥ����������ץ�����",

'//��²���軰��',

"1��������ɸ", "�ܿͤδ�˾",
"��²�δ�˾", "��ϥӥ�ơ������μ�������", "����ײ�",
"�ౡ����ɸ����������", "����ײ�",
"����ޤ����ౡ��μҲ񻲲äθ�����",

'//�ײ�',
),

'�Ǹ��' => array(),

'PT' => array(
"���֤�", "�����夬��", "�°�", "Ω���夬��", "Ω��",
"����ư�û����ɸ", "����ư����ץ�����",
"�ְػҰܾ衦û����ɸ", "�ְػҰܾ衦���ץ�����",
"��ư��û����ɸ", "��ư�����ץ�����",
),

'OT' => array(
"����ե�����û����ɸ", "����ե��������ץ�����",
"�ܾ衦û����ɸ", "�ܾ衦���ץ�����", 
"�ְػҡ�û����ɸ", "�ְػҡ����ץ�����",
),

'ST' => array(
"���ߥ�˥��������û����ɸ", "���ߥ�˥�������󡦥��ץ�����",
"�Ҳ�Ūǧ�Ρ�û����ɸ", "�Ҳ�Ūǧ�Ρ����ץ�����",
),

'MSW' => array(
"����", "����ȼ�Ż�����", "�кѾ���", "�Ҳ񻲲á����ơ���������",
";�˳�ư�����ơ���������", "�ౡ��", "����", "��������", "�Ż�����",
"�̶���ˡ", "���������", "�Ҳ��ư", "��̣",
"Ʊ���²", "��²�ط�",
"�������", "����", "�＼�μ���", "�ȥ����ͼ�", "��������β���",
"�ȼ���", "����",
),

);

function _lib_u_everybody_ps_prep_validate(&$it) {
	$nullify_cols = array(
		"ȯ����","ǯ��",
		"�缣��","���ô����","PT","OT","ST","�Ǹ��","SW",
		"����̾","��¸��������ʻ��",

		"�ȥ���_P",
		"�ȥ���_TP",
		"�ȥ���ư��_P",
		"�ȥ���ư��_TP",
		"�٥åɡ��ػҡ��ְػ�_P",
		"�٥åɡ��ػҡ��ְػ�_TP",
		"����_P",
		"����_TP",
		"����_P",
		"����_TP",
		"���ᡦ��Ⱦ��_P",
		"���ᡦ��Ⱦ��_TP",
		"���ᡦ��Ⱦ��_P",
		"���ᡦ��Ⱦ��_TP",
		"�Ҳ�Ū��ή_P",
		"�Ҳ�Ū��ή_TP",
		"�ְػ�_P",
		"�ְػ�_TP",
		"����_P",
		"����_TP",
		"����_P",
		"����_TP",
		"����_P",
		"����_TP",
		"��Ǣ����_P",
		"��Ǣ����_TP",
		"��������_P",
		"��������_TP",
		"ɽ��_P",
		"ɽ��_TP",
		"���_P",
		"���_TP",
		"������_P",
		"������_TP",
		"���奷��_P",
		"���奷��_TP",
		"����_P",
		"����_TP",

		);
	

	foreach ($nullify_cols as $c) {
		if ($it->data[$c] == "")
			$it->data[$c] = NULL;
	}
	$bad = 0;
	if ($st = mx_db_validate_date($it->data['����'])) {
		$it->err("(����): $st\n");
		$bad++;
	}
	foreach (array("ȯ����") as $c) {
		if (!is_null($it->data[$c])) {
			if ($st = mx_db_validate_date($it->data[$c])) {
				$it->err("($c): $st\n");
				$bad++;
			}
		}
	}
	if ($bad == 0)
		return 'ok';
}

?>