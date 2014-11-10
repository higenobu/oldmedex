<?php // -*- mode: php; coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';

/*
 * CLIP specific master uploader
 *
 * 1. upload master
 * 2. list difference
 * 3. pick & accept
 * 4. commit
 */


class manage_clip_uploader { // extends nottin'

  function manage_clip_uploader($prefix) {
    $db = mx_db_connect();
    $script = '../tools/clip/clip_updater.py';
    $this->prefix = $prefix;
    $this->chosen_ = NULL;
    $this->errs = array();
    $this->success = NULL;
    if (array_key_exists($this->prefix . 'show', $_REQUEST)) {
      $file = $_FILES[$this->prefix . 'file'];
      if(!$file)
	$this->errs[] = "ファイルを指定してください";

      if(!$this->errs) {
	$desc = array(
		      0 => array("pipe", "r"),  // stdin 
		      1 => array("pipe", "w"),  // stdout 
		      2 => array("pipe", "w")   // stderr
		      );
        $spath = $_SERVER['DOCUMENT_ROOT'] .
                             $script . " " .
                             escapeshellarg($file['tmp_name']);

	$process = proc_open($spath,
			     $desc,
			     $pipes);
	if( !is_resource($process) ) {
	  $this->errs[] = "スクリプトの呼び出しに失敗しました";
	  $this->chosen_ = NULL;
	}else{
	  fclose($pipes[0]);
	  while (!feof($pipes[2]))
	    $txt .= fread($pipes[2], 8192);
	  proc_close($process);
	  if($txt) {
	    $this->items = NULL;
	    $txt = mb_convert_encoding($txt, 'eucJP-win', 'UTF-8');
	    $this->items = $this->parse_result($txt);
	    $this->chosen_ = NULL;
	  }else{
	    $this->success = "アップロードしました。";
	  }
	}
      }
    }else if (array_key_exists($this->prefix . 'exec', $_REQUEST)) {
      $cols = array("当院採用",
		    "Category",
		    "Name",
		    "Unit",
		    "MaleNormalText",
		    "MaleNormalBottom",
		    "Parent",
		    "FemaleNormalTop",
		    "FemaleNormalBottom",
		    "FemaleNormalText",
		    "Search",
		    "medis",
		    "MaleNormalTop",
		    "Container",
		    "ReceiptSystemCode",
		    "LaboSystemCode",
		    "DispCategory",
		    "SortOrder"
		    );
      $this->errs = array();
      # validate
      foreach(array('NEW', 'MOD') as $mode) {
	$vvv = NULL;
	foreach($_REQUEST as $k => $v) {
	  list($m, $code) = explode(':', $k);
	  if ($m == $mode) {
	    $a = mx_form_unescape_key($v);
	    $a[0] = $a[0] == '○' ? 'Y' : 'N';
	    $a[1]=substr($a[11],0,1);
	    if($a[0] == 'Y') {
	      if (!$a[11]) {
		$this->errs[] = $a[15] . "は表示項目ですがmedisコードがありません";
	      }else if ($a[1]<1 or $a[1]>9) {
		$this->errs[] = $a[15] . "のmedisコード{$a[11]}は不正です";
	      }
	    }
	  }
	}
      }
      if (count($this->errs) > 0)
	return;
      pg_query($db, 'BEGIN');
      foreach(array('NEW', 'MOD') as $mode) {
	$vvv = NULL;
	foreach($_REQUEST as $k => $v) {
	  list($m, $code) = explode(':', $k);
	  if ($m == $mode) {
	    $a = mx_form_unescape_key($v);
	    $a[0] = $a[0] == '○' ? 'Y' : 'N';
	    $cat = NULL;
	    if ($a[11]) {
		    $cat = substr($a[11],0,1);
		    if ($cat < 1 || 9 < $cat)
			    $cat = NULL;
	    }
	    $a[1] = $cat;
	    $a[] = $cat;
	    $a[] = $a[15];
	    if ($m == 'NEW') {
	      $ccc = implode('","', $cols);
	      $vvv = implode(', ', array_map('mx_db_sql_quote', $a));
	      $stmt = "INSERT INTO test_master (\"$ccc\") VALUES ($vvv);";
	      if (!pg_query($db, $stmt)) {
		$this->errs[] = "DBエラーです. ".$stmt;
		return;
	      }
	    }else if ($m == 'MOD') {
	      // stash and update
	      $stmt = "SELECT nextval('\"test_master_ID_seq\"') as id;";
	      $r = mx_db_fetch_single($db, $stmt);
	      $stash_id = $r['id'];
	      $stmt = "SELECT * FROM test_master WHERE \"Superseded\" IS NULL AND \"LaboSystemCode\"=" . mx_db_sql_quote($a[15]);
	      // print $stmt."<br>";
	      $r = mx_db_fetch_single($db, $stmt);
	      if(is_null($r)) {
		# this must not happen
		$this->errs[] = "DBエラーです. 検査コード {$a[15]} がみつからない";
		return;
	      }
	      $rcols = array();
	      $rvals = array();
	      foreach($r as $rcol => $rval) {
		if ($rcol == "ObjectID")
		  $rval = $stash_id;
		else if ($rcol == "Superseded")
		  $rval = 'now()';
		$rcols[] = $rcol;
		$rvals[] = mx_db_sql_quote($rval);
	      }
	      $rcols = '"' . implode('", "', $rcols) . '"';
	      $rvals = implode(', ', $rvals);
	      $stmt = "INSERT INTO test_master ($rcols) VALUES ($rvals)";
	      //print "create a new row<br>".$stmt."<br>";
	      if (!pg_query($db, $stmt))
		print $stmt;
	      $kv = array();
	      for($i=0; $i < count($cols); $i++)
		$kv[] = sprintf("\"%s\"=%s", $cols[$i],
			      mx_db_sql_quote($a[$i]));
	      $kv = implode(', ', $kv);
	      $stmt = "UPDATE test_master SET $kv WHERE \"ObjectID\"=" . $r['ObjectID'];
	      //print $stmt."<br>";
	      if (!pg_query($db, $stmt)){
		$this->errs[] = "DBエラー." . $stmt;
		return;
	      }
	    }
	  }
	}
      }
      # TODO: convert parent from labo code to objectid
      # $plabo = $a[6];

      if(pg_query($db, 'COMMIT'))
	$this->errs[] = "実行完了";
    }
  }

  function parse_result($txt) {

     $newitems = NULL;
     $moditems = NULL;

     foreach(explode("\n", $txt) as $line) {
       list($state, $lab, $data, $name) = explode(':', $line);

       if($state == 'NEW')
	 $newitems[$lab] = array('NAME' => $name,
				 'DATA' => $data);
       if($state == 'MOD') {
	 $moditems[$lab] = array('NAME' => $name,
				 'DATA' => $data);
       }
     }
     return array($newitems, $moditems);
  }


  function draw_items($d, $mode) {
    $title = array('NEW' => "新規追加される検査項目",
		   'MOD' => "変更される検査項目"
		   );
    $ttl = $title[$mode];
    if (!is_array($d))
      return <<<HTML
<table class="listofstuff">
  <tr><th>$ttl</th></tr>
  <tr><td>ありません</td></tr>
</table>
HTML;

    $output[] = <<<HTML
<table class="listofstuff">
  <tr><th colspan="3">$ttl</th></tr>
  <tr><td>&nbsp;</td><td>検査コード</td><td>検査名称</td></tr>
HTML;
    
    foreach($d as $k => $v) {
      $cb = sprintf('<input type="checkbox" name="%s:%s" value="%s" checked>',
		    $mode, htmlspecialchars($k), $v['DATA']);
      $output[] = sprintf("<tr><td>%s</td><td>%s</td><td>%s</td></tr>",
			  $cb,
			  htmlspecialchars($k), 
			  htmlspecialchars($v['NAME']));
    }
    $output[] = "</table>";
    return implode("\n", $output);
  }

  function changed() { return 1; }

  function chosen() {
    return $this->chosen_;
  }

  function draw() {
    print "<br>";
    if ($this->errs) {
      foreach ($this->errs as $msg) {
	print "$msg<br>";
      }
      print "<br>\n";
    }

    if($this->items) {
      mx_formi_submit($this->prefix. 'exec', "実行");
      list($n, $m, $d) = $this->items;
      print '<table class="listofstuff" style="border: 1px solid"><tr>';

      print "<td valign=top>";
      print $this->draw_items($n, 'NEW');
      print "</td>";

      print "<td valign=top>";
      print $this->draw_items($m, 'MOD');
      print "</td>";

      print "</tr></table>";
    }else{
      $file = $_FILE[$this->prefix . 'file'];
      print "CLIPマスターファイルを指定してください。<br>";
      print "<br>";
      mx_formi_upload($this->prefix . 'file', $file);
      mx_formi_submit($this->prefix . 'show', '送信');
      mx_formi_hidden('Print', 1);
    }
  }

  function lost_selection() { return 0; }

}

class manage_clip_uploader_application extends single_table_application {
  var $use_upload = 1;
  var $use_print = 1;
  var $use_single_pane = 1;
  function list_of_objects($prefix) {
    $loo = new manage_clip_uploader($prefix);
    $loo->application = &$this;
    return $loo;
  }

  function allow_new() {
    return NULL;
  }
}

?>
