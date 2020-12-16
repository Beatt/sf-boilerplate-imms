<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20201102180711 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE descuento_monto (id SERIAL NOT NULL, monto_carrera_id INT DEFAULT NULL, num_alumnos INT NOT NULL, descuento_inscripcion DOUBLE PRECISION NOT NULL, descuento_colegiatura DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1DCB6CDE6376C8AC ON descuento_monto (monto_carrera_id)');
        $this->addSql('ALTER TABLE descuento_monto ADD CONSTRAINT FK_1DCB6CDE6376C8AC FOREIGN KEY (monto_carrera_id) REFERENCES monto_carrera (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE descuento_monto');
    }
}
