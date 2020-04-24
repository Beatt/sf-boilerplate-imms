<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200422010011 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs

        /* CATALOGO CARRERA */
        $this->addSql("ALTER TABLE convenio DROP CONSTRAINT FK_25577244C671B40F");
        $this->addSQL("TRUNCATE TABLE carrera RESTART IDENTITY");
        $SQLFile = __DIR__ . '/../../../db/seeds/carrera.sql';
        foreach (explode(';', file_get_contents($SQLFile)) as $sql) {
          if (strlen(trim($sql)) > 0) {
              $this->addSql($sql);
          }
        }
        $this->addSql("ALTER TABLE convenio ADD CONSTRAINT FK_25577244C671B40F FOREIGN KEY (carrera_id) REFERENCES carrera (id) NOT DEFERRABLE INITIALLY IMMEDIATE");

        /* CATALOGO DEPARTAMENTO */
        $this->addSql("ALTER TABLE usuario DROP CONSTRAINT FK_2265B05D5A91C08D");
        $this->addSQL("TRUNCATE TABLE departamento RESTART IDENTITY");
        $SQLFile = __DIR__ . '/../../../db/seeds/departamento.sql';
        foreach (explode(';', file_get_contents($SQLFile)) as $sql) {
          if (strlen(trim($sql)) > 0) {
              $this->addSql($sql);
          }
        }
        $this->addSql("ALTER TABLE usuario ADD CONSTRAINT FK_2265B05D5A91C08D FOREIGN KEY (departamento_id) REFERENCES departamento (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        //$this->addSql('CREATE SCHEMA public');
    }
}
