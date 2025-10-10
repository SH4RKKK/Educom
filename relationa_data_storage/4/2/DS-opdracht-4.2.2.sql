SELECT c.name, IFNULL(g.name,'INVALID')
FROM mhl_cities AS c
LEFT JOIN mhl_communes AS g ON g.id = c.commune_ID