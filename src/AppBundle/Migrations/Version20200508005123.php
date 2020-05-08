<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200508005123 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE factura (id SERIAL NOT NULL, zip VARCHAR(100) NOT NULL, fecha_facturacion DATE NOT NULL, monto NUMERIC(10, 4) NOT NULL, folio VARCHAR(10) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE pago ADD factura_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pago DROP xml');
        $this->addSql('ALTER TABLE pago DROP pdf');
        $this->addSql('ALTER TABLE pago ALTER solicitud_id DROP NOT NULL');
        $this->addSql('ALTER TABLE pago RENAME COLUMN factura TO requiere_factura');
        $this->addSql('ALTER TABLE pago ADD CONSTRAINT FK_F4DF5F3E1CB9D6E4 FOREIGN KEY (solicitud_id) REFERENCES solicitud (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pago ADD CONSTRAINT FK_F4DF5F3EF04F795F FOREIGN KEY (factura_id) REFERENCES factura (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F4DF5F3E1CB9D6E4 ON pago (solicitud_id)');
        $this->addSql('CREATE INDEX IDX_F4DF5F3EF04F795F ON pago (factura_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE pago DROP CONSTRAINT FK_F4DF5F3EF04F795F');
        $this->addSql('DROP TABLE factura');
        $this->addSql('ALTER TABLE pago DROP CONSTRAINT FK_F4DF5F3E1CB9D6E4');
        $this->addSql('DROP INDEX IDX_F4DF5F3E1CB9D6E4');
        $this->addSql('DROP INDEX IDX_F4DF5F3EF04F795F');
        $this->addSql('ALTER TABLE pago ADD xml VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE pago ADD pdf VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE pago DROP factura_id');
        $this->addSql('ALTER TABLE pago ALTER solicitud_id SET NOT NULL');
        $this->addSql('ALTER TABLE pago RENAME COLUMN requiere_factura TO factura');
    }
}
