<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/ms2-compat.php';

$param = array('table_name' => 'Medis�����ʥޥ�����',
	       'pk_name' => array('����ֹ�'),
	       'accept_name' => array('��������','�±����Ѱ�����̾',				 '�±����ѥ쥻���󥳡���',),
	       'column' => array('�±����Ѱ�����̾',
				 '�±����ѥ쥻���󥳡���',
				 '�쥻�ץ��Ż����������ƥ������̾',
				 '�쥻�ץ��Ż����������ƥॳ���ɡʣ���',
				 '����ñ��',
				 '��������',
				 '����ñ�̿�',
				 '����ñ��ñ��',
				 '�������̿�',
				 '��������ñ��',
				 '������',
				 '���������ܰ����ʥ�����',
				 '����ֹ�',
				 '����ǯ����',
				 '�±�������'
				 ),
	       'stat' => array( # '������ʿ�' => 'COUNT(*)',
			       '�ǽ��ǡ���������' => 'MAX("����ǯ����")',
		       ),
);

master_select_table($param,
		    $__uiconfig_ms_qbe_enum_medicine,
		    $__uiconfig_ms_header_fields_medicine);
?>
