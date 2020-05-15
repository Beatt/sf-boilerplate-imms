<?php

namespace Tests\AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200511202656 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->executeSqlSinDependencias();
        $this->executeSqlConDependencias();
    }

    private function executeSqlSinDependencias()
    {
        $this->executeSql(__DIR__ . '/../../../db/seeds/categoria.sql');
        $this->executeSql(__DIR__ . '/../../../db/seeds/ciclo_academico.sql');
        $this->executeSql(__DIR__ . '/../../../db/seeds/region.sql');
        $this->executeSql(__DIR__ . '/../../../db/seeds/tipo_unidad.sql');
        $this->executeSql(__DIR__ . '/../../../db/seeds/estatus_campo.sql');
        $this->executeSql(__DIR__ . '/../../../db/seeds/nivel_academico.sql');
        $this->executeSql(__DIR__ . '/../../../db/seeds/institucion.sql');
    }

    private function executeSqlConDependencias()
    {
        $this->executeSql(__DIR__ . '/../../../db/seeds/carrera.sql');
        $this->executeSql(__DIR__ . '/../../../db/seeds/delegacion.sql');
        $this->executeSql(__DIR__ . '/../../../db/seeds/unidad.sql');
        $this->executeSql(__DIR__ . '/../../../db/seeds/convenios.sql');
        $this->executeSql(__DIR__ . '/../../../db/seeds/departamento.sql');
    }

    /**
     * @param string $sqlFile
     */
    private function executeSql($sqlFile)
    {
        foreach (explode(';', file_get_contents($sqlFile)) as $sql) {
            if (strlen(trim($sql)) > 0) {
                $this->addSql($sql);
            }
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
