<?php // -*- mode: php; coding: euc-japan -*-
#$_SERVER['DOCUMENT_ROOT'] = '/net/pete/export/rose/home/kenji/php/html';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/index-pt.php';

require_once 'SOAP/Value.php';
require_once 'SOAP/Fault.php';
require_once 'medex_types.php';
require_once 'medex_da.php';

function np($patient_id)
{
  global $_mx_patient_id_zeropad;
  return mx_zeropad($patient_id, $_mx_patient_id_zeropad);
}

function htmldecode($s)
{
  $s = str_replace("&amp;", "&", $s);
  $s = str_replace("&lt;", "<", $s);
  $s = str_replace("&gt;", ">", $s);
  $s = str_replace("&quot;", '"', $s);
  $s = str_replace("&#039;", "'", $s);
  return $s;
}


class DummyConnection extends IDbConnection {

  function &DummyConnection(&$db) {
    $this->Connection = &$db;
  }

  function Open() {
  }

  function Close() {
  }
}

class SOAP_Medex_Server {
  var $__dispatch_map = array();
  function SOAP_Medex_Server() {
    $db = mx_db_connect();
    $this->ds = new MedexDataSet();
    $this->conn = new DummyConnection($db);
    
    #---- single Patient-----------------------------------------
    $this->__typedef['{http://medex.twinsun.com/xsd}Patient'] = 
      $this->ds->Tables['Patient']->GetColumnTypes();
    
    # Karte
    $this->__typedef['{http://medex.twinsun.com/xsd}Karte'] = 
      $this->ds->Tables['Karte']->GetColumnTypes();

    # Index
    $this->__typedef['{http://medex.twinsun.com/xsd}Index'] = 
      index_pt_soap_type();

    # RxOrder
    $this->__typedef['{http://medex.twinsun.com/xsd}RxOrder'] = 
      $this->ds->Tables['RxOrder']->GetColumnTypes();

    # RxOrderContent
    $this->__typedef['{http://medex.twinsun.com/xsd}RxOrderContent'] = 
      $this->ds->Tables['RxOrderContent']->GetColumnTypes();

    #---- array of RxOrderContent--------------------------------
    # ArrayOfRxOrderContent
    $this->__typedef['{http://medex.twinsun.com/xsd}ArrayOfRxOrderContent'] = 
      array(array('{http://medex.twinsun.com/xsd}RxOrderContent'));

    #---- array of RxOrder---------------------------------------
    # ArrayOfRxOrder
    $this->__typedef['{http://medex.twinsun.com/xsd}ArrayOfRxOrder'] = 
      array(array('{http://medex.twinsun.com/xsd}RxOrder'));
    
    #---- array of Index-----------------------------------------
    # ArrayOfIndex
    $this->__typedef['{http://medex.twinsun.com/xsd}ArrayOfIndex'] = 
      array(array('{http://medex.twinsun.com/xsd}Index'));
    
    
# set function
    $this->__dispatch_map['getPatient'] = 
      array('in' => array('PatientID' => 'string'),
	    'out' => array('Patient' => '{http://medex.twinsun.com/xsd}Patient'),
	    );
    $this->__dispatch_map['getKarte'] = 
      array('in' => array('PatientID' => 'string'),
	    'out' => array('Karte' => '{http://medex.twinsun.com/xsd}Karte'),
	    );
    $this->__dispatch_map['putKarte'] = 
      array('in' => array('Karte' => '{http://medex.twinsun.com/xsd}Karte'),
	    'out' => array('Status' => 'string'),
	    );
    $this->__dispatch_map['getArrayOfRxOrder'] = 
      array('in' => array('PatientID' => 'string'),
	    'out' => array('ArrayOfRxOrder' => '{http://medex.twinsun.com/xsd}ArrayOfRxOrder'),
	    );
    $this->__dispatch_map['getArrayOfIndex'] =
      array('in' => array('PatientID' => 'string'),
	    'out' => array('ArrayOfIndex' => '{http://medex.twinsun.com/xsd}ArrayOfIndex'),
	    );

    $this->__dispatch_map['putImage'] = 
      array('in' => array('Base64Image' => 'string'),
	    'out' => array('BlobMediaUrl' => 'string')
	    );

    
  }

  function __dispatch($methodname) {
    if (isset($this->__dispatch_map[$methodname]))
      return $this->__dispatch_map[$methodname];
    return NULL;
  }

  function getPatient($patient_id)
  {
    $pg_select_cmd1 = new PgCommand('select * from "´µ¼ÔÂæÄ¢" where "Superseded" IS NULL AND "´µ¼ÔID"=' . mx_db_sql_quote(np($patient_id)), $this->conn);
    $pg_da1 = new PgDataAdapter();
    $pg_da1->SelectCommand = $pg_select_cmd1;
    $ds = new MedexDataSet();
    $pg_da1->Fill($this->ds, "Patient");
    $pt_rows = &$this->ds->Tables["Patient"]->Select(new Condition("´µ¼ÔID", $patient_id));
    if(is_array($pt_rows) and count($pt_rows) > 0) {
      $p = new Patient($pt_rows[0]);
      return $p->__to_soap();
    }
    return null;
  }

  function getKarte($patient_id)
  {
    $pg_select_cmd1 = new PgCommand('select * from "´µ¼ÔÂæÄ¢" where "Superseded" IS NULL AND "´µ¼ÔID"=' . mx_db_sql_quote(np($patient_id)), $this->conn);
    $pg_select_cmd2 = new PgCommand('select * from karte_dotnet where "Superseded" IS NULL', $this->conn);
    $pg_da1 = new PgDataAdapter();
    $pg_da1->SelectCommand = $pg_select_cmd1;

    $pg_da2 = new PgDataAdapter();
    $pg_da2->SelectCommand = $pg_select_cmd2;

    $ds = new MedexDataSet();
    $pg_da1->Fill($this->ds, "Patient");
    $pg_da2->Fill($this->ds, "Karte");

    $pt_rows = &$this->ds->Tables["Patient"]->Select(new Condition("´µ¼ÔID", $patient_id));
    if(is_array($pt_rows) and count($pt_rows) > 0) {
      $kartes = $pt_rows[0]->GetChildRows("patient_2_karte");
      if(is_array($kartes) and count($kartes) > 0) {
	$mykarte  = new Karte($kartes[0]);
	return $mykarte->__to_soap();
      }
    }
    return null;
  }

  function putKarte($karte)
  {
    $db = mx_db_connect();
    $stmt = 'SELECT * FROM "´µ¼ÔÂæÄ¢" WHERE "ObjectID"=' . $karte->patient;
    $patient_row = mx_db_fetch_single($db, $stmt);
    $stmt = 'SELECT * FROM karte_dotnet WHERE "Superseded" IS NULL AND patient=' . $karte->patient;
    $current_row = mx_db_fetch_single($db, $stmt);

    if($current_row) {
      // has record already
      $current_id = $current_row['ID'];
      $current_xhtml = str_replace("'", "\\'", $current_row['xhtml']);
      $current_patient = $current_row['patient'];
      
      // copy the current row into history
      $stmt = <<<SQL
	INSERT INTO karte_dotnet ("Superseded", xhtml, patient) values (now(), '${current_xhtml}', ${current_patient});
        
SQL;
      pg_query($db, $stmt);

      $seq = mx_db_fetch_single($db, 'SELECT currval(\'"karte_dotnet_ID_seq"\') as id');
      $id = $seq['id'];
      $stmt = <<<SQL
	UPDATE karte_dotnet SET "ID"=${current_id} WHERE "ObjectID"=${id}
SQL;
      pg_query($db, $stmt);
    }

    // new record
    $xhtml = mb_convert_encoding(htmldecode($karte->xhtml), 'eucjp-win', 'utf-8');
    $xhtml = str_replace("'", "\\'", $xhtml);
    $patient = $karte->patient;
    
    if($current_row) {
      // update the current row
      $stmt = <<<SQL
	UPDATE karte_dotnet SET xhtml='${xhtml}' WHERE "Superseded" is null and "ObjectID"=${current_id}
SQL;
      pg_query($db, $stmt);
    }else{
      $stmt = <<<SQL
	INSERT INTO karte_dotnet (xhtml, patient) values ('${xhtml}', ${patient})
SQL;
      pg_query($db, $stmt);
    }
    return 'ok';
  }


  function getRxOrder($patient_id)
  {
    $pg_select_cmd1 = new PgCommand('select * from "´µ¼ÔÂæÄ¢" where "Superseded" IS NULL AND "´µ¼ÔID"=' . mx_db_sql_quote(np($patient_id)), $this->conn);
    $pg_select_cmd2 = new PgCommand('select * from "ÌôºÞ½èÊýäµ" where "Superseded" IS NULL', $this->conn);
    #$pg_select_cmd3 = new PgCommand('select * from "ÌôºÞ½èÊýäµÆâÍÆ"', $this->conn);
				
    $pg_da1 = new PgDataAdapter();
    $pg_da1->SelectCommand = $pg_select_cmd1;

    $pg_da2 = new PgDataAdapter();
    $pg_da2->SelectCommand = $pg_select_cmd2;

    #$pg_da3 = new PgDataAdapter();
    #$pg_da3->SelectCommand = $pg_select_cmd3;

    $ds = new MedexDataSet();
    $pg_da1->Fill($this->ds, "Patient");
    $pg_da2->Fill($this->ds, "RxOrder");
    #$pg_da3->Fill($this->ds, "RxOrderContent");

    $pt_rows = &$this->ds->Tables["Patient"]->Select(new Condition("´µ¼ÔID", $patient_id));
    $rx_orders = $pt_rows[0]->GetChildRows("patient_2_rx_order");
    $rx  = new RxOrder($rx_orders[0]);
    return $rx->__to_soap();
  }


  function getArrayOfRxOrder($patient_id) {
    $pg_select_cmd1 = new PgCommand('select * from "´µ¼ÔÂæÄ¢" where "Superseded" IS NULL AND "´µ¼ÔID"=' . mx_db_sql_quote(np($patient_id)), $this->conn);
    $pg_select_cmd2 = new PgCommand('select * from "ÌôºÞ½èÊýäµ" where "Superseded" IS NULL', $this->conn);
    #$pg_select_cmd3 = new PgCommand('select * from "ÌôºÞ½èÊýäµÆâÍÆ"', $this->conn);
				
    $pg_da1 = new PgDataAdapter();
    $pg_da1->SelectCommand = $pg_select_cmd1;

    $pg_da2 = new PgDataAdapter();
    $pg_da2->SelectCommand = $pg_select_cmd2;

    #$pg_da3 = new PgDataAdapter();
    #$pg_da3->SelectCommand = $pg_select_cmd3;

    $ds = new MedexDataSet();
    $pg_da1->Fill($this->ds, "Patient");
    $pg_da2->Fill($this->ds, "RxOrder");
    #$pg_da3->Fill($this->ds, "RxOrderContent");

    $pt_rows = &$this->ds->Tables["Patient"]->Select(new Condition("´µ¼ÔID", $patient_id));
    $rx_orders = $pt_rows[0]->GetChildRows("patient_2_rx_order");
    if(is_array($rx_orders) and count($rx_orders) > 0 )
      foreach($rx_orders as $rx_order) {
	$meds = get_meds($rx_order->Columns['ObjectID'],0);
	$rx_order->Columns['Print']  =  join("<<<br>>>", set_body($meds,0,0));
	$rx = new RxOrder($rx_order);
	$soap_msg[] = $rx->__to_soap();
      }
    return $soap_msg;
  }

  function getArrayOfIndex($patient_id) {
    $pg_select_cmd1 = new PgCommand('select * from "´µ¼ÔÂæÄ¢" where "Superseded" IS NULL AND "´µ¼ÔID"=' . mx_db_sql_quote(np($patient_id)), $this->conn);
    $pg_da1 = new PgDataAdapter();
    $pg_da1->SelectCommand = $pg_select_cmd1;
    $ds = new MedexDataSet();
    $pg_da1->Fill($this->ds, "Patient");
    $pt_rows = &$this->ds->Tables["Patient"]->Select(new Condition("´µ¼ÔID", $patient_id));
    if(is_array($pt_rows) and count($pt_rows) > 0) {
      $pt = $pt_rows[0];
    
      $oid = $pt->Columns['ObjectID'];
      $pid = $pt->Columns['´µ¼ÔID']; // same as $patient_id anyway
      $date_from = NULL;
      $date_to = NULL;
      $indecies = index_pt_collect(array(array($oid, $pid)),
				   $date_from,
				   $date_to,
				   'orders');
      foreach($indecies as $index) {
	$idx = new Index($index);
	$soap_msg[] = $idx->__to_soap();
      }
      return $soap_msg;
    }
    return null;
  }

  function putImage($base64str) {
    global  $_mx_site_url;
    $data = base64_decode($base64str);
    $db = mx_db_connect();
    $type = 'image/jpeg';
    $bid = mx_db_insert_blobmedia(&$db, $type, $data);
    $id = mx_db_insert_extdocument($db, 'JPEG²èÁü', $bid, $pt=NULL, $comment=NULL);
    if($id)
      return sprintf("%s/blobmedia.php/${id}/image${id}.jpg", $_mx_site_url);
    return null;
  }
}

/*
$patient_id = '0000001212';
$svc = new SOAP_Medex_Server();
$ret = $svc->getArrayOfIndex($patient_id);
print $ret;
*/
?>
