SELECT s.name, s.straat, s.huisnr, s.postcode, c.name
FROM mhl_suppliers AS s
JOIN mhl_cities AS c ON s.city_id = c.id
JOIN mhl_communes AS g ON c.commune_ID = g.id AND g.name = 'Steenwijkerland';