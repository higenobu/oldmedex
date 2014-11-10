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
  return strcmp($a["����ǯ����"],$b["����ǯ����"]);
}
function type_sort ($a,$b) {
  return strcmp($b["��ʬ"],$a["��ʬ"]);
}
function id_sort ($a,$b) {
  return strcmp($a["oid"],$b["oid"]);
}
function canc_sort ($a,$b) {
  return strcmp($a["�����"],$b["�����"]);
}

function get_drugs_in_past_orders($new_order_start, $tbl) {
  global $pid;
  /* $tbl: 0 for drug, 1 for shots
   1.  order_start + interval duration < new_order_start
   2.  stop_date < today
  */
  $table = array( array("���޽����", "���޽��������"),
		  array("��ͽ����", "��ͽ��������") );
  
  $order_table = $table[$tbl][0];
  $detail_table = $table[$tbl][1];
  $stmt = <<<SQL
    SELECT
    O."ObjectID",
    O."����ǯ����",
    O."����������",
    O."����ǯ����",
	
    M."�쥻�ץ��Ż����������ƥ������̾",
    M."�쥻�ץ��Ż����������ƥॳ���ɡʣ���"
    FROM "${order_table}" as O
    JOIN "${detail_table}" as D
    ON (O."ObjectID" = D."${order_table}" AND
	O."Superseded" IS NULL)
    JOIN "Medis�����ʥޥ�����" M
    ON (M."ObjectID" = D."����" AND
	M."Superseded" IS NULL)
    WHERE
    O."����"=${pid} AND
    O."Superseded" IS NULL AND 
    O."����������" + D."����" >= '${new_order_start}' AND
    (O."�����" IS NULL OR (O."�����" >= '${new_order_start}'))
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
      $errors[] = sprintf("%s�Ϻ��Ѥ���Ƥ��ޤ���",
			  $row["�쥻�ץ��Ż����������ƥ������̾"]);
  }

  if(!$_mx_rx_use_inhibit)
    return implode('<br>', $errors);

  // inhibit & daily max, days checking
  foreach($le_list as $row_obj) {
    $row = $row_obj->med;
    $row["����"] = mb_convert_kana($row["����"], "a");
    $row["����"] = mb_convert_kana($row["����"], "a");

    if (get_class($row_obj) == 'direction') {
      if(is_array($_meds))
	foreach($_meds as $m) {
	  $m["����"] = mb_convert_kana($m["����"], "a");
	  $total_amount[$m["�쥻�ץ��Ż����������ƥॳ���ɡʣ���"]] += $m["����"] * $row["����"];
	  $max_days[$m["�쥻�ץ��Ż����������ƥॳ���ɡʣ���"]] = $row["����"];
	}
      $_meds = NULL;
      continue;
    }
    
    $_meds[] = $row;
    $mnames[$row["�쥻�ץ��Ż����������ƥॳ���ɡʣ���"]] = $row["�쥻�ץ��Ż����������ƥ������̾"];
    $daily_amount[$row["�쥻�ץ��Ż����������ƥॳ���ɡʣ���"]] = 
      max($daily_amount[$row["�쥻�ץ��Ż����������ƥॳ���ɡʣ���"]],
	  $row["����"]);
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
      $errors[] = sprintf("%s�κ��磱���̤�%s�Ǥ�", $mnames[$m], $r['max_daily_amount_comment']);
  }
  
  foreach($max_days as $m => $a) {
    if(is_null($a) or $a == "")
      continue;
    $a = mb_convert_kana($a, "a");
    $qm = mx_db_sql_quote($m);
    $stmt = "SELECT receipt_system_code, max_days_comment FROM max_amount WHERE receipt_system_code = $qm AND $a > max_days";
    $r = mx_db_fetch_single($db, $stmt);
    if ($r) 
      $errors[] = sprintf("%s�κ���������%s�Ǥ�", $mnames[$m], $r['max_days_comment']);
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
      $drug_codes[$ihd["�쥻�ץ��Ż����������ƥॳ���ɡʣ���"]] = 1;
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
    return "�������Ƥ���Ĥ⤢��ޤ���";
  }
  // Rule 1
  $rows = $le->get_list();
  if (get_class($rows[0]) != 'med') {
    $le->set_error(0);
    return "��Ƭ�ιԤ����ޤǤϤ���ޤ���";
  }
  // Rule 2
  if (get_class($rows[count($rows) - 1]) == 'med' && 
      !$rows[count($rows) - 1]->external) {
    $le->set_error(count($rows) - 1);
    return "��ˡ��Ǹ�ιԤ��ɲä��Ƥ�������";
  }

  // Rule 3,4,5,6, pre7
  $all_generic_ok = 0;
  for($i=0; $i < count($rows); $i++) {
    if (get_class($rows[$i]) == 'direction') {
      if (get_class($rows[$i-1]) != 'med') {
        $le->set_error($i);
	return sprintf("%d���ܤ���ˡ�����ޤθ�ˤ���������ޤ���", $i+1);
      }
      $dir = $rows[$i]->get_array();
      if (get_class($rows[$i-1]) == 'med' && !$rows[$i-1]->external &&
	  (!$dir['����'] || $dir['����'] == 0) &&
	  (! $dir['����'] || $dir['����'] == '')) {
        $le->set_error($i);
	return sprintf("%d���ܤ���ˡ�ˡ����������Ϥ���Ƥ��ޤ���", $i+1);
      }
      $dir['����'] = mb_convert_kana($dir['����'], 'a');
      if ( (int)$dir['����'] != $dir['����'] || (int)$dir['����'] <= 0) {
        $le->set_error($i);
	return sprintf("%d���ܤ���ˡ���������������������Ϥ��Ƥ�������", $i+1);
      }
    }else if (get_class($rows[$i]) == 'med') {
      $m = $rows[$i]->get_array();
      $m['����'] = mb_convert_kana($m['����'], 'a');
      if ( !is_numeric($m['����']) || $m['����'] <=0) {
        $le->set_error($i);
	return sprintf("%d���ܤ����̤������ͤ����Ϥ��Ƥ�������", $i+1);
      }
      if($rows[$i]->external && get_class($rows[$i-1]) == 'med'
	 && !$rows[$i-1]->external) {
        $le->set_error($i);
        $le->set_error($i+1);
	return sprintf("%d���ܤ���������%d���ܤγ������δ֤���ˡ��ɬ�פǤ���", $i, $i+1);
      }
      $x = $rows[$i]->get_array();
      $all_generic_ok |= $x['generic_ok'];
    }
  }
  //  Rule 7
  if($var['i��ȯ��'] == 0 and $all_generic_ok == 1)
    return "����䵤ϸ�ȯ���ԲĤǤ�������ȯ�ʲĤ����ޤ�����ޤ���";
  if($var['isetflag'] and empty($var['isetcomment']))
    return "���å���Ͽ�Ǥϥ��å�̾��ɬ�ܤǤ�";
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
		  '��Ͽ�˼��Ԥ��ޤ�����'.
		  '������Ͽ��ԤäƤ���������'.
		  '</font></h1><br>');
    }
    else {
      $dbok = ('<h1><font color="green">'.
	       '��Ͽ���ޤ�����'.
	       '</font></h1><br>');
      $db_new_rx_id = $new_oid_return;
    }
  }
  else if ($dbaction == "dbupdate") {
    if (!update_pharm_order($var)) {
      pg_query($db, "rollback");
      $dberror = ('<h1><font color="red">'.
		  '�����˼��Ԥ��ޤ�����'.
		  '���ٹ�����ԤäƤ���������'.
		  '</font></h1><br>');
    }
    else {
      $dbok = ('<h1><font color="green">'.
	       '�������ޤ�����'.
	       '</font></h1><br>');
    }
  }
  else if ($dbaction == "dbpreview") {
    setup_list_editor($var, 1);
    if($action != "update")
      $dberror = check_order_sanity($var);
  }
  else if ($dbaction == "dbstop") {
    $stop_date = $var['i�����'];
    $stop_comment = $var['iComment'];

    $stop_by = $u;
    $stmt = <<<SQL
      UPDATE "���޽����"
      SET "�����"='${stop_date}',
      "Comment"='${stop_comment}',
      "��߰�"=(SELECT E."ObjectID"
		FROM "������Ģ" E
		WHERE E."Superseded" IS NULL AND E.userid=${stop_by})
      WHERE "Superseded" IS NULL 
      AND "ObjectID"=$oid
SQL;
    $db = mx_db_connect();
    if(!pg_query($db, $stmt)) {
      $dberror = ('<h1><font color="red">'.
		  '��ߤ˼��Ԥ��ޤ�����'.
		  '�����������ԤäƤ���������'.
		  '</font></h1><br>');
    }
    else {
      $dbok = ('<h1><font color="green">'.
	       '��ߤ��ޤ�����'.
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
		  $dt = $var['i�����'];
	  } else {
		  $pt = $var['i����'];
		  if ($_mx_orca_send_rx_on_orderdate)
			  $dt = $var['i����ǯ����'];
		  else
			  $dt = $var['i����������'];
	  }
	  mx_kick_claim_if_by_poid($db, $pt, $dt);
  }
}

function draw_flippage($prefix, $page) {
  $pages = array("���ޡ���ˡ", "���å�");
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
         ������������</button>\n";
  print "<hr>";

  if ($pid) {
    $drugpick_cfg = u_pharmacy_rx_order_drugpick_cfg($pt_outin);
    $drugpick_cfg['u'] = $u;
    $dp =  new drugpick('dp-', $drugpick_cfg);
    $yoho_cfg = array('ROW_PER_PAGE' => 200,
                      'SCROLLABLE_HEIGHT' => "140px",
		      );
    if ($dp->chosen()) {
      $types = array('��' => 1, '��' => 2, '��' => 4);
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
	$ord['��ȯ��']=0;
	  print join("\n", set_body($meds,1,$ord['��ȯ��']));
	  print "\n";
	  print '</pre>'; 
	}else if($setlist->chosen_for_do) {
	  
	}
      }else{
	$dp->draw();
	print "<br>\n";
	//��ˡ����
	mx_titlespan('��ˡ����', 'small_heading');
	$directionlist = new list_of_pharmacy_directions('direction-list-',
							 $yoho_cfg);
	$directionlist->draw();
      }
    }

    if (!$show_edit_order || 1) {
      print "<br />\n";
      mx_titlespan('���޽�����', 'small_heading');
      print "&nbsp;&nbsp;";
      $me = $_SERVER['PHP_SELF'];
      $match = array();
      $path = 'u/pharmacy/exec-calendar.php';
      if (preg_match('/^(\/au\/[^\/]+\/)(.*)$/', $me, &$match)) {
	$cookie = $match[1];
	$pid = '?SetPatient=1&amp;PatientID=' . htmlspecialchars($pt_hid);
	$ap = htmlspecialchars($cookie.$path).$pid;
	print '<a target=_blank href="'.$ap.'">����ꥹ��</a>';
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
	print '<td align=left><button style="height:50; width:50;" type="submit" name="update" value="'.$oid."\">����</button></td>\n";
      if ($_mx_allow_cancel_rx)
	print '<td align=left><button style="height:50; width:50;" type="submit" name="stop" value="'.$oid."\">���</button></td>\n";
      print '<td><button style="height:50; width:50;" type="submit" name="copy" value="'.$oid."\"> Do </button></td>\n";
    }else{
      print '<td><button style="height:50; width:50; background-color: #bfb" type="submit" name="copy" value="'.$oid."\"> Do </button></td>\n";
      if ($_mx_allow_cancel_rx)
	print '<td align=left><button style="height:50; width:50; background-color: #fbb"  type="submit" name="stop" value="'.$oid."\">���</button></td>\n";
      if ($_mx_allow_modify_rx)
	print '<td align=left><button style="height:50; width:50; background-color: #ffb" type="submit" name="update" value="'.$oid."\">����</button></td>\n";
    }
    $open_print_script = 
      "window.open('print.php?oid={$oid}','','width=640,height=640')";
    print "<td><button type=\"button\" style=\"height:50\"  OnClick=\"$open_print_script\">������٥����</button></td>\n";
    if($_mx_inhosp_rx_print) {
      $open_print2_script = 
	"window.open('print_inhosp.php?oid={$oid}','','width=640,height=640')";
      print "<td><button type=\"button\" style=\"height:50\"  OnClick=\"$open_print2_script\">�����������</button></td>\n";
    }
    if ($_mx_rx_control_print && $pt_outin == 'I')
      print "<td><button type=\"submit\" style=\"height:50\"  name='print_toukan'\" value=\"$oid\">�������������</button></td>\n";
//1117-2011

      $open_print2_script = 
	"window.open('print_inhosp2.php?oid={$oid}','','width=640,height=640')";
      print "<td><button type=\"button\" style=\"height:50\"  OnClick=\"$open_print2_script\">IN��������</button></td>\n";

//1117-2011
    if ($_mx_rx_print)
      print "<td><button type=\"submit\" style=\"height:50\"  name='print' value=\"$oid\">���������</button></td>\n";
    print "</tr></table>";
    print "<table>";
    get_order_history("���޽����",$oid,"pill");
    $ord['��ȯ��']=0;
    print '</table>';
    print '<input type=hidden name="det-in" value="'.$oid.'">
           <table '.$class."><tr><th>�����ID{$oid}";
    $name = get_emp_name($ord['��Ͽ��']);
    if (!$_mx_hack_takamiya)
      $tr = get_teiki_rinji($ord['����׻�']);
    print "<td>{$name['lname']}&nbsp;{$name['fname']}";
    print "<tr><th nowrap>������<td>{$ord['����ǯ����']}
               <th nowrap>������<td>{$ord['����������']}
           <td>{$ord['��ʬ']}${tr}";
//    $ord['��ȯ��'] ? print "<th>��ȯ�ʲ�<td>" : print "<th>��ȯ���Բ�<td>";
    if ($_mx_rx_funsai && $ord['funsai'])
      print "<th>ʴ��";

    if ($ord['��߰�']) {
      $name = get_emp_name($ord['��߰�']);
      print "<tr><th>��߰�<td>{$name['lname']}&nbsp;{$name['fname']}
               <th>�����<td>{$ord['�����']}";
    }
    if($_mx_meds_comment){
      print '<tr><td><b>������</b></td><td colspan=3>'.$ord['Comment'].'</td></tr>';
    }

    print '</table>
    <div id="div_past" style="overflow: auto; height: 300px;">
    <table '.$class.'><pre>';
    /* ���Ƥ�ɽ�� */
    print join("\n", set_body($meds,1,$ord['��ȯ��']));
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
    foreach (array('medis', '�쥻�ץ��Ż����������ƥ������̾',
		   '�쥻�ץ��Ż����������ƥॳ���ɡʣ���',
		   '����', '��ˡʬ��','����¾������', 'freqid',
		   '����', '����','����', '��ʬ','����׻�','accept', 'generic_ok', '����ñ��','���������ܰ����ʥ�����') as $key) {
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
    $med['�쥻�ץ��Ż����������ƥ������̾'] = $k[2];
    $med['����ñ��'] = $k[5];
    $med['��ʬ'] = trim($k[6]);
    $med['accept'] = trim($k[7]);
    $med['�쥻�ץ��Ż����������ƥॳ���ɡʣ���'] = $k[8];
    //$med["���������ܰ����ʥ�����"] = $k[9];
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
      $row['����'] = 0;
    $le->insert_at_cursor(new direction($row));
  }
  $le->header = check_all_accepted($le->get_list(), $var['i����������']);
  // set cursor pos where a row was deleted
  if (array_key_exists('delcont' , $var))
    $le->cursor_pos = $var['delcont'];
}

function construct_meds(){
  global $le;
  $con = mx_db_connect();

  $freq_str = 'select "ObjectID", "��ˡ" as val, "����" from "�������ˡ" where "Superseded" is NULL order by "ObjectID"';

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
	  $meds[count($meds) -1 ]['��ˡ'] =
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
	if (is_null(!$row['����ñ��']) and ($row['����ñ��'] != $row['old����ñ��']))
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
    $a = set_body($meds,0,$ord['��ȯ��']);
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
	$ord['��ȯ��']=0;
    $a = set_body($meds,0,$ord['��ȯ��']);
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
	  $ord['�����'] = NULL;
	  $ord['��߰�'] = NULL;
	  // NEEDSWORK: nullify pharmacist, etc
	}
      }
      else {
	// new order
	$ord = array();
	// set default value generic_ok 03-20-2012
	 $ord["��ȯ��"] = $_mx_rx_generic_ok;
//0328-2012	$ord["��ȯ��"] = 1;
	$ord['��ʬ'] = '����';
	if ($_mx_rx_innai_ingai)
	  $ord['��ʬ'] = $_mx_rx_innai_ingai == 1 ? "����" : "����";
	else if($pt_outin == 'I') 

// 0603-2011

	  $ord['��ʬ'] = "����";
	else if ($pt_outin == 'O')

//06-03-2011
	  $ord['��ʬ'] = "����";
	$ord['����׻�'] = $_mx_rx_teikirinji;
      }
    } else
      foreach ($var as $k => $v)
	if (ereg("^i.*",$k)) $ord[substr($k,1)] = $v;

    print "<input type=hidden name=oid value={$oid}>";
    print "<input type=hidden name=action value={$action}>";

    if ($var["copy"]) { $ord["����ǯ����"] = $ord["����������"] = date("Y-m-d"); }

    $btn_value = '';
    $btn_label = '';

	$ord["��ȯ��"] = 1;
    if($_mx_hack_takamiya)
      $btn_back = '<button type="button" onClick="window.history.back();">���</button>';
    else
      $btn_back = '<button style="height: 50" type="button" onClick="window.history.back();">���</button>';

    if($action == "stop" || ($dbaction == 'dbpreview' && $dberror == '')){
      if($action == "update"){
	$btn_value = "dbupdate";
	$btn_label = "�����ID{$oid}����";
      }else  if($action == "new" || $action == "copy"){
	$btn_value = "dbnew";
	if($_mx_rx_show_noclaim and $ord['noclaim'])
	  $btn_label = "��������Ͽ";
	else if($_mx_iji)
	  $btn_label = "�������/��Ͽ";
	else
	  $btn_label = "������Ͽ";
      }else if($action == "stop") {
	$btn_value = "dbstop";
	$btn_label = "��ߤ�¹Ԥ��ޤ�";
	print "<table>";
	get_order_history("���޽����",$oid,"pill");
	print '</table>';
	print '<input type=hidden name="det-in" value="'.$oid.'">
           <table '.$class."><tr><th>�����ID{$oid}";
	$name = get_emp_name($ord['��Ͽ��']);
	$tr = get_teiki_rinji($ord['����׻�']);
//03-20-2012
	$ord["��ȯ��"] = 1;
	print "<td>{$name['lname']}&nbsp;{$name['fname']}";
	print "<tr><th nowrap>������<td>{$ord['����ǯ����']}
               <th nowrap>������<td>{$ord['����������']}
           <td>{$ord['��ʬ']} ${tr}";
	$ord['��ȯ��'] ? print "<th>��ȯ�ʲ�<td>" : print "<td>��ȯ���Բ�<td>";

	if ($ord['��߰�']) {
	  $name = get_emp_name($ord['��߰�']);
	  print "<tr><th>��߰�<td>{$name['lname']}&nbsp;{$name['fname']}
               <th>�����<td>{$ord['�����']}";
	}
	if($_mx_meds_comment){
	  print '<tr><td><b>������</b></td><td colspan=3>'.$ord['Comment'].'</td></tr>';
	}
	print '</table>';

	print "<div id='div_meds' style=\"overflow: auto; height: 550px; \">";
	manage_med_contents($var,&$dp, &$dirp,&$ord, $setlist);
	print "�����";
	mx_formi_date("i�����",
		      ($ord["i�����"] ? $ord["i�����"]
		       : date("Y-m-d")), NULL);
	$start_date = $ord['����������'];
	print "<br><br>";
	print <<<HTML
	  <font color="red"><b>
	  �嵭�����հʹߤν�������ߤ��ޤ���<br>
	  ��${start_date}�������������ޤǤν����ϼ»ܺѤߤȤ��ư���������ޤ�<br>
			   </b></font><br>
			   �����ͳ <select name="iComment">
			   <option value="���Ѥˤ�����">����
			   <option value="���ϥߥ��ˤ�����" selected>���ϥߥ�
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
      $btn_label = "��ǧ���̤ؿʤ�";
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
      if ($ord['i��Ͽ��'])
	    $rec['id'] = $ord['i��Ͽ��'];
      else if ($_REQUEST['i��Ͽ��'])
	    $rec['id'] = $_REQUEST['i��Ͽ��'];
      else
	    $rec['id'] = $auth[2]['ObjectID'];
    } else {
      $rec['id'] = $auth[2]['ObjectID'];
    }
    $rec['name'] = get_emp_name($rec['id']);
    $current_user['id'] = $auth[2]['ObjectID'];
    $current_user['name'] = get_emp_name($current_user['id']);
    if ($_mx_rx_use_set) {
      print ' ���å���Ͽ';
      mx_formi_checkbox("isetflag", $ord['setflag'], NULL);
      mx_formi_text("isetcomment", $ord['setcomment'], NULL);
    }
    if ($_mx_rx_show_noclaim) {
      print ' ������';
      mx_formi_checkbox("inoclaim", $ord['noclaim'], NULL);
    }
    if ($_mx_rx_use_set)
      print '<br>';
    print '������';
# NEEDSWORK: add readonly mode by $readonly
    mx_formi_date("i����ǯ����",
		  ($ord["����ǯ����"] ? $ord["����ǯ����"]
		   : date("Y-m-d")), NULL);
    if ($_mx_rx_order_record_doctor && $pid) {
      print '������';
      list_doctors("i��Ͽ��",$rec['id'],NULL,"doctor",$current_user);
    } else {
      print "<input type=\"hidden\" name=\"i��Ͽ��\" value=\"{$rec['id']}\">";
    }

    if ($_mx_rx_use_set)
      print '<br />';
    print '������';
    mx_formi_date("i����������",
		  ($ord["����������"] ? $ord["����������"]
		   : date("Y-m-d")), NULL);

    if($readonly != '') {
      print "\n";
      print '��ȯ�� ';
	$ord["��ȯ��"] = 1;
      print '<input type=text value="'. ($ord['��ȯ��'] == 1 ? '��' : '��'). '" size=2 readonly>';
      print "\n";
      print '<input type=hidden name="i��ȯ��" value="'.$ord['��ȯ��'].'">';
      print "\n";
      print '��ʬ ';
      print '<input type=text name="i��ʬ" value="'.$ord['��ʬ'].'" size=4 readonly>';
      $tr = get_teiki_rinji($ord['����׻�']);
      print '<input type=hidden name="i����׻�" value="'.$ord['����׻�'].'" size=4 readonly>';
      if (!$_mx_hack_takamiya)
	print $tr;
      if($_mx_meds_comment) {
	print '<br><b>������</b>';
	print '<pre>' . $ord['Comment'] . '</pre>';
	mx_formi_hidden("iComment", $ord['Comment']);
      }
      if ($_mx_rx_funsai && $ord['funsai'])  {
	print " ʴ��";
	mx_formi_hidden("ifunsai", $ord['funsai']);
      }
    }else{
      print "\n";
//03-20-2012
	$ord["��ȯ��"] =1;
      print '��ȯ��<select ONCHANGE="update_generic_ok(this)" name="i��ȯ��"'.$__mx_formi_dek . '>
         <option value="1"' . ($ord['��ȯ��'] == 1 ? "selected" : "") . '>��
         <option value="0"' . ($ord['��ȯ��'] == 0 ? "selected" : "") . '>��</select>';

      $fixed_kubun = ($_mx_rx_fixed_kubun != '' &&
		      strchr($_mx_rx_fixed_kubun, $pt_outin) !== FALSE);

      if ($fixed_kubun) {
	      print '��ʬ: ' . $ord['��ʬ'];
	      mx_formi_hidden('i��ʬ', $ord['��ʬ']);
      } else {
	      print '��ʬ <select name="i��ʬ" '.$__mx_formi_dek. ' >';
	      if ($_mx_rx_type_enum=='T') {
//0604-2011		


if ($pt_outin == 'O') {
		  print '<option value="����" '.($ord['��ʬ'] == "����" ? "selected" : "").'>����';
		} else {
		  print '<option value="���" '.($ord['��ʬ'] == "���" ? "selected" : "").'>���
		 <option value="�׻�" '.($ord['��ʬ'] == "�׻�" ? "selected" : "").'>�׻�
		 <option value="�ౡ��" '.($ord['��ʬ'] == "�ౡ��" ? "selected" : "").'>�ౡ��';
		}




	      } else {




		print '<option value="����" '.($ord['��ʬ'] == "����" ? "selected" : "").'>����
		 <option value="����" '.($ord['��ʬ'] == "����" ? "selected" : "").'>����';
		// print '<option value="����" '.($ord['��ʬ'] == "����" ? "selected" : "").'>����';
	      }
	      print '</select>';
      }

      // teiki-rinji
      $v = $_REQUEST['i����׻�'] ? $_REQUEST['i����׻�'] : $ord['����׻�'];
      if ($_mx_hack_takamiya)
	mx_formi_hidden("i����׻�", $v);
      else
	mx_formi_select("i����׻�", $v, $_lib_u_pharmacy_common_tr);

      // ʴ�ջؼ�
      if($_mx_rx_funsai) {
	print "ʴ��";
	mx_formi_checkbox("ifunsai", $ord['funsai'], NULL);
      }
      if($_mx_meds_comment) {
	print '<br><b>����䵥�����</b>';
	mx_formi_textarea("iComment", $ord['Comment'], array('rows'=>2,'cols'=>80));
      }
    }
    print '<input type="hidden" name="i����" value="'.$pid.'">';

    if ($__uiconfig_pharmacy_rx_show_stop_doctor) {
      print ' ����� <input type="text" maxlength="10" size="10" 
		 name="i�����" value="' . $ord["�����"] . '" '.
	$__mx_formi_dek.'>
		   ��߰�
		 <input type="hidden" name="i����" value="'.$pid.'">';
      list_doctors("i��߰�",$ord['��߰�'],$pid,"all0",$rec);
    }
    else {
      print '<input type="hidden" name="i�����" value="'.
	$ord["�����"].'">';
      print '<input type="hidden" name="i��߰�" value="'.
	$ord['��߰�'].'">';
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
    print '<td class="ptsel"> <form method="POST">����ID:';
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
    print "<br />���ꤵ�줿���Ԥˤϥ��������Ǥ��ޤ���";
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
	$opid = 'AND "���޽��������" IN (' . $opid . ')';
	$stmt = <<<SQL
		SELECT X."ObjectID", X."�»���", X."�»ܼ�",
		X."���޽��������", X."�õ�����", X.recorded,
		E."��", E."̾"
		FROM "���޼»ܵ�Ͽ" AS X
		LEFT JOIN "������Ģ" AS E
		ON E."ObjectID" = X."�»ܼ�"
		WHERE X."Superseded" IS NULL
		$opid
		ORDER BY X."���޽��������", X.recorded
SQL;
	$db = mx_db_connect();
	$execution = mx_db_fetch_all($db, $stmt);
	if (!is_array($execution) || !count($execution))
		return;

	print "�»ܵ�Ͽ\n\n";

	foreach ($execution as $x) {
		$med = $meds[$x['���޽��������']];
		$medname = trim($med['�쥻�ץ��Ż����������ƥ������̾']);
		$n = $x['��'] . ' ' . $x['̾'];
		$t = mx_format_timestamp($x['recorded'], 0);
		$e = $x['�õ�����'];
		print "$medname $t $n\n";
		if (trim($e) != '') {
			$lead = '�õ�����: ';
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

$stmt = ('SELECT * FROM "������Ģ" WHERE "Superseded" IS NULL
	    AND "ObjectID" = ' . mx_db_sql_quote($pid));
$d = mx_db_fetch_single(mx_db_connect(), $stmt);
$pt_hid = $d['����ID'];
$pt_outin = $d['������ʬ'];
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
