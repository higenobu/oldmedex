<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/so.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pdf4.php';

function _lib_u_sei_kiroku() {
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


function _lib_u_kensa() {
  




  $db = mx_db_connect();
  $stmt = <<<SQL
    select  medis_cd as cd ,  kensa_name as name
    from seiri_master   
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array(NULL => '');
  foreach($rows as $row)

	 $ret[$row['cd']] = $row['name'];
	

  return $ret;
}

function _lib_u_sei_shiji() {
  
$id_col = 'id';




  $db = mx_db_connect();
  $stmt = <<<SQL
    select E."${id_col}" as id ,  "name" as name
    from modalities E where rtype=904
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array(NULL => '');
  foreach($rows as $row)
    $ret[$row['name']] = $row['name'];
  return $ret;
}


//0617-2011 fro gishi
function _lib_u_sei_gishi() {
  
$id_col = 'id';




  $db = mx_db_connect();
  $stmt = <<<SQL
    select E."${id_col}" as id ,  "name" as name
    from modalities E where rtype= 905 
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array(NULL => '');
  foreach($rows as $row)
    $ret[$row['name']] = $row['name'];
  return $ret;
}

//**********************************






function __lib_u_seiorder_cfg(&$cfg)
{
$cfg = array_merge(

array(
TABLE => 'seiorder',
'ALLOW_SORT' =>1,

 COLS => array(
	
 "orderdate" ,
 "plandate",
 "procdate" ,
  "´µ¼Ô" ,
"stop",
  "teikikubun",
  "xctkubun" ,
  "techname",
  "techid" ,
  "bui1" ,
  "bui2" ,
 "bui3" ,
 "bui4" ,
 "bui5",
"memo1",
"memo2" ,
"memo3" ,
"memo4" ,
"memo5" ,
"memo11" ,
"memo21" ,
"memo31" ,
"memo41" ,
"memo51",
"memo12" ,
"memo22" ,
"memo32" ,
"memo42" ,
"memo52",
"syoken1" ,
"syoken2" ,
"syoken3" ,
"syoken4" ,
"syoken5" ,
"techsyoken" ,
"drsyoken" ,
"shiji",
"gishi",
"recorded",
"proof" 
 ),

LCOLS => array(

array('Column' => 'orderdate',
'Label' => '°ÍÍêÆü'),			      
array('Column' => 'plandate',
'Label' => 'Í½ÄêÆü'),
array('Column' => 'procdate',
'Label' => '¼Â»ÜÆü'),
array('Column' => 'stop',
'Label' => 'Ãæ»ß'),
array('Column' => 'xctkubun',
					'Label' => '»êµÞ',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
'ÄÌ¾ï' => 'ÄÌ¾ï',						      
'»êµÞ' => '»êµÞ'


						     )
				       ),

array('Column' => 'shiji',
					'Label' => '»Ø¼¨°å',
				   
				       'Draw' => 'text',
				        

				       ),

array('Column' => 'gishi',
					'Label' => '¸¡ººµ»»Õ',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_sei_gishi()

				       ),
array('Column' => "CreatedBy",
					'Label' => 'µ­Ï¿¼Ô',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_sei_kiroku()

				       ),

array('Column' => 'bui1',
					'Label' => '¸¡ºº1',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),
array('Column' => 'syoken1',
					'Label' => '¥³¥á¥ó¥È',
				   
				      
				      
				       ),
array('Column' => 'bui2',
					'Label' => '¸¡ºº2',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),
array('Column' => 'syoken2',
					'Label' => '²ó¿ô',
				   
				      
				      
				       ),
array('Column' => 'bui3',
					'Label' => '¸¡ºº3',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),

array('Column' => 'syoken3',
					'Label' => '¥³¥á¥ó¥È',
				   
				      
				      
				       ),
array('Column' => 'bui4',
					'Label' => '¸¡ºº4',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),
array('Column' => 'syoken4',
					'Label' => '²ó¿ô',
				   
				      
				      
				       ),
array('Column' => 'bui5',
					'Label' => '¸¡ºº5',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),

array('Column' => 'syoken5',
					'Label' => '¥³¥á¥ó¥È',
				   
				      
				      
				       ),


array('Column' => 'proof',
'Label' => '¼Â»Ü¡¦Ì¤¡¦ºÑ')),





//*******************************************************************8

DCOLS => array(
array('Column' => 'orderdate',
'Label' => '°ÍÍêÆü'
),			      
array('Column' => 'plandate',
'Label' => 'Í½ÄêÆü'),
array('Column' => 'procdate',
'Label' => '¼Â»ÜÆü'),
array('Column' => 'stop',
'Label' => 'Ãæ»ß'),
array('Column' => 'xctkubun',
					'Label' => '»êµÞ',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
'ÄÌ¾ï' => 'ÄÌ¾ï',						      
'»êµÞ' => '»êµÞ'


						     )
				       ),

array('Column' => 'shiji',
					'Label' => '»Ø¼¨°å',
				   
				       'Draw' => 'text',
				        

				       ),

array('Column' => 'gishi',
					'Label' => 'µ»»Õ',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_sei_gishi()

				       ),
array('Column' => "CreatedBy",
					'Label' => 'µ­Ï¿¼Ô',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_sei_kiroku()

				       ),

array('Column' => 'memo1',
'Label' => '»Ø¼¨'),
array('Column' => 'memo4',
					'Label' => 'Äê´ü',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
'Äê´ü' => 'Äê´ü',						      
'Î×»þ' => 'Î×»þ'


						     )
				       ),				      

array('Column' => 'memo2',
'Label' => '½èÊý'),
array('Column' => 'memo3',
'Label' => 'ÅÅ°µ¤½¤ÎÂ¾'),
/* array('Column' => 'bui1',
'Label' => '¸¡ºº£±'),*/



array('Column' => 'bui1',
					'Label' => '¸¡ºº1',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),
array('Column' => 'syoken1',
					'Label' => '¥³¥á¥ó¥È',
				   
				      
				      
				       ),
array('Column' => 'bui2',
					'Label' => '¸¡ºº2',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),
array('Column' => 'syoken2',
					'Label' => '¥³¥á¥ó¥È',
				   
				      
				      
				       ),
array('Column' => 'bui3',
					'Label' => '¸¡ºº3',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),
array('Column' => 'syoken3',
					'Label' => '¥³¥á¥ó¥È',
				   
				      
				      
				       ),
array('Column' => 'bui4',
					'Label' => '¸¡ºº4',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),
array('Column' => 'syoken4',
					'Label' => '¥³¥á¥ó¥È',
				   
				      
				      
				       ),
array('Column' => 'bui5',
					'Label' => '¸¡ºº5',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),

array('Column' => 'syoken5',
					'Label' => '¥³¥á¥ó¥È',
				   
				      
				      
				       ),
array('Column' => 'techsyoken',
'Label' => 'µ»»Õ¥³¥á¥ó¥È'),			      
array('Column' => 'drsyoken',
'Label' => '°å»Õ½ê¸«'),
array('Column' => 'recorded',
'Label' => 'µ­Ï¿', 'Draw' => 'timestamp'),
array('Column' => 'proof',
'Label' => '¼Â»Ü¡¦Ì¤¡¦ºÑ')
),

//***************************************************************
ECOLS => array(
array('Column' => 'orderdate','Label' => '°ÍÍêÆü',
'Draw' => 'date',
'Option' => array('validate' => 'date,nonnull', 'list' => 1)
),

array('Column' => 'plandate',
'Label' => '¼Â»ÜÍ½ÄêÆü',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'date')),

array('Column' => 'procdate',
'Label' => '¼Â»ÜÆü',
				       'Draw' => 'date',
				       'Option' => array('validate' =>

							 'date')),
//0701-2011
array('Column' => 'stop',
'Label' => 'Ãæ»ß'),

array('Column' => 'xctkubun',
					'Label' => '»êµÞ',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
'ÄÌ¾ï' => 'ÄÌ¾ï',						      
'»êµÞ' => '»êµÞ'


						     )
				       ),

array('Column' => 'memo4',
					'Label' => 'Äê´ü',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
'Äê´ü' => 'Äê´ü',						      
'Î×»þ' => 'Î×»þ'


						     )
				       ),				      


array('Column' => 'shiji',
					'Label' => '»Ø¼¨°å',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_sei_shiji()

				       ),
array('Column' => 'gishi',
					'Label' => 'µ»»Õ',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_sei_gishi()

				       ),



 array('Column' => 'memo1',
					'Label' => '»Ø¼¨',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('SOLct'),
'cols' => 80)

),



array('Column' => 'bui1',
					'Label' => '¸¡ºº1',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),

array('Column' => 'syoken1',
					'Label' => '¥³¥á¥ó¥È',
				   
				       'Draw' => 'text'
				       

				       ),



array('Column' => 'memo12',
					'Label' => '²ó¿ô£±',
				    'Draw' => 'text'),
array('Column' => 'bui2',
					'Label' => '¸¡ºº2',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),

array('Column' => 'syoken2',
					'Label' => '¥³¥á¥ó¥È',
				   
				       'Draw' => 'text'
				       

				       ),



array('Column' => 'memo22',
					'Label' => '²ó¿ô2',
				    'Draw' => 'text'),


array('Column' => 'bui3',
					'Label' => '¸¡ºº3',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),
array('Column' => 'syoken3',
					'Label' => '¥³¥á¥ó¥È',
				   
				       'Draw' => 'text'
				       

				       ),




array('Column' => 'memo32',
					'Label' => '²ó¿ô3',
				    'Draw' => 'text'),
array('Column' => 'bui4',
					'Label' => '¸¡ºº4',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),


array('Column' => 'syoken4',
					'Label' => '¥³¥á¥ó¥È',
				   
				       'Draw' => 'text'
				       

				       ),


array('Column' => 'memo42',
					'Label' => '²ó¿ô4',
				    'Draw' => 'text'),
array('Column' => 'bui5',
					'Label' => '¸¡ºº5',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),

array('Column' => 'syoken5',
					'Label' => '¥³¥á¥ó¥È',
				   
				       'Draw' => 'text'
				       

				       ),



array('Column' => 'memo52',
					'Label' => '²ó¿ô5',
				    'Draw' => 'text'),



array('Column' => 'drsyoken',
'Label' => '°å»Õ½ê¸«',
'Draw' => 'textarea',
'Option' => array('vocab' => array('SOLdoc'),
'cols' => 80)
),

array('Column' => 'techsyoken',
'Label' => 'µ»»Õ¥³¥á¥ó¥È',
'Draw' => 'textarea',
'Option' => array('vocab' => array('SOLgishi'),
'cols' => 80)
),

array('Column' => 'recorded',
'Label' => 'µ­Ï¿', 'Draw' => 'timestamp'),
 array('Column' => 'proof',
'Label' => '¼Â»Ü¡¦Ì¤¡¦ºÑ¡¦¾µÇ§',

				       'Draw' => 'enum',
				       'Enum' => array('Ì¤¼Â»Ü' => 'Ì¤¼Â»Ü',
						       
						       'µ»»Õ¼Â»Ü' => 'µ»»Õ¼Â»Ü',
						       '°å»Õ¾µÇ§' => '°å»Õ¾µÇ§'
						     ),
				       'Option' => array('validate' =>
							 'nonnull'))

)
), $cfg);
	return $cfg;
}

class list_of_seiorders extends list_of_ppa_objects {
	function list_of_seiorders($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_seiorder_cfg($cfg);
		list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
	}

function row_paging_orders() {
		$paging_keys = $this->row_paging_keys();
		$paging_orders = array();
		foreach ($paging_keys as $col) {
			if ($col == 'orderdate' ||$col == 'ObjectID')
				$paging_orders[] = 1;
			else
				$paging_orders[] = 0;
		}
		return $paging_orders;
	}




}

class seiorder_display extends simple_object_display {
	
function seiorder_display($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_seiorder_cfg($cfg);
		simple_object_display::simple_object_display($prefix, $cfg);
	}
/*
function print_sod() {
    go_pdf($this->id, 0);
  }

}
*/



function print_sod($template='srl') {
    $db = mx_db_connect();

    $oid = $this->id;
    $stmt = 'SELECT "ID" from "seiorder" WHERE "ObjectID"=' . $oid;
    $rs = mx_db_fetch_single($db, $stmt);
    if(is_null($rs))
      return;

    $status = 0;
 //prints or printseiri 10-28-2014  
    
    $s =  <<< HTML
    <SCRIPT LANGUAGE="JavaScript">
       window.open("prints.php? status=${status}&oid={$oid}", "","width=640,height=640");
    </SCRIPT>
HTML;
    print $s;
  }

}



class seiorder_edit extends simple_object_edit {
	function seiorder_edit($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_seiorder_cfg($cfg);
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}


function commit($force=NULL) {
  

    $this->data['´µ¼Ô'] = $this->so_config['Patient_ObjectID'];

    $db = mx_db_connect();
    $date = $this->data['procdate'];
 $patient_objectid = $this->data['´µ¼Ô'];
$p_oid = $this->data['´µ¼Ô'];
//print $o_oid;
//10-29-2014
 $name=_lib_u_kensa(); 
//print_r($name);

$byomei=$this->data["xctkubun"];
$kaishi=$this->data['orderdate'];
$tenkibi=$this->data['plandate'];
$tenki=$this->data['shiji'];

$st=$name[$this->data['bui1']];
$st2=$name[$this->data['bui2']];
$st3=$name[$this->data['bui3']];
$bi1=$name[$this->data["bui4"]];
$bi2=$name[$this->data["bui5"]];

$ocont="----------------------------\n"."À¸Íý¸¡ºº\n".'¼Â»ÜÆü='.$date.'¥ª¡¼¥ÀÆü='.$kaishi.'Í½ÄêÆü='.$tenkibi.'»Ø¼¨°å='.$tenki."\n  "."TYPE=".$byomei." "."¸¡ºº=".$st." ".$st2." ".$st3." ".$bi1." ".$bi2."\n";

//print $ocont;
 
 
//new 10-24-2014
$stmt10 = <<<SQL
select * from "¥«¥ë¥Æ¥Ç¥âÉ½" where "ÆüÉÕ"='$kaishi' and "´µ¼Ô"=$p_oid and 
	"Superseded" is null;
SQL;
 
// print $stmt10;

$rs0 = mx_db_fetch_all($db, $stmt10);
//print "all?".count($rs0);
if (count($rs0) == 0){
$stmt11 = <<<SQL
INSERT INTO "¥«¥ë¥Æ¥Ç¥âÉ½" ("´µ¼Ô", "ÆüÉÕ","P") values ($p_oid,'$kaishi','$ocont');
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
   	update  "¥«¥ë¥Æ¥Ç¥âÉ½" set "P"='$ocont2' where "ÆüÉÕ"='$kaishi' and "´µ¼Ô"=$p_oid and 
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
 simple_object_edit::commit($force); 
  
    

/* always claim_request */

$urgent = 0;
if (preg_match('/^(\d{4})-(\d+)-(\d+) /', $date, &$match)) {
	    $date = sprintf("%s-%s-%s", $match[1], $match[2], $match[3]);}

$stmt = 'INSERT INTO claim_request (patient, date_since, date_until, utime, result_flag) values ('. $patient_objectid.","."'". $date."'".",". "'". $date."'".","."current_timestamp".",".$urgent.")";

//print $stmt;
  
	 pg_query($db, $stmt); 



     
  }




 function anew_tweak($orig_id) {
		$this->data['orderdate'] = mx_today_string();
		$this->data['recorded'] =  date("Y-m-d H:i:s");
		
	} 
function edit_tweak() {
//		$this->data['orderdate'] = mx_today_string();
		
		$this->data['recorded'] =  date("Y-m-d H:i:s");
 		
	}


	/* could inherit from simple_object_ppa_edit */
	

}
?>

