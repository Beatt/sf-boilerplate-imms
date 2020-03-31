<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200331193445 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE delegacion ALTER latitud TYPE DOUBLE PRECISION');
        $this->addSql('ALTER TABLE delegacion ALTER latitud DROP DEFAULT');
        $this->addSql('ALTER TABLE delegacion ALTER altitud TYPE DOUBLE PRECISION');
        $this->addSql('ALTER TABLE delegacion ALTER altitud DROP DEFAULT');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE delegacion ALTER latitud TYPE NUMERIC(24, 4)');
        $this->addSql('ALTER TABLE delegacion ALTER latitud DROP DEFAULT');
        $this->addSql('ALTER TABLE delegacion ALTER altitud TYPE NUMERIC(24, 4)');
        $this->addSql('ALTER TABLE delegacion ALTER altitud DROP DEFAULT');
    }
}
