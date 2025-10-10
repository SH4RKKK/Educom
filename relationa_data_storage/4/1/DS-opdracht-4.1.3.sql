SELECT s.name, s.straat, s.huisnr, s.postcode, r.name
FROM mhl_suppliers_mhl_rubriek_view AS rv
JOIN mhl_suppliers AS s ON rv.mhl_suppliers_ID=s.id
JOIN mhl_rubrieken AS r ON rv.mhl_rubriek_view_ID=r.id
LEFT JOIN mhl_rubrieken AS pr ON r.parent = pr.id
JOIN mhl_cities AS c ON s.city_id = c.id AND c.name = 'Amsterdam'
WHERE r.name="drank" or pr.name="drank"
ORDER by r.name, s.name