<?php // -*- mode: php; coding: euc-japan -*-

//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pdf11.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/enum.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';
function _lib_u_insident2_get_busyo() {
 

    $id_col = 'ObjectID';

  $db = mx_db_connect();
  $stmt = <<<SQL
    select E."ID" as id , "��ʬ��1" as name
    from "�������ɽ" E 
    where  E."Superseded" IS NULL
    order by E."ID"
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array(NULL => ' ');
  foreach($rows as $row)
    $ret[$row['name']] = $row['name'];
  return $ret;
}
function _lib_u_insident2_get_shokusyu() {
 

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


function __lib_u_insident2_cfg(&$cfg)
{
$cfg = array_merge(

array(
TABLE => 'insident',
'ALLOW_SORT' => 1,
 'DEFAULT_SORT' => 'reportdate',
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
"proof"
 ),

LCOLS => array(
array('Column' => 'reportdate',
'Label' => '�����'),			      
array('Column' => 'factdate',
'Label' => 'ȯ����'),
array('Column' => 'factloc',
'Label' => '���'),
array('Column' => 'facttype',
'Label' => '����'),
//array('Column' => 'busho',
//'Label' => '����'),
array('Column' => 'empnm1',
'Label' => '����'),
array('Column' => 'pnm1',
'Label' => '����̾'),
/*array('Column' => 'pnm1',
			 'Label' => '����',
			 'Draw' => 'enum',
			 'Enum' => _lib_u_insident2_get_emp(),
			 'Option' => array('validate' => 'nonnull'),
			 ),
*/

/*array('Column' => 'pnm1',
'Label' => '����̾'),*/

//array('Column' => 'empnm2',
//'Label' => '����̾'),
/*
array('Column' => 'factcont',
'Label' => '����'),
array('Column' => 'factdone',
'Label' => '�б�'),
array('Column' => 'factplan',
'Label' => '�к�'),
array('Column' => 'pnm3',
					'Label'=> '���Ű����Ѱ��񤫤�Υ�������'),
*/

array('Column' => 'factdo',
'Label' => '������'),
array('Column' => 'proof',
'Label' => '�»ܡ�̤����')),




DCOLS => array(
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
array('Column' => 'pnm1',
'Label' => '����̾'),
array('Column' => 'empnm2',
'Label' => '����̾'),			 


/*
array('Column' => 'pnm1',
			 'Label' => '����̾',

),
			 
*/
			
array('Column' => 'factcont',
'Label' => '����'),
array('Column' => 'factdone',
'Label' => '�б�'),
array('Column' => 'factplan',
'Label' => '�к�'),
array('Column' => 'pnm3',
'Label'=> '���Ű����Ѱ��񤫤�Υ�������'),
				   
array('Column' => 'factdo',

'Label' => '������'),
array('Column' => 'proof',
'Label' => '�»ܡ�̤����')),


ECOLS => array(
array('Column' => 'reportdate','Label' => '�����',
'Draw' => 'date',
'Option' => array('validate' => 'date,nonnull', 'list' => 1)
),
array('Column' => 'busho',
'Label' => '����',

				       'Draw' => 'enum',
				       'Enum' => _lib_u_insident2_get_busyo(),
			 'Option' => array('validate' => 'nonnull')),

array('Column' => 'empnm1',
			 'Label' => '����',
			 'Draw' => 'text',
			 
			 ),
array('Column' => 'empnm3',
'Label' => '����',

				       'Draw' => 'enum',
				       'Enum' => _lib_u_insident2_get_shokusyu(),
			 'Option' => array('validate' => 'nonnull')),
/*
array('Column' => 'pnm1',
			 'Label' => '����',
			 'Draw' => 'text',
			 

),

*/

array('Column' => 'empnm2',
'Label' => '����̾',
'Draw' => 'text'),
			
array('Column' => 'facttype',
'Label' => 'Type',

				       'Draw' => 'enum',
				       'Enum' => array('��ũ' => '��ũ',

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
				       'Enum' => _lib_u_insident2_get_busyo(),
			 'Option' => array('validate' => 'nonnull')),




 array('Column' => 'factcont',
					'Label' => '����',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('insident1'),
'cols' => 80)

),
array('Column' => 'factdone',
					'Label' => '�б�',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('insident2'),
'cols' => 80)

),
array('Column' => 'factplan',
					'Label'=> '�к�',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('insident3'),
'cols' => 80)

),
array('Column' => 'pnm3',
					'Label'=> '���Ű����Ѱ��񤫤�Υ�������',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('insident3'),
'cols' => 80)

),

array('Column' => 'factdo',
'Label' => '������',

				       'Draw' => 'enum',
				       'Enum' => array('A' => 'A',
						       
						       'B' => 'B'
						     ),
				       'Option' => array('validate' =>
							 'nonnull')),

 array('Column' => 'proof',
'Label' => '�»ܡ�̤���ѡ���ǧ',

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

class list_of_insident2 extends list_of_ppa_objects {
	function list_of_insident2($prefix, &$cfg) { 
 
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_insident2_cfg($cfg);
		
		list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
	}
}

class insident2_display extends simple_object_display {

var $use_printer =1;
	function insident2_display($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_insident2_cfg($cfg);
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

class insident2_edit extends simple_object_edit {
	function insident2_edit($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_insident2_cfg($cfg);
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}

 function anew_tweak($orig_id) {
		$this->data['reportdate'] = mx_today_string();
		
		
	} 


	/* could inherit from simple_object_ppa_edit */
	function commit($force=NULL) {
		$this->data['����'] = $this->so_config['Patient_ObjectID'];
	/*	$this->data['reportdate'] = mx_now_string(); */

		return simple_object_edit::commit($force);
	}
}
?>

