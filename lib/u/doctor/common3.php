<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/enum.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ord_common.php';

$_lib_u_pharmacy_common_tr = array(0 => '', 1 => 'Äê´ü', 2 => 'Î×»þ', 3=> 'Âà±¡»þ');
$_lib_u_pharmacy_common_tr_short = array(0 => '', 1 => 'Äê', 2 => 'Î×', 3=> 'Âà');

 
/* do not display these for history */
$no_disp = array("ObjectID","Superseded","CreatedBy");

 

 
 
 
 

 
 

//0317-2014 30 DAYS

 
function get_otatest3_pastorder ($ptid,$oid,$odate) {
  $con = mx_db_connect();
  $delete='deleted';
$str='select *   FROM otatest3_order where "Superseded" is null and "patient"= '.mx_db_sql_quote($ptid).' 
and "ObjectID" != ' . mx_db_sql_quote($oid) . ' and "order_date" < date '.mx_db_sql_quote($odate)." -integer '30'".' order by order_date desc limit 1'; 

 
//print $str;
return pg_fetch_assoc(pg_query($con,$str));
 

 

}
//***********************************//

 
function get_otatest3_order ($oid) {
  $con = mx_db_connect();
$str ='
       SELECT * FROM otatest3_order 
where "Superseded" is null  and "ObjectID" = ' . mx_db_sql_quote($oid) . ' 
  order by "ObjectID" desc';
  return pg_fetch_assoc(pg_query($con,$str));


 

}


 

 

 

 
function print_hidden_vars($prefix,$var) {
  $k = array_keys($var);
  for ($i=0, $c=count($var);$i < $c;$i++) {
    printf("<input type=\"hidden\" name=\"%s%s\" value=\"%s\">\n",
	   $prefix,$k[$i],$var[$k[$i]]);
  }
}

 

 
 

class phoney_ppa {
	function phoney_ppa($u, $patient_ID, $patient_ObjectID) {
		$this->u = $u;
		$this->patient_ID = $patient_ID;
		$this->patient_ObjectID = $patient_ObjectID;
	}
	function appbar_filter($path, $name, $pid) {
	  if (trim($pid) == '') {
		  /*
		   * Do not show applications that set encounter to
		   * "finished" and such when seeing no patient.
		   */
		  if (is_encounter_state_application($path))
			  return 0;
	  } else {
		  /*
		   * Do not show applications that switch encounter
		   * mode between Inpatient and Outpatient when
		   * already seeing a patient.
		   */
		  if ($path == 'u/everybody/encounter-mode-flip.php')
			  return 0;
	  }
	  return 1;
	}
	function edit_in_progress() {
		global $dbaction, $action;

		if ($dbaction == 'dbpreview')
			return 1;
		return 0; /* NEEDSWORK */
	}
}
?>
