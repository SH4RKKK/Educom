SELECT 
    c.name,
    CASE 
        WHEN LEFT(c.name, 1) = "'" THEN CONCAT(LEFT(c.name, 3), UPPER(SUBSTRING(c.name, 4, 1)), SUBSTRING(c.name, 5))
        ELSE CONCAT(UPPER(LEFT(c.name, 1)), SUBSTRING(c.name, 2))
    END nice_name
FROM mhl_cities c
ORDER BY c.name;