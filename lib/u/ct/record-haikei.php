<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';

$_lib_u_ct_record_haikei_cfg = array('TABLE' => '¼£¸³ÇØ·Ê',
	     'COLS' => array("Æ±°ÕÇ¯·îÆü",
			     "Ç¯Îð",
			     "¿ÈÄ¹",
			     "ÂÎ½Å",
			     "ÈîËþÅÙ",
			     "ÊÄ·Ð»þ´ü",
			     "»ÒµÜÅ¦½ÐÎò",
			     "»ÒµÜÅ¦½Ð½Ñ¼°",
			     "»ÒµÜÅ¦½ÐÇ¯·î",
			     "ÍñÁãº¸Å¦½ÐÎò",
			     "ÍñÁãº¸Å¦½ÐÇ¯·î",
			     "ÍñÁã±¦Å¦½ÐÎò",
			     "ÍñÁã±¦Å¦½ÐÇ¯·î",
			     "´û±ýÎò",
			     "´û±ýÎò(1)",
			     "´û±ýÎò(2)",
			     "´û±ýÎò(3)",
			     "´û±ýÎò(4)",
			     "´û±ýÎò(5)",
			     "´û±ýÎò(6)",
			     "´û±ýÎò(7)",
			     "´û±ýÎò(8)",
			     "´û±ýÎò(9)",
			     "´û±ýÎò(10)",
			     "¹çÊ»¾É",
			     "¹çÊ»¾É(1)",
			     "¹çÊ»¾É(2)",
			     "¹çÊ»¾É(3)",
			     "¹çÊ»¾É(4)",
			     "¹çÊ»¾É(5)",
			     "¹çÊ»¾É(6)",
			     "¹çÊ»¾É(7)",
			     "¹çÊ»¾É(8)",
			     "¹çÊ»¾É(9)",
			     "¹çÊ»¾É(10)",
			     "HBs¹³¸¶",
			     "HBs¹³¸¶¸¡ººÆü",
			     "ÇßÆÇ",
			     "ÇßÆÇ¸¡ººÆü",
			     "HCV¹³ÂÎ",
			     "HCV¹³ÂÎ¸¡ººÆü",
			     "HIV",
			     "HIV¸¡ººÆü",
			     "ÅêÍ¿Æü»þ",
			     "ÌôºÞ¶èÊ¬",
			     "ÅêÍ¿ÎÌ",
			     "¥³¥á¥ó¥È",
			     ),
	     'ICOLS' => array("Æ±°ÕÇ¯·îÆü",
			     "Ç¯Îð",
			     "¿ÈÄ¹",
			     "ÂÎ½Å",
			     "ÈîËþÅÙ",
			     "ÊÄ·Ð»þ´ü",
			     "»ÒµÜÅ¦½ÐÎò",
			     "»ÒµÜÅ¦½Ð½Ñ¼°",
			     "»ÒµÜÅ¦½ÐÇ¯·î",
			     "ÍñÁãº¸Å¦½ÐÎò",
			     "ÍñÁãº¸Å¦½ÐÇ¯·î",
			     "ÍñÁã±¦Å¦½ÐÎò",
			     "ÍñÁã±¦Å¦½ÐÇ¯·î",
			     "´û±ýÎò",
			     "´û±ýÎò(1)",
			     "´û±ýÎò(2)",
			     "´û±ýÎò(3)",
			     "´û±ýÎò(4)",
			     "´û±ýÎò(5)",
			     "´û±ýÎò(6)",
			     "´û±ýÎò(7)",
			     "´û±ýÎò(8)",
			     "´û±ýÎò(9)",
			     "´û±ýÎò(10)",
			     "¹çÊ»¾É",
			     "¹çÊ»¾É(1)",
			     "¹çÊ»¾É(2)",
			     "¹çÊ»¾É(3)",
			     "¹çÊ»¾É(4)",
			     "¹çÊ»¾É(5)",
			     "¹çÊ»¾É(6)",
			     "¹çÊ»¾É(7)",
			     "¹çÊ»¾É(8)",
			     "¹çÊ»¾É(9)",
			     "¹çÊ»¾É(10)",
			     "HBs¹³¸¶",
			     "HBs¹³¸¶¸¡ººÆü",
			     "ÇßÆÇ",
			     "ÇßÆÇ¸¡ººÆü",
			     "HCV¹³ÂÎ",
			     "HCV¹³ÂÎ¸¡ººÆü",
			     "HIV",
			     "HIV¸¡ººÆü",
			     "ÅêÍ¿Æü»þ",
			     "ÌôºÞ¶èÊ¬",
			     "ÅêÍ¿ÎÌ",
			     "¥³¥á¥ó¥È",
			     "¼£¸³¥ª¡¼¥À",
			     ),
	     'ECOLS' => array(
			      array('Column' => "Æ±°ÕÇ¯·îÆü",
				    'Draw' => 'date'
				    ),
			      "Ç¯Îð",
			      "¿ÈÄ¹",
			      "ÂÎ½Å",
			      "ÈîËþÅÙ",
			      array('Column' => "ÊÄ·Ð»þ´ü",
				    'Draw' => 'date'
				    ),
			      array('Column' => "»ÒµÜÅ¦½ÐÎò",
				    'Draw' => 'radio',
				    'Enum' => array(NULL => 'Ì¤µ­Æþ',
						    1 => '¤Ê¤·',
						    2 => '¤¢¤ê'),
				    ),
			      "»ÒµÜÅ¦½Ð½Ñ¼°",
			      array('Column' => "»ÒµÜÅ¦½ÐÇ¯·î",
				    'Draw' => 'date'
				    ),
			      array('Column' => "ÍñÁãº¸Å¦½ÐÎò",
				    'Draw' => 'radio',
				    'Enum' => array(NULL => 'Ì¤µ­Æþ',
						    1 => '¤Ê¤·',
						    2 => '¤¢¤ê'),
				    ),
			      array('Column' => "ÍñÁãº¸Å¦½ÐÇ¯·î",
				    'Draw' => 'date',
				    ),
			     "ÍñÁã±¦Å¦½ÐÎò",
			      array('Column' => "ÍñÁã±¦Å¦½ÐÇ¯·î",
				    'Draw' => 'date',
				    ),
			      array('Column' => "´û±ýÎò",
				    'Draw' => 'radio', 
				    'Enum' => array(NULL => 'Ì¤µ­Æþ',
						    1 => '¤Ê¤·',
						    2 => '¤¢¤ê'),
				    ),
			     "´û±ýÎò(1)",
			     "´û±ýÎò(2)",
			     "´û±ýÎò(3)",
			     "´û±ýÎò(4)",
			     "´û±ýÎò(5)",
			     "´û±ýÎò(6)",
			     "´û±ýÎò(7)",
			     "´û±ýÎò(8)",
			     "´û±ýÎò(9)",
			     "´û±ýÎò(10)",
			      array('Column' => "¹çÊ»¾É",
				    'Draw' => 'radio', 
				    'Enum' => array(NULL => 'Ì¤µ­Æþ',
						    1 => '¤Ê¤·',
						    2 => '¤¢¤ê'),
				    ),
			     "¹çÊ»¾É(1)",
			     "¹çÊ»¾É(2)",
			     "¹çÊ»¾É(3)",
			     "¹çÊ»¾É(4)",
			     "¹çÊ»¾É(5)",
			     "¹çÊ»¾É(6)",
			     "¹çÊ»¾É(7)",
			     "¹çÊ»¾É(8)",
			     "¹çÊ»¾É(9)",
			     "¹çÊ»¾É(10)",
			      array('Column' => "HBs¹³¸¶",
				    'Draw' => 'radio', 
				    'Enum' => array(NULL => 'Ì¤µ­Æþ',
						    1 => '±¢À­',
						    2 => 'ÍÛÀ­'),
				    ),
			      array('Column' => "HBs¹³¸¶¸¡ººÆü",
				    'Draw' => 'date'
				    ),
			      array('Column' => "ÇßÆÇ",
				    'Draw' => 'radio', 
				    'Enum' => array(NULL => 'Ì¤µ­Æþ',
						    1 => '±¢À­',
						    2 => 'ÍÛÀ­'),
				    ),
			      
			      array('Column' => "ÇßÆÇ¸¡ººÆü",
				    'Draw' => 'date'
				    ),
			      array('Column' => "HCV¹³ÂÎ",
				    'Draw' => 'radio', 
				    'Enum' => array(NULL => 'Ì¤µ­Æþ',
						    1 => '±¢À­',
						    2 => 'ÍÛÀ­'),
				    ),
			      
			      array('Column' => "HCV¹³ÂÎ¸¡ººÆü",
				    'Draw' => 'date'
				    ),
			      array('Column' => "HIV",
				    'Draw' => 'radio', 
				    'Enum' => array(NULL => 'Ì¤µ­Æþ',
						    1 => '±¢À­',
						    2 => 'ÍÛÀ­'),
				    ),
			      
			     array('Column' => "HIV¸¡ººÆü",
				    'Draw' => 'date'
				    ),
			      
			     array('Column' => "ÅêÍ¿Æü»þ",
				   'Draw' => 'datetime'
				   ),
			     array('Column' => "ÌôºÞ¶èÊ¬",
				   'Draw' => 'radio',
				   'Enum' => array(NULL => 'Ì¤µ­Æþ',
						   1 => '¼ÂÌô',
						   2 => '¥×¥é¥»¥Ü'
						   ),
				   ),
			     "ÅêÍ¿ÎÌ",
			     "¥³¥á¥ó¥È",
			     ),

	     );
$_lib_u_ct_record_haikei_cfg['LCOLS'] = array('Æ±°ÕÇ¯·îÆü');
$_lib_u_ct_record_haikei_cfg['DCOLS'] = $_lib_u_ct_record_haikei_cfg['COLS'];

class ct_record_haikei_edit extends simple_object_edit {
  var $debug = 1;
  function ct_record_haikei_edit($prefix, &$app, $cfg=NULL) {
    global $_lib_u_ct_record_haikei_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_ct_record_haikei_cfg;
    //_lib_u_ct_annotate_cfg(&$cfg);
    $this->app = $app;
    $this->data['¼£¸³'] = $this->app->loo->CT_ObjectID;
    $this->data['¼£¸³¥ª¡¼¥À'] = $this->app->sod->chosen();
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }

  function edit($chiken_id) {
    $db = mx_db_connect();
    
    // ID here means ¼£¸³¥ª¡¼¥À id.  What I really want is ¼£¸³Ê»ÍÑ id.
    $stmt = 'select "ObjectID" from "¼£¸³ÇØ·Ê" where "Superseded" is NULL and "¼£¸³¥ª¡¼¥À"=' . $chiken_id;
    
    $r = mx_db_fetch_single($db, $stmt);
    if(!$r) {
      //print "NO OLD RECORD_HAIKEI FOUND for chiken_id=$chiken_id";
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
