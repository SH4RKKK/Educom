-- view
SELECT 
    IF(ISNULL(pr.name), cr.name, CONCAT(pr.name, ' - ', cr.name)) rubriek,
    COUNT(CASE WHEN IFNULL(pr.id, cr.id) = rv.mhl_rubriek_view_id THEN 1 END ) `Aantal leveranciers`
FROM mhl_suppliers_mhl_rubriek_view rv
JOIN mhl_rubrieken pr ON rv.mhl_rubriek_view_id = pr.id
RIGHT JOIN mhl_rubrieken AS cr ON cr.parent = pr.id
GROUP BY rubriek
ORDER BY rubriek






CREATE OR REPLACE VIEW RUBRIEK AS 
SELECT 
    IFNULL(cr.id, pr.id) id,
    IF(ISNULL(pr.name), rc.name, CONCAT(pr.name, ' - ', rc.name)) rubriek
FROM mhl_rubrieken pr
RIGHT JOIN mhl_rubrieken cr ON cr.parent = pr.id
ORDER by rubriek;


-- No view but need to fix order of initialization beceause current order is wrong
SELECT 
    COALESCE(CONCAT(pr.name, ' - ', cr.name), cr.name, pr.name) rubriek,
    COUNT(DISTINCT rv.mhl_suppliers_id) `Aantal leveranciers`
FROM mhl_rubrieken cr
LEFT JOIN mhl_rubrieken pr ON cr.parent = pr.id
LEFT JOIN mhl_suppliers_mhl_rubriek_view rv ON rv.mhl_rubriek_view_id = cr.id OR rv.mhl_rubriek_view_id = pr.id
GROUP BY cr.id
ORDER BY rubriek;