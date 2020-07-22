<?php

namespace AppBundle\Repository\IE\DetalleSolicitudMultiple\ListaCamposClinicos;

use AppBundle\ObjectValues\SolicitudId;
use AppBundle\Repository\IE\DetalleSolicitud\ListaCamposClinicos\Carrera;
use AppBundle\Repository\IE\DetalleSolicitud\ListaCamposClinicos\CicloAcademico;
use AppBundle\Repository\IE\DetalleSolicitud\ListaCamposClinicos\Convenio;
use AppBundle\Repository\IE\DetalleSolicitud\ListaCamposClinicos\NivelAcademico;
use AppBundle\Repository\IE\DetalleSolicitud\ListaCamposClinicos\Unidad;
use Carbon\Carbon;
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
            SELECT campo_clinico.id       AS id_campo_clinico,
                   lugares_solicitados,
                   lugares_autorizados,
                   fecha_inicial,
                   fecha_final,
                   carrera.nombre         AS nombre_carrera,
                   nivel_academico.nombre AS nombre_nivel_academico,
                   ciclo_academico.nombre AS nombre_ciclo_academico,
                   unidad.nombre          AS nombre_unidad,
                   estatus_campo.nombre   AS estatus,
                   (
                       SELECT pago.id
                       FROM pago
                       WHERE pago.solicitud_id = solicitud.id
                         AND pago.referencia_bancaria = campo_clinico.referencia_bancaria
                       ORDER BY fecha_creacion DESC
                       LIMIT 1
                   )                      AS id_pago,
                   (
                       SELECT factura.zip
                       FROM pago
                                JOIN factura
                                     ON pago.factura_id = factura.id
                       WHERE pago.solicitud_id = solicitud.id
                         AND pago.referencia_bancaria = campo_clinico.referencia_bancaria
                       LIMIT 1
                   )                      AS factura,
                   (
                       SELECT pago.requiere_factura
                       FROM pago
                       WHERE pago.solicitud_id = solicitud.id
                         AND pago.referencia_bancaria = campo_clinico.referencia_bancaria
                       LIMIT 1
                   )                      AS requiere_factura
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
                     JOIN estatus_campo
                          ON campo_clinico.estatus_campo_id = estatus_campo.id
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
            $pago = new Pago(
                $record['id_pago'],
                $record['factura'],
                $record['requiere_factura']
            );

            return new CampoClinico(
                $record['id_campo_clinico'],
                $convenio,
                $record['lugares_solicitados'],
                $record['lugares_autorizados'],
                $record['fecha_inicial'],
                $record['fecha_final'],
                $unidad,
                $this->getNoSemanas(
                    $record['fecha_inicial'],
                    $record['fecha_final']
                ),
                $pago,
                $record['estatus']
            );
        }, $records);
    }

    private function getNoSemanas($fechaInicial, $fechaFinal)
    {
        $inicial = Carbon::instance(new \DateTime($fechaInicial));
        $final = Carbon::instance(new \DateTime($fechaFinal));
        return $final->diffInWeeks($inicial) > 0 ? $final->diffInWeeks($inicial) : 1;
    }
}
