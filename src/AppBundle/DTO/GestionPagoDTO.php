<?php

namespace AppBundle\DTO;

use AppBundle\DTO\GestionPago\CampoClinicoDTO;
use AppBundle\DTO\GestionPago\PagoDTO;
use AppBundle\DTO\GestionPago\UltimoPagoDTO;
use AppBundle\Entity\Pago;
use AppBundle\Entity\SolicitudTipoPagoInterface;
use AppBundle\Repository\PagoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;

class GestionPagoDTO implements GestionPagoDTOInterface
{
    private $solicitud;

    private $campoClinico;

    private $pago;

    private function __construct(Pago $pago)
    {
        $this->pago = $pago;
        $this->solicitud = $pago->getSolicitud();
        $this->campoClinico = $this->solicitud->getCampoClinicoByReferenciaBancaria($this->pago->getReferenciaBancaria());
    }

    public static function create(Pago $pago)
    {
        return new GestionPagoDTO($pago);
    }

    public function getPagos()
    {
        $pagos = new ArrayCollection();

        foreach($this->solicitud->getPagosByReferenciaBancaria($this->pago->getReferenciaBancaria()) as $pago) {
            $pagos->add(new PagoDTO($pago));
        }

        return $pagos;
    }

    public function getUltimoPago()
    {
        /** @var Criteria $criteria */
        $criteria = PagoRepository::getUltimoPagoByCriteria($this->campoClinico->getReferenciaBancaria());

        /** @var Collection $result */
        $result = $this->solicitud->getPagos()->matching($criteria);

        return new UltimoPagoDTO($result->first());
    }

    public function getMontoTotal()
    {
        return $this->campoClinico->getMonto();
    }

    public function getNombreInstitucion()
    {
        return $this->solicitud->getInstitucion()->getNombre();
    }

    public function getMontoTotalPorPagar()
    {
        $amountCarry = array_reduce(
            $this->campoClinico->getPagos()->toArray(),
            function ($carry, Pago $pago) {
                if($pago->isValidado()) $carry += intval($pago->getMonto());
            return $carry;
        });

        return $this->campoClinico->getMonto() - $amountCarry;
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


        return new CampoClinicoDTO($this->campoClinico);
    }
}
