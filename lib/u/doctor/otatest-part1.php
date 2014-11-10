<?
//DISPLAY
$__otatest2_order_cfg['D_RANDOM_LAYOUT'] = array(

	array('Label' => '検査日'),
 
 	array('Column' => 'order_date','Span' => 1),
	array('Label' => ''),
	array('Column' => 'preorderdate','Span'=>1),
	array('Column' => 'shiji',
					'Label' => 'doctor',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_ota_kiroku2()

				       ), 
	array('Insn' => '//'),
	
 
	array('Insn' => '//'),
	array('Label' =>  '****'),
	
	array('Insn' => '//'),
	array('Label' =>  '身長'),
	array('Column' => 'k100', 'Option' => array('size' => 5)),
	 
	array('Label' => ''),
	array('Column' => 'p100'),
	array('Column' => 'aa51','Label' => '','Span' => 1),
	array('Insn' => '//'),	
	array('Label' => '体重'),
	array('Column' => 'k101','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p101'),
	array('Column' => 'aa52','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => '肥満度'),
	array('Column' => 'k1006','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p1006'),
	array('Column' => 'aa1006','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => 'BMI'),
	array('Column' => 'k1007','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p1007'),
	array('Column' => 'aa1007','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => '腹囲'),
	array('Column' => 'k105','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p105'),
	array('Column' => 'aa53','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => '体脂肪率'),
	array('Column' => 'k1005','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p1005'),
	array('Column' => 'aa1005','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' =>  '身体計測'),
	array('Column' => 'k401', 'Span' => 2),
	array('Insn' => '//'),

	array('Label' => ''),
	array('Column' => 'cc401','Span' => 5),
	array('Insn' => '//'),
	array('Label' =>  '****'),	
	
	array('Insn' => '//'),
	array('Label' =>  '血圧（上）'),
	array('Column' => 'k300', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p300', 'Span' => 1),
	array('Column' => 'aa56','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => '血圧（下）'),
	array('Column' => 'k301', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p301', 'Span' => 1),
	array('Column' => 'aa57','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => '心拍数'),
	array('Column' => 'k302', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p302', 'Span' => 1),
	array('Column' => 'aa58','Label' => '','Span' => 1),
	array('Insn' => '//'),

	array('Label' =>  '胸部(心臓)X線'),
	array('Column' => 'k501', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p501'),
	
	array('Label' => ''),
	array('Column' => 'c501','Span' => 3),
	
	array('Insn' => '//'),
  

	array('Label' =>  '安静時心電図'),
	array('Column' => 'k502', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p502'),
	
	array('Label' => ''),
	array('Column' => 'c502','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  '大動脈超音波'),
	array('Column' => 'k503', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p503'),
	
	array('Label' => ''),
	array('Column' => 'c503','Span' => 3),
//0510-2013
	array('Insn' => '//'),
	array('Label' =>  '頚動脈超音波'),
	array('Column' => 'k1002', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1002'),
	
	array('Label' => ''),
	array('Column' => 'c1002','Span' => 3),	
	array('Insn' => '//'),
 
	array('Label' =>  '循環器系判定'),
	array('Column' => 'k403', 'Span' => 2),
//	array('Insn' => '  ', 'Span' => 1),
	array('Insn' => '//'),
	array('Label' => ''),

	array('Column' => 'cc403','Span' => 5),

	array('Insn' => '//'),
	array('Label' =>  '****'),	
	
	array('Insn' => '//'),	
	array('Label' =>  '肺機能検査'),
	array('Column' => 'k507', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p507'),
	
	array('Label' => ''),
	array('Column' => 'c507','Span' => 3),
	
	array('Insn' => '//'),
	
	array('Label' =>  '肺活量'),
	array('Column' => 'k200', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p200', 'Span' => 1),
	array('Column' => 'aa54','Label' => '','Span' => 1),
	array('Insn' => '//'),
 //
 
	
	array('Label' =>  '予想肺活量'),
	array('Column' => 'k103', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p103', 'Span' => 1),
//	array('Column' => 'aa103','Label' => '','Span' => 1),
	array('Insn' => '//'),
 	array('Label' =>  '%肺活量'),
	array('Column' => 'k104', 'Span' => 1),
	array('Label' => ''),
//	array('Column' => 'p104', 'Span' => 1),
//	array('Column' => 'aa104','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => '一秒量'),
	array('Column' => 'k203', 'Span' => 1),
 	array('Label' => ''),
	array('Column' => 'p203', 'Span' => 1),
	array('Column' => 'aa55','Label' => '','Span' => 1),
	array('Insn' => '//'),
 
	
	array('Label' =>  '一秒率'),
	array('Column' => 'k106', 'Span' => 1),
	array('Label' => ''),
 	array('Column' => 'p106', 'Span' => 1),
//	array('Column' => 'aa106','Label' => '','Span' => 1),
	array('Insn' => '//'),
//0315-2013
	array('Insn' => '//'),
	array('Label' =>  '胸部X線'),
	array('Column' => 'k500', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p500'),
	
	array('Label' => ''),
	array('Column' => 'c500','Span' => 3),
 
	array('Insn' => '//'),
	array('Label' =>  '呼吸器系判定'),
	array('Column' => 'k402', 'Span' => 2),
	array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc402','Span' => 5),	
	
	array('Insn' => '//'),

 
	array('Insn' => '//'),
	array('Label' =>  '****'),	
	
	array('Insn' => '//'),

 
//1111-2013
	array('Label' =>  '矯正（右）'),
	array('Column' => 'k80', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p80'),
	array('Column' => 'aa80','Label' => '','Span' => 1), 

	array('Insn' => '//'),
	array('Label' =>  '矯正（左）'),
	array('Column' => 'k81', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p81'),
	array('Column' => 'aa81','Label' => '','Span' => 1), 
	
	array('Insn' => '//'),
//0501-2013
	array('Insn' => '//'),
	array('Label' =>  '裸眼（右）'),
	array('Column' => 'k1000', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1000'),
	array('Column' => 'aa1000','Label' => '','Span' => 1), 
	array('Insn' => '//'),
	array('Label' =>  '裸眼（左）'),
	array('Column' => 'k1001', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1001'),
	array('Column' => 'aa1001','Label' => '','Span' => 1), 
	array('Insn' => '//'),
	array('Label' =>  '眼科判定'),
	array('Column' => 'k414', 'Span' => 2),
	array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc414','Span' => 5),
	
	array('Insn' => '//'),

	array('Insn' => '//'),
	array('Label' =>  '****'),	
	
	array('Insn' => '//'),
	
	 
	array('Label' =>  '500Hz（右）'),
	array('Column' => 'k82', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p82'),
	array('Column' => 'aa82','Label' => '','Span' => 1), 
	array('Insn' => '//'),
	array('Label' =>  '500Hz（左）'),
	array('Column' => 'k83', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p83'),
	array('Column' => 'aa83','Label' => '','Span' => 1), 	
	array('Insn' => '//'),
	array('Label' =>  '1000Hz（右）'),
	array('Column' => 'k84', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p84'),
	array('Column' => 'aa84','Label' => '','Span' => 1), 
	array('Insn' => '//'),
	array('Label' =>  '1000Hz（左）'),
	array('Column' => 'k85', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p85'),
	array('Column' => 'aa85','Label' => '','Span' => 1), 
	
	array('Insn' => '//'),
	array('Label' =>  '2000Hz（右）'),
	array('Column' => 'k86', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p86'),
	array('Column' => 'aa86','Label' => '','Span' => 1), 
	array('Insn' => '//'),
	array('Label' =>  '2000Hz（左）'),
	array('Column' => 'k87', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p87'),
	array('Column' => 'aa87','Label' => '','Span' => 1), 
	
	array('Insn' => '//'),
	array('Label' =>  '4000Hz（右）'),
	array('Column' => 'k88', 'Span' => 1),
	array('Label' => ''),

	array('Column' => 'p88'),
	array('Column' => 'aa88','Label' => '','Span' => 1), 
	array('Insn' => '//'),
	array('Label' =>  '4000Hz（左）'),
	array('Column' => 'k89', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p89'),
	array('Column' => 'aa89','Label' => '','Span' => 1), 
	
	array('Insn' => '//'),
	array('Label' =>  '8000Hz（右）'),
	array('Column' => 'k90', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p90'),
	array('Column' => 'aa90','Label' => '','Span' => 1), 
	array('Insn' => '//'),
	array('Label' =>  '8000Hz（左）'),
	array('Column' => 'k91', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p91'),
	array('Column' => 'aa91','Label' => '','Span' => 1), 
	array('Insn' => '//'),
	array('Label' =>  '聴力判定'),
	array('Column' => 'k410', 'Span' => 2),
	array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc410','Span' => 5),
	
	array('Insn' => '//'),
	array('Insn' => '//'),
	array('Label' =>  '****'),	
	
	array('Insn' => '//'),
	array('Label' =>  '血液型ABO式'),
	array('Column' => 'kk70', 'Span' => 1),
	array('Insn' => '//'),	

	array('Label' =>  '白血球数'),
	array('Column' => 'kk71', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp71'),
	array('Column' => 'aa38','Label' => '','Span' => 1),
 	
	 
	array('Insn' => '//'),
	array('Label' =>  '赤血球数'),
	array('Column' => 'kk72',
	      
	       'Span' =>1),

	array('Label' => ''),
	array('Column' => 'pp72'),
	array('Column' => 'aa39','Label' => '','Span' => 1),
 
	
	 
	array('Insn' => '//'),
	
	array('Label' => '血色素量'),
	array('Column' => 'kk73','Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp73'),
	array('Column' => 'aa40','Label' => '','Span' => 1),
 
	
	 
	array('Insn' => '//'),
	array('Label' => 'ヘマトクリット'),
	array('Column' => 'kk74',
	      
	       'Span' => 1),

	array('Label' => ''),
	array('Column' => 'pp74'),
	array('Column' => 'aa41','Label' => '','Span' => 1), 
//	array('Label' => ''),
//	array('Column' => 'cc74','Span' => 3),
	
	 
	array('Insn' => '//'),
	array('Label' => 'Rh式'),
	array('Column' => 'kk75','Span' => 1),
	
	array('Insn' => '//'),
	
//
	array('Label' =>  'MCV'),
	array('Column' => 'kk76', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp76'),
	 array('Column' => 'aa42','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc76','Span' => 3),
	
	 
	array('Insn' => '//'),
	array('Label' =>  'MCH'),
	array('Column' => 'kk77',
	      
	       'Span' =>1),
	array('Label' => ''),
	array('Column' => 'pp77'),
	array('Column' => 'aa43','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc77','Span' => 3),
	
	 
	array('Insn' => '//'),
	
	array('Label' => 'MCHC'),
	array('Column' => 'kk78',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp78'),
	array('Column' => 'aa44','Label' => '','Span' => 1), 
//	array('Label' => ''),
//	array('Column' => 'cc78','Span' => 3),
	
	 
	array('Insn' => '//'),
//
	
	array('Label' => '血小板数'),
	array('Column' => 'kk79',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp79'),
	 array('Column' => 'aa45','Label' => '','Span' => 1),
 
	 
	array('Insn' => '//'), 
	array('Label' =>  '血液一般判定'),
	array('Column' => 'k409', 'Span' => 2),
	array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc409','Span' => 5),
	
	array('Insn' => '//'),
	array('Label' =>  '****'),	
	
	array('Insn' => '//'),

	array('Label' =>  '空腹時血糖'),
	array('Column' => 'kk50', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp50'),
	array('Column' => 'aa20','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc50','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  '空腹時尿糖'),
	array('Column' => 'kk51', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp51'),
	array('Column' => 'aa21','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc51','Span' => 3),
	
	array('Insn' => '//'),



	array('Label' =>  'HbA1C'),
	array('Column' => 'kk52', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp52'),
	array('Column' => 'aa22','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc52','Span' => 3),
	array('Insn' => '//'),

	array('Label' =>  '糖代謝判定'),
	array('Column' => 'k407', 'Span' => 2),
	array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc407','Span' => 5),
	
	array('Insn' => '//'),
	array('Label' =>  '****'),	
	
	array('Insn' => '//'),
	array('Label' =>  '総コレステロール'),
	array('Column' => 'kk10', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp10'),
	array('Column' => 'aa1','Label' => '','Span' => 1),
	      
	     
//	array('Label' => ''),
//	array('Column' => 'cc10','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  '中性脂肪'),
	array('Column' => 'kk13', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp13'),
	array('Column' => 'aa4','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc13','Span' => 3),
	
	array('Insn' => '//'),

	array('Label' =>  'HDL(善玉) ｺﾚｽﾃﾛｰﾙ'),
	array('Column' => 'kk11', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp11'),
	array('Column' => 'aa2','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc11','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  'VLDL (悪玉) ｺﾚｽﾃﾛｰﾙ'),
	array('Column' => 'kk12', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp12'),
	array('Column' => 'aa3','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc12','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  'LDL(悪玉) ｺﾚｽﾃﾛｰﾙ'),
	array('Column' => 'kk14', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp14'),
	array('Column' => 'aa5','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc14','Span' => 3),
//0510-2013
	array('Insn' => '//'),
	array('Label' =>  'CHOL/HDL 比'),
	array('Column' => 'k1003', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1003'),
	array('Column' => 'aa1003','Label' => '','Span' => 1),
//0510-2013
	
	array('Insn' => '//'),

	array('Label' =>  '脂質代謝判定'),
	array('Column' => 'k404', 'Span' => 2),
	
	array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc404','Span' =>5),
	array('Insn' => '//'),

	array('Label' =>  'アミラーゼ'),
	array('Column' => 'kk21', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp21'),
	array('Column' => 'aa7','Label' => '','Span' => 1),

 	
	array('Insn' => '//'),
	array('Label' =>  '超音波検査'),
	array('Column' => 'k519', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p519'),
	array('Label' => ''),
	array('Column' => 'c519','Span' => 3),
	array('Insn' => '//'),

	array('Label' =>  '膵臓器系判定'),
	array('Column' => 'k405', 'Span' => 2),
	array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc405','Span' => 5),
	
	array('Insn' => '//'),
 
	array('Insn' => '//'),
	array('Label' =>  '****'),	
	
	array('Insn' => '//'),
	array('Label' =>  '総蛋白'),
	array('Column' => 'kk30', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp30'),
	array('Column' => 'aa8','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc30','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  'ｱﾙﾌﾞﾐﾝ'),
	array('Column' => 'kk31', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp31'),
	array('Column' => 'aa9','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc31','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  'A/G比'),
	array('Column' => 'kk32', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp32'),
	array('Column' => 'aa10','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc32','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'GOT'),
	array('Column' => 'kk33', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp33'),
	array('Column' => 'aa11','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc33','Span' => 3),
	
	array('Insn' => '//'),

	array('Label' =>  'GPT'),
	array('Column' => 'kk34', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp34'),
	array('Column' => 'aa12','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc34','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  'γ-GTP'),
	array('Column' => 'kk35', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp35'),
	array('Column' => 'aa13','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc35','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  'LDH'),
	array('Column' => 'kk36', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp36'),
	array('Column' => 'aa14','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc36','Span' => 3),
	
	array('Insn' => '//'),
//
	array('Label' =>  '総ﾋﾞﾘﾙﾋﾞﾝ'),
	array('Column' => 'kk37', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp37'),
	array('Column' => 'aa15','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc37','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  'ALP'),
	array('Column' => 'kk38', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp38'),
	array('Column' => 'aa16','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc38','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  'ＨＢｓ抗原'),
	array('Column' => 'kk39', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp39'),
	array('Column' => 'aa17','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc39','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  'ＨＢｓ抗体'),
	array('Column' => 'kk40', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp40'),
	array('Column' => 'aa18','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc40','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  'C型肝炎抗体'),
	array('Column' => 'kk41', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp41'),
	array('Column' => 'aa19','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc41','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  '超音波検査'),
	array('Column' => 'k520', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p520'),
	
	array('Label' => ''),
	array('Column' => 'c520','Span' => 3),
	
	array('Insn' => '//'),
 
	array('Label' =>  '肝胆のう判定'),
	array('Column' => 'k406', 'Span' => 2),
	array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc406','Span' => 5),
	
	array('Insn' => '//'),
//
	array('Insn' => '//'),
	array('Label' =>  '****'),	
	
	array('Insn' => '//'),
	array('Label' =>  '尿一般　蛋白'),
	array('Column' => 'kk64', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp64'),
	array('Column' => 'aa23','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc64','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  '糖'),
	array('Column' => 'kk65', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp65'),
	array('Column' => 'aa24','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc65','Span' => 3),
	
	array('Insn' => '//'),
//
	array('Label' =>  'ケトン体'),
	array('Column' => 'kk66', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp66'),
	array('Column' => 'aa25','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc66','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  '潜血'),
	array('Column' => 'kk67', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp67'),
	array('Column' => 'aa26','Label' => '','Span' => 1),
 
 	 
 
	
	array('Insn' => '//'),
	array('Label' =>  '(尿沈渣）赤血球'),
	array('Column' => 'kk60', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp60'),
	array('Column' => 'aa31','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc60','Span' => 3),
	
	array('Insn' => '//'),

	array('Label' =>  '(尿）白血球'),
	array('Column' => 'kk61', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp61'),
	array('Column' => 'aa32','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc61','Span' => 3),
	
	array('Insn' => '//'),

	array('Label' =>  '(尿）細菌'),
	array('Column' => 'kk62', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp62'),
	array('Column' => 'aa33','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc62','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  'Na'),
	array('Column' => 'kk53', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp53'),
	array('Column' => 'aa27','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc53','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  'K'),
	array('Column' => 'kk54', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp54'),
	array('Column' => 'aa28','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc54','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  'Cl'),
	array('Column' => 'kk55', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp55'),
	array('Column' => 'aa29','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc55','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  'CO2'),
	array('Column' => 'kk56', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp56'),
	array('Column' => 'aa30','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc56','Span' => 3),
	
	array('Insn' => '//'),

 
	array('Label' =>  '尿素窒素'),
	array('Column' => 'kk57', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp57'),
	array('Column' => 'aa35','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc57','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  'クレアチニン'),
	array('Column' => 'kk58', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp58'),
	array('Column' => 'aa36','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc58','Span' => 3),
	
	array('Insn' => '//'),

	array('Label' =>  '超音波検査'),
	array('Column' => 'k510', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p510'),
	
	array('Label' => ''),
	array('Column' => 'c510','Span' => 3),
	
	array('Insn' => '//'),
 	array('Label' =>  '腎機能判定'),
	array('Column' => 'k408', 'Span' => 2),
	array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc408','Span' => 5),
	
	array('Insn' => '//'),

	array('Insn' => '//'),
	array('Label' =>  '****'),	
	
	array('Insn' => '//'),
	array('Label' =>  '尿酸（痛風）'),
	array('Column' => 'kk59', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp59'),
	array('Column' => 'aa37','Label' => '','Span' => 1),
 
	
	array('Insn' => '//'),

	array('Label' =>  '痛風判定'),
	array('Column' => 'k417', 'Span' => 2),
	array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc417','Span' => 5),
	array('Insn' => '//'),

	array('Insn' => '//'),
	array('Label' =>  '****'),	
	
	array('Insn' => '//'),
 

	array('Label' =>  '触診'),
	array('Column' => 'k511', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p511'),
	
	array('Label' => ''),
	array('Column' => 'c511','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  'TSH'),
	array('Column' => 'kk92', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp92'),
	array('Column' => 'aa46','Label' => '','Span' => 1),
 
	array('Insn' => '//'),
	array('Label' =>  'FreeT4'),
	array('Column' => 'kk93', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp93'),
	array('Column' => 'aa47','Label' => '','Span' => 1),

	array('Insn' => '//'),
	array('Label' =>  '甲状腺検査'),
	array('Column' => 'k411', 'Span' => 2),
	array('Insn' => '//'),
 	array('Label' => ''),
 	array('Column' => 'cc411','Span' => 5),
	
 
	array('Insn' => '//'),
	array('Label' =>  '****'),	
	
	array('Insn' => '//'),
	array('Label' =>  '便潜血（免疫法)'),
	array('Column' => 'kk15', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp15'),
	array('Column' => 'aa6','Label' => '','Span' => 1),
 
	
	array('Insn' => '//'),
	array('Label' =>  '胃Ｘ線透視検査'),
	array('Column' => 'k504', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p504'),
	
	array('Label' => ''),
	array('Column' => 'c504','Span' => 3),
	
	array('Insn' => '//'),


	array('Label' =>  'ピロリ菌呼気検査'),
	array('Column' => 'k505', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p505'),
	
	array('Label' => ''),
	array('Column' => 'c505','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  '上部消化器内視鏡検査'),
	array('Column' => 'k506', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p506'),
	
	array('Label' => ''),
	array('Column' => 'c506','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  '下部消化器内視鏡検査'),
	array('Column' => 'k508', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p508'),
	
	array('Label' => ''),
	array('Column' => 'c508','Span' => 3),
	
	array('Insn' => '//'),

	array('Label' =>  'カプセル小腸内視鏡検査'),
	array('Column' => 'k509', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p509'),
	
	array('Label' => ''),
	array('Column' => 'c509','Span' => 3),
	
	array('Insn' => '//'),

	array('Label' =>  '消化器系判定'),
	array('Column' => 'k415', 'Span' => 1),
	array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc415','Span' =>5),
	
	array('Insn' => '//'),
	array('Insn' => '//'),
	array('Label' =>  '****'),	
	
	array('Insn' => '//'), 
	array('Label' =>  '乳がん検査'),
	array('Column' => 'k412', 'Span' => 2),
	array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc412','Span' => 5),
	array('Insn' => '//'),

	array('Label' =>  '触診'),
	array('Column' => 'k512', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p512'),
	
	array('Label' => ''),
	array('Column' => 'c512','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  'ﾏﾝﾓｸﾞﾗﾌｨｰ'),
	array('Column' => 'k513', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p513'),
	
	array('Label' => ''),
	array('Column' => 'c513','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  '超音波検査'),
	array('Column' => 'k514', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p514'),
	
	array('Label' => ''),
	array('Column' => 'c514','Span' => 3),
	
	array('Insn' => '//'),
//fujinnka
  

		

	array('Label' =>  '診察所見'),
	array('Column' => 'k515', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p515'),
	
	array('Label' => ''),
	array('Column' => 'c515','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  'CA125'),
	array('Column' => 'kk94', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp94'),
	array('Column' => 'aa48','Label' => '','Span' => 1),
 
	array('Insn' => '//'),
	array('Label' =>  '子宮細胞診:頚部'),
	array('Column' => 'k516', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p516'),
	
	array('Label' => ''),
	array('Column' => 'c516','Span' => 3),
	
	array('Insn' => '//'),
 
 
	array('Label' =>  '婦人科検査'),
	array('Column' => 'k413', 'Span' => 2),
	array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc413','Span' => 5),
	array('Insn' => '//'),
 
 
	array('Insn' => '//'),
	array('Insn' => '//'),
	array('Label' =>  '****'),	
	
	array('Insn' => '//'),
	array('Label' =>  '直腸診'),
	array('Column' => 'k418', 'Span' => 2),
	array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc418','Span' => 5),
	
	array('Insn' => '//'),
	array('Label' =>  '触診'),
	array('Column' => 'k517', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p517'),
	
	array('Label' => ''),
	array('Column' => 'c517','Span' => 3),
	
	array('Insn' => '//'),
//zenritsu
 
	array('Insn' => '//'),

	array('Insn' => '//'),
	array('Label' =>  '****'),	
	
	array('Insn' => '//'),
	array('Label' =>  '前立腺検査'),
	array('Column' => 'k416', 'Span' => 2),
	array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc416','Span' => 5),
	array('Insn' => '//'),	

	array('Label' =>  '触診'),
	array('Column' => 'k518', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p518'),
	
	array('Label' => ''),
	array('Column' => 'c518','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  'PSA'),
	array('Column' => 'kk95', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp95'),
	array('Column' => 'aa49','Label' => '','Span' => 1),
 
	array('Insn' => '//'),
//k560 for mri.mra
	array('Insn' => '//'),
	array('Label' =>  '****'),	
	
	array('Insn' => '//'),
/* for LCM added
array('Label' =>  'MRI/MRA'),
	 
	array('Column' => 'k560', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p560'),
	
	array('Label' => ''),
	array('Column' => 'c560','Span' => 3),
array('Insn' => '//'),

//k561 for kotumitudo
array('Insn' => '//'),
array('Label' =>  '骨密度検査'),
	 
	array('Column' => 'k561', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p561'),
	
	array('Label' => ''),
	array('Column' => 'c561','Span' => 3),
array('Insn' => '//'),
*/

//yobi etc
	array('Insn' => '//'),
	array('Label' =>  '****'),	
	
	array('Insn' => '//'),
	array('Label' =>  ''),
	array('Column' => 'kk540', 'Span' => 1),
	array('Column' => 'kk530', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp530'),
	
	array('Label' => ''),
	array('Column' => 'cc530','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  ''),
	array('Column' => 'kk541', 'Span' => 1),
	array('Column' => 'kk531', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp531'),
	
	array('Label' => ''),
	array('Column' => 'cc531','Span' => 3),
	
	array('Insn' => '//'),

	array('Label' =>  ''),
	array('Column' => 'kk542', 'Span' => 1),
	array('Column' => 'kk532', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp532'),
	
	array('Label' => ''),
	array('Column' => 'cc532','Span' => 3),
	
	
 
	array('Insn' => '//'),
	array('Label' =>  '****'),	
	
	array('Insn' => '//'),
	array('Insn' => '//'),
	array('Insn' => '//'),
	array('Label' => '総合結果.指示事項'),
	array('Column' => 'special_req', 'Span' => 5),
	array('Insn' => '//'),

	array('Label' => '備考'),
	array('Column' => 'notes', 'Span' =>5),
	array('Insn' => '//'),

);
?>


