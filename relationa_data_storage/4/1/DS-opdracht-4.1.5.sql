SELECT s.name, s.straat, s.huisnr, s.postcode, gl.lat, gl.lng
FROM mhl_suppliers AS s
JOIN pc_lat_long AS gl ON s.postcode=gl.pc6
ORDER BY gl.lat DESC
LIMIT 5;