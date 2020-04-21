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

       
        /* CATALOGO CICLO_ACADEMICO */        
        $this->addSql("ALTER TABLE convenio DROP CONSTRAINT FK_25577244A7D9417F");
        $this->addSQL("TRUNCATE TABLE ciclo_academico RESTART IDENTITY");

        $this->addSQL("INSERT INTO ciclo_academico(id, nombre) VALUES (1, 'Ciclos Clínicos')");
        $this->addSQL("INSERT INTO ciclo_academico(id, nombre) VALUES (2, 'Internado')");

        $this->addSql("ALTER TABLE convenio ADD CONSTRAINT FK_25577244A7D9417F FOREIGN KEY (ciclo_academico_id) REFERENCES ciclo_academico (id) NOT DEFERRABLE INITIALLY IMMEDIATE");


        /* CATALOGO CARRERA */
        $this->addSql("ALTER TABLE convenio DROP CONSTRAINT FK_25577244C671B40F");
        $this->addSQL("TRUNCATE TABLE carrera RESTART IDENTITY");

        $this->addSQL("INSERT INTO carrera(id, nombre, activo, nivel_academico_id) VALUES(1,'ESTOMATOLOGÍA',true,1)");
        $this->addSQL("INSERT INTO carrera(id, nombre, activo, nivel_academico_id) VALUES(2,'FARMACIA',true, 1)");
        $this->addSQL("INSERT INTO carrera(id, nombre, activo, nivel_academico_id) VALUES(3,'FISIOTERAPIA',true, 1)");
        $this->addSQL("INSERT INTO carrera(id, nombre, activo, nivel_academico_id) VALUES(4,'GERONTOLOGÍA',true,1)");
        $this->addSQL("INSERT INTO carrera(id, nombre, activo, nivel_academico_id) VALUES(5,'MEDICINA',true,1)");
        $this->addSQL("INSERT INTO carrera(id, nombre, activo, nivel_academico_id) VALUES(6,'NUTRICIÓN',true,1)");
        $this->addSQL("INSERT INTO carrera(id, nombre, activo, nivel_academico_id) VALUES(7,'ODONTOLOGÍA',true,1)");
        $this->addSQL("INSERT INTO carrera(id, nombre, activo, nivel_academico_id) VALUES(8,'OPTOMETRÍA',true,1)");
        $this->addSQL("INSERT INTO carrera(id, nombre, activo, nivel_academico_id) VALUES(9,'PSICOLOGÍA',true,1)");
        $this->addSQL("INSERT INTO carrera(id, nombre, activo, nivel_academico_id) VALUES(10,'PSICOLOGÍA CLÍNICA',true,1)");
        $this->addSQL("INSERT INTO carrera(id, nombre, activo, nivel_academico_id) VALUES(11,'QUÍMICO BIÓLOGO PARASITÓLOGO',true,1)");
        $this->addSQL("INSERT INTO carrera(id, nombre, activo, nivel_academico_id) VALUES(12,'QUÍMICO CLÍNICO',true,1)");
        $this->addSQL("INSERT INTO carrera(id, nombre, activo, nivel_academico_id) VALUES(13,'QUÍMICO CLÍNICO BIÓLOGO',true,1)");
        $this->addSQL("INSERT INTO carrera(id, nombre, activo, nivel_academico_id) VALUES(14,'QUÍMICO FARMACO BIÓLOGO',true,1)");
        $this->addSQL("INSERT INTO carrera(id, nombre, activo, nivel_academico_id) VALUES(15,'RADIOLOGÍA E IMAGEN',true,1)");
        $this->addSQL("INSERT INTO carrera(id, nombre, activo, nivel_academico_id) VALUES(16,'TERAPIA FISICA',true,1)");
        $this->addSQL("INSERT INTO carrera(id, nombre, activo, nivel_academico_id) VALUES(17,'TRABAJO SOCIAL',true,1)");
        $this->addSQL("INSERT INTO carrera(id, nombre, activo, nivel_academico_id) VALUES(18,'ENFERMERÍA',true,1)");
        $this->addSQL("INSERT INTO carrera(id, nombre, activo, nivel_academico_id) VALUES(19,'DENTAL',true,1)");
        $this->addSQL("INSERT INTO carrera(id, nombre, activo, nivel_academico_id) VALUES(20,'RADIOLOGÍA E IMAGEN',true,2)");
        $this->addSQL("INSERT INTO carrera(id, nombre, activo, nivel_academico_id) VALUES(21,'LABORATORISTA CLÍNICO',true,2)");
        $this->addSQL("INSERT INTO carrera(id, nombre, activo, nivel_academico_id) VALUES(22,'LABORATORISTA QUÍMICO',true,2)");
        $this->addSQL("INSERT INTO carrera(id, nombre, activo, nivel_academico_id) VALUES(23,'TERAPIA RESPIRATORIA',true,2)");
        $this->addSQL("INSERT INTO carrera(id, nombre, activo, nivel_academico_id) VALUES(24,'TRABAJO SOCIAL',true,2)");
        $this->addSQL("INSERT INTO carrera(id, nombre, activo, nivel_academico_id) VALUES(25,'ENFERMERÍA',true,2)");

        $this->addSql("ALTER TABLE convenio ADD CONSTRAINT FK_25577244C671B40F FOREIGN KEY (carrera_id) REFERENCES carrera (id) NOT DEFERRABLE INITIALLY IMMEDIATE");

        
        
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
