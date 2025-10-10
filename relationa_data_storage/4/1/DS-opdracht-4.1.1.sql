SELECT s.name, s.straat, s.huisnr, s.postcode 
FROM mhl_suppliers AS s
JOIN mhl_cities AS c ON s.city_id = c.id AND c.name = 'Amsterdam';