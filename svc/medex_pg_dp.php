<?php
require_once 'medex_dp.php';
// Medex Postgresql Data Provider

class PgConnection extends IDbConnection {

  function &PgConnection($database, $user=NULL, $password=NULL,
			$host=NULL, $port=NULL, $encoding='euc-jp') {
    $this->host = $host;
    $this->port = $port;
    $this->database = $database;
    $this->user = $user;
    $this->password = $password;
    $this->encoding = $encoding;
    $this->Connection = NULL;
  }

  function Open() {
    $conn_str = '';

    if($this->host) {
      $conn_str .= sprintf(" host=%s", $this->host);
      if($this->port)
	$conn_str .= sprintf(" port=%s", $this->port);
    }
    if($this->database)
      $conn_str .= sprintf(" dbname=%s", $this->database);
    if($this->port)
      $conn_str .= sprintf(" port=%s", $this->port);
    if($this->user)
      $conn_str .= sprintf(" user=%s", $this->user);
    if($this->password)
      $conn_str .= sprintf(" password=%s", $this->password);

    $this->ConnectionString = $conn_str;
    $this->Connection = pg_connect($this->ConnectionString);
    if(!is_null($this->Connection))
      pg_query(sprintf("set client_encoding to '%s'", $this->encoding));
  }

  function Close() {
    pg_close($this->Connection);
  }
}

class PgDataReader extends IDataReader {
  function PgDataReader(&$sth) {
    $this->StatementHandler = &$sth;
  }

  function Read() {
    $tuple = pg_fetch_array(&$this->StatementHandler, NULL, PGSQL_ASSOC);
    if(!$tuple)
      return NULL;
    return new DataRow($tuple);
  }
}

class PgCommand extends IDbCommand {
  function PgCommand($query,&$conn) {
    $this->query = $query;
    $this->conn = &$conn;
  }

  function ExecuteReader() {
    $sth = pg_query($this->conn->Connection, $this->query);
    if(!$sth) {
      print "Connection: ";
      print $this->conn->Connection;
      print "Query: ";
      print $this->query;
      return NULL;
    }
    return new PgDataReader($sth);
  }
}


class PgDataAdapter extends IDbDataAdapter {
  var $SelectCommand = NULL;
  var $InsertCommand = NULL;
  var $UpdateCommand = NULL;
  var $DeleteCommand = NULL;

  function PgDataAdapter() {
    $this->TableMapping = array();
  }

  function SetFillCommand(&$cmd) {
    $this->SelectCommand = &$cmd;
  }


  function Fill(&$ds, $tbl=NULL) {
    if(!$this->SelectCommand->conn->Connection)
      die("Connection not Open");

    #NEEDSWORK:  do not create datatable implicitly
    #if($tbl and is_null($ds->Tables[$tbl])) {
    #  $ds->AddTable(new DataTable($tbl));
    #}

    $data_reader = $this->SelectCommand->ExecuteReader();
    while($ds->Tables[$tbl]->AddRow($data_reader->Read()));
  }

  function Update($ds, $tbl) {
  }
} 

?>