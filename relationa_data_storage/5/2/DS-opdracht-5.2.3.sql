SELECT 
    h.year Jaar,
    SUM(CASE WHEN h.month IN (1, 2, 3) THEN h.hitcount ELSE 0 END) `Eerste kwartaal`,
    SUM(CASE WHEN h.month IN (4, 5, 6) THEN h.hitcount ELSE 0 END) `Tweede kwartaal`,
    SUM(CASE WHEN h.month IN (7, 8, 9) THEN h.hitcount ELSE 0 END) `Derde kwartaal`,
    SUM(CASE WHEN h.month IN (10, 11, 12) THEN h.hitcount ELSE 0 END) `Vierde kwartaal`,
    SUM(h.hitcount) Totaal
FROM mhl_hitcount h
GROUP BY h.year