<?php

namespace AppBundle\Repository\GetExistSolicitud;

use AppBundle\Exception\ErrorDBALException;
use AppBundle\ObjectValues\SolicitudId;
use AppBundle\ObjectValues\UsuarioId;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;

class GetExistSolicitudUsingSql implements GetExistSolicitud
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function ofUsuario(SolicitudId $solicitudId, UsuarioId $usuarioId)
    {
        $record = null;

        try {

            $stmt = $this->entityManager->getConnection()->prepare("  
                SELECT EXISTS(
                  SELECT *
                  FROM (
                    SELECT DISTINCT campo_clinico.solicitud_id
                    FROM usuario
                    JOIN institucion
                      ON usuario.id = institucion.usuario_id
                    JOIN convenio
                      ON institucion.id = convenio.institucion_id
                    JOIN campo_clinico
                      ON convenio.id = campo_clinico.convenio_id
                    WHERE institucion.id = :institucionId
                  ) AS solicitudes_id
                  WHERE solicitud_id IN (:solicitudId)
                )   
            ");

            $stmt->execute([
                'institucionId' => $usuarioId->asInt(),
                'solicitudId' => $solicitudId->asInt(),
            ]);

            $record = $stmt->fetchColumn();

        } catch (DBALException $e) {
            throw ErrorDBALException::withExistSolicitud($solicitudId);
        }

        return new Solicitud($record);
    }
}
