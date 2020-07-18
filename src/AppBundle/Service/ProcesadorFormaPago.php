<?php

namespace AppBundle\Service;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\Pago;
use AppBundle\Entity\ReferenciaBancariaInterface;
use AppBundle\Entity\Solicitud;
use AppBundle\Entity\SolicitudInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class ProcesadorFormaPago implements ProcesadorFormaPagoInterface
{
    private $entityManager;

    private $generadorReferenciaBancaria;

    public function __construct(
        EntityManagerInterface $entityManager,
        GeneradorReferenciaBancariaInterface $generadorReferenciaBancaria
    ) {
        $this->entityManager = $entityManager;
        $this->generadorReferenciaBancaria = $generadorReferenciaBancaria;
    }

    public function procesar(Solicitud $solicitud)
    {
        if($solicitud->getEstatus() !== SolicitudInterface::MONTOS_VALIDADOS_CAME) {
            throw new \Exception('Asignación de tipo de pago no permitida');
        }

        if($this->isPagoUnico($solicitud)) {
            $pago = $this->createPago(
                $solicitud,
                $this->getMontoTotal($solicitud->getCamposClinicos())
            );
            $this->setReferenciaPago($pago, $solicitud);
        }
        elseif($this->isPagoMultiple($solicitud)) {
            /** @var CampoClinico $camposClinico */
            foreach($solicitud->getCamposClinicos() as $camposClinico) {

                $pago = $this->createPago(
                    $solicitud,
                    $camposClinico->getMonto()
                );

                $this->setReferenciaPago(
                    $pago,
                    $camposClinico
                );

                $this->entityManager->persist($camposClinico);
            }
        }

        $solicitud->setEstatus(SolicitudInterface::FORMATOS_DE_PAGO_GENERADOS);
        $this->entityManager->flush();
    }

    /**
     * @param Solicitud $solicitud
     * @return bool
     */
    private function isPagoUnico(Solicitud $solicitud)
    {
        return $solicitud->getTipoPago() === Solicitud::TIPO_PAGO_UNICO;
    }

    /**
     * @param Collection $camposClinicos
     * @return float|int
     */
    private function getMontoTotal(Collection $camposClinicos)
    {
        $total = 0;

        /** @var CampoClinico $camposClinico */
        foreach($camposClinicos as $camposClinico) $total += $camposClinico->getMonto();

        return $total;
    }

    /**
     * @param Solicitud $solicitud
     * @param $monto
     * @param bool $requireFactura
     * @return Pago
     */
    private function createPago(Solicitud $solicitud, $monto, $requireFactura = true)
    {
        $pago = new Pago();
        $pago->setSolicitud($solicitud);
        $pago->setMonto($monto);
        $pago->setRequiereFactura($requireFactura);
        $this->entityManager->persist($pago);
        return $pago;
    }

    /**
     * @param Pago $pago
     * @param ReferenciaBancariaInterface $referenciaBancaria
     */
    private function setReferenciaPago(Pago $pago, ReferenciaBancariaInterface $referenciaBancaria)
    {
        $referenciaBancariaResult = $this->generadorReferenciaBancaria->makeReferenciaBancaria($pago, $referenciaBancaria->getId());
        $pago->setReferenciaBancaria($referenciaBancariaResult);
        $referenciaBancaria->setReferenciaBancaria($referenciaBancariaResult);
    }

    /**
     * @param Solicitud $solicitud
     * @return bool
     */
    protected function isPagoMultiple(Solicitud $solicitud)
    {
        return $solicitud->getTipoPago() === Solicitud::TIPO_PAGO_MULTIPLE;
    }
}
