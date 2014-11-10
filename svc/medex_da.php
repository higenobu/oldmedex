<?php
require 'medex_pg_dp.php';
require 'medex_ds.php';
#----------- 患者台帳 -----------------------------
class PatientTable extends DataTable {
  function PatientTable() {
    DataTable::DataTable("Patient");  // pick a convenient name
    $c0 = new DataColumn($this,"ObjectID", "int");
    $c1 = new DataColumn($this, "Superseded", "timestamp");
    $this->AddColumn($c0);
    $this->AddColumn($c1);
    $this->SetPrimaryKey(new PrimaryKey(array($c0, $c1)));

    #$this->AddColumn(new DataColumn($this, "ID","int")); 
    #$this->AddColumn(new DataColumn($this, "CreatedBy", "int")); 
    $this->AddColumn(new DataColumn($this, "患者ID", "string", "PatientID")); 
    $this->AddColumn(new DataColumn($this, "姓", "string", "LastName")); 
    $this->AddColumn(new DataColumn($this, "名", "string", "FirstName")); 
    #$this->AddColumn(new DataColumn($this, "フリガナ", "string", "Kana")); 
    $this->AddColumn(new DataColumn($this, "性別", "string", "Sex")); 
    #$this->AddColumn(new DataColumn($this, "利き手", "string", "Kikite")); 
    $this->AddColumn(new DataColumn($this, "生年月日", "date", "DOB")); 
    $this->AddColumn(new DataColumn($this, "住所0", "string", "Addr0")); 
    $this->AddColumn(new DataColumn($this, "住所1", "string", "Addr1")); 
    $this->AddColumn(new DataColumn($this, "住所2", "string", "Addr2")); 
    $this->AddColumn(new DataColumn($this, "住所3", "string", "Addr3")); 
    $this->AddColumn(new DataColumn($this, "住所4", "string", "Addr4")); 
    $this->AddColumn(new DataColumn($this, "加入電話", "string", "Phone")); 
    $this->AddColumn(new DataColumn($this, "携帯電話", "string", "Cell")); 
    $this->AddColumn(new DataColumn($this, "入外区分", "string", "IO")); 
    /*
    $this->AddColumn(new DataColumn($this, "保険者番号", "string")); 
    $this->AddColumn(new DataColumn($this, "被保険者", "string")); 
    $this->AddColumn(new DataColumn($this, "被保険者手帳の記号", "string")); 
    $this->AddColumn(new DataColumn($this, "被保険者手帳の番号", "string")); 
    $this->AddColumn(new DataColumn($this, "公費負担者番号", "string")); 
    $this->AddColumn(new DataColumn($this, "公費負担医療の受給者番号", "string")); 
    $this->AddColumn(new DataColumn($this, "発症日", "date")); 
    $this->AddColumn(new DataColumn($this, "入院日", "date")); 
    $this->AddColumn(new DataColumn($this, "退院予定日", "date")); 
    $this->AddColumn(new DataColumn($this, "退院予定・見込", "string")); 
    $this->AddColumn(new DataColumn($this, "死亡日", "date")); 
    $this->AddColumn(new DataColumn($this, "備考", "string")); 
    $this->AddColumn(new DataColumn($this, "回復期", "string")); 
    $this->AddColumn(new DataColumn($this, "医学的不安定", "string")); 
    $this->AddColumn(new DataColumn($this, "希望病棟", "string")); 
    $this->AddColumn(new DataColumn($this, "血液型ＡＢＯ式", "string")); 
    $this->AddColumn(new DataColumn($this, "血液型Ｒｈ式", "string")); 
    $this->AddColumn(new DataColumn($this, "ＨＢｓ抗原", "string")); 
    $this->AddColumn(new DataColumn($this, "アレルギー", "string")); 
    $this->AddColumn(new DataColumn($this, "感染症", "string")); 
    $this->AddColumn(new DataColumn($this, "透析患者フラグ", "string")); 
    $this->AddColumn(new DataColumn($this, "勤務先名", "string")); 
    $this->AddColumn(new DataColumn($this, "勤務先郵便番号", "string")); 
    $this->AddColumn(new DataColumn($this, "勤務先住所", "string")); 
    $this->AddColumn(new DataColumn($this, "勤務先電話番号", "string")); 
    $this->AddColumn(new DataColumn($this, "請求先名", "string")); 
    $this->AddColumn(new DataColumn($this, "請求先郵便番号", "string")); 
    $this->AddColumn(new DataColumn($this, "請求先住所", "string")); 
    $this->AddColumn(new DataColumn($this, "請求先電話番号", "string")); 
    $this->AddColumn(new DataColumn($this, "公費有効期限", "date")); 
    $this->AddColumn(new DataColumn($this, "公費負担者番号2", "string")); 
    $this->AddColumn(new DataColumn($this, "公費負担医療の受給者番号2", "string")); 
    $this->AddColumn(new DataColumn($this, "公費有効期限2", "date")); 
    $this->AddColumn(new DataColumn($this, "公費負担者番号3", "string")); 
    $this->AddColumn(new DataColumn($this, "公費負担医療の受給者番号3", "string")); 
    $this->AddColumn(new DataColumn($this, "公費有効期限3", "date"));  
    */
  }
}

#----------- 薬剤処方箋 ----------------------------
class RxOrderTable extends DataTable {
  function RxOrderTable() {
    DataTable::DataTable("RxOrder");
    $c0 = new DataColumn($this, "ObjectID", "int");
    $c1 = new DataColumn($this, "Superseded", "timestamp");
    $c2 = new DataColumn($this, "患者", "int", "PatientObjectID");
    $c3 = new DataColumn($this, "処方年月日", "date", "RxOrderDate");
    $c4 = new DataColumn($this, "Print", "string", "Print");
    $this->AddColumn($c0);
    $this->AddColumn($c1);
    $this->AddColumn($c2);
    $this->AddColumn($c3);
    $this->AddColumn($c4);
    $this->SetPrimaryKey(new PrimaryKey(array($c0, $c1)));
  }
}

class RxOrderContentTable extends DataTable {
  function RxOrderContentTable() {
    DataTable::DataTable("RxOrderContent");
    $c0 = new DataColumn($this, "ObjectID", "int");
    $c1 = new DataColumn($this, "薬剤処方箋", "int", "RxOrderObjectID");
    $this->AddColumn($c0);
    $this->AddColumn($c1);
  }
}

#------------ カルテ --------------------------------
class KarteTable extends DataTable {
  function KarteTable() {
    DataTable::DataTable("Karte");
    $c0 = new DataColumn($this, "ObjectID", "int");
    $c1 = new DataColumn($this, "Superseded", "timestamp");
    $c2 = new DataColumn($this, "patient", "int");
    $c3 = new DataColumn($this, "xhtml", "string");
    $this->AddColumn($c0);
    $this->AddColumn($c1);
    $this->AddColumn($c2);
    $this->AddColumn($c3);
    $this->SetPrimaryKey(new PrimaryKey(array($c0)));
  }
}

class MedexDataSet extends DataSet {
  function MedexDataSet() {
    DataSet::DataSet();
    // create tables
    $this->AddTable(new PatientTable());
    $this->AddTable(new RxOrderTable());
    $this->AddTable(new RxOrderContentTable());
    $this->AddTable(new KarteTable());
    $this->AddRelation(new DataRelation
		       ("rx_order_2_rx_order_content",
			$this->Tables["RxOrder"]->Columns["ObjectID"],
			$this->Tables["RxOrderContent"]->Columns["薬剤処方箋"]
			));

    $this->AddRelation(new DataRelation
		       ("patient_2_rx_order",
			$this->Tables["Patient"]->Columns["ObjectID"],
			$this->Tables["RxOrder"]->Columns["患者"]
			));
    $this->AddRelation(new DataRelation
		       ("patient_2_karte",
			$this->Tables["Patient"]->Columns["ObjectID"],
			$this->Tables["Karte"]->Columns["patient"]
			));
  }
}
?>