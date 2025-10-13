SELECT 
    COUNT(h.hitcount) aantal_records, 
    MIN(h.hitcount) minimaal_records,
    MAX(h.hitcount) maximaal_records,
    AVG(h.hitcount) gemiddelde_records,
    SUM(h.hitcount) totaal_records
FROM mhl_hitcount h