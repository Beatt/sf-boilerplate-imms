<?php

namespace AppBundle\Repository\IE\DetalleSolicitud\TotalCamposClinicosAutorizados;

use AppBundle\ObjectValues\SolicitudId;
use Doctrine\DBAL\Driver\Connection;

class TotalCamposClinicosUsingSql implements TotalCamposClinicos
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function totalCamposClinicosAutorizados(SolicitudId $solicitudId)
    {
        $statement = $this->connection->prepare(
            'SELECT COUNT(campo_clinico.id) AS total
                FROM campo_clinico
                WHERE campo_clinico.solicitud_id = :id
                AND campo_clinico.lugares_autorizados > 0'
        );

        $statement->execute([
            'id' => $solicitudId->asInt()
        ]);

        $total = $statement->fetchColumn();

        return new TotalCamposClinicosAutorizados($total);
    }
}
