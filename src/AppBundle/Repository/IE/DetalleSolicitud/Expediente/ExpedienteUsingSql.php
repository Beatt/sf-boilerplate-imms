<?php

namespace AppBundle\Repository\IE\DetalleSolicitud\Expediente;

use AppBundle\ObjectValues\SolicitudId;
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
            $oficioMonto
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

        $record = $statement->fetch();

        return new OficioMontos(
            $record['fecha_comprobante'],
            '',
            $record['url_archivo']
        );
    }
}
