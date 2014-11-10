<?php
require 'medex_pg_dp.php';
require 'medex_ds.php';
#----------- ������Ģ -----------------------------
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
    $this->AddColumn(new DataColumn($this, "����ID", "string", "PatientID")); 
    $this->AddColumn(new DataColumn($this, "��", "string", "LastName")); 
    $this->AddColumn(new DataColumn($this, "̾", "string", "FirstName")); 
    #$this->AddColumn(new DataColumn($this, "�եꥬ��", "string", "Kana")); 
    $this->AddColumn(new DataColumn($this, "����", "string", "Sex")); 
    #$this->AddColumn(new DataColumn($this, "������", "string", "Kikite")); 
    $this->AddColumn(new DataColumn($this, "��ǯ����", "date", "DOB")); 
    $this->AddColumn(new DataColumn($this, "����0", "string", "Addr0")); 
    $this->AddColumn(new DataColumn($this, "����1", "string", "Addr1")); 
    $this->AddColumn(new DataColumn($this, "����2", "string", "Addr2")); 
    $this->AddColumn(new DataColumn($this, "����3", "string", "Addr3")); 
    $this->AddColumn(new DataColumn($this, "����4", "string", "Addr4")); 
    $this->AddColumn(new DataColumn($this, "��������", "string", "Phone")); 
    $this->AddColumn(new DataColumn($this, "��������", "string", "Cell")); 
    $this->AddColumn(new DataColumn($this, "������ʬ", "string", "IO")); 
    /*
    $this->AddColumn(new DataColumn($this, "�ݸ����ֹ�", "string")); 
    $this->AddColumn(new DataColumn($this, "���ݸ���", "string")); 
    $this->AddColumn(new DataColumn($this, "���ݸ��Լ�Ģ�ε���", "string")); 
    $this->AddColumn(new DataColumn($this, "���ݸ��Լ�Ģ���ֹ�", "string")); 
    $this->AddColumn(new DataColumn($this, "������ô���ֹ�", "string")); 
    $this->AddColumn(new DataColumn($this, "������ô���Ťμ�����ֹ�", "string")); 
    $this->AddColumn(new DataColumn($this, "ȯ����", "date")); 
    $this->AddColumn(new DataColumn($this, "������", "date")); 
    $this->AddColumn(new DataColumn($this, "�ౡͽ����", "date")); 
    $this->AddColumn(new DataColumn($this, "�ౡͽ�ꡦ����", "string")); 
    $this->AddColumn(new DataColumn($this, "��˴��", "date")); 
    $this->AddColumn(new DataColumn($this, "����", "string")); 
    $this->AddColumn(new DataColumn($this, "������", "string")); 
    $this->AddColumn(new DataColumn($this, "���Ū�԰���", "string")); 
    $this->AddColumn(new DataColumn($this, "��˾����", "string")); 
    $this->AddColumn(new DataColumn($this, "��շ����£ϼ�", "string")); 
    $this->AddColumn(new DataColumn($this, "��շ��ң輰", "string")); 
    $this->AddColumn(new DataColumn($this, "�ȣ£󹳸�", "string")); 
    $this->AddColumn(new DataColumn($this, "����륮��", "string")); 
    $this->AddColumn(new DataColumn($this, "������", "string")); 
    $this->AddColumn(new DataColumn($this, "Ʃ�ϴ��ԥե饰", "string")); 
    $this->AddColumn(new DataColumn($this, "��̳��̾", "string")); 
    $this->AddColumn(new DataColumn($this, "��̳��͹���ֹ�", "string")); 
    $this->AddColumn(new DataColumn($this, "��̳�轻��", "string")); 
    $this->AddColumn(new DataColumn($this, "��̳�������ֹ�", "string")); 
    $this->AddColumn(new DataColumn($this, "������̾", "string")); 
    $this->AddColumn(new DataColumn($this, "������͹���ֹ�", "string")); 
    $this->AddColumn(new DataColumn($this, "�����轻��", "string")); 
    $this->AddColumn(new DataColumn($this, "�����������ֹ�", "string")); 
    $this->AddColumn(new DataColumn($this, "����ͭ������", "date")); 
    $this->AddColumn(new DataColumn($this, "������ô���ֹ�2", "string")); 
    $this->AddColumn(new DataColumn($this, "������ô���Ťμ�����ֹ�2", "string")); 
    $this->AddColumn(new DataColumn($this, "����ͭ������2", "date")); 
    $this->AddColumn(new DataColumn($this, "������ô���ֹ�3", "string")); 
    $this->AddColumn(new DataColumn($this, "������ô���Ťμ�����ֹ�3", "string")); 
    $this->AddColumn(new DataColumn($this, "����ͭ������3", "date"));  
    */
  }
}

#----------- ���޽���� ----------------------------
class RxOrderTable extends DataTable {
  function RxOrderTable() {
    DataTable::DataTable("RxOrder");
    $c0 = new DataColumn($this, "ObjectID", "int");
    $c1 = new DataColumn($this, "Superseded", "timestamp");
    $c2 = new DataColumn($this, "����", "int", "PatientObjectID");
    $c3 = new DataColumn($this, "����ǯ����", "date", "RxOrderDate");
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
    $c1 = new DataColumn($this, "���޽����", "int", "RxOrderObjectID");
    $this->AddColumn($c0);
    $this->AddColumn($c1);
  }
}

#------------ ����� --------------------------------
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
			$this->Tables["RxOrderContent"]->Columns["���޽����"]
			));

    $this->AddRelation(new DataRelation
		       ("patient_2_rx_order",
			$this->Tables["Patient"]->Columns["ObjectID"],
			$this->Tables["RxOrder"]->Columns["����"]
			));
    $this->AddRelation(new DataRelation
		       ("patient_2_karte",
			$this->Tables["Patient"]->Columns["ObjectID"],
			$this->Tables["Karte"]->Columns["patient"]
			));
  }
}
?>