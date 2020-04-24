<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200424060443 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE expediente (id SERIAL NOT NULL, solicitud_id INT DEFAULT NULL, descripcion VARCHAR(255) NOT NULL, url_archivo VARCHAR(200) NOT NULL, fecha DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D59CA4131CB9D6E4 ON expediente (solicitud_id)');
        $this->addSql('ALTER TABLE expediente ADD CONSTRAINT FK_D59CA4131CB9D6E4 FOREIGN KEY (solicitud_id) REFERENCES solicitud (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE expediente');
    }
}
