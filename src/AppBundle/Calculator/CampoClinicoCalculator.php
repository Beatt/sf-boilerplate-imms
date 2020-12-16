<?php

namespace AppBundle\Calculator;

use AppBundle\Entity\Solicitud;
use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\DescuentoMonto;
use AppBundle\Entity\MontoCarrera;

class CampoClinicoCalculator implements CampoClinicoCalculatorInterface {

    public function getMontoAPagar(CampoClinico $campo, Solicitud $solicitud)
    {
        $idCarrera = $campo->getConvenio()->getCarrera()->getId();
        $montos = $solicitud->getMontosCarreras()->filter(function( MontoCarrera $montoC) use ($idCarrera) {
            return $montoC->getCarrera()->getId() === $idCarrera;
        });
        $monto = $montos[0];

        $descuentos = $monto->getDescuentos();
        $totalAlumnosCobrados = 0;

        $idCicloAcademico = $campo->getConvenio()->getCicloAcademico()->getId();
        $numSemanas = $idCicloAcademico === 1 ? $campo->getWeeks() : 1;

        $montoTotal = 0.0;
        /** @var DescuentoMonto $descuento */
        foreach ($descuentos as $descuento) {
            $alumnosDescuento =min($campo->getLugaresAutorizados()-$totalAlumnosCobrados,
                $descuento->getNumAlumnos());

            $subTotal1 = $this->getSubtotalCAI($monto, $descuento);
            $subTotal2 = $subTotal1*( $idCicloAcademico === 1 ? 0.005 : .50);
            $montoTotal += $alumnosDescuento*$subTotal2*$numSemanas;

            $totalAlumnosCobrados += $alumnosDescuento;

        }
        $alumnosSinDescuento = max($campo->getLugaresAutorizados()-$totalAlumnosCobrados, 0);
        $subTotal1 = $this->getSubtotalCAI($monto);
        $subTotal2 = $subTotal1*( $idCicloAcademico === 1 ? 0.005 : .50);

        $montoTotal += $alumnosSinDescuento*$subTotal2*$numSemanas;

        return $montoTotal;
    }

    private function getSubtotalCAI(MontoCarrera $montoCarrera,  DescuentoMonto $descuento=null) {
        $descIns = $descuento ? $this->validaPorcentaje($descuento->getDescuentoInscripcion()) : 0;
        $descCol = $descuento ? $this->validaPorcentaje($descuento->getDescuentoColegiatura()) : 0;

        return
            $montoCarrera->getMontoInscripcion()*((100-$descIns)/100.0)
            + $montoCarrera->getMontoColegiatura()*((100-$descCol)/100.0);
    }

    /**
     * @param int $monto
     * @return float
     */
    private function validaPorcentaje($monto) {
        $res = $monto < 0 ? 0.0 : $monto;
        $res = $monto > 100 ? 100.0 : $monto;
        return $res;
    }
}