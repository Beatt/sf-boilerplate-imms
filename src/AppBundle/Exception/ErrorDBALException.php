<?php

namespace AppBundle\Exception;


use AppBundle\ObjectValues\SolicitudId;
use Doctrine\DBAL\DBALException;

final class ErrorDBALException extends DBALException
{
    public static function withExistSolicitud(SolicitudId $solicitudId)
    {
        return new self(sprintf('Hubo un error con la solicitud con id: %id', $solicitudId->asInt()));
    }
}
