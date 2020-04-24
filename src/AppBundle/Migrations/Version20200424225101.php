<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200424225101 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql('alter table campo_clinico add column solicitud_id integer;');
        $this->addSql('alter table campo_clinico ADD FOREIGN KEY (solicitud_id) REFERENCES solicitud(id);');
        $this->addSql('alter table campo_clinico add column unidad_id integer;');
        $this->addSql('alter table campo_clinico ADD FOREIGN KEY (unidad_id) REFERENCES unidad(id);');
        $this->addSql('alter table campo_clinico add column id_estatus integer;');
        $this->addSql('alter table campo_clinico ADD FOREIGN KEY (id_estatus) REFERENCES estatus_campo(id);');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

    }
}
