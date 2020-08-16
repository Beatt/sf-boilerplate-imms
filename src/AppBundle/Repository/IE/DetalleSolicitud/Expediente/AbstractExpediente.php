<?php

namespace AppBundle\Repository\IE\DetalleSolicitud\Expediente;

abstract class AbstractExpediente implements Expediente
{
    /**
     * @param array $record
     * @return string
     */
    protected function getDescripcion(array $record)
    {
        $descripcion = [];
        array_push(
            $descripcion,
            sprintf('Monto: $%s', $record['monto'])
        );
        return implode(', ', $descripcion);
    }

    /**
     * @param $estatus
     * @return FormatosFofoe|null
     */
    protected function createFormatosFofoe($estatus)
    {
        $estatusParaMostrarFormatosFofoe = [
            'Montos validados CAME',
            'Formatos de pago generados',
            'Cargando comprobantes',
            'En validación FOFOE',
            'Credenciales generadas'
        ];

        if(!in_array($estatus, $estatusParaMostrarFormatosFofoe)) return null;

        return new FormatosFofoe(
            null,
            'Formatos de pago FOFOE',
            ''
        );
    }
}
