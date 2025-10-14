SELECT
    s.id,
    s.joindate,
    DATEDIFF(CURRENT_DATE, s.joindate) `dagen lid`
FROM mhl_suppliers s 
ORDER BY `dagen lid` ASC