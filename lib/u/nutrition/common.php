<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/enum.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ord_common.php';

$ord_array =  array(
    "¶èÊ¬" => array('Æþ±¡','ÊÑ¹¹','Ää»ß¡Ê³°Çñ¡Ë','Ää»ß¡Ê³°½Ð¡Ë',
		    'Ää»ß¡Ê¸¡ºº¡Ë','ºÆ³«','Àä¿©','Âà±¡'),
    "ÊÝ¸±" => array('ÆÃÊÌ²Ã»»','Èó²Ã»»'),
    "¼Â¹Ô»þ" => array('Ä«','Ãë','Í¼'),
    "¿©¼ï" => array('-','¾ï¿© ­µ','¾ï¿© ­¶','¾ï¿© ­·','Óë²¼­µ','Óë²¼­¶',
		    'Óë²¼­·','¥Ô¥å¡¼¥ì¿©','¤¢¤ó¤«¤±¿©',
		    'ÆðºÚ°ì¸ýÂç¡Ê¥È¥í¥ß¤Ä¤­¡Ë','»°Ê¬´¡¿©',
		    '¸ÞÊ¬´¡¿©','¼·Ê¬´¡¿©','Á´´¡¿©','ÊÆÈÓ¿©','Î®Æ°¿©',
		    'Ç»¸üÎ®Æ°¡Ê·Ð¸ý¡Ë','Î®Æ°¿©¡Ê¼£ÎÅ¡Ë','Ž´ŽÈŽÙŽ·ŽÞŽ°ŽºŽÝŽÄŽÛŽ°ŽÙ1000',
		    'Ž´ŽÈŽÙŽ·ŽÞŽ°ŽºŽÝŽÄŽÛŽ°ŽÙ1400','Ž´ŽÈŽÙŽ·ŽÞŽ°ŽºŽÝŽÄŽÛŽ°ŽÙ1800','ŽÀŽÝŽÊŽßŽ¸ŽºŽÝŽÄŽÛŽ°ŽÙ1600',
		    'ŽÀŽÝŽÊŽßŽ¸ŽºŽÝŽÄŽÛŽ°ŽÙ1800','»é¼ÁŽºŽÝŽÄŽÛŽ°ŽÙ1500','»é¼ÁŽºŽÝŽÄŽÛŽ°ŽÙ1800',
		    '±öÊ¬ŽºŽÝŽÄŽÛŽ°ŽÙ1000','±öÊ¬ŽºŽÝŽÄŽÛŽ°ŽÙ1400','±öÊ¬ŽºŽÝŽÄŽÛŽ°ŽÙ1800',
		    '°×¾Ã²½»°Ê¬´¡¿©','°×¾Ã²½¸ÞÊ¬´¡¿©','°×¾Ã²½¼·Ê¬´¡¿©',
		    '°×¾Ã²½Á´´¡¿©','°×¾Ã²½ÊÆÈÓ¿©','Ç»¸üÎ®Æ°¡Ê·Ð´É¡Ë','Àä¿©'),
    "¼ç¿©" => array('-','¥Ñ¥ó','ÊÆÈÓ','Á´´¡','¼·Ê¬´¡','¸ÞÊ¬´¡','»°Ê¬´¡','¥Ñ¥ó´¡',
		    '´¡¥Ô¥å¡¼¥ì','¥¼¥ê¡¼'),
    "Å¬±þ¼À´µ" => array('-','ÅüÇ¢ÉÂ','»éËÃ´Î','¹âÅÙÈîËþ¾É','ÄËÉ÷','Å´·çË³À­ÉÏ·ì',
			'¿Õ¼À´µ¡¦ÅüÇ¢ÉÂÀ­¿Õ±ê','´Î¼À´µ','µÞÀ­´Î±ê¡¦ç¹Â¡¼À´µ',
			'¹â»é·ì¾É','¿Õ¼À´µ','¿´Â¡¼À´µ',
			'ÄÙáç¡Ê°ßÄÙáç¡¦½½Æó»ØÄ²¡Ë',
			'¥¯¥í¡¼¥óÉÂ¡¦ÄÙáçÀ­ÂçÄ²±ê¤Ê¤É','PEG¡¦·ÐÉ¡'),
    "Å¬±þ¼À´µ2" => array('ÅüÇ¢ÉÂ','»éËÃ´Î','¹âÅÙÈîËþ¾É','ÄËÉ÷','Å´·çË³À­ÉÏ·ì',
			'¿Õ¼À´µ¡¦ÅüÇ¢ÉÂÀ­¿Õ±ê','´Î¼À´µ','µÞÀ­´Î±ê¡¦ç¹Â¡¼À´µ',
			'¹â»é·ì¾É','¿Õ¼À´µ','¿´Â¡¼À´µ',
			'ÄÙáç¡Ê°ßÄÙáç¡¦½½Æó»ØÄ²¡Ë',
			'¥¯¥í¡¼¥óÉÂ¡¦ÄÙáçÀ­ÂçÄ²±ê¤Ê¤É','PEG¡¦·ÐÉ¡'),
    "ÉûºÚ" => array('-','¾ïºÚ','ÆðºÚ°ì¸ýÂç','ÆðºÚ°ì¸ýÂç¡Ê¤È¤í¤ßÉÕ¤­¡Ë',
		    '°ì¸ýÂç','¤¢¤ó¤«¤±','¥Ô¥å¡¼¥ì','¥¼¥ê¡¼'),
    "¥¢¥ì¥ë¥®¡¼¤Î³ÎÇ§" => array('-','µíÆý','Íñ','¤½¤Ð','¥µ¥Ð'),
    "»þ" => array('-','7','8','9','10','11','12','13','14','15','16','17','18',
		  '19','20','21','22','23','24','1','2','3','4','5','6'),
    "¼ïÎà" => array('-','2.0','1.5','1.0','Åü¼ÁÄ´À°Î®Æ°¡ÊDMÍÑ¡Ë'),
    "¿åÊ¬¡¦´Ö¿å" => array('-','Í­'),
    "Â®ÅÙ" => array('-','30','45','60'));

$ins = array('Î®Æ°¿©¡Ê¼£ÎÅ¡Ë','Ž´ŽÈŽÙŽ·ŽÞŽ°ŽºŽÝŽÄŽÛŽ°ŽÙ1000','Ž´ŽÈŽÙŽ·ŽÞŽ°ŽºŽÝŽÄŽÛŽ°ŽÙ1400',
	     'Ž´ŽÈŽÙŽ·ŽÞŽ°ŽºŽÝŽÄŽÛŽ°ŽÙ1800','ŽÀŽÝŽÊŽßŽ¸ŽºŽÝŽÄŽÛŽ°ŽÙ1600','ŽÀŽÝŽÊŽßŽ¸ŽºŽÝŽÄŽÛŽ°ŽÙ1800',
	     '»é¼ÁŽºŽÝŽÄŽÛŽ°ŽÙ1500','»é¼ÁŽºŽÝŽÄŽÛŽ°ŽÙ1800','±öÊ¬ŽºŽÝŽÄŽÛŽ°ŽÙ1000','±öÊ¬ŽºŽÝŽÄŽÛŽ°ŽÙ1400',
	     '±öÊ¬ŽºŽÝŽÄŽÛŽ°ŽÙ1800','°×¾Ã²½»°Ê¬´¡¿©','°×¾Ã²½¸ÞÊ¬´¡¿©','°×¾Ã²½¼·Ê¬´¡¿©',
	     '°×¾Ã²½Á´´¡¿©','°×¾Ã²½ÊÆÈÓ¿©','Ç»¸üÎ®Æ°¡Ê·Ð´É¡Ë');

function print_select($array,$key,$currval,$onchange) {
  global $__mx_formi_dek;
  global $ord_array;

  if ($onchange) 
    $onchange = 'OnChange="this.form.submit();"';
  else $onchange = "";
  printf("<select %s %s name=\"i%s\">\n",$__mx_formi_dek,$onchange,$key);
  foreach ($ord_array[$array] as $val)
    printf("<option %s value=\"%s\">%s\n",
	   ($val == $currval ? "selected" : ""),$val,$val);
  print "</select>\n";
}
function get_measure($pid,$type) {
  $con = mx_db_connect();
  $mes = pg_fetch_assoc(pg_query($con,
				 'select "'.$type.'"
                                  from "¥Ð¥¤¥¿¥ë¥Ç¡¼¥¿É½"
                                  where "Superseded" is NULL and
                                  "´µ¼Ô" = '."'$pid'"));
  return $mes ? $mes[$type] : "";
}

function get_patient_meal($pid) {
  if (!ereg("^[0-9]+$",$pid)) return FALSE;

  $con = mx_db_connect();
  $res = pg_query($con, 'select P."ObjectID", P."À«", P."Ì¾"
     from ¿©»öäµ as M, ´µ¼ÔÂæÄ¢ as P
     where M."Superseded" is NULL and
           P."Superseded" is NULL and 
           P."ObjectID" = M."´µ¼Ô" and
           P."´µ¼ÔID" = '."'$pid'".
           'order by M."ObjectID"')
      or die('pg_query => '. pg_last_error());
  if (pg_num_rows($res) &&
      ($pat = pg_fetch_assoc($res)))
    pg_free_result($res);
  return $pat;
}

function get_meal_history($pid) {
  $con = mx_db_connect();

  return (pg_fetch_all(pg_query($con,
				  'select * from "¿©»öäµ"
                                   where "Superseded" is NULL and
                                         "´µ¼Ô" = '."'$pid'".
                                   'order by "ObjectID"')));
}

function get_meal_new_updates($search) {
  $con = mx_db_connect();

  $str = 'select M."ObjectID", M."µ­Ï¿Æü", M."µ­Ï¿»þ´Ö", P."À«", P."Ì¾"
         from "¿©»öäµ" as M
              join  "´µ¼ÔÂæÄ¢" as P 
              on P."ObjectID" = M."´µ¼Ô" and
                P."Superseded" is NULL
         where 
         M."Superseded" is NULL and ';
  switch ($search) {
  case '1' : $str = $str . ' M."¼Â¹ÔÆü" >= '."'today' and ".
	                   ' M."¼Â¹ÔÆü" <= '."'tomorrow' ";
    break;
  case '2' : $str = $str . ' M."±ÉÍÜ»Îµ­Ï¿" is NULL ';
    break;
  }
  $str = $str . ' order by M."ObjectID"';
  return pg_fetch_all(pg_query($con,$str));
}

function get_meal_order($oid) {
  $con = mx_db_connect();
  return (pg_fetch_assoc(pg_query($con,
				  'select  * from "¿©»öäµ"
                                   where "ObjectID" = '. "'$oid'")));
}

function insert_meal_order ($var) {

  foreach ($var as $key => $val)
    if (ereg("^i.*",$key)) {
      $key = substr($key,1);
      if ($val == '-') $val = "";
      if (($key == "½èÊýÆü" || $key == "¼Â¹ÔÆü" || $key == "ºÆ³«Æü") && 
	  check_date($key,mb_convert_kana($val,'a','EUC-JP'))) return;
      if ($key == "Ç»¸üÎ®Æ°ÁíÇ®ÎÌ" || $key == "ÎÌ0" || $key == "ÎÌ1" || 
	  $key == "ÎÌ2" || $key == "ÎÌ3" || $key == "ÎÌ4" ||
	  $key == "´Ö¿åÎÌ0" || $key == "´Ö¿åÎÌ1" || $key == "´Ö¿åÎÌ2" ||
	  $key == "´Ö¿åÎÌ3" || $key == "´Ö¿åÎÌ4") {
	$val = mb_convert_kana($val,'a','EUC-JP');
	if ($val && !ereg("^[0-9]+$",$val)) {
	  print '<font color="red">{$key}¤Ë¿ô»ú¤òÆþÎÏ¤·¤Æ¤¯¤À¤µ¤¤¡£</font>';
	  return FALSE;        
	}
      }
      $ins[$key]=$val;
    }
  $ins['CreatedBy'] = $var['u'];
  $str = make_insert_str("¿©»öäµ",$ins,$oid);

  $ret = true;
  $con = mx_db_connect();
  pg_query($con,"begin");
  pg_query($con,$str) or $ret = false;
  pg_query($con, "commit;");
  return $ret;

}

function update_meal_order ($var) {

  foreach($var as $key => $val)
    if (ereg("^i.*",$key)) {
      $key = substr($key,1);
      if ($val == '-') $val = "";
      if (($key == "½èÊýÆü" || $key == "¼Â¹ÔÆü" || $key == "ºÆ³«Æü") && 
	  check_date($key,mb_convert_kana($val,'a','EUC-JP'))) return;
      if ($key == "Ç»¸üÎ®Æ°ÁíÇ®ÎÌ" || $key == "ÎÌ0" || $key == "ÎÌ1" || 
	  $key == "ÎÌ2" || $key == "ÎÌ3" || $key == "ÎÌ4" ||
	  $key == "´Ö¿åÎÌ0" || $key == "´Ö¿åÎÌ1" || $key == "´Ö¿åÎÌ2" ||
	  $key == "´Ö¿åÎÌ3" || $key == "´Ö¿åÎÌ4") {
	$val = mb_convert_kana($val,'a','EUC-JP');
	if ($val && !ereg("^[0-9]+$",$val)) {
	  print '<font color="red">{$key}¤Ë¿ô»ú¤òÆþÎÏ¤·¤Æ¤¯¤À¤µ¤¤¡£</font>';
	  return FALSE;
	}
      }
      $array[$key]=$val;
    }
  $array['CreatedBy'] = $var['u'];
  $array['act'] = $var['oid'];

  $ret = true;
  if (diff_contents("¿©»öäµ",$array)) {
    make_update_str("¿©»öäµ",$array,$upstr,$insstr);
    $con = mx_db_connect();
    pg_query($con,"begin");
    pg_query($con,$insstr) or $ret = false;
    pg_query($con,$upstr) or $ret = false;
    pg_query($con, "commit;");
  }
  return $ret;
}

function print_meal_detail($ord,&$done) {

    $name = get_emp_name($ord['µ­Ï¿¼Ô']);
    $room = get_pat_room($ord['´µ¼Ô']);
    print "<table>
           <tr><th nowrap>¿©»öäµID<td nowrap>{$ord['ObjectID']}
               <th nowrap>ÉÂ¼¼Ì¾<td nowrap>{$room['ÉÂ¼¼Ì¾']}";
    foreach($ord as $k => $v) {
      if ($k == "CreatedBy" || $k == "ObjectID" ||
	  $k == "Superseded" || $k == "ID" ||
	  $k == "´µ¼Ô") continue;
      if (($k == "»þ0" || $k == "»þ1" || $k == "»þ2" ||
	  $k == "»þ3" || $k == "»þ4" || 
	  $k == "¼ïÎà0" || $k == "¼ïÎà1" || $k == "¼ïÎà2" ||
	  $k == "¼ïÎà3" || $k == "¼ïÎà4" ||
	  $k == "ÎÌ0" || $k == "ÎÌ1" || $k == "ÎÌ2" ||
	  $k == "ÎÌ3" || $k == "ÎÌ4" || $k == "´Ö¿åÎÌ0" ||
	  $k == "´Ö¿åÎÌ1" ||$k == "´Ö¿åÎÌ2" ||$k == "´Ö¿åÎÌ3" ||
	  $k == "´Ö¿åÎÌ4" || $k == "Â®ÅÙ" || $k == "Â®ÅÙ¤½¤ÎÂ¾") && $v) {
	$tbl[$k] = $v;
	continue;
      }
      if (($k == "Ä«¿©¼ïÌ¾" || $k == "Ä«¼ç¿©" || $k == "Ä«ÉûºÚ" ||
	   $k == "Êä½õ¿©" ||
	   $k == "Ãë¿©¼ïÌ¾" || $k == "Ãë¼ç¿©" || $k == "ÃëÉûºÚ" ||
	   $k == "Í¼¿©¼ïÌ¾" || $k == "Í¼¼ç¿©" || $k == "Í¼ÉûºÚ") && $v) {
	$btbl[$k] = $v;
	continue;
      }
      if ($v && !($col++ % 2)) print '<tr>'; 
      if ($k == 'µ­Ï¿¼Ô')
	print "<th nowrap>{$k}<td>{$name['lname']}&nbsp;{$name['fname']}\n";
      elseif ($k == 'µ­Ï¿Æü' || $k == 'µ­Ï¿»þ´Ö') {
	$time_tbl[$k] = $v;
	if (count($time_tbl) == 2) {
	  print "<th nowrap>µ­Ï¿Æü»þ<td nowrap>".
	    disp_day_time($time_tbl['µ­Ï¿Æü'],$time_tbl['µ­Ï¿»þ´Ö']);
	  $col-=2;
	}
      }
      elseif ($k == 'ÆÃÊÌ»Ø¼¨' && $v)
	print "<th nowrap><font color=red>{$k}</font>
               <td nowrap><font color=red>{$v}</font>\n";
      elseif ($k == '±ÉÍÜ»Îµ­Ï¿' && $v) {
	print "<th nowrap>±ÉÍÜ»Îµ­Ï¿ºÑ¤ß<td>\n";
	$done = 1;
      }
      elseif ($v)
	print "<th nowrap>{$k}<td nowrap>{$v}\n";
    }
    if (count($btbl)) {
      print "<tr><td colspan=4>
                 <table frame=border border=1><tr><td>
                 <th align=center>¿©¼ïÌ¾
                 <th align=center>¼ç¿©
                 <th align=center>ÉûºÚ";
      if ($btbl['Ä«¿©¼ïÌ¾'] || $btbl['Ä«¼ç¿©'] || $btbl['Ä«ÉûºÚ'])
	print "<tr><th>Ä«
                 <td align=center>{$btbl['Ä«¿©¼ïÌ¾']}
                 <td align=center>{$btbl['Ä«¼ç¿©']}
                 <td align=center>{$btbl['Ä«ÉûºÚ']}";
      if ($btbl['Ãë¿©¼ïÌ¾'] || $btbl['Ãë¼ç¿©'] || $btbl['ÃëÉûºÚ'])
	print "<tr><th>Ãë
                 <td align=center>{$btbl['Ãë¿©¼ïÌ¾']}
                 <td align=center>{$btbl['Ãë¼ç¿©']}
                 <td align=center>{$btbl['ÃëÉûºÚ']}";
      if ($btbl['Í¼¿©¼ïÌ¾'] || $btbl['Í¼¼ç¿©'] || $btbl['Í¼ÉûºÚ'])
	print "<tr><th>Í¼
                 <td align=center>{$btbl['Í¼¿©¼ïÌ¾']}
                 <td align=center>{$btbl['Í¼¼ç¿©']}
                 <td align=center>{$btbl['Í¼ÉûºÚ']}";
      if ($btbl['Êä½õ¿©'])
	print "<tr><th align=center>Êä½õ¿©
                 <td align=left colspan=3>{$btbl['Êä½õ¿©']}";
      print "</table>";
    }

    if (count($tbl)) {
      print '<tr><td colspan="4">
                 <table border="1"><tr><th align="center">»þ´Ö
                            <th align="center">¼ïÎà
                            <th align="center">ÎÌ
                            <th align="center">¿åÊ¬<br>´Ö¿åÎÌ';
      if ($tbl['»þ0'] || $tbl['¼ïÎà0'] || $tbl['ÎÌ0'] || $tbl['´Ö¿åÎÌ0'])
	print "<tr><td align=center>".($tbl['»þ0']?$tbl['»þ0']:"-")."»þ
                   <td align=center>".($tbl['¼ïÎà0']?$tbl['¼ïÎà0']:"-")."
                   <td align=center>".($tbl['ÎÌ0']?$tbl['ÎÌ0']:"-")."ml
                   <td align=center>".($tbl['´Ö¿åÎÌ0']?$tbl['´Ö¿åÎÌ0']:"-")."ml";
      if ($tbl['»þ1'] || $tbl['¼ïÎà1'] || $tbl['ÎÌ1'] || $tbl['´Ö¿åÎÌ1'])
	print "<tr><td align=center>".($tbl['»þ1']?$tbl['»þ1']:"-")."»þ
                   <td align=center>".($tbl['¼ïÎà1']?$tbl['¼ïÎà1']:"-")."
                   <td align=center>".($tbl['ÎÌ1']?$tbl['ÎÌ1']:"-")."ml
                   <td align=center>".($tbl['´Ö¿åÎÌ1']?$tbl['´Ö¿åÎÌ1']:"-")."ml";
      if ($tbl['»þ2'] || $tbl['¼ïÎà2'] || $tbl['ÎÌ2'] || $tbl['´Ö¿åÎÌ2'])
	print "<tr><td align=center>".($tbl['»þ2']?$tbl['»þ2']:"-")."»þ
                   <td align=center>".($tbl['¼ïÎà2']?$tbl['¼ïÎà2']:"-")."
                   <td align=center>".($tbl['ÎÌ2']?$tbl['ÎÌ2']:"-")."ml
                   <td align=center>".($tbl['´Ö¿åÎÌ2']?$tbl['´Ö¿åÎÌ2']:"-")."ml";
      if ($tbl['»þ3'] || $tbl['¼ïÎà3'] || $tbl['ÎÌ3'] || $tbl['´Ö¿åÎÌ3'])
	print "<tr><td align=center>".($tbl['»þ3']?$tbl['»þ3']:"-")."»þ
                   <td align=center>".($tbl['¼ïÎà3']?$tbl['¼ïÎà3']:"-")."
                   <td align=center>".($tbl['ÎÌ3']?$tbl['ÎÌ3']:"-")."ml
                   <td align=center>".($tbl['´Ö¿åÎÌ3']?$tbl['´Ö¿åÎÌ3']:"-")."ml";
      if ($tbl['»þ4'] || $tbl['¼ïÎà4'] || $tbl['ÎÌ4'] || $tbl['´Ö¿åÎÌ4'])
	print "<tr><td align=center>".($tbl['»þ4']?$tbl['»þ4']:"-")."»þ
                   <td align=center>".($tbl['¼ïÎà4']?$tbl['¼ïÎà4']:"-")."
                   <td align=center>".($tbl['ÎÌ4']?$tbl['ÎÌ4']:"-")."ml
                   <td align=center>".($tbl['´Ö¿åÎÌ4']?$tbl['´Ö¿åÎÌ4']:"-")."ml";
      if ($tbl['Â®ÅÙ'] || $tbl['Â®ÅÙ¤½¤ÎÂ¾'])
	print "<tr><th align=center>Â®ÅÙ
                   <td align=center>{$tbl['Â®ÅÙ']}
                   <th align=center>Â®ÅÙ¤½¤ÎÂ¾
                   <td align=center>{$tbl['Â®ÅÙ¤½¤ÎÂ¾']}";
      print "</table>";
    }
}

$no_disp = array('ObjectID','Superseded','CreatedBy','µ­Ï¿Æü','µ­Ï¿»þ´Ö');

?>