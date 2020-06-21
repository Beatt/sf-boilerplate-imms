<?php

namespace AppBundle\DTO\IE;

use AppBundle\DTO\Entity\Solicitud;
use AppBundle\Entity\Solicitud as SolicitudBase;
use AppBundle\Entity\SolicitudInterface;
use AppBundle\Entity\SolicitudTipoPagoInterface;
use AppBundle\Repository\PagoRepository;

final class InicioDTO extends Solicitud implements InicioDTOInterface
{
    public function __construct(SolicitudBase $solicitud)
    {
        parent::__construct($solicitud);
    }

    public function getUltimoPago()
    {
        if(
            $this->estatus === SolicitudInterface::CARGANDO_COMPROBANTES &&
            $this->tipoPago === SolicitudTipoPagoInterface::TIPO_PAGO_UNICO
        ) {
            $criteria = PagoRepository::getPagosByReferenciaBancaria($this->referenciaBancaria);

            return $this->getPagos()
                ->matching($criteria)
                ->first()
                ->getId();
        }

        return null;
    }
}
