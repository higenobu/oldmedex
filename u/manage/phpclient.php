<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja"><head><meta http-equiv="content-type" content="text/html; charset=euc-jp"><link rel="shortcut icon" href="/resource/8a783773/favicon.ico">
<script language="JavaScript" src="/resource/8a783773/AC_OETags.js"></script>
<script language="JavaScript" src="/resource/8a783773/mx.js"></script>
<script language="JavaScript" src="/resource/8a783773/PopupWindow.js"></script>
<script language="JavaScript" src="/resource/8a783773/date.js"></script>
<script language="JavaScript" src="/resource/8a783773/CalendarPopup.js"></script>
<script language="JavaScript" src="/resource/8a783773/AnchorPosition.js"></script>
<script language="JavaScript" src="/resource/8a783773/MochiKit.js"></script>
<script language="JavaScript" src="/resource/8a783773/post_code.js"></script>

<script language="JavaScript" src="/resource/8a783773/inc_search_sjis.js"></script>
<script language="JavaScript" src="/resource/8a783773/vocabulary.js"></script>
<script language="JavaScript" src="/resource/8a783773/apptcal.js"></script>
<script language="JavaScript" src="/resource/8a783773/drawapp-js.php"></script>
<link rel="stylesheet" href="/resource/8a783773/mxstyle.css" />
<link rel="stylesheet" href="/resource/8a783773/calend.css" />
<link rel="stylesheet" href="/resource/8a783773/qxr.css" />


		<title>Card</title>
	</head>
	<body>
<a href="index.php?tab=1">Go to Main</a>
<br>

<a href="phpclient-app.php?tab=1">Start again</a>
<br>
<?php 

 include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';  

//pg_set_client_encoding('EUC_JP');

    

  function check_len($str) {
    
   for ($i = 0; $i < 17 ; $i++){
        $c = ord($str[$i]);
        print $c;
	print "A";
	if ( $c==0) $i=$i+100;
        }
  
    return $i;
}


  function del_sp($str,$sps) {
    $k=0;
	$strr='';
   for ($i = 0; $i < 17 ; $i++){
        $c = ord($str[$i]);
        $cn=ord($str[$i+1]);
	if ( $c==129 && $cn==64) {
	$i++;
	$strr=$strr.$sps;
	
	
	
        }
	else {
	$strr =$strr.$str[$i];
	
}
  }

    return $strr;
} // end of check_utf8

function check_utf8($str) {
    $len = strlen($str);
    for($i = 0; $i < $len; $i++){
        $c = ord($str[$i]);
        if ($c > 128) {
            if (($c > 247)) return false;
            elseif ($c > 239) $bytes = 4;
            elseif ($c > 223) $bytes = 3;
            elseif ($c > 191) $bytes = 2;
            else return false;
            if (($i + $bytes) > $len) return false;
            while ($bytes > 1) {
                $i++;
                $b = ord($str[$i]);
                if ($b < 128 || $b > 191) return false;
                $bytes--;
            }
        }
    }
    return true;
} // end of check_utf8

function to_wareki($ymd)
{
    //綛贋??ャ??????????腟??
// $ymd = sprintf("%02d%02d%02d", $y, $m, $d);

$y=substr($ymd,0,4);
$m=substr($ymd,4,2);
$d=substr($ymd,6,2);
//$ymd = sprintf("%04d%02d%02d", $y, $m, $d);
    if ($ymd <= "19120729") {
        $gg = "M";
        $yy = $y - 1867;
    } elseif ($ymd >= "19120730" && $ymd <= "19261224") {
        $gg = "T";
        $yy = $y - 1911;
    } elseif ($ymd >= "19261225" && $ymd <= "19890107") {
        $gg = "S";
        $yy = $y - 1925;
    } elseif ($ymd >= "19890108") {
        $gg = "H";

        $yy = $y - 1988;
    }
if ($yy<10) {$yy="0".$yy;}
    $wareki = $gg.$yy.$m.$d;
    return $wareki;
}





function zeropad($data)
{

$v=$data;
 $leng= strlen($data );
 $ksps="0";




	 
		$cnt = 8-$leng;
		for ($i = 0; $i < $cnt; $i++) {

			$v = $ksps.$v;
		}
		return $v;
	 
}

function mx_sppad($data, $width)
{
	$v = $data;


	if ($v != '' && mb_strlen($v) < $width) {
		$cnt = $width- mb_strlen($v);
		for ($i = 0; $i < $cnt; $i++) {
			$v = $v." ";
		}
		return $v;
	}
	return $data;
}

function mx_space($data)
{

   
	$o = '';
	for ($i = 0; $i < 16; $i++) {
		$c = mb_substr($data, $i, 1,"UTF-8");
		if ($c == '??') {$c = '';}

		$o .= $c;
	}
	return $o;
}

function checkcode($string,$encoding)
{


/* test 1 mb_check_encoding (test for bad byte stream) */

if ( true === mb_check_encoding ( $string, $encoding ) )
{
    echo 'valid (' . $encoding . ') encoded byte stream!<br />';
}
else
{
    echo 'invalid (' . $encoding . ') encoded byte stream!<br />';
}
return;
}





$zid=$_POST[ptid];

print "typed ID is:";
print $zid;
$id=zeropad($zid);
print "zero padded ID is:";

print $id;
print "......From Database:...";

 $con = mx_db_connect();

pg_set_client_encoding('UTF-8');



$query='SELECT pt_no,  pt_kana , birthday, sex from  tbl_patient  WHERE  pt_no= ';




$cond11="'".$id."'";	
$query=$query.$cond11;
//print $query;


$res = pg_query($con, $query);

if (pg_num_rows($res) && 
      ($pat = pg_fetch_array($res, PG_ASSOC)))
     pg_free_result($res);
  else
     $pat = FALSE;
/*

$con =  pg_connect("host=localhost dbname=orca user=orca ");
if (!$con) {
	echo("<p><span style=\"color:red\">orca cannot be connected</span></p>\n");
	echo("</body></html>\n");
	exit;
}




$query = 'select a.ptid, p.ptnum, a.name as ptname,a.kananame,a.birthday as ptdob,home_post, home_adrs, home_tel1, home_banti,setainusi,
a.sex as ptsex, 
office_name,office_post,office_adrs,office_banti,honkzkkbn,
skkgetymd,tekedymd,b.hknid, b.hknjanum,b.kigo,b.num, b.hihknjaname, b.kakuninymd, d.hknjaname, d.adrs

from tbl_ptinf a,

  tbl_pthkninf b,
  
 tbl_hknjainf d,
tbl_ptnum p

where
 
a.ptid=b.ptid and
b.hknjanum=d.hknjanum  and
a.ptid=p.ptid and
 p.ptnum=';
 

$cond11="'".$id."'";	


 

$query = $query.$cond11;



	
$query = $query."  order by hknid desc  limit 1";



$res = pg_query($con, $query);

 


  if (pg_num_rows($res) && 
      ($pat = pg_fetch_array($res, PG_ASSOC)))
     pg_free_result($res);
  else
     $pat = FALSE;



 
*/






  

  $params = array();
 

  $params['PATIENT_ID'] = $pat['pt_no'];

  $params['PATIENT_KANA'] = $pat['pt_kana'];
  $params['PATIENT_KANJI'] = $pat['ptname'];
   
   

  
  $params['PATIENT_SEX'] = $pat['sex'];
//$ab= mb_detect_encoding($id, "JIS, SJIS, EUC-JP, UTF-8");
//print $ab;



//kana

$ptkana=$pat['pt_kana'];

print mb_convert_encoding($ptkana, "EUC-JP",mb_detect_encoding($ptkana, "JIS, SJIS, EUC-JP, UTF-8,EUC-JP_win"));
print "<=EUC-JP:";

$a= mb_detect_encoding($ptkana, "JIS, SJIS, EUC-JP, UTF-8");
print "CODING=".$a;

//*******************************************************
$pad = ' ';

$pad3 = mb_convert_kana($pad, "k", "SJIS");

$foo5 = mb_convert_kana($ptkana, "k", $a);

$fook1 = mb_convert_encoding($foo5, "SJIS",$a);

$length2=mb_strlen($fook1, "SJIS");
print "sjis-len=".$length2;
print "strlen=".strlen($fook1);
print "***";

print check_len($fook1);
print "***";

$fook2= del_sp($fook1,$pad3);
print mb_convert_encoding($fook2, "EUC-JP",mb_detect_encoding($fook2, "JIS, SJIS, EUC-JP, UTF-8,EUC-JP_win"));
print "YYY";
$length2=mb_strlen($fook2, "SJIS");
print "length=".$length2;

if (strlen($fook2)<16){
$i2 = 16 - $length2;
for ($i = 0; $i < $i2; $i++) {
    $fook2 .= $pad3;
}
}
else 
{
$fook2=substr($fook2,0,16);

}



/*

print mb_convert_encoding($fook2, "EUC-JP",mb_detect_encoding($fook2, "JIS, SJIS, EUC-JP, UTF-8,EUC-JP_win"));
print "********";
$length3=mb_strlen($fook2, "SJIS");
print "sjis-len=".$length3;
print "strlen=".strlen($fook2);

*/


$fook=$fook2;




//kanji

$ptkanji="";


$pads = ' ';
$pads2 = mb_convert_kana($pads, "S", "SJIS");
$i2 = 8 ;
for ($i = 0; $i < $i2; $i++) {
    $ptkanji .= 'a';
}

$fookanji = mb_ereg_replace("a", $pads2, $ptkanji);


//ptid


$ptids = mb_convert_encoding($id, "SJIS");


$space="      ";
$spaces = mb_convert_encoding($space, "SJIS", "EUC-JP");




//birthday
$ptdobs=$pat['birthday'];
$ptdob=substr($ptdobs,0,4).substr($ptdobs,5,2).substr($ptdobs,8,2);
print $ptdob;
print "<=birthday=";

//birthday
$ptdobw= to_wareki($ptdob);


$ptdobs=mb_convert_encoding($ptdobw,"SJIS",mb_detect_encoding($ptdobw, "JIS, SJIS, EUC-JP, UTF-8"));


//sex

$ptsex=$params['PATIENT_SEX'] ;
$ptsexs = mb_convert_encoding($ptsex, "SJIS", mb_detect_encoding($ptsex, "JIS, SJIS, EUC-JP, UTF-8"));
 
print $ptsexs;
 
print "=SEX=";

//header

 $cr="\r";
$crs=mb_convert_encoding($cr,"SJIS",mb_detect_encoding($cr, "JIS, SJIS, EUC-JP, UTF-8"));

$head1="";
   
$head="          E00";
$head1=$head.$cr;  
$heads=mb_convert_encoding($head1,"SJIS",mb_detect_encoding($head1, "JIS, SJIS, EUC-JP, UTF-8"));

//dembun

$denbun = $heads.$ptids;
print strlen($denbun);
$denbun .= $spaces;
print strlen($denbun);
$denbun .= $fook;
print strlen($denbun);
$denbun .= $ptdobs;
print strlen($denbun);
$denbun .= $ptsexs;
print strlen($denbun);
$denbun .= $fookanji;
print strlen($denbun);
$denbun .= $crs;
print strlen($denbun);



$denbun=$heads.$ptids.$spaces.$fook.$ptdobs.$ptsexs.$fookanji.$crs;

print "Length of strings: ";

print strlen($denbun);
print "<=lenght-of-denbun";



print mb_strlen($heads,"SJIS")."\n";
print mb_strlen($ptids,"SJIS")."\n";
print mb_strlen($spaces,"SJIS")."\n";
print mb_strlen($fook,"SJIS")."\n";
print mb_strlen($ptdobs,"SJIS")."\n";
print mb_strlen($ptsexs,"SJIS")."\n";
print mb_strlen($fookanji,"SJIS")."\n";
print mb_strlen($crs,"SJIS")."\n";

print "end of length.   ";


/* 

error_reporting(E_ALL);
	$address = "192.168.1.201";
//$address = "192.168.1.4";
$port = 51031;



$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
} else {
    echo "socket successfully created.\n";
}

echo "Attempting to connect to '$address' on port '$port'...";




$result = socket_connect($socket, $address, $port);
if ($result === false) {
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
} else {
    echo "successfully connected to $address.\n";
}



//if connected
if ($result===true){

 	$sent=socket_write($socket, $denbun, strlen($denbun));


	if($sent === false) {
                	print "cannot write";
          	 	 }
            else{
			print "write";
		}
	//from server

    $input = socket_read($socket, 2048);
	sleep(5);
    echo "Response from server \n";
     print mb_convert_encoding($input, "EUC-JP", "SJIS"); 

   	 
 	if (substr($input,0,3)=='E00'){echo "Correct!\n";}
		else{ print "ERROR:".$input;}

  	 sleep(5);

		}


//end-connection

echo "Closing socket...";
socket_close($socket);



*/



?>
	</tbody>
     </table>

	</body>






</html>
