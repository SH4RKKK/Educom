SELECT
    cr.id,
    IFNULL(pr.name, cr.name) AS hoofdrubriek,
    IF(ISNULL(pr.name), '', cr.name) AS subrubriek
FROM mhl_rubrieken AS pr
RIGHT JOIN mhl_rubrieken AS cr ON cr.parent = pr.id
ORDER by hoofdrubriek, subrubriek