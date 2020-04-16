<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200416184008 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE carrera ALTER nombre TYPE VARCHAR(35)');
        $this->addSql('ALTER TABLE delegacion ADD longitud DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE delegacion DROP altitud');
        $this->addSql('ALTER TABLE delegacion ALTER nombre TYPE VARCHAR(30)');
        $this->addSql('ALTER TABLE delegacion ALTER latitud DROP NOT NULL');
        $this->addSql('ALTER TABLE delegacion ALTER grupo_delegacion TYPE VARCHAR(4)');
        $this->addSql('ALTER TABLE delegacion ALTER nombre_grupo_delegacion TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE delegacion ALTER fecha TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE delegacion ALTER fecha DROP DEFAULT');
        $this->addSql('ALTER TABLE region ALTER nombre TYPE VARCHAR(20)');
        $this->addSql('ALTER TABLE unidad RENAME COLUMN altitud TO longitud');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE unidad RENAME COLUMN longitud TO altitud');
        $this->addSql('ALTER TABLE region ALTER nombre TYPE VARCHAR(30)');
        $this->addSql('ALTER TABLE carrera ALTER nombre TYPE VARCHAR(30)');
        $this->addSql('ALTER TABLE delegacion ADD altitud DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE delegacion DROP longitud');
        $this->addSql('ALTER TABLE delegacion ALTER nombre TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE delegacion ALTER grupo_delegacion TYPE VARCHAR(5)');
        $this->addSql('ALTER TABLE delegacion ALTER nombre_grupo_delegacion TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE delegacion ALTER fecha TYPE DATE');
        $this->addSql('ALTER TABLE delegacion ALTER fecha DROP DEFAULT');
        $this->addSql('ALTER TABLE delegacion ALTER latitud SET NOT NULL');
    }
}
