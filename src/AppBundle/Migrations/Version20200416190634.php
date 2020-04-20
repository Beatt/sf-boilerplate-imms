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

        /* CATALOGO DELEGACION */

        $this->addSql("ALTER TABLE convenio DROP CONSTRAINT FK_25577244F4B21EB5");
        $this->addSql("ALTER TABLE usuario_delegacion DROP CONSTRAINT FK_17D166E9F4B21EB5");
        $this->addSql("ALTER TABLE unidad DROP CONSTRAINT FK_F3E6D02FF4B21EB5");
        $this->addSQL("TRUNCATE TABLE delegacion RESTART IDENTITY");        

        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(1 , 1 , 'AGUASCALIENTES' ,true,'01',  21.8853, -102.2916, 'AS', 'AGUASCALIENTES',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(2 , 1 , 'BAJA CALIFORNIA' ,true,'02',  32.6245, -115.4523, 'BC', 'BAJA CALIFORNIA',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(3 , 1 , 'BAJA CALIFORNIA SUR' ,true,'03',  24.1426, -110.3128, 'BS', 'BAJA CALIFORNIA SUR',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(4 , 4 , 'CAMPECHE' ,true,'04',  19.8301, -90.5349, 'CC', 'CAMPECHE',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(5 , 2 , 'COAHUILA' ,true,'05',  25.4267, -100.9954, 'CL', 'COAHUILA',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(6 , 1 , 'COLIMA' ,true,'06',  19.2452, -103.7241, 'CM', 'COLIMA',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(7 , 3 , 'CHIAPAS' ,true,'07',  16.7516, -93.103, 'CS', 'CHIAPAS',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(8 , 2 , 'CHIHUAHUA' ,true,'08',  28.633, -106.0691, 'CH', 'CHIHUAHUA',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(9 , null , 'OFICINAS CENTRALES' ,true,'09',  null, null, 'OFC', 'OFICINAS CENTRALES',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(10 , 2 , 'DURANGO' ,true,'10',  24.0277, -104.6532, 'DG', 'DURANGO',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(11 , 1 , 'GUANAJUATO' ,true,'11',  21.0178, -101.2567, 'GT', 'GUANAJUATO',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(12 , 3 , 'GUERRERO' ,true,'12',  17.5515, -99.5006, 'GR', 'GUERRERO',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(13 , 4 , 'HIDALGO' ,true,'13',  20.1011, -98.7591, 'HG', 'HIDALGO',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(14 , 1 , 'JALISCO' ,true,'14',  20.6597, -103.3496, 'JC', 'JALISCO',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(15 , 4 , 'EDO MEX OTE' ,true,'15',  19.4667241, -99.22372, 'MCO', 'EDO MEX OTE',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(16 , 4 , 'EDO MEX PTE' ,true,'16',  19.289033, -99.647212, 'MCP', 'EDO MEX PTE',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(17 , 1 , 'MICHOACAN' ,true,'17',  19.706, -101.195, 'MN', 'MICHOACAN',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(18 , 3 , 'MORELOS' ,true,'18',  18.946, -99.2231, 'MS', 'MORELOS',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(19 , 1 , 'NAYARIT' ,true,'19',  21.5042, -104.8946, 'NT', 'NAYARIT',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(20 , 2 , 'NUEVO LEON' ,true,'20',  25.6866, -100.3161, 'NL', 'NUEVO LEON',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(21 , 3 , 'OAXACA' ,true,'21',  17.0594, -96.7216, 'OC', 'OAXACA',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(22 , 3 , 'PUEBLA' ,true,'22',  19.0413, -98.2062, 'PL', 'PUEBLA',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(23 , 3 , 'QUERETARO' ,true,'23',  20.5888, -100.3899, 'QT', 'QUERETARO',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(24 , 4 , 'QUINTANA ROO' ,true,'24',  18.5361, -88.2993, 'QR', 'QUINTANA ROO',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(25 , 2 , 'SAN LUIS POTOSI' ,true,'25',  22.1565, -100.9855, 'SP', 'SAN LUIS POTOSI',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(26 , 1 , 'SINALOA' ,true,'26',  24.8052, -107.3834, 'SL', 'SINALOA',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(27 , 1 , 'SONORA' ,true,'27',  29.073, -110.9559, 'SR', 'SONORA',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(28 , 3 , 'TABASCO' ,true,'28',  18.2612, -93.2217, 'TC', 'TABASCO',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(29 , 2 , 'TAMAULIPAS' ,true,'29',  21.2115, -100.2161, 'TS', 'TAMAULIPAS',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(30 , 3 , 'TLAXCALA' ,true,'30',  19.3182, -98.2375, 'TL', 'TLAXCALA',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(31 , 3 , 'VERACRUZ NORTE' ,true,'31',  19.52247, -96.921612, 'VZN', 'VERACRUZ NORTE',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(32 , 3 , 'VERACRUZ SUR' ,true,'32',  18.839656, -97.11509, 'VZS', 'VERACRUZ SUR',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(33 , 4 , 'YUCATAN' ,true,'33',  20.966, -89.6274, 'YN', 'YUCATAN',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(34 , 2 , 'ZACATECAS' ,true,'34',  22.7709, -102.5833, 'ZS', 'ZACATECAS',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(35 , 4 , 'D F 1 NORTE' ,true,'35',  19.481456, -99.13454, 'DFN', 'D F NORTE',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(36 , 4 , 'D F 2 NORTE' ,true,'36',  19.4307, -99.2084, 'DFN', 'D F NORTE',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(37 , 3 , 'D F 3 SUR' ,true,'37',  19.369089, -99.120756, 'DFS', 'D F SUR',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(38 , 3 , 'D F 4 SUR' ,true,'38',  19.380173, -99.122072, 'DFS', 'D F SUR',  '2017-05-16 10:43:19.296897'  )");
        $this->addSQL("INSERT INTO delegacion(id, region_id, nombre, activo,  clave_delegacional, latitud, longitud, grupo_delegacion,  nombre_grupo_delegacion, fecha) VALUES(39 ,  null, 'MANDO' ,true,'39',  null, null, 'MAN', 'MANDO',  '2017-05-16 10:43:19.296897'  )");


        $this->addSql("ALTER TABLE convenio ADD CONSTRAINT FK_25577244F4B21EB5 FOREIGN KEY (delegacion_id) REFERENCES delegacion (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE usuario_delegacion ADD CONSTRAINT FK_17D166E9F4B21EB5 FOREIGN KEY (delegacion_id) REFERENCES delegacion (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE unidad ADD CONSTRAINT FK_F3E6D02FF4B21EB5 FOREIGN KEY (delegacion_id) REFERENCES delegacion (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
