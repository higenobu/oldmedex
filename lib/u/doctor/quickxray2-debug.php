<?php // -*- mode: php; coding: euc-japan -*-

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';



function __lib_u_doctor_quickxray2_schema()
{
/*
	global $__lib_u_doctor_quickxray2_schemainfo;
$__lib_u_doctor_quickxray2_schemainfo = array('XCOLS' => array(

	array('Column' => 'orderdate','Label'=>'DOS'),
	 			      
	   array( 'Column'=>'s0','Label'=>'s0'),),
	       
	 );
*/


	if (!$__lib_u_doctor_quickxray2_schemainfo) {
		$db = mx_db_connect();
		$stmt = <<<SQL
		SELECT name, quick_dirs, left_right
		FROM "ctmaster"
		WHERE "Superseded" IS NULL
SQL;
		$them = pg_fetch_all(pg_query($db, $stmt));
		$cols = array();
		$revinfo = array();
		foreach ($them as $item) {
			$name = $item['name'];
			$quick_dirs = $item['quick_dirs'];
			$left_right = $item['left_right'];
			$len = strlen($quick_dirs);
			$vars = array();
			for ($i = 0; $i < $len; $i++) {
				$c = $quick_dirs[$i];
				if (ord('A') <= ord($c))
					$c = ord($c) - ord('A') + 10;
				else
					$c = ord($c) - ord('0');
				$vars[] = $c;
			}
			if ($left_right == 'Y') {
				$vars[] = "L";
				$vars[] = "R";
				$vars[] = "B";
			} else if ($left_right == 'D') {
				$vars[] = "L";
				$vars[] = "R";
				$vars[] = "B";
				for ($i = 1; $i <= 5; $i++)
					$vars[] = "D$i";
			}
			foreach ($vars as $v) {
				$info = array('Column' => "$name.$v",
					      'Draw' => 'check');
				$cols[] = $info;
				if (!array_key_exists($name, $revinfo))
					$revinfo[$name] = array();
				$revinfo["$name"][$v] = $info;
			}
			$info = array('Column' => "$name.T",
				      'Draw' => 'text');
			$cols[] = $info;
			if (!array_key_exists($name, $revinfo))
				$revinfo[$name] = array();
			$revinfo[$name]['T'] = $info;
		}
		$__lib_u_doctor_quickxray2_schemainfo =
			array('XCOLS' => $cols, 'REVINDEX' => $revinfo);
	}

	return $__lib_u_doctor_quickxray2_schemainfo;
}
//
/*
function __lib_u_k2_cfg(&$cfg)
{
 

$cfg = array_merge(

	array(
	'TABLE' => 'karte',
	'ALLOW_SORT' =>1,

	 'COLS' => array(
	
 		"order_date" ,
  
  		"patient" ,
		"s0",
	"s1","s2","s3","s4"
	      ),

  	

	

	'LCOLS' => array(

	array('Column' => 'orderdate','Label'=>'DOS'),
	 			      
	 array( 'Column'=>'s0','Label'=>'s0'),
	       
	 
	 ),
	



//*******************************************************************8

'DCOLS' => array(

	array('Column' => 'orderdate','Label'=>'DOS'),
	 			      
	   array( 'Column'=>'s0','Label'=>'s0'),
	       
	 ),
	

//***************************************************************
'ECOLS' => array(

	array('Column' => 'orderdate','Label'=>'DOS'),
	 			      
	  array( 'Column'=>'s0','Label'=>'s0'),





),
	       
	 
	 ),

	
), $cfg);
	return $cfg;
}

*/


//
function __lib_u_doctor_quickxray2_cfg(&$cfg)
{
	$schema = __lib_u_doctor_quickxray2_schema();
	$dynamic_cols = $schema['XCOLS'];
	$static_cols = array(
		array('Column' => 'orderdate', 'Draw' => 'date',
		      ),
		array('Column' => 's0',
					'Label' => 'S0',
				   
				       'Draw' => 'text',
				        ),
		array('Column' => 's1', 'Draw' => 'text'),
		array('Column' => 's2', 'Draw' => 'text'),
		array('Column' => 'patient'),
		
				       					    
			  

		);


	$lcols = $static_cols;
	

	$local_cfg = array('TABLE' => 'karte2',
			   'LCOLS' => $lcols);

	$__c = $static_cols;
	foreach ($dynamic_cols as $elem) {
		$__c[] = $elem;
	}
	$local_cfg['DCOLS'] = $__c;


	$local_cfg['ECOLS'] = $__c;

	$__c = array();

	foreach ($static_cols as $elem) {
		$__c[] = $elem['Column'];
	}
//	$__c[] = 'patient';
	$local_cfg['COLS'] = $__c;

	$cfg = array_merge($local_cfg, $cfg);
}

function __lib_u_doctor_quickxray2_fetch_sub(&$db, $id)
{

	$idq = mx_db_sql_quote($id);
	$stmt = <<<SQL
SELECT
 O.patient,
O.orderdate  ,

O."s0"  ,
O."s1"  ,
 O."s2"  ,
O."s3"  ,
 
FROM "karte2" AS O
 
WHERE O."ObjectID" = $idq
SQL;

	$val = array();
	$sth = pg_query($db, $stmt);
	if (!$sth) {
		print "<!-- $stmt -->";
		return $val;
	}
	$data = pg_fetch_all($sth);
	if (!$data || !count($data)) {
		return $val;
	}
	
	$schema = __lib_u_doctor_quickxray2_schema();
	$revinfo = $schema['REVINDEX'];
	foreach ($data as $e) {
		
	}


	return $val;
}

function __lib_u_doctor_quickxray2_apply_template(&$data, $it, $read_only=NULL)
{
	$schema = __lib_u_doctor_quickxray2_schema();
	$revinfo = $schema['REVINDEX'];
	$s = file_get_contents($_SERVER['DOCUMENT_ROOT'] .
			       '/templates/quickxray2.html');
	$result = array();
//10-20-2014
	$pid=$data["patient"];
print_r($data);
	$db = mx_db_connect();
      $stmt = '
SELECT id, pt_no
FROM "tbl_patient2"  
WHERE id = ' . mx_db_sql_quote($pid);
print $stmt;

      $pt = mx_db_fetch_single($db, $stmt);
	$pno=$pt["pt_no"];
	$s = str_replace("pid",$pno,$s);
//10-20-2014
	while ($s != '') {
		if ($read_only) {
			$s = str_replace("<input type",
					 "<input disabled=disabled type",
					 $s);
			$s = str_replace("<textarea ",
					 "<textarea disabled=disabled ",
					 $s);
		}
		$m = array();
		if (!preg_match('/^(.*?)@@([^@]+?)@@(.*)$/s', $s, &$m))
			break;
		$result[] = $m[1];
		if ($m[2]) {
			$key = $m[2];
			if ($key[0] == 'N') {
				$key = substr($key, 1);
				$en = $it->prefix . mx_form_encode_name($key);
				$result[] = $en;
			} else {
				$v = $data[$key];
				list($name, $suffix) = explode('.', $key);
				if (array_key_exists($name, $revinfo) &&
				    array_key_exists($suffix, $revinfo[$name])) {
					if ($revinfo[$name][$suffix]['Draw'] == 'check') {
						if ($v == 'N' || !$v)
							$v = '';
						else
							$v = 'checked';
					}
				}
				$result[] = $v;
			}
		}
		$s = $m[3];
	}
	$result[] = $s;
	return implode('', $result);
}



function __lib_u_doctor_quickxray2_apply_template1(&$data, $it, $read_only=NULL)
{
	$schema = __lib_u_doctor_quickxray2_schema();
	$revinfo = $schema['REVINDEX'];
	$s = file_get_contents($_SERVER['DOCUMENT_ROOT'] .
			       '/templates/quickxray3.html');
	$result = array();
//10-20-2014
	$pid=$data["patient"];
print_r($data);
	$db = mx_db_connect();
      $stmt = '
SELECT id, pt_no
FROM "tbl_patient2"  
WHERE id = ' . mx_db_sql_quote($pid);
print $stmt;

      $pt = mx_db_fetch_single($db, $stmt);
	$pno=$pt["pt_no"];
	$s = str_replace("pid",$pno,$s);
//10-20-2014
	while ($s != '') {
		if ($read_only) {
			$s = str_replace("<input type",
					 "<input disabled=disabled type",
					 $s);
			$s = str_replace("<textarea ",
					 "<textarea disabled=disabled ",
					 $s);
		}
		$m = array();
		if (!preg_match('/^(.*?)@@([^@]+?)@@(.*)$/s', $s, &$m))
			break;
		$result[] = $m[1];
		if ($m[2]) {
			$key = $m[2];
			if ($key[0] == 'N') {
				$key = substr($key, 1);
				$en = $it->prefix . mx_form_encode_name($key);
				$result[] = $en;
			} else {
				$v = $data[$key];
				list($name, $suffix) = explode('.', $key);
				if (array_key_exists($name, $revinfo) &&
				    array_key_exists($suffix, $revinfo[$name])) {
					if ($revinfo[$name][$suffix]['Draw'] == 'check') {
						if ($v == 'N' || !$v)
							$v = '';
						else
							$v = 'checked';
					}
				}
				$result[] = $v;
			}
		}
		$s = $m[3];
	}
	$result[] = $s;
	return implode('', $result);
}

function __lib_u_doctor_quickxray2_summarize($dcols, $d)
{
	$items = array();
	foreach ($dcols as $desc) {
		$col = $desc['Column'];
		$data = trim($d[$col]);
		list($name, $suffix) = explode('.', $col);
		if ($suffix == 'T') {
			if ($data == '')
				continue;
			$suffix = $data;
		} else {
			if ($desc['Draw'] != 'check')
				continue;
			if ($data != 'Y')
				continue;
		}
		if (!array_key_exists($name, $items))
			$items[$name] = array();
		if ($suffix == 'R')
			$suffix = '右';
		else if ($suffix == 'L')
			$suffix = '左';
		else if ($suffix == 'B')
			$suffix = '両';
		$items[$name][] = $suffix;
	}

	$retval = array();
	foreach ($items as $name => $vals)
		$retval[] = sprintf("%s (%s)", $name, implode(',', $vals));
	return $retval;
}

class list_of_quickxray2s extends list_of_ppa_objects {
	function list_of_quickxray2s($prefix, $config=NULL) {
		__lib_u_doctor_quickxray2_cfg(&$config);
//		__lib_u_k2_cfg(&$config);
		list_of_ppa_objects::list_of_ppa_objects($prefix, $config);
	}


//

function annotate_fetched_data() {
		$annotated = array();
		$db = mx_db_connect();
		$dc = $this->so_config['DCOLS'];
		foreach ($this->fetched_data as $d) {
			$oid = $d['ObjectID'];
			$v = __lib_u_doctor_quickxray2_fetch_sub(&$db, $oid);
			$items = __lib_u_doctor_quickxray2_summarize($dc, $v);
			$d['s0'] = implode(', ', $items);
			$annotated[] = $d;
		}
		print_r($annotated);
		$this->fetched_data = $annotated;
	}
//

	


}

class quickxray2_display extends simple_object_display {
	function quickxray2_display($prefix, $config=NULL) {
		__lib_u_doctor_quickxray2_cfg(&$config);
		simple_object_display::simple_object_display($prefix, $config);
	}

	function annotate_row_data(&$data) {
		$db = mx_db_connect();
		$val = __lib_u_doctor_quickxray2_fetch_sub
			(&$db, $data['ObjectID']);
		$data = array_merge($data, $val);
	}

	function draw_body($data, $hdata) {
		print __lib_u_doctor_quickxray2_apply_template1(&$data, $this, 1);
	}
/*
	function module_info($p_pid) {
		$d = $this->prepare_data_for_draw();

		$candidates = array("u/doctor/quickxray2.php",
				    "u/everybody/quickxray2.php");
		$avail = mx_filter_accessible_application($candidates);
		if (count($avail) == 0)
			$url = NULL;
		else {
			$application = $avail[0]['path'];
			$url = sprintf("/$application?SetPatient=1&".
				       "PatientID=%s&SetSODObject=%s",
				       $p_pid, $d['ObjectID']);
		}

		$text = 'CT検査';
		$fuller = '';
                $yotei ='';
		$dcols = $this->so_config['DCOLS'];
		$items = __lib_u_doctor_quickxray2_summarize($dcols, $d);
		foreach ($items as $item) {
			$fuller .= htmlspecialchars($item) . "<br />";
		}
		foreach (array('その他') as $col) {
			$data = trim($d[$col]);
			if ($data == '')
				continue;
			$fuller .= htmlspecialchars("$col: $data") . "<br />";
		}


		foreach (array('mokuteki') as $col) {
			$data = trim($d[$col]);
			if ($data == '')
				continue;
			$fuller .= htmlspecialchars("$col: $data") . "<br />";
		}


		return array('timestamp' => $d['orderate'],
			     'text' => $text,
			     'fuller' => $fuller,

			     'callback_url' => $url,
			     'thumb' => NULL,
			     'object_id' => $d['ObjectID'],
			     );
	}
*/


//  03-25-2011 add

function print_sod($template='srl') {
	
 }



}

class quickxray2_edit extends simple_object_ppa_edit {
	function quickxray2_edit($prefix, $config=NULL) {
		__lib_u_doctor_quickxray2_cfg(&$config);
		simple_object_edit::simple_object_edit($prefix, $config);
	}

	function anew_tweak($orig_id) {
		simple_object_ppa_edit::anew_tweak($orig_id);
		$this->data['orderdate'] = mx_today_string();
	$this->data['patient'] = $this->so_config['Patient_ObjectID'];
	//$this->data['yotei'] = mx_today_string();
	}

	function annotate_form_data(&$data) {
		simple_object_ppa_edit::annotate_form_data(&$data);
	}

	function annotate_row_data(&$data) {
		$db = mx_db_connect();
		$val = __lib_u_doctor_quickxray2_fetch_sub
			(&$db, $data['ObjectID']);
		$data = array_merge($data, $val);
		simple_object_ppa_edit::annotate_row_data(&$data);
	}
	function commit($force=NULL) {
		$this->data['patient'] = $this->so_config['Patient_ObjectID'];
		$this->data['orderdate'] = mx_now_string();
		return simple_object_edit::commit($force);
	}

	function data_compare($curr, $data) {
		if (simple_object_ppa_edit::data_compare($curr, $data))
			return 1;

		$schema = __lib_u_doctor_quickxray2_schema();
		$xcols = $schema['XCOLS'];
		foreach ($xcols as $desc) {
			$col = $desc['Column'];
			$c = trim($curr[$col]);
			$d = trim($data[$col]);
			if ($desc['Draw'] == 'check') {
				if ($c == 'N') { $c = ''; }
				if ($d == 'N') { $d = ''; }
				if ($c != $d)
					return 1;
			} else if ($desc['Draw'] == 'text') {
				if ($c != $d)
					return 1;
			} else {
				print "<!-- Eh?\n";
				var_dump($desc);
				print "\n-->\n";
				continue;
			}
		}
		return 0;
	}

	function _update_subtables(&$db, $id, $stash_id) {

		
		return '';
	}

	function draw_body_1($data, $soc) {
		print __lib_u_doctor_quickxray2_apply_template(&$data, $this);
	}

	function is_empty_order() {
		$schema = __lib_u_doctor_quickxray2_schema();
		$xcols = $schema['XCOLS'];
		$data = $this->data;
		if (trim($data['s0']) != '')
			return 0;
		foreach ($xcols as $desc) {
			$col = $desc['Column'];
			$d = trim($data[$col]);
			if (($desc['Draw'] == 'check') &&
			    ($d == 'N'))
				$d = '';
			if ($d != '') {
				print "<!-- $col is not empty '$d' -->\n";
				return 0;
			}
		}
		return 1;
	}

	function _validate($force=NULL) {
/*
		$bad = 0;
		$d =& $this->data;
		$res = simple_object_ppa_edit::_validate($force);
		if ($res != 'ok')
			$bad = 1;
		if ($this->is_empty_order()) {
			$this->err("指示内容が空ではいけません\n");
			$bad++;
		}
		if ($bad)
			return '';

*/

		return 'ok';
	}

}

/*
 * This is used by index-pt via lib/ord_module.php.
 */


?>
