SELECT s.name, s.straat, s.huisnr, s.postcode
FROM mhl_yn_properties AS p
JOIN mhl_suppliers AS s ON p.supplier_ID=s.id
JOIN mhl_propertytypes AS pt ON p.propertytype_ID=pt.id AND pt.name IN('specialistische leverancier', 'ook voor particulieren');