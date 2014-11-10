<?php
$aaa="あ　い";
print $aaa;
function mx_kanjipad($data, $width)
{

$v=$data;

	if ($v != '' && mb_strlen($v,"UTF-8") < $width) {
		$cnt = $width - mb_strlen($v,"UTF-8");
		for ($i = 0; $i < $cnt; $i++) {

			$v = $v."一";
		}
		return $v;
	}
	return $data;
}
$aaa= "&#82A0;";
print $aaa;





?> 
