<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

// This file is to hold various UI configurations for different hospitals.

/*

$__uiconfig_patientinfo_brief_show::

An array of arrays that defines the columns and their display order in
lib/boilerplates/patientinfo.php:mx_draw_patientinfo_brief();
the columns can be chosen from the following set:

	"患者ID", "発症日", "氏名", "入院日", "性別", "退院予定日",
	"生年月日", "利き手", "入外区分"

*/

if ($_mx_cheap_layout) {
	$__uiconfig_patientinfo_brief_show =
		array(array("患者ID", "氏名", "入院日", "発症日"),
		      array("性別", "生年月日", "入外区分", "退院予定日"));
}
else {
	$__uiconfig_patientinfo_brief_show =
		array(array("患者ID", "発症日"),
		      array("氏名", "入院日"),
		      array("性別", "退院予定日"),
		      array("生年月日", "利き手"));
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

$__uiconfig_ms_qbe_enum = array('' => 'マスタ全て',
				'U' => '未指定のみ',
				'N' => '不採用分のみ',
				'Y' => '採用・非頻出',
				'YF' => '採用・頻出',
				'F' => '頻出',
				);

$__uiconfig_ms_header_fields = array('N' => '不採用',
				     'Y' => '採用',
				     'F' => '頻出',
				     );

if ($_mx_meds_accept=='I') {
	$__uiconfig_ms_qbe_enum_medicine =
		array('' => 'マスター全て',
		      'U' => '未指定のみ',
		      'N' => '不採用分のみ',
		      'I' => '院内のみ採用分',
		      );
	$__uiconfig_ms_header_fields_medicine =
		array('N' => '不採用',
		      'I' => '院内',
		      );
	$__uiconfig_u_pharmacy_accepted = array('I' =>1,'B'=>1);
	$__uiconfig_u_pharmacy_qbe = array('I' => '院内採用薬');

	$__uiconfig_u_pharmacy_default_qbe = array('当院採用', 'I');
	$__uiconfig_u_pharmacy_outpatient_default = array('当院採用', 'I');

} else if ($_mx_meds_accept=='IOB') {
	$__uiconfig_ms_qbe_enum_medicine =
		array('' => 'マスター全て',
		      'U' => '未指定のみ',
		      'N' => '不採用分のみ',
		      'I' => '院内のみ採用分',
		      'B' => '院外追加採用分',
		      );
	$__uiconfig_ms_header_fields_medicine =
		array('N' => '不採用',
		      'I' => '院内',
		      'B' => '院外',
		      );
	$__uiconfig_u_pharmacy_accepted = array('I' =>1,'B'=>1);
	$__uiconfig_u_pharmacy_qbe = array('I' => '院内採用薬',
					   'IB' => '院外処方用',
						'' => 'マスター全て');

	$__uiconfig_u_pharmacy_default_qbe = array('当院採用', 'I');
	$__uiconfig_u_pharmacy_outpatient_default = array('当院採用', 'IB');

} else {
	$__uiconfig_ms_qbe_enum_medicine = $__uiconfig_ms_qbe_enum;
	$__uiconfig_ms_header_fields_medicine = $__uiconfig_ms_header_fields;

	$__uiconfig_u_pharmacy_accepted = array('Y'=>1,'F'=>1);
	$__uiconfig_u_pharmacy_qbe = array('Y' => '採用薬',
					   'YF' => '採用・頻出',
					   '' => 'マスタ全て');
	$__uiconfig_u_pharmacy_default_qbe = array('当院採用', 'Y');
	$__uiconfig_u_pharmacy_outpatient_default = array('当院採用', 'YF');
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
   'ア' => 'ア','カ' => 'カ',
   'サ' => 'サ','タ' => 'タ',
   'ナ' => 'ナ','ハ' => 'ハ',
   'マ' => 'マ','ヤ' => 'ヤ',
   'ラ' => 'ラ','ワ' => 'ワ',
   'ン' => 'ン',"_0" => "br",
   
   'イ' => 'イ','キ' => 'キ',
   'シ' => 'シ','チ' => 'チ',
   'ニ' => 'ニ','ヒ' => 'ヒ',
   'ミ' => 'ミ','_1' => '_',
   'リ' => 'リ','ー' => "ー",
   '_2' => 'br',
   
   'ウ' => 'ウ','ク' => 'ク',
   'ス' => 'ス','ツ' => 'ツ',
   'ヌ' => 'ヌ','フ' => 'フ',
   'ム' => 'ム','ユ' => 'ユ',
   'ル' => 'ル','_3' => 'br',
   
   'エ' => 'エ','ケ' => 'ケ',
   'セ' => 'セ','テ' => 'テ',
   'ネ' => 'ネ','ヘ' => 'ヘ',
   'メ' => 'メ','_4' => '_',
   'レ' => 'レ','_5' => 'br',
   
   'オ' => 'オ','コ' => 'コ',
   'ソ' => 'ソ','ト' => 'ト',
   'ノ' => 'ノ','ホ' => 'ホ',
   'モ' => 'モ','ヨ' => 'ヨ',
   'ロ' => 'ロ','ヲ' => 'ヲ',
   '_6' => 'br',
   );

$__uiconfig_rx_kbd = array('ア' => 'ア..ア','カ' => 'カ..ガ',
			     'サ' => 'サ..ザ','タ' => 'タ..ダ',
			     'ナ' => 'ナ..ナ','ハ' => 'ハ..パ',
			     'マ' => 'マ..マ','ヤ' => 'ヤ..ヤ',
			     'ラ' => 'ラ..ラ','ワ' => 'ワ..ワ',
			     'ン' => 'ン..ン',"_0" => "br",

			     'イ' => 'イ..イ','キ' => 'キ..ギ',
			     'シ' => 'シ..ジ','チ' => 'チ..ヂ',
			     'ニ' => 'ニ..ニ','ヒ' => 'ヒ..ピ',
			     'ミ' => 'ミ..ミ',"_1" => "_",
			     'リ' => 'リ..リ','ツムラ' => 'ツムラ',
			     "_2" => "br",


			     'ウ' => 'ウ..ウ','ク' => 'ク..グ',
			     'ス' => 'ス..ズ','ツ' => 'ツ..ヅ',
			     'ヌ' => 'ヌ..ヌ','フ' => 'フ..プ',
			     'ム' => 'ム..ム','ユ' => 'ユ..ユ',
			     'ル' => 'ル..ル','その他' => '!ア..ヲ',
			     "_3" => "br",

			     'エ' => 'エ..エ','ケ' => 'ケ..ゲ',
			     'セ' => 'セ..ゼ','テ' => 'テ..デ',
			     'ネ' => 'ネ..ネ','ヘ' => 'ヘ..ペ',
			     'メ' => 'メ..メ',"_4" => "_",
			     'レ' => 'レ..レ','ー' => "ー",
			     "_5" => "br",

			     'オ' => 'オ..オ','コ' => 'コ..ゴ',
			     'ソ' => 'ソ..ゾ','ト' => 'ト..ド',
			     'ノ' => 'ノ..ノ','ホ' => 'ホ..ポ',
			     'モ' => 'モ..モ','ヨ' => 'ヨ..ヨ',
			     'ロ' => 'ロ..ロ','ヲ' => 'ヲ..ヲ',
			     "_6" => "br",
			);

/*
 * Used by disease name applicatino to pick pre- and postfix
 * adjectives for a disease name
 */
$__uiconfig_dismod_kbd = array('ア' => 'ア..ア','カ' => 'カ..ガ',
			       'サ' => 'サ..ザ','タ' => 'タ..ダ',
			       'ナ' => 'ナ..ナ','ハ' => 'ハ..パ',
			       'マ' => 'マ..マ','ヤ' => 'ヤ..ヤ',
			       'ラ' => 'ラ..ラ','ワ' => 'ワ..ワ',
			       "_0" => "br",

			       'イ' => 'イ..イ','キ' => 'キ..ギ',
			       'シ' => 'シ..ジ','チ' => 'チ..ヂ',
			       'ニ' => 'ニ..ニ','ヒ' => 'ヒ..ピ',
			       'ミ' => 'ミ..ミ',"_1" => "_",
			       'リ' => 'リ..リ',"_2" => "br",


			       'ウ' => 'ウ..ウ','ク' => 'ク..グ',
			       'ス' => 'ス..ズ','ツ' => 'ツ..ヅ',
			       'ヌ' => 'ヌ..ヌ','フ' => 'フ..プ',
			       'ム' => 'ム..ム','ユ' => 'ユ..ユ',
			       'ル' => 'ル..ル','その他' => '!ア..ヲ',
			       "_3" => "br",

			       'エ' => 'エ..エ','ケ' => 'ケ..ゲ',
			       'セ' => 'セ..ゼ','テ' => 'テ..デ',
			       'ネ' => 'ネ..ネ','ヘ' => 'ヘ..ペ',
			       'メ' => 'メ..メ',"_4" => "_",
			       'レ' => 'レ..レ',"_5" => "br",

			       'オ' => 'オ..オ','コ' => 'コ..ゴ',
			       'ソ' => 'ソ..ゾ','ト' => 'ト..ド',
			       'ノ' => 'ノ..ノ','ホ' => 'ホ..ポ',
			       'モ' => 'モ..モ','ヨ' => 'ヨ..ヨ',
			       'ロ' => 'ロ..ロ','ヲ' => 'ヲ..ヲ',
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
$__uiconfig_ptname_kbd = array('ア' => 'ア..ア','カ' => 'カ..ガ',
			       'サ' => 'サ..ザ','タ' => 'タ..ダ',
			       'ナ' => 'ナ..ナ','ハ' => 'ハ..パ',
			       'マ' => 'マ..マ','ヤ' => 'ヤ..ヤ',
			       'ラ' => 'ラ..ラ','ワ' => 'ワ..ワ',
			       'ン' => 'ン..ン',"_0" => "br",

			       'イ' => 'イ..イ','キ' => 'キ..ギ',
			       'シ' => 'シ..ジ','チ' => 'チ..ヂ',
			       'ニ' => 'ニ..ニ','ヒ' => 'ヒ..ピ',
			       'ミ' => 'ミ..ミ',"_1" => "_",
			       'リ' => 'リ..リ','_1.5' => '_',
			       "_2" => "br",


			       'ウ' => 'ウ..ウ','ク' => 'ク..グ',
			       'ス' => 'ス..ズ','ツ' => 'ツ..ヅ',
			       'ヌ' => 'ヌ..ヌ','フ' => 'フ..プ',
			       'ム' => 'ム..ム','ユ' => 'ユ..ユ',
			       'ル' => 'ル..ル','その他' => '!ア..ヲ',
			       "_3" => "br",

			       'エ' => 'エ..エ','ケ' => 'ケ..ゲ',
			       'セ' => 'セ..ゼ','テ' => 'テ..デ',
			       'ネ' => 'ネ..ネ','ヘ' => 'ヘ..ペ',
			       'メ' => 'メ..メ',"_4" => "_",
			       'レ' => 'レ..レ',"_5" => "br",

			       'オ' => 'オ..オ','コ' => 'コ..ゴ',
			       'ソ' => 'ソ..ゾ','ト' => 'ト..ド',
			       'ノ' => 'ノ..ノ','ホ' => 'ホ..ポ',
			       'モ' => 'モ..モ','ヨ' => 'ヨ..ヨ',
			       'ロ' => 'ロ..ロ','ヲ' => 'ヲ..ヲ',
			       "_6" => "br",
			       );



?>
