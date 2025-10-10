SELECT h.hitcount, s.name, s.straat, s.huisnr, s.postcode, c.name, g.name, p.name 
FROM mhl_hitcount AS h
JOIN mhl_suppliers AS s ON h.supplier_ID = s.id
JOIN mhl_cities AS c ON s.city_id = c.id
JOIN mhl_communes AS g ON c.commune_ID = g.id
JOIN mhl_districts AS p ON g.district_ID = p.id AND p.name IN ('Noord-Brabant', 'Limburg', 'Zeeland')
WHERE h.year = 2014 AND h.month = 1;