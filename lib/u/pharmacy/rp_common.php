<?php
function get_rp($rec) {
  $con = mx_db_connect();
  $str = 'select "ObjectID" as oid, "RP̾"
          from "RP"
          where "Superseded" is NULL
          and "��Ͽ��" = '."'$rec' ".
          'order by "oid"';
  return pg_fetch_all(pg_query($con,$str));
}

function get_rp_order ($oid) {
  $con = mx_db_connect();
  $str = 'select "ObjectID" as oid, "RP̾", "��Ͽ��"
  from  "RP"
  where "ObjectID" = ' . mx_db_sql_quote($oid) . ' 
  order by "ObjectID"';

  return pg_fetch_assoc(pg_query($con,$str));
}

function get_rp_freq($oid) {
  $con = mx_db_connect();
  $str = 'select distinct "��ˡ"
  from  "���޽��������"
  where "RPID" = ' . mx_db_sql_quote($oid);

  return pg_fetch_assoc(pg_query($con,$str));
}

function get_rp_meds($oid) {
  $con = mx_db_connect();
  $str = 'select B."�쥻�ץ��Ż����������ƥ������̾"
  from  "���޽��������" as A, "Medis�����ʥޥ�����" as B
  where
   A."����" = B."ObjectID" and
   A."RPID" = ' . mx_db_sql_quote($oid);

  return pg_fetch_all(pg_query($con,$str));
}

function show_rp($rec,$bname) {
  $rp = get_rp($rec);
  print '<div style="overflow: auto; height: 80px;">
         <table><tr><th>�ң�';
  foreach ($rp as $val) {
    $meds = get_rp_meds($val['oid']);
    printf ("<tr><td valign=top>
             <button name=\"%s\" value=\"%s\">RPID %s %s</button>\n",
	    $bname,$val['oid'],$val['oid'],$val['RP̾']);

    $index = 2;
    print "<td>";
    foreach ($meds as $mval) {
      printf("&nbsp;%s",$mval["�쥻�ץ��Ż����������ƥ������̾"]);
      if (($index++ % 4) == 1) print "<br>";
    }
    if ($_POST['dbaction'] == "dbnew") $_POST['detail'] = $val['oid'];
  }
  print "</table></div>\n";
}
?>