<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200323044311 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE convenio (id SERIAL NOT NULL, nivel_id INT DEFAULT NULL, ciclo_academico_id INT DEFAULT NULL, carrera_id INT DEFAULT NULL, institucion_id INT DEFAULT NULL, delegacion_id INT DEFAULT NULL, sector VARCHAR(250) NOT NULL, tipo VARCHAR(250) NOT NULL, vigencia DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_25577244DA3426AE ON convenio (nivel_id)');
        $this->addSql('CREATE INDEX IDX_25577244A7D9417F ON convenio (ciclo_academico_id)');
        $this->addSql('CREATE INDEX IDX_25577244C671B40F ON convenio (carrera_id)');
        $this->addSql('CREATE INDEX IDX_25577244B239FBC6 ON convenio (institucion_id)');
        $this->addSql('CREATE INDEX IDX_25577244F4B21EB5 ON convenio (delegacion_id)');
        $this->addSql('ALTER TABLE convenio ADD CONSTRAINT FK_25577244DA3426AE FOREIGN KEY (nivel_id) REFERENCES nivel_academico (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE convenio ADD CONSTRAINT FK_25577244A7D9417F FOREIGN KEY (ciclo_academico_id) REFERENCES ciclo_academico (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE convenio ADD CONSTRAINT FK_25577244C671B40F FOREIGN KEY (carrera_id) REFERENCES carrera (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE convenio ADD CONSTRAINT FK_25577244B239FBC6 FOREIGN KEY (institucion_id) REFERENCES institucion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE convenio ADD CONSTRAINT FK_25577244F4B21EB5 FOREIGN KEY (delegacion_id) REFERENCES delegacion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE convenio');
    }
}
