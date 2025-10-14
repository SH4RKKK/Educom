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
    IFNULL(SUM(h.hitcount),'Geen hits') total
FROM RUBRIEK r 
LEFT JOIN mhl_suppliers_mhl_rubriek_view rv ON rv.mhl_rubriek_view_id = r.child_id
LEFT JOIN mhl_suppliers s ON s.id = rv.mhl_suppliers_id
LEFT JOIN mhl_hitcount h on h.supplier_id = s.id 
GROUP BY r.child_id;