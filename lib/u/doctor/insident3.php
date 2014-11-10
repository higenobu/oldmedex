<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';

//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pdf11.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/enum.php';

function _lib_u_insident3_get_busyo() {
 

    $id_col = 'ObjectID';

  $db = mx_db_connect();
  $stmt = <<<SQL
    select E."ID" as id , "��ʬ��1" as name
    from "�������ɽ" E 
    where  E."Superseded" IS NULL
    order by E."ID"
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array(NULL => '');
  foreach($rows as $row)
    $ret[$row['name']] = $row['name'];
  return $ret;
}
function _lib_u_insident3_get_shokusyu() {
 

    $id_col = 'ObjectID';

  $db = mx_db_connect();
  $stmt = <<<SQL
    select E."ID" as id , "����" as name
    from  "�������ɽ" E 
    where  E."Superseded" IS NULL  
    order by E."ID"
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array(NULL => ' ');
  foreach($rows as $row)
    $ret[$row['name']] = $row['name'];
  return $ret;
}


function __lib_u_insident3_cfg(&$cfg)
{
$cfg = array_merge(

array(
TABLE => 'insident',
'ALLOW_SORT' => 1,
 'DEFAULT_SORT' => 'factdate',
 COLS => array(

 "reportdate" ,

 "factdate",
 "factloc",
"facttype",
"busho",
"empnm1",
"empnm2",
"empnm3",

  "����" ,

"pnm1",
"pnm2",
"pnm3",
 
  "factcont" ,
  "factdone",
  "factplan" ,
  "factdo",
"kubun",
"pid",
"dob",
"sex",
"kana",
"proof"


 ),

'ENABLE_QBE' => array(
		      
		        
			 
			array('Column' => 'factloc', 'Label' => '���','Draw' => 'enum',
				   'Enum' => _lib_u_insident3_get_busyo(),  ),
array('Column' => 'pid',
			 'Label' => '����ID',),

 



array('Column' => 'pnm1',
'Label' => '����', ),
array('Column' => 'kana',
'Label' => '��������', ),
array('Column' => 'dob',
'Label' => '��ǯ����', ),
array('Column' => 'sex',
'Label' => '����', ),

array('Column' => 'facttype',
'Label' => '����',

				       'Draw' => 'enum',
				       'Enum' => array('' => '',

						       '��ũ' => '��ũ',

'���' => '���',
'Ϳ��' => 'Ϳ��',

						       '͢��' => '͢��',
 'ž�ݡ�ž��' => 'ž�ݡ�ž��',
 '�롼�ȡ����塼����' => '�롼�ȡ����塼����',
 'Υ�Υ��' => 'Υ�Υ��',
 '�˻ɡ�����' => '�˻ɡ�����',
 '�������' => '�������',
 '˽�Ϲ԰�' => '˽�Ϲ԰�',
'��©' => '��©',
'����¾' => '����¾'

						     ),
				       ),
 


array('Column' => 'factdo',
'Label' => '������',

				       'Draw' => 'enum',
				       'Enum' => array('' => '',
'A' => 'A',
						       'B' => 'B',
'C' => 'C',

						     ),
				       ),
array('Column' => 'sex',
'Label' => '����',

				       'Draw' => 'enum',
				       'Enum' => array('' => '',
'M' => '��',
						       'F' => '��',


						     ),
				       ),


),


LCOLS => array(

array('Column' => 'ObjectID',
'Label'=>'Ϣ��'),
 
array('Column' => 'kubun',
'Label'=>'��ʬ'),
array('Column' => 'reportdate',
'Label' => '�����'),			      


array('Column' => 'factdate',
'Label' => 'ȯ����'),
array('Column' => 'factloc',
'Label' => '���'),
array('Column' => 'facttype',
'Label' => '����'),
 
array('Column' => 'empnm1',
'Label' => '����'),
 
array('Column' => 'pid',
			 'Label' => '����ID',
),
array('Column' => 'pnm1',
'Label' => '����', ),
array('Column' => 'kana',
'Label' => '��������', ),
array('Column' => 'dob',
'Label' => '��ǯ����', ),
array('Column' => 'sex',
'Label' => '����',

				       'Draw' => 'enum',
				       'Enum' => array('M' => '��',
						       'F' => '��',


						     ),
				       ),



array('Column' => 'factdo',
'Label' => '������'),
array('Column' => 'proof',
'Label' => '��ǧ')
 
 

),

DCOLS => array(
array('Column' => 'ObjectID',
'Label'=>'Ϣ��'),
 
array('Column' => 'kubun',
'Label'=>'��ʬ'),
array('Column' => 'reportdate',
'Label' => '�����'),			      
array('Column' => 'factdate',
'Label' => 'ȯ����'),
array('Column' => 'factloc',
'Label' => '���'),
array('Column' => 'facttype',
'Label' => '����'),
array('Column' => 'busho',
'Label' => '����'),

array('Column' => 'empnm1',
'Label' => '����'),
			 
 
array('Column' => 'pid',
	'Label' => '����ID'),
array('Column' => 'pnm1',
'Label' => '����', ),
array('Column' => 'kana',
'Label' => '��������', ),
array('Column' => 'dob',
'Label' => '��ǯ����', ), 
 
array('Column' => 'sex',
'Label' => '����',

				       'Draw' => 'enum',
				       'Enum' => array('M' => '��',
						       'F' => '��',


						     ),
				       ),
array('Column' => 'empnm2',
'Label' => '����̾'),	 			
array('Column' => 'factcont',
'Label' => '����','Draw' => 'textarea'),

array('Column' => 'factdone',
'Label' => '�б�','Draw' => 'textarea'),
 
array('Column' => 'factplan',
'Label' => '�к�','Draw' => 'textarea'),

array('Column' => 'pnm3',
'Label'=> '���Ű����Ѱ���','Draw' => 'textarea'),

				   
array('Column' => 'factdo','Label' => '������')




), 

ECOLS => array(
 
array('Column' => 'kubun',
'Label'=>'��ʬ',
 'Draw' => 'enum',
'Enum' => array('���󥷥ǥ��' => '���󥷥ǥ��',

'����' => '����')),
array('Column' => 'reportdate','Label' => '�����',
'Draw' => 'date',
'Option' => array('validate' => 'date,nonnull', 'list' => 1)
),
array('Column' => 'busho',
'Label' => '����',

				       'Draw' => 'enum',
				       'Enum' => _lib_u_insident3_get_busyo(),
			 'Option' => array('validate' => 'nonnull')),
 
array('Column' => 'empnm1',
			 'Label' => '����',
			 'Draw' => 'text',
			 
			 ),
 

 
array('Column' => 'empnm3',
'Label' => '����',

				       'Draw' => 'enum',
				       'Enum' => _lib_u_insident3_get_shokusyu(),
			 'Option' => array('validate' => 'nonnull')),
 

/*
array('Column' => 'pid',
			 'Label' => '����ID(5��)',
			 'Draw' => 'text',),
*/

 
array('Column' => 'pid',
'Draw' => 'text',
//				    'Singleton' => 1,
//				    'CompareMethod' => 'zeropad_exact',
//				    'ZeroPad' => $_mx_patient_id_zeropad,
				    'Option' => array('validate' => 'digits'),),
			 
array('Column' => 'empnm2',
'Label' => '����̾',
'Draw' => 'text'),			
array('Column' => 'facttype',
'Label' => '����',

				       'Draw' => 'enum',
				       'Enum' => array('��ũ' => '��ũ',

						       '��ũ' => '��ũ',

'���' => '���',
'Ϳ��' => 'Ϳ��',

						       '͢��' => '͢��',
 'ž�ݡ�ž��' => 'ž�ݡ�ž��',
 '�롼�ȡ����塼����' => '�롼�ȡ����塼����',
 'Υ�Υ��' => 'Υ�Υ��',
 '�˻ɡ�����' => '�˻ɡ�����',
 '�������' => '�������',
 '˽�Ϲ԰�' => '˽�Ϲ԰�',
'��©' => '��©',
'����¾' => '����¾'

						     ),
				       'Option' => array('validate' =>
							 'nonnull')),


array('Column' => 'factdate',
'Label' => 'ȯ����',
				       'Draw' => 'date',
				       'Option' => array('validate' => 'date,nonnull', 'list' => 1)
),

array('Column' => 'factloc',
'Label' => '���',

				       'Draw' => 'enum',
				       'Draw' => 'enum',
				       'Enum' => _lib_u_insident3_get_busyo(),
			 'Option' => array('validate' => 'nonnull')),




 array('Column' => 'factcont',
					'Label' => '����',
				    'Draw' => 'textarea',



),
array('Column' => 'factdone',
					'Label' => '�б�',
				    'Draw' => 'textarea',



),
array('Column' => 'factplan',
					'Label'=> '�к�',
				    'Draw' => 'textarea',



),
array('Column' => 'pnm3',
					'Label'=> '���Ű����Ѱ���',
				    'Draw' => 'textarea',

),

array('Column' => 'factdo',
'Label' => '������',

				       'Draw' => 'enum',
				       'Enum' => array('A' => 'A',
						       'B' => 'B',
'C' => 'C',

						     ),
				       'Option' => array('validate' =>
							 'nonnull')),

 array('Column' => 'proof',
'Label' => '��ǧ',

				       'Draw' => 'enum',
				       'Enum' => array('̤��ǧ' => '̤��ǧ',
						       
						       '��ǧ' => '��ǧ'
						     ),
				       'Option' => array('validate' =>
							 'nonnull'))

)

 

), $cfg);
	return $cfg;
}

class list_of_insident3 extends list_of_simple_objects {
	function list_of_insident3($prefix, &$cfg) { 
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_insident3_cfg($cfg);
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
	}
function row_paging_orders() {
		$paging_keys = $this->row_paging_keys();
		$paging_orders = array();
		foreach ($paging_keys as $col) {
			if ($col == 'factdate' ||$col == 'ObjectID')
				$paging_orders[] = 1;
			else
				$paging_orders[] = 0;
		}
		return $paging_orders;
	}
}

class insident3_display extends simple_object_display {

var $use_printer =1;
	function insident3_display($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_insident3_cfg($cfg);
		simple_object_display::simple_object_display($prefix, $cfg);
	}
/*
function print_sod() {
    go_pdf($this->id, 0);
  }
*/


function print_sod($template='srl') {
    $db = mx_db_connect();

  $oid = $this->id;



    $stmt = 'SELECT "ID" from "insident" WHERE "ObjectID"=' . $oid;
    $rs = mx_db_fetch_single($db, $stmt);


    if(is_null($rs))
      return;

    $status = 0;
   
    
    $s =  <<< HTML
    <SCRIPT LANGUAGE="JavaScript">
       window.open("printinsident.php?test_app_type=${test_app_type}&status=${status}&oid={$oid}", "","width=640,height=640");
    </SCRIPT>
HTML;
    print $s;
  }

}

class insident3_edit extends simple_object_edit {
	function insident3_edit($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_insident3_cfg($cfg);
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}

 function anew_tweak($orig_id) {
		$this->data['reportdate'] = mx_today_string();
		
		
	} 



	function commit($force=NULL) {
		$this->data['����'] = $this->so_config['Patient_ObjectID'];


		return simple_object_edit::commit($force);
	}
}




?>

