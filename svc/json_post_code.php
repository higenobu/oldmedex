<?
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
$limit = 30;
print "{\n";
if($_GET['zip']) {
  $db = mx_db_connect();
  $stmt = "SELECT code, pref, city, block FROM post_code WHERE code like " . mx_db_sql_quote($_GET['zip'] . '%') . " ORDER by code LIMIT " . $limit;

  $sth = pg_query($db, $stmt);
  if ($sth) {
    $data = pg_fetch_all($sth);
    if (is_array($data)) {
      printf("\"post_code\": [\n");
      foreach($data as $row) {
	$major = substr($row['code'], 0,3);
	$minor = substr($row['code'], 3,4);
	printf("{ \"zip\":  \"%s%s\", \"pref\": \"%s\", \"city\": \"%s\", \"block\": \"%s\"},\n" ,
	       $major, $minor,
	       /*
	       mb_convert_encoding($row['pref'], "UTF-8", "EUC-JP"),
	       mb_convert_encoding($row['city'], "UTF-8", "EUC-JP"),
	       mb_convert_encoding($row['block'], "UTF-8", "EUC-JP"));
	       */
	       $row['pref'],$row['city'],$row['block']);
	       
      }
      printf("]\n");
      if(count($data) == $limit) {
	print ", 'more' : '${limit}件以上ヒットしました'";
      }
    }
  }
}
print "}\n";
?>