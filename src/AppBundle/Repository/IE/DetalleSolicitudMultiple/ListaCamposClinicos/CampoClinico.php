<?php

namespace AppBundle\Repository\IE\DetalleSolicitudMultiple\ListaCamposClinicos;

use AppBundle\Repository\IE\DetalleSolicitud\ListaCamposClinicos\CampoClinico as CampoClinicoBase;
use AppBundle\Repository\IE\DetalleSolicitud\ListaCamposClinicos\Convenio;
use AppBundle\Repository\IE\DetalleSolicitud\ListaCamposClinicos\Unidad;

final class CampoClinico extends CampoClinicoBase
{
    private $pago;

    private $estatus;

    private $referenciaBancaria;

    public function __construct(
        $id,
        Convenio $convenio,
        $lugaresSolicitados,
        $lugaresAutorizados,
        $fechaInicial,
        $fechaFinal,
        Unidad $unidad,
        $noSemanas,
        Pago $pago,
        $estatus,
        $referenciaBancaria
    ) {
        parent::__construct(
            $id,
            $convenio,
            $lugaresSolicitados,
            $lugaresAutorizados,
            $fechaInicial,
            $fechaFinal,
            $unidad,
            $noSemanas
        );

        $this->pago = $pago;
        $this->estatus = $estatus;
        $this->referenciaBancaria = $referenciaBancaria;
    }

    /**
     * @return Pago
     */
    public function getPago()
    {
        return $this->pago;
    }

    /**
     * @return string
     */
    public function getEstatus()
    {
        return $this->estatus;
    }

    /**
     * @return string
     */
    public function getReferenciaBancaria()
    {
        return $this->referenciaBancaria;
    }
}
