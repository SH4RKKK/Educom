SELECT 
    c.name stad,
    COUNT( CASE WHEN m.name = 'Gold' THEN 1 END ) Gold,
    COUNT( CASE WHEN m.name = 'Silver' THEN 1 END ) Silver,
    COUNT( CASE WHEN m.name = 'Bronze' THEN 1 END ) Bronze,
    COUNT( CASE WHEN m.name NOT IN ('Gold', 'Silver', 'Bronze') THEN 1 END ) Other
FROM mhl_suppliers s
JOIN mhl_membertypes m ON m.id = s.membertype
LEFT JOIN mhl_cities c ON c.id = s.city_id
GROUP BY c.name
ORDER BY Gold DESC, Silver DESC, Bronze DESC, Other DESC