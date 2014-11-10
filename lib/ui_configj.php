<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

// This file is to hold various UI configurations for different hospitals.

/*

$__uiconfig_patientinfo_brief_show::

An array of arrays that defines the columns and their display order in
lib/boilerplates/patientinfo.php:mx_draw_patientinfo_brief();
the columns can be chosen from the following set:

	"����ID", "ȯ����", "��̾", "������", "����", "�ౡͽ����",
	"��ǯ����", "������", "������ʬ"

*/

if ($_mx_cheap_layout) {
	$__uiconfig_patientinfo_brief_show =
		array(array("����ID", "��̾", "������", "ȯ����"),
		      array("����", "��ǯ����", "������ʬ", "�ౡͽ����"));
}
else {
	$__uiconfig_patientinfo_brief_show =
		array(array("����ID", "ȯ����"),
		      array("��̾", "������"),
		      array("����", "�ౡͽ����"),
		      array("��ǯ����", "������"));
}

/*
$__uiconfig_pharmacy_rx_show_stop_doctor::
 
A boolean that tells if stop doctor and date should be displayed
and editable in the u/pharmacy/demo1.php application.

*/
$__uiconfig_pharmacy_rx_show_stop_doctor = 0;

/*
$__uiconfig_pharmacy_rx_print_after_update::

When enabled, updating Rx would always pop-up the print window.

 */
$__uiconfig_pharmacy_rx_print_after_update = 1;

$__uiconfig_ms_qbe_enum = array('' => '�ޥ�������',
				'U' => '̤����Τ�',
				'N' => '�Ժ���ʬ�Τ�',
				'Y' => '���ѡ����ѽ�',
				'YF' => '���ѡ��ѽ�',
				'F' => '�ѽ�',
				);

$__uiconfig_ms_header_fields = array('N' => '�Ժ���',
				     'Y' => '����',
				     'F' => '�ѽ�',
				     );

if ($_mx_meds_accept=='I') {
	$__uiconfig_ms_qbe_enum_medicine =
		array('' => '�ޥ���������',
		      'U' => '̤����Τ�',
		      'N' => '�Ժ���ʬ�Τ�',
		      'I' => '����Τߺ���ʬ',
		      );
	$__uiconfig_ms_header_fields_medicine =
		array('N' => '�Ժ���',
		      'I' => '����',
		      );
	$__uiconfig_u_pharmacy_accepted = array('I' =>1,'B'=>1);
	$__uiconfig_u_pharmacy_qbe = array('I' => '���������');

	$__uiconfig_u_pharmacy_default_qbe = array('��������', 'I');
	$__uiconfig_u_pharmacy_outpatient_default = array('��������', 'I');

} else if ($_mx_meds_accept=='IOB') {
	$__uiconfig_ms_qbe_enum_medicine =
		array('' => '�ޥ���������',
		      'U' => '̤����Τ�',
		      'N' => '�Ժ���ʬ�Τ�',
		      'I' => '����Τߺ���ʬ',
		      'B' => '�����ɲú���ʬ',
		      );
	$__uiconfig_ms_header_fields_medicine =
		array('N' => '�Ժ���',
		      'I' => '����',
		      'B' => '����',
		      );
	$__uiconfig_u_pharmacy_accepted = array('I' =>1,'B'=>1);
	$__uiconfig_u_pharmacy_qbe = array('I' => '���������',
					   'IB' => '����������',
						'' => '�ޥ���������');

	$__uiconfig_u_pharmacy_default_qbe = array('��������', 'I');
	$__uiconfig_u_pharmacy_outpatient_default = array('��������', 'IB');

} else {
	$__uiconfig_ms_qbe_enum_medicine = $__uiconfig_ms_qbe_enum;
	$__uiconfig_ms_header_fields_medicine = $__uiconfig_ms_header_fields;

	$__uiconfig_u_pharmacy_accepted = array('Y'=>1,'F'=>1);
	$__uiconfig_u_pharmacy_qbe = array('Y' => '������',
					   'YF' => '���ѡ��ѽ�',
					   '' => '�ޥ�������');
	$__uiconfig_u_pharmacy_default_qbe = array('��������', 'Y');
	$__uiconfig_u_pharmacy_outpatient_default = array('��������', 'YF');
}


/*
$__uiconfig_appbar_app_classes: The category of applications to show
in the application bar, when USE_APPLICATION_BAR is in effect
*/

if ($_mx_appbar_classes == '')
	$_mx_appbar_classes = "SM12C34567D";

$__uiconfig_appbar_app_classes = array();
for ($__i = 0; $__i < strlen($_mx_appbar_classes); $__i++) {
    $__uiconfig_appbar_app_classes[] = substr($_mx_appbar_classes, $__i, 1);
}

/*
$__uiconfig_rx_kbd.

This is passed to los to show a one-click-search keyboard via SearchByInitial
when looking for an Rx drug.

The keyboard is configurable by key => value pairs.  Special value '_'
is for a empty button, 'br' breaks line.  Keys must be unique.

*/
$__uiconfig_kbd = array
  (
   '��' => '��','��' => '��',
   '��' => '��','��' => '��',
   '��' => '��','��' => '��',
   '��' => '��','��' => '��',
   '��' => '��','��' => '��',
   '��' => '��',"_0" => "br",
   
   '��' => '��','��' => '��',
   '��' => '��','��' => '��',
   '��' => '��','��' => '��',
   '��' => '��','_1' => '_',
   '��' => '��','��' => "��",
   '_2' => 'br',
   
   '��' => '��','��' => '��',
   '��' => '��','��' => '��',
   '��' => '��','��' => '��',
   '��' => '��','��' => '��',
   '��' => '��','_3' => 'br',
   
   '��' => '��','��' => '��',
   '��' => '��','��' => '��',
   '��' => '��','��' => '��',
   '��' => '��','_4' => '_',
   '��' => '��','_5' => 'br',
   
   '��' => '��','��' => '��',
   '��' => '��','��' => '��',
   '��' => '��','��' => '��',
   '��' => '��','��' => '��',
   '��' => '��','��' => '��',
   '_6' => 'br',
   );

$__uiconfig_rx_kbd = array('��' => '��..��','��' => '��..��',
			     '��' => '��..��','��' => '��..��',
			     '��' => '��..��','��' => '��..��',
			     '��' => '��..��','��' => '��..��',
			     '��' => '��..��','��' => '��..��',
			     '��' => '��..��',"_0" => "br",

			     '��' => '��..��','��' => '��..��',
			     '��' => '��..��','��' => '��..��',
			     '��' => '��..��','��' => '��..��',
			     '��' => '��..��',"_1" => "_",
			     '��' => '��..��','�ĥ��' => '�ĥ��',
			     "_2" => "br",


			     '��' => '��..��','��' => '��..��',
			     '��' => '��..��','��' => '��..��',
			     '��' => '��..��','��' => '��..��',
			     '��' => '��..��','��' => '��..��',
			     '��' => '��..��','����¾' => '!��..��',
			     "_3" => "br",

			     '��' => '��..��','��' => '��..��',
			     '��' => '��..��','��' => '��..��',
			     '��' => '��..��','��' => '��..��',
			     '��' => '��..��',"_4" => "_",
			     '��' => '��..��','��' => "��",
			     "_5" => "br",

			     '��' => '��..��','��' => '��..��',
			     '��' => '��..��','��' => '��..��',
			     '��' => '��..��','��' => '��..��',
			     '��' => '��..��','��' => '��..��',
			     '��' => '��..��','��' => '��..��',
			     "_6" => "br",
			);

/*
 * Used by disease name applicatino to pick pre- and postfix
 * adjectives for a disease name
 */
$__uiconfig_dismod_kbd = array('��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       "_0" => "br",

			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��',"_1" => "_",
			       '��' => '��..��',"_2" => "br",


			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','����¾' => '!��..��',
			       "_3" => "br",

			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��',"_4" => "_",
			       '��' => '��..��',"_5" => "br",

			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       "_6" => "br",
			       );

$__uiconfig_icd10_kbd = array('A' => 'A..A',
			      'B' => 'B..B',
			      'C' => 'C..C',
			      'D' => 'D..D',
			      'E' => 'E..E',
			      'F' => 'F..F',
			      'G' => 'G..G',
			      'H' => 'H..H',
			      'I' => 'I..I',
			      'J' => 'J..J',
			      'K' => 'K..K',
			      'L' => 'L..L',
			      'M' => 'M..M', '_0' => 'br',

			      'N' => 'N..N',
			      'O' => 'O..O',
			      'P' => 'P..P',
			      'Q' => 'Q..Q',
			      'R' => 'R..R',
			      'S' => 'S..S',
			      'T' => 'T..T',
			      'U' => 'U..U',
			      'V' => 'V..V',
			      'W' => 'W..W',
			      'X' => 'X..X',
			      'Y' => 'Y..Y',
			      'Z' => 'Z..Z', '_1' => 'br',
			      );

/*
 * Used by patient picker.
 */
$__uiconfig_ptname_kbd = array('��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��',"_0" => "br",

			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��',"_1" => "_",
			       '��' => '��..��','_1.5' => '_',
			       "_2" => "br",


			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','����¾' => '!��..��',
			       "_3" => "br",

			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��',"_4" => "_",
			       '��' => '��..��',"_5" => "br",

			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       '��' => '��..��','��' => '��..��',
			       "_6" => "br",
			       );



?>
