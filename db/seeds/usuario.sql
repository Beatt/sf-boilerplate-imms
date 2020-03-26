INSERT INTO usuario (nombre, contrasena, correo, activo, categoria_id, matricula, apellido_paterno, apellido_materno, regims, curp, rfc, sexo, fecha_ingreso) VALUES ('Admin', '$2y$12$CZXMdJQ.qP6epZ1Q.6pqtOJ0uK2opiTjR.o.6trQaZCbCI688H8YW', 'admin@gmail.com', true, null, 0, 'Admin', 'Admin', 0, '0', '0', '0', '2020-03-25');

INSERT INTO usuario_rol (usuario_id, rol_id) VALUES (1, 1);
