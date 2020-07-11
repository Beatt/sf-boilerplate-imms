<?php

namespace AppBundle\Repository\IE\DetalleSolicitudMultiple\Expediente;

use AppBundle\ObjectValues\SolicitudId;
use AppBundle\Repository\IE\DetalleSolicitud\Expediente\Documents;
use AppBundle\Repository\IE\DetalleSolicitud\Expediente\OficioMontos;
use Doctrine\DBAL\Driver\Connection;

final class ExpedienteUsingSql implements Expediente
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function expedienteBySolicitud(SolicitudId $solicitudId)
    {
        $oficioMonto = $this->getOficioMonto($solicitudId);

        return new Documents(
            $oficioMonto,
            [],
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
}
