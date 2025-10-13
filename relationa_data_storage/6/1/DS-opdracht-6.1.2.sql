SELECT 
    g.name gemeente,
    s.name leverancier,
    SUM(h.hitcount) total_hitcount,
    g_avg.average_hitcount
FROM mhl_hitcount h
JOIN mhl_suppliers s ON h.supplier_id = s.id
JOIN mhl_cities c ON s.city_id = c.id
JOIN mhl_communes g ON c.commune_id = g.id
JOIN (
    SELECT 
        g.id id,
        AVG(h.hitcount) average_hitcount
    FROM mhl_hitcount h
    JOIN mhl_suppliers s ON h.supplier_id = s.id
    JOIN mhl_cities c ON s.city_id = c.id
    JOIN mhl_communes g ON c.commune_id = g.id
    JOIN mhl_districts p ON g.district_id = p.id AND p.id < 13
    GROUP BY g.id
) AS g_avg ON g_avg.id = g.id
GROUP BY g.id, s.id 
HAVING total_hitcount > g_avg.average_hitcount
ORDER BY gemeente, total_hitcount DESC;