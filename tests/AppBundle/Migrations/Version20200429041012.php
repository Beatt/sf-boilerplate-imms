<?php

namespace AppBundle\Migrations\Test;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200429041012 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE departamento (id INTEGER NOT NULL, unidad_id INTEGER DEFAULT NULL, nombre VARCHAR(100) NOT NULL, clave_departamental VARCHAR(15) NOT NULL, clave_presupuestal VARCHAR(15) NOT NULL, es_unidad BOOLEAN NOT NULL, anio INTEGER NOT NULL, fecha DATETIME NOT NULL, activo BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_40E497EB9D01464C ON departamento (unidad_id)');
        $this->addSql('CREATE TABLE region (id INTEGER NOT NULL, nombre VARCHAR(20) NOT NULL, activo BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE pago (id INTEGER NOT NULL, monto NUMERIC(10, 4) NOT NULL, solicitud_id INTEGER NOT NULL, comprobante_pago VARCHAR(100) NOT NULL, referencia_bancaria VARCHAR(100) NOT NULL, validado BOOLEAN NOT NULL, xml VARCHAR(100) NOT NULL, pdf VARCHAR(100) NOT NULL, factura BOOLEAN NOT NULL, observaciones VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE categoria (id INTEGER NOT NULL, nombre VARCHAR(100) NOT NULL, clave VARCHAR(15) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE convenio (id INTEGER NOT NULL, ciclo_academico_id INTEGER DEFAULT NULL, carrera_id INTEGER DEFAULT NULL, institucion_id INTEGER NOT NULL, delegacion_id INTEGER DEFAULT NULL, nombre VARCHAR(255) NOT NULL, sector VARCHAR(250) NOT NULL, tipo VARCHAR(250) NOT NULL, vigencia DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_25577244A7D9417F ON convenio (ciclo_academico_id)');
        $this->addSql('CREATE INDEX IDX_25577244C671B40F ON convenio (carrera_id)');
        $this->addSql('CREATE INDEX IDX_25577244B239FBC6 ON convenio (institucion_id)');
        $this->addSql('CREATE INDEX IDX_25577244F4B21EB5 ON convenio (delegacion_id)');
        $this->addSql('CREATE TABLE estatus_campo (id INTEGER NOT NULL, estatus VARCHAR(50) NOT NULL, nombre VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE institucion (id INTEGER NOT NULL, nombre VARCHAR(255) NOT NULL, telefono VARCHAR(16) DEFAULT NULL, correo VARCHAR(254) DEFAULT NULL, fax VARCHAR(254) DEFAULT NULL, sitio_web VARCHAR(100) DEFAULT NULL, cedula_identificacion VARCHAR(255) DEFAULT NULL, rfc VARCHAR(13) DEFAULT NULL, direccion VARCHAR(255) DEFAULT NULL, representante VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE ciclo_academico (id INTEGER NOT NULL, nombre VARCHAR(30) NOT NULL, activo BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE carrera (id INTEGER NOT NULL, nivel_academico_id INTEGER DEFAULT NULL, nombre VARCHAR(35) NOT NULL, activo BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CF1ECD30C21F5FA8 ON carrera (nivel_academico_id)');
        $this->addSql('CREATE TABLE traductor (id INTEGER NOT NULL, locale VARCHAR(2) NOT NULL, textos CLOB NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE permiso (id INTEGER NOT NULL, nombre VARCHAR(255) NOT NULL, descripcion VARCHAR(255) NOT NULL, rol_seguridad VARCHAR(80) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE permiso_rol (permiso_id INTEGER NOT NULL, rol_id INTEGER NOT NULL, PRIMARY KEY(permiso_id, rol_id))');
        $this->addSql('CREATE INDEX IDX_DD501D066CEFAD37 ON permiso_rol (permiso_id)');
        $this->addSql('CREATE INDEX IDX_DD501D064BAB96C ON permiso_rol (rol_id)');
        $this->addSql('CREATE TABLE usuario (id INTEGER NOT NULL, departamento_id INTEGER DEFAULT NULL, categoria_id INTEGER DEFAULT NULL, matricula INTEGER NOT NULL, nombre VARCHAR(25) NOT NULL, apellido_paterno VARCHAR(50) NOT NULL, apellido_materno VARCHAR(50) DEFAULT NULL, regims BIGINT NOT NULL, contrasena VARCHAR(64) NOT NULL, correo VARCHAR(254) NOT NULL, activo BOOLEAN NOT NULL, curp VARCHAR(18) NOT NULL, rfc VARCHAR(13) NOT NULL, sexo VARCHAR(10) NOT NULL, fecha_ingreso DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2265B05D15DF1885 ON usuario (matricula)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2265B05D77040BC9 ON usuario (correo)');
        $this->addSql('CREATE INDEX IDX_2265B05D5A91C08D ON usuario (departamento_id)');
        $this->addSql('CREATE INDEX IDX_2265B05D3397707A ON usuario (categoria_id)');
        $this->addSql('CREATE TABLE usuario_delegacion (usuario_id INTEGER NOT NULL, delegacion_id INTEGER NOT NULL, PRIMARY KEY(usuario_id, delegacion_id))');
        $this->addSql('CREATE INDEX IDX_17D166E9DB38439E ON usuario_delegacion (usuario_id)');
        $this->addSql('CREATE INDEX IDX_17D166E9F4B21EB5 ON usuario_delegacion (delegacion_id)');
        $this->addSql('CREATE TABLE usuario_rol (usuario_id INTEGER NOT NULL, rol_id INTEGER NOT NULL, PRIMARY KEY(usuario_id, rol_id))');
        $this->addSql('CREATE INDEX IDX_72EDD1A4DB38439E ON usuario_rol (usuario_id)');
        $this->addSql('CREATE INDEX IDX_72EDD1A44BAB96C ON usuario_rol (rol_id)');
        $this->addSql('CREATE TABLE delegacion (id INTEGER NOT NULL, region_id INTEGER DEFAULT NULL, nombre VARCHAR(30) NOT NULL, activo BOOLEAN NOT NULL, clave_delegacional VARCHAR(2) NOT NULL, grupo_delegacion VARCHAR(4) NOT NULL, nombre_grupo_delegacion VARCHAR(50) NOT NULL, fecha DATETIME NOT NULL, latitud DOUBLE PRECISION DEFAULT NULL, longitud DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E4E12C4B98260155 ON delegacion (region_id)');
        $this->addSql('CREATE TABLE rol (id INTEGER NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE expediente (id INTEGER NOT NULL, solicitud_id INTEGER NOT NULL, descripcion CLOB DEFAULT NULL, url_archivo VARCHAR(255) NOT NULL, fecha DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D59CA4131CB9D6E4 ON expediente (solicitud_id)');
        $this->addSql('CREATE TABLE unidad (id INTEGER NOT NULL, delegacion_id INTEGER DEFAULT NULL, tipo_unidad_id INTEGER DEFAULT NULL, nombre VARCHAR(100) NOT NULL, clave_unidad VARCHAR(15) NOT NULL, clave_presupuestal VARCHAR(15) NOT NULL, nivel_atencion INTEGER DEFAULT NULL, es_umae BOOLEAN NOT NULL, direccion VARCHAR(255) DEFAULT NULL, nombre_unidad_principal VARCHAR(50) DEFAULT NULL, clave_unidad_principal VARCHAR(2) DEFAULT NULL, anio INTEGER NOT NULL, fecha DATE NOT NULL, activo BOOLEAN NOT NULL, latitud DOUBLE PRECISION DEFAULT NULL, longitud DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F3E6D02FF4B21EB5 ON unidad (delegacion_id)');
        $this->addSql('CREATE INDEX IDX_F3E6D02F7F6FF902 ON unidad (tipo_unidad_id)');
        $this->addSql('CREATE TABLE campo_clinico (id INTEGER NOT NULL, ciclo_academico_id INTEGER DEFAULT NULL, carrera_id INTEGER DEFAULT NULL, convenio_id INTEGER DEFAULT NULL, solicitud_id INTEGER DEFAULT NULL, estatus_campo_id INTEGER DEFAULT NULL, unidad_id INTEGER DEFAULT NULL, fecha_inicial DATE NOT NULL, fecha_final DATE NOT NULL, horario VARCHAR(100) NOT NULL, promocion VARCHAR(100) NOT NULL, lugares_solicitados INTEGER NOT NULL, lugares_autorizados INTEGER NOT NULL, referencia_bancaria VARCHAR(100) NOT NULL, monto DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3307999AA7D9417F ON campo_clinico (ciclo_academico_id)');
        $this->addSql('CREATE INDEX IDX_3307999AC671B40F ON campo_clinico (carrera_id)');
        $this->addSql('CREATE INDEX IDX_3307999AF9D43F2A ON campo_clinico (convenio_id)');
        $this->addSql('CREATE INDEX IDX_3307999A1CB9D6E4 ON campo_clinico (solicitud_id)');
        $this->addSql('CREATE INDEX IDX_3307999A44D087A7 ON campo_clinico (estatus_campo_id)');
        $this->addSql('CREATE INDEX IDX_3307999A9D01464C ON campo_clinico (unidad_id)');
        $this->addSql('CREATE TABLE nivel_academico (id INTEGER NOT NULL, nombre VARCHAR(30) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE solicitud (id INTEGER NOT NULL, no_solicitud VARCHAR(9) DEFAULT NULL, fecha DATE NOT NULL, estatus VARCHAR(100) NOT NULL, referencia_bancaria VARCHAR(100) DEFAULT NULL, tipo_pago VARCHAR(10) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_96D27CC0415EAE2C ON solicitud (no_solicitud)');
        $this->addSql('CREATE TABLE tipo_unidad (id INTEGER NOT NULL, nombre VARCHAR(100) NOT NULL, descripcion VARCHAR(100) NOT NULL, nivel INTEGER NOT NULL, grupo_tipo VARCHAR(6) NOT NULL, grupo_nombre VARCHAR(50) NOT NULL, activo BOOLEAN NOT NULL, PRIMARY KEY(id))');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

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
        $this->addSql('DROP TABLE campo_clinico');
        $this->addSql('DROP TABLE nivel_academico');
        $this->addSql('DROP TABLE solicitud');
        $this->addSql('DROP TABLE tipo_unidad');
    }
}
