CREATE OR REPLACE VIEW VERZENDLIJST AS
SELECT
    s.id ID,
    s.name,
    IF(COALESCE(s.p_address, '') != '', s.p_address, CONCAT(s.straat, ' ', s.huisnr)) adres,
    IF(COALESCE(s.p_address, '') != '', s.p_postcode, s.postcode) postcode,
    c.name stad
FROM mhl_suppliers s
LEFT JOIN mhl_cities c ON c.id = IFNULL(NULLIF(s.p_city_id * (s.p_address IS NOT NULL AND s.p_address != ''), 0), s.city_id)