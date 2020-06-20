<?php

namespace AppBundle\DTO;

use AppBundle\DTO\GestionPago\CampoClinicoDTO;
use AppBundle\DTO\GestionPago\PagoDTO;
use AppBundle\DTO\GestionPago\UltimoPagoDTO;
use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\Pago;
use AppBundle\Entity\Solicitud;
use AppBundle\Entity\SolicitudTipoPagoInterface;
use AppBundle\Repository\PagoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;

class GestionPagoDTO implements GestionPagoDTOInterface
{
    private $solicitud;

    private function __construct(Solicitud $solicitud)
    {
        $this->solicitud = $solicitud;
    }

    public static function fromSolicitud(Solicitud $solicitud)
    {
        return new GestionPagoDTO($solicitud);
    }

    public function getPagos()
    {
        $pagos = new ArrayCollection();

        foreach($this->solicitud->getPagos() as $pago) {
            $pagos->add(new PagoDTO($pago));
        }

        return $pagos;
    }

    public function getUltimoPago()
    {
        /** @var CampoClinico $campoClinico */
        $campoClinico = $this->solicitud->getCamposClinicos()->first();
        /** @var Criteria $criteria */
        $criteria = PagoRepository::getUltimoPagoByCriteria($campoClinico->getReferenciaBancaria());

        /** @var Collection $result */
        $result = $this->solicitud->getPagos()->matching($criteria);

        return new UltimoPagoDTO($result->first());
    }

    public function getMontoTotal()
    {
        return $this->solicitud->getCamposClinicos()->first()->getMonto();
    }

    public function getNombreInstitucion()
    {
        return $this->solicitud->getInstitucion()->getNombre();
    }

    public function getMontoTotalPorPagar()
    {
        /** @var CampoClinico $campoClinico */
        $campoClinico = $this->solicitud->getCamposClinicos()->first();

        $amountCarry = array_reduce(
            $campoClinico->getPagos()->toArray(),
            function ($carry, Pago $pago) {
                if($pago->isValidado()) $carry += intval($pago->getMonto());
            return $carry;
        });

        return $campoClinico->getMonto() - $amountCarry;
    }

    public function getNoSolicitud()
    {
        return $this->solicitud->getNoSolicitud();
    }

    public function getTipoPago()
    {
        return $this->solicitud->getTipoPago();
    }

    public function getCampoClinico()
    {
        if($this->solicitud->getTipoPago() === SolicitudTipoPagoInterface::TIPO_PAGO_UNICO) return null;

        /** @var CampoClinico $campoClinico */
        $campoClinico = $this->solicitud->getCamposClinicos()->first();

        return new CampoClinicoDTO($campoClinico);
    }
}
