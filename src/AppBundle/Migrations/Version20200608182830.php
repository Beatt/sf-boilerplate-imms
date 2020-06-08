<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200608182830 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE usuario_rol');
        $this->addSql('ALTER TABLE usuario ADD rol_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario ADD CONSTRAINT FK_2265B05D4BAB96C FOREIGN KEY (rol_id) REFERENCES rol (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2265B05D4BAB96C ON usuario (rol_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE usuario_rol (usuario_id INT NOT NULL, rol_id INT NOT NULL, PRIMARY KEY(usuario_id, rol_id))');
        $this->addSql('CREATE INDEX idx_72edd1a4db38439e ON usuario_rol (usuario_id)');
        $this->addSql('CREATE INDEX idx_72edd1a44bab96c ON usuario_rol (rol_id)');
        $this->addSql('ALTER TABLE usuario_rol ADD CONSTRAINT fk_72edd1a4db38439e FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE usuario_rol ADD CONSTRAINT fk_72edd1a44bab96c FOREIGN KEY (rol_id) REFERENCES rol (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE usuario DROP CONSTRAINT FK_2265B05D4BAB96C');
        $this->addSql('DROP INDEX IDX_2265B05D4BAB96C');
        $this->addSql('ALTER TABLE usuario DROP rol_id');
    }
}
