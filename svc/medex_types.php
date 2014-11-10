<?php // -*- mode: php; coding: euc-japan -*-
/**
 * This is a data type that is used in SOAP Interop testing, but is here as an
 * example of using complex types.  When the class is deserialized from a SOAP
 * message, it's constructor IS NOT CALLED!  So your type classes need to
 * behave in a way that will work with that.
 *
 * Some types may need more explicit serialization for SOAP.  The __to_soap
 * function allows you to be very explicit in building the SOAP_Value
 * structures.  The soap library does not call this directly, you would call
 * it from your soap server class, echoStruct in the server class is an
 * example of doing this.
 */
#require_once 'medex_ds.php';
#require_once 'medex_pg_dp.php';
require_once 'medex_da.php';
require_once 'SOAP/Value.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ord_common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/print.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/index-pt.php';


class Patient {

  function Patient(&$pdr)
  {
    // assert $pdr = Patient DataRow
    if(!is_null($pdr))
      $this->pdr = $pdr;
  }
    
  function &__to_soap($name = 'Patient', $header = false,
		      $mustUnderstand = 0,
		      $actor = 'http://schemas.xmlsoap.org/soap/actor/next')
  {
    $ds = new MedexDataSet();

    foreach($ds->Tables['Patient']->Columns as $k => $v) {
      $tp = $v->Type;
      $nm = $v->Alias ? $v->Alias : $k;
      if($tp == 'string')
	$val = htmlspecialchars($val, ENT_QUOTES);
      $val = mb_convert_encoding($this->pdr->Columns[$k], "UTF-8", "EUC-JP");
      if(is_null($val))
	$val = '';
      $inner[] =& new SOAP_Value($nm, $tp, $val);
    }

    if ($header) {
      $value =& new SOAP_Header($name,'{http://medex.twinsun.com/xsd}Patient',$inner,$mustUnderstand,$actor);
    } else {
      $value =& new SOAP_Value($name,'{http://medex.twinsun.com/xsd}Patient',$inner);
    }
    return $value;
  } 
}

class Karte {

  function Karte($kr=NULL)
  {
    // assert $kr = Karte DataRow
    if(!is_null($kr))
      $this->kr = $kr;
  }
    
  function &__to_soap($name = 'Karte', $header = false,
		      $mustUnderstand = 0,
		      $actor = 'http://schemas.xmlsoap.org/soap/actor/next')
  {
    $ds = new MedexDataSet();

    foreach($ds->Tables['Karte']->Columns as $k => $v) {
      $tp = $v->Type;
      $nm = $v->Alias ? $v->Alias : $k;
      if($tp == 'string')
	$val = htmlspecialchars($val, ENT_QUOTES);
      $val = mb_convert_encoding($this->kr->Columns[$k], "UTF-8", "EUC-JP");
      if(is_null($val))
	$val = '';
      $inner[] =& new SOAP_Value($nm, $tp, $val);
    }

    if ($header) {
      $value =& new SOAP_Header($name,'{http://medex.twinsun.com/xsd}Karte',$inner,$mustUnderstand,$actor);
    } else {
      $value =& new SOAP_Value($name,'{http://medex.twinsun.com/xsd}Karte',$inner);
    }
    return $value;
  }

  function to_string() {
  }
}

class RxOrder {

  function RxOrder(&$pdr)
  {
    // assert $pdr = RxOrder DataRow
    if(!is_null($pdr))
      $this->pdr = $pdr;
  }
    
  function &__to_soap($name = 'RxOrder', $header = false,
		      $mustUnderstand = 0,
		      $actor = 'http://schemas.xmlsoap.org/soap/actor/next')
  {
    $ds = new MedexDataSet();

    // header table
    foreach($ds->Tables['RxOrder']->Columns as $k => $v) {
      $tp = $v->Type;
      $nm = $v->Alias ? $v->Alias : $k;
      if($tp == 'string')
	$val = htmlspecialchars($val, ENT_QUOTES);
      $val = mb_convert_encoding($this->pdr->Columns[$k], "UTF-8", "EUC-JP");
      if(is_null($val))
	$val = '';
      if($k == 'Print')
	$val = htmlspecialchars($val, ENT_QUOTES);
      $inner[] =& new SOAP_Value($nm, $tp, $val);
    }
    // computed field
    $val = mb_convert_encoding($this->Print, "UTF-8", "EUC-JP");
    $inner[] =& new SOAP_Value('Print', 'string', $val);

    if ($header) {
      $value =& new SOAP_Header($name,'{http://medex.twinsun.com/xsd}RxOrder',$inner,$mustUnderstand,$actor);
    } else {
      $value =& new SOAP_Value($name,'{http://medex.twinsun.com/xsd}RxOrder',$inner);
    }
    return $value;
  } 
}

class Index {
  function Index(&$idx)
  {
    if(!is_null($idx))
      $this->idx = $idx;
  }
  
  function &__to_soap($name = 'Index', $header = false,
		      $mustUnderstand = 0,
		      $actor = 'http://schemas.xmlsoap.org/soap/actor/next')
  {
    foreach(index_pt_soap_type() as $nm => $tp) {
      if($tp == 'string')
	$val = htmlspecialchars($val, ENT_QUOTES);
      $val = mb_convert_encoding($this->idx[$nm], 'UTF-8', 'EUC-JP');
      if(is_null($val))
	$val = '';
      $inner[] =& new SOAP_Value($nm, $tp, $val);
    }
     
    if ($header) {
      $value =& new SOAP_Header($name,'{http://medex.twinsun.com/xsd}Index',$inner,$mustUnderstand,$actor);
    } else {
      $value =& new SOAP_Value($name,'{http://medex.twinsun.com/xsd}Index',$inner);
    }
    return $value;
  } 
}
/*
$row = array('生年月日'=>'2007-01-01');
$p = new Patient($row);
$x = $p->__to_soap();
var_dump($x);
*/
?>