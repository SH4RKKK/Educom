SELECT name, straat, huisnr, postcode 
FROM mhl_suppliers
WHERE membertype IN (
    1,
    2,
    3,
    8
);