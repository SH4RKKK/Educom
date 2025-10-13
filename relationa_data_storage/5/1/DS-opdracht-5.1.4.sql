SELECT 
    s.name,
    SUM(h.hitcount) totaal_records, 
    COUNT(h.month) aantal_maanden,
    AVG(h.hitcount) gemiddelde_per_maand
FROM mhl_hitcount h
JOIN mhl_suppliers s ON h.supplier_ID = s.id
GROUP BY s.name
HAVING SUM(h.hitcount) > 100
ORDER BY totaal_records DESC;