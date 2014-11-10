<?php
function get_rp($rec) {
  $con = mx_db_connect();
  $str = 'select "ObjectID" as oid, "RP名"
          from "RP"
          where "Superseded" is NULL
          and "記録者" = '."'$rec' ".
          'order by "oid"';
  return pg_fetch_all(pg_query($con,$str));
}

function get_rp_order ($oid) {
  $con = mx_db_connect();
  $str = 'select "ObjectID" as oid, "RP名", "記録者"
  from  "RP"
  where "ObjectID" = ' . mx_db_sql_quote($oid) . ' 
  order by "ObjectID"';

  return pg_fetch_assoc(pg_query($con,$str));
}

function get_rp_freq($oid) {
  $con = mx_db_connect();
  $str = 'select distinct "用法"
  from  "薬剤処方箋内容"
  where "RPID" = ' . mx_db_sql_quote($oid);

  return pg_fetch_assoc(pg_query($con,$str));
}

function get_rp_meds($oid) {
  $con = mx_db_connect();
  $str = 'select B."レセプト電算処理システム医薬品名"
  from  "薬剤処方箋内容" as A, "Medis医薬品マスター" as B
  where
   A."薬剤" = B."ObjectID" and
   A."RPID" = ' . mx_db_sql_quote($oid);

  return pg_fetch_all(pg_query($con,$str));
}

function show_rp($rec,$bname) {
  $rp = get_rp($rec);
  print '<div style="overflow: auto; height: 80px;">
         <table><tr><th>ＲＰ';
  foreach ($rp as $val) {
    $meds = get_rp_meds($val['oid']);
    printf ("<tr><td valign=top>
             <button name=\"%s\" value=\"%s\">RPID %s %s</button>\n",
	    $bname,$val['oid'],$val['oid'],$val['RP名']);

    $index = 2;
    print "<td>";
    foreach ($meds as $mval) {
      printf("&nbsp;%s",$mval["レセプト電算処理システム医薬品名"]);
      if (($index++ % 4) == 1) print "<br>";
    }
    if ($_POST['dbaction'] == "dbnew") $_POST['detail'] = $val['oid'];
  }
  print "</table></div>\n";
}
?>