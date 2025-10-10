SELECT 
    s.name,
    pt.name,
    IFNULL(p.content,'NOT SET') as value
FROM mhl_suppliers AS s
CROSS JOIN mhl_propertytypes AS pt
LEFT JOIN mhl_yn_properties AS p ON p.supplier_ID=s.id AND pt.id = p.propertytype_ID AND pt.proptype = 'A'
JOIN mhl_cities AS c ON s.city_id = c.id AND c.name = 'Amsterdam'