<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200608184928 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->executeSql(__DIR__ . '/../../../db/seeds/rol.sql');
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
