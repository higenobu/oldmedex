<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';

/*
 * Design.
 *
 * From the patient attribute table, we would want to prepare the
 * generic config structure suitable for soe.
 *
 * More than one applications can be written to edit different
 * groups of attributes.
 *
 * When only editing a subset of attributes, the other attributes
 * should stay the same from the previous version, instead of getting
 * dropped.
 */

function __pp_attr_colname($group, $name)
{
	return sprintf("%s-X-%s", $group, $name);
}

class pp_attr_builder {

	function pp_attr_builder($group, $db=NULL) {
		if (is_null($db))
			$db = mx_db_connect();
		$this->db = $db;
		$this->group = $group;
		$this->cols = array();
		$this->xcols = array(
			array('Column' => 'µ­Ï¿Æü',
			      'Draw' => 'static',
			      'Option' => array('OmitIfEmpty' => 1)),
		);
		$this->attr = array();

		$this->get_attr_schema();
	}

	function get_attr_schema() {
		$db = $this->db;
		$stmt = '
SELECT "¥°¥ë¡¼¥×", "Ì¾¾Î", "Â°À­·¿", "LB", "UB", "DP",
       "ÁªÂò»è", "É½¼¨½ç°Ì", "ObjectID", "length"
FROM "´µ¼ÔÂ°À­°ìÍ÷"
WHERE "Superseded" IS NULL AND (
"Retired" IS NULL OR "Retired" != ' . "'Y'" . '
) AND "É½¼¨½ç°Ì" >= 0
ORDER BY "¥°¥ë¡¼¥×", "É½¼¨½ç°Ì", "ObjectID"';

		$data = pg_fetch_all(pg_query($db, $stmt));
		$no_group_header = (count($this->group) < 2);
		$last_group = NULL;

		foreach ($this->group as $g) {
			if (!is_array($data))
				continue;
			foreach ($data as $d) {
				if ($g != $d['¥°¥ë¡¼¥×'])
					continue;
				if (is_null($last_group) ||
				    $g != $last_group) {
					if (!$no_group_header)
						$this->add_group_header($g);
					$last_group = $g;
				}
				$d['Column'] = __pp_attr_colname($d['¥°¥ë¡¼¥×'], $d['Ì¾¾Î']);
				$this->attr[$d['ObjectID']] = $d['Column'];
				$method = "add_attr_" . $d['Â°À­·¿'];
				$this->$method($d);
			}
		}

		$this->cols[] = '´µ¼Ô';
		$this->cols[] = 'µ­Ï¿Æü';
	}

	function add_group_header($g) {
		$this->xcols[] = array("Label" => $g,
				       "Draw" => 'group_head');
	}

	function add_attr_T($d) {
		$name = $d['Ì¾¾Î'];
		$this->cols[] = $column = $d['Column'];

		$elem = array("Column" => $column,
			      "Label" => $name,
			      "Draw" => 'text');
		if (!is_null($d['length']))
		  $elem['Option']['maxlength'] = $d['length'];
		$this->xcols[] = $elem;
	}

	function add_attr_I($d) {
		/* Not yet */
		$this->add_attr_T($d);
	}

	function add_attr_D($d) {
		/* Not yet */
		$this->add_attr_T($d);
	}

	function add_attr_C($d) {
		$name = $d['Ì¾¾Î'];
		$this->cols[] = $column = $d['Column'];

		$enum = array();
		foreach (explode("\n", $d['ÁªÂò»è']) as $c) {
			$c = trim($c);
			if ($c == '')
				continue;
			$enum[$c] = $c;
		}
		$elem = array("Column" => $column,
			      "Label" => $name,
			      "Draw" => 'enum',
			      "Enum" => $enum);
		if (!is_null($d['length']))
		  $elem['Option']['maxlength'] = $d['length'];
		$this->xcols[] = $elem;
	}

	function add_attr_M($d) {
		$name = $d['Ì¾¾Î'];
		$this->cols[] = $column = $d['Column'];

		$enum = array();
		foreach (explode("\n", $d['ÁªÂò»è']) as $c) {
			$c = trim($c);
			if ($c == '')
				continue;
			$enum[] = $c;
		}
		$elem = array("Column" => $column,
			      "Label" => $name,
			      "Draw" => 'daysoftheweek',
			      "Option" => array('dow' => $enum),
			      );
		$this->xcols[] = $elem;
	}
}

function _lib_pp_attr_fetch_data($id, $attr) {
	$db = mx_db_connect();
	$substmt = '
SELECT M."¥°¥ë¡¼¥×", M."Ì¾¾Î", D."Â°À­ÃÍ", D."Ä¢É¼Â°À­", A."µ­Ï¿Æü", A."´µ¼Ô",
       A."ID", A."ObjectID", A."Superseded", A."CreatedBy"
FROM "Ä¢É¼Â°À­" AS A
JOIN "Ä¢É¼Â°À­¥Ç¡¼¥¿" AS D ON D."Ä¢É¼Â°À­" = A."ObjectID"
JOIN "´µ¼ÔÂ°À­°ìÍ÷" AS M ON M."ObjectID" = D."Â°À­"
WHERE D."Â°À­" IN (' . implode(', ', array_keys($attr)) . ')';

	$stmt = $substmt . ' AND A."ObjectID" = ' . mx_db_sql_quote($id);

	$d = array();
	$q = pg_fetch_all(pg_query($db, $stmt));
	if (!is_array($q)) {
		# A rare case that the parent table has
		# a row but no child table element exist
		# for it.
		$stmt = '
SELECT A."ID", A."ObjectID", A."Superseded", A."CreatedBy",
       A."µ­Ï¿Æü", A."´µ¼Ô"
FROM "Ä¢É¼Â°À­" AS A
WHERE A."ObjectID" = ' . mx_db_sql_quote($id);
		$q = pg_fetch_all(pg_query($db, $stmt));
	}
	if (!is_array($q))
		return $d;
	foreach ($q as $e) {
		if (count($d) == 0) {
			$d['ID'] = $e['ID'];
			$d['ObjectID'] = $e['ObjectID'];
			$d['Superseded'] = $e['Superseded'];
			$d['CreatedBy'] = $e['CreatedBy'];
			$d['´µ¼Ô'] = $e['´µ¼Ô'];
			$d['µ­Ï¿Æü'] = $e['µ­Ï¿Æü'];
		}
		if (is_null($e['Ä¢É¼Â°À­']))
			continue;
		$g = __pp_attr_colname($e['¥°¥ë¡¼¥×'], $e['Ì¾¾Î']);
		$d[$g] = $e['Â°À­ÃÍ'];
	}
	return $d;
}
//04-17-2012 change from kannjyazokusei to chouhyouzokusei
function _lib_pp_attr_find($patient, $group)
{
	$stmt = '
	SELECT M."¥°¥ë¡¼¥×", M."Ì¾¾Î", D."Â°À­ÃÍ", A."µ­Ï¿Æü", A."´µ¼Ô"
	FROM "Ä¢É¼Â°À­" AS A
	JOIN "Ä¢É¼Â°À­¥Ç¡¼¥¿" AS D
	  ON D."Ä¢É¼Â°À­" = A."ObjectID" AND A."Superseded" IS NULL
	JOIN "´µ¼ÔÂ°À­°ìÍ÷" AS M
	  ON M."ObjectID" = D."Â°À­" AND M."Superseded" IS NULL
	WHERE A."´µ¼Ô" = \'%s\'%s';

	if (!is_array($group))
		$group = array($group);
	$group = implode(",", array_map('mx_db_sql_quote', $group));
	if ($group != '') {
		$group = "  AND M.\"¥°¥ë¡¼¥×\" IN ($group)";
	}
	$stmt = sprintf($stmt, $patient, $group);
	$db = mx_db_connect();

	return pg_fetch_all(pg_query($db, $stmt));
}

class pp_attr_los extends list_of_ppa_objects {
	var $table = 'Ä¢É¼Â°À­';
	function pp_attr_los($prefix, $config) {

		$builder = new pp_attr_builder($this->group);
		$this->attr = $builder->attr;

		$config['TABLE'] = $this->table;
		$config['COLS'] = array('µ­Ï¿Æü');
		list_of_ppa_objects::list_of_ppa_objects($prefix, $config);
	}

	function annotate_row_data(&$data) {
		$t = $data['µ­Ï¿Æü'];
		if ($t != '') {
			$data['µ­Ï¿Æü'] = mx_format_timestamp($t, -1);
		}
		list_of_ppa_objects::annotate_row_data(&$data);
	}

}

class pp_attr_soe extends simple_object_edit {
	var $table = 'Ä¢É¼Â°À­';
	function pp_attr_soe($prefix, $config) {

		$builder = new pp_attr_builder($this->group);
		$config['TABLE'] = $this->table;
		$config['COLS'] = $builder->cols;
		$config['ECOLS'] = $builder->xcols;
		$config['HSTMT'] = 'Do not use';
	        if($this->table == 'Ä¢É¼Â°À­') {
		  $config['ICOLS'][] = '´µ¼Ô';
		  $config['ICOLS'][] = 'µ­Ï¿Æü';
		}
		else
		  $config['ICOLS'] = array('´µ¼Ô', 'µ­Ï¿Æü');
		$this->attr = $builder->attr;

		simple_object_edit::simple_object_edit($prefix, $config);

	}

	function annotate_row_data(&$data) {
		$t = $data['µ­Ï¿Æü'];
		if ($t != '') {
			$data['µ­Ï¿Æü'] = mx_format_timestamp($t, -1);
		}
		simple_object_edit::annotate_row_data(&$data);
	}

	function data_compare($curr, $data) {
		foreach ($this->attr as $oid => $col) {
			$a = $curr[$col];
			$b = $data[$col];
			if ("z$a" != "z$b") {
				$this->_data_compare_debug($a, $b, $col);
				return 1;
			}
		}
		return 0;
	}

	function commit($force=NULL) {
		$this->data['´µ¼Ô'] = $this->so_config['Patient_ObjectID'];
		$this->data['µ­Ï¿Æü'] = mx_now_string();
		return simple_object_edit::commit($force);
	}

	function fetch_data($id) {
		return _lib_pp_attr_fetch_data($id, $this->attr);
	}

	function _update_subtables(&$db, $ObjectID, $StashID) {

		if (!is_null($StashID)) {
			/*
			 * We are doing partial updates, so just
			 * make a copy of everything there to
			 * $StashID without touching the current
			 * data.
			 */
			$stmt = '
INSERT INTO "Ä¢É¼Â°À­¥Ç¡¼¥¿" ("Ä¢É¼Â°À­", "Â°À­", "Â°À­ÃÍ")
SELECT ' . mx_db_sql_quote($StashID) . ', "Â°À­", "Â°À­ÃÍ"
FROM "Ä¢É¼Â°À­¥Ç¡¼¥¿"
WHERE "Ä¢É¼Â°À­" = ' . mx_db_sql_quote($ObjectID);
			if (! pg_query($db, $stmt))
				return pg_last_error($db);
		}

		/*
		 * Then delete any existing ones that we are
		 * going to update.
		 */
		$stmt = '
DELETE FROM "Ä¢É¼Â°À­¥Ç¡¼¥¿"
WHERE "Ä¢É¼Â°À­" = ' . mx_db_sql_quote($ObjectID) . '
AND "Â°À­" IN (' . implode(', ', array_keys($this->attr)) . ')';

		if (! pg_query($db, $stmt))
			return pg_last_error($db);

		/*
		 * And finally insert the ones from this round.
		 */
		foreach ($this->attr as $attr_id => $attr_name) {
			$stmt = '
INSERT INTO "Ä¢É¼Â°À­¥Ç¡¼¥¿" ("Â°À­", "Â°À­ÃÍ", "Ä¢É¼Â°À­")
VALUES (' . mx_db_sql_quote($attr_id) . ',
' . mx_db_sql_quote($this->data[$attr_name]) . ',
' . mx_db_sql_quote($ObjectID) . ')';
			if (! pg_query($db, $stmt))
				return pg_last_error($db);
		}

	}

}

class pp_attr_sod extends simple_object_display {
	var $table = 'Ä¢É¼Â°À­';
	function pp_attr_sod($prefix, $config) {

		$builder = new pp_attr_builder($this->group);
		$config['TABLE'] = $this->table;
		$config['COLS'] = $builder->cols;
		$config['DCOLS'] = $builder->xcols;
		$config['HSTMT'] = 'Do not use';

		$config['ICOLS'] = array('´µ¼Ô');
		$this->attr = $builder->attr;
		simple_object_display::simple_object_display($prefix, $config);

	}

	function fetch_data($id) {
		return _lib_pp_attr_fetch_data($id, $this->attr);
	}

	function annotate_row_data(&$data) {
		$t = $data['µ­Ï¿Æü'];
		if ($t != '') {
			$data['µ­Ï¿Æü'] = mx_format_timestamp($t, -1);
		}
		simple_object_display::annotate_row_data(&$data);
	}

}

?>
