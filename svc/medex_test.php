<?php

require 'medex_ds.php';
require 'medex_pg_dp.php';


$pg_conn = new PgConnection('kenji_test4', 'kenji', '', 'localhost', '5433');
$pg_select_cmd1 = new PgCommand('select * from "������Ģ"', $pg_conn);
$pg_select_cmd2 = new PgCommand('select * from "���޽����"', $pg_conn);
				
$pg_da1 = new PgDataAdapter();
$pg_da1->SelectCommand = $pg_select_cmd1;

$pg_da2 = new PgDataAdapter();
$pg_da2->SelectCommand = $pg_select_cmd2;

$pg_conn->Open();
$ds = new DataSet();
$pg_da1->Fill($ds, "patients");
$pg_da2->Fill($ds, "rx_orders");

$dr = new DataRelation("patient_rx_order",
		       $ds->Tables["patients"]->Columns["ObjectID"],
		       $ds->Tables["rx_orders"]->Columns["����"]
		       );
$ds->AddRelation($dr);
$parent_rows = $ds->Tables["patients"]->Select(new Condition("����ID", "0001212     "));
$parent = $parent_rows[0]; // datarow

$child_rows = $parent->GetChildRows("patient_rx_order");

foreach($child_rows as $row) {
  foreach($row->Columns as $k => $v) 
    printf("%s=%s\n", $k, $v);
}
$pg_conn->Close();

?>