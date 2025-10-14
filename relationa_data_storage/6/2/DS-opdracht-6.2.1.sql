SELECT
 s.joindate,
 DATEDIFF(LAST_DAY(s.joindate), s.joindate) days_from_end,
 s.id
FROM mhl_suppliers s
WHERE DATEDIFF(LAST_DAY(s.joindate), s.joindate) <= 7
ORDER BY s.id