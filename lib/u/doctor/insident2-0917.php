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
function _lib_u_insident2_get_busyo() {
 

    $id_col = 'ObjectID';

  $db = mx_db_connect();
  $stmt = <<<SQL
    select E."ID" as id , "中分類1" as name
    from "部署一覧表" E 
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
    select E."ID" as id , "職種" as name
    from  "職種一覧表" E 
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
  "患者" ,
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
'Label' => '報告日'),			      
array('Column' => 'factdate',
'Label' => '発生日'),
array('Column' => 'factloc',
'Label' => '場所'),
array('Column' => 'facttype',
'Label' => '種類'),
array('Column' => 'busho',
'Label' => '部署'),
array('Column' => 'empnm1',
'Label' => '職員'),
/*array('Column' => 'pnm1',
			 'Label' => '職員',
			 'Draw' => 'enum',
			 'Enum' => _lib_u_insident2_get_emp(),
			 'Option' => array('validate' => 'nonnull'),
			 ),
*/

/*array('Column' => 'pnm1',
'Label' => '患者名'),*/

array('Column' => 'pnm1',
			 'Label' => '患者',

			 
			 ),

array('Column' => 'factcont',
'Label' => '状況'),
array('Column' => 'factdone',
'Label' => '対応'),
array('Column' => 'factplan',
'Label' => '対策'),
array('Column' => 'pnm3',
					'Label'=> '医療安全委員会からのコメント欄'),
array('Column' => 'factdo',
'Label' => '重要度'),
array('Column' => 'proof',
'Label' => '実施・未・済')),




DCOLS => array(
array('Column' => 'reportdate',
'Label' => '報告日'),			      
array('Column' => 'factdate',
'Label' => '発生日'),
array('Column' => 'factloc',
'Label' => '場所'),
array('Column' => 'facttype',
'Label' => '種類'),
array('Column' => 'busho',
'Label' => '部署'),

array('Column' => 'empnm1',
			 'Label' => '職員'),
			 

/*
array('Column' => '患者',
'Label' => '患者'),*/

array('Column' => 'pnm1',
			 'Label' => '患者',

),
			 
			
array('Column' => 'factcont',
'Label' => '状況'),
array('Column' => 'factdone',
'Label' => '対応'),
array('Column' => 'factplan',
'Label' => '対策'),
array('Column' => 'pnm3',
					'Label'=> '医療安全委員会からのコメント欄'),
				   
array('Column' => 'factdo',

'Label' => '重要度'),
array('Column' => 'proof',
'Label' => '実施・未・済')),


ECOLS => array(
array('Column' => 'reportdate','Label' => '報告日',
'Draw' => 'date',
'Option' => array('validate' => 'date,nonnull', 'list' => 1)
),
array('Column' => 'busho',
'Label' => '部署',

				       'Draw' => 'enum',
				       'Enum' => _lib_u_insident2_get_busyo(),
			 'Option' => array('validate' => 'nonnull')),

array('Column' => 'empnm1',
			 'Label' => '報告者',
			 'Draw' => 'text',
			 
			 ),
array('Column' => 'empnm3',
'Label' => '職種',

				       'Draw' => 'enum',
				       'Enum' => _lib_u_insident2_get_shokusyu(),
			 'Option' => array('validate' => 'nonnull')),

array('Column' => 'pnm1',
			 'Label' => '患者',
			 'Draw' => 'text',
			 

),



			
array('Column' => 'facttype',
'Label' => 'Type',

				       'Draw' => 'enum',
				       'Enum' => array('点滴' => '点滴',

						       '点滴' => '点滴',

'注射' => '注射',
'与薬' => '与薬',

						       '輸血' => '輸血',
 '転倒・転落' => '転倒・転落',
 'ルート・チューブ類' => 'ルート・チューブ類',
 '離棟・離院' => '離棟・離院',
 '針刺・針置' => '針刺・針置',
 '自殺企図' => '自殺企図',
 '暴力行為' => '暴力行為',
'窒息' => '窒息',
'その他' => 'その他'

						     ),
				       'Option' => array('validate' =>
							 'nonnull')),


array('Column' => 'factdate',
'Label' => '発生日',
				       'Draw' => 'date',
				       'Option' => array('validate' => 'date,nonnull', 'list' => 1)
),

array('Column' => 'factloc',
'Label' => '場所',

				       'Draw' => 'enum',
				       'Draw' => 'enum',
				       'Enum' => _lib_u_insident2_get_busyo(),
			 'Option' => array('validate' => 'nonnull')),




 array('Column' => 'factcont',
					'Label' => '状況',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('insident1'),
'cols' => 80)

),
array('Column' => 'factdone',
					'Label' => '対応',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('insident2'),
'cols' => 80)

),
array('Column' => 'factplan',
					'Label'=> '対策',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('insident3'),
'cols' => 80)

),
array('Column' => 'pnm3',
					'Label'=> '医療安全委員会からのコメント欄',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('insident3'),
'cols' => 80)

),

array('Column' => 'factdo',
'Label' => '重要度',

				       'Draw' => 'enum',
				       'Enum' => array('A' => 'A',
						       
						       'B' => 'B'
						     ),
				       'Option' => array('validate' =>
							 'nonnull')),

 array('Column' => 'proof',
'Label' => '実施・未・済・承認',

				       'Draw' => 'enum',
				       'Enum' => array('未承認' => '未承認',
						       
						       '承認' => '承認'
						     ),
				       'Option' => array('validate' =>
							 'nonnull'))

)
), $cfg);
	return $cfg;
}

class list_of_insident2 extends list_of_simple_objects {
	function list_of_insident2($prefix, &$cfg) { function dx_employee($desc, $value, $row) {
    $name = get_emp_name($value);
    $this->_dx_textish($name['lname'] . $name['fname']);
  }
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_insident2_cfg($cfg);
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
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
		$this->data['患者'] = $this->so_config['Patient_ObjectID'];
	/*	$this->data['reportdate'] = mx_now_string(); */

		return simple_object_edit::commit($force);
	}
}
?>

