<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200323002206 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE departamento (id SERIAL NOT NULL, unidad_id INT DEFAULT NULL, nombre VARCHAR(30) NOT NULL, clave_departamental VARCHAR(12) NOT NULL, clave_presupuestal VARCHAR(12) NOT NULL, es_unidad BOOLEAN NOT NULL, anio INT NOT NULL, fecha DATE NOT NULL, activo BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_40E497EB9D01464C ON departamento (unidad_id)');
        $this->addSql('CREATE TABLE nivel_academico (id SERIAL NOT NULL, nombre VARCHAR(30) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE departamento ADD CONSTRAINT FK_40E497EB9D01464C FOREIGN KEY (unidad_id) REFERENCES unidad (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE departamento');
        $this->addSql('DROP TABLE nivel_academico');
    }
}
