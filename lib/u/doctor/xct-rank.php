<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/doctor/xctorder2.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pdf4.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/so.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';

function mk_enum_n($a) {
	$r = array(0=>'',1=>'today');
       $i=2;
	while($i <=$a) {
		
			$r[$i] = $i;
			$i++;

		}
	
	return $r;
}

function _lib_u_xct_get_pt_name() {
  $db = mx_db_connect();
  $stmt = <<<SQL
    select  distinct(pt_id), pt_nm from xctorder, tbl_patient where  pt_id="����"  and orderdate >date'today'-50

SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array();
  foreach($rows as $row)
    $ret[$row['pt_id']] = $row['pt_nm'];
  return $ret;
}



$_lib_u_manage_xct_rank_cfg = array
(
'COLS' => array(

	"orderdate" ,
 "plandate",
 "procdate" ,
  "����" ,
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

 'TABLE' => 'xctorder',
'ALLOW_SORT' =>1,

'ENABLE_QBE' => array(
		      
		       array('Column' => 'orderdate','Label'=>'��������' ),
			array('Column' => 'plandate','Label'=>'ͽ����' ),
			array('Column' => 'procdate','Label'=>'�»���' ),

		       
		       ),
//*****************************************
LCOLS => array(
array('Column' =>  "����",
'Label' => '����','Draw' => 'enum','Enum'=>_lib_u_xct_get_pt_name(),

),	
array('Column' => 'orderdate',
'Label' => '������'),			      
array('Column' => 'plandate',
'Label' => 'ͽ����'),
array('Column' => 'procdate',
'Label' => '�»���'),
/*array('Column' => 'xctkubun',
'Label' => 'CXT-Kubun'),*/
array('Column' => 'xctkubun',
					'Label' => 'XCT-Kubun',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '170001910' => 'XP',
'170011810' => 'CT',
'170020110' => 'MRI'

						     )
				       ),

array('Column' => 'shiji',
					'Label' => '�ؼ���',
				   
				       'Draw' => 'text',
				        

				       ),

array('Column' => 'gishi',
					'Label' => '����',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_gishi()

				       ),

array('Column' => "CreatedBy",
					'Label' => '��Ͽ��',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_kiroku()

				       ),




array('Column' => 'bui1',
					'Label' => '����1',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken1',
					'Label' => '����',
				   
				      
				      
				       ),
array('Column' => 'bui2',
					'Label' => '����2',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken2',
					'Label' => '����',
				   
				      
				      
				       ),
array('Column' => 'bui3',
					'Label' => '����3',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),

array('Column' => 'syoken3',
					'Label' => '����',
				   
				      
				      
				       ),
array('Column' => 'bui4',
					'Label' => '����4',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken4',
					'Label' => '����',
				   
				      
				      
				       ),
array('Column' => 'bui5',
					'Label' => '����5',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),

array('Column' => 'syoken5',
					'Label' => '����',
				   
				      
				      
				       ),


array('Column' => 'proof',
'Label' => '�»ܡ�̤����')),





//******************************************************************* 

DCOLS => array(
array('Column' =>  "����",
'Label' => '����','Draw' => 'enum','Enum'=>_lib_u_xct_get_pt_name(),

),
array('Column' => 'orderdate',
'Label' => '������'
),			      
array('Column' => 'plandate',
'Label' => 'ͽ����'),
array('Column' => 'procdate',
'Label' => '�»���'),
/* array('Column' => 'xctkubun',
'Label' => 'XCT-KUBUN'), */
array('Column' => 'xctkubun',
					'Label' => 'XCT-Kubun',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '170001910' => 'X',
'170011810' => 'CT',
'170020110' => 'MRI'

						     )
				       ),

array('Column' => 'shiji',
					'Label' => '�ؼ���',
				   
				       'Draw' => 'text',
				        

				       ),

array('Column' => 'gishi',
					'Label' => '����',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_gishi()

				       ),

array('Column' => "CreatedBy",
					'Label' => '��Ͽ��',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_kiroku()

				       ),



array('Column' => 'memo1',
'Label' => '�ؼ�'),
				      
array('Column' => 'memo4',
'Label' => '��Ū'),
array('Column' => 'memo2',
'Label' => '����'),
array('Column' => 'memo3',
'Label' => '�Ű�����¾'),
/* array('Column' => 'bui1',
'Label' => '���̣�'),*/



array('Column' => 'bui1',
					'Label' => '����1',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken1',
					'Label' => '����',
				   
				      
				      
				       ),
array('Column' => 'bui2',
					'Label' => '����2',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken2',
					'Label' => '����',
				   
				      
				      
				       ),
array('Column' => 'bui3',
					'Label' => '����3',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken3',
					'Label' => '����',
				   
				      
				      
				       ),
array('Column' => 'bui4',
					'Label' => '����4',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken4',
					'Label' => '����',
				   
				      
				      
				       ),
array('Column' => 'bui5',
					'Label' => '����5',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),

array('Column' => 'syoken5',
					'Label' => '����',
				   
				      
				      
				       ),
array('Column' => 'techsyoken',
'Label' => '���ե�����'),			      
array('Column' => 'drsyoken',
'Label' => '��ս긫'),
array('Column' => 'proof',
'Label' => '�»ܡ�̤����')
),
//*******************************************

ECOLS => array(
array('Column' =>  "����",
'Label' => '����','Draw' => 'static'

),
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

array('Column' => 'xctkubun',
					'Label' => 'XCT-Kubun',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '170001910' => 'X',
'170011810' => 'CT',
'170020110' => 'MRI'

						     )
				       ),


array('Column' => 'shiji',
					'Label' => '�ؼ���',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_shiji()

				       ),
array('Column' => 'gishi',
					'Label' => '����',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_gishi()

				       ),

array('Column' => 'memo4',
'Label' => '��Ū',
 'Draw' => 'text'),

 array('Column' => 'memo1',
					'Label' => '�ؼ�',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('SOLct'),
'cols' => 80)

),
array('Column' => 'memo2',
					'Label' => '����',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '�����Хߥ��300' => '�����Хߥ�󣳣���',
'����˥С���240' => '����˥С���240',
'�����ȥ���ե���' => '�����ȥ���ե���',
'�Х�ȥ��󣳣���' => '�Х�ȥ��󣳣���',
'�Х�å������ȣ�' => '�Х�å������ȣ�',
'�Хꥨ�ͥޣ�����' => '�Хꥨ�ͥޣ�����',
'�ӥꥹ���ӥ�ģɣã���' => '�ӥꥹ���ӥ�ģɣã���',
'����¾��¤�ƺ�' => '����¾��¤�ƺ�',
'͢����ũ���åȡʥǥ����ݡ�' => '͢����ũ���åȡʥǥ����ݡ�',

'�����' => '�����',

'������' => '������',
'���ݥ�ߥ�����󥸡�20ml/1ml��' => '���ݥ�ߥ�����󥸡�20ml/1ml��',
'���륫����' => '���륫����',
						     )
				       ),

 array('Column' => 'memo3',
					'Label' => '�Ű�����¾',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('SOLct'),
'cols' => 80)

),



array('Column' => 'bui1',
					'Label' => '����1',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),

array('Column' => 'syoken1',
					'Label' => '����',
				   
				       'Draw' => 'text'
				       

				       ),



array('Column' => 'memo12',
					'Label' => '�����',
				    'Draw' => 'text'),
array('Column' => 'bui2',
					'Label' => '����2',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),

array('Column' => 'syoken2',
					'Label' => '����',
				   
				       'Draw' => 'text'
				       

				       ),



array('Column' => 'memo22',
					'Label' => '���2',
				    'Draw' => 'text'),


array('Column' => 'bui3',
					'Label' => '����3',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken3',
					'Label' => '����',
				   
				       'Draw' => 'text'
				       

				       ),




array('Column' => 'memo32',
					'Label' => '���3',
				    'Draw' => 'text'),
array('Column' => 'bui4',
					'Label' => '����4',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),


array('Column' => 'syoken4',
					'Label' => '����',
				   
				       'Draw' => 'text'
				       

				       ),


array('Column' => 'memo42',
					'Label' => '���4',
				    'Draw' => 'text'),
array('Column' => 'bui5',
					'Label' => '����5',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),

array('Column' => 'syoken5',
					'Label' => '����',
				   
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


 array('Column' => 'proof',
'Label' => '�»ܡ�̤���ѡ���ǧ',

				       'Draw' => 'enum',
				       'Enum' => array('̤�»�' => '̤�»�',
						       
						       '���ռ»�' => '���ռ»�',
						       '��վ�ǧ' => '��վ�ǧ'
						     ),
				       'Option' => array('validate' =>
							 'nonnull'))

),


 
'LCHOICE' => mk_enum_n(50),
		   						      
 






);



class list_of_xct_ranks extends list_of_simple_objects {

var $use_printer =1;


  function list_of_xct_ranks($prefix, $cfg=NULL) {
    global $_lib_u_manage_xct_rank_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_xct_rank_cfg;
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }

 
function base_fetch_stmt_1($i) {
   
    $base = 'select 	
"ObjectID", "CreatedBy", "ID",
"orderdate" ,
 "plandate",
 "procdate" ,
  "����" ,
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

"proof" from xctorder where "Superseded" is null ';
    if ($i != '')
      $base .= " AND \"plandate\" >=  date'today' -  $i+1 ";
    return $base;
  }

 

function row_paging_orders() {
		$paging_keys = $this->row_paging_keys();
		$paging_orders = array();
		foreach ($paging_keys as $col) {
			if ($col == 'orderdate' ||
			    $col == 'ObjectID')
				$paging_orders[] = 1;
			else
				$paging_orders[] = 0;
		}
		return $paging_orders;
	}


}

class xct_rank_display extends simple_object_display {

  function xct_rank_display($prefix, $cfg=NULL) {
    global $_lib_u_manage_xct_rank_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_xct_rank_cfg;
    simple_object_display::simple_object_display($prefix, $cfg);
  }

function print_sod() {
    go_pdf($this->id, 0);
  }


/*
	function print_sod() {
    		$this->sod->print_sod();
  }
*/


}

class xct_rank_edit extends simple_object_edit {
  function xct_rank_edit($prefix, $cfg=NULL) {
    global $_lib_u_manage_xct_rank_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_xct_rank_cfg;
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }


function commit($force=NULL) {
  

   

    $db = mx_db_connect();
    $date = $this->data['procdate'];
 $patient_objectid = $this->data['����'];
 
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
		
		
	} 
}
?>
