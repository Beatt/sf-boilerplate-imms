<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200608170623 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE permiso_rol');
        $this->addSql('ALTER TABLE permiso ADD rol_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE permiso ADD CONSTRAINT FK_FD7AAB9E4BAB96C FOREIGN KEY (rol_id) REFERENCES rol (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_FD7AAB9E4BAB96C ON permiso (rol_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE permiso_rol (permiso_id INT NOT NULL, rol_id INT NOT NULL, PRIMARY KEY(permiso_id, rol_id))');
        $this->addSql('CREATE INDEX idx_dd501d066cefad37 ON permiso_rol (permiso_id)');
        $this->addSql('CREATE INDEX idx_dd501d064bab96c ON permiso_rol (rol_id)');
        $this->addSql('ALTER TABLE permiso_rol ADD CONSTRAINT fk_dd501d066cefad37 FOREIGN KEY (permiso_id) REFERENCES permiso (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE permiso_rol ADD CONSTRAINT fk_dd501d064bab96c FOREIGN KEY (rol_id) REFERENCES rol (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE permiso DROP CONSTRAINT FK_FD7AAB9E4BAB96C');
        $this->addSql('DROP INDEX IDX_FD7AAB9E4BAB96C');
        $this->addSql('ALTER TABLE permiso DROP rol_id');
    }
}
