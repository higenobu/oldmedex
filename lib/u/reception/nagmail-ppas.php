<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/pp_attr.php';

class list_of_nagmail_ppases extends pp_attr_los {

	var $group = array('リマインドメール');

}

class nagmail_ppas_display extends pp_attr_sod {

	var $group = array('リマインドメール');

}

class nagmail_ppas_edit extends pp_attr_soe {

	var $group = array('リマインドメール');

}

?>
