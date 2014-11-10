<?php
//abcdkarte.php

$__karte_cfg['D_RANDOM_LAYOUT'] = array(



array( 'Insn' =>'//'),
array( 'Label' =>'Karte作成日'),
array( 'Column' =>'日付','Option' => array('size' =>1)),
array( 'Column' =>'','Label' => '','Span' => 1,'Option' => array('size' => 1)),
 array( 'Insn' =>'//'),
 array( 'Insn' =>'//'),
array( 'Label' =>'Karte'),
array( 'Label' =>'Order'),
/*
array( 'Label' =>'Karte'),
array( 'Column' =>'shiji','Option' => array('size' =>1)),
array( 'Column' =>'','Label' => '','Span' => 1,'Option' => array('size' => 1)),
array( 'Insn' =>'//'),
 
*/
array( 'Insn' =>'//'), 
array( 'Insn' =>'//'),
array( 'Label' =>''),
array( 'Column' =>'O0','Label' => '','Option' => array('size' =>1)),
//array( 'Column' =>'','Label' => '','Span' => 1,'Option' => array('size' => 1)),

//array( 'Label' =>''),
array( 'Column' =>'P','Label' => '','Option' => array('size' =>1)),


array( 'Insn' =>'//'),

array( 'Label' =>'Note'),
array( 'Insn' =>'//'),
array( 'Label' =>''),
array( 'Column' =>'D','Label' => '','Option' => array('size' =>1)),
//array( 'Column' =>'','Label' => '','Span' => 1,'Option' => array('size' => 1)),
array( 'Insn' =>'//'),
array('Column' => 'recorded',
'Label' => '記録', 'Draw' => 'timestamp'),

array( 'Insn' =>'//'),
array( 'Label' =>''),
array('Column' => "I1", 'Label' =>"", 'Draw' => "extdocument",
			      'Option' =>array('img' => 'always',
			      'Extdocument' => '画像')),

);
$__karte_cfg['E_RANDOM_LAYOUT'] = $__karte_cfg['D_RANDOM_LAYOUT'];



?>

