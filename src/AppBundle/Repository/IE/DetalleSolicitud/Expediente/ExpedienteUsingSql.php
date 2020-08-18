<?php

namespace AppBundle\Repository\IE\DetalleSolicitud\Expediente;

use AppBundle\ObjectValues\SolicitudId;
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
        $facturas = $this->getFacturas($solicitudId);
        $formatosFofoe = $this->getFormatosFofoe($solicitudId);

        return new Documents(
            $oficioMonto,
            $comprobantesPago,
            $facturas,
            $formatosFofoe
        );
    }

    /**
     * @param SolicitudId $solicitudId
     * @return OficioMontos
     */
    private function getOficioMonto(SolicitudId $solicitudId)
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
                "%s %s: Inscripción $%s, Colegiatura: $%s\n",
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
        $statement = $this->connection->prepare(
            '
            SELECT pago.comprobante_pago,
                   pago.fecha_creacion,
                   pago.monto,
                   solicitud.referencia_bancaria,
                   pago.requiere_factura,
                   pago.id AS pago_id
            FROM solicitud
            JOIN pago
              ON solicitud.id = pago.solicitud_id 
            WHERE solicitud.id = :id
                AND solicitud.referencia_bancaria = pago.referencia_bancaria
                AND pago.fecha_pago IS NOT NULL
        ');

        $statement->execute([
            'id' => $solicitudId->asInt()
        ]);

        $records = $statement->fetchAll();

        return array_map(function (array $record) use ($solicitudId) {

            return new ComprobantePago(
                $record['fecha_creacion'],
                $this->getDescripcion($record),
                $record['comprobante_pago'],
                [
                    'pagoId' => $record['pago_id'],
                    'referenciaBancaria' => $record['referencia_bancaria']
                ]
            );
        }, $records);
    }

    private function getFacturas(SolicitudId $solicitudId)
    {
        $statement = $this->connection->prepare(
            '
            SELECT fecha_facturacion,
                   zip,
                   folio,
                   factura.monto AS factura_monto,
                   factura.id AS factura_id
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
                sprintf('Folio: %s, Monto: $%s',
                    $record['folio'],
                    $record['factura_monto']
                ),
                $record['zip'],
                [
                    'facturaId' => $record['factura_id']
                ]
            );
        }, $records);
    }

    /**
     * @param SolicitudId $solicitudId
     * @return FormatosFofoe
     */
    private function getFormatosFofoe(SolicitudId $solicitudId)
    {
        $statement = $this->connection->prepare('
            SELECT estatus
            FROM solicitud
            WHERE solicitud.id = :id;
        ');

        $statement->execute([
            'id' => $solicitudId->asInt()
        ]);

        $estatus = $statement->fetchColumn();
        return $this->createFormatosFofoe($estatus);
    }
}
