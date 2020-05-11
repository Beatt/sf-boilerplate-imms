<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200508055126 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE monto_carrera ADD carrera_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE monto_carrera ADD CONSTRAINT FK_BCC32A56C671B40F FOREIGN KEY (carrera_id) REFERENCES carrera (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_BCC32A56C671B40F ON monto_carrera (carrera_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE monto_carrera DROP CONSTRAINT FK_BCC32A56C671B40F');
        $this->addSql('DROP INDEX IDX_BCC32A56C671B40F');
        $this->addSql('ALTER TABLE monto_carrera DROP carrera_id');
    }
}
