<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200416190634 extends AbstractMigration
{


    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs

        /* CATALOGO NIVEL ACADEMICO */
        $this->addSQL("ALTER TABLE carrera DROP CONSTRAINT FK_CF1ECD30C21F5FA8");
        $this->addSQL("ALTER TABLE convenio DROP CONSTRAINT FK_25577244DA3426AE");
        $this->addSQL("TRUNCATE TABLE nivel_academico RESTART IDENTITY");

        $this->addSQL("INSERT INTO nivel_academico(id, nombre) VALUES (1, 'Licenciatura')");
        $this->addSQL("INSERT INTO nivel_academico(id, nombre) VALUES (2, 'Técnico')");

        $this->addSql("ALTER TABLE carrera ADD CONSTRAINT FK_CF1ECD30C21F5FA8 FOREIGN KEY (nivel_academico_id) REFERENCES nivel_academico (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE convenio ADD CONSTRAINT FK_25577244DA3426AE FOREIGN KEY (nivel_id) REFERENCES nivel_academico (id) NOT DEFERRABLE INITIALLY IMMEDIATE");



        /* CATALOGO REGION */
        $this->addSql("ALTER TABLE delegacion DROP CONSTRAINT FK_E4E12C4B98260155");
        $this->addSQL("TRUNCATE TABLE region RESTART IDENTITY");

        $this->addSQL("INSERT INTO region(id, nombre, activo) VALUES (1, 'Nor-Occidente', true)");
        $this->addSQL("INSERT INTO region(id, nombre, activo) VALUES (2, 'Nor-Este', true)");
        $this->addSQL("INSERT INTO region(id, nombre, activo) VALUES (3, 'Centro-Sur', true)");
        $this->addSQL("INSERT INTO region(id, nombre, activo) VALUES (4, 'Centro-Norte', true)");

        $this->addSql("ALTER TABLE delegacion ADD CONSTRAINT FK_E4E12C4B98260155 FOREIGN KEY (region_id) REFERENCES region (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
