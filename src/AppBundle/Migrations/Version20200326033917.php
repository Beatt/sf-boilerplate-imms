<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200326033917 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE categoria (id SERIAL NOT NULL, nombre VARCHAR(30) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE usuario_departamento (usuario_id INT NOT NULL, departamento_id INT NOT NULL, PRIMARY KEY(usuario_id, departamento_id))');
        $this->addSql('CREATE INDEX IDX_4ADC123EDB38439E ON usuario_departamento (usuario_id)');
        $this->addSql('CREATE INDEX IDX_4ADC123E5A91C08D ON usuario_departamento (departamento_id)');
        $this->addSql('ALTER TABLE usuario_departamento ADD CONSTRAINT FK_4ADC123EDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE usuario_departamento ADD CONSTRAINT FK_4ADC123E5A91C08D FOREIGN KEY (departamento_id) REFERENCES departamento (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP INDEX uniq_2265b05d2265b05d');
        $this->addSql('ALTER TABLE usuario ADD categoria_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario ADD matricula INT NOT NULL');
        $this->addSql('ALTER TABLE usuario ADD apellido_paterno VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE usuario ADD apellido_materno VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE usuario ADD regims BIGINT NOT NULL');
        $this->addSql('ALTER TABLE usuario ADD curp VARCHAR(18) NOT NULL');
        $this->addSql('ALTER TABLE usuario ADD rfc VARCHAR(13) NOT NULL');
        $this->addSql('ALTER TABLE usuario ADD sexo VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE usuario ADD fecha_ingreso DATE NOT NULL');
        $this->addSql('ALTER TABLE usuario RENAME COLUMN usuario TO nombre');
        $this->addSql('ALTER TABLE usuario RENAME COLUMN es_activo TO activo');
        $this->addSql('ALTER TABLE usuario ADD CONSTRAINT FK_2265B05D3397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2265B05D15DF1885 ON usuario (matricula)');
        $this->addSql('CREATE INDEX IDX_2265B05D3397707A ON usuario (categoria_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE usuario DROP CONSTRAINT FK_2265B05D3397707A');
        $this->addSql('DROP TABLE categoria');
        $this->addSql('DROP TABLE usuario_departamento');
        $this->addSql('DROP INDEX UNIQ_2265B05D15DF1885');
        $this->addSql('DROP INDEX IDX_2265B05D3397707A');
        $this->addSql('ALTER TABLE usuario DROP categoria_id');
        $this->addSql('ALTER TABLE usuario DROP matricula');
        $this->addSql('ALTER TABLE usuario DROP apellido_paterno');
        $this->addSql('ALTER TABLE usuario DROP apellido_materno');
        $this->addSql('ALTER TABLE usuario DROP regims');
        $this->addSql('ALTER TABLE usuario DROP curp');
        $this->addSql('ALTER TABLE usuario DROP rfc');
        $this->addSql('ALTER TABLE usuario DROP sexo');
        $this->addSql('ALTER TABLE usuario DROP fecha_ingreso');
        $this->addSql('ALTER TABLE usuario RENAME COLUMN nombre TO usuario');
        $this->addSql('ALTER TABLE usuario RENAME COLUMN activo TO es_activo');
        $this->addSql('CREATE UNIQUE INDEX uniq_2265b05d2265b05d ON usuario (usuario)');
    }
}
