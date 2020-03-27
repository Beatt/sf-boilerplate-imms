<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200327052623 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE carrera ADD nivel_academico_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE carrera ADD CONSTRAINT FK_CF1ECD30C21F5FA8 FOREIGN KEY (nivel_academico_id) REFERENCES nivel_academico (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_CF1ECD30C21F5FA8 ON carrera (nivel_academico_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE carrera DROP CONSTRAINT FK_CF1ECD30C21F5FA8');
        $this->addSql('DROP INDEX IDX_CF1ECD30C21F5FA8');
        $this->addSql('ALTER TABLE carrera DROP nivel_academico_id');
    }
}
