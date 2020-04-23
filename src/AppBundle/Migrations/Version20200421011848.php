<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200421011848 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs

        /* CATALOGO DELEGACIONES */
        $this->addSql("ALTER TABLE convenio DROP CONSTRAINT FK_25577244F4B21EB5");
        $this->addSql("ALTER TABLE usuario_delegacion DROP CONSTRAINT FK_17D166E9F4B21EB5");
        $this->addSql("ALTER TABLE unidad DROP CONSTRAINT FK_F3E6D02FF4B21EB5");
        $this->addSQL("TRUNCATE TABLE delegacion RESTART IDENTITY");        
        $SQLFile = __DIR__ . '/../../../db/seeds/delegacion.sql';
        foreach (explode(';', file_get_contents($SQLFile)) as $sql) {
            if (strlen(trim($sql)) > 0) {
                $this->addSql($sql);
            }            
        }
        $this->addSql("ALTER TABLE convenio ADD CONSTRAINT FK_25577244F4B21EB5 FOREIGN KEY (delegacion_id) REFERENCES delegacion (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE usuario_delegacion ADD CONSTRAINT FK_17D166E9F4B21EB5 FOREIGN KEY (delegacion_id) REFERENCES delegacion (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE unidad ADD CONSTRAINT FK_F3E6D02FF4B21EB5 FOREIGN KEY (delegacion_id) REFERENCES delegacion (id) NOT DEFERRABLE INITIALLY IMMEDIATE");

        /* CATALOGO CATEGORIA */
        $this->addSQL("ALTER TABLE usuario DROP CONSTRAINT FK_2265B05D3397707A");
        $this->addSQL("TRUNCATE TABLE categoria RESTART IDENTITY");
        $SQLFile = __DIR__ . '/../../../db/seeds/categoria.sql';
        foreach (explode(';', file_get_contents($SQLFile)) as $sql) {
            if (strlen(trim($sql)) > 0) {
                $this->addSql($sql);
            }            
        }
        $this->addSql("ALTER TABLE usuario ADD CONSTRAINT FK_2265B05D3397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id) NOT DEFERRABLE INITIALLY IMMEDIATE");

        /* CATALOGO TIPO_UNIDAD */
        $this->addSql("ALTER TABLE unidad DROP CONSTRAINT FK_F3E6D02F7F6FF902");
        $this->addSQL("TRUNCATE TABLE tipo_unidad RESTART IDENTITY");
        $SQLFile = __DIR__ . '/../../../db/seeds/tipo_unidad.sql';
        foreach (explode(';', file_get_contents($SQLFile)) as $sql) {
            if (strlen(trim($sql)) > 0) {
                $this->addSql($sql);
            }            
        }
        $this->addSql("ALTER TABLE unidad ADD CONSTRAINT FK_F3E6D02F7F6FF902 FOREIGN KEY (tipo_unidad_id) REFERENCES tipo_unidad (id) NOT DEFERRABLE INITIALLY IMMEDIATE");

        /* CATALOGO UNIDADES */
        $this->addSql("ALTER TABLE departamento DROP CONSTRAINT FK_40E497EB9D01464C");
        $this->addSQL("TRUNCATE TABLE unidad RESTART IDENTITY");
        $SQLFile = __DIR__ . '/../../../db/seeds/unidad.sql';
        foreach (explode(';', file_get_contents($SQLFile)) as $sql) {
            if (strlen(trim($sql)) > 0) {
                $this->addSql($sql);
            }            
        }
        $this->addSql("ALTER TABLE departamento ADD CONSTRAINT FK_40E497EB9D01464C FOREIGN KEY (unidad_id) REFERENCES unidad (id) NOT DEFERRABLE INITIALLY IMMEDIATE");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
