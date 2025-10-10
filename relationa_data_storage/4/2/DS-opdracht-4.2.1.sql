SELECT c.name,c.commune_ID
FROM mhl_cities AS c
LEFT JOIN mhl_communes AS g ON g.id = c.commune_ID
WHERE ISNULL(g.name)