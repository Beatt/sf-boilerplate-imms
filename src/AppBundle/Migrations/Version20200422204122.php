<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200422204122 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        /* CATALOGO CICLO_ACADEMICO */        
        $this->addSql("ALTER TABLE convenio DROP CONSTRAINT FK_25577244A7D9417F");
        $this->addSQL("TRUNCATE TABLE ciclo_academico RESTART IDENTITY");
        $this->addSql('ALTER TABLE ciclo_academico ADD activo BOOLEAN NOT NULL');
        $SQLFile = __DIR__ . '/../../../db/seeds/ciclo_academico.sql';
        foreach (explode(';', file_get_contents($SQLFile)) as $sql) {
          if (strlen(trim($sql)) > 0) {
              $this->addSql($sql);
          }
        }
        $this->addSql("ALTER TABLE convenio ADD CONSTRAINT FK_25577244A7D9417F FOREIGN KEY (ciclo_academico_id) REFERENCES ciclo_academico (id) NOT DEFERRABLE INITIALLY IMMEDIATE");

        /* INSTITUCIONES */
        $this->addSql("ALTER TABLE convenio DROP CONSTRAINT FK_25577244B239FBC6");
        $this->addSQL("TRUNCATE TABLE institucion RESTART IDENTITY");
        $SQLFile = __DIR__ . '/../../../db/seeds/institucion.sql';
        foreach (explode(';', file_get_contents($SQLFile)) as $sql) {
            if (strlen(trim($sql)) > 0) {
                $this->addSql($sql);
            }            
        }
        $this->addSql("ALTER TABLE convenio ADD CONSTRAINT FK_25577244B239FBC6 FOREIGN KEY (institucion_id) REFERENCES institucion (id) NOT DEFERRABLE INITIALLY IMMEDIATE");

        /* Convenios Publicados al 21-04-2020 */        
        $this->addSQL("TRUNCATE TABLE convenio RESTART IDENTITY");
        $this->addSql('ALTER TABLE convenio ADD nombre VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE convenio ALTER institucion_id SET NOT NULL');
        $SQLFile = __DIR__ . '/../../../db/seeds/convenios.sql';
        foreach (explode(';', file_get_contents($SQLFile)) as $sql) {
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
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        //$this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE ciclo_academico DROP activo');
        $this->addSql('ALTER TABLE convenio DROP nombre');
        $this->addSql('ALTER TABLE convenio ALTER institucion_id DROP NOT NULL');
    }
}
