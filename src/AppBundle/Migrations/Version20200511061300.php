<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200511061300 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->ExecuteSql(__DIR__ . '/../../../db/seeds/categoria.sql');
        $this->ExecuteSql(__DIR__ . '/../../../db/seeds/ciclo_academico.sql');
        $this->ExecuteSql(__DIR__ . '/../../../db/seeds/region.sql');
        $this->ExecuteSql(__DIR__ . '/../../../db/seeds/tipo_unidad.sql');
        $this->ExecuteSql(__DIR__ . '/../../../db/seeds/estatus_campo.sql');
        $this->ExecuteSql(__DIR__ . '/../../../db/seeds/nivel_academico.sql');
        $this->ExecuteSql(__DIR__ . '/../../../db/seeds/institucion.sql');

        $this->ExecuteSql(__DIR__ . '/../../../db/seeds/carrera.sql');
        $this->ExecuteSql(__DIR__ . '/../../../db/seeds/delegacion.sql');
        $this->ExecuteSql(__DIR__ . '/../../../db/seeds/unidad.sql');
        $this->ExecuteSql(__DIR__ . '/../../../db/seeds/convenios.sql');
        $this->ExecuteSql(__DIR__ . '/../../../db/seeds/departamento.sql');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
    }

    private function ExecuteSql($sqlFile)
    {
        foreach (explode(';', file_get_contents($sqlFile)) as $sql) {
            if (strlen(trim($sql)) > 0) {
                $this->addSql($sql);
            }
        }
    }
}
