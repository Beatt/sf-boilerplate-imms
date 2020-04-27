<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200427033342 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE campo_clinico ADD unidad_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE campo_clinico ADD CONSTRAINT FK_3307999A9D01464C FOREIGN KEY (unidad_id) REFERENCES unidad (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3307999A9D01464C ON campo_clinico (unidad_id)');
        $this->addSql('ALTER TABLE convenio DROP CONSTRAINT fk_25577244da3426ae');
        $this->addSql('DROP INDEX idx_25577244da3426ae');
        $this->addSql('ALTER TABLE convenio DROP nivel_id');
        $this->addSql('ALTER TABLE institucion ADD representante VARCHAR(100) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE institucion DROP representante');
        $this->addSql('ALTER TABLE convenio ADD nivel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE convenio ADD CONSTRAINT fk_25577244da3426ae FOREIGN KEY (nivel_id) REFERENCES nivel_academico (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_25577244da3426ae ON convenio (nivel_id)');
        $this->addSql('ALTER TABLE campo_clinico DROP CONSTRAINT FK_3307999A9D01464C');
        $this->addSql('DROP INDEX IDX_3307999A9D01464C');
        $this->addSql('ALTER TABLE campo_clinico DROP unidad_id');
    }
}
