<?php // -*- mode: php; coding: euc-japan -*-

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';


/*
function _lib_u_x_get_shiji2() {
  
$id_col = 'id';




  $db = mx_db_connect();
  $stmt = <<<SQL
    select E."${id_col}" as id ,  "name" as name
    from modalities E 
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array(NULL => '');
  foreach($rows as $row)
    $ret[$row['name']] = $row['name'];
  return $ret;
}

*/



function __lib_u_doctor_lcmfm_schema()
{
	global $__lib_u_doctor_lcmfm_schemainfo;
	if (!$__lib_u_doctor_lcmfm_schemainfo) {
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
		$__lib_u_doctor_lcmfm_schemainfo =
			array('XCOLS' => $cols, 'REVINDEX' => $revinfo);
	}
	return $__lib_u_doctor_lcmfm_schemainfo;
}

function __lib_u_doctor_lcmfm_cfg(&$cfg)
{
	$schema = __lib_u_doctor_lcmfm_schema();
	$dynamic_cols = $schema['XCOLS'];
	$static_cols = array(
		array('Column' => 'dos', 'Draw' => 'date',
		      'Option' => array('validate' => 'nonnull,date')),
		array('Column' => 'stop',
					'Label' => 'stop',
				   
				       'Draw' => 'text',
				        ),
		array('Column' => 'others', 'Draw' => 'textarea'),
		array('Column' => 'mokuteki', 'Draw' => 'text'),
		array('Column' => 'yotei', 'Draw' => 'date'),
		array('Column' => 'shiji',
					'Label' => '指示医',
				   
				       'Draw' => 'text',
				       					    
			    ),
/*
array('Column' => 'a0', 'Draw' => 'text'),
array('Column' => 'b0', 'Draw' => 'text'),
array('Column' => 'a1', 'Draw' => 'text'),
array('Column' => 'b1', 'Draw' => 'text'),
*/

		);


	$lcols = $static_cols;
	$lcols[] = array('Column' => 'find');

	$local_cfg = array('TABLE' => 'lcmfm',
			   'LCOLS' => $lcols);

	$__c = $static_cols;
/*
	foreach ($dynamic_cols as $elem) {
		$__c[] = $elem;
 

	}
*/

	$local_cfg['DCOLS'] = $__c;


	$local_cfg['ECOLS'] = $__c;

	$__c = array();

	foreach ($static_cols as $elem) {
		$__c[] = $elem['Column'];
	}
	$__c[] = 'patient';
	$local_cfg['COLS'] = $__c;

	$cfg = array_merge($local_cfg, $cfg);
}

function __lib_u_doctor_lcmfm_fetch_sub(&$db, $id)
{
	$idq = mx_db_sql_quote($id);
	$stmt = <<<SQL
SELECT
M.name AS "部位名称",
O.yotei as "yotei",
O.shiji as "shiji",
O.mokuteki as "mokuteki",
O.stop as "stop",
M."ObjectID" AS "buiObjectID",
D.dirs, D.leftdir, D.rightdir, D.bothdirs, D.digits
FROM "lcmfm" AS O
JOIN "lcmfmcont" AS D
ON O."ObjectID" = D."lcm"
JOIN "ctmaster" AS M
ON M."ObjectID" = D."bui" AND M."Superseded" IS NULL
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
	
	$schema = __lib_u_doctor_lcmfm_schema();
	$revinfo = $schema['REVINDEX'];
	foreach ($data as $e) {
		$name = $e['部位名称'];
		if (!array_key_exists($name, $revinfo))
			continue;
		$r = $revinfo[$name];
		if (array_key_exists('L', $r) && $e['leftdir'])
			$val["$name.L"] = 'Y';
		if (array_key_exists('R', $r) && $e['rightdir'])
			$val["$name.R"] = 'Y';
		if (array_key_exists('B', $r) && $e['bothdirs'])
			$val["$name.B"] = 'Y';
		for ($i = 0; $i < strlen($e['digits']); $i++) {
			$it = "D" . $e['digits'][$i];
			$d = "$name.$it";
			if (array_key_exists($it, $r))
				$val[$d] = 'Y';
		}
		$t = '';
		$dirs = $e['dirs'];
		for ($i = 0; $i < strlen($dirs); $i++) {
			$it = $dirs[$i];
			$d = "$name.$it";
			if (array_key_exists($it, $r))
				$val[$d] = 'Y';
			else {
				if ($t != '')
					$t .= ',';
				$t .= $it;
			}
		}
		$val["$name.T"] = $t;
	}
	return $val;
}

function __lib_u_doctor_lcmfm_apply_template(&$data, $it, $read_only=NULL)
{
	$schema = __lib_u_doctor_lcmfm_schema();
	$revinfo = $schema['REVINDEX'];
	$s = file_get_contents($_SERVER['DOCUMENT_ROOT'] .
			       '/templates/lcmfm.html');
	$result = array();
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



function __lib_u_doctor_lcmfm_apply_template1(&$data, $it, $read_only=NULL)
{
	$schema = __lib_u_doctor_lcmfm_schema();
	$revinfo = $schema['REVINDEX'];
	$s = file_get_contents($_SERVER['DOCUMENT_ROOT'] .
			       '/templates/lcmfm2.html');
	$result = array();
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

function __lib_u_doctor_lcmfm_summarize($dcols, $d)
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

class list_of_lcmfms extends list_of_ppa_objects {
	function list_of_lcmfms($prefix, $config=NULL) {
		__lib_u_doctor_lcmfm_cfg(&$config);
		list_of_ppa_objects::list_of_ppa_objects($prefix, $config);
	}




function row_paging_orders() {
		$paging_keys = $this->row_paging_keys();
		$paging_orders = array();
		foreach ($paging_keys as $col) {
			if ($col == 'dos' ||
			    $col == 'ObjectID')
				$paging_orders[] = 1;
			else
				$paging_orders[] = 0;
		}
		return $paging_orders;
	}

	function annotate_fetched_data() {
		$annotated = array();
		$db = mx_db_connect();
		$dc = $this->so_config['DCOLS'];
		foreach ($this->fetched_data as $d) {
			$oid = $d['ObjectID'];
			$v = __lib_u_doctor_lcmfm_fetch_sub(&$db, $oid);
			$items = __lib_u_doctor_lcmfm_summarize($dc, $v);
			$d['find'] = implode(', ', $items);
			$annotated[] = $d;
		}
		$this->fetched_data = $annotated;
	}

}

class lcmfm_display extends simple_object_display {
	function lcmfm_display($prefix, $config=NULL) {
		__lib_u_doctor_lcmfm_cfg(&$config);
		simple_object_display::simple_object_display($prefix, $config);
	}

	function annotate_row_data(&$data) {
		$db = mx_db_connect();
		$val = __lib_u_doctor_lcmfm_fetch_sub
			(&$db, $data['ObjectID']);
		$data = array_merge($data, $val);
	}

	function draw_body($data, $hdata) {
		print __lib_u_doctor_lcmfm_apply_template1(&$data, $this, 1);
	}

	function module_info($p_pid) {
		$d = $this->prepare_data_for_draw();

		$candidates = array("u/doctor/lcmfm.php",
				    "u/everybody/lcmfm.php");
		$avail = mx_filter_accessible_application($candidates);
		if (count($avail) == 0)
			$url = NULL;
		else {
			$application = $avail[0]['path'];
			$url = sprintf("/$application?SetPatient=1&".
				       "PatientID=%s&SetSODObject=%s",
				       $p_pid, $d['ObjectID']);
		}

		$text = 'LCMFM';
		$fuller = '';
                $yotei ='';
		$dcols = $this->so_config['DCOLS'];
		$items = __lib_u_doctor_lcmfm_summarize($dcols, $d);
		foreach ($items as $item) {
			$fuller .= htmlspecialchars($item) . "<br />";
		}
		foreach (array('others') as $col) {
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


		return array('timestamp' => $d['dos'],
			     'text' => $text,
			     'fuller' => $fuller,

			     'callback_url' => $url,
			     'thumb' => NULL,
			     'object_id' => $d['ObjectID'],
			     );
	}


//  03-25-2011 add

function print_sod($template='srl') {
	
$oid = $this->id;




$db = mx_db_connect();


$stmt = 'SELECT M.name AS "部位名称",M."ObjectID" AS "buiObjectID",D.dirs, D.leftdir, D.rightdir,D.bothdirs, D.digits , O.shiji, O.yotei,O.mokuteki,O.stop  FROM "lcmfm" AS O JOIN "lcmfmcont" AS D ON O."ObjectID" = D."lcm" JOIN "ctmaster" AS M ON M."ObjectID" = D."bui" AND M."Superseded" IS NULL WHERE O."ObjectID" = ' . $oid;


	$val = array();
	$sth = pg_query($db, $stmt);




	
	$data = pg_fetch_all($sth);
// 0325-2011

    
	


	

//	 $schema = __lib_u_doctor_lcmfm_schema();
	 $revinfo = $schema['REVINDEX'];
/*
	  foreach ($data as $e) {
		$name = $e['部位名称'];
	

	

		if (!array_key_exists($name, $revinfo))
			continue;
		$r = $revinfo[$name];
		if (array_key_exists('L', $r) && $e['leftdir'])
			$val["$name.L"] = 'Y';
		if (array_key_exists('R', $r) && $e['rightdir'])
			$val["$name.R"] = 'Y';
		if (array_key_exists('B', $r) && $e['bothdirs'])
			$val["$name.B"] = 'Y';
		for ($i = 0; $i < strlen($e['digits']); $i++) {lcm
			$it = "D" . $e['digits'][$i];
			$d = "$name.$it";
			if (array_key_exists($it, $r))
				$val[$d] = 'Y';
		}
		$t = '';
		$dirs = $e['dirs'];
		for ($i = 0; $i < strlen($dirs); $i++) {
			$it = $dirs[$i];
			$d = "$name.$it";
			if (array_key_exists($it, $r))
				$val[$d] = 'Y';
			else {
				if ($t != '')
					$t .= ',';
				$t .= $it;
			}
		}
		$val["$name.T"] = $t;
 

	}


 */


  

    
  $status = 0;
   
    
    $s =  <<< HTML
    <SCRIPT LANGUAGE="JavaScript">
       window.open("print22.php?test_app_type=${test_app_type}&status=${status}&oid={$oid}", "","width=640,height=640");
    </SCRIPT>
HTML;
    print $s;



  }

// 03-25-2011

}

class lcmfm_edit extends simple_object_ppa_edit {
	function lcmfm_edit($prefix, $config=NULL) {
		__lib_u_doctor_lcmfm_cfg(&$config);
		simple_object_edit::simple_object_edit($prefix, $config);
	}

	function anew_tweak($orig_id) {
		simple_object_ppa_edit::anew_tweak($orig_id);
		$this->data['dos'] = mx_today_string();
$this->data['yotei'] = mx_today_string();
	}

	function annotate_form_data(&$data) {
		simple_object_ppa_edit::annotate_form_data(&$data);
	}

	function annotate_row_data(&$data) {
		$db = mx_db_connect();
		$val = __lib_u_doctor_lcmfm_fetch_sub
			(&$db, $data['ObjectID']);
		$data = array_merge($data, $val);
		simple_object_ppa_edit::annotate_row_data(&$data);
	}

	function data_compare($curr, $data) {
		if (simple_object_ppa_edit::data_compare($curr, $data))
			return 1;

		$schema = __lib_u_doctor_lcmfm_schema();
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

		$idq = mx_db_sql_quote($id);
		if (!is_null($stash_id)) {
			$stash_idq = mx_db_sql_quote($stash_id);

			$stmt = <<<SQL
UPDATE "lcmfmcont"
SET "lcm" = $stash_idq
WHERE "lcm" = $idq
SQL;
			if (!pg_query($db, $stmt))
				return pg_last_error($db);
		}

		$schema = __lib_u_doctor_lcmfm_schema();
		$revinfo = $schema['REVINDEX'];
		$d = &$this->data;
		foreach ($revinfo as $name => $pieces) {
			$ldir = ($d["$name.L"] == 'Y') ? "'Y'" : 'NULL';
			$rdir = ($d["$name.R"] == 'Y') ? "'Y'" : 'NULL';
			$bdir = ($d["$name.B"] == 'Y') ? "'Y'" : 'NULL';
			$digits = '';
			for ($i = 1; $i <= 5; $i++) {
				if ($d["$name.D$i"] == 'Y')
					$digits .= "$i";
			}
			$digitsq = mx_db_sql_quote($digits);
			$dirs = array();
			foreach (preg_split('/[^\d]+/', $d["$name.T"])
				 as $dir) {
				if ($dir != '') {
					$dirs[$dir] = $dir;
				}
			}
			foreach ($d as $k => $v) {
				list($n, $suffix) = explode('.', $k);
				if ($n != $name || $v != 'Y')
					continue;
				if (1 <= $suffix + 0)
					$dirs[$suffix] = 1;
			}
			$dirs = array_keys($dirs);
			if (!count($dirs) && $digits == '' &&
			    $ldir == 'NULL' &&
			    $rdir == 'NULL' &&
			    $bdir == 'NULL')
				continue;

			$nameq = mx_db_sql_quote($name);
			$dirs = mx_db_sql_quote(implode('', $dirs));
			$stmt = <<<SQL
INSERT INTO "lcmfmcont"
("lcm", "bui", "dirs", "leftdir", "rightdir", "bothdirs", "digits")
SELECT
$idq, M."ObjectID", $dirs, $ldir, $rdir, $bdir, $digitsq
FROM "ctmaster" AS M
WHERE M."Superseded" IS NULL AND M.name = $nameq
LIMIT 1
SQL;
			if (!pg_query($db, $stmt))
				return pg_last_error($db);
		}
		return '';
	}

	function draw_body_1($data, $soc) {
		print __lib_u_doctor_lcmfm_apply_template(&$data, $this);
	}

	function is_empty_order() {
		$schema = __lib_u_doctor_lcmfm_schema();
		$xcols = $schema['XCOLS'];
		$data = $this->data;
		if (trim($data['others']) != '')
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
		return 'ok';
	}

}

/*
 * This is used by index-pt via lib/ord_module.php.
 */
function lcmfm_module_index_info
(&$dbh, $p_oid, $p_pid, $time_from, $time_to, $options=NULL) {
	if (!is_null($time_from))
		$limit[] = 'X."dos" >= '. mx_db_sql_quote($time_from);
	if (!is_null($time_to))
		$limit[] = 'X."dos" <= '. mx_db_sql_quote($time_to);
	if (!count($limit))
		$num_limit = 1;
	else
		$num_limit = 0;
	$limit[] = 'X."Superseded" IS NULL';
	$q_oid = mx_db_sql_quote($p_oid);
	if (count($limit))
		$limit = "\nAND " . implode(' AND ', $limit);
	else
		$limit = '';
	if ($num_limit)
		$num_limit = ' LIMIT 30';
	else
		$num_limit = '';

	$sql = <<<SQL
SELECT "ObjectID"
FROM "lcmfm" AS X
WHERE X."patient" = $q_oid$limit
$num_limit
SQL;
	$all = pg_fetch_all(pg_query($dbh, $sql));
	$result = array();
	if ($all === false)
		return $result;

	$ix = 0;
	foreach ($all as $e) {
		$object = $e['ObjectID'];
		$config = array();
		$sod = new lcmfm_display("sod-$ix-", &$config);
		$sod->reset($object);
		$result[] = $sod->module_info($p_pid);
	}
	return $result;
}

?>
