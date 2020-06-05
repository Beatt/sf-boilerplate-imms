INSERT INTO rol (id, nombre, clave) VALUES (1, 'Super admin', 'SUPER');
INSERT INTO rol (nombre, clave) VALUES ('Institución Educativa', 'IE');
INSERT INTO rol (nombre, clave) VALUES ('CAME', 'CAME');
INSERT INTO rol (nombre, clave) VALUES ('FOFOE', 'FOFOE');

INSERT INTO permiso_rol (permiso_id, rol_id) VALUES (1, 1);
INSERT INTO permiso_rol (permiso_id, rol_id) VALUES ((SELECT id from permiso where rol_seguridad = 'ROLE_CONSULTAR_SOLICITUDES' limit 1), (SELECT id from rol where clave = 'CAME' limit 1));
INSERT INTO permiso_rol (permiso_id, rol_id) VALUES ((SELECT id from permiso where rol_seguridad = 'ROLE_TERMINAR_SOLICITUDES' limit 1), (SELECT id from rol where clave = 'CAME' limit 1));
INSERT INTO permiso_rol (permiso_id, rol_id) VALUES ((SELECT id from permiso where rol_seguridad = 'ROLE_FORMATO_FOFOE' limit 1), (SELECT id from rol where clave = 'CAME' limit 1));
INSERT INTO permiso_rol (permiso_id, rol_id) VALUES ((SELECT id from permiso where rol_seguridad = 'ROLE_DESCARGAR_OFICIO_MONTOS' limit 1), (SELECT id from rol where clave = 'CAME' limit 1));
INSERT INTO permiso_rol (permiso_id, rol_id) VALUES ((SELECT id from permiso where rol_seguridad = 'ROLE_VALIDACION_MONTOS' limit 1), (SELECT id from rol where clave = 'CAME' limit 1));
INSERT INTO permiso_rol (permiso_id, rol_id) VALUES ((SELECT id from permiso where rol_seguridad = 'ROLE_DETALLE_SOLICITUD' limit 1), (SELECT id from rol where clave = 'CAME' limit 1));
INSERT INTO permiso_rol (permiso_id, rol_id) VALUES ((SELECT id from permiso where rol_seguridad = 'ROLE_EDITAR_SOLICITUD' limit 1), (SELECT id from rol where clave = 'CAME' limit 1));
INSERT INTO permiso_rol (permiso_id, rol_id) VALUES ((SELECT id from permiso where rol_seguridad = 'ROLE_AGREGAR_SOLICITUD' limit 1), (SELECT id from rol where clave = 'CAME' limit 1));