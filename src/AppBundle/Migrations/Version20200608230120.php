<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200608230120 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE usuario_permiso (usuario_id INT NOT NULL, permiso_id INT NOT NULL, PRIMARY KEY(usuario_id, permiso_id))');
        $this->addSql('CREATE INDEX IDX_845C01D9DB38439E ON usuario_permiso (usuario_id)');
        $this->addSql('CREATE INDEX IDX_845C01D96CEFAD37 ON usuario_permiso (permiso_id)');
        $this->addSql('ALTER TABLE usuario_permiso ADD CONSTRAINT FK_845C01D9DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE usuario_permiso ADD CONSTRAINT FK_845C01D96CEFAD37 FOREIGN KEY (permiso_id) REFERENCES permiso (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F751F7C3DB38439E ON institucion (usuario_id)');
        $this->addSql('ALTER TABLE permiso RENAME COLUMN descripcion TO clave');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE usuario_permiso');
        $this->addSql('DROP INDEX UNIQ_F751F7C3DB38439E');
        $this->addSql('ALTER TABLE permiso RENAME COLUMN clave TO descripcion');
    }
}
