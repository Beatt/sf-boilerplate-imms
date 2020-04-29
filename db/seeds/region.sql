INSERT INTO region(id, nombre, activo) VALUES (1, 'Nor-Occidente', true);
INSERT INTO region(id, nombre, activo) VALUES (2, 'Nor-Este', true);
INSERT INTO region(id, nombre, activo) VALUES (3, 'Centro-Sur', true);
INSERT INTO region(id, nombre, activo) VALUES (4, 'Centro-Norte', true);
SELECT setval('region_id_seq', (SELECT MAX(id) from region));