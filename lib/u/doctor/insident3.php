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
    select E."ID" as id , "ÃæÊ¬Îà1" as name
    from "Éô½ð°ìÍ÷É½" E 
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
    select E."ID" as id , "¿¦¼ï" as name
    from  "¿¦¼ï°ìÍ÷É½" E 
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

  "´µ¼Ô" ,

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
		      
		        
			 
			array('Column' => 'factloc', 'Label' => '¾ì½ê','Draw' => 'enum',
				   'Enum' => _lib_u_insident3_get_busyo(),  ),
array('Column' => 'pid',
			 'Label' => '´µ¼ÔID',),

 



array('Column' => 'pnm1',
'Label' => '´µ¼Ô', ),
array('Column' => 'kana',
'Label' => '¥«¥¿¥«¥Ê', ),
array('Column' => 'dob',
'Label' => 'À¸Ç¯·îÆü', ),
array('Column' => 'sex',
'Label' => 'À­ÊÌ', ),

array('Column' => 'facttype',
'Label' => '¼ïÎà',

				       'Draw' => 'enum',
				       'Enum' => array('' => '',

						       'ÅÀÅ©' => 'ÅÀÅ©',

'Ãí¼Í' => 'Ãí¼Í',
'Í¿Ìô' => 'Í¿Ìô',

						       'Í¢·ì' => 'Í¢·ì',
 'Å¾ÅÝ¡¦Å¾Íî' => 'Å¾ÅÝ¡¦Å¾Íî',
 '¥ë¡¼¥È¡¦¥Á¥å¡¼¥ÖÎà' => '¥ë¡¼¥È¡¦¥Á¥å¡¼¥ÖÎà',
 'Î¥Åï¡¦Î¥±¡' => 'Î¥Åï¡¦Î¥±¡',
 '¿Ë»É¡¦¿ËÃÖ' => '¿Ë»É¡¦¿ËÃÖ',
 '¼«»¦´ë¿Þ' => '¼«»¦´ë¿Þ',
 'Ë½ÎÏ¹Ô°Ù' => 'Ë½ÎÏ¹Ô°Ù',
'ÃâÂ©' => 'ÃâÂ©',
'¤½¤ÎÂ¾' => '¤½¤ÎÂ¾'

						     ),
				       ),
 


array('Column' => 'factdo',
'Label' => '½ÅÍ×ÅÙ',

				       'Draw' => 'enum',
				       'Enum' => array('' => '',
'A' => 'A',
						       'B' => 'B',
'C' => 'C',

						     ),
				       ),
array('Column' => 'sex',
'Label' => 'À­ÊÌ',

				       'Draw' => 'enum',
				       'Enum' => array('' => '',
'M' => 'ÃË',
						       'F' => '½÷',


						     ),
				       ),


),


LCOLS => array(

array('Column' => 'ObjectID',
'Label'=>'Ï¢ÈÖ'),
 
array('Column' => 'kubun',
'Label'=>'¶èÊ¬'),
array('Column' => 'reportdate',
'Label' => 'Êó¹ðÆü'),			      


array('Column' => 'factdate',
'Label' => 'È¯À¸Æü'),
array('Column' => 'factloc',
'Label' => '¾ì½ê'),
array('Column' => 'facttype',
'Label' => '¼ïÎà'),
 
array('Column' => 'empnm1',
'Label' => '¿¦°÷'),
 
array('Column' => 'pid',
			 'Label' => '´µ¼ÔID',
),
array('Column' => 'pnm1',
'Label' => '´µ¼Ô', ),
array('Column' => 'kana',
'Label' => '¥«¥¿¥«¥Ê', ),
array('Column' => 'dob',
'Label' => 'À¸Ç¯·îÆü', ),
array('Column' => 'sex',
'Label' => 'À­ÊÌ',

				       'Draw' => 'enum',
				       'Enum' => array('M' => 'ÃË',
						       'F' => '½÷',


						     ),
				       ),



array('Column' => 'factdo',
'Label' => '½ÅÍ×ÅÙ'),
array('Column' => 'proof',
'Label' => '¾µÇ§')
 
 

),

DCOLS => array(
array('Column' => 'ObjectID',
'Label'=>'Ï¢ÈÖ'),
 
array('Column' => 'kubun',
'Label'=>'¶èÊ¬'),
array('Column' => 'reportdate',
'Label' => 'Êó¹ðÆü'),			      
array('Column' => 'factdate',
'Label' => 'È¯À¸Æü'),
array('Column' => 'factloc',
'Label' => '¾ì½ê'),
array('Column' => 'facttype',
'Label' => '¼ïÎà'),
array('Column' => 'busho',
'Label' => 'Éô½ð'),

array('Column' => 'empnm1',
'Label' => '¿¦°÷'),
			 
 
array('Column' => 'pid',
	'Label' => '´µ¼ÔID'),
array('Column' => 'pnm1',
'Label' => '´µ¼Ô', ),
array('Column' => 'kana',
'Label' => '¥«¥¿¥«¥Ê', ),
array('Column' => 'dob',
'Label' => 'À¸Ç¯·îÆü', ), 
 
array('Column' => 'sex',
'Label' => 'À­ÊÌ',

				       'Draw' => 'enum',
				       'Enum' => array('M' => 'ÃË',
						       'F' => '½÷',


						     ),
				       ),
array('Column' => 'empnm2',
'Label' => '¼À´µÌ¾'),	 			
array('Column' => 'factcont',
'Label' => '¾õ¶·','Draw' => 'textarea'),

array('Column' => 'factdone',
'Label' => 'ÂÐ±þ','Draw' => 'textarea'),
 
array('Column' => 'factplan',
'Label' => 'ÂÐºö','Draw' => 'textarea'),

array('Column' => 'pnm3',
'Label'=> '°åÎÅ°ÂÁ´°Ñ°÷²ñ','Draw' => 'textarea'),

				   
array('Column' => 'factdo','Label' => '½ÅÍ×ÅÙ')




), 

ECOLS => array(
 
array('Column' => 'kubun',
'Label'=>'¶èÊ¬',
 'Draw' => 'enum',
'Enum' => array('¥¤¥ó¥·¥Ç¥ó¥È' => '¥¤¥ó¥·¥Ç¥ó¥È',

'»ö¸Î' => '»ö¸Î')),
array('Column' => 'reportdate','Label' => 'Êó¹ðÆü',
'Draw' => 'date',
'Option' => array('validate' => 'date,nonnull', 'list' => 1)
),
array('Column' => 'busho',
'Label' => 'Éô½ð',

				       'Draw' => 'enum',
				       'Enum' => _lib_u_insident3_get_busyo(),
			 'Option' => array('validate' => 'nonnull')),
 
array('Column' => 'empnm1',
			 'Label' => 'Êó¹ð¼Ô',
			 'Draw' => 'text',
			 
			 ),
 

 
array('Column' => 'empnm3',
'Label' => '¿¦¼ï',

				       'Draw' => 'enum',
				       'Enum' => _lib_u_insident3_get_shokusyu(),
			 'Option' => array('validate' => 'nonnull')),
 

/*
array('Column' => 'pid',
			 'Label' => '´µ¼ÔID(5·å)',
			 'Draw' => 'text',),
*/

 
array('Column' => 'pid',
'Draw' => 'text',
//				    'Singleton' => 1,
//				    'CompareMethod' => 'zeropad_exact',
//				    'ZeroPad' => $_mx_patient_id_zeropad,
				    'Option' => array('validate' => 'digits'),),
			 
array('Column' => 'empnm2',
'Label' => '¼À´µÌ¾',
'Draw' => 'text'),			
array('Column' => 'facttype',
'Label' => '¼ïÎà',

				       'Draw' => 'enum',
				       'Enum' => array('ÅÀÅ©' => 'ÅÀÅ©',

						       'ÅÀÅ©' => 'ÅÀÅ©',

'Ãí¼Í' => 'Ãí¼Í',
'Í¿Ìô' => 'Í¿Ìô',

						       'Í¢·ì' => 'Í¢·ì',
 'Å¾ÅÝ¡¦Å¾Íî' => 'Å¾ÅÝ¡¦Å¾Íî',
 '¥ë¡¼¥È¡¦¥Á¥å¡¼¥ÖÎà' => '¥ë¡¼¥È¡¦¥Á¥å¡¼¥ÖÎà',
 'Î¥Åï¡¦Î¥±¡' => 'Î¥Åï¡¦Î¥±¡',
 '¿Ë»É¡¦¿ËÃÖ' => '¿Ë»É¡¦¿ËÃÖ',
 '¼«»¦´ë¿Þ' => '¼«»¦´ë¿Þ',
 'Ë½ÎÏ¹Ô°Ù' => 'Ë½ÎÏ¹Ô°Ù',
'ÃâÂ©' => 'ÃâÂ©',
'¤½¤ÎÂ¾' => '¤½¤ÎÂ¾'

						     ),
				       'Option' => array('validate' =>
							 'nonnull')),


array('Column' => 'factdate',
'Label' => 'È¯À¸Æü',
				       'Draw' => 'date',
				       'Option' => array('validate' => 'date,nonnull', 'list' => 1)
),

array('Column' => 'factloc',
'Label' => '¾ì½ê',

				       'Draw' => 'enum',
				       'Draw' => 'enum',
				       'Enum' => _lib_u_insident3_get_busyo(),
			 'Option' => array('validate' => 'nonnull')),




 array('Column' => 'factcont',
					'Label' => '¾õ¶·',
				    'Draw' => 'textarea',



),
array('Column' => 'factdone',
					'Label' => 'ÂÐ±þ',
				    'Draw' => 'textarea',



),
array('Column' => 'factplan',
					'Label'=> 'ÂÐºö',
				    'Draw' => 'textarea',



),
array('Column' => 'pnm3',
					'Label'=> '°åÎÅ°ÂÁ´°Ñ°÷²ñ',
				    'Draw' => 'textarea',

),

array('Column' => 'factdo',
'Label' => '½ÅÍ×ÅÙ',

				       'Draw' => 'enum',
				       'Enum' => array('A' => 'A',
						       'B' => 'B',
'C' => 'C',

						     ),
				       'Option' => array('validate' =>
							 'nonnull')),

 array('Column' => 'proof',
'Label' => '¾µÇ§',

				       'Draw' => 'enum',
				       'Enum' => array('Ì¤¾µÇ§' => 'Ì¤¾µÇ§',
						       
						       '¾µÇ§' => '¾µÇ§'
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
		$this->data['´µ¼Ô'] = $this->so_config['Patient_ObjectID'];


		return simple_object_edit::commit($force);
	}
}




?>

