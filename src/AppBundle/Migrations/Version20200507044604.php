<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200507044604 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE departamento (id SERIAL NOT NULL, unidad_id INT DEFAULT NULL, nombre VARCHAR(100) NOT NULL, clave_departamental VARCHAR(15) NOT NULL, clave_presupuestal VARCHAR(15) NOT NULL, es_unidad BOOLEAN NOT NULL, anio INT NOT NULL, fecha TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, activo BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_40E497EB9D01464C ON departamento (unidad_id)');
        $this->addSql('CREATE TABLE region (id SERIAL NOT NULL, nombre VARCHAR(20) NOT NULL, activo BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE pago (id SERIAL NOT NULL, monto NUMERIC(10, 4) NOT NULL, solicitud_id INT NOT NULL, comprobante_pago VARCHAR(100) NOT NULL, referencia_bancaria VARCHAR(100) NOT NULL, validado BOOLEAN NOT NULL, xml VARCHAR(100) NOT NULL, pdf VARCHAR(100) NOT NULL, factura BOOLEAN NOT NULL, observaciones VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE categoria (id SERIAL NOT NULL, nombre VARCHAR(100) NOT NULL, clave VARCHAR(15) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE convenio (id SERIAL NOT NULL, ciclo_academico_id INT DEFAULT NULL, carrera_id INT DEFAULT NULL, institucion_id INT NOT NULL, delegacion_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, sector VARCHAR(250) NOT NULL, tipo VARCHAR(250) NOT NULL, vigencia DATE NOT NULL, numero VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_25577244A7D9417F ON convenio (ciclo_academico_id)');
        $this->addSql('CREATE INDEX IDX_25577244C671B40F ON convenio (carrera_id)');
        $this->addSql('CREATE INDEX IDX_25577244B239FBC6 ON convenio (institucion_id)');
        $this->addSql('CREATE INDEX IDX_25577244F4B21EB5 ON convenio (delegacion_id)');
        $this->addSql('CREATE TABLE estatus_campo (id SERIAL NOT NULL, estatus VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE institucion (id SERIAL NOT NULL, nombre VARCHAR(255) NOT NULL, telefono VARCHAR(16) DEFAULT NULL, correo VARCHAR(254) DEFAULT NULL, fax VARCHAR(254) DEFAULT NULL, sitio_web VARCHAR(100) DEFAULT NULL, cedula_identificacion VARCHAR(255) DEFAULT NULL, rfc VARCHAR(13) DEFAULT NULL, direccion VARCHAR(255) DEFAULT NULL, representante VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE ciclo_academico (id SERIAL NOT NULL, nombre VARCHAR(30) NOT NULL, activo BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE carrera (id SERIAL NOT NULL, nivel_academico_id INT DEFAULT NULL, nombre VARCHAR(35) NOT NULL, activo BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CF1ECD30C21F5FA8 ON carrera (nivel_academico_id)');
        $this->addSql('CREATE TABLE traductor (id SERIAL NOT NULL, locale VARCHAR(2) NOT NULL, textos JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE permiso (id SERIAL NOT NULL, nombre VARCHAR(255) NOT NULL, descripcion VARCHAR(255) NOT NULL, rol_seguridad VARCHAR(80) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE permiso_rol (permiso_id INT NOT NULL, rol_id INT NOT NULL, PRIMARY KEY(permiso_id, rol_id))');
        $this->addSql('CREATE INDEX IDX_DD501D066CEFAD37 ON permiso_rol (permiso_id)');
        $this->addSql('CREATE INDEX IDX_DD501D064BAB96C ON permiso_rol (rol_id)');
        $this->addSql('CREATE TABLE usuario (id SERIAL NOT NULL, departamento_id INT DEFAULT NULL, categoria_id INT DEFAULT NULL, matricula INT NOT NULL, nombre VARCHAR(25) NOT NULL, apellido_paterno VARCHAR(50) NOT NULL, apellido_materno VARCHAR(50) DEFAULT NULL, regims BIGINT NOT NULL, contrasena VARCHAR(64) NOT NULL, correo VARCHAR(254) NOT NULL, activo BOOLEAN NOT NULL, curp VARCHAR(18) NOT NULL, rfc VARCHAR(13) NOT NULL, sexo VARCHAR(10) NOT NULL, fecha_ingreso DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2265B05D15DF1885 ON usuario (matricula)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2265B05D77040BC9 ON usuario (correo)');
        $this->addSql('CREATE INDEX IDX_2265B05D5A91C08D ON usuario (departamento_id)');
        $this->addSql('CREATE INDEX IDX_2265B05D3397707A ON usuario (categoria_id)');
        $this->addSql('CREATE TABLE usuario_delegacion (usuario_id INT NOT NULL, delegacion_id INT NOT NULL, PRIMARY KEY(usuario_id, delegacion_id))');
        $this->addSql('CREATE INDEX IDX_17D166E9DB38439E ON usuario_delegacion (usuario_id)');
        $this->addSql('CREATE INDEX IDX_17D166E9F4B21EB5 ON usuario_delegacion (delegacion_id)');
        $this->addSql('CREATE TABLE usuario_rol (usuario_id INT NOT NULL, rol_id INT NOT NULL, PRIMARY KEY(usuario_id, rol_id))');
        $this->addSql('CREATE INDEX IDX_72EDD1A4DB38439E ON usuario_rol (usuario_id)');
        $this->addSql('CREATE INDEX IDX_72EDD1A44BAB96C ON usuario_rol (rol_id)');
        $this->addSql('CREATE TABLE delegacion (id SERIAL NOT NULL, region_id INT DEFAULT NULL, nombre VARCHAR(30) NOT NULL, activo BOOLEAN NOT NULL, clave_delegacional VARCHAR(2) NOT NULL, grupo_delegacion VARCHAR(4) NOT NULL, nombre_grupo_delegacion VARCHAR(50) NOT NULL, fecha TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, latitud DOUBLE PRECISION DEFAULT NULL, longitud DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E4E12C4B98260155 ON delegacion (region_id)');
        $this->addSql('CREATE TABLE rol (id SERIAL NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE expediente (id SERIAL NOT NULL, solicitud_id INT NOT NULL, descripcion TEXT DEFAULT NULL, url_archivo VARCHAR(255) NOT NULL, fecha DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D59CA4131CB9D6E4 ON expediente (solicitud_id)');
        $this->addSql('CREATE TABLE unidad (id SERIAL NOT NULL, delegacion_id INT DEFAULT NULL, tipo_unidad_id INT DEFAULT NULL, nombre VARCHAR(100) NOT NULL, clave_unidad VARCHAR(15) NOT NULL, clave_presupuestal VARCHAR(15) NOT NULL, nivel_atencion INT DEFAULT NULL, es_umae BOOLEAN NOT NULL, direccion VARCHAR(255) DEFAULT NULL, nombre_unidad_principal VARCHAR(50) DEFAULT NULL, clave_unidad_principal VARCHAR(2) DEFAULT NULL, anio INT NOT NULL, fecha DATE NOT NULL, activo BOOLEAN NOT NULL, latitud DOUBLE PRECISION DEFAULT NULL, longitud DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F3E6D02FF4B21EB5 ON unidad (delegacion_id)');
        $this->addSql('CREATE INDEX IDX_F3E6D02F7F6FF902 ON unidad (tipo_unidad_id)');
        $this->addSql('CREATE TABLE monto_carrera (id SERIAL NOT NULL, solicitud_id INT DEFAULT NULL, monto_inscripcion DOUBLE PRECISION NOT NULL, monto_colegiatura DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BCC32A561CB9D6E4 ON monto_carrera (solicitud_id)');
        $this->addSql('CREATE TABLE campo_clinico (id SERIAL NOT NULL, convenio_id INT DEFAULT NULL, solicitud_id INT DEFAULT NULL, estatus_campo_id INT DEFAULT NULL, unidad_id INT DEFAULT NULL, fecha_inicial DATE NOT NULL, fecha_final DATE NOT NULL, horario VARCHAR(100), promocion VARCHAR(100), lugares_solicitados INT NOT NULL, lugares_autorizados INT NOT NULL, referencia_bancaria VARCHAR(100), monto DOUBLE PRECISION DEFAULT NULL, asignatura VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3307999AF9D43F2A ON campo_clinico (convenio_id)');
        $this->addSql('CREATE INDEX IDX_3307999A1CB9D6E4 ON campo_clinico (solicitud_id)');
        $this->addSql('CREATE INDEX IDX_3307999A44D087A7 ON campo_clinico (estatus_campo_id)');
        $this->addSql('CREATE INDEX IDX_3307999A9D01464C ON campo_clinico (unidad_id)');
        $this->addSql('CREATE TABLE nivel_academico (id SERIAL NOT NULL, nombre VARCHAR(30) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE solicitud (id SERIAL NOT NULL, no_solicitud VARCHAR(9) DEFAULT NULL, fecha DATE NOT NULL, estatus VARCHAR(100) NOT NULL, referencia_bancaria VARCHAR(100) DEFAULT NULL, monto DOUBLE PRECISION DEFAULT NULL, tipo_pago VARCHAR(10) DEFAULT NULL, documento VARCHAR(255) DEFAULT NULL, url_archivo VARCHAR(255) DEFAULT NULL, validado BOOLEAN DEFAULT NULL, fecha_comprobante DATE DEFAULT NULL, observaciones VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_96D27CC0415EAE2C ON solicitud (no_solicitud)');
        $this->addSql('CREATE TABLE tipo_unidad (id SERIAL NOT NULL, nombre VARCHAR(100) NOT NULL, descripcion VARCHAR(100) NOT NULL, nivel INT NOT NULL, grupo_tipo VARCHAR(6) NOT NULL, grupo_nombre VARCHAR(50) NOT NULL, activo BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE departamento ADD CONSTRAINT FK_40E497EB9D01464C FOREIGN KEY (unidad_id) REFERENCES unidad (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE convenio ADD CONSTRAINT FK_25577244A7D9417F FOREIGN KEY (ciclo_academico_id) REFERENCES ciclo_academico (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE convenio ADD CONSTRAINT FK_25577244C671B40F FOREIGN KEY (carrera_id) REFERENCES carrera (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE convenio ADD CONSTRAINT FK_25577244B239FBC6 FOREIGN KEY (institucion_id) REFERENCES institucion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE convenio ADD CONSTRAINT FK_25577244F4B21EB5 FOREIGN KEY (delegacion_id) REFERENCES delegacion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE carrera ADD CONSTRAINT FK_CF1ECD30C21F5FA8 FOREIGN KEY (nivel_academico_id) REFERENCES nivel_academico (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE permiso_rol ADD CONSTRAINT FK_DD501D066CEFAD37 FOREIGN KEY (permiso_id) REFERENCES permiso (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE permiso_rol ADD CONSTRAINT FK_DD501D064BAB96C FOREIGN KEY (rol_id) REFERENCES rol (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE usuario ADD CONSTRAINT FK_2265B05D5A91C08D FOREIGN KEY (departamento_id) REFERENCES departamento (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE usuario ADD CONSTRAINT FK_2265B05D3397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE usuario_delegacion ADD CONSTRAINT FK_17D166E9DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE usuario_delegacion ADD CONSTRAINT FK_17D166E9F4B21EB5 FOREIGN KEY (delegacion_id) REFERENCES delegacion (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE usuario_rol ADD CONSTRAINT FK_72EDD1A4DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE usuario_rol ADD CONSTRAINT FK_72EDD1A44BAB96C FOREIGN KEY (rol_id) REFERENCES rol (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE delegacion ADD CONSTRAINT FK_E4E12C4B98260155 FOREIGN KEY (region_id) REFERENCES region (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE expediente ADD CONSTRAINT FK_D59CA4131CB9D6E4 FOREIGN KEY (solicitud_id) REFERENCES solicitud (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE unidad ADD CONSTRAINT FK_F3E6D02FF4B21EB5 FOREIGN KEY (delegacion_id) REFERENCES delegacion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE unidad ADD CONSTRAINT FK_F3E6D02F7F6FF902 FOREIGN KEY (tipo_unidad_id) REFERENCES tipo_unidad (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE monto_carrera ADD CONSTRAINT FK_BCC32A561CB9D6E4 FOREIGN KEY (solicitud_id) REFERENCES solicitud (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE campo_clinico ADD CONSTRAINT FK_3307999AF9D43F2A FOREIGN KEY (convenio_id) REFERENCES convenio (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE campo_clinico ADD CONSTRAINT FK_3307999A1CB9D6E4 FOREIGN KEY (solicitud_id) REFERENCES solicitud (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE campo_clinico ADD CONSTRAINT FK_3307999A44D087A7 FOREIGN KEY (estatus_campo_id) REFERENCES estatus_campo (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE campo_clinico ADD CONSTRAINT FK_3307999A9D01464C FOREIGN KEY (unidad_id) REFERENCES unidad (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE usuario DROP CONSTRAINT FK_2265B05D5A91C08D');
        $this->addSql('ALTER TABLE delegacion DROP CONSTRAINT FK_E4E12C4B98260155');
        $this->addSql('ALTER TABLE usuario DROP CONSTRAINT FK_2265B05D3397707A');
        $this->addSql('ALTER TABLE campo_clinico DROP CONSTRAINT FK_3307999AF9D43F2A');
        $this->addSql('ALTER TABLE campo_clinico DROP CONSTRAINT FK_3307999A44D087A7');
        $this->addSql('ALTER TABLE convenio DROP CONSTRAINT FK_25577244B239FBC6');
        $this->addSql('ALTER TABLE convenio DROP CONSTRAINT FK_25577244A7D9417F');
        $this->addSql('ALTER TABLE convenio DROP CONSTRAINT FK_25577244C671B40F');
        $this->addSql('ALTER TABLE permiso_rol DROP CONSTRAINT FK_DD501D066CEFAD37');
        $this->addSql('ALTER TABLE usuario_delegacion DROP CONSTRAINT FK_17D166E9DB38439E');
        $this->addSql('ALTER TABLE usuario_rol DROP CONSTRAINT FK_72EDD1A4DB38439E');
        $this->addSql('ALTER TABLE convenio DROP CONSTRAINT FK_25577244F4B21EB5');
        $this->addSql('ALTER TABLE usuario_delegacion DROP CONSTRAINT FK_17D166E9F4B21EB5');
        $this->addSql('ALTER TABLE unidad DROP CONSTRAINT FK_F3E6D02FF4B21EB5');
        $this->addSql('ALTER TABLE permiso_rol DROP CONSTRAINT FK_DD501D064BAB96C');
        $this->addSql('ALTER TABLE usuario_rol DROP CONSTRAINT FK_72EDD1A44BAB96C');
        $this->addSql('ALTER TABLE departamento DROP CONSTRAINT FK_40E497EB9D01464C');
        $this->addSql('ALTER TABLE campo_clinico DROP CONSTRAINT FK_3307999A9D01464C');
        $this->addSql('ALTER TABLE carrera DROP CONSTRAINT FK_CF1ECD30C21F5FA8');
        $this->addSql('ALTER TABLE expediente DROP CONSTRAINT FK_D59CA4131CB9D6E4');
        $this->addSql('ALTER TABLE monto_carrera DROP CONSTRAINT FK_BCC32A561CB9D6E4');
        $this->addSql('ALTER TABLE campo_clinico DROP CONSTRAINT FK_3307999A1CB9D6E4');
        $this->addSql('ALTER TABLE unidad DROP CONSTRAINT FK_F3E6D02F7F6FF902');
        $this->addSql('DROP TABLE departamento');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE pago');
        $this->addSql('DROP TABLE categoria');
        $this->addSql('DROP TABLE convenio');
        $this->addSql('DROP TABLE estatus_campo');
        $this->addSql('DROP TABLE institucion');
        $this->addSql('DROP TABLE ciclo_academico');
        $this->addSql('DROP TABLE carrera');
        $this->addSql('DROP TABLE traductor');
        $this->addSql('DROP TABLE permiso');
        $this->addSql('DROP TABLE permiso_rol');
        $this->addSql('DROP TABLE usuario');
        $this->addSql('DROP TABLE usuario_delegacion');
        $this->addSql('DROP TABLE usuario_rol');
        $this->addSql('DROP TABLE delegacion');
        $this->addSql('DROP TABLE rol');
        $this->addSql('DROP TABLE expediente');
        $this->addSql('DROP TABLE unidad');
        $this->addSql('DROP TABLE monto_carrera');
        $this->addSql('DROP TABLE campo_clinico');
        $this->addSql('DROP TABLE nivel_academico');
        $this->addSql('DROP TABLE solicitud');
        $this->addSql('DROP TABLE tipo_unidad');
    }
}
