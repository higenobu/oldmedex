CREATE OR REPLACE FUNCTION vital()
  RETURNS trigger AS
$BODY$
<<update_vital>>
    DECLARE

        d_pt        bigint;
	d_predate   date;
        d_date      date;
        d_plandate date;
       
        
         d_sup date;
       
       ota_sum   "バイタルデータ表"%ROWTYPE;

       
    BEGIN 



        IF (TG_OP = 'INSERT' OR TG_OP='UPDATE') THEN 

            d_pt := NEW."患者";
            d_date := NEW."日付";
            d_plandate := NEW."日付";
              
		d_sup := NEW."Superseded";


        	IF d_date  is  not null  and d_sup is null THEN

 		delete from otatest_order where d_pt=patient and order_date is null;
 
	update otatest_order
	set k100=NEW."身長",
	k101=NEW."体重",
	k300=NEW."血圧(上)",
	k301=NEW."血圧(下)",
	k302=NEW."脈拍"
	where    patient=d_pt and   order_date=d_date;   
  

        RETURN null;
   
	EXIT update_vital;
        END IF;
         
         
   	
         

        	

		
         
         END IF;
   
  	RETURN null;
     
      END 

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
 
