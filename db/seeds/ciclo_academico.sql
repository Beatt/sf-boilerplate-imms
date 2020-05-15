INSERT INTO ciclo_academico(id, nombre, activo) VALUES (1, 'Ciclo clínico', true);
INSERT INTO ciclo_academico(id, nombre, activo) VALUES (2, 'Internado médico', true);
INSERT INTO ciclo_academico(id, nombre, activo) VALUES (3, 'Servicio social', false);
SELECT setval('ciclo_academico_id_seq', (SELECT MAX(id) from ciclo_academico));