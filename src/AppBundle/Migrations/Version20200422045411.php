<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200422045411 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE campo_clinico (id SERIAL NOT NULL, ciclo_academico_id INT DEFAULT NULL, carrera_id INT DEFAULT NULL, convenio_id INT DEFAULT NULL, fecha_inicial DATE NOT NULL, fecha_final DATE NOT NULL, horario VARCHAR(100) NOT NULL, promocion VARCHAR(100) NOT NULL, lugares_solicitados INT NOT NULL, lugares_autorizados INT NOT NULL, referencia_bancaria VARCHAR(100) NOT NULL, monto DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3307999AA7D9417F ON campo_clinico (ciclo_academico_id)');
        $this->addSql('CREATE INDEX IDX_3307999AC671B40F ON campo_clinico (carrera_id)');
        $this->addSql('CREATE INDEX IDX_3307999AF9D43F2A ON campo_clinico (convenio_id)');
        $this->addSql('ALTER TABLE campo_clinico ADD CONSTRAINT FK_3307999AA7D9417F FOREIGN KEY (ciclo_academico_id) REFERENCES ciclo_academico (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE campo_clinico ADD CONSTRAINT FK_3307999AC671B40F FOREIGN KEY (carrera_id) REFERENCES carrera (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE campo_clinico ADD CONSTRAINT FK_3307999AF9D43F2A FOREIGN KEY (convenio_id) REFERENCES convenio (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE campo_clinico');
    }
}
