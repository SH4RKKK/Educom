-- view
CREATE OR REPLACE VIEW RUBRIEK AS
SELECT 
    cr.id child_id,
    COALESCE(pr.id, cr.id) parent_id,
    COALESCE(CONCAT(pr.name, ' - ', cr.name), cr.name, pr.name) rubriek
FROM mhl_rubrieken cr
LEFT JOIN mhl_rubrieken pr ON cr.parent = pr.id
ORDER by rubriek;

SELECT 
    r.rubriek,
    COUNT(rv.mhl_suppliers_id)
FROM RUBRIEK r
LEFT JOIN mhl_suppliers_mhl_rubriek_view rv ON rv.mhl_rubriek_view_id = r.child_id
GROUP BY r.child_id 


-- No view
SELECT 
    COALESCE(CONCAT(pr.name, ' - ', cr.name), cr.name, pr.name) rubriek,
    COUNT(rv.mhl_suppliers_id) `Aantal leveranciers`
FROM mhl_rubrieken cr
LEFT JOIN mhl_rubrieken pr ON cr.parent = pr.id
LEFT JOIN mhl_suppliers_mhl_rubriek_view rv ON rv.mhl_rubriek_view_id = cr.id
GROUP BY cr.id
ORDER BY rubriek;