CREATE TABLE wk_tensu
(
  srycd character(9),
  "name" character varying(200),
  kananame character varying(200)
)
WITH (
  OIDS=FALSE
);


copy wk_tensu from '/tmp/wk-tensu' with delimiter ';';

insert into wkmedis select * from    "Medis医薬品マスター"  





update  "Medis医薬品マスター" 
   SET 
       "病院使用医薬品名"=  
       
 (select "kananame" from  wk_tensu  where  srycd="レセプト電算処理システムコード（１）" limit 1)
  
where "レセプト電算処理システムコード（１）"='661110027';





update  "Medis医薬品マスター"
   SET 
       "kananame"=  
       
 (select "kananame" from  wk_tensu  where  srycd="レセプト電算処理システムコード（１）" limit 1)
  



