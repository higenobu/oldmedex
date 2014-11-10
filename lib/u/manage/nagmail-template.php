<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';

function _lib_u_manage_nagmail_template_cfg($it)
{
	$vocab = array($it->purpose['vocab']);

	$cols = array
		(array('Column' => 'sortorder', 'Label' => '表示順',
		       'Option' => array('validate' => 'nonnull,digits')),
		 array('Column' => 'subject', 'Label' => '表題',
		       'Draw' => 'textarea',
		       'Option' => array('validate' => 'nonnull',
					 'cols' => 60, 'rows' => 1,
					 'vocab' => $vocab,
					 'vocab-no-newline' => 1)),
		 array('Column' => 'body', 'Label' => '本文',
		       'Draw' => 'textarea',
		       'Option' => array('validate' => 'nonnull',
					 'cols' => 60, 'rows' => 8,
					 'vocab' => $vocab,
					 'vocab-no-newline' => 1)),
		 );
	$dc = $cols;
	$dc[] = array('Column' => 'purpose', 'Draw' => NULL);

	$a = array(
		'TABLE' => 'nagmail_template',
		'COLS' => array('sortorder', 'purpose', 'subject', 'body'),

		'LCOLS' => $cols,
		'DCOLS' => $dc,
		'ECOLS' => $dc,
		);
	return $a;
};

function _lib_u_manage_nagmail_purpose($string)
{
	$db = mx_db_connect();
	$string = mx_db_sql_quote($string);
	$stmt = <<<SQL
		SELECT P."ObjectID" AS purpose, P."vocab" AS vocab
		FROM nagmail_purpose AS P
		WHERE P.purpose = $string AND P."Superseded" IS NULL
SQL;
	return mx_db_fetch_single($db, $stmt);
}

class list_of_nagmail_templates extends list_of_simple_objects {

	function list_of_nagmail_templates($prefix, $purpose, $cfg=NULL) {
		$this->purpose = _lib_u_manage_nagmail_purpose($purpose);
		if (is_null($cfg))
			$cfg =_lib_u_manage_nagmail_template_cfg($this);
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
	}

	function base_fetch_stmt_0() {
		$s = list_of_simple_objects::base_fetch_stmt_0();
		return ($s . ' AND purpose = ' .
			mx_db_sql_quote($this->purpose['purpose']));
	}

	function annotate_row_data(&$data) {
		$data['body'] = (mb_substr($data['body'], 0, 40, 'EUC-JP') .
				 "・・・");
	}

}

class nagmail_template_display extends simple_object_display {

	function nagmail_template_display($prefix, $purpose, $cfg=NULL) {
		$this->purpose = _lib_u_manage_nagmail_purpose($purpose);
		if (is_null($cfg))
			$cfg =_lib_u_manage_nagmail_template_cfg($this);
		simple_object_display::simple_object_display($prefix, $cfg);
	}

}

class nagmail_template_edit extends simple_object_edit {

	function nagmail_template_edit($prefix, $purpose, $cfg=NULL) {
		$this->purpose = _lib_u_manage_nagmail_purpose($purpose);
		if (is_null($cfg))
			$cfg =_lib_u_manage_nagmail_template_cfg($this);
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}

	function annotate_form_data(&$data) {
		$data['purpose'] = $this->purpose['purpose'];
	}

}

?>
