<?php // -*- coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
//0920-2011
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/common.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/rp_common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/print.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ooo.php';



function go_pdf($oid, $shots, $template=NULL) 
{



 

$ord = get_otatest_order($oid, $shots);


//0920-2011  記録者-> CreatedBy

  $doc = get_emp_name($ord['CreatedBy']);



  $pat = get_patient($ord['patient'],false);
  
  $pat['患者ID'] = ereg_replace("^(.*) .*","\\1",$pat['患者ID']);
  $kubun=array();

 
  // prepare values to be embedded into PDF
  $params = array();
  
  
    $params['PRESCRIPTION_TITLE'] = 'OTATEST';
  
  
   // 患者氏名、生年月日、区分、割合
  $params['PATIENT_ID'] = $pat['患者ID'];
  $params['PATIENT_KANA'] = $pat['フリガナ'];
 
  $params['PATIENT_KANJI'] = $pat['姓'] .'　'. $pat['名'];
  $params['PATIENT_DOB'] = mx_wareki($pat['生年月日']);
  $params['PATIENT_AGE'] = mx_calc_age($pat['生年月日']);
  $params['PATIENT_SEX'] = $pat['性別'] == 'M' ? '男' : '女';
  $params['PATIENT_KUBUN'] = $pat['被保険者'] == '1' ? '本人' : '家族';
  $params['PATIENT_GROUP'] = $pat['希望病棟'];


  $params['PRESCRIPTION_DATE'] = $ord['order_date'];
                                          
 $age= $params['PATIENT_AGE'];

//

	$params['kk11'] = $ord['kk11'];
     	$params['kk12'] = $ord['kk12'];
	 $params['kk13'] = $ord['kk13'];
     	$params['kk14'] = $ord['kk14'];
	$params['kk15'] = $ord['kk15'];
    	 $params['kk16'] = $ord['kk16'];
	 $params['kk17'] = $ord['kk17'];
     	$params['kk18'] = $ord['kk18'];
	 $params['pp11'] = $ord['pp11'];
     	$params['pp12'] = $ord['pp12'];
	 $params['pp13'] = $ord['pp13'];
    	 $params['pp14'] = $ord['pp14'];
	$params['pp15'] = $ord['pp15'];
    	 $params['pp16'] = $ord['pp16'];
	 $params['pp17'] = $ord['pp17'];
    	 $params['pp18'] = $ord['pp18'];
//04-20-2012 add
	$params['kk10'] = $ord['kk10'];	
	$params['kk11'] = $ord['kk11'];
     	$params['kk12'] = $ord['kk12'];
	 $params['kk13'] = $ord['kk13'];
     	$params['kk14'] = $ord['kk14'];
	$params['kk15'] = $ord['kk15'];
    	 $params['kk16'] = $ord['kk16'];
	 $params['kk17'] = $ord['kk17'];
     	$params['kk18'] = $ord['kk18'];
	$params['kk19'] = $ord['kk19'];
	$params['pp10'] = $ord['pp10'];	
	 $params['pp11'] = $ord['pp11'];
     	$params['pp12'] = $ord['pp12'];
	 $params['pp13'] = $ord['pp13'];
    	 $params['pp14'] = $ord['pp14'];
	$params['pp15'] = $ord['pp15'];
    	 $params['pp16'] = $ord['pp16'];
	 $params['pp17'] = $ord['pp17'];
    	 $params['pp18'] = $ord['pp18'];
 	$params['pp19'] = $ord['pp19'];

	$params['cc10'] = $ord['cc10'];	
	 $params['cc11'] = $ord['cc11'];
     	$params['cc12'] = $ord['cc12'];
	 $params['cc13'] = $ord['cc13'];
    	 $params['cc14'] = $ord['cc14'];
	$params['cc15'] = $ord['cc15'];
    	 $params['cc16'] = $ord['cc16'];
	 $params['cc17'] = $ord['cc17'];
    	 $params['cc18'] = $ord['cc18'];
 	$params['cc19'] = $ord['cc19'];

	$params['kk30'] = $ord['kk30'];	
	$params['kk31'] = $ord['kk31'];
     	$params['kk32'] = $ord['kk32'];
	 $params['kk33'] = $ord['kk33'];
     	$params['kk34'] = $ord['kk34'];
	$params['kk35'] = $ord['kk35'];
    	 $params['kk36'] = $ord['kk36'];
	 $params['kk37'] = $ord['kk37'];
     	$params['kk38'] = $ord['kk38'];
	$params['kk39'] = $ord['kk39'];
	$params['pp30'] = $ord['pp30'];	
	 $params['pp31'] = $ord['pp31'];
     	$params['pp32'] = $ord['pp32'];
	 $params['pp33'] = $ord['pp33'];
    	 $params['pp34'] = $ord['pp34'];
	$params['pp35'] = $ord['pp35'];
    	 $params['pp36'] = $ord['pp36'];
	 $params['pp37'] = $ord['pp37'];
    	 $params['pp38'] = $ord['pp38'];
 	$params['pp39'] = $ord['pp39'];

	$params['cc30'] = $ord['cc30'];	
	 $params['cc31'] = $ord['cc31'];
     	$params['cc32'] = $ord['cc32'];
	 $params['cc33'] = $ord['cc33'];
    	 $params['cc34'] = $ord['cc34'];
	$params['cc35'] = $ord['cc35'];
    	 $params['cc36'] = $ord['cc36'];
	 $params['cc37'] = $ord['cc37'];
    	 $params['cc38'] = $ord['cc38'];
 	$params['cc39'] = $ord['cc39'];

	$params['kk50'] = $ord['kk50'];	
	$params['kk51'] = $ord['kk51'];
     	$params['kk52'] = $ord['kk52'];
	 $params['kk53'] = $ord['kk53'];
     	$params['kk54'] = $ord['kk54'];
	$params['kk55'] = $ord['kk55'];
    	 $params['kk56'] = $ord['kk56'];
	 $params['kk57'] = $ord['kk57'];
     	$params['kk58'] = $ord['kk58'];
	$params['kk59'] = $ord['kk59'];
	$params['pp50'] = $ord['pp50'];	
	 $params['pp51'] = $ord['pp51'];
     	$params['pp52'] = $ord['pp52'];
	 $params['pp53'] = $ord['pp53'];
    	 $params['pp54'] = $ord['pp54'];
	$params['pp55'] = $ord['pp55'];
    	 $params['pp56'] = $ord['pp56'];
	 $params['pp57'] = $ord['pp57'];
    	 $params['pp58'] = $ord['pp58'];
 	$params['pp59'] = $ord['pp59'];

	$params['cc50'] = $ord['cc50'];	
	 $params['cc51'] = $ord['cc51'];
     	$params['cc52'] = $ord['cc52'];
	 $params['cc53'] = $ord['cc53'];
    	 $params['cc54'] = $ord['cc54'];
	$params['cc55'] = $ord['cc55'];
    	 $params['cc56'] = $ord['cc56'];
	 $params['cc57'] = $ord['cc57'];
    	 $params['cc58'] = $ord['cc58'];
 	$params['cc59'] = $ord['cc59'];

	$params['kk60'] = $ord['kk60'];	
	$params['kk61'] = $ord['kk61'];
     	$params['kk62'] = $ord['kk62'];
	 $params['kk63'] = $ord['kk63'];
     	$params['kk64'] = $ord['kk64'];
	$params['kk65'] = $ord['kk65'];
    	 $params['kk66'] = $ord['kk66'];
	 $params['kk67'] = $ord['kk67'];
     	$params['kk68'] = $ord['kk68'];
	$params['kk69'] = $ord['kk69'];
	$params['pp60'] = $ord['pp60'];	
	 $params['pp61'] = $ord['pp61'];
     	$params['pp62'] = $ord['pp62'];
	 $params['pp63'] = $ord['pp63'];
    	 $params['pp64'] = $ord['pp64'];
	$params['pp65'] = $ord['pp65'];
    	 $params['pp66'] = $ord['pp66'];
	 $params['pp67'] = $ord['pp67'];
    	 

	$params['cc60'] = $ord['cc60'];	
	 $params['cc61'] = $ord['cc61'];
     	$params['cc62'] = $ord['cc62'];
	 $params['cc63'] = $ord['cc63'];
    	 $params['cc64'] = $ord['cc64'];
	$params['cc65'] = $ord['cc65'];
    	 $params['cc66'] = $ord['cc66'];
	 $params['cc67'] = $ord['cc67'];
    	 
	$params['kk70'] = $ord['kk70'];	
	$params['kk71'] = $ord['kk71'];
     	$params['kk72'] = $ord['kk72'];
	 $params['kk73'] = $ord['kk73'];
     	$params['kk74'] = $ord['kk74'];
	$params['kk75'] = $ord['kk75'];
    	 $params['kk76'] = $ord['kk76'];
	 $params['kk77'] = $ord['kk77'];
     	$params['kk78'] = $ord['kk78'];
	$params['kk79'] = $ord['kk79'];
	$params['pp70'] = $ord['pp70'];	
	 $params['pp71'] = $ord['pp71'];
     	$params['pp72'] = $ord['pp72'];
	 $params['pp73'] = $ord['pp73'];
    	 $params['pp74'] = $ord['pp74'];
	$params['pp75'] = $ord['pp75'];
    	 $params['pp76'] = $ord['pp76'];
	 $params['pp77'] = $ord['pp77'];
    	 $params['pp78'] = $ord['pp78'];
 	$params['pp79'] = $ord['pp79'];

	$params['cc70'] = $ord['cc70'];	
	 $params['cc71'] = $ord['cc71'];
     	$params['cc72'] = $ord['cc72'];
	 $params['cc73'] = $ord['cc73'];
    	 $params['cc74'] = $ord['cc74'];
	$params['cc75'] = $ord['cc75'];
    	 $params['cc76'] = $ord['cc76'];
	 $params['cc77'] = $ord['cc77'];
    	 $params['cc78'] = $ord['cc78'];
 	$params['cc79'] = $ord['cc79'];

	$params['kk40'] = $ord['kk40'];	
	$params['kk41'] = $ord['kk41'];
     	$params['kk42'] = $ord['kk42'];
	$params['pp40'] = $ord['pp40'];	
	 $params['pp41'] = $ord['pp41'];
     	$params['pp42'] = $ord['pp42'];
	$params['cc40'] = $ord['cc40'];	
	 $params['cc41'] = $ord['cc41'];
     	$params['cc42'] = $ord['cc42'];

	$params['kk20'] = $ord['kk20'];	
	$params['kk21'] = $ord['kk21'];
     	$params['kk22'] = $ord['kk22'];
	$params['pp20'] = $ord['pp20'];	
	 $params['pp21'] = $ord['pp21'];
     	$params['pp22'] = $ord['pp22'];
	$params['cc20'] = $ord['cc20'];	
	 $params['cc21'] = $ord['cc21'];
     	$params['cc22'] = $ord['cc22'];
//0710-2012
$params['k100'] = $ord['k100'];	
	$params['k101'] = $ord['k101'];
     	$params['k102'] = $ord['k102'];
	 $params['k103'] = $ord['k103'];
     	$params['k104'] = $ord['k104'];
	$params['k105'] = $ord['k105'];
    	 $params['k106'] = $ord['k106'];
	 $params['k107'] = $ord['k107'];
     	$params['k108'] = $ord['k108'];
	$params['k109'] = $ord['k109'];
	$params['p100'] = $ord['p100'];	
	 $params['p101'] = $ord['p101'];
     	$params['p102'] = $ord['p102'];
	 $params['p103'] = $ord['p103'];
    	 $params['p104'] = $ord['p104'];
	$params['p105'] = $ord['p105'];
    	 $params['p106'] = $ord['p106'];
	 $params['p107'] = $ord['p107'];
    	 $params['p108'] = $ord['p108'];
 	$params['p109'] = $ord['p109'];
//
$params['c100'] = $ord['c100'];	
	 $params['c101'] = $ord['c101'];
     	$params['c102'] = $ord['c102'];
	 $params['c103'] = $ord['c103'];
    	 $params['c104'] = $ord['c104'];
	$params['c105'] = $ord['c105'];
    	 $params['c106'] = $ord['c106'];
	 $params['c107'] = $ord['c107'];
    	 $params['c108'] = $ord['c108'];
 	$params['c109'] = $ord['c109'];
//
	$params['k200'] = $ord['k200'];	
	$params['k201'] = $ord['k201'];
     	$params['k202'] = $ord['k202'];
	 $params['k203'] = $ord['k203'];
     	$params['k204'] = $ord['k204'];
	$params['k205'] = $ord['k205'];
    	 $params['k206'] = $ord['k206'];
	 $params['k207'] = $ord['k207'];
     	$params['k208'] = $ord['k208'];
	$params['k209'] = $ord['k209'];
	$params['p200'] = $ord['p200'];	
	 $params['p201'] = $ord['p201'];
     	$params['p202'] = $ord['p202'];
	 $params['p203'] = $ord['p203'];
    	 $params['p204'] = $ord['p204'];
	$params['p205'] = $ord['p205'];
    	 $params['p206'] = $ord['p206'];
	 $params['p207'] = $ord['p207'];
    	 $params['p208'] = $ord['p208'];
 	$params['p209'] = $ord['p209'];
//
$params['c200'] = $ord['c200'];	
	 $params['c201'] = $ord['c201'];
     	$params['c202'] = $ord['c202'];
	 $params['c203'] = $ord['c203'];
    	 $params['c204'] = $ord['c204'];
	$params['c205'] = $ord['c205'];
    	 $params['c206'] = $ord['c206'];
	 $params['c207'] = $ord['c207'];
    	 $params['c208'] = $ord['c208'];
 	$params['c209'] = $ord['c209'];
//
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
//
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
//
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
//0710-2012
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
//
	$params['k300'] = $ord['k300'];	
	$params['k301'] = $ord['k301'];
     	$params['k302'] = $ord['k302'];
	 $params['k303'] = $ord['k303'];
     	$params['k304'] = $ord['k304'];
	$params['k305'] = $ord['k305'];
    	 $params['k306'] = $ord['k306'];
	 $params['k307'] = $ord['k307'];
     	$params['k308'] = $ord['k308'];
	$params['k309'] = $ord['k309'];
	$params['p300'] = $ord['p300'];	
	 $params['p301'] = $ord['p301'];
     	$params['p302'] = $ord['p302'];
	 $params['p303'] = $ord['p303'];
    	 $params['p304'] = $ord['p304'];
	$params['p305'] = $ord['p305'];
    	 $params['p306'] = $ord['p306'];
	 $params['p307'] = $ord['p307'];
    	 $params['p308'] = $ord['p308'];
 	$params['p309'] = $ord['p309'];
//
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

	$params['k90'] = $ord['k90'];	
	$params['k91'] = $ord['k91'];
	$params['p90'] = $ord['p90'];	
	 $params['p91'] = $ord['p91'];
//
//
$params['kk90'] = $ord['kk90'];	
	$params['kk91'] = $ord['kk91'];
     	$params['kk92'] = $ord['kk92'];
	 $params['kk93'] = $ord['kk93'];
     	$params['kk94'] = $ord['kk94'];
	$params['kk95'] = $ord['kk95'];
    	 $params['kk96'] = $ord['kk96'];
	 $params['kk97'] = $ord['kk97'];
     	$params['kk98'] = $ord['kk98'];
	$params['kk99'] = $ord['kk99'];
	$params['pp90'] = $ord['pp90'];	
	 $params['pp91'] = $ord['pp91'];
     	$params['pp92'] = $ord['pp92'];
	 $params['pp93'] = $ord['pp93'];
    	 $params['pp94'] = $ord['pp94'];
	$params['pp95'] = $ord['pp95'];
    	 $params['pp96'] = $ord['pp96'];
	 $params['pp97'] = $ord['pp97'];
    	 $params['pp98'] = $ord['pp98'];
 	$params['pp99'] = $ord['pp99'];

	$params['cc90'] = $ord['cc90'];	
	 $params['cc91'] = $ord['cc91'];
     	$params['cc92'] = $ord['cc92'];
	 $params['cc93'] = $ord['cc93'];
    	 $params['cc94'] = $ord['cc94'];
	$params['cc95'] = $ord['cc95'];
    	 $params['cc96'] = $ord['cc96'];
	 $params['cc97'] = $ord['cc97'];
    	 $params['cc98'] = $ord['cc98'];
 	$params['cc99'] = $ord['cc99'];
//0720-2012
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


$params['notes'] = $ord['notes'];
$params['special_req'] = $ord['special_req'];
	
// haikaturyo
$height=$params['k100'];
if ($pat['性別']=='M'){
 $params['k202'] = (27.63 - 0.112 * $age) * $height;
 }
else {

$params['k202'] = (21.78 - 0.101 * $age) * $height;
}


	
  $idata = mx_get_install_data();
  $params['HOSPITAL_NAME'] = $idata['HOSPITAL_NAME'];
  $params['HOSPITAL_ADDR'] = $idata['HOSPITAL_ADDR'];
  $params['HOSPITAL_TEL'] = $idata['HOSPITAL_TEL'];
  $params['CORPORATION_NAME'] = $idata['CORPORATION_NAME'];
  
  $params['DOCTOR'] = $doc['lname'] .'　'.$doc['fname'];
  
   
  $params['BODY'] = "   ";

 
//0710-2012
$template = "osato.ods";
 
  $rand = rand(0,100000000);
  $pdf_path = sprintf("/tmp/%s_%d.pdf", $template, $rand);
  $params['PDF_PATH'] = $pdf_path;
  $params['TEMPLATE_DIR'] = $_SERVER['DOCUMENT_ROOT'] . '/templates/';
//0710-2012
  $params['TEMPLATE'] = "osato.ods";
	
//$params['TEMPLATE'] = "shohousen_tg10.ods";
  print ooo_print_pdf2($params);

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
    $type = '処方箋';
//    if($shots)
//      $type = '注射箋';
    $id = mx_db_insert_extdocument($db, $type, $bid,
				   $pt=NULL, $comment=NULL);
    // update record...
    // this is irregular design. SOD should not update db in normal case
    if($shots) {
      $stmt = 'UPDATE "注射処方箋" SET "PDF"=' . mx_db_sql_quote($id) .
	' WHERE "ObjectID"=' . mx_db_sql_quote($oid);
    }else{
      $stmt = 'UPDATE "薬剤処方箋" SET "PDF"=' . mx_db_sql_quote($id) .
	' WHERE "ObjectID"=' . mx_db_sql_quote($oid);
    }
    pg_query($db, $stmt);
    //HACK: open window and show PDF for client-side printing
    print '
<SCRIPT LANGUAGE="JavaScript">
 window.open("/blobmedia.php/' . $id .
      '/generated.pdf","","width=640,height=640");
</SCRIPT>';
    
  }else{
    print "PDFの生成に失敗しました";
  }


}

?>
