SELECT c.name, c.id, c2.id, c.commune_ID, c2.commune_ID, g.name, g2.name
FROM mhl_cities AS c
JOIN mhl_cities AS c2 ON c.name=c2.name
JOIN mhl_communes AS g on g.id=c.commune_ID
JOIN mhl_communes AS g2 on g2.id=c2.commune_ID
WHERE c.id < c2.id AND c.commune_ID != 0
ORDER BY c.name;