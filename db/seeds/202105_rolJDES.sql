INSERT INTO rol (nombre, clave) VALUES ('JDES', 'JDES');
INSERT INTO permiso (nombre, clave, rol_id) VALUES ('JDES', 'JDES', (select id from rol where rol.clave = 'JDES' limit 1));
