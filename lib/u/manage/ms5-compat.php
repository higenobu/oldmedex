<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ui_config.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/ms5.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/appbar.php';

//   table_name   : name of the table.
//   accept_name  : the name of the column that stores acceptance.
//   column       : a number-indexed array of column names to be shown.

class mx_mx5_compat extends single_table_application {
	var $use_single_pane = 1;
	var $no_control_bar = 1;
	var $_upper = array('index.php' => '/images/top_button.png',
			    'u/manage/index.php' => '´ÉÍý¥¢¥×¥ê¥±¡¼¥·¥ç¥ó'
			    );

	function mx_mx5_compat($config) {
		single_table_application::single_table_application();
		$this->loo = new master_select('ms5-', $config);
	}

	function setup() {
	}

	function top_pane() {
		global $_mx_use_appbar;

		if ($_mx_use_appbar) {
			print "<table><tr valign=\"top\"><td width=\"50%\">";
			$this->top_pane_left(0);
			print "</td><td>";
			print '<a href="master-select-help.html" '.
				'target="_blank">¥Ø¥ë¥×</a>'."\n";
			print "</tr></table>\n";
			mx_appbar($this);
		}
		else
			$this->top_pane_left(1);
	}

	function single_pane() {
		$this->loo->draw();
		$this->show_stat($stat);
	}

	function show_stat() {
		$config = $this->loo->so_config;
		$stat = mx_check_option('stat', $config);
		if (!$stat)
			return;
		$table = mx_db_sql_quote_name($config['TABLE']);
		$dbh = mx_db_connect();
		foreach ($stat as $column => $expr) {
			$stmt = sprintf('SELECT %s AS it ' .
					'FROM %s WHERE "Superseded" IS NULL',
					$expr, $table);
			$value = mx_db_fetch_single($dbh, $stmt);
			if (!$value)
				continue;
			print "<br />";
			print htmlspecialchars($column);
			print ": ";
			print htmlspecialchars($value['it']);
		}
	}

	function uplink() {
		single_table_application::uplink();
		print '<a href="master-select-help.html" '.
			'target="_blank">¥Ø¥ë¥×</a>'."\n";
	}

}

function master_select_table($param, $qbe_enum=NULL, $header_fields=NULL) {
  global $__uiconfig_ms_qbe_enum, $__uiconfig_ms_header_fields;

  if (is_null($qbe_enum))
	  $qbe_enum = $__uiconfig_ms_qbe_enum;
  if (is_null($header_fields))
	  $header_fields = $__uiconfig_ms_header_fields;

  $asort = array();
  foreach ($param['column'] as $c) {
    $asort[$c] = array($c => mx_db_sql_quote_name($c));
  }
  if (array_key_exists('lcols', $param))
	  $lcols = $param['lcols'];
  else
	  $lcols = $param['column'];

  if (array_key_exists('enable_qbe', $param))
	  $enable_qbe = $param['enable_qbe'];
  else
	  $enable_qbe = $param['column'];

  $config = array
    (
     'TABLE' =>'inventt',
//'TABLE' => $param['table_name'],
     'COLS' => array_merge(array('Åö±¡ºÎÍÑ'), $param['column']),
     'accept_name' => 'Åö±¡ºÎÍÑ',
     'LCOLS' => array_merge(array
			    (array('Column' => 'Åö±¡ºÎÍÑ',
				   'Label' => 'Åö±¡ºÎÍÑ',
				   'Span' => count($header_fields),
				   'Draw' => 'pickedit',
				   'Align' => 'center')), $lcols),
     'NOLINK' => 1,
     'ALLOW_SORT' => $asort,
     'ENABLE_QBE' => array_merge
     (array(array('Column' => 'Åö±¡ºÎÍÑ',
		  'Compare' => 'Åö±¡ºÎÍÑ',
		  'Draw' => 'enum',
		  'Enum' => $qbe_enum,
		  'CompareMethod' => 'enum_single_char',
		  'Singleton' => 1)),
     $enable_qbe),
     'DEFAULT_QBE' => array(),
     'Header_Fields' => $header_fields,
     'QBE_Enum' => $qbe_enum,
     );

  if (array_key_exists('config-tweak', $param)) {
	  $fn = $param['config-tweak'];
	  $config = $fn($config, $param);
  }
  if (array_key_exists('stat', $param)) {
	  $config['stat'] = $param['stat'];
  }

  $mx2_compat = new mx_mx5_compat($config);
  $mx2_compat->main();
}
