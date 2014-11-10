<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

class Dodwell {
  function Dodwell($ptinfo) {
    // ������ֹ�
    $this->karte_bango = trim($ptinfo["����ID"]);
    // ������̾
    $this->kanji_shimei = $ptinfo["��"] . '��' . $ptinfo["̾"];
    // ���ʻ�̾
    $this->kana_shimei = $ptinfo['�եꥬ��'];
    // ����
    $this->seibetsu = $ptinfo["����"] == 'M' ? '1' : ( $ptinfo["����"] == 'F' ? '2' : NULL);
    // ��ǯ����
    if (trim($ptinfo["��ǯ����"]) != '') {
      list($y, $m,$d) = split('-', $ptinfo["��ǯ����"]);
      $ymd = sprintf("%02d%02d%02d", $y, $m, $d);
      $n=null;
      if ($ymd <= "19120729") {
	$gg = "����";
	$yy = $y - 1867;
      } elseif ($ymd >= "19120730" && $ymd <= "19261224") {
	$gg = "����";
	$yy = $y - 1911;
      } elseif ($ymd >= "19261225" && $ymd <= "19890107") {
	$gg = "����";
	$yy = $y - 1925;
      } elseif ($ymd >= "19890108") {
	$gg = "ʿ��";
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
      $this->error[] = "������ֹ�(����ID)�����Ǥ���";
    if(!mb_strpos($this->kanji_shimei, '��'))
      $this->error[] = "������̾������̾�δ֤����ѥ��ڡ�����ɬ�פǤ���";
    if(is_null($this->kana_shimei))
      $this->error[] = "���ʻ�̾�����Ǥ���";
    if(is_null($this->seibetsu))
      $this->error[] = "���̤����Ǥ���";
    if(is_null($this->nengo))
      $this->error[] = "ǯ�椬���Ǥ���";
    if(is_null($this->nen))
      $this->error[] = "��ǯ����ǯ�椬���Ǥ���";
    if(is_null($this->nen))
      $this->error[] = "��ǯ����ǯ�����Ǥ���";
    if(is_null($this->tsuki))
      $this->error[] = "��ǯ��������Ǥ���";
    if(is_null($this->nichi))
      $this->error[] = "��ǯ�����������Ǥ���";
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
      $this->error[] = "�����ɥץ�󥿡��ӥ���";
      return False;
    }
    
    // error if file creation failed
    $fp = fopen($_mx_dodwell_csv_path, 'w');
    if(!$fp) {
      $this->error[] = "�����ɥץ�󥿡��˥ե����뤬�񤭹���ޤ���";
      return False;
    }
    
    fwrite($fp, $this->get_csv());
    return True;
  }
}
?>