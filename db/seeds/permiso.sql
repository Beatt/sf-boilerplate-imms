INSERT INTO permiso (nombre, clave, rol_id) VALUES ('Inicio', 'FOFOE_INICIO', (select id from rol where rol.clave = 'FOFOE' limit 1));
INSERT INTO permiso (nombre, clave, rol_id) VALUES ('Validar pago', 'FOFOE_VALIDAR_PAGO', (select id from rol where rol.clave = 'FOFOE' limit 1));
INSERT INTO permiso (nombre, clave, rol_id) VALUES ('Validar pago multiple', 'FOFOE_VALIDAR_PAGO_MULTIPLE', (select id from rol where rol.clave = 'FOFOE' limit 1));
INSERT INTO permiso (nombre, clave, rol_id) VALUES ('Registrar facturar', 'FOFOE_REGISTRAR_FACTURAR', (select id from rol where rol.clave = 'FOFOE' limit 1));
INSERT INTO permiso (nombre, clave, rol_id) VALUES ('Detalle de institución educativa', 'FOFOE_DETALLE_INSTITUCION_EDUCATIVA', (select id from rol where rol.clave = 'FOFOE' limit 1));

INSERT INTO permiso (nombre, clave, rol_id) VALUES ('CAME', 'CAME', (select id from rol where rol.clave = 'CAME' limit 1));
INSERT INTO permiso (nombre, clave, rol_id) VALUES ('IE', 'IE', (select id from rol where rol.clave = 'IE' limit 1));
INSERT INTO permiso (nombre, clave, rol_id) VALUES ('Super admin', 'SUPER', (select id from rol where rol.clave = 'SUPER' limit 1));

INSERT INTO permiso(nombre, clave, rol_id) VALUES ('Consultar Reporte de Ingresos', 'FOFOE_REPORTE_INGS', (select id from rol where rol.clave = 'FOFOE' limit 1));
INSERT INTO permiso(nombre, clave, rol_id) VALUES ('Consultar Reporte Oportunidad de Pago', 'FOFOE_REPORTE_OP', (select id from rol where rol.clave = 'FOFOE' limit 1));
INSERT INTO permiso(nombre, clave, rol_id) VALUES ('Consultar Reporte Detallado de CCS', 'REPORTE_CCS_DET', (select id from rol where rol.clave = 'FOFOE' limit 1));
INSERT INTO permiso(nombre, clave, rol_id) VALUES ('Consultar Reporte de CCS por Unidad', 'REPORTE_CCS_ENF', (select id from rol where rol.clave = 'FOFOE' limit 1));

INSERT INTO permiso(nombre, clave, rol_id) VALUES ('Consultar Reporte Detallado de CCS [Pregrado]', 'REPORTE_CCS_DET', (select id from rol where rol.clave = 'PREGRADO' limit 1));
INSERT INTO permiso(nombre, clave, rol_id) VALUES ('Consultar Reporte de CCS por Unidad [Enfermería]', 'REPORTE_CCS_ENF', (select id from rol where rol.clave = 'ENF' limit 1));

