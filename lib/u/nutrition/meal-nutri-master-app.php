<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/nutrition/meal-nutri-master.php';

class meal_nutri_master_application extends single_table_application {

	function list_of_objects($prefix) {
		return new list_of_meal_nutri_masters($prefix);
	}

	function object_display($prefix) {
		return new meal_nutri_master_display($prefix);
	}

	function object_edit($prefix) {
		return new meal_nutri_master_edit($prefix);
	}

}

?>

