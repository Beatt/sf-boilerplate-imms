-- super
INSERT INTO usuario (nombre, contrasena, correo, activo, categoria_id, matricula, apellido_paterno, apellido_materno, regims, curp, rfc, sexo, fecha_ingreso) VALUES ('Admin', '$2y$12$CZXMdJQ.qP6epZ1Q.6pqtOJ0uK2opiTjR.o.6trQaZCbCI688H8YW', 'admin@gmail.com', true, null, null, 'Admin', 'Admin', 0, '0', '0', '0', '2020-03-25');
INSERT INTO usuario_permiso values (1,(SELECT id from permiso where clave = 'SUPER' limit 1));
-- came
INSERT INTO usuario (nombre, contrasena, correo, activo, categoria_id, matricula, apellido_paterno, apellido_materno, regims, curp, rfc, sexo, fecha_ingreso) VALUES ('CAME', '$2y$13$M9pZwcUaOmTmQdD0sMtMr.0.5U.KvZ4E7paMoVQ/N1VGPYp5cd.Aa', 'came@itzelcom.com', true, null, 304104532, 'Pruebas', 'Pruebas', 0, '0', '0', '0', '2020-03-25');
INSERT INTO usuario_permiso values ((SELECT id from usuario where correo = 'came@itzelcom.com' limit 1),(SELECT id from permiso where clave = 'CAME' limit 1));
INSERT INTO usuario_delegacion values ((SELECT id from usuario where correo = 'came@itzelcom.com' limit 1),(SELECT id from delegacion where clave_delegacional = '22' limit 1));
INSERT INTO usuario_delegacion values ((SELECT id from usuario where correo = 'came@itzelcom.com' limit 1),(SELECT id from delegacion where clave_delegacional = '33' limit 1));
INSERT INTO usuario_delegacion values ((SELECT id from usuario where correo = 'came@itzelcom.com' limit 1),(SELECT id from delegacion where clave_delegacional = '16' limit 1));
-- fofoe
INSERT INTO usuario (nombre, contrasena, correo, activo, categoria_id, matricula, apellido_paterno, apellido_materno, regims, curp, rfc, sexo, fecha_ingreso) VALUES ('FOFOE', '$2y$13$M9pZwcUaOmTmQdD0sMtMr.0.5U.KvZ4E7paMoVQ/N1VGPYp5cd.Aa', 'fofoe@itzelcom.com', true, null, 304104530, 'Pruebas', 'IMSS', 0, '0', '0', '0', '2020-03-25');
INSERT INTO usuario_permiso values ((SELECT id from usuario where correo = 'fofoe@itzelcom.com' limit 1),(SELECT id from permiso where clave = 'FOFOE_INICIO' limit 1));
-- reportes fofoe:
INSERT INTO usuario_permiso values ((SELECT id from usuario where correo = 'fofoe@itzelcom.com' limit 1),(SELECT id from permiso where clave = 'FOFOE_REPORTE_INGS' limit 1));
INSERT INTO usuario_permiso values ((SELECT id from usuario where correo = 'fofoe@itzelcom.com' limit 1),(SELECT id from permiso where clave = 'FOFOE_REPORTE_OP' limit 1));
INSERT INTO usuario_permiso values ((SELECT id from usuario where correo = 'fofoe@itzelcom.com' limit 1),(SELECT id from permiso where clave = 'REPORTE_CCS_DET' limit 1));
INSERT INTO usuario_permiso values ((SELECT id from usuario where correo = 'fofoe@itzelcom.com' limit 1),(SELECT id from permiso where clave = 'REPORTE_CCS_ENF' limit 1));