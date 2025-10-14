SELECT 
    DAYNAME(s.joindate) `Dagen van de week`,
    COUNT(s.id) `Aantal aanmeldingen`
FROM mhl_suppliers s 
GROUP BY DAYNAME(s.joindate)
ORDER BY WEEKDAY(s.joindate)