<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200427214102 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('alter table campo_clinico add column id_estatus integer');
        $this->addSql('alter table campo_clinico ADD FOREIGN KEY (id_estatus) REFERENCES estatus_campo(id)');
        $this->addSql('alter table campo_clinico alter column promocion drop not null');
        $this->addSql('alter table campo_clinico alter column referencia_bancaria drop not null');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
