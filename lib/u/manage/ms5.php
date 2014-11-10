<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

$_lib_u_manage_ms5_config = array
(
 'TABLE' => 'inventt',
 'COLS' => array('��������', 'inventname', 'inventcode', 'value'),
 'accept_name' => '��������',
 'LCOLS' => array
 (array('Column' => '��������',
	'Label' => '��������',
	'Span' => 3,
	'Draw' => 'pickedit',
	'Align' => 'center'),
  array('Column' => 'inventname','Label' => '��̾' ),array('Column' => 'inventcode','Label' => '��̾������' ),array('Column' => 'value','Label' => '�߸˿�' ) ),
 'NOLINK' => 1,
 'ALLOW_SORT' => array
 ('inventname' => array('inventname' => '"inventname"'),
  'inventcode' => array('inventcode' => '"inventcode"'),
 ),
 'ENABLE_QBE' => array
 (array('Column' => '��������',
	'Compare' => '��������',
	'Draw' => 'enum',
	'Enum' => array('' => '�ޥ�������',
			'U' => '̤����Τ�',
			'N' => '�Ժ���ʬ�Τ�',
			'Y' => '���ѡ����ѽ�',
			'YF' => '���ѡ��ѽ�',
			'F' => '�ѽ�'),
	'CompareMethod' => 'enum_single_char',
	'Singleton' => 1),
 array('Column' => 'inventname','Label' => '��̾' ),array('Column' => 'inventcode','Label' => '��̾������' ),array('Column' => 'value','Label' => '�߸˿�' )),
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
    $this->ui_msgs = array('First' => '��Ͽ���ƺǽ��',
			   'Last' => '��Ͽ���ƺǸ��',
			   'Prev' => '��Ͽ��������',
			   'Next' => '��Ͽ���Ƽ���');

    $bulk_control = array();
    $bulk_control['XX'] = '�ڡ�����ư�����˵�Ͽ';
    if (array_key_exists('Y', $this->header_fields)) {
	    $bulk_control['AY'] = '���Ƥ����';
	    $bulk_control['UY'] = '̤��������';
    }
    if (array_key_exists('I', $this->header_fields)) {
	    $bulk_control['AI'] = '���Ƥ������';
	    $bulk_control['UI'] = '̤����������';
    }
    if (array_key_exists('B', $this->header_fields)) {
	    $bulk_control['AB'] = '���Ƥ򱡳�����';
	    $bulk_control['UB'] = '̤����򱡳�����';
    }
    if (array_key_exists('N', $this->header_fields)) {
	    $bulk_control['AN'] = '���Ƥ��Ժ���';
	    $bulk_control['UN'] = '̤������Ժ���';
    }
    $bulk_control['AU'] = '���Ƥ�̤����';

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
	  if ($desc['Column'] == '��������' && $thtd == 'td')
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
