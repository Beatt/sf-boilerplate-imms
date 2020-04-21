<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200420203245 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE categoria ALTER nombre TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE categoria ALTER clave TYPE VARCHAR(15)');
        $this->addSql('ALTER TABLE departamento ALTER nombre TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE departamento ALTER clave_departamental TYPE VARCHAR(15)');
        $this->addSql('ALTER TABLE departamento ALTER clave_presupuestal TYPE VARCHAR(15)');
        $this->addSql('ALTER TABLE departamento ALTER fecha TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE departamento ALTER fecha DROP DEFAULT');
        $this->addSql('ALTER TABLE institucion ALTER telefono DROP NOT NULL');
        $this->addSql('ALTER TABLE institucion ALTER correo DROP NOT NULL');
        $this->addSql('ALTER TABLE institucion ALTER fax DROP NOT NULL');
        $this->addSql('ALTER TABLE institucion ALTER sitio_web DROP NOT NULL');
        $this->addSql('ALTER TABLE institucion ALTER cedula_identificacion DROP NOT NULL');
        $this->addSql('ALTER TABLE institucion ALTER rfc DROP NOT NULL');
        $this->addSql('ALTER TABLE institucion ALTER direccion DROP NOT NULL');
        $this->addSql('ALTER TABLE tipo_unidad ADD nivel INT NOT NULL');
        $this->addSql('ALTER TABLE tipo_unidad ALTER nombre TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE tipo_unidad ALTER descripcion TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE tipo_unidad ALTER grupo_tipo TYPE VARCHAR(6)');
        $this->addSql('ALTER TABLE tipo_unidad ALTER grupo_nombre TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE unidad ALTER clave_unidad TYPE VARCHAR(15)');
        $this->addSql('ALTER TABLE unidad ALTER clave_presupuestal TYPE VARCHAR(15)');
        $this->addSql('ALTER TABLE unidad ALTER nivel_atencion DROP NOT NULL');
        $this->addSql('ALTER TABLE unidad ALTER direccion DROP NOT NULL');
        $this->addSql('ALTER TABLE unidad ALTER nombre_unidad_principal DROP NOT NULL');
        $this->addSql('ALTER TABLE unidad ALTER nombre_unidad_principal TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE unidad ALTER clave_unidad_principal DROP NOT NULL');
        $this->addSql('ALTER TABLE unidad ALTER latitud DROP NOT NULL');
        $this->addSql('ALTER TABLE unidad ALTER longitud DROP NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tipo_unidad DROP nivel');
        $this->addSql('ALTER TABLE tipo_unidad ALTER nombre TYPE VARCHAR(150)');
        $this->addSql('ALTER TABLE tipo_unidad ALTER descripcion TYPE VARCHAR(150)');
        $this->addSql('ALTER TABLE tipo_unidad ALTER grupo_tipo TYPE VARCHAR(5)');
        $this->addSql('ALTER TABLE tipo_unidad ALTER grupo_nombre TYPE VARCHAR(150)');
        $this->addSql('ALTER TABLE unidad ALTER clave_unidad TYPE VARCHAR(12)');
        $this->addSql('ALTER TABLE unidad ALTER clave_presupuestal TYPE VARCHAR(12)');
        $this->addSql('ALTER TABLE unidad ALTER nivel_atencion SET NOT NULL');
        $this->addSql('ALTER TABLE unidad ALTER direccion SET NOT NULL');
        $this->addSql('ALTER TABLE unidad ALTER nombre_unidad_principal SET NOT NULL');
        $this->addSql('ALTER TABLE unidad ALTER nombre_unidad_principal TYPE VARCHAR(200)');
        $this->addSql('ALTER TABLE unidad ALTER clave_unidad_principal SET NOT NULL');
        $this->addSql('ALTER TABLE unidad ALTER latitud SET NOT NULL');
        $this->addSql('ALTER TABLE unidad ALTER longitud SET NOT NULL');
        $this->addSql('ALTER TABLE institucion ALTER telefono SET NOT NULL');
        $this->addSql('ALTER TABLE institucion ALTER correo SET NOT NULL');
        $this->addSql('ALTER TABLE institucion ALTER fax SET NOT NULL');
        $this->addSql('ALTER TABLE institucion ALTER sitio_web SET NOT NULL');
        $this->addSql('ALTER TABLE institucion ALTER cedula_identificacion SET NOT NULL');
        $this->addSql('ALTER TABLE institucion ALTER rfc SET NOT NULL');
        $this->addSql('ALTER TABLE institucion ALTER direccion SET NOT NULL');
        $this->addSql('ALTER TABLE departamento ALTER nombre TYPE VARCHAR(30)');
        $this->addSql('ALTER TABLE departamento ALTER clave_departamental TYPE VARCHAR(12)');
        $this->addSql('ALTER TABLE departamento ALTER clave_presupuestal TYPE VARCHAR(12)');
        $this->addSql('ALTER TABLE departamento ALTER fecha TYPE DATE');
        $this->addSql('ALTER TABLE departamento ALTER fecha DROP DEFAULT');
        $this->addSql('ALTER TABLE categoria ALTER nombre TYPE VARCHAR(30)');
        $this->addSql('ALTER TABLE categoria ALTER clave TYPE VARCHAR(255)');
    }
}
