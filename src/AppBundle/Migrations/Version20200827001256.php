<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200827001256 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE bitacora_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE bitacora (id INT NOT NULL, usuario_id INT NOT NULL, message TEXT NOT NULL, context TEXT NOT NULL, level SMALLINT NOT NULL, level_name VARCHAR(50) NOT NULL, extra TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9087FEF9DB38439E ON bitacora (usuario_id)');
        $this->addSql('COMMENT ON COLUMN bitacora.context IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN bitacora.extra IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE bitacora ADD CONSTRAINT FK_9087FEF9DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE bitacora_id_seq CASCADE');
        $this->addSql('DROP TABLE bitacora');
    }
}
