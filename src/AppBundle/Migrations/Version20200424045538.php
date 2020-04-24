<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200424045538 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE convenio ADD solicitud_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE convenio ADD CONSTRAINT FK_255772441CB9D6E4 FOREIGN KEY (solicitud_id) REFERENCES solicitud (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_255772441CB9D6E4 ON convenio (solicitud_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE convenio DROP CONSTRAINT FK_255772441CB9D6E4');
        $this->addSql('DROP INDEX IDX_255772441CB9D6E4');
        $this->addSql('ALTER TABLE convenio DROP solicitud_id');
    }
}
