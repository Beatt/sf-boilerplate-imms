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
        array_push(
            $descripcion,
            sprintf('Número de referencia: %s', $record['referencia_bancaria'])
        );

        return implode(', ', $descripcion);
    }
}
