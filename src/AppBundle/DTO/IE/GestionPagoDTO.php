<?php

namespace AppBundle\DTO\IE;

use AppBundle\DTO\IE\GestionPago\CampoClinicoDTO;
use AppBundle\DTO\IE\GestionPago\PagoDTO;
use AppBundle\DTO\IE\GestionPago\UltimoPagoDTO;
use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\Pago;
use AppBundle\Entity\SolicitudTipoPagoInterface;
use AppBundle\Repository\PagoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;

class GestionPagoDTO implements GestionPagoDTOInterface
{
    private $solicitud;

    /** @var CampoClinico campoClinico */
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
        $referenciaBancaria = null;
        if($this->solicitud->isPagoUnico()) {
            $referenciaBancaria = $this->solicitud->getReferenciaBancaria();
        } else {
            $referenciaBancaria = $this->campoClinico->getReferenciaBancaria();
        }

        /** @var Criteria $criteria */
        $criteria = PagoRepository::getUltimoPagoByCriteria($referenciaBancaria);

        /** @var Collection $result */
        $result = $this->solicitud->getPagos()->matching($criteria);

        return new UltimoPagoDTO($result->first());
    }

    public function getMontoTotal()
    {
        return $this->solicitud->isPagoUnico() ?
            $this->solicitud->getMonto() :
            $this->campoClinico->getMonto();
    }

    public function getIdInstitucion()
    {
        return $this->solicitud->getInstitucion()->getId();
    }

    public function getMontoTotalPorPagar()
    {
        $pagos = null;
        if($this->solicitud->isPagoUnico()) {
            $pagos = $this->solicitud->getPagos();
        } else {
            $pagos = $this->campoClinico->getPagos();
        }

        $amountCarry = array_reduce(
            $pagos->toArray(),
            function ($carry, Pago $pago) {
                $carry += intval($pago->getMonto());
                return $carry;
        });

        return $this->getMonto() - $amountCarry;
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

    /**
     * @return float
     */
    protected function getMonto()
    {
        return $this->solicitud->isPagoUnico() ?
            $this->solicitud->getMonto() :
            $this->campoClinico->getMonto();
    }
}
