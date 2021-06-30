<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20210628174354 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE campo_clinico ADD observaciones VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario ALTER regims DROP NOT NULL');
        $this->addSql('ALTER TABLE monto_carrera DROP CONSTRAINT fk_bcc32a561cb9d6e4');
        $this->addSql('DROP INDEX idx_bcc32a561cb9d6e4');
        $this->addSql('ALTER TABLE monto_carrera RENAME COLUMN solicitud_id TO campo_clinico_id');
        $this->addSql('ALTER TABLE monto_carrera ADD CONSTRAINT FK_BCC32A56A8225CD5 FOREIGN KEY (campo_clinico_id) REFERENCES campo_clinico (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BCC32A56A8225CD5 ON monto_carrera (campo_clinico_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE usuario ALTER regims DROP NOT NULL');
        $this->addSql('ALTER TABLE usuario ALTER regims SET NOT NULL');
        $this->addSql('ALTER TABLE campo_clinico DROP observaciones');
        $this->addSql('ALTER TABLE monto_carrera DROP CONSTRAINT FK_BCC32A56A8225CD5');
        $this->addSql('DROP INDEX UNIQ_BCC32A56A8225CD5');
        $this->addSql('ALTER TABLE monto_carrera RENAME COLUMN campo_clinico_id TO solicitud_id');
        $this->addSql('ALTER TABLE monto_carrera ADD CONSTRAINT fk_bcc32a561cb9d6e4 FOREIGN KEY (solicitud_id) REFERENCES solicitud (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_bcc32a561cb9d6e4 ON monto_carrera (solicitud_id)');
    }
}
