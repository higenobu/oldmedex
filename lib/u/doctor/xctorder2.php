<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/so.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pdf4-xct.php';
//10-29-2014 revised added karte insert

function _lib_u_xct_kiroku3() {
  $db = mx_db_connect();
  $stmt = <<<SQL
    select "姓" || "名" as empname , userid
    from "職員台帳"
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array(NULL => '');
  foreach($rows as $row)
    $ret[$row['userid']] = $row['empname'];
  return $ret;
}

function _lib_u_xct_get_bui() {

$id_col = 'bui_code';



  $db = mx_db_connect();
  $stmt = <<<SQL
    select E."${id_col}" as id ,  E.bui_id as buiid, E.bui_name as name
    from bui_master4 E 
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array(NULL => '');
  foreach($rows as $row)
{
    if ($row['buiid']>300) {
    $ret[$row['id']] = "CT".$row['name'];}
else {
	 $ret[$row['id']] = $row['name'];
	}
}

  return $ret;


}

/*
function _lib_u_xct_get_shiji() {
 
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
function _lib_u_xct_get_gishi() {
  
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

function mk_enum($a) {
	$r = array();
	foreach ($a as $k) {
		if (trim($k) == '') {
			$r[NULL] = '';
		} else {
			$r[$k] = $k;
		}
	}
	return $r;
}


*/


function __lib_u_doctor_xctorder2_cfg(&$cfg)
{
$cfg = array_merge(

array(
TABLE => 'xctorder',
'ALLOW_SORT' =>1,

 COLS => array(
recorded,
 "orderdate" ,
 "plandate",
 "procdate" ,
  "患者" ,
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

"proof" 
 ),

LCOLS => array(

array('Column' => 'orderdate',
'Label' => '依頼日'),			      
array('Column' => 'plandate',
'Label' => '予定日'),
array('Column' => 'procdate',
'Label' => '実施日'),
array('Column' => 'stop',
'Label' => '中止'),

array('Column' => 'xctkubun',
					'Label' => 'XCT-Kubun',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
'170027910' => 'XP',
'170011810' => 'CT',
'170020110' => 'MRI'

						     )
				       ),



array('Column' => 'shiji',
					'Label' => '指示医',
				   
				       'Draw' => 'enum',
				         'Enum' =>_lib_u_xct_kiroku3(),

				       ),

array('Column' => 'gishi',
					'Label' => '技師',
				   
				       'Draw' => 'enum',
				        'Enum' => _lib_u_xct_kiroku3(),


				       ),

array('Column' => 'bui1',
					'Label' => '部位1',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken1',
					'Label' => '方向',
				   
				      
				      
				       ),
array('Column' => 'bui2',
					'Label' => '部位2',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken2',
					'Label' => '方向',
				   
				      
				      
				       ),
array('Column' => 'bui3',
					'Label' => '部位3',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),

array('Column' => 'syoken3',
					'Label' => '方向',
				   
				      
				      
				       ),
array('Column' => 'bui4',
					'Label' => '部位4',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken4',
					'Label' => '方向',
				   
				      
				      
				       ),
array('Column' => 'bui5',
					'Label' => '部位5',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),

array('Column' => 'syoken5',
					'Label' => '方向',
				   
				      
				      
				       ),


array('Column' => 'proof',
'Label' => '実施・未・済'),



),







DCOLS => array(
array('Column' => 'orderdate',
'Label' => '依頼日'
),			      
array('Column' => 'plandate',
'Label' => '予定日'),
array('Column' => 'procdate',
'Label' => '実施日'),
array('Column' => 'stop',
'Label' => '中止'),

array('Column' => 'xctkubun',
					'Label' => 'XCT-Kubun',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '170027910' => 'XP',
'170011810' => 'CT',
'170020110' => 'MRI'

						     )
				       ),

array('Column' => 'shiji',
					'Label' => '指示医',
				   
				      'Draw' => 'enum',
				         'Enum' =>_lib_u_xct_kiroku3(),
				        

				       ),

array('Column' => 'gishi',
					'Label' => '技師',
				   
				       'Draw' => 'enum',
				        'Enum' =>_lib_u_xct_kiroku3(),
				       ),

array('Column' => "CreatedBy",
					'Label' => '記録者',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_kiroku3(),

				       ),



array('Column' => 'memo1',
'Label' => '指示'),
				      
array('Column' => 'memo4',
'Label' => '目的'),
array('Column' => 'memo2',
'Label' => '処方'),
array('Column' => 'memo3',
'Label' => '電圧その他'),




array('Column' => 'bui1',
					'Label' => '部位1',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken1',
					'Label' => '方向',
				   
				      
				      
				       ),
array('Column' => 'bui2',
					'Label' => '部位2',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken2',
					'Label' => '方向',
				   
				      
				      
				       ),
array('Column' => 'bui3',
					'Label' => '部位3',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken3',
					'Label' => '方向',
				   
				      
				      
				       ),
array('Column' => 'bui4',
					'Label' => '部位4',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken4',
					'Label' => '方向',
				   
				      
				      
				       ),
array('Column' => 'bui5',
					'Label' => '部位5',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),

array('Column' => 'syoken5',
					'Label' => '方向',
				   
				      
				      
				       ),
array('Column' => 'techsyoken',
'Label' => '技師コメント'),			      
array('Column' => 'drsyoken',
'Label' => '医師所見'),
array('Column' => 'proof',
'Label' => '実施・未・済'),
array('Column' => 'recorded',
'Label' => '記録', 'Draw' => 'timestamp')


),

//***************************************************************
ECOLS => array(
array('Column' => 'orderdate','Label' => '依頼日',
'Draw' => 'date',
'Option' => array('validate' => 'date,nonnull', 'list' => 1)
),

array('Column' => 'plandate',
'Label' => '実施予定日',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'date')),

array('Column' => 'procdate',
'Label' => '実施日',
				       'Draw' => 'date',
				       'Option' => array('validate' =>

							 'date')),
//0701-2011
array('Column' => 'stop',
'Label' => '中止'),

array('Column' => 'xctkubun',
					'Label' => 'XCT-Kubun',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '170027910' => 'XP',
'170011810' => 'CT',
'170020110' => 'MRI'

						     )
				       ),


array('Column' => 'shiji', 'Draw' => 'enum', 'Enum' =>_lib_u_xct_kiroku3(),
		      'Option' => array('validate' => 'nonnull')),

array('Column' => 'gishi',
					'Label' => '技師',
				   
				       'Draw' => 'enum',
				        'Enum' =>_lib_u_xct_kiroku3(),
				       ),

array('Column' => 'memo4',
'Label' => '目的',
 'Draw' => 'text'),

 array('Column' => 'memo1',
					'Label' => '指示',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('SOLct'),
'cols' => 80)

),
array('Column' => 'memo2',
					'Label' => '処方',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 'イオバミロン300' => 'イオバミロン３００',
'オムニバーグ240' => 'オムニバーグ240',
'ガストログラフィン' => 'ガストログラフィン',
'バリトゲン３００' => 'バリトゲン３００',
'バレックスモルトＳ' => 'バレックスモルトＳ',
'バリエネマ３００' => 'バリエネマ３００',
'ビリスコビンＤＩＣ５０' => 'ビリスコビンＤＩＣ５０',
'その他の造影剤' => 'その他の造影剤',
'輸液点滴セット（ディスポ）' => '輸液点滴セット（ディスポ）',

'翼状針' => '翼状針',

'生食注' => '生食注',
'スポラミン注シリンジ（20ml/1ml）' => 'スポラミン注シリンジ（20ml/1ml）',
'グルカゴン' => 'グルカゴン',
						     )
				       ),

 array('Column' => 'memo3',
					'Label' => '電圧その他',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('SOLct'),
'cols' => 80)

),



array('Column' => 'bui1',
					'Label' => '部位1',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),

array('Column' => 'syoken1',
					'Label' => '方向',
				   
				       'Draw' => 'text'
				       

				       ),



array('Column' => 'memo12',
					'Label' => '回数１',
				    'Draw' => 'text'),
array('Column' => 'bui2',
					'Label' => '部位2',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),

array('Column' => 'syoken2',
					'Label' => '方向',
				   
				       'Draw' => 'text'
				       

				       ),



array('Column' => 'memo22',
					'Label' => '回数2',
				    'Draw' => 'text'),


array('Column' => 'bui3',
					'Label' => '部位3',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken3',
					'Label' => '方向',
				   
				       'Draw' => 'text'
				       

				       ),




array('Column' => 'memo32',
					'Label' => '回数3',
				    'Draw' => 'text'),
array('Column' => 'bui4',
					'Label' => '部位4',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),


array('Column' => 'syoken4',
					'Label' => '方向',
				   
				       'Draw' => 'text'
				       

				       ),


array('Column' => 'memo42',
					'Label' => '回数4',
				    'Draw' => 'text'),
array('Column' => 'bui5',
					'Label' => '部位5',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),

array('Column' => 'syoken5',
					'Label' => '方向',
				   
				       'Draw' => 'text'
				       

				       ),



array('Column' => 'memo52',
					'Label' => '回数5',
				    'Draw' => 'text'),



array('Column' => 'drsyoken',
'Label' => '医師所見',
'Draw' => 'textarea',
'Option' => array('vocab' => array('SOLdoc'),
'cols' => 80)
),

array('Column' => 'techsyoken',
'Label' => '技師コメント',
'Draw' => 'textarea',
'Option' => array('vocab' => array('SOLgishi'),
'cols' => 80)
),
array('Column' => 'recorded',
'Label' => '記録', 'Draw' => 'timestamp'),

 array('Column' => 'proof',
'Label' => '実施・未・済・承認',

				       'Draw' => 'enum',
				       'Enum' => array('未実施' => '未実施',
						       
						       '技師実施' => '技師実施',
						       '医師承認' => '医師承認'
						     ),
				       'Option' => array('validate' =>
							 'nonnull'))



)
), $cfg);
	return $cfg;
}

class list_of_xctorder2s extends list_of_ppa_objects {
	function list_of_xctorder2s($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_xctorder2_cfg($cfg);
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

class xctorder2_display extends simple_object_display {
	
function xctorder2_display($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_xctorder2_cfg($cfg);
		simple_object_display::simple_object_display($prefix, $cfg);
	}

function print_sod() {
    go_pdf_xct($this->id, 0);
  }

}






 //0414-2012 test

/*
function print_sod($template='srl') {
    $db = mx_db_connect();

    $oid = $this->id;
    $stmt = 'SELECT "ID" from "xctorder" WHERE "ObjectID"=' . $oid;
    $rs = mx_db_fetch_single($db, $stmt);
    if(is_null($rs))
      return;

    $status = 0;
   
    
    $s =  <<< HTML
    <SCRIPT LANGUAGE="JavaScript">
       window.open("print12.php?test_app_type=${test_app_type}&status=${status}&oid={$oid}", "","width=640,height=640");
    </SCRIPT>
HTML;
    print $s;
  }

}

*/

 



class xctorder2_edit extends simple_object_edit {
	function xctorder2_edit($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_xctorder2_cfg($cfg);
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}


function commit($force=NULL) {
  

    $this->data['患者'] = $this->so_config['Patient_ObjectID'];
simple_object_edit::commit($force); 
    $db = mx_db_connect();
    $date = $this->data['procdate'];
 $patient_objectid = $this->data['患者'];
//insert into karte

$p_oid=$patient_objectid;
//


//
 $name=_lib_u_xct_get_bui(); 

$type=array('' => '',
						      
'170027910' => 'XP',
'170011810' => 'CT',
'170020110' => 'MRI'

						     );
// print_r($name);
$byomei=$type[$this->data["xctkubun"]];
$kaishi=$this->data['orderdate'];
$tenkibi=$this->data['plandate'];
$tenki=$this->data['shiji'];

$st=$name[$this->data['bui1']];
$st2=$name[$this->data['bui2']];
$st3=$name[$this->data['bui3']];
$bi1=$name[$this->data["bui4"]];
$bi2=$name[$this->data["bui5"]];

$ocont="----------------------------\n"."IMAGE\n".'実施日='.$date.'オーダ日='.$kaishi.'予定日='.$tenkibi.'指示医='.$tenki."\n  "."TYPE=".$byomei." "."BUI=".$st." ".$st2." ".$st3." ".$bi1." ".$bi2."\n";

//print $ocont;
 
 
//new 10-24-2014
$stmt10 = <<<SQL
select * from "カルテデモ表" where "日付"='$kaishi' and "患者"=$p_oid and 
	"Superseded" is null;
SQL;
 
// print $stmt10;

$rs0 = mx_db_fetch_all($db, $stmt10);
//print "all?".count($rs0);
if (count($rs0) == 0){
$stmt11 = <<<SQL
INSERT INTO "カルテデモ表" ("患者", "日付","P") values ($p_oid,'$kaishi','$ocont');
SQL;
 
//print $stmt11;

if (pg_query($db, $stmt11)){

	}
else {
print '<p > DB access error</p>';
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
   	update  "カルテデモ表" set "P"='$ocont2' where "日付"='$kaishi' and "患者"=$p_oid and 
	"Superseded" is null and "ID"=$idd
SQL;
//print $stmt1;
if (pg_query($db, $stmt1)){

}
else {
print '<p > DB access error</p>';
die;
}

}

} //end else

 

//
// simple_object_edit::commit($force); 
  
 



$urgent = 0;
if (preg_match('/^(\d{4})-(\d+)-(\d+) /', $date, &$match)) {
	    $date = sprintf("%s-%s-%s", $match[1], $match[2], $match[3]);}

$stmt = 'INSERT INTO claim_request (patient, date_since, date_until, utime, result_flag) values ('. $patient_objectid.","."'". $date."'".",". "'". $date."'".","."current_timestamp".",".$urgent.")";

//print $stmt;
  
	 pg_query($db, $stmt); 


 
     
  }




 function anew_tweak($orig_id) {
		$this->data['orderdate'] = mx_today_string();
		$this->data['recorded'] = date("Y-m-d H:i:s");

	} 

function edit_tweak() {
		$this->data['recorded'] =  date("Y-m-d H:i:s");

	}
	/* could inherit from simple_object_ppa_edit */
	
/* 0407-2011 change  
function commit($force=NULL) {
		$this->data['患者'] = $this->so_config['Patient_ObjectID'];
	

		return simple_object_edit::commit($force);
	}  */

}
?>

