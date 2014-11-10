<?php
// in-memory cache of data

class PrimaryKey {
  function PrimaryKey($cols) {
    $this->Columns = $cols;
  }

  function GetHash(&$dr) {
    $cond = array();
    if(is_array($this->Columns)) {
      foreach($this->Columns as $col)
	$cond[] = sha1($col->ColumnName . $dr->Columns[$col->ColumnName]);
      return implode("", $cond);
    }else
      return sha1($col->ColumnName . $dr->Columns[$col->ColumnName]);
  }
}

class DataRelation {
  function DataRelation($name, &$parent, &$child) {
    // assert $name == string
    // assert instanceof($parent) == DataColumn
    // assert instanceof($child) == DataColumn
    if(is_null($parent))
      die(sprintf("DataRelation %s cannot be instantiated. Parent DataColumn is null", $name));
    if(is_null($child))
      die(sprintf("DataRelation %s cannot be instantiated. Child DataColumn is null", $name));

    $this->RelationName = $name;
    $this->Parent = &$parent;
    $this->Child = &$child;
  }
}

class DataColumn {
  function DataColumn(&$table, $column_name, $type, $alias=NULL) {
    $this->Table = &$table;
    $this->ColumnName = $column_name;
    $this->Type = $type;
    $this->Alias = $alias;
  }
}

class DataRow {
  function DataRow($tuple) { //HACK
    $this->DataTable = NULL;
    $this->Columns = array();
    // can't i use attribute??
    foreach($tuple as $k=>$v) {
      $this->Columns[$k] = $v;
    }
  }

  function GetChildRows($rel_name) {

    $parent_dc = & $this->DataTable->DataSet->Relations[$rel_name]->Parent;
    if(is_null($parent_dc))
      die( sprintf("DataRelation %s not found for parent table %s", $rel_name, $this->DataTable->TableName));
    $child_dc = & $this->DataTable->DataSet->Relations[$rel_name]->Child;
    if(is_null($child_dc))
      die( sprintf("DataRelation %s not found for child table %s", $rel_name, $this->DataTable->TableName));
    $cond = new Condition($child_dc->ColumnName, $this->Columns[$parent_dc->ColumnName]);
    $dt = $child_dc->Table->Select(new Condition($child_dc->ColumnName,
						 $this->Columns[$parent_dc->ColumnName]));
    return( $dt );
  }
}

class Condition {
  function Condition($col, $val) {
    $this->col = $col;
    $this->val = $val;
  }

  function Match($dr) {
    #print "Column " . $this->col . "  '" . $dr->Columns[$this->col] . "'  '" .$this->val ."'\n";
    #print (trim($dr->Columns[$this->col]) == trim($this->val));
    #print "\n";
    return( trim($dr->Columns[$this->col]) == trim($this->val));
  }
}

class DataTable {
  function DataTable($name) {
    $this->TableName = $name;
    $this->DataSet = NULL;
    $this->Columns = array(); // collection of DataColumn
    $this->Rows = array();    // collection of DataRow
  }

  function AddColumn(&$dc) {
    // assert $dc == DataColumn
    $this->Columns[$dc->ColumnName] = &$dc;
  }

  function AddRow(&$dr) {
    if(!$dr)
      return $dr;

    // cleansing DataRow
    foreach($dr->Columns as $k => $v)
      if(! array_key_exists($k, $this->Columns))
	unset($dr->Columns[$k]);

    // assert $dr = DataRow
    #NEEDSWORK:  shouldn't add unknown columns blindly
    #foreach($dr->Columns as $col_name=>$col_val)
    #  if(is_null($this->Columns[$col_name]))
    #	$this->AddColumn(new DataColumn($this, $col_name, 'HACK'));
    $dr->DataTable = &$this;
    if($this->PrimaryKey) {
      $hash = $this->PrimaryKey->GetHash($dr);
      if(!is_null($this->Rows[$hash]))
	die(sprintf("Duplicate Primary Key on %s\n", $this->TableName));
      $this->Rows[] = $dr; // copy
      $this->Index[$hash] = $this->Rows[count($this->Rows) -1 ];
    }else
      $this->Rows[] = $dr; // copy
    return($dr);
  }

  function SetPrimaryKey(&$pk) {
    $this->PrimaryKey = &$pk;
    #NEEDSWORK:  Do I need to reclculate hash for Rows?
  }

  function &Select(&$co) {
    // assert $co == Condition
    $ret = NULL;
    foreach($this->Rows as $row) {
      if($co->Match($row)) {
	$ret[] = $row;  //return a copy, hence no &
      }
    }
    return $ret;
  }

  function &SelectByPrimaryKey(&$dr) {
    // assert $v = array() ?
    return $this->Rows[$this->PrimaryKey->GetHash($dr)];
  }

  function GetColumnTypes() {
    $a = array();
    foreach($this->Columns as $k => $v) {
       $name = $v->Alias ? $v->Alias : $k;
       $a[$name] = $v->Type;
    }
    return $a;
  }
}

class DataSet {
  function DataSet($name=NULL) {
    $this->DataSetName = $name;
    $this->Relations = array();  // collection of DataRelation
    $this->Tables = array();    // collection of DataTable
  }

  function AddTable(&$dt) {
    $dt->DataSet = &$this;
    $this->Tables[$dt->TableName] = &$dt;
  }
    
  function AddRelation(&$dr) {
    //assert $dr == DataRelation
    $this->Relations[$dr->RelationName] = &$dr;
  }
  function ToString() {
    return sprintf("DataSetName=%s");
  }
}

?>
