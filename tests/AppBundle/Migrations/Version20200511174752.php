<?php

namespace Tests\AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200511174752 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE pago ADD fecha_pago DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE factura ALTER monto TYPE DOUBLE PRECISION');
        $this->addSql('ALTER TABLE factura ALTER monto DROP DEFAULT');
        $this->addSql('ALTER TABLE factura ALTER monto DROP NOT NULL');
        $this->addSql('ALTER TABLE factura ALTER folio TYPE VARCHAR(100)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE factura ALTER monto TYPE NUMERIC(10, 4)');
        $this->addSql('ALTER TABLE factura ALTER monto DROP DEFAULT');
        $this->addSql('ALTER TABLE factura ALTER monto SET NOT NULL');
        $this->addSql('ALTER TABLE factura ALTER folio TYPE VARCHAR(10)');
        $this->addSql('ALTER TABLE pago DROP fecha_pago');
    }
}
