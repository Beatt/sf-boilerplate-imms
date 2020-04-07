<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200407043007 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE usuario_delegacion (usuario_id INT NOT NULL, delegacion_id INT NOT NULL, PRIMARY KEY(usuario_id, delegacion_id))');
        $this->addSql('CREATE INDEX IDX_17D166E9DB38439E ON usuario_delegacion (usuario_id)');
        $this->addSql('CREATE INDEX IDX_17D166E9F4B21EB5 ON usuario_delegacion (delegacion_id)');
        $this->addSql('ALTER TABLE usuario_delegacion ADD CONSTRAINT FK_17D166E9DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE usuario_delegacion ADD CONSTRAINT FK_17D166E9F4B21EB5 FOREIGN KEY (delegacion_id) REFERENCES delegacion (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE usuario_departamento');
        $this->addSql('ALTER TABLE usuario ADD departamento_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario ADD CONSTRAINT FK_2265B05D5A91C08D FOREIGN KEY (departamento_id) REFERENCES departamento (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2265B05D5A91C08D ON usuario (departamento_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE usuario_departamento (usuario_id INT NOT NULL, departamento_id INT NOT NULL, PRIMARY KEY(usuario_id, departamento_id))');
        $this->addSql('CREATE INDEX idx_4adc123edb38439e ON usuario_departamento (usuario_id)');
        $this->addSql('CREATE INDEX idx_4adc123e5a91c08d ON usuario_departamento (departamento_id)');
        $this->addSql('ALTER TABLE usuario_departamento ADD CONSTRAINT fk_4adc123edb38439e FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE usuario_departamento ADD CONSTRAINT fk_4adc123e5a91c08d FOREIGN KEY (departamento_id) REFERENCES departamento (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE usuario_delegacion');
        $this->addSql('ALTER TABLE usuario DROP CONSTRAINT FK_2265B05D5A91C08D');
        $this->addSql('DROP INDEX IDX_2265B05D5A91C08D');
        $this->addSql('ALTER TABLE usuario DROP departamento_id');
    }
}
