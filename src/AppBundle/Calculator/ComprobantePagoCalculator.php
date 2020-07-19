<?php

namespace AppBundle\Calculator;

use AppBundle\Entity\Pago;
use AppBundle\Repository\CampoClinicoRepository;
use AppBundle\Repository\CampoClinicoRepositoryInterface;
use AppBundle\Repository\PagoRepositoryInterface;

class ComprobantePagoCalculator implements ComprobantePagoCalculatorInterface
{
    private $pagoRepository;

    private $campoClinicoRepository;

    public function __construct(
        PagoRepositoryInterface $pagoRepository,
        CampoClinicoRepositoryInterface $campoClinicoRepository
    ) {
        $this->pagoRepository = $pagoRepository;
        $this->campoClinicoRepository = $campoClinicoRepository;
    }

    public function getMontoAPagar(Pago $pago)
    {
        $comprobantesPago = $this
            ->pagoRepository
            ->getComprobantesPagoByReferenciaBancaria(
                $pago->getReferenciaBancaria()
            );

        $amount = $this->getSubTotal($comprobantesPago);
        if(!$amount) return $this->getPrecio($pago);

        return $this->getPrecio($pago) - $amount;
    }

    /**
     * @param Pago $pago
     * @return int
     */
    private function getPrecio(Pago $pago)
    {
        $solicitud = $pago->getSolicitud();
        if ($solicitud->isPagoUnico()) return $solicitud->getMonto();

        $campoClinico = $solicitud
            ->getCamposClinicos()
            ->matching(
                CampoClinicoRepository::getCampoClinicoByReferenciaBancaria(
                    $pago->getReferenciaBancaria()
                )
            )->first();

        return $campoClinico->getMonto();
    }

    /**
     * @param $comprobantesPago
     * @return mixed|null
     */
    private function getSubTotal($comprobantesPago)
    {
        return array_reduce(
            $comprobantesPago,
            function ($carry, Pago $pago) {
                if ($pago->getFechaCreacion() === null) return $carry;
                $carry += intval($pago->getMonto());
                return $carry;
            });
    }
}
