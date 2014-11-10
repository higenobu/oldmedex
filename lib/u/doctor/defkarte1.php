<?php





$__karte_all_cols = array(

//array('Column' => 'CreatedBy','Draw' => NULL,'Option' => array('noedit' => 1),),
array('Column' => '´µ¼Ô','Draw' => NULL,'Option' => array('noedit' => 1, 'nodisp' => 1),),
	  
 
 
	    
array('Column' => 'ÆüÉÕ','Label' => 'KarteºîÀ®Æü','Draw' => 'date','Option' => array('list' => 1,'size'=>10),),
	   
 


array('Column' => 'P',
	      'Label' => '¥ª¡¼¥ÀÆâÍÆ',
	      'Draw' => 'textarea',
	      'Option' => array('vocab' => array('P'),
				'cols' =>100,'list' => 1,'rows'=>40),
	      ),
				   
 
	
 
array('Column' => 'O0',
	      'Label' => '¥«¥ë¥Æ',
	      'Draw' => 'textarea',
	      'Option' => array('vocab' => array('S1'),
			'cols' => 100,'list' => 1,'rows'=>40),
	      ),
array('Column' => 'D',
	      'Label' => 'È÷¹Í',
	      'Draw' => 'textarea',
	      'Option' => array('vocab' => array('D'),
			'cols' => 100,'list' => 1,'rows'=>20),
	      ),
array('Column' => 'recorded',
'Label' => 'µ­Ï¿', 'Draw' => 'timestamp'),


array('Column' => "I1", 'Label' =>"²èÁü", 'Draw' => "extdocument",
			      'Option' =>array('img' => 'always',
			      'Extdocument' => '²èÁü')),
array('Column' => "I2", 'Label' =>"²èÁü", 'Draw' => "extdocument",
			      'Option' =>array('img' => 'always','OmitIfEmpty' => array('I1'),
			      'Extdocument' => '²èÁü')),

array('Column' => "I3", 'Label' =>"²èÁü", 'Draw' => "extdocument",
			      'Option' =>array('img' => 'always','OmitIfEmpty' => array('I2'),
			      'Extdocument' => '²èÁü')),
 
array('Column' => "I4", 'Label' =>"²èÁü", 'Draw' => "extdocument",
			      'Option' =>array('img' => 'always','OmitIfEmpty' => array('I3'),
			      'Extdocument' => '²èÁü')),
array('Column' => "I5", 'Label' =>"²èÁü", 'Draw' => "extdocument",
			      'Option' =>array('img' => 'always','OmitIfEmpty' => array('I4'),
			      'Extdocument' => '²èÁü')),
 
array('Column' => "I6", 'Label' =>"²èÁü", 'Draw' => "extdocument",
			      'Option' =>array('img' => 'always','OmitIfEmpty' => array('I5'),
			      'Extdocument' => '²èÁü'))			
 

			
);


?>
