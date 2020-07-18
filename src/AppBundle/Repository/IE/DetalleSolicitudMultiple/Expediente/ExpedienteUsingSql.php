<?php

namespace AppBundle\Repository\IE\DetalleSolicitudMultiple\Expediente;

use AppBundle\ObjectValues\SolicitudId;
use AppBundle\Repository\IE\DetalleSolicitud\Expediente\AbstractExpediente;
use AppBundle\Repository\IE\DetalleSolicitud\Expediente\ComprobantePago;
use AppBundle\Repository\IE\DetalleSolicitud\Expediente\Documents;
use AppBundle\Repository\IE\DetalleSolicitud\Expediente\OficioMontos;
use Doctrine\DBAL\Driver\Connection;

final class ExpedienteUsingSql extends AbstractExpediente implements Expediente
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function expedienteBySolicitud(SolicitudId $solicitudId)
    {
        $oficioMonto = $this->getOficioMonto($solicitudId);
        $comprobantesPago = $this->getComprobantesPago($solicitudId);

        return new Documents(
            $oficioMonto,
            $comprobantesPago,
            []
        );
    }

    /**
     * @param SolicitudId $solicitudId
     * @return OficioMontos
     */
    protected function getOficioMonto(SolicitudId $solicitudId)
    {
        $statement = $this->connection->prepare('
            SELECT documento,
                   fecha_comprobante,
                   url_archivo
            FROM solicitud
            WHERE solicitud.id = :id
        ');

        $statement->execute([
            'id' => $solicitudId->asInt()
        ]);

        $oficioRecord = $statement->fetch();

        $statement = $this->connection->prepare('
            SELECT nivel_academico.nombre AS nombre_nivel_academico,
                   carrera.nombre AS nombre_carrera,
                   monto_inscripcion,
                   monto_colegiatura
            FROM solicitud
            JOIN monto_carrera
              ON solicitud.id = monto_carrera.solicitud_id
            JOIN carrera
              ON monto_carrera.carrera_id = carrera.id
            JOIN nivel_academico
              ON carrera.nivel_academico_id = nivel_academico.id
            WHERE solicitud.id = :id
        ');

        $statement->execute([
            'id' => $solicitudId->asInt()
        ]);

        $montosCarreraRecord = $statement->fetchAll();

        return new OficioMontos(
            $oficioRecord['fecha_comprobante'],
            $this->getDescripcionOficioMontos($montosCarreraRecord),
            $oficioRecord['url_archivo']
        );
    }

    private function getDescripcionOficioMontos(array $montosCarreraRecord)
    {
        $items = array_map(function (array $record) {
            return sprintf(
                "%s %s: Inscripción $%s, Colegiatura: $%s",
                $record['nombre_nivel_academico'],
                $record['nombre_carrera'],
                $record['monto_inscripcion'],
                $record['monto_colegiatura']
            );
        }, $montosCarreraRecord);

        return implode('. ', $items);
    }

    private function getComprobantesPago(SolicitudId $solicitudId)
    {
        $statement = $this->connection->prepare('
            SELECT campo_clinico.id,
                   unidad.nombre AS nombre_unidad,
                   pago.comprobante_pago,
                   pago.fecha_creacion,
                   pago.referencia_bancaria,
                   pago.monto
            FROM solicitud
                     JOIN campo_clinico
                          ON solicitud.id = campo_clinico.solicitud_id
                     JOIN pago
                          ON solicitud.id = pago.solicitud_id
                     JOIN unidad
                          ON campo_clinico.unidad_id = unidad.id
            WHERE solicitud.id = :id
              AND campo_clinico.referencia_bancaria = pago.referencia_bancaria
              AND campo_clinico.lugares_autorizados > 0
            ORDER BY id
        ');

        $statement->execute([
            'id' => $solicitudId->asInt()
        ]);

        $records = $statement->fetchAll();

        return array_map(function (array $record) {
            return new ComprobantePago(
                $record['fecha_creacion'],
                $this->getDescripcion($record),
                $record['comprobante_pago'],
                $record['nombre_unidad']
            );
        }, $records);
    }
}
