SELECT name, straat, huisnr, postcode 
FROM leveranciers 
WHERE 
  (postcode BETWEEN 1000AA AND 1119ZZ)
  OR (postbus BETWEEN 2491AA AND 2597ZZ);