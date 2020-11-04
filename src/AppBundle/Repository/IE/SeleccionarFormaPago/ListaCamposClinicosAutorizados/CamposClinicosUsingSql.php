<?php

namespace AppBundle\Repository\IE\SeleccionarFormaPago\ListaCamposClinicosAutorizados;

use AppBundle\ObjectValues\SolicitudId;
use Carbon\Carbon;
use Doctrine\DBAL\Driver\Connection;

final class CamposClinicosUsingSql implements CamposClinicos
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function listaCamposClinicosAutorizados(SolicitudId $solicitudId)
    {
        $statement = $this->connection->prepare('
            SELECT campo_clinico.id AS id_campo_clinico,
                   monto,
                   lugares_solicitados,
                   lugares_autorizados,
                   fecha_inicial,
                   fecha_final,
                   unidad.nombre          AS nombre_unidad,
                   carrera.id         AS carrera_id,
                   carrera.nombre         AS nombre_carrera,
                   nivel_academico.nombre AS nombre_nivel_academico,
                   ciclo_academico.id AS ciclo_academico_id,
                   ciclo_academico.nombre AS nombre_ciclo_academico
            FROM campo_clinico
                   JOIN unidad
                        ON campo_clinico.unidad_id = unidad.id
                   JOIN convenio
                        ON campo_clinico.convenio_id = convenio.id
                   JOIN carrera
                        ON convenio.carrera_id = carrera.id
                   JOIN nivel_academico
                        ON carrera.nivel_academico_id = nivel_academico.id
                   JOIN ciclo_academico
                        ON convenio.ciclo_academico_id = ciclo_academico.id
            WHERE campo_clinico.solicitud_id = :solicitudId
              AND lugares_autorizados != 0;
        ');

        $statement->execute(['solicitudId' => $solicitudId->asInt()]);
        $records = $statement->fetchAll();

        return array_map(function (array $record) {
            $nivelAcademico = new NivelAcademico($record['nombre_nivel_academico']);
            $carrera = new Carrera($record['carrera_id'], $record['nombre_carrera'], $nivelAcademico);
            $cicloAcademico = new CicloAcademico($record['ciclo_academico_id'], $record['nombre_ciclo_academico']);
            $convenio = new Convenio(
                $carrera,
                $cicloAcademico
            );

            $unidad = new Unidad($record['nombre_unidad']);

            return new CampoClinico(
                $record['id_campo_clinico'],
                $unidad,
                $convenio,
                $record['lugares_solicitados'],
                $record['lugares_autorizados'],
                $record['fecha_inicial'],
                $record['fecha_final'],
                $this->getNumeroSemanas(
                    $record['fecha_inicial'],
                    $record['fecha_final']
                ),
                $this->getMontoPagar(
                    $record['lugares_autorizados'],
                    $record['monto']
                ),
                $this->getEnlaceCalculoCuotas(
                    $record['id_campo_clinico'],
                    $record['lugares_autorizados']
                )
            );
        }, $records);
    }

    private function getNumeroSemanas($fechaInicial, $fechaFinal)
    {
      $inicial = Carbon::instance(new \DateTime($fechaInicial));
      $final = Carbon::instance(new \DateTime($fechaFinal));

      $dias = 1 + $final->diffInDays($inicial);
      $weeks = intval($dias/7) + ($dias % 7 > 0 ? 1 : 0);

      return $weeks;
    }

    private function getMontoPagar($lugaresAutorizados, $monto)
    {
        return $lugaresAutorizados !== 0 ? $monto : 'No aplica';
    }

    private function getEnlaceCalculoCuotas($id, $lugaresAutorizados)
    {
        return $lugaresAutorizados !== 0 ? $id : '';
    }
}
