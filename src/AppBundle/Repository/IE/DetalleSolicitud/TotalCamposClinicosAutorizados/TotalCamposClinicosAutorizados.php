<?php

namespace AppBundle\Repository\IE\DetalleSolicitud\TotalCamposClinicosAutorizados;

class TotalCamposClinicosAutorizados
{
    private $total;

    public function __construct($total)
    {
        $this->total = $total;
    }

    /**
     * @return string
     */
    public function getTotal()
    {
        return $this->total;
    }
}
