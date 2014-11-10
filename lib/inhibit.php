<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

class Inhibit {
  function Inhibit() {
    $this->db = mx_db_connect();
    $this->result = NULL;
  }

  function cache_interact($drugs) {
    if(is_null($this->_cache)) {
      $t = time();
      #print "Caching start";
      $ds = implode(',', array_map('mx_db_sql_quote', $drugs));
      $stmt = <<<SQL
      SELECT distinct drugcd, drugcd2, syojyou, sayokijyo
      FROM tbl_interact I JOIN tbl_sskijyo S ON I.syojyoucd  = S.syojyoucd 
      WHERE drugcd in (${ds}) or drugcd2 in (${ds})
SQL;
      $rs = mx_db_fetch_all($this->db, $stmt);
      foreach($rs as $r) {
	$k1 = $r['drugcd'];
	$k2 = $r['drugcd2'];
	if ($r['drugcd2'] < $r['drugcd']) {
	  $tmp = $k2;
	  $k2 = $k1;
	  $k1 = $tmp;
	}
	$this->_cache[$k1][$k2] = $r;
      }
      #print "Caching END" . (time() - $t);

    }
  }

  function check_pair($drugcd, $drugcd2) {
    if ($drugcd2 < $drugcd) {
      $tmp = $drugcd2;
      $drugcd2 = $drugcd;
      $drugcd = $tmp;
    }
    return $this->_cache[$drugcd][$drugcd2];
  }

  function check_combination($drugs) {
    $this->cache_interact($drugs);
    $ret = NULL;
    while( count($drugs) > 0) {
      $drug = array_shift($drugs);
      foreach($drugs as $target) {
	$r = $this->check_pair($drug, $target);
	if($r) 
	  $ret[] = $r;
	}
    }
    $this->result = $ret;
    return $ret;
  }

  function _get_med($drugcd) {
    $db = mx_db_connect();
    $drugcd = mx_db_sql_quote($drugcd);
    $stmt = <<<SQL
      SELECT "レセプト電算処理システム医薬品名"
      FROM "Medis医薬品マスター"
      WHERE "Superseded" IS NULL
      AND "レセプト電算処理システムコード（１）"=${drugcd}
SQL;
   $rs = mx_db_fetch_all($db, $stmt);
   if($rs and count($rs) > 0)
     return $rs[0]["レセプト電算処理システム医薬品名"];
  }

  function draw() {
    $ret = NULL;
    if(is_null($this->result) or count($this->result) == 0)
      $ret[] = "禁忌チェック問題なし";

    if($this->result) {
      foreach($this->result as $r) {
	
	$drug = $this->_get_med($r['drugcd']);
	$drug2 = $this->_get_med($r['drugcd2']);
	
	if(!$drug)
	  $drug = "不明な薬剤(". $r['drugcd'] .")";
	
	if(!$drug2)
	  $drug2 = "不明な薬剤(". $r['drugcd2'] .")";
	
	$ret[] = sprintf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $drug, $drug2, $r['syojyou'], $r['sayokijyo']);
      }
      $ret[] = "<table border=1>";
      $ret[] = "<tr><td>薬剤1</td><td>薬剤2</td><td>症状</td><td>作用機序</td></tr>";
      $ret[] = implode('<br>', $ret);
      $ret[] = "</table>";
    }
    return implode("\n", $ret);
  }

  function annotate_drug($cd, $anno) {
    $ret = NULL;
    foreach($anno as $a)
      if ($a["レセプト電算処理システムコード（１）"] == $cd && $a['ObjectID'])
	$ret[] = sprintf("処方箋ID%dで%sに処方開始の薬剤%s",
			 $a['ObjectID'],
			 $a['処方開始日'],
			 $a["レセプト電算処理システム医薬品名"]);
    if(is_null($ret))
      return NULL;
    return implode('と', $ret);
  }

  function to_string($anno=NULL) {
    $ret = NULL;
    if(is_null($this->result) or count($this->result) == 0)
      return '';

    if($this->result) {
      foreach($this->result as $r) {
	// if there's annotation info use the info (most likely drugs from
	// past orders).

	// if not, lookup med db (most likely drugs from currently editing
	// order)

	$drug = $this->annotate_drug($r['drugcd'], $anno);
	if(is_null($drug))
	  $drug = $this->_get_med($r['drugcd']);
	$drug2 = $this->annotate_drug($r['drugcd2'], $anno);
	if(is_null($drug2))
	  $drug2 = $this->_get_med($r['drugcd2']);

	$ret[] = sprintf("<font color=\"red\">%sと%sは禁忌です(%s)</font>", $drug, $drug2, $r['syojyou']);
	$ret[] = $r['sayokijyo'];
      }
    }
    return implode("<br>\n", $ret);
  }
}

?>
