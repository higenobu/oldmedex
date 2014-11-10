<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/authorization.php';

class mx_management_authorization extends single_table_application {
	var $_browse_only = 1;
	var $use_single_pane = 1;
	var $_upper = array('index.php' => '/images/top_button.png',
			    'u/manage/index.php' => '管理アプリケーション'
			    );

	function setup() {
		$this->loa = new list_of_applications('loa-');
		$this->sae = new authorization_edit('sae-');
	}

	function allow_new() {
		return NULL;
	}

	function single_pane() {
		global $_mx_resource_dir;

		if ($this->loa->changed())
			$this->sae->reset($this->loa->chosen());

		print '<table><tr valign="top">';

		// Lower-left
		print '<td width="50%">';

		mx_titlespan('アプリケーションのリスト');
		$this->loa->draw();

		// Lower-right
		print '</td><td width="50%">';

		$this->sae->draw();

		// End
		print '</td></tr></table></form>';
	}
}

$it = new mx_management_authorization();
$it->main();
?>
