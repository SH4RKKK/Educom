SELECT 
     v.name,
     COALESCE(d.contact, 't.a.v. de directie') contact,
     v.adres,
     v.postcode,
     v.stad
FROM VERZENDLIJST v
LEFT JOIN DIRECTIE d ON v.id = d.id 
ORDER BY v.name