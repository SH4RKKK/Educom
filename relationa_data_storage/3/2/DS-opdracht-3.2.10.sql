SELECT name, straat, huisnr, postcode 
FROM leveranciers 
WHERE 
  name REGEXP '&[^\\s]*;';