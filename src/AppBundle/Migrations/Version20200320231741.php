<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200320231741 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE seccion_traduccion (id SERIAL NOT NULL, translatable_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, descripcion VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4091C6042C2AC5D3 ON seccion_traduccion (translatable_id)');
        $this->addSql('CREATE UNIQUE INDEX seccion_traduccion_unique_translation ON seccion_traduccion (translatable_id, locale)');
        $this->addSql('CREATE TABLE seccion (id SERIAL NOT NULL, modulo VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE seccion_traduccion ADD CONSTRAINT FK_4091C6042C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES seccion (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE seccion_traduccion DROP CONSTRAINT FK_4091C6042C2AC5D3');
        $this->addSql('DROP TABLE seccion_traduccion');
        $this->addSql('DROP TABLE seccion');
    }
}
