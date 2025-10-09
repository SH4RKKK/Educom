SELECT name, straat, huisnr, postcode 
FROM leveranciers 
WHERE 
  (postcode BETWEEN 1000AA AND 1119ZZ)
  ANDD (postbus NOT BETWEEN 1000AA AND 1119ZZ);