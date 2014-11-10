<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/pdfkarte.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';
function _lib_u_sei_kiroku10() {
  $db = mx_db_connect();
  $stmt = <<<SQL
    select "À«" || "Ì¾" as empname , userid
    from "¿¦°÷ÂæÄ¢"
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array(NULL => '');
  foreach($rows as $row)
    $ret[$row['userid']] = $row['empname'];
  return $ret;
}

function __lib_u_doctor_inhosp_cfg(&$cfg)
{
$cfg = array_merge(

array(
TABLE => 'inhosp_order',
'ALLOW_SORT' => 1,
 'DEFAULT_SORT' => 'OrderDate',
// 'DEFAULT_SORT' => 'recorded',

 COLS => array(
	 "OrderDate" ,
	  "ExecDate",
 	 "´µ¼Ô" ,
	 "Patient",
 	 "code",
 	 "name" ,
  	"room",
 
	"byoto",

"recorded"

 	),

LCOLS => array(
	array('Column' => 'OrderDate',
	'Label' => '°ÍÍêÆü'),
	  
 	 array('Column' => 'ExecDate',
	'Label' => '¼Â»ÜÆü'),
	 
 	array('Column' => 'code',
					'Label' => 'action',
				   
				       'Draw' => 'enum',
				       'Enum' => array('001' => 'Æş±¡','002' => 'Âà±¡','006' => '³°Çñ')),

 	  
  	array('Column' => 'byoto','Label' => 'ÉÂÅï'),
 
	array('Column' => 'room','Label' => 'ÉÂ¼¼'),
array('Column' => "CreatedBy",
					'Label' => 'µ­Ï¿¼Ô',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_sei_kiroku10()

				       ),
 	),


DCOLS =>  array(
	 array('Column' => 'OrderDate',
	'Label' => '°ÍÍêÆü'),
	  
 	 array('Column' => 'ExecDate',
	'Label' => '¼Â»ÜÆü'),
	 
 	array('Column' => 'code',
					'Label' => 'action',
				   
				       'Draw' => 'enum',
				       'Enum' => array('001' => 'Æş±¡','002' => 'Âà±¡','006' => '³°Çñ')),

 	 
  	array('Column' => 'byoto','Label' => 'ÉÂÅï'),
 
	array('Column' => 'room','Label' => 'ÉÂ¼¼'),
//11-01-2014
array('Column' => 'recorded',
'Label' => 'µ­Ï¿', 'Draw' => 'timestamp'),


 	),

ECOLS => array(
	array('Column' => 'OrderDate',
	'Label' => '°ÍÍêÆü',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'nonnull,date')),
 	array('Column' => 'ExecDate',
	'Label' => '¼Â»ÜÆü',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'nonnull,date')),
 
	array('Column' => 'code',
					'Label' => 'action',
				   
				       'Draw' => 'enum',
				       'Enum' => array('001' => 'Æş±¡','002' => 'Âà±¡','006' => '³°Çñ')),

 
	array('Column' => 'byoto','Label' => 'ÉÂÅï', 'Draw' => 'text'), 
					 
	array('Column' => 'room','Label' => 'ÉÂ¼¼', 'Draw' => 'text'),
array('Column' => "CreatedBy",
					'Label' => 'µ­Ï¿¼Ô',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_sei_kiroku10()

				       ),
 
	
)
),$cfg);
	return $cfg;
}

class list_of_inhosps extends list_of_ppa_objects {
	function list_of_inhosps($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_inhosp_cfg($cfg);
		list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
	}

function row_paging_orders() {
		$paging_keys = $this->row_paging_keys();
		$paging_orders = array();
		foreach ($paging_keys as $col) {
			if ($col == 'OrderDate' ||$col == 'ObjectID')
				$paging_orders[] = 1;
			else
				$paging_orders[] = 0;
		}
		return $paging_orders;
	}

}

class inhosp_display extends simple_object_display {

	function inhosp_display($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_inhosp_cfg($cfg);
		simple_object_display::simple_object_display($prefix, $cfg);
	}


/* 
function print_sod() {
    go_pdf5($this->id, 0);
  }
 
*/

function print_sod($template='srl') {
    $db = mx_db_connect();

  $oid = $this->id;



    $stmt = 'SELECT "ID" from "inhosp_order" WHERE "ObjectID"=' . $oid;
    $rs = mx_db_fetch_single($db, $stmt);


    if(is_null($rs))
      return;

    $status = 0;
   
    
    $s =  <<< HTML
    <SCRIPT LANGUAGE="JavaScript">
       window.open("printml.php?test_app_type=${test_app_type}&status=${status}&oid={$oid}", "","width=640,height=640");
    </SCRIPT>
HTML;
    print $s;
  }



}

class inhosp_edit extends simple_object_edit {
	function inhosp_edit($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_inhosp_cfg($cfg);
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}
 
function edit_tweak() {
$this->data['recorded'] =  date("Y-m-d H:i:s");

}
 

function anew_tweak($orig_id) {
		$this->data['OrderDate'] = mx_today_string();
		$this->data['ExecDate'] = mx_today_string();
		$this->data['Patient'] = $this->so_config['Patient_ObjectID'];
$this->data['recorded'] = date("Y-m-d H:i:s");
	} 
function commit($force=NULL) {
		$this->data['´µ¼Ô'] = $this->so_config['Patient_ObjectID'];
// 		$this->data['Patient'] = $this->so_config['Patient_ObjectID'];

//

    $db = mx_db_connect();
    $date = $this->data['OrderDate'];
 $patient_objectid = $this->data['´µ¼Ô'];
$p_oid = $this->data['´µ¼Ô'];
print $o_oid;

$name=array('001' => 'Æş±¡','002' => 'Âà±¡','006' => '³°Çñ');


$kaishi=$this->data['ExecDate'];
$tenkibi=$this->data['OrderDate'];
$tenki=$this->data['room'];

$st= $this->data['byoto'];
$st2= $this->data['memo'];
$st3= $name[$this->data['code']];

$ocont="----------------------------\n"."ÆşÂà±¡\n"."Status: ".$st3.' ¥ª¡¼¥ÀÆü='.$tenkibi.'Í½ÄêÆü='.$kaishi." ÉÂÅï¡¦ÉÂ¼¼=".$st." ".$tenki." ".$st2." "."\n";

print $ocont;
 
 
//new 10-24-2014
$stmt10 = <<<SQL
select * from "¥«¥ë¥Æ¥Ç¥âÉ½" where "ÆüÉÕ"='$tenkibi' and "´µ¼Ô"=$p_oid and 
	"Superseded" is null;
SQL;
 
// print $stmt10;

$rs0 = mx_db_fetch_all($db, $stmt10);
//print "all?".count($rs0);
if (count($rs0) == 0){
$stmt11 = <<<SQL
INSERT INTO "¥«¥ë¥Æ¥Ç¥âÉ½" ("´µ¼Ô", "ÆüÉÕ","P") values ($p_oid,'$tenkibi','$ocont');
SQL;
 
//print $stmt11;

if (pg_query($db, $stmt11)){

	}
else {
print '<p > karte insert DB access error</p>';
die;
	}

 }

else{ 

 for ($i=0;$i<count($rs0);$i++){	
 $pp=$rs0[$i]["P"];
 $idd=$rs0[$i]["ID"];
// print $pp."=";
$ocont2=$pp.'\r\n'.'-----(updated to)-----'.$ocont;


$stmt1 = <<<SQL
   	update  "¥«¥ë¥Æ¥Ç¥âÉ½" set "P"='$ocont2' where "ÆüÉÕ"='$tenkibi' and "´µ¼Ô"=$p_oid and 
	"Superseded" is null and "ID"=$idd
SQL;
//print $stmt1;
if (pg_query($db, $stmt1)){

}
else {
print '<p > karte update DB access error</p>';
die;
}

}

} //end else




//
  

    	
//    	$date = $this->data['OrderDate'];
//	$patient_objectid = $this->data['´µ¼Ô'];
 
 	simple_object_edit::commit($force); 
  
    

/* always claim_request */

$urgent = 0;
if (preg_match('/^(\d{4})-(\d+)-(\d+) /', $date, &$match)) {
	    $date = sprintf("%s-%s-%s", $match[1], $match[2], $match[3]);}

$stmt = 'INSERT INTO claim_request (patient, date_since, date_until, utime, result_flag) values ('. $patient_objectid.","."'". $date."'".",". "'". $date."'".","."current_timestamp".",".$urgent.")";

//print $stmt;
  
	 pg_query($db, $stmt); 


	}


}
?>

