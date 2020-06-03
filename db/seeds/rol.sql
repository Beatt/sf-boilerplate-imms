INSERT INTO rol (id, nombre, clave) VALUES (1, 'Super admin', 'SUPER');
INSERT INTO rol (nombre, clave) VALUES ('Institución Educativa', 'IE');
INSERT INTO rol (nombre, clave) VALUES ('CAME', 'CAME');
INSERT INTO rol (nombre, clave) VALUES ('FOFOE', 'FOFOE');

INSERT INTO permiso_rol (permiso_id, rol_id) VALUES (1, 1);
