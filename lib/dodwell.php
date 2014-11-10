<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

class Dodwell {
  function Dodwell($ptinfo) {
    // カルテ番号
    $this->karte_bango = trim($ptinfo["患者ID"]);
    // 漢字氏名
    $this->kanji_shimei = $ptinfo["姓"] . '　' . $ptinfo["名"];
    // カナ氏名
    $this->kana_shimei = $ptinfo['フリガナ'];
    // 性別
    $this->seibetsu = $ptinfo["性別"] == 'M' ? '1' : ( $ptinfo["性別"] == 'F' ? '2' : NULL);
    // 生年月日
    if (trim($ptinfo["生年月日"]) != '') {
      list($y, $m,$d) = split('-', $ptinfo["生年月日"]);
      $ymd = sprintf("%02d%02d%02d", $y, $m, $d);
      $n=null;
      if ($ymd <= "19120729") {
	$gg = "明治";
	$yy = $y - 1867;
      } elseif ($ymd >= "19120730" && $ymd <= "19261224") {
	$gg = "大正";
	$yy = $y - 1911;
      } elseif ($ymd >= "19261225" && $ymd <= "19890107") {
	$gg = "昭和";
	$yy = $y - 1925;
      } elseif ($ymd >= "19890108") {
	$gg = "平成";
	$yy = $y - 1988;
      }
      $this->nengo = $gg;
      $this->nen = $yy+0;
      $this->tsuki = $m+0;
      $this->nichi = $d+0;
    }
  }
  
  function valid() {
    $this->error = array();
    if(is_null($this->karte_bango))
      $this->error[] = "カルテ番号(患者ID)が空です。";
    if(!mb_strpos($this->kanji_shimei, '　'))
      $this->error[] = "漢字氏名の姓と名の間に全角スペースが必要です。";
    if(is_null($this->kana_shimei))
      $this->error[] = "カナ氏名が空です。";
    if(is_null($this->seibetsu))
      $this->error[] = "性別が空です。";
    if(is_null($this->nengo))
      $this->error[] = "年号が空です。";
    if(is_null($this->nen))
      $this->error[] = "生年月日年号が空です。";
    if(is_null($this->nen))
      $this->error[] = "生年月日年が空です。";
    if(is_null($this->tsuki))
      $this->error[] = "生年月日月が空です。";
    if(is_null($this->nichi))
      $this->error[] = "生年月日日が空です。";
    return count($this->error) == 0;
  }

  function get_csv() {
    $vals = array(
		  $this->karte_bango,
		  mb_convert_encoding($this->kanji_shimei, "SJIS"),
		  mb_convert_encoding(mb_convert_kana($this->kana_shimei, "k"), "SJIS"),
		  $this->seibetsu,
		  mb_convert_encoding($this->nengo, "SJIS"),
		  $this->nen,
		  $this->tsuki,
		  $this->nichi,
		  );
    return implode(',', $vals);
  }
  
  function write_csv() {
    global $_mx_dodwell_csv_path;
    
    // validate
    if(!$this->valid())
      return False;
    
    // error if csv path is not defined or a file exists already
    if($_mx_dodwell_csv_path == '' or file_exists($_mx_dodwell_csv_path)) {
      $this->error[] = "カードプリンタービジー";
      return False;
    }
    
    // error if file creation failed
    $fp = fopen($_mx_dodwell_csv_path, 'w');
    if(!$fp) {
      $this->error[] = "カードプリンターにファイルが書き込めません";
      return False;
    }
    
    fwrite($fp, $this->get_csv());
    return True;
  }
}
?>