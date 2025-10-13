CREATE OR REPLACE VIEW MAAND AS
WITH RECURSIVE numbers AS (
    SELECT 1 AS n
    UNION ALL
    SELECT n + 1 FROM numbers WHERE n < 12
)
SELECT 
    n AS id,
    ELT(n, 
        'Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni',
        'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'
    ) AS month
FROM numbers;


SELECT
    h.year jaar,
    m.month maand,
    COUNT(h.supplier_ID) `aantal leveranciers`,
    SUM(h.hitcount) `totaal aantal hits`
FROM mhl_hitcount h 
JOIN MAAND m ON m.id = h.month
GROUP BY h.year, h.month
ORDER BY jaar DESC, maand ASC;