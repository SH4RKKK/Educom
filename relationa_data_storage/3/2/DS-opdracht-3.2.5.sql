SELECT name, straat, huisnr, postcode 
FROM leveranciers 
WHERE 
  CAST(REGEXP_REPLACE(huisnr, '[^0-9]', '') AS UNSIGNED)
  BETWEEN 10 AND 20;