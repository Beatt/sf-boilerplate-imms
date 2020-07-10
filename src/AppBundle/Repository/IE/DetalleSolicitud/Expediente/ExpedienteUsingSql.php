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
        $comprobantesPago = $this->getComprobantesPago($solicitudId);
        $facturas = $this->getFacturas($solicitudId);

        return new Documents(
            $oficioMonto,
            $comprobantesPago,
            $facturas
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

    private function getComprobantesPago(SolicitudId $solicitudId)
    {
        $statement = $this->connection->prepare(
            '
            SELECT comprobante_pago,
                   fecha_pago
            FROM solicitud
              JOIN pago
                ON solicitud.id = pago.solicitud_id AND
                solicitud.referencia_bancaria = pago.referencia_bancaria
            WHERE solicitud.id = :id
        ');

        $statement->execute([
            'id' => $solicitudId->asInt()
        ]);

        $records = $statement->fetchAll();

        return array_map(function (array $record) {
            return new ComprobantePagoInterface(
                $record['fecha_pago'],
                '',
                $record['comprobante_pago']
            );
        }, $records);
    }

    private function getFacturas(SolicitudId $solicitudId)
    {
        $statement = $this->connection->prepare(
            '
            SELECT fecha_facturacion,
                   zip
            FROM solicitud
              JOIN pago
                ON solicitud.id = pago.solicitud_id
              JOIN factura
                ON pago.factura_id = factura.id
            WHERE solicitud.id = :id AND
            solicitud.referencia_bancaria = pago.referencia_bancaria
        ');

        $statement->execute([
            'id' => $solicitudId->asInt()
        ]);

        $records = $statement->fetchAll();

        return array_map(function (array $record) {
            return new Factura(
                $record['fecha_facturacion'],
                '',
                $record['zip']
            );
        }, $records);
    }
}
