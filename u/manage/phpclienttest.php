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

<a href="phpclient2-app.php?tab=1">Start again</a>
<br>
<?php 

 include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';  

//pg_set_client_encoding('EUC_JP');

    

  


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
    //年月日を文字列として結合
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





function sp14pad($data)
{

$v=$data;
$ksp=" ";


$ksps=mb_convert_encoding($ksp,"SJIS","EUC-JP");




	 
		$cnt = 4;
		for ($i = 0; $i < $cnt; $i++) {

			$v = $v.$ksps;
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
		$c = mb_substr($data, $i, 1, "EUC-JP");
		if ($c == '　')
			$c = '';
		if ($c == ' ')
			$c = '';
		if ($c == '')
			$c = '';
		$o .= $c;
	}
	return $o;
}






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
 

//$ptkana="ｱｲｳｴｵｶｷｸｹｺｻｼｽｾｿﾅ";


$ptk="アイウエオカキクケコサシスセソナ";
$a=mb_detect_encoding($ptk, "JIS, SJIS, EUC-JP, UTF-8,EUC-JP_win");

print $ptk;
$foo5 = mb_convert_kana($ptk, "k", $a);

//$fook2 = mb_convert_kana($ptkana, "k","EUC-JP");
//$fook2 = mb_convert_kana($ptkana, "k","SJIS");
//$length2=mb_strlen($fook2, "SJIS");
//print $length2;
$foo2 = mb_convert_encoding($foo5, "SJIS",mb_detect_encoding($foo5, "JIS, SJIS, EUC-JP, UTF-8,EUC-JP_win"));

print "kana".$foo2."**************";













$ptkanji="";


$pads = ' ';
$pads2 = mb_convert_kana($pads, "S", "SJIS");
$i2 = 8 ;
for ($i = 0; $i < $i2; $i++) {
    $ptkanji .= 'a';
}

$fookanji = mb_ereg_replace("a", $pads2, $ptkanji);





$aptid="12345678";
$ptids = mb_convert_encoding($aptid, "SJIS",mb_detect_encoding($ptkana, "JIS, SJIS, EUC-JP, UTF-8,EUC-JP_win"));


$space="      ";
$spaces = mb_convert_encoding($space, "SJIS", mb_detect_encoding($ptkana, "JIS, SJIS, EUC-JP, UTF-8,EUC-JP_win"));




//print mb_strlen($spaces,"SJIS");
//$a=checkcode($ptid,$encoding);
$ptdob="20100101";
//$ptdobs = mb_convert_encoding($ptdob, "SJIS", mb_detect_encoding($ptdob, "JIS, SJIS, EUC-JP, UTF-8"));

print "BOD=".$ptdobs;

$ptsex="M";
$ptsexs = mb_convert_encoding($ptsex, "SJIS", mb_detect_encoding($ptsex, "JIS, SJIS, EUC-JP, UTF-8"));
 

 










error_reporting(E_ALL);
 $address = "192.168.1.201";

$port = 51031;

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "socket creation failed: reason: " . socket_strerror(socket_last_error()) . "\n";
} else {
    echo "socket created.\n";
}

echo "Try to connect to  '$address' on port '$port'...";
$result = socket_connect($socket, $address, $port);
if ($result === false) {
    echo "cannot connect \nreason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
} else {
    echo "connected to $address.\n";
}






 $cr="\r";
$crs=mb_convert_encoding($cr,"SJIS",mb_detect_encoding($cr, "JIS, SJIS, EUC-JP, UTF-8"));

$head1="";
   
$head="          E00";
$head1=$head.$cr;  
$heads=mb_convert_encoding($head1,"SJIS",mb_detect_encoding($head1, "JIS, SJIS, EUC-JP, UTF-8"));



 

 





$ptdobw= to_wareki($ptdob);




$ptdobs=mb_convert_encoding($ptdobw,"SJIS",mb_detect_encoding($ptdobw, "JIS, SJIS, EUC-JP, UTF-8"));






$denbun=$heads.$ptids.$spaces.$foo2.$ptdobs.$ptsexs.$fookanji.$crs;

//print $denbun."\n";
print "Length of strings: ";
print mb_strlen($heads,"SJIS")."\n";
print mb_strlen($ptids,"SJIS")."\n";
print mb_strlen($spaces,"SJIS")."\n";
print mb_strlen($foo2,"SJIS")."\n";
print mb_strlen($ptdobs,"SJIS")."\n";
print mb_strlen($ptsexs,"SJIS")."\n";
print mb_strlen($fookanji,"SJIS")."\n";
print mb_strlen($crs,"SJIS")."\n";

print "end of length.   ";



print strlen($denbun);



/*

 socket_write($socket, $denbun, strlen($denbun));
  
    $input = socket_read($socket, 2048);
    echo "Response from server is: $input\n";
      
    sleep(5);
 if (substr($input,0,3)=='E00'){echo "Correct!\n";}

socket_close($socket);



echo "Closing socket...";


*/



?>
	</tbody>
     </table>

	</body>






</html>
