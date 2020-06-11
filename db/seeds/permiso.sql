INSERT INTO permiso (nombre, clave, rol_id) VALUES ('Inicio', 'FOFOE_INICIO', (select id from rol where rol.clave = 'FOFOE'));
INSERT INTO permiso (nombre, clave, rol_id) VALUES ('Validar pago', 'FOFOE_VALIDAR_PAGO', (select id from rol where rol.clave = 'FOFOE'));
INSERT INTO permiso (nombre, clave, rol_id) VALUES ('Validar pago multiple', 'FOFOE_VALIDAR_PAGO_MULTIPLE', (select id from rol where rol.clave = 'FOFOE'));
INSERT INTO permiso (nombre, clave, rol_id) VALUES ('Registrar facturar', 'FOFOE_REGISTRAR_FACTURAR', (select id from rol where rol.clave = 'FOFOE'));
INSERT INTO permiso (nombre, clave, rol_id) VALUES ('Detalle de institución educativa', 'FOFOE_DETALLE_INSTITUCION_EDUCATIVA', (select id from rol where rol.clave = 'FOFOE'));

INSERT INTO permiso (nombre, clave, rol_id) VALUES ('CAME', 'CAME', (select id from rol where rol.clave = 'CAME'));
INSERT INTO permiso (nombre, clave, rol_id) VALUES ('IE', 'IE', (select id from rol where rol.clave = 'IE'));
INSERT INTO permiso (nombre, clave, rol_id) VALUES ('Super admin', 'SUPER', (select id from rol where rol.clave = 'SUPER'));
