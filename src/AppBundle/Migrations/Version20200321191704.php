<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200321191704 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE permiso (id SERIAL NOT NULL, nombre VARCHAR(255) NOT NULL, descripcion VARCHAR(255) NOT NULL, rol_seguridad VARCHAR(80) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE permiso_rol (permiso_id INT NOT NULL, rol_id INT NOT NULL, PRIMARY KEY(permiso_id, rol_id))');
        $this->addSql('CREATE INDEX IDX_DD501D066CEFAD37 ON permiso_rol (permiso_id)');
        $this->addSql('CREATE INDEX IDX_DD501D064BAB96C ON permiso_rol (rol_id)');
        $this->addSql('CREATE TABLE usuario (id SERIAL NOT NULL, usuario VARCHAR(25) NOT NULL, contrasena VARCHAR(64) NOT NULL, correo VARCHAR(254) NOT NULL, es_activa BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2265B05D2265B05D ON usuario (usuario)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2265B05D77040BC9 ON usuario (correo)');
        $this->addSql('CREATE TABLE usuario_rol (usuario_id INT NOT NULL, rol_id INT NOT NULL, PRIMARY KEY(usuario_id, rol_id))');
        $this->addSql('CREATE INDEX IDX_72EDD1A4DB38439E ON usuario_rol (usuario_id)');
        $this->addSql('CREATE INDEX IDX_72EDD1A44BAB96C ON usuario_rol (rol_id)');
        $this->addSql('CREATE TABLE rol (id SERIAL NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE permiso_rol ADD CONSTRAINT FK_DD501D066CEFAD37 FOREIGN KEY (permiso_id) REFERENCES permiso (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE permiso_rol ADD CONSTRAINT FK_DD501D064BAB96C FOREIGN KEY (rol_id) REFERENCES rol (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE usuario_rol ADD CONSTRAINT FK_72EDD1A4DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE usuario_rol ADD CONSTRAINT FK_72EDD1A44BAB96C FOREIGN KEY (rol_id) REFERENCES rol (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE permiso_rol DROP CONSTRAINT FK_DD501D066CEFAD37');
        $this->addSql('ALTER TABLE usuario_rol DROP CONSTRAINT FK_72EDD1A4DB38439E');
        $this->addSql('ALTER TABLE permiso_rol DROP CONSTRAINT FK_DD501D064BAB96C');
        $this->addSql('ALTER TABLE usuario_rol DROP CONSTRAINT FK_72EDD1A44BAB96C');
        $this->addSql('DROP TABLE permiso');
        $this->addSql('DROP TABLE permiso_rol');
        $this->addSql('DROP TABLE usuario');
        $this->addSql('DROP TABLE usuario_rol');
        $this->addSql('DROP TABLE rol');
    }
}
