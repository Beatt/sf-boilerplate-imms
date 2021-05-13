<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20210513211529 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE usuario_unidad (usuario_id INT NOT NULL, unidad_id INT NOT NULL, PRIMARY KEY(usuario_id, unidad_id))');
        $this->addSql('CREATE INDEX IDX_9F43326DB38439E ON usuario_unidad (usuario_id)');
        $this->addSql('CREATE INDEX IDX_9F433269D01464C ON usuario_unidad (unidad_id)');
        $this->addSql('ALTER TABLE usuario_unidad ADD CONSTRAINT FK_9F43326DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE usuario_unidad ADD CONSTRAINT FK_9F433269D01464C FOREIGN KEY (unidad_id) REFERENCES unidad (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE usuario_unidad');
    }
}
