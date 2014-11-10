<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
$pid = $_REQUEST['pid'];
$uri = $_SERVER['SCRIPT_NAME'];

$_POST['u'] = mx_authenticate_user();
$auth = mx_authorization();
if (! $auth[0]) {
  mx_authorization_error($auth);
  return;
}

mx_html_head($auth[1]); print '<body>';

print '<table border="0"><tr><td valign="top"  width="40%">';
print "${auth[1]}&nbsp;";
draw_back('../../index.php');
mx_draw_userinfo();
print '<td valign="top" align="left">';

function show_drug_list($drugs, &$ym){
  print '<table><tr><th>´ÉÍıID<th>¼õÊ§Ç¯·îÆü
           <input type="hidden" name="ym" value="'. $ym .'"<td>' . "\n";
  foreach ($drugs as $item) {
    $data = base64_encode(serialize($item));
    print '<tr><td>
             <button type="submit" name="detail" value="' . $data .
      "\">´ÉÍıID{$item['ID']}</button>" .
      "<td>{$item['¼õÊ§Ç¯·îÆü']}<td>\n";
  }
  print "</table><p>\n";
}

function get_pat_name($oid) {
  $con = mx_db_connect();

   return (pg_fetch_assoc(pg_query($con,
  'select P."À«", P."Ì¾"
   from "ÌôºŞ½èÊıäµ" as O, "´µ¼ÔÂæÄ¢" as P
   where O."Superseded" is NULL and
         P."Superseded" is NULL and
         P."ObjectID" = O."´µ¼Ô" and
         O."ObjectID" = ' . mx_db_sql_quote($oid))));
}

function show_drug_detail($detail) {
  if ($detail) {
    $data = unserialize(base64_decode($detail));
    if ($data["ÌôºŞ»Õ"]) $pharm = get_emp_name($data["ÌôºŞ»Õ"]);
    if ($data["½èÊıäµ"]) $pname = get_pat_name($data["½èÊıäµ"]);
    print "<table><tr><th>µ­Ï¿ID<td>{$data['ID']}
           <tr><th>¼õÊ§ÊÌ<td>" .
          ($data['¼õÊ§'] == "I" ? "¾ù¼õ" : 
             ($data['¼õÊ§'] == "O" ? "Ê§½Ğ" : "")) .
          "<tr><th>ÌôºŞÌ¾<td>{$data['¥ì¥»¥×¥ÈÅÅ»»½èÍı¥·¥¹¥Æ¥à°åÌôÉÊÌ¾']}
           <tr><th>¼õÊ§Ç¯·îÆü<td>{$data['¼õÊ§Ç¯·îÆü']}
           <tr><th>¼õÊ§¿ôÎÌ<td>{$data['¼õÊ§¿ôÎÌ']}
           <tr><th>ÌôºŞ»ÕÌ¾<td>{$pharm['lname']}¡¡{$pharm['fname']}
           <tr><th>´µ¼ÔÌ¾<td>{$pname['À«']}¡¡{$pname['Ì¾']}
           <tr><th>»Ä¿ôÎÌ<td>{$data['»Ä¿ôÎÌ']}
           <tr><th>È÷¹Í<td>{$data['È÷¹Í']}
           </table>";
  }
}

print "<form method=\"post\" action=\"$uri\">\n";
if(!$ym && (!$drugs = search_drugs("",$ym))) {
  print "</table>";
  return;
} else {
  print "</table><hr>";
  print '<table border="0"><tr><td valign="top">' . "\n";
  show_drug_list($drugs,$ym);
  print "<tr><td><hr>";
  print "<tr><td>";
  show_drug_detail($_POST['detail']);
  print "</table>\n";
}
print "</form>";
?>