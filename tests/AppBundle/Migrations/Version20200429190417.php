<?php

namespace AppBundle\Migrations\Test;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200429190417 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE pago (id SERIAL NOT NULL, monto NUMERIC(10, 4) NOT NULL, solicitud_id INT NOT NULL, comprobante_pago VARCHAR(100) NOT NULL, referencia_bancaria VARCHAR(100) NOT NULL, validado BOOLEAN NOT NULL, xml VARCHAR(100) NOT NULL, pdf VARCHAR(100) NOT NULL, factura BOOLEAN NOT NULL, observaciones VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE estatus_campo (id SERIAL NOT NULL, estatus VARCHAR(50) NOT NULL, nombre VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE expediente (id SERIAL NOT NULL, solicitud_id INT NOT NULL, descripcion TEXT DEFAULT NULL, url_archivo VARCHAR(255) NOT NULL, fecha DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D59CA4131CB9D6E4 ON expediente (solicitud_id)');
        $this->addSql('CREATE TABLE campo_clinico (id SERIAL NOT NULL, ciclo_academico_id INT DEFAULT NULL, carrera_id INT DEFAULT NULL, convenio_id INT DEFAULT NULL, solicitud_id INT DEFAULT NULL, estatus_campo_id INT DEFAULT NULL, unidad_id INT DEFAULT NULL, fecha_inicial DATE NOT NULL, fecha_final DATE NOT NULL, horario VARCHAR(100) NOT NULL, promocion VARCHAR(100) NOT NULL, lugares_solicitados INT NOT NULL, lugares_autorizados INT NOT NULL, referencia_bancaria VARCHAR(100) NOT NULL, monto DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3307999AA7D9417F ON campo_clinico (ciclo_academico_id)');
        $this->addSql('CREATE INDEX IDX_3307999AC671B40F ON campo_clinico (carrera_id)');
        $this->addSql('CREATE INDEX IDX_3307999AF9D43F2A ON campo_clinico (convenio_id)');
        $this->addSql('CREATE INDEX IDX_3307999A1CB9D6E4 ON campo_clinico (solicitud_id)');
        $this->addSql('CREATE INDEX IDX_3307999A44D087A7 ON campo_clinico (estatus_campo_id)');
        $this->addSql('CREATE INDEX IDX_3307999A9D01464C ON campo_clinico (unidad_id)');
        $this->addSql('CREATE TABLE solicitud (id SERIAL NOT NULL, no_solicitud VARCHAR(9) DEFAULT NULL, fecha DATE NOT NULL, estatus VARCHAR(100) NOT NULL, referencia_bancaria VARCHAR(100) DEFAULT NULL, tipo_pago VARCHAR(10) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_96D27CC0415EAE2C ON solicitud (no_solicitud)');
        $this->addSql('ALTER TABLE expediente ADD CONSTRAINT FK_D59CA4131CB9D6E4 FOREIGN KEY (solicitud_id) REFERENCES solicitud (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE campo_clinico ADD CONSTRAINT FK_3307999AA7D9417F FOREIGN KEY (ciclo_academico_id) REFERENCES ciclo_academico (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE campo_clinico ADD CONSTRAINT FK_3307999AC671B40F FOREIGN KEY (carrera_id) REFERENCES carrera (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE campo_clinico ADD CONSTRAINT FK_3307999AF9D43F2A FOREIGN KEY (convenio_id) REFERENCES convenio (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE campo_clinico ADD CONSTRAINT FK_3307999A1CB9D6E4 FOREIGN KEY (solicitud_id) REFERENCES solicitud (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE campo_clinico ADD CONSTRAINT FK_3307999A44D087A7 FOREIGN KEY (estatus_campo_id) REFERENCES estatus_campo (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE campo_clinico ADD CONSTRAINT FK_3307999A9D01464C FOREIGN KEY (unidad_id) REFERENCES unidad (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE departamento ALTER nombre TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE departamento ALTER clave_departamental TYPE VARCHAR(15)');
        $this->addSql('ALTER TABLE departamento ALTER clave_presupuestal TYPE VARCHAR(15)');
        $this->addSql('ALTER TABLE departamento ALTER fecha TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE departamento ALTER fecha DROP DEFAULT');
        $this->addSql('ALTER TABLE region ALTER nombre TYPE VARCHAR(20)');
        $this->addSql('ALTER TABLE categoria ALTER nombre TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE categoria ALTER clave TYPE VARCHAR(15)');
        $this->addSql('ALTER TABLE convenio ADD nombre VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE convenio ALTER institucion_id SET NOT NULL');
        $this->addSql('ALTER TABLE institucion ADD representante VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE institucion ALTER telefono DROP NOT NULL');
        $this->addSql('ALTER TABLE institucion ALTER correo DROP NOT NULL');
        $this->addSql('ALTER TABLE institucion ALTER fax DROP NOT NULL');
        $this->addSql('ALTER TABLE institucion ALTER sitio_web DROP NOT NULL');
        $this->addSql('ALTER TABLE institucion ALTER cedula_identificacion DROP NOT NULL');
        $this->addSql('ALTER TABLE institucion ALTER rfc DROP NOT NULL');
        $this->addSql('ALTER TABLE institucion ALTER direccion DROP NOT NULL');
        $this->addSql('ALTER TABLE ciclo_academico ADD activo BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE carrera ALTER nombre TYPE VARCHAR(35)');
        $this->addSql('ALTER TABLE delegacion ADD longitud DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE delegacion DROP altitud');
        $this->addSql('ALTER TABLE delegacion ALTER nombre TYPE VARCHAR(30)');
        $this->addSql('ALTER TABLE delegacion ALTER latitud DROP NOT NULL');
        $this->addSql('ALTER TABLE delegacion ALTER grupo_delegacion TYPE VARCHAR(4)');
        $this->addSql('ALTER TABLE delegacion ALTER nombre_grupo_delegacion TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE delegacion ALTER fecha TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE delegacion ALTER fecha DROP DEFAULT');
        $this->addSql('ALTER TABLE unidad ADD longitud DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE unidad DROP altitud');
        $this->addSql('ALTER TABLE unidad ALTER clave_unidad TYPE VARCHAR(15)');
        $this->addSql('ALTER TABLE unidad ALTER clave_presupuestal TYPE VARCHAR(15)');
        $this->addSql('ALTER TABLE unidad ALTER nivel_atencion DROP NOT NULL');
        $this->addSql('ALTER TABLE unidad ALTER direccion DROP NOT NULL');
        $this->addSql('ALTER TABLE unidad ALTER nombre_unidad_principal DROP NOT NULL');
        $this->addSql('ALTER TABLE unidad ALTER nombre_unidad_principal TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE unidad ALTER clave_unidad_principal DROP NOT NULL');
        $this->addSql('ALTER TABLE unidad ALTER latitud DROP NOT NULL');
        $this->addSql('ALTER TABLE tipo_unidad ADD nivel INT NOT NULL');
        $this->addSql('ALTER TABLE tipo_unidad ALTER nombre TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE tipo_unidad ALTER descripcion TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE tipo_unidad ALTER grupo_tipo TYPE VARCHAR(6)');
        $this->addSql('ALTER TABLE tipo_unidad ALTER grupo_nombre TYPE VARCHAR(50)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE campo_clinico DROP CONSTRAINT FK_3307999A44D087A7');
        $this->addSql('ALTER TABLE expediente DROP CONSTRAINT FK_D59CA4131CB9D6E4');
        $this->addSql('ALTER TABLE campo_clinico DROP CONSTRAINT FK_3307999A1CB9D6E4');
        $this->addSql('DROP TABLE pago');
        $this->addSql('DROP TABLE estatus_campo');
        $this->addSql('DROP TABLE expediente');
        $this->addSql('DROP TABLE campo_clinico');
        $this->addSql('DROP TABLE solicitud');
        $this->addSql('ALTER TABLE region ALTER nombre TYPE VARCHAR(30)');
        $this->addSql('ALTER TABLE departamento ALTER nombre TYPE VARCHAR(30)');
        $this->addSql('ALTER TABLE departamento ALTER clave_departamental TYPE VARCHAR(12)');
        $this->addSql('ALTER TABLE departamento ALTER clave_presupuestal TYPE VARCHAR(12)');
        $this->addSql('ALTER TABLE departamento ALTER fecha TYPE DATE');
        $this->addSql('ALTER TABLE departamento ALTER fecha DROP DEFAULT');
        $this->addSql('ALTER TABLE tipo_unidad DROP nivel');
        $this->addSql('ALTER TABLE tipo_unidad ALTER nombre TYPE VARCHAR(150)');
        $this->addSql('ALTER TABLE tipo_unidad ALTER descripcion TYPE VARCHAR(150)');
        $this->addSql('ALTER TABLE tipo_unidad ALTER grupo_tipo TYPE VARCHAR(5)');
        $this->addSql('ALTER TABLE tipo_unidad ALTER grupo_nombre TYPE VARCHAR(150)');
        $this->addSql('ALTER TABLE unidad ADD altitud DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE unidad DROP longitud');
        $this->addSql('ALTER TABLE unidad ALTER clave_unidad TYPE VARCHAR(12)');
        $this->addSql('ALTER TABLE unidad ALTER clave_presupuestal TYPE VARCHAR(12)');
        $this->addSql('ALTER TABLE unidad ALTER nivel_atencion SET NOT NULL');
        $this->addSql('ALTER TABLE unidad ALTER direccion SET NOT NULL');
        $this->addSql('ALTER TABLE unidad ALTER nombre_unidad_principal SET NOT NULL');
        $this->addSql('ALTER TABLE unidad ALTER nombre_unidad_principal TYPE VARCHAR(200)');
        $this->addSql('ALTER TABLE unidad ALTER clave_unidad_principal SET NOT NULL');
        $this->addSql('ALTER TABLE unidad ALTER latitud SET NOT NULL');
        $this->addSql('ALTER TABLE ciclo_academico DROP activo');
        $this->addSql('ALTER TABLE institucion DROP representante');
        $this->addSql('ALTER TABLE institucion ALTER telefono SET NOT NULL');
        $this->addSql('ALTER TABLE institucion ALTER correo SET NOT NULL');
        $this->addSql('ALTER TABLE institucion ALTER fax SET NOT NULL');
        $this->addSql('ALTER TABLE institucion ALTER sitio_web SET NOT NULL');
        $this->addSql('ALTER TABLE institucion ALTER cedula_identificacion SET NOT NULL');
        $this->addSql('ALTER TABLE institucion ALTER rfc SET NOT NULL');
        $this->addSql('ALTER TABLE institucion ALTER direccion SET NOT NULL');
        $this->addSql('ALTER TABLE categoria ALTER nombre TYPE VARCHAR(30)');
        $this->addSql('ALTER TABLE categoria ALTER clave TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE convenio DROP nombre');
        $this->addSql('ALTER TABLE convenio ALTER institucion_id DROP NOT NULL');
        $this->addSql('ALTER TABLE delegacion ADD altitud DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE delegacion DROP longitud');
        $this->addSql('ALTER TABLE delegacion ALTER nombre TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE delegacion ALTER grupo_delegacion TYPE VARCHAR(5)');
        $this->addSql('ALTER TABLE delegacion ALTER nombre_grupo_delegacion TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE delegacion ALTER fecha TYPE DATE');
        $this->addSql('ALTER TABLE delegacion ALTER fecha DROP DEFAULT');
        $this->addSql('ALTER TABLE delegacion ALTER latitud SET NOT NULL');
        $this->addSql('ALTER TABLE carrera ALTER nombre TYPE VARCHAR(30)');
    }
}
