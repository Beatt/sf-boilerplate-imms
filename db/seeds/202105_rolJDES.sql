INSERT INTO permiso (nombre, clave, rol_id) VALUES ('CAME', 'CAME', (select id from rol where rol.clave = 'CAME' limit 1));
INSERT INTO rol (nombre, clave) VALUES ('JDES', 'JDES');
