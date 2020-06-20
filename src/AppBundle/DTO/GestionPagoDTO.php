<?php

namespace AppBundle\DTO;

use AppBundle\DTO\GestionPago\PagoDTO;
use AppBundle\DTO\GestionPago\UltimoPagoDTO;
use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\Pago;
use AppBundle\Entity\Solicitud;
use Doctrine\Common\Collections\ArrayCollection;

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
        return new UltimoPagoDTO($this->solicitud->getPagos()->first());
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

        return array_reduce(
            $campoClinico->getPagos()->toArray(),
            function ($carry, Pago $pago) {
                $carry .= $pago->getMonto();
            return $carry;
        });
    }
}
