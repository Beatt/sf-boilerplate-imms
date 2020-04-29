INSERT INTO nivel_academico(id, nombre) VALUES (1, 'Licenciatura');
INSERT INTO nivel_academico(id, nombre) VALUES (2, 'Técnico');
SELECT setval('nivel_academico_id_seq', (SELECT MAX(id) from nivel_academico));