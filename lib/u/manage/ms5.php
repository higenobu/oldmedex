<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

$_lib_u_manage_ms5_config = array
(
 'TABLE' => 'inventt',
 'COLS' => array('当院採用', 'inventname', 'inventcode', 'value'),
 'accept_name' => '当院採用',
 'LCOLS' => array
 (array('Column' => '当院採用',
	'Label' => '当院採用',
	'Span' => 3,
	'Draw' => 'pickedit',
	'Align' => 'center'),
  array('Column' => 'inventname','Label' => '品名' ),array('Column' => 'inventcode','Label' => '品名コード' ),array('Column' => 'value','Label' => '在庫数' ) ),
 'NOLINK' => 1,
 'ALLOW_SORT' => array
 ('inventname' => array('inventname' => '"inventname"'),
  'inventcode' => array('inventcode' => '"inventcode"'),
 ),
 'ENABLE_QBE' => array
 (array('Column' => '当院採用',
	'Compare' => '当院採用',
	'Draw' => 'enum',
	'Enum' => array('' => 'マスタ全て',
			'U' => '未指定のみ',
			'N' => '不採用分のみ',
			'Y' => '採用・非頻出',
			'YF' => '採用・頻出',
			'F' => '頻出'),
	'CompareMethod' => 'enum_single_char',
	'Singleton' => 1),
 array('Column' => 'inventname','Label' => '品名' ),array('Column' => 'inventcode','Label' => '品名コード' ),array('Column' => 'value','Label' => '在庫数' )),
);

class master_select extends list_of_simple_objects {

  var $header_fields = NULL;
  var $qbe_enum = NULL;

  function master_select($prefix, $config=NULL) {
    global $_lib_u_manage_ms5_config;
    global $__uiconfig_ms_qbe_enum, $__uiconfig_ms_header_fields;

    if (is_null($config)) $config = $_lib_u_manage_ms5_config;
    list_of_simple_objects::list_of_simple_objects
      ($prefix, $config);

    $this->header_fields = $config['Header_Fields'];
    if (!$this->header_fields)
	    $this->header_fields = $__uiconfig_ms_header_fields;
    $this->qbe_enum = $config['QBE_Enum'];
    if (!$this->qbe_enum)
	    $this->qbe_enum = $__uiconfig_ms_qbe_enum;
    $this->ui_msgs = array('First' => '記録して最初へ',
			   'Last' => '記録して最後へ',
			   'Prev' => '記録して前へ',
			   'Next' => '記録して次へ');

    $bulk_control = array();
    $bulk_control['XX'] = 'ページ移動せずに記録';
    if (array_key_exists('Y', $this->header_fields)) {
	    $bulk_control['AY'] = '全てを採用';
	    $bulk_control['UY'] = '未指定を採用';
    }
    if (array_key_exists('I', $this->header_fields)) {
	    $bulk_control['AI'] = '全てを院内採用';
	    $bulk_control['UI'] = '未指定を院内採用';
    }
    if (array_key_exists('B', $this->header_fields)) {
	    $bulk_control['AB'] = '全てを院外採用';
	    $bulk_control['UB'] = '未指定を院外採用';
    }
    if (array_key_exists('N', $this->header_fields)) {
	    $bulk_control['AN'] = '全てを不採用';
	    $bulk_control['UN'] = '未指定を不採用';
    }
    $bulk_control['AU'] = '全てを未指定';

    $this->bulk_control = $bulk_control;

    $setunset = mx_check_request($prefix . 'setunset');

    if (array_key_exists($prefix . 'shown', $_REQUEST)) {
      $db = mx_db_connect();
      foreach ($_REQUEST[$prefix . 'shown'] as $oid) {
	$o = $_REQUEST[$prefix . 'o-' . $oid];
	$n = $_REQUEST[$prefix . 'n-' . $oid];
	if ($n == '') $n = 'U';
	if (! is_null($setunset)) {
	  if (substr($setunset, 0, 1) == 'A' || substr($setunset, 0, 1) == $n)
	    $n = substr($setunset, 1, 1);
	}
	if (($o != $n) || ($setunset == 'XX')) {
	  $this->dbglog("UPD $oid $o vs $n\n");
	  $this->update_selection($db, $oid, $n);
	}
      }
    }
  }

  function draw_list_head() {
    list_of_simple_objects::draw_list_head();
    if ($this->header_fields) {
	print '<tr>';
	foreach ($this->header_fields as $k => $v) {
		printf("<th>%s</th>", $v);
	}
    }
    $sp = count($this->so_config['LCOLS']) - 1;
    print "<th colspan=\"$sp\">&nbsp;</th>";
    print "</tr>\n";
  }

  function dx_pickedit($desc, $value, $row) {
    $oid = $row['ObjectID'];
    mx_formi_hidden($this->prefix . 'shown[]', $oid);
    mx_formi_hidden($this->prefix . 'o-' . $oid, $value);
    mx_formi_radio($this->prefix . 'n-' . $oid, $value,
		   $this->header_fields,
		   array('omit-label' => 1,
			 'item-delimiter' => '</td><td>'));
  }

  function table_control_head($sp, $desc, $thtd, $extra=NULL) {
	  if ($desc['Column'] == '当院採用' && $thtd == 'td')
		  $sp = $desc['Span'] = 1;
	  return list_of_simple_objects::table_control_head($sp, $desc, $thtd, $extra);
  }

  function update_selection(&$db, $oid, $value) {
    global $mx_authenticate_current_user;

    $table = $this->so_config['TABLE'];
    $stash_id =
      mx_db_allocate_unused_id($db, $this->so_config['SEQUENCE']);
    $orig = mx_db_stash_original($db, $table, $oid, $stash_id, &$this);
    $stmt = ('UPDATE ' . mx_db_sql_quote_name($table) .
	     ' SET "CreatedBy" = ' .
	     mx_db_sql_quote($mx_authenticate_current_user) . ', ' .
	     mx_db_sql_quote_name($this->so_config['accept_name']) .
	     ' = ' . mx_db_sql_quote($value) .
	     ' WHERE "ObjectID" = ' . mx_db_sql_quote($oid));
    pg_query($db, $stmt);
  }

  function draw() {
    $pfx = $this->prefix;
    list_of_simple_objects::draw();


    foreach ($this->bulk_control as $cntl => $label) {
      mx_formi_submit($pfx . 'setunset', $cntl,
		      "<span class=\"link\">" .
		      htmlspecialchars($label) . "</span>\n");
    }
  }
}

?>
