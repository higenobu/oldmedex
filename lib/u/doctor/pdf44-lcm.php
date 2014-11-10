<?php // -*- coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
 
//0920-2011
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/common.php';
 
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/print.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ooo.php';


//for japanese
function go_pdf($oid, $shots, $template=NULL) 
{



 

$ord = get_otatest_order($oid, $shots);


//0920-2011  µ≠œøº‘-> CreatedBy

//  $doc = get_emp_name($ord['CreatedBy']);



  $pat = get_patient($ord['patient'],false);
  
  $pat['¥µº‘ID'] = ereg_replace("^(.*) .*","\\1",$pat['¥µº‘ID']);
  $kubun=array();

 
  // prepare values to be embedded into PDF
  $params = array();
  
  
    $params['PRESCRIPTION_TITLE'] = 'OTATEST';
  
  
   // ¥µº‘ª·Ãæ°¢¿∏«Ø∑Ó∆¸°¢∂Ë ¨°¢≥‰πÁ
  $params['PATIENT_ID'] = $pat['¥µº‘ID'];
  $params['PATIENT_KANA'] = $pat['•’•Í•¨• '];
 
  $params['PATIENT_KANJI'] = $pat['¿´'] .','. $pat['Ãæ'];
$params['PATIENT_DOB'] = $pat['¿∏«Ø∑Ó∆¸'] ;
//  $params['PATIENT_DOB'] = mx_wareki($pat['¿∏«Ø∑Ó∆¸']);
  $params['PATIENT_AGE'] = mx_calc_age($pat['¿∏«Ø∑Ó∆¸']);
  $params['PATIENT_SEX'] = $pat['¿≠ Ã'] == 'M' ? '√À' : 'Ω˜';
  $params['PATIENT_KUBUN'] = $pat['»Ô ›∏±º‘'] == '1' ? 'À‹øÕ' : '≤»¬≤';
  $params['PATIENT_GROUP'] = $pat['¥ıÀæ…¬≈Ô'];


  $params['PRESCRIPTION_DATE'] = $ord['order_date'];
    $params['PREORDERDATE'] = $ord['preorderdate'];                                      
 $age= $params['PATIENT_AGE'];
$params['PATIENT_ZIP'] = $pat['ΩªΩÍ0'];
  $params['PATIENT_ADDR1'] = $pat['ΩªΩÍ1'];
$params['PATIENT_ADDR2'] = $pat['ΩªΩÍ2'];
  $params['PATIENT_ADDR3'] = $pat['ΩªΩÍ3'];
$params['PATIENT_OCC'] = $pat['∂–Ã≥¿ËÃæ'];
$params['PTDEPT'] = $pat['¿¡µ·¿ËÃæ'];
$params['PTBR'] = $pat['¿¡µ·¿ËΩªΩÍ'];
$params['PTTEL'] = $pat['tel'];
$params['CTRY'] = $pat['country'];
 
 
 
//

$params['order_date'] = $ord['order_date'];
$params['preorderdate'] = $ord['preorderdate'];
$params['CAT'] = $ord['category'];
$params['addition'] = $ord['addition'];
$params['orderid'] = $ord['orderid'];
$params['pdf'] = $ord['pdf'];
$params['special_req'] = $ord['special_req'];
$params['notes'] = $ord['notes'];
$params['kk0'] = $ord['kk0'];
$params['ss0'] = $ord['ss0'];
$params['cc0'] = $ord['cc0'];
$params['kk1'] = $ord['kk1'];
$params['ss1'] = $ord['ss1'];
$params['cc1'] = $ord['cc1'];
$params['kk2'] = $ord['kk2'];
$params['ss2'] = $ord['ss2'];
$params['cc2'] = $ord['cc2'];
$params['kk3'] = $ord['kk3'];
$params['ss3'] = $ord['ss3'];
$params['cc3'] = $ord['cc3'];
$params['kk4'] = $ord['kk4'];
$params['ss4'] = $ord['ss4'];
$params['cc4'] = $ord['cc4'];
$params['pp0'] = $ord['pp0'];
$params['pp1'] = $ord['pp1'];
$params['pp2'] = $ord['pp2'];
$params['pp3'] = $ord['pp3'];
$params['pp4'] = $ord['pp4'];
$params['kk5'] = $ord['kk5'];
$params['pp5'] = $ord['pp5'];
$params['cc5'] = $ord['cc5'];
$params['ss5'] = $ord['ss5'];
$params['kk6'] = $ord['kk6'];
$params['pp6'] = $ord['pp6'];
$params['ss6'] = $ord['ss6'];
$params['cc6'] = $ord['cc6'];
$params['kk7'] = $ord['kk7'];
$params['pp7'] = $ord['pp7'];
$params['ss7'] = $ord['ss7'];
$params['cc7'] = $ord['cc7'];
$params['kk8'] = $ord['kk8'];
$params['pp8'] = $ord['pp8'];
$params['ss8'] = $ord['ss8'];
$params['cc8'] = $ord['cc8'];
$params['kk9'] = $ord['kk9'];
$params['pp9'] = $ord['pp9'];
$params['cc9'] = $ord['cc9'];
$params['ss9'] = $ord['ss9'];
$params['kk10'] = $ord['kk10'];
$params['pp10'] = $ord['pp10'];
$params['ss10'] = $ord['ss10'];
$params['cc10'] = $ord['cc10'];
$params['kk11'] = $ord['kk11'];
$params['pp11'] = $ord['pp11'];
$params['ss11'] = $ord['ss11'];
$params['cc11'] = $ord['cc11'];
$params['kk12'] = $ord['kk12'];
$params['pp12'] = $ord['pp12'];
$params['ss12'] = $ord['ss12'];
$params['cc12'] = $ord['cc12'];
$params['kk13'] = $ord['kk13'];
$params['pp13'] = $ord['pp13'];
$params['ss13'] = $ord['ss13'];
$params['cc13'] = $ord['cc13'];
$params['kk14'] = $ord['kk14'];
$params['pp14'] = $ord['pp14'];
$params['ss14'] = $ord['ss14'];
$params['cc14'] = $ord['cc14'];
$params['kk15'] = $ord['kk15'];
$params['pp15'] = $ord['pp15'];
$params['ss15'] = $ord['ss15'];
$params['cc15'] = $ord['cc15'];
$params['kk20'] = $ord['kk20'];
$params['pp20'] = $ord['pp20'];
$params['ss20'] = $ord['ss20'];
$params['cc20'] = $ord['cc20'];
$params['kk21'] = $ord['kk21'];
$params['pp21'] = $ord['pp21'];
$params['ss21'] = $ord['ss21'];
$params['cc21'] = $ord['cc21'];
$params['kk22'] = $ord['kk22'];
$params['pp22'] = $ord['pp22'];
$params['ss22'] = $ord['ss22'];
$params['cc22'] = $ord['cc22'];
$params['kk40'] = $ord['kk40'];
$params['pp40'] = $ord['pp40'];
$params['ss40'] = $ord['ss40'];
$params['cc40'] = $ord['cc40'];
$params['kk41'] = $ord['kk41'];
$params['pp41'] = $ord['pp41'];
$params['ss41'] = $ord['ss41'];
$params['cc41'] = $ord['cc41'];
$params['kk42'] = $ord['kk42'];
$params['pp42'] = $ord['pp42'];
$params['ss42'] = $ord['ss42'];
$params['cc42'] = $ord['cc42'];
$params['kk50'] = $ord['kk50'];
$params['pp50'] = $ord['pp50'];
$params['ss50'] = $ord['ss50'];
$params['cc50'] = $ord['cc50'];
$params['kk51'] = $ord['kk51'];
$params['pp51'] = $ord['pp51'];
$params['ss51'] = $ord['ss51'];
$params['cc51'] = $ord['cc51'];
$params['kk52'] = $ord['kk52'];
$params['pp52'] = $ord['pp52'];
$params['ss52'] = $ord['ss52'];
$params['cc52'] = $ord['cc52'];
$params['kk53'] = $ord['kk53'];
$params['pp53'] = $ord['pp53'];
$params['ss53'] = $ord['ss53'];
$params['cc53'] = $ord['cc53'];
$params['kk54'] = $ord['kk54'];
$params['pp54'] = $ord['pp54'];
$params['ss54'] = $ord['ss54'];
$params['cc54'] = $ord['cc54'];
$params['kk55'] = $ord['kk55'];
$params['pp55'] = $ord['pp55'];
$params['ss55'] = $ord['ss55'];
$params['cc55'] = $ord['cc55'];
$params['kk56'] = $ord['kk56'];
$params['pp56'] = $ord['pp56'];
$params['ss56'] = $ord['ss56'];
$params['cc56'] = $ord['cc56'];
$params['kk57'] = $ord['kk57'];
$params['pp57'] = $ord['pp57'];
$params['ss57'] = $ord['ss57'];
$params['cc57'] = $ord['cc57'];
$params['kk58'] = $ord['kk58'];
$params['pp58'] = $ord['pp58'];
$params['ss58'] = $ord['ss58'];
$params['cc58'] = $ord['cc58'];
$params['kk59'] = $ord['kk59'];
$params['pp59'] = $ord['pp59'];
$params['ss59'] = $ord['ss59'];
$params['cc59'] = $ord['cc59'];
$params['kk30'] = $ord['kk30'];
$params['pp30'] = $ord['pp30'];
$params['ss30'] = $ord['ss30'];
$params['cc30'] = $ord['cc30'];
$params['kk31'] = $ord['kk31'];
$params['pp31'] = $ord['pp31'];
$params['ss31'] = $ord['ss31'];
$params['cc31'] = $ord['cc31'];
$params['kk32'] = $ord['kk32'];
$params['pp32'] = $ord['pp32'];
$params['ss32'] = $ord['ss32'];
$params['cc32'] = $ord['cc32'];
$params['kk33'] = $ord['kk33'];
$params['pp33'] = $ord['pp33'];
$params['ss33'] = $ord['ss33'];
$params['cc33'] = $ord['cc33'];
$params['kk34'] = $ord['kk34'];
$params['pp34'] = $ord['pp34'];
$params['ss34'] = $ord['ss34'];
$params['cc34'] = $ord['cc34'];
$params['kk35'] = $ord['kk35'];
$params['pp35'] = $ord['pp35'];
$params['ss35'] = $ord['ss35'];
$params['cc35'] = $ord['cc35'];
$params['kk36'] = $ord['kk36'];
$params['pp36'] = $ord['pp36'];
$params['ss36'] = $ord['ss36'];
$params['cc36'] = $ord['cc36'];
$params['kk37'] = $ord['kk37'];
$params['pp37'] = $ord['pp37'];
$params['ss37'] = $ord['ss37'];
$params['cc37'] = $ord['cc37'];
$params['kk38'] = $ord['kk38'];
$params['pp38'] = $ord['pp38'];
$params['ss38'] = $ord['ss38'];
$params['cc38'] = $ord['cc38'];
$params['kk39'] = $ord['kk39'];
$params['pp39'] = $ord['pp39'];
$params['ss39'] = $ord['ss39'];
$params['cc39'] = $ord['cc39'];
$params['kk60'] = $ord['kk60'];
$params['pp60'] = $ord['pp60'];
$params['ss60'] = $ord['ss60'];
$params['cc60'] = $ord['cc60'];
$params['kk61'] = $ord['kk61'];
$params['pp61'] = $ord['pp61'];
$params['ss61'] = $ord['ss61'];
$params['cc61'] = $ord['cc61'];
$params['kk62'] = $ord['kk62'];
$params['pp62'] = $ord['pp62'];
$params['ss62'] = $ord['ss62'];
$params['cc62'] = $ord['cc62'];
$params['kk63'] = $ord['kk63'];
$params['pp63'] = $ord['pp63'];
$params['ss63'] = $ord['ss63'];
$params['cc63'] = $ord['cc63'];
$params['kk64'] = $ord['kk64'];
$params['pp64'] = $ord['pp64'];
$params['ss64'] = $ord['ss64'];
$params['cc64'] = $ord['cc64'];
$params['kk65'] = $ord['kk65'];
$params['pp65'] = $ord['pp65'];
$params['ss65'] = $ord['ss65'];
$params['cc65'] = $ord['cc65'];
$params['kk66'] = $ord['kk66'];
$params['pp66'] = $ord['pp66'];
$params['ss66'] = $ord['ss66'];
$params['cc66'] = $ord['cc66'];
$params['kk67'] = $ord['kk67'];
$params['pp67'] = $ord['pp67'];
$params['ss67'] = $ord['ss67'];
$params['cc67'] = $ord['cc67'];
$params['kk70'] = $ord['kk70'];
$params['pp70'] = $ord['pp70'];
$params['ss70'] = $ord['ss70'];
$params['cc70'] = $ord['cc70'];
$params['kk71'] = $ord['kk71'];
$params['pp71'] = $ord['pp71'];
$params['ss71'] = $ord['ss71'];
$params['cc71'] = $ord['cc71'];
$params['kk72'] = $ord['kk72'];
$params['pp72'] = $ord['pp72'];
$params['ss72'] = $ord['ss72'];
$params['cc72'] = $ord['cc72'];
$params['kk73'] = $ord['kk73'];
$params['pp73'] = $ord['pp73'];
$params['ss73'] = $ord['ss73'];
$params['cc73'] = $ord['cc73'];
$params['kk74'] = $ord['kk74'];
$params['pp74'] = $ord['pp74'];
$params['ss74'] = $ord['ss74'];
$params['cc74'] = $ord['cc74'];
$params['kk75'] = $ord['kk75'];
$params['pp75'] = $ord['pp75'];
$params['ss75'] = $ord['ss75'];
$params['cc75'] = $ord['cc75'];
$params['kk76'] = $ord['kk76'];
$params['pp76'] = $ord['pp76'];
$params['ss76'] = $ord['ss76'];
$params['cc76'] = $ord['cc76'];
$params['kk77'] = $ord['kk77'];
$params['pp77'] = $ord['pp77'];
$params['ss77'] = $ord['ss77'];
$params['cc77'] = $ord['cc77'];
$params['kk78'] = $ord['kk78'];
$params['pp78'] = $ord['pp78'];
$params['ss78'] = $ord['ss78'];
$params['cc78'] = $ord['cc78'];
$params['kk79'] = $ord['kk79'];
$params['pp79'] = $ord['pp79'];
$params['ss79'] = $ord['ss79'];
$params['cc79'] = $ord['cc79'];
$params['kk90'] = $ord['kk90'];
$params['pp90'] = $ord['pp90'];
$params['ss90'] = $ord['ss90'];
$params['cc90'] = $ord['cc90'];
$params['kk91'] = $ord['kk91'];
$params['pp91'] = $ord['pp91'];
$params['ss91'] = $ord['ss91'];
$params['cc91'] = $ord['cc91'];
$params['kk92'] = $ord['kk92'];
$params['pp92'] = $ord['pp92'];
$params['ss92'] = $ord['ss92'];
$params['cc92'] = $ord['cc92'];
$params['kk93'] = $ord['kk93'];
$params['pp93'] = $ord['pp93'];
$params['ss93'] = $ord['ss93'];
$params['cc93'] = $ord['cc93'];
$params['kk94'] = $ord['kk94'];
$params['pp94'] = $ord['pp94'];
$params['ss94'] = $ord['ss94'];
$params['cc94'] = $ord['cc94'];
$params['kk95'] = $ord['kk95'];
$params['pp95'] = $ord['pp95'];
$params['ss95'] = $ord['ss95'];
$params['cc95'] = $ord['cc95'];
$params['k100'] = $ord['k100'];
$params['k101'] = $ord['k101'];
$params['k102'] = $ord['k102'];
$params['k103'] = $ord['k103'];
$params['k104'] = $ord['k104'];
$params['k105'] = $ord['k105'];
$params['k106'] = $ord['k106'];
$params['p100'] = $ord['p100'];
$params['p101'] = $ord['p101'];
$params['p102'] = $ord['p102'];
$params['p103'] = $ord['p103'];
$params['p104'] = $ord['p104'];
$params['p105'] = $ord['p105'];
$params['p106'] = $ord['p106'];
$params['k200'] = $ord['k200'];
$params['k201'] = $ord['k201'];
$params['k202'] = $ord['k202'];
$params['k203'] = $ord['k203'];
$params['k204'] = $ord['k204'];
$params['k205'] = $ord['k205'];
$params['k206'] = $ord['k206'];
$params['k207'] = $ord['k207'];
$params['k208'] = $ord['k208'];
$params['p200'] = $ord['p200'];
$params['p201'] = $ord['p201'];
$params['p202'] = $ord['p202'];
$params['p203'] = $ord['p203'];
$params['p204'] = $ord['p204'];
$params['p205'] = $ord['p205'];
$params['p206'] = $ord['p206'];
$params['p207'] = $ord['p207'];
$params['p208'] = $ord['p208'];
$params['k300'] = $ord['k300'];
$params['k301'] = $ord['k301'];
$params['k302'] = $ord['k302'];
$params['p300'] = $ord['p300'];
$params['p301'] = $ord['p301'];
$params['p302'] = $ord['p302'];
$params['k500'] = $ord['k500'];
$params['k501'] = $ord['k501'];
$params['k502'] = $ord['k502'];
$params['k503'] = $ord['k503'];
$params['k504'] = $ord['k504'];
$params['k505'] = $ord['k505'];
$params['k506'] = $ord['k506'];
$params['k507'] = $ord['k507'];
$params['k508'] = $ord['k508'];
$params['k509'] = $ord['k509'];
$params['k510'] = $ord['k510'];
$params['k511'] = $ord['k511'];
$params['k512'] = $ord['k512'];
$params['k513'] = $ord['k513'];
$params['k514'] = $ord['k514'];
$params['k515'] = $ord['k515'];
$params['k516'] = $ord['k516'];
$params['k517'] = $ord['k517'];
$params['k518'] = $ord['k518'];
$params['k519'] = $ord['k519'];
$params['k520'] = $ord['k520'];
$params['p500'] = $ord['p500'];
$params['p501'] = $ord['p501'];
$params['p502'] = $ord['p502'];
$params['p503'] = $ord['p503'];
$params['p504'] = $ord['p504'];
$params['p505'] = $ord['p505'];
$params['p506'] = $ord['p506'];
$params['p507'] = $ord['p507'];
$params['p508'] = $ord['p508'];
$params['p509'] = $ord['p509'];
$params['p510'] = $ord['p510'];
$params['p511'] = $ord['p511'];
$params['p512'] = $ord['p512'];
$params['p513'] = $ord['p513'];
$params['p514'] = $ord['p514'];
$params['p515'] = $ord['p515'];
$params['p516'] = $ord['p516'];
$params['p517'] = $ord['p517'];
$params['p518'] = $ord['p518'];
$params['p519'] = $ord['p519'];
$params['p520'] = $ord['p520'];
$params['c500'] = $ord['c500'];
$params['c501'] = $ord['c501'];
$params['c502'] = $ord['c502'];
$params['c503'] = $ord['c503'];
$params['c504'] = $ord['c504'];
$params['c505'] = $ord['c505'];
$params['c506'] = $ord['c506'];
$params['c507'] = $ord['c507'];
$params['c508'] = $ord['c508'];
$params['c509'] = $ord['c509'];
$params['c510'] = $ord['c510'];
$params['c511'] = $ord['c511'];
$params['c512'] = $ord['c512'];
$params['c513'] = $ord['c513'];
$params['c514'] = $ord['c514'];
$params['c515'] = $ord['c515'];
$params['c516'] = $ord['c516'];
$params['c517'] = $ord['c517'];
$params['c518'] = $ord['c518'];
$params['c519'] = $ord['c519'];
$params['c520'] = $ord['c520'];
$params['k80'] = $ord['k80'];
$params['k81'] = $ord['k81'];
$params['k82'] = $ord['k82'];
$params['k83'] = $ord['k83'];
$params['k84'] = $ord['k84'];
$params['k85'] = $ord['k85'];
$params['k86'] = $ord['k86'];
$params['k87'] = $ord['k87'];
$params['k88'] = $ord['k88'];
$params['k89'] = $ord['k89'];
$params['k90'] = $ord['k90'];
$params['k91'] = $ord['k91'];
$params['p80'] = $ord['p80'];
$params['p81'] = $ord['p81'];
$params['p82'] = $ord['p82'];
$params['p83'] = $ord['p83'];
$params['p84'] = $ord['p84'];
$params['p85'] = $ord['p85'];
$params['p86'] = $ord['p86'];
$params['p87'] = $ord['p87'];
$params['p88'] = $ord['p88'];
$params['p89'] = $ord['p89'];
$params['p90'] = $ord['p90'];
$params['p91'] = $ord['p91'];
$params['k400'] = $ord['k400'];
$params['k401'] = $ord['k401'];
$params['k402'] = $ord['k402'];
$params['k403'] = $ord['k403'];
$params['k404'] = $ord['k404'];
$params['k405'] = $ord['k405'];
$params['k406'] = $ord['k406'];
$params['k407'] = $ord['k407'];
$params['k408'] = $ord['k408'];
$params['k409'] = $ord['k409'];
$params['k410'] = $ord['k410'];
$params['k411'] = $ord['k411'];
$params['k412'] = $ord['k412'];
$params['k413'] = $ord['k413'];
$params['k414'] = $ord['k414'];
$params['k415'] = $ord['k415'];
$params['plandate'] = $ord['plandate'];
$params['kk600'] = $ord['kk600'];
$params['pp600'] = $ord['pp600'];
$params['kk601'] = $ord['kk601'];
$params['pp601'] = $ord['pp601'];
$params['kk602'] = $ord['kk602'];
$params['pp602'] = $ord['pp602'];
$params['kk603'] = $ord['kk603'];
$params['pp603'] = $ord['pp603'];
$params['kk604'] = $ord['kk604'];
$params['pp604'] = $ord['pp604'];
$params['kk605'] = $ord['kk605'];
$params['pp605'] = $ord['pp605'];
$params['kk606'] = $ord['kk606'];
$params['pp606'] = $ord['pp606'];
$params['kk607'] = $ord['kk607'];
$params['pp607'] = $ord['pp607'];
$params['kk608'] = $ord['kk608'];
$params['pp608'] = $ord['pp608'];
$params['kk609'] = $ord['kk609'];
$params['pp609'] = $ord['pp609'];
$params['kk610'] = $ord['kk610'];
$params['pp610'] = $ord['pp610'];
$params['kk611'] = $ord['kk611'];
$params['pp611'] = $ord['pp611'];
$params['kk612'] = $ord['kk612'];
$params['pp612'] = $ord['pp612'];
$params['kk613'] = $ord['kk613'];
$params['pp613'] = $ord['pp613'];
$params['kk614'] = $ord['kk614'];
$params['pp614'] = $ord['pp614'];
$params['kk615'] = $ord['kk615'];
$params['pp615'] = $ord['pp615'];
$params['kk616'] = $ord['kk616'];
$params['pp616'] = $ord['pp616'];
$params['kk617'] = $ord['kk617'];
$params['pp617'] = $ord['pp617'];
$params['kk618'] = $ord['kk618'];
$params['pp618'] = $ord['pp618'];
$params['kk619'] = $ord['kk619'];
$params['pp619'] = $ord['pp619'];
$params['kk620'] = $ord['kk620'];
$params['pp620'] = $ord['pp620'];
$params['aa1'] = $ord['aa1'];
$params['aa2'] = $ord['aa2'];
$params['aa3'] = $ord['aa3'];
$params['aa4'] = $ord['aa4'];
$params['aa5'] = $ord['aa5'];
$params['aa6'] = $ord['aa6'];
$params['aa7'] = $ord['aa7'];
$params['aa8'] = $ord['aa8'];
$params['aa9'] = $ord['aa9'];
$params['aa10'] = $ord['aa10'];
$params['aa11'] = $ord['aa11'];
$params['aa12'] = $ord['aa12'];
$params['aa13'] = $ord['aa13'];
$params['aa14'] = $ord['aa14'];
$params['aa15'] = $ord['aa15'];
$params['aa16'] = $ord['aa16'];
$params['aa17'] = $ord['aa17'];
$params['aa18'] = $ord['aa18'];
$params['aa19'] = $ord['aa19'];
$params['aa20'] = $ord['aa20'];
$params['aa21'] = $ord['aa21'];
$params['aa22'] = $ord['aa22'];
$params['aa23'] = $ord['aa23'];
$params['aa24'] = $ord['aa24'];
$params['aa25'] = $ord['aa25'];
$params['aa26'] = $ord['aa26'];
$params['aa27'] = $ord['aa27'];
$params['aa28'] = $ord['aa28'];
$params['aa29'] = $ord['aa29'];
$params['aa30'] = $ord['aa30'];
$params['aa31'] = $ord['aa31'];
$params['aa32'] = $ord['aa32'];
$params['aa33'] = $ord['aa33'];
$params['aa34'] = $ord['aa34'];
$params['aa35'] = $ord['aa35'];
$params['aa36'] = $ord['aa36'];
$params['aa37'] = $ord['aa37'];
$params['aa38'] = $ord['aa38'];
$params['aa39'] = $ord['aa39'];
$params['aa40'] = $ord['aa40'];
$params['aa41'] = $ord['aa41'];
$params['aa42'] = $ord['aa42'];
$params['aa43'] = $ord['aa43'];
$params['aa44'] = $ord['aa44'];
$params['aa45'] = $ord['aa45'];
$params['aa46'] = $ord['aa46'];
$params['aa47'] = $ord['aa47'];
$params['aa48'] = $ord['aa48'];
$params['aa49'] = $ord['aa49'];
$params['aa50'] = $ord['aa50'];
$params['cc16'] = $ord['cc16'];
$params['cc17'] = $ord['cc17'];
$params['cc18'] = $ord['cc18'];
$params['cc19'] = $ord['cc19'];
$params['kk530'] = $ord['kk530'];
$params['cc530'] = $ord['cc530'];
$params['pp530'] = $ord['pp530'];
$params['kk540'] = $ord['kk540'];
$params['kk531'] = $ord['kk531'];
$params['cc531'] = $ord['cc531'];
$params['pp531'] = $ord['pp531'];
$params['kk541'] = $ord['kk541'];
$params['kk532'] = $ord['kk532'];
$params['cc532'] = $ord['cc532'];
$params['pp532'] = $ord['pp532'];
$params['kk542'] = $ord['kk542'];
$params['k107'] = $ord['k107'];
$params['p107'] = $ord['p107'];
$params['aa51'] = $ord['aa51'];
$params['aa52'] = $ord['aa52'];
$params['aa53'] = $ord['aa53'];
$params['aa54'] = $ord['aa54'];
$params['aa55'] = $ord['aa55'];
$params['aa56'] = $ord['aa56'];
$params['aa57'] = $ord['aa57'];
$params['aa58'] = $ord['aa58'];
$params['aa59'] = $ord['aa59'];
$params['shiji'] = $ord['shiji'];
$params['cc400'] = $ord['cc400'];
$params['k416'] = $ord['k416'];
$params['cc401'] = $ord['cc401'];
$params['cc402'] = $ord['cc402'];
$params['cc403'] = $ord['cc403'];
$params['cc404'] = $ord['cc404'];
$params['cc405'] = $ord['cc405'];
$params['cc406'] = $ord['cc406'];
$params['cc407'] = $ord['cc407'];
$params['cc408'] = $ord['cc408'];
$params['cc409'] = $ord['cc409'];
$params['cc410'] = $ord['cc410'];
$params['cc411'] = $ord['cc411'];
$params['cc412'] = $ord['cc412'];
$params['cc413'] = $ord['cc413'];
$params['cc414'] = $ord['cc414'];
$params['cc415'] = $ord['cc415'];
$params['cc416'] = $ord['cc416'];
$params['cc417'] = $ord['cc417'];
$params['k417'] = $ord['k417'];
$params['kk417'] = $ord['kk417'];
$params['k1000'] = $ord['k1000'];
$params['p1000'] = $ord['p1000'];
$params['k1001'] = $ord['k1001'];
$params['p1001'] = $ord['p1001'];
$params['k1003'] = $ord['k1003'];
$params['p1003'] = $ord['p1003'];
$params['aa1003'] = $ord['aa1003'];
$params['k1002'] = $ord['k1002'];
$params['p1002'] = $ord['p1002'];
$params['c1002'] = $ord['c1002'];
$params['k1004'] = $ord['k1004'];
$params['p1004'] = $ord['p1004'];
$params['aa94'] = $ord['aa94'];
$params['k1005'] = $ord['k1005'];
$params['p1005'] = $ord['p1005'];
$params['aa103'] = $ord['aa103'];
$params['aa104'] = $ord['aa104'];
$params['aa105'] = $ord['aa105'];
$params['aa1005'] = $ord['aa1005'];
$params['k1006'] = $ord['k1006'];
$params['p1006'] = $ord['p1006'];
$params['k1007'] = $ord['k1007'];
$params['p1007'] = $ord['p1007'];
$params['aa1006'] = $ord['aa1006'];
$params['aa1007'] = $ord['aa1007'];
$params['k418'] = $ord['k418'];
$params['cc418'] = $ord['cc418'];
$params['aa80'] = $ord['aa80'];
$params['aa81'] = $ord['aa81'];
$params['aa82'] = $ord['aa82'];
$params['aa83'] = $ord['aa83'];
$params['aa84'] = $ord['aa84'];
$params['aa85'] = $ord['aa85'];
$params['aa86'] = $ord['aa86'];
$params['aa87'] = $ord['aa87'];
$params['aa88'] = $ord['aa88'];
$params['aa89'] = $ord['aa89'];
$params['aa90'] = $ord['aa90'];
$params['aa91'] = $ord['aa91'];
$params['aa1000'] = $ord['aa1000'];
$params['aa1001'] = $ord['aa1001'];
$params['k560'] = $ord['k560'];
$params['p560'] = $ord['p560'];
$params['c560'] = $ord['c560'];
$params['k561'] = $ord['k561'];
$params['p561'] = $ord['p561'];
$params['c561'] = $ord['c561'];
$params['k562'] = $ord['k562'];
$params['p562'] = $ord['p562'];
$params['c562'] = $ord['c562'];
$params['k563'] = $ord['k563'];
$params['p563'] = $ord['p563'];
$params['c563'] = $ord['c563'];
$params['k564'] = $ord['k564'];
$params['p564'] = $ord['p564'];
$params['c564'] = $ord['c564'];
$params['kk100'] = $ord['kk100'];
$params['pp100'] = $ord['pp100'];
$params['aa100'] = $ord['aa100'];
$params['cc100'] = $ord['cc100'];
$params['kk101'] = $ord['kk101'];
$params['pp101'] = $ord['pp101'];
$params['aa101'] = $ord['aa101'];
$params['cc101'] = $ord['cc101'];
$params['kk102'] = $ord['kk102'];
$params['pp102'] = $ord['pp102'];
$params['aa102'] = $ord['aa102'];
$params['cc102'] = $ord['cc102'];
$params['kk103'] = $ord['kk103'];
$params['pp103'] = $ord['pp103'];
$params['cc103'] = $ord['cc103'];
$params['kk104'] = $ord['kk104'];
$params['pp104'] = $ord['pp104'];
$params['cc104'] = $ord['cc104'];
$params['kk105'] = $ord['kk105'];
$params['pp105'] = $ord['pp105'];
$params['cc105'] = $ord['cc105'];
$params['kk106'] = $ord['kk106'];
$params['pp106'] = $ord['pp106'];
$params['aa106'] = $ord['aa106'];
$params['cc106'] = $ord['cc106'];
$params['kk107'] = $ord['kk107'];
$params['pp107'] = $ord['pp107'];
$params['aa107'] = $ord['aa107'];
$params['cc107'] = $ord['cc107'];
$params['kk108'] = $ord['kk108'];
$params['pp108'] = $ord['pp108'];
$params['aa108'] = $ord['aa108'];
$params['cc108'] = $ord['cc108'];
$params['kk109'] = $ord['kk109'];
$params['pp109'] = $ord['pp109'];
$params['aa109'] = $ord['aa109'];
$params['cc109'] = $ord['cc109'];
$params['kk110'] = $ord['kk110'];
$params['pp110'] = $ord['pp110'];
$params['aa110'] = $ord['aa110'];
$params['cc110'] = $ord['cc110'];
$params['kk111'] = $ord['kk111'];
$params['pp111'] = $ord['pp111'];
$params['aa111'] = $ord['aa111'];
$params['cc111'] = $ord['cc111'];
$params['kk112'] = $ord['kk112'];
$params['pp112'] = $ord['pp112'];
$params['aa112'] = $ord['aa112'];
$params['cc112'] = $ord['cc112'];
$params['kk113'] = $ord['kk113'];
$params['pp113'] = $ord['pp113'];
$params['aa113'] = $ord['aa113'];
$params['cc113'] = $ord['cc113'];
$params['kk114'] = $ord['kk114'];
$params['pp114'] = $ord['pp114'];
$params['aa114'] = $ord['aa114'];
$params['cc114'] = $ord['cc114'];
$params['kk115'] = $ord['kk115'];
$params['pp115'] = $ord['pp115'];
$params['aa115'] = $ord['aa115'];
$params['cc115'] = $ord['cc115'];
$params['kk116'] = $ord['kk116'];
$params['pp116'] = $ord['pp116'];
$params['aa116'] = $ord['aa116'];
$params['cc116'] = $ord['cc116'];
$params['kk117'] = $ord['kk117'];
$params['pp117'] = $ord['pp117'];
$params['aa117'] = $ord['aa117'];
$params['cc117'] = $ord['cc117'];
$params['kk118'] = $ord['kk118'];
$params['pp118'] = $ord['pp118'];
$params['aa118'] = $ord['aa118'];
$params['cc118'] = $ord['cc118'];
$params['kk119'] = $ord['kk119'];
$params['pp119'] = $ord['pp119'];
$params['aa119'] = $ord['aa119'];
$params['cc119'] = $ord['cc119'];
$params['kk120'] = $ord['kk120'];
$params['pp120'] = $ord['pp120'];
$params['aa120'] = $ord['aa120'];
$params['cc120'] = $ord['cc120'];
$params['kk121'] = $ord['kk121'];
$params['pp121'] = $ord['pp121'];
$params['aa121'] = $ord['aa121'];
$params['cc121'] = $ord['cc121'];
$params['kk122'] = $ord['kk122'];
$params['pp122'] = $ord['pp122'];
$params['aa122'] = $ord['aa122'];
$params['cc122'] = $ord['cc122'];
$params['kk123'] = $ord['kk123'];
$params['pp123'] = $ord['pp123'];
$params['aa123'] = $ord['aa123'];
$params['cc123'] = $ord['cc123'];
$params['kk124'] = $ord['kk124'];
$params['pp124'] = $ord['pp124'];
$params['aa124'] = $ord['aa124'];
$params['cc124'] = $ord['cc124'];
$params['kk125'] = $ord['kk125'];
$params['pp125'] = $ord['pp125'];
$params['aa125'] = $ord['aa125'];
$params['cc125'] = $ord['cc125'];
$params['kk126'] = $ord['kk126'];
$params['pp126'] = $ord['pp126'];
$params['aa126'] = $ord['aa126'];
$params['cc126'] = $ord['cc126'];
$params['kk127'] = $ord['kk127'];
$params['pp127'] = $ord['pp127'];
$params['aa127'] = $ord['aa127'];
$params['cc127'] = $ord['cc127'];
$params['h1'] = $ord['h1'];
$params['h2'] = $ord['h2'];
$params['h3'] = $ord['h3'];
$params['h4'] = $ord['h4'];
$params['h5'] = $ord['h5'];
$params['h6'] = $ord['h6'];
$params['h7'] = $ord['h7'];
$params['h8'] = $ord['h8'];
$params['h9'] = $ord['h9'];
$params['h10'] = $ord['h10'];
$params['h11'] = $ord['h11'];
$params['h12'] = $ord['h12'];
//


$params['notes'] = $ord['notes'];
$params['special_req'] = $ord['special_req'];
	
// haikaturyo
$height=$params['k100'];
if ($pat['¿≠ Ã']=='M'){
 $params['k202'] = (27.63 - 0.112 * $age) * $height;
 $params['SEX'] =0;
 }
else {
$params['SEX'] =1;
$params['k202'] = (21.78 - 0.101 * $age) * $height;
}


	
  $idata = mx_get_install_data();
  $params['HOSPITAL_NAME'] = $idata['HOSPITAL_NAME'];
  $params['HOSPITAL_ADDR'] = $idata['HOSPITAL_ADDR'];
  $params['HOSPITAL_TEL'] = $idata['HOSPITAL_TEL'];
  $params['CORPORATION_NAME'] = $idata['CORPORATION_NAME'];
  
  $params['DOCTOR'] = $ord['shiji'];
  
   
  $params['BODY'] = "   ";
$params['DOCTOR'] = $ord['shiji'];

$ejboth = $ord['h12'];
 
//0115-2014
$template = "osato.ods";
if ($ejboth=='1')
$template = "osato.ods";
 
if ($ejboth=='2')
$template = "osato2.ods";
if ($ejboth=='3')
$template = "osato3.ods";

  $rand = rand(0,100000000);
  $pdf_path = sprintf("/tmp/%s_%d.pdf", $template, $rand);
  $params['PDF_PATH'] = $pdf_path;
  $params['TEMPLATE_DIR'] = $_SERVER['DOCUMENT_ROOT'] . '/templates/';
//0115-2014
$params['TEMPLATE'] = "osato.ods";

if ($ejboth=='1')
  $params['TEMPLATE'] = "osato.ods";
 
if ($ejboth=='2')
  $params['TEMPLATE'] = "osato2.ods";
if ($ejboth=='3')
  $params['TEMPLATE'] = "osato3.ods";	

  print ooo_print_pdf2($params);
//0315-2013
$ppid=$pat['¥µº‘ID'];
//use patient-id as file name
$ppid2=substr($ppid,0,8);
$pdfname=$ppid2.".pdf";
//0315-2013
  if(file_exists($pdf_path)) {
    //---- read pdf file
    $handler = fopen($pdf_path, 'rb');
    $content = fread($handler, filesize($pdf_path));
    fclose($handler);
    unlink($pdf_path);

    //---- store into db
    $db = mx_db_connect();
    $bid = mx_db_insert_blobmedia($db, 'application/pdf', $content);
//0413-2012
    $type = 'ΩË ˝‰µ';
//    if($shots)
//      $type = '√ÌºÕ‰µ';
    $id = mx_db_insert_extdocument($db, $type, $bid,
				   $pt=NULL, $comment=NULL);
    // update record...
    // this is irregular design. SOD should not update db in normal case
    if($shots) {
      $stmt = 'UPDATE "√ÌºÕΩË ˝‰µ" SET "PDF"=' . mx_db_sql_quote($id) .
	' WHERE "ObjectID"=' . mx_db_sql_quote($oid);
    }else{
      $stmt = 'UPDATE "ÃÙ∫ﬁΩË ˝‰µ" SET "PDF"=' . mx_db_sql_quote($id) .
	' WHERE "ObjectID"=' . mx_db_sql_quote($oid);
    }
    pg_query($db, $stmt);
    //HACK: open window and show PDF for client-side printing
    print '
<SCRIPT LANGUAGE="JavaScript"> 
  window.open("/blobmedia.php/' . $id .
      '/'.$pdfname.'","","width=640,height=640");
</SCRIPT>';
    
  }else{
    print "PDF§Œ¿∏¿Æ§Àº∫«‘§∑§ﬁ§∑§ø";
  }


}

?>
