<?php

print '<html><head><meta http-equiv="content-type"
       content="text/html; charset=euc-jp"></head><body>';
if (!$_REQUEST['pid']) {
  print '<form method="POST">
   ´µ¼Ô£É£Ä<input type="text" name="pid"> <br>
   <input type="submit" name="data1" value="¥Ç¡¼¥¿£±Å¾Á÷">
   <input type="submit" name="data2" value="¥Ç¡¼¥¿£²Å¾Á÷">
</form>';
}
else {
  if ($_REQUEST['data1']) {
    $data = array(
		   array('JPN000000000000','2', '01', '20060225','1','1','1','700','70','170015210','1','0','20060225'),
		   array('JPN000000000000','2', '01', '20060225','1','1','2','700','70','700710000','2','0','20060225'));
  } elseif ($_REQUEST['data2']) {
    $data = array(
	       array('JPN000000000000','2', '01', '20060225','1','1','1','700','70','170001910','1','0','20060225'),
	       array('JPN000000000000','2', '01', '20060225','1','1','2','700','70','170000410','1','0','20060225'),
	       array('JPN000000000000','2', '01', '20060225','1','1','3','700','70','700710000','1','0','20060225'),
	       array('JPN000000000000','2', '01', '20060225','1','1','4','700','70','700780000','1','0','20060225'),
	       array('JPN000000000000','2', '01', '20060225','1','1','5','700','70','700840000','1','0','20060225'),
	       array('JPN000000000000','2', '01', '20060225','1','1','6','700','70','170000210','1','0','20060225')
	       );
  }


  $con = pg_connect("host=localhost port=5432 dbname=orca user=orca")
    or die('pg_connect error '.pg_last_error());
  pg_set_client_encoding("EUC-JP");
  
  if (!$_REQUEST['pid']) return;
  $pid_sql = sprintf ("select ptid 
       from tbl_ptnum where ptnum = '%05d'",$_REQUEST['pid']);
  $pid = pg_fetch_all(pg_query($con,$pid_sql));
  
  if (!$pid[0]['ptid']) {
    print "»ØÄê¤·¤¿´µ¼Ô¤¬£Ï£Ò£Ã£Á¤ËÅÐÏ¿¤µ¤ì¤Æ¤¤¤Þ¤»¤ó¡£";
    return;
  }

  $index=0;
  if (!$data) return;
  while ($index < count($data)) {
    $sql = sprintf("insert into tbl_wksryact 
         (hospid, nyugaikbn, ptid, sryka, sryymd,
          hkncombi, zainum, rennum, srysyukbn, srykbn,
          srycd_1, srysuryo_1, srykaisu_1, termid, creymd)
          values ('%s','%s','%s','%s','%s','%s','%s','%s',
          '%s','%s','%s','%s','%s','WKSRYACT','%s');",
	      $data[$index][0],
	      $data[$index][1],
	      $pid[0]['ptid'],
	      $data[$index][2],
	      $data[$index][3],
	      $data[$index][4],
	      $data[$index][5],
	      $data[$index][6],
	      $data[$index][7],
	      $data[$index][8],
	      $data[$index][9],
	      $data[$index][10],
	      $data[$index][11],
	      $data[$index][12]);
    $index++;
    $res = pg_query($con,$sql)
      or die('pg_query => '. pg_last_error());
   
  }
  pg_close($con);
  print "¥Ç¡¼¥¿¤ò£Ï£Ò£Ã£Á¤ËÁ÷¿®¤·¤Þ¤·¤¿¡£\n";
}
?>