SELECT 
    YEAR(s.joindate) jaar,
    MONTHNAME(s.joindate) maand,
    COUNT(s.id) aantal
FROM mhl_suppliers s 
GROUP BY YEAR(s.joindate), MONTH(s.joindate)
ORDER BY jaar, MONTH(s.joindate)