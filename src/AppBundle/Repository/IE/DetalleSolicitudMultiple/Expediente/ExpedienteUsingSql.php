<?php

namespace AppBundle\Repository\IE\DetalleSolicitudMultiple\Expediente;

use AppBundle\ObjectValues\SolicitudId;
use AppBundle\Repository\IE\DetalleSolicitud\Expediente\AbstractExpediente;
use AppBundle\Repository\IE\DetalleSolicitud\Expediente\ComprobantePago;
use AppBundle\Repository\IE\DetalleSolicitud\Expediente\Documents;
use AppBundle\Repository\IE\DetalleSolicitud\Expediente\FormatosFofoe;
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
        $formatosFOFOE = $this->getFormatosFofoe($solicitudId);

        return new Documents(
            $oficioMonto,
            $comprobantesPago,
            [],
            $formatosFOFOE
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
                   monto_colegiatura,
                   monto_carrera.id as monto_carrera_id
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
                "%s %s: Inscripción $%s, Colegiatura: $%s\n%s",
                $record['nombre_nivel_academico'],
                $record['nombre_carrera'],
                $record['monto_inscripcion'],
                $record['monto_colegiatura'],
                $this->getDescuentos($record['monto_carrera_id'])
            );
        }, $montosCarreraRecord);

        return implode('. ', $items);
    }


    private function getDescuentos($monto_carrera_id) {
        $statement = $this->connection->prepare('
            SELECT num_alumnos,
                   descuento_inscripcion,
                   descuento_colegiatura
            FROM descuento_monto
            WHERE monto_carrera_id = :id
        ');
        $statement->execute([
            'id' => $monto_carrera_id
        ]);
        $descuentosRecord = $statement->fetchAll();
        $itemsDesc = array_map(function (array $record) {
            $descInsc = $record['descuento_inscripcion'];
            $descInsc = is_numeric($descInsc) && $descInsc != '0' ? (float)$descInsc : 0;
            $descCol = $record['descuento_colegiatura'];
            $descCol = is_numeric($descCol) && $descCol != '0' ? (float)$descCol : 0;
            return sprintf(
                " %s alumno(s) con descuento de %s \n",
                $record['num_alumnos'],
                ($descInsc > 0 ? $descInsc.'% inscripción,' : '')
                .($descCol > 0 ? $descCol.'% colegiatura' : '')
            );
        }, $descuentosRecord);

        return implode('; ', $itemsDesc);
    }

    private function getComprobantesPago(SolicitudId $solicitudId)
    {
        $statement = $this->connection->prepare('
            SELECT campo_clinico.id,
                   unidad.nombre AS nombre_unidad,
                   pago.comprobante_pago,
                   pago.fecha_creacion,
                   pago.referencia_bancaria,
                   pago.monto,
                   pago.id AS pago_id
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
              AND pago.fecha_pago IS NOT NULL
            ORDER BY unidad.nombre
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
                    'unidad' => $record['nombre_unidad'],
                    'pagoId' => $record['pago_id'],
                    'campoClinicoId' => $record['id'],
                    'referenciaBancaria' => $record['referencia_bancaria']
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
