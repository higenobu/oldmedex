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
    select "��" || "̾" as empname , userid
    from "������Ģ"
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
  "����" ,
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
'Label' => '������'),			      
array('Column' => 'plandate',
'Label' => 'ͽ����'),
array('Column' => 'procdate',
'Label' => '�»���'),
array('Column' => 'stop',
'Label' => '���'),
array('Column' => 'xctkubun',
					'Label' => '���',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
'�̾�' => '�̾�',						      
'���' => '���'


						     )
				       ),

array('Column' => 'shiji',
					'Label' => '�ؼ���',
				   
				       'Draw' => 'text',
				        

				       ),

array('Column' => 'gishi',
					'Label' => '��������',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_sei_gishi()

				       ),
array('Column' => "CreatedBy",
					'Label' => '��Ͽ��',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_sei_kiroku()

				       ),

array('Column' => 'bui1',
					'Label' => '����1',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),
array('Column' => 'syoken1',
					'Label' => '������',
				   
				      
				      
				       ),
array('Column' => 'bui2',
					'Label' => '����2',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),
array('Column' => 'syoken2',
					'Label' => '���',
				   
				      
				      
				       ),
array('Column' => 'bui3',
					'Label' => '����3',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),

array('Column' => 'syoken3',
					'Label' => '������',
				   
				      
				      
				       ),
array('Column' => 'bui4',
					'Label' => '����4',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),
array('Column' => 'syoken4',
					'Label' => '���',
				   
				      
				      
				       ),
array('Column' => 'bui5',
					'Label' => '����5',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),

array('Column' => 'syoken5',
					'Label' => '������',
				   
				      
				      
				       ),


array('Column' => 'proof',
'Label' => '�»ܡ�̤����')),





//*******************************************************************8

DCOLS => array(
array('Column' => 'orderdate',
'Label' => '������'
),			      
array('Column' => 'plandate',
'Label' => 'ͽ����'),
array('Column' => 'procdate',
'Label' => '�»���'),
array('Column' => 'stop',
'Label' => '���'),
array('Column' => 'xctkubun',
					'Label' => '���',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
'�̾�' => '�̾�',						      
'���' => '���'


						     )
				       ),

array('Column' => 'shiji',
					'Label' => '�ؼ���',
				   
				       'Draw' => 'text',
				        

				       ),

array('Column' => 'gishi',
					'Label' => '����',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_sei_gishi()

				       ),
array('Column' => "CreatedBy",
					'Label' => '��Ͽ��',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_sei_kiroku()

				       ),

array('Column' => 'memo1',
'Label' => '�ؼ�'),
array('Column' => 'memo4',
					'Label' => '���',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
'���' => '���',						      
'�׻�' => '�׻�'


						     )
				       ),				      

array('Column' => 'memo2',
'Label' => '����'),
array('Column' => 'memo3',
'Label' => '�Ű�����¾'),
/* array('Column' => 'bui1',
'Label' => '������'),*/



array('Column' => 'bui1',
					'Label' => '����1',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),
array('Column' => 'syoken1',
					'Label' => '������',
				   
				      
				      
				       ),
array('Column' => 'bui2',
					'Label' => '����2',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),
array('Column' => 'syoken2',
					'Label' => '������',
				   
				      
				      
				       ),
array('Column' => 'bui3',
					'Label' => '����3',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),
array('Column' => 'syoken3',
					'Label' => '������',
				   
				      
				      
				       ),
array('Column' => 'bui4',
					'Label' => '����4',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),
array('Column' => 'syoken4',
					'Label' => '������',
				   
				      
				      
				       ),
array('Column' => 'bui5',
					'Label' => '����5',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),

array('Column' => 'syoken5',
					'Label' => '������',
				   
				      
				      
				       ),
array('Column' => 'techsyoken',
'Label' => '���ե�����'),			      
array('Column' => 'drsyoken',
'Label' => '��ս긫'),
array('Column' => 'recorded',
'Label' => '��Ͽ', 'Draw' => 'timestamp'),
array('Column' => 'proof',
'Label' => '�»ܡ�̤����')
),

//***************************************************************
ECOLS => array(
array('Column' => 'orderdate','Label' => '������',
'Draw' => 'date',
'Option' => array('validate' => 'date,nonnull', 'list' => 1)
),

array('Column' => 'plandate',
'Label' => '�»�ͽ����',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'date')),

array('Column' => 'procdate',
'Label' => '�»���',
				       'Draw' => 'date',
				       'Option' => array('validate' =>

							 'date')),
//0701-2011
array('Column' => 'stop',
'Label' => '���'),

array('Column' => 'xctkubun',
					'Label' => '���',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
'�̾�' => '�̾�',						      
'���' => '���'


						     )
				       ),

array('Column' => 'memo4',
					'Label' => '���',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
'���' => '���',						      
'�׻�' => '�׻�'


						     )
				       ),				      


array('Column' => 'shiji',
					'Label' => '�ؼ���',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_sei_shiji()

				       ),
array('Column' => 'gishi',
					'Label' => '����',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_sei_gishi()

				       ),



 array('Column' => 'memo1',
					'Label' => '�ؼ�',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('SOLct'),
'cols' => 80)

),



array('Column' => 'bui1',
					'Label' => '����1',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),

array('Column' => 'syoken1',
					'Label' => '������',
				   
				       'Draw' => 'text'
				       

				       ),



array('Column' => 'memo12',
					'Label' => '�����',
				    'Draw' => 'text'),
array('Column' => 'bui2',
					'Label' => '����2',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),

array('Column' => 'syoken2',
					'Label' => '������',
				   
				       'Draw' => 'text'
				       

				       ),



array('Column' => 'memo22',
					'Label' => '���2',
				    'Draw' => 'text'),


array('Column' => 'bui3',
					'Label' => '����3',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),
array('Column' => 'syoken3',
					'Label' => '������',
				   
				       'Draw' => 'text'
				       

				       ),




array('Column' => 'memo32',
					'Label' => '���3',
				    'Draw' => 'text'),
array('Column' => 'bui4',
					'Label' => '����4',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),


array('Column' => 'syoken4',
					'Label' => '������',
				   
				       'Draw' => 'text'
				       

				       ),


array('Column' => 'memo42',
					'Label' => '���4',
				    'Draw' => 'text'),
array('Column' => 'bui5',
					'Label' => '����5',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_kensa()

				       ),

array('Column' => 'syoken5',
					'Label' => '������',
				   
				       'Draw' => 'text'
				       

				       ),



array('Column' => 'memo52',
					'Label' => '���5',
				    'Draw' => 'text'),



array('Column' => 'drsyoken',
'Label' => '��ս긫',
'Draw' => 'textarea',
'Option' => array('vocab' => array('SOLdoc'),
'cols' => 80)
),

array('Column' => 'techsyoken',
'Label' => '���ե�����',
'Draw' => 'textarea',
'Option' => array('vocab' => array('SOLgishi'),
'cols' => 80)
),

array('Column' => 'recorded',
'Label' => '��Ͽ', 'Draw' => 'timestamp'),
 array('Column' => 'proof',
'Label' => '�»ܡ�̤���ѡ���ǧ',

				       'Draw' => 'enum',
				       'Enum' => array('̤�»�' => '̤�»�',
						       
						       '���ռ»�' => '���ռ»�',
						       '��վ�ǧ' => '��վ�ǧ'
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
  

    $this->data['����'] = $this->so_config['Patient_ObjectID'];

    $db = mx_db_connect();
    $date = $this->data['procdate'];
 $patient_objectid = $this->data['����'];
$p_oid = $this->data['����'];
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

$ocont="----------------------------\n"."��������\n".'�»���='.$date.'��������='.$kaishi.'ͽ����='.$tenkibi.'�ؼ���='.$tenki."\n  "."TYPE=".$byomei." "."����=".$st." ".$st2." ".$st3." ".$bi1." ".$bi2."\n";

//print $ocont;
 
 
//new 10-24-2014
$stmt10 = <<<SQL
select * from "����ƥǥ�ɽ" where "����"='$kaishi' and "����"=$p_oid and 
	"Superseded" is null;
SQL;
 
// print $stmt10;

$rs0 = mx_db_fetch_all($db, $stmt10);
//print "all?".count($rs0);
if (count($rs0) == 0){
$stmt11 = <<<SQL
INSERT INTO "����ƥǥ�ɽ" ("����", "����","P") values ($p_oid,'$kaishi','$ocont');
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
   	update  "����ƥǥ�ɽ" set "P"='$ocont2' where "����"='$kaishi' and "����"=$p_oid and 
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

