SELECT 
    s.name leverancier,
    COALESCE(c.name, 't.a.v. de directie') aanhef,
    IF(COALESCE(s.p_address, '') != '', s.p_address, CONCAT(s.straat, ' ', s.huisnr)) adres,
    IF(COALESCE(s.p_address, '') != '', s.p_postcode, s.postcode) postcode,
    city.name stad,
    provincie.name provincie
FROM mhl_suppliers s

-- Need to subquery to get all directors for each supplier
LEFT JOIN (
    SELECT 
        c.supplier_id,
        c.name,
        c.department
    FROM mhl_contacts c
    WHERE c.department = 3 OR c.contacttype LIKE '%direct%'
) c ON c.supplier_id = s.id

LEFT JOIN mhl_departments d ON c.department = d.id

-- IFNULL value null get replacement, NULLIF return NULL if value is the same else left value
-- Basically because city id and p city id exist you have to check if p address is empty to select correct city id
LEFT JOIN mhl_cities city ON city.id = IFNULL(NULLIF(s.p_city_id * (s.p_address IS NOT NULL AND s.p_address != ''), 0), s.city_id)
LEFT JOIN mhl_communes gemeente ON city.commune_id = gemeente.id
LEFT JOIN mhl_districts provincie ON gemeente.district_id = provincie.id

ORDER BY provincie, stad, leverancier;