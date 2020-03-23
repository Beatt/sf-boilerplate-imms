<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200322232839 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE region (id SERIAL NOT NULL, nombre VARCHAR(30) NOT NULL, activo BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE institucion (id SERIAL NOT NULL, nombre VARCHAR(255) NOT NULL, telefono VARCHAR(16) NOT NULL, correo VARCHAR(254) NOT NULL, fax VARCHAR(254) NOT NULL, sitio_web VARCHAR(100) NOT NULL, cedula_identificacion VARCHAR(255) NOT NULL, rfc VARCHAR(13) NOT NULL, direccion VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE ciclo_academico (id SERIAL NOT NULL, nombre VARCHAR(30) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE carrera (id SERIAL NOT NULL, nombre VARCHAR(30) NOT NULL, activo BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE delegacion (id SERIAL NOT NULL, region_id INT DEFAULT NULL, nombre VARCHAR(100) NOT NULL, activo BOOLEAN NOT NULL, clave_delegacional VARCHAR(2) NOT NULL, latitud NUMERIC(24, 4) NOT NULL, altitud NUMERIC(24, 4) NOT NULL, grupo_delegacion VARCHAR(5) NOT NULL, nombre_grupo_delegacion VARCHAR(100) NOT NULL, fecha DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E4E12C4B98260155 ON delegacion (region_id)');
        $this->addSql('CREATE TABLE unidad (id SERIAL NOT NULL, delegacion_id INT DEFAULT NULL, tipo_unidad_id INT DEFAULT NULL, nombre VARCHAR(100) NOT NULL, clave_unidad VARCHAR(12) NOT NULL, clave_presupuestal VARCHAR(12) NOT NULL, nivel_atencion INT NOT NULL, es_umae BOOLEAN NOT NULL, direccion VARCHAR(255) NOT NULL, nombre_unidad_principal VARCHAR(200) NOT NULL, clave_unidad_principal VARCHAR(2) NOT NULL, anio INT NOT NULL, fecha DATE NOT NULL, activo BOOLEAN NOT NULL, latitud NUMERIC(24, 4) NOT NULL, altitud NUMERIC(24, 4) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F3E6D02FF4B21EB5 ON unidad (delegacion_id)');
        $this->addSql('CREATE INDEX IDX_F3E6D02F7F6FF902 ON unidad (tipo_unidad_id)');
        $this->addSql('CREATE TABLE tipo_unidad (id SERIAL NOT NULL, nombre VARCHAR(150) NOT NULL, descripcion VARCHAR(150) NOT NULL, grupo_tipo VARCHAR(5) NOT NULL, grupo_nombre VARCHAR(150) NOT NULL, activo BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE delegacion ADD CONSTRAINT FK_E4E12C4B98260155 FOREIGN KEY (region_id) REFERENCES region (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE unidad ADD CONSTRAINT FK_F3E6D02FF4B21EB5 FOREIGN KEY (delegacion_id) REFERENCES delegacion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE unidad ADD CONSTRAINT FK_F3E6D02F7F6FF902 FOREIGN KEY (tipo_unidad_id) REFERENCES tipo_unidad (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE delegacion DROP CONSTRAINT FK_E4E12C4B98260155');
        $this->addSql('ALTER TABLE unidad DROP CONSTRAINT FK_F3E6D02FF4B21EB5');
        $this->addSql('ALTER TABLE unidad DROP CONSTRAINT FK_F3E6D02F7F6FF902');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE institucion');
        $this->addSql('DROP TABLE ciclo_academico');
        $this->addSql('DROP TABLE carrera');
        $this->addSql('DROP TABLE delegacion');
        $this->addSql('DROP TABLE unidad');
        $this->addSql('DROP TABLE tipo_unidad');
    }
}
