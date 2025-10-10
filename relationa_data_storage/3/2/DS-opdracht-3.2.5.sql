SELECT name, straat, huisnr, postcode 
FROM mhl_suppliers
WHERE
  CAST(REGEXP_SUBSTR(huisnr, '^[0-9]+') AS UNSIGNED)
  BETWEEN 10 AND 20;