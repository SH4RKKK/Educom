SELECT c.name, c2.name, c.id, c2.id, c.commune_ID, c2.commune_ID
FROM mhl_cities AS c
JOIN mhl_cities AS c2 ON c.name=c2.name
WHERE c.id < c2.id
ORDER BY c.name;