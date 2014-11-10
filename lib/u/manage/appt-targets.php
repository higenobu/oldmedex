<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

$_lib_u_manage_appt_targets_cfg = array(
	'TABLE' => 'appt_target',
	'COLS' => array('Ì¾¾Î', 'ÍÆÎÌ'),
	'DCOLS' => array('Ì¾¾Î', 'ÍÆÎÌ'),
	'ECOLS' => array(array('Column' => 'Ì¾¾Î'),
			 array('Column' => 'ÍÆÎÌ',
			       'Option' => array('validate' => 'nnint'))),
	'SEQUENCE' => 'modalities_id_seq',
);

class list_of_appt_targets extends list_of_simple_objects {

	function list_of_appt_targets($prefix, $cfg=NULL) {
		global $_lib_u_manage_appt_targets_cfg;
		if (is_null($cfg))
			$cfg = $_lib_u_manage_appt_targets_cfg;
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
	}

	function annotate_row_data(&$data) {
		$data['ÍÆÎÌ'] =
			mx_default_modality_capacity($data['ObjectID']);
	}
}

class appt_target_display extends simple_object_display {

	function appt_target_display($prefix, $cfg=NULL) {
		global $_lib_u_manage_appt_targets_cfg;
		if (is_null($cfg))
			$cfg = $_lib_u_manage_appt_targets_cfg;
		simple_object_display::simple_object_display($prefix, $cfg);
	}

	function annotate_row_data(&$data) {
		$data['ÍÆÎÌ'] =
			mx_default_modality_capacity($data['ObjectID']);
	}
}

class appt_target_edit extends simple_object_edit {

	function appt_target_edit($prefix, $cfg=NULL) {
		global $_lib_u_manage_appt_targets_cfg;
		if (is_null($cfg))
			$cfg = $_lib_u_manage_appt_targets_cfg;
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}

	function _update_subtables($db, $id, $stash) {
		$capacity = $this->data['ÍÆÎÌ'];
		$modality = $id;
		mx_update_modality_capacity($modality, $capacity);
		return '';
	}

	function _insert_stmt($data, $id, $stash) {
		if ($stash) {
			return 'SELECT TRUE';
		}
		$name = mx_db_sql_quote($data['Ì¾¾Î']);
		return ("INSERT INTO modalities (id, name, rtype) values " .
			"($id, $name, 401)");
	}

	function _update_stmt($data, $user, $id) {
		$name = mx_db_sql_quote($data['Ì¾¾Î']);
		return "UPDATE modalities set name = $name WHERE id = $id";
	}

	function annotate_row_data(&$data) {
		$data['ÍÆÎÌ'] =
			mx_default_modality_capacity($data['ObjectID']);
		simple_object_edit::annotate_row_data(&$data);
	}
}
?>
