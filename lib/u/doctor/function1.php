//common.php added


function get_otatest_pastorder ($ptid,$oid,$odate) {
  $con = mx_db_connect();
  $delete='deleted';
$str='select *   FROM otatest_order where "Superseded" is null and "patient"= '.mx_db_sql_quote($ptid).' 
and "ObjectID" != ' . mx_db_sql_quote($oid) . ' and "order_date" < date '.mx_db_sql_quote($odate)." -integer '60'".' order by order_date desc limit 1'; 

 
//print $str;
return pg_fetch_assoc(pg_query($con,$str));
 

 

}
//***********************************//

function get_otatest_order ($oid) {
  $con = mx_db_connect();
$str ='
       SELECT * FROM otatest_order 
where "Superseded" is null  and "ObjectID" = ' . mx_db_sql_quote($oid) . ' 
  order by "ObjectID" desc';
  return pg_fetch_assoc(pg_query($con,$str));


 

}



