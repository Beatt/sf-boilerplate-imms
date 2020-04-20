<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200420213818 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        /* Table solicitud */
        $this->addSQL("CREATE TABLE solicitud (id INT NOT NULL, no_solicitud VARCHAR(5) NOT NULL, fecha DATE NOT NULL, estatus INT NOT NULL, referencia_bancaria VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id));");
        $this->addSql("CREATE UNIQUE INDEX UNIQ_96D27CC0415EAE2C ON solicitud (no_solicitud);");
        $this->addSql("CREATE SEQUENCE expediente_id_seq INCREMENT BY 1 MINVALUE 1 START 1;");
        $this->addSql("CREATE SEQUENCE estatus_campo_id_seq INCREMENT BY 1 MINVALUE 1 START 1;");
        $this->addSql("CREATE SEQUENCE pago_id_seq INCREMENT BY 1 MINVALUE 1 START 1;");
        $this->addSql("CREATE SEQUENCE campo_clinico_id_seq INCREMENT BY 1 MINVALUE 1 START 1;");
        $this->addSql("CREATE TABLE expediente (id INT NOT NULL, descripcion TEXT DEFAULT NULL, url_archivo VARCHAR(255) NOT NULL, solicitud_id INT NOT NULL, fecha DATE NOT NULL, PRIMARY KEY(id));");
        $this->addSql("CREATE TABLE estatus_campo (id INT NOT NULL, estatus VARCHAR(50) NOT NULL, PRIMARY KEY(id));");
        $this->addSql("CREATE TABLE pago (id INT NOT NULL, monto NUMERIC(10, 4) NOT NULL, solicitud_id INT NOT NULL, comprobante_pago VARCHAR(100) NOT NULL, referencia_bancaria VARCHAR(100) NOT NULL, validado BOOLEAN NOT NULL, xml VARCHAR(100) NOT NULL, pdf VARCHAR(100) NOT NULL, factura BOOLEAN NOT NULL, observaciones VARCHAR(100) NOT NULL, PRIMARY KEY(id));");
        $this->addSql("CREATE TABLE campo_clinico (id INT NOT NULL, ciclo_academico_id INT NOT NULL, carrera_id INT NOT NULL, fecha_inicial DATE NOT NULL, fecha_final DATE NOT NULL, horario VARCHAR(100) NOT NULL, promocion VARCHAR(100) NOT NULL, lugares_solicitados INT NOT NULL, lugares_autorizados INT NOT NULL, convenio_id INT NOT NULL, solicitud_id INT NOT NULL, referencia_bancaria VARCHAR(100) DEFAULT NULL, monto DOUBLE PRECISION DEFAULT NULL, id_estatus INT NOT NULL, unidad_id INT NOT NULL, PRIMARY KEY(id));");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE solicitud DROP CONSTRAINT UNIQ_96D27CC0415EAE2C');
        $this->addSql('DROP TABLE solicitud');
        $this->addSql("DROP TABLE expediente");
        $this->addSql("DROP TABLE estatus_campo");
        $this->addSql("DROP TABLE pago");
        $this->addSql("DROP TABLE campo_clinico");

    }
}
