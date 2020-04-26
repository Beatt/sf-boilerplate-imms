<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200426223245 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE campo_clinico ADD estatus_campo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE campo_clinico ADD CONSTRAINT FK_3307999A44D087A7 FOREIGN KEY (estatus_campo_id) REFERENCES estatus_campo (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3307999A44D087A7 ON campo_clinico (estatus_campo_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE campo_clinico DROP CONSTRAINT FK_3307999A44D087A7');
        $this->addSql('DROP INDEX IDX_3307999A44D087A7');
        $this->addSql('ALTER TABLE campo_clinico DROP estatus_campo_id');
    }
}
