CREATE OR REPLACE VIEW DIRECTIE AS
SELECT 
    c.supplier_id ID,
    c.name contact,
    c.contacttype functie,
    CASE WHEN d.id = 3 THEN d.name ELSE 'nvt' END department
FROM mhl_contacts c
JOIN mhl_departments d ON d.id=c.department
WHERE c.department = 3 OR c.contacttype LIKE '%direct%'