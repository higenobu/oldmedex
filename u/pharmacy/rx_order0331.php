<?php // -*- coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/inhibit.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ui_config.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/rp_common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/rx.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/direction.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/print.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/list_editor.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/rows.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/pdf.php';     
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/index-pt.php';


global $dp;
global $dirp;
global $auth;
global $pid;

$db_new_rx_id = NULL;

function date_sort ($a,$b) {
  return strcmp($a["処方年月日"],$b["処方年月日"]);
}
function type_sort ($a,$b) {
  return strcmp($b["区分"],$a["区分"]);
}
function id_sort ($a,$b) {
  return strcmp($a["oid"],$b["oid"]);
}
function canc_sort ($a,$b) {
  return strcmp($a["停止日"],$b["停止日"]);
}

function get_drugs_in_past_orders($new_order_start, $tbl) {
  global $pid;
  /* $tbl: 0 for drug, 1 for shots
   1.  order_start + interval duration < new_order_start
   2.  stop_date < today
  */
  $table = array( array("薬剤処方箋", "薬剤処方箋内容"),
		  array("注射処方箋", "注射処方箋内容") );
  
  $order_table = $table[$tbl][0];
  $detail_table = $table[$tbl][1];
  $stmt = <<<SQL
    SELECT
    O."ObjectID",
    O."処方年月日",
    O."処方開始日",
    O."処方年月日",
	
    M."レセプト電算処理システム医薬品名",
    M."レセプト電算処理システムコード（１）"
    FROM "${order_table}" as O
    JOIN "${detail_table}" as D
    ON (O."ObjectID" = D."${order_table}" AND
	O."Superseded" IS NULL)
    JOIN "Medis医薬品マスター" M
    ON (M."ObjectID" = D."薬剤" AND
	M."Superseded" IS NULL)
    WHERE
    O."患者"=${pid} AND
    O."Superseded" IS NULL AND 
    O."処方開始日" + D."日数" >= '${new_order_start}' AND
    (O."停止日" IS NULL OR (O."停止日" >= '${new_order_start}'))
SQL;
  $db = mx_db_connect();
  return mx_db_fetch_all($db, $stmt);
}
// should be a method of an inherited class of list_editor
function check_all_accepted($le_list, $start_date=NULL) {
  global $__uiconfig_u_pharmacy_accepted;
  global $_mx_rx_use_inhibit;

  $errors = array();
  $ih_drugs = NULL;
  
  $max_days = array();
  $daily_amount = array();

  // include drugs currently editing
  foreach($le_list as $row_obj){
    if (get_class($row_obj) != 'med')
      continue;
    $row = $row_obj->med;
    if(is_null($row['accept']) or
       !array_key_exists($row['accept'], $__uiconfig_u_pharmacy_accepted))
      $errors[] = sprintf("%sは採用されていません",
			  $row["レセプト電算処理システム医薬品名"]);
  }

  if(!$_mx_rx_use_inhibit)
    return implode('<br>', $errors);

  // inhibit & daily max, days checking
  foreach($le_list as $row_obj) {
    $row = $row_obj->med;
    $row["日数"] = mb_convert_kana($row["日数"], "a");
    $row["用量"] = mb_convert_kana($row["用量"], "a");

    if (get_class($row_obj) == 'direction') {
      if(is_array($_meds))
	foreach($_meds as $m) {
	  $m["用量"] = mb_convert_kana($m["用量"], "a");
	  $total_amount[$m["レセプト電算処理システムコード（１）"]] += $m["用量"] * $row["日数"];
	  $max_days[$m["レセプト電算処理システムコード（１）"]] = $row["日数"];
	}
      $_meds = NULL;
      continue;
    }
    
    $_meds[] = $row;
    $mnames[$row["レセプト電算処理システムコード（１）"]] = $row["レセプト電算処理システム医薬品名"];
    $daily_amount[$row["レセプト電算処理システムコード（１）"]] = 
      max($daily_amount[$row["レセプト電算処理システムコード（１）"]],
	  $row["用量"]);
  }
  $db = mx_db_connect();
  foreach($daily_amount as $m => $a) {
    if(is_null($a) or $a == "")
      continue;
    $a = mb_convert_kana($a, "a");
    $qm = mx_db_sql_quote($m);
    $stmt = "SELECT receipt_system_code, max_daily_amount_comment FROM max_amount WHERE receipt_system_code = $qm AND $a > max_daily_amount";
    $r = mx_db_fetch_single($db, $stmt);
    if ($r) 
      $errors[] = sprintf("%sの最大１日量は%sです", $mnames[$m], $r['max_daily_amount_comment']);
  }
  
  foreach($max_days as $m => $a) {
    if(is_null($a) or $a == "")
      continue;
    $a = mb_convert_kana($a, "a");
    $qm = mx_db_sql_quote($m);
    $stmt = "SELECT receipt_system_code, max_days_comment FROM max_amount WHERE receipt_system_code = $qm AND $a > max_days";
    $r = mx_db_fetch_single($db, $stmt);
    if ($r) 
      $errors[] = sprintf("%sの最大日数は%sです", $mnames[$m], $r['max_days_comment']);
  }
  
  // get drugs from existing orders only when start_date is set
  if($start_date) {
    $drugs = get_drugs_in_past_orders($start_date, 0);
    $shots = get_drugs_in_past_orders($start_date, 1);
    $ih_drugs = array_merge($drugs, $shots);
  }
  
  // include drugs currently editing
  foreach($le_list as $row_obj){
    if (get_class($row_obj) != 'med')
      continue;
    $row = $row_obj->med;
    $ih_drugs[] = $row;
  }

  // Finally run inhibit check
  $ih_msg = '';

  if(is_array($ih_drugs) and count($ih_drugs) > 0) {
    $drug_codes = NULL;
    foreach($ih_drugs as $ihd)
      $drug_codes[$ihd["レセプト電算処理システムコード（１）"]] = 1;
    $ih = new Inhibit();
    $ih->check_combination(array_keys($drug_codes));
    $ih_msg = $ih->to_string($ih_drugs);
  }
  $errors[] = $ih_msg;
  return implode('<br>', $errors);
}

function check_order_sanity($var,$check_ih=FALSE) {
  global $le;
  /* Rules:
   0: there must be 1 row at least
   1: start with a med
   2: end with a direction or gaiyo-med
   3: direction is only afer med
   4: direction for internal med needs days
   5: need direction before gaiyo med
   6: need direction between internal med and external med
   7: check yakuzai-shohousen.kouhatsuhin == yakuzai-shohousen-naiyou.generic_ok[]
   Rule Final check inhibit
  */
  // Rule 0
  if ($le->count == 0) {
    return "処方内容が一つもありません。";
  }
  // Rule 1
  $rows = $le->get_list();
  if (get_class($rows[0]) != 'med') {
    $le->set_error(0);
    return "先頭の行が薬剤ではありません";
  }
  // Rule 2
  if (get_class($rows[count($rows) - 1]) == 'med' && 
      !$rows[count($rows) - 1]->external) {
    $le->set_error(count($rows) - 1);
    return "用法を最後の行に追加してください";
  }

  // Rule 3,4,5,6, pre7
  $all_generic_ok = 0;
  for($i=0; $i < count($rows); $i++) {
    if (get_class($rows[$i]) == 'direction') {
      if (get_class($rows[$i-1]) != 'med') {
        $le->set_error($i);
	return sprintf("%d行目の用法は薬剤の後にしか入れられません", $i+1);
      }
      $dir = $rows[$i]->get_array();
      if (get_class($rows[$i-1]) == 'med' && !$rows[$i-1]->external &&
	  (!$dir['頓服'] || $dir['頓服'] == 0) &&
	  (! $dir['日数'] || $dir['日数'] == '')) {
        $le->set_error($i);
	return sprintf("%d行目の用法に、日数が入力されていません", $i+1);
      }
      $dir['日数'] = mb_convert_kana($dir['日数'], 'a');
      if ( (int)$dir['日数'] != $dir['日数'] || (int)$dir['日数'] <= 0) {
        $le->set_error($i);
	return sprintf("%d行目の用法の日数は正の整数で入力してください", $i+1);
      }
    }else if (get_class($rows[$i]) == 'med') {
      $m = $rows[$i]->get_array();
      $m['用量'] = mb_convert_kana($m['用量'], 'a');
      if ( !is_numeric($m['用量']) || $m['用量'] <=0) {
        $le->set_error($i);
	return sprintf("%d行目の用量は正の値を入力してください", $i+1);
      }
      if($rows[$i]->external && get_class($rows[$i-1]) == 'med'
	 && !$rows[$i-1]->external) {
        $le->set_error($i);
        $le->set_error($i+1);
	return sprintf("%d行目の内服薬と%d行目の外用薬の間に用法が必要です。", $i, $i+1);
      }
      $x = $rows[$i]->get_array();
      $all_generic_ok |= $x['generic_ok'];
    }
  }
  //  Rule 7
  if($var['i後発品'] == 0 and $all_generic_ok == 1)
    return "処方箋は後発品不可ですが、後発品可の薬剤があります。";
  if($var['isetflag'] and empty($var['isetcomment']))
    return "セット登録ではセット名が必須です";
}


function handle_db_action($var) {
  global $dberror;
  global $dbok;
  global $db_new_rx_id;
  global $show_edit_order;
  global $action;
  global $dbaction;
  global $pid;
  global $oid;
  global $u;

  $db = mx_db_connect();
  $dberror = $dbok = "";
  if ($dbaction == "dbnew") {
    $new_oid_return = 'please';
    if (!insert_pharm_order($var, &$new_oid_return)) {
      pg_query($db, "rollback");
      $dberror = ('<h1><font color="red">'.
		  '登録に失敗しました。'.
		  '再度登録を行ってください。'.
		  '</font></h1><br>');
    }
    else {
      $dbok = ('<h1><font color="green">'.
	       '登録しました。'.
	       '</font></h1><br>');
      $db_new_rx_id = $new_oid_return;
    }
  }
  else if ($dbaction == "dbupdate") {
    if (!update_pharm_order($var)) {
      pg_query($db, "rollback");
      $dberror = ('<h1><font color="red">'.
		  '更新に失敗しました。'.
		  '再度更新を行ってください。'.
		  '</font></h1><br>');
    }
    else {
      $dbok = ('<h1><font color="green">'.
	       '更新しました。'.
	       '</font></h1><br>');
    }
  }
  else if ($dbaction == "dbpreview") {
    setup_list_editor($var, 1);
    if($action != "update")
      $dberror = check_order_sanity($var);
  }
  else if ($dbaction == "dbstop") {
    $stop_date = $var['i停止日'];
    $stop_comment = $var['iComment'];

    $stop_by = $u;
    $stmt = <<<SQL
      UPDATE "薬剤処方箋"
      SET "停止日"='${stop_date}',
      "Comment"='${stop_comment}',
      "停止医"=(SELECT E."ObjectID"
		FROM "職員台帳" E
		WHERE E."Superseded" IS NULL AND E.userid=${stop_by})
      WHERE "Superseded" IS NULL 
      AND "ObjectID"=$oid
SQL;
    $db = mx_db_connect();
    if(!pg_query($db, $stmt)) {
      $dberror = ('<h1><font color="red">'.
		  '中止に失敗しました。'.
		  '再度中止操作を行ってください。'.
		  '</font></h1><br>');
    }
    else {
      $dbok = ('<h1><font color="green">'.
	       '中止しました。'.
	       '</font></h1><br>');
    }
  }

  $show_edit_order = 0;
  if (!$action)
    return;

  foreach ($var as $k => $v)
    if ($k == "new" || $k == "copy" || $k == "update" ||
	$k == 'delcont' ||
	$k == 'sort' || ereg("set", $k) || ereg("dp",$k) || ereg("drug",$k)){
      $go = true;
      break;
    }

  if ($pid && $go && !($dbaction && $dbaction != 'dbpreview' && 
		       $dberror == '')) 
    $show_edit_order = 1;

  if ($dbok != '') {
	  global $_mx_orca_send_rx_on_orderdate;

	  if ($dbaction == "dbstop") {
		  $pt = $pid;
		  $dt = $var['i停止日'];
	  } else {
		  $pt = $var['i患者'];
		  if ($_mx_orca_send_rx_on_orderdate)
			  $dt = $var['i処方年月日'];
		  else
			  $dt = $var['i処方開始日'];
	  }
	  mx_kick_claim_if_by_poid($db, $pt, $dt);
  }
}

function draw_flippage($prefix, $page) {
  $pages = array("薬剤・用法", "セット");
  // Flip Page.
  print "<table class=\"flippage\" width=\"100%\"><tr>";
  $page_num = -1;
    foreach ($pages as $page_name) {
      $page_num++;
      if ($page_num == $page) {
	print "<td class=\"focused ltcorner\">&nbsp;</td>";
	print "<td class=\"focused\">&nbsp;";
	print $page_name;
	mx_formi_hidden($prefix . 'page', $page_num);
	print "&nbsp;</td><td class=\"focused rtcorner\">&nbsp;</td>";
      } else {
	// A page that is hidden
	print "<td class=\"unfocused ltcorner\">&nbsp;</td>";
	print "<td class=\"unfocused\">";
	mx_formi_submit($prefix . 'page-to', $page_num, $page_name);
	print "</td><td class=\"unfocused rtcorner\">&nbsp;</td>";
      }
    }
    print "</tr></table>\n";
}
function show_rx_history($pat,$var,$dp,$dirp, &$setlist) {
  global $auth;
  global $rxlist;
  global $show_edit_order;
  global $dbaction;
  global $dberror;
  global $pt_hid;
  global $pt_outin;
  global $__uiconfig_u_pharmacy_qbe;
  global $__uiconfig_u_pharmacy_default_qbe;
  global $__uiconfig_u_pharmacy_outpatient_default;
  global $_mx_pdfgen_cmd2;
  global $u;
  global $_mx_rx_use_set;

  $page = !is_null($_REQUEST['rx-page-to']) ?  $_REQUEST['rx-page-to'] :  $_REQUEST['rx-page'];

  $pid = $pat['ID'] ? $pat['ID'] : $var['pid'];
  print "<input type=\"hidden\" name=\"pid\" value=\"$pid\">\n
         <button type=\"submit\" name=\"new\" value=\"1\">
         新規処方作成</button>\n";
  print "<hr>";

  if ($pid) {
    $drugpick_cfg = u_pharmacy_rx_order_drugpick_cfg($pt_outin);
    $drugpick_cfg['u'] = $u;
    $dp =  new drugpick('dp-', $drugpick_cfg);
    $yoho_cfg = array('ROW_PER_PAGE' => 200,
                      'SCROLLABLE_HEIGHT' => "140px",
		      );
    if ($dp->chosen()) {
      $types = array('内' => 1, '外' => 2, '注' => 4);
      $k = mx_form_unescape_key($dp->chosen());
      $type = $types[trim($k[6])];
      if ($type)
	$yoho_cfg['MED_TYPE'] = $type;
    }
	
    if ($show_edit_order  && !($dbaction == 'dbpreview' && $dberror == '')) {
      if ($_mx_rx_use_set)
	draw_flippage('rx-', $page);
      else
	$page = 0;
      if($page == 1) {
	$setlist->draw();
	if($setlist->chosen_for_show) {
	  $meds = get_meds($setlist->chosen_for_show,0);
	  print '<pre>';
	$ord['後発品']=0;
	  print join("\n", set_body($meds,1,$ord['後発品']));
	  print "\n";
	  print '</pre>'; 
	}else if($setlist->chosen_for_do) {
	  
	}
      }else{
	$dp->draw();
	print "<br>\n";
	//用法選択
	mx_titlespan('用法選択', 'small_heading');
	$directionlist = new list_of_pharmacy_directions('direction-list-',
							 $yoho_cfg);
	$directionlist->draw();
      }
    }

    if (!$show_edit_order || 1) {
      print "<br />\n";
      mx_titlespan('薬剤処方歴', 'small_heading');
      print "&nbsp;&nbsp;";
      $me = $_SERVER['PHP_SELF'];
      $match = array();
      $path = 'u/pharmacy/exec-calendar.php';
      if (preg_match('/^(\/au\/[^\/]+\/)(.*)$/', $me, &$match)) {
	$cookie = $match[1];
	$pid = '?SetPatient=1&amp;PatientID=' . htmlspecialchars($pt_hid);
	$ap = htmlspecialchars($cookie.$path).$pid;
	print '<a target=_blank href="'.$ap.'">薬歴リスト</a>';
      }
      $rxlist->draw();
    }

  }
}

function show_rx ($var) {
  global $class, $open_print_script, $_mx_pdfgen_cmd,  $_mx_pdfgen_cmd2, $db_new_rx_id;
  global $_mx_meds_comment;
  global $_mx_inhosp_rx_print;
  global $_mx_rx_print;
  global $_mx_allow_modify_rx;
  global $_mx_rx_control_print;
  global $pt_outin;
  global $_mx_allow_cancel_rx;
  global $_mx_rx_funsai;
  global $_mx_hack_takamiya;
  if ($db_new_rx_id)
    $oid = $db_new_rx_id;
  else
    $oid = $var['detail'] ? $var['detail'] : $var['det-in'];
  $open_print_script = '';

  if ($oid) {
    $ord = get_pharm_order($oid);
    $meds = get_meds($oid,0);
    if ($meds) {
      $class = "";
      foreach($meds as $med)
	if (check_drug($med['medis'])) {
	  $class = 'class="historical-data"';
	  break;
	}
    }
    print '<table '.$class.'>';
    print '<tr><td align=left>';
    if ($_mx_hack_takamiya) {
      if ($_mx_allow_modify_rx)
	print '<td align=left><button style="height:50; width:50;" type="submit" name="update" value="'.$oid."\">修正</button></td>\n";
      if ($_mx_allow_cancel_rx)
	print '<td align=left><button style="height:50; width:50;" type="submit" name="stop" value="'.$oid."\">中止</button></td>\n";
      print '<td><button style="height:50; width:50;" type="submit" name="copy" value="'.$oid."\"> Do </button></td>\n";
    }else{
      print '<td><button style="height:50; width:50; background-color: #bfb" type="submit" name="copy" value="'.$oid."\"> Do </button></td>\n";
      if ($_mx_allow_cancel_rx)
	print '<td align=left><button style="height:50; width:50; background-color: #fbb"  type="submit" name="stop" value="'.$oid."\">中止</button></td>\n";
      if ($_mx_allow_modify_rx)
	print '<td align=left><button style="height:50; width:50; background-color: #ffb" type="submit" name="update" value="'.$oid."\">修正</button></td>\n";
    }
    $open_print_script = 
      "window.open('print.php?oid={$oid}','','width=640,height=640')";
    print "<td><button type=\"button\" style=\"height:50\"  OnClick=\"$open_print_script\">処方ラベル印刷</button></td>\n";
    if($_mx_inhosp_rx_print) {
      $open_print2_script = 
	"window.open('print_inhosp.php?oid={$oid}','','width=640,height=640')";
      print "<td><button type=\"button\" style=\"height:50\"  OnClick=\"$open_print2_script\">院内処方印刷</button></td>\n";
    }
    if ($_mx_rx_control_print && $pt_outin == 'I')
      print "<td><button type=\"submit\" style=\"height:50\"  name='print_toukan'\" value=\"$oid\">投薬管理簿印刷</button></td>\n";
//1117-2011

      $open_print2_script = 
	"window.open('print_inhosp2.php?oid={$oid}','','width=640,height=640')";
      print "<td><button type=\"button\" style=\"height:50\"  OnClick=\"$open_print2_script\">IN処方印刷</button></td>\n";

//1117-2011
    if ($_mx_rx_print)
      print "<td><button type=\"submit\" style=\"height:50\"  name='print' value=\"$oid\">入院処方箋</button></td>\n";
    print "</tr></table>";
    print "<table>";
    get_order_history("薬剤処方箋",$oid,"pill");
    $ord['後発品']=0;
    print '</table>';
    print '<input type=hidden name="det-in" value="'.$oid.'">
           <table '.$class."><tr><th>処方箋ID{$oid}";
    $name = get_emp_name($ord['記録者']);
    if (!$_mx_hack_takamiya)
      $tr = get_teiki_rinji($ord['定期臨時']);
    print "<td>{$name['lname']}&nbsp;{$name['fname']}";
    print "<tr><th nowrap>処方日<td>{$ord['処方年月日']}
               <th nowrap>開始日<td>{$ord['処方開始日']}
           <td>{$ord['区分']}${tr}";
//    $ord['後発品'] ? print "<th>後発品可<td>" : print "<th>後発品不可<td>";
    if ($_mx_rx_funsai && $ord['funsai'])
      print "<th>粉砕";

    if ($ord['停止医']) {
      $name = get_emp_name($ord['停止医']);
      print "<tr><th>停止医<td>{$name['lname']}&nbsp;{$name['fname']}
               <th>停止日<td>{$ord['停止日']}";
    }
    if($_mx_meds_comment){
      print '<tr><td><b>コメント</b></td><td colspan=3>'.$ord['Comment'].'</td></tr>';
    }

    print '</table>
    <div id="div_past" style="overflow: auto; height: 300px;">
    <table '.$class.'><pre>';
    /* 内容の表示 */
    print join("\n", set_body($meds,1,$ord['後発品']));
    print "\n";
    print_rx_exec_records($oid);
    print '</pre></table>
    </div>';
  }
}

function setup_list_editor($var, $preview=0){
  global $dp;
  global $le;
  global $_mx_rx_generic_ok;
  /*
   if the key name in $var starts with "med...", these are the contents
   of the order. Divide them into individual contents, and put them in
   the list-editor.
  */
  for ($slot = 0; $slot < $var['max_row'] ; $slot++) {

    if (array_key_exists('delcont' , $var) &&
	$slot == $var['delcont']){
      if($slot < $le->cursor_pos)
	$le->cursor_pos--;
      continue;
    }

    $row = array();
//0328-2012
    foreach (array('medis', 'レセプト電算処理システム医薬品名',
		   'レセプト電算処理システムコード（１）',
		   '用量', '用法分類','その他コメント', 'freqid',
		   '日数', '頓服','一包', '区分','定期臨時','accept', 'generic_ok', '用量単位','薬価基準収載医薬品コード') as $key) {
      if(array_key_exists("med{$slot}".$key, $var))
	$row[$key] = $var["med{$slot}" . $key];
    }
    if(array_key_exists('medis', $row))
      $le->add(new med($row));
    else
      $le->add(new direction($row));
  }

  // add a new med
  if ($_POST['dp-sel-id-select']) {
    $k = mx_form_unescape_key($dp->chosen());
    $med = array();
    $med['medis'] = $k[0];
    $med['レセプト電算処理システム医薬品名'] = $k[2];
    $med['用量単位'] = $k[5];
    $med['区分'] = trim($k[6]);
    $med['accept'] = trim($k[7]);
    $med['レセプト電算処理システムコード（１）'] = $k[8];
    //$med["薬価基準収載医薬品コード"] = $k[9];
    $med['generic_ok'] = $_mx_rx_generic_ok;
    $le->insert_at_cursor(new med($med));
  } else if ($var['rp-select']) {
    $rpmeds = get_meds($var['rp-select'],2);
    foreach($rpmeds as $med)
      $le->insert_at_cursor( new med($med));
  } else if ($_POST['direction-list-id-select']) {
    $k = mx_form_unescape_key($_POST['direction-list-id-select']);
    $row = array();
    $row['freqid'] = $k[0];
    if($k[1] == 1)
      $row['頓服'] = 0;
    $le->insert_at_cursor(new direction($row));
  }
  $le->header = check_all_accepted($le->get_list(), $var['i処方開始日']);
  // set cursor pos where a row was deleted
  if (array_key_exists('delcont' , $var))
    $le->cursor_pos = $var['delcont'];
}

function construct_meds(){
  global $le;
  $con = mx_db_connect();

  $freq_str = 'select "ObjectID", "用法" as val, "頓服" from "処方箋用法" where "Superseded" is NULL order by "ObjectID"';

  // lookup db
  $freqs = array();
  $res = pg_query($con,$freq_str) or die(pg_last_error());
  while ($row = pg_fetch_assoc($res)) {
    $freqs[$row['ObjectID']] = $row['val'];
  }
  pg_free_result($res);
  // construct $meds, and show Rx
  $meds = array();
  foreach($le->get_list() as $row) {
    $cls = get_class($row);
    if($cls == 'med'){
      $med = $row->get_array();
      //XXX: HACK HACK!
      $med['shapeid'] = 1;
      $meds[] = $med;
    }else if($cls == 'direction'){
      foreach($row->get_array() as $k => $v){
	$meds[count($meds) -1 ][$k] = $v;
	if($k=='freqid')
	  $meds[count($meds) -1 ]['用法'] =
	    $freqs[$v];
      }
    }
  }
  return $meds;
}

function manage_med_contents($var,$dp,$dirp, $ord, &$setlist) {
  global $action;
  global $dbaction;
  global $dberror;
  global $class;
  global $oid;
  global $__mx_formi_dek;
  global $le;
  global $_mx_rx_order_multiple_do;
  global $ih_msg;

  if ($var["new"]) {
    // discard old contents; do nothing
    $le->header = check_all_accepted($le->get_list(), date('Y-m-d'));
  }else if ($var["copy"] || $var["update"] || $setlist->chosen_for_do) {
    /* 
     the request was posted from the left pane, get the current contents
     from the order.
    */
    // Per request from Minoru , allow multiple Do 
    if ($_mx_rx_order_multiple_do || $setlist->chosen_for_do)
      setup_list_editor($var);
    if($setlist->chosen_for_do)
      $meds = get_meds($setlist->chosen_for_do,0);
    else
      $meds = get_meds($oid,0);
    if($meds) 
      foreach($meds as $row){
        $m = new med($row);
	if (is_null(!$row['用量単位']) and ($row['用量単位'] != $row['old用量単位']))
	   $m->unit_warning = 1;
	$le->add($m);
	if($row['freqid'])
	  $le->add(new direction($row));
      }
    $le->header = check_all_accepted($le->get_list(), date('Y-m-d'));
    // do not preserve cursor position. 
    $le->set_cursor($le->count);
  }else if($var["stop"]){
    // read out order, and make a preview
    $meds = get_meds($oid,0);
    $a = set_body($meds,0,$ord['後発品']);
    print "<pre>";
    print join("\n", $a);
    print "</pre>";
    return;
  }else{
    // normal editing
    // very much spaghetty with too many flags and combinations
    if($dbaction != 'dbpreview')
      setup_list_editor($var);
  }

  // draw preview or form
  if($dbaction == 'dbpreview' && $dberror == '') {

    $meds = construct_meds();
    dump_hidden($meds);
    // needs dbcommit button or back button
	$ord['後発品']=0;
    $a = set_body($meds,0,$ord['後発品']);
    print "<pre>";
    print join("\n", $a);
    print "</pre>";
  }else{
    $le->draw();
    printf('<INPUT TYPE=HIDDEN NAME="max_row" VALUE="%d">', $le->count);
  }
}

function show_edit_order($var,$dp, $dirp, &$setlist) {
  global $__mx_formi_dek;
  global $_mx_iji;
  global $_mx_meds_comment;
  global $action;
  global $dbaction;
  global $auth;
  global $dberror;
  global $dbok;
  global $oid;
  global $__uiconfig_pharmacy_rx_show_stop_doctor;
  global $show_edit_order;
  global $pt_outin;
  global $_mx_rx_type_enum;
  global $_mx_rx_show_noclaim;
  global $_mx_rx_order_record_doctor;
  global $_mx_rx_generic_ok;
  global $_mx_rx_funsai;
  global $_lib_u_pharmacy_common_tr;
  global $_mx_rx_fixed_kubun;
  global $_mx_rx_innai_ingai;
  global $_mx_rx_teikirinji;
  global $pid;
  global $_mx_rx_use_set;
  global $_mx_hack_takamiya;

  $readonly = $dbaction == 'dbpreview' && $dberror == '' ? ' READONLY ' : '';


  if ($dbok != '') {
    print $dbok;
  }

  if (!$action) return;

  if ($show_edit_order) {
    if ($var['update'] || $var['copy'] || $var['new']) {
      if($oid) {
	// existing order
	$ord = get_pharm_order($oid);
	if($var['copy']) {
	  $ord['停止日'] = NULL;
	  $ord['停止医'] = NULL;
	  // NEEDSWORK: nullify pharmacist, etc
	}
      }
      else {
	// new order
	$ord = array();
	// set default value generic_ok 03-20-2012
	 $ord["後発品"] = $_mx_rx_generic_ok;
//0328-2012	$ord["後発品"] = 1;
	$ord['区分'] = '院外';
	if ($_mx_rx_innai_ingai)
	  $ord['区分'] = $_mx_rx_innai_ingai == 1 ? "院内" : "院外";
	else if($pt_outin == 'I') 

// 0603-2011

	  $ord['区分'] = "院内";
	else if ($pt_outin == 'O')

//06-03-2011
	  $ord['区分'] = "院内";
	$ord['定期臨時'] = $_mx_rx_teikirinji;
      }
    } else
      foreach ($var as $k => $v)
	if (ereg("^i.*",$k)) $ord[substr($k,1)] = $v;

    print "<input type=hidden name=oid value={$oid}>";
    print "<input type=hidden name=action value={$action}>";

    if ($var["copy"]) { $ord["処方年月日"] = $ord["処方開始日"] = date("Y-m-d"); }

    $btn_value = '';
    $btn_label = '';

	$ord["後発品"] = 1;
    if($_mx_hack_takamiya)
      $btn_back = '<button type="button" onClick="window.history.back();">戻る</button>';
    else
      $btn_back = '<button style="height: 50" type="button" onClick="window.history.back();">戻る</button>';

    if($action == "stop" || ($dbaction == 'dbpreview' && $dberror == '')){
      if($action == "update"){
	$btn_value = "dbupdate";
	$btn_label = "処方箋ID{$oid}修正";
      }else  if($action == "new" || $action == "copy"){
	$btn_value = "dbnew";
	if($_mx_rx_show_noclaim and $ord['noclaim'])
	  $btn_label = "持込薬登録";
	else if($_mx_iji)
	  $btn_label = "医事送信/登録";
	else
	  $btn_label = "処方登録";
      }else if($action == "stop") {
	$btn_value = "dbstop";
	$btn_label = "中止を実行します";
	print "<table>";
	get_order_history("薬剤処方箋",$oid,"pill");
	print '</table>';
	print '<input type=hidden name="det-in" value="'.$oid.'">
           <table '.$class."><tr><th>処方箋ID{$oid}";
	$name = get_emp_name($ord['記録者']);
	$tr = get_teiki_rinji($ord['定期臨時']);
//03-20-2012
	$ord["後発品"] = 1;
	print "<td>{$name['lname']}&nbsp;{$name['fname']}";
	print "<tr><th nowrap>処方日<td>{$ord['処方年月日']}
               <th nowrap>開始日<td>{$ord['処方開始日']}
           <td>{$ord['区分']} ${tr}";
	$ord['後発品'] ? print "<th>後発品可<td>" : print "<td>後発品不可<td>";

	if ($ord['停止医']) {
	  $name = get_emp_name($ord['停止医']);
	  print "<tr><th>停止医<td>{$name['lname']}&nbsp;{$name['fname']}
               <th>停止日<td>{$ord['停止日']}";
	}
	if($_mx_meds_comment){
	  print '<tr><td><b>コメント</b></td><td colspan=3>'.$ord['Comment'].'</td></tr>';
	}
	print '</table>';

	print "<div id='div_meds' style=\"overflow: auto; height: 550px; \">";
	manage_med_contents($var,&$dp, &$dirp,&$ord, $setlist);
	print "中止日";
	mx_formi_date("i停止日",
		      ($ord["i停止日"] ? $ord["i停止日"]
		       : date("Y-m-d")), NULL);
	$start_date = $ord['処方開始日'];
	print "<br><br>";
	print <<<HTML
	  <font color="red"><b>
	  上記の日付以降の処方を中止します。<br>
	  ※${start_date}より中止日前日までの処方は実施済みとして医事送信します<br>
			   </b></font><br>
			   中止理由 <select name="iComment">
			   <option value="処変により中止">処変
			   <option value="入力ミスにより中止" selected>入力ミス
			   </select>
			   <br>
			   <br>

HTML;
	print '<button style="background-color: #fdc ; color:black; height: 50px;" type="submit" name="dbaction" value="'. $btn_value .'">';
	print $btn_label;
	print '</button>';
	print "</div>\n";
	return;

      }
    }else{
      $btn_value = "dbpreview";
      $btn_label = "確認画面へ進む";
      $btn_back='';
    }

    print $btn_back;
    if ($_mx_hack_takamiya) 
      print '<button style="background-color: #fdc ; color:black;" type="submit" name="dbaction" value="'. $btn_value .'">';
    else
      print '<button style="background-color: #fdc ; color:black; height: 50" type="submit" name="dbaction" value="'. $btn_value .'">';
    print $btn_label;
    print '</button>';

    if ($_mx_rx_order_record_doctor) {
      if ($ord['i記録者'])
	    $rec['id'] = $ord['i記録者'];
      else if ($_REQUEST['i記録者'])
	    $rec['id'] = $_REQUEST['i記録者'];
      else
	    $rec['id'] = $auth[2]['ObjectID'];
    } else {
      $rec['id'] = $auth[2]['ObjectID'];
    }
    $rec['name'] = get_emp_name($rec['id']);
    $current_user['id'] = $auth[2]['ObjectID'];
    $current_user['name'] = get_emp_name($current_user['id']);
    if ($_mx_rx_use_set) {
      print ' セット登録';
      mx_formi_checkbox("isetflag", $ord['setflag'], NULL);
      mx_formi_text("isetcomment", $ord['setcomment'], NULL);
    }
    if ($_mx_rx_show_noclaim) {
      print ' 持込薬';
      mx_formi_checkbox("inoclaim", $ord['noclaim'], NULL);
    }
    if ($_mx_rx_use_set)
      print '<br>';
    print '処方日';
# NEEDSWORK: add readonly mode by $readonly
    mx_formi_date("i処方年月日",
		  ($ord["処方年月日"] ? $ord["処方年月日"]
		   : date("Y-m-d")), NULL);
    if ($_mx_rx_order_record_doctor && $pid) {
      print '処方医';
      list_doctors("i記録者",$rec['id'],NULL,"doctor",$current_user);
    } else {
      print "<input type=\"hidden\" name=\"i記録者\" value=\"{$rec['id']}\">";
    }

    if ($_mx_rx_use_set)
      print '<br />';
    print '開始日';
    mx_formi_date("i処方開始日",
		  ($ord["処方開始日"] ? $ord["処方開始日"]
		   : date("Y-m-d")), NULL);

    if($readonly != '') {
      print "\n";
      print '後発品 ';
	$ord["後発品"] = 1;
      print '<input type=text value="'. ($ord['後発品'] == 1 ? '可' : '不'). '" size=2 readonly>';
      print "\n";
      print '<input type=hidden name="i後発品" value="'.$ord['後発品'].'">';
      print "\n";
      print '区分 ';
      print '<input type=text name="i区分" value="'.$ord['区分'].'" size=4 readonly>';
      $tr = get_teiki_rinji($ord['定期臨時']);
      print '<input type=hidden name="i定期臨時" value="'.$ord['定期臨時'].'" size=4 readonly>';
      if (!$_mx_hack_takamiya)
	print $tr;
      if($_mx_meds_comment) {
	print '<br><b>コメント</b>';
	print '<pre>' . $ord['Comment'] . '</pre>';
	mx_formi_hidden("iComment", $ord['Comment']);
      }
      if ($_mx_rx_funsai && $ord['funsai'])  {
	print " 粉砕";
	mx_formi_hidden("ifunsai", $ord['funsai']);
      }
    }else{
      print "\n";
//03-20-2012
	$ord["後発品"] =1;
      print '後発品<select ONCHANGE="update_generic_ok(this)" name="i後発品"'.$__mx_formi_dek . '>
         <option value="1"' . ($ord['後発品'] == 1 ? "selected" : "") . '>可
         <option value="0"' . ($ord['後発品'] == 0 ? "selected" : "") . '>不</select>';

      $fixed_kubun = ($_mx_rx_fixed_kubun != '' &&
		      strchr($_mx_rx_fixed_kubun, $pt_outin) !== FALSE);

      if ($fixed_kubun) {
	      print '区分: ' . $ord['区分'];
	      mx_formi_hidden('i区分', $ord['区分']);
      } else {
	      print '区分 <select name="i区分" '.$__mx_formi_dek. ' >';
	      if ($_mx_rx_type_enum=='T') {
//0604-2011		


if ($pt_outin == 'O') {
		  print '<option value="外来" '.($ord['区分'] == "外来" ? "selected" : "").'>外来';
		} else {
		  print '<option value="定期" '.($ord['区分'] == "定期" ? "selected" : "").'>定期
		 <option value="臨時" '.($ord['区分'] == "臨時" ? "selected" : "").'>臨時
		 <option value="退院時" '.($ord['区分'] == "退院時" ? "selected" : "").'>退院時';
		}




	      } else {




		print '<option value="院外" '.($ord['区分'] == "院外" ? "selected" : "").'>院外
		 <option value="院内" '.($ord['区分'] == "院内" ? "selected" : "").'>院内';
		// print '<option value="自費" '.($ord['区分'] == "自費" ? "selected" : "").'>自費';
	      }
	      print '</select>';
      }

      // teiki-rinji
      $v = $_REQUEST['i定期臨時'] ? $_REQUEST['i定期臨時'] : $ord['定期臨時'];
      if ($_mx_hack_takamiya)
	mx_formi_hidden("i定期臨時", $v);
      else
	mx_formi_select("i定期臨時", $v, $_lib_u_pharmacy_common_tr);

      // 粉砕指示
      if($_mx_rx_funsai) {
	print "粉砕";
	mx_formi_checkbox("ifunsai", $ord['funsai'], NULL);
      }
      if($_mx_meds_comment) {
	print '<br><b>処方箋コメント</b>';
	mx_formi_textarea("iComment", $ord['Comment'], array('rows'=>2,'cols'=>80));
      }
    }
    print '<input type="hidden" name="i患者" value="'.$pid.'">';

    if ($__uiconfig_pharmacy_rx_show_stop_doctor) {
      print ' 停止日 <input type="text" maxlength="10" size="10" 
		 name="i停止日" value="' . $ord["停止日"] . '" '.
	$__mx_formi_dek.'>
		   停止医
		 <input type="hidden" name="i患者" value="'.$pid.'">';
      list_doctors("i停止医",$ord['停止医'],$pid,"all0",$rec);
    }
    else {
      print '<input type="hidden" name="i停止日" value="'.
	$ord["停止日"].'">';
      print '<input type="hidden" name="i停止医" value="'.
	$ord['停止医'].'">';
    }
    //print "</table>\n";
    print "<hr/>";

    if ("{$dbok}{$dberror}" != '') {
      print "<FONT COLOR='RED'><b>{$dberror}</b></FONT><br>\n";
    }


    //print "<div id='div_meds' style=\"overflow: auto; height: 550px; \">";
    manage_med_contents($var,&$dp, &$dirp,&$ord, $setlist);
    //print "</div>\n";
  }
}

class phoney_rx_class extends phoney_ppa {
}

function draw_main_content() {
  global $pid;
  global $dp;
  global $pat;
  global $pt_hid;
  global $auth;
  global $u;
  global $_mx_use_appbar;

  print '<table border="0"><tr><td valign="top"  width="40%">';
  mx_titlespan($auth[1], 'appname');
  if (!$_mx_use_appbar)
    draw_back('../../index.php');
  mx_draw_userinfo();
  print '</td>';

  if ($pid && mx_authorize_patient_access($pid)) {
    $patient_access_denied = 1;
    $pid = NULL;
  }
  if ($pid && !mx_hide_patient_selection()) {
    print '<td class="ptsel"> <form method="POST">患者ID:';
    mx_formi_text('PatientID', trim($pt_hid),
		  array('ime' => 'disabled'));
    mx_formi_pt_submit('SetPatient');
    mx_draw_patientinfo_brief($pid);
    print "</form>";

  }

  if (!$_mx_use_appbar)
    mx_draw_ppa_applist($pt_hid);
  print '</td></tr></table>';
  if ($_mx_use_appbar) {
    $me = new phoney_rx_class($u, $pt_hid, $pid);
    mx_appbar($me);
  }
  print '<hr />';

  if ($patient_access_denied) {
    print "<br />指定された患者にはアクセスできません";
    return;
  }

  handle_db_action($_REQUEST);

  print "<form method=\"post\" action=\"$uri\">\n";
  print '<table style="border-collapse: collapse; border: hidden">
       <tr><td width="410px" valign="top" style="border-right: dotted 1px">'."\n";

  $setlist = new list_of_pharmacy_rxs('set-list-', $pid,
				      array('SETONLY' => 1,
					    'AUTH' => $auth));
  show_rx_history($pat,$_REQUEST,&$dp,&$dirp, $setlist);
  print "<hr>";
  show_rx($_POST);
  print "\n<td valign=\"top\">";
  show_edit_order($_REQUEST,&$dp,&$dirp, &$setlist);

  print "</tr></table></form>\n";
}

function print_rx_exec_records($oid)
{
	global $_mx_show_exec_in_rx_order;

	if (!$_mx_show_exec_in_rx_order)
		return;

	$medsarray = get_meds($oid, 0);
	$opid = array();
	$meds = array();
	foreach ($medsarray as $m) {
		$opid[] = $m['medid'];
		$meds[$m['medid']] = $m;
	}
	$opid = implode(', ', $opid);
	$opid = 'AND "薬剤処方箋内容" IN (' . $opid . ')';
	$stmt = <<<SQL
		SELECT X."ObjectID", X."実施日", X."実施者",
		X."薬剤処方箋内容", X."特記事項", X.recorded,
		E."姓", E."名"
		FROM "薬剤実施記録" AS X
		LEFT JOIN "職員台帳" AS E
		ON E."ObjectID" = X."実施者"
		WHERE X."Superseded" IS NULL
		$opid
		ORDER BY X."薬剤処方箋内容", X.recorded
SQL;
	$db = mx_db_connect();
	$execution = mx_db_fetch_all($db, $stmt);
	if (!is_array($execution) || !count($execution))
		return;

	print "実施記録\n\n";

	foreach ($execution as $x) {
		$med = $meds[$x['薬剤処方箋内容']];
		$medname = trim($med['レセプト電算処理システム医薬品名']);
		$n = $x['姓'] . ' ' . $x['名'];
		$t = mx_format_timestamp($x['recorded'], 0);
		$e = $x['特記事項'];
		print "$medname $t $n\n";
		if (trim($e) != '') {
			$lead = '特記事項: ';
			foreach (explode("\n", $e) as $l) {
				$l = trim($l);
				print "$lead$l\n";
				$lead = '          ';
			}
		}
	}
}

//---------------------------- RUN! RUN! -------------------------
$u = $_REQUEST['u'] = mx_authenticate_user();
$auth = mx_authorization();
if (! $auth[0]) {
  mx_authorization_error($auth);
  return;
 }

// create a list-editor
$le = new list_editor();
// need to get cursor_pos in order to jumpTo

if ($_POST['cursor_pos']){
  $le->set_cursor($_POST['cursor_pos']);
 }


$action = $_POST['new'] ? "new" : ($_POST['copy'] ? "copy" :
				   ($_POST['update'] ? "update" :
				    ($_POST['stop'] ? "stop" :
				     $_POST['action'])));

if(is_null($action)) {
  $action = 'new';
  $_POST['new'] = 1;
  $_REQUEST['new'] = 1;
 }

$class = "";
$dbaction = $_POST['dbaction'];
$oid = $_POST['update'] ? $_POST['update'] : ($_POST['copy'] ? $_POST['copy'] :
					      ($_POST['stop'] ? $_POST['stop'] :
					       $_REQUEST['oid']));
$pid = $_REQUEST['pid'];
$pid_changed = 0;
$uri = $_SERVER['SCRIPT_NAME'];

//--------------------------------------------------------------------
// drawing start
//--------------------------------------------------------------------

mx_html_head($auth[1]);
if ($action == 'copy')
  print "<body>";
else
  print "<body onLoad=\"jumpTo();\">";
if($_REQUEST['print'])
  go_pdf($_REQUEST['print'], 0);

if($_REQUEST['print_toukan'])
  go_pdf($_REQUEST['print_toukan'], 0, 'management_list_rx.ods');

if(!$pid) {
  if($_REQUEST['SetPatient'] && $_REQUEST['PatientID'])
    $_REQUEST['PID'] = $_REQUEST['PatientID'];

  $pat = search_patient("",$ym);

  if ($pat) {
    $pid = $pat['ObjectID'];
    $pid_changed = 1;
  }
 }
if ($pid) {
  $rxlist = new list_of_pharmacy_rxs('rx-list-', $pid);
  if ($pid_changed)
    $rxlist->reset(NULL);
  if ($_POST['rx-list-id-select']) {
    $k = mx_form_unescape_key($rxlist->chosen());
    $_POST['detail'] = $k[0];
    print "<!-- Found $k[0] -->\n";
  }
 }

$stmt = ('SELECT * FROM "患者台帳" WHERE "Superseded" IS NULL
	    AND "ObjectID" = ' . mx_db_sql_quote($pid));
$d = mx_db_fetch_single(mx_db_connect(), $stmt);
$pt_hid = $d['患者ID'];
$pt_outin = $d['入外区分'];
if($pt_hid and $pid)
  mx_draw_ppa_index($pid, $pt_hid);

draw_main_content();

// draw jumpto
if(!is_null($le->focusme)) {
  $rows = $le->get_list();
  $row_num = $le->focusme;
  if (get_class($rows[$row_num]) == 'med')
    $el = "med_amount$row_num";
  else
    $el = "dir_day$row_num";
  print "
<script>
element = document.getElementById('${el}');
element.focus();
</script>
";
 }

$jumpto = 'ins_pos' . $le->cursor_pos;
print "
<script>
function jumpTo()
{
	element = document.getElementById('$jumpto');

	if(element == null)
		return;

	pos = getPosition(element);
        if (pos.y > 700)
	  window.scrollTo(pos.x, pos.y - 350);
}
</script>
   ";
print "</body></html>";
?>
