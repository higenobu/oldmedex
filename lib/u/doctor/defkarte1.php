<?php





$__karte_all_cols = array(

//array('Column' => 'CreatedBy','Draw' => NULL,'Option' => array('noedit' => 1),),
array('Column' => '����','Draw' => NULL,'Option' => array('noedit' => 1, 'nodisp' => 1),),
	  
 
 
	    
array('Column' => '����','Label' => 'Karte������','Draw' => 'date','Option' => array('list' => 1,'size'=>10),),
	   
 


array('Column' => 'P',
	      'Label' => '����������',
	      'Draw' => 'textarea',
	      'Option' => array('vocab' => array('P'),
				'cols' =>100,'list' => 1,'rows'=>40),
	      ),
				   
 
	
 
array('Column' => 'O0',
	      'Label' => '�����',
	      'Draw' => 'textarea',
	      'Option' => array('vocab' => array('S1'),
			'cols' => 100,'list' => 1,'rows'=>40),
	      ),
array('Column' => 'D',
	      'Label' => '����',
	      'Draw' => 'textarea',
	      'Option' => array('vocab' => array('D'),
			'cols' => 100,'list' => 1,'rows'=>20),
	      ),
array('Column' => 'recorded',
'Label' => '��Ͽ', 'Draw' => 'timestamp'),


array('Column' => "I1", 'Label' =>"����", 'Draw' => "extdocument",
			      'Option' =>array('img' => 'always',
			      'Extdocument' => '����')),
array('Column' => "I2", 'Label' =>"����", 'Draw' => "extdocument",
			      'Option' =>array('img' => 'always','OmitIfEmpty' => array('I1'),
			      'Extdocument' => '����')),

array('Column' => "I3", 'Label' =>"����", 'Draw' => "extdocument",
			      'Option' =>array('img' => 'always','OmitIfEmpty' => array('I2'),
			      'Extdocument' => '����')),
 
array('Column' => "I4", 'Label' =>"����", 'Draw' => "extdocument",
			      'Option' =>array('img' => 'always','OmitIfEmpty' => array('I3'),
			      'Extdocument' => '����')),
array('Column' => "I5", 'Label' =>"����", 'Draw' => "extdocument",
			      'Option' =>array('img' => 'always','OmitIfEmpty' => array('I4'),
			      'Extdocument' => '����')),
 
array('Column' => "I6", 'Label' =>"����", 'Draw' => "extdocument",
			      'Option' =>array('img' => 'always','OmitIfEmpty' => array('I5'),
			      'Extdocument' => '����'))			
 

			
);


?>
