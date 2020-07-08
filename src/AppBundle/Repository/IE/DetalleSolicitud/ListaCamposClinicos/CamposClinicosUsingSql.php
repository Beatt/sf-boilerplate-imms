<?php

namespace AppBundle\Repository\IE\DetalleSolicitud\ListaCamposClinicos;

use AppBundle\ObjectValues\SolicitudId;
use Doctrine\DBAL\Driver\Connection;

final class CamposClinicosUsingSql implements CamposClinicos
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function listaCamposClinicosBySolicitud(SolicitudId $solicitudId)
    {
        $statement = $this->connection->prepare('
            SELECT campo_clinico.id AS id_campo_clinico,
                   lugares_solicitados,
                   lugares_autorizados,
                   fecha_inicial,
                   fecha_final,
                   carrera.nombre AS nombre_carrera,
                   nivel_academico.nombre AS nombre_nivel_academico,
                   ciclo_academico.nombre AS nombre_ciclo_academico,
                   unidad.nombre AS nombre_unidad 
            FROM campo_clinico
              JOIN solicitud
                ON campo_clinico.solicitud_id = solicitud.id
              JOIN convenio
                ON campo_clinico.convenio_id = convenio.id
              JOIN carrera
                ON convenio.carrera_id = carrera.id
              JOIN nivel_academico
                ON carrera.nivel_academico_id = nivel_academico.id
              JOIN ciclo_academico
                ON convenio.ciclo_academico_id = ciclo_academico.id
              JOIN unidad
                ON campo_clinico.unidad_id = unidad.id
            WHERE solicitud.id = :id
        ');

        $statement->execute([
            'id' => $solicitudId->asInt()
        ]);

        $records = $statement->fetchAll();

        return array_map(function (array $record) {

            $nivelAcademico = new NivelAcademico($record['nombre_nivel_academico']);
            $carrera = new Carrera($record['nombre_carrera'], $nivelAcademico);
            $cicloAcademico = new CicloAcademico($record['nombre_ciclo_academico']);
            $convenio = new Convenio(
                $carrera,
                $cicloAcademico
            );

            $unidad = new Unidad($record['nombre_unidad']);

            return new CampoClinico(
                $record['id_campo_clinico'],
                $convenio,
                $record['lugares_solicitados'],
                $record['lugares_autorizados'],
                $record['fecha_inicial'],
                $record['fecha_final'],
                $unidad
            );
        }, $records);
    }
}
