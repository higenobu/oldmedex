<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/ms2-compat.php';

$param = array('table_name' => '���θ����ޥ�����',
	       'pk_name' => array('���ܭ�', '�ޭ�'),
	       'accept_name' => '��������',
	       'column' => array('����̾ (���ܸ�)', 'ñ��̾',
				 '�����ﲼ��', '��������',
				 '��������ʸ��',
				 '�����ﲼ��', '��������',
			         '��������ʸ��'));

$param1 = array('table_name' => 'test_master',
	       'pk_name' => array('test_lab', 'LaboSystemCode'),
	       'accept_name' => '��������',
	       'column' => array(
			'test_lab',
			'LaboSystemCode',
			'ReceiptSystemCode',
			'Name',
			'Search',
			'Unit',
			'Container',
			'MaleNormalText',
			'MaleNormalBottom',
			'MaleNormalTop',
			'FemaleNormalText',
			'FemaleNormalBottom',
			'FemaleNormalTop',
			'created_on'
		       ),
		'lcols' => array(
			array('Column' => 'test_lab', 'Label' => '�������',
			      'Draw' => 'enum',
			      'Enum' => array(
				      '' => '',
				      '1' => 'CLIP',
				      '2' => '2����',
				      '3' => '3����',
				      ),
				),
			array('Column' => 'LaboSystemCode', 'Label' => '����������'),
			array('Column' => 'ReceiptSystemCode', 'Label' => '���������'),
			array('Column' => 'Name', 'Label' => '����̾'),
			array('Column' => 'Search', 'Label' => '����̾'),
			array('Column' => 'Unit', 'Label' => 'ñ��'),
			array('Column' => 'Container', 'Label' => '�ƴ�'),
			array('Column' => 'MaleNormalText', 'Label' => '������ʸ��'),
			array('Column' => 'MaleNormalBottom', 'Label' => '�����ﲼ��'),
                                    array('Column' => 'MaleNormalBottom', 'Label' => '��������'),
			array('Column' => 'FemaleNormalText', 'Label' => '������ʸ��'),
			array('Column' => 'FemaleNormalBottom', 'Label' => '�����ﲼ��'),
			array('Column' => 'FemaleNormalTop', 'Label' => '��������'),
			array('Column' => 'created_on','Label' => '������'),
			),
		'enable_qbe' => array(
			array('Column' => '�������', 'Compare' => '"test_lab"',
			      'Draw' => 'enum',
			      'Enum' => array(
				      '' => '',
				      '1' => 'CLIP',
				      '2' => '2����',
				      '3' => '3����',
				      ),
				),
			array('Column' => '����������', 'Compare' =>  '"LaboSystemCode"', 'Singleton' => 1, 'CompareMethod' =>'number'),
			array('Column' => '���������', 'Compare' => '"ReceiptSystemCode"', 'Singleton' => 1),
			array('Column' => '����̾', 'Compare' => '"Name"', 'Singleton' => 1),
			array('Column' => '����̾', 'Compare' => '"Search"', 'Singleton' => 1),
			array('Column' => 'ñ��', 'Compare' => '"Unit"', 'Singleton' => 1),
			array('Column' => '�ƴ�', 'Compare' => '"Container"', 'Singleton' => 1),
			array('Column' => '������ʸ��', 'Compare' => '"MailNormalText"', 'Singleton' => 1),
			array('Column' => '�����ﲼ��', 'Compare' => '"MailNormalBottom"', 'Singleton' => 1),
			array('Column' => '��������', 'Compare' => '"MailNormalTop"', 'Singleton' => 1),
			array('Column' => '������ʸ��', 'Compare' => '"FemailNormalText"', 'Singleton' => 1),
			array('Column' => '�����ﲼ��', 'Compare' => '"FemailNormalBottom"', 'Singleton' => 1),
			array('Column' => '��������', 'Compare' => '"FemailNormalTop"', 'Singleton' => 1),
			array('Column' => '������', 'Compare' =>  '"created_on"', 'Singleton' => 1)
				      )
	       );

if($_mx_test_master == 2)
  $param = $param1;

master_select_table($param);
?>
