<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';

$_lib_u_ct_record_chushi_cfg = array
('TABLE' => '¼£¸³Ãæ»ßÃ¦Íî',
 'COLS' => array("Ãæ»ß¡¦Ã¦Íî¤ÎÍ­Ìµ",
		 "Ãæ»ß¡¦Ã¦ÍîÆü",
		 "Ãæ»ßÍýÍ³1",
		 "Ãæ»ßÍýÍ³2",
		 "Ãæ»ßÍýÍ³3",
		 "Ãæ»ßÍýÍ³4",
		 "Ãæ»ßÍýÍ³5",

		 "Ã¦ÍîÍýÍ³1",
		 "Ã¦ÍîÍýÍ³2",
		 "Ã¦ÍîÍýÍ³3",
		 "¥³¥á¥ó¥È",
		 ),
 'ICOLS' => array(
		  "Ãæ»ß¡¦Ã¦Íî¤ÎÍ­Ìµ",
		  "Ãæ»ß¡¦Ã¦ÍîÆü",
		  "Ãæ»ßÍýÍ³1",
		  "Ãæ»ßÍýÍ³2",
		  "Ãæ»ßÍýÍ³3",
		  "Ãæ»ßÍýÍ³4",
		  "Ãæ»ßÍýÍ³5",
		  
		  "Ã¦ÍîÍýÍ³1",
		  "Ã¦ÍîÍýÍ³2",
		  "Ã¦ÍîÍýÍ³3",
		  "¥³¥á¥ó¥È",
		  "¼£¸³¥ª¡¼¥À"
		 ),
 'ECOLS' => array(
		  array('Column' => "Ãæ»ß¡¦Ã¦Íî¤ÎÍ­Ìµ",
			'Draw' => 'radio',
			'Enum' => array(NULL => 'Ì¤µ­Æþ',
					1 => '¤Ê¤·',
					2 => '¤¢¤ê'
					),
			),
		  array('Column' => "Ãæ»ß¡¦Ã¦ÍîÆü",
			'Draw' => 'date',
			),
		  array('Column' => "Ãæ»ßÍýÍ³1",
			'Draw' => 'check',
			'Caption' => 'Èï¸¡¼Ô¤«¤é¼­Âà¤Î¿½¤·½Ð¤¬¤¢¤Ã¤¿'
			),
		  array('Column' => "Ãæ»ßÍýÍ³2",
			'Draw' => 'check',
			'Caption' => '½ÅÆÆ¤ÊÍ­³²»ö¾Ý¤¬È¯¸½¤·¡¢ÅêÍ¿·ÑÂ³º¤Æñ¤ÈÈ½ÃÇ¤µ¤ì¤¿'
			),
		  array('Column' => "Ãæ»ßÍýÍ³3",
			'Draw' => 'check',
			'Caption' => '¼£¸³´ü´ÖÃæ¤Î¶öÈ¯»ö¸Î¤¢¤ë¤¤¤ÏØí´µ¤Ê¤É¤Ë¤è¤êÅêÍ¿·ÑÂ³¤¬º¤Æñ¤È¤Ê¤Ã¤¿'
			),
		  array('Column' => "Ãæ»ßÍýÍ³4",
			'Draw' => 'check',
			'Caption' => '¼£¸³³«»Ï¸å¡¢Èï¸¡¼Ô¤¬ÂÐ¾Ý³°¤Ç¤¢¤ë»ö¤¬È½ÌÀ¤·¤¿'
			),
		  array('Column' => "Ãæ»ßÍýÍ³5",
			'Draw' => 'check',
			'Caption' => '¤½¤ÎÂ¾¡¢¼£¸³ÀÕÇ¤°å»Õ¡¦¼£¸³Ê¬Ã´°å»Õ¤ÎÈ½ÃÇ¤Ë¤è¤êÃæ»ß¤·¤¿'
			),
		  array('Column' => "Ã¦ÍîÍýÍ³1",
			'Draw' => 'check',
			'Caption' => 'Íè±¡¤»¤º'
			),
		  array('Column' => "Ã¦ÍîÍýÍ³2",
			'Draw' => 'check',
			'Caption' => 'Èï¸¡¼Ô¤¬¼£¸³ÀÕÇ¤°å»Õ¡¦¼£¸³Ê¬Ã´°å»Õ¤Î»Ø¼¨¤Ë½¾¤ï¤Ê¤¤(Èó¶¨ÎÏ)'
			),
		  array('Column' => "Ã¦ÍîÍýÍ³3",
			'Draw' => 'check',
			'Caption' => '¤½¤ÎÂ¾Èï¸¡¼Ô¤ÎÅÔ¹ç¤Ë¤è¤êÃæÃÇ¤µ¤ì¤¿'
			),

		  array('Column' => "¥³¥á¥ó¥È",
			'Draw' => 'textarea'
			)
		  ),
 );

$_lib_u_ct_record_chushi_cfg['LCOLS'] = $_lib_u_ct_record_chushi_cfg['COLS'];
$_lib_u_ct_record_chushi_cfg['DCOLS'] = $_lib_u_ct_record_chushi_cfg['COLS'];

class ct_record_chushi_edit extends simple_object_edit {
  var $debug = 1;
  function ct_record_chushi_edit($prefix, &$app, $cfg=NULL) {
    global $_lib_u_ct_record_chushi_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_ct_record_chushi_cfg;
    //_lib_u_ct_annotate_cfg(&$cfg);
    $this->app = $app;
    $this->data['¼£¸³'] = $this->app->loo->CT_ObjectID;
    $this->data['¼£¸³¥ª¡¼¥À'] = $this->app->sod->chosen();
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }

  function edit($chiken_id) {
    $db = mx_db_connect();
    
    // ID here means ¼£¸³¥ª¡¼¥À id.  What I really want is ¼£¸³Ê»ÍÑ id.
    $stmt = 'select "ObjectID" from "¼£¸³Ãæ»ßÃ¦Íî" where "Superseded" is NULL and "¼£¸³¥ª¡¼¥À"=' . $chiken_id;
    
    $r = mx_db_fetch_single($db, $stmt);
    if(!$r) {
      //print "NO OLD RECORD_CHUSHI FOUND for chiken_id=$chiken_id";
      return $this->anew(null);
    }
    
    $this->id = $r["ObjectID"];
    $this->data = $this->fetch_data($this->id);
    $this->data['¼£¸³¥ª¡¼¥À'] = $chiken_id;
    $this->annotate_row_data(&$this->data);
    $this->Subpick = NULL;
    $this->page = 0;
    $this->edit_tweak();
    $this->origin = $this->fetch_origin_info();
    $this->chosen = 1;
  }

  function annotate_form_data(&$data) {
    $data['¼£¸³¥ª¡¼¥À'] = $this->app->sod->chosen();
    $data['CreatedBy'] = $this->u;
  }

}
?>
